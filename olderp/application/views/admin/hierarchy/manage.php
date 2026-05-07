<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            
            <?php hooks()->do_action('before_items_page_content'); ?>
            <?php
            echo form_open($this->uri->uri_string(),array('id'=>'hierachy_form','class'=>'_transaction_form hierachy-form'));
            ?>
            <div class="row">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="job_position" class="control-label"><small class="req text-danger">* </small>Position</label>
                        <select class="selectpicker" name="job_position" id="job_position" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                            <?php
                                foreach ($position as $key => $value) {
                                            # code...
                                ?>
                                <option value="<?php echo $value["position_id"]?>"><?php echo $value["position_name"]; ?></option>
                                <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="from_staff" class="control-label"><small class="req text-danger">* </small> From Staff</label>
                        <select class="selectpicker" name="from_staff" id="from_staff" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="to_staff" class="control-label"><small class="req text-danger">* </small> Transfer To</label>
                        <select class="selectpicker" name="to_staff" id="to_staff" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                           
                        </select>
                        <input type="hidden" name="tostaff_report_to" id="tostaff_report_to" value="">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <br>
                    <button type="submit" class="btn-tr btn btn-info invoice-form-submit transaction-submit" style="margin-top:8px;">Transfer</button>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="select_staff" class="control-label"><small class="req text-danger">* </small> Select Staff</label>
                        <select class="selectpicker" name="select_staff[]" id="select_staff" data-width="100%" multiple="true"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                           
                        </select>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
    </div>
    </div>
 </div>
</div>







<?php init_tail(); ?>
<?php $this->load->view('admin/hierarchy/hierarchy_js'); ?>
<?php $this->load->view('admin/hierarchy/validate_js'); ?>
</body>
</html>
