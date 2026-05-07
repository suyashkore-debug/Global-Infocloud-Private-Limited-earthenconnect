<?php

defined('BASEPATH') or exit('No direct script access allowed');


$dimensions = $pdf->getPageDimensions();

$pdf->SetMargins(3, 0, 3, 0);
$pdf->Ln(0);



$get_order_list = get_order_list($invoice->ChallanID);
$count = 0;
$count_order = count($get_order_list);


    foreach ($get_order_list as $key => $order_detail) {
       
        $client_detail = get_client_detail($order_detail["AccountID"]);
        $client_detail2 = get_client_detail($order_detail["AccountID2"]);
        $company_detail = get_company_name_by_id($order_detail["PlantID"]);
        $gst_type = get_gst_type();
        $sales_detail = get_sales_details($invoice->ChallanID,$order_detail["OrderID"]);
        $state_detail = get_state_detail($client_detail->state);
        $billing_state_detail = get_state_detail($client_detail->billing_state);
        $shipping_state_detail = get_state_detail($client_detail->shipping_state);
        
        $title = "";
        if($order_detail["OrderType"] == "TaxItems"){
            $title = "TAX INVOICE";
        }
        if($order_detail["OrderType"] == "NonTaxItems"){
            $title = "BILL OF SUPPLY";
        }
        //$html = '<h1>Madhav Shinde'.$client_detail->company.'</h1>';
        $html = '<div class="col-md-12">
        
       
       <table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="1">
        <tr >
        <td colspan="4" style="border: 1px solid #333;"><p style="text-align:center;font-size:14px;"><b>'.$title.'</b><br><b>'.$company_detail->company_name.'</b><br><b>'.$company_detail->address.'<br></b><b>GSTIN '.$company_detail->gst.', <i>fssai</i> Lic.no '.$company_detail->food_lic.' </b><br><b>Contact No. : '.$company_detail->mobile1." , ".$company_detail->mobile2.'</b></p></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Invoice No.</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'.$sales_detail->SalesID.'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Ack No.</td>
        <td style="border-right: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Invoice Date</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'. substr($sales_detail->Transdate,0,10) .'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Ack Date</td>
        <td style="border-right: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Challan No</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'. $invoice->ChallanID .'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Vehicle No</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'.$invoice->VehicleID.'</b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;border-bottom: 1px solid #333;" width="20%">Order No</td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b>'. $order_detail["OrderID"] .'</b></td>
        <td width="20%" style="border-bottom: 1px solid #333;border-left: 1px solid #333;">eWayBillNo</td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>Bill To</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>Ship To</b></td>
        
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail->company.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail2->company.'</b></td>
        
        </tr>';
        
        if(is_null($client_detail->address3)){
            
        }else{
        $html .= '<tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address3.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_detail2->address3.'</td>
        </tr>';
        }
        $city_name = get_city_by_id($client_detail->city);
        if(empty($city_name)){
            $new_city_name = $client_detail->city;
        }else {
            $new_city_name = $city_name->city_name;
        }
        $city_name2 = get_city_by_id($client_detail2->city);
        if(empty($city_name2)){
            $new_city_name2 = $client_detail2->city;
        }else {
            $new_city_name2 = $city_name2->city_name;
        }
        $html .= '<tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_detail2->address.'</td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$new_city_name.' - '.$client_detail->pincode.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$new_city_name2.' - '.$client_detail2->pincode.'</td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail->vat.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail2->vat.'</b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$client_detail->cmobile.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$client_detail2->cmobile.'</b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail->FLNO1.'</b></td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail2->FLNO1.'</b></td>
        </tr>';
        
        $rowspan = 'rowspan="2"';
        $item_name_width = "28%";
        $hsn_width = "7%";
        
        $html .= '<tr>
        <td width="3.6%" '.$rowspan.' style="text-align:center;"><b>Sr.No.</b></td>
        <td width="'.$item_name_width.'" '.$rowspan.'><b>Name of Product</b></td>
        <td width="'.$hsn_width.'" '.$rowspan.'><b>HSN Code</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Pack</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Qty.</b></td>
        <td width="6.5%" '.$rowspan.' style="text-align:center;"><b>In Units</b></td>
        <td width="5%" '.$rowspan.' style="text-align:center;"><b>Rate</b></td>
        <td width="7%" '.$rowspan.' style="text-align:center;"><b>Amount</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>Disc Amount</b></td>
        <td width="7%" '.$rowspan.' style="text-align:center;"><b>Taxable Amount</b></td>';
    
    $html .= '<td style="text-align:center;" width="7%"><b>GST</b></td>';
    $html .= '<td style="text-align:center;" width="10%"><b>Total</b></td>';
    //$html .= '<td '.$rowspan.' style="text-align:center;">Total Amt</td>';    
    $html .= '</tr>';
    
    $html .= '<tr>
        
        <td style="text-align:center;"><b>%</b></td>
        <td style="text-align:center;"><b>Amount</b></td>
        </tr>';
    
    $i = 1;
        $qty = 0;
        $units = 0;
        $amt = 0;
        $dis_amt = 0;
        $taxable_amt_item = 0;
        $csgst_total = 0;
        $csgst = 0;
        $gst_total = 0;
        $order_total = 0;
        $empty_height= 280;
        foreach ($gst_type as $gstkey => $gastvalue) {
                # code...
                $gst.$gastvalue["taxrate"] = 0;
                
            }
        
    $inv_item = get_item_by_order_id($order_detail["OrderID"]); 
    
    $z =1;
    
    foreach ($inv_item as $item) {
            $hsn_code = get_hsn_byitem_id($item['ItemID']);
            if($i>1){
                $empty_height = $empty_height - 30;
            }
        if($z > 17){
            
        
        }else{
        
        if($item['OrderAmt'] == "0.00"){
            
        }else{
            
            $html .= '<tr>'; 
           $html .= '<td style="text-align:center;">'.$i.'</td>'; 
           $html .= '<td class="description" align="left;" width="'.$item_name_width.'"><b>'.$item['description'].'</b></td>';
           $html .= '<td width="'.$hsn_width.'" style="text-align:center;"><b>'.$hsn_code->hsn_code.'</b></td>';
           $html .= '<td style="text-align:right;"><b>'. (int) $item['CaseQty'].'</b></td>';
           if(is_null($item['eOrderQty'])){
               
               $html .= '<td style="text-align:right;"><b>'. (int) $item['OrderQty'] / $item['CaseQty'].'</b></td>';
               $html .= '<td style="text-align:right;"><b>'. (int) $item['OrderQty'].'</b></td>';
               $units = $units + $item['OrderQty'];
               $qty = $qty + $item['OrderQty'] / $item['CaseQty'];
           }else{
               $html .= '<td style="text-align:right;"> <b>'. (int) $item['eOrderQty'] / $item['CaseQty'].'</b></td>';
               $html .= '<td style="text-align:right;"><b>'. (int) $item['eOrderQty'].'</b></td>';
               $units = $units + $item['eOrderQty'];
               $qty = $qty + $item['eOrderQty'] / $item['CaseQty'];
           }
           
           $html .= '<td style="text-align:right;"><b>'.$item['BasicRate'].'</b></td>';
           $html .= '<td style="text-align:right;"><b>'.$item['OrderAmt'].'</b></td>';
           $amt = $amt + $item['OrderAmt'];
           $html .= '<td style="text-align:right;"><b>'.round($item['DiscAmt'],2) .'</b></td>';
           $dis_amt = $dis_amt + $item['DiscAmt'];
           $html .= '<td style="text-align:right;"><b>'.$item['OrderAmt'].'</b></td>';
           
           $taxable_amt_item = $taxable_amt_item + $item['OrderAmt'];
           
           if($client_detail->state == "UP"){
               $gst_rate = $item['cgst'] + $item['sgst'];
               $gst_rate = $gst_rate.".00";
               $scgst = $item['cgstamt'] * 2;
               $gst_total = $gst_total + $scgst;
           }else {
               $gst_rate = $item['igst'];
               $gst_total = $gst_total + $item['igstamt'];
           }
           
           
           
           $html .= '<td style="text-align:center;"><b>'.$gst_rate.'</b></td>';
           $html .= '<td style="text-align:right;"><b>'.$item['NetOrderAmt'].'</b></td>';
           $order_total = $order_total + $item['NetOrderAmt'];
           $html .= '</tr>';
           $i++;
           $z++;
        }
            
        }
           
    }
        
    $amt = (double) $amt;
    $html .='<tr>'; 
    
    $html .='<td colspan="2" style="text-align:center;"><b>Total</b></td>'; 
    $html .='<td style="text-align:center;"></td>';
    $html .='<td style="text-align:center;"></td>';
    $html .='<td style="text-align:right;"><b>'.$qty.'</b></td>';
    $html .='<td style="text-align:right;"><b>'.$units.'</b></td>';
    $html .='<td></td>';
    $html .='<td style="text-align:right;"><b>'.round($amt,2).'</b></td>';
    $html .='<td style="text-align:right;"><b>'.round($dis_amt,2).'</b></td>';
    $html .='<td style="text-align:right;"><b>'.round($taxable_amt,2).'</b></td>';
    
    $html .='<td style="text-align:center;"><b></b></td>'; 
    $html .='<td style="text-align:right;"><b>'.round($order_total,2).'</b></td>';
    $html .='</tr>'; 
    
    $html .='<tr><td colspan="12" style="height:'.$empty_height.'px;"></td></tr>';
    
    $html .='<tr>
    <td colspan="2" width="20%" style="text-align:center;"><b>GST Breakup</b></td>
    <td width="6%" style="text-align:center;"><b>GST %</b></td>
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
    $i = 0;
    if($gst_count == "1"){
        $gst_brk_after_space_h = 40;
    }if($gst_count == "2"){
        $gst_brk_after_space_h = 40;
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
                $html .='<td width="6%" style="text-align:center;"><b>'.$gst_per.'.00/'.$gst_count.'</b></td>
                <td width="13.2%" style="text-align:center;"><b>'.$taxable_amt.'</b></td>
                <td width="7%" style="text-align:center;"><b>'.$gvalue["cgst"].'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$cs_gst_amt.'</b></td>
                <td width="7%" style="text-align:center;"><b>'.$gvalue["cgst"].'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$cs_gst_amt.'</b></td>
                <td width="6%" style="text-align:center;"></td>
                <td width="8%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"><b>'.$gst_total_amt.'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$item_count_new.'</b></td>
                
                </tr>';
            $i++;
            }
        }else {
            
            
            
            $igst_detail = get_igst_details($order_detail["OrderID"]);
    
            $igst_count = count($igst_detail);
            $i = 0;
            if($igst_count == "1"){
                $gst_brk_after_space_h = 40;
            }if($igst_count == "2"){
                $gst_brk_after_space_h = 40;
            }
            if($igst_count == "3"){
                $gst_brk_after_space_h = 0;
            }
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
                <td width="13.2%" style="text-align:center;"><b>'.$taxable_amt.'</b></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="6%" style="text-align:center;"><b>'.$igvalue["igst"].'</b></td>
                <td width="8%" style="text-align:center;"><b>'.$i_gst_amt.'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$i_gst_amt.'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$i_item_count_new.'</b></td>
                
                </tr>';
            $i++;
           }  
        } 
    
    $html .='<tr><td colspan="12" style="height:'.$gst_brk_after_space_h.'px;"></td></tr>';
    $html .='<tr>'; 
    //$html2 .='<td></td>';
    $html .='<td colspan="2" style="border-right:none;" width="20%"><b>Pending Crates : 0</b></td>';
    $html .='<td colspan="3" style="border-right:none; border-left:none;" width="20%"><b>Crates on bill : '.$order_detail["Crates"].'</b></td>';
    $html .='<td colspan="3" style="border-right:none;" width="20%"><b>Cases on bill  :    '.$order_detail["Cases"].'</b></td>';
   
    
    $html .='<td colspan="3" width="25%"><b>Taxable Value/ Amt</b></td>';
    $html .='<td  style="text-align:right;" width="14.3%"><b>'.round($taxable_amt_item,2).'</b></td>';
    $html .='</tr>'; 
    
    
    $html .='<tr>'; 
    if($client_detail->state == "UP"){
        $bank_rowspan='rowspan="6"';
    }else {
        $bank_rowspan='rowspan="5"';
    }
    $html .='<td colspan="8" '.$bank_rowspan.'><b>Bank A/c Details<br>Punjab National Bank, Bank road Gorakhpur<br> IFSC Code- PUNB0187500<br>A/c No.- 1875008700013389</b></td>';
    if($client_detail->state == "UP"){
        $html .='<td colspan="3"><b>Add CGST</b></td>';
        $grand_csgst = $gst_total / 2;
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($grand_csgst,2).'</b></td>';
    }else {
        $html .='<td colspan="3"><b>Add IGST</b></td>';
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($gst_total,2).'</b></td>';
    }
    
    $html .='</tr>'; 
    
    if($client_detail->state == "UP"){
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add SGST</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($grand_csgst,2).'</b></td>';
    $html .='</tr>'; 
    }
    $sale_data = get_is_tcs($order_detail["SalesID"]);
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add TCS @ '.round($sale_data->tcs,2).'%</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($sale_data->tcsAmt,2).'</b></td>';
    $html .='</tr>'; 
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Amount after GST + TCS</b></td>';
    $tcs_amt = $sale_data->tcsAmt;
    $inc_tcs_amt = $order_total + $tcs_amt;
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($sale_data->RndAmt,2).'</b></td>';
    $html .='</tr>'; 
    
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Previous Balance</b></td>';
    $html .='<td '.$colspan_taxable_amt.'></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Balance Amt (Rnd)</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    
    $html .='<td colspan="8"></td>';
    $html .='<td colspan="4">For<b> '.$company_detail->company_name.'<br><br><br><br>Authorized Signatory</b></td>';
    $html .='</tr>';
    
    $html .= '</table>';
    
    
    
    // 2nd Page
    
    //$html .='<br><br>';
    $invoice_item_count = count($inv_item);
    if($z > 17 ){
        //$html .='<br><br><br>';
        $html .='<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="1">
        <tr >
        <td colspan="4" style="border: 1px solid #333;"><p style="text-align:center;font-size:14px;"><b>TAX INVOICE</b><br><b>'.$company_detail->company_name.'</b><br><b>'.$company_detail->address.'<br></b><b>GSTIN '.$company_detail->gst.', <i>fssai</i> Lic.no '.$company_detail->food_lic.' </b><br><b>Contact No. : '.$company_detail->mobile1." , ".$company_detail->mobile2.'</b></p></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Invoice No.</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'.$sales_detail->SalesID.'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Ack No.</td>
        <td style="border-right: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Invoice Date</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'. substr($sales_detail->Transdate,0,10) .'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Ack Date</td>
        <td style="border-right: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Challan No</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'. $invoice->ChallanID .'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Vehicle No</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'.$invoice->VehicleID.'</b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;border-bottom: 1px solid #333;" width="20%">Order No</td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b>'. $order_detail["OrderID"] .'</b></td>
        <td width="20%" style="border-bottom: 1px solid #333;border-left: 1px solid #333;">eWayBillNo</td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>Bill To</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>Ship To</b></td>
        
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail->company.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail->company.'</b></td>
        
        </tr>';
        
        if(is_null($client_detail->address3)){
            
        }else{
        $html .= '<tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address3.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address3.'</td>
        </tr>';
        }
        $city_name = get_city_by_id($client_detail->city);
        if(empty($city_name)){
            $new_city_name = $client_detail->city;
        }else {
            $new_city_name = $city_name->city_name;
        }
        $html .= '<tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address.'</td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$new_city_name.' - '.$client_detail->pincode.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$new_city_name.' - '.$client_detail->pincode.'</td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail->vat.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail->vat.'</b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$client_detail->cmobile.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$client_detail->cmobile.'</b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail->FLNO1.'</b></td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail->FLNO1.'</b></td>
        </tr>';
        
        $rowspan = 'rowspan="2"';
        $item_name_width = "28%";
        $hsn_width = "7%";
        
        $html .= '<tr>
        <td width="3.6%" '.$rowspan.' style="text-align:center;"><b>Sr.No.</b></td>
        <td width="'.$item_name_width.'" '.$rowspan.'><b>Name of Product</b></td>
        <td width="'.$hsn_width.'" '.$rowspan.'><b>HSN Code</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Pack</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Qty.</b></td>
        <td width="6.5%" '.$rowspan.' style="text-align:center;"><b>In Units</b></td>
        <td width="5%" '.$rowspan.' style="text-align:center;"><b>Rate</b></td>
        <td width="7%" '.$rowspan.' style="text-align:center;"><b>Amount</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>Disc Amount</b></td>
        <td width="7%" '.$rowspan.' style="text-align:center;"><b>Taxable Amount</b></td>';
    
    $html .= '<td style="text-align:center;" width="7%"><b>GST</b></td>';
    $html .= '<td style="text-align:center;" width="10%"><b>Total</b></td>';
    //$html .= '<td '.$rowspan.' style="text-align:center;">Total Amt</td>';    
    $html .= '</tr>';
    
    $html .= '<tr>
        
        <td style="text-align:center;"><b>%</b></td>
        <td style="text-align:center;"><b>Amount</b></td>
        </tr>';
    
    $i = 1;
        $qty = 0;
        $units = 0;
        $amt = 0;
        $dis_amt = 0;
        $taxable_amt_item = 0;
        $csgst_total = 0;
        $csgst = 0;
        $gst_total = 0;
        $order_total = 0;
        $empty_height= 280;
        foreach ($gst_type as $gstkey => $gastvalue) {
                # code...
                $gst.$gastvalue["taxrate"] = 0;
                
            }
        
    $inv_item = get_item_by_order_id($order_detail["OrderID"]); 
    
    $z =1;
    
    foreach ($inv_item as $item) {
            $hsn_code = get_hsn_byitem_id($item['ItemID']);
            if($i>1){
                $empty_height = $empty_height - 30;
            }
        if($z > 17 && $z < 36){
          
            if($item['OrderAmt'] == "0.00"){
                
            }else{
                
                $html .= '<tr>'; 
           $html .= '<td style="text-align:center;">'.$z.'</td>'; 
           $html .= '<td class="description" align="left;" width="'.$item_name_width.'"><b>'.$item['description'].'</b></td>';
           $html .= '<td width="'.$hsn_width.'" style="text-align:center;"><b>'.$hsn_code->hsn_code.'</b></td>';
           $html .= '<td style="text-align:right;"><b>'. (int) $item['CaseQty'].'</b></td>';
           if(is_null($item['eOrderQty'])){
               
               $html .= '<td style="text-align:right;"><b>'. (int) $item['OrderQty'] / $item['CaseQty'].'</b></td>';
               $html .= '<td style="text-align:right;"><b>'. (int) $item['OrderQty'].'</b></td>';
               $units = $units + $item['OrderQty'];
               $qty = $qty + $item['OrderQty'] / $item['CaseQty'];
           }else{
               $html .= '<td style="text-align:right;"> <b>'. (int) $item['eOrderQty'] / $item['CaseQty'].'</b></td>';
               $html .= '<td style="text-align:right;"><b>'. (int) $item['eOrderQty'].'</b></td>';
               $units = $units + $item['eOrderQty'];
               $qty = $qty + $item['eOrderQty'] / $item['CaseQty'];
           }
           
           $html .= '<td style="text-align:right;"><b>'.$item['BasicRate'].'</b></td>';
           $html .= '<td style="text-align:right;"><b>'.$item['OrderAmt'].'</b></td>';
           $amt = $amt + $item['OrderAmt'];
           $html .= '<td style="text-align:right;"><b>'.round($item['DiscAmt'],2) .'</b></td>';
           $dis_amt = $dis_amt + $item['DiscAmt'];
           $html .= '<td style="text-align:right;"><b>'.$item['OrderAmt'].'</b></td>';
           
           $taxable_amt_item = $taxable_amt_item + $item['OrderAmt'];
           
           if($client_detail->state == "UP"){
               $gst_rate = $item['cgst'] + $item['sgst'];
               $scgst = $item['cgstamt'] * 2;
               $gst_total = $gst_total + $scgst;
           }else {
               $gst_rate = $item['igst'];
               $gst_total = $gst_total + $item['igstamt'];
           }
           
           
           
           $html .= '<td style="text-align:center;"><b>'.$gst_rate.'.00</b></td>';
           $html .= '<td style="text-align:right;"><b>'.$item['NetOrderAmt'].'</b></td>';
           $order_total = $order_total + $item['NetOrderAmt'];
           $html .= '</tr>';
           $i++;
           $z++;
            }
        
        }else{
            if($item['OrderAmt'] == "0.00"){
                
            }else{
                $z++;
            }
            
        }
           
    }
        
    $amt = (double) $amt;
    $html .='<tr>'; 
    
    $html .='<td colspan="2" style="text-align:center;"><b>Total</b></td>'; 
    $html .='<td style="text-align:center;"></td>';
    $html .='<td style="text-align:center;"></td>';
    $html .='<td style="text-align:right;"><b>'.$qty.'</b></td>';
    $html .='<td style="text-align:right;"><b>'.$units.'</b></td>';
    $html .='<td></td>';
    $html .='<td style="text-align:right;"><b>'.round($amt,2).'</b></td>';
    $html .='<td style="text-align:right;"><b>'.round($dis_amt,2).'</b></td>';
    $html .='<td style="text-align:right;"><b>'.round($taxable_amt,2).'</b></td>';
    
    $html .='<td style="text-align:center;"><b></b></td>'; 
    $html .='<td style="text-align:right;"><b>'.round($order_total,2).'</b></td>';
    $html .='</tr>'; 
    
    $html .='<tr><td colspan="12" style="height:'.$empty_height.'px;"></td></tr>';
    
    $html .='<tr>
    <td colspan="2" width="20%" style="text-align:center;"><b>GST Breakup</b></td>
    <td width="6%" style="text-align:center;"><b>GST %</b></td>
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
    $i = 0;
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
                <td width="13.2%" style="text-align:center;"><b>'.$taxable_amt.'</b></td>
                <td width="7%" style="text-align:center;"><b>'.$gvalue["cgst"].'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$cs_gst_amt.'</b></td>
                <td width="7%" style="text-align:center;"><b>'.$gvalue["cgst"].'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$cs_gst_amt.'</b></td>
                <td width="6%" style="text-align:center;"></td>
                <td width="8%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"><b>'.$gst_total_amt.'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$item_count_new.'</b></td>
                
                </tr>';
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
                <td width="13.2%" style="text-align:center;"><b>'.$taxable_amt.'</b></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="6%" style="text-align:center;"><b>'.$igvalue["igst"].'</b></td>
                <td width="8%" style="text-align:center;"><b>'.$i_gst_amt.'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$i_gst_amt.'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$i_item_count_new.'</b></td>
                
                </tr>';
            $i++;
           }  
        } 
    
    $html .='<tr><td colspan="12" style="height:20px;"></td></tr>';
    $html .='<tr>'; 
    //$html2 .='<td></td>';
    $html .='<td colspan="2" style="border-right:none;" width="20%"><b>Pending Crates : 0</b></td>';
    $html .='<td colspan="3" style="border-right:none; border-left:none;" width="20%"><b>Crates on bill : '.$order_detail["Crates"].'</b></td>';
    $html .='<td colspan="3" style="border-right:none;" width="20%"><b>Cases on bill  :    '.$order_detail["Cases"].'</b></td>';
   
    
    $html .='<td colspan="3" width="25%"><b>Taxable Value/ Amt</b></td>';
    $html .='<td  style="text-align:right;" width="14.2%"><b>'.round($taxable_amt_item,2).'</b></td>';
    $html .='</tr>'; 
    
    
    $html .='<tr>'; 
    if($client_detail->state == "UP"){
        $bank_rowspan='rowspan="6"';
    }else {
        $bank_rowspan='rowspan="5"';
    }
    $html .='<td colspan="8" '.$bank_rowspan.'><b>Bank A/c Details<br>Punjab National Bank, Bank road Gorakhpur<br> IFSC Code- PUNB0187500<br>A/c No.- 1875008700013389</b></td>';
    if($client_detail->state == "UP"){
        $html .='<td colspan="3"><b>Add CGST</b></td>';
        $grand_csgst = $gst_total / 2;
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($grand_csgst,2).'</b></td>';
    }else {
        $html .='<td colspan="3"><b>Add IGST</b></td>';
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($gst_total,2).'</b></td>';
    }
    
    $html .='</tr>'; 
    
    if($client_detail->state == "UP"){
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add SGST</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($grand_csgst,2).'</b></td>';
    $html .='</tr>'; 
    }
    $sale_data = get_is_tcs($order_detail["SalesID"]);
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add TCS @ %</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($sale_data->tcs,2).'</b></td>';
    $html .='</tr>'; 
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Amount after GST + TCS</b></td>';
    $tcs_amt = $sale_data->tcsAmt;
    $inc_tcs_amt = $order_total + $tcs_amt;
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($sale_data->RndAmt,2).'</b></td>';
    $html .='</tr>'; 
    
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Previous Balance</b></td>';
    $html .='<td '.$colspan_taxable_amt.'></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Balance Amt (Rnd)</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    
    $html .='<td colspan="8"></td>';
    $html .='<td colspan="4">For<b> '.$company_detail->company_name.'<br><br><br><br>Authorized Signatory</b></td>';
    $html .='</tr>';
    
    $html .= '</table>';
    }
    
    
    // 3rd page
    if($z > 36 ){
        $html .='<br><br><br>';
        $html .='<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="1">
        <tr >
        <td colspan="4" style="border: 1px solid #333;"><p style="text-align:center;font-size:14px;"><b>TAX INVOICE</b><br><b>'.$company_detail->company_name.'</b><br><b>'.$company_detail->address.'<br></b><b>GSTIN '.$company_detail->gst.', <i>fssai</i> Lic.no '.$company_detail->food_lic.' </b><br><b>Contact No. : '.$company_detail->mobile1." , ".$company_detail->mobile2.'</b></p></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Invoice No.</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'.$sales_detail->SalesID.'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Ack No.</td>
        <td style="border-right: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Invoice Date</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'. substr($sales_detail->Transdate,0,10) .'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Ack Date</td>
        <td style="border-right: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;" width="20%">Challan No</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'. $invoice->ChallanID .'</b></td>
        <td width="20%" style="border-left: 1px solid #333;">Vehicle No</td>
        <td style="border-right: 1px solid #333;" width="30%"><b>'.$invoice->VehicleID.'</b></td>
        </tr>
        <tr>
        <td style="border-left: 1px solid #333;border-bottom: 1px solid #333;" width="20%">Order No</td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b>'. $order_detail["OrderID"] .'</b></td>
        <td width="20%" style="border-bottom: 1px solid #333;border-left: 1px solid #333;">eWayBillNo</td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="30%"><b></b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>Bill To</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>Ship To</b></td>
        
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail->company.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2"><b>'.$client_detail->company.'</b></td>
        
        </tr>';
        
        if(is_null($client_detail->address3)){
            
        }else{
        $html .= '<tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address3.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address3.'</td>
        </tr>';
        }
        $city_name = get_city_by_id($client_detail->city);
        if(empty($city_name)){
            $new_city_name = $client_detail->city;
        }else {
            $new_city_name = $city_name->city_name;
        }
        $html .= '<tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$client_detail->address.'</td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">'.$new_city_name.' - '.$client_detail->pincode.'</td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">'.$new_city_name.' - '.$client_detail->pincode.'</td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail->vat.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">GSTIN  <b>'.$client_detail->vat.'</b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$client_detail->cmobile.'</b></td>
        <td style="border-right: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Mobile No  <b>'.$client_detail->cmobile.'</b></td>
        </tr>
        
        <tr>
        <td style="border-left: 1px solid #333;border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail->FLNO1.'</b></td>
        <td style="border-right: 1px solid #333;border-bottom: 1px solid #333;border-left: 1px solid #333;" width="50%" colspan="2">Food Lic No  <b>'.$client_detail->FLNO1.'</b></td>
        </tr>';
        
        $rowspan = 'rowspan="2"';
        $item_name_width = "28%";
        $hsn_width = "7%";
        
        $html .= '<tr>
        <td width="3.6%" '.$rowspan.' style="text-align:center;"><b>Sr.No.</b></td>
        <td width="'.$item_name_width.'" '.$rowspan.'><b>Name of Product</b></td>
        <td width="'.$hsn_width.'" '.$rowspan.'><b>HSN Code</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Pack</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>CR/CS Qty.</b></td>
        <td width="6.5%" '.$rowspan.' style="text-align:center;"><b>In Units</b></td>
        <td width="5%" '.$rowspan.' style="text-align:center;"><b>Rate</b></td>
        <td width="7%" '.$rowspan.' style="text-align:center;"><b>Amount</b></td>
        <td width="6%" '.$rowspan.' style="text-align:center;"><b>Disc Amount</b></td>
        <td width="7%" '.$rowspan.' style="text-align:center;"><b>Taxable Amount</b></td>';
    
    $html .= '<td style="text-align:center;" width="7%"><b>GST</b></td>';
    $html .= '<td style="text-align:center;" width="10%"><b>Total</b></td>';
    //$html .= '<td '.$rowspan.' style="text-align:center;">Total Amt</td>';    
    $html .= '</tr>';
    
    $html .= '<tr>
        
        <td style="text-align:center;"><b>%</b></td>
        <td style="text-align:center;"><b>Amount</b></td>
        </tr>';
    
    $i = 1;
        $qty = 0;
        $units = 0;
        $amt = 0;
        $dis_amt = 0;
        $taxable_amt_item = 0;
        $csgst_total = 0;
        $csgst = 0;
        $gst_total = 0;
        $order_total = 0;
        $empty_height= 280;
        foreach ($gst_type as $gstkey => $gastvalue) {
                # code...
                $gst.$gastvalue["taxrate"] = 0;
                
            }
        
    $inv_item = get_item_by_order_id($order_detail["OrderID"]); 
    
    $z =1;
    
    foreach ($inv_item as $item) {
            $hsn_code = get_hsn_byitem_id($item['ItemID']);
            if($i>1){
                $empty_height = $empty_height - 30;
            }
        if($z > 36 && $z < 55){
            
            if($item['OrderAmt'] == "0.00"){
                
            }else{
            
        $html .= '<tr>'; 
           $html .= '<td style="text-align:center;">'.$z.'</td>'; 
           $html .= '<td class="description" align="left;" width="'.$item_name_width.'"><b>'.$item['description'].'</b></td>';
           $html .= '<td width="'.$hsn_width.'" style="text-align:center;"><b>'.$hsn_code->hsn_code.'</b></td>';
           $html .= '<td style="text-align:right;"><b>'. (int) $item['CaseQty'].'</b></td>';
           if(is_null($item['eOrderQty'])){
               
               $html .= '<td style="text-align:right;"><b>'. (int) $item['OrderQty'] / $item['CaseQty'].'</b></td>';
               $html .= '<td style="text-align:right;"><b>'. (int) $item['OrderQty'].'</b></td>';
               $units = $units + $item['OrderQty'];
               $qty = $qty + $item['OrderQty'] / $item['CaseQty'];
           }else{
               $html .= '<td style="text-align:right;"> <b>'. (int) $item['eOrderQty'] / $item['CaseQty'].'</b></td>';
               $html .= '<td style="text-align:right;"><b>'. (int) $item['eOrderQty'].'</b></td>';
               $units = $units + $item['eOrderQty'];
               $qty = $qty + $item['eOrderQty'] / $item['CaseQty'];
           }
           
           $html .= '<td style="text-align:right;"><b>'.$item['BasicRate'].'</b></td>';
           $html .= '<td style="text-align:right;"><b>'.$item['OrderAmt'].'</b></td>';
           $amt = $amt + $item['OrderAmt'];
           $html .= '<td style="text-align:right;"><b>'.round($item['DiscAmt'],2) .'</b></td>';
           $dis_amt = $dis_amt + $item['DiscAmt'];
           $html .= '<td style="text-align:right;"><b>'.$item['OrderAmt'].'</b></td>';
           
           $taxable_amt_item = $taxable_amt_item + $item['OrderAmt'];
           
           if($client_detail->state == "UP"){
               $gst_rate = $item['cgst'] + $item['sgst'];
               $scgst = $item['cgstamt'] * 2;
               $gst_total = $gst_total + $scgst;
           }else {
               $gst_rate = $item['igst'];
               $gst_total = $gst_total + $item['igstamt'];
           }
           
           
           
           $html .= '<td style="text-align:center;"><b>'.$gst_rate.'.00</b></td>';
           $html .= '<td style="text-align:right;"><b>'.$item['NetOrderAmt'].'</b></td>';
           $order_total = $order_total + $item['NetOrderAmt'];
           $html .= '</tr>';
           $i++;
           $z++;
            }
        }else{
            if($item['OrderAmt'] == "0.00"){
                
            }else{
                $z++;
            }
            
        }
           
    }
        
    $amt = (double) $amt;
    $html .='<tr>'; 
    
    $html .='<td colspan="2" style="text-align:center;"><b>Total</b></td>'; 
    $html .='<td style="text-align:center;"></td>';
    $html .='<td style="text-align:center;"></td>';
    $html .='<td style="text-align:right;"><b>'.$qty.'</b></td>';
    $html .='<td style="text-align:right;"><b>'.$units.'</b></td>';
    $html .='<td></td>';
    $html .='<td style="text-align:right;"><b>'.round($amt,2).'</b></td>';
    $html .='<td style="text-align:right;"><b>'.round($dis_amt,2).'</b></td>';
    $html .='<td style="text-align:right;"><b>'.round($taxable_amt,2).'</b></td>';
    
    $html .='<td style="text-align:center;"><b></b></td>'; 
    $html .='<td style="text-align:right;"><b>'.round($order_total,2).'</b></td>';
    $html .='</tr>'; 
    
    $html .='<tr><td colspan="12" style="height:'.$empty_height.'px;"></td></tr>';
    
    $html .='<tr>
    <td colspan="2" width="20%" style="text-align:center;"><b>GST Breakup</b></td>
    <td width="6%" style="text-align:center;"><b>GST %</b></td>
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
    $i = 0;
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
                <td width="13.2%" style="text-align:center;"><b>'.$taxable_amt.'</b></td>
                <td width="7%" style="text-align:center;"><b>'.$gvalue["cgst"].'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$cs_gst_amt.'</b></td>
                <td width="7%" style="text-align:center;"><b>'.$gvalue["cgst"].'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$cs_gst_amt.'</b></td>
                <td width="6%" style="text-align:center;"></td>
                <td width="8%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"><b>'.$gst_total_amt.'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$item_count_new.'</b></td>
                
                </tr>';
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
                <td width="13.2%" style="text-align:center;"><b>'.$taxable_amt.'</b></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="7%" style="text-align:center;"></td>
                <td width="9%" style="text-align:center;"></td>
                <td width="6%" style="text-align:center;"><b>'.$igvalue["igst"].'</b></td>
                <td width="8%" style="text-align:center;"><b>'.$i_gst_amt.'</b></td>
                <td width="9%" style="text-align:center;"><b>'.$i_gst_amt.'</b></td>
                <td width="5%" style="text-align:center;"><b>'.$i_item_count_new.'</b></td>
                
                </tr>';
            $i++;
           }  
        } 
    
    $html .='<tr><td colspan="12" style="height:20px;"></td></tr>';
    $html .='<tr>'; 
    //$html2 .='<td></td>';
    $html .='<td colspan="2" style="border-right:none;" width="20%"><b>Pending Crates : 0</b></td>';
    $html .='<td colspan="3" style="border-right:none; border-left:none;" width="20%"><b>Crates on bill : '.$order_detail["Crates"].'</b></td>';
    $html .='<td colspan="3" style="border-right:none;" width="20%"><b>Cases on bill  :    '.$order_detail["Cases"].'</b></td>';
   
    
    $html .='<td colspan="3" width="25%"><b>Taxable Value/ Amt</b></td>';
    $html .='<td  style="text-align:right;" width="14.3%"><b>'.round($taxable_amt_item,2).'</b></td>';
    $html .='</tr>'; 
    
    
    $html .='<tr>'; 
    if($client_detail->state == "UP"){
        $bank_rowspan='rowspan="6"';
    }else {
        $bank_rowspan='rowspan="5"';
    }
    $html .='<td colspan="8" '.$bank_rowspan.'><b>Bank A/c Details<br>Punjab National Bank, Bank road Gorakhpur<br> IFSC Code- PUNB0187500<br>A/c No.- 1875008700013389</b></td>';
    if($client_detail->state == "UP"){
        $html .='<td colspan="3"><b>Add CGST</b></td>';
        $grand_csgst = $gst_total / 2;
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($grand_csgst,2).'</b></td>';
    }else {
        $html .='<td colspan="3"><b>Add IGST</b></td>';
        $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($gst_total,2).'</b></td>';
    }
    
    $html .='</tr>'; 
    
    if($client_detail->state == "UP"){
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add SGST</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($grand_csgst,2).'</b></td>';
    $html .='</tr>'; 
    }
    $sale_data = get_is_tcs($order_detail["SalesID"]);
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Add TCS @ %</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($sale_data->tcs,2).'</b></td>';
    $html .='</tr>'; 
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Amount after GST + TCS</b></td>';
    $tcs_amt = $sale_data->tcsAmt;
    $inc_tcs_amt = $order_total + $tcs_amt;
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b>'.round($sale_data->RndAmt,2).'</b></td>';
    $html .='</tr>'; 
    
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Previous Balance</b></td>';
    $html .='<td '.$colspan_taxable_amt.'></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    $html .='<td colspan="3"><b>Balance Amt (Rnd)</b></td>';
    $html .='<td '.$colspan_taxable_amt.' style="text-align:right;"></td>';
    $html .='</tr>';
    $html .='<tr>'; 
    
    $html .='<td colspan="8"></td>';
    $html .='<td colspan="4">For<b> '.$company_detail->company_name.'<br><br><br><br>Authorized Signatory</b></td>';
    $html .='</tr>';
    
    $html .= '</table>';
    }
    
    
    
    
    $html .= '</div>';
        $pdf->writeHTML($html, true, false, false, false, '');
        
        
        
       
    
    $pdf->Ln(0);

  
    


$count++;
//$pdf->Ln(0);
if($count == $count_order){
    
}else{
    $pdf->AddPage();
}

        
        
    }


