<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Einvoice extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('einvoice_model');
        $this->load->model('vehicle_return_model');
        $this->load->model('challan_model');
        $this->load->helper('download');
        require_once module_dir_path(TIMESHEETS_MODULE_NAME) . '/third_party/excel/PHPExcel.php';
    }
    public function index(){
        
        if (!has_permission_new('einvoice', '', 'view')) {
            access_denied('invoices');
        }
        // echo 'hii';
         $title = "E-invoice";
        $data['title'] = $title;
        if ($this->input->post()) {
           if (!has_permission_new('einvoice', '', 'create')) {
            access_denied('invoices');
        }
            $data = $this->input->post();
                // print_r($data);die;
               $challan_data_new = $this->challan_model->get_json($this->input->post());    
                
                if ($success == true) {
                     set_alert('success', _l('added_successfully'));
                }
                redirect(admin_url('einvoice'));
           
        }
        $year = $this->session->userdata('finacial_year');
        $date = array(
            "from_date"=>'01/04/2022',
            "to_date"=>date('t/m/Y'),
            );
        $data['Translist'] =  $this->einvoice_model->sales_model_table($date);
        $data['clients_details'] = $this->vehicle_return_model->get_vendor_data();
        $data['staff_details'] = $this->vehicle_return_model->get_staff_data();
    
        $this->load->view('admin/E-invoice/manage', $data);
    }
    
    public function sales_details_model(){
       $data =  $this->einvoice_model->sales_model_table($this->input->post());
        $html ='';
        if(count($data) >0 ){
            
        
         foreach($data as $value){
           $html.= '<tr class="get_challan_id" data-id="'.$value["ChallanID"].'" data-name="'.$value["SalesID"].'">'; 
            $html.= '<td>'.$value["SalesID"].'</td>'; 
            $html.= '<td>'. _d(substr($value["Transdate"],0,10)).'</td>'; 
            $html.= '<td>'.$value["ChallanID"].'</td>'; 
            $html.= '<td>'.$value["company"].'</td>';
            $html.= '<td>'.substr($value["address"],0,20).'</td>'; 
            $html.= '<td style="text-align:right;">'.$value["SaleAmt"].'</td>'; 
            $html.= '<td style="text-align:right;">'.$value["DiscAmt"].'</td>'; 
            $gst = 0.00;
            if($value["igstamt"]=="0.00"){
                $gst = $value["sgstamt"] + $value["cgstamt"];
            }else{
                $gst = $value["igstamt"];
            }
            $html.= '<td style="text-align:right;">'.$gst.'</td>'; 
            $html.= '<td style="text-align:right;">'. $value["tcsAmt"].'</td>'; 
            $html.= '<td style="text-align:right;">'.$value["BillAmt"].'</td>'; 
            $html.= '<td style="text-align:center;">'.$value["ItCount"].'</td>'; 
            //$html.= '<td>'.$value["Qrcode"].'</td>'; 
            $html.= '<td>'.substr($value["irn"],0,10).'</td>'; 
           
           
           $html.= '</tr>'; 
       } 
        }else{
             $html.= '<tr>'; 
              $html.= '<span style="color:red;">No data found..</span>';
             $html.= '</tr>'; 
        }
       
       echo $html;
    }
    
    public function unique_challan_details(){
        
         $data =  $this->einvoice_model->challan_unique_data($this->input->post());
         echo json_encode($data);
    }
    public function process_excel()
    {
        if ($this->input->post()) {
            if(!class_exists('XLSXReader_fin')){
      require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
        }
        require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
        $file_name = $_FILES["res_file"]["name"];
        //$FILE = $this->input->post('FILE');
        
        $tmpDir = 'uploads/E-invoice/';
        $target_file = $tmpDir . basename($_FILES["res_file"]["name"]);
        $newFilePath = $tmpDir . $file_name; 
        
        if (move_uploaded_file($_FILES["res_file"]["tmp_name"],
                                            $target_file)) {
            $xlsx = new XLSXReader_fin($target_file);
        $sheetNames = $xlsx->getSheetNames();
        $data = $xlsx->getSheetData($sheetNames[1]);
        $SaleID = $this->input->post('TransID2');
        //echo json_encode($SaleID);
        $IRN =  $data[1][1];
            $ackNo = $data[1][2];
            $ackdate = $data[1][3];
            $Qrcode = $data[1][10];
            
            $selected_company = $this->session->userdata('root_company');
                $FY = $this->session->userdata('finacial_year');
            if( $data[1][4]==$SaleID){
                $this->db->where('SalesID', $SaleID);
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $FY);
              $this->db->update(db_prefix().'salesmaster',['irn' => $IRN,'Qrcode' => $Qrcode,'ackno' => $ackNo,'ackdate' => $ackdate]);
                  if($this->db->affected_rows()){
                      set_alert('success', _l('added_successfully'));
                            redirect(admin_url('einvoice'));
                      
                  }else{
                      set_alert('warning', 'something went wrong...');
                            redirect(admin_url('einvoice'));
                  }
            }else{
                set_alert('warning', 'Please upload Excel for selected TransID...');
                    redirect(admin_url('einvoice'));
            }
                
        }
        }
       
    }
    public function ganerate_json_table()
    {
        
        $sales_data = $this->einvoice_model->get_sales($this->input->post());
        $Item_data = $this->einvoice_model->get_items($this->input->post());
        
        if($sales_data->gstno == ""){
            $html = 'Selected party not GST registerd';
            $data = array(
            'html'=>$html,
            'irn'=>$sales_data->irn,
            'Qrcode'=>$sales_data->Qrcode,
            'ackno'=>$sales_data->ackno,
            'ackdate'=>$sales_data->ackdate
            );
            echo json_encode($data); 
        }else{
            $html = '';
        $html .= '<table class="table table-striped table-bordered json_table_div" id="json_table_div" width="100%">';
        $html .= '<thead id="thead">';
        $html .= '<tr>';
        $html .= '<th>SupplyTypeCode</th>';
        $html .= '<th>ReverseCharge</th>';
        $html .= '<th>eCommGSTIN</th>';
        $html .= '<th>IgstOnIntra</th>';
        $html .= '<th>DocumentType</th>';
        $html .= '<th>DocumentNo</th>';
        $html .= '<th>DocumentDate</th>';
        $html .= '<th>BuyerGSTIN</th>';
        $html .= '<th>BuyerLegalName</th>';
        $html .= '<th>BuyerTradeName</th>';
        $html .= '<th>BuyerPOS</th>';
        $html .= '<th>BuyerAddr1</th>';
        $html .= '<th>BuyerAddr2</th>';
        $html .= '<th>BuyerLocation</th>';
        $html .= '<th>BuyerPinCode</th>';
        $html .= '<th>BuyerState</th>';
        $html .= '<th>BuyerPhoneNumber</th>';
        $html .= '<th>BuyerEmailId</th>';
        $html .= '<th>DispatchName</th>';
        $html .= '<th>DispatchAddr1</th>';
        $html .= '<th>DispatchAddr2</th>';
        $html .= '<th>DispatchLocation</th>';
        $html .= '<th>DispatchPinCode</th>';
        $html .= '<th>DispatchState</th>';
        $html .= '<th>ShippingGSTIN</th>';
        $html .= '<th>ShippingLegalName</th>';
        $html .= '<th>ShippingTradeName</th>';
        $html .= '<th>ShippingAddr1</th>';
        $html .= '<th>ShippingAddr2</th>';
        $html .= '<th>ShippingLocation</th>';
        $html .= '<th>ShippingPinCode</th>';
        $html .= '<th>ShippingState</th>';
        $html .= '<th>SINo</th>';
        $html .= '<th>ProductDescription</th>';
        $html .= '<th>IsService</th>'; //36
        $html .= '<th>HsnCode</th>';
        $html .= '<th>BarCode</th>';
        $html .= '<th>Quantity</th>';
        $html .= '<th>FreeQuantity</th>';
        $html .= '<th>Unit</th>';
        $html .= '<th>UnitPrice</th>';
        $html .= '<th>GrossAmount</th>';
        $html .= '<th>Discount</th>';
        $html .= '<th>PreTaxValue</th>';
        $html .= '<th>TaxableValue</th>';
        $html .= '<th>GSTRate</th>';
        $html .= '<th>SGSTAMT</th>';
        $html .= '<th>CGSTAMT</th>';
        $html .= '<th>IGSTAMT</th>';
        $html .= '<th>Cess</th>';
        $html .= '<th>CessAmtAdval</th>';
        $html .= '<th>CessNonAmtAdval</th>';
        $html .= '<th>StateCessRate</th>';
        $html .= '<th>StateCessAdvalAmt</th>';
        $html .= '<th>StateCessNonAdvalAmt</th>';
        $html .= '<th>OnCharges</th>';
        $html .= '<th>ItemTotal</th>';
        $html .= '<th>BatchName</th>';
        $html .= '<th>BatchExpiryDt</th>';
        $html .= '<th>WarrantyDt</th>';
        $html .= '<th>TotalTaxablevalue</th>';
        $html .= '<th>SGSTAmount</th>';
        $html .= '<th>CGSTAmount</th>';
        $html .= '<th>IGSTAmount</th>';
        $html .= '<th>CessAmt</th>';
        $html .= '<th>StateCessAmt</th>';
        $html .= '<th>DiscAmt</th>';
        $html .= '<th>OtherCharges</th>';
        $html .= '<th>RoundOff</th>';
        $html .= '<th>TotalInvoicevalue</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i=1;
        foreach ($Item_data as $key => $value) {
            $html .= '<tr>';
            $html .= '<td>B2B</td>';
            if($sales_data->igstamt == "0.00" || $sales_data->igstamt == null){
              $igstonintra = 'No';  
            }else{
                $igstonintra = 'Yes';  
            }
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td>'.$igstonintra.'</td>';
            $html .= '<td>Tax Invoice</td>';
            $html .= '<td>'.$sales_data->SalesID.'</td>';
            $html .= '<td>'. _d(substr($sales_data->Transdate,0,10)) .'</td>';
            $html .= '<td>'.$sales_data->gstno.'</td>';
            $html .= '<td>'.$sales_data->company.'</td>';
            $html .= '<td>'.$sales_data->company.'</td>';
            $html .= '<td>'.$sales_data->StateName.'</td>';
            $html .= '<td>'.$sales_data->address.'</td>';
            $html .= '<td>'.$sales_data->Address3.'</td>';
            if($sales_data->city_name == ""){
                $location = $sales_data->city;
            }else{
                $location = $sales_data->city_name;
            }
            $html .= '<td>'.$location.'</td>';
            $html .= '<td>'.$sales_data->zip.'</td>';
            $html .= '<td>'.$sales_data->StateName.'</td>';
            $html .= '<td>'.$sales_data->phonenumber.'</td>';
            $html .= '<td>'.$sales_data->email.'</td>';
            $html .= '<td>'.$sales_data->company.'</td>';
            $html .= '<td>'.$sales_data->address.'</td>';
            $html .= '<td>'.$sales_data->Address3.'</td>';
            $html .= '<td>'.$location.'</td>';
            $html .= '<td>'.$sales_data->zip.'</td>';
            $html .= '<td>'.$sales_data->StateName.'</td>';
            $html .= '<td>'.$sales_data->gstno.'</td>';
            $html .= '<td>'.$sales_data->company.'</td>';
            $html .= '<td>'.$sales_data->company.'</td>';
            $html .= '<td>'.$sales_data->address.'</td>';
            $html .= '<td>'.$sales_data->Address3.'</td>';
            $html .= '<td>'.$location.'</td>';
            $html .= '<td>'.$sales_data->zip.'</td>';
            $html .= '<td>'.$sales_data->StateName.'</td>';
            $html .= '<td>'.$i.'</td>';
            $html .= '<td>'.$value["hsn_code"].'</td>';
            $html .= '<td>No</td>'; //36
            $html .= '<td>'.$value["hsn_code"].'</td>';
            $html .= '<td></td>'; // bar code
            $html .= '<td>'.$value["BilledQty"].'</td>';
            $html .= '<td>0</td>'; // free quantity
            $html .= '<td>'.$value["unit"].'</td>';
            $html .= '<td>'.$value["BasicRate"].'</td>';
            $html .= '<td>'.$value["ChallanAmt"].'</td>';
            $html .= '<td>0.00</td>'; // Discount
            $html .= '<td>0.00</td>'; // preTaxvalue
            $html .= '<td>'.$value["ChallanAmt"].'</td>';
            if($value["igst"] == NULL || $value["igst"] == '0.00'){
                $gst = $value["sgst"] + $value["cgst"];
                $sgstamt = $value["sgstamt"];
                $cgstamt = $value["cgstamt"];
                $igstamt = 0.00;
            }else{
                $gst = $value["igst"];
                $sgstamt = 0.00;
                $cgstamt = 0.00;
                $igstamt = $value["igstamt"];
            }
            $html .= '<td>'.$gst.'</td>';
            $html .= '<td>'.number_format($cgstamt,2).'</td>';
            $html .= '<td>'.number_format($sgstamt,2).'</td>';
            $html .= '<td>'.number_format($igstamt,2).'</td>';
            $html .= '<td>0.00</td>'; // cess
            $html .= '<td>0.00</td>';
            $html .= '<td>0</td>';
            $html .= '<td>0</td>';
            $html .= '<td>0</td>';
            $html .= '<td>0</td>';
            $html .= '<td>0</td>'; // other charges
            $html .= '<td>'.number_format($value["NetChallanAmt"],2).'</td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td>'.number_format($value["ChallanAmt"],2).'</td>';
            $html .= '<td>'.number_format($cgstamt,2).'</td>';
            $html .= '<td>'.number_format($sgstamt,2).'</td>';
            $html .= '<td>'.number_format($igstamt,2).'</td>';
            $html .= '<td>0.00</td>'; // cess amount
            $html .= '<td>0</td>'; //state cess amount
            $html .= '<td>'.$value["Discamt"].'</td>';
            
            if($sales_data->tcsAmt == "0.00"){
                $otherAmt = 0.00;
            }else{
                $otherAmt = ($value["NetChallanAmt"] / 100) * $sales_data->tcs;
            }
            $html .= '<td>'.number_format($otherAmt,2).'</td>';
            $fTotal = $value["NetChallanAmt"] + $otherAmt;
            $rndoff = round($fTotal);
            $rndoffAmt = $rndoff - $fTotal;
            $html .= '<td>'.number_format(abs($rndoffAmt),2).'</td>';
            $html .= '<td>'.$rndoff.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        //echo $html;
        $data = array(
            'html'=>$html,
            'irn'=>$sales_data->irn,
            'Qrcode'=>$sales_data->Qrcode,
            'ackno'=>$sales_data->ackno,
            'ackdate'=>$sales_data->ackdate
            );
        
        echo json_encode($data); 
        }
    }
    
    
    
}?>