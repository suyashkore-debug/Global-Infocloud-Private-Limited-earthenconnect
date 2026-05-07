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
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Sales VS Sales Return </b></li>
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
									<div class="form-group">
										<label for="AccountID" class="control-label">AccountID</label>
										<input type="text" name="AccountID" id="AccountID" class="form-control">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="AccountName" class="control-label">Account Name</label>
										<input type="text" name="AccountName" id="AccountName" class="form-control" readonly>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="AccountName" class="control-label">Account Address</label>
										<input type="text" name="AccountAddress" id="AccountAddress" class="form-control" readonly>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-2">
									<?php 
										echo render_date_input('from_date','From Date',$from_date);          
									?>
								</div>
								
								<div class="col-md-2">
									<?php 
										echo render_date_input('to_date','To Date',$to_date);          
									?>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="locType" class="control-label">Location Type</label>
										<select name="locType" id="locType" class="selectpicker" data-width="100%" data-none-selected-text="Non selected" data-live-search="true" tabindex="-98">
											<option value="">All</option>
											<option value="2">OutStation</option>
											<option value="1">Local</option>
											<option value="3">notDefine</option>
										</select>
									</div>
								</div>
								
								<div class="col-md-2">
									<label for="repType" class="control-label">Report Type</label>
									<div class="form-group">
										<select name="repType" id="repType" class="selectpicker" data-width="100%" data-none-selected-text="Non selected" data-live-search="true" tabindex="-98">
											<option value="1">Account Wise Details</option>
											<option value="2">Item Wise Details</option>
										</select>
									</div>
								</div>
								<div class="col-md-2" style="display:none;" id="SubgroupDiv">
									<label for="Subgroup2" class="control-label">Subgroup</label>
									<div class="form-group">
										<select name="Subgroup2[]" id="Subgroup2" data-actions-box="true" multiple class="selectpicker" data-width="100%" data-none-selected-text="None selected" data-live-search="true" tabindex="-98">
											<?php
												foreach($Subgroup2 as $group){
												?>
												<option <?php if($group['id'] == '1' || $group['id'] == '2'){echo "selected";}?> value="<?= $group['id'];?>"><?= $group['name'];?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="clearfix"></div>
								
								<div class="col-md-8">
									<div class="custom_button">
										<a class="btn btn-info search_data" href="#" id="search_data">Show</a>
										<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export</span></a>
										<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									</div>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" style="float: right;">
								</div>
							</div>
							
						</div>
						<div class="modal fade" id="transfer-modal">
							<div class="modal-dialog modal-xl" style=" max-width: 1230px;">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h6 class="modal-title"> Account List</h6>
									</div>
									<div class="modal-body" style="padding:5px;">
										
										<div class="row">
											<div class="col-md-12">
												<div class="table_accountlist">
													<table class="tree table table-striped table-bordered table_accountlist" id="table_accountlist" width="100%">
														<thead>
															<tr>
																<th style="text-align:center;">AccountID</th>
																<th style="text-align:center;">Account Name</th>
																<th style=" text-align:center;">Station Name</th>
																<th style=" text-align:center;">State</th>
																<th style="text-align:center;">Address 1</th><!--
																<th style=" text-align:center;">Address 2</th>-->
																<th style=" text-align:center;">GSTNO</th>
																<th style=" text-align:center;">Blocked</th>
															</tr>
														</thead>
														<tbody>
															<?php
																foreach($Accountlist as $value){
																?>
																<tr class="GetAccountID" data-id="<?php echo $value["AccountID"]; ?>">
																	<td style="padding:0px 3px !important;text-align:center;" ><?php echo $value["AccountID"]; ?></td> 
																	<td style="padding:0px 3px !important;"><?php echo $value["company"]; ?></td>
																	<td style="padding:0px 3px !important;"><?php echo $value["StationName"]; ?></td>
																	<td style="padding:0px 3px !important;"><?php echo $value["state"]; ?></td>
																	<td style="padding:0px 3px !important;"><?php echo substr($value["address"],0,30); ?></td><!--
																	<td style="padding:0px 3px !important;"><?php echo substr($value["Address3"],0,25); ?></td> -->
																	<td style="padding:0px 3px !important;"><?php echo $value["vat"]; ?></td>
																	<td style="padding:0px 3px !important;text-align:center;"><?php echo $value["Blockyn"]; ?></td>
																</tr>
																<?php
																} 
															?>
														</tbody>
													</table>   
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer" style="padding:0px;">
										<input type="text" id="myInput2"  autofocus="1" name='myInput2' onkeyup="myFunction2()" placeholder="Search for names.."  style="float: left;width: 100%;">
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<span id="searchh" style="display:none;">please wait fetching data...</span>
						<span id="searchh2" style="display:none;">please wait Exporting data...</span>
						<div class="fixTableHead SaleVsSaleRtn_report">
							
						</div>
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<style>
    .SaleVsSaleRtn_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
    .SaleVsSaleRtn_report thead th { position: sticky; top: 0; z-index: 1; }
    .SaleVsSaleRtn_report tbody th { position: sticky; left: 0; }
    
    /* Just common table stuff. Really. */
    .SaleVsSaleRtn_report table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    .SaleVsSaleRtn_report th     { background: #50607b;color: #fff !important; }
    
    .table_accountlist { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
    .table_accountlist thead th { position: sticky; top: 0; z-index: 1; }
    .table_accountlist tbody th { position: sticky; left: 0; }
    
    /* Just common table stuff. Really. */
    .table_accountlist table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    .table_accountlist th     { background: #50607b;color: #fff !important; }
    
    #table_accountlist tr:hover {
    background-color: #ccc;
    }
    
    #table_accountlist td:hover {
	cursor: pointer;
    }
	
</style>
<script type="text/javascript" language="javascript" >
    function myFunction() 
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("SaleVsSaleRtn_report");
        tr = table.getElementsByTagName("tr");
        for (i = 6; i < tr.length; i++) 
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
    
    function myFunction2() 
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        table = document.getElementById("table_accountlist");
        tr = table.getElementsByTagName("tr");
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
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		$('#repType').on('change', function() {
		var selectedVal = $(this).val();
		if (selectedVal == '2') {
			$('#SubgroupDiv').show();
			$('#Subgroup2').selectpicker('refresh'); // refresh if using bootstrap-select
		} else {
			$('#SubgroupDiv').hide();
			$('#Subgroup2').selectpicker('refresh'); // refresh if using bootstrap-select
		}
	});
	
		$("#AccountID").dblclick(function(){
            $('#transfer-modal').modal('show');
            $('#transfer-modal').on('shown.bs.modal', function () {
				$('#myInput1').focus();
			})
		});
		
		// Initialize For Account
		$( "#AccountID" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "<?php echo admin_url(); ?>sale_reports/AccountList",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
				$('#AccountID').val(ui.item.value); // display the selected text
				$('#AccountName').val(ui.item.label); // display the selected text
				$('#AccountAddress').val(ui.item.address); // display the selected text
				return false;      
			}
		});
		
		$('.GetAccountID').on('click',function(){ 
			$('#transfer-modal').modal('hide');
			AccountID = $(this).attr("data-id");
			$.ajax({
				url:"<?php echo admin_url(); ?>sale_reports/AccountDetails",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{AccountID:AccountID,},
				
				success:function(data){
					
                    $('#AccountID').val(data.AccountID); // display the selected text
                    $('#AccountName').val(data.company); // display the selected text
                    $('#AccountAddress').val(data.address); // display the selected text
                    $('#locType').attr('disabled', 'disabled');
                    
                    $("#repType").children().remove();
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#repType').append('<option value="2">ItemWise Details</option>');
                    $("#repType").selectpicker("refresh");
					$('#SubgroupDiv').show();
					$('#Subgroup2').selectpicker('refresh'); // refresh if using bootstrap-select
                    $('#search_data').focus();
				}
			});
		});
		$('#AccountID').on('blur',function(){
			
			var AccountID = $(this).val();
			if(AccountID !==''){
				$.ajax({
					url:"<?php echo admin_url(); ?>sale_reports/AccountDetails",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{AccountID:AccountID,},
					
					success:function(data){
						
						if(empty(data)){
							alert("AccountID not found..."); 
							}else{
							$('#AccountID').val(data.AccountID); // display the selected text
							$('#AccountName').val(data.company); // display the selected text
							$('#AccountAddress').val(data.address); // display the selected text
							$('#locType').attr('disabled', 'disabled');
							
							/*$('select[name=repType]').val(2);
							$('.selectpicker').selectpicker('refresh');*/
							$("#repType").children().remove();
							// APPEND OR INSERT DATA TO SELECT ELEMENT.
							$('#repType').append('<option value="2">ItemWise Details</option>');
							$("#repType").selectpicker("refresh");
						}
						$('#search_data').focus();
					}
				});
			}
		})
		
		$('#repType').on('change',function(){
			var report_type = $(this).val();
			//alert(report_type);
			if(report_type == 2){
				$('#AccountID').val('');
				$('#AccountName').val('');
				$('#AccountAddress').val('');
				$('#locType').removeAttr('disabled');
				$("#repType").children().remove();
				// APPEND OR INSERT DATA TO SELECT ELEMENT.
				$('#repType').append('<option value="2">ItemWise Details</option>');
				$('#repType').append('<option value="1">AccountWise Details</option>');
				$("#repType").selectpicker("refresh");
				}else{
				$("#repType").children().remove();
				// APPEND OR INSERT DATA TO SELECT ELEMENT.
				$('#repType').append('<option value="1">AccountWise Details</option>');
				$('#repType').append('<option value="2">ItemWise Details</option>');
				$("#repType").selectpicker("refresh");
			}
		});
		
		
		$('#AccountID').on('focus',function(){
            $('#AccountID').val('');
            $('#AccountName').val('');
            $('#AccountAddress').val('');
            $('#locType').removeAttr('disabled');
            $("#repType").children().remove();
            // APPEND OR INSERT DATA TO SELECT ELEMENT.
            $('#repType').append('<option value="1">AccountWise Details</option>');
            $('#repType').append('<option value="2">ItemWise Details</option>');
			$('#SubgroupDiv').hide();
			$('#Subgroup2').selectpicker('refresh'); // refresh if using bootstrap-select
            $("#repType").selectpicker("refresh");
		});
		
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var AccountID = $("#AccountID").val();
			var AccountName = $("#AccountName").val();
			var AccountAddress = $("#AccountAddress").val();
			var locType = $("#locType").val();
			var repType = $("#repType").val();
			var Subgroup2 = $("#Subgroup2").val();
			
			$.ajax({
				url:"<?php echo admin_url(); ?>sale_reports/GetSaleVsSaleRtnReport",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{from_date:from_date, to_date:to_date, AccountID:AccountID,AccountName:AccountName,locType:locType,repType:repType,Subgroup2:Subgroup2},
				beforeSend: function () {
					$('#searchh').css('display','block');
					$('.SaleVsSaleRtn_report').css('display','none');
				},
				complete: function () {
					$('.SaleVsSaleRtn_report').css('display','');
					$('#searchh').css('display','none');
				},
				success:function(data){
					$('.SaleVsSaleRtn_report').html(data);
				}
			});
		});
		
	});
	$("#caexcel").click(function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var AccountID = $("#AccountID").val();
	    var AccountName = $("#AccountName").val();
	    var locType = $("#locType").val();
	    var repType = $("#repType").val();
	    var AccountAddress = $("#AccountAddress").val();
	    var Subgroup2 = $("#Subgroup2").val();
	    $.ajax({
			url:"<?php echo admin_url(); ?>sale_reports/ExportSaleVsSaleRtnReport",
			method:"POST",
			data:{from_date:from_date, to_date:to_date, AccountID:AccountID,AccountName:AccountName,locType:locType,repType:repType,AccountAddress:AccountAddress,Subgroup2:Subgroup2},
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
<script type="text/javascript">
	function printPage(){
		
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var AccountID = $("#AccountID").val();
		var AccountName = $("#AccountName").val();
		var AccountAddress = $("#AccountAddress").val();
		var locType = $("#locType").val();
		var repType = $("#repType").val();
		if(locType == '1'){
            var locTypeName = 'Local';
			}else if(locType == '2'){
            var locTypeName = 'Outstation';
			}else{
            var locTypeName = 'notDefine';
		}
        if(repType == '1'){
            var repTypeName = 'AccountWiseDetails';
			}else{
            var repTypeName = 'ItemWiseDetails';
		}
        if(AccountID !==''){
            var colspan = '11';
            var AccountDetails = 'Account Name : '+ AccountName+ ' Address : '+AccountAddress;
			}else{
            if(repType == '2'){
                var colspan = '11';
				}else{
                var colspan = '8';
			}
		}
		var filterdate = 'Report Date : '+from_date+' To '+to_date;
		var LocationType = 'LocationType : '+locTypeName;
		var RType = 'Report Type : '+repTypeName;
		var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">';
		heading_data += '<tbody><tr><td style="text-align:center;" colspan="'+colspan+'"><?php echo $company_detail->company_name; ?></td></tr>';
		heading_data += '<tr><td style="text-align:center;" colspan="'+colspan+'"><?php echo $company_detail->address; ?></td></tr>';    
		heading_data += '<tr><td style="text-align:left;"colspan="'+colspan+'">'+filterdate+'</td></tr>';
		if(AccountID !==''){
			heading_data += '<tr><td style="text-align:left;"colspan="'+colspan+'">'+AccountDetails+'</td></tr>';
		}
		heading_data += '<tr><td style="text-align:left;"colspan="'+colspan+'">'+LocationType+'</td></tr>';
		heading_data += '<tr><td style="text-align:left;"colspan="'+colspan+'">'+RType+'</td></tr>';
		heading_data += '</tbody></table>';
		
		var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .show_in_print{ display:block; }</style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[1].innerHTML+'</table>';
		
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
			var maxEndDate_new = maxEndDate;
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
			timepicker: false
		});
		$(document).on("click", ".sortable", function () {
			var table = $("#SaleVsSaleRtn_report tbody");
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