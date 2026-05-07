<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
	.table-daily_report tr:hover {
    background-color: #ccc;
	}
	
	.table-daily_report td:hover {
    cursor: pointer;
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
            					<li class="breadcrumb-item active" aria-current="page"><b>Damage Qty Report</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						
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
									$from_date = "01/".date('m')."/".date('Y');
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
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="status_list">
									<label for="status_list" class="control-label"><?php echo _l('Order Status'); ?></label>
									<select name="status_list" id="status_list" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
										<option value="Completed">Completed</option>
										<option value="In-Progress">In-Progress</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
								
							</div>
							
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="col-md-8">
							<div class="custom_button">
								&nbsp;<!--<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>-->
								<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
								<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export</span></a>
							</div>
						</div>
						<div class="col-md-4">
							<input type="text" id="myInput1" onkeyup="myFunction2()" class="form-control" placeholder="Search.." title="Type in a name" style="float: right;">
							
						</div>
						
						
						<?php
							//print_r($company_detail);
						?>
						<div class="table-daily_report tableFixHead2">
							
							<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
								
								<thead>
									
									<tr style="display:none;">
										<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
									</tr>
									<tr>
										<th class="sortable" style="text-align:left;">Sr No.</th>
										<th class="sortable" style="text-align:left;">RecipeID</th>
										<th class="sortable" style="text-align:left;">Item Name</th>
										<th class="sortable" style="text-align:left;">No Of Batch</th>
										<th class="sortable" style="text-align:left;">Standard Packing Qty</th>
										<th style="text-align:left;">Actual Packing Qty</th>
										<th class="sortable" style="text-align:left;">Damage Qty</th>
										<th class="sortable" style="text-align:left;">Packing Weight (Kg)</th>
										<th class="sortable" style="text-align:left;">Status</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
						</div>
						<span id="searchh2" style="display:none;">Loading.....</span>
						
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
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
<script>
	$(document).ready(function(){
		
		function load_data(from_date,to_date,status_list)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>production/load_data_for_damage_production_packing",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date,status_list:status_list},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('#table-daily_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('#table-daily_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					$('#table-daily_report tbody').html(data);
					var from_date = $("#from_date").val();
					var to_date = $("#to_date").val();
					var status_list = $("#status_list").val();
					var html_filter_name =    'Filtes Date from: '+from_date+',Date to: '+to_date+', Order Status:'+status_list;
					$('.report_for').text(html_filter_name);
				}
			});
		}
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var status_list = $("#status_list").val();
			
			load_data(from_date,to_date,status_list);
			
			load_data_chart(from_date,to_date,status_list);
		});
		
	});
	
	function load_data_chart(from_date,to_date,status_list)
	{
		$.ajax({
			url:"<?php echo admin_url(); ?>production/GetMonthlyDamageReport",
			dataType:"JSON",
			method:"POST",
			data:{from_date:from_date, to_date:to_date,status_list:status_list},
			beforeSend: function () {
				
				$('#searchh2').css('display','block');
				$('.table-daily_report tbody').css('display','none');
				
			},
			complete: function () {
				
				$('.table-daily_report tbody').css('display','');
				$('#searchh2').css('display','none');
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
						text: '<b>Month Wise Total Damage</b>'
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
							text: 'Total'
						}
					},
					legend: {
						enabled: true
					},
					tooltip: {
						shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}</b><br/>'
					},
					series: [
					{
						name: 'Total Damage',
						data: returndata.TotalDamage,
						color: '#119EFA',
						dataLabels: {
							enabled: true,
							rotation: -90,
							color: '#FFFFFF',
							inside: true,
							verticalAlign: 'top',
							format: '{point.y:.1f}',
							y: 10
						}
					}
					]
				});
				
				
			}
		});
	}
	function myFunction2() {
		var input, filter, table, tbody, tr, i, rowText, searchWords;
		
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase().trim(); // Normalize search input
		
		// Split input into separate words (e.g., "MANOJ KUMAR" -> ["MANOJ", "KUMAR"])
		searchWords = filter.split(/\s+/);
		
		table = document.getElementById("table-daily_report");
		tbody = table.getElementsByTagName("tbody")[0];
		
		tr = tbody.getElementsByTagName("tr");
		
		for (i = 0; i < tr.length; i++) {
			rowText = tr[i].textContent || tr[i].innerText; // Get entire row text
			rowText = rowText.toUpperCase().trim(); // Normalize row text
			
			// Check if ALL search words exist somewhere in the row
			let allWordsMatch = searchWords.every(word => rowText.includes(word));
			
			// Show row if all words match, otherwise hide it
			tr[i].style.display = allWordsMatch ? "" : "none";
		}
	}	
	
	$("#caexcel").click(function(){
		
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var status_list = $("#status_list").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>production/export_damage_production_packing",
            method:"POST",
            data:{from_date:from_date, to_date:to_date,status_list:status_list},
            
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
			}
		});
	});
</script>
<script type="text/javascript">
	function printPage(){
		var html_filter_name =    $('.report_for').html();
		var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">Damage Production List </td>';
		heading_data += '</tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name+'</td>';
		heading_data += '</tr>';
		
		heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
	
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
