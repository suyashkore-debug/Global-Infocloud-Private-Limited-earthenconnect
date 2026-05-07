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
									$current_date = date('d/m/Y');
									echo render_date_input('from_date','From Date',$from_date);          
								?>
							</div>
							<div class="col-md-2">
								<?php 
									echo render_date_input('to_date','To Date',$to_date);          
								?>
							</div>
							
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="ItemCount">
									<label for="ItemCount" class="control-label">Max Count</label>
									<input type="text" id="ItemCount" onkeypress="return isNumber(event)" name="ItemCount" class="form-control" value="5">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="state">
									<small class="req text-danger"></small>
									<label for="state" class="form-label">State</label>
									<select name="state" id="state" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
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
							<div class="clearfix"></div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="ReportType">
									<label for="ReportType" class="form-label">Report Type</label>
									<select name="ReportType" id="ReportType" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
										<option value="Itemwise">Item Wise</option>
										<option value="Groupwise">Group Wise</option>
									</select>
								</div>
							</div>
							<div class="col-md-3" id="ItemDiv">
								<div class="form-group" app-field-wrapper="Items">
									<small class="req text-danger"></small>
									<label for="Items" class="form-label">Item</label>
									<select name="Items[]" multiple id="Items" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<?php
											foreach ($ItemList as $key => $value) {
											?>
											<option value="<?php echo $value['item_code'];?>"><?php echo $value['description'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3" id="SubgroupDiv" style="display:none;">
								<div class="form-group" app-field-wrapper="SubGroup">
									<small class="req text-danger"></small>
									<label for="SubGroup" class="form-label">SubGroup</label>
									<select name="SubGroup[]" multiple id="SubGroup" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<?php
											foreach ($SubGroup as $key => $value) {
											?>
											<option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							
							
							<div class="col-md-3" style="margin-top:20px;">
								<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button> 
								&nbsp
								<a class="btn btn-default buttons-excel buttons-html5" href="#" id="caexcel"><span>Export</span></a>&nbsp;
							</div>
							<div class="col-md-4" style="margin-top:20px;">
								<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search .." title="Type in a name" style="float: right;width: 60%;">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
							    <span id="searchh" style="display:none;">Please wait fetching data...</span>
						        <span id="searchhExport" style="display:none;">please wait Exporting data...</span>
								<div class="table-daily_report tableFixHead2">
									<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
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
    $('#search_data').on('click',function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var ChartType = $("#ChartType").val();
		var ItemCount = $("#ItemCount").val();
		var SubGroup = $("#SubGroup").val();
		var state = $("#state").val();
		var ReportType = $("#ReportType").val();
		var Items = $("#Items").val();
		load_data(from_date,to_date,ChartType,ItemCount,state,SubGroup,ReportType,Items);
		
	});
	
	function load_data(from_date,to_date,ChartType,ItemCount,state,SubGroup,ReportType,Items)
	{
		$.ajax({
			url:"<?php echo admin_url(); ?>sale_reports/GetTopSellingItem",
			dataType:"JSON",
			method:"POST",
			data:{from_date:from_date, to_date:to_date,ChartType:ChartType,ItemCount:ItemCount,state:state,SubGroup:SubGroup,ReportType:ReportType,Items:Items},
			beforeSend: function () {
				$('#searchh').css('display','block');
				$('.table-daily_report tbody').css('display','none');
			},
			complete: function () {
				$('.table-daily_report tbody').css('display','');
				$('#searchh').css('display','none');
			},
			success:function(returndata){
				$('#table-daily_report').html(returndata.TableData);
			}
		});
	}
	
	$("#caexcel").click(function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var ChartType = $("#ChartType").val();
		var ItemCount = $("#ItemCount").val();
		var SubGroup = $("#SubGroup").val();
		var state = $("#state").val();
		var ReportType = $("#ReportType").val();
		var Items = $("#Items").val();
	    $.ajax({
			url:"<?php echo admin_url(); ?>sale_reports/export_GetTopSellingItem",
			method:"POST",
			data:{from_date:from_date, to_date:to_date,ChartType:ChartType,ItemCount:ItemCount,state:state,SubGroup:SubGroup,ReportType:ReportType,Items:Items},
			beforeSend: function () {
				$('#searchhExport').css('display','block');
			},
			complete: function () {
				$('#searchhExport').css('display','none');
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
        table = document.getElementById("table-daily_report");
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