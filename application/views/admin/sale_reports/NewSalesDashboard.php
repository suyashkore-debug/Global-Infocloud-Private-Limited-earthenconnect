<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>



<div id="wrapper">

	<div class="content" >

	    

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

						<nav aria-label="breadcrumb">

            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">

            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>

            					<li class="breadcrumb-item active text-capitalize"><b>Sales</b></li>

            					<li class="breadcrumb-item active" aria-current="page"><b>Dashboard</b></li>

							</ol>

						</nav>

                        <hr class="hr_style">

					    <div class="row">

							<div class="col-md-6">

							    <div class="row">

							        <div class="col-md-3">

        								<?php

        									echo render_date_input('from_date2','From Date',$from_date);

										?>

									</div>

        							<div class="col-md-3">

        								<?php

        									echo render_date_input('to_date2','To Date',$to_date);

										?>

									</div>

        							<div class="col-md-3">

        								<div class="form-group">

        									<label for="TradeType" class="control-label" ><small class="req text-danger"></small> Trade Type</label>

        									<select class="selectpicker" name="TradeType" id="TradeType" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

        										<option value=""></option>

        										<option value="General">General Trade</option>

        										<option value="Modern">Modern Trade</option>

        										

        										

											</select>

										</div> 

									</div>

									<div class="col-md-3">

										<div class="form-group">

											<label class="control-label" for="ItemType">Item Type</label>

											<select class="selectpicker display-block" data-width="100%" id="ItemType" name="ItemType" data-none-selected-text="None selected">

												<option value="">All</option>

												<option value="Taxable">Taxable</option>

												<option value="NonTaxable">Non Taxable</option>

											</select>

										</div>

									</div>

        							

        							

							        <div class="clearfix"></div>

							        

							        <div class="col-md-4">

        								<div class="form-group">

        									<label for="AccountID" class="control-label" ><small class="req text-danger"></small> Party</label>

        									<select class="selectpicker" name="AccountID" id="AccountID" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

        										

											</select>

										</div> 

									</div>

									

									<div class="col-md-4">

        								<div class="form-group">

        									<label for="Station" class="control-label" ><small class="req text-danger"></small> Station</label>

        									<select class="selectpicker" name="Station" id="Station" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

												

											</select>

										</div> 

									</div>

        							<div class="col-md-4">

        								<div class="form-group">

        									<label for="City" class="control-label" ><small class="req text-danger"></small> City</label>

        									<select class="selectpicker" name="City" id="City" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

												

											</select>

										</div> 

									</div>

									

									<div class="clearfix"></div>

									

									<div class="col-md-4">

										<div class="form-group">

											<label class="form-label">Main Item Group</label>

											<select class="selectpicker" name="MainItemGroup" id="MainItemGroup" data-width="100%" data-none-selected-text="None selected" data-live-search="true">

												<option value=""></option>   

												<?php

													foreach ($MainItemGroup as $key => $value) {

													?>

													<option <?php if($value['id'] == '1'){echo "selected";}?> value="<?php echo $value['id'];?>"><?php echo $value['name']; ?></option>   

													<?php   

													}

												?>

											</select>

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group">

											<label class="control-label" for="SubGroup1">Sub-Group 1</label>

											<select class="selectpicker display-block" data-width="100%" id="SubGroup1" name="SubGroup1" data-none-selected-text="None selected" data-live-search="true" >

												<option value="">None selected</option>

											</select>

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group">

											<label class="control-label" for="SubGroup2">Sub-Group 2</label>

											<select class="selectpicker display-block" data-width="100%" id="SubGroup2" name="SubGroup2" data-none-selected-text="None selected" data-live-search="true" >

												<option value="">None selected</option>

											</select>

										</div>

									</div>

									<div class="clearfix"></div>

									<div class="col-md-4">

										<div class="form-group">

											<label class="control-label" for="ItemID">Item</label>

											<select class="selectpicker display-block" data-width="100%" id="ItemID" name="ItemID" data-none-selected-text="None selected" data-live-search="true" >

												<option value="">None selected</option>

											</select>

										</div>

									</div>

									

        							

        							

        							<div class="col-md-2" style="margin-top:20px;">

        								<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button> 

									</div>

								</div>

							</div>

							<div class="col-md-6">

							    <div class="row">

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="TotalSaleAmtSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalSaleAmt"></p>

        										<p class="title"><?php echo _l('Sale Amt'); ?></p>

											</div>

										</div>

									</div>

        							

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="TotalDiscAmtSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalDiscAmt"></p>

        										<p class="title"><?php echo _l('Disc Amt'); ?></p>

											</div>

										</div>

									</div>

        							

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="TotalFreshRtnAmtSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalFreshRtnAmt"></p>

        										<p class="title"><?php echo _l('Fresh Rtn Amt'); ?></p>

											</div>

										</div>

									</div>

        							

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="TotalDamageRtnAmtSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalDamageRtnAmt"></p>

        										<p class="title"><?php echo _l('Damage Rtn Amt'); ?></p>

											</div>

										</div>

									</div>

        							

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="TotalOrdersSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalOrders"></p>

        										<p class="title"><?php echo _l('Total Order'); ?></p>

											</div>

										</div>

									</div>

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="TotalInvoiceSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalInvoice"></p>

        										<p class="title"><?php echo _l('Total Invoice'); ?></p>

											</div>

										</div>

									</div>

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="PendingOrderSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="PendingOrder"></p>

        										<p class="title"><?php echo _l('Pending Order'); ?></p>

											</div>

										</div>

									</div>

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="CancelOrderSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="CancelOrder"></p>

        										<p class="title"><?php echo _l('Cancel Order'); ?></p>

											</div>

										</div>

									</div>

									<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="AvgOrderValueSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="AvgOrderValue"></p>

        										<p class="title"><?php echo _l('Avg Order Amt'); ?></p>

											</div>

										</div>

									</div>

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="AvgInvoiceValueSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="AvgInvoiceValue"></p>

        										<p class="title"><?php echo _l('Avg Invoice Amt'); ?></p>

											</div>

										</div>

									</div>

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="TotalSoldQtySpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalSoldQty"></p>

        										<p class="title"><?php echo _l('Total Sold Qty'); ?></p>

											</div>

										</div>

									</div>

									<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="GSTCollectionAmtSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="GSTCollectionAmt"></p>

        										<p class="title"><?php echo _l('GST Collection Amt'); ?></p>

											</div>

										</div>

									</div>

									

									<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">

        								<div class="top_stats_wrapper custdesg bg2">

        									<div class="col-md-12">

        									    <p class="TotalSKUSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="TotalSKU"></p>

        										<p class="title"><?php echo _l('Total SKU'); ?></p>

											</div>

										</div>

									</div>

							        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">

        								<div class="top_stats_wrapper custdesg bg1">

        									<div class="col-md-12">

        									    <p class="NewCustomerSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

        										<p class="mtop5 labeltxt" id="NewCustomer"></p>

        										<p class="title"><?php echo _l("New Party's"); ?></p>

											</div>

										</div>

									</div>

									

									<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3">

    									<div class="top_stats_wrapper custdesg bg4">

    										<!--<div class="col-md-3">

    											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>

    										</div>-->

    										<div class="col-md-12">

    										    <p class="BestSellerSKUSpinner mtop5 labeltxt" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

       										<p class="mtop5 labeltxt" id="BestSellerSKU">
        										    <span class="numstyl" style="font-size:9px;"><label style="font-size:9px;" id="BestSellerSKUName" class="labeltxt"></label></span>
    											    <span class="numstyl"><label id="BestSellerSKUAmt" class="labeltxt">0.00</label></span>
       										</p>

       										<p class="title"><?php echo _l("Best Seller SKU"); ?></p>

    										</div>

    									</div>

    								</div>

									

									

        							<div class="clearfix"></div>

        							

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

									<input type="month"  id="month" name="month" class="form-control" value="<?php echo $val;?>">

								</div>								

							</div>

							<div class="col-md-12">

								<figure class="highcharts-figure">

									<div id="container_calander"></div>

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

			<div class="col-md-6">

				<div class="panel_s">

					<div class="panel-body">

						<div class="row">

							<div class="col-md-4">

								<div class="form-group" app-field-wrapper="month">

									<label for="month" class="control-label">Month</label>

									<?php $val = date("Y-m");?>

									<input type="month_sale_return"  id="month_sale_return" name="month_sale_return" class="form-control" value="<?php echo $val;?>">

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

			<div class="col-md-6 TopCustomer Padding_right">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="TopCustomerSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure TopCustomerFigure">

							<div id="TopCustomer"></div>

						</figure>

					</div>

				</div>

			</div>

			<div class="col-md-6 TopGroupItem Padding_left">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="TopGroupItemSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure TopGroupItemFigure">

							<div id="TopGroupItem"></div>

						</figure>

					</div>

				</div>

			</div>

			<div class="col-md-6 StationWiseTopSale Padding_right">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="StationWiseTopSaleSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure StationWiseTopSaleFigure">

							<div id="StationWiseTopSale"></div>

						</figure>

					</div>

				</div>

			</div>

			<div class="col-md-6 CityWiseTopSale Padding_left">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="CityWiseTopSaleSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure CityWiseTopSaleFigure">

							<div id="CityWiseTopSale"></div>

						</figure>

					</div>

				</div>

			</div>

			<!--<div class="col-md-6 MonthWiseSale Padding_right">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="MonthWiseSaleSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure MonthWiseSaleFigure">

							<div id="MonthWiseSale"></div>

						</figure>

					</div>

				</div>

			</div>-->

			<div class="col-md-6 TopCustomerReturnRate Padding_left">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="TopCustomerReturnRateSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure TopCustomerReturnRateFigure">

							<div id="TopCustomerReturnRate"></div>

						</figure>

					</div>

				</div>

			</div>

			<div class="col-md-6 TopReturnRateByItemGroup Padding_right">

	            <div class="panel_s top_stats_wrapper">

					<div class="panel-body">

					    <p class="TopReturnRateByItemGroupSpinner mtop5 SpinnerCSS" style="display: none;"><i class="fa fa-spinner fa-spin"></i></p>

				        <figure class="highcharts-figure TopReturnRateByItemGroupFigure">

							<div id="TopReturnRateByItemGroup"></div>

						</figure>

					</div>

				</div>

			</div>

			

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

						<div class="row">

							<div class="col-md-12">

								<figure class="highcharts-figure">

									<div id="CityWiseSale"></div>

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

									<div id="CityWiseCustomer"></div>

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

									<div id="CrateAlert"></div>

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

									<div id="BillsReceivable"></div>

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

	

	.Padding_right{

	padding-right:1px;

	}

	.Padding_left{

	padding-left:1px;

	}

	.Padding_left_right{

	padding-left:1px;

	padding-right:1px;

	}

	.Padding_right .panel_s .panel-body{

	padding:1px;

	min-height:300px;

	max-height:600px;

	}

	.Padding_left .panel_s .panel-body{

	padding:1px;

	min-height:300px;

	max-height:600px;

	}

	.Padding_left_right .panel_s .panel-body{

	padding:1px;

	min-height:300px;

	max-height:600px;

	}

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

	height:55px;

    }

    .col-md-3.col-sm-3.col-xs-12.quick-stats-invoices {

	padding: 0px 2px 0px 2px;

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

	font-size: 20px;

	color: #fff;

	text-align: center;

	/*font-weight: 700;*/

	margin:0px;

    }

    .SpinnerCSS{

	font-size: 80px;

	color: #FF425C;

	text-align: center;

	margin:0px;

    }

    .title{

	font-size: 13px;

	color: #fff;

	text-align: center;

	/*font-weight: 700;*/

	margin:0px;

    }

    .numstyl{

	text-align: center;

	display: block;

	font-size: 14px;

    }

    /*.mtop5 {

	margin-top: 4px;

	margin-bottom: 2px;

    }*/

    .mtop5 {

	margin-top: 1px;

	margin-bottom: 1px;

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

	margin-bottom: 5px !important;

	}

    .top_stats_wrapper:hover{

	box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.4);

    }

	

	

	#CityWiseSale .highcharts-null-point{

	fill: rgb(84, 79, 197);

	}

	#CityWiseCustomer .highcharts-null-point{

	fill: rgb(84, 79, 197);

	}

</style>





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

<script>

    $(document).ready(function(){

        LoadPartyList();

        LoadCityList();

        LoadStationList();

	})

    function LoadPartyList()

    {

        var TradeType = $("#TradeType").val();

        var FromDate = $("#from_date2").val();

        var ToDate = $("#to_date2").val();

		var url = "<?php echo base_url(); ?>admin/Sale_reports/GetPartyListByTradeType";

		jQuery.ajax({

			type: 'POST',

			url:url,

			data: {TradeType:TradeType,FromDate:FromDate,ToDate:ToDate},

			dataType:'json',

			success: function(response) {

				$("#AccountID").find('option').remove();

				$("#AccountID").selectpicker("refresh");

				$("#AccountID").append(new Option('None selected', ''));

				for (var i = 0; i < response.length; i++) {

					$("#AccountID").append(new Option(response[i].company, response[i].AccountID));

				}

				$('.selectpicker').selectpicker('refresh');

			}

		});

	}

    

    function LoadCityList()

    {

        var TradeType = $("#TradeType").val();

        var FromDate = $("#from_date2").val();

        var ToDate = $("#to_date2").val();

		var url = "<?php echo base_url(); ?>admin/Sale_reports/GetPartyCityListByFilter";

		jQuery.ajax({

			type: 'POST',

			url:url,

			data: {TradeType:TradeType,FromDate:FromDate,ToDate:ToDate},

			dataType:'json',

			success: function(response) {

				$("#City").find('option').remove();

				$("#City").selectpicker("refresh");

				$("#City").append(new Option('None selected', ''));

				for (var i = 0; i < response.length; i++) {

					$("#City").append(new Option(response[i].city_name, response[i].city));

				}

				$('.selectpicker').selectpicker('refresh');

			}

		});

	}

    

    function LoadStationList()

    {

        var TradeType = $("#TradeType").val();

        var FromDate = $("#from_date2").val();

        var ToDate = $("#to_date2").val();

		var url = "<?php echo base_url(); ?>admin/Sale_reports/GetPartyStationListByFilter";

		jQuery.ajax({

			type: 'POST',

			url:url,

			data: {TradeType:TradeType,FromDate:FromDate,ToDate:ToDate},

			dataType:'json',

			success: function(response) {

				$("#Station").find('option').remove();

				$("#Station").selectpicker("refresh");

				$("#Station").append(new Option('None selected', ''));

				for (var i = 0; i < response.length; i++) {

					$("#Station").append(new Option(response[i].StationName, response[i].StationID));

				}

				$('.selectpicker').selectpicker('refresh');

			}

		});

	}

	function isNumber(evt) {

		evt = (evt) ? evt : window.event;

		var charCode = (evt.which) ? evt.which : evt.keyCode;

		if (charCode = 46 && charCode > 31 

		&& (charCode < 48 || charCode > 57)){

			return false;

		}

		return true;

	}

	$('#TradeType').on('change', function() {

		LoadPartyList();

		LoadCityList();

		LoadStationList();

	});

	$('#from_date2').on('change', function() {

		LoadPartyList();

		LoadCityList();

		LoadStationList();

	});

	$('#to_date2').on('change', function() {

		LoadPartyList();

		LoadCityList();

		LoadStationList();

	});

	

	$('#MainItemGroup').on('change', function() {

		var MainItemGroup = $(this).val();

		var ItemType = $('#ItemType').val();

		//alert(roleid);

		var url = "<?php echo base_url(); ?>admin/invoice_items/GetSubgroup1DataNew";

		jQuery.ajax({

			type: 'POST',

			url:url,

			data: {MainItemGroup: MainItemGroup,ItemType:ItemType},

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

	

	$('#SubGroup1').on('change', function() {

		var SubGroup1 = $(this).val();

		var ItemType = $('#ItemType').val();

		//alert(roleid);

		var url = "<?php echo base_url(); ?>admin/invoice_items/GetSubgroup2DataNew";

		jQuery.ajax({

			type: 'POST',

			url:url,

			data: {SubGroup1: SubGroup1,ItemType:ItemType},

			dataType:'json',

			success: function(data) {

				$("#SubGroup2").find('option').remove();

				$("#SubGroup2").selectpicker("refresh");

				$("#SubGroup2").append(new Option('None selected', ''));

				for (var i = 0; i < data.length; i++) {

					$("#SubGroup2").append(new Option(data[i].name, data[i].id));

				}

				$('.selectpicker').selectpicker('refresh');

			}

		});

	});

	$('#SubGroup2').on('change', function() {

		var SubGroup2 = $(this).val();

		var ItemType = $('#ItemType').val();

		//alert(roleid);

		var url = "<?php echo base_url(); ?>admin/invoice_items/GetItemBySubgroup2DataNew";

		jQuery.ajax({

			type: 'POST',

			url:url,

			data: {SubGroup2: SubGroup2,ItemType:ItemType},

			dataType:'json',

			success: function(data) {

				$("#ItemID").find('option').remove();

				$("#ItemID").selectpicker("refresh");

				$("#ItemID").append(new Option('None selected', ''));

				for (var i = 0; i < data.length; i++) {

					$("#ItemID").append(new Option(data[i].description, data[i].item_code));

				}

				$('.selectpicker').selectpicker('refresh');

			}

		});

	});

	$('#ItemType').on('change', function() {

		$("#ItemID").find('option').remove();

		$("#ItemID").selectpicker("refresh");

		$("#ItemID").append(new Option('None selected', ''));

		

		$("#SubGroup1").find('option').remove();

		$("#SubGroup1").selectpicker("refresh");

		$("#SubGroup1").append(new Option('None selected', ''));

		

		$("#SubGroup2").find('option').remove();

		$("#SubGroup2").selectpicker("refresh");

		$("#SubGroup2").append(new Option('None selected', ''));

		$('.selectpicker').selectpicker('refresh');

		

		$('#MainItemGroup').change();

	});

</script>

<script type="text/javascript" language="javascript" >

	$(document).ready(function(){

		$('#MainItemGroup').change();

		function GetCountersValue(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>Sale_reports/GetSalesDashboardCounters",

				dataType:"JSON",

				method:"POST",

				data:{

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.TotalSaleAmtSpinner').show();

				    $('#TotalSaleAmt').hide();

				    $('.TotalDiscAmtSpinner').show();

				    $('#TotalDiscAmt').hide();

				    $('.TotalFreshRtnAmtSpinner').show();

				    $('#TotalFreshRtnAmt').hide();

				    $('.TotalDamageRtnAmtSpinner').show();

				    $('#TotalDamageRtnAmt').hide();

				    $('.TotalOrdersSpinner').show();

				    $('#TotalOrders').hide();

				    $('.TotalInvoiceSpinner').show();

				    $('#TotalInvoice').hide();

				     $('.PendingOrderSpinner').show();

				    $('#PendingOrder').hide();

				    $('.CancelOrderSpinner').show();

				    $('#CancelOrder').hide();

				    $('.AvgOrderValueSpinner').show();

				    $('#AvgOrderValue').hide();

				    $('.AvgInvoiceValueSpinner').show();

				    $('#AvgInvoiceValue').hide();

				    $('.TotalSoldQtySpinner').show();

				    $('#TotalSoldQty').hide();

				    $('.GSTCollectionAmtSpinner').show();

				    $('#GSTCollectionAmt').hide();

				    $('.TotalSKUSpinner').show();

				    $('#TotalSKU').hide();

				    $('.NewCustomerSpinner').show();

				    $('#NewCustomer').hide();

				    $('.BestSellerSKUSpinner').show();

				    $('#BestSellerSKU').hide();

				},

				complete: function () {

				    $('.TotalSaleAmtSpinner').hide();

				    $('#TotalSaleAmt').show();

				    $('.TotalDiscAmtSpinner').hide();

				    $('#TotalDiscAmt').show();

				    $('.TotalFreshRtnAmtSpinner').hide();

				    $('#TotalFreshRtnAmt').show();

				    $('.TotalDamageRtnAmtSpinner').hide();

				    $('#TotalDamageRtnAmt').show();

				    $('.TotalOrdersSpinner').hide();

				    $('#TotalOrders').show();

				    $('.TotalInvoiceSpinner').hide();

				    $('#TotalInvoice').show();

				    $('.AvgOrderValueSpinner').hide();

				    $('#PendingOrder').show();

				    $('.PendingOrderSpinner').hide();

				    $('#CancelOrder').show();

				    $('.CancelOrderSpinner').hide();

				    $('#AvgOrderValue').show();

				    $('.AvgInvoiceValueSpinner').hide();

				    $('#AvgInvoiceValue').show();

				    $('.TotalSoldQtySpinner').hide();

				    $('#TotalSoldQty').show();

				    $('.GSTCollectionAmtSpinner').hide();

				    $('#GSTCollectionAmt').show();

				    $('.TotalSKUSpinner').hide();

				    $('#TotalSKU').show();

				    $('.NewCustomerSpinner').hide();

				    $('#NewCustomer').show();

				    $('.BestSellerSKUSpinner').hide();

				    $('#BestSellerSKU').show();

				},

				success:function(returndata){

					var TotalSaleAmt = returndata.TotalSaleAmt;

					var TotalDiscAmt = returndata.TotalDiscAmt;

					var TotalDamageRtnAmt = returndata.TotalDamageRtnAmt;

					var TotalFreshRtnAmt = returndata.TotalFreshRtnAmt;

					var TotalOrders = returndata.TotalOrders;

					var PendingOrder = returndata.TotalPendingOrder;

					var CancelOrder = returndata.CancelOrder;

					var TotalInvoice = returndata.TotalInvoice;

					var AvgOrderValue = returndata.AvgOrderValue;

					var AvgInvoiceValue = returndata.AvgInvoiceValue;

					var TotalSoldQty = returndata.TotalSoldQty;

					var GSTCollectionAmt = returndata.GSTCollectionAmt;

					var TotalSKU = returndata.ItemCount;

					var NewCustomer = returndata.NewPartys;

					var BestSellerSKUName = returndata.BestSellerSKUName;

					var BestSellerSKUAmt = returndata.BestSellerSKUAmt;

					

					$("#TotalSaleAmt").html(TotalSaleAmt ?? "0");

					$("#TotalDiscAmt").html(TotalDiscAmt ?? "0");

					$("#TotalFreshRtnAmt").html(TotalFreshRtnAmt ?? "0");

					$("#TotalDamageRtnAmt").html(TotalDamageRtnAmt ?? "0");

					$("#TotalOrders").html(TotalOrders ?? "0");

					$("#PendingOrder").html(PendingOrder ?? "0");

					$("#CancelOrder").html(CancelOrder ?? "0");

					$("#TotalInvoice").html(TotalInvoice ?? "0");

					$("#AvgOrderValue").html(AvgOrderValue ?? "0");

					$("#AvgInvoiceValue").html(AvgInvoiceValue ?? "0");

					$("#TotalSoldQty").html(TotalSoldQty ?? "0");

					$("#GSTCollectionAmt").html(GSTCollectionAmt ?? "0");

					$("#TotalSKU").html(TotalSKU ?? "0");

					$("#NewCustomer").html(NewCustomer ?? "0");

					$("#BestSellerSKUName").html(BestSellerSKUName ?? "-");

					$("#BestSellerSKUAmt").html(BestSellerSKUAmt ?? "0");

				}

			});

		}

		$('#search_data').on('click',function(){

			var from_date = $("#from_date2").val();

			var to_date = $("#to_date2").val();

			var TradeType = $("#TradeType").val();

			var AccountID = $("#AccountID").val();

			var MainItemGroup = $("#MainItemGroup").val();

			var SubGroup1 = $("#SubGroup1").val();

			var SubGroup2 = $("#SubGroup2").val();

			var ItemID = $("#ItemID").val();

			var ItemType = $("#ItemType").val();

			var Station = $("#Station").val();

			var City = $("#City").val();

			var Month = $("#month").val();

			

			GetCountersValue(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			TopCustomer(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			TopGroupItem(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			StationWiseTopSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			CityWiseTopSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			TopCustomerReturnRate(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			TopReturnRateByItemGroup(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			DailySaleReport(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			load_calanderChart(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City,Month);

			load_calanderChartReturn(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City,Month);

			load_funnelData(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			CityWiseSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			CityWiseCustomer();

			YOYMonthlySale(TradeType,AccountID,ItemID,MainItemGroup,SubGroup1,SubGroup2,ItemType,Station,City);

			CrateAlert(from_date,to_date,'Bar','7');

			BillsReceivable(from_date,to_date,'Bar','7');

			MonthlySalesReturn(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

			MonthlyBestSellerItems(MainItemGroup,SubGroup1,SubGroup2,ItemID);

			

			// MonthWiseSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City);

		});

		

		

		

		function TopCustomer(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetTopCustomer",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.TopCustomerSpinner').show();

				    $('.TopCustomerFigure').hide();

				},

				complete: function () {

					$('.TopCustomerSpinner').hide();

				    $('.TopCustomerFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('TopCustomer', {

						chart: {

							type: 'column',

							height: 300,

						},

						title: {

							text: ''

						},

						subtitle: {

							text: '<b>Top Customer From '+from_date+' To '+to_date+'</b>',

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

								text: 'Total Amt',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

							}

						},

						legend: {

							enabled: false

						},

						tooltip: {

							pointFormat: 'Total Amt: <b>{point.y:.1f} </b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						series: [{

							name: 'Population',

							colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

							colorByPoint: true,

							groupPadding: 0,

							data: returndata.TransData,

							dataLabels: {

								enabled: true,

								rotation: -90,

								color: '#FFFFFF',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

								inside: true,

								verticalAlign: 'top',

								format: '{point.y:.1f}', // one decimal

								y: 10, // 10 pixels down from the top

								

							}

						}]

					});

				}

			});

		}

		function TopGroupItem(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetTopGroupItem",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.TopGroupItemSpinner').show();

				    $('.TopGroupItemFigure').hide();

				},

				complete: function () {

					$('.TopGroupItemSpinner').hide();

				    $('.TopGroupItemFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('TopGroupItem', {

						chart: {

							type: 'column',

							height: 300,

						},

						title: {

							text: ''

						},

						subtitle: {

							text: '<b>Top Group/Items From '+from_date+' To '+to_date+'</b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						xAxis: {

							type: 'category',

							labels: {

								autoRotation: [-45, -90],

							},

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						yAxis: {

							min: 0,

							title: {

								text: 'Total Amt',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

							}

						},

						legend: {

							enabled: false

						},

						tooltip: {

							pointFormat: 'Total Amt: <b>{point.y:.1f} </b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						series: [{

							name: 'Population',

							colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

							colorByPoint: true,

							groupPadding: 0,

							data: returndata.TransData,

							dataLabels: {

								enabled: true,

								rotation: -90,

								color: '#FFFFFF',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

								inside: true,

								verticalAlign: 'top',

								format: '{point.y:.1f}', // one decimal

								y: 10, // 10 pixels down from the top

								

							}

						}]

					});

				}

			});

		}

		function StationWiseTopSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetStationWiseTopSale",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.StationWiseTopSaleSpinner').show();

				    $('.StationWiseTopSaleFigure').hide();

				},

				complete: function () {

					$('.StationWiseTopSaleSpinner').hide();

				    $('.StationWiseTopSaleFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('StationWiseTopSale', {

						chart: {

							type: 'column',

							height: 300,

						},

						title: {

							text: ''

						},

						subtitle: {

							text: '<b>Station Wise Top Sale From '+from_date+' To '+to_date+'</b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						xAxis: {

							type: 'category',

							labels: {

								autoRotation: [-45, -90],

							},

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						yAxis: {

							min: 0,

							title: {

								text: 'Total Amt',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

							}

						},

						legend: {

							enabled: false

						},

						tooltip: {

							pointFormat: 'Total Amt: <b>{point.y:.1f} </b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						series: [{

							name: 'Population',

							colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

							colorByPoint: true,

							groupPadding: 0,

							data: returndata.TransData,

							dataLabels: {

								enabled: true,

								rotation: -90,

								color: '#FFFFFF',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

								inside: true,

								verticalAlign: 'top',

								format: '{point.y:.1f}', // one decimal

								y: 10, // 10 pixels down from the top

								

							}

						}]

					});

				}

			});

		}

		function CityWiseTopSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetCityWiseTopSale",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.CityWiseTopSaleSpinner').show();

				    $('.CityWiseTopSaleFigure').hide();

				},

				complete: function () {

					$('.CityWiseTopSaleSpinner').hide();

				    $('.CityWiseTopSaleFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('CityWiseTopSale', {

						chart: {

							type: 'column',

							height: 300,

						},

						title: {

							text: ''

						},

						subtitle: {

							text: '<b>City Wise Top Sale From '+from_date+' To '+to_date+'</b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						xAxis: {

							type: 'category',

							labels: {

								autoRotation: [-45, -90],

							},

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						yAxis: {

							min: 0,

							title: {

								text: 'Total Amt',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

							}

						},

						legend: {

							enabled: false

						},

						tooltip: {

							pointFormat: 'Total Amt: <b>{point.y:.1f} </b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						series: [{

							name: 'Population',

							colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

							colorByPoint: true,

							groupPadding: 0,

							data: returndata.TransData,

							dataLabels: {

								enabled: true,

								rotation: -90,

								color: '#FFFFFF',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

								inside: true,

								verticalAlign: 'top',

								format: '{point.y:.1f}', // one decimal

								y: 10, // 10 pixels down from the top

								

							}

						}]

					});

				}

			});

		}

		function MonthWiseSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetMonthWiseSale",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.MonthWiseSaleSpinner').show();

				    $('.MonthWiseSaleFigure').hide();

				},

				complete: function () {

					$('.MonthWiseSaleSpinner').hide();

				    $('.MonthWiseSaleFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('MonthWiseSale', {

						chart: {

							type: 'line',

							height: 253,

						},

						title: {

							text: 'Monthly Stock Level',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						subtitle: {

							text: ''

						},

						xAxis: {

							categories: returndata.Months, // Set dynamically from server

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						yAxis: {

							title: {

								text: 'Stock Qty',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

							}

						},

						plotOptions: {

							line: {

								dataLabels: {

									enabled: true

								},

								enableMouseTracking: false

							}

						},

						series: returndata.Sales

					});

				}

			});

		}

		function TopCustomerReturnRate(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetTopCustomerReturnRate",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.TopCustomerReturnRateSpinner').show();

				    $('.TopCustomerReturnRateFigure').hide();

				},

				complete: function () {

					$('.TopCustomerReturnRateSpinner').hide();

				    $('.TopCustomerReturnRateFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('TopCustomerReturnRate', {

						chart: {

							type: 'column',

							height: 300,

						},

						title: {

							text: ''

						},

						subtitle: {

							text: '<b>Top Customer Wise Return Rate From '+from_date+' To '+to_date+'</b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						xAxis: {

							type: 'category',

							labels: {

								autoRotation: [-45, -90],

							},

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						yAxis: {

							min: 0,

							title: {

								text: 'Total Amt',

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

							}

						},

						legend: {

							enabled: false

						},

						tooltip: {

							pointFormat: 'Total Amt: <b>{point.y:.1f} </b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						series: [{

							name: 'Population',

							colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

							colorByPoint: true,

							groupPadding: 0,

							data: returndata.TransData,

							dataLabels: {

								enabled: true,

								rotation: -90,

								color: '#FFFFFF',

								inside: true,

								verticalAlign: 'top',

								format: '{point.y:.1f}', // one decimal

								style: {

									fontSize: '12px'  // ⬅️ Increased font size

								},

								y: 10, // 10 pixels down from the top

								

							}

						}]

					});

				}

			});

		}

		function TopReturnRateByItemGroup(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City) {

			$.ajax({

				url: "<?php echo admin_url(); ?>Sale_reports/GetTopReturnRateByItemGroup",

				dataType: "JSON",

				method: "POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				    $('.TopReturnRateByItemGroupSpinner').show();

				    $('.TopReturnRateByItemGroupFigure').hide();

				},

				complete: function () {

					$('.TopReturnRateByItemGroupSpinner').hide();

				    $('.TopReturnRateByItemGroupFigure').show();

				},

				success: function (returndata) {

					Highcharts.chart('TopReturnRateByItemGroup', {

						chart: {

							type: 'pie',

							height: 400,

							backgroundColor: '#f8f9fa',

						},

						title: {

							text: ''

						},

						subtitle: {

							text: '<b>Top Group/Item wise return rate % and return % From ' + from_date + ' To ' + to_date + '</b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						tooltip: {

							useHTML: true,

							headerFormat: '<span style="font-size:14px"><b>{point.name}</b></span><br/>',

							pointFormat: '<span style="color:{point.color}">●</span> <b>Return Rate : {point.percentage:.1f}%</b><br/>Sale: <b>{point.sale:,.0f}</b><br/>Return: <b>{point.return:,.0f}</b>',

							style: {

								fontSize: '12px'  // ⬅️ Increased font size

							},

						},

						plotOptions: {

							pie: {

								innerSize: '50%',   // donut effect

								depth: 45,          // 3D style

								allowPointSelect: true,

								cursor: 'pointer',

								dataLabels: {

									enabled: true,

									format: '<b>{point.name}</b><br/>Return Rate : {point.percentage:.1f}%',

									style: {

										fontSize: '12px',

										fontWeight: 'bold'

									}

								},

								showInLegend: true

							}

						},

						legend: {

							align: 'center',

							verticalAlign: 'bottom',

							layout: 'horizontal'

						},

						series: [{

							name: 'Return Rate',

							colorByPoint: true,

							data: returndata.TransData

						}]

					});

					

				}

			});

		}

		function DailySaleReport(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/GetDailySaleReportsNew",

				dataType:"JSON",

				method:"POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

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

		function load_calanderChart(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City,Month)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/GetCalenderMonthlySaleDataNew",

				dataType:"JSON",

				method:"POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

					Month: Month,

				},

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

							type: 'heatmap',

							height: 367,

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

		function load_calanderChartReturn(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City,Month)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/GetCalenderMonthlySaleReturnDataNew",

				dataType:"JSON",

				method:"POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

					Month: Month,

				},

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

							type: 'heatmap',

							height: 367,

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

		function load_funnelData(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/GetCustomerOverviewNew",

				dataType:"JSON",

				method:"POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

				beforeSend: function () {

				},

				complete: function () {

				},

				success:function(returndata){

					Highcharts.chart('container_funnel', {

						chart: {

							type: 'funnel3d',

							height: 367,

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

		function CityWiseSale(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>Sale_reports/CityWiseSalesNew",

				dataType:"JSON",

				method:"POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

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

						

						Highcharts.mapChart('CityWiseSale', {

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

		function CityWiseCustomer()

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

						

						Highcharts.mapChart('CityWiseCustomer', {

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

		

		function CrateAlert(from_date,to_date,ChartType,MaxCount)

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

					Highcharts.chart('CrateAlert', {

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

			});

		}

		function BillsReceivable(from_date,to_date,ChartType,MaxCount)

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

					

					Highcharts.chart('BillsReceivable', {

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

							pointFormat: 'Amount : <b>{point.y:.1f} </b>',

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

			});

		}

		function MonthlySalesReturn(from_date,to_date,TradeType,AccountID,MainItemGroup,SubGroup1,SubGroup2,ItemID,ItemType,Station,City)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/MonthlySaleReturnsNew",

				dataType:"JSON",

				method:"POST",

				data: {

					from_date: from_date,

					to_date: to_date,

					TradeType: TradeType,

					AccountID: AccountID,

					MainItemGroup: MainItemGroup,

					SubGroup1: SubGroup1,

					SubGroup2: SubGroup2,

					ItemID: ItemID,

					ItemType: ItemType,

					Station: Station,

					City: City,

				},

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

		

		function MonthlyBestSellerItems(MainItemGroup,SubGroup1,SubGroup2,ItemID)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/GetMonthlyBestSellerItems",

				dataType:"JSON",

				method:"POST",

				data:{

					MainItemGroup: MainItemGroup,

					SubGroup: SubGroup1,

					SubGroup2: SubGroup2,

				Items: ItemID},

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

		

		function YOYMonthlySale(TradeType,AccountID,ItemID,MainItemGroup,SubGroup1,SubGroup2,ItemType,Station,City)

		{

			$.ajax({

				url:"<?php echo admin_url(); ?>sale_reports/GetYOYMonthlySaleReports",

				dataType:"JSON",

				method:"POST",

				data:{TradeType:TradeType,AccountID:AccountID,ItemID:ItemID,MainItemGroup:MainItemGroup,

				SubGroup1:SubGroup1,SubGroup2:SubGroup2,ItemType:ItemType,Station:Station,City:City},

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

		$('#search_data').click();

		

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