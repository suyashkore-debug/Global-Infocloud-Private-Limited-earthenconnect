<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Damage_entry_model extends App_Model
{
    public function __construct()
    { 
        parent::__construct();
    }
       public function get_vendor_data($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
       
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002','60001004']);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
    public function get_items_code(){
         $selected_company = $this->session->userdata('root_company');
    //   $year = $_SESSION['finacial_year'];
       return $this->db->query('select item_code as id, CONCAT(item_code," - ",description) as label from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
    }
     public function items_change($code){
           $selected_company = $this->session->userdata('root_company'); 
    //      $this->db->select();
    //     $this->db->from(db_prefix() . 'items');
    //     $this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax', 'left');
    //      $this->db->where(db_prefix() . 'items.id', $code);
    //   $this->db->where(db_prefix() .'items.PlantID', $selected_company);
    //   $rs = $this->db->get()->row();
      
      $sql="SELECT * FROM `tblitems` 
      LEFT JOIN `tbltaxes` ON `tbltaxes`.`id` = `tblitems`.`tax`
      LEFT JOIN `tblitems_sub_groups` ON `tblitems_sub_groups`.`id` = `tblitems`.`subgroup_id` 
      LEFT JOIN `tblitems_main_groups` ON `tblitems_main_groups`.`id` = `tblitems_sub_groups`.`main_group_id` 
      WHERE `tblitems`.`item_code` = '".$code."' AND `tblitems`.`PlantID` = '".$selected_company."'";    
    $query = $this->db->query($sql);
   return  $query->row();
 
    }
    public function get_basic_r($item_code,$group_id='',$state=''){
        $selected_company = $this->session->userdata('root_company'); 
        $this->db->select();
        $this->db->from(db_prefix() . 'rate_master'); 
        $this->db->where(db_prefix() . 'rate_master.state_id', $state);
        $this->db->where(db_prefix() . 'rate_master.distributor_id', $group_id);
        $this->db->where(db_prefix() . 'rate_master.item_id', $item_code);
        $this->db->where(db_prefix() .'rate_master.PlantID', $selected_company);
     return $rs = $this->db->get()->row();
    echo $this->db->last_query();die;
         
    } 
    public function add_damage_entry($data){
         $data_i ='';
         if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
        
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'Cases/creats';
            $header[] = 'PackQty';
            $header[] = 'DmgQty';
            $header[] = 'BasicRate';
            $header[] = 'GST';
            $header[] = 'CGST';
            $header[] = 'CGSTAMT';
            $header[] = 'SGST';
            $header[] = 'SGSTAMT';
            $header[] = 'IGST';
            $header[] = 'IGSTAMT';
            $header[] = 'total';
            foreach ($pur_order_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        // $acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
        $acc_id = $data['act_name'];
        
        $PlantID = $this->session->userdata('root_company'); 
        $FY = $this->session->userdata('finacial_year'); 
        
        
         if($PlantID == 1){
            $next_damage_entry_number = get_option('next_dmg_number_for_cspl');
        }elseif($PlantID == 2){
            $next_damage_entry_number = get_option('next_dmg_number_for_cff');
        }elseif($PlantID == 3){
            $next_damage_entry_number = get_option('next_dmg_number_for_cbu');
        }elseif($PlantID == 4){
            $next_damage_entry_number = get_option('next_dmg_number_for_cbupl');
        }
         $new_damage_entry_number = 'DMG'.$FY.$next_damage_entry_number;   
        $Transdate =  to_sql_date($data['prd_date'])." ".date('H:i:s');
       
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
            
          
            if($value['Cases/creats'] == 'Case'){
             $SuppliedIn =  'CS';
            }else if(($value['Cases/creats'] == 'Create')){
                $SuppliedIn =  'CR';
            }else{
                $SuppliedIn =  '';
            }
             $basic_rate = $value['BasicRate'];
            $gst_amt = ($value['BasicRate'] * $value['GST']) / 100;
            $sale_rate = $basic_rate + $gst_amt;
            
             $total_gst_amount_including = $value['total'];
             $total_amount_gst = ($value['total']* $value['GST'])/ 100;
            $total_gst_amount_excluding = $total_gst_amount_including - $total_amount_gst;
            
                $data_array_result = array(
                'PlantID'=>$PlantID,
                'FY'=>$FY,
                'cnfid' =>1,
                'OrderID' =>$new_damage_entry_number,
                'TransID' =>$new_damage_entry_number,
                'TransDate' =>$Transdate,
                'BillID' =>$new_damage_entry_number,
                'TransDate2'=>$Transdate,
                'TType'=>'D',
                'TType2'=> 'Damage',
                'AccountID'=> $acc_id,
                'ItemID'=>$value['item_code'],
                'CaseQty'=>$value['PackQty'],
                'SaleRate'=>$sale_rate,
                'BasicRate'=>$value['BasicRate'],
                'SuppliedIn'=>$SuppliedIn,
                'BilledQty'=>$value['DmgQty'],
                'OrderQty'=>$value['DmgQty'],
                'cgst'=>$value['CGST'],
                'cgstamt'=>round($value['CGSTAMT'], 2),
                'sgst'=>$value['SGST'],
                'sgstamt'=>round($value['SGSTAMT'], 2),
                'igst'=>$value['IGST'],
                'igstamt'=>round($value['IGSTAMT'], 2),
                
                'OrderAmt'=>round($total_gst_amount_excluding, 2),
                'NetOrderAmt'=>round($total_gst_amount_excluding, 2),
                'ChallanAmt'=>round($total_gst_amount_including, 2),
                'NetChallanAmt'=>round($total_gst_amount_including, 2),
                'Ordinalno'=>$i,
                'UserID'=>$_SESSION['username'],
                );
                // print_r($data_array_result);
                $data_i = $this->db->insert(db_prefix() . 'history',$data_array_result);
                //echo $this->db->last_query();
               
            $i++;
                
            }
            // die;
            return true;
        }
        return false;
    }
     public function increment_next_number()
    {
        // Update next CHALLAN number in settings
        
       $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_dmg_number_for_cspl');
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_dmg_number_for_cff');
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_dmg_number_for_cbu');
            }elseif($selected_company == 4){
                $this->db->where('name', 'next_dmg_number_for_cbupl');
            }
        $this->db->set('value', 'value+1', false);
        $this->db->update(db_prefix() . 'options');
    }
    public function data_for_damage_list($data){
         $from_date = to_sql_date($data["from_date"]);
         $to_date = to_sql_date($data["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '('.db_prefix().'damagemaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")  AND '.db_prefix().'damagemaster.FY = "'.$fy.'" AND '.db_prefix().'damagemaster.PlantID = "'.$selected_company.'" ORDER BY Transdate,DamageID ASC';
        
        // $sql ='SELECT '.db_prefix().'damagemaster.*,  
        // (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'damagemaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as AccountName 
        
        // FROM '.db_prefix().'damagemaster WHERE '.$sql1;
        
        $this->db->select('tbldamagemaster.*,tblclients.company,tblclients.address,tblclients.Address3,');
        $this->db->from(db_prefix() . 'damagemaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'damagemaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company, 'left');
        $this->db->where($sql1);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_damage_entry_details($id){
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
         $this->db->select('tbldamagemaster.*,tblclients.company,tblclients.address,tblclients.Address3,tblclients.city,tblclients.state,xx_statelist.state_name');
        $this->db->from(db_prefix() . 'damagemaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'damagemaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company, 'left');
        $this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
        $this->db->where('tbldamagemaster.DamageID',$id);
        $result = $this->db->get()->row();
        return $result;
    }
     public function get_total_cases($id){
         $selected_company = $this->session->userdata('root_company'); 
          $year = $_SESSION['finacial_year'];
          $this->db->select('SUM('.db_prefix() . 'history.OrderQty) as OrderQty' , false);
        $this->db->from(db_prefix() . 'history');
        
         $this->db->where(db_prefix() . 'history.OrderID', $id);
      $this->db->where(db_prefix() .'history.FY', $year);
      $this->db->where(db_prefix() .'history.PlantID', $selected_company);
      $this->db->where(db_prefix() .'history.TType', 'D');
      $this->db->where(db_prefix() .'history.TType2', 'Damage');
     return $rs = $this->db->get()->row_array();
     }
    public function get_damage_entry_detail_full($request,$group_id,$state){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
      
      
      
      $this->db->select(db_prefix() . 'history.*,'.db_prefix() . 'rate_master.assigned_rate,'.db_prefix() . 'items.*,'.db_prefix() . 'clients.*,'.db_prefix() . 'taxes.taxrate');
         $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID', 'left');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID', 'left');
        $this->db->join(db_prefix() . 'rate_master', db_prefix() . 'rate_master.item_id = ' . db_prefix() . 'history.ItemID ', 'left');
        $this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax', 'left');
         $this->db->where(db_prefix() . 'history.OrderID', $request);
         $this->db->where(db_prefix() .'rate_master.PlantID', $selected_company);
      $this->db->where(db_prefix() . 'rate_master.state_id', $state);
      $this->db->where(db_prefix() . 'rate_master.distributor_id', $group_id);
      $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
      $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
      $this->db->where(db_prefix() . 'history.FY', $year);
      $data = $this->db->get()->result_array();
      foreach($data  as $key => $value){
        
          if($data[$key]['SuppliedIn'] == 'CS'){
              $data[$key]['SuppliedIn_data'] = 'Case';
          }else if($data[$key]['SuppliedIn'] == 'CR') {
               $data[$key]['SuppliedIn_data'] = 'Crate';
          }else{
             $data[$key]['SuppliedIn_data'] = ''; 
          }
         }
       return $data;
         
    }
    public function update_damage_entry($data,$id){
         $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $data_i ='';
         if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'Cases/creats';
            $header[] = 'PackQty';
            $header[] = 'DmgQty';
            $header[] = 'BasicRate';
            $header[] = 'GST';
            $header[] = 'CGST';
            $header[] = 'CGSTAMT';
            $header[] = 'SGST';
            $header[] = 'SGSTAMT';
            $header[] = 'IGST';
            $header[] = 'IGSTAMT';
            $header[] = 'total';
            foreach ($pur_order_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        // echo '<pre>';
        // $acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
        $acc_id = $data['act_name'];
        $old_damage_item_details = $this->damage_entry_model->get_damage_old_item_detail($id);
        $PlantID = $this->session->userdata('root_company'); 
        $FY = $this->session->userdata('finacial_year'); 
      
        // $Transdate =  $data['prd_date'];
        // if($data['prd_date'] == $data['prd_date2']){
        //     $Transdate =  to_sql_date($data['prd_date2']);
        // }else{
            $Transdate =  to_sql_date($data['prd_date'])." ".date('H:i:s');
        // }
        
             $data_array = array(
            'PlantID'=>$PlantID,
            'FY'=>$FY,
            'Transdate' =>$Transdate,
            'AccountID'=>$acc_id,
            'cgstamt'=>round($data['cgst_amt'], 2),
            'sgstamt'=>round($data['sgst_amt'], 2),
            'igstamt'=>round($data['igst_amt'], 2),
            'DamageAmt'=>round($data['dmg_amt'], 2),
             'UserID2'=>$_SESSION['username'],
            'Lupdate'=>date('Y-m-d H:i:s'),
            'cnfid'=>1,
            );
        
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('DamageID',$id);
            $this->db->update(db_prefix() . 'damagemaster',$data_array);
        //     echo 'damagemaster';
        //   print_r($data_array);die;
        if($this->db->affected_rows() > 0){
            
            
            $new_items = array();
            $deleted_item = array();
          foreach($es_detail as $value){
                    
                    array_push($new_items, $value['item_code']);
                }
        $old_item_code = array();
            foreach ($old_damage_item_details as $key => $value) {
                array_push($old_item_code, $value["ItemID"]);
                if (!in_array($value["ItemID"], $new_items)){
                    array_push($deleted_item, $value["ItemID"]);
                }  
            }
        //   print_r($old_item_code);
            $i =1;
            foreach($es_detail as $value){
            if (in_array($value['item_code'], $old_item_code)){
             
            $basic_rate = $value['BasicRate'];
            $gst_amt = ($value['BasicRate'] * $value['GST']) / 100;
            $sale_rate = $basic_rate + $gst_amt;  
            
              $total_gst_amount_including = $value['total'];
             $total_amount_gst = ($value['total']* $value['GST'])/ 100;
            $total_gst_amount_excluding = $total_gst_amount_including - $total_amount_gst;
            
              if($value['Cases/creats'] == 'Case'){
             $SuppliedIn =  'CS';
            }else if(($value['Cases/creats'] == 'Create')){
                $SuppliedIn =  'CR';
            }else{
                $SuppliedIn =  '';
            }
            
            $data_array_result = array(
                'CaseQty'=>$value['PackQty'],
               
                'SaleRate'=>$sale_rate,
                'BasicRate'=>$value['BasicRate'],
                'TransDate' =>$Transdate,
                'TransDate2'=>$Transdate,
                'BilledQty'=>$value['DmgQty'],
                'OrderQty'=>$value['DmgQty'],
                'cgst'=>$value['CGST'],
                'cgstamt'=>round($value['CGSTAMT'], 2),
                'sgst'=>$value['SGST'],
                'sgstamt'=>round($value['SGSTAMT'], 2),
                'igst'=>$value['IGST'],
                'igstamt'=>round($value['IGSTAMT'], 2),
                
                'OrderAmt'=>round($total_gst_amount_excluding, 2),
                'NetOrderAmt'=>round($total_gst_amount_excluding, 2),
                'ChallanAmt'=>round($total_gst_amount_including, 2),
                'NetChallanAmt'=>round($total_gst_amount_including, 2),
                'Ordinalno'=>$i,
                'UserID2'=>$_SESSION['username'],
                'Lupdate'=>date('Y-m-d H:i:s'),
                'AccountID'=>$acc_id,
                );
            //   echo 'item update'; 
            //     print_r($data_array_result);
                    $this->db->where('OrderID',$id);
                    $this->db->where('ItemID',$value['item_code']);
                    $this->db->where('PlantID', $selected_company);
                    $this->db->LIKE('FY', $fy);
                    $this->db->update(db_prefix() . 'history',$data_array_result);
                
            }else{
            
               
               
            if($value['Cases/creats'] == 'Case'){
             $SuppliedIn =  'CS';
            }else if(($value['Cases/creats'] == 'Create')){
                $SuppliedIn =  'CR';
            }else{
                $SuppliedIn =  '';
            }
             $basic_rate = $value['BasicRate'];
            $gst_amt = ($value['BasicRate'] * $value['GST']) / 100;
            $sale_rate = $basic_rate + $gst_amt;
            
             $total_gst_amount_including = $value['total'];
             $total_amount_gst = ($value['total']* $value['GST'])/ 100;
            $total_gst_amount_excluding = $total_gst_amount_including - $total_amount_gst;
            
                $data_array_result = array(
                'PlantID'=>$PlantID,
                'FY'=>$FY,
                'cnfid' =>1,
                'OrderID' =>$id,
                'TransID' =>$id,
                'TransDate' =>$Transdate,
                'BillID' =>$id,
                'TransDate2'=>$Transdate,
                'TType'=>'D',
                'TType2'=> 'Damage',
                'AccountID'=>$acc_id,
                'ItemID'=>$value['item_code'],
                'CaseQty'=>$value['PackQty'],
                'SaleRate'=>$sale_rate,
                'BasicRate'=>$value['BasicRate'],
                'SuppliedIn'=>$SuppliedIn,
                'BilledQty'=>$value['DmgQty'],
                'OrderQty'=>$value['DmgQty'],
                'cgst'=>$value['CGST'],
                'cgstamt'=>round($value['CGSTAMT'], 2),
                'sgst'=>$value['SGST'],
                'sgstamt'=>round($value['SGSTAMT'], 2),
                'igst'=>$value['IGST'],
                'igstamt'=>round($value['IGSTAMT'], 2),
                
                'OrderAmt'=>round($total_gst_amount_excluding, 2),
                'NetOrderAmt'=>round($total_gst_amount_excluding, 2),
                'ChallanAmt'=>round($total_gst_amount_including, 2),
                'NetChallanAmt'=>round($total_gst_amount_including, 2),
                'Ordinalno'=>$i,
                'UserID'=>$_SESSION['username'],
                );
                //  echo 'item insert'; 
                // print_r($data_array_result);
                $data_i = $this->db->insert(db_prefix() . 'history',$data_array_result);
            } 
                $i++;
        }
        foreach($deleted_item as $values){
                 $this->db->where('OrderID',$id);
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('ItemID', $values);
                $this->db->delete(db_prefix() . 'history');
           }
            
        }
        // die;
        return true;
    }
      public function get_damage_old_item_detail($id){
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
    
    function getaccounts($postData){

    $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_clients = '';
    
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company');
       $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR tblclients.AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID = 60001004';
       
       //$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'clients.PlantID');
       //$this->db->join(db_prefix() . 'route', '' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'accountroutes.RouteID AND ' . db_prefix() . 'route.PlantID = ' . db_prefix() . 'accountroutes.PlantID');
       //$this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state');
       //$this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'clients.PlantID');
       //$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'clients.PlantID');
       
       $this->db->where($where_clients);
      
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        
       $records = $this->db->get(db_prefix() . 'clients')->result();

       foreach($records as $row ){
          $response[] = array("label"=>$row->company,"value"=>$row->AccountID);
       }

     }

     return $response;
  }
    public function get_Account_Details($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        $AccountID = $postData['AccountID'];
        $this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'xx_statelist.state_name,'.db_prefix() . 'customers_groups.name AS aname,'.db_prefix() . 'customers_groups.id AS customers_groups_id,'.db_prefix() . 'accountlocations.LocationTypeID');
       
        //$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'clients.PlantID');
        //$this->db->join(db_prefix() . 'route', '' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'accountroutes.RouteID AND ' . db_prefix() . 'route.PlantID = ' . db_prefix() . 'accountroutes.PlantID');
        $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state');
        $this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'clients.PlantID');
        $this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'clients.PlantID');
      
        $this->db->where(db_prefix() . 'clients.AccountID', $AccountID);
        $this->db->where(db_prefix() . 'clients.SubActGroupID', '60001004');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        return $this->db->get(db_prefix() . 'clients')->row();
      
    }
    
    
}?>