<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
	#imagePreview {
    width: 200px;
    height: 200px;
    border: 1px solid #ccc;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
	}
	#imagePreview img{
    width: 200px;
    height: 200px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
	}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="panel_s">
					<div class="panel-body">
					<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Item Master</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait Create new ItemID...</div>
							</div>
							<br>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="item_code1">
									<small class="req text-danger">* </small>
									<label for="item_code1" class="control-label">Item ID</label>
									<input type="text" readonly id="item_code1" name="item_code1" class="form-control" value="">
								</div>
								<input type="hidden" id="item_code" name="item_code" class="form-control" value="0">
								<span id="lblError" style="color: red"></span>
							</div>
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="description">
									<small class="req text-danger">* </small>
									<label for="description" class="control-label">Item Name</label>
									<input type="text" id="description" name="description" class="form-control" value="">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Botanicalname">
									<label for="Botanicalname" class="control-label">Botanical Name</label>
									<input type="text" id="Botanicalname" name="Botanicalname" class="form-control" value="">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Main Item Group</label>
									<select class="selectpicker" name="MainItemGroup" id="MainItemGroup" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value=""></option>   
										<?php
											foreach ($MainItemGroup as $key => $value) {
											?>
											<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>   
											<?php   
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="control-label" for="SubGroup1">Sub-Group 1</label>
									<select class="selectpicker display-block" data-width="100%" id="SubGroup1" name="SubGroup1" data-none-selected-text="None selected">
										<option value="">None selected</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="control-label" for="SubGroup2">Sub-Group 2</label>
									<select class="selectpicker display-block" data-width="100%" id="SubGroup2" name="SubGroup2" data-none-selected-text="None selected">
										<option value="">None selected</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">HSN Code</label>
									<select class="selectpicker" name="hsn_code" id="hsn_code" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										
										<option value="">None selected</option>
										<?php
											foreach ($hsn as $key => $value) {
											?>
											<option value="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></option>   
											<?php   
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="control-label" for="tax"><?php echo _l('gst'); ?></label>
									<select class="selectpicker display-block" data-width="100%" id="tax" name="tax" data-none-selected-text="<?php echo _l('no_gst'); ?>">
										<!--<option value=""></option>-->
										<?php foreach($taxes as $tax){ ?>
											<option value="<?php echo $tax['id']; ?>" ><?php echo $tax['taxrate']; ?>%</option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="control-label" for="unit">Unit</label>
									<select class="selectpicker display-block" data-width="100%" name="unit" id="unit" data-none-selected-text="<?php echo "None selected"; ?>">
										<option value=""></option>
										<?php
											foreach ($units as $key => $value) {
											?>
											<option value="<?php echo $value['UOMName']; ?>"><?php echo $value['UOMName']; ?></option>   
											<?php   
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Unit Weight(Kg)</label>
									<input type="text" name="weight" id="weight" class="form-control" value="0" onkeypress="return isNumberdecimal(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Packing Weight(Kg)</label>
									<input type="text" name="case_weight" id="case_weight" class="form-control" value="0" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Local Supply In</label>
									<select class="selectpicker" name="local_supply_in" id="local_supply_in" data-width="100%" data-none-selected-text="None selected" data-live-search="false">
										
										<option value="">None selected</option>
										<option value="CR">Crate</option> 
										<option value="CS">Case</option> 
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Outst. Supply In</label>
									<select class="selectpicker" name="outst_supply_in" id="outst_supply_in" data-width="100%" data-none-selected-text="None selected" data-live-search="false">
										<option value="">None selected</option>
										<option value="CR">Crate</option> 
										<option value="CS">Case</option> 
									</select> 
								</div> 
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Crate Qty</label>
									<input type="text" name="crate_qty" id="crate_qty" class="form-control" value="1"  onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Case Qty</label>
									<input type="text" name="case_qty" id="case_qty" class="form-control" value="1" onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Bowl Qty</label>
									<input type="text" name="bowl_qty" id="bowl_qty" class="form-control" value="1" onkeypress="return isNumber(event)">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Min Qty</label>
									<input type="text" name="min_qty" id="min_qty" class="form-control" value="1" onkeypress="return isNumber(event)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Max Qty</label>
									<input type="text" name="max_qty" id="max_qty" class="form-control" value="1" onkeypress="return isNumber(event)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">MRP</label>
									<input type="text" name="mrp" id="mrp" class="form-control" value="0" onkeypress="return isNumberdecimal(event)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Product Life (Days)</label>
									<input type="text" name="min_day" id="min_day" class="form-control" value="0" onkeypress="return isNumber(event)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Product Range</label>
									<select class="selectpicker" id= "product_range" name="product_range" data-width="100%" data-none-selected-text="None selected" data-live-search="false">
										<option value="">None selected</option> 
										<option value="SLP">Short Life Product</option> 
										<option value="LLP">Long Life Product</option> 
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Rack ID</label>
									<select class="selectpicker" name="rack_id" id="rack_id" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>   
										<?php
											foreach ($items_rack as $key => $value) {
											?>
											<option value="<?php echo $value['RackID']; ?>"><?php echo $value['RackName']; ?></option>   
											<?php  
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Sub RackId</label>
									<select class="selectpicker" name="subrack_id" id="subrack_id" data-width="100%" data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										<option value="A">A</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">MonitorStock?</label>
									<select class="selectpicker" id= "monitorstock" name="monitorstock" data-width="100%" data-none-selected-text="None selected" data-live-search="false">
										<option value="">None selected</option>
										<option value="Y">Y</option> 
										<option value="N">N</option> 
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="form-label">Is Active?</label>
									<select class="selectpicker" name="isactive" id="isactive" data-width="100%" data-none-selected-text="None selected" data-live-search="false">
										<option value="">None selected</option>
										<option value="Y">Active</option> 
										<option value="N">Deactive</option> 
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Brand Name</label>
									<input type="text" name="brand_name" id="brand_name" class="form-control" value="">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Is Traceability</label>
									<select class="selectpicker" name="istraceability" id="istraceability" data-width="100%" data-none-selected-text="None selected" data-live-search="false">
										<option value="N">No</option> 
										<option value="Y">Yes</option> 
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Image</label>
									<input type="file" name="image" id="image" class="form-control" accept="image/*">
								</div>
							</div>
							
							<div class="clearfix"></div>
							
							<hr>
							
							<!--BannerImg-->
							<div class="col-md-4 col-lg-4 mb-3" id="bandiv" style="display:none;">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="bannerimg" class="form-label fw-semibold">
                                            Banner Image
                                        </label>
                        
                                        <input  
                                            type="file" 
                                            class="form-control" 
                                            id="bannerimg" 
                                            name="bannerimg"
                                            accept="image/*" >
                                        
                                        <small class="text-danger d-block mt-2" style="font-size:7px;"> 
                                            Recommended image size: <strong>1920 × 552 pixels</strong>
                                        </small>
                                    </div> 
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3" 
                                 style="margin-left:-10px;margin-top:20px;display:none" 
                                 id="bannerimgdiv">
                            
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    id="viewBanerimgbtn">
                                    View Image
                                </button>
                            </div>
                            
							
							<!--TraceabilityImg-->
                            <div class="col-md-4 col-lg-4 mb-3" id="tracestagediv" style="display:none;">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="img_one" class="form-label fw-semibold">
                                            Traceability Stages Image
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="img_one" 
                                            name="img_one"
                                            accept="image/*"
                                        >
                                        
                                        <small class="text-danger d-block mt-2" style="font-size:7px;">
                                            Recommended image size: <strong>2504 × 1070 pixels</strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3" 
                                 style="margin-left:-10px;margin-top:20px;display:none" 
                                 id="stageimgdiv">
                            
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    id="viewStageImgBtn">
                                    View Image
                                </button>
                            
                            </div>
                            
                            <!--Nnutritional Img-->
                            <div class="col-md-4 col-lg-4 mb-3" id="nutridiv" style="display:none;">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="nutri_img" class="form-label fw-semibold">
                                            Nutritional Information Image
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="nutri_img" 
                                            name="nutri_img"
                                            accept="image/*">
                                        
                                        
                                        <small class="text-danger d-block mt-2" style="font-size:7px;">
                                            Recommended image size: <strong>498 × 700 pixels</strong> 
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3" 
                                 style="margin-left:-10px;margin-top:20px;display:none" 
                                 id="nutriimgdiv">
                            
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    id="viewNutriImgbtn">
                                    View Image
                                </button>
                            </div>
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-8" id="ParameterDiv" style="display:none;">
                            <hr>
                                    <div class="row parameter-row">
                                        <div class="col-md-4">
                                           <div class="form-group">
                                                <label class="control-label">Parameter Name</label>
                                                <select name="parameterlabelname[]" class="form-control selectpicker"  data-live-search="true" data-width="100%" title="None Selected">
                                                   
                                        
                                                     <?php if(!empty($ParameterQcList)) { 
                                                        foreach($ParameterQcList as $param) { ?>
                                                            
                                                            <option value="<?= $param['id']; ?>">
                                                                <?= $param['ParameterName']; ?>
                                                            </option>
                                    
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                        </div>
                            
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" 
                                                    class="btn btn-success add-parameterqc" 
                                                    style="margin-top:20px;">+</button>
                                        </div>
                                    </div>
                            </div>
							
							<div class="clearfix"></div>
							<div class="col-md-9">
								<table>
									<thead>
										<tr>
											<th width="40%">CompanyName</th>
											<th width="30%">OpeningStock</th>
											<th width="30%">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($RootCompany as $key => $value) {
												$staff_user_id = $this->session->userdata('staff_user_id');
												$selected_company = $this->session->userdata('root_company');
											?>
											<tr>
												<td width="40%"><?php echo $value['company_name']; ?></td>
												<td width="30%"><input type="text" name="OQTY" id="OQTY<?php echo $value['id']; ?>" value="" class="form-control OQTY" <?php if($staff_user_id !== "3"){ echo "disabled";}?> style="height: 35px;"></td>
												<td width="30%">
													<select class="selectpicker" name="isactiveAll"  id="isactive<?php echo $value['id']; ?>" data-width="100%" data-none-selected-text="-- Item not found --" data-live-search="false">
														<option value= ''>-- Select status --</option>
														<option value="Y">Active</option> 
														<option value="N">Deactive</option> 
													</select>
												</td>
											</tr>
											<?php  
											}
										?>
									</tbody>
								</table>
							</div>
							<div class="col-md-3">
								<div id="imagePreview"></div>
							</div>
							<div class="clearfix"></div>
							<br><br>
							<div class="col-md-12">
								<?php if (has_permission_new('items', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission_new('items', '', 'edit')) {
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
						
						<div class="modal fade Item_List" id="Item_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Item List</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-Item_List tableFixHead2">
											<table class="tree table table-striped table-bordered table-Item_List tableFixHead2" id="table_Item_List" width="100%">
												<thead>
													<tr>
														<th style="text-align:left;" class="sortablePop">Item ID</th>
														<th style="text-align:left;" class="sortablePop">Item Name</th>
														<th style="text-align:left;" class="sortablePop">Unit</th>
														<th style="text-align:left;" class="sortablePop">Main Group</th>
														<th style="text-align:left;" class="sortablePop">SubGroup1 Name</th>
														<th style="text-align:left;" class="sortablePop">SubGroup2 Name</th>
													</tr>
												</thead>
												<tbody id="itemlistbody">
													<?php
														foreach ($table_data as $key => $value) {
														?>
														<!--<tr class="get_ItemID" data-id="<?php echo $value["item_code"]; ?>">
															<td><?php echo $value['item_code'];?></td>
															<td><?php echo $value['description'];?></td>
															<td><?php echo $value["unit"];?></td>
															<td><?php echo $value["group_name"];?></td>
															<td><?php echo $value["subgroup_name"];?></td>
														</tr>-->
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
		$('.updateBtn').hide();
		$('.updateBtn2').hide();
		
		const fileInput = document.getElementById('image');
		const imagePreview = document.getElementById('imagePreview');
		
		fileInput.addEventListener('change', function() {
			const file = this.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function(e) {
					const img = document.createElement('img'); // Create img element
					img.src = e.target.result; // Set src attribute
					img.alt = 'Image Preview'; // Set alt attribute for accessibility
					
					// Clear previous content of imagePreview div
					imagePreview.innerHTML = '';
					
					// Append img element to imagePreview div
					imagePreview.appendChild(img);
				};
				reader.readAsDataURL(file);
				} else {
				// Clear imagePreview div if no file selected
				imagePreview.innerHTML = '';
			}
		});
		
		$("#item_code1,#description").dblclick(function(){
			$('#Item_List').modal('show');
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetAllItemList",
				dataType:"JSON",
				method:"POST",
				beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
				complete: function () {
					$('.searchh2').css('display','none');
				},
				success:function(data){
					$('#itemlistbody').html(data);
				}
			});
			$('#Item_List').on('shown.bs.modal', function () {
				$('#myInput1').focus();
			})
		});
		
		$(document).on('click', '.get_ItemID', function() {
			ItemID = $(this).attr("data-id");
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetItemDetailByID",
				dataType:"JSON",
				method:"POST",
				data:{ItemID:ItemID},
				beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
				complete: function () {
					$('.searchh2').css('display','none');
				},
				success:function(data){
					init_selectpicker();
					$('#item_code1').val(data.item_code);
					$('#item_code').val(data.item_code);
					$('#description').val(data.description);
					$('#crate_qty').val(data.crate_qty);
					$('#case_qty').val(data.case_qty);
					$('#bowl_qty').val(data.bowl_qty);
					$('#min_qty').val(data.min_qty);
					$('#max_qty').val(data.max_qty);
					$('#weight').val(data.weight);
					$('#mrp').val(data.mrp);
					$('#brand_name').val(data.brand_name);
					$('#case_weight').val(data.case_weight);
					$('#min_day').val(data.min_day);
					
					if(data.IsTraceability == "Y")
					{
					    $('#bandiv').show();
					    $('#tracestagediv').show();
					    $('#nutridiv').show();
					    $('#ParameterDiv').show();
					    
					    $('#viewBanerimgbtn').show();
					    $('#viewStageImgBtn').show();
					    $('#viewNutriImgbtn').show();
					}else{
					    $('#bandiv').hide();
					    $('#tracestagediv').hide();
					    $('#nutridiv').hide();
					    $('#ParameterDiv').hide();
					    
					    $('#viewBanerimgbtn').hide();
					    $('#viewStageImgBtn').hide();
					    $('#viewNutriImgbtn').hide();
					}
					
					if(data.TraceStageImg != '' && data.TraceStageImg != null) 
					{
                        var imgUrl = "<?php echo base_url()?>media/Product/" + data.TraceStageImg;
                    
                        $('#viewStageImgBtn').data('imgurl', imgUrl);
                    
                        $('#stageimgdiv').show();
                    
                    } else {
                        $('#stageimgdiv').hide();
                    }
                    
                    if(data.BannerTraceImg != '' && data.BannerTraceImg != null) 
					{
                        var imgUrlBanner = "<?php echo base_url()?>media/Product/" + data.BannerTraceImg;
                    
                        $('#viewBanerimgbtn').data('imgUrlBanner', imgUrlBanner);
                    
                        $('#bannerimgdiv').show();
                    
                    } else {
                        $('#bannerimgdiv').hide();
                    }
                    
                    if(data.NutritionalImg != '' && data.NutritionalImg != null) 
					{
                        var imgUrlNutri = "<?php echo base_url()?>media/Product/" + data.NutritionalImg;
                    
                        $('#viewNutriImgbtn').data('imgUrlNutri', imgUrlNutri);
                    
                        $('#nutriimgdiv').show();
                    
                    } else {
                        $('#nutriimgdiv').hide();
                    }
					
					$('#Botanicalname').val(data.BotanicalName);
					$('#image').val('');
					if (data.image != null) {
						var imageUrl = "<?php echo base_url()?>media/Product/" + data.image;
						var img = "<img src='" + imageUrl + "' class='img-thumbnail'>";
						} else {
						var img = "";
					}
					
					$('#imagePreview').html(img);
					
					
					$('select[name=MainItemGroup]').val(data.MainGrpID);
					$('select[name=MainItemGroup]').attr('disabled',true);
					$('.selectpicker').selectpicker('refresh');
					
					let SubGroup1List = data.SubGroup1List;
					$("#SubGroup1").find('option').remove();
					$("#SubGroup1").selectpicker("refresh");
					$("#SubGroup1").append(new Option('None selected', ''));
					for (var i = 0; i < SubGroup1List.length; i++) {
						
						$("#SubGroup1").append(new Option(SubGroup1List[i].name, SubGroup1List[i].id));
					}
					$('select[name=SubGroup1]').val(data.SubGrpID1);
					$('.selectpicker').selectpicker('refresh');
					
					let SubGroup2List = data.SubGroup2List;
					$("#SubGroup2").find('option').remove();
					$("#SubGroup2").selectpicker("refresh");
					$("#SubGroup2").append(new Option('None selected', ''));
					for (var i = 0; i < SubGroup2List.length; i++) {
						
						$("#SubGroup2").append(new Option(SubGroup2List[i].name, SubGroup2List[i].id));
					}
					
					$('select[name=SubGroup2]').val(data.SubGrpID2);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=tax]').val(data.taxid);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=istraceability]').val(data.IsTraceability);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=unit]').val(data.unit);
					$('.selectpicker').selectpicker('refresh');
					
					
					$('select[name=local_supply_in]').val(data.local_supply_in);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=outst_supply_in]').val(data.outst_supply_in);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=monitorstock]').val(data.monitorstock);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=hsn_code]').val(data.hsn_code);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=rack_id]').val(data.rack_id);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=subrack_id]').val(data.subrack_id);
					$('.selectpicker').selectpicker('refresh');
					
					$('select[name=isactive]').val(data.isactive);
					$('.selectpicker').selectpicker('refresh');
					
					let stockArray = data.stocks;
					if (stockArray && Array.isArray(stockArray)) {
						for (var count = 0; count < stockArray.length; count++) {
							var PlantID = stockArray[count].PlantID;
							$('#OQTY' + PlantID).val(stockArray[count].OQty);
						}
					}
					
					if (data.itemStatus) {
						let itemStatusArray = data.itemStatus;
						if (itemStatusArray && Array.isArray(itemStatusArray)) {
							for (var count = 0; count < itemStatusArray.length; count++) {
								var PlantID = itemStatusArray[count].PlantID;
								var stvalue = itemStatusArray[count].isactive;
								$('select[id=isactive' + PlantID + ']').val(stvalue);
								$('.selectpicker').selectpicker('refresh');
							}
						}
					}
					
					if (data.IsTraceability && data.IsTraceability.trim().toUpperCase() === "Y") 
                    {
                        $('#ParameterDiv').html('<hr>');
                        $('#ParameterDiv').show();
                        
                        if (data.EditParameterList && data.EditParameterList.length > 0) 
                        {
                            $.each(data.EditParameterList, function(index, param) {
                    
                                var isFirst = (index === 0);
                    
                                appendParameterRow(isFirst);
                    
                            });
                    
                            $('.selectpicker').selectpicker('refresh');
                    
                            $('select[name="parameterlabelname[]"]').each(function(i){
                                $(this).val(data.EditParameterList[i].ParameterID);
                            });
                    
                            $('.selectpicker').selectpicker('refresh');
                        }
                        else
                        {
                            appendParameterRow(true);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                    else
                    {
                        $('#ParameterDiv').hide();
                    }
					
					$('.saveBtn').hide();
					$('.updateBtn').show();
					$('.saveBtn2').hide();
					$('.updateBtn2').show();
				}
			});
			$('#Item_List').modal('hide');
		});
		
		function appendParameterRow(isFirst = false)
        {
            var newRow = `
            <div class="row parameter-row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        ${isFirst ? '<label class="control-label">Parameter Name</label>' : ''}
        
                        <select name="parameterlabelname[]" 
                                class="form-control selectpicker"
                                data-live-search="true"
                                data-width="100%"
                                title="None Selected">
        
                            <?php foreach($ParameterQcList as $p) { ?>
                                <option value="<?= $p['id']; ?>">
                                    <?= $p['ParameterName']; ?>
                                </option>
                            <?php } ?>
        
                        </select>
                    </div>
                </div>
        
                <div class="col-md-1 d-flex align-items-end">
                    ${isFirst 
                        ? '<button type="button" class="btn btn-success add-parameterqc" style="margin-top:20px;">+</button>' 
                        : '<button type="button" class="btn btn-danger remove-parameterqc" style="margin-top:20px;">−</button>'}
                </div>
            </div>`;
        
            $('#ParameterDiv').append(newRow);
        }
		
		$(document).on('click', '.add-parameterqc', function () 
		{
		    var currentRow = $(this).closest('.parameter-row');
            var selectedValue = currentRow.find('select[name="parameterlabelname[]"]').val();
            
            if (!selectedValue) {
                alert('Please select parameter first!');
                return false;
            }

            var newRow = `
            <div class="row parameter-row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="parameterlabelname[]" 
                                class="form-control selectpicker" 
                                data-live-search="true" 
                                data-width="100%" 
                                title="None Selected">
                            <?php foreach($ParameterQcList as $param) { ?>
                                <option value="<?= $param['id']; ?>">
                                    <?= $param['ParameterName']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
        
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" 
                            class="btn btn-danger remove-parameterqc" 
                            style="margin-top:20px;">−</button>
                </div>
            </div>`;
        
            $('#ParameterDiv').append(newRow);
        
            $('.selectpicker').selectpicker('refresh');
            preventDuplicateSelection();
        });
        
        $(document).on('click', '.remove-parameterqc', function () {
            $(this).closest('.parameter-row').remove();
            preventDuplicateSelection();
        });
        
        function preventDuplicateSelection() 
        {
            var selectedValues = [];
        
            $('select[name="parameterlabelname[]"]').each(function () {
                var val = $(this).val();
                if (val) {
                    selectedValues.push(val);
                }
            });
        
            $('select[name="parameterlabelname[]"] option').prop('disabled', false);
        
            $('select[name="parameterlabelname[]"]').each(function () {
        
                var current = $(this);
        
                selectedValues.forEach(function (value) {
                    if (current.val() != value) {
                        current.find('option[value="' + value + '"]').prop('disabled', true);
                    }
                });
        
            });
        
            $('.selectpicker').selectpicker('refresh');
        }
		
		$('#istraceability').on('changed.bs.select', function () {
            var selectedValue = $(this).val();
            if(selectedValue === 'Y'){
                $('#bandiv').show();
			    $('#tracestagediv').show();
			    $('#nutridiv').show();
			    $('#ParameterDiv').show();
            } else {
                $('#bandiv').hide();
                $('#tracestagediv').hide();
                $('#nutridiv').hide();
                $('#ParameterDiv').hide();
            }
        });
		
		$(document).on('click', '#viewStageImgBtn', function () {

            var url = $(this).data('imgurl');
        
            if (url) {
                window.open(url, '_blank');
            }
        
        });
        
        $(document).on('click', '#viewBanerimgbtn', function () {

            var url = $(this).data('imgUrlBanner');
        
            if (url) {
                window.open(url, '_blank');
            }
        
        });
        
        $(document).on('click', '#viewNutriImgbtn', function () {

            var url = $(this).data('imgUrlNutri');
        
            if (url) {
                window.open(url, '_blank');
            }
        
        });
		
		// ItemID Typing Validation
		$("#item_code1").keypress(function (e) {
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
		
		
		$('#MainItemGroup').on('change', function() {
			var MainItemGroup = $(this).val();
			//alert(roleid);
			var url = "<?php echo base_url(); ?>admin/invoice_items/GetSubgroup1Data";
			jQuery.ajax({
				type: 'POST',
				url:url,
				data: {MainItemGroup: MainItemGroup},
				dataType:'json',
				success: function(data) {
					$("#SubGroup1").find('option').remove();
					$("#SubGroup1").selectpicker("refresh");
					$("#SubGroup1").append(new Option('None selected', ''));
					for (var i = 0; i < data.length; i++) {
						$("#SubGroup1").append(new Option(data[i].name, data[i].id));
					}
					$('.selectpicker').selectpicker('refresh');
				}
			});
			var url2 = "<?php echo base_url(); ?>admin/invoice_items/GetItemCodeByMainGroup";
			jQuery.ajax({
				type: 'POST',
				url:url2,
				data: {MainItemGroup: MainItemGroup},
				dataType:'json',
				success: function(data) {
					$('#item_code1').val(data);
					$('#item_code').val(data);
				}
			});
		});
		
		$('#SubGroup1').on('change', function() {
			var SubGroup1 = $(this).val();
			//alert(roleid);
			var url = "<?php echo base_url(); ?>admin/invoice_items/GetSubgroup2Data";
			jQuery.ajax({
				type: 'POST',
				url:url,
				data: {SubGroup1: SubGroup1},
				dataType:'json',
				success: function(data) {
					$("#SubGroup2").find('option').remove();
					$("#SubGroup2").selectpicker("refresh");
					$("#SubGroup2").append(new Option('None selected', ''));
					for (var i = 0; i < data.length; i++) {
						$("#SubGroup2").append(new Option(data[i].name, data[i].id));
					}
					$('.selectpicker').selectpicker('refresh');
				}
			});
		});
		
		// Empty and open create mode
		$("#item_code1").focus(function(){
			$('#item_code1').val('');
			$('#item_code').val('');
			$('#description').val('');
			$('#crate_qty').val('');
			$('#case_qty').val('');
			$('#bowl_qty').val('');
			$('#min_qty').val('');
			$('#max_qty').val('');
			$('#weight').val('');
			$('#mrp').val('');
			$('#brand_name').val('');
			$('#case_weight').val('');
			$('#min_day').val('');
			$('#OQTY').val('');
			$('#image').val('');
			$('#imagePreview').html('');
			$('input[name=OQTY]').val('');
			
			
			$('select[name=MainItemGroup]').val('');
			$('select[name=MainItemGroup]').attr('disabled',false);
            $('.selectpicker').selectpicker('refresh'); 
			
			$("#SubGroup1").find('option').remove();
			$("#SubGroup1").append(new Option('None selected', ''));
			$("#SubGroup1").selectpicker("refresh");
			
			$("#SubGroup2").find('option').remove();
			$("#SubGroup2").append(new Option('None selected', ''));
			$("#SubGroup2").selectpicker("refresh");
			
			$('select[name=isactiveAll]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=tax]').val('1');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=unit]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=product_range]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=local_supply_in]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=outst_supply_in]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=monitorstock]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=hsn_code]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=rack_id]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=subrack_id]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=isactive]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('.saveBtn').show();
			$('.saveBtn2').show();
			$('.updateBtn').hide();
			$('.updateBtn2').hide();
			
		});
		
		// Cancel selected data
		$(".cancelBtn").click(function(){
			$('#item_code1').val('');
			$('#item_code').val('');
			$('#description').val('');
			$('#crate_qty').val('');
			$('#case_qty').val('');
			$('#bowl_qty').val('');
			$('#min_qty').val('');
			$('#max_qty').val('');
			$('#weight').val('');
			$('#mrp').val('');
			$('#brand_name').val('');
			$('#case_weight').val('');
			$('#min_day').val('');
			$('#OQTY').val('');
			$('#image').val('');
			$('#imagePreview').html('');
			$('input[name=OQTY]').val('');
			
			
			$('select[name=MainItemGroup]').val('');
			$('select[name=MainItemGroup]').attr('disabled',false);
            $('.selectpicker').selectpicker('refresh'); 
			
			$("#SubGroup1").find('option').remove();
			$("#SubGroup1").append(new Option('None selected', ''));
			$("#SubGroup1").selectpicker("refresh");
			
			$("#SubGroup2").find('option').remove();
			$("#SubGroup2").append(new Option('None selected', ''));
			$("#SubGroup2").selectpicker("refresh");
			
			$('select[name=isactiveAll]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=tax]').val('1');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=unit]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=product_range]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=local_supply_in]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=outst_supply_in]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=monitorstock]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=hsn_code]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=rack_id]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=subrack_id]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('select[name=isactive]').val('');
			$('.selectpicker').selectpicker('refresh');
			
			$('.saveBtn').show();
			$('.saveBtn2').show();
			$('.updateBtn').hide();
			$('.updateBtn2').hide();
			
		});
		
		// Save New Item
		$('.saveBtn').on('click',function(){ 
			item_code = $('#item_code1').val();
			description = $('#description').val();
			crate_qty = $('#crate_qty').val();
			case_qty = $('#case_qty').val();
			bowl_qty = $('#bowl_qty').val();
			min_qty = $('#min_qty').val();
			max_qty = $('#max_qty').val();
			weight = $('#weight').val();
			mrp = $('#mrp').val();
			brand_name = $('#brand_name').val();
			case_weight = $('#case_weight').val();
			min_day = $('#min_day').val();
			tax = $('#tax').val();
			unit = $('#unit').val();
			MainItemGroup = $('#MainItemGroup').val();
			SubGroup1 = $('#SubGroup1').val();
			SubGroup2 = $('#SubGroup2').val();
			local_supply_in = $('#local_supply_in').val();
			outst_supply_in = $('#outst_supply_in').val();
			monitorstock = $('#monitorstock').val();
			hsn_code = $('#hsn_code').val();
			rack_id = $('#rack_id').val();
			subrack_id = $('#subrack_id').val();
			isactive = $('#isactive').val();
			OQty = $('input[name=OQTY]').val();
			Botanicalname = $('#Botanicalname').val();
			IsTraceability = $('#istraceability').val();
			
			var parameterArray = [];
            $('select[name="parameterlabelname[]"]').each(function () {
                if ($(this).val() != '' && $(this).val() != null) {
                    parameterArray.push($(this).val());
                }
            });
			
			var ImgInput = document.getElementById('img_one');
            var Imgfile1 = ImgInput.files[0];
            
            var BannerInput = document.getElementById('bannerimg');
            var BanImgfile2 = BannerInput.files[0];
            
            var NutriInput = document.getElementById('nutri_img');
            var NutriImgfile3 = NutriInput.files[0];
            
			var OQty = '';
			var favorite = [];
			$.each($("input[name='OQTY']"), function(){
				if($(this).val() == ''){
					favorite.push('0');
					}else{
					favorite.push($(this).val());
				}
			});
			var OQty_new = favorite.join(",");
			
			ItemStatus = $('input[name=isactiveAll]').val();
			var ItemStatus = '';
			var favorite1 = [];
			$.each($("select[name='isactiveAll']"), function(){
				if($(this).val() == ''){
					favorite1.push('0');
					}else{
					favorite1.push($(this).val());
				}
			});
			var ItemStatus_new = favorite1.join(",");
			
			ItemID = $(this).val();
			if(item_code == ''){
				alert('please enter ItemID');
				$('#item_code1').focus();
				}else if(description == ""){
				alert('please enter Item Name');
				$('#description').focus();
				}else if(MainItemGroup == ""){
				alert('please select MainGroup');
				$('#MainItemGroup').focus();
				}else if(SubGroup1 == ""){
				alert('please select SubGroup1');
				$('#SubGroup1').focus();
				}else if(SubGroup2 == ""){
				alert('please select SubGroup2');
				$('#SubGroup2').focus();
				}else if(hsn_code == ""){
				alert('please select HSN');
				$('#hsn_code').focus();
				}else if(tax == ""){
				alert('please select GST');
				$('#tax').focus();
				}else if(unit == ""){
				alert('please select Unit');
				$('#unit').focus();
				}else if(weight == ""){
				alert('please Enter Unit Weight');
				$('#weight').focus();
				}else if(case_weight == ""){
				alert('please Enter Case Weight');
				$('#weight').focus();
				}else if(local_supply_in == ""){
				alert('please Select Local Supply');
				$('#local_supply_in').focus();
				}else if(outst_supply_in == ""){
				alert('please Select Outst. Supply');
				$('#local_supply_in').focus();
				}else if(bowl_qty == ""){
				alert('please Enter Bowl Qty');
				$('#bowl_qty').focus();
				}else if(min_qty == ""){
				alert('please Enter Min Qty');
				$('#min_qty').focus();
				}else if(max_qty == ""){
				alert('please Enter Max Qty');
				$('#max_qty').focus();
				}else if(monitorstock == ""){
				alert('please Select Monitor Stock');
				$('#monitorstock').focus();
				}else if(isactive == ""){
				alert('please Select Is Active');
				$('#isactive').focus();
				}else{
				
				var postData = {
					item_code: item_code,
					description: description,
					MainItemGroup: MainItemGroup,
					SubGroup1: SubGroup1,
					SubGroup2: SubGroup2,
					crate_qty: crate_qty,
					case_qty: case_qty,
					bowl_qty: bowl_qty,
					min_qty: min_qty,
					max_qty: max_qty,
					weight: weight,
					mrp: mrp,
					brand_name: brand_name,
					min_day: min_day,
					case_weight: case_weight,
					tax: tax,
					unit: unit,
					local_supply_in: local_supply_in,
					outst_supply_in: outst_supply_in,
					monitorstock: monitorstock,
					hsn_code: hsn_code,
					rack_id: rack_id,
					subrack_id: subrack_id,
					isactive: isactive,
					OQty: OQty_new,
					ItemStatus_new: ItemStatus_new,
					Botanicalname: Botanicalname,
					IsTraceability:IsTraceability,
					parameterlabelname: parameterArray
				};
				
				var fileInput = document.getElementById('image');
				
				// Read the selected file
				var file = fileInput.files[0];
				/*if (file) {
					// Create a FileReader to read the file as base64
					var reader = new FileReader();
					
					reader.onloadend = function() {
						// Send the base64 data to the server via AJAX
						var base64Data = reader.result.split(',')[1]; // Extract the base64 data
						postData.image = base64Data;
						makeAjaxRequestAdd(postData);
					};
					reader.readAsDataURL(file);
				}
				else
				{
					makeAjaxRequestAdd(postData);
				}
				
				//posting traceability stage img
				if (Imgfile1) 
				{
                    var reader = new FileReader();
                
                    reader.onloadend = function () {
                
                        var base64Data = reader.result.split(',')[1]; 
                        postData.img_one = base64Data;   
                
                        makeAjaxRequestAdd(postData);
                    };
                
                    reader.readAsDataURL(Imgfile1);
                
                } else {
                    makeAjaxRequestAdd(postData);
                }
                
                //posting Banner img
				if (BanImgfile2) 
				{
                    var reader = new FileReader();
                
                    reader.onloadend = function () {
                
                        var base64Data = reader.result.split(',')[1]; 
                        postData.bannerimg = base64Data;   
                
                        makeAjaxRequest(postData);
                    };
                
                    reader.readAsDataURL(BanImgfile2);
                
                } else {
                    makeAjaxRequest(postData);
                }
                
                //posting Nutritional Image
                if (NutriImgfile3) 
				{
                    var reader = new FileReader();
                
                    reader.onloadend = function () {
                
                        var base64Data = reader.result.split(',')[1]; 
                        postData.nutri_img = base64Data;   
                
                        makeAjaxRequest(postData);
                    };
                
                    reader.readAsDataURL(NutriImgfile3);
                
                } else {
                    makeAjaxRequest(postData);
                }*/
                
                var readersCompleted = 0;
                var totalFiles = 0;
                
                function checkAndSend() {
                    if (readersCompleted === totalFiles) {
                        makeAjaxRequestAdd(postData);
                    }
                }
                
                function readFile(file, keyName) {
                    totalFiles++;
                
                    var reader = new FileReader();
                    reader.onloadend = function () {
                        postData[keyName] = reader.result.split(',')[1];
                        readersCompleted++;
                        checkAndSend();
                    };
                    reader.readAsDataURL(file);
                }
                
                if (file) {
                    readFile(file, 'image');
                }

                // Stage Image
                if (Imgfile1) {
                    readFile(Imgfile1, 'img_one');
                }
                
                // Banner Image
                if (BanImgfile2) {
                    readFile(BanImgfile2, 'bannerimg');
                }
                
                // Nutri Image
                if (NutriImgfile3) {
                    readFile(NutriImgfile3, 'nutri_img');
                }
                
                // If no images selected
                if (totalFiles === 0) {
                    makeAjaxRequestAdd(postData);
                }
			}
			
		});
		
		function makeAjaxRequestAdd(postData) {
			// Make the Ajax request using data
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/SaveItemID",
				dataType:"JSON",
				method:"POST",
				data:postData,
				beforeSend: function () {
					$('.searchh3').css('display','block');
					$('.searchh3').css('color','blue');
				},
				complete: function () {
					$('.searchh3').css('display','none');
				},
				success:function(data){
					if(data == true){
						alert_float('success', 'Record created successfully...');
						$('#item_code1').val('');
						$('#item_code').val('');
						$('#description').val('');
						$('#crate_qty').val('');
						$('#case_qty').val('');
						$('#bowl_qty').val('');
						$('#min_qty').val('');
						$('#max_qty').val('');
						$('#weight').val('');
						$('#mrp').val('');
						$('#brand_name').val('');
						$('#case_weight').val('');
						$('#min_day').val('');
						$('#OQTY').val('');
						$('#image').val('');
						$('#imagePreview').html('');
						$('input[name=OQTY]').val('');
						$('#Botanicalname').val('');
						
						$('#ParameterDiv').html('');
                        $('#ParameterDiv').hide();
                        $('#bandiv').hide();
					    $('#tracestagediv').hide();
					    $('#nutridiv').hide();
					    $('#viewBanerimgbtn').hide();
					    $('#viewStageImgBtn').hide();
					    $('#viewNutriImgbtn').hide();
						
						$('select[name=istraceability]').val('N');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=MainItemGroup]').val('');
						$('select[name=MainItemGroup]').attr('disabled',false);
						$('.selectpicker').selectpicker('refresh'); 
						
						$("#SubGroup1").find('option').remove();
						$("#SubGroup1").append(new Option('None selected', ''));
						$("#SubGroup1").selectpicker("refresh");
						
						$("#SubGroup2").find('option').remove();
						$("#SubGroup2").append(new Option('None selected', ''));
						$("#SubGroup2").selectpicker("refresh");
						
						$('select[name=isactiveAll]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=tax]').val('1');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=unit]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=product_range]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=local_supply_in]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=outst_supply_in]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=monitorstock]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=hsn_code]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=rack_id]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=subrack_id]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=isactive]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						 $('#img_one').val('');
						 $('#bannerimg').val('');
						
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
		
		// Update Exiting Item
		$('.updateBtn').on('click',function()
		{ 
			item_code = $('#item_code1').val();
			description = $('#description').val();
			crate_qty = $('#crate_qty').val();
			case_qty = $('#case_qty').val();
			bowl_qty = $('#bowl_qty').val();
			min_qty = $('#min_qty').val();
			max_qty = $('#max_qty').val();
			weight = $('#weight').val();
			mrp = $('#mrp').val();
			brand_name = $('#brand_name').val();
			case_weight = $('#case_weight').val();
			min_day = $('#min_day').val();
			tax = $('#tax').val();
			unit = $('#unit').val();
			SubGroup1 = $('#SubGroup1').val();
			SubGroup2 = $('#SubGroup2').val();
			local_supply_in = $('#local_supply_in').val();
			outst_supply_in = $('#outst_supply_in').val();
			monitorstock = $('#monitorstock').val();
			hsn_code = $('#hsn_code').val();
			rack_id = $('#rack_id').val();
			subrack_id = $('#subrack_id').val();
			isactive = $('#isactive').val();
			OQty = $('input[name=OQTY]').val();
			
			var ImgInput = document.getElementById('img_one');
            var Imgfile1 = ImgInput.files[0];
            
            var BannerInput = document.getElementById('bannerimg');
            var BanImgfile2 = BannerInput.files[0];
            
            var NutriInput = document.getElementById('nutri_img');
            var NutriImgfile3 = NutriInput.files[0];
			
			Botanicalname = $('#Botanicalname').val();
			IsTraceability = $('#istraceability').val();
			
		    var parameterArray = [];
            $('select[name="parameterlabelname[]"]').each(function () {
                if ($(this).val() != '' && $(this).val() != null) {
                    parameterArray.push($(this).val());
                }
            });
			
			var OQty = '';
			var favorite = [];
			$.each($("input[name='OQTY']"), function(){
				if($(this).val() == ''){
					favorite.push('0');
					}else{
					favorite.push($(this).val());
				}
			});
			var OQty_new = favorite.join(",");
			
			ItemStatus = $('input[name=isactiveAll]').val();
			var ItemStatus = '';
			var favorite1 = [];
			$.each($("select[name='isactiveAll']"), function(){
				if($(this).val() == ''){
					favorite1.push('0');
					}else{
					favorite1.push($(this).val());
				}
			});
			var ItemStatus_new = favorite1.join(",");
			if(item_code == ''){
				alert('please enter ItemID');
				$('#item_code1').focus();
				}else if(description == ""){
				alert('please enter Item Name');
				$('#description').focus();
				}else if(SubGroup1 == ""){
				alert('please select SubGroup1');
				$('#SubGroup1').focus();
				}else if(SubGroup2 == ""){
				alert('please select SubGroup2');
				$('#SubGroup2').focus();
				}else if(hsn_code == ""){
				alert('please select HSN');
				$('#hsn_code').focus();
				}else if(tax == ""){
				alert('please select GST');
				$('#tax').focus();
				}else if(unit == ""){
				alert('please select Unit');
				$('#unit').focus();
				}else if(weight == ""){
				alert('please Enter Unit Weight');
				$('#weight').focus();
				}else if(case_weight == ""){
				alert('please Enter Case Weight');
				$('#weight').focus();
				}else if(local_supply_in == ""){
				alert('please Select Local Supply');
				$('#local_supply_in').focus();
				}else if(outst_supply_in == ""){
				alert('please Select Outst. Supply');
				$('#local_supply_in').focus();
				}else if(bowl_qty == ""){
				alert('please Enter Bowl Qty');
				$('#bowl_qty').focus();
				}else if(min_qty == ""){
				alert('please Enter Min Qty');
				$('#min_qty').focus();
				}else if(max_qty == ""){
				alert('please Enter Max Qty');
				$('#max_qty').focus();
				}else if(monitorstock == ""){
				alert('please Select Monitor Stock');
				$('#monitorstock').focus();
				}else if(isactive == ""){
				alert('please Select Is Active');
				$('#isactive').focus();
				}else{
				var postData = {
					item_code: item_code,
					description: description,
					crate_qty: crate_qty,
					case_qty: case_qty,
					bowl_qty: bowl_qty,
					min_qty: min_qty,
					max_qty: max_qty,
					weight: weight,
					mrp: mrp,
					brand_name: brand_name,
					min_day: min_day,
					case_weight: case_weight,
					tax: tax,
					unit: unit,
					SubGroup1: SubGroup1,
					SubGroup2: SubGroup2,
					local_supply_in: local_supply_in,
					outst_supply_in: outst_supply_in,
					monitorstock: monitorstock,
					hsn_code: hsn_code,
					rack_id: rack_id,
					subrack_id: subrack_id,
					isactive: isactive,
					OQty: OQty_new,
					ItemStatus_new: ItemStatus_new,
					Botanicalname: Botanicalname,
					IsTraceability: IsTraceability,
					parameterlabelname: parameterArray
				};
				
			    var fileInput = document.getElementById('image');
				
				// Read the selected file
				var file = fileInput.files[0];
				/*if (file) {
					// Create a FileReader to read the file as base64
					var reader = new FileReader();
					
					reader.onloadend = function() {
						// Send the base64 data to the server via AJAX
						var base64Data = reader.result.split(',')[1]; // Extract the base64 data
						postData.image = base64Data;
						makeAjaxRequest(postData);
					};
					reader.readAsDataURL(file);
				}
				else
				{
					makeAjaxRequest(postData);
				}*/
				
				//posting traceability stage img
				/*if (Imgfile1) 
				{
                    var reader = new FileReader();
                
                    reader.onloadend = function () {
                
                        var base64Data = reader.result.split(',')[1]; 
                        postData.img_one = base64Data;   
                
                        makeAjaxRequest(postData);
                    };
                
                    reader.readAsDataURL(Imgfile1);
                
                } else {
                    makeAjaxRequest(postData);
                }
                
                //posting Banner img
				if (BanImgfile2) 
				{
                    var reader = new FileReader();
                
                    reader.onloadend = function () {
                
                        var base64Data = reader.result.split(',')[1]; 
                        postData.bannerimg = base64Data;   
                
                        makeAjaxRequest(postData);
                    };
                
                    reader.readAsDataURL(BanImgfile2);
                
                } else {
                    makeAjaxRequest(postData);
                }
                
                //posting Nutritional Image
                if (NutriImgfile3) 
				{
                    var reader = new FileReader();
                
                    reader.onloadend = function () {
                
                        var base64Data = reader.result.split(',')[1]; 
                        postData.nutri_img = base64Data;   
                
                        makeAjaxRequest(postData);
                    };
                
                    reader.readAsDataURL(NutriImgfile3);
                
                } else {
                    makeAjaxRequest(postData);
                }*/
                
                var readersCompleted = 0;
                var totalFiles = 0;
                
                function checkAndSend() {
                    if (readersCompleted === totalFiles) {
                        makeAjaxRequest(postData);
                    }
                }
                
                function readFile(file, keyName) {
                    totalFiles++;
                
                    var reader = new FileReader();
                    reader.onloadend = function () {
                        postData[keyName] = reader.result.split(',')[1];
                        readersCompleted++;
                        checkAndSend();
                    };
                    reader.readAsDataURL(file);
                }
                
                if (file) {
                    readFile(file, 'image');
                }

                // Stage Image
                if (Imgfile1) {
                    readFile(Imgfile1, 'img_one');
                }
                
                // Banner Image
                if (BanImgfile2) {
                    readFile(BanImgfile2, 'bannerimg');
                }
                
                // Nutri Image
                if (NutriImgfile3) {
                    readFile(NutriImgfile3, 'nutri_img');
                }
                
                // If no images selected
                if (totalFiles === 0) {
                    makeAjaxRequest(postData);
                }
			}
		});
		function makeAjaxRequest(postData) {
			// Make the Ajax request using data
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/UpdateItemID",
				dataType:"JSON",
				method:"POST",
				enctype: 'multipart/form-data',
				data:postData,
				beforeSend: function () {
					$('.searchh3').css('display','block');
					$('.searchh3').css('color','blue');
				},
				complete: function () {
					$('.searchh3').css('display','none');
				},
				success:function(data){
					if(data == true){
						alert_float('success', 'Record updated successfully...');
						$('#item_code1').val('');
						$('#item_code').val('');
						$('#description').val('');
						$('#crate_qty').val('');
						$('#case_qty').val('');
						$('#bowl_qty').val('');
						$('#min_qty').val('');
						$('#max_qty').val('');
						$('#weight').val('');
						$('#mrp').val('');
						$('#brand_name').val('');
						$('#case_weight').val('');
						$('#min_day').val('');
						$('#OQTY').val('');
						$('#image').val('');
						$('#imagePreview').html('');
						$('input[name=OQTY]').val('');
						
						$('#ParameterDiv').html('');
                        $('#ParameterDiv').hide();
                        
                        $('#bandiv').hide();
					    $('#tracestagediv').hide();
					    $('#nutridiv').hide();
					    $('#viewBanerimgbtn').hide();
					    $('#viewStageImgBtn').hide();
					    $('#viewNutriImgbtn').hide();
						
						$('#Botanicalname').val('');
						
						$('select[name=istraceability]').val('N');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=MainItemGroup]').val('');
						$('select[name=MainItemGroup]').attr('disabled',false);
						$('.selectpicker').selectpicker('refresh'); 
						
						$("#SubGroup1").find('option').remove();
						$("#SubGroup1").append(new Option('None selected', ''));
						$("#SubGroup1").selectpicker("refresh");
						
						$("#SubGroup2").find('option').remove();
						$("#SubGroup2").append(new Option('None selected', ''));
						$("#SubGroup2").selectpicker("refresh");
						
						$('select[name=isactiveAll]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=tax]').val('1');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=unit]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=product_range]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=local_supply_in]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=outst_supply_in]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=monitorstock]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=hsn_code]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=rack_id]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=subrack_id]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						$('select[name=isactive]').val('');
						$('.selectpicker').selectpicker('refresh');
						
						 $('#img_one').val('');
						 $('#bannerimg').val('');
						 $('#nutri_img').val('');
						
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
	
	
</script>

<script>
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_Item_List");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			td4 = tr[i].getElementsByTagName("td")[4];
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
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_Item_List tbody");
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
	
	function isNumberdecimal(evt) {
		evt = evt || window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		
		// Allow only numeric input and the decimal point
		if (charCode !== 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		
		return true;
	}
</script>

<script type="text/javascript">
	$('.OQTY').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3 )) {
			event.preventDefault();
		}
	});
</script>
<style>
	
	#item_code1 {
    text-transform: uppercase;
	}
	#table_Item_List td:hover {
    cursor: pointer;
	}
	#table_Item_List tr:hover {
    background-color: #ccc;
	}
	
    .table-Item_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Item_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Item_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>