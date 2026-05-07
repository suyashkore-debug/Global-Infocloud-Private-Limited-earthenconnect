<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content" >
	    <div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Dashboard</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
					    <div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
							<div class="_buttons">
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
										// $from_date = "01/".date('m')."/".date('Y');
										$from_date = date('d/m/Y');
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
								<div class="col-md-3">
									<button class="btn btn-info pull-left mleft5 search_data_counter" style="margin-top: 19px;" id="search_data_counter">Show</button>
								</div>
								
							</div>
							<div class="clearfix"></div>
							<div class="row" >  
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg1">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"> <?php echo _l('Total SKU'); ?><br>
											<span class="numstyl"><?php echo $ItemCount->TotalItem; ?></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo $ItemPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $ItemPercentage; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">
									<div class="top_stats_wrapper custdesg bg2">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-users"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Total Party'); ?><br>
											<span class="numstyl"><?php echo $CustomerCount->TotalCustomer; ?></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo $percent_total_customer; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_total_customer; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg3">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-user"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('New Parties'); ?><br>
											<span class="numstyl"><label id="NewPartiesNewParty"  class="labeltxt"> <?php echo $NewParties->NewParty; ?></label></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-dark no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo 100; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo 100; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">
									<div class="top_stats_wrapper custdesg bg4">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Total Sale'); ?><br>
											<span class="numstyl"><label id="TotalSaleByAnyParty"  class="labeltxt"><?php echo number_format(round(0), 2); ?></label></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-dark no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo $AllSaleReturn; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $AllSaleReturn; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg1">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l("Today's Sale"); ?><br>
											<span class="numstyl"> <?php echo number_format(round($TodaysSale->TotalSale), 2); ?></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-dark no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo 100; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo 100; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg2">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('GST Collection'); ?><br>
											<span class="numstyl"><label id="TotalGstAmtTotalGST"  class="labeltxt"> <?php echo number_format(round($TotalGstAmt->TotalGST), 2); ?></label></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-dark no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo 100; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo 100; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg3">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Average Order Amount'); ?><br>
											<span class="numstyl"> <label id="AvgInvoiceAmtAvgAmt"  class="labeltxt"><?php echo number_format(round($AvgInvoiceAmt->AvgAmt),2); ?></label></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-dark no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo 100; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo 100; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">
									<div class="top_stats_wrapper custdesg bg4">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Bestseller SKU'); ?>
												<span class="numstyl" style="font-size:9px;"><label id="TopSkuName" style="font-size:9px;" class="labeltxt"><?php echo $TopItem->description_name; ?></label></span>
											<span class="numstyl"><label id="TopItemTotalSale"  class="labeltxt"><?php echo number_format(round($TopItem->TotalSale), 2); ?></label></span></p>
											<div class="clearfix"></div>
											<!--<div class="progress no-margin progress-bar-mini">
												<div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php //echo $TopSaleReturn; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $TopSaleReturn; ?>">
												</div>
											</div>-->
										</div>
									</div>
								</div>
								  
								
								<div class="clearfix"></div>
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg1">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt">Fresh Sale Return<br>
											<span class="numstyl"> <label id="TotalSaleRtmAmtFreshRtn"  class="labeltxt"> <?php echo number_format(round($TotalSaleRtmAmt->FreshRtn),2); ?></label></span></p>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg2">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt">Damage Sale Return<br>
											<span class="numstyl"> <label id="TotalSaleRtmAmtDmgRtn"  class="labeltxt"> <?php echo number_format(round($TotalSaleRtmAmt->DmgRtn),2); ?></label></span></p>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg3">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt">Total Invoice Count<br>
											<span class="numstyl"> <label id="AvgInvoiceAmtTotalInvoice"  class="labeltxt"> <?php echo $AvgInvoiceAmt->TotalInvoice; ?></label></span></p>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg4">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt">Top Party(by Sale Amt)<br>
											<span class="numstyl" style="font-size:9px;"><label style="font-size:9px;" id="TopSaleAmtPartycompany"  class="labeltxt"><?php echo $TopSaleAmtParty->company; ?></span>
											<span class="numstyl"><label id="TopSaleAmtPartyTotalSale"  class="labeltxt"><?php echo number_format(round($TopSaleAmtParty->TotalSale), 2); ?></span></p>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg1">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt">Total Pending Orders<br>
											<span class="numstyl"> <label id="TotalPendingOrders"  class="labeltxt"> <?php echo $TotalPendingOrder->TotalCount; ?></label></span></p>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								
								
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Widget Row-->
				
				<!-- Filet row-->
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
				<div class="row">
					<div class="col-md-12">
						<div class="panel_s">
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
									<!--<div class="col-md-2">
										<?php echo render_input('month','month',date('Y-m'), 'month'); ?>
									</div>-->
									
									<div class="col-md-2">
										<label class="control-label">Chart Type</label>
										<select name="ChartType" id="ChartType" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
											<option value="Bar">Bar Chart</option>
											<!--<option value="Pie">Pie Chart</option>-->
										</select>
									</div>
									
									<div class="col-md-2">
										<div class="form-group" app-field-wrapper="ItemCount">
											<label for="ItemCount" class="control-label">Max Count</label>
											<input type="text" id="ItemCount" onkeypress="return isNumber(event)" name="ItemCount" class="form-control" value="7">
										</div>
									</div>
									
									<div class="col-md-3">
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
									
									<div class="col-md-3">
										<div class="form-group" app-field-wrapper="Items">
											<small class="req text-danger"></small>
											<label for="Items" class="form-label">Item</label>
											<select name="Items[]" multiple id="Items" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
												
											</select>
										</div>
									</div>
									<!--<div class="clearfix"></div>-->
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
									
									<div class="col-md-2">
										<label class="control-label">Report In</label>
										<select name="ReportIn" id="ReportIn" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
											<option value="amount">Amount</option>
											<option value="qty">Quantity</option>
										</select>
									</div>
									
									<div class="col-md-2">
										<div class="form-group" app-field-wrapper="ReportType">
											<small class="req text-danger"></small>
											<label for="ReportType" class="form-label">Report Type</label>
											<select name="ReportType" id="ReportType" class="selectpicker form-control" data-width="100%" data-live-search="true">
												<option value="Sale">Sale</option>
												<option value="NetSale">Net Sale (Sale - Sale Return)</option>
												<option value="FreshDamage">Sale Return (Fresh + Damage)</option>
												<option value="Fresh">Fresh Sale Return</option>
												<option value="Damage">Damage Sale Return</option>
											</select>
										</div>
									</div>
									
									<div class="col-md-6" style="margin-top:20px;">
										<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button> 
									</div>
									
									
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body">
								<div class="row">
								    <div class="col-md-4">
									    <div class="form-group" app-field-wrapper="month">
									        <label for="month" class="control-label">Month</label>
									        <?php $val = date("Y-m");?>
									        <input type="month"  id="month" name="month" class="form-control" value="<?php echo $val;?>">
									    </div>								
									 </div>
									<div class="col-md-12">
										<figure class="highcharts-figure">
                                            <div id="container_calander"></div>
                                            <!--<p class="highcharts-description">
                                                Heatmap with over 31 data points, visualizing the temperature at 12AM
                                                every day in July 2023. The blue colors indicate colder days, and the
                                                orange colors indicate warmer days.
                                            </p>-->
                                        </figure>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body">
								<div class="row">
								    <div class="col-md-12">
										<h4 style="text-align:center;"><b>Daily Sale Report</b></h4>
									</div>
									<div class="col-md-12">
										<div class="relative" style="max-height:400px">
											<canvas class="chart" height="400" id="contracts-value-by-type-chart"></canvas>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body">
								<div class="row">
								    <div class="col-md-4">
									    <div class="form-group" app-field-wrapper="month">
									        <label for="month" class="control-label">Month</label>
									        <?php $val = date("Y-m");?>
									        <input type="month"  id="month_sale_return" name="month_sale_return" class="form-control" value="<?php echo $val;?>">
									    </div>								
									 </div>
									<div class="col-md-12">
										<figure class="highcharts-figure">
                                            <div id="container_calander_sale_return"></div>
                                            <!--<p class="highcharts-description">
                                                Heatmap with over 31 data points, visualizing the temperature at 12AM
                                                every day in July 2023. The blue colors indicate colder days, and the
                                                orange colors indicate warmer days.
                                            </p>-->
                                        </figure>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body">
								<div class="row">
								    <div class="col-md-12">
										<h4 style="text-align:center;"><b>Daily Sale Return</b></h4>
									</div>
									<div class="col-md-12">
										<div class="relative" style="max-height:400px">
											<canvas class="chart" height="400" id="sale_return_chart_day_wise"></canvas>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				
				<div class="row">
				    <div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
                                            <div id="container_funnel"></div>
                                        </figure>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<!--<div class="row">  
									<div class="col-md-9" style="margin-top:20px;">
									<a class="btn btn-default buttons-excel buttons-html5" href="#" id="caexcel2"><span>Export</span></a>&nbsp;
									<a href="<?php echo admin_url();?>/Sale_reports/TopSellingCustomer" class="btn btn-default" target="_blank">View More Details</a>
									</div>
								</div>-->
								<div class="row">  
									<figure class="highcharts-figure">
										<div id="container2"></div>
									</figure>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				
				
				
				<div class="row">
					<!-- First Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<!-- <div class="row">  
									<div class="col-md-8" style="margin-top:10px;">
									<a class="btn btn-default buttons-excel buttons-html5" href="#" id="caexcel"><span>Export</span></a>&nbsp;
									<a href="<?php echo admin_url();?>Sale_reports/TopSellingItem" class="btn btn-default" target="_blank">View More Details</a>
									</div>
								</div>--><!-- Firter End-->
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container"></div>
										</figure>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<!-- Second Column-->
					
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">  
									<div class="col-md-12">
										<h4 style="text-align:center;"><b>Monthly Best Seller Items</b></h4>
										<p id="ItemList"></p>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<br>
										<div class="relative" style="max-height:400px">
											<canvas class="chart" height="400" id="MonthlyBestSellerItems"></canvas>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="clearfix"></div>
					<!-- Third Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<!-- <div class="row">  
									<div class="col-md-9" style="margin-top:20px;">
									<a href="<?php echo admin_url();?>Sale_reports/SaleRtn" class="btn btn-default" target="_blank">View More Details</a>
									</div>
								</div>-->
								<div class="row">  
									<figure class="highcharts-figure">
										<div id="container3"></div>
									</figure>
								</div>
								
							</div>
						</div>
					</div>
					<!-- Fourth Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<!--<div class="row">  
									
									<div class="col-md-9" style="margin-top:20px;">
									<a href="<?php echo admin_url();?>Sale_reports/SaleRtn" class="btn btn-default" target="_blank">View More Details</a>
									</div>
								</div>-->
								
								<div class="row"> 
									<figure class="highcharts-figure">
										<div id="container4"></div>
									</figure>
								</div>
								
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<!-- Fifth Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">  
									<div class="col-md-12">
										<h4 style="text-align:center;"><b>Sales Forecasting</b></h4>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-3"></div>
									<div class="col-md-2" style="width:50px;height:16px;background-color:rgba(37,155,35,0.2);"></div>
									<div class="col-md-2" style="padding-left: 6px;">Actual Sale</div>
									<div class="col-md-2" style="width:50px;height:16px;background-color:#e29ed4;"></div>
									<div class="col-md-2" style="padding-left: 6px;">Expected Sale</div>
									<div class="col-md-3"></div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<br>
										<div class="relative" style="max-height:400px">
											<canvas class="chart" height="400" id="SalesForecasting"></canvas>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<!-- Sixth Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">  
									<div class="col-md-12">
										<h4 style="text-align:center;"><b>YOY Monthly Sales</b></h4>
									</div>
									<?php
										$fy = $this->session->userdata('finacial_year');
										$CurrentYear = "April-".$fy." To March-".($fy+1);
										$LastYear = "April-".($fy-1)." To March-".$fy;
									?>
									<div class="clearfix"></div>
									<div class="col-md-2"></div>
									<div class="col-md-2" style="width:50px;height:16px;background-color:#4B5158;"></div>
									<div class="col-md-4" style="padding-left: 6px;">Last Year Sale(<?php echo $LastYear;?>)</div>
									<div class="col-md-2" style="width:50px;height:16px;background-color:#03a9f4;"></div>
									<div class="col-md-4" style="padding-left: 6px;">Current Year Sale( <?php echo $CurrentYear;?> )</div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<br>
										<div class="relative" style="max-height:400px">
											<canvas class="chart" height="400" id="YOYMonthlySales"></canvas>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<!-- Seven Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">  
									<div class="col-md-12">
										<h4 style="text-align:center;"><b>Monthly Sales Return</b></h4>
									</div>
									<?php
										$fy = $this->session->userdata('finacial_year');
										$CurrentYear = "April-".$fy." To March-".($fy+1);
										$LastYear = "April-".($fy-1)." To March-".$fy;
									?>
									<div class="clearfix"></div>
									<div class="col-md-3"></div>
									<div class="col-md-2" style="width:50px;height:16px;background-color:#4B5158;"></div>
									<div class="col-md-3" style="padding-left: 6px;">Damage Sale Return</div>
									<div class="col-md-2" style="width:50px;height:16px;background-color:#03a9f4;"></div>
									<div class="col-md-3" style="padding-left: 6px;">Fresh Sale Return</div>
									
									<div class="clearfix"></div>
									<div class="col-md-12">
										<br>
										<div class="relative" style="max-height:400px">
											<canvas class="chart" height="400" id="MonthlySalesReturn"></canvas>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					
					<!-- Eight Column-->
					
					
					<!-- Nine Column-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container5"></div>
										</figure>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container8"></div>
										</figure>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container9"></div>
										</figure>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<!-- Ten Column-->
					<!--<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<center><span class="text-danger">Crate Alert Report</span></center>
										<hr/>
										<figure class="highcharts-figure">
											<div id="container6" class="CrateLedger"></div>
										</figure>
									</div>
								</div>
								
							</div>
						</div>
					</div>-->
					<!-- Thirteen Column-->
					<!--<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<center><span class="text-danger">Trade Receivable Report</span></center>
										<hr/>
										<figure class="highcharts-figure">
											<div id="container7" class="CrateLedger"></div>
										</figure>
									</div>
								</div>
								
							</div>
						</div>
					</div>-->
					
					<!--<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container10"></div>
										</figure>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container11"></div>
										</figure>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel_s">
							<div class="panel-body" style="max-height: 600px;">
								<div class="row">
									<div class="col-md-12">
										<figure class="highcharts-figure">
											<div id="container12"></div>
										</figure>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					
					
				</div>
			</div>
		</div>
		
		<style>
			.CrateLedger { overflow: auto;max-height: 58vh;position:relative;top: 0px; }
			.CrateLedger thead th { position: sticky; top: 0; z-index: 1; }
			.CrateLedger tbody th { position: sticky; left: 0; }
			
			/* Just common table stuff. Really. */
			.CrateLedger table  { border-collapse: collapse; }
			.CrateLedger th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
			.CrateLedger th     { background: #50607b;color: #fff !important; }
			
			
		</style>
		<style>
			/*@import url("https://code.highcharts.com/css/highcharts.css");*/
			
			.highcharts-credits {
			display: none;
			}
			.table-table_staff tbody{
			display: block;
			max-height: 450px;
			overflow-y: scroll;
			width: calc(100% - -8.9em);
			}
			.table-table_staff thead, .table-table_staff tbody tr{
			display: table;
			table-layout: fixed;
			width: 100%;
			
			}
			.table-table_staff thead{
			width: calc(100% - -5.9em);
			}
			.table-table_staff thead{
			position: relative;
			}
			.table-table_staff thead th:last-child:after{
			content: ' ';
			position: absolute;
			background-color: #337ab7;
			width: 1.3em;
			height: 38px;
			right: -1.3em;
			top: 0;
			border-bottom: 2px solid #ddd;
			}
			
			/*.staff_name{*/
			/*width:21%;*/
			/*}*/
			.table-table_staff th td{padding: 32px -20px 12px 14px;
			}
			
			.fontsize{
			font-size:13px;
			}
			.fontsize2{
			font-size:15px;
			}
			
			thead tr:nth-child(2) th {
			top: 20px; /* Offset for the second row to appear below the first */
			}
		</style>
		
		<style>
			.table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
			.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
			.table-daily_report tbody th { position: sticky; left: 0; }
			
			
			table  { border-collapse: collapse; width: 100%; }
			th, td { padding: 0px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
			th     { background: #50607b;
			color: #fff !important; }
			
			.custdesg{
			height:65px;
			}
			.imgsize{
			font-size:40px;
			display: block;
			margin: 0;
			color: #fff;
			}
			.panel_s{
			margin-bottom:5px !important;
			}
			.labeltxt{
			font-size:14px;
			font-weight:400;
			color: #fff;
			}
			.numstyl{
			text-align: left;
			display: block;
			font-size: 14px;
			}
			.mtop5 {
			margin-top: 4px;
			margin-bottom: 2px;
			}
			.bg1{
			background-image: linear-gradient(to right,#008385 0,#008385 100%);
			background-repeat: repeat-x;
			}
			.bg2{
			background-image: linear-gradient(to right,#FF425C 0,#FF425C 100%);
			background-repeat: repeat-x;
			}
			.bg3{
			background-image: linear-gradient(to right,#FF864A 0,#FF864A 100%);
			background-repeat: repeat-x;
			}
			.bg4{
			background-image: linear-gradient(to right,#11A578 0,#11A578 100%);
			background-repeat: repeat-x;
			}
			.top_stats_wrapper{
			margin-top: 0px;
			border-radius: 5px;
			padding:0px !important;
			margin-bottom: 10px !important;
			}
			.top_stats_wrapper:hover{
			box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.4);
			}
			
			// #container8 .highcharts-background {
			// fill: #1f1f1f !important;
			// }
			
			// #container8 .highcharts-credits {
			// color: #888888 !important;
			// }
			
			#container8 .highcharts-null-point{
			fill: rgb(84, 79, 197);
			}
			#container10 .highcharts-xaxis-labels{
			font-size: 12px;
			}
			#container9 .highcharts-null-point{
			fill: rgb(84, 79, 197);
			}
			
		</style>
		<!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
		<script src="https://code.highcharts.com/maps/highmaps.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>
		<?php init_tail(); ?>
	
	
		<!--new update -->
		<script src="https://code.highcharts.com/dashboards/datagrid.js"></script>
			<script src="https://code.highcharts.com/dashboards/dashboards.js"></script>
		<script src="https://code.highcharts.com/dashboards/modules/layout.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/cylinder.js"></script>
<script src="https://code.highcharts.com/modules/funnel3d.js"></script>
<style>
    .highcharts-color-0{
        fill:"" !important;
    }
</style>
<script type="text/javascript">
    // The function takes in a dataset and calculates how many empty tiles needed
    // before and after the dataset is plotted.
    function generateChartData(data) {
    
        // Calculate the starting weekday index (0-6 of the first date in the given
        // array)
        const firstWeekday = new Date(data[0].date).getDay(),
            monthLength = data.length,
            lastElement = data[monthLength - 1].date,
            lastWeekday = new Date(lastElement).getDay(),
            lengthOfWeek = 6,
            emptyTilesFirst = firstWeekday,
            chartData = [];
    
        // Add the empty tiles before the first day of the month with null values to
        // take up space in the chart
        for (let emptyDay = 0; emptyDay < emptyTilesFirst; emptyDay++) {
            chartData.push({
                x: emptyDay,
                y: 5,
                value: null,
                date: null,
                custom: {
                    empty: true
                }
            });
        }
    
        // Loop through and populate with temperature and dates from the dataset
        for (let day = 1; day <= monthLength; day++) {
            // Get date from the given data array
            const date = data[day - 1].date;
            // Offset by thenumber of empty tiles
            const xCoordinate = (emptyTilesFirst + day - 1) % 7;
            const yCoordinate = Math.floor((firstWeekday + day - 1) / 7);
            const id = day;
    
            // Get the corresponding temperature for the current day from the given
            // array
            const temperature = data[day - 1].temperature;
    
            chartData.push({
                x: xCoordinate,
                y: 5 - yCoordinate,
                value: temperature,
                date: new Date(date).getTime(),
                custom: {
                    monthDay: id
                }
            });
        }
    
        // Fill in the missing values when dataset is looped through.
        /*const emptyTilesLast = lengthOfWeek - lastWeekday;
        for (let emptyDay = 1; emptyDay <= emptyTilesLast; emptyDay++) {
            chartData.push({
                x: (lastWeekday + emptyDay) % 7,
                y: 0,
                value: null,
                date: null,
                custom: {
                    empty: true
                }
            });
        }*/
        return chartData;
    }

</script>
		<!--<script src="https://code.highcharts.com/maps/highmaps.js"></script>-->
		<!--<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
			<script src="https://code.highcharts.com/maps/modules/offline-exporting.js">
			</script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>-->
		<script>
			$('#SubGroup').on('change',function(){
				var SubGroup = $("#SubGroup").val();
				$.ajax({
					url:"<?php echo admin_url(); ?>sale_reports/GetGroupWiseItemList",
					dataType:"JSON",
					method:"POST",
					data:{SubGroup:SubGroup},
					beforeSend: function () {
					},
					complete: function () {
					},
					success:function(data){
						let ItemList = data;
						$("#Items").children().remove();
						for (var i = 0; i < ItemList.length; i++) {
							$("#Items").append('<option value="'+ItemList[i]["item_code"]+'">'+ItemList[i]["description"]+'</option>');
						}
						$('.selectpicker').selectpicker('refresh');
					}
				});
			});
			
		</script>
		<script>
			function isNumber(evt) {
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode = 46 && charCode > 31 
				&& (charCode < 48 || charCode > 57)){
					return false;
				}
				return true;
			}
		</script>
		<script type="text/javascript" language="javascript" >
			$(document).ready(function(){
				$('#search_data_counter').on('click',function(){
					var from_date = $("#from_date").val();
					var to_date = $("#to_date").val();
					
					GetCountersValue(from_date,to_date);
				});
				$('#search_data_counter').click();
				function GetCountersValue(from_date,to_date)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetSaleCounters",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							var TopItem = returndata.TopItem;
							var TotalPendingOrder = returndata.TotalPendingOrder;
							var TotalGstAmt = returndata.TotalGstAmt;
							var AvgInvoiceAmt = returndata.AvgInvoiceAmt;
							var TotalSaleAmt = returndata.TotalSaleAmtByAnyParty;
							var NewParties = returndata.NewParties;
							var TotalSaleRtmAmt = returndata.TotalSaleRtmAmt;
							var TopSaleAmtParty = returndata.TopSaleAmtParty;
							
							
							
							$("#TopSkuName").html(TopItem?.description_name ?? " ");
							$("#TopItemTotalSale").html(TopItem?.TotalSale ?? " ");
							$("#TotalGstAmtTotalGST").html(TotalGstAmt?.TotalGST ?? " ");
							$("#TotalPendingOrders").html(TotalPendingOrder?.TotalCount ?? " ");
							$("#AvgInvoiceAmtAvgAmt").html(parseFloat(AvgInvoiceAmt?.AvgAmt ?? 0.00).toFixed(2));
							$("#TotalSaleByAnyParty").html(parseFloat(TotalSaleAmt?.TotalSale ?? 0.00).toFixed(2));
							$("#NewPartiesNewParty").html(NewParties?.NewParty ?? " ");
							$("#TotalSaleRtmAmtFreshRtn").html(parseFloat(TotalSaleRtmAmt?.FreshRtn ?? 0.00).toFixed(2));
							$("#TotalSaleRtmAmtDmgRtn").html(parseFloat(TotalSaleRtmAmt?.DmgRtn ?? 0.00).toFixed(2));
							$("#AvgInvoiceAmtTotalInvoice").html(AvgInvoiceAmt?.TotalInvoice ?? " ");
							$("#TopSaleAmtPartycompany").html(TopSaleAmtParty?.company ?? " ");
							$("#TopSaleAmtPartyTotalSale").html(parseFloat(TopSaleAmtParty?.TotalSale ?? 0.00).toFixed(2));
							
						}
					});
				}
				$('#month').on('change',function(){
				    var from_date = $("#from_date2").val();
					var to_date = $("#to_date2").val();
					var Items = $("#Items").val();
					var SubGroup = $("#SubGroup").val();
					var state = $("#state").val();
					var ReportType = $("#ReportType").val();
					var Month = $("#month").val();
				    load_calanderChart(from_date,to_date,Items,SubGroup,state,ReportType,Month);
				});
				$('#month_sale_return').on('change',function(){
				    var from_date = $("#from_date2").val();
					var to_date = $("#to_date2").val();
					var Items = $("#Items").val();
					var SubGroup = $("#SubGroup").val();
					var state = $("#state").val();
					var ReportType = $("#ReportType").val();
					var Month = $("#month_sale_return").val();
				    load_calander_sale_returnChart(from_date,to_date,Items,SubGroup,state,ReportType,Month);
				});
				$('#search_data').on('click',function(){
					var from_date = $("#from_date2").val();
					var to_date = $("#to_date2").val();
					var ChartType = $("#ChartType").val();
					var MaxCount = $("#ItemCount").val();
					var SubGroup = $("#SubGroup").val();
					var Items = $("#Items").val();
					var state = $("#state").val();
					var ReportType = $("#ReportType").val();
					var ReportIn = $("#ReportIn").val();
					var Month = $("#month").val();
					var report = "3";
					var report2 = "4";
					load_calanderChart(from_date,to_date,Items,SubGroup,state,ReportType,Month);
					load_calander_sale_returnChart(from_date,to_date,Items,SubGroup,state,ReportType,Month);
					load_sale_return_data_for_chart(from_date,to_date,Items,SubGroup,state,ReportType);
					load_funnelData(from_date,to_date,Items,SubGroup,state,ReportType,Month);
					load_data5(from_date,to_date,Items,SubGroup,state,ReportType);
					load_data(from_date,to_date,ChartType,MaxCount,state,SubGroup,Items);
					load_data2(from_date,to_date,ChartType,MaxCount,state,SubGroup,Items);
					load_data3(from_date,to_date,ChartType,MaxCount,SubGroup,Items,state,ReportIn,report);
					load_data4(from_date,to_date,ChartType,MaxCount,SubGroup,Items,state,ReportIn,report2);
					
					load_data6(Items,SubGroup,state);
					load_data7(Items,SubGroup,state,ReportType);
					load_data8(Items,SubGroup,state,ReportType);
					load_data9(Items,SubGroup,state);
					
					load_data10(from_date,to_date,ChartType,MaxCount,SubGroup,Items);
					// load_data11(from_date,to_date);
					// load_data12(from_date,to_date);
					load_data13(from_date,to_date,ChartType,MaxCount,SubGroup,Items);
					load_data14();
					// load_data15(from_date,to_date,ChartType,MaxCount,SubGroup,Items,state,ReportIn,report);
					load_data16(from_date,to_date,ChartType,MaxCount);
					load_data17(from_date,to_date,ChartType,MaxCount);
					
				});
				function load_funnelData(from_date,to_date,Items,SubGroup,state,ReportType,Month)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetCustomerOverview",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType,Month:Month},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
						    Highcharts.chart('container_funnel', {
                            chart: {
                                type: 'funnel3d',
                                options3d: {
                                    enabled: true,
                                    alpha: 10,
                                    depth: 50,
                                    viewDistance: 50
                                }
                            },
                            title: {
                                text: 'Customers Funnel'
                            },
                            accessibility: {
                                screenReaderSection: {
                                    beforeChartFormat: '<{headingTagName}>' +
                                        '{chartTitle}</{headingTagName}><div>{typeDescription}</div>' +
                                        '<div>{chartSubtitle}</div><div>{chartLongdesc}</div>'
                                }
                            },
                            plotOptions: {
                                series: {
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b> ({point.y:,.0f})',
                                        allowOverlap: true,
                                        y: 10
                                    },
                                    neckWidth: '30%',
                                    neckHeight: '25%',
                                    width: '80%',
                                    height: '80%'
                                }
                            },
                            series: [{
                                name: 'Unique users',
                                data:returndata
                                /*data: [
                                    ['All Customers', 15654],
                                    ['Active Customers', 4064],
                                    ['Ordered Customers', 1987],
                                    ['Invoiced Customers', 976]
                                ]*/
                            }]
                        });
						}
					});
				}
				function load_calanderChart(from_date,to_date,Items,SubGroup,state,ReportType,Month)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetCalenderMonthlySaleData",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType,Month:Month},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
						    const data = returndata;
                            const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                            const chartData = generateChartData(data);
                            Highcharts.chart('container_calander', {
                                chart: {
                                    type: 'heatmap'
                                },
                                title: {
                                    text: 'Day Wise Sale '+Month,
                                    align: 'left'
                                },
                            
                                subtitle: {
                                    text: '',
                                    align: 'left'
                                },
                            
                                accessibility: {
                                    landmarkVerbosity: 'one'
                                },
                            
                                tooltip: {
                                    enabled: true,
                                    outside: true,
                                    zIndex: 20,
                                    headerFormat: '',
                                    pointFormat: '{#unless point.custom.empty}{point.date:%A, %b %e, ' +
                                        '%Y}{/unless}',
                                    nullFormat: 'No data'
                                },
                            
                                xAxis: {
                                    categories: weekdays,
                                    opposite: true,
                                    lineWidth: 26,
                                    offset: 13,
                                    lineColor: 'rgba(27, 26, 37, 0.2)',
                                    labels: {
                                        rotation: 0,
                                        y: 20,
                                        style: {
                                            textTransform: 'uppercase',
                                            fontWeight: 'bold'
                                        }
                                    },
                                    accessibility: {
                                        description: 'weekdays',
                                        rangeDescription: 'X Axis is showing all 7 days of the week, ' +
                                            'starting with Sunday.'
                                    }
                                },
                            
                                yAxis: {
                                    min: 0,
                                    max: 5,
                                    accessibility: {
                                        description: 'weeks'
                                    },
                                    visible: false
                                },
                            
                                legend: {
                                    align: 'right',
                                    layout: 'vertical',
                                    verticalAlign: 'middle'
                                },
                            
                                colorAxis: {
                                    min: 0,
                                    stops: [
                                        [0.2, 'lightblue'],
                                        [0.4, '#CBDFC8'],
                                        [0.6, '#F3E99E'],
                                        [0.9, '#F9A05C']
                                    ],
                                    labels: {
                                        format: '{value}'
                                    }
                                },
                            
                                series: [{
                                    keys: ['x', 'y', 'value', 'date', 'id'],
                                    data: chartData,
                                    nullColor: 'rgba(196, 196, 196, 0.2)',
                                    borderWidth: 2,
                                    borderColor: 'rgba(196, 196, 196, 0.2)',
                                    dataLabels: [{
                                        enabled: true,
                                        format: '{#unless point.custom.empty}{point.value:.1f}{/unless}',
                                        style: {
                                            textOutline: 'none',
                                            fontWeight: 'normal',
                                            fontSize: '1rem'
                                        },
                                        y: 4
                                    }, {
                                        enabled: true,
                                        align: 'left',
                                        verticalAlign: 'top',
                                        format: '{#unless ' +
                                            'point.custom.empty}{point.custom.monthDay}{/unless}',
                                        backgroundColor: 'whitesmoke',
                                        padding: 2,
                                        style: {
                                            textOutline: 'none',
                                            color: 'rgba(70, 70, 92, 1)',
                                            fontSize: '0.8rem',
                                            fontWeight: 'bold',
                                            opacity: 0.5
                                        },
                                        x: 1,
                                        y: 1
                                    }]
                                }]
                            });
						}
					});
				}
				
				function load_calander_sale_returnChart(from_date,to_date,Items,SubGroup,state,ReportType,Month)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetCalenderMonthlySaleReturnData",
						dataType:"JSON", 
						method:"POST",
						data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType,Month:Month},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
						    const data = returndata;
                            const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                            const chartData = generateChartData(data);
                            Highcharts.chart('container_calander_sale_return', {
                                chart: {
                                    type: 'heatmap'
                                },
                                title: {
                                    text: 'Day Wise Sale Return '+Month,
                                    align: 'left'
                                },
                            
                                subtitle: {
                                    text: '',
                                    align: 'left'
                                },
                            
                                accessibility: {
                                    landmarkVerbosity: 'one'
                                },
                            
                                tooltip: {
                                    enabled: true,
                                    outside: true,
                                    zIndex: 20,
                                    headerFormat: '',
                                    pointFormat: '{#unless point.custom.empty}{point.date:%A, %b %e, ' +
                                        '%Y}{/unless}',
                                    nullFormat: 'No data'
                                },
                            
                                xAxis: {
                                    categories: weekdays,
                                    opposite: true,
                                    lineWidth: 26,
                                    offset: 13,
                                    lineColor: 'rgba(27, 26, 37, 0.2)',
                                    labels: {
                                        rotation: 0,
                                        y: 20,
                                        style: {
                                            textTransform: 'uppercase',
                                            fontWeight: 'bold'
                                        }
                                    },
                                    accessibility: {
                                        description: 'weekdays',
                                        rangeDescription: 'X Axis is showing all 7 days of the week, ' +
                                            'starting with Sunday.'
                                    }
                                },
                            
                                yAxis: {
                                    min: 0,
                                    max: 5,
                                    accessibility: {
                                        description: 'weeks'
                                    },
                                    visible: false
                                },
                            
                                legend: {
                                    align: 'right',
                                    layout: 'vertical',
                                    verticalAlign: 'middle'
                                },
                            
                                colorAxis: {
                                    min: 0,
                                    stops: [
                                        [0.2, 'lightblue'],
                                        [0.4, '#CBDFC8'],
                                        [0.6, '#F3E99E'],
                                        [0.9, '#F9A05C']
                                    ],
                                    labels: {
                                        format: '{value}'
                                    }
                                },
                            
                                series: [{
                                    keys: ['x', 'y', 'value', 'date', 'id'],
                                    data: chartData,
                                    nullColor: 'rgba(196, 196, 196, 0.2)',
                                    borderWidth: 2,
                                    borderColor: 'rgba(196, 196, 196, 0.2)',
                                    dataLabels: [{
                                        enabled: true,
                                        format: '{#unless point.custom.empty}{point.value:.1f}{/unless}',
                                        style: {
                                            textOutline: 'none',
                                            fontWeight: 'normal',
                                            fontSize: '1rem'
                                        },
                                        y: 4
                                    }, {
                                        enabled: true,
                                        align: 'left',
                                        verticalAlign: 'top',
                                        format: '{#unless ' +
                                            'point.custom.empty}{point.custom.monthDay}{/unless}',
                                        backgroundColor: 'whitesmoke',
                                        padding: 2,
                                        style: {
                                            textOutline: 'none',
                                            color: 'rgba(70, 70, 92, 1)',
                                            fontSize: '0.8rem',
                                            fontWeight: 'bold',
                                            opacity: 0.5
                                        },
                                        x: 1,
                                        y: 1
                                    }]
                                }]
                            });
						}
					});
				}
				
				function load_data(from_date,to_date,ChartType,MaxCount,state,SubGroup,Items)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetTopSellingItem",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,state:state,SubGroup:SubGroup,Items:Items},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							if(ChartType == "Pie"){
								Highcharts.chart('container', {
									chart: {
										styledMode: true,  
										height: 600, // Increase chart height
										spacing: [10, 100, 10, 10],
									},
									title: {
										text: '',
									},
									subtitle: {
										text: '<b>Top Selling Items '+from_date+' To '+to_date+'</b>'
									},
									plotOptions: {
										pie: {
											size: '70%', // Force the pie to occupy 90% of the chart area
											dataLabels: {
												enabled: true,
												distance: 10, // Move data labels closer to the pie
												style: {
													fontSize: '16px'
												}
											}
										}
									},
									series: [{
										type: 'pie',
										allowPointSelect: true,
										keys: ['name', 'y', 'selected', 'sliced'],
										data: returndata.ChartData,
										showInLegend: true
									}],
									legend: {
										layout: 'horizontal', // Arrange legend items horizontally
										align: 'center', // Center-align the legend
										verticalAlign: 'bottom', // Place legend at the bottom
										itemWidth: 150, // Control the width of each legend item for better wrapping
										itemStyle: {
											fontSize: '14px'
										}
									},
								});
							}
							
							if(ChartType == "Bar"){
								Highcharts.chart('container', {
									chart: {
										type: 'column',
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>Top Selling Items '+from_date+' To '+to_date+'</b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									},
									xAxis: {
										type: 'category',
										labels: {
											autoRotation: [-45, -90],
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											}
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Selling Qty (Unit)',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											}
										}
									},
									legend: {
										enabled: false
									},
									tooltip: {
										pointFormat: 'QTY : <b>{point.y:.1f} </b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									},
									series: [{
										name: 'Population',
										colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
										colorByPoint: true,
										groupPadding: 0,
										data: returndata.ChartData,
										dataLabels: {
											enabled: true,
											rotation: -90,
											color: '#FFFFFF',
											inside: true,
											verticalAlign: 'top',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											format: '{point.y:.1f}', // one decimal
											y: 10, // 10 pixels down from the top
											
										}
									}]
								});
							}
						}
					});
				}
				// Second Table Start
				function load_data2(from_date,to_date,ChartType,MaxCount,state,SubGroup,Items)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetTopSellingCustomer",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,state:state,SubGroup:SubGroup,Items:Items},
						beforeSend: function () {
							
							$('#searchh2').css('display','block');
							$('#table-daily_report2 tbody').css('display','none');
							
						},
						complete: function () {
							
							$('#table-daily_report2 tbody').css('display','');
							$('#searchh2').css('display','none');
						},
						success:function(returndata){
							if(ChartType == "Pie"){
								Highcharts.chart('container2', {
									chart: {
										styledMode: true,  
										height: 600, // Increase chart height
										spacing: [10, 100, 10, 10],
									},
									title: {
										text: '',
									},
									subtitle: {
										text: '<b> Top Customers '+from_date+' To '+to_date+'</b>'
									},
									plotOptions: {
										pie: {
											size: '70%', // Force the pie to occupy 90% of the chart area
											dataLabels: {
												enabled: true,
												distance: 10, // Move data labels closer to the pie
												style: {
													fontSize: '16px'
												}
											}
										}
									},
									series: [{
										type: 'pie',
										allowPointSelect: true,
										keys: ['name', 'y', 'selected', 'sliced'],
										data: returndata.ChartData,
										showInLegend: true
									}],
									legend: {
										layout: 'horizontal', // Arrange legend items horizontally
										align: 'center', // Center-align the legend
										verticalAlign: 'bottom', // Place legend at the bottom
										itemWidth: 150, // Control the width of each legend item for better wrapping
										itemStyle: {
											fontSize: '14px'
										}
									},
								});
							}
							
							if(ChartType == "Bar"){
								Highcharts.chart('container2', {
									chart: {
										type: 'column'
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b> Top Customers '+from_date+' To '+to_date+'</b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										},
									},
									xAxis: {
										type: 'category',
										labels: {
											autoRotation: [-45, -90],
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Amount',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
										}
									},
									legend: {
										enabled: false
									},
									tooltip: {
										pointFormat: 'AMT : <b>{point.y:.1f} </b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										},
									},
									series: [{
										name: 'Population',
										colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
										colorByPoint: true,
										groupPadding: 0,
										data: returndata.ChartData,
										dataLabels: {
											enabled: true,
											rotation: -90,
											color: '#FFFFFF',
											inside: true,
											verticalAlign: 'top',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											format: '{point.y:.1f}', // one decimal
											y: 10, // 10 pixels down from the top
											
										}
									}]
								});
							}
						}
					});
				}
				
				// Third Table Start
				function load_data3(from_date,to_date,ChartType,MaxCount,SubGroup,Items,state,ReportIn,report)
				{   
					chart_title1 = "Fresh Sale Return";
					var TType2 = "Fresh";
					var cont = "container3";
					if(ReportIn == "amount"){
						var label = "Amount";
						}else{
						var label = "Quantity";
					}
					
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetSalesReturnReport",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,state:state,ReportIn:ReportIn,TType2:TType2},
						beforeSend: function () {
							//$('#searchh4').css('display','block');
						},
						complete: function () {
							//$('#searchh4').css('display','none');
						},
						success:function(returndata){
							if(ChartType == "Pie"){
								Highcharts.chart(cont, {
									chart: {
										styledMode: true,  
										height: 600, // Increase chart height
										spacing: [10, 100, 10, 10],
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>'+chart_title1+' '+from_date+' To '+to_date+'</b>'
									},
									plotOptions: {
										pie: {
											size: '70%', // Force the pie to occupy 90% of the chart area
											dataLabels: {
												enabled: true,
												distance: 10, // Move data labels closer to the pie
												style: {
													fontSize: '14px'
												}
											}
										}
									},
									series: [{
										type: 'pie',
										allowPointSelect: true,
										keys: ['name', 'y', 'selected', 'sliced'],
										data: returndata.ChartData,
										showInLegend: true
									}],
									legend: {
										layout: 'horizontal', // Arrange legend items horizontally
										align: 'center', // Center-align the legend
										verticalAlign: 'bottom', // Place legend at the bottom
										itemWidth: 150, // Control the width of each legend item for better wrapping
										itemStyle: {
											fontSize: '14px'
										}
									},
								});
							}
							
							if(ChartType == "Bar"){
								Highcharts.chart(cont, {
									chart: {
										type: 'column'
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>'+chart_title1 +' '+from_date+' To '+to_date+'</b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										},
									},
									xAxis: {
										type: 'category',
										labels: {
											autoRotation: [-45, -90],
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: label,
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
										}
									},
									legend: {
										enabled: false
									},
									tooltip: {
										pointFormat: label +': <b>{point.y:.1f} </b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										},
									},
									series: [{
										name: 'Population',
										colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
										colorByPoint: true,
										groupPadding: 0,
										data: returndata.ChartData,
										dataLabels: {
											enabled: true,
											rotation: -90,
											color: '#FFFFFF',
											inside: true,
											verticalAlign: 'top',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											format: '{point.y:.1f}', // one decimal
											y: 10, // 10 pixels down from the top
										}
									}]
								});
							}
						}
					});
				}
				// Fourth Table Start
				function load_data4(from_date,to_date,ChartType,MaxCount,SubGroup,Items,state,ReportIn,report)
				{  
					chart_title = "Damage Sale Return";
					var TType2 = "Damage";
					var cont = "container4";
					if(ReportIn == "amount"){
						var label = "Amount";
						}else{
						var label = "Quantity";
					}
					
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetSalesReturnReport",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,state:state,ReportIn:ReportIn,TType2:TType2},
						beforeSend: function () {
							//$('#searchh4').css('display','block');
						},
						complete: function () {
							//$('#searchh4').css('display','none');
						},
						success:function(returndata){
							if(ChartType == "Pie"){
								Highcharts.chart(cont, {
									chart: {
										styledMode: true,  
										height: 600, // Increase chart height
										spacing: [10, 100, 10, 10],
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>'+chart_title+' '+from_date+' To '+to_date+'</b>'
									},
									plotOptions: {
										pie: {
											size: '70%', // Force the pie to occupy 90% of the chart area
											dataLabels: {
												enabled: true,
												distance: 10, // Move data labels closer to the pie
												style: {
													fontSize: '14px'
												}
											}
										}
									},
									series: [{
										type: 'pie',
										allowPointSelect: true,
										keys: ['name', 'y', 'selected', 'sliced'],
										data: returndata.ChartData,
										showInLegend: true
									}],
									legend: {
										layout: 'horizontal', // Arrange legend items horizontally
										align: 'center', // Center-align the legend
										verticalAlign: 'bottom', // Place legend at the bottom
										itemWidth: 150, // Control the width of each legend item for better wrapping
										itemStyle: {
											fontSize: '14px'
										}
									},
								});
							}
							
							if(ChartType == "Bar"){
								Highcharts.chart(cont, {
									chart: {
										type: 'column'
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>'+chart_title+'  '+from_date+' To '+to_date+'</b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										},
									},
									xAxis: {
										type: 'category',
										labels: {
											autoRotation: [-45, -90],
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: label,
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
										}
									},
									legend: {
										enabled: false
									},
									tooltip: {
										pointFormat: label +': <b>{point.y:.1f} </b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										},
									},
									series: [{
										name: 'Population',
										colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
										colorByPoint: true,
										groupPadding: 0,
										data: returndata.ChartData,
										dataLabels: {
											enabled: true,
											rotation: -90,
											color: '#FFFFFF',
											inside: true,
											verticalAlign: 'top',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											format: '{point.y:.1f}', // one decimal
											y: 10, // 10 pixels down from the top
										}
									}]
								});
							}
						}
					});
				}
				
				function load_sale_return_data_for_chart(from_date,to_date,Items,SubGroup,state,ReportType)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetDayWiseSaleReturnReports",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							new Chart($('#sale_return_chart_day_wise'), {
								type: 'line',
								data: returndata,
								options: {
									responsive: true,
									legend: {
										display: false,
									},
									maintainAspectRatio:false,
									scales: {
										yAxes: [{
											display: true,
											ticks: {
												suggestedMin: 0,
											}
										}]
									}
								}
							});
						}
					});
				}
				function load_data5(from_date,to_date,Items,SubGroup,state,ReportType)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetDailySaleReports",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							new Chart($('#contracts-value-by-type-chart'), {
								type: 'line',
								data: returndata,
								options: {
									responsive: true,
									legend: {
										display: false,
									},
									maintainAspectRatio:false,
									scales: {
										yAxes: [{
											display: true,
											ticks: {
												suggestedMin: 0,
											}
										}]
									}
								}
							});
						}
					});
				}
				
				function load_data6(Items,SubGroup,state)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/SalesForecasting",
						dataType:"JSON",
						method:"POST",
						data:{Items:Items,SubGroup:SubGroup,state:state},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							new Chart($('#SalesForecasting'), {
								type: 'bar',
								data: returndata,
								options: {
									responsive: true,
									legend: {
										display: false,
									},
									maintainAspectRatio:false,
									scales: {
										yAxes: [{
											display: true,
											ticks: {
												suggestedMin: 0,
											}
										}]
									}
								}
							});
						}
					});
				}
				function load_data7(Items,SubGroup,state,ReportType)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetYOYMonthlySaleReports",
						dataType:"JSON",
						method:"POST",
						data:{Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							new Chart($('#YOYMonthlySales'), {
								type: 'line',
								data: returndata,
								options: {
									responsive: true,
									legend: {
										display: false,
									},
									maintainAspectRatio:false,
									scales: {
										yAxes: [{
											display: true,
											ticks: {
												suggestedMin: 0,
											}
										}]
									}
								}
							});
						}
					});
				}
				function load_data8(Items,SubGroup,state,ReportType)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/MonthlySaleReturns",
						dataType:"JSON",
						method:"POST",
						data:{Items:Items,SubGroup:SubGroup,state:state,ReportType:ReportType},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							new Chart($('#MonthlySalesReturn'), {
								type: 'line',
								data: returndata,
								options: {
									responsive: true,
									legend: {
										display: false,
									},
									maintainAspectRatio:false,
									scales: {
										yAxes: [{
											display: true,
											ticks: {
												suggestedMin: 0,
											}
										}]
									}
								}
							});
						}
					});
				}
				
				function load_data9(Items,SubGroup,state)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetMonthlyBestSellerItems",
						dataType:"JSON",
						method:"POST",
						data:{Items:Items,SubGroup:SubGroup,state:state},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							$("#ItemList").html(returndata.ItemList);
							new Chart($('#MonthlyBestSellerItems'), {
								type: 'bar',
								data: returndata,
								options: {
									responsive: true,
									legend: {
										display: false,
									},
									maintainAspectRatio:false,
									scales: {
										yAxes: [{
											display: true,
											ticks: {
												suggestedMin: 0,
											}
										}]
									}
								}
							});
						}
					});
				}
				function load_data10(from_date,to_date,ChartType,MaxCount,SubGroup,Items)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>production/Prod_VS_Sales",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							
							Highcharts.chart('container5', {
								chart: {
									type: 'column'
								},
								title: {
									text: ''
								},
								subtitle: {
									text: '<b>PRODUCTION VS SALES '+from_date+' To '+to_date+'</b>',
									style: {
										fontSize: '12px'  // ⬅️ Increased font size
									}
								},
								xAxis: {
									type: 'category',
									labels: {
										autoRotation: [-45, -90],
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									}
								},
								yAxis: {
									min: 0,
									title: {
										text: 'Qty In(Unit)',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									}
								},
								tooltip: {
									pointFormat: 'QTY : <b>{point.y:.1f} </b>',
									style: {
										fontSize: '12px'  // ⬅️ Increased font size
									}
								},
								plotOptions: {
									column: {
										pointPadding: 0.2,
										borderWidth: 0
									}
								},
								series: [
								{
									name: 'Production',
									data: returndata.Production,
								},
								{
									name: 'Sales',
									data: returndata.Sales,
								}
								]
							});
							
						}
					});
				}
				
				// function load_data11(from_date,to_date)
				// {
					// $.ajax({
						// url:"<?php echo admin_url(); ?>sale_reports/GetCrateAlertReport",
						// dataType:"JSON",
						// method:"POST",
						// data:{from_date:from_date,to_date:to_date},
						// beforeSend: function () {
						// },
						// complete: function () {
						// },
						// success:function(returndata){
							// $('#container6').html(returndata);
						// }
					// });
				// }
				// function load_data12(from_date,to_date)
				// {
					// $.ajax({
						// url:"<?php echo admin_url(); ?>sale_reports/GetSaleBillsReceivableReport",
						// dataType:"JSON",
						// method:"POST",
						// data:{from_date:from_date,to_date:to_date},
						// beforeSend: function () {
						// },
						// complete: function () {
						// },
						// success:function(returndata){
							// $('#container7').html(returndata);
						// }
					// });
				// }
				
				function load_data13(from_date,to_date,ChartType,MaxCount,SubGroup,Items)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>Sale_reports/CityWiseSales",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							// Create a data value for each feature
							(async () => {
								const topology = await fetch(
								'https://code.highcharts.com/mapdata/countries/in/in-all.topo.json'
								).then(response => response.json());
								
								Highcharts.mapChart('container8', {
									chart: {
										map: topology,
									},
									
									title: {
										text: 'City Wise Sales'
									},
									
									mapNavigation: {
										enabled: true,
										buttonOptions: {
											verticalAlign: 'bottom'
										}
									},
									
									series: [
									{
										name: 'States of India',
										borderColor: '#A0A0A0',
										nullColor: '#E0E0E0',
										showInLegend: false
									},
									{
										// City data with lat/lon
										type: 'mappoint',
										name: 'City',
										color: '#fe6a35',
										data: returndata,
										dataLabels: {
											enabled: true,
											format: '{point.name}'
										},
										tooltip: {
											pointFormat: '{point.name} Sales: {point.sales}'
										}
									}
									]
								});
							})();
							
						}
					});
				}
				function load_data14()
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>Sale_reports/CityWiseCustomers",
						dataType:"JSON",
						method:"POST",
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							// Create a data value for each feature
							(async () => {
								const topology = await fetch(
								'https://code.highcharts.com/mapdata/countries/in/in-all.topo.json'
								).then(response => response.json());
								
								Highcharts.mapChart('container9', {
									chart: {
										map: topology
									},
									
									title: {
										text: 'City wise Customers'
									},
									
									mapNavigation: {
										enabled: true,
										buttonOptions: {
											verticalAlign: 'bottom'
										}
									},
									
									series: [
									{
										name: 'States of India',
										borderColor: '#A0A0A0',
										nullColor: '#E0E0E0',
										showInLegend: false
									},
									{
										// City data with lat/lon
										type: 'mappoint',
										name: 'City',
										color: '#fe6a35',
										data: returndata,
										dataLabels: {
											enabled: true,
											format: '{point.name}'
										},
										tooltip: {
											pointFormat: '{point.name} : {point.sales}'
										}
									}
									]
								});
							})();
							
						}
					});
				}
				// Third Table Start
				function load_data15(from_date,to_date,ChartType,MaxCount,SubGroup,Items,state,ReportIn,report)
				{   
					$.ajax({
						url: "<?php echo admin_url(); ?>sale_reports/GetFreshVsDamageSalesReturnReport",
						dataType: "JSON",
						method: "POST",
						data: {
							from_date: from_date,
							to_date: to_date,
							ChartType: ChartType,
							MaxCount: MaxCount,
							SubGroup: SubGroup,
							Items: Items,
							state: state,
							ReportIn: ReportIn
						},
						success: function (returndata) {
							var label = ReportIn == "amount" ? "Amount" : "Quantity";
							
							// Create a month -> value mapping for Fresh and Damage
							let months = ['April', 'May', 'June',
							'July', 'August', 'September', 'October', 'November', 'December','January', 'February', 'March'
							];
							
							let freshMap = {};
							let damageMap = {};
							
							returndata.Fresh.forEach(function (row) {
								freshMap[row.name] = row.y;
							});
							
							returndata.Damage.forEach(function (row) {
								damageMap[row.name] = row.y;
							});
							
							// Ensure all 12 months are covered even if value is missing
							let freshData = [], damageData = [];
							months.forEach(function (month) {
								freshData.push(freshMap[month] || 0);
								damageData.push(damageMap[month] || 0);
							});
							
							// Render chart
							Highcharts.chart('container10', {
								chart: {
									type: 'line'
								},
								title: {
									text: 'Monthly Fresh vs Damage Sale Return'
								},
								xAxis: {
									categories: months,
									style: {
										fontSize: '14px'
									}
								},
								yAxis: {
									title: {
										text: label,
										style: {
											fontSize: '14px'
										}
									}
								},
								plotOptions: {
									line: {
										dataLabels: {
											enabled: true
										},
										enableMouseTracking: true
									}
								},
								tooltip: {
									pointFormat: label + ' : <b>{point.y:.1f}</b>',
									style: {
										fontSize: '14px'
									}
								},
								series: [{
									name: 'Fresh',
									data: freshData,
									}, {
									name: 'Damage',
									data: damageData
								}]
							});
						}
					});
				}
				
				function load_data16(from_date,to_date,ChartType,MaxCount)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetTopCrateAlert",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							if(ChartType == "Pie"){
								Highcharts.chart('container11', {
									chart: {
										styledMode: true,  
										height: 600, // Increase chart height
										spacing: [10, 100, 10, 10],
									},
									title: {
										text: '',
									},
									subtitle: {
										text: '<b>Top Crate Alert '+from_date+' To '+to_date+'</b><br><a style="color:#008ece;" href="<?= admin_url('Sale_reports/CrateAlertReport')?>" target="_blank">Click To Get Detailed Report </a>'
									},
									plotOptions: {
										pie: {
											size: '70%', // Force the pie to occupy 90% of the chart area
											dataLabels: {
												enabled: true,
												distance: 10, // Move data labels closer to the pie
												style: {
													fontSize: '16px'
												}
											}
										}
									},
									series: [{
										type: 'pie',
										allowPointSelect: true,
										keys: ['name', 'y', 'selected', 'sliced'],
										data: returndata.ChartData,
										showInLegend: true
									}],
									legend: {
										layout: 'horizontal', // Arrange legend items horizontally
										align: 'center', // Center-align the legend
										verticalAlign: 'bottom', // Place legend at the bottom
										itemWidth: 150, // Control the width of each legend item for better wrapping
										itemStyle: {
											fontSize: '14px'
										}
									},
								});
							}
							
							if(ChartType == "Bar"){
								Highcharts.chart('container11', {
									chart: {
										type: 'column',
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>Top Crate Alert '+from_date+' To '+to_date+'</b><br><a style="color:#008ece;" href="<?= admin_url('Sale_reports/CrateAlertReport')?>" target="_blank">Click To Get Detailed Report </a>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									},
									xAxis: {
										type: 'category',
										labels: {
											autoRotation: [-45, -90],
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											}
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Crates Qty',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											}
										}
									},
									legend: {
										enabled: false
									},
									tooltip: {
										pointFormat: 'QTY : <b>{point.y:.1f} </b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									},
									series: [{
										name: 'Population',
										colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
										colorByPoint: true,
										groupPadding: 0,
										data: returndata.ChartData,
										dataLabels: {
											enabled: true,
											rotation: -90,
											color: '#FFFFFF',
											inside: true,
											verticalAlign: 'top',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											format: '{point.y:.1f}', // one decimal
											y: 10, // 10 pixels down from the top
											
										}
									}]
								});
							}
						}
					});
				}
				function load_data17(from_date,to_date,ChartType,MaxCount)
				{
					$.ajax({
						url:"<?php echo admin_url(); ?>sale_reports/GetTopBillsReceivableReport",
						dataType:"JSON",
						method:"POST",
						data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount},
						beforeSend: function () {
						},
						complete: function () {
						},
						success:function(returndata){
							if(ChartType == "Pie"){
								Highcharts.chart('container12', {
									chart: {
										styledMode: true,  
										height: 600, // Increase chart height
										spacing: [10, 100, 10, 10],
									},
									title: {
										text: '',
									},
									subtitle: {
										text: '<b>Top Bills Receivable '+from_date+' To '+to_date+'</b><br><a style="color:#008ece;" href="<?= admin_url('Sale_reports/BillsReceivableReport')?>" target="_blank">Click To Get Detailed Report </a>'
									},
									plotOptions: {
										pie: {
											size: '70%', // Force the pie to occupy 90% of the chart area
											dataLabels: {
												enabled: true,
												distance: 10, // Move data labels closer to the pie
												style: {
													fontSize: '16px'
												}
											}
										}
									},
									series: [{
										type: 'pie',
										allowPointSelect: true,
										keys: ['name', 'y', 'selected', 'sliced'],
										data: returndata.ChartData,
										showInLegend: true
									}],
									legend: {
										layout: 'horizontal', // Arrange legend items horizontally
										align: 'center', // Center-align the legend
										verticalAlign: 'bottom', // Place legend at the bottom
										itemWidth: 150, // Control the width of each legend item for better wrapping
										itemStyle: {
											fontSize: '14px'
										}
									},
								});
							}
							
							if(ChartType == "Bar"){
								Highcharts.chart('container12', {
									chart: {
										type: 'column',
									},
									title: {
										text: ''
									},
									subtitle: {
										text: '<b>Top Bills Receivable '+from_date+' To '+to_date+'</b><br><a style="color:#008ece;" href="<?= admin_url('Sale_reports/BillsReceivableReport')?>" target="_blank">Click To Get Detailed Report </a>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									},
									xAxis: {
										type: 'category',
										labels: {
											autoRotation: [-45, -90],
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											}
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Amount',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											}
										}
									},
									legend: {
										enabled: false
									},
									tooltip: {
										pointFormat: 'QTY : <b>{point.y:.1f} </b>',
										style: {
											fontSize: '12px'  // ⬅️ Increased font size
										}
									},
									series: [{
										name: 'Population',
										colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
										colorByPoint: true,
										groupPadding: 0,
										data: returndata.ChartData,
										dataLabels: {
											enabled: true,
											rotation: -90,
											color: '#FFFFFF',
											inside: true,
											verticalAlign: 'top',
											style: {
												fontSize: '12px'  // ⬅️ Increased font size
											},
											format: '{point.y:.1f}', // one decimal
											y: 10, // 10 pixels down from the top
											
										}
									}]
								});
							}
						}
					});
				}
				$('#search_data').click();
			});
			
			
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
		<script type="text/javascript">
			function printPage(){
				
				var from_date = $("#from_date").val();
				var to_date = $("#to_date").val();
				var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
				var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
				var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->FIRMNAME; ?></td></tr><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2; ?></td></tr>';
				heading_data += '<tr>';
				heading_data += '<td style="text-align:center;"colspan="9">Sales Report : '+from_date+' To '+to_date+'</td>';
				heading_data += '</tr>';
				heading_data += '</tbody></table>';
				var print_data = stylesheet+heading_data+tableData
				newWin= window.open("");
				newWin.document.write(print_data);
				newWin.print();
				newWin.close();
			};
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
				
				
			});
		</script> 																