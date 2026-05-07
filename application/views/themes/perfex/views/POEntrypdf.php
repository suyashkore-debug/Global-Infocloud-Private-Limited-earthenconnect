<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	
	$dimensions = $pdf->getPageDimensions();
	
	$pdf->SetMargins(5, 7, 5, 0);
	//$pdf->Ln(0);
	// var_dump($invoice);
	$order_detail = $invoice;
	$count = 0;
	$count_order = count($order_detail);
	$html = '';
    $client_detail = get_client_detail($order_detail["AccountID"]);
	// var_dump($order_detail);
	// die;
    $FY = $order_detail["FY"];
    $PlantDetail = GetPlantDetails($order_detail["PlantID"], $order_detail["FY"]);
    
    
    $qty = 0;
    $amt = 0;
    $dis_amt = 0;
    $taxable_amt_item = 0;
    $order_total = 0;
	
    $title = "";
    // if ($order_detail["OrderType"] == "TaxItems") {
	$title = "Purchase Order";
    // }
    // if ($order_detail["OrderType"] == "NonTaxItems") {
    //     $title = "BILL OF SUPPLY";
    // }
    $pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 1));
    //$html .= '<div class="page-break-after: always;">';
	$html .= '<p style="text-align:center;padding-bottom:0px;margin-bottom:0px;"><b>PURCHASE INVOICE</b></p>';
    $html .= '<table style="width: 100%; font-size:12px;font-weight:400; padding-top:0px;margin-top:0px;" cellspacing="1" cellpadding="3" border="1" >';
	
    $html .= '<thead>';
    $logo = "https://goodmorning.globalinfocloud.in/uploads/company/5fda92399dd9e76bcb4437e6720d8868.png";
    /*$html .= '<tr>
		<th colspan="4" style="border: 1px solid #333;text-align:center;font-size:14px;">'.$title.'</th>
	</tr>';*/
    $html .= '<tr >
	<th align="center" style="border-left: 1px solid #333;text-align:center;" width="10%"><p></p><img style="display:block;margin:0 auto;" src="' . $logo . '" title="logo" /></th>
	<th colspan="2" style="border-right: 1px solid black;" width="40%"><p style="text-align:center;font-size:10px;"><span>Invoice To</span><br/><b style="font-size:16px;">' . $PlantDetail->FIRMNAME . '</b><br>' . $PlantDetail->ADDRESS1 . ' ' . $PlantDetail->ADDRESS2 . '-'.$PlantDetail->ADDRESS3.'<br>PAN/IEC : ' . $PlantDetail->FLNO1 . '<br>GSTIN : ' . $PlantDetail->GSTNO . ', State Name : Uttar Pradesh, State Code : 09<br> E-Mail : accounts@xyz.com</p></th>
	<th style="border-bottom: 1px solid #333;border-top: 1px solid #333;border-left: 1px solid #333; height:100px;" width="24%"><b>Entry No : </b><b> ' . $order_detail["PurchID"] . '</b><br><br><b>PO Number : </b><b> ' . $order_detail["PO_Number"] . '</b></th>
	
	
	<th width="26%" style="border-bottom: 1px solid #333;border-top: 1px solid #333;border-left: 1px solid #333;height:100px;"><b> Date : </b> <b>'._d(substr($order_detail["Transdate"], 0, 10)).'</b><br><br><b>Invoice Number : </b><b> ' . $order_detail["Invoiceno"] . '</b></th>
	</tr>';
	$html .= '<tr >
	<th colspan="2" style="border-right: 1px solid black;border-top: 1px solid black;word-wrap: break-word;" width="50%"><p style="text-align:left;font-size:12px;"><span>Supplier (Bill From)</span><br/><b style="font-size:16px;">' . $order_detail["company"] . '</b><br>' . $client_detail->address .'<br>GSTIN : ' . $client_detail->vat . ', State : '.$client_detail->state.'</p></th>
	
	<th colspan="2" style="border-right: 1px solid black;border-top: 1px solid black;word-wrap: break-word;" width="50%"><p style="text-align:left;font-size:12px;"><span>Consignee (Ship to)</span><br/><b style="font-size:16px;">' . $PlantDetail->FIRMNAME . '</b><br>' . $PlantDetail->ADDRESS1 . ' ' . $PlantDetail->ADDRESS2 . '-'.$PlantDetail->ADDRESS3.'<br>PAN/IEC : ' . $PlantDetail->FLNO1 . '<br>GSTIN : ' . $PlantDetail->GSTNO . ', State Name : Uttar Pradesh, State Code : 09<br> E-Mail : accounts@xyz.com</p></th>
	</tr>';
    // $html .= '<tr >
	// <th colspan="2" style="border-right: 1px solid black;border-top: 1px solid black;word-wrap: break-word;" width="50%"><p style="text-align:left;font-size:12px;"><span>Consignee (Ship to)</span><br/><b style="font-size:16px;">' . $PlantDetail->FIRMNAME . '</b><br>' . $PlantDetail->ADDRESS1 . ' ' . $PlantDetail->ADDRESS2 . '-'.$PlantDetail->ADDRESS3.'<br>PAN/IEC : ' . $PlantDetail->FLNO1 . '<br>GSTIN : ' . $PlantDetail->GSTNO . ', State Name : Uttar Pradesh, State Code : 09<br> E-Mail : accounts@xyz.com</p></th>
	
	
	// </tr>';
    
    
    
    
    /*$html .= '<tr>
		<th colspan="4" style="border: 1px solid #333;font-size:14px;height:40px;"><b>Terms of payment : </b></th>
	</tr>';*/
    
    
	
    
	
    
    $rowspan = 'rowspan=""';
    $item_name_width = "28%";
    $hsn_width = "13%";
    $html .= '<tr style="white-space: nowrap !important;">
	<th width="5%" style="text-align:center;"><b>Sr.No.</b></th>
	<th width="33%"><b>Name of Product</b></th>
	<th width="10%" ><b>Quantity</b></th>
	<th  width="10%"><b>Unit</b></th>
	
	<th width="7%"  style="text-align:center;"><b>Rate</b></th>
	<th width="11%" style="text-align:center;"><b>Amount</b></th>
	<th width="10%"  style="text-align:center;"><b>Gst</b></th>
	<th width="14%"  style="text-align:center;"><b>Total Amt.</b></th>';
	
    $html .= '</tr>';
	
	
    $html .= '</thead>';
    $html .= '<tbody>';
	
	
    $inv_item = $invoice['details'];
	// var_dump($inv_item);
    $i = 1;
    $total_item_count = count($inv_item);
	
    if ($total_item_count <= 9) {
        $empty_height = 168;
	}
	
    if ($total_item_count > 9 && $total_item_count <= 33) {
        $empty_height = 564;
        $empty_height1 = 252;
	}
    if ($total_item_count > 33) {
        $empty_height = 208;
	}
	
    $qty = 0;
    $units = 0;
    $amt = 0;
    $dis_amt = 0;
    $taxable_amt_item = 0;
    $order_total = 0;
    $NetAmt = 0;
    foreach ($inv_item as $item) {
        $hsn_code = get_hsn_byitem_id($item['ItemID']);
        if ($total_item_count <= 9) {
            $empty_height = $empty_height - 23;
		}
        if ($total_item_count > 9 && $total_item_count <= 33) {
            $empty_height = $empty_height - 22;
		}
        if ($total_item_count > 9 && $total_item_count <= 33 && $i > 33) {
            $empty_height1 = $empty_height1 - 22;
		}
        if ($total_item_count > 33 && $i > 33) {
            $empty_height = $empty_height - 22;
		}
		$amount =$item['PurchRate']*$item['BilledQty'];
		$order_total += $amount;
		$NetAmt += $item['NetChallanAmt'];
        $html .= '<tr>';
        $html .= '<td width="5%" style="text-align:center;">' . $i . '</td>';
        $html .= '<td width="33%" class="description" align="left;"><b>' . $item['description'] . '</b></td>';
        $html .= '<td width="10%" style="text-align:right;" colspan="2"><b>' . $item['BilledQty'] . '</b></td>';
        $html .= '<td width="10%" style="text-align:right;"><b>' . $item['unit'] . '</b></td>';
		$html .= '<td width="7%" style="text-align:right;"><b>' . $item['PurchRate'] . '</b></td>';
        $html .= '<td width="11%" style="text-align:right;"><b>' . number_format($amount, 2, '.', '') . '</b></td>';
        $html .= '<td width="10%" style="text-align:right;"><b>' . $item['gst'] . '%</b></td>';
        $html .= '<td width="14%" style="text-align:right;"><b>' . $item['NetChallanAmt'] . '</b></td>';
        
        $html .= '</tr>';
		
        $i++;
	}
    if (!empty($inv_item)) {
        $html .= '<tr>';
        $html .= '<td colspan="6" style="text-align:center;"><b>Total</b></td>';
        $html .= '<td style="text-align:right;"><b>' . number_format($order_total, 2, '.', '') . '</b></td>';
        $html .= '<td style="text-align:center;" ></td>';
        $html .= '<td style="text-align:right;"><b>' . number_format($NetAmt, 2, '.', '') . '</b></td>';
        $html .= '</tr>';
	}
	$html .= '<tr style="border:none !important; ">';
	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
	$InvoiceWord =  $f->format($NetAmt);
    $html .= '<td style="border-right:none;" width="101%"><span style="font-size:10px;">Amount Chargeable (in words) </span><br><b> INR '.ucfirst($InvoiceWord).' Only</b></td>';
	$html .= '</tr>';
	
	
    //if($total_item_count > 17 && $total_item_count <=33){
    $html .= '<tr><td colspan="12" width="100%" height="' . $empty_height . 'px"></td></tr>';
    if ($total_item_count > 13 && $total_item_count <= 33) {
        $html .= '<tr><td colspan="12" height="' . $empty_height1 . 'px"></td></tr>';
	}
	
	
    $html .= '</tbody>';
	
    $html .= '<tfoot style="width:100%;position:fixed !important;bottom:0 !important; white-space: nowrap;">';
	
    $html .= '<tr>';
    $html .= '<td colspan="6"><b>Company\'s PAN : ' . $PlantDetail->FLNO1 . '</b></td>';
    $html .= '<td style="text-align:right;" colspan="7">For<b> ' . $PlantDetail->FIRMNAME . '<br><br><br></b>Authorized Signatory</td>';
    $html .= '</tr>';
	
    
	
    $html .= '</tfoot>';
    $html .= '</table>';
    //$html .= '</div>';
    //$pdf->AddPage();
	
	
	$pdf->writeHTML($html, true, false, false, false, '');
?>