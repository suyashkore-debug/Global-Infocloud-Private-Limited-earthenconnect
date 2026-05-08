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

// ── Checkpoint Map ────────────────────────────────────────────────────────────
$checkpointMap = [
    1 => 'No water leakage / dampness',
    2 => 'No mold / odor',
    3 => 'Walls and floors intact',
    4 => 'No pest entry points',
    5 => 'Door seals intact',
    6 => 'No Chemicals stored nearby',
    7 => 'No non-foods items present',
    8 => 'Temperature control equipment working',
];

// Decode CheckPointIDs — stored as JSON array string e.g. ["1","2","4","5"]
$selectedCheckpoints = [];
if (!empty($log->CheckPointIDs)) {
    $decoded = json_decode($log->CheckPointIDs, true);
    if (is_array($decoded)) {
        $selectedCheckpoints = array_map('intval', $decoded);
    }
}

// ── Overall Status ────────────────────────────────────────────────────────────
// 1 = Acceptable, 2 = Needs Improvement
$overallStatus = isset($log->OverallStatus) ? (int)$log->OverallStatus : 0;
$statusAcceptable       = ($overallStatus === 1)
    ? '<span style="font-family:dejavusans; font-size:13px; color:#1565C0;">&#x2611;</span>'
    : '<span style="font-family:dejavusans; font-size:13px; color:#555555;">&#x2610;</span>';
$statusNeedsImprovement = ($overallStatus === 2)
    ? '<span style="font-family:dejavusans; font-size:13px; color:#1565C0;">&#x2611;</span>'
    : '<span style="font-family:dejavusans; font-size:13px; color:#555555;">&#x2610;</span>';

// ── Build Checkpoint Rows (2 columns layout) ──────────────────────────────────
// Split checkpoints into left and right columns (4 each)
$checkpointKeys  = array_keys($checkpointMap);
$leftKeys  = array_slice($checkpointKeys, 0, 4);  // IDs 1–4
$rightKeys = array_slice($checkpointKeys, 4, 4);  // IDs 5–8

$checkpointRows = '';
$maxRows = max(count($leftKeys), count($rightKeys));
for ($i = 0; $i < $maxRows; $i++) {
    $leftID    = $leftKeys[$i]  ?? null;
    $rightID   = $rightKeys[$i] ?? null;

    $leftBox = $leftLabel = $rightBox = $rightLabel = '';

    if ($leftID !== null) {
        $leftBox   = in_array($leftID, $selectedCheckpoints)
            ? '<span style="font-family:dejavusans; font-size:13px; color:#1565C0;">&#x2611;</span>'
            : '<span style="font-family:dejavusans; font-size:13px; color:#555555;">&#x2610;</span>';
        $leftLabel = htmlspecialchars($checkpointMap[$leftID]);
    }

    if ($rightID !== null) {
        $rightBox   = in_array($rightID, $selectedCheckpoints)
            ? '<span style="font-family:dejavusans; font-size:13px; color:#1565C0;">&#x2611;</span>'
            : '<span style="font-family:dejavusans; font-size:13px; color:#555555;">&#x2610;</span>';
        $rightLabel = htmlspecialchars($checkpointMap[$rightID]);
    }

    $checkpointRows .= '
    <tr>
        <td width="50%" valign="middle" style="border-right:1px solid #cccccc; border-bottom:1px solid #cccccc; padding:4px 8px;">
            ' . $leftBox . '&nbsp;<span style="font-size:10px;">' . $leftLabel . '</span>
        </td>
        <td width="50%" valign="middle" style="border-bottom:1px solid #cccccc; padding:4px 8px;">
            ' . $rightBox . '&nbsp;<span style="font-size:10px;">' . $rightLabel . '</span>
        </td>
    </tr>';
}

// ── Field Values ──────────────────────────────────────────────────────────────
$inspectionID = htmlspecialchars($log->InspectionID ?? '');
$accountName  = htmlspecialchars($log->AccountName  ?? '');
$locationID   = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : '-';
$remarks      = htmlspecialchars($log->Remarks       ?? '');

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
            Monthly Inspection Checklist
        </td>
    </tr>

    <!-- ===== INSPECTION INFO ROW 1 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Inspection ID</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $inspectionID . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Date</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $transDate . '</td>
    </tr>

    <!-- ===== INSPECTION INFO ROW 2 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Warehouse</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $accountName . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Location</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . $locationID . '</td>
    </tr>

    <!-- ===== CHECK POINTS HEADER ===== -->
    <tr>
        <td colspan="2" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">
            Check Points
        </td>
        <td colspan="2" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">
            Overall Status
        </td>
    </tr>

    <!-- ===== CHECK POINTS + OVERALL STATUS ===== -->
    <tr>
        <!-- Checkpoints 2-column inner table -->
        <td colspan="2" valign="top" style="border:1px solid #000000; padding:0px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">
                ' . $checkpointRows . '
            </table>
        </td>

        <!-- Overall Status -->
        <td colspan="2" valign="top" style="border:1px solid #000000; padding:8px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">
                <tr>
                    <td style="padding:4px 8px; border-bottom:1px solid #cccccc;">
                        ' . $statusAcceptable . '&nbsp;<span style="font-size:10px;">Acceptable</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:4px 8px;">
                        ' . $statusNeedsImprovement . '&nbsp;<span style="font-size:10px;">Needs Improvement</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== REMARKS ===== -->
    <tr>
        <td width="16%" align="center" valign="top" style="border:1px solid #000000; padding:5px; height:50px; background-color:#e8ecf0; font-weight:bold;">Remarks</td>
        <td colspan="3" valign="top" style="border:1px solid #000000; padding:5px; height:50px;">' . $remarks . '</td>
    </tr>

    <!-- ===== SIGNATURE ===== -->
    <tr style="height:80px;">
        <td colspan="2" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="30px" valign="bottom">
            Inspected By :
        </td>
        <td colspan="2" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="30px" valign="bottom">
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