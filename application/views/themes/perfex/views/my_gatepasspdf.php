<?php

defined('BASEPATH') or exit('No direct script access allowed');


$dimensions = $pdf->getPageDimensions();

$pdf->SetMargins(3, 0, 3, 0);
$pdf->Ln(0);
$getItemList = GetItemDetailsFrGatepass($invoice->ChallanID);
$get_order_list = get_order_list($invoice->ChallanID);
$route_detail = get_route_detail($invoice->RouteID);
$PlantDetail = GetPlantDetails($invoice->PlantID,$invoice->FY);
$user_detail = get_staff_detail($invoice->DriverID);
$user_detail2 = get_staff_detail($invoice->SalesmanID);
$GetLoggedInName = GetLoginFullName();
$sales_detail = GetChallanCratesCases($invoice->ChallanID);
$TotalCS = $sales_detail->TotalCases; 
$TotalCR = $sales_detail->TotalCrates;
$count = 0;
$count_order = count($get_order_list);
// print_r($get_order_list);

$html = '<div>
       <br>
       <table style="width: 100%; font-size:12px;font-weight:700;" cellspacing="1" cellpadding="4" border="1">
       <tr>
       <td colspan="10" style="text-align:center;">
       <span style="text-align:center;font-size:12px;padding:0px;margin:0px;"><b><u>Gate Pass</u> </b></span><br>
       <span style="font-size:14px;font-weight:700;"> <b>'.$PlantDetail->FIRMNAME.'  </b></span><br>
       <span><b>'.$PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2.'</b></span><br>
       <span><b>(GSTIN '.$PlantDetail->GSTNO.') Contact No. : '.$PlantDetail->PHONENO.'</b></span>
       </td>
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;" colspan="2"><b>Challan Id</b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '. $invoice->ChallanID .'</b></td>
        
        <td style="border-left: 0px solid #333;" colspan="2"><b>Route Name. </b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '.$route_detail->name.'</b></td>
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;" colspan="2"><b>Date</b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '. _d(substr($invoice->Transdate,0,10)) .'</b></td>
        <td style="border-left: 0px solid #333;" colspan="2"><b>Vehicle No. </b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '.$invoice->VehicleID.'</b></td>
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;" colspan="2"><b>Sales Man</b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '. $user_detail2->firstname .' '.$user_detail2->lastname.'</b></td>
        <td style="border-left: 0px solid #333;" colspan="2"><b>Driver Name</b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '. $user_detail->firstname .' '.$user_detail->lastname.'</b></td>
        
       </tr>
       <tr>
        <td style="border-left: 0px solid #333;" colspan="2"><b>Station</b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b>: '. $get_order_list[0]['StationName'].'</b></td>
        <td style="border-left: 0px solid #333;" colspan="2"><b></b></td>
        <td style="border-right: 1px solid #333;" colspan="3"> <b> </b></td>
        
       </tr>
       <tr><td colspan="10"></td></tr>
       <tr>
       <td align="center;"><b>Sr.No.</b></td>
       <td align="center;"><b>Nick Name</b></td>
       <td colspan="4"><b>Item Name</b></td>
       <td align="center;"><b>Packing</b></td>
       <td align="center;"><b>Supplied In</b></td>
       <td align="center;"><b>Pkt Qty</b></td>
       <td align="right;"><b>Cases/Crate</b></td>
       </tr>';
    
    $empty_height= 750;   
    $TotalCrates = 0;
    $TotalCases = 0;
    $Totalunitqty = 0;
    $sr = 1;
    foreach ($getItemList as $key => $ItemDetails) {
        // if((int) $ItemDetails['Cases'] > 0){
            if($sr>1){
                $empty_height = $empty_height - 25;
            }
            if($ItemDetails['SuppliedIn'] == "CR"){
                $TotalCrates += round($ItemDetails['Cases']);
            }else{
                $TotalCases += round($ItemDetails['Cases']);
            }
                $Totalunitqty += round($ItemDetails['qty']);
            $html .= '<tr>'; 
            $html .= '<td style="text-align:center;"><b>'.$sr.'</b></td>'; 
            $html .= '<td class="description" align="center;"><b>'.$ItemDetails['ItemID'].'</b></td>';
            $html .= '<td class="description" align="left;" colspan="4"><b>'.$ItemDetails['description'].'</b></td>';
            $html .= '<td class="description" align="center;"><b>'. (int) $ItemDetails['CaseQty'].'</b></td>';
             $html .= '<td class="description" align="center;"><b>'.$ItemDetails['SuppliedIn'].'</b></td>';
            $html .= '<td class="description" align="right;"><b>'. (int) round($ItemDetails['qty']) .'</b></td>';
            $html .= '<td class="description" align="right;"><b>'. (int) round($ItemDetails['Cases']) .'</b></td>';
            $html .= '</tr>';
            $sr++;
        // }
        
    }
    

    $html .= '<tr>
       <td colspan="8"><b>Total Pkts</b></td>
       <td align="right;"><b>'.$Totalunitqty.'</b></td>
       <td align="right;"></td>
        </tr>';
    $html .= '<tr>
       <td colspan="9"><b>Total Crates</b></td>
       <td align="right;"><b>'.$TotalCR.'</b></td>
        </tr>';
        $html .= '<tr>
       <td colspan="9"><b>Total Cases / Gatta</b></td>
       <td align="right;"><b>'.$TotalCS.'</b></td>
        </tr>';
       //$html .= '<tr><td colspan="10" style="height:'.$empty_height.'px;"></td></tr>';
       $html .= '</table>';
       $html .= '<table style="width: 100%; font-size:12px;font-weight:400;" cellspacing="1" cellpadding="3" border="0">';
    $html .='<tr>';
    $html .='<td colspan="6" style="text-align:left;border-right:1px solid #fff;border-left:1px solid #fff;border-bottom:1px solid #fff;" >'.date('d/m/Y H:i:s').'</td>';
    $html .='<td colspan="6" style="text-align:right;border-right:1px solid #fff;border-bottom:1px solid #fff;">'.$GetLoggedInName->firstname.' '.$GetLoggedInName->lastname.'</td>';
    $html .='</tr>';
    $html .= '</table>';
    $html .= '</div>';
       $pdf->writeHTML($html, true, false, false, false, '');
?>