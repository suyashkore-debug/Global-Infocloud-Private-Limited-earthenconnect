<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php  init_head(); ?>
<style>
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
	
	
</style>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 0px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
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
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Target Vs Achievement</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-2 ">
								<?php $month = date('m');?>
								<div class="form-group">
									<label class="control-label" for="month_data">Month</label>
									<select name="month_data" class="selectpicker form-control" id="month_data" data-none-selected-text="<?php echo _l('Select Month'); ?>" data-live-search="true">
										<option></option>
										<option value="04" <?php if($month == '04'){ echo 'Selected';}   ?>>Apr - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="05" <?php if($month == '05'){ echo 'Selected';}   ?>>May - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="06" <?php if($month == '06'){ echo 'Selected';}   ?>>Jun  - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="07" <?php if($month == '07'){ echo 'Selected';}   ?>>Jul - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="08" <?php if($month == '08'){ echo 'Selected';}   ?> >Aug - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="09" <?php if($month == '09'){ echo 'Selected';}   ?> >Sep - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="10" <?php if($month == '10'){ echo 'Selected';}   ?> >Oct - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="11" <?php if($month == '11'){ echo 'Selected';}   ?> >Nov - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="12" <?php if($month == '12'){ echo 'Selected';}   ?> >Dec - <?php  echo $this->session->userdata('finacial_year'); ?></option>
										<option value="01" <?php if($month == '01'){ echo 'Selected';}   ?> >Jan - <?php  echo $this->session->userdata('finacial_year')+1; ?></option>
										<option value="02" <?php if($month == '02'){ echo 'Selected';}   ?> >Feb - <?php  echo $this->session->userdata('finacial_year')+1; ?></option>
										<option value="03" <?php if($month == '03'){ echo 'Selected';}   ?> >Mar - <?php  echo $this->session->userdata('finacial_year')+1; ?></option>
									</select>
								</div> 
							</div>
							
							<div class="col-md-3 leads-filter-column">
								<label for="Subgroup">Subgroup</label>
								<select name="Subgroup" class="selectpicker" id="Subgroup" data-width="100%"  data-live-search="true" data-none-selected-text="<?php echo _l('Select Subgroup Name'); ?>"> 
									<option value=""></option>
									<?php 
										foreach ($Subgroup as $value) { ?>
										<option data-name="<?php echo $value['name'] ?>" data-id="<?php echo html_entity_decode($value['id']); ?>" value="<?php echo html_entity_decode($value['id']); ?>"><?php echo $value['name'] ?></option>
										<?php }
									?>              
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="custom_button">
									&nbsp;&nbsp;<a class="btn btn-info pull-left mleft5 search_data" style="" id="search_data">Show</a>
									&nbsp;
									<!--<a class="btn btn-default buttons-excel buttons-html5" onclick="printPage();" style=""  tabindex="0" aria-controls="table-daily_report"><span>Print</span></a>-->
									&nbsp;<a class="btn btn-default  mleft5 buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									
								</div>
							</div>
							
							<div class="col-md-4">
								<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." class="form-control" title="Type in a name" style="float: right;">
							</div>
							<br>
							
							<div class="clearfix"></div>
							
							
						</div>
						<br/>
						<hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<span id="searchh2" style="display:none;">Loading.....</span>
								<div class="table-daily_report tableFixHead2">
									<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
									</table>   
								</div>
							</div>
						</div>
						<br/>
						<div class="row">
							<!-- First Column-->
							<div class="col-md-12">
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
</div>



<style>
	
</style>
<div id="modal_wrapper"></div>
<?php init_tail(); ?>
<?php 
?>
<script>
	$(document).ready(function(){
		
		function load_data(month_data,fullMonth,Subgroup)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>misc_reports/GetTargetAchievement",
				dataType:"json",
				method:"POST",
				data:{month_data:month_data,Subgroup:Subgroup},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('.table-daily_report tbody').css('display','none');
					$('.table-daily_report tfoot').css('display','none');
					
				},
				complete: function () {
					
					$('.table-daily_report tbody').css('display','');
					$('.table-daily_report tfoot').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					
					$('#table-daily_report').html(data);
					const d = new Date();
					let year = d.getFullYear();
					var msg = "Month: "+fullMonth+"-"+year;
					$(".report_for").text(msg);
					
				}
			});
			
			$.ajax({
					url:"<?php echo admin_url(); ?>misc_reports/GetTargetAchievementGraph",
					dataType:"JSON",
					method:"POST",
					data:{month_data:month_data,Subgroup:Subgroup},
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
								text: '<b>Target VS Achievement - '+fullMonth+'</b>'
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
								name: 'Target',
								data: returndata.Target,
							},
							{
								name: 'Achievement',
								data: returndata.Achievement,
							}
							]
						});
						
					}
				});
		}
		
		$('#search_data').on('click',function(){
			var month_data = $("#month_data").val();
			var Subgroup = $("#Subgroup").val();
			if(month_data == ''){
				alert('Please select Month') 
				return false;
			}
			
			const dt = new Date(month_data);
			const locale = navigator.languages != undefined ? navigator.languages[0] : navigator.language;
			const fullMonth = dt.toLocaleDateString(locale, {month: 'long'});
			// console.log(fullMonth);
			
			load_data(month_data,fullMonth,Subgroup);
			//   }else{
			//           alert("Please select State and Customer Type");
			//         }
			
		});
		
		
		
	});
</script>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script>
	
	
	
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table-daily_report");
		tbody = table.getElementsByTagName("tbody")[0]; // Get the first tbody element
		tr = tbody.getElementsByTagName("tr"); 
		for (i = 0; i < tr.length; i++) 
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
<script>
	
	$("#caexcel").click(function(){
		
        var month_data = $("#month_data").val();
	    var Subgroup = $("#Subgroup").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>misc_reports/export_TaregetAchievement",
            method:"POST",
            data:{Subgroup:Subgroup, month_data:month_data},
            beforeSend: function () {
                $('#searchh3').css('display','block');
                
			},
            complete: function () {
                
                $('#searchh3').css('display','none');
			},
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
			}
		});
	});
	
	function newexportaction(e, dt, button, config) {
		var self = this;
		var oldStart = dt.settings()[0]._iDisplayStart;
		dt.one('preXhr', function (e, s, data) {
			// Just this once, load all data from the server...
			data.start = 0;
			data.length = 2147483647;
			dt.one('preDraw', function (e, settings) {
				// Call the original action function
				if (button[0].className.indexOf('buttons-copy') >= 0) {
					$.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-excel') >= 0) {
					$.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
					$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
					$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-csv') >= 0) {
					$.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
					$.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
					$.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-pdf') >= 0) {
					$.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
					$.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
					$.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-print') >= 0) {
					$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
				}
				dt.one('preXhr', function (e, s, data) {
					// DataTables thinks the first item displayed is index 0, but we're not drawing that.
					// Set the property to what it was before exporting.
					settings._iDisplayStart = oldStart;
					data.start = oldStart;
				});
				// Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
				setTimeout(dt.ajax.reload, 0);
				// Prevent rendering of the full data to the DOM
				return false;
			});
		});
		// Requery the server with the new one-time export settings
		dt.ajax.reload();
	}
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
		heading_data += '<td style="text-align:center;"colspan="3">Target Sale</td>';
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
</script>
<script>
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
</body>
</html>
