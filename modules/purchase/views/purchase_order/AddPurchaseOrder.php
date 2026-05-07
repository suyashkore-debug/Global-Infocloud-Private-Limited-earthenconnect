<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
				echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form'));
				
			?>
			<div class="col-md-12">
				<div class="panel_s accounting-template estimate">
					<div class="row">
						<div class="col-md-12"> 
							<div class="panel-body">
							    <nav aria-label="breadcrumb">
                    				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
                    					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
                    					<li class="breadcrumb-item active text-capitalize"><b>Purchase</b></li>
                    					<li class="breadcrumb-item active" aria-current="page"><b>Add Purchase Order</b></li>
                    				</ol>
                                </nav>
                                <hr class="hr_style">
								<br>
								<?php
									$customer_custom_fields = false;
									if(total_rows(db_prefix().'customfields',array('fieldto'=>'pur_order','active'=>1)) > 0 ){
										$customer_custom_fields = true;
									}
								?>
								<div class="tab-content">
									<?php if($customer_custom_fields) { ?>
										<div role="tabpanel" class="tab-pane" id="custom_fields">
											<?php $rel_id=( isset($pur_order) ? $pur_order->id : false); ?>
											<?php echo render_custom_fields( 'pur_order',$rel_id); ?>
										</div>
									<?php } ?>
									<div role="tabpanel" class="tab-pane active" id="general_infor">
										<div class="row">
										    <div class="col-md-9">
										        <div class="row">
										            <div class="col-md-3">
														<?php
															$selected_company = $this->session->userdata('root_company');
															$fy = $this->session->userdata('finacial_year');
															if($selected_company == 1){
																$new_purchase_orderNumbar = get_option('next_purchase_order_number_for_cspl');
															}elseif($selected_company == 2){
																$new_purchase_orderNumbar = get_option('next_purchase_number_for_cff');
															}elseif($selected_company == 3){
																$new_purchase_orderNumbar = get_option('next_purchase_number_for_cbu');
															}elseif($selected_company == 4){
																$new_purchase_orderNumbar = get_option('next_purchase_number_for_cbupl');
															}
															$format = get_option('invoice_number_format');
															
															$prefix = get_purchase_option('pur_order_po_prefix');
															$prefix = $prefix.'<span id="prefix_year">'.$fy.'</span>';
															$__number = $new_purchase_orderNumbar;
															
															$_production_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
														?> 
														<div class="form-group">
															<label for="pro_orderid"> PO No.</label>
															<div class="input-group">
																<span class="input-group-addon"><?php echo $prefix;?></span>
																<input type="text" name="pro_orderid" id="pro_orderid" class="form-control receiptsid" value="<?php echo ($_is_draft) ? 'DRAFT' : $_production_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($_is_draft) ? 'disabled' : '' ?>>
															</div>
														</div>
													</div> 
													<div class="col-md-3">
														<?php
															$fy = $this->session->userdata('finacial_year');
															$fy_new  = $fy + 1;
															$lastdate_date = '20'.$fy_new.'-03-31';
															$curr_date = date('Y-m-d');
															$curr_date_new    = new DateTime($curr_date);
															$last_date_yr = new DateTime($lastdate_date);
															if($last_date_yr < $curr_date_new){
																$date = $lastdate_date;
															}else{
																$date = date('Y-m-d');
															}
														?>
														<?php $order_date = (isset($pur_order) ? _d($pur_order->order_date) : _d($date));
														echo render_date_input('prd_date','PO Date',$order_date); ?>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="estimate">GST</label>
															<input type="text" readonly="" class="form-control" name="gst_num" id="gst_num"  aria-invalid="false">
															
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="IsTDS">TDS Applicable ?</label>
															<input type="text" readonly="" class="form-control" name="IsTDS" id="IsTDS"  aria-invalid="false">
														</div>
													</div> 
													
													<div class="clearfix"></div>
												    <div class="col-md-4">
														<label for="vendor"><?php echo _l('Vendor Name'); ?></label>
														<select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
															<option value=""></option>
															<?php foreach($vendors as $s) { ?>
																<option value="<?php echo html_entity_decode($s['AccountID']); ?>" ><?php echo html_entity_decode($s['company'])." - ".html_entity_decode($s['AccountID']); ?></option>
															<?php } ?>
														</select>  
													</div>
													
													<div class="col-md-2">
													    <input type="hidden" value="" class="form-control" name="item_associated" id="item_associated"  aria-invalid="false">
														<div class="form-group">
															<label for="state_f">State Name</label>
															<input type="text" readonly="" class="form-control" name="state_f" id="state_f"  aria-invalid="false">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="estimate">City</label>
															<input type="text" readonly="" class="form-control" name="city" id="city"  aria-invalid="false">
														</div>
													</div> 
													
													<div class="col-md-3" >
														<div class="form-group">
															<label for="ContactPersonName"><small class="req text-danger">*</small>Contact Person Name</label>
															<input type="text" class="form-control" name="ContactPersonName" id="ContactPersonName"  aria-invalid="false" required>
														</div>
													</div>
													
													
													<div class="clearfix"></div>
													<div class="col-md-3" >
														<div class="form-group">
															<label for="ContactMobileNo"><small class="req text-danger">*</small>Contact Number</label>
															<input type="text" class="form-control" name="ContactMobileNo" id="ContactMobileNo" 
															pattern="{0-10}" maxlength="10" minlength="10"
															title="Please enter a 10-digit phone number" required>
														</div>
													</div>
													<div class="col-md-2" style="margin-top: 1.5%;">
														<div class="form-group">
															<div class="checkbox">
																<input type="checkbox" name="useVendorAddress" id="useVendorAddress" checked="checked" style="margin-right: 5px;">
																<label for="useVendorAddress" style="margin-bottom: 0; font-weight: normal;">
																	Same as Bill Address
																</label>
															</div>
														</div>
													</div>
													
													<div class="col-md-3" >
														<div class="form-group">
															<label for="ShipToParty"><small class="req text-danger">*</small>Ship To Party</label>
															<select name="ShipToParty" id="ShipToParty" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true" required>
																<option value="">None selected</option>
																<?php foreach($PartyList as $party) { ?>
																	<option value="<?php echo html_entity_decode($party['AccountID']); ?>">
																		<?php echo html_entity_decode($party['company']); ?>
																	</option>
																<?php } ?>
															</select>
														</div>		
													</div>
													
													<div class="col-md-3" id="shipToAddressContainer">
														<div class="form-group">
															<label for="ShipToAddress"><small class="req text-danger">*</small>Ship To Address</label>
															<select name="ShipToAddress" id="ShipToAddress" class="selectpicker form-control" data-none-selected-text="None selected" data-live-search="true" required>
																<option value="">None selected</option>
																<!-- Options will be populated dynamically -->
															</select> 
														</div>
													</div>
										        </div>
										    </div>
										    <div class="col-md-3">
										        <div class="row">
										            <div class="col-md-12">
														<label for="narration">Address</label>
														<textarea class="form-control" readonly="" class="form-control" name="address" id="address"></textarea>
													</div>
													<div class="col-md-12">
														<label for="DeliveryTerms">Terms of Delivery</label>
														<textarea class="form-control" name="DeliveryTerms" id="DeliveryTerms"></textarea>
													</div>		
										        </div>
										    </div>
										</div> 
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-body mtop10">
					    <div class="row">
					        <div class="col-md-12">
					            <p class="bold p_style"><?php echo _l('pur_order_detail'); ?></p>
    							<!--<hr class="hr_style"/>-->
    							<div class="" id="example">
    							</div>
    							<?php echo form_hidden('pur_order_detail'); ?>
					        </div>
					        <div class="clearfix"></div>
					        <div class="col-md-2 ">
								<div class="input-group" id="discount-total">
									<label for="PurchaseAmt">Purchase Amt</label>  
									
									<input type="text" readonly class="form-control text-right" name="total_mn" value="">
									
								</div>
							</div>
							<div class="col-md-2 ">
								
								<div class="input-group" id="discount-total">
									<label for="<?php echo _l('subtotal'); ?>">Discount Amt</label>  
									<input type="text" readonly value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" onchange="dc_total_change(this); return false;" data-type="currency" name="dc_total">
									
								</div>
							</div>
							
							<div class="col-md-2 "> 
								<div class="input-group" id="discount-total">
									<label for="<?php echo _l('subtotal'); ?>">CGST Amt</label>  
									<input type="text" readonly value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" data-type="currency" name="CGST_amt">
									
									
									
								</div>
							</div>
							<div class="col-md-2 ">
								<div class="input-group" id="discount-total">
									<label  for="<?php echo _l('subtotal'); ?>">SGST Amt</label>  
									<input type="text" readonly value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" onchange="dc_total_change(this); return false;" data-type="currency" name="SGST_AMT">
								</div>
							</div>
							<div class="col-md-2 "> 
								<div class="input-group" id="discount-total">
									<label for="<?php echo _l('subtotal'); ?>">IGST Amt</label>  
									<input type="text" readonly  value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" onchange="dc_total_change(this); return false;" data-type="currency" name="IGST_amt">
										
								</div>
							</div>
							<div class="col-md-2 "> 
								<div class="input-group" id="">
									<label for="TDS_amt">Tds Amt</label>  
									<input type="text" readonly  value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->tdsamt,''); } ?>" class="form-control pull-left text-right" data-type="currency" name="TDS_amt">
										
								</div>
							</div>
							<div class="col-md-2 "> 
								<div class="input-group" id="discount-total">
									<label  for="<?php echo _l('subtotal'); ?>">Round Off Amt</label>  
									<input type="text" readonly value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" onchange="dc_total_change(this); return false;" data-type="currency" name="Round_OFF">
								</div>
							</div>
							
							<div class="col-md-2 "> 
								<div class="input-group" id="discount-total">
									<label for="<?php echo _l('subtotal'); ?>">Invoice Amt</label>  
									<input type="text" readonly value="<?php if(isset($pur_order)){ echo $pur_order->invamt; } ?>" class="form-control pull-left text-right" data-type="currency" name="Invoice_amt">
								</div>
							</div>
					    </div>
					    
				</div>
				<div class="row">
					<div class="col-md-12 mtop15">
						<div class="panel-body bottom-transaction">
							
							<div id="vendor_data">
								
							</div>
							
							<div class="btn-bottom-toolbar text-right" style="width: 100%;">
								<a href="#" class="btn btn-warning edit-new-order">View List</a>
								<?php if (has_permission_new('purchase-order-po', '', 'create')){
								?>
								
								<button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
									<?php echo _l('submit'); ?>
								</button>
								<?php
								}?>
							</div>
						</div>
						<div class="btn-bottom-pusher"></div>
					</div>
				</div>
			</div>
			
		</div>
		<?php echo form_close(); ?>
		
	</div>
</div>
</div>
</div>
<style>
	/*    @media (min-width: 768px)*/ 
	/*        .modal-xl {*/
	/*    width: 90%;*/
	/*    max-width: 1230px;*/
	/*}*/
</style>
<div class="modal fade" id="transfer-modal">
	<div class="modal-dialog modal-xl" style=" max-width: 1230px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Puchase Order List</h4>
			</div>
			
			
			<div class="modal-body" style="padding:5px;">
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
                        $from_date = "01/".date('m')."/".date('Y');
                        $to_date = date('d/m/Y');
					}
				?> 
				<div class="row">
					
					<div class="col-md-2">
						<?php
							echo render_date_input('from_date','From Date',$from_date);
						?>
					</div>
					<div class="col-md-2">
						<?php
							echo render_date_input('to_date','To Date',$to_date);
						?>
					</div>
					<div class="col-md-3">
						<br>
						<button class="btn btn-info pull-left mleft5 search_data " id="search_data"><?php echo _l('rate_filter'); ?></button>
					</div>
					<div class="col-md-3">
						<br>
						<input type="text" id="myInput1" class="form-control" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
					</div>
					<div class="col-md-12">
						
						<div class="table_purchase_report">
							
							<table class="tree table table-striped table-bordered table_purchase_report" id="table_purchase_report" width="100%">
								
								<thead>
									<tr>
										<th class="sortablePop" style="width:1% ">BT</th>
										<th class="sortablePop" style="width:7% ">PO No.</th>
										<th class="sortablePop" style="width:5% ">PO Date</th>
										<th class="sortablePop" style="width:15% text-align:left;">Purchased From</th>
										<th class="sortablePop" style="width:5% text-align:left;">Purchase Amt</th>
										<th class="sortablePop" style="width:3% text-align:left;">Disc Amt</th>
										<th class="sortablePop" style="width:5% text-align:left;">CGST Amt</th>
										<th class="sortablePop" style="width:5% text-align:left;">SGST Amt</th>
										<th class="sortablePop" style="width:5% text-align:left;">IGST Amt</th>
										<th class="sortablePop" style="width:5% text-align:left;">Inv. Amt</th>
										
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
						</div>
						<span id="searchh2" style="display:none;">
							Loading.....
						</span>
						
					</div>
				</div>
				
				
			</div>
			
		</div>
	</div>
</div>

<div class="modal fade" id="last-purchase-modal">
	<div class="modal-dialog modal-md" style=" max-width: 1230px;">
		<div class="modal-content">
			<div class="modal-header" style="padding: 10px;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Last Puchases Entry Details</h4>
			</div>
			
			<div class="modal-body" style="padding:5px;">
				
				<div class="row">
					
					<div class="col-md-12">
						
						<div class="last_purchase_details">
							
							<table class="tree table table-striped table-bordered last_purchase_details" id="last_purchase_details" width="100%">
								
								<thead>
									<tr>
										<th style="width:25% ">Purch Entry No.</th>
										<th style="width:25% ">Purch Entry Date</th>
										<th style="width:25% text-align:left;">Purchased From</th>
										<th style="width:25% text-align:left;">Basic Purch Rate</th>
										<th style="width:25% text-align:left;">GST(%)</th>
										<th style="width:25% text-align:left;">Net Purch Rate</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
						</div>
						
					</div>
				</div>
				
			</div>
			
			
		</div>
	</div>
</div>
<?php init_tail(); ?>

</body>
<script type="text/javascript">
	$('#tcs_pre_data').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});
</script>
<style>
    .table_purchase_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table_purchase_report thead th { position: sticky; top: 0; z-index: 1; }
	.table_purchase_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table_purchase_report table  { border-collapse: collapse; width: 100%; }
	.table_purchase_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.table_purchase_report th     { background: #50607b;color: #fff !important; }
	#table_purchase_report tr:hover {
        background-color: #ccc;
	}
	
	#table_purchase_report td:hover {
        cursor: pointer;
	}
	
	.last_purchase_details { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.last_purchase_details thead th { position: sticky; top: 0; z-index: 1; }
	.last_purchase_details tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.last_purchase_details table  { border-collapse: collapse; width: 100%; }
	.last_purchase_details th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.last_purchase_details th     { background: #50607b;color: #fff !important; }
	#last_purchase_details tr:hover {
        background-color: #ccc;
	}
	
	#last_purchase_details td:hover {
        cursor: pointer;
	}
</style>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		function load_data(from_date,to_date)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>purchase/load_data_for_purchaseOrder",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('.table_purchase_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('.table_purchase_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					var html = '';
					
					for(var count = 0; count < data.length; count++)
					{
						
						var url = "'<?php echo admin_url() ?>purchase/EditPurchaseOrder/"+data[count].PurchID+"'";
						html += '<tr onclick="location.href='+url+'">';
						html += '<td style="text-align:center;">'+data[count].BT+'</td>';
						html += '<td style="text-align:center;">'+data[count].PurchID+'</td>';
						var date = data[count].Transdate.substring(0, 10)
						var date_new = date.split("-").reverse().join("/");
						
						html += '<td  style="text-align:center;">'+date_new+'</td>';
						html += '<td >'+data[count].AccountName+'</td>';
						html += '<td style="text-align:right;">'+data[count].Purchamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].Discamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].cgstamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].sgstamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].igstamt+'</td>';
						html += '<td style="text-align:right;">'+data[count].Invamt+'</td>';
						html += '</tr>';
					}
					$('.table_purchase_report tbody').html(html);
					
				}
			});
		}
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var msg = "Sales Report "+from_date +" To " + to_date;
			$(".report_for").text(msg);
			load_data(from_date,to_date);
			
		});
		
	});
</script>

<script>
    function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_purchase_report");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[3];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else {
					tr[i].style.display = "none";
				}
			}       
		}
	}
</script>
<script>
    $('.add-new-transfer').on('click', function(){
		$('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
		$('#transfer-modal').modal('show');
		init_journal_entry_table();
	});
</script>
<script>
    $(document).ready(function(){
		var maxEndDate = new Date('Y/m/d');
		var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
		
		var year = "20"+fin_y;
		var cur_y = new Date().getFullYear().toString().substr(-2);
		if(cur_y > fin_y){
			var year2 = parseInt(fin_y) + parseInt(1);
			var year2_new = "20"+year2;
			
			var e_dat = new Date(year2_new+'/03/31');
			var maxEndDate_new = e_dat;
			}else{
			var maxEndDate_new = maxEndDate;
		}
		
		var minStartDate = new Date(year, 03);
		
		$('#prd_date').datetimepicker({
			format: 'd/m/Y',
			minDate: minStartDate,
			maxDate: maxEndDate_new,
			timepicker: false
		});
	});
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
			var e_dat2 = new Date(year2+'/03/31');
			var maxEndDate_new = e_dat2;
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
			timepicker: false,
			showOtherMonths: false,
			pickTime: false,
            orientation: "left",
		});
		
	});
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_purchase_report tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortablePop").removeClass("asc desc");
		$(".sortablePop span").remove();
		
		// Add sort classes and arrows
		$(this).addClass(ascending ? "asc" : "desc");
		$(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
		
		rows.sort(function (a, b) {
			var valA = $(a).find("td").eq(index).text().trim();
			var valB = $(b).find("td").eq(index).text().trim();
			
			if ($.isNumeric(valA) && $.isNumeric(valB)) {
				return ascending ? valA - valB : valB - valA;
				} else {
				return ascending
                ? valA.localeCompare(valB)
                : valB.localeCompare(valA);
			}
		});
		table.append(rows);
	});
</script>

<script type="text/javascript">
$(document).ready(function() {
    function toggleShipToAddress() {
        if ($('#useVendorAddress').is(':checked')) {
            $('#shipToAddressContainer').hide();
            $('#ShipToParty').closest('.form-group').hide();
            $('#ShipToAddress').removeAttr('required');
            $('#ShipToParty').removeAttr('required');
            $('#ShipToAddress').val('');
            $('#ShipToParty').val('');
        } else {
            $('#shipToAddressContainer').show();
            $('#ShipToParty').closest('.form-group').show();
            $('#ShipToAddress').attr('required', 'required');
            $('#ShipToParty').attr('required', 'required');
        }
    }
    
    // Initial state
    toggleShipToAddress();
    
    // Bind the change event
    $('#useVendorAddress').change(function() {
        toggleShipToAddress();
    });

    // ShipToParty change event - load shipping addresses
	$('#ShipToParty').on('change', function() {
		var ShipToParty = $(this).val();
		var BillToParty = $("#vendor").val();
		var url = "<?php echo admin_url(); ?>purchase/GetShippingAddressList";
		
		
		if(BillToParty == "" || BillToParty == null){
			alert("Please Select Vendor First");
			$("#ShipToParty").val('');
			$('.selectpicker').selectpicker('refresh');
			return;
		}
		
		if(!ShipToParty || ShipToParty == "") {
			$("#ShipToAddress").children().remove();
			$("#ShipToAddress").append('<option value="">None selected</option>');
			$('.selectpicker').selectpicker('refresh');
			return;
		}
		
		jQuery.ajax({
			type: 'POST',
			url: url,
			data: { 
				ShipToParty: ShipToParty
			},
			dataType: 'json',
			beforeSend: function() {
				// Show loading indicator if needed
				console.log('Loading shipping addresses...');
			},
			success: function(data) {
				console.log('Shipping addresses received:', data);
				
				$("#ShipToAddress").children().remove();
				// $("#ShipToAddress").append('<option value="">None selected</option>');
				
				if(data && data.length > 0 && !data.error) {
					for (var i = 0; i < data.length; i++) {
						var address = data[i];
						var shippingAddress = address.ShippingAdrees || '';
						var city = address.city_name || '';
						var state = address.state_name || address.ShippingState || '';
						var pin = address.ShippingPin || '';
						
						// Create display text similar to your order template
						var displayText = shippingAddress;
						if (city) displayText += ' ' + city;
						if (state) displayText += ' (' + state + ')';
						if (pin) displayText += ' - ' + pin;
						
						$("#ShipToAddress").append('<option value="' + address.id + '">' + displayText + '</option>');
					}
				} else {
					var errorMsg = data.error || 'No addresses found';
					$("#ShipToAddress").append('<option value="">' + errorMsg + '</option>');
					console.warn('No shipping addresses returned from server:', data);
				}
				$('.selectpicker').selectpicker('refresh');
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', error);
				console.error('Status:', status);
				console.error('Response:', xhr.responseText);
				
				$("#ShipToAddress").children().remove();
				$("#ShipToAddress").append('<option value="">Error loading addresses</option>');
				$('.selectpicker').selectpicker('refresh');
				
				alert('Error loading shipping addresses. Please check console for details.');
			}
		}); 
	});
		
	// ShipToAddress change event - get shipping details
	$('#ShipToAddress').on('change', function() {
		var ShipToAddress = $(this).val();
		var url = "<?php echo admin_url(); ?>purchase/GetShippingDetails";
		
		if(!ShipToAddress || ShipToAddress == "") {
			return;
		}
		
		jQuery.ajax({
			type: 'POST',
			url: url,
			data: { 
				ShipToAddress: ShipToAddress 
			},
			dataType: 'json',
			success: function(data) {
				console.log('Shipping details received:', data);
				
				if(data && !data.error) {
					// You can use these details if needed
					$('#shipping_state').val(data.state_name || '');
					$('#shipping_city').val(data.city_name || '');
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error in GetShippingDetails:', error);
				console.error('Response:', xhr.responseText);
			}
		});
	});
});
</script>
</html>
<?php require 'modules/purchase/assets/js/pur_order_po_js.php';?>

