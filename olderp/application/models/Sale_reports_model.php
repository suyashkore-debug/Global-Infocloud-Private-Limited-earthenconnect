<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sale_reports_model extends App_Model
{
    
    public function __construct()
    {
        parent::__construct(); 
    }

    public function get_company_detail()
     {  
         
        $selected_company = $this->session->userdata('root_company');
      
        
        $sql ='SELECT '.db_prefix().'rootcompany.*
        FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
        $result = $this->db->query($sql)->row();
        
        return $result;
     }
    public function GetGodownData()
    {
        $PlantID = $this->session->userdata('root_company');
        $this->db->where('PlantID', $PlantID);
        $this->db->order_by(db_prefix() . 'godownmaster.Type,'.db_prefix() . 'godownmaster.AccountID', 'ASC');
       return $this->db->get(db_prefix().'godownmaster')->result_array();
    }
    public function GetPlantDetails()
     {   
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $sql ='SELECT '.db_prefix().'setup.*
        FROM '.db_prefix().'setup WHERE PlantID = '.$selected_company.' AND FY = "'.$FY.'"';
        $result = $this->db->query($sql)->row();
        return $result;
    }
    // get Account List
    function AccountList($postData){
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $where_clients = '';
        if(isset($postData['search']) ){
           $q = $postData['search'];
           
           $this->db->select(db_prefix() . 'clients.*,' . db_prefix() . 'xx_citylist.city_name');
           $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\')  AND ' . db_prefix() . 'clients.SubActGroupID IN("60001004")';
           $this->db->join(db_prefix() . 'xx_citylist', '' . db_prefix() . 'xx_citylist.id = ' . db_prefix() . 'clients.city', 'left');
           $this->db->where($where_clients);
           $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
           $records = $this->db->get(db_prefix() . 'clients')->result();
           foreach($records as $row ){
              $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"address"=>$row->address,"Address3"=>$row->Address3,"StationName"=>$row->StationName,"state"=>$row->state,"CityName"=>$row->city_name);
           }
        }
        return $response;
    }
    
    // AccountList List
    public function AccountList_table(){
        $selected_company = $this->session->userdata('root_company');
          
        $this->db->select('tblclients.*');
        $this->db->where('tblclients.PlantID ', $selected_company);
        $this->db->where('tblclients.SubActGroupID ', '60001004');
        $this->db->order_by('tblclients.company','ASC');
        return $this->db->get('tblclients')->result_array();
    }
    
    // get Item List
    function ItemList($postData){
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $where_clients = '';
        if(isset($postData['search']) ){
           $q = $postData['search'];
           
           $this->db->select(db_prefix() . 'items.*');
           $where_clients .= '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q . '%" ESCAPE \'!\' )';
           $this->db->where($where_clients);
           $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
           $records = $this->db->get(db_prefix() . 'items')->result();
           foreach($records as $row ){
              $response[] = array("label"=>$row->description,"value"=>$row->item_code);
           }
        }
        return $response;
    }
    
    public function GetItemDetails($ItemID)
     {  
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'items.*
        FROM '.db_prefix().'items WHERE item_code = "'.$ItemID.'" AND PlantID = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function GetAccountDetails($AccountID)
     {  
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'clients.*,tblxx_citylist.city_name 
        FROM '.db_prefix().'clients 
        LEFT JOIN tblxx_citylist ON tblxx_citylist.id = tblclients.city  WHERE AccountID = "'.$AccountID.'" AND PlantID = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function GetItemRate($ItemID)
     {  
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == '1'){
            $distID = '1';
        }else if($selected_company == '2'){
            $distID = '13';
        }else if($selected_company == '3'){
            $distID = '21';
        }
        $sql ='SELECT '.db_prefix().'rate_master.SaleRate
        FROM '.db_prefix().'rate_master WHERE item_id = "'.$ItemID.'" AND state_id = "UP" AND distributor_id = "'.$distID.'" AND PlantID = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    // Item Wise Stock report
    public function GetItemWiseStockReport($filterdata)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        $from_date = to_sql_date($filterdata["from_date"]).' 00:00:00';
        $to_date = to_sql_date($filterdata["to_date"]).' 23:59:59';
        $ItemID = $filterdata["ItemID"];
        $GodownID = $filterdata["GodownID"];
        
        if($GodownID !==''){
            $sql = 'SELECT tblhistory.TransDate2, tblhistory.ItemID,tblhistory.TType,tblhistory.TType2,tblhistory.CaseQty,tblhistory.SaleRate,
            SUM(tblhistory.BilledQty) AS Qty,SUM(tblhistory.NetChallanAmt) AS AmtSum,tblitems.item_code,tblitems.description,tblstockmaster.OQty,tblhistory.SuppliedIn 
            FROM `tblhistory` 
            INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            INNER JOIN tblstockmaster ON tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY AND tblstockmaster.GodownID = tblhistory.GodownID
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'"  
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND 
            tblhistory.ItemID = "'.$ItemID.'" AND tblhistory.BillID IS NOT NULL  AND tblstockmaster.GodownID = "'.$GodownID.'" AND tblhistory.GodownID = "'.$GodownID.'" 
            GROUP BY tblhistory.TType,tblhistory.TType2, DATE(tblhistory.TransDate2)   
            ORDER BY tblhistory.TransDate2 ASC'; 
        }else{
            $sql = 'SELECT tblhistory.TransDate2, tblhistory.ItemID,tblhistory.TType,tblhistory.TType2,tblhistory.CaseQty,tblhistory.SaleRate,
            SUM(tblhistory.BilledQty) AS Qty,SUM(tblhistory.NetChallanAmt) AS AmtSum,tblitems.item_code,tblitems.description,tblhistory.SuppliedIn,
            (SELECT SUM(tblstockmaster.OQty) FROM tblstockmaster WHERE tblstockmaster.ItemID=tblhistory.ItemID AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" GROUP BY tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY) AS OQty
            FROM `tblhistory` 
            INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'"  
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND 
            tblhistory.ItemID = "'.$ItemID.'" AND tblhistory.BillID IS NOT NULL 
            GROUP BY tblhistory.TType,tblhistory.TType2, DATE(tblhistory.TransDate2)   
            ORDER BY tblhistory.TransDate2 ASC';
        }
        
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
     
    public function GetItemWiseStockReportOQty($filterdata)
     {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $from_date = to_sql_date($filterdata["from_date"]).' 00:00:00';
        $to_date = to_sql_date($filterdata["to_date"]).' 23:59:59';
        $ItemID = $filterdata["ItemID"];
        $GodownID = $filterdata["GodownID"];
        if($from_date == "2022-04-01"){
            $day_before = '2022-04-01 23:59:59';
        }else{
            $day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) ).' 23:59:59';
        }
        $first_date = '2022-04-01 00:00:00';
        
        if($GodownID !==''){
            $sql = 'SELECT tblhistory.TransDate2, tblhistory.ItemID,tblhistory.TType,tblhistory.TType2,tblhistory.CaseQty,
            SUM(tblhistory.BilledQty) AS Qty,tblitems.item_code,tblitems.description,tblstockmaster.OQty,tblhistory.SuppliedIn 
            FROM `tblhistory` 
            INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            INNER JOIN tblstockmaster ON tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY AND tblstockmaster.GodownID = tblhistory.GodownID
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'"  
            AND tblhistory.TransDate2 BETWEEN "'.$first_date.'" AND "'.$day_before.'" AND 
            tblhistory.ItemID = "'.$ItemID.'" AND tblhistory.BillID IS NOT NULL AND tblstockmaster.GodownID = "'.$GodownID.'" AND tblhistory.GodownID = "'.$GodownID.'"  
            GROUP BY tblhistory.TType,tblhistory.TType2, DATE(tblhistory.TransDate2)  
            ORDER BY tblhistory.TransDate2 ASC';
        }else{
            $sql = 'SELECT tblhistory.TransDate2, tblhistory.ItemID,tblhistory.TType,tblhistory.TType2,tblhistory.CaseQty,
            SUM(tblhistory.BilledQty) AS Qty,tblitems.item_code,tblitems.description,tblhistory.SuppliedIn,
            (SELECT SUM(tblstockmaster.OQty) FROM tblstockmaster WHERE tblstockmaster.ItemID=tblhistory.ItemID AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" GROUP BY tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY) AS OQty
            FROM `tblhistory` 
            INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'"  
            AND tblhistory.TransDate2 BETWEEN "'.$first_date.'" AND "'.$day_before.'" AND 
            tblhistory.ItemID = "'.$ItemID.'" AND tblhistory.BillID IS NOT NULL 
            GROUP BY tblhistory.TType,tblhistory.TType2, DATE(tblhistory.TransDate2)  
            ORDER BY tblhistory.TransDate2 ASC';
        }
        
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
     
    // Party Item Wise Report body data
    public function GetPartyItemWiseBodyData($filterdata)
     { 

        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $AccountID = $filterdata["AccountID"];
        $TransType = $filterdata["TransType"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($TransType =='1'){
            $sql = 'SELECT '.db_prefix() . 'history.*,'.db_prefix() . 'items.description,Sum('.db_prefix() . 'history.NetChallanAmt) AS ItemValue,Sum('.db_prefix() . 'history.BilledQty) AS BilledQty FROM '.db_prefix() . 'history 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType IN("O") AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
        }else if($TransType =='2'){
            $sql = 'SELECT '.db_prefix() . 'history.*,'.db_prefix() . 'items.description,Sum('.db_prefix() . 'history.NetChallanAmt) AS ItemValue,Sum('.db_prefix() . 'history.BilledQty) AS BilledQty FROM '.db_prefix() . 'history 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType = "R" AND tblhistory.TType2 = "Fresh" AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
        }else if($TransType =='3'){
            $sql = 'SELECT '.db_prefix() . 'history.*,'.db_prefix() . 'items.description,Sum('.db_prefix() . 'history.NetChallanAmt) AS ItemValue,Sum('.db_prefix() . 'history.BilledQty) AS BilledQty FROM '.db_prefix() . 'history 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType IN("R","D") AND tblhistory.TType2 = "Damage" AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
        }else if($TransType =='4'){
            
            $AllItemList = 'SELECT '.db_prefix() . 'history.ItemID,'.db_prefix() . 'items.description FROM '.db_prefix() . 'history 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType IN("O","R","D") AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
            $AllItems = $this->db->query($AllItemList)->result_array();
            
            
            $sql1 = 'SELECT '.db_prefix() . 'history.*,Sum('.db_prefix() . 'history.NetChallanAmt) AS ItemValue,Sum('.db_prefix() . 'history.BilledQty) AS BilledQty FROM '.db_prefix() . 'history 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType IN("O") AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
            $result1 = $this->db->query($sql1)->result_array();
            
            $sql2 = 'SELECT '.db_prefix() . 'history.*,Sum('.db_prefix() . 'history.NetChallanAmt) AS ItemValue,Sum('.db_prefix() . 'history.BilledQty) AS BilledQty FROM '.db_prefix() . 'history 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType = "R" AND tblhistory.TType2 = "Fresh" AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
            $result2 = $this->db->query($sql2)->result_array();
            
            $sql3 = 'SELECT '.db_prefix() . 'history.*,Sum('.db_prefix() . 'history.NetChallanAmt) AS ItemValue,Sum('.db_prefix() . 'history.BilledQty) AS BilledQty FROM '.db_prefix() . 'history 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'
            AND tblhistory.TType IN("R","D") AND tblhistory.TType2 = "Damage" AND tblhistory.AccountID = "'.$AccountID.'" 
            AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
            $result3 = $this->db->query($sql3)->result_array();
            
            $ResultData = array();
            $i = 0;
            foreach($AllItems as $key => $value){
                $ResultData[$i]['ItemID'] = $value["ItemID"];
                $ResultData[$i]['description'] = $value["description"];
                $suplIn = "";
                $CaseQty = "";
                $SaleRate = "";
                $BilledQty = 0;
                $ItemValue = 0;
                
                // for Order
                foreach ($result1 as $key1 => $value1) {
                    if($value["ItemID"] == $value1["ItemID"]){
                        $suplIn = $value1["SuppliedIn"];
                        $CaseQty = $value1["CaseQty"];
                        $SaleRate = $value1["SaleRate"];
                        $BilledQty = $value1["BilledQty"];
                        $ItemValue = $value1["ItemValue"];
                    }
                }
                // for Sale return as fresh
                foreach ($result2 as $key2 => $value2) {
                    if($value["ItemID"] == $value2["ItemID"]){
                        $suplIn = $value2["SuppliedIn"];
                        $CaseQty = $value2["CaseQty"];
                        $SaleRate = $value2["SaleRate"];
                        $BilledQty -= $value2["BilledQty"];
                        $ItemValue -= $value2["ItemValue"];
                    }
                }
                
                // for Sale return as damage
                foreach ($result3 as $key3 => $value3) {
                    if($value["ItemID"] == $value3["ItemID"]){
                        $suplIn = $value3["SuppliedIn"];
                        $CaseQty = $value3["CaseQty"];
                        $SaleRate = $value3["SaleRate"];
                        $BilledQty -= $value3["BilledQty"];
                        $ItemValue -= $value3["ItemValue"];
                    }
                }
                $ResultData[$i]['SuppliedIn'] = $suplIn;
                $ResultData[$i]['CaseQty'] = $CaseQty;
                $ResultData[$i]['SaleRate'] = $SaleRate;
                $ResultData[$i]['BilledQty'] = $BilledQty;
                $ResultData[$i]['ItemValue'] = $ItemValue;
                $i++;
                
            }
            /*foreach ($result1 as $key1 => $value1) {
                $ResultData[$i]['ItemID'] = $value1["ItemID"];
                $ResultData[$i]['description'] = $value1["description"];
                $ResultData[$i]['SuppliedIn'] = $value1["SuppliedIn"];
                $ResultData[$i]['CaseQty'] = $value1["CaseQty"];
                $ResultData[$i]['SaleRate'] = $value1["SaleRate"];
                $BilledQty = $value1["BilledQty"];
                $ItemValue = $value1["ItemValue"];
                foreach ($result2 as $key2 => $value2) {
                    if($value1["ItemID"] == $value2["ItemID"]){
                        $BilledQty -= $value2["BilledQty"];
                        $ItemValue -= $value2["ItemValue"];
                    }
                }
                foreach ($result3 as $key3 => $value3) {
                    if($value1["ItemID"] == $value3["ItemID"]){
                        $BilledQty -= $value3["BilledQty"];
                        $ItemValue -= $value3["ItemValue"];
                    }
                }
                $ResultData[$i]['BilledQty'] = $BilledQty;
                $ResultData[$i]['ItemValue'] = $ItemValue;
                $i++;
            }*/
            
            return $ResultData;
            
        }
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
     
    public function GetSaleVsSaleRtnBodyRowData($filterdata)
     { 

        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $AccountID = $filterdata["AccountID"];
        $locType = $filterdata["locType"];
        $repType = $filterdata["repType"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($AccountID !==''){
            $sql = 'SELECT '.db_prefix() . 'history.ItemID,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2,'.db_prefix() . 'clients.AccountID FROM '.db_prefix() . 'history 
            INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'  AND tblhistory.TType IN("O","R") AND tblclients.AccountID = "'.$AccountID.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
        }else{
            if($repType == '2'){
                $sql = 'SELECT '.db_prefix() . 'history.ItemID,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2,'.db_prefix() . 'clients.AccountID FROM '.db_prefix() . 'history 
                INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
                INNER JOIN  tblaccountlocations ON  tblaccountlocations.AccountID = tblhistory.AccountID AND  tblaccountlocations.PlantID = tblhistory.PlantID 
                INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
                WHERE '.db_prefix() . 'history.FY = '.$fy.' AND '.db_prefix() . 'history.PlantID = '.$selected_company.'  AND tblhistory.TType IN("O","R") AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" ';
                if($locType !== '3'){
                    $sql .= ' AND '.db_prefix() . 'accountlocations.LocationTypeID = "'.$locType.'"';
                }
                $sql .= ' Group By tblhistory.ItemID ORDER BY tblhistory.ItemID';
            }else {
                $sql = 'SELECT '.db_prefix() . 'history.ItemID,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2,'.db_prefix() . 'clients.AccountID FROM '.db_prefix() . 'history 
                INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
                INNER JOIN  tblaccountlocations ON  tblaccountlocations.AccountID = tblhistory.AccountID AND  tblaccountlocations.PlantID = tblhistory.PlantID 
                WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.' AND tblhistory.TType IN("O","R") AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" ';
                if($locType !== '3'){
                    $sql .= ' AND '.db_prefix() . 'accountlocations.LocationTypeID = "'.$locType.'"';
                }
                $sql .= ' Group By tblhistory.AccountID ORDER BY tblhistory.AccountID';
            }
        }
        
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
    public function GetSaleVsSaleRtnBodyData($filterdata)
     { 

        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $AccountID = $filterdata["AccountID"];
        $locType = $filterdata["locType"];
        $repType = $filterdata["repType"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($AccountID !==''){
            $sql = 'SELECT '.db_prefix() . 'history.ItemID,'.db_prefix() . 'history.CaseQty,SUM('.db_prefix() . 'history.NetChallanAmt) AS NetChallanAmt,SUM('.db_prefix() . 'history.BilledQty) AS BilledQty,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.StationName,'.db_prefix() . 'items.description FROM '.db_prefix() . 'history 
            INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'  AND tblhistory.TType IN("O","R") AND tblclients.AccountID = "'.$AccountID.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID,tblhistory.TType,tblhistory.TType2 ORDER BY tblhistory.ItemID';
        }else{
            if($repType == '2'){
                $sql = 'SELECT '.db_prefix() . 'history.ItemID,'.db_prefix() . 'history.CaseQty,SUM('.db_prefix() . 'history.NetChallanAmt) AS NetChallanAmt,SUM('.db_prefix() . 'history.BilledQty) AS BilledQty,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.StationName,'.db_prefix() . 'items.description FROM '.db_prefix() . 'history 
                INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
                LEFT JOIN  tblaccountlocations ON  tblaccountlocations.AccountID = tblhistory.AccountID AND  tblaccountlocations.PlantID = tblhistory.PlantID 
                INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
                WHERE '.db_prefix() . 'history.FY = '.$fy.' AND '.db_prefix() . 'history.PlantID = '.$selected_company.'  AND tblhistory.TType IN("O","R") AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" ';
                if($locType !== '3'){
                    $sql .= ' AND '.db_prefix() . 'accountlocations.LocationTypeID = "'.$locType.'"';
                }
                $sql .= ' Group By tblhistory.ItemID,tblhistory.TType,tblhistory.TType2 ORDER BY tblhistory.ItemID';
            }else {
                $sql = 'SELECT '.db_prefix() . 'history.ItemID,SUM('.db_prefix() . 'history.NetChallanAmt) AS NetChallanAmt,SUM('.db_prefix() . 'history.BilledQty) AS BilledQty,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2,'.db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.StationName FROM '.db_prefix() . 'history 
                INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
                LEFT JOIN  tblaccountlocations ON  tblaccountlocations.AccountID = tblhistory.AccountID AND  tblaccountlocations.PlantID = tblhistory.PlantID 
                WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.' AND tblhistory.TType IN("O","R") AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" ';
                if($locType !== '3'){
                    $sql .= ' AND '.db_prefix() . 'accountlocations.LocationTypeID = "'.$locType.'"';
                }
                $sql .= ' Group By tblhistory.AccountID,tblhistory.TType,tblhistory.TType2 ORDER BY tblhistory.AccountID';
            }
        }
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function GetSaleRtnBodyData($filterdata)
     { 

        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $AccountID = $filterdata["AccountID"];
        $AccountAddress2 = $filterdata["AccountAddress2"];
        $AccountCity = $filterdata["AccountCity"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
        
        if($AccountID !==''){
            $sql = 'SELECT '.db_prefix() . 'history.AccountID,'.db_prefix() . 'history.OrderID,'.db_prefix() . 'history.ItemID,'.db_prefix() . 'history.CaseQty,'.db_prefix() . 'history.TransDate2,SUM('.db_prefix() . 'history.NetChallanAmt) AS NetChallanAmt,SUM('.db_prefix() . 'history.ChallanAmt) AS ChallanAmt,SUM('.db_prefix() . 'history.cgstamt) AS cgstamtSum,SUM('.db_prefix() . 'history.sgstamt) AS sgstamtSum,SUM('.db_prefix() . 'history.igstamt) AS igstamtSum,SUM('.db_prefix() . 'history.BilledQty) AS BilledQty,'.db_prefix() . 'items.description FROM '.db_prefix() . 'history 
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
            WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.'  AND tblhistory.TType IN("R") AND tblhistory.AccountID = "'.$AccountID.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            Group By tblhistory.ItemID,tblhistory.OrderID ORDER BY tblhistory.OrderID ASC';
        }else{
            
            $sql = 'SELECT '.db_prefix() . 'history.OrderID,'.db_prefix() . 'history.TransDate2,SUM('.db_prefix() . 'history.NetChallanAmt) AS NetChallanAmt,SUM('.db_prefix() . 'history.ChallanAmt) AS ChallanAmt,SUM('.db_prefix() . 'history.cgstamt) AS cgstamtSum,SUM('.db_prefix() . 'history.sgstamt) AS sgstamtSum,SUM('.db_prefix() . 'history.igstamt) AS igstamtSum,'.db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.address,'.db_prefix() . 'clients.vat FROM '.db_prefix() . 'history 
                INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
                WHERE '.db_prefix() . 'history.FY = '.$fy.'  AND '.db_prefix() . 'history.PlantID = '.$selected_company.' AND tblhistory.TType IN("R") AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" ';
                
                $sql .= ' Group By tblhistory.OrderID,tblhistory.AccountID ORDER BY tblclients.company ASC';
        }
        
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
     
     public function load_data($data)
     {  
         $from_date = to_sql_date($data["from_date"]);
         $to_date = to_sql_date($data["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '('.db_prefix().'salesmaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'salesmaster.PlantID="'.$selected_company.'"  ORDER BY ChallanID ASC';
        
        $sql ='SELECT '.db_prefix().'salesmaster.*,  
        (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'salesmaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as AccountName, 
        (SELECT GROUP_CONCAT(StationName SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'salesmaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as StationName, 
        (SELECT COUNT(OrderID) FROM '.db_prefix().'ordermaster WHERE '.db_prefix().'ordermaster.ChallanID = '.db_prefix().'salesmaster.ChallanID AND '.db_prefix().'ordermaster.PlantID = '.$selected_company.') as Count_number, 
        (SELECT SUM(OrderAmt) FROM '.db_prefix().'ordermaster WHERE '.db_prefix().'ordermaster.ChallanID = '.db_prefix().'salesmaster.ChallanID AND '.db_prefix().'ordermaster.PlantID = '.$selected_company.') as Total_number
        FROM '.db_prefix().'salesmaster WHERE '.$sql1;
        
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
    
    public function get_sale_item_group2($data)
     {  
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
         
         
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '(Transdate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")';
        
        $sql1 .= ' AND TType ="O" AND PlantID = "'.$selected_company.'" AND FY = "'.$fy.'"';
        
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
      //$this->db->where(db_prefix() . 'items_groups.PlantID', $selected_company);
      
      $this->db->where_in('id',$item_group_ids_uniqu);
      $this->db->order_by('name','ASC');
      $result4 = $this->db->get()->result_array();
        
        return $result4;
        
        }
     }
    
    public function get_itemdetails_for_sale_return($item_id,$AccountId,$filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $report_type = $filterdata["report_type"];
        
        $sql = 'SELECT SUM(NetChallanAmt) as sr_amt_sum,AccountID,ItemID,SUM(tblhistory.BilledQty / tblhistory.CaseQty) AS sr_sumcases,SUM(tblhistory.BilledQty) AS sr_sumunit  FROM `tblhistory` WHERE TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND AccountID IN('.$AccountId.') AND PlantID = '.$selected_company.' AND FY = "'.$fy.'"';
        
        $sql .= ' AND tblhistory.TType ="R" AND ItemID="'.$item_id.'"';
        
        //$sql .= ' GROUP BY ItemID,AccountID';
        $result = $this->db->query($sql)->row();
        return $result;
     }
     
    public function get_itemdetails_for_sale_return2($AccountId,$filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $report_type = $filterdata["report_type"];
        $values_in = $filterdata["values_in"];
        $sql = 'SELECT';
        if($values_in == '1'){
            $sql .= ' SUM(NetChallanAmt) as sr_amt_sum,';
        }else{
            $sql .= ' SUM(ChallanAmt) as sr_amt_sum,';
        }
        $sql .= 'AccountID,ItemID,SUM(tblhistory.BilledQty / tblhistory.CaseQty) AS sr_sumcases,SUM(tblhistory.BilledQty) AS sr_sumunit  
        FROM `tblhistory` 
        WHERE TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND AccountID IN('.$AccountId.') AND PlantID = '.$selected_company.' AND FY = "'.$fy.'"';
        
        $sql .= ' AND tblhistory.TType IN("R","D")';
        
        $sql .= ' GROUP BY ItemID,AccountID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_itemdetails_for_sale_return3($AccountId,$filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $report_type = $filterdata["report_type"];
        $values_in = $filterdata["values_in"];
        $sql = 'SELECT';
        if($values_in == '1'){
            $sql .= ' SUM(NetChallanAmt) as sr_amt_sum,';
        }else{
            $sql .= ' SUM(ChallanAmt) as sr_amt_sum,';
        }
        $sql .= 'AccountID,ItemID,SUM(tblhistory.BilledQty / tblhistory.CaseQty) AS sr_sumcases,SUM(tblhistory.BilledQty) AS sr_sumunit  FROM `tblhistory` WHERE TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND AccountID IN('.$AccountId.') AND PlantID = '.$selected_company.' AND FY = "'.$fy.'"';
        
        $sql .= ' AND tblhistory.TType IN("R","D")';
        
        $sql .= ' GROUP BY ItemID ORDER BY ItemID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
     
    
    public function get_itemdetails_for_body_data($AccountId,$filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $report_type = $filterdata["report_type"];
        $values_in = $filterdata["values_in"];
        $sql = 'SELECT';
        if($values_in == '1'){
            $sql .= ' SUM(NetChallanAmt) as amt_sum,';
        }else{
            $sql .= ' SUM(ChallanAmt) as amt_sum,';
        }
        
        $sql .= ' AccountID,ItemID,SUM(tblhistory.BilledQty / tblhistory.CaseQty) AS sumcases,SUM(tblhistory.BilledQty) AS sumunit,tblhistory.SuppliedIn  FROM `tblhistory` WHERE TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND AccountID IN('.$AccountId.') AND PlantID = '.$selected_company.' AND FY = "'.$fy.'"';
        if($report_type == "freshrtn"){
            $sql .= ' AND tblhistory.TType ="R" AND TType2="Fresh"';
        }else if($report_type == "damage"){
            $sql .= ' AND tblhistory.TType IN("R","D") AND TType2="Damage"';
        }else if($report_type == "netsales"){
            $sql .= ' AND tblhistory.TType IN("O","R","D") AND TType2 IN("Order","Damage","Fresh")';
            //$sql .= ' AND tblhistory.TType ="O" AND tblhistory.TType2="Order"';
        }else if($report_type == "sales"){
            $sql .= ' AND tblhistory.TType ="O" AND tblhistory.TType2="Order"';
        }
        $sql .= ' GROUP BY ItemID,AccountID ORDER BY ItemID ASC';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_itemdetails_for_footer_data($AccountId,$filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $report_type = $filterdata["report_type"];
        $values_in = $filterdata["values_in"];
        $sql = 'SELECT';
        if($values_in == '1'){
            $sql .= ' SUM(NetChallanAmt) as amt_sum,';
        }else{
            $sql .= ' SUM(ChallanAmt) as amt_sum,';
        }
        $sql .= 'AccountID,ItemID,SUM(tblhistory.BilledQty / tblhistory.CaseQty) AS sumcases,SUM(tblhistory.BilledQty) AS sumunit FROM `tblhistory` WHERE TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" AND AccountID IN('.$AccountId.') AND PlantID = '.$selected_company.' AND FY = "'.$fy.'"';
        if($report_type == "freshrtn"){
            $sql .= ' AND tblhistory.TType ="R" AND TType2="Fresh"';
        }elseif($report_type == "damage"){
            $sql .= ' AND tblhistory.TType IN("R","D") AND TType2="Damage"';
        }elseif($report_type == "netsales"){
            $sql .= ' AND tblhistory.TType IN("O","R","D") AND TType2 IN("Order","Damage","Fresh")';
        }elseif($report_type == "sales"){
            $sql .= ' AND tblhistory.TType ="O" AND TType2="Order"';
        }else{
            
        }
        $sql .= ' GROUP BY ItemID';
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
     public function get_orders_itemlist_new($filterdata,$item_group)
     {
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]); 
        $loc_type = $filterdata["loc_type"];
        $states = $filterdata["states"];
        $client_type = $filterdata["client_type"];
        $report_type = $filterdata["report_type"];
        $staff_designation = $filterdata["staff_designation"];
        $staff_id = $filterdata["staff_id"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        
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
            $sql .= ' AND tblhistory.TType IN("R","D") AND TType2="Damage"';
        }elseif($report_type == "netsales"){
            //$sql .= ' AND tblhistory.TType ="O" AND TType2="Order"';
            $sql .= ' AND tblhistory.TType IN("O","R","D") AND TType2 IN("Order","Damage","Fresh")';
        }elseif($report_type == "sales"){
            $sql .= ' AND tblhistory.TType ="O" AND TType2="Order"';
        }
$sql .= ' GROUP BY tblhistory.ItemID,tblhistory.AccountID ORDER BY tblitems.subgroup_id ASC';

        $result = $this->db->query($sql)->result_array();
        return $result;
         
     }
     
   
    public function get_commulative_data($data)
     {  
        $from_date = to_sql_date($data["from_date"]);
         $to_date = to_sql_date($data["to_date"]);
         
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '(Transdate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")';
        
        $sql1 .= ' AND OrderStatus ="C" AND PlantID = "'.$selected_company.'" AND FY = "'.$fy.'"';
        
        $sql ='SELECT '.db_prefix().'ordermaster.* FROM '.db_prefix().'ordermaster WHERE '.$sql1;
        
        $result = $this->db->query($sql)->result_array();
        if(empty($result)){
            return $result;
        }
        
        $order_ids = array();
        $item_ids = array();
        foreach ($result as $key => $value) {
               # code...
               array_push($order_ids, $value["OrderID"]);
            }
        
        $this->db->select('ItemID,description');
      $this->db->from(db_prefix() . 'history');
      $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
      $this->db->where(db_prefix() . 'history.FY', $fy);
      //$this->db->where_in("$order_ids");
      $this->db->distinct();
      $this->db->where_in('OrderID',$order_ids);
      $result2 = $this->db->get()->result_array();
        return $result2;
        
    }
    
    public function GetAccountList($data)
     {  
        $selected_company = $this->session->userdata('root_company');
        $this->db->select('AccountID,company,StationName');
        $this->db->from(db_prefix() . 'clients');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->where_in('AccountID',$data);
        //$this->db->order_by('StationName','ASC');
        $this->db->order_by("StationName ASC,company DESC");
        $result2 = $this->db->get()->result_array();
        return $result2;
        
     }
     
    public function get_body_commulative_data($data)
     {  
        $from_date = $data["from_date"];
         $to_date = $data["to_date"];
         
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '(date BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")';
        
        $sql1 .= ' AND OrderStatus ="C" AND PlantID = "'.$selected_company.'" AND FY = "'.$fy.'"';
        
        $sql ='SELECT '.db_prefix().'ordermaster.* FROM '.db_prefix().'ordermaster WHERE '.$sql1;
        
        $result = $this->db->query($sql)->result_array();
        if(empty($result)){
            return $result;
        }
        
        $order_ids = array();
        $item_ids = array();
        foreach ($result as $key => $value) {
               # code...
               array_push($order_ids, $value["OrderID"]);
            }
        
        $this->db->select('ItemID,description');
      $this->db->from(db_prefix() . 'history');
      $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
      $this->db->where(db_prefix() . 'history.FY', $fy);
      //$this->db->where_in("$order_ids");
      $this->db->distinct();
      $this->db->where_in('OrderID',$order_ids);
      $result2 = $this->db->get()->result_array();
        return $result2;
        
     }
     
     //-----------------------------------------------------
    public function get_reported_by_staff($id){
        $this->db->select('*');
        $this->db->where('job_position', $id);
        $this->db->where('active', '1');
        $records = $this->db->get(db_prefix() . 'staff')->result();
           return $records;
    }
    
    public function GetSOList($id){
        $selected_company = $this->session->userdata('root_company');
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        $this->db->select('*');
        $this->db->where('job_position', $id);
        $this->db->where('active', '1');
        $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
        $records = $this->db->get(db_prefix() . 'staff')->result_array();
        return $records;
    }
    public function GetPartyList(){
        $selected_company = $this->session->userdata('root_company');
        $SubActGroupID = '60001004';
        $this->db->select('*');
        $this->db->where('SubActGroupID', $SubActGroupID);
        $this->db->where('PlantID', $selected_company);
        $this->db->order_by('company', 'ASC');
        $records = $this->db->get(db_prefix() . 'clients')->result_array();
        return $records;
    }
  
   //-----------------------------------------------------
  public function get_state_name($state_id)
  {
    $this->db->select('state_name');
    $this->db->where('short_name', $state_id);
    $state_name = $this->db->get(db_prefix() . 'xx_statelist')->row();
    return $state_name;
  }
  
  //-----------------------------------------------------
  public function GetPartyName($AccountID)
  {
    $selected_company = $this->session->userdata('root_company');
    $this->db->select('company');
    $this->db->where('AccountID', $AccountID);
    $this->db->where('PlantID', $selected_company);
    $AccountName = $this->db->get(db_prefix() . 'clients')->row();
    return $AccountName;
  }
  
  //-----------------------------------------------------
  public function get_client_type_name($client_type){
      
        $selected_company = $this->session->userdata('root_company');
        $this->db->select('name');
        $this->db->where('id', $client_type);
        $this->db->where('PlantID', $selected_company);
        $client_type_name = $this->db->get(db_prefix() . 'customers_groups')->row();
       return $client_type_name;
  }
  
    //-----------------------------------------------------
    public function get_item_group_name($item_group){
      
      $item_group_array = explode(",",$item_group);
        $this->db->select('name');
        $this->db->where_in('id', $item_group_array);
        $item_group_names = $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
        $item_group_name = array();
        foreach ($item_group_names as $key => $value) 
        {
            array_push($item_group_name, $value["name"]);
        }
        $item_group_name_s = implode(", ", $item_group_name);
        return $item_group_name_s;
    }
    
    //-----------------------------------------------------
    public function GetOrderVsDispatchData($filterdata){
     
        $fromDateNew = to_sql_date($filterdata["from_date"]).' 00:00:00';
        $toDateNew = to_sql_date($filterdata["to_date"]).' 23:59:59';
        $State = $filterdata["states"];
        $DistType = $filterdata["client_type"];
        $selected_company = $this->session->userdata('root_company');
        /*$this->db->select(db_prefix().'salesmaster.OrderID,'.db_prefix().'salesmaster.SalesID,'.db_prefix().'salesmaster.Transdate,'.db_prefix().'salesmaster.BillAmt,'.db_prefix().'clients.company,'.db_prefix().'ordermaster.Transdate AS OrderDate,'.db_prefix().'ordermaster.OrderAmt,'.db_prefix().'challanmaster.gatepasstime');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesmaster.PlantID');
        if($State){
            $this->db->where(db_prefix() . 'clients.state',$State);
        }
        if($DistType){
            $this->db->where(db_prefix() . 'clients.DistributorType',$DistType);
        }
        $this->db->join(db_prefix() . 'ordermaster', db_prefix() . 'ordermaster.OrderID = ' . db_prefix() . 'salesmaster.OrderID AND  '.db_prefix() . 'ordermaster.PlantID = ' . db_prefix() . 'salesmaster.PlantID AND  '.db_prefix() . 'ordermaster.FY = ' . db_prefix() . 'salesmaster.FY');
        $this->db->join(db_prefix() . 'challanmaster', db_prefix() . 'challanmaster.ChallanID = ' . db_prefix() . 'salesmaster.ChallanID AND  '.db_prefix() . 'challanmaster.PlantID = ' . db_prefix() . 'salesmaster.PlantID AND  '.db_prefix() . 'challanmaster.FY = ' . db_prefix() . 'salesmaster.FY');
        $this->db->where(db_prefix() . 'ordermaster.Transdate BETWEEN "'. $fromDateNew. '" AND "'. $toDateNew.'"');
        $this->db->order_by(db_prefix() . 'ordermaster.OrderID','ASC');
        $DATA = $this->db->get(db_prefix() . 'salesmaster')->result_array();*/
        
        $this->db->select(db_prefix().'ordermaster.OrderID,'.db_prefix().'salesmaster.SalesID,'.db_prefix().'salesmaster.Transdate,'.db_prefix().'salesmaster.BillAmt,'.db_prefix().'clients.company,'.db_prefix().'ordermaster.Transdate AS OrderDate,'.db_prefix().'ordermaster.OrderAmt,'.db_prefix().'challanmaster.gatepasstime');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'ordermaster.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'ordermaster.PlantID');
        if($State){
            $this->db->where(db_prefix() . 'clients.state',$State);
        }
        if($DistType){
            $this->db->where(db_prefix() . 'clients.DistributorType',$DistType);
        }
        $this->db->join(db_prefix() . 'salesmaster', db_prefix() . 'salesmaster.OrderID = ' . db_prefix() . 'ordermaster.OrderID AND  '.db_prefix() . 'salesmaster.PlantID = ' . db_prefix() . 'ordermaster.PlantID AND  '.db_prefix() . 'ordermaster.FY = ' . db_prefix() . 'salesmaster.FY','LEFT');
        $this->db->join(db_prefix() . 'challanmaster', db_prefix() . 'challanmaster.ChallanID = ' . db_prefix() . 'ordermaster.ChallanID AND  '.db_prefix() . 'challanmaster.PlantID = ' . db_prefix() . 'ordermaster.PlantID AND  '.db_prefix() . 'challanmaster.FY = ' . db_prefix() . 'ordermaster.FY','LEFT');
        $this->db->where(db_prefix() . 'ordermaster.Transdate BETWEEN "'. $fromDateNew. '" AND "'. $toDateNew.'"');
        $this->db->where(db_prefix() . 'ordermaster.PlantID',$selected_company);
        //$this->db->where(db_prefix() . 'ordermaster.OrderStatus ','O');
        $this->db->order_by(db_prefix() . 'ordermaster.OrderID','ASC');
        $DATA = $this->db->get(db_prefix() . 'ordermaster')->result_array();
        return $DATA;
    }
    
    //-----------------------------------------------------
    public function GetOrderVsDispatchItemWiseData($filterdata){
     
        $fromDateNew = to_sql_date($filterdata["from_date"]).' 00:00:00';
        $toDateNew = to_sql_date($filterdata["to_date"]).' 23:59:59';
        $State = $filterdata["states"];
        $DistType = $filterdata["client_type"];
        $AccountID = $filterdata["AccountID"];
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select(db_prefix().'history.ItemID,'.db_prefix().'items.description,'.db_prefix().'items.case_qty,SUM('.db_prefix().'history.OrderQty) AS OrdQty,SUM('.db_prefix().'history.BilledQty) AS BillQty');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND  '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
        if($State || $DistType || $AccountID){
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND  '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
        }
        if($State){
            $this->db->where(db_prefix() . 'clients.state',$State);
        }
        if($AccountID){
            $this->db->where(db_prefix() . 'clients.AccountID',$AccountID);
        }
        if($DistType){
            $this->db->where(db_prefix() . 'clients.DistributorType',$DistType);
        }
        $this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'. $fromDateNew. '" AND "'. $toDateNew.'"');
        $this->db->where(db_prefix() . 'history.PlantID',$selected_company);
        $this->db->where(db_prefix() . 'history.TType','O');
        $this->db->where(db_prefix() . 'history.TType2','Order');
        $this->db->group_by(db_prefix() . 'history.ItemID');
        $DATA = $this->db->get(db_prefix() . 'history')->result_array();
        return $DATA;
    }
}
