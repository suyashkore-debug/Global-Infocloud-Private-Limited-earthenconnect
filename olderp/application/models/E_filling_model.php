<?php

defined('BASEPATH') or exit('No direct script access allowed');

class E_filling_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    function getaccounts($postData){

    $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_clients = '';
    
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       $this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountroutes.RouteID,'.db_prefix() . 'route.name,'.db_prefix() . 'xx_statelist.state_name,'.db_prefix() . 'customers_groups.name AS aname');
       $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR tblclients.AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID = 60001004';
       
       $this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'clients.PlantID','left');
       $this->db->join(db_prefix() . 'route', '' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'accountroutes.RouteID AND ' . db_prefix() . 'route.PlantID = ' . db_prefix() . 'accountroutes.PlantID','left');
       $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state','left');
       $this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'clients.PlantID','left');
       $this->db->where($where_clients);
      
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        
       $records = $this->db->get(db_prefix() . 'clients')->result();

       foreach($records as $row ){
          $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"address"=>$row->address,"address2"=>$row->Address3,"state"=>$row->state,"station"=>$row->StationName,"gst"=>$row->vat,"route"=>$row->RouteID,"route_name"=>$row->name,"state_name"=>$row->state_name,"account_type"=>$row->DistributorType,"account_type_name"=>$row->aname);
       }

     }

     return $response;
  }
  
  public function get_Account_Details($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        $AccountID = $postData['AccountID'];
        $this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountroutes.RouteID,'.db_prefix() . 'route.name,'.db_prefix() . 'xx_statelist.state_name,'.db_prefix() . 'customers_groups.name AS aname');
       
        $this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'clients.PlantID','left');
        $this->db->join(db_prefix() . 'route', '' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'accountroutes.RouteID AND ' . db_prefix() . 'route.PlantID = ' . db_prefix() . 'accountroutes.PlantID','left');
        $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state','left');
        $this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'clients.PlantID','left');
        $this->db->where(db_prefix() . 'clients.AccountID', $AccountID);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        return $this->db->get(db_prefix() . 'clients')->row();
      
    }
    public function get_data_for_table($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
       $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
         if($data['bill_wise_type'] == 2){
          $this->db->select(db_prefix() . 'salesmaster.Transdate,sum('.db_prefix() . 'salesmaster.SaleAmt) as SaleAmt,sum('.db_prefix() . 'salesmaster.sgstamt) as sgstamt,sum('.db_prefix() . 'salesmaster.cgstamt) as cgstamt,sum('.db_prefix() . 'salesmaster.igstamt) as igstamt,sum('.db_prefix() . 'salesmaster.BillAmt) as BillAmt');  
       }else{
          $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate,'.db_prefix() . 'salesmaster.SaleAmt,'.db_prefix() . 'salesmaster.sgstamt,'.db_prefix() . 'salesmaster.cgstamt,'.db_prefix() . 'salesmaster.igstamt,'.db_prefix() . 'salesmaster.BillAmt,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.company');  
       }
       
 
        $this->db->from(db_prefix() . 'salesmaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
        
       $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'salesmaster.FY', $year);
       $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        // $this->db->where(db_prefix() . 'salesmaster.BT != B');
        if($data['accountId'] != ''){
            $this->db->where(db_prefix() . 'salesmaster.AccountID', $data['accountId']);
       }
        if($data['bill_type'] == 2){
            $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
       }else if($data['bill_type'] == 3){
            $this->db->where(db_prefix() . 'salesmaster.gstno IS  NULL');
       }
       if($data['bill_wise_type'] == 2){
           $this->db->group_by('DAY('.db_prefix() . 'salesmaster.Transdate)');
       }
       $this->db->order_by(db_prefix() . 'salesmaster.SalesID', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function get_GstType($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        /*$this->db->select(db_prefix() . 'history.*');  
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'salesmaster', db_prefix() . 'salesmaster.SalesID = ' . db_prefix() . 'history.TransID AND  '.db_prefix() . 'salesmaster.PlantID = ' . db_prefix() . 'history.PlantID AND '.db_prefix() . 'salesmaster.FY = ' . db_prefix() . 'history.FY');
        $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->order_by(db_prefix() . 'history.Transdate', 'ASC');*/
        
        $this->db->select(db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.igst,');  
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.Transdate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.TType', "O");
        $this->db->where(db_prefix() . 'history.TType2', "Order");
        $this->db->order_by(db_prefix() . 'history.Transdate2', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function get_GstTypeWiseValue($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $this->db->select(db_prefix() . 'history.TransID, '. db_prefix() . 'history.cgst, '. db_prefix() . 'history.sgst, '. db_prefix() . 'history.igst, 
        SUM('. db_prefix() . 'history.sgstamt ) AS sgstsum, SUM('. db_prefix() . 'history.cgstamt ) AS cgstsum, SUM('. db_prefix() . 'history.igstamt ) AS igstsum ,
         SUM('. db_prefix() . 'history.ChallanAmt ) AS taxableAmt,'.db_prefix() . 'history.TransDate2');  
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->where(db_prefix() . 'history.TransID IS NOT NULL');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.TType', 'O');
        $this->db->where(db_prefix() . 'history.TType2', 'Order');
        if($data['bill_type'] == 2 || $data['bill_type'] == 3){
           $this->db->join(db_prefix() . 'salesmaster', db_prefix() . 'salesmaster.SalesID = ' . db_prefix() . 'history.TransID AND  '.db_prefix() . 'salesmaster.PlantID = ' . db_prefix() . 'history.PlantID AND '.db_prefix() . 'salesmaster.FY = ' . db_prefix() . 'history.FY');
        }
        
        if($data['accountId'] != ''){
            $this->db->where(db_prefix() . 'history.AccountID', $data['accountId']);
       }
        if($data['bill_type'] == 2){
            $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
            //$this->db->where(db_prefix() . 'salesmaster.gstno > 0');
       }else if($data['bill_type'] == 3){
            $this->db->where(db_prefix() . 'salesmaster.gstno IS  NULL');
       }
       if($data['bill_wise_type'] == 2){
           //$this->db->group_by('DAY('.db_prefix() . 'salesmaster.Transdate)');
           $this->db->group_by(db_prefix() . 'history.igst, DAY('.db_prefix() . 'history.TransDate2),'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.cgst');
       
           $this->db->order_by(db_prefix() . 'history.TransDate2', 'ASC');
       }else{
          $this->db->group_by(db_prefix() . 'history.TransID, '.db_prefix() . 'history.igst,'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.cgst'); 
          $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
       }
        return $this->db->get()->result_array();
    }
    
    public function get_GstTypeWiseValueP($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $this->db->select(db_prefix() . 'history.BillID, '. db_prefix() . 'history.cgst, '. db_prefix() . 'history.sgst, '. db_prefix() . 'history.igst, 
        SUM('. db_prefix() . 'history.sgstamt ) AS sgstsum, SUM('. db_prefix() . 'history.cgstamt ) AS cgstsum, SUM('. db_prefix() . 'history.igstamt ) AS igstsum ,
         SUM('. db_prefix() . 'history.ChallanAmt ) AS taxableAmt, '.db_prefix() . 'purchasemaster.Transdate,'.db_prefix() . 'history.TransDate2,'.db_prefix() . 'clients.vat');  
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'purchasemaster', db_prefix() . 'purchasemaster.PurchID = ' . db_prefix() . 'history.BillID AND  '.db_prefix() . 'purchasemaster.PlantID = ' . db_prefix() . 'history.PlantID AND '.db_prefix() . 'purchasemaster.FY = ' . db_prefix() . 'history.FY');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasemaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'purchasemaster.PlantID');
        $this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.TType', 'P');
        $this->db->where(db_prefix() . 'history.TType2', 'Purchase');
        if($data['accountId'] != ''){
            $this->db->where(db_prefix() . 'purchasemaster.AccountID', $data['accountId']);
       }
        if($data['bill_type'] == 2){
            $this->db->where_not_in(db_prefix() . 'clients.vat', ['null','',' ']);
       }else if($data['bill_type'] == 3){
            $this->db->where_in(db_prefix() . 'clients.vat', ['null','',' ']);
       }
       if($data['bill_wise_type'] == 2){
           //$this->db->group_by('DAY('.db_prefix() . 'salesmaster.Transdate)');
           $this->db->group_by(db_prefix() . 'history.igst, DAY('.db_prefix() . 'purchasemaster.Transdate),'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.cgst');
       
           $this->db->order_by(db_prefix() . 'purchasemaster.Transdate', 'ASC');
       }else{
          $this->db->group_by(db_prefix() . 'history.BillID, '.db_prefix() . 'history.igst,'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.cgst'); 
          $this->db->order_by(db_prefix() . 'purchasemaster.Transdate', 'ASC');
           
       }
        
        return $this->db->get()->result_array();
    }
    
    public function get_GstTypeP($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $this->db->select(db_prefix() . 'history.*');  
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'purchasemaster', db_prefix() . 'purchasemaster.PurchID = ' . db_prefix() . 'history.OrderID AND  '.db_prefix() . 'purchasemaster.PlantID = ' . db_prefix() . 'history.PlantID AND '.db_prefix() . 'purchasemaster.FY = ' . db_prefix() . 'history.FY');
        $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->order_by(db_prefix() . 'history.Transdate', 'ASC');
        return $this->db->get()->result_array();
    }
    
     
    public function get_purchase_data_for_table($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
       $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
         if($data['bill_wise_type'] == 2){
          $this->db->select(db_prefix() . 'purchasemaster.Transdate, '.db_prefix() . 'purchasemaster.PurchID,sum('.db_prefix() . 'purchasemaster.Purchamt) as Purchamt,sum('.db_prefix() . 'purchasemaster.Invamt) as Invamt,sum('.db_prefix() . 'purchasemaster.sgstamt) as sgstamt,sum('.db_prefix() . 'purchasemaster.cgstamt) as cgstamt,sum('.db_prefix() . 'purchasemaster.igstamt) as igstamt,sum('.db_prefix() . 'purchasemaster.Invamt) as Invamt');  
       }else{
          $this->db->select(db_prefix() . 'purchasemaster.PurchID,'.db_prefix() . 'purchasemaster.Transdate,'.db_prefix() . 'purchasemaster.Purchamt,'.db_prefix() . 'purchasemaster.Invamt,'.db_prefix() . 'purchasemaster.sgstamt,'.db_prefix() . 'purchasemaster.cgstamt,'.db_prefix() . 'purchasemaster.igstamt,'.db_prefix() . 'purchasemaster.Invamt,'.db_prefix() . 'clients.vat,'.db_prefix() . 'clients.company');  
       }
       
 
        $this->db->from(db_prefix() . 'purchasemaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasemaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'purchasemaster.PlantID');
        
       $this->db->where(db_prefix() . 'purchasemaster.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'purchasemaster.FY', $year);
       $this->db->where(db_prefix() . 'purchasemaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        // $this->db->where(db_prefix() . 'purchasemaster.BT != B');
        if($data['accountId'] != ''){
            $this->db->where(db_prefix() . 'purchasemaster.AccountID', $data['accountId']);
       }
        if($data['bill_type'] == 2){
            /*$this->db->where(db_prefix() . 'clients.vat IS NOT NULL');
            $this->db->where(db_prefix() . 'clients.vat > 0');*/
            $this->db->where_not_in(db_prefix() . 'clients.vat', ['null','',' ']);
       }else if($data['bill_type'] == 3){
            //$this->db->where(db_prefix() . 'clients.vat IS  NULL');
            $this->db->where_in(db_prefix() . 'clients.vat', ['null','',' ']);
       }
       if($data['bill_wise_type'] == 2){
           $this->db->group_by('DAY('.db_prefix() . 'purchasemaster.Transdate)');
       }
       $this->db->order_by(db_prefix() . 'purchasemaster.PurchID', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function GetDataForB2B2($data){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        $BT = array('T','M','C');
        $BT2 = array('T','C'); 
        // for taxable
        $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.BillAmt AS INVAMT,'.db_prefix() . 'salesmaster.tcsAmt,'.db_prefix() . 'salesmaster.AccountID,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state');
        $this->db->from(db_prefix() . 'salesmaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
       $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'salesmaster.FY', $year);
       $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT2);
       $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
       $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $SaleData = $this->db->get()->result_array();
        $SaleIds = array();
        foreach ($SaleData as $key => $value) {
            array_push($SaleIds,$value['SalesID']);
        }
         if(!empty($SaleIds)){
            $this->db->select(db_prefix() . 'history.TransID,'.db_prefix() . 'history.TransDate2,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt ,SUM('.db_prefix() . 'history.NetChallanAmt) AS BillAmt');
            $this->db->from(db_prefix() . 'history');
            $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.FY', $year);
            $this->db->group_by(db_prefix() . 'history.TransID,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst');
            $this->db->where_in(db_prefix() . 'history.TransID', $SaleIds);
            $this->db->where(db_prefix() . 'history.TType', 'O');
            $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
            $historyData = $this->db->get()->result_array(); 
         }else{
            $historyData = array();
         }
        
        // B2CL
        $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.BillAmt AS INVAMT,'.db_prefix() . 'salesmaster.tcsAmt,'.db_prefix() . 'salesmaster.AccountID,'.db_prefix() . 'salesmaster.Transdate AS BillDate,'.db_prefix() . 'clients.state');
        $this->db->from(db_prefix() . 'salesmaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
        $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'salesmaster.FY', $year);
        $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT);
        $this->db->where(db_prefix() . 'clients.state !=', 'UP');
        $this->db->where(db_prefix() . 'salesmaster.BillAmt >', '250000');
        $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
        $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $SaleData2 = $this->db->get()->result_array();
        
        $SaleIds2 = array();
        foreach ($SaleData2 as $key => $value) {
            array_push($SaleIds2,$value['SalesID']);
        }
        if(!empty($SaleIds2)){
            $this->db->select(db_prefix() . 'history.TransID,'.db_prefix() . 'history.TransDate2,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt ,SUM('.db_prefix() . 'history.NetChallanAmt) AS BillAmt');
            $this->db->from(db_prefix() . 'history');
            $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.FY', $year);
            $this->db->group_by(db_prefix() . 'history.TransID,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst');
            $this->db->where_in(db_prefix() . 'history.TransID', $SaleIds2);
            $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
            $historyData2 = $this->db->get()->result_array();
        }else{
            $historyData2 = array();
        }
        
        
        // B2CS
        // 1
        $B2CSSaleIds1 = array();
        
        $this->db->select(db_prefix() . 'salesmaster.SalesID');
        $this->db->from(db_prefix() . 'salesmaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
        $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'salesmaster.FY', $year);
        $this->db->where(db_prefix() . 'salesmaster.BT', 'M');
        $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
        $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $B2CSSaleIDTRN = $this->db->get()->result_array();
        
        foreach ($B2CSSaleIDTRN as $key1 => $value1) {
            array_push($B2CSSaleIds1,$value1['SalesID']);
        }
        
        
        $this->db->select(db_prefix() . 'salesmaster.SalesID');
        $this->db->from(db_prefix() . 'salesmaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
        $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'salesmaster.FY', $year);
        $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT);
        $this->db->where(db_prefix() . 'clients.state ', 'UP');
        $this->db->where(db_prefix() . 'salesmaster.BillAmt >', '250000');
        $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
        $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $B2CSSaleID = $this->db->get()->result_array();
        
        
        foreach ($B2CSSaleID as $key => $value) {
            array_push($B2CSSaleIds1,$value['SalesID']);
        }
        if(!empty($B2CSSaleIds1)){
            $this->db->select(db_prefix() . 'history.TransID,'.db_prefix() . 'clients.state,'.db_prefix() . 'history.TransDate2,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt ');
            $this->db->from(db_prefix() . 'history');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
            $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.FY', $year);
            $this->db->group_by(db_prefix() . 'clients.state,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.igst');
            $this->db->where_in(db_prefix() . 'history.TransID', $B2CSSaleIds1);
            $this->db->where(db_prefix() . 'history.TType', 'O');
            $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
            $B2CS1 = $this->db->get()->result_array();
        }else{
            $B2CS1 = array();
        }
        
        // 2
        $this->db->select(db_prefix() . 'salesmaster.SalesID');
        $this->db->from(db_prefix() . 'salesmaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
        $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'salesmaster.FY', $year);
        $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT);
        $this->db->where(db_prefix() . 'salesmaster.BillAmt <=', '250000');
        $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
        $this->db->where(db_prefix() . 'salesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $B2CSSaleID2 = $this->db->get()->result_array();
        
        $B2CSSaleIds2 = array();
        /*foreach ($B2CSSaleIDTRN as $key1 => $value1) {
            array_push($B2CSSaleIds2,$value1['SalesID']);
        }*/
        
        foreach ($B2CSSaleID2 as $key => $value) {
            array_push($B2CSSaleIds2,$value['SalesID']);
        }
        if(!empty($B2CSSaleIds2)){
            $this->db->select(db_prefix() . 'history.TransID,'.db_prefix() . 'clients.state,'.db_prefix() . 'history.TransDate2,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.igst,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt ');
            $this->db->from(db_prefix() . 'history');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
            $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.FY', $year);
            $this->db->group_by(db_prefix() . 'clients.state,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst,'.db_prefix() . 'history.igst');
            $this->db->where_in(db_prefix() . 'history.TransID', $B2CSSaleIds2);
            $this->db->where(db_prefix() . 'history.TType', 'O');
            $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
            $B2CS2 = $this->db->get()->result_array();
        }else{
            $B2CS2 = array();
        }
        
        
        // CDNR
        
        $this->db->select(db_prefix() . 'history.TransID');
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.TType', 'R');
        $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->group_by(db_prefix() . 'history.TransID,'.db_prefix() . 'history.OrderID');
        $CDNRSaleID2 = $this->db->get()->result_array();
        if(!empty($CDNRSaleID2)){
            $CDNRSaleIDS2 = array();
            foreach ($CDNRSaleID2 as $key => $value) {
                array_push($CDNRSaleIDS2,$value['TransID']);
            } 
            if(!empty($CDNRSaleIDS2)){
                $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate AS SaleDate,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'salesmaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
                $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $CDNRSaleIDS2);
                $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
                $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT2);
                $CDNRSaleIDGST2 = $this->db->get()->result_array();
                
                
                
                $SaleIDSGST2 = array();
                foreach ($CDNRSaleIDGST2 as $key => $value) {
                    array_push($SaleIDSGST2,$value['SalesID']);
                }
                
                
                if(!empty($SaleIDSGST2)){
                    $this->db->select(db_prefix() . 'history.TransID,'.db_prefix() . 'history.OrderID,'.db_prefix() . 'history.Transdate AS SaleRTNDate,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.sgst,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt,SUM('.db_prefix() . 'history.NetChallanAmt) AS BillAmt ');
                    $this->db->from(db_prefix() . 'history');
                    $this->db->where_in(db_prefix() . 'history.TransID', $SaleIDSGST2);
                    $this->db->where(db_prefix() . 'history.TType', 'R');
                    $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
                    $this->db->group_by(db_prefix() . 'history.TransID,'.db_prefix() . 'history.OrderID,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst');
                    $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
                    $CDNR1 = $this->db->get()->result_array();
                }else{
                    $CDNR1 = array();
                }
            }else{
                $CDNR1 = array();
            }
        }else{
            $CDNR1 = array();
        }
        
        // CDNR2
            
            $this->db->select(db_prefix() . 'cdnotehistory.TransID');
            $this->db->from(db_prefix() . 'cdnotehistory');
            $this->db->where(db_prefix() . 'cdnotehistory.plantid', $selected_company);
            $this->db->where(db_prefix() . 'cdnotehistory.fy', $year);
            $this->db->where(db_prefix() . 'cdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
            $this->db->group_by(db_prefix() . 'cdnotehistory.billno');
            $CDNRSaleID = $this->db->get()->result_array();
        if(!empty($CDNRSaleID)){
            $CDNRSaleIDS = array();
            foreach ($CDNRSaleID as $key => $value) {
                array_push($CDNRSaleIDS,$value['TransID']);
            } 
            if(!empty($CDNRSaleIDS)){
                $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate AS SaleDate,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'salesmaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
                $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $CDNRSaleIDS);
                $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
                $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT2);
                //$this->db->where_not_in(db_prefix() . 'salesmaster.gstno', ['null','',' ']);
                $CDNRSaleIDGST = $this->db->get()->result_array();
                
                // for Purchase credit note
               /* $this->db->select(db_prefix() . 'purchasemaster.PurchID,'.db_prefix() . 'purchasemaster.Transdate AS SaleDate,'.db_prefix() . 'clients.vat,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'purchasemaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasemaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'purchasemaster.PlantID');
                $this->db->where(db_prefix() . 'purchasemaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'purchasemaster.PurchID', $CDNRSaleIDS);
                $this->db->where(db_prefix() . 'purchasemaster.BT', "Y");
                $CDNRSaleIDGST22 = $this->db->get()->result_array();
                */
                $SaleIDSGST = array();
                foreach ($CDNRSaleIDGST as $key => $value) {
                    array_push($SaleIDSGST,$value['SalesID']);
                }
                
                /*foreach ($CDNRSaleIDGST22 as $key => $value) {
                    array_push($SaleIDSGST,$value['PurchID']);
                }*/
                if(!empty($SaleIDSGST)){
                    $this->db->select(db_prefix() . 'cdnotehistory.billno,'.db_prefix() . 'cdnotehistory.TransID,'.db_prefix() . 'cdnotehistory.ttype,'.db_prefix() . 'cdnotehistory.transdate AS CDDate,'.db_prefix() . 'cdnotehistory.cgst,'.db_prefix() . 'cdnotehistory.igst,'.db_prefix() . 'cdnotehistory.sgst,SUM('.db_prefix() . 'cdnotehistory.rate) AS TaxableAmt,SUM('.db_prefix() . 'cdnotehistory.amount) AS BillAmt ');
                    $this->db->from(db_prefix() . 'cdnotehistory');
                    $this->db->where_in(db_prefix() . 'cdnotehistory.TransID', $SaleIDSGST);
                    $this->db->where(db_prefix() . 'cdnotehistory.plantid', $selected_company);
                    $this->db->where(db_prefix() . 'cdnotehistory.fy', $year);
                    $this->db->where(db_prefix() . 'cdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
                    $this->db->group_by(db_prefix() . 'cdnotehistory.billno,'.db_prefix() . 'cdnotehistory.igst,'.db_prefix() . 'cdnotehistory.cgst,'.db_prefix() . 'cdnotehistory.sgst');
                    $this->db->order_by(db_prefix() . 'cdnotehistory.TransID', 'ASC');
                    $CDNR22 = $this->db->get()->result_array();
                }else{
                    $CDNR22 = array();
                }
                
            }else{
                $CDNR22 = array();
            }
        }else{
            $CDNR22 = array();
        } 
        // CDNUR
        
        $this->db->select(db_prefix() . 'history.TransID');
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.TType', 'R');
        $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->group_by(db_prefix() . 'history.TransID,'.db_prefix() . 'history.OrderID');
        $CDNURSaleID2 = $this->db->get()->result_array();
        if(!empty($CDNURSaleID2)){
            $CDNURSaleIDS2 = array();
            foreach ($CDNURSaleID2 as $key => $value) {
                array_push($CDNURSaleIDS2,$value['TransID']);
            } 
            if(!empty($CDNURSaleIDS2)){
                $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate AS SaleDate,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'salesmaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
                $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $CDNURSaleIDS2);
                $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT);
                $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
                $CDNURSaleIDNonGST2 = $this->db->get()->result_array();
                
                $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate AS SaleDate,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'salesmaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
                $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $CDNURSaleIDS2);
                $this->db->where(db_prefix() . 'salesmaster.BT', 'M');
                $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
                $CDNURSaleIDNonGST2_TRN = $this->db->get()->result_array();
                
                $SaleIDSNONGST2 = array();
                
                foreach ($CDNURSaleIDNonGST2_TRN as $key1 => $value1) {
                    array_push($SaleIDSNONGST2,$value1['SalesID']);
                }
                
                foreach ($CDNURSaleIDNonGST2 as $key => $value) {
                    array_push($SaleIDSNONGST2,$value['SalesID']);
                }
                if(!empty($SaleIDSNONGST2)){
                    $this->db->select(db_prefix() . 'history.TransID,'.db_prefix() . 'history.OrderID,'.db_prefix() . 'history.Transdate AS SaleRTNDate,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.sgst,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt,SUM('.db_prefix() . 'history.NetChallanAmt) AS BillAmt ');
                    $this->db->from(db_prefix() . 'history');
                    $this->db->where_in(db_prefix() . 'history.TransID', $SaleIDSNONGST2);
                    $this->db->where(db_prefix() . 'history.TType', 'R');
                    $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
                    $this->db->group_by(db_prefix() . 'history.TransID,'.db_prefix() . 'history.OrderID,'.db_prefix() . 'history.igst,'.db_prefix() . 'history.cgst,'.db_prefix() . 'history.sgst');
                    $this->db->order_by(db_prefix() . 'history.TransID', 'ASC');
                    $CDNUR1 = $this->db->get()->result_array();
                }else{
                    $CDNUR1 = array();
                }
            }else{
                $CDNUR1 = array();
            }
        }else{
            $CDNUR1 = array();
        } 
        
       
        // CDNUR2
            
           $this->db->select(db_prefix() . 'cdnotehistory.TransID');
            $this->db->from(db_prefix() . 'cdnotehistory');
            $this->db->where(db_prefix() . 'cdnotehistory.plantid', $selected_company);
            $this->db->where(db_prefix() . 'cdnotehistory.fy', $year);
            $this->db->where(db_prefix() . 'cdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
            $this->db->group_by(db_prefix() . 'cdnotehistory.billno');
            $CDNURSaleID = $this->db->get()->result_array();
        if(!empty($CDNURSaleID)){
            $CDNURSaleIDS = array();
            foreach ($CDNURSaleID as $key => $value) {
                array_push($CDNURSaleIDS,$value['TransID']);
            } 
            if(!empty($CDNURSaleIDS)){
                $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate AS SaleDate,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'salesmaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
                $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $CDNURSaleIDS);
                $this->db->where_in(db_prefix() . 'salesmaster.BT', $BT);
                $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
                $CDNURSaleIDNonGST = $this->db->get()->result_array();
                
                $this->db->select(db_prefix() . 'salesmaster.SalesID,'.db_prefix() . 'salesmaster.Transdate AS SaleDate,'.db_prefix() . 'salesmaster.gstno,'.db_prefix() . 'clients.state,'.db_prefix() . 'clients.company');
                $this->db->from(db_prefix() . 'salesmaster');
                $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
                $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
                $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $CDNURSaleIDS);
                $this->db->where(db_prefix() . 'salesmaster.BT', 'M');
                $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
                $CDNURSaleIDNonGST_TRN = $this->db->get()->result_array();
                
                $SaleIDSNONGST = array();
                
                foreach ($CDNURSaleIDNonGST_TRN as $key1 => $value1) {
                    array_push($SaleIDSNONGST,$value1['SalesID']);
                }
                foreach ($CDNURSaleIDNonGST as $key => $value) {
                    array_push($SaleIDSNONGST,$value['SalesID']);
                }
                if(!empty($SaleIDSNONGST)){
                    $this->db->select(db_prefix() . 'cdnotehistory.billno,'.db_prefix() . 'cdnotehistory.TransID,'.db_prefix() . 'cdnotehistory.ttype,'.db_prefix() . 'cdnotehistory.transdate AS CDDate,'.db_prefix() . 'cdnotehistory.cgst,'.db_prefix() . 'cdnotehistory.igst,'.db_prefix() . 'cdnotehistory.sgst,SUM('.db_prefix() . 'cdnotehistory.rate) AS TaxableAmt,SUM('.db_prefix() . 'cdnotehistory.amount) AS BillAmt ');
                    $this->db->from(db_prefix() . 'cdnotehistory');
                    $this->db->where_in(db_prefix() . 'cdnotehistory.TransID', $SaleIDSNONGST);
                    $this->db->where(db_prefix() . 'cdnotehistory.plantid', $selected_company);
                    $this->db->where(db_prefix() . 'cdnotehistory.fy', $year);
                    $this->db->where(db_prefix() . 'cdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
                    $this->db->group_by(db_prefix() . 'cdnotehistory.billno,'.db_prefix() . 'cdnotehistory.igst,'.db_prefix() . 'cdnotehistory.cgst,'.db_prefix() . 'cdnotehistory.sgst');
                    $this->db->order_by(db_prefix() . 'cdnotehistory.billno', 'ASC');
                    $CDNUR22 = $this->db->get()->result_array();
                }else{
                    $CDNUR22 = array();
                }
                
            }else{
                $CDNUR22 = array();
            }
        }else{
            $CDNUR22 = array();
        } 
        
        $B2BData = array();
        $B2BData['SaleData'] = $SaleData;
        $B2BData['HistoryData'] = $historyData;
        $B2BData['SaleData2'] = $SaleData2;
        $B2BData['HistoryData2'] = $historyData2;
        $B2BData['B2CS1'] = $B2CS1;
        $B2BData['B2CS2'] = $B2CS2;
        $B2BData['CDNR1'] = $CDNR1;
        $B2BData['CDNR11'] = $CDNRSaleIDGST2;
        $B2BData['CDNR111'] = $CDNRSaleIDGST22;
        $B2BData['CDNR2'] = $CDNR22;
        $B2BData['CDNR22'] = $CDNRSaleIDGST; 
        $B2BData['CDNUR1'] = $CDNUR1;
        $B2BData['CDNUR11'] = $CDNURSaleIDNonGST2;
        $B2BData['CDNUR11TRN'] = $CDNURSaleIDNonGST2_TRN;
        $B2BData['CDNUR2'] = $CDNUR22; 
        $B2BData['CDNUR22'] = $CDNURSaleIDNonGST;
        $B2BData['CDNUR22TRN'] = $CDNURSaleIDNonGST_TRN;
        return $B2BData;
    }
    
    
    public function get_data_for_B2B1($data){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblordermaster.GSTNO,tblhistory.TransID,tblhistory.TransDate2,tblhistory.igst,tblhistory.cgst,tblhistory.sgst, SUM(ChallanAmt) AS TaxableAmt , SUM(NetChallanAmt) AS BillAmt
                FROM `tblhistory` 
            INNER JOIN tblordermaster ON tblhistory.OrderID = tblordermaster.OrderID AND tblhistory.PlantID = tblordermaster.PlantID AND tblhistory.FY = tblordermaster.FY
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'"  AND tblhistory.TransID IS NOT NULL 
            AND tblordermaster.SalesID IS NOT NULL AND tblordermaster.GSTNO IS NOT NULL 
            AND tblordermaster.OrderType ="TaxItems" AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'"
            GROUP BY tblhistory.TransID,tblhistory.igst,tblhistory.cgst,tblhistory.sgst ORDER BY tblhistory.TransID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2B2($data){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblsalesmaster.SalesID,tblsalesmaster.BillAmt AS INVAMT,tcsAmt,tblclients.state,tblxx_statelist.state_name FROM `tblsalesmaster`
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        INNER JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY = "'.$year.'" AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2B_CD($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tbltaxes.taxrate,SUM(tblcdnotehistory.rate) AS taxableAmt,SUM(tblcdnotehistory.amount) AS InvAmt,tblcdnotehistory.billno,tblordermaster.GSTNO,tblcdnotehistory.transdate, tblclients.state,tblxx_statelist.state_name FROM `tblcdnotehistory` 
                JOIN tblitems ON tblitems.item_code = tblcdnotehistory.itemid AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblordermaster ON tblordermaster.SalesID = tblcdnotehistory.TransID AND tblordermaster.PlantID = tblcdnotehistory.plantid AND tblordermaster.FY = tblcdnotehistory.fy
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid AND tblsalesmaster.FY = tblcdnotehistory.fy
                JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
                JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblsalesmaster.BT IN ("T","M","C")
            AND tblcdnotehistory.TransID IS NOT NULL AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NOT NULL AND tblcdnotehistory.ttype = "D"
            GROUP BY tblcdnotehistory.billno ORDER BY tblcdnotehistory.billno ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2CL1($data){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblhistory.TransID,tblhistory.TransDate2,tblhistory.igst,tblhistory.cgst,tblhistory.sgst, SUM(ChallanAmt) AS TaxableAmt , SUM(NetChallanAmt) AS BillAmt
                FROM `tblhistory` 
            INNER JOIN tblordermaster ON tblhistory.OrderID = tblordermaster.OrderID AND tblhistory.PlantID = tblordermaster.PlantID AND tblhistory.FY = tblordermaster.FY
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'"  AND tblhistory.TransID IS NOT NULL 
            AND tblordermaster.SalesID IS NOT NULL AND tblordermaster.GSTNO IS NULL 
            AND tblordermaster.OrderType ="TaxItems" AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
            GROUP BY tblhistory.TransID,tblhistory.igst,tblhistory.cgst,tblhistory.sgst ORDER BY tblhistory.TransID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2CL2($data){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblsalesmaster.SalesID,tblsalesmaster.BillAmt AS INVAMT,Transdate AS BillDate,tcsAmt,tblclients.state,tblxx_statelist.state_name FROM `tblsalesmaster`
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID AND tblclients.state != "UP"
        INNER JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY = "'.$year.'" AND tblsalesmaster.BillAmt >250000 AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    public function get_data_for_B2CS($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tbltaxes.taxrate,SUM(ChallanAmt) AS taxableAmt, tblclients.state,tblxx_statelist.state_name FROM `tblhistory` 
                JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblordermaster ON tblordermaster.SalesID = tblhistory.TransID AND tblordermaster.PlantID = tblhistory.PlantID AND tblordermaster.FY = tblhistory.FY
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID AND tblsalesmaster.FY = tblhistory.FY
                JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
                JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'" AND tblhistory.TType = "O" AND tblhistory.TType2 = "Order" AND tblsalesmaster.BT IN("T","M")
            AND tblhistory.BillID IS NOT NULL AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NULL
            GROUP BY tblclients.state,tbltaxes.taxrate ORDER BY tblclients.state ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    public function get_data_for_B2CS1($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT SUM(ChallanAmt) AS TaxableAmt,cgst,sgst,igst,tblclients.state,tblxx_statelist.state_name FROM `tblhistory` 
INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID AND tblsalesmaster.FY = tblhistory.FY
INNER JOIN tblordermaster ON tblordermaster.SalesID = tblhistory.TransID AND tblordermaster.PlantID = tblhistory.PlantID AND tblordermaster.FY = tblhistory.FY
JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.SaleAmt < 250000  AND tblordermaster.OrderType = "TaxItems"
AND TType = "O" AND TType2 = "Order" AND tblclients.state !="UP"  AND tblordermaster.GSTNO IS NULL
GROUP BY cgst,sgst,igst,tblclients.state';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2CS11($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT SUM(ChallanAmt) AS TaxableAmt,cgst,sgst,igst,tblclients.state,tblxx_statelist.state_name FROM `tblhistory` 
INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID AND tblsalesmaster.FY = tblhistory.FY
INNER JOIN tblordermaster ON tblordermaster.SalesID = tblhistory.TransID AND tblordermaster.PlantID = tblhistory.PlantID AND tblordermaster.FY = tblhistory.FY
JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.OrderType = "TaxItems"
AND TType = "O" AND TType2 = "Order" AND tblclients.state ="UP"  AND tblordermaster.GSTNO IS NULL
GROUP BY cgst,sgst,igst,tblclients.state';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2CS_CD($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tbltaxes.taxrate,SUM(tblcdnotehistory.rate) AS taxableAmt, tblclients.state,tblxx_statelist.state_name FROM `tblcdnotehistory` 
                JOIN tblitems ON tblitems.item_code = tblcdnotehistory.itemid AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblordermaster ON tblordermaster.SalesID = tblcdnotehistory.TransID AND tblordermaster.PlantID = tblcdnotehistory.plantid AND tblordermaster.FY = tblcdnotehistory.fy
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid AND tblsalesmaster.FY = tblcdnotehistory.fy
                JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
                JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblsalesmaster.BT IN ("T","M","C")
            AND tblcdnotehistory.TransID IS NOT NULL AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NULL AND tblcdnotehistory.ttype = "D"
            GROUP BY tblclients.state,tbltaxes.taxrate ORDER BY tblclients.state ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_B2CS_SRT($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tbltaxes.taxrate,SUM(ChallanAmt) AS taxableAmt, tblclients.state,tblxx_statelist.state_name FROM `tblhistory` 
                JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblordermaster ON tblordermaster.SalesID = tblhistory.TransID AND tblordermaster.PlantID = tblhistory.PlantID AND tblordermaster.FY = tblhistory.FY
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID AND tblsalesmaster.FY = tblhistory.FY
                JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
                JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'" AND tblhistory.TType = "R" AND tblsalesmaster.BT IN ("T","M","C")
            AND tblhistory.BillID IS NOT NULL AND tblhistory.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"  AND tblordermaster.GSTNO IS NULL
            GROUP BY tblclients.state,tbltaxes.taxrate ORDER BY tblclients.state ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_CDNR($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblhistory.BillID,tblhistory.OrderID,tblhistory.cgst,tblhistory.sgst,tblhistory.igst,tbltaxes.taxrate,SUM(ChallanAmt) AS taxableAmt,SUM(NetChallanAmt) AS BillAmt, 
                tblsalesmaster.Transdate AS SALEDate,tblsalesreturn.Transdate AS SRTDate,tblordermaster.GSTNO,tblsalesreturn.PayType,tblclients.state,tblclients.company,tblxx_statelist.state_name FROM `tblhistory` 
                JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID 
                JOIN tblordermaster ON tblsalesmaster.SalesID = tblordermaster.SalesID AND tblsalesmaster.PlantID = tblordermaster.PlantID 
                JOIN tblsalesreturn ON tblsalesreturn.SalesRtnID = tblhistory.OrderID AND tblsalesreturn.PlantID = tblhistory.PlantID 
                JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
                LEFT JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'" AND tblhistory.TType = "R" 
            AND tblhistory.BillID IS NOT NULL AND tblsalesreturn.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NOT NULL
            GROUP BY tblhistory.OrderID,tbltaxes.taxrate ORDER BY tblhistory.TransID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_CDNR_CD($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblcdnotehistory.billno,tblcdnotehistory.TransID,tblcdnotehistory.cgst,tblcdnotehistory.sgst,tblcdnotehistory.igst,tbltaxes.taxrate,SUM(tblcdnotehistory.rate) AS taxableAmt,SUM(tblcdnotehistory.amount) AS BillAmt, tblcdnotehistory.ttype,
                tblsalesmaster.Transdate AS SALEDate,tblcdnotehistory.transdate AS CDDate,tblordermaster.GSTNO,tblsalesmaster.PayType,tblclients.state,tblclients.company,tblxx_statelist.state_name FROM `tblcdnotehistory` 
                JOIN tblitems ON tblitems.item_code = tblcdnotehistory.itemid AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
                JOIN tblordermaster ON tblsalesmaster.SalesID = tblordermaster.SalesID AND tblsalesmaster.PlantID = tblordermaster.PlantID 
                
                JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
                LEFT JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" 
            AND tblcdnotehistory.TransID IS NOT NULL AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NOT NULL
            GROUP BY tblcdnotehistory.billno,tbltaxes.taxrate ORDER BY tblcdnotehistory.TransID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_CDNUR($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblhistory.BillID,tblhistory.OrderID,tblhistory.cgst,tblhistory.sgst,tblhistory.igst,tbltaxes.taxrate,SUM(ChallanAmt) AS taxableAmt,SUM(NetChallanAmt) AS BillAmt,  
                tblsalesmaster.Transdate AS SALEDate,tblsalesreturn.Transdate AS SRTDate,tblordermaster.GSTNO,tblsalesreturn.PayType,tblclients.state,tblclients.company,tblxx_statelist.state_name FROM `tblhistory` 
                JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID 
                JOIN tblordermaster ON tblsalesmaster.SalesID = tblordermaster.SalesID AND tblsalesmaster.PlantID = tblordermaster.PlantID 
                JOIN tblsalesreturn ON tblsalesreturn.SalesRtnID = tblhistory.OrderID AND tblsalesreturn.PlantID = tblhistory.PlantID 
                JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
                LEFT JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'" AND tblhistory.TType = "R" 
            AND tblhistory.BillID IS NOT NULL AND tblsalesreturn.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NULL
            GROUP BY tblhistory.OrderID,tbltaxes.taxrate ORDER BY tblhistory.TransID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_CDNUR_CD($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblcdnotehistory.billno,tblcdnotehistory.TransID,tblcdnotehistory.cgst,tblcdnotehistory.sgst,tblcdnotehistory.igst,tbltaxes.taxrate,SUM(tblcdnotehistory.rate) AS taxableAmt,SUM(tblcdnotehistory.amount) AS BillAmt, tblcdnotehistory.ttype,
                tblsalesmaster.Transdate AS SALEDate,tblcdnotehistory.transdate AS CDDate,tblordermaster.GSTNO,tblsalesmaster.PayType,tblclients.state,tblclients.company,tblxx_statelist.state_name FROM `tblcdnotehistory` 
                JOIN tblitems ON tblitems.item_code = tblcdnotehistory.itemid AND tblitems.PlantID = '.$selected_company.'
                JOIN tbltaxes ON tbltaxes.id = tblitems.tax 
                JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
                JOIN tblordermaster ON tblsalesmaster.SalesID = tblordermaster.SalesID AND tblsalesmaster.PlantID = tblordermaster.PlantID 
                
                JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
                LEFT JOIN tblxx_statelist ON tblclients.state = tblxx_statelist.short_name
            WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" 
            AND tblcdnotehistory.TransID IS NOT NULL AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NULL
            GROUP BY tblcdnotehistory.billno,tbltaxes.taxrate ORDER BY tblcdnotehistory.TransID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_EXEMP($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $this->db->select(db_prefix() . 'history.TransID');
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.TType', 'R');
        $this->db->where(db_prefix() . 'history.cgst', '0.00');
        $this->db->where(db_prefix() . 'history.sgst', '0.00');
        $this->db->where(db_prefix() . 'history.igst', '0.00');
        $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
        $this->db->group_by(db_prefix() . 'history.TransID');
        $SRTTransID = $this->db->get()->result_array();
        if(!empty($SRTTransID)){
            $SRTTransID2 = array();
            foreach ($SRTTransID as $key => $value) {
                array_push($SRTTransID2,$value['TransID']);
            } 
        }else{
            $SRTTransID2 = array();
        }
        
        
        
         //Inter State
        
        // Register Party
        // Inter state with registered party i.e Other than UP
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAmt, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND cgstamt = "0.00" AND sgstamt = "0.00" AND igstamt ="0.00" AND tblsalesmaster.gstno IS NOT NULL AND tblclients.state != "UP" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" ';
        $resultInterStateGST = $this->db->query($sqlMFirst)->row();
        
        // Inter state with registered party i.e Other than UP Sale Return
        
        if(!empty($SRTTransID2)){
            $this->db->select(db_prefix() . 'salesmaster.SalesID');
            $this->db->from(db_prefix() . 'salesmaster');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
            $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
            $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $SRTTransID2);
            $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
            $this->db->where(db_prefix() . 'clients.state !=', 'UP');
            $SRTInterGST = $this->db->get()->result_array();
            if(!empty($SRTInterGST)){
                $SRTInterGSTIDs = array();
                foreach ($SRTInterGST as $key => $value) {
                    array_push($SRTInterGSTIDs,$value['SalesID']);
                }
            }
         }else{
             $SRTInterGSTIDs = array();
         }
        
        if(!empty($SRTInterGSTIDs)){
            $this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS Amt');
            $this->db->from(db_prefix() . 'history');
            $this->db->where_in(db_prefix() . 'history.TransID', $SRTInterGSTIDs);
            $this->db->where(db_prefix() . 'history.TType', 'R');
            $this->db->where(db_prefix() . 'history.cgst', '0.00');
            $this->db->where(db_prefix() . 'history.sgst', '0.00');
            $this->db->where(db_prefix() . 'history.igst', '0.00');
            $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
            $SRTInterGST = $this->db->get()->result_array();
            if(!empty($SRTInterGST)){
                $SumSRTInterGST = $SRTInterGST[0]['Amt'];
            }
        }else{
            $SumSRTInterGST = 0;
        }
        
        // Inter state with registered pary i.e Other than UP CD Note
        
        $sqlInterStateGSTCD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state != "UP" AND tblsalesmaster.gstno IS NOT NULL AND tblcdnotehistory.ttype = "C" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultInterStateGSTCD = $this->db->query($sqlInterStateGSTCD)->row();
        
        $sqlInterStateGSTDD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state != "UP" AND tblsalesmaster.gstno IS NOT NULL AND tblcdnotehistory.ttype = "D" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultInterStateGSTDD = $this->db->query($sqlInterStateGSTDD)->row();
        
        
        // UnRegisterd Party
        // Inter state with Unregistered party i.e Other than UP
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAmt, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND cgstamt = "0.00" AND sgstamt = "0.00" AND igstamt ="0.00" AND tblsalesmaster.gstno IS NULL AND tblclients.state != "UP" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" ';
        $resultInterState = $this->db->query($sqlMFirst)->row();
        
        // Inter state with Unregistered pary i.e Other than UP Sale Return
        
        if(!empty($SRTTransID2)){
            $this->db->select(db_prefix() . 'salesmaster.SalesID');
            $this->db->from(db_prefix() . 'salesmaster');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
            $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
            $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $SRTTransID2);
            $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
            $this->db->where(db_prefix() . 'clients.state !=', 'UP');
            $SRTInter = $this->db->get()->result_array();
            if(!empty($SRTInter)){
                $SRTInterIDs = array();
                foreach ($SRTInter as $key => $value) {
                    array_push($SRTInterIDs,$value['SalesID']);
                }
            }
         }else{
             $SRTInterIDs = array();
         }
        
        if(!empty($SRTInterIDs)){
            $this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS Amt');
            $this->db->from(db_prefix() . 'history');
            $this->db->where_in(db_prefix() . 'history.TransID', $SRTInterIDs);
            $this->db->where(db_prefix() . 'history.TType', 'R');
            $this->db->where(db_prefix() . 'history.cgst', '0.00');
            $this->db->where(db_prefix() . 'history.sgst', '0.00');
            $this->db->where(db_prefix() . 'history.igst', '0.00');
            $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
            $SRTIntert = $this->db->get()->result_array();
            if(!empty($SRTIntert)){
                $SumSRTInter = $SRTIntert[0]['Amt'];
            }
        }else{
            $SumSRTInter = 0;
        }
        
        // Inter state with Unregistered pary i.e Other than UP CD Note
        $sqlInterStateCD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state != "UP" AND tblsalesmaster.gstno IS NULL AND tblcdnotehistory.ttype = "C" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultInterStateCD = $this->db->query($sqlInterStateCD)->row();
        
        $sqlInterStateDD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state != "UP" AND tblsalesmaster.gstno IS NULL AND tblcdnotehistory.ttype = "D" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultInterStateDD = $this->db->query($sqlInterStateDD)->row();
        
        
        // Intra State
        // Registerd Party
        // Intra state with registered pary i.e Within UP
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAmt, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND cgstamt = "0.00" AND sgstamt = "0.00" AND igstamt ="0.00" AND tblsalesmaster.gstno IS NOT NULL AND tblclients.state = "UP" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" ';
        $resultIntraStateGST = $this->db->query($sqlMFirst)->row();
        
        // Intra state with registered pary i.e Within UP Sale Return -- 1.7 too long
        
        if(!empty($SRTTransID2)){
            $this->db->select(db_prefix() . 'salesmaster.SalesID');
            $this->db->from(db_prefix() . 'salesmaster');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
            $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
            $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $SRTTransID2);
            $this->db->where(db_prefix() . 'salesmaster.gstno IS NOT NULL');
            $this->db->where(db_prefix() . 'clients.state', 'UP');
            $SRTIntrtaGST = $this->db->get()->result_array();
            if(!empty($SRTIntrtaGST)){
                $SRTIntraGSTIDs = array();
                foreach ($SRTIntrtaGST as $key => $value) {
                    array_push($SRTIntraGSTIDs,$value['SalesID']);
                }
            }
         }else{
             $SRTIntraGSTIDs = array();
         }
        
        if(!empty($SRTIntraGSTIDs)){
            $this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS Amt');
            $this->db->from(db_prefix() . 'history');
            $this->db->where_in(db_prefix() . 'history.TransID', $SRTIntraGSTIDs);
            $this->db->where(db_prefix() . 'history.TType', 'R');
            $this->db->where(db_prefix() . 'history.cgst', '0.00');
            $this->db->where(db_prefix() . 'history.sgst', '0.00');
            $this->db->where(db_prefix() . 'history.igst', '0.00');
            $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
            $SRTIntraGST = $this->db->get()->result_array();
            if(!empty($SRTIntraGST)){
                $SumSRTIntraGST = $SRTIntraGST[0]['Amt'];
            }
        }else{
            $SumSRTIntraGST = 0;
        }
        
        // Intra state with registered pary i.e Within UP CD Note
        $sqlIntraStateGSTCD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state = "UP" AND tblsalesmaster.gstno IS NOT NULL AND tblcdnotehistory.ttype = "C" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultIntraStateGSTCD = $this->db->query($sqlIntraStateGSTCD)->row();
        
        $sqlIntraStateGSTDD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state = "UP" AND tblsalesmaster.gstno IS NOT NULL AND tblcdnotehistory.ttype = "D" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultIntraStateGSTDD = $this->db->query($sqlIntraStateGSTDD)->row();
        
        
        // UnRegisterd Party
        // Intra state with Unregistered pary i.e Within UP
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAmt, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND cgstamt = "0.00" AND sgstamt = "0.00" AND igstamt ="0.00" AND tblsalesmaster.gstno IS NULL AND tblclients.state = "UP" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" ';
        $resultIntraState = $this->db->query($sqlMFirst)->row();
        
        // Intra state with Unregistered pary i.e Within UP Sale Return
        if(!empty($SRTTransID2)){
            $this->db->select(db_prefix() . 'salesmaster.SalesID');
            $this->db->from(db_prefix() . 'salesmaster');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
            $this->db->where(db_prefix() . 'salesmaster.PlantID', $selected_company);
            $this->db->where_in(db_prefix() . 'salesmaster.SalesID', $SRTTransID2);
            $this->db->where(db_prefix() . 'salesmaster.gstno IS NULL');
            $this->db->where(db_prefix() . 'clients.state', 'UP');
            $SRTIntrta = $this->db->get()->result_array();
            if(!empty($SRTIntrta)){
                $SRTIntraIDs = array();
                foreach ($SRTIntrta as $key => $value) {
                    array_push($SRTIntraIDs,$value['SalesID']);
                }
            }
         }else{
             $SRTIntraIDs = array();
         }
        
        if(!empty($SRTIntraIDs)){
            $this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS Amt');
            $this->db->from(db_prefix() . 'history');
            $this->db->where_in(db_prefix() . 'history.TransID', $SRTIntraIDs);
            $this->db->where(db_prefix() . 'history.TType', 'R');
            $this->db->where(db_prefix() . 'history.cgst', '0.00');
            $this->db->where(db_prefix() . 'history.sgst', '0.00');
            $this->db->where(db_prefix() . 'history.igst', '0.00');
            $this->db->where(db_prefix() . 'history.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
            $SRTIntra = $this->db->get()->result_array();
            if(!empty($SRTIntra)){
                $SumSRTIntra = $SRTIntra[0]['Amt'];
            }
        }else{
            $SumSRTIntra = 0;
        }
        
        // Intra state with Unregistered pary i.e Within UP CD Note
        $sqlIntraStateCD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state = "UP" AND tblsalesmaster.gstno IS NULL AND tblcdnotehistory.ttype = "C" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultIntraStateCD = $this->db->query($sqlIntraStateCD)->row();
        
        $sqlIntraStateDD = 'SELECT SUM(amount) AS SumCD  FROM `tblcdnotehistory` 
        INNER JOIN tblclients ON tblclients.AccountID = tblcdnotehistory.AccountID AND tblclients.PlantID = tblcdnotehistory.plantid
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.cgst = "0.00" AND tblcdnotehistory.sgst = "0.00" AND tblcdnotehistory.igst = "0.00" AND
        tblclients.state = "UP" AND tblsalesmaster.gstno IS NULL AND tblcdnotehistory.ttype = "D" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultIntraStateDD = $this->db->query($sqlIntraStateDD)->row();
        
        
        
        $Inter_GSTR = $resultInterStateGST->TaxableAmt - ($SumSRTInterGST + $resultInterStateGSTCD->SumCD) + $resultInterStateGSTDD->SumCD;
        $Intra_GSTR = $resultIntraStateGST->TaxableAmt - ($SumSRTIntraGST + $resultIntraStateGSTCD->SumCD) + $resultIntraStateGSTDD->SumCD;
        $Inter_GSTUR = $resultInterState->TaxableAmt - ($SumSRTInter + $resultInterStateCD->SumCD) + $resultInterStateDD->SumCD;
        $Intra_GSTUR = $resultIntraState->TaxableAmt - ($SumSRTIntra + $resultIntraStateCD->SumCD) + $resultIntraStateDD->SumCD;
        $response = array(
            'InterGSTR' =>$Inter_GSTR,
            'IntraGSTR' =>$Intra_GSTR,
            'InterGSTUR' =>$Inter_GSTUR,
            'IntraGSTUR' =>$Intra_GSTUR,
            );
        
        return $response;
    }
    
    public function get_data_for_HSN($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql1 = 'SELECT tblsalesmaster.SalesID
        FROM `tblsalesmaster` 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY = "'.$year.'" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
        ';
        $SaleIDS = $this->db->query($sql1)->result_array();
        
        $TransIDs = array();
        foreach($SaleIDS as $value){
            array_push($TransIDs,$value["SalesID"]);
        }
        $this->db->select('tblitems.hsn_code,
        tblhistory.cgst,sum(tblhistory.cgstamt) AS CGSTSUM, tblhistory.sgst, SUM(tblhistory.sgstamt) SGSTSUM,tblhistory.igst, 
        SUM(tblhistory.igstamt) AS IGSTSUM, SUM(tblhistory.ChallanAmt) AS TaxableAmt , SUM(tblhistory.NetChallanAmt) AS BillAmt,SUM(tblhistory.BilledQty) AS BilledQtySum');
            $this->db->from(db_prefix() . 'history');
            $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND  '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
            $this->db->where_in(db_prefix() . 'history.TransID', $TransIDs);
            $this->db->where(db_prefix() . 'history.TType', 'O');
            $this->db->where(db_prefix() . 'history.TType2', 'Order');
            $this->db->group_by('tblitems.hsn_code,tblhistory.cgst,tblhistory.sgst,tblhistory.igst');
            $result = $this->db->get()->result_array();
       /* $sql = 'SELECT tblitems.hsn_code,
        tblhistory.cgst,sum(tblhistory.cgstamt) AS CGSTSUM, tblhistory.sgst, SUM(tblhistory.sgstamt) SGSTSUM,tblhistory.igst, 
        SUM(tblhistory.igstamt) AS IGSTSUM, SUM(tblhistory.ChallanAmt) AS TaxableAmt , SUM(tblhistory.NetChallanAmt) AS BillAmt,SUM(tblhistory.BilledQty) AS BilledQtySum
        FROM `tblhistory` 
        INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID
INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID
WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY LIKE "'.$year.'" AND tblhistory.TType = "O" AND tblhistory.TType2 = "Order"
AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
GROUP BY tblitems.hsn_code,tblhistory.cgst,tblhistory.sgst,tblhistory.igst';*/
        //$result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_HSNCode($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql1 = 'SELECT tblsalesmaster.SalesID
        FROM `tblsalesmaster` 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY = "'.$year.'" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
        ';
        $SaleIDS = $this->db->query($sql1)->result_array();
        
        $TransIDs = array();
        foreach($SaleIDS as $value){
            array_push($TransIDs,$value["SalesID"]);
        }
        
        $this->db->distinct();
        $this->db->select('tblitems.hsn_code,tblhistory.sgst,tblhistory.cgst,tblhistory.igst');
            $this->db->from(db_prefix() . 'history');
            $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND  '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
            $this->db->where_in(db_prefix() . 'history.TransID', $TransIDs);
            $this->db->where(db_prefix() . 'history.TType', 'O');
            $this->db->where(db_prefix() . 'history.TType2', 'Order');
            $this->db->group_by('tblitems.hsn_code,tblhistory.cgst,tblhistory.sgst,tblhistory.igst');
            $result = $this->db->get()->result_array();
        
        return $result;
    }
    
    public function getHsnMaster($data)
    {
        $sql1 = 'SELECT tblhsn.* FROM `tblhsn`';
        $HsnMaster = $this->db->query($sql1)->result_array();
        return $HsnMaster;
    }
    public function GetSRT_HSN($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql1 = 'SELECT tblsalesreturn.SalesRtnID
        FROM `tblsalesreturn` 
        WHERE tblsalesreturn.PlantID = '.$selected_company.' AND tblsalesreturn.FY = "'.$year.'" AND tblsalesreturn.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
        ';
        $SaleIDS = $this->db->query($sql1)->result_array();
        
        $TransIDs = array();
        foreach($SaleIDS as $value){
            array_push($TransIDs,$value["SalesRtnID"]);
        }
        if (!$TransIDs) {
            
        } else {
            $this->db->select('tblitems.hsn_code,tblhistory.cgst, tblhistory.sgst, tblhistory.igst');
            $this->db->from(db_prefix() . 'history');
            $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND  '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
            $this->db->where_in(db_prefix() . 'history.OrderID', $TransIDs);
            $this->db->where(db_prefix() . 'history.TType', 'R');
            $this->db->group_by('tblitems.hsn_code,tblhistory.cgst,tblhistory.sgst,tblhistory.igst');
            $result = $this->db->get()->result_array();
        }
        
       
        return $result;
    }
    
    public function GetCD_HSN($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
         $sql = 'SELECT DISTINCT tblcdnotehistory.hsncode,tblcdnotehistory.cgst,tblcdnotehistory.sgst,tblcdnotehistory.igst 
        FROM `tblcdnotehistory`
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy LIKE "'.$year.'" 
AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
GROUP BY tblcdnotehistory.hsncode,tblcdnotehistory.cgst,tblcdnotehistory.sgst,tblcdnotehistory.igst';
        $result = $this->db->query($sql)->result_array();
        return $result;
        
    }
    
    public function get_data_for_HSNSRT($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblitems.hsn_code,
        tblhistory.cgst,sum(tblhistory.cgstamt) AS CGSTSUM, tblhistory.sgst, SUM(tblhistory.sgstamt) SGSTSUM,tblhistory.igst, 
        SUM(tblhistory.igstamt) AS IGSTSUM, SUM(tblhistory.ChallanAmt) AS TaxableAmt , SUM(tblhistory.NetChallanAmt) AS BillAmt,SUM(tblhistory.BilledQty) AS BilledQtySum
        FROM `tblhistory` 
INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID
WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY LIKE "'.$year.'" AND tblhistory.TType = "R"
AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" 
GROUP BY tblitems.hsn_code,tblhistory.cgst,tblhistory.sgst,tblhistory.igst';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_HSNCD($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblcdnotehistory.hsncode,tblcdnotehistory.cgst,SUM(tblcdnotehistory.cgstamt) AS CGSTSUM, tblcdnotehistory.sgst, SUM(tblcdnotehistory.sgstamt) SGSTSUM,tblcdnotehistory.igst,  tblpurchasemaster.PurchID, tblsalesmaster.SalesID,
        SUM(tblcdnotehistory.igstamt) AS IGSTSUM, SUM(tblcdnotehistory.rate) AS TaxableAmt , SUM(tblcdnotehistory.amount) AS BillAmt,SUM(tblcdnotehistory.qty) AS BilledQtySum  
        FROM `tblcdnotehistory` 
        LEFT JOIN tblpurchasemaster ON tblpurchasemaster.PurchID =  tblcdnotehistory.TransID
        LEFT JOIN 	tblsalesmaster ON 	tblsalesmaster.SalesID =  tblcdnotehistory.TransID
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblcdnotehistory.ttype = "C"
        GROUP BY tblcdnotehistory.hsncode,tblcdnotehistory.cgst,tblcdnotehistory.sgst,tblcdnotehistory.igst';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_HSNDD($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sql = 'SELECT tblcdnotehistory.hsncode,tblcdnotehistory.cgst,SUM(tblcdnotehistory.cgstamt) AS CGSTSUM, tblcdnotehistory.sgst, SUM(tblcdnotehistory.sgstamt) SGSTSUM,tblcdnotehistory.igst,  tblpurchasemaster.PurchID, tblsalesmaster.SalesID,
        SUM(tblcdnotehistory.igstamt) AS IGSTSUM, SUM(tblcdnotehistory.rate) AS TaxableAmt , SUM(tblcdnotehistory.amount) AS BillAmt,SUM(tblcdnotehistory.qty) AS BilledQtySum  
        FROM `tblcdnotehistory`
        LEFT JOIN tblpurchasemaster ON tblpurchasemaster.PurchID =  tblcdnotehistory.TransID
        LEFT JOIN 	tblsalesmaster ON 	tblsalesmaster.SalesID =  tblcdnotehistory.TransID
        WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblcdnotehistory.ttype = "D"
        GROUP BY tblcdnotehistory.hsncode,tblcdnotehistory.cgst,tblcdnotehistory.sgst,tblcdnotehistory.igst';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_data_for_DOCS($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        // First
        $sqlTFirst = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "T" ORDER BY SalesID ASC limit 1';
        $resultTFirst = $this->db->query($sqlTFirst)->row();
        
        //Last
        $sqlTLast = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "T" ORDER BY SalesID DESC limit 1';
        $resultTLAst = $this->db->query($sqlTLast)->row();
        
        // TOtal
        $sqlTTotal = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "T" ';
        $resultTotal = $this->db->query($sqlTTotal)->result_array();
        
        // Total Cancel
        $sqlTC = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "T" AND BillAmt = "0.00"';
        $resultC = $this->db->query($sqlTC)->result_array();
        
        // First
        $sqlBFirst = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "B" ORDER BY SalesID ASC limit 1';
        $resultBFirst = $this->db->query($sqlBFirst)->row();
        
        //Last
        $sqlBLast = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "B" ORDER BY SalesID DESC limit 1';
        $resultBLAst = $this->db->query($sqlBLast)->row();
        
        // TOtal
        $sqlBTotal = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "B" ';
        $resultBTotal = $this->db->query($sqlBTotal)->result_array();
        
        // Total Cancel
        $sqlBC = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "B" AND BillAmt = "0.00"';
        $resulBtC = $this->db->query($sqlBC)->result_array();
        
        // First
        $sqlMFirst = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "M" ORDER BY SalesID ASC limit 1';
        $resultMFirst = $this->db->query($sqlMFirst)->row();
        
        //Last
        $sqlMLast = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "M" ORDER BY SalesID DESC limit 1';
        $resultMLAst = $this->db->query($sqlMLast)->row();
        
        // TOtal
        $sqlMTotal = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "M" ';
        $resultMTotal = $this->db->query($sqlMTotal)->result_array();
        
        // Total Cancel
        $sqlMC = 'SELECT * FROM tblsalesmaster 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" 
        AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND BT = "M" AND BillAmt = "0.00"';
        $resulMtC = $this->db->query($sqlMC)->result_array();
        
        
        $response = array(
            'TStart'=>$resultTFirst->SalesID,
            'TEnd'=>$resultTLAst->SalesID,
            'TTotal'=>count($resultTotal),
            'TTotalC'=>count($resultC),
            'BStart'=>$resultBFirst->SalesID,
            'BEnd'=>$resultBLAst->SalesID,
            'BTotal'=>count($resultBTotal),
            'BTotalC'=>count($resulBtC),
            'MStart'=>$resultMFirst->SalesID,
            'MEnd'=>$resultMLAst->SalesID,
            'MTotal'=>count($resultMTotal),
            'MTotalC'=>count($resulMtC),
            );
        return $response;
    }
    
    public function get_data_for_gstr_3_1_a($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        
        
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAMT, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblordermaster ON tblordermaster.SalesID = tblsalesmaster.SalesID AND tblordermaster.PlantID = tblsalesmaster.PlantID AND tblordermaster.FY = tblsalesmaster.FY
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND tblsalesmaster.BT IN("T","M","C") AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.OrderType ="TaxItems"';
        $resultMFirst = $this->db->query($sqlMFirst)->row();
        
        
        $sqlFrCNote = 'SELECT SUM(rate) AS TaxableAmt, SUM(tblcdnotehistory.cgstamt) CAMT, SUM(tblcdnotehistory.sgstamt) AS SAmt, SUM(tblcdnotehistory.igstamt) AS IAmt FROM `tblcdnotehistory` 
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid 
INNER JOIN tblordermaster ON tblordermaster.SalesID = tblsalesmaster.SalesID AND tblordermaster.PlantID = tblsalesmaster.PlantID
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.BT IN("T","M","C") AND tblcdnotehistory.ttype = "C"  AND tblordermaster.OrderType ="TaxItems"';
        $resultCNote = $this->db->query($sqlFrCNote)->row();
        
        $sqlFrDNote = 'SELECT SUM(rate) AS TaxableAmt, SUM(tblcdnotehistory.cgstamt) CAMT, SUM(tblcdnotehistory.sgstamt) AS SAmt, SUM(tblcdnotehistory.igstamt) AS IAmt FROM `tblcdnotehistory` 
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid
INNER JOIN tblordermaster ON tblordermaster.SalesID = tblsalesmaster.SalesID AND tblordermaster.PlantID = tblsalesmaster.PlantID
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.BT IN("T","M","C") AND tblcdnotehistory.ttype = "D" AND tblordermaster.OrderType ="TaxItems"';
        $resultDNote = $this->db->query($sqlFrDNote)->row();
        
        
        $sqlSRT = 'SELECT SUM(tblhistory.ChallanAmt) AS TaxableAmt, SUM(tblhistory.sgstamt) AS SAmt, 
        SUM(tblhistory.cgstamt) AS CAMT, SUM(tblhistory.igstamt) AS IAmt FROM `tblhistory` 
        INNER JOIN tblordermaster ON tblordermaster.SalesID = tblhistory.TransID AND tblordermaster.PlantID = tblhistory.PlantID 
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$year.'"  AND tblhistory.TType ="R" AND tblhistory.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"  AND tblordermaster.OrderType ="TaxItems"';
        $resultSRT = $this->db->query($sqlSRT)->row();
        
        $taxableAmt = $resultMFirst->TaxableAmt - ($resultCNote->TaxableAmt + $resultSRT->TaxableAmt) + $resultDNote->TaxableAmt;
        $IGSTAmt = $resultMFirst->IAmt - $resultCNote->IAmt + $resultDNote->IAmt - $resultSRT->IAmt;
        $CSGSTAmt = $resultMFirst->CAMT - $resultCNote->CAMT + $resultDNote->CAMT - $resultSRT->CAMT;
        $response = array(
            'TaxableAmt'=>$taxableAmt,
            'IAmt'=>$IGSTAmt,
            'CAmt'=>$CSGSTAmt,
            'SAmt'=>$CSGSTAmt
        );
        return $response;
    }
    
    public function get_data_for_gstr_3_1_c($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAmt, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND cgstamt = "0.00" AND sgstamt = "0.00" AND igstamt ="0.00" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" ';
        $resultMFirst = $this->db->query($sqlMFirst)->row();
        
        $sqlFrCNote = 'SELECT SUM(amount) AS TaxableAmt, SUM(tblcdnotehistory.cgstamt) CAMT, SUM(tblcdnotehistory.sgstamt) AS SAmt, SUM(tblcdnotehistory.igstamt) AS IAmt FROM `tblcdnotehistory` 
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND cgst = "0.00" AND sgst = "0.00" AND igst="0.00" AND tblcdnotehistory.ttype = "C"';
        $resultCNote = $this->db->query($sqlFrCNote)->row();
        
        $sqlFrDNote = 'SELECT SUM(amount) AS TaxableAmt, SUM(tblcdnotehistory.cgstamt) CAMT, SUM(tblcdnotehistory.sgstamt) AS SAmt, SUM(tblcdnotehistory.igstamt) AS IAmt FROM `tblcdnotehistory` 
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND cgst = "0.00" AND sgst = "0.00" AND igst="0.00" AND tblcdnotehistory.ttype = "D"';
        $resultDNote = $this->db->query($sqlFrDNote)->row();
        
        $sqlSRT = 'SELECT SUM(tblhistory.NetChallanAmt) AS TaxableAmt, SUM(tblhistory.sgstamt) AS SAmt, 
        SUM(tblhistory.cgstamt) AS CAmt, SUM(tblhistory.igstamt) AS IAmt FROM `tblhistory` 
        
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY LIKE "'.$year.'"  AND TType ="R" AND cgst = "0.00" AND sgst = "0.00" AND igst="0.00" AND tblhistory.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultSRT = $this->db->query($sqlSRT)->row();
       
        $taxableAmt = 0.00;
        $taxableAmt = $resultMFirst->TaxableAmt - ($resultCNote->TaxableAmt + $resultSRT->TaxableAmt) + $resultDNote->TaxableAmt ;
        $IGSTAmt = $resultMFirst->IAmt - $resultCNote->IAmt + $resultDNote->IAmt - $resultSRT->IAmt;
        $CSGSTAmt = $resultMFirst->CAmt - $resultCNote->CAMT + $resultDNote->CAMT - $resultSRT->CAMT;
        $response = array(
            'TaxableAmt'=>$taxableAmt,
            'IAmt'=>$IGSTAmt,
            'CAmt'=>$CSGSTAmt,
            'SAmt'=>$CSGSTAmt
        );
        return $response;
        //return $resultMFirst;
    }
    
    public function get_data_for_gstr_3_1_d($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sqlMFirst = 'SELECT Purchamt,igstamt,cgstamt,sgstamt 
        FROM `tblpurchasemaster` 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$year.'" AND BT = "N" AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultMFirst = $this->db->query($sqlMFirst)->result_array();
        
        $TaxableAmt = 0.00;
        $IAmt = 0.00;
        $SAmt = 0.00;
        $CAmt = 0.00;
        foreach ($resultMFirst as $key => $value) {
            if($value['igstamt'] == "0.00" && $value['sgstamt'] == "0.00" && $value['cgstamt'] == "0.00"){
                
            }else{
                $TaxableAmt += $value['Purchamt'];
                $IAmt += $value['igstamt'];
                $SAmt += $value['sgstamt'];
                $CAmt += $value['cgstamt'];
            }
        }
        $response = array(
            'TaxableAmt'=>$TaxableAmt,
            'IAmt'=>$IAmt,
            'CAmt'=>$CAmt,
            'SAmt'=>$SAmt,
        );
        return $response;
    }
    
    public function get_data_for_gstr_3_2_a($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAMT, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.BT IN("T","M","C") AND tblsalesmaster.gstno IS NULL AND tblclients.state != "UP" ';
        $resultMFirst = $this->db->query($sqlMFirst)->row();
        
        $sqlFrCNote = 'SELECT SUM(rate) AS TaxableAmt, SUM(tblcdnotehistory.cgstamt) CAMT, SUM(tblcdnotehistory.sgstamt) AS SAmt, SUM(tblcdnotehistory.igstamt) AS IAmt FROM `tblcdnotehistory` 
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid AND tblsalesmaster.FY = tblcdnotehistory.fy
INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.BT IN("T","M","C") AND tblcdnotehistory.ttype = "C" AND tblsalesmaster.gstno IS NULL AND tblclients.state !="UP"';
        $resultCNote = $this->db->query($sqlFrCNote)->row();
       
        $sqlFrDNote = 'SELECT SUM(rate) AS TaxableAmt, SUM(tblcdnotehistory.cgstamt) CAMT, SUM(tblcdnotehistory.sgstamt) AS SAmt, SUM(tblcdnotehistory.igstamt) AS IAmt FROM `tblcdnotehistory` 
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblcdnotehistory.TransID AND tblsalesmaster.PlantID = tblcdnotehistory.plantid AND tblsalesmaster.FY = tblcdnotehistory.fy
INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
WHERE tblcdnotehistory.plantid = '.$selected_company.' AND tblcdnotehistory.fy = "'.$year.'" AND tblcdnotehistory.transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.BT IN("T","M","C") AND tblcdnotehistory.ttype = "D" AND tblsalesmaster.gstno IS NULL AND tblclients.state !="UP"';
        $resultDNote = $this->db->query($sqlFrDNote)->row();
        
        $sqlSRT = 'SELECT SUM(tblhistory.ChallanAmt) TaxableAmt, SUM(tblhistory.cgstamt) CAMT, SUM(tblhistory.sgstamt) AS SAmt, SUM(tblhistory.igstamt) AS IAmt  FROM `tblhistory` 
INNER JOIN tblsalesmaster ON tblsalesmaster.SalesID = tblhistory.TransID AND tblsalesmaster.PlantID = tblhistory.PlantID AND tblsalesmaster.FY = tblhistory.FY
INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY LIKE "'.$year.'" AND tblhistory.TType =  "R" AND tblhistory.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblsalesmaster.gstno IS NULL AND tblclients.state !="UP"';
        $resultSRT = $this->db->query($sqlSRT)->row();
        
        
        $taxableAmt = $resultMFirst->TaxableAmt - ($resultCNote->TaxableAmt + $resultSRT->TaxableAmt) + $resultDNote->TaxableAmt;
        $IGSTAmt = $resultMFirst->IAmt - $resultCNote->IAmt + $resultDNote->IAmt - $resultSRT->IAmt;
        $CSGSTAmt = $resultMFirst->CAMT - $resultCNote->CAMT + $resultDNote->CAMT - $resultSRT->CAMT;
        $response = array(
            'TaxableAmt'=>$taxableAmt,
            'IAmt'=>$IGSTAmt,
            'CAmt'=>$CSGSTAmt,
            'SAmt'=>$CSGSTAmt
        );
        return $response;
    }
    
    public function get_data_for_gstr_3_2_b($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sqlMFirst = 'SELECT SUM(tblsalesmaster.SaleAmt) AS TaxableAmt, SUM(tblsalesmaster.sgstamt) AS SAmt, 
        SUM(tblsalesmaster.cgstamt) AS CAmt, SUM(tblsalesmaster.igstamt) AS IAmt FROM `tblsalesmaster` 
        INNER JOIN tblordermaster ON tblordermaster.SalesID = tblsalesmaster.SalesID AND tblordermaster.PlantID = tblsalesmaster.PlantID AND tblordermaster.FY = tblsalesmaster.FY
        INNER JOIN tblclients ON tblclients.AccountID = tblsalesmaster.AccountID AND tblclients.PlantID = tblsalesmaster.PlantID
        WHERE tblsalesmaster.PlantID = '.$selected_company.' AND tblsalesmaster.FY LIKE "'.$year.'" AND tblsalesmaster.Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblordermaster.GSTNO IS NOT NULL AND tblclients.state != "UP" AND tblclients.gsttype ="3"';
        $resultMFirst = $this->db->query($sqlMFirst)->row();
        return $resultMFirst;
    }
    
    public function get_data_for_gstr_4_A_5($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sqlMFirst = 'SELECT Purchamt,igstamt,cgstamt,sgstamt 
        FROM `tblpurchasemaster` 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$year.'" AND  Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        $resultMFirst = $this->db->query($sqlMFirst)->result_array();
        
        $TaxableAmt = 0.00;
        $IAmt = 0.00;
        $SAmt = 0.00;
        $CAmt = 0.00;
        foreach ($resultMFirst as $key => $value) {
            /*if($value['igstamt'] == "0.00" && $value['sgstamt'] == "0.00" && $value['cgstamt'] == "0.00"){
                
            }else{*/
                $TaxableAmt += $value['Purchamt'];
                $IAmt += $value['igstamt'];
                $SAmt += $value['sgstamt'];
                $CAmt += $value['cgstamt'];
            //}
        }
        $response = array(
            'TaxableAmt'=>$TaxableAmt,
            'IAmt'=>$IAmt,
            'CAmt'=>$CAmt,
            'SAmt'=>$SAmt,
        );
        return $response;
    }
    
    public function get_data_for_gstr_5_1($data){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        
        $sqlIntraState = 'SELECT Purchamt,igstamt,cgstamt,sgstamt 
        FROM `tblpurchasemaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblpurchasemaster.AccountID AND tblclients.PlantID = tblpurchasemaster.PlantID
        WHERE tblpurchasemaster.PlantID = '.$selected_company.' AND tblpurchasemaster.FY = "'.$year.'" AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblclients.state = "UP"';
        $resultIntraState = $this->db->query($sqlIntraState)->result_array();
        
        $sqlInterState = 'SELECT Purchamt,igstamt,cgstamt,sgstamt 
        FROM `tblpurchasemaster` 
        INNER JOIN tblclients ON tblclients.AccountID = tblpurchasemaster.AccountID AND tblclients.PlantID = tblpurchasemaster.PlantID
        WHERE tblpurchasemaster.PlantID = '.$selected_company.' AND tblpurchasemaster.FY = "'.$year.'" AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND tblclients.state != "UP"';
        $resultInterState = $this->db->query($sqlInterState)->result_array();
        
        $TaxableAmt = 0.00;
       
        foreach ($resultIntraState as $key => $value) {
            if($value['igstamt'] == "0.00" && $value['sgstamt'] == "0.00" && $value['cgstamt'] == "0.00"){
                $TaxableAmt += $value['Purchamt'];
            }
        }
        
        $TaxableAmt1 = 0.00;
        
        foreach ($resultInterState as $key1 => $value1) {
            if($value1['igstamt'] == "0.00" && $value1['sgstamt'] == "0.00" && $value1['cgstamt'] == "0.00"){
                $TaxableAmt1 += $value1['Purchamt'];
            }
        }
        
        $response = array(
            'IntraTaxableAmt'=>$TaxableAmt,
            'IterStateTaxableAmt'=>$TaxableAmt1,
        );
        return $response;
    }
    
    
}?>