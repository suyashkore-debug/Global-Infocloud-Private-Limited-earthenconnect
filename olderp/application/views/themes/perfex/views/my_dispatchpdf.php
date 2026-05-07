<?php

defined('BASEPATH') or exit('No direct script access allowed');


$dimensions = $pdf->getPageDimensions();

$pdf->SetMargins(5, 15, 5, 0);
$pdf->Ln(0);
$get_order_list = get_order_list($invoice->ChallanID);
$count = 0;
$count_order = count($get_order_list);
$GetLoggedInName = GetLoginFullName();
$PlantDetail = GetPlantDetails($invoice->PlantID,$invoice->FY);
$user_detail = get_staff_detail($invoice->DriverID);
$route_detail = get_route_detail($invoice->RouteID);

$html = '<table style="width: 100%; font-size:12px;font-weight:700;" cellspacing="1" cellpadding="4" border="1">
       <tr>
       <td align="center;" colspan="10">
       <span style="text-align:center;font-size:14px;padding:0px;margin:0px;"><b><u>Route Dispatch</u></b></span> <br>
       <span style="font-size:14px;font-weight:700;"> <b>'.$PlantDetail->FIRMNAME.' (GSTIN '.$PlantDetail->GSTNO.') </b></span><br>
       <span><b>'.$PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2.'</b></span><br>
       <span><b>Contact No. : '.$PlantDetail->PHONENO.' Food Lic '.$PlantDetail->FLNO1.'</b></span>
       
       </td>
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;font-size:16px;" colspan="2"><b>ChallanID</b></td>
        <td style="border-right: 1px solid #333;font-size:16px;" colspan="3"> <b> '. $invoice->ChallanID .'</b></td>
        <td style="border-left: 0px solid #333;font-size:16px;" colspan="2"><b>Vehicle No. </b></td>
        <td style="border-right: 1px solid #333;font-size:16px;" colspan="3"> <b> '.$invoice->VehicleID.'</b></td>
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;font-size:16px;" colspan="2"><b>Date</b></td>
        <td style="border-right: 1px solid #333;font-size:16px;" colspan="3"> <b> '. _d(substr($invoice->Transdate,0,10)).' '.substr($invoice->Transdate,11,8) .'</b></td>
        <td style="border-left: 0px solid #333;font-size:16px;" colspan="2"><b>Route Name. </b></td>
        <td style="border-right: 1px solid #333;font-size:16px;" colspan="3"> <b> '.$route_detail->name.'</b></td>
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;font-size:16px;" colspan="2"><b>Driver Name</b></td>
        <td style="border-right: 1px solid #333;font-size:16px;" colspan="3"> <b> '. $user_detail->firstname .' '.$user_detail->lastname.'</b></td>
        <td style="border-left: 0px solid #333;border-bottom: 1px solid #333;" colspan="5"> </td>
        
       </tr></table>';
       
       
       $empty_height= 200;
       $ordec = count($get_order_list);
       if($ordec >9 && $ordec <=11){
           $width1 = 24;
           $width11 = 3;
           $width111 = 18;
       }else if($ordec > 11){
           $width1 = 24;
           $width11 = 3;
           $width111 = 18;
       }else{
           $width1 = 32;
           $width11 = 5;
           $width111 = 22;
       }
       $html .= '<table style="width: 100%; font-size:12px;font-weight:700;" cellspacing="1" cellpadding="4" border="1">';
       $html .= '<tr>';
       $html .= '<td colspan="4" rowspan="2" width="'.$width1.'%"></td>';
       
        foreach ($get_order_list as $key => $order_detail) {
            //$client_detail = get_client_detail($order_detail["AccountID"]);
            $html .= '<td align="center"><b>'.$order_detail["company"].'</b></td>';
        }
        $html .= '<td align="center"><b></b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        foreach ($get_order_list as $key => $order_detail) {
            //$client_detail = get_client_detail($order_detail["AccountID"]);
            $html .= '<td align="center"><b>'.$order_detail["StationName"].'</b></td>';
        }
        $html .= '<td align="center"><b>Total</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td colspan="4" style="font-size:13px;"><b>Bill Amt</b></td>';
        $AllBillAmt = 0;
        
        foreach ($get_order_list as $key => $order_detail) {
            //$sales_detail = get_sales_details($invoice->ChallanID,$order_detail["OrderID"]);
            $html .= '<td align="center"> <b>'.round($order_detail["BillAmt"],2).'</b></td>';
            $AllBillAmt += round($order_detail["BillAmt"],2);
        }
        $html .= '<td align="center"><b>'.round($AllBillAmt,2).'</b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td colspan="4" style="font-size:13px;"><b>Cash</b></td>';
        
        for ($i=1; $i <=$ordec ; $i++) { 
            $html .= '<td></td>';
        }
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td colspan="4" style="font-size:13px;"><b>Credit</b></td>';
        
        for ($i=1; $i <=$ordec ; $i++) { 
            $html .= '<td></td>';
        }
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td colspan="4" style="font-size:13px;"><b>Fresh Return</b></td>';
        for ($i=1; $i <=$ordec ; $i++) { 
            $html .= '<td></td>';
        }
        $html .= '<td></td>';
        $html .= '</tr>';
        
       $html .= '<tr>';
       $html .= '<td colspan="2" width="'.$width111.'%"><b>Item Name</b></td>';
       $html .= '<td width="'.$width11.'%" align="center"><b>In</b></td>';
       $html .= '<td width="'.$width11.'%" align="center"><b>Pack</b></td>';
       foreach ($get_order_list as $key => $order_detail) {
       $html .= '<td align="center"><b>Billed Qty.</b></td>';
       }
       $html .= '<td align="center"><b>CS/CR</b></td>';
       $html .= '</tr>';
       $AllItemList = array();
       $AllItemListU = array();
       
       foreach ($get_order_list as $key => $order_detail) {
           $inv_item = get_item_by_order_id($order_detail["OrderID"]);
           foreach ($inv_item as $item) {
               if($item['OrderAmt'] == "0.00"){
                   
               }else{
                   if (!in_array($item['ItemID'], $AllItemListU)){
                       array_push($AllItemListU, $item['ItemID']);
                       if(is_null($item["eOrderQty"])){
                            $newqty = $item["OrderQty"] / $item["CaseQty"];
                        }else{ 
                            $newqty = $item["eOrderQty"] / $item["CaseQty"];
                        }
                        $dataArray = array(
                            'description' =>$item['description'],
                            'SuppliedIn' =>$item['SuppliedIn'],
                            'CaseQty' =>$item['CaseQty'],
                            'AccountID' =>$item['AccountID'],
                            'ItemID' =>$item['ItemID'],
                            'OrderID' =>$item['OrderID'],
                        );
                        array_push($AllItemList, $dataArray);
                   }
                    
               }
           }
       }
       $ItemDetailsNew = GetItemDetailsNew($invoice->ChallanID);
       $i = 1;
        /*foreach ($AllItemListU as $ItemID) {
           foreach ($AllItemList as $key1 => $value1) {
                if($ItemID == $value1["ItemID"]){
                   $rowTotal = 0;
                   if($i>1){
                        $empty_height = $empty_height - 35;
                    }
                    
                    $html .= '<tr>';
                    $html .= '<td class="description" align="left;" colspan="2"><b>'.$value1['description'].'</b></td>';
                    $html .= '<td class="description" align="center;"><b>'.$value1['SuppliedIn'].'</b></td>';
                    $html .= '<td class="description" align="center;"><b>'. (int) $value1['CaseQty'].'</b></td>';
                    foreach ($get_order_list as $key => $order_detail) {
                        $Qty = '';
                        foreach ($ItemDetailsNew as $key2 => $value2) {
                            if($order_detail['OrderID'] == $value2["OrderID"] && $value1['ItemID'] == $value2["ItemID"]){
                                $Qty = (int) $value2["caseqty"];
                                $rowTotal += $value2["caseqty"];
                            }
                        }
                        $html .= '<td align="center;"><b>'.$Qty.'</b></td>';
                    }
                    
                    $html .= '<td align="center;"><b>'.$rowTotal.'</b></td>';
                    $html .= '</tr>';
                    
                }
           }
        }*/
       foreach ($AllItemList as $key1 => $value1) {
           $rowTotal = 0;
           if($i>1){
                $empty_height = $empty_height - 35;
            }
            $html .= '<tr>';
            $html .= '<td class="description" align="left;" colspan="2"><b>'.$value1['description'].'</b></td>';
            $html .= '<td class="description" align="center;"><b>'.$value1['SuppliedIn'].'</b></td>';
            $html .= '<td class="description" align="center;"><b>'. (int) $value1['CaseQty'].'</b></td>';
            
            foreach ($get_order_list as $key => $order_detail) {
                $Qty = '';
                foreach ($ItemDetailsNew as $key2 => $value2) {
                    if($order_detail['OrderID'] == $value2["OrderID"] && $value1['ItemID'] == $value2["ItemID"]){
                        $Qty = (int) $value2["caseqty"];
                        $rowTotal += $value2["caseqty"];
                    }
                }
                
                $html .= '<td align="center;"><b>'.$Qty.'</b></td>';
            }
            $html .= '<td align="center;"><b>'.$rowTotal.'</b></td>';
            $html .= '</tr>';
       }
       
       
       $html .= '<tr>
       <td colspan="2" align="left;"><b>Total Qty</b></td>
       <td></td>
       <td></td>';
       $SumQty = 0;
       foreach ($get_order_list as $key => $order_detail) {
           $AllQty = $order_detail["Cases"] + $order_detail["Crates"];
           $html .= '<td align="center"><b>'.$AllQty.'</b></td>';
           $SumQty += $AllQty;
       }
       
       $html .= '<td align="center"><b>'.$SumQty.'</b></td>
       </tr>';
       
       
       $html .= '<tr>
       <td colspan="2" align="left"><b>Total Cases</b></td>
       <td align="center"><b>CS</b></td>
       <td></td>';
       $AllCases = 0;
       foreach ($get_order_list as $key => $order_detail) {
            $html .= '<td align="center"><b>'.$order_detail["Cases"].'</b></td>';
            $AllCases += $order_detail["Cases"];
       }
       $html .= '<td align="center"><b>'.$AllCases.'</b></td>
       </tr>';
       
       $html .= '<tr>
       <td colspan="2" align="left"><b>Total Crates</b></td>
       <td align="center"><b>CR</b></td>
       <td></td>';
       $AllCrates = 0;
       foreach ($get_order_list as $key => $order_detail) {
        $html .= '<td align="center"><b>'.$order_detail["Crates"].'</b></td>';
        $AllCrates += $order_detail["Crates"];
       }
       $html .= '<td align="center"><b>'.$AllCrates.'</b></td>
       </tr> </table>';
        
        $html .= '<table style="width: 100%; font-size:12px;font-weight:700;" cellspacing="1" cellpadding="4" border="1">';
       $html .= '<tr>
       <td align="left" width="11%"><b>Sales ID</b></td>
       <td align="left" width="15%"><b>GSTIN</b></td>
       <td align="left" colspan="2" width="29%"><b>AccountName</b></td>
       <td align="center" width="8%"><b>hsncode</b></td>
       <td align="center" width="9%"><b>TaxableAmt</b></td>
       <td align="center" width="7%"><b>Qty</b></td>
       <td align="center" width="7%"><b>CGST%</b></td>
       <td align="center" width="7%"><b>SGST%</b></td>
       <td align="center" width="7%"><b>IGST%</b></td>
       </tr>';
       
       $sum_taxable_amt = 0.00;
        foreach ($get_order_list as $key => $order_detail) {
            $empty_height = $empty_height - 35;
           $hsn_list = get_hsn_list($order_detail["OrderID"]);
           //$client_detail = get_client_detail($order_detail["AccountID"]);
           
        foreach ($hsn_list as $hsn) {
           if($order_detail["state"] == "UP"){
                $gst_detail_new = get_gst_details_new($order_detail["OrderID"],$hsn["hsn_code"]);
            }else{
                $gst_detail_new = get_igst_details_new($order_detail["OrderID"],$hsn["hsn_code"]);
            }
        foreach ($gst_detail_new as $gvalue) {
            if($order_detail["state"] == "UP"){
                $taxable_amt = get_gst_taxable_amt_new($order_detail["OrderID"],$gvalue["cgst"],$hsn["hsn_code"]);
                $gst_per = $gvalue["cgst"];
                $igst_per = "0.00";
                $item_qty_sum_for_igst_new = get_gst_item_qty_sum_new($order_detail["OrderID"],$gvalue["cgst"],$hsn["hsn_code"]);
            }else{
                $taxable_amt = get_igst_taxable_amt_new($order_detail["OrderID"],$gvalue["igst"],$hsn["hsn_code"]);
                $igst_per = $gvalue["igst"];
                $gst_per = "0.00"; 
                $item_qty_sum_for_igst_new = get_igst_item_qty_sum_new($order_detail["OrderID"],$gvalue["igst"],$hsn["hsn_code"]);
            }
            
            $sum_taxable_amt = $sum_taxable_amt + $taxable_amt;
            if($taxable_amt == "0.00"){
                
            }else{
                $html .='<tr>';
                $html .='<td><b>'.$order_detail["SalesID"].'</b></td>';
                $html .='<td><b>'.$order_detail["vat"].'</b></td>';
                $html .='<td colspan="2"><b>'.$order_detail["company"].'</b></td>';
                $html .='<td><b>'.$hsn["hsn_code"].'</b></td>';
                $html .='<td align="right"><b>'.$taxable_amt.'</b></td>';
                $html .='<td align="right"><b>'.number_format($item_qty_sum_for_igst_new,2).'</b></td>';
                $html .='<td align="right"><b>'.$gst_per.'</b></td>';
                $html .='<td align="right"><b>'.$gst_per.'</b></td>';
                $html .='<td align="right"><b>'.$igst_per.'</b></td>';
                $html .='</tr>'; 
            }
        }
        }
        }
        
        $html .= '<tr>
        <td><b>GSTType</b></td>
        <td align="right"><b>Amount</b></td>
        </tr>';
        $html .= '<tr>
        <td><b>TaxableAmt</b></td>
        <td align="right"><b>'.$sum_taxable_amt.'</b></td>
        </tr>';
        $html .= '<tr>
        <td><b>Bill Amt</b></td>
        <td align="right"><b>'.round($AllBillAmt,2).'</b></td>
        </tr>';
       
       
       $html .= '<tr>
        <td align="center" width="5%"><b>Sr</b></td>
        <td width="39.5%"><b>AccountName</b></td>
        <td width="12%"><b>Order ID</b></td>
        <td width="16%"><b>Order Date</b></td>
        <td width="12%"><b>Sales ID</b></td>
        <td width="16%"><b>Sales Date</b></td>
        </tr>';
        $sr = 1;
        foreach ($get_order_list as $key => $order_detail) {
            $empty_height = $empty_height - 35;
            //$client_detail = get_client_detail($order_detail["AccountID"]);
            //$sales_detail = get_sales_details($invoice->ChallanID,$order_detail["OrderID"]);
            $html .= '<tr>
            <td align="center"><b>'.$sr.'</b></td>
            <td><b>'.$order_detail["company"].'</b></td>
            <td><b>'.$order_detail["OrderID"].'</b></td>
            <td><b>'._d(substr($order_detail["Transdate"],0,10)).' '.substr($order_detail["Transdate"],11,8).'</b></td>
            <td><b>'.$order_detail["SalesID"].'</b></td>
            <td><b>'._d(substr($order_detail["Saledate"],0,10)).' '.substr($order_detail["Saledate"],11,8).'</b></td>
            </tr>';
            $sr++;
        }
        $html .= '<tr><td colspan="10" width="100%" style="height:'.$empty_height.'px;"></td></tr>';
    $html .= '</table>';
    $html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="0">';
    $html .='<tr>';
    $html .='<td colspan="6" style="text-align:left;border-right:1px solid #fff;border-left:1px solid #fff;border-bottom:1px solid #fff;" >'.date('d/m/Y H:i:s').'</td>';
    $html .='<td colspan="6" style="text-align:right;border-right:1px solid #fff;border-bottom:1px solid #fff;">'.$GetLoggedInName->firstname.' '.$GetLoggedInName->lastname.'</td>';
    $html .='</tr>';
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, false, false, '');

?>