<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Sale_return extends AdminController
	{
		private $not_importable_fields = ['id'];
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Sale_return_model');
			$this->load->model('challan_model');
			$this->load->model('clients_model');
		}
		
		/* List all available items */
		public function index()
		{
			if (!has_permission_new('sale_return', '', 'view')) {
				access_denied('Sale return');
			}
			
			$data['sale_returns'] = $this->Sale_return_model->SaleRtnList();
			$data['PartyList'] = $this->clients_model->GetPartyList();
			/*echo "<pre>";
				print_r($data['sale_returns']);
			die;*/
			$data['title'] = "Sales Return";
			$this->load->view('admin/sale_return/manage', $data);
		}
		
		public function load_data_for_salertn()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date')
			);
			$data = $this->Sale_return_model->load_data_for_salertn($data);
			echo json_encode($data);
		}
		
		public function edit($id = '')
		{
			if ($this->input->post()) {
				if (!has_permission_new('sale_return', '', 'edit')) {
					access_denied('sale return');
				}
				$data = $this->input->post();
				$success = $this->Sale_return_model->update_sale_return($data);
                if ($success == "true") {
					set_alert('success', 'Sale return update Successfully..');
					redirect(admin_url('sale_return/edit/'.$data["ex_sale_return_id"]));
				}
			}
			
			if($id != ''){
				$data['sale_return'] = $this->Sale_return_model->get_sale_return_details($id);
			}
			
			$data['PartyList'] = $this->clients_model->GetPartyList();
			$data['ShippingDetails']  = $this->Sale_return_model->GetShippingAddress($data['sale_return']->AccountID2);
			$data['sale_returns'] = $this->Sale_return_model->SaleRtnList();
			/*echo "<pre>";
				print_r($data['ShippingDetails']);
			die;*/
			$this->load->model('sale_reports_model');
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['title'] = "Sales Return";
			$this->load->view('admin/sale_return/manage', $data);
		}
		
		public function accountlist()
		{
			$postData = $this->input->post();
			$data = $this->Sale_return_model->getaccounts($postData);
			echo json_encode($data);
		}
		
		public function get_Account_Details()
		{
			$postData = $this->input->post();
			$Account_data = $this->Sale_return_model->get_Account_Details($postData);
			echo json_encode($Account_data);
		}
		
		public function transaction_list()
		{
			$postData = $this->input->post();
			$data = $this->Sale_return_model->getransaction($postData);
			echo json_encode($data);
		}
		
		public function itemlist()
		{
			$postData = $this->input->post();
			$data = $this->Sale_return_model->getitems($postData);
			echo json_encode($data);
		}
		
		public function ItemDetails(){
			
			// POST data
			$postData = $this->input->post();
			// Get data
			$data = $this->Sale_return_model->getitemsDetails($postData);
			
			echo json_encode($data);
		}
		
		public function get_sale_item(){
			
			// POST data
			$postData = $this->input->post();
			
			// Get data
			$data = $this->Sale_return_model->getsale_item_list($postData);
			
			echo json_encode($data);
		}
		
		/* Get item by id / ajax */
		public function get_bill_id($item_code,$act_code,$shipping_party)
		{
			if ($this->input->is_ajax_request()) {
				$item                     = $this->Sale_return_model->get_bill($item_code,$act_code,$shipping_party);
				
				echo json_encode($item);
			}
		}
//============= Get Bill Details By ItemID, TransID and IncID ==================
    public function BillItemDetailsByID($bill_id,$item_code,$Incid)
	{
		if ($this->input->is_ajax_request()) {
			$item = $this->Sale_return_model->GetBillItemDetailsByID($bill_id,$item_code,$Incid);
			echo json_encode($item);
		}
	}
    
    
		/* Get bill details by id / ajax */
		public function get_bill_detail($bill_id,$item_code)
		{
			if ($this->input->is_ajax_request()) {
				$item                     = $this->Sale_return_model->get_bill_details($bill_id,$item_code);
				
				echo json_encode($item);
			}
		}
		
		
		public function add()
		{
			$data = $this->input->post();
			if (!has_permission_new('sale_return', '', 'create')) {
				access_denied('invoices');
			}
			if($data["act_name"] == "" || $data["act_name"] == null){
				set_alert('warning', "please add atleast one item..");
				redirect(admin_url('sale_return'));
			}
			$date = to_sql_date($data['sale_return_date'])." ".date('H:i:s');
			$month = substr($date,5,2);
			if($data["type_select"] =="fresh"){
				$salertnType = "Fresh";
			}else{
				$salertnType = "Damage";
			}
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
                $GodownID = 'CSPL';
				}else if($selected_company == "2"){
                $GodownID = 'CFF';
				}else if($selected_company == "3"){
                $GodownID = 'CBUPL';
			}
            
			if($selected_company == 1){  
				$new_sale_returnNumber = get_option('next_sale_return_number_for_cspl');
				}elseif($selected_company == 2){
				$new_sale_returnNumber = get_option('next_sale_return_number_for_cff');
				}elseif($selected_company == 3){
				$new_sale_returnNumber = get_option('next_sale_return_number_for_cbu');
				}elseif($selected_company == 4){
				$new_sale_returnNumber = get_option('next_sale_return_number_for_cbupl');
			}
			
			$Billno = "SRT".$fy.$new_sale_returnNumber;
			
			$roundoff = round($data["net_total_val"]);
			$rnd_amt = $data["roundoff_total_val"];
			(int) $count = $data["countof_record"]; 
			$ItCount = $count - 1;
			
			$INSERT = 0;
			$sale_rtn = array(
            "FY"=>$fy,
            "PlantID"=>$selected_company,
            "BT"=>$data["is_taxable"],
            "SalesRtnID"=>$Billno,
            "Transdate"=>$date,
            "cd_note_no"=>$data["cd_note_no"],
            "cd_note_date"=>to_sql_date($data['cd_note_date'])." ".date('H:i:s'),
            "AccountID"=>$data["act_name"],
            "AccountID2"=>$data["shipping_party"],
            "AccountID2_address"=>$data["shipping_address"],
            "PayType"=>"C",
            "SaleAmt"=>$data["gross_total_val"],
            "DiscAmt"=>$data["disc_total_val"],
            "cgstamt"=>$data["cgst_total_val"],
            "sgstamt"=>$data["sgst_total_val"],
            "igstamt"=>$data["igst_total_val"],
            "BillAmt"=>$data["net_total_val"]+$data["roundoff_total_val"],
            "RndOffAmt"=>$data["roundoff_total_val"],
            "RndAmt"=>$data["net_total_val"],
            "SalesRtnTypeID"=>$salertnType,
            "ItCount"=>$ItCount,
            "passedfrom"=>"SALESRTN",
            "Userid"=>$this->session->userdata('username'),
			);
            
			$this->db->insert(db_prefix() . 'salesreturn', $sale_rtn);
			$INSERT = $this->db->affected_rows();
			if($INSERT > 0){
				$this->increment_next_number();
				$TransID = '';
				for($i=1; $i<$count; $i++) {
					$itemid = "item_code".$i;
					$hsn_val = "hsn_val".$i;
					$disc_per_val = "disc_per_val".$i;
					$disc_amt_val = "disc_amt_val".$i;
					$cgst_per_val = "cgst_per_val".$i;
					$cgst_amt_val = "cgst_amt_val".$i;
					$sgst_per_val = "sgst_per_val".$i;
					$sgst_amt_val = "sgst_amt_val".$i;
					$igst_per_val = "igst_per_val".$i;
					$igst_amt_val = "igst_amt_val".$i;
					$total_amt_val = "total_amt_val".$i;
					$basic_rate_val = "basic_rate_val".$i;
					$return_qty = "return_qty".$i;
					$pack_val = "pack_val".$i;
					$sale_id = "sale_id".$i;
					$sale_rate_val = "sale_rate_val".$i;
					$saleAmtPerItem =  $data[$total_amt_val] -  $data[$sgst_amt_val] - $data[$sgst_amt_val] - $data[$igst_amt_val];
					
					$TransID = $data[$sale_id];
					
					
					$ItemData = $this->Sale_return_model->GetItemDetails($data[$itemid]);
					
					
					$salertn_details = array(
                    "PlantID"=>$selected_company,
                    "FY"=>$fy,
                    "cnfid"=>"1",
                    "OrderID"=>$Billno,
                    "GodownID"=>$GodownID,
                    "TransDate"=>$date,
                    "TransDate2"=>$date,
                    "BillID"=>$data[$sale_id],
                    "TransID"=>$data[$sale_id],
                    "TType"=>"R",
                    "TType2"=>$salertnType,
                    "AccountID"=>$data["act_name"],
                    "ItemID"=>$data[$itemid],
                    "CaseQty"=>$data[$pack_val],
                    "SaleRate"=>$data[$sale_rate_val],
                    "BasicRate"=>$data[$basic_rate_val],
                    "SuppliedIn"=>"CS",
                    "BilledQty"=>$data[$return_qty],
                    "DiscPerc"=>$data[$disc_per_val],
                    "DiscAmt"=>$data[$disc_amt_val],
                    "cgst"=>$data[$cgst_per_val],
                    "cgstamt"=>$data[$cgst_amt_val],
                    "sgst"=>$data[$sgst_per_val],
                    "sgstamt"=>$data[$sgst_amt_val],
                    "igst"=>$data[$igst_per_val],
                    "igstamt"=>$data[$igst_amt_val],
                    "ChallanAmt"=>$saleAmtPerItem,
                    "NetChallanAmt"=>$data[$total_amt_val],
                    "Ordinalno"=>$i,
                    "UserID"=>$this->session->userdata('username'),
					);
					$this->db->insert(db_prefix() . 'history', $salertn_details);
					
					if($salertnType == 'Damage'){
						if($ItemData->SubGrpID1 == '17' || $ItemData->SubGrpID1 == '18'){
							$data_array_result2 = array(
							'PlantID' => $selected_company,
							'FY' => $fy,
							'cnfid' => 1,
							'OrderID' => $Billno,
							"GodownID"=>$GodownID,
							'TransID' => $data[$sale_id],
							'TransDate' => $date,
							'BillID' => $data[$sale_id],
							'TransDate2' => date('Y-m-d H:i:s'),
							'TType' => 'I',
							'TType2' => 'Inward',
							'AccountID' => $data["act_name"],     
							'ItemID' => 'GFFG0292',   //convert to 
							'ItemIDTo' => $data[$itemid], //item id
							'CaseQty' => $ItemData->case_qty,
							'SaleRate' => $data[$sale_rate_val],
							'BasicRate' => $data[$basic_rate_val],
							'SuppliedIn' => $ItemData->outst_supply_in,
							'BilledQty' => $data[$return_qty] * $ItemData->weight,
							'OrderQty' => $data[$return_qty] * $ItemData->weight,  // conver to in kg
							"DiscPerc"=>$data[$disc_per_val],
							"DiscAmt"=>$data[$disc_amt_val],
							"cgst"=>$data[$cgst_per_val],
							"cgstamt"=>$data[$cgst_amt_val],
							"sgst"=>$data[$sgst_per_val],
							"sgstamt"=>$data[$sgst_amt_val],
							"igst"=>$data[$igst_per_val],
							"igstamt"=>$data[$igst_amt_val],
							"ChallanAmt"=>$saleAmtPerItem,
							"NetChallanAmt"=>$data[$total_amt_val],
							'UserID' => $_SESSION['username'],
							);
							$this->db->insert(db_prefix() . 'history', $data_array_result2);
						}
					}
					
				} 
				$ord_no = 1;
				$credit_ledger = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"C",
                "AccountID"=>$data['act_name'],
                "EffectOn"=>'SALE',
                "Amount"=>$data["net_total_val"],
				"BillNo"=>$TransID,
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
				);
				$this->db->insert(db_prefix() . 'accountledger', $credit_ledger);
				$ord_no++;
				
				$debit_ledger = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"D",
                "AccountID"=>"SALE",
                "EffectOn"=>$data['act_name'],
                "Amount"=>$data['gross_total_val'],
				"BillNo"=>$TransID,
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
				);
				$this->db->insert(db_prefix() . 'accountledger', $debit_ledger);
				$ord_no++;
				
				
				if($data['disc_total_val'] > 0){
					$credit_Disc = array(
					"FY"=>$fy,
					"PlantID"=>$selected_company,
					"VoucherID"=>$Billno,
					"Transdate"=>$date,
					"TransDate2"=>date('Y-m-d H:i:s'),
					"TType"=>"C",
					"AccountID"=>"DISC",
					"EffectOn"=>$data['act_name'],
					"Amount"=>$data['disc_total_val'],
					"BillNo"=>$TransID,
					"Narration"=>"By SalesRtnID ".$Billno,
					"PassedFrom"=>"SALESRTN",
					"OrdinalNo"=>$ord_no,
					"UserID"=>$this->session->userdata('username'),
					);
					$this->db->insert(db_prefix() . 'accountledger', $credit_Disc);
					$ord_no++;
				}
				
				if($data['igst_total_val']=="0.00"){
					
					$debit_ledger_sgst = array(
					"FY"=>$fy,
					"PlantID"=>$selected_company,
					"VoucherID"=>$Billno,
					"Transdate"=>$date,
					"TransDate2"=>date('Y-m-d H:i:s'),
					"TType"=>"D",
					"AccountID"=>"SGST",
					"EffectOn"=>$data['act_name'],
					"Amount"=>$data['sgst_total_val'],
					"BillNo"=>$TransID,
					"Narration"=>"By SalesRtnID ".$Billno,
					"PassedFrom"=>"SALESRTN",
					"OrdinalNo"=>$ord_no,
					"UserID"=>$this->session->userdata('username'),
					);
					$this->db->insert(db_prefix() . 'accountledger', $debit_ledger_sgst);
					$ord_no++;
					
					
					$debit_ledger_cgst = array(
					"FY"=>$fy,
					"PlantID"=>$selected_company,
					"VoucherID"=>$Billno,
					"Transdate"=>$date,
					"TransDate2"=>date('Y-m-d H:i:s'),
					"TType"=>"D",
					"AccountID"=>"CGST",
					"EffectOn"=>$data['act_name'],
					"Amount"=>$data['cgst_total_val'],
					"BillNo"=>$TransID,
					"Narration"=>"By SalesRtnID ".$Billno,
					"PassedFrom"=>"SALESRTN",
					"OrdinalNo"=>$ord_no,
					"UserID"=>$this->session->userdata('username'),
					);
					$this->db->insert(db_prefix() . 'accountledger', $debit_ledger_cgst);
					$ord_no++;
					
					}else{
					$debit_ledger_igst = array(
					"FY"=>$fy,
					"PlantID"=>$selected_company,
					"VoucherID"=>$Billno,
					"Transdate"=>$date,
					"TransDate2"=>date('Y-m-d H:i:s'),
					"TType"=>"D",
					"AccountID"=>"IGST",
					"EffectOn"=>$data['act_name'],
					"Amount"=>$data['igst_total_val'],
					"BillNo"=>$TransID,
					"Narration"=>"By SalesRtnID ".$Billno,
					"PassedFrom"=>"SALESRTN",
					"OrdinalNo"=>$ord_no,
					"UserID"=>$this->session->userdata('username'),
					);
					$this->db->insert(db_prefix() . 'accountledger', $debit_ledger_igst);
					$ord_no++;
					
				}
				
				
				if($rnd_amt > 0 || $rnd_amt < 0){
					if($rnd_amt < 0){
						$TType = "D";
						}else{
						$TType = "C";
					}
					$debit_ledger_roundoff = array(
					"FY"=>$fy,
					"PlantID"=>$selected_company,
					"VoucherID"=>$Billno,
					"Transdate"=>$date,
					"TransDate2"=>date('Y-m-d H:i:s'),
					"TType"=>$TType,
					"AccountID"=>"ROUNDOFF",
					"EffectOn"=>$data['act_name'],
					"Amount"=>abs($rnd_amt),
					"BillNo"=>$TransID,
					"Narration"=>"By SalesRtnID ".$Billno,
					"PassedFrom"=>"SALESRTN",
					"OrdinalNo"=>$ord_no,
					"UserID"=>$this->session->userdata('username'),
					);
					$this->db->insert(db_prefix() . 'accountledger', $debit_ledger_roundoff);
					$ord_no++;
					
				}
			}
			set_alert('success', 'Sale return added Successfully..');
			redirect(admin_url('sale_return'));
		}
		
		/**
			* delete Sale return entry
			* @param  integer $id
			* @return
		*/
		public function delete_sale_entry($id)
		{
			if (!has_permission_new('sale_return', '', 'delete')) {
                access_denied('sale return');
			}
			$success = $this->Sale_return_model->delete_sale_entry($id);
			$message = '';
			if ($success) {
				$message = _l('deleted');
				set_alert('success', $message);
				} else {
				$message = _l('can_not_delete');
				set_alert('warning', $message);
			}
			redirect(admin_url('sale_return'));
		}
		
		public function cancel_sale_entry($id)
		{
			if (!has_permission_new('sale_return', '', 'edit')) {
                access_denied('sale return');
			}
			
			$success = $this->Sale_return_model->cancel_sale_entry($id);
			$message = '';
			if ($success) {
				$message = _l('cancel sale return');
				set_alert('success', $message);
				} else {
				$message = _l('can_not_delete');
				set_alert('warning', $message);
			}
			redirect(admin_url('sale_return/edit/'.$id));
		}
		
		public function get_acc_bal($id)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$this->db->where('PlantID', $selected_company);
			$this->db->where('FY', $fy);
			$this->db->where('AccountID', $id);
			
			return $this->db->get(db_prefix() . 'accountbalances')->row();
		}
		
		/**
			* @since  2.7.0
			*
			* Increment the Receipts next nubmer
			*
			* @return void
		*/
		public function increment_next_number()
		{
			// Update next Receipts number in settings
			$FY = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_sale_return_number_for_cspl');
				}elseif($selected_company == 2){
                $this->db->where('name', 'next_sale_return_number_for_cff');
				}elseif($selected_company == 3){
                $this->db->where('name', 'next_sale_return_number_for_cbu');
				}elseif($selected_company == 4){
                $this->db->where('name', 'next_sale_return_number_for_cbupl');
			}
			$this->db->set('value', 'value+1', false);
			$this->db->WHERE('FY', $FY);
			$this->db->update(db_prefix() . 'options');
		}
		
		
	}
