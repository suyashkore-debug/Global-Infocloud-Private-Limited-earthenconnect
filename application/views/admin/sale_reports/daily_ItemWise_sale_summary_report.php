<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Daily ItemWise Sales Report</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="_buttons">
							<div class="row">  
								<div class="col-md-3">
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
											$from_date = date('d/m/Y');
											$to_date = date('d/m/Y');
										}
									?>
									<?php 
										$current_date = date('d/m/Y');
										echo render_date_input('from_date','Date',$from_date);          
									?>
								</div>
								
								<div class="col-md-3" style="margin-top:10px;">
									<br>
									
									<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
								</div>
							</div>
							
						</div>
						<div class="clearfix"></div>
						
						<div class="row">
							
							<div class="col-md-6">
								<div class="custom_button">
									<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
								</div>
							</div>
							<div class="col-md-6">
								<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
								
							</div>
							<div class="col-md-12">
								<span id="searchh3" style="display:none;">please wait exporting data....</span>
							</div>
						</div>
						
						<?php
							//print_r($company_detail);
						?>
						<div class="table-daily_report">
							
							<table class="tree table table-striped table-bordered table-daily_report" id="table-daily_report" width="100%">
								
								<thead>
									<tr style="display:none;">
										<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
									</tr>
									<tr>
										<th class="sortable" style="text-align:left;">SR.No</th>
										<th class="sortable" style="text-align:left;">Item Name</th>
										<th class="sortable" style="text-align:left;">Pkt Qty</th>
										<th class="sortable" style="text-align:left;">CS/CR Qty</th>
										<th class="sortable" style="text-align:left;">Weight</th>
										<th class="sortable" style="text-align:left;">Amount</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
						</div>
						<span id="searchh2" style="display:none;">Loading.....</span>
						
						
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
<!--new update -->
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script>
    function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table-daily_report");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[3];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else {
					tr[i].style.display = "none";
				}
			}       
		}
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		function load_data(from_date)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>sale_reports/load_data_daily_ItemWise_sale_report",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('.table-daily_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('.table-daily_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					var html = '';
					var totalsale = 0;					
					var totalunitsale = 0;					
					var totalweight = 0;					
					var totalAmount = 0;					
					var i = 1;
					
					for (var count = 0; count < data.length; count++) {
						if(parseInt(data[count].Total_unit) >0)
						{
							html += '<tr>';
							html += '<td class="table_data" data-row_id="'+i+'" data-column_name="orderid" style="text-align:center;">'+i+'</td>';
							html += '<td class="table_data" data-row_id="'+data[count].description+'" data-column_name="date" style="text-align:left;">'+data[count].description+'</td>';
							html += '<td class="table_data" data-row_id="" data-column_name="date" style="text-align:right;">'+parseInt(data[count].Total_unit).toFixed(2)+'</td>';
							html += '<td class="table_data" data-row_id="" data-column_name="date" style="text-align:right;">'+parseFloat(data[count].Total_Sale).toFixed(2)+'</td>';
							html += '<td class="table_data" data-row_id="" data-column_name="date" style="text-align:right;">'+parseFloat(data[count].Total_weight).toFixed(2)+'</td>';
							html += '<td class="table_data" data-row_id="" data-column_name="date" style="text-align:right;">'+parseFloat(data[count].Amount).toFixed(2)+'</td>';
							html += '</tr>';
							
							// Update totals
							
							totalsale += parseFloat(data[count].Total_Sale);
							totalunitsale += parseInt(data[count].Total_unit);
							totalweight += parseFloat(data[count].Total_weight);
							totalAmount += parseFloat(data[count].Amount);
							i++;
						}
					}
					
					// Add the totals row after the loop
					html += '<tr>';
					html += '<td colspan="2" style="text-align:right;">Total</td>';
					html += '<td style="text-align:right;">'+totalunitsale.toFixed(2)+'</td>';
					html += '<td style="text-align:right;">'+totalsale.toFixed(2)+'</td>';
					html += '<td colspan="" style="text-align:right;">'+totalweight.toFixed(2)+'</td>';
					html += '<td colspan="" style="text-align:right;">'+totalAmount.toFixed(2)+'</td>';
					html += '</tr>';
					
					
					
					$('tbody').html(html);
				}
			});
		}
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var msg = "Sales Report "+from_date;
			$(".report_for").text(msg);
			load_data(from_date);
			
		});
		
		
		
	});
	
	$("#caexcel").click(function(){
		
		var from_date = $("#from_date").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>sale_reports/export_daily_ItemWise_sale_summary_report",
            method:"POST",
            data:{from_date:from_date},
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
	
	
</script>
<script type="text/javascript">
	function printPage(){
        
		var from_date = $("#from_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->FIRMNAME; ?></td></tr><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="9">Sales Report : '+from_date+'</td>';
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