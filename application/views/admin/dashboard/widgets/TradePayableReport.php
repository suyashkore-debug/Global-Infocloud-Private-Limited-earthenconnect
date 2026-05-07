<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'TradePayable'; ?>">
	<?php if(is_admin()){ ?>
		<div class="purchase-summary">
			<div class="panel_s">
				<div class="panel-body">
					<div class="widget-dragger"></div>
					<div class="row home-summary">
						<?php if(is_admin()){
						?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<center><span class="text-danger">Overdue Trade Payable Report</span></center>
							<hr/>
							<div id="TradePayable">
							</div>
						</div>
						<?php } ?>
						
					</div>
					
				</div>
			</div>
		</div>
	<?php } ?>
</div>
