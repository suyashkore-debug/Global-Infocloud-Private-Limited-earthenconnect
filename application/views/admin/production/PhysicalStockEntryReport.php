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
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Physical Stock Entry Report</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<?php
								$fy = $this->session->userdata('finacial_year');
								$fy_new  = $fy + 1;
								$lastdate_date = '20'.$fy_new.'-03-31';
								$firstdate_date = '20'.$fy_new.'-04-01';
								$curr_date = date('Y-m-d');
								$curr_date_new    = new DateTime($curr_date);
								$last_date_yr = new DateTime($lastdate_date);
								if($last_date_yr < $curr_date_new){
									$to_date = '31/03/20'.$fy_new;
									$from_date = '01/03/20'.$fy_new;
									}else{
									$from_date = "01/".date('m')."/".date('Y');
									$to_date = date('d/m/Y');
								}
							?>
							<div class="col-md-2">
								<?php
									echo render_date_input('from_date','From Date',$from_date);
								?>
							</div>
							<div class="col-md-2">
								<?php
									echo render_date_input('to_date','To Date',$to_date);
								?>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<small class="req text-danger"> </small>
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
							
							<div class="col-md-2">
								<div class="form-group">
									<small class="req text-danger"> </small>
									<label class="control-label" for="SubGroup1">Sub-Group 1</label>
									<select class="selectpicker display-block" data-width="100%" id="SubGroup1" name="SubGroup1" data-none-selected-text="None selected">
										<option value="">None selected</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<small class="req text-danger"> </small>
									<label class="control-label">Created By</label>
									<select name="UserID" id="UserID" class="selectpicker" data-none-selected-text="None selected" data-width="100%" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($StockEntryStaff as $key => $value) {
											?>
											<option value="<?php echo $value["AccountID"];?>"><?php echo $value["AccountName"];?></option>
											<?php
											}
										?>
										
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
							</div>
						</div>
						
						
						<hr class="hr-panel-heading" />
							<div class="col-md-6">
								<div class="custom_button">
									&nbsp;<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
								</div>
							</div>
						<div class="table-daily_report tableFixHead2">
							
							<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
								
								<thead>
									<tr>
										<th class="sortable" style="text-align:left;">Sr No.</th>
										<th style="text-align:left;">EntryID</th>
										<th style="text-align:left;">Entry Date</th>
										<th style="text-align:left;">Item ID</th>
										<th class="sortable" style="text-align:left;">Item Name</th>
										<th class="sortable" style="text-align:left;">ERP Stock</th>
										<th class="sortable" style="text-align:left;">Physical Stock</th>
										<th class="sortable" style="text-align:left;">Discrepancy count</th>
										<th class="sortable" style="text-align:left;">Created By</th>
										<th class="sortable" style="text-align:left;">Created Date</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
							<div class="col-md-12">
							<div class="searchh" style="display:none;">Please wait Deleting data...</div>
							<div class="searchh2" id="searchh2" style="display:none;">Please wait fetching data...</div>
							<div class="searchh3" style="display:none;">Please wait Create new Stock entry...</div>
							<div class="searchh4" style="display:none;">Please wait update Stock entry...</div>
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
	$(document).ready(function(){
		
		function load_data(from_date,to_date,MainGroup,SubGroup1,UserID)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>production/GetPhysicalStockEntryData",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date,MainGroup:MainGroup,SubGroup1:SubGroup1,UserID:UserID},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('#table-daily_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('#table-daily_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					$('#table-daily_report tbody').html(data);
					
				}
			});
		}
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var MainGroup = $("#MainGroup").val();
			var SubGroup1 = $("#SubGroup1").val();
			var UserID = $("#UserID").val();
			
			load_data(from_date,to_date,MainGroup,SubGroup1,UserID);
			
		});
		
	});
	$('#MainGroup').on('change', function() {
		var MainItemGroup = $(this).val();
		//alert(roleid);
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
		var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">Physical Stock Entry Report</td>';
		heading_data += '</tr>';
		
		heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
	
	
	$("#caexcel").click(function(){
		
		var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var MainGroup = $("#MainGroup").val();
			var SubGroup1 = $("#SubGroup1").val();
			var UserID = $("#UserID").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>production/export_PhysicalStockEntryReport",
            method:"POST",
            data:{from_date:from_date, to_date:to_date,MainGroup:MainGroup,SubGroup1:SubGroup1,UserID:UserID},
            
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
			}
		});
	});
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



