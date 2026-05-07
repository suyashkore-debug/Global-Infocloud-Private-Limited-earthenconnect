<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
                    				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
                    					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
                    					<li class="breadcrumb-item active text-capitalize"><b>Transaction</b></li>
                    					<li class="breadcrumb-item active" aria-current="page"><b>Gatepass</b></li>
                    				</ol>
                                </nav>
                                <hr class="hr_style">
						<?php hooks()->do_action('before_items_page_content'); ?>
						
						<div class="clearfix"></div>
						
						<div class="_buttons">
							<div class="col-md-2">
								<?php //$cur_date = date('d/m/Y'); 
									//$first_date = "01/".date('m')."/".date('Y');
								?>
								<?php
									$fy = $this->session->userdata('finacial_year');
									$fy_new  = $fy + 1;
									$lastdate_date = '20'.$fy_new.'-03-31';
									$firstdate_date = '20'.$fy_new.'-04-01';
									$curr_date = date('Y-m-d');
									$curr_date_new    = new DateTime($curr_date);
									$last_date_yr = new DateTime($lastdate_date);
									if($last_date_yr < $curr_date_new){
										$to_date = '31/03/20'.$fy_new;
										$from_date = '01/03/20'.$fy_new;
										}else{
										// $from_date = "01/".date('m')."/".date('Y');
										$from_date = date('d/m/Y');  // "27/01/2025"

										// Convert the date to a timestamp using the correct format
										$timestamp = strtotime(str_replace('/', '-', $from_date));

										// Subtract 5 days
										$five_days_before = strtotime("-5 days", $timestamp);

										// Format the result back to the desired format
										$from_date = date('d/m/Y', $five_days_before);
										$to_date = date('d/m/Y');
									}
								?>
								<?php echo render_date_input('from_date','from_date',$from_date); ?>
							</div>
							<div class="col-md-2">
								<?php echo render_date_input('to_date','to_date',$to_date); ?>
							</div>
							
							<!-- <div class="col-md-3">
								<?php 
									
									echo render_select('distributor_id',$groups,array('id','name'),'customer_groups',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
									
								?>
							</div>-->
							<?php
							    if (isset($_GET['status'])) {
                                    $status = $_GET['status'];
                                }
							?>
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="gatepass_status">
									<label for="gatepass_status" class="control-label">Status</label>
									<div class="dropdown bootstrap-select bs3 open" style="width: 100%;">
										<select id="gatepass_status" name="gatepass_status" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
											<option value="" >All</option>
											<option value="NotGenerated" <?php if($status == "NotGenerated") echo 'selected';?>>Not Generated</option>
											<option value="Generated" <?php if($status == "Generated") echo 'selected';?>>Generated</option>
										</select>
									</div>
								</div>         
							</div>
							
							
						</div>
						
						<div class="col-md-3" style="margin-top:10px;">
							<br>
							<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						
						<?php
							$table_data = [];
							
							if(has_permission('ratemaster','','delete')) {
								//$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
							}
							
							$table_data = array(
							"Challan No.",
							"Challan Date",
							"Gatepass Time",
							"Party Name",
							"Vehicle",
							"Driver Name",
							"Route",
							"Total Qty",
							"Challan Amt",
							"Status");
							
							/*$cf = get_custom_fields('items');
								foreach($cf as $custom_field) {
								array_push($table_data,$custom_field['name']);
							}*/
						render_datatable($table_data,'gatepass'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
<script>
	function Getepassgenerate(Link)
	{
		var result = confirm("Are you sure you want to generate gatepass?");
		if (result) {
			window.open(Link,'_blank');
		}
	}
	$(function(){
		$('#search_data').on('click',function(){
			var notSortableAndSearchableItemColumns = [];
			<?php if(has_permission('items','','delete')){ ?>
				notSortableAndSearchableItemColumns.push(0);
			<?php } ?>
			var CustomersServerParams = {};
			CustomersServerParams['from_date'] = '[name="from_date"]';
			CustomersServerParams['date'] = '[name="to_date"]';
			CustomersServerParams['gatepass_status'] = '[name="gatepass_status"]';
			var dates = $("#to_date").val();
			if (dates.trim()!=''){
				if ($.fn.DataTable.isDataTable('.table-gatepass')) {
					$('.table-gatepass').DataTable().destroy();
				} 
				initDataTable('.table-gatepass', admin_url+'challan/gatepass_list', undefined, undefined, CustomersServerParams,<?php echo hooks()->apply_filters('projects_table_default_order', json_encode([0, 'desc'])); ?>);
				}else{
				alert("Please select Date First");
			}
		});
	});
	function items_bulk_action(event) {
		if (confirm_delete()) {
			var mass_delete = $('#mass_delete').prop('checked');
			var ids = [];
			var data = {};
			
			if(mass_delete == true) {
				data.mass_delete = true;
			}
			
			var rows = $('.table-invoice-items').find('tbody tr');
			$.each(rows, function() {
				var checkbox = $($(this).find('td').eq(0)).find('input');
				if (checkbox.prop('checked') === true) {
					ids.push(checkbox.val());
				}
			});
			data.ids = ids;
			$(event).addClass('disabled');
			setTimeout(function() {
				$.post(admin_url + 'invoice_items/bulk_action', data).done(function() {
					window.location.reload();
					}).fail(function(data) {
					alert_float('danger', data.responseText);
				});
			}, 200);
		}
	}
</script>

<script>
    $(document).ready(function(){
		var maxEndDate = new Date('Y/m/d');
		var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
		
		var year = "20"+fin_y;
		var cur_y = new Date().getFullYear().toString().substr(-2);
		if(cur_y => fin_y){
			var year2 = parseInt(fin_y) + parseInt(1);
			var year2_new = "20"+year2;
			
			var e_dat = new Date(year2_new+'/03/31');
			var maxEndDate_new = e_dat;
			}else{
			var maxEndDate_new = maxEndDate;
		}
		
		var minStartDate = new Date(year, 03);
		
		
		
		$('#from_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
		
		$('#to_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
	});
</script> 
</body>
</html>
