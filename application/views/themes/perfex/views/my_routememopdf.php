<?php
defined('BASEPATH') or exit('No direct script access allowed');

$pdf = new TCPDF('L', 'mm', 'A4'); // Landscape A4
$pdf->SetMargins(5, 7, 5, 0);
$pdf->AddPage();

$get_order_list = get_order_list($invoice->ChallanID);
$FY = $invoice->FY;
$PlantID = $invoice->PlantID;
$PlantDetail = GetPlantDetails($PlantID, $FY);
$challan = getNew($invoice->ChallanID);

$Order_item = $OrderIds = $AccountIds = $Accountdetail = $Order_itemdetail = [];
$Crate_detail = $Case_detail = $Crate_total = $Case_total = $Qty_total = [];

// Initialize arrays to store totals per account
$accountCrateTotals = [];
$accountCaseTotals = [];

    foreach($get_order_list as $OKey=>$Oval){
    // Accumulate total crates per account for CR Send
        if (!isset($accountCrateTotals[$Oval["AccountID"]])) {
            $accountCrateTotals[$Oval["AccountID"]] = 0;
        }
        $accountCrateTotals[$Oval["AccountID"]] += $Oval["Crates"];
        
        // Accumulate total cases per account
        if (!isset($accountCaseTotals[$Oval["AccountID"]])) {
            $accountCaseTotals[$Oval["AccountID"]] = 0;
        }
        $accountCaseTotals[$Oval["AccountID"]] += $Oval["Cases"];
    }
foreach ($challan as $code) {
    $new_arr = ['ItemID' => $code["ItemID"], 'description' => $code["description"]];
    $arr_company = ['AccountID' => $code["AccountID"], 'company' => $code["company"]];
    $arr_Pkt = ['AccountID' => $code["AccountID"], 'ItemID' => $code["ItemID"], 'Qty' => $code["BilledQty"]];

    // Calculate crate quantities
    if ($code['local_supply_in'] == 'CR') {
        $crateQty = ($code["BilledQty"] / $code["CaseQty"]);
        $arr_crate = ['AccountID' => $code["AccountID"], 'ItemID' => $code["ItemID"], 'Crates' => $crateQty];
        $Crate_total[] = $arr_crate;
        
        
    }

    // Calculate case quantities  
    if ($code['local_supply_in'] == 'CS') {
        $caseQty = ($code["BilledQty"] / $code["CaseQty"]);
        $arr_cases = ['AccountID' => $code["AccountID"], 'ItemID' => $code["ItemID"], 'Cases' => $caseQty];
        $Case_total[] = $arr_cases;
        
        
    }

    $Qty_total[] = $arr_Pkt;
    $Order_itemdetail[] = $new_arr;
    $Order_item[] = $code["ItemID"];
    $OrderIds[] = $code["OrderID"];
    $AccountIds[] = $code["AccountID"];
    $Accountdetail[] = $arr_company;
}

$Order_item = array_unique($Order_item);
$OrderIds = array_unique($OrderIds);
$AccountIds = array_unique($AccountIds);

// Remove duplicate items
$Itemdetails = array_column($Order_itemdetail, 'ItemID');
$uniqueItems = array_unique($Itemdetails);
$Order_itemdetail = array_values(array_filter($Order_itemdetail, function($i) use (&$uniqueItems){
    if (in_array($i['ItemID'], $uniqueItems)) {
        $uniqueItems = array_diff($uniqueItems, [$i['ItemID']]);
        return true;
    }
    return false;
}));

// Remove duplicate accounts
$accountIds = array_column($Accountdetail, 'AccountID');
$uniqueAcc = array_unique($accountIds);
$Accountdetail = array_values(array_filter($Accountdetail, function($i) use (&$uniqueAcc){
    if (in_array($i['AccountID'], $uniqueAcc)) {
        $uniqueAcc = array_diff($uniqueAcc, [$i['AccountID']]);
        return true;
    }
    return false;
}));

$fg_codes = $Order_item;
$total_fg = count($fg_codes);
$fixed_cols = 3;
$max_fg_fixed = 25;

// Always show 25 columns minimum; expand if more than 25
if ($total_fg < $max_fg_fixed) {
    $fg_to_print = $max_fg_fixed;
    $extra_fg = $max_fg_fixed - $total_fg;
} else {
    $fg_to_print = $total_fg;
    $extra_fg = 0;
}

$actual_fg = $total_fg;
$total_columns = $fg_to_print + $fixed_cols;
$page_width_mm = $pdf->getPageWidth();
$usable_width_mm = $page_width_mm - 5 - 5;
$party_width_pct = 16;
$party_width_mm = $usable_width_mm * ($party_width_pct / 100);
$remaining_mm = $usable_width_mm - $party_width_mm;
$col_width = $remaining_mm / $total_columns;

$title = "ROUTE MEMO";
$driver = (!empty($invoice->driver_fn) ? $invoice->driver_fn . " " . $invoice->driver_ln : '');

// Cell helpers
$cell = function($content = '', $style = '') use ($col_width) {
    $border = 'border:1px solid #000;';
    $s = $style ? $border . $style : $border;
    return '<td width="'.$col_width.'mm" style="'.$s.'">'.$content.'</td>';
};
$party_cell = function($content = '', $style = '') use ($party_width_pct) {
    $border = 'border:1px solid #000;';
    $s = $style ? $border . $style : $border;
    return '<td width="'.$party_width_pct.'%" style="'.$s.'">'.$content.'</td>';
};

function renderPage(&$pdf, $header_html, $fg_to_print, $actual_fg, $fg_codes, $extra_fg, $Order_itemdetail, $col_width, $Accountdetail_chunk, $Crate_total, $Case_total, $Qty_total, $accountCrateTotals, $accountCaseTotals, $cell, $party_cell) {
    $html = $header_html . '<tbody>';

    foreach ($Accountdetail_chunk as $acc) {
        $acc_id = $acc['AccountID'];
        $company = substr($acc['company'], 0, 21);

        // Get totals for this account
        $crateSendTotal = isset($accountCrateTotals[$acc_id]) ? number_format($accountCrateTotals[$acc_id], 0) : '';
        $caseSendTotal = isset($accountCaseTotals[$acc_id]) ? number_format($accountCaseTotals[$acc_id], 0) : '';

        // Party Name
        $html .= '<tr style="font-size:8px;">'
            . $party_cell('<b> ' . htmlspecialchars($company) . '</b>')
            . str_repeat($cell('','height:20px;'), $fg_to_print + 3)
            . '</tr>';

		$square_style = 'text-align:center;vertical-align:middle;font-size:8px;height:20px;line-height:20px;border:1px solid #000;';
		 
        // Crates Qty
        $html .= '<tr style="font-size:8px;">'.$party_cell('<b> Crates Qty</b>');
        foreach ($fg_codes as $itemid) {
            $val = '';
            foreach ($Crate_total as $crate) {
                if ($crate['ItemID']==$itemid && $crate['AccountID']==$acc_id) { 
                    $val = number_format($crate['Crates'],0); 
                    break; 
                }
            }
            $html .= $cell($val, $square_style);
        }
        for ($i=0; $i<$extra_fg; $i++) $html .= $cell('', $square_style); // fill blanks
        // CR Send, CR Rcvd, Sign - Show total for CR Send
        $html .= $cell($crateSendTotal,$square_style);
        $html .= $cell('', $square_style);      // CR Rcvd
		$html .= $cell('', $square_style);     // Sign
        $html .= '</tr>';

        // Case Qty
        $html .= '<tr style="font-size:8px;">'.$party_cell('<b> Case Qty</b>');
        foreach ($fg_codes as $itemid) {
            $val = '';
            foreach ($Case_total as $case) {
                if ($case['ItemID']==$itemid && $case['AccountID']==$acc_id) { 
                    $val = number_format($case['Cases'],0); 
                    break; 
                }
            }
            $html .= $cell($val, $square_style);
        }
        for ($i=0; $i<$extra_fg; $i++) $html .= $cell('', $square_style);
        // CR Send, CR Rcvd, Sign - Show total for Case Send
        $html .= $cell($caseSendTotal,$square_style);
        $html .= $cell('', $square_style);// CR Rcvd
		$html .= $cell('', $square_style);// Sign
        $html .= '</tr>';

        // Pkt Qty
        $html .= '<tr style="font-size:8px; height:22px;">'.$party_cell('<b> Pkt Qty</b>');
        foreach ($fg_codes as $itemid) {
            $val = '';
            foreach ($Qty_total as $qty) {
                if ($qty['ItemID']==$itemid && $qty['AccountID']==$acc_id) { 
                    $val = (int)$qty['Qty']; 
                    break; 
                }
            }
			$html .= $cell($val, $square_style); 
        }
        for ($i=0; $i<$extra_fg; $i++) $html .= $cell();
        $html .= $cell().$cell().$cell(); // Empty for Pkt Qty in fixed columns
        $html .= '</tr>';

        // Fresh Return
        $html .= '<tr style="font-size:8px;">'.$party_cell('<b> Fresh Return</b>')
            .str_repeat($cell('',$square_style), $fg_to_print + 3).'</tr>';

        // Damage Return
        $html .= '<tr style="font-size:8px;">'.$party_cell('<b> Damage Return</b>')
            .str_repeat($cell('',$square_style), $fg_to_print + 3).'</tr>';
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html, true, false, false, false, '');
}

function buildHeaderAndCoords($pdf, $total_columns, $party_width_pct, $invoice, $PlantDetail, $driver, $fg_to_print, $fg_codes, $extra_fg, $Order_itemdetail, $col_width, &$arr_nomes, &$arr_nomes1, $title) {
    $html = '<table style="width:100%;font-size:10px;border-collapse:collapse;" border="0" cellpadding="0" cellspacing="0"><thead>';

    // Title
    $html .= '<tr><th colspan="'.($total_columns+1).'" style="border:1px solid #000;padding:0;line-height:1.2;">
        <div style="margin:0;padding:1.5mm 0;text-align:center;font-size:10px;line-height:1.3;">
            <b>'.htmlspecialchars($title).'</b><br>
            <b>'.htmlspecialchars($PlantDetail->FIRMNAME).'</b><br>
            <b>'.htmlspecialchars($PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2).'</b><br>
            <b>GSTIN '.htmlspecialchars($PlantDetail->GSTNO).', <i>fssai</i> Lic.no '.htmlspecialchars($PlantDetail->FLNO1).'</b><br>
            <b>Contact No. : '.htmlspecialchars($PlantDetail->PHONENO).'</b>
        </div>
    </th></tr>';

    // Info row
    $html .= '<tr>
        <td colspan="8" style="border:1px solid #000;font-size:8px;"><b> Challan No :</b> '.$invoice->ChallanID.'</td>
        <td colspan="5" style="border:1px solid #000;font-size:8px;"><b> Date :</b> '._d(substr($invoice->Transdate,0,10)).'</td>
        <td colspan="9" style="border:1px solid #000;font-size:8px;"><b> Vehicle No :</b> '.$invoice->VehicleID.'</td>
        <td colspan="'.($total_columns-20).'" style="border:1px solid #000;font-size:8px;"><b> Name :</b> '.htmlspecialchars($driver).'</td>
    </tr>';

    // FG Code row
    $x = 53; 
    $y_fg = $pdf->GetY() + 49;
    $html .= '<tr><td width="'.$party_width_pct.'%" style="height:50px;font-weight:bold;border:1px solid #000;font-size:8px;"> FG Code</td>';

    foreach ($fg_codes as $valItemID) {
        $code = $valItemID;
        $arr_nomes[] = [$code, $x, $y_fg];
        $html .= '<td width="'.$col_width.'mm" style="border:1px solid #000;"></td>';
        $x += $col_width;
    }

    // fill remaining up to 25 if needed
    for ($i=0; $i<$extra_fg; $i++) {
        $arr_nomes[] = ['', $x, $y_fg];
        $html .= '<td width="'.$col_width.'mm" style="border:1px solid #000;"></td>';
        $x += $col_width;
    }

    $fixed_headers = ['CR Send','CR Rcvd','Sign'];
    foreach ($fixed_headers as $txt) {
        $arr_nomes[] = [$txt, $x, $y_fg, true];
        $html .= '<td width="'.$col_width.'mm" style="border:1px solid #000;"></td>';
        $x += $col_width;
    }

    $html .= '</tr>';

    // Description Row
    $x = 53; 
    $y_desc = $y_fg + 42;
    $html .= '<tr><td width="'.$party_width_pct.'%" style="height:120px;font-weight:bold;border:1px solid #000;font-size:8px;"> Party Name</td>';

    foreach($fg_codes as $valItemID) {
        $desc = '';
        $itemid = $valItemID;
        foreach ($Order_itemdetail as $d) {
            if ($d['ItemID'] == $itemid) { 
                $desc = substr($d['description'],0,20); 
                break; 
            }
        }
        $arr_nomes1[] = [$desc, $x, $y_desc];
        $html .= '<td width="'.$col_width.'mm" style="border:1px solid #000;font-size:8px;"></td>';
        $x += $col_width;
    }

    // fill blanks up to 25
    for ($i=0; $i<$extra_fg; $i++) {
        $arr_nomes1[] = ['', $x, $y_desc];
        $html .= '<td width="'.$col_width.'mm" style="border:1px solid #000;"></td>';
        $x += $col_width;
    }

    for ($i=0; $i<3; $i++) {
        $arr_nomes1[] = ['', $x, $y_desc];
        $html .= '<td width="'.$col_width.'mm" style="border:1px solid #000;"></td>';
        $x += $col_width;
    }

    $html .= '</tr></thead>';
    return $html;
} 

$chunked_parties = array_chunk($Accountdetail, 2);
$first_page = true;

foreach ($chunked_parties as $chunk) {
    if (!$first_page) $pdf->AddPage();

    $arr_nomes = $arr_nomes1 = [];

    $header_html = buildHeaderAndCoords(
        $pdf, $total_columns, $party_width_pct, $invoice, $PlantDetail, $driver,
        $fg_to_print, $fg_codes, $extra_fg, $Order_itemdetail, $col_width,
        $arr_nomes, $arr_nomes1, $title
    );

    renderPage(
        $pdf, $header_html, $fg_to_print, $actual_fg, $fg_codes, $extra_fg, $Order_itemdetail, $col_width,
        $chunk, $Crate_total, $Case_total, $Qty_total, $accountCrateTotals, $accountCaseTotals,
        $cell, $party_cell
    );

    // Rotated Text for FG Codes
    foreach ($arr_nomes as $a) {
        if ($a[0] === '') continue;
        $pdf->SetFont('helvetica', isset($a[3]) && $a[3] ? 'B' : '', 7);
        $pdf->StartTransform();
        $pdf->Rotate(90, $a[1], $a[2]);
        $pdf->Text($a[1], $a[2], $a[0]);
        $pdf->StopTransform(); 
    }

    // Rotated Text for Descriptions
    foreach ($arr_nomes1 as $a) {
        if ($a[0] === '') continue;
        $pdf->SetFont('helvetica', '', 7); 
        $pdf->StartTransform();
        $pdf->Rotate(90, $a[1], $a[2]);
        $pdf->Text($a[1], $a[2], $a[0]);
        $pdf->StopTransform();
    }

    $first_page = false;
}

$pdf->Output('route_memo.pdf', 'I');
?>