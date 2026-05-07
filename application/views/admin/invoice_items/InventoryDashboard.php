<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
	$FinishedGoodSKU = 0;
	$RawMaterialSKU = 0;
	$PackingMaterialSKU = 0;
	$AllSKUs = 0;
	foreach($TotalSKUCount as $TotalSKU){
		if($TotalSKU['MainGrpID'] == "1"){
			$FinishedGoodSKU = $TotalSKU['count'];
		}
		if($TotalSKU['MainGrpID'] == "2"){
			$RawMaterialSKU = $TotalSKU['count'];
		}
		if($TotalSKU['MainGrpID'] == "3"){
			$PackingMaterialSKU = $TotalSKU['count'];
		}
	}
	$AllSKUs = $FinishedGoodSKU+$RawMaterialSKU+$PackingMaterialSKU;
	
	
	
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
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
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
											<p class="mtop5 labeltxt"> <?php echo _l('Total Number Of SKU :'); ?><?= $AllSKUs?><br>
												<span class="numstyl">Finish Goods : <?php echo $FinishedGoodSKU; ?> SKU</span>
												<span class="numstyl" >Raw Material : <?php echo $RawMaterialSKU; ?> SKU</span>
											<span class="numstyl">Packing Material : <?php echo $PackingMaterialSKU; ?> SKU</span></p>
											<div class="clearfix"></div>
											
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">
									<div class="top_stats_wrapper custdesg bg2">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-users"></i></p>
										</div>
										
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Expiring Soon SKU'); ?> : 0 SKU<br>
												<span class="numstyl">Finish Good  : 0 SKU  : ₹ 0</span>
												<span class="numstyl">Raw Material : 0 SKU : ₹ 0</span>
											<span class="numstyl">Packing Material : 0 SKU : ₹ 0</span></p>
											<div class="clearfix"></div>
											
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">
									<div class="top_stats_wrapper custdesg bg3">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Out Of Stock SKU'); ?> : <label id="AllItemOutstock" class="labeltxt">0</label> SKU<br>
												<span class="numstyl">Finish Good  : <label id="FinishGoodOutstock" class="labeltxt">0</label> SKU</span>
												<span class="numstyl">Raw Material : <label id="RawMaterialOutstock" class="labeltxt">0</label> SKU</span>
											<span class="numstyl">Packing Material : <label id="PackingMaterialOutstock" class="labeltxt">0</label> SKU</span></p>
											<div class="clearfix"></div>
											
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 <?php echo $initial_column; ?>">
									<div class="top_stats_wrapper custdesg bg4">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-area-chart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Total Wastage/Scrap SKU'); ?> : ₹ 0<br>
												<span class="numstyl">Finish Good  : 0 SKU  : ₹ 0</span>
												<span class="numstyl">Raw Material : 0 SKU : ₹ 0</span>
											<span class="numstyl">Packing Material : 0 SKU : ₹ 0</span></p>
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
											<p class="mtop5 labeltxt"><?php echo _l('Stock Value'); ?> : ₹ <label id="AllItemValue" class="labeltxt">0</label><br>
												<span class="numstyl">Finish Good  : ₹ <label id="FinishGoodValue" class="labeltxt">0</label></span>
												<span class="numstyl">Raw Material : ₹ <label id="RawMaterialValue" class="labeltxt">0</label></span>
											<span class="numstyl">Packing Material : ₹ <label id="PackingMaterialValue" class="labeltxt">0</label></span></p>
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
											<p class="mtop5 labeltxt"><?php echo _l('Expired SKU'); ?> :0 : ₹ 0<br>
												<span class="numstyl">Finish Good  : 0 SKU  : ₹ 0</span>
												<span class="numstyl">Raw Material : 0 SKU : ₹ 0</span>
											<span class="numstyl">Packing Material : 0 SKU : ₹ 0</span></p>
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
											<p class="mtop5 labeltxt"><?php echo _l('Total Stock Received'); ?> : ₹ 0<br>
												<span class="numstyl">Raw Material : ₹ 0</span>
											<span class="numstyl">Packing Material : ₹ 0</span></p>
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
											<p class="mtop5 labeltxt"><?php echo _l('Low Stock SKU'); ?> : <label id="AllItemLowstock" class="labeltxt">0</label> <br>
											
												<span class="numstyl">Finish Good  : <label id="FinishGoodLowstock" class="labeltxt">0</label> SKU</span>
												<span class="numstyl">Raw Material : <label id="RawMaterialLowstock" class="labeltxt">0</label> SKU</span><span class="numstyl">Packing Material : <label id="PackingMaterialLowstock" class="labeltxt">0</label> SKU</span></p>
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
											<p class="mtop5 labeltxt"><?php echo _l('Highest Stock SKU'); ?> : 0<br>
												<span class="numstyl">Finish Good  : 0 SKU  : ₹ 0</span>
												<span class="numstyl">Raw Material : 0 SKU : ₹ 0</span>
											<span class="numstyl">Packing Material : 0 SKU : ₹ 0</span></p>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								
								<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
									<div class="top_stats_wrapper custdesg bg2">
										<div class="col-md-3">
											<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
										</div>
										<div class="col-md-9">
											<p class="mtop5 labeltxt"><?php echo _l('Total Stock ISSUE'); ?> : ₹ 0<br>
												<span class="numstyl">Raw Material : ₹ 0</span>
											<span class="numstyl">Packing Material : ₹ 0</span></p>
											<div class="clearfix"></div>
											
										</div>
									</div>
								</div>
								
								<div class="clearfix"></div>
								
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
								<div class="form-group" app-field-wrapper="SubGroup">
									<small class="req text-danger"></small>
									<label for="SubGroup2" class="form-label">SubGroup 2</label>
									<select name="SubGroup2[]" multiple id="SubGroup2" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
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
							
							
							<div class="col-md-6" style="margin-top:20px;">
								<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button> 
							</div>
							
							
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
			<div class="col-md-6">
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
			</div>
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
	        <!-- Sixth Column-->
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
	        <!-- Seventh Column-->
			<div class="col-md-6">
	            <div class="panel_s">
					<div class="panel-body" style="max-height: 600px;">
						<div class="row">
						    <div class="col-md-12">
						        <figure class="highcharts-figure">
									<div id="container7"></div>
								</figure>
							</div>
						</div>
						
					</div>
				</div>
			</div>
	        <!-- Eight Column-->
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
	        <!-- Nine Column-->
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
    .numstyl{
	text-align: left;
	display: block;
	font-size: 11px;
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
		
		$.ajax({
			url:"<?php echo admin_url(); ?>invoice_items/GetSubGroup2ByGroupID",
			dataType:"JSON",
			method:"POST",
			data:{SubGroup1:SubGroup},
			beforeSend: function () {
			},
			complete: function () {
			},
			success:function(data){
			    let subgroupList = data;
    			$("#SubGroup2").children().remove();
    			for (var i = 0; i < subgroupList.length; i++) {
    				$("#SubGroup2").append('<option value="'+subgroupList[i]["id"]+'">'+subgroupList[i]["name"]+'</option>');
				}
    			$('.selectpicker').selectpicker('refresh');
			}
		});
	});
    $('#SubGroup2').on('change',function(){
		var SubGroup = $("#SubGroup").val();
		var SubGroup2 = $("#SubGroup2").val();
		$.ajax({
			url:"<?php echo admin_url(); ?>invoice_items/GetGroupsWiseItemList",
			dataType:"JSON",
			method:"POST",
			data:{SubGroup:SubGroup,SubGroup2:SubGroup2},
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
					url:"<?php echo admin_url(); ?>invoice_items/GetInventoryCounters",
					dataType:"JSON",
					method:"POST",
					data:{from_date:from_date,to_date:to_date},
					beforeSend: function () {
					},
					complete: function () {
					},
					success:function(returndata){
						
						var StockValue = returndata.StockValue;
						var OutOfStockSKU = returndata.OutOfStockSKU;
						var LowStockSKU = returndata.LowStockSKU;
						
						var FinishGoodValue = 0;
						var RawMaterialValue = 0;
						var PackingMaterialValue = 0;
						var AllItemValue = 0;
						// Iterate through ProductionStatus to get counts
						$.each(StockValue, function (index, Value) {
							if (index === "FinishGoodValue") {
								FinishGoodValue = FinishGoodValue + parseFloat(Value);
							}
							if (index === "RawMaterialValue") {
								RawMaterialValue = RawMaterialValue + parseFloat(Value);
							}
							if (index === "PackingMaterialValue") {
								PackingMaterialValue = PackingMaterialValue + parseFloat(Value);
							}
						});
						AllItemValue = parseFloat(FinishGoodValue) + parseFloat(RawMaterialValue) + parseFloat(PackingMaterialValue);
						$("#AllItemValue").html(parseFloat(AllItemValue).toFixed(2));
						$("#FinishGoodValue").html(parseFloat(FinishGoodValue).toFixed(2));
						$("#RawMaterialValue").html(parseFloat(RawMaterialValue).toFixed(2));
						$("#PackingMaterialValue").html(parseFloat(PackingMaterialValue).toFixed(2));
						
						
						var FinishGoodOutstock = 0;
						var RawMaterialOutstock = 0;
						var PackingMaterialOutstock = 0;
						var AllItemOutstock = 0;
						// Iterate through ProductionStatus to get counts
						$.each(OutOfStockSKU, function (index, Value) {
							if (index === "FinishGoodValue") {
								FinishGoodOutstock = FinishGoodOutstock + parseFloat(Value);
							}
							if (index === "RawMaterialValue") {
								RawMaterialOutstock = RawMaterialOutstock + parseFloat(Value);
							}
							if (index === "PackingMaterialValue") {
								PackingMaterialOutstock = PackingMaterialOutstock + parseFloat(Value);
							}
						});
						AllItemOutstock = parseFloat(FinishGoodOutstock) + parseFloat(RawMaterialOutstock) + parseFloat(PackingMaterialOutstock);
						$("#AllItemOutstock").html(parseFloat(AllItemOutstock).toFixed(2));
						$("#FinishGoodOutstock").html(parseFloat(FinishGoodOutstock).toFixed(2));
						$("#RawMaterialOutstock").html(parseFloat(RawMaterialOutstock).toFixed(2));
						$("#PackingMaterialOutstock").html(parseFloat(PackingMaterialOutstock).toFixed(2));
						
						var FinishGoodLowstock = 0;
						var RawMaterialLowstock = 0;
						var PackingMaterialLowstock = 0;
						var AllItemOutstock = 0;
						// Iterate through ProductionStatus to get counts
						$.each(LowStockSKU, function (index, Value) {
							if (index === "FinishGoodValue") {
								FinishGoodLowstock = FinishGoodLowstock + parseFloat(Value);
							}
							if (index === "RawMaterialValue") {
								RawMaterialLowstock = RawMaterialLowstock + parseFloat(Value);
							}
							if (index === "PackingMaterialValue") {
								PackingMaterialLowstock = PackingMaterialLowstock + parseFloat(Value);
							}
						});
						AllItemLowstock = parseFloat(FinishGoodLowstock) + parseFloat(RawMaterialLowstock) + parseFloat(PackingMaterialLowstock);
						$("#AllItemLowstock").html(parseFloat(AllItemLowstock).toFixed(2));
						$("#FinishGoodLowstock").html(parseFloat(FinishGoodLowstock).toFixed(2));
						$("#RawMaterialLowstock").html(parseFloat(RawMaterialLowstock).toFixed(2));
						$("#PackingMaterialLowstock").html(parseFloat(PackingMaterialLowstock).toFixed(2));
					}
				});
			}
		$('#search_data').on('click',function(){
			//var month = $("#month").val();
			var from_date = $("#from_date2").val();
			var to_date = $("#to_date2").val();
			var ChartType = $("#ChartType").val();
			var MaxCount = $("#ItemCount").val();
			var SubGroup = $("#SubGroup").val();
			var SubGroup2 = $("#SubGroup2").val();
			var Items = $("#Items").val();
			
			
			load_data(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data2(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data3(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data4(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data5(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data6(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data7(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data8(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
			load_data9(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2);
		});
		
		function load_data(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetTopInventoryItem",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
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
								text: '<b>Top Inventory Items  '+from_date+' To '+to_date+'</b>'
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
						Highcharts.chart('container', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Top Inventory Items  '+from_date+' To '+to_date+'</b>'
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
									text: 'Qty (Unit)'
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
								data: returndata.ChartData,
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
		function load_data2(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>production/Prod_VS_Sales",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					
					Highcharts.chart('container2', {
						chart: {
							type: 'column'
						},
						title: {
							text: ''
						},
						subtitle: {
							text: '<b>PRODUCTION VS SALES  '+from_date+' To '+to_date+'</b>'
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
		function load_data3(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/Purchase_VS_Sales",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					
					Highcharts.chart('container3', {
						chart: {
							type: 'column'
						},
						title: {
							text: ''
						},
						subtitle: {
							text: '<b>PURCHASE VS SALES  '+from_date+' To '+to_date+'</b>'
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
							name: 'Purchase',
							data: returndata.Purchase,
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
		
		
		function load_data4(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>purchase/GetTopPurchaseItem",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					if(ChartType == "Pie"){
						Highcharts.chart('container4', {
							chart: {
								styledMode: true,  
								height: 600, // Increase chart height
								spacing: [10, 100, 10, 10],
							},
							title: {
								text: '',
							},
							subtitle: {
								text: '<b>Top Purchase Items  '+from_date+' To '+to_date+'</b>'
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
						Highcharts.chart('container4', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Top Purchase Items '+from_date+' To '+to_date+'</b>'
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
									text: 'Purchase Qty (Unit)'
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
								data: returndata.ChartData,
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
		
		function load_data5(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>sale_reports/GetTopSellingItemInventory",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					if(ChartType == "Pie"){
						Highcharts.chart('container5', {
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
						Highcharts.chart('container5', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Top Selling Items '+from_date+' To '+to_date+'</b>'
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
									text: 'Selling Qty (Unit)'
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
								data: returndata.ChartData,
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
		function load_data6(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/PurchaseRegisterItemWise",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					if(ChartType == "Pie"){
						Highcharts.chart('container6', {
							chart: {
								styledMode: true,  
								height: 600, // Increase chart height
								spacing: [10, 100, 10, 10],
							},
							title: {
								text: '',
							},
							subtitle: {
								text: '<b>Purchase Register '+from_date+' To '+to_date+'</b>'
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
								data: returndata.Purchase,
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
						Highcharts.chart('container6', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Purchase Register '+from_date+' To '+to_date+'</b>'
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
									text: 'Rate'
								}
							},
							legend: {
								enabled: false
							},
							tooltip: {
								pointFormat: 'Rate : <b>{point.y:.1f} </b>'
							},
							series: [{
								name: 'Population',
								colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
								colorByPoint: true,
								groupPadding: 0,
								data: returndata.Purchase,
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
		
		function load_data7(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			var MainGrpID = '1';
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetTopInventoryItem",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,MainGrpID:MainGrpID,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					if(ChartType == "Pie"){
						Highcharts.chart('container7', {
							chart: {
								styledMode: true,  
								height: 600, // Increase chart height
								spacing: [10, 100, 10, 10],
							},
							title: {
								text: '',
							},
							subtitle: {
								text: '<b>Top Inventory FG Items '+from_date+' To '+to_date+'</b>'
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
						Highcharts.chart('container7', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Top Inventory FG Items '+from_date+' To '+to_date+'</b>'
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
									text: 'Qty (Unit)'
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
								data: returndata.ChartData,
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
		function load_data8(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			var MainGrpID = '2';
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetTopInventoryItem",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,MainGrpID:MainGrpID,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					if(ChartType == "Pie"){
						Highcharts.chart('container8', {
							chart: {
								styledMode: true,  
								height: 600, // Increase chart height
								spacing: [10, 100, 10, 10],
							},
							title: {
								text: '',
							},
							subtitle: {
								text: '<b>Top Inventory RM Items '+from_date+' To '+to_date+'</b>'
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
						Highcharts.chart('container8', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Top Inventory RM Items '+from_date+' To '+to_date+'</b>'
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
									text: 'Qty (Unit)'
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
								data: returndata.ChartData,
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
		function load_data9(from_date,to_date,ChartType,MaxCount,SubGroup,Items,SubGroup2)
		{
			var MainGrpID = '3';
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetTopInventoryItem",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date,to_date:to_date,ChartType:ChartType,MaxCount:MaxCount,MainGrpID:MainGrpID,SubGroup:SubGroup,Items:Items,SubGroup2:SubGroup2},
				beforeSend: function () {
				},
				complete: function () {
				},
				success:function(returndata){
					if(ChartType == "Pie"){
						Highcharts.chart('container9', {
							chart: {
								styledMode: true,  
								height: 600, // Increase chart height
								spacing: [10, 100, 10, 10],
							},
							title: {
								text: '',
							},
							subtitle: {
								text: '<b>Top Inventory PM Items '+from_date+' To '+to_date+'</b>'
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
						Highcharts.chart('container9', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							subtitle: {
								text: '<b>Top Inventory PM Items '+from_date+' To '+to_date+'</b>'
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
									text: 'Qty (Unit)'
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
								data: returndata.ChartData,
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
		
		$('#search_data').click();
		
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