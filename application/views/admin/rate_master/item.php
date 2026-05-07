<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="rate_master_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('rate_master_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('rate_master_edit_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/rate_master/add_edit_rate_master',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('itemid'); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        
                        <input type="hidden" id="rate_master_id" name="rate_master_id" class="form-control" value="">
                        <input type="hidden" id="state_id" name="state_id" class="form-control" value="">
                        <input type="hidden" id="distributor_id" name="distributor_id" class="form-control" value="">
                        <?php echo render_input('description','invoice_item_add_edit_description'); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('long_description','invoice_item_long_description'); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('item_code','Item Code'); ?>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <label for="rate" class="control-label">
                            <?php echo _l('invoice_item_add_edit_rate_currency',$base_currency->name . ' <small>('._l('base_currency_string').')</small>'); ?></label>
                            <input type="number" id="assignrate" name="assignrate" class="form-control" value="">
                        </div>
                    </div>
                    
                         
                    <div class="col-md-4">
                        <div class="form-group">
                                <label class="control-label" for="tax"><?php echo _l('gst'); ?></label>
                                <select class="selectpicker display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo _l('no_gst'); ?>">
                                    <option value=""></option>
                                    <?php foreach($taxes as $tax){ ?>
                                    <option value="<?php echo $tax['id']; ?>" data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>%</option>
                                    <?php } ?>
                                </select>
                            </div>
                    </div>
                    
                    <div class="col-md-4">
                         <?php echo render_input('unit','MeasuredIn'); ?>
                    </div>
                    
                    <div class="col-md-4">
                         <?php echo render_select('group_id',$items_groups,array('id','name'),'item_group'); ?>
                    </div>
                    <div class="col-md-4">
                         <?php echo render_select('subgroup_id',$items_sub_groups,array('id','name'),'item_sub_group'); ?>
                    </div>
                    <!--<div class="col-md-4">
                        
                         <!--<?php echo render_input('assignrate','New Rate'); ?>-->
                    <!--</div>-->
                    
                    <div class="col-md-4">
        
                  <?php echo render_date_input('effective_date','effective_date'); ?>
            </div>     
                    
                    
                    
                    <!--<div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        
                    
                <div class="clearfix mbot15"></div>
               
                <div id="custom_fields_items">
                    <?php echo render_custom_fields('items'); ?>
                </div>
                
            </div>-->
        </div>
    </div>
    <div class="modal-footer">
        <?php  if (has_permission_new('ratemaster', '', 'delete')) { ?>
        <a href="admin_url" class="text-danger _delete" id="delete_rate">Delete</a>
        <?php } ?>
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
        $('#rate_master_modal').modal('hide');
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
    $("body").on('show.bs.modal', '#rate_master_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#rate_master_modal');
        $('input[name="itemid"]').val('');
        $itemModal.find('input').not('input[type="hidden"]').val('');
        $itemModal.find('textarea').val('');
        $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
        $('select[name="tax2"]').selectpicker('val', '').change();
        $('select[name="tax"]').selectpicker('val', '').change();
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');

        var id = $(event.relatedTarget).data('id');
        var state_id = $("#states").val();
        var distributor_id = $("#distributor_id").val();
        var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

var today1 =  yyyy + '-' + mm + '-' + dd;
        // If id found get the text from the datatable
        if (typeof (id) !== 'undefined') {

            $('.affect-warning').removeClass('hide');
            $('input[name="itemid"]').val(id);
            //var data = { id: id, state_id: state_id, distributor_id: distributor_id };
            requestGetJSON('rate_master/get_item_by_id/' + id +'/' + state_id + '/'+ distributor_id).done(function (response) {
                $itemModal.find('input[name="description"]').val(response.description);
                $itemModal.find('input[name="long_description"]').val(response.long_description.replace(/(<|<)br\s*\/*(>|>)/g, " "));
                $itemModal.find('input[name="item_code"]').val(response.item_code);
                $itemModal.find('input[name="rate"]').val(response.rate);
                $itemModal.find('input[name="unit"]').val(response.unit);
                $itemModal.find('input[name="assignrate"]').val(response.new_rate);
                $itemModal.find('input[name="state_id"]').val(state_id);
                $itemModal.find('input[name="distributor_id"]').val(distributor_id);
                $itemModal.find('input[name="rate_master_id"]').val(response.rate_master_id);
                $itemModal.find('input[name="effective_date"]').val(today1);
                $('select[name="tax"]').selectpicker('val', response.taxid).change();
                //$('select[name="tax2"]').selectpicker('val', response.taxid_2).change();
                $itemModal.find('#group_id').selectpicker('val', response.group_id);
                $itemModal.find('#subgroup_id').selectpicker('val', response.subgroup_id);
                
                var url = "rate_master/delete/"+response.rate_master_id;
               $("a[href='admin_url']").attr('href', url)
                $itemModal.find('#rate_id').text(response.rate_master_id);
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

    $("body").on("hidden.bs.modal", '#rate_master_modal', function (event) {
        $('#item_select').selectpicker('val', '');
    });

   validate_item_form();
}
function validate_item_form(){
    // Set validation for invoice item form
    appValidateForm($('#invoice_item_form'), {
        description: 'required',
        rate: {
            required: true,
        }
    }, manage_invoice_items);
}



</script>
