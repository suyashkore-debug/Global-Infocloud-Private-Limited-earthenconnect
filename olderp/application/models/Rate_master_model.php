<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rate_master_model extends App_Model
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
    public function get($id = '',$state_id = '',$distributor_id = '')
    {
        $columns             = $this->db->list_fields(db_prefix() . 'items');
        $rateCurrencyColumns = '';
        foreach ($columns as $column) {
            if (strpos($column, 'rate_currency_') !== false) {
                $rateCurrencyColumns .= $column . ',';
            }
        }
        $this->db->select($rateCurrencyColumns . '' . db_prefix() . 'items.id as itemid, rate,
            t1.taxrate as taxrate,t1.id as taxid,t1.name as taxname,r1.assigned_rate as new_rate,r1.id as rate_master_id,
            description,long_description,group_id,item_code,subgroup_id,' . db_prefix() . 'items_groups.name as group_name, '. db_prefix() . 'items_sub_groups.name as subgroup_name,unit');
        $this->db->from(db_prefix() . 'items');
        $this->db->join('' . db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . 'items.tax', 'left');
        $this->db->join('' . db_prefix() . 'rate_master r1', 'r1.item_id = ' . db_prefix() . 'items.item_code', 'left');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
        $this->db->join(db_prefix() . 'items_sub_groups', '' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id', 'left');
        $this->db->order_by('description', 'asc');
        //if (is_numeric($id)) {
            $this->db->where('item_code', $id);
            $selected_company = $this->session->userdata('root_company');
            $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
            $this->db->where('r1.PlantID', $selected_company);
            $this->db->where('r1.state_id', $state_id);
            $this->db->where('r1.distributor_id', $distributor_id);

            return $this->db->get()->row();
        //}

        //return $this->db->get()->result_array();
    }
    
    public function table_data($data){
      
        $state_id = $data['state_id'];
        $distributor_id = $data['distributor_id'];
        $this->db->select(db_prefix() . 'items.id as itemid, rate,
            t1.name as taxname_1,t1.id as tax_id_1,t1.taxrate as taxrate, t3.assigned_rate as assigned_2,t3.item_id as rate_id, t3.item_id as item_id_2,t3.id as rate_master_id,  t3.state_id as state_id_2, t3.distributor_id as distributor_id_2, t3.groups_id as groups_id_2,
            description,long_description,group_id,item_code,subgroup_id,' . db_prefix() . 'items_groups.name as group_name, '. db_prefix() . 'items_sub_groups.name as subgroup_name,unit,t3.effective_date as effective_date_2');
        $this->db->from(db_prefix() . 'items');
        $this->db->join('' . db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . 'items.tax', 'left');
        $this->db->join('' . db_prefix() . 'rate_master t3', 't3.item_id = ' . db_prefix() . 'items.item_code', 'left');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
        $this->db->join(db_prefix() . 'items_sub_groups', '' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id', 'left');
        $this->db->order_by('description', 'asc');
        //if (is_numeric($id)) {
            // $this->db->where('item_code', $id);
            $selected_company = $this->session->userdata('root_company');
            $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
            $this->db->where('t3.PlantID', $selected_company);
            $this->db->where('t3.state_id', $state_id);
            $this->db->where('t3.distributor_id', $distributor_id);
            $this->db->order_by(db_prefix() . 'items.item_code', "ASC");

            return $this->db->get()->result_array();
    
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
    
     public function get_state()
    {
        $this->db->select('*');
        $this->db->where('country_id', '1');
        $this->db->from(db_prefix() . 'xx_statelist');
        $this->db->order_by('state_name', 'ASC');

        return $this->db->get()->result_array();
    }
    
     public function get_rate_master_data_by_id($state_id,$distributor_id)
    {
        $this->db->select('*');
        $this->db->where('state_id', $state_id);
        $this->db->where('distributor_id', $distributor_id);
        $this->db->from(db_prefix() . 'rate_master');
        //$this->db->order_by('name', 'ASC');

        return $this->db->get()->row_array();
    }
    
     /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add_rate_master($data)
    {
        
        $this->db->insert(db_prefix() . 'rate_master', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            

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
    public function edit_rate_master($data)
    {
        $state_id = $data['state_id'];
        $distributor_id = $data['distributor_id'];
        $id = $data['rate_master_id'];
        $item_code = $data['item_code'];
        unset($data["item_code"]);
        unset($data["state_id"]);
        unset($data["distributor_id"]);
        unset($data['rate_master_id']);
        
        $data = hooks()->apply_filters('before_update_item', $data, $itemid);
        $user_id = $this->session->userdata('username');
        $selected_company = $this->session->userdata('root_company');
        $data["UserID2"] = $user_id;
        $data["Lupdate"] = date('Y-m-d H:i:s');
        $this->db->select('*');
        $this->db->where('state_id', $state_id);
        $this->db->where('distributor_id', $distributor_id);
        $this->db->where('id', $id);
        $this->db->from(db_prefix() . 'rate_master');
        $data_rate_master =  $this->db->get()->row_array();
        
        $data_insert = array(
            'PlantID' => $data_rate_master['PlantID'],
            'DistributorType' => $data_rate_master['distributor_id'],
            'ItemID' => $data_rate_master['item_id'],
            'BasicRate' => $data_rate_master['assigned_rate'],
            'SaleRate' => $data_rate_master['SaleRate'],
            'EffDate' => $data_rate_master['effective_date'],
            'UserId' => $data_rate_master['UserId'],
            'StateID' => $data_rate_master['state_id'],
            'gst' => $data_rate_master['gst'],
            'UserID2' => $user_id,
            'Lupdate' => date('Y-m-d H:i:s'),
            );
           
            $gst_amt = ($data['assigned_rate'] /100) * $data_rate_master['gst'];
           $data["SaleRate"] = $gst_amt + $data['assigned_rate'];
           $data["Lupdate"] = $data["effective_date"];
           $data["UserID2"] = $this->session->userdata('username');
        
        $this->db->where('state_id', $state_id);
        $this->db->where('distributor_id', $distributor_id);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'rate_master', $data);
       
        if ($this->db->affected_rows() > 0) {
            
              $this->db->insert(db_prefix() . 'ratehistory2', $data_insert);   
            
            log_activity('Invoice Item Updated [ID: ' . $rate_master_id . ', ' . $data['description'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['itemid']);
        if ($data['tax'] == '') {
            unset($data['tax']);
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            unset($data['tax2']);
        }

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
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

        $this->db->insert(db_prefix() . 'items', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields, true);
            }

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
        $itemid = $data['itemid'];
        unset($data['itemid']);

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

        $this->db->where('id', $itemid);
        $this->db->update(db_prefix() . 'items', $data);
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
        $this->db->delete(db_prefix() . 'rate_master');
        if ($this->db->affected_rows() > 0) {
            /*$this->db->where('relid', $id);
            $this->db->where('fieldto', 'items_pr');
            $this->db->delete(db_prefix() . 'customfieldsvalues');*/

            log_activity('Rate Item Deleted [ID: ' . $id . ']');

            hooks()->do_action('item_deleted', $id);

            return true;
        }

        return false;
    }

    public function get_groups()
    {
        //$selected_company = $this->session->userdata('root_company');
        //$this->db->where('PlantID', $selected_company);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }

    public function add_group($data)
    {
        $this->db->insert(db_prefix() . 'items_groups', $data);
        log_activity('Items Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
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

            log_activity('Item Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
    
    
    public function get_main_groups()
    {
        //$selected_company = $this->session->userdata('root_company');
         //$this->db->where('PlantID', $selected_company);
        $this->db->order_by('name', 'asc');

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

            log_activity('Item Main Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
    public function get_sub_groups()
    {
        //$selected_company = $this->session->userdata('root_company');
        //$this->db->where('PlantID', $selected_company);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
    }
    
    public function add_sub_group($data)
    {
        $this->db->insert(db_prefix() . 'items_sub_groups', $data);
        log_activity('Items Sub Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }
}
