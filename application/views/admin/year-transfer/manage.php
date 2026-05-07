<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'year-transfer-form','class'=>'_transaction_form'));
			
			?>
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
      <nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Master</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Year Transfer</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
            <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trf_from" class="form-label">Transfer From</label>
                            <select name="trf_from" id="trf_from" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                <option value=""> </option>
                                <?php
                                    foreach ($firm as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value["FY"];?>"><?php echo $value["FIRMNAME"]." ".substr($value["YEARFROM"],0,4).'-'.substr($value["YEARTO"],0,4);?></option>
                                <?php
                                    }
                                ?>
                                
                            </select> 
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trf_to" class="form-label">Transfer To</label>
                            <select name="trf_to" id="trf_to" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                <option value=""></option>
                                <?php
                                    foreach ($firm as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value["FY"];?>"><?php echo $value["FIRMNAME"]." ".substr($value["YEARFROM"],0,4).'-'.substr($value["YEARTO"],0,4);?></option>
                                <?php
                                    }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trf_accounts" class="form-label">Transfer Accounts</label>
                            <select name="trf_accounts" id="trf_accounts" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                <option value="0">All Account Head, No Closing Balance</option>
                                <option value="1">All Account Head, Closing Balance</option>
                            </select> 
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trf_crates" class="form-label">Transfer Crates</label>
                            <select name="trf_crates" id="trf_crates" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                <option value="N">No</option>
                                <option value="Y">Yes</option>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trf_stock" class="form-label">Transfer Stock</label>
                            <select name="trf_stock" id="trf_stock" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                <option value="N">No</option>
                                <option value="Y">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="col-md-1">
                        <?php
                        //if (has_permission_new('stock_adjustment', '', 'create')) {  
                        ?>
                            <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                            Transfer</button>
                        <?php// } ?>
                    </div>
                    <div class="col-md-1">
                        <?php
                        //if (has_permission_new('stock_adjustment', '', 'create')) {  
                        ?>
                            <!--<button type="button"  class="btn-tr btn btn-warning mleft10 transaction-submit">Cancel</button>-->
                            <a href="#" class="btn-tr btn btn-warning mleft10">Cancel</a>
                        <?php// } ?>
                    </div>
                        
                </div>
                    
                    
                   
              
                
            </div>
            </div>
        </div>
        
        
           
              </div>
        </div>
        </div>

			</div>
			<?php echo form_close(); ?>
			
		</div>
	</div>
</div>
</div>



<?php init_tail(); ?>

</body>

</html>

<script>
    $(function(){
  "use strict";
		validate_transfer_form();
    function validate_transfer_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#year-transfer-form' : selector;

        appValidateForm($(selector), {
            trf_from: 'required',
            trf_to: 'required',
        });
    }
});
</script>

