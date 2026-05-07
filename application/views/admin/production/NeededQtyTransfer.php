<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
    #export_table_to_excel_2          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	#export_table_to_excel_2 th { position: sticky; top: 0; z-index: 1; }
	#export_table_to_excel_2 th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
	.table-daily_report tr:hover {
    background-color: #ccc;
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
            					<li class="breadcrumb-item active text-capitalize"><b>Production</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Needed Qty Transfer</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						
						<?php if(has_permission_new('production_list','','view')){ ?>
							
							<hr class="hr-panel-heading" />
							<div class="col-md-12">
								<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
								
							</div>
						<?php } ?>
						
						<?php
							//print_r($company_detail);
						?>
						<div class="table-daily_report tableFixHead2">
							
							<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
								
								<thead>
									
									<tr>
										<th style="text-align:left;">Tag</th>
										<th class="sortable" style="text-align:left;">Sr. No. </th>
										<th class="sortable" style="text-align:left;">Production ID </th>
										
										<th class="sortable" style="text-align:left;">PRD Date</th>
										<th class="sortable" style="text-align:left;">Recipe Name</th>
										<th class="sortable" style="text-align:left;">Item Name</th>
										<th class="sortable" style="text-align:left;">Batch Qty</th>
										<th class="sortable" style="text-align:left;">STD FGQty</th>
										<th class="sortable" style="text-align:left;">Actual Qty</th>
										<th class="sortable" style="text-align:left;">Diff. Qty</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$i = 1; 
										foreach($PendingProduction as $value){ 
											$url = admin_url('production/production_order/' . $value['pro_order_id']); 
											$diff = $value['Finish_good_qty'] - $value['Finish_good_qty_new']; 
											
										?>
										<tr>
											<td><input type="checkbox" class="selected_ord_id" name="selected_ord_id" onclick="select_ord(this.value)" value="<?php echo $value['pro_order_id']; ?>"></td>
											<td><?php echo $i; ?></td>
											<td><a href="<?= $url;?>" target="_blank"><?php echo $value['pro_order_id']; ?></a></td>
											<td align="left"><?php echo _d(substr($value["TransDate"],0,10)); ?></td>
											<td><?php echo strtoupper($value['recipeID']); ?></td>
											<td><?php echo strtoupper($value['description']); ?></td>
											<td align="right"><?php echo $value['batch_qty']; ?></td>
											<td align="right"><?php echo $value['Finish_good_qty']; ?></td>
											<td align="right"><?php echo $value['Finish_good_qty_new']; ?></td>
											<td align="right"><?php echo number_format($diff,2,'.',''); ?></td>
										</tr>
										<?php 
											$i++; 
										} 
									?>
								</tbody>
							</table>   
						</div>
						<span id="searchh2" style="display:none;">Loading.....</span>
						
						<div class="row">
							<div class="col-md-4">
								<input type="text" id="myInput2" onkeyup="myFunction3()" placeholder="Search.." class="form-control" style="float: right;">
							</div>
							<div class="col-md-10">
								<div class="fixed_header1">
									<table class="table table-striped fixed_header1" id="export_table_to_excel_2" width="100%">
										<thead>
											<tr>
												<th align="center">ItemID</th>
												<th align="center">Item Name</th>
												<th align="center">Std. Qty</th>
												<th align="center">Req Qty</th>
												<th align="center">Rtn Qty</th>
												<th align="center">Extra Qty</th>
												<th align="center">Acctual qty</th>
												<th align="center">Stock Qty In Production Unit</th>
												<th align="center">Stock Qty In RMPM Store</th>
												<th align="center">Needed Qty</th>
												<th align="center">Transfer Qty</th>
												<th align="center">MesuredIn</th>
											</tr>
										</thead>
										
										<tbody >
										</tbody>
									</table>   
									<span id="searchh2" style="display:none;">
										Loading.....
									</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<?php if (has_permission_new('NeededQtyTransfer', '', 'edit')) { ?>
									<button type="button" class="btn btn-info pull-left mleft5 SaveBtn" style="margin-top: 19px;" id="search_data">Save</button>
								<?php } ?>  
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
<script>
	function select_ord(value){
		
        var selected = [];
        $('input[type=checkbox]:checked').each(function () {
			
			var value = $(this).attr("value");
			selected.push(value);
		});
        let selected_ids = selected.toString();
		
        $.ajax({
			url:"<?php echo admin_url(); ?>production/GetNeededQtyItemByOrderIds",
			dataType:"JSON",
			method:"POST",
			data:{selected_ids:selected_ids},
			beforeSend: function () {
				
				$('#searchh2').css('display','block');
				$('#export_table_to_excel_2 tbody').css('display','none');
				
			},
			complete: function () {
				
				$('#export_table_to_excel_2 tbody').css('display','');
				$('#searchh2').css('display','none');
			},
			success:function(data){
				$('#export_table_to_excel_2 tbody').html(data);
			}
		});
	}
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table-daily_report");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			td4 = tr[i].getElementsByTagName("td")[4];
			td5 = tr[i].getElementsByTagName("td")[5];
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
								}else if(td4){
								txtValue = td4.textContent || td4.innerText;
								if (txtValue.toUpperCase().indexOf(filter) > -1) {
									tr[i].style.display = "";
									
									}else if(td5){
									txtValue = td5.textContent || td5.innerText;
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
		}
	}
	function myFunction3() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput2");
		filter = input.value.toUpperCase();
		table = document.getElementById("export_table_to_excel_2");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			td4 = tr[i].getElementsByTagName("td")[4];
			td5 = tr[i].getElementsByTagName("td")[5];
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
								}else if(td4){
								txtValue = td4.textContent || td4.innerText;
								if (txtValue.toUpperCase().indexOf(filter) > -1) {
									tr[i].style.display = "";
									
									}else if(td5){
									txtValue = td5.textContent || td5.innerText;
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
		}
	}
	
	$(document).on("click", ".SaveBtn", function () {
		let ItemArray = [];
		let ii = 0;
		let stopExecution = false;
		$("#export_table_to_excel_2 tbody tr").each(function () {
			if (stopExecution) return false;
			let itemId   = $(this).find(".ItemID").val();
			let itemName = $(this).find("td:eq(1)").text();   // 2nd column = description
			let RMPMStock = $(this).find("td:eq(8)").text();   // 9th column = RMPM Unit Stock
			let pack     = $(this).find(".CaseQty").val();   
			let unit     = $(this).find("td:last").text();    // Last column = Unit
			let transQty = $(this).find(".TransQty").val();
			
			if (itemId && transQty > 0) {
				if (parseFloat(RMPMStock) >= transQty) {
					
					ItemArray[ii] = [];
					ItemArray[ii][0] = itemId;
					ItemArray[ii][1] = itemName.trim();
					ItemArray[ii][2] = pack.trim();
					ItemArray[ii][3] = unit.trim();
					ItemArray[ii][4] = transQty;
					ii++;
					}else{
					alert('Stock Not Available');
					$(this).find(".TransQty").focus(); // focus on invalid input
					stopExecution = true; // set flag to break loop
					return false; // break out of each() immediately
				}
			}
		});
		
		if (stopExecution) return;
		
		var ItemSerializedArr = JSON.stringify(ItemArray);
		if (ItemArray.length === 0) {
			alert("Please enter at least one valid stock transfer qty before saving.");
			return; // stop further execution
		}
		
		if(confirm('Are You Sure You Want to Proceed ?')){
			// AJAX request
			$.ajax({
				url: "<?php echo admin_url(); ?>production/SaveNeededStockTransfer",
				type: "POST",
				dataType: "json",
				data: { ItemSerializedArr: ItemSerializedArr },
				success: function (res) {
					if(res){
						alert_float('success',"Data saved successfully!");
						}else{
						alert('warning',"Something went wrong!");
					}
					location.reload();
				},
				error: function () {
					alert("Something went wrong!");
				}
			});
		}
	});
	
</script>


<script type="text/javascript">
	$(document).on('keypress', '.TransQty', function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});
</script>

<script type="text/javascript">
	
	$(document).on("click", ".sortable", function () {
		var table = $("#table-daily_report tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortable").removeClass("asc desc");
		$(".sortable span").remove();
		
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
    $(document).ready(function(){
		var maxEndDate = new Date('Y/m/d');
		var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
		
		var year = "20"+fin_y;
		var cur_y = new Date().getFullYear().toString().substr(-2);
		if(cur_y => fin_y){
			var year2 = parseInt(fin_y) + parseInt(1);
			var year2_new = "20"+year2;
			
			var e_dat = new Date(year2_new+'/03/31');
			
			var maxEndDate_new = e_dat;
			}else{
			var e_dat2 = new Date(year2+'/03/31');
			var maxEndDate_new = e_dat2;
		}
		
		var minStartDate = new Date(year, 03);
		$('#from_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
		
		$('#to_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false,
			showOtherMonths: false,
			pickTime: false,
            orientation: "left",
		});
		
	});
</script> 
</body>
</html>
