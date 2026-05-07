<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-10">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Master</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Customer</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait while fetching data.</div>
								<div class="searchh3" style="display:none;">Please wait while creating new record.</div>
								<div class="searchh4" style="display:none;">Please wait while updating data.</div>
							</div>
							<br>
							<div class="col-md-3">
								<?php
									$selected_company = $this->session->userdata('root_company');
									$next_cust_number = (int) get_option('next_customer_number');
									
									$prefix = "C";
									
									$next_cust_number = $prefix.str_pad($next_cust_number,5,'0',STR_PAD_LEFT);
								?>
								<div class="form-group" app-field-wrapper="AccountID">
									<small class="req text-danger">* </small>
									<label for="AccountID" class="control-label">Account ID</label>
									<input type="text" id="AccountID" value="<?= $next_cust_number?>" name="AccountID" class="form-control" value="" readonly>
									<?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
									<?php if(is_admin()) {$is_admin = 1;}else{$is_admin = 0;} ?>
									<input type="hidden" name="staffid" value="<?php echo $staff_user_id; ?>" id="staffid">
									<input type="hidden" name="HiddenAccountID" value="<?php echo $next_cust_number; ?>" id="HiddenAccountID">
									<input type="hidden" name="is_admin" value="<?php echo $is_admin; ?>" id="is_admin">
									<input type="hidden" name="PlantID" value="<?php echo $this->session->userdata('root_company');?>" id="PlantID">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="AccoountName">
									<small class="req text-danger">* </small>
									<label for="AccoountName" class="control-label">Account Name</label>
									<input type="text" id="AccoountName" name="AccoountName" class="form-control" value="" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="firstname">
									<small class="req text-danger"> </small>
									<label for="firstname" class="control-label">First Name</label>
									<input type="text" id="firstname" name="firstname" class="form-control" value="" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="lastname">
									<small class="req text-danger"> </small>
									<label for="lastname" class="control-label">Last Name</label>
									<input type="text" id="lastname" name="lastname" class="form-control" value="" >
								</div>
							</div>
							
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label" for="title">Position</label>
									<select class="selectpicker display-block" data-width="100%" name="title" id="title" data-none-selected-text="--Select--">
										<option value="Owner">Owner</option>
										<option value="Employee">Employee</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="subgroup">
									<small class="req text-danger">* </small>
									<label for="subgroup" class="form-label">Sub group</label>
									<select name="subgroup" id="subgroup" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($accountgroupssub as $key => $value) {
											?>
											<option value="<?php echo $value['SubActGroupID'];?>"><?php echo $value['SubActGroupName'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="groups_in">
									<small class="req text-danger">* </small>
									<label for="groups_in" class="form-label">Distributor Type</label>
									<select name="groups_in" id="groups_in" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($groups as $key => $value) {
											?>
											<option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Pan"> 
									<label for="Pan" class="control-label">PAN Number</label>
									<input type="text" maxlength="10" minlength="10" name="Pan" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="Pan" class="form-control" 
									value="">
									<span class="pan_denger" style="color:red;"></span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Aadhaarno">
									<label for="aadhaar" class="control-label">Aadhar Number</label>
									<input type="text" maxlength="12" minlength="12"  name="Aadhaarno" pattern="[0-9] {12}" id="Aadhaarno" class="form-control numbersOnly" onkeypress="return isNumber(event)" value="">
									<span class="aadhar_denger" style="color:red;"></span>
								</div>
							</div>
							<div class="col-md-3 gst-section">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="Blockyn">GST Type</label>
									<select name="gst_type" id="gst_type" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										<option value="1" >Registered</option>
										<option value="2">Un-Registered</option>
										<option value="3" >Composition</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 gst-section">
								<div class="form-group" app-field-wrapper="vat">
									<label for="vat" class="control-label">GST Number</label>
									<input type="text" id="vat" name="vat" class="form-control" pattern="([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([0-9A-Za-z]){2}" maxlength="15" minlength="15" value="">
									<span class="gst_denger" style="color:red;"></span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="phonenumber">
									<small class="req text-danger">* </small>
									<label for="phonenumber" class="control-label">Mobile Number</label>
									<input type="text" id="phonenumber" name="phonenumber" class="form-control" value="" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="altphonenumber">
									<label for="altphonenumber" class="control-label">Alt Mobile Number</label>
									<input type="text" id="altphonenumber" name="altphonenumber" class="form-control" value="" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="email">
									<label for="email" class="control-label">Email Address</label>
									<input type="text" id="email" name="email" class="form-control" value="">
									
									<span class="email_error" style="color:red;"></span>
								</div>
							</div>
							
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="country">
									<small class="req text-danger">* </small>
									<label for="country" class="form-label">Country</label>
									<select name="country" id="country" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<?php if(isset($countries)){ ?>
											<?php foreach ($countries as $c) { ?>
												<option value="<?php echo $c['country_id']; ?>"><?php echo $c['short_name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								
								<div class="form-group" app-field-wrapper="state">
									<small class="req text-danger">* </small>
									<label for="state" class="form-label">State</label>
									<input type="hidden"  name="hiddenState" id="hiddenState" value="">
									<select name="state" id="state" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($state as $key => $value) {
											?>
											<option value="<?php echo $value['short_name'];?>"><?php echo $value['state_name'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label for="city" class="control-label">City</label>
									<select class="form-control city selectpicker" data-width="100%" data-none-selected-text="None selected" name="city" id="city" data-live-search="true">
										<option value="">None selected</option>
									</select>
									
								</div>
							</div>
							<div class="col-md-3">
								<?php echo render_input( 'address', 'Address 1'); ?>
							</div>
							
							<div class="col-md-3">
								<?php echo render_input( 'Address3', 'Address 2'); ?>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="zip">
									<label for="zip" class="control-label">Pin Code</label>
									<input type="text"  name="zip" id="zip" class="form-control" onchange="validateZipCode" value="" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
								</div>
							</div> 
							
							<div class="col-md-2">
								<label for="dis_per" class="control-label">Discount% (non-taxable)</label>
								<input type="text" id="dis_per" name="dis_per" value="0.00" class="form-control" value="" onkeypress="return isNumber2(event)">
								
							</div>
							
							<div class="col-md-2">
								<label for="dis_per_taxable" class="control-label">Disc(%) (taxable)</label>
								<input type="text" id="dis_per_taxable" name="dis_per_taxable" value="0.00" class="form-control" value="" onkeypress="return isNumber2(event)">
							</div>
							<div class="clearfix"></div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="route">
									<small class="req text-danger">* </small>
									<label for="route" class="form-label">Route</label>
									<select name="route[]" id="route" class="selectpicker form-control" multiple data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<?php
											foreach ($routes as $key => $value) {
											?>
											<option value="<?php echo $value['RouteID'];?>"><?php echo $value['name'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="route_point">
									<small class="req text-danger">* </small>
									<label for="route_point" class="form-label">Route Point</label>
									<select name="route_point" id="route_point" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value="" >None selected</option>
									</select>
								</div>
							</div>
							
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="BalancesYN">
									<label for="BalancesYN">Balance on Bill</label>
									<select name="BalancesYN" id="BalancesYN" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										<option value="N" >No</option>
										<option value="Y" >Yes</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="bill_till_bal">
									<label for="bill_till_bal" class="form-label">Bill Till Balance</label>
									<select name="bill_till_bal" id="bill_till_bal" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										<option value="N" >No</option>
										<option value="Y" >Yes</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="MaxCrdAmt">
									<label for="MaxCrdAmt" class="control-label">Max.Credit Amount</label>
									<input type="text" id="MaxCrdAmt" name="MaxCrdAmt" class="form-control numbersOnly" value="">
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label class="control-label" for="article">Article</label>
									<select class="selectpicker display-block" data-width="100%" name="article" id="article" >
										<option value="N">No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label for="istcs">TCS</label>
								<select name="istcs" id="istcs" class="selectpicker form-control tcs_type">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select>
							</div>
							
							<div class="col-md-2">
								<?php $value2 = date('d/m/Y');?>
								<?php echo render_date_input( 'TcsStartDate1', 'TCS Date',$value2,'text'); ?>
								<input type="hidden" name="TcsStartDate" value="">
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="location_type">
									<small class="req text-danger">* </small>
									<label for="location_type" class="control-label">Location Type</label>
									<select name="location_type" id="location_type" class="selectpicker form-control"  data-none-selected-text="None selected" data-width="100%"  data-live-search="true">
										<option value="" ></option>
										<option value="1" >Local</option>
										<option value="2" >OutStation</option>
										<option value="3" >Not Defined</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="ActSalestype">
									<small class="req text-danger">* </small>
									<label for="ActSalestype" class="form-label">Sales Type</label>
									<select name="ActSalestype" id="ActSalestype" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										
										<option value="Sales">Sales</option>
										<option value="CNF">CNF</option>
										<option value="StockTransfer">Stock Transfer</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label for="SalesFrequency">Sales Frquency</label>
								<select name="SalesFrequency" id="SalesFrequency" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
									<option value="0">Weekly</option>
									<option value="1">Bi-Weekly</option>
									<option value="2">Monthly</option>
									<option value="3">Quaterly</option>
								</select>
							</div>
							
							<div class="clearfix"></div>
							<div class="col-md-2">
								<label for="cd">CD</label>
								<select name="cd" id="cd" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
									<option value="N" >No</option>
									<option value="Y" >Yes</option>
								</select>
							</div>
							
							<div class="col-md-2">
								<label for="rate_print">Rate Print</label>
								<select name="rate_print" id="rate_print" class="selectpicker form-control"  data-live-search="true">
									<option value="Y" >Yes</option>
									<option value="N" >No</option>
								</select>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="kms">
									<label for="kms" class="control-label">Kms</label>
									<input id="kms" type="text" maxlength="7"  name="kms" class="form-control" value="" aria-invalid="false">
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="crate_limit">
									<label for="crate_limit" class="control-label">Crate Limit</label>
									<input id="crate_limit" type="text" name="crate_limit" class="form-control" value="" aria-invalid="false">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="credit_days">
									<label for="credit_days" class="control-label">Credit Days</label>
									<input id="credit_days" type="text" maxlength="2"  name="credit_days" class="form-control" value="" aria-invalid="false">
								</div>
							</div>
							
							
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="FLNO1">
									<label for="FLNO1" class="control-label">Food Licence Number</label>
									<input type="text" maxlength="14" minlength="14" id="FLNO1" name="FLNO1" class="form-control" value="" onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-2">
								<?php $value2 = date('d/m/Y');?>
								<?php echo render_date_input( 'expiry_licence', 'Licence Expiry',$value2,'text'); ?>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="StationName">
									<small class="req text-danger">* </small>
									<label for="StationName" class="form-label">Station Name</label>
									<select name="StationName" id="StationName" class="selectpicker form-control"  data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<?php
											foreach ($StationList as $key => $value) {
											?>
											<option value="<?php echo $value['id'];?>"><?php echo $value['StationName'];?></option>
											<?php
											}
										?>
									</select>
								</div>							
							</div>
							
							<!--<div class="clearfix"></div>-->
							<div class="col-md-2">
								<label for="Blockyn">Block A/C</label>
								<select name="Blockyn" id="Blockyn" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
									<option value="N" >No</option>
									<option value="Y" >Yes</option>
								</select>
							</div>
							
							<div class="col-md-2">
								<?php $value = date('d/m/Y');?>
								<?php echo render_date_input( 'StartDate', 'Start Date',$value,'text'); ?>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="active">
									<label for="active" class="form-label">Status</label>
									<select name="active" id="active" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true">
										<option value="1" >Active</option>
										<option value="0" >InActive</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="FreshReturn">
									<small class="req text-danger"> </small>
									<label for="FreshReturn" class="control-label">Fresh Return %</label>
									<input type="text" id="FreshReturn" name="FreshReturn" class="form-control" onkeypress="return isNumber2(event)" value="">
								</div>							
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="DamageReturn">
									<small class="req text-danger"> </small>
									<label for="DamageReturn" class="control-label">Damage Return %</label>
									<input type="text" id="DamageReturn" name="DamageReturn" class="form-control" onkeypress="return isNumber2(event)" value="">
								</div>							
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Latitude">
									<small class="req text-danger"> </small>
									<label for="Latitude" class="control-label">Latitude</label>
									<input type="text" id="Latitude" name="Latitude" class="form-control" onkeypress="return isNumber2(event)" value="">
								</div>							
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Longitude">
									<small class="req text-danger"> </small>
									<label for="Longitude" class="control-label">Longitude</label>
									<input type="text" id="Longitude" name="Longitude" class="form-control" onkeypress="return isNumber2(event)" value="">
								</div>							
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label" for="TradeType">Trade Type</label>
									<select class="selectpicker display-block" data-width="100%" name="TradeType" id="TradeType" data-none-selected-text="None Selected">
										<option value="">None Selected</option>
										<option value="General">General Trade</option>
										<option value="Modern">Modern Trade</option>
										<option value="Online">Online Trade</option>
									</select>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="row">
							
							
							<div class="col-md-7">
								<?php 
									$company_assigned = $client->company_assigned; 
									$company_assigned_new = unserialize($company_assigned);
									
									$company_assigned_staff = $client->company_assigned_staff; 
									$company_assigned_staff_new = unserialize($company_assigned_staff);
									
									$opening_bal = $client->opening_bal; 
									$opening_bal_new = unserialize($opening_bal);
									
									$drcr = $client->drcr; 
									$drcr_new = unserialize($drcr);
								?>
								
								<table class="table scroll-responsive">
									<thead >
										<tr>
											<th style="color:#fff;">#</th>
											<th style="color:#fff;">Company</th>
											<th style="color:#fff;">Sales Person Name</th>
											<th style="color:#fff;width: 100px;">Opening Bal</th>
											<th style="color:#fff;">DR/CR</th>
										</tr>
										
									</thead>
									<tbody>
										<?php foreach($rootcompany as $r_company){ ?>
											<tr>
												
												<td><div class="checkbox"><input type="checkbox" class="company_assigned" name="company_assigned" id="company_assigned<?php echo $r_company["id"];?>" value="<?php echo $r_company["id"];?>"><label></label></div></td>
												<td><?php echo $r_company["company_name"];?></td>
												<td> 
													<div class="dropdown bootstrap-select form-control bs3 company_assigned_staff">
														<select name="company_assigned_staff" id="company_assigned_staff<?php echo $r_company["id"];?>" class="form-control selectpicker" tabindex="-98" data-none-selected-text="None selected" data-live-search="true">
															<option></option>
															<?php
																foreach ($staff as $sskey => $ssvalue) {
																	# code...
																	$staff_comp = $ssvalue["staff_comp"];
																	$company_array = unserialize($staff_comp);
																	$StaffPlant = $ssvalue["PlantID"];
																	//if (in_array($r_company["id"], $company_array)){
																	if ($r_company["id"] == $StaffPlant){
																	?>
																	<option value="<?php echo $ssvalue['AccountID'];?>"><?php echo $ssvalue["firstname"]." ".$ssvalue["lastname"]?></option>
																	<?php
																	}
																} ?>
																
														</select>
													</div>
												</td>
												<td>
													
													<?php
														$staff_user_id = $this->session->userdata('staff_user_id');
														
														if(has_permission_new('openingbaledit', '', 'edit')){
															$isAccessable = 1;
															}else{
															$isAccessable = 0;
														}
													?>
													<input class="form-control opening_bal" type="text" name="opening_bal" id="opening_bal<?php echo $r_company["id"];?>" value=""  style="height: 25px;font-size: 12px;">
												</td>
												<td>
													<select name="drcr" id="drcr<?php echo $r_company["id"];?>" class="form-control selectpicker drcr" <?php if(isset($client) && $staff_user_id !== "3"){ echo "disabled";}?>>
														<option value="DR" >DR</option>
														<option value="CR" >CR</option>
													</select>
													
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-7">
								<p class="bold p_style">Shipping Wise Details</p>
								<hr class="hr_style">
								<table width="100%">
									<thead>
										<tr>
											<th>Shipping State</th>
											<th>Shipping City</th>
											<th>Shipping Address</th>
											<th>Shipping Pin</th>
											<th>Add New</th>
										</tr>
									</thead>
									<tbody id="addresstbody">
										<tr>
											<td>
												<select name="shipping_state[]" id="shipping_state" class="selectpicker form-control" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
													<option value="">None selected</option>
													<?php foreach ($state as $key => $value) { ?>
														<option value="<?php echo $value['short_name'];?>"><?php echo $value['state_name'];?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-control city selectpicker" data-width="100%" data-none-selected-text="None selected" name="shipping_city[]" id="shipping_city" data-live-search="true">
													<option value="">None selected</option>
												</select>
											</td>
											<td style="text-align:center;">
												<input type="text" id="ShippingAdrees" name="ShippingAdrees[]" class="form-control">
											</td>
											<td>
												<input type="text" id="ShippingPin" name="ShippingPin[]" class="form-control">
											</td>
											<td>
												<a class="btn btn-success" onclick="addRow()"><i class="fa fa-plus"></i></a>
											</td>
										</tr>
									</tbody>
								</table>
								
							</div>
							
						</div>
						<div class="clearfix"></div>
						<br><br>
						<div class="row">
							<div class="col-md-12">
								<?php if (has_permission_new('customers', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission_new('customers', '', 'edit')) {
								?>
								<button type="button" class="btn btn-info updateBtn" style="margin-right: 25px;">Update</button>
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
						
						<div class="modal fade Account_List" id="Account_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Account List</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-Account_List tableFixHead2">
											<table class="tree table table-striped table-bordered table-Account_List tableFixHead2" id="table_Account_List" width="100%">
												<thead>
													<tr>
														<th style="text-align:left;" class="sortablePop">AccountID</th>
														<th style="text-align:left;" class="sortablePop">Account Name</th>
														<th style="text-align:left;" class="sortablePop">First Name</th>
														<th style="text-align:left;" class="sortablePop">Last Name</th>
														<th style="text-align:left;" class="sortablePop">Distributor Type</th>
														<th style="text-align:left;" class="sortablePop">State</th>
														<th style="text-align:left;" class="sortablePop">City</th>
														<th style="text-align:left;" class="sortablePop">Status</th>
													</tr>
												</thead>
												<tbody id="customertlistbody">
													
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
	function isNumber2(evt) {
		evt = evt || window.event;
		var charCode = evt.which || evt.keyCode;
		
		// Allow decimal point (charCode 46) and numbers (charCodes 48 to 57)
		if (charCode !== 46 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		
		return true;
	}
	
    $(document).ready(function(){
        $('.saveBtn').show();
		$('.updateBtn').hide();
		$('.saveBtn2').show();
		$('.updateBtn2').hide();
		
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
	    function FormSet(data)
	    {
	        $('#AccountID').val(data.AccountID);
			$('#AccoountName').val(data.company);
			$('#firstname').val(data.firstname);
			$('#lastname').val(data.lastname);
			$('#phonenumber').val(data.phonenumber);
			$('#altphonenumber').val(data.altphonenumber);
			$('#email').val(data.email);
			$('#vat').val(data.vat);
			$('#address').val(data.address);
			$('#Address3').val(data.Address3);
			$('#zip').val(data.zip);
			$('#dis_per').val(data.dis_per);
			$('#dis_per_taxable').val(data.dis_per_taxable);
			$('#cd').selectpicker('val', data.cd);
			$('.selectpicker').selectpicker('refresh');
			$('#rate_print').selectpicker('val', data.rate_print);
			$('.selectpicker').selectpicker('refresh');
			$('#kms').val(data.kms);
			$('#credit_days').val(data.credit_days);
			$('#crate_limit').val(data.crate_limit);
			$('#FLNO1').val(data.FLNO1);
			$('#Pan').val(data.Pan);
			$('#Aadhaarno').val(data.Aadhaarno);
			
			$('select[name=subgroup]').val(data.Cust_group);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=gst_type]').val(data.gsttype);
			$('.selectpicker').selectpicker('refresh');
			$('select[name=TradeType]').val(data.Trade_Type);
			$('.selectpicker').selectpicker('refresh');
			
			if(data.TcsStartDate == null || data.TcsStartDate == '' || data.TcsStartDate == "0000-00-00 00:00:00"){
				$('#TcsStartDate1').val('');
				}else{
				var date = data.TcsStartDate.substring(0, 10)
				var date_new = date.split("-").reverse().join("/");
				$('#TcsStartDate1').val(date_new);
			}
			if(data.expiry_licence == null || data.expiry_licence == '' || data.expiry_licence == "0000-00-00 00:00:00"){
				$('#expiry_licence').val('');
				}else{
				var date = data.expiry_licence.substring(0, 10)
				var date_new = date.split("-").reverse().join("/");
				$('#expiry_licence').val(date_new);
			}
			$('#MaxCrdAmt').val(data.MaxCrdAmt);
			$('#StationName').val(data.StationName);
			$('.selectpicker').selectpicker('refresh');
			$('#FreshReturn').val(data.FreshReturn);
			$('#DamageReturn').val(data.DamageReturn);
			$('#Latitude').val(data.latitude);
			$('#Longitude').val(data.longitude);
			var date = data.StartDate.substring(0, 10)
			var date_new = date.split("-").reverse().join("/");
			$('#StartDate').val(date_new);
			$('#shipping_street').val(data.shipping_street);
			$('#shipping_zip').val(data.shipping_zip);
			
			let AccountCompanyArray = data.Company;
			for(var count = 0; count < AccountCompanyArray.length; count++)
			{
				var CompanyID = AccountCompanyArray[count].company_id;
				$('#company_assigned'+CompanyID+'').prop('checked', true);
				
				var StaffID = AccountCompanyArray[count].staff_id
				$('select[id=company_assigned_staff'+CompanyID+']').val(StaffID);
				$('.selectpicker').selectpicker('refresh');
			}
			
			let AccountOpnBalArray = data.OpnBal;
			for(var count = 0; count < AccountOpnBalArray.length; count++)
			{
				var PlantID = AccountOpnBalArray[count].PlantID;
				var BAL1 = AccountOpnBalArray[count].BAL1;
				$('#opening_bal'+PlantID+'').val(Math.abs(BAL1));
				if(parseFloat(BAL1) > 0){
					var DRCR = 'DR';
					}else{
					var DRCR = 'CR';
				}
				//var StaffID = AccountOpnBalArray[count].staff_id
				$('select[id=drcr'+PlantID+']').val(DRCR);
				$('.selectpicker').selectpicker('refresh');
			}
			
			let AccountRouteArray = data.Route;
			let optArr = [];
			for (var i = 0; i < AccountRouteArray.length; i++) {
				optArr.push(AccountRouteArray[i].RouteID);
			}
			$('#route').selectpicker('val', optArr);
			$('.selectpicker').selectpicker('refresh')
			
			let AccountRoutePointsList = data.AccountRoutePoints;
			$("#route_point").children().remove();
			$("#route_point").append('<option value=" ">None selected</option>');
			for (var i = 0; i < AccountRoutePointsList.length; i++) {
				$("#route_point").append('<option value="'+AccountRoutePointsList[i]["id"]+'">'+AccountRoutePointsList[i]["PointName"]+'</option>');
			}
			$('.selectpicker').selectpicker('refresh');
			if(data.RoutePoint){
			    $('#route_point').val(data.RoutePoint);
			    $('.selectpicker').selectpicker('refresh');
			}
			let CityList = data.CityList;
			$("#city").children().remove();
			for (var i = 0; i < CityList.length; i++) {
				$("#city").append('<option value="'+CityList[i]["id"]+'">'+CityList[i]["city_name"]+'</option>');
			}
			$('.selectpicker').selectpicker('refresh');
			
			$('#city').selectpicker('val', data.city);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=Blockyn]').val(data.Blockyn);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=location_type]').val(data.LocationTypeID);
			$('.selectpicker').selectpicker('refresh');
			
			
			$('select[name=article]').val(data.article);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=title]').val(data.title);
			$('.selectpicker').selectpicker('refresh');
			
			$('#groups_in').removeAttr('disabled');
			$('select[name=groups_in]').val(data.DistributorType);
			$('.selectpicker').selectpicker('refresh');
			
			if(typeof data.country !== "undefined" && data.country !== null){
				$('select[name=country]').val(data.country);
				$('.selectpicker').selectpicker('refresh');
				$('#country').trigger('change');
			}

			$('select[name=state]').val(data.state);
			$('.selectpicker').selectpicker('refresh');
			$("#hiddenState").val(data.state);
			
			$('select[name=istcs]').val(data.istcs);
			$('.selectpicker').selectpicker('refresh');
			
			
			$('select[name=BalancesYN]').val(data.BalancesYN);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=ActSalestype]').val(data.ActSalestype);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=bill_till_bal]').val(data.bill_till_bal);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=active]').val(data.active);
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=shipping_state]').val(data.shipping_state);
			$('.selectpicker').selectpicker('refresh');
			
			let CityList2 = data.CityList2;
			$("#shipping_city").children().remove();
			for (var i = 0; i < CityList2.length; i++) {
				$("#shipping_city").append('<option value="'+CityList2[i]["id"]+'">'+CityList2[i]["city_name"]+'</option>');
			}
			
			$('#shipping_city').selectpicker('val', data.shipping_city);
			$('.selectpicker').selectpicker('refresh');
			
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
				$(".opening_bal").prop("readonly", true);
			}
			
			var shipdata=data.shippingdata;
			populateVendorData(shipdata);
			
			$('.saveBtn').hide();
			$('.updateBtn').show();
			$('.saveBtn2').hide();
			$('.updateBtn2').show();
		}
		
		$("#AccountID").focus(function(){
			ResetForm();
		});
		$("#AccountID").dblclick(function(){
			$('#Account_List').modal('show');
			$.ajax({
				url:"<?php echo admin_url(); ?>clients/GetAllCustomerList",
				dataType:"JSON",
				method:"POST",
				beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
				complete: function () {
					$('.searchh2').css('display','none');
				},
				success:function(data){
					$('#customertlistbody').html(data);
					$('.get_AccountID').on('click',function(){ 
                		AccountID = $(this).attr("data-id");
                		$.ajax({
                			url:"<?php echo admin_url(); ?>clients/GetAccountDetailByID",
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
                			    FormSet(data);
							}
						});
                		$('#Account_List').modal('hide');
					});
				}
			});
			$('#Account_List').on('shown.bs.modal', function () {
                $('#myInput1').val('');
                $('#myInput1').focus();
			})
			
		});
		$("#firstname, #lastname").keypress(function (e) {
			var keyCode = e.keyCode || e.which;
			if (keyCode == "") {
				$("#lblError").html("");
				} else {
				var regex = /^[A-Za-z\s]+$/; // Updated regex to allow letters and spaces
				var isValid = regex.test(String.fromCharCode(keyCode));
				return isValid;
			}
		});
		$("#AccoountName").keypress(function (e) {
			var keyCode = e.keyCode || e.which;
			if (keyCode == "") {
				$("#lblError").html("");
				} else {
				var regex = /^[A-Za-z0-9\s]+$/; // Updated regex to allow letters and spaces
				var isValid = regex.test(String.fromCharCode(keyCode));
				return isValid;
			}
		});
		// AccountID Typing Validation
		$("#AccountID").keypress(function (e) {
			var keyCode = e.keyCode || e.which;
			if(keyCode == ""){
				$("#lblError").html("");
				}else{
				var regex = /^[A-Za-z0-9]+$/;
				var isValid = regex.test(String.fromCharCode(keyCode));
				return isValid;
			}
		});
        
        // GST Type Typing Validation
        $("#vat").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if(keyCode == ""){
                $("#lblError").html("");
				}else{
                var regex = /^[A-Za-z0-9]+$/;
                var isValid = regex.test(String.fromCharCode(keyCode));
                return isValid;
			}
		});
        
        // Pan Number Typing Validation
        $("#Pan").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if(keyCode == ""){
                $("#lblError").html("");
				}else{
                var regex = /^[A-Za-z0-9]+$/;
                var isValid = regex.test(String.fromCharCode(keyCode));
                return isValid;
			}
		});
	});
	
	
	$('#shipping_state').on('change', function() {
		var StateID = $(this).val();
		var url = "<?php echo base_url(); ?>admin/clients/GetCity";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {StateID: StateID},
            dataType:'json',
            success: function(data) {
                $("#shipping_city").find('option').remove();
                $("#shipping_city").selectpicker("refresh");
                for (var i = 0; i < data.length; i++) {
                    $("#shipping_city").append(new Option(data[i].city_name, data[i].id));
				}
                $('.selectpicker').selectpicker('refresh');
			}
		});
	});
    function ResetForm()
    {
        var HiddenAccountID = $('#HiddenAccountID').val();
        $('#AccountID').val(HiddenAccountID);
		$('#AccoountName').val('');
		$('#firstname').val('');
		$('#lastname').val('');
		$('#phonenumber').val('');
		$('#altphonenumber').val('');
		$('#email').val('');
		$('#vat').val('');
		$('#dis_per').val('0.00');
		$('#dis_per_taxable').val('0.00');
		$('#cd').selectpicker('val', 'N');
		$('.selectpicker').selectpicker('refresh');
		$('#rate_print').selectpicker('val', 'Y');
		$('.selectpicker').selectpicker('refresh');
		$('#TradeType').selectpicker('val', '');
		$('.selectpicker').selectpicker('refresh');
		$('#address').val('');
		$('#Address3').val('');
		$('#zip').val('');
		$('#kms').val('');
		$('#credit_days').val('');
		$('#crate_limit').val('');
		$('#FLNO1').val('');
		$('#Pan').val('');
		$('#Aadhaarno').val('');
		$('#MaxCrdAmt').val('');
		$('#StationName').val('');
		$('.selectpicker').selectpicker('refresh');
		
		$('#FreshReturn').val('');
		$('#DamageReturn').val('');
		$('#Latitude').val('');
		$('#Longitude').val('');
		
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		today = dd + '/' + mm + '/' + yyyy;
		$('#StartDate').val(today);
		
		$('#TcsStartDate1').val(today);
		$('#expiry_licence').val(today);
		$('#shipping_street').val('');
		$('#shipping_zip').val('');
		
		$('select[name=subgroup]').val('');
		$('.selectpicker').selectpicker('refresh');
		
		$('.company_assigned').prop('checked', false);
		
		$('select[name=company_assigned_staff]').val('');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=gst_type]').val('1');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=title]').val('Owner');
		$('.selectpicker').selectpicker('refresh');
		
		$('.opening_bal').val('');
		$('#route').selectpicker('val', '');
		$('.selectpicker').selectpicker('refresh');
		
		$("#city").children().remove();
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=location_type]').val('');
		$('.selectpicker').selectpicker('refresh');
		
		
		$('select[name=article]').val('Y');
		$('.selectpicker').selectpicker('refresh');
		
		$('#groups_in').attr('disabled', 'disabled');
		$('select[name=groups_in]').val('');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=country]').val('');
		$('.selectpicker').selectpicker('refresh');

		$('select[name=state]').val('');
		$('.selectpicker').selectpicker('refresh');
		$("#hiddenState").val("");
		
		$('select[name=istcs]').val('N');
		$('.selectpicker').selectpicker('refresh');
		
		
		$('select[name=BalancesYN]').val('N');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=ActSalestype]').val('Sales');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=bill_till_bal]').val('N');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=active]').val('1');
		$('.selectpicker').selectpicker('refresh');
		
		$('select[name=shipping_state]').val('');
		$('.selectpicker').selectpicker('refresh');
		
		$("#shipping_city").children().remove();
		$('.selectpicker').selectpicker('refresh');
		
		$("#addresstbody tr.addedtr").remove();
		// var is_admin = $('#is_admin').val();
		// if(is_admin !== "1"){
		// }
			$(".opening_bal").prop("readonly", false);
		
		$('.saveBtn').show();
		$('.updateBtn').hide();
		$('.saveBtn2').show();
		$('.updateBtn2').hide();
	}
	
    // Cancel selected data
	$(".cancelBtn").click(function(){
		ResetForm();
	});
	
	
	function populateVendorData(shipdata) {
		// Clear existing rows except the template row
		$("#addresstbody tr.addedtr").remove();
		
		// Clear any input values in the template row (optional)
		$("#shipping_state").val('');
		$("#shipping_city").val('');
		$("#ShippingAdrees").val('');
		$("#ShippingPin").val('');
		// Create a Set to store unique vendor IDs
		const existingVendorIds = new Set();
		
		// Populate rows based on vendors data
		shipdata.forEach(function (shipdata) {
			// Check if the vendor ID already exists in the Set
			if (!existingVendorIds.has(shipdata.id)) {
				// Add the vendor ID to the Set
				existingVendorIds.add(shipdata.id);
				
				// Create a new row for the vendor
				var newRow = $("<tr class='addedtr'></tr>");
				newRow.append("<td><input type='hidden' name='shipping_state[]' class='form-control' value='" + shipdata.ShippingState + "'><input type='hidden' name='shipping_id[]' class='form-control' value='" + shipdata.id + "'>"+shipdata.state_name+"</td>");
				newRow.append("<td><input type='hidden' name='shipping_city[]' class='form-control' value='" + shipdata.ShippingCity + "' readonly>"+shipdata.city_name+"</td>");
				newRow.append("<td><input type='text' name='ShippingAdrees[]' class='form-control' value='" + shipdata.ShippingAdrees + "'></td>");
				newRow.append("<td><input type='text' name='ShippingPin[]' class='form-control' value='" + shipdata.ShippingPin + "' ></td>");
				newRow.append("<td></td>");
				
				// Append the new row to the table body
				$("#addresstbody").append(newRow);
			}
		});
	}
	
    // Save New Item
	$('.saveBtn').on('click',function(){ 
		AccountID = $('#AccountID').val();
		AccoountName = $('#AccoountName').val();
		firstname = $('#firstname').val();
		lastname = $('#lastname').val();
		subgroup = $('#subgroup').val();
		title = $('#title').val();
		phonenumber = $('#phonenumber').val();
		altphonenumber = $('#altphonenumber').val();
		email = $('#email').val();
		vat = $('#vat').val();
		groups_in = $('#groups_in').val();
		country = $('#country').val();
		state = $('#state').val(); 
		city = $('#city').val();
		address = $('#address').val();
		Address3 = $('#Address3').val();
		zip = $('#zip').val();
		kms = $('#kms').val();
		credit_days = $('#credit_days').val();
		crate_limit = $('#crate_limit').val();
		FLNO1 = $('#FLNO1').val();
		gsttype = $('#gst_type').val();
		TradeType = $('#TradeType').val();
		Pan = $('#Pan').val();
		Aadhaarno = $('#Aadhaarno').val();
		istcs = $('#istcs').val();
		TcsStartDate1 = $('#TcsStartDate1').val();
		MaxCrdAmt = $('#MaxCrdAmt').val();
		Blockyn = $('#Blockyn').val();
		SalesFrequency = $('#SalesFrequency').val();
		location_type = $('#location_type').val();
		article = $('#article').val();
		BalancesYN = $('#BalancesYN').val();
		StationName = $('#StationName').val();
		FreshReturn = $('#FreshReturn').val();
		DamageReturn = $('#DamageReturn').val();
		Latitude = $('#Latitude').val();
		Longitude = $('#Longitude').val();
		ActSalestype = $('#ActSalestype').val();
		route = $('#route').val();
		route_point = $('#route_point').val();
		dis_per = $('#dis_per').val();
		dis_per_taxable = $('#dis_per_taxable').val();
		cd = $('#cd').val();
		rate_print = $('#rate_print').val();
		bill_till_bal = $('#bill_till_bal').val();
		active = $('#active').val();
		StartDate = $('#StartDate').val();
		expiry_licence = $('#expiry_licence').val();
		shipping_state = $('#shipping_state').val();
		shipping_city = $('#shipping_city').val();
		shipping_street = $('#shipping_street').val();
		shipping_zip = $('#shipping_zip').val();
		
		let Ship = [];
		$("#addresstbody tr").each(function() {
			let shipping_state = $(this).find("input[name='shipping_state[]']").val();
			let shipping_city = $(this).find("input[name='shipping_city[]']").val();
			let ShippingAdrees = $(this).find("input[name='ShippingAdrees[]']").val();
			let ShippingPin = $(this).find("input[name='ShippingPin[]']").val();
			Ship.push({ shipping_state: shipping_state, shipping_city: shipping_city, ShippingAdrees: ShippingAdrees, ShippingPin: ShippingPin });
		});
		let count = Ship.length;
		if(count <= 1){	
			Ship.push({ shipping_state: state, shipping_city: city, ShippingAdrees: address, ShippingPin: zip });
		}
		let ShippingData = JSON.stringify(Ship);
		
		var CompArray = new Array();
		var i = 1;
		$.each($("input[name='company_assigned']"), function(){
			var val = $(this).val();
			var id= 'company_assigned'+val;
			var company_assignedID = document.getElementById(id).value;
			var id2= 'company_assigned_staff'+val;
			var company_assigned_staffID = document.getElementById(id2).value;
			var id3= 'opening_bal'+val;
			var opening_balID = document.getElementById(id3).value;
			var id4= 'drcr'+val;
			var drcrID = document.getElementById(id4).value;
			var ii = i - 1;
			CompArray[ii]=new Array();
			CompArray[ii][0]=company_assignedID;
			CompArray[ii][1]=company_assigned_staffID;
			CompArray[ii][2]=opening_balID;
			CompArray[ii][3]=drcrID;
			i++;
		});
		let CompArraylength = CompArray.length;
		var CompSerializedArr = JSON.stringify(CompArray);
		
        if(AccountID == ''){
            alert('please enter AccountID');
            $('#AccountID').focus();
			}else if($.trim(AccoountName) == ''){
            alert('please enter Account Name');
            $('#AccountName').focus();
			}else if(title == ''){
            alert('please Select Position');
            $('#title').focus();
			}else if(subgroup == ''){
            alert('please Select Subgroup Name');
            $('#subgroup').focus();
			}/*else if(groups_in == '' || groups_in == null){
            alert('please Select Distributor Type');
            $('#groups_in').focus();
			}*/else if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#Pan').val() !== ""){
            alert('Enter valid PAN number');
            $('#Pan').focus();
			}else if(!$('#Aadhaarno').val().match('[0-9]{12}') && $('#Aadhaarno').val() !== ""){
            alert('Enter valid Aadhar number');
            $('#Aadhaarno').focus();
		}else if(country == '102' && gsttype == ''){
			alert('please select Gst Type');
			$('.saveBtn').removeAttr('disabled');
			$('#gst_type').focus();
			}else if(country == '102' && parseInt(gsttype) == 1 && vat == ''){
			alert('Enter valid GST number');
			$('.saveBtn').removeAttr('disabled');
			$('#gst_type').focus();
			}else if(country == '102' && !$('#vat').val().match('[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z][0-9][0-9A-Za-z]{2}') && $('#vat').val() !== '')  {
            alert("Enter valid GST no..");
            $('#vat').focus();
			}else if(phonenumber == ''){
            alert('please  enter mobile number');
            $('#phonenumber').focus();
			}else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
            alert('Enter valid Mobile number');
            $('#phonenumber').focus();
			}else if(!$('#email').val().match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/) && $('#email').val() !== ""){
			alert('Enter valid Email-id');
			$('.saveBtn').removeAttr('disabled');
			$('#email').focus();
			}else if(country == '' || country == null){
            alert('please select Country');
            $('#country').focus();
			}else if(state == ''){
            alert('please select State');
            $('#state').focus();
			}else if(city == ''){
            alert('please select City');
            $('#city').focus();
			}else if(route == ''){
            alert('Please Select Route');
            $('#route').focus();
			}else if($.trim(route_point) == '' || route_point == null){
            alert('Please Select Route Point');
            //$('#route_point').focus();
			}else if($.trim(location_type) == ''){
            alert('Please Select Location Type');
            $('#location_type').focus();
			}else if($.trim(ActSalestype) == ''){
            alert('Please Select Sale Type');
            $('#ActSalestype').focus();
			}else if(StationName == ''){
            alert('please enter station name');
            $('#StationName').focus();
			}else if(TradeType == ''){
            alert('please select Trade Type');
            $('#TradeType').focus();
			}else {
            $.ajax({
                url:"<?php echo admin_url(); ?>clients/SaveAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,AccoountName:AccoountName,firstname:firstname,lastname:lastname,subgroup:subgroup,title:title,phonenumber:phonenumber,
                    altphonenumber:altphonenumber,email:email,vat:vat,groups_in:groups_in,country:country,state:state,city:city,address:address,Address3:Address3,
                    zip:zip,kms:kms,crate_limit:crate_limit,credit_days:credit_days,FLNO1:FLNO1,Pan:Pan,Aadhaarno:Aadhaarno,istcs:istcs,TcsStartDate1:TcsStartDate1,MaxCrdAmt:MaxCrdAmt,
                    Blockyn:Blockyn,SalesFrequency:SalesFrequency,location_type:location_type,article:article,BalancesYN:BalancesYN,StationName:StationName,ActSalestype:ActSalestype,
                    route:route,route_point:route_point,dis_per:dis_per,dis_per_taxable:dis_per_taxable,cd:cd,rate_print:rate_print,gsttype:gsttype,
                    bill_till_bal:bill_till_bal,active:active,StartDate:StartDate,shipping_state:shipping_state,shipping_city:shipping_city,
                    shipping_street:shipping_street,shipping_zip:shipping_zip,CompSerializedArr:CompSerializedArr,expiry_licence:expiry_licence,ShippingData:ShippingData,FreshReturn:FreshReturn,DamageReturn:DamageReturn,Latitude:Latitude,Longitude:Longitude,TradeType:TradeType
				},
                beforeSend: function () {
					$('.searchh3').css('display','block');
					$('.searchh3').css('color','blue');
				},
                complete: function () {
					$('.searchh3').css('display','none');
				},
                success:function(data){
					if(data){
					    $('#HiddenAccountID').val(data);
						alert('Record created successfully...');
						ResetForm();
						}else{
						alert_float('warning', 'Something went wrong...');
						ResetForm();
					}
				}
			}); 
		}
		
	});
    // Update Exiting Item
	$('.updateBtn').on('click',function(){ 
		AccountID = $('#AccountID').val();
		AccoountName = $('#AccoountName').val();
		firstname = $('#firstname').val();
		lastname = $('#lastname').val();
		subgroup = $('#subgroup').val();
		title = $('#title').val();
		phonenumber = $('#phonenumber').val();
		altphonenumber = $('#altphonenumber').val();
		email = $('#email').val();
		gsttype = $('#gst_type').val();
		TradeType = $('#TradeType').val();
		vat = $('#vat').val();
		groups_in = $('#groups_in').val();
		country = $('#country').val();
		state = $('#state').val();
		city = $('#city').val();
		dis_per = $('#dis_per').val();
		dis_per_taxable = $('#dis_per_taxable').val();
		cd = $('#cd').val();
		rate_print = $('#rate_print').val();
		address = $('#address').val();
		Address3 = $('#Address3').val();
		zip = $('#zip').val();
		kms = $('#kms').val();
		credit_days = $('#credit_days').val();
		crate_limit = $('#crate_limit').val();
		FLNO1 = $('#FLNO1').val();
		Pan = $('#Pan').val();
		Aadhaarno = $('#Aadhaarno').val();
		istcs = $('#istcs').val();
		TcsStartDate1 = $('#TcsStartDate1').val();
		MaxCrdAmt = $('#MaxCrdAmt').val();
		Blockyn = $('#Blockyn').val();
		SalesFrequency = $('#SalesFrequency').val();
		location_type = $('#location_type').val();
		article = $('#article').val();
		BalancesYN = $('#BalancesYN').val();
		StationName = $('#StationName').val();
		FreshReturn = $('#FreshReturn').val();
		DamageReturn = $('#DamageReturn').val();
		Latitude = $('#Latitude').val();
		Longitude = $('#Longitude').val();
		ActSalestype = $('#ActSalestype').val();
		route = $('#route').val();
		route_point = $('#route_point').val();
		bill_till_bal = $('#bill_till_bal').val();
		active = $('#active').val();
		StartDate = $('#StartDate').val();
		expiry_licence = $('#expiry_licence').val();
		shipping_state = $('#shipping_state').val();
		shipping_city = $('#shipping_city').val();
		shipping_street = $('#shipping_street').val();
		shipping_zip = $('#shipping_zip').val();
		
		let Ship = [];
		$("#addresstbody tr").each(function() {
			let shipping_id = $(this).find("input[name='shipping_id[]']").val();
			let shipping_state = $(this).find("input[name='shipping_state[]']").val();
			let shipping_city = $(this).find("input[name='shipping_city[]']").val();
			let ShippingAdrees = $(this).find("input[name='ShippingAdrees[]']").val();
			let ShippingPin = $(this).find("input[name='ShippingPin[]']").val();
			
			if(shipping_state != '' && shipping_state != null){
				Ship.push({ shipping_id:shipping_id, shipping_state: shipping_state, shipping_city: shipping_city, ShippingAdrees: ShippingAdrees, ShippingPin: ShippingPin });
			}
		});
		
		let ShippingData = JSON.stringify(Ship);
		
		
		var CompArray = new Array();
		var i = 1;
		$.each($("input[name='company_assigned']:checked"), function(){
			var val = $(this).val();
			var id= 'company_assigned'+val;
			var company_assignedID = document.getElementById(id).value;
			var id2= 'company_assigned_staff'+val;
			var company_assigned_staffID = document.getElementById(id2).value;
			var id3= 'opening_bal'+val;
			var opening_balID = document.getElementById(id3).value;
			var id4= 'drcr'+val;
			var drcrID = document.getElementById(id4).value;
			var ii = i - 1;
			CompArray[ii]=new Array();
			CompArray[ii][0]=company_assignedID;
			CompArray[ii][1]=company_assigned_staffID;
			CompArray[ii][2]=opening_balID;
			CompArray[ii][3]=drcrID;
			i++;
		});
		let CompArraylength = CompArray.length;
		var CompSerializedArr = JSON.stringify(CompArray);
		if(AccountID == ''){
            alert('please enter AccountID');
            $('#AccountID').focus();
			}else if($.trim(AccoountName) == ''){
            alert('please enter Account Name');
            $('#AccountName').focus();
			}else if(title == ''){
            alert('please Select Position');
            $('#title').focus();
			}else if(subgroup == ''){
            alert('please Select Subgroup Name');
            $('#subgroup').focus();
			}else if(groups_in == '' || groups_in == null){
            alert('please Select Distributor Type');
            $('#groups_in').focus();
			}else if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#Pan').val() !== ""){
            alert('Enter valid PAN number');
            $('#Pan').focus();
			}else if(!$('#Aadhaarno').val().match('[0-9]{12}') && $('#Aadhaarno').val() !== ""){
            alert('Enter valid Aadhar number');
            $('#Aadhaarno').focus();
			}else if(country == '102' && gsttype == ''){
			alert('please select Gst Type');
			$('.saveBtn').removeAttr('disabled');
			$('#gst_type').focus();
			}else if(country == '102' && parseInt(gsttype) == 1 && vat == ''){
			alert('Enter valid GST number');
			$('.saveBtn').removeAttr('disabled');
			$('#gst_type').focus();
			}else if(country == '102' && !$('#vat').val().match('[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z][0-9][0-9A-Za-z]{2}') && $('#vat').val() !== '')  {
            alert("Enter valid GST no..");
            $('#vat').focus();
			}else if(phonenumber == ''){
            alert('please  enter mobile number');
            $('#phonenumber').focus();
			}else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
            alert('Enter valid Mobile number');
            $('#phonenumber').focus();
			}else if(!$('#email').val().match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/) && $('#email').val() !== ""){
			alert('Enter valid Email-id');
			$('.saveBtn').removeAttr('disabled');
			$('#email').focus();
			}else if(country == '' || country == null){
            alert('please select Country');
            $('#country').focus();
			}else if(state == ''){
            alert('please select State');
            $('#state').focus();
			}else if(city == ''){
            alert('please select City');
            $('#city').focus();
			}else if(route == ''){
            alert('please select Route');
            $('#route').focus();
			}else if(route_point == '' || route_point == ' ' || route_point == null){
            alert('Please Select Route Point');
            //$('#route_point').focus();
			}else if($.trim(location_type) == ''){
            alert('please select location type');
            $('#location_type').focus();
			}else if($.trim(ActSalestype) == ''){
            alert('please select sale type');
            $('#ActSalestype').focus();
			}else if(StationName == ''){
            alert('please enter station name');
            $('#StationName').focus();
			}else if(TradeType == ''){
            alert('please select Trade Type');
            $('#TradeType').focus();
			}else {
            $.ajax({
                url:"<?php echo admin_url(); ?>clients/UpdateAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,AccoountName:AccoountName,firstname:firstname,lastname:lastname,subgroup:subgroup,title:title,phonenumber:phonenumber,
                    altphonenumber:altphonenumber,email:email,vat:vat,groups_in:groups_in,country:country,state:state,city:city,dis_per:dis_per,dis_per_taxable:dis_per_taxable,cd:cd,rate_print:rate_print,address:address,Address3:Address3,
                    zip:zip,kms:kms,credit_days:credit_days,crate_limit:crate_limit,FLNO1:FLNO1,Pan:Pan,Aadhaarno:Aadhaarno,istcs:istcs,TcsStartDate1:TcsStartDate1,MaxCrdAmt:MaxCrdAmt,
                    Blockyn:Blockyn,SalesFrequency:SalesFrequency,location_type:location_type,article:article,BalancesYN:BalancesYN,StationName:StationName,
                    ActSalestype:ActSalestype,route:route,route_point:route_point,
                    bill_till_bal:bill_till_bal,active:active,StartDate:StartDate,shipping_state:shipping_state,shipping_city:shipping_city,
                    shipping_street:shipping_street,shipping_zip:shipping_zip,
                    CompSerializedArr:CompSerializedArr,expiry_licence:expiry_licence,ShippingData:ShippingData,gsttype:gsttype,FreshReturn:FreshReturn,DamageReturn:DamageReturn,Latitude:Latitude,Longitude:Longitude,TradeType:TradeType
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
						alert('Record updated successfully...');
						ResetForm();
						}else{
						alert_float('warning', 'Something went wrong...');
						ResetForm();
					}
				}
			});
		}
		
	});
    
    $('#state').on('change', function() {
		var StateID = $(this).val();
		var url = "<?php echo base_url(); ?>admin/clients/GetCity";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {StateID: StateID},
            dataType:'json',
            success: function(data) {
                $("#city").find('option').remove();
                $("#city").selectpicker("refresh");
                for (var i = 0; i < data.length; i++) {
                    $("#city").append(new Option(data[i].city_name, data[i].id));
				}
                $('.selectpicker').selectpicker('refresh');
			}
		});
	});

	$('#country').on('change', function() {
		var CountryID = $(this).val();

		// If country is not India (102), clear/disable GST fields and set type to Un-Registered
		if(CountryID !== '102'){
			$('#gst_type').val('2');
			$('#gst_type').prop('disabled', true);
			$('#vat').val('');
			$('#vat').prop('disabled', true);
			$('.selectpicker').selectpicker('refresh');
		} else {
			$('#gst_type').prop('disabled', false);
			$('#vat').prop('disabled', false);
			$('.selectpicker').selectpicker('refresh');
		}

		var url = "<?php echo base_url(); ?>admin/clients/GetStatesByCountry";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {CountryID: CountryID},
            dataType:'json',
            success: function(data) {
                $("#state").find('option').remove();
                $("#state").selectpicker("refresh");
                $("#state").append(new Option('None selected', ""));
                for (var i = 0; i < data.length; i++) {
                    $("#state").append(new Option(data[i].state_name, data[i].short_name));
				}
                $('.selectpicker').selectpicker('refresh');

				$("#city").find('option').remove();
                $("#city").append(new Option('None selected', ""));
                $('.selectpicker').selectpicker('refresh');
			}
		});
	});
	//==================== Check Distributor Type state ============================
	
    /*$('#groups_in').on('change', function() {
        var DistType = $(this).val();
        var hiddenState = $("#hiddenState").val();
        var url = "<?php echo base_url(); ?>admin/clients/GetDistTypeState";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {DistType: DistType},
            dataType:'json',
            success: function(data) {
                if(data){
                   
                    if(data.state == hiddenState){
						}else{
                        $('#groups_in').val('');
                        $('.selectpicker').selectpicker('refresh');
                        alert("Party state and  Distributor Type state must be same");
					}   
				}
			}
		});
	})*/
	//======================== Get Route Point aginst routes =================
	$('#route').on('change', function() {
		var routes = $(this).val();
		var url = "<?php echo base_url(); ?>admin/clients/GetRoutePoints";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {routes: routes},
            dataType:'json',
            success: function(data) {
                $("#route_point").find('option').remove();
                $("#route_point").selectpicker("refresh");
                $("#route_point").append(new Option('None selected', " "));
                for (var i = 0; i < data.length; i++) {
                    $("#route_point").append(new Option(data[i].PointName, data[i].id));
				}
                $('.selectpicker').selectpicker('refresh');
			}
		});
	});
	
	
	
	function addRow() {
		var shipping_state = $("#shipping_state").val();
		var shipping_state_text = $("#shipping_state option:selected").text();
		var shipping_city = $("#shipping_city").val();
		var shipping_city_text = $("#shipping_city option:selected").text();
		var ShippingAdrees = $("#ShippingAdrees").val();
		var ShippingPin = $("#ShippingPin").val();
		
		// Validate if all required fields are filled
		if (shipping_state !== '' && shipping_city !== '' && ShippingAdrees !== '' && ShippingPin !== '') {
			var exists = false;
			
			// Check if the state and city combination already exists in the table
			$("#addresstbody tr").each(function(index) {
				var existingState = $(this).find("input[name='shipping_state[]']").val();
				var existingCity = $(this).find("input[name='shipping_city[]']").val();
				console.log(existingState);
				if (existingState === shipping_state && existingCity === shipping_city) {
					exists = true;
					return false; // Exit the loop if state and city combination already exists
				}
			});
			
			if (!exists) {
				var newRow = $("<tr class='addedtr'></tr>");
				
				// Append columns to the new row
				newRow.append("<td><input type='hidden' name='shipping_state[]' value='" + shipping_state + "'>" + shipping_state_text + "</td>");
				newRow.append("<td><input type='hidden' name='shipping_city[]' value='" + shipping_city + "'>" + shipping_city_text + "</td>");
				newRow.append("<td><input type='text' name='ShippingAdrees[]' class='form-control' value='" + ShippingAdrees + "'></td>");
				newRow.append("<td><input type='text' name='ShippingPin[]' class='form-control' value='" + ShippingPin + "'></td>");
				newRow.append("<td><a href='#' class='btn btn-danger removebtn'><i class='fa fa-times'></i></a></td>");
				
				// Append the new row to the table body
				$("#addresstbody").append(newRow);
				
				// Clear input fields after adding row
				$("#shipping_state").val('').selectpicker('refresh');
				$("#shipping_city").val('').selectpicker('refresh');
				$("#ShippingAdrees").val('');
				$("#ShippingPin").val('');
				} else {
				alert('The shipping state and city combination already exists.');
			}
			} else {
			alert('All fields are required.');
		}
	}
	
	// Attach event handler for removing rows
	$(document).on('click', '.removebtn', function() {
		$(this).closest('tr').remove();
	});
</script>

<script>
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_Account_List");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
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
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_Account_List tbody");
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
						$("#AccoountName").val(res.GstData.legalName);
						$("#address").val(res.GstData.address1);
						$("#Address3").val(res.GstData.address2.substring(6));
						$("#zip").val(res.GstData.pinCode);
						}else{
						alert('Invalid Gst No.');
						$("#vat").val('');
						$("#AccoountName").val('');
					}
					
				},
			});
		});
	});
</script>
<script>
	function validateZipCode(elementValue){
		var zipCodePattern = /^\d{5}$|^\d{5}-\d{4}$/;
		return zipCodePattern.test(elementValue);
	}
</script>
<script>
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

<script type="text/javascript">
	$('#MaxCrdAmt,#kms,.opening_bal,#crate_limit,#credit_days').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});
</script>
<style>
	
	#AccountID {
    text-transform: uppercase;
	}
	#Pan {
    text-transform: uppercase;
	}
	#vat {
    text-transform: uppercase;
	}
	#table_Account_List td:hover {
    cursor: pointer;
	}
	#table_Account_List tr:hover {
    background-color: #ccc;
	}
	
    .table-Account_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Account_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Account_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>