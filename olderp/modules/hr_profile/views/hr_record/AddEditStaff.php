<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-10">
        <div class="panel_s">
          <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="searchh2" style="display:none;">Please wait while fetching data.</div>
                        <div class="searchh3" style="display:none;">Please wait while creating new record.</div>
                        <div class="searchh4" style="display:none;">Please wait while updating data.</div>
                    </div>
                    <br>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="AccountID">
                            <small class="req text-danger">* </small>
                            <label for="AccountID" class="control-label">AccountID</label>
                            <input type="text" id="AccountID" name="AccountID" class="form-control" value="" autocomplete="off" />
                            <?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
                            <input type="hidden" name="staffid" value="<?php echo $staff_user_id; ?>" id="staffid">
                            <input type="hidden" name="userid" value="" id="userid">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            
                            <label class="control-label" for="sldtype">SLDType</label>
                            <select class="selectpicker display-block" data-width="100%" name="sldtype" id="sldtype" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
                            <?php
                                foreach ($SLDTYPE as $key => $value) {
                            ?>
                                <option value="<?php echo $value['SLDTypeID'];?>"><?php echo $value['SLDTypeName'];?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <small class="req text-danger">* </small>
                            <label class="control-label" for="SubActGroupID">Account Group</label>
                            <select class="selectpicker display-block" data-width="100%" name="SubActGroupID" id="SubActGroupID" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                            <?php
                                foreach ($SubGroup as $key => $value) {
                            ?>
                                <option value="<?php echo $value['SubActGroupID'];?>"><?php echo $value['SubActGroupName'];?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input('firstname','Firstname','','text'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input('lastname','Lastname','','text'); ?>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-2">
                        <?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
                        <div class="form-group">
                            <label for="">Opening Balance</label>
                            <input type="text" name="opening_b" id="opening_b" value="" class="form-control" <?php if(isset($client) && $staff_user_id !== "3"){ echo "disabled";}?>>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="phonenumber">
                            <small class="req text-danger">* </small>
                            <label for="phonenumber" class="control-label">Mobile Number</label>
                            <input type="text" id="phonenumber" name="phonenumber" class="form-control" value="" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="altphonenumber">
                            <label for="altphonenumber" class="control-label">Alternative Mobile</label>
                            <input type="text" id="altphonenumber" name="altphonenumber" class="form-control" value="" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="email">
                            <label for="email" class="control-label">Email</label>
                            <input type="text" id="email" name="email" class="form-control" value="">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="peremail">
                            <label for="peremail" class="control-label">Personal Email</label>
                            <input type="text" id="peremail" name="peremail" class="form-control" value="">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="state">
                            <small class="req text-danger">* </small>
                            <label for="state" class="form-label">State</label>
                            <select name="state" id="state" class="selectpicker form-control" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
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
                    
                    <div class="col-md-2">
                        <div class="form-group">
                           <small class="req text-danger">* </small>
                            <label for="city" class="control-label">City</label>
                            <select class="form-control city selectpicker" data-width="100%" data-none-selected-text="Non Selected" name="city" id="city" data-live-search="true">
                                <option value="">Select city name</option>
                            </select>
                                
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="zip">
                            <label for="zip" class="control-label">Pin Code</label>
                            <input type="text"  name="zip" id="zip" class="form-control" onchange="validateZipCode" value="" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                        </div>
                    </div> 
                    
                    <div class="col-md-3">
                        <?php echo render_input( 'current_address', 'Address 1'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input( 'home_town', 'Address 2'); ?>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-2">
                        <label for="sex">Sex</label>
                        <select name="sex" id="sex" class="selectpicker form-control sex">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="Pan"> 
                            <label for="Pan" class="control-label">PAN number</label>
                            <input type="text" maxlength="10" minlength="10" name="Pan" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="Pan" class="form-control" 
                            value="">
                            <span class="pan_denger" style="color:red;"></span>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="Aadhaarno">
                            <label for="aadhaar" class="control-label">Aadhar number</label>
                            <input type="text" maxlength="12" minlength="12"  name="Aadhaarno" pattern="[0-9] {12}" id="Aadhaarno" class="form-control numbersOnly" onkeypress="return isNumber(event)" value="">
                            <span class="aadhar_denger" style="color:red;"></span>
                        </div>
                    </div>
                    
                    <!--<div class="col-md-3">
                        <div class="form-group" app-field-wrapper="company_id1">
                            <small class="req text-danger">* </small>
                            <label for="company_id1" class="form-label">Select Company</label>
                            <select name="company_id1[]" id="company_id1" class="selectpicker form-control" multiple data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
                            <?php
                                foreach ($rootcompany as $key => $value) {
                            ?>
                                  <option value="<?php echo $value['id'];?>"><?php echo $value['company_name'];?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                    </div>-->
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="team_manage">
                            <small class="req text-danger">* </small>
                            <label for="team_manage" class="form-label">Report To</label>
                            <select name="team_manage" id="team_manage" class="selectpicker form-control" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
                            <?php
                                foreach ($list_staff as $key => $value) {
                            ?>
                                  <option value="<?php echo $value['staffid'];?>"><?php echo $value['firstname']. " ".$value['lastname']; ?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-2">
                        <?php echo render_input( 'StationName', 'Station Name','','text'); ?>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" app-field-wrapper="job_position">
                            <small class="req text-danger">* </small>
                            <label for="job_position" class="form-label">Position</label>
                            <select name="job_position" id="job_position" class="selectpicker form-control" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
                            <?php
                                foreach ($positions as $key => $value) {
                            ?>
                                  <option value="<?php echo $value['position_id'];?>"><?php echo $value['position_name'];?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">	
						<label for="account_number" class="control-label">Account number</label>
						<input type="tel" minlenght="9" maxlength="18"  name="account_number" pattern="[0-9] {10}" id="account_number" class="form-control" value="<?php echo $account_number?>">
						<span class="actnumber_denger" style="color:red;"></span>
					</div>
					
					<div class="col-md-3">
						<?php					
						echo render_input('name_account','Bank account holder','', 'text'); ?>
					</div>
					
					<div class="col-md-3">
						<?php
						echo render_input('issue_bank','hr_bank_name',$issue_bank, 'text'); ?>
					</div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="headqurter" class="control-label">Head Quarter</label>
                            <select class="form-control headqurter selectpicker" data-width="100%" data-none-selected-text="Non Selected" name="headqurter" id="headqurter" data-live-search="true">
                                <option value="">Select headqurter name</option>
                            </select>
                                
                        </div>
                    </div>
                    <div class="col-md-2">
                        <?php echo render_date_input( 'birthday', 'Birthday','','text'); ?>
                    </div>
                    
                    
                    <div class="col-md-2">
					    <div class="form-group">
						    <label for="literacy" class="control-label"><?php echo _l('hr_hr_literacy'); ?></label>
						    <select name="literacy" id="literacy" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('hr_not_required'); ?>">
							    <option value=""></option>
							    <option value="primary_level" ><?php echo _l('hr_primary_level'); ?></option>
							    <option value="intermediate_level" ><?php echo _l('hr_intermediate_level'); ?></option>
							    <option value="college_level" ><?php echo _l('hr_college_level'); ?></option>
							    <option value="masters" ><?php echo _l('hr_masters'); ?></option>
							    <option value="doctor" ><?php echo _l('hr_Doctor'); ?></option>
							    <option value="bachelor" ><?php echo _l('hr_bachelor'); ?></option>
							    <option value="engineer" ><?php echo _l('hr_Engineer'); ?></option>
							    <option value="university" ><?php echo _l('hr_university'); ?></option>
							    <option value="intermediate_vocational" ><?php echo _l('hr_intermediate_vocational'); ?></option>
							    <option value="college_vocational" ><?php echo _l('hr_college_vocational'); ?></option>
							    <option value="in-service" ><?php echo _l('hr_in-service'); ?></option>
							    <option value="high_school" ><?php echo _l('hr_high_school'); ?></option>
							    <option value="intermediate_level_pro" ><?php echo _l('hr_intermediate_level_pro'); ?></option>
						    </select>
					    </div>
				    </div>
				    
				    <div class="col-md-2">
						<div class="form-group">
							<label for="marital_status" class="control-label"><?php echo _l('hr_hr_marital_status'); ?></label>
							<select name="marital_status" class="selectpicker" id="marital_status" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
								<option value=""></option>                  
								<option value="<?php echo 'single'; ?>" ><!--<?php echo _l('single'); ?>-->single</option>
								<option value="<?php echo 'married'; ?>"><?php echo _l('married'); ?></option>
							</select>
						</div>
					</div>
				    
				    <div class="col-md-2">
						<div class="form-group">
                            <label for="active" class="control-label"><?php echo _l('hr_status_work'); ?></label>
                            <select name="active" class="selectpicker" id="active" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
                                <option value="1">Active</option>
                                <option value="0">De-Active</option>
                            </select>
                        </div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label for="app_access" class="control-label">SO App Access</label>
							<select name="app_access" class="selectpicker" id="app_access" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
								<option value="No" >No</option>
								<option value="Yes" >Yes</option>
							</select>
						</div>
					</div>
					
					
					
				    <div class="clearfix"></div>
                    
                    <div class="col-md-2">
						<?php 
						$curDate = date('d/m/Y');
						echo render_date_input('datecreated','Started Date',$curDate,'date'); ?>
					</div>
					
					<!--<div class="col-md-2">
						<?php 
						//echo render_input('IMEI','IMEI Number'); ?>
					</div>-->					
                    
                    <div class="col-md-2">
					<?php 
							echo render_input('DeviceID','DeviceID'); ?>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label for="Movement" class="control-label">Movement</label>
							<select name="Movement" class="selectpicker" id="Movement" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
								<option value="No" >No</option>
								<option value="Yes">Yes</option>
							</select>
						</div>
					</div>
					
					<div class="col-md-3">
					    <div class="form-group">
						    <label for="OfficeID" class="control-label">Office Location</label>
						    <select name="OfficeID" class="selectpicker" id="OfficeID" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
    							    <option value=""></option>
    						    <?php foreach ($OfficeLocation as $key => $value) { ?>
        						    <option value="<?php echo $value['AccountID']; ?>" ><?php echo $value['OfficeName']; ?></option>
        					    <?php	} ?> 
						    </select>
					    </div>
				    </div>
										
                    <div class="col-md-3">
								<?php //if(!isset($member) || is_admin() || !is_admin() && $member->admin == 0) { ?>
										<!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
						<input  type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1"/>
						<input  type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1"/>
						<div class="clearfix form-group" style="margin-top:-20px;"></div>
						<div id="password_field">
							<label for="password" class="control-label">Create Password for APP</label>
    						<div class="input-group" >
    							<input type="password" class="form-control password" name="password" id="password"  autocomplete="off">
    							<span class="input-group-addon">
    								<a href="#password" class="show_password" onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
    							</span>
    							<span class="input-group-addon">
    								<a href="#" class="generate_password" onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
    							</span>
    						</div>
										<?php if(isset($member)){ ?>
											<p class="text-muted"><?php echo _l('staff_add_edit_password_note'); ?></p>
											<?php if($member->last_password_change != NULL){ ?>
												<?php //echo _l('staff_add_edit_password_last_changed'); ?>
												<!--<span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($member->last_password_change); ?>">
													<?php echo time_ago($member->last_password_change); ?>
												</span>-->
											<?php } } ?>
						</div>
										<?php //} ?>
				    </div>
				    
                    <div class="clearfix"></div>   
                    
                    <!--<div class="col-md-12" >
                        <?php if(count($departments) > 0){ ?>
                            <label for="departments"><?php echo _l('staff_add_edit_departments'); ?></label>
                            <br>
                        <?php } ?>
                        <?php foreach($departments as $department){ ?>
                                 <div class="checkbox checkbox-primary col-md-3">

                                     <input type="checkbox" class="departments" id="dep_<?php echo html_entity_decode($department['departmentid']); ?>" name="departments" value="<?php echo html_entity_decode($department['departmentid']); ?>">
                                     <label for="dep_<?php echo html_entity_decode($department['departmentid']); ?>"><?php echo html_entity_decode($department['name']); ?></label>
                                 </div>
                              <?php } ?>
                    </div>-->
                </div>
                
                <div class="btn-bottom-toolbar text-right">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (has_permission('hrm_hr_records', '', 'create')) {
                            ?>
                            <button type="button" class="btn btn-info saveBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Save</button>
                            <?php
                            }else{
                            ?>
                            <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                            <?php
                            }?>
                            
                            <?php if (has_permission('hrm_hr_records', '', 'edit')) {
                            ?>
                            <button type="button" class="btn btn-info updateBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Update</button>
                            <?php
                            }else{
                            ?>
                            <button type="button" class="btn btn-info updateBtn2 disabled" style="margin-right: 25px;">Update</button>
                            <?php
                            }?>
                            
                            <button type="button" class="btn btn-default cancelBtn" >Cancel</button>
                        </div>
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
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">AccountID </th>
                                            <th style="text-align:left;">Full Name</th>
                                            <th style="text-align:left;">Company Name</th>
                                            <th style="text-align:left;">Mobile</th>
                                            <th style="text-align:left;">State</th>
                                            <th style="text-align:left;">reportTo</th>
                                            <!--<th style="text-align:left;">Department</th>-->
                                            <th style="text-align:left;">Designation</th>
                                            <th style="text-align:left;">Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($table_data as $key => $value) {
                                    ?>
                                        <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                            <td><?php echo $value['AccountID'];?></td>
                                            <td><?php echo $value['firstname'].' '.$value['lastname'];?></td>
                                        <?php
                                            /*$staff_company = unserialize($value['staff_comp']);
                                		    $stf_comp_name = "";
                                		    $j =1;
                                		    foreach ($staff_company as $key1 => $value1) {*/
                                                # code...
                                                $name = get_comp_name_by_id($value['PlantID']);
                                               /* 
                                                if($j>1){
                                                    $stf_comp_name .= ",";
                                                }
                                                $stf_comp_name .= $name;
                                                $j++;
                                                
                                            }*/
                                        ?>
                                            <td><?php echo $name;?></td>
                                            <td><?php echo $value["phonenumber"];?></td>
                                            <td><?php echo $value["state"];?></td>
                                            
                                            <?php
                                            $fullName = $value["reported_f_n"].' '.$value["reported_l_n"];
                                            $name = substr($fullName, 0,strpos($fullName, "/"));
                                            ?>
                                            <td><?php echo $name;?></td>
                                            <td><?php echo $value["position_name"];?></td>
                                            <td><?php if($value["active"] == "0"){ echo 'Inactive'; }else{ echo 'Active';} ?></td>
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
    $(document).ready(function(){
        var SessionID = "<?php echo $this->session->userdata('AccountIDSet');?>";
        //alert('hello');
        if(SessionID !== ""){
            
            //alert(SessionID);
            $.ajax({
                url:"<?php echo admin_url(); ?>hr_profile/GetAccountDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:SessionID},
                beforeSend: function () {
                $('.searchh2').css('display','block');
                $('.searchh2').css('color','blue');
                },
                complete: function () {
                $('.searchh2').css('display','none');
                },
                success:function(data){
                    if(data == null){
                       
                       
                        $('#opening_b').removeAttr('disabled');
                        $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        
                    }else if(data.Type == 'othercompstaff'){
                        alert('This AccountID Use for Other Company Staff');
                        $('#AccountID').focus();
                       
                        $('#opening_b').removeAttr('disabled');
                        $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else if(data.Type == 'staff'){
                       
                       $('#AccountID').val(data.AccountID);
                       $('#firstname').val(data.firstname);
                       $('#lastname').val(data.lastname);
                       $('#userid').val(data.staffid);
                        $('select[id=SubActGroupID]').val(data.SubActGroupID);
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[id=sldtype]').val(data.SLDTypeID);
                        $('.selectpicker').selectpicker('refresh');
                        
                       $('#email').val(data.email);
                       $('#peremail').val(data.peremail);
                       $('#phonenumber').val(data.phonenumber);
                       $('#altphonenumber').val(data.mobile2);
                       if(data.birthday !== null){
                           var date = data.birthday.substring(0, 10);
                            var date_new = date.split("-").reverse().join("/");
                            $('#birthday').val(date_new);
                       }else{
                           $('#birthday').val('');
                       }
                       var staffid = $('#staffid').val();
                       $('#opening_b').val(data.BAL1);
                        if(staffid !== "3"){
                            $('#opening_b').attr('disabled','disabled');    
                        }
                       $('select[name=state]').val(data.state);
                       $('.selectpicker').selectpicker('refresh');
                       
                       let CityList = data.CityList;
                        $("#city").children().remove();
                        for (var i = 0; i < CityList.length; i++) {
                            $("#city").append('<option value="'+CityList[i]["id"]+'">'+CityList[i]["city_name"]+'</option>');
                        }
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('#city').selectpicker('val', data.city);
                        $('.selectpicker').selectpicker('refresh');
                        
                       $('#zip').val(data.pincode);
                       $('#current_address').val(data.current_address);
                       $('#home_town').val(data.home_town);
                       $('#StationName').val(data.stationName);
                       
                       $('select[name=job_position]').val(data.job_position);
                       $('.selectpicker').selectpicker('refresh');
                       
                       let HeadQurterList = data.HeadQurterList;
                        $("#headqurter").children().remove();
                        for (var i = 0; i < HeadQurterList.length; i++) {
                            $("#headqurter").append('<option value="'+HeadQurterList[i]["id"]+'">'+HeadQurterList[i]["name"]+'</option>');
                        }
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('#headqurter').selectpicker('val', data.headqurter);
                        $('.selectpicker').selectpicker('refresh');
                        
                        /*$('#company_id1').selectpicker('val', data.compList);
                        $('.selectpicker').selectpicker('refresh')*/
                        
                       $('#team_manage').selectpicker('val', data.team_manage);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#sex').selectpicker('val', data.sex);
                       $('.selectpicker').selectpicker('refresh')
                       $('#Pan').val(data.pan_number);
                       $('#Aadhaarno').val(data.aadhar_number);
                       $('#account_number').val(data.account_number);
                       $('#name_account').val(data.name_account);
                       $('#issue_bank').val(data.issue_bank);
                       
                       $('#literacy').selectpicker('val', data.literacy);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#marital_status').selectpicker('val', data.marital_status);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#OfficeID').selectpicker('val', data.OfficeID);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#active').selectpicker('val', data.active);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#app_access').selectpicker('val', data.app_access);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#Movement').selectpicker('val', data.Movement);
                       $('.selectpicker').selectpicker('refresh');
                       if(data.StartDate == '' || data.StartDate == null){
                           $('#datecreated').val('');
                       }else{
                           var date = data.StartDate.substring(0, 10);
                            var date_new = date.split("-").reverse().join("/");
                            $('#datecreated').val(date_new);
                       }
                       
                       $('#DeviceID').val(data.DiveceID);
                       $('#dep_'+data.departmentid).prop('checked', true);
                       $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                    }else if(data.Type == 'client'){
                        $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                        alert("This AccountID Use for other Accounts");
                        $('#AccountID').focus();
                    } 
                }
            });
        }
        /*$('input.departments').on('change', function() {
            $('input.departments').not(this).prop('checked', false);  
        });*/
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $("#AccountID").dblclick(function(){
            $('#Account_List').modal('show');
            $('#Account_List').on('shown.bs.modal', function () {
                $('#myInput1').val('');
                $('#myInput1').focus();
            })
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
        $('#Pan').keyup(function(e) {
            var val = $('#Pan').val();
            if(val == ""){
                $(".pan_denger").text(" ");
            }else{
                e.preventDefault();
                if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}'))  {
                    $(".pan_denger").text("Enter valid PAN number");
                }else{
                    $(".pan_denger").text(" ");
                }
            }
        });
        
        $('#phonenumber').keyup(function(e) {
            e.preventDefault();
            if(!$('#phonenumber').val().match('[0-9]{10}'))  {
                
                $(".mob_denger").text("Enter valid 10 digit mobile number");
            }else{
                $(".mob_denger").text(" ");
            }
        });
        
        $('#Aadhaarno').keyup(function(e) {
            e.preventDefault();
            if(!$('#Aadhaarno').val().match('[0-9]{12}'))  {
                
                $(".aadhar_denger").text("Enter valid 12 digit Aadhar number");
            }else{
                $(".aadhar_denger").text(" ");
            }
        });
        
        $('#account_number').keyup(function(e) {
            e.preventDefault();
            if(!$('#account_number').val().match('[0-9]{9}'))  {
                
                $(".actnumber_denger").text("Enter valid Account number");
            }else{
                $(".actnumber_denger").text(" ");
            }
        });
    });
    
    // Empty and open create mode
        $("#AccountID").focus(function(){
            $('#AccountID').val('');
            $('#firstname').val('');
            $('#lastname').val('');
            $('#userid').val('');           
            $('select[name=sldtype]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=SubActGroupID]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#email').val('');
            $('#peremail').val('');
            $('#phonenumber').val('');
            $('#altphonenumber').val('');
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('#birthday').val(today);
            $('#opening_b').val(0.00);
                       
            $('select[name=state]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $("#city").children().remove();
            $('.selectpicker').selectpicker('refresh');
                       
            $('#zip').val('');
            $('#current_address').val('');
            $('#home_town').val('');
            $('#StationName').val('');
                       
            $('select[name=job_position]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $("#headqurter").children().remove();
            $('.selectpicker').selectpicker('refresh');
                       
            /*$('#company_id1').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh')*/
                        
            $('#team_manage').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#sex').selectpicker('val', 'male');
            $('.selectpicker').selectpicker('refresh')
            $('#Pan').val('');
            $('#Aadhaarno').val('');
            $('#account_number').val('');
            $('#name_account').val('');
            $('#issue_bank').val('');
                       
            $('#literacy').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#marital_status').selectpicker('val', 'single');
            $('.selectpicker').selectpicker('refresh');
            
            $('#OfficeID').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
            
            $('#active').selectpicker('val', '1');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#app_access').selectpicker('val', 'No');
            $('.selectpicker').selectpicker('refresh');
            
            $('#Movement').selectpicker('val', 'No');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#datecreated').val(today);
            $('#DeviceID').val('');
            //$('.departments').prop('checked', false);
                       
            $('#opening_b').removeAttr('disabled');
            $('.updateBtn').removeAttr('disabled');
            $('.saveBtn').removeAttr('disabled');
            $('.saveBtn').show();
            $('.updateBtn').hide();
            $('.saveBtn2').show();
            $('.updateBtn2').hide();
            
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
            $('#AccountID').val('');
            $('#firstname').val('');
            $('#lastname').val('');
            $('#userid').val('');           
            $('select[name=sldtype]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=SubActGroupID]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#email').val('');
            $('#peremail').val('');
            $('#phonenumber').val('');
            $('#altphonenumber').val('');
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('#birthday').val(today);
            $('#opening_b').val(0.00);
                       
            $('select[name=state]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $("#city").children().remove();
            $('.selectpicker').selectpicker('refresh');
                       
            $('#zip').val('');
            $('#current_address').val('');
            $('#home_town').val('');
            $('#StationName').val('');
                       
            $('select[name=job_position]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $("#headqurter").children().remove();
            $('.selectpicker').selectpicker('refresh');
                       
            /*$('#company_id1').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh')*/
                        
            $('#team_manage').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#sex').selectpicker('val', 'male');
            $('.selectpicker').selectpicker('refresh')
            $('#Pan').val('');
            $('#Aadhaarno').val('');
            $('#account_number').val('');
            $('#name_account').val('');
            $('#issue_bank').val('');
                       
            $('#literacy').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#marital_status').selectpicker('val', 'single');
            $('.selectpicker').selectpicker('refresh');
            
            $('#OfficeID').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#active').selectpicker('val', '1');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#app_access').selectpicker('val', 'No');
            $('.selectpicker').selectpicker('refresh');
            
            $('#Movement').selectpicker('val', 'No');
            $('.selectpicker').selectpicker('refresh');
                       
            $('#datecreated').val(today);
            $('#DeviceID').val('');
            //$('.departments').prop('checked', false);
                       
            $('#opening_b').removeAttr('disabled');
            $('.updateBtn').removeAttr('disabled');
            $('.saveBtn').removeAttr('disabled');           
            $('.saveBtn').show();
            $('.updateBtn').hide();
            $('.saveBtn2').show();
            $('.updateBtn2').hide();
            
        });
        
    // On Blur ItemID Get All Date
        $('#AccountID').blur(function(){ 
            AccountID = $(this).val();
            if(AccountID == ''){
                
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>hr_profile/GetAccountDetailByID",
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
                    
                    if(data == null){
                       
                       
                        $('#opening_b').removeAttr('disabled');
                        $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        
                    }else if(data.Type == 'othercompstaff'){
                        alert('This AccountID Use for Other Company Staff');
                        $('#AccountID').focus();
                       
                        $('#opening_b').removeAttr('disabled');
                        $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else if(data.Type == 'staff'){
                       
                       $('#AccountID').val(data.AccountID);
                       $('#firstname').val(data.firstname);
                       $('#lastname').val(data.lastname);
                       $('#userid').val(data.staffid);
                        $('select[id=SubActGroupID]').val(data.SubActGroupID);
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[id=sldtype]').val(data.SLDTypeID);
                        $('.selectpicker').selectpicker('refresh');
                        
                       $('#email').val(data.email);
                       $('#peremail').val(data.peremail);
                       $('#phonenumber').val(data.phonenumber);
                       $('#altphonenumber').val(data.mobile2);
                       if(data.birthday !== null){
                           var date = data.birthday.substring(0, 10);
                            var date_new = date.split("-").reverse().join("/");
                            $('#birthday').val(date_new);
                       }else{
                           $('#birthday').val('');
                       }
                       var staffid = $('#staffid').val();
                       $('#opening_b').val(data.BAL1);
                        if(staffid !== "3"){
                            $('#opening_b').attr('disabled','disabled');    
                        }
                       $('select[name=state]').val(data.state);
                       $('.selectpicker').selectpicker('refresh');
                       
                       let CityList = data.CityList;
                        $("#city").children().remove();
                        for (var i = 0; i < CityList.length; i++) {
                            $("#city").append('<option value="'+CityList[i]["id"]+'">'+CityList[i]["city_name"]+'</option>');
                        }
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('#city').selectpicker('val', data.city);
                        $('.selectpicker').selectpicker('refresh');
                        
                       $('#zip').val(data.pincode);
                       $('#current_address').val(data.current_address);
                       $('#home_town').val(data.home_town);
                       $('#StationName').val(data.stationName);
                       
                       $('select[name=job_position]').val(data.job_position);
                       $('.selectpicker').selectpicker('refresh');
                       
                       let HeadQurterList = data.HeadQurterList;
                        $("#headqurter").children().remove();
                        for (var i = 0; i < HeadQurterList.length; i++) {
                            $("#headqurter").append('<option value="'+HeadQurterList[i]["id"]+'">'+HeadQurterList[i]["name"]+'</option>');
                        }
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('#headqurter').selectpicker('val', data.headqurter);
                        $('.selectpicker').selectpicker('refresh');
                        
                        /*$('#company_id1').selectpicker('val', data.compList);
                        $('.selectpicker').selectpicker('refresh')*/
                        
                       $('#team_manage').selectpicker('val', data.team_manage);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#sex').selectpicker('val', data.sex);
                       $('.selectpicker').selectpicker('refresh')
                       $('#Pan').val(data.pan_number);
                       $('#Aadhaarno').val(data.aadhar_number);
                       $('#account_number').val(data.account_number);
                       $('#name_account').val(data.name_account);
                       $('#issue_bank').val(data.issue_bank);
                       
                       $('#literacy').selectpicker('val', data.literacy);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#marital_status').selectpicker('val', data.marital_status);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#OfficeID').selectpicker('val', data.OfficeID);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#active').selectpicker('val', data.active);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#app_access').selectpicker('val', data.app_access);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#Movement').selectpicker('val', data.Movement);
                       $('.selectpicker').selectpicker('refresh');
                       if(data.StartDate == '' || data.StartDate == null){
                           $('#datecreated').val('');
                       }else{
                           var date = data.StartDate.substring(0, 10);
                            var date_new = date.split("-").reverse().join("/");
                            $('#datecreated').val(date_new);
                       }
                       
                       $('#DeviceID').val(data.DiveceID);
                       $('#dep_'+data.departmentid).prop('checked', true);
                       $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                    }else if(data.Type == 'client'){
                        $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').removeAttr('disabled');
                        alert("This AccountID Use for other Accounts");
                        $('#AccountID').focus();
                    } 
                }
            });
            }
            
        });
        
        $('.get_AccountID').on('click',function(){ 
            AccountID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>hr_profile/GetAccountDetailByIDPOPUP",
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
                       $('#userid').val(data.staffid);
                       $('#AccountID').val(data.AccountID);
                       $('#firstname').val(data.firstname);
                       $('#lastname').val(data.lastname);
                       
                        $('select[id=SubActGroupID]').val(data.SubActGroupID);
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[id=sldtype]').val(data.SLDTypeID);
                        $('.selectpicker').selectpicker('refresh');
                        
                       $('#email').val(data.email);
                       $('#peremail').val(data.peremail);
                       $('#phonenumber').val(data.phonenumber);
                       $('#altphonenumber').val(data.mobile2);
                       if(data.birthday !== null){
                           var date = data.birthday.substring(0, 10);
                            var date_new = date.split("-").reverse().join("/");
                            $('#birthday').val(date_new);
                       }else{
                           $('#birthday').val('');
                       }
                       $('#opening_b').val(data.BAL1);
                       var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $('#opening_b').attr('disabled','disabled');
                            
                        }
                       $('select[name=state]').val(data.state);
                       $('.selectpicker').selectpicker('refresh');
                       
                       let CityList = data.CityList;
                        $("#city").children().remove();
                        for (var i = 0; i < CityList.length; i++) {
                            $("#city").append('<option value="'+CityList[i]["id"]+'">'+CityList[i]["city_name"]+'</option>');
                        }
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('#city').selectpicker('val', data.city);
                        $('.selectpicker').selectpicker('refresh');
                        
                       $('#zip').val(data.pincode);
                       $('#current_address').val(data.current_address);
                       $('#home_town').val(data.home_town);
                       $('#StationName').val(data.stationName);
                       
                       $('select[name=job_position]').val(data.job_position);
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       
                       let HeadQurterList = data.HeadQurterList;
                        $("#headqurter").children().remove();
                        for (var i = 0; i < HeadQurterList.length; i++) {
                            $("#headqurter").append('<option value="'+HeadQurterList[i]["id"]+'">'+HeadQurterList[i]["name"]+'</option>');
                        }
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('#headqurter').selectpicker('val', data.headqurter);
                        $('.selectpicker').selectpicker('refresh');
                        
                        /*$('#company_id1').selectpicker('val', data.compList);
                        $('.selectpicker').selectpicker('refresh')*/
                        
                       $('#team_manage').selectpicker('val', data.team_manage);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#sex').selectpicker('val', data.sex);
                       $('.selectpicker').selectpicker('refresh')
                       $('#Pan').val(data.pan_number);
                       $('#Aadhaarno').val(data.aadhar_number);
                       $('#account_number').val(data.account_number);
                       $('#name_account').val(data.name_account);
                       $('#issue_bank').val(data.issue_bank);
                       
                       $('#literacy').selectpicker('val', data.literacy);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#marital_status').selectpicker('val', data.marital_status);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#OfficeID').selectpicker('val', data.OfficeID);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#active').selectpicker('val', data.active);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#app_access').selectpicker('val', data.app_access);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#Movement').selectpicker('val', data.Movement);
                       $('.selectpicker').selectpicker('refresh');
                       
                       if(data.StartDate == '' || data.StartDate == null){
                           $('#datecreated').val('');
                       }else{
                           var date = data.StartDate.substring(0, 10);
                            var date_new = date.split("-").reverse().join("/");
                            $('#datecreated').val(date_new);
                       }
                       $('#DeviceID').val(data.DiveceID);
                       $('#dep_'+data.departmentid).prop('checked', true);
                       $('.updateBtn').removeAttr('disabled');
                       $('.saveBtn').removeAttr('disabled');
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                }
            });
            $('#Account_List').modal('hide');
        });
        
    // Save New Item
        $('.saveBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            sldtype = $('#sldtype').val();
            SubActGroupID = $('#SubActGroupID').val();
            firstname = $('#firstname').val();
            lastname = $('#lastname').val();
            opening_b = $('#opening_b').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            peremail = $('#peremail').val();
            state = $('#state').val();
            city = $('#city').val();
            current_address = $('#current_address').val();
            home_town = $('#home_town').val();
            zip = $('#zip').val();
            sex = $('#sex').val();
            Pan = $('#Pan').val();
            Aadhaarno = $('#Aadhaarno').val();
            team_manage = $('#team_manage').val();
            StationName = $('#StationName').val();
            job_position = $('#job_position').val();
            account_number = $('#account_number').val();
            name_account = $('#name_account').val();
            issue_bank = $('#issue_bank').val();
            headqurter = $('#headqurter').val();
            birthday = $('#birthday').val();
            literacy = $('#literacy').val();
            marital_status = $('#marital_status').val();
            active = $('#active').val();
            app_access = $('#app_access').val();
            datecreated = $('#datecreated').val();
            DeviceID = $('#DeviceID').val();
            Movement = $('#Movement').val();
            OfficeID = $('#OfficeID').val();
            password = $('#password').val();
            //Department = $('input[name="departments"]:checked').val();
            //company_id1 = $('#company_id1').val();
            
	        
        if(AccountID == ''){
            alert('please enter AccountID');
            $('#AccountID').focus();
            $('.saveBtn').removeAttr('disabled');
        }/*else if(sldtype == ''){
            alert('please select SLD Type');
            $('.saveBtn').removeAttr('disabled');
            $('#sldtype').focus();
        }*/else if(state == ''){
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
        }/*else if(!$('#vat').val().match('/^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/') && $('#vat').val() !== '')  {
            alert("Enter valid GST no..");
            $('#vat').focus();
        }*/else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
            alert('Enter valid Mobile number');
            $('.saveBtn').removeAttr('disabled');
            $('#phonenumber').focus();
        }else if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#Pan').val() !== ""){
            alert('Enter valid PAN number');
            $('.saveBtn').removeAttr('disabled');
            $('#Pan').focus();
        }else if(!$('#Aadhaarno').val().match('[0-9]{12}') && $('#Aadhaarno').val() !== ""){
            alert('Enter valid Aadhar number');
            $('.saveBtn').removeAttr('disabled');
            $('#Aadhaarno').focus();
        }else if(SubActGroupID == ''){
            alert('please Select Account Subgroup');
            $('.saveBtn').removeAttr('disabled');
            $('#SubActGroupID').focus();
        }else if(job_position == ''){
            alert('please select Position');
            $('.saveBtn').removeAttr('disabled');
            $('#job_position').focus();
        }/*else if(company_id1 == ''){
            alert('please select Company');
            $('#company_id1').focus();
        }*/else {
            
            $.ajax({
                url:"<?php echo admin_url(); ?>hr_profile/SaveAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,sldtype:sldtype,SubActGroupID:SubActGroupID,firstname:firstname,lastname:lastname,phonenumber:phonenumber,
                    altphonenumber:altphonenumber,email:email,opening_b:opening_b,peremail:peremail,state:state,city:city,current_address:current_address,home_town:home_town,
                    zip:zip,sex:sex,Pan:Pan,Aadhaarno:Aadhaarno,team_manage:team_manage,StationName:StationName,job_position:job_position,
                    account_number:account_number,name_account:name_account,issue_bank:issue_bank,headqurter:headqurter,birthday:birthday,literacy:literacy,marital_status:marital_status,active:active,app_access:app_access,datecreated:datecreated,
                    DeviceID:DeviceID,Movement:Movement,OfficeID:OfficeID,password:password,/*Department:Department,company_id1:company_id1*/
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
                       alert('Record created successfully...');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#userid').val('');
                       $('select[name=sldtype]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=SubActGroupID]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#email').val('');
                       $('#peremail').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                       $('#birthday').val(today);
                       $('#opening_b').val(0.00);
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#zip').val('');
                       $('#current_address').val('');
                       $('#home_town').val('');
                       $('#StationName').val('');
                       
                       $('select[name=job_position]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#headqurter").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       /*$('#company_id1').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')*/
                        
                       $('#team_manage').selectpicker('val', '');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#sex').selectpicker('val', 'male');
                       $('.selectpicker').selectpicker('refresh')
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                       $('#account_number').val('');
                       $('#name_account').val('');
                       $('#issue_bank').val('');
                       
                       $('#literacy').selectpicker('val', '');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#marital_status').selectpicker('val', 'single');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#OfficeID').selectpicker('val', '');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#active').selectpicker('val', '1');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#app_access').selectpicker('val', 'No');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#Movement').selectpicker('val', 'No');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#datecreated').val(today);
                       $('#DeviceID').val('');
                       //$('.departments').prop('checked', false);
                       $('#opening_b').removeAttr('disabled');
                       $('.saveBtn').removeAttr('disabled');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                        $('.saveBtn').removeAttr('disabled');
                       alert_float('warning', 'Something went wrong...');
                   }
                }
            });   
        }
            
        });
    // Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            userID = $('#userid').val();
            AccountID = $('#AccountID').val();
            sldtype = $('#sldtype').val();
            SubActGroupID = $('#SubActGroupID').val();
            firstname = $('#firstname').val();
            lastname = $('#lastname').val();
            opening_b = $('#opening_b').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            peremail = $('#peremail').val();
            state = $('#state').val();
            city = $('#city').val();
            current_address = $('#current_address').val();
            home_town = $('#home_town').val();
            zip = $('#zip').val();
            sex = $('#sex').val();
            Pan = $('#Pan').val();
            Aadhaarno = $('#Aadhaarno').val();
            team_manage = $('#team_manage').val();
            StationName = $('#StationName').val();
            job_position = $('#job_position').val();
            account_number = $('#account_number').val();
            name_account = $('#name_account').val();
            issue_bank = $('#issue_bank').val();
            headqurter = $('#headqurter').val();
            birthday = $('#birthday').val();
            literacy = $('#literacy').val();
            marital_status = $('#marital_status').val();
            active = $('#active').val();
            app_access = $('#app_access').val();
            datecreated = $('#datecreated').val();
            DeviceID = $('#DeviceID').val();
            Movement = $('#Movement').val();
            OfficeID = $('#OfficeID').val();
            password = $('#password').val();
            //Department = $('input[name="departments"]:checked').val();
            //company_id1 = $('#company_id1').val();
            
        if(AccountID == ''){
            alert('please enter AccountID');
            $('.updateBtn').removeAttr('disabled');
            $('#AccountID').focus();
        }/*else if(sldtype == ''){
            alert('please select SLD Type');
            $('.updateBtn').removeAttr('disabled');
            $('#sldtype').focus();
        }*/else if(state == ''){
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
        }/*else if(!$('#vat').val().match('/^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/') && $('#vat').val() !== '')  {
            alert("Enter valid GST no..");
            $('#vat').focus();
        }*/else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
            alert('Enter valid Mobile number');
            $('.updateBtn').removeAttr('disabled');
            $('#phonenumber').focus();
        }else if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#Pan').val() !== ""){
            alert('Enter valid PAN number');
            $('.updateBtn').removeAttr('disabled');
            $('#Pan').focus();
        }else if(!$('#Aadhaarno').val().match('[0-9]{12}') && $('#Aadhaarno').val() !== ""){
            alert('Enter valid Aadhar number');
            $('.updateBtn').removeAttr('disabled');
            $('#Aadhaarno').focus();
        }else if(SubActGroupID == ''){
            alert('please Select Account Subgroup');
            $('.updateBtn').removeAttr('disabled');
            $('#SubActGroupID').focus();
        }else if(job_position == ''){
            alert('please select Position');
            $('.updateBtn').removeAttr('disabled');
            $('#job_position').focus();
        }/*else if(company_id1 == ''){
            alert('please select Company');
            $('#company_id1').focus();
        }*/else {
            $.ajax({
                url:"<?php echo admin_url(); ?>hr_profile/UpdateAccountID",
                dataType:"JSON",
                method:"POST",
                data:{userID:userID,AccountID:AccountID,sldtype:sldtype,SubActGroupID:SubActGroupID,firstname:firstname,lastname:lastname,phonenumber:phonenumber,
                    altphonenumber:altphonenumber,email:email,opening_b:opening_b,peremail:peremail,state:state,city:city,current_address:current_address,home_town:home_town,
                    zip:zip,sex:sex,Pan:Pan,Aadhaarno:Aadhaarno,team_manage:team_manage,StationName:StationName,job_position:job_position,
                    account_number:account_number,name_account:name_account,issue_bank:issue_bank,headqurter:headqurter,birthday:birthday,literacy:literacy,marital_status:marital_status,active:active,app_access:app_access,datecreated:datecreated,
                    DeviceID:DeviceID,Movement:Movement,OfficeID:OfficeID,password:password,/*Department:Department,company_id1:company_id1*/
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
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#userid').val('');
                       $('select[name=sldtype]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=SubActGroupID]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#email').val('');
                       $('#peremail').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                       $('#birthday').val(today);
                       $('#opening_b').val(0.00);
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#zip').val('');
                       $('#current_address').val('');
                       $('#home_town').val('');
                       $('#StationName').val('');
                       
                       $('select[name=job_position]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#headqurter").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       /*$('#company_id1').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')*/
                        
                       $('#team_manage').selectpicker('val', '');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#sex').selectpicker('val', 'male');
                       $('.selectpicker').selectpicker('refresh')
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                       $('#account_number').val('');
                       $('#name_account').val('');
                       $('#issue_bank').val('');
                       
                       $('#literacy').selectpicker('val', '');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#marital_status').selectpicker('val', 'single');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#OfficeID').selectpicker('val', '');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#active').selectpicker('val', '1');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#app_access').selectpicker('val', 'No');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#Movement').selectpicker('val', 'No');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('#datecreated').val(today);
                       $('#DeviceID').val('');
                       //$('.departments').prop('checked', false);
                       $('#opening_b').removeAttr('disabled');
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        $('.updateBtn').removeAttr('disabled');
                   }else{
                       alert('warning', 'there is no changes');
                       $('.updateBtn').removeAttr('disabled');
                   }
                }
            });
        }
            
        });
    
    $('#state').on('change', function() {
				var StateID = $(this).val();
				//alert(roleid);
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
	
	$('#state').on('change', function() {
				var id = $(this).val();
				var url = "<?php echo base_url(); ?>admin/hr_profile/select_quarter";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                           $('#head_quarter_modal input[name="state_id"]').val(id);
                               $("#headqurter").children().remove();
                                $.each(data, function (index, value) {
                                    $('#headqurter').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                                $("#headqurter").selectpicker("refresh");
                        }
                    });
			});
			
	var app_access = $("#app_access").val();
	var erp_access = $("#login_access").val();
	if(app_access == "No"){
	    $("#password_field").css("display", "none");
	}	
	$('#app_access').on('change', function() {
		var app_access = $(this).val();
				
		if(app_access == "Yes"){
			$("#password_field").css("display", "");
		}else {
			$//("").css("display", "inline-table");
			$("#password_field").css("display", "none");
		}	
	});
			
	$('#shipping_state').on('change', function() {
				var StateID = $(this).val();
				//alert(roleid);
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
   $('#MaxCrdAmt,#kms,.opening_bal').on('keypress',function (event) {
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

.btn-bottom-toolbar{
    width: 83% !important;
}

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

.itemdivisioncomp .btn-default {
    height: 25px !important;
    padding: 0px 10px !important;
    font-size: 12px !important;
}

    .table-Account_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Account_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Account_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>