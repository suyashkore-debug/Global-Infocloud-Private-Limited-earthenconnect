<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Target_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
    function get_staffList1($postData){

     $response = array();
 
     if(isset($postData['search']) ){
       $this->db->select('*');
       $this->db->where("firstname like '%".$postData['search']."%' ");
      //echo $selected_company = $this->session->userdata('root_company'); exit();
      // $this->db->where(db_prefix() . 'staff.PlantID', $selected_company);
	   $this->db->where(db_prefix() . 'staff.active=', '1'); 
       $records = $this->db->get('tblstaff')->result(); 
	  // $aa = $this->db->last_query(); print_r($aa); exit(); 
	   
       foreach($records as $row ){
		   // print_r($row); exit();
          $response[] = array("id"=>$row->staffid,"label"=>$row->firstname);
       }
      
     }

     return $response;
  } 
  
  public function get_staff()
    {
        $this->db->where('active', '1');
        //$this->db->order_by('company', 'ASC');
        $accounts = $this->db->get(db_prefix() . 'staff')->result_array();
        return $accounts;
    }
	
	public function edit_target($id)
		{		
			$this->db->select('*');
			$this->db->from('tbltarget_vs_achievement');
			//$this->db->join('recipe_details', 'recipe.id = recipe_details.rec_id');
			$this->db->where(db_prefix() . 'target_vs_achievement.id', $id);
			return $this->db->get()->result_array();
			//$aa = $this->db->last_query(); print_r($aa); exit();
		}
}