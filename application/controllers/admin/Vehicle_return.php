<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Vehicle_return extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vehicle_return_model');
    }
    public function index(){
        
        if (!has_permission_new('vehicle_return', '', 'view')) {
            access_denied('invoices');
        }
        // echo 'hii';
         $title = "Vehicle Return";
        $data['title'] = $title;
        if ($this->input->post()) {
           
            $data = $this->input->post();
                // print_r($data);die;
                $success = $this->vehicle_return_model->add_vehicle_return($data);
                
                if ($success) {
                     set_alert('success', _l('added_successfully'));
                }
                redirect(admin_url('Vehicle_return'));
           
        }
        $data['clients_details'] = $this->vehicle_return_model->get_vendor_data();
        $data['staff_details'] = $this->vehicle_return_model->get_staff_data();
        $fy = $this->session->userdata('finacial_year');
        $fy_new  = $fy + 1;
        $lastdate_date = '20'.$fy_new.'-03-31';
        $firstdate_date = '20'.$fy_new.'-04-01';
        $curr_date = date('Y-m-d');
        $curr_date_new    = new DateTime($curr_date);
        $last_date_yr = new DateTime($lastdate_date);
        if($last_date_yr < $curr_date_new){
            $to_date = '31/03/20'.$fy_new;
            $from_date = '01/03/20'.$fy_new;
        }else{
            $from_date = "01/".date('m')."/".date('Y');
            $to_date = date('d/m/Y');
        }
        $date = array(
            "from_date"=>$from_date,
            "to_date"=>$to_date,
            );
        $data['vRtnlist'] =  $this->vehicle_return_model->vehicle_return_table($date);
        $data['chllist'] =  $this->vehicle_return_model->challan_model_table($date);
        /*print_r($date);
        die;*/
        $this->load->view('admin/vehicle_return/manage_new', $data);
    }
    public function challan_details_model(){
       $data =  $this->vehicle_return_model->challan_model_table($this->input->post());
        $html ='';
        if(count($data) >0 ){
         foreach($data as $value){
            $html.= '<tr class="get_challan_id" data-id="'.$value["ChallanID"].'">'; 
            $html.= '<td style="padding:0px 3px !important;" >'.$value["ChallanID"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;">'. _d(substr($value["Transdate"],0,10)).'</td>'; 
            $html.= '<td></td>'; 
            $html.= '<td style="padding:0px 3px !important;">'.$value["name"].'</td>';
            $html.= '<td style="padding:0px 3px !important;">'.$value["VehicleID"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;">'.$value["driver_fn"].' '.$value["driver_ln"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;">'.$value["loader_fn"].' '.$value["loader_ln"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;">'.$value["Salesman_fn"].' '.$value["Salesman_ln"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;text-align:right;">'. $value["Crates"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;text-align:right;">'.$value["Cases"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;text-align:right;">'.$value["ChallanAmt"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;">'.$value["OtherVehicleDetails"].'</td>'; 
            $html.= '</tr>'; 
       } 
        }else{
            $html.= '<tr>'; 
            $html.= '<td colspan="12"><span style="color:red;">No data found..</span></td>';
            $html.= '</tr>'; 
        }
       
       echo $html;
    }
    public function unique_challan_details(){
        
         $data =  $this->vehicle_return_model->challan_unique_data($this->input->post());
         echo json_encode($data);
    }
    public function all_challan_details(){
        
        $data_a =  $this->vehicle_return_model->challan_all_data($this->input->post());
        /*$html = '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody id="tbody">';
        $i=1;
        foreach ($data_a as $key => $value) {
        
            $html .= '<tr id="row'.$i.'" class="accounts">';
            $html .= '<td>'.$value["AccountID"].'</td>';
            $html .= '<td>'.$value["company"].'</td>';
            $html .= '<td>'.$value["address"].'</td>';
            if($value["Qty"] == null){
                $qty = 0;
            }else{
                $qty = $value["Qty"];
            }
            $html .= '<td>'.$qty.'</td>';
            $html .= '<td>'.$value["Crates"].'</td>';
            $html .= '<td class="rtnqty"><input type="text" name="rtncrates'.$i.'" id="rtncrates'.$i.'" onblur="calculate_balcrates();"><input type="hidden" name="balcrates'.$i.'" id="balcrates'.$i.'" value="'.$value["balance_crates"].'"></td>';
            $html .= '<td class="balCrates"><span>'.$value["balance_crates"].'</span></td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '<td><input type="text" name="rtncratesAct" id="rtncratesAct" class="form-control"></td>';
        $html .= '<td><span id="company"></span></td>';
        $html .= '<td><span id="address"></span></td>';
        $html .= '<td><span id="opnCrates"></span></td>';
        $html .= '<td><span id="chlCrates"></span></td>';
        $html .= '<td><input type="text" name="rtncrates" id="rtncrates"></td>';
        $html .= '<td><span id="balCrates"></span></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';*/
         echo json_encode($data_a);
    }
    
    public function accountlist(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $data = $this->vehicle_return_model->getaccounts($postData);
    
        echo json_encode($data);
    }
    public function get_Account_Details(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $Account_data = $this->vehicle_return_model->get_Account_Details($postData);
    
        echo json_encode($Account_data);
    }
    
    public function staffaccountlist(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $data = $this->vehicle_return_model->staffgetaccounts($postData);
    
        echo json_encode($data);
    }
    
    public function get_staffAccount_Details(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $Account_data = $this->vehicle_return_model->get_staffAccount_Details($postData);
    
        echo json_encode($Account_data);
    }
    public function get_vendor_d($accout_id){
        $data_a =  $this->vehicle_return_model->get_vendor_details($accout_id);
         echo json_encode($data_a);
        // return $data_a;
    }
    public function get_vendor_details_expenses($accout_id){
        $data_a =  $this->vehicle_return_model->get_staff_data($accout_id);
         echo json_encode($data_a);
        // return $data_a;
    }
    public function vehicle_return_model(){
        $data =  $this->vehicle_return_model->vehicle_return_table($this->input->post());
        $html ='';
        if(count($data) >0 ){
            
        
         foreach($data as $value){
          
            $url = "'".admin_url().'Vehicle_return/vehicle_return_list/'.$value["ReturnID"]."'";
            $html.= '<tr onclick="location.href='.$url.'">';
       
             $html.= '<td style="padding:0px 3px !important;">'.$value["ReturnID"].'</td>';
              $html.= '<td style="padding:0px 3px !important;">'. _d(substr($value["returnTransdate"],0,10)).'</td>';
             $html.= '<td style="padding:0px 3px !important;">'.$value["ChallanID"].'</td>'; 
              $html.= '<td style="padding:0px 3px !important;">'. _d(substr($value["Transdate"],0,10)).'</td>'; 
           $html.= '<td></td>'; 
            $html.= '<td style="padding:0px 3px !important;">'.$value["name"].'</td>';
           
           $html.= '<td style="padding:0px 3px !important;">'.$value["driver_fn"].' '.$value["driver_ln"].'</td>'; 
           $html.= '<td style="padding:0px 3px !important;">'.$value["loader_fn"].' '.$value["loader_ln"].'</td>'; 
           $html.= '<td style="padding:0px 3px !important;">'.$value["Salesman_fn"].' '.$value["Salesman_ln"].'</td>'; 
            $html.= '<td style="padding:0px 3px !important;text-align:right;">'. $value["Crates"].'</td>'; 
           $html.= '<td style="padding:0px 3px !important;text-align:right;">'.$value["Cases"].'</td>'; 
           $html.= '<td style="padding:0px 3px !important;text-align:right;">'.$value["ChallanAmt"].'</td>'; 
           $html.= '<td style="padding:0px 3px !important;">'.$value["OtherVehicleDetails"].'</td>'; 
           
           
           $html.= '</tr>'; 
       } 
        }else{
             $html.= '<tr>'; 
              $html.= '<td colspan="13"><span style="color:red;">No data found..</span></td>';
             $html.= '</tr>'; 
        }
       
       echo $html;
    }
    public function vehicle_return_list($id = ""){
        
         if ($this->input->post()) {
            if (!has_permission_new('vehicle_return', '', 'edit')) {
            access_denied('invoices');
            }
            $vehicleRtn_data = $this->input->post();
            /*echo "<pre>";
            print_r($vehicleRtn_data);
            
            die;*/
            $idd = $this->vehicle_return_model->update_vehicle_rtn($vehicleRtn_data);
             if ($idd == true) {
                    set_alert('success', _l('updated_successfully'));
                }else{
                   set_alert('error', _l('Some thing went wrong')); 
                }
                redirect(admin_url('Vehicle_return/vehicle_return_list/' . $id)); 
         }else{
        $title = "Edit Vechile Return";
        $data['return_details'] = $this->vehicle_return_model->get_unique_vehicle_return($id);
        $vehRtnDetails = $this->vehicle_return_model->get_unique_vehicle_return($id);
        $data['return_expense_list'] = $this->vehicle_return_model->get_all_expense_vehicle_return($id);
        $data['return_payment_list'] = $this->vehicle_return_model->get_all_payment_vehicle_return($id);
        $data['return_payment_sum'] = $this->vehicle_return_model->get_sum_payment_vehicle_return($id);
        $data['return_saleRtn_sum'] = $this->vehicle_return_model->get_sum_saleRtn_vehicle_return($id);
        //echo $vehRtnDetails->ChallanID;
        $data['return_saleRtn_list'] = $this->vehicle_return_model->get_saleRtn_list($vehRtnDetails['ChallanID']);
        $data['return_saleRtn_Itemlist'] = $this->vehicle_return_model->get_saleRtn_Itemlist($vehRtnDetails['ChallanID']);
        $data['return_crate_list'] = $this->vehicle_return_model->get_all_crate_vehicle_return($id);
        $data['return_crate_list_new_added'] = $this->vehicle_return_model->get_crate_vehicle_return_new_added($id,$vehRtnDetails['ChallanID']);
        $data['clients_details'] = $this->vehicle_return_model->get_vendor_data();
        $data['staff_details'] = $this->vehicle_return_model->get_staff_data();
        $fy = $this->session->userdata('finacial_year');
        $fy_new  = $fy + 1;
        $lastdate_date = '20'.$fy_new.'-03-31';
        $firstdate_date = '20'.$fy_new.'-04-01';
        $curr_date = date('Y-m-d');
        $curr_date_new    = new DateTime($curr_date);
        $last_date_yr = new DateTime($lastdate_date);
        if($last_date_yr < $curr_date_new){
            $to_date = '31/03/20'.$fy_new;
            $from_date = '01/03/20'.$fy_new;
        }else{
            $from_date = "01/".date('m')."/".date('Y');
            $to_date = date('d/m/Y');
        }
        $date = array(
            "from_date"=>$from_date,
            "to_date"=>$to_date,
            );
        $data['vRtnlist'] =  $this->vehicle_return_model->vehicle_return_table($date);
        $data['title'] = $title;
        /*echo "<pre>";
        print_r($data['return_crate_list']);
        die;*/
        $this->load->view('admin/vehicle_return/edit_vehicle_return', $data);  
         }
         
    }
}?>