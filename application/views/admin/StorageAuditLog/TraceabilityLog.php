<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    // .table-daily_report { 
        // overflow: auto;
        // max-height: 150vh;
        // width:100%;
        // position:relative;
        // top: 0px; 
    // }
    .table-daily_report thead th { 
        position: sticky; 
        top: 0; 
        z-index: 1; 
    }
    .table-daily_report tbody th { 
        position: sticky; 
        left: 0; 
    }
    
    table { 
        border-collapse: collapse; 
        width: 100%; 
    }
    th, td { 
        padding: 1px 5px !important; 
        white-space: nowrap; 
        border:1px solid !important;
        font-size:11px; 
        line-height:1.42857143!important;
        vertical-align: middle !important;
    }
    th { 
        background: #50607b;
        color: #fff !important; 
    }
</style>
<div id="wrapper">
	<div class="content">
	<div class="row">
	<div class="col-md-8">
		<div class="row">
			<div class="panel_s invoice accounting-template">				
				<div class="panel-body" style="padding-top:5px;">
					<div class="row">
						<div class="col-md-12">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
									<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
									<li class="breadcrumb-item active text-capitalize"><b>Transaction</b></li>
									<li class="breadcrumb-item active" aria-current="page"><b>Inventory / Traceability Log</b></li>
								</ol>
							</nav>
							<hr class="hr_style">							
						</div>
					</div>	
					<div class="row">
						<div class="col-md-3">								 
							<?php                               
								$fy = $this->session->userdata('finacial_year');
								$fy_new  = $fy + 1;
								$lastdate_date = '20'.$fy_new.'-03-31';
								$curr_date = date('Y-m-d');
								$curr_date_new    = new DateTime($curr_date);
								$last_date_yr = new DateTime($lastdate_date);
								if($last_date_yr < $curr_date_new){
									$date1 = $lastdate_date;
									} else {
									$date1 = _d(date('Y-m-d'));
								}								
							echo render_date_input('date','Date',$date1); ?>							
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="WarehouseID" class="control-label" ><small class="req text-danger">* </small> Warehouse Name</label>
								<select class="selectpicker" name="WarehouseID" id="WarehouseID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
									<option value=""></option>
									<?php
										foreach($WarehouseList as $key => $value) {
										?>
										<option value="<?php echo $value["VehicleID"]?>" ><?php echo $value["VehicleID"]?></option>
										 <?php
										}
									?>
									
								</select>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
								<label for="LocationID" class="control-label" ><small class="req text-danger">* </small> Location</label>
								<select class="selectpicker" name="LocationID" id="LocationID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit">Show</button>
						</div>

						<!-- <div class="col-md-6">
							<label for="Notes" class="control-label" >Notes</label>
							<div class="form-group">
								<textarea id="Notes" name="Notes" class="form-control"></textarea>
							</div>
						</div> -->
						
						
						<div class="clearfix"></div>
						<div class="col-md-12">
							<div class="table-daily_report tableFixHead2">
                            <table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
                                <thead>
                                    <tr>
                                        <th class="sortable">Product Name</th>
                                        <th class="sortable">Batch No</th>
                                        <th class="sortable">Issue Quantity</th>
										<th class="sortable">Production Quantity</th>
										<th class="sortable">Dispatched Quantity</th>
										<th class="sortable">Balance</th>
										<th class="sortable">Storage Location</th>
										<th class="sortable">Initials</th>
                                    </tr>
                                </thead>
                                <tbody>
									<!-- <tr>
										<td style="font-weight:700;text-align:left;">Entry Door</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="DoorCondition" id="DoorCondition"></textarea>
											</div>
										</td>
										<td>
											<select class="selectpicker" name="DoorActFound" id="DoorActFound" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												<option value=""></option>
												<option value="Y">Yes</option>
												<option value = "N">No</option>
											</select>
										</td>
										
										<td>
											<div class="form-group">
												<textarea class="form-control" name="DoorAction" id="DoorAction"></textarea>
											</div>
										</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="DoorInitial" id="DoorInitial"></textarea>
											</div>
										</td>
									</tr>
									<tr>
										<td style="font-weight:700;text-align:left;">Corners</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="CornersCondition" id="CornersCondition"></textarea>
											</div>
										</td>
										<td>
											<select class="selectpicker" name="CornersActFound" id="CornersActFound" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												<option value=""></option>
												<option value="Y">Yes</option>
												<option value = "N">No</option>
											</select>
										</td>

										<td>
											<div class="form-group">
												<textarea class="form-control" name="CornersAction" id="CornersAction"></textarea>
											</div>
										</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="CornerInitial" id="CornerInitial"></textarea>
											</div>
										</td>
									</tr>
									<tr>
										<td style="font-weight:700;text-align:left;">Storage Area</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="AreaCondition" id="AreaCondition"></textarea>
											</div>
										</td>
										<td>
											<select class="selectpicker" name="AreaActFound" id="AreaActFound" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												<option value=""></option>
												<option value="Y">Yes</option>
												<option value = "N">No</option>
											</select>
										</td>
										
										<td>
											<div class="form-group">
												<textarea class="form-control" name="AreaAction" id="AreaAction"></textarea>
											</div>
										</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="AreaInitial" id="AreaInitial"></textarea>
											</div>
										</td>
									</tr> -->
									
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
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
</div>
<?php init_tail(); ?>

<script type="text/javascript">
	$('#Temp,#Humidity').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});
</script>