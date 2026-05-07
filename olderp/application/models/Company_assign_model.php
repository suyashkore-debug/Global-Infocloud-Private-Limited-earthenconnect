<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Company_assign_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get All Job Position
     
     */
    public function get_all_staff($id = '')
    {
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'staff.staffid', $id);

            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
    
    //-----------------------------------------------------
	public function get_staff_by_id($id){
		
		$this->db->select('*');
        $this->db->from(db_prefix() . 'staff');
        
        $this->db->where(db_prefix() . 'staff.staffid', $id);

        $result = $this->db->get()->row();
        $compant_data = $result->staff_comp;
		$compant_data_new = unserialize($compant_data);
		
		$this->db->select('*');
       $this->db->where_in('id', $compant_data_new);
       $records = $this->db->get(db_prefix() . 'rootcompany')->result();
       return $records;
		
        
	}
	
	//-----------------------------------------------------
	public function get_distributor_by_company($plant_id,$staff_id){
	    
		
    /*    $this->db->select('*');
       $this->db->where('company_id', $company_ids);
       $this->db->where('staff_id', $staff_id);
       $records = $this->db->get(db_prefix() . 'customer_admins')->result();
       if($records){
           $distributor_ids = array();
       foreach ($records as $key => $value) {
        # code...
        array_push($distributor_ids, $value->customer_id);
       }
       
       $this->db->select('*');
       $this->db->where_in('userid', $distributor_ids);
       
       $records2 = $this->db->get(db_prefix() . 'clients')->result();
       
       return $records2;
       }else {
           return false;
       }*/
       
       $ss = 'SELECT '.db_prefix().'clients.*,
        (SELECT GROUP_CONCAT(name SEPARATOR ",") FROM '.db_prefix().'customers_groups WHERE '.db_prefix().'customers_groups.id = '.db_prefix().'clients.DistributorType) as customerGroups
FROM tblclients WHERE '.db_prefix().'clients.AccountID IN (SELECT customer_id FROM tblcustomer_admins WHERE staff_id = '.$staff_id.' AND company_id = '.$plant_id.') AND PlantID ='.$plant_id;

                $customer_data = $this->db->query($ss)->result_array();
       return $customer_data;
	}
	
	//-----------------------------------------------------
	public function get_staff_by_company($company_ids){
	    
		
        $this->db->select('*');
       
       $records = $this->db->get(db_prefix() . 'staff')->result();
       $staff_ids = array();
       foreach ($records as $key => $value) {
        # code...
        $staff_comp = $value->staff_comp;
        $staffcompany_ids = unserialize($staff_comp);
        if(in_array($company_ids, $staffcompany_ids)){
            
            array_push($staff_ids, $value->staffid);
        }
        
       }
       
       $this->db->select('*');
       $this->db->where_in('staffid', $staff_ids);
       
       $records2 = $this->db->get(db_prefix() . 'staff')->result();
       
       return $records2;
	}
    
    //-----------------------------------------------------
	public function get_transfer_staff($id,$job_id){
		/*$query = $this->db->get_where('tblstaff', array('job_position' => $job_id));
		 $result = $query->result_array();*/
		 $this->db->select('*');
  $this->db->from('tblstaff');
  $this->db->where('job_position =' , $job_id);
  $this->db->where('staffid !=' , $id);
  $query = $this->db->get();
  
  return $query->result();
		 
	}
	
	//-----------------------------------------------------
	public function get_to_staff_detail($id){
	
	  $this->db->select('*');
      $this->db->from('tblstaff');
      //$this->db->where('job_position =' , $job_id);
      $this->db->where('staffid =' , $id);
      $query = $this->db->get();
      
      return $query->row();
		 
	}
	
	public function uptate_salse_person($data)
    {
            
            $from_staff = $data['all_staff'];
            $to_staff = $data['transfer_to'];
            $company_select = $data['company_select'];
            $distributor_select = $data['distributor_select'];
            
            /*print_r($distributor_select);
            echo "<br>";
            echo $from_staff;
            echo "<br>";
            echo $to_staff;
            echo "<br>";
            echo $company_select;
            die;
            */
        foreach ($distributor_select as $value) {
                # code...
                $mapping_data = array(
                    "staff_id" =>$to_staff,
                    "customer_id" =>$value
                );
                
                
                $this->db->where('staff_id', $from_staff);
                $this->db->where('company_id', $company_select);
                $this->db->where('customer_id', $value);
                $this->db->update(db_prefix() . 'customer_admins', $mapping_data);
            }
        if ($this->db->affected_rows() > 0) {
            log_activity(' Salse Person Updated [ID: ' . $to_staff . ', ' . $company_select . ']');
            $affectedRows++;
        }

        

        return $affectedRows > 0 ? true : false;
    }
    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'hsn');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'hsn.id', $id);

            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
    
    function getitem($postData){

     $response = array();

     if(isset($postData['search']) ){
       // Select record
       $item_div_id = explode(" ",$postData['item_divistion']);
       //if($postData['item_taxes']=="Non-Taxable"){
           $non_taxable_id = get_zerotaxrate_id();
       //}
       
       $this->db->select('*');
       $this->db->where("description like '%".$postData['search']."%' ");
       $this->db->where_in('group_id', $item_div_id);
       if($postData['item_taxes']=="Non-Taxable"){
           $this->db->where('tax', $non_taxable_id->id);
       } 
       if($postData['item_taxes']=="Taxable"){
           
           $this->db->where('tax !=' , $non_taxable_id->id);
       }
       $records = $this->db->get(db_prefix() . 'items')->result();

       foreach($records as $row ){
          $response[] = array("value"=>$row->id,"label"=>$row->description,"location"=>$postData['location'],"dist_type_id"=>$postData['dist_type_id'],"dist_state_id"=>$postData['dist_state_id']);
       }

     }

     return $response;
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
        $this->db->select('*');
        
        $this->db->where('state_id', $state_id);
        $this->db->where('distributor_id', $distributor_id);
        $this->db->where('item_id', $item_id);
        $this->db->from(db_prefix() . 'rate_master');
        //$this->db->order_by('name', 'ASC');

        return $this->db->get()->row();
        
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

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data["itemid"]);
        $data["created_date"] = date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'hsn', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            

           

            log_activity('New HSN Added [ID:' . $insert_id . ', ' . $data['name'] . ']');

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

        $data["created_date"] = date('Y-m-d h:i:s');
        $this->db->where('id', $itemid);
        $this->db->update(db_prefix() . 'hsn', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity(' HSN Code Updated [ID: ' . $itemid . ', ' . $data['name'] . ']');
            $affectedRows++;
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
        $this->db->delete(db_prefix() . 'hsn');
        if ($this->db->affected_rows() > 0) {
            

            log_activity('HSN Code Deleted [ID: ' . $id . ']');

            

            return true;
        }

        return false;
    }

    public function get_groups()
    {
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

            log_activity('Item Main Group Deleted [Name: ' . $group->name . ']');

            return true;
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

            log_activity('Item Sub Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
    public function get_sub_groups()
    {
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
