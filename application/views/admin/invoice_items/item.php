<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('invoice_item_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('invoice_item_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/invoice_items/manage',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('itemid'); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <?php echo render_input('item_code1','Item Code','','text',array("disabled" =>true)); ?>
                        <input type="hidden" id="item_code" name="item_code" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('description','invoice_item_add_edit_description'); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('long_description','invoice_item_long_description'); ?>
                        <input type="hidden" id="rate" name="rate" class="form-control" value="0">
                    </div>
                    
                    <!--<div class="col-md-4">
                        <div class="form-group">
                        <label for="rate" class="control-label">
                            <?php echo _l('invoice_item_add_edit_rate_currency',$base_currency->name . ' <small>('._l('base_currency_string').')</small>'); ?></label>
                            <input type="hidden" id="rate" name="rate" class="form-control" value="0">
                        </div>
                    </div>-->
                    <?php
                            foreach($currencies as $currency){
                                if($currency['isdefault'] == 0 && total_rows(db_prefix().'clients',array('default_currency'=>$currency['id'])) > 0){ ?>
                            <div class="col-md-4">
                            <div class="form-group">
                                    <label for="rate_currency_<?php echo $currency['id']; ?>" class="control-label">
                                        <?php echo _l('invoice_item_add_edit_rate_currency', $currency['name']); ?></label>
                                        <input type="number" id="rate_currency_<?php echo $currency['id']; ?>" name="rate_currency_<?php echo $currency['id']; ?>" class="form-control" value="">
                                    </div>
                                </div>
                                     <?php   }
                            }
                        ?>
                         
                    <div class="col-md-3">
                        <div class="form-group">
                                <label class="control-label" for="tax"><?php echo _l('gst'); ?></label>
                                <select class="selectpicker display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo _l('no_gst'); ?>">
                                    <option value=""></option>
                                    <?php foreach($taxes as $tax){ ?>
                                    <option value="<?php echo $tax['id']; ?>" ><?php echo $tax['taxrate']; ?>%</option>
                                    <?php } ?>
                                </select>
                            </div>
                    </div>
                    <!--<div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="tax2"><?php echo _l('other_tax'); ?></label>
                            <select class="selectpicker display-block" disabled data-width="100%" name="tax2" data-none-selected-text="<?php echo _l('no_other_tax'); ?>">
                                <option value=""></option>
                                <?php foreach($taxes as $tax){ ?>
                                <option value="<?php echo $tax['id']; ?>" data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>%</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>-->
                    <div class="col-md-3">
                         <?php //echo render_input('unit','MeasuredIn'); ?>
                         <div class="form-group">
                            <label class="control-label" for="unit"><?php echo _l('measured_in'); ?></label>
                            <select class="selectpicker display-block" data-width="100%" name="unit" id="unit" data-none-selected-text="<?php echo _l('no_measured_in'); ?>">
                                <option value="Pcs">Pcs</option>
                                <option value="Kgs">Kgs</option>
                                <option value="Gms">Gms</option>
                                <option value="Ltrs">Ltrs</option>
                                <option value="Mtrs">Mtrs</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <?php echo render_select('subgroup_id',$items_sub_groups,array('id','name'),'item_sub_group'); ?>
                    </div>
                    
                    <div class="col-md-3">
                         <?php echo render_select('group_id',$items_groups,array('id','name'),'item_group'); ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Local Supply In</label>
                             <select class="selectpicker" name="local_supply_in" id="local_supply_in" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="CR">Crate</option> 
                                <option value="CS">Case</option> 
                             </select>
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                           <label class="form-label">Outst. Supply In</label>
                             <select class="selectpicker" name="outst_supply_in" id="outst_supply_in" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="CR">Crate</option> 
                                <option value="CS">Case</option> 
                             </select> 
                        </div> 
                         
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Crate Qty</label>
                            <input type="number" name="crate_qty" id="crate_qty" class="form-control" value="0" min="0">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Case Qty</label>
                            <input type="number" name="case_qty" id="case_qty" class="form-control" value="0" min="0">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Bowl Qty</label>
                            <input type="number" name="bowl_qty" id="bowl_qty" class="form-control" value="0" min="0">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Min Qty</label>
                            <input type="number" name="min_qty" id="min_qty" class="form-control" value="0" min="0">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Case Weight</label>
                            <input type="number" name="case_weight" id="case_weight" class="form-control" value="0" min="0">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Min Days</label>
                            <input type="number" name="min_day" id="min_day" class="form-control" value="0" min="0">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><small class="req text-danger">* </small>MonitorStock?</label>
                            <select class="selectpicker" id= "monitorstock" name="monitorstock" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false" required>
                                <option value="Y">Y</option> 
                                <option value="N">N</option> 
                            </select>
                        </div>
                         
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">HSN Code</label>
                            <select class="selectpicker" name="hsn_code" id="hsn_code" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                                <?php
                                foreach ($hsn as $key => $value) {
                                        # code...
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
                            <label class="form-label">Rack ID</label>
                            <select class="selectpicker" name="rack_id" id="rack_id" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                                <!--<option value="1">Rec 1</option> 
                                <option value="2">Rec 2</option> 
                                <option value="3">Rec 3</option> 
                                <option value="4">Rec 4</option> 
                                <option value="5">Rec 5</option> 
                                <option value="6">Rec 6</option> -->
                                <?php
                                foreach ($items_rack as $key => $value) {
                                        # code...
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
                            <select class="selectpicker" name="subrack_id" id="subrack_id" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                                <option value="A">A</option> 
                                
                            </select>
                        </div>
                         
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><small class="req text-danger">* </small>Is Active?</label>
                            <select class="selectpicker" name="isactive" id="isactive" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false" required>
                                <option value="Y">Active</option> 
                                <option value="N">Deactive</option> 
                            </select>
                        </div>
                         
                    </div>
                    <?php
                        $staff_user_id = $this->session->userdata('staff_user_id');
                        if($staff_user_id !== "3"){
                            $disabled = "disabled";
                        }
                    ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Opening Stock</label>
                            <input type="text" name="opening_stock" id="opening_stock" <?php echo $disabled; ?> value="" class="form-control">
                        </div>
                         
                    </div>
                    
                    <div class="col-md-12">
                        <!--<div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>-->
                        
                    
                <div class="clearfix mbot15"></div>
               
                <div id="custom_fields_items">
                    <?php echo render_custom_fields('items'); ?>
                </div>
                
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
            var item_select = $('#item_select');
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
            } else {
                // Is general items view
                $('.table-invoice-items').DataTable().ajax.reload(null, false);
            }
            alert_float('success', response.message);
        }
        $('#sales_item_modal').modal('hide');
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
    $("body").on('show.bs.modal', '#sales_item_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#sales_item_modal');
        $('input[name="itemid"]').val('');
        $itemModal.find('input').not('input[type="hidden"]').val('');
        $itemModal.find('textarea').val('');
        $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
        $('select[name="tax2"]').selectpicker('val', '').change();
        $('select[name="tax"]').selectpicker('val', '').change();
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');
        //remove it
        $itemModal.find('input[name="item_code1"]').removeAttr("disabled");
        
        var id = $(event.relatedTarget).data('id');
        // If id found get the text from the datatable
        if (typeof (id) !== 'undefined') {
            //add disabled
                $itemModal.find('input[name="item_code1"]').attr('disabled', 'disabled');
            $('.affect-warning').removeClass('hide');
            $('input[name="itemid"]').val(id);

            requestGetJSON('invoice_items/get_item_by_id/' + id).done(function (response) {
                $itemModal.find('input[name="item_code"]').val(response.item_code);
                $itemModal.find('input[name="item_code1"]').val(response.item_code);
                $itemModal.find('input[name="description"]').val(response.description);
                $itemModal.find('input[name="long_description"]').val(response.long_description.replace(/(<|<)br\s*\/*(>|>)/g, " "));
                $itemModal.find('input[name="rate"]').val(response.rate);
                /*$itemModal.find('input[name="unit"]').val(response.unit);*/
                $('select[name="tax"]').selectpicker('val', response.taxid).change();
                $('select[name="tax2"]').selectpicker('val', response.taxid_2).change();
                $itemModal.find('#group_id').selectpicker('val', response.group_id);
                $itemModal.find('#subgroup_id').selectpicker('val', response.subgroup_id);
                $itemModal.find('#unit').selectpicker('val', response.unit);
                $itemModal.find('#local_supply_in').selectpicker('val', response.local_supply_in);
                $itemModal.find('#outst_supply_in').selectpicker('val', response.local_supply_in);
                $itemModal.find('input[name="crate_qty"]').val(response.crate_qty);
                $itemModal.find('input[name="case_qty"]').val(response.case_qty);
                $itemModal.find('input[name="bowl_qty"]').val(response.bowl_qty);
                $itemModal.find('input[name="min_qty"]').val(response.min_qty);
                $itemModal.find('input[name="case_weight"]').val(response.case_weight);
                $itemModal.find('input[name="min_day"]').val(response.min_day);
                $itemModal.find('#monitorstock').selectpicker('val', response.monitorstock);
                $itemModal.find('#isactive').selectpicker('val', response.isactive);
                $itemModal.find('#hsn_code').selectpicker('val', response.hsn_code);
                $itemModal.find('#rack_id').selectpicker('val', response.rack_id);
                $itemModal.find('#subrack_id').selectpicker('val', response.subrack_id);
                $itemModal.find('#opening_stock').val(response.OQty);
                $.each(response, function (column, value) {
                    if (column.indexOf('rate_currency_') > -1) {
                        $itemModal.find('input[name="' + column + '"]').val(value);
                    }
                });

                $('#custom_fields_items').html(response.custom_fields_html);

                init_selectpicker();
                init_color_pickers();
                init_datepicker();

                $itemModal.find('.add-title').addClass('hide');
                $itemModal.find('.edit-title').removeClass('hide');
                validate_item_form();
            });

        }
    });

    $("body").on("hidden.bs.modal", '#sales_item_modal', function (event) {
        $('#item_select').selectpicker('val', '');
    });

   validate_item_form();
}
function validate_item_form(){
    // Set validation for invoice item form
    appValidateForm($('#invoice_item_form'), {
        description: 'required',
		item_code1: {
				required: true,
				remote: {
					url: site_url + "admin/misc/ItemID_exists",
					type: 'post',
					data: {
						ItemID: function() {
							return $('input[name="item_code1"]').val();
						},
						itemid: function() {
							return $('input[name="itemid"]').val();
						}
					}
				}
			},
        tax: 'required',
        unit: 'required',
        group_id: 'required',
        subgroup_id: 'required',
        
        
        
    }, manage_invoice_items);
}
</script>
