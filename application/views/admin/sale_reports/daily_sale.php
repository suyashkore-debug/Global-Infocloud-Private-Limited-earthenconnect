<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="panel_s">
					<div class="panel-body">
						
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Daily Sales Report12</b></li>
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
										$last_yr_date = $last_date_yr->format('Y-m-d');
                				        $curr_yr_date = $curr_date_new->format('Y-m-d');
                				        $from_date = '01/04/20'.$fy;
										if($last_yr_date < $curr_yr_date){
                							$to_date = '31/03/20'.$fy_new;
                						}else{
                							$to_date = date('d/m/Y');
                						}
									?>
									<?php 
										$current_date = date('d/m/Y');
										echo render_date_input('from_date','From Date',$from_date);          
									?>
								</div>
								<div class="col-md-3">
									<?php 
										echo render_date_input('to_date','To Date',$to_date);          
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
							
							<div class="col-md-7">
								<div class="custom_button">
									<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
								</div>
							</div>
							<div class="col-md-5">
								<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
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
										<th style="text-align:left;">Challan ID</th>
										<th style="text-align:left;">Order No</th>
										<th style="text-align:left;">Bill No</th>
										<th style="text-align:left;">Bill Date</th>
										<th style="text-align:left;">Account ID</th>
										<th style="text-align:left;">Account Name</th>
										<th style="text-align:left;">Bill Amount</th>
										<th style="text-align:left;">Return</th>
										<th style="text-align:left;">Vehicle Return Payment</th>
										<th style="text-align:left;">Fresh Return</th>
										<th style="text-align:left;">Other Payment</th>
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
<script>
    
    function myFunction2() 
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.querySelector(".table-daily_report");
        tr = table.getElementsByTagName("tr");
        for (i = 2; i < tr.length; i++) {
            var tdArray = tr[i].getElementsByTagName("td");
            var rowContainsSearchTerm = false;
            for (var j = 0; j <=5; j++) {
                td = tdArray[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if(txtValue != "" && txtValue != null){
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            rowContainsSearchTerm = true;
                            break;
                        }
                    }
                }
            }
            if (rowContainsSearchTerm) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		function load_data(from_date,to_date)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>sale_reports/load_data",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date},
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
					
					var i = 0;
					var total = 0;
					var rowspan = 0;
					var grand_total = 0;
					for(var count = 0; count < data.length; count++)
					{
						var bill_amt = parseFloat(data[count].BillAmt);
						var RndAmt = parseFloat(bill_amt);
						var grand_total = grand_total + Math.round(RndAmt);
						html += '<tr style="cursor: pointer;">';
						
						var j = j + 1;
						//onclick="window.open(\'<?= admin_url()?>challan/UpdateChallan/'+data[count].ChallanID+'\', \'_blank\')"
						html += '<td  class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="orderid" style="text-align:center;"><a href="'+'<?= admin_url()?>challan/UpdateChallan/'+data[count].ChallanID+'" target="_blank">'+data[count].ChallanID+'</a></td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="date" style="text-align:center;"><a href="'+'<?= admin_url()?>order/order/'+data[count].OrderID+'" target="_blank">'+data[count].OrderID+'</a></td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="date" style="text-align:center;">'+data[count].SalesID+'</td>';
						var date = data[count].Transdate.substring(0, 10)
						var date_new = date.split("-").reverse().join("/");
						
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="accountid" style="text-align:center;">'+date_new+'</td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="accountname" >'+data[count].AccountID+'</td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="accountname" >'+data[count].AccountName+'</td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="station" style="text-align:right;">'+ Math.round(RndAmt).toFixed(2) +'</td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="closebalamt" style="text-align:center;">N</td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="orderamt"></td>';
						
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="cancel"></td>';
						html += '<td class="table_data" data-row_id="'+data[count].OrderID+'" data-column_name="remark" ></td>';
						//html += '<td><button type="button" name="delete_btn" id="'+data[count].OrderID+'" class="btn btn-xs btn-danger btn_delete"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
						html += '</tr>';
						var challan_id = data[count].ChallanID
						
						if(data[count].ChallanID == challan_id){
							var i = i + 1;
						}
						
						if(data[count].Count_number>= 1){
							
							if(data[count].Count_number == i){
								
								html += '<tr>';
								html += '<td></td>';
								html += '<td></td>';
								html += '<td></td>';
								html += '<td></td>';
								html += '<td></td>';
								html += '<td align="right">Total</td>';
								html += '<td style="text-align:right;">'+Math.round(data[count].Total_number).toFixed(2)+'</td>';
								html += '<td></td>';
								html += '<td></td>';
								html += '<td></td>';
								html += '<td></td>';
								html += '</tr>';
								var i = 0;
							}
						}else {
							var i = 0;
						}
					}
					
					html += '<tr>';
					html += '<td></td>';
					html += '<td></td>';
					html += '<td></td>';
					html += '<td></td>';
					html += '<td></td>';
					html += '<td align="right">Total '+data.length+' rows Grand Total</td>';
					html += '<td style="text-align:right;">'+ parseFloat(grand_total).toFixed(2)+'</td>';
					html += '<td></td>';
					html += '<td></td>';
					html += '<td></td>';
					html += '<td></td>';
					html += '</tr>';
					
					$('tbody').html(html);
				}
			});
		}
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var msg = "Sales Report "+from_date +" To " + to_date;
			$(".report_for").text(msg);
			load_data(from_date,to_date);
			
		});
		
		
		
	});
	
	$("#caexcel").click(function(){
		
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>sale_reports/export_daily_sale",
            method:"POST",
            data:{from_date:from_date, to_date:to_date},
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
		
	});
</script> 