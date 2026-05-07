<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Year_transfer extends AdminController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('year_transfer_model');
    }

    public function index($id='')
    {
        if (!has_permission_new('year_transfer', '', 'edit')) {
            access_denied('invoices');
        }
        $data['title'] = _l('Year Transfer');
         if ($this->input->post()) {
            if (!has_permission_new('year_transfer', '', 'edit')) {
                access_denied('invoices');
            }
            $data = $this->input->post();
                $success = $this->year_transfer_model->transfer_year($data);
                if ($success = true) {
                    //set_alert('success', "Transfer Successfully");
                    echo "<script>alert('Year transfer processed Successfully')</script>";
                }
                redirect(admin_url('year_transfer'));
        }
        $data['firm'] =  $this->year_transfer_model->get_firm_data();
        
        $this->load->view('admin/year-transfer/manage', $data);
    }
    
}