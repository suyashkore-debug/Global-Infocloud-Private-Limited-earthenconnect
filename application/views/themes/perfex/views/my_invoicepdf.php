<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	
	$dimensions = $pdf->getPageDimensions();
	
	$pdf->SetMargins(5, 7, 5, 0);
	//$pdf->Ln(0);
	$get_order_list = get_order_list($invoice->ChallanID);
	// print_r($get_order_list);
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
        $crateledger = GetCrateLedger($invoice->ChallanID,$sales_detail->AccountID,$sales_detail->Transdate);
        // var_dump($crateledger);
		
        // Current date and time
        $datetime = $sales_detail->Transdate;
        $TotalCS = $sales_detail->Cases; 
		$TotalCR = $sales_detail->Crates;
		
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
            $title = "TAX INVOICE ";
		}
        if($order_detail["OrderType"] == "NonTaxItems"){
            $title = "BILL OF SUPPLY";
		}
        $pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 1));
        $left_bottom = "border-left:1px solid #333;border-bottom:1px solid #333;";
        $left_bottom_right = "border-left:1px solid #333;border-bottom:1px solid #333;border-right:1px solid #333;";
        //$html .= '<div class="page-break-after: always;">';
        $html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" >';
		
        $html .= '<thead>';
        $html .= '<tr >
        <th colspan="10" width:"100%"><p style="text-align:center;font-size:14px;"><b>'.$title.'</b></p></th>
        </tr>';
		// $company_logo = "InvoiceLogo2.png";
		// $company_logo = "996124ca70d147015f6a95f9eeb82393.png";
		$company_logo = "InvoiceLogo.png";
		$html .= '<tr>
        <th colspan="3"  align="center" style="border-top: 1px solid #333;border-left: 1px solid #333;border-bottom: 1px solid #333;height:80;" width="21.5%"><img style="display:block;margin:0 auto;" src="' . base_url('uploads/company/' . $company_logo) . '"  title="logo" /></th>
        <th colspan="6" style="border-top: 1px solid #333;border-bottom: 1px solid #333;" width="57%"><p style="text-align:center;font-size:14px;"><b>'.$PlantDetail->FIRMNAME.'</b><br><b>'.$PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2.'<br></b><b>GSTIN '.$PlantDetail->GSTNO.', <i>fssai</i> Lic.no '.$PlantDetail->FLNO1.' </b><br><b>Contact No. : '.$PlantDetail->PHONENO.'</b></p></th>
        <th colspan="3" width="21.3%" style="border-top: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; text-align:center;"></th>
        </tr>';
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="12%">Invoice No</th>
        <th style="" width="13%"><b>'.$sales_detail->SalesID.'</b></th>
        <th style="" width="12%">Invoice Date</th>
        <th colspan="2" style="border-right: 1px solid #333;" width="13%"><b>'._d(substr($sales_detail->Transdate,0,10)).'</b></th>
        <th width="12%" style="">Ack No</th>
        <th width="13%" style=""></th>
        <th style="" width="12%">Ack Date</th>
        <th colspan="2" width="13%" style="border-right: 1px solid #333;"></th>
        
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="12%">Buy Order No</th>
        <th style="" width="13%"><b>'.$order_detail["buyer_ord_no"].'</b></th>
        <th style="" width="12%">Buy Order Date</th>
        <th colspan="2" style="border-right: 1px solid #333;" width="13%"><b>'._d(substr($order_detail["buyer_ord_date"],0,10)).'</b></th>
        <th width="12%" style="">Vehicle No</th>
        <th width="13%" style=""><b>'.$invoice->VehicleID.'</b></th>
        <th style="" width="12%">E-Way Bill No</th>
        <th width="13%" colspan="2" style="border-right: 1px solid #333;"></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="12%">Dis. Docs No</th>
        <th style="" width="13%"><b>'.$sales_detail->ChallanID.'</b></th>
        <th style="" width="12%">Del. Date</th>
        <th colspan="2" style="border-right: 1px solid #333;" width="13%" colsapn="2"><b>'._d(substr($sales_detail->Transdate,0,10)).'</b></th>
        <th width="50%" style="border-right: 1px solid #333;" colspan="5">Dispatched through</th>
        </tr>';
        $html .= '<tr>
        <th style="border-left: 1px solid #333;" width="12%">Reference No</th>
        <th style="" width="13%"><b></b></th>
        <th style="" width="12%">Reference Date</th>
        <th style="border-right: 1px solid #333;" width="13%" colspan="2"><b></b></th>
        <th width="50%" style="border-right: 1px solid #333;" colspan="5">Other References</th>
        </tr>';
        $html .= '<tr>
        <th width="50%" style="border-top: 1px solid #333;border-left: 1px solid #333;height:50px;" colspan="5">Mode/Terms of Payment</th>
        <th width="50%" style="border-right: 1px solid #333;border-top: 1px solid #333;border-left: 1px solid #333;height:50px;" colspan="5">Terms Of Delivery : </th>
        </tr>';
        
		/*$html .= '<tr>
			<th style="border-left: 1px solid #333;" width="20%">Challan No. <br/><b>'.$sales_detail->ChallanID.'</b></th>
			<th style="border-right: 1px solid #333;" width="30%">Order No<br/><b>'.$sales_detail->OrderID.'</b></th>
			<th style="border-left: 1px solid #333;" width="20%">Buyers Order No. & Date <br/><b>'.$invoice->buyer_ord_no.'</b></th>
			<th style="border-right: 1px solid #333;" width="30%">Motor Vehicle No. <br/><b>'.$invoice->VehicleID.'</b></th>
			<th width="20%" style="border-left: 1px solid #333;">Reference No. & Date. <br/><b></b></th>
			<th style="border-right: 1px solid #333;" width="30%">Other References</th>
			
		</tr>';*/
        
        /*$html .= '<tr>
			<th style="border-left: 1px solid #333;" width="20%">Delivery Note</th>
			<th style="border-right: 1px solid #333;" width="30%">Delivery Note Date <br/></th>
			<th width="20%" style="border-left: 1px solid #333;">Dispatched through <br/><b>'.$invoice->transporter.'</b></th>
			<th style="border-right: 1px solid #333;" width="30%">Destination <br/><b>'.$client_details2->address.'</b></th>
			</tr>';
			
			$html .= '<tr>
			<th style="border-left: 1px solid #333; border-bottom: 1px solid #333;" width="20%">Dispatch Doc No.
			</th>
			<th style="border-right: 1px solid #333; border-bottom: 1px solid #333;" width="30%">Mode/Terms of Payment<br/></th>
			<th width="20%" style="border-left: 1px solid #333; border-bottom: 1px solid #333;">E-Way Bill No.</th>
			<th style="border-right: 1px solid #333; border-bottom: 1px solid #333;" width="30%"> </th>
		</tr>';*/
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>Buyer (Bill To)</b></th>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>Consignee (Ship To)</b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>'.$client_detail->company.'</b></th>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-top: 1px solid #333;" width="50%" colspan="5"><b>'.$client_details2->company.'</b></th>
        </tr>';
        
        
        // if(is_null($client_detail->address3)){
		
		// }else{
		$html .= '<tr>
		<th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">'.$client_detail->address.'</th>
		<th style="border-right: 1px solid #333;" width="50%" colspan="5">'.$client_details2->address.'</th>
		</tr>';
		// }
        
		if(is_null($client_detail->address3)){
            
			}else{
			$html .= '<tr>
			<th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">'.$client_detail->address3.'</th>
			<th style="border-right: 1px solid #333;" width="50%" colspan="5">'.$client_details2->address3.'</th>
			</tr>';
		}
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
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">'.$new_city_name.' - '.$client_detail->zip.'</th>
        <th style="border-right: 1px solid #333;" width="50%" colspan="5">'.$new_city_name2.' - '.$client_details2->zip.'</th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">GSTIN  <b>'.$client_detail->vat.'</b></th>
        <th style="border-right: 1px solid #333;" width="50%" colspan="5">GSTIN  <b>'.$client_details2->vat.'</b></th>
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
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;" width="50%" colspan="5">Mobile No  <b>'.$Mobile.'</b></th>
        <th style="border-right: 1px solid #333;" width="50%" colspan="5">Mobile No  <b>'.$Mobile2.'</b></th>
        </tr>';
        
        $html .= '<tr>
        <th style="border-left: 1px solid #333;border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="5">Food Lic No  <b>'.$client_detail->FLNO1.'</b></th>
        <th style="border-right: 1px solid #333;border-bottom: 1px solid #333;" width="50%" colspan="5">Food Lic No  <b>'.$client_details2->FLNO1.'</b></th>
        </tr>';
		
        $rowspan = 'rowspan="1"';
		if($client_detail->rate_print == 'Y'){
			$item_name_width = "36%";
			$rate_display = "";
			}else{
			$item_name_width = "50%";
			$rate_display = "display:none";
		}
        $hsn_width = "8%";
        $html .= '<tr>
        <th width="3.6%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Sr. No.</b></th>
        <th width="'.$item_name_width.'" '.$rowspan.' style="'.$left_bottom.'"><b>Name of Product</b></th>
        <th width="'.$hsn_width.'" '.$rowspan.' style="'.$left_bottom.'"><b>HSN</b></th>
        <th width="6%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Pack Qty</b></th>
        <th width="6%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Pkt Qty.</b></th>
        <th width="7%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Billed Qty.</b></th>
        <th width="8%" '.$rowspan.' style="text-align:center;'.$left_bottom.';'.$rate_display.'"><b>Rate</b></th>
        <th width="6.4%" '.$rowspan.' style="text-align:center;'.$left_bottom.'"><b>Unit</b></th>
        <th width="6%" '.$rowspan.' style="text-align:center;'.$left_bottom.';'.$rate_display.'"><b>Disc %</b></th>
        <th width="13%" '.$rowspan.' style="text-align:center;'.$left_bottom_right.'"><b>Taxable Amount</b></th>';
		
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
					}else{
                    $ItemName = $item['description'];
				}
				}else{
                $ItemName = $item['description'];
			}
			$TotalUnitQty += $item['BilledQty'];
			$PackQty = $item['BilledQty']/$item['CaseQty'];
			$TotalPackQty += $PackQty;
			
			$html .= '<tr>'; 
			$html .= '<td width="3.6%" style="text-align:center;'.$left_bottom.'">'.$i.'</td>'; 
			$html .= '<td width="'.$item_name_width.'" class="description" align="left;" width="'.$item_name_width.'" style="'.$left_bottom.'"><b>'.$ItemName.' </b></td>';
			$html .= '<td width="'.$hsn_width.'" style="text-align:center;'.$left_bottom.'"><b>'.$hsn_code->hsn_code.'</b></td>';
			$html .= '<td width="6%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['CaseQty'].'</b></td>';
			
			$html .= '<td width="6%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
			$html .= '<td width="7%" style="text-align:right;'.$left_bottom.'"><b>'.number_format($PackQty, 2, '.', '') .'</b></td>';
			$html .= '<td width="8%" style="text-align:right;'.$left_bottom.';'.$rate_display.'"><b>'.number_format($item['BasicRate'], 2, '.', '').'</b></td>';
			$html .= '<td width="6.4%" style="text-align:right;'.$left_bottom.'"><b>'. strtoupper($item['unit']).'</b></td>';
			$html .= '<td width="6%" style="text-align:right;'.$left_bottom.';'.$rate_display.'"><b>'.number_format($item['DiscPerc'], 2, '.', '') .'</b></td>';
			$TaxableAmt = $item['ChallanAmt'] - $item['DiscAmt'];
			$TotalSubAmt += $TaxableAmt;
			$html .= '<td width="13%" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TaxableAmt, 2, '.', '').'</b></td>';
			
			if($client_detail->state == "UP"){
				$gst_rate = $item['cgst'] + $item['sgst'];
				$gst_rate = $gst_rate.".00";
				$TotalSGSTAmt += $item['sgstamt'];
				$TotalCGSTAmt += $item['cgstamt'];
				}else {
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
					}else{
                    $ItemName = $item['description']." - Free";
				}
				}else{
                $ItemName = $item['description']." - Free";
			}
            $TotalUnitQty += $item['BilledQty'];
			$PackQty = $item['BilledQty']/$item['CaseQty'];
			$TotalPackQty += $PackQty;
			
			$html .= '<tr>'; 
			$html .= '<td width="3.6%" style="text-align:center;'.$left_bottom.'">'.$i.'</td>'; 
			$html .= '<td width="'.$item_name_width.'" class="description" align="left;" width="'.$item_name_width.'" style="'.$left_bottom.'"><b>'.$ItemName.' </b></td>';
			$html .= '<td width="'.$hsn_width.'" style="text-align:center;'.$left_bottom.'"><b>'.$hsn_code->hsn_code.'</b></td>';
			$html .= '<td width="6%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['CaseQty'].'</b></td>';
			
			$html .= '<td width="6%" style="text-align:right;'.$left_bottom.'"><b>'. (int) $item['BilledQty'].'</b></td>';
			$html .= '<td width="7%" style="text-align:right;'.$left_bottom.'"><b>'.number_format($PackQty, 2, '.', '') .'</b></td>';
			$html .= '<td width="8%" style="text-align:right;'.$left_bottom.';'.$rate_display.'"><b>'.number_format($item['BasicRate'], 2, '.', '').'</b></td>';
			$html .= '<td width="6.4%" style="text-align:right;'.$left_bottom.'"><b>'. strtoupper($item['unit']).'</b></td>';
			$TaxableAmt = $item['BasicRate'] * $item['BilledQty'];
			$TotalSubAmt += $TaxableAmt;
			$html .= '<td width="6%" style="text-align:right;'.$left_bottom.';'.$rate_display.'"><b>0.00</b></td>';
			$TotalDiscOnSale += $item['DiscAmt'];
			$html .= '<td width="13%" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TaxableAmt, 2, '.', '').'</b></td>';
			
			if($client_detail->state == "UP"){
				$gst_rate = $item['cgst'] + $item['sgst'];
				$gst_rate = $gst_rate.".00";
				$TotalSGSTAmt += $item['sgstamt'];
				$TotalCGSTAmt += $item['cgstamt'];
				$TotalDiscOnSale += $item['sgstamt'];
				$TotalDiscOnSale += $item['cgstamt'];
				}else {
				$gst_rate = $item['igst'];
				$TotalIGSTAmt += $item['igstamt'];
				$TotalDiscOnSale += $item['igstamt'];
			}
			$html .= '</tr>';
			
			$i++;
		}
		$amt = (double) $amt;
		
		if(!empty($inv_item)){
			$html .='<tr>';
			$html .='<td colspan="3" style="text-align:center;'.$left_bottom.'"><b>Total</b></td>'; 
			$html .='<td style="text-align:center;'.$left_bottom.'"></td>';
			$html .='<td style="text-align:right;'.$left_bottom.'"><b>'.(int) $TotalUnitQty.'</b></td>';
			$html .='<td style="text-align:right;'.$left_bottom.'"><b>'.number_format($TotalPackQty, 2, '.', '').'</b></td>';
			$html .='<td style="text-align:right;'.$left_bottom.';'.$rate_display.'"><b></b></td>';
			$html .='<td style="'.$left_bottom.'"></td>';
			$html .='<td style="text-align:right;'.$left_bottom.';'.$rate_display.'"><b></b></td>';
			$html .='<td style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TotalSubAmt, 2, '.', '').'</b></td>';
			$html .='</tr>';
		}
		
		//if($total_item_count > 17 && $total_item_count <=33){
        $html .='<tr><td colspan="10" width="100%" height="'.$empty_height.'px" style="'.$left_bottom_right.'"></td></tr>';
        if($total_item_count > 13 && $total_item_count<=33){
			$html .='<tr><td colspan="10" width="100%" height="'.$empty_height1.'px" style="'.$left_bottom_right.'"></td></tr>';
		}
        $html .= '</tbody>';
        
        $html .= '<tfoot style="width:100%;position:fixed !important;bottom:0 !important">';
		
        $html .='<tr>
		<td  width="22%" style="text-align:center;'.$left_bottom.'"><b>GST Breakup</b></td>
		<td width="14%" style="text-align:center;'.$left_bottom.'"><b>Taxable Amt</b></td>
		<td width="8%" style="text-align:center;'.$left_bottom.'"><b>CGST %</b></td>
		<td width="9%" style="text-align:center;'.$left_bottom.'"><b>CGST Amt</b></td>
		<td width="8%" style="text-align:center;'.$left_bottom.'"><b>SGST %</b></td>
		<td width="9%" style="text-align:center;'.$left_bottom.'"><b>SGST Amt</b></td>
		<td width="7%" style="text-align:center;'.$left_bottom.'"><b>IGST %</b></td>
		<td width="9%" style="text-align:center;'.$left_bottom.'"><b>IGST Amt</b></td>
		<td width="9%" style="text-align:center;'.$left_bottom.'"><b>GST Amt</b></td>
		<td width="5%" style="text-align:center;'.$left_bottom_right.'"><b>Item </b></td>
		
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
                $html .='<tr>';
                if($i == 0){
                    $html .='<td style="'.$left_bottom_right.'" rowspan="'.$gst_count.'" width="22%"></td>';
				}
                $gst_per = $gvalue["cgst"] * 2;
                $gst_per = $gst_per;
                $taxable_amt = get_gst_taxable_amt($order_detail["OrderID"],$gvalue["cgst"]);
                $cs_gst_amt = get_gst_amt($order_detail["OrderID"],$gvalue["cgst"]);
                $gst_total_amt = $cs_gst_amt * 2;
                $item_count = get_gst_item_count($order_detail["OrderID"],$gvalue["cgst"]);
                $item_count_new = count($item_count);
                $html .='<td width="14%" style="text-align:center;'.$left_bottom.'"><b>'.number_format($taxable_amt, 2, '.', '').'</b></td>
                <td width="8%" style="text-align:center;'.$left_bottom.'"><b>'.number_format($gvalue["cgst"], 2, '.', '').'</b></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"><b>'.number_format($cs_gst_amt, 2, '.', '').'</b></td>
                <td width="8%" style="text-align:center;'.$left_bottom.'"><b>'.number_format($gvalue["cgst"], 2, '.', '').'</b></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"><b>'.number_format($cs_gst_amt, 2, '.', '').'</b></td>
                <td width="7%" style="text-align:center;'.$left_bottom.'"></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"><b>'. number_format($gst_total_amt, 2, '.', '').'</b></td>
                <td width="5%" style="text-align:center;'.$left_bottom_right.'"><b>'.$item_count_new .'</b></td>
                
                </tr>';
                $bill_gst_total = $bill_gst_total + $gst_total_amt;
				$i++;
			}
			}else {
            $igst_detail = get_igst_details($order_detail["OrderID"]);
            $igst_count = count($igst_detail);
            $i = 0;
            
			foreach ($igst_detail as $igvalue) {
                $html .='<tr>';
                if($i == 0){
                    $html .='<td style="'.$left_bottom.'" rowspan="'.$igst_count.'" width="22%"></td>';
				}
                $igst_per = $igvalue["igst"];
                $igst_per = $igst_per;
                $taxable_amt = get_igst_taxable_amt($order_detail["OrderID"],$igvalue["igst"]);
                $i_gst_amt = get_igst_amt($order_detail["OrderID"],$igvalue["igst"]);
                $i_item_count = get_igst_item_count($order_detail["OrderID"],$igvalue["igst"]);
                $i_item_count_new = count($i_item_count);
                $html .='<td width="14%" style="text-align:center;'.$left_bottom.'"><b>'.number_format($taxable_amt, 2, '.', '').'</b></td>
                <td width="8%" style="text-align:center;'.$left_bottom.'"></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"></td>
                <td width="8%" style="text-align:center;'.$left_bottom.'"></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"></td>
                <td width="7%" style="text-align:center;'.$left_bottom.'"><b>'.$igvalue["igst"].'</b></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"><b>'. number_format($i_gst_amt, 2, '.', '').'</b></td>
                <td width="9%" style="text-align:center;'.$left_bottom.'"><b>'. number_format($i_gst_amt, 2, '.', '').'</b></td>
                <td width="5%" style="text-align:center;'.$left_bottom_right.'"><b>'.$i_item_count_new.'</b></td>
                
                </tr>';
                $bill_gst_total = $bill_gst_total + $i_gst_amt;
				$i++;
			}  
		}
        if($gst_count>1){}else{
            // $html .='<tr><td colspan="12" style="height:'.$gst_brk_after_space_h.'px;"></td></tr>';
		}
        
        
		$html .='<tr>'; 
		$html .='<td  colspan="3" style="border-right:none;'.$left_bottom.'" width="30%"><b>Total Crates : '.number_format(ceil($TotalCR), 2, '.', '').'</b></td>';
		$html .='<td  colspan="3" style="border-right:none;'.$left_bottom.'" width="31%"><b>Total Cases : '.number_format(ceil($TotalCS), 2, '.', '').'</b></td>';
		
		//$html .='<td colspan="6" style="border-right:none;'.$left_bottom.'" width="61%"><b></b></td>';
		$html .='<td colspan="2" width="25%" style="'.$left_bottom.'"><b>Sub Total </b></td>';
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'" width="14%"><b>'. number_format($TotalSubAmt, 2, '.', '').'</b></td>';
		$html .='</tr>'; 
		$irn='';
		if($sales_detail->irn !== null){
			$irn = '<hr><b>IRN: '.$sales_detail->irn.'</b>';
		}
		$html .='<tr>'; 
		if($client_detail->state == "UP"){
		
			$br=$irn;
			}else {
			$br= $irn;
		}
		if($order_detail["FY"] > 24){
			$bank_rowspan='rowspan="8"';
		}else{
			$bank_rowspan='rowspan="9"';
		
		}
		if($PlantID == "1"){
			$BankMsg = '<b>Company’s PAN : AADFG4556A</b><br/><b>Bank A/c Details</b> : <br>A/c Holder Name : <b>GAURI FOODS</b><br>
			Bank Name : <b>Kotak Bank CC A/c 4412621686</b><br>
			A/c No. : <b>4412621686</b><br>
			Branch & IFS Code: <b>Aliganj, Lucknow & KKBK0005190</b>';
			}else if($PlantID == "3"){
			if($FY == "22"){
				$BankMsg = '<b>Bank A/c Details : <br> <br><br></b>';
				}else if($FY >= 23){
				$BankMsg = '<b>Bank A/c Details :</b> <br><br>';
				}else{
				$BankMsg = '<b>Bank A/c Details : <br></b>';
			}
			}else{
			$BankMsg = '';
		}
		
		$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'ltr';
		$lg['a_meta_language'] = 'IN';
		
		$lg['w_page'] = 'page';
		
		$html .='<td colspan="6" '.$bank_rowspan.' style="'.$left_bottom.'"><b>Declaration : <br/></b><b>I/We hereby certify that food/foods mentioned in this invoice
		is/are warranted to be of the nature and qualilty which it /
		these purports/purported to be. Signature of the manufacturer / Distributor / Dealer</b>
		<br/><hr/>
		<b>Narration :</b> <br/>
		<b>'.$invoice->remark.' </b>
		</td>';
		//$html .='<td colspan="8" '.$bank_rowspan.'><b>Note : </b><br><img src="" title="Link to Google.com" style="width:auto;height:90px;"/>'.$br.'</td>';
		
		/*$html .='<td colspan="2" style="'.$left_bottom.'"><b>Taxable Value/ Amt</b></td>';
			$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($taxable_amt_item, 2, '.', '').'</b></td>';
			$html .='</tr>'; 
			
		$html .='<tr>'; */
		if($client_detail->state == "UP"){
			$html .='<td colspan="2" style="'.$left_bottom.'"><b>Add CGST</b></td>';
			$grand_csgst = $gst_total / 2;
			$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TotalCGSTAmt, 2, '.', '').'</b></td>';
			}else {
			$html .='<td colspan="2"><b>Add IGST</b></td>';
			$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TotalIGSTAmt, 2, '.', '').'</b></td>';
		}
		$html .='</tr>'; 
		
		if($client_detail->state == "UP"){
			$html .='<tr>'; 
			$html .='<td colspan="2" style="'.$left_bottom.'"><b>Add SGST</b></td>';
			$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TotalSGSTAmt, 2, '.', '').'</b></td>';
			$html .='</tr>'; 
		}
		$html .='<tr>'; 
		$html .='<td colspan="2" style="'.$left_bottom.'"><b>Discount On sale</b></td>';
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($TotalDiscOnSale, 2, '.', '').'</b></td>';
		$html .='</tr>'; 
		$sale_data = get_is_tcs($order_detail["SalesID"]);
		$tcs_amt = $sale_data->tcsAmt;
		$tcstext = '';
		if($order_detail["FY"] > 24){
			// $html .='<tr>'; 
			// $html .='<td colspan="2" style="'.$left_bottom .'"></td>';
			// $html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"></td>';
			// $html .='</tr>';  
			}else{
			$tcstext = ' + TCS (Rnd)';
			$html .='<tr>'; 
			$html .='<td colspan="2" style="'.$left_bottom .'"><b>Add TCS @ '.round($sale_data->tcs,2).'%</b></td>';
			$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format($tcs_amt, 2, '.', '').'</b></td>';
			$html .='</tr>';
		}
		$html .='<tr>'; 
		$html .='<td colspan="2" style="'.$left_bottom.'"><b>Amount after GST '.$tcstext.'</b></td>';
		$BeforeTCSAmt = ($TotalSubAmt + $TotalCGSTAmt + $TotalSGSTAmt + $TotalIGSTAmt) - $TotalDiscOnSale;
		$AfterTCSAmt = $BeforeTCSAmt + $tcs_amt;
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format(round($AfterTCSAmt), 2, '.', '').'</b></td>';
		$html .='</tr>'; 
		$BalAmt = $netBal + $BeforeTCSAmt + $sale_data->tcsAmt;
		if($netBal > 0){
			$CRDR1 = "Dr";
			}else{
			$CRDR1 = "Cr";
		}
		$html .='<tr>'; 
		$html .='<td colspan="2" style="'.$left_bottom.'"><b>Previous Balance</b></td>';
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format(abs($netBal), 2, '.', '').$CRDR1.'</b></td>';
		//$html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b></b></td>';
		$html .='</tr>';
		$html .='<tr>'; 
		$html .='<td colspan="2" style="'.$left_bottom.'"><b>Balance Amt</b></td>';
		if($BalAmt > 0){
			$CRDR = "Dr";
			}else{
			$CRDR = "Cr";
		}
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format(abs($BalAmt), 2, '.', '').$CRDR.'</b></td>';
		//$html .='<td '.$colspan_taxable_amt.' style="text-align:right;"><b></b></td>';
		$html .='</tr>';
		$html .='<tr>'; 
		$html .='<td colspan="2" style="'.$left_bottom.'"><b>Previous Crates</b></td>';
		$prevcrates = ($crateledger['AllDispatch'] - $crateledger['ChallanDisp']) - ($crateledger['AllReturn'] - $crateledger['ChallanReturn']);
		$balcrate = $prevcrates + $crateledger['ChallanDisp'] - $crateledger['ChallanReturn'];
		if($prevcrates < 0){
			$prevdrcr = "Cr";
			}else{
			$prevdrcr = "Dr";
		}
		if($balcrate < 0){
			$baldrcr = "Cr";
			}else{
			$baldrcr = "Dr";
		}
		
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format(abs($prevcrates), 0, '.', '').$prevdrcr.'</b></td>';
		$html .='</tr>';
		$html .='<tr>'; 
		$html .='<td colspan="2" style="'.$left_bottom.'"><b>Balance Crates</b></td>';
		
		$html .='<td colspan="2" style="text-align:right;'.$left_bottom_right.'"><b>'.number_format(abs($balcrate), 0, '.', '').$baldrcr.'</b></td>';
		$html .='</tr>';
		$html .='<tr>'; 
		//$html2 .='<td></td>';
		$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $InvoiceWord =  $f->format(round($AfterTCSAmt));
		$html .='<td colspan="10" style="border-right:none;'.$left_bottom_right.'" align="left" width="100%"><b>Amount In Words : INR '.ucfirst($InvoiceWord).' Only</b></td>';
		
		// $html .='<td  style="text-align:right;" width="14.3%"><b>'. number_format($taxable_amt_item, 2, '.', '').'</b></td>';
		$html .='</tr>';
		
		$html .='<tr>'; 
		
		$src= 'https://chart.googleapis.com/chart?chs=115x115&cht=qr&chl='.$sales_detail->Qrcode.'&choe=UTF-8';
		if($sales_detail->irn !== null){
			$html .='<td colspan="3" style="'.$left_bottom.'">';
			$html .='<img src="'.$src.'" title="Link to Google.com" />';
			$html .='</td>';
			$html .='<td colspan="3" style="'.$left_bottom.'">'.$BankMsg;
			$html .='</td>';
			}else{
			$html .='<td colspan="5" style="'.$left_bottom.'">'.$BankMsg;
			$html .='</td>';
			$html .='<td colspan="1" style="'.$left_bottom.'">';
			$html .='<img height="100px" src="' . base_url('uploads/QRPNB.jpg') . '" title="Link to Google.com" />';
			$html .='</td>';
		}
		// $html .='</td>';
		
		$html .='<td colspan="4" style="'.$left_bottom_right.'">For<b> '.$PlantDetail->FIRMNAME.'<br><br><br><br><br>Authorized Signatory</b></td>';
		$html .='</tr>';
        $html .= '</tfoot>';
        $html .= '</table>';
		$html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="0">';
		$html .='<tr>';
		$html .='<td colspan="6" style="text-align:left;border-right:1px solid #fff;border-left:1px solid #fff;border-bottom:1px solid #fff;" >'.date('d/m/Y H:i:s').'</td>';
		$html .='<td colspan="6" style="text-align:right;border-right:1px solid #fff;border-bottom:1px solid #fff;">'.$GetLoggedInName->firstname.' '.$GetLoggedInName->lastname.'</td>';
		$html .='</tr>';
		$html .= '</table> ';
		// $html .= '<pagebreak> ';
		$pdf->writeHTML($html, true, false, false, false, '');
		if ($key < count($get_order_list) - 1) {
			$pdf->AddPage();
		}
		// Reset $html for the next iteration
		$html = '';
		
	}
	
	// $pdf->writeHTML($html, true, false, false, false, '');
	
?>