<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s invoice accounting-template">
   <div class="additional"></div>
   <div class="panel-body">
      <?php
      /*echo $order->OrderType;
      echo "<br>";
      print_r($order);
      
      die;*/
      $OrderType = '';
      if($order){
          $OrderType = $order->OrderType;
          if($order->accbal > 0){
            $drcr = "Dr";  
          }else{
              $drcr = "Cr";
          }
          $amtvalue = 0;
          /*foreach ($order->accbal as $key => $accvalue) {
            # code...
            if($key == "PlantID" || $key == "FY" || $key == "AccountID" || $key == "UserID2" ||$key == "Lupdate"){
                
            }else{
                $amtvalue = $amtvalue + $accvalue;
            }
            
        }*/
        $TransDate = '';
        $BillAmt = '';
        foreach ($order->last_billed_on as $key1 => $last_billed_on) {
            if($key1 == "Transdate"){
                $TransDate = _d(substr($last_billed_on,0,10));
            }
            if($key1 == "BillAmt"){
                $BillAmt = $last_billed_on;
            }
        }
        $depTransDate = '';
        $depositAmt = '';
        foreach ($order->last_deposit_on as $key2 => $last_deposit_on) {
            if($key2 == "Transdate"){
                $depTransDate = _d(substr($last_deposit_on,0,10));
            }
            if($key2 == "Amount"){
                $depositAmt = $last_deposit_on;
            }
        }
          
      }
      
//echo $amtvalue;
      ?>
    <div class="row">
        <div class="col-md-6">
            
            <?php
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == 1){
                $next_order_number = get_option('next_order_number_for_cspl');
            }elseif($selected_company == 2){
                $next_order_number = get_option('next_order_number_for_cff');
            }elseif($selected_company == 3){
                $next_order_number = get_option('next_order_number_for_cbu');
            }elseif($selected_company == 4){
                $next_order_number = get_option('next_order_number_for_cbupl');
            }
            
            
               //$next_order_number = get_option('next_order_number');
               $format = get_option('invoice_number_format');

               if(isset($order)){
                  $format = $order->number_format;
               }

               //$prefix = get_option('invoice_prefix');
                $prefix = "ORD".$fy;
               if ($format == 1) {
                 $__number = $next_order_number;
                 if(isset($order)){
                   $__number = $order->number;
                   $prefix = '<span id="prefix">ORD</span>';
                 }
               } else if($format == 2) {
                 if(isset($order)){
                   $__number = $order->number;
                   $prefix = $order->prefix;
                   $prefix = '<span id="prefix">'. $prefix . '</span><span id="prefix_year">' .date('Y',strtotime($order->date)).'</span>/';
                 } else {
                  $__number = $next_order_number;
                  $prefix = $prefix.'<span id="prefix_year">'.date('Y').'</span>/';
                }
               } else if($format == 3) {
                  if(isset($order)){
                   $yy = date('y',strtotime($order->date));
                   $__number = $order->number;
                   $prefix = '<span id="prefix">'. $order->prefix . '</span>';
                 } else {
                  $yy = date('y');
                  $__number = $next_order_number;
                }
               } else if($format == 4) {
                  if(isset($order)){
                   $yyyy = date('Y',strtotime($order->date));
                   $mm = date('m',strtotime($order->date));
                   $__number = $order->number;
                   $prefix = '<span id="prefix">'. $order->prefix . '</span>';
                 } else {
                  $yyyy = date('Y');
                  $mm = date('m');
                  $__number = $next_order_number;
                }
               }

               $_is_draft = (isset($order) && $order->status == Invoices_model::STATUS_DRAFT) ? true : false;
               $_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
               $isedit = isset($order) ? 'true' : 'false';
               $data_original_number = isset($order) ? $order->number : 'false';
               
               if(isset($order)){
                   $_invoice_number = substr($order->OrderID,5);
                   
               }

               ?>
               <div class="col-md-6">
                   <div class="form-group">
                           <label for="number">
                              <?php echo _l('order_add_edit_number'); ?> 
                             <!--<i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('invoice_number_not_applied_on_draft') ?>" data-placement="top"></i>-->
                        </label>
                           <div class="input-group">
                              <span class="input-group-addon">
                              <?php if(isset($order)){ ?>
                                <!--<a href="#" onclick="return false;" data-toggle="popover" data-container='._transaction_form' data-html="true" data-content="<label class='control-label'><?php echo _l('settings_sales_invoice_prefix'); ?></label><div class='input-group'><input name='s_prefix' type='text' class='form-control' value='<?php echo $order->prefix; ?>'></div><button type='button' onclick='save_sales_number_settings(this); return false;' data-url='<?php echo admin_url('invoices/update_number_settings/'.$order->id); ?>' class='btn btn-info btn-block mtop15'><?php echo _l('submit'); ?></button>">
                                <i class="fa fa-cog"></i>
                                </a>-->
                              <?php }
                                echo $prefix;
                              ?>
                              <?php 
                    if(isset($order)){
                        $view = "disabled";
                    }else{
                        $view = "";
                    }
                    ?>
                              </span>
                              <input type="hidden" value="<?php echo date('y'); ?>" name="years" class="years" id="years">
                              <input type="text" id="ordnumber" name="number1" class="form-control number1" value="<?php echo $_invoice_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo $view; ?>>
                              <?php if($format == 3) { ?>
                              <span class="input-group-addon">
                                 <span id="prefix_year" class="format-n-yy"><?php echo $yy; ?></span>
                              </span>
                              <?php } else if($format == 4) { ?>
                               <span class="input-group-addon">
                                 <span id="prefix_month" class="format-mm-yyyy"><?php echo $mm; ?></span>
                                 /
                                 <span id="prefix_year" class="format-mm-yyyy"><?php echo $yyyy; ?></span>
                              </span>
                              <?php } ?>
                             <div class="input-group-addon search_order">
                                  <i class="fa fa-search calendar-icon"></i>
                              </div>
                           </div>
                        </div>
               </div>
               
               <div class="col-md-6">
                   <?php
                    if(isset($order)){
                        ?>
                        <input type="hidden" name="OrderID" id="OrderID" value="<?php echo $order->OrderID; ?>">
                        <input type="hidden" name="PlantID" id="PlantID" value="<?php echo $this->session->userdata('root_company'); ?>">
                        <input type="hidden" name="SalesID" id="SalesID" value="<?php echo $order->SaleDetails->SalesID; ?>">
                        <input type="hidden" name="SalesDate" id="SalesDate" value="<?php echo $order->SaleDetails->Transdate; ?>">
                        <input type="hidden" name="ChallanID" id="ChallanID" value="<?php echo $order->ChallanDetails->ChallanID; ?>">
                    <?php
                    }else{
                    ?>
                    <input type="hidden" name="OrderID" id="OrderID" value="">
                    <?php
                    }
                   ?>
                   <input type="hidden" name="number" class="form-control" value="<?php echo ($_is_draft) ? 'DRAFT' : $_invoice_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($_is_draft) ? 'disabled' : '' ?>>
                  <?php
                    $fy = $this->session->userdata('finacial_year');
                    $fy_new  = $fy + 1;
                    $lastdate_date = '20'.$fy_new.'-03-31';
                    $curr_date = date('Y-m-d');
                    $curr_date_new    = new DateTime($curr_date);
                    $last_date_yr = new DateTime($lastdate_date);
                    if($last_date_yr < $curr_date_new){
                        $date = $lastdate_date;
                    }else{
                        $date = date('Y-m-d');
                    }
                ?>
                   <?php $value = (isset($order) ? _d(substr($order->Transdate,0,10)) : _d($date));
                  $date_attrs = array();
                  if(isset($order)){
                    $date_attrs['disabled'] = true;
                  }
                  ?>
                  <?php echo render_date_input('date1','invoice_add_edit_date',$value,$date_attrs); ?>
                  
                </div>
                <div class="col-md-12">
                    
                    <div class="f_client_id1">
                        <div class="form-group select-placeholder">
                        <label for="clientid" class="control-label"><?php echo _l('order_select_customer'); ?></label>
                        <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($order) && empty($order->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" <?php echo $view; ?>>
                            <?php $selected = (isset($order) ? $order->AccountID : '');
                            if($selected == ''){
                                $selected = (isset($customer_id) ? $customer_id: '');
                            }
                            if($selected != ''){
                                $rel_data = get_relation_data('customer',$selected);
                                $rel_val = get_relation_values($rel_data,'customer');
                                echo '<option value="'.$rel_val['AccountID'].'" selected>'.$rel_val['name'].'</option>';
                            } ?>
                        </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-lable" style="margin-top: 7px;">Location</label>
                        </div>
                        
                        <?php //print_r($client_detail);
                        if($order){
                            
                            $custitemdiv = array();
                            foreach ($custitems_groups as $key => $value) {
                               # code...
                               array_push($custitemdiv, $value["ItemDivID"]);
                            }
                            //print_r($custitemdiv);
                            $custitemdiv_ids = implode(",",$custitemdiv);
                            if($client_detail->state == "9"){
                            $location_type = "Local";
                        }else{
                            $location_type = "OutStation";
                        }
                            
                            if($client_detail->istcs == "1"){
                                $tcs_per = $tcs[0]['tcs'];
                            }else{
                                $tcs_per = " ";
                            }
                        }else{
                            $location_type = "";
                            $tcs_per = $tcs[0]['tcs'];
                        }
                        
                        
                        
                         
                        ?>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:5px;">
                            <input type="hidden" name="cust_id" value="<?php echo $order->AccountID; ?>">
                             <input type="text" class="form-control" name="location_type" id="location_type" value="<?php echo $location_type; ?>" disabled>
                             <input type="hidden" class="form-control" name="istcsper" id="istcsper" value="<?php echo $tcs_per; ?>">
                             <input type="hidden" class="form-control" name="istcs" id="istcs" value="<?php echo $client_detail->istcs; ?>">
                             
                             <input type="hidden" class="form-control" name="location_typevalue" id="location_typevalue" value="<?php echo $location_type; ?>">
                             <input type="hidden" class="form-control" name="item_divistion" id="item_divistion" value='<?php echo $custitemdiv_ids; ?>'>
                             <input type="hidden" class="form-control" name="dist_comp" id="dist_comp" value='<?php echo $client_detail->company_assigned; ?>'>
                             <input type="hidden" class="form-control" name="dist_sale_agent" id="dist_sale_agent" value='<?php echo $client_detail->company_assigned_staff; ?>'>
                             <?php
                             if(isset($order)){
                                ?>
                            <input type="hidden" class="form-control" name="billing_street1" value="<?php echo $client_detail->billing_street; ?>">
                            <input type="hidden" class="form-control"name="billing_city1" value="<?php echo $client_detail->billing_city; ?>">
                            <input type="hidden" class="form-control"name="billing_state1" value="<?php echo $client_detail->billing_state; ?>">
                            <input type="hidden" class="form-control"name="billing_zip1" value="<?php echo $client_detail->billing_zip; ?>">
                            <input type="hidden" class="form-control" name="billing_country1" value="<?php echo $client_detail->billing_country; ?>">
                            <input type="hidden" class="form-control" name="include_shipping" value="1">
                            <input type="hidden" class="form-control" name="show_shipping_on_invoice" value="1">
                            <input type="hidden" class="form-control" name="shipping_street1" value="<?php echo $client_detail->shipping_street; ?>">
                            <input type="hidden" name="shipping_city1" value="<?php echo $client_detail->shipping_city; ?>">
                            <input type="hidden" name="shipping_state1" value="<?php echo $client_detail->shipping_state; ?>">
                            <input type="hidden" name="shipping_zip1" value="<?php echo $client_detail->shipping_zip; ?>">
                            <input type="hidden" name="shipping_country1" value="<?php echo $client_detail->shipping_country; ?>">
                            
                            <?php
                             }
                             ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php $billing_street = (isset($order) ? $order->billing_street : '--'); ?>
                     <?php $billing_street = ($billing_street == '' ? '--' :$billing_street); ?>
                            <input type="text" class="form-control" name="dist_street" id="dist_street" value="<?php echo $order->client->Address3; ?>" disabled>
                            <input type="hidden" class="form-control" name="dist_route" id="dist_route" value='<?php echo $client_detail->routes; ?>' >
                            <input type="hidden" class="form-control" name="dist_tcs" id="dist_tcs" value="" >
                            <input type="hidden" class="form-control" name="act_gst" id="act_gst" value="<?php echo $client_detail->vat; ?>" >
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-lable" style="margin-top: 7px;">SHIP To</label>
                                </div>
                                <div class="col-md-4" style="padding-right:0px;">
                                    <div class="form-group">
                                        <input type="text" name="act_code" id="act_code" class="form-control" value="<?php echo $order->AccountID2; ?>">
                                        
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left:0px;">
                                    <div class="form-group">
                                        
                                        <input type="text" name="act_name" id="act_name" class="form-control" value="<?php echo $client_detail2->company; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4" style="padding-right:0px;">
                                    <div class="form-group">
                                        <input type="text" name="act2_city" id="act2_city" class="form-control" value="<?php echo $client_detail2->StationName; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding-right:0px;padding-left:0px;">
                                    <div class="form-group">
                                        <input type="text" name="act2_state" id="act2_state" class="form-control" value="<?php echo $client_detail2->state; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding-left:0px;">
                                    <div class="form-group">
                                        <input type="text" name="act2_gst" id="act2_gst" class="form-control" value="<?php echo $client_detail2->vat; ?>" disabled>
                                        <input type="hidden" name="act2_gst_no" id="act2_gst_no" class="form-control" value="<?php echo $client_detail2->vat; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" style="padding-right:0px;">
                                    <div class="form-group">
                                        <input type="text" name="act2_address" id="act2_address" class="form-control" value="<?php echo $client_detail2->address; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left:0px;">
                                    <div class="form-group">
                                        <input type="text" name="act2_address2" id="act2_address2" class="form-control" value="<?php echo $client_detail2->Address3; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            </br>
                            <p style="color:#3826d3 !important;font-size: 16px;font-weight: 600;" class="item_added_msg" id="item_added_msg">
                                <?php
                                if(isset($order)){
                                    if($order->OrderType == "TaxItems"){
                                        echo  "We can add only Taxable items..";
                                    }else{
                                        echo "We can add only Non Taxable items..";
                                    }
                                }
                                ?>
                            </p>
                            <?php
                            
                                if(isset($order)){
                                    if($order->ChallanDetails->Gatepassuserid !== NULL){
                                    ?>
                                        <p style="color:red !important;font-size: 16px;font-weight: 600;">GatePass has been Generated for this order..</p>
                                    <?php
                                    }
                                    if($order->SaleDetails->irn !== NULL){
                                    ?>
                                        <p style="color:red !important;font-size: 16px;font-weight: 600;">E-invoice has been Generated for this order..</p>
                                    <?php
                                    }
                                }
                            ?>
                            <!--<?php
                                if((isset($order) && $order->ChallanID == null) || !isset($order)){}else{
                            ?>
                            <p style="color:red !important;font-size: 16px;font-weight: 600;">Challan has been created for this order..</p>
                            
                            <?php } ?>-->
                            
                            <!--<?php
                                if(isset($order) && ($order->GSTNO == NULL)){
                                    if($order->ChallanDetails->Gatepassuserid !== NULL){
                                    ?>
                                        <p style="color:red !important;font-size: 16px;font-weight: 600;">GatePass has been Generated for this order..</p>
                                    <?php
                                    }
                                }else{
                                    if($order->SaleDetails->irn !== NULL){
                                    ?>
                                        <p style="color:red !important;font-size: 16px;font-weight: 600;">E-invoice has been Generated for this order..</p>
                                    <?php    
                                    }
                                }
                            ?>-->
                        </div>
                        
                    </div>
                    <!--<?php if(isset($order)){
                    
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php $value = (isset($order) ? $order->reason : ''); ?>
                            <?php echo render_textarea('reason','Reason',$value); ?>
                        </div>
                    </div>
                    <?php } ?>-->
                </div>
                <!--<div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-lable" style="margin-top: 2px;"><?php echo _l('dispatch_loc'); ?></label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group" style="margin-bottom:5px;">
                             <select name="dispatch_loc" id="dispatch_loc" class="selectpicker" data-live-search="true" required data-width="100%">
                                 
                                 <option>Select Company</option>
                                 <?php
                                 foreach($rootcompany as $comp){
                                     ?>
                                     <option value="<?php echo $comp["id"];?>"><?php echo $comp["company_name"];?></option>
                                <?php } ?>
                             </select>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>-->
               
        </div>
        
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="margin-bottom:5px;">
                        
                        <label for="act_bal" class="control-label">A/C Balance</label>
                        <input type="text" class="form-control" name="acct_bal" id="acct_bal" value="<?php echo number_format($order->accbal,2)." ".$drcr; ?>" disabled>
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            
                            <label class="control-label" style="margin-top: 10px;">Last Billed On</label>
                            <?php //echo render_date_input('date','last_bill',$value,$date_attrs); ?>
                            <!--<input type="date" class="form-control" name="last_bill" id="last_bill" value="<?php echo $last_bill; ?>">-->
                           
                        </div>
                        <div class="col-md-5">
                            <?php $last_bill = (isset($order) ? $order->last_bill : ' '); ?>
                            <?php $last_bill = ($last_bill == '' ? '--' :$last_bill); ?>
                    
                            <?php $value = (isset($order) ? _d($order->date) : ' ');
                            $date_attrs = array();
                            $date_attrs['disabled'] = true;
                            ?>
                           <input type="text" class="form-control " name="last_bill_date" id="last_bill" value="<?php echo $TransDate; ?>" disabled> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:5px;">
                                
                                <input type="text" class="form-control" name="last_bill_amt" id="last_bill" value="<?php echo $BillAmt; ?>" disabled>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" style="margin-top: 10px;">Last Deposit</label>
                        </div>
                        <div class="col-md-5">
                            <?php $last_dep = (isset($order) ? $order->last_dep : ' '); ?>
                            <?php $last_dep = ($last_dep == '' ? '--' :$last_dep); ?>
                    
                            <?php $value = (isset($order) ? _d($order->date) : " ");
                            $date_attrs = array();
                            $date_attrs['disabled'] = true;
                            ?>
                           
                            <input type="text" class="form-control " name="last_dep_date" id="last_dep" value="<?php echo $depTransDate; ?>" disabled>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:5px;">
                                
                                <input type="text" class="form-control" name="last_dep_amt" id="last_dep" value="<?php echo $depositAmt; ?>" disabled>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" style="margin-top: 10px;"><?php echo _l('customer_type'); ?></label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group" style="margin-bottom:5px;">
                                <input type="text" class="form-control" name="customer_group" id="customer_group" value="<?php echo $customer_groups_name->name; ?>" placeholder="Distributor Type" disabled>
                                <input type="hidden" name="customer_group_id" id="customer_group_id" value="<?php echo $client_detail->DistributorType; ?>" >
                                <input type="hidden" name="customer_state_id" id="customer_state_id" value="<?php echo $client_detail->state?>" >
                            </div>
                            
                        </div>
                    </div>
                    
                    
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" style="margin-top: 10px;">Order Type - </label>
                        </div>
                        <div class="col-md-2">
                            <?php $order_type = (isset($order) ? $order->order_type : 'Web'); ?>
                            <?php //$order_type = ($order_type == null ? 'Web' :$order_type); ?>
                            <p style="color:#3826d3 !important;margin-top: 10px;font-size: 15px;font-weight: 600;"><?php echo $order_type; ?></p>
                            <input type="hidden" class="form-control" name="order_type" id="order_type" value="<?php echo $order_type; ?>">
                        </div>
                        <div class="col-md-7">
                            <?php $taxable = (isset($order) ? $order->OrderType : ''); ?>
                            
                            <input type="text" class="form-control" name="tax1" id="tax1" value="<?php echo $taxable; ?>"  disabled>
                            <input type="hidden" class="form-control" name="taxes1" id="taxes1" value="<?php echo $taxable; ?>">
                        
                        <?php
                                    if (isset($order)) {
                                            $add_items       = $order->items;
                                            $item_code_list = array();
                                            foreach($add_items as $item) {
                                                array_push($item_code_list, $item['ItemID']);
                                                
                                            }
                                            $item_code_string = implode(",",$item_code_list);
                                           
                                          }else{
                                              $item_code_string = "";
                                          }
                            ?>
                            <input type="hidden" class="form-control" name="item_code_list" id="item_code_list" value="<?php echo $item_code_string; ?>">
                        </div>
                    </div>
                    
                    
                </div>
                
                
                
            </div>
        </div>
        
    </div>
        <div class="row">
            
            <div class="col-md-6">
            
           
            <div class="row">
               
               <div class="col-md-6">
                     
                </div>
               <!--<div class="col-md-6">
                  <?php
                  $value = '';
                  if(isset($order)){
                    $value = _d($order->duedate);
                  } else {
                    if(get_option('invoice_due_after') != 0){
                        $value = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                    }
                  }
                   ?>
                  <?php echo render_date_input('duedate','invoice_add_edit_duedate',$value); ?>
               </div>-->
            </div>
            
            <div class="row">
               <div class="col-md-12">
               <!--<hr class="hr-10" />-->
                  <!--<a href="#" class="edit_shipping_billing_info" data-toggle="modal" data-target="#billing_and_shipping_details"><i class="fa fa-pencil-square-o"></i></a>-->
                  <?php include_once(APPPATH .'views/admin/order/billing_and_shipping_template.php'); ?>
               </div>
            </div>
            
            </div>
           
        </div>
        
        
            
            
            
            
            <div class="row" style="display:none;">
                
                <?php
                        $currency_attr = array('disabled'=>true,'data-show-subtext'=>true,);
                        $currency_attr = apply_filters_deprecated('invoice_currency_disabled', [$currency_attr], '2.3.0', 'invoice_currency_attributes');

                        foreach($currencies as $currency){
                         if($currency['isdefault'] == 1){
                           $currency_attr['data-base'] = $currency['id'];
                         }
                         if(isset($order)){
                          if($currency['id'] == $order->currency){
                           $selected = $currency['id'];
                         }
                        } else {
                         if($currency['isdefault'] == 1){
                           $selected = $currency['id'];
                         }
                        }
                        }
                        $currency_attr = hooks()->apply_filters('invoice_currency_attributes',$currency_attr);
                        
                        ?>
                <?php echo render_select('currency', $currencies, array('id','name','symbol'), 'invoice_add_edit_currency', $selected, $currency_attr); ?>
                <div class="col-md-3">
                    <input type="hidden" name="currency" value="<?php echo $selected; ?>">
                    <?php
                        $i = 0;
                        $selected = '';
                        foreach($staff as $member){
                         if(isset($order)){
                           if($order->sale_agent == $member['staffid']) {
                             $selected = $member['staffid'];
                           }
                         }
                         $i++;
                        }
                        echo render_select('sale_agent',$staff,array('staffid',array('firstname','lastname')),'sale_agent_string',$selected);
                        ?>
                </div>
                
            </div>
            
           
            
         
            <div class="row">
                
                  <div class="col-md-6">
                     
                  </div>
                  <div class="recurring_custom <?php if((isset($order) && $order->custom_recurring != 1) || (!isset($order))){echo 'hide';} ?>">
                     <div class="col-md-6">
                        <?php $value = (isset($order) && $order->custom_recurring == 1 ? $order->recurring : 1); ?>
                        <?php echo render_input('repeat_every_custom','',$value,'number',array('min'=>1)); ?>
                     </div>
                     <div class="col-md-6">
                        <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value="day" <?php if(isset($order) && $order->custom_recurring == 1 && $order->recurring_type == 'day'){echo 'selected';} ?>><?php echo _l('invoice_recurring_days'); ?></option>
                           <option value="week" <?php if(isset($order) && $order->custom_recurring == 1 && $order->recurring_type == 'week'){echo 'selected';} ?>><?php echo _l('invoice_recurring_weeks'); ?></option>
                           <option value="month" <?php if(isset($order) && $order->custom_recurring == 1 && $order->recurring_type == 'month'){echo 'selected';} ?>><?php echo _l('invoice_recurring_months'); ?></option>
                           <option value="year" <?php if(isset($order) && $order->custom_recurring == 1 && $order->recurring_type == 'year'){echo 'selected';} ?>><?php echo _l('invoice_recurring_years'); ?></option>
                        </select>
                     </div>
                  </div>
                  <div id="cycles_wrapper" class="<?php if(!isset($order) || (isset($order) && $order->recurring == 0)){echo ' hide';}?>">
                     <div class="col-md-12">
                        <?php $value = (isset($order) ? $order->cycles : 0); ?>
                        <div class="form-group recurring-cycles">
                          <label for="cycles"><?php echo _l('recurring_total_cycles'); ?>
                            <?php if(isset($order) && $order->total_cycles > 0){
                              echo '<small>' . _l('cycles_passed', $order->total_cycles) . '</small>';
                            }
                            ?>
                          </label>
                          <div class="input-group">
                            <input type="number" class="form-control"<?php if($value == 0){echo ' disabled'; } ?> name="cycles" id="cycles" value="<?php echo $value; ?>" <?php if(isset($order) && $order->total_cycles > 0){echo 'min="'.($order->total_cycles).'"';} ?>>
                            <div class="input-group-addon">
                              <div class="checkbox">
                                <input type="checkbox"<?php if($value == 0){echo ' checked';} ?> id="unlimited_cycles">
                                <label for="unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label>
                              </div>
                            </div>
                          </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--<?php $value = (isset($order) ? $order->adminnote : ''); ?>
               <?php echo render_textarea('adminnote','invoice_add_edit_admin_note',$value); ?>-->
            
      <?php if(isset($invoice_from_project)){ echo '<hr class="no-mtop" />'; } ?>
      <div class="table-responsive s_table">
        
         <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
            <thead>
               <tr>
                  <th></th>
                  <th  width="8%" align="left">ItemID</th>
                  <th width="30%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('order_table_item_heading'); ?></th>
                  <th width="5%" align="left"><?php echo _l('cs_cr'); ?></th>
                  <th width="5%" align="left"><?php echo _l('pack_qty'); ?></th>
                  <?php
                  
                     $qty_heading = _l('order_table_quantity_heading');
                     if(isset($order) && $order->show_quantity_as == 2 || isset($hours_quantity)){
                      $qty_heading = _l('invoice_table_hours_heading');
                     } else if(isset($order) && $order->show_quantity_as == 3){
                      $qty_heading = _l('invoice_table_quantity_heading') .'/'._l('invoice_table_hours_heading');
                     }
                     ?>
                  <th width="5%" align="left" class="qty1"><?php echo $qty_heading; ?></th>
                  <?php if(isset($order)){
                  ?>
                  <th width="2%" align="left" class="qty">new Qty</th>
                  <th width="8%" align="left" class="qty">Stock</th>
                  <th width="29%" align="left">Reason</th>
                  <?php
                  }?>
                  <th width="5%" align="left"><?php echo _l('invoice_table_rate_heading'); ?></th>
                  <th width="5%" align="left"><?php echo _l('order_table_dis'); ?></th>
                  <th width="5%" align="left"><?php echo _l('order_table_dis_amt'); ?></th>
                 <!-- <th width="5%" align="left"><?php echo _l('order_table_tax_heading'); ?></th>-->
                  <th width="10%" align="left"><?php echo _l('order_table_tax_heading'); ?></th>
                  
                  <th width="10%" align="left"><?php echo _l('invoice_table_amount_heading'); ?></th>
                  <?php if(isset($order)){}else{ ?>
                  <th align="center"><i class="fa fa-cog"></i></th>
                  <?php } ?>
               </tr>
            </thead>
            
            <tbody>
            <?php
                //if(isset($order)){
                    if($order->SaleDetails->irn == NULL && $order->ChallanDetails->Gatepassuserid == NULL){
                        ?>
                <tr class="main">
                  <td></td>
                  <td><input type="text" name="item_code" id="item_code" class="form-control">
                  <div class="" id="serchh" style="display:none;">Serching</div>
                  <input type="hidden" name="hsn_code" class="form-control"></td>
                  <td>
                    <input type="text" id="autouser" name="autouser" class="form-control" placeholder="item name">
                  </td>
                  <td>
                    <input type="text" name="items_case_qty" id="items_case_qty" class="form-control" placeholder="" disabled>
                    
                  </td>
                  <td>
                    <input type="number" name="pack_qty" id="pack_qty" class="form-control" placeholder="" disabled>
                  </td>
                  <?php if(isset($order)){
                  $ee = "11";
                  $min = 0;
                  $q_dis_not = "disabled";
                  }else{
                      $min = 0;
                       $q_dis_not = "";
                  }?>
                  <td>
                     <input type="text" name="quantity<?php echo $ee;?>" id="quantity<?php echo $ee;?>" min="<?php echo $min;?>"  value="" class="form-control" onblur="add_item_to_table1('undefined','undefined',<?php echo $new_item; ?>); return false;" <?php echo $q_dis_not; ?>>
                  </td>
                  <?php if(isset($order)){
                  ?>
                  <td>
                     <input type="text" name="quantity" id="quantity"  value="" class="form-control" onblur="add_item_to_table2('undefined','undefined',<?php echo $new_item; ?>); return false;" >
                  </td>
                  <td>
                     <input type="text" name="stockqty" id="stockqty" class="form-control" placeholder="" disabled>
                  </td>
                  <td>
                     <input type="text" name="ereason" class="form-control" placeholder="" disabled>
                  </td>
                  
                  <?php } ?>
                  <td>
                     <input type="text" name="rate" id="rate" class="form-control" placeholder="" disabled>
                  </td>
                  <td>
                     <input type="text" name="dis" id="dis" class="form-control" placeholder="" disabled>
                  </td>
                  <td>
                     <input type="text" name="dis_amt" id="dis_amt" class="form-control" placeholder="" disabled>
                     <input type="hidden" name="taxrate1" id="taxrate1" class="form-control" placeholder="" disabled>
                  </td>
                 <td>
                     <?php
                        $default_tax = unserialize(get_option('default_tax'));
                        $select = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="taxname" id="taxname" multiple data-none-selected-text="" disabled>';
                        foreach($taxes as $tax){
                        $selected = '';
                         if(is_array($default_tax)){
                             if(in_array($tax['name'] . '|' . $tax['taxrate'],$default_tax)){
                                  $selected = ' selected ';
                             }
                        }
                        $select .= '<option value="'.$tax['name'].'|'.$tax['taxrate'].'"'.$selected.'data-taxrate="'.$tax['taxrate'].'" data-taxname="'.$tax['name'].'" data-subtext="'.$tax['name'].'">'.$tax['taxrate'].'%</option>';
                        }
                        $select .= '</select>';
                        echo $select;
                        ?>
                  </td>
                  <td></td>
                  <td>
                     <?php
                        $new_item = 'undefined';
                        if(isset($order)){
                         $new_item = true;
                        }
                        ?>
                  </td>
               </tr>
            <?php
                    }
                //}    
            ?>
            
                <!--<?php
                    if((isset($order) && $order->ChallanID == null) || !isset($order)){
                ?> 
               
              <?php } ?>-->
               <?php if (isset($order) || isset($add_items)) {
                  $i               = 1;
                  //print_r($order->items);
                  $items_indicator = 'newitems';
                  if (isset($order)) {
                    $add_items       = $order->items;
                    $itemStocks       = $order->itemStocks;
                    $items_indicator = 'items';
                  }
                  foreach ($add_items as $item) {

                    $manual    = false;
                    $table_row = '<tr class="sortable item ">';
                    $table_row .= '<td class="dragger">';
                    if (!is_numeric($item['qty'])) {
                      $item['qty'] = 1;
                    }
                    $invoice_item_taxes = get_invoice_item_taxes($item['id']);
                    // passed like string
                    if ($item['id'] == 0) {
                        $invoice_item_taxes = $item['taxname'];
                        $manual             = true;
                    }
                    $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
                    $amount = $item['rate'] * $item['qty'];
                    $amount = app_format_number($amount);
                    $code_placeholder = "Item Code";
                   
                    $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                    $table_row .= '</td>';
                    $table_row .= '<td><input type="text" placeholder="'.$code_placeholder.'" name="'.$items_indicator.'['.$i.'][item_code]" class="form-control" value="'.$item['ItemID'].'" disabled><input type="hidden" name="'.$items_indicator.'['.$i.'][item_code1]" value="'.$item['ItemID'].'"><input type="hidden" name="'.$items_indicator.'['.$i.'][hsn_code]" value="'.$item['hsn_code'].'"></td>';
                    $table_row .= '<td class="bold description"><input name="' . $items_indicator . '[' . $i . '][description]" class="form-control" value="'.$item['description'].'" '. $tt .' disabled></td>';
                    $table_row .= '<td class="cs_cr"><input name="' . $items_indicator . '[' . $i . '][items_case_qty]" class="form-control"  value="'.$item['SuppliedIn'].'" disabled><input type="hidden" name="' . $items_indicator . '[' . $i . '][cs_cr]" value="'.$item['SuppliedIn'].'"></td>';
                    $CaseQty = (int) $item['CaseQty'];
                    $table_row .= '<td class="pack_qty"><input name="' . $items_indicator . '[' . $i . '][pack_qty1]" class="form-control" value="'.$CaseQty.'" disabled><input type="hidden" name="' . $items_indicator . '[' . $i . '][pack_qty]" value="'.$CaseQty.'"></td>';
                    //$table_row .= render_custom_fields_items_table_in($item,$items_indicator.'['.$i.']');
                    $old_qty = $item['OrderQty'] / $item['CaseQty'];
                    if(is_null($item['eOrderQty'])){
                        $qty = $item['OrderQty'] / $item['CaseQty'];
                    }else {
                        $qty = $item['eOrderQty'] / $item['CaseQty'];
                    }
                    if(isset($order)){
                        $dq = "";
                    }else{
                        $dq = "data-quantity";
                    }
                    $table_row .= '<td class="order_qty1"><input type="text" min="1" onblur="calculate_total();" onchange="calculate_total();" '.$dq.' name="' . $items_indicator . '[' . $i . '][qty1]" value="' . $old_qty . '" class="form-control" disabled>';

                    $table_row .= '</td>';
                    $table_row .= '<td class="order_qty"><input type="text" min="0" onblur="calculate_total2();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $qty . '" class="form-control">';
                    $table_row .= '</td>';
                    $PQty = 0;
                    $PRQty = 0;
                    $IQty = 0;
                    $PRDQty = 0;
                    $SQty = 0;
                    $SRTQty = 0;
                    $AQty = 0;
                    $AQty2 = 0;
                    $AQty3 = 0;
                    $AQty4 = 0;
                    $GIQty = 0;
                    $GOQty = 0;
                    
                    foreach ($itemStocks as $stock) {
                        if($stock['ItemID']==$item['ItemID']){
                            if($stock['TType'] == 'P'){
                                $PQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'N'){
                                $PRQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'A'){
                                $IQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'B'){
                                $PRDQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
                                $SQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
                                $SRTQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X'  && $stock['TType2'] == 'Free distribution'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X'  && $stock['TType2'] == 'Free Distribution'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
                                $GIQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
                                $GOQty = $stock['BilledQty'];
                            }
                        }
                    }
                    $stockQty = $item['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
                    /*$stock = $item['OQty'] + $item['PQty'] - $item['PRQty'] - $item['IQty'] + $item['PRDQty'] + $item['gtiqty'] - $item['gtoqty'] - $item['SQty'] + $item['SRQty'] - $item['DQTY'] - $item['ADJQTY']; 
                    $stockInCase = $stock / $item['CaseQty'];*/
                    $stockQtyInCase = $stockQty / $item['CaseQty'];
                    if($order->ChallanID !== null){
                        $stockQtyInCase += $qty;
                    }
                    $table_row .= '<td class="stock_qty"><input type="text" min="0"  onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][stockqty]" value="' . number_format((float)$stockQtyInCase, 2, '.', '') . '" class="form-control" disabled> <input type="hidden" name="' . $items_indicator . '[' . $i . '][stockqty1]" value="' . number_format((float)$stockInCase, 2, '.', '') . '">';
                    $table_row .= '</td>';
                    
                    $table_row .= '<td class="reason_td"><input type="text" name="' . $items_indicator . '[' . $i . '][ereason]" value="' . $item['ereason'] . '" class="form-control">';
                    $table_row .= '</td>';
                    $table_row .= '<td class="rate"><input type="number"  onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][rate1]" value="' . $item['BasicRate'] . '" class="form-control" disabled> <input type="hidden" name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['BasicRate'] . '"></td>';
                    $table_row .= '<td><input name="' . $items_indicator . '[' . $i . '][dis]" class="form-control"  value="'.$item['DiscPerc'].'" disabled></td>';
                    $table_row .= '<td><input name="' . $items_indicator . '[' . $i . '][dis_amt]" class="form-control"  value="'.round($item['DiscAmt'],2).'" disabled><input type="hidden" name="' . $items_indicator . '[' . $i . '][dis_amt1]" value="'.$item['DiscAmt'].'"></td>';
                    //$table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $invoice_item_taxes, 'invoice', $item['id'], true, $manual) . '</td>';
                    //$gst_value = 0.00;
                    if($item['igst']=="0.00" || is_null($item['igst'])){
                        
                        (double) $gst_value = $item['cgst'] + $item['sgst'];
                        
                    }else{
                        $gst_value = $item['igst'];
                    }
                    //$final_value = (float) $gst_value;
                    $final_value = number_format($gst_value, 2);
                    $table_row .= '<td class="taxrate"><input type="hidden" value="'.$final_value.'" name="' . $items_indicator . '[' . $i . '][taxrate1]" >';
                    
                    $table_row .= '<select class="selectpicker display-block tax" data-width="100%" name="taxname[]" id="taxname" multiple data-none-selected-text="" disabled>';
                      //  $select .= '<option value=""'.(count($default_tax) == 0 ? ' selected' : '').'>'._l('no_tax').'</option>';
                        foreach($taxes as $tax){
                        $selected = "";
                        if($tax['taxrate'] == $final_value){
                            $selected = ' selected ';
                        }
                                  
                         
                        $table_row .= '<option value="'.$tax['taxrate'].'"'.$selected.'data-taxrate="'.$tax['taxrate'].'" data-taxname="'.$tax['name'].'" data-subtext="'.$tax['name'].'">'.$tax['taxrate'].'%</option>';
                        }
                        $table_row .= '</select></td>';
                    
                    $table_row .= '<td class="amount" align="right">' . $item['grand_total'] . '</td>';
                    
                        //$table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                   
                    if (isset($item['task_id'])) {
                      if (!is_array($item['task_id'])) {
                        $table_row .= form_hidden('billed_tasks['.$i.'][]', $item['task_id']);
                      } else {
                        foreach ($item['task_id'] as $task_id) {
                          $table_row .= form_hidden('billed_tasks['.$i.'][]', $task_id);
                        }
                      }
                    } else if (isset($item['expense_id'])) {
                      $table_row .= form_hidden('billed_expenses['.$i.'][]', $item['expense_id']);
                    }
                    $table_row .= '</tr>';
                    echo $table_row;
                    $i++;
                  }
                  }
                  ?>
            </tbody>
         </table>
      </div>
      <div class="col-md-6">
        <table class="table text-right">
            <tbody>
                <tr id="subtotal">
                  <td class="total_crates"><span class="bold">Total Crates :</span>
                  <input type="hidden" name="total_crates" value="">
                  </td>
                  <td class="crates"></td>
                  <td class="total_cases"><span class="bold">Total Cases :</span>
                  <input type="hidden" name="total_cases" value="">
                  <input type="hidden" name="total_tax" value="">
                  </td>
                  <td class="cases"></td>
               </tr>
            </tbody>
        </table>
        </div>
      <div class="col-md-6">
         <table class="table text-right">
            <tbody>
                
                
               
               <tr id="subtotal">
                  <td><span class="bold"><?php echo _l('invoice_subtotal'); ?> :</span>
                  </td>
                  <td class="subtotal" colspan="2">
                  </td>
               </tr>
               <tr id="discount_area">
                  <td>
                     <div class="row">
                        <div class="col-md-12">
                           <span class="bold">
                            <?php echo _l('order_discount'); ?>
                         </span>
                        </div>
                        
                     </div>
                  </td>
                  <td class="discount-total" colspan="2"></td>
               </tr>
               
               <tr class="cgsttotaltr">
                  <td><span class="bold"><?php echo _l('CGST_Amt'); ?> :</span>
                  </td>
                  <td class="cgsttotal" colspan="2">
                  </td>
               </tr>
               <tr class="sgsttotaltr">
                  <td><span class="bold"><?php echo _l('SGST_Amt'); ?> :</span>
                  </td>
                  <td class="sgsttotal" colspan="2">
                  </td>
               </tr>
               <tr class="igsttotaltr">
                  <td><span class="bold"><?php echo _l('IGST_Amt'); ?> :</span>
                  </td>
                  <td class="igsttotal" colspan="2">
                  </td>
               </tr>
               <tr class="tcstotaltr">
                  <td><span class="bold">TCS :</span>
                  </td>
                  <td style="width:5%"><span class="istcsper"></span></td>
                  <td class="tcstotal">
                  </td>
               </tr>
               <tr>
                  <td><span class="bold" ><?php echo _l('invoice_total'); ?> :</span>
                  </td>
                  <td class="total" colspan="2" style="width: 15%">
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <div id="removed-items"></div>
      <div id="billed-tasks"></div>
      <div id="billed-expenses"></div>
      <?php echo form_hidden('task_id'); ?>
      <?php echo form_hidden('expense_id'); ?>  
               
               

      </div>
     
   </div>
   
   <div class="row">
      <div class="col-md-12 mtop15">
         <div class="panel-body bottom-transaction">
            
            <div class="btn-bottom-toolbar text-right">
                
              <div class="btn-group dropup">
            <?php
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $lgstaff = $this->session->userdata('staff_user_id');
                $order_date = substr($order->Transdate,0,10);
                
                $order_date_new    = new DateTime($order_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                $sql = 'SELECT * FROM tblordermaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" AND  OrderStatus = "O" ORDER BY tblordermaster.OrderID DESC ';
                $result_data = $this->db->query($sql)->row();
                $lastdate_order = substr($result_data->Transdate,0,10);
               
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $this->db->select('*');
                $this->db->where('plant_id', $selected_company);
                $this->db->where('year', $fy);
                $this->db->where('staff_id', $lgstaff);
                $this->db->LIKE('feature', "orders");
                $this->db->LIKE('capability', "view");
                $this->db->from(db_prefix() . 'staff_permissions');
                $result2 = $this->db->get()->row();
                $day = $result2->days;
                
                if($day == 0){
                            $return = '';
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($order_date_new < $tillDate_new) {
                                $return = 'disabled';
                            }else{
                                $return = '';
                            }
                        } 
            ?>
            
            <?php
            if(isset($order)){
                if($order->ChallanDetails->Gatepassuserid == NULL && $order->SaleDetails->irn == NULL){
                    if (has_permission_new('orders', '', 'edit')) {
                        if($return == "disabled"){
                            ?>
                            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
                            <?php
                        }else{
                            ?>
                            <button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit">Update</button>
                            <?php
                        }
                    }
                }
                
                /*if(isset($order) && ($order->GSTNO == NULL)){
                    if($order->ChallanDetails->Gatepassuserid !== NULL){
                ?>
                    
                <?php
                    }else{
                        // Enter Code hare
                        
                        if (has_permission_new('orders', '', 'edit')) {
                        ?>
                            <?php if($return == "disabled"){
                            ?>
                            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
                            <?php
                            }else{
                            ?>
                            <button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit">Update</button>
                            <?php
                            }?>
                       <?php }  ?>
               <?php
                    }
                }else{
                    if($order->SaleDetails->irn !== NULL){
                ?>
               
                <?php    
                    }else{
                        // Enter Code hare
                            if (has_permission_new('orders', '', 'edit')) {
                        ?>
                            <?php if($return == "disabled"){
                            ?>
                            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
                            <?php
                            }else{
                            ?>
                            <button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit">Update</button>
                            <?php
                            }?>
                       <?php } 
                    }
                }*/
                }
            ?>
               <?php
                    if(!isset($order)){
                ?>  
                <?php
                if (has_permission_new('orders', '', 'create')) {
                        ?>
                <button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit"><?php echo _l('submit'); ?></button>
               
               <?php } } ?>
              </div>
             </div>
         </div>
        <!--<div class="btn-bottom-pusher"></div>-->
      </div>
   </div>
</div>
<style>
    ._transaction_form .table.items thead>tr>th {
    min-width: 70px;
}
</style>
<!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type='text/javascript'>
    $(document).ready(function(){
    
    
     $('.search_order').on('click', function() {
         
        var NestId = 'ORD' + $(".years").val() + $(".number1").val();
        var url = admin_url + 'order/list_orders/' + NestId;
        window.location.href = url;
        //init_order(NestId);
        //alert(NestId);
     });
     
    $('#item_code').on('focus', function() {
        var ItemID = $('#item_code').val();
        if(ItemID == ''){
        }else{
            var item_code_list = $("#item_code_list").val();
            let result = item_code_list.replace(ItemID, " ");
            $("#item_code_list").val(result);
        }
        $('#item_code').val(''); 
        $('#autouser').val(''); 
        $('#items_case_qty').val(''); 
        $('#pack_qty').val(''); 
        $('#rate').val(''); 
        $('#taxrate1').val('');
        $('#stockqty').val('');
       $('select[name=taxname]').val('');
       $('.selectpicker').selectpicker('refresh')
    }); 
     // Initialize 
     $( "#autouser" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          var location = $("#location_typevalue").val();
          var dist_type_id = $("#customer_group_id").val();
          var dist_state_id = $("#customer_state_id").val();
          var item_divistion = $("#item_divistion").val();
          var item_taxes = $("#taxes1").val();
          $.ajax({
            url: "<?=base_url()?>admin/order/itemlist",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term,
              location: location,
              dist_type_id: dist_type_id,
              dist_state_id: dist_state_id,
              item_divistion: item_divistion,
              item_taxes: item_taxes
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
        var item_divistion = $("#item_divistion").val();
          let div_array = item_divistion.split(",");
          
           var item_code_list = $("#item_code_list").val();
          let item_code_list_array = item_code_list.split(",");
          
          var item_taxes1 = $("#taxes1").val();
          if(ui.item.isactive == "N"){
              alert("Item Deactive....");
              $('#item_code').val('');
          }else{
            if(item_code_list_array.includes(ui.item.value)){
              alert("item already added");
              $('#item_code').val('');
               return false;
          }else{
              if(item_taxes1 == "TaxItems") {
              
                  if(ui.item.gst != 1){
                    if(div_array.includes(ui.item.itemdiv)){
                        
                        // Set selection
                        $('#autouser').val(ui.item.label); // display the selected text
                        $("#item_code_list").val(item_code_list+","+ui.item.value);
                        add_item_to_preview1(ui.item.value,ui.item.location,ui.item.dist_type_id,ui.item.dist_state_id);
                        $('#userid').val(ui.item.value); // save selected id to input
                        $('#quantity').focus();
                        return false;
                        
                    }else{
                        
                        alert("Selected Item Division not assign to customer...");
                        $('#autouser').val(""); // display the selected text
                        $('#userid').val(""); // save selected id to input
                        $('#item_code').val('');
                        return false;
                    }
                      
                  }else {
                      alert("please add only taxable item");
                      // Set selection
                      $('#item_code').val('');
                        $('#autouser').val(""); // display the selected text
                        return false;
                  }
              }
              
              if(item_taxes1 == "NonTaxItems"){
                  if(ui.item.gst == 1){
                      if(div_array.includes(ui.item.itemdiv)){
                      // Set selection
                        $('#autouser').val(ui.item.label); // display the selected text
                        $("#item_code_list").val(item_code_list+","+ui.item.value);
                        add_item_to_preview1(ui.item.value,ui.item.location,ui.item.dist_type_id,ui.item.dist_state_id);
                        $('#userid').val(ui.item.value); // save selected id to input
                        $('#quantity').focus();
                        return false;
                      }else{
                          
                        alert("Selected Item Division not assign to customer...");
                        $('#autouser').val(""); // display the selected text
                        $('#userid').val(""); // save selected id to input
                        $('#item_code').val('');
                        return false;
                    }
                    
                  }else {
                      alert("please add only non taxable item");
                      // Set selection
                      $('#item_code').val('');
                        $('#autouser').val(""); // display the selected text
                        return false;
                  }
                  
              }
              
              if(item_taxes1 == ""){
                  if(div_array.includes(ui.item.itemdiv)){
                  // Set selection
                    $('#autouser').val(ui.item.label); // display the selected text
                    $("#item_code_list").val(item_code_list+","+ui.item.value);
                    add_item_to_preview1(ui.item.value,ui.item.location,ui.item.dist_type_id,ui.item.dist_state_id);
                    $('#userid').val(ui.item.value); // save selected id to input
                    $('#quantity').focus();
                    return false;
                  }else{
                        
                        alert("Selected Item Division not assign to customer...");
                        $('#autouser').val(""); // display the selected text
                        $('#userid').val(""); // save selected id to input
                        $('#item_code').val('');
                        return false;
                    }
              }
            }  
          }
          
          
        }
      });
    
    // On Blur ItemID Get All Date
        $('#item_code').blur(function(){
            ItemID = $(this).val();
            if(ItemID == ''){
            }else{
                // Fetch data
              var location = $("#location_typevalue").val();
              var dist_type_id = $("#customer_group_id").val();
              var dist_state_id = $("#customer_state_id").val();
              var item_divistion = $("#item_divistion").val();
              var item_taxes = $("#taxes1").val();
                $.ajax({
                    url:"<?php echo admin_url(); ?>order/GetItemDetailByID",
                    dataType:"JSON",
                    method:"POST",
                    data:{ItemID:ItemID,location:location,dist_type_id:dist_type_id,dist_state_id:dist_state_id,item_divistion:item_divistion,item_taxes:item_taxes},
                    beforeSend: function () {
                    $('.searchh2').css('display','block');
                    $('.searchh2').css('color','blue');
                    },
                    complete: function () {
                    $('.searchh2').css('display','none');
                    },
                    success:function(data){
                        var item_divistion = $("#item_divistion").val();
                        let div_array = item_divistion.split(",");
                        var item_code_list = $("#item_code_list").val();
                        let item_code_list_array = item_code_list.split(",");
                        var item_taxes1 = $("#taxes1").val();
                        if(data.isactive == "N"){
                            alert("Item Deactive....");
                            $('#item_code').val('');
                        }else{
                            if(item_code_list_array.includes(data.item_code)){
                                alert("item already added");
                                $('#item_code').val('');
                                return false;
                            }else{
                                if(item_taxes1 == "TaxItems") {
                                    if(data.tax != 1){
                                      if(div_array.includes(data.group_id)){
                                          // Set selection
                                            $('#item_code').val(data.item_code); // display the selected text
                                            $("#item_code_list").val(item_code_list+","+data.item_code);
                                            add_item_to_preview1(data.item_code,data.location,data.dist_type_id,data.dist_state_id);
                                            $('#userid').val(data.item_code); // save selected id to input
                                            $('#quantity').focus();
                                            return false;
                                        }else{
                                            alert("Selected Item Division not assign to customer...");
                                            $('#item_code').val(""); // display the selected text
                                            $('#userid').val(""); // save selected id to input
                                            return false;
                                        }
                                    }else {
                                      alert("please add only taxable item");
                                      // Set selection
                                        $('#item_code').val(""); // display the selected text
                                        return false;
                                    }
                                }  
                                
                                
                                if(item_taxes1 == "NonTaxItems"){
                                    if(data.tax == 1){
                                        if(div_array.includes(data.group_id)){ 
                                        // Set selection
                                            $('#autouser').val(data.item_code); // display the selected text
                                            $("#item_code_list").val(item_code_list+","+data.item_code);
                                            add_item_to_preview1(data.item_code,data.location,data.dist_type_id,data.dist_state_id);
                                            $('#userid').val(data.item_code); // save selected id to input
                                            $('#quantity').focus();
                                            return false;
                                        }else{
                                            alert("Selected Item Division not assign to customer...");
                                            $('#item_code').val(""); // display the selected text
                                            $('#userid').val(""); // save selected id to input
                                            return false;
                                        }
                                    }else {
                                        alert("please add only non taxable item");
                                        // Set selection
                                        $('#item_code').val(""); // display the selected text
                                        return false;
                                    }
                                }
                                
                                if(item_taxes1 == ""){
                                    if(div_array.includes(data.group_id)){
                                    // Set selection
                                        $('#item_code').val(data.item_code); // display the selected text
                                        $("#item_code_list").val(item_code_list+","+data.item_code);
                                        add_item_to_preview1(data.item_code,data.location,data.dist_type_id,data.dist_state_id);
                                        $('#userid').val(data.item_code); // save selected id to input
                                        $('#quantity').focus();
                                        return false;
                                    }else{
                                        alert("Selected Item Division not assign to customer...");
                                        $('#item_code').val(""); // display the selected text
                                        $('#userid').val(""); // save selected id to input
                                        return false;
                                    }
                                }
                                
                            }
                        }
                    }
                });
              
            }
        });  
      // Initialize 
     $( "#item_code" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          var location = $("#location_typevalue").val();
          var dist_type_id = $("#customer_group_id").val();
          var dist_state_id = $("#customer_state_id").val();
          var item_divistion = $("#item_divistion").val();
          
          var item_divistion = $("#item_divistion").val();
          let div_array = item_divistion.split(",");
          
          var item_taxes = $("#taxes1").val();
          $.ajax({
            url: "<?=base_url()?>admin/order/itemlist_using_itemcode",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term,
              location: location,
              dist_type_id: dist_type_id,
              dist_state_id: dist_state_id,
              item_divistion: item_divistion,
              item_taxes: item_taxes
            },
            beforeSend: function () {
               
               $('#serchh').css('display','block');
               //$("#ui-id-2").prepend("<li value='' class='ui-menu-item'>Serching</li>");
            },
            complete: function () {
                //$("#item_code").val("");
                $('#serchh').css('display','none');
            },
            success: function( data ) {
                
              response( data );
            }
          });
        },
        select: function (event, ui) {
        var item_divistion = $("#item_divistion").val();
          let div_array = item_divistion.split(",");
          
          var item_code_list = $("#item_code_list").val();
          let item_code_list_array = item_code_list.split(",");
          
          var item_taxes1 = $("#taxes1").val();
          if(ui.item.isactive == "N"){
              alert("Item Deactive....");
              $('#item_code').val('');
          }else{
            if(item_code_list_array.includes(ui.item.value)){
              alert("item already added");
              $('#item_code').val('');
               return false;
          }else{
              
              if(item_taxes1 == "TaxItems") {
              
                  if(ui.item.gst != 1){
                      if(div_array.includes(ui.item.itemdiv)){
                      // Set selection
                        $('#item_code').val(ui.item.value); // display the selected text
                        //$("#item_code_list").val(item_code_list+","+ui.item.value);
                        //add_item_to_preview1(ui.item.value,ui.item.location,ui.item.dist_type_id,ui.item.dist_state_id);
                        $('#userid').val(ui.item.value); // save selected id to input
                        $('#quantity').focus();
                        return false;
                      }else{
                        alert("Selected Item Division not assign to customer...");
                        $('#item_code').val(""); // display the selected text
                        $('#userid').val(""); // save selected id to input
                        return false;
                    }
                    
                  }else {
                      alert("please add only taxable item");
                      // Set selection
                        $('#item_code').val(""); // display the selected text
                      return false;
                  }
              }
              
              if(item_taxes1 == "NonTaxItems"){
                  if(ui.item.gst == 1){
                     if(div_array.includes(ui.item.itemdiv)){ 
                      // Set selection
                        $('#autouser').val(ui.item.value); // display the selected text
                        //$("#item_code_list").val(item_code_list+","+ui.item.value);
                        //add_item_to_preview1(ui.item.value,ui.item.location,ui.item.dist_type_id,ui.item.dist_state_id);
                        $('#userid').val(ui.item.value); // save selected id to input
                        $('#quantity').focus();
                        return false;
                  }else{
                        alert("Selected Item Division not assign to customer...");
                        $('#item_code').val(""); // display the selected text
                        $('#userid').val(""); // save selected id to input
                        return false;
                    }
                  }else {
                      alert("please add only non taxable item");
                      // Set selection
                      $('#item_code').val(""); // display the selected text
                      return false;
                  }
                  
              }
              
              if(item_taxes1 == ""){
                  if(div_array.includes(ui.item.itemdiv)){
                  // Set selection
                    $('#item_code').val(ui.item.value); // display the selected text
                    //$("#item_code_list").val(item_code_list+","+ui.item.value);
                    //add_item_to_preview1(ui.item.value,ui.item.location,ui.item.dist_type_id,ui.item.dist_state_id);
                    $('#userid').val(ui.item.value); // save selected id to input
                    $('#quantity').focus();
                    return false;
                  }else{
                    alert("Selected Item Division not assign to customer...");
                        $('#item_code').val(""); // display the selected text
                        $('#userid').val(""); // save selected id to input
                        return false;
              }
              }
          }  
          }
          
          
          
        }
      });
    
      
      // Initialize For Account
     $( "#act_code" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/Cd_notes/accountlist",
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
          
          
          $('#act_code').val(ui.item.value); // display the selected text
         $('#act_name').val(ui.item.label); // display the selected text
           $('#act2_address').val(ui.item.address); // display the selected text
          $('#act2_address2').val(ui.item.address2); // display the selected text
          $('#act2_state').val(ui.item.state_name); // display the selected text
          $('#act2_city').val(ui.item.station); // display the selected text
          $('#act2_gst').val(ui.item.gst); // display the selected text
          $('#act2_gst_no').val(ui.item.gst); // display the selected text
          /*//$('#account_state_name').val(ui.item.state_name); // display the selected text
          get_sale_item(ui.item.value);*/
            $("#item_code").focus();
            return false;      
            
        }
      });



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
   /* console.log(minStartDate);
    console.log(maxEndDate_new);*/
    
    $('#date1').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    
    
    });
</script>     
   