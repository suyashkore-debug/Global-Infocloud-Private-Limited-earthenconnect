<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
				<div class="panel_s accounting-template estimate">
					<div class="row">
						<div class="col-md-12">
							<div class="panel-body">
								
								<?php
									$customer_custom_fields = false;
									if(total_rows(db_prefix().'customfields',array('fieldto'=>'pur_order','active'=>1)) > 0 ){
										$customer_custom_fields = true;
									}
								?>
								<div class="tab-content">
									<?php if($customer_custom_fields) { ?>
										<div role="tabpanel" class="tab-pane" id="custom_fields">
											<?php $rel_id=( isset($pur_order) ? $pur_order->id : false); ?>
											<?php echo render_custom_fields( 'pur_order',$rel_id); ?>
										</div>
									<?php } ?>
									<div role="tabpanel" class="tab-pane active" id="general_infor">
										<div class="row">
											<div class="col-md-2">
												
												<div class="form-group ">
													<?php;
														$pur_entry_number = $pur_order->pur_order_number;;
														
														$number = (isset($pur_order) ? $pur_order->number : $next_number);
													echo form_hidden('number',$number); ?> 
													
													<label for="pur_entry_number">PO Entry No.</label>
													
													<input type="text" readonly="" class="form-control" name="pur_entry_number" id="pur_entry_number" value="<?php echo html_entity_decode($purchase_details->PurchID); ?>">
													
												</div>
											</div>
											
											<div class="col-md-2">
												<input type="hidden" name="trans_date" id="trans_date" value="<?php echo $purchase_details->Transdate;?>">
												<?php $value = (isset($purchase_details) ? _d(substr($purchase_details->Transdate,0,10)) : '');?>
												
												<?php echo render_date_input('prd_date','PO Entry Date',$value); ?>
											</div>
											
											<div class="col-md-2 ">
												<div class="form-group">
													<label for="Po_number"><?php echo _l('P.O No.'); ?></label>
													<input type="text" readonly="" class="form-control" name="Po_number" id="Po_number" value="<?= $purchase_details->PO_Number?>"  aria-invalid="false">
												</div>
											</div>
											<div class="col-md-2 ">
												<div class="form-group">
													<label for="estimate">Balance</label>
													<?php 
														$actBal = $purchase_details->BAL1 + $purchase_details->BAL2 + $purchase_details->BAL3 + $purchase_details->BAL4 +$purchase_details->BAL5 +$purchase_details->BAL6 + $purchase_details->BAL7 + $purchase_details->BAL8 +$purchase_details->BAL9 + $purchase_details->BAL10 + $purchase_details->BAL11 + $purchase_details->BAL12 + $purchase_details->BAL13;
														if($actBal > 0){
															$actBal_new = number_format($actBal,2)."Dr";
															}else{
															$actBal_new = number_format($actBal,2)."Cr";
														}             
													?>
													<input type="text" readonly="" class="form-control" name="c_balance" id="c_balance" value="<?= $actBal_new ?>" aria-invalid="false">
													
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="estimate">Station</label>
													<input type="text" readonly="" class="form-control" name="station_n" id="station_n"  value="<?= $purchase_details->StationName?>" aria-invalid="false">
												</div>  
											</div>
											
										</div>
										<div class="row">
											<div class="col-md-2">
												<input type="hidden" name="vendor_code" id="vendor_code" value="<?php echo $purchase_details->Vendor; ?>">
												<label for="vendor">Vendor</label>
												<input type="hidden" name="vendor" id="vendor" readonly class="form-control" value="<?php echo $purchase_details->userid; ?>">
												<input type="text" name="vendor_name" id="vendor_name" readonly class="form-control" value="<?php echo $purchase_details->company; ?>">
												
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="estimate">State Name</label>
													<input type="text" readonly="" class="form-control" name="state_f" id="state_f" value="<?= $purchase_details->state_name?>" aria-invalid="false">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="estimate">City</label>
													<input type="text" readonly="" class="form-control" name="city" id="city"  value="<?= $purchase_details->city_name?>" aria-invalid="false">
												</div>
											</div>
											<div class="col-md-3 ">
												<div class="form-group">
													<label for="estimate">Address</label>
													<input type="text" readonly="" class="form-control" name="address" id="address" value="<?= $purchase_details->address?>" aria-invalid="false">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="estimate">Address 2</label>
													<input type="text" readonly="" class="form-control" name="address2" id="address2" value="<?= $purchase_details->Address3?>"  aria-invalid="false">
												</div>
											</div>
											
										</div>
										<?php
											if (has_permission_new('PurchaseQCApproveReject', '', 'view')) {
											    $status = "";
											    $TotalItem = count($QCItemsList);
											    $totalY = 0;
                                				$totalN = 0;
                                				$totalH = 0;
                                				$totalC = 0;
                                				foreach($QCItemsList as $value){
                                				    $status = $value["Status"];
                                					if($status == 'Y'){
                                						$totalY++;
                                					}elseif($status == 'N'){
                                						$totalN++;
                                					}elseif($status == 'H'){
                                						$totalH++;
                                					}else if($status == 'C'){
                                						$totalC++;
                                					}
                                				}
                                				if($totalN == $TotalItem || $totalN > 0 && $totalY >0 || $totalN > 0 && $totalH >0){
                                					$QCStatus = 'N';
                                				}
                                				if($totalY == $TotalItem ){
                                					$QCStatus = 'Y';
                                				}
                                				if($totalH == $TotalItem || $totalN == 0 && $totalH > 0 && $totalY >0){
                                					$QCStatus = 'H';
                                				}
                                				if($totalC >0){
                                					$QCStatus = 'C';
                                				}
											?>
											<div class="row">
												<div class="col-md-2">
													<div class="form-group">
														<label for="status"><small class="req text-danger">* </small>Status </label>
														<select class="selectpicker" data-live-search="true" data-width="100%" name="status" id="status">
															<option value="">None Selected</option>
															<option value="N" <?php if($QCStatus == "N"){ echo "selected";}?>>Pending</option>
															<option value="H" <?php if($QCStatus == "H"){ echo "selected";}?>>Hold</option>
															<option value="Y" <?php if($QCStatus == "Y"){ echo "selected";}?>>Approve</option>
															<option value="C" <?php if($QCStatus == "C"){ echo "selected";}?>>Cancel</option>
														</select>
													</div>
												</div>
												<div class="col-md-2">
												    <?php
												        $val = $purchase_details->is_deduction;
												    ?>
													<div class="form-group">
														<label for="is_deduction"><small class="req text-danger">* </small>Is Deduction</label>
														<select class="selectpicker" data-live-search="true" data-width="100%" name="is_deduction" id="is_deduction">
															<option value="">None Selected</option>
															<option value="Y" <?php if($val == "Y"){ echo "selected";}?>>Yes</option>
															<option value="N" <?php if($val == "N"){ echo "selected";}?>>No</option>
														</select>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="remark"><small class="req text-danger">* </small>Remark</label>
														<textarea class="form-control" name="remark" id="remark"><?= $QCItemsList[0]['remark']?></textarea>
													</div>
												</div>
												<div class="col-md-2">
													<br>
													<div class="form-group">
													<?php
                									    if (has_permission_new('PurchaseQCApproveReject', '', 'create')) {
                								    ?>
														<button type="button"  onclick="ApproveRejectQCStatus()" class="form-control btn btn-warning">Update</button>
													<?php
                									    }else{
                									?>
                									   <button type="button" disabled class="form-control btn btn-warning">Update</button>
                									<?php
                									    }
													?>
													</div>
												</div>
											</div>
											<?php
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-body mtop10">
						<div class="row col-md-12">
							<p class="bold p_style"><?php echo "Item QC" ?></p>
							<hr class="hr_style"/>
							<?php
								foreach($purchase_history as $each)
								{
									foreach($QCItemsList as $QCItem){
										if($each['ItemID'] == $QCItem['ItemID']){
											
											$QcParameters = $QCItem['QCParameters'];
											$QCValues = $QCItem['QCValues'];
										?>
										<div class="col-md-4">
											<table class="table  table-striped table-bordered" width="100%">
												<thead>
													<tr style="margin-top:20px;"> 
														<th align="center" colspan='7' style="font-size:14px;"><b class="text-danger"><?= $each['description']?></b></th>
													</tr>
													<tr style="margin-top:20px; background-color:#51647c; color:#fff !important;"> 
														<th style="color:#fff !important;"><b>Parameter</b></th>
														<th style="color:#fff !important;"><b>Min</b></th>
														<th style="color:#fff !important;"><b>Max</b></th>
														<th style="color:#fff !important;"><b>Value</b></th>
													</tr>
												</thead>
												<tbody id="QcItem<?= $QCItem['ItemID']?>">
													<?php
														foreach($QcParameters as $param){
															$QCvalue = '';
															foreach($QCValues as $val){
																if($val['ParameterID'] == $param['para_id'] && $val['ItemID'] == $QCItem['ItemID']){
																	$QCvalue = $val['value'];
																}
															}
															
															$backgroundcss = '';
															if(!empty($QCvalue)){
																if($QCvalue > $param['max_range'] || $QCvalue < $param['min_range']){
																	$backgroundcss = "background-color:#fe6363;";
																}
															}
															
														?>
														<tr style="<?= $backgroundcss;?>">
															<td width="50%">
																<input type="hidden" name="paramId<?= $QCItem['ItemID']?>" value="<?= $param['para_id']?>">
																<h5><b><?= $param['parameter_name']?></b></h5>
															</td>
															<td width="15%"><input type="hidden" class="form-control" name="MinRange<?= $QCItem['ItemID']?>" id="MinRange<?= $QCItem['ItemID']?>" value="<?= $param['min_range'];?>"><?= $param['min_range']?></td>
															<td width="15%"><input type="hidden" class="form-control" name="MaxRange<?= $QCItem['ItemID']?>" id="MaxRange<?= $QCItem['ItemID']?>" value="<?= $param['max_range'];?>"><?= $param['max_range']?></td>
															<td width="20%"><input type="text" class="form-control" name="paramValue<?= $QCItem['ItemID']?>" onblur="CheckRange('<?= $QCItem['ItemID']?>')" id="Param<?= $param['id']?>" value="<?= $QCvalue;?>"></td>
															</tr>
														<?php
														}
													?>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="4" >
															<input type="hidden" name="ItemStatus<?= $QCItem['ItemID']?>" id="ItemStatus<?= $QCItem['ItemID']?>" value="<?= $QCItem['Status']?>">
															<?php
																if($QCItem['Status'] !== 'C' && has_permission_new('PurchaseQCAddEdit', '', 'create')){
																?>
																<button style="max-width: 30%;margin-left: 32%;" <?php if($QCStatus == "C" || $QCStatus == "H"){ echo "disabled";}?> type="button" onclick="SaveQCValue('<?= $QCItem['ItemID']?>')" class="form-control btn btn-primary">Update QC</button>
																<?php
																}
															?>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
										<?php
										}
									}
								}
								
							?>
							
						</div>
					</div>
				</div>
				
			</div>
			
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
</body>
<style>
	.table_purchase_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table_purchase_report thead th { position: sticky; top: 0; z-index: 1; }
	.table_purchase_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table_purchase_report table  { border-collapse: collapse; width: 100%; }
	.table_purchase_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.table_purchase_report th     { background: #50607b;color: #fff !important; }
	
	
	#table_purchase_report tr:hover {
	background-color: #ccc;
	}
	
	#table_purchase_report td:hover {
	cursor: pointer;
	}
</style>
<script>
	function ApproveRejectQCStatus() {
		var PurchaseEntryNo = $('#pur_entry_number').val();
		var status = $('#status').val();
		var remark = $('#remark').val();
		var is_deduction = $('#is_deduction').val();
		
		if(status == '' || status == null){
			alert('Please Select Status');
		}else if(remark == '' || remark == null){
			alert('Please Enter Remark');
		}else if(is_deduction == "" || is_deduction == null){
		    alert("Please Select Is Deduction Yes/No");
		}else{
			$.ajax({
				url: "<?php echo admin_url(); ?>QcMaster/UpdateQCStatus", // Replace with your server URL
				type: 'POST',
				data: {PurchaseEntryNo:PurchaseEntryNo,status:status,remark:remark,is_deduction:is_deduction}, // Send serialized data
				dataType:"JSON",
				success: function(response) {
					if (response) {
						alert('QC Status Updated successfully!');
						location.reload();
						} else {
						alert('Something Went Wrong');
					}
				},
			});
		}
	}
	function SaveQCValue(itemID) {
		let isValid = true;
		let paramData = {}; // Object to hold key-value pairs (paramId => paramValue)
		let PurchaseEntryNo = $('#pur_entry_number').val(); // Make sure this element exists
		let ItemStatus = $('#ItemStatus'+itemID).val(); 
		
		// Get the itemID (ensure itemID is defined)
		let itemData = { 
			'itemID': itemID, 
			'PurchaseEntryNo': PurchaseEntryNo,
			'ItemStatus': ItemStatus,
		};
		
		
		// Loop through text inputs and hidden parameter IDs in the specific tbody
		$(`#QcItem${itemID} input[type="text"]`).each(function() {
			let paramValue = $(this).val().trim(); // Get and trim the input value
			let paramId = $(this).closest('tr').find('input[name="paramId'+itemID+'"]').val(); // Get corresponding paramId
			
			if (paramValue === "") {
				alert('Please fill in all values!');
				isValid = false;
				return false; 
			}
			
			paramData[paramId] = paramValue;
		});
		
		if (!isValid) {
			return;
		}
		
		let formData = {
			'parameters': paramData, // Object for parameters
			'itemInfo': itemData // Object for itemID
		};
		
		let serializedData = JSON.stringify(formData);
		
		$.ajax({
			url: "<?php echo admin_url(); ?>QcMaster/SavePurchaseQC", // Replace with your server URL
			type: 'POST',
			data: serializedData, // Send serialized data
			contentType: 'application/json', // Set content type to JSON
			success: function(response) {
				if (response) {
					alert('QC values saved successfully!');
					location.reload();
					} else {
					alert('Something Went Wrong');
				}
			},
		});
	}
	function CheckRange(itemID) {
		
		var Status = 'Y';
		
		// Loop through text inputs and hidden parameter IDs in the specific tbody
		$(`#QcItem${itemID} input[type="text"]`).each(function() {
			let paramValue = $(this).val().trim(); // Get and trim the input value
			let MinRange = $(this).closest('tr').find('input[name="MinRange'+itemID+'"]').val(); 
			let MaxRange = $(this).closest('tr').find('input[name="MaxRange'+itemID+'"]').val(); 
			
			if(paramValue !== ''){
				if (parseFloat(paramValue) < parseFloat(MinRange) || parseFloat(paramValue) > parseFloat(MaxRange)) {
					Status = 'H';
					$(this).closest('tr').css('background-color', '#fe6363');
					}else{
					$(this).closest('tr').css('background-color', '#fff');
				}
				}else{
				Status = 'N';
				$(this).closest('tr').css('background-color', '#fff');
			}
			
		});
		
		$('#ItemStatus'+itemID).val(Status);
		
	}
	
	
	
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_purchase_report");
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
<script>
	$('.add-new-transfer').on('click', function(){
		$('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
		$('#transfer-modal').modal('show');
		init_journal_entry_table();
	});
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
		
		$('#prd_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
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
</html>				