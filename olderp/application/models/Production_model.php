<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Production_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
    function getitem_using_itemcode($postData){

    $response = array();
    $subgroup = array(9,20,36);
     if(isset($postData['search']) ){
     
	   $q = $postData['search'];
       $this->db->select('tblitems.item_code,tblitems.description,tblitems.unit,tblitems_sub_groups.name');
	   $where_items .= '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q. '%" ESCAPE \'!\')';
	   
       $selected_company = $this->session->userdata('root_company');
	   $this->db->where($where_items);
       $this->db->where(db_prefix() . 'items.isactive', "Y");
       $this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.subgroup_id');
       $this->db->join('tblitems_main_groups', 'tblitems_main_groups.id = tblitems_sub_groups.main_group_id');
       $this->db->where(db_prefix() . 'items_main_groups.id', '1');
	   $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $records = $this->db->get(db_prefix() . 'items')->result();

       foreach($records as $row ){
          $response[] = array("units"=>$row->unit,"sub_group_name"=>$row->name,"value"=>$row->item_code,"label"=>$row->description);
       }
     }

     return $response;
  }
	
	function itemDetails_by_itemcode($postData){
         if(isset($postData['search']) ){
         
    	   $q = $postData['search'];
           $this->db->select('tblitems.item_code,tblitems.description,tblitems.unit,tblitems_sub_groups.name,tblitems_sub_groups.main_group_id');
           $selected_company = $this->session->userdata('root_company');
    	   $this->db->where("item_code",$q);
           $this->db->where(db_prefix() . 'items.isactive', "Y");
           $this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.subgroup_id');
           $this->db->join('tblitems_main_groups', 'tblitems_main_groups.id = tblitems_sub_groups.main_group_id');
           //$this->db->where(db_prefix() . 'items_main_groups.id', '2');
    	   $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
           $records = $this->db->get(db_prefix() . 'items')->row();
            return $records;
         }
         return false;
    }
    // Get Item List For Recipe edit add
    
    function ItemListReceipe($postData)
    {
        $response = array();
        $subgroup = array('2','8','9','10','20','21','25','28','29','31','34','36','38','42','45','69','70','73');
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['search']) ){
           // Select record
           $q = $postData['search'];
           $where_items = '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q. '%")';
           $this->db->select('tblitems.item_code,tblitems.description,tblitems.unit,tblitems_sub_groups.name,tblitems_sub_groups.main_group_id');
           $this->db->where($where_items);
    	   $this->db->where('PlantID', $selected_company);
    	   //$this->db->where_in('subgroup_id', $subgroup);
    	   $this->db->where(db_prefix() . 'items.isactive', "Y");
           $this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.subgroup_id');
           $records = $this->db->get(db_prefix() . 'items')->result();
            foreach($records as $row ){
              $response[] = array("units"=>$row->unit,"sub_group_name"=>$row->name,"main_group_name"=>$row->main_group_id,"value"=>$row->item_code,"label"=>$row->description);
            }
        }
         return $response;
    }
    
    public function load_data_for_bom($status)
    {  
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '';
        if($status == "Y"){
            $sql1 .= db_prefix().'recipe.status = "Y" AND ';
        }else if($status == "N"){
            $sql1 .= db_prefix().'recipe.status = "N" AND ';
        }
        $sql1 .= db_prefix().'recipe.FY = "'.$fy.'" AND '.db_prefix().'recipe.PlantID = "'.$selected_company.'" AND BOMID IS NOT NULL ORDER BY item_code ASC';
        
        $sql ='SELECT '.db_prefix().'recipe.* 
        FROM '.db_prefix().'recipe WHERE '.$sql1;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
	
  
    function getitem_subgroup($postData,$ProdId){
         $response = array();
         $selected_company = $this->session->userdata('root_company');
         if(isset($postData['search']) ){
           // Select record
           $q = $postData['search'];
           $where_items = '(item_name LIKE "%' . $q . '%" ESCAPE \'!\' OR item_id LIKE "%' . $q. '%")';
           $this->db->select('*');
           $this->db->where($where_items);
    	   $this->db->where('production_id', $ProdId);
           $records = $this->db->get(db_prefix() . 'production_details')->result();
           foreach($records as $row ){
              $response[] = array("value"=>$row->item_id,"label"=>$row->item_name,"unit"=>$row->unit);
           }
         }
         return $response;
    }
  
  
  
  function getitem_subgroup1($postData,$ProdId){
     $response = array();
     if(isset($postData['search']) ){
       // Select record
       $this->db->select('*');
       $this->db->where("item_id like '%".$postData['search']."%' ");
	   $this->db->where('production_id', $ProdId);
      $records = $this->db->get(db_prefix() . 'production_details')->result();
	  //$aa = $this->db->last_query(); print_r($aa); exit(); 
       foreach($records as $row ){
          $response[] = array("value"=>$row->item_id,"label"=>$row->item_name,"req_qty"=>$row->req_qty,"pro_req_qty"=>$row->production_req_qty,"unit"=>$row->unit);
       } 
     } 
     return $response;
  }
  
  function get_item_details($ProdId,$ItemID){
    $selected_company = $this->session->userdata('root_company');
    $FY = $this->session->userdata('finacial_year');
       $this->db->select('*');
       $this->db->where('PlantID', $selected_company);
       $this->db->LIKE('FY', $FY);
       $this->db->where('production_id', $ProdId);
	   $this->db->where('item_id', $ItemID);
       $records = $this->db->get(db_prefix() . 'production_details')->row();
	  if($records){
	     $Stocks = $this->GetItemStockDetails($ItemID); 
	    $PQty = 0;
        $PRQty = 0;
        $IQty = 0;
        $PRDQty = 0;
        $SQty = 0;
        $SRTQty = 0;
        $AQty = 0;
        $GIQty = 0;
        $GOQty = 0;
        
        foreach ($Stocks as $stock) {
            $OQty = $stock['OQty'];
            if($stock['TType'] == 'P'){
                $PQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'N'){
                $PRQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'A'){
                $IQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'B'){
                $PRDQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
                $SQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
                $SRTQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
                $AQty += $stock['BilledQty'];
            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free distribution'){
                $AQty += $stock['BilledQty'];
            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged'){
                $AQty += $stock['BilledQty'];
            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
                $AQty += $stock['BilledQty'];
            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
                $AQty += $stock['BilledQty'];
            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
                $GIQty = $stock['BilledQty'];
            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
                $GOQty = $stock['BilledQty'];
            }
        }
        $stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
	     $records->ItemStocks = $stockQty;
	  }
     return $records;
  }
  
  function get_recipe_details($recipeID){
    $selected_company = $this->session->userdata('root_company');
    $FY = $this->session->userdata('finacial_year');
    
       $this->db->select('*');
       $this->db->where('PlantID', $selected_company);
       //$this->db->LIKE('FY', $FY);
	   $this->db->where('item_code', $recipeID);
	   $this->db->where('status', 'Y');
      $records = $this->db->get(db_prefix() . 'recipe')->row();
	  
     return $records;
  }
  function get_recipename($postData){
     $response = array();
     if(isset($postData['search']) ){
     
	   $q = $postData['search'];
       $this->db->select(db_prefix() . 'recipe.*');
       $where_items .= '(item_description LIKE "%' . $q . '%" ESCAPE \'!\' OR item_code LIKE "%' . $q. '%")';
       $selected_company = $this->session->userdata('root_company');
	   $this->db->where($where_items);
       $this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'recipe.status', 'Y');
       $records = $this->db->get(db_prefix() . 'recipe')->result();
	  // $aa = $this->db->last_query(); print_r($aa); exit(); 

       foreach($records as $row ){
		   // print_r($row); exit();
          $response[] = array("value"=>$row->item_code,"quantity"=>$row->qty,"units"=>$row->unit,"label"=>$row->item_description);
       }
     }
     return $response;
  }
  
  public function load_data_for_production($data)
     {  
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $status_list = $data["status_list"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
        if($status_list == "all"){
            
        }else{
            $sql1 .= ' AND '.db_prefix().'production.production_status = "'.$status_list.'"';
        }
        
        $sql1 .= '  ORDER BY Transdate ASC';
        
        $sql ='SELECT '.db_prefix().'production.*,  tblitems.description,
        (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'production.contractor_name AND '.db_prefix().'clients.PlantID = '.$selected_company.') as conName, 
        (SELECT GROUP_CONCAT(firstname SEPARATOR ",") FROM '.db_prefix().'staff WHERE '.db_prefix().'staff.AccountID = '.db_prefix().'production.manager_name ) as firstname,
        (SELECT GROUP_CONCAT(lastname SEPARATOR ",") FROM '.db_prefix().'staff WHERE '.db_prefix().'staff.AccountID = '.db_prefix().'production.manager_name ) as lastname
        FROM '.db_prefix().'production 
        LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
        WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
   
  
  public function get_managername($id = '')
    {
        $selected_company = $this->session->userdata('root_company');
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
       // $this->db->where('SubActGroupID', '10022004');
        $this->db->where('PlantID', $selected_company);
        //$this->db->where('tblstaff.staff_comp REGEXP',$regExp);
        $this->db->order_by('firstname', 'ASC');
        $accounts = $this->db->get(db_prefix() . 'staff')->result_array();
        return $accounts;
    }
	
	public function get_contractorname($id = '')
    {
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->where('SubActGroupID', '10022003');
        $this->db->where('PlantID', $selected_company);
        $this->db->order_by('company', 'ASC');
        $accounts = $this->db->get(db_prefix() . 'clients')->result_array();
        return $accounts;
    }
    
    public function GetLastPrdDate()
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        if($selected_company == "1"){
            $this->db->select('*');
            $this->db->where(db_prefix() . 'production.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'production.FY', $fy);
            $this->db->from(db_prefix() . 'production');
            $this->db->order_by(db_prefix() . 'production.TransDate', 'DESC');
            $PRDOrder = $this->db->get()->row();
            $LastPRDORDDate = substr($PRDOrder->TransDate,0,10);
        }else{
            $LastPRDORDDate = date('Y-m-d');
        }
        return $LastPRDORDDate;
    }
    
    public function GetGodownData()
    {
        $PlantID = $this->session->userdata('root_company');
        $this->db->where('PlantID', $PlantID);
        $this->db->order_by(db_prefix() . 'godownmaster.Type,'.db_prefix() . 'godownmaster.AccountName', 'ASC');
       return $this->db->get(db_prefix().'godownmaster')->result_array();
    }
	
	public function getbyitemname($recipeID,$GodownID)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        /*if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }*/
        
		$this->db->select(db_prefix() . 'recipe.*,'.db_prefix() . 'recipe_details.*,'.db_prefix() . 'stockmaster.OQty');
		$this->db->from(db_prefix() . 'recipe');
		$this->db->join(db_prefix() . 'recipe_details', db_prefix() . 'recipe.id = '.db_prefix() . 'recipe_details.rec_id');
		$this->db->join(db_prefix() . 'stockmaster', db_prefix() . 'stockmaster.ItemID = '.db_prefix() . 'recipe_details.item_id AND '.db_prefix() . 'stockmaster.PlantID = "'.$selected_company.'" AND '.db_prefix() . 'stockmaster.FY = "'.$fy.'" AND '.db_prefix() . 'stockmaster.GodownID = "'.$GodownID.'"','LEFT');
		$this->db->where(db_prefix() . 'recipe.item_code', $recipeID);
		$this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
		$this->db->where(db_prefix() . 'recipe_details.PlantID', $selected_company);
		//$this->db->where(db_prefix() . 'stockmaster.GodownID',$GodownID);
		$this->db->where(db_prefix() . 'recipe.status', 'Y');
		return $this->db->get()->result_array();
    }  
    
    public function GetItemStock($GodownID)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        
        $this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
        $this->db->from(db_prefix() .'history');
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() .'history.FY', $fy);
        $this->db->where(db_prefix() .'history.GodownID', $GodownID);
        $this->db->group_by('ItemID,TType,TType2');
        return $this->db->get()->result_array();
    }
    
    public function GetPRDItemStock($pro_orid,$GodownID)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select(db_prefix() .'history.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty,'.db_prefix() .'stockmaster.OQty');
        $this->db->from(db_prefix() .'history');
        $this->db->join('tblstockmaster', 'tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY AND tblstockmaster.GodownID = "'.$GodownID.'"','LEFT');
        $this->db->join('tblproduction_details', 'tblproduction_details.item_id = tblhistory.ItemID AND tblproduction_details.PlantID = tblhistory.PlantID AND tblproduction_details.FY = tblhistory.FY','LEFT');
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        $this->db->where(db_prefix() .'production_details.production_id', $pro_orid);
        $this->db->where(db_prefix() .'history.GodownID', $GodownID);
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() .'history.FY', $fy);
        $this->db->group_by(db_prefix() .'history.ItemID,TType,TType2');
        $result = $this->db->get()->result_array();
            return $result;
    }
    
    public function GetPRDItemStockNew($PrdItem,$GodownID)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select(db_prefix() .'history.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
        $this->db->from(db_prefix() .'history');
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() .'history.FY', $fy);
        $this->db->where(db_prefix() .'history.GodownID', $GodownID);
        $this->db->where_in(db_prefix() .'history.ItemID', $PrdItem);
        $this->db->group_by(db_prefix() .'history.ItemID,TType,TType2');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function GetPRDItemOQty($pro_orid,$GodownID)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        /*if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }*/
        $this->db->select(db_prefix() .'production_details.item_id');
        $this->db->from(db_prefix() .'production_details');
        $this->db->where(db_prefix() .'production_details.PlantID', $selected_company);
        $this->db->where(db_prefix() .'production_details.production_id', $pro_orid);
        $this->db->where(db_prefix() .'production_details.FY', $fy);
        $ItemList = $this->db->get()->result_array();
        $ItemIDs = array();
        foreach ($ItemList as $ItemID) {
            array_push($ItemIDs,$ItemID['item_id']);
        }
        $this->db->select(db_prefix() .'stockmaster.ItemID,'.db_prefix() .'stockmaster.OQty');
        $this->db->from(db_prefix() .'stockmaster');
        $this->db->where(db_prefix() .'stockmaster.PlantID', $selected_company);
        $this->db->where_in(db_prefix() .'stockmaster.ItemID', $ItemIDs);
        $this->db->where(db_prefix() .'stockmaster.FY', $fy);
        $this->db->where(db_prefix() .'stockmaster.GodownID',$GodownID);
        $this->db->where(db_prefix() .'stockmaster.cnfid', 1);
        return $this->db->get()->result_array();
    }
    
    public function GetItemStockDetails($ItemID)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
        $this->db->select('tblhistory.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty,tblstockmaster.OQty');
        $this->db->from(db_prefix() .'history');
        $this->db->join('tblstockmaster', 'tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY ','LEFT');
        $this->db->where(db_prefix() .'history.ItemID', $ItemID);
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() .'history.FY', $fy);
        $this->db->where(db_prefix() .'history.GodownID',$GodownID);
        $this->db->where(db_prefix() .'stockmaster.GodownID',$GodownID);
        $this->db->group_by('ItemID,TType,TType2');
        return $this->db->get()->result_array();
    }
    
    public function getReceipeDetailswithPODetails($recipeID,$PONumber)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
            
		$this->db->select('recipe_details.item_id,recipe_details.item_name,production_details.req_qty AS StdQty,production_details.return_req_qty AS RtnQty,production_details.ExtraQty AS ExtraQty,production_details.unit');
		$this->db->from('recipe');
		$this->db->join('recipe_details', 'recipe.id = recipe_details.rec_id');
		$this->db->join('production_details', 'recipe_details.item_id = production_details.item_id AND production_details.production_id = "'.$PONumber.'" AND production_details.FY="'.$fy.'" AND production_details.PlantID="'.$selected_company.'"');
		$this->db->where(db_prefix() . 'recipe.item_code', $recipeID);
		return $this->db->get()->result_array();
    } 

	public function get_production_order($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select(db_prefix() . 'production.*,'.db_prefix() . 'production.TransDate AS Date,'.db_prefix() . 'items.description,'.db_prefix() . 'items.case_qty');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where(db_prefix() . 'production.pro_order_id', $pro_orid);
			$this->db->where(db_prefix() . 'production.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production.FY', $fy); 
			$pro_order =  $this->db->get('production')->row();
			if($pro_order){
			    
			    $item          = $this->get_production_item($pro_order->pro_order_id);
                $pro_order->items = $item;
                $ItemRate = $this->GetItemRate($pro_order->recipeID);
		        $pro_order->ItemRate = $ItemRate;
			}
			return $pro_order;
	}
	
	public function GetPrdDetails($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select(db_prefix() . 'production.*,'.db_prefix() . 'production.TransDate AS Date,tblitems.description,tblitems.case_qty,
			tblrecipe.BOMID,tblrecipe.FG_Godown,tblrecipe.RM_Godown,tblrecipe.Scrap_Godown,tblrecipe.cost_allocation,tblrecipe.conv_cost,tblrecipe.st_cost,tblrecipe.frt_cost,tblrecipe.mrkt_cost');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->join(db_prefix() . 'recipe', db_prefix() . 'recipe.BOMID = '.db_prefix() . 'production.BOMID AND '.db_prefix() . 'recipe.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where(db_prefix() . 'production.pro_order_id', $pro_orid);
			$this->db->where(db_prefix() . 'production.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production.FY', $fy); 
			$pro_order =  $this->db->get('production')->row();
			if($pro_order){
			    $item          = $this->GetPrdItems($pro_order->pro_order_id);
			    $BOMDetails         = $this->GetBOMItems($pro_order->BOMID);
                $pro_order->items = $item;
                $pro_order->BOMDetails = $BOMDetails;
			}
			return $pro_order;
	}
	public function get_PRD_Details($pro_orid)
	{
		$selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
		$this->db->select('*');
		$this->db->where(db_prefix() . 'history.OrderID', $pro_orid);
		$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
		$this->db->where(db_prefix() . 'history.FY', $fy); 
		$this->db->where(db_prefix() . 'history.TType', 'B'); 
		$PRDDetails =  $this->db->get('history')->row();
		
		return $PRDDetails;
	}
	
	public function GetItemRate($ItemID)
	{
		$selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        if($selected_company == "1"){
            $CustType = '1';
        }else if($selected_company == "2"){
            $CustType = '13';
        }else if($selected_company == "3"){
            $CustType = '21';
        }
        
		$this->db->select(db_prefix() . 'rate_master.assigned_rate AS BasicRate,'.db_prefix() . 'rate_master.SaleRate AS SaleRate');
		$this->db->where(db_prefix() . 'rate_master.item_id', $ItemID);
		$this->db->where(db_prefix() . 'rate_master.PlantID', $selected_company); 
		$this->db->where(db_prefix() . 'rate_master.state_id', 'UP'); 
		$this->db->where(db_prefix() . 'rate_master.distributor_id', $CustType); 
		$ItemRateDetails =  $this->db->get('tblrate_master')->row();
		if(empty($ItemRateDetails)){
		    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate');
    		$this->db->where(db_prefix() . 'history.ItemID', $ItemID);
    		$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    		//$this->db->where(db_prefix() . 'history.TType', 'O'); 
    		$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    		$ItemRateDetails =  $this->db->get('history')->row();
		}
		return $ItemRateDetails;
	}
	
	public function get_PRDIssue_Details($pro_orid)
	{
		$selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
		$this->db->select('*');
		$this->db->where(db_prefix() . 'history.OrderID', $pro_orid);
		$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
		$this->db->where(db_prefix() . 'history.FY', $fy); 
		$this->db->where(db_prefix() . 'history.TType', 'A'); 
		$PRDIssueDetails =  $this->db->get('history')->result_array();
		return $PRDIssueDetails;
	}
	
	public function get_receipeDetails($receipeID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select('*');
			$this->db->where(db_prefix() . 'recipe.item_code', $receipeID);
			$ReceipeDetails =  $this->db->get('recipe')->row();
			return $ReceipeDetails;
		}
		
		public function get_stock_details($ItemID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
			$this->db->select('*');
			$this->db->where(db_prefix() . 'stockmaster.ItemID', $ItemID);
			$this->db->where(db_prefix() . 'stockmaster.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'stockmaster.FY', $fy); 
			$this->db->where(db_prefix() . 'stockmaster.GodownID',$GodownID);
			$stock =  $this->db->get(db_prefix() . 'stockmaster')->row();
			return $stock;
		
		}
		
		public function GetPrdItemStockList($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
                $GodownID = 'CSPL';
            }else if($selected_company == "2"){
                $GodownID = 'CFF';
            }else if($selected_company == "3"){
                $GodownID = 'CBUPL';
            }
            
			$this->db->select('*');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->join(db_prefix() . 'stockmaster', db_prefix() . 'stockmaster.ItemID = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'stockmaster.PlantID = '.db_prefix() . 'production_details.PlantID AND '.db_prefix() . 'stockmaster.FY = '.db_prefix() . 'production_details.FY AND '.db_prefix() . 'stockmaster.GodownID ="'.$GodownID.'"','LEFT');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$ItemStock =  $this->db->get()->result_array();
			$i = 0;
			foreach($ItemStock as $row ){
			    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate,'.db_prefix() . 'history.CaseQty AS CaseQty');
    			$this->db->where(db_prefix() . 'history.ItemID', $row['item_id']);
    			$this->db->where(db_prefix() . 'history.TType', 'P');
    			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    			$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
    			$ItemStock[$i]['BasicRate'] = $PrdItemDetails->BasicRate;
    			$ItemStock[$i]['SaleRate'] = $PrdItemDetails->SaleRate;
    			$ItemStock[$i]['CaseQty'] = $PrdItemDetails->CaseQty;
    			$i++;
			}
			return $ItemStock;
		
		}
		
		public function GetRowMaterialOthDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'production_details.item_id');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$ItemStock =  $this->db->get()->result_array();
			$i = 0;
			foreach($ItemStock as $row ){
			   
			    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate,'.db_prefix() . 'history.CaseQty AS CaseQty');
    			$this->db->where(db_prefix() . 'history.ItemID', $row['item_id']);
    			$this->db->where(db_prefix() . 'history.TType', 'P');
    			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    			$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
    			if($PrdItemDetails->BasicRate == null || $PrdItemDetails->BasicRate == ''){
    			     $ItemStock[$i]['BasicRate'] = 0.00;
    			}else{
    			    $ItemStock[$i]['BasicRate'] = $PrdItemDetails->BasicRate;
    			}
    			if($PrdItemDetails->SaleRate == null || $PrdItemDetails->SaleRate == ''){
    			     $ItemStock[$i]['SaleRate'] = 0.00;
    			}else{
    			    $ItemStock[$i]['SaleRate'] = $PrdItemDetails->SaleRate;
    			}
    			if($PrdItemDetails->CaseQty == null || $PrdItemDetails->CaseQty == ''){
    			     $ItemStock[$i]['CaseQty'] = 1;
    			}else{
    			    $ItemStock[$i]['CaseQty'] = $PrdItemDetails->CaseQty;
    			}
    			
    			$i++;
			}
			return $ItemStock;
		
		}
		public function GetIssueDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select(db_prefix() . 'history.ItemID');
			$this->db->from(db_prefix() . 'history');
			$this->db->where(db_prefix() . 'history.OrderID', $proId);
			$this->db->where(db_prefix() . 'history.TType', "A");
			$this->db->where(db_prefix() . 'history.TType2', "Issue");
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'history.FY', $fy); 
			$IssueItem =  $this->db->get()->result_array();
			return $IssueItem;
		}
		
		public function GetFGDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select(db_prefix() . 'history.ItemID');
			$this->db->from(db_prefix() . 'history');
			$this->db->where(db_prefix() . 'history.OrderID', $proId);
			$this->db->where(db_prefix() . 'history.TType', "B");
			$this->db->where(db_prefix() . 'history.TType2', "Production");
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'history.FY', $fy); 
			$FGItem =  $this->db->get()->result_array();
			return $FGItem;
		}
		
		
		public function GetStockDetailsForRM($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select('*');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->join(db_prefix() . 'stockmaster', db_prefix() . 'stockmaster.ItemID = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'stockmaster.PlantID = '.db_prefix() . 'production_details.PlantID AND '.db_prefix() . 'stockmaster.FY = '.db_prefix() . 'production_details.FY AND '.db_prefix() . 'stockmaster.GodownID = '.db_prefix() . 'production_details.GodownID','LEFT');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$ItemStock =  $this->db->get()->result_array();
			return $ItemStock;
		}
		
		public function GetRowMaterialDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select('*');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$RMDetails =  $this->db->get()->result_array();
			return $RMDetails;
		}
		public function GetPrdFGDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'production.*,'.db_prefix() . 'items.case_qty');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where(db_prefix() . 'production.pro_order_id', $proId);
			$this->db->where(db_prefix() . 'production.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production.FY', $fy); 
			$PrdDetails =  $this->db->get(db_prefix() . 'production')->row();
			if($PrdDetails){
			    $ItemRate = $this->GetItemRate($PrdDetails->recipeID);
		        $PrdDetails->ItemRate = $ItemRate;
			}
			return $PrdDetails;
		
		}
		
		public function get_prd_item_rtnQty($PrdID, $ItemID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select('*');
			$this->db->where(db_prefix() . 'production_details.item_id', $ItemID);
			$this->db->LIKE(db_prefix() . 'production_details.production_id', $PrdID);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->LIKE(db_prefix() . 'production_details.FY', $fy); 
			$PrdItemDetails =  $this->db->get(db_prefix() . 'production_details')->row();
			
			return $PrdItemDetails;
		
		}
		
		public function GetBOMItems($BOMID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('*');
			$this->db->from('tblrecipe_details');
			$this->db->where(db_prefix() . 'recipe_details.BOMID', $BOMID);
			$PRDItems = $this->db->get()->result_array();
			return $PRDItems;
		}
		public function GetPrdItems($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('*');
			$this->db->from('production_details');
			$this->db->where(db_prefix() . 'production_details.production_id', $pro_orid);
			$PRDItems = $this->db->get()->result_array();
			return $PRDItems;
		}
		
		public function get_production_item($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('*');
			$this->db->from('production_details');
			$this->db->where(db_prefix() . 'production_details.production_id', $pro_orid);
			//return $this->db->get()->result_array();
			$PRDItems = $this->db->get()->result_array();
			$i = 0;
			foreach($PRDItems as $row ){
			    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate,'.db_prefix() . 'history.CaseQty AS CaseQty');
    			$this->db->where(db_prefix() . 'history.ItemID', $row['item_id']);
    			$this->db->where(db_prefix() . 'history.TType', 'P');
    			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    			$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
    			$PRDItems[$i]['BasicRate'] = $PrdItemDetails->BasicRate;
    			$PRDItems[$i]['SaleRate'] = $PrdItemDetails->SaleRate;
    			$PRDItems[$i]['CaseQty'] = $PrdItemDetails->CaseQty;
    			$i++;
			}
			return $PRDItems;
		}
		
		public function get_PRD_DetailsFromHistory($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('*');
			$this->db->from(db_prefix() . 'history');
			$this->db->where(db_prefix() . 'history.OrderID', $pro_orid);
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.FY', $fy);
			return $this->db->get()->result_array();
		}
		
		public function edit_recipe($id)
		{		
			$this->db->select('*');
			$this->db->from('tblrecipe');
			//$this->db->join('recipe_details', 'recipe.id = recipe_details.rec_id');
			$this->db->where(db_prefix() . 'recipe.id', $id);
			return $this->db->get()->result_array();
			//$aa = $this->db->last_query(); print_r($aa); exit();
		}
	public function load_data_for_recipe($status)
    {  
        
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        //$sql1 = db_prefix().'recipe.FY = "'.$fy.'" AND '.db_prefix().'recipe.PlantID = "'.$selected_company.'" ORDER BY item_code ASC';
        if($status == "Y"){
            $sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" AND '.db_prefix().'recipe.status = "Y" ORDER BY item_code ASC';
        }else if($status == "N"){
            $sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" AND '.db_prefix().'recipe.status = "N" ORDER BY item_code ASC';
        }else if($status == "YN"){
            $sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" ORDER BY item_code ASC';
        }else{
            $sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" AND '.db_prefix().'recipe.status = "Y" ORDER BY item_code ASC';
        }
        
        $sql ='SELECT '.db_prefix().'recipe.* 
        FROM '.db_prefix().'recipe WHERE '.$sql1;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
		
	public function edit_recipe1($id)
	{		
		$this->db->select('*');
		$this->db->from('tblrecipe_details');
		//$this->db->join('recipe_details', 'recipe.id = recipe_details.rec_id');
		$this->db->where(db_prefix() . 'recipe_details.rec_id', $id);
		return $this->db->get()->result_array();
		//$aa = $this->db->last_query(); print_r($aa); exit();
	}
	
	public function GetBOMList()
    {
        $PlantID = $this->session->userdata('root_company');
        $this->db->select('BOMID,item_description');
        $this->db->where('PlantID', $PlantID);
        $this->db->where('BOMID IS NOT NULL');
        $this->db->order_by(db_prefix() . 'recipe.BOMID', 'ASC');
       return $this->db->get(db_prefix().'recipe')->result_array();
    }
	public function get_bom_fm_details($BOMID)
	{		
		$this->db->select('tblrecipe.*,tblitems_sub_groups.name AS SubGroupName');
		$this->db->from('tblrecipe');
		$this->db->join('tblitems', 'tblitems.item_code = tblrecipe.item_code ');
		$this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.subgroup_id');
		$this->db->where(db_prefix() . 'recipe.BOMID', $BOMID);
		return $this->db->get()->row();
	}
	public function GetBom_details($id)
	{		
		$this->db->select('tblrecipe.*,tblitems_sub_groups.name AS SubGroupName');
		$this->db->from('tblrecipe');
		$this->db->join('tblitems', 'tblitems.item_code = tblrecipe.item_code ');
		$this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.subgroup_id');
		$this->db->where(db_prefix() . 'recipe.id', $id);
		return $this->db->get()->row();
	}
	
	public function GetBOMRMDetails($id)
	{		
		$this->db->select('tblrecipe_details.*');
		$this->db->from('tblrecipe_details');
		$this->db->where(db_prefix() . 'recipe_details.rec_id', $id);
		return $this->db->get()->result_array();
	}
	public function get_bom_rm_details($BOMID)
	{		
		$this->db->select('tblrecipe_details.*');
		$this->db->from('tblrecipe_details');
		$this->db->where(db_prefix() . 'recipe_details.BOMID', $BOMID);
		return $this->db->get()->result_array();
	}
	
	public function Pro_order_list(){
		$fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
		$this->db->select('*');
	    $this->db->from('tblproduction');
		$this->db->where('FY', $fy);
		$this->db->where('PlantID', $selected_company);
		$this->db->order_by('pro_order_id', 'DESC');
		return $this->db->get()->result_array();
	}
	
	public function PRDList(){
		$fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
		$this->db->select('tblproduction.*,tblitems.description');
	    $this->db->from('tblproduction');
	    $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
		$this->db->where('tblproduction.FY', $fy);
		$this->db->where('tblproduction.PlantID', $selected_company);
		$this->db->order_by('pro_order_id', 'DESC');
		return $this->db->get()->result_array();
	}
	
	// Production Details Data By ID
     public function getPRDDetailsByID($PRDID)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select(db_prefix() . 'production.*');
        $this->db->from(db_prefix() . 'production');
        /*$this->db->where('tblproduction.FY', $fy);
        $this->db->where('tblproduction.PlantID', $selected_company);*/
        $this->db->where(db_prefix() . 'production.pro_order_id', $PRDID);
        return $this->db->get()->row();
    }
	public function Pro_order_report($data)
	{
		$fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
	    $this->db->select('tblproduction.*,tblitems.description');
		$this->db->from('tblproduction');
		$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
		
		$this->db->where('tblproduction.pro_order_id', $data['pro_order_id']);
		$this->db->where('tblproduction.FY', $fy);
		$this->db->where('tblproduction.PlantID', $selected_company);
		return $this->db->get()->row_array();
	}
	public function pro_order_report_details($data)
	{
	    $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
	    $this->db->select('*');
		$this->db->from('tblproduction');
		$this->db->join('tblproduction_details', 'tblproduction.pro_order_id = tblproduction_details.production_id AND tblproduction.FY = tblproduction_details.FY AND tblproduction.PlantID = tblproduction_details.PlantID','left');
		$this->db->where('tblproduction.pro_order_id', $data['pro_order_id']);
		$this->db->where('tblproduction.FY', $fy);
		$this->db->where('tblproduction.PlantID', $selected_company);
		$this->db->order_by('tblproduction_details.item_name', 'ASC');
		return $this->db->get()->result_array();
	}
	
	public function get_bom_details($BOMID)
	{
	    $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
	    $this->db->select('*');
		$this->db->from('tblrecipe');
		$this->db->where('tblrecipe.BOMID', $BOMID);
		return $this->db->get()->row();
	}
	
	public function get_unit_sum($data)
	{
	    $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
	    $this->db->select('unit,SUM(production_req_qty) AS PrdQty,SUM(return_req_qty) AS rtnQty,SUM(ExtraQty) AS ExtQty');
		$this->db->from('tblproduction_details');
		$this->db->where('tblproduction_details.production_id', $data['pro_order_id']);
		$this->db->where('tblproduction_details.FY', $fy);
		$this->db->where('tblproduction_details.PlantID', $selected_company);
		$this->db->where('tblproduction_details.item_id !=',"SCRAP");
		$this->db->group_by('tblproduction_details.unit');
		return $this->db->get()->result_array();
	}
	function getproduct_list($postData){

    $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_items = '';
     $fy = $this->session->userdata('finacial_year');
     if(isset($postData['search']) ){
        $q = $postData['search'];
       // Select record
       $this->db->select('*');
      // $this->db->where("recipeID like '%".$postData['search']."%' ");
       $where_items .= '('.db_prefix() . 'items.description LIKE "%' . $q . '%" ESCAPE \'!\' OR recipeID LIKE "%' . $q. '%")';
       $this->db->where('tblproduction.FY', $fy);
       
	   $this->db->where('tblproduction.PlantID', $selected_company);
       $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID','left');
	   $this->db->where($where_items);
	   $this->db->group_by('recipeID');
       $records = $this->db->get(db_prefix() . 'production')->result();
       foreach($records as $row ){
          $response[] = array("value"=>$row->recipeID,"label"=>$row->description,);
       } 
     } 

     return $response;
  }
    public function pro_cost_report($data)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $from_date = to_sql_date($data['from_date']);
        $to_date = to_sql_date($data['to_date']);
		    $this->db->select('SUM(tblproduction.batch_qty) as batch_qty,SUM(tblproduction.Finish_good_qty) as Finish_good_qty, SUM(tblproduction.Finish_good_qty_new) as Finish_good_qty_new, 
		    tblproduction.recipeID,tblitems.description,tblproduction.finish_good_unit AS FG_Unit');
			$this->db->from('tblproduction');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			if($data['product_name'] !=""){
			    $this->db->where('tblproduction.recipeID', $data['product_name']);
			}
			//$this->db->where('production_status','Completed');
			$status = array("WELDING","ASSEMBLY","PAINTING","MOVEMENT FOR GODOWN");
			$this->db->where_in('production_status',$status);
			$this->db->where(db_prefix().'production.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->group_by('recipeID');
			$result = $this->db->get()->result_array();
			
        foreach($result as $key=>$value)
        {
            $this->db->select('tblhistory.SaleRate,tblhistory.BasicRate,tblhistory.cgst,tblhistory.sgst,tblhistory.igst');
            $this->db->from('tblhistory');
            
            $this->db->where('tblhistory.ItemID', $value['recipeID']);
            $this->db->where('tblhistory.FY', $fy);
            $this->db->where('tblhistory.PlantID', $selected_company);
            $this->db->where('tblhistory.TType', 'O');
            $this->db->where('tblhistory.TType2', 'Order');
            $this->db->where(db_prefix().'history.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
            
            $this->db->order_by('id','desc');
            $this->db->limit(1);
            $data_row =  $this->db->get()->row_array();
            if($data_row != ''){
                $result[$key]['SaleRate'] = $data_row['SaleRate'];
                $result[$key]['BasicRate'] = $data_row['BasicRate'];
                if($data_row['cgst'] == null &&  $data_row['sgst']== null){
                    $result[$key]['gst'] = $data_row['cgst'] + $data_row['sgst'] + $data_row['igst'];
                }else{
                    $result[$key]['gst'] = $data_row['cgst'] + $data_row['sgst'] + $data_row['igst'];
                }
            }else{
                $result[$key]['SaleRate'] = '';
                $result[$key]['BasicRate'] = '';
                $result[$key]['gst'] = '';
            }
        }
        return $result;
    }
    
    public function prd_cost_calculate($data)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $from_date = to_sql_date($data['from_date']);
        $to_date = to_sql_date($data['to_date']);
		    $this->db->select('tblproduction.pro_order_id,tblproduction.BOMID,tblrecipe.conv_cost,tblrecipe.st_cost,tblrecipe.frt_cost,tblrecipe.mrkt_cost');
			$this->db->from('tblproduction');
			$this->db->join(db_prefix() . 'recipe', db_prefix() . 'recipe.BOMID = '.db_prefix() . 'production.BOMID ');
			if($data['product_name'] !=""){
			    $this->db->where('tblproduction.recipeID', $data['product_name']);
			}
			$status = array("WELDING","ASSEMBLY","PAINTING","MOVEMENT FOR GODOWN");
			$this->db->where_in('production_status',$status);
			$this->db->where(db_prefix().'production.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			$result = $this->db->get()->result_array();
		
        return $result;
    }
    public function pro_cost_report_details($data)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $from_date = to_sql_date($data['from_date']);
        $to_date = to_sql_date($data['to_date']);
        $this->db->select('tblproduction.pro_order_id');
        $this->db->from('tblproduction');
        if($data['product_name'] !=""){
            $this->db->where('tblproduction.recipeID', $data['product_name']);
        }
        $status = array("WELDING","ASSEMBLY","PAINTING","MOVEMENT FOR GODOWN");
        $this->db->where_in('production_status',$status);
        $this->db->where(db_prefix().'production.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
        $this->db->where('tblproduction.FY', $fy);
        $this->db->where('tblproduction.PlantID', $selected_company);
        $result =  $this->db->get()->result_array();
        $pro_order_id = array();
        foreach($result as $value){
            if($value["pro_order_id"] != ''){
                array_push($pro_order_id, "'".$value["pro_order_id"]."'");
            }
        }
        $pro_order_id_data = implode(", ", $pro_order_id);
        if($pro_order_id_data ==''){
            return true;
        }
        $this->db->select('tblproduction_details.item_name,tblproduction_details.item_id,SUM(tblproduction_details.production_req_qty) as production_req_qty,SUM(tblproduction_details.return_req_qty) as return_req_qty,SUM(tblproduction_details.ExtraQty) as ExtraQty,'.db_prefix() .'taxes.taxrate,tblproduction_details.unit AS RMUnit');
        $this->db->from('tblproduction_details');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production_details.PlantID');
        $this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = '.db_prefix() . 'items.tax ');
        
        $this->db->where_in('tblproduction_details.production_id', $pro_order_id_data,FALSE);
        $this->db->where('tblproduction_details.FY', $fy);
        $this->db->where('tblproduction_details.PlantID', $selected_company);
        $this->db->group_by('item_id');
        $data =  $this->db->get()->result_array();
        $last_fy = $fy-1; 
        
        $from_date = '20'.$last_fy.'-04-01';
        foreach($data as $key=>$value)
        {
            $this->db->select('tblhistory.SaleRate');
            $this->db->from('tblhistory');
            $this->db->where('tblhistory.ItemID', $value['item_id']);
            $this->db->where('tblhistory.PlantID', $selected_company);
            $this->db->where('tblhistory.TType', 'P');
            $this->db->where('tblhistory.TType2', 'Purchase');
            $this->db->where(db_prefix().'history.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
            
            $this->db->order_by('TransDate','desc');
            $this->db->limit(1);
            $data_row =  $this->db->get()->row_array();
            if($data_row != ''){
                $data[$key]['BasicRate'] = $data_row['SaleRate'];
            }else{
                $data[$key]['BasicRate'] = '';
            }
        }
        return $data;
    }
		
}