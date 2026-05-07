<?php
defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();
$pdf->SetMargins(5, 7, 5, 0);

$get_order_list = get_order_list($invoice->ChallanID);
$count = 0;
$count_order = count($get_order_list);
$GetLoggedInName = GetLoginFullName();

foreach ($get_order_list as $key => $order_detail) {
    
    $client_detail = get_client_detail($order_detail["AccountID"]);
    $client_details2 = get_client_detail($order_detail["AccountID2"]);
    
    $FY = $order_detail["FY"];
    $PlantDetail = GetPlantDetails($order_detail["PlantID"],$order_detail["FY"]);
    $gst_type = get_gst_type();
    $invoice_note = get_invoice_note();
    $sales_detail = get_sales_details($invoice->ChallanID,$order_detail["OrderID"]);
    $crateledger = GetCrateLedger($invoice->ChallanID,$sales_detail->AccountID,$sales_detail->Transdate);
    
    $datetime = $sales_detail->Transdate;
    $TotalCS = $sales_detail->Cases; 
    $TotalCR = $sales_detail->Crates;
    
    $timestamp = strtotime($datetime);
    $time = $timestamp - 1;
    $datetime = date("Y-m-d H:i:s", $time);
    $AccountCR = GetCreditBal($sales_detail->AccountID,$datetime);
    $AccountDR = GetDebitBal($sales_detail->AccountID,$datetime);
    $AccountOpn = getOPNBal($sales_detail->AccountID);
    $OpnBal = $AccountOpn->BAL1;
    $netBal = $OpnBal + $AccountDR->AmtSum - $AccountCR->AmtSum;
    
    $PlantID = $sales_detail->PlantID;
    $state_detail = get_state_detail($client_detail->state);
    $billing_state_detail = get_state_detail($client_detail->billing_state);
    $shipping_state_detail = get_state_detail($client_detail->shipping_state);
    
    $qty = 0;
    $amt = 0;
    $dis_amt = 0;
    $taxable_amt_item = 0;
    $order_total = 0;
    
    $title = "Goods Delivery Note";
    $pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 1));
    
    $left_bottom = "border-left:1px solid #333;border-bottom:1px solid #333;";
    $left_bottom_right = "border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;";
    
    // Start HTML content for this order
    $html = '<div style="position:relative; width:100%; page-break-inside:avoid;">';
    
    $html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" >';
    
    $html .= '<thead>';
    $html .= '<tr >
    <th colspan="10" width:"100%"><p style="text-align:center;font-size:14px;"><b>'.$title.'</b></p></th>
    </tr>';
    $company_logo = "InvoiceLogo.png";
    $html .= '<tr>
    <th colspan="3" align="center" style="border-top: 1px solid #333;border-left: 1px solid #333;border-bottom: 1px solid #333;height:80;" width="21.5%"><img style="display:block;margin:0 auto;" src="' . base_url('uploads/company/' . $company_logo) . '" title="logo" /></th>
    <th colspan="6" style="border-top: 1px solid #333;border-bottom: 1px solid #333;" width="57%"><p style="text-align:center;font-size:14px;"><b>'.$PlantDetail->FIRMNAME.'</b><br><b>'.$PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2.'<br></b><b>GSTIN '.$PlantDetail->GSTNO.', <i>fssai</i> Lic.no '.$PlantDetail->FLNO1.' </b><br><b>Contact No. : '.$PlantDetail->PHONENO.'</b></p></th>
    <th colspan="3" width="21.3%" style="border-top: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; text-align:center;"></th>
    </tr>';
    $html .= '<tr> 
    <th style="border-left: 1px solid #333;" width="12%">Invoice No</th>
    <th style="" width="13%"><b>'.$sales_detail->SalesID.'</b></th>
    <th style="" width="12%">Invoice Date</th>
    <th colspan="2" style="border-right: 1px solid #333;" width="13%"><b>'._d(substr($sales_detail->Transdate,0,10)).'</b></th>
    <th width="12%" style="">E-Way Bill No</th>
    <th width="13%" style=""><b>'.$order_detail["ewaybill_no"].'</b></th>
    <th style="" width="12%">E-Way Bill Date</th>
    <th width="13%" colspan="2" style="border-right: 1px solid #333;"><b>'._d(substr($order_detail["ewaybill_date"],0,10)).'</b></th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;" width="12%">Buyer P.O No.</th>
    <th style="" width="13%"><b>'.$order_detail["buyer_ord_no"].'</b></th>
    <th style="" width="12%">Buyer P.O Date</th>
    <th colspan="2" style="border-right: 1px solid #333;" width="13%"><b>'._d(substr($order_detail["buyer_ord_date"],0,10)).'</b></th>
    <th width="20%" style="">E-Way Bill Valid Up To</th>
    <th width="30.3%" style="border-right: 1px solid #333;"><b>'.$order_detail["ewaybill_valid_upto"].'</b></th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;" width="12%">Disp. Docs No</th>
    <th style="" width="13%"><b>'.$sales_detail->ChallanID.'</b></th>
    <th style="" width="12%">Disp. Date</th>
    <th colspan="2" style="border-right: 1px solid #333;" width="13%" colsapn="2"><b>'._d(substr($sales_detail->Transdate,0,10)).'</b></th>
    <th width="12%" style="" colspan="">Vehicle No</th>
    <th width="13%" colspan=""><b>'.$invoice->VehicleID.'</b></th>
    <th width="7%" style="" colspan="">Driver</th>
    <th width="17.6%" style="border-right: 1px solid #333;" colspan="5"><b>'.$invoice->DriverName.'</b></th>
    </tr>';
    $html .= '<tr>
    <th style="border-left: 1px solid #333;" width="12%">Order No</th>
    <th style="" width="13%"><b>'.$order_detail["OrderID"].'</b></th>
    <th style="" width="12%">Order Date</th>
    <th style="border-right: 1px solid #333;" width="13%" colspan="2"><b>'._d(substr($order_detail["Transdate"],0,10)).'</b></th>
    <th width="12%" style=""></th>
    <th width="13%" style=""><b></b></th>
    <th style="" width="12%"></th>
    <th colspan="2" width="13%" style="border-right: 1px solid #333;"><b></b></th>
    </tr>';
     $html .= '<tr>
    <th width="100%" style="border-right: 1px solid #333;border-top: 1px solid #333;border-left: 1px solid #333;height:30px;text-align:center;font-size:17px;" colspan="10" ><b>Goods Delivery Note No. : '.$sales_detail->SalesID.' </b><b> Date :'._d(substr($sales_detail->Transdate,0,10)).'</b></th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>Buyer (Bill To)</b></th>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>Consignee (Ship To)</b></th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>'.$client_detail->company.'</b></th>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>'.$client_details2->company.'</b></th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">'.$client_detail->address.'</th>
    <th style="border-right: 1px solid #333;" width="50%" colspan="5">'.$client_details2->address.'</th>
    </tr>';
    
    if(is_null($client_detail->address3)){
        // No address3
    } else {
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">'.$client_detail->address3.'</th>
        <th style="border-right: 1px solid #333;" width="50%" colspan="5">'.$client_details2->address3.'</th>
        </tr>';
    }
    $city_name = get_city_by_id($client_detail->city);
    if(empty($city_name)){
        $new_city_name = $client_detail->city;
    } else {
        $new_city_name = $city_name->city_name;
    }
    
    $city_name2 = get_city_by_id($client_details2->city);
    if(empty($city_name2)){
        $new_city_name2 = $client_details2->city;
    } else {
        $new_city_name2 = $city_name2->city_name;
    }
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">'.$new_city_name.' - '.$client_detail->zip.'</th>
    <th style="border-right: 1px solid #333;" width="50%" colspan="5">'.$new_city_name2.' - '.$client_details2->zip.'</th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">GSTIN  : <b>'.$client_detail->vat.'</b></th>
    <th style="border-right: 1px solid #333;" width="50%" colspan="5">GSTIN  : <b>'.$client_details2->vat.'</b></th>
    </tr>';
    if($client_detail->phonenumber == ""){
        $Mobile = $client_detail->cmobile;
    } else {
        $Mobile = $client_detail->phonenumber;
    }
    if($client_details2->phonenumber == ""){
        $Mobile2 = $client_details2->cmobile;
    } else {
        $Mobile2 = $client_details2->phonenumber;
    }
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">Mobile No  : <b>'.$Mobile.'</b></th>
    <th style="border-right: 1px solid #333;" width="50%" colspan="5">Mobile No  : <b>'.$Mobile2.'</b></th>
    </tr>';
    
    $html .= '<tr>
    <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="5">Food Lic No  : <b>'.$client_detail->FLNO1.'</b></th>
    <th style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="5">Food Lic No  : <b>'.$client_details2->FLNO1.'</b></th>
    </tr>';
    
    $rowspan = 'rowspan="1"';
    if($client_detail->rate_print == 'Y'){
        $item_name_width = "37%";
        $rate_display = "";
    } else {
        $item_name_width = "51%";
        $rate_display = "display:none";
    }
    $hsn_width = "7%";
    
    $html .= '<tr>
    <th width="3.6%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Sr. No.</b></th>
    <th width="'.$item_name_width.'" '.$rowspan.' style="'.$left_bottom.'"><b>Name of Product</b></th>
    <th width="'.$hsn_width.'" '.$rowspan.' style="'.$left_bottom.'"><b>Unit</b></th>
    <th width="6%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Challan Qty</b></th>
    <th width="7%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Delivery Qty.</b></th>
    <th width="8%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Accepted Qty</b></th>
    <th colspan="3" width="31.4%" '.$rowspan.' style="text-align:center;'.$left_bottom_right.'"><b>Reason Short Description</b></th>';
    
    $html .= '</tr>';
    
    $html .= '</thead>';
    $html .= '<tbody>';
    
    $inv_item = get_item_by_order_id($order_detail["OrderID"]);
    $inv_item_free = get_item_by_order_id_free($order_detail["OrderID"]);
    
    $i = 1;
    $total_item_count = count($inv_item)+count($inv_item_free);
    
    if($total_item_count <= 13 ){
        $empty_height = 215;
    }
    
    if($total_item_count > 13 && $total_item_count<=33){
        $empty_height = 678;
        $empty_height1 = 318;
    }
    if($total_item_count > 33 ){
        $empty_height = 318;
    }
    
    $TotalUnitQty = 0;
    $TotalPackQty = 0;
    $TotalSubAmt = 0;
    $TotalDiscOnSale = 0;
    $TotalCGSTAmt = 0;
    $TotalSGSTAmt = 0;
    $TotalIGSTAmt = 0;
    
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
        
        if($client_detail->article == "Y"){
            if($item['ArticleName']){
                $ItemName = $item['description']." (".$item['ArticleName'].")";
            } else {
                $ItemName = $item['description'];
            }
        } else {
            $ItemName = $item['description'];
        }
        $TotalUnitQty += $item['BilledQty'];
        $PackQty = $item['BilledQty']/$item['CaseQty'];
        $TotalPackQty += $PackQty;
        
        $html .= '<tr>'; 
        $html .= '<td width="3.6%" style="text-align:center;'.$left_bottom.'">'.$i.'</td>'; 
        $html .= '<td width="'.$item_name_width.'" class="description" align="left;" style="'.$left_bottom.'word-wrap:break-word;overflow-wrap:break-word;max-height:40px;"><b>'.$ItemName.'</b></td>';
        $html .= '<td width="'.$hsn_width.'" style="text-align:center;'.$left_bottom.'"><b>'. strtoupper($item['unit']).'</b></td>';
        $html .= '<td width="6%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
        $html .= '<td width="7%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
        $html .= '<td width="8%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
        $html .= '<td colspan="3" width="31.4%" style="text-align:right;'.$left_bottom_right.'"></td>';
        
        $TaxableAmt = $item['ChallanAmt'] - $item['DiscAmt'];
        $TotalSubAmt += $TaxableAmt;
        if($client_detail->state == "UP"){
            $gst_rate = $item['cgst'] + $item['sgst'];
            $gst_rate = $gst_rate.".00";
            $TotalSGSTAmt += $item['sgstamt'];
            $TotalCGSTAmt += $item['cgstamt'];
        } else {
            $gst_rate = $item['igst'];
            $TotalIGSTAmt += (int)$item['igstamt'];
        }
        $html .= '</tr>';
        $i++;
    }
    
    // Free Items
    foreach ($inv_item_free as $item) {
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
        
        if($client_detail->article == "Y"){
            if($item['ArticleName']){
                $ItemName = $item['description']." (".$item['ArticleName'].") - Free";
            } else {
                $ItemName = $item['description']." - Free";
            }
        } else {
            $ItemName = $item['description']." - Free";
        }
        $TotalUnitQty += $item['BilledQty'];
        $PackQty = $item['BilledQty']/$item['CaseQty'];
        $TotalPackQty += $PackQty;
        
        $html .= '<tr>'; 
        $html .= '<td width="3.6%" style="text-align:center;'.$left_bottom.'">'.$i.'</td>'; 
        $html .= '<td width="'.$item_name_width.'" class="description" align="left;" style="'.$left_bottom.'word-wrap:break-word;overflow-wrap:break-word;max-height:40px;"><b>'.$ItemName.'</b></td>';
        $html .= '<td width="'.$hsn_width.'" style="text-align:center;'.$left_bottom.'"><b>'. strtoupper($item['unit']).'</b></td>';
        $html .= '<td width="6%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
        $html .= '<td width="7%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
        $html .= '<td width="8%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
        $html .= '<td colspan="3" width="31.4%" style="text-align:right;'.$left_bottom_right.'"></td>';
        
        $TaxableAmt = $item['BasicRate'] * $item['BilledQty'];
        $TotalSubAmt += $TaxableAmt;
        $TotalDiscOnSale += $item['DiscAmt'];
        
        if($client_detail->state == "UP"){
            $gst_rate = $item['cgst'] + $item['sgst'];
            $gst_rate = $gst_rate.".00";
            $TotalSGSTAmt += $item['sgstamt'];
            $TotalCGSTAmt += $item['cgstamt'];
            $TotalDiscOnSale += $item['sgstamt'];
            $TotalDiscOnSale += $item['cgstamt'];
        } else {
            $gst_rate = $item['igst'];
            $TotalIGSTAmt += $item['igstamt'];
            $TotalDiscOnSale += $item['igstamt'];
        }
        $html .= '</tr>';
        
        $i++;
    }

    $html .= '</tbody>';

    $html .= '<tfoot>';

    if(!empty($inv_item)){
        $html .='<tr>';
        $html .='<td colspan="2" style="text-align:center;'.$left_bottom.'"><b>Total</b></td>'; 
        $html .='<td style="text-align:center;'.$left_bottom.'"></td>';
        $html .='<td style="text-align:right;'.$left_bottom.'"><b>'.(int) $TotalUnitQty.'</b></td>';
        $html .='<td style="text-align:right;'.$left_bottom.'"><b>'.(int) $TotalUnitQty.'</b></td>';
        $html .='<td style="text-align:right;'.$left_bottom.'"><b>'.(int) $TotalUnitQty.'</b></td>';
        $html .='<td colspan="3" style="text-align:right;'.$left_bottom_right.'"><b></b></td>';
        $html .='</tr>';
    }

    $html .='<tr><td colspan="10" width="100%" style="'.$left_bottom_right.'"></td></tr>';

    $html .='<tr>'; 
    $html .='<td colspan="5" height="50px;" style="border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;"><b>Time Of Arrival At Party :</b><br><br> <b>Time Of Departure From Party :</b></td>';
    
    $html .= '<td colspan="5" style="border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;">';
    $html .= '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr>';
    $html .= '<td width="120" style="font-size:15px;"><b>Driver Rating :</b></td>';
    $html .= '<td>';
    $html .= '<table border="0" cellpadding="2" cellspacing="0" style="display:inline-block;"><tr>';
    $circleNumbers = ['&#9312;','&#9313;','&#9314;','&#9315;','&#9316;'];
    foreach ($circleNumbers as $num) {
        $html .= '<td width="35" align="center" style="font-size:20px; line-height:22px;">'.$num.'</td>';
    }
    $html .= '</tr></table>';
    $html .= '</td>';
    $html .= '</tr></table>';
    $html .= '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td colspan="10" style="border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;">';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
    $html .= '<tr><th colspan="3"><b>Payment Details</b></th>';
	$html .= '<th colspan="3" align="right"><b>Total Inv Value : '.$sales_detail->RndAmt.' ₹</b></th></tr>';
    $html .= '<tr>';
    $html .= '<th width="7%"><b>Sr. No.</b></th>';
    $html .= '<th width="13%"><b>Currency</b></th>';
    $html .= '<th width="13%"><b>Denomination</b></th>';
    $html .= '<th width="17%"><b>Total</b></th>';
    $html .= '<th width="25%"><b>Receiver Sign</b></th>';
    $html .= '<th width="25%"><b>Cashier Sign</b></th>';
    $html .= '</tr>';

    $denoms = ['1 ₹ Coin','2 ₹ Coin','5 ₹ Coin','10 ₹ Coin','20 ₹ Coin','₹ 10','₹ 20','₹ 50','₹ 100','₹ 200','₹ 500'];
    for($d=1; $d<=11; $d++){
        $html .= '<tr><td>'.$d.'</td><td>'.$denoms[$d-1].'</td><td></td><td></td><td></td><td></td></tr>';
    }
    $html .= '<tr><td rowspan="2"><b></b></td><td><b>UPI/Bank</b></td><td></td><td></td><td></td><td></td></tr>';
    $html .= '<tr><td><b>Grand Total</b></td><td></td><td></td><td></td><td></td></tr>';
    $html .= '</table>';
    $html .= '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td colspan="3" height="60px;" style="text-align:center; border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;"><b>Party Sign & Stamp</b></td>';
    $html .= '<td colspan="4" height="60px;" style="text-align:center; border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;"><b>Driver Sign</b></td>';
    $html .= '<td colspan="3" height="60px;" style="text-align:center; border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;"><b>Accounts / Cashier Sign</b></td>';
    $html .= '</tr>';

    $html .= '</tfoot>';
    
    $html .='<tr>';
    $html .='<td colspan="6" style="text-align:left;border-right:1px solid #fff;border-left:1px solid #fff;border-bottom:1px solid #fff;" >'.date('d/m/Y H:i:s').'</td>';
    $html .='<td colspan="6" style="text-align:right;border-right:1px solid #fff;border-bottom:1px solid #fff;">'.$GetLoggedInName->firstname.' '.$GetLoggedInName->lastname.'</td>';
    $html .='</tr>';
    $html .= '</table> ';
    
    $html .= '</div>';

    // Write HTML for current order
    $pdf->writeHTML($html, true, false, false, false, '');
    
    // Add new page ONLY if this is not the last order
    if ($key < (count($get_order_list) - 1)) {
        $pdf->AddPage();
        $pdf->resetColumns();
        $pdf->setEqualColumns(1);
        $pdf->selectColumn(0);
    }
}
?>