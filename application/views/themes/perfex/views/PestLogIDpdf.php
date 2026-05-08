<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php

$pdf->SetMargins(10, 10, 10, 0);
$pdf->Ln(0);

// ── Data Extract ──────────────────────────────────────────────────────────────
$rootcom    = isset($log->rootcompany['data'][0]) ? $log->rootcompany['data'][0] : [];
$detailsArr = isset($log->details) && is_array($log->details) ? $log->details : [];

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

// ── Date / Location ───────────────────────────────────────────────────────────
$transDate  = isset($log->TransDate)  ? date('d/m/Y', strtotime($log->TransDate)) : '';
$locationID = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : '-';

// ── Trap Location Display Names ───────────────────────────────────────────────
$trapDisplayNames = [
    'Door'    => 'Entry Door',
    'Corners' => 'Corners',
    'Area'    => 'Storage Area',
];

// ── Detail Rows Build ─────────────────────────────────────────────────────────
$detailRows  = '';
$count       = 0;
$activityList = [];

foreach ($detailsArr as $item) {
    $trapKey   = $item->TrapLocation ?? '';
    $trapLabel = isset($trapDisplayNames[$trapKey]) ? $trapDisplayNames[$trapKey] : htmlspecialchars($trapKey);

    $activityRaw   = $item->ActivityFound ?? '';
    $activity      = ($activityRaw === 'Y') ? 'Yes' : (($activityRaw === 'N') ? 'No' : '');
    $activityColor = ($activityRaw === 'Y') ? '#cc0000' : '#007700';

    $trapCondition = htmlspecialchars($item->TrapCondition ?? '');
    $actionTaken   = htmlspecialchars($item->ActionTaken   ?? '');
    $initials      = htmlspecialchars($item->Initials      ?? '');

    $bg = (++$count % 2 === 0) ? '#f0f0f0' : '#ffffff';

    if ($activityRaw === 'Y') {
        $activityList[] = $trapLabel;
    }

    $detailRows .= "
    <tr bgcolor=\"{$bg}\">
        <td width=\"20%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px; font-weight:bold;\" align=\"center\" valign=\"middle\">{$trapLabel}</td>
        <td width=\"25%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$trapCondition}</td>
        <td width=\"12%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px; color:{$activityColor}; font-weight:bold;\" align=\"center\" valign=\"middle\">{$activity}</td>
        <td width=\"28%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$actionTaken}</td>
        <td width=\"15%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$initials}</td>
    </tr>";
}

// ── Summary ───────────────────────────────────────────────────────────────────
$activityCount = count($activityList);
$summaryColor  = $activityCount > 0 ? '#cc0000' : '#007700';
$summaryText   = $activityCount > 0
    ? 'WARNING: Pest activity found at ' . $activityCount . ' location(s) — ' . implode(', ', $activityList) . '. Immediate corrective action required.'
    : 'No pest activity found. All trap locations are within acceptable standards.';

// ── Full HTML ─────────────────────────────────────────────────────────────────
$html = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">

    <!-- ===== HEADER ===== -->
    <tr>
        <td colspan="5" style="border:1px solid #000000; padding:5px;" valign="middle">
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
        <td colspan="5" align="center" valign="middle" style="border:1px solid #000000; padding:0px; font-size:13px; color:#ffffff; height:26px; font-weight:bold;">
           Weekly/Monthly Pest Control Log
        </td>
    </tr>

    <!-- ===== LOG INFO ROW 1 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Pest Log ID</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->PestLogID ?? '') . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Date</td>
        <td width="34%" align="center" valign="middle" colspan="2" style="border:1px solid #000000; padding:5px; height:20px;">' . $transDate . '</td>
    </tr>

    <!-- ===== LOG INFO ROW 2 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Warehouse</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->AccountName ?? '') . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Location</td>
        <td width="34%" align="center" valign="middle" colspan="2" style="border:1px solid #000000; padding:5px; height:20px;">' . $locationID . '</td>
    </tr>

    <!-- ===== LOG INFO ROW 3 ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Pest Control Service</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->ServiceName ?? '') . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Remark</td>
        <td width="34%" align="center" valign="middle" colspan="2" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->Remark ?? '') . '</td>
    </tr>

    <!-- ===== TABLE HEADER ===== -->
    <tr bgcolor="#50607b">
        <td style="border:1px solid #000000; padding:5px; width:20%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Trap Location</td>
        <td style="border:1px solid #000000; padding:5px; width:25%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Trap Condition</td>
        <td style="border:1px solid #000000; padding:5px; width:12%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Activity Found (Y/N)</td>
        <td style="border:1px solid #000000; padding:5px; width:28%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Action Taken</td>
        <td style="border:1px solid #000000; padding:5px; width:15%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Initials</td>
    </tr>

    <!-- ===== DETAIL ROWS ===== -->
    ' . $detailRows . '

    <!-- ===== SUMMARY ===== -->
    <tr>
        <td colspan="5" align="center" valign="middle" style="border:1px solid #000000; padding:6px; height:20px; color:' . $summaryColor . '; font-weight:bold; font-size:11px;">
            ' . $summaryText . '
        </td>
    </tr>

    <!-- ===== SIGNATURE ===== -->
     <tr style="height:100px;">
        <td colspan="2" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="29px" valign="bottom">
            Prepared By :
        </td>
        <td colspan="3" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="20px" valign="bottom">
            Authorized Sign :
        </td>
    </tr>


    <!-- ===== PRINTED ON ===== -->
    <tr>
        <td colspan="2" valign="middle" style="border:1px solid #000000; padding:5px; height:18px; font-size:10px; font-weight:bold;">
            Printed On : ' . date('d/m/Y h:i A') . '
        </td>
        <td colspan="3" valign="middle" style="border:1px solid #000000; padding:5px; height:18px;"></td>
    </tr>

</table>';

$pdf->writeHTML($html, true, false, false, false, '');