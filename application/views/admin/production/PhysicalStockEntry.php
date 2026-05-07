<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
	.table-daily_report tr:hover {
    background-color: #ccc;
	}
	
	.table-daily_report td:hover {
    cursor: pointer;
	} 
	#sl :hover {
    /*background-color: #ccc;*/
    cursor: pointer;
	}
	
	
</style>
<div id="wrapper">
	<div class="content">
        <div class="row" style="display:none;">
			<div class="col-md-12">
				<table id="print_table" style="border-bottom:none;">
					<thead>
						<tr>
							<th align="center" colspan="5"><?php echo $company_detail->company_name; ?></th>
						</tr>
						<tr>
							<th align="center" colspan="5"><?php echo $company_detail->address; ?></th>
						</tr>
					</thead>
					<tbody id="print_tablebody">
					</tbody>
					
				</table>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-8">
				<div class="panel_s">
					<div class="panel-body">
						
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Physical Stock Entry</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-2">
								<?php
									$selected_company = $this->session->userdata('root_company');
									$fy = $this->session->userdata('finacial_year');
									
									$new_TRNSNumber = get_option('next_physical_stock_number_for_cspl');
									$format = get_option('invoice_number_format');
									$prefix = "PSE".$fy;
									$_newTRNSNumber = str_pad($new_TRNSNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
								?>
								<div class="form-group">
									<label for="AccountID">EntryID</label>
									<input type="text" name="EntryID" id="EntryID" class="form-control EntryID" value="<?php echo $prefix.$_newTRNSNumber; ?>" readonly>
									
								</div>
							</div>
							
							<div class="col-md-3">
								
								<?php $value = (isset($order) ? _d(substr($order->Dispatchdate,0,16)) : date('d/m/Y H:00'));
								echo render_datetime_input('Date','Date',$value,array('readonly'=>true)); ?>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="control-label">Main Group</label>
									<select name="MainGroup" id="MainGroup" class="selectpicker" data-none-selected-text="None selected" data-width="100%" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($main_item_group as $key => $value) {
											?>
											<option value="<?php echo $value["id"];?>"><?php echo $value["name"];?></option>
											<?php
											}
										?>
										
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="control-label" for="SubGroup1">Sub-Group 1</label>
									<select class="selectpicker display-block" data-width="100%" id="SubGroup1" name="SubGroup1" data-none-selected-text="None selected">
										<option value="">None selected</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="searchh" style="display:none;">Please wait Deleting data...</div>
							<div class="searchh2" style="display:none;">Please wait fetching data...</div>
							<div class="searchh3" style="display:none;">Please wait Create new Stock entry...</div>
							<div class="searchh4" style="display:none;">Please wait update Stock entry...</div>
						</div>
						<div class="table-daily_report tableFixHead2">
							
							<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
								
								<thead>
									<tr>
										<th class="sortable" style="text-align:left;">Sr No.</th>
										<th style="text-align:left;">ItemID</th>
										<th class="sortable" style="text-align:left;">Item Name</th>
										<th class="sortable" style="text-align:left;">Stock</th>
										<th class="sortable" style="text-align:left; width:100px;">Physical Stock</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
						</div>
						
						<div class="row"> 
							<div class="col-md-12">
								<?php if (has_permission_new('PhysicalStockEntry', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn"  onclick="SaveData()"  style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
							</div>
						</div>
						
						
						
						<div class="clearfix"></div>
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
<!--new update -->


<script type="text/javascript">
	
	$(document).on('keypress', '.PhysicalQty', function (event) {
		if (event.which < 48 || event.which > 57) {
			event.preventDefault();
		}
		
	});
	
	$('#MainGroup').on('change', function() {
		var MainItemGroup = $(this).val();
		//alert(roleid);
		$('#table-daily_report tbody').html('');
		var url = "<?php echo base_url(); ?>admin/production/GetSubgroup1Data";
		jQuery.ajax({
			type: 'POST',
			url:url,
			data: {MainItemGroup: MainItemGroup},
			dataType:'json',
			success: function(data) {
				$("#SubGroup1").find('option').remove();
				$("#SubGroup1").selectpicker("refresh");
				$("#SubGroup1").append(new Option('None selected', ''));
				for (var i = 0; i < data.length; i++) {
					$("#SubGroup1").append(new Option(data[i].name, data[i].id));
				}
				$('.selectpicker').selectpicker('refresh');
			}
		});
	});
	$('#SubGroup1').on('change',function(){
		var Date = $("#Date").val();
		var MainGroup = $("#MainGroup").val();
		var SubGroup1 = $("#SubGroup1").val();
		
		$.ajax({
			url:"<?php echo admin_url(); ?>production/Get_ItemData_for_PhysicalStock",
			dataType:"JSON",
			method:"POST",
			data:{Date:Date, MainGroup:MainGroup,SubGroup1:SubGroup1},
			beforeSend: function () {
				
				$('.searchh2').css('display','block');
				$('#table-daily_report tbody').css('display','none');
				
			},
			complete: function () {
				
				$('#table-daily_report tbody').css('display','');
				$('.searchh2').css('display','none');
			},
			success:function(data){
				$('#table-daily_report tbody').html(data);
				
			}
		});
		
	});
	$('#Date').on('change',function(){
		var Date = $("#Date").val();
		var MainGroup = $("#MainGroup").val();
		var SubGroup1 = $("#SubGroup1").val();
		
		if(MainGroup !== '' && SubGroup1 !== ''){
			$('#SubGroup1').change();
		}
		
	});
	
	
	function SaveData() {
		var Date = $("#Date").val();
		var MainGroup = $("#MainGroup").val();
		var SubGroup1 = $("#SubGroup1").val();
		
		const rows = document.querySelectorAll('#table-daily_report tbody tr');
		var ItemData = [];
		for (const row of rows) {
			const ItemID = row.querySelector('.ItemID').textContent;
			const Erp_Stock = row.querySelector('.Erp_Stock').textContent;
			const PhysicalQty = row.querySelector('.PhysicalQty');
			
			if (PhysicalQty && ItemID  && Erp_Stock ) {
				
				if (PhysicalQty.value !== null && PhysicalQty.value !== '') 
				{
					var rowData = {
						ItemID: ItemID,
						Erp_Stock: Erp_Stock,
						Qty: PhysicalQty.value,
					};
					ItemData.push(rowData);
				}
				
				
			}
		}
		
		if (Date == '' || Date == null) {
			alert("Please enter Date");
			}else if (MainGroup == '' || MainGroup == null) {
			alert("Please Select Main Group");
			}else if (SubGroup1 == '' || SubGroup1 == null) {
			alert("Please Select Subgroup");
			}else if (ItemData.length === 0) {
			alert("Please add at least 1 item before submitting.");
			}else {
			if (ItemData.length > 0) {
				// Send the baking data via AJAX
				if(confirm('Are You Sure You Want To Proceed ?')){
					$.ajax({
						url: "<?=base_url()?>admin/production/SavePhysicalStock",
						type: 'POST',
						dataType: 'json', 
						data: JSON.stringify({            
							tabledata: ItemData,
							Date:Date,
							MainGroup:MainGroup,
							SubGroup1:SubGroup1,
						}),
						contentType: 'application/json',
						success: function(response) {
							if (response === true) {
								alert_float('success', 'Record created successfully...');
								} else {
								alert_float('warning', 'Something Went Wrong');
							}
							location.reload();
						},
						error: function(error) {
							// Handle error response
							alert('Something Went Wrong', error);
						}
					});
				}
				}else{
				alert('Please Select Atleast 1 Row');
			}
		}
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		
		$('#qty_val').on('keypress',function (event) {
			var unit = $('#Unit_val').val();
			if(unit == "Kgs"){
				if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
					event.preventDefault();
				}
				var input = $(this).val();
				if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3 )) {
					event.preventDefault();
				}
				}else if(unit == "Pcs"){
				event = (event) ? event : window.event;
				var charCode = (event.which) ? event.which : event.keyCode;
				if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					return false;
				}
				return true;
			}
		});
		
	});
	
</script>

<script>
    function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_Item_List");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
            if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						} else if(td2){
						txtValue = td2.textContent || td2.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
							}else if(td3){
							txtValue = td3.textContent || td3.innerText;
							if (txtValue.toUpperCase().indexOf(filter) > -1) {
								tr[i].style.display = "";
								}else{
								tr[i].style.display = "none";
							} 
						}
					}
				}    
			}
		}
	}
    
    function myFunction3() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput2");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_Trans_List");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
            if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						} else if(td2){
						txtValue = td2.textContent || td2.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
							}else if(td3){
							txtValue = td3.textContent || td3.innerText;
							if (txtValue.toUpperCase().indexOf(filter) > -1) {
								tr[i].style.display = "";
								}else{
								tr[i].style.display = "none";
							} 
						}
					}
				}    
			}
		}
	}
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_Trans_List tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortablePop").removeClass("asc desc");
		$(".sortablePop span").remove();
		
		// Add sort classes and arrows
		$(this).addClass(ascending ? "asc" : "desc");
		$(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
		
		rows.sort(function (a, b) {
			var valA = $(a).find("td").eq(index).text().trim();
			var valB = $(b).find("td").eq(index).text().trim();
			
			if ($.isNumeric(valA) && $.isNumeric(valB)) {
				return ascending ? valA - valB : valB - valA;
				} else {
				return ascending
                ? valA.localeCompare(valB)
                : valB.localeCompare(valA);
			}
		});
		table.append(rows);
	});
	
	$(document).on("click", ".sortablePop2", function () {
		var table = $("#table_Item_List tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortablePop2").removeClass("asc desc");
		$(".sortablePop2 span").remove();
		
		// Add sort classes and arrows
		$(this).addClass(ascending ? "asc" : "desc");
		$(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
		
		rows.sort(function (a, b) {
			var valA = $(a).find("td").eq(index).text().trim();
			var valB = $(b).find("td").eq(index).text().trim();
			
			if ($.isNumeric(valA) && $.isNumeric(valB)) {
				return ascending ? valA - valB : valB - valA;
				} else {
				return ascending
                ? valA.localeCompare(valB)
                : valB.localeCompare(valA);
			}
		});
		table.append(rows);
	});
</script>
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
		}
        return true;
	}
</script>

<script type="text/javascript">
	function printPage(){
		
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .print_item_h{ background: #505f7b;colr:#fff;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
		var print_data = stylesheet+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
</script>

<style>
    #table_Item_List td:hover {
	cursor: pointer;
    }
	#AccountID{
    text-transform: uppercase;
	}
	#table_Item_List tr:hover {
    background-color: #ccc;
	}
	#table_Trans_List tr:hover {
    background-color: #ccc;
	}
	#table_Trans_List td:hover {
	cursor: pointer;
    }
	
    .table-Item_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Item_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Item_List tbody th { position: sticky; left: 0; }
    
    .table-Trans_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Trans_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Trans_List tbody th { position: sticky; left: 0; }
    
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>



