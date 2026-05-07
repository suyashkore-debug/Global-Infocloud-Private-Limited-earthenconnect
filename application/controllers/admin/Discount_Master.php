<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Discount_Master extends AdminController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('clients_model');
    }

    public function index($id='')
    {
        
        $this->Manage();
    }
    public function Manage($id='')
    {
        
        $data['title'] = _l('Discount Master');
        $data['ItemList'] = array();
        $this->load->view('admin/Discount_Master/ManageNew', $data);
    }
    
}