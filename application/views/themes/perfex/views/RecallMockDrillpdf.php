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

// ── Date ──────────────────────────────────────────────────────────────────────
$transDate = isset($log->TransDate) ? date('d/m/Y', strtotime($log->TransDate)) : '';

// ── Field Values ──────────────────────────────────────────────────────────────
$recallID            = $log->RecallID                ?? '';
$warehouseName       = $log->AccountName             ?? '';
$locationID          = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : 'None';
$productName         = $log->product->description    ?? '';
$productCode         = $log->product->item_code      ?? '';
$batchNo             = (!empty($log->BatchNo) && $log->BatchNo != 0) ? $log->BatchNo : 'None';
$timeRecallInitiated = $log->TimeRecallInitiated     ?? '';
$timeCustomerIDd     = $log->TimeCustomerIdentified  ?? '';
$timeProductTraced   = $log->TimeProductTraced       ?? '';
$gapIdentified       = $log->GapIdentified           ?? '';
$actionPlan          = $log->ActionPlan              ?? '';

// ── Within 2 Hrs ──────────────────────────────────────────────────────────────
$within2Hrs = isset($log->CompletedWithin2Hrs) ? (int)$log->CompletedWithin2Hrs : 0;

// ── Colors ────────────────────────────────────────────────────────────────────
$headerBg = [80,  96, 123];
$labelBg  = [232, 236, 240];

// ── Layout ────────────────────────────────────────────────────────────────────
$marginL = 10;
$pageW   = 190;
$col1 = 32;
$col2 = 63;
$col3 = 47;
$col4 = 48;

// ════════════════════════════════════════════════════════════════════════════
// LOGO PATH — HTML version मध्ये pdf_logo_url() वापरतो
// त्या URL वरून server path काढतो TCPDF साठी
// ════════════════════════════════════════════════════════════════════════════
$logoFound = false;
$logoPath  = '';

// Step 1: Direct FCPATH check (सर्वात आधी)
$directPath = FCPATH . 'assets/images/logo.png';
if (file_exists($directPath)) {
    $logoPath  = $directPath;
    $logoFound = true;
}

// Step 2: pdf_logo_url() function असल्यास त्यातून path काढ
if (!$logoFound && function_exists('pdf_logo_url')) {
    $logoUrl = pdf_logo_url(); // URL मिळेल, e.g. http://domain.com/assets/images/logo.png

    // URL मधून relative path काढ आणि FCPATH जोड
    $parsedPath = parse_url($logoUrl, PHP_URL_PATH); // /assets/images/logo.png
    $parsedPath = ltrim($parsedPath, '/');            // assets/images/logo.png

    $fromUrl = FCPATH . $parsedPath;
    if (file_exists($fromUrl)) {
        $logoPath  = $fromUrl;
        $logoFound = true;
    }
}

// Step 3: Other common fallback paths
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


// ════════════════════════════════════════════════════════════════════════════
// HEADER — Logo + Company Info
// ════════════════════════════════════════════════════════════════════════════
$hX = $marginL;
$hY = $pdf->GetY();
$hH = 24;

// Border FIRST
$pdf->Rect($hX, $hY, $pageW, $hH, 'D');

// Logo — Rect नंतर लगेच, कोणताही Cell/MultiCell आधी नाही
if ($logoFound) {
    $pdf->Image($logoPath, $hX + 2, $hY + 4, 20, 16, 'PNG');
    $textX = $hX + 26;
    $textW = $pageW - 26;
} else {
    // Logo नसल्यास text पूर्ण width वापरेल
    $textX = $hX + 2;
    $textW = $pageW - 4;
}

// Company Name
$pdf->SetXY($textX, $hY + 2);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($textW, 7, $rc('company_name'), 0, 2, 'C');

// Address
$pdf->SetX($textX);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($textW, 5,
    $rc('address') . ', ' . $rc('city') . ' - ' . $rc('pincode') . ', ' . $rc('state'),
    0, 2, 'C');

// GSTIN + Contact
$pdf->SetX($textX);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(14, 5, 'GSTIN:', 0, 0, 'R');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(32, 5, $rc('gst'), 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(18, 5, 'Contact:', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(40, 5, $rc('mobile1') . ' / ' . $rc('mobile2'), 0, 0, 'L');

// Y header खाली
$pdf->SetXY($hX, $hY + $hH);

// ════════════════════════════════════════════════════════════════════════════
// TITLE BAR
// ════════════════════════════════════════════════════════════════════════════
$pdf->SetFillColor($headerBg[0], $headerBg[1], $headerBg[2]);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($pageW, 8, 'Recall Mock Drill', 1, 1, 'C', true);
$pdf->SetTextColor(0, 0, 0);

// ════════════════════════════════════════════════════════════════════════════
// HELPER — 4-column row
// ════════════════════════════════════════════════════════════════════════════
$drawRow4 = function($label1, $data1, $label2, $data2, $minH = 8)
    use ($pdf, $labelBg, $col1, $col2, $col3, $col4, $marginL)
{
    $pdf->SetFont('helvetica', 'B', 9);
    $h1 = $pdf->getStringHeight($col1, $label1);
    $pdf->SetFont('helvetica', '', 10);
    $h2 = $pdf->getStringHeight($col2, $data1);
    $pdf->SetFont('helvetica', 'B', 9);
    $h3 = $pdf->getStringHeight($col3, $label2);
    $pdf->SetFont('helvetica', '', 10);
    $h4 = $pdf->getStringHeight($col4, $data2);

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
    return $rowH;
};

// ── Rows 1–4 ──
$drawRow4('Recall ID',             $recallID,            'Date',                          $transDate);
$drawRow4('Warehouse',             $warehouseName,       'Location',                      $locationID);
$drawRow4('Product',               $productName . ' (' . $productCode . ')', 'Batch',    $batchNo);
$drawRow4('Time Recall Initiated', $timeRecallInitiated, 'Time All Customer Identified',  $timeCustomerIDd);

// ── Row 5 — Time Product Traced | Checkboxes ──
$row5Y = $pdf->GetY();
$row5H = $drawRow4('Time Product Traced', $timeProductTraced, 'Recall completed within 2 hours', '');

$chkX    = $marginL + $col1 + $col2 + $col3;
$chkY    = $row5Y + ($row5H / 2) - 3;
$yesChar = ($within2Hrs === 1) ? "\xe2\x98\x91" : "\xe2\x98\x90";
$noChar  = ($within2Hrs === 2) ? "\xe2\x98\x91" : "\xe2\x98\x90";

$pdf->SetFont('dejavusans', '', 11);
$pdf->SetTextColor($within2Hrs === 1 ? 21 : 85, $within2Hrs === 1 ? 101 : 85, $within2Hrs === 1 ? 192 : 85);
$pdf->SetXY($chkX + 3, $chkY);
$pdf->Cell(6, 5, $yesChar, 0, 0, 'C');
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(11, 5, ' Yes', 0, 0, 'L');

$pdf->SetFont('dejavusans', '', 11);
$pdf->SetTextColor($within2Hrs === 2 ? 21 : 85, $within2Hrs === 2 ? 101 : 85, $within2Hrs === 2 ? 192 : 85);
$pdf->Cell(6, 5, $noChar, 0, 0, 'C');
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(10, 5, ' No', 0, 0, 'L');

$pdf->SetXY($marginL, $row5Y + $row5H);

// ── Row 6 — Gap Identified ──
$pdf->SetFont('helvetica', '', 10);
$gapH = max($pdf->getStringHeight($col2 + $col3 + $col4, $gapIdentified), 20);
$gY   = $pdf->GetY();

$pdf->SetXY($marginL, $gY);
$pdf->SetFillColor($labelBg[0], $labelBg[1], $labelBg[2]);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell($col1, $gapH, 'Gap Identified', 1, 'C', true, 0, '', '', true, 0, false, true, $gapH, 'M');

$pdf->SetXY($marginL + $col1, $gY);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell($col2 + $col3 + $col4, $gapH, $gapIdentified, 1, 'L', true, 1, '', '', true, 0, false, true, $gapH, 'T');

$pdf->SetXY($marginL, $gY + $gapH);

// ── Row 7 — Action Plan ──
$pdf->SetFont('helvetica', '', 10);
$actionH = max($pdf->getStringHeight($col2 + $col3 + $col4, $actionPlan), 20);
$aY = $pdf->GetY();

$pdf->SetXY($marginL, $aY);
$pdf->SetFillColor($labelBg[0], $labelBg[1], $labelBg[2]);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell($col1, $actionH, 'Action Plan', 1, 'C', true, 0, '', '', true, 0, false, true, $actionH, 'M');

$pdf->SetXY($marginL + $col1, $aY);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell($col2 + $col3 + $col4, $actionH, $actionPlan, 1, 'L', true, 1, '', '', true, 0, false, true, $actionH, 'T');

$pdf->SetXY($marginL, $aY + $actionH);

// ════════════════════════════════════════════════════════════════════════════
// SIGNATURE ROW
// ════════════════════════════════════════════════════════════════════════════
$sigH = 20;
$sigW = $pageW / 2;
$sY   = $pdf->GetY();

$pdf->SetFillColor(255, 255, 255);

$pdf->Rect($marginL, $sY, $sigW, $sigH, 'D');
$pdf->SetXY($marginL + 3, $sY + $sigH - 7);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell($sigW - 6, 6, 'Inspected By :', 0, 0, 'L');

$pdf->Rect($marginL + $sigW, $sY, $sigW, $sigH, 'D');
$pdf->SetXY($marginL + $sigW + 3, $sY + $sigH - 7);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell($sigW - 6, 6, 'Authorized Sign :', 0, 0, 'L');

$pdf->SetXY($marginL, $sY + $sigH);

// ════════════════════════════════════════════════════════════════════════════
// PRINTED ON ROW
// ════════════════════════════════════════════════════════════════════════════
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell($sigW, 7, 'Printed On : ' . date('d/m/Y h:i A'), 1, 0, 'L', true);
$pdf->Cell($sigW, 7, '', 1, 1, 'L', true);