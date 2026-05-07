<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class GodownMaster extends AdminController
	{
		private $not_importable_fields = ['id'];
		public function __construct()
		{
			parent::__construct();
			$this->load->model('godown_model');
			$this->load->model('accounts_master_model');
		}
		
		/* Add / edit Godown  */
		public function index()
		{
			if (!has_permission_new('GodownMaster', '', 'view')) {
				access_denied('Invoice Items');
			}
			
			$data['title'] = "Add/Edit Godown";
			$data['TableData'] = $this->godown_model->GetTableData();
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$this->load->view('admin/GodownMaster/AddEditGodown', $data);
		}
		
		/* Save New Godown / ajax */
		public function SaveAccount()
		{
			$selected_company = $this->session->userdata('root_company');
			$data = array(
            'PlantID'=>$selected_company,
            'AccountID'=>strtoupper($this->input->post('AccountID')),
            'AccountName'=>$this->input->post('AccountName'),
            'UserID'=>$this->session->userdata('username'),
            'Transdate'=>date('Y-m-d H:i:s'),
			);
			$AccountDetails  = $this->godown_model->SaveAccount($data);
			echo json_encode($AccountDetails);
		}
		
		public function GetAccountDetails()
		{
			
			$AccountID = $this->input->post('AccountID');
			$account_data = $this->godown_model->GetAccountDetails($AccountID);
			echo json_encode($account_data);
		}
		
		public function exportGodown()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				$TableData = $this->godown_model->GetTableData();
				$selected_company_details = $this->accounts_master_model->get_company_detail();
				
				$writer = new XLSXWriter();
				$border = array( 'border'=>'left,right,top,bottom');
				$border_style = array( 'border-style'=>'solid');
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 3);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 3);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$set_col_tk = [];
				$set_col_tk["AccountID"] =  'AccountID';
				$set_col_tk["AccountName"] =  'AccountName';
				$set_col_tk["CreatedDate"] =  'CreatedDate';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				foreach ($TableData as $key => $value) {
					$list_add = [];
                   	
					$list_add[] = $value['AccountID'];
					$list_add[] = $value['AccountName'];
					$list_add[] = substr(_d($value["Transdate"]),0,10);
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'GodownList.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* Update Exiting SubGroup / ajax */
		public function UpdateAccount()
		{
			$data = array(
            'AccountName'=>$this->input->post('AccountName'),
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
			);
			$AccountID = $this->input->post('AccountID');
			$AccountDetails                    = $this->godown_model->UpdateAccount($data,$AccountID);
			echo json_encode($AccountDetails);
		}
		
		public function DeleteAccount()
		{
			
			$AccountID = $this->input->post('AccountID');
			$AccountDetails                    = $this->godown_model->DeleteAccount($AccountID);
			echo json_encode($AccountDetails);
		}
		
		public function GetList()
		{
			$AccountID = $this->input->post('AccountID');
			$AccountList                    = $this->godown_model->GetTableData();
			$html = '';
			foreach ($AccountList as $key => $value) {
				$html .= '<tr>';
				$html .= '<td>'.$value["AccountID"].'</td>';
				$html .= '<td>'.$value["AccountName"].'</td>';
				$html .= '</tr>';
			} 
			echo json_encode($html);
		}
		
		public function getAccountSerch(){
			// POST data
			$postData = $this->input->post();
			// Get data
			$data = $this->godown_model->getAccountSerch($postData);
			echo json_encode($data);
		}
		
		/* Add / edit Transer  */
		public function StockTransfer()
		{
			if (!has_permission_new('StockTransfer', '', 'view')) {
				access_denied('Invoice Items');
			}
			
			$data['title'] = "Add/Edit Stock Tranfer";
			$data['TableData'] = $this->godown_model->GetTableData();
			$data['TransData'] = $this->godown_model->GetTransData();
			$data['Itemdata'] = $this->godown_model->GetItemList();
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$this->load->view('admin/GodownMaster/stocktransfer', $data);
		}
		
		
		public function itemlist(){
			// POST data
			$postData = $this->input->post();
			// Get data
			$data = $this->godown_model->itemlist($postData);
			//print_r($postData); exit();
			echo json_encode($data);
		}
		public function GetItemDetails()
		{
			// POST data
			$postData = $this->input->post();
			
			$ItemID = $this->input->post('ItemID');
			$FromID = $this->input->post('FromID');
			// Get data
			$data = $this->godown_model->GetItemDetails($ItemID,$FromID);
			echo json_encode($data);
		}
		
		public function GetTransDetails()
		{
			// POST data
			$postData = $this->input->post();
			$TransID = $this->input->post('TransID');
			// Get data
			$data = $this->godown_model->GetTransDetails($TransID);
			echo json_encode($data);
		}
		
		public function SaveTransfer()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			if($selected_company == 1){
				$new_TransNumber = get_option('next_trns_number_for_cspl');
				}elseif($selected_company == 2){
				$new_TransNumber = get_option('next_trns_number_for_cff');
				}elseif($selected_company == 3){
				$new_TransNumber = get_option('next_trns_number_for_cbu');
				}elseif($selected_company == 4){
				$new_TransNumber = get_option('next_trns_number_for_cbupl');
			}
            
			$TransNumber = 'TRS'.$FY.str_pad($new_TransNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
			$Transdate = to_sql_date($this->input->post('Transdate'))." ".date('H:i:s');
			$TrnsFrom = $this->input->post('TrnsFrom');
			$TrnsTo = $this->input->post('TrnsTo');
			$ItemCount = $this->input->post('ItemCount');
			$ItemCountN = $ItemCount - 1;
			$masterData = array(
            'PlantID'=> $selected_company,
            'FY'=> $FY,
            'TransID'=> $TransNumber,
            'Transdate'=> date('Y-m-d H:i:s'),
            'Transdate2'=> $Transdate,
            'TransFrom'=> $TrnsFrom,
            'TransTo'=> $TrnsTo,
            'UserID'=> $_SESSION['username'],
			);
			$this->db->insert(db_prefix() . 'TransferMaster',$masterData);
			if($this->db->affected_rows() > 0){
				
				$this->godown_model->increment_next_number();
				
				$ItemSerializedArr = $this->input->post('ItemSerializedArr');
				$ItemArray = json_decode($ItemSerializedArr, true);
				$selectedArray = array();
				for($i=0; $i<$ItemCountN; $i++) {
					$ItemID = $ItemArray[$i][0];
					array_push($selectedArray,$ItemID);
				}
				$OrdNo = 1;
				for($k=0; $k<$ItemCountN; $k++) {
					$ItemID = $ItemArray[$k][0];
					$ItemName = $ItemArray[$k][1];
					$Pack = $ItemArray[$k][2];
					$Unit = $ItemArray[$k][3];
					$qtyCases = $ItemArray[$k][4];
					// $Qty = $qtyCases * $Pack;
					$Qty = $qtyCases;
					
                    $HistoryArrayOut = array(
					'PlantID' =>$selected_company,
					'FY' =>$FY,
					'cnfid' =>'1',
					'OrderID' =>$TransNumber,
					'BillID' =>$TransNumber,
					'TransID' =>$TransNumber,
					'TransDate' =>date('Y-m-d H:i:s'),
					'TransDate2' =>$Transdate,
					'TType' =>'T',
					'TType2' =>'Out',
					'AccountID' =>$TrnsFrom,
					'GodownID' =>$TrnsFrom,
					'ItemID' =>$ItemID,
					'SaleRate' =>null,
					'BasicRate' =>null,
					'SuppliedIn' =>'CS',
					'OrderQty' =>$Qty,
					'BilledQty' =>$Qty,
					'CaseQty' =>$Pack,
					'OrderAmt' =>null,
					'ChallanAmt' =>null,
					'NetOrderAmt' =>null,
					'NetChallanAmt' =>null,
					'Ordinalno' =>$OrdNo,
					'UserID' =>$_SESSION['username'],
                    );
					//print_r($HistoryArrayOut);
					$this->db->insert(db_prefix() . 'history',$HistoryArrayOut);
					$OrdNo++;
                    $HistoryArrayIn = array(
					'PlantID' =>$selected_company,
					'FY' =>$FY,
					'cnfid' =>'1',
					'OrderID' =>$TransNumber,
					'BillID' =>$TransNumber,
					'TransID' =>$TransNumber,
					'TransDate' =>date('Y-m-d H:i:s'),
					'TransDate2' =>$Transdate,
					'TType' =>'T',
					'TType2' =>'In',
					'AccountID' =>$TrnsTo,
					'GodownID' =>$TrnsTo,
					'ItemID' =>$ItemID,
					'SaleRate' =>null,
					'BasicRate' =>null,
					'SuppliedIn' =>'CS',
					'OrderQty' =>$Qty,
					'BilledQty' =>$Qty,
					'CaseQty' =>$Pack,
					'OrderAmt' =>null,
					'ChallanAmt' =>null,
					'NetOrderAmt' =>null,
					'NetChallanAmt' =>null,
					'Ordinalno' =>$OrdNo,
					'UserID' =>$_SESSION['username'],
                    );
                    //print_r($HistoryArrayIn);
					$this->db->insert(db_prefix() . 'history',$HistoryArrayIn);
					$OrdNo++;
				}
				//die;
				if($selected_company == 1){
                    $next_TransNumber = get_option('next_trns_number_for_cspl');
					}elseif($selected_company == 2){
                    $next_TransNumber = get_option('next_trns_number_for_cff');
					}elseif($selected_company == 3){
                    $next_TransNumber = get_option('next_trns_number_for_cbu');
					}elseif($selected_company == 4){
                    $next_TransNumber = get_option('next_trns_number_for_cbupl');
				}
                $new_TransNumber = 'TRS'.$FY.str_pad($next_TransNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                echo json_encode($new_TransNumber);
                die;
			}
		}
		
		public function UpdateTransfer()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');  
			$TransID = $this->input->post('TransID');
			$Transdate = to_sql_date($this->input->post('Transdate'))." ".date('H:i:s');
			$TrnsFrom = $this->input->post('TrnsFrom');
			$TrnsTo = $this->input->post('TrnsTo');
			$ItemCount = $this->input->post('ItemCount');
			$ItemCountN = $ItemCount - 1;
			
			$OldTransDetails = $this->godown_model->GetOLDTransDetails($TransID);
			
			$masterData = array(
            
            'Transdate'=> date('Y-m-d H:i:s'),
            'Transdate2'=> $Transdate,
            'TransFrom'=> $TrnsFrom,
            'TransTo'=> $TrnsTo,
            'UserID2'=> $_SESSION['username'],
            'Lupdate'=>date('Y-m-d H:i:s'),
			);
			$this->db->where('PlantID', $selected_company);
			$this->db->where('FY', $FY);  
			$this->db->where('TransID', $TransID); 
			$this->db->update(db_prefix() . 'TransferMaster', $masterData);
			
            $OldTransFrom = $OldTransDetails->TransFrom;
            $OldTransTo = $OldTransDetails->TransTo;
            
            
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $FY);
            $this->db->where('TType', "T");
            $this->db->where('TransID', $TransID);
            $this->db->delete(db_prefix() . 'history');
			
            $ItemSerializedArr = $this->input->post('ItemSerializedArr');
            $ItemArray = json_decode($ItemSerializedArr, true);
            $selectedArray = array();
            for($i=0; $i<$ItemCountN; $i++) {
                $ItemID = $ItemArray[$i][0];
                array_push($selectedArray,$ItemID);
			}
            $OrdNo = 1;
            for($k=0; $k<$ItemCountN; $k++) {
                $ItemID = $ItemArray[$k][0];
                $ItemName = $ItemArray[$k][1];
                $Pack = $ItemArray[$k][2];
                $Unit = $ItemArray[$k][3];
                $qtyCases = $ItemArray[$k][4];
                // $Qty = $qtyCases * $Pack;
                $Qty = $qtyCases;
               
                //echo "<pre>";
				$HistoryArrayOut = array(
				'PlantID' =>$selected_company,
				'FY' =>$FY,
				'cnfid' =>'1',
				'OrderID' =>$TransID,
				'BillID' =>$TransID,
				'TransID' =>$TransID,
				'TransDate' =>date('Y-m-d H:i:s'),
				'TransDate2' =>$Transdate,
				'TType' =>'T',
				'TType2' =>'Out',
				'AccountID' =>$TrnsFrom,
				'GodownID' =>$TrnsFrom,
				'ItemID' =>$ItemID,
				'SaleRate' =>null,
				'BasicRate' =>null,
				'SuppliedIn' =>'CS',
				'OrderQty' =>$Qty,
				'BilledQty' =>$Qty,
				'CaseQty' =>$Pack,
				'OrderAmt' =>null,
				'ChallanAmt' =>null,
				'NetOrderAmt' =>null,
				'NetChallanAmt' =>null,
				'Ordinalno' =>$OrdNo,
				'UserID' =>$_SESSION['username'],
				);
                //print_r($HistoryArrayOut);
                $this->db->insert(db_prefix() . 'history',$HistoryArrayOut);
                $OrdNo++;
				$HistoryArrayIn = array(
				'PlantID' =>$selected_company,
				'FY' =>$FY,
				'cnfid' =>'1',
				'OrderID' =>$TransID,
				'BillID' =>$TransID,
				'TransID' =>$TransID,
				'TransDate' =>date('Y-m-d H:i:s'),
				'TransDate2' =>$Transdate,
				'TType' =>'T',
				'TType2' =>'In',
				'AccountID' =>$TrnsTo,
				'GodownID' =>$TrnsTo,
				'ItemID' =>$ItemID,
				'SaleRate' =>null,
				'BasicRate' =>null,
				'SuppliedIn' =>'CS',
				'OrderQty' =>$Qty,
				'BilledQty' =>$Qty,
				'CaseQty' =>$Pack,
				'OrderAmt' =>null,
				'ChallanAmt' =>null,
				'NetOrderAmt' =>null,
				'NetChallanAmt' =>null,
				'Ordinalno' =>$OrdNo,
				'UserID' =>$_SESSION['username'],
				);
				//print_r($HistoryArrayIn);
                $this->db->insert(db_prefix() . 'history',$HistoryArrayIn);
                $OrdNo++;
			}
            //die;
            if($selected_company == 1){
				$next_TransNumber = get_option('next_trns_number_for_cspl');
                }elseif($selected_company == 2){
				$next_TransNumber = get_option('next_trns_number_for_cff');
                }elseif($selected_company == 3){
				$next_TransNumber = get_option('next_trns_number_for_cbu');
                }elseif($selected_company == 4){
				$next_TransNumber = get_option('next_trns_number_for_cbupl');
			}
			$new_TransNumber = 'TRS'.$FY.$next_TransNumber;
			echo json_encode($new_TransNumber);
			die;
		}
		
		public function DeleteTransfer()
		{
			
			$TransID = $this->input->post('TransID');
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$OldTransDetails = $this->godown_model->GetOLDTransDetails($TransID);
			
			$masterData = array(
            'UserID2'=> $_SESSION['username'],
            'Lupdate'=>date('Y-m-d H:i:s'),
			);
			$this->db->where('PlantID', $selected_company);
			$this->db->where('FY', $FY);  
			$this->db->where('TransID', $TransID); 
			$this->db->update(db_prefix() . 'TransferMaster', $masterData);
			
            $OldTransFrom = $OldTransDetails->TransFrom;
            $OldTransTo = $OldTransDetails->TransTo;
            
            foreach($OldTransDetails->ItemS as $row ){
                // Qty minus to TransFrom Godown  
                $TrnsFromOLD = $row['AccountID'];
                $ItemIDOLD = $row['ItemID'];
                
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $FY);  
                $this->db->where('GodownID', $TrnsFromOLD);  
                $this->db->where('ItemID', $ItemIDOLD);
				
                if($row['TType2']=="Out"){
                    $QtyIn = $row['gtoqty'] - $row['BilledQty'];
                    $this->db->update(db_prefix() . 'stockmaster', [
					'gtoqty' => $QtyIn,
					]);
					}else{
                    $QtyIn = $row['gtiqty'] - $row['BilledQty'];
                    $this->db->update(db_prefix() . 'stockmaster', [
					'gtiqty' => $QtyIn,
					]);
				}      
			}
            
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $FY);
            $this->db->where('TType', "T");
            $this->db->where('TransID', $TransID);
            $this->db->delete(db_prefix() . 'history');
            if ($this->db->affected_rows() > 0) {
				if($selected_company == 1){
					$next_TransNumber = get_option('next_trns_number_for_cspl');
                    }elseif($selected_company == 2){
					$next_TransNumber = get_option('next_trns_number_for_cff');
                    }elseif($selected_company == 3){
					$next_TransNumber = get_option('next_trns_number_for_cbu');
                    }elseif($selected_company == 4){
					$next_TransNumber = get_option('next_trns_number_for_cbupl');
				}
                $new_TransNumber = 'TRS'.$FY.$next_TransNumber;
                echo json_encode($new_TransNumber);
                die;
				}else{
                echo json_encode(false);
			}
            
		}
		
		
		
	}	