<?php

defined('BASEPATH') or exit('No direct script access allowed');


$dimensions = $pdf->getPageDimensions();

$pdf->SetMargins(5, 7, 5, 0);
//$pdf->Ln(0);

$get_order_list = get_order_list($invoice->ChallanID);
$count = 0;
$count_order = count($get_order_list);
$GetLoggedInName = GetLoginFullName();
$html = '';
foreach ($get_order_list as $key => $order_detail) {
    
        $client_detail = get_client_detail($order_detail["AccountID"]);
        $client_details2 = get_client_detail($order_detail["AccountID2"]);
        
        $FY = $order_detail["FY"];
        $PlantDetail = GetPlantDetails($order_detail["PlantID"],$order_detail["FY"]);
        $gst_type = get_gst_type();
        $invoice_note = get_invoice_note();
        $sales_detail = get_sales_details($invoice->ChallanID,$order_detail["OrderID"]);
        // Current date and time
        $datetime = $sales_detail->Transdate;

        // Convert datetime to Unix timestamp
        $timestamp = strtotime($datetime);
        
        // Subtract time from datetime
        $time = $timestamp - 1;
        
        // Date and time after subtraction
        $datetime = date("Y-m-d H:i:s", $time);
        $AccountCR = GetCreditBal($sales_detail->AccountID,$datetime);
        $AccountDR = GetDebitBal($sales_detail->AccountID,$datetime);
        $AccountOpn = getOPNBal($sales_detail->AccountID);
        $OpnBal = $AccountOpn->BAL1;
        $netBal = $OpnBal + $AccountDR->AmtSum -  $AccountCR->AmtSum;
        
        $PlantID = $sales_detail->PlantID;
        $state_detail = get_state_detail($client_detail->state);
        $billing_state_detail = get_state_detail($client_detail->billing_state);
        $shipping_state_detail = get_state_detail($client_detail->shipping_state);
        
        $qty = 0;
        $amt = 0;
        $dis_amt = 0;
        $taxable_amt_item = 0;
        $order_total = 0;
        
        $title = "";
        if($order_detail["OrderType"] == "TaxItems"){
            $title = "TAX INVOICE";
        }
        if($order_detail["OrderType"] == "NonTaxItems"){
            $title = "BILL OF SUPPLY";
        }
        $pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 1));
        //$html .= '<div class="page-break-after: always;">';
        $html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="1" >';
       
        $html .= '<thead>';
        $html .= '<tr >
        <th colspan="4" style="border: 1px solid #333;"><p style="text-align:center;font-size:14px;"><b>'.$title.'</b><br><b>'.$PlantDetail->FIRMNAME.'</b><br><b>'.$PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2.'<br></b><b>GSTIN '.$PlantDetail->GSTNO.', <i>fssai</i> Lic.no '.$PlantDetail->FLNO1.' </b><br><b>Contact No. : '.$PlantDetail->PHONENO.'</b></p></th>
        </tr>';
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="20%">Invoice No.</th>
        <th style="border-right: 1px solid #333;" width="30%"><b>'.$sales_detail->SalesID.'</b></th>
        <th width="20%" style="border-left: 1px solid #333;">Ack No.</th>
        <th style="border-right: 1px solid #333;" width="30%"><b></b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="20%">Invoice Date</th>
        <th style="border-right: 1px solid #333;" width="30%"><b>'. _d(substr($sales_detail->Transdate,0,10)) .'</b></th>
        <th width="20%" style="border-left: 1px solid #333;">Ack Date</th>
        <th style="border-right: 1px solid #333;" width="30%"><b></b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="20%">Challan No</th>
        <th style="border-right: 1px solid #333;" width="30%"><b>'. $invoice->ChallanID .'</b></th>
        <th width="20%" style="border-left: 1px solid #333;">Vehicle No</th>
        <th style="border-right: 1px solid #333;" width="30%"><b>'.$invoice->VehicleID.'</b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-bottom: 1px solid #333;" width="20%">Order No</th>
        <th style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b>'. $order_detail["OrderID"] .'</b></th>
        <th width="20%" style="border-bottom: 1px solid #333;border-left: 1px solid #333;">eWayBillNo</th>
        <th style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b></b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>Bill To</b></th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>Ship To</b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail->company.'</b></th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>'.$client_details2->company.'</b></th>
        </tr>';
        
        if(is_null($client_detail->address3)){
            
        }else{
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address3.'</th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_details2->address3.'</th>
        </tr>';
        }
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address.'</th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_details2->address.'</th>
        </tr>';
        
        $city_name = get_city_by_id($client_detail->city);
        if(empty($city_name)){
            $new_city_name = $client_detail->city;
        }else {
            $new_city_name = $city_name->city_name;
        }
        
        $city_name2 = get_city_by_id($client_details2->city);
        if(empty($city_name2)){
            $new_city_name2 = $client_details2->city;
        }else {
            $new_city_name2 = $city_name2->city_name;
        }
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$new_city_name.' - '.$client_detail->zip.'</th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$new_city_name2.' - '.$client_details2->zip.'</th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail->vat.'</b></th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_details2->vat.'</b></th>
        </tr>';
        if($client_detail->phonenumber == ""){
            $Mobile = $client_detail->cmobile;
        }else{
            $Mobile = $client_detail->phonenumber;
        }
        if($client_details2->phonenumber == ""){
            $Mobile2 = $client_details2->cmobile;
        }else{
            $Mobile2 = $client_details2->phonenumber;
        }
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$Mobile.'</b></th>
        <th style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$Mobile2.'</b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail->FLNO1.'</b></th>
        <th style="border-right: 1px solid #333;border-bottom: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_details2->FLNO1.'</b></th>
        </tr>';
        $rowspan = 'rowspan="2"';
        $item_name_width = "28%";
        $hsn_width = "7%";
        $html .= '<tr>
        <th width="3.6%" '.$rowspan.' style="text-align:center;"><b>Sr. No.</b></th>
        <th width="'.$item_name_width.'" '.$rowspan.'><b>Name of Product</b></th>
        <th width="'.$hsn_width.'" '.$rowspan.'><b>HSN Code</b></th>
        <th width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Pack</b></th>
        <th width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Qty.</b></th>
        <th width="6.5%" '.$rowspan.' style="text-align:center;"><b>In Units</b></th>
        <th width="5%" '.$rowspan.' style="text-align:center;"><b>Rate</b></th>
        <th width="8%" '.$rowspan.' style="text-align:center;"><b>Amount</b></th>
        <th width="6%" '.$rowspan.' style="text-align:center;"><b>Disc Amount</b></th>
        <th width="8%" '.$rowspan.' style="text-align:center;"><b>Taxable Amount</b></th>';
    
    $html .= '<th style="text-align:center;" width="6%"><b>GST</b></th>';
    $html .= '<th style="text-align:center;" width="9%"><b>Total</b></th>';
    //$html .= '<td '.$rowspan.' style="text-align:center;">Total Amt</td>';    
    $html .= '</tr>';
    $html .= '<tr>
        
        <th style="text-align:center;"><b>%</b></th>
        <th style="text-align:center;"><b>Amount</b></th>
        </tr>';
        
        $html .= '</thead>';
        $html .= '<tbody>';
        
        
    $inv_item = get_item_by_order_id($order_detail["OrderID"]);
    $i = 1;
    $total_item_count = count($inv_item);
    
    if($total_item_count <= 13 ){
        $empty_height = 307;
    }
    
    if($total_item_count > 13 && $total_item_count<=33){
        $empty_height = 678;
        $empty_height1 = 318;
    }
    if($total_item_count > 33 ){
        $empty_height = 318;
    }
    
    $qty = 0;
    $units = 0;
    $amt = 0;
    $dis_amt = 0;
    $taxable_amt_item = 0;
    $order_total = 0;
    foreach ($inv_item as $item) {
        $hsn_code = get_hsn_byitem_id($item['ItemID']);
        if($total_item_count <= 13 ){
            $empty_height = $empty_height - 23;
        }
        if($total_item_count > 13 && $total_item_count<=33){
            $empty_height = $empty_height - 22;
        }
        if($total_item_count > 13 && $total_item_count<=33 && $i > 33){
            $empty_height1 = $empty_height1 - 22;
        }
        if($total_item_count > 33 && $i > 33){
            $empty_height = $empty_height - 22;
        }
        $html .= '<tr>'; 
           $html .= '<td width="3.6%" style="text-align:center;">'.$i.'</td>'; 
           $html .= '<td width="'.$item_name_width.'" class="description" align="left;" width="'.$item_name_width.'"><b>'.$item['description'].'</b></td>';
           $html .= '<td width="'.$hsn_width.'" style="text-align:center;"><b>'.$hsn_code->hsn_code.'</b></td>';
           $html .= '<td width="6%" style="text-align:right;"><b>'. (int) $item['CaseQty'].'</b></td>';
           
           $html .= '<td width="6%" style="text-align:right;"><b>'. (int) $item['caseqty'].'</b></td>';
           $html .= '<td width="6.5%" style="text-align:right;"><b>'. (int) $item['qty'].'</b></td>';
           $units = $units + $item['qty'];
            $qty = $qty + (int) $item['caseqty'];
           $html .= '<td width="5%" style="text-align:right;"><b>'.number_format($item['BasicRate'], 2, '.', '').'</b></td>';
           $html .= '<td width="8%" style="text-align:right;"><b>'.$item['ChallanAmt'].'</b></td>';
           $amt = $amt + $item['ChallanAmt'];
           $html .= '<td width="6%" style="text-align:right;"><b>'.round($item['DiscAmt'],2) .'</b></td>';
           $dis_amt = $dis_amt + $item['DiscAmt'];
           $html .= '<td width="8%" style="text-align:right;"><b>'.$item['ChallanAmt'].'</b></td>';
           
           $taxable_amt_item = $taxable_amt_item + $item['ChallanAmt'];
           
           if($client_detail->state == "UP"){
               $gst_rate = $item['cgst'] + $item['sgst'];
               $gst_rate = $gst_rate.".00";
               $scgst = $item['cgstamt'] * 2;
               $gst_total = $gst_total + $scgst;
           }else {
               $gst_rate = $item['igst'];
               $gst_total = $gst_total + $item['igstamt'];
           }
           
           
           
           $html .= '<td width="6%" style="text-align:center;"><b>'.$gst_rate.'</b></td>';
           $html .= '<td width="9%" style="text-align:right;"><b>'.$item['NetChallanAmt'].'</b></td>';
           $order_total = $order_total + $item['NetChallanAmt'];
           $html .= '</tr>';
           
        $i++;
    }
    $amt = (double) $amt;
     
    if(!empty($inv_item)){
        $html .='<tr>';
        $html .='<td colspan="2" style="text-align:center;"><b>Total</b></td>'; 
        $html .='<td style="text-align:center;"></td>';
        $html .='<td style="text-align:center;"></td>';
        $html .='<td style="text-align:right;"><b>'.$qty.'</b></td>';
        $html .='<td style="text-align:right;"><b>'.$units.'</b></td>';
        $html .='<td></td>';
        $html .='<td style="text-align:right;"><b>'.number_format($amt, 2, '.', '').'</b></td>';
        $html .='<td style="text-align:right;"><b>'.round($dis_amt,2).'</b></td>';
        $html .='<td style="text-align:right;"><b>'.number_format($taxable_amt_item, 2, '.', '').'</b></td>';
        
        $html .='<td style="text-align:center;"><b></b></td>'; 
        $html .='<td style="text-align:right;"><b>'.number_format($order_total, 2, '.', '').'</b></td>';
        $html .='</tr>';
    }
    
    //if($total_item_count > 17 && $total_item_count <=33){
        $html .='<tr><td colspan="12" width="99.1%" height="'.$empty_height.'px"></td></tr>';
        if($total_item_count > 13 && $total_item_count<=33){
        $html .='<tr><td colspan="12" height="'.$empty_height1.'px"></td></tr>';
        }
    
    
        $html .= '</tbody>';
        
        $html .= '<tfoot style="width:100%;position:fixed !important;bottom:0 !important">';
       
        $html .='<tr>
    <td colspan="2" width="20%" style="text-align:center;"><b>GST Breakup</b></td>
    <td width="6%" style="text-align:center;"><b>GST %'.'</b></td>
    <td width="13.2%" style="text-align:center;"><b>Taxable Amt</b></td>
    <td width="7%" style="text-align:center;"><b>CGST %</b></td>
    <td width="9%" style="text-align:center;"><b>CGST Amt</b></td>
    <td width="7%" style="text-align:center;"><b>SGST %</b></td>
    <td width="9%" style="text-align:center;"><b>SGST Amt</b></td>
    <td width="6%" style="text-align:center;"><b>IGST %</b></td>
    <td width="8%" style="text-align:center;"><b>IGST Amt</b></td>
    <td width="9%" style="text-align:center;"><b>GST Amt</b></td>
    <td width="5%" style="text-align:center;"><b>Item </b></td>
    
    </tr>';
    
    if($client_detail->state == "UP"){
    $gst_detail = get_gst_details($order_detail["OrderID"]);
    
    $gst_count = count($gst_detail);
    $bill_gst_total = 0.00;
    $i = 0;
    if($gst_count == "1"){
        $gst_brk_after_space_h = 22;
    }if($gst_count == "2"){
        $gst_brk_after_space_h = 0;
    }
    if($gst_count == "3"){
        $gst_brk_after_space_h = 0;
    }
           foreach ($gst_detail as $gvalue) {
                # code...
                
                $html .='<tr>';
                if($i == 0){
                    $html .='<td rowspan="'.$gst_count.'" colspan="2" width="20%"></td>';
                }
                $gst_per = $gvalue["cgst"] * 2;
                $gst_per = $gst_per;
                $taxable_amt = get_gst_taxable_amt($order_detail["OrderID"],$gvalue["cgst"]);
                $cs_gst_amt = get_gst_amt($order_detail["OrderID"],$gvalue["cgst"]);
                $gst_total_amt = $cs_gst_amt * 2;
                $item_count = get_gst_item_count($order_detail["OrderID"],$gvalue["cgst"]);
                $item_count_new = count($item_count);
                $html .='<td width="6%" style="text-align:center;"><b>'.$gst_per.'.00</b></td>
                <td width="13.2%" style="text-align:center;"><b>'.number_format($taxable_amt, 2, '.', '').'</b></td>
                <td width="7%" style="text-align:center;"><b>'.number_format($gvalue["cgst"], 2, '.', '').'</b></td>
                <td width="9%" style="text-align:center;"><b>'.number_format($cs_gst_amt, 2, '.', '').'</b></td>
                <td width="7%" style="text-align:center;"><b>'.number_format($gvalue["cgst"], 2, '.', '').'</b></td>
                <td width="9%" style="text-align:center;"><b>'.number_format($cs_gst_amt, 2, '.', '').'</b></td>
                <td width="6%" style="text-align:center;"></td>
                <td width="8%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"><b>'. number_format($gst_total_amt, 2, '.', '').'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$item_count_new .'</b></td>
                
                </tr>';
                $bill_gst_total = $bill_gst_total + $gst_total_amt;
            $i++;
            }
        }else {
            $igst_detail = get_igst_details($order_detail["OrderID"]);
            $igst_count = count($igst_detail);
            $i = 0;
           foreach ($igst_detail as $igvalue) {
                # code...
                $html .='<tr>';
                if($i == 0){
                    $html .='<td rowspan="'.$igst_count.'" colspan="2" width="20%"></td>';
                }
                $igst_per = $igvalue["igst"];
                $igst_per = $igst_per;
                $taxable_amt = get_igst_taxable_amt($order_detail["OrderID"],$igvalue["igst"]);
                $i_gst_amt = get_igst_amt($order_detail["OrderID"],$igvalue["igst"]);
                $i_item_count = get_igst_item_count($order_detail["OrderID"],$igvalue["igst"]);
                $i_item_count_new = count($i_item_count);
                $html .='<td width="6%" style="text-align:center;"><b>'.$igst_per.'</b></td>
                <td width="13.2%" style="text-align:center;"><b>'.number_format($taxable_amt, 2, '.', '').'</b></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="6%" style="text-align:center;"><b>'.$igvalue["igst"].'</b></td>
                <td width="8%" style="text-align:center;"><b>'. number_format($i_gst_amt, 2, '.', '').'</b></td>
                <td width="9%" style="text-align:center;"><b>'. number_format($i_gst_amt, 2, '.', '').'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$i_item_count_new.'</b></td>
                </tr>';
                $bill_gst_total = $bill_gst_total + $i_gst_amt;
            $i++;
           }  
        }
        if($gst_count>1){}else{
            $html .='<tr><td colspan="12" style="height:'.$gst_brk_after_space_h.'px;"></td></tr>';
        }
        
    $html .='<tr>'; 
    //$html2 .='<td></td>';
    $html .='<td colspan="2" style="border-right:none;" width="20%"><b>Pending Crates : 0</b></td>';
    $html .='<td colspan="3" style="border-right:none; border-left:none;" width="20%"><b>Crates on bill : '.$order_detail["Crates"].'</b></td>';
    $html .='<td colspan="3" style="border-right:none;" width="20%"><b>Cases on bill  :    '.$order_detail["Cases"].'</b></td>';
   
    $html .='<td colspan="3" width="25%"><b>Taxable Value/ Amt</b></td>';
    $html .='<td  style="text-align:right;" width="14.3%"><b>'. number_format($taxable_amt_item, 2, '.', '').'</b></td>';
    $html .='</tr>'; 
    $irn='';
    if($sales_detail->irn !== null){
        $irn = '<hr><b>IRN: '.$sales_detail->irn.'</b>';
    }
    $html .='<tr>'; 
    if($client_detail->state == "UP"){
        $bank_rowspan='rowspan="6"';
        $br=$irn;
    }else {
        $bank_rowspan='rowspan="5"';
        $br= $irn;
    }
    if($PlantID == "1"){
        $BankMsg = '<b>Bank A/c Details : <br>EARTHEN CONNECT INDIA PVT. LTD.<br>1. ICICI BANK - A/C - 777705520357, IFSC-ICIC0006450, Pune.</b>';
    }else if($PlantID == "3"){
        if($FY == "22"){
            $BankMsg = '<b>Bank A/c Details : <br>EARTHEN CONNECT INDIA PVT. LTD.<br>1. ICICI BANK - A/C - 777705520357, IFSC-ICIC0006450, Pune.</b>';
        }else if($FY >= 23){
            $BankMsg = '<b>Bank A/c Details : <br>EARTHEN CONNECT INDIA PVT. LTD.<br>1. ICICI BANK - A/C - 777705520357, IFSC-ICIC0006450, Pune.</b>';
        }else{
            $BankMsg = '<b>Bank A/c Details : <br>EARTHEN CONNECT INDIA PVT. LTD.<br>1. ICICI BANK - A/C - 777705520357, IFSC-ICIC0006450, Pune.</b>';
        }
    }else{
         $BankMsg = '';
    }
    
    $lg = Array();
    $lg['a_meta_charset'] = 'UTF-8';
    $lg['a_meta_dir'] = 'ltr';
    $lg['a_meta_language'] = 'IN';
    $lg['w_page'] = 'page';
//$pdf->setLanguageArray($lg);
//$pdf->SetFont('freesans', '', 12);
    //$html .='<td lang="in" colspan="8" '.$bank_rowspan.' ><b>Note : </b><br>'.$invoice_note->note.$br.'</td>';
    $src2 = base_url('uploads/invoice_note/invoice-note.png');
    $html .='<td colspan="8" '.$bank_rowspan.'><b>Note : </b><br><img src="'.$invoice_note->note.'" title="Link to Google.com" style="width:auto;height:90px;"/>'.$br.'</td>';
    //$html .='<td colspan="8" '.$bank_rowspan.'></td>';
    if($client_detail->state == "UP"){
        $html .='<td colspan="3"><b>Add CGST</b></td>';
        $grand_csgst = $gst_total / 2;
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($bill_gst_total / 2, 2, '.', '').'</b></td>';
    }else {
        $html .='<td colspan="3"><b>Add IGST</b></td>';
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($bill_gst_total, 2, '.', '').'</b></td>';
    }
    
    $html .='</tr>'; 
    
    if($client_detail->state == "UP"){
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add SGST</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($bill_gst_total /2, 2, '.', '').'</b></td>';
    $html .='</tr>'; 
    }
    $sale_data = get_is_tcs($order_detail["SalesID"]);
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add TCS @ '.round($sale_data->tcs,2).'%</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($sale_data->tcsAmt, 2, '.', '').'</b></td>';
    $html .='</tr>'; 
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Amount after GST + TCS (Rnd)</b></td>';
    $tcs_amt = $sale_data->tcsAmt;
    $inc_tcs_amt = $order_total + $tcs_amt;
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($sale_data->RndAmt, 2, '.', '').'</b></td>';
    $html .='</tr>'; 
    $BalAmt = $netBal + $sale_data->BillAmt + $sale_data->tcsAmt;
    if($netBal > 0){
        $CRDR1 = "Dr";
    }else{
        $CRDR1 = "Cr";
    }
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Previous Balance</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($netBal, 2, '.', '').'</b></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Balance Amt</b></td>';
    if($BalAmt > 0){
        $CRDR = "Dr";
    }else{
        $CRDR = "Cr";
    }
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.number_format($BalAmt, 2, '.', '').'</b></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    
    $src= 'https://chart.googleapis.com/chart?chs=115x115&cht=qr&chl='.$sales_detail->Qrcode.'&choe=UTF-8';
    if($sales_detail->irn !== null){
        $html .='<td colspan="3">';
        $html .='<img src="'.$src.'" title="Link to Google.com" />';
        $html .='</td>';
        $html .='<td colspan="5" >'.$BankMsg;
        
    }else{
        $html .='<td colspan="8">'.$BankMsg;
    }
    $html .='</td>';
    
    $html .='<td colspan="4">For<b> '.$PlantDetail->FIRMNAME.'<br><br><br><br><br><br><br>Authorized Signatory</b></td>';
    $html .='</tr>';
        $html .= '</tfoot>';
        $html .= '</table>';
    $html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="0">';
    $html .='<tr>';
    $html .='<td colspan="6" style="text-align:left;border-right:1px solid #fff;border-left:1px solid #fff;border-bottom:1px solid #fff;" >'.date('d/m/Y H:i:s').'</td>';
    $html .='<td colspan="6" style="text-align:right;border-right:1px solid #fff;border-bottom:1px solid #fff;">'.$GetLoggedInName->firstname.' '.$GetLoggedInName->lastname.'</td>';
    $html .='</tr>';
    $html .= '</table>';
}

$pdf->writeHTML($html, true, false, false, false, '');
?>