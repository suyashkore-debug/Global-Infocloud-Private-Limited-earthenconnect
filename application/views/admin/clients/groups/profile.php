<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--<h4 class="customer-profile-group-heading"><?php echo _l('client_add_edit_profile'); ?></h4>-->
<div class="row">
    <?php if(isset($client)){ ?>
   <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
   <?php } else { ?>
   <?php echo form_open(admin_url('clients/client_add'),array('class'=>'client-form','autocomplete'=>'off')); ?>
   <?php } ?>
   <div class="additional"></div>
   <div class="col-md-12">
      
      <div class="tab-content mtop15">
         <?php hooks()->do_action('after_custom_profile_tab_content',isset($client) ? $client : false); ?>
        <?php if($customer_custom_fields) { ?>
         <div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') == 'custom_fields'){echo ' active';}; ?>" id="custom_fields">
            <?php $rel_id=( isset($client) ? $client->userid : false); ?>
            <?php //echo render_custom_fields( 'customers',$rel_id); ?>
            
            
            
         </div>
         <?php } ?>
         
         
         <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
            
            
            
            <div class="row">
                
                <div class="col-md-3">
                <?php $value=( isset($client) ? $client->AccountID : ''); ?>
                  <?php //echo render_input( 'AccountID', 'Account ID',$value,'text',$attrs); ?>
                  <div id="companycode_exists_info1" class="hide"></div>
                  
                  <div class="form-group" app-field-wrapper="AccountID">
                    <label for="AccountID" class="control-label">Account ID</label>
                    <input type="text" id="AccountID" name="AccountID" class="form-control" value="<?php echo $value;?>" <?php if(isset($client)) { echo "disabled";}?>>
                    <span id="lblError" style="color: red"></span>
                  </div>
                  
                </div>
                    <?php
                //}
                ?>
                
            <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->company : ''); ?>
                  <?php $attrs = (isset($client) ? array() : array('autofocus'=>true)); ?>
                  <?php echo render_input( 'company', 'client_company',$value,'text',$attrs); ?>
                  <div id="company_exists_info" class="hide"></div>
                </div>
                
            <div class="col-md-3">
                <?php
                //print_r($client_contacts);
                //echo $client_contacts[0]['firstname'];
                ?>
                <?php $value=( isset($client_contacts) ? $client_contacts[0]['firstname'] : ''); ?>
                
                    <div class="form-group" app-field-wrapper="firstname">
                        <label for="firstname" class="control-label">First Name</label>
                        <input type="text" id="firstname" name="firstname" class="form-control AlphabetsOnly"  value="<?= $value;?>">
                    </div>
                
                </div>
            <div class="col-md-3">
                <?php $value=( isset($client_contacts) ? $client_contacts[0]['lastname'] : ''); ?>
                
                <div class="form-group" app-field-wrapper="lastname">
                    <label for="lastname" class="control-label">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control AlphabetsOnly" value="<?= $value;?>">
                </div>
                </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                 
                <div class="form-group" app-field-wrapper="contact_position">
                    <label class="control-label" for><?php echo _l( 'contact_position' ); ?></label>
                    <select name="title" class="form-control">
                        <option value="Owner" <?php if($client_contacts[0]['title']=="Owner"){echo 'selected';}?>>Owner</option>
                        <option value="Employee" <?php if($client_contacts[0]['title']=="Employee"){echo 'selected';}?>>Employee</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                 <!--<?php echo render_input( 'phonenumber_contact', 'client_mobile',"",'number',array('autocomplete'=>'off')); ?>-->
                 <?php $value=( isset($client) ? $client->phonenumber : ''); ?>
                  <?php //echo render_input( 'phonenumber', 'client_mobile',$value,'text'); ?>
                    <div class="form-group" app-field-wrapper="phonenumber">
                     <label for="phonenumber" class="control-label">Mobile Number</label>
                     <input type="text" id="mobile-num" name="phonenumber" class="form-control" value="<?= $value;?>" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                    </div>
                </div>
            <div class="col-md-3">
                <?php $value=( isset($client) ? $client->altnumber : ''); ?>
                  <?php //echo render_input( 'altphonenumber', 'client_phonenumber',$value,'text'); ?>
                <div class="form-group" app-field-wrapper="altphonenumber">
                     <label for="altphonenumber" class="control-label">Alternative Mobile</label>
                     <input type="text" id="altphonenumber" name="altphonenumber" class="form-control" value="<?= $value;?>" maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                </div>  
            </div>
            <div class="col-md-3">
                <?php $value=( isset($client_contacts) ? $client_contacts[0]['email'] : ''); ?>
                 <?php //echo render_input( 'email', 'client_email',$value, 'email'); ?>
                <div class="form-group" app-field-wrapper="email">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= $value; ?>">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <?php if(get_option('company_requires_vat_number_field') == 1){
                     $value=( isset($client) ? $client->vat : '');
                    //echo render_input( 'vat', 'client_vat_number',$value);
                ?>
                <div class="form-group" app-field-wrapper="vat">
                    <label for="vat" class="control-label">GST Number</label>
                    <input type="text" id="vat" name="vat" class="form-control" 
                    pattern="([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([0-9A-Za-z]){2}" maxlength="15" minlength="15" value="<?= $value; ?>">
                    <span class="gst_denger" style="color:red;"></span>
                </div>
                <?php
                     } ?>
            </div>
            
            <div class="col-md-3">
                <?php $selected=( isset($client) ? $client->DistributorType : ''); ?>
                    <?php
                     
                     
                     if(is_admin() || get_option('staff_members_create_inline_customer_groups') == '1'){
                         
                      //echo render_select_with_input_group('groups_in',$groups,array('id','name'),'customer_groups',$selected,'<a href="#" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>',array('data-actions-box'=>false),array(),'','',false);
                      echo render_select('groups_in',$groups,array('id',array('name')),'customer_groups',$selected,array('data-actions-box'=>false),array(),'','',array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                     
                         
                     } else {
                        echo render_select('groups_in',$groups,array('id',array('name')),'customer_groups',$selected,array('data-actions-box'=>false),array(),'','',array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                      }
                     ?>
                </div>
            
            
            
                
            <div class="col-md-3">
               <?php $value=( isset($client) ? $client->state : ''); ?>
                  <?php //echo render_input( 'state', 'client_state',$value); ?>
                  <?php //print_r($state);
                     $selected =( isset($client) ? $client->state : '');
                    
                     echo render_select( 'state',$state,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                     
                     ?>
            </div>
            
            
            <div class="col-md-3">
                <div class="form-group">
                    <?php $value=( isset($client) ? $client->city : ''); ?>
                    <?php
                    $city_name = get_city_name_by_state_id($client->state);
                    $vv = "";
                    ?>
                <label for="city" class="control-label">City</label>
                                
                <select class="form-control city" name="city" id="city" >
                    <option value="">Select city name</option>
                    
                    <?php foreach($city_name as $cn){ ?>
                    <?php
                        if($cn['id'] == $client->city){
                            $vv = "matched";
                        }
                    ?>
                    <option value="<?php echo $cn['id'];?>" <?php if($cn['id']==$client->city){ echo 'selected'; }?>><?php echo $cn["city_name"]; ?></option>
                  <?php } ?>   
                  <?php 
                    if($vv == "matched"){
                        
                    }else{
                        ?>
                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php
                    }
                  ?>
                  
                </select>
                                
                </div>
            </div>
        <div class="clearfix"></div>    
            <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->address : ''); ?>
                  <!--<?php echo render_textarea( 'address', 'Address 1',$value); ?>-->
                  <?php echo render_input( 'address', 'Address 1',$value); ?>
                </div>
                
            <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->Address3 : ''); ?>
                  
                  <?php echo render_input( 'Address3', 'Address 2',$value); ?>
                </div>
            
            <div class="col-md-3">
                <?php $value=( isset($client) ? $client->zip : ''); 
                if($value){
                            
                        }else{
                            $value = $client->pincodes;
                        }
                        ?>
                  <?php //echo render_input( 'zip', 'client_postal_code',$value,'text'); ?>
                <div class="form-group" app-field-wrapper="zip">
                    <label for="zip" class="control-label">Pin Code</label>
                    <input type="text"  name="zip" class="form-control" onchange="validateZipCode" value="<?= $value; ?>" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
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
                    <?php $value=( isset($client) ? $client->kms : ''); ?>
                  
                  <?php //echo render_input( 'kms', 'kms',$value,'text'); ?>
                  <div class="form-group" app-field-wrapper="kms">
                    <label for="kms" class="control-label">Kms</label>
                    <input id="kms" type="text" maxlength="7"  name="kms" class="form-control" value="<?= $value;?>" aria-invalid="false">
                  </div>
                  
                </div>
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->FLNO1 : ''); ?>
                  <?php //echo render_input( 'FLNO1', 'Food Licence No',$value,'text'); ?>
                    <div class="form-group" app-field-wrapper="FLNO1">
                        <label for="FLNO1" class="control-label">Food Licence No</label>
                        <input type="text" maxlength="14" minlength="14" id="FLNO1" name="FLNO1" class="form-control" value="<?= $value;?>" onkeypress="return isNumber(event)">
                    </div>
                  
                </div>
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->Pan : ''); ?>
                  
                  <?php //echo render_input( 'Pan', 'PAN Number',$value,'text'); ?>
                    <div class="form-group" app-field-wrapper="Pan"> 
                    <label for="Pan" class="control-label">PAN number</label>
                        <input type="text" maxlength="10" minlength="10" name="Pan" 
                            pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="Pan" class="form-control" 
                            value="<?php echo $value?>">
                        <span class="pan_denger" style="color:red;"></span>
                        </div>
                  
                </div>
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->Aadhaarno : ''); ?>
                    <?php //echo render_input( 'Aadhaarno', 'Aadhar Number',$value,'text'); ?>
                    <div class="form-group" app-field-wrapper="Aadhaarno">
                        <label for="aadhaar" class="control-label">Aadhar number</label>
                        <input type="text" maxlength="12" minlength="12"  name="Aadhaarno" pattern="[0-9] {12}" id="Aadhaarno" class="form-control numbersOnly" onkeypress="return isNumber(event)" value="<?php echo $value?>">
                        <span class="aadhar_denger" style="color:red;"></span>
                    </div>
                </div>
            <div class="clearfix"></div>
            
            <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->istcs : ''); 
                  
                    ?>
                  
                  <?php //echo render_input( 'istcs', 'TCS',$value,'text'); ?>
                  <label for="istcs">TCS</label>
                  <select name="istcs" id="istcs" class="selectpicker form-control tcs_type">
                      
                      <option value="0" <?php if($value == "0"){ echo "selected"; }?>>No</option>
                      <option value="1" <?php if($value == "1"){ echo "selected"; }?>>Yes</option>
                  </select>
                  
                </div>
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? substr($client->TcsStartDate,0,10) : ''); 
                    
                    ?>
                  
                  <?php echo render_date_input( 'TcsStartDate1', 'TCS Date',$value,'text'); ?>
                  <input type="hidden" name="TcsStartDate" value="<?php echo $value; ?>">
                </div>
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->MaxCrdAmt : ''); ?>
                  <?php //echo render_input( 'MaxCrdAmt', 'Max.Credit Amt',$value,'text'); ?>
                  <div class="form-group" app-field-wrapper="MaxCrdAmt">
                    <label for="MaxCrdAmt" class="control-label">Max.Credit Amt</label>
                    <input type="text" id="MaxCrdAmt" name="MaxCrdAmt" class="form-control numbersOnly" value="<?= $value;?>">
                    </div>
                  
                </div>
                
                <!--<div class="col-md-3">
                    <?php $value=( isset($client) ? $client->MaxDays : ''); ?>
                    <div class="form-group" app-field-wrapper="MaxDays">
                        <label for="MaxDays" class="control-label">Max.Day</label>
                        <input maxlength="3" type="text" id="MaxDays" name="MaxDays" class="form-control numbersOnly" value="<?= $value?>" onkeypress="return isNumber(event)">
                    </div>
                </div>-->
                
                
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->Blockyn : ''); ?>
                  
                  <?php //echo render_input( 'Blockyn', 'Block A/C',$value,'text'); ?>
                  <label for="Blockyn">Block A/C</label>
                  <select name="Blockyn" id="Blockyn" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                      
                      
                      <option value="N" <?php if($value == "N"){ echo "selected"; }?>>No</option>
                      <option value="Y" <?php if($value == "Y"){ echo "selected"; }?>>Yes</option>
                  </select>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->SalesFrequency : ''); ?>
                  
                  <?php //echo render_input( 'SalesFrequency', 'Sales Frquency',$value,'text'); ?>
                  <label for="SalesFrequency">Sales Frquency</label>
                  <select name="SalesFrequency" id="SalesFrequency" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                     
                      <option value="0" <?php if($value == "0"){ echo "selected"; }?>>Weekly</option>
                      <option value="1" <?php if($value == "1"){ echo "selected"; }?>>Bi-Weekly</option>
                      <option value="2" <?php if($value == "2"){ echo "selected"; }?>>Monthly</option>
                      <option value="3" <?php if($value == "3"){ echo "selected"; }?>>Quaterly</option>
                  </select>
                  
                </div>
                
                <div class="col-md-3">
                    <div class="form-group" app-field-wrapper="location_type">
                        <label for="location_type" class="control-label">Location Type</label>
                    <select name="location_type" id="location_type" class="selectpicker form-control" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                        <option value="">Non Selected</option>
                        <option value="3" <?php if($client_location->LocationTypeID == '3'){ echo 'selected'; }?>>None</option>
                        <option value="1" <?php if($client_location->LocationTypeID == '1'){ echo 'selected'; }?>>Local</option>
                        <option value="2" <?php if($client_location->LocationTypeID == '2'){ echo 'selected'; }?>>OutStation</option>
                    </select>
                    </div>
                </div>
                
                <!--<div class="col-md-3">
                    <?php $value=( isset($client) ? substr($client->StartDate,0,10) : ''); 
                    
                    ?>
                  
                  <?php echo render_date_input( 'StartDate', 'Start Date',$value,'text'); ?>
                  
                </div>-->
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->BalancesYN : ''); ?>
                  
                  <?php //echo render_input( 'Blockyn', 'Block A/C',$value,'text'); ?>
                  <label for="BalancesYN">Balance on bill</label>
                  <select name="BalancesYN" id="BalancesYN" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                      
                      <option value="Y" <?php if($value == "Y"){ echo "selected"; }?>>Yes</option>
                      <option value="N" <?php if($value == "N"){ echo "selected"; }?>>No</option>
                  </select>
                </div>
                
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->CtrlAccountID : ''); ?>
                  
                  <?php //echo render_input( 'CtrlAccountID', 'Ctrl AccountID',$value,'text'); ?>
                  <div class="form-group" app-field-wrapper="CtrlAccountID">
                    <label for="CtrlAccountID" class="control-label">Ctrl AccountID</label>
                    <input type="text" id="CtrlAccountID" name="CtrlAccountID" class="form-control" value="<?= $value?>">
                    <span id="lblError2" style="color: red"></span>
                </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->StationName : ''); ?>
                  
                  <?php echo render_input( 'StationName', 'Station Name',$value,'text'); ?>
                  
                </div>
                
                <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->ActSalestype : ''); ?>
                  
                  <?php //echo render_input( 'Blockyn', 'Block A/C',$value,'text'); ?>
                  <div class="form-group" app-field-wrapper="ActSalestype">
                  <label for="ActSalestype" class="form-label">Sales Type</label>
                  <select name="ActSalestype" id="ActSalestype" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                      <option value="">Non Selected</option>
                      <option value="Sales" <?php if($value == "Sales"){ echo "selected"; }?>>Sales</option>
                      <option value="CNF" <?php if($value == "CNF"){ echo "selected"; }?>>CNF</option>
                      <option value="StockTransfer" <?php if($value == "StockTransfer"){ echo "selected"; }?>>StockTransfer</option>
                  </select>
                  </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group" app-field-wrapper="route">
                    <label for="route" class="form-label">Route</label>
                    <?php
                    
                    $selected_route = array();
                    foreach ($dist_route as $key => $value) {
                        # code...
                        array_push($selected_route, $value["RouteID"]);
                    }
                    
                    ?>
                    <select name="route[]" id="route" class="selectpicker form-control" multiple data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                       
                        <?php
                        foreach ($routes as $key => $value) {
                          # code...
                          ?>
                          
                          <option value="<?php echo $value['RouteID'];?>" <?php if(in_array($value['RouteID'], $selected_route)){ echo 'selected'; }?>><?php echo $value['name'];?></option>
                        <?php
                        }
                        ?>
                        
                    </select>
                    </div>
                </div>
                
            
            <div class="col-md-3">
                
                  <?php if(!isset($client)){ ?>
                  <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
                  <?php }
                     $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                     $selected = $client->default_currency;
                     if(isset($client)){
                        $s_attrs['disabled'] = true;
                     }
                     foreach($currencies as $currency){
                        
                          
                          $def = $currency['isdefault'];
                          if($def=="1") {
                               $selected = $currency['id'];
                          }
                          
                      
                     }
                    // print_r($currencies);
                     
                            // Do not remove the currency field from the customer profile!
                     echo render_select('default_currency',$currencies,array('id','name','symbol'),'invoice_add_edit_currency',$selected,$s_attrs); ?>
                    
            </div> 
            
            <div class="clearfix"></div>
                
                <?php if(!is_language_disabled()){ ?>
                <div class="col-md-3">
                  <div class="form-group select-placeholder">
                     <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?>
                     </label>
                     <select name="default_language" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('system_default_string'); ?></option>
                        <?php foreach($this->app->get_available_languages() as $availableLanguage){
                           $selected = '';
                           if(isset($client)){
                              if($client->default_language == $availableLanguage){
                                 $selected = 'selected';
                              }
                           }
                           ?>
                        <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>><?php echo ucfirst($availableLanguage); ?></option>
                        <?php } ?>
                     </select>
                  </div>
                </div> 
                  <?php } ?>
            
            <div class="col-md-3">
                
                <?php if((isset($client) && empty($client->website)) || !isset($client)){
                     $value=( isset($client) ? $client->website : '');
                     echo render_input( 'website', 'client_website',$value);
                     } else { ?>
                  <div class="form-group">
                     <label for="website"><?php echo _l('client_website'); ?></label>
                     <div class="input-group">
                        <input type="text" name="website" id="website" value="<?php echo $client->website; ?>" class="form-control">
                        <div class="input-group-addon">
                           <span><a href="<?php echo maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                        </div>
                     </div>
                  </div>
                  <?php } ?>
                </div>
            <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->bill_till_bal : ''); ?>
                
                  <div class="form-group" app-field-wrapper="bill_till_bal">
                  <label for="bill_till_bal" class="form-label">Bill Till Bal.</label>
                  <select name="bill_till_bal" id="bill_till_bal" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                      <option value="N" <?php if($value == "N"){ echo "selected"; }?>>No</option>
                      <option value="Y" <?php if($value == "Y"){ echo "selected"; }?>>Yes</option>
                      </select>
                  </div>
            </div>
            <div class="col-md-3">
                    <?php $value=( isset($client) ? $client->active : ''); ?>
                
                  <div class="form-group" app-field-wrapper="active">
                  <label for="active" class="form-label">Status</label>
                  <select name="active" id="active" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                    
                      <option value="1" <?php if($value == "1"){ echo "selected"; }?>>Active</option>
                      <option value="0" <?php if($value == "0"){ echo "selected"; }?>>InActive</option>
                      </select>
                  </div>
            </div>
            
            <div class="col-md-3">
                <div id="contact-profile-image" class="form-group<?php if(isset($contact) && !empty($contact->profile_image)){echo ' hide';} ?>">
                            <label for="profile_image" class="profile-image"><?php echo _l('client_profile_image'); ?></label>
                            <input type="file" name="profile_image" class="form-control" id="profile_image">
                </div>
            </div> 
            <div class="col-md-3">
                    <?php $value=( isset($client) ? _d(substr($client->StartDate,0,10)) : date('d/m/Y')); 
                    
                    ?>
                  
                  <?php echo render_date_input( 'StartDate', 'Start Date',$value,'text'); ?>
                  
                </div>
            <div class="clearfix"></div>
            <br>
            <hr>
            
           <!-- <div class="col-md-12">
                <h5 class="no-mtop">
                    <?php echo _l('billing_address'); ?>&nbsp;&nbsp;<input type="checkbox" class="billing-same-as-customer1"><small class="font-medium-xs">&nbsp;<?php echo _l('customer_billing_same_as_profile'); ?></small> 
                    </h5>
            </div>
            <div class="col-md-3">
                        <h4 class="no-mtop"><?php echo _l('billing_address'); ?> <a href="#" class="pull-right billing-same-as-customer"><small class="font-medium-xs"><?php echo _l('customer_billing_same_as_profile'); ?></small></a></h4>
                        
                        <?php //$selected=( isset($client) ? $client->billing_country : '' ); 
                        $customer_default_country = get_option('customer_default_country');
                     $selected =( isset($client) ? $customer_default_country : $customer_default_country);?>
                        <?php //echo render_select( 'billing_country',$countries,array( 'country_id',array( 'short_name')), 'billing_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                        
                        <?php $selected=( isset($client) ? $client->billing_state : ''); 
                        $all_state = get_all_state();
                        ?>
                        
                        <?php echo render_select( 'billing_state',$all_state,array( 'short_name',array( 'state_name')), 'billing_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                        <?php //echo render_input( 'billing_state', 'billing_state',$value); ?>
            </div>
            <div class="col-md-3">
                        <?php $value=( isset($client) ? $client->city : ''); 
                        
                        ?>
                        <?php echo render_input( 'billing_city', 'billing_city',$value); ?>
                        <?php //echo render_select( 'billing_city',$city_name,array( 'id',array( 'state_name')), 'billing_city',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            </div>
            <div class="col-md-3">
                        <?php $value=( isset($client) ? $client->address : ''); ?>
                        <?php echo render_input( 'billing_street', 'Address',$value); ?>
            </div>
            <div class="col-md-3">
                        
                        <?php $value=( isset($client) ? $client->billing_zip : ''); 
                        if($value){
                            
                        }else{
                            $value = $client->pincodes;
                        }
                        ?>
                        <?php //echo render_input( 'billing_zip', 'billing_zip',$value); ?>
                        <div class="form-group" app-field-wrapper="billing_zip">
                            <label for="billing_zip" class="control-label">Pin Code</label>
                            <input type="text"  name="billing_zip" class="form-control" onchange="validateZipCode" value="<?= $value; ?>" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                        </div>
                     </div>
            <div class="clearfix"></div>
            <hr />-->
            <div class="col-md-12">
                <h5 class="no-mtop"> 
                    <?php echo _l('shipping_address'); ?>&nbsp;&nbsp;<input type="checkbox" class="customer-copy-billing-address1"><small class="font-medium-xs">&nbsp;<?php echo _l('customer_billing_same_as_profile'); ?></small> 
                </h5>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                
               
                        <?php $selected=( isset($client) ? $client->shipping_state : ''); 
                        $all_state = get_all_state();
                        ?>
                        
                        <?php echo render_select( 'shipping_state',$all_state,array( 'short_name',array( 'state_name')), 'shipping_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                        
        </div>
        <div class="col-md-3">
            <div class="form-group">
                    <?php $value=( isset($client) ? $client->shipping_city : ''); ?>
                    <?php
                    $city_name2 = get_city_name_by_state_id($client->shipping_state);
                    $vv2 = "";
                    ?>
                <label for="shipping_city" class="control-label"> Shipping City</label>
                                
                <select class="form-control shipping_city" name="shipping_city" id="shipping_city" >
                    <option value="">Select city name</option>
                    
                    <?php foreach($city_name as $cn){ ?>
                    <?php
                        if($cn['id'] == $client->shipping_city){
                            $vv2 = "matched";
                        }
                    ?>
                    <option value="<?php echo $cn['id'];?>" <?php if($cn['id']==$client->shipping_city){ echo 'selected'; }?>><?php echo $cn["city_name"]; ?></option>
                  <?php } ?>   
                  <?php 
                    if($vv2 == "matched"){
                        
                    }else{
                        ?>
                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php
                    }
                  ?>
                  
                </select>
                                
                </div>
            </div>
                        
                        <?php //echo render_input( 'shipping_city', 'shipping_city',$value); ?>
        
        <div class="col-md-3">
                        <?php $value=( isset($client) ? $client->address : ''); ?>
                        <?php echo render_input( 'shipping_street', 'Address',$value); ?>
        </div>
        <div class="col-md-3">
                        <?php $value=( isset($client) ? $client->shipping_zip : ''); 
                        if($value){
                            
                        }else{
                            $value = $client->pincodes;
                        }
                        ?>
                        <?php //echo render_input( 'shipping_zip', 'shipping_zip',$value); ?>
                        <div class="form-group" app-field-wrapper="shipping_zip">
                            <label for="shipping_zip" class="control-label">Pin Code</label>
                            <input type="text"  name="shipping_zip" class="form-control" onchange="validateZipCode" value="<?= $value; ?>" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                        </div>
                        
                     </div>
         
        </div>
        <hr />
        <div class="row">
            <h5></h5>
            <?php 
                $acc_item_div_com_id = array();
                foreach ($dist_item_div as $key => $value) {
                    array_push($acc_item_div_com_id, [$value["ItemDivID"] => $value["plant_assign"]]);
                }
            ?>
                    <div class="col-md-3">
                         <table class="table scroll-responsive">
                             <thead style="background-color: #0286c2;">
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
                                         
                                         <input type="checkbox" name="itemdiv<?php echo $item_division["id"];?>" value="<?php echo $item_division["id"];?>" <?php foreach($acc_item_div_com_id as $key => $value) { foreach($value as $key1 => $value1) { if($key1 == $item_division["id"]){{ echo "checked"; }}}}?>><label></label></div></td>
                                     <td><?php echo $item_division["name"]; ?></td>
                                     <td>
                                         
                                         <select name="itemdivisioncomp<?php echo $item_division["id"];?>" id="itemdivisioncomp<?php echo $item_division["id"];?>" class="form-control selectpicker itemdivisioncomp">
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
                    <div class="col-md-6">
                          
                          <?php 
                            $company_assigned = $client->company_assigned; 
                            $company_assigned_new = unserialize($company_assigned);
                            
                            $company_assigned_staff = $client->company_assigned_staff; 
                            $company_assigned_staff_new = unserialize($company_assigned_staff);
                            
                            $opening_bal = $client->opening_bal; 
                            $opening_bal_new = unserialize($opening_bal);
                            
                            $drcr = $client->drcr; 
                            $drcr_new = unserialize($drcr);
                           /* echo "<pre>";
                            print_r($rootcompany);
                            echo "<br>";*/
                            //print_r($company_assigned_staff_new);
                            //print_r($customer_admins);
                            
                            
                          ?>
                          
                         <table class="table scroll-responsive">
                             <thead style="background-color: #0286c2;">
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
                                    <?php
                                         $selected = "";
                                        
                               foreach($customer_admins as $c_admin){
                                   if($c_admin['company_id'] == $r_company["id"]){
                                       //array_push($selected,$c_admin['staff_id']);
                                       $selected = $c_admin['staff_id'];
                                       $selected_comp = $c_admin['company_id'];
                                   }
                                  
                               }
                               
                                ?>
                                     <td><div class="checkbox"><input type="checkbox" name="company_assigned<?php echo $r_company["id"];?>" value="<?php echo $r_company["id"];?>" <?php if ($selected_comp == $r_company["id"]) { echo "checked"; }?>><label></label></div></td>
                                     <td><?php echo $r_company["company_name"];?></td>
                                     <td> 
                                        
                                         
                                        
                                <div class="dropdown bootstrap-select form-control bs3 company_assigned_staff">
                                    <select name="company_assigned_staff<?php echo $r_company["id"];?>[]" class="form-control selectpicker" tabindex="-98" data-none-selected-text="Non selected" data-live-search="true">
                                    <option></option>
                                    <?php
                                    foreach ($staff as $sskey => $ssvalue) {
                                        # code...
                                        $staff_comp = $ssvalue["staff_comp"];
                                        $company_array = unserialize($staff_comp);
                                        if (in_array($r_company["id"], $company_array)){
                                            ?>
                                            <option value="<?php echo $ssvalue['staffid'];?>" <?php if($ssvalue['staffid'] == $selected){ echo "selected"; } ?>><?php echo $ssvalue["firstname"]." ".$ssvalue["lastname"]?></option>
                                            <?php
                                        }
                                        
                                    } ?>
                                    
                                </select>
                                </div>
                                </td>
                                     <td>
                                         <?php
                                         if($r_company["id"]=="1"){
                                            $bal = $acc_bal1->BAL1;
                                         }
                                         if($r_company["id"]=="2"){
                                           $bal = $acc_bal2->BAL1;
                                         }
                                         if($r_company["id"]=="3"){
                                            $bal = $acc_bal3->BAL1;
                                         }
                                         ?>
                                    <?php
                                    $staff_user_id = $this->session->userdata('staff_user_id');
                                    ?>
                                        <input type="text" name="opening_bal<?php echo $r_company["id"];?>" id="opening_bal<?php echo $r_company["id"];?>" value="<?php  echo abs($bal);?>" class="form-control opening_bal" <?php if(isset($client) && $staff_user_id !== "3"){ echo "disabled";}?> style="height: 25px;font-size: 12px;">
                                    </td>
                                     <td>
                                        <?php
                                        if($bal > 0){
                                            $drcr = "DR";
                                        }else {
                                            $drcr = "CR";
                                        }
                                        ?>
                                         <select name="drcr<?php echo $r_company["id"];?>" id="drcr<?php echo $r_company["id"];?>" class="form-control selectpicker drcr" <?php if(isset($client) && $staff_user_id !== "3"){ echo "disabled";}?>>
                                             <option value="DR" <?php if($drcr == "DR") { echo "selected"; }?>>DR</option>
                                             <option value="CR" <?php if($drcr == "CR") { echo "selected"; }?>>CR</option>
                                             </select>
                                         
                                     </td>
                                 </tr>
                                 <?php } ?>
                             </tbody>
                         </table>
                         
                    <div class="row">
                        <div class="col-md-12">
                  <div class="row">
                     
                     
                     <?php if(isset($client) &&
                        (total_rows(db_prefix().'invoices',array('clientid'=>$client->userid)) > 0 || total_rows(db_prefix().'estimates',array('clientid'=>$client->userid)) > 0 || total_rows(db_prefix().'creditnotes',array('clientid'=>$client->userid)) > 0)){ ?>
                     <div class="col-md-12">
                        <div class="alert alert-warning">
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_all_other_transactions" id="update_all_other_transactions">
                              <label for="update_all_other_transactions">
                              <?php echo _l('customer_update_address_info_on_invoices'); ?><br />
                              </label>
                           </div>
                           <b><?php echo _l('customer_update_address_info_on_invoices_help'); ?></b>
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_credit_notes" id="update_credit_notes">
                              <label for="update_credit_notes">
                              <?php echo _l('customer_profile_update_credit_notes'); ?><br />
                              </label>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
                    </div>
                       
                </div>
                
                     
            </div>
        
            <?php if(!isset($client)){ ?>
            
            <div class="row">
               
                
                <div class="col-md-6">
                   
            
             
             <?php $rel_id=( isset($contact) ? $contact->id : false); ?>
                    <?php echo render_custom_fields( 'contacts',$rel_id); ?>
              
              <!--<div class="client_password_set_wrapper">
                        <label for="password" class="control-label">
                            <?php echo _l( 'client_password'); ?>
                        </label>
                        <div class="input-group">

                            <input type="password" class="form-control password" name="password" autocomplete="false">
                            <span class="input-group-addon">
                                <a href="#password" class="show_password" onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
                            </span>
                            <span class="input-group-addon">
                                <a href="#" class="generate_password" onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                            </span>
                        </div>
                        <?php if(isset($contact)){ ?>
                        <p class="text-muted">
                            <?php echo _l( 'client_password_change_populate_note'); ?>
                        </p>
                        <?php if($contact->last_password_change != NULL){
                            echo _l( 'client_password_last_changed');
                            echo '<span class="text-has-action" data-toggle="tooltip" data-title="'._dt($contact->last_password_change).'"> ' . time_ago($contact->last_password_change) . '</span>';
                        }
                    } ?>
                </div> -->
                </div>
                </div>
                
            
            <?php } ?>
           
         </div>
         <?php if(isset($client)){ ?>
         <div role="tabpanel" class="tab-pane" id="customer_admins">
            <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
            <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
            <?php } ?>
            <table class="table dt-table">
               <thead>
                  <tr>
                     <th><?php echo _l('staff_member'); ?></th>
                     <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <th><?php echo _l('options'); ?></th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($customer_admins as $c_admin){ ?>
                  <tr>
                     <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                        <?php echo staff_profile_image($c_admin['staff_id'], array(
                           'staff-profile-image-small',
                           'mright5'
                           ));
                           echo get_staff_full_name($c_admin['staff_id']); ?></a>
                     </td>
                     <td data-order="<?php echo $c_admin['date_assigned']; ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <td>
                        <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                     </td>
                     <?php } ?>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
         <?php } ?>
         <div role="tabpanel" class="tab-pane" id="billing_and_shipping">
            <div class="row">
               
            </div>
         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid)); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
         </div>
         <div class="modal-body">
            <?php
               $selected = array();
               foreach($customer_admins as $c_admin){
                  array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]',$staff,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>
<?php $this->load->view('admin/clients/client_group'); ?>
<style>
#Pan, #vat {
    text-transform: uppercase;
}
.horizontal-scrollable-tabs {
    min-height: 25px;
}
.nav-tabs>li>a {
    padding: 2px 10px 2px 10px;
}
    table.table {
    margin-top: 0px !important;
    border: 1px solid #ebf5ff;
    }
    .table>tbody>tr>td, .table>tfoot>tr>td{
        padding:2px !important;
    }
    .table>thead>tr>th{
        padding:2px !important;
    }
    .itemdivisioncomp .btn-default{
        height:25px !important;
        padding: 0px 10px !important;
        font-size:12px !important;
    }
    .company_assigned_staff .btn-default{
        height:25px !important;
        padding: 0px 10px !important;
        font-size:12px !important;
    }
    .drcr .btn-default{
        height:25px !important;
        padding: 0px 10px !important;
        font-size:12px !important;
    }
    .checkbox, .radio {
        margin-bottom:0px !important;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $('.AlphabetsOnly').keypress(function (e) {
        var regex = new RegExp(/^[a-zA-Z\s]+$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else {
            e.preventDefault();
            return false;
        }
    });
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

<script>
   function validateZipCode(elementValue){
  var zipCodePattern = /^\d{5}$|^\d{5}-\d{4}$/;
  return zipCodePattern.test(elementValue);
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

<script>
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

    $('#vat').keyup(function(e) {
        var val = $('#vat').val();
        if(val == ""){
             $(".gst_denger").text(" ");
        }else{
            e.preventDefault();
            if(!$('#vat').val().match('([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([0-9A-Za-z]){2}')) {
                $(".gst_denger").text("Enter valid GST number");
            }else{
                $(".gst_denger").text(" ");
            }
        }
        
    });

    $('#Aadhaarno').keyup(function(e) {
        var val = $('#Aadhaarno').val();
        if(val == ""){
             $(".aadhar_denger").text(" ");
             return true;
        }else{
            e.preventDefault();
            if(!$('#Aadhaarno').val().match('[0-9]{12}'))  {
                return false;
                $(".aadhar_denger").text("Enter valid 12 digit Aadhar number");
            }else{
                $(".aadhar_denger").text(" ");
                return true;
            }
        }
        
    });
</script>
<script type="text/javascript">
    $(function () {
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
    });
    
    $(function () {
        $("#CtrlAccountID").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if(keyCode == ""){
                $("#lblError2").html("");
            }else{
                //Regex for Valid Characters i.e. Alphabets and Numbers.
                var regex = /^[A-Za-z0-9]+$/;
     
                //Validate TextBox value against the Regex.
                var isValid = regex.test(String.fromCharCode(keyCode));
                if (!isValid) {
                    $("#lblError2").html("Only Alphabets and Numbers allowed.");
                }else{
                    $("#lblError2").html("");
                    
                }
            return isValid;
                
            }
            
        });
    });
</script>