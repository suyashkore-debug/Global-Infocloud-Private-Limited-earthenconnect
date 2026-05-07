<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Misc_reports_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* Get main item group */
    public function get_main_item_group($id = '')
    {
       
        $this->db->select('*');
        $this->db->from(db_prefix() . 'items_main_groups');
        return $this->db->get()->result_array();
    }
    
    public function GetGodownData()
    {
        $PlantID = $this->session->userdata('root_company');
        $this->db->where('PlantID', $PlantID);
        $this->db->order_by(db_prefix() . 'godownmaster.Type,'.db_prefix() . 'godownmaster.AccountID', 'ASC');
       return $this->db->get(db_prefix().'godownmaster')->result_array();
    }
    /* Start Code for  Stock position reports */
    
    //-----------------------------------------------------
    public function get_item_group_name($item_group){
          
          $item_group_array = explode(",",$item_group);
            $this->db->select('name');
            $this->db->where_in('id', $item_group_array);
            $item_group_names = $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
            $item_group_name = array();
            foreach ($item_group_names as $key => $value) {
        # code...
            array_push($item_group_name, $value["name"]);
          }
          $item_group_name_s = implode(", ", $item_group_name);
           return $item_group_name_s;
    }
    /* Get item group */
    public function get_item_group($id = '')
    {
       
        $this->db->select('*');
        if($id !== '0'){
            $this->db->where('main_group_id',$id);
        }
        $this->db->order_by('name','ASC');
        $this->db->from(db_prefix() . 'items_sub_groups');
        return $this->db->get()->result_array();
    }
    
    public function get_sale_item_group2($data)
     {  
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $main_item_group_id = $data["main_item_group_id"];
         
         
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '(Transdate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")';
        
        $sql1 .= ' AND PlantID = "'.$selected_company.'" AND FY = "'.$fy.'"';
        //$sql1 .= ' AND PlantID = "'.$selected_company.'" AND FY = "'.$fy.'"';
        
        $sql ='SELECT '.db_prefix().'history.* FROM '.db_prefix().'history WHERE '.$sql1;
        
        $result = $this->db->query($sql)->result_array();
        if(empty($result)){
            return $result;
        }
        
        $order_ids = array();
        $item_ids = array();
        foreach ($result as $key => $value) {
               # code...
               array_push($item_ids, $value["ItemID"]);
            }
        
        if(empty($item_ids)){
            
        }else{
        
        
        $item_ids_uniqu = array_unique($item_ids);
        
        $this->db->select('*');
      $this->db->from(db_prefix() . 'items');
      $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
      //$this->db->where(db_prefix() . 'items.isactive', "Y");
      $this->db->where_in('item_code',$item_ids_uniqu);
      $result3 = $this->db->get()->result_array();
        
        $item_group_ids = array();
        foreach ($result3 as $key3 => $value3) {
               # code...
               array_push($item_group_ids, $value3["subgroup_id"]);
            }
        $item_group_ids_uniqu = array_unique($item_group_ids);
        
        $this->db->select('*');
      $this->db->from(db_prefix() . 'items_sub_groups');
      $this->db->where(db_prefix() . 'items_sub_groups.main_group_id', $main_item_group_id);
      
      $this->db->where_in('id',$item_group_ids_uniqu);
      $result4 = $this->db->get()->result_array();
        
        return $result4;
        
        }
     }
    
    /* Get Main item group */
    public function get_mainitem_group($id = '')
    {
       
        $this->db->select('*');
        $this->db->where('id',$id);
        $this->db->from(db_prefix() . 'items_main_groups');
        return $this->db->get()->row();
    }
    public function get_item_open_qty($filterdata,$item_group)
     {
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]); 
        $GodownID = $filterdata["GodownID"]; 
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $from_date_value = '20'.$fy.'-04-01';
        
        if($from_date == $from_date_value){
            $day_before = $from_date_value;
        }else{
            $day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) );
        }
        $first_date = $from_date_value;
        
        
         if($GodownID !==''){
        $sql = 'SELECT tblhistory.TType,tblhistory.TType2,tblhistory.ItemID,tblstockmaster.OQty,
         SUM(tblhistory.BilledQty)as billsum FROM `tblhistory` 
        INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
        LEFT JOIN tblstockmaster ON tblstockmaster.ItemID=tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY AND tblstockmaster.cnfid = "1"
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$first_date.' 00:00:00" AND "'.$day_before.' 23:59:59" AND tblitems.subgroup_id IN('.$item_group.') AND tblhistory.BillID IS NOT NULL ';
        if($GodownID !==''){
            $sql .= ' AND tblhistory.GodownID = "'.$GodownID.'" AND tblstockmaster.GodownID = "'.$GodownID.'"';
        }
        
        }else{
            $sql = 'SELECT tblhistory.TType,tblhistory.TType2,tblhistory.ItemID,
            (SELECT SUM(tblstockmaster.OQty) FROM tblstockmaster WHERE tblstockmaster.ItemID=tblhistory.ItemID AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" GROUP BY tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY) AS OQty,
         SUM(tblhistory.BilledQty)as billsum FROM `tblhistory` 
        INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$first_date.' 00:00:00" AND "'.$day_before.' 23:59:59" AND tblitems.subgroup_id IN('.$item_group.') AND tblhistory.BillID IS NOT NULL';
           
        }
        $sql .= ' GROUP BY tblhistory.ItemID,tblhistory.TType,tblhistory.TType2 ';
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
     
    public function GetItemListCommulative($filterdata,$item_group)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $sql = 'SELECT tblitems.PlantID,tblitems.item_code,tblitems.description,tblitems.case_qty,tblitems.unit
        FROM `tblitems` 
        WHERE tblitems.PlantID = '.$selected_company.' AND tblitems.subgroup_id IN('.$item_group.')';
        $sql .= ' ORDER BY tblitems.subgroup_id ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
    
    public function getCommulativeStockData($filterdata,$item_group)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $to_date = to_sql_date($filterdata["to_date"]);
        $from_date = '20'.$fy.'-04-01';
        
        $sql = 'SELECT tblhistory.TType,tblhistory.TType2,tblhistory.ItemID,tblhistory.GodownID,tblstockmaster.OQty,
         SUM(tblhistory.BilledQty)as billsum FROM `tblhistory` 
        INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
        INNER JOIN tblstockmaster ON tblstockmaster.ItemID=tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY AND tblstockmaster.GodownID = tblhistory.GodownID
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND tblitems.subgroup_id IN('.$item_group.') AND tblhistory.BillID IS NOT NULL ';
        
        $sql .= ' GROUP BY tblhistory.GodownID,tblhistory.ItemID,tblhistory.TType,tblhistory.TType2 ';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function GetItemList($filterdata,$item_group)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $GodownID = $filterdata["GodownID"]; 
        if($selected_company == "1"){
            $CustType = '1';
        }else if($selected_company == "2"){
            $CustType = '13';
        }else if($selected_company == "3"){
            $CustType = '21';
        }
        if($GodownID !==''){
        $sql = 'SELECT tblitems.PlantID,tblitems.item_code,tblitems.description,tblitems.case_qty,tblitems.unit,tblstockmaster.ItemID,tblstockmaster.OQty AS OQty,
        tblrate_master.assigned_rate
        FROM `tblitems` 
        JOIN tblstockmaster ON tblstockmaster.ItemID = tblitems.item_code AND tblstockmaster.PlantID = tblitems.PlantID 
        LEFT JOIN tblrate_master ON tblrate_master.item_id=tblitems.item_code AND tblrate_master.PlantID = tblitems.PlantID AND tblrate_master.state_id = "UP" AND tblrate_master.distributor_id= "'.$CustType.'"
        WHERE tblitems.PlantID = '.$selected_company.' AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'"  AND tblstockmaster.cnfid = "1"  
         AND tblitems.subgroup_id IN('.$item_group.')';
        if($GodownID !==''){
            $sql .= ' AND tblstockmaster.GodownID = "'.$GodownID.'"';
        } 
        
        }else{
            $sql = 'SELECT  tblitems.PlantID,tblitems.item_code,tblitems.description,tblitems.case_qty,tblitems.unit,tblrate_master.assigned_rate,
            (SELECT SUM(tblstockmaster.OQty) AS OQty FROM tblstockmaster WHERE tblstockmaster.ItemID=tblitems.item_code AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" GROUP BY tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY) AS OQty
         FROM `tblitems` 
        LEFT JOIN tblrate_master ON tblrate_master.item_id=tblitems.item_code AND tblrate_master.PlantID = tblitems.PlantID AND tblrate_master.state_id = "UP" AND tblrate_master.distributor_id= "'.$CustType.'"
        WHERE tblitems.PlantID = '.$selected_company.'   
         AND tblitems.subgroup_id IN('.$item_group.') ';
       
        }
        $sql .= ' ORDER BY tblitems.subgroup_id ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
    public function GetStockData($filterdata,$item_group)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $from_date = to_sql_date($filterdata["from_date"]).' 00:00:00';
        $to_date = to_sql_date($filterdata["to_date"]).' 23:59:59';
        $GodownID = $filterdata["GodownID"];
        
        $sql = 'SELECT tblhistory.*,tblitems.item_code FROM `tblhistory` 
        INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND
         tblitems.subgroup_id IN('.$item_group.') AND tblhistory.BillID IS NOT NULL ';
        if($GodownID !==''){
            $sql .= ' AND tblhistory.GodownID = "'.$GodownID.'"';
        }
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
    public function get_stock_itemlist($filterdata,$item_group)
     {
        /*$from_date = to_sql_date($filterdata["from_date"]);*/
        $to_date = to_sql_date($filterdata["to_date"]); 
        $from_date = "2021-04-01";
        //$to_date = date('Y-m-d'); 
        
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $CustType = '1';
        }else if($selected_company == "2"){
            $CustType = '13';
        }else if($selected_company == "3"){
            $CustType = '21';
        }
       
        $sql = 'SELECT tblitems.*,tblrate_master.assigned_rate,tblstockmaster.OQty,( SELECT SaleRate FROM tblhistory WHERE tblhistory.ItemID=tblitems.item_code AND tblhistory.PlantID = tblitems.PlantID AND tblhistory.TType = "P" AND tblhistory.TType2 = "PURCHASE" AND tblhistory.FY = "'.$fy.'" ORDER BY id DESC LIMIT 1) AS rate FROM `tblitems` 
        LEFT JOIN tblrate_master ON tblrate_master.item_id=tblitems.item_code AND tblrate_master.PlantID = tblitems.PlantID AND tblrate_master.state_id = "UP" AND tblrate_master.distributor_id= "'.$CustType.'"
        LEFT JOIN tblstockmaster ON tblstockmaster.ItemID=tblitems.item_code AND tblstockmaster.PlantID = tblitems.PlantID AND tblstockmaster.FY = "'.$fy.'"
        WHERE tblitems.PlantID = '.$selected_company.'  AND tblitems.subgroup_id IN('.$item_group.')';
        $sql .= 'ORDER BY tblitems.subgroup_id ASC';
        
        $result = $this->db->query($sql)->result_array();
        return $result;
         
     }
     
    public function get_stock_itemdetails_for_body_data($item_group,$filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $report_type = $filterdata["report_type"];
        
        $sql = 'SELECT SUM(tblhistory.BilledQty) as qty_sum,tblhistory.ItemID,tblhistory.TType,tblhistory.TType2,tblitems.case_qty  FROM `tblhistory` 
        INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID
        WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND tblitems.subgroup_id IN('.$item_group.') AND tblhistory.BillID IS NOT NULL';
        
        $sql .= ' GROUP BY tblhistory.ItemID,tblhistory.TType,tblhistory.TType2';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_stock_itemlist_new($filterdata,$item_group)
     {
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]); 
        
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
   
$sql = 'SELECT tblhistory.OrderID, tblhistory.ItemID,tblitems.description,tblhistory.AccountID,tblclients.StationName FROM `tblhistory`

INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID';
if($states || $client_type){
     $sql .= ' INNER JOIN tblclients ON tblclients.AccountID=tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID';
}else{
    $sql .= ' INNER JOIN tblclients ON tblclients.AccountID=tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID';
}
if($loc_type){
     $sql .= ' INNER JOIN tblaccountlocations ON tblhistory.AccountID=tblaccountlocations.AccountID AND tblhistory.PlantID = tblaccountlocations.PlantID';
}
if($staff_id){
    $sql .= ' INNER JOIN tblcustomer_admins ON tblcustomer_admins.customer_id=tblclients.AccountID AND tblcustomer_admins.company_id = tblclients.PlantID';
}
$sql .= ' WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND tblitems.subgroup_id IN('.$item_group.') AND tblhistory.BillID IS NOT NULL AND tblhistory.NetChallanAmt !=0.00';

if($loc_type == "3"){
    $sql .= ' AND tblaccountlocations.LocationTypeID IN(1,2,3)';
}else{
    $sql .= ' AND tblaccountlocations.LocationTypeID = '.$loc_type;
}
if($states){
    $sql .= ' AND tblclients.state ="'.$states.'"';
}
if($staff_id){
    $sql .= ' AND tblcustomer_admins.staff_id IN('.$staff_ids_uniqu_s.')';
}
if($client_type){
    $sql .= ' AND tblclients.DistributorType ="'.$client_type.'"';
}

if($report_type == "freshrtn"){
            $sql .= ' AND tblhistory.TType ="R" AND TType2="Fresh"';
        }elseif($report_type == "damage"){
            $sql .= ' AND tblhistory.TType ="R" AND TType2="Damage"';
        }elseif($report_type == "netsales"){
            //$sql .= ' AND tblhistory.TType ="O" AND TType2="Order"';
            
        }elseif($report_type == "sales"){
            $sql .= ' AND tblhistory.TType ="O" AND TType2="Order"';
        }else{
            
        }
 
$sql .= ' GROUP BY tblhistory.ItemID,tblhistory.AccountID ORDER BY tblclients.StationName ASC';

        $result = $this->db->query($sql)->result_array();
        return $result;
         
     }
    
    // Production Report code
    
    public function item_division_group(){
        
        $this->db->order_by('name', 'asc');
        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }
    
    function getaccounts($postData){

     $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_clients = '';
    $where_staff = '';
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'clients.*');
       $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR AccountID LIKE "%' . $q . '%" ESCAPE \'!\') ';
       $this->db->where($where_clients);
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       $records = $this->db->get(db_prefix() . 'clients')->result();
       foreach($records as $row ){
          $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"source"=>'con');
       }
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'staff.*');
       $where_staff .= '(AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR firstname LIKE "%' . $q . '%" ESCAPE \'!\' OR lastname LIKE "%' . $q. '%" ESCAPE \'!\' OR stationName LIKE "%' . $q . '%" ESCAPE \'!\') ';
       $this->db->where($where_staff);
       $this->db->where(db_prefix() . 'staff.SubActGroupID', '10022004');
       $records = $this->db->get(db_prefix() . 'staff')->result();
       foreach($records as $row ){
           $full_name = $row->firstname." ".$row->lastname;
          $response[] = array("label"=>$full_name,"value"=>$row->AccountID,"source"=>'staff');
       }

     }

     return $response;
  }
   function itemlist($postData){

     $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_items = '';
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'items.*');
       $where_items .= '(   item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q . '%" ESCAPE \'!\' OR long_description LIKE "%' . $q. '%" ESCAPE \'!\') AND ' . db_prefix() . 'items.isactive = "Y" AND '.db_prefix() . 'items.subgroup_id NOT IN(9,20,36)';
       
       $this->db->where($where_items);
       
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        
       $records = $this->db->get(db_prefix() . 'items')->result();

       foreach($records as $row ){
          $response[] = array("label"=>$row->description,"value"=>$row->item_code);
       }

     }

     return $response;
  }
  
    public function get_account_details($AccountID)
     {  
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'clients.*
        FROM '.db_prefix().'clients WHERE AccountID = "'.$AccountID.'" AND PlantID = '.$selected_company;
        
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    public function get_staff_details($AccountID)
     {  
        
        $sql ='SELECT '.db_prefix().'staff.*
        FROM '.db_prefix().'staff WHERE AccountID = "'.$AccountID.'"';
        
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    public function get_item_details($ItemID)
     {  
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'items.*
        FROM '.db_prefix().'items WHERE item_code = "'.$ItemID.'" AND PlantID = '.$selected_company;
        
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
  public function get_production_for_body_data($filterdata)
     { 

        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $item_division = $filterdata["item_division"];
        $accountID = $filterdata["accountID"];
        $report_type = $filterdata["report_type"];
        $ItemID = $filterdata["ItemID"];
        $source = $filterdata["source"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        if($report_type == 1 && empty($ItemID) && empty($accountID)){
            $sql = ' SELECT '.db_prefix() . 'production.*,tblitems.description,tblstaff.firstname FROM '.db_prefix() . 'production 
            LEFT JOIN tblstaff ON tblstaff.AccountID = tblproduction.manager_name
            INNER JOIN tblitems ON tblitems.item_code = tblproduction.recipeID AND tblitems.PlantID = tblproduction.PlantID 
            WHERE '.db_prefix() . 'production.FY = '.$fy.'  AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" ORDER BY tblproduction.pro_order_id ASC';
            
        }else if($report_type == 2 && empty($ItemID) && empty($accountID)){
            $sql = 'SELECT SUM(tblproduction.batch_qty) AS TotalBatch,SUM(tblproduction.Finish_good_qty) AS STDFGQty,SUM(tblproduction.Finish_good_qty_new) AS ActualFGQty,tblitems.description,tblitems.case_qty,tblproduction.recipeID
                    FROM tblproduction 
                    INNER JOIN tblitems ON tblitems.item_code=tblproduction.recipeID AND tblitems.PlantID = tblproduction.PlantID 
                    WHERE tblproduction.FY = '.$fy.' AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
                    GROUP BY tblproduction.recipeID ORDER BY tblproduction.recipeID';
        }else if(!empty($ItemID) && empty($accountID)){
            $sql = ' SELECT '.db_prefix() . 'production.*,'.db_prefix() . 'clients.*,tblstaff.firstname FROM '.db_prefix() . 'production 
            LEFT JOIN tblclients ON tblclients.AccountID=tblproduction.contractor_name AND tblclients.PlantID = tblproduction.PlantID 
            LEFT JOIN tblstaff ON tblstaff.AccountID=tblproduction.manager_name
            WHERE '.db_prefix() . 'production.FY = '.$fy.' AND  tblproduction.recipeID = "'.$ItemID.'"  AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
             ORDER BY tblclients.AccountID';
        }else if(empty($ItemID) && !empty($accountID)){
            if($source == "con"){
                $sql = ' SELECT '.db_prefix() . 'production.*,'.db_prefix() . 'clients.*,tblitems.description FROM '.db_prefix() . 'production 
            INNER JOIN tblclients ON tblclients.AccountID=tblproduction.contractor_name AND tblclients.PlantID = tblproduction.PlantID 
            INNER JOIN tblitems ON tblitems.item_code=tblproduction.recipeID AND tblitems.PlantID = tblproduction.PlantID 
            WHERE '.db_prefix() . 'production.FY = '.$fy.' AND  tblproduction.contractor_name = "'.$accountID.'"  AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
             ORDER BY tblclients.AccountID';
            }else{
                $sql = ' SELECT '.db_prefix() . 'production.*,'.db_prefix() . 'staff.*,tblitems.description FROM '.db_prefix() . 'production 
            INNER JOIN tblstaff ON tblstaff.AccountID=tblproduction.manager_name 
            INNER JOIN tblitems ON tblitems.item_code=tblproduction.recipeID AND tblitems.PlantID = tblproduction.PlantID 
            WHERE '.db_prefix() . 'production.FY = '.$fy.' AND  tblproduction.manager_name = "'.$accountID.'"  AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
             ORDER BY tblstaff.AccountID';
            }
            
        }else if(!empty($ItemID) && !empty($accountID)){
            if($source == "con"){
                $sql = ' SELECT '.db_prefix() . 'production.*,'.db_prefix() . 'clients.*,tblitems.description FROM '.db_prefix() . 'production 
            INNER JOIN tblclients ON tblclients.AccountID=tblproduction.contractor_name AND tblclients.PlantID = tblproduction.PlantID 
            INNER JOIN tblitems ON tblitems.item_code=tblproduction.recipeID AND tblitems.PlantID = tblproduction.PlantID 
            WHERE '.db_prefix() . 'production.FY = '.$fy.' AND  tblproduction.recipeID = "'.$ItemID.'" AND  tblproduction.contractor_name = "'.$accountID.'"  AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
             ORDER BY tblclients.AccountID';
            }else{
                $sql = ' SELECT '.db_prefix() . 'production.*,'.db_prefix() . 'staff.*,tblitems.description FROM '.db_prefix() . 'production 
            INNER JOIN tblstaff ON tblstaff.AccountID=tblproduction.manager_name 
            INNER JOIN tblitems ON tblitems.item_code=tblproduction.recipeID AND tblitems.PlantID = tblproduction.PlantID 
            WHERE '.db_prefix() . 'production.FY = '.$fy.' AND  tblproduction.recipeID = "'.$ItemID.'" AND  tblproduction.manager_name = "'.$accountID.'"  AND tblproduction.production_status = "MOVEMENT FOR GODOWN" AND tblproduction.PlantID = '.$selected_company.' AND tblproduction.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
             ORDER BY tblstaff.AccountID';
            }
            
        }
        
       $result = $this->db->query($sql)->result_array();
        return $result;
        
     }
      public function item_division_group_data(){
        
        // $this->db->order_by('name', 'asc');
        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }
      public function get_company_detail()
     {  
         
        $selected_company = $this->session->userdata('root_company');
      
        $sql ='SELECT '.db_prefix().'rootcompany.*
        FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
     }
    // END production reports code
    public function get_rate_table_data($data)
     {  
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $item_group = explode(",",$data['item_group']);
        $item_type = $data['item_data'];
        $this->db->select(db_prefix() .'rate_master.item_id,'.db_prefix() .'rate_master.assigned_rate,'.db_prefix() .'rate_master.effective_date,'.db_prefix() . 'items.description,'.db_prefix() . 'items.case_qty,'.db_prefix() . 'items.crate_qty ,'.db_prefix() .'taxes.taxrate');
        $this->db->from(db_prefix() .'rate_master');
        if($data['item_data'] == '1'){
            $this->db->join(db_prefix() .'items', db_prefix() .'items.item_code = '.db_prefix() .'rate_master.item_id AND '.db_prefix() .'items.PlantID = '.db_prefix() .'rate_master.PlantID AND '.db_prefix() .'items.isactive = "Y"');
        }else{
            $this->db->join(db_prefix() .'items', db_prefix() .'items.item_code = '.db_prefix() .'rate_master.item_id AND '.db_prefix() .'items.PlantID = '.db_prefix() .'rate_master.PlantID');
        }
        
        $this->db->join(db_prefix() .'taxes', db_prefix() .'taxes.id = '.db_prefix() .'items.tax ');
        $this->db->where(db_prefix() .'rate_master.PlantID', $selected_company);
        
        if($data['states'] !=''){
            $this->db->where(db_prefix() .'rate_master.state_id', $data['states']);
        }
        if($data['distributor_id'] !=''){
            $this->db->where(db_prefix() .'rate_master.distributor_id', $data['distributor_id']);
        }
        if($data['item_group'] !=''){
            $this->db->where_in(db_prefix() .'items.subgroup_id', $item_group);
        }
        $this->db->order_by(db_prefix() .'items.subgroup_id', 'ASC');
        return $this->db->get()->result_array();/*
        echo $this->db->last_query();die;*/
        
     }
     
    // start target entry andtarget Vs acivements
     public function get_salesstaff(){
        $selected_company = $this->session->userdata('root_company');
         $data_a = array('30001002');
         $this->db->select('firstname,lastname,staffid,AccountID');
         $this->db->from('tblstaff');
         $this->db->where_in('SubActGroupID',$data_a);
         $this->db->where('active',1);
         $this->db->where('PlantID',$selected_company);
         $this->db->order_by('firstname','ASC');
      return  $data = $this->db->get()->result_array();
         
     }
     public function get_salesstaff2(){
         $selected_company = $this->session->userdata('root_company');
         $data_a = array('30001002');
         $this->db->select('firstname,lastname,staffid,AccountID');
         $this->db->from('tblstaff');
         $this->db->where_in('SubActGroupID',$data_a);
         $this->db->where('PlantID',$selected_company);
         $this->db->order_by('firstname','ASC');
      return  $data = $this->db->get()->result_array();
         
     }
     public function get_targetList($data){
         
          $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
      
        $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.StationName,'.db_prefix() . 'customers_groups.name');
        $this->db->from(db_prefix() . 'customer_admins');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'customer_admins.customer_id', 'left');
        $this->db->join(db_prefix() . 'customers_groups', db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType', 'left');
        // $this->db->join(db_prefix() . 'accountitemdiv', db_prefix() . 'accountitemdiv.AccountID = ' . db_prefix() . 'clients.AccountID', 'left');
       
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       if($data['staff_d'] != ''){
            $this->db->where(db_prefix() . 'customer_admins.staff_id', $data['staff_d']);
       }
    //   $this->db->where(db_prefix() . 'customer_admins.staff_id', $data['staff_d']);
       $this->db->order_by(db_prefix() . 'clients.company');
       return $this->db->get()->result_array();
    //   echo $this->db->last_query();
     }
      public function get_coutomer_division($data){
         
          $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
     $month = substr($data['month_data'], -2);
     
      $this->db->select(db_prefix() . 'staff_target.Targate,'.db_prefix() . 'clients.AccountID,'.db_prefix() . 'customers_groups.name,'.db_prefix() . 'accountitemdiv.ItemDivID');
 
        $this->db->from(db_prefix() . 'customer_admins');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'customer_admins.customer_id', 'left');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'customer_admins.staff_id', 'left');
        $this->db->join(db_prefix() . 'customers_groups', db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType', 'left');
        $this->db->join(db_prefix() . 'accountitemdiv', db_prefix() . 'accountitemdiv.AccountID = ' . db_prefix() . 'clients.AccountID AND  '.db_prefix() . 'accountitemdiv.PlantID = '.$selected_company, 'left');
        $this->db->join(db_prefix() . 'staff_target', db_prefix() . 'staff_target.Staff_AccountID = ' . db_prefix() . 'staff.AccountID AND '.db_prefix() . 'staff_target.ItemDivID = ' . db_prefix() . 'accountitemdiv.ItemDivID AND '.db_prefix() . 'staff_target.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'staff_target.MonthID = ' . $month, 'left');
       
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        if($data['staff_d'] != ''){
            $this->db->where(db_prefix() . 'customer_admins.staff_id', $data['staff_d']);
       }
       return $this->db->get()->result_array();
        echo $this->db->last_query();
     }
     public function get_staff_business_division($data){
         
          $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
     $month = substr($data['month_data'], -2); 
    
      $this->db->select(db_prefix() . 'new_business_target.*');
        $this->db->from(db_prefix() . 'new_business_target');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.AccountID = ' . db_prefix() . 'new_business_target.Staff_AccountID', 'left');
       
       $this->db->like(db_prefix() . 'new_business_target.FY', $year);
       $this->db->where(db_prefix() . 'new_business_target.MonthID', $month);
       $this->db->where(db_prefix() . 'new_business_target.PlantID', $selected_company);
       
           $this->db->where(db_prefix() . 'staff.staffid', $data['staff_d']);
    
       
       return $this->db->get()->result_array();
        echo $this->db->last_query();
     }
      public function sum_get_coutomer_division($data){ 
         
          $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
     $month = substr($data['month_data'], -2);
     $this->db->select_sum(db_prefix() . 'staff_target.Targate');
      $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'accountitemdiv.ItemDivID');
 
        $this->db->from(db_prefix() . 'customer_admins');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'customer_admins.customer_id', 'left');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'customer_admins.staff_id', 'left');
        $this->db->join(db_prefix() . 'accountitemdiv', db_prefix() . 'accountitemdiv.AccountID = ' . db_prefix() . 'clients.AccountID AND  '.db_prefix() . 'accountitemdiv.PlantID = '.$selected_company, 'left');
        $this->db->join(db_prefix() . 'staff_target', db_prefix() . 'staff_target.Staff_AccountID = ' . db_prefix() . 'staff.AccountID AND '.db_prefix() . 'staff_target.ItemDivID = ' . db_prefix() . 'accountitemdiv.ItemDivID AND '.db_prefix() . 'staff_target.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'staff_target.MonthID = ' . $month, 'left');
       
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        if($data['staff_d'] != ''){
            $this->db->where(db_prefix() . 'customer_admins.staff_id', $data['staff_d']);
       }
       $this->db->group_by(db_prefix() . 'accountitemdiv.ItemDivID');
        return $this->db->get()->result_array();
        // echo $this->db->last_query();die;
     }
     
      public function sum_get_achievement_division($data){
         
          $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
     $month = substr($data['month_data'], -2);
     /*$start_date = '2022-'.$month.'-01';
     $end_date = '2022-'.$month.'-31';*/
     
     if ( $month <= 03 ) {
            $FY = date('y') - 1;
        }
        else {
            $FY = date('y');
        }
     $start_date = '20'.$FY.'-'.$month.'-01';
    // Converting string to date
    $date = strtotime($start_date);
    // Last date of current month.
    $end_date = date("Y-m-t", $date );
    /* echo $start_date;
     echo "<br>";
     echo $end_date;
     die;*/

         $this->db->select_sum(db_prefix() . 'history.NetChallanAmt');
     $this->db->select(db_prefix() . 'history.AccountID,'.db_prefix() . 'staff_target.ItemDivID');
    $this->db->from(db_prefix() . 'staff_target');
    
   $this->db->join(db_prefix() . 'items', db_prefix() . 'items.group_id = ' . db_prefix() . 'staff_target.ItemDivID AND '. db_prefix() . 'items.PlantID = '.$selected_company , 'left');
    $this->db->join(db_prefix() . 'history', db_prefix() . 'history.AccountID = ' . db_prefix() . 'staff_target.AccountID AND '.db_prefix() . 'history.ItemID = ' . db_prefix() . 'items.item_code AND '.db_prefix() . 'history.PlantID = '.$selected_company.' AND '.db_prefix() . 'history.FY = '.$year, 'left');
    $this->db->like(db_prefix() . 'staff_target.AccountID', $data['accountId']);
    $this->db->like(db_prefix() . 'staff_target.MonthID', $month);
    $this->db->where(db_prefix() . 'staff_target.PlantID', $selected_company);
    $this->db->like(db_prefix() . 'staff_target.FY', $year);
      $this->db->like(db_prefix() . 'history.TType', 'O');
      $this->db->like(db_prefix() . 'history.TType2', 'Order');
      $this->db->where(db_prefix() . 'history.OrderID !=', NULL);
      $this->db->where(db_prefix() . 'history.TransID !=', NULL);
        $this->db->where(db_prefix() . 'history.TransDate2 >=', $start_date.' 00:00:00');
        $this->db->where(db_prefix() . 'history.TransDate2 <=',$end_date.' 23:59:59');
         $this->db->group_by(db_prefix() . 'staff_target.ItemDivID');
       return $this->db->get()->result_array();
        //  echo $this->db->last_query();die;
     }
      public function create_targetSale($data){
       
          $selected_company = $this->session->userdata('root_company');
          $username = $this->session->userdata('username');
      $year = $_SESSION['finacial_year'];
      $month = substr($data['month_data'], -2);
      $staff_d = $this->db->get_where('tblstaff',array('staffid'=>$data['Staff_AccountID']))->row_array();
    
        $i = 0; foreach($data['AccountID'] as $key=>$AccountID){
           $item_id = $AccountID.'_item_id';
           $target = $AccountID.'_target';
          $j = 0;   
        foreach($data[$item_id] as $key=>$ItemDivID){
            
            if($AccountID == 'New_Business'){
                 $data_array = array(
                 'PlantID' => $selected_company,
                 'FY' => $year,
                 'Staff_AccountID' => $staff_d['AccountID'],
                 'MonthID' => $month,
                 'ItemDivID' => $ItemDivID,
                 'Targate' => $data[$target][$j],
                 'UserID' => $username,
                 'TransDate' => date('Y-m-d H:i:s')
                 );
             
                $effected =  $this->db->insert('tblnew_business_target',$data_array);
                }else{
                $data_array = array(
                 'PlantID' => $selected_company,
                 'FY' => $year,
                 'Staff_AccountID' => $staff_d['AccountID'],
                 'MonthID' => $month,
                 'AccountID' => $AccountID,
                 'ItemDivID' => $ItemDivID,
                 'Targate' => $data[$target][$j],
                 'UserID' => $username,
                 'TransDate' => date('Y-m-d H:i:s')
                 );
                $effected =  $this->db->insert('tblstaff_target',$data_array);
            }
                
                
            $j++;
              
          }
                 
         $i++;}
         return $effected;
     }
     public function create_targetSale_bkp_bussiness($data){
       
          $selected_company = $this->session->userdata('root_company');
          $username = $this->session->userdata('username');
      $year = $_SESSION['finacial_year'];
      $month = substr($data['month_data'], -2);
      $staff_d = $this->db->get_where('tblstaff',array('staffid'=>$data['Staff_AccountID']))->row_array();
    
        $i = 0; foreach($data['AccountID'] as $key=>$AccountID){
           $item_id = $AccountID.'_item_id';
           $target = $AccountID.'_target';
          $j = 0;   
        foreach($data[$item_id] as $key=>$ItemDivID){
            /*if($data[$target][$j] == "" || $data[$target][$j] == null){
                
            }else{*/
                $data_array = array(
                 'PlantID' => $selected_company,
                 'FY' => $year,
                 'Staff_AccountID' => $staff_d['AccountID'],
                 'MonthID' => $month,
                 'AccountID' => $AccountID,
                 'ItemDivID' => $ItemDivID,
                 'Targate' => $data[$target][$j],
                 'UserID' => $username,
                 'TransDate' => date('Y-m-d H:i:s')
                 );
                $effected =  $this->db->insert('tblstaff_target',$data_array);
            //}
                
            $j++;
              
          }
                 
         $i++;}
         return $effected;
     }
      public function update_targetSale($data){
       
          $selected_company = $this->session->userdata('root_company');
          $username = $this->session->userdata('username');
      $year = $_SESSION['finacial_year'];
      $month = substr($data['month_data'], -2);
      $staff_d = $this->db->get_where('tblstaff',array('staffid'=>$data['Staff_AccountID']))->row_array();
    
        $i = 0; foreach($data['AccountID'] as $key=>$AccountID){
           $item_id = $AccountID.'_item_id';
           $target = $AccountID.'_target';
          $j = 0;   foreach($data[$item_id] as $key=>$ItemDivID){
              if($AccountID == 'New_Business'){
                   $data_array = array(
              
                 'Targate' => $data[$target][$j],
                 'UserID2' => $username,
                 'Lupdate' => date('Y-m-d H:i:s')
                 );
                
                 $this->db->where('FY' ,$year);
                 $this->db->where('PlantID' ,$selected_company);
                 $this->db->where('MonthID' ,$month);
                 $this->db->where('Staff_AccountID' ,$staff_d['AccountID']);
                 $this->db->where('ItemDivID' ,$ItemDivID);
                $effected =  $this->db->update('tblnew_business_target',$data_array);
              }else{
                   $data_array = array(
              
                 'Targate' => $data[$target][$j],
                 'UserID2' => $username,
                 'Lupdate' => date('Y-m-d H:i:s')
                 );
                 $this->db->where('FY' ,$year);
                 $this->db->where('PlantID' ,$selected_company);
                 $this->db->where('MonthID' ,$month);
                 $this->db->where('Staff_AccountID' ,$staff_d['AccountID']);
                 $this->db->where('AccountID' ,$AccountID);
                 $this->db->where('ItemDivID' ,$ItemDivID);
                $effected =  $this->db->update('tblstaff_target',$data_array);
                 if($this->db->affected_rows() == 0){
                  $data_array = array(
                 'PlantID' => $selected_company,
                 'FY' => $year,
                 'Staff_AccountID' => $staff_d['AccountID'],
                 'MonthID' => $month,
                 'AccountID' => $AccountID,
                 'ItemDivID' => $ItemDivID,
                 'Targate' => $data[$target][$j],
                 'UserID' => $username,
                 'TransDate' => date('Y-m-d H:i:s')
                 );
               
                $effected =  $this->db->insert('tblstaff_target',$data_array);
                
             }
              }
              
          $j++;}
                 
         $i++;}
         return $effected;
     } 
    public function update_targetSale_bkp_bussiness($data){
       
        $selected_company = $this->session->userdata('root_company');
        $username = $this->session->userdata('username');
      $year = $_SESSION['finacial_year'];
      $month = substr($data['month_data'], -2);
      $staff_d = $this->db->get_where('tblstaff',array('staffid'=>$data['Staff_AccountID']))->row_array();
    
        $i = 0; foreach($data['AccountID'] as $key=>$AccountID){
           $item_id = $AccountID.'_item_id';
           $target = $AccountID.'_target';
          $j = 0;   foreach($data[$item_id] as $key=>$ItemDivID){
               $data_array = array(
              
                 'Targate' => $data[$target][$j],
                 'UserID2' => $username,
                 'Lupdate' => date('Y-m-d H:i:s')
                 );
                 $this->db->where('FY' ,$year);
                 $this->db->where('PlantID' ,$selected_company);
                 $this->db->where('MonthID' ,$month);
                 $this->db->where('Staff_AccountID' ,$staff_d['AccountID']);
                 $this->db->where('AccountID' ,$AccountID);
                 $this->db->where('ItemDivID' ,$ItemDivID);
                $effected =  $this->db->update('tblstaff_target',$data_array); 
          $j++;}
                 
         $i++;}
         return $effected;
     }
    // end target entery and targate vs achivements
    
    
    // market outstanding 
    
    //Get All route
    public function get_all_route(){
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'route.*');
        $this->db->from(db_prefix() . 'route');
        $this->db->where(db_prefix() . 'route.PlantID', $selected_company);
        return $this->db->get()->result_array();
    }
    
    // Get All state
    public function get_all_states(){
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'xx_statelist.*');
        $this->db->from(db_prefix() . 'xx_statelist');
        $this->db->where(db_prefix() . 'xx_statelist.country_id', 1);
        $this->db->order_by(db_prefix() . 'xx_statelist.state_name', "ASC");
        return $this->db->get()->result_array();
    }
    
    // Get All Distributor Type
    public function get_all_dist_type(){
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'customers_groups.*');
        $this->db->from(db_prefix() . 'customers_groups');
        $this->db->where(db_prefix() . 'customers_groups.PlantID', $selected_company);
        $this->db->order_by(db_prefix() . 'customers_groups.name', "DESC");
        return $this->db->get()->result_array();
    }
    
    // Get All Item Division
    public function get_all_item_division(){
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'items_groups.*');
        $this->db->from(db_prefix() . 'items_groups');
        //$this->db->where(db_prefix() . 'items_groups.PlantID', $selected_company);
        $this->db->order_by(db_prefix() . 'items_groups.name', "ASC");
        return $this->db->get()->result_array();
    }
    
    // Get All Item Division
    public function market_outstanding_data($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $routID = $filterdata["routID"];
        $states = $filterdata["states"];
        $loc_type = $filterdata["loc_type"];
        $dist_type = $filterdata["dist_type"];
        $staff_id = $filterdata["staff_id"];
        $from_date = "2021-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        
        $staff_ids = array();
        array_push($staff_ids, $staff_id);
        if($staff_id){
            $get_sql1 = 'SELECT * FROM tblstaff WHERE team_manage = "'.$staff_id.'"';
            $get_result1 = $this->db->query($get_sql1)->result_array();
            foreach ($get_result1 as $key1 => $value1) {
                array_push($staff_ids, $value1["staffid"]);
                $get_sql2 = 'SELECT * FROM tblstaff WHERE team_manage = "'.$value1["staffid"].'"';
                $get_result2 = $this->db->query($get_sql2)->result_array();
                foreach ($get_result2 as $key2 => $value2) {
                    array_push($staff_ids, $value2["staffid"]);
                    $get_sql3 = 'SELECT * FROM tblstaff WHERE team_manage = "'.$value2["staffid"].'"';
                    $get_result3 = $this->db->query($get_sql3)->result_array();
                    foreach ($get_result3 as $key3 => $value3) {
                        array_push($staff_ids, $value3["staffid"]);
                        $get_sql4 = 'SELECT * FROM tblstaff WHERE team_manage = "'.$value3["staffid"].'"';
                        $get_result4 = $this->db->query($get_sql4)->result_array();
                        foreach ($get_result4 as $key4 => $value4) {
                            array_push($staff_ids, $value4["staffid"]);
                            $get_sql5 = 'SELECT * FROM tblstaff WHERE team_manage = "'.$value4["staffid"].'"';
                            $get_result5 = $this->db->query($get_sql5)->result_array();
                            foreach ($get_result5 as $key5 => $value5) {
                                array_push($staff_ids, $value5["staffid"]);
                            }
                        }
                    }
                }
            }
        }
    $staff_ids_uniqu = array_unique($staff_ids);  
    $staff_ids_uniqu_s = implode(", ", $staff_ids_uniqu);
        $sql = '';
        
        
        $sql .= 'SELECT tblclients.StationName,tblclients.CtrlAccountID,tblclients.AccountID,tblclients.company,tblclients.state,tblaccountbalances.BAL1
        FROM tblclients'; 
        $sql .= ' INNER JOIN tblaccountbalances ON tblaccountbalances.AccountID=tblclients.AccountID AND tblaccountbalances.PlantID = tblclients.PlantID AND tblaccountbalances.FY = '.$fy;
        //$sql .= ' INNER JOIN tblaccountledger ON tblaccountledger.AccountID=tblclients.AccountID AND tblaccountledger.PlantID = tblclients.PlantID ';
        if($routID !==""){
           $sql .= ' INNER JOIN tblaccountroutes ON tblaccountroutes.AccountID = tblclients.AccountID AND tblaccountroutes.PlantID = tblclients.PlantID '; 
        }
        if($loc_type){
            $sql .= ' INNER JOIN tblaccountlocations ON tblclients.AccountID=tblaccountlocations.AccountID AND tblclients.PlantID = tblaccountlocations.PlantID';
        }
        if($staff_id){
            $sql .= ' INNER JOIN tblcustomer_admins ON tblcustomer_admins.customer_id=tblclients.AccountID AND tblcustomer_admins.company_id = tblclients.PlantID';
        }
        $sql .= ' WHERE tblclients.PlantID = '.$selected_company. ' AND tblclients.SubActGroupID = "60001004" ';
        // if($states !==""){
        //     $sql .= ' AND tblclients.state = "'.$states.'"';
        // }
        
        if($dist_type !==""){
            $sql .= ' AND tblclients.DistributorType = "'.$dist_type.'"';
        }
        if($routID !==""){
            $sql .= ' AND tblaccountroutes.RouteID = "'.$routID.'" AND tblaccountroutes.PlantID = '.$selected_company;
        }
        if($loc_type == "3"){
            $sql .= ' AND tblaccountlocations.LocationTypeID IN(1,2,3) AND tblaccountlocations.PlantID = '.$selected_company;
        }else{
            $sql .= ' AND tblaccountlocations.LocationTypeID = '.$loc_type.' AND tblaccountlocations.PlantID = '.$selected_company;
        }
        if($staff_id){
            $sql .= ' AND tblcustomer_admins.staff_id IN('.$staff_ids_uniqu_s.')';
        }
        //$sql .= ' GROUP BY tblaccountledger.AccountID';
        $sql .= ' Order BY tblclients.AccountID ASC';
        /*echo  $sql;
        die;*/
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function market_outstanding_credit_data($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $FYNew = $fy - 1;
        $from_date = "20".$FYNew."-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        $sql = '';
        
        $sql .= 'SELECT SUM(tblaccountledger.Amount) AS Credit_Amt,AccountID
        FROM tblaccountledger 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$fy.'" AND TType = "C" AND tblaccountledger.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"  
        GROUP BY tblaccountledger.AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    public function market_outstanding_debit_data($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $FYNew = $fy - 1;
        $from_date = "20".$FYNew."-04-01";
        //$from_date = "2021-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        $sql = '';
        
        $sql .= 'SELECT SUM(tblaccountledger.Amount) AS Debit_Amt,AccountID
        FROM tblaccountledger 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$fy.'" AND TType = "D" AND tblaccountledger.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"  
        GROUP BY tblaccountledger.AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function market_outstanding_trans_data($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        $from_date = "2021-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        $sql = '';
        
        $sql .= 'SELECT SUM(tblaccountledger.Amount) AS total_Amt,tblaccountledger.AccountID
        FROM tblaccountledger 
        INNER JOIN tblclients ON tblclients.AccountID = tblaccountledger.AccountID AND tblclients.PlantID = tblaccountledger.PlantID
        WHERE tblaccountledger.PlantID = '.$selected_company.' AND tblaccountledger.FY = "'.$fy.'" AND tblclients.SubActGroupID= "60001004" AND tblaccountledger.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"  
        GROUP BY tblaccountledger.AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function market_outstanding_last_billDate($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        $from_date = "2021-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        $sql = '';
        
        $sql .= 'SELECT max(TransDate2) AS TransDate2,AccountID FROM tblhistory 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$fy.'" AND TType = "O" AND TType2 = "Order" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"  
        GROUP BY AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function market_outstanding_currDaySale($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        $from_date = "2021-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        $sql = '';
        
        $sql .= 'SELECT SUM(NetChallanAmt) AS NetChallanAmt,AccountID FROM tblhistory 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$fy.'" AND TType = "O" AND TType2 = "Order" AND tblhistory.TransDate2 BETWEEN "'.$to_date.' 00:00:00" AND "'.$to_date.' 23:59:59"  
        GROUP BY AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    public function market_outstanding_preDaySale($filterdata){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        $from_date = "2021-04-01";
        $to_date = to_sql_date($filterdata["as_on"]); 
        $to_date = date('Y-m-d', strtotime('-1 day', strtotime($to_date)));
        $sql = '';
        
        $sql .= 'SELECT SUM(NetChallanAmt) AS NetChallanAmt,AccountID FROM tblhistory 
        WHERE PlantID = '.$selected_company.' AND FY = "'.$fy.'" AND TType = "O" AND TType2 = "Order" AND tblhistory.TransDate2 BETWEEN "'.$to_date.' 00:00:00" AND "'.$to_date.' 23:59:59"  
        GROUP BY AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    // end market outstanding
   
   // Start Create ledger 
   
   public function get_vendor_data($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
       
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002','60001004']);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
   public function get_state_list(){
        // tblxx_statelist
       return $this->db->order_by('state_name')->get('tblxx_statelist')->result_array();
    }
     public function get_data_vendor($id = '')
    {
     $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select();
        $this->db->from(db_prefix() . 'clients');
        $this->db->join(db_prefix() . 'xx_citylist', db_prefix() . 'xx_citylist.id = ' . db_prefix() . 'clients.city', 'left');
        $this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
        $this->db->where(db_prefix() . 'clients.AccountID', $id);
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       return $this->db->get()->row();
 
      
    }
    public function getCratesRcvdVehicle($filterdata){
        $from_date = to_sql_date($filterdata);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $regExp ="'.*;s:[0-9]+:'".$selected_company."'.*'";
        $regExp1 ="'.*;s:[0-9]+:";
        $regExp2 =".*'";
        
        $sql = 'SELECT tblvehiclereturn.*,tblroute.name,tblchallanmaster.VehicleID,tblstaff.firstname,tblstaff.lastname FROM tblvehiclereturn 
                INNER JOIN tblchallanmaster ON tblchallanmaster.ChallanID = tblvehiclereturn.ChallanID AND tblchallanmaster.PlantID = tblvehiclereturn.PlantID AND tblchallanmaster.FY = tblvehiclereturn.FY
                INNER JOIN tblroute ON tblroute.RouteID = tblchallanmaster.RouteID AND tblroute.PlantID = tblchallanmaster.PlantID
                LEFT JOIN tblstaff ON tblstaff.AccountID = tblchallanmaster.DriverID AND tblstaff.staff_comp REGEXP '.$regExp1.'"'.$selected_company.'"'.$regExp2.' 
                WHERE tblvehiclereturn.FY = '.$fy.'  AND tblvehiclereturn.PlantID = '.$selected_company.' AND  tblvehiclereturn.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$from_date.' 23:59:59" 
                Order BY tblvehiclereturn.ChallanID ASC';
        $result_all = $this->db->query($sql)->result_array();
        $i = 0;
        foreach ($result_all as $key => $value) {
            $sql2 = 'SELECT tblaccountcrates.Qty,tblclients.company FROM tblaccountcrates 
            INNER JOIN tblclients ON tblclients.AccountID = tblaccountcrates.AccountID AND tblclients.PlantID = tblaccountcrates.PlantID
            WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.VoucherID = "'.$value['ReturnID'].'" AND TType = "C" 
            Order BY tblclients.company ASC';
            $PartyDetails = $this->db->query($sql2)->result_array();
            $result_all[$i]['PartyDetails'] = $PartyDetails;
            $i++;
        }
        return $result_all;
    }
    public function get_Crates_for_body_data($filterdata){
        
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $accountId = $filterdata["accountId"];
        $state_type = $filterdata["state_type"];
        $loc_type = $filterdata["loc_type"];
        $order_by = $filterdata["order_by"];
        
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        if ( date('m') <= 3 ) {
            $year = date('y') - 1;
        }
        else {
            $year = date('y');
        }
         if($accountId != ''){
            
            $sql = 'SELECT tblaccountcrates.* FROM tblaccountcrates 
                WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND  tblaccountcrates.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND tblaccountcrates.AccountID = "'.$accountId.'" ';
            $result_all = $this->db->query($sql)->result_array();
        
            $sql2 = 'SELECT * FROM tblaccountcrates 
                WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom = "OPENCRATES" AND tblaccountcrates.AccountID = "'.$accountId.'" ';
            $result_open_cr_debit = $this->db->query($sql2)->row();
            
            $result = array(
                'all' => $result_all,
                'opn_caret' => $result_open_cr_debit,
                );
                // print_r($result);die;
            return $result;
         }else{
             
             
            $Billing_end_Date = to_sql_date($filterdata["from_date"]);
            $state_type = $filterdata["state_type"];
            $loc_type = $filterdata["loc_type"];
            $order_by = $filterdata["order_by"];
            $currDate = date('Y-m-d');
            $preDay = date('Y-m-d', strtotime('-1 day', strtotime($currDate)));
            $Billing_start_Date =  '20'.$year.'-04-01';
            
            $Vehicle_end_Rtn_Date = to_sql_date($filterdata["to_date"]);
            $Vehicle_start_Rtn_Date = '20'.$year.'-04-01';
            if($Billing_end_Date > $Vehicle_end_Rtn_Date){
                $max_date = $Billing_end_Date;
            }else{
                 $max_date = $Vehicle_end_Rtn_Date;
            }
       
            $sql = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID,tblclients.company,tblclients.address,tblclients.StationName FROM tblaccountcrates 
                    LEFT JOIN tblclients ON tblclients.AccountID=tblaccountcrates.AccountID AND tblclients.PlantID = '.$selected_company; 
                    if($loc_type){
                    $sql .= ' INNER JOIN tblaccountlocations ON tblaccountcrates.AccountID=tblaccountlocations.AccountID AND tblaccountcrates.PlantID = tblaccountlocations.PlantID';
                    }
                    $sql .= ' WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND  tblaccountcrates.Transdate BETWEEN "'.$Billing_start_Date.' 00:00:00" AND "'.$max_date.' 23:59:59"';
            if($state_type){
                $sql .= ' AND tblclients.state = "'.$state_type.'"';
            }
            if($loc_type == "3"){
                $sql .= ' AND tblaccountlocations.LocationTypeID IN(1,2,3) AND tblaccountlocations.PlantID = '.$selected_company;
            }else{
                $sql .= ' AND tblaccountlocations.LocationTypeID = '.$loc_type.' AND tblaccountlocations.PlantID = '.$selected_company;
            }
            
            $sql .= ' Group BY tblaccountcrates.AccountID';
            if($order_by == 1){
                $sql .= ' ORDER BY tblclients.StationName ASC';
            }else{
                $sql .= ' ORDER BY tblclients.company ASC';
            }
            $result_all = $this->db->query($sql)->result_array();
        
            $sql1 = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID FROM tblaccountcrates 
                    WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.TType = "D" AND tblaccountcrates.PassedFrom != "OPENCRATES"  AND tblaccountcrates.Transdate BETWEEN "'.$Billing_start_Date.' 00:00:00" AND "'.$Billing_end_Date.' 23:59:59"  Group BY tblaccountcrates.AccountID';
            $result_debit = $this->db->query($sql1)->result_array();
            
            $sql2 = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID FROM tblaccountcrates 
                    WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.TType = "C" AND tblaccountcrates.PassedFrom != "OPENCRATES" AND tblaccountcrates.Transdate BETWEEN "'.$Vehicle_start_Rtn_Date.' 00:00:00" AND "'.$Vehicle_end_Rtn_Date.' 23:59:59"  Group BY tblaccountcrates.AccountID';
            $result_credit = $this->db->query($sql2)->result_array();
            
            $sql3 = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID FROM tblaccountcrates 
                    WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.TType = "D" AND tblaccountcrates.PassedFrom = "OPENCRATES" AND tblaccountcrates.Transdate BETWEEN "'.$Vehicle_start_Rtn_Date.' 00:00:00" AND "'.$Vehicle_end_Rtn_Date.' 23:59:59"  Group BY tblaccountcrates.AccountID';
            $result_open_cr_debit = $this->db->query($sql3)->result_array();
            
            $sql33 = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID FROM tblaccountcrates 
                    WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.TType = "C" AND tblaccountcrates.PassedFrom = "OPENCRATES" AND tblaccountcrates.Transdate BETWEEN "'.$Vehicle_start_Rtn_Date.' 00:00:00" AND "'.$Vehicle_end_Rtn_Date.' 23:59:59"  Group BY tblaccountcrates.AccountID';
            $result_open_cr_credit = $this->db->query($sql33)->result_array();
            
            /*$sql4 .= 'SELECT max(TransDate2) AS lastBill,AccountID FROM tblhistory 
                    WHERE PlantID = '.$selected_company.' AND FY = "'.$fy.'" AND TType = "O" AND TType2 = "Order" AND tblhistory.TransDate2 BETWEEN "'.$Billing_start_Date.' 00:00:00" AND "'.$Billing_end_Date.' 23:59:59"  
                    GROUP BY AccountID';
            $result_lastBill = $this->db->query($sql4)->result_array();*/
            
            /*$sql5 = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID FROM tblaccountcrates 
                    WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND tblaccountcrates.Transdate BETWEEN "'.$preDay.' 00:00:00" AND "'.$preDay.' 23:59:59"  Group BY tblaccountcrates.AccountID';
            $result_preDay = $this->db->query($sql5)->result_array();
            
            $sql6 = 'SELECT SUM(tblaccountcrates.Qty)as sum_total , tblaccountcrates.AccountID FROM tblaccountcrates 
                    WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND tblaccountcrates.Transdate BETWEEN "'.$currDate.' 00:00:00" AND "'.$currDate.' 23:59:59"  Group BY tblaccountcrates.AccountID';
            $result_currDay = $this->db->query($sql6)->result_array();*/
            
            
            $result = array(
                'all' => $result_all,
                'debit' => $result_debit,
                'credit' => $result_credit,
                'opn_debit' => $result_open_cr_debit,
                'opn_credit' => $result_open_cr_credit,
                /*'lastBill' => $result_lastBill,
                'preDay' => $result_preDay,
                'currDay' => $result_currDay,*/
                );
                // print_r($result);die;
            return $result;
           
        }
    }
    
    public function GetCrateLedger($filterdata)
    {
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $accountId = $filterdata["accountId"];
        $state_type = $filterdata["state_type"];
        $loc_type = $filterdata["loc_type"];
        $order_by = $filterdata["order_by"];
        
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if(date('y') == $fy){
            if ( date('m') <= 3 ) {
                $year = date('y') - 1;
            }else {
                $year = date('y');
            }
        }else{
            if ( date('m') <= 3 ) {
                $year = date('y');
            }else {
                $year = date('y') - 1;
            }
        }
        
        $FirstDate = '20'.$year.'-04-01';
        if($accountId != ''){
            $sql = 'SELECT SUM(Qty) AS OQty FROM tblaccountcrates 
                WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom = "OPENCRATES"  AND tblaccountcrates.AccountID = "'.$accountId.'"';
                $OpenCrate = $this->db->query($sql)->result_array();
                $OQty =  $OpenCrate['0']['OQty'];
                
            if($from_date == $FirstDate){
                $FromDate = $FirstDate;
                $ToDate = to_sql_date($filterdata["to_date"]);
                $sql1 = 'SELECT * FROM tblaccountcrates 
                WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND  tblaccountcrates.Transdate BETWEEN "'.$FromDate.' 00:00:00" AND "'.$ToDate.' 23:59:59" 
                AND tblaccountcrates.AccountID = "'.$accountId.'" ORDER BY tblaccountcrates.Transdate ASC';
                $Trans = $this->db->query($sql1)->result_array();
                $result = array(
                    'OpenCrate' => $OQty,
                    'Trans' => $Trans,
                );
                return $result;
            
            }else{
                $FromDate = $FirstDate;
                $ToDate = date('Y-m-d', strtotime('-1 day', strtotime($from_date)));
                $sql = 'SELECT TType,SUM(Qty) AS Qty FROM tblaccountcrates 
                WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND  tblaccountcrates.Transdate BETWEEN "'.$FromDate.' 00:00:00" AND "'.$ToDate.' 23:59:59" AND tblaccountcrates.AccountID = "'.$accountId.'" GROUP BY TType';
                $OpenCrate = $this->db->query($sql)->result_array();
                
                foreach ($OpenCrate as $key => $value) {
                    if($value['TType']== 'C'){
                        $OPNBal -= $value['Qty'];
                    }else{
                        $OPNBal += $value['Qty'];
                    }
                }
                $OQtyNew = $OPNBal + $OQty;
                
                $FromDate = $from_date;
                $ToDate = $to_date;
                $sql = 'SELECT * FROM tblaccountcrates 
                WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND  tblaccountcrates.Transdate BETWEEN "'.$FromDate.' 00:00:00" AND "'.$ToDate.' 23:59:59" 
                AND tblaccountcrates.AccountID = "'.$accountId.'" ORDER BY tblaccountcrates.Transdate ASC';
                $Trans = $this->db->query($sql)->result_array();
                $result = array(
                    'OpenCrate' => $OQtyNew,
                    'Trans' => $Trans,
                );
                return $result;
            }
        }else{
            
            $sql = 'SELECT tblaccountcrates.AccountID,SUM(tblaccountcrates.Qty) AS OQty FROM tblaccountcrates 
            WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom = "OPENCRATES"  GROUP BY AccountID';
            $OpenCrate = $this->db->query($sql)->result_array();
            
            $FromDate = $FirstDate;
            $ToDate = $from_date;
            $sql1 = 'SELECT tblaccountcrates.AccountID,SUM(tblaccountcrates.Qty) AS OQty FROM tblaccountcrates 
            WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND tblaccountcrates.TType = "D" AND  tblaccountcrates.Transdate BETWEEN "'.$FromDate.' 00:00:00" AND "'.$ToDate.' 23:59:59"  GROUP BY AccountID';
            $DebitCrate = $this->db->query($sql1)->result_array();
            
            $ToDate = $to_date;
            $sql11 = 'SELECT tblaccountcrates.AccountID,SUM(tblaccountcrates.Qty) AS OQty FROM tblaccountcrates 
            WHERE tblaccountcrates.FY = '.$fy.'  AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.PassedFrom != "OPENCRATES" AND tblaccountcrates.TType = "C" AND  tblaccountcrates.Transdate BETWEEN "'.$FromDate.' 00:00:00" AND "'.$ToDate.' 23:59:59"  GROUP BY AccountID';
            $CreditCrate = $this->db->query($sql11)->result_array();
            
            $sql111 = 'SELECT tblclients.AccountID,tblclients.company,tblclients.address,tblclients.StationName FROM tblclients ';
                if($loc_type){
                    $sql111 .= ' INNER JOIN tblaccountlocations ON tblclients.AccountID=tblaccountlocations.AccountID AND tblclients.PlantID = tblaccountlocations.PlantID';
                }
            $sql111 .= ' WHERE tblclients.PlantID = '.$selected_company.' ';
            if($loc_type == "3"){
                $sql111 .= ' AND tblaccountlocations.LocationTypeID IN(1,2,3) AND tblaccountlocations.PlantID = '.$selected_company;
            }else{
                $sql111 .= ' AND tblaccountlocations.LocationTypeID = '.$loc_type.' AND tblaccountlocations.PlantID = '.$selected_company;
            }
            if($state_type !==''){
                $sql111 .= ' AND tblclients.state = "'.$state_type.'"';
            }
            if($order_by == 1){
                $sql111 .= ' ORDER BY tblclients.AccountID ASC';
            }else{
                $sql111 .= ' ORDER BY tblclients.company ASC';
            }
            
            $AllAccount = $this->db->query($sql111)->result_array();
            
            $result = array(
                'OpenCrate' => $OpenCrate,
                'Debit' => $DebitCrate,
                'Credit' => $CreditCrate,
                'AllAccount' => $AllAccount,
            );
            return $result;
        }
    }
   // END Create ledger
}