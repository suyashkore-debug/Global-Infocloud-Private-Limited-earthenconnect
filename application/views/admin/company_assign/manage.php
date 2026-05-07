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
            echo form_open($this->uri->uri_string(),array('id'=>'company_assign_form','class'=>'_transaction_form hierachy-form'));
            ?>
            <div class="row">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="all_staff" class="control-label"><small class="req text-danger">* </small>Select Staff</label>
                        <select class="selectpicker" name="all_staff" id="all_staff" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                            <?php
                                foreach ($all_staff as $key => $value) {
                                            # code...
                                ?>
                                <option value="<?php echo $value["staffid"]?>"><?php echo $value["firstname"]." ".$value["lastname"]; ?></option>
                                <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="company_select" class="control-label"><small class="req text-danger">* </small> Select Company</label>
                        <select class="selectpicker" name="company_select" id="company_select" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="distributor_select" class="control-label"><small class="req text-danger">* </small> Distributor Select</label>
                        <select class="selectpicker" name="distributor_select[]" id="distributor_select" multiple="true" data-width="100%"  data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                           
                        </select>
                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="transfer_to" class="control-label"><small class="req text-danger">* </small> Transfer To</label>
                        <select class="selectpicker" name="transfer_to" id="transfer_to" data-width="100%" data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                           
                        </select>
                    </div>
                </div>
                
                
                
            </div>
            <div class="row">
                <div class="col-md-2">
                    
                    <button type="submit" class="btn-tr btn btn-info invoice-form-submit transaction-submit" style="margin-top:8px;">Transfer</button>
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
<?php $this->load->view('admin/company_assign/hierarchy_js'); ?>
<?php $this->load->view('admin/company_assign/validate_js'); ?>
</body>
</html>
