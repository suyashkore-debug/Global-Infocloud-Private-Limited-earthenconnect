<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tour extends AdminController
{
    
    public function index()
    {
        /*if (!has_permission('tour', '', 'view')) {
            if (!have_assigned_customers() && !has_permission('tour', '', 'create')) {
                access_denied('tour');
            }
        }*/

        $this->load->model('tour_model');
        $this->load->model('enquiry_model');
        //echo "hello";
        $data['staff_list'] = $this->clients_model->get_customers_assigned_person();
        $data['state'] = $this->staff_model->get_state();
        $data['ss'] = $this->tour_model->get();
        

        $this->load->view('admin/tour/TourList', $data);
    }
    
    public function GetTourPlan(){
        if(!has_permission_new('accounting_cd_report', '', 'view')) {
            access_denied('accounting_tcs_report');
        }
        $data_post = $this->input->post();
        
        $this->load->model('tour_model');
         $data =$this->tour_model->table_data($data_post);
    //print_r($data);die;
       $selected_company = $this->session->userdata('root_company');
        $html ='';
        $i =1;
        
        foreach($data as $value){
            $html.= '<tr>';
            $html.= '<td align="center" style="text-align:center;width:2%">'.$i.'</td>';
            $html.= '<td align="left"  style="text-align:left;width:15%">'.$value['firstname']." ".$value['lastname'].'</td>';
            $html.= '<td align="left"  style="text-align:left;width:10%">'.$value['purpose'].'</td>';
            $html.= '<td align="center" style="text-align:left;width:8%">'.date("d/m/Y", strtotime(substr($value['start_date'],0,10))).'</td>';
            $html.= '<td align="center" style="text-align:left;width:8%">'.date("d/m/Y", strtotime(substr($value['end_date'],0,10))).'</td>';
            $html.= '<td style="text-align:left;width:5%">'.$value['state'].'</td>';
            $html.= '<td align="left" style="text-align:left;width:8%">'.$value['city_name'].'</td>';
            $html.= '<td align="left" style="text-align:left;width:15%">'.$value['area'].'</td>';
            $html.= '<td align="left" style="text-align:left;width:21%">'.$value['remark'].'</td>';
            if($value['status']=="0"){
                $status ="Pending";
            }else if($value['status']=="1"){
                $status ="Approved";
            }else{
                $status ="Cancel";
            }
            $html.= '<td align="left" style="text-align:left;width:8%">'.$status.'</td>';
            $html.= '</tr>';
            $i++;
        }
        echo $html;
    }

    public function table()
    {
        $this->app->get_table_data('tour');
    }

    
}
