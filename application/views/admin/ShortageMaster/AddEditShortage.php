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
									<li class="breadcrumb-item active text-capitalize"><b>Transaction</b></li>
									<li class="breadcrumb-item active" aria-current="page"><b>Shortage Qty</b></li>
								</ol>
							</nav>
							<hr class="hr_style">							
						</div>
					</div>	
					<div class="row">
						<?php							
							$selected_company = $this->session->userdata('root_company');								 
							if($selected_company == 1){ 
								$__number = get_option('next_shortage_number');
							} 
							
							$prefix = "SHR".$this->session->userdata('finacial_year');							
							$ShortID = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);		 
						?>
						
						<div class="col-md-2">
							<div class="form-group">
								<label for="ShortID">ShortID</label>
								<div class="input-group"><span class="input-group-addon"><?php echo $prefix;?></span>
									<input type="text" id="ShortID" name="ShortID" class="form-control ShortID" readonly value="<?php echo $ShortID; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-2">								 
							<?php                               
								$fy = $this->session->userdata('finacial_year');
								$fy_new  = $fy + 1;
								$lastdate_date = '20'.$fy_new.'-03-31';
								$curr_date = date('Y-m-d');
								$curr_date_new    = new DateTime($curr_date);
								$last_date_yr = new DateTime($lastdate_date);
								if($last_date_yr < $curr_date_new){
									$date1 = $lastdate_date;
									} else {
									$date1 = _d(date('Y-m-d'));
								}								
							echo render_date_input('date','Short Date',$date1); ?>							
						</div>
						<div class="col-md-2">	
							<div class="form-group">
								<label for="ChallanNo">Challan Number</label>
								<input type="text" id="ChallanNo" name="ChallanNo" placeholder="CHL<?= $fy?>00001"  style="text-transform:uppercase;" class="form-control">
							</div>
						</div>
					</div>	
					
					<div class="row">				
						<div class="col-md-2">					
							<div class="form-group">
								<label for="challan_vehicle">Vehicle NO</label>
								<input type="text" name="challan_vehicle" id="challan_vehicle" class="form-control" readonly>
							</div>
						</div>						
						<div class="col-md-2">	
							<div class="form-group">
								<label for="challan_driver">Driver Name</label>
								<input type="text" name="challan_driver" id="challan_driver" class="form-control" readonly>
							</div>
						</div>					
						<div class="col-md-2">	
							<div class="form-group">
								<label for="challan_route">Route Name</label>
								<input type="text" name="challan_route" id="challan_route" class="form-control" readonly>
							</div>	
						</div>					
					</div>
					
					<div class="clearfix"></div>
					<!-- ******************** Table start ***********************  -->
					<div class="row">		
						<!-- order table-->
						<div class="col-md-12">
							<br>
							<span id="searchh2" class="searchh2" style="display:none;">Loading....</span>
							<div id="showtable" class="showtable">				
								
							</div>
						</div>
					</div>				
					<!-- **************** Table end ************************** -->		
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" style="float:right; margin-top:10px">
								
								<?php if (has_permission_new('ShortageEntry', '', 'create')) { ?>
									<button type="button" id="saveShortageBtn" class="btn btn-primary" style="display:none;">Save</button>
								<?php } ?>   
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>
<?php init_tail(); ?>
<style>
	.chk {
	margin: 0;
	transform: scale(1.2);
	}
	
	/* Validation Styles */
	.shortage-input:focus {
	border-color: #438EB9 !important;
	box-shadow: 0 0 5px rgba(67, 142, 185, 0.3) !important;
	}
	.shortage-input.invalid {
	border-color: #dc3545 !important;
	background-color: #f8d7da !important;
	}
	.shortage-input.valid {
	border-color: #28a745 !important;
	background-color: #f8fff8 !important;
	}
	
	/* Validation Popup */
	#validation-popup {
	position: absolute;
	background: #ffebee;
	color: #c62828;
	padding: 8px 12px;
	border: 1px solid #ffcdd2;
	border-radius: 4px;
	font-size: 12px;
	z-index: 10000;
	box-shadow: 0 2px 10px rgba(0,0,0,0.1);
	max-width: 300px;
	white-space: nowrap;
	}
</style>	
<script>
	$(function() {
		let isShortageExists = false; // Global flag to track if shortage exists
		
		function showValidationPopup(element, message, type = 'danger') {
			alert(message);
		}
		
		function checkShortageExists(challanNumber) {
			if (!challanNumber || challanNumber.trim() === '') {
				showValidationPopup(null, 'Please enter a Challan Number first.', 'warning');
				$('#ChallanNo').focus();
				return Promise.resolve(false);
			}
			
			return new Promise((resolve) => {
				$.ajax({
					url: admin_url + 'ShortQtyMaster/check_shortage_exists',
					type: 'POST',
					data: { ChallanNo: challanNumber },
					success: function(response) {
						try {
							const responseData = JSON.parse(response);
							isShortageExists = responseData.exists;
							resolve(isShortageExists);
							} catch (e) {
							console.error("Error parsing shortage check response:", e);
							isShortageExists = false;
							resolve(false);
						}
					},
					error: function() {
						console.error("Failed to check shortage existence");
						isShortageExists = false;
						resolve(false);
					}
				});
			});
		}
		
		function clearChallanFields() {
			$('#challan_vehicle').val('');
			$('#challan_driver').val('');
			$('#challan_route').val('');
			$('#showtable').html('<table width="100%" id="challan_data" style="border: 1px solid #50617b; border-collapse: collapse;overflow: scroll; white-space: nowrap;"><thead style="background: #50617b; color: #FFF;"><tr><th colspan="8" class="text-center" style="border: 1px solid #50617b; padding: 8px;">jy </th></tr></thead><tbody></tbody></table>');
			
			//$('#showtable').hide();
			
			// Hide save button when clearing fields
			$('#saveShortageBtn').hide();
			
			// Reset the shortage exists flag when clearing
			isShortageExists = false;
		}
		
		function clearAllFields() {
			// Clear all form fields including ChallanNo
			$('#ChallanNo').val('');
			$('#challan_vehicle').val('');
			$('#challan_driver').val('');
			$('#challan_route').val('');
			$('#showtable').html('<table width="100%" id="challan_data" style="display:none; border: 1px solid #50617b; border-collapse: collapse; overflow: scroll; white-space: nowrap;"><thead style="background: #50617b; color: #FFF;"><tr><th colspan="8" class="text-center" style="border: 1px solid #50617b; padding: 8px;">No data available</th></tr></thead><tbody></tbody></table>');
			
			// Hide save button
			$('#saveShortageBtn').hide();
			
			// Reset the shortage exists flag
			isShortageExists = false;
		}
		
		function showSaveButton() {
			$('#saveShortageBtn').show();
		}
		
		function hideSaveButton() {
			$('#saveShortageBtn').hide();
		}
		
		async function fetchAndPopulateChallanDetails() {
			var challan_number = $('#ChallanNo').val().trim();
			
			// Validate Challan Number is not empty
			if (challan_number.length === 0) {
				showValidationPopup(null, 'Please enter a Challan Number to fetch details.', 'warning');
				$('#ChallanNo').focus();
				clearChallanFields();
				hideSaveButton();
				return;
			}
			
			$('#searchh2').show();
			hideSaveButton(); // Hide button while loading
			
			try {
				// First check if shortage already exists for this challan
				const shortageExists = await checkShortageExists(challan_number);
				
				if (shortageExists) {
					showValidationPopup(null, 'Shortage data already exists for this Challan Number. Please use a different Challan Number.', 'warning');
					$('#ChallanNo').val('').focus();
					clearChallanFields();
					hideSaveButton();
					$('#searchh2').hide();
					return; // Stop execution if shortage exists
				}
				
				// If shortage doesn't exist, then fetch challan details
				const response = await $.post(admin_url + 'ShortQtyMaster/get_challan_data', {
					ChallanNo: challan_number
				});
				
				const responseData = JSON.parse(response);
				if (responseData.success) {
					var data = responseData.data;
					$('#challan_vehicle').val(data.vehicle_no || '');
					$('#challan_driver').val(data.driver_name || '');
					$('#challan_route').val(data.route_name || '');
					
					if (responseData.table_html) {
						$('#showtable').html(responseData.table_html);
						
						// Check if table has actual data (not just "No data found" message)
						setTimeout(function() {
							const hasDataRows = $('#challan_data tbody tr').length > 0;
							const firstRowText = $('#challan_data tbody tr:first td:first').text();
							
							// Show save button only if table has real data (not "No data found")
							if (hasDataRows && firstRowText !== 'No data found') {
								showSaveButton();
								} else {
								hideSaveButton();
							}
						}, 100);
						
						} else {
						clearChallanFields();
						hideSaveButton();
						showValidationPopup(null, 'No table data found for this challan.', 'warning');
					}
					} else {
					showValidationPopup(null, responseData.message || 'Challan details not found.', 'warning');
					clearChallanFields();
					hideSaveButton();
					if (responseData.table_html) {
						$('#showtable').html(responseData.table_html);
					}
					$('#ChallanNo').focus();
				}
				} catch (e) {
				console.error("Error:", e);
				showValidationPopup(null, 'Error fetching challan details. Please try again.', 'danger');
				clearChallanFields();
				hideSaveButton();
				$('#ChallanNo').focus();
				} finally {
				$('#searchh2').hide();
			}
		}
		
		function validateShortageQuantity(inputElement) {
			if (isShortageExists) {
				showValidationPopup(inputElement, 'Shortage data already exists for this Challan. Cannot enter shortage quantities.', 'warning');
				inputElement.value = '';
				return false;
			}
			
			const shortageQty = inputElement.value.trim();
			const billQty = parseInt(inputElement.getAttribute('data-billqty')) || 0;
			
			// Allow empty field (user might be clearing)
			if (shortageQty === '') {
				return true;
			}
			
			// Check if it's a valid number
			if (isNaN(shortageQty) || shortageQty === '') {
				showValidationPopup(inputElement, 'Please enter a valid number for shortage quantity.', 'warning');
				inputElement.value = '';
				inputElement.focus();
				return false;
			}
			
			const shortageQtyNum = parseInt(shortageQty);
			
			// Check if greater than 0
			if (shortageQtyNum <= 0) {
				showValidationPopup(inputElement, 'Shortage quantity must be greater than 0.', 'warning');
				inputElement.value = '';
				inputElement.focus();
				return false;
			}
			
			// Check if doesn't exceed bill quantity
			if (shortageQtyNum > billQty) {
				showValidationPopup(inputElement, `Shortage quantity (${shortageQtyNum}) cannot exceed Bill Quantity (${billQty}).`, 'warning');
				inputElement.value = '';
				inputElement.focus();
				return false;
			}
			
			return true;
		}
		
		async function saveShortageData() {
			const challan_number = $('#ChallanNo').val().trim();
			
			// Validate Challan Number
			if (!challan_number) {
				showValidationPopup(null, 'Please enter a Challan Number before saving.', 'warning');
				$('#ChallanNo').focus();
				return;
			}
			
			// Check if shortage already exists
			const shortageExists = await checkShortageExists(challan_number);
			if (shortageExists) {
				showValidationPopup(null, 'Shortage data already exists for this Challan. Cannot save.', 'warning');
				return;
			}
			
			// Check if at least one shortage quantity is entered
			const enteredShortages = $('.shortage-input:not([disabled])').filter(function() {
				return $(this).val().trim() !== '';
			}).length;
			
			if (enteredShortages === 0) {
				showValidationPopup(null, 'Please enter at least one shortage quantity before saving.', 'warning');
				// Focus on first available shortage input
				const firstInput = $('.shortage-input:not([disabled])').first();
				if (firstInput.length) {
					firstInput.focus();
				}
				return;
			}
			
			// Validate all non-empty shortage inputs
			let hasErrors = false;
			let firstErrorElement = null;
			
			$('.shortage-input:not([disabled])').each(function() {
				if ($(this).val().trim() !== '') {
					if (!validateShortageQuantity(this)) {
						hasErrors = true;
						if (!firstErrorElement) {
							firstErrorElement = this;
						}
					}
				}
			});
			
			if (hasErrors) {
				showValidationPopup(null, 'Please fix the validation errors before saving.', 'danger');
				if (firstErrorElement) {
					firstErrorElement.focus();
				}
				return;
			}
			
			// Prepare and send form data
			var formData = new FormData();
			formData.append('ChallanNo', challan_number);
			formData.append('ShortID', $('#ShortID').val());
			formData.append('date', $('#date').val());
			
			// Add shortage quantities to form data
			$('.shortage-input:not([disabled])').each(function() {
				if ($(this).val().trim() !== '') {
					const orderId = $(this).data('orderid');
					const itemCode = $(this).data('itemcode');
					const shortageQty = $(this).val();
					formData.append(`shortageQty[${orderId}][${itemCode}]`, shortageQty);
				}
			});
			
			try {
				const response = await $.ajax({
					url: admin_url + 'ShortQtyMaster/save_shortage',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false
				});
				
				const responseData = JSON.parse(response);
				if (responseData.success) {
					showValidationPopup(null, responseData.message, 'success');
					setTimeout(function() {
						window.location.href = admin_url + 'ShortQtyMaster';
					}, 2000);
					} else {
					showValidationPopup(null, responseData.message, 'warning');
				}
				} catch (error) {
				showValidationPopup(null, 'Failed to save shortage data. Please try again.', 'danger');
			}
		}
		
		// --- EVENT LISTENERS ---
		
		// Real-time validation as user types in shortage inputs
		$(document).on('input', '.shortage-input', function() {
			validateShortageQuantity(this);
		});
		
		// Save button event listener
		$(document).on('click', '#saveShortageBtn', function() {
			saveShortageData();
		});
		
		// Event handlers for fetching challan data
		$('#ChallanNo').on('dblclick', function() {
			fetchAndPopulateChallanDetails();
		});
		
		$('#ChallanNo').on('change', function() {
			const challanNumber = $(this).val().trim();
			if (challanNumber.length > 0) {
				fetchAndPopulateChallanDetails();
				} else {
				clearChallanFields();
				hideSaveButton();
				showValidationPopup(null, 'Please enter a Challan Number to fetch details.', 'info');
			}
		});
		
		// Clear form when ChallanNo gets focus
		$('#ChallanNo').on('focus', function() {
			clearAllFields();
		});
		
		// Enter key support for ChallanNo field
		$('#ChallanNo').on('keypress', function(e) {
			if (e.which === 13) { // Enter key
				fetchAndPopulateChallanDetails();
			}
		});
		
		// Initialize
		clearAllFields();
		hideSaveButton();
	});
</script>	