<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row" style="display:none;">
			<div class="col-md-12">
				<table id="print_table">
					<thead>
						<tr>
							<th align="center" colspan="13"><?php echo $company_detail->company_name; ?></th>
						</tr>
						<tr>
							<th align="center" colspan="13"><?php echo $company_detail->address; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="13"><b>Debit Note Details</b></td>
						</tr>
						<tr>
							<td colspan="3"><?php echo $cd_notes_details->Billno ; ?></td>
							<td colspan="3"><b>Date : </b><?php echo _d(substr($cd_notes_details->Transdate,0,10)) ; ?></td>
							<td colspan="3"><b>Type : </b><?php echo $cd_notes_details->BT; ?></td>
							<td colspan="4"><?php echo $cd_notes_details->narration;?></td>
						</tr>
						<tr>
							<td colspan="3"><b>AccountID : </b><?php echo $cd_notes_details->accounts->AccountID; ?></td>
							<td colspan="3"><b>Name : </b> <?php echo $cd_notes_details->accounts->company; ?></td>
							<td colspan="3"><b>Station : </b><?php echo $cd_notes_details->accounts->StationName; ?></td>
							<td colspan="4"><b>Mobile : </b><?php echo $cd_notes_details->accounts->phonenumber; ?></td>
						</tr>
						<tr>
							<td colspan="3"><b>GSTIN : </b><?php echo $cd_notes_details->accounts->vat; ?></td>
							<td colspan="3"><b>State : </b> <?php echo $cd_notes_details->accounts->state; ?></td>
							<td colspan="7"><b>Address : </b><?php echo $cd_notes_details->accounts->address; ?></td>
							<!--<td colspan="4"><b>Mobile : </b><?php echo $cd_notes_details->accounts->phonenumber; ?></td>-->
						</tr>
						
						<tr class="print_item_h">
							<td align="center">ItemID</td>
							<td align="center">Item Name</td>
							<td align="center">HSN/SAC</td>
							<td align="center">Description</td>
							<td align="center">SalesID</td>
							<td align="center">Rate</td>
							<td align="center">CGST%</td>
							<td align="center">CGSTAmt</td>
							<td align="center">SGST%</td>
							<td align="center">SGSTAmt</td>
							<td align="center">IGST%</td>
							<td align="center">IGSTAmt</td>
							<td align="center">Amount</td>
						</tr>
						<?php
							if(isset($cd_notes_details)){
								foreach ($cd_notes_details->items as $key => $value) {
								?>
								<tr>
									<td ><?php echo $value["itemid"]; ?></td>
									<td ><?php echo $value["description"]; ?></td>
									<td ><?php echo $value["hsncode"]; ?></td>
									<td ><?php echo $value["hsncode"]; ?></td>
									<td ><?php echo $value["TransID"]; ?></td>
									<td ><?php echo round($value["rate"],2); ?></td>
									<td align="center"><?php echo $value["cgst"]; ?></td>
									<td align="right"><?php echo $value["cgstamt"]; ?></td>
									<td align="center"><?php echo $value["cgst"]; ?></td>
									<td align="right"><?php echo $value["sgstamt"]; ?></td>
									<td align="center"><?php echo $value["igst"]; ?></td>
									<td align="right"><?php echo $value["igstamt"]; ?></td>
									<td align="right"><?php echo $value["amount"]; ?></td>
								</tr>    
								<?php
								}
							}
						?>
						<tr>
                            <td colspan="12" align="right">Gross Amt</td>
                            <?php $value = (isset($cd_notes_details) ? $cd_notes_details->SaleAmt : '0.00'); ?>
                            <td align="right"><?php echo $value; ?></td>
						</tr>
                        <tr>
                            <td colspan="12" align="right">CGST Amt</td>
                            <?php $value = (isset($cd_notes_details) ? $cd_notes_details->cgstamt : '0.00'); ?>
                            <td align="right"><?php echo $value; ?></td>
						</tr>
                        <tr>
                            <td colspan="12" align="right">SGST Amt</td>
                            <?php $value = (isset($cd_notes_details) ? $cd_notes_details->sgstamt : '0.00'); ?>
                            <td align="right"><?php echo $value; ?></td>
						</tr>
                        <tr>
                            <td colspan="12" align="right">IGST Amt</td>
                            <?php $value = (isset($cd_notes_details) ? $cd_notes_details->igstamt : '0.00'); ?>
                            <td align="right"><?php echo $value; ?></td>
						</tr>
                        <tr>
                            <td colspan="12" align="right">Net Amt</td>
                            <?php $value = (isset($cd_notes_details) ? $cd_notes_details->BillAmt : '0.00'); ?>
                            <td align="right"><?php echo $value; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						
			   <nav aria-label="breadcrumb">
                    				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
                    					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
                    					<li class="breadcrumb-item active text-capitalize"><b>Accounts</b></li>
                    					<li class="breadcrumb-item active" aria-current="page"><b>Debit Note</b></li>
                    				</ol>
                                </nav>
                                <hr class="hr_style">
						
						<?php hooks()->do_action('before_items_page_content'); ?>
						
						<div class="clearfix"></div>
						<!--<hr class="hr-panel-heading" />-->
						<?php if(isset($cd_notes_details)){
						?>
						<input type="hidden" name="Autopost" id="Autopost" value="<?php echo $cd_notes_details->IsAutopost;?>">
						<?php echo form_open('admin/cd_notes/edit',array('id'=>'cd_note_form')); ?>
						<?php }else{ ?>
						<?php echo form_open('admin/cd_notes/add',array('id'=>'cd_note_form')); ?>
						<?php } ?>
						
						<div class="row">
							
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-8">
										
										<!--START First row-->
										<div class="row">
											
											<div class="col-md-3" style="padding-right: 0px;">
												<?php
													$selected_company = $this->session->userdata('root_company');
													if($selected_company == 1){
														
														$new_creditNumber = get_option('next_credit_number_for_cspl');
														$new_debitNumber = get_option('next_debit_number_for_cspl');
														}elseif($selected_company == 2){
														$new_creditNumber = get_option('next_credit_number_for_cff');
														$new_debitNumber = get_option('next_debit_number_for_cff');
														}elseif($selected_company == 3){
														$new_creditNumber = get_option('next_credit_number_for_cbu');
														$new_debitNumber = get_option('next_debit_number_for_cbu');
														}elseif($selected_company == 4){
														$new_creditNumber = get_option('next_credit_number_for_cbupl');
														$new_debitNumber = get_option('next_debit_number_for_cbupl');
													}
													
													$format = get_option('invoice_number_format');
													
													
													$prefix = "CR";
													$prefix2 = "DB";
													if ($format == 1) {
														$__number = $new_creditNumber;
														$__number2 = $new_debitNumber;
														$prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>';
														$prefix2 = $prefix2.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>';
														} else if($format == 2) {
														
														$__number = $new_creditNumber;
														$__number2 = $new_debitNumber;
														$prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>/';
														$prefix2 = $prefix2.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>/';
														
														} else if($format == 3) {
														$__number2 = $new_debitNumber;
														$__number = $new_creditNumber;
														
														} else if($format == 4) {
														
														$yyyy = date('Y');
														$mm = date('m');
														$__number2 = $new_debitNumber;
														$__number = $new_creditNumber;
														
													}
													
													
													$_credit_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
													$_debit_number = str_pad($__number2, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
												?>
												<div class="form-group credit_div" style="<?php if(isset($cd_notes_details) && $cd_notes_details->BT=="D" || !isset($cd_notes_details)){ echo 'display:none'; }?>">
													<label for="number">
														Credit
													</label>
													<div class="input-group">
														<span class="input-group-addon">
															<?php
																echo $prefix;
															?>
														</span>
														
														<input type="text" name="credit_noteid" id="credit_noteid" class="form-control credit_noteid" value="<?php if(isset($cd_notes_details)){ echo substr($cd_notes_details->Billno,4);}else{ echo $_credit_number; } ?>" <?php if(isset($cd_notes_details)){ echo "disabled";} ?>>
														
													</div>
												</div>
												<?php if(isset($cd_notes_details)){
												?>
												<input type="hidden" name="updated_record" value=" " id="updated_record">
												<input type="hidden" name="new_record" value=" " id="new_record">
												<input type="hidden" name="orignal_date" value="<?php if(isset($cd_notes_details)){ echo substr($cd_notes_details->Transdate,0,10);}?>" id="orignal_date">
												<?php } ?>
												<input type="hidden" name="ex_credit_noteid" id="ex_credit_noteid" value="<?php if(isset($cd_notes_details)){ echo $cd_notes_details->Billno;}?>">
												
												<div class="form-group debit_div" style="<?php if((isset($cd_notes_details) && $cd_notes_details->BT=="C")){ echo 'display:none'; }?>">
													<label for="number">
														Debit
														<!-- <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('invoice_number_not_applied_on_draft') ?>" data-placement="top"></i>-->
													</label>
													<div class="input-group">
														<span class="input-group-addon">
															<?php
																echo $prefix2;
															?>
														</span>
														
														<input type="text" name="debit_noteid" id="debit_noteid" class="form-control debit_noteid" value="<?php if(isset($cd_notes_details)){ echo substr($cd_notes_details->Billno,4);}else{ echo $_debit_number; } ?>" <?php if(isset($cd_notes_details)){ echo "disabled";} ?>>
													</div>
												</div>
												<?php //echo render_input('receiptsid','ReceiptID',$_credit_number); ?>
												
											</div>
											<div class="col-md-4" style="padding-right: 0px;">
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
												<?php $value = (isset($cd_notes_details) ? _d(substr($cd_notes_details->Transdate,0,10)) : $to_date); ?>
												<?php echo render_date_input('credit_note_date','Date',$value); ?>
											</div>
											
											
											
											<div class="col-md-3" style="margin-top: 19px;padding: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->vat : ''); ?>
												<input type="text" name="gst_no" id="gst_no" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											<div class="col-md-2" style="margin-top: 19px;padding: 0px;">
												<input type="text" name="account_bal" id="account_bal" class="form-control">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->SubActGroupID : ''); ?>
												<input type="hidden" name="account_group" id="account_group" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											
										</div>
										<!--END First row-->
										
										<!--Start Second row-->
										<div class="row">
											
											<div class="col-md-3" style="padding-right: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->AccountID : ''); ?>
												<?php
													if(isset($cd_notes_details)){
													?>
													<input type="hidden" name="old_act_name" id="old_act_name" value="<?php echo $value; ?>">
													<?php
													}
												?>
												<div class="form-group">
													<label for="act_name">A/c no</label>
													<input type="text" name="act_name" id="act_name" class="form-control" value="<?php echo $value; ?>">
													
												</div>
											</div>
											
											<div class="col-md-4" style="margin-top: 19px;padding: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->company : ''); ?>
												<input type="text" name="account_full_name" id="account_full_name" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											
											
											
											<div class="col-md-1" style="margin-top: 19px;padding: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->state : ''); ?>
												<input type="text" name="account_state" id="account_state" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											<!--<div class="col-md-4" style="margin-top: 19px;padding: 0px;">
												<input type="text" name="account_state_name" id="account_state_name" class="form-control">
												
											</div>-->
											<div class="col-md-4" style="margin-top: 19px;padding: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->StationName : ''); ?>
												<input type="text" name="account_station" id="account_station" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											
										</div>
										
										<!--END Second row-->
										
										<!-- Start third row-->
										<div class="row" style="padding-right: 0px;">
											
											<div class="col-md-6" style="padding-right: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->address : ''); ?>
												<input type="text" name="account_address" id="account_address" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											
											<div class="col-md-6" style="padding: 0px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->accounts->Address3 : ''); ?>
												<input type="text" name="account_address2" id="account_address2" class="form-control" value="<?php echo $value; ?>">
												
											</div>
											
											
										</div>
										
										<!-- END third row-->
										
									</div>
									<div class="col-md-4">
										<?php
											if(isset($cd_notes_details)){
												$typecd = $cd_notes_details->selectType;
												if($cd_notes_details->BT == "C"){
													$value = "credit";
													}else{
													$value = "debit";
												}
											?>
											<input type="hidden" name="type_select2" id="type_select2" value="<?php echo $value;?>">
											<input type="hidden" name="type_cd2" id="type_cd2" value="<?php echo $typecd;?>">
											<?php
												}else{
											?>
											<div class="row" style="display:none;">
												<div class="col-md-6">
													<input type="radio" id="credit" name="type_select" class="type_select" value="credit" >
													<label for="credit">Credit</label>
													<input type="radio" id="debit" name="type_select" class="type_select" value="debit" checked>
													<label for="debit">Debit</label>
													<input type="hidden" name="type_select2" id="type_select2" value="debit">
												</div>
												
												<div class="col-md-6">
													<input type="radio" id="salecd" name="type_cd" class="type_cd" value="salecd" >
													<label for="salecd">Sale CD</label>
													<input type="radio" id="purchasecd" name="type_cd" class="type_cd" value="purchasecd" checked>
													<label for="purchasecd">Purchase CD</label>
													<input type="hidden" name="type_cd2" id="type_cd2" value="purchasecd">
												</div>
												
											</div>
											<?php
											}
										?>
										
										<div class="row">
											<div class="col-md-12" style="margin-top: 4%;height: 97px;">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->narration : ''); ?>
												<textarea class="form-control" name="narration" id="narration"><?php echo $value; ?></textarea>
												<?php $value = (isset($cd_notes_details) ? count($cd_notes_details->items) : ''); ?>
												<input type="hidden" value="<?php echo $value + 1;?>" name="countof_record" id="countof_record">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->purchased_item : ''); ?>
												<input type="hidden" value="<?php echo $value;?>" name="sale_item" id="sale_item">
												<?php $value = (isset($cd_notes_details) ? $cd_notes_details->items[0]["TransID"] : ''); ?>
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
											
											<th style="width:10%">ITEMID</th>
											<th style="width:25%">ITEMNAME</th>
											<th style="width:5%">HSN/SAC</th>
											<th style="width:5%">Description</th>
											<th style="width:15%">SalesID</th>
											<th style="width:5%">Value</th>
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
											if(isset($cd_notes_details)){
												foreach ($cd_notes_details->items as $key => $value) {
													$order_amt = get_order_amt_for_cd_notes($value["hsncode"],$value["TransID"],$value["FY"],$value["PlantID"]);
												?>
												<tr id='row<?php echo $i;?>'>
													<td style="width:10%" id="item_code<?php echo $i;?>"><?php echo $value["itemid"]; ?><input type="hidden" name="item_code<?php echo $i;?>" value="<?php echo $value["itemid"]; ?>" id="item_code<?php echo $i;?>" style="width: 100%;border-radius: 2px;height: 30px;"></td>
													<td style="width:25%" id="item_name<?php echo $i;?>"><?php echo $value["description"]; ?><input type="hidden" name="item_name_val<?php echo $i;?>" id="item_name_val<?php echo $i;?>" value="<?php echo $value["description"]; ?>"></td>
													<td class="hsn<?php echo $i;?>" style="width:5%" ><?php echo $value["hsncode"]; ?><input type="hidden" name="hsn_val<?php echo $i;?>" id="hsn_val<?php echo $i;?>" value="<?php echo $value["hsncode"]; ?>"></td>
													<td style="width:5%" id="hsndesc<?php echo $i;?>"><?php echo $value["hsncode"]; ?><input type="hidden" name="hsndesc_val<?php echo $i;?>" id="hsndesc_val<?php echo $i;?>" value="<?php echo $value["hsncode"]; ?>"></td>
													<td style="width:15%" id="sale_id<?php echo $i;?>"><?php echo $value["TransID"]; ?><input type="hidden" name="sale_id<?php echo $i;?>" id="sale_id<?php echo $i;?>" value="<?php echo $value["TransID"]; ?>" style="height: 30px;width: 100%;" /><input type="hidden" name="order_amt<?php echo $i;?>" id="order_amt<?php echo $i;?>" value="<?php echo $order_amt->total_amount; ?>"></td>
													<td style="width:5%" id="paidamth<?php echo $i;?>"><?php echo round($value["rate"],2); ?><input type="hidden" name="paidamth<?php echo $i;?>" id="paidamth<?php echo $i;?>" value="<?php echo $value["rate"]; ?>" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)" /></td>
													<td style="width:5%" align="right" id="cgst<?php echo $i;?>"><?php echo $value["cgst"]; ?><input type="hidden" name="cgst_per_val<?php echo $i;?>" id="cgst_per_val<?php echo $i;?>" value="<?php echo $value["cgst"]; ?>"></td>
													<td style="width:5%" align="right" id="cgstamt<?php echo $i;?>"><?php echo $value["cgstamt"]; ?><input type="hidden" name="cgst_amt_val<?php echo $i;?>" id="cgst_amt_val<?php echo $i;?>" value="<?php echo $value["cgstamt"]; ?>"></td>
													<td style="width:5" align="right" id="sgst"><?php echo $value["cgst"]; ?><input type="hidden" name="sgst_per_val<?php echo $i;?>" id="sgst_per_val<?php echo $i;?>" value="<?php echo $value["sgst"]; ?>"></td>
													<td style="width:5%" align="right" id="sgstamt<?php echo $i;?>"><?php echo $value["sgstamt"]; ?><input type="hidden" name="sgst_amt_val<?php echo $i;?>" id="sgst_amt_val<?php echo $i;?>" value="<?php echo $value["sgstamt"]; ?>"></td>
													<td style="width:5%" align="right" id="igst<?php echo $i;?>"><?php echo $value["igst"]; ?><input type="hidden" name="igst_per_val<?php echo $i;?>" id="igst_per_val<?php echo $i;?>" value="<?php echo $value["igst"]; ?>"></td>
													<td style="width:5%" align="right" id="igstamt<?php echo $i;?>"><?php echo $value["igstamt"]; ?><input type="hidden" name="igst_amt_val<?php echo $i;?>" id="igst_amt_val<?php echo $i;?>" value="<?php echo $value["igstamt"]; ?>"></td>
													<td style="width:5%" align="right" id="amount<?php echo $i;?>"><?php echo $value["amount"]; ?><input type="hidden" name="total_amt_val<?php echo $i;?>" id="total_amt_val<?php echo $i;?>" value="<?php echo $value["amount"]; ?>"></td>
													<td style="width:5%"><button type="button" name="edit" id="edit_row" class="btn btn-xs btn-succes edit_row" value="edit"><i class="fa fa-pencil " style="font-size:16px;"></i></button><input type='hidden' name='rownum' id='rownum' value="<?php echo $i;?>"></td>
												</tr>    
												<?php
													$i++;
												}
											}
										?>
										<tr id="R1">
											
											<td style="width:10%"><input type="text" name="item_code" id="item_code" style="width: 100%;border-radius: 2px;height: 30px;"></td>
											<td style="width:25%" ><span id="item_name"></span><input type="hidden" name="item_name_val" id="item_name_val" value=""></td>
											<td class="trans1" style="width:5%" ><span id="hsn"></span><input type="hidden" name="hsn_val" id="hsn_val" value=""></td>
											<td style="width:5%"><span id="hsndesc"></span><input type="hidden" name="hsndesc_val" id="hsndesc_val" value=""></td>
											<td style="width:15%" ><input type="text" name="sale_id" id="sale_id" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="order_amt" id="order_amt" value=""></td>
											<td style="width:5%"><input type="text" name="paidamth" id="paidamth" value="" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)" /></td>
											<td style="width:5%" align="right"><span id="cgst"></span><input type="hidden" name="cgst_per_val" id="cgst_per_val" value=""></td>
											<td style="width:5%" align="right"><span id="cgstamt"></span><input type="hidden" name="cgst_amt_val" id="cgst_amt_val" value=""></td>
											<td style="width:5" align="right"><span id="sgst"></span> <input type="hidden" name="sgst_per_val" id="sgst_per_val" value=""></td>
											<td style="width:5%" align="right"><span id="sgstamt"></span><input type="hidden" name="sgst_amt_val" id="sgst_amt_val" value=""></td>
											<td style="width:5%" align="right"><span id="igst"></span><input type="hidden" name="igst_per_val" id="igst_per_val" value=""></td>
											<td style="width:5%" align="right"><span id="igstamt"></span><input type="hidden" name="igst_amt_val" id="igst_amt_val" value=""></td>
											<td style="width:5%" align="right"><span id="amount"></span><input type="hidden" name="total_amt_val" id="total_amt_val" value=""></td>
											
											<td style="width:5%">
												<!--<button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>
												-->   <!--<a href="#" class="addBtn"  id="addBtn"><i class="fa fa-plus-circle " style="font-size:20px;"></i></a>--></td>
										</tr>
									</tbody>
								</table>
								
							</div>
							
							<div class="col-md-8">
								<div class="row" style="margin-top:10%">
									<div class="col-md-6">
										<?php if(has_permission_new('cd_notes','','create')){}else{ ?>
											<span style="color:red;">your not allowed to create CD notes</span><br>
										<?php } ?>
										<?php if(has_permission_new('cd_notes','','view')){}else{ ?>
											<span style="color:red;">your not allowed to show CD notes list</span>
										<?php } ?>
									</div>
									<div class="col-md-2">
										<?php 
											if(isset($cd_notes_details)){
												
												if(has_permission_new('cd_notes','','edit')){ 
													$selected_company = $this->session->userdata('root_company');
													$fy = $this->session->userdata('finacial_year');
													$fy_new  = $fy + 1;
													$first_date = '20'.$fy.'-04-01';
													$lastdate_date = '20'.$fy_new.'-03-31';
													$curr_date = date('Y-m-d');
													$lgstaff = $this->session->userdata('staff_user_id');
													$CDNote_date = substr($cd_notes_details->Transdate,0,10);
													
													$CDNote_date_new    = new DateTime($CDNote_date);
													$first_date_yr = new DateTime($first_date);
													$last_date_yr = new DateTime($lastdate_date);
													$curr_date_new = new DateTime($curr_date);
													
													/*if($cd_notes_details->BT == "C"){
														$sql = 'SELECT * FROM tblcdnote WHERE plantid = '.$selected_company.' AND FY LIKE "'.$fy.'" AND BT = "C" ORDER BY tblcdnote.Billno DESC ';
														}else{
														$sql = 'SELECT * FROM tblcdnote WHERE plantid = '.$selected_company.' AND FY LIKE "'.$fy.'" AND BT = "D" ORDER BY tblcdnote.Billno DESC '; 
														}
														$result_data = $this->db->query($sql)->row();
													$lastdate_CDNote = substr($result_data->Transdate,0,10);*/
													
													if($curr_date_new > $last_date_yr){
														$lastdate = $lastdate_date;
														}else{
														$lastdate = date('Y-m-d');
													}
													
													$this->db->select('*');
													$this->db->where('plant_id', $selected_company);
													$this->db->where('year', $fy);
													$this->db->where('staff_id', $lgstaff);
													$this->db->LIKE('feature', "cd_notes");
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
														
														if ($CDNote_date_new < $tillDate_new) {
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
														if($cd_notes_details->IsAutopost == "Y"){
														?>
														<a href="#" class="btn btn-info disabled">Update</a>
														<?php
															}else{
														?>
														<button type="submit" class="btn btn-info ">Update</button>
														<?php } 
													} ?>
													<?php
													}
													}else{
													if(has_permission_new('cd_notes','','create')){ ?>
													<button type="submit" class="btn btn-info" onclick="this.form.submit();this.disabled = true;"><?php echo _l('submit'); ?></button>
													
													<?php
													} 
											} ?>
											
									</div>
									<!--<div class="col-md-2">
										
										<button type="text" class="btn btn-denger">Cancel</button>
									</div>-->
									<div class="col-md-2">
										<?php if(has_permission_new('cd_notes','','view')){ ?>
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
										<?php $value = (isset($cd_notes_details) ? $cd_notes_details->SaleAmt : '0.00'); ?>
										<td width="30%"><span id="gross_total"><?php echo $value; ?></span><input type="hidden" name="gross_total_val" id="gross_total_val" value="<?php echo $value; ?>"></td>
									</tr>
									<tr>
										<td>CGST Amt</td>
										<?php $value = (isset($cd_notes_details) ? $cd_notes_details->cgstamt : '0.00'); ?>
										<td  width="30%"><span id="cgst_total"><?php echo $value; ?></span><input type="hidden" name="cgst_total_val" id="cgst_total_val" value="<?php echo $value; ?>"></td>
									</tr>
									<tr>
										<td>SGST Amt</td>
										<?php $value = (isset($cd_notes_details) ? $cd_notes_details->sgstamt : '0.00'); ?>
										<td  width="30%"><span id="sgst_total"><?php echo $value; ?></span><input type="hidden" name="sgst_total_val" id="sgst_total_val" value="<?php echo $value; ?>"></td>
									</tr>
									<tr>
										<td>IGST Amt</td>
										<?php $value = (isset($cd_notes_details) ? $cd_notes_details->igstamt : '0.00'); ?>
										<td  width="30%"><span id="igst_total"><?php echo $value; ?></span><input type="hidden" name="igst_total_val" id="igst_total_val" value="<?php echo $value; ?>"></td>
									</tr>
									<tr>
										<td>Net Amt</td>
										<?php $value = (isset($cd_notes_details) ? $cd_notes_details->BillAmt : '0.00'); ?>
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
										<h4 class="modal-title" id="myModalLabel">
											Purchase List
										</h4>
									</div>
									
									<div class="modal-body">
										
										
										<div class="row">
											<div class="col-md-12">
												<div class="Bill_data">
													<table class="table table-striped table-bordered Bill_data" id="Bill_data" width="100%">
														
														
													</table>
												</div>
												
											</div>
											
											
										</div>
									</div>
									
									<div class="modal-footer" style="padding:0px;">
										<input type="text" id="myInput4"  autofocus="1" name='myInput4' onkeyup="myFunction4()" placeholder="Search for names.."  style="float: left;width: 100%;">
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


<div class="modal fade" id="transfer-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Debit Notes List</h4>
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
						<!--<br>
						<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">-->
					</div>
					<div class="col-md-12">
						
						<div class="table_cd_report">
							
							<table class="tree table table-striped table-bordered table_cd_report" id="table_cd_report" width="100%">
								
								<thead>
									<tr>
										<th class="sortablePop" style="width:10%;">Debit Note No</th>
										<th class="sortablePop" style="width:10%;text-align:left;">Debit Type</th>
										<th class="sortablePop" style="width:10%;text-align:left;">TrandDate</th>
										<th class="sortablePop" style="width:28%;text-align:left;">Account Name</th>
										<th class="sortablePop" style="width:5%;text-align:left;">state</th>
										<th class="sortablePop" style="width:3%;text-align:left;">GrossAmt</th>
										<th class="sortablePop" style="width:3%;text-align:left;">CGSTAmt</th>
										<th class="sortablePop" style="width:3%;text-align:left;">SGSTAmt</th>
										<th class="sortablePop" style="width:3%;text-align:left;">IGSTAmt</th>
										<th class="sortablePop" style="width:3%;text-align:left;">NetAmt</th>
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
			<div class="modal-footer" style="padding:0px;">
				<input type="text" id="myInput1"  autofocus="1" name='myInput1' onkeyup="myFunction2()" placeholder="Search for names.."  style="float: left;width: 100%;">
			</div> 
			
		</div>
	</div>
</div>
<?php init_tail(); ?>
<style>
	.table_cd_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table_cd_report thead th { position: sticky; top: 0; z-index: 1; }
	.table_cd_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table_cd_report table  { border-collapse: collapse; width: 100%; }
	.table_cd_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.table_cd_report th     { background: #50607b;color: #fff !important; }
	
	#table_cd_report tr:hover {
    background-color: #ccc;
	}
	
	#table_cd_report td:hover {
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
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	th     { background: #50607b;color: #fff !important; }
	
	
</style>


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
			var type = "D";
			$.ajax({
				url:"<?php echo admin_url(); ?>cd_notes/load_data_for_cd_notes",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date,type:type},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('.table_cd_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('.table_cd_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					var html = '';
					
					for(var count = 0; count < data.length; count++)
					{
						
						var url = "'<?php echo admin_url() ?>cd_notes/edit/"+data[count].Billno+"'";
						html += '<tr onclick="location.href='+url+'">';
						html += '<td style="text-align:center;">'+data[count].Billno+'</td>';
						if(data[count].BT == "C"){
							var bt = "CreditNote";
							}else if(data[count].BT == "D"){
							var bt = "DebitNote";
						}
						html += '<td  style="text-align:center;">'+bt+'</td>';
						var date = data[count].Transdate.substring(0, 10)
						var date_new = date.split("-").reverse().join("/");
						
						html += '<td  style="text-align:center;">'+date_new+'</td>';
						var AccoutName = data[count].AccountName;
						
						html += '<td  style="text-align:left;">'+ AccoutName +'</td>';
						html += '<td  style="text-align:center;">'+data[count].state+'</td>';
						html += '<td  style="text-align:right;">'+data[count].SaleAmt+'</td>';
						html += '<td style="text-align:right;">'+data[count].cgstamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].sgstamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].igstamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].RndAmt+'</td>';
						html += '</tr>';
					}
					$('.table_cd_report tbody').html(html);
					
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
	
	function myFunction4() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput4");
		filter = input.value.toUpperCase();
		table = document.getElementById("Bill_data");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) 
        {
            tr[i].style.display = "none"; 
            td = tr[i].getElementsByTagName("td"); 
			
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;                
                    if (txtValue.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
                        tr[i].style.display = "";  
                        break; 
					}
				}
			}
		}
	}
    function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_cd_report");
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
							} else if(td3){
							txtValue = td3.textContent || td3.innerText;
							if (txtValue.toUpperCase().indexOf(filter) > -1) {
								tr[i].style.display = "";
								} else if(td4){
								txtValue = td4.textContent || td4.innerText;
								if (txtValue.toUpperCase().indexOf(filter) > -1) {
									tr[i].style.display = "";
									} else if(td7){
									txtValue = td7.textContent || td7.innerText;
									if (txtValue.toUpperCase().indexOf(filter) > -1) {
										tr[i].style.display = "";
										} else if(td5){
										txtValue = td5.textContent || td5.innerText;
										if (txtValue.toUpperCase().indexOf(filter) > -1) {
											tr[i].style.display = "";
											} else if(td6){
											txtValue = td6.textContent || td6.innerText;
											if (txtValue.toUpperCase().indexOf(filter) > -1) {
												tr[i].style.display = "";
												} else if(td8){
												txtValue = td8.textContent || td8.innerText;
												if (txtValue.toUpperCase().indexOf(filter) > -1) {
													tr[i].style.display = "";
													} else {
													tr[i].style.display = "none";
												}}}}}}}}}
			}       
		}
	}
</script>
<script type='text/javascript'>
	
	$(document).ready(function () {
		<?php if(isset($cd_notes_details)){    
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
			var total_amt2 = $(this).parents("tr").find('input[name="total_amt_val'+no+'"]').val();
			if(act_state =="UP"){
				var sgst_amt =$(this).parents("tr").find('input[name="sgst_amt_val'+no+'"]').val();
				var cgst_amt =$(this).parents("tr").find('input[name="cgst_amt_val'+no+'"]').val();
				var sale_total = parseFloat(total_amt) - parseFloat(sgst_amt) - parseFloat(cgst_amt);
				var total_tax = parseFloat(sgst_amt) + parseFloat(cgst_amt);
				
				sgst_total = parseFloat(sgst_total) - parseFloat(sgst_amt);
				$('#sgst_total').html(parseFloat(sgst_total).toFixed(2));
				$('#sgst_total_val').val(parseFloat(sgst_total).toFixed(2));
                
				cgst_total = parseFloat(cgst_total) - parseFloat(cgst_amt);
				$('#cgst_total').html(parseFloat(cgst_total).toFixed(2));
				$('#cgst_total_val').val(parseFloat(cgst_total).toFixed(2));
				}else{
				
				var igst_amt =$(this).parents("tr").find('input[name="igst_amt_val'+no+'"]').val();
				var sale_total = parseFloat(total_amt) - parseFloat(igst_amt);
				var total_tax = parseFloat(igst_amt);
				igst_total = parseFloat(igst_total) - parseFloat(igst_amt);
				$('#igst_total').html(parseFloat(igst_total).toFixed(2));
				$('#igst_total_val').val(parseFloat(igst_total).toFixed(2));
			}
			
			
			var new_gross = parseFloat(gross_total) + parseFloat(total_tax);
			gross_total = parseFloat(gross_total) + parseFloat(total_tax) - parseFloat(total_amt);
			$('#gross_total').html(parseFloat(gross_total).toFixed(2));
			$('#gross_total_val').val(parseFloat(gross_total).toFixed(2));
			net_total = parseFloat(net_total) - parseFloat(total_amt).toFixed(2);
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
			//alert(num); 
			var paidamth = $(this).parents("tr").find('input[name="paidamth'+num+'"]').val();
			
			$(this).parents("tr").find("td:eq(5)").html('<input name="paidamth'+num+'" id="paidamth'+num+'" value="'+paidamth+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)"><input type="hidden" name="paidamthORG'+num+'" id="paidamthORG'+num+'" value="'+paidamth+'" >');
			
			$(this).parents("tr").find("td:eq(13)").prepend("<button class='btn btn-info btn-xs btn-update'>Update</button><button class='btn btn-warning btn-xs btn-cancel'>Cancel</button>")  
			$(this).hide(); 
		});  
		
		$("body").on("click", ".btn-cancel", function(){  
			
			var  num = $(this).parents("tr").find('input[name="rownum"]').val();
			var paidamth = $(this).parents("tr").find('input[name="paidamth'+num+'"]').val();
			var paidamthORG = $(this).parents("tr").find('input[name="paidamthORG'+num+'"]').val();
			$(this).parents("tr").find("td:eq(5)").html('<span>'+paidamthORG+'</span><input type="hidden" name="paidamth'+num+'" id="paidamth'+num+'" value="'+paidamthORG+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)">');
            $(this).parents("tr").find(".edit_row").show();  
            
            $(this).parents("tr").find(".btn-update").remove();
            $(this).parents("tr").find(".btn-cancel").remove();  
			
		});
		
		$("body").on("click", ".btn-update", function(){  
			
			var  num = $(this).parents("tr").find('input[name="rownum"]').val();
			var paidamth = $(this).parents("tr").find('input[name="paidamth'+num+'"]').val();
			var paidamthORG = $(this).parents("tr").find('input[name="paidamthORG'+num+'"]').val();
			//alert(paidamth);
			
			var item_code = $(this).parents("tr").find('input[name="item_code'+num+'"]').val();
			
			$(this).parents("tr").find("td:eq(5)").html('<span>'+paidamth+'</span><input type="hidden" name="paidamth'+num+'" id="paidamth'+num+'" value="'+parseFloat(paidamth).toFixed(2)+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)">');
			
			var order_amt = $(this).parents("tr").find('input[name="order_amt'+num+'"]').val();
			
			
            if(paidamth == "" || paidamth == null || order_amt == "" || order_amt == null){
				alert("Order amount is not available for this HSN code, please contact to admin.");
				$(this).parents("tr").find("td:eq(5)").html('<span>'+paidamthORG+'</span><input type="hidden" name="paidamth'+num+'" id="paidamth'+num+'" value="'+parseFloat(paidamthORG).toFixed(2)+'" style="height: 30px;width: 100%;" onkeypress="return isNumber(event)">');
				$(this).parents("tr").find(".edit_row").show();  
				$(this).parents("tr").find(".btn-cancel").remove();  
				$(this).parents("tr").find(".btn-update").remove();
				}else{
				
				paidamth = parseFloat(paidamth);
				order_amt = parseFloat(order_amt);
				
				if(paidamth > order_amt){
					alert("please enter Amount should be less than order Amount");
					
					
					//$('#total_amt_val').val('0.00');
					$('#paidamth"'+num+'"').val('');
					$('#paidamth"'+num+'"').focus();
					$(this).parents("tr").find(".btn-cancel").show();  
					$(this).parents("tr").find(".btn-update").show();
					}else{
					
					var act_state = $("#account_state").val();
					var gross_total = $("#gross_total_val").val();
					var cgst_total = $("#cgst_total_val").val();
					var sgst_total = $("#sgst_total_val").val();
					var igst_total = $("#igst_total_val").val();
					var net_total = $("#net_total_val").val();
					
					var old_grand_amt = $(this).parents("tr").find('input[name="total_amt_val'+num+'"]').val();
					
					if(act_state == "UP"){
						var cgst_per = $(this).parents("tr").find('input[name="cgst_per_val'+num+'"]').val();
						var old_cgst_amt = $(this).parents("tr").find('input[name="cgst_amt_val'+num+'"]').val();
						var old_sgst_amt = $(this).parents("tr").find('input[name="sgst_amt_val'+num+'"]').val();
						var old_gst_amt = parseFloat(old_cgst_amt) + parseFloat(old_sgst_amt);
						var tax_amt = paidamth * (cgst_per / 100);
						
						$(this).parents("tr").find("td:eq(11)").html('<span>0.00</span><input type="hidden" name="igst_amt_val'+num+'" id="igst_amt_val'+num+'" value="0.00">');
						$(this).parents("tr").find("td:eq(7)").html('<span>'+parseFloat(tax_amt).toFixed(2)+'</span><input type="hidden" name="cgst_amt_val'+num+'" id="cgst_amt_val'+num+'" value="'+parseFloat(tax_amt).toFixed(2)+'">');
						$(this).parents("tr").find("td:eq(9)").html('<span>'+parseFloat(tax_amt).toFixed(2)+'</span><input type="hidden" name="sgst_amt_val'+num+'" id="sgst_amt_val'+num+'" value="'+parseFloat(tax_amt).toFixed(2)+'">');
						
						cgst_total = parseFloat(cgst_total) - old_cgst_amt + parseFloat(tax_amt);
						$("#cgst_total").html(parseFloat(cgst_total).toFixed(2));
						$("#cgst_total_val").val(parseFloat(cgst_total).toFixed(2));
						
						sgst_total = parseFloat(sgst_total) - old_sgst_amt + parseFloat(tax_amt);
						$("#sgst_total").html(parseFloat(sgst_total).toFixed(2));
						$("#sgst_total_val").val(parseFloat(sgst_total).toFixed(2));
						var grand_amt = paidamth + tax_amt + tax_amt;
						
						}else{
						var igst_per = $(this).parents("tr").find('input[name="igst_per_val'+num+'"]').val(); 
						var old_gst_amt = $(this).parents("tr").find('input[name="igst_amt_val'+num+'"]').val();
						var tax_amt = paidamth * (igst_per / 100);
						$(this).parents("tr").find("td:eq(11)").html('<span>'+parseFloat(tax_amt).toFixed(2)+'</span><input type="hidden" name="igst_amt_val'+num+'" id="igst_amt_val'+num+'" value="'+parseFloat(tax_amt).toFixed(2)+'">');
						$(this).parents("tr").find("td:eq(7)").html('<span>0.00</span><input type="hidden" name="cgst_amt_val'+num+'" id="cgst_amt_val'+num+'" value="0.00">');
						$(this).parents("tr").find("td:eq(9)").html('<span>0.00</span><input type="hidden" name="sgst_amt_val'+num+'" id="sgst_amt_val'+num+'" value="0.00">');
						
						igst_total = parseFloat(igst_total) - old_gst_amt + parseFloat(tax_amt);
						$("#igst_total").html(parseFloat(igst_total).toFixed(2));
						$("#igst_total_val").val(parseFloat(igst_total).toFixed(2));
						var grand_amt = paidamth + tax_amt;
					}
					
					
					$(this).parents("tr").find("td:eq(12)").html('<span>'+parseFloat(grand_amt).toFixed(2)+'</span><input type="hidden" name="total_amt_val'+num+'" id="total_amt_val'+num+'" value="'+parseFloat(grand_amt).toFixed(2)+'">');
					
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
					var new_gross = new_grand - new_gst;
					$('#gross_total').html(parseFloat(new_gross).toFixed(2));
					$('#gross_total_val').val(parseFloat(new_gross).toFixed(2));
					
					//alert(grand_amt);
					//alert(paidamth);
					if($('#ex_credit_noteid').val() == "" || $('#ex_credit_noteid').val() == null){
						
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
		
		// new code 
		
		$('#data_table').on('click', '.add', function () {
			add_row();
		})
		
	}); 
	$(document).ready(function(){
		$('#receipts_amt').on('keyup', function () {
			
			var balamt_new = $("#balamth").val();
			var ramt = $(this).val();
			var bamt2 = balamt_new - ramt;
			$('#balamt').html(bamt2.toFixed(2));
			
			//$('#balamth').val(bamt2.toFixed(2));
		});  
		
		$('#disc_amt').on('keyup', function () {
			
			var balamt_new = $("#balamth").val();
			var ramt2 = $("#receipts_amt").val();
			var damt = $(this).val();
			if(damt === ''){
				
				}else{
				
				var damt_include = parseFloat(ramt2) + parseFloat(damt);
				//alert(damt_include);
				var bamt3 = balamt_new - damt_include;
				//alert(bamt3);
				$('#balamt').html(bamt3.toFixed(2));
				
				//$('#balamth').val(bamt2.toFixed(2));
			}
			
		});  
		
		// new code
		
		$('input[type=radio][name=type_select]').change(function() {
			if (this.value == 'credit') {
				//alert("credit Thai Gayo Bhai");
				$(".credit_div").css("display","");
				$(".debit_div").css("display","none");
				$("#type_select2").val('credit');
			}
			else if (this.value == 'debit') {
				//alert("debit Thai Gayo");
				$(".credit_div").css("display","none");
				$(".debit_div").css("display","");
				$("#type_select2").val('debit');
			}
		});
		
		$('input[type=radio][name=type_cd]').change(function() {
			if (this.value == 'salecd') {
				$("#type_cd2").val('salecd');
			}
			else if (this.value == 'purchasecd') {
				$("#type_cd2").val('purchasecd');
			}
		});
		
		
		// Initialize For Account
		$( "#act_name" ).autocomplete({
			
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
				
				var old_AccountID = $('#old_act_name').val();
				if(empty(old_AccountID)){
					$('#act_name').val(ui.item.value);
					$('#account_full_name').val(ui.item.label); 
					$('#account_address').val(ui.item.address); 
					$('#account_address2').val(ui.item.address2); 
					$('#account_state').val(ui.item.state);
					$('#account_station').val(ui.item.station); 
					$('#gst_no').val(ui.item.gst); 
					$('#account_group').val(ui.item.act_group); 
					get_sale_item(ui.item.value);
					$("#item_code").focus();
                    return false; 
					}else{
					$('#act_name').val(ui.item.value);
					return false
				}
			}
		});
		
		$('#act_name').on('blur', function () {
			
			var AccountID = $(this).val();
			//alert(AccountID);
			var old_AccountID = $('#old_act_name').val();
			//alert(old_AccountID);
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
								$('#account_full_name').val(data.company); // display the selected text
								$('#account_address').val(data.address); // display the selected text
								$('#account_address2').val(data.address2); // display the selected text
								$('#account_state').val(data.state); // display the selected text
								$('#account_station').val(data.state_name); // display the selected text
								$('#gst_no').val(data.vat); // display the selected text
								$('#account_group').val(data.SubActGroupID); 
								//$('#account_route').val(data.route_name); // display the selected text
								//$('#account_type').val(data.account_type_name); // display the selected text
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
									// $('#account_route').val(data.route_name); // display the selected text
									//$('#account_type').val(data.account_type_name); // display the selected text
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
		// Blur for ItemID
		
		$('#item_code').on('blur', function () {
			var act_code = $("#act_name").val();
			var type_select = $("#type_select2").val();
			var search = $("#item_code").val();
			if(act_code ){
				if(search !== ""){
					$.ajax({
						url: "<?=base_url()?>admin/Cd_notes/itemlistDetails",
						type: 'post',
						dataType: "json",
						data: {
							search: search,
							act_code:act_code,
							type_select:type_select,
						},
						success: function( data ) {
							if(data !== null){
								var act_code = $("#act_name").val(); 
								var sale_item = $("#sale_item").val();
								let sale_item_array = sale_item.split(",");
								//alert(data[0].value);
								if(sale_item_array.includes(data[0].value)){
									var Act_group = $("#account_group").val();
									if(Act_group == ""){
										alert('Account Group Not assigned');
										$('#item_code').val(''); 
										$("#item_code").focus();
										return false;
										}else{
										if(data[0].hsn_code == ""){
											alert('HSN Code not assigned to this Item');
											$('#item_code').val(''); 
											$("#item_code").focus();
											return false;
											}else{
											$('#item_code').val(data[0].value); 
											$('#item_name').html(data[0].label);
											$('#item_name_val').val(data[0].label);
											$('#hsn').html(data[0].hsn_code); 
											$('#hsn_val').val(data[0].hsn_code);
											var act_state = $("#account_state").val();
											if(act_state == "UP"){
												var cgst = data[0].tax / 2;
												$('#cgst').html(cgst);
												$('#sgst').html(cgst);
												$('#igst').html("0.00");
												$('#cgst_per_val').val(cgst.toFixed(2));
												$('#sgst_per_val').val(cgst.toFixed(2));
												$('#igst_per_val').val("0.00");
												}else{
												$('#igst').html(data[0].tax);
												$('#cgst').html("0.00");
												$('#sgst').html("0.00");
												$('#cgst_per_val').val("0.00");
												$('#sgst_per_val').val("0.00");
												$('#igst_per_val').val(data[0].tax);
											}
											$('#hsndesc').html(data[0].hsn_code); // display the selected text
											$('#hsndesc_val').val(data[0].hsn_code);
											$("#sale_id").focus();
											init_model(data[0].hsn_code,act_code);
											return false;
										}
									}
									}else{
									alert("item not purchased by this party11");
									$("#item_code").val('');
									//$("#item_code").focus();
									//return false;
								}
							}
						}
					});
				}
				
				}else{
                alert("please select account first...");
                $("#act_name").focus();
			}
		})
		// Initialize For ItemID
		$( "#item_code" ).autocomplete({
			
			source: function( request, response ) {
				// Fetch data
				var act_code = $("#act_name").val();
				var type_select = $("#type_select2").val();
				if(act_code){
					$.ajax({
						url: "<?=base_url()?>admin/Cd_notes/itemlist",
						type: 'post',
						dataType: "json",
						data: {
							search: request.term,
							act_code:act_code,
							type_select:type_select,
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
					var Act_group = $("#account_group").val();
					if(Act_group == ""){
						alert('Account Group Not assigned');
						$('#item_code').val(''); 
						$("#item_code").focus();
						return false;
						}else{
						if(ui.item.hsn_code == ""){
							alert('HSN Code not assigned to this Item');
							$('#item_code').val(''); 
							$("#item_code").focus();
							return false;
							}else{
							$('#item_code').val(ui.item.value); 
							$('#item_name').html(ui.item.label);
							$('#item_name_val').val(ui.item.label);
							$('#hsn').html(ui.item.hsn_code); 
							$('#hsn_val').val(ui.item.hsn_code);
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
							$('#hsndesc').html(ui.item.hsn_code); // display the selected text
							$('#hsndesc_val').val(ui.item.hsn_code);
							$("#sale_id").focus();
							init_model(ui.item.hsn_code,act_code);
							return false;
						}
					}
					
					}else{
					alert("item not purchased by this party");
					$("#item_code").focus();
					return false;
				}
				
			}
		});
		
		$('#paidamth').on('blur', function () {
			var pamt = $('#paidamth').val();
			var order_amt = $('#order_amt').val();
			order_amt = parseFloat(order_amt);
			pamt = parseFloat(pamt);
			if(pamt > order_amt){
				alert("please enter amount should be less than order amount");
				
				//$('#total_amt_val').val('0.00');
				$('#paidamth').val('');
				$('#paidamth').focus();
				}else{
				if(isNaN(pamt)){
					
					$('#paidamth').focus();
					return false;
					}else{
					
					var act_state = $("#account_state").val();
					var gross_total = $("#gross_total_val").val();
					var cgst_total = $("#cgst_total_val").val();
					var sgst_total = $("#sgst_total_val").val();
					var igst_total = $("#igst_total_val").val();
					var net_total = $("#net_total_val").val();
					
					if(act_state == "UP"){
						var cgst_per = $("#cgst_per_val").val();
						var tax_amt = pamt * (cgst_per / 100);
						$('#cgstamt').html(parseFloat(tax_amt).toFixed(2));
						$('#cgst_amt_val').val(parseFloat(tax_amt).toFixed(2));
						$('#sgstamt').html(parseFloat(tax_amt).toFixed(2));
						$('#sgst_amt_val').val(parseFloat(tax_amt).toFixed(2));
						
                        $('#igstamt').html("0.00");
                        $('#igst_amt_val').val("0.00");
                        
                        cgst_total = parseFloat(cgst_total) + parseFloat(tax_amt);
                        $('#cgst_total').html(parseFloat(cgst_total).toFixed(2));
                        $('#cgst_total_val').val(parseFloat(cgst_total).toFixed(2));
                        
                        sgst_total = parseFloat(sgst_total) + parseFloat(tax_amt);
                        $('#sgst_total').html(parseFloat(sgst_total).toFixed(2));
                        $('#sgst_total_val').val(parseFloat(sgst_total).toFixed(2));
                        $tax_amt2 = parseFloat(tax_amt).toFixed(2) *2;
						
						}else{
						var igst_per = $("#igst_per_val").val();  
						var $tax_amt2 = pamt * (igst_per / 100);
						
						$('#igstamt').html(parseFloat($tax_amt2).toFixed(2));
						$('#igst_amt_val').val(parseFloat($tax_amt2).toFixed(2));
						
						$('#cgstamt').html("0.00");
						$('#cgst_amt_val').val("0.00");
						$('#sgstamt').html("0.00");
						$('#sgst_amt_val').val("0.00");
						
                        igst_total = parseFloat(igst_total) + parseFloat($tax_amt2);
                        $('#igst_total').html(parseFloat(igst_total).toFixed(2));
                        $('#igst_total_val').val(parseFloat(igst_total).toFixed(2));
                        
					}
					
					var grand_amt = parseFloat(pamt) + parseFloat($tax_amt2);
					/*alert(parseFloat(pamt));
						alert(parseFloat($tax_amt2));
					alert(parseFloat(grand_amt));*/
					$('#amount').html(parseFloat(grand_amt).toFixedNoRounding(2));
					$('#total_amt_val').val(parseFloat(grand_amt).toFixedNoRounding(2));
					
					//summery calculation
					
					gross_total = parseFloat(gross_total) + parseFloat(pamt);
					$('#gross_total').html(parseFloat(gross_total).toFixed(2));
					$('#gross_total_val').val(parseFloat(gross_total).toFixed(2));
					
					var new_net_total = parseFloat(net_total) + parseFloat(grand_amt);
					$('#net_total').html(parseFloat(new_net_total).toFixed(2));
					$('#net_total_val').val(parseFloat(new_net_total).toFixed(2));
					//$('#paidamth').focus();
					add_row();
				}
				
			}
			
			
			
		}); 
		
		
		
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
				}
			});
			//alert(ss);
		}    
		
		function init_model(item_hsn,act_code){
			//alert(value);
			
			var Act_group = $("#account_group").val();
			var CDType = $("#type_cd2").val();
			// If id found get the text from the datatable
			if (typeof (item_code) !== 'undefined') {
				var $itemModal = $('#sales_item_modal');
				var html = '';
				
				requestGetJSON('cd_notes/get_bill_id/' + item_hsn +"/"+ act_code + "/"+ Act_group+ "/"+CDType).done(function (response) {
					html +=' <thead>';
					
					if(CDType == "salecd"){
						
						html +='<th align="center">FY</th>';
						html +='<th align="center">SalesID</th>';
						html +='<th align="center">Buyer Order No</th>';
						html +='<th align="center">TransDate</th>';
						html +='<th align="center">GST%</th>';
						html +='<th align="center">BillAmt</th>';
						html +='<th align="center">-SRtnAmt</th>';
						html +='<th align="center">-CDNoteAmt</th>';
						html +='<th align="center">=BalRtnAmt</th>';
						
						}else{
						html +='<th align="center">FY</th>';
						html +='<th align="center">PurchID</th>';
						html +='<th align="center">RecieptDate</th>';
						html +='<th align="center">Invoiceno</th>';
						html +='<th align="center">InvoiceDate</th>';
						html +='<th align="center">PurchQty</th>';
						html +='<th align="center">CaseQty</th>';
						html +='<th align="center">Cases</th>';
						html +='<th align="center">PurchRate</th>';
						html +='<th align="center">CGST</th>';
						html +='<th align="center">SGST</th>';
						html +='<th align="center">IGST</th>';
						html +='<th align="center">Amount</th>';
						html +='<th align="center">RtnQty</th>';
					}
					html +=' <thead>';
					html +='<tbody>';
					$.each(response, function (column, value) {
						if(CDType == "salecd"){
							
							html +='<tr onclick="get_bill_details(\''+value.TransID+'\',\''+value.sum_total+'\',\''+value.igst+'\',\''+value.cgst+'\',\''+value.sgst+'\')">';
							html +='<td align="center">'+value.FY+'</td>';
							html +='<td align="center"><input type="hidden" name="bill_id">'+value.TransID+'</td>';
							html +='<td align="center">'+value.buyer_ord_no+'</td>';
							html +='<td align="center">'+value.TransDate.substring(0, 10)+'</td>';
							if(value.igst == "0.00" || value.igst == null){
								var gst = parseFloat(value.cgst) + parseFloat(value.sgst);
								}else{
								var gst = value.igst;
							}
							html +='<td align="right">'+parseFloat(gst).toFixed(2)+'</td>';
							
							var cases1 = value.BilledQty / value.CaseQty;
							
							html +='<td align="right">'+value.sum_total+'</td>';
							
							html +='<td align="right">0</td>';
							
							html +='<td align="right">0</td>';
							html +='<td align="right">'+value.sum_total+'</td>';
							
							html +='</tr>';
							
							}else{
							html +='<tr onclick="get_bill_details(\''+value.BillID+'\',\''+value.sum_total+'\',\''+value.igst+'\',\''+value.cgst+'\',\''+value.sgst+'\')">';
							html +='<td align="center">'+value.FY+'</td>';
							html +='<td align="center"><input type="hidden" name="bill_id">'+value.BillID+'</td>';
							html +='<td align="center">'+value.TransDate.substring(0, 10)+'</td>';
							html +='<td align="center">'+value.Invoiceno+'</td>';
							html +='<td align="center">'+value.Invoicedate.substring(0, 10)+'</td>';
							var cases1 = value.BilledQty / value.CaseQty;
							
							html +='<td align="right">'+cases1+'</td>';
							
							html +='<td align="right">'+value.CaseQty+'</td>';
							html +='<td align="right">'+cases1+'</td>';
							html +='<td align="right">'+value.BasicRate+'</td>';
							html +='<td align="right">'+value.cgst+'</td>';
							html +='<td align="right">'+value.cgst+'</td>';
							html +='<td align="right">'+value.igst+'</td>';
							html +='<td align="right">'+value.sum_total+'</td>';
							
							html +='<td align="center">0.00</td>';
							html +='</tr>';
						}    
						
						
					});
					html +='</tbody>';
					$itemModal.find('#Bill_data').html(html);
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
		if (charCode != 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57)){
			return false;
		}
		return true;
	}
    
    
    
	
    function get_bill_details(bill_id,sum,igst,cgst,sgst){
        //alert(sum);
        var $data_table = $('#data_table');
        var added_trn_id = $("#tax_id").val();
        //alert(added_trn_id);
        var item_code = $("#item_code").val();
        var hsn_code = $("#hsn_val").val();
        var count_record = $("#countof_record").val();
        
        if(added_trn_id == bill_id || added_trn_id == "" || added_trn_id == null){
            $('#sgst').html(parseFloat(sgst).toFixed(2));
            $('#sgst_per_val').val(parseFloat(sgst).toFixed(2));
            
            $('#cgst').html(parseFloat(cgst).toFixed(2));
            $('#cgst_per_val').val(parseFloat(cgst).toFixed(2));
            
            $('#igst').html(parseFloat(igst).toFixed(2));
            $('#igst_per_val').val(parseFloat(igst).toFixed(2));
            $data_table.find('input[name="sale_id"]').val(bill_id);
			if(count_record > 1){
				
				var new_amt = sum;
				for (let i = 1; i < count_record; i++) {
					
					var new_hsn_code = $data_table.find('input[name="hsn_val'+i+'"]').val();
					
					if(hsn_code == new_hsn_code){
						
						
						var new_order_paidamth = $data_table.find('input[name="paidamth'+i+'"]').val();
						
						//var new_amt = response.sum_amt - parseFloat(new_order_paidamth);
						new_amt = new_amt - parseFloat(new_order_paidamth);
					}
				}
				
				$data_table.find('input[name="order_amt"]').val(new_amt);
				
                }else{
				$data_table.find('input[name="order_amt"]').val(sum);
			}
			
			$('#tax_id').val(bill_id);
			$('#sales_item_modal').modal('hide');
			$data_table.find('input[name="paidamth"]').focus();
			}else{
			$('#sales_item_modal').modal('hide');
			alert("Single SalesID with multiple GST% is only accepted");
			$data_table.find('input[name="item_name_val"]').val('');
			$data_table.find('input[name="hsn_val"]').val('');
			$data_table.find('input[name="hsndesc_val"]').val('');
			$data_table.find('input[name="sale_id"]').val('');
			$data_table.find('input[name="cgst_per_val"]').val('');
			$data_table.find('input[name="sgst_per_val"]').val('');
			$data_table.find('input[name="igst_per_val"]').val('');
			$data_table.find('#item_name').html('');
			$data_table.find('#hsn').html('');
			$data_table.find('#hsndesc').html('');
			$data_table.find('#cgst').html('');
			$data_table.find('#sgst').html('');
			$data_table.find('#igst').html('');
			$("#item_code").focus();
		}
	}
</script>
<script>
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
</script>

<script>
    
    function add_row()
	{
		
		var item_code =document.getElementById("item_code").value;
		var item_name =document.getElementById("item_name_val").value;
		var hsn =document.getElementById("hsn_val").value;
		var hsndesc =document.getElementById("hsndesc_val").value;
		var sale_id =document.getElementById("sale_id").value;
		var order_amt =document.getElementById("order_amt").value;
		var paidamth =document.getElementById("paidamth").value;
		var cgst =document.getElementById("cgst_per_val").value;
		var cgst_amt =document.getElementById("cgst_amt_val").value;
		var sgst =document.getElementById("sgst_per_val").value;
		var sgst_amt =document.getElementById("sgst_amt_val").value;
		var igst =document.getElementById("igst_per_val").value;
		var igst_amt =document.getElementById("igst_amt_val").value;
		var total_amt =document.getElementById("total_amt_val").value;
		var countof_record = document.getElementById("countof_record").value;
		if(item_name !=="" && item_name !==null){
			var table=document.getElementById("data_table");
			var table_len=(table.rows.length)-1;
			var html = '';
			html += "<tr id='row"+table_len+"'>";
			html += "<td id='item_code"+table_len+"'>"+item_code+" <input type='hidden' name='item_code"+table_len+"' value='"+item_code+"'></td>";
			html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name_val"+table_len+"' value='"+item_name+"'></td>";
			html += "<td id='hsn"+table_len+"'>"+hsn+" <input type='hidden' name='hsn_val"+table_len+"' value='"+hsn+"'></td>";
			html += "<td id='hsndesc"+table_len+"'>"+hsndesc+" <input type='hidden' name='hsndesc_val"+table_len+"' value='"+hsndesc+"'></td>";
			html += "<td id='sale_id"+table_len+"'>"+sale_id+" <input type='hidden' name='sale_id"+table_len+"' value='"+sale_id+"'><input type='hidden' name='order_amt"+table_len+"' value='"+order_amt+"'></td>";
			html += "<td id='paidamth"+table_len+"' align='right'>"+paidamth+" <input type='hidden' name='paidamth"+table_len+"' value='"+paidamth+"'></td>";
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
			
			
			
			var countof_record = document.getElementById("countof_record").value;
			var temp1 = parseFloat(countof_record) + parseFloat(1);
			
			document.getElementById("countof_record").value=temp1;
			var new_rec = '';
			new_rec = $('#new_record').val();
			new_rec = new_rec +","+ item_code
			$('#new_record').val(new_rec);
			
			
			document.getElementById("item_code").value="";
			document.getElementById("item_name_val").value="";
			document.getElementById("hsn_val").value="";
			document.getElementById("hsndesc_val").value="";
			document.getElementById("sale_id").value="";
			document.getElementById("order_amt").value="";
			document.getElementById("paidamth").value="";
			document.getElementById("cgst_per_val").value="";
			document.getElementById("cgst_amt_val").value="";
			document.getElementById("sgst_per_val").value="";
			document.getElementById("sgst_amt_val").value="";
			document.getElementById("igst_per_val").value="";
			document.getElementById("igst_amt_val").value="";
			document.getElementById("total_amt_val").value="";
			document.getElementById("item_name").innerHTML="";
			document.getElementById("hsn").innerHTML="";
			document.getElementById("hsndesc").innerHTML="";
			document.getElementById("cgst").innerHTML="";
			document.getElementById("cgstamt").innerHTML="";
			document.getElementById("sgst").innerHTML="";
			document.getElementById("sgstamt").innerHTML="";
			document.getElementById("igst").innerHTML="";
			document.getElementById("igstamt").innerHTML="";
			document.getElementById("amount").innerHTML="";
			
			document.getElementById("item_code").focus();
			}else{
			alert("please add proper item..");
			document.getElementById("item_code").focus();
		}
		
	}
</script>
<script>
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
</script>

<script>
    Number.prototype.toFixedNoRounding = function(n) {
		const reg = new RegExp("^-?\\d+(?:\\.\\d{0," + n + "})?", "g")
		const a = this.toString().match(reg)[0];
		const dot = a.indexOf(".");
		if (dot === -1) { // integer, insert decimal dot and pad up zeros
			return a + "." + "0".repeat(n);
		}
		const b = n - (a.length - dot) + 1;
		return b > 0 ? (a + "0".repeat(b)) : a;
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
		
		$('#credit_note_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
		
	});
</script> 

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
<script>
	
	$(function(){
		"use strict";
		validate_CDNote_form();
		function validate_CDNote_form(selector) {
			
			selector = typeof(selector) == 'undefined' ? '#cd_note_form' : selector;
			
			appValidateForm($(selector), {
				credit_note_date: {
					remote: {
						url: site_url + "admin/misc/checkCDNote_val",
						type: 'post',
						data: {
							credit_note_date: function() {
								return $('input[name="credit_note_date"]').val();
							},
							CDNoteID: function() {
								return $('input[name="ex_credit_noteid"]').val();
							},
							type_select: function() {
								return $('input[name="type_select2"]').val();
							}
						}
					}
				},
				credit_noteid: 'required',
				debit_noteid: 'required',
			});
		}
		
		
	});
	
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_cd_report tbody");
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
</body>
</html>
