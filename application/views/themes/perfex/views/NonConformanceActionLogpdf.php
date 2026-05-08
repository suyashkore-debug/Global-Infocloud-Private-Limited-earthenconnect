<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php

$pdf->SetMargins(10, 10, 10, 0);
$pdf->Ln(0);

// ── Data Extract ──────────────────────────────────────────────────────────────
$rootcom = isset($log->rootcompany['data'][0]) ? $log->rootcompany['data'][0] : [];

$rc = function($key) use ($rootcom) {
    return isset($rootcom[$key]) ? $rootcom[$key] : '';
};

// ── Logo ──────────────────────────────────────────────────────────────────────
$logoPath = FCPATH . 'assets/images/logo.png';
$logoTag  = '';
if (file_exists($logoPath)) {
    $logoTag = '<img src="' . $logoPath . '" width="80" height="60">';
} else {
    $logoTag = pdf_logo_url();
}

// ── Date ──────────────────────────────────────────────────────────────────────
$transDate = isset($log->TransDate) ? date('d/m/Y', strtotime($log->TransDate)) : '';

// ── Type Checkboxes ───────────────────────────────────────────────────────────
$typeMap = [
    1 => 'Temperature Deviation',
    2 => 'Pest Issue',
    3 => 'Damaged Packaging',
    4 => 'Hygiene Issue',
    5 => 'Other',
];

$detailsArr  = isset($log->details) && is_array($log->details) ? $log->details : [];
$selectedIDs = array_map(fn($d) => (int)($d->TypeID ?? 0), $detailsArr);

// Build checkbox cells — using [X] / [ ] style, works in all mPDF versions
$checkboxCells = '';
foreach ($typeMap as $id => $label) {
    if (in_array($id, $selectedIDs)) {
        $box = '<span style="font-family:dejavusans; font-size:13px; color:#1565C0;">&#x2611;</span>'; // ☑ checked
    } else {
        $box = '<span style="font-family:dejavusans; font-size:13px; color:#555555;">&#x2610;</span>'; // ☐ unchecked
    }
    $checkboxCells .= $box . '&nbsp;<span style="font-size:11px;">' . htmlspecialchars($label) . '</span>&nbsp;&nbsp;&nbsp;';
}

// ── Location ──────────────────────────────────────────────────────────────────
$locationID = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : '-';

// ── Field Values ──────────────────────────────────────────────────────────────
$issue            = htmlspecialchars($log->Issue            ?? '');
$correctiveAction = htmlspecialchars($log->CorrectiveAction ?? '');
$preventiveAction = htmlspecialchars($log->PreventiveAction ?? '');
$otherText        = htmlspecialchars($log->OtherText        ?? '');
$ncID             = htmlspecialchars($log->NCID             ?? '');
$accountName      = htmlspecialchars($log->AccountName      ?? '');

// ── Full HTML ─────────────────────────────────────────────────────────────────
$html = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">

    <!-- ===== HEADER ===== -->
    <tr>
        <td colspan="4" style="border:1px solid #000000; padding:5px;" valign="middle">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="15%" align="left" valign="middle">
                        ' . $logoTag . '
                    </td>
                    <td width="70%" align="center" valign="middle">
                        <span style="font-size:15px; font-weight:bold;">' . htmlspecialchars($rc('company_name')) . '</span><br/>
                        <span style="font-size:10px;">' . htmlspecialchars($rc('address')) . ', ' . htmlspecialchars($rc('city')) . ' - ' . htmlspecialchars($rc('pincode')) . ', ' . htmlspecialchars($rc('state')) . '</span><br/>
                        <span style="font-size:10px;"><span style="font-weight:bold;">GSTIN:</span> ' . htmlspecialchars($rc('gst')) . ' &nbsp;|&nbsp; <span style="font-weight:bold;">Contact:</span> ' . htmlspecialchars($rc('mobile1')) . ' / ' . htmlspecialchars($rc('mobile2')) . '</span>
                    </td>
                    <td width="15%" valign="middle"></td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== TITLE ===== -->
    <tr bgcolor="#50607b">
        <td colspan="4" align="center" valign="middle" style="border:1px solid #000000; padding:0px; font-size:13px; color:#ffffff; height:26px; font-weight:bold;">
            Non-Conformance & Corrective Action Log
        </td>
    </tr>

    <!-- ===== NC INFO ROW 1 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">NC ID</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $ncID . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Date</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $transDate . '</td>
    </tr>

    <!-- ===== NC INFO ROW 2 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Warehouse</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $accountName . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Location</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $locationID . '</td>
    </tr>

    <!-- ===== TYPE ROW ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:26px; background-color:#e8ecf0; font-weight:bold;">Type</td>
        <td colspan="3" align="center" valign="middle" style="border:1px solid #000000; padding:6px 10px; height:26px;">
            ' . $checkboxCells . '
        </td>
    </tr>

    <!-- ===== OTHERS ROW ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Others</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $otherText . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Issue Identified</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $issue . '</td>
    </tr>

    <!-- ===== ACTIONS ROW ===== -->
    <tr>
        <td width="16%" align="center" valign="top" style="border:1px solid #000000; padding:5px; height:50px; background-color:#e8ecf0; font-weight:bold;">Corrective Action Taken</td>
        <td width="34%" align="center" valign="top" style="border:1px solid #000000; padding:5px; height:50px;">' . $correctiveAction . '</td>
        <td width="16%" align="center" valign="top" style="border:1px solid #000000; padding:5px; height:50px; background-color:#e8ecf0; font-weight:bold;">Preventive Action (Future)</td>
        <td width="34%" align="center" valign="top" style="border:1px solid #000000; padding:5px; height:50px;">' . $preventiveAction . '</td>
    </tr>

     <!-- ===== SIGNATURE ===== -->
     <tr style="height:100px;">
        <td colspan="2" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="29px" valign="bottom">
            Prepared By :
        </td>
        <td colspan="2" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="20px" valign="bottom">
            Authorized Sign :
        </td>
    </tr>

    <!-- ===== PRINTED ON ===== -->
    <tr>
        <td colspan="2" valign="middle" style="border:1px solid #000000; padding:5px; height:18px; font-size:10px; font-weight:bold;">
            Printed On : ' . date('d/m/Y h:i A') . '
        </td>
        <td colspan="2" valign="middle" style="border:1px solid #000000; padding:5px; height:18px;"></td>
    </tr>

</table>';

$pdf->writeHTML($html, true, false, false, false, '');