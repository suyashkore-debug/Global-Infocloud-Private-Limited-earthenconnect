<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class SchemeMaster extends AdminController
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('SchemeMaster_model');
		}
		
		public function index(){
			
			if (!has_permission_new('SchemeMaster', '', 'view')) {
				access_denied('invoices');
			}
			$title = "Scheme Master";
			$data['title'] = $title;
			$this->load->model('clients_model');
			$data['states'] = $this->clients_model->getallstate();
			$data['groups'] = $this->clients_model->get_groups();
			$data['ItemList'] = $this->SchemeMaster_model->GetItemList();
			$fy = $this->session->userdata('finacial_year');
			$datas = array(
			'from_date' => '01/04/20'.$fy,
			'to_date'  => date('d/m/Y')
			);
			$this->load->model('sale_reports_model');
			//$data['groups'] = $this->SplDisc_model->GetAllItemGroup();
			$data['SchemeList'] = $this->SchemeMaster_model->GateList();
			/*echo '<pre>';
				print_r($data['SchemeList']);
			die;*/
			$this->load->view('admin/SchemeMaster/AddEditScheme', $data);
		}
		
		/* Get Discount Details by DiscountID / ajax */
		public function GetItemDetailByItemID()
		{
			$ItemID = $this->input->post('ItemID');
			$State = $this->input->post('State');
			$DistType = $this->input->post('DistType');
			$SchemeID = $this->input->post('SchemeID');
			$FromDate = $this->input->post('FromDate');
			$ToDate = $this->input->post('ToDate');
			$DiscountDetails  = $this->SchemeMaster_model->GetItemDetailByItemID($ItemID,$State,$DistType,$FromDate,$ToDate,$SchemeID);
			echo json_encode($DiscountDetails);
		}
		
		public function SaveScheme()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			if($selected_company == 1){
				$SchemeID = get_option('next_scheme_number_for_cspl');
				}elseif($selected_company == 2){
				$SchemeID = get_option('next_scheme_number_for_cff');
				}elseif($selected_company == 3){
				$SchemeID = get_option('next_scheme_number_for_cbu');
			}
			
			$new_SchemeID = 'SCH'.$FY.$SchemeID;
			
			//$vehicle_number = $this->input->post('vehicle_number');
			$FromDate = to_sql_date($this->input->post('FromDate'))." 00:00:00";
			$ToDate = to_sql_date($this->input->post('ToDate'))." 23:59:59";
			$states = $this->input->post('states');
			$client_type = $this->input->post('client_type');
			$distTypeStr = implode(',', $client_type);
			$narration = $this->input->post('narration');
			$SchemeType = $this->input->post('SchemeType');
			$ItemSerializedArr = $this->input->post('ItemSerializedArr');
			$ItemValArray = json_decode($ItemSerializedArr, true);
			$itemCount = sizeof($ItemValArray);
			
			$InsertDetails = array(
            "FY"=>$FY,
            "PlantID"=>$selected_company,
            "SchemeID"=>$new_SchemeID,
            "TransDate"=>date('Y-m-d H:i:s'),
            "SchemeType"=>$SchemeType,
            "DistributorType"=>$distTypeStr,
            "narration"      =>$narration,
            "StartDate"=>$FromDate,
            "EndDate"=>$ToDate,
            "UserID"=>$this->session->userdata('username'),
            "StateID"=>$states,
            "status"=>1,
			"Approve"=>"N"
			);
			$this->db->insert(db_prefix() . 'schememaster', $InsertDetails);
			if($this->db->affected_rows()>0){
				$this->SchemeMaster_model->increment_next_number();
				$Ord = 1;
				for($k=0; $k<$itemCount; $k++) {
					$ItemID = $ItemValArray[$k][0];
					
					$SlabCases = $ItemValArray[$k][1];
					$Disc = $ItemValArray[$k][2];
					$UnitDisc = $ItemValArray[$k][3];
					$status = $ItemValArray[$k][4];
					$Disc_type = $ItemValArray[$k][5];
					$FreeItem = $ItemValArray[$k][6];
					if($Disc_type == "disc"){
						$Discpkt = '0';
						$DiscPerc = $Disc;
					}
					if($Disc_type == "free_distribution"){
						$Discpkt = $UnitDisc;
						$DiscPerc = '0';
					}
					$InsertItem = array(
					"FY"=>$FY,
					"PlantID"=>$selected_company,
					"SchemeID"=>$new_SchemeID,
					"TransDate"=>date('Y-m-d H:i:s'),
					"DistributorType"=>$distTypeStr,
					"ItemID"=>$ItemID,
					"FreeItemID"=>$FreeItem,
					"SuppliedIn"=>'CS',
					"SlabQty"=>$SlabCases,
					"Disc_type"=>$Disc_type,
					"Disc_pkt"=>$Discpkt,
					"DiscPerc"=>$DiscPerc,
					"StartDate"=>$FromDate,
					"EndDate"=>$ToDate,
					"ActYN"=>strtoupper($status),
					"Ordinalno"=>$Ord,
					"StateID"=>$states
                    );
                    if($ItemID == ""){
                        
						}else{
                        $this->db->insert(db_prefix() . 'schemedetails', $InsertItem);
                        $Ord++;
					}
				}
				
				if($selected_company == 1){
					$NextSchemeID = get_option('next_scheme_number_for_cspl');
					}elseif($selected_company == 2){
					$NextSchemeID = get_option('next_scheme_number_for_cff');
					}elseif($selected_company == 3){
					$NextSchemeID = get_option('next_scheme_number_for_cbu');
				}
				$Nextnew_SchemeID = 'SCH'.$FY.$NextSchemeID;
				echo json_encode($Nextnew_SchemeID);
				}else{
				echo json_encode(false);
			}  
		}
		
		public function UpdateScheme()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$SchemeID = $this->input->post('SchemeID');
			$FromDate = to_sql_date($this->input->post('FromDate'))." 00:00:00";
			$ToDate = to_sql_date($this->input->post('ToDate'))." 23:59:59";
			$states = $this->input->post('states');
			$client_type = $this->input->post('client_type');
			$distTypeStr = implode(',', $client_type);
			$narration = $this->input->post('narration');
			$SchemeType = $this->input->post('SchemeType');
			
			$ItemSerializedArr = $this->input->post('ItemSerializedArr');
			$ItemValArray = json_decode($ItemSerializedArr, true);
			$itemCount = sizeof($ItemValArray);
			$SchemeDetails  = $this->SchemeMaster_model->GetSchemeByID($SchemeID);
			$TransDate = $SchemeDetails->TransDate;
			$UserID = $SchemeDetails->UserID;
			$ApproveYN = $SchemeDetails->Approve;
			if($ApproveYN == "Y" || $ApproveYN == "y"){
				$this->db->where('PlantID', $selected_company);
				$this->db->where('FY', $FY);
				$this->db->where('SchemeID', $SchemeID);
				$this->db->delete(db_prefix() . 'schemedetails');
				
				$Ord = 1;
				for($k=0; $k<$itemCount; $k++) {
					$ItemID = $ItemValArray[$k][0];
					
					$SlabCases = $ItemValArray[$k][1];
					$Disc = $ItemValArray[$k][2];
					$UnitDisc = $ItemValArray[$k][3];
					$status = $ItemValArray[$k][4];
					$Disc_type = $ItemValArray[$k][5];
					$FreeItem = $ItemValArray[$k][6];
					if($Disc_type == "disc"){
						$Discpkt = '0';
						$DiscPerc = $Disc;
					}
					if($Disc_type == "free_distribution"){
						$Discpkt = $UnitDisc;
						$DiscPerc = '0';
					}
					$InsertItem = array(
                    "FY"=>$FY,
                    "PlantID"=>$selected_company,
                    "SchemeID"=>$SchemeID,
                    "UserID2"=>$this->session->userdata('username'),
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "TransDate"=>$TransDate,
                    "DistributorType"=>$distTypeStr,
                    "ItemID"=>$ItemID,
					"FreeItemID"=>$FreeItem,
                    "SuppliedIn"=>'CS',
                    "SlabQty"=>$SlabCases,
					"Disc_type"=>$Disc_type,
					"Disc_pkt"=>$Discpkt,
                    "DiscPerc"=>$DiscPerc,
                    "StartDate"=>$FromDate,
                    "EndDate"=>$ToDate,
                    "ActYN"=>strtoupper($status),
                    "Ordinalno"=>$Ord,
                    "StateID"=>$states
					);
					if($ItemID == ""){
						
						}else{
						$this->db->insert(db_prefix() . 'schemedetails', $InsertItem);
						$Ord++;
					}
				}
				echo json_encode(true);
				
				}else{
				$UpdateDetails = array(
                "Lupdate"=>date('Y-m-d H:i:s'),
                "DistributorType"=>$distTypeStr,
                "narration"      =>$narration,
                "SchemeType"      =>$SchemeType,
                "StartDate"=>$FromDate,
                "EndDate"=>$ToDate,
                "UserID2"=>$this->session->userdata('username'),
                "StateID"=>$states,
                "status"=>1,
				"Approve"=>"N"
				);
				$this->db->where('PlantID', $selected_company);
				$this->db->where('FY', $FY);
				$this->db->where('SchemeID', $SchemeID);
				$this->db->update(db_prefix() . 'schememaster', $UpdateDetails);
				if($this->db->affected_rows()>0){
					$this->db->where('PlantID', $selected_company);
					$this->db->where('FY', $FY);
					$this->db->where('SchemeID', $SchemeID);
					$this->db->delete(db_prefix() . 'schemedetails');
					
					$Ord = 1;
					for($k=0; $k<$itemCount; $k++) {
						$ItemID = $ItemValArray[$k][0];
						$SlabCases = $ItemValArray[$k][1];
					$Disc = $ItemValArray[$k][2];
					$UnitDisc = $ItemValArray[$k][3];
					$status = $ItemValArray[$k][4];
					$Disc_type = $ItemValArray[$k][5];
					$FreeItem = $ItemValArray[$k][6];
						if($Disc_type == "disc"){
							$Discpkt = '0';
							$DiscPerc = $Disc;
						}
						if($Disc_type == "free_distribution"){
							$Discpkt = $UnitDisc;
							$DiscPerc = '0';
						}
						$InsertItem = array(
						"FY"=>$FY,
						"PlantID"=>$selected_company,
						"SchemeID"=>$SchemeID,
						"UserID2"=>$this->session->userdata('username'),
						"Lupdate"=>date('Y-m-d H:i:s'),
						"TransDate"=>$TransDate,
						"DistributorType"=>$distTypeStr,
						"ItemID"=>$ItemID,
						"FreeItemID"=>$FreeItem,
						"SuppliedIn"=>'CS',
						"SlabQty"=>$SlabCases,
						"Disc_type"=>$Disc_type,
						"Disc_pkt"=>$Discpkt,
						"DiscPerc"=>$DiscPerc,
						"DiscAmt"=>$DiscAmt,
						"StartDate"=>$FromDate,
						"EndDate"=>$ToDate,
						"ActYN"=>strtoupper($status),
						"Ordinalno"=>$Ord,
						"StateID"=>$states
                        );
                        if($ItemID == ""){
                            
							}else{
                            $this->db->insert(db_prefix() . 'schemedetails', $InsertItem);
                            $Ord++;
						}
					}
					echo json_encode(true);
					}else{
					echo json_encode(false);
				}
			}
			
		}
		
		// Approve Scheme 
		public function ApproveScheme()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$SchemeID = $this->input->post('SchemeID');
			$FromDate = to_sql_date($this->input->post('FromDate'))." 00:00:00";
			$ToDate = to_sql_date($this->input->post('ToDate'))." 23:59:59";
			$states = $this->input->post('states');
			$SchemeType = $this->input->post('SchemeType');
			$client_type = $this->input->post('client_type');
			$distTypeStr = implode(',', $client_type);
			
			$ItemSerializedArr = $this->input->post('ItemSerializedArr');
			$ItemValArray = json_decode($ItemSerializedArr, true);
			$itemCount = sizeof($ItemValArray);
			$SchemeDetails  = $this->SchemeMaster_model->GetSchemeByID($SchemeID);
			$TransDate = $SchemeDetails->TransDate;
			$UserID = $SchemeDetails->UserID;
			$UpdateDetails = array(
            "Lupdate"=>date('Y-m-d H:i:s'),
            "DistributorType"=>$distTypeStr,
            "SchemeType"=>$SchemeType,
            "StartDate"=>$FromDate,
            "EndDate"=>$ToDate,
            "UserID2"=>$this->session->userdata('username'),
            "StateID"=>$states,
            "status"=>1,
            "Approve"=>"Y"
			);
			$this->db->where('PlantID', $selected_company);
			$this->db->where('FY', $FY);
			$this->db->where('SchemeID', $SchemeID);
			$this->db->update(db_prefix() . 'schememaster', $UpdateDetails);
			$affectedrow = 0;
			if($this->db->affected_rows()>0){
				$affectedrow++;
				$this->db->where('PlantID', $selected_company);
				$this->db->where('FY', $FY);
				$this->db->where('SchemeID', $SchemeID);
				$this->db->delete(db_prefix() . 'schemedetails');
				$allItem = array();
				$Item_UnitDisc = array();
				$Ord = 1;
				for($k=0; $k<$itemCount; $k++) {
					$ItemID = $ItemValArray[$k][0];
					$SlabCases = $ItemValArray[$k][1];
					$Disc = $ItemValArray[$k][2];
					$UnitDisc = $ItemValArray[$k][3];
					$status = $ItemValArray[$k][4];
					$Disc_type = $ItemValArray[$k][5];
					$FreeItem = $ItemValArray[$k][6];
					if($Disc_type == "disc"){
						$Discpkt = '0';
						$DiscPerc = $Disc;
					}
					if($Disc_type == "free_distribution"){
						$Discpkt = $UnitDisc;
						$DiscPerc = '0';
					}
                    if($ItemID == ""){
                        
						}else{
                        $InsertItem = array(
						"FY"=>$FY,
						"PlantID"=>$selected_company,
						"SchemeID"=>$SchemeID,
						"UserID2"=>$this->session->userdata('username'),
						"Lupdate"=>date('Y-m-d H:i:s'),
						"TransDate"=>$TransDate,
						"DistributorType"=>$distTypeStr,
						"Disc_type"=>$Disc_type,
						"ItemID"=>$ItemID,
						"SuppliedIn"=>'CS',
						"SlabQty"=>$SlabCases,
						"DiscPerc"=>$DiscPerc,
						"Disc_pkt"=>$Discpkt,
						"FreeItemID"=>$FreeItem,
						"StartDate"=>$FromDate,
						"EndDate"=>$ToDate,
						"ActYN"=>strtoupper($status),
						"Ordinalno"=>$Ord,
						"StateID"=>$states
                        );
                        $this->db->insert(db_prefix() . 'schemedetails', $InsertItem);
                        $Ord++;
					}
				}
				
				echo json_encode($affectedrow);
				}else{
				echo json_encode(false);
			}  
		}
		
		public function ShowSchemeAmt()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$SchemeID = $this->input->post('SchemeID');
			//$FromDate = to_sql_date($this->input->post('FromDate'))." 00:00:00";
			//$ToDate = to_sql_date($this->input->post('ToDate'))." 23:59:59";
			$FromDate = date('Y-m-d')." 00:00:00";
			$ToDate = date('Y-m-d')." 23:59:59";
			$states = $this->input->post('states');
			$client_type = $this->input->post('client_type');
			
			$ItemSerializedArr = $this->input->post('ItemSerializedArr');
			$ItemValArray = json_decode($ItemSerializedArr, true);
			$itemCount = sizeof($ItemValArray);
			$allItem = array();
			$Item_UnitDisc = array();
			for($k=0; $k<$itemCount; $k++) {
				$ItemID = $ItemValArray[$k][0];
				$CaseQty = $ItemValArray[$k][2];
				$UnitDisc = $ItemValArray[$k][7];
				$SlabCases = $ItemValArray[$k][4];
				if($ItemID == ""){
					}else{
					array_push($allItem,$ItemID);
					$record = array(
                    "ItemID"=>$ItemID,
                    "DiscAmt"=>$UnitDisc,
                    "SlabQtyCases"=>$SlabCases,
                    "CaseQty"=>$CaseQty
					);
					array_push($Item_UnitDisc,$record);
				}
			}
			$ItemPartyWiseSale  = $this->SchemeMaster_model->ItemPartyWiseSale($allItem,$FromDate,$ToDate,$states,$client_type,$Item_UnitDisc);
            
			echo json_encode($ItemPartyWiseSale);
		}
		
		/* Get Show Result Export / ajax */
		public function ExportShowSchemeAmt()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				$selected_company = $this->session->userdata('root_company');
				$FY = $this->session->userdata('finacial_year');
				
				$SchemeID = $this->input->post('SchemeID');
				$FromDate = to_sql_date($this->input->post('FromDate'))." 00:00:00";
				$ToDate = to_sql_date($this->input->post('ToDate'))." 23:59:59";
				$states = $this->input->post('states');
				$client_type = $this->input->post('client_type');
				
				$ItemSerializedArr = $this->input->post('ItemSerializedArr');
				$ItemValArray = json_decode($ItemSerializedArr, true);
				$itemCount = sizeof($ItemValArray);
				$allItem = array();
				$Item_UnitDisc = array();
				for($k=0; $k<$itemCount; $k++) {
					$ItemID = $ItemValArray[$k][0];
					$CaseQty = $ItemValArray[$k][2];
					$UnitDisc = $ItemValArray[$k][7];
					$SlabCases = $ItemValArray[$k][4];
					if($ItemID == ""){
						}else{
						array_push($allItem,$ItemID);
						$record = array(
                        "ItemID"=>$ItemID,
                        "DiscAmt"=>$UnitDisc,
                        "SlabQtyCases"=>$SlabCases,
                        "CaseQty"=>$CaseQty
						);
						array_push($Item_UnitDisc,$record);
					}
				}
				$ItemPartyWiseSale  = $this->SchemeMaster_model->ItemPartyWiseSaleExport($allItem,$FromDate,$ToDate,$states,$client_type,$Item_UnitDisc);
				
				
				$company_detail = $this->SchemeMaster_model->get_company_detail();
				$writer = new XLSXWriter();
				
				
				$Discper = $Data['Discper'];
				$colspan = '6';
				
				
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$distTypeStr = implode("','", $client_type);
				$dateFilter = 'Scheme Report From '.$FromDate.' To '.$ToDate.' for State : '.$states.' , Dist. Type : '.$distTypeStr;
				$dateFilterArr = array($dateFilter,);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $dateFilterArr);
            	
				
				
				$set_col_tk = [];
				$set_col_tk["AccountID"] = 'AccountID';
				$set_col_tk["AccountName"] = 'Account Name';
				$set_col_tk["ItemID"] = 'ItemID';
				//$set_col_tk["ItemName"] = 'ItemName';
				$set_col_tk["SaleAmt"] = 'SaleAmt';
				$set_col_tk["BilledQty"] = 'BilledQty';
				$set_col_tk["SchemeAmt"] = 'SchemeAmt';
        		
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$SaleAmtSum = 0;
				$SchemeAmtSum = 0;
				$BilledQtySum = 0; 
                foreach ($ItemPartyWiseSale as $key => $value) {
                    $ShemeAmt = 0;
                    foreach($Item_UnitDisc as $value1){
                        $SlabQty = $value1["SlabQtyCases"] * $value1["CaseQty"];
                        if($value1["ItemID"] == $value["ItemID"] && $SlabQty <= $value["BilledQty"]){
                            $ShemeAmt = $value1["DiscAmt"] * $value["BilledQty"];
                            $SchemeAmtSum += $ShemeAmt;
						}
					}
                    if($ShemeAmt > 0){
                        $SaleAmtSum += $value["TaxableAmt"];
                        $BilledQtySum += $value["BilledQty"];
                        $list_add = [];
                        $list_add[] = $value["AccountID"];
                        $list_add[] = $value["company"];
                        $list_add[] = $value["ItemID"];
                        //$list_add[] = $value["description"];
                        $list_add[] = $value["TaxableAmt"];
                        $list_add[] = $value["BilledQty"];
                        $list_add[] = $ShemeAmt;
                        $writer->writeSheetRow('Sheet1', $list_add);
					}
				}
				
				// Footer Data
                $list_add = [];
                $list_add[] = 'Total';
                $list_add[] = '';
                $list_add[] = '';
                $list_add[] = number_format($SaleAmtSum, 2, '.', '');
                $list_add[] = number_format($BilledQtySum, 2, '.', '');
                $list_add[] = number_format($SchemeAmtSum, 2, '.', '');
                $writer->writeSheetRow('Sheet1', $list_add);
                
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        		foreach($files as $file){
        			if(is_file($file)) {
        				unlink($file); 
					}
				}
        		$filename = 'SchemeResult.xlsx';
        		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        		echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        		]);
        		die;
			}
		}
		
		/* Get Scheme Details by SchemeID / ajax */
		public function GetSchemeDetailByID()
		{
			$SchemeID  = $this->input->post('SchemeID');
			$order_by = $this->input->post('order_by');
			$DiscountDetails  = $this->SchemeMaster_model->GetSchemeDetailByID($SchemeID);
			echo json_encode($DiscountDetails);
		}
		
		// Upload approve latter 
		function ajax_upload()  
		{  
            $selected_company = $this->session->userdata('root_company');
            $FY = $this->session->userdata('finacial_year');
            $SchemeID = $this->input->post('EditSchemeID');;
            if ($_FILES['image_file']['name'] != '') {
                hooks()->do_action('before_upload_staff_profile_image');
                $path = get_upload_path_by_type('staff') . $SchemeID . '/';
                // Get the temp file path
                $tmpFilePath = $_FILES['image_file']['tmp_name'];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Getting file extension
                    $extension          = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
                    $allowed_extensions = [
					'jpg',
					'jpeg',
					'png',
                    ];
					
                    $allowed_extensions = hooks()->apply_filters('staff_profile_image_upload_allowed_extensions', $allowed_extensions);
					
                    if (!in_array($extension, $allowed_extensions)) {
                        set_alert('warning', _l('file_php_extension_blocked'));
						
                        return false;
					}
                    _maybe_create_upload_path($path);
                    $filename    = unique_filename($path, $_FILES['image_file']['name']);
                    $newFilePath = $path . '/' . $filename;
                    // Upload the file into the company uploads dir
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $CI                       = & get_instance();
                        $config                   = [];
                        $config['image_library']  = 'gd2';
                        $config['source_image']   = $newFilePath;
                        $config['new_image']      = 'thumb_' . $filename;
                        $config['maintain_ratio'] = true;
                        $config['width']          = hooks()->apply_filters('staff_profile_image_thumb_width', 820);
                        $config['height']         = hooks()->apply_filters('staff_profile_image_thumb_height', 820);
                        $CI->image_lib->initialize($config);
                        $CI->image_lib->resize();
                        $CI->image_lib->clear();
                        $config['image_library']  = 'gd2';
                        $config['source_image']   = $newFilePath;
                        $config['new_image']      = 'small_' . $filename;
                        $config['maintain_ratio'] = true;
                        $config['width']          = hooks()->apply_filters('staff_profile_image_small_width', 150);
                        $config['height']         = hooks()->apply_filters('staff_profile_image_small_height', 150);
                        $CI->image_lib->initialize($config);
                        $CI->image_lib->resize();
                        $CI->db->where('PlantID', $selected_company);
                        $CI->db->where('FY', $FY);
                        $CI->db->where('SchemeID', $SchemeID);
                        $CI->db->update(db_prefix().'schememaster', [
						'file_name' => $filename,
                        ]);
                        // Remove original image
                        unlink($newFilePath);
                        $link = site_url().'uploads/staff_profile_images/'.$SchemeID.'/small_'.$filename;
                        echo "<img src='$link' />";
					}
				}
			}
			
			
		}  
	}
?>