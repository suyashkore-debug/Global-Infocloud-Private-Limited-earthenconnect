<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Damage_entry extends AdminController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('damage_entry_model');
        $this->load->model('clients_model');
        $this->load->model('stock_a_model');
    }

    public function index($id='')
    {
        
        $data['title'] = _l('Damage entry form');
         if ($this->input->post()) {
            
            $data = $this->input->post();
            
                // print_r($data);die;
                $success = $this->damage_entry_model->add_damage_entry($data);
                
                if ($success) {
                     set_alert('success', _l('added_successfully'));
                }
                redirect(admin_url('damage_entry'));
            
        }
        $data['vendors'] =  $this->damage_entry_model->get_vendor_data();
        $data['item_code'] = $this->damage_entry_model->get_items_code();
        
        /*echo "<pre>";
        print_r($data['item_code']);
        die;*/
        $this->load->view('admin/damage_entry/manage', $data);
    }
     public function items_change($val,$group_id='',$state=''){

        $data['value'] = $this->damage_entry_model->items_change($val);
             $data['basic_r'] =   $this->damage_entry_model->get_basic_r($val,$group_id,$state);
            
              echo json_encode($data);
        
    }
    public function load_data_for_damage_list(){
        $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
          );
      $data = $this->damage_entry_model->data_for_damage_list($data);
      echo json_encode($data);
    }
    public function damage_edit_form($id='')
    {
        
        $data['title'] = _l('Damage entry edit form');
         if ($this->input->post()) {
            
            $data = $this->input->post();
            
                // print_r($data);die;
                $success = $this->damage_entry_model->update_damage_entry($data,$id);
                
                if ($success) {
                     set_alert('success', _l('update_successfully'));
                }
                redirect(admin_url('damage_entry'));
            
        }
        $data['vendors'] =  $this->damage_entry_model->get_vendor_data();
        $data['item_code'] = $this->damage_entry_model->get_items_code();
        $data['damage_details'] = $this->damage_entry_model->get_damage_entry_details($id);
        $data['damage_total_cases'] = $this->damage_entry_model->get_total_cases($id);
        $data['client_details']  = $this->clients_model->get($data['damage_details']->AccountID);
        $data['customer_groups_name'] = $this->clients_model->get_customer_groups_name($data['client_details']->DistributorType);
        $data['locations'] =   $this->stock_a_model->get_accountlocations($data['damage_details']->AccountID);
        $data['damage_entry_detail'] = json_encode($this->damage_entry_model->get_damage_entry_detail_full($id,$data['customer_groups_name']->id,$data['client_details']->state));
        
        $this->load->view('admin/damage_entry/manage', $data);
    }
    
    public function accountlist(){
        $postData = $this->input->post();
        $data = $this->damage_entry_model->getaccounts($postData);
        echo json_encode($data);
    }
    public function get_Account_Details(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $Account_data = $this->damage_entry_model->get_Account_Details($postData);
    
        echo json_encode($Account_data);
    }
     
    
    
}