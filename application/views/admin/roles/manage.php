<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="panel_s">
					<div class="panel-body">
						
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Admin</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Staff Roles</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="_buttons">
						<?php
                            if (has_permission_new('roles', '', 'create')) {
                        ?>
							<a href="<?php echo admin_url('roles/role'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_role'); ?></a>
						<?php } ?>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('roles_dt_name'),
							_l('options')
							),'roles'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php init_tail(); ?>
	<script>
		initDataTable('.table-roles', window.location.href, [1], [1]);
	</script>
</body>
</html>
