<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
		    <div class="panel_s invoice accounting-template">
               <div class="additional"></div>
               <div class="panel-body" style="padding-top:5px;">
            <?php
            if($challan){
                echo form_open($this->uri->uri_string(),array('id'=>'challan_form','class'=>'_transaction_form invoice-form'));
            }else {
                echo form_open($this->uri->uri_string(),array('id'=>'challan_form','class'=>'_transaction_form invoice-form'));
            }
            ?>       
			    <!--<p>Route Challan</p>-->
			    <div><b>Route Challan</b></div>
			    <hr class="hr-panel-heading2" />
			        <div class="col-md-4">
			            <div class="row">
			                <div class="col-md-6">
			                    <?php
			                    
			 $selected_company = $this->session->userdata('root_company');
			 if($selected_company == 1){
			     $LimitCHK = "Y";
			 }else{
			     $LimitCHK = "N";
			 }
			 
            if($selected_company == 1){
                
                $next_challan_number = get_option('next_challan_number_for_cspl');
            }elseif($selected_company == 2){
                $next_challan_number = get_option('next_challan_number_for_cff');
            }elseif($selected_company == 3){
                $next_challan_number = get_option('next_challan_number_for_cbu');
            }elseif($selected_company == 4){
                $next_challan_number = get_option('next_challan_number_for_cbupl');
            }    
               //$next_challan_number = get_option('next_challan_number');
               $format = get_option('invoice_number_format');

               if(isset($invoice)){
                  $format = $invoice->number_format;
               }

               //$prefix = get_option('invoice_prefix');
                $prefix = "CHL".$this->session->userdata('finacial_year');
               if ($format == 1) {
                 $__number = $next_challan_number;
                 if(isset($invoice)){
                   $__number = $invoice->number;
                   $prefix = '<span id="prefix">' . $invoice->prefix . '</span>';
                 }
               } else if($format == 2) {
                 if(isset($invoice)){
                   $__number = $invoice->number;
                   $prefix = $invoice->prefix;
                   $prefix = '<span id="prefix">'. $prefix . '</span><span id="prefix_year">' .date('Y',strtotime($invoice->date)).'</span>/';
                 } else {
                  $__number = $next_challan_number;
                  $prefix = $prefix.'<span id="prefix_year">'.date('Y').'</span>/';
                }
               } else if($format == 3) {
                  if(isset($invoice)){
                   $yy = date('y',strtotime($invoice->date));
                   $__number = $invoice->number;
                   $prefix = '<span id="prefix">'. $invoice->prefix . '</span>';
                 } else {
                  $yy = date('y');
                  $__number = $next_challan_number;
                }
               } else if($format == 4) {
                  if(isset($invoice)){
                   $yyyy = date('Y',strtotime($invoice->date));
                   $mm = date('m',strtotime($invoice->date));
                   $__number = $invoice->number;
                   $prefix = '<span id="prefix">'. $invoice->prefix . '</span>';
                 } else {
                  $yyyy = date('Y');
                  $mm = date('m');
                  $__number = $next_challan_number;
                }
               }

               $_is_challan = (isset($challan)) ? true : false;
               $next_challan_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
               if(isset($challan)){
                   $challan_nu = substr($challan->ChallanID,5); 
               }
               
               $_challan_number = str_pad($challan_nu, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
               $isedit = isset($challan) ? 'true' : 'false';
               $data_original_number = isset($challan) ? $challan->number : 'false';

               ?>
            <style>
			     thead, th{
			         top:0px;
			         position:sticky;
			         z-index:20;
			     }
			         .col-id-no{
			             left:0px;
			             position:sticky !important;
			             min-width:34px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         .fixed-header{
			             z-index:50;
			         }
			         .col-id-ordid{
			             left:34px;
			             position:sticky !important;
			             min-width:85px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         .col-id-custname{
			             left:119px;
			             position:sticky !important;
			             min-width:190px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         .col-id-custstate{
			             left:309px;
			             position:sticky !important;
			             min-width:46px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         .col-id-ordtype{
			             left:355px;
			             position:sticky !important;
			             min-width:78px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         .col-id-saleid{
			             left:433px;
			             position:sticky !important;
			             min-width:84px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         .col-id-saledate{
			             left:517px;
			             position:sticky !important;
			             min-width:84px;
			             background-color:#438eb9;
			             color:#fff;
			         }
			         th, td{
			             outline:1px solid #ccc;
			         }
			         /*tfoot{
			         bottom:0px;
			         position:sticky;
			         z-index:20;
			         background: #438EB9;
                     color: #FFF;
			     }*/
			     </style>   
               
               <!--<form class="info">-->
                        <div class="form-group">
                           <label for="number">
                              Challan Number
                             <!-- <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('invoice_number_not_applied_on_draft') ?>" data-placement="top"></i>-->
                        </label>
                           <div class="input-group">
                              <span class="input-group-addon">
                              <?php
                                echo $prefix;
                              ?>
                              </span>
                              <input type="text" name="number1" id="number1" class="form-control number1" value="<?php echo ($challan) ? $_challan_number : $next_challan_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($_is_draft) ? 'disabled' : '' ?>>
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
                              
                           </div>
                        </div>
                        
			                </div>
			
			                <div class="col-md-6">
			                 <input type="hidden" name="OldDate" id="OldDate" value="<?php if(isset($challan)){ echo $challan->Transdate; }?>">
			                 <input type="hidden" name="ChallanID" id="ChallanID" value="<?php if(isset($challan)){ echo $challan->ChallanID; }?>">
                                <input type="hidden" name="number" class="form-control" value="<?php echo ($challan) ? $prefix.$_challan_number : $next_challan_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($_is_draft) ? 'disabled' : '' ?>>    
                                    <?php 
                                    //$date_attrs['disabled'] = true;
                                    $date = substr($challan->Transdate,0,10);
                                    //echo $date;
                                   
                                $fy = $this->session->userdata('finacial_year');
                                $fy_new  = $fy + 1;
                                $lastdate_date = '20'.$fy_new.'-03-31';
                                $curr_date = date('Y-m-d');
                                $curr_date_new    = new DateTime($curr_date);
                                $last_date_yr = new DateTime($lastdate_date);
                                if($last_date_yr < $curr_date_new){
                                    $date1 = $lastdate_date;
                                }else{
                                    $date1 = date('Y-m-d');
                                }
                
                                    $value = (isset($challan) ? _d($date) : _d($date1));
                                      $date_attrs = array();
                                      
                                        //$date_attrs['disabled'] = true;
                                    echo render_date_input('date','Date',$value,$date_attrs); ?>
                                
			                </div>
			                <div class="col-md-12">
			             <?php
                                $selected = (isset($challan) ? $challan->route : '');
                                if(isset($challan)){
                                    $rstates = 'disabled';
                                }else{
                                    $rstates = '';
                                }
                            ?>
                                <div class="form-group">
                                    <label for="challan_route" class="control-label" ><small class="req text-danger">* </small> Route</label>
                                    <select class="selectpicker" name="challan_route" id="challan_route" data-width="100%"  data-action-box="true"  <?php echo $rstates; ?> data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""></option>
                                    <?php
                                        foreach($routes as $key => $value) {
                                        ?>
                                            <option value="<?php echo $value["RouteID"]?>" <?php if(isset($challan) && $challan->RouteID == $value['RouteID']){echo 'selected';} ?>><?php echo $value["name"]?></option>
                                    <?php
                                        }
                                    ?>
                                    
                                    </select>
                                </div>    
                                <?php
                                //print_r($route_ids);
                                ?>
			                </div>
			                <div class="col-md-6">
			                    
                                    <?php
                                        //echo $client->routes;
                                        $new_element = array(
                                            "id"=>0,
                                            "reg_no"=>"Transport Vehicle",
                                            "type"=>"other",
                                            "status"=>1,
                                            );
                                           
                                        $selected = (isset($challan) ? $challan->vehicle : '');
                                        $vehicle_ids = array();
                                        //echo render_select( 'challan_vehicle',$vehicle,array( 'id',array( 'reg_no')), 'Vehicle',$selected);
                                        ?>
                                <div class="form-group">
                                    <label for="challan_vehicle" class="control-label"><small class="req text-danger">* </small> Vehicle</label>
                                    <select class="selectpicker" name="challan_vehicle" id="challan_vehicle" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""></option>
                                    
                                    <?php
                                        foreach ($vehicle as $key => $value) {
                                            # code...
                                            array_push($vehicle_ids, $value['VehicleID']);
                                            //if(($value["VehicleID"] == $challan->VehicleID) || ($value["EngageID"] == NULL)){
                                            ?>
                                            <option value="<?php echo $value["VehicleID"]?>" <?php if(isset($challan) && $challan->VehicleID == $value['VehicleID']){echo 'selected';} ?>><?php echo $value["VehicleID"]?></option>
                                            <?php
                                            //}
                                        }
                                        
                                    ?>
                                    <option value="TV" <?php if(!in_array($challan->VehicleID, $vehicle_ids) && isset($challan)){ echo "selected"; }?>>Transport Vehicle</option>
                                    </select>
                                </div>
			                </div>
			                
			                
			                
			                <div class="col-md-6 cvn" id="custom_vehicle_number">
			                    <div class="form-group">
                                    <label for="number"><small class="req text-danger">* </small> Vehicle No.</label>
                                    <?php $value = (isset($challan) ? $challan->VehicleID : ''); ?>
                                    <input type="text" class="form-control" name="vahicle_number" id="vahicle_number" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                    
                                    
                                </div>
			                </div>
			                <?php //if(!in_array($challan->VehicleID, $vehicle_ids) && isset($challan)){ }else { ?>
			                <div class="col-md-6" id="capacity_div">
			                    <div class="form-group">
                                    <label for="number">Capacity</label>
                                    <?php 
                                    $vehicle_capacity = get_vehicle_capacity($challan->VehicleID);
                                    //print_r($vehicle_capacity);
                                    //$value = (isset($challan) ? $challan->vahicle_capacity : ''); ?>
                                    <input type="text" class="form-control" name="vahicle_capacity" id="vahicle_capacity" value="<?php echo $vehicle_capacity->VehicleCapacity; ?>" disabled>
                                    <!--<input type="hidden" name="vahicle_capacity" id="vahicle_capacity" value="<?php echo $value; ?>">
                                    -->
                                </div>
			                </div>
			                <?php //} ?>
			                
			            </div>
			        </div>
			        <div class="col-md-4">
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group" app-field-wrapper="challan_driver">
                                    <label for="challan_driver" class="control-label"> <small class="chldr text-danger">* </small>DriverID</label>
                                    <?php $value = (isset($challan) ? $challan->DriverID : ''); ?>
                                    <input type="text" class="form-control" name="challan_driver" id="challan_driver" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                </div>
			                </div>
			                <div class="col-md-8" style="margin-top: 20px;">
			                    <div class="form-group" app-field-wrapper="challan_driver_name">
                                    
                                    <?php $value = (isset($challan) ? $challan->driver_fn." ".$challan->driver_ln : ''); ?>
                                    <input readonly type="text" class="form-control" name="challan_driver_name" id="challan_driver_name" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group" app-field-wrapper="challan_loader">
                                    <label for="challan_loader" class="control-label">LoaderID</label>
                                    <?php $value = (isset($challan) ? $challan->LoaderID : ''); ?>
                                    <input type="text" class="form-control" name="challan_loader" id="challan_loader" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                </div>
			                </div>
			                <div class="col-md-8" style="margin-top: 20px;">
			                    <div class="form-group" app-field-wrapper="challan_loader_name">
                                    
                                    <?php $value = (isset($challan) ? $challan->loader_fn." ".$challan->loader_ln : ''); ?>
                                    <input readonly type="text" class="form-control" name="challan_loader_name" id="challan_loader_name" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group" app-field-wrapper="challan_sales_man">
                                    <label for="challan_sales_man" class="control-label">SalesManID</label>
                                    <?php $value = (isset($challan) ? $challan->SalesmanID : ''); ?>
                                    <input  type="text" class="form-control" name="challan_sales_man" id="challan_sales_man" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                </div>
			                </div>
			                <div class="col-md-8" style="margin-top: 20px;">
			                    <div class="form-group" app-field-wrapper="challan_sales_man_name">
                                    
                                    <?php $value = (isset($challan) ? $challan->Salesman_fn." ".$challan->Salesman_ln : ''); ?>
                                    <input readonly type="text" class="form-control" name="challan_sales_man_name" id="challan_sales_man_name" value="<?php echo $value; ?>" style="text-transform:uppercase">
                                </div>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-2">
			            <div class="row">
			                <div class="col-md-12">
			                    <div class="form-group">
                                    <label for="number">Challan Value</label>
                                    <?php $value = (isset($challan) ? $challan->ChallanAmt : '0'); ?>
                                    <input type="text" class="form-control" name="txtchalanvalue1" id="txtchalanvalue1" value="<?php echo $value; ?>"  disabled>
                                    <input type="hidden" name="txtchalanvalue" id="txtchalanvalue" value="<?php echo $value; ?>">
                                </div>
			                </div>
			                <div class="col-md-12">
			                    <div class="form-group">
                                    <label for="number">Total Cases</label>
                                    <?php $value = (isset($challan) ? $challan->Cases : ''); ?>
                                    <input type="text" class="form-control" name="txtCases1" id="txtCases1" value="<?php echo $value; ?>" disabled>
                                    <input type="hidden" name="txtCases" id="txtCases" value="<?php echo $value ?>">
                                </div>
			                </div>
			                <div class="col-md-12">
			                    <div class="form-group">
                                    <label for="number">Total Crates</label>
                                    <?php $value = (isset($challan) ? $challan->Crates : ''); ?>
                                    <input type="text" class="form-control" name="txtCrates1" id="txtCrates1" value="<?php echo $value; ?>" disabled>
                                    <input type="hidden" name="txtCrates" id="txtCrates" value="<?php echo $value ?>">
                                    <!--<input type="hidden" name="order_id" id="order_id" value="<?php echo $order->number; ?>">-->
                                    <input type="hidden" name="rate_changeID" id="rate_changeID" value="0">
                                    <input type="hidden" name="new_record" id="new_record" value="">
                                    <input type="hidden" name="LastValue" id="LastValue" value="">
                                    <input type="hidden" name="MaxCreditLimit" id="MaxCreditLimit" value="<?php echo $LimitCHK; ?>">
                                    
                                </div>
			                </div>
			                
			            </div>
			        </div>
			        <div class="col-md-2" style="margin-top: 11%;">
			            <a href="#" class="btn btn-danger updateRate_btn" style="display:none;">update Rate</a>
			         </div>
			        <div class="clearfix"></div>
			        <div class="col-md-12">
			            <span ><b style="color:red;">Note:</b> Item values in red color indicates old rates are applied.</span><br>
			         <?php
			         $gatepass = 0;
			            if(isset($challan) && $challan->Gatepassuserid !== NULL){
			                $gatepass = 1;
			              ?>
			              <span><b>Gatepass Generated</b></span>
			         <?php
			            }else{
			                $irn = '';
			                foreach ($Curchallan["order_ids"] as $key1 => $ids) {
                                if($ids["irn"] !== null){
                                    $irn = 'Y';
                                }
			                }
			                if($irn == "Y"){
			                    ?>
			                    <span><b>E-invoice Generated</b></span>
			                    <?php
			                }
			            }
			           ?>
			           <input type="hidden" name="gatepass" id="gatepass" value="<?php echo $gatepass; ?>">
			        </div>
			        <div class="clearfix"></div>
			        
			        <!-- order table-->
			        <div class="col-md-12">
			            <br>
			         <span id="searchh2" class="searchh2" style="display:none;">
			                Loading.....
			            </span>
			          <div id="showtable" class="showtable">
			     <?php
			          if($challan){
			             if($Curchallan["order_ids"]){
			     ?>
			     
			     
			     
			                <table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;height: 330px;"><thead style="background: #438EB9;color: #FFF;">
			                    
			                    <th class="col-id-no fixed-header">Tag</th>
                                <th class="col-id-ordid fixed-header">OrderNo</th>
                                <th class="col-id-custname fixed-header">AccountName</th>
                                <th class="col-id-custstate fixed-header">StateID</th>
                                <th class="col-id-ordtype fixed-header">Ordertype</th>
                                <th class="col-id-saleid fixed-header">SalesID</th>
                                <th class="col-id-saledate fixed-header">SalesDate</th>
                        <?php
                            foreach ($ORDItem as $code) {
                        ?>
                                <th width="5%"><?php echo $code; ?></th>
                        <?php    } ?>
                                <th>Crates</th>
                                <th>Cases</th>
                                <th>OrderAmt</th>
                                <th>SaleAmt</th>
                                <th>DiscAmt</th>
                                <th>CGSTAMT</th>
                                <th>SGSTAMT</th>
                                <th>IGSTAMT</th>
                                <th>TCSPer</th>
                                <th>TCSAmt</th>
                                <th>BillAmt</th>
                                </thead>
                        <?php
                            $challan_cases = 0;
                            $challan_crate = 0;
                            $challan_subtotal = 0;
                            $challan_total = 0;
                            $DiscAmtSum = 0;
                            $CGSTAMTSum = 0;
                            $SGSTAMTSum = 0;
                            $IGSTAMTSum = 0;
                        ?>
                                <tbody>
                <!--Existing Challan Details     -->
                        <?php
                        
                            foreach ($Curchallan["order_ids"] as $key1 => $ids) {
                                /*if($ids["irn"] !== null){
                                    $readonly = 'disabled';
                                    $readonly2 = 'readonly';
                                    $irn = "Y";
                                }else{
                                    $readonly = '';
                                    $readonly2 = '';
                                }*/
                        ?>
                                <tr class= "bg-an">
                                
                                <td scope="row" class="col-id-no"><input type="checkbox" name="order_id[]" class="chk " checked  value="<?php echo $ids["OrderID"]; ?>"><input type="hidden" name="OrderID" value="<?php echo $ids["OrderID"]; ?>"></td>
                                <?php
                                    $BAL = 0;
                                    foreach ($AccountBalances as $BalKey => $BalVal) {
                                        if($ids["AccountID"] === $BalVal["AccountID"]){
                                            $BAL = $BalVal["Balance"] - $ids["MaxCrdAmt"] - $ids["OrderAmt"];
                                        }
                                    }
                                ?>
                                <td scope="row" class="col-id-ordid"><input type="hidden" name="Balance" value="<?php echo $BAL; ?>"><?php echo $ids["OrderID"]; ?><input type="hidden" name="MaxCrdAmt" value="<?php echo $ids["MaxCrdAmt"];?>"></td>
                                
                                <td scope="row" class="col-id-custname"><?php echo $ids["company"]; ?></td>
                                <td scope="row" class="col-id-custstate"><?php echo $ids["state"]; ?></td>
                                
                                <td scope="row" class="col-id-ordtype"><?php echo $ids["OrderType"]; ?></td>
                            <?php
                                if($ids["istcs"] == "1"){
                                    $tcs = $TCSValue;
                                }else{
                                    $tcs = 0.00;
                                }
                            ?>
                                <td scope="row" class="col-id-saleid"><?php echo $ids["SalesID"]; ?><input type="hidden" name="istcs" value="<?php echo $tcs; ?>"></td>
                                <td scope="row" class="col-id-saledate"><?php echo _d(substr($ids["TransDate"],0,10)); ?></td>
                        <?php
                                $mm = 0;
                                $OrderSaleAmt = 0;
                                $OrderBillAmt = 0;
                                $DiscAmt = 0; 
                                $OSGST = 0; 
                                $OCGST = 0; 
                                $OIGST = 0; 
                                foreach ($ORDItem as $ItemIDc) {
                                    $isItem = '';
                                    foreach ($Curchallan["item_list"] as $key => $code) {
                                        if($code["ItemID"] == $ItemIDc){
                                            $matched = '';
                                            if($ids["OrderID"] == $code["OrderID"]){
                                                $isItem = 1;
                                                $pack_qty = $code["CaseQty"];
                                                $rate = $code["BasicRate"];
                                                $gst = $code["cgst"] + $code["sgst"] + $code["igst"];
                                                if($ids["state"] == "UP"){
                                                    $cscr = $code["local_supply_in"];
                                                }else{
                                                    $cscr = $code["outst_supply_in"];
                                                }
                                                
                                                $qty = $code["BilledQty"] / $code["CaseQty"];
                                                $OrderSaleAmt = $OrderSaleAmt + $code["OrderAmt"];
                                                $OrderBillAmt += $code["NetOrderAmt"];
                                                $DiscAmt += $code["DiscAmt"];
                                                $OSGST += $code["sgstamt"];
                                                $OCGST += $code["cgstamt"];
                                                $OIGST += $code["igstamt"];
                                            }
                                        }
                                    }
                                    //$stocks = $ItemStockDetails[$ItemIDc];
                                    $PQty = 0;
                                    $PRQty = 0;
                                    $IQty = 0;
                                    $PRDQty = 0;
                                    $SQty = 0;
                                    $SRQty = 0;
                                    $ADJQTY = 0;
                                    foreach ($ItemStockDetails as $key => $value) {
                                        if($value['ItemID'] == $ItemIDc){
                                            $oQty = $value['OQty'];
                                            $caseQty = $value['CaseQty'];
                                            if($value['TType'] == 'P'){
                                                $PQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'N'){
                                                $PRQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'A'){
                                                $IQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'B'){
                                                $PRDQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'O' && $value['TType2'] == 'Order'){
                                                $SQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'R' && $value['TType2'] == 'Fresh'){
                                                $SRQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'X' && $value['TType2'] == 'Stock Adjustment'){
                                                $ADJQTY += $value['BilledQty'];
                                            }elseif($value['TType'] == 'X' && $value['TType2'] == 'Promotional Activity'){
                                                $ADJQTY += $value['BilledQty'];
                                            }elseif($value['TType'] == 'X' && $value['TType2'] == 'Free Distribution'){
                                                $ADJQTY += $value['BilledQty'];
                                            }
                                        }
                                    }
                                    $balance = (float) $oQty + (float) $PQty - (float) $PRQty - (float) $IQty +  (float) $PRDQty - (float) $SQty + (float) $SRQty - (float) $ADJQTY;
                                    $balCase = $balance / $caseQty;
                                    if($isItem == ""){
                                    ?>
                                        <td width="5%" align="right" ></td>
                                    <?php
                                    }else{
                                        $value = $qty.'_'.$pack_qty.'_'.$rate.'_'.$gst.'_'.$cscr.'_'.$ids["state"].'_'.$balCase;
                                    ?>
                                        <td width="5%"><input type="hidden" value="<?php echo $value; ?>" id="qtyhidden"/><input type="hidden" id="orgqty_<?php echo $ids["OrderID"].'_'.$ItemIDc;?>" name="orgqty_<?php echo $ids["OrderID"].'_'.$ItemIDc;?>" value="<?php echo $qty; ?>"/><input style="width: 45px;" type="text" onchange="total(this,<?php echo $qty;?>)" name="qty_<?php echo $ids["OrderID"].'_'.$ItemIDc;?>" value="<?php echo $qty; ?>" ></td>
                                    <?php
                                    }
                                }
                                ?>
                                    <td style="text-align: right;"><?php echo $ids["Crates"]; ?>
                                    <?php
                                        if($mm > 0){
                                    ?>
                                            <input type="hidden" name="rate_change" id="rate_change" value="Y">
                                    <?php
                                        }
                                    ?>
                                        </td>
                            <?php        
                                $challan_crate = $challan_crate + $ids["Crates"];
                            ?>
                                <td style="text-align: right;"><?php echo $ids["Cases"]; ?></td>
                            <?php
                                $challan_cases = $challan_cases + $ids["Cases"];
                                // bill Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OrderBillAmt; ?></td>
                            <?php
                                $challan_total = $challan_total + $OrderBillAmt;
                                //sale Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OrderSaleAmt; ?></td>
                            <?php
                                $challan_subtotal = $challan_subtotal + $OrderSaleAmt;
                                // Disc Amt
                            ?>
                                <td style="text-align: right;"><?php echo $DiscAmt; ?></td>
                            <?php
                                $DiscAmtSum = $DiscAmtSum + $DiscAmt;
                                // CGST Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OCGST; ?></td>
                            <?php
                                $CGSTAMTSum = $CGSTAMTSum + $OCGST;
                                // SGST Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OSGST; ?></td>
                            <?php
                                $SGSTAMTSum = $SGSTAMTSum + $OSGST;
                                // IGST Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OIGST; ?></td>
                            <?php
                                $IGSTAMTSum = $IGSTAMTSum + $OIGST;
                                // TCS Amt
                            ?>
                                <td style="text-align: right;"><input type="hidden" name="tcsper" value="<?php echo $tcs; ?>"><?php echo $tcs; ?></td>
                            <?php
                                if($tcs == "0.00"){
                                    $tcsAmt = 0.00;
                                }else{
                                    $tcsAmt = ($OrderBillAmt / 100) * $TCSValue;
                                }
                                 $CHLTCSAmt += $tcsAmt;
                            ?>    
                                <td style="text-align: right;"><?php echo round($tcsAmt,2); ?></td>
                            <?php
                                // Bill Amt Include TCSAMT
                                $finalBillAmt = $OrderBillAmt + $tcsAmt;
                            ?>
                                <td style="text-align: right;"><?php echo round($finalBillAmt,2); ?><input type="hidden" name="FBilAmt" id="FBilAmt" value="<?php echo round($finalBillAmt,2); ?>"></td>
                                </tr>
                            <?php
                            }
                        ?>
                <!-- releted to Route-->
                        <?php
                            foreach ($get_order_list["order_ids"] as $key1 => $ids) {
                        ?>
                                <tr>
                                <td><input type="checkbox" name="order_id[]" class="chk" value="<?php echo $ids["OrderID"]; ?>"><input type="hidden" name="OrderID" value="<?php echo $ids["OrderID"]; ?>"></td>
                                <?php
                                    $BAL = 0;
                                    foreach ($AccountBalances as $BalKey => $BalVal) {
                                        if($ids["AccountID"] === $BalVal["AccountID"]){
                                            $BAL = $BalVal["Balance"] - $ids["MaxCrdAmt"];
                                        }
                                    }
                                ?>
                                <td><input type="hidden" name="Balance" value="<?php echo $BAL; ?>"><input type="hidden" name="MaxCrdAmt" value="<?php echo $ids["MaxCrdAmt"];?>"><?php echo $ids["OrderID"]; ?></td>
                                
                                <td><?php echo $ids["company"]; ?></td>
                                <td><?php echo $ids["state"]; ?></td>
                                
                                <td><?php echo $ids["OrderType"]; ?></td>
                            <?php
                                if($ids["istcs"] == "1"){
                                    $tcs = $TCSValue;
                                }else{
                                    $tcs = 0.00;
                                }
                            ?>
                                <td><input type="hidden" name="istcs" value="<?php echo $tcs; ?>"></td>
                                <td></td>
                        <?php
                                $mm = 0;
                                $OrderSaleAmt = 0;
                                $OrderBillAmt = 0;
                                $DiscAmt = 0; 
                                $OSGST = 0; 
                                $OCGST = 0; 
                                $OIGST = 0; 
                                foreach ($ORDItem as $ItemIDc) {
                                    $isItem = '';
                                    foreach ($get_order_list["item_list"] as $key => $code) {
                                        if($code["ItemID"] == $ItemIDc){
                                            $matched = '';
                                            if($ids["OrderID"] == $code["OrderID"]){
                                                $isItem = 1;
                                                
                                                foreach ($ItemRate as $key2 => $code2) {
                                                    if($code2["item_id"]==$code["ItemID"] && $ids["state"] == $code2["state_id"] && $ids["DistributorType"]==$code2["distributor_id"] && $code["BasicRate"] !== $code2["assigned_rate"]){
                                                        $matched= 'color:red;';
                                                        $rr = $code2["assigned_rate"];
                                                        $mm++;
                                                        
                                                    }
                                                }
                                
                                                $pack_qty = $code["CaseQty"];
                                                $rate = $code["BasicRate"];
                                                $gst = $code["cgst"] + $code["sgst"] + $code["igst"];
                                                if($ids["state"] == "UP"){
                                                    $cscr = $code["local_supply_in"];
                                                }else{
                                                    $cscr = $code["outst_supply_in"];
                                                }
                                                
                                                $qty = $code["orderqty"] / $code["CaseQty"];
                                                $OrderSaleAmt = $OrderSaleAmt + $code["OrderAmt"];
                                                $OrderBillAmt += $code["NetOrderAmt"];
                                                $DiscAmt += $code["DiscAmt"];
                                                $OSGST += $code["sgstamt"];
                                                $OCGST += $code["cgstamt"];
                                                $OIGST += $code["igstamt"];
                                            }
                                        }
                                    }
                                    
                                    $PQty = 0;
                                    $PRQty = 0;
                                    $IQty = 0;
                                    $PRDQty = 0;
                                    $SQty = 0;
                                    $SRQty = 0;
                                    $ADJQTY = 0;
                                    foreach ($ItemStockDetails as $key => $value) {
                                        if($value['ItemID'] == $ItemIDc){
                                            $oQty = $value['OQty'];
                                            $caseQty = $value['CaseQty'];
                                            if($value['TType'] == 'P'){
                                                $PQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'N'){
                                                $PRQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'A'){
                                                $IQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'B'){
                                                $PRDQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'O' && $value['TType2'] == 'Order'){
                                                $SQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'R' && $value['TType2'] == 'Fresh'){
                                                $SRQty = $value['BilledQty'];
                                            }elseif($value['TType'] == 'X' && $value['TType2'] == 'Stock Adjustment'){
                                                $ADJQTY += $value['BilledQty'];
                                            }elseif($value['TType'] == 'X' && $value['TType2'] == 'Promotional Activity'){
                                                $ADJQTY += $value['BilledQty'];
                                            }elseif($value['TType'] == 'X' && $value['TType2'] == 'Free Distribution'){
                                                $ADJQTY += $value['BilledQty'];
                                            }
                                        }
                                    }
                                    $balance = (float) $oQty + (float) $PQty - (float) $PRQty - (float) $IQty +  (float) $PRDQty - (float) $SQty + (float) $SRQty - (float) $ADJQTY;
                                    $balCase = $balance / $caseQty;
                                    $stockless = '';
                                    if($balCase < $qty){
                                        $stockless= 'color:red;';
                                    }
                                    if($isItem == ""){
                                    ?>
                                        <td width="5%" align="right" ></td>
                                    <?php
                                    }else{
                                        $value = $qty.'_'.$pack_qty.'_'.$rr.'_'.$gst.'_'.$cscr.'_'.$ids["state"].'_'.$balCase;
                                    ?>
                                        <td width="5%"><input type="hidden" value="<?php echo $value; ?>" id="qtyhidden"/><input type="hidden" id="orgqty_<?php echo $ids["OrderID"].'_'.$ItemIDc;?>" name="orgqty_<?php echo $ids["OrderID"].'_'.$ItemIDc;?>" value="<?php echo $qty; ?>"/><input style="width: 45px;<?php echo $matched;?>" type="text" onchange="total()" name="qty_<?php echo $ids["OrderID"].'_'.$ItemIDc;?>" value="<?php echo $qty; ?>"></td>
                                    <?php
                                    }
                                }
                                ?>
                                    <td style="text-align: right;"><?php echo $ids["Crates"]; ?>
                                    <?php
                                        if($mm > 0){
                                    ?>
                                            <input type="hidden" name="rate_change" id="rate_change" value="Y">
                                    <?php
                                        }
                                    ?>
                                        </td>
                            <?php        
                                $challan_crate = $challan_crate + $ids["Crates"];
                            ?>
                                <td style="text-align: right;"><?php echo $ids["Cases"]; ?></td>
                            <?php
                                $challan_cases = $challan_cases + $ids["Cases"];
                                // bill Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OrderBillAmt; ?></td>
                            <?php
                                $challan_total = $challan_total + $OrderBillAmt;
                                //sale Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OrderSaleAmt; ?></td>
                            <?php
                                $challan_subtotal = $challan_subtotal + $OrderSaleAmt;
                                // Disc Amt
                            ?>
                                <td style="text-align: right;"><?php echo $DiscAmt; ?></td>
                            <?php
                                $DiscAmtSum = $DiscAmtSum + $DiscAmt;
                                // CGST Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OCGST; ?></td>
                            <?php
                                $CGSTAMTSum = $CGSTAMTSum + $OCGST;
                                // SGST Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OSGST; ?></td>
                            <?php
                                $SGSTAMTSum = $SGSTAMTSum + $OSGST;
                                // IGST Amt
                            ?>
                                <td style="text-align: right;"><?php echo $OIGST; ?></td>
                            <?php
                                $IGSTAMTSum = $IGSTAMTSum + $OIGST;
                                // TCS Amt
                            ?>
                                <td style="text-align: right;"><input type="hidden" name="tcsper" value="<?php echo $tcs; ?>"><?php echo $tcs; ?></td>
                            <?php
                                if($tcs =="0.00"){
                                    $tcsAmt = 0.00;
                                }else{
                                    $tcsAmt = ($OrderBillAmt / 100) * $tcs;
                                }
                                $CHLTCSAmt += $tcsAmt;
                            ?>    
                                <td style="text-align: right;"><?php echo round($tcsAmt,2); ?></td>
                            <?php
                                // Bill Amt Include TCSAMT
                                $finalBillAmt = $OrderBillAmt + $tcsAmt;
                            ?>
                                <td style="text-align: right;"><?php echo round($finalBillAmt,2); ?><input type="hidden" name="FBilAmt" id="FBilAmt" value="<?php echo round($finalBillAmt,2); ?>"></td>
                                </tr>
                            <?php
                            }
                        ?>
                                    
                                </tbody>
                                <tfoot>
			                 <tr>
			                    <td style="text-align:center;" scope="row" class="col-id-no">Total</td>
			                    <td scope="row" class="col-id-ordid"></td>
			                    <td scope="row" class="col-id-custname"></td>
			                    <td scope="row" class="col-id-custstate"></td>
			                    <td scope="row" class="col-id-ordtype"></td>
			                    <td scope="row" class="col-id-saleid"></td>
			                    <td scope="row" class="col-id-saledate"></td> 
			                    <?php
			                    foreach ($ORDItem as $code) {
                                    foreach ($AllItemSum as $keys => $values) {
                                        if($code == $values['ItemID']){
                                            $ItemSum = $values['OrderQty'] / $values['CaseQty'];
                                            ?>
                                                <td style="text-align: right;"><?php echo $ItemSum; ?></td>
                                            <?php
                                        }
                                    }
                                ?>
                                    
                            <?php    }
			                    ?>
			                    <td style="text-align: right;"><?php echo $challan_crate; ?></td>
			                    <td style="text-align: right;"><?php echo $challan_cases; ?></td>
			                    <td style="text-align: right;"><?php echo $challan_subtotal; ?></td>
			                    <td style="text-align: right;"><?php echo $challan_total; ?></td>
			                    <td style="text-align: right;"><?php echo $DiscAmtSum; ?></td>
			                    <td style="text-align: right;"><?php echo $CGSTAMTSum; ?></td>
			                    <td style="text-align: right;"><?php echo $SGSTAMTSum; ?></td>
			                    <td style="text-align: right;"><?php echo $IGSTAMTSum; ?></td>
			                    <td style="text-align: right;"></td>
			                    <td style="text-align: right;"><?php echo round($CHLTCSAmt,2); ?></td>
			                    <td style="text-align: right;"><?php echo $challan_total; ?></td>
			                 </tr>
			             </tfoot>
			                </table>
			    <?php   } 
			    
			         }
			     ?>
			            </div>
			        </div>
			        
			        <div class="col-md-12">
			        <div class="row">
                        <div class="col-md-12 mtop15">
			            
			                <div class="btn-bottom-toolbar text-right">
			                    <?php
			                    if($challan){
			                        if (has_permission_new('challan_list', '', 'edit')) {
			                        ?>
			                 <?php
			            if(isset($challan) && $challan->Gatepassuserid == NULL){
			                if($irn == ''){
			              ?>
			                 <button type="submit" class="btn-tr btn btn-info invoice-form-submit transaction-submit">Update</button>
			                 <?php } }else{ ?>
			                 <a href="#" class="btn-tr btn btn-info disabled">Update</a>
			                <?php
			                    }
			                 }?>
			                 <!--<button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit">Dispatch Sheet</button>-->
			                 <!--<a href="<?php echo admin_url('challan/gatepass/'.$challan->ChallanID.'?output_type=I'); ?>" target="_blank" class="mleft10 pull-right btn btn-success">
                     <i class="fa fa-eye"></i> Gate Pass </a>-->
                     <?php if (has_permission_new('challan_list', '', 'view')) { ?>
			                 <a href="<?php echo admin_url('challan/dispatchsheet/'.$challan->ChallanID.'?output_type=I'); ?>" target="_blank" class="mleft10 pull-right btn btn-success">
                     <i class="fa fa-eye"></i> Dispatch Sheet </a>
                     <?php } ?>
			                 <!--<button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit">-->
			     <?php if (has_permission_new('challan_list', '', 'view')) { ?>
			                 <!--<a href="<?php echo admin_url('challan/pdf/'.$challan->ChallanID.'?output_type=I'); ?>" target="_blank" class="mleft10 pull-right btn btn-success">
                     <i class="fa fa-eye"></i> Invoice Print </a> -->
                
                     <button type="button" class="mleft10 pull-right btn btn-success InvoicePrint"><i class="fa fa-eye"></i> Invoice Print </button>
                     <?php } ?>
			                     <!--</button>-->
			                
			                 <?php
			                    }else {
			                    ?>
			         <?php if (has_permission_new('challan', '', 'create')) { ?>
			                    <button type="submit" class="btn-tr btn btn-info invoice-form-submit transaction-submit  save_challan">Save</button>
			           <?php } ?>         
			                    
			                    <?php } ?>
			                    </div>
			              
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
			   </div>
			   </div>
			</div>
		</div>
	</div>
</div>

<?php init_tail(); ?>

<?php 
			                //$vehicle_detail = get_vehicle_detail($challan->VehicleID);
			                //print_r($vehicle_detail);
			                if(!in_array($challan->VehicleID, $vehicle_ids) && isset($challan)){}else{
			                    ?>
			                    <script>
			                        $("#custom_vehicle_number").hide();
			                    </script>
			                    <?php
			                }
			                if(!in_array($challan->VehicleID, $vehicle_ids) && isset($challan)){
			                    ?>
			                    <script>
			                        $("#capacity_div").hide();
			                    </script>
			                    <?php
			                }
			                ?>
<?php $this->load->view('admin/challan/challanJSNew'); ?>
<?php $this->load->view('admin/challan/validateJSNew'); ?>
</body>
<style>
    #challan_data th,td{
        padding:1px 3px;
        border:1px solid #ccc;
    }
    #challan_data input[type=text] {
        height: 27px;
        text-align: right;
    }
    .bg-an {
    background-color: #65baba;
}
</style>
<script>
$(document).ready(function(){
    
    $('#challan_driver').on('focus',function(){
            $('#challan_driver').val('');
            $('#challan_driver_name').val('');
       
     });
    // Initialize For Account
     $( "#challan_driver" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/challan/accountlist_driver",
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
          $('#challan_driver').val(ui.item.value);
          $('#challan_driver_name').val(ui.item.label);
            return false;
        }
      });
      
    $('#challan_driver').on('blur', function () {
      
        var AccountID = $(this).val();
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/challan/get_Account_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#challan_driver").val('');
                             $("#challan_driver_name").val('');
                            $("#challan_driver").focus();
                        }else{
                            var fullName = data.firstname +" " + data.lastname;
                            $('#challan_driver_name').val(fullName); // display the selected text
                          
                        }
                    }
                });
            }
    });
    
    $('#challan_loader').on('focus',function(){
            $('#challan_loader').val('');
            $('#challan_loader_name').val('');
       
     });
    // Initialize For Account
     $( "#challan_loader" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/challan/accountlist_Loader",
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
          $('#challan_loader').val(ui.item.value);
          $('#challan_loader_name').val(ui.item.label);
            return false;
        }
      });
      
    $('#challan_loader').on('blur', function () {
      
        var AccountID = $(this).val();
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/challan/get_Loader_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#challan_loader").val('');
                            $("#challan_loader_name").val('');
                            $("#challan_loader").focus();
                        }else{
                            var fullName = data.firstname +" " + data.lastname;
                            $('#challan_loader_name').val(fullName); // display the selected text
                          
                        }
                    }
                });
            }
    });
    
    $('#challan_sales_man').on('focus',function(){
            $('#challan_sales_man').val('');
            $('#challan_sales_man_name').val('');
       
     });
     
    // Initialize For Account
     $( "#challan_sales_man" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/challan/accountlist_salesMan",
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
          $('#challan_sales_man').val(ui.item.value);
          $('#challan_sales_man_name').val(ui.item.label);
            return false;
        }
      });
      
    $('#challan_sales_man').on('blur', function () {
      
        var AccountID = $(this).val();
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/challan/get_Account_Details_salesman",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#challan_sales_man").val('');
                            $("#challan_sales_man_name").val('');
                            $("#challan_sales_man").focus();
                        }else{
                            var fullName = data.firstname +" " + data.lastname;
                            $('#challan_sales_man_name').val(fullName); // display the selected text
                          
                        }
                    }
                });
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
    
    $('#date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    
    
    });
</script> 
</html>
