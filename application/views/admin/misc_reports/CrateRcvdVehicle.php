<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .CratesRcvdVehicleTable          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	.CratesRcvdVehicleTable thead th { position: sticky; top: 0; z-index: 1; }
	.CratesRcvdVehicleTable tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
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
            					<li class="breadcrumb-item active text-capitalize"><b>Misc. Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Crate Received Via Vehicle Return</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="_buttons">
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
								<div class="col-md-2">
									
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
								
								<div class="col-md-2">
									
									<div class="form-group" app-field-wrapper="to_date">
										<label for="to_date" class="control-label to_date_text">To Date</label>
										<div class="input-group date">
											<input type="text" id="to_date" name="to_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
											<div class="input-group-addon">
												<i class="fa fa-calendar calendar-icon"></i>
											</div>
										</div>
									</div>
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
								
								<div class="col-md-5">
									<br>
									<div class="custom_button">
										<button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;">Show</button>
									</div>
									<div class="custom_button">
										<button class="btn btn-default pull-left mleft5 " href="javascript:void(0);"    onclick="printPage();" style="font-size:12px;">Print</button>
									</div>
								</div>
							</div> 
							<div class="row">
								<div class="col-md-9">
								</div> 
								<div class="col-md-3">
									<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search " title="Type in a name" style="float: left;">
								</div> 
							</div> 
						</div>
						<br>
						<div class="clearfix"></div>
						
						<div class="fixTableHead load_data">
							
						</div>
						<span id="searchh" style="display:none;">Loading.....</span>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
</div>
</div>

<style>
    .CratesRcvdVehicleTable { overflow: auto;max-height: 60vh;position:relative;top: 0px; }
	.CratesRcvdVehicleTable thead th { position: sticky; top: 0; z-index: 1; }
	.CratesRcvdVehicleTable tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.CratesRcvdVehicleTable table  { border-collapse: collapse; }
	.CratesRcvdVehicleTable th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.CratesRcvdVehicleTable th     { background: #50607b;color: #fff !important; }
	
	
</style>
<?php init_tail(); ?>
<!--new update -->
<!--<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>-->
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var driver = $("#driver").val();
			$.ajax({
				url:"<?php echo admin_url(); ?>misc_reports/GetCratesRcvdVehicle",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{from_date:from_date,to_date:to_date,driver:driver},
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
		});
	});
	/* $("#caexcel").click(function(){
	    var to_date = $("#to_date").val();
	    
        $.ajax({
		url:"<?php echo admin_url(); ?>misc_reports/export_crate_legder",
		method:"POST",
		data:{to_date:to_date},
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
	});*/
	
	function printPage(){
		var AsOn =    $('#to_date').val();
		
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="9">Crates received via Vehicle return</td>';
		heading_data += '</tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="9"> Report Date :'+AsOn+'</td>';
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
    function myFunction2() 
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.getElementById("CratesRcvdVehicleTable");
		var tbody = table.getElementsByTagName("tbody")[0];
		var tr = tbody.getElementsByTagName("tr");
        for (i = 1; i < tr.length; i++) 
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
		
		// $(document).on("click", ".sortable", function () {
			// var table = $("#CratesRcvdVehicleTable tbody");
			// var rows = table.find("tr").toArray();
			// var index = $(this).index();
			// var ascending = !$(this).hasClass("asc");
			
			
			// $(".sortable").removeClass("asc desc");
			// $(".sortable span").remove();
			
			// $(this).addClass(ascending ? "asc" : "desc");
			// $(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
			
			// rows.sort(function (a, b) {
				// var valA = $(a).find("td").eq(index).text().trim();
				// var valB = $(b).find("td").eq(index).text().trim();
				
				// if ($.isNumeric(valA) && $.isNumeric(valB)) {
					// return ascending ? valA - valB : valB - valA;
					// } else {
					// return ascending
					// ? valA.localeCompare(valB)
					// : valB.localeCompare(valA);
				// }
			// });
			// table.append(rows);
		// });
	});
</script> 


