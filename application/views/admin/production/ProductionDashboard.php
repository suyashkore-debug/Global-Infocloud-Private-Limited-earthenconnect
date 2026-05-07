<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<?php

	$PendingProduction = 0;

	$InProgressProduction = 0;

	$CompletedProduction = 0;

	$AllProduction = 0;

	foreach($ProductionStatus as $Production){

		if($Production['production_status'] == "pending"){

			$PendingProduction = $Production['count'];

		}

		if($Production['production_status'] == "In-Progress"){

			$InProgressProduction = $Production['count'];

		}

		if($Production['production_status'] == "Completed"){

			$CompletedProduction = $Production['count'];

		}

	}

	$AllProduction = $PendingProduction+$InProgressProduction+$CompletedProduction;

?>

<div id="wrapper">

	<div class="content" >

	    <div class="row">

			<div class="col-md-12">

				<div class="panel_s">

					<div class="panel-body">

						<nav aria-label="breadcrumb">

            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">

            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>

            					<li class="breadcrumb-item active text-capitalize"><b>Production</b></li>

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

								<!-- ===== LEFT: Filters | RIGHT: Stat Cards — Sales Dashboard layout ===== -->
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-3">
											<?php
												echo render_date_input('from_date','From Date',$from_date);
											?>
										</div>
										<div class="col-md-3">
											<?php
												echo render_date_input('to_date','To Date',$to_date);
											?>
										</div>

										<!-- Chart Type -->
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">Chart Type</label>
												<select name="ChartType" id="ChartType" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
													<option value="Bar">Bar Chart</option>
													<option value="Pie">Pie Chart</option>
												</select>
											</div>
										</div>

										<!-- Max Count -->
										<div class="col-md-3">
											<div class="form-group" app-field-wrapper="ItemCount">
												<label for="ItemCount" class="control-label">Max Count</label>
												<input type="text" id="ItemCount" onkeypress="return isNumber(event)" name="ItemCount" class="form-control" value="5">
											</div>
										</div>

										<!-- SubGroup -->
										<div class="col-md-6">
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

										<!-- Items -->
										<div class="col-md-6">
											<div class="form-group" app-field-wrapper="Items">
												<small class="req text-danger"></small>
												<label for="Items" class="form-label">Item</label>
												<select name="Items[]" multiple id="Items" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
													
												</select>
											</div>
										</div>

										<div class="col-md-12" style="margin-top:10px;">
											<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('search'); ?></button> 
										</div>
									</div>
								</div>

								<!-- RIGHT: KPI Stat Cards matching Sales Dashboard -->
								<div class="col-md-6 prod-cards-panel">
									<div class="row prod-stat-row">

										<!-- Row 1 -->
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-red">
												<p class="PendingProductionSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="PendingProduction"><?php echo $PendingProduction; ?> SKU</p>
												<p class="prod-stat-label">Pending Production</p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-teal">
												<p class="InProgressProductionSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="InProgressProduction"><?php echo $InProgressProduction; ?> SKU</p>
												<p class="prod-stat-label">In-Progress Production</p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-red">
												<p class="CompletedProductionSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="CompletedProduction"><?php echo $CompletedProduction; ?> SKU</p>
												<p class="prod-stat-label">Completed Production</p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-teal">
												<p class="TotalBatchSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="TotalBatchProduction"><?php echo number_format(round($TotalBatchProduction->TotalBatch), 2); ?></p>
												<p class="prod-stat-label">Total Batch Production</p>
											</div>
										</div>

										<!-- Row 2 -->
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-red">
												<p class="HighestYieldPackingSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="HighestYieldPackingVal">
													<span id="HighestYieldPackingPer"><?php echo number_format($HighestYieldPacking->AchievementPercentage,2); ?></span>%
												</p>
												<p class="prod-stat-label"><?php echo _l('Highest Packing Yield SKU'); ?> - <span id="HighestYieldPackingName"><?php echo $HighestYieldPacking->description; ?></span></p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-teal">
												<p class="LowestYieldPackingSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="LowestYieldPackingVal">
													<span id="LowestYieldPackingPer"><?php echo number_format($LowestYieldPacking->AchievementPercentage, 2); ?></span>%
												</p>
												<p class="prod-stat-label"><?php echo _l('Lowest Packing Yield SKU'); ?> - <span id="LowestYieldPackingName"><?php echo $LowestYieldPacking->description; ?></span></p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-red">
												<p class="HighestYieldBakingSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="HighestYieldBakingVal">
													<span id="HighestYieldBakingPer"><?php echo number_format($HighestYieldBaking->AchievementPercentage,2); ?></span>%
												</p>
												<p class="prod-stat-label">Highest Baking Yield SKU - <span id="HighestYieldBakingName"><?php echo $HighestYieldBaking->description; ?></span></p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-teal">
												<p class="LowestYieldBakingSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="LowestYieldBakingVal">
													<span id="LowestYieldBakingPer"><?php echo number_format($LowestYieldBaking->AchievementPercentage,2); ?></span>%
												</p>
												<p class="prod-stat-label">Lowest Baking Yield SKU - <span id="LowestYieldBakingName"><?php echo $LowestYieldBaking->description; ?></span></p>
											</div>
										</div>

										<!-- Row 3 -->
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-red">
												<p class="HighestProductionSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="HighestProductionVal">
													<span id="HighestProductionQty"><?php echo number_format(round($HighestProduction->Finish_good_qty), 2); ?></span>
												</p>
												<p class="prod-stat-label"><?php echo _l('Highest Production SKU'); ?> - <span id="HighestProductionName"><?php echo $HighestProduction->description; ?></span></p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-teal">
												<p class="AvgInvoiceAmtSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="AvgInvoiceAmt">0.00</p>
												<p class="prod-stat-label"><?php echo _l('Todays Highest Revenue SKU'); ?></p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-red">
												<p class="LowestRevenueSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="LowestRevenueSKU"><?php echo $NewParties->NewParty; ?></p>
												<p class="prod-stat-label"><?php echo _l('Todays Lowest Revenue SKU'); ?></p>
											</div>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 prod-stat-col">
											<div class="prod-stat-card bg-green">
												<p class="RMWastageSpinner prod-stat-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i></p>
												<p class="prod-stat-value" id="RMWastageAmt"><?php echo number_format(round($TodaysSale->TotalSale), 2); ?></p>
												<p class="prod-stat-label"><?php echo _l("Todays RM Wastage Amount"); ?></p>
											</div>
										</div>

									</div>
								</div> <!-- end right col-md-6 -->

								<div class="clearfix"></div>

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

					$from_date = "01/".date('m')."/".date('Y');

					// $from_date = date('d/m/Y');

					$to_date = date('d/m/Y');

				}

			?>

			<div class="row" style="display:none;">

				<div class="col-md-12">

					<div class="panel_s">

						<div class="panel-body">

							<div class="row">

								

								<!-- Secondary date filters (from_date2 / to_date2) removed; graphs now use main From/To filters -->

								<!--<div class="col-md-2">

									<?php echo render_input('month','month',date('Y-m'), 'month'); ?>

								</div>-->

								

								<div class="col-md-2">

									<label class="control-label">Chart Type</label>

									<select name="ChartType" id="ChartType" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">

										<option value="Bar">Bar Chart</option>

										<option value="Pie">Pie Chart</option>

									</select>

								</div>

								

								<div class="col-md-2">

									<div class="form-group" app-field-wrapper="ItemCount">

										<label for="ItemCount" class="control-label">Max Count</label>

										<input type="text" id="ItemCount" onkeypress="return isNumber(event)" name="ItemCount" class="form-control" value="5">

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

								

								

								<div class="col-md-6" style="margin-top:20px;">

									<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button> 

								</div>

								

								

							</div>

						</div>

					</div>

				</div>

			</div>

			

			

			<div class="row chart-section">

				<div class="col-md-12">

					<div class="panel_s">

						<div class="panel-body" style="max-height: 600px;">

							<div class="row"> 

								<div class="col-md-12">

									<h4 style="text-align:center;"><b>Date Wise Production</b></h4>

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

						<div class="panel-body" style="max-height: 600px;">

							<div class="row"> 

								<div class="col-md-12">

									<h4 style="text-align:center;"><b>Date Wise RM Consumption In Production</b></h4>

								</div>

								<div class="col-md-12">

									<div class="relative" style="max-height:400px">

										<canvas class="chart" height="400" id="contracts-value-by-type-chart1"></canvas>

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

									<h4 style="text-align:center;"><b>Date Wise PM Consumption In Production</b></h4>

								</div>

								<div class="col-md-12">

									<div class="relative" style="max-height:400px">

										<canvas class="chart" height="400" id="contracts-value-by-type-chart2"></canvas>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				

				

			</div>

			<div class="row chart-section">

				<!-- First Column-->

				<div class="col-md-6">

					<div class="panel_s">

						<div class="panel-body" style="max-height: 600px;">

							

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

									<figure class="highcharts-figure">

										<div id="container2"></div>

									</figure>

								</div>

							</div>

							

						</div>

					</div>

				</div>

				<!-- Third Column-->

				<!-- <div class="col-md-6">

					<div class="panel_s">

						<div class="panel-body" style="max-height: 600px;">

							

							<div class="row">

								<div class="col-md-12">

									<figure class="highcharts-figure">

										<div id="container3"></div>

									</figure>

								</div>

							</div>

							

						</div>

					</div>

				</div> -->

				<!-- Fourth Column-->

				<div class="col-md-6">

					<div class="panel_s">

						<div class="panel-body" style="max-height: 600px;">

							

							<div class="row">

								<div class="col-md-12">

									<figure class="highcharts-figure">

										<div id="container4"></div>

									</figure>

								</div>

							</div>

							

						</div>

					</div>

				</div>

				<!-- Fifth Column-->

				<!-- <div class="col-md-6">

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

				</div> -->

				<!-- Six Column-->

				<div class="col-md-6">

					<div class="panel_s">

						<div class="panel-body" style="max-height: 600px;">

							

							<div class="row">

								<div class="col-md-12">

									<figure class="highcharts-figure">

										<div id="container6"></div>

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

		@import url("https://code.highcharts.com/css/highcharts.css");

		

		/*	.highcharts-pie-series .highcharts-point {

		stroke: #ede;

		stroke-width: 2px;

		}

		#wrapper{

		background: #fff;

		}

		.highcharts-pie-series .highcharts-data-label-connector {

		stroke: silver;

		stroke-dasharray: 2, 2;

		stroke-width: 2px;

		}

		

		.highcharts-figure,

		.highcharts-data-table table {

		min-width: 320px;

		max-width: 600px;

		margin: 1em auto;

		}

		

		.highcharts-data-table table {

		font-family: Verdana, sans-serif;

		border-collapse: collapse;

		border: 1px solid #ebebeb;

		margin: 10px auto;

		text-align: center;

		width: 100%;

		max-width: 500px;

		}

		

		.highcharts-data-table caption {

		padding: 1em 0;

		font-size: 1.2em;

		color: #555;

		}

		

		.highcharts-data-table th {

		font-weight: 600;

		padding: 0.5em;

		}

		

		.highcharts-data-table td,

		.highcharts-data-table th,

		.highcharts-data-table caption {

		padding: 0.5em;

		}

		

		.highcharts-data-table thead tr,

		.highcharts-data-table tr:nth-child(even) {

		background: #f8f8f8;

		}

		

		.highcharts-data-table tr:hover {

		background: #f1f7ff;

		}

		

		.highcharts-description {

		margin: 0.3rem 10px;

		}

		

		*/

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

		

		/* ------------------------------------------------------------------ */
		/* Stat Cards — matching Sales Dashboard style exactly                */
		/* ------------------------------------------------------------------ */
		.prod-stat-row {
			margin: 0;
			display: flex;
			flex-wrap: wrap;
		}
		.prod-stat-col {
			padding: 0 2px 4px 2px;
			box-sizing: border-box;
			display: flex;         /* makes inner card stretch to col height */
		}
		/* ALL cards same fixed height — if text is long it clips, never grows */
		.prod-stat-card {
			border-radius: 4px;
			padding: 5px 4px;
			height: 70px;          /* fixed height — every card identical */
			width: 100%;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			text-align: center;
			cursor: default;
			transition: box-shadow .2s;
			overflow: hidden;      /* clip text that overflows */
		}
		.prod-stat-card:hover {
			box-shadow: 0 6px 18px rgba(0,0,0,.28);
		}
		/* Value number — large bold like Sales Dashboard screenshot */
		.prod-stat-value {
			font-size: 22px;
			
			color: #fff;
			margin: 0 0 2px 0;
			line-height: 1.1;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			max-width: 100%;
		}
		/* Label text — compact below the number */
		.prod-stat-label {
			font-size: 11px;
			color: #fff;
			margin: 0;
			line-height: 1.2;
			overflow: hidden;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			max-width: 100%;
		}
		/* Spinner inside card */
		.prod-stat-spinner {
			font-size: 18px;
			color: #fff;
			margin: 0;
			line-height: 1;
		}
		/* Card colour variants — red + teal alternating as in Sales Dashboard */
		.bg-red    { background: #FF425C; }
		.bg-teal   { background: #008385; }
		.bg-green  { background: #11A578; }
		.bg-orange { background: #FF864A; }
		/* Right card panel - remove Bootstrap default left padding so cards align flush */
		.prod-cards-panel {
			padding-left: 5px;
		}

		/* Chart rows — same horizontal margin as the top filter panel row */
		.chart-section {
			margin-left: 0 !important;
			margin-right: 0 !important;
		}
		.chart-section > [class*="col-"] {
			padding-left: 5px !important;
			padding-right: 5px !important;
		}

		/* keep original named colours for anything else that may use them */
		.custdesg{

		height:90px;

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

		.title{

		font-size: 13px;

		color: #fff;

		text-align: center;

		margin:0px;

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

		

		

	</style>

	

	<?php init_tail(); ?>

	<!--new update -->

	

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

		

		$(document).ready(function(){

			

			$('#search_data_counter').on('click',function(){

				var from_date = $("#from_date").val();

				var to_date = $("#to_date").val();

				

				GetCountersValue(from_date,to_date);

			});

			

			function GetCountersValue(from_date,to_date)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetGetProductionCounters",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date},

					beforeSend: function () {

						// Show all spinners, hide values
						$('.prod-stat-spinner').show();
						$('.prod-stat-value, .prod-stat-label').hide();

					},

					complete: function () {

						// Hide all spinners, show values
						$('.prod-stat-spinner').hide();
						$('.prod-stat-value, .prod-stat-label').show();

					},

					success:function(returndata){

						var ProductionStatus = returndata.ProductionStatus;

						var HighestYieldPacking = returndata.HighestYieldPacking;

						var LowestYieldPacking = returndata.LowestYieldPacking;

						var HighestProduction = returndata.HighestProduction;

						var ProductionYieldStatus = returndata.ProductionYieldStatus;

						var MonthlyYieldStatus = returndata.MonthlyYieldStatus;

						var HighestYieldBaking = returndata.HighestYieldBaking;

						var LowestYieldBaking = returndata.LowestYieldBaking;

						var TotalBatchProduction = returndata.TotalBatchProduction;

						

						

						var PendingProduction = 0;

						var InProgressProduction = 0;

						var CompletedProduction = 0;

						var AllProduction = 0;

						// Iterate through ProductionStatus to get counts

						$.each(ProductionStatus, function (index, Production) {

							if (Production.production_status === "pending") {

								PendingProduction = Production.count;

							}

							if (Production.production_status === "In-Progress") {

								InProgressProduction = Production.count;

							}

							if (Production.production_status === "Completed") {

								CompletedProduction = Production.count;

							}

						});

						

						AllProduction = parseFloat(PendingProduction) + parseFloat(InProgressProduction) + parseFloat(CompletedProduction);

						$("#AllProduction").html(AllProduction);

						$("#PendingProduction").html(PendingProduction + " SKU");

						$("#InProgressProduction").html(InProgressProduction + " SKU");

						$("#CompletedProduction").html(CompletedProduction + " SKU");

						$("#HighestYieldPackingName").html(HighestYieldPacking?.description ?? " ");

						$("#HighestYieldPackingPer").html(parseFloat(HighestYieldPacking?.AchievementPercentage ?? 0.00).toFixed(2));

						$("#LowestYieldPackingName").html(LowestYieldPacking?.description ?? " ");

						$("#LowestYieldPackingPer").html(parseFloat(LowestYieldPacking?.AchievementPercentage ?? 0.00).toFixed(2));

						$("#HighestProductionName").html(HighestProduction?.description ?? " ");

						$("#HighestProductionQty").html(parseFloat(HighestProduction?.Finish_good_qty ?? 0.00).toFixed(2));

						$("#ProductionYieldStatusStd").html(parseFloat(ProductionYieldStatus?.StandardQty ?? 0).toFixed(2));

						$("#ProductionYieldStatusBaking").html(parseFloat(ProductionYieldStatus?.BakingQty ?? 0).toFixed(2));

						$("#ProductionYieldStatusPacking").html(parseFloat(ProductionYieldStatus?.PackingQty ?? 0).toFixed(2));

						$("#HighestYieldBakingName").html(HighestYieldBaking?.description ?? " ");

						$("#HighestYieldBakingPer").html(parseFloat(HighestYieldBaking?.AchievementPercentage ?? 0.00).toFixed(2));

						$("#LowestYieldBakingName").html(LowestYieldBaking?.description ?? " ");

						$("#LowestYieldBakingPer").html(parseFloat(LowestYieldBaking?.AchievementPercentage ?? 0.00).toFixed(2));

						$("#TotalBatchProduction").html(parseFloat(TotalBatchProduction?.TotalBatch ?? 0.00).toFixed(2));

						$("#AvgInvoiceAmt").html(parseFloat(AvgInvoiceAmt?.TotalBatch ?? 0.00).toFixed(2));

						

					}

				});

			}

			

			$('#search_data').on('click',function(){

				// Use main date filters for graphs and cards

				var from_date = $("#from_date").val();

				var to_date = $("#to_date").val();

				var ChartType = $("#ChartType").val();

				var MaxCount = $("#ItemCount").val();

				var SubGroup = $("#SubGroup").val();

				var Items = $("#Items").val();

				var report = "3";

				var report2 = "4";

				

				// Update top counters and cards
				GetCountersValue(from_date,to_date);

				// Load all graphs
				load_data1(from_date,to_date,Items,SubGroup);

				load_data2(from_date,to_date,ChartType,MaxCount,SubGroup,Items);

				load_data3(from_date,to_date,ChartType,MaxCount,SubGroup,Items);

				// load_data4(from_date,to_date,ChartType,MaxCount,SubGroup,Items); // Production VS Baking graph disabled

				load_data5(from_date,to_date,ChartType,MaxCount,SubGroup,Items);

				// load_data6(from_date,to_date,ChartType,MaxCount,SubGroup,Items); // Baking VS Packing graph disabled

				load_data7(from_date,to_date,ChartType,MaxCount,SubGroup,Items);

				load_data8(from_date,to_date,Items,SubGroup);

				load_data9(from_date,to_date,Items,SubGroup);

			});

			

			function load_data1(from_date,to_date,Items,SubGroup)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetDailyProductionReports",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup},

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

			function load_data8(from_date,to_date,Items,SubGroup)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetDateWiseRMISSUE",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup},

					beforeSend: function () {

					},

					complete: function () {

					},

					success:function(returndata){

						new Chart($('#contracts-value-by-type-chart1'), {

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

			function load_data9(from_date,to_date,Items,SubGroup)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetDateWisePMISSUE",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,Items:Items,SubGroup:SubGroup},

					beforeSend: function () {

					},

					complete: function () {

					},

					success:function(returndata){

						new Chart($('#contracts-value-by-type-chart2'), {

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

			

			function load_data2(from_date,to_date,ChartType,MaxCount,SubGroup,Items)

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

						

						Highcharts.chart('container', {

							chart: {

								type: 'column'

							},

							title: {

								text: ''

							},

							subtitle: {

								text: '<b>PRODUCTION VS SALES '+from_date+' To '+to_date+'</b>'

							},

							xAxis: {

								type: 'category',

								labels: {

									autoRotation: [-45, -90],

								}

							},

							yAxis: {

								min: 0,

								title: {

									text: 'Qty In(Unit)'

								}

							},

							tooltip: {

								pointFormat: 'QTY : <b>{point.y:.1f} </b>'

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

			

			function load_data3(from_date,to_date,ChartType,MaxCount,SubGroup,Items)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetTopProduction",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items},

					beforeSend: function () {

					},

					complete: function () {

					},

					success:function(returndata){

						if(ChartType == "Pie"){

							Highcharts.chart('container2', {

								chart: {

									type: 'pie',

									height: 400,

								},

								colors: [ '#119EFA','#15f34f','#ef370d','#791db2', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91ef','#ED561B'],

								title: {

									text: '',

								},

								subtitle: {

									text: '<b>Production '+from_date+' To '+to_date+'</b>'

								},

								plotOptions: {

									pie: {

										allowPointSelect: true,

										cursor: 'pointer',

										dataLabels: {

											enabled: true,

											format: '<b>{point.name}</b>: {point.percentage:.1f}%',

											distance: 15,

											style: {

												fontSize: '11px'

											}

										},

										showInLegend: true

									}

								},

								series: [{

									type: 'pie',

									allowPointSelect: true,

									keys: ['name', 'y', 'selected', 'sliced'],

									data: returndata.Production,

									showInLegend: true

								}],

								legend: {

									layout: 'horizontal',

									align: 'center',

									verticalAlign: 'bottom',

									itemWidth: 150,

									itemStyle: {

										fontSize: '12px'

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

									text: '<b>Production '+from_date+' To '+to_date+'</b>'

								},

								xAxis: {

									type: 'category',

									labels: {

										autoRotation: [-45, -90],

									}

								},

								yAxis: {

									min: 0,

									title: {

										text: 'Qty In (Unit)'

									}

								},

								legend: {

									enabled: false

								},

								tooltip: {

									pointFormat: 'QTY : <b>{point.y:.1f} </b>'

								},

								series: [{

									name: 'Population',

									colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

									colorByPoint: true,

									groupPadding: 0,

									data: returndata.Production,

									dataLabels: {

										enabled: true,

										rotation: -90,

										color: '#FFFFFF',

										inside: true,

										verticalAlign: 'top',

										format: '{point.y:.1f}', // one decimal

										y: 10, // 10 pixels down from the top

										

									}

								}]

							});

						}

						

					}

				});

			}

			

			function load_data4(from_date,to_date,ChartType,MaxCount,SubGroup,Items)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetProduction_VS_Baking",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items},

					beforeSend: function () {

					},

					complete: function () {

					},

					success:function(returndata){

						if(ChartType == "Pie"){

							Highcharts.chart('container3', {

								chart: {

									type: 'pie',

									height: 400,

								},

								colors: [ '#119EFA','#15f34f','#ef370d','#791db2', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91ef','#ED561B'],

								title: {

									text: '',

								},

								subtitle: {

									text: '<b>Production VS Baking '+from_date+' To '+to_date+'</b>'

								},

								plotOptions: {

									pie: {

										allowPointSelect: true,

										cursor: 'pointer',

										dataLabels: {

											enabled: true,

											format: '<b>{point.name}</b>: {point.percentage:.1f}%',

											distance: 15,

											style: {

												fontSize: '11px'

											}

										},

										showInLegend: true

									}

								},

								series: [{

									type: 'pie',

									allowPointSelect: true,

									keys: ['name', 'y', 'selected', 'sliced'],

									data: returndata.Production,

									showInLegend: true

								}],

								legend: {

									layout: 'horizontal',

									align: 'center',

									verticalAlign: 'bottom',

									itemWidth: 150,

									itemStyle: {

										fontSize: '12px'

									}

								},

							});

						}

						if(ChartType == "Bar"){

							Highcharts.chart('container3', {

								chart: {

									type: 'column'

								},

								title: {

									text: ''

								},

								subtitle: {

									text: '<b>Production VS Baking '+from_date+' To '+to_date+'</b>'

								},

								xAxis: {

									type: 'category',

									labels: {

										autoRotation: [-45, -90],

									}

								},

								yAxis: {

									min: 0,

									title: {

										text: 'Percentage'

									}

								},

								legend: {

									enabled: false

								},

								tooltip: {

									pointFormat: 'Percentage : <b>{point.y:.1f} </b>'

								},

								series: [{

									name: 'Population',

									colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

									colorByPoint: true,

									groupPadding: 0,

									data: returndata.Production,

									dataLabels: {

										enabled: true,

										rotation: -90,

										color: '#FFFFFF',

										inside: true,

										verticalAlign: 'top',

										format: '{point.y:.1f}', // one decimal

										y: 10, // 10 pixels down from the top

										

									}

								}]

							});

						}

						

					}

				});

			}

			function load_data5(from_date,to_date,ChartType,MaxCount,SubGroup,Items)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetProduction_VS_Packing",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items},

					beforeSend: function () {

					},

					complete: function () {

					},

					success:function(returndata){

						if(ChartType == "Pie"){

							Highcharts.chart('container4', {

								chart: {

									type: 'pie',

									height: 400,

								},

								colors: [ '#119EFA','#15f34f','#ef370d','#791db2', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91ef','#ED561B'],

								title: {

									text: '',

								},

								subtitle: {

									text: '<b>Production VS Packing '+from_date+' To '+to_date+'</b>'

								},

								plotOptions: {

									pie: {

										allowPointSelect: true,

										cursor: 'pointer',

										dataLabels: {

											enabled: true,

											format: '<b>{point.name}</b>: {point.percentage:.1f}%',

											distance: 15,

											style: {

												fontSize: '11px'

											}

										},

										showInLegend: true

									}

								},

								series: [{

									type: 'pie',

									allowPointSelect: true,

									keys: ['name', 'y', 'selected', 'sliced'],

									data: returndata.Production,

									showInLegend: true

								}],

								legend: {

									layout: 'horizontal',

									align: 'center',

									verticalAlign: 'bottom',

									itemWidth: 150,

									itemStyle: {

										fontSize: '12px'

									}

								},

							});

						}

						if(ChartType == "Bar"){

							Highcharts.chart('container4', {

								chart: {

									type: 'column'

								},

								title: {

									text: ''

								},

								subtitle: {

									text: '<b>Production VS Packing '+from_date+' To '+to_date+'</b>'

								},

								xAxis: {

									type: 'category',

									labels: {

										autoRotation: [-45, -90],

									}

								},

								yAxis: {

									min: 0,

									title: {

										text: 'Percentage'

									}

								},

								legend: {

									enabled: false

								},

								tooltip: {

									pointFormat: 'Percentage : <b>{point.y:.1f} </b>'

								},

								series: [{

									name: 'Population',

									colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

									colorByPoint: true,

									groupPadding: 0,

									data: returndata.Production,

									dataLabels: {

										enabled: true,

										rotation: -90,

										color: '#FFFFFF',

										inside: true,

										verticalAlign: 'top',

										format: '{point.y:.1f}', // one decimal

										y: 10, // 10 pixels down from the top

										

									}

								}]

							});

						}

						

					}

				});

			}

			function load_data6(from_date,to_date,ChartType,MaxCount,SubGroup,Items)

			{

				$.ajax({

					url:"<?php echo admin_url(); ?>production/GetBaking_VS_Packing",

					dataType:"JSON",

					method:"POST",

					data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items},

					beforeSend: function () {

					},

					complete: function () {

					},

					success:function(returndata){

						if(ChartType == "Pie"){

							Highcharts.chart('container5', {

								chart: {

									type: 'pie',

									height: 400,

								},

								colors: [ '#119EFA','#15f34f','#ef370d','#791db2', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91ef','#ED561B'],

								title: {

									text: '',

								},

								subtitle: {

									text: '<b>Baking VS Packing '+from_date+' To '+to_date+'</b>'

								},

								plotOptions: {

									pie: {

										allowPointSelect: true,

										cursor: 'pointer',

										dataLabels: {

											enabled: true,

											format: '<b>{point.name}</b>: {point.percentage:.1f}%',

											distance: 15,

											style: {

												fontSize: '11px'

											}

										},

										showInLegend: true

									}

								},

								series: [{

									type: 'pie',

									allowPointSelect: true,

									keys: ['name', 'y', 'selected', 'sliced'],

									data: returndata.Production,

									showInLegend: true

								}],

								legend: {

									layout: 'horizontal',

									align: 'center',

									verticalAlign: 'bottom',

									itemWidth: 150,

									itemStyle: {

										fontSize: '12px'

									}

								},

							});

						}

						if(ChartType == "Bar"){

							Highcharts.chart('container5', {

								chart: {

									type: 'column'

								},

								title: {

									text: ''

								},

								subtitle: {

									text: '<b>Baking VS Packing '+from_date+' To '+to_date+'</b>'

								},

								xAxis: {

									type: 'category',

									labels: {

										autoRotation: [-45, -90],

									}

								},

								yAxis: {

									min: 0,

									title: {

										text: 'Percentage'

									}

								},

								legend: {

									enabled: false

								},

								tooltip: {

									pointFormat: 'Percentage : <b>{point.y:.1f} </b>'

								},

								series: [{

									name: 'Population',

									colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

									colorByPoint: true,

									groupPadding: 0,

									data: returndata.Production,

									dataLabels: {

										enabled: true,

										rotation: -90,

										color: '#FFFFFF',

										inside: true,

										verticalAlign: 'top',

										format: '{point.y:.1f}', // one decimal

										y: 10, // 10 pixels down from the top

										

									}

								}]

							});

						}

						

					}

				});

			}

			function load_data7(from_date,to_date,ChartType,MaxCount,SubGroup,Items)

			{

				$.ajax({

					url: "<?php echo admin_url(); ?>production/GetProduction_Vs_Sales",

					dataType: "JSON",

					method: "POST",

					data: {

						from_date: from_date,

						to_date: to_date,

						ChartType: ChartType,

						MaxCount: MaxCount,

						SubGroup: SubGroup,

						Items: Items

					},

					success: function (returndata) {

						let salesData = returndata.Sales.map(item => [new Date(item.z).getTime(), item.y]);

						let productionData = returndata.Production.map(item => [new Date(item.z).getTime(), item.y]);

						

						// Sorting the data by date (important for Highcharts)

						salesData.sort((a, b) => a[0] - b[0]);

						productionData.sort((a, b) => a[0] - b[0]);

						

						Highcharts.chart('container6', {

							title: { text: 'Date Wise Production VS Sales', align: 'left' },

							yAxis: { title: { text: 'Quantity' } },

							xAxis: { 

								type: 'datetime', 

								title: { text: 'Date' },

								dateTimeLabelFormats: { day: '%d %b' }, // Show only date (e.g., 14 Mar)

								labels: { format: '{value:%d %b}' } // Ensures labels are formatted correctly

							},

							legend: {

								layout: 'vertical',

								align: 'right',

								verticalAlign: 'middle'

							},

							plotOptions: {

								series: {

									marker: { enabled: true }, // Show data points

									lineWidth: 2 // Make lines visible

								}

							},

							series: [

							{ name: 'Production', data: productionData, color: '#3498db' },

							{ name: 'Sales', data: salesData, color: '#2c3e50' }

							],

							responsive: {

								rules: [{

									condition: { maxWidth: 500 },

									chartOptions: {

										legend: {

											layout: 'horizontal',

											align: 'center',

											verticalAlign: 'bottom'

										}

									}

								}]

							}

						});

					}

				});

				

				

			}

			

			

			

			

			$('#search_data').click();

			$('#search_data_counter').click();

			

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