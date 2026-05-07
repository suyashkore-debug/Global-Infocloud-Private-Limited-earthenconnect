<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
	thead, th{
	top:0px;
	position:sticky;
	z-index:20;
	}
	.fixed-header{
	z-index:50;
	}
	.col-id-ordid{
	position:sticky !important;
    left: 0px;
	min-width:85px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-custname{
	left:85px;
	position:sticky !important;
	min-width:190px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-custstate{
	left:275px;
	position:sticky !important;
	min-width:46px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-custRoute{
	left:321px;
	position:sticky !important;
	min-width:90px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-ordtype{
	left:411px;
	position:sticky !important;
	min-width:83px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-saleid{
	left:494px;
	position:sticky !important;
	min-width:85px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-saledate{
	left:579px;
	position:sticky !important;
	min-width:84px;
	background-color:#438eb9;
	color:#fff;
	}
	th, td{
	border:1px solid #fff;
	}
	
	#challan_data th,td{
	padding:1px 3px;
	/*border:1px solid #ccc;*/
	}
	#challan_data input[type=text] {
	height: 27px;
	text-align: right;
	}
	.bg-an {
	background-color: #65baba;
    white-space: nowrap;
	}
</style>

<div id="wrapper">
    <div class="content">  
        <div class="row">
            <div class="panel_s invoice accounting-template">
                <div class="panel-body" style="padding-top:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
                                    <li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
                                    <li class="breadcrumb-item"><a href="<?= admin_url('ShortQtyMaster/shortage_list');?>"><b>Shortage List</b></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><b>Edit Shortage</b></li>
								</ol>
							</nav>
                            <hr class="hr_style">
						</div>
					</div>
					
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ShortID">ShortID</label>
                                <div class="input-group">
                                    
                                    <input type="text" id="ShortID" name="ShortID" class="form-control ShortID" 
									value="<?= isset($shortage_master->ShortageID) ? $shortage_master->ShortageID : '' ?>" readonly>
								</div>
							</div>
						</div>
                        <div class="col-md-2">
                            <?php
                                // Format the date to dd/mm/YYYY
                                $short_date = '';
                                if (isset($shortage_master->TrasDate)) {
                                    $short_date = date('d/m/Y', strtotime($shortage_master->TrasDate));
									} else {
                                    $short_date = date('d/m/Y'); // Current date in dd/mm/YYYY format
								}
                                echo render_date_input('date', 'Short Date', $short_date);
							?>
							
						</div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ChallanNo">Challan Number</label>
                                <input type="text" id="ChallanNo" name="ChallanNo" class="form-control" 
								value="<?= isset($shortage_master->ChallanID) ? $shortage_master->ChallanID : '' ?>" readonly>
							</div>
						</div>
					</div>
					
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="challan_vehicle">Vehicle NO</label>
                                <input type="text" name="challan_vehicle" id="challan_vehicle" class="form-control" 
								value="<?= isset($challan_details['vehicle_no']) ? $challan_details['vehicle_no'] : '' ?>" readonly>
							</div>
						</div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="challan_driver">Driver Name</label>
                                <input type="text" name="challan_driver" id="challan_driver" class="form-control" 
								value="<?= isset($challan_details['driver_name']) ? $challan_details['driver_name'] : '' ?>" readonly>
							</div>
						</div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="challan_route">Route Name</label>
                                <input type="text" name="challan_route" id="challan_route" class="form-control" 
								value="<?= isset($challan_details['route_name']) ? $challan_details['route_name'] : '' ?>" readonly>
							</div>
						</div>
					</div>
					
                    <div class="clearfix"></div>
					
                    <!-- Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="showtable" class="showtable">
                            <div style="max-height: 500px; overflow: auto; white-space: nowrap;">
                                <?php if (isset($table_data) && !empty($table_data)): ?>
								<table width="100%" id="challan_data"  style="border: 1px solid #fff; display: block;overflow: scroll;white-space: nowrap;height: 300px;">
									<thead  style="background: #438EB9; color: #FFF;">
										<tr>
											<th class="col-id-ordid fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">OrderNo</th>
											<th class="col-id-custname fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">AccountName</th>
											<th class="col-id-custstate fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">StateID</th>
											<th class="col-id-custRoute fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">Route Name</th>
											<th class="col-id-ordtype fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">Ordertype</th>
											<th class="col-id-saleid fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">SalesID</th>
											<th class="col-id-saledate fixed-header" rowspan="2" style="border: 1px solid #fff; padding: 1px 3px;">SalesDate</th>
											
											<?php foreach ($ORDItem as $item_code): ?>
											<?php
												$item = $this->db->get_where('tblitems', array('item_code' => $item_code['ItemID']))->row();
												$title = $item ? $item->description : $item_code['ItemID'];
											?>
											<th width="5%" colspan="2" title="<?= $title ?>" style="border: 1px solid #fff;text-align:center; padding: 1px 3px;"><?= $item_code['ItemID'] ?></th>
											<?php endforeach; ?>
										</tr>
										<tr>
											<?php foreach ($ORDItem as $item_code): ?>
											<th width="2.5%" style="border: 1px solid #fff;text-align:center; padding: 1px 3px;">Bill Qty</th>
											<th width="2.5%" style="border: 1px solid #fff;text-align:center; padding: 1px 3px;">Short Qty</th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($table_data as $row): ?>
										<?php
											$order_id = !empty($row['OrderID']) ? $row['OrderID'] : '';
											
											// Format SalesDate to dd/mm/YYYY
											$sales_date = '';
											if (!empty($row['SalesDate'])) {                 
												if (is_numeric($row['SalesDate'])) {
													$sales_date = date('d/m/Y', $row['SalesDate']);
                                                    } else {                   
													$timestamp = strtotime($row['SalesDate']);
													$sales_date = $timestamp ? date('d/m/Y', $timestamp) : '';
												}
											}
										?>
										<tr class= "bg-an">
											<td  class="col-id-ordid" style="padding: 1px 3px;"><?= $order_id ?></td>
											<td class="col-id-custname" style="padding: 1px 3px;"><?= !empty($row['AccountName']) ? $row['AccountName'] : '' ?></td>
											<td class="col-id-custstate" style="padding: 1px 3px;"><?= !empty($row['StateID']) ? $row['StateID'] : '' ?></td>
											<td class="col-id-custRoute" style="padding: 1px 3px;"><?= !empty($row['RouteName']) ? $row['RouteName'] : '' ?></td>
											<td class="col-id-ordtype" style="padding: 1px 3px;">
												<?= !empty($row['OrderType']) ? ($row['OrderType'] == 'T' ? 'TaxItems' : ($row['OrderType'] == 'B' ? 'NonTaxItems' : $row['OrderType'])) : '' ?>
											</td>
											<td class="col-id-saleid" style="padding: 1px 3px;"><?= !empty($row['SalesID']) ? $row['SalesID'] : '' ?></td>
											<td class="col-id-saledate" style="padding: 1px 3px;"><?= $sales_date ?></td>
											
											<?php foreach($ORDItem as $item_code): ?>
											<?php
												$quantity = 0;
												$item_id = $item_code['ItemID'];
												if (isset($item_quantities[$order_id][$item_id])) {
													$quantity = (int) $item_quantities[$order_id][$item_id];
												}
												
												// Get existing shortage quantity
												$existing_shortage = 0;
												if (isset($shortage_details[$order_id][$item_id])) {
													$existing_shortage = (int) $shortage_details[$order_id][$item_id]['ShortageQty'];
												}
												
												$disabled = ($quantity == 0) ? 'disabled' : '';
												$disabled_style = ($quantity == 0) ? 'background-color: #999; color: #999;' : '';
											?>
											<!-- Bill Qty Column -->
											<td style="border: 1px solid #fff; padding: 1px 3px; text-align: center;">
												<?= $quantity ?>
											</td>
											<!-- Shortage Qty Column -->
											<td style="border: 1px solid #fff; padding: 1px 3px; text-align: center;">
												<input type="text" 
												name="shortageQty[<?= $order_id ?>][<?= $item_id ?>]" 
												class="shortage-input"
												data-billqty="<?= $quantity ?>"
												data-orderid="<?= $order_id ?>"
												data-itemcode="<?= $item_id ?>"
												style="width: 100%; border: 1px solid #ccc; padding: 3px; text-align: center; <?= $disabled_style ?>" 
												value="<?= $existing_shortage ?>" 
												<?= $disabled ?>
												onkeypress="return validateShortageInput(event, this)"
												onblur="validateShortageQuantity(this)">
											</td>
											<?php endforeach; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
                                <?php else: ?>
								<?php 
									$colspan = 7 + (count($ORDItem) * 2);
								?>
								<table width="100%" id="challan_data" style="border: 1px solid #438eb9; border-collapse: collapse; overflow: scroll; white-space: nowrap;">
									<thead style="background: #438EB9; color: #FFF;">
										<tr>
											<th colspan="<?= $colspan ?>" class="text-center" style="border: 1px solid #fff; padding: 8px;">No data available</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
                                <?php endif; ?>
							</div>
							</div>
						</div>
					</div>
					
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"style="float:right; margin-top:10px">
								
								<?php if (has_permission_new('ShortageEntry', '', 'edit')) { ?>
									<button type="button" id="updateShortageBtn" class="btn btn-primary">Update</button>
								<?php } ?>   
                                <a href="<?= admin_url('ShortQtyMaster/shortage_list') ?>" class="btn btn-default">Back to List</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>

<script>
	
	// Add these validation functions that are referenced in the input fields
	function validateShortageInput(event, element) {
		// Allow only numbers and backspace
		var charCode = (event.which) ? event.which : event.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
			return false;
		}
		return true;
	}
	
	function validateShortageQuantity(element) {
		var shortageQty = parseInt(element.value) || 0;
		var billQty = parseInt(element.getAttribute('data-billqty')) || 0;
		
		if (shortageQty < 0) {
			// Show alert dialog
			alert('Shortage quantity cannot be negative'); 
			// Clear the input field
			element.value = '';
			// Focus on the input field
			element.focus();
			return false;
		}
		
		if (shortageQty > billQty) {
			// Show alert dialog
			alert('Shortage quantity cannot exceed bill quantity (' + billQty + ')');
			// Clear the input field
			element.value = '';
			// Focus on the input field
			element.focus();
			return false;
		}
		
		return true;
	}
	
	$(function() {
		// Simple validation function
		function validateBeforeUpdate() {
			let hasErrors = false;
			let errorMessage = '';
			
			$('input[name^="shortageQty"]:not([disabled])').each(function() {
				const input = $(this);
				const shortageQty = input.val().trim();
				
				// Skip empty inputs
				if (shortageQty === '') {
					return true;
				}
				
				const billQty = parseInt(input.data('billqty'));
				const shortageQtyNum = parseInt(shortageQty);
				
				// Validation checks
				if (isNaN(shortageQtyNum)) {
					hasErrors = true;
					errorMessage = 'Please enter valid numbers for shortage quantities';
					return false;
				}
				
				if (shortageQtyNum < 0) {
					hasErrors = true;
					errorMessage = 'Shortage quantities cannot be negative';
					return false;
				}
				
				if (shortageQtyNum > billQty) {
					hasErrors = true;
					errorMessage = `Shortage quantity (${shortageQty}) cannot exceed bill quantity (${billQty})`;
					return false;
				}
			});
			
			return {
				hasErrors: hasErrors,
				errorMessage: errorMessage
			};
		}
		
		// Update shortage data
		function updateShortageData() {
			const validation = validateBeforeUpdate();
			
			if (validation.hasErrors) {
				alert(validation.errorMessage);
				
				return;
			}
			
			// Check if at least one shortage quantity is entered
			const enteredShortages = $('input[name^="shortageQty"]:not([disabled])').filter(function() {
				return $(this).val().trim() !== '';
			}).length;
			
			if (enteredShortages === 0) {
				alert('Please enter at least one shortage quantity before updating.');
				// alert_float('warning', 'Please enter at least one shortage quantity before updating.');
				return;
			}
			
			var formData = new FormData();
			formData.append('ShortID', $('#ShortID').val());
			formData.append('date', $('#date').val());
			formData.append('ChallanNo', $('#ChallanNo').val());
			
			// Collect all shortage quantities
			$('input[name^="shortageQty"]').each(function() {
				var name = $(this).attr('name');
				var value = $(this).val();
				formData.append(name, value);
			});
			
			$.ajax({
				url: '<?= admin_url('ShortQtyMaster/update_shortage') ?>',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					try {
						var responseData = JSON.parse(response);
						if (responseData.success) {
							// alert(responseData.message);
							alert_float('success', responseData.message);
							
							setTimeout(function() {
								location.reload();
							}, 1500);
							} else {
							alert(responseData.message);
							// alert_float('warning', responseData.message);
						}
						} catch (e) {
						alert('Error processing response');
						// alert_float('danger', 'Error processing response');
					}
				},
				
			});
		}
		
		// Add update button event listener
		$(document).on('click', '#updateShortageBtn', function() {
			updateShortageData();
		});
	});
</script>