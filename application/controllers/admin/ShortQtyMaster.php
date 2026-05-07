<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class ShortQtyMaster extends AdminController
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('invoices_model');
			$this->load->model('order_model');
			$this->load->model('challan_model');
			$this->load->model('clients_model');
			$this->load->model('invoice_items_model');
			$this->load->helper('sales_helper');
		}
		
		
		public function index()
		{ 			
			if (!has_permission_new('ShortageEntry', '', 'view')) {
				access_denied('invoices');
			}
			$data['title'] = "Short Dispatch Quantity";
			$DriverType = "1000159";
			$data['DriverList']    = $this->clients_model->GetStaffListTypeWise($DriverType);
			$data['vehicle']    = $this->clients_model->getvehicle();
			$data['routes']    = $this->clients_model->getroute();
			
			
			// $data['ORDItem'] = ['ITEM001', 'ITEM002', 'ITEM003'];
			
			
			$this->load->view('admin/ShortageMaster/AddEditShortage', $data);
		}
		
		
		//-------------****************************************************--------------------	
		public function check_shortage_exists()
		{
			$challan_number = $this->input->post('ChallanNo');
			
			//log_message('debug', 'Checking shortage existence for ChallanNo: ' . $challan_number);
			
			if (empty($challan_number)) {
				echo json_encode(['exists' => false]);
				return;
			}
			
			$this->db->where('ChallanID', $challan_number);
			$query = $this->db->get('tblShortageMaster');
			
			$exists = $query->num_rows() > 0;
			
			//log_message('debug', 'Shortage exists: ' . ($exists ? 'YES' : 'NO'));
			
			echo json_encode(['exists' => $exists]);
		}
		
		public function get_challan_data()
		{
			$challan_number = $this->input->post('ChallanNo');
			
			if (empty($challan_number)) {
				echo json_encode(['success' => false, 'message' => 'Challan Number is required.']);
				return;
			}
			$UserID = $this->session->userdata('username');
			
			// Get challan basic details
			$challan_details = $this->challan_model->get_challan_details_by_id($challan_number);
			
			// In get_challan_data function, after getting challan_details
			//$shortage_exists = $this->challan_model->check_shortage_exists($challan_number);
			
			// Get order table details
			$table_data = $this->challan_model->getChallanOrderTableDetails($challan_number);
			
			// Get item quantities from history table
			$item_quantities = $this->challan_model->getChallanItemQuantities($challan_number);
			
			// Get distinct items for this challan
			$ORDItem = $this->getDistinctItemsForChallan($challan_number);
			
			// Generate complete table HTML (header + body)
			$table_html = $this->generateCompleteTableHtml($table_data, $item_quantities, $ORDItem);
			
			if ($challan_details){
				echo json_encode([ 
				'success' => true, 
				'data' => $challan_details, 
				'table_html' => $table_html,
				'shortage_exists' => $shortage_exists
				]);
				} else {
				echo json_encode([
				'success' => false, 
				'message' => 'Challan not found or details missing.',
				'table_html' => '<table width="100%" id="challan_data" style="border: 1px solid #ccc; border-collapse: collapse; overflow: scroll; white-space: nowrap;"><thead style="background: #ccc; color: #FFF;"><tr><th colspan="8" class="text-center" style="border: 1px solid #ccc; padding: 1px 3px;">No data available</th></tr></thead><tbody></tbody></table>'
				]);
			}
		}
		
		
		private function getDistinctItemsForChallan($challan_number)
		{
			$prefix = db_prefix();
			
			$this->db->select('DISTINCT(tblhistory.ItemID),tblitems.description');
			$this->db->from($prefix . 'history');
			$this->db->join('tblitems', 'tblitems.item_code  = tblhistory.ItemID');
			$this->db->where('tblhistory.BillID', $challan_number);
			
			$result = $this->db->get()->result_array();
			
			return $result;
		}
		
		private function generateCompleteTableHtml($table_data, $item_quantities, $ORDItem)
		{
			// Generate table header
			$thead_html = '<thead style="background: #438EB9; color: #FFF;"><tr>';
			$thead_html .= '<th class="col-id-ordid fixed-header" rowspan="2" style="padding: 1px 3px;">OrderNo</th>';
			$thead_html .= '<th class="col-id-custname fixed-header" rowspan="2" style="padding: 1px 3px;">AccountName</th>';
			$thead_html .= '<th class="col-id-custstate fixed-header" rowspan="2" style="padding: 1px 3px;">StateID</th>';
			$thead_html .= '<th class="col-id-custRoute fixed-header" rowspan="2" style="padding: 1px 3px;">Route Name</th>';
			$thead_html .= '<th class="col-id-ordtype fixed-header" rowspan="2" style="padding: 1px 3px;">Ordertype</th>';
			$thead_html .= '<th class="col-id-saleid fixed-header" rowspan="2" style="padding: 1px 3px;">SalesID</th>';
			$thead_html .= '<th class="col-id-saledate fixed-header" rowspan="2" style="padding: 1px 3px;">SalesDate</th>';
			
			// Add item columns to header
			foreach ($ORDItem as $item_code) {
				$thead_html .= '<th width="5%" colspan="2" title="' . $item_code["description"] . '" style="text-align:center; padding:1px 3px;">' . $item_code["ItemID"] . '</th>';
			}
			$thead_html .= '</tr>';
			$thead_html .= '<tr>';
			foreach ($ORDItem as $item_code) {
				$thead_html .= '<th width="2.5%" style="text-align:center; padding: 1px 3px;">Bill Qty</th>';
				$thead_html .= '<th width="2.5%" style="text-align:center; padding: 1px 3px;">Short Qty</th>';
			}
			$thead_html .= '</tr>';
			$thead_html .= '</tr></thead>';
			
			// Generate table body
			$tbody_html = '<tbody>';
			
			if(!empty($table_data)){
				foreach ($table_data as $row) {
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
					
					$tbody_html .= '<tr class= "bg-an">';
					
					$tbody_html .= '<td class="col-id-ordid" style="padding: 1px 3px;">' . $order_id . '</td>';
					$tbody_html .= '<td class="col-id-custname" style="padding:1px 3px;">' . (!empty($row['AccountName']) ? $row['AccountName'] : '') . '</td>';
					$tbody_html .= '<td class="col-id-custstate" style="padding: 1px 3px;">' . (!empty($row['StateID']) ? $row['StateID'] : '') . '</td>';
					$tbody_html .= '<td class="col-id-custRoute style="padding: 1px 3px;">' . (!empty($row['RouteName']) ? $row['RouteName'] : '') . '</td>';
					$tbody_html .= '<td class="col-id-ordtype" style="padding: 1px 3px;">' . 
					(!empty($row['OrderType']) ? 
					($row['OrderType'] == 'T' ? 'TaxItems' : 
					($row['OrderType'] == 'B' ? 'NonTaxItems' : $row['OrderType'])) 
					: '') . 
					'</td>';
					$tbody_html .= '<td class="col-id-saleid" style="padding: 1px 3px;">' . (!empty($row['SalesID']) ? $row['SalesID'] : '') . '</td>';
					$tbody_html .= '<td class="col-id-saledate" style="padding: 1px 3px;">' . $sales_date . '</td>';
					
					// Add item quantity columns with shortage quantity input
					foreach ($ORDItem as $item_code) {
						$quantity = 0;
						$ItemID = $item_code["ItemID"];
						if (isset($item_quantities[$order_id][$ItemID])) {
							$quantity = (int) $item_quantities[$order_id][$ItemID];
						}
						
						$disabled = ($quantity == 0) ? 'disabled' : '';
						$disabled_style = ($quantity == 0) ? 'background-color: #999;text-align:center; color: #999;' : '';
						
						$tbody_html .= '<td style="text-align:center;">' . $quantity . '</td>';
						$tbody_html .= '<td style="text-align:center;">';
						$tbody_html .= '<input type="text" name="shortageQty[' . $order_id . '][' . $ItemID . ']" 
						class="shortage-input"
						data-billqty="' . $quantity . '"
						data-orderid="' . $order_id . '"
						data-itemcode="' . $ItemID . '"
						style="width: 100%; padding: 3px; text-align: center; ' . $disabled_style . '" 
						value="" ' . $disabled . ' 
						onkeypress="return validateShortageInput(event, this)"
						onblur="validateShortageQuantity(this)">';
						$tbody_html .= '</td>';
					}
					
					$tbody_html .= '</tr>';
				}
				} else {
				$colspan = 7 + (count($ORDItem) * 2);
				$tbody_html .= '<tr><td colspan="' . $colspan . '" class="text-center" style="border: 1px solid #438eb9; padding: 2px 6px;">No data found</td></tr>';
			}
			
			$tbody_html .= '</tbody>';
			
			// Combine header and body
			$complete_table = '
			<div id="challan_wrapper">
			<table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;height: 300px;">
			' . $thead_html . $tbody_html . '
			</table>
			</div>';
			
			return $complete_table;
		}
		
		
		// Save shortage data
		public function save_shortage()
		{
			if ($this->input->post()) {
				$financial_year = $this->session->userdata('finacial_year');
				$UserID = $this->session->userdata('username');
				$current_date = date('Y-m-d H:i:s');
				
				// Get form data
				$shortage_id = $this->input->post('ShortID');
				$short_date = to_sql_date($this->input->post('date'))." ".date('H:i:s');
				$challan_no = $this->input->post('ChallanNo');
				$shortage_quantities = $this->input->post('shortageQty');
				
				$prefix = "SHR" . $financial_year;
				$ShortageID = $prefix . $shortage_id;
				// Save to tblShortageMaster
				$master_data = array(
				'ShortageID' => $ShortageID,
				'TrasDate' => $short_date,
				'ChallanID' => $challan_no,
				'UserID' => $UserID,
				'UserID2' => $UserID,
				'Lupdate' => $current_date
				);
				
				$this->db->insert('tblShortageMaster', $master_data);
				$master_id = $this->db->insert_id();
				
				// Save to tblShortageDetails
				$saved_details = 0;
				if (!empty($shortage_quantities)) {
					foreach ($shortage_quantities as $order_id => $items) {
						foreach ($items as $item_code => $shortage_qty) {
							// Only save if shortage quantity is provided and greater than 0
							if (!empty($shortage_qty) && $shortage_qty > 0) {
								// Get billed quantity from history table
								$billed_qty = $this->getBilledQuantity($challan_no, $order_id, $item_code);
								
								$detail_data = array(
								'ShortageID' => $ShortageID,
								'OrderID' => $order_id,
								'SaleID' => $this->getSaleIDFromOrder($order_id),
								'ItemID' => $item_code,
								'BilledQty' => $billed_qty,
								'ShortageQty' => $shortage_qty,
								'TransDate' => $short_date,
								'UserID' => $UserID,
								'UserID2' => $UserID,
								'Lupdate' => $current_date
								);
								
								$this->db->insert('tblShortageDetails', $detail_data);
								$saved_details++;
							}
						}
					}
				}
				
				if ($master_id) {
					// Increment the next_shortage_number in tbloptions
					$this->incrementShortageNumber();
					
					// Get the next ShortID for the response
					$next_short_id = $this->getNextShortID();
					
					echo json_encode([
					'success' => true, 
					'message' => 'Shortage data saved successfully. ' . $saved_details . ' items saved.',
					'next_short_id' => $next_short_id
					]);
					} else {
					echo json_encode(['success' => false, 'message' => 'Failed to save shortage data']);
				}
			}
		}
		
		// Function to increment the shortage number in tbloptions
		private function incrementShortageNumber()
		{
			$selected_company = $this->session->userdata('root_company');
			
			if ($selected_company == 1) {
				// Get current next_shortage_number
				$current_number = get_option('next_shortage_number');
				
				// Increment the number
				$new_number = $current_number + 1;
				
				// Update the option in database
				update_option('next_shortage_number', $new_number);
				
				return $new_number;
			}
			
			return false;
		}
		
		// Function to get next ShortID (without incrementing)
		private function getNextShortID()
		{
			$selected_company = $this->session->userdata('root_company');
			$next_number = get_option('next_shortage_number');
			
			$prefix = "SHR" . $this->session->userdata('finacial_year');
			$ShortID = str_pad($next_number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
			
			return $ShortID;
		}
		
		
		private function getBilledQuantity($challan_no, $order_id, $item_code)
		{
			$prefix = db_prefix();
			
			$this->db->select('BilledQty');
			$this->db->from($prefix . 'history');
			$this->db->where('BillID', $challan_no);
			$this->db->where('OrderID', $order_id);
			$this->db->where('ItemID', $item_code);
			
			$result = $this->db->get()->row();
			
			return $result ? $result->BilledQty : 0;
		}
		private function getShortageDetailsData($shortage_id, $order_id, $item_code)
		{
			$prefix = db_prefix();
			
			$this->db->select('*');
			$this->db->from($prefix . 'ShortageDetails');
			$this->db->where('ShortageID', $shortage_id);
			$this->db->where('OrderID', $order_id);
			$this->db->where('ItemID', $item_code);
			
			$result = $this->db->get()->row();
			
			return $result;
		}
		
		private function getSaleIDFromOrder($order_id)
		{
			$prefix = db_prefix();
			
			$this->db->select('SalesID');
			$this->db->from($prefix . 'ordermaster');
			$this->db->where('OrderID', $order_id);
			
			$result = $this->db->get()->row();
			
			return $result ? $result->SalesID : '';
		}	
		
		// //---********************************** List SHortage  ********************************************----------
		
		public function shortage_list()
		{
			
			$data['title'] = "Shortage Quantity List";
			
			if (!has_permission_new('ShortageList', '', 'view')) {
				access_denied('invoices');
			}
			
			// Get filter parameters
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			// If no filters posted, set default to current month
			if (!$from_date && !$to_date) {
				$from_date = date('01/m/Y');
				$to_date = date('d/m/Y');
			}
			
			// Get all shortage records with filters
			$data['shortage_list'] = $this->challan_model->get_shortage_master_list($from_date, $to_date);
			$data['PartyList'] = $this->challan_model->PartyListBySales();
			$data['DriverList'] = $this->challan_model->DriverListByChallan();
			$data['VehicleList'] = $this->challan_model->VehicleListByChallan();
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			
			$this->load->view('admin/ShortageMaster/ShortageList', $data);
		}
		
		
		
		
		
		// Load shortage data via AJAX with row grouping for ShortID
		public function load_shortage_data()
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			$AccountID = $this->input->post('AccountID');
			$VehicleID = $this->input->post('VehicleID');
			$DriverID = $this->input->post('DriverID');
			
			// Use the updated, more descriptive model function name
			$shortage_list = $this->challan_model->get_detailed_shortage_list($from_date, $to_date, $AccountID, $VehicleID, $DriverID);
			
			$html = '';
			if (!empty($shortage_list)) {
				
				// 1. Pre-calculate counts for rowspan
				$shortageCounts = [];
				foreach ($shortage_list as $shortage) {
					if (!isset($shortageCounts[$shortage->ShortageID])) {
						$shortageCounts[$shortage->ShortageID] = 0;
					}
					$shortageCounts[$shortage->ShortageID]++;
				}
				
				// 2. Build HTML with grouping logic
				$rendered = []; // Tracks which ShortageIDs have been rendered
				
				foreach($shortage_list as $shortage){
					$html .= '<tr>';
					
					// Check if this is the first row for this ShortageID
					if (!in_array($shortage->ShortageID, $rendered)) {
						$rowspan = $shortageCounts[$shortage->ShortageID];
						
						// Render the columns that need to be grouped with rowspan
						$html .= '<td rowspan="' . $rowspan . '"><a href="' . admin_url('ShortQtyMaster/edit_shortage/' . $shortage->ShortageID) . '" target="_blank">' . $shortage->ShortageID . '</a></td>';
						$html .= '<td rowspan="' . $rowspan . '">' . _d($shortage->ShortDate) . '</td>';
						//$html .= '<td rowspan="' . $rowspan . '">' . (!empty($shortage->PartyName) ? $shortage->PartyName : 'N/A') . '</td>';
						$html .= '<td rowspan="' . $rowspan . '">' . (!empty($shortage->DriverName) ? $shortage->DriverName : 'N/A') . '</td>';
						$html .= '<td rowspan="' . $rowspan . '">' . (!empty($shortage->VehicleNo) ? $shortage->VehicleNo : 'N/A') . '</td>';
						$html .= '<td rowspan="' . $rowspan . '">' . $shortage->ChallanID . '</td>';
						
						
						// Mark this ShortageID as rendered
						$rendered[] = $shortage->ShortageID;
					}
					
					// Render the unique, non-grouped columns for every row
					$html .= '<td>' . $shortage->PartyName . '</td>';
					$html .= '<td>' . $shortage->OrderID . '</td>';
					$html .= '<td>' . $shortage->SaleID . '</td>';
					$html .= '<td>' . $shortage->ItemID . '</td>';
					$html .= '<td>' . (!empty($shortage->ItemName) ? $shortage->ItemName : 'N/A') . '</td>';
					$html .= '<td class="text-right">' . $shortage->BillQty . '</td>';
					$html .= '<td class="text-right">' . $shortage->ShortageQty . '</td>';
					$html .= '<td class="text-right">' . $shortage->CreatedBy . '</td>';             
					$html .= '</tr>';
				} 
				
				} else {
				// Updated colspan to match the new number of columns
				$html = '<tr><td colspan="13" class="text-center">No shortage records found</td></tr>';
			}
			
			echo $html;
		}
		
		
		
		
		
		// Export to Excel
		public function export_shortage_list()
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			$shortage_list = $this->challan_model->get_shortage_master_list($from_date, $to_date);
			
			// Set header for CSV
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=shortage_list_' . date('Y-m-d') . '.csv');
			
			$output = fopen('php://output', 'w');
			
			// Add headers
			fputcsv($output, array('Short ID', 'Short Date', 'Party Name', 'Driver Name', 'Vehicle No', 'Challan ID', 'Order ID', 'Sale ID', 'Item ID', 'Item Name', 'Bill Qty', 'Shortage Qty', 'Created By'));
			
			// Add data
			foreach ($shortage_list as $shortage) {
				fputcsv($output, array(
				$shortage->ShortageID,
				date('d/m/Y', strtotime($shortage->TrasDate)),
				$shortage->PartyName,
				$shortage->DriverName,
				$shortage->VehicleNo,
				$shortage->ChallanID,
				$shortage->OrderID,
				$shortage->SaleID,
				$shortage->ItemID,
				$shortage->ItemName,
				$shortage->BilledQty,
				$shortage->ShortageQty,
				$shortage->UserID
				));
			}
			
			fclose($output);
			exit;
		}
		
		//------******************************** Edit *******************************
		
		// Edit Shortage Record
		public function edit_shortage($shortage_id)
		{
			$data['title'] = "Edit Shortage Record";    
			$data['shortage_master'] = $this->challan_model->get_shortage_master_by_id($shortage_id);
			
			$data['shortage_details'] = $this->challan_model->get_shortage_details_by_id($shortage_id);
			
			if ($data['shortage_master']) {
				$challan_number = $data['shortage_master']->ChallanID;
				$data['challan_details'] = $this->challan_model->get_challan_details_by_id($challan_number);
				
				$data['table_data'] = $this->challan_model->getChallanOrderTableDetails($challan_number);
				
				$data['item_quantities'] = $this->challan_model->getChallanItemQuantities($challan_number);
				
				// Get distinct items for this challan
				$data['ORDItem'] = $this->getDistinctItemsForChallan($challan_number);
			}
			
			$DriverType = "1000159";
			$data['DriverList'] = $this->clients_model->GetStaffListTypeWise($DriverType);
			$data['vehicle'] = $this->clients_model->getvehicle();
			$data['routes'] = $this->clients_model->getroute();
			
			$this->load->view('admin/ShortageMaster/EditShortage', $data);
		}
		
		// Update Shortage Record
		public function update_shortage()
		{
			if ($this->input->post()) {
				$UserID = $this->session->userdata('username');
				$current_date = date('Y-m-d H:i:s');
				
				// Get form data
				$shortage_id = $this->input->post('ShortID');
				$short_date = $this->input->post('date');
				$challan_no = $this->input->post('ChallanNo');
				$shortage_quantities = $this->input->post('shortageQty');
				
				// Convert date from dd/mm/YYYY to YYYY-mm-dd
				$converted_short_date = $this->convertDateToMySQL($short_date);
				
				if (!$converted_short_date) {
					echo json_encode([
					'success' => false, 
					'message' => 'Invalid date format. Please use dd/mm/YYYY format.'
					]);
					return;
				}
				
				// Update tblShortageMaster
				$master_data = array(
				'TrasDate' => $converted_short_date,
				'UserID2' => $UserID,
				'Lupdate' => $current_date
				);
				
				$this->db->where('ShortageID', $shortage_id);
				$master_updated = $this->db->update('tblShortageMaster', $master_data);
				
				// Update tblShortageDetails
				$updated_details = 0;
				if (!empty($shortage_quantities)) {
					foreach ($shortage_quantities as $order_id => $items) {
						foreach ($items as $item_code => $shortage_qty) {
							// Only update if shortage quantity is provided and greater than 0
							if (!empty($shortage_qty) && $shortage_qty > 0) {
								// Get billed quantity from history table
								$billed_qty = $this->getBilledQuantity($challan_no, $order_id, $item_code);
								$IsAdded = $this->getShortageDetailsData($shortage_id, $order_id, $item_code);
								
								if(!empty($IsAdded)){
									
									$detail_data = array(
									'BilledQty' => $billed_qty,
									'ShortageQty' => $shortage_qty,
									'TransDate' => $converted_short_date,
									'UserID2' => $UserID,
									'Lupdate' => $current_date
									);
									
									$this->db->where('ShortageID', $shortage_id);
									$this->db->where('OrderID', $order_id);
									$this->db->where('ItemID', $item_code);
									$this->db->update('tblShortageDetails', $detail_data);
									}else{
									$detail_data = array(
									'ShortageID' => $shortage_id,
									'OrderID' => $order_id,
									'SaleID' => $this->getSaleIDFromOrder($order_id),
									'ItemID' => $item_code,
									'BilledQty' => $billed_qty,
									'ShortageQty' => $shortage_qty,
									'TransDate' => $short_date,
									'UserID' => $UserID,
									'UserID2' => $UserID,
									'Lupdate' => $current_date
									);
									
									$this->db->insert('tblShortageDetails', $detail_data);
								}
								
								if ($this->db->affected_rows() > 0) {
									$updated_details++;
								}
							} else {
								// If shortage quantity is empty or 0, delete the record
								$this->db->where('ShortageID', $shortage_id);
								$this->db->where('OrderID', $order_id);
								$this->db->where('ItemID', $item_code);
								$this->db->delete('tblShortageDetails');
							}
						}
					}
				}
				
				if ($master_updated) {
					echo json_encode([
					'success' => true, 
					'message' => 'Shortage data updated successfully. ' . $updated_details . ' items updated.'
					]);
					} else {
					echo json_encode([
					'success' => false, 
					'message' => 'Failed to update shortage data. No changes made.'
					]);
				}
			}
		}
		
		// Add this helper function to convert date format
		private function convertDateToMySQL($date)
		{
			if (empty($date)) {
				return false;
			}
			
			// Check if date is already in YYYY-mm-dd format
			if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
				return $date;
			}
			
			// Convert from dd/mm/YYYY to YYYY-mm-dd
			$date_parts = explode('/', $date);
			
			if (count($date_parts) === 3) {
				$day = $date_parts[0];
				$month = $date_parts[1];
				$year = $date_parts[2];
				
				// Validate date parts
				if (checkdate($month, $day, $year)) {
					return $year . '-' . $month . '-' . $day;
				}
			}
			
			// Try alternative parsing if the above fails
			$timestamp = strtotime(str_replace('/', '-', $date));
			if ($timestamp !== false) {
				return date('Y-m-d', $timestamp);
			}
			
			return false;
		}
		
		
		//---******************************************************************************----------
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}					