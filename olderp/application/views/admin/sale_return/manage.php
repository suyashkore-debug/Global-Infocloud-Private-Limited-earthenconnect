<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row" style="display:none;">
		<div class="col-md-12">
		    <table id="print_table">
			     <thead>
			         <tr>
			             <th align="center" colspan="17"><?php echo $company_detail->company_name; ?></th>
			         </tr>
			         <tr>
			             <th align="center" colspan="17"><?php echo $company_detail->address; ?></th>
			         </tr>
			     </thead>
			 <tbody>
			         <tr>
			             <td colspan="17"><b>SaleRtn Details</b></td>
			         </tr>
			         <tr>
			             <td colspan="3"><?php echo $sale_return->SalesRtnID ; ?></td>
			             <td colspan="3"><b>Date : </b><?php echo _d(substr($sale_return->Transdate,0,10)) ; ?></td>
			             <td colspan="3"><b>Type : </b><?php echo $sale_return->SalesRtnTypeID; ?></td>
			             <td colspan="4"></td>
			             <td colspan="4"></td>
			         </tr>
			         <tr>
			             <td colspan="3"><b>AccountID : </b><?php echo $sale_return->accounts->AccountID; ?></td>
			             <td colspan="3"><b>Name : </b> <?php echo $sale_return->accounts->company; ?></td>
			             <td colspan="3"><b>Station : </b><?php echo $sale_return->accounts->StationName; ?></td>
			             <td colspan="4"><b>Mobile : </b><?php echo $sale_return->accounts->phonenumber; ?></td>
			             <td colspan="4"></td>
			         </tr>
			         <tr>
			             <td colspan="3"><b>GSTIN : </b><?php echo $sale_return->accounts->vat; ?></td>
			             <td colspan="3"><b>State : </b> <?php echo $sale_return->accounts->state; ?></td>
			             <td colspan="11"><b>Address : </b><?php echo $sale_return->accounts->address; ?></td>
			             <!--<td colspan="4"><b>Mobile : </b><?php echo $sale_return->accounts->phonenumber; ?></td>-->
			         </tr>
			                 
			         <tr class="print_item_h">
			             <td align="center">ItemID</td>
			             <td align="center">Item Name</td>
			             <td align="center">Pack</td>
			             <td align="center">SaleID</td>
			             <td align="center">FY</td>
			             <td align="center">BilledCS</td>
			             <td align="center">ReturnQty.</td>
			             <td align="center">BasicRate</td>
			             <td align="center">Disc%</td>
			             <td align="center">DiscAmt</td>
			             <td align="center">CGST%</td>
			             <td align="center">CGSTAmt</td>
			             <td align="center">SGST%</td>
			             <td align="center">SGSTAmt</td>
			             <td align="center">IGST%</td>
			             <td align="center">IGSTAmt</td>
			             <td align="center">Amount</td>
			         </tr>
			     <?php
                    if(isset($sale_return)){
                        foreach ($sale_return->items as $key => $value) {
                    ?>
                        <tr>
                            <td><?php echo $value["ItemID"]; ?></td>
                            <td ><?php echo $value["description"]; ?></td>
                            <td><?php echo $value["CaseQty"]; ?></td>
                            <td ><?php echo $value["BillID"]; ?></td>
                            <td ><?php echo $value["FY"]; ?></td>
                            <?php 
                            $billedCS = get_billedCS($value["ItemID"],$value["TransID"],$value["FY"],$value["PlantID"]);
                            $billedCS_new = $billedCS->BilledQty / $billedCS->CaseQty;
                            ?>
                            <td align='right'><?php echo $billedCS_new; ?></td>
                            <td align='right'><?php echo $value["BilledQty"]; ?></td>
                            <td align='right'><?php echo $value["BasicRate"]; ?></td>
                            <td align='right'><?php echo $value["DiscPerc"]; ?></td>
                            <td align='right'><?php echo $value["DiscAmt"]; ?></td>
                            <td align='right'><?php echo $value["cgst"]; ?></td>
                            <td align='right'><?php echo $value["cgstamt"]; ?></td>
                            <td align='right'><?php echo $value["sgst"]; ?></td>
                            <td align='right'><?php echo $value["sgstamt"]; ?></td>
                            <td align='right'><?php echo $value["igst"]; ?></td>
                            <td align='right'><?php echo $value["igstamt"]; ?></td>
                            <td align='right'><?php echo $value["NetChallanAmt"]; ?></td>
                        </tr>    
                <?php
                        }
                    }
                ?>
                <tr>
                            <td colspan="15" align="right">Gross Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->SaleAmt : '0.00'); ?>
                            <td colspan="2" align="right"><?php echo $value; ?></td>
                        </tr>
                        <tr>
                            <td colspan="15" align="right">CGST Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->cgstamt : '0.00'); ?>
                            <td colspan="2" align="right"><?php echo $value; ?></td>
                        </tr>
                        <tr>
                            <td colspan="15" align="right">SGST Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->sgstamt : '0.00'); ?>
                            <td colspan="2" align="right"><?php echo $value; ?></td>
                        </tr>
                        <tr>
                            <td colspan="15" align="right">IGST Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->igstamt : '0.00'); ?>
                            <td colspan="2" align="right"><?php echo $value; ?></td>
                        </tr>
                        <tr>
                            <td colspan="15" align="right">Net Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->BillAmt : '0.00'); ?>
                            <td colspan="2" align="right"><?php echo $value; ?></td>
                        </tr>
			</tbody>
		</table>
		</div>
	</div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
        
            
     <?php hooks()->do_action('before_items_page_content'); ?>
     
      <div class="clearfix"></div>
      <?php if(isset($sale_return)){
        ?>
      <?php echo form_open('admin/sale_return/edit',array('id'=>'salereturn_form')); ?>
      <?php }else{ ?>
      <?php echo form_open('admin/sale_return/add',array('id'=>'salereturn_form')); ?>
      <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <?php
                                    $data_attr = array();
                                    if(isset($sale_return)){
                                        $data_attr = array(
                                            "disabled" =>true
                                            );
                                    }
                                ?>
                                <div class="col-md-3" style="padding-right: 0px;">
                                <?php
                                        $selected_company = $this->session->userdata('root_company');
                                    if($selected_company == 1){
                                        $new_sale_returnNumber = get_option('next_sale_return_number_for_cspl');
                                    }elseif($selected_company == 2){
                                        $new_sale_returnNumber = get_option('next_sale_return_number_for_cff');
                                    }elseif($selected_company == 3){
                                        $new_sale_returnNumber = get_option('next_sale_return_number_for_cbu');
                                    }
                                    $format = get_option('invoice_number_format');
                                    $prefix = "SRT";
                                
                                    if ($format == 1) {
                                        $__number = $new_sale_returnNumber;
                                        $prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>';
                                    } else if($format == 2) {
                                        $__number = $new_sale_returnNumber;
                                        $prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>/';
                                    } else if($format == 3) {
                                        $__number = $new_sale_returnNumber;
                                    } else if($format == 4) {
                                        $yyyy = date('Y');
                                        $mm = date('m');
                                        $__number = $new_sale_returnNumber;
                                    }
                                    $_sale_return_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                               
                                ?>
                                    <div class="form-group credit_div">
                                        <label for="number">Return ID</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><?php echo $prefix; ?></span>
                                            <input type="hidden" name="ex_sale_return_id" id="ex_sale_return_id" value="<?php if(isset($sale_return)){ echo $sale_return->SalesRtnID;}?>">
                                            <input type="text" name="sale_return_id" id="sale_return_id" class="form-control sale_return_id" value="<?php if(isset($sale_return)){ echo substr($sale_return->SalesRtnID,5);}else{ echo $_sale_return_number; } ?>" <?php if(isset($sale_return)){ echo "disabled";} ?>>
                                            <?php if(isset($sale_return)){ ?>
                                                <input type="hidden" name="updated_record" value=" " id="updated_record">
                                                <input type="hidden" name="new_record" value=" " id="new_record">
                                            <?php } ?>
                                                <input type="hidden" name="SaleID" value="" id="SaleID">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3" style="padding-right: 0px;">
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
                                    
                                <?php $value = (isset($sale_return) ? _d(substr($sale_return->Transdate,0,10)) : $to_date); ?>
                                <?php echo render_date_input('sale_return_date','Date',$value); ?>
                                    <?php
                                    if(isset($sale_return)){ ?>
                                        <input type="hidden" name="sale_return_date_old" value="<?php echo _d(substr($sale_return->Transdate,0,10));?>">
                                    <?php } ?>
                                </div>
                            </div>
                                <!-- Second row-->
                            <div class="row">
                                <div class="col-md-3" style="padding-right: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->AccountID : ''); ?>
                                    <?php
                                        if(isset($sale_return)){ ?>
                                            <input type="hidden" name="old_act_name" id="old_act_name" value="<?php echo $value; ?>">
                                    <?php } ?>
                                    <div class="form-group">
                                        <label for="act_name">Return From</label>
                                        <input type="text" name="act_name" id="act_name" class="form-control" value="<?php echo $value; ?>" >
                                    </div>
                                </div>
                                    
                                <div class="col-md-4" style="margin-top: 19px;padding: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->accounts->company : ''); ?>
                                    <input type="text" name="account_full_name" id="account_full_name" class="form-control" value="<?php echo $value; ?>">
                                </div>
                                    
                                <div class="col-md-3" style="margin-top: 19px;padding: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->accounts->vat : ''); ?>
                                    <input type="text" name="gst_no" id="gst_no" class="form-control" value="<?php echo $value; ?>">
                                </div>
                                    
                                <div class="col-md-2" style="margin-top: 19px;padding: 0px;">
                                    <input type="text" name="account_bal" id="account_bal" class="form-control">
                                </div>
                            </div>
                            <!-- Third Row-->
                            <div class="row">
                                <div class="col-md-3" style="padding-right: 0px;">
                                    <div class="form-group">
                                        <label class="control-label">Return Type</label>
                                        <select name="return_type" id="return_type" class="form-control" disabled>
                                            
                                            <option value="1">Tax Invoice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-top: 19px;padding: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->accounts->address : ''); ?>
                                    <input type="text" name="account_address" id="account_address" class="form-control" value="<?php echo $value; ?>">
                                    
                                </div>
                                
                                <div class="col-md-1" style="margin-top: 19px;padding: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->accounts->state : ''); ?>
                                    <input type="text" name="account_state" id="account_state" class="form-control" value="<?php echo $value; ?>">
                                    
                                </div>
                                <div class="col-md-2" style="margin-top: 19px;padding: 0px;">
                                    <input type="text" name="account_route" id="account_route" class="form-control">
                                </div>
                                
                                <div class="col-md-2" style="margin-top: 19px;padding: 0px;">
                                    <input type="text" name="account_type" id="account_type" class="form-control">
                                </div>
                                
                            </div> 
                            
                            <!--End Third row-->
                            <!--Start Fourth Row-->
                            <div class="row">
                                <div class="col-md-3" style="padding-right: 0px;">
                                    <div class="form-group">
                                        <label class="control-label">Pytm Type</label>
                                        <select name="pytm_type" id="pytm_type" class="form-control" disabled>
                                            
                                            <option value="1">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4" style="margin-top: 19px;padding: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->accounts->Address3 : ''); ?>
                                    <input type="text" name="account_address2" id="account_address2" class="form-control" value="<?php echo $value; ?>">
                                </div>
                                <div class="col-md-2" style="margin-top: 19px;padding: 0px;">
                                    <?php $value = (isset($sale_return) ? $sale_return->accounts->StationName : ''); ?>
                                    <input type="text" name="account_station" id="account_station" class="form-control" value="<?php echo $value; ?>">
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Second coloumn-->
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 2%;">
                                    <input type="radio" id="fresh" name="type_select" value="fresh" <?php if(isset($sale_return) && $sale_return->SalesRtnTypeID  == "Fresh"){ echo "checked"; } if(!isset($sale_return)){ echo "checked"; }?>>
                                    <label for="fresh">Add return to stock</label><br>
                                    <input type="radio" id="damage" name="type_select" value="damage" <?php if(isset($sale_return) && $sale_return->SalesRtnTypeID  == "Damage"){ echo "checked";}?>>
                                    <label for="damage">Declare return as damage</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 8%;height: 97px;">
                                    <textarea class="form-control" name="narration" id="narration"></textarea>
                                    <?php $value = (isset($sale_return) ? count($sale_return->items) : ''); ?>
                                    <input type="hidden" value="<?php echo $value + 1;?>" name="countof_record" id="countof_record">
                                    <?php $value = (isset($sale_return) ? $sale_return->purchased_item : ''); ?>
                                    <input type="hidden"  name="sale_item" id="sale_item" value="<?php echo $value; ?>">
                                    
                                    <?php $value = (isset($sale_return) ? $sale_return->items[0]["TransID"] : ''); ?>
                                    <input type="hidden" value="<?php echo $value; ?>" name="tax_id" id="tax_id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="data_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:10%">ItemID</th>
                                <th style="width:25%">ItemName</th>
                                <th style="width:5%">Pack</th>
                                <th style="width:15%">SaleID</th>
                                <th style="width:5%">FY</th>
                                <th style="width:5%">BilledCS</th>
                                <th style="width:5%">ReturnQty.</th>
                                <th style="width:5%">BasicRate</th>
                                <th style="width:5%">Disc%</th>
                                <th style="width:5%">DiscAmt</th>
                                <th style="width:5%">CGST%</th>
                                <th style="width:5%">CGSTAmt</th>
                                <th style="width:5%">SGST%</th>
                                <th style="width:5%">SGSTAmt</th>
                                <th style="width:5%">IGST%</th>
                                <th style="width:5%">IGSTAmt</th>
                                <th style="width:5%">Amount</th>
                                <th style="width:5%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                <?php
                    $i=1;
                    foreach ($sale_return->items as $key => $value) {
                ?>
                <?php 
                    $billedCS = get_billedCS($value["ItemID"],$value["TransID"],$value["FY"],$value["PlantID"]);
                    $billedCS_new = $billedCS->BilledQty / $billedCS->CaseQty;
                ?>
                        <tr id='row<?php echo $i;?>'>
                        <td id='item_code<?php echo $i;?>'><?php echo $value["ItemID"]; ?><input type='hidden' name='item_code<?php echo $i;?>' value='<?php echo $value["ItemID"]; ?>'></td>
                        <td id='item_name<?php echo $i;?>'><?php echo $value["description"]; ?><input type='hidden' name='item_name_val<?php echo $i;?>' value='<?php echo $value["description"]; ?>'></td>
                        <td id='pack<?php echo $i;?>'><?php echo $value["CaseQty"]; ?><input type='hidden' name='pack_val<?php echo $i;?>' value='<?php echo $value["CaseQty"]; ?>'></td>
                        <td id='sale_id<?php echo $i;?>'><?php echo $value["BillID"]; ?><input type='hidden' name='sale_id<?php echo $i;?>' value='<?php echo $value["BillID"]; ?>'><input type="hidden" name="order_qty<?php echo $i;?>" id="order_qty<?php echo $i;?>" value="<?php echo $billedCS->BilledQty;?>"></td>
                        <td id='fy<?php echo $i;?>'><?php echo $value["FY"]; ?><input type='hidden' name='fy_val<?php echo $i;?>' value='<?php echo $value["FY"]; ?>'></td>
                        
                        <td id='billed_cs<?php echo $i;?>' align='right'><?php echo $billedCS_new; ?><input type='hidden' name='billed_cs_val<?php echo $i;?>' value='<?php echo $billedCS_new; ?>'></td>
                        <td id='return_qty<?php echo $i;?>' align='right'><?php echo $value["BilledQty"]; ?><input type='hidden' name='return_qty<?php echo $i;?>' value='<?php echo $value["BilledQty"]; ?>' style="height: 30px;width: 100%;" onkeypress="return isNumber(event)"></td>
                        <td id='basic_rate<?php echo $i;?>'><?php echo $value["BasicRate"]; ?><input type='hidden' name='basic_rate_val<?php echo $i;?>' value='<?php echo $value["BasicRate"]; ?>'><input type='hidden' name='sale_rate_val<?php echo $i;?>' value='<?php echo $value["SaleRate"]; ?>'></td>
                        <td id='disc<?php echo $i;?>' align='right'><?php echo $value["DiscPerc"]; ?><input type='hidden' name='disc_per_val<?php echo $i;?>' value='<?php echo $value["DiscPerc"]; ?>'></td>
                        <td id='discamt<?php echo $i;?>' align='right'><?php echo $value["DiscAmt"]; ?><input type='hidden' name='disc_amt_val<?php echo $i;?>' value='<?php echo $value["DiscAmt"]; ?>'></td>
                         
                        <td id='cgst<?php echo $i;?>' align='right'><?php echo $value["cgst"]; ?><input type='hidden' name='cgst_per_val<?php echo $i;?>' value='<?php echo $value["cgst"]; ?>'></td>
                        <td id='cgst_amt<?php echo $i;?>' align='right'><?php echo $value["cgstamt"]; ?><input type='hidden' name='cgst_amt_val<?php echo $i;?>' value='<?php echo $value["cgstamt"]; ?>'></td>
                        <td id='sgst<?php echo $i;?>' align='right'><?php echo $value["sgst"]; ?><input type='hidden' name='sgst_per_val<?php echo $i;?>' value='<?php echo $value["sgst"]; ?>'></td>
                        <td id='sgst_amt<?php echo $i;?>' align='right'><?php echo $value["sgstamt"]; ?><input type='hidden' name='sgst_amt_val<?php echo $i;?>' value='<?php echo $value["sgstamt"]; ?>'></td>
                        <td id='igst<?php echo $i;?>' align='right'><?php echo $value["igst"]; ?><input type='hidden' name='igst_per_val<?php echo $i;?>' value='<?php echo $value["igst"]; ?>'></td>
                        <td id='igst_amt<?php echo $i;?>' align='right'><?php echo $value["igstamt"]; ?><input type='hidden' name='igst_amt_val<?php echo $i;?>' value='<?php echo $value["igstamt"]; ?>'></td>
                        <td id='total_amt<?php echo $i;?>' align='right'><?php echo $value["NetChallanAmt"]; ?><input type='hidden' name='total_amt_val<?php echo $i;?>' value='<?php echo $value["NetChallanAmt"]; ?>'></td>
                        <td><!--<input type='button' value='Delete' class='remove' >--><button type="button" name="edit" id="edit_row" class="btn btn-xs btn-succes edit_row" value="edit"><i class="fa fa-pencil " style="font-size:16px;"></i></button><!--<input type='button' value='edit' class='edit_row' >--><input type='hidden' name='rownum' id='rownum' value="<?php echo $i;?>"></td>
                         
                        </tr>
                <?php
                    $i++;
                    }
                ?>
                    <tr id="R1">
                        <td style="width:10%"><input type="text" name="act_code" id="item_code" style="width: 100%;border-radius: 2px;height: 30px;"></td>
                        <td style="width:25%" ><span id="item_name"></span><input type="hidden" name="item_name_val" id="item_name_val" value=""><input type="hidden" name="hsndesc_val" id="hsndesc_val" value=""></td>
                        <td class="trans1" style="width:5%" ><span id="pack"></span><input type="hidden" name="pack_val" id="pack_val" value=""></td>
                        <td style="width:15%" ><input type="text" name="sale_id" id="sale_id" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="order_qty" id="order_qty" value=""></td>
                        <td style="width:5%"><span id="fy"></span><input type="hidden" name="fy_val" id="fy_val" value=""></td>
                        <td style="width:5%"><span id="billed_cs"></span><input type="hidden" name="billed_cs_val" id="billed_cs_val" value=""></td>
                        <td style="width:5%"><input type="text" name="return_qty" id="return_qty" value="" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)" /></td>
                        <td style="width:5%"><span id="basic_rate"></span><input type="hidden" name="basic_rate_val" id="basic_rate_val" value=""><input type="hidden" name="sale_rate_val" id="sale_rate_val" value=""></td>
                        <td style="width:5%" align="right"><span id="disc"></span><input type="hidden" name="disc_per_val" id="disc_per_val" value=""></td>
                        <td style="width:5%" align="right"><span id="discamt"></span><input type="hidden" name="disc_amt_val" id="disc_amt_val" value=""></td>
                        <td style="width:5%" align="right"><span id="cgst"></span><input type="hidden" name="cgst_per_val" id="cgst_per_val" value=""></td>
                        <td style="width:5%" align="right"><span id="cgstamt"></span><input type="hidden" name="cgst_amt_val" id="cgst_amt_val" value=""></td>
                        <td style="width:5" align="right"><span id="sgst"></span> <input type="hidden" name="sgst_per_val" id="sgst_per_val" value=""></td>
                        <td style="width:5%" align="right"><span id="sgstamt"></span><input type="hidden" name="sgst_amt_val" id="sgst_amt_val" value=""></td>
                        <td style="width:5%" align="right"><span id="igst"></span><input type="hidden" name="igst_per_val" id="igst_per_val" value=""></td>
                        <td style="width:5%" align="right"><span id="igstamt"></span><input type="hidden" name="igst_amt_val" id="igst_amt_val" value=""></td>
                        <td style="width:5%" align="right"><span id="amount"></span><input type="hidden" name="total_amt_val" id="total_amt_val" value=""></td>
                        <td style="width:5%"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
                
                <div class="col-md-8">
                    <div class="row" style="margin-top:5%">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2">
                    <?php 
                        if(isset($sale_return)){
                    ?>
                    <?php if(has_permission_new('sale_return','','edit')){ 
                            $selected_company = $this->session->userdata('root_company');
                            $fy = $this->session->userdata('finacial_year');
                            $fy_new  = $fy + 1;
                            $first_date = '20'.$fy.'-04-01';
                            $lastdate_date = '20'.$fy_new.'-03-31';
                            $curr_date = date('Y-m-d');
                            $lgstaff = $this->session->userdata('staff_user_id');
                            $SRtn_date = substr($sale_return->Transdate,0,10);
                            
                            $SRtn_date_new    = new DateTime($SRtn_date);
                            $first_date_yr = new DateTime($first_date);
                            $last_date_yr = new DateTime($lastdate_date);
                            $curr_date_new = new DateTime($curr_date);
                            
                            if($curr_date_new > $last_date_yr){
                                $lastdate = $lastdate_date;
                            }else{
                                $lastdate = date('Y-m-d');
                            }
                            
                            $this->db->select('*');
                            $this->db->where('plant_id', $selected_company);
                            $this->db->where('year', $fy);
                            $this->db->where('staff_id', $lgstaff);
                            $this->db->LIKE('feature', "sale_return");
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
                                        if ($SRtn_date_new < $tillDate_new) {
                                            $return = 'disabled';
                                        }else{
                                            $return = '';
                                        }
                                    }
                        ?>
                        <?php if($return == "disabled"){ ?>
                            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
                        <?php }else{ ?>
                            <button type="submit"  class="btn btn-info" id="submit_form" onclick="this.form.submit();this.disabled = true;">Update</button>
                        <?php } ?>
                           
                    <?php } ?>
                <?php }else{ ?>
                        <?php if(has_permission_new('sale_return','','create')){ ?>
                            <button type="submit" class="btn btn-info" id="submit_form" onclick="this.form.submit();this.disabled = true;"><?php echo _l('submit'); ?></button>
                        <?php } ?>
                <?php } ?>
            </div>
               
                <?php 
                    if(isset($sale_return)){
                            ?>
                        <div class="col-md-2">
                            <?php if(has_permission_new('sale_return','','delete')){ ?>
                            <a href="<?php echo admin_url('Sale_return/delete_sale_entry/' . $sale_return->SalesRtnID) ?>" class="btn btn-danger <?php echo $return;?>" onclick="if (confirm('Do you want to delete this sale return?')){return true;}else{event.stopPropagation(); event.preventDefault();};">Delete</a>
                            <?php } ?>
                        </div>
                        <div class="col-md-2">
                        <?php if(has_permission_new('sale_return','','edit')){ ?>
                        <a href="<?php echo admin_url('Sale_return/cancel_sale_entry/' . $sale_return->SalesRtnID) ?>" class="btn btn-default <?php echo $return;?>" onclick="if (confirm('Do you want to cancel this sale return?')){return true;}else{event.stopPropagation(); event.preventDefault();};">Cancel</a>
                        <?php } ?>
                        </div>
                    <?php } ?>
                        
                    <div class="col-md-2">
                    <?php if(has_permission_new('sale_return','','view')){ ?>
                                <a href="#" class="btn btn-warning add-new-transfer mbot15">show list</a>
                    <?php } ?>
                    </div>
                    <div class="col-md-2" >
                            <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
                    </div>
                       
                </div>
                        
            </div>
            <div class="col-md-4">
                    <table class="table text-right">
                        <tr>
                            <td>Gross Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->SaleAmt : '0.00'); ?>
                            <td width="30%"><span id="gross_total"><?php echo $value; ?></span><input type="hidden" name="gross_total_val" id="gross_total_val" value="<?php echo $value; ?>"></td>
                        </tr>
                        <tr>
                            <td>CGST Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->cgstamt : '0.00'); ?>
                            <td  width="30%"><span id="cgst_total"><?php echo $value; ?></span><input type="hidden" name="cgst_total_val" id="cgst_total_val" value="<?php echo $value; ?>"></td>
                        </tr>
                        <tr>
                            <td>SGST Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->sgstamt : '0.00'); ?>
                            <td  width="30%"><span id="sgst_total"><?php echo $value; ?></span><input type="hidden" name="sgst_total_val" id="sgst_total_val" value="<?php echo $value; ?>"></td>
                        </tr>
                        <tr>
                            <td>IGST Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->igstamt : '0.00'); ?>
                            <td  width="30%"><span id="igst_total"><?php echo $value; ?></span><input type="hidden" name="igst_total_val" id="igst_total_val" value="<?php echo $value; ?>"></td>
                        </tr>
                        <tr>
                            <td>Net Amt</td>
                            <?php $value = (isset($sale_return) ? $sale_return->BillAmt : '0.00'); ?>
                            <td  width="30%"><span id="net_total"><?php echo $value; ?></span><input type="hidden" name="net_total_val" id="net_total_val" value="<?php echo $value; ?>"></td>
                        </tr>
                    </table>
                </div>
                
            </div>
      <?php echo form_close(); ?>
    
          <div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Bill List</h4>
                    </div>
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                            <div class="Bill_data">
                                <table class="table table-striped table-bordered Bill_data" id="Bill_data" width="100%">
                                    <thead>
                                        <th>FY</th>
                                        <th>SalesID</th>
                                        <th>TransDate</th>
                                        <th>SaleRate</th>
                                        <th>BasicRate</th>
                                        <th>Disc%</th>
                                        <th>DiscAmt</th>
                                        <th>IGST%</th>
                                        <th>CGST%</th>
                                        <th>SGST%</th>
                                        <th>Cases</th>
                                        <th>BilledQty</th>
                                        <th>RtnQty</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="padding:0px;">
                        <input type="text" id="myInput3"  autofocus="1" name='myInput3' onkeyup="myFunction3()" placeholder="Search.."  style="float: left;width: 100%;">
                    </div>
                </div>
            </div>
          </div>  
  </div>
</div>
</div>
</div>
</div>
</div>


<div class="modal fade" id="transfer-modal" data-backdrop="static">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Sale Return List</h4>
         </div>
         
         
         <div class="modal-body" style="padding:5px;">
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
                <div class="col-md-3">
                    <?php
                   echo render_date_input('from_date','From',$from_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('to_date','To',$to_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
                </div>
                <div class="col-md-3">
                </div>
                <div class="col-md-12">
                    <div class="table_salertn_report">
                    <table class="tree table table-striped table-bordered table_salertn_report" id="table_salertn_report" width="100%">
                        <thead>
                            <tr>
                             <th style="width:8%;">SalesRtnID</th>
                             <th style="width:15%;text-align:left;">ReturnDate</th>
                             <th style="width:5%;text-align:center;">AccountID</th>
                             <th style="width:20%;text-align:left;">Account Name</th>
                             <th style="width:20%;text-align:left;">Address</th>
                             <th style="width:3%;text-align:center;">SaleAmt</th>
                             <th style="width:3%;text-align:right;">DiscAmt</th>
                             <th style="width:3%;text-align:right;">CGSTAmt</th>
                             <th style="width:3%;text-align:right;">SGSTAmt</th>
                             <th style="width:3%;text-align:right;">IGSTAmt</th>
                             <th style="width:3%;text-align:right;">BillAmt</th>
                             <th style="width:3%;text-align:right;">Items</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        foreach ($sale_returns as $key => $value) {
                            $url = admin_url().'sale_return/edit/'.$value['SalesRtnID'];
                    ?>
                        <tr onclick="location.href='<?php echo $url;?>'">
                            <td style="text-align:center;"><?php echo $value['SalesRtnID']; ?></td>
                            <td  style="text-align:center;"><?php echo _d(substr($value['Transdate'],0,10));?></td>
                            <td  style="text-align:center;"><?php echo $value['AccountID']; ?></td>
                            <?php
                                $len = strlen($value['company']);
                                if($len >20){
                                    $dots = '...';
                                }else{
                                    $dots = '';
                                }
                                
                                $len1 = strlen($value['address']);
                                if($len1 >20){
                                    $dots1 = '...';
                                }else{
                                    $dots1 = '';
                                }
                            ?>
                            <td  style="text-align:left;"><?php echo substr($value['company'],0,20).$dots; ?></td>
                            <td  style="text-align:left;"><?php echo substr($value['address'],0,20).$dots1; ?></td>
                            <td  style="text-align:right;"><?php echo $value['SaleAmt']; ?></td>
                            <td style="text-align:right;"><?php echo $value['DiscAmt']; ?></td>
                            <td style="text-align:right;"><?php echo $value['cgstamt']; ?></td>
                            <td style="text-align:right;"><?php echo $value['sgstamt']; ?></td>
                            <td style="text-align:right;"><?php echo $value['igstamt']; ?></td>
                            <td style="text-align:right;"><?php echo $value['RndAmt']; ?></td>
                            <td style="text-align:center;"><?php echo $value['ItCount']; ?></td>
                        </tr>
                <?php } ?>
                    </tbody>
                </table>   
            </div>
            <span id="searchh2" style="display:none;"> Loading..... </span>
            </div>
        </div> 
        </div>
         <div class="modal-footer" style="padding:0px;">
            <input type="text" id="myInput1"  autofocus="1" name='myInput1' onkeyup="myFunction2()" placeholder="Search for names.."  style="float: left;width: 100%;">
        </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>

<style>
#act_name {
    text-transform: uppercase;
}
#act_code {
    text-transform: uppercase;
}
    .table_salertn_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_salertn_report thead th { position: sticky; top: 0; z-index: 1; }
.table_salertn_report tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.table_salertn_report table  { border-collapse: collapse; width: 100%; }
.table_salertn_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.table_salertn_report th     { background: #50607b;color: #fff !important; }

 table  { border-collapse: collapse; width: 100%; }
 th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
 th     { background: #50607b;color: #fff !important; }


#table_salertn_report tr:hover {
    background-color: #ccc;
}

#table_salertn_report td:hover {
    cursor: pointer;
}

.Bill_data { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.Bill_data thead th { position: sticky; top: 0; z-index: 1; }
.Bill_data tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.Bill_data table  { border-collapse: collapse; width: 100%; }
.Bill_data th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.Bill_data th     { background: #50607b;color: #fff !important; }
#Bill_data tr:hover {
    background-color: #ccc;
}

#Bill_data td:hover {
    cursor: pointer;
}
</style>
<script type="text/javascript">
 function printPage(){
      
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .print_item_h{ background: #505f7b;colr:#fff;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
    var print_data = stylesheet+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
    };
 </script>
<script>
    $(function(){
        'use strict';
        appValidateForm($('#salereturn_form'), {
           
            act_name: 'required',
            countof_record: {
				required: {
					depends: function(element) {
						return ($('input[name="countof_record"]').val() > 1) ? true : false
					}
				}
			},
			sale_return_date: {
				remote: {
					url: site_url + "admin/misc/last_sale_return_date",
					type: 'post',
					data: {
						SaleRtnDate: function() {
							return $('input[name="sale_return_date"]').val();
						},
						oldSaleRtnDate: function() {
							return $('input[name="sale_return_date_old"]').val();
						},
						ex_sale_return_id: function() {
							return $('input[name="ex_sale_return_id"]').val();;
						}
					}
				}
			},
        });
    });
</script>
</script>
<script>
    $('.add-new-transfer').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
        $('#transfer-modal').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
      //init_journal_entry_table();
    });
</script>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
  function load_data(from_date,to_date)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>Sale_return/load_data_for_salertn",
      dataType:"JSON",
      method:"POST",
      data:{from_date:from_date, to_date:to_date},
      beforeSend: function () {
               
        $('#searchh2').css('display','block');
        $('.table_salertn_report tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table_salertn_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
        var html = '';
      
        for(var count = 0; count < data.length; count++)
        {
           
          var url = "'<?php echo admin_url() ?>sale_return/edit/"+data[count].SalesRtnID+"'";
        html += '<tr onclick="location.href='+url+'">';
        html += '<td style="text-align:center;">'+data[count].SalesRtnID+'</td>';
          
        var date = data[count].Transdate.substring(0, 10);
        var date_new = date.split("-").reverse().join("/");
          
          html += '<td  style="text-align:center;">'+date_new+'</td>';
          html += '<td  style="text-align:center;">'+data[count].AccountID+'</td>';
          let length = data[count].AccountName.length;
          if(length >20){
                var dots = '...';
            }else{
                var dots = '';
            }
          var AccoutName = data[count].AccountName;
          let length1 = data[count].AccountAddr.length;
          if(length1 >20){
                var dots1 = '...';
            }else{
                var dots1 = '';
            }
         
          html += '<td  style="text-align:left;">'+ AccoutName.substring(0, 20)+ dots +'</td>';
          html += '<td  style="text-align:left;">'+data[count].AccountAddr.substring(0, 20)+ dots1 +'</td>';
          html += '<td  style="text-align:right;">'+data[count].SaleAmt+'</td>';
          html += '<td style="text-align:right;">'+data[count].DiscAmt+'</td>';
          html += '<td style="text-align:right;">'+data[count].cgstamt+'</td>';
          html += '<td style="text-align:right;">'+data[count].sgstamt+'</td>';
          html += '<td style="text-align:right;">'+data[count].igstamt+'</td>';
          html += '<td style="text-align:right;">'+data[count].RndAmt+'</td>';
          html += '<td style="text-align:center;">'+data[count].ItCount+'</td>';
          html += '</tr>';
        }
         $('.table_salertn_report tbody').html(html);
      
      }
    });
  }
  
 $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
        load_data(from_date,to_date);
        
 });

});
</script>

<script>
    function myFunction2() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput1");
      filter = input.value.toUpperCase();
      table = document.getElementById("table_salertn_report");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
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
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td1) {
          txtValue = td1.textContent || td1.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td2) {
          txtValue = td2.textContent || td2.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td3) {
          txtValue = td3.textContent || td3.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td4) {
          txtValue = td4.textContent || td4.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td5) {
          txtValue = td5.textContent || td5.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td6) {
          txtValue = td6.textContent || td6.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td7) {
          txtValue = td7.textContent || td7.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td8) {
          txtValue = td8.textContent || td8.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td9) {
          txtValue = td9.textContent || td9.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td10) {
          txtValue = td10.textContent || td10.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }}}}}}}}}}}
        }       
      }
    }
    
    function myFunction3() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput3");
      filter = input.value.toUpperCase();
      table = document.getElementById("Bill_data");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
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
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td1) {
          txtValue = td1.textContent || td1.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td2) {
          txtValue = td2.textContent || td2.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td3) {
          txtValue = td3.textContent || td3.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td4) {
          txtValue = td4.textContent || td4.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td5) {
          txtValue = td5.textContent || td5.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td6) {
          txtValue = td6.textContent || td6.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td7) {
          txtValue = td7.textContent || td7.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td8) {
          txtValue = td8.textContent || td8.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td9) {
          txtValue = td9.textContent || td9.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if (td10) {
          txtValue = td10.textContent || td10.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }}}}}}}}}}}
        }       
      }
    }
</script>
<script type='text/javascript'>

$(document).ready(function () {
    <?php if(isset($sale_return)){    
    }else{?>
    $("#act_name").focus();
    <?php } ?>
    var rowIdx = 1;
  
    $('#tbody').on('click', '.remove', function () {
  
        var child = $(this).closest('tr').nextAll();
        child.each(function () {
            // Getting <tr> id.
            var id = $(this).attr('id');
            // Getting the <p> inside the .row-index class.
            var idx = $(this).children('.row-index').children('p');
            // Gets the row number from <tr> id.
            var dig = parseInt(id.substring(1));
            // Modifying row index.
            idx.html(`Row ${dig - 1}`);
            // Modifying row id.
            $(this).attr('id', `R${dig - 1}`);
        });
        var  no = $(this).parents("tr").find('input[name="rownum"]').val();
        var item_code =$(this).parents("tr").find('input[name="item_code'+no+'"]').val();
        
        var gross_total = $("#gross_total_val").val();
        var cgst_total = $("#cgst_total_val").val();
        var sgst_total = $("#sgst_total_val").val();
        var igst_total = $("#igst_total_val").val();
        var net_total = $("#net_total_val").val();
        var act_state = $("#account_state").val();
        var total_amt =$(this).parents("tr").find('input[name="total_amt_val'+no+'"]').val();
        
        if(act_state =="UP"){
            var sgst_amt =$(this).parents("tr").find('input[name="sgst_amt_val'+no+'"]').val();
            var cgst_amt =$(this).parents("tr").find('input[name="cgst_amt_val'+no+'"]').val();
            var sale_total = parseFloat(total_amt) - parseFloat(sgst_amt) - parseFloat(cgst_amt);
            
            sgst_total = parseFloat(sgst_total) - parseFloat(sgst_amt);
            $('#sgst_total').html(parseFloat(sgst_total).toFixed(2));
            $('#sgst_total_val').val(parseFloat(sgst_total).toFixed(2));
                    
            cgst_total = parseFloat(cgst_total) - parseFloat(cgst_amt);
            $('#cgst_total').html(parseFloat(cgst_total).toFixed(2));
            $('#cgst_total_val').val(parseFloat(cgst_total).toFixed(2));
        }else{
            
            var igst_amt =$(this).parents("tr").find('input[name="igst_amt_val'+no+'"]').val();
            var sale_total = parseFloat(total_amt) - parseFloat(igst_amt);
            
            igst_total = parseFloat(igst_total) - parseFloat(igst_amt);
            $('#igst_total').html(parseFloat(igst_total).toFixed(2));
            $('#igst_total_val').val(parseFloat(igst_total).toFixed(2));
        }
        
        gross_total = parseFloat(gross_total) - parseFloat(sale_total);
        $('#gross_total').html(parseFloat(gross_total).toFixed(2));
        $('#gross_total_val').val(parseFloat(gross_total).toFixed(2));
              
        net_total = parseFloat(net_total) - parseFloat(total_amt);
        $('#net_total').html(parseFloat(net_total).toFixed(2));
        $('#net_total_val').val(parseFloat(net_total).toFixed(2));
        
        // Removing the current row.
        $(this).closest('tr').remove();
      
        // Decreasing the total number of rows by 1.
        rowIdx--;
        var countof_record = $("#countof_record").val();
        var new_cont = countof_record -1;
        $("#countof_record").val(new_cont);
        if(new_cont == 1){
            $("#tax_id").val('');
        }
        var new_rec = $('#new_record').val();
        
        $new_item_code = ','+item_code;
        new_rec = new_rec.replace($new_item_code, " ");
                
        $('#new_record').val(new_rec);
    
    });


    $("#tbody").on("click", ".edit_row", function(){  
        var  num = $(this).parents("tr").find('input[name="rownum"]').val();
        var return_qty = $(this).parents("tr").find('input[name="return_qty'+num+'"]').val();
        $(this).parents("tr").find("td:eq(6)").html('<input name="return_qty'+num+'" id="return_qty'+num+'" value="'+return_qty+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)">');
      
        $(this).parents("tr").find("td:eq(17)").prepend("<button class='btn btn-info btn-xs btn-update'>Update</button><button class='btn btn-warning btn-xs btn-cancel'>Cancel</button>")  
        $(this).hide();  
    });  

    $("body").on("click", ".btn-cancel", function(){  
     
        var  num = $(this).parents("tr").find('input[name="rownum"]').val();
        var return_qty = $(this).parents("tr").find('input[name="return_qty'+num+'"]').val();
        $(this).parents("tr").find("td:eq(6)").html('<span>'+return_qty+'</span><input type="hidden" name="return_qty'+num+'" id="return_qty'+num+'" value="'+return_qty+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)">');
        $(this).parents("tr").find(".edit_row").show(); 
        $(this).parents("tr").find(".btn-update").remove();
        $(this).parents("tr").find(".btn-cancel").remove();  
    });  
    
    $("body").on("click", ".btn-update", function(){ 
        var  num = $(this).parents("tr").find('input[name="rownum"]').val();
        var return_qty = $(this).parents("tr").find('input[name="return_qty'+num+'"]').val();
        var item_code = $(this).parents("tr").find('input[name="item_code'+num+'"]').val();
        $(this).parents("tr").find("td:eq(6)").html('<span>'+return_qty+'</span><input type="hidden" name="return_qty'+num+'" id="return_qty'+num+'" value="'+return_qty+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)">');
      
        var order_qty = $(this).parents("tr").find('input[name="order_qty'+num+'"]').val();
        if(return_qty == "" || return_qty == null || order_qty == "" || order_qty == null){
            $(this).parents("tr").find(".edit_row").show();  
            $(this).parents("tr").find(".btn-cancel").remove();  
            $(this).parents("tr").find(".btn-update").remove();
        }else{
            return_qty = parseFloat(return_qty);
            order_qty = parseFloat(order_qty);
            
            if(return_qty > order_qty){
                alert("please enter quantity should be less than order quantity");
                //$('#return_qty'+num).val('');
                $(this).parents("tr").find('input[name="return_qty'+num+'"]').val('0');
                //$(this).parents("tr").find('input[name="return_qty'+num+'"]').focus();
                //$('#return_qty'+num).focus();
                //$(this).parents("tr").find(".edit_row").show();  
                $(this).parents("tr").find(".btn-cancel").show();  
                $(this).parents("tr").find(".btn-update").show();
            }else{
            
                var act_state = $("#account_state").val();
                var gross_total = $("#gross_total_val").val();
                var cgst_total = $("#cgst_total_val").val();
                var sgst_total = $("#sgst_total_val").val();
                var igst_total = $("#igst_total_val").val();
                var net_total = $("#net_total_val").val();
                var basic_rate = $(this).parents("tr").find('input[name="basic_rate_val'+num+'"]').val();
                var old_grand_amt = $(this).parents("tr").find('input[name="total_amt_val'+num+'"]').val();
                
                var pamt = parseFloat(basic_rate) * return_qty
                    if(act_state == "UP"){
                        var cgst_per = $(this).parents("tr").find('input[name="cgst_per_val'+num+'"]').val();
                        var old_cgst_amt = $(this).parents("tr").find('input[name="cgst_amt_val'+num+'"]').val();
                        var old_sgst_amt = $(this).parents("tr").find('input[name="sgst_amt_val'+num+'"]').val();
                        var old_gst_amt = parseFloat(old_cgst_amt) + parseFloat(old_sgst_amt);
                        var tax_amt = pamt * (cgst_per / 100);
                      
                        $(this).parents("tr").find("td:eq(15)").html('<span>0.00</span><input type="hidden" name="igst_amt_val'+num+'" id="igst_amt_val'+num+'" value="0.00">');
                        $(this).parents("tr").find("td:eq(11)").html('<span>'+tax_amt.toFixed(2)+'</span><input type="hidden" name="cgst_amt_val'+num+'" id="cgst_amt_val'+num+'" value="'+tax_amt+'">');
                        $(this).parents("tr").find("td:eq(13)").html('<span>'+tax_amt.toFixed(2)+'</span><input type="hidden" name="sgst_amt_val'+num+'" id="sgst_amt_val'+num+'" value="'+tax_amt+'">');
                    
                        cgst_total = parseFloat(cgst_total) - old_cgst_amt + parseFloat(tax_amt);
                        $("#cgst_total").html(parseFloat(cgst_total).toFixed(2));
                        $("#cgst_total_val").val(parseFloat(cgst_total).toFixed(2));
                        
                        sgst_total = parseFloat(sgst_total) - old_sgst_amt + parseFloat(tax_amt);
                        $("#sgst_total").html(parseFloat(sgst_total).toFixed(2));
                        $("#sgst_total_val").val(parseFloat(sgst_total).toFixed(2));
                        tax_amt = tax_amt * 2;
                    }else{
                        var igst_per = $(this).parents("tr").find('input[name="igst_per_val'+num+'"]').val(); 
                        var old_gst_amt = $(this).parents("tr").find('input[name="igst_amt_val'+num+'"]').val();
                        var tax_amt = pamt * (igst_per / 100);
                        $(this).parents("tr").find("td:eq(15)").html('<span>'+tax_amt.toFixed(2)+'</span><input type="hidden" name="igst_amt_val'+num+'" id="igst_amt_val'+num+'" value="'+tax_amt+'">');
                        $(this).parents("tr").find("td:eq(11)").html('<span>0.00</span><input type="hidden" name="cgst_amt_val'+num+'" id="cgst_amt_val'+num+'" value="0.00">');
                        $(this).parents("tr").find("td:eq(13)").html('<span>0.00</span><input type="hidden" name="sgst_amt_val'+num+'" id="sgst_amt_val'+num+'" value="0.00">');
                        
                        igst_total = parseFloat(igst_total) - old_gst_amt + parseFloat(tax_amt);
                        $("#igst_total").html(parseFloat(igst_total).toFixed(2));
                        $("#igst_total_val").val(parseFloat(igst_total).toFixed(2));
                    }
                    var grand_amt = parseFloat(pamt) + parseFloat(tax_amt);
                    $(this).parents("tr").find("td:eq(16)").html('<span>'+grand_amt.toFixed(2)+'</span><input type="hidden" name="total_amt_val'+num+'" id="total_amt_val'+num+'" value="'+grand_amt+'">');
                    
                    //summery calculation
                      
                    old_grand_amt = old_grand_amt - old_gst_amt;
                    var temp_grs = parseFloat(gross_total) - old_grand_amt;
                    gross_total = temp_grs + parseFloat(grand_amt);
                  
                    var temp_net = parseFloat(net_total) - parseFloat(old_grand_amt);
                    temp_net = temp_net - old_gst_amt;
                    net_total = parseFloat(temp_net) + parseFloat(grand_amt);
                    $('#net_total').html(parseFloat(net_total).toFixed(2));
                    $('#net_total_val').val(parseFloat(net_total).toFixed(2));
                  
                    var new_grand = $("#net_total_val").val();
                    if(act_state == "UP"){
                      var cgst = $("#cgst_total_val").val();
                      var sgst = $("#sgst_total_val").val();
                      var new_gst = parseFloat(cgst) + parseFloat(sgst);
                    }else{
                      var new_gst = $("#igst_total_val").val();
                    }
                    var new_gross = net_total - new_gst;
                  
                    $('#gross_total').html(parseFloat(new_gross).toFixed(2));
                    $('#gross_total_val').val(parseFloat(new_gross).toFixed(2));
                  
                    if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
                      
                    }else{
                      var ex_edit_rec = $('#updated_record').val();
                      ex_edit_rec = ex_edit_rec +","+ item_code
                      $('#updated_record').val(ex_edit_rec);
                    }
                  
                    $(this).parents("tr").find(".edit_row").show();  
                    $(this).parents("tr").find(".btn-cancel").remove();  
                    $(this).parents("tr").find(".btn-update").remove();
            }
        }
    });  
}); 
$(document).ready(function(){
    $('#receipts_amt').on('keyup', function () {
        var balamt_new = $("#balamth").val();
        var ramt = $(this).val();
        var bamt2 = balamt_new - ramt;
        $('#balamt').html(bamt2.toFixed(2));
    });  
    
    $('#disc_amt').on('keyup', function () {
        var balamt_new = $("#balamth").val();
        var ramt2 = $("#receipts_amt").val();
        var damt = $(this).val();
       if(damt === ''){
       }else{
           var damt_include = parseFloat(ramt2) + parseFloat(damt);
            var bamt3 = balamt_new - damt_include;
            $('#balamt').html(bamt3.toFixed(2));
       }
    });  
    
    // new code
    
    $('input[type=radio][name=type_select]').change(function() {
        if (this.value == 'credit') {
            $(".credit_div").css("display","");
            $(".debit_div").css("display","none");
        }
        else if (this.value == 'debit') {
            $(".credit_div").css("display","none");
            $(".debit_div").css("display","");
        }
    });
    
    
    // Initialize For Account
     $( "#act_name" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/Sale_return/accountlist",
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
        
       var old_AccountID = $('#old_act_name').val();
       if(empty(old_AccountID)){
          $('#act_name').val(ui.item.value);
          $('#account_full_name').val(ui.item.label);
          $('#account_address').val(ui.item.address); 
          $('#account_address2').val(ui.item.address2);
          $('#account_state').val(ui.item.state);
          $('#account_station').val(ui.item.station);
          $('#gst_no').val(ui.item.gst);
          $('#account_route').val(ui.item.route_name);
          $('#account_type').val(ui.item.account_type_name);
          get_sale_item(ui.item.value);
            $("#item_code").focus();
            return false;
       }else{
              $('#act_name').val(ui.item.value);
                return false;
            }
        }
      });
      
    $('#act_name').on('blur', function () {
      
        var AccountID = $(this).val();
        var old_AccountID = $('#old_act_name').val();
        if(empty(old_AccountID)){
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/sale_return/get_Account_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#act_name").val('');
                            $("#act_name").focus();
                        }else{
                          $('#act_name').val(data.AccountID);
                          $('#account_full_name').val(data.company); 
                          $('#account_address').val(data.address); 
                          $('#account_address2').val(data.address3); 
                          $('#account_state').val(data.state); 
                          $('#account_station').val(data.StationName); 
                          $('#gst_no').val(data.vat); 
                          $('#account_route').val(data.name); 
                          $('#account_type').val(data.DistributorType); 
                          get_sale_item(data.AccountID);
                            $("#item_code").focus();
                        }
                    }
                });
            }
            
        }else{
            if(old_AccountID == AccountID){
                
            }else{
                var Conform = myFunction();
                if(Conform == true){
                    $.ajax({
                        url: "<?=base_url()?>admin/sale_return/get_Account_Details",
                        type: 'post',
                        dataType: "json",
                        data: {
                          AccountID: AccountID,
                        },
                        success: function( data ) {
                            if(empty(data)){
                                alert('AccountID not found.');
                                $("#act_name").val(old_AccountID);
                            }else{
                                $('#account_full_name').val(data.company); // display the selected text
                                $('#account_address').val(data.address); // display the selected text
                                $('#account_address2').val(data.address2); // display the selected text
                                $('#account_state').val(data.state); // display the selected text
                                $('#account_station').val(data.station); // display the selected text
                                $('#gst_no').val(data.gst); // display the selected text
                                $('#account_route').val(data.route_name); // display the selected text
                                $('#account_type').val(data.account_type_name); // display the selected text
                                delete_row();
                                get_sale_item(data.AccountID);
                                $("#item_code").focus();
                            }
                        }
                    });
                }else{
                    $('#act_name').val(old_AccountID);
                }
            }
        }
        
    });
      
      // Initialize For Account
     $( "#item_code" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          var act_code = $("#act_name").val(); 
             if(act_code){
                $.ajax({
                    url: "<?=base_url()?>admin/Sale_return/itemlist",
                    type: 'post',
                    dataType: "json",
                    data: {
                      search: request.term,
                      act_code:act_code,
                    },
                    success: function( data ) {
                      response( data );
                    }
                  });
             }else{
                 alert("please select account first...");
                 $("#act_name").focus();
             }
          
        },
        select: function (event, ui) {
          var act_code = $("#act_name").val(); 
          var sale_item = $("#sale_item").val();
          let sale_item_array = sale_item.split(",");
        if(sale_item_array.includes(ui.item.value)){
            
          $('#item_code').val(ui.item.value); // display the selected text
          $('#item_name').html(ui.item.label); // display the selected text
          $('#item_name_val').val(ui.item.label);
          $('#pack').html(ui.item.case_qty); // display the selected text
          $('#pack_val').val(ui.item.case_qty);
          var act_state = $("#account_state").val();
          if(act_state == "UP"){
            var cgst = ui.item.tax / 2;
            $('#cgst').html(cgst);
            $('#sgst').html(cgst);
            $('#igst').html("0.00");
            $('#cgst_per_val').val(cgst.toFixed(2));
            $('#sgst_per_val').val(cgst.toFixed(2));
            $('#igst_per_val').val("0.00");
          }else{
            $('#igst').html(ui.item.tax);
            $('#cgst').html("0.00");
            $('#sgst').html("0.00");
            $('#cgst_per_val').val("0.00");
            $('#sgst_per_val').val("0.00");
            $('#igst_per_val').val(ui.item.tax);
          }
          
          $('#hsndesc_val').val(ui.item.hsn_code);
            init_model(ui.item.value,act_code);
            $('#sales_item_modal').on('shown.bs.modal', function () {
              $('#myInput3').focus();
            })
            return false;  
        }else{
            alert("item not purchased by this party");
            $("#item_code").val('');
            $('#item_name').html('');
            $('#item_name_val').val('');
            $('#pack').html(''); 
            $('#pack_val').val('');
            $("#item_code").focus();
            return true;
        }
        }
    });
    $('#item_code').on('focus', function () {
        $("#item_code").val('');
        $('#item_name').html('');
        $('#item_name_val').val('');
        $('#pack').html(''); 
        $('#pack_val').val('');
        $('#sale_id').val('');
        $('#fy_val').val('');
        $('#billed_cs_val').val('');
        $('#basic_rate_val').val('');
        $('#sale_rate_val').val('');
        $('#cgst_per_val').val('');
        $('#sgst_per_val').val('');
        $('#igst_per_val').val('');
        
        $('#fy').html('');
        $('#billed_cs').html('');
        $('#basic_rate').html('');
        $('#discamt').html('');
        $('#cgst').html('');
        $('#sgst').html('');
        $('#igst').html('');
    })
    $('#item_code').on('blur', function () {
        var ItemID = $('#item_code').val();
        if(ItemID !== ''){
            $.ajax({
                url: "<?=base_url()?>admin/sale_return/ItemDetails",
                type: 'post',
                dataType: "json",
                data: {
                  ItemID: ItemID,
                },
                success: function( data ) {
                    if(data == null){
                       alert('Item Not found...');
                       $("#item_code").val('');
                       $("#item_code").focus();
                        return false;
                    }else{
                        var act_code = $("#act_name").val(); 
                        var SaleID = $("#SaleID").val(); 
                        var sale_item = $("#sale_item").val();
                        let sale_item_array = sale_item.split(",");
                        if(sale_item_array.includes(data.item_code)){
                            
                          $('#item_code').val(data.item_code); // display the selected text
                          $('#item_name').html(data.description); // display the selected text
                          $('#item_name_val').val(data.description);
                          $('#pack').html(data.case_qty); // display the selected text
                          $('#pack_val').val(data.case_qty);
                          var act_state = $("#account_state").val();
                          
                          $('#hsndesc_val').val(data.hsn_code);
                          if(SaleID == "" || SaleID == null){
                                init_model(data.item_code,act_code);
                                $('#sales_item_modal').on('shown.bs.modal', function () {
                                    $('#myInput3').focus();
                                })
                            //$("#sale_id").focus();
                            return false; 
                          }else{
                            
                                var $data_table = $('#data_table');
                                var added_trn_id = $("#tax_id").val();
                                var item_code = $("#item_code").val();
                                var cases = '';
                                if(added_trn_id == SaleID || added_trn_id == "" || added_trn_id == null){
                                   
                                    requestGetJSON('sale_return/get_bill_detail/'+ SaleID+'/'+item_code).done(function (response) {
                                        if(response == null){
                                            alert('This Item is not available in selected SaleID');
                                            $("#item_code").val('');
                                            $('#item_name').html('');
                                            $('#item_name_val').val('');
                                            $('#pack').html(''); 
                                            $('#pack_val').val('');
                                            $("#item_code").focus();
                                            return false;  
                                        }else{
                                            $data_table.find('input[name="sale_id"]').val(response.TransID);
                                        $data_table.find('input[name="fy_val"]').val(response.FY);
                                        cases = response.BilledQty / response.CaseQty;
                                        $data_table.find('input[name="billed_cs_val"]').val(cases);
                                        $data_table.find('input[name="basic_rate_val"]').val(response.BasicRate);
                                        $data_table.find('input[name="sale_rate_val"]').val(response.SaleRate);
                                        $data_table.find('input[name="disc_per_val"]').val(response.DiscPerc);
                                        $data_table.find('input[name="disc_amt_val"]').val(response.DiscAmt);
                                        $data_table.find('input[name="order_qty"]').val(response.BilledQty);
                                        $data_table.find('input[name="cgst_per_val"]').val(response.cgst);
                                        $data_table.find('input[name="sgst_per_val"]').val(response.sgst);
                                        $data_table.find('input[name="igst_per_val"]').val(response.igst);
                                        
                                        $data_table.find('#cgst').html(response.cgst);
                                        $data_table.find('#sgst').html(response.sgst);
                                        $data_table.find('#igst').html(response.igst);
                                        
                                        $data_table.find('#fy').html(response.FY);
                                        $data_table.find('#billed_cs').html(cases);
                                        $data_table.find('#basic_rate').html(response.BasicRate);
                                        $data_table.find('#disc').html(response.DiscPerc);
                                        $data_table.find('#discamt').html(response.DiscAmt);
                                        
                                        $('#tax_id').val(response.TransID);
                                        $('#SaleID').val(response.TransID);
                                        $('#sales_item_modal').modal('hide');
                                        $data_table.find('input[name="return_qty"]').focus();
                                        return true;
                                        }
                                    })
                                }else{
                                        $('#sales_item_modal').modal('hide');
                                        alert("Single SalesID with multiple GST% is only accepted");
                                        $data_table.find('input[name="item_name_val"]').val('');
                                        $data_table.find('input[name="pack_val"]').val('');
                                        $data_table.find('input[name="sale_id"]').val('');
                                        $data_table.find('input[name="cgst_per_val"]').val('');
                                        $data_table.find('input[name="sgst_per_val"]').val('');
                                        $data_table.find('input[name="igst_per_val"]').val('');
                                        
                                        $data_table.find('#item_name').html('');
                                        $data_table.find('#pack').html('');
                                        $data_table.find('#cgst').html('');
                                        $data_table.find('#sgst').html('');
                                        $data_table.find('#igst').html('');
                                        $("#item_code").focus();
                                        return false;
                                    }
                                        }
                            
                        }else{
                            alert("item not purchased by this party");
                            $("#item_code").val('');
                            $('#item_name').html('');
                            $('#item_name_val').val('');
                            $('#pack').html(''); 
                            $('#pack_val').val('');
                            $("#item_code").focus();
                            return true;
                        }
                }
                }
          });
        }
    })  
    $('#return_qty').on('blur', function () {
        var return_qty = $('#return_qty').val();
        var order_qty = $('#order_qty').val();
        
        if(return_qty == "" || return_qty == null || order_qty == "" || order_qty == null){
            
        }else{
            return_qty = parseFloat(return_qty);
            order_qty = parseFloat(order_qty);
            if(return_qty > order_qty){
            alert("please enter quantity should be less than order quantity");
            
            //$('#total_amt_val').val('0.00');
            $('#return_qty').val('');
            $('#return_qty').focus();
        }else{
            
            var act_state = $("#account_state").val();
        var gross_total = $("#gross_total_val").val();
        var cgst_total = $("#cgst_total_val").val();
        var sgst_total = $("#sgst_total_val").val();
        var igst_total = $("#igst_total_val").val();
        var net_total = $("#net_total_val").val();
        var basic_rate = $("#basic_rate_val").val();
        var pamt = parseFloat(basic_rate) * return_qty
          if(act_state == "UP"){
              var cgst_per = $("#cgst_per_val").val();
              var tax_amt = pamt * (cgst_per / 100);
              var tax_amt_new = tax_amt * 2;
              $('#cgstamt').html(parseFloat(tax_amt).toFixed(2));
              $('#cgst_amt_val').val(parseFloat(tax_amt).toFixed(2));
              $('#sgstamt').html(parseFloat(tax_amt).toFixed(2));
              $('#sgst_amt_val').val(parseFloat(tax_amt).toFixed(2));
              
                $('#igstamt').html("0.00");
                $('#igst_amt_val').val("0.00");
                
                
            
          }else{
            var igst_per = $("#igst_per_val").val();  
            var tax_amt = pamt * (igst_per / 100);
            var tax_amt_new = tax_amt;
            $('#igstamt').html(parseFloat(tax_amt).toFixed(2));
            $('#igst_amt_val').val(parseFloat(tax_amt).toFixed(2));
            
                $('#cgstamt').html("0.00");
              $('#cgst_amt_val').val("0.00");
              $('#sgstamt').html("0.00");
              $('#sgst_amt_val').val("0.00");
              
                
          }
          
          var grand_amt = parseFloat(pamt) + parseFloat(tax_amt_new);
          $('#amount').html(parseFloat(grand_amt).toFixed(2));
          $('#total_amt_val').val(parseFloat(grand_amt).toFixed(2));
          add_row();
          
        }
        }
        
    }); 
    
    function myFunction() {
    let text = "Do you really want to change account?";
      if (confirm(text) == true) {
        /*text = "You pressed OK!";*/
        return true;
      } else {
        //text = "You canceled!";
        return false;
      }
      
    }
    
    function delete_row()
    {
        var no =  $("#countof_record").val();
        
        for(var i = 1; i<no;i++){
            document.getElementById("row"+i+"").outerHTML="";
            
        }
     $("#countof_record").val('1');
     $("#tax_id").val('');
     $("#gross_total_val").val('0.00');
     $("#gross_total").html('0.00');
     $("#cgst_total_val").val('0.00');
     $("#cgst_total").html('0.00');
     $("#sgst_total_val").val('0.00');
     $("#sgst_total").html('0.00');
     $("#igst_total_val").val('0.00');
     $("#igst_total").html('0.00');
     $("#net_total_val").val('0.00');
     $("#net_total").html('0.00');
    } 
    
    function get_sale_item(account_id){
        
    $.ajax({
            url: "<?=base_url()?>admin/sale_return/get_sale_item",
            type: 'post',
            dataType: "json",
            data: {
              
              account_id: account_id,
            },
            success: function( data ) {
              $('#sale_item').val(data);
              //return data.TransID;
              //die();
            }
          });
          
    } 
    
    
    function init_model(item_code,act_code){
       
        // If id found get the text from the datatable
        if (typeof (item_code) !== 'undefined') {
            var $itemModal = $('#sales_item_modal');
            var html = '';
            
            requestGetJSON('sale_return/get_bill_id/' + item_code +"/"+ act_code).done(function (response) {
                if(response == "" || response == null){
                    html +='<tr>';
                     html +='<td align="center"colspan="14">Item not sale by selected Account...</td>';
                    html +='</tr>';
                }else{
                    $.each(response, function (column, value) {
                    
                    html +='<tr onclick="get_bill_details(\''+value.TransID+'\')">';
                    html +='<td align="center">'+value.FY+'</td>';
                    html +='<td align="center"><input type="hidden" name="bill_id">'+value.TransID+'</td>';
                    var date = value.TransDate.substring(0, 10);
                    var date_new = date.split("-").reverse().join("/");
                    html +='<td align="center">'+date_new+'</td>';
                    html +='<td align="right">'+value.SaleRate+'</td>';
                    html +='<td align="right">'+value.BasicRate+'</td>';
                    html +='<td align="right">'+value.DiscPerc+'</td>';
                    html +='<td align="right">'+value.DiscAmt+'</td>';
                    html +='<td align="right">'+value.igst+'</td>';
                    html +='<td align="right">'+value.cgst+'</td>';
                    html +='<td align="right">'+value.sgst+'</td>';
                    var cases1 = value.BilledQty / value.CaseQty;
                    html +='<td align="right">'+cases1+'</td>';
                    html +='<td align="right">'+value.BilledQty+'</td>';
                    html +='<td align="right">0</td>';
                    html +='<td align="right">'+value.NetChallanAmt+'</td>';
                    
                    
                    html +='</tr>';
                });
                }
                
                
                $itemModal.find('tbody').html(html);
            });
            $('#sales_item_modal').modal('show');
            
        }
    }
    
    
  
});
</script>
<script>
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
    }
    
    
    

    function get_bill_details(bill_id){
        var $data_table = $('#data_table');
        var added_trn_id = $("#tax_id").val();
        var item_code = $("#item_code").val();
        var cases = '';
        if(added_trn_id == bill_id || added_trn_id == "" || added_trn_id == null){
            requestGetJSON('sale_return/get_bill_detail/'+ bill_id+'/'+item_code).done(function (response) {
            
                $data_table.find('input[name="sale_id"]').val(response.TransID);
                $data_table.find('input[name="fy_val"]').val(response.FY);
                cases = response.BilledQty / response.CaseQty;
                $data_table.find('input[name="billed_cs_val"]').val(cases);
                $data_table.find('input[name="basic_rate_val"]').val(response.BasicRate);
                $data_table.find('input[name="sale_rate_val"]').val(response.SaleRate);
                $data_table.find('input[name="disc_per_val"]').val(response.DiscPerc);
                $data_table.find('input[name="disc_amt_val"]').val(response.DiscAmt);
                $data_table.find('input[name="order_qty"]').val(response.BilledQty);
                $data_table.find('input[name="cgst_per_val"]').val(response.cgst);
                $data_table.find('input[name="sgst_per_val"]').val(response.sgst);
                $data_table.find('input[name="igst_per_val"]').val(response.igst);
                
                $data_table.find('#cgst').html(response.cgst);
                $data_table.find('#sgst').html(response.sgst);
                $data_table.find('#igst').html(response.igst);
                
                $data_table.find('#fy').html(response.FY);
                $data_table.find('#billed_cs').html(cases);
                $data_table.find('#basic_rate').html(response.BasicRate);
                $data_table.find('#disc').html(response.DiscPerc);
                $data_table.find('#discamt').html(response.DiscAmt);
                
                $('#tax_id').val(response.TransID);
                $('#SaleID').val(response.TransID);
                $('#sales_item_modal').modal('hide');
                $data_table.find('input[name="return_qty"]').focus();
                return true;
            })
        }else{
                $('#sales_item_modal').modal('hide');
                alert("Single SalesID with multiple GST% is only accepted");
                $data_table.find('input[name="item_name_val"]').val('');
                $data_table.find('input[name="pack_val"]').val('');
                $data_table.find('input[name="sale_id"]').val('');
                $data_table.find('input[name="cgst_per_val"]').val('');
                $data_table.find('input[name="sgst_per_val"]').val('');
                $data_table.find('input[name="igst_per_val"]').val('');
                
                $data_table.find('#item_name').html('');
                $data_table.find('#pack').html('');
                $data_table.find('#cgst').html('');
                $data_table.find('#sgst').html('');
                $data_table.find('#igst').html('');
                $("#item_code").focus();
                return false;
            }
    }
</script>

<script>
    function add_row()
{
    
 var item_code =document.getElementById("item_code").value;
 var item_name =document.getElementById("item_name_val").value;
 var pack_val =document.getElementById("pack_val").value;
 
 var sale_id =document.getElementById("sale_id").value;
 var fy_val =document.getElementById("fy_val").value;
 var billed_cs_val =document.getElementById("billed_cs_val").value;
 var return_qty =document.getElementById("return_qty").value;
 var basic_rate_val =document.getElementById("basic_rate_val").value;
 var sale_rate_val =document.getElementById("sale_rate_val").value;
 var disc_per_val =document.getElementById("disc_per_val").value;
 var disc_amt_val =document.getElementById("disc_amt_val").value;
 var cgst =document.getElementById("cgst_per_val").value;
 var cgst_amt =document.getElementById("cgst_amt_val").value;
 var sgst =document.getElementById("sgst_per_val").value;
 var sgst_amt =document.getElementById("sgst_amt_val").value;
 var igst =document.getElementById("igst_per_val").value;
 var igst_amt =document.getElementById("igst_amt_val").value;
 var total_amt =document.getElementById("total_amt_val").value;
 var countof_record = document.getElementById("countof_record").value;
	
 var table=document.getElementById("data_table");
 var table_len=(table.rows.length)-1;
 var html = '';
 html += "<tr id='row"+table_len+"'>";
 html += "<td id='item_code"+table_len+"'>"+item_code+" <input type='hidden' name='item_code"+table_len+"' value='"+item_code+"'></td>";
 html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name_val"+table_len+"' value='"+item_name+"'></td>";
 html += "<td id='pack"+table_len+"'>"+pack_val+" <input type='hidden' name='pack_val"+table_len+"' value='"+pack_val+"'></td>";
 html += "<td id='sale_id"+table_len+"'>"+sale_id+" <input type='hidden' name='sale_id"+table_len+"' value='"+sale_id+"'></td>";
 html += "<td id='fy"+table_len+"'>"+fy_val+" <input type='hidden' name='fy_val"+table_len+"' value='"+fy_val+"'></td>";
 html += "<td id='billed_cs"+table_len+"'>"+billed_cs_val+" <input type='hidden' name='billed_cs_val"+table_len+"' value='"+billed_cs_val+"'></td>";
 html += "<td id='return_qty"+table_len+"' align='right'>"+return_qty+" <input type='hidden' name='return_qty"+table_len+"' value='"+return_qty+"'></td>";
 html += "<td id='basic_rate"+table_len+"'>"+basic_rate_val+" <input type='hidden' name='basic_rate_val"+table_len+"' value='"+basic_rate_val+"'><input type='hidden' name='sale_rate_val"+table_len+"' value='"+sale_rate_val+"'></td>";
 html += "<td id='disc"+table_len+"' align='right'>"+disc_per_val+" <input type='hidden' name='disc_per_val"+table_len+"' value='"+disc_per_val+"'></td>";
 html += "<td id='discamt"+table_len+"' align='right'>"+disc_amt_val+" <input type='hidden' name='disc_amt_val"+table_len+"' value='"+disc_amt_val+"'></td>";
 
 html += "<td id='cgst"+table_len+"' align='right'>"+cgst+" <input type='hidden' name='cgst_per_val"+table_len+"' value='"+cgst+"'></td>";
 html += "<td id='cgst_amt"+table_len+"' align='right'>"+cgst_amt+" <input type='hidden' name='cgst_amt_val"+table_len+"' value='"+cgst_amt+"'></td>";
 html += "<td id='sgst"+table_len+"' align='right'>"+sgst+" <input type='hidden' name='sgst_per_val"+table_len+"' value='"+sgst+"'></td>";
 html += "<td id='sgst_amt"+table_len+"' align='right'>"+sgst_amt+" <input type='hidden' name='sgst_amt_val"+table_len+"' value='"+sgst_amt+"'></td>";
 html += "<td id='igst"+table_len+"' align='right'>"+igst+" <input type='hidden' name='igst_per_val"+table_len+"' value='"+igst+"'></td>";
 html += "<td id='igst_amt"+table_len+"' align='right'>"+igst_amt+" <input type='hidden' name='igst_amt_val"+table_len+"' value='"+igst_amt+"'></td>";
 html += "<td id='total_amt"+table_len+"' align='right'>"+total_amt+" <input type='hidden' name='total_amt_val"+table_len+"' value='"+total_amt+"'></td>";
 //html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
 html += '<td><button type="button" name="edit" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum" id="rownum" value="'+countof_record+'"></td>';
 html += '</tr>';
 var row = table.insertRow(table_len).outerHTML=html;

 
 
  
  var temp1 = parseFloat(countof_record) + parseFloat(1);
 
 document.getElementById("countof_record").value=temp1;
 
        var gross_total = $("#gross_total_val").val();
        var cgst_total = $("#cgst_total_val").val();
        var sgst_total = $("#sgst_total_val").val();
        var igst_total = $("#igst_total_val").val();
        var net_total = $("#net_total_val").val();
        var act_state = $("#account_state").val();
        if(act_state == "UP"){
            
            var sale_total = parseFloat(total_amt) - parseFloat(cgst_amt) - parseFloat(sgst_amt);
            
            sgst_total = parseFloat(sgst_total) + parseFloat(sgst_amt);
            $('#sgst_total').html(parseFloat(sgst_total).toFixed(2));
            $('#sgst_total_val').val(parseFloat(sgst_total).toFixed(2));
            
            cgst_total = parseFloat(cgst_total) + parseFloat(cgst_amt);
            $('#cgst_total').html(parseFloat(cgst_total).toFixed(2));
            $('#cgst_total_val').val(parseFloat(cgst_total).toFixed(2));
                
        }else{
            var sale_total = parseFloat(total_amt) - parseFloat(igst_amt);
            igst_total = parseFloat(igst_total) + parseFloat(igst_amt);
            $('#igst_total').html(parseFloat(igst_total).toFixed(2));
                $('#igst_total_val').val(parseFloat(igst_total).toFixed(2));
        }
  
 gross_total = parseFloat(gross_total) + parseFloat(sale_total);
          $('#gross_total').html(parseFloat(gross_total).toFixed(2));
          $('#gross_total_val').val(parseFloat(gross_total).toFixed(2));
          
          net_total = parseFloat(net_total) + parseFloat(total_amt);
          $('#net_total').html(parseFloat(net_total).toFixed(2));
          $('#net_total_val').val(parseFloat(net_total).toFixed(2));
        if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
        }  else{
            var new_rec = $('#new_record').val();
            
                new_rec = new_rec +","+ item_code
            
              $('#new_record').val(new_rec);
        }
 
 
 
document.getElementById("item_code").value="";
document.getElementById("item_name_val").value="";
document.getElementById("pack_val").value="";
document.getElementById("sale_id").value="";
document.getElementById("order_qty").value="";
document.getElementById("fy_val").value="";
document.getElementById("billed_cs_val").value="";
document.getElementById("return_qty").value="";
document.getElementById("basic_rate_val").value="";
document.getElementById("sale_rate_val").value="";
document.getElementById("disc_per_val").value="";
document.getElementById("disc_amt_val").value="";
document.getElementById("cgst_per_val").value="";
document.getElementById("cgst_amt_val").value="";
document.getElementById("sgst_per_val").value="";
document.getElementById("sgst_amt_val").value="";
document.getElementById("igst_per_val").value="";
document.getElementById("igst_amt_val").value="";
document.getElementById("total_amt_val").value="";

document.getElementById("item_name").innerHTML="";
 document.getElementById("pack").innerHTML="";
 document.getElementById("fy").innerHTML="";
 document.getElementById("billed_cs").innerHTML="";
 document.getElementById("basic_rate").innerHTML="";
 document.getElementById("disc").innerHTML="";
 document.getElementById("discamt").innerHTML="";
 document.getElementById("cgst").innerHTML="";
 document.getElementById("cgstamt").innerHTML="";
 document.getElementById("sgst").innerHTML="";
 document.getElementById("sgstamt").innerHTML="";
 document.getElementById("igst").innerHTML="";
 document.getElementById("igstamt").innerHTML="";
 document.getElementById("amount").innerHTML="";
 
 document.getElementById("item_code").focus();
}
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
   
    $('#sale_return_date').datetimepicker({
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

</body>
</html>
