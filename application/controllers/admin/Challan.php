<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Challan extends AdminController
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
		
		/* Get all invoices in case user go on index page */
		public function index($id = '')
		{
			//$this->list_challan($id);
			//$this->challanAddEdit();
			$redUrl = admin_url('challan/challanAddEdit');
			redirect($redUrl);
		}
		
		public function itemlist(){
			$this->load->model('invoice_items_model');
			// POST data
			$postData = $this->input->post();
			
			// Get data
			$data = $this->invoice_items_model->getitem($postData);
			
			echo json_encode($data);
		}
		
		public function challan_list()
		{
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('invoices');
			}
			
			$data['title']                = " Challan List";
			$data['bodyclass']            = 'challan-total-manual';
			$this->load->view('admin/challan/challan_list', $data);
		}
		
		public function VehicleUpdate()
		{
			if (!has_permission_new('change_vehicle', '', 'view')) {
				access_denied('invoices');
			}
			
			$data['title']                = "Vehicle Update";
			$data['bodyclass']            = 'challan-total-manual';
			$DriverType = "1000159";
			$data['DriverList']    = $this->clients_model->GetStaffListTypeWise($DriverType);
			$data['ChallanList']          = $this->challan_model->GetChallanList();
			/*echo "<pre>";
				print_r($data['ChallanList']);
			die;*/
			$this->load->view('admin/challan/UpdateVehicle', $data);
		}
		
		public function UpdateCratesCasesAfterGatepass()
		{
			if (!has_permission_new('crate_update', '', 'edit')) {
					access_denied('challan');
			}
			$data = $this->input->post();
			$ChallanID = $this->input->post('ChallanID');
			$ChallanDetails = $this->challan_model->UpdateCratesCasesAfterGatepass($data,$ChallanID);
			echo json_encode($ChallanDetails);
		}
		
		/* Get Challan Vehicle by ChallanID / ajax */
		public function GetVehicleByChallan()
		{
			$ChallanID = $this->input->post('ChallanID');
			$ChallanDetails = $this->challan_model->GetVehicleByChallan($ChallanID);
			echo json_encode($ChallanDetails);
		}
		
		/* Update Exiting ItemID / ajax */
		public function UpdateVehicle()
		{
			$UserID = $this->session->userdata('username');
			$VehData = array(
            "UserID2"=>$UserID,
            "Lupdate"=>date('Y-m-d H:i:s')
			);
			if(!empty($this->input->post('NewVehicleNo'))){
				$VehData['VehicleID'] = $this->input->post('NewVehicleNo');
			}
			if(!empty($this->input->post('challan_driver'))){
				$VehData['DriverID'] = $this->input->post('challan_driver');
			}
			$VehicleNo = $this->input->post('VehicleNo');
			$ChallanID = $this->input->post('ChallanID');
			$Result         = $this->challan_model->UpdateVehicle($VehData,$VehicleNo,$ChallanID);
			echo json_encode($Result);
		}
		//==================== Get Vehicle List By DriverID ============================	
		public function GetVehicleListByDriverID()
		{
			$postData = $this->input->post();
			$VehicleData = $this->challan_model->GetVehicleListByDriverID($postData);
			echo json_encode($VehicleData);
		}
		public function accountlist_driver(){
			
			// POST data
			$postData = $this->input->post();
			// Get data
			$data = $this->challan_model->accountlist_driver($postData);
		
		echo json_encode($data);
		}
		
		public function get_Loader_Details(){
			
			// POST data
			$postData = $this->input->post();
			// Get data
			$Account_data = $this->challan_model->get_Loader_Details($postData);
			
			echo json_encode($Account_data);
		}
		public function accountlist_Loader(){
			
			// POST data
			$postData = $this->input->post();
			// Get data
			$data = $this->challan_model->accountlist_Loader($postData);
			
			echo json_encode($data);
		}
		
		public function accountlist_salesMan(){
			
			// POST data
			$postData = $this->input->post();
			// Get data
			$data = $this->challan_model->accountlist_salesMan($postData);
			
			echo json_encode($data);
		}
		
		public function get_Account_Details_salesman(){
			
			// POST data
			$postData = $this->input->post();
			
			// Get data
			$Account_data = $this->challan_model->get_Account_Details_salesman($postData);
			
			echo json_encode($Account_data);
		}
		
		public function GetTaxableTransaction(){
			// POST data
			$postData = $this->input->post();
			// Get data
			$Salesdata = $this->challan_model->GetTaxableTransaction($postData);
			echo json_encode($Salesdata);
		}
		
		
		public function edit_challan($id = '')
		{
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('challan');
			}
			$redUrl = admin_url('challan/UpdateChallan/'.$id);
			redirect($redUrl);
			close_setup_menu();
			if ($id == '') {
				$data['title']                = "Create Challan";
				}else {
				$data['title']                = "Update Challan";
				$data['challan']    = $this->challan_model->get($id);
			}
			
			$this->load->model('payment_modes_model');
			$data['invoiceid']            = $id;
			
			$data['routes']    = $this->clients_model->getroute();
			$data['vehicle']    = $this->clients_model->getvehicle();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/challan/edit_challan', $data);
		}
		
		/* List all invoices datatables */
		public function list_challan($id = '')
		{
			
			if ($this->input->post()) {
				$challan_data = $this->input->post();
			}
			close_setup_menu();
			
			if ($id == '') {
				$data['title']                = "Create Challan";
				}else {
				$data['title']                = "Update Challan";
				$data['challan']    = $this->challan_model->get($id);
			}
			
			$this->load->model('payment_modes_model');
			
			$data['invoiceid']            = $id;
			
			$data['routes']    = $this->clients_model->getroute();
			$data['vehicle']    = $this->clients_model->getvehicle();
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/challan/manage', $data);
		}
		
		/* List all invoices datatables */
		public function challanAddEdit($id = '')
		{
			
			if ($this->input->post()) {
				$challan_data = $this->input->post();
				$challan_data["route"] = $challan_data["challan_route"];
				$challan_data["vehicle"] = $challan_data["challan_vehicle"];
				unset($challan_data["challan_route"]);
				unset($challan_data["challan_vehicle"]);
				
				if (!has_permission('challan', '', 'create')) {
                    access_denied('challan');
				}
                
                $challan = $this->challan_model->checkorder($challan_data["order_id"]);
                
                if(empty($challan)){
                    $challan_data["challan_driver"] = strtoupper($challan_data["challan_driver"]);
                    $challan_data["challan_loader"] = strtoupper($challan_data["challan_loader"]);
                    $challan_data["challan_sales_man"] = strtoupper($challan_data["challan_sales_man"]);
                    $challan_data["vahicle_number"] = strtoupper($challan_data["vahicle_number"]);
                    $id = $this->challan_model->AddNewChallan($challan_data);
					if ($id == false) {
						set_alert('warning', 'Stock Not Available...');
						$redUrl = admin_url('challan/challanAddEdit');
						redirect($redUrl);
                    }else{
						set_alert('success', _l('added_successfully', 'Challan'));
						$redUrl = admin_url('challan/challan_list/');
						redirect($redUrl);
					}
				}else{
					set_alert('warning', "Challan already created for this order");
					redirect(admin_url('challan/challan_list'));
				}
			}
			close_setup_menu();
			
			if ($id == '') {
				$data['title']                = "Create Challan";
				}else {
				$data['title']                = "Update Challan";
				$data['challan']    = $this->challan_model->get($id);
			}
			$this->load->model('payment_modes_model');
			$data['invoiceid']            = $id;
			$data['routes']    = $this->clients_model->getroute();
			$data['vehicle']    = $this->clients_model->getvehicle();
			$DriverType = "1000159";
			$data['DriverList']    = $this->clients_model->GetStaffListTypeWise($DriverType);
			$LoaderType = "1000161";
			$data['LoaderList']    = $this->clients_model->GetStaffListTypeWise($LoaderType);
			$SalesManType = "1000163";
			$data['SalesManList']    = $this->clients_model->GetStaffListTypeWise($SalesManType);
			$data['bodyclass']            = 'invoices-total-manual';
			
			$this->load->view('admin/challan/manageNew', $data);
		}
		
		public function UpdateChallan($id = '')
		{
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('challan');
			}
			if ($this->input->post()) {
				if (!has_permission_new('challan_list', '', 'edit')) {
					access_denied('challan');
				}
				$data = $this->input->post();
				// echo "<pre>";print_r($data);die;
				$id = $this->challan_model->UpdateExistingChallan($data);
				if($id == true){
					set_alert('success', 'Challan Updated Successfully');  
					}else{
					set_alert('warning', 'Something went wrong, please try again later.');
				}
				$redUrl = admin_url('challan/UpdateChallan/'.$data["number"]);
				redirect($redUrl);
			}// If Post data from Front end
			
			close_setup_menu();
			
			if ($id == '') {
				$data['title']                = "Create Challan";
				}else {
				$data['title']                = "Update Challan";
				$Order_item_free = array();
				$Order_item = array();
				$OrderIds = array();
				$AccountIds = array();
				// Existing order
				$data['challan']    = $this->challan_model->get($id);
				
				$challan    = $this->challan_model->getNew($id);
				
				// echo"<pre>";print_r($challan);die;
				foreach ($challan["item_list"] as $key => $code) {
					array_push($Order_item, $code["ItemID"]);
					array_push($OrderIds, $code["OrderID"]);
					array_push($AccountIds, $code["AccountID"]);
				}
				foreach ($challan["free_item_list"] as $key => $code) {
					array_push($Order_item_free, $code["ItemID"]);
				}
				$get_order_list = $this->challan_model->get_order_by_routeNew($data['challan']->RouteID);
				foreach ($get_order_list["item_list"] as $key1 => $code1) {
					array_push($Order_item, $code1["ItemID"]);
					array_push($OrderIds, $code1["OrderID"]);
					array_push($AccountIds, $code1["AccountID"]);
				}
				foreach ($get_order_list["free_item_list"] as $key1 => $code1) {
					array_push($Order_item_free, $code1["ItemID"]);
					
				}
				$Order_item =  array_unique($Order_item);
				$Order_item_free =  array_unique($Order_item_free);
				$OrderIds =  array_unique($OrderIds);
				$AccountIds =  array_unique($AccountIds);
				
				if(empty($Order_item)){
					
				}else{
					$get_item_rate = $this->challan_model->get_order_Item_rateNew($Order_item);
					$ItemSum = $this->challan_model->GetItemSum($OrderIds);
					$ItemStockDetails = $this->challan_model->GetStockDetails($Order_item);
				}
				$AccountBalances = $this->challan_model->GetAccountBalancec($AccountIds);
				$GetTcsPer = $this->challan_model->get_tcsperNew();
				$tcsPerValue = $GetTcsPer[0]['tcs'];
				// echo "<pre>";
				// print_r($challan);
				// die;
				$data['Curchallan']    = $challan;
				$data['ORDItem']    = $Order_item;
				$data['ORDItemFree']    = $Order_item_free;
				$data['ItemRate']    = $get_item_rate;
				$data['TCSValue']    = $tcsPerValue;
				$data['AccountBalances']    = $AccountBalances;
				$data['get_order_list']    = $get_order_list;
				$data['AllItemSum']    = $ItemSum;
				$data['ItemStockDetails']    = $ItemStockDetails;
				
			}
			
			
			$DriverType = "1000159";
			$data['DriverList']    = $this->clients_model->GetStaffListTypeWise($DriverType);
			$LoaderType = "1000161";
			$data['LoaderList']    = $this->clients_model->GetStaffListTypeWise($LoaderType);
			$SalesManType = "1000163";
			$data['SalesManList']    = $this->clients_model->GetStaffListTypeWise($SalesManType);
			
			$data['invoiceid']            = $id;
			$data['routes']    = $this->clients_model->getroute();
			$data['vehicle']    = $this->clients_model->getvehicle();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/challan/manageNew', $data);
		}
		
		//------------------- Vehicle Detail -------------------------------
		public function get_vehicle_detail()
		{
			$id=$this->input->post('id'); 
			$vehicle_data = $this->challan_model->get_vehicle_detail($id);
			echo json_encode($vehicle_data);
		}
		
		public function update_rate()
		{
			
			$RCHID = $this->input->post('RCHID'); 
			$update_rate = $this->challan_model->update_rate($RCHID);
			echo json_encode($update_rate);
		}
		
		// New Code start
		
		
		public function get_order_by_routeNew()
		{
			$selected_company = $this->session->userdata('root_company');
			$id = $this->input->post('id'); 
			$Order_item = array();
			$Order_item_free = array();
			$OrderIds = array();
			$AccountIds = array();
			$get_order_list = $this->challan_model->get_order_by_routeNew($id);
			
			foreach ($get_order_list["item_list"] as $key1 => $code1) {
				array_push($Order_item, $code1["ItemID"]);
				array_push($OrderIds, $code1["OrderID"]);
			}
			foreach ($get_order_list["free_item_list"] as $key1 => $code1) {
				array_push($Order_item_free, $code1["ItemID"]);
			}
			
			foreach ($get_order_list["order_ids"] as $key2 => $code2) {
				array_push($AccountIds, $code2["AccountID"]);
			}
			
			$Order_item =  array_unique($Order_item);
			$Order_item_free =  array_unique($Order_item_free);
			// echo json_encode($Order_item_free);
			// die;
			if(empty($Order_item)){
				
				}else{
				$get_item_rate = $this->challan_model->get_order_Item_rateNew($Order_item);
				if($selected_company !== "1"){
					$ItemStockDetails = $this->challan_model->GetStockDetails($Order_item);
				}
				$AllItemSum = $this->challan_model->GetItemSum($OrderIds);
				$AllItemSumFree = $this->challan_model->GetItemSumFree($OrderIds);
				$AccountBalances = $this->challan_model->GetAccountBalancec($AccountIds);
			}
			// echo json_encode($get_item_rate);
			// die;
			$GetTcsPer = $this->challan_model->get_tcsperNew();
			$tcsPerValue = $GetTcsPer[0]['tcs'];
			/*echo json_encode($get_order_list["item_list"]);
			die;*/
			if($get_order_list["order_ids"]){
				
				$html = '';
				$html .='<table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;height: 400px;"><thead style="background: #438EB9;color: #FFF;">';
				// $html .='<tr>';
				// $html .='<th colspan="7" class="col-id-no fixed-header"><center>Order Detais</center></th>';
				// if(count($Order_item)>0){
				// $html .='<th colspan="'.count($Order_item).'" class="col-id-no fixed-header"><center>Order Items</center></th>';
				// }
				// if(count($Order_item_free)>0){
				// $html .='<th colspan="'.count($Order_item_free).'" class="col-id-no fixed-header"><center>Free Items</center></th>';
				// }
				// $html .='<th colspan="11" class="col-id-no fixed-header"><center>Amount Details</center></th>';
				// $html .='</tr>';
				$html .='<tr>';
				$html .='<th class="col-id-no fixed-header">Tag</th>';
				$html .='<th class="col-id-ordid fixed-header">OrderNo</th>';
				$html .='<th class="col-id-custname fixed-header">AccountName</th>';
				$html .='<th class="col-id-custstate fixed-header">Sequence</th>';
				$html .='<th class="col-id-custstate fixed-header">StateID</th>';
				$html .='<th class="col-id-custstate fixed-header">Route Name</th>';
				$html .='<th class="col-id-ordtype fixed-header">Ordertype</th>';
				$html .='<th>SalesID</th>';
				$html .='<th>SalesDate</th>';
				foreach ($Order_item as $code) {
					$item =	$this->db->get_where('tblitems',array('item_code'=>$code))->row(); 
					$html .='<th width="5%" title="'.$item->description.'">'.$code.'</th>';
				}
				$html .='<th>Crates</th>';
				$html .='<th>Cases</th>';
				$html .='<th>OrderAmt</th>';
				$html .='<th>SaleAmt</th>';
				$html .='<th>DiscAmt</th>';
				$html .='<th>CGSTAMT</th>';
				$html .='<th>SGSTAMT</th>';
				$html .='<th>IGSTAMT</th>';
				$html .='<th>TCSPer</th>';
				$html .='<th>TCSAmt</th>';
				$html .='<th>BillAmt</th>';
				$html .='</tr>';
				$html .='</thead>';
				$challan_cases = 0;
				$challan_crate = 0;
				$challan_subtotal = 0;
				$challan_total = 0;
				$DiscAmtSum = 0;
				$CGSTAMTSum = 0;
				$SGSTAMTSum = 0;
				$IGSTAMTSum = 0;
				$html .='<tbody>';
				
				foreach ($get_order_list["order_ids"] as $key1 => $ids) {
					$css = '';
					if($ids['credit_exceed'] == 'Y' && $ids['credit_apply'] == 'Y'){
						$css = 'color:red';
					}
					
					if($ids['credit_exceed'] == 'Y' && $ids['credit_apply'] == 'N'){
						$css = 'color:green';
					}
					$html .='<tr>';
					//$order_data = $this->challan_model->getorderdetail_by_orderId($ids["OrderID"]);
					$html .='<td scope="row" class="col-id-no"><input type="checkbox" name="order_id[]" class="chk" value="'.$ids["OrderID"].'"><input type="hidden" name="OrderID" value="'.$ids["OrderID"].'"><input type="hidden" name="credit_apply" value="'.$ids["credit_apply"].'"><input type="hidden" name="PrevOrderAmt" value="'.$ids["OrderAmt"].'"></td>';
					$BAL = 0;
					foreach ($AccountBalances as $BalKey => $BalVal) {
						if($ids["AccountID"] === $BalVal["AccountID"]){
							$BAL = (-1 * floatval($BalVal["Balance"])) + $ids["MaxCrdAmt"];
						}
					}
					$html .='<td scope="row" class="col-id-ordid"><input type="hidden" name="Balance" value="'.$BAL.'"><input type="hidden" name="MaxCrdAmt" value="'.$ids["MaxCrdAmt"].'"><span style="'.$css.'">'.$ids["OrderID"].'</span></td>';
					
					$html .='<td scope="row" class="col-id-custname">'.$ids["company"].'</td>';
					$html .='<td scope="row" class="col-id-custstate"><input class= "SequenceInput" style="width: 45px;" type="text" name="Sequence_'.$ids["OrderID"].'" value=""></td>';
					$html .='<td scope="row" class="col-id-custstate">'.$ids["state"].'</td>';
					$html .='<td scope="row" class="col-id-custstate">'.$ids["RouteName"].'</td>';
					
					$html .='<td scope="row" class="col-id-ordtype">'.$ids["OrderType"].'</td>';
					if($ids["istcs"] == "1"){
						$tcs = $tcsPerValue;
						}else{
						$tcs = 0.00;
					}
					$html .='<td><input type="hidden" name="istcs" value="'.$tcs.'"></td>';
					
					$html .='<td></td>';
					$mm = 0;
					$OrderSaleAmt = 0;
					$OrderBillAmt = 0;
					$DiscAmt = 0; 
					$OSGST = 0; 
					$OCGST = 0; 
					$OIGST = 0; 
					foreach ($Order_item as $ItemIDc) {
						$isItem = '';
						foreach ($get_order_list["item_list"] as $key => $code) {
							if($code["ItemID"] == $ItemIDc){
								$matched = '';
								
								if($ids["OrderID"] == $code["OrderID"]){
									$isItem = 1;
									foreach ($get_item_rate as $key2 => $code2) {
										if($code2["item_id"]==$code["ItemID"] && $ids["state"] == $code2["state_id"] && $ids["DistributorType"]==$code2["distributor_id"]){
											if($code["BasicRate"] == $code2["assigned_rate"]){
												break;
											}else{
												$matched= 'color:red;';
												$mm++;
											}
										}
									}
									/*if($mm == 0){
										$his_rate = $this->challan_model->get_order_Item_rate_history($code["ItemID"]);
										if($his_rate->ItemID ==$code["ItemID"] &&  $ids["state"] == $his_rate->StateID && $ids["DistributorType"]==$his_rate->DistributorType && $code["BasicRate"] !== $his_rate->BasicRate){
										$matched= 'style="color:red"';
										$mm++;
										}
									}*/
									
									$pack_qty = $code["CaseQty"];
									$rate = $code["BasicRate"];
									$DiscPer = $code["DiscPerc"];
									$gst = $code["cgst"] + $code["sgst"] + $code["igst"];
									if($ids["state"] == "UP"){
										$cscr = $code["local_supply_in"];
										}else{
										$cscr = $code["outst_supply_in"];
									}
									
									$qty = (int) $code["orderqty"] ;// / $code["CaseQty"] Add If Needed
									$OrderSaleAmt = $OrderSaleAmt + $code["OrderAmt"];
									$OrderBillAmt += $code["NetOrderAmt"];
									$DiscAmt += $code["DiscAmt"];
									$OSGST += $code["sgstamt"];
									$OCGST += $code["cgstamt"];
									$OIGST += $code["igstamt"];
									
									//$html .='<td width="5%" align="right" '.$matched.'>'.$qty1.'</td>';
								}
							}
						}
						$balCase = 0;
						if($selected_company !== "1"){
							$PQty = 0;
							$PRQty = 0;
							$IQty = 0;
							$PRDQty = 0;
							$SQty = 0;
							$SRQty = 0;
							$ADJQTY = 0;
							$GIQTY = 0;
							$GOQTY = 0;
							foreach ($ItemStockDetails as $key => $value) {
								if($value['ItemID'] == $ItemIDc){
									$oQty = $value['OQty'];
									$caseQty = $value['CaseQty'];
									if($value['TType'] == 'P'){
										$PQty = $value['BilledQty'];
										}elseif($value['TType'] == 'N'){
										$PRQty = $value['BilledQty'];
										}elseif($value['TType'] == 'A'){
										$IQty = $value['BilledQty'];
										}elseif($value['TType'] == 'B'){
										$PRDQty = $value['BilledQty'];
										}elseif($value['TType'] == 'O' && $value['TType2'] == 'Order'){
										$SQty = $value['BilledQty'];
										}elseif($value['TType'] == 'R' && $value['TType2'] == 'Fresh'){
										$SRQty = $value['BilledQty'];
										}elseif($value['TType'] == 'X' && $value['TType2'] == 'Stock Adjustment'){
										$ADJQTY += $value['BilledQty'];
										}elseif($value['TType'] == 'X' && $value['TType2'] == 'Promotional Activity'){
										$ADJQTY += $value['BilledQty'];
										}elseif($value['TType'] == 'X' && $value['TType2'] == 'Free Distribution'){
										$ADJQTY += $value['BilledQty'];
										}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
										$GIQTY += $stock['BilledQty'];
										}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
										$GOQTY += $stock['BilledQty'];
									}
								}
							}
							$balance = (float) $oQty + (float) $PQty - (float) $PRQty - (float) $IQty +  (float) $PRDQty - (float) $SQty + (float) $SRQty - (float) $ADJQTY  - (float) $GOQTY +  - (float) $GIQTY;
							$balCase = $balance ;// / $caseQty Add If Needed
						}
						if($isItem == ""){
							$html .='<td width="5%" align="right" ></td>';
							}else{
							$html .='<td width="5%"><input type="hidden" value="'.$qty.'_'.$pack_qty.'_'.$rate.'_'.$gst.'_'.$cscr.'_'.$ids["state"].'_'.$balCase.'_'.$DiscPer.'_'.$ids["DistributorType"].'_'.$ids["Transdate"].'" id="qtyhidden"/><input type="hidden" id="orgqty_'.$ids["OrderID"].'_'.$ItemIDc.'" name="orgqty_'.$ids["OrderID"].'_'.$ItemIDc.'" value="'.$qty.'"/><input class= "QtyInput" style="width: 45px;'.$matched.'" type="text" onchange="total(this,'.$qty.')" name="qty_'.$ids["OrderID"].'_'.$ItemIDc.'" value="'.$qty.'"></td>';
						}
					}
					
					
					$html .='<td style="text-align: right;"><input class= "CratesInput" style="width: 45px;" type="text" onchange="ChallanValues()" name="crates_'.$ids["OrderID"].'" value="'.$ids["Crates"].'">';
					if($mm > 0){
						$html .='<input type="hidden" name="rate_change" id="rate_change" value="Y">';
					}
					$html .='</td>';
					$challan_crate = $challan_crate + $ids["Crates"];
					$html .='<td style="text-align: right;"><input class= "CasesInput" style="width: 45px;" type="text" onchange="ChallanValues()" name="cases_'.$ids["OrderID"].'" value="'.$ids["Cases"].'"></td>';
					$challan_cases = $challan_cases + $ids["Cases"];
					// bill Amt
					$html .='<td style="text-align: right;">'.$OrderBillAmt.' </td>';
					$challan_total = $challan_total + $OrderBillAmt;
					//sale Amt
					$html .='<td style="text-align: right;">'.$OrderSaleAmt.'</td>';
					$challan_subtotal = $challan_subtotal + $OrderSaleAmt;
					// Disc Amt
					$html .='<td style="text-align: right;">'.$DiscAmt.'</td>';
					$DiscAmtSum = $DiscAmtSum + $DiscAmt;
					// CGST Amt
					$html .='<td style="text-align: right;">'.$OCGST.'</td>';
					$CGSTAMTSum = $CGSTAMTSum + $OCGST;
					// SGST Amt
					$html .='<td style="text-align: right;">'.$OSGST.'</td>';
					$SGSTAMTSum = $SGSTAMTSum + $OSGST;
					// IGST Amt
					$html .='<td style="text-align: right;">'.$OIGST.'</td>';
					$IGSTAMTSum = $IGSTAMTSum + $OIGST;
					// TCS Amt
					$html .='<td style="text-align: right;"><input type="hidden" name="tcsper" value="'.$tcs.'">'.$tcs.'</td>';
					if($tcs !=="0.00"){
						$tcsAmt = ($OrderBillAmt / 100) * $tcs;
						}else{
						$tcsAmt = 0.00;
					}
					
					$html .='<td style="text-align: right;">'.round($tcsAmt,2).'</td>';
					// Bill Amt Include TCSAMT
					$finalBillAmt = $OrderBillAmt + $tcsAmt;
					$html .='<td style="text-align: right;">'.round($finalBillAmt,2).'<input type="hidden" name="FBilAmt" id="FBilAmt" value="'.$finalBillAmt.'"></td>';
					$html .='</tr>';
				}
				
				$html .='<tfoot><tr>';
				
				$html .='<td style="text-align:center; scope="row" class="col-id-no"">Total</td>
				<td scope="row" class="col-id-ordid"></td>
				<td scope="row" class="col-id-custname"></td>
				<td scope="row" class="col-id-custstate"></td>
				<td scope="row" class="col-id-custstate"></td>
				<td scope="row" class="col-id-custRoute"></td>
				<td scope="row" class="col-id-ordtype"></td>
				<td></td><td></td>';
				
				foreach ($Order_item as $ItemIDc) {
					foreach ($AllItemSum as $keys => $values) {
						if($ItemIDc == $values['ItemID']){
							$ItemSum = $values['OrderQty'] ;// / $values['CaseQty'] Add If Needed
							$html .='<td style="text-align: right;">'.(int) $ItemSum.'</td>';
						}
					}                  
				}
				
				$html .='<td style="text-align: right;">'.$challan_crate.'</td>';
				$html .='<td style="text-align: right;">'.$challan_cases.'</td>';
				$html .='<td style="text-align: right;">'.$challan_total.'</td>';
				$html .='<td style="text-align: right;">'.$challan_subtotal.'</td>';
				$html .='<td style="text-align: right;">'.$DiscAmtSum.'</td>';
				$html .='<td style="text-align: right;">'.$CGSTAMTSum.'</td>';
				$html .='<td style="text-align: right;">'.$SGSTAMTSum.'</td>';
				$html .='<td style="text-align: right;">'.$IGSTAMTSum.'</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='<td style="text-align: right;">'.$challan_total.'</td>';
				$html .='</tr></tfoot>';
				
				$html .='</tbody>';
				$html .='</table>';
				}else{
				$html = '<p style="color:red;">No data found...</p>';
			}
			
			echo json_encode($html);
		}
		public function get_order_by_routeNewAll()
		{
			$selected_company = $this->session->userdata('root_company');
			$Order_item = array();
			$Order_item_free = array();
			$OrderIds = array();
			$AccountIds = array();
			$get_order_list = $this->challan_model->get_order_by_routeNewAll();
			
			foreach ($get_order_list["item_list"] as $key1 => $code1) {
				array_push($Order_item, $code1["ItemID"]);
				array_push($OrderIds, $code1["OrderID"]);
			}
			foreach ($get_order_list["free_item_list"] as $key1 => $code1) {
				array_push($Order_item_free, $code1["ItemID"]);
			}
			
			foreach ($get_order_list["order_ids"] as $key2 => $code2) {
				array_push($AccountIds, $code2["AccountID"]);
			}
			
			$Order_item =  array_unique($Order_item);
			$Order_item_free =  array_unique($Order_item_free);
			// echo json_encode($get_order_list["order_ids"]);
			// die;
			if(empty($Order_item)){
				
				}else{
				$get_item_rate = $this->challan_model->get_order_Item_rateNew($Order_item);
				if($selected_company !== "1"){
					$ItemStockDetails = $this->challan_model->GetStockDetails($Order_item);
				}
				$AllItemSum = $this->challan_model->GetItemSum($OrderIds);
				$AllItemSumFree = $this->challan_model->GetItemSumFree($OrderIds);
				$AccountBalances = $this->challan_model->GetAccountBalancec($AccountIds);
			}
			// echo json_encode($AccountBalances);
			// die;
			$GetTcsPer = $this->challan_model->get_tcsperNew();
			$tcsPerValue = $GetTcsPer[0]['tcs'];
			/*echo json_encode($get_order_list["item_list"]);
			die;*/
			if($get_order_list["order_ids"]){
				
				$html = '';
				$html .='<table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;height: 400px;"><thead style="background: #438EB9;color: #FFF;">';
				$html .='<tr>';
				$html .='<th class="col-id-no fixed-header">Tag</th>';
				$html .='<th class="col-id-ordid fixed-header">OrderNo</th>';
				$html .='<th class="col-id-custname fixed-header">AccountName</th>';
				$html .='<th class="col-id-custstate fixed-header" style="width:50px;">Sequence</th>';
				$html .='<th class="col-id-custstate fixed-header">StateID</th>';
				$html .='<th class="col-id-custstate fixed-header">Route Name</th>';
				$html .='<th class="col-id-ordtype fixed-header">Ordertype</th>';
				$html .='<th>SalesID</th>';
				$html .='<th>SalesDate</th>';
				foreach ($Order_item as $code) {
					$item =	$this->db->get_where('tblitems',array('item_code'=>$code))->row(); 
					$html .='<th width="5%" title="'.$item->description.'">'.$code.'</th>';
				}
				$html .='<th>Crates</th>';
				$html .='<th>Cases</th>';
				$html .='<th>OrderAmt</th>';
				$html .='<th>SaleAmt</th>';
				$html .='<th>DiscAmt</th>';
				$html .='<th>CGSTAMT</th>';
				$html .='<th>SGSTAMT</th>';
				$html .='<th>IGSTAMT</th>';
				$html .='<th>TCSPer</th>';
				$html .='<th>TCSAmt</th>';
				$html .='<th>BillAmt</th>';
				$html .='</tr>';
				$html .='</thead>';
				$challan_cases = 0;
				$challan_crate = 0;
				$challan_subtotal = 0;
				$challan_total = 0;
				$DiscAmtSum = 0;
				$CGSTAMTSum = 0;
				$SGSTAMTSum = 0;
				$IGSTAMTSum = 0;
				$html .='<tbody>';
				
				foreach ($get_order_list["order_ids"] as $key1 => $ids) {
					$css = '';
					if($ids['credit_exceed'] == 'Y' && $ids['credit_apply'] == 'Y'){
						$css = 'color:red';
					}
					
					if($ids['credit_exceed'] == 'Y' && $ids['credit_apply'] == 'N'){
						$css = 'color:green';
					}
					$html .='<tr>';
					//$order_data = $this->challan_model->getorderdetail_by_orderId($ids["OrderID"]);
					$html .='<td scope="row" class="col-id-no"><input type="checkbox" name="route_id[]" onclick="GetRouteOrder('.$ids["RouteID"].')" class="getroute" value="'.$ids["RouteID"].'"><input type="hidden" name="credit_apply" value="'.$ids["credit_apply"].'"><input type="hidden" name="PrevOrderAmt" value="'.$ids["OrderAmt"].'"></td>';
					$BAL = 0;
					foreach ($AccountBalances as $BalKey => $BalVal) {
						if($ids["AccountID"] === $BalVal["AccountID"]){
							$BAL = (-1 * floatval($BalVal["Balance"])) + $ids["MaxCrdAmt"];
						}
					}
					$html .='<td scope="row" class="col-id-ordid"><input type="hidden" name="Balance" value="'.$BAL.'"><input type="hidden" name="MaxCrdAmt" value="'.$ids["MaxCrdAmt"].'"><span style="'.$css.'">'.$ids["OrderID"].'</span></td>';
					
					$html .='<td scope="row" class="col-id-custname">'.$ids["company"].'</td>';
					$html .='<td scope="row" class="col-id-custstate"><input class= "SequenceInput" style="width: 45px;" type="text" name="Sequence_'.$ids["OrderID"].'" value=""></td>';
					$html .='<td scope="row" class="col-id-custstate">'.$ids["state"].'</td>';
					$html .='<td scope="row" class="col-id-custstate">'.$ids["RouteName"].'</td>';
					
					$html .='<td scope="row" class="col-id-ordtype">'.$ids["OrderType"].'</td>';
					if($ids["istcs"] == "1"){
						$tcs = $tcsPerValue;
						}else{
						$tcs = 0.00;
					}
					$html .='<td><input type="hidden" name="istcs" value="'.$tcs.'"></td>';
					
					$html .='<td></td>';
					$mm = 0;
					$OrderSaleAmt = 0;
					$OrderBillAmt = 0;
					$DiscAmt = 0; 
					$OSGST = 0; 
					$OCGST = 0; 
					$OIGST = 0; 
					foreach ($Order_item as $ItemIDc) {
						$isItem = '';
						foreach ($get_order_list["item_list"] as $key => $code) {
							if($code["ItemID"] == $ItemIDc){
								$matched = '';
								
								if($ids["OrderID"] == $code["OrderID"]){
									$isItem = 1;
									foreach ($get_item_rate as $key2 => $code2) {
										if($code2["item_id"]==$code["ItemID"] && $ids["state"] == $code2["state_id"] && $ids["DistributorType"]==$code2["distributor_id"] && $code["BasicRate"] !== $code2["assigned_rate"]){
											$matched= 'color:red;';
											$mm++;
											
										}
									}
									/*if($mm == 0){
										$his_rate = $this->challan_model->get_order_Item_rate_history($code["ItemID"]);
										if($his_rate->ItemID ==$code["ItemID"] &&  $ids["state"] == $his_rate->StateID && $ids["DistributorType"]==$his_rate->DistributorType && $code["BasicRate"] !== $his_rate->BasicRate){
										$matched= 'style="color:red"';
										$mm++;
										}
									}*/
									
									$pack_qty = $code["CaseQty"];
									$rate = $code["BasicRate"];
									$DiscPer = $code["DiscPerc"];
									$gst = $code["cgst"] + $code["sgst"] + $code["igst"];
									if($ids["state"] == "UP"){
										$cscr = $code["local_supply_in"];
										}else{
										$cscr = $code["outst_supply_in"];
									}
									
									$qty = (int) $code["orderqty"] ;// / $code["CaseQty"] Add If Needed
									$OrderSaleAmt = $OrderSaleAmt + $code["OrderAmt"];
									$OrderBillAmt += $code["NetOrderAmt"];
									$DiscAmt += $code["DiscAmt"];
									$OSGST += $code["sgstamt"];
									$OCGST += $code["cgstamt"];
									$OIGST += $code["igstamt"];
									
									//$html .='<td width="5%" align="right" '.$matched.'>'.$qty1.'</td>';
								}
							}
						}
						$balCase = 0;
						if($selected_company !== "1"){
							$PQty = 0;
							$PRQty = 0;
							$IQty = 0;
							$PRDQty = 0;
							$SQty = 0;
							$SRQty = 0;
							$ADJQTY = 0;
							$GIQTY = 0;
							$GOQTY = 0;
							foreach ($ItemStockDetails as $key => $value) {
								if($value['ItemID'] == $ItemIDc){
									$oQty = $value['OQty'];
									$caseQty = $value['CaseQty'];
									if($value['TType'] == 'P'){
										$PQty = $value['BilledQty'];
										}elseif($value['TType'] == 'N'){
										$PRQty = $value['BilledQty'];
										}elseif($value['TType'] == 'A'){
										$IQty = $value['BilledQty'];
										}elseif($value['TType'] == 'B'){
										$PRDQty = $value['BilledQty'];
										}elseif($value['TType'] == 'O' && $value['TType2'] == 'Order'){
										$SQty = $value['BilledQty'];
										}elseif($value['TType'] == 'R' && $value['TType2'] == 'Fresh'){
										$SRQty = $value['BilledQty'];
										}elseif($value['TType'] == 'X' && $value['TType2'] == 'Stock Adjustment'){
										$ADJQTY += $value['BilledQty'];
										}elseif($value['TType'] == 'X' && $value['TType2'] == 'Promotional Activity'){
										$ADJQTY += $value['BilledQty'];
										}elseif($value['TType'] == 'X' && $value['TType2'] == 'Free Distribution'){
										$ADJQTY += $value['BilledQty'];
										}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
										$GIQTY += $stock['BilledQty'];
										}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
										$GOQTY += $stock['BilledQty'];
									}
								}
							}
							$balance = (float) $oQty + (float) $PQty - (float) $PRQty - (float) $IQty +  (float) $PRDQty - (float) $SQty + (float) $SRQty - (float) $ADJQTY  - (float) $GOQTY +  - (float) $GIQTY;
							$balCase = $balance ;// / $caseQty Add If Needed
						}
						if($isItem == ""){
							$html .='<td width="5%" align="right" ></td>';
							}else{
							$html .='<td width="5%"><input type="hidden" value="'.$qty.'_'.$pack_qty.'_'.$rate.'_'.$gst.'_'.$cscr.'_'.$ids["state"].'_'.$balCase.'_'.$DiscPer.'_'.$ids["DistributorType"].'_'.$ids["Transdate"].'" id="qtyhidden"/><input type="hidden" id="orgqty_'.$ids["OrderID"].'_'.$ItemIDc.'" name="orgqty_'.$ids["OrderID"].'_'.$ItemIDc.'" value="'.$qty.'"/><input class= "QtyInput" style="width: 45px;'.$matched.'" type="text" onchange="total(this,'.$qty.')" name="qty_'.$ids["OrderID"].'_'.$ItemIDc.'" value="'.$qty.'"></td>';
						}
					}
					
					
					$html .='<td style="text-align: right;"><input class= "CratesInput" style="width: 45px;" type="text" onchange="ChallanValues()" name="crates_'.$ids["OrderID"].'" value="'.$ids["Crates"].'">';
					if($mm > 0){
						$html .='<input type="hidden" name="rate_change" id="rate_change" value="Y">';
					}
					$html .='</td>';
					$challan_crate = $challan_crate + $ids["Crates"];
					$html .='<td style="text-align: right;"><input class= "CasesInput" style="width: 45px;" type="text" onchange="ChallanValues()" name="cases_'.$ids["OrderID"].'" value="'.$ids["Cases"].'"></td>';
					$challan_cases = $challan_cases + $ids["Cases"];
					// bill Amt
					$html .='<td style="text-align: right;">'.$OrderBillAmt.' </td>';
					$challan_total = $challan_total + $OrderBillAmt;
					//sale Amt
					$html .='<td style="text-align: right;">'.$OrderSaleAmt.'</td>';
					$challan_subtotal = $challan_subtotal + $OrderSaleAmt;
					// Disc Amt
					$html .='<td style="text-align: right;">'.$DiscAmt.'</td>';
					$DiscAmtSum = $DiscAmtSum + $DiscAmt;
					// CGST Amt
					$html .='<td style="text-align: right;">'.$OCGST.'</td>';
					$CGSTAMTSum = $CGSTAMTSum + $OCGST;
					// SGST Amt
					$html .='<td style="text-align: right;">'.$OSGST.'</td>';
					$SGSTAMTSum = $SGSTAMTSum + $OSGST;
					// IGST Amt
					$html .='<td style="text-align: right;">'.$OIGST.'</td>';
					$IGSTAMTSum = $IGSTAMTSum + $OIGST;
					// TCS Amt
					$html .='<td style="text-align: right;"><input type="hidden" name="tcsper" value="'.$tcs.'">'.$tcs.'</td>';
					if($tcs !=="0.00"){
						$tcsAmt = ($OrderBillAmt / 100) * $tcs;
						}else{
						$tcsAmt = 0.00;
					}
					
					$html .='<td style="text-align: right;">'.round($tcsAmt,2).'</td>';
					// Bill Amt Include TCSAMT
					$finalBillAmt = $OrderBillAmt + $tcsAmt;
					$html .='<td style="text-align: right;">'.round($finalBillAmt,2).'<input type="hidden" name="FBilAmt" id="FBilAmt" value="'.$finalBillAmt.'"></td>';
					$html .='</tr>';
				}
				
				$html .='<tfoot><tr>';
				
				$html .='<td style="text-align:center; scope="row" class="col-id-no"">Total</td>
				<td scope="row" class="col-id-ordid"></td>
				<td scope="row" class="col-id-custname"></td>
				<td scope="row" class="col-id-custname"></td>
				<td scope="row" class="col-id-custstate"></td>
				<td scope="row" class="col-id-custRoute"></td>
				<td scope="row" class="col-id-ordtype"></td>
				<td></td><td></td>';
				
				foreach ($Order_item as $ItemIDc) {
					foreach ($AllItemSum as $keys => $values) {
						if($ItemIDc == $values['ItemID']){
							$ItemSum = $values['OrderQty'] ;// / $values['CaseQty'] Add If Needed
							$html .='<td style="text-align: right;">'.(int) $ItemSum.'</td>';
						}
					}                  
				}
				
				$html .='<td style="text-align: right;">'.$challan_crate.'</td>';
				$html .='<td style="text-align: right;">'.$challan_cases.'</td>';
				$html .='<td style="text-align: right;">'.$challan_total.'</td>';
				$html .='<td style="text-align: right;">'.$challan_subtotal.'</td>';
				$html .='<td style="text-align: right;">'.$DiscAmtSum.'</td>';
				$html .='<td style="text-align: right;">'.$CGSTAMTSum.'</td>';
				$html .='<td style="text-align: right;">'.$SGSTAMTSum.'</td>';
				$html .='<td style="text-align: right;">'.$IGSTAMTSum.'</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='<td style="text-align: right;">'.$challan_total.'</td>';
				$html .='</tr></tfoot>';
				
				$html .='</tbody>';
				$html .='</table>';
				}else{
				$html = '<p style="color:red;">No data found...</p>';
			}
			
			echo json_encode($html);
		}
		// New Code End 
		public function get_order_by_route2()
		{
			$selected_company = $this->session->userdata('root_company');
			$id = $this->input->post('id'); 
			$Order_item = array();
			
			$get_order_list = $this->challan_model->get_order_by_route($id);
			
			foreach ($get_order_list["item_list"] as $key1 => $code1) {
				array_push($Order_item, $code1["ItemID"]);
			}
			if(empty($Order_item)){
				
				}else{
				$get_item_rate = $this->challan_model->get_order_Item_rate($Order_item);
			}
			//echo json_encode($get_item_rate);
			if($get_order_list["order_ids"]){
				
				$html = '';
				$html .='<table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;"><thead style="background: #438EB9;color: #FFF;">';
				$html .='<th>Tag</th>';
				$html .='<th>OrderNo</th>';
				$html .='<th>AccountName</th>';
				$html .='<th>StateID</th>';
				$html .='<th>Ordertype</th>';
				$html .='<th>SalesID</th>';
				$html .='<th>SalesDate</th>';
				foreach ($get_order_list["item_list"] as $key => $code) {
					$html .='<th width="5%">'.$code["ItemID"].'</th>';
				}
				$html .='<th>Crates</th>';
				$html .='<th>Cases</th>';
				$html .='<th>OrderAmt</th>';
				$html .='<th>SaleAmt</th>';
				$html .='<th>TCSPer</th>';
				$html .='<th>TCSAmt</th>';
				$html .='</thead>';
				$challan_cases = 0;
				$challan_crate = 0;
				$challan_subtotal = 0;
				$challan_total = 0;
				$html .='<tbody>';
				
				foreach ($get_order_list["order_ids"] as $key1 => $ids) {
					$html .='<tr>';
					//$order_data = $this->challan_model->getorderdetail_by_orderId($ids["OrderID"]);
					$html .='<td><input type="checkbox" name="order_id[]" class="chk" value="'.$ids["OrderID"].'"><input type="hidden" name="OrderID" value="'.$ids["OrderID"].'"></td>';
					$html .='<td>'.$ids["OrderID"].'</td>';
					//$account_name = get_account_name($order_data->AccountID,$selected_company);
					$html .='<td>'.$ids["company"].'</td>';
					$html .='<td>'.$ids["state"].'</td>';
					
					$html .='<td>'.$ids["OrderType"].'</td>';
					$html .='<td></td>';
					
					$html .='<td></td>';
					$mm = 0;
					
					foreach ($get_order_list["item_list"] as $key => $code) {
						$matched = '';
						if($ids["OrderID"] == $code["OrderID"]){
							foreach ($get_item_rate as $key2 => $code2) {
								if($code2["item_id"]==$code["ItemID"] && $ids["state"] == $code2["state_id"] && $ids["DistributorType"]==$code2["distributor_id"] && $code["BasicRate"] !== $code2["assigned_rate"]){
									$matched= 'style="color:red"';
									$mm++;
									
								}
							}
							if($mm == 0){
								$his_rate = $this->challan_model->get_order_Item_rate_history($code["ItemID"]);
								if($his_rate->ItemID ==$code["ItemID"] &&  $ids["state"] == $his_rate->StateID && $ids["DistributorType"]==$his_rate->DistributorType && $code["BasicRate"] !== $his_rate->BasicRate){
									$matched= 'style="color:red"';
									$mm++;
								}
							}
							$qty1 = $code["orderqty"] / $code["CaseQty"];
							$html .='<td width="5%" align="right" '.$matched.'>'.$qty1.'</td>';
							}else{
							$html .='<td></td>';
						}
						//$item_data1 = $this->challan_model->get_order_singleitem($ids["OrderID"],$code["ItemID"]);
						/*if($item_data1){
							if(is_null($item_data1->eOrderQty)){
							$qty1 = $item_data1->OrderQty / $item_data1->CaseQty;
							}else{
							$qty1 = $item_data1->eOrderQty / $item_data1->CaseQty;
							}
							
							$html .='<td width="5%">'.$qty1.'</td>';
							}else{
							
							$html .='<td></td>';
						}*/
						
						
					}
					
					$html .='<td style="text-align: right;">'.$ids["Crates"];
					if($mm > 0){
						$html .='<input type="hidden" name="rate_change" id="rate_change" value="Y">';
					}
					$html .='</td>';
					$challan_crate = $challan_crate + $ids["Crates"];
					$html .='<td style="text-align: right;">'.$ids["Cases"].'</td>';
					$challan_cases = $challan_cases + $ids["Cases"];
					$html .='<td style="text-align: right;">'.$ids["OrderAmt"].'</td>';
					$challan_subtotal = $challan_subtotal + $ids["OrderAmt"];
					$html .='<td style="text-align: right;"></td>';
					$challan_total = $challan_total + $ids["OrderAmt"];
					$html .='<td style="text-align: right;">0</td>';
					$html .='<td style="text-align: right;">0</td>';
					$html .='</tr>';
				}
				
				$html .='<tfoot><tr>';
				
				$html .='<td style="text-align:center;">Total</td><td></td><td></td><td></td><td></td><td></td><td></td>';
				foreach ($get_order_list["item_list"] as $key => $code1) {
					
					$item_count = $this->challan_model->get_itemcout_all_order($id,$code1["ItemID"]);
					
					$item_count_new = (int) $item_count->OrderQty;
					$html .='<td style="text-align: right;">'.$item_count_new.'</td>';
				}
				$html .='<td style="text-align: right;">'.$challan_crate.'</td>';
				$html .='<td style="text-align: right;">'.$challan_cases.'</td>';
				$html .='<td style="text-align: right;">'.$challan_subtotal.'</td>';
				$html .='<td style="text-align: right;">'.$challan_total.'</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='</tr></tfoot>';
				
				$html .='</tbody>';
				$html .='</table>';
			}
			
			echo json_encode($html);
		}
		
		//------------------- List of Order By route-------------------------------
		public function get_order_by_route()
		{
			$selected_company = $this->session->userdata('root_company');
			$id = $this->input->post('id'); 
			
			$get_acc_by_route = $this->challan_model->get_acc_by_route($id);
			
			$order_ids = array();
			$account_ids = array();
			
			
			foreach ($get_acc_by_route as $key => $value) {
				
				array_push($account_ids,$value['AccountID']);
			}
			$order_ids_details = $this->challan_model->getorderlist_by_accId($account_ids);
			
			foreach ($order_ids_details as $key1 => $value1) {
				
				array_push($order_ids,$value1['OrderID']);
			}
			
			
			if($order_ids){
				$item_code_list_new = array();
				
				
				
				$item_code_list = $this->challan_model->get_item_code_list_by_order_ids($order_ids);
				
				foreach ($item_code_list as $key2 => $value2) {
					
					array_push($item_code_list_new,$value2['ItemID']);
				}
				
				$item_code_list_new_unique = array_unique($item_code_list_new);
				
				$html = '';
				$html .='<table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;"><thead style="background: #438EB9;color: #FFF;">';
				$html .='<th>Tag</th>';
				$html .='<th>OrderNo</th>';
				$html .='<th>AccountName</th>';
				$html .='<th>StateID</th>';
				$html .='<th>Ordertype</th>';
				$html .='<th>SalesID</th>';
				$html .='<th>SalesDate</th>';
				foreach ($item_code_list_new_unique as $code) {
					$html .='<th width="5%">'.$code.'</th>';
				}
				$html .='<th>Crates</th>';
				$html .='<th>Cases</th>';
				$html .='<th>OrderAmt</th>';
				$html .='<th>SaleAmt</th>';
				$html .='<th>TCSPer</th>';
				$html .='<th>TCSAmt</th>';
				$html .='</thead>';
				$challan_cases = 0;
				$challan_crate = 0;
				$challan_subtotal = 0;
				$challan_total = 0;
				$html .='<tbody>';
				foreach ($order_ids as $ids) {
					$html .='<tr>';
					$order_data = $this->challan_model->getorderdetail_by_orderId($ids);
					$html .='<td><input type="checkbox" name="order_id[]" class="chk" value="'.$ids.'"></td>';
					$html .='<td>'.$ids.'</td>';
					$account_name = get_account_name($order_data->AccountID,$selected_company);
					$html .='<td>'.$account_name->company.'</td>';
					$html .='<td>'.$order_data->client->state.'</td>';
					$html .='<td>'.$order_data->OrderType.'</td>';
					$html .='<td></td>';
					
					$html .='<td></td>';
					
					foreach ($item_code_list_new_unique as $code) {
						$item_data1 = $this->challan_model->get_order_singleitem($ids,$code);
						if($item_data1){
							if(is_null($item_data1->eOrderQty)){
								$qty1 = $item_data1->OrderQty / $item_data1->CaseQty;
								}else{
								$qty1 = $item_data1->eOrderQty / $item_data1->CaseQty;
							}
							
							$html .='<td width="5%"><input style="width: 50px;" type="text" name="qty" value="'.$qty1.'"></td>';
							}else{
							
							$html .='<td></td>';
						}
						
					}
					
					$html .='<td style="text-align: right;">'.$order_data->Crates.'</td>';
					$challan_crate = $challan_crate + $order_data->Crates;
					$html .='<td style="text-align: right;">'.$order_data->Cases.'</td>';
					$challan_cases = $challan_cases + $order_data->Cases;
					$html .='<td style="text-align: right;">'.$order_data->OrderAmt.'</td>';
					$challan_subtotal = $challan_subtotal + $order_data->OrderAmt;
					$html .='<td style="text-align: right;"></td>';
					$challan_total = $challan_total + $order_data->OrderAmt;
					$html .='<td style="text-align: right;">0</td>';
					$html .='<td style="text-align: right;">0</td>';
					$html .='</tr>';
				}
				
				$html .='<tfoot><tr>';
				
				$html .='<td style="text-align:center;">Total</td><td></td><td></td><td></td><td></td><td></td><td></td>';
				foreach ($item_code_list_new_unique as $code) {
					
					$item_count = $this->challan_model->get_itemcout_all_order($order_ids,$code);
					
					$item_count_new = (int) $item_count;
					$html .='<td style="text-align: right;">'.$item_count_new.'</td>';
				}
				$html .='<td style="text-align: right;">'.$challan_crate.'</td>';
				$html .='<td style="text-align: right;">'.$challan_cases.'</td>';
				$html .='<td style="text-align: right;">'.$challan_subtotal.'</td>';
				$html .='<td style="text-align: right;">'.$challan_total.'</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='<td style="text-align: right;">0</td>';
				$html .='</tr></tfoot>';
				
				
				$html .='</tbody>';
				$html .='</table>';
				
				/*echo json_encode($html);
				die;*/
				/*//
					$html = '';
					$html .='<table width="100%" id="challan_data" border="1" style="display: block;overflow: scroll;white-space: nowrap;"><thead style="background: #438EB9;color: #FFF;">';
					$html .='<th>Tag</th>';
					$html .='<th>OrderNo</th>';
					$html .='<th>AccountName</th>';
					$html .='<th>StateID</th>';
					$html .='<th>Ordertype</th>';
					$html .='<th>SalesID</th>';
					$html .='<th>SalesDate</th>';
					foreach ($item_code_list_new_unique as $code) {
					$html .='<th width="5%">'.$code.'</th>';
					}
					$html .='<th>Crates</th>';
					$html .='<th>Cases</th>';
					$html .='<th>OrderAmt</th>';
					$html .='<th>SaleAmt</th>';
					$html .='<th>TCSPer</th>';
					$html .='<th>TCSAmt</th>';
					$html .='</thead>';
					$challan_cases = 0;
					$challan_crate = 0;
					$challan_subtotal = 0;
					$challan_total = 0;
					
					$html .='<tbody>';
					
					
					foreach ($order_ids as $ids) {
					$html .='<tr>';
					$order_data = $this->challan_model->getorderdetail_by_orderId($ids);
					$html .='<td><input type="checkbox" name="order_id[]" class="chk" value="'.$ids.'"></td>';
					$html .='<td>'.$ids.'</td>';
					$account_name = get_account_name($order_data->AccountID,$selected_company);
					$html .='<td>'.$account_name->company.'</td>';
					$html .='<td>'.get_state_code($order_data->client->billing_state).'</td>';
					$html .='<td>'.$order_data->OrderType.'</td>';
					$html .='<td></td>';
					
					$html .='<td></td>';
					
					foreach ($item_code_list_new_unique as $code) {
					$item_data1 = $this->challan_model->get_order_singleitem($ids,$code);
					
					if($item_data1){
					
					$html .='<td width="5%"><input style="width: 50px;" type="text" onchange="total()" name="qty_'.$ids.'_'.$item_data1->ItemID.'" value="'.$item_data1->OrderQty / $item_data1->CaseQty.'"></td>';
					
					}else {
					
					$html .='<td width="5%"><input style="width: 50px;" type="text" onchange="total()" name="qty_'.$ids.'_'.$item_data1->ItemID.'" value="0"></td>';
					}
					}
					
					$html .='<td style="text-align: right;">'.$order_data->Crates.'</td>';
					$challan_crate = $challan_crate + $order_data->Crates;
					$html .='<td style="text-align: right;">'.$order_data->Cases.'</td>';
					$challan_cases = $challan_cases + $order_data->Cases;
					$html .='<td style="text-align: right;">'.$order_data->subtotal.'</td>';
					$challan_subtotal = $challan_subtotal + $order_data->subtotal;
					$html .='<td style="text-align: right;">'.$order_data->OrderAmt.'</td>';
					$challan_total = $challan_total + $order_data->OrderAmt;
					$html .='<td style="text-align: right;">0</td>';
					$html .='<td style="text-align: right;">0</td>';
					$html .='</tr>';
					}
					$html .='</tbody>';
					
					
					$html .='<tfoot><tr>';
					
					$html .='<td style="text-align:center;">Total</td><td></td><td></td><td></td><td></td><td></td><td></td>';
					foreach ($item_code_list as $code) {
					
					$item_count = $this->challan_model->get_itemcout_all_order($order_ids,$code);
					
					$item_count_new = (int) $item_count;
					$html .='<td style="text-align: right;">'.$item_count_new.'</td>';
					}
					$html .='<td style="text-align: right;">'.$challan_crate.'</td>';
					$html .='<td style="text-align: right;">'.$challan_cases.'</td>';
					$html .='<td style="text-align: right;">'.$challan_subtotal.'</td>';
					$html .='<td style="text-align: right;">'.$challan_total.'</td>';
					$html .='<td style="text-align: right;">0</td>';
					$html .='<td style="text-align: right;">0</td>';
					$html .='</tr></tfoot>';
				$html .='</table>';*/
				}else {
				$html = '<h3 style="color:#fc0a0b;">No Record Found...</h3>';
			}
			
			
			echo json_encode($html);
			die;
		}
		
		/* List all Gatepass datatables */
		public function view_gatepass($id = '')
		{
			
			close_setup_menu();
			
			
			$data['title']                = "View Gatepass";
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/gatepass/manage', $data);
		}
		
		public function gatepass_list()
		{
			if (!has_permission_new('gatepass', '', 'view')) {
				ajax_access_denied();
			}
			if ($this->input->is_ajax_request()) {
				if($this->input->post()){
					$this->app->get_table_data('gatepass');
				}
			}
		}
		
		/* List all recurring invoices */
		public function recurring($id = '')
		{
			
			
			close_setup_menu();
			
			$data['invoiceid']            = $id;
			$data['title']                = _l('invoices_list_recurring');
			$data['invoices_years']       = $this->invoices_model->get_invoices_years();
			$data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();
			$this->load->view('admin/invoices/recurring/list', $data);
		}
		
		public function table($clientid = '')
		{
			
			
			$this->app->get_table_data(($this->input->get('recurring') ? 'recurring_invoices' : 'challan'), [
			'clientid' => $clientid,
			'data'     => $data,
			]);
		}
		
		public function client_change_data($customer_id, $current_invoice = '')
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('projects_model');
				$this->load->model('invoice_items_model');
				$data                     = [];
				$data['billing_shipping'] = $this->clients_model->get_customer_billing_and_shipping_details($customer_id);
				$data['client_currency']  = $this->clients_model->get_customer_default_currency($customer_id);
				$data['client_details']  = $this->clients_model->get($customer_id);
				$client_item_div = unserialize($data['client_details']->itemdivision);
				$client_item_div2 = implode(" ",$client_item_div);
				$data['client_details']->itemdivision = $client_item_div2;
				//$data['division'] = $client_item_div;
				$data['item_data'] = $this->invoice_items_model->get2($client_item_div);
				$data['customer_groups'] = $this->clients_model->get_customer_groups($customer_id);
				$data['customer_groups_name'] = $this->clients_model->get_customer_groups_name($data['customer_groups']['0']['groupid']);
				$data['customer_has_projects'] = customer_has_projects($customer_id);
				$data['billable_tasks']        = $this->tasks_model->get_billable_tasks($customer_id);
				
				if ($current_invoice != '') {
					$this->db->select('status');
					$this->db->where('id', $current_invoice);
					$current_invoice_status = $this->db->get(db_prefix() . 'invoices')->row()->status;
				}
				
				$_data['invoices_to_merge'] = !isset($current_invoice_status) || (isset($current_invoice_status) && $current_invoice_status != Invoices_model::STATUS_CANCELLED) ? $this->invoices_model->check_for_merge_invoice($customer_id, $current_invoice) : [];
				
				$data['merge_info'] = $this->load->view('admin/invoices/merge_invoice', $_data, true);
				
				$this->load->model('currencies_model');
				
				$__data['expenses_to_bill'] = !isset($current_invoice_status) || (isset($current_invoice_status) && $current_invoice_status != Invoices_model::STATUS_CANCELLED) ? $this->invoices_model->get_expenses_to_bill($customer_id) : [];
				
				$data['expenses_bill_info'] = $this->load->view('admin/invoices/bill_expenses', $__data, true);
				echo json_encode($data);
			}
		}
		
		
		
		public function add_note($rel_id)
		{
			if ($this->input->post() && user_can_view_invoice($rel_id)) {
				$this->misc_model->add_note($this->input->post(), 'invoice', $rel_id);
				echo $rel_id;
			}
		}
		
		public function get_notes($id)
		{
			if (user_can_view_invoice($id)) {
				$data['notes'] = $this->misc_model->get_notes($id, 'invoice');
				$this->load->view('admin/includes/sales_notes_template', $data);
			}
		}
		
		public function pause_overdue_reminders($id)
		{
			if (has_permission('challan', '', 'edit')) {
				$this->db->where('id', $id);
				$this->db->update(db_prefix() . 'invoices', ['cancel_overdue_reminders' => 1]);
			}
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		public function resume_overdue_reminders($id)
		{
			if (has_permission('challan', '', 'edit')) {
				$this->db->where('id', $id);
				$this->db->update(db_prefix() . 'invoices', ['cancel_overdue_reminders' => 0]);
			}
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		public function mark_as_cancelled($id)
		{
			if (!has_permission('challan', '', 'edit') && !has_permission('challan', '', 'create')) {
				access_denied('invoices');
			}
			
			$success = $this->invoices_model->mark_as_cancelled($id);
			
			if ($success) {
				set_alert('success', _l('invoice_marked_as_cancelled_successfully'));
			}
			
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		public function unmark_as_cancelled($id)
		{
			if (!has_permission('invoices', '', 'edit') && !has_permission('invoices', '', 'create')) {
				access_denied('invoices');
			}
			$success = $this->invoices_model->unmark_as_cancelled($id);
			if ($success) {
				set_alert('success', _l('invoice_unmarked_as_cancelled'));
			}
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		public function copy($id)
		{
			if (!$id) {
				redirect(admin_url('invoices'));
			}
			if (!has_permission('invoices', '', 'create')) {
				access_denied('invoices');
			}
			$new_id = $this->invoices_model->copy($id);
			if ($new_id) {
				set_alert('success', _l('invoice_copy_success'));
				redirect(admin_url('invoices/invoice/' . $new_id));
				} else {
				set_alert('success', _l('invoice_copy_fail'));
			}
			redirect(admin_url('invoices/invoice/' . $id));
		}
		
		public function get_merge_data($id)
		{
			$invoice = $this->invoices_model->get($id);
			$cf      = get_custom_fields('items');
			
			$i = 0;
			
			foreach ($invoice->items as $item) {
				$invoice->items[$i]['taxname']          = get_invoice_item_taxes($item['id']);
				$invoice->items[$i]['long_description'] = clear_textarea_breaks($item['long_description']);
				$this->db->where('item_id', $item['id']);
				$rel              = $this->db->get(db_prefix() . 'related_items')->result_array();
				$item_related_val = '';
				$rel_type         = '';
				foreach ($rel as $item_related) {
					$rel_type = $item_related['rel_type'];
					$item_related_val .= $item_related['rel_id'] . ',';
				}
				if ($item_related_val != '') {
					$item_related_val = substr($item_related_val, 0, -1);
				}
				$invoice->items[$i]['item_related_formatted_for_input'] = $item_related_val;
				$invoice->items[$i]['rel_type']                         = $rel_type;
				
				$invoice->items[$i]['custom_fields'] = [];
				
				foreach ($cf as $custom_field) {
					$custom_field['value']                 = get_custom_field_value($item['id'], $custom_field['id'], 'items');
					$invoice->items[$i]['custom_fields'][] = $custom_field;
				}
				$i++;
			}
			echo json_encode($invoice);
		}
		
		public function get_bill_expense_data($id)
		{
			$this->load->model('expenses_model');
			$expense = $this->expenses_model->get($id);
			
			$expense->qty              = 1;
			$expense->long_description = clear_textarea_breaks($expense->description);
			$expense->description      = $expense->name;
			$expense->rate             = $expense->amount;
			if ($expense->tax != 0) {
				$expense->taxname = [];
				array_push($expense->taxname, $expense->tax_name . '|' . $expense->taxrate);
			}
			if ($expense->tax2 != 0) {
				array_push($expense->taxname, $expense->tax_name2 . '|' . $expense->taxrate2);
			}
			echo json_encode($expense);
		}
		
		public function challan($id = '')
		{
			$redUrl = admin_url('challan/challanAddEdit');
			redirect($redUrl);
			
		}
		
		/* Add new Challan or update existing */
		public function challan2($id = '')
		{
			if ($this->input->post()) {
				$invoice_data = $this->input->post();
				if ($id == '') {
					if (!has_permission('challan', '', 'create')) {
						access_denied('challan');
					}
					
					$id = $this->order_model->add($invoice_data);
					if ($id == false) {
						set_alert('warning', "Challan already created for this order");
						redirect(admin_url('challan/challan_list'));
						
						}else{
						set_alert('success', _l('added_successfully', _l('invoice')));
						$redUrl = admin_url('order/list_orders/' . $id);
						redirect($redUrl);
					}
					} else {
					if (!has_permission('challan', '', 'edit')) {
						access_denied('challan');
					}
					$success = $this->invoices_model->update($invoice_data, $id);
					if ($success) {
						set_alert('success', _l('updated_successfully', _l('invoice')));
					}
					redirect(admin_url('order/list_orders/' . $id));
				}
			}
			if ($id == '') {
				$title                  = _l('create_new_order');
				$data['billable_tasks'] = [];
				} else {
				$invoice = $this->invoices_model->get($id);
				
				if (!$invoice || !user_can_view_invoice($id)) {
					blank_page(_l('invoice_not_found'));
				}
				
				$data['invoices_to_merge'] = $this->invoices_model->check_for_merge_invoice($invoice->clientid, $invoice->id);
				$data['expenses_to_bill']  = $this->invoices_model->get_expenses_to_bill($invoice->clientid);
				
				$data['invoice']        = $invoice;
				$data['edit']           = true;
				$data['billable_tasks'] = $this->tasks_model->get_billable_tasks($invoice->clientid, !empty($invoice->project_id) ? $invoice->project_id : '');
				
				$title = _l('edit', _l('invoice_lowercase')) . ' - ' . format_invoice_number($invoice->id);
			}
			
			if ($this->input->get('customer_id')) {
				$data['customer_id'] = $this->input->get('customer_id');
			}
			
			$this->load->model('payment_modes_model');
			$data['payment_modes'] = $this->payment_modes_model->get('', [
			'expenses_only !=' => 1,
			]);
			
			$this->load->model('taxes_model');
			$data['taxes'] = $this->taxes_model->get();
			$this->load->model('invoice_items_model');
			
			$data['ajaxItems'] = false;
			if (total_rows(db_prefix() . 'items') <= ajax_on_total_items()) {
				$data['items'] = $this->invoice_items_model->get_grouped();
				} else {
				$data['items']     = [];
				$data['ajaxItems'] = true;
			}
			$data['items_groups'] = $this->invoice_items_model->get_groups();
			
			$this->load->model('currencies_model');
			$this->load->model('clients_model');
			$data['currencies'] = $this->currencies_model->get();
			
			$data['base_currency'] = $this->currencies_model->get_base_currency();
			
			$data['staff']     = $this->staff_model->get('', ['active' => 1]);
			$data['rootcompany'] = $this->clients_model->get_rootcompany();
			// Customer groups
			$data['groups'] = $this->clients_model->get_groups();
			$data['title']     = $title;
			$data['bodyclass'] = 'invoice';
			$this->load->view('admin/order/order', $data);
		}
		
		/* Get all invoice data used when user click on invoiec number in a datatable left side*/
		public function get_order_data_ajax($id)
		{
			if (!has_permission('challan', '', 'view')
			&& !has_permission('challan', '', 'view_own')
			&& get_option('allow_staff_view_invoices_assigned') == '0') {
				echo _l('access_denied');
				die;
			}
			
			if (!$id) {
				die(_l('invoice_not_found'));
			}
			
			$invoice = $this->order_model->get($id);
			
			if (!$invoice || !user_can_view_invoice($id)) {
				echo _l('invoice_not_found');
				die;
			}
			
			$template_name = 'invoice_send_to_customer';
			
			if ($invoice->sent == 1) {
				$template_name = 'invoice_send_to_customer_already_sent';
			}
			
			$data = prepare_mail_preview_data($template_name, $invoice->clientid);
			
			// Check for recorded payments
			$this->load->model('payments_model');
			$data['invoices_to_merge']          = $this->invoices_model->check_for_merge_invoice($invoice->clientid, $id);
			$data['members']                    = $this->staff_model->get('', ['active' => 1]);
			$data['payments']                   = $this->payments_model->get_invoice_payments($id);
			$data['activity']                   = $this->invoices_model->get_invoice_activity($id);
			$data['totalNotes']                 = total_rows(db_prefix() . 'notes', ['rel_id' => $id, 'rel_type' => 'invoice']);
			$data['invoice_recurring_invoices'] = $this->invoices_model->get_invoice_recurring_invoices($id);
			
			$data['applied_credits'] = $this->credit_notes_model->get_applied_invoice_credits($id);
			// This data is used only when credit can be applied to invoice
			if (credits_can_be_applied_to_invoice($invoice->status)) {
				$data['credits_available'] = $this->credit_notes_model->total_remaining_credits_by_customer($invoice->clientid);
				
				if ($data['credits_available'] > 0) {
					$data['open_credits'] = $this->credit_notes_model->get_open_credits($invoice->clientid);
				}
				
				$customer_currency = $this->clients_model->get_customer_default_currency($invoice->clientid);
				$this->load->model('currencies_model');
				
				if ($customer_currency != 0) {
					$data['customer_currency'] = $this->currencies_model->get($customer_currency);
					} else {
					$data['customer_currency'] = $this->currencies_model->get_base_currency();
				}
			}
			
			$data['invoice'] = $invoice;
			$data['invoice_generate'] = $this->order_model->check_invoice_generate($id);
			$data['record_payment'] = false;
			$data['send_later']     = false;
			
			if ($this->session->has_userdata('record_payment')) {
				$data['record_payment'] = true;
				$this->session->unset_userdata('record_payment');
				} elseif ($this->session->has_userdata('send_later')) {
				$data['send_later'] = true;
				$this->session->unset_userdata('send_later');
			}
			
			$this->load->view('admin/order/invoice_preview_template', $data);
		}
		
		public function apply_credits($invoice_id)
		{
			$total_credits_applied = 0;
			foreach ($this->input->post('amount') as $credit_id => $amount) {
				$success = $this->credit_notes_model->apply_credits($credit_id, [
				'invoice_id' => $invoice_id,
				'amount'     => $amount,
				]);
				if ($success) {
					$total_credits_applied++;
				}
			}
			
			if ($total_credits_applied > 0) {
				update_invoice_status($invoice_id, true);
				set_alert('success', _l('invoice_credits_applied'));
			}
			redirect(admin_url('order/list_orders/' . $invoice_id));
		}
		
		public function get_invoices_total()
		{
			if ($this->input->post()) {
				load_invoices_total_template();
			}
		}
		
		/* Record new inoice payment view */
		public function record_invoice_payment_ajax($id)
		{
			$this->load->model('payment_modes_model');
			$this->load->model('payments_model');
			$data['payment_modes'] = $this->payment_modes_model->get('', [
			'expenses_only !=' => 1,
			]);
			$data['invoice']  = $this->invoices_model->get($id);
			$data['payments'] = $this->payments_model->get_invoice_payments($id);
			$this->load->view('admin/invoices/record_payment_template', $data);
		}
		
		/* Record new inoice  */
		public function crate_invoice_by_ajax($id)
		{
			$this->load->model('payment_modes_model');
			$this->load->model('payments_model');
			$data['payment_modes'] = $this->payment_modes_model->get('', [
			'expenses_only !=' => 1,
			]);
			$order  = $this->order_model->get($id);
			//echo "<pre>";
			$addedfrom = !DEFINED('CRON') ? get_staff_user_id() : 0;
			$invoicedata=array(
			"sent"=>$order->sent,
			"datesend"=>$order->datesend,
			"clientid"=>$order->clientid,
			"deleted_customer_name"=>$order->deleted_customer_name,
			"order_id"=>$order->number,
			"order_type"=>$order->order_type,
			"dist_comp"=>$order->dist_comp,
			"dist_sale_agent"=>$order->dist_sale_agent,
			"prefix"=>'INV-',
			"number_format"=>$order->number_format,
			"datecreated"=>date('Y-m-d H:i:s'),
			"date"=>date('Y-m-d'),
			"currency"=>$order->currency,
			"subtotal"=>$order->subtotal,
			"total_tax"=>$order->total_tax,
			"total"=>$order->total,
			"total_cases"=>$order->total_cases,
			"adjustment"=>$order->adjustment,
			"addedfrom"=>$addedfrom,
			"hash"=>$order->hash,
			"status"=>$order->status,
			"allowed_payment_modes"=>$order->allowed_payment_modes,
			"token"=>$order->token,
			"discount_percent"=>$order->discount_percent,
			"discount_total"=>$order->discount_total,
			"discount_type"=>$order->discount_type,
			"sale_agent"=>$order->sale_agent,
			"billing_street"=>$order->billing_street,
			"billing_city"=>$order->billing_city,
			"billing_state"=>$order->billing_state,
			"billing_zip"=>$order->billing_zip,
			"billing_country"=>$order->billing_country,
			"shipping_street"=>$order->shipping_street,
			"shipping_state"=>$order->shipping_state,
			"shipping_city"=>$order->shipping_city,
			"shipping_zip"=>$order->shipping_zip,
			"shipping_country"=>$order->shipping_country,
			"include_shipping"=>$order->include_shipping,
			"show_shipping_on_invoice"=>$order->show_shipping_on_invoice,
			"show_quantity_as"=>$order->show_quantity_as,
			"subscription_id"=>$order->subscription_id,
			"short_link"=>$order->short_link,
			"project_id"=>$order->project_id
			);
			
			$items = $order->items;
			/*foreach ($items as $key => $value) {
				# code...
				echo $value["description"];
				echo "<br>";
				
			}*/
			//print_r($items);
			//echo $items[0]['rel_type'];
			//die;
			$this->db->insert(db_prefix() . 'invoices', $invoicedata);
			$invoice_id = $this->db->insert_id();
			if($invoice_id){
				
				$this->db->where('id', $invoice_id);
				$this->db->update(db_prefix() . 'invoices', [
				'number' => $invoice_id,
				]);
				
				foreach ($items as $key => $item) {
					# code...
					
					$itemdata=array(
					"rel_id"=>$invoice_id,
					"rel_type"=>$item['rel_type'],
					"description"=>$item['description'],
					"long_description"=>$item['long_description'],
					"hsn_code"=>$item['hsn_code'],
					"qty"=>$item['qty'],
					"pack_qty"=>$item['pack_qty'],
					"rate"=>$item['rate'],
					"total_amt"=>$item['total_amt'],
					"discount_amt"=>$item['discount_amt'],
					"taxable_amt"=>$item['taxable_amt'],
					"gst"=>$item['gst'],
					"gst_amt"=>$item['gst_amt'],
					"unit"=>$item['unit'],
					"grand_total"=>$item['grand_total'],
					"item_order"=>$item['item_order']
					);
					$this->db->insert(db_prefix() . 'itemable', $itemdata);
				}        
				
			}
			
			redirect(admin_url('order/get_order_data_ajax/' . $id));
			
		}
		
		/* This is where invoice payment record $_POST data is send */
		public function record_payment()
		{
			if (!has_permission('payments', '', 'create')) {
				access_denied('Record Payment');
			}
			if ($this->input->post()) {
				$this->load->model('payments_model');
				$id = $this->payments_model->process_payment($this->input->post(), '');
				if ($id) {
					set_alert('success', _l('invoice_payment_recorded'));
					redirect(admin_url('payments/payment/' . $id));
					} else {
					set_alert('danger', _l('invoice_payment_record_failed'));
				}
				redirect(admin_url('order/list_orders/' . $this->input->post('invoiceid')));
			}
		}
		
		/* Send invoice to email */
		public function send_to_email($id)
		{
			$canView = user_can_view_invoice($id);
			if (!$canView) {
				access_denied('Invoices');
				} else {
				if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && $canView == false) {
					access_denied('Invoices');
				}
			}
			
			try {
				$statementData = [];
				if ($this->input->post('attach_statement')) {
					$statementData['attach'] = true;
					$statementData['from']   = to_sql_date($this->input->post('statement_from'));
					$statementData['to']     = to_sql_date($this->input->post('statement_to'));
				}
				
				$success = $this->invoices_model->send_invoice_to_client(
				$id,
				'',
				$this->input->post('attach_pdf'),
				$this->input->post('cc'),
				false,
				$statementData
				);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			
			// In case client use another language
			load_admin_language();
			if ($success) {
				set_alert('success', _l('invoice_sent_to_client_success'));
				} else {
				set_alert('danger', _l('invoice_sent_to_client_fail'));
			}
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		/* Delete invoice payment*/
		public function delete_payment($id, $invoiceid)
		{
			if (!has_permission('payments', '', 'delete')) {
				access_denied('payments');
			}
			$this->load->model('payments_model');
			if (!$id) {
				redirect(admin_url('payments'));
			}
			$response = $this->payments_model->delete($id);
			if ($response == true) {
				set_alert('success', _l('deleted', _l('payment')));
				} else {
				set_alert('warning', _l('problem_deleting', _l('payment_lowercase')));
			}
			redirect(admin_url('order/list_orders/' . $invoiceid));
		}
		
		/* Delete invoice */
		public function delete($id)
		{
			if (!has_permission('invoices', '', 'delete')) {
				access_denied('invoices');
			}
			if (!$id) {
				redirect(admin_url('order/list_orders'));
			}
			$success = $this->invoices_model->delete($id);
			
			if ($success) {
				set_alert('success', _l('deleted', _l('invoice')));
				} else {
				set_alert('warning', _l('problem_deleting', _l('invoice_lowercase')));
			}
			if (strpos($_SERVER['HTTP_REFERER'], 'list_orders') !== false) {
				redirect(admin_url('order/list_orders'));
				} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		
		public function delete_attachment($id)
		{
			$file = $this->misc_model->get_file($id);
			if ($file->staffid == get_staff_user_id() || is_admin()) {
				echo $this->invoices_model->delete_attachment($id);
				} else {
				header('HTTP/1.0 400 Bad error');
				echo _l('access_denied');
				die;
			}
		}
		
		/* Will send overdue notice to client */
		public function send_overdue_notice($id)
		{
			$canView = user_can_view_invoice($id);
			if (!$canView) {
				access_denied('Invoices');
				} else {
				if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && $canView == false) {
					access_denied('Invoices');
				}
			}
			
			$send = $this->invoices_model->send_invoice_overdue_notice($id);
			if ($send) {
				set_alert('success', _l('invoice_overdue_reminder_sent'));
				} else {
				set_alert('warning', _l('invoice_reminder_send_problem'));
			}
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		/* Generates invoice PDF and senting to email of $send_to_email = true is passed */
		public function pdf($id)
		{
			if (!$id) {
				redirect(admin_url('challan/challan_list'));
			}
			
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('Invoices');
			}
			
			$invoice        = $this->challan_model->getchallandetail($id);
			//print_r($invoice);
			
			$invoice        = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
			//$invoice_number = format_invoice_number($invoice->id);
			
			try {
				$pdf = invoice_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($id)) . '-Invoice.pdf', $type);
		}
		public function DeliveryNotePdf($id)
		{
			if (!$id) {
				redirect(admin_url('challan/challan_list'));
			}
			
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('Invoices');
			}
			
			$invoice        = $this->challan_model->getchallandetail($id);
			//print_r($invoice);
			
			$invoice        = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
			//$invoice_number = format_invoice_number($invoice->id);
			
			try {
				$pdf = deliverynote_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($id)) . '-Invoice.pdf', $type);
		}
		public function RouteMemo($id)
		{
			if (!$id) {
				redirect(admin_url('challan/challan_list'));
			}
			
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('Invoices');
			}
			
			$invoice        = $this->challan_model->get($id);
			//print_r($invoice);
			
			$invoice        = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
			//$invoice_number = format_invoice_number($invoice->id);
			
			try {
				$pdf = RouteMemo_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($id)) . '-RouteMemo.pdf', $type);
		}
		
		public function dispatchsheet($challan_id)
		{
			if (!$challan_id) {
				redirect(admin_url('challan/challan_list'));
			}
			
			if (!has_permission_new('challan_list', '', 'view')) {
				access_denied('Invoices');
			}
			
			$invoice        = $this->challan_model->getchallandetail($challan_id);
			/*print_r($invoice);
			die;*/
			try {
				$pdf = dispatch_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($challan_id)) . '-Dispatch.pdf', $type);
		}
		
		/* Generates Gate Pass PDF and senting to email of $send_to_email = true is passed */
		public function gatepass($challan_id)
		{
			if (!$challan_id) {
				redirect(admin_url('challan/challan_list'));
			}
			
			if (!has_permission_new('gatepass', '', 'view')) {
				access_denied('Invoices');
			}
			
			$invoice        = $this->challan_model->getchallandetail($challan_id);
			if(is_null($invoice->Gatepassuserid)){
				$selected_company = $this->session->userdata('root_company');
				$fy = $this->session->userdata('finacial_year');
				$this->db->where('PlantID', $selected_company);
				$this->db->where('FY', $fy);  
				$this->db->where('ChallanID', $challan_id);
				$this->db->update(db_prefix() . 'challanmaster', [
				'gatepasstime' => date('Y-m-d H:i:s'),
				'Gatepassuserid' => $this->session->userdata('username'),
				'GetPassTime' => date('Y-m-d H:i:s'),
				]);
			}
			
			
			try {
				$pdf = gatepass_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($challan_id)) . '-Gatepass.pdf', $type);
		}
		
		
		public function mark_as_sent($id)
		{
			if (!$id) {
				redirect(admin_url('order/list_orders'));
			}
			if (!user_can_view_invoice($id)) {
				access_denied('Invoice Mark As Sent');
			}
			
			$success = $this->invoices_model->set_invoice_sent($id, true);
			
			if ($success) {
				set_alert('success', _l('invoice_marked_as_sent'));
				} else {
				set_alert('warning', _l('invoice_marked_as_sent_failed'));
			}
			
			redirect(admin_url('order/list_orders/' . $id));
		}
		
		public function get_due_date()
		{
			if ($this->input->post()) {
				$date    = $this->input->post('date');
				$duedate = '';
				if (get_option('invoice_due_after') != 0) {
					$date    = to_sql_date($date);
					$d       = date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime($date)));
					$duedate = _d($d);
					echo $duedate;
				}
			}
		}
		
		public function OtherPOpdf($id)
		{
			if (!$id) {
				redirect(admin_url('purchase/purchase_Po'));
			}
			
			if (!has_permission_new('purchase-order-po', '', 'view')) {
				access_denied('Invoices');
			}
			$invoice        = $this->challan_model->get_order_entry_details_PO($id);
			
			
			try {
				$pdf = PO_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			// print_r($pdf);
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($id)) . '-PurchaseOrder.pdf', $type);
		}
		public function PurchEntrypdf($id)
		{
			if (!$id) {
				redirect(admin_url('purchase/pur_order'));
			}
			
			if (!has_permission_new('purchase-order', '', 'view')) {
				access_denied('Invoices');
			}
			$invoice        = $this->challan_model->get_order_entry_details($id);
			
			
			try {
				$pdf = PO_Entry_pdf($invoice);
				} catch (Exception $e) {
				$message = $e->getMessage();
				echo $message;
				if (strpos($message, 'Unable to get the size of the image') !== false) {
					show_pdf_unable_to_get_image_size_error();
				}
				die;
			}
			// print_r($pdf);
			$type = 'D';
			
			if ($this->input->get('output_type')) {
				$type = $this->input->get('output_type');
			}
			
			if ($this->input->get('print')) {
				$type = 'I';
			}
			
			$pdf->Output(mb_strtoupper(slug_it($id)) . '-PurchaseEntry.pdf', $type);
		}
		
		public function GetSchemeData()
		{
			$qty=$this->input->post('qty'); 
			$DistType=$this->input->post('DistType'); 
			$State=$this->input->post('State'); 
			$date=$this->input->post('date'); 
			$ItemID=$this->input->post('ItemID'); 
			$Scheme = $this->challan_model->GetSchemeData($DistType,$State,$date,$ItemID);
			$return = 0;
			foreach($Scheme as $each){
				if($qty >= $each['SlabQty']  &&  $each['SlabQty'] > 0){
					$Disc_pkt = floor($qty / $each['SlabQty']) * $each['Disc_pkt'];
					$return = $Disc_pkt;
					break;
				}
			}
			echo json_encode($return);
		}
	}
