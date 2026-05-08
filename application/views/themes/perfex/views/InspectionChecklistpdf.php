<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php

$pdf->SetMargins(10, 10, 10, 0);
$pdf->SetAutoPageBreak(true, 10);
$pdf->Ln(0);

// ── Data Extract ──────────────────────────────────────────────────────────────
$rootcom = isset($log->rootcompany['data'][0]) ? $log->rootcompany['data'][0] : [];
$rc = function($key) use ($rootcom) {
    return isset($rootcom[$key]) ? $rootcom[$key] : '';
};

$product1 = isset($log->product1[0]) ? (object)$log->product1[0] : (object)[];

$transDate    = isset($log->TransDate)  ? date('d/m/Y', strtotime($log->TransDate)) : '';
$locationID   = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : '-';
$inspectionID = $log->InspectionID ?? '';
$batchNo      = $log->BatchNo      ?? '';
$remark       = $log->Remark       ?? '';

$warehouseName = $product1->WarehouseName ?? ($log->AccountName ?? '');
$supplierName  = $product1->SupplierName  ?? '';
$productName   = $product1->ProductName   ?? '';

// ── Parameter Status ──────────────────────────────────────────────────────────
$parameters = [
    ['Packaging Intact (no tears/leaks)', $product1->PackagingStatus ?? ''],
    ['No Moisture Damage',                $product1->MoistureStatus  ?? ''],
    ['No pest Contamination',             $product1->PestStatus      ?? ''],
    ['Labels Present (ingredient,origin,expiry)', $product1->LabelsStatus ?? ''],
    ['COA / Document Received',           $product1->COAStatus       ?? ''],
];

// ── Colors & Layout ───────────────────────────────────────────────────────────
$headerBg = [80, 96, 123];
$labelBg  = [232, 236, 240];
$marginL  = 10;
$pageW    = 190;

$col1 = 32;
$col2 = 63;
$col3 = 32;
$col4 = 63;

// Detail table columns
$tc1 = 152; // Parameter
$tc2 = 38;  // OK / X

// ── Logo ──────────────────────────────────────────────────────────────────────
$logoFound  = false;
$logoPath   = '';
$directPath = FCPATH . 'assets/images/logo.png';
if (file_exists($directPath)) {
    $logoPath  = $directPath;
    $logoFound = true;
}
if (!$logoFound && function_exists('pdf_logo_url')) {
    $parsedPath = ltrim(parse_url(pdf_logo_url(), PHP_URL_PATH), '/');
    $fromUrl    = FCPATH . $parsedPath;
    if (file_exists($fromUrl)) {
        $logoPath  = $fromUrl;
        $logoFound = true;
    }
}
if (!$logoFound) {
    $fallbacks = [
        APPPATH . '../assets/images/logo.png',
        APPPATH . 'assets/images/logo.png',
        $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo.png',
    ];
    if (defined('ROOTPATH')) {
        $fallbacks[] = ROOTPATH . 'assets/images/logo.png';
    }
    foreach ($fallbacks as $fp) {
        if (file_exists($fp)) {
            $logoPath  = $fp;
            $logoFound = true;
            break;
        }
    }
}

// ── HEADER ───────────────────────────────────────────────────────────────────
$hX = $marginL;
$hY = $pdf->GetY();
$hH = 24;
$pdf->Rect($hX, $hY, $pageW, $hH, 'D');
if ($logoFound) {
    $pdf->Image($logoPath, $hX + 2, $hY + 4, 20, 16, 'PNG');
}
$pdf->SetXY($hX, $hY + 2);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($pageW, 7, $rc('company_name'), 0, 2, 'C');
$pdf->SetX($hX);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW, 5, $rc('address') . ', ' . $rc('city') . ' - ' . $rc('pincode') . ', ' . $rc('state'), 0, 2, 'C');
$pdf->SetX($hX);
$pdf->Cell($pageW, 5, 'GSTIN: ' . $rc('gst') . '     Contact: ' . $rc('mobile1') . ' / ' . $rc('mobile2'), 0, 2, 'C');
$pdf->SetXY($hX, $hY + $hH);

// ── TITLE ─────────────────────────────────────────────────────────────────────
$pdf->SetFillColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($pageW, 8, 'Incoming Material Inspection Report', 1, 1, 'C', true);
$pdf->SetTextColor(0, 0, 0);

// ── HELPER: 4-column info row ─────────────────────────────────────────────────
$drawRow4 = function($label1, $data1, $label2, $data2, $minH = 8) use ($pdf, $labelBg, $marginL, $col1, $col2, $col3, $col4) {
    $pdf->SetFont('helvetica', 'B', 9); $h1 = $pdf->getStringHeight($col1, $label1);
    $pdf->SetFont('helvetica', '', 10); $h2 = $pdf->getStringHeight($col2, $data1);
    $pdf->SetFont('helvetica', 'B', 9); $h3 = $pdf->getStringHeight($col3, $label2);
    $pdf->SetFont('helvetica', '', 10); $h4 = $pdf->getStringHeight($col4, $data2);
    $rowH = max($h1, $h2, $h3, $h4, $minH);
    $rowY = $pdf->GetY();

    $pdf->SetXY($marginL, $rowY);
    $pdf->SetFillColor($labelBg[0], $labelBg[1], $labelBg[2]);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell($col1, $rowH, $label1, 1, 'C', true, 0, '', '', true, 0, false, true, $rowH, 'M');

    $pdf->SetXY($marginL + $col1, $rowY);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell($col2, $rowH, $data1, 1, 'C', true, 0, '', '', true, 0, false, true, $rowH, 'M');

    $pdf->SetXY($marginL + $col1 + $col2, $rowY);
    $pdf->SetFillColor($labelBg[0], $labelBg[1], $labelBg[2]);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell($col3, $rowH, $label2, 1, 'C', true, 0, '', '', true, 0, false, true, $rowH, 'M');

    $pdf->SetXY($marginL + $col1 + $col2 + $col3, $rowY);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell($col4, $rowH, $data2, 1, 'C', true, 1, '', '', true, 0, false, true, $rowH, 'M');

    $pdf->SetXY($marginL, $rowY + $rowH);
};

// ── HELPER: 2-column wide row ─────────────────────────────────────────────────
$drawRow2 = function($label, $data, $minH = 8) use ($pdf, $labelBg, $marginL, $pageW, $col1) {
    $dataW = $pageW - $col1;
    $pdf->SetFont('helvetica', 'B', 9); $h1 = $pdf->getStringHeight($col1, $label);
    $pdf->SetFont('helvetica', '', 10); $h2 = $pdf->getStringHeight($dataW, $data);
    $rowH = max($h1, $h2, $minH);
    $rowY = $pdf->GetY();

    $pdf->SetXY($marginL, $rowY);
    $pdf->SetFillColor($labelBg[0], $labelBg[1], $labelBg[2]);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell($col1, $rowH, $label, 1, 'C', true, 0, '', '', true, 0, false, true, $rowH, 'M');

    $pdf->SetXY($marginL + $col1, $rowY);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell($dataW, $rowH, $data, 1, 'L', true, 1, '', '', true, 0, false, true, $rowH, 'T');

    $pdf->SetXY($marginL, $rowY + $rowH);
};

// ── INFO ROWS ─────────────────────────────────────────────────────────────────
$drawRow4('Entry No', $inspectionID,  'Date',          $transDate);
$drawRow4('Warehouse',     $warehouseName, 'Location',      $locationID);
$drawRow4('Supplier Name', $supplierName,  'Product Name',  $productName);
$drawRow4('Batch / Lot No', $batchNo,      '',              '');
$drawRow2('Reason (if rejected)', $remark);

// ── DETAIL TABLE HEADER ───────────────────────────────────────────────────────
$thY = $pdf->GetY();
$thH = 10;
$pdf->SetFillColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 9);

$pdf->SetXY($marginL, $thY);
$pdf->MultiCell($tc1, $thH, 'Parameter', 1, 'L', true, 0, '', '', true, 0, false, true, $thH, 'M');
$pdf->SetXY($marginL + $tc1, $thY);
$pdf->MultiCell($tc2, $thH, 'OK / X',   1, 'C', true, 1, '', '', true, 0, false, true, $thH, 'M');
$pdf->SetXY($marginL, $thY + $thH);
$pdf->SetTextColor(0, 0, 0);

// ── PARAMETER ROWS ────────────────────────────────────────────────────────────
$count = 0;
foreach ($parameters as [$paramLabel, $statusRaw]) {
    $status = ($statusRaw === 'Y') ? 'Ok' : (($statusRaw === 'N') ? 'Not Ok' : '');

    $pdf->SetFont('helvetica', '', 9);
    $rh1  = $pdf->getStringHeight($tc1, $paramLabel);
    $rh2  = $pdf->getStringHeight($tc2, $status);
    $rowH = max($rh1, $rh2, 8);

    $count++;
    $rowBg = ($count % 2 === 0) ? [240, 240, 240] : [255, 255, 255];
    $rowY  = $pdf->GetY();

    // Parameter label
    $pdf->SetXY($marginL, $rowY);
    $pdf->SetFillColor($rowBg[0], $rowBg[1], $rowBg[2]);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell($tc1, $rowH, $paramLabel, 1, 'L', true, 0, '', '', true, 0, false, true, $rowH, 'M');

    // OK / Not Ok — colored
    $pdf->SetXY($marginL + $tc1, $rowY);
    $pdf->SetFont('helvetica', 'B', 9);
    if ($statusRaw === 'N') {
        $pdf->SetTextColor(204, 0, 0);
    } elseif ($statusRaw === 'Y') {
        $pdf->SetTextColor(0, 119, 0);
    } else {
        $pdf->SetTextColor(0, 0, 0);
    }
    $pdf->MultiCell($tc2, $rowH, $status, 1, 'C', true, 1, '', '', true, 0, false, true, $rowH, 'M');
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetXY($marginL, $rowY + $rowH);
}

// ── SUMMARY ROW ───────────────────────────────────────────────────────────────
$notOkList = [];
foreach ($parameters as [$paramLabel, $statusRaw]) {
    if ($statusRaw === 'N') {
        $notOkList[] = $paramLabel;
    }
}
$notOkCount  = count($notOkList);
$summaryText = $notOkCount > 0
    ? 'WARNING: ' . $notOkCount . ' parameter(s) failed — ' . implode(', ', $notOkList) . '. Corrective action required.'
    : 'All parameters passed inspection successfully.';

$pdf->SetFont('helvetica', 'B', 9);
$sH = max($pdf->getStringHeight($pageW, $summaryText), 10);
$sY = $pdf->GetY();
$pdf->SetXY($marginL, $sY);
$pdf->SetFillColor(255, 255, 255);
if ($notOkCount > 0) {
    $pdf->SetTextColor(204, 0, 0);
} else {
    $pdf->SetTextColor(0, 119, 0);
}
$pdf->MultiCell($pageW, $sH, $summaryText, 1, 'C', true, 1, '', '', true, 0, false, true, $sH, 'M');
$pdf->SetTextColor(0, 0, 0);

// ── SIGNATURE ─────────────────────────────────────────────────────────────────
$sigH = 20;
$sigW = $pageW / 2;
$sY   = $pdf->GetY();

$pdf->SetFillColor(255, 255, 255);
$pdf->Rect($marginL, $sY, $sigW, $sigH, 'D');
$pdf->SetXY($marginL + 3, $sY + $sigH - 7);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell($sigW - 6, 6, 'Prepared By :', 0, 0, 'L');

$pdf->Rect($marginL + $sigW, $sY, $sigW, $sigH, 'D');
$pdf->SetXY($marginL + $sigW + 3, $sY + $sigH - 7);
$pdf->Cell($sigW - 6, 6, 'Authorized Sign :', 0, 0, 'L');
$pdf->SetXY($marginL, $sY + $sigH);

// ── PRINTED ON ────────────────────────────────────────────────────────────────
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell($sigW, 7, 'Printed On : ' . date('d/m/Y h:i A'), 1, 0, 'L', true);
$pdf->Cell($sigW, 7, '', 1, 1, 'L', true);