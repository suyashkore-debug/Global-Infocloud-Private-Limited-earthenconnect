<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ItemIDMerge extends AdminController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('ItemIDMerge_model');
    }

    public function index($id='')
    {
        if (!has_permission_new('ItemIDMerge', '', 'edit')) {
            access_denied('invoices');
        }
        $data['title'] = _l('ItemIDMerge');
        $this->load->view('admin/ItemIDMerge/manage', $data);
    }
    public function itemlist_using_itemcode(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->ItemIDMerge_model->getitem_using_itemcode($postData);
        echo json_encode($data);
    }
    
    public function CheckNewItemID(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->ItemIDMerge_model->CheckNewItemID($postData);
        echo json_encode($data);
    }
    
    public function exItemIDDetails(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->ItemIDMerge_model->exItemIDDetails($postData);
        echo json_encode($data);
    }
    
    public function MergeItemID(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->ItemIDMerge_model->modifyItemID($postData);
        echo json_encode($data);
    }
    
}