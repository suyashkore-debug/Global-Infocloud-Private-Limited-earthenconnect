<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class UserApp_Controller extends ClientsController {

    public function __construct() {
        parent::__construct();
        hooks()->do_action('clients_authentication_constructor', $this);
        $this->load->helper(array('form', 'url', 'file'));
        //$this->load->model('BuisnessModel');
        $this->load->library('upload');
    }
    
//========================= Vendor Login API ===================================
    public function VendorLOGINAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "mobile"=>$decode['mobile'],
                    "password"=>$decode['password']
                );
                $response=$this->VendorLOGIN($data);
            }
        }
        echo json_encode($response);    
    }
    
    public function VendorLOGIN($params=FALSE)
    {   
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->VendorLOGIN($params['mobile'],$params['password']);
        return $success; 
    } 
    
//====================== Vendor User Logout ====================================
    public function VendorlogoutAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "AccountID"=>$decode['AccountID'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->Vendorlogout($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function Vendorlogout($params=FALSE)
    {
        $Clientdata =array(
            "login_token"=>NULL
        );
        
        $this->db->where('phonenumber', $params['mobile']);
        $this->db->where('login_token', $params['login_token']);
        $this->db->update(db_prefix().'contacts', $Clientdata);
        if($this->db->affected_rows() > 0){
            $response = array("status"=>true,"message"=>"User Logout Successfully");
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }

//====================== Vendor Add/Edit Land ==================================
    public function AddEditLandAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "AccountID"=>$decode['AccountID'],
                        "login_token"=>$decode['login_token'],
                        "land_id"=>$decode['land_id'],
                        "LandName"=>$decode['LandName'],
                        "SurveyNo"=>$decode['SurveyNo'],
                        "Area"=>$decode['Area'],
                        "LandUnit"=>$decode['LandUnit'],
                        "SoilType"=>$decode['SoilType'],
                        "WaterQuality"=>$decode['WaterQuality'],
                        "IrrigationResource"=>$decode['IrrigationSource'],
                        "latitude"=>$decode['latitude'],
                        "longitude"=>$decode['longitude'],
                    );
                    $response = $this->AddEditLand($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function AddEditLand($params=FALSE)
    {
        $landdata =array(
            "AccountID"=>$params['AccountID'],
            "LandName"=>$params['LandName'],
            "SurveyNo"=>$params['SurveyNo'],
            "latitude"=>$params['latitude'],
            "longitude"=>$params['longitude'],
            "Area"=>$params['Area'],
            "LandUnit"=>$params['LandUnit'],
            "SoilType"=>$params['SoilType'],
            "WaterQuality"=>$params['WaterQuality'],
            "IrrigationResource"=>$params['IrrigationResource'],
            "UserID"=>$params['AccountID'],
            "TransDate"=>date("Y-m-d H:i:s")
        );
        if($params['land_id']){
            $this->db->WHERE(db_prefix().'LandMaster.id', $params['land_id']);
            $this->db->update(db_prefix().'LandMaster', $landdata);
            $msg = "Land Updated Successfully";
        }else{
            $this->db->insert(db_prefix().'LandMaster', $landdata);
            $msg = "Land Added Successfully";
        }
        if($this->db->affected_rows() > 0){
            $response = array("status"=>true,"message"=>$msg);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
    
//====================== Crop Add/Edit  ========================================
    public function AddEditCropAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "AccountID"=>$decode['AccountID'],
                        "login_token"=>$decode['login_token'],
                        "LandID"=>$decode['LandID'],
                        "ItemSubGroupID"=>$decode['CropCategoryID'],
                        "ItemID"=>$decode['CropMasterID'],
                        "crop_id"=>$decode['crop_id'],
                        "PlantingDate"=>$decode['PlantingDate'],
                        "BioFertilizerName"=>$decode['BioFertilizerName'],
                        "BioFertilizerBrand"=>$decode['BioFertilizerBrand'],
                        "BioFertilizerQty"=>$decode['BioFertilizerQty'],
                        "BioFertilizerUnit"=>$decode['BioFertilizerUnit'],
                        "PesticideName"=>$decode['PesticideName'],
                        "PesticideBrand"=>$decode['PesticideBrand'],
                        "PesticideQty"=>$decode['PesticideQty'],
                        "PesticideUnit"=>$decode['PesticideUnit'],
                    );
                    $response = $this->AddEditCrop($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function AddEditCrop($params=FALSE)
    {
        $Cropdata =array(
            "AccountID"=>$params['AccountID'],
            "LandID"=>$params['LandID'],
            "ItemSubGroupID"=>$params['ItemSubGroupID'],
            "ItemID"=>$params['ItemID'],
            "PlantingDate"=>$params['PlantingDate'],
            "BioFertilizerName"=>$params['BioFertilizerName'],
            "BioFertilizerBrand"=>$params['BioFertilizerBrand'],
            "BioFertilizerQty"=>$params['BioFertilizerQty'],
            "BioFertilizerUnit"=>$params['BioFertilizerUnit'],
            "PesticideName"=>$params['PesticideName'],
            "PesticideBrand"=>$params['PesticideBrand'],
            "PesticideQty"=>$params['PesticideQty'],
            "PesticideUnit"=>$params['PesticideUnit'],
        );
        if($params['crop_id']){
            $Cropdata["UserID2"] = $params['AccountID'];
            $Cropdata["LupDate"] = date("Y-m-d H:i:s");
            $this->db->WHERE(db_prefix().'CropRecord.id', $params['crop_id']);
            if($this->db->update(db_prefix().'CropRecord', $Cropdata)){
                $msg = "Land Updated Successfully";
                $response = array("status"=>true,"message"=>$msg);
            }else{
                $response = array("status"=>false,"message"=>"Something Went Wrong");
            }
        }else{
            $Cropdata["UserID"] = $params['AccountID'];
            $Cropdata["TransDate"] = date("Y-m-d H:i:s");
            if($this->db->insert(db_prefix().'CropRecord', $Cropdata)){
                $inserted_id = $this->db->insert_id();
                $nextNumber = $this->GetNextBatchNumber($params['ItemID']);
                $BatchNo = $params['ItemID'].date("Y").date("m").str_pad($nextNumber->BatchNo,4,"0",STR_PAD_LEFT);
                $this->db->WHERE(db_prefix().'CropRecord.id', $inserted_id);
                $this->db->update(db_prefix().'CropRecord', ["BatchNo"=>$BatchNo]);
                $NextNo =$nextNumber->BatchNo + 1;
                $this->UpdatenextBatchNumber($params['ItemID'],$NextNo);
                $msg = "Crop Added Successfully";
                $response = array("status"=>true,"message"=>$msg);
            }else{
                $response = array("status"=>false,"message"=>"Something Went Wrong");
            }
        }
        return $response; 
    }
    
    public function GetNextBatchNumber($ItemID)
    {
        $this->db->select('tblitems.*');
        $this->db->where('tblitems.PlantID',1);
        $this->db->where('tblitems.item_code',$ItemID);
        $ItemDetails = $this->db->get('tblitems')->row();
        return $ItemDetails; 
    }
    
    public function UpdatenextBatchNumber($ItemID,$NextNo)
    {
        $this->db->where('tblitems.PlantID',1);
        $this->db->where('tblitems.item_code',$ItemID);
        $this->db->update(db_prefix().'items', ["BatchNo"=>$NextNo]);
    }

//====================== Vendor Get Land =======================================
    public function GetMyLandAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "AccountID"=>$decode['AccountID'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->GetMyLand($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetMyLand($params=FALSE)
    {
        $this->db->select('tblLandMaster.*,tblLandUnit.Name AS LandUnitName,tblSoilType.Name As SoilTypeName,tblWaterQuality.Name AS WaterQualityName,
        tblIrrigationResource.Name As IrrigationResourceName');
        $this->db->where('tblLandMaster.AccountID', $params['AccountID']);
        $this->db->join('tblLandUnit','tblLandUnit.id = tblLandMaster.LandUnit',"LEFT");
        $this->db->join('tblSoilType','tblSoilType.id = tblLandMaster.SoilType',"LEFT");
        $this->db->join('tblWaterQuality','tblWaterQuality.id = tblLandMaster.WaterQuality',"LEFT");
        $this->db->join('tblIrrigationResource','tblIrrigationResource.id = tblLandMaster.IrrigationResource',"LEFT");
        $LandList = $this->db->get('tblLandMaster')->result_array();
        if($LandList){
            $response = array("status"=>true,"message"=>"Land List","LandList"=>$LandList);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
//======================== Get Area type =======================================
    public function LandUnitAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->GetLandUnit($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetLandUnit($params=FALSE)
    {
        $this->db->select('tblLandUnit.*');
        $LandUnitList = $this->db->get('tblLandUnit')->result_array();
        if($LandUnitList){
            $response = array("status"=>true,"message"=>"Land Unit List","LandUnitList"=>$LandUnitList);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
    
//======================== Get Soil Type =======================================
    public function SoilTypeAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->GetSoilType($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetSoilType($params=FALSE)
    {
        $this->db->select('tblSoilType.*');
        $SoilTypeList = $this->db->get('tblSoilType')->result_array();
        if($SoilTypeList){
            $response = array("status"=>true,"message"=>"Soil Type List","SoilTypeList"=>$SoilTypeList);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
    
//======================== Get Water Quality Type ==============================
    public function WaterQualityAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->GetWaterQuality($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetWaterQuality($params=FALSE)
    {
        $this->db->select('tblWaterQuality.*');
        $WaterQualityList = $this->db->get('tblWaterQuality')->result_array();
        if($WaterQualityList){
            $response = array("status"=>true,"message"=>"Water Quality List","WaterQualityList"=>$WaterQualityList);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
//======================== Get Irrigation Resources ============================
    public function IrrigationResourcesAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->GetIrrigationResources($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetIrrigationResources($params=FALSE)
    {
        $this->db->select('tblIrrigationResource.*');
        $IrrigationResourcesList = $this->db->get('tblIrrigationResource')->result_array();
        if($IrrigationResourcesList){
            $response = array("status"=>true,"message"=>"Irrigation Resources List","IrrigationResourcesList"=>$IrrigationResourcesList);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }

//======================== Get My Crop List ====================================
    public function ListOfCropAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                        "AccountID"=>$decode['AccountID']
                    );
                    $response = $this->ListOfCrop($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function ListOfCrop($params=FALSE)
    {
        $this->db->select('CR.id,CR.BatchNo,CR.TransDate,CR.LandID,tblLandMaster.LandName,CR.ItemSubGroupID,tblitems_sub_groups.name AS CategoryName,CR.ItemID,tblitems.description,
        CR.PlantingDate,CR.BioFertilizerName,CR.BioFertilizerBrand,CR.BioFertilizerQty,CR.BioFertilizerUnit,
        CR.PesticideName,CR.PesticideBrand,CR.PesticideQty,CR.PesticideUnit');
        $this->db->from('tblCropRecord AS CR');
        $this->db->join(db_prefix() . 'LandMaster', db_prefix() . 'LandMaster.id = CR.LandID');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = CR.ItemID');
        $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = CR.ItemSubGroupID');
        $this->db->where('CR.AccountID',$params["AccountID"]);
        $this->db->where('tblitems.PlantID',1);
        $CropList = $this->db->get()->result_array();
        if($CropList){
            $response = array("status"=>true,"message"=>"Crop List","CropList"=>$CropList);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
//======================== Get Crop Category ===================================
    public function GetCropCategoryAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                    );
                    $response = $this->GetCropCategory($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetCropCategory($params=FALSE)
    {
        $this->db->select('tblitems_sub_groups.*');
        $CropCategoryMaster = $this->db->get('tblitems_sub_groups')->result_array();
        if($CropCategoryMaster){
            $response = array("status"=>true,"message"=>"Crop Category List","CropCategoryList"=>$CropCategoryMaster);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
    
//======================== Get Crop Master By Category =========================
    public function GetCropMasterAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $checkLoginTokan = $this->CheckVendorLoginTokan($decode['login_token'],$decode['mobile']);
                if($checkLoginTokan){
                    $data = array(
                        "mobile"=>$decode['mobile'],
                        "login_token"=>$decode['login_token'],
                        "Category_id"=>$decode['Category_id'],
                    );
                    $response = $this->GetCropMaster($data);
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number","checkLoginTokan"=>$checkLoginTokan);
                }
            }
        }
        echo json_encode($response);    
    }
    
    public function GetCropMaster($params=FALSE)
    {
        $this->db->select('tblitems.*');
        $this->db->where('tblitems.PlantID',1);
        $this->db->where('tblitems.subgroup_id',$params["Category_id"]);
        $CropMaster = $this->db->get('tblitems')->result_array();
        if($CropMaster){
            $response = array("status"=>true,"message"=>"Crop Master List","CropList"=>$CropMaster);
        }else{
            $response = array("status"=>false,"message"=>"Something Went Wrong");
        }
        return $response; 
    }
    
//======================== Check Vendor Login Token ============================
    public function CheckVendorLoginTokan($login_token,$phonenumber) 
    {
        $this->db->where('phonenumber', $phonenumber);
        $this->db->where('login_token', $login_token);
        $UserDetails = $this->db->get('tblcontacts')->row_array();
        return $UserDetails;
    }
    
//========================= Login API ==========================================
    public function loginAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "mobile"=>$decode['mobile'],
                    "password"=>$decode['password'],
                    "staff"=>$decode['staff']
                );
                $response=$this->login($data);
            }
        }
        echo json_encode($response);    
    }
    
    public function login($params=FALSE)
    {   
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->login(
            $params['mobile'],
            $params['password'],
            $params['staff']
        );
        return $success; 
    } 
    
    public function auto_end_day_cron($param=FALSE) {
         $cur_date = date('Y-m-d');
         //$yesterday_date = date('Y-m-d',strtotime("-1 days"));
        $this->db->where('type_check', 1);
        $this->db->where('date', $cur_date);
        $tracking = $this->db->get(db_prefix().'check_in_out_app2')->result_array();
        /*$time = '23:59:00';
        
 echo date('H:i:s');
 $time = '23:59:00';

        */
        echo "<pre>";
        print_r($tracking);
       /* foreach ($tracking as $key => $value) {
            # code...
            
                
                $this->db->where('date', $value['date']);
                $this->db->where('staff_id', $value['staff_id']);
                $this->db->update(db_prefix().'check_in_out_app2', [
                'check_out' => serialize(date('H:i:s')),
                'type_check' => '2',
                'check_out_loc'      => $value['check_in_loc'],
                'check_out_loc_name'      => $value['check_in_loc_name'],
                ]);
             
        }*/
    }
    
    public function Dashboard($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id']
                                      );
                            $response=$this->Get_dashboard_status($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Asigned_company($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id']
                                      );
                            $response=$this->Get_assigned_company($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_StateAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            /*$data=array(
                                        "customer_type"=>$decode['customer_type']
                                      );*/
                            $response=$this->Get_statelist();
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function get_targetAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                      );
                            $response=$this->Get_target($data);
                }
            }
        
        echo json_encode($response);    
    }
    
    	public function get_achievementAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                               "staff_id"=>$decode['staff_id'],
                               "PlantID"=>$decode['PlantID'],
                               
                            );
                            $response=$this->Get_achievement($data);
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_CityAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "state_id"=>$decode['state_id']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_Citylist($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_order_list_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "dist_id"=>$decode['dist_id'],
                                        "PlantID"=>$decode['PlantID'],
                                        "start_date"=>$decode['start_date'],
                                        "end_date"=>$decode['end_date'],
                                        "order_status"=>$decode['order_status']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_order_list($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    
    
    public function Get_pending_order_list_API_new($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "dist_id"=>$decode['dist_id'],
                                        "PlantID"=>$decode['PlantID'],
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_pending_order_list_new($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_pending_order_list_API_new2($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                            $data=array(
                                "dist_id"=>$decode['dist_ids'],
                                "PlantID"=>$decode['PlantID'],
                                "staff_id"=>$decode['staff_id'],
                            );
                        $response=$this->Get_pending_order_list_new2($data);
                }
            }
        
        echo json_encode($response);    
    }
    
    public function GetPendingOrderAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                            $data=array(
                                "PlantID"=>$decode['PlantID'],
                                "staff_id"=>$decode['staff_id'],
                            );
                        $response=$this->GetPendingOrder($data);
                }
            }
        
        echo json_encode($response);    
    }
    
    
    public function Get_my_team_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "PlantID"=>$decode['PlantID']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_my_team_list($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_staff_detail_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "PlantID"=>$decode['PlantID']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_staff_detail($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_sale_reports_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "UserID"=>$decode['UserID'],
                                        "PlantID"=>$decode['PlantID'],
                                        "AccountID"=>$decode['AccountID'],
                                        "from_date"=>$decode['from_date'],
                                        "to_date"=>$decode['to_date']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_sale_reports($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function parties_not_billedAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "UserID"=>$decode['UserID'],
                                        "PlantID"=>$decode['PlantID'],
                                        "from_date"=>$decode['from_date'],
                                        "to_date"=>$decode['to_date']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_parties_not_billed($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function item_not_billedAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "AccountID"=>$decode['AccountID'],
                                        "PlantID"=>$decode['PlantID'],
                                        "from_date"=>$decode['from_date'],
                                        "to_date"=>$decode['to_date']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_item_not_billed($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_account_ledger_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "UserID"=>$decode['UserID'],
                                        "PlantID"=>$decode['PlantID'],
                                        "AccountID"=>$decode['AccountID'],
                                        "from_date"=>$decode['from_date'],
                                        "to_date"=>$decode['to_date']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_account_ledger($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function update_tour_plan_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "PlantID"=>$decode['PlantID'],
                                        "id"=>$decode['id'],
                                        "status"=>$decode['status'],
                                        "reason"=>$decode['reason']
                                      );
                            $response=$this->update_tour_plan($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function SubmitTPlanAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "staff_id"=>$decode['staff_id'],
                    "PlantID"=>$decode['PlantID'],
                    "id"=>$decode['id'],
                    "DistAvl"=>$decode['DistAvl'],
                    "Retailing"=>$decode['Retailing'],
                    "TotalCounterCall"=>$decode['TotalCounterCall'],
                    "TotalProductiveCall"=>$decode['TotalProductiveCall'],
                    "TotalValue"=>$decode['TotalValue'],
                    "PrimaryValue"=>$decode['PrimaryValue'],
                    "ClosingRemark"=>$decode['ClosingRemark']
                );
                $response=$this->SubmitTPlan($data);
                }
            }
        echo json_encode($response);    
    }
    
    public function detail_tour_planAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "id"=>$decode['id']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->detail_tour_plan($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_order_detail_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "order_id"=>$decode['order_id']
                                      );
                        //$state_id = $decode['state_id'];
                            $response=$this->Get_order_details($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    
    
    
    public function In_OutAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            /*$data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "type_check"=>$decode['type_check'],
                                        "location_user"=>$decode['location_user'],
                                        "location_name"=>$decode['location_name']
                                      );*/
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "type_check"=>$decode['type_check'],
                                        "location_user"=>$decode['location_user']
                                      );
                            $response=$this->In_Out($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function In_OutAPI_new1($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "type_check"=>$decode['type_check'],
                                        "location_user"=>$decode['location_user'],
                                        "location_name"=>$decode['location_name']
                                      );
                           
                            $response=$this->In_Out_new1($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function In_OutAPI_new($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "type_check"=>$decode['type_check'],
                                        "location_user"=>$decode['location_user'],
                                        "location_name"=>$decode['location_name']
                                      );
                           
                            $response=$this->In_Out_new($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function In_OutAPI_newl($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data=array(
                        "staff_id"=>$decode['staff_id'],
                        "type_check"=>$decode['type_check'],
                        "location_user"=>$decode['location_user'],
                        "location_name"=>$decode['location_name'],
                        "IMEI"=>$decode['IMEI']
                    );  
                $response=$this->In_Out_newl($data);
                }
            }
        echo json_encode($response);    
    }
    
    public function In_Out_statusAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id']
                                      );
                           
                            $response=$this->In_Out_status($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function VisLocationsAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                           /* $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "location_list"=>$decode['location_list'],
                                        "total_distance"=>$decode['total_distance'],
                                        "location_name_list"=>$decode['location_name_list']
                                      );*/
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "location_list"=>$decode['location_list'],
                                        "total_distance"=>$decode['total_distance']
                                      );
                            $response=$this->VisLocations($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function VisLocationsAPI_new($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "location_list"=>$decode['location_list'],
                                        "total_distance"=>$decode['total_distance'],
                                        "location_name_list"=>$decode['location_name_list'],
                                        "battery_level"=>$decode['battery_level'],
                                        "device_information"=>$decode['device_information'],
                                        "GPS_Status"=>$decode['GPS_Status']
                                      );
                           
                            $response=$this->VisLocations_new($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_CustomerAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "plant_id"=>$decode['plant_id'],
                                        "staff_id"=>$decode['staff_id']
                                      );
                            $response=$this->Get_Customer($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_CustomerAPI_new($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "plant_id"=>$decode['plant_id'],
                                        "staff_id"=>$decode['staff_id']
                                      );
                            $response=$this->Get_Customer_new($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_EnquiryAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id']
                                      );
                            $response=$this->Get_enquiry($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_EnqDetailsAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data=array(
                        "enqID"=>$decode['enqID']
                    );
                $response=$this->Get_enquiryDetails($data);
                }
            }
        echo json_encode($response);    
    }
    
    public function Update_EnqAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data=array(
                        "enqID"=>$decode['enqID'],
                        "GSTIN"=>$decode['GSTIN'],
                        "PAN"=>$decode['PAN'],
                        "AdharNo"=>$decode['AdharNo'],
                        "FLIC"=>$decode['FLIC'],
                        "PIN"=>$decode['PIN'],
                        "GROUPTYPE"=>$decode['GROUPTYPE'],
                        "DISTTYPE"=>$decode['DISTTYPE'],
                        "EmailID"=>$decode['EmailID']
                    );
                $response=$this->Update_enquiryDetails($data);
                }
            }
        echo json_encode($response);    
    }
    
    public function Get_TourAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "staff_id"=>$decode['staff_id'],
                    "from_date"=>$decode['from_date'],
                    "to_date"=>$decode['to_date'],
                    "PlantID"=>$decode['PlantID']
                );
                $response=$this->Get_tour($data);
            }
        }
        echo json_encode($response);    
    }
    
    public function GetTeamTourAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "staff_id"=>$decode['staff_id'],
                    "from_date"=>$decode['from_date'],
                    "to_date"=>$decode['to_date'],
                    "PlantID"=>$decode['PlantID']
                );
                $response=$this->GetTeamTour($data);
            }
        }
        echo json_encode($response);    
    }
    
    
    public function Get_ItemDevisionAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $response=$this->Get_ItemDivision();
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_CustDivAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "dist_id"=>$decode['dist_id'],
                                        "plant_id"=>$decode['plant_id']
                                      );
                            $response=$this->Get_ItemDivision_by_dist($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_ItemDevwise_listAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "group_id"=>$decode['division_id']
                                      );
                            $response=$this->Get_ItemDivwise_list($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_itemlistAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "plant_id"=>$decode['plant_id'],
                                        "dist_type"=>$decode['dist_type'],
                                        "dist_state_id"=>$decode['dist_state_id'],
                                        "item_division"=>$decode['item_division']
                                      );
                            $response=$this->Get_itemlist($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function All_dist_type($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "plant_id"=>$decode['plant_id']
                                      );
                            $response=$this->Get_all_dist_type($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function All_Item_price_List($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "dist_type"=>$decode['dist_type'],
                                        "dist_state_id"=>$decode['dist_state_id'],
                                        "plant_id"=>$decode['plant_id']
                                      );
                            $response=$this->Get_allitemlist($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_order_numberAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            
                            $response=$this->Get_next_order_number();
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    
    public function Ganerate_hashAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                $hash = app_generate_hash();
                            
                            //$response=$this->Get_next_order_number();
                $response=array("status"=>true,"message"=>"Hash Code","hash"=>$hash);        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function oder_placeAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "number"=>$decode['number'],
                                        "date"=>$decode['date'],
                                        "clientid"=>$decode['clientid'],
                                        "dist_comp"=>$decode['dist_comp'],
                                        "dist_sale_agent"=>$decode['dist_sale_agent'],
                                        "dist_route"=>$decode['dist_route'],
                                        "dist_tcs"=>$decode['dist_tcs'],
                                        "order_type"=>$decode['order_type'],
                                        "taxes"=>$decode['taxes'],
                                        "project_id"=>$decode['project_id'],
                                        "billing_street"=>$decode['billing_street'],
                                        "billing_city"=>$decode['billing_city'],
                                        "billing_state"=>$decode['billing_state'],
                                        "billing_zip"=>$decode['billing_zip'],
                                        "billing_country"=>$decode['billing_country'],
                                        "include_shipping"=>$decode['include_shipping'],
                                        "show_shipping_on_invoice"=>$decode['show_shipping_on_invoice'],
                                        "shipping_street"=>$decode['shipping_street'],
                                        "shipping_city"=>$decode['shipping_city'],
                                        "shipping_state"=>$decode['shipping_state'],
                                        "shipping_zip"=>$decode['shipping_zip'],
                                        "shipping_country"=>$decode['shipping_country'],
                                        "currency"=>$decode['currency'],
                                        "sale_agent"=>$decode['sale_agent'],
                                        "total_cases"=>$decode['total_cases'],
                                        "total_crates"=>$decode['total_crates'],
                                        "total_tax"=>$decode['total_tax'],
                                        "subtotal"=>$decode['subtotal'],
                                        "total"=>$decode['total'],
                                        "prefix"=>$decode['prefix'],
                                        "number_format"=>$decode['number_format'],
                                        "datecreated"=>$decode['datecreated'],
                                        "addedfrom"=>$decode['addedfrom'],
                                        "cancel_overdue_reminders"=>$decode['cancel_overdue_reminders'],
                                        "allowed_payment_modes"=>$decode['allowed_payment_modes'],
                                        "custom_recurring"=>$decode['custom_recurring'],
                                        "recurring"=>$decode['recurring'],
                                        "hash"=>$decode['hash'],
                                        "adjustment"=>$decode['adjustment'],
                                        "company_id"=>$decode['company_id'],
                                        "financial_year"=>$decode['financial_year'],
                                        "Item"=>$decode['Item']
                                      );
                            $response=$this->order_place($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function oder_placeAPI2($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                /*if($decode['PlantID'] == "3"){
                    $response=array("status"=>true,"message"=>"Unable to place order because application is under the maintance...");
                }else{*/
                    $data=array(
                        "PlantID"=>$decode['PlantID'],
                        "FY"=>$decode['FY'],
                        "OrderID"=>$decode['OrderID'],
                        "AccountID"=>$decode['AccountID'],
                        "subtotal"=>$decode['subtotal'],
                        "total_tax"=>$decode['total_tax'],
                        "OrderAmt"=>$decode['OrderAmt'],
                        "Crates"=>$decode['Crates'],
                        "Cases"=>$decode['Cases'],
                        "OrderStatus"=>$decode['OrderStatus'],
                        "OrderType"=>$decode['OrderType'],
                        "order_type"=>$decode['order_type'],
                        "UserID"=>$decode['UserID'],
                        "hash"=>$decode['hash'],
                        "Item"=>$decode['Item']
                    );
                    $response=$this->order_place($data);
                //}   
                }
            }
        //$response=array("status"=>true,"message"=>"Unable to place order, please try again later...");
                                //return $response;
        echo json_encode($response);    
    }
    public function searchCustomerAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "customer_name"=>$decode['customer_name'],
                                        "plant_id"=>$decode['plant_id']
                                      );
                            $response=$this->search_Customer($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function singleCustomerAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "customer_id"=>$decode['customer_id'],
                                        "plant_id"=>$decode['plant_id']
                                      );
                                      //$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$decode['plant_id']);
                            $response = $this->single_Customer_detail($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_CustomerGroupAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            /*$data=array(
                                        "customer_type"=>$decode['customer_type']
                                      );*/
                            $response=$this->Get_CustomerGroup();
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function addCustomerAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "company"=>$decode['company'],
                                        "vat"=>$decode['gst'],
                                        "phonenumber"=>$decode['companyphone'],
                                        "country"=>$decode['country'],
                                        "city"=>$decode['city'],
                                        "zip"=>$decode['zipcode'],
                                        "state"=>$decode['state'],
                                        "address"=>$decode['address'],
                                        "groups_in"=>$decode['groups_in'],
                                        "addedfrom"=>$decode['addedfrom'],
                                        "is_primary"=>$decode['is_primary'],
                                        "firstname"=>$decode['firstName'],
                                        "lastname"=>$decode['lastname'],
                                        "title"=>$decode['title'],
                                        "email"=>$decode['email'],
                                        "password"=>$decode['password']
                                      );
                            
                            $response=$this->addCustomer($data);
                        
                }
            }
            
          
        
        echo json_encode($response);    
    }
    
    public function AddEnquiryAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "farm_name"=>$decode['farm_name'],
                                        "contact_person"=>$decode['contact_person'],
                                        "cp_mobile_no"=>$decode['cp_mobile_no'],
                                        "address"=>$decode['address'],
                                        "remark"=>$decode['remark'],
                                        "state"=>$decode['state'],
                                        "district"=>$decode['district'],
                                        "area"=>$decode['area'],
                                        "revisit"=>$decode['revisit'],
                                        "status"=>$decode['status']
                                      );
                            
                            $response=$this->AddEnquiry($data);
                        
                }
            }
            
          
        
        echo json_encode($response);    
    }
    
    public function AddTourAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "staff_id"=>$decode['staff_id'],
                                        "PlantID"=>$decode['PlantID'],
                                        "cust_ID"=>$decode['cust_ID'],
                                        "purpose"=>$decode['purpose'],
                                        "start_date"=>$decode['start_date'],
                                        "end_date"=>$decode['end_date'],
                                        "state"=>$decode['state'],
                                        "city"=>$decode['city'],
                                        "area"=>$decode['area'],
                                        "remark"=>$decode['remark'],
                                        "status"=>$decode['status'],
                                        "reason"=>$decode['reason']
                                      );
                            
                            $response=$this->addtour($data);
                        
                }
            }
            
          
        
        echo json_encode($response);    
    }
    
    public function Add_versonAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                                        "verson"=>$decode['verson'],
                                        "app_url"=>$decode['app_url']
                                      );
                            $response=$this->Add_verson($data);
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function Get_versonAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            /*$data=array(
                                        "customer_type"=>$decode['customer_type']
                                      );*/
                            $response=$this->Get_App_Version();
                        
                }
            }
        
        echo json_encode($response);    
    }
    
    public function getAPI_old($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'PUT')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                switch ($decode['api_name']) {
                    case "get_subCategory":
                            $response=$this->getSubCategory($decode['parent_category_id']);
                         break;
                    case "signup":
                           $data=array(
                                        "mobile_number"=>$decode['mobile_number'],
                                        "fname"=>$decode['fname'],
                                        "lname"=>$decode['lname'],
                                        "email"=>$decode['email'],
                                        "password"=>$decode['password'],
                                        "reference_code"=>$decode['reference_code']
                                      );
                            $response=$this->signup($data);
                        break;
                    case "firebase_token":
                           $data=array(
                                        "android_id"=>$decode['android_id'],
                                        "user_id"=>$decode['user_id'],
                                        "firebase_token"=>$decode['firebase_token']
                                      );
                            $response=$this->firebase_token($data);
                        break;
                    case "get_childCategory":
                            $response=$this->getChildCategory($decode['sub_category_id']);
                        break;
                        case "login":
                            $data=array(
                                        "email"=>$decode['email'],
                                        "password"=>$decode['password'],
                                        "staff"=>$decode['staff']
                                      );
                            $response=$this->login($data);
                        break;
                    case "all_subCategory":
                            $response=$this->getAllSubCategory();
                        break;
                    case "update_profile":
                            $data=array( 
                                        "user_id"=>$decode['user_id'],                                       
                                        "fname"=>$decode['fname'],
                                        "lname"=>$decode['lname'],
                                        "email"=>$decode['email'],
                                        "dob"=>$decode['dob'],
                                        "gender"=>$decode['gender'],
                                        "address"=>$decode['address'],
                                        "aboutme"=>$decode['aboutme']
                                      );
                            $response=$this->updateUserProfile($data);
                        break;
                    case "vendor_registration":
                            $data=array( 
                                        "user_id"=>$decode['user_id'],
                                        "exp1"=>$decode['exp1'],                                       
                                        "exp2"=>$decode['exp2'],
                                        "exp3"=>$decode['exp3'],
                                        "professional_type"=>$decode['professional_type'],
                                        "qualification"=>$decode['qualification'],
                                        "month"=>$decode['month'],
                                        "year"=>$decode['year']
                                      );
                            $response=$this->vendorRegistration($data);
                        break;
                    case "contact_us":
                            $data=array( 
                                      "message"=>$decode['message'],                                       
                                      "contact_number"=>$decode['contact_number']
                                      );
                            $response=$this->contactUs($data);
                        break;
                        
                        default:
                        $response = array("error" => true,"message" => "Invalid API");  
                }
            }
        }
        else
        {
           $response = array("error" => true,"message" => "Invalid madhav request");  
        }
        echo json_encode($response);    
    }
    
    
    
    
    public function getSubCategory($parent_id) {
        
        $sql="SELECT * FROM `tbl_sub_category` WHERE `parent_category_id`=".$parent_id;
        $result=$this->BuisnessModel->getQuery($sql);
        $response=array("error"=>false,"message"=>"Sub-category list.","sub_categories"=>$result);            
        return $response;
    }

    public function signup($params=FALSE) {
        
        
        $sql="SELECT * FROM `tbl_user` WHERE `user_mobile_number`='".$params['mobile_number']."'";
        $num=$this->BuisnessModel->numRowsQuery($sql);
        if($num>=1)
        {
            $mobile = $params['mobile_number'];
           $response=array("error"=>true,"message"=>"$mobile has already registered.");
           return $response;   
        }
        else
        {
           $curdate=$this->curDate();
           $sql_insert="INSERT INTO `tbl_user`(`user_fname`, `user_lname`,`user_email`, `user_mobile_number`, `user_password`, `user_account_status`, `user_insertedDate`) VALUES ('".$params['fname']."','".$params['lname']."','".$params['email']."','".$params['mobile_number']."','".md5($params['password'])."','activate','".$curdate."')"; 
           $result=$this->BuisnessModel->insertQueryGetLastId($sql_insert);

           if($result['affected_rows']>=1)
           {
              
           	    if(trim($params['reference_code'])!="")
           	    {

           	    	$sql_ven_num="SELECT * FROM `tbl_user`,vendor_info WHERE tbl_user.user_id=vendor_info.user_id and tbl_user.reference_code='".trim($params['reference_code'])."'";
           	    	$ven_num=$this->BuisnessModel->numRowsQuery($sql_ven_num);
           	    	$ven_data=$this->BuisnessModel->getQuery($sql_ven_num);

           	    	if($ven_num==1)
           	    	{
           	    		$bonus_remark="You have got 200 bonus credits for giving reference to other user";
           	    		$sql_bonus="INSERT INTO `tbl_user_credit_details`(`user_id`, `get_credits`, `credits_type`, `credits_remark`, `payment_status`, `credit_insertedDate`) VALUES (".$ven_data[0]['user_id'].",200,'Bonus','".$bonus_remark."','Free','".$this->curDate()."')";
           	    		$result_bonus=$this->BuisnessModel->insertQuery($sql_bonus);

           	    		$sql_update_credit="UPDATE `vendor_info` SET  user_credits=user_credits+200 WHERE `user_id`=".$ven_data[0]['user_id']; 
                        $affected_row_credit=$this->BuisnessModel->updateQuery($sql_update_credit);


                        $notification_remark="You have got 200 bonus credits for giving reference to other user";
           	    		$sql_notification="INSERT INTO `tbl_notification`(`user_id`, `notification_title`, `notification_text`, `notification_status`, `notification_inserted_date`) VALUES (".$ven_data[0]['user_id'].",'Bonus Credit','".$notification_remark."','unssen','".$this->curDate()."')";
           	    		$result_notification=$this->BuisnessModel->insertQuery($sql_notification);

           	    	}
           	    }

           	   /*  generate reference code*/
           	    $reference_code="F2F".$result['last_id'].substr($params['fname'],0,1).substr($params['lname'],strlen($params['lname'])-1,1).substr($params['lname'],0,1).substr($params['fname'],strlen($params['fname'])-1,1).$this->generateRandomNumber(2);

           	    $sql_update="UPDATE `tbl_user` SET  reference_code='".strtoupper($reference_code)."' WHERE `user_id`=".$result['last_id']; 
                $affected_row=$this->BuisnessModel->updateQuery($sql_update);

                /*  generate reference code end */ 

               $sql_get="SELECT * FROM `tbl_user` WHERE `user_mobile_number`='".$params['mobile_number']."' and `user_password`='".md5($params['password'])."'";
               $result=$this->BuisnessModel->getQuery($sql_get);
               if(empty($result[0]['user_photo']))
               {
                 $file_path="https://".$_SERVER['SERVER_NAME']."/foot2feet-live/admin_assets/uploads/user/profile/default.jpg";
               }
               else{
                 $file_path="https://".$_SERVER['SERVER_NAME']."/foot2feet-live/admin_assets/uploads/user/profile/".$result[0]['user_photo'];
               }
               $data=array("user_id"=>$result[0]['user_id']);
               $vendor_info=$this->vendor_info($data);
               $user_data=array(
                        "user_id"=> $result[0]['user_id'],
                        "user_fname"=> $result[0]['user_fname'],
                        "user_lname"=> $result[0]['user_lname'],
                        "user_email"=> $result[0]['user_email'],
                        "user_mobile_number"=>$result[0]['user_mobile_number'],
                        "user_dob"=> $result[0]['user_dob'],
                        "user_gender"=> $result[0]['user_gender'],
                        "user_address"=> $result[0]['user_address'],
                        "user_about_me"=> $result[0]['user_about_me'],
                        "reference_code"=> $result[0]['reference_code'],
                        "user_photo"=> $file_path,
                        "user_insertedDate"=> $result[0]['user_insertedDate'],
                        "vendor_id"=> $vendor_info['vendor_id'],
                        "vendor_flag"=> $vendor_info['vendor_flag']
               );

               $response=array("error"=>false,"message"=>"You have registered successfully.","user_data"=>$user_data);
               return $response;  
           }
           else{

                $response=array("error"=>true,"message"=>"During signup some error occure.");
                return $response;
           }

        }
    }

    public function firebase_token($params=FALSE) {
        
        
        $sql="SELECT * FROM `tbl_firebase_notification` WHERE `android_id`='".$params['android_id']."' ";
        $num=$this->BuisnessModel->numRowsQuery($sql);
        if($num>=1)
        {
            $sql_update="UPDATE `tbl_firebase_notification` SET 
            user_id = '".$params['user_id']."',`firebase_token`= 
            '".$params['firebase_token']."' WHERE `android_id`='".$params['android_id']."' "; 
           
           $affected_row=$this->BuisnessModel->updateQuery($sql_update);
           if($affected_row>=1){
                $response=array("error"=>false,"message"=>"firebase token updated.");
                return $response;   
           }else {
            $response=array("error"=>true,"message"=>"Firebase token is upto date.");
                return $response;   
           }
        }
        else
        {
           $curdate=$this->curDate();
           $sql_insert="INSERT INTO `tbl_firebase_notification`(`android_id`, `user_id`,`firebase_token`, `created`) VALUES ('".$params['android_id']."','".$params['user_id']."','".$params['firebase_token']."', '".$curdate."')"; 
           $result=$this->BuisnessModel->insertQueryGetLastId($sql_insert);

           if($result['affected_rows']>=1)
           {
               $response=array("error"=>false,"message"=>"You have registered firebase token successfully.");
               return $response;  
           }
           else{
                $response=array("error"=>true,"message"=>"During insert firebase token some error occured.");
                return $response;
           }

        }
    }

    public function getChildCategory($subcategory_id) {
        
        $sql="SELECT * FROM `tbl_child_category` WHERE `sub_category_id`=".$subcategory_id;
        $result=$this->BuisnessModel->getQuery($sql);
        $response=array("error"=>false,"message"=>"Child-category list.","child_category"=>$result);            
        return $response;
    }
    
    
    
    public function In_Out($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        /*$data=array(
                    "staff_id"=>$params['staff_id'],
                    "type_check"=>$params['type_check'],
                    "edit_date"=>'',
                    "point_id"=>'',
                    "location_user"=>$params['location_user'],
                    "location_name"=>$params['location_name']
                                      );*/
        $data=array(
                    "staff_id"=>$params['staff_id'],
                    "type_check"=>$params['type_check'],
                    "edit_date"=>'',
                    "point_id"=>'',
                    "location_user"=>$params['location_user']
                                      );
            $type = $data['type_check'];
        $re = $this->UserApp_Model->check_in($data);
      
       if(is_numeric($re)){
				if($re == 2){
				    $response=array("status"=>false,"message"=>"Your Current Location is not allowed to take attendance");
					//set_alert('warning',_l('your_current_location_is_not_allowed_to_take_attendance'));            
				}
				if($re == 3){
				    $response=array("status"=>false,"message"=>"Your location information is unknown");
					//set_alert('warning',_l('location_information_is_unknown'));            
				}
				if($re == 4){
				    $response=array("status"=>false,"message"=>"Your Route point is unknown");
					//set_alert('warning',_l('route_point_is_unknown'));            
				}
			}
			else{
				if($re == true){
					if($type == 1){
					    $response=array("status"=>true,"message"=>"Start Day Successfully");
						//set_alert('success',_l('check_in_successfull'));            
					}
					else{
					    $response=array("status"=>true,"message"=>"End Day Successfully");
						//set_alert('success',_l('check_out_successfull'));            
					}
				}
				else{
					if($type == 1){
					    $response=array("status"=>false,"message"=>"Day Not Started Successfully");
						//set_alert('warning',_l('check_in_not_successfull'));            
					}
					else{
					    $response=array("status"=>false,"message"=>"Day out not Successfully");
						//set_alert('warning',_l('check_out_not_successfull'));            
					}
				}                
			}
               return $response; 
    } 
    
    public function In_Out_new($params=FALSE){
        
        $this->load->model('UserApp_Model');
        $type = $params['type_check'];
        $staff_id= $params['staff_id'];
        $cur_date = date('Y-m-d');
        $get_data = $this->UserApp_Model->get_in_out_data($staff_id,$cur_date);
        
        if($get_data){
            if($type== "2"){
                if(empty($get_data["check_out"])){
                    
                    $check_out = unserialize($get_data["check_out"]);
                                $check_out = date('H:i:s');
                                
                                $check_out_loc = unserialize($get_data["check_out_loc"]);
                                $check_out_loc = $params['location_user'];
                                
                                $check_out_loc_name = unserialize($get_data["check_out_loc_name"]);
                                $check_out_loc_name = $params['location_name'];
                                
                                $location_list = unserialize($get_data["location_list"]);
                                $location_list = $location_list."|".$params['location_user'];
                                $location_list = serialize($location_list);
                                
                                $location_name_list = unserialize($get_data["location_name_list"]);
                                $location_name_list = $location_name_list."|".$params['location_name'];
                                $location_name_list = serialize($location_name_list);
                                
                                $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "check_out"=>serialize($check_out),
                                        "check_out_loc"=>serialize($check_out_loc),
                                        "location_list"=>$location_list,
                                        "check_out_loc_name"=>serialize($check_out_loc_name),
                                        "location_name_list"=>$location_name_list
                                    );
                
                $re = $this->UserApp_Model->check_in_new_update($data);
                $travel_data = array(
                                    "staff_id"=>$params['staff_id'],
                                    "location_list"=>$params['location_user'],
                                    "location_trav"=>'0.00',
                                    "location_name_list"=>$params['location_name'],
                                    "battery_level"=>"NA",
                                    "device_information"=>"NA",
                                    "GPS_Status"=>"NA",
                                    "date"=>date('Y-m-d')
                                );
                    $this->db->insert(db_prefix().'travel_report', $travel_data);
                if($re == true){
    					$date = date('Y-m-d');
			            $datetime = $date.' '.date('H:i:s');
    				    $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "date"=>$datetime
                                    );
                        $this->db->insert(db_prefix().'check_in_out', $data);
			            $insert_id = $this->db->insert_id();
			            if($insert_id){
			                $response=array("status"=>true,"message"=>"End Day Successfully");
			            }
                }
                }else {
                    $response=array("status"=>false,"message"=>"We have already Day Endded..");
                }
                
                
            }else {
                $response=array("status"=>false,"message"=>"We have already Day Started..");
            }
            
        }else {
            if($type== "1"){
                
                $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "check_in"=>serialize(date('H:i:s')),
                                        "check_in_loc"=>serialize($params['location_user']),
                                        "location_list"=>serialize($params['location_user']),
                                        "check_in_loc_name"=>serialize($params['location_name']),
                                        "location_name_list"=>serialize($params['location_name']),
                                        "date"=>date('Y-m-d')
                                    );
                $re = $this->UserApp_Model->check_in_new($data);
                $travel_data = array(
                                    "staff_id"=>$params['staff_id'],
                                    "location_list"=>$params['location_user'],
                                    "location_trav"=>'0.00',
                                    "location_name_list"=>$params['location_name'],
                                    "battery_level"=>"NA",
                                    "device_information"=>"NA",
                                    "GPS_Status"=>"NA",
                                    "date"=>date('Y-m-d')
                                );
                    $this->db->insert(db_prefix().'travel_report', $travel_data);
                if($re == true){
            					$date = date('Y-m-d');
        			            $datetime = $date.' '.date('H:i:s');
            				    $data=array(
                                                "staff_id"=>$params['staff_id'],
                                                "type_check"=>$params['type_check'],
                                                "date"=>$datetime
                                            );
                                $this->db->insert(db_prefix().'check_in_out', $data);
        			            $insert_id = $this->db->insert_id();
        			            if($insert_id){
        			                $response=array("status"=>true,"message"=>"Start Day Successfully");
        			            }
                         }
                
            }else{
                $response=array("status"=>false,"message"=>"We have Check in First");
            }
        }
        return $response; 
    }
    
    public function In_Out_newl($params=FALSE){
        
        $this->load->model('UserApp_Model');
        $type = $params['type_check'];
        $staff_id= $params['staff_id'];
        $IMEI= $params['IMEI'];
        $cur_date = date('Y-m-d');
        $get_data = $this->UserApp_Model->get_in_out_data($staff_id,$cur_date);
        $table = db_prefix() . 'staff';
        $this->db->where('staffid', $staff_id);
        $user = $this->db->get($table)->row();
        if($IMEI == $user->DiveceID){
            if($get_data){
                if($type== "2"){
                    if(empty($get_data["check_out"])){
                        
                        $check_out = unserialize($get_data["check_out"]);
                        $check_out = date('H:i:s');
                        $check_out_loc = unserialize($get_data["check_out_loc"]);
                        $check_out_loc = $params['location_user'];
                                    
                        $check_out_loc_name = unserialize($get_data["check_out_loc_name"]);
                        $check_out_loc_name = $params['location_name'];
                                    
                        $location_list = unserialize($get_data["location_list"]);
                        $location_list = $location_list."|".$params['location_user'];
                        $location_list = serialize($location_list);
                                    
                        $location_name_list = unserialize($get_data["location_name_list"]);
                        $location_name_list = $location_name_list."|".$params['location_name'];
                        $location_name_list = serialize($location_name_list);
                                    
                        $data=array(
                            "staff_id"=>$params['staff_id'],
                            "type_check"=>$params['type_check'],
                            "check_out"=>serialize($check_out),
                            "check_out_loc"=>serialize($check_out_loc),
                            "location_list"=>$location_list,
                            "check_out_loc_name"=>serialize($check_out_loc_name),
                            "location_name_list"=>$location_name_list
                        );
                    
                    $re = $this->UserApp_Model->check_in_new_update($data);
                        $travel_data = array(
                            "staff_id"=>$params['staff_id'],
                            "location_list"=>$params['location_user'],
                            "location_trav"=>'0.00',
                            "location_name_list"=>$params['location_name'],
                            "battery_level"=>"NA",
                            "device_information"=>"NA",
                            "GPS_Status"=>"NA",
                            "date"=>date('Y-m-d')
                        );
                        $this->db->insert(db_prefix().'travel_report', $travel_data);
                    if($re == true){
        					$date = date('Y-m-d');
    			            $datetime = $date.' '.date('H:i:s');
        				    $data=array(
                                "staff_id"=>$params['staff_id'],
                                "type_check"=>$params['type_check'],
                                "date"=>$datetime
                            );
                            $this->db->insert(db_prefix().'check_in_out', $data);
    			            $insert_id = $this->db->insert_id();
    			            if($insert_id){
    			                $response=array("status"=>true,"message"=>"End Day Successfully");
    			            }
                    }
                    }else {
                        $response=array("status"=>false,"message"=>"We have already Day Endded..");
                    }
                }else {
                    $response=array("status"=>false,"message"=>"We have already Day Started..");
                }
                
            }else {
                if($type== "1"){
                    $data=array(
                        "staff_id"=>$params['staff_id'],
                        "type_check"=>$params['type_check'],
                        "check_in"=>serialize(date('H:i:s')),
                        "check_in_loc"=>serialize($params['location_user']),
                        "location_list"=>serialize($params['location_user']),
                        "check_in_loc_name"=>serialize($params['location_name']),
                        "location_name_list"=>serialize($params['location_name']),
                        "date"=>date('Y-m-d')
                    );
                    $re = $this->UserApp_Model->check_in_new($data);
                    $travel_data = array(
                        "staff_id"=>$params['staff_id'],
                        "location_list"=>$params['location_user'],
                        "location_trav"=>'0.00',
                        "location_name_list"=>$params['location_name'],
                        "battery_level"=>"NA",
                        "device_information"=>"NA",
                        "GPS_Status"=>"NA",
                        "date"=>date('Y-m-d')
                    );
                    $this->db->insert(db_prefix().'travel_report', $travel_data);
                    if($re == true){
                		$date = date('Y-m-d');
            			 $datetime = $date.' '.date('H:i:s');
                		$data=array(
                            "staff_id"=>$params['staff_id'],
                            "type_check"=>$params['type_check'],
                            "date"=>$datetime
                        );
                        $this->db->insert(db_prefix().'check_in_out', $data);
            			$insert_id = $this->db->insert_id();
            			 if($insert_id){
            			     $response=array("status"=>true,"message"=>"Start Day Successfully");
            			 }
                    }
                }else{
                    $response=array("status"=>false,"message"=>"We have Check in First");
                }
            }
        }else{
            $response=array("status"=>false,"message"=>"IMEI number not matched...");
        }
        return $response; 
    }
    
    public function In_Out_new_old($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $type = $params['type_check'];
        $staff_id= $params['staff_id'];
        $cur_date = date('Y-m-d');
        $get_data = $this->UserApp_Model->get_in_out_data($staff_id,$cur_date);
        
        
       
        if($get_data && !empty($get_data["check_out"])){
            
                if($type==$get_data["type_check"]){
                
                    if($type=="1"){
                        $response=array("status"=>false,"message"=>"We have already Day Started");
                    }else {
                        $response=array("status"=>false,"message"=>"We have already Day Ended");
                    }
                
                
            }else {
                
                        if($type == "1" ){
                            
                            $check_in = unserialize($get_data["check_in"]);
                            $check_in = $check_in.",".date('H:i:s');
                            
                            $check_in_loc = unserialize($get_data["check_in_loc"]);
                            $check_in_loc = $check_in_loc."|".$params['location_user'];
                            
                            $check_in_loc_name = unserialize($get_data["check_in_loc_name"]);
                            $check_in_loc_name = $check_in_loc_name."|".$params['location_name'];
                
                                $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "check_in"=>serialize($check_in),
                                        "check_in_loc"=>serialize($check_in_loc),
                                        "check_in_loc_name"=>serialize($check_in_loc_name)
                                    );
                                
                            } else {
                            
                                $check_out = unserialize($get_data["check_out"]);
                                $check_out = $check_out.",".date('H:i:s');
                                
                                $check_out_loc = unserialize($get_data["check_out_loc"]);
                                $check_out_loc = $check_out_loc."|".$params['location_user'];
                                
                                $check_out_loc_name = unserialize($get_data["check_out_loc_name"]);
                                $check_out_loc_name = $check_out_loc_name."|".$params['location_name'];
                                
                                $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "check_out"=>serialize($check_out),
                                        "check_out_loc"=>serialize($check_out_loc),
                                        "check_out_loc_name"=>serialize($check_out_loc_name)
                                    );
                
                            }
            
            
                
                    $re = $this->UserApp_Model->check_in_new_update($data);
           
          
    				if($re == true){
    					$date = date('Y-m-d');
			            $datetime = $date.' '.date('H:i:s');
    				    $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "date"=>$datetime
                                    );
                        $this->db->insert(db_prefix().'check_in_out', $data);
			            $insert_id = $this->db->insert_id();
			            if($insert_id){
			               //$times =  $this->timesheets_model->add_check_in_out_value_to_timesheet($data['staff_id'], $date);
    			                if($type == 1){
        					    $response=array("status"=>true,"message"=>"Start Day Successfully");
        						//set_alert('success',_l('check_in_successfull'));            
        					    }
            					else{
            					    $response=array("status"=>true,"message"=>"End Day Successfully");
            						//set_alert('success',_l('check_out_successfull'));            
            					}
    					
			            }
    				}
    				else{
    					if($type == 1){
    					    $response=array("status"=>false,"message"=>"Day Not Started Successfully");
    						//set_alert('warning',_l('check_in_not_successfull'));            
    					}
    					else{
    					    $response=array("status"=>false,"message"=>"Day out not Successfully");
    						//set_alert('warning',_l('check_out_not_successfull'));            
    					}
    				}                
    		
                
            }
            
        } else {
            
                    if($type==$get_data["type_check"]){
                        
                        $response=array("status"=>false,"message"=>"We have already Day Started");
                    }else {
            
                            if($type == "1"){
                                
                                $data=array(
                                        "staff_id"=>$params['staff_id'],
                                        "type_check"=>$params['type_check'],
                                        "check_in"=>serialize(date('H:i:s')),
                                        "check_in_loc"=>serialize($params['location_user']),
                                        "check_in_loc_name"=>serialize($params['location_name']),
                                        "date"=>date('Y-m-d')
                                    );
                                    
                            }else {
                                
                                $data=array(
                                                "staff_id"=>$params['staff_id'],
                                                "type_check"=>$params['type_check'],
                                                "check_out"=>serialize(date('H:i:s')),
                                                "check_out_loc"=>serialize($params['location_user']),
                                                "check_out_loc_name"=>serialize($params['location_name'])
                                            );
                            }
                    
                        
                        $re = $this->UserApp_Model->check_in_new($data);
                        
                        if($re == true){
            					$date = date('Y-m-d');
        			            $datetime = $date.' '.date('H:i:s');
            				    $data=array(
                                                "staff_id"=>$params['staff_id'],
                                                "type_check"=>$params['type_check'],
                                                "date"=>$datetime
                                            );
                                $this->db->insert(db_prefix().'check_in_out', $data);
        			            $insert_id = $this->db->insert_id();
        			            if($insert_id){
        			                
        			                if($type == 1){
                					    $response=array("status"=>true,"message"=>"Start Day Successfully");
                						//set_alert('success',_l('check_in_successfull'));            
                					}
                					else{
                					    $response=array("status"=>true,"message"=>"End Day Successfully");
                						//set_alert('success',_l('check_out_successfull'));            
                					}
        			            }
            				}
            				else{
            					if($type == 1){
            					    $response=array("status"=>false,"message"=>"Day Not Started Successfully");
            						//set_alert('warning',_l('check_in_not_successfull'));            
            					}
            					else{
            					    $response=array("status"=>false,"message"=>"Day out not Successfully");
            						//set_alert('warning',_l('check_out_not_successfull'));            
            					}
            				}
                }
        }
        
        
               return $response; 
    }
    
    
    public function In_Out_status($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        
        $staff_id= $params['staff_id'];
        $cur_date = date('Y-m-d');
        $get_data = $this->UserApp_Model->get_in_out_data($staff_id,$cur_date);
        
        
       
        if($get_data){
            
                        $response=array("status"=>true,"message"=>"Below detail for login user", "data"=>$get_data);
            
                    } else {
                        
                            $response=array("status"=>false,"message"=>"no data for today", "data"=>null);
                    }
        
        
               return $response; 
    }
    
    public function VisLocations($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        /*$data=array(
                    "staff_id"=>$params['staff_id'],
                    "location_list"=>$params['location_list'],
                    "location_trav"=>$params['total_distance'],
                    "location_name_list"=>$params['location_name_list']
                                      );*/
        $data=array(
                    "staff_id"=>$params['staff_id'],
                    "location_list"=>$params['location_list'],
                    "location_trav"=>$params['total_distance']
                                      );
                $data['travDate'] = date('Y-m-d');
            $this->db->insert(db_prefix() . 'staffVisitLoc', $data);
            $insert_id = $this->db->insert_id();
        
        if (isset($insert_id)) {
                $response=array("status"=>true,"message"=>"You have Added Visiting list successfully");
            } else {
                $response=array("status"=>false,"message"=>"Something Went Wrong...");
            }
        
       
                
            return $response; 
    } 
    
    public function VisLocations_new($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $staff_id= $params['staff_id'];
        $cur_date = date('Y-m-d');
        
        
                                      
        $get_data = $this->UserApp_Model->get_in_out_data($staff_id,$cur_date);
        
        if($get_data && !empty($get_data["location_list"])){
            
            $location_list = unserialize($get_data["location_list"]);
            $location_list = $location_list."|".$params['location_list'];
            $location_list = serialize($location_list);
        
            $total_distance = unserialize($get_data["location_trav"]);
            $total_distance = $total_distance."|".$params['total_distance'];
            $total_distance = serialize($total_distance);
            
            $location_name_list = unserialize($get_data["location_name_list"]);
            $location_name_list = $location_name_list."|".$params['location_name_list'];
            $location_name_list = serialize($location_name_list);
            
            
        }else{
            
            $location_list= serialize($params['location_list']);
            $total_distance= serialize($params['total_distance']);
            $location_name_list= serialize($params['location_name_list']);
            
        }
        
        
            if($get_data['type_check'] == "1"){    
                $data=array(
                    "staff_id"=>$params['staff_id'],
                    "location_list"=>$location_list,
                    "location_trav"=>$total_distance,
                    "location_name_list"=>$location_name_list
                                      );
                $result = $this->UserApp_Model->location_update($data);
            }    
        
            if($get_data['type_check'] == "1"){
                $single_record = array(
                        "staff_id"=>$params['staff_id'],
                        "location_list"=>$params['location_list'],
                        "location_trav"=>$params['total_distance'],
                        "location_name_list"=>$params['location_name_list'],
                        "battery_level"=>$params['battery_level'],
                        "device_information"=>$params['device_information'],
                        "GPS_Status"=>$params['GPS_Status'],
                        "date"=>$cur_date
                    );
                $this->db->insert(db_prefix().'travel_report', $single_record);
            }
            
        
                
        if ($result==true) {
                $response=array("status"=>true,"message"=>"You have Added Visiting list successfully");
            } else {
                $response=array("status"=>false,"message"=>"Something Went Wrong...");
            }
        
       
                
            return $response; 
    }
    
    public function Get_statelist($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_statelist();
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function Get_dashboard_status($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_dashboard_status($staff_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$staff_id);
                                return $response;*/
               return $success; 
    }
    
    public function Get_assigned_company($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_assigned_company($staff_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$staff_id);
                                return $response;*/
               return $success; 
    }
    
     public function Get_target($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_target($staff_id);
        return $success; 
    }
    
    public function Get_achievement($params=FALSE){
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_achievement($staff_id,$PlantID);
        return $success; 
    }
     
    public function Get_Citylist($params=FALSE){
        
        $state_id = $params['state_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_Citylist($state_id);
       return $success; 
    }
    
    public function Get_order_list($params=FALSE){
        
        $dist_id = $params['dist_id'];
        $PlantID = $params['PlantID'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $order_status = $params['order_status'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_order_list_detail($dist_id,$PlantID,$start_date,$end_date,$order_status);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$staff_id);
                                return $response;*/
               return $success; 
    }
    
    
    
    public function Get_pending_order_list_new($params=FALSE){
        
        $dist_id = $params['dist_id'];
        $PlantID = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_pending_order_list_detail_new($dist_id,$PlantID);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$staff_id);
                                return $response;*/
               return $success; 
    }
    
    public function Get_pending_order_list_new2($params=FALSE)
    {
        $dist_id = $params['dist_id'];
        $PlantID = $params['PlantID'];
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_pending_order_list_detail_new2($dist_id,$PlantID,$staff_id);
        return $success; 
    }
    
    public function GetPendingOrder($params=FALSE){
        
        $PlantID = $params['PlantID'];
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->GetPendingOrder($PlantID,$staff_id);
        return $success; 
    }
    
    public function Get_my_team_list($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_my_team_list_detail($staff_id,$PlantID);
        return $success; 
    }
    
    public function Get_staff_detail($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_staff_detail($staff_id,$PlantID);
        return $success; 
    }
    
    public function Get_sale_reports($params=FALSE){
        
        $UserID = $params['UserID'];
        $PlantID = $params['PlantID'];
        $AccountID = $params['AccountID'];
        $from_date = $params['from_date'];
        $to_date = $params['to_date'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_sale_reports($UserID,$PlantID,$AccountID,$from_date,$to_date);
        return $success; 
    }
    
    public function Get_parties_not_billed($params=FALSE){
        
        $UserID = $params['UserID'];
        $PlantID = $params['PlantID'];
        $from_date = $params['from_date'];
        $to_date = $params['to_date'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_parties_not_billed($UserID,$PlantID,$from_date,$to_date);
       return $success; 
    }
    
    public function Get_item_not_billed($params=FALSE){
        
        $AccountID = $params['AccountID'];
        $PlantID = $params['PlantID'];
        $from_date = $params['from_date'];
        $to_date = $params['to_date'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_item_not_billed($AccountID,$PlantID,$from_date,$to_date);
       
                
               return $success; 
    }
    
    public function Get_account_ledger($params=FALSE){
        
        $UserID = $params['UserID'];
        $PlantID = $params['PlantID'];
        $AccountID = $params['AccountID'];
        $from_date = $params['from_date'];
        $to_date = $params['to_date'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_account_ledger($UserID,$PlantID,$AccountID,$from_date,$to_date);
       
               return $success; 
    }
    
    public function update_tour_plan($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $id = $params['id'];
        $status = $params['status'];
        $reason = $params['reason'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->update_tour_plan($staff_id,$PlantID,$id,$status,$reason);
               return $success; 
    }
    
    public function SubmitTPlan($params=FALSE){
        
        
        $data = array(
            "staff_id" => $params['staff_id'],
            "PlantID" => $params['PlantID'],
            "id" => $params['id'],
            "DistAvl" => $params['DistAvl'],
            "Retailing" => $params['Retailing'],
            "TotalCounterCall" => $params['TotalCounterCall'],
            "TotalProductiveCall" => $params['TotalProductiveCall'],
            "TotalValue" => $params['TotalValue'],
            "PrimaryValue" => $params['PrimaryValue'],
        );
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Submit_TPlan($data);
        return $success; 
    }
    
    public function detail_tour_plan($params=FALSE){
        
        
        $id = $params['id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->detail_tour_plan($id);
       
               
               return $success; 
    }
    
    public function Get_order_details($params=FALSE){
        
        $order_id = $params['order_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_order_details($order_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function Get_Customer($params=FALSE){
        
        $plant_id = $params['plant_id'];
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_Customer($plant_id,$staff_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function Get_Customer_new($params=FALSE){
        
        $plant_id = $params['plant_id'];
        $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_Customer_new($plant_id,$staff_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function Get_enquiry($params=FALSE){
        
        
         $staff_id = $params['staff_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_enquiry($staff_id);
       
               return $success; 
    }
    
    public function Get_enquiryDetails($params=FALSE){
        $enqID = $params['enqID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_enquiryDetails($enqID);
        return $success; 
    }
    public function Update_enquiryDetails($params=FALSE){
        $enqID = $params['enqID'];
        $data =array(
            "enqID"=>$params['enqID'],
            "GSTIN"=>$params['GSTIN'],
            "PAN"=>$params['PAN'],
            "AdharNo"=>$params['AdharNo'],
            "FLIC"=>$params['FLIC'],
            "PIN"=>$params['PIN'],
            "GROUPTYPE"=>$params['GROUPTYPE'],
            "DISTTYPE"=>$params['DISTTYPE'],
            "EmailID"=>$params['EmailID']
        );
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Update_enquiryDetails($data);
        return $success; 
    }
    
    public function Get_tour($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $from_date = $params['from_date'];
        $to_date  = $params['to_date'];
        $PlantID  = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_tour($staff_id,$from_date,$to_date,$PlantID);
        return $success; 
    }
    public function GetTeamTour($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $from_date = $params['from_date'];
        $to_date  = $params['to_date'];
        $PlantID  = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->GetTeamTour($staff_id,$from_date,$to_date,$PlantID);
        return $success; 
    }
    
    public function Get_ItemDivision($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_ItemDivision();
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function Get_ItemDivision_by_dist($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $dist_id = $params['dist_id'];
        $plant_id = $params['plant_id'];
        $success = $this->UserApp_Model->Get_ItemDivision_by_dist($dist_id,$plant_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function Get_ItemDivwise_list($params=FALSE){
        
        
        $group_id = $params['group_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_ItemDivwise_list($group_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$dd);
                                return $response;*/
               return $success; 
    }
    public function Get_itemlist($params=FALSE){
        
        $plant_id = $params['plant_id'];
        $dist_type = $params['dist_type'];
        $dist_state_id = $params['dist_state_id'];
        $item_division = $params['item_division'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_itemlist($dist_type,$dist_state_id,$item_division,$plant_id);
       
               
               return $success; 
    }
    
    public function Get_all_dist_type($params=FALSE){
        
        
        $plant_id = $params['plant_id'];
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_all_dist_type($plant_id);
       
               
               return $success; 
    }
    
    public function Get_Allitemlist($params=FALSE){
        
        
        $dist_type = $params['dist_type'];
        $dist_state_id = $params['dist_state_id'];
        $plant_id = $params['plant_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_Allitemlist($dist_type,$dist_state_id,$plant_id);
       
               
               return $success; 
    }
    
    public function Get_next_order_number($params=FALSE){
        
        
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_next_order_number();
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$dd);
                                return $response;*/
               return $success; 
    }
    
    public function search_Customer($params=FALSE){
        
        
        $search_key = $params['customer_name'];
        $plant_id= $params['plant_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->search_Customer($search_key,$plant_id);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$dd);
                                return $response;*/
               return $success; 
    }
    
    public function single_Customer_detail($params=FALSE){
        
        
        $customer_id = $params['customer_id'];
        $plant_id = $params['plant_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->single_Customer_detail($customer_id,$plant_id);
       
               /* $response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$customer_id);
                                return $response;*/
               return $success; 
    }
    
    public function Get_CustomerGroup($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Get_CustomerGroup();
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
    
    public function addCustomer($params=FALSE){
        
        $company = $params['company'];
        $vat = $params['gst'];
        $phonenumber = $params['phonenumber'];
        $country = $params['country'];
        $city = $params['city'];
        $zip = $params['zip'];
        $state = $params['state'];
        $address = $params['address'];
        $addedfrom = $params['addedfrom'];
        
        $groups_in = $params['groups_in'];
        
        
        $is_primary = $params['is_primary'];
        $firstname = $params['firstname'];
        $lastname = $params['lastname'];
        $title = $params['title'];
        $email = $params['email'];
        $password = $params['password'];
        
            $companydata=array(
                                "company"=>$company,
                                "vat"=>$vat,
                                "phonenumber"=>$phonenumber,
                                "country"=>$country,
                                "city"=>$city,
                                "zip"=>$zip,
                                "state"=>$state,
                                "address"=>$address,
                                "addedfrom"=>$addedfrom        
                            );  
            $companydata['datecreated'] = date('Y-m-d H:i:s');
            
            if (isset($companydata['groups_in'])) {
            $groups_in = $companydata['groups_in'];
            unset($companydata['groups_in']);
        }
            
            $this->db->insert(db_prefix() . 'clients', $companydata);
            $userid = $this->db->insert_id();
            
            if ($userid) {
                                    
            $contactdata=array(
                        "userid"=>$userid,             
                        "is_primary"=>$is_primary,
                        "firstname"=>$firstname,
                        "lastname"=>$lastname,
                        "title"=>$title,
                        "email"=>$email,
                        "password"=>$password
                    );
            
            if (isset($contactdata['password'])) {
            $password_before_hash = $contactdata['password'];
            $contactdata['password']     = app_hash_password($contactdata['password']);
        }
        
        $this->db->insert(db_prefix() . 'contacts', $contactdata);
        $contact_id = $this->db->insert_id();
        
       /* if (isset($groups_in)) {
                foreach ($groups_in as $group) {*/
                    $this->db->insert(db_prefix() . 'customer_groups', [
                        'customer_id' => $userid,
                        'groupid'     => $groups_in,
                    ]);
               /* }
            }*/
        
       
                $response=array("status"=>true,"message"=>"You have Create New Customer successfully");
                                return $response;
               //return $success; 
        }
    }
    
    public function order_place($params=FALSE){
        
        $PlantID = $params['PlantID'];
        if ( date('m') <= 3 ) {
            $FY = date('y') - 1;
        }
        else {
            $FY = date('y');
        }
        $distId = $params['AccountID'];
        $OrdType = $params['OrderType'];
        $this->load->model('UserApp_Model');
        //if($PlantID == 3){
            $CHKPendingOrder = $this->UserApp_Model->CheckPendingOrder($distId,$FY,$PlantID,$OrdType);
        /*}else{
            $CHKPendingOrder = true;
        }*/
        
        if($CHKPendingOrder == true){
            if($PlantID == 1){
                $next_order_number = get_option2('next_order_number_for_cspl',$FY);
            }elseif($PlantID == 2){
                $next_order_number = get_option2('next_order_number_for_cff',$FY);
            }elseif($PlantID == 3){
                $next_order_number = get_option2('next_order_number_for_cbu',$FY);
            }
        //$FY = $params['FY'];
        
        $OrderID = "ORD".$FY.str_pad($next_order_number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
        $AccountID = $params['AccountID'];
        $subtotal = $params['subtotal'];
        $total_tax = $params['total_tax'];
        $OrderAmt = $params['OrderAmt'];
        $OrderAmt_new = round($params['OrderAmt'],2);
        $Crates = $params['Crates'];
        $Cases = $params['Cases'];
        $OrderStatus = $params['OrderStatus'];
        if($params['OrderType']=="Taxable"){
            $OrderType = "TaxItems";
        }else{
            $OrderType = "NonTaxItems";
        }
        $OrderType = $OrderType;
        $order_type = $params['order_type'];
        //$Lupdate = date('Y-m-d h:m:s');
        $date = date('Y-m-d H:i:s');
        $UserID = $params['UserID'];
        $hash = $params['hash'];
        $Items = $params['Item'];
        $this->load->model('UserApp_Model');                                
        $AccountDetails = $this->UserApp_Model->Get_accountDetails($AccountID,$PlantID);
        $AccountState = $AccountDetails->state;
        if($AccountDetails->vat == '' || $AccountDetails->vat == null){
            $order_data=array(
                "PlantID"=>$PlantID,
                "FY"=>$FY,
                "OrderID"=>$OrderID,
                "AccountID"=>$AccountID,
                "OrderAmt"=>$OrderAmt_new,
                "Crates"=>$Crates,
                "Cases"=>$Cases,
                "OrderStatus"=>$OrderStatus,
                "OrderType"=>$OrderType,
                "order_type"=>$order_type,
                "Transdate"=>$date,
                "UserID"=>$UserID,
            );
        }else{
            $order_data=array(
                "PlantID"=>$PlantID,
                "FY"=>$FY,
                "OrderID"=>$OrderID,
                "AccountID"=>$AccountID,
                "GSTNO"=>$AccountDetails->vat,
                "OrderAmt"=>$OrderAmt_new,
                "Crates"=>$Crates,
                "Cases"=>$Cases,
                "OrderStatus"=>$OrderStatus,
                "OrderType"=>$OrderType,
                "order_type"=>$order_type,
                "Transdate"=>$date,
                "UserID"=>$UserID,
            );
        }
                      
        /*$response=array("status"=>true,"message"=>$order_data);
                                return $response;   */ 
            if(count($Items)>0){
                
                $this->db->insert(db_prefix() . 'ordermaster', $order_data);
            if($PlantID == 1){
                $this->db->where('name', 'next_order_number_for_cspl');
                
            }elseif($PlantID == 2){
                $this->db->where('name', 'next_order_number_for_cff');
               
            }elseif($PlantID == 3){
                $this->db->where('name', 'next_order_number_for_cbu');
                
            }
           
        $this->db->set('value', 'value+1', false);
        $this->db->where('FY', $FY);
        $this->db->update(db_prefix() . 'options');
        $ItemIDs = array();
        foreach ($Items as $key1 => $value1) {
            array_push($ItemIDs,$value1["ItemID"]);
        }
        $ItemDetails = $this->UserApp_Model->GetItems($ItemIDs,$PlantID);
            //$i = 1;
            $OrderSum = 0;    
                foreach ($Items as $key => $value) {
                    $CaseQty = $value["CaseQty"];
                    # code...
                    if($value["SuppliedIn"] == "CR"){
                        foreach($ItemDetails as $key3 => $value3) {
                            if($value3['item_code'] == $value["ItemID"]){
                                $CaseQty = $value3['crate_qty'];
                            }
                        }
                    }
                    if($AccountState == "UP"){
                        $GstAmt = $value["cgstamt"] + $value["sgstamt"] + $value["igstamt"];
                        $GstPer = $value["cgst"] + $value["sgst"] + $value["igst"];
                        $cgst = $GstPer / 2;
                        $cgstamt = $GstAmt / 2;
                        $sgst = $GstPer / 2;
                        $sgstamt = $GstAmt / 2;
                        $igst = 0.00;
                        $igstamt = 0.00;
                    }else{
                        $GstAmt = $value["cgstamt"] + $value["sgstamt"] + $value["igstamt"];
                        $GstPer = $value["cgst"] + $value["sgst"] + $value["igst"];
                        $cgst = 0.00;
                        $cgstamt = 0.00;
                        $sgst = 0.00;
                        $sgstamt = 0.00;
                        $igst = $GstPer;
                        $igstamt = $GstAmt;
                    }
                    
                
                    $OrderSum = $OrderSum + $value["NetOrderAmt"];
                    $itemdata=array(
                        "PlantID"=>$value["PlantID"],             
                        "FY"=>$FY,
                        "OrderID"=>$OrderID,
                        "AccountID"=>$value["AccountID"],
                        "ItemID"=>$value["ItemID"],
                        "BasicRate"=>$value["BasicRate"],
                        "SuppliedIn"=>$value["SuppliedIn"],
                        "OrderQty"=>$value["OrderQty"],             
                        "SaleRate"=>$value["SaleRate"],
                        "DiscAmt"=>$value["DiscAmt"],
                        "igst"=>$igst,
                        "igstamt"=>$igstamt,
                        "cgst"=>$cgst,
                        "cgstamt"=>$cgstamt,
                        "sgst"=>$sgst,
                        "sgstamt"=>$sgstamt,
                        "CaseQty"=>$CaseQty,
                        "OrderAmt"=>$value["OrderAmt"],
                        "NetOrderAmt"=>$value["NetOrderAmt"],
                        "Ordinalno"=>$value["Ordinalno"],
                        "UserID"=>$value["UserID"],
                        "TType"=>"O",
                        "TType2"=>"Order",
                        "TransDate"=>$date,
                    );
                //$i++;
                $this->db->insert(db_prefix() . 'history', $itemdata);
                }
            $this->db->set('OrderAmt', $OrderSum);
            $this->db->where('OrderID', $OrderID);
            $this->db->update(db_prefix() . 'ordermaster');
            
                $response=array("status"=>true,"message"=>"New order created successfully", "data"=>$order_data);
                                return $response;
            }else{
                
                $response=array("status"=>true,"message"=>"Please select atleast one item..", "data"=>$order_data);
                                return $response;
            }
        }else{
            $response=array("status"=>true,"message"=>"Please dispatch or cancel your existing order");
                                return $response;
        }
    }
    
    public function increment_next_order_number()
    {
        // Update next invoice number in settings
        $this->db->where('name', 'next_order_number');
        $this->db->set('value', 'value+1', false);
        $this->db->update(db_prefix() . 'options');
    }
    
    public function addenquiry($params=FALSE){
        
        $staff_id = $params['staff_id'];
        $farm_name = $params['farm_name'];
        $contact_person = $params['contact_person'];
        $cp_mobile_no = $params['cp_mobile_no'];
        $address = $params['address'];
        $remark = $params['remark'];
        $state = $params['state'];
        $district = $params['district'];
        $area = $params['area'];
        $revisit = $params['revisit'];
        $status = $params['status'];
        $date = date('Y-m-d');
            $enquirydata=array(
                                "staff_id"=>$staff_id,
                                "farm_name"=>$farm_name,
                                "contact_person"=>$contact_person,
                                "cp_mobile_no"=>$cp_mobile_no,
                                "address"=>$address,
                                "remark"=>$remark,
                                "state"=>$state,
                                "district"=>$district,
                                "area"=>$area,
                                "revisit"=>$revisit,
                                "status"=>$status,
                                "Enq_date"=>$date
                            );  
           
            
            $this->db->insert(db_prefix() . 'so_enquiry', $enquirydata);
            $enquiryid = $this->db->insert_id();
            
            if ($enquiryid) {
                                    
            
       
                $response=array("status"=>true,"message"=>"You have Create New Enquiry successfully");
                                return $response;
               //return $success; 
        }
    }
    
    public function addtour($params=FALSE){
        
        
        $staff_id = $params['staff_id'];
        $cust_ID = $params['cust_ID'];
        $purpose = $params['purpose'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $state = $params['state'];
        $city = $params['city'];
        $area = $params['area'];
        $remark = $params['remark'];
        $status = $params['status'];
        $reason = $params['reason'];
        $PlantID = $params['PlantID'];
        
            $tourdata=array(
                "staff_id"=>$staff_id,
                "PlantID"=>$PlantID,
                "cust_ID"=>$cust_ID,
                "purpose"=>$purpose,
                "start_date"=>$start_date,
                "end_date"=>$end_date,
                "remark"=>$remark,
                "state"=>$state,
                "city"=>$city,
                "area"=>$area,
                "status"=>$status,
                "reason"=>$reason,
            );  
           
            
            $this->db->insert(db_prefix() . 'tour', $tourdata);
            $tourid = $this->db->insert_id();
            
            if ($tourid) {
                                    
            
       
                $response=array("status"=>true,"message"=>"You have Create New Tour successfully");
                                return $response;
               //return $success; 
        }
    }
    
    public function Add_verson($params=FALSE){
        
        $verson = $params['verson'];
        $app_url = $params['app_url'];
        $status = 1;
        
            $appdata=array(
                                "verson"=>$verson,
                                "app_url"=>$app_url,
                                "status"=>$status      
                            );  
            $appdata['created_date'] = date('Y-m-d H:i:s');
            
            $this->db->insert(db_prefix() . 'app_version', $appdata);
            $insertid = $this->db->insert_id();
            
            if ($insertid) {
                                    
                $this->db->where("id !=",$insertid); 
                $this->db->set('status',0);
                $this->db->update(db_prefix() . 'app_version');
       
                $response=array("status"=>true,"message"=>"You have Create New Verson successfully");
                                return $response;
               //return $success; 
        }
    }
    
    public function Get_App_Version($params=FALSE){
        
        
        $this->load->model('UserApp_Model');
        $status = 1;
        $success = $this->UserApp_Model->Get_App_Version($status);
       
                /*$response=array("status"=>true,"message"=>"You have logined successfully","user_data"=>$user_data);
                                return $response;*/
               return $success; 
    }
   
  public function AddExpense_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                // if($decode['image']){
                //       $image = base64_decode($decode['image']);
                // $image_name = md5(uniqid(rand(), true));
                // $filename = $image_name . '.' . 'png';
                // //rename file name with random number
                //   $staff_d = $this->db->get_where('tblstaff',array('staffid',$decode['UserID']))->row();
                // //   mkdir($staff_d->AccountID);
                //   if (!file_exists('assets/expense_file/'.$staff_d->AccountID)) {
                //     mkdir('assets/expense_file/'.$staff_d->AccountID, 0777, true);
                // }
                // $path = "assets/expense_file/".$staff_d->AccountID."/".$filename;
                // //image uploading folder path
                // file_put_contents($path , $image);
                // }else{
                //     $path = '';
                // }
                //  $all_img_url = array();
                if($decode['date'] == ''){
                     $response = array("error" => true,"message" => "Date is requird field."); 
                }else{
                $this->load->model('UserApp_Model');
                 $UserAccount = $this->UserApp_Model->Get_user_details_by_userID($decode['UserID']);
        $AccountID = $UserAccount->AccountID;
                if($decode['image1']){
                    
                      $image1 = base64_decode($decode['image1']);
                $image_name = md5(uniqid(rand(), true));
                $filename = $image_name . '.' . 'png';
                //rename file name with random number
                //   $staff_d = $this->db->get_where('tblstaff',array('staffid',$decode['UserID']))->row();
                //   mkdir($staff_d->AccountID);
                   if (!file_exists('assets/expense_file/'.$AccountID)) {
                    mkdir('assets/expense_file/'.$AccountID, 0777, true);
                }
                $path1 = "assets/expense_file/".$AccountID."/".$filename;
               
                file_put_contents($path1 , $image1);
                // array_push($all_img_url,$path1);
                }else{
                  $path1 = '';  
                }
                
                if($decode['image2']){
                    
                      $image2 = base64_decode($decode['image2']);
                $image_name = md5(uniqid(rand(), true));
                $filename = $image_name . '.' . 'png';
                //rename file name with random number
                //   $staff_d = $this->db->get_where('tblstaff',array('staffid',$decode['UserID']))->row();
                //   mkdir($staff_d->AccountID);
                  if (!file_exists('assets/expense_file/'.$AccountID)) {
                    mkdir('assets/expense_file/'.$AccountID, 0777, true);
                }
                $path2 = "assets/expense_file/".$AccountID."/".$filename;
               
                file_put_contents($path2 , $image2);
                // array_push($all_img_url,$path2);
                }else{
                  $path2 = '';  
                }
                if($decode['image3']){
                    
                      $image3 = base64_decode($decode['image3']);
                $image_name = md5(uniqid(rand(), true));
                $filename = $image_name . '.' . 'png';
                //rename file name with random number
                //   $staff_d = $this->db->get_where('tblstaff',array('staffid',$decode['UserID']))->row();
                //   mkdir($staff_d->AccountID);
                  if (!file_exists('assets/expense_file/'.$AccountID)) {
                    mkdir('assets/expense_file/'.$AccountID, 0777, true);
                }
                $path3 = "assets/expense_file/".$AccountID."/".$filename;
               
                file_put_contents($path3 , $image3);
                // array_push($all_img_url,$path3);
                }else{
                  $path3 = '';  
                }
                if($decode['image4']){
                    
                      $image4 = base64_decode($decode['image4']);
                $image_name = md5(uniqid(rand(), true));
                $filename = $image_name . '.' . 'png';
                //rename file name with random number
                //   $staff_d = $this->db->get_where('tblstaff',array('staffid',$decode['UserID']))->row();
                //   mkdir($staff_d->AccountID);
                  if (!file_exists('assets/expense_file/'.$AccountID)) {
                    mkdir('assets/expense_file/'.$AccountID, 0777, true);
                }
                $path4 = "assets/expense_file/".$AccountID."/".$filename;
               
                file_put_contents($path4 , $image4);
                // array_push($all_img_url,$path4);
                }else{
                  $path4 = '';  
                }
                if($decode['image5']){
                    
                      $image5 = base64_decode($decode['image5']);
                $image_name = md5(uniqid(rand(), true));
                $filename = $image_name . '.' . 'png';
                //rename file name with random number
                //   $staff_d = $this->db->get_where('tblstaff',array('staffid',$decode['UserID']))->row();
                //   mkdir($staff_d->AccountID);
                  if (!file_exists('assets/expense_file/'.$AccountID)) {
                    mkdir('assets/expense_file/'.$AccountID, 0777, true);
                }
                $path5 = "assets/expense_file/".$AccountID."/".$filename;
               
                file_put_contents($path5 , $image5);
                // array_push($all_img_url,$path5);
                }else{
                  $path5 = '';  
                }
              
                            $data=array(
                                 "image_path1"=>$path1,
                                         "image_path2"=>$path2,
                                         "image_path3"=>$path3,
                                         "image_path4"=>$path4,
                                         "image_path5"=>$path5,
                                        // "image_path"=>$path,
                                        "PlantID"=>$decode['PlantID'],
                                        "UserID"=>$decode['UserID'],
                                        "date"=>$decode['date'],
                                        "da_type"=>$decode['da_type'],
                                        "market"=>$decode['market'],
                                        "travel_mode"=>$decode['travel_mode'],
                                        "travel_expenses"=>$decode['travel_expenses'],
                                        "kilometer"=>$decode['kilometer'],
                                        "misc_expenses"=>$decode['misc_expenses'],
                                        "reason"=>$decode['reason'],
                                        "previous_file"=>$decode['previous_file']
                                      );
                            
                            $response=$this->addexpenses_data($data);
                }       
                }
            }
            
        echo json_encode($response);    
    }
    
    public function addexpense($params=FALSE){
        
        $this->load->model('UserApp_Model');
        $image_path = $params['image_path'];
        $date = $params['date'];
        $PlantID = $params['PlantID'];
        $UserID = $params['UserID'];
        $UserAccount = $this->UserApp_Model->Get_user_details_by_userID($UserID);
        $AccountID = $UserAccount->AccountID;
        $da_type = $params['da_type'];
        $market = $params['market'];
        $travel_mode = $params['travel_mode'];
        $travel_expenses = $params['travel_expenses'];
        $kilometer = $params['kilometer'];
        $misc_expenses = $params['misc_expenses'];
        $reason = $params['reason'];
        $previous_file = $params['previous_file'];
        
        if ( date('m') <= 3 ) {
            $FY = date('y') - 1;
        }
        else {
            $FY = date('y');
        }
        
        if($previous_file == 1){
            $last_row=$this->db->select('image_path,id')->order_by('id',"desc")->limit(1)->get_where(db_prefix() . 'claimexpense',array('UserID'=>$AccountID))->row();
            $image_path = $last_row->image_path;
        }
            $expanse_data=array(
                                "image_path"=>$image_path,
                                "PlantID"=>$PlantID,
                                "FY"=>$FY,
                                "UserID"=>$AccountID,
                                "date"=>$date,
                                "da_type"=>$da_type,
                                "market"=>$market,
                                "travel_mode"=>$travel_mode,
                                "travel_expenses"=>$travel_expenses,
                                "kilometer"=>$kilometer,
                                "misc_expenses"=>$misc_expenses,
                                "reason"=>$reason,
                                "previous_file"=>$previous_file,
                            );  
           
            
            $this->db->insert(db_prefix() . 'claimexpense', $expanse_data);
            $expense_id = $this->db->insert_id();
            
            if ($expense_id) {
                                    
            if($image_path != '' || $previous_file != ''){
                $response=array("file_uploaded"=>"1","status"=>true,"data"=>$expanse_data,"message"=>"You have Create New Expense successfully");
                                return $response;
               return $success;  
            }else{
                 $response=array("file_uploaded"=>"0","status"=>true,"data"=>$expanse_data,"message"=>"You have Create New Expense successfully");
                                return $response;
               return $success; 
            }
       
               
        }
    }
    public function addexpenses_data($params=FALSE){
         $this->load->model('UserApp_Model');
         
        $UserID = $params['UserID'];
        
        $UserAccount = $this->UserApp_Model->Get_user_details_by_userID($UserID);
        $ACTID = $UserAccount->AccountID;
        $month = substr($params['date'],5,2);
        if ( $month <= 3 ) {
            $FY = date('y') - 1;
        }
        else {
            $FY = date('y');
        }
        
        $all_img_url = array();
        $image_path1 = $params['image_path1'];
        $image_path2 = $params['image_path2'];
        $image_path3 = $params['image_path3'];
        $image_path4 = $params['image_path4'];
        $image_path5 = $params['image_path5'];
        if($image_path1 != ''){
             array_push($all_img_url,$image_path1);
        }
         if($image_path2 != '' ){
             array_push($all_img_url,$image_path2);
        }
         if($image_path3 != '' ){
             array_push($all_img_url,$image_path3);
        }
         if($image_path4 != '' ){
             array_push($all_img_url,$image_path4);
        }
         if($image_path5 != '' ){
             array_push($all_img_url,$image_path5);
        }
            $all_img  = implode(",",$all_img_url);
            
        $date = $params['date'];
        $PlantID = $params['PlantID'];
        $UserID = $params['UserID'];
        $da_type = $params['da_type'];
        $market = $params['market'];
        $travel_mode = $params['travel_mode'];
        $travel_expenses = $params['travel_expenses'];
        $kilometer = $params['kilometer'];
        $misc_expenses = $params['misc_expenses'];
        $reason = $params['reason'];
        $previous_file = $params['previous_file'];
            
        $this->db->select();
        $this->db->from(db_prefix() . 'claimexpense');
        $this->db->where(db_prefix() . 'claimexpense.PlantID', $PlantID);
        $this->db->where(db_prefix() . 'claimexpense.UserID', $ACTID);
        $this->db->where(db_prefix() . 'claimexpense.FY', $FY);
        $this->db->where(db_prefix() . 'claimexpense.date', $date);
        $this->db->order_by('id', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
     $array_data = $this->db->get()->row_array();
    //  echo $this->db->last_query();die;
    //  print_r($array_data);die;
        if(count($array_data) > 0){
            
            
        $images = explode(",",$array_data['image_path']);
         
        foreach($images as $val){
            
             array_push($all_img_url,$val);
        }
        $all_img_url1  = implode(",",$all_img_url);
    
            $expanse_data=array(
                                "image_path"=>$all_img_url1,
                                "PlantID"=>$PlantID,
                                "FY"=>$FY,
                                "UserID"=>$ACTID,
                                "date"=>$date,
                                "da_type"=>$da_type,
                                "market"=>$market,
                                "travel_mode"=>$travel_mode,
                                "travel_expenses"=>$travel_expenses,
                                "kilometer"=>$kilometer,
                                "misc_expenses"=>$misc_expenses,
                                "reason"=>$reason,
                                "previous_file"=>$previous_file,
                                "Lupdate" =>date('Y-m-d H:i:s'),
                            );   
                   $expanse_data1=array(
                                "image_path"=>$all_img_url,
                                "PlantID"=>$PlantID,
                                "FY"=>$FY,
                                "UserID"=>$ACTID,
                                "date"=>$date,
                                "da_type"=>$da_type,
                                "market"=>$market,
                                "travel_mode"=>$travel_mode,
                                "travel_expenses"=>$travel_expenses,
                                "kilometer"=>$kilometer,
                                "misc_expenses"=>$misc_expenses,
                                "reason"=>$reason,
                                "previous_file"=>$previous_file,
                                "image_path1" => $params['image_path1'],
                                "image_path2" => $params['image_path2'],
                                "image_path3" => $params['image_path3'],
                                "image_path4" => $params['image_path4'],
                                "image_path5" => $params['image_path5'],
                                
                            ); 
                            // print_r($expanse_data);die;
                    $this->db->where(db_prefix() . 'claimexpense.PlantID', $PlantID);
                    $this->db->where(db_prefix() . 'claimexpense.UserID', $ACTID);
                    $this->db->where(db_prefix() . 'claimexpense.FY', $FY);
                    $this->db->where(db_prefix() . 'claimexpense.date', $date);
                    $expense_id= $this->db->update(db_prefix() . 'claimexpense', $expanse_data);
        // echo $this->db->last_query();die;
            // $this->db->insert(db_prefix() . 'claimexpense', $expanse_data);
            // $expense_id = $this->db->insert_id();
            
            if ($expense_id) {
                                    
            if($image_path != '' || $previous_file != ''){
                $response=array("file_uploaded"=>"1","status"=>true,"data"=>$expanse_data1,"message"=>"You have Update Expense successfully");
                                return $response;
               return $success;  
            }else{
                 $response=array("file_uploaded"=>"0","status"=>true,"data"=>$expanse_data1,"message"=>"You have Update Expense successfully");
                                return $response;
               return $success; 
            }   
        }
        }else{
            $date = $params['date'];
            $PlantID = $params['PlantID'];
            $UserID = $params['UserID'];
            $da_type = $params['da_type'];
            $market = $params['market'];
            $travel_mode = $params['travel_mode'];
            $travel_expenses = $params['travel_expenses'];
            $kilometer = $params['kilometer'];
            $misc_expenses = $params['misc_expenses'];
            $reason = $params['reason'];
            $previous_file = $params['previous_file'];
      
        if($previous_file == 1){
            $last_row=$this->db->select('image_path,id')->order_by('id',"desc")->limit(1)->get_where(db_prefix() . 'claimexpense',array('UserID'=>$ACTID))->row();
            $image_path = $last_row->image_path;
             $all_img_url  = implode(",",$last_row->image_path);
        }
            $expanse_data=array(
                                "image_path"=>$all_img,
                                "PlantID"=>$PlantID,
                                "FY"=>$FY,
                                "UserID"=>$ACTID,
                                "date"=>$date,
                                "da_type"=>$da_type,
                                "market"=>$market,
                                "travel_mode"=>$travel_mode,
                                "travel_expenses"=>$travel_expenses,
                                "kilometer"=>$kilometer,
                                "misc_expenses"=>$misc_expenses,
                                "reason"=>$reason,
                                "previous_file"=>$previous_file,
                            );   
                   $expanse_data1=array(
                                "image_path"=>$all_img_url,
                                "PlantID"=>$PlantID,
                                "FY"=>$FY,
                                "UserID"=>$ACTID,
                                "date"=>$date,
                                "da_type"=>$da_type,
                                "market"=>$market,
                                "travel_mode"=>$travel_mode,
                                "travel_expenses"=>$travel_expenses,
                                "kilometer"=>$kilometer,
                                "misc_expenses"=>$misc_expenses,
                                "reason"=>$reason,
                                "previous_file"=>$previous_file,
                            ); 
         
            $this->db->insert(db_prefix() . 'claimexpense', $expanse_data);
            $expense_id = $this->db->insert_id();
            
            if ($expense_id) {
                                    
            if($image_path != '' || $previous_file != ''){
                $response=array("file_uploaded"=>"1","status"=>true,"data"=>$expanse_data1,"message"=>"You have Create New Expense successfully");
                                return $response;
               return $success;  
            }else{
                 $response=array("file_uploaded"=>"0","status"=>true,"data"=>$expanse_data1,"message"=>"You have Create New Expense successfully");
                                return $response;
               return $success; 
            }
       
               
        } 
        }
    }
    
    public function GetExpense_API($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                // return $decode;
                  $data=array(
                                         
                                        "PlantID"=>$decode['PlantID'],
                                        "UserID"=>$decode['UserID'],
                                        "date"=>$decode['date']
                                       
                                      );
                   $response=$this->getexpense($data);
                            
                        
                }
            }
            
          
        
        echo json_encode($response);    
    }
     public function getexpense($params=FALSE){
        
         $this->load->model('UserApp_Model');
        
        $date = $params['date'];
        $PlantID = $params['PlantID'];
        $UserID = $params['UserID'];
        
        $UserAccount = $this->UserApp_Model->Get_user_details_by_userID($UserID);
        $AccountID = $UserAccount->AccountID;
        
        $this->db->select();
        $this->db->from(db_prefix() . 'claimexpense');
        $this->db->where(db_prefix() . 'claimexpense.PlantID', $PlantID);
        $this->db->where(db_prefix() . 'claimexpense.UserID', $AccountID);
        $this->db->where(db_prefix() . 'claimexpense.date', $date);
        $this->db->order_by('id', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
     $array_data = $this->db->get()->row_array();
      
            
            if (count($array_data) > 0) {
                      if($array_data['image_path'] !=""){
                          $images = explode(",",$array_data['image_path']); 
                      }else{
                           $images = [];
                      }              
               
                $response=array("status"=>true,"data"=>$array_data,"images"=>$images);
                                return $response;
               
            }else{
                 $response=array("status"=>false,"message"=>"No Data Found");
                                return $response;
               
            }
       
               
        }
        	public function get_targetandAchievementAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                               "staff_id"=>$decode['staff_id'],
                               "PlantID"=>$decode['PlantID'],
                               
                            );
                            $response=$this->Get_targetAchievementAPI($data);
                }
            }
        
        echo json_encode($response);    
    } 
      public function Get_targetAchievementAPI($params=FALSE){
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->targetAchievementAPI($staff_id,$PlantID);
        return $success; 
    }
    public function Get_division_targetandAchievementAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                               "staff_id"=>$decode['staff_id'],
                               "PlantID"=>$decode['PlantID'],
                               "month"=>$decode['month'],
                               "party_id"=>$decode['DistId'],
                               
                            );
                            $response=$this->Get_division_targetAchievementAPI($data);
                }
            }
        
        echo json_encode($response);    
    }
       public function Get_division_targetAchievementAPI($params=FALSE){
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $month = $params['month'];
        $party_id = $params['party_id'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->division_targetAchievementAPI($staff_id,$PlantID,$month,$party_id);
        return $success; 
    }
    
    public function GetSaleReportAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                
                
                            $data=array(
                               "staff_id"=>$decode['staff_id'],
                               "PlantID"=>$decode['PlantID'],
                               "AsOn"=>$decode['AsOn'],
                               "admin"=>$decode['admin'],
                            );
                            $response=$this->GetSaleReport($data);
                }
            }
        
        echo json_encode($response);    
    }
       public function GetSaleReport($params=FALSE){
        $staff_id = $params['staff_id'];
        $PlantID = $params['PlantID'];
        $AsOn = $params['AsOn'];
        $admin = $params['admin'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->GetSaleReport($staff_id,$PlantID,$AsOn,$admin);
        return $success; 
    }
    
    public function GetSaleDetailAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                            $data=array(
                               "SaleID"=>$decode['SaleID']
                            );
                            $response=$this->GetSaleDetail($data);
                }
            }
        echo json_encode($response);    
    }
    
    public function CheckAccountCodeAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                            $data=array(
                               "AccountID"=>$decode['AccountID']
                            );
                            $response=$this->CheckAccountID($data);
                }
            }
        echo json_encode($response);    
    }
    public function CheckAccountID($params=FALSE){
        $AccountID = $params['AccountID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->CheckAccountID($AccountID);
        return $success; 
    }
    
    public function SavePartyAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                            $data=array(
                               "AccountID"=>$decode['AccountID'],
                               "company"=>$decode['company'],
                               "firstname"=>$decode['firstname'],
                               "lastname"=>$decode['lastname'],
                               "phonenumber"=>$decode['phonenumber'],
                               "state"=>$decode['state'],
                               "city"=>$decode['city'],
                               "address"=>$decode['address'],
                               "Address3"=>$decode['Address3'],
                               "zip"=>$decode['zip'],
                               "StationName"=>$decode['StationName'],
                               "addedfrom"=>$decode['addedfrom'],
                               "StartDate"=>date('Y-m-d H:i:s'),
                               "vat"=>$decode['vat'],
                               "Pan"=>$decode['Pan'],
                               "Aadhaarno"=>$decode['Aadhaarno'],
                               "FLNO1"=>$decode['FLNO1'],
                               "email"=>$decode['email'],
                               "DistributorType"=>$decode['DistributorType'],
                               "EnqID"=>$decode['EnqID']
                            );
                            $response=$this->SaveParty($data);
                }
            }
        echo json_encode($response);    
    }
    public function SaveParty($params=FALSE){
        $AccountID = $params['AccountID'];
        $EnqID = $params['EnqID'];
        $Clientdata = array(
            'AccountID' =>$params['AccountID'],
            'company' =>$params['company'],
            'phonenumber' =>$params['phonenumber'],
            'state' =>$params['state'],
            'city' =>$params['city'],
            'address' =>$params['address'],
            'Address3' =>$params['Address3'],
            'zip' =>$params['zip'],
            'StationName' =>$params['StationName'],
            'addedfrom' =>$params['addedfrom'],
            'StartDate' =>$params['StartDate'],
            "SubActGroupID" =>'60001004',
            "country" =>'102',
            'DistributorType' =>$params['DistributorType'],
            'vat' =>$params['vat']
        );
        
        $Contactdata = array(
            'AccountID' =>$params['AccountID'],
            'firstname' =>$params['firstname'],
            'lastname' =>$params['lastname'],
            'phonenumber' =>$params['phonenumber'],
            'datecreated' =>date('Y-m-d H:i:s'),
            'email' =>$params['email'],
            'FLNO1' =>$params['FLNO1'],
            'Aadhaarno' =>$params['Aadhaarno'],
            'Pan' =>$params['Pan'],
        );
        if ( date('m') <= 3 ) {
            $FY = date('y') - 1;
        }
        else {
            $FY = date('y');
        }
        $Baldata = array(
            'AccountID' =>$params['AccountID'],
            'FY' =>$FY,
            'BAL1' =>'0'
        );
        $this->load->model('UserApp_Model');
        $checkParty = $this->UserApp_Model->CheckParty($AccountID);
        
        if($checkParty == false){
            $success = array("status"=>false,"message"=>"AccountID already used...");
        }else{
            $success = $this->UserApp_Model->SavePartyDetails($Clientdata,$Contactdata,$Baldata,$EnqID);
        }
        return $success; 
    }
    
    public function GetSaleDetail($params=FALSE){
        $SaleID = $params['SaleID'];
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->GetSaleDetails($SaleID);
        return $success; 
    }
    
    public function GetOfficeAddressAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }
            else
            {
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data=array(
                        "staff_id"=>$decode['staff_id']
                    );  
                $response=$this->GetOfficeAddress($data);
                }
            }
        echo json_encode($response);    
    }
    
    public function GetOfficeAddress($params=FALSE){
        
        $this->load->model('UserApp_Model');
        $staff_id= $params['staff_id'];
        $get_data = $this->UserApp_Model->GetOfficeAddress($staff_id);
        
        return $get_data;
    }
  
}
    ?>