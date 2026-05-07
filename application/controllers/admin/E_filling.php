<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class E_filling extends AdminController
{
   
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('e_filling_model');
        require_once module_dir_path(TIMESHEETS_MODULE_NAME) . '/third_party/excel/PHPExcel.php';
        $this->load->helper('download');
    }

    /* Sale Report Page */
    public function index()
    {
        if (!has_permission_new('GSTR_sales', '', 'view')) {
            access_denied('GSTR_sales');
        }
        $title = _l('GST Sales Report');
        $data['title'] = $title;
         $this->load->model('misc_reports_model');
         $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/gst_sale_report', $data);
    }
   
      public function accountlist(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $data = $this->e_filling_model->getaccounts($postData);
    
        echo json_encode($data);
    }
     public function get_Account_Details(){
        
        // POST data
        $postData = $this->input->post();
    
        // Get data
        $Account_data = $this->e_filling_model->get_Account_Details($postData);
    
        echo json_encode($Account_data);
    }
    public function load_table()
    {
       
        $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'accountId'  => $this->input->post('accountId'),
           'bill_type'  => $this->input->post('bill_type'),
           'bill_wise_type'  => $this->input->post('bill_wise_type'),
           'gst_type'  => $this->input->post('gst_type')
        );
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $accountId = $this->input->post('accountId');
        $bill_type = $this->input->post('bill_type');
        $bill_wise_type = $this->input->post('bill_wise_type');
        $gst_type = $this->input->post('gst_type');
        $account_full_name = $this->input->post('account_full_name');
        
        $SaleIDSList = $this->e_filling_model->GetSaleIDSForGSTSale($filterdata);
        $SaleIDs = array();
        foreach($SaleIDSList as $val){
            array_push($SaleIDs,$val["SalesID"]);
        }
        $body_data = $this->e_filling_model->GetGSTSaleBody($filterdata,$SaleIDs);
        $GstType = $this->e_filling_model->GetGSTTypeForGSTSale($filterdata,$SaleIDs);
        $GstTypeWiseValue = $this->e_filling_model->GetGSTTypeWiseAmt($filterdata,$SaleIDs);
        $gstTypeArray = array();
        foreach ($GstType as $key1 => $value1) {
            $GSTS = 0;
            $GSTS = $value1['cgst'] + $value1['sgst'] + $value1['igst'];
            array_push($gstTypeArray, $GSTS);
        }
        $GSTType_unq = array_unique($gstTypeArray);
        sort($GSTType_unq);
        // echo json_encode($GSTType_unq);
        // die;
        $table_width = '100%';
        $colspan = 6;
        $html = '';
        if($filterdata['bill_wise_type'] == 2){
        // Day Wise report
            $html .= '<table class="table-striped table-bordered production_report" id="production_report" width="'.$table_width.'">';
            $html .= '<thead style="font-size:11px;">';
            $html .= '<tr style="display:none;" >';
            $html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;">';
            if($accountId != ''){
                $html .= 'Account: '.$account_full_name.',';
            }else{
                $html .= 'Account: All,';
            }
            if($bill_type == 1){
                $html .= 'Bill Type: All Bills,';
            }else if($bill_type == 2){
                $html .= 'Bill Type: GST Bills,';
            }else if($bill_type == 3){
                $html .= 'Bill Type: Non-GST Bills,';
            }
            $html .= 'Day Wise Summary,';
            $html .= 'form date:'.$from_date.', to date:'.$to_date.'</span></td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td align="center" colspan="2"></td>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $html .= '<td align="center" colspan="4">GST '.sprintf('%0.2f', $value2).'%</td>';
                }
            }
            //$html .= '<th align="center" colspan="2">Account Details</th>';
            $html .= '<td align="center" colspan="3">Total</td>';
            $html .= '</tr>';
            
            $html .= '<tr>';
            $html .= '<th class="sortable" align="center">SrNo</th>';
            $html .= '<th class="sortable" align="center">Date</th>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $html .= '<th class="sortable" align="center" >Taxable '.sprintf('%0.2f', $value2).'</th>';
                    $html .= '<th class="sortable" align="center" >CGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                    $html .= '<th class="sortable" align="center" >SGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                    $html .= '<th class="sortable" align="center" >IGST '.sprintf('%0.2f', $value2).'</th>';
                }
            }
            
            $html .= '<th class="sortable" align="center">TaxableAmt</th>';
            $html .= '<th class="sortable" align="center">GSTAmt</th>';
            $html .= '<th class="sortable" align="center">BillAmt</th>';
            $html .= '</tr>';
            
        
            $html .= '</thead>';
            $html .= '<tbody>';
            $total_taxable_amt = 0;
            $total_gst_amt = 0;
            $total_bill_amt = 0;
            $i = 1;
            foreach ($body_data as $key => $value) {
               /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                if($value["SaleAmt"] == 0 || $value["SaleAmt"] == 0.00){
            
                }else{
                    $html .= '<tr>';
                    $html .= '<td align="center">'.$i.'</td>';
                    $html .= '<td align="center">'.substr(_d($value["Transdate"]),0,10).'</td>';
                    $total_taxable_amt += $value["SaleAmt"];
                    if($gst_type !== "2"){   
                        foreach ($GSTType_unq as $value2) {
                            $match1 = 0;
                            $taxAmt = 0;
                            $cgstAmt = 0;
                            $sgstAmt = 0;
                            $igstAmt = 0;
                            foreach ($GstTypeWiseValue as $key3 => $value3) {
                                $gstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                                if($gstP2 == $value2 && substr($value["Transdate"],0,10) == substr($value3["TransDate2"],0,10)){
                                    $match1 = 1;
                                    $taxAmt += $value3["taxableAmt"];
                                    $cgstAmt += $value3["cgstsum"];
                                    $sgstAmt += $value3["sgstsum"];
                                    $igstAmt += $value3["igstsum"];
                                }
                            }
                            if($match1 == 0){
                                $html .= '<td align="center" > </td>';
                                $html .= '<td align="center" > </td>';
                                $html .= '<td align="center" > </td>';
                                $html .= '<td align="center" > </td>';
                            }else{
                                $html .= '<td align="center" >'.number_format($taxAmt,2).' </td>';
                                $html .= '<td align="center" >'.number_format($cgstAmt,2).' </td>';
                                $html .= '<td align="center" >'.number_format($sgstAmt,2).' </td>';
                                $html .= '<td align="center" >'.number_format($igstAmt,2).' </td>';
                            }  
                        }
                    }
                    $html .= '<td align="right">'.number_format($value["SaleAmt"],2).'</td>';
                    $gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                    $total_gst_amt +=$gst;
                    //$html .= '<td align="right">'.sprintf('%0.2f', $gst).'</td>';
                    $html .= '<td align="right">'.number_format($gst,2).'</td>';
                    $bill_amt = $value["SaleAmt"]+$gst;
                    $total_bill_amt +=$bill_amt;
                    $html .= '<td align="right">'.number_format($bill_amt,2).'</td>';
                    $html .= '</tr>'; 
                    $i++;
               }
            }
            $html .= '</tbody>';
           
            $html .= '<tfoot>';
            $html .= '<tr>';
          
            $html .= '<td ></td>';
            $html .= '<td ><span style="color:#e93232;font-weight:700;text-align:right;">Total</span></td>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt = 0;
                    $fcgstAmt = 0;
                    $fsgstAmt = 0;
                    $figstAmt = 0;
                    foreach ($GstTypeWiseValue as $key3 => $value3) {
                        $fgstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                        if($fgstP2 == $value2){
                            $ftaxAmt += $value3["taxableAmt"];
                            $fcgstAmt += $value3["cgstsum"];
                            $fsgstAmt += $value3["sgstsum"];
                            $figstAmt += $value3["igstsum"];
                        }
                    }
                    $html .= '<td align="center" >'.number_format($ftaxAmt,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fcgstAmt,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fsgstAmt,2).' </td>';
                    $html .= '<td align="center" >'.number_format($figstAmt,2).' </td>';
                }
            }
            $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_taxable_amt,2).'</span></td>';
            $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_gst_amt,2).'</span></td>';
          
            $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_bill_amt,2).'</span></td>';
            $html .= '</tr>';
            $html .= '</tfoot>';
     
            $html .= '</table>';
        }else{
        // Bill Wise report
            $html .= '<table class="table-striped table-bordered production_report" id="production_report" width="'.$table_width.'">';
            $html .= '<thead style="font-size:11px;">';
           
            $html .= '<tr style="display:none;" >';
            $html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;">';
            if($accountId != ''){
                $html .= 'Account: '.$account_full_name.',';
            }else{
                $html .= 'Account: All,';
            }
            if($bill_type == 1){
                $html .= 'Bill Type: All Bills,';
            }else if($bill_type == 2){
                $html .= 'Bill Type: GST Bills,';
            }else if($bill_type == 3){
                $html .= 'Bill Type: Non-GST Bills,';
            }
            $html .= 'Bill Wise Summary,';
            $html .= 'form date:'.$from_date.', to date:'.$to_date.'</span></td>';
            $html .= '</tr>';
                
            $html .= '<tr>';
            $html .= '<td align="center" colspan="3"></td>';
            $html .= '<td align="center" colspan="2">Account Details</td>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $html .= '<td align="center" colspan="4">GST '.sprintf('%0.2f', $value2).'%</td>';
                }
            }
            $html .= '<td align="center" colspan="3">Total</td>';
            $html .= '</tr>';
            
            $html .= '<tr>';
            $html .= '<th class="sortable" align="center">SrNo</th>';
            $html .= '<th class="sortable" align="center">BillNo</th>';
            $html .= '<th class="sortable" align="center">Date</th>';
            $html .= '<th class="sortable" align="center">Account Name</th>';
            $html .= '<th class="sortable" align="center">GSTIN</th>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $html .= '<th class="sortable" align="center">Taxable '.sprintf('%0.2f', $value2).'</th>';
                    $html .= '<th class="sortable" align="center" >CGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                    $html .= '<th class="sortable" align="center" >SGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                    $html .= '<th class="sortable" align="center" >IGST '.sprintf('%0.2f', $value2).'</th>';
                }
            }
            $html .= '<th class="sortable" align="center">TaxableAmt</th>';
            $html .= '<th class="sortable" align="center">GSTAmt</th>';
            $html .= '<th class="sortable" align="center">BillAmt</th>';
            $html .= '</tr>';
                
            
            $html .= '</thead>';
            $html .= '<tbody>';
            $total_taxable_amt = 0;
            $total_gst_amt = 0;
            $total_bill_amt = 0;
            $i = 1;
            //  print_r($body_data);die;
            foreach ($body_data as $key => $value) {
                /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                if(($value["SaleAmt"] == 0) || $value["SaleAmt"] == 0.00){
                
                }else{
                    $html .= '<tr>';
                    $html .= '<td align="center">'.$i.'</td>';
                    $html .= '<td align="center">'.$value["SalesID"].'</td>';
                    $html .= '<td align="center">'.substr(_d($value["Transdate"]),0,10).'</td>';
                    $html .= '<td align="left">'.$value["company"].'</td>';
                    $html .= '<td align="center">'.$value["gstno"].'</td>';
                    $total_taxable_amt +=$value["SaleAmt"];
                    if($gst_type !== "2"){   
                        foreach ($GSTType_unq as $value2) {
                            $match = 0;
                            foreach ($GstTypeWiseValue as $key3 => $value3) {
                                $gstP = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                                if($gstP == $value2 && $value["SalesID"] == $value3["TransID"]){
                                    $match = 1;
                                    $html .= '<td align="center" >'.number_format($value3["taxableAmt"],2).' </td>';
                                    $html .= '<td align="center" >'.number_format($value3["cgstsum"],2).' </td>';
                                    $html .= '<td align="center" >'.number_format($value3["sgstsum"],2).' </td>';
                                    $html .= '<td align="center" >'.number_format($value3["igstsum"],2).' </td>';
                                }
                            }
                            if($match == 0){
                                $html .= '<td align="center" > </td>';
                                $html .= '<td align="center" > </td>';
                                $html .= '<td align="center" > </td>';
                                $html .= '<td align="center" > </td>';
                            }  
                        }
                    }
                
                    $html .= '<td align="right">'.number_format($value["SaleAmt"],2).'</td>';
                    $gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                    $total_gst_amt +=$gst;
                    $html .= '<td align="right">'.number_format($gst,2).'</td>';
                    $bill_amt = $value["SaleAmt"]+$gst;
                    $total_bill_amt +=$bill_amt;
                    $html .= '<td align="right">'.number_format($bill_amt,2).'</td>';
                    $html .= '</tr>'; 
                    $i++;
                }
            }
            $html .= '</tbody>';
               
            $html .= '<tfoot>';
            $html .= '<tr>';
              
            $html .= '<td ></td>';
            $html .= '<td ><span style="color:#e93232;font-weight:700;text-align:right;">Total</span></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt2 = 0;
                    $fcgstAmt2 = 0;
                    $fsgstAmt2 = 0;
                    $figstAmt2 = 0;
                    foreach ($GstTypeWiseValue as $keyf => $valuef) {
                        $gstPF = $valuef['igst'] + $valuef['cgst'] + $valuef['sgst'];
                        if($gstPF == $value2 ){
                            $ftaxAmt2 += $valuef['taxableAmt'];
                            $fcgstAmt2 += $valuef['cgstsum'];
                            $fsgstAmt2 += $valuef['sgstsum'];
                            $figstAmt2 += $valuef['igstsum'];
                        }
                    }
                    $html .= '<td align="center" >'.number_format($ftaxAmt2,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fcgstAmt2,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fsgstAmt2,2).' </td>';
                    $html .= '<td align="center" >'.number_format($figstAmt2,2).' </td>';
                }
            }
            $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_taxable_amt,2).'</span></td>';
            $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_gst_amt,2).'</span></td>';
              
            $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_bill_amt,2).'</span></td>';
            $html .= '</tr>';
            $html .= '</tfoot>';
            $html .= '</table>';
        }   
        echo json_encode($html);
        die;
    }
    public function export_gst_sale_report()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
       $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'accountId'  => $this->input->post('accountId'),
           'bill_type'  => $this->input->post('bill_type'),
           'bill_wise_type'  => $this->input->post('bill_wise_type'),
           'gst_type'  => $this->input->post('gst_type')
          );
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            $accountId = $this->input->post('accountId');
            $bill_type = $this->input->post('bill_type');
            $bill_wise_type = $this->input->post('bill_wise_type');
            $gst_type = $this->input->post('gst_type');
            $account_full_name = $this->input->post('account_full_name');
          
        $SaleIDSList = $this->e_filling_model->GetSaleIDSForGSTSale($filterdata);
        $SaleIDs = array();
        foreach($SaleIDSList as $val){
            array_push($SaleIDs,$val["SalesID"]);
        }
        $body_data = $this->e_filling_model->GetGSTSaleBody($filterdata,$SaleIDs);
        $GstType = $this->e_filling_model->GetGSTTypeForGSTSale($filterdata,$SaleIDs);
        $GstTypeWiseValue = $this->e_filling_model->GetGSTTypeWiseAmt($filterdata,$SaleIDs);
        
        $gstTypeArray = array();
        foreach ($GstType as $key1 => $value1) {
            $GSTS = 0;
            $GSTS = $value1['cgst'] + $value1['sgst'] + $value1['igst'];
            array_push($gstTypeArray, $GSTS);
        }
        $GSTType_unq = array_unique($gstTypeArray);
        sort($GSTType_unq);
        
        $this->load->model('misc_reports_model');
    	$selected_company_details    = $this->misc_reports_model->get_company_detail();
    if($gst_type !== "2"){
        if($bill_wise_type == 2){
    	    // Day Wise
    	    $default = 4;
    	    $otherColmn = count($GSTType_unq) * 4;
    	    $totColmn = $default + $otherColmn - 1;
    	}else{
    	    // Bill Wise
    	    $default = 7;
    	    $otherColmn = count($GSTType_unq) * 4;
    	    $totColmn = $default + $otherColmn - 1;
    	}	
    }else{
         if($bill_wise_type == 2){
    	    // Day Wise
    	    $default = 4;
    	    $totColmn = $default - 1;
    	}else{
    	    // Bill Wise
    	    $default = 7;
    	    $totColmn = $default - 1;
    	}
    }	
    	
    	$writer = new XLSXWriter();
    	$border = array( 'border'=>'left,right,top,bottom');
	    $border_style = array( 'border-style'=>'solid');
    		$company_name = array($selected_company_details->company_name);
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $totColmn);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $totColmn);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		
    		
    		$html = 'Sales (';
    		 if($accountId != ''){
                 $html .= 'Accounts: '.$account_full_name.',';
                 }else{
                      $html .= 'Accounts: All';
                 }
                /*if($bill_type == 1){
                 $html .= 'Bill Type: All Bills,';
                 }else if($bill_type == 2){
                  $html .= 'Bill Type: GST Bills,';
                 }else if($bill_type == 3){
                  $html .= 'Bill Type: Non-GST Bills,';
                 }*/
                  /*if($bill_wise_type == 2){
                       $html .= 'Day Wise Summary,';
                  }else{
                       $html .= 'Bill Wise Summary,';
                  }*/
                
            $html .= ') form '.$from_date.' To '.$to_date.'';
            $filter = array($html);
            $writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $totColmn);  //merge cells
    		$writer->writeSheetRow('Sheet1', $filter);
    	    
    	    
    		
    	 if($bill_wise_type == 2){
    	 // Day wise report
    	   
            $set_col_tk = [];
    		$set_col_tk["Date"] =  'Date';
    		if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $set_col_tk["Taxable'".$value2."'"] =  'Taxable'.sprintf('%0.2f', $value2).'%';
                    $set_col_tk["CGST'".($value2 / 2)."'"] =  'CGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["SGST'".($value2 / 2)."'"] =  'SGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["IGST'".$value2."'"] =  'IGST'.sprintf('%0.2f', $value2);
                }
            }
    		$set_col_tk["TaxableAmt"] =  'TaxableAmt';
    		$set_col_tk["GSTAmt"] =  'GSTAmt';
    		$set_col_tk["BillAmt"] =  'BillAmt';
           
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    	      $total_taxable_amt = 0;
               $total_gst_amt = 0;
               $total_bill_amt = 0;
           
                    foreach ($body_data as $key => $value) {
                        /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                        if($value["SaleAmt"] == 0 || $value["SaleAmt"] == 0.00){
                
                   }else{
                   $list_add = [];
                   	
                   	$list_add[] = substr(_d($value["Transdate"]),0,10);
                    $total_taxable_amt +=$value["SaleAmt"];
                    
                    if($gst_type !== "2"){   
                   foreach ($GSTType_unq as $value2) {
                       $match1 = 0;
                       $taxAmt = 0;
                       $cgstAmt = 0;
                       $sgstAmt = 0;
                       $igstAmt = 0;
                       foreach ($GstTypeWiseValue as $key3 => $value3) {
                           $gstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                           if($gstP2 == $value2 && substr($value["Transdate"],0,10) == substr($value3["TransDate2"],0,10)){
                               $match1 = 1;
                               $taxAmt += $value3["taxableAmt"];
                               $cgstAmt += $value3["cgstsum"];
                               $sgstAmt += $value3["sgstsum"];
                               $igstAmt += $value3["igstsum"];
                                
                           }
                        }
                        if($match1 == 0){
                            
                            $list_add[] = "";
                            $list_add[] = "";
                            $list_add[] = "";
                            $list_add[] = "";
                        }else{
                            $list_add[] = sprintf('%0.2f', $taxAmt);
                            $list_add[] = sprintf('%0.2f', $cgstAmt);
                            $list_add[] = sprintf('%0.2f', $sgstAmt);
                            $list_add[] = sprintf('%0.2f', $igstAmt);
                        }  
                    }
                }
                
                   	$list_add[] = sprintf('%0.2f', $value["SaleAmt"]);
                   	$gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                    $total_gst_amt +=$gst;
                   	$list_add[] = sprintf('%0.2f', $gst);
                   	 $bill_amt = $value["SaleAmt"]+$gst;
                   $total_bill_amt +=$bill_amt;
                   	$list_add[] = sprintf('%0.2f', $bill_amt);
                   
                    $writer->writeSheetRow('Sheet1', $list_add);
                   }
               
               }
              
               $list_add = [];
                $list_add[] = "Total";
                if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt = 0;
                    $fcgstAmt = 0;
                    $fsgstAmt = 0;
                    $figstAmt = 0;
                    foreach ($GstTypeWiseValue as $key3 => $value3) {
                        $fgstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                        if($fgstP2 == $value2){
                            $ftaxAmt += $value3["taxableAmt"];
                            $fcgstAmt += $value3["cgstsum"];
                            $fsgstAmt += $value3["sgstsum"];
                            $figstAmt += $value3["igstsum"];
                        }
                    }
                    $list_add[] = $ftaxAmt;
                    $list_add[] = $fcgstAmt;
                    $list_add[] = $fsgstAmt;
                    $list_add[] = $figstAmt;
                    
                    }
            }
                $list_add[] = $total_taxable_amt;
                $list_add[] = $total_gst_amt;
                $list_add[] = $total_bill_amt;
                  
            $writer->writeSheetRow('Sheet1', $list_add);
          
    	 }else{
    	     
    	 // Bill Wise report
    	 
    	    // Top Heading
    	    
    	      
    	    $list_add = [];
    	    
    	    $list_add[] = " ";
    	    //$list_add[] = "Account Details";
    	    $list_add[] = "";
    	    if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    //$list_add[] = "GST".$value2."%";
                    $list_add[] = "";
                }
    	    }
    	    $list_add[] = "";
    	    $writer->writeSheetRow('Sheet1', $list_add);
    	    //$writer->markMergedCell("A4:B4"); 
    	    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 1);  //merge cells
    	    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 2, $end_row = 3, $end_col = 3);  //merge cells
    	    if($gst_type !== "2"){
    	        $i = 4;
                $j = 7;
                foreach ($GSTType_unq as $value2) {
                    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = $i, $end_row = 3, $end_col = $j);  //merge cells
                    $i += 4;
                    $j += 4;
                }
    	    }
    	    $lastThree = $totColmn -2;
    	    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = $lastThree, $end_row = 3, $end_col = $totColmn);  //merge cells
    	    
            $set_col_tk = [];
    		$set_col_tk["BillNo"] =  'BillNo';
    		$set_col_tk["Date"] =  'Date';
    		$set_col_tk["Account Name"] =  'Account Name';
    		$set_col_tk["GSTIN"] =  'GSTIN';
    		if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $set_col_tk["Taxable'".$value2."'"] =  'Taxable'.sprintf('%0.2f', $value2).'%';
                    $set_col_tk["CGST'".($value2 / 2)."'"] =  'CGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["SGST'".($value2 / 2)."'"] =  'SGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["IGST'".$value2."'"] =  'IGST'.sprintf('%0.2f', $value2);
                }
            }
    		$set_col_tk["TaxableAmt"] =  'TaxableAmt';
    		$set_col_tk["GSTAmt"] =  'GSTAmt';
    		$set_col_tk["BillAmt"] =  'BillAmt';
           
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    	      $total_taxable_amt = 0;
               $total_gst_amt = 0;
               $total_bill_amt = 0;
           
                    foreach ($body_data as $key => $value) {
                        /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                        if($value["SaleAmt"] == 0 || $value["SaleAmt"] == 0.00){
                
                   }else{
                   $list_add = [];
                   	$list_add[] = $value["SalesID"];
                   	
                   	$list_add[] = substr(_d($value["Transdate"]),0,10);
                   	$list_add[] = $value["company"];
                   	$list_add[] = $value["gstno"];
                   	
                   	if($gst_type !== "2"){   
                   foreach ($GSTType_unq as $value2) {
                       $match = 0;
                       foreach ($GstTypeWiseValue as $key3 => $value3) {
                           $gstP = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                           if($gstP == $value2 && $value["SalesID"] == $value3["TransID"]){
                               $match = 1;
                                $list_add[] = sprintf('%0.2f', $value3["taxableAmt"]);
                                $list_add[] = sprintf('%0.2f', $value3["cgstsum"]);
                                $list_add[] = sprintf('%0.2f', $value3["sgstsum"]);
                                $list_add[] = sprintf('%0.2f', $value3["igstsum"]);
                           }
                        }
                        if($match == "0"){
                            $list_add[] = '';
                            $list_add[] = '';
                            $list_add[] = '';
                            $list_add[] = '';
                        }  
                    }
                }
                
                    $total_taxable_amt +=$value["SaleAmt"];
                   	$list_add[] = sprintf('%0.2f', $value["SaleAmt"]);
                   	$gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                    $total_gst_amt +=$gst;
                   	$list_add[] = sprintf('%0.2f', $gst);
                   	$bill_amt = $value["SaleAmt"]+$gst;
                   	
                    $total_bill_amt +=$bill_amt;
                   	$list_add[] = sprintf('%0.2f', $bill_amt);
                   
                    $writer->writeSheetRow('Sheet1', $list_add);
                   }
               
               }
              
                $list_add = [];
                $list_add[] = "Total";
                $list_add[] = "";
                $list_add[] = "";
                $list_add[] = "";
                if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt2 = 0;
                    $fcgstAmt2 = 0;
                    $fsgstAmt2 = 0;
                    $figstAmt2 = 0;
                    foreach ($GstTypeWiseValue as $keyf => $valuef) {
                           $gstPF = $valuef['igst'] + $valuef['cgst'] + $valuef['sgst'];
                           if($gstPF == $value2 ){
                                $ftaxAmt2 += $valuef['taxableAmt'];
                                $fcgstAmt2 += $valuef['cgstsum'];
                                $fsgstAmt2 += $valuef['sgstsum'];
                                $figstAmt2 += $valuef['igstsum'];
                           }
                    }
                    $list_add[] = sprintf('%0.2f', $ftaxAmt2);
                    $list_add[] = sprintf('%0.2f', $fcgstAmt2);
                    $list_add[] = sprintf('%0.2f', $fsgstAmt2);
                    $list_add[] = sprintf('%0.2f', $figstAmt2);
                }
            }
                $list_add[] = sprintf('%0.2f', $total_taxable_amt);
                $list_add[] = sprintf('%0.2f', $total_gst_amt);
                $list_add[] = sprintf('%0.2f', $total_bill_amt);
                  
            $writer->writeSheetRow('Sheet1', $list_add);
          
    	 }
    		
    		// empty row
    	
    	
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'GST Sale Report.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
    public function purchase_gst_report()
    {
        if (!has_permission_new('GSTR_purchase', '', 'view')) {
            access_denied('GSTR_purchase');
        }
        $title = _l('GST Purchase Report');
        $data['title'] = $title;
        $this->load->model('misc_reports_model');
        $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/gst_purchase_report', $data);
    }
//============================ Gstr Purchase ===================================
    public function purchase_gst_table()
    {   
        $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'accountId'  => $this->input->post('accountId'),
           'bill_type'  => $this->input->post('bill_type'),
           'bill_wise_type'  => $this->input->post('bill_wise_type'),
           'gst_type'  => $this->input->post('gst_type')
          );
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            $accountId = $this->input->post('accountId');
            $bill_type = $this->input->post('bill_type');
            $bill_wise_type = $this->input->post('bill_wise_type');
            $gst_type = $this->input->post('gst_type');
            $account_full_name = $this->input->post('account_full_name');
          
        $body_data = $this->e_filling_model->get_purchase_data_for_table($filterdata);
        
        $GstType = $this->e_filling_model->get_GstTypeP($filterdata);
        $GstTypeWiseValue = $this->e_filling_model->get_GstTypeWiseValueP($filterdata);
        
        $gstTypeArray = array();
        foreach ($GstType as $key1 => $value1) {
            $GSTS = 0;
            $GSTS = $value1['cgst'] + $value1['sgst'] + $value1['igst'];
            array_push($gstTypeArray, $GSTS);
        }
        $GSTType_unq = array_unique($gstTypeArray);
        sort($GSTType_unq);
        // echo json_encode($GstTypeWiseValue);
        // die;
        $table_width = '100%';
       $colspan = 6;
        $html = '';
        if($filterdata['bill_wise_type'] == 2){
            
        // Day Wise report
        
            $html .= '<table class="table-striped table-bordered production_report" id="production_report" width="'.$table_width.'">';
            $html .= '<thead style="font-size:11px;">';
           
          $html .= '<tr style="display:none;" >';
            
                
            $html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;">';
                 if($accountId != ''){
                 $html .= 'Account: '.$account_full_name.',';
                 }else{
                      $html .= 'Account: All';
                 }
                if($bill_type == 1){
                 $html .= 'Bill Type: All Bills,';
                 }else if($bill_type == 2){
                  $html .= 'Bill Type: GST Bills,';
                 }else if($bill_type == 3){
                  $html .= 'Bill Type: Non-GST Bills,';
                 }
                 $html .= 'Day Wise Summary,';
                 $html .= 'form date:'.$from_date.', to date:'.$to_date.'</span></td>';
                $html .= '</tr>';
                
                $html .= '<tr>';
                $html .= '<td align="center" colspan="2"></td>';
                if($gst_type !== "2"){
                    foreach ($GSTType_unq as $value2) {
                        $html .= '<td align="center" colspan="4">GST '.sprintf('%0.2f', $value2).'%</td>';
                    }
                }
                //$html .= '<th align="center" colspan="2">Account Details</th>';
                $html .= '<td align="center" colspan="3">Total</td>';
                $html .= '</tr>';
            
                $html .= '<tr>';
                $html .= '<th class="sortable" align="center">S.no</th>';
                $html .= '<th class="sortable" align="center">Date</th>';
                if($gst_type !== "2"){
                    foreach ($GSTType_unq as $value2) {
                        $html .= '<th class="sortable" align="center" >Taxable '.sprintf('%0.2f', $value2).'</th>';
                        $html .= '<th class="sortable" align="center" >CGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                        $html .= '<th class="sortable" align="center" >SGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                        $html .= '<th class="sortable" align="center" >IGST '.sprintf('%0.2f', $value2).'</th>';
                    }
                }
                $html .= '<th class="sortable" align="center">TaxableAmt</th>';
                $html .= '<th class="sortable" align="center">GSTAmt</th>';
                $html .= '<th class="sortable" align="center">BillAmt</th>';
                $html .= '</tr>';
                
            
            $html .= '</thead>';
               $html .= '<tbody>';
               $total_taxable_amt = 0;
               $total_gst_amt = 0;
               $total_bill_amt = 0;
               $i = 1;
               foreach ($body_data as $key => $value) {
                   
                   /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                   if($value["Purchamt"] == 0 || $value["Purchamt"] == 0.00){
                
                   }else{
                  $html .= '<tr>';
                   $html .= '<td align="center">'.$i.'</td>';
                   $html .= '<td align="center">'.substr(_d($value["Transdate"]),0,10).'</td>';
                   $total_taxable_amt +=$value["Purchamt"];
                   
                   if($gst_type !== "2"){   
                   foreach ($GSTType_unq as $value2) {
                       $match1 = 0;
                       $taxAmt = 0;
                       $cgstAmt = 0;
                       $sgstAmt = 0;
                       $igstAmt = 0;
                       foreach ($GstTypeWiseValue as $key3 => $value3) {
                           $gstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                           if($gstP2 == $value2 && substr($value["Transdate"],0,10) == substr($value3["TransDate"],0,10)){
                               $match1 = 1;
                               $taxAmt += $value3["taxableAmt"];
                               $cgstAmt += $value3["cgstsum"];
                               $sgstAmt += $value3["sgstsum"];
                               $igstAmt += $value3["igstsum"];
                                
                           }
                        }
                        if($match1 == 0){
                            $html .= '<td align="center" > </td>';
                            $html .= '<td align="center" > </td>';
                            $html .= '<td align="center" > </td>';
                            $html .= '<td align="center" > </td>';
                        }else{
                            $html .= '<td align="center" >'.number_format($taxAmt,2).' </td>';
                            $html .= '<td align="center" >'.number_format($cgstAmt,2).' </td>';
                            $html .= '<td align="center" >'.number_format($sgstAmt,2).' </td>';
                            $html .= '<td align="center" >'.number_format($igstAmt,2).' </td>';
                        }  
                    }
                }
                
                   $html .= '<td align="right">'.number_format($value["Purchamt"],2).'</td>';
                   $gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                   $total_gst_amt +=$gst;
                   $html .= '<td align="right">'.number_format($gst,2).'</td>';
                   $bill_amt = $value["Purchamt"]+$gst;
                   $total_bill_amt +=$value["Invamt"];
                   $html .= '<td align="right">'.number_format($value["Invamt"],2).'</td>';
                   $html .= '</tr>'; 
                   $i++;
                   }
                   
               }
               $html .= '</tbody>';
               
               $html .= '<tfoot>';
               $html .= '<tr>';
              
             $html .= '<td ></td>';
                $html .= '<td ><span style="color:#e93232;font-weight:700;text-align:right;">Total</span></td>';
                if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt = 0;
                    $fcgstAmt = 0;
                    $fsgstAmt = 0;
                    $figstAmt = 0;
                    foreach ($GstTypeWiseValue as $key3 => $value3) {
                        $fgstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                        if($fgstP2 == $value2){
                            $ftaxAmt += $value3["taxableAmt"];
                            $fcgstAmt += $value3["cgstsum"];
                            $fsgstAmt += $value3["sgstsum"];
                            $figstAmt += $value3["igstsum"];
                        }
                    }
                    $html .= '<td align="center" >'.number_format($ftaxAmt,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fcgstAmt,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fsgstAmt,2).' </td>';
                    $html .= '<td align="center" >'.number_format($figstAmt,2).' </td>';
                }
                }
               
               $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_taxable_amt,2).'</span></td>';
               $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_gst_amt,2).'</span></td>';
              
               $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_bill_amt,2).'</span></td>';
               $html .= '</tr>';
               $html .= '</tfoot>';
         
            $html .= '</table>';
        }else{
            
            // Bill Wise report
            
            $html .= '<table class="table-striped table-bordered production_report" id="production_report" width="'.$table_width.'">';
            $html .= '<thead style="font-size:11px;">';
           
            $html .= '<tr style="display:none;" >';
            $html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;">';
                 if($accountId != ''){
                 $html .= 'Account: '.$account_full_name.',';
                 }else{
                      $html .= 'Accounts: All,';
                 }
                if($bill_type == 1){
                 $html .= 'Bill Type: All Bills,';
                 }else if($bill_type == 2){
                  $html .= 'Bill Type: GST Bills,';
                 }else if($bill_type == 3){
                  $html .= 'Bill Type: Non-GST Bills,';
                 }
                 $html .= 'Bill Wise Summary,';
                 $html .= 'form date:'.$from_date.', to date:'.$to_date.'</span></td>';
                $html .= '</tr>';
                
                $html .= '<tr>';
                $html .= '<td align="center" colspan="3"></td>';
                $html .= '<td align="center" colspan="2">Account Details</td>';
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $html .= '<td align="center" colspan="4">GST '.sprintf('%0.2f', $value2).'%</td>';
                }
            }
                $html .= '<td align="center" colspan="3">Total</td>';
                $html .= '</tr>';
                
            
                $html .= '<tr>';
                $html .= '<th class="sortable" align="center">S.no</th>';
                $html .= '<th class="sortable" align="center">BillNo</th>';
                $html .= '<th class="sortable" align="center">Date</th>';
                $html .= '<th class="sortable" align="center">Account Name</th>';
                $html .= '<th class="sortable" align="center">GSTIN</th>';
                if($gst_type !== "2"){
                    foreach ($GSTType_unq as $value2) {
                        $html .= '<th class="sortable" align="center">Taxable '.sprintf('%0.2f', $value2).'</th>';
                        $html .= '<th class="sortable" align="center" >CGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                        $html .= '<th class="sortable" align="center" >SGST '.sprintf('%0.2f', ($value2 / 2)).'</th>';
                        $html .= '<th class="sortable" align="center" >IGST '.sprintf('%0.2f', $value2).'</th>';
                    }
                }
                $html .= '<th class="sortable" align="center">TaxableAmt</th>';
                $html .= '<th class="sortable" align="center">GSTAmt</th>';
                $html .= '<th class="sortable" align="center">BillAmt</th>';
                $html .= '</tr>';
                
            
            $html .= '</thead>';
               $html .= '<tbody>';
               $total_taxable_amt = 0;
               $total_gst_amt = 0;
               $total_bill_amt = 0;
               $i = 1;
            //  print_r($body_data);die;
               foreach ($body_data as $key => $value) {
                   
                   /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                    if($value["Purchamt"] == 0 || $value["Purchamt"] == 0.00){
                   }else{
                  $html .= '<tr>';
                   $html .= '<td align="center">'.$i.'</td>';
                   $html .= '<td align="center">'.$value["PurchID"].'</td>';
                   $html .= '<td align="center">'.substr(_d($value["Transdate"]),0,10).'</td>';
                   $html .= '<td align="left">'.$value["company"].'</td>';
                   $html .= '<td align="center">'.$value["vat"].'</td>';
                   if($gst_type !== "2"){   
                   foreach ($GSTType_unq as $value2) {
                       $match = 0;
                       foreach ($GstTypeWiseValue as $key3 => $value3) {
                           $gstP = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                           if($gstP == $value2 && $value["PurchID"] == $value3["OrderID"]){
                               $match = 1;
                                $html .= '<td align="center" >'.number_format($value3["taxableAmt"],2).' </td>';
                                $html .= '<td align="center" >'.number_format($value3["cgstsum"],2).' </td>';
                                $html .= '<td align="center" >'.number_format($value3["sgstsum"],2).' </td>';
                                $html .= '<td align="center" >'.number_format($value3["igstsum"],2).' </td>';
                           }
                        }
                        if($match == 0){
                            $html .= '<td align="center" > </td>';
                            $html .= '<td align="center" > </td>';
                            $html .= '<td align="center" > </td>';
                            $html .= '<td align="center" > </td>';
                        }  
                    }
                }
                   $total_taxable_amt +=$value["Purchamt"];
                   $html .= '<td align="right">'.number_format($value["Purchamt"],2).'</td>';
                   $gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                   $total_gst_amt +=$gst;
                   $html .= '<td align="right">'.number_format($gst,2).'</td>';
                   $bill_amt = $value["Purchamt"]+$gst;
                   $total_bill_amt +=$value["Invamt"];
                   $html .= '<td align="right">'.number_format($value["Invamt"],2).'</td>';
                   $html .= '</tr>'; 
                   $i++;
                   }
                   
               }
               $html .= '</tbody>';
               
               $html .= '<tfoot>';
               $html .= '<tr>';
              
             $html .= '<td ></td>';
                $html .= '<td ><span style="color:#e93232;font-weight:700;text-align:right;">Total</span></td>';
               $html .= '<td></td>';
               $html .= '<td></td>';
               $html .= '<td></td>';
               if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt2 = 0;
                    $fcgstAmt2 = 0;
                    $fsgstAmt2 = 0;
                    $figstAmt2 = 0;
                    foreach ($GstTypeWiseValue as $keyf => $valuef) {
                           $gstPF = $valuef['igst'] + $valuef['cgst'] + $valuef['sgst'];
                           if($gstPF == $value2 ){
                                $ftaxAmt2 += $valuef['taxableAmt'];
                                $fcgstAmt2 += $valuef['cgstsum'];
                                $fsgstAmt2 += $valuef['sgstsum'];
                                $figstAmt2 += $valuef['igstsum'];
                           }
                    }
                    $html .= '<td align="center" >'.number_format($ftaxAmt2,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fcgstAmt2,2).' </td>';
                    $html .= '<td align="center" >'.number_format($fsgstAmt2,2).' </td>';
                    $html .= '<td align="center" >'.number_format($figstAmt2,2).' </td>';
                    
                }
            }
               $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_taxable_amt,2).'</span></td>';
               $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_gst_amt,2).'</span></td>';
              
               $html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.number_format($total_bill_amt,2).'</span></td>';
               $html .= '</tr>';
               $html .= '</tfoot>';
         
            $html .= '</table>';
        }
            
        echo json_encode($html);
        die;
    }
    public function export_gst_purchase_report()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
       $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'accountId'  => $this->input->post('accountId'),
           'bill_type'  => $this->input->post('bill_type'),
           'bill_wise_type'  => $this->input->post('bill_wise_type'),
           'gst_type'  => $this->input->post('gst_type')
          );
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            $accountId = $this->input->post('accountId');
            $bill_type = $this->input->post('bill_type');
            $bill_wise_type = $this->input->post('bill_wise_type');
            $gst_type = $this->input->post('gst_type');
            $account_full_name = $this->input->post('account_full_name');
          
        $body_data = $this->e_filling_model->get_purchase_data_for_table($filterdata);
        
        $GstType = $this->e_filling_model->get_GstTypeP($filterdata);
        $GstTypeWiseValue = $this->e_filling_model->get_GstTypeWiseValueP($filterdata);
        
        $gstTypeArray = array();
        foreach ($GstType as $key1 => $value1) {
            $GSTS = 0;
            $GSTS = $value1['cgst'] + $value1['sgst'] + $value1['igst'];
            array_push($gstTypeArray, $GSTS);
        }
        $GSTType_unq = array_unique($gstTypeArray);
        sort($GSTType_unq);
        
        $this->load->model('misc_reports_model');
    	$selected_company_details    = $this->misc_reports_model->get_company_detail();
    	if($gst_type !== "2"){
            if($bill_wise_type == 2){
        	    // Day Wise
        	    $default = 4;
        	    $otherColmn = count($GSTType_unq) * 4;
        	    $totColmn = $default + $otherColmn - 1;
        	}else{
        	    // Bill Wise
        	    $default = 7;
        	    $otherColmn = count($GSTType_unq) * 4;
        	    $totColmn = $default + $otherColmn - 1;
        	}	
        }else{
             if($bill_wise_type == 2){
        	    // Day Wise
        	    $default = 4;
        	    $totColmn = $default - 1;
        	}else{
        	    // Bill Wise
        	    $default = 7;
        	    $totColmn = $default - 1;
        	}
        }
    
    		$writer = new XLSXWriter();
    	    
    		
    		$company_name = array($selected_company_details->company_name);
    		
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $totColmn);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $totColmn);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		 
    	
    		$html = 'Purchase (';
    		 if($accountId != ''){
                 $html .= 'Accounts: '.$account_full_name.',';
                 }else{
                      $html .= 'Accounts: All';
                 }
                /*if($bill_type == 1){
                 $html .= 'Bill Type: All Bills,';
                 }else if($bill_type == 2){
                  $html .= 'Bill Type: GST Bills,';
                 }else if($bill_type == 3){
                  $html .= 'Bill Type: Non-GST Bills,';
                 }
                  if($bill_wise_type == 2){
                       $html .= 'Day Wise Summary,';
                  }else{
                       $html .= 'Bill Wise Summary,'; 
                  }*/
                
            $html .= ') form :'.$from_date.', To :'.$to_date.'';
            $filter = array($html);
            $writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $totColmn); //merge cells
    		$writer->writeSheetRow('Sheet1', $filter);
    	 if($bill_wise_type == 2){
    	     
    	     // Day wise report
            $set_col_tk = [];
    		$set_col_tk["Date"] =  'Date';
    		if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $set_col_tk["Taxable'".$value2."'"] =  'Taxable'.sprintf('%0.2f', $value2).'%';
                    $set_col_tk["CGST'".($value2 / 2)."'"] =  'CGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["SGST'".($value2 / 2)."'"] =  'SGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["IGST'".$value2."'"] =  'IGST'.sprintf('%0.2f', $value2);
                }
            }
    		$set_col_tk["TaxableAmt"] =  'TaxableAmt';
    		$set_col_tk["GSTAmt"] =  'GSTAmt';
    		$set_col_tk["BillAmt"] =  'BillAmt';
           
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    	      $total_taxable_amt = 0;
               $total_gst_amt = 0;
               $total_bill_amt = 0;
           
                    foreach ($body_data as $key => $value) {
                        /*if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                        if($value["Purchamt"] == 0 || $value["Purchamt"] == 0.00){
                
                   }else{
                   $list_add = [];
                   	
                   	$list_add[] = substr(_d($value["Transdate"]),0,10);
                if($gst_type !== "2"){   
                   foreach ($GSTType_unq as $value2) {
                       $match1 = 0;
                       $taxAmt = 0;
                       $cgstAmt = 0;
                       $sgstAmt = 0;
                       $igstAmt = 0;
                       foreach ($GstTypeWiseValue as $key3 => $value3) {
                           $gstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                           if($gstP2 == $value2 && substr($value["Transdate"],0,10) == substr($value3["TransDate"],0,10)){
                               $match1 = 1;
                               $taxAmt += $value3["taxableAmt"];
                               $cgstAmt += $value3["cgstsum"];
                               $sgstAmt += $value3["sgstsum"];
                               $igstAmt += $value3["igstsum"];
                                
                           }
                        }
                        if($match1 == 0){
                            
                            $list_add[] = "";
                            $list_add[] = "";
                            $list_add[] = "";
                            $list_add[] = "";
                        }else{
                            $list_add[] = sprintf('%0.2f', $taxAmt);
                            $list_add[] = sprintf('%0.2f', $cgstAmt);
                            $list_add[] = sprintf('%0.2f', $sgstAmt);
                            $list_add[] = sprintf('%0.2f', $igstAmt);
                        }  
                    }
                }
                    $total_taxable_amt +=$value["Purchamt"];
                   	$list_add[] = sprintf('%0.2f', $value["Purchamt"]);
                
                   	$gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                    $total_gst_amt +=$gst;
                   	$list_add[] = sprintf('%0.2f', $gst);
                   	 $bill_amt = $value["Purchamt"]+$gst;
                   $total_bill_amt +=$bill_amt;
                   	$list_add[] = sprintf('%0.2f', $bill_amt);
                   
                    $writer->writeSheetRow('Sheet1', $list_add);
                   }
               
               }
              
               $list_add = [];
                $list_add[] = "Total";
                if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt = 0;
                    $fcgstAmt = 0;
                    $fsgstAmt = 0;
                    $figstAmt = 0;
                    foreach ($GstTypeWiseValue as $key3 => $value3) {
                        $fgstP2 = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                        if($fgstP2 == $value2 ){
                            $ftaxAmt += $value3["taxableAmt"];
                            $fcgstAmt += $value3["cgstsum"];
                            $fsgstAmt += $value3["sgstsum"];
                            $figstAmt += $value3["igstsum"];
                        }
                    }
                    $list_add[] = $ftaxAmt;
                    $list_add[] = $fcgstAmt;
                    $list_add[] = $fsgstAmt;
                    $list_add[] = $figstAmt;
                    
                    }
            }
                $list_add[] = $total_taxable_amt;
                $list_add[] = $total_gst_amt;
                $list_add[] = $total_bill_amt;
                  
            $writer->writeSheetRow('Sheet1', $list_add);
          
    	 }else{
    	    
    	    // Bill Wise report 
    	    
    	    $list_add = [];
    	    
    	    $list_add[] = " ";
    	    //$list_add[] = "Account Details";
    	    $list_add[] = "";
    	    if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    //$list_add[] = "GST".$value2."%";
                    $list_add[] = "";
                }
    	    }
    	    $list_add[] = "";
    	    $writer->writeSheetRow('Sheet1', $list_add);
    	    //$writer->markMergedCell("A4:B4"); 
    	    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 1);  //merge cells
    	    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 2, $end_row = 3, $end_col = 3);  //merge cells
    	    if($gst_type !== "2"){
    	        $i = 4;
                $j = 7;
                foreach ($GSTType_unq as $value2) {
                    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = $i, $end_row = 3, $end_col = $j);  //merge cells
                    $i += 4;
                    $j += 4;
                }
    	    }
    	    $lastThree = $totColmn -2;
    	    $writer->markMergedCell('Sheet1', $start_row = 3, $start_col = $lastThree, $end_row = 3, $end_col = $totColmn);  //merge cells
    	    
            
            
            $set_col_tk = [];
    		$set_col_tk["BillNo"] =  'BillNo';
    		$set_col_tk["Date"] =  'Date';
    		$set_col_tk["Account Name"] =  'Account Name';
    		$set_col_tk["GSTIN"] =  'GSTIN';
    		if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $set_col_tk["Taxable'".$value2."'"] =  'Taxable'.sprintf('%0.2f', $value2).'%';
                    $set_col_tk["CGST'".($value2 / 2)."'"] =  'CGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["SGST'".($value2 / 2)."'"] =  'SGST'.sprintf('%0.2f', ($value2 / 2)).'%';
                    $set_col_tk["IGST'".$value2."'"] =  'IGST'.sprintf('%0.2f', $value2);
                }
            }
    		$set_col_tk["TaxableAmt"] =  'TaxableAmt';
    		$set_col_tk["GSTAmt"] =  'GSTAmt';
    		$set_col_tk["BillAmt"] =  'BillAmt';
           
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    	      $total_taxable_amt = 0;
               $total_gst_amt = 0;
               $total_bill_amt = 0;
           
                    foreach ($body_data as $key => $value) {
                    /*    if(($value["sgstamt"] == 0) && ($value["cgstamt"] == 0) && ($value["igstamt"] == 0)){*/
                    if($value["Purchamt"] == 0 || $value["Purchamt"] == 0.00){
                   }else{
                   $list_add = [];
                   	$list_add[] = $value["PurchID"];
                   	
                   	$list_add[] = substr(_d($value["Transdate"]),0,10);
                   	$list_add[] = $value["company"];
                   	$list_add[] = $value["gstno"];
                if($gst_type !== "2"){   
                   foreach ($GSTType_unq as $value2) {
                       $match = 0;
                       foreach ($GstTypeWiseValue as $key3 => $value3) {
                           $gstP = $value3['igst'] + $value3['cgst'] + $value3['sgst'];
                           if($gstP == $value2 && $value["PurchID"] == $value3["OrderID"]){
                               $match = 1;
                                $list_add[] = sprintf('%0.2f', $value3["taxableAmt"]);
                                $list_add[] = sprintf('%0.2f', $value3["cgstsum"]);
                                $list_add[] = sprintf('%0.2f', $value3["sgstsum"]);
                                $list_add[] = sprintf('%0.2f', $value3["igstsum"]);
                           }
                        }
                        if($match == "0"){
                            $list_add[] = '';
                            $list_add[] = '';
                            $list_add[] = '';
                            $list_add[] = '';
                        }  
                    }
                }
                    $total_taxable_amt +=$value["Purchamt"];
                   	$list_add[] = sprintf('%0.2f', $value["Purchamt"]);
                   	$gst = $value["sgstamt"]+$value["cgstamt"]+$value["igstamt"];
                    $total_gst_amt +=$gst;
                   	$list_add[] = sprintf('%0.2f', $gst);
                   	 $bill_amt = $value["Purchamt"]+$gst;
                   $total_bill_amt +=$bill_amt;
                   	$list_add[] = sprintf('%0.2f', $bill_amt);
                   
                    $writer->writeSheetRow('Sheet1', $list_add);
                   }
               }
              
               $list_add = [];
                $list_add[] = "Total";
                $list_add[] = "";
                $list_add[] = "";
                $list_add[] = "";
            if($gst_type !== "2"){
                foreach ($GSTType_unq as $value2) {
                    $ftaxAmt2 = 0;
                    $fcgstAmt2 = 0;
                    $fsgstAmt2 = 0;
                    $figstAmt2 = 0;
                    foreach ($GstTypeWiseValue as $keyf => $valuef) {
                           $gstPF = $valuef['igst'] + $valuef['cgst'] + $valuef['sgst'];
                           if($gstPF == $value2 ){
                                $ftaxAmt2 += $valuef['taxableAmt'];
                                $fcgstAmt2 += $valuef['cgstsum'];
                                $fsgstAmt2 += $valuef['sgstsum'];
                                $figstAmt2 += $valuef['igstsum'];
                           }
                    }
                    $list_add[] = sprintf('%0.2f', $ftaxAmt2);
                    $list_add[] = sprintf('%0.2f', $fcgstAmt2);
                    $list_add[] = sprintf('%0.2f', $fsgstAmt2);
                    $list_add[] = sprintf('%0.2f', $figstAmt2);
                }
            }
                $list_add[] = sprintf('%0.2f', $total_taxable_amt);
                $list_add[] = sprintf('%0.2f', $total_gst_amt);
                $list_add[] = sprintf('%0.2f', $total_bill_amt);
                  
            $writer->writeSheetRow('Sheet1', $list_add);
          
    	 }
    		
    		// empty row
    	
    	
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'GST Purchase Report.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
//===================== Load E-Way Bill List Page ===============================
    public function EWayBillList()
    {
        if (!has_permission_new('EWayBillReport', '', 'view')) {
            access_denied('EWayBillReport');
        }
        $title = "E-Way Bill Report";
        $data['title'] = $title;
         $this->load->model('misc_reports_model');
         $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/EWayBill', $data);
    }
//====================== Get E-Way Bill Report =================================
    public function GetEWayBillReport()
    {
        if (!has_permission_new('EWayBillReport', '', 'view')) {
            access_denied('EWayBillReport');
        }
        $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
        );
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $EWayBillData = $this->e_filling_model->GetEWayBillReport($filterdata);
        $srNo = 1;
        $html7 = '';
        foreach($EWayBillData as $key=>$val)
        {
            $html7 .= '<tr>';
            $html7 .= '<td style="text-align:center;">'.$srNo.'</td>';
            $html7 .= '<td style="text-align:center;">'._d(substr($val["Transdate"],0,10)).'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["SalesID"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["ConsolidatedEWayBillNo"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["ewaybill_no"].'</td>';
            $html7 .= '<td style="text-align:center;">'._d($val["ewaybill_date"]).'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["ewaybill_valid_upto"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["OrderID"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["ChallanID"].'</td>';
            $html7 .= '<td>'.$val["company"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["gstno"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["state"].'</td>';
            $html7 .= '<td align="right">'.number_format($val["BillAmt"],2).'</td>';
            $html7 .= '<td align="right"></td>';
            $html7 .= '</tr>';
            $srNo++;
        }
        echo json_encode($html7);
        die;
    }
//===================== Export E Way Bill Report ===============================
    public function ExportEWayBillReport()
    {
        if (!has_permission_new('EWayBillReport', '', 'export')) {
            access_denied('EWayBillReport');
        }
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
            $this->load->model('misc_reports_model');
    	    $selected_company_details    = $this->misc_reports_model->get_company_detail();
    		$writer = new XLSXWriter();
    		
    		$company_name = array($selected_company_details->company_name);
    		
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 11);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 11);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		$filterdata = array(
               'from_date' => $this->input->post('from_date'),
               'to_date'  => $this->input->post('to_date')
            );
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
    	
            $html .= ' Report form :'.$from_date.', To :'.$to_date.'';
            $filter = array($html);
            $writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 11); //merge cells
    		$writer->writeSheetRow('Sheet1', $filter);
    		$set_col_tk = [];
    		$set_col_tk["Invoice Date"] =  'Invoice Date';
    		$set_col_tk["Invoice No"] =  'Invoice No';
    		$set_col_tk["Consolidate E-Way Bill"] =  'Consolidate E-Way Bill';
    		$set_col_tk["E-Way Bill No"] =  'E-Way Bill No';
    		$set_col_tk["E-Way Bill Date"] =  'E-Way Bill Date';
    		$set_col_tk["E-Way Bill Upto"] =  'E-Way Bill Upto';
    		$set_col_tk["OrderID"] =  'OrderID';
    		$set_col_tk["ChallanID"] =  'ChallanID';
    		$set_col_tk["Party Name"] =  'Party Name';
    		$set_col_tk["GSTIN"] =  'GSTIN';
    		$set_col_tk["State"] =  'State';
    		$set_col_tk["Bill Amt"] =  'Bill Amt';
    		$set_col_tk["Status On Gst Portal"] =  'Status On Gst Portal';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
    		
            $EWayBillData = $this->e_filling_model->GetEWayBillReport($filterdata);
    		foreach($EWayBillData as $key=>$value){
    		 
    		    $list_add = [];
    		    $list_add[] = substr(_d($value["Transdate"]),0,10);
    		    $list_add[] = $value["SalesID"];
    		    $list_add[] = $value["ConsolidatedEWayBillNo"];
    		    $list_add[] = $value["ewaybill_no"];
    		    $list_add[] = _d($value["ewaybill_date"]);
    		    $list_add[] = _d($value["ewaybill_valid_upto"]);
    		    $list_add[] = $value["OrderID"];
    		    $list_add[] = $value["ChallanID"];
    		    $list_add[] = $value["company"];
    		    $list_add[] = $value["gstno"];
    		    $list_add[] = $value["state"];
    		    $list_add[] = sprintf('%0.2f',$value["BillAmt"]);
    		    $list_add[] = '';
                $writer->writeSheetRow('Sheet1', $list_add);
    		}
    	
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'E-WayBill Report.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
//===================== Load E-Invoice List Page ===============================
    public function EInvoiceList()
    {
        if (!has_permission_new('EInvoiceReport', '', 'view')) {
            access_denied('EInvoiceReport');
        }
        $title = "E-Invoice Report";
        $data['title'] = $title;
         $this->load->model('misc_reports_model');
         $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/E-Invoice', $data);
    }
//====================== Get E-Invoice Report ==================================
    public function GetEInvoiceReport()
    {
        if (!has_permission_new('EInvoiceReport', '', 'view')) {
            access_denied('EInvoiceReport');
        }
        $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
        );
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $EInvoiceData = $this->e_filling_model->GetEInvoiceReport($filterdata);
        $srNo = 1;
        $html7 = '';
        foreach($EInvoiceData as $key=>$val)
        {
            $html7 .= '<tr>';
            $html7 .= '<td style="text-align:center;">'.$srNo.'</td>';
            $html7 .= '<td style="text-align:center;">'._d(substr($val["Transdate"],0,10)).'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["SalesID"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["ackno"].'</td>';
            $html7 .= '<td style="text-align:center;">'._d(substr($val["ackdate"],0,10)).'</td>';
            
            $html7 .= '<td style="text-align:center;">'.$val["OrderID"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["ChallanID"].'</td>';
            $html7 .= '<td>'.$val["company"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["gstno"].'</td>';
            $html7 .= '<td style="text-align:center;">'.$val["state"].'</td>';
            $html7 .= '<td align="right">'.number_format($val["BillAmt"],2).'</td>';
            $html7 .= '<td>'.$val["irn"].'</td>';
            $html7 .= '<td></td>';
            $html7 .= '</tr>';
            $srNo++;
        }
        echo json_encode($html7);
        die;
    }
//===================== Export EInvoice Report =================================
    public function ExportEInvoiceReport()
    {
        if (!has_permission_new('EInvoiceReport', '', 'export')) {
            access_denied('EInvoiceReport');
        }
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
            $this->load->model('misc_reports_model');
    	    $selected_company_details    = $this->misc_reports_model->get_company_detail();
    		$writer = new XLSXWriter();
    		
    		$company_name = array($selected_company_details->company_name);
    		
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 11);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 11);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		$filterdata = array(
               'from_date' => $this->input->post('from_date'),
               'to_date'  => $this->input->post('to_date')
            );
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
    	
            $html .= ' Report form :'.$from_date.', To :'.$to_date.'';
            $filter = array($html);
            $writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 11); //merge cells
    		$writer->writeSheetRow('Sheet1', $filter);
    		$set_col_tk = [];
    		$set_col_tk["Invoice Date"] =  'Invoice Date';
    		$set_col_tk["Invoice No"] =  'Invoice No';
    		$set_col_tk["Acknowledge No"] =  'Acknowledge No';
    		$set_col_tk["Acknowledge Date"] =  'Acknowledge Date';
    		$set_col_tk["OrderID"] =  'OrderID';
    		$set_col_tk["ChallanID"] =  'ChallanID';
    		$set_col_tk["Party Name"] =  'Party Name';
    		$set_col_tk["GSTIN"] =  'GSTIN';
    		$set_col_tk["State"] =  'State';
    		$set_col_tk["Bill Amt"] =  'Bill Amt';
    		$set_col_tk["IRN"] =  'IRN';
    		$set_col_tk["Status On Gst Portal"] =  'Status On Gst Portal';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
    		
            $EInvoiceData = $this->e_filling_model->GetEInvoiceReport($filterdata);
    		foreach($EInvoiceData as $key=>$value){
    		    $list_add = [];
    		    $list_add[] = substr(_d($value["Transdate"]),0,10);
    		    $list_add[] = $value["SalesID"];
    		    $list_add[] = $value["ackno"];
    		    $list_add[] = substr(_d($value["ackdate"]),0,10);
    		    $list_add[] = $value["OrderID"];
    		    $list_add[] = $value["ChallanID"];
    		    $list_add[] = $value["company"];
    		    $list_add[] = $value["gstno"];
    		    $list_add[] = $value["state"];
    		    $list_add[] = sprintf('%0.2f',$value["BillAmt"]);
    		    $list_add[] = $value["irn"];
    		    $list_add[] = '';
                $writer->writeSheetRow('Sheet1', $list_add);
    		}
    	
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'E-Invoice Report.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
//====================== Load GSTR 1 Page ======================================
    public function GSTR1()
    {
        if (!has_permission_new('GGSTR_1', '', 'view')) {
            access_denied('GGSTR_1');
        }
        $title = _l('GST-R 1');
        $data['title'] = $title;
         $this->load->model('misc_reports_model');
         $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/GSTR1', $data);
    }
    
    public function GSTR1Reports()
    {  
        $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
          );
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
         
        
        $B2B_data2 = $this->e_filling_model->GetDataForB2B2($filterdata);
        /**/
        $DOCS_data = $this->e_filling_model->get_data_for_DOCS($filterdata);
        $EXEMP_data = $this->e_filling_model->get_data_for_EXEMP($filterdata);
        
        $HSN_data = $this->e_filling_model->get_data_for_HSN($filterdata);
        $HSNMaster = $this->e_filling_model->getHsnMaster($filterdata);
        $SRT_HSN = $this->e_filling_model->GetSRT_HSN($filterdata);
        $CD_HSN = $this->e_filling_model->GetCD_HSN($filterdata);
        
        $HSN_dataSRT = $this->e_filling_model->get_data_for_HSNSRT($filterdata);
        $HSN_dataCD = $this->e_filling_model->get_data_for_HSNCD($filterdata);
        $HSN_dataDD = $this->e_filling_model->get_data_for_HSNDD($filterdata);
        
        $html = '';
        $srNo = 001;
        $InvSum = 0;
        $TaxableSum = 0;
        $TaxSum = 0;
        
//====================== HSN Report ============================================
        $html7 = '';
        $srNo7 = 001;
        $BillQtyTotal = 0.00;
        $billAmtTotal = 0.00;
        $taxAmtTotal = 0.00;
        $ISUMTotal = 0.00;
        $CSUMTotal = 0.00;
        $SSUMTotal = 0.00;
        
        $HSNList = array();
        $HSNTaxrate = array();
        foreach ($HSN_data as $hsnkey => $hsnvalue) {
            if($hsnvalue["hsn_code"] !== ''){
                array_push($HSNList,$hsnvalue["hsn_code"]);
                $tax = $hsnvalue["igst"] + $hsnvalue["sgst"] + $hsnvalue["cgst"];
                array_push($HSNTaxrate,$tax);
            }
        }
        
        foreach ($CD_HSN as $hsnkey1 => $hsnvalue1) {
            if($hsnvalue1["hsncode"] !== ''){
                array_push($HSNList,$hsnvalue1["hsncode"]);
                $tax = $hsnvalue1["igst"] + $hsnvalue1["sgst"] + $hsnvalue1["cgst"];
                array_push($HSNTaxrate,$tax);
            }
        }
        
        foreach ($SRT_HSN as $hsnkey2 => $hsnvalue2) {
            if($hsnvalue2["hsn_code"] !== ''){
                array_push($HSNList,$hsnvalue2["hsn_code"]);
                $tax = $hsnvalue2["igst"] + $hsnvalue2["sgst"] + $hsnvalue2["cgst"];
                array_push($HSNTaxrate,$tax);
            }
        }
        
        $HSNList = array_unique($HSNList);
        $HSNTaxrate = array_unique($HSNTaxrate);
        //echo "<pre>";
        //print_r($SRT_HSN);
        //die;
        foreach ($HSNList as $hsnCode) {
            $hsnDesc = "";
            foreach ($HSNMaster as $master) {
                if($hsnCode == $master["name"]){
                    $hsnDesc = $master["hsndesc"];
                }
            }
            foreach ($HSNTaxrate as $hsnTax) {
                $match = 0;
                $BillQty = 0.00;
                $billAmt = 0.00;
                $taxAmt = 0.00;
                $ISUM = 0.00;
                $CSUM = 0.00;
                $SSUM = 0.00;
                foreach ($HSN_data as $key7 => $value7) {
                    $gstPer = $value7["igst"] + $value7["sgst"] + $value7["cgst"];
                    if($value7['hsn_code'] == $hsnCode && $hsnTax == $gstPer){
                        $BillQty += $value7["BilledQtySum"];
                        $billAmt += $value7["BillAmt"];
                        $taxAmt += $value7["TaxableAmt"];
                        $ISUM += $value7["IGSTSUM"];
                        $CSUM += $value7["CGSTSUM"];
                        $SSUM += $value7["SGSTSUM"];
                        $match = 1;
                    }
                }
            // Minus SRT values    
                foreach ($HSN_dataSRT as $keySRT => $valueSRT) {
                    $gstPer2 = $valueSRT["igst"] + $valueSRT["sgst"] + $valueSRT["cgst"];
                    if($valueSRT['hsn_code'] == $hsnCode && $hsnTax == $gstPer2){
                        $BillQty -= $valueSRT["BilledQtySum"];
                        $billAmt -= $valueSRT["BillAmt"];
                        $taxAmt -= $valueSRT["TaxableAmt"];
                        $ISUM -= $valueSRT["IGSTSUM"];
                        $CSUM -= $valueSRT["CGSTSUM"];
                        $SSUM -= $valueSRT["SGSTSUM"];
                        $match = 1;
                    }
                }
            // Minus Credit value values    
                foreach ($HSN_dataCD as $keyCD => $valueCD) {
                    $gstPer3 = $valueCD["igst"] + $valueCD["sgst"] + $valueCD["cgst"];
                    if($valueCD['hsncode'] == $hsnCode && $hsnTax == $gstPer3 && $valueCD["SalesID"] != NULL){
                            $BillQty -= $valueCD["BilledQtySum"];
                            $billAmt -= $valueCD["BillAmt"];
                            $taxAmt -= $valueCD["TaxableAmt"];
                            $ISUM -= $valueCD["IGSTSUM"];
                            $CSUM -= $valueCD["CGSTSUM"];
                            $SSUM -= $valueCD["SGSTSUM"];
                            $match = 1;
                    }
                }
            // ADD Debit value values    
                foreach ($HSN_dataDD as $keyDD => $valueDD) {
                    $gstPer3 = $valueDD["igst"] + $valueDD["sgst"] + $valueDD["cgst"];
                    if($valueDD['hsncode'] == $hsnCode && $hsnTax == $gstPer3 && $valueDD["SalesID"] != NULL){
                            $BillQty += $valueDD["BilledQtySum"];
                            $billAmt += $valueDD["BillAmt"];
                            $taxAmt += $valueDD["TaxableAmt"];
                            $ISUM += $valueDD["IGSTSUM"];
                            $CSUM += $valueDD["CGSTSUM"];
                            $SSUM += $valueDD["SGSTSUM"];
                            $match = 1;
                    }
                }
                if($match == "1"){
                   $html7 .= '<tr>'; 
                   $html7 .= '<td align="center">'.$srNo7.'</td>'; 
                   $html7 .= '<td align="center">'.$hsnCode.'</td>'; 
                   $html7 .= '<td>'.$hsnDesc.'</td>'; 
                   $html7 .= '<td align="center">PCS-PIECES</td>'; 
                   $html7 .= '<td align="right">'.number_format($BillQty,2).'</td>'; 
                   $BillQtyTotal += $BillQty;
                   $html7 .= '<td align="right">'.number_format($billAmt,2).'</td>'; 
                   $billAmtTotal += $billAmt;
                   $html7 .= '<td align="right">'.number_format($taxAmt,2).'</td>'; 
                   $taxAmtTotal += $taxAmt;
                   $html7 .= '<td align="right">'.number_format($ISUM,2).'</td>'; 
                   $ISUMTotal += $ISUM;
                   $html7 .= '<td align="right">'.number_format($CSUM,2).'</td>'; 
                   $CSUMTotal += $CSUM;
                   $html7 .= '<td align="right">'.number_format($SSUM,2).'</td>'; 
                   $SSUMTotal += $SSUM;
                   $html7 .= '<td></td>'; 
                   $html7 .= '<td align="center">'.number_format($hsnTax,2).'</td>'; 
                   $html7 .= '<tr>'; 
                   $srNo7++;
                }
            }
        }
        
        $html7 .= '<tr>';
        $html7 .= '<td></td>';
        $html7 .= '<td>Total</td>';
        $html7 .= '<td></td>';
        $html7 .= '<td></td>';
        $html7 .= '<td align="right">'.number_format($BillQtyTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($billAmtTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($taxAmtTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($ISUMTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($CSUMTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($SSUMTotal,2).'</td>';
        $html7 .= '<td></td>';
        $html7 .= '<td></td>';
        $html7 .= '</tr>';
//=================== B2B Tab Details ==========================================
        foreach ($B2B_data2['HistoryData'] as $key => $value) {
            $GSTPER = $value['igst'] + $value['cgst'] + $value['sgst'];
            if($value["BillAmt"] == "0.00" || $value["BillAmt"] == NULL || $value["BillAmt"] == ''){
                
            }else{
                $GST = 0.00;
                $GST = $value['igst'] + $value['cgst'] + $value['sgst'];
                if(number_format($GST,2) !== '0.00'){
                    $html .= '<tr>';
                    $html .= '<td align="center">'.$srNo.'</td>';
                    foreach ($B2B_data2['SaleData'] as $key2 => $value2) {
                        
                        if($value2['SalesID'] == $value["TransID"]){
							$url = '"'.admin_url('order/order/' . $value2['OrderID']).'"';
                            $html .= '<td>'.$value2["company"].'</td>';
                            $html .= '<td>'.$value2["gstno"].'</td>';
                            $html .= '<td ><a href='.$url.' target="_blank">'.$value["TransID"].'</a></td>';
                            $html .= '<td align="center">'._d(substr($value["TransDate2"],0,10)).'</td>';
                            $invAmt = 0;
                            $invAmt = $value2["INVAMT"];
                            $invAmt = round($invAmt);
                            $html .= '<td align="right">'.number_format($invAmt,2).'</td>';
                            $InvSum = $InvSum + $value2["INVAMT"];
                            $html .= '<td>'.$value2["state"].'-'.$value2["state"].'</td>';
                        }
                    }
                    $html .= '<td align="center">N</td>';
                    $html .= '<td align="center">Regular</td>';
                    $html .= '<td></td>';
                    
                    $html .= '<td align="center">'.number_format($GST,2).'</td>';
                    $html .= '<td align="right">'.number_format($value["TaxAmt"],2).'</td>';
                    $html .= '<td align="right">'.number_format($value["TaxableAmt"],2).'</td>';
                    $TaxableSum += $value["TaxableAmt"];
                    $TaxSum += $value["TaxAmt"];
                    $html .= '<td></td>';
                    $html .= '</tr>';
                    $srNo++;
                }
            }
        }
		
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td>Total</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td align="right">'.number_format(round($InvSum),2).'</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td align="right">'.number_format($TaxSum,2).'</td>';
        $html .= '<td align="right">'.number_format($TaxableSum,2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
//======================== B2CL ================================================
        $html2 = '';
        $srNo2 = 001;
        $InvSum2 = 0;
        $TaxableSum2 = 0;
        foreach ($B2B_data2['SaleData2'] as $key3 => $value3) {
            if($value3["INVAMT"] == "0.00" || $value3["INVAMT"] == NULL || $value3["INVAMT"] == ''){
                
            }else{
                foreach ($B2B_data2['HistoryData2'] as $key4 => $value4) {
                    $invAmt2 = 0;
                    if($value3['SalesID'] == $value4['TransID']){
                        $GST = 0.00;
                        $GST = $value4['igst'] + $value4['cgst'] + $value4['sgst'];
                        if(number_format($GST,2) !== '0.00'){
                            $html2 .= '<tr>';
                            $html2 .= '<td align="center">'.$srNo2.'</td>';
                            $html2 .= '<td>'.$value4["TransID"].'</td>';
                            $html2 .= '<td align="center">'._d(substr($value3["BillDate"],0,10)).'</td>';
                            $invAmt2 = $value3["INVAMT"] + $value3["tcsAmt"];
                            $invAmt2 = round($invAmt2);
                            $html2 .= '<td align="right">'.number_format($invAmt2,2).'</td>';
                            $InvSum2 = $InvSum2 + $value3["INVAMT"] + $value3["tcsAmt"];
                            $html2 .= '<td>'.$value3["state"].'-'.$value3["state_name"].'</td>';
                            $html2 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html2 .= '<td align="right">'.number_format($value4["TaxableAmt"],2).'</td>';
                            $TaxableSum2 += $value4["TaxableAmt"];
                            $html2 .= '<td></td>';
                            $html2 .= '<td></td>';
                            $html2 .= '</tr>';
                            $srNo2++;
                        }
                    }
                }
            }
        }
        $html2 .= '<tr>';
        $html2 .= '<td></td>';
        $html2 .= '<td>Total</td>';
        $html2 .= '<td></td>';
        $html2 .= '<td align="right">'.number_format(round($InvSum2),2).'</td>';
        $html2 .= '<td></td>';
        $html2 .= '<td></td>';
        $html2 .= '<td align="right">'.number_format($TaxableSum2,2).'</td>';
        $html2 .= '<td></td>';
        $html2 .= '<td></td>';
        $html2 .= '</tr>';
        
//=========================== B2CS =============================================
        $srNo3 = 1;
        $TaxableSum3 = 0;
        $html3 = '';
        foreach ($B2B_data2['B2CS1'] as $key5 => $value5) {
            $GST = 0.00;
            $GST = $value5['sgst'] + $value5['cgst']+$value5['igst'];
            if(number_format($GST,2) !== '0.00'){
                $html3 .= '<tr>';
                $html3 .= '<td align="center">'.$srNo3.'</td>';
                $html3 .= '<td>OE</td>';
                $html3 .= '<td>'.$value5['state'].'</td>';
                $html3 .= '<td align="center">'.number_format($GST,2).'</td>';
                $html3 .= '<td align="right">'.number_format($value5['TaxableAmt'],2).'</td>';
                $TaxableSum3 += $value5['TaxableAmt'];
                $html3 .= '<td></td>';
                $html3 .= '<td></td>';
                $html3 .= '</tr>';
                $srNo3++;
            }
                
        }
        
        foreach ($B2B_data2['B2CS2'] as $key6 => $value6) {
            $GST = 0.00;
            $GST = $value6['sgst'] + $value6['cgst']+$value6['igst'];
            if(number_format($GST,2) !== "0.00"){
                $html3 .= '<tr>';
                $html3 .= '<td align="center">'.$srNo3.'</td>';
                $html3 .= '<td>OE</td>';
                $html3 .= '<td>'.$value6['state'].'</td>';
                
                $html3 .= '<td align="center">'.number_format($GST,2).'</td>';
                $html3 .= '<td align="right">'.number_format($value6['TaxableAmt'],2).'</td>';
                $TaxableSum3 += $value6['TaxableAmt'];
                $html3 .= '<td></td>';
                $html3 .= '<td></td>';
                $html3 .= '</tr>';
                $srNo3++;
            }
                
        }
        
        $html3 .= '<tr>';
        $html3 .= '<td></td>';
        $html3 .= '<td>Total</td>';
        $html3 .= '<td></td>';
        $html3 .= '<td></td>';
        $html3 .= '<td align="right">'.number_format($TaxableSum3,2).'</td>';
        $html3 .= '<td></td>';
        $html3 .= '<td></td>';
        $html3 .= '</tr>';
        
//=========================== CDNR =============================================
        $srNo4 = 1;
        $html4 = '';
        $TaxableSum4 = 0;
        $InvoiveSum4 = 0;
        foreach ($B2B_data2['CDNR11'] as $key77 => $value77) {
                foreach ($B2B_data2['CDNR1'] as $key7 => $value7) {
                    $GST = 0.00;
                    if($value77['SalesID']==$value7['TransID']){
                        
                        if($value7["BillAmt"] == "0.00" || $value7["BillAmt"] == NULL || $value7["BillAmt"] == ''){
                        
                        }else{
                            $GST = $value7["cgst"] + $value7["sgst"] + $value7["igst"];
                            if(number_format($GST,2) !== "0.00"){
                                $html4 .= '<tr>';
                                $html4 .= '<td align="center">'.$srNo4.'</td>';
                                $html4 .= '<td>'.$value77["company"].'</td>';
                                $html4 .= '<td>'.$value77["gstno"].'</td>';
                                $html4 .= '<td>'.$value7["TransID"].'</td>';
                                $html4 .= '<td align="center">'._d(substr($value77["SaleDate"],0,10)).'</td>';
                                $html4 .= '<td>'.$value7["OrderID"].'</td>';
                                $html4 .= '<td align="center">'._d(substr($value7["SaleRTNDate"],0,10)).'</td>';
                                $html4 .= '<td align="center">C</td>';
                                $html4 .= '<td>01 SalesReturn</td>';
                                $html4 .= '<td>'.$value77["state"].'</td>';
                                $html4 .= '<td align="right">'.number_format(round($value7["BillAmt"]),2).'</td>';
                                
                                $InvoiveSum4 += round($value7["BillAmt"]);
                                
                                $html4 .= '<td align="center">'.number_format($GST,2).'</td>';
                                $html4 .= '<td align="right">'.number_format($value7["TaxableAmt"],2).'</td>';
                                $TaxableSum4 += $value7["TaxableAmt"];
                                $html4 .= '<td align="right">0.00</td>';
                                $html4 .= '<td align="center">N</td>';
                                
                                $html4 .= '</tr>';
                                $srNo4++;
                            }
                    }   
                }
            } 
        }
        
        foreach ($B2B_data2['CDNR111'] as $key777 => $value777) {
            foreach ($B2B_data2['CDNR2'] as $key7 => $value7) {
                $GST = 0.00;
                if($value777['PurchID']==$value7['TransID']){
                    
                    if($value7["BillAmt"] == "0.00" || $value7["BillAmt"] == NULL || $value7["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value7["cgst"] + $value7["sgst"] + $value7["igst"];
                        if(number_format($GST,2) !== "0.00"){
                            $html4 .= '<tr>';
                            $html4 .= '<td align="center">'.$srNo4.'</td>';
                            $html4 .= '<td>'.$value777["company"].'</td>';
                            $html4 .= '<td>'.$value777["vat"].'</td>';
                            $html4 .= '<td>'.$value777["PurchID"].'</td>';
                            $html4 .= '<td align="center">'._d(substr($value777["SaleDate"],0,10)).'</td>';
                            $html4 .= '<td>'.$value7["billno"].'</td>';
                            $html4 .= '<td align="center">'._d(substr($value7["CDDate"],0,10)).'</td>';
                            $html4 .= '<td align="center">C</td>';
                            $html4 .= '<td>01 SalesReturn</td>';
                            $html4 .= '<td>'.$value777["state"].'</td>';
                            $html4 .= '<td align="right">'.number_format(round($value7["BillAmt"]),2).'</td>';
                            
                            $InvoiveSum4 += round($value7["BillAmt"]);
                            
                            $html4 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html4 .= '<td align="right">'.number_format($value7["TaxableAmt"],2).'</td>';
                            $TaxableSum4 += $value7["TaxableAmt"];
                            $html4 .= '<td align="right">0.00</td>';
                            $html4 .= '<td align="center">N</td>';
                            
                            $html4 .= '</tr>';
                            $srNo4++;
                        }
                    }   
                }
            } 
        }
        
        foreach ($B2B_data2['CDNR22'] as $key88 => $value88) {
            foreach ($B2B_data2['CDNR2'] as $key8 => $value8) {
                if($value88['SalesID']==$value8['TransID']){
                    $GST = 0.00;
                    if($value8["BillAmt"] == "0.00" || $value8["BillAmt"] == NULL || $value8["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value8["cgst"] + $value8["sgst"] + $value8["igst"];
                        if(number_format($GST,2) !== "0.00"){
                            $html4 .= '<tr>';
                            $html4 .= '<td align="center">'.$srNo4.'</td>';
                            $html4 .= '<td>'.$value88["company"].'</td>';
                            $html4 .= '<td>'.$value88["gstno"].'</td>';
                            $html4 .= '<td>'.$value88["SalesID"].'</td>';
                            $html4 .= '<td align="center">'._d(substr($value88["SaleDate"],0,10)).'</td>';
                            $html4 .= '<td>'.$value8["billno"].'</td>';
                            $html4 .= '<td align="center">'._d(substr($value8["CDDate"],0,10)).'</td>';
                            $html4 .= '<td align="center">C</td>';
                            $html4 .= '<td>01 SalesReturn</td>';
                            $html4 .= '<td>'.$value88["state"].'</td>';
                            $html4 .= '<td align="right">'.number_format(round($value8["BillAmt"]),2).'</td>';
                            //$InvoiveSum4 += round($value8["BillAmt"]);
                            
                            $html4 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html4 .= '<td align="right">'.number_format($value8["TaxableAmt"],2).'</td>';
                            //$TaxableSum4 += $value8["TaxableAmt"];
                            if($value8["ttype"] == "C"){
                                $InvoiveSum4 += round($value8["BillAmt"]);
                                $TaxableSum4 += $value8["TaxableAmt"];
                            }else{
                                $InvoiveSum4 = $InvoiveSum4 - round($value8["BillAmt"]);
                                $TaxableSum4 = $TaxableSum4 - $value8["TaxableAmt"];
                            }
                            $html4 .= '<td align="right">0.00</td>';
                            $html4 .= '<td align="center">N</td>';
                            
                            $html4 .= '</tr>';
                            $srNo4++;
                        }
                    }   
                }
            } 
        }
        
        $html4 .= '<tr>';
        $html4 .= '<td></td>';
        $html4 .= '<td>Total</td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        $html4 .= '<td align="right">'.number_format($InvoiveSum4,2).'</td>';
        $html4 .= '<td></td>';
        $html4 .= '<td align="right">'.number_format($TaxableSum4,2).'</td>';
        $html4 .= '<td></td>';
        $html4 .= '<td></td>';
        
        $html4 .= '</tr>';
        
//============================ CDNUR ===========================================
        $srNo5 = 1;
        $html5 = '';
        $TaxableSum5 = 0;
        $InvoiveSum5 = 0;
        foreach ($B2B_data2['CDNUR11'] as $key99 => $value99) {
            foreach ($B2B_data2['CDNUR1'] as $key9 => $value9) {
                if($value99['SalesID']==$value9['TransID']){
                    $GST = 0.00;
                    if($value9["BillAmt"] == "0.00" || $value9["BillAmt"] == NULL || $value9["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value9["cgst"] + $value9["sgst"] + $value9["igst"];
                        if(number_format($GST,2) !== "0.00"){
                            $html5 .= '<tr>';
                            $html5 .= '<td align="center">'.$srNo5.'</td>';
                            $html5 .= '<td>'.$value99["company"].'</td>';
                            $html5 .= '<td>'.$value99["gstno"].'</td>';
                            $html5 .= '<td>'.$value9["TransID"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value99["SaleDate"],0,10)).'</td>';
                            $html5 .= '<td>'.$value9["OrderID"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value9["SaleRTNDate"],0,10)).'</td>';
                            $html5 .= '<td align="center">C</td>';
                            $html5 .= '<td>01 SalesReturn</td>';
                            $html5 .= '<td>'.$value99["state"].'</td>';
                            $html5 .= '<td align="right">'.number_format(round($value9["BillAmt"]),2).'</td>';
                            $InvoiveSum5 += round($value9["BillAmt"]);
                            
                            $html5 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html5 .= '<td align="right">'.number_format($value9["TaxableAmt"],2).'</td>';
                            $TaxableSum5 += $value9["TaxableAmt"];
                            $html5 .= '<td align="right">0.00</td>';
                            $html5 .= '<td align="center">N</td>';
                            
                            $html5 .= '</tr>';
                            $srNo5++;
                        }
                    }   
                }
            } 
        }
        
        foreach ($B2B_data2['CDNUR11TRN'] as $key99 => $value99) {
            foreach ($B2B_data2['CDNUR1'] as $key9 => $value9) {
                if($value99['SalesID']==$value9['TransID']){
                    $GST = 0.00;
                    if($value9["BillAmt"] == "0.00" || $value9["BillAmt"] == NULL || $value9["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value9["cgst"] + $value9["sgst"] + $value9["igst"];
                        if(number_format($GST,2) !== "0.00"){
                            $html5 .= '<tr>';
                            $html5 .= '<td align="center">'.$srNo5.'</td>';
                            $html5 .= '<td>'.$value99["company"].'</td>';
                            $html5 .= '<td>'.$value99["gstno"].'</td>';
                            $html5 .= '<td>'.$value9["TransID"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value99["SaleDate"],0,10)).'</td>';
                            $html5 .= '<td>'.$value9["OrderID"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value9["SaleRTNDate"],0,10)).'</td>';
                            $html5 .= '<td align="center">C</td>';
                            $html5 .= '<td>01 SalesReturn</td>';
                            $html5 .= '<td>'.$value99["state"].'</td>';
                            $html5 .= '<td align="right">'.number_format(round($value9["BillAmt"]),2).'</td>';
                            $InvoiveSum5 += round($value9["BillAmt"]);
                            
                            $html5 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html5 .= '<td align="right">'.number_format($value9["TaxableAmt"],2).'</td>';
                            $TaxableSum5 += $value9["TaxableAmt"];
                            $html5 .= '<td align="right">0.00</td>';
                            $html5 .= '<td align="center">N</td>';
                            
                            $html5 .= '</tr>';
                            $srNo5++;
                        }
                    }   
                }
            } 
        }
        
        foreach ($B2B_data2['CDNUR22'] as $key1010 => $value1010) {
            foreach ($B2B_data2['CDNUR2'] as $key10 => $value10) {
                if($value1010['SalesID']==$value10['TransID']){
                    $GST = 0.00;
                    if($value10["BillAmt"] == "0.00" || $value10["BillAmt"] == NULL || $value10["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value10["cgst"] + $value10["sgst"] + $value10["igst"];
                        if(number_format($GST,2) !=="0.00"){
                            $html5 .= '<tr>';
                            $html5 .= '<td align="center">'.$srNo5.'</td>';
                            $html5 .= '<td>'.$value1010["company"].'</td>';
                            $html5 .= '<td>'.$value1010["gstno"].'</td>';
                            $html5 .= '<td>'.$value1010["SalesID"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value1010["SaleDate"],0,10)).'</td>';
                            $html5 .= '<td>'.$value10["billno"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value10["CDDate"],0,10)).'</td>';
                            $html5 .= '<td align="center">C</td>';
                            $html5 .= '<td>01 SalesReturn</td>';
                            $html5 .= '<td>'.$value1010["state"].'</td>';
                            $html5 .= '<td align="right">'.number_format(round($value10["BillAmt"]),2).'</td>';
                            //$InvoiveSum5 += round($value10["BillAmt"]);
                            if($value10["ttype"] == "C"){
                                $InvoiveSum5 += round($value10["BillAmt"]);
                                $TaxableSum5 += $value10["TaxableAmt"];
                            }else{
                                $InvoiveSum5 = $InvoiveSum5 - round($value10["BillAmt"]);
                                $TaxableSum5 = $TaxableSum5 - $value10["TaxableAmt"];
                            }
                            
                            $html5 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html5 .= '<td align="right">'.number_format($value10["TaxableAmt"],2).'</td>';
                            //$TaxableSum5 += $value10["TaxableAmt"];
                            $html5 .= '<td align="right">0.00</td>';
                            $html5 .= '<td align="center">N</td>';
                            
                            $html5 .= '</tr>';
                            $srNo5++;
                        }
                    }   
                }
            } 
        }
        
        foreach ($B2B_data2['CDNUR22TRN'] as $key1010 => $value1010) {
            foreach ($B2B_data2['CDNUR2'] as $key10 => $value10) {
                if($value1010['SalesID']==$value10['TransID']){
                    $GST = 0.00;
                    if($value10["BillAmt"] == "0.00" || $value10["BillAmt"] == NULL || $value10["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value10["cgst"] + $value10["sgst"] + $value10["igst"];
                        if(number_format($GST,2) !=="0.00"){
                            $html5 .= '<tr>';
                            $html5 .= '<td align="center">'.$srNo5.'</td>';
                            $html5 .= '<td>'.$value1010["company"].'</td>';
                            $html5 .= '<td>'.$value1010["gstno"].'</td>';
                            $html5 .= '<td>'.$value1010["SalesID"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value1010["SaleDate"],0,10)).'</td>';
                            $html5 .= '<td>'.$value10["billno"].'</td>';
                            $html5 .= '<td align="center">'._d(substr($value10["CDDate"],0,10)).'</td>';
                            $html5 .= '<td align="center">C</td>';
                            $html5 .= '<td>01 SalesReturn</td>';
                            $html5 .= '<td>'.$value1010["state"].'</td>';
                            $html5 .= '<td align="right">'.number_format(round($value10["BillAmt"]),2).'</td>';
                            //$InvoiveSum5 += round($value10["BillAmt"]);
                            if($value10["ttype"] == "C"){
                                $InvoiveSum5 += round($value10["BillAmt"]);
                                $TaxableSum5 += $value10["TaxableAmt"];
                            }else{
                                $InvoiveSum5 = $InvoiveSum5 - round($value10["BillAmt"]);
                                $TaxableSum5 = $TaxableSum5 - $value10["TaxableAmt"];
                            }
                            
                            $html5 .= '<td align="center">'.number_format($GST,2).'</td>';
                            $html5 .= '<td align="right">'.number_format($value10["TaxableAmt"],2).'</td>';
                            //$TaxableSum5 += $value10["TaxableAmt"];
                            $html5 .= '<td align="right">0.00</td>';
                            $html5 .= '<td align="center">N</td>';
                            
                            $html5 .= '</tr>';
                            $srNo5++;
                        }
                    }   
                }
            } 
        }
        
        
        $html5 .= '<tr>';
        $html5 .= '<td></td>';
        $html5 .= '<td>Total</td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        $html5 .= '<td align="right">'.number_format($InvoiveSum5,2).'</td>';
        $html5 .= '<td></td>';
        $html5 .= '<td align="right">'.number_format($TaxableSum5,2).'</td>';
        $html5 .= '<td></td>';
        $html5 .= '<td></td>';
        
        $html5 .= '</tr>';
        
//======================== EXEMP Report ========================================
        $html6 = '';
        $srNo6 = 001;
        $total = 0.00;
        //foreach ($EXEMP_data as $key6 => $value6) {
            
            $html6 .= '<tr>';
            $html6 .= '<td align="center">'.$srNo6.'</td>';
            $html6 .= '<td>Inter-State Supplies to registered persons</td>';
            $html6 .= '<td></td>';
            $html6 .= '<td align="right">'.number_format($EXEMP_data["InterGSTR"],2).'</td>';
            $total += $EXEMP_data["InterGSTR"];
            $html6 .= '<td></td>';
            $html6 .= '</tr>';
            $srNo6++;
            
            $html6 .= '<tr>';
            $html6 .= '<td align="center">'.$srNo6.'</td>';
            $html6 .= '<td>Intra-State Supplies to registered persons</td>';
            $html6 .= '<td></td>';
            $html6 .= '<td align="right">'.number_format($EXEMP_data["IntraGSTR"],2).'</td>';
            $total += $EXEMP_data["IntraGSTR"];
            $html6 .= '<td></td>';
            $html6 .= '</tr>';
            $srNo6++;
            
            $html6 .= '<tr>';
            $html6 .= '<td align="center">'.$srNo6.'</td>';
            $html6 .= '<td>Inter-State Supplies to Unregistered persons</td>';
            $html6 .= '<td></td>';
            $html6 .= '<td align="right">'.number_format($EXEMP_data["InterGSTUR"],2).'</td>';
            $total += $EXEMP_data["InterGSTUR"];
            $html6 .= '<td></td>';
            $html6 .= '</tr>';
            $srNo6++;
            
            $html6 .= '<tr>';
            $html6 .= '<td align="center">'.$srNo6.'</td>';
            $html6 .= '<td>Intra-State Supplies to Unregistered persons</td>';
            $html6 .= '<td></td>';
            $html6 .= '<td align="right">'.number_format($EXEMP_data["IntraGSTUR"],2).'</td>';
            $total += $EXEMP_data["IntraGSTUR"];
            $html6 .= '<td></td>';
            $html6 .= '</tr>';
            $srNo6++;
        //}
        
        $html6 .= '<tr>';
        $html6 .= '<td></td>';
        $html6 .= '<td>Total</td>';
        $html6 .= '<td></td>';
        $html6 .= '<td align="right">'.number_format($total,2).'</td>';
        $html6 .= '<td></td>';
        $html6 .= '</tr>';
       
        
        
        // Docs
        $srNo8 = 1;
        $html8 = '';
        
        $html8 .= '<tr>';
        $html8 .= '<td align="center">'.$srNo8.'</td>';
        $html8 .= '<td>Invoice for Outward Supply</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TStart"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TEnd"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TTotal"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TTotalC"].'</td>';
        $html8 .= '</tr>';
        $srNo8++;
        
        $html8 .= '<tr>';
        $html8 .= '<td align="center">'.$srNo8.'</td>';
        $html8 .= '<td>Invoice for Outward Supply</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BStart"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BEnd"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BTotal"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BTotalC"].'</td>';
        $html8 .= '</tr>';
        $srNo8++;
        
        $html8 .= '<tr>';
        $html8 .= '<td align="center">'.$srNo8.'</td>';
        $html8 .= '<td>Invoice for Outward Supply</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MStart"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MEnd"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MTotal"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MTotalC"].'</td>';
        $html8 .= '</tr>';
        $srNo8++;
        
        $response  = array();
        $response['B2B']= $html;
        $response['B2CL']= $html2;
        $response['B2CS']= $html3;
        $response['CDNR']= $html4;
        $response['CDNUR']= $html5;
        $response['EXEMP']= $html6;
        $response['HSN']= $html7;
        $response['DOCS']= $html8;
        echo json_encode($response);
    }
    
    public function GSTR1ReportsExport(){
        
        if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	    $filterdata = array(
               'from_date' => $this->input->post('from_date'),
               'to_date'  => $this->input->post('to_date')
              );
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            
            $B2B_data2 = $this->e_filling_model->GetDataForB2B2($filterdata);
            $DOCS_data = $this->e_filling_model->get_data_for_DOCS($filterdata);
            $EXEMP_data = $this->e_filling_model->get_data_for_EXEMP($filterdata);
            
            $HSN_data = $this->e_filling_model->get_data_for_HSN($filterdata);
            $HSNMaster = $this->e_filling_model->getHsnMaster($filterdata);
            $CD_HSN = $this->e_filling_model->GetCD_HSN($filterdata);
            $SRT_HSN = $this->e_filling_model->GetSRT_HSN($filterdata);
            //$HSN_Code = $this->e_filling_model->get_data_for_HSNCode($filterdata);
            $HSN_dataSRT = $this->e_filling_model->get_data_for_HSNSRT($filterdata);
            $HSN_dataCD = $this->e_filling_model->get_data_for_HSNCD($filterdata);
            $HSN_dataDD = $this->e_filling_model->get_data_for_HSNDD($filterdata);
            
            $this->load->model('misc_reports_model');
        	$selected_company_details    = $this->misc_reports_model->get_company_detail();
        	
        	$writer = new XLSXWriter();
        	
        	$company_name = array($selected_company_details->company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address);
    		
    		$header = array('SrNo' => '0','Account Name' => 'string','GSTIN' => 'string','InvNumber' => 'string','InvDate' => 'string','InvValue' => '0.00','PlaceOfSupply' => 'string',
    		'RevCharge' => 'string','InvoiceType' => 'string','E-comGSTIN' => 'string','GSTRate' => '0.00','TaxValue' => '0.00','TaxableValue' => '0.00','CessAmount' => '0.00');
    	    
    	    $headers = array($selected_company_details->company_name,'Subject','Content');
    	    
    	    $srNo = 001;
            $InvSum = 0;
            $TaxableSum = 0;
            $rows = array();
            
            foreach ($B2B_data2['HistoryData'] as $key => $value) {
                if($value["BillAmt"] == "0.00" || $value["BillAmt"] == NULL || $value["BillAmt"] == ''){
                    
                }else{
                    $GSTPER = 0.00;
                    $GSTPER = $value['igst'] + $value['cgst'] + $value['sgst'];
                    if(number_format($GSTPER,2) !== '0.00'){
                        foreach ($B2B_data2['SaleData'] as $key2 => $value2) {
                            if($value2['SalesID'] == $value["TransID"]){
                                $invAmt = 0;
                                $invAmt = $value2["INVAMT"];
                                $invAmt = round($invAmt);
                                $InvSum = $InvSum + $value2["INVAMT"];
                                $Party = $value2["company"];
                                $GSTNo = $value2["gstno"];
                                $state = $value2["state"];
                            }
                        }
                        
                        $TaxableSum += $value["TaxableAmt"];
                        $TaxSum += $value["TaxAmt"];
                        
                        $row = array($srNo,$Party,$GSTNo,$value["TransID"], _d(substr($value["TransDate2"],0,10)), $invAmt, $state, 'N', 'Regular',
                        '',$GSTPER,$value["TaxAmt"],$value["TaxableAmt"],'');
                        $srNo++;
                        array_push($rows, $row);        
                    }
                }
            }
            $row = array('','','Total','', '', round($InvSum), '', '', '','','',$TaxSum,$TaxableSum,'');
            array_push($rows, $row);
            $writer->writeSheet($rows, 'B2B', $header);
            
            // B2CL Export
            
            $header2 = array('SrNo' => '0','InvNumber' => 'string','InvDate' => 'string','InvAmt' => '0.00','PlaceOfSupply' => 'string',
    		'Rate' => '0.00','TaxableValue' => '0.00','CessAmount' => 'string','E-comGSTIN' => 'string');
    	    
            $srNo2 = 001;
            $InvSum2 = 0;
            $TaxableSum2 = 0;
            $rows2 = array();
            
            foreach ($B2B_data2['SaleData2'] as $key3 => $value3) {
                if($value3["INVAMT"] == "0.00" || $value3["INVAMT"] == NULL || $value3["INVAMT"] == ''){
                    
                }else{
                    foreach ($B2B_data2['HistoryData2'] as $key4 => $value4) {
                        $invAmt2 = 0;
                        if($value3['SalesID'] == $value4['TransID']){
                            $GSTPER2 = 0.00;
                            $GSTPER2 = $value4['igst'] + $value4['cgst'] + $value4['sgst'];
                            if(number_format($GSTPER2,2) !== '0.00'){
                                $invAmt2 = $value3["INVAMT"] + $value3["tcsAmt"];
                                $invAmt2 = round($invAmt2);
                                $InvSum2 = $InvSum2 + $value3["INVAMT"] + $value3["tcsAmt"];
                                $TaxableSum2 += $value4["TaxableAmt"];
                                
                                
                                $row2 = array($srNo2,$value4["TransID"],_d(substr($value3["BillDate"],0,10)),$invAmt2,$value3["state"],
                                            $GSTPER2,$value4["TaxableAmt"],'','');
                                array_push($rows2, $row2);
                                $srNo2++;
                            }
                        }
                    }
                }
            }
            
            $row2 = array('','Total','',round($InvSum2),'','',$TaxableSum2,'','');
            array_push($rows2, $row2);
            $writer->writeSheet($rows2, 'B2CL', $header2);
            
            // B2CS
            $header3 = array('SrNo' => '0','InvType' => 'string','PlaceOfSupply' => 'string',
    		'Rate' => '0.00','TaxableValue' => '0.00','CessAmount' => 'string','E-comGSTIN' => 'string');
    		$rows3 = array();
            $TaxableSum3 = 0;
            $srNo3 = 1;
            foreach ($B2B_data2['B2CS1'] as $key5 => $value5) {
                    $GST = 0.00;
                    $GST = $value5['sgst'] + $value5['cgst']+$value5['igst'];
                    if(number_format($GST,2) !== '0.00'){
                        $TaxableSum3 += $value5['TaxableAmt'];
                        $row3 = array($srNo3,'OE',$value5['state'],$GST,$value5['TaxableAmt'],'','');
                        array_push($rows3, $row3);
                        $srNo3++;
                    }
            }
        
            foreach ($B2B_data2['B2CS2'] as $key6 => $value6) {
                $GST = 0.00;
                $GST = $value6['sgst'] + $value6['cgst']+$value6['igst'];
                if(number_format($GST,2) !== "0.00"){
                    $GST_new =  number_format($GST,2);
                    $TaxableSum3 += $value6['TaxableAmt'];
               
                    $row3 = array($srNo3,'OE',$value6['state'],$GST_new,$value6['TaxableAmt'],'','');
                    array_push($rows3, $row3);
                    $srNo3++;
                }
            }
            $row3 = array('','Total','','',$TaxableSum3,'','');
            array_push($rows3, $row3);
            $writer->writeSheet($rows3, 'B2CS', $header3);
            
            // CDNR Export
            
            $header4 = array('SrNo' => '0','ReceiverName'=>'string','GSTINUINofRecipient' => 'string','InvoiceAdvanceReceiptNumber' => 'string',
    		'InvoiceAdvanceReceiptDate' => 'string','NoteRefundVoucherNumber' => 'string','NoteRefundVoucherDate' => 'string',
    		'DocumentType' => 'string','ReasonForIssuingdocument' => 'string','PlaceOfSupply'=>'string','NoteRefundVoucherValue'=>'0.00',
    		'Rate'=>'0.00','TaxableValue'=>'0.00','CessAmt'=>'0.00','PreGst'=>'string');
            
            $rows4 = array();
            $srNo4 = 001;
            $TaxableSum4 = 0.00;
            $InvoiveSum4 = 0.00;
            
            foreach ($B2B_data2['CDNR11'] as $key77 => $value77) {
                foreach ($B2B_data2['CDNR1'] as $key7 => $value7) {
                    $GST = 0.00;
                    if($value77['SalesID']==$value7['TransID']){
                        if($value7["BillAmt"] == "0.00" || $value7["BillAmt"] == NULL || $value7["BillAmt"] == ''){
                        
                        }else{
                            $GST = $value7["cgst"] + $value7["sgst"] + $value7["igst"];
                            if(number_format($GST,2) !== "0.00"){
                                $InvoiveSum4 += round($value7["BillAmt"]);
                                $TaxableSum4 += $value7["TaxableAmt"];
                                $row4 = array($srNo4,$value77["company"],$value77["gstno"],$value7["TransID"],_d(substr($value77["SaleDate"],0,10)),$value7["OrderID"],
                        _d(substr($value7["SaleRTNDate"],0,10)),'C','01 SalesReturn',$value77["state"],round($value7["BillAmt"]),
                                $GST,$value7["TaxableAmt"],'0.00','N');
                                array_push($rows4, $row4);
                                $srNo4++;
                            }
                        }   
                    }
                } 
            }
            
            foreach ($B2B_data2['CDNR111'] as $key777 => $value777) {
                foreach ($B2B_data2['CDNR2'] as $key7 => $value7) {
                    if($value777['PurchID']==$value7['TransID']){
                        $GST = 0.00;
                        if($value7["BillAmt"] == "0.00" || $value7["BillAmt"] == NULL || $value7["BillAmt"] == ''){
                        
                        }else{
                            $GST = $value7["cgst"] + $value7["sgst"] + $value7["igst"];
                            if(number_format($GST,2) !== "0.00"){
                                if($value7["ttype"] == "C"){
                                    $InvoiveSum4 += round($value7["BillAmt"]);
                                    $TaxableSum4 += $value7["TaxableAmt"];
                                }else{
                                    $InvoiveSum4 = $InvoiveSum4 - round($value7["BillAmt"]);
                                    $TaxableSum4 = $TaxableSum4 - $value7["TaxableAmt"];
                                }
                                
                                $GST = $value7["cgst"] + $value7["sgst"] + $value7["igst"];
                                $row4 = array($srNo4,$value777["company"],$value777["vat"],$value777["PurchID"],_d(substr($value777["SaleDate"],0,10)),$value7["billno"],
                        _d(substr($value7["CDDate"],0,10)),'C','01 SalesReturn',$value777["state"],round($value7["BillAmt"]),
                                $GST,$value7["TaxableAmt"],'0.00','N');
                                array_push($rows4, $row4);
                                $srNo4++;
                            }
                        }   
                    }
                } 
            }
            
            foreach ($B2B_data2['CDNR22'] as $key88 => $value88) {
                foreach ($B2B_data2['CDNR2'] as $key8 => $value8) {
                    if($value88['SalesID']==$value8['TransID']){
                        $GST = 0.00;
                        if($value8["BillAmt"] == "0.00" || $value8["BillAmt"] == NULL || $value8["BillAmt"] == ''){
                        
                        }else{
                            $GST = $value8["cgst"] + $value8["sgst"] + $value8["igst"];
                            if(number_format($GST,2) !== "0.00"){
                                if($value8["ttype"] == "C"){
                                    $InvoiveSum4 += round($value8["BillAmt"]);
                                    $TaxableSum4 += $value8["TaxableAmt"];
                                }else{
                                    $InvoiveSum4 = $InvoiveSum4 - round($value8["BillAmt"]);
                                    $TaxableSum4 = $TaxableSum4 - $value8["TaxableAmt"];
                                }
                                
                                $GST = $value8["cgst"] + $value8["sgst"] + $value8["igst"];
                                $row4 = array($srNo4,$value88["company"],$value88["gstno"],$value88["SalesID"],_d(substr($value88["SaleDate"],0,10)),$value8["billno"],
                        _d(substr($value8["CDDate"],0,10)),'C','01 SalesReturn',$value88["state"],round($value8["BillAmt"]),
                                $GST,$value8["TaxableAmt"],'0.00','N');
                                array_push($rows4, $row4);
                                $srNo4++;
                            }
                        }   
                    }
                } 
            }
            
            $row4 = array('','Total','','','','','','','','',$InvoiveSum4,'',$TaxableSum4,'','');
            array_push($rows4, $row4);
            $writer->writeSheet($rows4, 'CDNR', $header4);
            
            // CDNUR Export
            $header5 = array('SrNo' => '0','ReceiverName'=>'string','GSTINUINofRecipient' => 'string','InvoiceAdvanceReceiptNumber' => 'string',
    		'InvoiceAdvanceReceiptDate' => 'string','NoteRefundVoucherNumber' => 'string','NoteRefundVoucherDate' => 'string',
    		'DocumentType' => 'string','ReasonForIssuingdocument' => 'string','PlaceOfSupply'=>'string','NoteRefundVoucherValue'=>'0.00',
    		'Rate'=>'0.00','TaxableValue'=>'0.00','CessAmt'=>'0.00','PreGst'=>'string');
            
            $rows5 = array();
            $srNo5 = 001;
            $TaxableSum5 = 0.00;
            $InvoiveSum5 = 0.00;
            
            foreach ($B2B_data2['CDNUR11'] as $key99 => $value99) {
                foreach ($B2B_data2['CDNUR1'] as $key9 => $value9) {
                    if($value99['SalesID']==$value9['TransID']){
                        $GST = 0.00;
                        if($value9["BillAmt"] == "0.00" || $value9["BillAmt"] == NULL || $value9["BillAmt"] == ''){
                        
                        }else{
                            $GST = $value9["cgst"] + $value9["sgst"] + $value9["igst"];
                            if(number_format($GST,2) !== "0.00"){
                                $InvoiveSum5 += round($value9["BillAmt"]);
                                $TaxableSum5 += $value9["TaxableAmt"];
                                
                                $row5 = array($srNo5,$value99["company"],$value99["gstno"],$value9["TransID"],_d(substr($value99["SaleDate"],0,10)),$value9["OrderID"],
                        _d(substr($value9["SaleRTNDate"],0,10)),'C','01 SalesReturn',$value99["state"],round($value9["BillAmt"]),
                                $GST,$value9["TaxableAmt"],'0.00','N');
                                array_push($rows5, $row5);
                                $srNo5++;
                            }
                        }   
                    }
                } 
            }
            
        foreach ($B2B_data2['CDNUR11TRN'] as $key99 => $value99) {
            foreach ($B2B_data2['CDNUR1'] as $key9 => $value9) {
                if($value99['SalesID']==$value9['TransID']){
                    $GST = 0.00;
                    if($value9["BillAmt"] == "0.00" || $value9["BillAmt"] == NULL || $value9["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value9["cgst"] + $value9["sgst"] + $value9["igst"];
                        if(number_format($GST,2) !== "0.00"){
                            
                            $InvoiveSum5 += round($value9["BillAmt"]);
                            $TaxableSum5 += $value9["TaxableAmt"];
                            
                            $row5 = array($srNo5,$value99["company"],$value99["gstno"],$value9["TransID"],_d(substr($value99["SaleDate"],0,10)),$value9["OrderID"],
                        _d(substr($value9["SaleRTNDate"],0,10)),'C','01 SalesReturn',$value99["state"],round($value9["BillAmt"]),
                                $GST,$value9["TaxableAmt"],'0.00','N');
                                array_push($rows5, $row5);
                                $srNo5++;
                        }
                    }   
                }
            } 
        }
            
            foreach ($B2B_data2['CDNUR22TRN'] as $key1010 => $value1010) {
            foreach ($B2B_data2['CDNUR2'] as $key10 => $value10) {
                if($value1010['SalesID']==$value10['TransID']){
                    $GST = 0.00;
                    if($value10["BillAmt"] == "0.00" || $value10["BillAmt"] == NULL || $value10["BillAmt"] == ''){
                    
                    }else{
                        $GST = $value10["cgst"] + $value10["sgst"] + $value10["igst"];
                        if(number_format($GST,2) !=="0.00"){
                            
                            //$InvoiveSum5 += round($value10["BillAmt"]);
                            if($value10["ttype"] == "C"){
                                $InvoiveSum5 += round($value10["BillAmt"]);
                                $TaxableSum5 += $value10["TaxableAmt"];
                            }else{
                                $InvoiveSum5 = $InvoiveSum5 - round($value10["BillAmt"]);
                                $TaxableSum5 = $TaxableSum5 - $value10["TaxableAmt"];
                            }
                            
                            $row5 = array($srNo5,$value1010["company"],$value1010["gstno"],$value1010["SalesID"],_d(substr($value1010["SaleDate"],0,10)),$value10["billno"],
                        _d(substr($value10["CDDate"],0,10)),'C','01 SalesReturn',$value1010["state"],round($value10["BillAmt"]),
                                $GST,$value10["TaxableAmt"],'0.00','N');
                                array_push($rows5, $row5);
                                $srNo5++;
                        }
                    }   
                }
            }
        }
          
          
            foreach ($B2B_data2['CDNUR22'] as $key1010 => $value1010) {
                foreach ($B2B_data2['CDNUR2'] as $key10 => $value10) {
                    if($value1010['SalesID']==$value10['TransID']){
                        $GST = 0.00;
                        if($value10["BillAmt"] == "0.00" || $value10["BillAmt"] == NULL || $value10["BillAmt"] == ''){
                        
                        }else{
                            $GST = $value10["cgst"] + $value10["sgst"] + $value10["igst"];
                            if(number_format($GST,2) !=="0.00"){
                                if($value10["ttype"] == "C"){
                                    $InvoiveSum5 += round($value10["BillAmt"]);
                                    $TaxableSum5 += $value10["TaxableAmt"];
                                }else{
                                    $InvoiveSum5 = $InvoiveSum5 - round($value10["BillAmt"]);
                                    $TaxableSum5 = $TaxableSum5 - $value10["TaxableAmt"];
                                }
                                
                                $row5 = array($srNo5,$value1010["company"],$value1010["gstno"],$value1010["SalesID"],_d(substr($value1010["SaleDate"],0,10)),$value10["billno"],
                        _d(substr($value10["CDDate"],0,10)),'C','01 SalesReturn',$value1010["state"],round($value10["BillAmt"]),
                                $GST,$value10["TaxableAmt"],'0.00','N');
                                array_push($rows5, $row5);
                                $srNo5++;
                            }
                        }   
                    }
                } 
            }
            
            $row5 = array('','Total','','','','','','','','',$InvoiveSum5,'',$TaxableSum5,'','');
            array_push($rows5, $row5);
            $writer->writeSheet($rows5, 'CDNUR', $header5);
            
            //EXEMP Export
            
            $header6 = array('SrNo' => '0','Description' => 'string','NilRatedSupplies' => 'string',
    		'Exempted' => '0.00','NonGSTSupplies' => 'string');
    		$rows6 = array();
            $srNo6 = 001;
            $total = 0.00;
       
                $total += $EXEMP_data["InterGSTR"];
                $row6 = array($srNo6,'Inter-State Supplies to registered persons','',$EXEMP_data["InterGSTR"],'');
                array_push($rows6, $row6);
                $srNo6++;
                
                $row6 = array($srNo6,'Intra-State Supplies to registered persons','',$EXEMP_data["IntraGSTR"],'');
                array_push($rows6, $row6);
                
                $total += $EXEMP_data["IntraGSTR"];
                $srNo6++;
                
                $row6 = array($srNo6,'Inter-State Supplies to Unregistered persons','',$EXEMP_data["InterGSTUR"],'');
                array_push($rows6, $row6);
                
                $total += $EXEMP_data["InterGSTUR"];
                $srNo6++;
                
                
                $row6 = array($srNo6,'Intra-State Supplies to Unregistered persons','',$EXEMP_data["IntraGSTUR"],'');
                array_push($rows6, $row6);
                
                $total += $EXEMP_data["IntraGSTUR"];
                $srNo6++;
                
                $row6 = array('','Total','',$total,'');
                array_push($rows6, $row6);
                $writer->writeSheet($rows6, 'EXEMP', $header6);
            
        // HSN Export
        
            $srNo7 = 001;
            $BillQtyTotal = 0.00;
            $billAmtTotal = 0.00;
            $taxAmtTotal = 0.00;
            $ISUMTotal = 0.00;
            $CSUMTotal = 0.00;
            $SSUMTotal = 0.00;
            $rows7 = array();
            $header7 = array('SrNo' => '0','HSN' => 'string','Description' => 'string','UQC'=>'string',
    		'TotalQty' => '0.00','TotalValue' => '0.00','TaxableValue'=>'0.00','IntegratedTax'=>'0.00','CentralTax'=>'0.00',
    		'State/UTTax'=>'0.00','CessAmount'=>'0.00','GST%'=>'0.00');
            
            $HSNList = array();
            $HSNTaxrate = array();
            foreach ($HSN_data as $hsnkey => $hsnvalue) {
                if($hsnvalue["hsn_code"] !== ''){
                    array_push($HSNList,$hsnvalue["hsn_code"]);
                    $tax = $hsnvalue["igst"] + $hsnvalue["sgst"] + $hsnvalue["cgst"];
                    array_push($HSNTaxrate,$tax);
                }
            }
            
            foreach ($CD_HSN as $hsnkey1 => $hsnvalue1) {
            if($hsnvalue1["hsncode"] !== ''){
                    array_push($HSNList,$hsnvalue1["hsncode"]);
                    $tax = $hsnvalue1["igst"] + $hsnvalue1["sgst"] + $hsnvalue1["cgst"];
                    array_push($HSNTaxrate,$tax);
                }
            }
            
            foreach ($SRT_HSN as $hsnkey2 => $hsnvalue2) {
                if($hsnvalue2["hsn_code"] !== ''){
                    array_push($HSNList,$hsnvalue2["hsn_code"]);
                    $tax = $hsnvalue2["igst"] + $hsnvalue2["sgst"] + $hsnvalue2["cgst"];
                    array_push($HSNTaxrate,$tax);
                }
            }
        
            $HSNList = array_unique($HSNList);
            $HSNTaxrate = array_unique($HSNTaxrate);
            
            foreach ($HSNList as $hsnCode) {
                $hsnDesc = "";
                foreach ($HSNMaster as $master) {
                    if($hsnCode == $master["name"]){
                        $hsnDesc = $master["hsndesc"];
                    }
                }
            foreach ($HSNTaxrate as $hsnTax) {
               
                $match = 0;
                $BillQty = 0.00;
                $billAmt = 0.00;
                $taxAmt = 0.00;
                $ISUM = 0.00;
                $CSUM = 0.00;
                $SSUM = 0.00;
                foreach ($HSN_data as $key7 => $value7) {
                    $gstPer = $value7["igst"] + $value7["sgst"] + $value7["cgst"];
                    if($value7['hsn_code'] == $hsnCode && $hsnTax == $gstPer){
                        $BillQty += $value7["BilledQtySum"];
                        $billAmt += $value7["BillAmt"];
                        $taxAmt += $value7["TaxableAmt"];
                        $ISUM += $value7["IGSTSUM"];
                        $CSUM += $value7["CGSTSUM"];
                        $SSUM += $value7["SGSTSUM"];
                        $match = 1;
                    }
                }
            // Minus SRT values    
                foreach ($HSN_dataSRT as $keySRT => $valueSRT) {
                    $gstPer2 = $valueSRT["igst"] + $valueSRT["sgst"] + $valueSRT["cgst"];
                    if($valueSRT['hsn_code'] == $hsnCode && $hsnTax == $gstPer2){
                        $BillQty -= $valueSRT["BilledQtySum"];
                        $billAmt -= $valueSRT["BillAmt"];
                        $taxAmt -= $valueSRT["TaxableAmt"];
                        $ISUM -= $valueSRT["IGSTSUM"];
                        $CSUM -= $valueSRT["CGSTSUM"];
                        $SSUM -= $valueSRT["SGSTSUM"];
                        $match = 1;
                    }
                }
            // Minus CD values    
                foreach ($HSN_dataCD as $keyCD => $valueCD) {
                    $gstPer3 = $valueCD["igst"] + $valueCD["sgst"] + $valueCD["cgst"];
                    if($valueCD['hsncode'] == $hsnCode && $hsnTax == $gstPer3 && $valueCD["SalesID"] != NULL){
                        $BillQty -= $valueCD["BilledQtySum"];
                        $billAmt -= $valueCD["BillAmt"];
                        $taxAmt -= $valueCD["TaxableAmt"];
                        $ISUM -= $valueCD["IGSTSUM"];
                        $CSUM -= $valueCD["CGSTSUM"];
                        $SSUM -= $valueCD["SGSTSUM"];
                        $match = 1;
                    }
                }
                
                // ADD Debit value values    
                foreach ($HSN_dataDD as $keyDD => $valueDD) {
                    $gstPer3 = $valueDD["igst"] + $valueDD["sgst"] + $valueDD["cgst"];
                    if($valueDD['hsncode'] == $hsnCode && $hsnTax == $gstPer3 && $valueDD["SalesID"] != NULL){
                            $BillQty += $valueDD["BilledQtySum"];
                            $billAmt += $valueDD["BillAmt"];
                            $taxAmt += $valueDD["TaxableAmt"];
                            $ISUM += $valueDD["IGSTSUM"];
                            $CSUM += $valueDD["CGSTSUM"];
                            $SSUM += $valueDD["SGSTSUM"];
                            $match = 1;
                    }
                }
                if($match == "1"){
                    
                    $row7 = array($srNo7,$hsnCode,$hsnDesc,'PCS-PIECES',$BillQty,$billAmt,$taxAmt,$ISUM,$CSUM,$SSUM,'',$hsnTax);
                    array_push($rows7, $row7);
                    
                   $BillQtyTotal += $BillQty;
                   $billAmtTotal += $billAmt;
                   $taxAmtTotal += $taxAmt;
                   $ISUMTotal += $ISUM;
                   $CSUMTotal += $CSUM;
                   $SSUMTotal += $SSUM;
                   $srNo7++;
                }
            }
        }
         
            $row7 = array('','Total','','',$BillQtyTotal,$billAmtTotal,$taxAmtTotal,$ISUMTotal,$CSUMTotal,$SSUMTotal,'','');
            array_push($rows7, $row7);
            $writer->writeSheet($rows7, 'HSN', $header7);   
            
        // Docs Export
            
            $header8 = array('SrNo' => '0','NatureofDocument' => 'string','SrNoFrom' => 'string','SrNoTo'=>'string',
    		'TotalNumber' => '0.00','Cancelled' => '0.00');
    		$rows8 = array();
            $srNo8 = 1;
            $html8 = '';
            
            $row8 = array($srNo8,'Invoice for Outward Supply',$DOCS_data["TStart"],$DOCS_data["TEnd"],$DOCS_data["TTotal"],$DOCS_data["TTotalC"]);
            array_push($rows8, $row8);
            $srNo8++;
            
            $row8 = array($srNo8,'Invoice for Outward Supply',$DOCS_data["BStart"],$DOCS_data["BEnd"],$DOCS_data["BTotal"],$DOCS_data["BTotalC"]);
            array_push($rows8, $row8);
            $srNo8++;
            
            $row8 = array($srNo8,'Invoice for Outward Supply',$DOCS_data["MStart"],$DOCS_data["MEnd"],$DOCS_data["MTotal"],$DOCS_data["MTotalC"]);
            array_push($rows8, $row8);
            $srNo8++;
            
            $writer->writeSheet($rows8, 'DOCS', $header8);
            
    	}
    	
    	$filename = 'GSTR1.xlsx';
    	$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    	echo json_encode([
    		'site_url'          => site_url(),
    		'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    	]);
    	die;
    	
        
        
        // EXEMP Report
        $html6 = '';
        $srNo6 = 001;
        $total = 0.00;
        //foreach ($EXEMP_data as $key6 => $value6) {
            
                $html6 .= '<tr>';
                $html6 .= '<td align="center">'.$srNo6.'</td>';
                $html6 .= '<td>Inter-State Supplies to registered persons</td>';
                $html6 .= '<td></td>';
                $html6 .= '<td align="right">'.number_format($EXEMP_data["InterGSTR"],2).'</td>';
                $total += $EXEMP_data["InterGSTR"];
                $html6 .= '<td></td>';
                $html6 .= '</tr>';
                $srNo6++;
                
                $html6 .= '<tr>';
                $html6 .= '<td align="center">'.$srNo6.'</td>';
                $html6 .= '<td>Intra-State Supplies to registered persons</td>';
                $html6 .= '<td></td>';
                $html6 .= '<td align="right">'.number_format($EXEMP_data["IntraGSTR"],2).'</td>';
                $total += $EXEMP_data["IntraGSTR"];
                $html6 .= '<td></td>';
                $html6 .= '</tr>';
                $srNo6++;
                
                $html6 .= '<tr>';
                $html6 .= '<td align="center">'.$srNo6.'</td>';
                $html6 .= '<td>Inter-State Supplies to Unregistered persons</td>';
                $html6 .= '<td></td>';
                $html6 .= '<td align="right">'.number_format($EXEMP_data["InterGSTUR"],2).'</td>';
                $total += $EXEMP_data["InterGSTUR"];
                $html6 .= '<td></td>';
                $html6 .= '</tr>';
                $srNo6++;
                
                $html6 .= '<tr>';
                $html6 .= '<td align="center">'.$srNo6.'</td>';
                $html6 .= '<td>Intra-State Supplies to Unregistered persons</td>';
                $html6 .= '<td></td>';
                $html6 .= '<td align="right">'.number_format($EXEMP_data["IntraGSTUR"],2).'</td>';
                $total += $EXEMP_data["IntraGSTUR"];
                $html6 .= '<td></td>';
                $html6 .= '</tr>';
                $srNo6++;
        //}
        
        $html6 .= '<tr>';
        $html6 .= '<td></td>';
        $html6 .= '<td>Total</td>';
        $html6 .= '<td></td>';
        $html6 .= '<td align="right">'.number_format($total,2).'</td>';
        $html6 .= '<td></td>';
        $html6 .= '</tr>';
       
        // HSN Report
        $html7 = '';
        $srNo7 = 001;
        $BillQtyTotal = 0.00;
        $billAmtTotal = 0.00;
        $taxAmtTotal = 0.00;
        $ISUMTotal = 0.00;
        $CSUMTotal = 0.00;
        $SSUMTotal = 0.00;
        
        $HSNList = array();
        $HSNTaxrate = array();
        foreach ($HSN_data as $hsnkey => $hsnvalue) {
            if($hsnvalue["hsn_code"] !== ''){
                array_push($HSNList,$hsnvalue["hsn_code"]);
                $tax = $hsnvalue["igst"] + $hsnvalue["sgst"] + $hsnvalue["cgst"];
                array_push($HSNTaxrate,$tax);
            }
        }
        
        $HSNList = array_unique($HSNList);
        $HSNTaxrate = array_unique($HSNTaxrate);
        
        foreach ($HSNList as $hsnCode) {
            foreach ($HSNTaxrate as $hsnTax) {
                $match = 0;
                $BillQty = 0.00;
                $billAmt = 0.00;
                $taxAmt = 0.00;
                $ISUM = 0.00;
                $CSUM = 0.00;
                $SSUM = 0.00;
                foreach ($HSN_data as $key7 => $value7) {
                    $gstPer = $value7["igst"] + $value7["sgst"] + $value7["cgst"];
                    if($value7['hsn_code'] == $hsnCode && $hsnTax == $gstPer){
                        $BillQty += $value7["BilledQtySum"];
                        $billAmt += $value7["BillAmt"];
                        $taxAmt += $value7["TaxableAmt"];
                        $ISUM += $value7["IGSTSUM"];
                        $CSUM += $value7["CGSTSUM"];
                        $SSUM += $value7["SGSTSUM"];
                        $match = 1;
                    }
                }
            // Minus SRT values    
                foreach ($HSN_dataSRT as $keySRT => $valueSRT) {
                    $gstPer2 = $valueSRT["igst"] + $valueSRT["sgst"] + $valueSRT["cgst"];
                    if($valueSRT['hsn_code'] == $hsnCode && $hsnTax == $gstPer2){
                        $BillQty -= $valueSRT["BilledQtySum"];
                        $billAmt -= $valueSRT["BillAmt"];
                        $taxAmt -= $valueSRT["TaxableAmt"];
                        $ISUM -= $valueSRT["IGSTSUM"];
                        $CSUM -= $valueSRT["CGSTSUM"];
                        $SSUM -= $valueSRT["SGSTSUM"];
                        $match = 1;
                    }
                }
            // Minus CD values    
                foreach ($HSN_dataCD as $keyCD => $valueCD) {
                    $gstPer3 = $valueCD["igst"] + $valueCD["sgst"] + $valueCD["cgst"];
                    if($valueCD['hsncode'] == $hsnCode && $hsnTax == $gstPer3 && $valueCD["SalesID"] != NULL){
                        $BillQty -= $valueCD["BilledQtySum"];
                        $billAmt -= $valueCD["BillAmt"];
                        $taxAmt -= $valueCD["TaxableAmt"];
                        $ISUM -= $valueCD["IGSTSUM"];
                        $CSUM -= $valueCD["CGSTSUM"];
                        $SSUM -= $valueCD["SGSTSUM"];
                        $match = 1;
                    }
                }
                if($match == "1"){
                    $html7 .= '<tr>'; 
                   $html7 .= '<td align="center">'.$srNo7.'</td>'; 
                   $html7 .= '<td align="center">'.$hsnCode.'</td>'; 
                   $html7 .= '<td align="center">'.$hsnCode.'</td>'; 
                   $html7 .= '<td align="center">PCS-PIECES</td>'; 
                   $html7 .= '<td align="right">'.number_format($BillQty,2).'</td>'; 
                   $BillQtyTotal += $BillQty;
                   $html7 .= '<td align="right">'.number_format($billAmt,2).'</td>'; 
                   $billAmtTotal += $billAmt;
                   $html7 .= '<td align="right">'.number_format($taxAmt,2).'</td>'; 
                   $taxAmtTotal += $taxAmt;
                   $html7 .= '<td align="right">'.number_format($ISUM,2).'</td>'; 
                   $ISUMTotal += $ISUM;
                   $html7 .= '<td align="right">'.number_format($CSUM,2).'</td>'; 
                   $CSUMTotal += $CSUM;
                   $html7 .= '<td align="right">'.number_format($SSUM,2).'</td>'; 
                   $SSUMTotal += $SSUM;
                   $html7 .= '<td></td>'; 
                   $html7 .= '<td align="center">'.number_format($hsnTax,2).'</td>'; 
                   $html7 .= '<tr>'; 
                   $srNo7++;
                }
            }
        }
        
        $html7 .= '<tr>';
        $html7 .= '<td></td>';
        $html7 .= '<td>Total</td>';
        $html7 .= '<td></td>';
        $html7 .= '<td></td>';
        $html7 .= '<td align="right">'.number_format($BillQtyTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($billAmtTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($taxAmtTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($ISUMTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($CSUMTotal,2).'</td>';
        $html7 .= '<td align="right">'.number_format($SSUMTotal,2).'</td>';
        $html7 .= '<td></td>';
        $html7 .= '<td></td>';
        $html7 .= '</tr>';
        
        // Docs
        $srNo8 = 1;
        $html8 = '';
        
        $html8 .= '<tr>';
        $html8 .= '<td align="center">'.$srNo8.'</td>';
        $html8 .= '<td>Invoice for Outward Supply</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TStart"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TEnd"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TTotal"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["TTotalC"].'</td>';
        $html8 .= '</tr>';
        $srNo8++;
        
        $html8 .= '<tr>';
        $html8 .= '<td align="center">'.$srNo8.'</td>';
        $html8 .= '<td>Invoice for Outward Supply</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BStart"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BEnd"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BTotal"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["BTotalC"].'</td>';
        $html8 .= '</tr>';
        $srNo8++;
        
        $html8 .= '<tr>';
        $html8 .= '<td align="center">'.$srNo8.'</td>';
        $html8 .= '<td>Invoice for Outward Supply</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MStart"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MEnd"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MTotal"].'</td>';
        $html8 .= '<td align="center">'.$DOCS_data["MTotalC"].'</td>';
        $html8 .= '</tr>';
        $srNo8++;
        
        $response  = array();
        $response['B2B']= $html;
        $response['B2CL']= $html2;
        $response['B2CS']= $html3;
        $response['CDNR']= $html4;
        $response['CDNUR']= $html5;
        $response['EXEMP']= $html6;
        $response['HSN']= $html7;
        $response['DOCS']= $html8;
        echo json_encode($response);
    }
    
    /* Sale Report Page */
    public function gst_r_1()
    {
        if (!has_permission_new('GGSTR_1', '', 'view')) {
            access_denied('GGSTR_1');
        }
        $title = _l('GST-R 1');
        $data['title'] = $title;
         $this->load->model('misc_reports_model');
         $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/gst_r_1', $data);
    }
    
    
    
    /* Sale Report Page */
    public function GSTR3B()
    {
        if (!has_permission_new('GSTR_3B', '', 'view')) {
            access_denied('GSTR3B');
        }
        $title = _l('GSTR 3B');
        $data['title'] = $title;
         $this->load->model('misc_reports_model');
         $data['company_detail'] = $this->misc_reports_model->get_company_detail();
        $this->load->view('admin/e_filling/gst_r_3', $data);
    }
//=========================== Load GSTR 3B =====================================
    public function load_tableGSRT3B()
    {  
        $month_input = $this->input->post('month'); // Example: '2024-11'
		// $selected_year = date('Y', strtotime($month_input . "-01")); // Extract year
		// $selected_month = date('m', strtotime($month_input . "-01")); // Extract month
		$date = $month_input.'-01';//your given date
		$first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
		$first_date = date("Y-m-d",$first_date_find);
		
		$last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
		$last_date = date("Y-m-d",$last_date_find);
		
		$from_date = $first_date;
		$to_date = $last_date;
		
        $filterdata = array(
           'from_date' => $from_date,
           'to_date'  => $to_date
        );
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
            
        // 3.1(a) – Outward taxable supplies (other than zero rated, nil rated and exempted) = State and Central Sales in which you charge GST and it’s Tax amount.
        $gstr_3_1_a = $this->e_filling_model->get_data_for_gstr_3_1_a($filterdata);
        
        /*echo json_encode($gstr_3_1_a);
        die;*/
        
        // 3.1(b) – Outward taxable supplies (zero rated) = Supplies with Zero GST rate, i.e, exports or supplies made to SEZ.
        
        
        // 3.1(c) – Other outward supplies (Nil rated, exempted) = Supplies with Nill Rated and Exempt such as Milk and Salt.
        $gstr_3_1_c = $this->e_filling_model->get_data_for_gstr_3_1_c($filterdata);
        
        // 3.1(d) – Inward supplies (liable to reverse charge) = Purchases made from UnRegistered suppliers for which you need to create an invoice for yourself to pay the GST.
        $gstr_3_1_d = $this->e_filling_model->get_data_for_gstr_3_1_d($filterdata);
        
        // 3.1(e) – Non-GST outward supplies = Goods that are not covered in GST, eg., Alcohol, Petroleum products etc.
        //-$gstr_3_1_e = $this->e_filling_model->get_data_for_gstr_3_1_e($filterdata);
        
        // 3.2.a Supplies made to Unregistered Persons = Capture Interstate sales to Unregistered Persons.
        $gstr_3_2_a = $this->e_filling_model->get_data_for_gstr_3_2_a($filterdata);
        
       
        // 3.2.b Supplies made to Composition Taxable Persons = Interstate sales made to Composition Tax Payers.
        $gstr_3_2_b = $this->e_filling_model->get_data_for_gstr_3_2_b($filterdata);
         
        // 4.A.5 (5)All other ITC = Normal purchases from a Registered dealer.
        $gstr_4_A_5 = $this->e_filling_model->get_data_for_gstr_4_A_5($filterdata);
        
        // 5.1 From a supplier under composition scheme, Exempt and Nil rated supply = Inter-state and Intra-State purchase of goods 0%, Exempt etc.
        $gstr_5_1 = $this->e_filling_model->get_data_for_gstr_5_1($filterdata);

    // GSTR 3B
            
        $html .= '<table class="table-striped table-bordered production_report" id="gstr3B" width="100%">';
        $html .= '<thead style="font-size:11px;">';
        
        $html .= '<tr>';
        $html .= '<th class="sortable" align="center">level</th>';
        $html .= '<th class="sortable" align="center">Nature of Supplies</th>';
        $html .= '<th class="sortable" align="center">Taxable Value</th>';
        $html .= '<th class="sortable" align="center">IntgegratedTax</th>';
        $html .= '<th class="sortable" align="center">CentralTax</th>';
        $html .= '<th class="sortable" align="center">State/UTTax</th>';
        $html .= '<th class="sortable" align="center">Cess</th>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        // 3.1
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.1</td>';
        $html .= '<td>Detail of Outward supplies and Inward supplies liable to reverse charges</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        //3.1.a
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.1.a</td>';
        $html .= '<td>(a). Outward taxable supplies(other than zero rate, nil rated and exmpted)</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_a['TaxableAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_a['IAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_a['CAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_a['SAmt'],2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        //3.1.b
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.1.b</td>';
        $html .= '<td>(b). Outward taxable supplies(zero rated)</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        //3.1.c
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.1.c</td>';
        $html .= '<td>Other Outward supplies,(Nil rated, exmpted)</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_c['TaxableAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_c['IAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_c['CAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_c['SAmt'],2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        //3.1.d
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.1.d</td>';
        $html .= '<td>Inward supplies(liable to reverse charges)</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_d["TaxableAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_d["IAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_d["CAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_1_d["SAmt"],2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        //3.1.e
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.1.e</td>';
        $html .= '<td>Not GST Outward supplies</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //3.2
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.2</td>';
        $html .= '<td>3.2 of the supplies show in 3.1(a) above, details of the inter-State supplies made to unregistered persons.</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //3.2.a
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.2.a</td>';
        $html .= '<td>Supplies made to unregisterd persons</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_a['TaxableAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_a['IAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_a['CAmt'],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_a['SAmt'],2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //3.2.b
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.2.b</td>';
        $html .= '<td>Supplies made to Composition Taxable Persons</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_b->TaxableAmt,2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_b->IAmt,2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_b->CAmt,2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_3_2_b->SAmt,2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //3.2.c
        $html .= '<tr>';
        $html .= '<td>GSTR3B_3.2.c</td>';
        $html .= '<td>Supplies made to UIN Holders</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.A
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.A</td>';
        $html .= '<td>(A) ITC Available (whether in full or part)</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.A.1
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.A.1</td>';
        $html .= '<td>(1) Import of goods</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.A.2
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.A.2</td>';
        $html .= '<td>(2) Import of services</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.A.3
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.A.3</td>';
        $html .= '<td>(3) Inward supplies liable to reverse charge (other than 1 & 2 above)</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.A.4
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.A.4</td>';
        $html .= '<td>(4) Inward supplies from ISD</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.A.5
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.A.5</td>';
        $html .= '<td>(5) All other ITC</td>';
        $html .= '<td align="right">'.number_format($gstr_4_A_5["TaxableAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_4_A_5["IAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_4_A_5["CAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_4_A_5["SAmt"],2).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.B
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.B</td>';
        $html .= '<td>(B) ITC Reversed</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.B.1
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.B.1</td>';
        $html .= '<td>(1) As per rules 42 & 43 of CGST Rules</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
         //4.B.2
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.B.2</td>';
        $html .= '<td>(2) Others</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
          //4.C
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.C</td>';
        $html .= '<td>(C) Net ITC Available (A) – (B)</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
          //4.D
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.D</td>';
        $html .= '<td>(D) Ineligible ITC</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
          //4.D.1
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.D.1</td>';
        $html .= '<td>(1) As per section 17(5)</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
          //4.D.2
        $html .= '<tr>';
        $html .= '<td>GSTR3B_4.D.2</td>';
        $html .= '<td>(2) Others</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        // Empty row
        $html .= '<tr style="height:20px;">';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $html .= '<table class="table-striped table-bordered gstr3B_2" id="gstr3B_2" width="100%" style="margin-top:20px;">';
        $html .= '<thead style="font-size:11px;">';
        
        $html .= '<tr>';
        $html .= '<th class="sortable2" align="center">level</th>';
        $html .= '<th class="sortable2" align="center">Nature of Supplies</th>';
        $html .= '<th class="sortable2" align="center">Inter State Supplies</th>';
        $html .= '<th class="sortable2" align="center">Intra State Supplies</th>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        //gstr3B_5.1
        $html .= '<tr>';
        $html .= '<td>gstr3B_5.1</td>';
        $html .= '<td>Values of exempt, nil-rated and non-GST inward supplies</td>';
        $html .= '<td align="right">'.number_format($gstr_5_1["IterStateTaxableAmt"],2).'</td>';
        $html .= '<td align="right">'.number_format($gstr_5_1["IntraTaxableAmt"],2).'</td>';
        $html .= '</tr>';
        
        // gstr3B_5.2
        $html .= '<tr>';
        $html .= '<td>gstr3B_5.2</td>';
        $html .= '<td>Non GST supply</td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '</tbody>';
        $html .= '</table>';
        
          
        echo json_encode($html);
        die;
    }
    public function download_json()
    {
        if ($this->input->post()) {
            $month_input = $this->input->post('monthExport'); // Example: '2024-11'
    		// $selected_year = date('Y', strtotime($month_input . "-01")); // Extract year
    		// $selected_month = date('m', strtotime($month_input . "-01")); // Extract month
    		$date = $month_input.'-01';//your given date
    		$first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
    		$first_date = date("Y-m-d",$first_date_find);
    		
    		$last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
    		$last_date = date("Y-m-d",$last_date_find);
    		
    		$from_date = $first_date;
    		$to_date = $last_date;
    		
            $filterdata = array(
               'from_date' => $from_date,
               'to_date'  => $to_date,
               'month_input'  => $month_input
            );
            $result = $this->e_filling_model->download_json($filterdata);
            set_alert('success', _l('added_successfully'));
            redirect(admin_url('e_filling/GSTR3B'));
        }
    }
    public function export_GSTR3B_report()
    {  
       if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
        $month_input = $this->input->post('month'); // Example: '2024-11'
		// $selected_year = date('Y', strtotime($month_input . "-01")); // Extract year
		// $selected_month = date('m', strtotime($month_input . "-01")); // Extract month
		$date = $month_input.'-01';//your given date
		$first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
		$first_date = date("Y-m-d",$first_date_find);
		
		$last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
		$last_date = date("Y-m-d",$last_date_find);
		
		$from_date = $first_date;
		$to_date = $last_date;
		
        $filterdata = array(
           'from_date' => $from_date,
           'to_date'  => $to_date
        );
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
        $this->load->model('misc_reports_model');
    	$selected_company_details    = $this->misc_reports_model->get_company_detail();
    	
        // 3.1(a) – Outward taxable supplies (other than zero rated, nil rated and exempted) = State and Central Sales in which you charge GST and it’s Tax amount.
        $gstr_3_1_a = $this->e_filling_model->get_data_for_gstr_3_1_a($filterdata);
        
        // 3.1(b) – Outward taxable supplies (zero rated) = Supplies with Zero GST rate, i.e, exports or supplies made to SEZ.
        
        
        // 3.1(c) – Other outward supplies (Nil rated, exempted) = Supplies with Nill Rated and Exempt such as Milk and Salt.
        $gstr_3_1_c = $this->e_filling_model->get_data_for_gstr_3_1_c($filterdata);
        
        // 3.1(d) – Inward supplies (liable to reverse charge) = Purchases made from UnRegistered suppliers for which you need to create an invoice for yourself to pay the GST.
        $gstr_3_1_d = $this->e_filling_model->get_data_for_gstr_3_1_d($filterdata);
        
        // 3.1(e) – Non-GST outward supplies = Goods that are not covered in GST, eg., Alcohol, Petroleum products etc.
        //$gstr_3_1_e = $this->e_filling_model->get_data_for_gstr_3_1_e($filterdata);
        
        // 3.2.a Supplies made to Unregistered Persons = Capture Interstate sales to Unregistered Persons.
        $gstr_3_2_a = $this->e_filling_model->get_data_for_gstr_3_2_a($filterdata);
        
        // 3.2.b Supplies made to Composition Taxable Persons = Interstate sales made to Composition Tax Payers.
        $gstr_3_2_b = $this->e_filling_model->get_data_for_gstr_3_2_b($filterdata);
        
        // 4.A.5 (5)All other ITC = Normal purchases from a Registered dealer.
        $gstr_4_A_5 = $this->e_filling_model->get_data_for_gstr_4_A_5($filterdata);
        
        // 5.1 From a supplier under composition scheme, Exempt and Nil rated supply = Inter-state and Intra-State purchase of goods 0%, Exempt etc.
        $gstr_5_1 = $this->e_filling_model->get_data_for_gstr_5_1($filterdata);
        
        $writer = new XLSXWriter();
        
        $company_name = array($selected_company_details->company_name);
    	$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 6);  //merge cells
    	$writer->writeSheetRow('Sheet1', $company_name);
    
    	$address = $selected_company_details->address;
    	$company_addr = array($address,);
    	$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 6);  //merge cells
    	$writer->writeSheetRow('Sheet1', $company_addr);
    	
    	// Header
    	$set_col_tk = [];
    	$set_col_tk["level"] =  'level';
    	$set_col_tk["Nature of Supplies"] =  'Nature of Supplies';
    	$set_col_tk["Taxable Value"] =  'Taxable Value';
    	$set_col_tk["IntgegratedTax"] =  'IntgegratedTax';
    	$set_col_tk["CentralTax"] =  'CentralTax';
    	$set_col_tk["State/UTTax"] =  'State/UTTax';
    	$set_col_tk["Cess"] =  'Cess';
    	
    	$writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
    // GSTR3B_3.1
        $list_add = [];
        $list_add[] = 'GSTR3B_3.1';
        $list_add[] = 'Detail of Outward supplies and Inward supplies liable to reverse charges';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //3.1.a
        $list_add = [];
        $list_add[] = 'GSTR3B_3.1.a';
        $list_add[] = '(a). Outward taxable supplies(other than zero rate, nil rated and exmpted)';
        $list_add[] = $gstr_3_1_a['TaxableAmt'];
        $list_add[] = $gstr_3_1_a['IAmt'];
        $list_add[] = $gstr_3_1_a['CAmt'];
        $list_add[] = $gstr_3_1_a['SAmt'];
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //3.1.b
        $list_add = [];
        $list_add[] = 'GSTR3B_3.1.b';
        $list_add[] = '(b). Outward taxable supplies(zero rated)';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //3.1.c
        $list_add = [];
        $list_add[] = 'GSTR3B_3.1.c';
        $list_add[] = 'Other Outward supplies,(Nil rated, exmpted)';
        $list_add[] = $gstr_3_1_c['TaxableAmt'];
        $list_add[] = $gstr_3_1_c['IAmt'];
        $list_add[] = $gstr_3_1_c['CAmt'];
        $list_add[] = $gstr_3_1_c['SAmt'];
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //3.1.d
        $list_add = [];
        $list_add[] = 'GSTR3B_3.1.d';
        $list_add[] = 'Inward supplies(liable to reverse charges)';
        $list_add[] = $gstr_3_1_d["TaxableAmt"];
        $list_add[] = $gstr_3_1_d["IAmt"];
        $list_add[] = $gstr_3_1_d["CAmt"];
        $list_add[] = $gstr_3_1_d["SAmt"];
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //3.1.e
        $list_add = [];
        $list_add[] = 'GSTR3B_3.1.e';
        $list_add[] = 'Not GST Outward supplies';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //3.2
        $list_add = [];
        $list_add[] = 'GSTR3B_3.2';
        $list_add[] = '3.2 of the supplies show in 3.1(a) above, details of the inter-State supplies made to unregistered persons.';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //3.2.a
        $list_add = [];
        $list_add[] = 'GSTR3B_3.2.a';
        $list_add[] = 'Supplies made to unregisterd persons';
        $list_add[] = $gstr_3_2_a['TaxableAmt'];
        $list_add[] = $gstr_3_2_a['IAmt'];
        $list_add[] = $gstr_3_2_a['CAmt'];
        $list_add[] = $gstr_3_2_a['SAmt'];
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //3.2.b
        $list_add = [];
        $list_add[] = 'GSTR3B_3.2.b';
        $list_add[] = 'Supplies made to Composition Taxable Persons';
        $list_add[] = $gstr_3_2_b->TaxableAmt;
        $list_add[] = $gstr_3_2_b->IAmt;
        $list_add[] = $gstr_3_2_b->CAmt;
        $list_add[] = $gstr_3_2_b->SAmt;
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //3.2.c
        $list_add = [];
        $list_add[] = 'GSTR3B_3.2.c';
        $list_add[] = 'Supplies made to UIN Holders';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.A
        $list_add = [];
        $list_add[] = 'GSTR3B_4.A';
        $list_add[] = '(A) ITC Available (whether in full or part)';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.A.1
        $list_add = [];
        $list_add[] = 'GSTR3B_4.A.1';
        $list_add[] = '(1) Import of goods';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.A.2
        $list_add = [];
        $list_add[] = 'GSTR3B_4.A.2';
        $list_add[] = '(2) Import of services';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
        
    //4.A.3
        $list_add = [];
        $list_add[] = 'GSTR3B_4.A.3';
        $list_add[] = '(3) Inward supplies liable to reverse charge (other than 1 & 2 above)';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //4.A.4
        $list_add = [];
        $list_add[] = 'GSTR3B_4.A.4';
        $list_add[] = '(4) Inward supplies from ISD';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //4.A.5
        $list_add = [];
        $list_add[] = 'GSTR3B_4.A.5';
        $list_add[] = '(5) All other ITC';
        $list_add[] = $gstr_4_A_5["TaxableAmt"];
        $list_add[] = $gstr_4_A_5["IAmt"];
        $list_add[] = $gstr_4_A_5["CAmt"];
        $list_add[] = $gstr_4_A_5["SAmt"];
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.B
        $list_add = [];
        $list_add[] = 'GSTR3B_4.B';
        $list_add[] = '(B) ITC Reversed';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.B.1
        $list_add = [];
        $list_add[] = 'GSTR3B_4.B.1';
        $list_add[] = '(1) As per rules 42 & 43 of CGST Rules';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.B.2
        $list_add = [];
        $list_add[] = 'GSTR3B_4.B.2';
        $list_add[] = '(2) Others';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.C
        $list_add = [];
        $list_add[] = 'GSTR3B_4.C';
        $list_add[] = '(C) Net ITC Available (A) – (B)';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.D
        $list_add = [];
        $list_add[] = 'GSTR3B_4.D';
        $list_add[] = '(D) Ineligible ITC';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //4.D.1
        $list_add = [];
        $list_add[] = 'GSTR3B_4.D.1';
        $list_add[] = '(1) As per section 17(5)';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    //4.D.2
        $list_add = [];
        $list_add[] = 'GSTR3B_4.D.2';
        $list_add[] = '(2) Others';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '0';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //Empty Row
        $list_add = [];
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    //Empty Row
        $list_add = [];
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $writer->writeSheetRow('Sheet1', $list_add);
    
    // Second table
        $list_add = [];
        $list_add[] = 'level';
        $list_add[] = 'Nature of Supplies';
        $list_add[] = 'Inter State Supplies';
        $list_add[] = 'Intra State Supplies';
        $writer->writeSheetRow('Sheet1', $list_add);
        
    // gstr3B_5.1
        $list_add = [];
        $list_add[] = 'gstr3B_5.1';
        $list_add[] = 'Values of exempt, nil-rated and non-GST inward supplies';
        $list_add[] = $gstr_5_1["IterStateTaxableAmt"];
        $list_add[] = $gstr_5_1["IntraTaxableAmt"];
        $writer->writeSheetRow('Sheet1', $list_add);
        
    // gstr3B_5.2
        $list_add = [];
        $list_add[] = 'gstr3B_5.2';
        $list_add[] = 'Non GST supply';
        $list_add[] = '';
        $list_add[] = '';
        $writer->writeSheetRow('Sheet1', $list_add);
    
        $files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        	foreach($files as $file){
        		if(is_file($file)) {
        			unlink($file); 
        		}
        	}
        $filename = 'GSTR3B.xlsx';
        $writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        	echo json_encode([
        			'site_url'          => site_url(),
        			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        		]);
         die;
    }
}?>