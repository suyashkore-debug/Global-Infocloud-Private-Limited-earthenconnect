<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .form-group{
        margin-bottom: 1px;
    }
    input[type=text]{
        height: 29px !important;
    }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form'));
			
			?>
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
      
            <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                     <?php
                                $data_attr = array();
                                if(isset($return_details)){
                                    $data_attr = array(
                                        "disabled" =>true
                                        );
                                }
                                ?>    
               
                          
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php if(isset($return_details)){
                                        $crate_list_count = count($return_crate_list);
                                        $crate_list_count2 = count($return_crate_list_new_added);
                                        $SRtnItem_count = count($return_saleRtn_Itemlist);
                                        $SRtn_count = count($return_saleRtn_list["data"]);
                                        $Payment_count = count($return_payment_list);
                                        $Expense_count = count($return_expense_list);
                                        if($return_expense_list[0]['Aid'] == ""){
                                            $Expense_count = 0;
                                        }
                                        if($return_payment_list[0]['Aid'] == ""){
                                            $Payment_count = 0;
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="row_count" value="<?php echo $crate_list_count + $crate_list_count2; ?>" id="row_count">
                                    <input type="hidden" name="row_count_frRtn" value="<?php echo $SRtn_count; ?>" id="row_count_frRtn">
                                    <input type="hidden" name="row_count_pay" value="<?php echo $Payment_count; ?>" id="row_count_pay">
                                    <input type="hidden" name="row_count_exp" value="<?php echo $Expense_count; ?>" id="row_count_exp">
                                    <input type="hidden" name="ItemCount" value="<?php echo $SRtnItem_count; ?>" id="ItemCount">
                                    
                                    <input type="hidden" name="ex_vehicle_return_id" id="ex_vehicle_return_id" value="<?php if(isset($return_details)){ echo $return_details['ReturnID'];}?>">
                                    <input type="text" readonly name="vehicle_return_id" id="vehicle_return_id" class="form-control vehicle_return_id" value="<?php if(isset($return_details)){ echo  $return_details['ReturnID']; } ?>">
                                    <?php if(isset($return_details)){
                                    ?>
                                    <input type="hidden" name="updated_record" value="<?= $return_details['ReturnID'];?>" id="updated_record">
                                    <input type="hidden" name="new_record" value=" " id="new_record">
                                    <?php } ?>
                                </div>
                            </div>
                           
                            <div class="col-md-2">
                                <?php $vrtn_date = (isset($return_details) ? _d(substr($return_details['returnTransdate'],0,10)) : _d(date('Y-m-d')));
                                ?>
                               <input type="hidden" name="old_date" value="<?php echo substr($return_details['returnTransdate'],0,10); ?>" id="old_date">
                                <?php echo render_date_input('VrtnDate','',$vrtn_date); ?>
                            </div>
                        <!--<div class="col-md-3">
                           
                        </div>
                        <div class="col-md-2">
                        </div>-->
                       
                         <div class="col-md-1">
                          <span></span><a href="#" class="btn btn-warning edit-vehicle_return">View List</a>
                        </div>
                          
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                           <div class="form-group ">
                                <input type="text" readonly name="challan_n" placeholder="Challan No" id="challan_n" class="form-control "  value="<?= $return_details['ChallanID'];?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                           <?php $chl_date = (isset($return_details) ? _d(substr($return_details['Transdate'],0,10)) : _d(date('Y-m-d')));
                            echo render_date_input('challan_date','',$chl_date,$data_attr); ?>
                        </div>
                        <div class="col-md-6 ">
                           
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                               
                                <input type="text" readonly="" class="form-control" placeholder="Route code" value="<?= $return_details['RouteID'];?>" name="route_code" id="route_code"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <input type="text" readonly="" class="form-control" name="route_name" id="route_name" value="<?= $return_details['name'];?>" aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-2 ">
                            <div class="form-group">
                                <input type="text" readonly="" class="form-control" name="routekm" id="routekm" value="<?= $return_details['KM'];?>"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3"></div>
               
                       
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                         <div class="form-group">
                                <input type="text" readonly="" class="form-control"  value="<?= $return_details['VehicleID'];?>" name="vehicle_number" id="vehicle_number" placeholder="vehicle" aria-invalid="false">
                            </div>
                    </div>
                    <div class="col-md-3 ">
                       
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" readonly="" placeholder="Vehi.Capacity" value="<?= $return_details['VehicleCapacity'];?>" class="form-control" name="vehicle_capc" id="vehicle_capc"  aria-invalid="false">
                         </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" placeholder="CaseDeposit" class="form-control" value="<?= $return_payment_sum->creditsum; ?>" name="case_depo1" id="case_depo1"  aria-invalid="false">
                           <input type="hidden" value="<?= $return_payment_sum->creditsum; ?>" name="case_depo" id="case_depo">
                        </div>
                    </div>
                    
                    
                    <div class="col-md-2">
                        
                    </div>
                    
                </div> 
                   
              
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" readonly="" class="form-control" value="<?= $return_details['DriverID'];?>" placeholder="Driver" name="driver_id" id="driver_id"  aria-invalid="false">
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" value="<?= $return_details['driver_fn'].' '.$return_details['driver_ln'];?>" name="driver_name" id="driver_name"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" value="<?= $return_details['Crates'];?>" placeholder="ChallanCrates" name="challan_crates" id="challan_crates"  aria-invalid="false">
                           
                        </div>
                    </div>
                  
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" name="check_depo" id="check_depo" placeholder="CheckDeposite" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" readonly="" class="form-control" value="<?= $return_details['LoaderID'];?>" placeholder="Loder" name="loder_id" id="loder_id"  aria-invalid="false">
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" value="<?= $return_details['loader_fn'].' '.$return_details['loader_ln'];?>" name="loder_name" id="loder_name"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" value="<?= $return_details['return_crates'];?>" placeholder="RefundCrates" name="refund_crates" id="refund_crates"  aria-invalid="false">
                           
                        </div>
                    </div>
                  
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" name="NERT_trans" id="NERT_trans" placeholder="NERT/Transfer" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" readonly="" class="form-control" value="<?= $return_details['SalesmanID'];?>" placeholder="Sales Man" name="salesman_id" id="salesman_id"  aria-invalid="false">
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" value="<?= $return_details['Salesman_fn'].' '.$return_details['Salesman_fn'];?>" name="salesman_name" id="salesman_name"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" placeholder="FreshReturn Amt" value="<?= $return_saleRtn_sum->salertnsum; ?>" name="fresh_ret_amt1" id="fresh_ret_amt1"  aria-invalid="false">
                           <input type="hidden" name="fresh_ret_amt" id="fresh_ret_amt" value="<?= $return_saleRtn_sum->salertnsum; ?>" aria-invalid="false">
                        </div>
                    </div>
                    <?php  $expense_amount =0;
                    foreach($return_expense_list as $value){
                        $expense_amount+=$value['expense_Amount'];
                    }
                    ?>
                  
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text"  class="form-control" value="<?= $expense_amount;?>" name="total_expense1" id="total_expense1" placeholder="TotalExpenses" aria-invalid="false">
                           <input type="hidden"  value="<?= $expense_amount;?>" name="total_expense" id="total_expense" >
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="panel-body mtop10">
        <div class="row col-md-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home" class="crate_details">Crate Details</a></li>
                <li><a data-toggle="tab" href="#menu1" class="fresh_stock_return">Fresh Stock Return</a></li>
                <li><a data-toggle="tab" href="#menu2" class="payment_reciept" >Payment Reciept</a></li>
                <li><a data-toggle="tab" href="#menu3" class="expense_details" >Expense Details</a></li>
            </ul>
  
            <div class="" id="crate_details" display="">
                
                <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered crate_details_tbl" id="crate_details_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>AccountID</th>
                                        <th>AccoutName</th>
                                        <th>Address</th>
                                        <th>OpeningCrates</th>
                                        <th>ChallanCrates</th>
                                        <th>RtnCrates</th>
                                        <th>BalanceCrates</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <tr class="accounts" id="row">
                                        <td id="AccountIDTD" style="width: 125px;"><input type="text" name="AccountID" style="width: 125px;" id="AccountID"></td>
                                        <td style="padding:1px 5px !important;"><span id="party_name"></span><input type="hidden" name="party_name_val" id="party_name_val"></td>
                                        <td style="padding:1px 5px !important;"><span id="address"></span><input type="hidden" name="address_val" id="address_val" value="" ></td>
                                        <td style="padding:1px 5px !important;text-align:right;"><span id="opnCrates"></span><input type="hidden" name="opnCrates_val" id="opnCrates_val"></td>
                                        <td style="padding:1px 5px !important;text-align:right;"><span id="chlCrates"><input type="hidden" name="chlCrates_val" id="chlCrates_val"></td>
                                        <td class="rtnqty" style="width: 80px;">
                                            <input type="text" name="rtncrates" id="rtncrates" onblur="calculate_balcrates();"  style="width: 80px;text-align:right;" onkeypress="return isNumber(event)">
                                            <input type="hidden" name="balcrates" id="balcrates" value="0">
                                            <input type="hidden" name="colno" id="colno" value="">
                                        </td>
                                        <td style="padding:1px 5px !important;text-align:right;"><span id="balCrates_new"></span><input type="hidden" name="balCrates_new_val" id="balCrates_new_val" class="form-control"></td>
                                    </tr>
                                <?php
                                $i = 1;
                                foreach ($return_crate_list as $key => $value) {
                                        # code...
                                    ?>
                                    <tr class="accounts" id="row<?php echo $i ?>">
                                        <td style="width: 125px;"><input type="text" name="AccountID<?php echo $i ?>" style="width: 125px;" id="AccountID<?php echo $i ?>" value="<?php echo $value['act_id'];?>"></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value['company'];?></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value['address'];?></td>
                                        <td style="padding:1px 5px !important;text-align:right;"><?php echo $value['Qty'];?></td>
                                        <td style="padding:1px 5px !important;text-align:right;"><?php echo $value['Crates'];?></td>
                                        <?php
                                        $befor_crate_bal = $value['balance_crates'] + $value['crate_data_qty'];
                                        ?>
                                        <td class="rtnqty" style="width: 80px;"><input type="text" name="rtncrates<?php echo $i ?>" id="rtncrates<?php echo $i ?>" onblur="calculate_balcrates();" value="<?php echo $value['crate_data_qty'];?>"  style="width: 80px;text-align:right;" onkeypress="return isNumber(event)"><input type="hidden" name="balcrates<?php echo $i ?>" id="balcrates<?php echo $i ?>" value="<?php echo $befor_crate_bal; ?>"><input type="hidden" name="colno" id="colno" value="<?php echo $i ?>"></td>
                                        <td id="balCrates" style="padding:1px 5px !important;text-align:right;"><span><?php echo $value['balance_crates'];?></span></td>
                                    </tr>
                                <?php
                                $i++;
                                }
                                ?>
                                
                                <?php
                                
                                foreach ($return_crate_list_new_added as $key => $value) {
                                        # code...
                                    ?>
                                    <tr class="accounts" id="row<?php echo $i ?>">
                                        <td style="width: 125px;"><input type="text" name="AccountID<?php echo $i ?>" class="form-control" id="AccountID<?php echo $i ?>" value="<?php echo $value['AccountID']; ?>"></td>
                                        <td><?php echo $value['company'];?></td>
                                        <td><?php echo $value['address'];?></td>
                                        <td><?php echo $value['open_qty'];?></td>
                                        <td></td>
                                        
                                        <td class="rtnqty" style="width: 80px;"><input type="text" name="rtncrates<?php echo $i ?>" id="rtncrates<?php echo $i ?>"  onblur="calculate_balcrates();" value="<?php echo $value['Qty'];?>" style="width: 80px;text-alig:right;" onkeypress="return isNumber(event)"><input type="hidden" name="balcrates<?php echo $i ?>" id="balcrates<?php echo $i ?>" value="<?php echo $value['balance_crates']; ?>"><input type="hidden" name="colno" id="colno" value="<?php echo $i ?>"></td>
                                        <td id="balCrates"><span><?php echo $value['currbalance_crates'];?></span></td>
                                    </tr>
                                <?php
                                $i++;
                                }
                                ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div> 
            <?php echo form_hidden('crate_details'); ?>
          
            <div class="" id="fresh_stock_return" style="display:none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered stock_details_tbl fixed_header1" id="stock_details_tbl" width="100%">
                                <thead id="thead">
                                    <tr>
                                        <th align="center" width="5%">AccountID</th>
                                        <th align="center" width="15%">AccoutName</th>
                                        <th align="center" width="10%">SaleID</th>
                                        <th align="center" width="5%">RtnAMT</th>
                                        <th align="center" width="5%">CGST</th>
                                        <th align="center" width="5%">SGST</th>
                                        <th align="center" width="5%">IGST</th>
                                    <?php
                                    foreach ($return_saleRtn_list["itemhead"] as $value2) {
                                    ?>
                                        <th align="center" width="10%"><?php echo $value2; ?></th>
                                    <?php } ?>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                <?php
                                $J = 1;
                                $itemCount = 0;
                                foreach ($return_saleRtn_list["data"] as $key22 => $value22) {
                                        # code...
                                    ?>
                                    <tr class="accounts" id="row<?php echo $J ?>">
                                        <td style="padding:1px 5px !important;"><?php echo $value22["AccountID"];?><input type="hidden" name="AccountID_SRtn<?php echo $J ?>" id="AccountID_SRtn<?php echo $J ?>" value="<?php echo $value22["AccountID"]; ?>"></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value22["company"];?></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value22["SalesID"];?></td>
                                        <?php
                                        $totaRtmAmt = 0;
                                        $total_igst = 0;
                                        $total_cgst = 0;
                                        $total_sgst = 0;
                                foreach ($return_saleRtn_list["itemhead"] as $value2) {
                                    foreach ($value22["itemdetails"] as $key2222 => $value2222) {
                                        if($value2==$value2222["ItemID"]){
                                        $totaRtmAmt = $totaRtmAmt + $value2222['RtnChallanAmt'];
                                        $total_igst = $total_igst + $value2222['Rtnigstamt'];
                                        $total_cgst = $total_cgst + $value2222['Rtncgstamt'];
                                        $total_sgst = $total_sgst + $value2222['Rtnsgstamt'];
                                        
                                    } 
                                    }
                                }?>
                                        <td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="RtnAmt_val<?php echo $J ?>" id="RtnAmt_val<?php echo $J ?>" value="<?php echo $totaRtmAmt; ?>"><span id="RtnAmt"><?php echo $totaRtmAmt; ?></span></td>
                                        <td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="cgst_val<?php echo $J ?>" id="cgst_val<?php echo $J ?>" value="<?php echo $total_cgst; ?>"><span id="cgst"><?php echo $total_cgst; ?></span></td>
                                        <td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="sgst_val<?php echo $J ?>" id="sgst_val<?php echo $J ?>" value="<?php echo $total_sgst; ?>"><span id="sgst"><?php echo $total_sgst; ?></span></td>
                                        <td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="igst_val<?php echo $J ?>" id="igst_val<?php echo $J ?>" value="<?php echo $total_igst; ?>"><span id="igst"><?php echo $total_igst; ?></span></td>
                                    <?php
                                    $k = 1;
                                    foreach ($return_saleRtn_list["itemhead"] as $value2) {
                                        $match = 0;
                                        $rate = 0;
                                        $gst = 0;
                                        $BilledQty = 0;
                                        $ItemID = 0;
                                        $TransID = 0;
                                        $AccountID = 0;
                                        $PackQty = 0;
                                        $state = '';
                                    ?>
                                    <?php
                                    foreach ($value22["itemdetails"] as $key222 => $value222) {
                                        if($value2==$value222["ItemID"] && $value22["SalesID"] == $value222["TransID"]){
                                            $itemCount = $itemCount + 1;
                                            $match = 1;
                                            $rate = $value222["BasicRate"];
                                            $BilledQty = $value222["BilledQty"];
                                            $ORDBilledQty = $value222["OrdBilledQty"];
                                            $ItemID = $value222["ItemID"];
                                            $TransID = $value222["TransID"];
                                            $AccountID = $value222["AccountID"];
                                            $PackQty = $value222["CaseQty"];
                                            if($value222["igst"]=="0.00"){
                                                $gst = $value222["cgst"] * 2;
                                                $state = "UP";
                                            }else{
                                                $gst = $value222["igst"];
                                                $state = "UP1";
                                            }
                                        }
                                    } 
                                    if($match == "1"){
                                        ?>
                                        <td style="width: 80px;"><input type="hidden" name="PackQty_val<?php echo $itemCount; ?>" id="PackQty_val<?php echo $itemCount; ?>" value="<?php echo $PackQty; ?>"><input type="hidden" name="AccountID_val<?php echo $itemCount; ?>" id="AccountID_val<?php echo $itemCount; ?>" value="<?php echo $AccountID; ?>"><input type="hidden" name="ItemID_val<?php echo $itemCount; ?>" id="ItemID_val<?php echo $itemCount; ?>" value="<?php echo $ItemID;?>"><input type="hidden" name="TransID_val<?php echo $itemCount; ?>" id="TransID_val<?php echo $itemCount; ?>" value="<?php echo $TransID; ?>"><input type="hidden" name="rate_val<?php echo $itemCount; ?>" id="rate_val<?php echo $itemCount; ?>" value="<?php echo $rate; ?>"><input type="hidden" name="gst_val<?php echo $itemCount; ?>" id="gst_val<?php echo $itemCount; ?>" value="<?php echo $gst; ?>"><input type="hidden" name="state_val<?php echo $itemCount; ?>" id="state_val<?php echo $itemCount; ?>" value="<?php echo $state; ?>"><input type="hidden" name="BilledQty" id="BilledQty" value="<?php echo $BilledQty; ?>"><input type="hidden" name="state" id="state" value="<?php echo $value22["state"];?>"><input type="hidden" name="rate" id="rate" value="<?php echo $rate; ?>"><input type="hidden" name="gst" id="gst" value="<?php echo $gst; ?>"><input type="text" name="rtnqty<?php echo $itemCount; ?>" id="rtnqty<?php echo $itemCount; ?>"  onblur="calculate_rtnqty();" onkeypress="return isNumber(event)" style="width: 80px;text-align: right;" value="<?php echo $ORDBilledQty; ?>" ></td>
                                        <?php
                                    }else{
                                        ?>
                                        <td style="width: 80px;padding:1px 5px !important;text-align: right;"></td>
                                    <?php } ?>
                                    
                                    <?php } ?>
                                    </tr>
                                    <?php  $J++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_hidden('fresh_stock_return'); ?>
            <div class="" id="payment_reciept" style="display:none;">
                <div class="row">
                        <div class="col-md-12">
                           
                                <table class="table table-striped table-bordered payment_details_tbl " id="payment_details_tbl" width="70%">
                                    <thead id="thead">
                                        <tr>
                                            <th style="width: 125px;">AccountID</th>
                                            <th>AccoutName</th>
                                            <th>Address</th>
                                            <th style="width: 80px;">ReceiptAmt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <tr class="accounts" id="row">
                                            <td id="AccountIDTD_pay" style="width: 125px;"><input type="text" name="AccountID_pay" style="width: 125px;" id="AccountID_pay" style="width: 125px;"></td>
                                            <td style="padding:1px 5px !important;"><span id="party_name_pay"></span><input type="hidden" name="party_name_pay_val" id="party_name_pay_val"></td>
                                            <td style="padding:1px 5px !important;"><span id="address_pay"></span><input type="hidden" name="address_pay_val" id="address_pay_val" value="" ></td>
                                            <td style="width: 80px;" class="rcptAmts"><input type="text" name="receiptamt" id="receiptamt" onblur="calculate_payment();"  style="width: 80px;text-align: right" onkeypress="return isNumber(event)" value="" ></td>
                                        </tr>
                                        <?php
                                $ii = 1;
                                foreach ($return_payment_list as $key3 => $value3) {
                                        # code...
                                    if($Payment_count != 0){
                                    ?>
                                    <tr class="accounts" id="row_pay<?php echo $ii ?>">
                                        <td style="width: 125px;" id="AccountIDTD_pay"><input type="text" name="AccountID_pay<?php echo $ii ?>" style="width: 125px;" id="AccountID_pay<?php echo $ii ?>" value="<?php echo $value3['Aid'];?>"></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value3['company'];?></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value3['address'];?></td>
                                        <td class="rcptAmts" style="width: 80px;"><input type="text" name="receiptamt<?php echo $ii ?>" id="receiptamt<?php echo $ii ?>"  onblur="calculate_payment();" value="<?php echo $value3['payment_recipt_Amount'];?>" style="width: 80px;text-align: right" onkeypress="return isNumber(event)"></td>
                                        
                                    </tr>
                                <?php
                                $ii++;
                                    }
                                }
                                ?>
                                    </tbody>
                                </table>
                            
                        </div>
                    </div>
            </div>
            <?php echo form_hidden('payment_reciept'); ?>
            <div class="" id="expense_detail" style="display:none;">
                <table class="table table-striped table-bordered expense_details_tbl " id="expense_details_tbl" width="70%">
                        <thead id="thead">
                            <tr>
                                <th style="width: 125px;">AccountID</th>
                                <th>AccoutName</th>
                                <th>Address</th>
                                <th style="width: 80px;">ExpenseAmt</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr class="accounts" id="row">
                                <td id="AccountIDTD_exp" style="width: 125px;"><input type="text" name="AccountID_exp" style="width: 125px;" id="AccountID_exp" style="width: 125px;"></td>
                                <td style="padding:1px 5px !important;"><span id="party_name_exp"></span><input type="hidden" name="party_name_exp_val" id="party_name_exp_val"></td>
                                <td style="padding:1px 5px !important;"><span id="address_exp"></span><input type="hidden" name="address_exp_val" id="address_exp_val" value="" ></td>
                                <td style="width: 80px;" class="expamts"><input type="text" name="expamt" id="expamt" value="" onblur="calculate_expense();" style="width: 80px;text-align: right"  onkeypress="return isNumber(event);"></td>
                            </tr>
                            <?php
                                $i4 = 1;
                                foreach ($return_expense_list as $key4 => $value4) {
                                        # code...
                                    if($Expense_count != 0){
                                    ?>
                                    <tr class="accounts" id="row_exp<?php echo $i4 ?>">
                                        <td style="width: 125px;" id="AccountID_exp<?php echo $i4 ?>"><input type="text" name="AccountID_exp<?php echo $i4 ?>" style="width: 125px;" id="AccountID_exp<?php echo $i4 ?>" value="<?php echo $value4['Aid'];?>"></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value4['firstname']." ".$value4['lastname'];?></td>
                                        <td style="padding:1px 5px !important;"><?php echo $value4['current_address'];?></td>
                                        <td class="expamts" style="width: 80px;"><input type="text" name="expamt<?php echo $i4 ?>" id="expamt<?php echo $i4 ?>" onblur="calculate_expense();"  value="<?php echo $value4['expense_Amount'];?>" style="width: 80px;text-align: right" onkeypress="return isNumber(event)"></td>
                                        
                                    </tr>
                                <?php
                                
                                $i4++;
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
            </div>
           <?php echo form_hidden('expense_detail'); ?>
          
        </div>
        </div>
       
        <div class="row">
          <div class="col-md-12 mtop15">

                <div id="vendor_data">
                  
                </div>

                <div class="btn-bottom-toolbar text-right" style="width: 100%;">
                <?php 
                if (has_permission_new('vehicle_return', '', 'edit')) {  
                    
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $lgstaff = $this->session->userdata('staff_user_id');
                $vehicleRtn_date = substr($return_details['returnTransdate'],0,10);
                
                $vehicleRtn_date_new    = new DateTime($vehicleRtn_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                $sql = 'SELECT * FROM tblvehiclereturn WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblvehiclereturn.ReturnID DESC ';
                $result_data = $this->db->query($sql)->row();
                $lastdate_vehrtn = substr($result_data->Transdate,0,10);
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $this->db->select('*');
                $this->db->where('plant_id', $selected_company);
                $this->db->where('year', $fy);
                $this->db->where('staff_id', $lgstaff);
                $this->db->LIKE('feature', "vehicle_return");
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
                        if ($vehicleRtn_date_new < $tillDate_new) {
                            $return = 'disabled';
                        }else{
                            $return = '';
                        }
                    }
                if($return == "disabled"){
                ?>
                <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
                <?php
                }else{
                ?>
                  <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                  Update
                  </button>
                <?php }} ?>
                </div>
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>
        </div>
        </div>

			</div>
			<?php echo form_close(); ?>
			
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="transfer-modal">
   <div class="modal-dialog modal-xl" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"> Challan List</h4>
         </div>
         <div class="modal-body" style="padding:5px;">
             
            <div class="row">
               <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="from_date">
                        <label for="from_date" class="control-label">From</label>
                        <?php $form_date = '01/'.date('m/Y'); ?>
                        <div class="input-group date">
                            <input type="text" id="from_date1" name="from_date1" class="form-control datepicker" value="<?php echo $form_date;?>" >
                            <div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="to_date">
                        <label for="to_date" class="control-label">To</label>
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
                        <div class="input-group date">
                            <input type="text" id="to_date1" name="to_date1" class="form-control datepicker" value="<?php echo $to_date;?>">
                            <div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data">Search</button>
                </div>
                
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                
                <div class="col-md-12">
                 
            <div class="table_adj_report">
             
              <table class="tree table table-striped table-bordered table_adj_report" id="table_adj_report" width="100%">
                  
                <thead>
                    
                  <tr>
                             <th >Challan No.</th>
                             <th >Challan Date</th>
                             <th >VehRtnId</th>
                             <th style=" text-align:center;">Route</th>
                             <th style=" text-align:center;">VehicleNo</th>
                             <th style=" text-align:center;">DriverName</th>
                             <th style=" text-align:center;">LoaderName</th>
                             <th style=" text-align:center;">SalemsmanName</th>
                             <th style=" text-align:center;">Crates</th>
                             <th style=" text-align:center;">Cases</th>
                             <th style=" text-align:center;">ChallanAmt</th>
                             <th style=" text-align:center;">OtherVehicleDetails</th>
                            
                          </tr>
                </thead>
                <tbody>
                </tbody>
              </table>   
            </div>
            <span id="searchh2" style="display:none;">
                                Loading.....
                            </span>
                    
                </div>
              </div>
              
         </div>
        
         
      </div>
   </div>
</div>
<div class="modal fade" id="transfer-modal_return_list">
   <div class="modal-dialog modal-xl" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vehicle Return List</h4>
         </div>
         <div class="modal-body" style="padding:5px;">
             
            <div class="row">
                
                <div class="col-md-2">
                    <?php
                   echo render_date_input('from_date2','From',$from_date);
                   ?>
                </div>
                <div class="col-md-2">
                    <?php
                   echo render_date_input('to_date2','To',$to_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data_vehicle_return"><?php echo _l('rate_filter'); ?></button>
                </div>
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                <div class="col-md-12">
                 
            <div class="table_vehicle_return">
             
              <table class="tree table table-striped table-bordered table_vehicle_return" id="table_vehicle_return" width="100%">
                  
                <thead>
                    <tr style="display:none;">
                      <td colspan="8" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                        <th style="padding:0px 3px !important;">Return No.</th>
                        <th style="padding:0px 3px !important;">Return Date</th>
                        <th style="padding:0px 3px !important;">Challan No.</th>
                        <th style="padding:0px 3px !important;">Challan Date</th>
                        <th style="padding:0px 3px !important;">VehRtnId</th>
                        <th style=" text-align:center;">Route</th>
                        <th style=" text-align:center;">DriverName</th>
                        <th style=" text-align:center;">LoaderName</th>
                        <th style=" text-align:center;">SalemsmanName</th>
                        <th style=" text-align:center;">Crates</th>
                        <th style=" text-align:center;">Cases</th>
                        <th style=" text-align:center;">ChallanAmt</th>
                        <th style=" text-align:center;">OtherVehicleDetails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
            if(count($vRtnlist) > 0 ){
            
        
         foreach($vRtnlist as $value){
          
            $url = admin_url().'Vehicle_return/vehicle_return_list/'.$value["ReturnID"];
        ?>
        <tr onclick="location.href='<?php echo $url; ?>">
            <td style="padding:0px 3px !important;"><?php echo $value["ReturnID"]; ?></td>
            <td style="padding:0px 3px !important;"><?php echo  _d(substr($value["returnTransdate"],0,10)); ?></td>
            <td style="padding:0px 3px !important;"><?php echo $value["ChallanID"]; ?></td>
            <td style="padding:0px 3px !important;"><?php echo  _d(substr($value["Transdate"],0,10)); ?></td>
            <td></td>
            <td style="padding:0px 3px !important;"><?php echo $value["name"]; ?></td>
            <td style="padding:0px 3px !important;"><?php echo $value["driver_fn"].' '.$value["driver_ln"]; ?></td>
            <td style="padding:0px 3px !important;"><?php echo $value["loader_fn"].' '.$value["loader_ln"]; ?></td> 
            <td style="padding:0px 3px !important;"><?php echo $value["Salesman_fn"].' '.$value["Salesman_ln"]; ?></td>
            <td style="padding:0px 3px !important;text-align:right;"><?php echo $value["Crates"]; ?></td>
            <td style="padding:0px 3px !important;text-align:right;"><?php echo $value["Cases"]; ?></td>
            <td style="padding:0px 3px !important;text-align:right;"><?php echo $value["ChallanAmt"]; ?></td> 
            <td style="padding:0px 3px !important;"><?php echo $value["OtherVehicleDetails"]; ?></td>
        </tr>
    <?php
       } 
        }else{
    ?>
        <tr>
            <td colspan="13"><span style="color:red;">No data found..</span></td>
        </tr>
    <?php
    }
    ?>
                </tbody>
                
              </table>   
            </div>
            <span id="searchh3" style="display:none;">
                                Loading.....
                            </span>
                    
                </div>
              </div>
              
         </div>
        
         
      </div>
   </div>
</div>
<style>
    .table_adj_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_adj_report thead th { position: sticky; top: 0; z-index: 1; }
.table_adj_report tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
 table  { border-collapse: collapse; width: 100%; }
 th, td { padding: 0px 0px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
 th     { background: #50607b;color: #fff !important; }


#table_adj_report tr:hover {
    background-color: #ccc;
}

#table_adj_report td:hover {
    cursor: pointer;
}

.transfer-modal_return_list { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.transfer-modal_return_list thead th { position: sticky; top: 0; z-index: 1; }
.transfer-modal_return_list tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.transfer-modal_return_list table  { border-collapse: collapse; width: 100%; }
.transfer-modal_return_list th, td { padding: 0px 0px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.transfer-modal_return_list th     { background: #50607b;color: #fff !important; }

.fixed_header1 { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.fixed_header1 thead th { position: sticky; top: 0; z-index: 1; }
.fixed_header1 tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.fixed_header1 table  { border-collapse: collapse; width: 100%; }
.fixed_header1 th, td { padding: 0px 0px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.fixed_header1 th     { background: #50607b;color: #fff !important; }


#transfer-modal_return_list tr:hover {
    background-color: #ccc;
}

#transfer-modal_return_list td:hover {
    cursor: pointer;
}
</style>

<?php init_tail(); ?>

</body>
<style>
    table.dataTable tbody td {
    padding: 4px 4px !important;
    font-size: 11px;
}
</style>
</html>

<?php $this->load->view('admin/vehicle_return/vehicleRtn_js'); ?>
<script type="text/javascript" language="javascript" >

function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_vehicle_return");
  tr = table.getElementsByTagName("tr");
for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    td1 = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[2];
    td3 = tr[i].getElementsByTagName("td")[3];
    td4 = tr[i].getElementsByTagName("td")[4];
    td5 = tr[i].getElementsByTagName("td")[5];
    td6 = tr[i].getElementsByTagName("td")[6];
    td7 = tr[i].getElementsByTagName("td")[7];
    td8 = tr[i].getElementsByTagName("td")[8];
    td9 = tr[i].getElementsByTagName("td")[9];
    td10 = tr[i].getElementsByTagName("td")[10];
    td11 = tr[i].getElementsByTagName("td")[11];
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
        
      }else if(td6){
         txtValue = td6.textContent || td6.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td7){
         txtValue = td7.textContent || td7.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td8){
         txtValue = td8.textContent || td8.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td9){
         txtValue = td9.textContent || td9.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td10){
         txtValue = td10.textContent || td10.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td11){
         txtValue = td10.textContent || td11.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else{
           tr[i].style.display = "none";
      } 
    }
    }}
    }
    }     
  }
}
}
}
}
}}
}
}
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
   
    
    $('#VrtnDate').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    
    $('#from_date2').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    $('#to_date2').datetimepicker({
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