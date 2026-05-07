<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QcMaster extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('QcMaster_model');
	}
	
	
//====================== QC Status List ========================================
	public function index($id = '')
	{
		if (!has_permission_new('QcStatusList', '', 'view')) {
			access_denied('QcStatusList');
		}
		$title = "QC Status List";
		$data['title']     = $title;
		$data['PlantDetail'] = $this->QcMaster_model->GetPlantDetails();
		$this->load->view('admin/QCMaster/QCStatusList', $data);
	}
//==================== Get All Purchase Entry Qc Status ========================
	public function LoadQCStatusList()
	{
	    if (!has_permission_new('QcStatusList', '', 'view')) {
			access_denied('QcStatusList');
		}
		$data = array(
    		'from_date' => $this->input->post('from_date'),
    		'to_date'  => $this->input->post('to_date'),
    		'status'  => $this->input->post('status'),
		);
		$data = $this->QcMaster_model->LoadQCStatusList($data);
		$html = "";
		$SrNo = 1;
		foreach($data as $key=>$val){
		    $QCStatus = $val["QCStatus"];
			$TotalItem = count($QCStatus);
			$QCStatusButton = "";
			if($TotalItem >0){
			    $totalY = 0;
				$totalN = 0;
				$totalH = 0;
				$totalC = 0;
				foreach($QCStatus as $value){
				    $status = $value["Status"];
					if($status == 'Y'){
						$totalY++;
					}elseif($status == 'N'){
						$totalN++;
					}elseif($status == 'H'){
						$totalH++;
					}else if($status == 'C'){
						$totalC++;
					}
				}
				$urlQC = admin_url()."QcMaster/AddEditPurchaseQC/".$val["PurchID"];
							
				if($totalN == $TotalItem || $totalN > 0 && $totalY >0 || $totalN > 0 && $totalH >0){
					$QCStatusButton .= '<span>Pending</span>';
				}
				if($totalY == $TotalItem ){
					$QCStatusButton .= '<span style="color:green;" target="_blank">Completed</span>';
				}
				if($totalH == $TotalItem || $totalN == 0 && $totalH > 0 && $totalY >0){
					$QCStatusButton .= '<span style="color:red;" target="_blank">Hold</span>';
				}
				if($totalC >0){
					$QCStatusButton .= '<span>Cancel</span>';
				}
			}else{
				continue;
			    $QCStatusButton .= '<span >QC Not Applicable<span>';
			}
			if (has_permission_new('PurchaseQCAddEdit', '', 'view') ) {
    			$html .= '<tr onclick = "window.open('."'".$urlQC."'".')">';
    		}else{
    		    $html .= '<tr>';
    		}
		    $html .= '<td  style="text-align:center;">'.$SrNo.'</a></td>';
		    $html .= '<td  style="text-align:center;">'.$val["PurchID"].'</a></td>';
		    $html .= '<td  style="text-align:center;">'._d($val["Transdate"]).'</a></td>';
		    $html .= '<td  style="text-align:left;">'.$val["AccountName"].'</a></td>';
		    $html .= '<td  style="text-align:left;">'.$QCStatusButton.'</a></td>';
		    $html .= "</tr>";
		    $SrNo++;
		}
		echo json_encode($html);
	}
//==================== Export All Purchase Entry Qc Status =====================	
	public function ExportQCStatusList()
	{
	    if (!has_permission_new('QcStatusList', '', 'export')) {
			access_denied('QcStatusList');
		}
		if(!class_exists('XLSXReader_fin')){
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
		}
		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
		
		if($this->input->post()){
			
			$data = array(
    			'from_date' => $this->input->post('from_date'),
    			'to_date'  => $this->input->post('to_date'),
    			'status'  => $this->input->post('status')
			);
			$status = $this->input->post('status');
			if(empty($status))
			{
				$status = "All";
			}
			$data = $this->QcMaster_model->LoadQCStatusList($data);  
			
			$PlantDetail = $this->QcMaster_model->GetPlantDetails();
			$writer = new XLSXWriter();
			
			$company_name = array($PlantDetail->FIRMNAME);
			$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);  //merge cells
			$writer->writeSheetRow('Sheet1', $company_name);
			
			$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
			$company_addr = array($address,);
			$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 4);  //merge cells
			$writer->writeSheetRow('Sheet1', $company_addr);
			
			$msg = "Purchase Entries Report ".$this->input->post('from_date')." To " .$this->input->post('to_date')." - Status : " .$status;
			$filter = array($msg);
			$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 4);  //merge cells
			$writer->writeSheetRow('Sheet1', $filter);
			
			// empty row
			$list_add = [];
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$writer->writeSheetRow('Sheet1', $list_add);
			$set_col_tk = [];
			$set_col_tk["Purch Entry No."] =  'Purch Entry No.';
			$set_col_tk["Purch Entry Date"] = 'Purch Entry Date';
			$set_col_tk["Purchased From"] = 'Purchased From';
			$set_col_tk["QC Status"] = 'QC Status';
			$writer_header = $set_col_tk;
			$writer->writeSheetRow('Sheet1', $writer_header);
			
			$i = 0;
			$total = 0;
			$rowspan = 0;
			$grand_total = 0;
			foreach ($data as $k => $val) {
			    $QCStatus = $val["QCStatus"];
    			$TotalItem = count($QCStatus);
    			$QCStatusButton = "";
    			if($TotalItem >0){
    			    $totalY = 0;
    				$totalN = 0;
    				$totalH = 0;
    				$totalC = 0;
    				foreach($QCStatus as $value){
    				    $status = $value["Status"];
    					if($status == 'Y'){
    						$totalY++;
    					}elseif($status == 'N'){
    						$totalN++;
    					}elseif($status == 'H'){
    						$totalH++;
    					}else if($status == 'C'){
    						$totalC++;
    					}
    				}
    				if($totalN == $TotalItem || $totalN > 0 && $totalY >0 || $totalN > 0 && $totalH >0){
    					$QCStatusButton .= 'Pending';
    				}
    				if($totalY == $TotalItem ){
    					$QCStatusButton .= 'Completed';
    				}
    				if($totalH == $TotalItem || $totalN == 0 && $totalH > 0 && $totalY >0){
    					$QCStatusButton .= 'Hold';
    				}
    				if($totalC >0){
    					$QCStatusButton .= 'Cancel';
    				}
    			}else{
    			    $QCStatusButton .= 'QC Not Applicable';
    			}
				
				$list_add = [];
				$list_add[] = $val["PurchID"];
				$date = _d(substr($val["Transdate"],0,10));
				$list_add[] = $date;
				$list_add[] = $val["AccountName"];
				$list_add[] = $QCStatusButton;
				
				$writer->writeSheetRow('Sheet1', $list_add);
			}
			
			$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
			foreach($files as $file){
				if(is_file($file)) {
					unlink($file); 
				}
			}
			$filename = 'QCStatusList.xlsx';
			$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
			echo json_encode([
			    'site_url'          => site_url(),
			    'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
			]);
			die;
		}
	}
	
//======================= Add Edit QC Parameter ================================
    public function AddEditPurchaseQC($id="")
    {
		if (!has_permission_new('PurchaseQCAddEdit', '', 'view')) {
            access_denied('purchase');
		}
		$title = "Add/Edit QC ";
		$data['purchase_details'] = $this->QcMaster_model->GetPurchaseEntryDetails($id);
		$data['purchase_history'] = $this->QcMaster_model->GetItemListAgianstPurchaseEntry($id);
		$data['QCItemsList'] = $this->QcMaster_model->GetQCApplicableItem($id);
		$data['title'] = $title;
		
		$this->load->view('admin/QCMaster/AddEditPurchaseQC', $data);  
	}
//=================== Update QC Parameter ======================================
	public function SavePurchaseQC()
	{
	    if (!has_permission_new('PurchaseQCAddEdit', '', 'create')) {
            access_denied('purchase');
		}
		$data = json_decode($this->input->raw_input_stream, true);
		$itemID = $data['itemInfo']['itemID'];
		$PurchaseEntryNo = $data['itemInfo']['PurchaseEntryNo'];
		$ItemStatus = $data['itemInfo']['ItemStatus'];
		
		$parameters = $data['parameters'];
		
		
		$this->db->where('PurchaseEntryNo', $PurchaseEntryNo);
		$this->db->where('ItemID', $itemID);
		$this->db->delete(db_prefix() . 'ItemWiseQcDetails');
		
		$i = 0;
		foreach($parameters as $paramid => $paramval){
			$insArr = [
			'PurchaseEntryNo' => $PurchaseEntryNo,
			'ItemID' => $itemID,
			'ParameterID' => $paramid,
			'value' => $paramval,
			'UserID' => $this->session->userdata('username'),
			'TransDate' => date('Y-m-d H:i:s'),
			];
			if($this->db->insert(db_prefix() . 'ItemWiseQcDetails', $insArr)){
				$i++;
			}
		}
		if($i >0){
			
			$this->db->where('ItemID', $itemID);
			$this->db->where('PurchaseEntryNo', $PurchaseEntryNo);
			$this->db->update(db_prefix() . 'ItemWiseQCStatus', ['Status'=>$ItemStatus]);
			// echo $ItemStatus;die;
			echo json_encode(true);
			}else{
			echo json_encode(false);
		}
	}
	
//========================== Update QC Status =================================
    public function UpdateQCStatus()
	{	
	    if (!has_permission_new('PurchaseQCApproveReject', '', 'create')) {
            access_denied('purchase');
		}
		$PurchaseEntryNo = $this->input->post('PurchaseEntryNo');
		$status = $this->input->post('status');
		$remark = $this->input->post('remark');
		$is_deduction = $this->input->post('is_deduction');
		$this->db->where('PurchaseEntryNo', $PurchaseEntryNo);
		if($this->db->update(db_prefix() . 'ItemWiseQCStatus', ['Status'=>$status,'remark'=>$remark])){
		    // Update Purchase Master
		    $this->db->where('PurchID', $PurchaseEntryNo);
		    $this->db->update(db_prefix() . 'purchasemaster', ['is_deduction'=>$is_deduction]);
			echo json_encode(true);
		}else{
			echo json_encode(false);
		}
	}
	
}