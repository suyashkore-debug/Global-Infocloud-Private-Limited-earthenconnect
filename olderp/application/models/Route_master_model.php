<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Route_master_model extends App_Model
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
    public function get($id = '')
    {
        
        $this->db->select('*');
        $selected_company = $this->session->userdata('root_company');
        $this->db->where(db_prefix() . 'route.PlantID', $selected_company);
        $this->db->from(db_prefix() . 'route');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'route.RouteID', $id);

            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
    
    
    

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data["itemid"]);
        $data["PlantID"] = $this->session->userdata('root_company');
        $next_route_id = $this->last_route_id();
        $data["RouteID"] = $next_route_id->RouteID + 1;
        $this->db->insert(db_prefix() . 'route', $data);
        //$insert_id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            
            log_activity('New Route Added [ID:' . $data["RouteID"] . ', ' . $data['name'] . ']');

            return $data["RouteID"];
        }

        return false;
    }
    
    public function last_route_id($id = '')
    {
        
        $this->db->select('*');
        $selected_company = $this->session->userdata('root_company');
        $this->db->where(db_prefix() . 'route.PlantID', $selected_company);
        $this->db->order_by(db_prefix() . 'route.RouteID', "DESC");
        $this->db->from(db_prefix() . 'route');
        return $this->db->get()->row();
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

        $selected_company = $this->session->userdata('root_company');
        $this->db->where('RouteID', $itemid);
        $this->db->where('PlantID', $selected_company);
        $this->db->update(db_prefix() . 'route', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity(' Route Updated [ID: ' . $itemid . ', ' . $data['name'] . ']');
            $affectedRows++;
        }

        

        return $affectedRows > 0 ? true : false;
    }

    

    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $this->db->where('RouteID', $id);
        $this->db->where('PlantID', $selected_company);
        $this->db->delete(db_prefix() . 'route');
        if ($this->db->affected_rows() > 0) {
            

            log_activity('Route Deleted [ID: ' . $id . ']');

            

            return true;
        }

        return false;
    }

    public function get_company_detail()
     {   
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'rootcompany.*
        FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    public function get_data_table()
    {
        $selected_company = $this->session->userdata('root_company');
       
        $data = $this->db->get_where(db_prefix() . 'route',array('PlantID'=>$selected_company))->result_array();
         return $data;
    }
}
