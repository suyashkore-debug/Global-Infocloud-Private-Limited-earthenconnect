<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tour_model extends App_Model
{
    private $contact_columns;

    public function __construct()
    {
        parent::__construct();

        $this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);

        $this->load->model(['client_vault_entries_model', 'client_groups_model', 'statement_model']);
    }

    /**
     * Get client object based on passed clientid if not passed clientid return array of all clients
     * @param  mixed $id    client id
     * @param  array  $where
     * @return mixed
     */
    public function get()
    {
        
        return $this->db->get(db_prefix() . 'tour')->result_array();
    }
    
    public function table_data($data){
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
           
            $from_date = to_sql_date( $data['from_date']);
            $to_date = to_sql_date($data['to_date']);
            $state = $data['state'];
            
            $this->db->select(db_prefix() . 'tour.*,tblstaff.firstname,tblstaff.lastname,tblxx_citylist.city_name');
            $this->db->from(db_prefix() . 'tour');
            $this->db->join(db_prefix() .'staff', db_prefix() .'staff.staffid = '.db_prefix() .'tour.staff_id AND '.db_prefix().'staff.PlantID='.db_prefix().'tour.PlantID');
            $this->db->join(db_prefix() .'xx_citylist', db_prefix() .'xx_citylist.id = '.db_prefix() .'tour.city ');
			$this->db->where(db_prefix() . 'tour.PlantID', $selected_company);
			$this->db->where( db_prefix() . 'tour.start_date BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			if($data['state'] != ''){
			   	$this->db->where(db_prefix() . 'tour.state', $state);
			}
			$this->db->order_by( db_prefix() .'tour.id','DESC');
			return $this->db->get()->result_array();
			 
    }
}
