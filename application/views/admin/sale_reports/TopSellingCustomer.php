<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content" >
	    <div class="row">
			<div class="col-md-12">
				<div class="panel_s">
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
				$from_date = date('01/m/Y');
				$to_date = date('d/m/Y');
			}
		?>
					<div class="panel-body">
						<div class="row">  
							<div class="col-md-2">
								<?php 
									echo render_date_input('from_date2','From Date',$from_date);          
								?>
							</div>
							<div class="col-md-2">
								<?php 
									echo render_date_input('to_date2','To Date',$to_date);          
								?>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="CustomerCount2">
									<label for="CustomerCount2" class="control-label">Max Count</label>
									<input type="text" id="CustomerCount2" onkeypress="return isNumber(event)" name="CustomerCount2" class="form-control" value="5">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="state2">
									<small class="req text-danger"></small>
									<label for="state2" class="form-label">State</label>
									<select name="state2" id="state2" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($state as $key => $value) {
											?>
											<option value="<?php echo $value['short_name'];?>"><?php echo $value['state_name'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-8" style="margin-top:10px;">
								<button class="btn btn-info pull-left mleft5 search_data2" id="search_data2"><?php echo _l('rate_filter'); ?></button>&nbsp
								<a class="btn btn-default buttons-excel buttons-html5" href="#" id="caexcel2"><span>Export</span></a>&nbsp;
							</div>
							<div class="col-md-4" style="margin-top:10px;">
								<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search .." title="Type in a name" style="float: right;width: 60%;">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
							    <span id="searchh2" style="display:none;">Please wait fetching data...</span>
						        <span id="searchhExport2" style="display:none;">please wait Exporting data...</span>
								<div class="table-daily_report tableFixHead2">
									<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report2" width="100%">
									</table>   
								</div>
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
    
    $('#ReportType').on('change',function(){
		var ReportType = $(this).val();
		if(ReportType == "Itemwise"){
			$('#ItemDiv').show();
			$('#SubgroupDiv').hide();
		}else{
			$('#SubgroupDiv').show();
			$('#ItemDiv').hide();
		}
		$('#SubGroup').val('');
		$('.selectpicker').selectpicker('refresh');
		$('#Items').val('');
		$('.selectpicker').selectpicker('refresh');
		$('#ItemCount').prop('disabled', false);
	});
	
	$('#Items,#SubGroup').on('change',function(){
		var SelectVal = $(this).val();
		// Check if the multiple select is empty
		if (SelectVal === null || SelectVal.length === 0) {
			$('#ItemCount').prop('disabled', false);
		} else {
			$('#ItemCount').prop('disabled', true);
		}
	});
    $('#search_data2').on('click',function(){
		var from_date2 = $("#from_date2").val();
		var to_date2 = $("#to_date2").val();
		var ChartType2 = $("#ChartType2").val();
		var CustomerCount2 = $("#CustomerCount2").val();
		var state2 = $("#state2").val();
		load_data2(from_date2,to_date2,ChartType2,CustomerCount2,state2);
	});
	
	function load_data2(from_date,to_date,ChartType,CustomerCount,state)
	{
		$.ajax({
			url:"<?php echo admin_url(); ?>sale_reports/GetTopSellingCustomer",
			dataType:"JSON",
			method:"POST",
			data:{from_date:from_date, to_date:to_date,ChartType:ChartType,CustomerCount:CustomerCount,state:state},
			beforeSend: function () {
				
				$('#searchh2').css('display','block');
				$('#table-daily_report2 tbody').css('display','none');
				
			},
			complete: function () {
				
				$('#table-daily_report2 tbody').css('display','');
				$('#searchh2').css('display','none');
			},
			success:function(returndata){
				$('#table-daily_report2').html(returndata.TableData);
			}
		});
	}
	
	$("#caexcel2").click(function(){
		var from_date = $("#from_date2").val();
		var to_date = $("#to_date2").val();
		var ChartType = $("#ChartType2").val();
		var CustomerCount = $("#CustomerCount2").val();
		var state = $("#state2").val();
		
	    $.ajax({
			url:"<?php echo admin_url(); ?>sale_reports/export_GetTopSellingCustomer",
			method:"POST",
			data:{from_date:from_date, to_date:to_date,ChartType:ChartType,CustomerCount:CustomerCount,state:state},
			beforeSend: function () {
				$('#searchhExport2').css('display','block');
			},
			complete: function () {
				$('#searchhExport2').css('display','none');
			},
			success:function(data){
				response = JSON.parse(data);
				window.location.href = response.site_url+response.filename;
			}
		});
	});
</script>
<script>
    function myFunction2() 
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.getElementById("table-daily_report2");
		tr = table.getElementsByTagName("tr");
		for (i = 2; i < tr.length; i++) 
		{
			tr[i].style.display = "none"; 
			td = tr[i].getElementsByTagName("td"); 
			for (j = 0; j < td.length; j++) {
				if (td[j]) {
					txtValue = td[j].textContent || td[j].innerText;                
					if (txtValue.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
						tr[i].style.display = "";  
						break; 
					}
				}
			}
		}
	}
	
	$(document).on("click", ".sortable2", function () {
		var table = $("#table-daily_report2 tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortable2").removeClass("asc desc");
		$(".sortable2 span").remove();
		
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
<style>
    .table-daily_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table-daily_report table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	.table-daily_report th     { background: #50607b;color: #fff !important; }
</style>