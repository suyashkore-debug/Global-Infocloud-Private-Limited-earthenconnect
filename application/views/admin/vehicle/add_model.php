<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="vehicle_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title">Edit Vehicle</span>
                    <span class="add-title">Add Vehicle</span>
				</h4>
			</div>
            <?php echo form_open('admin/vehicles/manage',array('id'=>'vehicle_form','enctype'=>'multipart/form-data')); ?>
            <?php echo form_hidden('itemid'); ?>
            <div class="modal-body">
                <div class="row">
					<?php
						$validate = array('required'=>true);
					?>
                    <div class="col-md-3">
						<div class="form-group" app-field-wrapper="VehicleID">
							<label for="VehicleID" class="control-label"><small class="req text-danger">* </small>Vehicle Number</label>
							<input type="text" id="VehicleID"  onkeypress="return isCharacterOrNumber(event)" required name="VehicleID" class="form-control text-uppercase" value="">
						</div>
					</div>
                    <div class="col-md-3">
                        <?php
							$value2 = date('d/m/Y'); 
							//echo render_date_input('StartDate','Start Date',$value2,'text',$validate);
						?>
						<div class="form-group" app-field-wrapper="StartDate">
							<label for="StartDate" class="control-label"><small class="req text-danger">* </small>Start Date</label>
							<div class="input-group date"><input type="text" id="StartDate" name="StartDate" class="form-control datepicker" value="<?= date('d/m/Y');?>" required autocomplete="off">
								<div class="input-group-addon"> <i class="fa fa-calendar calendar-icon"></i></div>
							</div>
						</div>
					</div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><small class="req text-danger">* </small>Vehicle Type</label>
							<select class="selectpicker"  required name="VehicleTypeID" id="VehicleTypeID" data-width="100%" data-none-selected-text="None Selected" data-live-search="true">
                                <option value="0">Own</option> 
                                <option value="1">Transport</option> 
                                <option value="2">Rental</option> 
							</select>
						</div>
                        
					</div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><small class="req text-danger">* </small> Driver Name</label>
                            <select class="selectpicker" name="DriverID" id="DriverID" data-width="100%" data-none-selected-text="None selected" data-live-search="true" required>
								
								<?php
									foreach($DriverList as $driver)
									{
									?>
									<option value="<?= $driver['AccountID']?>" ><?= $driver['firstname']." ".$driver['lastname']?></option> 
									<?php
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group" app-field-wrapper="brand">
							<label for="brand" class="control-label"> <small class="req text-danger">* </small>Brand</label>
							<input type="text" id="brand" name="brand" required class="form-control" value="">
						</div>                    
					</div>
					<div class="col-md-3">
						<div class="form-group" app-field-wrapper="model">
							<label for="model" class="control-label"> <small class="req text-danger">* </small>Model</label>
							<input type="text" id="model" name="model" required class="form-control" value="">
						</div>                    
					</div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><small class="req text-danger">* </small> Fuel Type</label>
                            <select class="selectpicker" name="fuel_type" id="fuel_type" data-width="100%" data-none-selected-text="None selected" data-live-search="true" required>
								<option value="CNG" >CNG</option>
								<option value="Diesel" >Diesel</option>
								<option value="Petrol" >Petrol</option>
								<option value="LPG" >LPG</option>
								<option value="Electric" >Electric</option>
							</select>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group" app-field-wrapper="fuel_capacity">
							<label for="fuel_capacity" class="control-label"> <small class="req text-danger">* </small>Fuel Tank Capacity</label>
							<input type="text" id="fuel_capacity" name="fuel_capacity" required onkeypress="return isNumber2(event)" class="form-control" value="">
						</div>                    
					</div>
					<div class="col-md-3">
						<div class="form-group" app-field-wrapper="mileage">
							<label for="mileage" class="control-label"> <small class="req text-danger">* </small>Mileage</label>
							<input type="text" id="mileage" name="mileage" onkeypress="return isNumber2(event)" required class="form-control" value="">
						</div>                    
					</div>
					<div class="col-md-3">
						<div class="form-group" app-field-wrapper="excel_type">
							<label for="excel_type" class="control-label"> <small class="req text-danger">* </small>Excel Type</label>
							<input type="text" id="excel_type" name="excel_type" onkeypress="return isNumber2(event)" required class="form-control" value="">
						</div>                    
					</div>
					
                    <div class="col-md-3">
                        <?php // echo render_input('VehicleCapacity','Crate Capacity','',$validate); ?>
						<div class="form-group" app-field-wrapper="VehicleCapacity"><label for="VehicleCapacity" class="control-label">Crate Capacity</label><input type="text" id="VehicleCapacity"  onkeypress="return isNumber2(event)" name="VehicleCapacity" class="form-control" value=""></div>
					</div>
					
                    <div class="col-md-3">
                        <?php //echo render_input('VehicleCapacityCase','Case Capacity','',$validate); ?>
						<div class="form-group" app-field-wrapper="VehicleCapacityCase"><label for="VehicleCapacityCase" class="control-label">Case Capacity</label><input type="Array" id="VehicleCapacityCase" name="VehicleCapacityCase" onkeypress="return isNumber2(event)" class="form-control" value=""></div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-3">
                        <div class="form-group" app-field-wrapper="fitnesscertificate">
							<label for="fitnesscertificate" class="control-label">Fitness Certificate</label>
							<input type="file" id="fitnesscertificate" name="fitnesscertificate" class="form-control" value="">
						</div>					
					</div>
					<div class="col-md-3">
                        <?php
						//echo render_date_input('fitness_exp_date','Fitness Expiry Date',date('d/m/Y'),'text'); ?>
						<div class="form-group" app-field-wrapper="fitness_exp_date"><label for="fitness_exp_date" class="control-label">Fitness Expiry Date</label><div class="input-group date"><input type="text" id="fitness_exp_date" name="fitness_exp_date" class="form-control datepicker" value="<?= date('d/m/Y');?>" autocomplete="off"><div class="input-group-addon">
							<i class="fa fa-calendar calendar-icon"></i>
						</div></div></div>
					</div>
					<div class="col-md-3">
                        <div class="form-group" app-field-wrapper="pollutioncertificate">
							<label for="pollutioncertificate" class="control-label">Pollution Certificate</label>
							<input type="file" id="pollutioncertificate" name="pollutioncertificate" class="form-control" value="">
						</div>					
					</div>
					<div class="col-md-3">
                        <?php
						//echo render_date_input('pollution_exp_date','Pollution Expiry Date',date('d/m/Y'),'text'); ?>
						<div class="form-group" app-field-wrapper="pollution_exp_date"><label for="pollution_exp_date" class="control-label">Pollution Expiry Date</label><div class="input-group date"><input type="text" id="pollution_exp_date" name="pollution_exp_date" class="form-control datepicker" value="<?= date('d/m/Y');?>" autocomplete="off"><div class="input-group-addon">
							<i class="fa fa-calendar calendar-icon"></i>
						</div></div></div>
					</div>
					<div class="col-md-3">
                        <div class="form-group" app-field-wrapper="taxduedate">
							<label for="taxduedate" class="control-label"> <small class="req text-danger">* </small>Motor Vehicle Tax Due Date</label>
							<div class="input-group date">
								<input type="text" id="taxduedate" required name="taxduedate" class="form-control datepicker" value="<?= date('d/m/Y');?>" autocomplete="off">
								<div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i></div>
							</div>
						</div>					
					</div>
                    <div class="col-md-3">
						<div class="form-group" app-field-wrapper="insuranceno">
							<label for="insuranceno" class="control-label"> <small class="req text-danger">* </small>Insurance No.</label>
							<input type="text" id="insuranceno"  name="insuranceno" required class="form-control" value="">
						</div>                    
					</div>
					<div class="col-md-3">
						<div class="form-group" app-field-wrapper="insurancetakenby">
							<label for="insurancetakenby" class="control-label"> <small class="req text-danger">* </small>Insurance Taken By</label>
							<input type="text" id="insurancetakenby" name="insurancetakenby" required class="form-control" value="">
						</div>                    
					</div>
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="duedate">
							<label for="duedate" class="control-label"> <small class="req text-danger">* </small>Due Date</label>
							<div class="input-group date">
								<input type="text" id="duedate" required name="duedate" class="form-control datepicker" value="<?= date('d/m/Y');?>" autocomplete="off">
								<div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i></div>
							</div>
						</div>					
					</div>
					
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><small class="req text-danger">* </small> Is Active? </label>
                            <select class="selectpicker" name="ActiveYN" id="ActiveYN" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false" required>
                                <option value="1" selected>Available</option> 
                                <option value="0">Deactive</option> 
                                <option value="2">In-Maintenance</option> 
                                <option value="3">Legal</option> 
							</select>
						</div>
					</div>
                    
                    <div class="col-md-12">
                        <!--<div class="alert alert-warning affect-warning hide">
							<?php echo _l('changing_items_affect_warning'); ?>
						</div>-->
						
						
						<div class="clearfix mbot15"></div>
						
						
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<script>
    // Maybe in modal? Eq convert to invoice or convert proposal to estimate/invoice
    if(typeof(jQuery) != 'undefined'){
        init_item_js();
		} else {
		window.addEventListener('load', function () {
			var initItemsJsInterval = setInterval(function(){
				if(typeof(jQuery) != 'undefined') {
					init_item_js();
					clearInterval(initItemsJsInterval);
				}
			}, 1000);
		});
	}
	// Items add/edit
	function manage_invoice_items(form) {
		var data = $(form).serialize();
		
		var url = form.action;
		$.post(url, data).done(function (response) {
			response = JSON.parse(response);
			if (response.success == true) {
				/*var item_select = $('#item_select');
					if ($("body").find('.accounting-template').length > 0) {
					if (!item_select.hasClass('ajax-search')) {
                    var group = item_select.find('[data-group-id="' + response.item.group_id + '"]');
                    if (group.length == 0) {
					var _option = '<optgroup label="' + (response.item.group_name == null ? '' : response.item.group_name) + '" data-group-id="' + response.item.group_id + '">' + _option + '</optgroup>';
					if (item_select.find('[data-group-id="0"]').length == 0) {
					item_select.find('option:first-child').after(_option);
					} else {
					item_select.find('[data-group-id="0"]').after(_option);
					}
                    } else {
					group.prepend('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">(' + accounting.formatNumber(response.item.rate) + ') ' + response.item.description + '</option>');
                    }
					}
					if (!item_select.hasClass('ajax-search')) {
                    item_select.selectpicker('refresh');
					} else {
					
                    item_select.contents().filter(function () {
					return !$(this).is('.newitem') && !$(this).is('.newitem-divider');
                    }).remove();
					
                    var clonedItemsAjaxSearchSelect = item_select.clone();
                    item_select.selectpicker('destroy').remove();
                    $("body").find('.items-select-wrapper').append(clonedItemsAjaxSearchSelect);
                    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
					}
					
					add_item_to_preview(response.item.itemid);
				} else {*/
                // Is general items view
                $('.table-vehicle-table').DataTable().ajax.reload(null, false);
				//}
				alert_float('success', response.message);
			}
			$('#vehicle_modal').modal('hide');
			}).fail(function (data) {
			alert_float('danger', data.responseText);
		});
		return false;
	}
	function init_item_js() {
		// Add item to preview from the dropdown for invoices estimates
		$("body").on('change', 'select[name="item_select"]', function () {
			var itemid = $(this).selectpicker('val');
			if (itemid != '') {
				add_item_to_preview(itemid);
			}
		});
		
		// Items modal show action
		$("body").on('show.bs.modal', '#vehicle_modal', function (event) {
			
			$('.affect-warning').addClass('hide');
			
			var $itemModal = $('#vehicle_modal');
			$('input[name="itemid"]').val('');
			$itemModal.find('input').not('input[type="hidden"]').val('');
			$itemModal.find('textarea').val('');
			$itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
			$('select[name="tax2"]').selectpicker('val', '').change();
			$('select[name="tax"]').selectpicker('val', '').change();
			$itemModal.find('.add-title').removeClass('hide');
			$itemModal.find('.edit-title').addClass('hide');
			
			var id = $(event.relatedTarget).data('id');
			// If id found get the text from the datatable
			if (typeof (id) !== 'undefined') {
				
				$('.affect-warning').removeClass('hide');
				$('input[name="itemid"]').val(id);
				
				requestGetJSON('vehicles/get_vehicle_by_id/' + id).done(function (response) {
					$itemModal.find('input[name="VehicleID"]').val(response.VehicleID);
					$itemModal.find('input[name="VehicleCapacity"]').val(response.VehicleCapacity);
					$itemModal.find('input[name="insuranceno"]').val(response.insuranceno);
					$itemModal.find('input[name="insurancetakenby"]').val(response.insurancetakenby);
					var duedate = new Date(response.duedate);
					// Format the date as DD/MM/YYYY
					var duedate = duedate.toLocaleDateString('en-GB');
					$itemModal.find('input[name="duedate"]').val(duedate);
					
					var taxduedate = new Date(response.taxduedate);
					// Format the date as DD/MM/YYYY
					var taxduedate = taxduedate.toLocaleDateString('en-GB');
					$itemModal.find('input[name="taxduedate"]').val(taxduedate);
					
					var fitness_exp_date = new Date(response.fitness_exp_date);
					var fitness_exp_date = fitness_exp_date.toLocaleDateString('en-GB');
					$itemModal.find('input[name="fitness_exp_date"]').val(fitness_exp_date);
					
					var pollution_exp_date = new Date(response.pollution_exp_date);
					var pollution_exp_date = pollution_exp_date.toLocaleDateString('en-GB');
					$itemModal.find('input[name="pollution_exp_date"]').val(pollution_exp_date);
					
					$itemModal.find('#DriverID').selectpicker('val', response.DriverID);
					$itemModal.find('input[name="brand"]').val(response.brand);
					$itemModal.find('input[name="model"]').val(response.model);
					$itemModal.find('#fuel_type').selectpicker('val', response.fuel_type);
					$itemModal.find('input[name="fuel_capacity"]').val(response.fuel_capacity);
					$itemModal.find('input[name="excel_type"]').val(response.excel_type);
					$itemModal.find('input[name="mileage"]').val(response.mileage);
					$itemModal.find('input[name="VehicleCapacityCase"]').val(response.VehicleCapacityCase);
					
					var dateObject = new Date(response.StartDate);
					// Format the date as DD/MM/YYYY
					var formattedDate = dateObject.toLocaleDateString('en-GB');
					$itemModal.find('input[name="StartDate"]').val(formattedDate);
					
					
					$itemModal.find('#VehicleTypeID').selectpicker('val', response.VehicleTypeID);
					
					$itemModal.find('#ActiveYN').selectpicker('val', response.ActiveYN);
					
					
					init_selectpicker();
					init_color_pickers();
					init_datepicker();
					
					$itemModal.find('.add-title').addClass('hide');
					$itemModal.find('.edit-title').removeClass('hide');
					// validate_item_form();
				});
				
			}
		});
		
		$("body").on("hidden.bs.modal", '#vehicle_modal', function (event) {
			$('#item_select').selectpicker('val', '');
		});
		
		// validate_item_form();
	}
	// function validate_item_form(){
	// Set validation for invoice item form
	// appValidateForm($('#vehicle_form'), {
	// VehicleID: 'required',
	// VehicleCapacity: 'required',
	// VehicleTypeID: 'required',
	// StartDate: 'required',
	
	
	
	// }, manage_invoice_items);
	// }
</script>
