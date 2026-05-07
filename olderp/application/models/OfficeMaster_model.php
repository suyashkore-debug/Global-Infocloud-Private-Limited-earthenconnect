<?php

defined('BASEPATH') or exit('No direct script access allowed');

class OfficeMaster_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
     
    public function get_table_data(){
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'OfficeMaster.*');
        $this->db->from(db_prefix() . 'OfficeMaster');
        $this->db->order_by('AccountID', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function get($AccountID = '')
    {
        $fy = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'OfficeMaster.*');
        $this->db->from(db_prefix() . 'OfficeMaster');
        $this->db->order_by('AccountID', 'asc');
        if ($AccountID) {
            $this->db->where(db_prefix() . 'OfficeMaster.AccountID', $AccountID);
            $data = $this->db->get()->row();
            return $data;
        }
        return $this->db->get()->result_array();
    }
    
    // Add New AccountID
    public function SaveAccountID($data)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $UserID = $this->session->userdata('username');
        
        $data['UserID'] = $UserID;
        $data['TransDate'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'OfficeMaster', $data);
        $INSERT = $this->db->affected_rows();
        
        if($INSERT > 0){
            return true;
        }else{
            return false;
        }
    }
    
    // Update Exiting ItemID
    public function UpdateAccountID($data,$AccountID)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $UserID = $this->session->userdata('username');
        
        $UPDATE = 0;
        
        $data['UserID2'] = $UserID;
        $data['Lupdate'] = date('Y-m-d H:i:s');
        
        $this->db->where('AccountID', $AccountID);
        $this->db->update(db_prefix() . 'OfficeMaster', $data);
                        
        $updateR = $this->db->affected_rows();
        $UPDATE += $updateR;
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
    function getStocks($id){
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        $this->db->select('*');
        $this->db->where(db_prefix() . 'stockmaster.ItemID', $id);
        $this->db->where(db_prefix() . 'stockmaster.FY', $fy);
        $this->db->where('GodownID',$GodownID);
        $this->db->order_by('PlantID', 'ASC');
        $records = $this->db->get(db_prefix() . 'stockmaster')->result();
        return $records;
    }
    
    function getStocksDetails($id){
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
        $this->db->from(db_prefix() .'history');
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.ItemID ', $id);
        $this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() .'history.FY', $fy);
        $this->db->group_by('ItemID,TType,TType2');
        return $this->db->get()->result_array();
    }
    
    function getItemStatus($id){
        $fy = $this->session->userdata('finacial_year');
        $this->db->select(db_prefix() . 'items.isactive,'.db_prefix() . 'items.PlantID');
        $this->db->where(db_prefix() . 'items.item_code', $id);
        $this->db->order_by('PlantID', 'ASC');
        $records = $this->db->get(db_prefix() . 'items')->result();
        return $records;
    }
    function getitem($postData){

     $response = array();
        $subgroup = array('9','20','36');
     if(isset($postData['search']) ){
       // Select record
       $item_div_id = explode(",",$postData['item_divistion']);
       //if($postData['item_taxes']=="Non-Taxable"){
           $non_taxable_id = get_zerotaxrate_id();
       //}
       
       $this->db->select('*');
       $this->db->where("description like '%".$postData['search']."%' ");
       $this->db->where_not_in('subgroup_id',$subgroup);
       
       $selected_company = $this->session->userdata('root_company');
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $records = $this->db->get(db_prefix() . 'items')->result();

       foreach($records as $row ){
          $response[] = array("itemdiv"=>$row->group_id,"gst"=>$row->tax,"value"=>$row->item_code,"label"=>$row->description,"location"=>$postData['location'],"item_taxes"=>$postData['item_taxes'],"dist_type_id"=>$postData['dist_type_id'],"dist_state_id"=>$postData['dist_state_id'],"isactive"=>$row->isactive);
       }

     }

     return $response;
  }
  
  function getitem_using_itemcode($postData){

     $response = array();
    $subgroup = array('9','20','36');
     if(isset($postData['search']) ){
       // Select record
       $item_div_id = explode(",",$postData['item_divistion']);
       //if($postData['item_taxes']=="Non-Taxable"){
           $non_taxable_id = get_zerotaxrate_id();
       //}
       
       $this->db->select('*');
       $this->db->where("item_code like '%".$postData['search']."%' ");
       $this->db->where_not_in('subgroup_id',$subgroup);
       
       $selected_company = $this->session->userdata('root_company');
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $records = $this->db->get(db_prefix() . 'items')->result();

       foreach($records as $row ){
          $response[] = array("itemdiv"=>$row->group_id,"gst"=>$row->tax,"value"=>$row->item_code,"label"=>$row->description,"location"=>$postData['location'],"item_taxes"=>$postData['item_taxes'],"dist_type_id"=>$postData['dist_type_id'],"dist_state_id"=>$postData['dist_state_id'],"isactive"=>$row->isactive);
       }

     }

     return $response;
  }
  
  function getItemDetailsByID($postData){

    $response = array();
    $subgroup = array('9','20','36');
    $selected_company = $this->session->userdata('root_company');
    
       $this->db->select('group_id,tax,item_code,description,isactive');
       $this->db->where("item_code" ,$postData['ItemID']);
       $this->db->where_not_in('subgroup_id',$subgroup);
       $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $records = $this->db->get(db_prefix() . 'items')->row();
       $records->location = $postData['location'];
       $records->item_taxes = $postData['item_taxes'];
       $records->dist_type_id = $postData['dist_type_id'];
       $records->dist_state_id = $postData['dist_state_id'];

       /*foreach($records as $row ){
           $response->itemdiv = $row->group_id
          $response[] = array("itemdiv"=>$row->group_id,"gst"=>$row->tax,"value"=>$row->item_code,"label"=>$row->description,"location"=>$postData['location'],"item_taxes"=>$postData['item_taxes'],"dist_type_id"=>$postData['dist_type_id'],"dist_state_id"=>$postData['dist_state_id'],"isactive"=>$row->isactive);
       }*/
    return $records;
  }
    
    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get2($id = '')
    {
        $columns             = $this->db->list_fields(db_prefix() . 'items');
        $rateCurrencyColumns = '';
        foreach ($columns as $column) {
            if (strpos($column, 'rate_currency_') !== false) {
                $rateCurrencyColumns .= $column . ',';
            }
        }
        $this->db->select($rateCurrencyColumns . '' . db_prefix() . 'items.id as itemid,rate,
            t1.taxrate as taxrate,t1.id as taxid,t1.name as taxname,
            t2.taxrate as taxrate_2,t2.id as taxid_2,t2.name as taxname_2,
            description,long_description,item_code,group_id,subgroup_id,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items_sub_groups.name as subgroup_name,unit');
        $this->db->from(db_prefix() . 'items');
        $this->db->join('' . db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . 'items.tax', 'left');
        $this->db->join('' . db_prefix() . 'taxes t2', 't2.id = ' . db_prefix() . 'items.tax2', 'left');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
        $this->db->join(db_prefix() . 'items_sub_groups', '' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id', 'left');
        $this->db->order_by('description', 'asc');
        /*if (is_numeric($id)) {
            

            return $this->db->get()->row();
        }*/
        $this->db->where_in(db_prefix() . 'items.group_id', $id);

        return $this->db->get()->result_array();
    }
    
    public function get_rate_master_data_by_id2($item_id, $distributor_id, $state_id)
    {
        $curDate = date('Y-m-d H:i:s');
        $this->db->select('*');
        $this->db->where('state_id', $state_id);
        $this->db->where('distributor_id', $distributor_id);
        $this->db->where('item_id', $item_id);
        $this->db->where('effective_date <=', $curDate);
        $this->db->from(db_prefix() . 'rate_master');
        //$this->db->order_by('name', 'ASC');
        $data =  $this->db->get()->row();
        
        if(empty($data)){
            $this->db->select(db_prefix() . 'ratehistory2.BasicRate AS assigned_rate');
            $this->db->where('StateID', $state_id);
            $this->db->where('DistributorType', $distributor_id);
            $this->db->where('ItemID', $item_id);
            $this->db->where('EffDate <=', $curDate);
            $this->db->order_by('EffDate', 'DESC');
            $this->db->from(db_prefix() . 'ratehistory2');
            $data2 =  $this->db->get()->row();
            return $data2;
        }else{
           return $data;
        }
        
    }

    public function get_grouped()
    {
        $items = [];
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    
    
    // Add New MainItemGroup
    public function SaveMainItemGroup($data,$monitorstock_new)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $UserID = $this->session->userdata('username');
        $monitorstock = explode(",",$monitorstock_new);
        $company_data = $this->GetRootCompany();
        $i = 0;
            foreach ($company_data as $key => $value) {
                $MData = array(
                    'PlantID'=>$value['id'],
                    'GroupID'=>$data['id'],
                    'monitorstock'=>$monitorstock[$i]
                );
                $this->db->insert(db_prefix() . 'items_main_groupsMonitor', $MData);
                $i++;
            }
        $this->db->insert(db_prefix() . 'items_main_groups', $data);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            return true;    
        }else{
            return false;
        }
    }
    
    // Add New Item Division
    public function SaveItemDivision($data)
    {
        $this->db->insert(db_prefix() . 'items_groups', $data);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            return true;    
        }else{
            return false;
        }
    }
    
    // Add New ItemGroup
    public function SaveItemGroup($data)
    {
        $this->db->insert(db_prefix() . 'items_sub_groups', $data);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            return true;    
        }else{
            return false;
        }
    }
    
    
    
    // Update Exiting MainItemGroup
    public function UpdateMainItemGroup($data,$itemGroupID,$monitorstock_new)
    {
        $monitorstock = explode(",",$monitorstock_new);
        $company_data = $this->GetRootCompany();
        $UserID = $this->session->userdata('username');
        $i = 0;
        foreach ($company_data as $key => $value) {
            $MData = array(
                'monitorstock'=>$monitorstock[$i],
                "UserID2"=>$UserID,
                "Lupdate"=>date('Y-m-d H:i:s')
            );
            $this->db->where('PlantID', $value['id']);
            $this->db->where('GroupID', $itemGroupID);
            $this->db->update(db_prefix() . 'items_main_groupsMonitor', $MData);
            $i++;
        }
        $this->db->where('id', $itemGroupID);
        $this->db->update(db_prefix() . 'items_main_groups', $data);
        $UPDATE = $this->db->affected_rows();        
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
    // Update Exiting Item Division
    public function UpdateItemDivision($data,$ItemDivisionID)
    {
        $this->db->where('id', $ItemDivisionID);
        $this->db->update(db_prefix() . 'items_groups', $data);
        $UPDATE = $this->db->affected_rows();        
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
    // Update Exiting ItemGroup
    public function UpdateItemGroup($data,$itemGroupID)
    {
        $this->db->where('id', $itemGroupID);
        $this->db->update(db_prefix() . 'items_sub_groups', $data);
        $UPDATE = $this->db->affected_rows();        
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
    // Get Root Company
    public function GetRootCompany()
    {
        $this->db->select(db_prefix() . 'rootcompany.*');
        $this->db->order_by('id', 'ASC');
        $this->db->from(db_prefix() . 'rootcompany');
        $data =  $this->db->get()->result_array();
        return $data;
    }
    
    // Check StockMaster Record
    public function ChkStockRecord($ItemID,$PlantID,$fy)
    {
        if($PlantID == "1"){
            $GodownID = 'CSPL';
        }else if($PlantID == "2"){
            $GodownID = 'CFF';
        }else if($PlantID == "3"){
            $GodownID = 'CBUPL';
        }
        
        $this->db->select(db_prefix() . 'stockmaster.*');
        $this->db->where('ItemID', $ItemID);
        $this->db->where('PlantID', $PlantID);
        $this->db->where('FY', $fy);
        $this->db->where('GodownID',$GodownID);
        $this->db->from(db_prefix() . 'stockmaster');
        $data =  $this->db->get()->row();
        return $data;
    }
    
    // Check ItemMaster Record
    public function ChkItemRecord($ItemID,$PlantID)
    {
        $this->db->select(db_prefix() . 'items.*');
        $this->db->where('item_code', $ItemID);
        $this->db->where('PlantID', $PlantID);
        $this->db->from(db_prefix() . 'items');
        $data =  $this->db->get()->row();
        return $data;
    }

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['itemid']);
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        $FY = $this->session->userdata('finacial_year');
        $UserID = $this->session->userdata('username');
        $data['PlantID'] = $selected_company;
        $data['tax2'] = 0;
        
        $data['item_code'] = $data['item_code1'];
        unset($data['item_code1']);
        
        unset($data['rate']);
        
        
        $this->db->insert(db_prefix() . 'items', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $data_stock = array(
                'PlantID'=>$selected_company,
                'FY'=>$FY,
                'cnfid'=>1,
                'ItemID'=>$data['item_code'],
                'GodownID'=>$GodownID,
                'EffDate'=>date('Y-m-d H:i:s'),
                'UserId'=>$UserID,
                );
            $this->db->insert(db_prefix() . 'stockmaster', $data_stock);
            hooks()->do_action('item_created', $insert_id);

            log_activity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $fy = $this->session->userdata('finacial_year');
        $itemid = $data['itemid'];
        unset($data['itemid']);
        $opening_stock = $data['opening_stock'];
        unset($data['opening_stock']);

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        if (isset($data['tax']) && $data['tax'] == '') {
            $data['tax'] = null;
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            $data['tax2'] = null;
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $columns = $this->db->list_fields(db_prefix() . 'items');
        $this->load->dbforge();

        foreach ($data as $column => $itemData) {
            if (!in_array($column, $columns) && strpos($column, 'rate_currency_') !== false) {
                $field = [
                        $column => [
                            'type' => 'decimal(15,' . get_decimal_places() . ')',
                            'null' => true,
                        ],
                ];
                $this->dbforge->add_column('items', $field);
            }
        }

        $affectedRows = 0;

        $data = hooks()->apply_filters('before_update_item', $data, $itemid);
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        $this->db->where('item_code', $itemid);
        $this->db->where('PlantID', $selected_company);
        $this->db->update(db_prefix() . 'items', $data);
        
        if($opening_stock !== '' && isset($opening_stock)){
            //stock update
            $stock_data = array(
                "OQty"=>$opening_stock,
                );
            $this->db->where('ItemID', $itemid);
            $this->db->where('PlantID', $selected_company);
            $this->db->where('FY', $fy);
            $this->db->where('GodownID',$GodownID);
            $this->db->update(db_prefix() . 'stockmaster', $stock_data);
        }
            
        if ($this->db->affected_rows() > 0) {
            log_activity('Invoice Item Updated [ID: ' . $itemid . ', ' . $data['description'] . ']');
            $affectedRows++;
        }

        if (isset($custom_fields)) {
            if (handle_custom_fields_post($itemid, $custom_fields, true)) {
                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            hooks()->do_action('item_updated', $itemid);
        }

        return $affectedRows > 0 ? true : false;
    }

    public function search($q)
    {
        $this->db->select('rate, id, description as name, long_description as subtext');
        $this->db->like('description', $q);
        $this->db->or_like('long_description', $q);

        $items = $this->db->get(db_prefix() . 'items')->result_array();

        foreach ($items as $key => $item) {
            $items[$key]['subtext'] = strip_tags(mb_substr($item['subtext'], 0, 200)) . '...';
            $items[$key]['name']    = '(' . app_format_number($item['rate']) . ') ' . $item['name'];
        }

        return $items;
    }

    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'items_pr');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            log_activity('Invoice Item Deleted [ID: ' . $id . ']');

            hooks()->do_action('item_deleted', $id);

            return true;
        }

        return false;
    }

    public function get_groups()
    {
        
        $selected_company = $this->session->userdata('root_company');
        $this->db->order_by('name', 'asc');
        //$this->db->where('PlantID', $selected_company);

        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }
    public function get_custitem_groups($AccountID)
    {
        
        $selected_company = $this->session->userdata('root_company');
        //$this->db->order_by('name', 'asc');
        $this->db->where('plant_assign', $selected_company);
        $this->db->where('AccountID', $AccountID);
        return $this->db->get(db_prefix() . 'accountitemdiv')->result_array();
    }

    public function add_group($data)
    {
        $this->db->insert(db_prefix() . 'items_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Group Created [Name: ' . $data['name'] . ']');

            return $this->db->insert_id();
        }else{
            return false;
        }
        
    }

    public function edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);

            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_groups');
            if ($this->db->affected_rows() > 0) {
                log_activity('Item Group Deleted [Name: ' . $group->name . ']');

                return true;
            }
            return false;
        }

        return false;
    }
    
    
    
    public function get_main_groups()
    {
        //$selected_company = $this->session->userdata('root_company');
        $this->db->order_by('name', 'asc');
        //$this->db->where('PlantID', $selected_company);
        return $this->db->get(db_prefix() . 'items_main_groups')->result_array();
    }
    
    public function add_main_group($data)
    {
        $this->db->insert(db_prefix() . 'items_main_groups', $data);
        log_activity('Items Main Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }
    
    public function edit_main_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_main_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Main Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    
    public function edit_sub_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_sub_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Sub Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    
     public function delete_main_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_main_groups')->row();

        if ($group) {
            /*$this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);
*/
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_main_groups');
            if ($this->db->affected_rows() > 0) {
                log_activity('Item Main Group Deleted [Name: ' . $group->name . ']');

                return true;
            }
            return false;
        }

        return false;
    }
    
     public function delete_sub_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_sub_groups')->row();

        if ($group) {
            /*$this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);
*/
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_sub_groups');
            if ($this->db->affected_rows() > 0) {
                log_activity('Item Sub Group Deleted [Name: ' . $group->name . ']');

                return true;
            }
            return false;
            
        }

        return false;
    }
    
    public function get_sub_groups()
    {
        //$selected_company = $this->session->userdata('root_company');
        $this->db->order_by('name', 'asc');
        //$this->db->where('PlantID', $selected_company);

        return $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
    }
    
    public function get_item_rack()
    {
        $selected_company = $this->session->userdata('root_company');
        $this->db->order_by('RackName', 'asc');
        $this->db->where('PlantID', $selected_company);

        return $this->db->get(db_prefix() . 'rackmaster')->result_array();
    }
    
    public function add_sub_group($data)
    {
        $this->db->insert(db_prefix() . 'items_sub_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Sub Group Created [Name: ' . $data['name'] . ']');

            return $this->db->insert_id();
        }

        return false;
        

        
    }
    
   
    // MainItemGroup Table Data
     public function get_MainItemGroup_data(){
        
        $this->db->select(db_prefix() . 'items_main_groups.*');
        $this->db->from(db_prefix() . 'items_main_groups');
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // ItemDivision Table Data
     public function get_ItemDivision_data(){
        
        $this->db->select(db_prefix() . 'items_groups.*');
        $this->db->from(db_prefix() . 'items_groups');
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // ItemGroup Table Data
     public function get_ItemGroup_data(){
        
        $this->db->select(db_prefix() . 'items_sub_groups.*,'.db_prefix() . 'items_main_groups.name AS MainGroupName');
        $this->db->from(db_prefix() . 'items_sub_groups');
        $this->db->join(db_prefix() . 'items_main_groups', '' . db_prefix() . 'items_main_groups.id = ' . db_prefix() . 'items_sub_groups.main_group_id');
        $this->db->order_by(db_prefix() . 'items_sub_groups.id', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function get_last_recordItemGroup(){
        $this->db->select('*');
        $this->db->from('items_sub_groups');
        $this->db->order_by('id', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
        $ItemGroupRecord =  $this->db->get()->row();
        return $ItemGroupRecord->id;
    }
    
    // MainItemGroup Table Data By ID
     public function getMainItemGroupDetails($ItemGroupID){
        
        $this->db->select(db_prefix() . 'items_main_groups.*');
        $this->db->from(db_prefix() . 'items_main_groups');
        $this->db->where(db_prefix() . 'items_main_groups.id', $ItemGroupID);
        $data = $this->db->get()->row();
        if(empty($data)){
            
        }else{
            $stockMonitor = $this->GetStockMonitor($ItemGroupID);
        $data->Stocks = $stockMonitor;
        }
        
        return $data;
    }
    
    // MinItemGroup Stock Monitor
     public function GetStockMonitor($ItemGroupID){
        
        $this->db->select(db_prefix() . 'items_main_groupsMonitor.*');
        $this->db->from(db_prefix() . 'items_main_groupsMonitor');
        $this->db->where(db_prefix() . 'items_main_groupsMonitor.GroupID', $ItemGroupID);
        $this->db->order_by(db_prefix() . 'items_main_groupsMonitor.PlantID', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // Item Division Table Data By ID
     public function getitemDivisionDetails($ItemDivisionID){
        
        $this->db->select(db_prefix() . 'items_groups.*');
        $this->db->from(db_prefix() . 'items_groups');
        $this->db->where(db_prefix() . 'items_groups.id', $ItemDivisionID);
        return $this->db->get()->row();
    }
    // Last Id For Item Division
    public function get_last_recordItemDevision(){
        $this->db->select('*');
        $this->db->from('items_groups');
        $this->db->order_by('id', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
        $ItemDivisionRecord =  $this->db->get()->row();
        return $ItemDivisionRecord->id;
    }
    
    // ItemGroup Table Data By ID
     public function getItemGroupDetails($ItemGroupID){
        
        $this->db->select(db_prefix() . 'items_sub_groups.*');
        $this->db->from(db_prefix() . 'items_sub_groups');
        $this->db->where(db_prefix() . 'items_sub_groups.id', $ItemGroupID);
        return $this->db->get()->row();
    }
}
