<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
	@import url("https://code.highcharts.com/css/highcharts.css");
	
	.highcharts-pie-series .highcharts-point {
    stroke: #ede;
    stroke-width: 2px;
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
	font-size:46px;
	display: block;
    margin: 0 auto;
    color: #fff;
    }
    .labeltxt{
	font-size:16px;
	font-weight:500;
	color: #fff;
    }
    .numstyl{
	text-align: center;
	display: block;
	font-size: 14px;
    }
    .bg1{
	background-image: linear-gradient(to right,#008385 0,#00E7EB 100%);
	background-repeat: repeat-x;
    }
    .bg2{
	background-image: linear-gradient(to right,#FF425C 0,#FFA8B4 100%);
	background-repeat: repeat-x;
    }
    .bg3{
	background-image: linear-gradient(to right,#FF864A 0,#FFCAB0 100%);
	background-repeat: repeat-x;
    }
    .bg4{
	background-image: linear-gradient(to right,#11A578 0,#32EAB2 100%);
	background-repeat: repeat-x;
    }
    .top_stats_wrapper{
	margin-top: 5px;
	border-radius: 10px;
	padding:5px !important;
    }
    .top_stats_wrapper:hover{
	box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.4);
    }
</style>
<div id="wrapper">
	<div class="content" >
		<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
			
			<div class="row" >  
				<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
					<div class="top_stats_wrapper custdesg bg1">
						<?php
							$ItemPercentage = ($ItemCountFG->TotalItem > 0 ? number_format(($ItemCountFG->ActiveItem * 100) / $ItemCountFG->TotalItem,2) : 0);
						?>
						<div class="col-md-3">
							<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
						</div>
						<div class="col-md-9">
							<p class="mtop5 labeltxt"> <?php echo _l('Total SKU FG(All / Active)'); ?><br>
							<span class="numstyl"><?php echo $ItemCountFG->TotalItem; ?> / <?php echo $ItemCountFG->ActiveItem; ?></span></p>
							<div class="clearfix"></div>
							<div class="progress no-margin progress-bar-mini">
								<div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $ItemPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $ItemPercentage; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
					<div class="top_stats_wrapper custdesg bg2">
						<?php
							$ItemPercentage = ($ItemCountRM->TotalItem > 0 ? number_format(($ItemCountRM->ActiveItem * 100) / $ItemCountRM->TotalItem,2) : 0);
						?>
						<div class="col-md-3">
							<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
						</div>
						<div class="col-md-9">
							<p class="mtop5 labeltxt"> <?php echo _l('Total SKU RM(All / Active)'); ?><br>
							<span class="numstyl"><?php echo $ItemCountRM->TotalItem; ?> / <?php echo $ItemCountRM->ActiveItem; ?></span></p>
							<div class="clearfix"></div>
							<div class="progress no-margin progress-bar-mini">
								<div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $ItemPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $ItemPercentage; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
					<div class="top_stats_wrapper custdesg bg3">
						
						<div class="col-md-3">
							<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
						</div>
						<div class="col-md-9">
							<p class="mtop5 labeltxt"> <?php echo _l('Stock Value FG'); ?><br>
							<span class="numstyl"><?php echo $stock_val_FG; ?></span></p>
							<div class="clearfix"></div>
							<div class="progress no-margin progress-bar-mini">
								<div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $ItemPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $ItemPercentage; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-3 ">
					<div class="top_stats_wrapper custdesg bg4">
						
						<div class="col-md-3">
							<p class="mtop5 imgsize"><i class="hidden-sm fa fa-shopping-cart"></i></p>
						</div>
						<div class="col-md-9">
							<p class="mtop5 labeltxt"> <?php echo _l('Stock Value RM'); ?><br>
							<span class="numstyl"><?php echo $stock_val_RM; ?></span></p>
							<div class="clearfix"></div>
							<div class="progress no-margin progress-bar-mini">
								<div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $ItemPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $ItemPercentage; ?>">
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
    .table-daily_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table-daily_report table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	.table-daily_report th     { background: #50607b;color: #fff !important; }
</style>


<?php init_tail(); ?>
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