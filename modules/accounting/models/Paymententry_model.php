<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paymententry_model extends App_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function generateNextVoucherIDNew($selected_date = '', $plant_id = '', $passage_from = ''){
    if(empty($selected_date)){
      $selected_date = date('Y-m-d');
    }
    
    if(empty($plant_id)){
      $plant_id = $this->session->userdata('root_company');
    }
    
    // Extract date components
    $date_parts = explode('-', $selected_date);
    $year = substr($date_parts[0], 2);
    $month = $date_parts[1];
    $day = $date_parts[2];
    
    $plant_id_formatted = str_pad($plant_id, 2, '0', STR_PAD_LEFT);
    
		switch (strtoupper($passage_from)) {
			case 'JOURNAL':
				$prefix = 'J';
				break;
			case 'RECEIPTS':
				$prefix = 'R';
				break;
			case 'PAYMENTS':
				$prefix = 'P';
				break;
			default:
				$prefix = 'C';
				break;
		}
    
    // Build base: J0126040300001 or C0126040300001
    $voucher_base = $prefix . $plant_id_formatted . $year . $month . $day;
    
    $sql = "SELECT VoucherID 
            FROM " . db_prefix() . "accountledgerPending 
            WHERE PlantID = " . (int)$plant_id . " 
            AND PassedFrom = '" . $this->db->escape_str(strtoupper($passage_from)) . "' 
            AND DATE(Transdate) = '" . $this->db->escape_str($selected_date) . "' 
            ORDER BY VoucherID DESC 
            LIMIT 1";
    
    $query = $this->db->query($sql);
    $sequence_number = 1;
    
    if($query->num_rows() > 0){
        $last_voucher = $query->row();
        $last_voucher_id = $last_voucher->VoucherID;
        
        // Last 3 characters = sequence number
        $last_sequence_str = substr($last_voucher_id, -3);
        $last_sequence = (int)$last_sequence_str;
        $sequence_number = $last_sequence + 1;
    }
    
    $sequence_formatted = str_pad($sequence_number, 3, '0', STR_PAD_LEFT);
    $new_voucher_number = $voucher_base . $sequence_formatted;
    
    return $new_voucher_number;
	}
	
	public function add_payment_entry($data){
		$payment_entry = json_decode($data['payment_entry']);
		unset($data['payment_entry']);
		$data['payment_entry'] = to_sql_date($data['payment_entry']);
		$fy = $this->session->userdata('finacial_year');
		$selected_company = $this->session->userdata('root_company');
		$payment_date = to_sql_date($data['payment_date'])." ".date('H:i:s');
		$date= to_sql_date($data['payment_date']);
		$month = substr($payment_date,5,2);
		$get_result_to_cur_date = $this->get_result_to_cur_date_payments($date);
		$PassedFrom = "PAYMENTS";
		$LastUniqueID = $this->generateNextVoucherIDNew($date, $selected_company, $PassedFrom);
		
		// if(empty($get_result_to_cur_date)){
		// 	if($selected_company == 1){
		// 		$new_tax_transactionNumber = get_option('next_payment_number_for_cspl');
		// 		}elseif($selected_company == 2){
		// 		$new_tax_transactionNumber = get_option('next_payment_number_for_cff');
		// 		}elseif($selected_company == 3){
		// 		$new_tax_transactionNumber = get_option('next_payment_number_for_cbu');
		// 	}
			$new_voucher_number = $this->generateNextVoucherIDNew($date, $selected_company, $PassedFrom);
		// }else{
		// 	$count = count($get_result_to_cur_date);
		// 	$last_index = $count - 1;
		// 	$new_voucher_number = $get_result_to_cur_date[$last_index]['VoucherID'];
			
		// 	$incNo = (int) $new_voucher_number - 1;
		// 	$sql = 'UPDATE tblaccountledgerPending SET VoucherID = abs(VoucherID) + 1 where abs(VoucherID) > "'.$incNo.'" AND PassedFrom = "PAYMENTS" AND FY = "'.$fy.'" AND PlantID = '.$selected_company;
		// 	$this->db->query($sql);
		// 	if ($this->db->affected_rows() > 0) {
		// 		$sql = 'UPDATE tblaccountledger SET VoucherID = abs(VoucherID) + 1 where abs(VoucherID) > "'.$incNo.'" AND PassedFrom = "PAYMENTS" AND FY = "'.$fy.'" AND PlantID = '.$selected_company;
		// 		$this->db->query($sql);
		// 		$this->increment_next_payment_number();
		// 		$sql2 = 'UPDATE tblReconsileMaster SET TransID = abs(TransID) + 1 where abs(TransID) > "'.$incNo.'" AND PassedFrom = "PAYMENT"';
		// 		$this->db->query($sql2);
		// 	}
		// }            
		$i = 1;
		foreach ($payment_entry as $key => $value) {
			if($value[0] != ''){
				// Insert Ledger Entry
				$credit_data = array(
					"PlantID" =>$selected_company,
					"Transdate" =>$payment_date,
					"TransDate2" =>$payment_date,
					"VoucherID" =>$new_voucher_number,
					"AccountID" =>$value[0],
					"EffectOn" =>$data['ganeral_account'],
					"TType" =>"D",
					"Against"=>$value[3],
					"BillNo"=>$value[4],
					"Amount" =>$value[6],
					"Narration" =>$value[7],
					"PassedFrom" =>"PAYMENTS",
					"Status"=>"N",
					"OrdinalNo" =>$i,
					"UserID" =>$this->session->userdata('username'),
					"FY" =>$fy,
					"UniquID" =>$LastUniqueID,
				); 
				$this->db->insert(db_prefix().'accountledgerPending', $credit_data);
				
				$debit_data = array(
					"PlantID" =>$selected_company,
					"Transdate" =>$payment_date,
					"TransDate2" =>$payment_date,
					"VoucherID" =>$new_voucher_number,
					"AccountID" =>$data['ganeral_account'],
					"EffectOn" =>$value[0],
					"TType" =>"C",
					"Against"=>$value[3],
					"BillNo"=>$value[4],
					"Amount" =>$value[6],
					"Narration" =>$value[7],
					"PassedFrom" =>"PAYMENTS",
					"Status"=>"N",
					"OrdinalNo" =>$i,
					"UserID" =>$this->session->userdata('username'),
					"FY" =>$fy,
					"UniquID" =>$LastUniqueID,
				);
				$this->db->insert(db_prefix().'accountledgerPending', $debit_data);
				$i++;					 
			}
			
			if(($value[3]=="AGAINST" || $value[3]=="Against") && $value[0] != '' && $value[4] != '')
			{  				
				$this->db->from(db_prefix() . 'ReconsileMaster');
				$this->db->where('EffectOn', $value[4]);
				$this->db->where('TType', 'DR');      
				$info = $this->db->get()->row();      
				$billid = $info->BillID;    
				
				$this->db->select('*');
				$this->db->from(db_prefix() . 'ReconsileMaster');
				$this->db->group_start();  // Start grouping conditions
				$this->db->where('EffectOn', $value[4]);
				$this->db->or_where('TransID', $value[4]);
				$this->db->group_end();    // End grouping conditions
				$invoiceData = $this->db->get()->result_array();                
				
				$this->db->select('*'); 
				$this->db->from(db_prefix() . 'ReconsileMaster'); 
				$this->db->where('TransID', $value[4]);
				$this->db->where('TType', 'CR');
				$ctypeamt = $this->db->get()->row(); 
				$amt = $ctypeamt->Amount;
				
				$typeCAmount = null;
				$typeDAmount = null;      
				foreach ($invoiceData as $row) {
					if ($row['TType'] == 'CR') {
						$typeCAmount += $row['Amount'];
						} elseif ($row['TType'] == 'DR') {
						$typeDAmount += $row['Amount'];
					}
				}                                     
				
				if(!empty($info))
				{
					if ($typeCAmount !== null && $typeDAmount !== null && $invoiceData) {               
						$difference = abs($typeCAmount - $typeDAmount);
						if($difference && $difference > 0)
						{
							if($value[6] == $difference)
							{  $amount = $difference;  }
							else if($value[6] > $difference) 
							{ $amount = $difference;}
							else if($value[6] < $difference) 
							{ $amount = $value[6]; }                           
						}
						else if($difference == 0)
						{
							$amount = $value[6];
						} 
						
						$reconciliation = array(
							"TransID"=>$new_voucher_number,
							"EffectOn"=>$value[4],
							"TransDate"=>$payment_date,
							"AccountID"=>$value[0],
							"Amount"=>$amount,
							"TType"=>"DR",
							"Status"=>"Y",
							"PassedFrom"=>"PAYMENT",
							"UserID"=>$this->session->userdata('username'),
						);
						$insertdata = $this->db->insert(db_prefix().'ReconsileMaster', $reconciliation);
						if($insertdata && ($value[6] == $difference || $value[6] > $difference))
						{
							// echo "ok";die;	
							$updatestatus = array(
								"Status"=>"Y"
							);                           
							$this->db->where('TType', 'CR');
							$this->db->where('TransID', $value[4]);
							$this->db->update(db_prefix().'ReconsileMaster', $updatestatus);
							// echo "ok";die;
						}                                              
					}
				}else if(empty($info))
				{                     
					if($ctypeamt && $amt)
					{
						if($value[6] > $amt)
						{
							$diff = $amt;
						}  
						else if ($value[6] < $amt)    
						{
							$diff = $value[6];
						}   
						else if($value[6] == $amt)   
						{
							$diff = $amt;
						}
					}
					else
					{                       
						$diff = $value[6];
					}					
					$reconciliation = array(
						"TransID"=>$new_voucher_number,
						"EffectOn"=>$value[4],
						"TransDate"=>$payment_date,
						"AccountID"=>$value[0],
						"Amount"=>$diff,
						"TType"=>"DR",
						"Status"=>"Y",
						"PassedFrom"=>"PAYMENT",
						"UserID"=>$this->session->userdata('username'),
					);
					$insertdetails = $this->db->insert(db_prefix().'ReconsileMaster', $reconciliation);  
					if($insertdetails && ($value[6] == $amt || $value[6] > $amt))
					{
						$updatestatus = array(
						"Status"=>"Y"
						);                           
						$this->db->where('TType', 'CR');
						$this->db->where('TransID', $value[4]);
						$this->db->update(db_prefix().'ReconsileMaster', $updatestatus);
					}  
				}              
			}
		}
		// if(empty($get_result_to_cur_date)){
		// 	$this->increment_next_payment_number();
		// }
		return true;			
	}

	public function update_payments_entry($data,$id){
		$payments_entry = json_decode($data['payment_entry']);
		
		unset($data['payment_entry']);
		$fy = $this->session->userdata('finacial_year');
		$selected_company = $this->session->userdata('root_company');
		$payment_date = to_sql_date($data['payment_date1'])." ".date('H:i:s');
		
		$UniqueID = $data['UniqueID'];
		//$payments_details = $this->get_payment_entry_details($id);
		$payments_details = $this->get_payment_entry_detailsNew($UniqueID);
		
		// Delete previous ledger details
		foreach ($payments_details as $key => $value) {
			$NewVoucherID = $value["VoucherID"];
			$ledger_audit = array(
			"PlantID"=>$value["PlantID"],
			"FY"=>$value["FY"],
			"Transdate"=>$value["Transdate"],
			"TransDate2"=>$value["TransDate2"],
			"VoucherID"=>$value["VoucherID"],
			"AccountID"=>$value["AccountID"],
			"TType"=>$value["TType"],
			"Amount"=>$value["Amount"],
			"Narration"=>$value["Narration"],
			"PassedFrom"=>$value["PassedFrom"],
			"OrdinalNo"=>$value["OrdinalNo"],
			"UserID"=>$value["UserID"],
			"Lupdate"=>date('Y-m-d H:i:s'),
			"UserID2"=>$this->session->userdata('username')
			);
			$this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
		}
		
		$this->db->where('PlantID', $selected_company);
		$this->db->LIKE('FY', $fy);
		$this->db->LIKE('PassedFrom', "PAYMENTS");
		$this->db->where('UniquID', $UniqueID);
		$this->db->delete(db_prefix() . 'accountledgerPending');
		// Delete Main Table
		$this->db->where('PlantID', $selected_company);
		$this->db->LIKE('FY', $fy);
		$this->db->LIKE('PassedFrom', "PAYMENTS");
		$this->db->where('UniquID', $UniqueID);
		$this->db->delete(db_prefix() . 'accountledger');
		// END Delete previous ledger details
		
		$this->db->LIKE('PassedFrom', "PAYMENT");
		$this->db->where('TransID', $id);
		$this->db->where('TType', 'DR');
		$this->db->delete(db_prefix() . 'ReconsileMaster');
		// END Delete previous Reconsile Record details
		
		$i = 1;
		foreach ($payments_entry as $key => $value) {
			if($value[0] != ''){
				$credit_data = array(
				"PlantID" =>$selected_company,
				"Transdate" =>$payment_date,
				"TransDate2" =>$payment_date,
				"VoucherID" =>$NewVoucherID,
				"AccountID" =>$value[0],
				"EffectOn" =>$data['ganeral_account'],
				"TType" =>"D",
				"Against"=>$value[3],
				"BillNo"=>$value[4],
				"Amount" =>$value[6],
				"Narration" =>$value[7],
				"PassedFrom" =>"PAYMENTS",
				"OrdinalNo" =>$i,
				"UserID" =>$this->session->userdata('username'),
				"FY" =>$fy,
				"UniquID" =>$UniqueID,
				);
				$this->db->insert(db_prefix().'accountledgerPending', $credit_data);
				$i++;
				
				$debit_data = array(
				"PlantID" =>$selected_company,
				"Transdate" =>$payment_date,
				"TransDate2" =>$payment_date,
				"VoucherID" =>$NewVoucherID,
				"AccountID" =>$data['ganeral_account'],
				"EffectOn" =>$value[0],
				"TType" =>"C",
				"Against"=>$value[3],
				"BillNo"=>$value[4],
				"Amount" =>$value[6],
				"Narration" =>$value[7],
				"PassedFrom" =>"PAYMENTS",
				"OrdinalNo" =>$i,
				"UserID" =>$this->session->userdata('username'),
				"FY" =>$fy,
				"UniquID" =>$UniqueID,
				);
				$this->db->insert(db_prefix().'accountledgerPending', $debit_data);
				$i++;
			}
			
			if(($value[3]=="AGAINST" || $value[3]=="Against") && $value[0] != '' && $value[4] != '')
			{           
				
				$this->db->from(db_prefix() . 'ReconsileMaster');
				$this->db->where('EffectOn', $value[3]);
				$this->db->where('TType', 'DR');      
				$info = $this->db->get()->row();      
				$billid = $info->BillID;    
				
				$this->db->select('*');
				$this->db->from(db_prefix() . 'ReconsileMaster');
				$this->db->group_start();  // Start grouping conditions
				$this->db->where('EffectOn', $value[4]);
				$this->db->or_where('TransID', $value[4]);
				$this->db->group_end();    // End grouping conditions
				$invoiceData = $this->db->get()->result_array();                
				
				$this->db->select('*'); 
				$this->db->from(db_prefix() . 'ReconsileMaster'); 
				$this->db->where('TransID', $value[4]);
				$this->db->where('TType', 'CR');
				$ctypeamt = $this->db->get()->row(); 
				$amt = $ctypeamt->Amount;
				
				$typeCAmount = null;
				$typeDAmount = null;      
				foreach ($invoiceData as $row) {
					if ($row['TType'] == 'CR') {
						$typeCAmount += $row['Amount'];
						} elseif ($row['TType'] == 'DR') {
						$typeDAmount += $row['Amount'];
					}
				}                                     
				
				if(!empty($info))
				{
					if ($typeCAmount !== null && $typeDAmount !== null && $invoiceData) {               
						$difference = abs($typeCAmount - $typeDAmount);
						if($difference && $difference > 0)
						{
							if($value[6] == $difference)
							{  $amount = $difference;  }
							else if($value[6] > $difference) 
							{ $amount = $difference;}
							else if($value[6] < $difference) 
							{ $amount = $value[6]; }                           
						}
						else if($difference == 0)
						{
							$amount = $value[5];
						} 
						
						$reconciliation = array(
						"TransID"=>$id,
						"EffectOn"=>$value[4],
						"TransDate"=>date('Y-m-d H:i:s'),
						"AccountID"=>$value[0],
						"Amount"=>$amount,
						"TType"=>"DR",
						"Status"=>"Y",
						"PassedFrom"=>"PAYMENT",
						"UserID"=>$this->session->userdata('username'),
						);
						
						$insertdata = $this->db->insert(db_prefix().'ReconsileMaster', $reconciliation);
						// echo $difference;die;
						if($insertdata)
						{
							$this->db->select('*');
							$this->db->from(db_prefix() . 'ReconsileMaster');
							$this->db->group_start();  // Start grouping conditions
							$this->db->where('EffectOn', $value[4]);
							$this->db->or_where('TransID', $value[4]);
							$this->db->group_end();    // End grouping conditions
							$invoiceData = $this->db->get()->result_array();
							$typeCAmount = null;
							$typeDAmount = null;      
							foreach ($invoiceData as $row) {
								if ($row['TType'] == 'CR') {
									$typeCAmount += $row['Amount'];
									} elseif ($row['TType'] == 'DR') {
									$typeDAmount += $row['Amount'];
								}
							} 
							$difference = abs($typeCAmount - $typeDAmount);
							if($value[6] == $difference || $value[6] > $difference)
							{
								
								// echo "ok";die;	
								$updatestatus = array(
								"Status"=>"Y"
								);                           
								$this->db->where('TType', 'CR');
								$this->db->where('TransID', $value[3]);
								$this->db->update(db_prefix().'ReconsileMaster', $updatestatus);
								// echo "ok";die;
							}
						}                                              
					}
				}
				else if(empty($info))
				{                     
					if($ctypeamt && $amt)
					{
						if($value[6] > $amt)
						{
							$diff = $amt;
						}  
						else if ($value[6] < $amt)    
						{
							$diff = $value[6];
						}   
						else if($value[6] == $amt)   
						{
							$diff = $amt;
						}
					}
					else
					{                       
						$diff = $value[6];
					}           
					
					$reconciliation = array(
					"TransID"=>$id,
					"EffectOn"=>$value[4],
					"TransDate"=>date('Y-m-d H:i:s'),
					"AccountID"=>$value[0],
					"Amount"=>$diff,
					"TType"=>"DR",
					"Status"=>"Y",
					"PassedFrom"=>"PAYMENT",
					"UserID"=>$this->session->userdata('username'),
					);
					$insertdetails = $this->db->insert(db_prefix().'ReconsileMaster', $reconciliation);  
					if($insertdetails && ($value[6] == $amt || $value[6] > $amt))
					{
						$updatestatus = array(
						"Status"=>"Y"
						);                           
						$this->db->where('TType', 'CR');
						$this->db->where('TransID', $value[4]);
						$this->db->update(db_prefix().'ReconsileMaster', $updatestatus);
					}  
				}              
			}
		}
		return true;
	}

	public function get_payments_entry($id){
		$fy = $this->session->userdata('finacial_year');
		$selected_company = $this->session->userdata('root_company');
		$this->db->where('PlantID', $selected_company);
		$this->db->LIKE('FY', $fy);
		$this->db->where('VoucherID', $id);
		$this->db->LIKE('PassedFrom', "PAYMENTS");
		$payment_entry = $this->db->get(db_prefix() . 'accountledgerPending')->row();
		
		$this->db->where('PlantID', $selected_company);
		$this->db->LIKE('FY', $fy);
		$this->db->where('VoucherID', $id);
		$this->db->LIKE('PassedFrom', "PAYMENTS");
		$this->db->order_by('OrdinalNo', "ASC");
		$payment_data = $this->db->get(db_prefix() . 'accountledgerPending')->result_array();
		
		
		// echo "<pre>";
		// print_r($creditedamt);
		// die;
		
		$data_details =[];
		$total_amt = 0;
		$debamt = 0;
		foreach ($payment_data as $key => $value) {
			
			$amt = '';
			
			if($value['TType']=="D"){
				$amt = $value['Amount'];
				$amt = floatval($amt);
				$dr_cr = "D";
				$total_amt = $total_amt + $amt;
				
				// Get Pending Amount
				$this->db->from(db_prefix() . 'ReconsileMaster');
				$this->db->where('TransID', $value['BillNo']);
				$this->db->where('TType', 'CR');  
				$this->db->where('PassedFrom', 'PURCHASE');  
				$this->db->where('AccountID', $value['AccountID']);  
				$Purch = $this->db->get()->row();
				$creditedamt = $Purch->Amount;
				
				$this->db->from(db_prefix() . 'accountledger');
				$this->db->where('BillNo', $value['BillNo']);
				$this->db->where('TType', 'D');  
				$this->db->where('AccountID', $value['AccountID']);  
				$details = $this->db->get()->result_array();       
				$DebitAmount = array_sum(array_column($details, 'Amount'));
				// echo "<pre>";print_r($details);die;
				$this->db->from(db_prefix() . 'accountledger');
				$this->db->where('BillNo', $value['BillNo']);
				$this->db->where('TType', 'C');  
				$this->db->where('AccountID', $value['AccountID']);  
				$details = $this->db->get()->result_array();       
				$CreditAmount = array_sum(array_column($details, 'Amount'));
				
				
				
				$diff = $DebitAmount - $CreditAmount;
				$total_pending_amt = $creditedamt - $diff;
				
				$total_pending_amt = $total_pending_amt + $value['Amount'];
				
				//  closing balance for this account
				$closingBalance = $this->getclosing_balance($value['AccountID']);
				
				$data_details[] = [
				"AccountID" => strtoupper($value['AccountID']),
				"company" => strtoupper($value['AccountID']),
				"ClosingBalance" => $closingBalance,
				"against"=>ucfirst($value['Against']),
				"bill"=>strtoupper($value['BillNo']),
				"pendingAmt"=>$total_pending_amt,
				"debit" => $amt,
				"Status" => $value['Status'],
				"description" => $value['Narration']];
				}else{
				$amt = $value['Amount'];
				$deb_act = strtoupper($value['AccountID']);
				$debamt = $debamt + $amt;
			}
			
			$debamt = floatval($debamt);
			
		}
		if(count($data_details) < 10){
			
		}
		$payment_entry->details = $data_details;
		$payment_entry->damt = $debamt;
		$payment_entry->d_act = $deb_act;
		/*echo "<pre>";
			print_r($data_details);
		die;*/
		
		return $payment_entry;
	}

	public function get_data_account_to_select_for_payment() {
		$accounts = $this->get_accounts_for_payment();
		$staff_list = $this->get_staff_for_payment();
		
		$acc_enable_account_numbers = get_option('acc_enable_account_numbers');
		$acc_show_account_numbers = get_option('acc_show_account_numbers');
		$list_accounts = [];
		
		
		foreach ($accounts as $key => $account) {
			$note = [];
			$note['id'] = strtoupper($account['AccountID']);
			$note['label'] = $account['company'].' - '.$account['AccountID'].'-'.$account['StationName'];
			
			$list_accounts[] = $note;
		}
		
		foreach ($staff_list as $key1 => $account1) {
			$note = [];
			$note['id'] = strtoupper($account1['AccountID']);
			$note['label'] = $account1['firstname']." ".$account1['lastname'].' - '.$account1['AccountID'].'-'.$account1["stationName"];
			
			$list_accounts[] = $note;
		}
		return $list_accounts;
		
	}

	public function get_data_ganeral_account_to_select(){
		$selected_company = $this->session->userdata('root_company');
		$subgroup = array('1000001');
		$this->db->where('PlantID', $selected_company);
		$this->db->where_in('SubActGroupID',$subgroup);
		$this->db->order_by('company', 'ASC');
		$accounts = $this->db->get(db_prefix() . 'clients')->result_array();
		return $accounts;
	}

	public function get_result_to_cur_date_payments($payment_date){
		$fy = $this->session->userdata('finacial_year');
		$selected_company = $this->session->userdata('root_company');
		
		$fy_ne = $fy + 1;
		$las_date_fy = '20'.$fy_ne.'-03-31 23:59:59';
		$sql = 'SELECT * FROM tblaccountledgerPending WHERE PlantID = '.$selected_company.' AND PassedFrom LIKE "PAYMENTS" AND FY LIKE "'.$fy.'" AND Transdate BETWEEN "'.$payment_date.' H:i:s" AND "'.$las_date_fy.'" GROUP BY VoucherID ORDER BY abs(tblaccountledgerPending.VoucherID) DESC ';
		$staff_data = $this->db->query($sql)->result_array();
		return $staff_data;
	}

	public function get_payment_entry_detailsNew($id){
		$fy = $this->session->userdata('finacial_year');
		$selected_company = $this->session->userdata('root_company');
		$this->db->where('PlantID', $selected_company);
		$this->db->LIKE('FY', $fy);
		$this->db->where('UniquID', $id);
		$this->db->LIKE('PassedFrom', "PAYMENTS");
		$journal_data = $this->db->get(db_prefix() . 'accountledgerPending')->result_array();
		return $journal_data;
	}
	
	public function getclosing_balance($AccountID){
		$selected_company = $this->session->userdata('root_company');
		$FY = $this->session->userdata('finacial_year');
		
		$Obal = 0;
		
		$sql = '';
		$sql .= 'SELECT SUM(Amount) as dramt_sum,tblaccountledger.AccountID,Transdate FROM `tblaccountledger`';
		$sql .= ' WHERE  AccountID = "'.$AccountID.'" AND tblaccountledger.PlantID = '.$selected_company.' AND tblaccountledger.FY = "'.$FY.'" AND tblaccountledger.TType = "D"';
		$result1 = $this->db->query($sql)->row();
		
		$sql2 = '';
		$sql2 .= 'SELECT SUM(Amount) as cramt_sum,tblaccountledger.AccountID,Transdate FROM `tblaccountledger`';
		$sql2 .= ' WHERE  AccountID = "'.$AccountID.'" AND tblaccountledger.PlantID = '.$selected_company.' AND tblaccountledger.FY = "'.$FY.'" AND tblaccountledger.TType = "C"';
		$result2 = $this->db->query($sql2)->row();
		
		$sql3 = '';
		$sql3 .= 'SELECT BAL1 FROM `tblaccountbalances`';
		$sql3 .= ' WHERE  AccountID = "'.$AccountID.'" AND tblaccountbalances.PlantID = '.$selected_company.' AND tblaccountbalances.FY = "'.$FY.'"';
		$result3 = $this->db->query($sql3)->row();
		if(empty($result3)){			
			}else{
			$Obal = $result3->BAL1;
		}
		$closing_balance = $Obal + $result1->dramt_sum - $result2->cramt_sum;
		return $closing_balance;
	}	

	public function get_accounts_for_payment($id = '', $where = []){
		$subgroup = array('1000001');
		if ($id) {
			
			$selected_company = $this->session->userdata('root_company');
			$this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountgroupssub.SubActGroupName');
			$this->db->join(db_prefix() . 'accountgroupssub', db_prefix() . 'accountgroupssub.SubActGroupID=' . db_prefix() . 'clients.SubActGroupID');
			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
			$this->db->where('AccountID', $id);
			$this->db->where_not_in('SubActGroupID',$subgroup);
			return $this->db->get(db_prefix() . 'clients')->row();
		}
		
		$acc_show_account_numbers = get_option('acc_show_account_numbers');
		
		$selected_company = $this->session->userdata('root_company');
		$this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountgroupssub.SubActGroupName');
		$this->db->join(db_prefix() . 'accountgroupssub', db_prefix() . 'accountgroupssub.SubActGroupID=' . db_prefix() . 'clients.SubActGroupID');
		$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
		$this->db->where_not_in('tblclients.SubActGroupID',$subgroup);
		$accounts = $this->db->get(db_prefix() . 'clients')->result_array();
		
		foreach ($accounts as $key => $value) {
			
			if($acc_show_account_numbers == 1 && $value['number'] != ''){
				$accounts[$key]['name'] = $value['name'] != '' ? $value['number'].' - '.$value['name'] : $value['number'].' - '._l($value['key_name']);
				}else{
				$accounts[$key]['name'] = $value['name'] != '' ? $value['name'] : _l($value['key_name']);
			}
		}
		return $accounts;
	}
	
	public function get_staff_for_payment($id = '', $where = []){
		$acc_show_account_numbers = get_option('acc_show_account_numbers');
		
		$selected_company = $this->session->userdata('root_company');
		$this->db->select(db_prefix() . 'staff.*');
		$regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
		//$this->db->where('tblstaff.staff_comp REGEXP',$regExp);
		$this->db->where('tblstaff.PlantID ',$selected_company);
		//$this->db->where(db_prefix() . 'staff.active', 1);
		$accounts = $this->db->get(db_prefix() . 'staff')->result_array();
		
		foreach ($accounts as $key => $value) {
			
			if($acc_show_account_numbers == 1 && $value['number'] != ''){
				$accounts[$key]['name'] = $value['name'] != '' ? $value['number'].' - '.$value['name'] : $value['number'].' - '._l($value['key_name']);
				}else{
				$accounts[$key]['name'] = $value['name'] != '' ? $value['name'] : _l($value['key_name']);
			}
		}
		return $accounts;
	}
}
