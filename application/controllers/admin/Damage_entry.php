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

    public function AddEdit($id='')
    {   
        $data['title'] = "Add Edit Damage Entry";
         if ($this->input->post()) {
            $data = $this->input->post();
            if($id == ''){
                $success = $this->damage_entry_model->add_damage_entry($data);
                if($success) {
                    set_alert('success', _l('added_successfully'));
                }
            }else{
                $success = $this->damage_entry_model->update_damage_entry($data,$id);
                if($success) {
                    set_alert('success', _l('update_successfully'));
                }
            }
            
            redirect(admin_url('Damage_entry/AddEdit'));
        }
        $data['DamageAccounts'] =  $this->damage_entry_model->GetDamgeAccount();
        $data['item_code'] = $this->damage_entry_model->get_items_code();
        if($id){
            $data['damage_details'] = $this->damage_entry_model->get_damage_entry_details($id);
            $data['damage_entry_detail'] = json_encode($data['damage_details']->ItemList);
        }
        /*echo "<pre>";
        print_r($data['item_code']);*/
        $this->load->view('admin/damage_entry/AddEditDamageEntry', $data);
    }
     public function ItemDetails($ItemID)
     {
        $ItemDetails = $this->damage_entry_model->ItemDetails($ItemID);
        echo json_encode($ItemDetails);
    }
    public function load_data_for_damage_list()
    {
        $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
        );
        $data = $this->damage_entry_model->data_for_damage_list($data);
        echo json_encode($data);
    }
    
    
}