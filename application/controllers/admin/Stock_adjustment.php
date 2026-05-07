<?php

defined('BASEPATH') or exit('No direct script access allowed');

class stock_adjustment extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('stock_a_model');
    }

    /* List all announcements */
    public function index()
    {
       if (!has_permission_new('stock_adjustment', '', 'view')) {
            access_denied('invoices');
        }
        $data['title'] = _l('Stock Adjustment');
         if ($this->input->post()) {
            if (!has_permission_new('stock_adjustment', '', 'create')) {
            access_denied('invoices');
            }
            $data = $this->input->post();
            if ($data['stock_id'] == '') {
                // print_r($data);die;
                $success = $this->stock_a_model->add_stock_aadjustment($data);
                
                if ($success) {
                     set_alert('success', _l('added_successfully'));
                }
                redirect(admin_url('Stock_adjustment'));
            }
        }
        $data['stock_list'] = $this->stock_a_model->get_stock_list();
        $data['vendors'] =  $this->stock_a_model->get_vendor_data();
        $data['item_code'] = $this->stock_a_model->get_items_code();
        $this->load->view('admin/stock_a/manage', $data);
    }
    public function load_data_for_stock_adj()
     {
        $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
          );
      $data = $this->stock_a_model->load_data_for_stock_adj($data);
      echo json_encode($data);
     }
    public function get_vendor_data($id =""){
            $data['vendor'] = $this->stock_a_model->get_data_vendor($id);
            $data['client_details']  = $this->clients_model->get($data['vendor']->AccountID);
            $data['customer_groups_name'] = $this->clients_model->get_customer_groups_name($data['client_details']->DistributorType);
             $data['locations'] =   $this->stock_a_model->get_accountlocations($data['vendor']->AccountID);
        //   echo json_encode([ 
         
        //     'vendor' => $vendor,
        // ]);
         echo json_encode($data);
         
     }
      public function items_change($val,$group_id='',$state='')
      {
        $data['value'] = $this->stock_a_model->items_change($val);
        $selected_company = $this->session->userdata('root_company');
        $data['basic_r'] =   $this->stock_a_model->get_basic_r($data['value']->item_code,$group_id,$state);
        echo json_encode($data);
    }
    public function stock_list($id = ""){
        if (!has_permission_new('stock_adjustment', '', 'view')) {
            access_denied('invoices');
        }
         if ($this->input->post()) {
            $pur_order_data = $this->input->post();
            if (!has_permission_new('stock_adjustment', '', 'edit')) {
                access_denied('invoices');
            }
            $idd = $this->stock_a_model->update_stock_adj($pur_order_data,$id);
             if ($idd) {
                    set_alert('success', _l('updated_successfully', _l('pur_order')));
                }else{
                   set_alert('error', _l('Some thing went wrong', _l('pur_order'))); 
                }
                redirect(admin_url('Stock_adjustment/stock_list/' . $id)); 
         }else{
            $title = "Edit Stock Adj";
        $data['stock_details'] = $this->stock_a_model->get_unique_stock_master($id);
        
        $data['client_details']  = $this->clients_model->get($data['stock_details']->AccountID);
        $data['customer_groups_name'] = $this->clients_model->get_customer_groups_name($data['client_details']->DistributorType);
        $data['locations'] =   $this->stock_a_model->get_accountlocations($data['stock_details']->AccountID);
        $data['total_cases'] = $this->stock_a_model->get_total_cases($id);
        $data['order_detail'] = json_encode($this->stock_a_model->get_stock_order_detail($id,$data['customer_groups_name']->id,$data['client_details']->state));
        
        $data['title'] = $title;
        $data['vendors'] =  $this->stock_a_model->get_vendor_data($data['stock_details']->AccountID);
        $data['item_code'] = $this->stock_a_model->get_items_code();
        // echo "<pre>";
        // print_r($data['order_detail']);
        // die;
        $this->load->view('admin/stock_a/stock_list', $data);  
         }
    }
}
?>