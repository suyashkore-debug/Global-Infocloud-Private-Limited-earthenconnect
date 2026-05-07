<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'TopFiveVendorChart'; ?>">
   <?php if(is_admin()){ ?>
    <div class="purchase-summary">
      <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <div class="row home-summary">
                <?php if(is_admin()){
                ?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div id="TopFiveVendorChart">
				    </div>
                </div>
                <?php } ?>
            </div>
        </div>
      </div>
    </div>
<?php } ?>
</div>
