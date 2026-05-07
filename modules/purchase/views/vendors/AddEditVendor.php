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
            					<li class="breadcrumb-item active text-capitalize"><b>Purchase</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Vendor Master</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait Create new Vendor...</div>
								<div class="searchh4" style="display:none;">Please wait update Vendor...</div>
							</div>
							<br>
							<div class="col-md-2">
								<?php
									$nextItemGroupID = $lastId + 1;
									$next_cust_number = (int) get_option('next_vendor_number');
									$prefix = "V";
									$next_cust_number = $prefix.str_pad($next_cust_number,5,'0',STR_PAD_LEFT);
								?>
								
								<div class="form-group" app-field-wrapper="ItemGroupID">
									<small class="req text-danger">* </small>
									<label for="AccountID" class="control-label">Account ID</label>
									<input type="text" id="AccountID" readonly value="" name="AccountID" class="form-control" value="">
									<?php $selected_company = $this->session->userdata('root_company');
										$UserID = $this->session->userdata('username');
									?>
									<input type="hidden" id="PlantID" name="PlantID" class="form-control" value="<?php echo $selected_company;?>">
									<input type="hidden" id="UserID" name="UserID" class="form-control" value="<?php echo $UserID;?>">
									<input type="hidden" id="HiddenVendorCode" name="HiddenVendorCode" class="form-control" value="">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="AccountName">
									<small class="req text-danger">* </small>
									<label for="AccountName" class="control-label">Account Name</label>
									<input type="text" id="AccountName" name="AccountName" class="form-control" value="">
								</div>							
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="vendor_type" class="control-label">Vendor Type</label>
									<select class="selectpicker display-block" data-width="100%" id="vendor_type" name="vendor_type" data-none-selected-text="<?php echo 'Non Selected'; ?>" data-live-search="true">
										<option value=""></option>
										<?php foreach($VendorType as $type){ ?>
											<option value="<?php echo $type['SubActGroupID']; ?>" ><?php echo $type['SubActGroupName']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="Blockyn">GST Type</label>
									<select name="gst_type" id="gst_type" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
										<option value="1" >Registered</option>
										<option value="2">Un-Registered</option>
										<option value="3" >Composition</option>
									</select>
								</div>
							</div>
							
							
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="vat">
									<label for="vat" class="control-label">GST Number</label>
									<input type="text" id="vat" name="vat" class="form-control" 
									pattern="([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([0-9A-Za-z]){2}" maxlength="15" minlength="15" value="">
									<span class="gst_denger" style="color:red;"></span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="">PAN Number</label>
									<input type="text" name="pan" maxlength="10" minlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="pan" value="<?php echo html_entity_decode($client->Pan); ?>" class="form-control">
									<span class="pan_denger" style="color:red;"></span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="state" class="control-label">State</label>
									<select class="selectpicker display-block" data-width="100%" id="state" name="state" data-none-selected-text="<?php echo 'Non Selected'; ?>" data-live-search="true">
										<option value=""></option>
										<?php foreach($state as $st){ ?>
											<option value="<?php echo $st['short_name']; ?>" ><?php echo $st['state_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="city" class="control-label">City</label>
									<select class="selectpicker display-block" data-width="100%" id="city" name="city" data-none-selected-text="<?php echo 'Non Selected'; ?>" data-live-search="true">
										<option value=""></option>    
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<?php echo render_input( 'address', 'Address 1'); ?>
							</div>
							<div class="col-md-3">
								<?php echo render_input( 'address2', 'Address 2'); ?>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="zip">
									<label for="zip" class="control-label">Pin Code</label>
									<input type="text"  name="zip" id= "zip" class="form-control" value="" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<?php //echo render_input('phonenumber','MobileNo'); ?>
								<div class="form-group" app-field-wrapper="phonenumber">
									<small class="req text-danger">* </small>
									<label for="phonenumber" class="control-label">Mobile Number</label>
									<input type="text" maxlength="10" pattern="[6789][0-9]{9}" id="phonenumber" name="phonenumber" class="form-control" autocomplete="off" value="" onkeypress="return isNumber(event)">
									<span class="mob_denger" style="color:red;"></span>
								</div>
							</div>
							
							<div class="col-md-3">
								<?php //echo render_input('phonenumber','MobileNo'); ?>
								<div class="form-group" app-field-wrapper="altphonenumber">
									<label for="altphonenumber" class="control-label">Alt Mobile Number</label>
									<input type="text" maxlength="10" pattern="[6789][0-9]{9}" id="altphonenumber" name="altphonenumber" class="form-control" autocomplete="off" value="" onkeypress="return isNumber(event)">
									<span class="mob_denger" style="color:red;"></span>
								</div>
							</div>
							
							<div class="col-md-3">
								<?php echo render_input('email','Email Address'); ?>
								<span class="email_error" style="color:red;"></span>
							</div>
							
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="contact_person">
									<label for="contact_person">Contact Person</label>
									<input type="text" name="contact_person" id="contact_person" value="" class="form-control">
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="contact_person_designation">
									<label for="contact_person_designation">Contact Person Designation</label>
									<input type="text" name="contact_person_designation" id="contact_person_designation" value="" class="form-control">
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="contact_person_mobile">
									<label for="contact_person_mobile">Contact Person Mobile No</label>
									<input type="text" name="contact_person_mobile" id="contact_person_mobile" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="contact_person_whatsapp">
									<label for="contact_person_whatsapp">Contact Person WhatsApp No</label>
									<input type="text" name="contact_person_whatsapp" id="contact_person_whatsapp" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="upi_no">
									<label for="upi_no">UPI ID</label>
									<input type="text" name="upi_no" id="upi_no" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="ifsc_code">
									<label for="ifsc_code">IFSC Code</label>
									<input type="text" name="ifsc_code" id="ifsc_code" value="" class="form-control">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="bank_branch">
									<label for="bank_name">Bank Name</label>
									<input type="text" name="bank_name" id="bank_name" readonly value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="bank_branch">
									<label for="bank_branch">Bank Branch</label>
									<input type="text" name="bank_branch" id="bank_branch" readonly value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="bank_address">
									<label for="bank_address">Bank Address</label>
									<input type="text" name="bank_address" id="bank_address" value="" readonly class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="bank_ac_no">
									<label for="bank_ac_no">Bank Account No</label>
									<input type="text" name="bank_ac_no" id="bank_ac_no" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="ac_holder_name">
									<label for="ac_holder_name">Account Holder Name</label>
									<input type="text" name="ac_holder_name" id="ac_holder_name" readonly value="" class="form-control">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="">FSSAI No.</label>
									<input type="text" name="food_lic_n" id="food_lic_n" maxlength="14" minlength="14" onkeypress="return isNumber(event)" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="">Station Name</label>
									<input type="text" name="StationName" id="StationName" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<?php
								$ss = "";
									?>
									<?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
									<div class="form-group">
										<label for="">Opening Balance Amount</label>
										<input type="text" name="opening_b" id="opening_b" value="0" class="form-control" <?php echo $ss;?>>
									</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="pay_term">Payment Term</label>
									<select name="pay_term" id="pay_term" class="selectpicker form-control"  data-live-search="true">
										<option value="Credit" >Credit</option>
										<option value="Advance" >Advance</option>
										<option value="OnDelivery" >On Delivery</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="">Credit Days</label>
									<input type="text" name="credit_days" id="credit_days" value="30" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="active">Is Active ?</label>
									<select name="active" id="active" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
										<option value="1" >Yes</option>
										<option value="0" >No</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<?php
									if(isset( $client) && is_admin()){
										}else{
										$attr_date = array('disabled'=>true);
									}
								?>
								<?php $value=  _d(date('Y-m-d')); ?>
								<?php echo render_date_input( 'StartDate', 'Start Date',$value,'text',$attr_date); ?>
							</div>
							
							<div class="clearfix"></div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Tds">
									<label for="Tds" class="control-label">TDS</label>
									<select name="Tds" id="Tds" class="selectpicker form-control Tds">
										<option value="">Non Selected</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-3" id="TdsSec" style="display:none;">
								<div class="form-group" app-field-wrapper="TdsSec">
									<label for="Tds" class="control-label">Tds Section</label>
									<select class="selectpicker display-block" data-width="100%" name="Tdsselection" id="Tdsselection" data-none-selected-text="Non Selected" >
										<option value="">Non Selected</option>
										<?php foreach($Tdssection as $w): ?>
										<option value="<?= $w['TDSCode']?>"><?= $w['TDSName']?></option>
										<?php endforeach; ?>
									</select>								</div>
							</div>
							<div class="col-md-3" id="TdsPercent1" style="display:none;">
								<div class="form-group" app-field-wrapper="TdsPercent">
									<label for="Tds" class="control-label">TDS Rate (%)</label>
									<select class="selectpicker display-block" data-width="100%" name="TdsPercent" id="TdsPercent" data-none-selected-text="Non Selected" >
										<option value="">Non Selected</option>
									</select>								
								</div>
							</div>
							
						</div>
						<div class="clearfix"></div>
						<br>
						<div class="row">
							<div class="col-md-8">
								<p class="bold p_style">Vendor Wise Items</p>
								<hr class="hr_style">
								<br>
								<table width="100%">
									<thead>
										<tr>
											<th>Item</th>
											<th>Unit</th>
											<th>Packing Qty</th>
											<th>Delivery Days</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="Itemstbody">
										<tr>
											<td style="width:50%">
												<select name="ItemID[]" id="ItemID" class="selectpicker form-control" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
													<option value="">Non Selected</option>
													<?php foreach ($itemlist as $key => $value) { ?>
														<option value="<?php echo $value['item_code'];?>"><?php echo $value['description'];?></option>
													<?php } ?>
												</select>
											</td>
											<td style="width:10%">
												<input type="text" readonly id="unit" name="unit[]" class="form-control">
											</td>
											
											<td style="width:10%">
												<input type="text" id="CaseQty" readonly name="CaseQty[]" class="form-control">
											</td>
											<td style="width:10%">
												<input type="text" id="DeliveryDays" name="DeliveryDays[]" class="form-control">
											</td>
											<td style="text-align:center;width:20%">
												<select type="text" id="item_status" name="item_status[]" class="selectpicker form-control"  data-live-search="true">
													<option value="Y">Enable</option>
													<option value="N">Disable</option>
												</select>
											</td>
											<td style="width:10%">
												<a class="btn btn-success" onclick="addRow()"><i class="fa fa-plus"></i></a>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
								<table width="100%">
									<thead>
										<tr>
											<th>Days</th>
											<th>Percentage</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="Distbody">
										<tr>
											<td>
												<input type="text" id="DisDays"  name="DisDays[]" class="form-control" onkeypress="return isNumber(event)">
											</td>
											
											<td >
												<input type="text" id="DisPercentage" name="DisPercentage[]"  onkeypress="return isNumber(event)" class="form-control" min="1" max="100">
											</td>
											<td>
												<a class="btn btn-success" onclick="addRowDis()"><i class="fa fa-plus"></i></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
							<div class="clearfix"></div>
							<br><br>
							
							
							
							<div class="col-md-12">
								<?php if (has_permission('vendors', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" onclick="this.disabled = false;" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission('vendors', '', 'edit')) {
								?>
								<button type="button" style="display:none;" class="btn btn-info updateBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Update</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info updateBtn2 disabled" style="margin-right: 25px;">Update</button>
								<?php
								}?>
								
								<button type="button" class="btn btn-default cancelBtn" >Cancel</button>
							</div>
							
						</div>
						<div class="clearfix"></div>
						<!-- Iteme List Model-->
						
						<div class="modal fade Vendor_List" id="Vendor_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Vendor List</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-Vendor_List tableFixHead2">
											<table class="tree table table-striped table-bordered table-Vendor_List tableFixHead2" id="table_Vendor_List" width="100%">
												<thead>
													<tr>
														<th class="sortablePop" style="text-align:left;">Account ID</th>
														<th class="sortablePop" style="text-align:left;">Account Name</th>
														<th class="sortablePop" style="text-align:left;">Station</th>
														<th class="sortablePop" style="text-align:left;">State</th>
														<th class="sortablePop" style="text-align:left;">City</th>
														<th class="sortablePop" style="text-align:left;">Status</th>
													</tr>
												</thead>
												<tbody>
													<?php
														foreach ($table_data as $key => $value) {
														?>
														<tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
															<td><?php echo $value['AccountID'];?></td>
															<td><?php echo $value['company'];?></td>
															<td><?php echo $value['StationName'];?></td>
															<td><?php echo $value['state_name'];?></td>
															<td><?php echo $value['city_name'];?></td>
															<td><?php 
																if($value['active'] == '1'){
																	$status = 'Active';
																	}else{
																	$status = 'Inactive';
																}
															echo $status;?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>   
										</div>
									</div>
									<div class="modal-footer" style="padding:0px;">
										<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>

<script>
	$('#vendor_type').on('change', function() {
		var vendor_type = $(this).val();
		//alert(roleid);
		if(vendor_type != '' && vendor_type != null){
			var url2 = "<?php echo admin_url(); ?>purchase/GetVendorCodeByGroup";
			jQuery.ajax({
				type: 'POST',
				url:url2,
				data: {vendor_type: vendor_type},
				dataType:'json',
				success: function(data) {
					$('#AccountID').val(data);
				}
			});
			}else{
			$('#AccountID').val('');
		}
	});
	function addRow() {
		var ItemID = $("#ItemID").val();
		var ItemName = $("#ItemID option:selected").text();
		var unit = $("#unit").val();
		var CaseQty = $("#CaseQty").val();
		var DeliveryDays = $("#DeliveryDays").val();
		var item_status = $("#item_status").val();
		var item_status_text = $("#item_status option:selected").text();
		// Validate if all required fields are filled
		if (ItemID !== '' && ItemName !== '' && DeliveryDays !== '' && item_status !== '' && ItemID !== null && ItemName !== null && DeliveryDays !== null && item_status !== null) {
			var exists = false;
			
			// Check if the state and city combination already exists in the table
			$("#Itemstbody tr").each(function(index) {
				var existingItem = $(this).find("input[name='ItemID[]']").val();
				if (ItemID === existingItem) {
					exists = true;
					return false; // Exit the loop if state and city combination already exists
				}
			});
			
			if (!exists) {
				var newRow = $("<tr class='addedtr'></tr>");
				
				// Append columns to the new row
				newRow.append("<td><input type='hidden' name='ItemID[]' value='" + ItemID + "'>" + ItemName + "</td>");
				newRow.append("<td><input type='text' readonly name='unit[]' class='form-control' value='" + unit + "'></td>");
				newRow.append("<td><input type='text' readonly name='CaseQty[]' class='form-control' value='" + CaseQty + "'></td>");
				newRow.append("<td><input type='text' name='DeliveryDays[]' class='form-control' value='" + DeliveryDays + "'></td>");
				newRow.append("<td><input type='hidden' name='item_status[]' value='" + item_status + "'>" + item_status_text + "</td>");
				newRow.append("<td><a href='#' class='btn btn-danger removebtn'><i class='fa fa-times'></i></a></td>");
				
				// Append the new row to the table body
				$("#Itemstbody").append(newRow);
				
				// Clear input fields after adding row
				$("#ItemID").val('').selectpicker('refresh');
				$("#item_status").val('Y');
				$("#item_status").selectpicker("refresh");
				$("#unit").val('');
				$("#DeliveryDays").val('');
				$("#CaseQty").val('');
				} else {
				alert('The Item Is already exists.');
			}
			} else {
			alert('All fields are required.');
		}
	}
	function addRowDis() {
		var DisDays = $("#DisDays").val();
		var DisPercentage = $("#DisPercentage").val();
		
		// Validate if all required fields are filled
		if (DisDays !== '' && DisPercentage !== '' && DisDays !== null && DisPercentage !== null) {
			var exists = false;
			
			// Check if the state and city combination already exists in the table
			$("#Distbody tr.addedtrDis").each(function(index) {
				var existingDays = $(this).find("input[name='DisDays[]']").val();
				if (DisDays === existingDays) {
					exists = true;
					return false; // Exit the loop if state and city combination already exists
				}
			});
			
			if (!exists) {
				var newRow = $("<tr class='addedtrDis'></tr>");
				
				// Append columns to the new row
				newRow.append("<td><input type='hidden' name='DisDays[]' value='" + DisDays + "'>" + DisDays + "</td>");
				newRow.append("<td><input type='hidden' name='DisPercentage[]' value='" + DisPercentage + "'>" + DisPercentage + "</td>");
				newRow.append("<td><a href='#' class='btn btn-danger removebtnDis'><i class='fa fa-times'></i></a></td>");
				
				// Append the new row to the table body
				$("#Distbody").append(newRow);
				
				// Clear input fields after adding row
				$("#DisDays").val('');
				$("#DisPercentage").val('');
				} else {
				alert('This Day Is already exists.');
			}
			} else {
			alert('All fields are required.');
		}
	}
	
	// Attach event handler for removing rows
	$(document).on('click', '.removebtn', function() {
		$(this).closest('tr').remove();
	});
	
	// Attach event handler for removing rows
	$(document).on('click', '.removebtnDis', function() {
		$(this).closest('tr').remove();
	});
	
    $(document).ready(function(){
		function getSubGroupsByMain(Tdsselection) {
			$.ajax({
				url: "<?php echo admin_url(); ?>purchase/gettdspercent",
				dataType: "JSON",
				method: "POST",
				data: { Tdsselection: Tdsselection},
				beforeSend: function() {
					$('.searchh2').css('display', 'block').css('color', 'blue');
				},
				complete: function() {
					$('.searchh2').css('display', 'none');
				},
				success: function(data) {
					$('#TdsPercent').empty();
					
					
					if (data && data.length > 0) {
						$('#TdsPercent').append('<option value="">Non Selected</option>');
						$.each(data, function(index, item) {
							$('#TdsPercent').append('<option value="' + item.rate + '">' + item.rate + '</option>');
						});
						} else {
						$('#TdsPercent').append('<option value="">Non Selected</option>');
					}
					$('.selectpicker').selectpicker('refresh');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("AJAX Error:", textStatus, errorThrown);
				}
			});
		}
		$('#Tdsselection').on('change', function() {
			var Tdsselection = $(this).val();
			getSubGroupsByMain(Tdsselection);
		});
		function checkTds() {
			if ($('#Tds').val() === "1") {
				$('#TdsPercent1').show();
				$('#TdsSec').show();
				} else {
				$('#TdsPercent1').hide();
				$('#TdsSec').hide();
			}
		}
		checkTds();
		$('#Tds').on('change', function() {
			checkTds();
		});
		
		$("#AccountID").keypress(function (e) {
			var keyCode = e.keyCode || e.which;
			if(keyCode == ""){
				$("#lblError").html("");
				}else{
				//Regex for Valid Characters i.e. Alphabets and Numbers.
				var regex = /^[A-Za-z0-9]+$/;
				//Validate TextBox value against the Regex.
				var isValid = regex.test(String.fromCharCode(keyCode));
				if (!isValid) {
					$("#lblError").html("Only Alphabets and Numbers allowed.");
					}else{
					$("#lblError").html("");
				}
				return isValid;
			}
		});
		
		$("#AccountName").keypress(function (e) {
			var keyCode = e.keyCode || e.which;
			if (keyCode == "") {
				$("#lblError").html("");
				} else {
				var regex = /^[A-Za-z0-9\s]+$/; // Updated regex to allow letters and spaces
				var isValid = regex.test(String.fromCharCode(keyCode));
				return isValid;
			}
		});
		
		$("#email").on("input", function() {
			var email = $(this).val();
			var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regular expression pattern for email validation
			
			if (!regex.test(email)) {
				$(".email_error").html("Enter a valid email address."); // Display error message if email is invalid
				} else {
				$(".email_error").html(""); // Clear error message if email is valid
			}
		});
		
		$("#vat").on("input", function() {
			var gstNumber = $(this).val();
			var regex = /^([0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z][0-9][0-9A-Za-z]{2})?$/;
			
			if (!regex.test(gstNumber)) {
				$(".gst_denger").html("Enter valid GST no..");
				} else {
				$(".gst_denger").html("");
			}
		});
		
		$("#AccountName").dblclick(function(){
			$('#Vendor_List').modal('show');
			$('#Vendor_List').on('shown.bs.modal', function () {
				$('#myInput1').val('');
				$('#myInput1').focus();
			})
		});
		$('#state').on('change', function() {
			var id = $(this).val();
			var url = "<?php echo admin_url(); ?>purchase/GetCityListByStateID";
			jQuery.ajax({
				type: 'POST',
				url:url,
				data: {id: id},
				dataType:'json',
				success: function(data) {
					$("#city").children().remove();
					$.each(data, function (index, value) {
						// APPEND OR INSERT DATA TO SELECT ELEMENT.
						$('#city').append('<option value="' + value.id + '">' + value.city_name + '</option>');
					});
					$("#city").selectpicker("refresh");
				}
			});
		});
		$('#ItemID').on('change', function() {
			
			$("#unit").val('');
			$("#CaseQty").val('');
			
			var ItemID = $(this).val();
			var url = "<?php echo admin_url(); ?>purchase/GetItemDetailByID";
			jQuery.ajax({
				type: 'POST',
				url:url,
				data: {ItemID: ItemID},
				dataType:'json',
				success: function(data) {
					$("#unit").val(data.unit);
					$("#CaseQty").val(data.case_qty);
				}
			});
		});
        function ResetForm()
        {
            var HiddenVendorCode = $("#HiddenVendorCode").val();
            $('#AccountID').val('');
            $('#AccountName').val('');
            $('#address').val('');
            $('#address2').val('');
            $('#zip').val('');
            $('#phonenumber').val('');
            $('#altphonenumber').val('');
            $('#email').val('');
            $('#vat').val('');
            $('#pan').val('');
            $('#contact_person').val('');
            $('#contact_person_designation').val('');
            $('#contact_person_mobile').val('');
            $('#contact_person_whatsapp').val('');
            $('#upi_no').val('');
            $('#bank_ac_no').val('');
            $('#ac_holder_name').val('');
            $('#ifsc_code').val('');
            $('#bank_name').val('');
            $('#bank_branch').val('');
            $('#bank_address').val('');
            $('#vendor_type').val('');
			$('select[name=vendor_type]').attr('disabled',false);
            $('.selectpicker').selectpicker('refresh');
			
            $('#credit_days').val('30');
            $('#pay_term').val('Credit');
            $('.selectpicker').selectpicker('refresh');
			
            $('#food_lic_n').val('');
            $('#StationName').val('');
            $('#opening_b').val('');
            $('#opening_b').removeAttr('disabled');
            $('.saveBtn').removeAttr('disabled');
            var today = new Date();
            var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
            $('#StartDate').val(date);
            
            $('select[name=gst_type]').val('1');
            $('.selectpicker').selectpicker('refresh');
			
            $('select[name=account_group]').val('');
            $('.selectpicker').selectpicker('refresh');
            $('select[name=state]').val('');
            $('.selectpicker').selectpicker('refresh');
			
			$('select[name=Tds]').val('');
			$('.selectpicker').selectpicker('refresh');
			$('#TdsPercent1').hide();
			$('#TdsSec').hide();
			
            $("#city").children().remove();
            $('.selectpicker').selectpicker('refresh');
			
            $('select[name=active]').val('1');
            $('.selectpicker').selectpicker('refresh'); 
            
            $("#Itemstbody tr.addedtr").remove();
            $("#Distbody tr.addedtrDis").remove();
            
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
		}
		// Empty and open create mode
        $("#AccountID").focus(function(){
            ResetForm();
		});
        
		// Cancel selected data
        $(".cancelBtn").click(function(){
            ResetForm();
		});
        
		
        
        $('.get_AccountID').on('click',function(){ 
            AccountID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>purchase/GetAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID},
                beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
                complete: function () {
					$('.searchh2').css('display','none');
				},
                success:function(data){
					$('#AccountID').val(data.AccountID);
					$('#AccountName').val(data.company);
					$('#address').val(data.address);
					$('#address2').val(data.Address3);
					$('#zip').val(data.zip);
					$('#phonenumber').val(data.phonenumber);
					$('#altphonenumber').val(data.altphonenumber);
					$('#email').val(data.email);
					$('#vat').val(data.vat);
					$('#pan').val(data.Pan);
					
					$('#contact_person').val(data.contact_person);
					$('#contact_person_designation').val(data.contact_person_designation);
					$('#contact_person_mobile').val(data.contact_person_mobile);
					$('#contact_person_whatsapp').val(data.contact_person_mobile2);
					$('#upi_no').val(data.upi_id);
					$('#bank_ac_no').val(data.acc_no);
					$('#ac_holder_name').val(data.acc_name);
					$('#ifsc_code').val(data.ifsc_code);
					$('#bank_name').val(data.bank_name);
					$('#bank_branch').val(data.bank_branch);
					$('#bank_address').val(data.bank_add);
					$('#vendor_type').val(data.SubActGroupID);
					
					$('select[name=vendor_type]').attr('disabled',true);
					$('.selectpicker').selectpicker('refresh');
					
					$('#credit_days').val(data.credit_limit);
					$('#pay_term').val(data.payment_term);
					$('.selectpicker').selectpicker('refresh');
					
					$('#food_lic_n').val(data.FLNO1);
					$('#StationName').val(data.StationName);
					$('#opening_b').val(data.BAL1);
					var UserID = $('#UserID').val();
					<?php
							    if(has_permission_new('openingbaledit', '', 'edit')){
							?>
							    var is_accessable = 1;
							<?php
							    }else{
							 ?>
							    var is_accessable = 0;
							 <?php
							    }
							?>
			if(is_accessable == "0"){
						$('#opening_b').attr('disabled','disabled');    
					}
					if(data.StartDate !== '' && data.StartDate !== null){
						StartDate = data.StartDate.substr(0, 10);
						StartDate = StartDate.split("-").reverse().join("/");
						}else{
						StartDate = '';
					}
					$('#StartDate').val(StartDate);
					
					if (data.IsTDS == "1") {
						$('#TdsPercent1').show();
						$('#TdsSec').show();
						$('select[name=Tds]').val(data.IsTDS);
						$('.selectpicker').selectpicker('refresh');
						$('select[name=Tdsselection]').val(data.TDSSection);
						$('.selectpicker').selectpicker('refresh');
						$('#TdsPercent').empty();
						if (data && data.TDSPerList.length > 0) {
							$('#TdsPercent').append('<option value="">Non Selected</option>');
							$.each(data.TDSPerList, function(index, item) {
								$('#TdsPercent').append('<option value="' + item.rate + '">' + item.rate + '</option>');
							});
							} else {
							$('#TdsPercent').append('<option value="">Non Selected</option>');
						}
						
						$('select[name=TdsPercent]').val(data.TDSPer);
						$('.selectpicker').selectpicker('refresh');
						
						} else {
						$('#TdsPercent1').hide();
						$('#TdsSec').hide();
						$('select[name=Tdsselection]').val('');
						$('.selectpicker').selectpicker('refresh'); // Optionally, clear the TDS percent input if Tds is not "1"
						$('select[name=TdsPercent]').val('');
						$('.selectpicker').selectpicker('refresh');
					}
					
					$('select[name=gst_type]').val(data.gsttype);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=state]').val(data.state);
					$('.selectpicker').selectpicker('refresh');
					
					$("#city").children().remove();
					var sel = '';
					for (i = 0; i < data.cityList.length; i++) {
                        $('#city').append('<option value="' + data.cityList[i]['id'] + '" '+sel+'>' + data.cityList[i]['city_name'] + '</option>');        
					}
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=city]').val(data.city);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=active]').val(data.active);
					$('.selectpicker').selectpicker('refresh');
					
					
					var ItemData=data.ItemData;
					populateItemData(ItemData);
					
					var DiscData=data.DiscData;
					populateDiscData(DiscData);
					
					$('.saveBtn').hide();
					$('.updateBtn').show();
					$('.saveBtn2').hide();
					$('.updateBtn2').show();
				}
			});
            $('#Vendor_List').modal('hide');
		});
		
		function populateDiscData(DiscData) 
		{
			// Clear existing rows except the template row
			$("#Distbody tr.addedtrDis").remove();
			// Clear any input values in the template row (optional)
			$("#DisDays").val('');
			$("#DisPercentage").val('');
			
			const existingDisc = new Set();
			
			// Populate rows based on vendors data
			DiscData.forEach(function (DiscData) {
				// Check if the vendor ID already exists in the Set
				if (!existingDisc.has(DiscData.Days)) {
					// Add the vendor ID to the Set
					existingDisc.add(DiscData.Days);
					// Create a new row for the vendor
					var newRow = $("<tr class='addedtrDis'></tr>");
					newRow.append("<td><input type='hidden' name='DisDays[]' class='form-control' value='" + DiscData.Days + "'>" + DiscData.Days + "</td>");
					newRow.append("<td><input type='hidden' name='DisPercentage[]' class='form-control' value='" + DiscData.Percentage + "' readonly>" + DiscData.Percentage + "</td>");
					newRow.append("<td><a href='#' class='btn btn-danger removebtnDis'><i class='fa fa-times'></i></a></td>");
					
					// Append the new row to the table body
					$("#Distbody").append(newRow);
				}
			});
		}
		function populateItemData(ItemsData) 
		{
			// Clear existing rows except the template row
			$("#Itemstbody tr.addedtr").remove();
			// Clear any input values in the template row (optional)
			$("#ItemID").val('');
			$("#unit").val('');
			$("#CaseQty").val('');
			$("#DeliveryDays").val('');
			$("#item_status").val('Y');
			$('#item_status').selectpicker('refresh');
			// Create a Set to store unique vendor IDs
			const existingItemIds = new Set();
			
			// Populate rows based on vendors data
			ItemsData.forEach(function (ItemsData) {
				// Check if the vendor ID already exists in the Set
				if (!existingItemIds.has(ItemsData.ItemID)) {
					// Add the vendor ID to the Set
					existingItemIds.add(ItemsData.ItemID);
					
					if(ItemsData.status == 'Y'){
						StatusVal = 'Enable'
					}
					// Create a new row for the vendor
					var newRow = $("<tr class='addedtr'></tr>");
					newRow.append("<td><input type='hidden' name='ItemID[]' class='form-control' value='" + ItemsData.ItemID + "'>" + ItemsData.description + "</td>");
					newRow.append("<td><input type='text' name='unit[]' class='form-control' value='" + ItemsData.unit + "' readonly></td>");
					newRow.append("<td><input type='text' name='CaseQty[]' class='form-control' readonly value='" + ItemsData.CaseQty + "'></td>");
					newRow.append("<td><input type='text' name='DeliveryDays[]' class='form-control' value='" + ItemsData.DeliveryDays + "'></td>");
					newRow.append(
					"<td><select name='item_status[]' class='form-control'>" +
					"<option value='Y' " + (ItemsData.status === 'Y' ? 'selected' : '') + ">Enable</option>" +
					"<option value='N' " + (ItemsData.status === 'N' ? 'selected' : '') + ">Disable</option>" +
					"</select></td>"
					);
					newRow.append("<td></td>");
					
					// Append the new row to the table body
					$("#Itemstbody").append(newRow);
				}
			});
		}
        
		// Save New New Vendor
        $('.saveBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            company = $('#AccountName').val();
            gsttype = $('#gst_type').val();
            state = $('#state').val();
            city = $('#city').val();
            address = $('#address').val();
            Address3 = $('#address2').val();
            zip = $('#zip').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            vat = $('#vat').val();
            Pan = $('#pan').val();
			
			contact_person = $('#contact_person').val();
		    contact_person_designation = $('#contact_person_designation').val();
		    contact_person_mobile = $('#contact_person_mobile').val();
		    contact_person_whatsapp = $('#contact_person_whatsapp').val();
		    upi_no = $('#upi_no').val();
		    bank_ac_no = $('#bank_ac_no').val();
		    ac_holder_name = $('#ac_holder_name').val();
		    ifsc_code = $('#ifsc_code').val();
            bank_name = $('#bank_name').val();
            bank_branch = $('#bank_branch').val();
            bank_address = $('#bank_address').val();
            vendor_type = $('#vendor_type').val();
            pay_term = $('#pay_term').val();
            credit_days = $('#credit_days').val();
			
            FLNO1 = $('#food_lic_n').val();
            StationName = $('#StationName').val();
            BAL1 = $('#opening_b').val();
            active = $('#active').val();
            StartDate = $('#StartDate').val();
			
			let ItemData = [];
			$("#Itemstbody tr").each(function() {
				let ItemID = $(this).find("input[name='ItemID[]']").val();
				let DeliveryDays = $(this).find("input[name='DeliveryDays[]']").val();
				let item_status = $(this).find("select[name='item_status[]'], input[name='item_status[]']").val();
				
				if(ItemID != '' && ItemID != null){
					ItemData.push({ ItemID: ItemID, item_status: item_status,DeliveryDays:DeliveryDays });
				}
			});
			
			let ItemDataNew = JSON.stringify(ItemData);
			
			let DisData = [];
			$("#Distbody tr").each(function() {
				let DisDays = $(this).find("input[name='DisDays[]']").val();
				let DisPercentage = $(this).find("input[name='DisPercentage[]']").val();
				
				if(DisDays != '' && DisPercentage != null){
					DisData.push({ DisDays: DisDays, DisPercentage: DisPercentage });
				}
			});
			
			let DisDataNew = JSON.stringify(DisData);
			let Tds = $('#Tds').val();
			let TdsPercent = '';
			let Tdsselection='';
			if (Tds === '1') {
				TdsPercent = $('#TdsPercent').val();
				Tdsselection = $('#Tdsselection').val();
			}
            if(AccountID == ''){
                alert('please enter AccountID');
                $('.saveBtn').removeAttr('disabled');
                $('#AccountID').focus();
				}else if($.trim(company) == ''){
                alert('please enter Account Name');
                $('.saveBtn').removeAttr('disabled');
                $('#AccountName').focus();
				}else if(vendor_type == ''){
                alert('please select Vendor Type');
                $('.saveBtn').removeAttr('disabled');
                $('#vendor_type').focus();
				}else if(state == ''){
                alert('please select State');
                $('.saveBtn').removeAttr('disabled');
                $('#state').focus();
				}else if(city == ''){
                alert('please select City');
                $('.saveBtn').removeAttr('disabled');
                $('#city').focus();
				}else if(phonenumber == ''){
                alert('please  enter mobile number');
                $('.saveBtn').removeAttr('disabled');
                $('#phonenumber').focus();
				}else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
                alert('Enter valid Mobile number');
                $('.saveBtn').removeAttr('disabled');
                $('#phonenumber').focus();
				}else if(gsttype == ''){
                alert('please select Gst Type');
                $('.saveBtn').removeAttr('disabled');
                $('#gst_type').focus();
				}else if(parseInt(gsttype) == '1' && vat == ''){
                alert('Enter valid GST number');
                $('.saveBtn').removeAttr('disabled');
                $('#gst_type').focus();
				}else if(!$('#pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#pan').val() !== ""){
                alert('Enter valid PAN number');
                $('.saveBtn').removeAttr('disabled');
                $('#pan').focus();
				}else if(!$('#vat').val().match('[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z][0-9][0-9A-Za-z]{2}') && $('#vat').val() !== ""){
                alert('Enter valid GST number');
                $('.saveBtn').removeAttr('disabled');
                $('#vat').focus();
				}else if(!$('#email').val().match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/) && $('#email').val() !== ""){
                alert('Enter valid Email-id');
                $('.saveBtn').removeAttr('disabled');
                $('#email').focus();
				}else if(pay_term == '' || pay_term == null){
                alert('please select Payment Term');
                $('.saveBtn').removeAttr('disabled');
                $('#pay_term').focus();
				}else{
                $.ajax({
					url:"<?php echo admin_url(); ?>purchase/SaveVendor",
					dataType:"JSON",
					method:"POST",
					data:{AccountID:AccountID,company:company,gsttype:gsttype,
						state:state,city:city,address:address,Address3:Address3,zip:zip,phonenumber:phonenumber,
						altphonenumber:altphonenumber,email:email,vat:vat,Pan:Pan,contact_person:contact_person,contact_person_designation:contact_person_designation,contact_person_mobile:contact_person_mobile,contact_person_whatsapp:contact_person_whatsapp,upi_no:upi_no,bank_ac_no:bank_ac_no,ac_holder_name:ac_holder_name,ifsc_code:ifsc_code,bank_name:bank_name,bank_branch:bank_branch,bank_address:bank_address,vendor_type:vendor_type,
						FLNO1:FLNO1,StationName:StationName,BAL1:BAL1,active:active,StartDate:StartDate,pay_term:pay_term,credit_days:credit_days,ItemData:ItemDataNew,Tds:Tds,TdsPercent:TdsPercent,Tdsselection:Tdsselection,DisData:DisDataNew
					},
					beforeSend: function () {
						$('.searchh3').css('display','block');
						$('.searchh3').css('color','blue');
					},
					complete: function () {
						$('.searchh3').css('display','none');
					},
					success:function(data){
						if(data == true){
							alert_float('success', 'Record created successfully...');
							location.reload();
							}else{
							alert_float('warning', 'Something went wrong...');
							$('.saveBtn').removeAttr('disabled');
						}
					}
				});
			}
		});
		// Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            company = $('#AccountName').val();
            gsttype = $('#gst_type').val();
            state = $('#state').val();
            city = $('#city').val();
            address = $('#address').val();
            Address3 = $('#address2').val();
            zip = $('#zip').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            vat = $('#vat').val();
            Pan = $('#pan').val();
			
			contact_person = $('#contact_person').val();
		    contact_person_designation = $('#contact_person_designation').val();
		    contact_person_mobile = $('#contact_person_mobile').val();
		    contact_person_whatsapp = $('#contact_person_whatsapp').val();
		    upi_no = $('#upi_no').val();
		    bank_ac_no = $('#bank_ac_no').val();
		    ac_holder_name = $('#ac_holder_name').val();
		    ifsc_code = $('#ifsc_code').val();
            bank_name = $('#bank_name').val();
            bank_branch = $('#bank_branch').val();
            bank_address = $('#bank_address').val();
            pay_term = $('#pay_term').val();
            credit_days = $('#credit_days').val();
			
            FLNO1 = $('#food_lic_n').val();
            StationName = $('#StationName').val();
            BAL1 = $('#opening_b').val();
            active = $('#active').val();
            StartDate = $('#StartDate').val();
			
			let ItemData = [];
			$("#Itemstbody tr").each(function() {
				let ItemID = $(this).find("input[name='ItemID[]']").val();
				let DeliveryDays = $(this).find("input[name='DeliveryDays[]']").val();
				let item_status = $(this).find("select[name='item_status[]'], input[name='item_status[]']").val();
				
				if(ItemID != '' && ItemID != null){
					ItemData.push({ ItemID: ItemID, item_status: item_status,DeliveryDays:DeliveryDays });
				}
			});
			
			let ItemDataNew = JSON.stringify(ItemData);
			
			let DisData = [];
			$("#Distbody tr").each(function() {
				let DisDays = $(this).find("input[name='DisDays[]']").val();
				let DisPercentage = $(this).find("input[name='DisPercentage[]']").val();
				
				if(DisDays != '' && DisPercentage != null){
					DisData.push({ DisDays: DisDays, DisPercentage: DisPercentage });
				}
			});
			
			let DisDataNew = JSON.stringify(DisData);
			
			let Tds = $('#Tds').val();
			let TdsPercent = '';
			let Tdsselection='';
			if (Tds === '1') {
				TdsPercent = $('#TdsPercent').val();
				Tdsselection = $('#Tdsselection').val();
			}
            if(AccountID == ''){
                alert('please enter AccountID');
                $('.updateBtn').removeAttr('disabled');
                $('#AccountID').focus();
				}else if($.trim(company) == ''){
                alert('please enter Account Name');
                $('.updateBtn').removeAttr('disabled');
                $('#AccountName').focus();
				}else if(state == ''){
				alert('please select State');
				$('.updateBtn').removeAttr('disabled');
				$('#state').focus();
				}else if(city == ''){
				alert('please select City');
				$('.updateBtn').removeAttr('disabled');
				$('#city').focus();
				}else if(phonenumber == ''){
				alert('please  enter mobile number');
				$('.updateBtn').removeAttr('disabled');
				$('#phonenumber').focus();
				}else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
				alert('Enter valid Mobile number');
				$('.updateBtn').removeAttr('disabled');
				$('#phonenumber').focus();
				}else if(gsttype == ''){
				alert('please select Gst Type');
				$('.updateBtn').removeAttr('disabled');
				$('#gst_type').focus();
				}else if(parseInt(gsttype) == '1' && vat == ''){
				alert('Enter valid GST number');
				$('.updateBtn').removeAttr('disabled');
				$('#gst_type').focus();
				}else if(!$('#vat').val().match('[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z][0-9][0-9A-Za-z]{2}') && $('#vat').val() !== ""){
				alert('Enter valid GST number');
				$('.saveBtn').removeAttr('disabled');
				$('#vat').focus();
				}else if(!$('#pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#pan').val() !== ""){
				alert('Enter valid PAN number');
				$('.updateBtn').removeAttr('disabled');
				$('#pan').focus();
				}else if(!$('#email').val().match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/) && $('#email').val() !== ""){
				alert('Enter valid Email-id');
				$('.updateBtn').removeAttr('disabled');
				$('#email').focus();
				}else if(pay_term == '' || pay_term == null){
				alert('please select Payment Term');
				$('.updateBtn').removeAttr('disabled');
				$('#pay_term').focus();
				}else{
				
				$.ajax({
					url:"<?php echo admin_url(); ?>purchase/UpdateVendor",
					dataType:"JSON",
					method:"POST",
					data:{AccountID:AccountID,company:company,gsttype:gsttype,
						state:state,city:city,address:address,Address3:Address3,zip:zip,phonenumber:phonenumber,
						altphonenumber:altphonenumber,email:email,vat:vat,Pan:Pan,contact_person:contact_person,contact_person_designation:contact_person_designation,contact_person_mobile:contact_person_mobile,contact_person_whatsapp:contact_person_whatsapp,upi_no:upi_no,bank_ac_no:bank_ac_no,ac_holder_name:ac_holder_name,ifsc_code:ifsc_code,bank_name:bank_name,bank_branch:bank_branch,bank_address:bank_address,
						FLNO1:FLNO1,StationName:StationName,BAL1:BAL1,active:active,StartDate:StartDate,pay_term:pay_term,credit_days:credit_days,ItemData:ItemDataNew,Tds:Tds,TdsPercent:TdsPercent,Tdsselection:Tdsselection,DisData:DisDataNew
					},
					beforeSend: function () {
						$('.searchh4').css('display','block');
						$('.searchh4').css('color','blue');
					},
					complete: function () {
						$('.searchh4').css('display','none');
					},
					success:function(data){
						if(data == true){
							alert_float('success', 'Record updated successfully...');
							location.reload();
							}else{
							alert_float('warning', 'Something went wrong...');
							$('.updateBtn').removeAttr('disabled');
						}
					}
				});
			}
		});
	});
</script>

 <script>
$(document).ready(function() {
    $("#vat").on("blur", function() {
        let GstNo = $(this).val().trim();
        if(GstNo === "") return;

        $.ajax({
            url: "<?php echo admin_url('clients/validate_gst_mastergst'); ?>",
            type: "POST",
            dataType: "json",
            data: { GstNo: GstNo },
            success: function(res) {
			if(res.Status){
				$("#AccountName").val(res.GstData.legalName);
				$("#address").val(res.GstData.address1);
				$("#address2").val(res.GstData.address2.substring(6));
				$("#zip").val(res.GstData.pinCode);
			}else{
				alert('Invalid Gst No.');
				$("#vat").val('');
				$("#AccountName").val('');
			}
               
            },
        });
    });
});
</script>
<script>
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_Vendor_List");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						}else if(td2){
						txtValue = td2.textContent || td2.innerText;
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
</script>
<script>
	
	$('#ifsc_code').blur(function(){
		var ifsc_code = $('#ifsc_code').val();
		$.ajax({
			url:"<?php echo admin_url(); ?>purchase/fetchBankDetailsFromIFSC",
			method:"POST",
			dataType:'json',
			data:{ifsc_code:ifsc_code},
			beforeSend: function () {
				$('.searchh6').css('display','block');
				
				$('.searchh6').css('color','blue');
			},
			complete: function () {
				$('.searchh6').css('display','none');
			},
			success:function(data){
				if(data == "Not Found"){
					alert("Enter valid IFSC Code");
					$('#bank_name').val("");
					$('#bank_branch').val("");
					$('#bank_address').val("");
					}else{
					$('#bank_name').val(data.BANK);
					$('#bank_branch').val(data.BRANCH);
					$('#bank_address').val(data.ADDRESS);
				}
			}
		});
	});
	
	$('#bank_ac_no').blur(function(){
		var bank_ac_no = $('#bank_ac_no').val();
		var ifsc_code = $('#ifsc_code').val();
		$.ajax({
			url:"<?php echo admin_url(); ?>purchase/verifyBankAccount",
			method:"POST",
			dataType:'json',
			data:{bank_ac_no:bank_ac_no,ifsc_code:ifsc_code},
			beforeSend: function () {
				$('.searchh6').css('display','block');
				
				$('.searchh6').css('color','blue');
			},
			complete: function () {
				$('.searchh6').css('display','none');
			},
			success:function(data){
				if(data.success == false){
					alert("Bank account not verified");
					
					$('#ac_holder_name').val('');
					$('#bank_ac_no').val('');
					}else{
					$('#ac_holder_name').val(data.data.full_name);
				}
			}
		});
	});
    function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode = 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57)){
			return false;
		}
		return true;
	}
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_Vendor_List tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortablePop").removeClass("asc desc");
		$(".sortablePop span").remove();
		
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
<style>
	
	#AccountID {
    text-transform: uppercase;
	}
	#pan {
    text-transform: uppercase;
	}
	#table_Vendor_List td:hover {
    cursor: pointer;
	}
	#table_Vendor_List tr:hover {
    background-color: #ccc;
	}
	
    .table-Vendor_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Vendor_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Vendor_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>	