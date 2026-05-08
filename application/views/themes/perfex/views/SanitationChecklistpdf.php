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
$weekStartDate = isset($log->WeekStartDate) ? date('d/m/Y', strtotime($log->WeekStartDate)) : '';
$locationID    = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : '-';

// ── Detail Rows Build ─────────────────────────────────────────────────────────
$detailRows = '';
$count      = 0;
$notOkList  = [];

$areaDisplayNames = [
    'Floor'   => 'Floor',
    'Pallets' => 'Pallets',
    'Rack'    => 'Storage Rack/Shelves',
    'Walls'   => 'Walls (visible area)',
    'Entry'   => 'Entry / Door Area',
];

foreach ($detailsArr as $item) {
    $areaKey   = $item->AreaName ?? '';
    $areaLabel = isset($areaDisplayNames[$areaKey]) ? $areaDisplayNames[$areaKey] : htmlspecialchars($areaKey);

    $statusRaw   = $item->Status ?? '';
    // ✓ fix: Ok = tick, Not Ok = X
    $status      = ($statusRaw === 'Y') ? 'Ok' : (($statusRaw === 'N') ? 'Not Ok' : '');
    $statusColor = ($statusRaw === 'N') ? '#cc0000' : '#007700';

    $cleaningAgent = htmlspecialchars($item->CleaningAgent ?? '');
    $issuesFound   = htmlspecialchars($item->IssuesFound   ?? '');
    $actionTaken   = htmlspecialchars($item->ActionTaken   ?? '');
    $initials      = htmlspecialchars($item->Initials      ?? '');

    $bg = (++$count % 2 === 0) ? '#f0f0f0' : '#ffffff';

    if ($statusRaw === 'N') {
        $notOkList[] = $areaLabel;
    }

    $detailRows .= "
    <tr bgcolor=\"{$bg}\">
        <td width=\"18%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px; font-weight:bold;\" align=\"center\" valign=\"middle\">{$areaLabel}</td>
        <td width=\"10%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px; color:{$statusColor}; font-weight:bold;\" align=\"center\" valign=\"middle\">{$status}</td>
        <td width=\"22%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$cleaningAgent}</td>
        <td width=\"22%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$issuesFound}</td>
        <td width=\"20%\" style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$actionTaken}</td>
        <td width=\"8%\"  style=\"border:1px solid #999999; padding:4px 5px; height:22px;\" align=\"center\" valign=\"middle\">{$initials}</td>
    </tr>";
}

// ── Summary ───────────────────────────────────────────────────────────────────
$notOkCount   = count($notOkList);
$summaryColor = $notOkCount > 0 ? '#cc0000' : '#007700';
$summaryText  = $notOkCount > 0
    ? 'WARNING: ' . $notOkCount . ' area(s) not cleaned properly — ' . implode(', ', $notOkList) . '. Corrective action required.'
    : 'All areas are cleaned and within acceptable standards.';

// ── Full HTML ─────────────────────────────────────────────────────────────────
$html = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">

    <!-- ===== HEADER ===== -->
    <tr>
        <td colspan="6" style="border:1px solid #000000; padding:5px;" valign="middle">
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
        <td colspan="6" align="center" valign="middle" style="border:1px solid #000000; padding:0px; font-size:13px; color:#ffffff; height:26px; font-weight:bold;">
            Weekly Sanitation Checklist
        </td>
    </tr>

    <!-- ===== LOG INFO ===== -->
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Sanitation ID</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->SanitationID ?? '') . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Week Starting Date</td>
        <td width="34%" align="center" valign="middle" colspan="3" style="border:1px solid #000000; padding:5px; height:20px;">' . $weekStartDate . '</td>
    </tr>
    <tr>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Warehouse</td>
        <td width="34%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->AccountName ?? '') . '</td>
        <td width="16%" align="center" valign="middle" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0; font-weight:bold;">Location</td>
        <td width="34%" align="center" valign="middle" colspan="3" style="border:1px solid #000000; padding:5px; height:20px;">' . $locationID . '</td>
    </tr>


    <!-- ===== TABLE HEADER ===== -->
    <tr bgcolor="#50607b">
        <td style="border:1px solid #000000; padding:5px; width:18%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Area</td>
        <td style="border:1px solid #000000; padding:5px; width:10%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Cleaned</td>
        <td style="border:1px solid #000000; padding:5px; width:22%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Cleaning Agent Used</td>
        <td style="border:1px solid #000000; padding:5px; width:22%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Issues Found</td>
        <td style="border:1px solid #000000; padding:5px; width:20%; color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Action Taken</td>
        <td style="border:1px solid #000000; padding:5px; width:8%;  color:#ffffff; height:22px; font-weight:bold;" align="center" valign="middle">Initials</td>
    </tr>

    <!-- ===== DETAIL ROWS ===== -->
    ' . $detailRows . '

    <!-- ===== SUMMARY ===== -->
    <tr>
        <td colspan="6" align="center" valign="middle" style="border:1px solid #000000; padding:6px; height:20px; color:' . $summaryColor . '; font-weight:bold; font-size:11px;">
            ' . $summaryText . '
        </td>
    </tr>

    <!-- ===== SIGNATURE ===== -->
     <tr style="height:100px;">
        <td colspan="3" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="29px" valign="bottom">
            Prepared By :
        </td>
        <td colspan="3" style="border:1px solid #000000; padding:10px; font-weight:bold;" align="left" height="20px" valign="bottom">
            Authorized Sign :
        </td>
    </tr>

    <!-- ===== PRINTED ON ===== -->
    <tr>
        <td colspan="3" valign="middle" style="border:1px solid #000000; padding:5px; height:18px; font-size:10px; font-weight:bold;">
            Printed On : ' . date('d/m/Y h:i A') . '
        </td>
        <td colspan="3" valign="middle" style="border:1px solid #000000; padding:5px; height:18px;"></td>
    </tr>

</table>';

$pdf->writeHTML($html, true, false, false, false, '');