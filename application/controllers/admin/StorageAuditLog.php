<?php
	
defined('BASEPATH') or exit('No direct script access allowed');

class StorageAuditLog extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->PlantID = $this->session->userdata('root_company');
		$this->FY = $this->session->userdata('finacial_year');
		
		$this->load->model('storagelog_model');
		$this->load->model('sale_reports_model');
		$this->load->model('curl_model');
		$this->load->model('history_model');
	}
	
	// Daily Storage Monitoring Log =============================
	public function index($id = '')
	{
			if (!has_permission_new('StorageAuditLog', '', 'view')) {
							access_denied('invoices');
					}
		//close_setup_menu();
		$data['title']      = "Daily Storage Monitoring Log";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', '*', []);
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];
		$this->load->view('admin/StorageAuditLog/StorageMonitorLog', $data);
	}

	public function saveStorageMonitorLog(){
		if (!has_permission_new('StorageAuditLog', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_daily_storage_log', [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'Log for the selected date and location already exists.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'Remark' => $postData['remark'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id()
		];
		$logID = $this->curl_model->saveData('tbl_daily_storage_log', $insertData);
		if(empty($logID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Storage Monitor Log.']);
			die;
		}
		
		$batchSaveData = [
			['LogID' => $logID, 'ParameterName' => 'Temp', 'ReadingValue' => $postData['Temp'], 'Status' => $postData['TempStatus'], 'ActionTaken' => $postData['TempAction'], 'Initials' => $postData['TempInitial']],
			['LogID' => $logID, 'ParameterName' => 'Humidity', 'ReadingValue' => $postData['Humidity'], 'Status' => $postData['HumidityStatus'], 'ActionTaken' => $postData['HumidityAction'], 'Initials' => $postData['HumidityInitial']],
			['LogID' => $logID, 'ParameterName' => 'Pest', 'ReadingValue' => $postData['Pest'], 'Status' => $postData['PestStatus'], 'ActionTaken' => $postData['PestStatusAction'], 'Initials' => $postData['PestInitial']],
			['LogID' => $logID, 'ParameterName' => 'Clean', 'ReadingValue' => $postData['Clean'], 'Status' => $postData['CleanStatus'], 'ActionTaken' => $postData['CleanAction'], 'Initials' => $postData['CleanInitial']],
			['LogID' => $logID, 'ParameterName' => 'Packaging', 'ReadingValue' => $postData['Packaging'], 'Status' => $postData['PackagingStatus'], 'ActionTaken' => $postData['PackagingAction'], 'Initials' => $postData['PackagingInitial']]
		];
		$this->curl_model->batchSave('tbl_daily_storage_log_details', $batchSaveData);

		echo json_encode(['status' => true, 'message' => 'Storage Monitor Log saved successfully.']);
		die;
	}
	
	public function getStorageMonitorLogList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getStorageMonitorLog(){
		$LogID = $this->input->post('LogID');
		if(empty($LogID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_daily_storage_log', '*', ['LogID' => $LogID]);
		$data['log']->details = $this->curl_model->getResult('tbl_daily_storage_log_details', '*', ['LogID' => $LogID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updateStorageMonitorLog(){
		if (!has_permission_new('StorageAuditLog', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_daily_storage_log', [
			'LogID' => $postData['update_id']
		]);
		
		if(!$check){
			echo json_encode(['status' => false, 'message' => 'Log not found.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'] ?? 0,
			'Remark' => $postData['remark'],
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id()
		];
		$logID = $this->curl_model->saveData('tbl_daily_storage_log', $insertData, ['LogID' => $postData['update_id']]);
		if(empty($logID)){
			echo json_encode(['status' => false, 'message' => 'Failed to update Storage Monitor Log.']);
			die;
		}
		
		$batchUpdateData = [
			[['LogID' => $logID, 'ParameterName' => 'Temp'], ['ReadingValue' => $postData['Temp'], 'Status' => $postData['TempStatus'], 'ActionTaken' => $postData['TempAction'], 'Initials' => $postData['TempInitial']]],
			[['LogID' => $logID, 'ParameterName' => 'Humidity'], ['ReadingValue' => $postData['Humidity'], 'Status' => $postData['HumidityStatus'], 'ActionTaken' => $postData['HumidityAction'], 'Initials' => $postData['HumidityInitial']]],
			[['LogID' => $logID, 'ParameterName' => 'Pest'], ['ReadingValue' => $postData['Pest'], 'Status' => $postData['PestStatus'], 'ActionTaken' => $postData['PestStatusAction'], 'Initials' => $postData['PestInitial']]],
			[['LogID' => $logID, 'ParameterName' => 'Clean'], ['ReadingValue' => $postData['Clean'], 'Status' => $postData['CleanStatus'], 'ActionTaken' => $postData['CleanAction'], 'Initials' => $postData['CleanInitial']]],
			[['LogID' => $logID, 'ParameterName' => 'Packaging'], ['ReadingValue' => $postData['Packaging'], 'Status' => $postData['PackagingStatus'], 'ActionTaken' => $postData['PackagingAction'], 'Initials' => $postData['PackagingInitial']]]
		];
		$this->curl_model->batchUpdate('tbl_daily_storage_log_details', $batchUpdateData);

		echo json_encode(['status' => true, 'message' => 'Storage Monitor Log updated successfully.']);
		die;
	}
	
	// Receiving Inspection Checklist =============================
	public function InspectionChecklist($id = '')
	{ 
			if (!has_permission_new('InspectionChecklist', '', 'view')) {
				access_denied('invoices');
			}
		//close_setup_menu();
		$data['title']      = "Receiving Inspection Checklist";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', 'id, AccountName', []);
		$data['SupplierList'] = $this->history_model->getDistinctClientsFromHistory("h.TType='P' AND TType2='Order'");
		// $data['ProductList'] = $this->history_model->getDistinctItemsFromHistory("h.TType='P' AND TType2='Order'");
		// $data['BatchList'] = $this->history_model->getDistinctBatchesFromHistory("h.TType='P' AND TType2='Order'");
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getRICListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];

		$this->load->view('admin/StorageAuditLog/InspectionChecklist', $data);
	}

	public function getProductsBySupplier(){
		$SupplierID = $this->input->post('SupplierID');
		$AccountID = $this->curl_model->getRow('tblclients', 'AccountID', ['userid' => $SupplierID])->AccountID ?? '';
		if(empty($AccountID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Supplier ID.']);
			die;
		}
		$ProductList = $this->history_model->getDistinctItemsFromHistory("h.TType='P' AND TType2='Order' AND h.AccountID = '$AccountID'");
		echo json_encode(['status' => true, 'message' => 'Products fetched successfully.', 'data' => $ProductList]);
		die;
	}

	public function getBatchByProduct(){
		$ProductID = $this->input->post('ProductID');
		$SupplierID = $this->input->post('SupplierID');
		
		$AccountID = $this->curl_model->getRow('tblclients', 'AccountID', ['userid' => $SupplierID])->AccountID ?? '';
		if(empty($AccountID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Supplier ID.']);
			die;
		}

		$ItemID = $this->curl_model->getRow('tblitems', 'item_code', ['id' => $ProductID])->item_code ?? '';
		if(empty($ItemID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Product ID.']);
			die;
		}
		$BatchList = $this->history_model->getDistinctBatchesFromHistory("h.TType='P' AND TType2='Order' AND h.ItemID = '$ItemID' AND h.AccountID = '$AccountID'");
		echo json_encode(['status' => true, 'message' => 'Batches fetched successfully.', 'data' => $BatchList]);
		die;
	}

	public function saveReceivingInspectionChecklist(){
		if (!has_permission_new('InspectionChecklist', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID', 'ProductID', 'SupplierID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_receiving_inspection', [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'SupplierID' => $postData['SupplierID'],
			'ProductID' => $postData['ProductID'],
			'BatchNo' => $postData['BatchNo']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'This record already exists for the selected date, warehouse, supplier, product and batch number.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'SupplierID' => $postData['SupplierID'],
			'ProductID' => $postData['ProductID'],
			'BatchNo' => $postData['BatchNo'],
			'Remark' => $postData['remark'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id()
		];
		$InspectionID = $this->curl_model->saveData('tbl_receiving_inspection', $insertData);
		if(empty($InspectionID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Receiving Inspection Checklist.']);
			die;
		}
		
		$batchSaveData = [
			['InspectionID' => $InspectionID, 'ParameterName' => 'Packaging', 'Status' => $postData['PackagingStatus']],
			['InspectionID' => $InspectionID, 'ParameterName' => 'Moisture', 'Status' => $postData['MoistureStatus']],
			['InspectionID' => $InspectionID, 'ParameterName' => 'Pest', 'Status' => $postData['PestStatus']],
			['InspectionID' => $InspectionID, 'ParameterName' => 'Labels', 'Status' => $postData['LabelsStatus']],
			['InspectionID' => $InspectionID, 'ParameterName' => 'COA', 'Status' => $postData['COAStatus']]
		];
		$this->curl_model->batchSave('tbl_receiving_inspection_details', $batchSaveData);

		echo json_encode(['status' => true, 'message' => 'Receiving Inspection Checklist saved successfully.']);
		die;
	}

	public function getReceivingInspectionChecklistList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getRICListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getReceivingInspectionChecklist(){
		$InspectionID = $this->input->post('InspectionID');
		if(empty($InspectionID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_receiving_inspection', '*', ['InspectionID' => $InspectionID]);
		$data['log']->details = $this->curl_model->getResult('tbl_receiving_inspection_details', '*', ['InspectionID' => $InspectionID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updateReceivingInspectionChecklist(){
		if (!has_permission_new('InspectionChecklist', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID', 'ProductID', 'SupplierID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_receiving_inspection', [
			'InspectionID' => $postData['update_id']
		]);
		
		if(!$check){
			echo json_encode(['status' => false, 'message' => 'Inspection not found.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'] ?? 0,
			'SupplierID' => $postData['SupplierID'],
			'ProductID' => $postData['ProductID'],
			'BatchNo' => $postData['BatchNo'],
			'Remark' => $postData['remark'],
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id()
		];
		$InspectionID = $this->curl_model->saveData('tbl_receiving_inspection', $insertData, ['InspectionID' => $postData['update_id']]);
		if(empty($InspectionID)){
			echo json_encode(['status' => false, 'message' => 'Failed to update inspection.']);
			die;
		}
		
		$batchUpdateData = [
			[['InspectionID' => $InspectionID, 'ParameterName' => 'Packaging'], ['Status' => $postData['PackagingStatus']]],
			[['InspectionID' => $InspectionID, 'ParameterName' => 'Moisture'], ['Status' => $postData['MoistureStatus']]],
			[['InspectionID' => $InspectionID, 'ParameterName' => 'Pest'], ['Status' => $postData['PestStatus']]],
			[['InspectionID' => $InspectionID, 'ParameterName' => 'Labels'], ['Status' => $postData['LabelsStatus']]],
			[['InspectionID' => $InspectionID, 'ParameterName' => 'COA'], ['Status' => $postData['COAStatus']]]
		];
		$this->curl_model->batchUpdate('tbl_receiving_inspection_details', $batchUpdateData);

		echo json_encode(['status' => true, 'message' => 'Inspection updated successfully.']);
		die;
	}

	// Weekly Sanitation Checklist ===============================
	public function SanitationChecklist($id = '')
	{ 
		if (!has_permission_new('SanitationChecklist', '', 'view')) {
			access_denied('invoices');
		}
		//close_setup_menu();
		$data['title']      = "Weekly Sanitation Checklist";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', 'id, AccountName', []);
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getSCListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];

		$this->load->view('admin/StorageAuditLog/SanitationChecklist', $data);
	}

	public function saveSanitationChecklist(){
		if (!has_permission_new('SanitationChecklist', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_weekly_sanitation', [
			'WeekStartDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'This record already exists for the selected date, warehouse and location.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'WeekStartDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id()
		];
		$SanitationID = $this->curl_model->saveData('tbl_weekly_sanitation', $insertData);
		if(empty($SanitationID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Weekly Sanitation Checklist.']);
			die;
		}
		
		$batchSaveData = [
			['SanitationID' => $SanitationID, 'AreaName' => 'Floor', 'Status' => $postData['FloorStatus'], 'CleaningAgent' => $postData['FloorAgent'], 'IssuesFound' => $postData['FloorIssue'], 'ActionTaken' => $postData['FloorAction'], 'Initials' => $postData['FloorInitial']],
			['SanitationID' => $SanitationID, 'AreaName' => 'Pallets', 'Status' => $postData['PalletsStatus'], 'CleaningAgent' => $postData['PalletsAgent'], 'IssuesFound' => $postData['PalletsIssue'], 'ActionTaken' => $postData['PalletsAction'], 'Initials' => $postData['PalletsInitial']],
			['SanitationID' => $SanitationID, 'AreaName' => 'Rack', 'Status' => $postData['RackStatus'], 'CleaningAgent' => $postData['RackAgent'], 'IssuesFound' => $postData['RackIssue'], 'ActionTaken' => $postData['RackAction'], 'Initials' => $postData['RackInitial']],
			['SanitationID' => $SanitationID, 'AreaName' => 'Walls', 'Status' => $postData['WallsStatus'], 'CleaningAgent' => $postData['WallsAgent'], 'IssuesFound' => $postData['WallsIssue'], 'ActionTaken' => $postData['WallsAction'], 'Initials' => $postData['WallsInitial']],
			['SanitationID' => $SanitationID, 'AreaName' => 'Entry', 'Status' => $postData['EntryStatus'], 'CleaningAgent' => $postData['EntryAgent'], 'IssuesFound' => $postData['EntryIssue'], 'ActionTaken' => $postData['EntryAction'], 'Initials' => $postData['EntryInitial']],
		];
		$this->curl_model->batchSave('tbl_weekly_sanitation_details', $batchSaveData);

		echo json_encode(['status' => true, 'message' => 'Weekly Sanitation Checklist saved successfully.']);
		die;
	}

	public function getSanitationChecklistList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getSCListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getSanitationChecklist(){
		$SanitationID = $this->input->post('SanitationID');
		if(empty($SanitationID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_weekly_sanitation', '*', ['SanitationID' => $SanitationID]);
		$data['log']->details = $this->curl_model->getResult('tbl_weekly_sanitation_details', '*', ['SanitationID' => $SanitationID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updateSanitationChecklist(){
		if (!has_permission_new('SanitationChecklist', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_weekly_sanitation', [
			'SanitationID' => $postData['update_id']
		]);
		
		if(!$check){
			echo json_encode(['status' => false, 'message' => 'Inspection not found.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'WeekStartDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'] ?? 0,
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id()
		];
		$SanitationID = $this->curl_model->saveData('tbl_weekly_sanitation', $insertData, ['SanitationID' => $postData['update_id']]);
		if(empty($SanitationID)){
			echo json_encode(['status' => false, 'message' => 'Failed to update sanitation log.']);
			die;
		}
		
		$batchUpdateData = [
			[['SanitationID' => $SanitationID, 'AreaName' => 'Floor'], ['Status' => $postData['FloorStatus'], 'CleaningAgent' => $postData['FloorAgent'], 'IssuesFound' => $postData['FloorIssue'], 'ActionTaken' => $postData['FloorAction'], 'Initials' => $postData['FloorInitial']]],
			[['SanitationID' => $SanitationID, 'AreaName' => 'Pallets'], ['Status' => $postData['PalletsStatus'], 'CleaningAgent' => $postData['PalletsAgent'], 'IssuesFound' => $postData['PalletsIssue'], 'ActionTaken' => $postData['PalletsAction'], 'Initials' => $postData['PalletsInitial']]],
			[['SanitationID' => $SanitationID, 'AreaName' => 'Rack'], ['Status' => $postData['RackStatus'], 'CleaningAgent' => $postData['RackAgent'], 'IssuesFound' => $postData['RackIssue'], 'ActionTaken' => $postData['RackAction'], 'Initials' => $postData['RackInitial']]],
			[['SanitationID' => $SanitationID, 'AreaName' => 'Walls'], ['Status' => $postData['WallsStatus'], 'CleaningAgent' => $postData['WallsAgent'], 'IssuesFound' => $postData['WallsIssue'], 'ActionTaken' => $postData['WallsAction'], 'Initials' => $postData['WallsInitial']]],
			[['SanitationID' => $SanitationID, 'AreaName' => 'Entry'], ['Status' => $postData['EntryStatus'], 'CleaningAgent' => $postData['EntryAgent'], 'IssuesFound' => $postData['EntryIssue'], 'ActionTaken' => $postData['EntryAction'], 'Initials' => $postData['EntryInitial']]]
		];
		$this->curl_model->batchUpdate('tbl_weekly_sanitation_details', $batchUpdateData);

		echo json_encode(['status' => true, 'message' => 'Sanitation log updated successfully.']);
		die;
	}

	// Weekly/Monthly Pest Control Log =====================================
	public function PestControlLog($id = '')
	{ 
		if (!has_permission_new('PestControlLog', '', 'view')) {
			access_denied('invoices');
		}
		//close_setup_menu();
		$data['title']      = "Weekly/Montly Pest Control Log";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', 'id, AccountName', []);
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getPCListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];

		$this->load->view('admin/StorageAuditLog/PestControlLog', $data);
	}

	public function savePestControlLog(){
		if (!has_permission_new('PestControlLog', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_pest_control_log', [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'This record already exists for the selected date, warehouse and location.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'ServiceName' => $postData['serviceName'],
			'Remark' => $postData['remark'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id()
		];
		$PestLogID = $this->curl_model->saveData('tbl_pest_control_log', $insertData);
		if(empty($PestLogID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Weekly Sanitation Checklist.']);
			die;
		}
		
		$batchSaveData = [
			['PestLogID' => $PestLogID, 'TrapLocation' => 'Door', 'TrapCondition' => $postData['DoorCondition'], 'ActivityFound' => $postData['DoorActFound'], 'ActionTaken' => $postData['DoorAction'], 'Initials' => $postData['DoorInitial']],
			['PestLogID' => $PestLogID, 'TrapLocation' => 'Corners', 'TrapCondition' => $postData['CornersCondition'], 'ActivityFound' => $postData['CornersActFound'], 'ActionTaken' => $postData['CornersAction'], 'Initials' => $postData['CornersInitial']],
			['PestLogID' => $PestLogID, 'TrapLocation' => 'Area', 'TrapCondition' => $postData['AreaCondition'], 'ActivityFound' => $postData['AreaActFound'], 'ActionTaken' => $postData['AreaAction'], 'Initials' => $postData['AreaInitial']]
		];
		$this->curl_model->batchSave('tbl_pest_control_log_details', $batchSaveData);

		echo json_encode(['status' => true, 'message' => 'Weekly Sanitation Checklist saved successfully.']);
		die;
	}

	public function getPestControlLogList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getPCListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getPestControlLog(){
		$PestLogID = $this->input->post('PestLogID');
		if(empty($PestLogID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_pest_control_log', '*', ['PestLogID' => $PestLogID]);
		$data['log']->details = $this->curl_model->getResult('tbl_pest_control_log_details', '*', ['PestLogID' => $PestLogID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updatePestControlLog(){
		if (!has_permission_new('PestControlLog', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_pest_control_log', [
			'PestLogID' => $postData['update_id']
		]);
		
		if(!$check){
			echo json_encode(['status' => false, 'message' => 'Inspection not found.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'] ?? 0,
			'ServiceName' => $postData['serviceName'],
			'Remark' => $postData['remark'],
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id()
		];
		$PestLogID = $this->curl_model->saveData('tbl_pest_control_log', $insertData, ['PestLogID' => $postData['update_id']]);
		if(empty($PestLogID)){
			echo json_encode(['status' => false, 'message' => 'Failed to update sanitation log.']);
			die;
		}
		
		$batchUpdateData = [
			[['PestLogID' => $PestLogID, 'TrapLocation' => 'Door'], ['TrapCondition' => $postData['DoorCondition'], 'ActivityFound' => $postData['DoorActFound'], 'ActionTaken' => $postData['DoorAction'], 'Initials' => $postData['DoorInitial']]],
			[['PestLogID' => $PestLogID, 'TrapLocation' => 'Corners'], ['TrapCondition' => $postData['CornersCondition'], 'ActivityFound' => $postData['CornersActFound'], 'ActionTaken' => $postData['CornersAction'], 'Initials' => $postData['CornersInitial']]],
			[['PestLogID' => $PestLogID, 'TrapLocation' => 'Area'], ['TrapCondition' => $postData['AreaCondition'], 'ActivityFound' => $postData['AreaActFound'], 'ActionTaken' => $postData['AreaAction'], 'Initials' => $postData['AreaInitial']]]
		];
		$this->curl_model->batchUpdate('tbl_pest_control_log_details', $batchUpdateData);

		echo json_encode(['status' => true, 'message' => 'Sanitation log updated successfully.']);
		die;
	}

	// =======================================
	public function TraceabilityLog($id = '')
	{ 
			if (!has_permission_new('TraceabilityLog', '', 'view')) {
							access_denied('invoices');
					}
		//close_setup_menu();
		$data['title']      = "Inventory / Traceability Log";
		$data['WarehouseList'] = array();
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$this->load->view('admin/StorageAuditLog/TraceabilityLog', $data);
	}

	// Non-Conformance & Corrective Action Log ================================
	public function NonConformanceActionLog($id = '')
	{ 
		if (!has_permission_new('NonConformanceActionLog', '', 'view')) {
			access_denied('invoices');
		}
		//close_setup_menu();
		$data['title']      = "Non-Conformance & Corrective Action Log";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', 'id, AccountName', []);
		$data['NCTypeList'] = $this->curl_model->getResult('tbl_nc_types', '*', []);
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getNCAListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];

		$this->load->view('admin/StorageAuditLog/NonConformanceActionLog', $data);
	}

	public function saveNonConformanceActionLog(){
		if (!has_permission_new('NonConformanceActionLog', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_non_conformance_log', [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'This record already exists for the selected date, warehouse and location.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'Issue' => $postData['Issue'],
			'CorrectiveAction' => $postData['ActionTaken'],
			'PreventiveAction' => $postData['PreAction'],
			'OtherText' => $postData['other_input'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id()
		];
		$NCID = $this->curl_model->saveData('tbl_non_conformance_log', $insertData);
		if(empty($NCID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Non-Conformance & Corrective Action Log.']);
			die;
		}
		
		$batchSaveData = [];
		foreach ($postData['type'] as $key => $value) {
			$batchSaveData[] = [
				'NCID' => $NCID,
				'TypeID' => $value
			];
		}
		$this->curl_model->batchSave('tbl_nc_log_types', $batchSaveData);

		echo json_encode(['status' => true, 'message' => 'Non-Conformance & Corrective Action Log saved successfully.']);
		die;
	}

	public function getNonConformanceActionLogList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getNCAListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getNonConformanceActionLog(){
		$NCID = $this->input->post('NCID');
		if(empty($NCID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_non_conformance_log', '*', ['NCID' => $NCID]);
		$data['log']->details = $this->curl_model->getResult('tbl_nc_log_types', '*', ['NCID' => $NCID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updateNonConformanceActionLog(){
		if (!has_permission_new('NonConformanceActionLog', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_non_conformance_log', [
			'NCID' => $postData['update_id']
		]);
		
		if(!$check){
			echo json_encode(['status' => false, 'message' => 'Non-conformance log not found.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'] ?? 0,
			'Issue' => $postData['Issue'],
			'CorrectiveAction' => $postData['ActionTaken'],
			'PreventiveAction' => $postData['PreAction'],
			'OtherText' => $postData['other_input'],
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id()
		];
		$NCID = $this->curl_model->saveData('tbl_non_conformance_log', $insertData, ['NCID' => $postData['update_id']]);
		if(empty($NCID)){
			echo json_encode(['status' => false, 'message' => 'Failed to update non-conformance log.']);
			die;
		}
		
		$typeIDs = $postData['type'] ?? [];
		$presentTypes = $this->curl_model->getResult('tbl_nc_log_types', 'TypeID', ['NCID' => $NCID]);
		$presentIDs = array_column($presentTypes, 'TypeID');
		$typeIDs    = array_map('intval', $typeIDs);
		$presentIDs = array_map('intval', $presentIDs);
		
		$toInsert = array_diff($typeIDs, $presentIDs);
		$toDelete = array_diff($presentIDs, $typeIDs);

		foreach ($toInsert as $typeID) {
			$this->curl_model->saveData('tbl_nc_log_types', [
				'NCID'   => $NCID,
				'TypeID' => $typeID
			]);
		}
		
		if (!empty($toDelete)) {
			$this->db->where('NCID', $NCID);
			$this->db->where_in('TypeID', $toDelete);
			$this->db->delete('tbl_nc_log_types');
		}

		echo json_encode(['status' => true, 'message' => 'Non-conformance log updated successfully.']);
		die;
	}

	// Monthly Inspection Checklist =======================================
	public function MonthlyInspectionChecklist($id = '')
	{ 
		if (!has_permission_new('MonthlyInspectionChecklist', '', 'view')) {
			access_denied('invoices');
		}
		$data['title']      = "Monthly Inspection Check List";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', 'id, AccountName', []);
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getMICListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];

		$this->load->view('admin/StorageAuditLog/MonthlyInspectionChecklist', $data);
	}

	public function saveMonthlyInspectionChecklist(){
		if (!has_permission_new('MonthlyInspectionChecklist', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_monthly_inspection', [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'This record already exists for the selected date, warehouse and location.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'CheckPointIDs' => json_encode($postData['type'] ?? []),
			'OverallStatus' => $postData['status'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id()
		];
		$InspectionID = $this->curl_model->saveData('tbl_monthly_inspection', $insertData);
		if(empty($InspectionID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Monthly Inspection Checklist.']);
			die;
		}

		echo json_encode(['status' => true, 'message' => 'Monthly Inspection Checklist saved successfully.']);
		die;
	}

	public function getMonthlyInspectionChecklistList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getMICListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getMonthlyInspectionChecklist(){
		$InspectionID = $this->input->post('InspectionID');
		if(empty($InspectionID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_monthly_inspection', '*', ['InspectionID' => $InspectionID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updateMonthlyInspectionChecklist(){
		if (!has_permission_new('MonthlyInspectionChecklist', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_monthly_inspection', [
			'InspectionID' => $postData['update_id']
		]);
		
		if(!$check){
			echo json_encode(['status' => false, 'message' => 'Inspection not found.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'] ?? 0,
			'CheckPointIDs' => json_encode($postData['type'] ?? []),
			'OverallStatus' => $postData['status'],
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id()
		];
		$InspectionID = $this->curl_model->saveData('tbl_monthly_inspection', $insertData, ['InspectionID' => $postData['update_id']]);
		if(empty($InspectionID)){
			echo json_encode(['status' => false, 'message' => 'Failed to update inspection log.']);
			die;
		}

		echo json_encode(['status' => true, 'message' => 'Monthly Inspection Checklist updated successfully.']);
		die;
	}

	// Recall Mock Drill ==================================
	public function RecallMockDrill($id = '')
	{ 
		if (!has_permission_new('RecallMockDrill', '', 'view')) {
			access_denied('invoices');
		}
		//close_setup_menu();
		$data['title']      = "Recall Mock Drill ";
		$data['WarehouseList'] = $this->curl_model->getResult('tblgodownmaster', 'id, AccountName', []);
		$data['ProductList'] = $this->curl_model->getResult('tblitems', 'id, item_code, description', ['MainGrpID' => 1]);
		$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
		$data['log_list'] = $this->storagelog_model->getRMDListByFilter(['fromDate' => date('Y-m-01'), 'toDate' => date('Y-m-d')], 500, 0)['rows'] ?? [];

		$this->load->view('admin/StorageAuditLog/RecallMockDrill', $data);
	}
	
	public function getBatchByProductOnly(){
		$ProductID = $this->input->post('ProductID');

		$ItemID = $this->curl_model->getRow('tblitems', 'item_code', ['id' => $ProductID])->item_code ?? '';
		if(empty($ItemID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Product ID.']);
			die;
		}
		$BatchList = $this->history_model->getDistinctBatchesFromHistory("h.TType='P' AND TType2='Order' AND h.ItemID = '$ItemID'");
		echo json_encode(['status' => true, 'message' => 'Batches fetched successfully.', 'data' => $BatchList]);
		die;
	}

	public function saveRecallMockDrill(){
		if (!has_permission_new('RecallMockDrill', '', 'create')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['date', 'WarehouseID', 'ProductID', 'status']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// check existing log for the day, warehouse and location
		$check = $this->curl_model->checkExist('tbl_recall_mock_drill', [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'ProductID' => $postData['ProductID']
		]);
		
		if($check){
			echo json_encode(['status' => false, 'message' => 'Recall Mock Drill for this date already exists.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'ProductID' => $postData['ProductID'],
			'BatchNo' => $postData['BatchNo'],
			'TimeRecallInitiated' => $postData['time_recall'],
			'TimeCustomerIdentified' => $postData['time_identified'],
			'TimeProductTraced' => $postData['time_traced'],
			'CompletedWithin2Hrs' => $postData['status'],
			'GapIdentified' => $postData['gap_identified'],
			'ActionPlan' => $postData['action_plan'],
			'TransDate2' => date('Y-m-d H:i:s'),
			// 'UserID' => get_staff_user_id(),
		];
		$RecallID = $this->curl_model->saveData('tbl_recall_mock_drill', $insertData);
		if(empty($RecallID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Recall Mock Drill.']);
			die;
		}

		echo json_encode(['status' => true, 'message' => 'Recall Mock Drill saved successfully.']);
		die;
	}

	public function getRecallMockDrillList(){
		$postData = $this->curl_model->validatePayload($this->input->post(), ['fromDate', 'toDate']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}
		$limit  = $postData['limit'] ?? 100;
		$offset = $postData['offset'] ?? 0;

		$postData['fromDate'] = to_sql_date($postData['fromDate']);
		$postData['toDate'] = to_sql_date($postData['toDate']);

		$result  = $this->storagelog_model->getRMDListByFilter($postData, $limit, $offset);
		if (!empty($result['rows'])) {
			echo json_encode(['success' => true,
				'message' => 'Data found',
				'total'   => $result['total'],
				'rows'    => $result['rows']]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No logs found.']);
		}
	}

	public function getRecallMockDrill(){
		$RecallID = $this->input->post('RecallID');
		if(empty($RecallID)){
			echo json_encode(['status' => false, 'message' => 'Invalid Recall ID.']);
			die;
		}
		$data['log'] = $this->curl_model->getRow('tbl_recall_mock_drill', '*', ['RecallID' => $RecallID]);
		echo json_encode(['success' => true, 'data' => $data['log']]);
	}

	public function updateRecallMockDrill(){
		if (!has_permission_new('RecallMockDrill', '', 'edit')) {
			access_denied('invoices');
		}
		// echo json_encode($this->input->post()); die;

		$postData = $this->curl_model->validatePayload($this->input->post(), ['update_id', 'date', 'WarehouseID', 'ProductID', 'status']);
		if(!$postData){
			echo json_encode(['status' => false, 'message' => 'Please fill all * fields.']);
			die;
		}

		// save in daily_storage_log
		$insertData = [
			'TransDate' => to_sql_date($postData['date']),
			'WarehouseID' => $postData['WarehouseID'],
			'LocationID' => $postData['LocationID'],
			'ProductID' => $postData['ProductID'],
			'BatchNo' => $postData['BatchNo'],
			'TimeRecallInitiated' => $postData['time_recall'],
			'TimeCustomerIdentified' => $postData['time_identified'],
			'TimeProductTraced' => $postData['time_traced'],
			'CompletedWithin2Hrs' => $postData['status'],
			'GapIdentified' => $postData['gap_identified'],
			'ActionPlan' => $postData['action_plan'],
			'Lupdate' => date('Y-m-d H:i:s'),
			// 'UserID2' => get_staff_user_id(),
		];
		$RecallID = $this->curl_model->saveData('tbl_recall_mock_drill', $insertData, ['RecallID' => $postData['update_id']]);
		if(empty($RecallID)){
			echo json_encode(['status' => false, 'message' => 'Failed to save Recall Mock Drill.']);
			die;
		}

		echo json_encode(['status' => true, 'message' => 'Recall Mock Drill saved successfully.']);
		die;
	}
	


    public function PrintPDF()
    {
        $LogID = $this->input->get('LogID');
        if (empty($LogID)) {
            echo json_encode(['status' => false, 'message' => 'Invalid Log ID.']);
            die;
        }
    
        $log          = $this->curl_model->getRow('tbl_daily_storage_log', '*', ['LogID' => $LogID]);
        $log->details = $this->curl_model->getResult('tbl_daily_storage_log_details', '*', ['LogID' => $LogID]);
        $log->rootcompany = $this->curl_model->getrootcompany('tblrootcompany');
        // echo"<pre>";print_r($log);exit();

    
        $invoice = [
            'invoice' => $log,
        ];
    
        try {
            $pdf = StorageAuditLog_pdf($invoice);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }
    
        $type = 'I';
        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }
        if ($this->input->get('print')) {
            $type = 'I';
        }
    
        // Fixed: was using undefined $OrderID
        $pdf->Output(mb_strtoupper(slug_it('storage-audit-log-' . $LogID)) . '.pdf', $type);
    }
}