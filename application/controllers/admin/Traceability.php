<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Traceability extends AdminController
	{
		public function AddEditProductSrcDetails()
		{
		    if (!has_permission_new('Source_details', '', 'view')) {
				access_denied('AddEditProductSrcDetails'); 
			}
			
			$this->load->model('Traceability_model');
			$data['title']  = "Product & Source Details";
			$data['PoEntryDetails'] = $this->Traceability_model->GetPoEntryDetails();
			$data['table_data'] = $this->Traceability_model->GetTraceabilityDetails();
			
			$ID = $this->uri->segment(4);
			if($ID != "")
			{
			    $data['EditTraceabilitydetails'] = $this->Traceability_model->GetTraceabilityDetailsByID($ID);
			    $batchNo = '';
                if (!empty($data['EditTraceabilitydetails'])) {
                    $batchNo = $data['EditTraceabilitydetails']['BatchNo'];
                }
               
			    $data['NutritionalValueDetails'] = $this->Traceability_model->GetNutritionalValueDetails($batchNo);
			    $data['LabAnalysisDetails'] = $this->Traceability_model->GetLabanalysisDetails($batchNo);
			}
		    $this->load->view('admin/Traceability/AddEditProductSrcDetails',$data);
		}
		
		public function GetPoEntryDetailsByID()
		{
		    $this->load->model('Traceability_model');
		    $POEntryNo = $this->input->post('POEntryNo');
		    $PoDetailsByID = $this->Traceability_model->GetPoEntryDetailByID($POEntryNo);
		    
		    $PoHistoryDetails = $this->Traceability_model->GetPoHistoryByPONumber($PoDetailsByID->PO_Number);
                          
            $response = [
                'po_details'      => $PoDetailsByID,
                'history_details' => $PoHistoryDetails
            ];  
		   
		    echo json_encode($response);
            exit;
		}
		
		public function GetBatchNoByItem()
		{
		    $this->load->model('Traceability_model');
		    $ItemID = $this->input->post('ItemID');
		    $PO_No = $this->input->post('PO_No');
		    $ItemDetails = $this->Traceability_model->GethistoryDetails($ItemID,$PO_No);
		    
		    echo json_encode($ItemDetails);
            exit;
		}
		
		public function SaveTraceabilityDetails()
		{
		    $selected_company = $this->session->userdata('root_company');
            $FY = $this->session->userdata('finacial_year');
                
		    $postData = $this->input->post();
		    $HarvestDate  = DateTime::createFromFormat('d/m/Y', $postData['HarvestDate']);
		    $DispatchDate = DateTime::createFromFormat('d/m/Y', $postData['DispatchDate']);
		    $PackDate = DateTime::createFromFormat('d/m/Y', $postData['PackingDate']);
		    $BeforeDate = DateTime::createFromFormat('d/m/Y', $postData['BeforeDate']);
		    
		    $labels = $postData['nutrallabel']; 
            $values = $postData['nutrivalue']; 
           
            $qcparaid = $postData['qcparaid'];
            $qcresult = $postData['qcresult'];
		    
		    $insert_details = array(
		            'PlantID'=>$selected_company,
		            'FY'=>$FY,
		            'PurchID'=>$postData['POEntryNo'],
		            'ItemID'=>$postData['ItemID'],
		            'BatchNo'=>$postData['BatchNo'],
		            'TransDate'=>date('Y-m-d H:i:s'),
		            'Product_details'=>trim($postData['ProductDetails']),
		            'BotanicalSrc'=>trim($postData['BotanicalSrc']),
		            'Floraltype'=>trim($postData['FloralType']),
		            'species'=>trim($postData['BeeSpecies']),
		            'Region'=>trim($postData['SrcRegion']),
		            'HarvestCordinate'=>trim($postData['HarvestCordinates']),
		            'Altitude'=>trim($postData['Altitude']),
		            'Soiltype'=>trim($postData['SoilType']),
		            'HarvestSeason'=>trim($postData['HarvestSeason']),
		            'HarvestDate'=>$HarvestDate ? $HarvestDate->format('Y-m-d') : null,
		            'BeekeeperCluster'=>trim($postData['Cluster']),
		            'Practice'=>trim($postData['Practice']),
		             //processing
		            'ExtractionStorage'=>trim($postData['ExtractionStorage']),
		            'HeatBoil'=>trim($postData['HeatBoil']),
		            'Aroma'=>trim($postData['Aroma']),
		            'TestingLab'=>trim($postData['TestingLab']),
		            'ExtractionMethod'=>trim($postData['ExtractionMethod']),
		            'FilterationMethod'=>trim($postData['FilterationMethod']),
		            //shipping
		            'DispatchDate'=>$DispatchDate ? $DispatchDate->format('Y-m-d') : null,
		            'Origin'=>trim($postData['Origin']),
		            'Destination'=>trim($postData['Destination']),
		            'ShipPartner'=>trim($postData['Shippartner']),
		            'ShipMode'=>trim($postData['ShipMode']),
		            'TransitRoute'=>trim($postData['Transitroute']),
		            'TransitDuration'=>trim($postData['TransitDuration']),
		            'ArrivalWarehouse'=>trim($postData['ArrivalWarehouse']),
		            'TrackID'=>trim($postData['TrackID']),
		            'TransportPack'=>trim($postData['Transportpack']),
		            'UserID'=>$this->session->userdata('username'),
		            //packaging
		            'PackDate'=>$PackDate ? $PackDate->format('Y-m-d') : null,
		            'PackUnit'=>trim($postData['Unit']),
		            'PackLocation'=>trim($postData['location']),
		            'PackType'=>trim($postData['PackType']),
		            'ShelfLife'=>trim($postData['ShelfLife']),
		            'StorageCond'=>trim($postData['StorageCondition']),
		            'beforedate'=>$BeforeDate ? $BeforeDate->format('Y-m-d') : null,
		            'labeldesc'=>trim($postData['labeldes']),
		            'packmaterial'=>trim($postData['packmaterial']),
		        );
		        
		        if (!empty($_FILES['trace_pdf']['name'])) {
                    $file = $_FILES['trace_pdf'];
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    
                    $batchNo = preg_replace('/[^A-Za-z0-9_-]/', '_', $postData['BatchNo']);
                    $fileName = 'trace_' . $batchNo . '_' . time() . '.' . $ext;
                    
                    $folderPath = FCPATH . 'uploads/trace-products/' . $batchNo . '/';
                    if (!is_dir($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
            
                    $filePath = $folderPath . $fileName;
            
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        $insert_details['trace_pdf'] = $batchNo . '/' . $fileName;
                    } else {
                        log_message('error', 'Failed to upload PDF for BatchNo: ' . $postData['BatchNo']);
                    }
                }
                
                
                if (!empty($_FILES['img_two']['name'])) {

                    $img = $_FILES['img_two'];
                    $imgExt = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
                    
                    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                
                    if (in_array($imgExt, $allowedExt)) {
                
                        $batchNo = preg_replace('/[^A-Za-z0-9_-]/', '_', $postData['BatchNo']);
                        $imgName = 'journey_' . $batchNo . '_' . time() . '.' . $imgExt;
                
                        $imgFolder = FCPATH . 'uploads/trace-products/' . $batchNo . '/images/';
                
                        if (!is_dir($imgFolder)) {
                            mkdir($imgFolder, 0755, true);
                        }
                
                        $imgPath = $imgFolder . $imgName;
                
                        if (move_uploaded_file($img['tmp_name'], $imgPath)) {
                            $insert_details['journeyproductimg'] =
                                $batchNo . '/images/' . $imgName;
                        } else {
                            log_message('error', 'Image upload failed for batch: ' . $batchNo);
                        }
                
                    } else {
                        log_message('error', 'Invalid image format for batch: ' . $postData['BatchNo']);
                    }
                }
                
                $fssai = $this->uploadTraceFile('fssai', $postData['BatchNo']);
                if ($fssai) {
                    $insert_details['FSSAI_img'] = $fssai;
                }
                $usfda = $this->uploadTraceFile('usfda', $postData['BatchNo']);
                if ($usfda) {
                    $insert_details['USFDA_img'] = $usfda;
                }
                $foodsafety = $this->uploadTraceFile('foodsafetycertificate', $postData['BatchNo']);
                if ($foodsafety) {
                    $insert_details['Food_Safety_img'] = $foodsafety;
                }
                $apeda = $this->uploadTraceFile('apeda', $postData['BatchNo']);
                if ($apeda) {
                    $insert_details['Apeda_img'] = $apeda;
                }
                $goods = $this->uploadTraceFile('impexpgoods', $postData['BatchNo']);
                if ($goods) {
                    $insert_details['ImportExport_GoodsImg'] = $goods;
                }
                
		        $Insert = $this->db->insert(db_prefix() . 'Traceability', $insert_details);
		        
		        if($Insert)
		        {
		            if (!empty($labels)) {
    
                        $this->db->where('BatchNo', $postData['BatchNo']);
                        $this->db->delete(db_prefix() . 'traceabilityNutritionalValue');
                        
                        foreach ($labels as $i => $label) {
                    
                            $label = trim($label);
                            $value = trim($values[$i] ?? '');
                            
                            if ($label === '' && $value === '') {
                                continue;
                            }
                    
                            $Updatenutritional_Details = [
                                'BatchNo' => $postData['BatchNo'],
                                'name'    => $label,
                                'value'   => $value
                            ];
                    
                            $this->db->insert(
                                db_prefix() . 'traceabilityNutritionalValue',
                                $Updatenutritional_Details
                            );
                        }
                    }
                    
                    if (!empty($qcparaid))
                    {

                        for ($i = 0; $i < count($qcparaid); $i++) {
                    
                            if (!empty($qcresult[$i])) {
                    
                                $insertData = [
                                    
                                    'BatchNo' => $postData['BatchNo'],
                                    'Name'    => $qcparaid[$i],
                                    'Value'   =>  $qcresult[$i]
                                ];
                    
                                $this->db->insert('tblLabanalysisReport', $insertData);
                            }
                        }
                    }
		        }
		       
		        echo json_encode([
                    'status' => $Insert ? true : false
                ]);
                exit;
		}
		
		public function UpdateTraceabilityDetails()
		{
		    $selected_company = $this->session->userdata('root_company');
            $FY = $this->session->userdata('finacial_year');
                
		    $postData = $this->input->post();
		    
		    $labels = $postData['nutrallabel']; 
            $values = $postData['nutrivalue']; 
            
            $qcparaid = $postData['qcparaid'];
            $qcresult = $postData['qcresult'];
		    
		    $HarvestDate  = DateTime::createFromFormat('d/m/Y', $postData['HarvestDate']);
		    $DispatchDate = DateTime::createFromFormat('d/m/Y', $postData['DispatchDate']);
		    $PackDate = DateTime::createFromFormat('d/m/Y', $postData['PackingDate']);
		    $BeforeDate = DateTime::createFromFormat('d/m/Y', $postData['BeforeDate']);
		    
		    $Update_details = array(
		            'Product_details'=>trim($postData['ProductDetails']),
		            'BotanicalSrc'=>trim($postData['BotanicalSrc']),
		            'Floraltype'=>trim($postData['FloralType']),
		            'species'=>trim($postData['BeeSpecies']),
		            'Region'=>trim($postData['SrcRegion']),
		            'HarvestCordinate'=>trim($postData['HarvestCordinates']),
		            'Altitude'=>trim($postData['Altitude']),
		            'Soiltype'=>trim($postData['SoilType']),
		            'HarvestSeason'=>trim($postData['HarvestSeason']),
		            'HarvestDate'=>$HarvestDate ? $HarvestDate->format('Y-m-d') : null,
		            'BeekeeperCluster'=>trim($postData['Cluster']),
		            'Practice'=>trim($postData['Practice']),
		             //processing
		            'ExtractionStorage'=>trim($postData['ExtractionStorage']),
		            'HeatBoil'=>trim($postData['HeatBoil']),
		            'Aroma'=>trim($postData['Aroma']),
		            'TestingLab'=>trim($postData['TestingLab']),
		            'ExtractionMethod'=>trim($postData['ExtractionMethod']),
		            'FilterationMethod'=>trim($postData['FilterationMethod']),
		            //shipping
		            'DispatchDate'=>$DispatchDate ? $DispatchDate->format('Y-m-d') : null,
		            'Origin'=>trim($postData['Origin']),
		            'Destination'=>trim($postData['Destination']),
		            'ShipPartner'=>trim($postData['Shippartner']),
		            'ShipMode'=>trim($postData['ShipMode']),
		            'TransitRoute'=>trim($postData['Transitroute']),
		            'TransitDuration'=>trim($postData['TransitDuration']),
		            'ArrivalWarehouse'=>trim($postData['ArrivalWarehouse']),
		            'TrackID'=>trim($postData['TrackID']),
		            'TransportPack'=>trim($postData['Transportpack']),
		            //packaging
		            'PackDate'=>$PackDate ? $PackDate->format('Y-m-d') : null,
		            'PackUnit'=>trim($postData['Unit']),
		            'PackLocation'=>trim($postData['location']),
		            'PackType'=>trim($postData['PackType']),
		            'ShelfLife'=>trim($postData['ShelfLife']),
		            'StorageCond'=>trim($postData['StorageCondition']),
		            'beforedate'=>$BeforeDate ? $BeforeDate->format('Y-m-d') : null,
		            'labeldesc'=>trim($postData['labeldes']),
		            'packmaterial'=>trim($postData['packmaterial']),
		            
		            'UserID2'=>$this->session->userdata('username'),
		            'Lupdate'=>date('Y-m-d H:i:s'),
		        );
		        
		        if (!empty($_FILES['trace_pdf']['name'])) {
                    
                    $batchNo = preg_replace('/[^A-Za-z0-9_-]/', '_', $postData['BatchNo']);
                    
                    $folderPath = FCPATH . 'uploads/trace-products/' . $batchNo . '/';
                    if (!is_dir($folderPath)) {
                        mkdir($folderPath, 0755, true); 
                    }
                    
                    $fileName = 'trace_' . time() . '.pdf';
                    $filePath = $folderPath . $fileName;
                    
                    if (move_uploaded_file($_FILES['trace_pdf']['tmp_name'], $filePath)) {
                        
                        $Update_details['trace_pdf'] = $batchNo . '/' . $fileName;
                    } else {
                        log_message('error', 'Failed to save PDF for BatchNo: ' . $postData['BatchNo']);
                    }
                }
                
                
                if (!empty($_FILES['img_two']['name'])) {

                    $img = $_FILES['img_two'];
                    $imgExt = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
                    
                    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                
                    if (in_array($imgExt, $allowedExt)) {
                
                        $batchNo = preg_replace('/[^A-Za-z0-9_-]/', '_', $postData['BatchNo']);
                        $imgName = 'journey_' . $batchNo . '_' . time() . '.' . $imgExt;
                
                        $imgFolder = FCPATH . 'uploads/trace-products/' . $batchNo . '/images/';
                
                        if (!is_dir($imgFolder)) {
                            mkdir($imgFolder, 0755, true);
                        }
                
                        $imgPath = $imgFolder . $imgName;
                
                        if (move_uploaded_file($img['tmp_name'], $imgPath)) {
                            $Update_details['journeyproductimg'] =
                                $batchNo . '/images/' . $imgName;
                        } else {
                            log_message('error', 'Image upload failed for batch: ' . $batchNo);
                        }
                
                    } else {
                        log_message('error', 'Invalid image format for batch: ' . $postData['BatchNo']);
                    }
                }
                
                $fssai = $this->uploadTraceFile('fssai', $postData['BatchNo']);
                if ($fssai) {
                    $Update_details['FSSAI_img'] = $fssai;
                }
                $usfda = $this->uploadTraceFile('usfda', $postData['BatchNo']);
                if ($usfda) {
                    $Update_details['USFDA_img'] = $usfda;
                }
                $foodsafety = $this->uploadTraceFile('foodsafetycertificate', $postData['BatchNo']);
                if ($foodsafety) {
                    $Update_details['Food_Safety_img'] = $foodsafety;
                }
                $apeda = $this->uploadTraceFile('apeda', $postData['BatchNo']);
                if ($apeda) {
                    $Update_details['Apeda_img'] = $apeda;
                }
                $goods = $this->uploadTraceFile('impexpgoods', $postData['BatchNo']);
                if ($goods) {
                    $Update_details['ImportExport_GoodsImg'] = $goods;
                }
            
		       $this->db->where('id', $postData['ID']);
		       $this->db->where('PurchID', $postData['POEntryNo']);
		       $this->db->where('ItemID', $postData['ItemID']);
		       $this->db->where('BatchNo', $postData['BatchNo']);
		       $Update = $this->db->update(db_prefix() . 'Traceability', $Update_details);
		       
		       if($Update)
		       {
		           if (!empty($labels)) {
    
                        $this->db->where('BatchNo', $postData['BatchNo']);
                        $this->db->delete(db_prefix() . 'traceabilityNutritionalValue');
                        
                        foreach ($labels as $i => $label) 
                        {
                    
                            $label = trim($label);
                            $value = trim($values[$i] ?? '');
                            
                            if ($label === '' && $value === '') {
                                continue;
                            }
                    
                            $Updatenutritional_Details = [
                                'BatchNo' => $postData['BatchNo'],
                                'name'    => $label,
                                'value'   => $value
                            ];
                    
                            $this->db->insert(
                                db_prefix() . 'traceabilityNutritionalValue',
                                $Updatenutritional_Details
                            );
                        }
                    }
                    
                    if (!empty($qcparaid))
                    {
                        $this->db->where('BatchNo', $postData['BatchNo']);
                        $this->db->delete(db_prefix() . 'LabanalysisReport');
                        for ($i = 0; $i < count($qcparaid); $i++) {
                    
                            if (!empty($qcresult[$i])) {
                    
                                $insertData = [
                                    
                                    'BatchNo' => $postData['BatchNo'],
                                    'Name'    => $qcparaid[$i],
                                    'Value'   =>  $qcresult[$i]
                                ];
                    
                                $this->db->insert('tblLabanalysisReport', $insertData);
                            }
                        }
                    }
		       }
		       
		       echo json_encode([
                    'status' => $Update ? true : false
                ]);
                exit;
		}
		
		private function uploadTraceFile($fieldName, $batchNo)
        {
            if (!empty($_FILES[$fieldName]['name'])) {
        
                $file = $_FILES[$fieldName];
                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
                $allowedExt = ['jpg','jpeg','png','webp','pdf','jfif'];
        
                if (!in_array($fileExt, $allowedExt)) {
                    log_message('error', 'Invalid file format for '.$fieldName);
                    return false;
                }
        
                // Secure batch name
                $batchNo = preg_replace('/[^A-Za-z0-9_-]/', '_', $batchNo);
        
                $folderPath = FCPATH . 'uploads/trace-products/' . $batchNo . '/certificates/';
                if (!is_dir($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
        
                $fileName = $fieldName . '_' . time() . '.' . $fileExt;
                $filePath = $folderPath . $fileName;
        
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    return $batchNo . '/certificates/' . $fileName;
                }
        
                log_message('error', 'Upload failed for '.$fieldName);
            }
        
            return false;
        }
		
		public function save_qr()
        {
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            $image   = $this->input->post('image', false);
            $batchNo = $this->input->post('batchNo', true);
            $id = $this->input->post('id');
        
            if (!$image || !$batchNo) {
                echo json_encode(['status' => false, 'message' => 'Invalid request']);
                exit;
            }

            $batchNo = preg_replace('/[^A-Za-z0-9_-]/', '_', $batchNo);
        
            $folder = FCPATH . 'uploads/trace-products/' . $batchNo . '/';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            
            $image = preg_replace('#^data:image/png;base64,#', '', $image);
            $image = str_replace(' ', '+', $image);
        
            $imageData = base64_decode($image);

            if ($imageData === false || strlen($imageData) < 100) {
                echo json_encode(['status' => false, 'message' => 'Image decode failed']);
                exit;
            }
            
            $fileName = 'traceQr_' . $batchNo . '.png';
            $filePath = $folder . $fileName;
           
            file_put_contents($filePath, $imageData, LOCK_EX);
            
            $updatedetails = array(
                    'IsQrGenerate'=>1,
                    'Lupdate'=> date('Y-m-d H:i:s')
                );
            $this->db->where('id', $id);
            $this->db->where('BatchNo', $batchNo);
            $this->db->update(db_prefix() . 'Traceability', $updatedetails);
        
            echo json_encode([
                'status'  => true,
                'message' => 'QR code saved successfully',
                'file'    => 'uploads/trace-products/' . $batchNo . '/' . $fileName
            ]);
            exit;
        }
        
        public function QcParameterMaster()
        {
            if (!has_permission_new('qcparameter_master', '', 'view')) {
				access_denied('QcParameterMaster'); 
			}
			
			$this->load->model('Traceability_model');
			$data['title']  = "Qc Parameter Master";
			$data['table_data'] = $this->Traceability_model->GetAllQcParameters();
			
            $this->load->view('admin/Traceability/QcParameterMaster',$data);
        }
        
        public function GetParameterDetailsByID()
        {
            $this->load->model('Traceability_model');
		    $ID = $this->input->post('ID');
		    $ParameterDetails = $this->Traceability_model->GetParameterDetailsByID($ID);
		   
		    echo json_encode($ParameterDetails);
            exit;
        }
        
        public function SaveQcParameter()
        {
            $this->load->model('Traceability_model');
            $ParameterName = $this->input->post('ParameterName');
            $insert_details = array(
                    'ParameterName'=>$ParameterName,
		            'UserID'=>$this->session->userdata('username'),
		            'TransDate'=>date('Y-m-d H:i:s'),
                );
            $Insert = $this->db->insert(db_prefix() . 'QcMaster', $insert_details);
            
            echo json_encode([
                'status' => $Insert ? true : false
            ]);
            exit;
        }
        
        public function UpdateQcParameter()
        {
            $this->load->model('Traceability_model');
            $ParameterName = $this->input->post('ParameterName');
            $ID = $this->input->post('ID');
            
            $update_array = array(
                    'ParameterName'=>$ParameterName,
                );
            $this->db->where('id', $ID); 
            $Update = $this->db->update(db_prefix() . 'QcMaster', $update_array);
            
            echo json_encode([
                'status' => $Update ? true : false
            ]);
            exit;
        }
	}