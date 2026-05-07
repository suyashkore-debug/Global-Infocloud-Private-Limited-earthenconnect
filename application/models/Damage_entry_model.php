<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Damage_entry_model extends App_Model
{
    public function __construct()
    { 
        parent::__construct();
    }
    public function GetDamgeAccount($id = '', $where = [])
    {
        $selected_company = $this->session->userdata('root_company');
        $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
        $this->db->where_in(db_prefix() . 'clients.AccountID',"C01291");
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
    public function get_items_code(){
         $selected_company = $this->session->userdata('root_company');
       return $this->db->query('select item_code as id, CONCAT(item_code," - ",description) as label from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
    }
    public function ItemDetails($ItemID)
    {
        $PlantID = $this->session->userdata('root_company'); 
        $sql="SELECT * FROM `tblitems` 
            LEFT JOIN `tbltaxes` ON `tbltaxes`.`id` = `tblitems`.`tax`
            LEFT JOIN `tblrate_master` ON `tblrate_master`.`item_id` = `tblitems`.`item_code` AND tblrate_master.state_id = 'UP' AND tblrate_master.PlantID = '".$PlantID."'
            WHERE `tblitems`.`item_code` = '".$ItemID."' AND `tblitems`.`PlantID` = '".$PlantID."'";    
        $query = $this->db->query($sql);
        return  $query->row();
    }
    /* public function add_damage_entry($data)
    {
        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $header = [];
            $header[] = 'id';
            $header[] = 'Unit';
            $header[] = 'SuppliedIn';
            $header[] = 'PackQty';
            $header[] = 'OrderQty';
            $header[] = 'BasicRate';
            $header[] = 'cgst';
            $header[] = 'cgstamt';
            $header[] = 'sgst';
            $header[] = 'sgstamt';
            $header[] = 'igst';
            $header[] = 'igstamt';
            $header[] = 'NetChallanAmt';
            foreach ($pur_order_detail as $key => $value) {
                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        $acc_id = $data['AccountID'];
        
        $PlantID = $this->session->userdata('root_company'); 
        $FY = $this->session->userdata('finacial_year'); 
        if($PlantID == 1){
            $next_damage_entry_number = get_option('next_dmg_number_for_gf');
        }
        $new_damage_entry_number = 'DMG'.$FY.$next_damage_entry_number;   
        $Transdate =  to_sql_date($data['DamageDate'])." ".date('H:i:s');
       
        $data_array = array(
            'PlantID'=>$PlantID,
            'FY'=>$FY,
            'DamageID' =>$new_damage_entry_number,
            'Transdate' =>$Transdate,
            'AccountID'=>$acc_id,
            'cgstamt'=>round($data['cgst_amt'], 2),
            'sgstamt'=>round($data['sgst_amt'], 2),
            'igstamt'=>round($data['igst_amt'], 2),
            'DamageAmt'=>round($data['dmg_amt'], 2),
            'UserID'=>$_SESSION['username'],
            'cnfid'=>1,
        );
        $this->db->insert(db_prefix() . 'damagemaster',$data_array);
        if($this->db->affected_rows() > 0){
         $this->increment_next_number();
            $i =1;
            foreach($es_detail as $value){
                $basic_rate = $value['BasicRate'];
                $GstPer = $value['cgst'] + $value['sgst'] + $value['igst'];
                $sale_rate = $basic_rate + ($value['BasicRate'] * $GstPer) / 100;
                $OrderAmt = $basic_rate * $value['OrderQty'];
                $data_array_result = array(
                    'PlantID'=>$PlantID,
                    'FY'=>$FY,
                    'cnfid' =>1,
                    'OrderID' =>$new_damage_entry_number,
                    'TransID' =>$new_damage_entry_number,
                    'TransDate' =>$Transdate,
                    'BillID' =>$new_damage_entry_number,
                    'TransDate2'=>date('Y-m-d H:i:s'),
                    'TType'=>'D',
                    'TType2'=> 'Damage',
                    'AccountID'=> $acc_id,
                    'ItemID'=>$value['id'],
                    'CaseQty'=>$value['PackQty'],
                    'SaleRate'=>$sale_rate,
                    'BasicRate'=>$value['BasicRate'],
                    'SuppliedIn'=>$value['SuppliedIn'],
                    'BilledQty'=>$value['OrderQty'],
                    'OrderQty'=>$value['OrderQty'],
                    'cgst'=>$value['cgst'],
                    'cgstamt'=>$value['cgstamt'],
                    'sgst'=>$value['cgst'],
                    'sgstamt'=>$value['sgstamt'],
                    'igst'=>$value['cgst'],
                    'igstamt'=>$value['igstamt'],
                    'OrderAmt'=>$OrderAmt,
                    'NetOrderAmt'=>$value['NetChallanAmt'],
                    'ChallanAmt'=>$OrderAmt,
                    'NetChallanAmt'=>$value['NetChallanAmt'],
                    'Ordinalno'=>$i,
                    'UserID'=>$_SESSION['username'],
                );
                $data_i = $this->db->insert(db_prefix() . 'history',$data_array_result);
                $i++;
            }
            return true;
        }
        return false;
    }
    
    public function update_damage_entry($data,$id)
    {
        $PlantID = $this->session->userdata('root_company'); 
        $FY = $this->session->userdata('finacial_year');
        
        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $header = [];
            $header[] = 'id';
            $header[] = 'Unit';
            $header[] = 'SuppliedIn';
            $header[] = 'PackQty';
            $header[] = 'OrderQty';
            $header[] = 'BasicRate';
            $header[] = 'cgst';
            $header[] = 'cgstamt';
            $header[] = 'sgst';
            $header[] = 'sgstamt';
            $header[] = 'igst';
            $header[] = 'igstamt';
            $header[] = 'NetChallanAmt';
            foreach ($pur_order_detail as $key => $value) {
                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        $acc_id = $data['AccountID'];
        $Transdate =  to_sql_date($data['DamageDate'])." ".date('H:i:s');
        $old_damage_item_details = $this->get_damage_old_item_detail($id);
        $data_array = array(
            'PlantID'=>$PlantID,
            'FY'=>$FY,
            'Transdate' =>$Transdate,
            'AccountID'=>$acc_id,
            'cgstamt'=>$data['cgst_amt'],
            'sgstamt'=>$data['sgst_amt'],
            'igstamt'=>$data['igst_amt'],
            'DamageAmt'=>$data['dmg_amt'],
             'UserID2'=>$_SESSION['username'],
            'Lupdate'=>date('Y-m-d H:i:s'),
            'cnfid'=>1,
        );
        $this->db->where('PlantID', $PlantID);
        $this->db->LIKE('FY', $FY);
        $this->db->where('DamageID',$id);
        $this->db->update(db_prefix() . 'damagemaster',$data_array);
        if($this->db->affected_rows() > 0){
            $new_items = array();
            $deleted_item = array();
            foreach($es_detail as $value){
                array_push($new_items, $value['id']);
            }
            $old_item_code = array();
            foreach ($old_damage_item_details as $key => $value) {
                array_push($old_item_code, $value["ItemID"]);
                if (!in_array($value["ItemID"], $new_items)){
                    array_push($deleted_item, $value["ItemID"]);
                }  
            }
            $i =1;
            foreach($es_detail as $value){
                if (in_array($value['id'], $old_item_code)){
                    $basic_rate = $value['BasicRate'];
                    $GstPer = $value['cgst'] + $value['sgst'] + $value['igst'];
                    $sale_rate = $basic_rate + ($value['BasicRate'] * $GstPer) / 100;
                    $OrderAmt = $basic_rate * $value['OrderQty'];
                    $data_array_result = array(
                        'TransDate' =>$Transdate,
                        'TransDate2'=>date('Y-m-d H:i:s'),
                        'AccountID'=> $acc_id,
                        'SaleRate'=>$sale_rate,
                        'BasicRate'=>$value['BasicRate'],
                        'CaseQty'=>$value['PackQty'],
                        'SuppliedIn'=>$value['SuppliedIn'],
                        'BilledQty'=>$value['OrderQty'],
                        'OrderQty'=>$value['OrderQty'],
                        'cgst'=>$value['cgst'],
                        'cgstamt'=>$value['cgstamt'],
                        'sgst'=>$value['cgst'],
                        'sgstamt'=>$value['sgstamt'],
                        'igst'=>$value['cgst'],
                        'igstamt'=>$value['igstamt'],
                        'OrderAmt'=>$OrderAmt,
                        'NetOrderAmt'=>$value['NetChallanAmt'],
                        'ChallanAmt'=>$OrderAmt,
                        'NetChallanAmt'=>$value['NetChallanAmt'],
                        'Ordinalno'=>$i,
                        'UserID2'=>$_SESSION['username'],
                        'Lupdate'=>date('Y-m-d H:i:s'),
                    );
                    $this->db->where('OrderID',$id);
                    $this->db->where('ItemID',$value['id']);
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->update(db_prefix() . 'history',$data_array_result);
                
                }else{
                    
                    $basic_rate = $value['BasicRate'];
                    $GstPer = $value['cgst'] + $value['sgst'] + $value['igst'];
                    $sale_rate = $basic_rate + ($value['BasicRate'] * $GstPer) / 100;
                    $OrderAmt = $basic_rate * $value['OrderQty'];
                    $data_array_result = array(
                        'PlantID'=>$PlantID,
                        'FY'=>$FY,
                        'cnfid' =>1,
                        'OrderID' =>$new_damage_entry_number,
                        'TransID' =>$new_damage_entry_number,
                        'TransDate' =>$Transdate,
                        'BillID' =>$new_damage_entry_number,
                        'TransDate2'=>date('Y-m-d H:i:s'),
                        'TType'=>'D',
                        'TType2'=> 'Damage',
                        'AccountID'=> $acc_id,
                        'ItemID'=>$value['id'],
                        'CaseQty'=>$value['PackQty'],
                        'SaleRate'=>$sale_rate,
                        'BasicRate'=>$value['BasicRate'],
                        'SuppliedIn'=>$value['SuppliedIn'],
                        'BilledQty'=>$value['OrderQty'],
                        'OrderQty'=>$value['OrderQty'],
                        'cgst'=>$value['cgst'],
                        'cgstamt'=>$value['cgstamt'],
                        'sgst'=>$value['cgst'],
                        'sgstamt'=>$value['sgstamt'],
                        'igst'=>$value['cgst'],
                        'igstamt'=>$value['igstamt'],
                        'OrderAmt'=>$OrderAmt,
                        'NetOrderAmt'=>$value['NetChallanAmt'],
                        'ChallanAmt'=>$OrderAmt,
                        'NetChallanAmt'=>$value['NetChallanAmt'],
                        'Ordinalno'=>$i,
                        'UserID'=>$_SESSION['username'],
                    );
            
                    $data_i = $this->db->insert(db_prefix() . 'history',$data_array_result);
                } 
                $i++;
            }
            foreach($deleted_item as $values){
                $this->db->where('OrderID',$id);
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('ItemID', $values);
                $this->db->delete(db_prefix() . 'history');
            }
        }
        return true;
    }*/
    public function increment_next_number()
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == 1){
            $this->db->where('name', 'next_dmg_number_for_gf');
        }
        $this->db->where('FY', $fy);
        $this->db->set('value', 'value+1', false);
        $this->db->update(db_prefix() . 'options');
    }
    public function data_for_damage_list($data)
    {
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $sql1 = '('.db_prefix().'damagemaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")  AND '.db_prefix().'damagemaster.FY = "'.$fy.'" AND '.db_prefix().'damagemaster.PlantID = "'.$selected_company.'" ORDER BY Transdate,DamageID ASC';
        
        $this->db->select('tbldamagemaster.*,tblclients.company,tblclients.address,tblclients.Address3,');
        $this->db->from(db_prefix() . 'damagemaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'damagemaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company, 'left');
        $this->db->where($sql1);
        $result = $this->db->get()->result_array();
        return $result;
    }
    /*public function get_damage_entry_details($id)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $this->db->select('tbldamagemaster.*');
        $this->db->from(db_prefix() . 'damagemaster');
        $this->db->where('tbldamagemaster.DamageID',$id);
        $result = $this->db->get()->row();
        if($result){
            $this->db->select('tblhistory.*,tblhistory.ItemID AS id,tblhistory.CaseQty As PackQty,tblitems.unit AS Unit');
            $this->db->from(db_prefix() . 'history');
            $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID');
            $this->db->where(db_prefix() . 'history.OrderID', $id);
            $this->db->where(db_prefix() .'history.FY', $fy);
            $this->db->where(db_prefix() .'history.PlantID', $selected_company);
            $this->db->where(db_prefix() .'history.TType', 'D');
            $this->db->where(db_prefix() .'history.TType2', 'Damage');
            $ItemList = $this->db->get()->result_array();
            $result->ItemList = $ItemList;
            $TotalCases = 0;
            $TotalCrates = 0;
            foreach($ItemList as $val){
                if($val["SuppliedIn"] == "CS"){
                    $TotalCases += ($val["BilledQty"] / $val["PackQty"]);
                }else{
                    $TotalCrates += ($val["BilledQty"] / $val["PackQty"]);
                }
            }
            $result->TotalCases = $TotalCases;
            $result->TotalCrates = $TotalCrates;
        }
        return $result;
    }*/
	
	 public function get_damage_entry_details($id)
{
    $fy = $this->session->userdata('finacial_year');
    $selected_company = $this->session->userdata('root_company');
    $this->db->select('tbldamagemaster.*');
    $this->db->from(db_prefix() . 'damagemaster');
    $this->db->where('tbldamagemaster.DamageID',$id);
    $result = $this->db->get()->row();
    
    if($result){
        // Single query using LEFT JOIN to get ConvertQty from inward entries
        $this->db->select('d.*, d.ItemID AS id, d.ItemIDTo AS convert_to, d.CaseQty As PackQty, 
                          tblitems.unit AS Unit,tblitems.weight, i.OrderQty AS ConvertQty');
        $this->db->from(db_prefix() . 'history d');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = d.ItemID');
        $this->db->join(db_prefix() . 'history i', 
                       'i.OrderID = d.OrderID AND i.ItemID = d.ItemIDTo AND i.TType = "I" AND i.TType2 = "Inward"', 
                       'left');
        $this->db->where('d.OrderID', $id);
        $this->db->where('d.FY', $fy);
        $this->db->where('d.PlantID', $selected_company);
        $this->db->where('d.TType', 'D');
        $this->db->where('d.TType2', 'Damage');
        
        $ItemList = $this->db->get()->result_array();
        $result->ItemList = $ItemList;
        
        $TotalCases = 0;
        $TotalCrates = 0;
        foreach($ItemList as $val){
            if($val["SuppliedIn"] == "CS"){
                $TotalCases += ($val["BilledQty"] / $val["PackQty"]);
            }else{
                $TotalCrates += ($val["BilledQty"] / $val["PackQty"]);
            }
        }
        $result->TotalCases = $TotalCases;
        $result->TotalCrates = $TotalCrates;
    }
    return $result;
}
    
    public function get_damage_old_item_detail($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $this->db->select();
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.BillID', $id);
        $this->db->where(db_prefix() .'history.TType', 'D');
        $this->db->where(db_prefix() .'history.TType2', 'Damage');
       return $this->db->get()->result_array();
    }
    // **************************** Add Update **************************************************/
public function add_damage_entry($data)
{
    if (isset($data['pur_order_detail'])) {
        $pur_order_detail = json_decode($data['pur_order_detail']);
        unset($data['pur_order_detail']);
        $es_detail = [];
        $header = [];
        $header[] = 'id';
        $header[] = 'convert_to';
        $header[] = 'Unit';
        $header[] = 'weight';
        $header[] = 'PackQty';
        $header[] = 'OrderQty';
		$header[] = 'ConvertQty';		
        $header[] = 'BasicRate';
        $header[] = 'cgst';
        $header[] = 'cgstamt';
        $header[] = 'sgst';
        $header[] = 'sgstamt';
        $header[] = 'igst';
        $header[] = 'igstamt';
        $header[] = 'NetChallanAmt';
        foreach ($pur_order_detail as $key => $value) {
            if ($value[0] != '') {
                $es_detail[] = array_combine($header, $value);
            }
        }
    }
    $acc_id = $data['AccountID'];

    $PlantID = $this->session->userdata('root_company');
    $FY = $this->session->userdata('finacial_year');
    if ($PlantID == 1) {
        $next_damage_entry_number = get_option('next_dmg_number_for_gf');
    }
    $new_damage_entry_number = 'DMG' . $FY . $next_damage_entry_number;
    $Transdate =  to_sql_date($data['DamageDate']) . " " . date('H:i:s');

    $data_array = array(
        'PlantID' => $PlantID,
        'FY' => $FY,
        'DamageID' => $new_damage_entry_number,
        'Transdate' => $Transdate,
        'AccountID' => $acc_id,
        'cgstamt' => round($data['cgst_amt'], 2),
        'sgstamt' => round($data['sgst_amt'], 2),
        'igstamt' => round($data['igst_amt'], 2),
        'DamageAmt' => round($data['dmg_amt'], 2),
        'UserID' => $_SESSION['username'],
        'cnfid' => 1,
    );
    $this->db->insert(db_prefix() . 'damagemaster', $data_array);
    if ($this->db->affected_rows() > 0) {
        $this->increment_next_number();
        $i = 1;
        foreach ($es_detail as $value) {
            $basic_rate = $value['BasicRate'];
            $GstPer = $value['cgst'] + $value['sgst'] + $value['igst'];
            $sale_rate = $basic_rate + ($value['BasicRate'] * $GstPer) / 100;
            $OrderAmt = $basic_rate * $value['OrderQty'];
            
            // Create the base data array for the history entry
            $data_array_result = array(
                'PlantID' => $PlantID,
                'FY' => $FY,
                'cnfid' => 1,
                'OrderID' => $new_damage_entry_number,
                'TransID' => $new_damage_entry_number,
                'TransDate' => $Transdate,
                'BillID' => $new_damage_entry_number,
                'TransDate2' => date('Y-m-d H:i:s'),
                'TType' => 'D',
                'TType2' => 'Damage',
                'AccountID' => $acc_id,
                'ItemID' => $value['id'],
				'ItemIDTo' => $value['convert_to'], 
                'CaseQty' => $value['PackQty'],
                'SaleRate' => $sale_rate,
                'BasicRate' => $value['BasicRate'],
                // 'SuppliedIn' => $value['SuppliedIn'],
                'BilledQty' => $value['OrderQty'],
                'OrderQty' => $value['OrderQty'],
				//'ConvertQty' =>$value['ConvertQty'],
				'cgst'=>$value['cgst'],
				'cgstamt'=>$value['cgstamt'],
				'sgst'=>$value['cgst'],
				'sgstamt'=>$value['sgstamt'],
				'igst'=>$value['cgst'],
				'igstamt'=>$value['igstamt'],
                'OrderAmt' => $OrderAmt,
                'NetOrderAmt' => $value['NetChallanAmt'],
                'ChallanAmt' => $OrderAmt,
                'NetChallanAmt' => $value['NetChallanAmt'],
                'UserID' => $_SESSION['username'],
            );

            // ## 1. SAVE THE ORIGINAL ITEM
            $data_array_result['Ordinalno'] = $i;
            $this->db->insert(db_prefix() . 'history', $data_array_result);
            $i++;

            // ## 2. IF 'convert_to'  SAVE THE SECOND ENTRY
            if (isset($value['convert_to']) && !empty($value['convert_to'])) {
			 $data_array_result2 = array(
				'PlantID' => $PlantID,
                'FY' => $FY,
                'cnfid' => 1,
                'OrderID' => $new_damage_entry_number,
                'TransID' => $new_damage_entry_number,
                'TransDate' => $Transdate,
                'BillID' => $new_damage_entry_number,
                'TransDate2' => date('Y-m-d H:i:s'),
                'TType' => 'I',
                'TType2' => 'Inward',
                'AccountID' => $acc_id,     //item id 
                'ItemID' => $value['convert_to'],   //convert to 
				'ItemIDTo' => $value['id'], 
                'CaseQty' => $value['PackQty'],
                'SaleRate' => $sale_rate,
                'BasicRate' => $value['BasicRate'],
                // 'SuppliedIn' => $value['SuppliedIn'],
                'BilledQty' => $value['OrderQty'],
                'OrderQty' => $value['ConvertQty'],  // conver to in kg
				'cgst'=>$value['cgst'],
				'cgstamt'=>$value['cgstamt'],
				'sgst'=>$value['cgst'],
				'sgstamt'=>$value['sgstamt'],
				'igst'=>$value['cgst'],
				'igstamt'=>$value['igstamt'],
                'OrderAmt' => $OrderAmt,
                'NetOrderAmt' => $value['NetChallanAmt'],
                'ChallanAmt' => $OrderAmt,
                'NetChallanAmt' => $value['NetChallanAmt'],
                'UserID' => $_SESSION['username'],
				  );
                $this->db->insert(db_prefix() . 'history', $data_array_result2);
                $i++;
            }
        }
        return true;
    }
    return false;
}	
	
 
	
	public function update_damage_entry($data, $id)
{
    $PlantID = $this->session->userdata('root_company'); 
    $FY = $this->session->userdata('finacial_year');
    
    if(isset($data['pur_order_detail'])){
        $pur_order_detail = json_decode($data['pur_order_detail']);
        unset($data['pur_order_detail']);
        $es_detail = [];
        $header = [];
        $header[] = 'id';
        $header[] = 'convert_to'; // ADD THIS for convert_to
        $header[] = 'Unit';
        $header[] = 'weight';
        $header[] = 'PackQty';
        $header[] = 'OrderQty';
        $header[] = 'ConvertQty';
        $header[] = 'BasicRate';
        $header[] = 'cgst';
        $header[] = 'cgstamt';
        $header[] = 'sgst';
        $header[] = 'sgstamt';
        $header[] = 'igst';
        $header[] = 'igstamt';
        $header[] = 'NetChallanAmt';
        foreach ($pur_order_detail as $key => $value) {
            if($value[0] != ''){
                $es_detail[] = array_combine($header, $value);
            }
        }
    }
    $acc_id = $data['AccountID'];
    $Transdate =  to_sql_date($data['DamageDate'])." ".date('H:i:s');
    
    $data_array = array(
        'PlantID'=>$PlantID,
        'FY'=>$FY,
        'Transdate' =>$Transdate,
        'AccountID'=>$acc_id,
        'cgstamt'=>$data['cgst_amt'],
        'sgstamt'=>$data['sgst_amt'],
        'igstamt'=>$data['igst_amt'],
        'DamageAmt'=>$data['dmg_amt'],
        'UserID2'=>$_SESSION['username'],
        'Lupdate'=>date('Y-m-d H:i:s'),
        'cnfid'=>1,
    );
    
    $this->db->where('PlantID', $PlantID);
    $this->db->LIKE('FY', $FY);
    $this->db->where('DamageID',$id);
    $this->db->update(db_prefix() . 'damagemaster',$data_array);
    
    if($this->db->affected_rows() > 0){
        // Delete all existing history records for this damage entry
        $this->db->where('OrderID', $id);
        $this->db->where('PlantID', $PlantID);
        $this->db->LIKE('FY', $FY);
        $this->db->delete(db_prefix() . 'history');
        
        // Insert all records fresh (both original and inverted)
        $i = 1;
        foreach($es_detail as $value){
            $basic_rate = $value['BasicRate'];
            $GstPer = $value['cgst'] + $value['sgst'] + $value['igst'];
            $sale_rate = $basic_rate + ($value['BasicRate'] * $GstPer) / 100;
            $OrderAmt = $basic_rate * $value['OrderQty'];
            
            // ## 1. SAVE THE ORIGINAL ITEM (Damage)
            $data_array_result = array(
                'PlantID' => $PlantID,
                'FY' => $FY,
                'cnfid' => 1,
                'OrderID' => $id,
                'TransID' => $id,
                'TransDate' => $Transdate,
                'BillID' => $id,
                'TransDate2' => date('Y-m-d H:i:s'),
                'TType' => 'D',
                'TType2' => 'Damage',
                'AccountID' => $acc_id,
                'ItemID' => $value['id'],
                'ItemIDTo' => $value['convert_to'], 
                'CaseQty' => $value['PackQty'],
                'SaleRate' => $sale_rate,
                'BasicRate' => $value['BasicRate'],
                // 'SuppliedIn' => $value['SuppliedIn'],
                'BilledQty' => $value['OrderQty'],
                'OrderQty' => $value['OrderQty'],
                'cgst' => $value['cgst'],
                'cgstamt' => $value['cgstamt'],
                'sgst' => $value['cgst'],
                'sgstamt' => $value['sgstamt'],
                'igst' => $value['cgst'],
                'igstamt' => $value['igstamt'],
                'OrderAmt' => $OrderAmt,
                'NetOrderAmt' => $value['NetChallanAmt'],
                'ChallanAmt' => $OrderAmt,
                'NetChallanAmt' => $value['NetChallanAmt'],
                'Ordinalno' => $i,
                'UserID' => $_SESSION['username'],
                'Lupdate' => date('Y-m-d H:i:s'),
            );
            $this->db->insert(db_prefix() . 'history', $data_array_result);
            $i++;

            // ## 2. IF 'convert_to' HAS VALUE, SAVE THE INVERTED ENTRY
            if (isset($value['convert_to']) && !empty($value['convert_to'])) {
                $data_array_result2 = array(
                    'PlantID' => $PlantID,
                    'FY' => $FY,
                    'cnfid' => 1,
                    'OrderID' => $id,
                    'TransID' => $id,
                    'TransDate' => $Transdate,
                    'BillID' => $id,
                    'TransDate2' => date('Y-m-d H:i:s'),
                    'TType' => 'I',
                    'TType2' => 'Inward',
                    'AccountID' => $acc_id,
                    'ItemID' => $value['convert_to'],   // convert to item
                    'ItemIDTo' => $value['id'],         // original item
                    'CaseQty' => $value['PackQty'],
                    'SaleRate' => $sale_rate,
                    'BasicRate' => $value['BasicRate'],
                    // 'SuppliedIn' => $value['SuppliedIn'],
                    'BilledQty' => $value['ConvertQty'],
                    //'OrderQty' => $value['OrderQty'],
					'OrderQty' => $value['ConvertQty'],
                    'cgst' => $value['cgst'],
                    'cgstamt' => $value['cgstamt'],
                    'sgst' => $value['cgst'],
                    'sgstamt' => $value['sgstamt'],
                    'igst' => $value['cgst'],
                    'igstamt' => $value['igstamt'],
                    'OrderAmt' => $OrderAmt,
                    'NetOrderAmt' => $value['NetChallanAmt'],
                    'ChallanAmt' => $OrderAmt,
                    'NetChallanAmt' => $value['NetChallanAmt'],
                    'Ordinalno' => $i,
                    'UserID' => $_SESSION['username'],
                    'Lupdate' => date('Y-m-d H:i:s'),
                );
                $this->db->insert(db_prefix() . 'history', $data_array_result2);
                $i++;
            }
        }
    }
    return true;
}
    
}?>