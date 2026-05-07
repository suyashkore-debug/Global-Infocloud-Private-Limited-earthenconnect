<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Target extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
         
        $this->load->model('target_model'); 
    }  

    /* List all available items */
    public function index()
    {   $this->load->model('target_model');
	 
		$data['title'] = _l('Target');
      //  $this->load->view('admin/target/manage', $data);
        $this->load->view('admin/target/target_list', $data);
		//print_r($data['staff_data']); exit();
    }
	
	
	public function staff_list1(){
    $this->load->model('target_model');
    $postData = $this->input->post();
    // Get data
    $data = $this->target_model->get_staffList1($postData);
    echo json_encode($data);
  }
      public function add()
    {  
        $data = $this->input->post();
         $selected_company = $this->session->userdata('root_company');
		 $loged_id = !DEFINED('CRON') ? get_staff_user_id() : 0;
		 
		 
		 $query = $this->db->get_where('target_vs_achievement', array('staff_id' => $data["staff"],'month'=>$data["month"],'year'=>$data["year"]));

              if( $query->num_rows() > 0 ) {
              //	echo "welcome";
              	//$this->session->set_flashdata('msg', ' Data Already Inserted!');
				set_alert('success', 'Data Already Inserted..');
					redirect(admin_url('target'));

              }
		 
            $targetData = array(
                "staff_id"=>$data["staff"],
				"year"=>$data["year"],
				"month"=>$data["month"],
				"total"=>$data["total"],
				"PlantID"=>$selected_company,
				"user_id" =>$loged_id,
				"created_on"=>date('Y-m-d H:i:s')
                );
         
           $targetRes=$this->db->insert(db_prefix() . 'target_vs_achievement', $targetData);
		 //  $aa = $this->db->last_query(); print_r($aa); exit(); 
		 if($targetRes){
			set_alert('success', 'Target added Successfully..');
			redirect(admin_url('target'));
		 }  
    }
	
	public function manage()
    {    $this->load->model('target_model');
        $data['staff_data'] = $this->target_model->get_staff();
        $data['title'] = _l('Target List');
       // $this->load->view('admin/target/target_list', $data);
        $this->load->view('admin/target/manage', $data);
    }
	
	public function table()
    {
        if (!has_permission('hsnmaster', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('target_table');
    }
	
	public function editTarget($id)
    {  
       $id=$this->uri->segment(4); 
	   $data['editRecipe_details'] = $this->target_model->edit_target($id);
	   $data['staff_data'] = $this->target_model->get_staff();

       $data['title'] = _l('Edit Traget');
       $this->load->view('admin/target/edit_target', $data); 
       $selected_company = $this->session->userdata('root_company');
	   $loged_id = !DEFINED('CRON') ? get_staff_user_id() : 0;
	   $tid = $this->input->post('id');
	   
	   if ($this->input->post('id')) {
       $data = $this->input->post();

		$update_data = array(
           "staff_id"=>$data["staff"],
	       "year"=>$data["year"],
		   "month"=>$data["month"],
		   "total"=>$data["total"],
		   "PlantID"=>$selected_company,
		   "user_id1" =>$loged_id,
		   "updated_on"=>date('Y-m-d H:i:s')
          );
		//print_r($update_data); exit();
		$multiClause = array('id' => $tid);
		$this->db->where($multiClause);		
		$query = $this->db->update(db_prefix() . 'target_vs_achievement', $update_data);
		//$aa = $this->db->last_query(); print_r($aa); exit(); 
		
		set_alert('success', 'Target Updated Successfully..');
        redirect(admin_url('target')); 	 
	   }
    }
	
 
}