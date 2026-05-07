<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
	foreach($editRecipe_details as $object){	
		$id= $object['id'];
	}
?>
<div id="wrapper">
	<div class="content">
		<div class="row" style="display:none;">
			<div class="col-md-12">
				<table id="print_table">
					<thead>
						<tr>
							<th align="center" colspan="4"><?php echo $company_detail->company_name; ?></th>
						</tr>
						<tr>
							<th align="center" colspan="4"><?php echo $company_detail->address; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4"><b>Recipe Details</b></td>
						</tr>
						<tr>
							<td ><b>ItemID : </b><?php echo $object['item_code'] ; ?></td>
							<td ><b>Name : </b><?php echo $object['item_description']; ?></td>
							<td ><b>Qty : </b><?php echo $object['qty']; ?></td>
							<td ><b>Unit : </b><?php echo $object['unit']; ?></td>
						</tr>
						<tr>
							<td ><b>Conversion Cost : </b><?php echo $object['conv_cost']; ?></td>
							<td ><b>Sales Team Cost % : </b> <?php echo $object['st_cost']; ?></td>
							<td ><b>Freight Cost % : </b><?php echo $object['frt_cost']; ?></td>
							<td ><b>Marketing Cost % : </b><?php echo $object['mrkt_cost']; ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Damage Cost % : </b><?php echo $object['dmg_cost']; ?></td>
							<?php if($object['status'] == "Y") { $status = 'Active'; }else{ $status = 'deActive'; }?>
							<td colspan="2"><b>Status : </b> <?php echo $status; ?></td>
							
						</tr>
						
						<tr class="print_item_h">
							<td colspan="4" align="center">Raw Materials</td>
						</tr>
						<tr class="print_item_h">
							<td align="center">ItemID</td>
							<td align="center">Item Name</td>
							<td align="center">Req.Qty</td>
							<td align="center">MesuredIn</td>
						</tr>
						<?php
							if(isset($editRecipe_details1)){
								foreach ($editRecipe_details1 as $value) {
									if($value['MainGrpID'] == '2'){
									?>
									<tr>
										<td align="center"><?php echo $value["item_id"]; ?></td>
										<td ><?php echo $value["item_name"]; ?></td>
										<td align="center"><?php echo $value["req_qty"]; ?></td>
										<td align="center"><?php echo $value["unit"]; ?></td>
									</tr>    
									<?php
									}
								}
							}
						?>
						<tr class="print_item_h">
							<td colspan="4" align="center">Packaging Materials</td>
						</tr>
						<tr class="print_item_h">
							<td align="center">ItemID</td>
							<td align="center">Item Name</td>
							<td align="center">Req.Qty</td>
							<td align="center">MesuredIn</td>
						</tr>
						<?php
							if(isset($editRecipe_details1)){
								foreach ($editRecipe_details1 as $value) {
									if($value['MainGrpID'] == '3'){
									?>
									<tr>
										<td align="center"><?php echo $value["item_id"]; ?></td>
									<td ><?php echo $value["item_name"]; ?></td>
									<td align="center"><?php echo $value["req_qty"]; ?></td>
									<td align="center"><?php echo $value["unit"]; ?></td>
									</tr>    
									<?php
									}
								}
							}
						?>
						
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						
						<div class="_buttons">
					<h4>Edit Recipe</h4>
					</div>
					<div class="clearfix"></div>
					<?php echo form_open('admin/production/editRecipe/'.$object['id'],array('id'=>'receipe_edit_form'));
						
					?>
					
					<?php if(isset($editRecipe_details)){ ?>
						<input type="hidden" name="updated_record" value=" " id="updated_record">
						<input type="hidden" name="deleted_record" value=" " id="deleted_record">
						<input type="hidden" name="new_record" value=" " id="new_record">
					<?php } ?>
					
					<input type="hidden" name="item_desc" id="item_desc" value="<?php echo $object['item_description']?>">
					<input type="hidden" name="id" id="id" value="<?php echo $object['id']?>">
					
					<div class="row">
						<div class="col-md-2">
							<?php 
								$value=$object['item_code']; 
							echo render_input('item_code','Recipe For',$value); ?>
							<div class="" id="serchh" style="display:none;">Serching</div>
						</div>
						<div class="col-md-3">
							<?php
								$attr = array('disabled'=>true);
							?>
							<?php 
								$value=$object['item_description']; 
							echo render_input('ItemName','Recipe Name',$value,'',$attr); ?>
						</div>
						<!--<div class="col-md-2">
							<div class="form-group" app-field-wrapper="qtytoproduce">
							<label class="control-label" for="qtytoproduce"><?php echo _l('Finished Good Qty'); ?></label>
							<input type="text" value="<?php echo $object['qty'];?>" name="qtytoproduce" id="qtytoproduce" class="form-control">
							</div>
						</div>-->  
						
						<div class="col-md-2">
							<label class="control-label" for=""><?php echo _l('Measured In'); ?></label>
							<input type="text" value="<?php echo $object['unit'];?>" name="unit_f_g" id="unit_f_g" class="form-control" readonly>
							<input type="hidden" value="<?php echo $object['unit'];?>" name="unit_f_g1" id="unit_f_g1" class="form-control">
						</div>
						
						
						<div class="col-md-1" style="margin-top: 20px;">
							<?php if(has_permission_new('recipe','','view')){ ?>
								<a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>
								<?php
								}
							?>
						</div>	
						
						
					</div>
					
					<div class="row">
						<!--<div class="col-md-2">
							<div class="form-group" app-field-wrapper="conv_cost">
							<label for="conv_cost" class="control-label">Conversion Cost</label>
							<input type="text" name="conv_cost" id="conv_cost" class="form-control" value="<?php echo $object['conv_cost'];?>" >
							</div>
							</div>
							
							<div class="col-md-2">
							<div class="form-group" app-field-wrapper="st_cost">
							<label for="st_cost" class="control-label">Sales Team Cost %</label>
							<input type="text" name="st_cost" id="st_cost" class="form-control" value="<?php echo $object['st_cost'];?>" >
							</div>
							</div>
							<div class="col-md-2">
							<div class="form-group" app-field-wrapper="frt_cost">
							<label for="frt_cost" class="control-label">Freight Cost %</label>
							<input type="text" name="frt_cost" id="frt_cost" class="form-control" value="<?php echo $object['frt_cost'];?>" >
							</div>
							</div>
							<div class="col-md-2">
							<div class="form-group" app-field-wrapper="mrkt_cost">
							<label for="mrkt_cost" class="control-label">Marketing Cost %</label>
							<input type="text" name="mrkt_cost" id="mrkt_cost" class="form-control" value="<?php echo $object['mrkt_cost'];?>" >
							</div>
							</div>
							<div class="col-md-2">
							<div class="form-group" app-field-wrapper="dmg_cost">
							<label for="dmg_cost" class="control-label">Damage Cost %</label>
							<input type="text" name="dmg_cost" id="dmg_cost" class="form-control" value="<?php echo $object['dmg_cost'];?>">
							</div>
						</div>-->
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="std_yield">
								<label for="std_yield" class="control-label">Std. Yield</label>
								<input type="text" name="std_yield" id="std_yield" class="form-control" value="<?php echo $object['std_yield'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="dough_wastage_per">
								<label for="dough_wastage_per" class="control-label">Dough Wastage %</label>
								<input type="text" name="dough_wastage_per" onblur="GetDoughWeight(),GetPackets()" id="dough_wastage_per" class="form-control" value="<?php echo $object['dough_wastage_per'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="yield_wastage_per">
								<label for="yield_wastage_per" class="control-label">Yield Of Packate Wastage %</label>
								<input type="text" name="yield_wastage_per" onblur="GetDoughWeight(),GetPackets()" id="yield_wastage_per" class="form-control" value="<?php echo $object['yield_wastage_per'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="env_temp">
								<label for="env_temp" class="control-label">Env. Temp.</label>
								<input type="text" name="env_temp" id="env_temp" class="form-control" value="<?php echo $object['env_temp'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="env_humidity">
								<label for="env_humidity" class="control-label">Env. Humidity</label>
								<input type="text" name="env_humidity" id="env_humidity" class="form-control" value="<?php echo $object['env_humidity'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="water_temp">
								<label for="water_temp" class="control-label">Water Temp</label>
								<input type="text" name="water_temp" id="water_temp" class="form-control" value="<?php echo $object['water_temp'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="batch_size">
								<label for="batch_size" class="control-label">BATCH SIZE (KG) In Flour base</label>
								<input type="text" name="batch_size" id="batch_size" class="form-control" value="<?php echo $object['batch_size'];?>" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group" app-field-wrapper="grammage_product">
								<label for="grammage_product" class="control-label">GRAMMAGE OF THE PRODUCT (ingm)</label>
								<input type="text" name="grammage_product" onblur="GetMoistureLossPost()" id="grammage_product" class="form-control" value="<?php echo $object['grammage_product'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="dough_dividing_weight">
								<label for="dough_dividing_weight" class="control-label">DOUGH DIVIDING WEIGHT</label>
								<input type="text" name="dough_dividing_weight" id="dough_dividing_weight" onblur="GetMoistureLossPost(),VolumeRatio(),GetPackets()" class="form-control" value="<?php echo $object['dough_dividing_weight'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="mould_length">
								<label for="mould_length" class="control-label">MOULD LENGTH</label>
								<input type="text" name="mould_length" id="mould_length" onblur="VolumeRatio()" class="form-control" value="<?php echo $object['mould_length'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="mould_width">
								<label for="mould_width" class="control-label">MOULD WIDTH</label>
								<input type="text" name="mould_width" id="mould_width" onblur="VolumeRatio()" class="form-control" value="<?php echo $object['mould_width'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="mould_depth">
								<label for="mould_depth" class="control-label">MOULD DEPTH</label>
								<input type="text" name="mould_depth" id="mould_depth" onblur="VolumeRatio()" class="form-control" value="<?php echo $object['mould_depth'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="wrapper_per_kg">
								<label for="wrapper_per_kg" class="control-label">NUMBER OF WRAPPER PER KG</label>
								<input type="text" name="wrapper_per_kg" id="wrapper_per_kg" class="form-control" value="<?php echo $object['wrapper_per_kg'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="rate_wrapper_per_kg">
								<label for="rate_wrapper_per_kg" class="control-label">RATE OF WRAPPER PER KG</label>
								<input type="text" name="rate_wrapper_per_kg" id="rate_wrapper_per_kg" class="form-control" value="<?php echo $object['rate_wrapper_per_kg'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="refined_palm_oil">
								<label for="refined_palm_oil" class="control-label">REFIND PALM OIL (MOULD)</label>
								<input type="text" name="refined_palm_oil" id="refined_palm_oil" class="form-control" value="<?php echo $object['refined_palm_oil'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="net_rec_rate">
								<label for="net_rec_rate" class="control-label">NET RCOVERY RATE</label>
								<input type="text" name="net_rec_rate" id="net_rec_rate" class="form-control" value="<?php echo $object['net_rec_rate'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="ptr">
								<label for="ptr" class="control-label">PTR</label>
								<input type="text" name="ptr" id="ptr" class="form-control" value="<?php echo $object['ptr'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="moisture_loss_post_ddw">
								<label for="moisture_loss_post_ddw" class="control-label">MOISTURE LOSS POST DDW</label>
								<input type="text" name="moisture_loss_post_ddw" readonly id="moisture_loss_post_ddw" class="form-control" value="<?php echo $object['moisture_loss_post_ddw'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="volume_ratio_cc">
								<label for="volume_ratio_cc" class="control-label">VOLUME/WEIGHT RATIO CC</label>
								<input type="text" name="volume_ratio_cc" readonly id="volume_ratio_cc" class="form-control" value="<?php echo $object['volume_ratio_cc'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="rm_size">
								<label for="rm_size" class="control-label">RM SIZE</label>
								<input type="text" name="rm_size" id="rm_size" readonly class="form-control" value="<?php echo $object['rm_size'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="dough_weight">
								<label for="dough_weight" class="control-label">DOUGH WEIGHT</label>
								<input type="text" name="dough_weight" readonly id="dough_weight" class="form-control" value="<?php echo $object['dough_weight'];?>" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group" app-field-wrapper="net_dough_weight">
								<label for="net_dough_weight" class="control-label">NET DOUGH WEIGHT @WASTAGE 1%</label>
								<input type="text" name="net_dough_weight" readonly id="net_dough_weight" class="form-control" value="<?php echo $object['net_dough_weight'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="number_packets">
								<label for="number_packets" class="control-label">NUMBER OF PACKETS</label>
								<input type="text" name="number_packets" readonly id="number_packets" class="form-control" value="<?php echo $object['qty'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="yield_packets">
								<label for="yield_packets" class="control-label">YEILD OF PACKET @ WASTAGE 1%</label>
								<input type="text" name="yield_packets" readonly id="yield_packets" class="form-control" value="<?php echo $object['yield_packets'];?>" >
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="Is_Baking">
								<label for="Is_Baking" class="control-label">Is Baking Required</label>
								<select name="Is_Baking" id="Is_Baking" class="form-control">
									<option value="Y" <?php if($object['Is_Baking'] == "Y") { echo 'selected'; }?>>Yes</option>
									<option value="N" <?php if($object['Is_Baking'] == "N") { echo 'selected'; }?>>No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="status">
								<label for="status" class="control-label">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="Y" <?php if($object['status'] == "Y") { echo 'selected'; }?>>Active</option>
									<option value="N" <?php if($object['status'] == "N") { echo 'selected'; }?>>Inactive</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<h5>Raw Materials</h5>
							<table class="table table-striped table-bordered" id="data_table" width="100%">
								<thead>
									<tr> 
										<th width="15%">Item Code</th>
										<th width="50%">Item Name</th> 
										<th>Is Calculation?</th>
										<th width="10%">Req. Qty</th>
										<th width="10%">Measured In</th>
										<th width="15%">Action</th>
									</tr>
								</thead>
								<tbody id="tbody">
									<?php
										$i=1;
										$ReceipeItemID = array();
										array_push($ReceipeItemID,' ');
										$countrm = 0;
										foreach ($editRecipe_details1 as $value) {
											if($value["MainGrpID"] == '2'){
												$countrm++;
												//$order_qty = get_order_qty($value["item_id"],$value["TransID"],$value["FY"],$value["PlantID"]);
												if($value["unit"] == "Kg"){
													$cond = 'onkeypress = "return decimalNumber(event)"';
													}else{
													$cond = 'onkeypress="return isNumber(event)"';
												}
												array_push($ReceipeItemID,$value["item_id"]);
											?>
											<tr id='row<?php echo $i;?>'>
												<td align="center" width="15%" id='item_id<?php echo $i;?>'><?php echo $value["item_id"]; ?><input type='hidden' name='item_id<?php echo $i;?>' value='<?php echo $value["item_id"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;"></td>
												
												<td width="50%" id='item_name<?php echo $i;?>'><?php echo $value["item_name"]; ?><input type='hidden' name='item_name<?php echo $i;?>' value='<?php echo $value["item_name"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;"><input type="hidden" name="item_main_group" id="item_main_group"   class="form-control item_main_group" value='<?php echo $value["MainGrpID"]; ?>' ></td>
												
												
												<td><select type="text" name="is_calculation<?php echo $i;?>" id="is_calculation<?php echo $i;?>" class="form-control Status_Cal" onchange="GetRMSize(),update_record('<?php echo $value["item_id"]; ?>')" style="width: 160px;border-radius: 2px;height: 30px;">
													<option <?php if($value["is_calculation"] == 'Y'){echo "selected";} ?> value="Y">Yes</option>
													<option <?php if($value["is_calculation"] == 'N'){echo "selected";} ?> value="N">No</option>
												</select></td>
												
												<td width="10%" align="right" id='req_qty<?php echo $i;?>'><?php //echo $value["req_qty"]; ?><input type='text' name='req_qty<?php echo $i;?>' class="Quantity" id="<?php echo $value["item_id"]; ?>" <?php echo $cond;?> value='<?php echo $value["req_qty"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;" onblur="update_record(this.id)"></td>
												
												<td width="10%" align="center" id='unit<?php echo $i;?>'><?php echo $value["unit"]; ?><input type='hidden' name='unit<?php echo $i;?>' value='<?php echo $value["unit"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;" readonly></td>
												
												<td width="15%" align="center"><button type="button" name="edit" id="remove_exiting_item" class="btn btn-xs btn-danger remove_exiting_item" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum<?php echo $i;?>" id="rownum" value="<?php echo $i;?>"></td>
											</tr>
											<?php
												$i++; 
											}     
										}     
									?>
									<tr id="R1">
										
										<td style="width:10%"><input type="text" name="item_id" id="item_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
										
										<td style="width:15%" ><input type="text" name="item_name" id="item_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="item_name" id="item_name" value=""><input type="hidden" name="item_main_group" id="item_main_group" class="form-control" </td>
											
									
											<td><select type="text" name="is_calculation" id="is_calculation" class="form-control" style="width: 160px;border-radius: 2px;height: 30px;">
												<option value="Y">Yes</option>
												<option value="N">No</option>
											</select></td>
											
											<td style="width:15%" ><input type="text" name="req_qty" id="req_qty" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="req_qty" id="req_qty" value=""></td>
											
											<td style="width:15%" ><input type="text" name="unit" id="unit" value="" style="height: 30px;width: 100%;" readonly/><input type="hidden" name="unit" id="unit" value=""></td>
											
											<td style="width:5%"></td>
											<!--<button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-12">
								<h5>Packaging Materials</h5>
								<table class="table table-striped table-bordered" id="data_table2" width="100%">
									
									<thead>
										<tr> 
											<th width="15%">Item Code</th>
											<th width="50%">Item Name</th>
											<th>Is Calculation?</th>
											<th width="10%">Req. Qty</th>
											<th width="10%">Measured In</th>
											<th width="15%">Action</th>
										</tr>
									</thead>
									<tbody id="Ptbody">
										<?php
											$i=1;
											$countpm = 0;
											foreach ($editRecipe_details1 as $value) {
												if($value["MainGrpID"] == '3'){
													$countpm++;
													if($value["unit"] == "Kg"){
														$cond = 'onkeypress = "return decimalNumber(event)"';
														}else{
														$cond = 'onkeypress="return isNumber(event)"';
													}
													array_push($ReceipeItemID,$value["item_id"]);
												?>
												<tr id='Prow<?php echo $i;?>'>
													<td align="center" width="15%" id='Pitem_id<?php echo $i;?>'><?php echo $value["item_id"]; ?><input type='hidden' name='Pitem_id<?php echo $i;?>' value='<?php echo $value["item_id"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;"></td>
													
													<td width="50%" id='Pitem_name<?php echo $i;?>'><?php echo $value["item_name"]; ?><input type='hidden' name='Pitem_name<?php echo $i;?>' value='<?php echo $value["item_name"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;"><input type="hidden" name="Pitem_main_group" id="Pitem_main_group"   class="form-control Pitem_main_group" value='<?php echo $value["MainGrpID"]; ?>' ></td>
													
													<td><select type="text" name="Pis_calculation<?php echo $i;?>" id="Pis_calculation<?php echo $i;?>" class="form-control PStatus_Cal" onchange="GetRMSize(),update_record('<?php echo $value["item_id"]; ?>')" style="width: 160px;border-radius: 2px;height: 30px;">
														<option <?php if($value["is_calculation"] == 'Y'){echo "selected";} ?> value="Y">Yes</option>
														<option <?php if($value["is_calculation"] == 'N'){echo "selected";} ?> value="N">No</option>
													</select></td>
													
													<td width="10%" align="right" id='Preq_qty<?php echo $i;?>'><?php //echo $value["req_qty"]; ?><input type='text' name='Preq_qty<?php echo $i;?>' class="PQuantity" id="<?php echo $value["item_id"]; ?>" <?php echo $cond;?> value='<?php echo $value["req_qty"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;" onblur="update_record(this.id)"></td>
													
													<td width="10%" align="center" id='Punit<?php echo $i;?>'><?php echo $value["unit"]; ?><input type='hidden' name='Punit<?php echo $i;?>' value='<?php echo $value["unit"]; ?>' style="width: 100%;border-radius: 2px;height: 30px;" readonly></td>
													
													<td width="15%" align="center"><button type="button" name="Pedit" id="Premove_exiting_item" class="btn btn-xs btn-danger Premove_exiting_item" value="Premove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="Prownum<?php echo $i;?>" id="Prownum" value="<?php echo $i;?>"></td>
												</tr>
												<?php
													$i++;
												}  
											}
										?>
										<tr id="P1">
											
											<td style="width:10%"><input type="text" name="Pitem_id" id="Pitem_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
											
											<td style="width:15%" ><input type="text" name="Pitem_name" id="Pitem_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="Pitem_name" id="Pitem_name" value=""><input type="hidden" name="Pitem_main_group" id="Pitem_main_group" class="form-control"> </td>
											
											<td><select type="text" name="Pis_calculation" id="Pis_calculation" class="form-control" style="width: 160px;border-radius: 2px;height: 30px;">
												<option value="Y">Yes</option>
												<option value="N">No</option>
											</select></td>
											
											<td style="width:15%" ><input type="text" name="Preq_qty" id="Preq_qty" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="Preq_qty" id="Preq_qty" value=""></td>
											
											<td style="width:15%" ><input type="text" name="Punit" id="Punit" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="Punit" id="Punit" value=""></td>
											
											<td style="width:5%"></td>
											<!--<button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
										</tr>
									</tbody>
								</table>
							</div>
							
						</div>
						
						<div class="row">
							<input type="hidden" value="<?php echo $countrm + 1;?>" name="countof_record" id="countof_record">
							<input type="hidden" value="<?php echo $countpm + 1;?>" name="Pcountof_record" id="Pcountof_record">
							<input type="hidden" value="2" name="sub_group" id="sub_group">
							<div class="col-md-2" style="margin-top:10px;">
								<?php $AllItemIDStr = implode(',', $ReceipeItemID); ?>
								<input type="hidden" name="AllItemID" id="AllItemID" value="<?php echo $AllItemIDStr; ?>">
								<?php
									if (has_permission_new('recipe', '', 'edit')) {
									?>
									<button type="submit" class="btn btn-info"><?php echo 'Update'; ?></button>
								<?php } ?>
							</div>
							<div class="col-md-2" style="margin-top:10px;">
								<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
								<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
							</div>
						</div>
						<?php echo form_close(); ?>
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
					<h4 class="modal-title">Recipe List</h4>
				</div>
				<div class="modal-body">
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<lebel for="Status" class="form-label">Status</lebel>
								<select name="Status" id="Status" class="form-control">
									<option value="Y">Active</option>
									<option value="N">DeActive</option>
									<option value="YN">All</option>
								</select>
							</div>
						</div>
						<div class="col-md-3"><br>
							<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
						</div>
						<!--<div class="col-md-6">
							<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
						</div>-->
						
						<div class="col-md-12">
							
							<div class="table_recipe_report">
								
								<table class="tree table table-striped table-bordered table_recipe_report" id="table_recipe_report" width="100%">
									
									<thead>
										<tr style="display:none;">
											<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
										</tr>
										<tr>
											<th style="text-align:left;">RecipeCode</th>
											<th style="text-align:left;">RecipeName</th>
											<th style="text-align:left;">Qty.</th>
											<th style="text-align:left;">MeasuredIn</th>
											<th style="text-align:left;">ActiveDate</th>
											<th style="text-align:left;">DeActiveDate</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($recipe_list as $key => $value) {
												$url = admin_url().'production/editRecipe/'.$value['id'];
											?>
											<tr onclick="location.href='<?php echo $url; ?>'">
												<td><?php echo $value['item_code'];?></td>
												<td><?php echo $value['item_description'];?></td>
												<td><?php echo $value['qty'];?></td>
												<td><?php echo $value['unit'];?></td>
												<td><?php echo _d(substr($value['ActiveDate'],0,10));?></td>
												<td><?php echo _d(substr($value['DeActiveDate'],0,10));?></td>
											</tr>
											<?php
												# code...
											}
										?>
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
					<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
				</div>
				
			</div>
		</div>
	</div>
	<?php init_tail(); ?>
	<style>
		.table_recipe_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
		.table_recipe_report thead th { position: sticky; top: 0; z-index: 1; }
		.table_recipe_report tbody th { position: sticky; left: 0; }
		
		/* Just common table stuff. Really. */
		.table_recipe_report table  { border-collapse: collapse; width: 100%; }
		.table_recipe_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
		.table_recipe_report th     { background: #50607b;color: #fff !important; }
		.modal-body {
		padding: 2px 5px;
		}
		.modal-header{
		padding: 5px 10px;
		}
		
		#table_recipe_report tr:hover {
		background-color: #ccc;
		}
		
		#table_recipe_report td:hover {
		cursor: pointer;
		}
		
		table  { border-collapse: collapse; width: 100%; }
		th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
		th     { background: #50607b;color: #fff !important; }
		
	</style>
	<script type="text/javascript">
		
		$("#caexcel").click(function(){
			var item_code = $("#item_code").val();
			var id = $("#id").val();
			
			$.ajax({
				url:"<?php echo admin_url(); ?>production/export_recipe",
				method:"POST",
				data:{id:id,item_code:item_code},
				beforeSend: function () {
					$('#searchh3').css('display','block');
					$('#searchh3').css('color','blue');
				},
				complete: function () {
					$('#searchh3').css('display','none');
				},
				success:function(data){
					response = JSON.parse(data);
					window.location.href = response.site_url+response.filename;
				}
			});
			
		});
		function GetMoistureLossPost() {
			var grammage_product = $('#grammage_product').val();
			var dough_dividing_weight = $('#dough_dividing_weight').val();
			
			if(grammage_product !== '' && grammage_product !== null && dough_dividing_weight !== '' && dough_dividing_weight !== null){
				var value = (1-(grammage_product/dough_dividing_weight))*100;
				$('#moisture_loss_post_ddw').val(value.toFixed(2));
				}else{
				$('#moisture_loss_post_ddw').val('');
			}
		}
		function GetRMSize() {
			const rows = document.querySelectorAll('#data_table tbody tr');
			let total = 0;
			
			// Loop through each row
			rows.forEach(row => {
				// Get the quantity input for the current row
				const quantityInput = row.querySelector('.Quantity');
				const statusInput = row.querySelector('.Status_Cal');
				const item_main_group = row.querySelector('.item_main_group');
				
				// Ensure both quantityInput and statusInput exist
				if (quantityInput && statusInput) {
					
					// Check if status is 'yes' and quantity is valid (not null or empty)
					if (statusInput.value === 'Y' && quantityInput.value !== null && quantityInput.value !== '') {
						if(item_main_group.value == '2'){
							total += parseFloat(quantityInput.value); // Add quantity to total
						}
					}
				}
			});
			
			// Set the total value to the rm_size input field
			$('#rm_size').val(total.toFixed(2));
		}
		function GetDoughWeight() {
			var dough_wastage_per = $('#dough_wastage_per').val();
			if(dough_wastage_per == '' || dough_wastage_per == null){
				dough_wastage_per = 0;
			}
			const rows = document.querySelectorAll('#data_table tbody tr');
			let total = 0;
			
			// Loop through each row
			rows.forEach(row => {
				// Get the quantity input for the current row
				const quantityInput = row.querySelector('.Quantity');
				const statusInput = row.querySelector('.Status_Cal');
				const item_main_group = row.querySelector('.item_main_group');
				
				
				// Ensure both quantityInput exist
				if (quantityInput && statusInput) {
					
					// Check if quantity is valid (not null or empty)
					if (statusInput.value === 'Y' && quantityInput.value !== null && quantityInput.value !== '') {
						if(item_main_group.value == '2'){
							total += parseFloat(quantityInput.value); // Add quantity to total
						}
					}
				}
			});
			let net_dough_weight = total-((dough_wastage_per / 100) * total); // or simply value * 0.01
			
			// Set the total value to the rm_size input field
			$('#dough_weight').val(total.toFixed(2));
			$('#net_dough_weight').val(net_dough_weight.toFixed(2));
		}
		
		function GetPackets() {
			var dough_dividing_weight = $('#dough_dividing_weight').val();
			var net_dough_weight = $('#net_dough_weight').val();
			var yield_wastage_per = $('#yield_wastage_per').val();
			if(yield_wastage_per == '' || yield_wastage_per == null){
				yield_wastage_per = 0;
			}
			
			if(dough_dividing_weight !== '' && dough_dividing_weight !== null && net_dough_weight !== '' && net_dough_weight){
				var value = net_dough_weight/(dough_dividing_weight/1000);
				let yield_packets = value-((yield_wastage_per / 100) * value);
				$('#number_packets').val(value.toFixed(2));
				$('#yield_packets').val(yield_packets.toFixed(2));
				}else{
				$('#number_packets').val('');
			}
		}
		function VolumeRatio() {
			var dough_dividing_weight = $('#dough_dividing_weight').val();
			var mould_length = $('#mould_length').val();
			var mould_width = $('#mould_width').val();
			var mould_depth = $('#mould_depth').val();
			
			if(dough_dividing_weight !== '' && dough_dividing_weight !== null && mould_length !== '' && mould_length !== null && mould_width !== '' && mould_width !== null && mould_depth !== '' && mould_depth !== null){
				var value = ((mould_length*mould_width*mould_depth)/1000000/dough_dividing_weight)*1000;
				$('#volume_ratio_cc').val(value.toFixed(2));
				}else{
				$('#volume_ratio_cc').val('');
			}
		}
		function printPage(){
			
			var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .print_item_h{ background: #505f7b;color:#fff;} </style>';
			var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="80%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
			var print_data = stylesheet+tableData
			newWin= window.open("");
			newWin.document.write(print_data);
			newWin.print();
			newWin.close();
		};
		
		$('.Quantity').on('blur', function () {
			GetRMSize();
			GetDoughWeight();
			GetPackets();
		});
	</script>
	<script>
		$('.add-new-transfer').on('click', function(){
			$('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
			$('#transfer-modal').modal('show');
			//init_journal_entry_table();
			$('#transfer-modal').on('shown.bs.modal', function () {
				$('#myInput1').focus();
			})
		});
	</script>
	<script type="text/javascript">
		$('#std_yield,#yield_wastage_per,#dough_wastage_per,#conv_cost,#st_cost,#frt_cost,#mrkt_cost,#dmg_cost').on('keypress',function (event) {
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
		function myFunction2() {
			var input, filter, table, tr, td, i, txtValue;
			input = document.getElementById("myInput1");
			filter = input.value.toUpperCase();
			table = document.getElementById("table_recipe_report");
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
						}else if(td1) {
						txtValue = td1.textContent || td1.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
							}else if(td2) {
							txtValue = td2.textContent || td2.innerText;
							if (txtValue.toUpperCase().indexOf(filter) > -1) {
								tr[i].style.display = "";
								}else if(td3) {
								txtValue = td3.textContent || td3.innerText;
								if (txtValue.toUpperCase().indexOf(filter) > -1) {
									tr[i].style.display = "";
									}else if(td4) {
									txtValue = td4.textContent || td4.innerText;
									if (txtValue.toUpperCase().indexOf(filter) > -1) {
										tr[i].style.display = "";
										}else if(td5) {
										txtValue = td5.textContent || td5.innerText;
										if (txtValue.toUpperCase().indexOf(filter) > -1) {
											tr[i].style.display = "";
											} else {
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
	<script type='text/javascript'>
		
		$(document).ready(function () {
			var rowIdx = 1;
			
			
			// remove new added row
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
				var  no = $(this).parents("tr").find('input[id="rownum"]').val();
				
				// Removing the current row.
				$(this).closest('tr').remove();
				
				// Decreasing the total number of rows by 1.
				rowIdx--;
				
				//counter update
				var countof_record = $("#countof_record").val();
				var new_cont = countof_record -1;
				$("#countof_record").val(new_cont);
				
				// new added records update
				var item_id =$(this).parents("tr").find('input[name="item_id'+no+'"]').val();
				
				var new_rec = $('#new_record').val();
				$new_item_name = ','+item_id;
				new_rec = new_rec.replace($new_item_name, " ");
				$('#new_record').val(new_rec);
				
				var newItemID = $('#AllItemID').val();
				$new_itemID = ','+item_id;
				newItemID = newItemID.replace($new_itemID, " ");
				$('#AllItemID').val(newItemID);
				
				GetRMSize();
				GetDoughWeight();
				GetPackets();
				
			});
			// remove new added row
			$('#Ptbody').on('click', '.Premove', function () {
				
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
				var  no = $(this).parents("tr").find('input[id="Prownum"]').val();
				
				// Removing the current row.
				$(this).closest('tr').remove();
				
				// Decreasing the total number of rows by 1.
				rowIdx--;
				
				//counter update
				var countof_record = $("#Pcountof_record").val();
				var new_cont = countof_record -1;
				$("#Pcountof_record").val(new_cont);
				
				// new added records update
				var item_id =$(this).parents("tr").find('input[name="Pitem_id'+no+'"]').val();
				// console.log(no);
				var new_rec = $('#new_record').val();
				$new_item_name = ','+item_id;
				new_rec = new_rec.replace($new_item_name, " ");
				$('#new_record').val(new_rec);
				
				var newItemID = $('#AllItemID').val();
				$new_itemID = ','+item_id;
				newItemID = newItemID.replace($new_itemID, " ");
				$('#AllItemID').val(newItemID);
				
				GetRMSize();
				GetDoughWeight();
				GetPackets();
				
			});
			
			// remove existing row
			$('#tbody').on('click', '.remove_exiting_item', function () {
				
				var child = $(this).closest('tr').nextAll();
				
				child.each(function () {
					
					var id = $(this).attr('id');
					var idx = $(this).children('.row-index').children('p');
					var dig = parseInt(id.substring(1));
					
					idx.html(`Row ${dig - 1}`);
					
					$(this).attr('id', `R${dig - 1}`);
				});
				var  no = $(this).parents("tr").find('input[id="rownum"]').val();
				
				// Removing the current row.
				$(this).closest('tr').remove();
				
				// Decreasing the total number of rows by 1.
				rowIdx--;
				
				//counter update
				var countof_record = $("#countof_record").val();
				var new_cont = countof_record -1;
				$("#countof_record").val(new_cont);
				
				// update deleted records
				var item_id =$(this).parents("tr").find('input[name="item_id'+no+'"]').val();
				
				var new_rec = $('#deleted_record').val();
				$new_item_name = new_rec+','+item_id;
				$('#deleted_record').val($new_item_name);
				
				var new_rec = $('#new_record').val();
				$new_item_name = ','+item_id;
				new_rec = new_rec.replace($new_item_name, " ");
				$('#new_record').val(new_rec);
				
				var newItemID = $('#AllItemID').val();
				$new_itemID = ','+item_id;
				newItemID = newItemID.replace($new_itemID, " ");
				$('#AllItemID').val(newItemID);
				
				GetRMSize();
				GetDoughWeight();
				GetPackets();
				
				
			});
			$('#Ptbody').on('click', '.Premove_exiting_item', function () {
				
				var child = $(this).closest('tr').nextAll();
				
				child.each(function () {
					
					var id = $(this).attr('id');
					var idx = $(this).children('.row-index').children('p');
					var dig = parseInt(id.substring(1));
					
					idx.html(`Row ${dig - 1}`);
					
					$(this).attr('id', `R${dig - 1}`);
				});
				var  no = $(this).parents("tr").find('input[id="Prownum"]').val();
				
				// Removing the current row.
				$(this).closest('tr').remove();
				
				// Decreasing the total number of rows by 1.
				rowIdx--;
				
				//counter update
				var countof_record = $("#Pcountof_record").val();
				var new_cont = countof_record -1;
				$("#Pcountof_record").val(new_cont);
				
				// update deleted records
				var item_id =$(this).parents("tr").find('input[name="Pitem_id'+no+'"]').val();
				
				var new_rec = $('#deleted_record').val();
				$new_item_name = new_rec+','+item_id;
				$('#deleted_record').val($new_item_name);
				
				var new_rec = $('#new_record').val();
				$new_item_name = ','+item_id;
				new_rec = new_rec.replace($new_item_name, " ");
				$('#new_record').val(new_rec);
				
				var newItemID = $('#AllItemID').val();
				$new_itemID = ','+item_id;
				newItemID = newItemID.replace($new_itemID, " ");
				$('#AllItemID').val(newItemID);
				
				GetRMSize();
				GetDoughWeight();
				GetPackets();
				
				
			});
			
			
			
			// new code
			
			$('#req_qty').on('blur', function () {
				
				var req_qty = $("#req_qty").val();
				var item_id = $("#item_id").val();
				var item_name = $("#item_name").val();
				//alert(item_id);
				if(item_id == "" || item_id == null ){
					
					alert("Select Item ID.");
					
					}else if(item_name == "" || item_name == null ){
					
					alert("Select Item Name.");
					
					}else if(req_qty == "" || req_qty == null ){
					
					alert("Add Require Quantity.");
					
					}else{
					add_row();
				}
			});
			$('#Preq_qty').on('blur', function () {
				
				var req_qty = $("#Preq_qty").val();
				var item_id = $("#Pitem_id").val();
				var item_name = $("#Pitem_name").val();
				//alert(item_id);
				if(item_id == "" || item_id == null ){
					
					alert("Select Item ID.");
					
					}else if(item_name == "" || item_name == null ){
					
					alert("Select Item Name.");
					
					}else if(req_qty == "" || req_qty == null ){
					
					alert("Add Require Quantity.");
					
					}else{
					add_row2();
				}
			});
			
			
			
			function add_row()
			{  
				var item_id =document.getElementById("item_id").value;
				var item_name =document.getElementById("item_name").value;
				var item_main_group =document.getElementById("item_main_group").value;
				var req_qty =document.getElementById("req_qty").value;
				var unit =document.getElementById("unit").value;
				var is_calculation=document.getElementById("is_calculation").value;
				var countof_record = document.getElementById("countof_record").value;
				if(is_calculation == 'Y'){
					CalcVal = 'Yes';
					}else{
					CalcVal = 'No';
				}
				
				var table=document.getElementById("data_table");
				var table_len=(table.rows.length)-1;
				var html = '';
				html += "<tr id='row"+table_len+"'>";
				html += "<td id='item_id"+table_len+"'>"+item_id+" <input type='hidden' name='item_id"+table_len+"' value='"+item_id+"'></td>";
				html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name"+table_len+"' value='"+item_name+"'><input type='hidden' name='item_main_group"+table_len+"' class='item_main_group' value='"+item_main_group+"'></td>";
				html += "<td id='Calculation"+table_len+"'>"+CalcVal+" <input type='hidden' class='Status_Cal' name='is_calculation"+table_len+"' value='"+is_calculation+"'></td>";
				html += "<td id='req_qty"+table_len+"'>"+req_qty+" <input type='hidden' class='Quantity' name='req_qty"+table_len+"' value='"+req_qty+"'></td>";
				html += "<td id='unit"+table_len+"'>"+unit+" <input type='hidden' name='unit"+table_len+"' value='"+unit+"'></td>";
				
				//html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
				html += '<td><button type="button" name="edit" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum" id="rownum" value="'+countof_record+'"></td>';
				html += '</tr>';
				var row = table.insertRow(table_len).outerHTML=html;
				
				var temp1 = parseFloat(countof_record) + parseFloat(1);
				
				document.getElementById("countof_record").value=temp1;
				
				var new_rec = $('#new_record').val();
				new_rec = new_rec +","+ item_id
				$('#new_record').val(new_rec);
				
				var NewItemID = $('#AllItemID').val();
				NewItemID = NewItemID +","+ item_id
				$('#AllItemID').val(NewItemID);
				
				
				$(this).parents("tr").find(".edit_row").show();  
				$(this).parents("tr").find(".btn-cancel").remove();  
				$(this).parents("tr").find(".btn-update").remove();
				
				
				document.getElementById("item_id").value="";
				document.getElementById("item_name").value="";
				document.getElementById("item_main_group").value="";
				document.getElementById("req_qty").value="";
				document.getElementById("unit").value="";
				
				document.getElementById("item_name").innerHTML="";
				document.getElementById("req_qty").innerHTML="";
				document.getElementById("unit").innerHTML="";
				document.getElementById("is_calculation").value="Y";
				
				document.getElementById("item_id").focus(); 
				
				GetRMSize();
				GetDoughWeight();
				GetPackets();
				
			}
			function add_row2()
			{  
				var item_id =document.getElementById("Pitem_id").value;
				var item_name =document.getElementById("Pitem_name").value;
				var item_main_group =document.getElementById("Pitem_main_group").value;
				var req_qty =document.getElementById("Preq_qty").value;
				var unit =document.getElementById("Punit").value;
				var is_calculation=document.getElementById("Pis_calculation").value;
				var countof_record = document.getElementById("Pcountof_record").value;
				if(is_calculation == 'Y'){
					CalcVal = 'Yes';
					}else{
					CalcVal = 'No';
				}
				
				var table=document.getElementById("data_table2");
				var table_len=(table.rows.length)-1;
				var html = '';
				html += "<tr id='Prow"+table_len+"'>";
				html += "<td id='Pitem_id"+table_len+"'>"+item_id+" <input type='hidden' name='Pitem_id"+table_len+"' value='"+item_id+"'></td>";
				html += "<td id='Pitem_name"+table_len+"'>"+item_name+" <input type='hidden' name='Pitem_name"+table_len+"' value='"+item_name+"'><input type='hidden' name='Pitem_main_group"+table_len+"' class='Pitem_main_group' value='"+item_main_group+"'></td>";
				html += "<td id='PCalculation"+table_len+"'>"+CalcVal+" <input type='hidden' class='PStatus_Cal' name='Pis_calculation"+table_len+"' value='"+is_calculation+"'></td>";
				html += "<td id='Preq_qty"+table_len+"'>"+req_qty+" <input type='hidden' class='PQuantity' name='Preq_qty"+table_len+"' value='"+req_qty+"'></td>";
				html += "<td id='Punit"+table_len+"'>"+unit+" <input type='hidden' name='Punit"+table_len+"' value='"+unit+"'></td>";
				
				//html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
				html += '<td><button type="button" name="Pedit" id="Premove" class="btn btn-xs btn-danger Premove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="Prownum" id="Prownum" value="'+countof_record+'"></td>';
				html += '</tr>';
				var row = table.insertRow(table_len).outerHTML=html;
				
				var temp1 = parseFloat(countof_record) + parseFloat(1);
				
				document.getElementById("Pcountof_record").value=temp1;
				
				var new_rec = $('#new_record').val();
				new_rec = new_rec +","+ item_id
				$('#new_record').val(new_rec);
				
				var NewItemID = $('#AllItemID').val();
				NewItemID = NewItemID +","+ item_id
				$('#AllItemID').val(NewItemID);
				
				
				$(this).parents("tr").find(".edit_row").show();  
				$(this).parents("tr").find(".btn-cancel").remove();  
				$(this).parents("tr").find(".btn-update").remove();
				
				
				document.getElementById("Pitem_id").value="";
				document.getElementById("Pitem_name").value="";
				document.getElementById("Pitem_main_group").value="";
				document.getElementById("Preq_qty").value="";
				document.getElementById("Punit").value="";
				
				document.getElementById("Pitem_name").innerHTML="";
				document.getElementById("Preq_qty").innerHTML="";
				document.getElementById("Punit").innerHTML="";
				document.getElementById("Pis_calculation").value="Y";
				
				document.getElementById("Pitem_id").focus(); 
				
				GetRMSize();
				GetDoughWeight();
				GetPackets();
				
			}
			
		}); 
		
	</script>
	<script type="text/javascript">
		$('#qtytoproduce').on('keypress',function (event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
				event.preventDefault();
			}
			var input = $(this).val();
			if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
				event.preventDefault();
			}
		});
	</script>
	<script type='text/javascript'>
		
		$(document).ready(function(){
			
			// Set validation for Accout Group Name form
			appValidateForm($('#receipe_edit_form'), {
				item_code: 'required',
				ItemName: 'required',
				number_packets: 'required',
			});
			$('#item_code').on('focus',function(){
				
				$('#item_code').val('');
				$('#item_desc').val('');
				$('#ItemName').val('');
				$('#unit1').val('');
				$('#unit_f_g').val('');
				$('#is_calculation').val('Y');
				
			});
			$('#item_code').on('blur', function () {
				
				var curr_val = $(this).val();
				if(curr_val == ""){
					
					}else{
					// Fetch data
					$.ajax({
						url: "<?=base_url()?>admin/production/itemDetails_by_itemcode",
						type: 'post',
						dataType: "json",
						data: {
							search: curr_val
						},
						
						success: function( data ) {
							if(data == null){
								alert('Item not found...');
								$('#item_code').val('');
								$('#item_desc').val('');
								$('#ItemName').val('');
								$('#unit1').val('');
								$('#unit_f_g').val('');
								}else{
								$('#item_code').val(data.item_code);
								$('#ItemName').val(data.description);
								$('#item_desc').val(data.description);
								$('#unit1').val(data.unit); // save selected id to input
								$('#unit_f_g').val(data.unit); // save selected id to input
							}
							//response( data );
							
						}
						
					});
				}
				
			});
			// Initialize 
			//By Item Code
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
					// Set selection
					$('#item_code').val(ui.item.value); // display the selected text
					$('#ItemName').val(ui.item.label); // save selected id to input
					$('#item_desc').val(ui.item.label); // save selected id to input
					$('#unit_f_g').val(ui.item.units); // save selected id to input
					$('#unit_f_g1').val(ui.item.units); // save selected id to input
					
					return false;
				}
			});
			
			//By Item Code
			$( "#item_id" ).autocomplete({
				source: function( request, response ) {
					// Fetch data
					var maingroup = 2;
					$.ajax({
						url: "<?=base_url()?>admin/production/ItemListReceipe",
						type: 'post',
						dataType: "json",
						data: {
							search: request.term,maingroup:maingroup
						},
						success: function( data ) {
							response( data );
						}
					});
				},
				select: function (event, ui) {
					// Set selection
					var AllItemIDs = $("#AllItemID").val();
					let AllItemIDs_array = AllItemIDs.split(",");
					if(AllItemIDs_array.includes(ui.item.value)){
						alert("Item already added");
						$('#item_id').val('');
						$('#item_name').val('');
						$('#item_main_group').val('');
						return false;
						}else{
						$('#item_id').val(ui.item.value); // display the selected text
						$('#item_name').val(ui.item.label); // save selected id to input
						$('#item_main_group').val(ui.item.MainGrpID); // save selected id to input
						$('#unit').val(ui.item.unit); // save selected id to input
						$('#is_calculation').focus();
						return false;
					}
				}
			});
			$( "#Pitem_id" ).autocomplete({
				source: function( request, response ) {
					// Fetch data
					var maingroup = 3;
					$.ajax({
						url: "<?=base_url()?>admin/production/ItemListReceipe",
						type: 'post',
						dataType: "json",
						data: {
							search: request.term,maingroup:maingroup
						},
						success: function( data ) {
							response( data );
						}
					});
				},
				select: function (event, ui) {
					// Set selection
					var AllItemIDs = $("#AllItemID").val();
					let AllItemIDs_array = AllItemIDs.split(",");
					if(AllItemIDs_array.includes(ui.item.value)){
						alert("Item already added");
						$('#Pitem_id').val('');
						$('#Pitem_name').val('');
						$('#Pitem_main_group').val('');
						return false;
						}else{
						$('#Pitem_id').val(ui.item.value); // display the selected text
						$('#Pitem_name').val(ui.item.label); // save selected id to input
						$('#Pitem_main_group').val(ui.item.MainGrpID); // save selected id to input
						$('#Punit').val(ui.item.unit); // save selected id to input
						$('#Pis_calculation').focus();
						return false;
					}
				}
			});
			
			// For Row Item blur
			$('#item_id').on('blur', function () {
				
				var curr_val = $(this).val();
				if(curr_val == ""){
					
					}else{
					var maingroup = 2;
					// Fetch data
					$.ajax({
						url: "<?=base_url()?>admin/production/itemDetails_by_itemcode",
						type: 'post',
						dataType: "json",
						data: {
							search: curr_val,maingroup:maingroup
						},
						
						success: function( data ) {
							if(data == null){
								alert('Item not found...');
								$('#item_id').val('');
								$('#item_name').val('');
								$('#item_main_group').val('');
								$('#req_qty').val('');
								$('#unit').val('');
								$('#item_id').focus();
								}else{
								var AllItemIDs = $("#AllItemID").val();
								let AllItemIDs_array = AllItemIDs.split(",");
								if(AllItemIDs_array.includes(data.item_code)){
									alert("Item already added"); 
									$('#item_id').val('');
									$('#item_name').val('');
									//$('#item_id').focus();
									return false;
									}else{
									$('#item_id').val(data.item_code);
									$('#item_name').val(data.description);
									$('#item_main_group').val(data.MainGrpID);
									$('#unit').val(data.unit); // save selected id to input
									$('#is_calculation').focus();
									return false;
								}
							}
						}
						
					});
				}
				
			});
			$('#Pitem_id').on('blur', function () {
				
				var curr_val = $(this).val();
				if(curr_val == ""){
					
					}else{
					
					var maingroup = 3;
					// Fetch data
					$.ajax({
						url: "<?=base_url()?>admin/production/itemDetails_by_itemcode",
						type: 'post',
						dataType: "json",
						data: {
							search: curr_val,maingroup:maingroup
						},
						
						success: function( data ) {
							if(data == null){
								alert('Item not found...');
								$('#Pitem_id').val('');
								$('#Pitem_name').val('');
								$('#Pitem_main_group').val('');
								$('#Preq_qty').val('');
								$('#Punit').val('');
								$('#Pitem_id').focus();
								}else{
								var AllItemIDs = $("#AllItemID").val();
								let AllItemIDs_array = AllItemIDs.split(",");
								if(AllItemIDs_array.includes(data.item_code)){
									alert("Item already added"); 
									$('#Pitem_id').val('');
									$('#Pitem_name').val('');
									//$('#item_id').focus();
									return false;
									}else{
									$('#Pitem_id').val(data.item_code);
									$('#Pitem_name').val(data.description);
									$('#Pitem_main_group').val(data.MainGrpID);
									$('#Punit').val(data.unit); // save selected id to input
									$('#Pis_calculation').focus();
									return false;
								}
							}
						}
						
					});
				}
				
			});
			
			//By Item Name
			$( "#item_name" ).autocomplete({
				source: function( request, response ) {
					var maingroup = 2;
					// Fetch data
					$.ajax({
						url: "<?=base_url()?>admin/production/ItemListReceipe",
						type: 'post',
						dataType: "json",
						data: {
							search: request.term,maingroup:maingroup
						},
						success: function( data ) {
							response( data );
						}
					});
				},
				select: function (event, ui) {
					// Set selection
					var AllItemIDs = $("#AllItemID").val();
					let AllItemIDs_array = AllItemIDs.split(",");
					if(AllItemIDs_array.includes(ui.item.value)){
						alert("Item already added"); 
						$('#item_id').val('');
						$('#item_name').val('');        
						}else{
						$('#item_id').val(ui.item.value); // display the selected text
						$('#item_name').val(ui.item.label); // save selected id to input
						$('#item_main_group').val(ui.item.MainGrpID); // save selected id to input
						$('#unit').val(ui.item.unit); // save selected id to input
						$('#is_calculation').focus();
						return false;
					}
				}
			});
			$( "#Pitem_name" ).autocomplete({
				source: function( request, response ) {
					var maingroup = 3;
					// Fetch data
					$.ajax({
						url: "<?=base_url()?>admin/production/ItemListReceipe",
						type: 'post',
						dataType: "json",
						data: {
							search: request.term,maingroup:maingroup
						},
						success: function( data ) {
							response( data );
						}
					});
				},
				select: function (event, ui) {
					// Set selection
					var AllItemIDs = $("#AllItemID").val();
					let AllItemIDs_array = AllItemIDs.split(",");
					if(AllItemIDs_array.includes(ui.item.value)){
						alert("Item already added"); 
						$('#Pitem_id').val('');
						$('#Pitem_name').val('');        
						}else{
						$('#Pitem_id').val(ui.item.value); // display the selected text
						$('#Pitem_name').val(ui.item.label); // save selected id to input
						$('#Pitem_main_group').val(ui.item.MainGrpID); // save selected id to input
						$('#Punit').val(ui.item.unit); // save selected id to input
						$('#Pis_calculation').focus();
						return false;
					}
				}
			});
			
			function load_data(status)
			{
				$.ajax({
					url:"<?php echo admin_url(); ?>production/load_data_for_recipe",
					dataType:"JSON",
					method:"POST",
					data:{status:status},
					beforeSend: function () {
						$('#searchh2').css('display','block');
						$('.table_recipe_report tbody').css('display','none');
					},
					complete: function () {
						$('.table_recipe_report tbody').css('display','');
						$('#searchh2').css('display','none');
					},
					success:function(data){
						var html = '';
						
						for(var count = 0; count < data.length; count++)
						{
							
							var url = "'<?php echo admin_url() ?>production/editRecipe/"+data[count].id+"'";
							html += '<tr onclick="location.href='+url+'">';
							html += '<td style="text-align:center;">'+data[count].item_code+'</td>';
							
							/*var date = data[count].Transdate.substring(0, 10)
								var date_new = date.split("-").reverse().join("/");
								
								html += '<td  style="text-align:center;">'+date_new+'</td>';
							*/
							html += '<td >'+data[count].item_description+'</td>';
							html += '<td  style="text-align:center;">'+data[count].qty+'</td>';
							html += '<td style="text-align:right;">'+data[count].unit+'</td>';
							if(data[count].ActiveDate == null){
								var date_new = '';
								}else{
								var date = data[count].ActiveDate.substring(0, 10);
								var date_new = date.split("-").reverse().join("/");
							}
							html += '<td >'+date_new+'</td>';
							if(data[count].DeActiveDate == null){
								var date_new2 = '';
								}else{
								var date2 = data[count].DeActiveDate.substring(0, 10);
								var date_new2 = date2.split("-").reverse().join("/");
							}
							html += '<td >'+date_new2+'</td>';
							html += '</tr>';
						}
						$('.table_recipe_report tbody').html(html);
						
					}
				});
			}  
			$('#search_data').on('click',function(){
				var status = $('#Status').val();
				load_data(status);
				
			});
			
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
		
	</script>
	<script>
		function decimalNumber(evt) {
			if ((evt.which != 46 || $(this).val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
				evt.preventDefault();
			}
			var input = $(this).val();
			if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3)) {
				evt.preventDefault();
			}
		}
		
		$('#req_qty,#Preq_qty').on('keypress',function (event) {
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
		
		$('#qtytoproduce').on('keypress',function (event) {
			var unit = $('#unit_f_g').val();
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
		function update_record(value){
			// alert(value);
			var update = $('#updated_record').val();
			update = update +","+ value
			$('#updated_record').val(update);
		}
	</script>					