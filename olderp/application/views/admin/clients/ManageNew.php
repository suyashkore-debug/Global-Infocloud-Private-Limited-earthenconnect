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
                    <div class="col-md-3">
                        <?php  ?>
                        <div class="form-group" app-field-wrapper="AccountID">
                            <small class="req text-danger">* </small>
                            <label for="AccountID" class="control-label">AccountID</label>
                            <input type="text" id="AccountID" name="AccountID" class="form-control" value="" >
                            <?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
                            <input type="hidden" name="staffid" value="<?php echo $staff_user_id; ?>" id="staffid">
                            <input type="hidden" name="PlantID" value="<?php echo $this->session->userdata('root_company');?>" id="PlantID">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php //echo render_input('AccoountName','Account Name'); ?>
                        <div class="form-group" app-field-wrapper="AccoountName">
                            <small class="req text-danger">* </small>
                            <label for="AccoountName" class="control-label">Account Name</label>
                            <input type="text" id="AccoountName" name="AccoountName" class="form-control" value="" >
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input('firstname','Firstname','','text'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input('lastname','Lastname','','text'); ?>
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
                        <div class="form-group" app-field-wrapper="phonenumber">
                            <small class="req text-danger">* </small>
                            <label for="phonenumber" class="control-label">Mobile Number</label>
                            <input type="text" id="phonenumber" name="phonenumber" class="form-control" value="" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
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
                        <div class="form-group" app-field-wrapper="vat">
                            <label for="vat" class="control-label">GST Number</label>
                            <input type="text" id="vat" name="vat" class="form-control" pattern="([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([0-9A-Za-z]){2}" maxlength="15" minlength="15" value="<?= $value; ?>">
                            <span class="gst_denger" style="color:red;"></span>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php
                            //echo render_select('groups_in',$groups,array('id',array('name')),'customer_groups',$selected,array('data-actions-box'=>false),array(),'','',array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        ?>
                        <div class="form-group" app-field-wrapper="groups_in">
                            <small class="req text-danger">* </small>
                            <label for="groups_in" class="form-label">Distributor Type</label>
                            <select name="groups_in" id="groups_in" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
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
                        <?php
                            //echo render_select( 'state',$state,array( 'short_name',array( 'state_name')), 'client_state','',array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        ?>
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
                    
                    <div class="col-md-3">
                        <div class="form-group">
                           <small class="req text-danger">* </small>
                            <label for="city" class="control-label">City</label>
                            <select class="form-control city selectpicker" data-width="100%" data-none-selected-text="Non Selected" name="city" id="city" data-live-search="true">
                                <option value="">Select city name</option>
                            </select>
                                
                        </div>
                    </div>
                <div class="clearfix"></div>    
                    
                    <div class="col-md-3">
                        <?php echo render_input( 'address', 'Address 1'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input( 'Address3', 'Address 2'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="zip">
                            <label for="zip" class="control-label">Pin Code</label>
                            <input type="text"  name="zip" id="zip" class="form-control" onchange="validateZipCode" value="" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                        </div>
                    </div> 
                    
                    <div class="col-md-3">
                        <?php $countries= get_all_countries();
                         $customer_default_country = get_option('customer_default_country');
                         $selected =( isset($client) ? $customer_default_country : $customer_default_country);
                         echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                         ?>
                    </div>
                    
                <div class="clearfix"></div> 
               <br>
               <hr>
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="kms">
                            <label for="kms" class="control-label">Kms</label>
                            <input id="kms" type="text" maxlength="7"  name="kms" class="form-control" value="" aria-invalid="false">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="FLNO1">
                            <label for="FLNO1" class="control-label">Food Licence No</label>
                            <input type="text" maxlength="14" minlength="14" id="FLNO1" name="FLNO1" class="form-control" value="" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="Pan"> 
                            <label for="Pan" class="control-label">PAN number</label>
                            <input type="text" maxlength="10" minlength="10" name="Pan" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="Pan" class="form-control" 
                            value="">
                            <span class="pan_denger" style="color:red;"></span>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="Aadhaarno">
                            <label for="aadhaar" class="control-label">Aadhar number</label>
                            <input type="text" maxlength="12" minlength="12"  name="Aadhaarno" pattern="[0-9] {12}" id="Aadhaarno" class="form-control numbersOnly" onkeypress="return isNumber(event)" value="">
                            <span class="aadhar_denger" style="color:red;"></span>
                        </div>
                    </div>
                    
                <div class="clearfix"></div>
            
                    <div class="col-md-3">
                        <label for="istcs">TCS</label>
                        <select name="istcs" id="istcs" class="selectpicker form-control tcs_type">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_date_input( 'TcsStartDate1', 'TCS Date','','text'); ?>
                        <input type="hidden" name="TcsStartDate" value="">
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="MaxCrdAmt">
                            <label for="MaxCrdAmt" class="control-label">Max.Credit Amt</label>
                            <input type="text" id="MaxCrdAmt" name="MaxCrdAmt" class="form-control numbersOnly" value="">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="Blockyn">Block A/C</label>
                        <select name="Blockyn" id="Blockyn" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="N" >No</option>
                            <option value="Y" >Yes</option>
                        </select>
                    </div>
                    
                <div class="clearfix"></div>
                    <div class="col-md-3">
                        <label for="SalesFrequency">Sales Frquency</label>
                        <select name="SalesFrequency" id="SalesFrequency" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="0">Weekly</option>
                            <option value="1">Bi-Weekly</option>
                            <option value="2">Monthly</option>
                            <option value="3">Quaterly</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="location_type">
                            <small class="req text-danger">* </small>
                            <label for="location_type" class="control-label">Location Type</label>
                            <select name="location_type" id="location_type" class="selectpicker form-control" data-width="100%"  data-live-search="true">
                                <option value="3" >not Defined</option>
                                <option value="1" >Local</option>
                                <option value="2" >OutStation</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="BalancesYN">Balance on bill</label>
                        <select name="BalancesYN" id="BalancesYN" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="Y" >Yes</option>
                            <option value="N" >No</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="CtrlAccountID">
                            <label for="CtrlAccountID" class="control-label">Ctrl AccountID</label>
                            <input type="text" id="CtrlAccountID" name="CtrlAccountID" class="form-control" value="">
                            <span id="lblError2" style="color: red"></span>
                        </div>
                    </div>
                    
                <div class="clearfix"></div>
                    <div class="col-md-3">
                        <?php echo render_input( 'StationName', 'Station Name','','text'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="ActSalestype">
                            <small class="req text-danger">* </small>
                            <label for="ActSalestype" class="form-label">Sales Type</label>
                            <select name="ActSalestype" id="ActSalestype" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                              
                              <option value="Sales">Sales</option>
                              <option value="CNF">CNF</option>
                              <option value="StockTransfer">StockTransfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="route">
                            <small class="req text-danger">* </small>
                            <label for="route" class="form-label">Route</label>
                            <select name="route[]" id="route" class="selectpicker form-control" multiple data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="">Non Selected</option>
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
                        <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
                          <?php
                             $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                             foreach($currencies as $currency){
                                  $def = $currency['isdefault'];
                                  if($def=="1") {
                                       $selected = $currency['id'];
                                  }
                             }
                            // Do not remove the currency field from the customer profile!
                             echo render_select('default_currency',$currencies,array('id','name','symbol'),'invoice_add_edit_currency',$selected,$s_attrs); ?>
                    </div> 
                <div class="clearfix"></div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="website"><?php echo _l('client_website'); ?></label>
                            <div class="input-group">
                                <input type="text" name="website" id="website" value="<?php echo $client->website; ?>" class="form-control">
                                <div class="input-group-addon">
                                   <span><a href="<?php echo maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                                </div>
                            </div>
                         </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="bill_till_bal">
                            <label for="bill_till_bal" class="form-label">Bill Till Bal.</label>
                            <select name="bill_till_bal" id="bill_till_bal" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="N" >No</option>
                                <option value="Y" >Yes</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="active">
                            <label for="active" class="form-label">Status</label>
                            <select name="active" id="active" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="1" >Active</option>
                                <option value="0" >InActive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php $value = date('Y/m/d');?>
                        <?php echo render_date_input( 'StartDate', 'Start Date',$value,'text'); ?>
                    </div>
                <div class="clearfix"></div>
                <br>
                <hr>
                
                    <!--<div class="col-md-12">
                        <h5 class="no-mtop"> 
                            <?php echo _l('shipping_address'); ?>&nbsp;&nbsp;<input type="checkbox" class="customer-copy-billing-address1"><small class="font-medium-xs">&nbsp;<?php echo _l('customer_billing_same_as_profile'); ?></small> 
                        </h5>
                    </div>-->
                <div class="clearfix"></div>  
                
                    <div class="col-md-3">
                        <?php 
                            $all_state = get_all_state();
                        ?>
                        <?php echo render_select( 'shipping_state',$all_state,array( 'short_name',array( 'state_name')), 'shipping_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="shipping_city" class="control-label"> Shipping City</label>
                            <select class="form-control shipping_city selectpicker " data-width="100%" data-none-selected-text="Non Selected" data-live-search="true" name="shipping_city" id="shipping_city" >
                                <option value="">Select city name</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input( 'shipping_street', 'Address',''); ?>
                    </div>
                    
                    <div class="col-md-3">
                        
                        <div class="form-group" app-field-wrapper="shipping_zip">
                            <label for="shipping_zip" class="control-label">Pin Code</label>
                            <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" onchange="validateZipCode" value="" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                <hr />
                
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <table class="table scroll-responsive">
                            <thead>
                                <tr>
                                    <th style="color:#fff;">#</th>
                                    <th style="color:#fff;">Item Division</th>
                                    <th style="color:#fff;">Company</th>
                                </tr>
                                 
                            </thead>
                            <tbody>
                                <?php foreach($itemdivision as $item_division){ ?>
                                <tr>
                                    <td><div class="checkbox">
                                         <input class = "itemdiv" type="checkbox" name="itemdiv" id = "itemdiv<?php echo $item_division["id"];?>" value="<?php echo $item_division["id"];?>" <?php foreach($acc_item_div_com_id as $key => $value) { foreach($value as $key1 => $value1) { if($key1 == $item_division["id"]){{ echo "checked"; }}}}?>><label></label></div></td>
                                    <td><?php echo $item_division["name"]; ?></td>
                                    <td>
                                        <select name="itemdivisioncomp" id="itemdivisioncomp<?php echo $item_division["id"];?>" class="form-control selectpicker itemdivisioncomp">
                                            <option value="">Select</option>
                                             <?php foreach($rootcompany as $r_company){ ?>
                                            <option value="<?php echo $r_company["id"]; ?>" <?php foreach($acc_item_div_com_id as $key => $value) { foreach($value as $key1 => $value1) { if($key1 == $item_division["id"] && $value1 == $r_company["id"]){{ echo "selected"; }}}}?>><?php echo $r_company["comp_short"];?></option>
                                             
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
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
                                 <th style="color:#fff;width: 100px;">Opening</th>
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
                                            <select name="company_assigned_staff" id="company_assigned_staff<?php echo $r_company["id"];?>" class="form-control selectpicker" tabindex="-98" data-none-selected-text="Non selected" data-live-search="true">
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
                                                        <option value="<?php echo $ssvalue['staffid'];?>"><?php echo $ssvalue["firstname"]." ".$ssvalue["lastname"]?></option>
                                                        <?php
                                                    }
                                                } ?>
                                            
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                         
                                    <?php
                                    $staff_user_id = $this->session->userdata('staff_user_id');
                                    ?>
                                        <input class="opening_bal" type="text" name="opening_bal" id="opening_bal<?php echo $r_company["id"];?>" value="" class="form-control opening_bal" <?php if(isset($client) && $staff_user_id !== "3"){ echo "disabled";}?> style="height: 25px;font-size: 12px;">
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
                    <div class="clearfix"></div>
                    <br><br>
                <div class="row">
                    <div class="col-md-12">
                        <?php if (has_permission('customers', '', 'create')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                        <?php
                        }?>
                        
                        <?php if (has_permission('customers', '', 'edit')) {
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
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">AccountID <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>
                                            <th style="text-align:left;">Account Name</th>
                                            <th style="text-align:left;">Distributor Type</th>
                                            <th style="text-align:left;">Station</th>
                                            <th style="text-align:left;">State</th>
                                            <th style="text-align:left;">City</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($table_data as $key => $value) {
                                    ?>
                                        <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                            <td><?php echo $value['AccountID'];?></td>
                                            <td><?php echo $value['company'];?></td>
                                            <td><?php echo $value["customerGroups"];?></td>
                                            <td><?php echo $value["StationName"];?></td>
                                            <td><?php echo $value["state"];?></td>
                                            <td><?php echo $value["city"];?></td>
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
        if(SessionID !== ""){
            $.ajax({
                url:"<?php echo admin_url(); ?>clients/GetAccountDetailByIDAllPlant",
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
                       
                       $('#AccoountName').val('');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#address').val('');
                       $('#Address3').val('');
                       $('#zip').val('');
                       $('#kms').val('');
                       $('#FLNO1').val('');
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                        $('#TcsStartDate1').val('');
                       $('#MaxCrdAmt').val('');
                       $('#CtrlAccountID').val('');
                       $('#StationName').val('');
                       $('#website').val('');
                       var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                       $('#StartDate').val(today);
                       $('#shipping_street').val('');
                       $('#shipping_zip').val('');
                       $('.itemdiv').prop('checked', false);
                       $('select[name=itemdivisioncomp]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('.company_assigned').prop('checked', false);
                        $('select[name=company_assigned_staff]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=title]').val('Owner');
                         $('.selectpicker').selectpicker('refresh');
                
                        $('.opening_bal').val('');
                        $('#route').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')
                        
                        $("#city").children().remove();
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=location_type]').val('3');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=groups_in]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=istcs]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=BalancesYN]').val('Y');
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", false);
                        }
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else if(data.Type == 'staff'){
                        alert('This AccountID Use for Staff');
                        
                        $('#AccountID').val('');
                       $('#AccoountName').val('');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#address').val('');
                       $('#Address3').val('');
                       $('#zip').val('');
                       $('#kms').val('');
                       $('#FLNO1').val('');
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                        $('#TcsStartDate1').val('');
                       $('#MaxCrdAmt').val('');
                       $('#CtrlAccountID').val('');
                       $('#StationName').val('');
                       $('#website').val('');
                       var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                       $('#StartDate').val(today);
                       $('#shipping_street').val('');
                       $('#shipping_zip').val('');
                       $('.itemdiv').prop('checked', false);
                       $('select[name=itemdivisioncomp]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('company_assigned').prop('checked', false);
                        
                        $('select[name=company_assigned_staff]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=title]').val('Owner');
                        $('.selectpicker').selectpicker('refresh');
                
                        $('.opening_bal').val('');
                        $('#route').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')
                        
                        $("#city").children().remove();
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=location_type]').val('3');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=groups_in]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=istcs]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=BalancesYN]').val('Y');
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", false);
                        }
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else if(data.Type == 'client' && data.SubActGroupID == '60001004'){
                        var selectedPlant = $('#PlantID').val();
                       if(data.PlantID == selectedPlant){
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
                           $('#kms').val(data.kms);
                           $('#FLNO1').val(data.FLNO1);
                           $('#Pan').val(data.Pan);
                           $('#Aadhaarno').val(data.Aadhaarno);
                           if(data.TcsStartDate == null || data.TcsStartDate == '' || data.TcsStartDate == "0000-00-00 00:00:00"){
                               $('#TcsStartDate1').val('');
                           }else{
                               var date = data.TcsStartDate.substring(0, 10)
                                var date_new = date.split("-").reverse().join("/");
                               $('#TcsStartDate1').val(date_new);
                           }
                           $('#MaxCrdAmt').val(data.MaxCrdAmt);
                           $('#CtrlAccountID').val(data.CtrlAccountID);
                           $('#StationName').val(data.StationName);
                           $('#website').val(data.website);
                           var date = data.StartDate.substring(0, 10)
                            var date_new = date.split("-").reverse().join("/");
                           $('#StartDate').val(date_new);
                           $('#shipping_street').val(data.shipping_street);
                           $('#shipping_zip').val(data.shipping_zip);
                           
                           let ItemDivArray = data.ItemDiv;
                           for(var count = 0; count < ItemDivArray.length; count++)
                            {
                                var ItemDivID = ItemDivArray[count].ItemDivID;
                                $('#itemdiv'+ItemDivID+'').prop('checked', true);
                                
                                var PlantID = ItemDivArray[count].plant_assign
                                $('select[id=itemdivisioncomp'+ItemDivID+']').val(PlantID);
                                $('.selectpicker').selectpicker('refresh');
                            }
                            
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
                           
                           $('select[name=title]').val(data.title);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=groups_in]').val(data.DistributorType);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=state]').val(data.state);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=istcs]').val(data.istcs);
                           $('.selectpicker').selectpicker('refresh');
                           
                           
                           $('select[name=BalancesYN]').val(data.BalancesYN);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=ActSalestype]').val(data.ActSalestype);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=route]').val('3');
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
                            
                            var staffid = $('#staffid').val();
                            if(staffid !== "3"){
                                $(".opening_bal").prop("readonly", true);
                            }
                           
                           $('.saveBtn').hide();
                           $('.updateBtn').show();
                           $('.saveBtn2').hide();
                           $('.updateBtn2').show();
                       }else{
                           $('#AccountID').val('');
                           alert("This AccountID Use for other Plant");
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                       }
                       
                    }else if(data.Type == 'client' && data.SubActGroupID !== '60001004'){
                        $('#AccountID').val('');
                        alert("This AccountID Use for other Accounts");
                    } 
                }
            });
        }
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $("#AccountID").dblclick(function(){
            $('#myInput1').focus();
            $('#Account_List').modal('show');
            
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
    // AccountID Typing Validation
        $("#CtrlAccountID").keypress(function (e) {
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
    
    // Empty and open create mode
        $("#AccountID").focus(function(){
                $('#AccountID').val('');
                $('#AccoountName').val('');
                $('#firstname').val('');
                $('#lastname').val('');
                $('#phonenumber').val('');
                $('#altphonenumber').val('');
                $('#email').val('');
                $('#vat').val('');
                $('#address').val('');
                $('#Address3').val('');
                $('#zip').val('');
                $('#kms').val('');
                $('#FLNO1').val('');
                $('#Pan').val('');
                $('#Aadhaarno').val('');
                $('#TcsStartDate1').val('');
                $('#MaxCrdAmt').val('');
                $('#CtrlAccountID').val('');
                $('#StationName').val('');
                $('#website').val('');
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = dd + '/' + mm + '/' + yyyy;
                $('#StartDate').val(today);
                $('#shipping_street').val('');
                $('#shipping_zip').val('');
                $('.itemdiv').prop('checked', false);
                $('select[name=itemdivisioncomp]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $('.company_assigned').prop('checked', false);
                
                $('select[name=company_assigned_staff]').val('');
                $('.selectpicker').selectpicker('refresh');
                
                $('select[name=title]').val('Owner');
                $('.selectpicker').selectpicker('refresh');
                $('.opening_bal').val('');
                $('#route').selectpicker('val', '');
                $('.selectpicker').selectpicker('refresh');
                                
                $("#city").children().remove();
                $('.selectpicker').selectpicker('refresh');
                                
                $('select[name=location_type]').val('3');
                $('.selectpicker').selectpicker('refresh');
                
                       
                $('select[name=groups_in]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=state]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=istcs]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                       
                $('select[name=BalancesYN]').val('Y');
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
                
                var staffid = $('#staffid').val();
                if(staffid !== "3"){
                    $(".opening_bal").prop("readonly", false);
                }
                               
                $('.saveBtn').show();
                $('.updateBtn').hide();
                $('.saveBtn2').show();
                $('.updateBtn2').hide();
            
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
            $('#AccountID').val('');
                $('#AccoountName').val('');
                $('#firstname').val('');
                $('#lastname').val('');
                $('#phonenumber').val('');
                $('#altphonenumber').val('');
                $('#email').val('');
                $('#vat').val('');
                $('#address').val('');
                $('#Address3').val('');
                $('#zip').val('');
                $('#kms').val('');
                $('#FLNO1').val('');
                $('#Pan').val('');
                $('#Aadhaarno').val('');
                $('#TcsStartDate1').val('');
                $('#MaxCrdAmt').val('');
                $('#CtrlAccountID').val('');
                $('#StationName').val('');
                $('#website').val('');
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = dd + '/' + mm + '/' + yyyy;
                $('#StartDate').val(today);
                $('#shipping_street').val('');
                $('#shipping_zip').val('');
                $('.itemdiv').prop('checked', false);
                $('select[name=itemdivisioncomp]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $('.company_assigned').prop('checked', false);
                        
                $('select[name=company_assigned_staff]').val('');
                $('.selectpicker').selectpicker('refresh');
                
                $('select[name=title]').val('Owner');
                $('.selectpicker').selectpicker('refresh');
                
                $('.opening_bal').val('');
                $('#route').selectpicker('val', '');
                $('.selectpicker').selectpicker('refresh');
                                
                $("#city").children().remove();
                $('.selectpicker').selectpicker('refresh');
                                
                $('select[name=location_type]').val('3');
                $('.selectpicker').selectpicker('refresh');
                
                       
                $('select[name=groups_in]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=state]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=istcs]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                       
                $('select[name=BalancesYN]').val('Y');
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
                
                var staffid = $('#staffid').val();
                if(staffid !== "3"){
                    $(".opening_bal").prop("readonly", false);
                }
                               
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
                url:"<?php echo admin_url(); ?>clients/GetAccountDetailByIDAllPlant",
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
                       
                       $('#AccoountName').val('');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#address').val('');
                       $('#Address3').val('');
                       $('#zip').val('');
                       $('#kms').val('');
                       $('#FLNO1').val('');
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                        $('#TcsStartDate1').val('');
                       $('#MaxCrdAmt').val('');
                       $('#CtrlAccountID').val('');
                       $('#StationName').val('');
                       $('#website').val('');
                       var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                       $('#StartDate').val(today);
                       $('#shipping_street').val('');
                       $('#shipping_zip').val('');
                       $('.itemdiv').prop('checked', false);
                       $('select[name=itemdivisioncomp]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('.company_assigned').prop('checked', false);
                        $('select[name=company_assigned_staff]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=title]').val('Owner');
                         $('.selectpicker').selectpicker('refresh');
                
                        $('.opening_bal').val('');
                        $('#route').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')
                        
                        $("#city").children().remove();
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=location_type]').val('3');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=groups_in]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=istcs]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=BalancesYN]').val('Y');
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", false);
                        }
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else if(data.Type == 'staff'){
                        alert('This AccountID Use for Staff');
                        
                        $('#AccountID').val('');
                       $('#AccoountName').val('');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#address').val('');
                       $('#Address3').val('');
                       $('#zip').val('');
                       $('#kms').val('');
                       $('#FLNO1').val('');
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                        $('#TcsStartDate1').val('');
                       $('#MaxCrdAmt').val('');
                       $('#CtrlAccountID').val('');
                       $('#StationName').val('');
                       $('#website').val('');
                       var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                       $('#StartDate').val(today);
                       $('#shipping_street').val('');
                       $('#shipping_zip').val('');
                       $('.itemdiv').prop('checked', false);
                       $('select[name=itemdivisioncomp]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('company_assigned').prop('checked', false);
                        
                        $('select[name=company_assigned_staff]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=title]').val('Owner');
                        $('.selectpicker').selectpicker('refresh');
                
                        $('.opening_bal').val('');
                        $('#route').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')
                        
                        $("#city").children().remove();
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=location_type]').val('3');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=groups_in]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=istcs]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=BalancesYN]').val('Y');
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", false);
                        }
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else if(data.Type == 'client' && data.SubActGroupID == '60001004'){
                        var selectedPlant = $('#PlantID').val();
                       if(data.PlantID == selectedPlant){
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
                           $('#kms').val(data.kms);
                           $('#FLNO1').val(data.FLNO1);
                           $('#Pan').val(data.Pan);
                           $('#Aadhaarno').val(data.Aadhaarno);
                           if(data.TcsStartDate == null || data.TcsStartDate == '' || data.TcsStartDate == "0000-00-00 00:00:00"){
                               $('#TcsStartDate1').val('');
                           }else{
                               var date = data.TcsStartDate.substring(0, 10)
                                var date_new = date.split("-").reverse().join("/");
                               $('#TcsStartDate1').val(date_new);
                           }
                           $('#MaxCrdAmt').val(data.MaxCrdAmt);
                           $('#CtrlAccountID').val(data.CtrlAccountID);
                           $('#StationName').val(data.StationName);
                           $('#website').val(data.website);
                           var date = data.StartDate.substring(0, 10)
                            var date_new = date.split("-").reverse().join("/");
                           $('#StartDate').val(date_new);
                           $('#shipping_street').val(data.shipping_street);
                           $('#shipping_zip').val(data.shipping_zip);
                           
                           let ItemDivArray = data.ItemDiv;
                           for(var count = 0; count < ItemDivArray.length; count++)
                            {
                                var ItemDivID = ItemDivArray[count].ItemDivID;
                                $('#itemdiv'+ItemDivID+'').prop('checked', true);
                                
                                var PlantID = ItemDivArray[count].plant_assign
                                $('select[id=itemdivisioncomp'+ItemDivID+']').val(PlantID);
                                $('.selectpicker').selectpicker('refresh');
                            }
                            
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
                           
                           $('select[name=title]').val(data.title);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=groups_in]').val(data.DistributorType);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=state]').val(data.state);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=istcs]').val(data.istcs);
                           $('.selectpicker').selectpicker('refresh');
                           
                           
                           $('select[name=BalancesYN]').val(data.BalancesYN);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=ActSalestype]').val(data.ActSalestype);
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=route]').val('3');
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
                            
                            var staffid = $('#staffid').val();
                            if(staffid !== "3"){
                                $(".opening_bal").prop("readonly", true);
                            }
                           
                           $('.saveBtn').hide();
                           $('.updateBtn').show();
                           $('.saveBtn2').hide();
                           $('.updateBtn2').show();
                       }else{
                           $('#AccountID').val('');
                           alert("This AccountID Use for other Plant");
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                       }
                       
                    }else if(data.Type == 'client' && data.SubActGroupID !== '60001004'){
                        $('#AccountID').val('');
                        alert("This AccountID Use for other Accounts");
                    } 
                }
            });
            }
            
        });
        
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
                       $('#kms').val(data.kms);
                       $('#FLNO1').val(data.FLNO1);
                       $('#Pan').val(data.Pan);
                       $('#Aadhaarno').val(data.Aadhaarno);
                       if(data.TcsStartDate == null || data.TcsStartDate == '' || data.TcsStartDate == "0000-00-00 00:00:00"){
                           $('#TcsStartDate1').val('');
                       }else{
                           var date = data.TcsStartDate.substring(0, 10)
                            var date_new = date.split("-").reverse().join("/");
                           $('#TcsStartDate1').val(date_new);
                       }
                       $('#MaxCrdAmt').val(data.MaxCrdAmt);
                       $('#CtrlAccountID').val(data.CtrlAccountID);
                       $('#StationName').val(data.StationName);
                       $('#website').val(data.website);
                       var date = data.StartDate.substring(0, 10)
                        var date_new = date.split("-").reverse().join("/");
                       $('#StartDate').val(date_new);
                       $('#shipping_street').val(data.shipping_street);
                       $('#shipping_zip').val(data.shipping_zip);
                       
                       let ItemDivArray = data.ItemDiv;
                       for(var count = 0; count < ItemDivArray.length; count++)
                        {
                            var ItemDivID = ItemDivArray[count].ItemDivID;
                            $('#itemdiv'+ItemDivID+'').prop('checked', true);
                            
                            var PlantID = ItemDivArray[count].plant_assign
                            $('select[id=itemdivisioncomp'+ItemDivID+']').val(PlantID);
                            $('.selectpicker').selectpicker('refresh');
                        }
                        
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
                       
                       $('select[name=title]').val(data.title);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=groups_in]').val(data.DistributorType);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val(data.state);
                       $('.selectpicker').selectpicker('refresh');
                       
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", true);
                        }
                       
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
            AccoountName = $('#AccoountName').val();
            firstname = $('#firstname').val();
            lastname = $('#lastname').val();
            title = $('#title').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            vat = $('#vat').val();
            groups_in = $('#groups_in').val();
            state = $('#state').val();
            city = $('#city').val();
            address = $('#address').val();
            Address3 = $('#Address3').val();
            zip = $('#zip').val();
            kms = $('#kms').val();
            FLNO1 = $('#FLNO1').val();
            Pan = $('#Pan').val();
            Aadhaarno = $('#Aadhaarno').val();
            istcs = $('#istcs').val();
            TcsStartDate1 = $('#TcsStartDate1').val();
            MaxCrdAmt = $('#MaxCrdAmt').val();
            Blockyn = $('#Blockyn').val();
            SalesFrequency = $('#SalesFrequency').val();
            location_type = $('#location_type').val();
            BalancesYN = $('#BalancesYN').val();
            CtrlAccountID = $('#CtrlAccountID').val();
            StationName = $('#StationName').val();
            ActSalestype = $('#ActSalestype').val();
            route = $('#route').val();
            website = $('#website').val();
            bill_till_bal = $('#bill_till_bal').val();
            active = $('#active').val();
            StartDate = $('#StartDate').val();
            shipping_state = $('#shipping_state').val();
            shipping_city = $('#shipping_city').val();
            shipping_street = $('#shipping_street').val();
            shipping_zip = $('#shipping_zip').val();
            
            var ItemDivArray = new Array();
            var i = 1;
            $.each($("input[name='itemdiv']:checked"), function(){
                var val = $(this).val();
                var id= 'itemdiv'+val;
                var itemdivID = document.getElementById(id).value;
                var id2= 'itemdivisioncomp'+val;
                var ItemDivCompID = document.getElementById(id2).value;
                var ii = i - 1;
                ItemDivArray[ii]=new Array();
                ItemDivArray[ii][0]=itemdivID;
                ItemDivArray[ii][1]=ItemDivCompID;
                i++;
            });
            var ItemDivSerializedArr = JSON.stringify(ItemDivArray);
            
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
        }else if(AccoountName == ''){
            alert('please enter Account Name');
            $('#AccountName').focus();
        }else if(state == ''){
            alert('please select State');
            $('#state').focus();
        }else if(city == ''){
            alert('please select City');
            $('#city').focus();
        }else if(phonenumber == ''){
            alert('please  enter mobile number');
            $('#phonenumber').focus();
        }/*else if(!$('#vat').val().match('/^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/') && $('#vat').val() !== '')  {
            alert("Enter valid GST no..");
            $('#vat').focus();
        }*/else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
            alert('Enter valid Mobile number');
            $('#phonenumber').focus();
        }else if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#Pan').val() !== ""){
            alert('Enter valid PAN number');
            $('#Pan').focus();
        }else if(!$('#Aadhaarno').val().match('[0-9]{12}') && $('#Aadhaarno').val() !== ""){
            alert('Enter valid Aadhar number');
            $('#Aadhaarno').focus();
        }else if(groups_in == ''){
            alert('please Select Distributor Type');
            $('#groups_in').focus();
        }else if(route == ''){
            alert('please select Route');
            $('#route').focus();
        }else if(location_type == ''){
            alert('please select location type');
            $('#location_type').focus();
        }else if(ItemDivSerializedArr == '[]'){
            alert('please select atleast one ItemDivision');
            $('.itemdiv').focus();
        }/*else if(CompArraylength != 3){
            
            alert('please select All Company and staff and balance...');
            $('.company_assigned').focus();
        }*/else {
            
            $.ajax({
                url:"<?php echo admin_url(); ?>clients/SaveAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,AccoountName:AccoountName,firstname:firstname,lastname:lastname,title:title,phonenumber:phonenumber,
                    altphonenumber:altphonenumber,email:email,vat:vat,groups_in:groups_in,state:state,city:city,address:address,Address3:Address3,
                    zip:zip,kms:kms,FLNO1:FLNO1,Pan:Pan,Aadhaarno:Aadhaarno,istcs:istcs,TcsStartDate1:TcsStartDate1,MaxCrdAmt:MaxCrdAmt,
                    Blockyn:Blockyn,SalesFrequency:SalesFrequency,location_type:location_type,BalancesYN:BalancesYN,CtrlAccountID:CtrlAccountID,StationName:StationName,ActSalestype:ActSalestype,route:route,website:website,
                    bill_till_bal:bill_till_bal,active:active,StartDate:StartDate,shipping_state:shipping_state,shipping_city:shipping_city,
                    shipping_street:shipping_street,shipping_zip:shipping_zip,ItemDivSerializedArr:ItemDivSerializedArr,CompSerializedArr:CompSerializedArr
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
                       //alert_float('success', 'Record created successfully...');
                       alert('Record created successfully...');
                       $('#AccountID').val('');
                       $('#AccoountName').val('');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#address').val('');
                       $('#Address3').val('');
                       $('#zip').val('');
                       $('#kms').val('');
                       $('#FLNO1').val('');
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                        $('#TcsStartDate1').val('');
                       $('#MaxCrdAmt').val('');
                       $('#CtrlAccountID').val('');
                       $('#StationName').val('');
                       $('#website').val('');
                       var today = new Date('d-m-Y');
                       $('#StartDate').val(today);
                       $('#shipping_street').val('');
                       $('#shipping_zip').val('');
                       $('.itemdiv').prop('checked', false);
                       $('select[name=itemdivisioncomp]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('.company_assigned').prop('checked', false);
                        
                        $('select[name=company_assigned_staff]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=title]').val('Owner');
                        $('.selectpicker').selectpicker('refresh');
                
                        $('.opening_bal').val('');
                        $('#route').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')
                        
                        $("#city").children().remove();
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=location_type]').val('3');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=groups_in]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=istcs]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=BalancesYN]').val('Y');
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", false);
                        }
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       alert_float('warning', 'Something went wrong...');
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
            title = $('#title').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            vat = $('#vat').val();
            groups_in = $('#groups_in').val();
            state = $('#state').val();
            city = $('#city').val();
            address = $('#address').val();
            Address3 = $('#Address3').val();
            zip = $('#zip').val();
            kms = $('#kms').val();
            FLNO1 = $('#FLNO1').val();
            Pan = $('#Pan').val();
            Aadhaarno = $('#Aadhaarno').val();
            istcs = $('#istcs').val();
            TcsStartDate1 = $('#TcsStartDate1').val();
            MaxCrdAmt = $('#MaxCrdAmt').val();
            Blockyn = $('#Blockyn').val();
            SalesFrequency = $('#SalesFrequency').val();
            location_type = $('#location_type').val();
            BalancesYN = $('#BalancesYN').val();
            CtrlAccountID = $('#CtrlAccountID').val();
            StationName = $('#StationName').val();
            ActSalestype = $('#ActSalestype').val();
            route = $('#route').val();
            website = $('#website').val();
            bill_till_bal = $('#bill_till_bal').val();
            active = $('#active').val();
            StartDate = $('#StartDate').val();
            shipping_state = $('#shipping_state').val();
            shipping_city = $('#shipping_city').val();
            shipping_street = $('#shipping_street').val();
            shipping_zip = $('#shipping_zip').val();
            
            var ItemDivArray = new Array();
            var i = 1;
            $.each($("input[name='itemdiv']:checked"), function(){
                var val = $(this).val();
                var id= 'itemdiv'+val;
                var itemdivID = document.getElementById(id).value;
                var id2= 'itemdivisioncomp'+val;
                var ItemDivCompID = document.getElementById(id2).value;
                var ii = i - 1;
                ItemDivArray[ii]=new Array();
                ItemDivArray[ii][0]=itemdivID;
                ItemDivArray[ii][1]=ItemDivCompID;
                i++;
            });
            var ItemDivSerializedArr = JSON.stringify(ItemDivArray);
            
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
        }else if(AccoountName == ''){
            alert('please enter Account Name');
            $('#AccountName').focus();
        }else if(state == ''){
            alert('please select State');
            $('#state').focus();
        }else if(city == ''){
            alert('please select City');
            $('#city').focus();
        }else if(phonenumber == ''){
            alert('please  enter mobile number');
            $('#phonenumber').focus();
        }/*else if(!$('#vat').val().match('/^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/') && $('#vat').val() !== '')  {
            alert("Enter valid GST no..");
            $('#vat').focus();
        }*/else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
            alert('Enter valid Mobile number');
            $('#phonenumber').focus();
        }else if(!$('#Pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#Pan').val() !== ""){
            alert('Enter valid PAN number');
            $('#Pan').focus();
        }else if(!$('#Aadhaarno').val().match('[0-9]{12}') && $('#Aadhaarno').val() !== ""){
            alert('Enter valid Aadhar number');
            $('#Aadhaarno').focus();
        }else if(groups_in == ''){
            alert('please Select Distributor Type');
            $('#groups_in').focus();
        }else if(route == ''){
            alert('please select Route');
            $('#route').focus();
        }else if(location_type == ''){
            alert('please select location type');
            $('#location_type').focus();
        }else if(ItemDivSerializedArr == '[]'){
            alert('please select Account ItemDivision');
            $('.itemdiv').focus();
        }/*else if(CompArraylength != 3){
            
            alert('please select All Company and staff and balance...');
            $('.company_assigned').focus();
        }*/else {
            $.ajax({
                url:"<?php echo admin_url(); ?>clients/UpdateAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,AccoountName:AccoountName,firstname:firstname,lastname:lastname,title:title,phonenumber:phonenumber,
                    altphonenumber:altphonenumber,email:email,vat:vat,groups_in:groups_in,state:state,city:city,address:address,Address3:Address3,
                    zip:zip,kms:kms,FLNO1:FLNO1,Pan:Pan,Aadhaarno:Aadhaarno,istcs:istcs,TcsStartDate1:TcsStartDate1,MaxCrdAmt:MaxCrdAmt,
                    Blockyn:Blockyn,SalesFrequency:SalesFrequency,location_type:location_type,BalancesYN:BalancesYN,CtrlAccountID:CtrlAccountID,StationName:StationName,ActSalestype:ActSalestype,route:route,website:website,
                    bill_till_bal:bill_till_bal,active:active,StartDate:StartDate,shipping_state:shipping_state,shipping_city:shipping_city,
                    shipping_street:shipping_street,shipping_zip:shipping_zip,ItemDivSerializedArr:ItemDivSerializedArr,CompSerializedArr:CompSerializedArr
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
                       //alert_float('success', 'Record updated successfully...');
                       alert('Record updated successfully...');
                       $('#AccountID').val('');
                       $('#AccoountName').val('');
                       $('#firstname').val('');
                       $('#lastname').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#address').val('');
                       $('#Address3').val('');
                       $('#zip').val('');
                       $('#kms').val('');
                       $('#FLNO1').val('');
                       $('#Pan').val('');
                       $('#Aadhaarno').val('');
                        $('#TcsStartDate1').val('');
                       $('#MaxCrdAmt').val('');
                       $('#CtrlAccountID').val('');
                       $('#StationName').val('');
                       $('#website').val('');
                       var today = new Date('d-m-Y');
                       $('#StartDate').val(today);
                       $('#shipping_street').val('');
                       $('#shipping_zip').val('');
                       $('.itemdiv').prop('checked', false);
                       $('select[name=itemdivisioncomp]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('.company_assigned').prop('checked', false);
                        $('select[name=company_assigned_staff]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=title]').val('Owner');
                        $('.selectpicker').selectpicker('refresh');
                
                        $('.opening_bal').val('');
                        $('#route').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh')
                        
                        $("#city").children().remove();
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=location_type]').val('3');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=groups_in]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=istcs]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       
                       $('select[name=BalancesYN]').val('Y');
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
                        
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $(".opening_bal").prop("readonly", false);
                        }
                       
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       alert_float('warning', 'Something went wrong...');
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