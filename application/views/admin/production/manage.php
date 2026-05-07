<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php hooks()->do_action('before_items_page_content'); ?>
						
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Production</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Recipe</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="clearfix"></div>
						<?php echo form_open('admin/production/add',array('id'=>'receipe_add_form')); ?>
						<input type="hidden" name="item_desc" id="item_desc">
						<div class="row">
							
							<div class="col-md-2">
								<?php echo render_input('item_code','Recipe For'); ?>
								
								<div class="" id="serchh" style="display:none;">Serching</div>
							</div>
							<div class="col-md-3">
								<?php
									$attr = array('disabled'=>true);
								?>
								<?php echo render_input('ItemName','Recipe Name','','',$attr); ?>
							</div>
							
							
							<div class="col-md-2">
								<?php 
									$attr = array(
									'disabled' =>true
									);
								?>
								<?php echo render_input('unit1','Measured In','','',$attr); ?>
								<input type="hidden" name="unit_f_g" id="unit_f_g" value="">    
							</div>
							<div class="col-md-1" style="margin-top: 20px;">
								<?php if(has_permission_new('recipe','','view')){ ?>
									<a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>
									<?php
									}
								?>
							</div>	
							<input type="hidden" value="0" name="countof_record" id="countof_record">
							<input type="hidden" value="0" name="Pcountof_record" id="Pcountof_record">
							<input type="hidden" value="2" name="sub_group" id="sub_group">
						</div>
						<div class="row">
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="std_yield">
									<label for="std_yield" class="control-label">Std. Yield</label>
									<input type="text" name="std_yield" id="std_yield" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="dough_wastage_per">
									<label for="dough_wastage_per" class="control-label">Dough Wastage %</label>
									<input type="text" name="dough_wastage_per" onblur="GetDoughWeight(),GetPackets()" id="dough_wastage_per" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="yield_wastage_per">
									<label for="yield_wastage_per" class="control-label">Yield Of Packate Wastage %</label>
									<input type="text" name="yield_wastage_per" onblur="GetDoughWeight(),GetPackets()" id="yield_wastage_per" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="env_temp">
									<label for="env_temp" class="control-label">Env. Temp.</label>
									<input type="text" name="env_temp" id="env_temp" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="env_humidity">
									<label for="env_humidity" class="control-label">Env. Humidity</label>
									<input type="text" name="env_humidity" id="env_humidity" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="water_temp">
									<label for="water_temp" class="control-label">Water Temp</label>
									<input type="text" name="water_temp" id="water_temp" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="batch_size">
									<label for="batch_size" class="control-label">Batch Size (KG) In Flour base</label>
									<input type="text" name="batch_size" id="batch_size" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="grammage_product">
									<label for="grammage_product" class="control-label">Grammage Of The Product (in gm)</label>
									<input type="text" name="grammage_product" onblur="GetMoistureLossPost()" id="grammage_product" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="dough_dividing_weight">
									<label for="dough_dividing_weight" class="control-label">Dough Dividing Weight</label>
									<input type="text" name="dough_dividing_weight" onblur="GetMoistureLossPost(),VolumeRatio(),GetPackets()" id="dough_dividing_weight" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="mould_length">
									<label for="mould_length" class="control-label">Mould Lenght</label>
									<input type="text" name="mould_length" id="mould_length" onblur="VolumeRatio()" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="mould_width">
									<label for="mould_width" class="control-label">Mould Width</label>
									<input type="text" name="mould_width" id="mould_width" onblur="VolumeRatio()" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="mould_depth">
									<label for="mould_depth" class="control-label">Mould Depth</label>
									<input type="text" name="mould_depth" id="mould_depth" onblur="VolumeRatio()" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="wrapper_per_kg">
									<label for="wrapper_per_kg" class="control-label">Number Of Wrapper Per KG</label>
									<input type="text" name="wrapper_per_kg" id="wrapper_per_kg" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="rate_wrapper_per_kg">
									<label for="rate_wrapper_per_kg" class="control-label">Rate Of Wrapper Per KG</label>
									<input type="text" name="rate_wrapper_per_kg" id="rate_wrapper_per_kg" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="refined_palm_oil">
									<label for="refined_palm_oil" class="control-label">Refind Palm Oil (Mould)</label>
									<input type="text" name="refined_palm_oil" id="refined_palm_oil" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="net_rec_rate">
									<label for="net_rec_rate" class="control-label">Net Recovery Rate</label>
									<input type="text" name="net_rec_rate" id="net_rec_rate" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="ptr">
									<label for="ptr" class="control-label">PTR</label>
									<input type="text" name="ptr" id="ptr" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="moisture_loss_post_ddw">
									<label for="moisture_loss_post_ddw" class="control-label">Moisture Loss Post DDW</label>
									<input type="text" name="moisture_loss_post_ddw" readonly id="moisture_loss_post_ddw" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="volume_ratio_cc">
									<label for="volume_ratio_cc" class="control-label">Volume / Weight Ratio CC</label>
									<input type="text" name="volume_ratio_cc" id="volume_ratio_cc" readonly class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="rm_size">
									<label for="rm_size" class="control-label">RM Size</label>
									<input type="text" name="rm_size" id="rm_size" readonly class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="dough_weight">
									<label for="dough_weight" class="control-label">Dough Weight</label>
									<input type="text" name="dough_weight" id="dough_weight" readonly class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="net_dough_weight">
									<label for="net_dough_weight" class="control-label">Net Dough Weight @Wastage %</label>
									<input type="text" name="net_dough_weight" id="net_dough_weight" readonly class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="number_packets">
									<label for="number_packets" class="control-label">Number Of Packets</label>
									<input type="text" name="number_packets" id="number_packets" readonly class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="yield_packets">
									<label for="yield_packets" class="control-label">Yeild Of Packet @ Wastage %</label>
									<input type="text" name="yield_packets" id="yield_packets" readonly class="form-control" value="" >
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Is_Baking">
									<label for="Is_Baking" class="control-label">Is Baking Required</label>
									<select name="Is_Baking" id="Is_Baking" class="form-control">
										<option value="Y">Yes</option>
										<option value="N">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="status">
									<label for="status" class="control-label">Status</label>
									<select name="status" id="status" class="form-control">
										<option value="Y">Active</option>
										<option value="N">Inactive</option>
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
											
											<th>Item Code</th>
											<th>Item Name</th>
											<th>Is Calculation?</th>
											<th>Req. Qty</th>
											<th>Measured In</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="tbody">
										<tr id="R1">
											
											<td><input type="text" name="item_id" id="item_id" style="width: 60px;border-radius: 2px;height: 30px;">
											<div class="" id="search_item" style="display:none;">Serching</div></td>
											
											<td><input type="text" name="item_name" id="item_name" class="form-control" style="width: 300px;border-radius: 2px;height: 30px;"><input type="hidden" name="item_main_group" id="item_main_group" class="form-control" ></td>
											<td><select type="text" name="is_calculation" id="is_calculation" class="form-control" style="width: 160px;border-radius: 2px;height: 30px;">
												<option value="Y">Yes</option>
												<option value="N">No</option>
											</select></td>
											<td><input type="text" name="req_qty" id="req_qty" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;"></td>
											
											<td><input type="text" name="unit" id="unit" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;"></td>
											
											
											<!--<td><button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button></td>-->
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
							
							<div class="col-md-12">
								<h5>Packaging Materials</h5>
								<table class="table table-striped table-bordered" id="data_table2" width="100%">
									<thead>
										<tr>
											
											<th>Item Code</th>
											<th>Item Name</th>
											<th>Is Calculation?</th>
											<th>Req. Qty</th>
											<th>Measured In</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="Ptbody">
										<tr id="P1">
											
											<td><input type="text" name="Pitem_id" id="Pitem_id" style="width: 60px;border-radius: 2px;height: 30px;">
											<div class="" id="Psearch_item" style="display:none;">Serching</div></td>
											
											<td><input type="text" name="Pitem_name" id="Pitem_name" class="form-control" style="width: 300px;border-radius: 2px;height: 30px;"><input type="hidden" name="Pitem_main_group" id="Pitem_main_group" class="form-control" ></td>
											<td><select type="text" name="Pis_calculation" id="Pis_calculation" class="form-control" style="width: 160px;border-radius: 2px;height: 30px;">
												<option value="Y">Yes</option>
												<option value="N">No</option>
											</select></td>
											<td><input type="text" name="Preq_qty" id="Preq_qty" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;"></td>
											
											<td><input type="text" name="Punit" id="Punit" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;"></td>
											
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-3" style="margin-top:10px;">
								<br>
								<?php if(has_permission_new('recipe','','create')){ ?>
									<button type="submit" id="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
								<?php } ?>
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
					<!--<div class="col-md-6"><br>
						<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
					</div>-->
					
					<div class="col-md-12">
						
						<div class="table_recipe_report">
							
							<table class="tree table table-striped table-bordered table_recipe_report" id="table_recipe_report" width="100%">
								
								<thead>
									<tr>
										<th class="sortablePop" style="text-align:left;">RecipeCode</th>
										<th class="sortablePop" style="text-align:left;">RecipeName</th>
										<th class="sortablePop" style="text-align:left;">Qty.</th>
										<th class="sortablePop" style="text-align:left;">MeasuredIn</th>
										<th class="sortablePop" style="text-align:left;">ActiveDate</th>
										<th class="sortablePop" style="text-align:left;">DeActiveDate</th>
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
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	th     { background: #50607b;color: #fff !important; }
	
	
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
</style>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
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
<script type='text/javascript'>
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
			
			// Simulate or fetch the status input for the current row
			// const statusInput = 'yes';  // Here you can manually set 'yes', but ideally this should come from the actual data in your row
			
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
	$(document).ready(function () {
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
			
			// Removing the current row.
			$(this).closest('tr').remove();
			
			// Decreasing the total number of rows by 1.
			rowIdx--;
			
			GetRMSize();
			GetDoughWeight();
			GetPackets();
		}); 
		$('#Ptbody').on('click', '.Premove', function () {
			
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
			
			var  no = $(this).parents("tr").find('input[name="Prownum"]').val();
			
			// Removing the current row.
			$(this).closest('tr').remove();
			
			// Decreasing the total number of rows by 1.
			rowIdx--;
			
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
			
			var req_qty2 = $("#Preq_qty").val();
			var item_id2 = $("#Pitem_id").val();
			var item_name2 = $("#Pitem_name").val();
			//alert(item_id);
			if(item_id2 == "" || item_id2 == null ){
				
				alert("Select Item ID.");
				
				}else if(item_name2 == "" || item_name2 == null ){
				
				alert("Select Item Name.");
				
				}else if(req_qty2 == "" || req_qty2 == null ){
				
				alert("Add Require Quantity.");
				
				}else{
				add_row2();
			}
		});
		
		// For recipe blur
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
							$('#number_packets').val('');
							$('#unit1').val('');
							$('#unit_f_g').val('');
							$('#item_code').focus();
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
		
		// For Row Item blur
		$('#item_id').on('blur', function () {
			
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
							$('#item_id').val('');
							$('#item_name').val('');
							$('#item_main_group').val('');
							$('#req_qty').val('');
							$('#is_calculation').val('Y');
							$('#unit').val('');
							$('#item_id').focus();
							}else{
							$('#item_id').val(data.item_code);
							$('#item_name').val(data.description);
							$('#item_main_group').val(data.MainGrpID);
							$('#unit').val(data.unit); // save selected id to input
							$('#is_calculation').focus();
						}
					}
					
				});
			}
			
		});
		
		$('#item_id').on('focus',function(){
			
			$('#item_id').val('');
			$('#item_name').val('');
			$('#item_main_group').val('');
			$('#req_qty').val('');
			$('#is_calculation').val('Y');
			$('#unit').val('');
		});
		
		
		function delete_row(no)
		{
			document.getElementById("row"+no+"").outerHTML="";
		}
		
		function add_row()
		{
			var item_id =document.getElementById("item_id").value;
			var item_name=document.getElementById("item_name").value;
			var item_main_group=document.getElementById("item_main_group").value;
			var req_qty=document.getElementById("req_qty").value;
			var unit=document.getElementById("unit").value;
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
			
			
			html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
			
			html += '</tr>';
			var row = table.insertRow(table_len).outerHTML=html;
			
			GetRMSize();
			GetDoughWeight();
			GetPackets();
			
			var countof_record = document.getElementById("countof_record").value;
			var temp1 = parseFloat(countof_record) + parseFloat(1);
			
			document.getElementById("countof_record").value=temp1;
			document.getElementById("item_id").value="";
			document.getElementById("item_name").value="";
			document.getElementById("item_main_group").value="";
			document.getElementById("req_qty").value="";
			document.getElementById("unit").value="";
			document.getElementById("is_calculation").value="Y";
			document.getElementById("item_id").focus();
		}
		function add_row2()
		{
			var item_id =document.getElementById("Pitem_id").value;
			var item_name=document.getElementById("Pitem_name").value;
			var item_main_group=document.getElementById("Pitem_main_group").value;
			var req_qty=document.getElementById("Preq_qty").value;
			var unit=document.getElementById("Punit").value;
			var is_calculation=document.getElementById("Pis_calculation").value;
			var Pcountof_record = document.getElementById("Pcountof_record").value;
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
			html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='Pitem_name"+table_len+"' value='"+item_name+"'><input type='hidden' name='Pitem_main_group"+table_len+"' class='Pitem_main_group' value='"+item_main_group+"'></td>";
			html += "<td id='Calculation"+table_len+"'>"+CalcVal+" <input type='hidden' class='PStatus_Cal' name='Pis_calculation"+table_len+"' value='"+is_calculation+"'></td>";
			html += "<td id='Preq_qty"+table_len+"'>"+req_qty+" <input type='hidden' class='PQuantity' name='Preq_qty"+table_len+"' value='"+req_qty+"'></td>";
			html += "<td id='Punit"+table_len+"'>"+unit+" <input type='hidden' name='Punit"+table_len+"' value='"+unit+"'></td>";
			
			
			html += "<td><input type='button' value='Delete' class='Premove' ><input type='hidden' name='Prownum' id='Prownum'></td>";
			
			html += '</tr>';
			var row = table.insertRow(table_len).outerHTML=html;
			
			GetRMSize();
			GetDoughWeight();
			GetPackets();
			
			var Pcountof_record = document.getElementById("Pcountof_record").value;
			var temp1 = parseFloat(Pcountof_record) + parseFloat(1);
			
			document.getElementById("Pcountof_record").value=temp1;
			document.getElementById("Pitem_id").value="";
			document.getElementById("Pitem_name").value="";
			document.getElementById("Pitem_main_group").value="";
			document.getElementById("Preq_qty").value="";
			document.getElementById("Punit").value="";
			document.getElementById("Pis_calculation").value="Y";
			document.getElementById("Pitem_id").focus();
		}
	}); 
	
</script>
<script type="text/javascript">
	$('#std_yield,#dough_wastage_per,#yield_wastage_per,#conv_cost,#st_cost,#frt_cost,#mrkt_cost,#dmg_cost').on('keypress',function (event) {
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
								} else {
								tr[i].style.display = "none";
							}
						}       
					}
				}
			}
		}
	}
</script>

<script type='text/javascript'>
    $(document).ready(function(){
		
		// Set validation for Accout Group Name form
		appValidateForm($('#receipe_add_form'), {
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
			
		});
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
					},
					complete: function () {
						$('#serchh').css('display','none');
					},
					success: function( data ) {
						
						response( data );
					}
					
				});
			},
			select: function (event, ui) {
                
                $('#item_code').val(ui.item.value);
                $('#item_desc').val(ui.item.label);
                $('#ItemName').val(ui.item.label);
				$('#unit1').val(ui.item.units); // save selected id to input
				$('#unit_f_g').val(ui.item.units); // save selected id to input
				//$('#item_code').focus();
				return false
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
				$('#item_id').val(ui.item.value); // display the selected text
				$('#item_name').val(ui.item.label); // save selected id to input
				$('#item_main_group').val(ui.item.MainGrpID); // save selected id to input
				$('#unit').val(ui.item.unit); // save selected id to input
				$('#item_name').focus();
				return false;
			}
		});
		$( "#Pitem_id" ).autocomplete({
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
				$('#Pitem_id').val(ui.item.value); // display the selected text
				$('#Pitem_name').val(ui.item.label); // save selected id to input
				$('#Pitem_main_group').val(ui.item.MainGrpID); // save selected id to input
				$('#Punit').val(ui.item.unit); // save selected id to input
				$('#Pitem_name').focus();
				return false;
			}
		});
		
		//By Item Name
		$( "#item_name" ).autocomplete({
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
				$('#item_id').val(ui.item.value); // display the selected text
				$('#item_name').val(ui.item.label); // save selected id to input
				$('#item_main_group').val(ui.item.MainGrpID);
				$('#unit').val(ui.item.unit); // save selected id to input
				$('#item_name').focus();
				return false;
			}
		});
		$( "#Pitem_name" ).autocomplete({
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
				$('#Pitem_id').val(ui.item.value); // display the selected text
				$('#Pitem_name').val(ui.item.label); // save selected id to input
				$('#Pitem_main_group').val(ui.item.MainGrpID);
				$('#Punit').val(ui.item.unit); // save selected id to input
				$('#Pitem_name').focus();
				return false;
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
<script type="text/javascript">
	
	
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
	
	$('#req_qty,#req_qty2').on('keypress',function (event) {
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
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_recipe_report tbody");
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

