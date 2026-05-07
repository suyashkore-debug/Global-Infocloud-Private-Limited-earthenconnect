<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sale_return extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sale_return_model');
        $this->load->model('challan_model');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission_new('sale_return', '', 'view')) {
            access_denied('Sale return');
        }
        
        $data['sale_returns'] = $this->Sale_return_model->SaleRtnList();
        /*echo "<pre>";
        print_r($data['sale_returns']);
        die;*/
        $data['title'] = "Sales Return";
        $this->load->view('admin/sale_return/manage', $data);
    }
    
    public function load_data_for_salertn()
     {
        $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
          );
      $data = $this->Sale_return_model->load_data_for_salertn($data);
      echo json_encode($data);
     }
    
    public function edit($id = '')
    {
        if ($this->input->post()) {
            if (!has_permission_new('sale_return', '', 'edit')) {
                access_denied('sale return');
            }
            $data = $this->input->post();
            $success = $this->Sale_return_model->update_sale_return($data);
                if ($success == "true") {
                        set_alert('success', 'Sale return update Successfully..');
                        redirect(admin_url('sale_return/edit/'.$data["ex_sale_return_id"]));
                }
            }
        
        if($id != ''){
            $data['sale_return'] = $this->Sale_return_model->get_sale_return_details($id);
        }
        
        $data['sale_returns'] = $this->Sale_return_model->SaleRtnList();
        /*echo "<pre>";
        print_r($data['sale_returns']);
        die;*/
        $this->load->model('sale_reports_model');
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $data['title'] = "Sales Return";
        $this->load->view('admin/sale_return/manage', $data);
    }
    
    public function accountlist(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $data = $this->Sale_return_model->getaccounts($postData);
    
        echo json_encode($data);
    }
    
     public function get_Account_Details(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $Account_data = $this->Sale_return_model->get_Account_Details($postData);
    
        echo json_encode($Account_data);
    }
  
  public function transaction_list(){
        
    // POST data
    $postData = $this->input->post();

    // Get data
    $data = $this->Sale_return_model->getransaction($postData);

    echo json_encode($data);
  }
  
  public function itemlist(){
        
    // POST data
    $postData = $this->input->post();

    // Get data
    $data = $this->Sale_return_model->getitems($postData);

    echo json_encode($data);
  }
  
  public function ItemDetails(){
        
    // POST data
    $postData = $this->input->post();
    // Get data
    $data = $this->Sale_return_model->getitemsDetails($postData);

    echo json_encode($data);
  }
    
  public function get_sale_item(){
        
    // POST data
    $postData = $this->input->post();

    // Get data
    $data = $this->Sale_return_model->getsale_item_list($postData);

    echo json_encode($data);
  }
  
  /* Get item by id / ajax */
    public function get_bill_id($item_code,$act_code)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->Sale_return_model->get_bill($item_code,$act_code);
            
            echo json_encode($item);
        }
    }

    /* Get bill details by id / ajax */
    public function get_bill_detail($bill_id,$item_code)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->Sale_return_model->get_bill_details($bill_id,$item_code);
            
            echo json_encode($item);
        }
    }
    
    
    public function add()
    {
        $data = $this->input->post();
        if (!has_permission_new('sale_return', '', 'create')) {
            access_denied('invoices');
        }
        if($data["act_name"] == "" || $data["act_name"] == null || $data["net_total_val"]=="0.00"){
            set_alert('warning', "please add atleast one item..");
            redirect(admin_url('sale_return'));
        }
        $date = to_sql_date($data['sale_return_date'])." ".date('H:i:s');
        $month = substr($date,5,2);
        if($data["type_select"] =="fresh"){
            $salertnType = "Fresh";
        }else{
            $salertnType = "Damage";
        }
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
                $GodownID = 'CSPL';
            }else if($selected_company == "2"){
                $GodownID = 'CFF';
            }else if($selected_company == "3"){
                $GodownID = 'CBUPL';
            }
            
        if($selected_company == 1){  
            $new_sale_returnNumber = get_option('next_sale_return_number_for_cspl');
        }elseif($selected_company == 2){
            $new_sale_returnNumber = get_option('next_sale_return_number_for_cff');
        }elseif($selected_company == 3){
            $new_sale_returnNumber = get_option('next_sale_return_number_for_cbu');
        }elseif($selected_company == 4){
            $new_sale_returnNumber = get_option('next_sale_return_number_for_cbupl');
        }
        
        $Billno = "SRT".$fy.$new_sale_returnNumber;
       
        $roundoff = round($data["net_total_val"]);
        $rnd_amt = $roundoff - $data["net_total_val"];
        (int) $count = $data["countof_record"]; 
        $ItCount = $count - 1;
        $INSERT = 0;
        $sale_rtn = array(
            "FY"=>$fy,
            "PlantID"=>$selected_company,
            "BT"=>"T",
            "SalesRtnID"=>$Billno,
            "Transdate"=>$date,
            "AccountID"=>$data["act_name"],
            "PayType"=>"C",
            "SaleAmt"=>$data["gross_total_val"],
            "DiscAmt"=>0.00,
            "cgstamt"=>$data["cgst_total_val"],
            "sgstamt"=>$data["sgst_total_val"],
            "igstamt"=>$data["igst_total_val"],
            "BillAmt"=>$data["net_total_val"],
            "RndAmt"=>round($data["net_total_val"]),
            "SalesRtnTypeID"=>$salertnType,
            "ItCount"=>$ItCount,
            "passedfrom"=>"SALESRTN",
            "Userid"=>$this->session->userdata('username'),
        );
            
        $this->db->insert(db_prefix() . 'salesreturn', $sale_rtn);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            $this->increment_next_number();
            for($i=1; $i<$count; $i++) { 
                $itemid = "item_code".$i;
                $hsn_val = "hsn_val".$i;
                $cgst_per_val = "cgst_per_val".$i;
                $cgst_amt_val = "cgst_amt_val".$i;
                $sgst_per_val = "sgst_per_val".$i;
                $sgst_amt_val = "sgst_amt_val".$i;
                $igst_per_val = "igst_per_val".$i;
                $igst_amt_val = "igst_amt_val".$i;
                $total_amt_val = "total_amt_val".$i;
                $basic_rate_val = "basic_rate_val".$i;
                $return_qty = "return_qty".$i;
                $pack_val = "pack_val".$i;
                $sale_rate_val = "sale_rate_val".$i;
                $saleAmtPerItem =  $data[$total_amt_val] -  $data[$sgst_amt_val] - $data[$sgst_amt_val] - $data[$igst_amt_val];
                $salertn_details = array(
                    "PlantID"=>$selected_company,
                    "FY"=>$fy,
                    "cnfid"=>"1",
                    "OrderID"=>$Billno,
                    "GodownID"=>$GodownID,
                    "TransDate"=>$date,
                    "TransDate2"=>$date,
                    "BillID"=>$data["tax_id"],
                    "TransID"=>$data["tax_id"],
                    "TType"=>"R",
                    "TType2"=>$salertnType,
                    "AccountID"=>$data["act_name"],
                    "ItemID"=>$data[$itemid],
                    "CaseQty"=>$data[$pack_val],
                    "SaleRate"=>$data[$sale_rate_val],
                    "BasicRate"=>$data[$basic_rate_val],
                    "SuppliedIn"=>"CS",
                    "BilledQty"=>$data[$return_qty],
                    "DiscPerc"=>"0.00",
                    "DiscAmt"=>"0.00",
                    "cgst"=>$data[$cgst_per_val],
                    "cgstamt"=>$data[$cgst_amt_val],
                    "sgst"=>$data[$sgst_per_val],
                    "sgstamt"=>$data[$sgst_amt_val],
                    "igst"=>$data[$igst_per_val],
                    "igstamt"=>$data[$igst_amt_val],
                    "ChallanAmt"=>$saleAmtPerItem,
                    "NetChallanAmt"=>$data[$total_amt_val],
                    "Ordinalno"=>$i,
                    "UserID"=>$this->session->userdata('username'),
                );
                $this->db->insert(db_prefix() . 'history', $salertn_details);
                if($salertnType == "Fresh"){
                    // stock update
                    $stock_data = $this->Sale_return_model->get_stock_item($data[$itemid]);
                    $item_stock= $stock_data->SRQty;
                    $new_stock = $item_stock + $data[$return_qty];
                    $this->db->where('PlantID', $selected_company);
                    $this->db->where('FY', $fy);  
                    $this->db->where('ItemID', $data[$itemid]);
                    $this->db->update(db_prefix() . 'stockmaster', [
                                        'SRQty' => $new_stock,
                                    ]);
                } 
            } 
            $ord_no = 1;
            $credit_ledger = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"C",
                "AccountID"=>$data['act_name'],
                "Amount"=>round($data["net_total_val"]),
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $credit_ledger);
            $ord_no++;
        
            if($month == "01"){
               $m = 11; 
            }
            if($month == "02"){
               $m = 12; 
            }
            if($month == "03"){
               $m = 13; 
            }
            if($month == "04"){
               $m = 2; 
            }
            if($month == "05"){
               $m = 3; 
            }
            if($month == "06"){
               $m = 4; 
            }
            if($month == "07"){
               $m = 5; 
            }
            if($month == "08"){
               $m = 6; 
            }
            if($month == "09"){
               $m = 7; 
            }
            if($month == "10"){
               $m = 8; 
            }
            if($month == "11"){
               $m = 9; 
            }
            if($month == "12"){
               $m = 10; 
            }
            $mm = "BAL".$m;
            
            $get_account_bal = $this->get_acc_bal($data['act_name']);
            if(empty($get_account_bal)){
                $Bal = 0.00 - $data["net_total_val"];
                    $insertActBal = array(
                        'PlantID'=>$selected_company,
                        'AccountID'=>$data['act_name'],
                        'FY'=>$fy,
                        $mm=>$Bal,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal);
            }else{
                $current_bal = $get_account_bal->$mm;
                $new_bal = $current_bal - round($data["net_total_val"]);
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $fy);
                $this->db->where('AccountID', $data['act_name']);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm => $new_bal,
                                ]);
            }
            
            $debit_ledger = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"D",
                "AccountID"=>"SALE",
                "Amount"=>$data['gross_total_val'],
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger);
            $ord_no++;
            
            $get_account_bal1 = $this->get_acc_bal('SALE');
            if(empty($get_account_bal1)){
                $Bal1 = $data["gross_total_val"];
                    $insertActBal1 = array(
                        'PlantID'=>$selected_company,
                        'AccountID'=>"SALE",
                        'FY'=>$fy,
                        $mm=>$Bal1,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal1);
            }else{
                $current_bal1 = $get_account_bal1->$mm;
                $new_bal1 = $current_bal1 + $data["gross_total_val"];
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->LIKE('AccountID', "SALE");
                $this->db->update(db_prefix() . 'accountbalances', [
                                            $mm => $new_bal1,
                                        ]);
            }
            
        if($data['igst_total_val']=="0.00"){
            
            $debit_ledger_sgst = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"D",
                "AccountID"=>"SGST",
                "Amount"=>$data['sgst_total_val'],
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_sgst);
            $ord_no++;
            
            $get_account_bal2 = $this->get_acc_bal('SGST');
            if(empty($get_account_bal2)){
                $Bal2 = $data["sgst_total_val"];
                    $insertActBal2 = array(
                        'PlantID'=>$selected_company,
                        'AccountID'=>"SGST",
                        'FY'=>$fy,
                        $mm=>$Bal2,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal2);
            }else{
                $current_bal2 = $get_account_bal2->$mm;
                $new_bal2 = $current_bal2 + $data["sgst_total_val"];
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->LIKE('AccountID', "SGST");
                $this->db->update(db_prefix() . 'accountbalances', [
                                            $mm => $new_bal2,
                                        ]);
            }
            $debit_ledger_cgst = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"D",
                "AccountID"=>"CGST",
                "Amount"=>$data['cgst_total_val'],
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_cgst);
            $ord_no++;
            $get_account_bal3 = $this->get_acc_bal('CGST');
            if(empty($get_account_bal3)){
                $Bal3 = $data["cgst_total_val"];
                    $insertActBal3 = array(
                        'PlantID'=>$selected_company,
                        'AccountID'=>"CGST",
                        'FY'=>$fy,
                        $mm=>$Bal3,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal3);
            }else{
                $current_bal3 = $get_account_bal3->$mm;
                $new_bal3 = $current_bal3 + $data["cgst_total_val"];
            
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->LIKE('AccountID', "CGST");
                $this->db->update(db_prefix() . 'accountbalances', [
                                            $mm => $new_bal3,
                                        ]);
            }
        }else{
            $debit_ledger_igst = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"D",
                "AccountID"=>"IGST",
                "Amount"=>$data['igst_total_val'],
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_igst);
            $ord_no++;
            $get_account_bal4 = $this->get_acc_bal('IGST');
            if(empty($get_account_bal4)){
                $Bal4 = $data["igst_total_val"];
                    $insertActBal4 = array(
                        'PlantID'=>$selected_company,
                        'AccountID'=>"IGST",
                        'FY'=>$fy,
                        $mm=>$Bal4,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal4);
            }else{
                $current_bal4 = $get_account_bal4->$mm;
                $new_bal4 = $current_bal4 + $data["igst_total_val"];
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->LIKE('AccountID', "IGST");
                $this->db->update(db_prefix() . 'accountbalances', [
                                            $mm => $new_bal4,
                                        ]);
            }
        }
        
            
        if($rnd_amt > 0 || $rnd_amt < 0){
            
            $debit_ledger_roundoff = array(
                "FY"=>$fy,
                "PlantID"=>$selected_company,
                "VoucherID"=>$Billno,
                "Transdate"=>$date,
                "TransDate2"=>date('Y-m-d H:i:s'),
                "TType"=>"D",
                "AccountID"=>"ROUNDOFF",
                "Amount"=>$rnd_amt,
                "Narration"=>"By SalesRtnID ".$Billno,
                "PassedFrom"=>"SALESRTN",
                "OrdinalNo"=>$ord_no,
                "UserID"=>$this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_roundoff);
            $ord_no++;
            $get_account_bal5 = $this->get_acc_bal('ROUNDOFF');
            if(empty($get_account_bal5)){
                $Bal5 = 0.00 - $rnd_amt;
                    $insertActBal5 = array(
                        'PlantID'=>$selected_company,
                        'AccountID'=>"ROUNDOFF",
                        'FY'=>$fy,
                        $mm=>$Bal5,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal5);
            }else{
                $current_bal5 = $get_account_bal5->$mm;
                $new_bal5 = $current_bal5 + $rnd_amt;
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->LIKE('AccountID', "ROUNDOFF");
                $this->db->update(db_prefix() . 'accountbalances', [
                                            $mm => $new_bal5,
                                        ]);
            }
        }
    }
        set_alert('success', 'Sale return added Successfully..');
        redirect(admin_url('sale_return'));
    }
    
    /**
     * delete Sale return entry
     * @param  integer $id
     * @return
     */
    public function delete_sale_entry($id)
    {
        if (!has_permission_new('sale_return', '', 'delete')) {
                access_denied('sale return');
        }
        $success = $this->Sale_return_model->delete_sale_entry($id);
        $message = '';
        if ($success) {
            $message = _l('deleted');
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }
        redirect(admin_url('sale_return'));
    }
    
    public function cancel_sale_entry($id)
    {
        if (!has_permission_new('sale_return', '', 'edit')) {
                access_denied('sale return');
        }
        
        $success = $this->Sale_return_model->cancel_sale_entry($id);
        $message = '';
        if ($success) {
            $message = _l('cancel sale return');
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }
        redirect(admin_url('sale_return/edit/'.$id));
    }
    
    public function get_acc_bal($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('AccountID', $id);

        return $this->db->get(db_prefix() . 'accountbalances')->row();
    }
    
    /**
     * @since  2.7.0
     *
     * Increment the Receipts next nubmer
     *
     * @return void
     */
    public function increment_next_number()
    {
        // Update next Receipts number in settings
        $FY = $this->session->userdata('finacial_year');
       $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_sale_return_number_for_cspl');
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_sale_return_number_for_cff');
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_sale_return_number_for_cbu');
            }elseif($selected_company == 4){
                $this->db->where('name', 'next_sale_return_number_for_cbupl');
            }
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
    

}
