<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
					    <nav aria-label="breadcrumb">
    						<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
    							<li class="breadcrumb-item" ><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
    							<li class="breadcrumb-item active text-capitalize"><b>Sale Report</b></li>
    							<li class="breadcrumb-item active" aria-current="page"><b>Item Group Wise Sale Report</b></li>
							</ol>
						</nav>
    					<hr class="hr_style">
					</div>
					
					<div class="row">
						<div class="col-md-6" >
							<div class="panel-body" style="max-height:400px;min-height:340px;">
								<?php
									$fy = $this->session->userdata('finacial_year');
									$from_date = "01/".date('m')."/".date('Y');
									$to_date = date('d/m/Y');
								?>
								<div class="col-md-4">
									<?php
										echo render_date_input('from_date','From',$from_date);
									?>
								</div>
								<div class="col-md-4">
									<?php
										echo render_date_input('to_date','To',$to_date);
									?>
								</div>
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="ItemGroup" class="control-label">Item Group</label>
										<select name="ItemGroup" id="ItemGroup" multiple data-actions-box="1" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<?php foreach($ItemGroupList as $value){ ?>
												<option value="<?php echo $value['id']; ?>" ><?php echo $value['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="DistributorType" class="control-label">Distributor Type</label>
										<select name="DistributorType" id="DistributorType" multiple data-actions-box="1" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<?php foreach($DistributorTypeList as $value){ ?>
												<option value="<?php echo $value['id']; ?>" ><?php echo $value['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="state" class="control-label">State</label>
										<select name="state" id="state"  class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<option value="">All</option>
											<?php foreach($StateList as $value){ ?>
												<option value="<?php echo $value['short_name']; ?>" ><?php echo $value['state_name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="city" class="control-label">City</label>
										<select name="city" id="city" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<option  value=''>None Selected</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="PartyName" class="control-label">Party Name</label>
										<select name="PartyName" id="PartyName" multiple data-actions-box="1" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<?php
												foreach($CustomerList as $value)
												{
												?>
												<option value="<?= $value['AccountID']?>"><?= $value['company']?></option>
												<?php
												}
											?>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="SalesPerson" class="control-label">Sales Person</label>
										<select name="SalesPerson" id="SalesPerson" multiple data-actions-box="1" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<?php
												foreach($PartySalePerson as $value)
												{
												?>
												<option value="<?= $value['staffid']?>"><?= $value['firstname']." ".$value['lastname']?></option>
												<?php
												}
											?>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="Route" class="control-label">Route</label>
										<select name="Route" id="Route" multiple data-actions-box="1" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<?php
												foreach($RouteList as $value)
												{
												?>
												<option value="<?= $value['RouteID']?>"><?= $value['name']?></option>
												<?php
												}
											?>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="ChartType" class="control-label">Chart Type</label>
										<select name="ChartType" id="ChartType" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<option  value='Pie'>Pie</option>
											<option  value='Column'>Column</option>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group select-placeholder">
										<label for="ReportIn" class="control-label">Report In</label>
										<select name="ReportIn" id="ReportIn" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Non selected">
											<option  value='2'>Sale Amount</option>
											<option  value='1'>Sale Amount Percentage</option>
											<option  value='3'>Sale Quantity</option>
											<option  value='4'>Sale Quantity Percentage</option>
										</select>
									</div>
								</div>
								
								
								<div class="clearfix"></div>
								<div class="col-md-2">
									<button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 9px;" id="search_data">Show</button>
								</div>
								<!--<div class="col-md-10">
									<div class="custom_button" style="margin-top: 9px;">
									&nbsp;
									<?php if (has_permission_new('ItemGroupChartReport', '', 'excel')) {
									?>
									<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									<?php } ?>
									
									<?php if (has_permission_new('ItemGroupChartReport', '', 'print')) {
									?>
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									<?php } ?>
									</div>
								</div>-->
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel-body" style="max-height:400px;min-height:340px;">
								<span id="FilterData"></span>
								<canvas id="myPieChart" height="150"></canvas>
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="panel-body" style="min-height:200px;">
								<div class="col-md-10">
									<div class="custom_button" style="margin-top: 9px;">
										&nbsp;
										<?php if (has_permission_new('ItemGroupChartReport', '', 'excel')) {
										?>
										<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
										<?php } ?>
										
										<?php if (has_permission_new('ItemGroupChartReport', '', 'print')) {
										?>
										<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
										<?php } ?>
									</div>
								</div>
								<div class="fixTableHead GroupWiseItemSale_report">
									
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
<script>
	$(document).ready(function() { 
		// $('.selectpicker').selectpicker();
		$('#state').on('change', function() {
			var StateID = $(this).val();
			//alert(roleid);
			var url = "<?php echo base_url(); ?>admin/clients/GetCity";
			jQuery.ajax({
				type: 'POST',
				url:url,
				data: {StateID: StateID},
				dataType:'json',
				success: function(data) {
					$("#city").find('option').remove();
					$("#city").selectpicker("refresh");
					$("#city").append(new Option('None selected', ''));
					for (var i = 0; i < data.length; i++) {
						$("#city").append(new Option(data[i].city_name, data[i].id));
					}
					$('.selectpicker').selectpicker('refresh');
				}
			});
		});
	});
</script>
<script>
	
	function load_data(from_date,to_date,ItemGroup,DistributorType,state,city,PartyName,SalesPerson,Route,ChartType,ReportIn) {
		filterFrom = 'Date From <b>'+from_date+'</b> To <b>'+to_date+'</b>';
		if(ItemGroup != ''){
			let selectedGroup = $("#ItemGroup option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - Item Group : <b>'+selectedGroup+'</b>';
		}
		if(DistributorType != ''){
			let selectedDist = $("#DistributorType option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - Distributor Type : <b>'+selectedDist+'</b>';
		}
		if(state != ''){
			let selectedstate = $("#state option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - State : <b>'+selectedstate+'</b>';
		}
		if(city != ''){
			let selectedcity = $("#city option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - City : <b>'+selectedcity+'</b>';
		}
		if(PartyName != ''){
			
			let selectedParty = $("#PartyName option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - Party : <b>'+selectedParty+'</b>';
		}
		if(SalesPerson != ''){
			let selectedSaleP = $("#SalesPerson option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get();
			filterFrom += ' - Sales Person : <b>'+selectedSaleP+'</b>';
		}
		if(Route != ''){
			let selectedRoute = $("#Route option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get();
			filterFrom += ' - Route : <b>'+selectedRoute+'</b>';
		}
		if(ReportIn != ''){
			let selectedReportIn = $("#ReportIn option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get();
			filterFrom += ' - ReportIn : <b>'+selectedReportIn+'</b>';
		}
		$.ajax({
			url: "<?php echo admin_url(); ?>Sale_reports/GetItemGroupWiseChartReport",
			dataType: "JSON",
			method: "POST",
			data: {
				from_date: from_date,
				to_date: to_date,
				ItemGroup: ItemGroup,
				DistributorType: DistributorType,
				state: state,
				city: city,PartyName:PartyName,SalesPerson:SalesPerson,Route:Route,ChartType:ChartType,ReportIn:ReportIn,FilterVal:filterFrom
			},
			beforeSend: function() {
				$('#searchh2').css('display', 'block');
				$('#TopData tbody').css('display', 'none');
			},
			complete: function() {
				$('#TopData tbody').css('display', '');
				$('#searchh2').css('display', 'none');
			},
			success: function(response) {
				// Update the pie chart with the new data
				updatePieChart(response.chartData);
				$('.GroupWiseItemSale_report').html(response.HTML);
				
				
				$('#FilterData').html(filterFrom);
			}
		});
	}
	
	
	$('#search_data').on('click',function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var ItemGroup = $("#ItemGroup").val();
		var DistributorType = $("#DistributorType").val();
		var state = $("#state").val();
		var city = $("#city").val();
		var PartyName = $("#PartyName").val();
		var SalesPerson = $("#SalesPerson").val();
		var Route = $("#Route").val();
		var ChartType = $("#ChartType").val();
		var ReportIn = $("#ReportIn").val();
		
		
		load_data(from_date,to_date,ItemGroup,DistributorType,state,city,PartyName,SalesPerson,Route,ChartType,ReportIn);
	});
	
	$("#caexcel").click(function(){
		
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var ItemGroup = $("#ItemGroup").val();
		var DistributorType = $("#DistributorType").val();
		var state = $("#state").val();
		var city = $("#city").val();
		var PartyName = $("#PartyName").val();
		var SalesPerson = $("#SalesPerson").val();
		var Route = $("#Route").val();
		var ChartType = $("#ChartType").val();
		var ReportIn = $("#ReportIn").val();
		filterFrom = 'Date From '+from_date+' To '+to_date;
		if(ItemGroup != ''){
			let selectedGroup = $("#ItemGroup option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - Item Group : '+selectedGroup;
		}
		if(DistributorType != ''){
			let selectedDist = $("#DistributorType option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - Distributor Type : '+selectedDist;
		}
		if(state != ''){
			let selectedstate = $("#state option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - State : '+selectedstate;
		}
		if(city != ''){
			let selectedcity = $("#city option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - City : '+selectedcity;
		}
		if(PartyName != ''){
			
			let selectedParty = $("#PartyName option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get(); 
			filterFrom += ' - Party : '+selectedParty;
		}
		if(SalesPerson != ''){
			let selectedSaleP = $("#SalesPerson option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get();
			filterFrom += ' - Sales Person : '+selectedSaleP;
		}
		if(Route != ''){
			let selectedRoute = $("#Route option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get();
			filterFrom += ' - Route : '+selectedRoute;
		}
		if(ReportIn != ''){
			let selectedReportIn = $("#ReportIn option:selected").map(function () {
				return $(this).text(); // Get the text of each selected option
			}).get();
			filterFrom += ' - ReportIn :'+selectedReportIn;
		}
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>sale_reports/ExportItemGroupWiseChartReport",
            method:"POST",
            data: {
				from_date: from_date,
				to_date: to_date,
				ItemGroup: ItemGroup,
				DistributorType: DistributorType,
				state: state,
				city: city,PartyName:PartyName,SalesPerson:SalesPerson,Route:Route,ChartType:ChartType,ReportIn:ReportIn,FilterVal:filterFrom
			},
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
	
</script>


<script>
	function printPage(){
        
		var FilterData = $("#FilterData").html();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->FIRMNAME; ?></td></tr><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="9">Item Group Wise Sales Report : '+FilterData+'</td>';
		heading_data += '</tr>';
		heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
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

<script>
	function updatePieChart(chartData) {
		const labels = chartData.map(item => item.label);
		const Values = chartData.map(item => item.Value);
		
		const ctx = document.getElementById('myPieChart').getContext('2d');
		
		// Check if the chart instance already exists, and if so, destroy it.
		if (window.myPieChart && typeof window.myPieChart.destroy === 'function') {
			window.myPieChart.destroy();
		}
		
		// Create a new pie chart instance.
		window.myPieChart = new Chart(ctx, {
			type: 'pie',
			data: {
				labels: labels,
				datasets: [{
					data: Values,
					backgroundColor: [
                    '#0A11FA','#FC2D42','#DA23E8','#19F03E','#34A4AF','#D4AC14','#ED1556','#1A252F','#3B82F6'
                    ],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'top',
					}
				}
			}
		});
	}
</script>

<style>
    .GroupWiseItemSale_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
    .GroupWiseItemSale_report thead th { position: sticky; top: 0; z-index: 1; }
    .GroupWiseItemSale_report tbody th { position: sticky; left: 0; }
    
    /* Just common table stuff. Really. */
    .GroupWiseItemSale_report table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    .GroupWiseItemSale_report th     { background: #50607b;color: #fff !important; }
	
    
    #table_accountlist tr:hover {
    background-color: #ccc;
    }
    
    #table_accountlist td:hover {
	cursor: pointer;
    }
</style>

</body>
</html>
