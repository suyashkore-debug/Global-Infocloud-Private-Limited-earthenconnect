<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SplDisc extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SplDisc_model');
    }
    
    public function index(){
        
    if (!has_permission_new('SplDisc', '', 'view')) {
            access_denied('invoices');
    }
        $title = "Special Discount";
        $data['title'] = "Special Discount";;
        $this->load->model('clients_model');
		$data['routes']    = $this->clients_model->getroute();
        $data['states'] = $this->clients_model->getallstate();
        $fy = $this->session->userdata('finacial_year');
        $datas = array(
           'from_date' => '01/04/20'.$fy,
           'to_date'  => date('d/m/Y')
          );
        $this->load->model('sale_reports_model');
        $data['groups'] = $this->SplDisc_model->GetAllItemGroup();
        $data['DiscountList'] = $this->SplDisc_model->GateList();
        // echo '<pre>';
        // print_r($data['groups']);
        // die;
        $this->load->view('admin/SplDisc/Manage', $data);
    }
    
    public function ExportGetDiscDetailByID()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	    
    	    $DiscountID = $this->input->post('DiscountID');
            $order_by = $this->input->post('order_by');
            $DiscountDetails  = $this->SplDisc_model->GateDiscountDetails($DiscountID,$order_by);
            if($DiscountDetails){
                $data = array(
                    'FromDate' => _d(substr($DiscountDetails->TransdateFrom,0,10)),
                    'ToDate'  => _d(substr($DiscountDetails->TransdateTo,0,10))
                );
                $GetSaleItemGroup = $this->SplDisc_model->GetSaleItemGroup($data);
                $GetDiscountItem = $this->SplDisc_model->GetDiscItem($DiscountDetails->DiscountID);
                $GetDiscountLedger = $this->SplDisc_model->GetDiscLedger($DiscountDetails->DiscountID,$order_by);
                $company_detail = $this->SplDisc_model->get_company_detail();
                
                $DiscountItemName = '';
                $discItemGroupID = array();
                foreach ($GetDiscountItem as $key1 => $value1) {
                    $DiscountItemName = $DiscountItemName .','.$value1['name'];
                    array_push($discItemGroupID, $value1['ItemGroupID']);
                }
                $saleDetails = $this->SplDisc_model->SaleDetails($discItemGroupID,$DiscountDetails->TransdateFrom,$DiscountDetails->TransdateTo,$DiscountDetails->StateID,$DiscountDetails->LocationTypeID);
                //return $saleDetails;
                $FreshRtn = $this->SplDisc_model->FreshRtnDetails($discItemGroupID,$DiscountDetails->TransdateFrom,$DiscountDetails->TransdateTo,$DiscountDetails->StateID,$DiscountDetails->LocationTypeID);
                $DamageRtn = $this->SplDisc_model->DamageRtnDetails($discItemGroupID,$DiscountDetails->TransdateFrom,$DiscountDetails->TransdateTo,$DiscountDetails->StateID,$DiscountDetails->LocationTypeID);
                
                $colspan = '8';
                $writer = new XLSXWriter();
            	$company_name = array($company_detail->company_name);
            	$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
            	$writer->writeSheetRow('Sheet1', $company_name);
            		
            	$address = $company_detail->address;
            	$company_addr = array($address,);
            	$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
            	$writer->writeSheetRow('Sheet1', $company_addr);
            	
            	if($DiscountDetails->LocationTypeID == '1'){
                    $LocType = 'Local';
                }else if($DiscountDetails->LocationTypeID == '2'){
                    $LocType = 'OutStation';
                }else{
                    $LocType = 'notdefine';
                }
                
                $dateFilter = 'Discount Report From '._d(substr($DiscountDetails->TransdateFrom,0,10)).' To '._d(substr($DiscountDetails->TransdateTo,0,10)).' for State : '.$DiscountDetails->state_name.' , Location Type : '.$LocType;
            	$dateFilterArr = array($dateFilter,);
            	$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
            	$writer->writeSheetRow('Sheet1', $dateFilterArr);
            	
            	$DiscFilter = 'DiscountID : '.$DiscountDetails->DiscountID.' Discount % : '.$DiscountDetails->DiscPerc.' On : '.$DiscountItemName;
            	$DiscFilterArr = array($DiscFilter,);
            	$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
            	$writer->writeSheetRow('Sheet1', $DiscFilterArr);
                
                $set_col_tk = [];
        		$set_col_tk["AccountName"] = 'Account Name';
        		$set_col_tk["Station"] = 'Station';
        		$set_col_tk["SaleAmt"] = 'SaleAmt';
        		$set_col_tk["FreshRtn"] = 'FreshRtn';
        		$set_col_tk["Damages"] = 'Damages';
        		$set_col_tk["NetSale"] = 'NetSale';
        		$set_col_tk["Discount"] = 'Discount%';
        		
        		$writer_header = $set_col_tk;
    	        $writer->writeSheetRow('Sheet1', $writer_header);
    	        
    	        $i = 1;
                $saleAmtSum = 0;
                $FreshAmtSum = 0;
                $DamageAmtSum = 0;
                $NetSaleSum = 0;
                $DiscAmtSum = 0;
                foreach ($GetDiscountLedger as $key => $value) {
                    $SaleAmt = '';
                    $FreshAmt = '';
                    $DamageAmt = '';
                    $list_add = [];
                    $list_add[] = $value["company"];
                    $list_add[] = $value["StationName"];
                    foreach ($saleDetails as $key1 => $value1) {
                        if(strtoupper($value["AccountID"]) == strtoupper($value1["AccountID"])){
                            $SaleAmt = $value1["SaleSum"];
                        }
                    }
                
                    foreach ($FreshRtn as $key2 => $value2) {
                        if(strtoupper($value["AccountID"]) == strtoupper($value2["AccountID"])){
                            $FreshAmt = $value2["SaleSum"];
                        }
                    }
                
                    foreach ($DamageRtn as $key3 => $value3) {
                        if(strtoupper($value["AccountID"]) == strtoupper($value3["AccountID"])){
                            $DamageAmt = $value3["SaleSum"];
                        }
                    }
                    
                    
                    $list_add[] = $SaleAmt;
                    $saleAmtSum += $SaleAmt;
                    
                    $list_add[] = $FreshAmt;
                    $FreshAmtSum += $FreshAmt;
                    
                    $list_add[] = $DamageAmt;
                    $DamageAmtSum += $DamageAmt;
                    
                    $NetSale = $SaleAmt - ($FreshAmt + $DamageAmt);
                    $list_add[] = $NetSale;
                    $NetSaleSum += $NetSale;
                    
                    
                    $list_add[] = $value["Amount"];
                    $DiscAmtSum += $value["Amount"];
                    
                    $i++;
                    $writer->writeSheetRow('Sheet1', $list_add);
                }
                
                // Footer Data
                $list_add = [];
                $list_add[] = '';
                $list_add[] = '';
                $list_add[] = $saleAmtSum;
                $list_add[] = $FreshAmtSum;
                $list_add[] = $DamageAmtSum;
                $list_add[] = $NetSaleSum;
                $list_add[] = $DiscAmtSum;
                $writer->writeSheetRow('Sheet1', $list_add);
            
        	$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        		foreach($files as $file){
        			if(is_file($file)) {
        				unlink($file); 
        			}
        		}
        		$filename = 'SpecialDiscount.xlsx';
        		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        		echo json_encode([
        			'site_url'          => site_url(),
        			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        		]);
        		die;
            }
    	    
    	}
    }
    
    /* Get Discount Result Export / ajax */
    public function ExportResult()
    {
        if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	    $Data = $this->input->post();
            $DiscountDetails  = $this->SplDisc_model->ShowResultForExport($Data);
            $AccountIDList  = $this->SplDisc_model->AccountIDList($Data);
            $company_detail = $this->SplDisc_model->get_company_detail();
            $Discper = $Data['Discper'];
            $ItemGroupSerializedArr = $Data['ItemGroupSerializedArr'];
            $_ItemGroupSerializedArr = json_decode($ItemGroupSerializedArr, true);
            $GetItemGroupName = $this->SplDisc_model->GetItemGroupDetailsByID($_ItemGroupSerializedArr);
            $DiscountItemName = '';
            $i = 1;
            foreach ($GetItemGroupName as $key1 => $value1) {
                if($i == "1"){
                    $DiscountItemName = $value1['name'];
                }else{
                    $DiscountItemName = $DiscountItemName .','.$value1['name'];
                }
                $i++;
            }
            $colspan = '8';
            $writer = new XLSXWriter();
            
            $company_name = array($company_detail->company_name);
        	$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
            $writer->writeSheetRow('Sheet1', $company_name);
            		
            $address = $company_detail->address;
            $company_addr = array($address,);
            $writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
            $writer->writeSheetRow('Sheet1', $company_addr);
            	
            	if($Data['loc_type'] == '1'){
                    $LocType = 'Local';
                }else if($Data['loc_type'] == '2'){
                    $LocType = 'OutStation';
                }else{
                    $LocType = 'notdefine';
                }
                
            $dateFilter = 'Discount Report From '.$Data['FromDate'].' To '.$Data['ToDate'].' for State : '.$Data['states'].' , Location Type : '.$LocType;
            $dateFilterArr = array($dateFilter,);
            $writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
            $writer->writeSheetRow('Sheet1', $dateFilterArr);
            	
            $DiscFilter = ' Discount % : '.$Data['Discper'].', Item Group ON : '.$DiscountItemName;
            $DiscFilterArr = array($DiscFilter,);
            $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
            $writer->writeSheetRow('Sheet1', $DiscFilterArr);
            
            $set_col_tk = [];
        	$set_col_tk["AccountName"] = 'Account Name';
        	$set_col_tk["Station"] = 'Station';
        	$set_col_tk["SaleAmt"] = 'SaleAmt';
        	$set_col_tk["FreshRtn"] = 'FreshRtn';
        	$set_col_tk["Damages"] = 'Damages From Sale Rtn';
        	$set_col_tk["Damages2"] = 'Damages From Damage Module';
        	$set_col_tk["NetSale"] = 'NetSale';
        	$set_col_tk["Discount"] = 'Discount%';
        		
        	$writer_header = $set_col_tk;
    	    $writer->writeSheetRow('Sheet1', $writer_header);
    	    
    	    $SaleSum = 0;
            $FreshAmtSum = 0;
            $DamageAmtSumFromSaleRtn = 0;
            $DamageAmtSumFromDamageModule = 0;
            $NetSaleSum = 0;
            foreach ($AccountIDList as $key => $value) {
                $saleAmt = '';
                $FreshAmt = '';
                $DamageAmtFromSaleRtn = 0;
                $DamageAmtFromDamageModule = 0;
                $Name = '';
                $Station = '';
                $match = 0;
                
                    foreach ($DiscountDetails as $key1 => $value1) {
                        if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "O" && $value1["TType2"] == "Order"){
                           $saleAmt = $value1["SaleSum"];
                           $Name = $value1["company"];
                           $Station = $value1["StationName"];
                           $match = 1;
                        }
                        if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "R" && $value1["TType2"] == "Fresh"){
                           $FreshAmt = $value1["SaleSum"];
                           $Name = $value1["company"];
                           $Station = $value1["StationName"];
                           $match = 1;
                        }
                        
                        if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) && $value1["TType"] == "R" && $value1["TType2"] == "Damage"){
                           $DamageAmtFromSaleRtn = $value1["SaleSum"];
                           $Name = $value1["company"];
                           $Station = $value1["StationName"];
                           $match = 1;
                        }
                        
                        if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) && $value1["TType"] == "D" && $value1["TType2"] == "Damage"){
                           $DamageAmtFromDamageModule = $value1["SaleSum"];
                           $Name = $value1["company"];
                           $Station = $value1["StationName"];
                           $match = 1;
                        }
                    }
                    
                    if($match == "1"){
                        if(($saleAmt == "" || $saleAmt == "0") && ($FreshAmt == "" || $FreshAmt == "0") && ($DamageAmt == "" || $DamageAmt == "0")){
                            
                        }else{
                            $list_add = [];
                            $list_add[] = $Name;
                            $list_add[] = $Station;
                            
                            $list_add[] = $saleAmt;
                            $SaleSum += $saleAmt;
                            
                            $list_add[] = $FreshAmt;
                            $FreshAmtSum += $FreshAmt;
                            
                            $list_add[] = $DamageAmtFromSaleRtn;
                            $DamageAmtSumFromSaleRtn += $DamageAmtFromSaleRtn;
                            
                            $list_add[] = $DamageAmtFromDamageModule;
                            $DamageAmtSumFromDamageModule += $DamageAmtFromDamageModule;
                            
                            $NetSale = $saleAmt - ($FreshAmt + $DamageAmtFromSaleRtn + $DamageAmtFromDamageModule);
                            $list_add[] = $NetSale;
                            $NetSaleSum += $NetSale;
                            
                            //Convert our percentage value into a decimal.
                            $percentInDecimal = $Discper / 100;
                            //Get the result.
                            $DiscAmt = $percentInDecimal * $NetSale;
                            $list_add[] = $DiscAmt;
                            $DiscAmtSum += $DiscAmt;
                            $writer->writeSheetRow('Sheet1', $list_add);
                        }
                    }
            }
            
            // Footer Data
                $list_add = [];
                $list_add[] = '';
                $list_add[] = '';
                $list_add[] = $SaleSum;
                $list_add[] = $FreshAmtSum;
                $list_add[] = $DamageAmtSumFromSaleRtn;
                $list_add[] = $DamageAmtSumFromDamageModule;
                $list_add[] = $NetSaleSum;
                $list_add[] = $DiscAmtSum;
                $writer->writeSheetRow('Sheet1', $list_add);
                
            $files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        		foreach($files as $file){
        			if(is_file($file)) {
        				unlink($file); 
        			}
        		}
        		$filename = 'SpecialDiscountResult.xlsx';
        		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        		echo json_encode([
        			'site_url'          => site_url(),
        			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        		]);
        		die;
    	    
    	}
    }
    
    /* Get Discount Details by DiscountID / ajax */
    public function GetDiscDetailByID()
    {
        $DiscountID = $this->input->post('DiscountID');
        $order_by = $this->input->post('order_by');
        $DiscountDetails  = $this->SplDisc_model->GateList($DiscountID,$order_by);
        echo json_encode($DiscountDetails);
    }
    
    /* Get Discount Result / ajax */
    public function ShowResult()
    {
        $Data = $this->input->post();
        $DiscountDetails  = $this->SplDisc_model->ShowResult($Data);
        echo json_encode($DiscountDetails);
    }
    
    /* Save Discount Result / ajax */
    public function SaveResult()
    {
        $Data = $this->input->post();
        $DiscountDetails  = $this->SplDisc_model->SaveResult($Data);
        echo json_encode($DiscountDetails);
    }
    
    public function GetSaleItemGroup()
     {
        $data = array(
           'FromDate' => $this->input->post('FromDate'),
           'ToDate'  => $this->input->post('ToDate')
          );
      $data = $this->SplDisc_model->GetSaleItemGroup($data);
      echo json_encode($data);
    }
}
?>