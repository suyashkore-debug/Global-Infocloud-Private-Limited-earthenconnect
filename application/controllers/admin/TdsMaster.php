<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TdsMaster extends AdminController
{
    private $not_importable_fields = ['id'];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tds_model');
    }
    public function index()
    {
        if (!has_permission_new('tdsmaster', '', 'view')) {
            access_denied('tdsmaster');
        }/*
        $data['tds_name'] = $this->Tds_model->GetTDSList();
        $data['tds_code'] = $this->Tds_model->GetTDSList();*/
        $this->load->view('admin/TDSMaster/AddEditTdsMaster', $data);
    }
	 public function AccountListPopUp()
    {
        $TDSList  = $this->Tds_model->GetTDSList();
        $html = "";
        foreach ($TDSList  as $key => $value) {
           $html .= '<tr class="get_AccountID" data-id="' . $value["TDSCode"] . '">';
           $html .= '<td>' . $value["TDSCode"] . '</td>';
           $html .= '<td>' . $value["TDSName"] . '</td>';
           $html .= '<td>' . $value["ThresholdLimit"] . '</td>';
           $html .= '</tr>';
        }
        echo $html;
    }
	public function GetAccountDetailByID()
    {
        $TDSCode = $this->input->post('TDSCode');
        $itemDetails = $this->Tds_model->GetTDSDetails($TDSCode);
        echo json_encode($itemDetails);
    }
	public function SaveItemID()
    {
        $data = array(
            'TDSCode'=>$this->input->post('TDSCode'),
            'TDSName'=>strtoupper($this->input->post('TDSName')),
            'ThresholdLimit'=>$this->input->post('ThresholdLimit'),
            'Blocked'=>$this->input->post('isactive'),
            'paradataArraylength'=>$this->input->post('paradataArraylength'),
            'paradataSerializedArr'=>$this->input->post('paradataSerializedArr'),
        );
        
        $item  = $this->Tds_model->SaveItemID($data);
        echo json_encode($item);
    }
	 /* Update Exiting ItemID / ajax */
    public function UpdateItemID()
    {
        $data = array(
            'TDSCode'=>$this->input->post('TDSCode'),
            'TDSName'=>strtoupper($this->input->post('TDSName')),
            'ThresholdLimit'=>$this->input->post('ThresholdLimit'),
            'Blocked'=>$this->input->post('isactive'),
            'paradataArraylength'=>$this->input->post('paradataArraylength'),
            'paradataSerializedArr'=>$this->input->post('paradataSerializedArr'),
        );
        $item  = $this->Tds_model->UpdateItemID($data);
        echo json_encode($item);
    }
}