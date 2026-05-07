<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
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
            					<li class="breadcrumb-item active text-capitalize"><b>E-Filling</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>E-Way Bill List</b></li>
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
									$from_date = "01/".date('m/Y');
									$to_date = date('d/m/Y');
								}
							?>     
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="from_date">
									<label for="from_date" class="control-label from_date_text">From Date</label>
									<div class="input-group date">
										<input type="text" id="from_date" name="from_date" class="form-control datepicker" value="<?php echo $from_date; ?>" autocomplete="off">
										<div class="input-group-addon">
											<i class="fa fa-calendar calendar-icon"></i>
										</div>
									</div>
								</div>
								
								<?php// echo render_date_input('from_date','FROM',$from_date);  ?>
							</div>
							
							<div class="col-md-2">
								<?php  //$to_date = date('d/m/Y');?>
								<div class="form-group" app-field-wrapper="to_date">
									<label for="to_date" class="control-label to_date_text">To Date</label>
									<div class="input-group date">
										<input type="text" id="to_date" name="to_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
										<div class="input-group-addon">
											<i class="fa fa-calendar calendar-icon"></i>
										</div>
									</div>
								</div>
								<?php //echo render_date_input('to_date','TO',$to_date); ?>
							</div>
							
							<div class="col-md-4">
							    <div class="custom_button" style="padding-top: 20px;">
							
    								<button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;">Show</button>
    								<!--<button class="btn btn-default pull-left mleft5 " href="javascript:void(0);"    onclick="printPage();" style="font-size:12px;">Print</button>
    								-->&nbsp;&nbsp;<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="production_report" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
    							</div>
							</div>
							<div class="col-md-4" style="padding-top: 20px;">
								<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search" title="Type in a name" style="float: right;">
							</div>
							<div class="clearfix"></div>
                            <div class="col-md-12">
                                <span id="searchh" style="display:none;">Please wait data fetching.....</span>
                                <span id="searchh2" style="display:none;">Please wait data Exporting.....</span>
                                <div class="EWayBill_report">
                                    <table class="table-striped table-bordered EWayBill_report" id="EWayBill_report" width="100%">
                                    <thead>
                                        <tr class="header">
                                            <th>Sr.No.</th>
                                            <th>Invoice Date</th>
                                            <th>Invoice No</th>
                                            <th>Consolidate E-Way Bill</th>
                                            <th>E-Way Bill No</th>
                                            <th>E-way Bill Date</th>
                                            <th>E-way Bill Valid</th>
                                            <th>OrderID</th>
                                            <th>ChallanID</th>
                                            <th>Party Name</th>
                                            <th>GSTIN</th>
                                            <th>State</th>
                                            <th>Bill Amt</th>
                                            <th>Status On Gst Portal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="TableBody"></tbody>
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
<style>
    .EWayBill_report { overflow: auto;max-height: 60vh;position:relative;top: 0px; }
	.EWayBill_report thead th { position: sticky; top: 0; z-index: 1; }
	.EWayBill_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.EWayBill_report table  { border-collapse: collapse; }
	.EWayBill_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.EWayBill_report th     { background: #50607b;color: #fff !important; }
	
	
</style>
<script>
    function myFunction2() 
    {
        const id = "EWayBill_report";
        const selector = `#${id} tr:not(.header)`;
        // Get all rows
        const trs = document.querySelectorAll(selector);
        let search = event.target.value.toLowerCase();

        trs.forEach((tr) => {
            // Get all cells in a row
            let tds = tr.querySelectorAll("td");
            // String that contains all td textContent from a row
            let str = Array.from(tds).map((td) => {
              return td.textContent.toLowerCase();
            }).join("");
            tr.style.display = (str.indexOf(search) > -1) ? "table-row" : "none";
        });
    }
$(document).ready(function(){
    $('#search_data').on('click',function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
        $.ajax({
			url:"<?php echo admin_url(); ?>E_filling/GetEWayBillReport",
			dataType:"JSON",
			method:"POST",
			cache: false,
			data:{from_date:from_date, to_date:to_date},
			beforeSend: function () {
				$('#searchh').css('display','block');
				$('#TableBody').css('display','none');
			},
			complete: function () {
				$('#TableBody').css('display','');
				$('#searchh').css('display','none');
			},
			success:function(data){
				$('#TableBody').html(data);
			}
		});
	});
	
	$("#caexcel").click(function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		$.ajax({
            url:"<?php echo admin_url(); ?>E_filling/ExportEWayBillReport",
            method:"POST",
            data:{from_date:from_date, to_date:to_date},
			beforeSend: function () {
                $('#searchh2').css('display','block');
			},
            complete: function () {
                $('#searchh2').css('display','none');
			},
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
			}
		});
	});
		
});
</script>