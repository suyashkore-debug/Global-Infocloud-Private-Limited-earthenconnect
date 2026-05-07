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
            					<li class="breadcrumb-item active text-capitalize"><b>Misc. Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Party Wise Crate Ledger</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="_buttons">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="act_name">Account ID</label>
										<select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
											<option value=""></option>
											<?php foreach($vendors as $s) { ?>
												<option value="<?php echo html_entity_decode($s['AccountID']); ?>" ><?php echo html_entity_decode($s['company'])." - ".html_entity_decode($s['AccountID']); ?></option>
											<?php } ?>
										</select> 
                                        
									</div>
								</div>
								<div class="col-md-3">
									<br>
									<div class="form-group">
										<input type="text" name="account_full_name" id="account_full_name" class="form-control" value="" readonly>
										<!--<input type="hidden" name="account_source" id="account_source" class="form-control" value="">-->
									</div>
								</div>
								<div class="col-md-3">
									
									<div class="form-group">
										<label for="estimate"></label>
										<input type="text" readonly="" class="form-control" name="address" id="address" aria-invalid="false">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="estimate"></label>
										<input type="text" readonly="" class="form-control" name="city" id="city" aria-invalid="false">
									</div>
								</div>
							</div>
							
							<div class="row">
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
									
									<div class="form-group" app-field-wrapper="from_date">
										<label for="from_date" class="control-label from_date_text">From Date</label>
										<div class="input-group date">
											<input type="text" id="from_date" name="from_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
											<div class="input-group-addon">
												<i class="fa fa-calendar calendar-icon"></i>
											</div>
										</div>
									</div>
									
									<?php// echo render_date_input('from_date','FROM',$from_date);  ?>
								</div>
								
								<div class="col-md-3">
									
									<div class="form-group" app-field-wrapper="to_date">
										<label for="to_date" class="control-label to_date_text">To Date</label>
										<div class="input-group date">
											<input type="text" id="to_date" name="to_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
											<div class="input-group-addon">
												<i class="fa fa-calendar calendar-icon"></i>
											</div>
										</div>
									</div>
									<?php //echo render_date_input('to_date','TO',$to_date); ?>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="estimate">Driver </label>
										<select class="form-control selectpicker"  data-live-search="true" name="driver" id="driver" aria-invalid="false">
											<option value="" >None Selected</option>
											<?php
												foreach ($DriverList as $key => $value) {
												?>
												<option value="<?php echo $value["AccountID"]?>" ><?php echo $value["firstname"]." ".$value["lastname"];?></option>
												<?php
												}
											?>
										</select>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="estimate"></label>
										<input type="text" readonly="" class="form-control" name="state_f" id="state_f" aria-invalid="false">
									</div>
								</div>
							</div> 
						</div>
						
						
						<div class="row"> 
							
							<div class="clearfix"></div>   
							<div class="col-md-9">
								<div class="custom_button">
									<button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;">Show</button>
								</div>
								
								<div class="custom_button">
									<button class="btn btn-default pull-left mleft5 " href="javascript:void(0);"    onclick="printPage();" style="font-size:12px;">Print</button>
								</div>
								<div class="custom_button">
									&nbsp;&nbsp;<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="production_report" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
									<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Print</span></a>-->
								</div>
							</div>    
							<div class="col-md-3">
								<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search " title="Type in a name" style="float: right;">
							</div>    
						</div> 
						
						<div class="clearfix"></div>
						<br>
						<div class="col-md-12">
							<span id="searchh" style="display:none;">Please wait fetching data</span>
							<span id="searchh11" style="display:none;">Please wait exporting data..</span>
							<div class="CrateLedger load_data">
								
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
    .CrateLedger { overflow: auto;max-height: 60vh;position:relative;top: 0px; }
	.CrateLedger thead th { position: sticky; top: 0; z-index: 1; }
	.CrateLedger tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.CrateLedger table  { border-collapse: collapse; }
	.CrateLedger th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.CrateLedger th     { background: #50607b;color: #fff !important; }
	
	
</style>
<?php init_tail(); ?>
<!--new update -->
<script>
    
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("CrateLedger");
		tr = table.getElementsByTagName("tr");
		for (i = 4; i < tr.length; i++) {
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
					}else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						}else if(td2){
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
										} else {
										tr[i].style.display = "none";
									}
								}    }} }}}  
		}
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		$( "#from_date" ).change(function() {
			$('.load_data').html('');
		});
		$( "#to_date" ).change(function() {
			$('.load_data').html('');
		});
		$( "#vendor" ).change(function() {
			$('.load_data').html('');
			if(this.value == ""){
				$("#account_full_name").val('');
				$("#address").val('');
				$("#city").val('');
				$("#state_f").val('');
				$('.from_date_text').html('From Date');
				$('.to_date_text').html('To Date');
			}else{
				$('.from_date_text').html('From Date');
				$('.to_date_text').html('To Date');
			}
			if(this.value != 0){
				$.post(admin_url + 'Misc_reports/get_vendor_data/'+this.value).done(function(response){
					response = JSON.parse(response);
					$("#account_full_name").val(response.vendor.company);
					$("#address").val(response.vendor.address);
					$("#city").val(response.vendor.city_name);
					$("#state_f").val(response.vendor.state_name);
					
				});
			}
		});
		
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var accountId = $("#vendor").val();
			var account_full_name = $("#account_full_name").val();
			var driver = $("#driver").val();
			var driverName = $("#driver :selected").text();
			if((accountId == '' || accountId == null) && driver == ""){
				alert('Please Select Party OR Driver');
			}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>misc_reports/GetPartyWiseDriverWiseCrateLedger",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{from_date:from_date,account_full_name:account_full_name, to_date:to_date,accountId:accountId,driver:driver,driverName:driverName},
					beforeSend: function () {
						
						$('#searchh').css('display','block');
						$('.load_data').css('display','none');
						
					},
					complete: function () {
						$('.load_data').css('display','');
						$('#searchh').css('display','none');
					},
					success:function(data){
						$('.load_data').html(data);
					}
				});
			}
		});
		
	});
	
	$("#caexcel").click(function(){
		var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var accountId = $("#vendor").val();
	    var account_full_name = $("#account_full_name").val();
	    var driver = $("#driver").val();
	    var driverName = $("#driver :selected").text();
		if((accountId == '' || accountId == null) && driver == ""){
			alert('Please Select Party OR Driver');
		}else{
			$.ajax({
				url:"<?php echo admin_url(); ?>misc_reports/ExportPartyWiseDriverWiseCrateLedger",
				method:"POST",
				data:{from_date:from_date,account_full_name:account_full_name, to_date:to_date,accountId:accountId,driver:driver,driverName:driverName},
				beforeSend: function () {
					$('#searchh11').css('display','block');  
				},
				complete: function () {
					$('#searchh11').css('display','none');  
				},
				success:function(data){
					response = JSON.parse(data);
					window.location.href = response.site_url+response.filename;
				}
			});
		}
	});
	
	
	function printPage()
	{
		var html_filter_name =    $('.report_for').html();
		var html_filter_name2 =    $('.report_for2').html();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
    	var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
    	heading_data += '<tr>';
    	heading_data += '<td style="text-align:center;"colspan="3">Crate Legder</td>';
    	heading_data += '</tr>';
    	heading_data += '<tr>';
    	heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name+'</td>';
    	heading_data += '</tr>';
    	heading_data += '<tr>';
    	heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name2+'</td>';
    	heading_data += '</tr>';
    	
    	heading_data += '</tbody></table>';
    	var print_data = stylesheet+heading_data+tableData
    	newWin= window.open("");
    	newWin.document.write(print_data);
    	newWin.print();
    	newWin.close();
	};
</script>

<style>
    input[type=checkbox], input[type=radio] {
    margin: 4px 4px 0px;
    line-height: normal;
	}
</style>

<script>
	$(document).ready(function(){
		var maxEndDate = new Date('Y/m/d');
		var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
		
		var year = "20"+fin_y;
		
		
		var cur_y = new Date().getFullYear().toString().substr(-2);
		if(cur_y > fin_y){
			var year2 = parseInt(fin_y) + parseInt(1);
			var year2_new = "20"+year2;
			
			var e_dat = new Date(year2_new+'/03/31');
			var maxEndDate_new = e_dat;
			}else{
			var maxEndDate_new = maxEndDate;
		}
		
		var minStartDate = new Date(year, 03);
		/* console.log(minStartDate);
		console.log(maxEndDate_new);*/
		
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
			timepicker: false
		});
		
		$(document).on("click", ".sortable", function () {
			var table = $("#CrateLedger tbody");
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


