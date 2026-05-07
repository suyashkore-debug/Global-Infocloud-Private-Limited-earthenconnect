<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
	$receipe_item = array();
	foreach ($production->items as $key=>$value) {
		array_push($receipe_item, $value["item_id"]);
	}
	$item_string = implode(',', $receipe_item);
	$selected_company = $this->session->userdata('root_company');
?>

<div id="wrapper">
	<div class="content">
        <div class="row" style="display:none;">
			<div class="col-md-12">
				<table id="print_table">
					<thead>
						<tr>
							<th align="center" colspan="8"><?php echo $company_detail->company_name; ?></th>
						</tr>
						<tr>
							<th align="center" colspan="8"><?php echo $company_detail->address; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="8"><b>Production Details</b></td>
						</tr>
						<tr>
							<td><?php echo $production->pro_order_id ; ?></td>
							<td colspan="2"><b>Date : </b><?php echo $production->TransDate ; ?></td>
							<td colspan="2"><b>ItemId : </b><?php echo $production->recipeID; ?></td>
							<td colspan="3"><?php echo $production->description;?></td>
							
						</tr>
						<tr>
							<td><b>BatchQty : </b><?php echo $production->batch_qty ; ?></td>
							<td colspan="2"><b>Std FG : </b> <?php echo $production->Finish_good_qty; ?></td>
							<td colspan="2"><b>FG Unit : </b><?php echo $production->finish_good_unit; ?></td>
							<td colspan="3"><b>STD. Time : </b><?php echo $production->required_time; ?></td>
						</tr>
						<tr>
							<td><b>PRD Time : </b><?php echo $production->production_time ; ?></td>
							<td colspan="2"><b>PRD STRT Time : </b><?php echo $production->p_start_time; ?></td>
							<td colspan="2"><b>PRD END Time : </b><?php echo $production->p_end_time; ?></td>
							<td colspan="3"><b>Status : </b><?php echo $production->production_status; ?></td>
						</tr>
						<tr>
							<?php
								if(is_null($production->manager_name)){
									$name = $production->contractor_name;
									}else{
									$name = $production->manager_name;
								}
							?>
							<td><b>Final FG : </b><?php echo $production->Finish_good_qty_new ; ?></td>
							<td colspan="2"><b>Res. Per : </b><?php echo $name; ?></td>
							<td colspan="2"><b>Comments : </b><?php echo $production->comment; ?></td>
							<td colspan="3"><b>Remark : </b><?php echo $production->remark; ?></td>
						</tr>
						<tr class="print_item_h">
							<td colspan="8" align="center">Raw Materials</td>
						</tr>
						<tr class="print_item_h">
							<td align="center">ItemID</td>
							<td align="center">Item Name</td>
							<td align="center">Std. Qty</td>
							<td align="center">PRD Qty</td>
							<td align="center">Rtn Qty</td>
							<td align="center">Extra Qty</td>
							<td align="center">Acctual qty</td>
							<td align="center">MesuredIn</td>
						</tr>
						<?php
			                foreach ($production->items as $key => $value) {
								if($value['MainGrpID'] == '2'){
								?>
								<tr>
									<td align="center"><?php echo $value['item_id'];?></td>
									<td><?php echo $value['item_name'];?></td>
									<td align="right"><?php echo $value['req_qty'];?></td>
									<td align="right"><?php echo $value['production_req_qty'];?></td>
									<td align="right"><?php echo $value['return_req_qty'];?></td>
									
									<td align="right"><?php echo number_format($value['ExtraQty'], 2); ?></td>
									<td align="right"><?php echo $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];?></td>
									<td align="center"><?php echo $value['unit'];?></td>
								</tr>
							<?php }} ?>
							<tr class="print_item_h">
								<td colspan="8" align="center">Packaging Materials</td>
							</tr>
							<tr class="print_item_h">
								<td align="center">ItemID</td>
								<td align="center">Item Name</td>
								<td align="center">Std. Qty</td>
								<td align="center">PRD Qty</td>
								<td align="center">Rtn Qty</td>
								<td align="center">Extra Qty</td>
								<td align="center">Acctual qty</td>
								<td align="center">MesuredIn</td>
							</tr>
							<?php
								foreach ($production->items as $key => $value) {
									if($value['MainGrpID'] == '3'){
									?>
									<tr>
										<td align="center"><?php echo $value['item_id'];?></td>
										<td><?php echo $value['item_name'];?></td>
										<td align="right"><?php echo $value['req_qty'];?></td>
										<td align="right"><?php echo $value['production_req_qty'];?></td>
										<td align="right"><?php echo $value['return_req_qty'];?></td>
										
										<td align="right"><?php echo number_format($value['ExtraQty'], 2); ?></td>
										<td align="right"><?php echo $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];?></td>
										<td align="center"><?php echo $value['unit'];?></td>
									</tr>
								<?php }} ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						
						<div class="_buttons">
							<h4>Production Order</h4>
						</div>
						<div class="clearfix"></div>
						<?php echo form_open('admin/production/production_order',array('id'=>'productionedit_form')); ?>
						<div class="row">
							
							<?php if(isset($production)){ ?>
								<input type="hidden" name="new_record" value=" " id="new_record">
								<input type="hidden" name="new_record1" value=" " id="new_record1">
								<input type="hidden" name="defualt_finishgood_qty" value="<?php echo $ReceipeDetails->qty;?>" id="defualt_finishgood_qty">
							<?php } ?>
							
							<div class="col-md-2">
								<label class="control-label" for=""><?php echo _l('Production Order Number'); ?></label>
								<input type="text" value="<?php echo $production->pro_order_id;?>" name="sr_no" readonly class="form-control">
								<input type="hidden" value="<?php echo $production->pro_order_id;?>" name="pid" id="pid">
								<input type="hidden" value="<?php echo $production->recipeID;?>" name="ItemID" id="ItemID">
								<input type="hidden" value="<?php echo $item_string;?>" name="item_string" id="item_string">
								<input type="hidden" value="<?php echo $selected_company;?>" name="PlantID" id="PlantID">
							</div>  
							
							<div class="col-md-2">
								<?php
									
									if($production->production_status == "pending"){
										$is_batchedit = '';
										}else{
										$attrDate = array(
										'disabled' =>true
										);
										$is_batchedit = 'readonly';
									}
								?>
								<!--<label class="control-label" for=""><?php echo _l('Date'); ?></label>-->
								<?php $date = _d(substr($production->Date,0,10));?>
								<?php echo render_date_input('start_date','Date',$date,$attrDate); ?>
								<!--<input type="text" value="<?php echo $date;?>" name="start_date" class="form-control">-->
							</div>
							
							<div class="col-md-2">
								<label class="control-label" for=""><?php echo _l('Production For'); ?></label>
								<input type="text" value="<?php echo $production->recipeID;?>" name="recipeID" readonly class="form-control">
								<input type="hidden" value="<?php echo $production->recipeID;?>" name="recipeID1" id="recipeID1" class="form-control">
							</div>
							<div class="col-md-2">
								<label class="control-label" for="recipeName">Production Name</label>
								<input type="text" value="<?php echo $production->description;?>" name="recipeName" readonly class="form-control">
							</div>
							<?php //echo render_input('recipeName','Production Name','','',$attr); ?>
							
							<div class="col-md-2">
								<input type="hidden" name="batchqtyChg" id="batchqtyChg" value="0">
								<div class="form-group" app-field-wrapper = "batch_qty">
									<label class="control-label" for="batch_qty"><?php echo _l('Batch Qty'); ?></label>
									<input type="text" value="<?php echo $production->batch_qty;?>" <?= $is_batchedit;?> name="batch_qty" id="batch_qty" class="form-control" >
								</div>
								
							</div>
							<div class="col-md-2">
								<label class="control-label" for=""><?php echo _l('Finished Good Qty'); ?></label>
								<input type="text" value="<?php echo $production->Finish_good_qty;?>" name="qty_product" id="qty_product" readonly class="form-control">
							</div>
						</div>	
						
						<div class="row" style="margin-top:10px;">
							
							
							<div class="col-md-2">
								<label class="control-label" for=""><?php echo _l('Measured In'); ?></label>
								<input type="text" value="<?php echo $production->finish_good_unit;?>" name="unit" readonly class="form-control">
								<input type="hidden" name="unit_new" id="unit_new" value="<?php echo $production->finish_good_unit;?>">
							</div>	
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="env_temp">
									<label for="env_temp" class="control-label">Env. Temp.</label>
									<input type="text" name="env_temp" id="env_temp" class="form-control" value="<?php echo $production->env_temp;?>" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="env_humidity">
									<label for="env_humidity" class="control-label">Env. Humidity</label>
									<input type="text" name="env_humidity" id="env_humidity" class="form-control" value="<?php echo $production->env_humidity;?>" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="water_temp">
									<label for="water_temp" class="control-label">Water Temp</label>
									<input type="text" name="water_temp" id="water_temp" class="form-control" value="<?php echo $production->water_temp;?>" >
								</div>
							</div>
							<div class="col-md-2">
								<label class="control-label" for=""><?php echo _l('Required Time')."(min)"; ?></label>
								<input type="text" value="<?php echo $production->required_time;?>" name="req_time"  class="form-control">
							</div>
							
							
							
							
							<?php 
								//if($production->production_status == "pending" || $production->production_status == "cancel"){
								$value = $production->production_status;
								if($value =="pending"){
									$GodownStatus = '';
									}else{
									$GodownStatus = 'disabled';
								}
							?>
							<div class="col-md-2">
								<input type="hidden" name="OldStatus" id="OldStatus" value="<?php echo $value; ?>">
								<div class="form-group" app-field-wrapper="status">
									<label for="status" class="control-label"><?php echo _l('Order Status'); ?></label>
									<select name="status" id="status" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
										
										<?php
											if($value == "pending"){
											?>
											<option value="pending" <?php if($value == "pending"){ echo "selected";}?>>Pending</option>
											<option value="In-Progress" <?php if($value == "In-Progress"){ echo "selected";}?>>In-Progress</option>
											<option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>Cancel</option>
										<?php } ?>
										<?php
											if($value == "In-Progress"){
											?>   
											<option value="In-Progress" <?php if($value == "In-Progress"){ echo "selected";}?>>In-Progress</option>
										<?php } ?>
										
										<?php
											if($value == "cancel"){
											?>
											<option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>Cancel</option>
										<?php } ?>
										<?php
											if($value == "Completed"){
											?>
											<option value="Completed" <?php if($value == "Completed"){ echo "selected";}?>>Completed</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php
								/*}else */if($production->production_status == "In-Progress" || $production->production_status == "Completed"){
									$value = $production->production_status;
								?>
								
								<!--<div class="col-md-2">
									<label class="control-label" for=""><?php echo _l('Final Finished Good Outcome'); ?></label>
									<?php //if($production->production_status == "Completed"){ echo "readonly"; }?>
									<input type="text"  name="finish_outcome" class="form-control"  value="<?php echo $production->Finish_good_qty_new;?>" readonly onkeypress="return isNumber(event)">
									<input type="hidden" name="status_hidden" name="status_hidden" value="<?php echo $production->production_status; ?>" >
								</div> -->
								<?php
								}
							?>
							<input type="hidden" name="time_start_work" class="form-control" id="time_start_work" value="<?php echo $production->p_start_time;?>">
							<input type="hidden" name="time_end_work" class="form-control" id="time_end_work" value="<?php echo $production->p_end_time;?>">
							
							
							
							
							<?php if($production->manager_name){
								//$staff_details = get_staff_detail($production->manager_name);
								$opt = 1;
							?>
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="operator_name">
									<label for="operator_name" class="control-label">Production Manager Name</label>
									<select name="operator_name" id="operator_name" data-live-search="true" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
										<option value=""></option>
										<?php
											foreach ($manager as $key => $value) {
											?>
											<option value="<?php echo $value["AccountID"];?>" <?php if($value["AccountID"] == $production->manager_name){ echo "selected"; }?>><?php echo $value['firstname']." ".$value['lastname']?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<?php }elseif($production->contractor_name){
								//$con_details = get_con_detail($production->contractor_name);
								$opt = 2;
							?>
							<div class="col-md-4">
								
								<?php echo render_select('operator_name',$contractor,array('AccountID','company'),'Contractor Name',$production->contractor_name); ?>
							</div>
							<?php } ?>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="shift">
									<label for="shift" class="control-label">Shift Type</label>
									<select name="shift" id="shift" data-live-search="true" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
										<option value=""></option>
										<option <?php if("Day" == $production->shift){ echo "selected"; }?> value="Day">Day</option>
										<option <?php if("Night" == $production->shift){ echo "selected"; }?> value="Night">Night</option>
										
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<?php
									$GodownID = $production->GodownID;
								?>
								<div class="form-group" app-field-wrapper="GodownID">
									
									<small class="req text-danger">* </small>
									<label for="GodownID1" class="form-label">GodownID</label> 
									<select name="GodownID1" id="GodownID1" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true" <?php echo $GodownStatus;?>>
										<option value="">Non Selected</option>
										<?php
											foreach ($GodownData as $key => $value) {
											?>
											<option value="<?php echo $value['AccountID'];?>" <?php if($GodownID == $value['AccountID']){ echo 'selected';}?>><?php echo $value['AccountName'];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<input type="hidden" name="GodownID" id="GodownID" value="<?php echo $GodownID;?>">
							<input type="hidden" name="opttype" id="opttype" value="<?php echo $opt;?>">	
						</div>
						
						<div class="row" style="margin-top:10px;">	
							
							<div class="col-md-3">
								<small class="req text-danger">* </small>
								<label class="control-label" for="comments"><?php echo _l('Comments'); ?></label>
								<!--<input type="text" value="<?php echo $production->comment;?>" name="comments" readonly class="form-control">-->
								<textarea id="comments" name="comments" rows="4" cols="50" class="form-control" ><?php echo $production->comment;?></textarea>
							</div>
							<div class="col-md-3">
								<small class="req text-danger">* </small>
								<label class="control-label" for="">Remark if any</label>
								<textarea id="remark" name="remark" rows="4" cols="50" class="form-control"><?php  echo $production->remark; ?></textarea>
							</div>
						</div>
						
						
						
						<input type="hidden" value="1" name="countof_record" id="countof_record">
						<?php
							if(!empty($BakingList))
							{
								$CountRecord = count($BakingList);
								}else{
								$CountRecord = 1;
							}
							
							if(!empty($packingList))
							{
								$CountRecord2 = count($packingList);
								}else{
								$CountRecord2 = 1;
							}
						?>
						<input type="hidden" value="<?= $CountRecord?>" name="countof_record_baking" id="countof_record_baking">
						<input type="hidden" value="<?= $CountRecord2?>" name="countof_record_packing" id="countof_record_packing">
						<input type="hidden" value="1" name="countof_record1" id="countof_record1">
						<input type="hidden" value="2" name="sub_group" id="sub_group">
						<div class="row">
							<div class="col-md-6">
								<div class="_buttons">
									<h4>Recipe Raw Materials</h4>
								</div>
								<div class="table-wrapper" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
									<table class="table table-striped table-bordered" id="data_table_ex" width="100%">
										<thead>
											<tr>
												<th align="center">ItemID</th>
												<th align="center">Item Name</th>
												<th align="center">Std. Qty</th>
												<th align="center">Req Qty</th>
												<th align="center">Rtn Qty</th>
												<th align="center">Extra Qty</th>
												<th align="center">Acctual qty</th>
												<th align="center">StockQty</th>
												<th align="center">Needed Qty</th>
												<th align="center">MesuredIn</th>
											</tr>
										</thead>
										<tbody id="data_table_exBody">
											
											<?php
												$StockMatch = 0;
												foreach ($production->items as $key => $value) {
													if($value['MainGrpID'] == '2'){
														$PQty = 0;
														$PRQty = 0;
														$IQty = 0;
														$PRDQty = 0;
														$SQty = 0;
														$SRTQty = 0;
														$AQty = 0;
														$OQty = 0;
														$GOQty = 0;
														$GIQty = 0;
														
														foreach ($OQtyItems as $qty) {
															if($qty['ItemID']==$value['item_id']){
																$OQty = $qty['OQty'];
															}
														}
														foreach ($ItemStocks as $stock) {
															if($stock['ItemID']==$value['item_id']){
																//$OQty = $stock['OQty'];
																if($stock['TType'] == 'P'  && $stock['TType2'] == 'Purchase'){
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
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock distribution'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
																	$GIQty = $stock['BilledQty'];
																	}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
																	$GOQty = $stock['BilledQty'];
																}
															}
														}
														$stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty + $GIQty - $GOQty;
													?>
													<tr>
														<td align="center"><?php echo $value['item_id'];?></td>
														<td><?php echo $value['item_name'];?></td>
														<?php
															if(strtoupper($value['unit']) == "KG" || strtoupper($value['unit']) == "LTR"){
																$PRDStd = number_format($value['req_qty'],3);
																$PRDReq = number_format($value['production_req_qty'],3);
																$PRDRTN = number_format($value['return_req_qty'],3);
																$PRDEXT = number_format($value['ExtraQty'],3);
																$PRDAct = number_format($value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'],3);
																}else if(strtoupper($value['unit']) == "PCS"){
																$PRDStd = (int) $value['req_qty'];
																$PRDReq = (int) $value['production_req_qty'];
																$PRDRTN = (int) $value['return_req_qty'];
																$PRDEXT = (int) $value['ExtraQty'];
																$PRDAct = (int) $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];
															}
														?>
														<td align="right"><?php echo $PRDStd;?></td>
														<td align="right"><?php echo $PRDReq;?></td>
														<td align="right"><?php echo $PRDRTN;?></td>
														<td align="right"><?php echo $PRDEXT; ?></td>
														<td align="right"><?php echo $PRDAct;?></td>
														<?php
															$style = '';
															if($PRDAct >$stockQty){
																$StockMatch++;
																if($production->production_status == "In-Progress" || $production->production_status == "pending"){
																	$style = 'style = "color:red;border-color:red"';
																}
															}
															$NeededQty = '';
															if($stockQty < 0){
																$NeededQty = number_format($PRDAct - $stockQty,2);
															}
														?>
														<td align="right" <?php echo $style; ?>><?php echo number_format($stockQty,2);?></td>
														<td align="right" ><?php echo $NeededQty;?></td>
														<td align="center"><?php echo $value['unit'];?></td>
													</tr>
													<?php
													}
												}
											?>
										</tbody>
									</table>
								</div>
								<br/>
								<div class="_buttons">
									<h4>Packaging Materials</h4>
								</div>
								<div class="table-wrapper" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
									<table class="table table-striped table-bordered" id="data_table_ex" width="100%">
										<thead>
											<tr>
												<th align="center">ItemID</th>
												<th align="center">Item Name</th>
												<th align="center">Std. Qty</th>
												<th align="center">Req Qty</th>
												<th align="center">Rtn Qty</th>
												<th align="center">Extra Qty</th>
												<th align="center">Acctual qty</th>
												<th align="center">StockQty</th>
												<th align="center">MesuredIn</th>
											</tr>
										</thead>
										<tbody id="data_table_exBody2">
											
											<?php
												$StockMatch = 0;
												foreach ($production->items as $key => $value) {
													if($value['MainGrpID'] == '3'){
														$PQty = 0;
														$PRQty = 0;
														$IQty = 0;
														$PRDQty = 0;
														$SQty = 0;
														$SRTQty = 0;
														$AQty = 0;
														$OQty = 0;
														$GOQty = 0;
														$GIQty = 0;
														
														foreach ($OQtyItems as $qty) {
															if($qty['ItemID']==$value['item_id']){
																$OQty = $qty['OQty'];
															}
														}
														foreach ($ItemStocks as $stock) {
															if($stock['ItemID']==$value['item_id']){
																//$OQty = $stock['OQty'];
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
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock distribution'){
																	$AQty += $stock['BilledQty'];
																	}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
																	$GIQty = $stock['BilledQty'];
																	}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
																	$GOQty = $stock['BilledQty'];
																}
															}
														}
														$stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty + $GIQty - $GOQty;
													?>
													<tr>
														<td align="center"><?php echo $value['item_id'];?></td>
														<td><?php echo $value['item_name'];?></td>
														<?php
															if(strtoupper($value['unit']) == "KG" || strtoupper($value['unit']) == "LTR"){
																$PRDStd = number_format($value['req_qty'],3);
																$PRDReq = number_format($value['production_req_qty'],3);
																$PRDRTN = number_format($value['return_req_qty'],3);
																$PRDEXT = number_format($value['ExtraQty'],3);
																$PRDAct = number_format($value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'],3);
																}else if(strtoupper($value['unit']) == "PCS"){
																$PRDStd = (int) $value['req_qty'];
																$PRDReq = (int) $value['production_req_qty'];
																$PRDRTN = (int) $value['return_req_qty'];
																$PRDEXT = (int) $value['ExtraQty'];
																$PRDAct = (int) $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];
															}
														?>
														<td align="right"><?php echo $PRDStd;?></td>
														<td align="right"><?php echo $PRDReq;?></td>
														<td align="right"><?php echo $PRDRTN;?></td>
														<td align="right"><?php echo $PRDEXT; ?></td>
														<td align="right"><?php echo $PRDAct;?></td>
														<?php
															$style = '';
															if($PRDAct >$stockQty){
																$StockMatch++;
																if($production->production_status == "In-Progress" || $production->production_status == "pending"){
																	$style = 'style = "color:red;border-color:red"';
																}
															}
														?>
														<td align="right" <?php echo $style; ?>><?php echo number_format($stockQty,2);?></td>
														<td align="center"><?php echo $value['unit'];?></td>
													</tr>
													<?php
													}
												}
												
											?>
										</tbody>
									</table>
								</div>	
							</div>	
							<?php 
								/*if($production->production_status == "Completed" || $production->production_status == "cancel"){*/
								if($production->production_status == "cancel"){
									
									}else{
								?>
								
								<div class="col-md-6">
								</div>
								<div class="col-md-6">
									<div class="_buttons">
										<h4>Add Extra Material</h4>
									</div>
									<table class="table table-striped table-bordered" id="data_table" width="100%">
										<thead>
											<tr> 
												<th>ItemCode</th>
												<th>ItemName</th>
												<th>Req.Qty</th>
												<th>Mes.In</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="tbody">
											<tr id="R1">
												<input type="hidden" class="form-control" name="PRDR_ItemID_list" id="PRDR_ItemID_list" value="">
												<td style="width:10%"><input type="text" name="item_id" id="item_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
												
												<td style="width:40%" ><input type="text" name="item_name" id="item_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="item_name" id="item_name" value=""><input type="hidden" name="ItemStocks" id="ItemStocks" value=""></td>
												
												<td style="width:7%" ><input type="text" name="req_qty" id="req_qty"  value="" style="height: 30px;width: 100%;" /><input type="hidden" name="req_qty" id="req_qty" value=""></td>
												
												<td style="width:7%" ><input type="text" name="unit" id="unit" value="" style="height: 30px;width: 100%;" readonly/><input type="hidden" name="unit" id="unit" value=""></td>
												
												<td style="width:5%"></td>
												<!--<button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
											</tr>
										</tbody>
									</table>
									
									<div class="_buttons">
										<h4>Return Extra Material</h4>
									</div>
									<table class="table table-striped table-bordered" id="data_table1" width="100%">
										<thead>
											<tr> 
												<th>ItemCode</th>
												<th>ItemName</th>
												<th>Req.Qty</th>
												<th>PRD Req.Qty</th>
												<th>Rtn Req.Qty</th>
												<th>Mes.In</th>
												<th>Action</th>
											</tr>
										</thead> 
										<tbody id="tbody1">
											<input type="hidden" class="form-control" name="PRD_ItemID_list" id="PRD_ItemID_list" value="">
											<tr id="R2">
												
												<td style="width:10%"><input type="text" name="pro_item_id" id="pro_item_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
												
												<td style="width:30%" ><input type="text" name="pro_item_name" id="pro_item_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="pro_item_name" id="pro_item_name" value=""></td>
												
												<td style="width:7%" ><input type="text" name="pro_req_qty" id="pro_req_qty" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="pro_req_qty" id="pro_req_qty" value=""></td>
												
												<td style="width:7%" ><input type="text" name="production_req_qty" id="production_req_qty" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="production_req_qty_h" id="production_req_qty_h" value=""></td>
												
												<td style="width:7%" ><input type="text" name="return_pro_req_qty" id="return_pro_req_qty" value="" style="height: 30px;width: 100%;"  /><input type="hidden" name="return_pro_req_qty" id="return_pro_req_qty" value=""></td>
												
												<td style="width:7%" ><input type="text" name="pro_unit" id="pro_unit" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="pro_unit" id="pro_unit" value=""></td>
												
												<td style="width:5%"></td>
												<!--<button type="button" name="addBtn1" id="add1" class="btn btn-xs btn-succes add1" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
											</tr>
										</tbody>
									</table>
									
									<div class="_buttons">
										<h4>Baking Qty</h4>
									</div>
									<table class="table table-striped table-bordered" id="data_table_baking" width="100%">
										<thead>
											<tr> 
												<th>ItemCode</th>
												<th>ItemName</th>
												<th>Req.Qty</th>
												<th>Mes.In</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="tbody_baking">
											<!--<tr id="R1">
												<td style="width:10%"><input type="text" name="item_id_baking" id="item_id_baking" style="width: 100%;border-radius: 2px;height: 30px;"></td>
												
												<td style="width:40%" ><input type="text" name="item_name_baking" id="item_name_baking" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="item_name_baking" id="item_name_baking" value=""><input type="hidden" name="ItemStocks_baking" id="ItemStocks_baking" value=""></td>
												
												<td style="width:7%" ><input type="text" name="req_qty_baking" id="req_qty_baking"  value="" style="height: 30px;width: 100%;" /><input type="hidden" name="req_qty_baking" id="req_qty_baking" value=""></td>
												
												<td style="width:7%" ><input type="text" name="unit_baking" id="unit_baking" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="unit_baking" id="unit_baking" value=""></td>
												
												<td style="width:5%"></td>
												
											</tr>-->
											<?php
												$i = 1;
												foreach($BakingList as $key => $value){
												?>
												<tr id="row<?= $i?>">
													<input type="hidden" class="editid_baking" name="editid_baking<?= $i?>" id="editid_baking<?= $i?>" style="width: 100%;border-radius: 2px;height: 30px;" value="<?= $value['id']?>">
													
													<td id="item_id_baking<?= $i?>" style="width:10%"><?= $value['ItemID']?><input type="hidden" class="ItemIDBaking" name="item_id_baking<?= $i?>" id="item_id_baking<?= $i?>" style="width: 100%;border-radius: 2px;height: 30px;" value="<?= $value['ItemID']?>"></td>
													
													<td style="width:40%" ><?= $value['description']?><input type="hidden" name="item_name_baking<?= $i?>" id="item_name_baking<?= $i?>" class="ItemNameBaking" value="<?= $value['description']?>"></td>
													
													<td style="width:7%" ><input type="text" name="req_qty_baking<?= $i?>" id="req_qty_baking<?= $i?>" readonly class="ItemQtyBaking"  value="<?= $value['Qty']?>" style="height: 30px;width: 100%;" /></td>
													
													<td style="width:7%" ><?= $value['unit']?><input type="hidden" name="unit_baking<?= $i?>" id="unit_baking<?= $i?>" class="ItemUnitBaking" value="<?= $value['unit']?>" style="height: 30px;width: 100%;" readonly /></td>
													
													<td style="width:5%"></td>
													
												</tr>
												<?php
													$i++;
												}
											?>
										</tbody>
										
									</table>
									<!--<button type="button" name="addBtn_baking" id="addBtn_baking" class="btn btn-xs btn-primary add_baking">Save Baking Qty</button>-->
									
									<div class="_buttons">
										<h4>Packing Qty</h4>
									</div>
									<table class="table table-striped table-bordered" id="data_table_packing" width="100%">
										<thead>
											<tr> 
												<th>ItemCode</th>
												<th>ItemName</th>
												<th>Req.Qty</th>
												<th>Mes.In</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="tbody_packing">
											<!--<tr id="R1">
												<td style="width:10%"><input type="text" name="item_id_packing" id="item_id_packing" style="width: 100%;border-radius: 2px;height: 30px;"></td>
												
												<td style="width:40%" ><input type="text" name="item_name_packing" id="item_name_packing" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="item_name_packing" id="item_name_packing" value=""><input type="hidden" name="ItemStocks_packing" id="ItemStocks_packing" value=""></td>
												
												<td style="width:7%" ><input type="text" name="req_qty_packing" id="req_qty_packing"  value="" style="height: 30px;width: 100%;" /><input type="hidden" name="req_qty_packing" id="req_qty_packing" value=""></td>
												
												<td style="width:7%" ><input type="text" name="unit_packing" id="unit_packing" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="unit_packing" id="unit_packing" value=""></td>
												
												<td style="width:5%"></td>
												
											</tr>-->
											<?php
												$i = 1;
												foreach($PackingList as $key => $value){
												?>
												<tr id="row<?= $i?>">
													<input type="hidden" class="editid_packing" name="editid_packing<?= $i?>" id="editid_packing<?= $i?>" style="width: 100%;border-radius: 2px;height: 30px;" value="<?= $value['id']?>">
													
													<td id="item_id_packing<?= $i?>" style="width:10%"><?= $value['ItemID']?><input type="hidden" class="ItemIDPacking" name="item_id_packing<?= $i?>" id="item_id_packing<?= $i?>" style="width: 100%;border-radius: 2px;height: 30px;" value="<?= $value['ItemID']?>"></td>
													
													<td style="width:40%" ><?= $value['description']?><input type="hidden" name="item_name_packing<?= $i?>" id="item_name_packing<?= $i?>" class="ItemNamePacking" value="<?= $value['description']?>"></td>
													
													<td style="width:7%" ><input type="text" name="req_qty_packing<?= $i?>" id="req_qty_packing<?= $i?>" readonly class="ItemQtyPacking"  value="<?= $value['Qty']?>" style="height: 30px;width: 100%;" /></td>
													
													<td style="width:7%" ><?= $value['unit']?><input type="hidden" name="unit_packing<?= $i?>" id="unit_packing<?= $i?>" class="ItemUnitPacking" value="<?= $value['unit']?>" style="height: 30px;width: 100%;" readonly /></td>
													
													<td style="width:5%"></td>
													
												</tr>
												<?php
													$i++;
												}
											?>
										</tbody>
										
									</table>
									<!--<button type="button" name="addBtn_packing" id="addBtn_packing" class="btn btn-xs btn-primary add_packing">Save Packing Qty</button>-->
								</div>
							<?php } ?>
						</div>
						
						<div class="row">
							<br>
							<div class="col-md-1" >
								<?php 
									if($production->production_status=="Completed"){
										
										}else{
										if(has_permission_new('production', '', 'edit')){
											
											$fy = $this->session->userdata('finacial_year');
											$fy_new  = $fy + 1;
											$first_date = '20'.$fy.'-04-01';
											$lastdate_date = '20'.$fy_new.'-03-31';
											$curr_date = date('Y-m-d');
											$lgstaff = $this->session->userdata('staff_user_id');
											$PRDdate = substr($production->TransDate,0,10);
											
											$PRDdate_new    = new DateTime($PRDdate);
											$first_date_yr = new DateTime($first_date);
											$last_date_yr = new DateTime($lastdate_date);
											$curr_date_new = new DateTime($curr_date);
											
											/*$sql = 'SELECT * FROM tblproduction WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblproduction.pro_order_id DESC ';
												$result_data = $this->db->query($sql)->row();
											$lastdate = substr($result_data->TransDate,0,10);*/
											
											if($curr_date_new > $last_date_yr){
												$lastdate = $lastdate_date;
												}else{
												$lastdate = date('Y-m-d');
											}
											
											$this->db->select('*');
											$this->db->where('plant_id', $selected_company);
											$this->db->where('year', $fy);
											$this->db->where('staff_id', $lgstaff);
											$this->db->LIKE('feature', "production");
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
												
												if ($PRDdate_new < $tillDate_new) {
													$return = 'disabled';
													}else{
													$return = '';
												}
											}
										?>
										<?php if($return == "disabled"){
										?>
										<a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
										<?php
											}else{
											if($production->production_status == "cancel"){
												
												}else{
											?>
											<button type="submit" class="btn btn-info" onclick="this.disabled=true; this.innerText='Processing...'; this.form.submit();" id="updateBtn">Update</button> 
										<?php }} ?> 
										<?php
										}
									}
								?>
								
							</div>
							<div class="col-md-1" >
								
								<!--<a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>-->
								<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
							</div>
						</div>
						<?php echo form_close(); ?>
						<!-- Hidden table for print-->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="transfer-modal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Production List</h4>
			</div>
			<div class="modal-body">
				
				<div class="row">
					<?php $current_date = date('d/m/Y'); 
						$from_date = '01/'.date('m').'/'.date('Y');
					?>
					<div class="col-md-3">
						<?php
							echo render_date_input('from_date','From',$from_date);
						?>
					</div>
					<div class="col-md-3">
						<?php
							echo render_date_input('to_date','To',$current_date);
						?>
					</div>
					<div class="col-md-2">
						<div class="form-group" app-field-wrapper="status_list">
							<label for="status_list" class="control-label"><?php echo _l('Order Status'); ?></label>
							<select name="status_list" id="status_list" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
								<option value="pending">Pending</option>
								<option value="In-Progress">In-Progress</option>
								<option value="Completed">Completed</option>
							</select>
						</div>
					</div>
					<div class="col-md-1">
						<br>
						<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
					</div>
					<div class="col-md-3">
						<br>
						<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
					</div>
					<div class="col-md-12">
						
						<div class="table_production_report">
							
							<table class="tree table table-striped table-bordered table_production_report" id="table_production_report" width="100%">
								
								<thead>
									<tr style="display:none;">
										<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
									</tr>
									<tr>
										<th style="text-align:left;">ProductionID</th>
										<th style="text-align:left;">PRDDate</th>
										<th style="text-align:left;">RecipeName</th>
										<th style="text-align:left;">BatchQty</th>
										<th style="text-align:left;">FGQty</th>
										<th style="text-align:left;">ReqTM</th>
										<th style="text-align:left;">PRDTM</th>
										<th style="text-align:left;">Man/Con Name</th>
										<th style="text-align:left;">Status</th>
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
<style>
    .table_production_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table_production_report thead th { position: sticky; top: 0; z-index: 1; }
	.table_production_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table_production_report table  { border-collapse: collapse; width: 100%; }
	.table_production_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.table_production_report th     { background: #50607b;color: #fff !important; }
	
	
	#table_production_report tr:hover {
    background-color: #ccc;
	}
	
	#table_production_report td:hover {
    cursor: pointer;
	}
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	th     { background: #50607b;color: #fff !important; }
	
</style>
<?php init_tail(); ?>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		$("#productionedit_form").validate({
			rules: {
				batch_qty: "required",
				/*start_date: {
					remote: {
					url: site_url + "admin/misc/CheckPRDDate",
					type: 'post',
					data: {
					Prd_date: function() {
					return $('input[name="start_date"]').val();
					},
					PRDID: function() {
					return $('input[name="pid"]').val();
					}
					}
					}
				},*/
			},
			
		})
		
		function load_data(from_date,to_date,status_list)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>production/load_data_for_production",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date,status_list:status_list},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('.table_production_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('.table_production_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					var html = '';
					
					for(var count = 0; count < data.length; count++)
					{
						
						var url = "'<?php echo admin_url() ?>production/production_order/"+data[count].pro_order_id+"'";
						html += '<tr onclick="location.href='+url+'">';
						html += '<td style="text-align:center;">'+data[count].pro_order_id+'</td>';
						
						var date = data[count].TransDate.substring(0, 10);
						var date_new = date.split("-").reverse().join("/");
						
						html += '<td  style="text-align:center;">'+date_new+'</td>';
						html += '<td style="text-align:center;">'+data[count].recipeID+'</td>';
						html += '<td style="text-align:center;">'+data[count].batch_qty+'</td>';
						html += '<td style="text-align:center;">'+data[count].Finish_good_qty_new+'</td>';
						html += '<td style="text-align:center;">'+data[count].required_time+'</td>';
						html += '<td style="text-align:center;">'+data[count].production_time+'</td>';
						if(data[count].contractor_name == null){
							if(data[count].lastname == null){
								var AccoutName = data[count].firstname;
								}else{
								var AccoutName = data[count].firstname + data[count].lastname;
							}
							
							}else{
							var AccoutName = data[count].conName;
						}
						html += '<td  style="text-align:left;">'+ AccoutName +'</td>';
						html += '<td  style="text-align:center;">'+data[count].production_status+'</td>';
						html += '</tr>';
					}
					$('.table_production_report tbody').html(html);
					
				}
			});
		}
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var status_list = $("#status_list").val();
			load_data(from_date,to_date,status_list);
			
		});
		
	});
</script>
<script>
    function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_production_report");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[2];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else {
					tr[i].style.display = "none";
				}
			}       
		}
	}
</script>
<script>
    $('.add-new-transfer').on('click', function(){
		$('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
		$('#transfer-modal').modal('show');
	});
</script>


<script type='text/javascript'>
	
	$(document).ready(function () {
		var rowIdx = 1;
		
		
		
		$('#tbody').on('click', '.remove', function () {
			
			// Getting all the rows next to the 
			// row containing the clicked button
			var child = $(this).closest('tr').nextAll();
			
			// Iterating across all the rows 
			// obtained to change the index
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
			var item_name =$(this).parents("tr").find('input[name="item_name'+no+'"]').val();
			var item_id =$(this).parents("tr").find('input[name="item_id'+no+'"]').val();
			// alert(no);
			
			
			// Removing the current row.
			$(this).closest('tr').remove();
			
			// Decreasing the total number of rows by 1.
			rowIdx--;
			var countof_record = $("#countof_record").val();
			var new_cont = countof_record -1;
			$("#countof_record").val(new_cont);
			
			var new_rec = $('#new_record').val();
			$new_item_name = ','+item_name;
			new_rec = new_rec.replace($new_item_name, " ");
			$('#new_record').val(new_rec);
			
			var PRDR_ItemID_list = $('#PRDR_ItemID_list').val();
			var item_id = ','+item_id;
			var PRDR = PRDR_ItemID_list.replace(item_id, "");
			$('#PRDR_ItemID_list').val(PRDR);
			
		});
		
		
		// new code 
		
		$('#data_table').on('click', '.add', function () {
			var req_qty = $("#req_qty").val();
			var item_id = $("#item_id").val();
			var item_name = $("#item_name").val();
			//alert(item_id);
			if(item_id == "" || item_id == null ){
				alert("Select Item ID.");
				focus(item_id);
				}else if(item_name == "" || item_name == null ){
				alert("Select Item Name.");
				focus(item_name);
				}else if(req_qty == "" || req_qty == null ){
				alert("Add Require Quantity.");
				focus(req_qty);
				}else{
				add_row();
			}  
		})
		
		$('#req_qty').on('blur', function () {
			
			var req_qty = $("#req_qty").val();
			var item_id = $("#item_id").val();
			var item_name = $("#item_name").val();
			var ItemStock = $("#ItemStocks").val();
			var PRDStatus = $("#OldStatus").val();
			var PlantID = $("#PlantID").val();
			var Val = $(this).val();
			//alert(item_id);
			if(item_id == "" || item_id == null ){
				alert("Select Item ID.");
				focus(item_id);
				}else if(item_name == "" || item_name == null ){
				alert("Select Item Name.");
				focus(item_name);
				}else if(req_qty == "" || req_qty == null ){
				alert("Add Require Quantity.");
				//focus(req_qty);
				focus(item_name);
				}else if(parseFloat(ItemStock) < parseFloat(Val) && PRDStatus == "In-Progress"){
				// alert('Item Stock not available...'+ItemStock);
				add_row();
				}else{
				add_row();
			}  
		});
		
		
		function add_row()
		{  
			var item_id =document.getElementById("item_id").value;
			var item_name =document.getElementById("item_name").value;
			var req_qty =document.getElementById("req_qty").value;
			var unit =document.getElementById("unit").value;
			
			var countof_record = document.getElementById("countof_record").value;
			
			var table=document.getElementById("data_table");
			var table_len=(table.rows.length)-1;
			var html = '';
			html += "<tr id='row"+table_len+"'>";
			html += "<td id='item_id"+table_len+"'>"+item_id+" <input type='hidden' name='item_id"+table_len+"' value='"+item_id+"'></td>";
			html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name"+table_len+"' value='"+item_name+"'></td>";
			html += "<td id='req_qty"+table_len+"'>"+req_qty+" <input type='hidden' name='req_qty"+table_len+"' value='"+req_qty+"'></td>";
			html += "<td id='unit"+table_len+"'>"+unit+" <input type='hidden' name='unit"+table_len+"' value='"+unit+"'></td>";
			
			//html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
			html += '<td><button type="button" name="edit" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum" id="rownum" value="'+countof_record+'"></td>';
			html += '</tr>';
			var row = table.insertRow(table_len).outerHTML=html;
			
			var temp1 = parseFloat(countof_record) + parseFloat(1);
			
			document.getElementById("countof_record").value=temp1;
			
			//  if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
			//  }  else{
            var new_rec = $('#new_record').val();
            
			new_rec = new_rec +","+ item_id
            
			$('#new_record').val(new_rec);
			//alert(new_rec);
			//  }
			
			$(this).parents("tr").find(".edit_row").show();  
			$(this).parents("tr").find(".btn-cancel").remove();  
			$(this).parents("tr").find(".btn-update").remove();
			
			
			document.getElementById("item_id").value="";
			document.getElementById("item_name").value="";
			document.getElementById("req_qty").value="";
			document.getElementById("unit").value="";
			
			document.getElementById("item_name").innerHTML="";
			document.getElementById("req_qty").innerHTML="";
			document.getElementById("unit").innerHTML="";
			
			document.getElementById("item_id").focus(); 
		}
		
		
	}); 
	
</script>
<script type='text/javascript'>
    $(document).ready(function(){
		
		// Initialize 
		$( "#item_code" ).autocomplete({
			source: function( request, response ) {
				// Fetch data
				$.ajax({
					url: "<?=base_url()?>admin/production/itemlist_using_itemcode",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term
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
                
                $('#item_code').val(ui.item.label);
				$('#item_desc').val(ui.item.label);
				$('#unit1').val(ui.item.units); // save selected id to input
			}
			
		});
	});
	
</script>

<script type='text/javascript'>
	
	$(document).ready(function(){
		
		// Initialize 
		//By Item Code
		$( "#item_id" ).autocomplete({
			source: function( request, response ) {
				var proId = $('#pid').val();
				var maingroup = 2;
				// Fetch data
				$.ajax({
					url: "<?=base_url()?>admin/production/itemlist_subgroup",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term, 'ProductionId' : proId,maingroup:maingroup
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
				// Set selection
				var item_string = $("#item_string").val();
				let item_array = item_string.split(",");
				if(item_array.includes(ui.item.value)){
					
					$('#item_id').val(ui.item.value); // display the selected text
					$('#item_name').val(ui.item.label); // save selected id to input
					$('#unit').val(ui.item.unit); // save selected id to input
					$('#item_name').focus();
					return false;
					}else{
					alert("item not prasent in selected recipe");
				}
				
			}
		});
		// blur from ItemID 
		$('#item_id').on('blur',function(){
			
			var ItemID = $(this).val();
			if(ItemID == "" || ItemID == null){
				
				}else{
				var PRDR_ItemID_list = $("#PRDR_ItemID_list").val();
				let PRDR_ItemID_list_array = PRDR_ItemID_list.split(",");
				if(PRDR_ItemID_list_array.includes(ItemID.toUpperCase())){
					alert("item already added");
                    $('#item_id').val('');
                    $('#item_name').val('');
                    $('#unit').val("");
                    $('#item_id').focus();
					}else{
					var proId = $('#pid').val();
					$.ajax({
						url:"<?php echo admin_url(); ?>production/get_item_details",
						dataType:"JSON",
						method:"POST",
						cache: false,
						data:{ItemID:ItemID,proId:proId},
						
						success:function(data){
							if(empty(data)){
								alert('Item not found...');
								$('#item_id').val('');
								$('#item_name').val('');
								$('#unit').val("");
								$('#item_id').focus();
								}else{
								$("#PRDR_ItemID_list").val(PRDR_ItemID_list+","+data.item_id);
								$('#item_id').val(data.item_id);
								$('#item_name').val(data.item_name);
								$('#unit').val(data.unit);
								$('#ItemStocks').val(parseFloat(data.ItemStocks).toFixed(2));
								$('#req_qty').focus();
							}
							
						}
					});
				}
				
			}
			
		})
		
		// blur from ItemID 
		$('#item_id_baking').on('blur',function(){
			
			var ItemID = $(this).val();
			if(ItemID == "" || ItemID == null){
				
				}else{
				
                var proId = $('#pid').val();
                $.ajax({
					url:"<?php echo admin_url(); ?>production/get_item_details_baking",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{ItemID:ItemID,proId:proId},
					
					success:function(data){
						if(empty(data)){
							alert('Item not found...');
							$('#item_id_baking').val('');
							$('#item_name_baking').val('');
							$('#unit_baking').val("");
							$('#item_id_baking').focus();
							}else{
							$('#item_id_baking').val(data.item_code);
							$('#item_name_baking').val(data.description);
							$('#unit_baking').val(data.unit);
							$('#ItemStocks_baking').val(parseFloat(data.ItemStocks).toFixed(2));
							$('#req_qty_baking').focus();
						}
						
					}
				});
				
			}
			
		})
		$( "#item_id_baking" ).autocomplete({
			source: function( request, response ) {
				var proId = $('#pid').val();
				var recipeID = $('#recipeID1').val();
				
				// Fetch data
				$.ajax({
					url: "<?=base_url()?>admin/production/itemlist_subgroup_baking",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term, 'ProductionId' : proId, recipeID : recipeID
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
				// Set selection
				var item_string = $("#ItemID").val();
				let item_array = item_string.split(",");
				if(item_array.includes(ui.item.value)){
					
					$('#item_id_baking').val(ui.item.value); // display the selected text
					$('#item_name_baking').val(ui.item.label); // save selected id to input
					$('#unit_baking').val(ui.item.unit); // save selected id to input
					$('#item_name_baking').focus();
					return false;
					}else{
					alert("item not present in selected recipe");
				}
				
			}
		});
		
		
		$('#req_qty_baking').on('blur', function () {
			
			var req_qty = $("#req_qty_baking").val();
			var item_id = $("#item_id_baking").val();
			var item_name = $("#item_name_baking").val();
			var ItemStock = $("#ItemStocks_baking").val();
			var PRDStatus = $("#OldStatus").val();
			var PlantID = $("#PlantID").val();
			var Val = $(this).val();
			//alert(item_id);
			if(item_id == "" || item_id == null ){
				alert("Select Item ID.");
				focus(item_id);
				}else if(item_name == "" || item_name == null ){
				alert("Select Item Name.");
				focus(item_name);
				}else if(req_qty == "" || req_qty == null ){
				alert("Add Require Quantity.");
				//focus(req_qty);
				focus(item_name);
				}else if(parseFloat(ItemStock) < parseFloat(Val) && PRDStatus == "In-Progress"){
				// alert('Item Stock not available...'+ItemStock);
				add_row_baking();
				}else{
				add_row_baking();
			}  
		});
		
		function add_row_baking()
		{  
			var item_id =document.getElementById("item_id_baking").value;
			var item_name =document.getElementById("item_name_baking").value;
			var req_qty =document.getElementById("req_qty_baking").value;
			var unit =document.getElementById("unit_baking").value;
			
			var countof_record = document.getElementById("countof_record_baking").value;
			
			var table=document.getElementById("data_table_baking");
			var table_len=(table.rows.length)-1;
			var html = '';
			html += "<tr id='row"+table_len+"'>";
			html += "<td id='item_id_baking"+table_len+"'>"+item_id+" <input type='hidden' class='ItemIDBaking' name='item_id_baking"+table_len+"' value='"+item_id+"'></td>";
			html += "<td id='item_name_baking"+table_len+"'>"+item_name+" <input type='hidden'  class='ItemNameBaking' name='item_name_baking"+table_len+"' value='"+item_name+"'></td>";
			html += "<td id='req_qty_baking"+table_len+"'>"+req_qty+" <input type='hidden'  class='ItemQtyBaking' name='req_qty_baking"+table_len+"' value='"+req_qty+"'></td>";
			html += "<td id='unit_baking"+table_len+"'>"+unit+" <input type='hidden' class='ItemUnitBaking' name='unit_baking"+table_len+"' value='"+unit+"'></td>";
			
			//html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
			html += '<td><button type="button" name="edit_baking" id="remove_baking" class="btn btn-xs btn-danger remove_baking" value="remove_baking"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum_baking" id="rownum_baking" value="'+countof_record+'"></td>';
			html += '</tr>';
			var row = table.insertRow(table_len).outerHTML=html;
			
			var temp1 = parseFloat(countof_record) + parseFloat(1);
			
			document.getElementById("countof_record_baking").value=temp1;
			
			//  if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
			//  }  else{
            var new_rec = $('#new_record').val();
            
			new_rec = new_rec +","+ item_id
            
			$('#new_record').val(new_rec);
			//alert(new_rec);
			//  }
			
			$(this).parents("tr").find(".edit_row").show();  
			$(this).parents("tr").find(".btn-cancel").remove();  
			$(this).parents("tr").find(".btn-update").remove();
			
			
			document.getElementById("item_id_baking").value="";
			document.getElementById("item_name_baking").value="";
			document.getElementById("req_qty_baking").value="";
			document.getElementById("unit_baking").value="";
			
			document.getElementById("item_name_baking").innerHTML="";
			document.getElementById("req_qty_baking").innerHTML="";
			document.getElementById("unit_baking").innerHTML="";
			
			document.getElementById("item_id_baking").focus(); 
		}
		
		var rowIdx = 1;
		
		
		
		$('#tbody_baking').on('click', '.remove_baking', function () {
			
			// Getting all the rows next to the 
			// row containing the clicked button
			var child = $(this).closest('tr').nextAll();
			
			// Iterating across all the rows 
			// obtained to change the index
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
			var  no = $(this).parents("tr").find('input[name="rownum_baking"]').val();
			var item_name =$(this).parents("tr").find('input[name="item_name_baking'+no+'"]').val();
			var item_id =$(this).parents("tr").find('input[name="item_id_baking'+no+'"]').val();
			// alert(no);
			
			
			// Removing the current row.
			$(this).closest('tr').remove();
			
			// Decreasing the total number of rows by 1.
			rowIdx--;
			var countof_record = $("#countof_record_baking").val();
			var new_cont = countof_record -1;
			$("#countof_record_baking").val(new_cont);
			
			
		});
		
		$('#addBtn_baking').click(function() {
			var proId = $('#pid').val();
			// Initialize an array to hold the data from the table
			var bakingData = [];
			var i=1;
			// Loop through each row in the tbody
			$('#tbody_baking tr').each(function() {
				// Get the values from each input inside the row
				// var itemId = $(this).find('input[name="item_id_baking'+i+'"]').val();
				// var itemName = $(this).find('input[name="item_name_baking'+i+'"]').val();
				// var reqQty = $(this).find('input[name="req_qty_baking'+i+'"]').val();
				// var unit = $(this).find('input[name="unit_baking'+i+'"]').val();
				;
				var itemId = $(this).find('.ItemIDBaking').val();
				var itemName = $(this).find('.ItemNameBaking').val();
				var reqQty = $(this).find('.ItemQtyBaking').val();
				var unit = $(this).find('.ItemUnitBaking').val();
				var editid = $(this).find('.editid_baking').val();
				
				if (typeof editid === 'undefined' || editid === '') {
					editid = '';
				}
				
				// Create an object for this row and push to bakingData array
				var rowData = {
					edit_id: editid,
					item_id: itemId,
					item_name: itemName,
					req_qty: reqQty,
					unit: unit
				};
				
				if(itemId != '' && itemId != null){
					bakingData.push(rowData);
					i++;
				}
			});
			
			// Send the baking data via AJAX
			$.ajax({
				url: "<?=base_url()?>admin/production/savebakingdata",
				type: 'POST',
				data: JSON.stringify({
					proId: proId,              // Send proId as part of the JSON body
					tabledata: bakingData      // Send bakingData directly without stringifying
				}),
				contentType: 'application/json',
				success: function(response) {
					// Handle success response
					location.reload();
				},
				error: function(error) {
					// Handle error response
					alert('Something Went Wrong', error);
				}
			});
		});
		
		// Packing JS Start
		$('#item_id_packing').on('blur',function(){
			
			var ItemID = $(this).val();
			if(ItemID == "" || ItemID == null){
				
				}else{
				
                var proId = $('#pid').val();
                $.ajax({
					url:"<?php echo admin_url(); ?>production/get_item_details_baking",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{ItemID:ItemID,proId:proId},
					
					success:function(data){
						if(empty(data)){
							alert('Item not found...');
							$('#item_id_packing').val('');
							$('#item_name_packing').val('');
							$('#unit_packing').val("");
							$('#item_id_packing').focus();
							}else{
							$('#item_id_packing').val(data.item_code);
							$('#item_name_packing').val(data.description);
							$('#unit_packing').val(data.unit);
							$('#ItemStocks_packing').val(parseFloat(data.ItemStocks).toFixed(2));
							$('#req_qty_packing').focus();
						}
						
					}
				});
				
			}
			
		})
		$( "#item_id_packing" ).autocomplete({
			source: function( request, response ) {
				var proId = $('#pid').val();
				var recipeID = $('#recipeID1').val();
				
				// Fetch data
				$.ajax({
					url: "<?=base_url()?>admin/production/itemlist_subgroup_baking",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term, 'ProductionId' : proId, recipeID : recipeID
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
				// Set selection
				var item_string = $("#ItemID").val();
				let item_array = item_string.split(",");
				if(item_array.includes(ui.item.value)){
					
					$('#item_id_packing').val(ui.item.value); // display the selected text
					$('#item_name_packing').val(ui.item.label); // save selected id to input
					$('#unit_packing').val(ui.item.unit); // save selected id to input
					$('#item_name_packing').focus();
					return false;
					}else{
					alert("item not present in selected recipe");
				}
				
			}
		});
		
		$('#req_qty_packing').on('blur', function () {
			
			var req_qty = $("#req_qty_packing").val();
			var item_id = $("#item_id_packing").val();
			var item_name = $("#item_name_packing").val();
			var ItemStock = $("#ItemStocks_packing").val();
			var PRDStatus = $("#OldStatus").val();
			var PlantID = $("#PlantID").val();
			var Val = $(this).val();
			//alert(item_id);
			if(item_id == "" || item_id == null ){
				alert("Select Item ID.");
				focus(item_id);
				}else if(item_name == "" || item_name == null ){
				alert("Select Item Name.");
				focus(item_name);
				}else if(req_qty == "" || req_qty == null ){
				alert("Add Require Quantity.");
				//focus(req_qty);
				focus(item_name);
				}else if(parseFloat(ItemStock) < parseFloat(Val) && PRDStatus == "In-Progress"){
				// alert('Item Stock not available...'+ItemStock);
				add_row_packing();
				}else{
				add_row_packing();
			}  
		});
		
		function add_row_packing()
		{  
			var item_id =document.getElementById("item_id_packing").value;
			var item_name =document.getElementById("item_name_packing").value;
			var req_qty =document.getElementById("req_qty_packing").value;
			var unit =document.getElementById("unit_packing").value;
			
			var countof_record = document.getElementById("countof_record_packing").value;
			
			var table=document.getElementById("data_table_packing");
			var table_len=(table.rows.length)-1;
			var html = '';
			html += "<tr id='row"+table_len+"'>";
			html += "<td id='item_id_packing"+table_len+"'>"+item_id+" <input type='hidden' class='ItemIDPacking' name='item_id_packing"+table_len+"' value='"+item_id+"'></td>";
			html += "<td id='item_name_packing"+table_len+"'>"+item_name+" <input type='hidden'  class='ItemNamePacking' name='item_name_packing"+table_len+"' value='"+item_name+"'></td>";
			html += "<td id='req_qty_packing"+table_len+"'>"+req_qty+" <input type='hidden'  class='ItemQtyPacking' name='req_qty_packing"+table_len+"' value='"+req_qty+"'></td>";
			html += "<td id='unit_packing"+table_len+"'>"+unit+" <input type='hidden' class='ItemUnitPacking' name='unit_packing"+table_len+"' value='"+unit+"'></td>";
			
			html += '<td><button type="button" name="edit_packing" id="remove_packing" class="btn btn-xs btn-danger remove_packing" value="remove_packing"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum_packing" id="rownum_packing" value="'+countof_record+'"></td>';
			html += '</tr>';
			var row = table.insertRow(table_len).outerHTML=html;
			
			var temp1 = parseFloat(countof_record) + parseFloat(1);
			
			document.getElementById("countof_record_packing").value=temp1;
			
			//  if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
			//  }  else{
            var new_rec = $('#new_record').val();
            
			new_rec = new_rec +","+ item_id
            
			$('#new_record').val(new_rec);
			//alert(new_rec);
			//  }
			
			$(this).parents("tr").find(".edit_row").show();  
			$(this).parents("tr").find(".btn-cancel").remove();  
			$(this).parents("tr").find(".btn-update").remove();
			
			
			document.getElementById("item_id_packing").value="";
			document.getElementById("item_name_packing").value="";
			document.getElementById("req_qty_packing").value="";
			document.getElementById("unit_packing").value="";
			
			document.getElementById("item_name_packing").innerHTML="";
			document.getElementById("req_qty_packing").innerHTML="";
			document.getElementById("unit_packing").innerHTML="";
			
			document.getElementById("item_id_packing").focus(); 
		}
		var rowIdx2 = 1;
		$('#tbody_packing').on('click', '.remove_packing', function () {
			
			// Getting all the rows next to the 
			// row containing the clicked button
			var child = $(this).closest('tr').nextAll();
			
			// Iterating across all the rows 
			// obtained to change the index
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
			var  no = $(this).parents("tr").find('input[name="rownum_packing"]').val();
			var item_name =$(this).parents("tr").find('input[name="item_name_packing'+no+'"]').val();
			var item_id =$(this).parents("tr").find('input[name="item_id_packing'+no+'"]').val();
			// alert(no);
			
			
			// Removing the current row.
			$(this).closest('tr').remove();
			
			// Decreasing the total number of rows by 1.
			rowIdx2--;
			var countof_record = $("#countof_record_packing").val();
			var new_cont = countof_record -1;
			$("#countof_record_packing").val(new_cont);
			
			
		});
		
		$('#addBtn_packing').click(function() {
			var proId = $('#pid').val();
			// Initialize an array to hold the data from the table
			var PackingData = [];
			var i=1;
			// Loop through each row in the tbody
			$('#tbody_packing tr').each(function() {
				// Get the values from each input inside the row
				// var itemId = $(this).find('input[name="item_id_packing'+i+'"]').val();
				// var itemName = $(this).find('input[name="item_name_packing'+i+'"]').val();
				// var reqQty = $(this).find('input[name="req_qty_packing'+i+'"]').val();
				// var unit = $(this).find('input[name="unit_packing'+i+'"]').val();
				;
				var itemId = $(this).find('.ItemIDPacking').val();
				var itemName = $(this).find('.ItemNamePacking').val();
				var reqQty = $(this).find('.ItemQtyPacking').val();
				var unit = $(this).find('.ItemUnitPacking').val();
				var editid = $(this).find('.editid_packing').val();
				
				if (typeof editid === 'undefined' || editid === '') {
					editid = '';
				}
				
				// Create an object for this row and push to PackingData array
				var rowData = {
					edit_id: editid,
					item_id: itemId,
					item_name: itemName,
					req_qty: reqQty,
					unit: unit
				};
				
				if(itemId != '' && itemId != null){
					PackingData.push(rowData);
					i++;
				}
			});
			
			// Send the Packing data via AJAX
			$.ajax({
				url: "<?=base_url()?>admin/production/savePackingdata",
				type: 'POST',
				data: JSON.stringify({
					proId: proId,              // Send proId as part of the JSON body
					tabledata: PackingData      // Send PackingData directly without stringifying
				}),
				contentType: 'application/json',
				success: function(response) {
					// Handle success response
					location.reload();
				},
				error: function(error) {
					// Handle error response
					alert('Something Went Wrong', error);
				}
			});
		});
		
		// Focus from ItemID 
		$('#item_id').on('focus',function(){
			
			$('#item_id').val('');
			$('#item_name').val('');
			$('#unit').val("");
			
		})
		$('#item_id_packing').on('focus',function(){
			
			$('#item_id_packing').val('');
			$('#item_name_packing').val('');
			$('#unit_packing').val("");
			
		})
		$('#item_id_baking').on('focus',function(){
			
			$('#item_id_baking').val('');
			$('#item_name_baking').val('');
			$('#unit_baking').val("");
			
		})
		
	});
	
</script>

<script>
	function cal(){
		var req_qty = document.getElementById('req_qty').value;
		//alert(amount);
		var batch_qty = document.getElementById('batch_qty').value;
		//alert(batch_qty);
		var result = parseFloat(req_qty) * parseFloat(batch_qty);
		document.getElementById('pro_req_qty').value = result; 	
	}
</script>

<script>
	$(document).ready(function () {
		var rowIdx = 1;
		
		
		
		$('#tbody1').on('click', '.remove', function () {
			
			// Getting all the rows next to the 
			// row containing the clicked button
			var child1 = $(this).closest('tr').nextAll();
			
			// Iterating across all the rows 
			// obtained to change the index
			child1.each(function () {
				
				// Getting <tr> id.
				var id1 = $(this).attr('id');
				
				// Getting the <p> inside the .row-index class.
				var idx = $(this).children('.row-index').children('p');
				
				// Gets the row number from <tr> id.
				var dig = parseInt(id1.substring(1));
				
				// Modifying row index.
				idx.html(`Row ${dig - 1}`);
				
				// Modifying row id.
				$(this).attr('id', `R${dig - 1}`);
			});
			var  no1 = $(this).parents("tr").find('input[name="rownum1"]').val();
			var pro_item_name =$(this).parents("tr").find('input[name="pro_item_name'+no1+'"]').val();
			var pro_item_id =$(this).parents("tr").find('input[name="pro_item_id'+no1+'"]').val();
			//alert(no1);
			
			
			// Removing the current row.
			$(this).closest('tr').remove();
			
			// Decreasing the total number of rows by 1.
			rowIdx--;
			var countof_record1 = $("#countof_record1").val();
			var new_cont1 = countof_record1 -1;
			$("#countof_record1").val(new_cont1);
			
			var new_rec1 = $('#new_record1').val();
			$new_pro_item_name = ','+pro_item_name;
			new_rec1 = new_rec1.replace($new_pro_item_name, " ");
			$('#new_record1').val(new_rec1);
			
			var PRD_ItemID_list = $('#PRD_ItemID_list').val();
			var ItemID = ','+pro_item_id;
			var PRD = PRD_ItemID_list.replace(ItemID, "");
			$('#PRD_ItemID_list').val(PRD);
			
		});
		
		// new code 
		
		
		
		$('#return_pro_req_qty').on('blur', function () {
			
			var return_pro_req_qty = $("#return_pro_req_qty").val();
			var prd_qty = $("#production_req_qty_h").val();
			//alert(return_pro_req_qty);
			//alert(prd_qty);
			var pro_item_id = $("#pro_item_id").val();
			var pro_item_name = $("#pro_item_name").val();
			
			//alert(pro_item_id);
			if(pro_item_id == "" || pro_item_id == null ){
				alert("Select Item ID.");
				focus(pro_item_id);
				}else if(pro_item_name == "" || pro_item_name == null ){
				alert("Select Item Name.");
				focus(pro_item_name);
				}else if(return_pro_req_qty == "" || return_pro_req_qty == null ){
				alert("Add Return Quantity.");
				focus(return_pro_req_qty);
				}else if(parseFloat(return_pro_req_qty) > parseFloat(prd_qty)){
				alert("Please enter quantity less than or equal to production quantity");
				$("#return_pro_req_qty").val('');
				//$('#return_pro_req_qty').focus();
				}else{
				add_row1();
			}    
		});
		
		
		
		function add_row1()
		{  
			var pro_item_id =document.getElementById("pro_item_id").value;
			var pro_item_name =document.getElementById("pro_item_name").value;
			var pro_req_qty =document.getElementById("pro_req_qty").value;
			var production_req_qty =document.getElementById("production_req_qty").value;
			var return_pro_req_qty =document.getElementById("return_pro_req_qty").value;
			var pro_unit =document.getElementById("pro_unit").value;
			
			var countof_record1 = document.getElementById("countof_record1").value;
			
			var table1=document.getElementById("data_table1");
			var table_len1=(table1.rows.length)-1;
			var html = '';
			html += "<tr id='row"+table_len1+"'>";
			html += "<td id='pro_item_id"+table_len1+"'>"+pro_item_id+" <input type='hidden' name='pro_item_id"+table_len1+"' value='"+pro_item_id+"'></td>";
			html += "<td id='pro_item_name"+table_len1+"'>"+pro_item_name+" <input type='hidden' name='pro_item_name"+table_len1+"' value='"+pro_item_name+"'></td>";
			html += "<td id='pro_req_qty"+table_len1+"'>"+pro_req_qty+" <input type='hidden' name='pro_req_qty"+table_len1+"' value='"+pro_req_qty+"'></td>";
			html += "<td id='production_req_qty"+table_len1+"'>"+production_req_qty+" <input type='hidden' name='production_req_qty"+table_len1+"' value='"+production_req_qty+"'></td>";
			html += "<td id='return_pro_req_qty"+table_len1+"'>"+return_pro_req_qty+" <input type='hidden' name='return_pro_req_qty"+table_len1+"' value='"+return_pro_req_qty+"'></td>";
			html += "<td id='pro_unit"+table_len1+"'>"+pro_unit+" <input type='hidden' name='pro_unit"+table_len1+"' value='"+pro_unit+"'></td>";
			
			//html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum1' id='rownum1'></td>";
			html += '<td><button type="button" name="edit1" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum1" id="rownum1" value="'+countof_record1+'"></td>';
			html += '</tr>';
			var row = table1.insertRow(table_len1).outerHTML=html;
			var temp1 = parseFloat(countof_record1) + parseFloat(1);
			document.getElementById("countof_record1").value=temp1;
			
			//  if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
			//  }  else{
            var new_rec1 = $('#new_record1').val();
			
            
			new_rec1 = new_rec1 +","+ pro_item_id
			$('#new_record1').val(new_rec1);
			// alert(new_rec1);
			//  }
			
			$(this).parents("tr").find(".edit1_row").show();  
			$(this).parents("tr").find(".btn-cancel").remove();  
			$(this).parents("tr").find(".btn-update").remove();
			
			
			document.getElementById("pro_item_id").value="";
			document.getElementById("pro_item_name").value="";
			document.getElementById("pro_req_qty").value="";
			document.getElementById("production_req_qty").value="";
			document.getElementById("return_pro_req_qty").value="";
			document.getElementById("pro_unit").value="";
			
			document.getElementById("pro_item_name").innerHTML="";
			document.getElementById("pro_req_qty").innerHTML="";
			document.getElementById("production_req_qty").innerHTML="";
			document.getElementById("pro_unit").innerHTML="";
			
			document.getElementById("pro_item_id").focus(); 
		}
		
	}); 
	
</script>

<script type='text/javascript'>
	
	$(document).ready(function(){
		
		// Initialize 
		//By Item Code
		$( "#pro_item_id" ).autocomplete({
			source: function( request, response ) {
				// Fetch data
				var proId = $('#pid').val();
				//alert(proId);
				var maingroup = 2;
				$.ajax({
					url: "<?=base_url()?>admin/production/itemlist_subgroup1",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term, 'ProductionId' : proId,maingroup:maingroup
					},
					
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
                $('#pro_item_id').val(ui.item.value); // display the selected text
                $('#pro_item_name').val(ui.item.label); // save selected id to input pro_req_qty
    		    $('#pro_req_qty').val(ui.item.req_qty); // save selected id to input 
    		    $('#production_req_qty').val(ui.item.pro_req_qty); // save selected id to input
    		    $('#production_req_qty_h').val(ui.item.pro_req_qty); // save selected id to input
    		    $('#pro_unit').val(ui.item.unit); // save selected id to input
                $('#pro_item_name').focus();
                return false;
			}
		});
		
		// blur from ItemID 
		$('#pro_item_id').on('blur',function(){
			
			var ItemID = $(this).val();
			if(ItemID == "" || ItemID == null){
				
				}else{
				
				var PRD_ItemID_list = $("#PRD_ItemID_list").val();
				let PRD_ItemID_list_array = PRD_ItemID_list.split(",");
				if(PRD_ItemID_list_array.includes(ItemID.toUpperCase())){
					alert("item already added");
					$('#pro_item_id').val('');
					$('#pro_item_name').val('');
					$('#pro_req_qty').val("");
					$('#production_req_qty').val("");
					$('#pro_unit').val("");
					$('#pro_item_id').focus();
					}else{
					var proId = $('#pid').val();
					$.ajax({
						url:"<?php echo admin_url(); ?>production/get_item_details",
						dataType:"JSON",
						method:"POST",
						cache: false,
						data:{ItemID:ItemID,proId:proId},
						
						success:function(data){
							if(empty(data)){
								alert('Item not found...');
								$('#pro_item_id').val('');
								$('#pro_item_name').val('');
								$('#pro_req_qty').val("");
								$('#production_req_qty').val("");
								$('#pro_unit').val("");
								$('#pro_item_id').focus();
								}else{
								$("#PRD_ItemID_list").val(PRD_ItemID_list+","+data.item_id);
								$('#pro_item_id').val(data.item_id);
								$('#pro_item_name').val(data.item_name);
								$('#pro_req_qty').val(data.req_qty);
								$('#production_req_qty').val(data.production_req_qty);
								$('#production_req_qty_h').val(data.production_req_qty);
								$('#pro_unit').val(data.unit);
								$('#return_pro_req_qty').focus();
							}
							
						}
					});  
				}
			}
			
		})
		
		// Focus from ItemID 
		$('#pro_item_id').on('focus',function(){
			
			$('#pro_item_id').val('');
			$('#pro_item_name').val('');
			$('#pro_req_qty').val("");
			$('#production_req_qty').val("");
			$('#pro_unit').val("");
			
		});
		
		// blur From batch quntity
		$('#batch_qty').on('change',function(){
			$('#batchqtyChg').val('1')
		})
		$('#batch_qty').on('blur',function(){
			var batch = $(this).val();
			
			var recipeID =$('#recipeID1').val();
			if(batch < 1 ){
				alert('please select atleast 1 qty..');
				$('#batch_qty').val('1');
				$.ajax({
					url:"<?php echo admin_url(); ?>production/get_recipe_details",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{recipeID:recipeID},
					success:function(data){
						$('#qty_product').val(parseFloat(data.qty).toFixed(2));
						$('#finishgood_qty').val(data.qty);
						showData();
					}
				});
				}else{
				var defualt_f_g_qty = $('#defualt_finishgood_qty').val();
				var final_f_g_qty = parseFloat(defualt_f_g_qty) * parseFloat(batch);
				$('#qty_product').val(parseFloat(final_f_g_qty).toFixed(2));
				showData();
			}
			
		});
		
	});
	
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>
<script>
	function showData() {
		var recipeID =$('#recipeID1').val();
		var batch = $('#batch_qty').val();
		var PONumber = $('#pid').val();
		var GodownID1 = $('#GodownID1').val();
        if(recipeID == ""){
            //$("#data_table_exBody").html();
			}else{
            
            $.ajax({
                url: "<?=base_url()?>admin/production/ReceipeData",
				dataType:"JSON",
				method:"POST",
				cache: false,
                data:{'recipeID':recipeID, 'batchQuantity' : batch || '1','PONumber':PONumber,'GodownID':GodownID1},
                
                success:function(data){
					$("#data_table_exBody").html(data.RM);
					$("#data_table_exBody2").html(data.PM);
				}  
			}); 
		}
        
	}
</script>

<script type="text/javascript">
	function printPage(){
		
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .print_item_h{ background: #505f7b;color:#fff;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
		var print_data = stylesheet+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
</script>

<script type="text/javascript">
	$('#return_pro_req_qty').on('keypress',function (event) {
		var unit = $('#pro_unit').val();
		if(unit == "Kgs"){
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
				event.preventDefault();
			}
			var input = $(this).val();
			if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3)) {
				event.preventDefault();
			}
			}else if(unit == "Pcs"){
			event = (event) ? event : window.event;
			var charCode = (event.which) ? event.which : event.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	});
	
	$('#req_qty').on('keypress',function (event) {
		var unit = $('#unit').val();
		if(unit == "Kgs"){
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
				event.preventDefault();
			}
			var input = $(this).val();
			if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3)) {
				event.preventDefault();
			}
			}else if(unit == "Pcs"){
			event = (event) ? event : window.event;
			var charCode = (event.which) ? event.which : event.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
		
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
		
		$('#start_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
	});
</script> 

