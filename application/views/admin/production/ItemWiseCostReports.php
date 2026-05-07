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
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Production</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Cost Report</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
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
							<!--<div class="col-md-2">
								<?php
									echo render_date_input('from_date','From Date',$from_date);
								?>
								</div>
								<div class="col-md-2">
								<?php
									echo render_date_input('to_date','To Date',$to_date);
								?>
							</div>-->
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="ItemID">
									<label for="ItemID" class="control-label">Item List</label>
									<select name="ItemID" id="ItemID" class="selectpicker" data-live-search ="true" data-width="100%" data-none-selected-text="None selected">
										<option value="">None selected</option>
										<?php
											foreach($PrdItemList as $key=>$val){
											?>
											<option value="<?php echo $val["recipeID"];?>"><?php echo $val["description"];?></option>
											<?php
											}   
										?> 
									</select>
								</div>
							</div>
							
							
							<div class="col-md-3">
								<button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="row" id="filter_row" style="display:none;">
							<hr class="hr-panel-heading" />
							<div class="col-md-6">
								<div class="custom_button">
									<!--&nbsp;<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>-->
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
								</div>
							</div>
							<div class="col-md-4">
								<input type="text" id="myInput1" onkeyup="myFunction2()" class="form-control" placeholder="Search for names.." title="Type in a name" style="float: right;">
							</div>
							<div class="col-md-2">
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-10" id="RMDataDiv">
								
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
								<h4 style="text-align:center;">Raw Material Composition By Value</h4>
								<hr class="hr-panel-heading-dashboard">
								<div class="relative" >
									<canvas class="chart" width="400px;" id="leads_status_stats"></canvas>
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
		
		$('#search_data').on('click',function(){
			var month = $("#month_data").val();
			var ItemID = $("#ItemID").val();
			if(ItemID){
				load_data(month,ItemID);
				}else{
				alert("Please Select Item from Item List");
			}
		});
		
		function load_data(month,ItemID)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>production/LoadItemWiseCostReport",
				dataType:"JSON",
				method:"POST",
				data:{month:month,ItemID:ItemID},
				beforeSend: function () {
					$('#searchh2').css('display','block');
					$('#RMDataDiv').css('display','none');
					$('#filter_row').css('display','none');
				},
				complete: function () {
					$('#RMDataDiv').css('display','');
					$('#filter_row').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					var leads_chart = $('#leads_status_stats');
					$('#RMDataDiv').html(data.html1);
					var from_date = $("#from_date").val();
					var to_date = $("#to_date").val();
					var status_list = $("#status_list").val();
					var html_filter_name =    'Filtes Date from: '+from_date+',Date to: '+to_date+', Order Status:'+status_list;
					$('.report_for').text(html_filter_name);
					new Chart(leads_chart, {
						type: 'doughnut',
						data: data.chart,
						options:{
							maintainAspectRatio:false,
							onClick:function(evt){
								onChartClickRedirect(evt,this);
							}
						}
					});
					
					
				}
			});
		}
		
		
		
	});
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table-daily_report");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			td4 = tr[i].getElementsByTagName("td")[4];
			td5 = tr[i].getElementsByTagName("td")[5];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						} else if(td2){
						txtValue = td2.textContent || td2.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
							}else if(td3){
							txtValue = td3.textContent || td3.innerText;
							if (txtValue.toUpperCase().indexOf(filter) > -1) {
								tr[i].style.display = "";
								}else if(td4){
								txtValue = td4.textContent || td4.innerText;
								if (txtValue.toUpperCase().indexOf(filter) > -1) {
									tr[i].style.display = "";
									
									}else if(td5){
									txtValue = td5.textContent || td5.innerText;
									if (txtValue.toUpperCase().indexOf(filter) > -1) {
										tr[i].style.display = "";
										
										}else{
										tr[i].style.display = "none";
									} 
								}
							}
						}
					}     
				}
			}
		}
	}
</script>
<script>
	
	$("#caexcel").click(function(){
		
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var status_list = $("#status_list").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>production/export_productionReport",
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
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} th {background-color:#50607b;color:#fff;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">ItemWise Production Cost </td>';
		heading_data += '</tr>';
		
		heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData;
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
</body>
</html>
