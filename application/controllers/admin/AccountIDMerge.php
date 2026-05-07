<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AccountIDMerge extends AdminController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('AccountIDMerge_model');
    }

    public function index($id='')
    {
        if (!has_permission_new('AccountIDMerge', '', 'edit')) {
            access_denied('invoices');
        }
        $data['title'] = _l('AccountIDMerge');
        $this->load->view('admin/AccountIDMerge/manage', $data);
    }
    public function AccountlistByAccountID(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->AccountIDMerge_model->AccountlistByAccountID($postData);
        echo json_encode($data);
    }
    
    public function CheckNewAccountID(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->AccountIDMerge_model->CheckNewAccountID($postData);
        echo json_encode($data);
    }
    
    public function exAccountIDDetails(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->AccountIDMerge_model->exAccountIDDetails($postData);
        echo json_encode($data);
    }
    
    public function MergeAccountID(){
        
    // POST data
        $postData = $this->input->post();
    // Get data
        $data = $this->AccountIDMerge_model->modifyItemID2($postData);
        echo json_encode($data);
    }
    
}