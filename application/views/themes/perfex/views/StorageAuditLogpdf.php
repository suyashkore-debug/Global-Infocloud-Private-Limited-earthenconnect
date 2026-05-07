<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php

$pdf->SetMargins(10, 10, 10, 0);
$pdf->Ln(0);

// ── Data Extract ──────────────────────────────────────────────────────────────
$rootcom    = isset($log->rootcompany['data'][0]) ? $log->rootcompany['data'][0] : [];
$detailsArr = isset($log->details) && is_array($log->details) ? $log->details : [];

$params = [];
foreach ($detailsArr as $item) {
    $params[$item->ParameterName] = $item;
}

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
$transDate  = isset($log->TransDate) ? date('d/m/Y', strtotime($log->TransDate)) : '';
$locationID = (isset($log->LocationID) && $log->LocationID != 0) ? $log->LocationID : '-';

// ── Parameter Rows Build ──────────────────────────────────────────────────────
$rows = [
    ['Temperature (°C)', 'Temp',      '10–25 °C'],
    ['Humidity (%)',      'Humidity',  '< 60%'],
    ['Pest Activity',     'Pest',      'None'],
    ['Cleanliness',       'Clean',     'Clean'],
    ['Packaging',         'Packaging', 'Intact'],
];

$paramRows = '';
$count     = 0;
$notOkList = [];

foreach ($rows as [$label, $paramName, $range]) {
    $item    = $params[$paramName] ?? null;
    $reading = $item->ReadingValue ?? '';

    if ($paramName === 'Pest') {
        $reading = ($reading === 'Y') ? 'Yes' : (($reading === 'N') ? 'No' : $reading);
    } elseif ($paramName === 'Clean') {
        $reading = ($reading === 'Y') ? 'Good' : (($reading === 'N') ? 'Poor' : $reading);
    } elseif ($paramName === 'Packaging') {
        $reading = ($reading === 'Y') ? 'Intact' : (($reading === 'N') ? 'Damaged' : $reading);
    }

    $statusRaw   = $item->Status ?? '';
    $status      = ($statusRaw === 'Y') ? 'Ok' : (($statusRaw === 'N') ? 'Not Ok' : '');
    $statusColor = ($statusRaw === 'N') ? '#cc0000' : '#007700';

    $action  = htmlspecialchars($item->ActionTaken ?? '');
    $initial = htmlspecialchars($item->Initials   ?? '');
    $bg      = (++$count % 2 === 0) ? '#f0f0f0' : '#ffffff';

    if ($statusRaw === 'N') {
        $notOkList[] = $label;
    }

    $paramRows .= "
    <tr bgcolor=\"{$bg}\">
        <td width=\"22%\" style=\"border:1px solid #999999; padding:4px 5px; height:20px;\"><b>{$label}</b></td>
        <td width=\"12%\" style=\"border:1px solid #999999; padding:4px 5px; height:20px;\" align=\"center\">{$reading}</td>
        <td width=\"18%\" style=\"border:1px solid #999999; padding:4px 5px; height:20px;\" align=\"center\">{$range}</td>
        <td width=\"12%\" style=\"border:1px solid #999999; padding:4px 5px; height:20px; color:{$statusColor}; font-weight:bold;\" align=\"center\">{$status}</td>
        <td width=\"24%\" style=\"border:1px solid #999999; padding:4px 5px; height:20px;\">{$action}</td>
        <td width=\"12%\" style=\"border:1px solid #999999; padding:4px 5px; height:20px;\" align=\"center\">{$initial}</td>
    </tr>";
}

// ── Summary ───────────────────────────────────────────────────────────────────
$notOkCount   = count($notOkList);
$summaryColor = $notOkCount > 0 ? '#cc0000' : '#007700';
$summaryText  = $notOkCount > 0
    ? 'WARNING: ' . $notOkCount . ' parameter(s) out of range — ' . implode(', ', $notOkList) . '. Corrective action required.'
    : 'All parameters are within acceptable range.';

// ── Full HTML ─────────────────────────────────────────────────────────────────
$html = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">

    <!-- ===== HEADER: Logo LEFT + Company CENTER ===== -->
    <tr>
        <td colspan="6" style="border:1px solid #000000; padding:5px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="15%" align="left" valign="middle">
                        ' . $logoTag . '
                    </td>
                    <td width="70%" align="center" valign="middle">
                        <b style="font-size:15px;">' . htmlspecialchars($rc('company_name')) . '</b><br/>
                        <span style="font-size:10px;">' . htmlspecialchars($rc('address')) . ', ' . htmlspecialchars($rc('city')) . ' - ' . htmlspecialchars($rc('pincode')) . ', ' . htmlspecialchars($rc('state')) . '</span><br/>
                        <span style="font-size:10px;"><b>GSTIN:</b> ' . htmlspecialchars($rc('gst')) . ' &nbsp;|&nbsp; <b>Contact:</b> ' . htmlspecialchars($rc('mobile1')) . ' / ' . htmlspecialchars($rc('mobile2')) . '</span><br/>
                        <span style="font-size:10px;"><b>Email:</b> ' . htmlspecialchars($rc('email')) . '</span>
                    </td>
                    <td width="15%"></td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== TITLE ===== -->
    <tr bgcolor="#50607b">
        <td colspan="6" align="center" style="border:1px solid #000000; padding:6px; font-size:13px; color:#ffffff; height:26px;">
            <b>Daily Storage Monitoring Log</b>
        </td>
    </tr>

    <!-- ===== LOG INFO ===== -->
    <tr>
        <td width="15%" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0;"><b>Log ID</b></td>
        <td width="35%" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->LogID ?? '') . '</td>
        <td width="15%" style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0;"><b>Date</b></td>
        <td width="35%" colspan="3" style="border:1px solid #000000; padding:5px; height:20px;">' . $transDate . '</td>
    </tr>
    <tr>
        <td style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0;"><b>Warehouse ID</b></td>
        <td style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->WarehouseID ?? '') . '</td>
        <td style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0;"><b>Location ID</b></td>
        <td colspan="3" style="border:1px solid #000000; padding:5px; height:20px;">' . $locationID . '</td>
    </tr>
    <tr>
        <td style="border:1px solid #000000; padding:5px; height:20px; background-color:#e8ecf0;"><b>Remark</b></td>
        <td colspan="5" style="border:1px solid #000000; padding:5px; height:20px;">' . htmlspecialchars($log->Remark ?? '') . '</td>
    </tr>

    <!-- ===== TABLE HEADER ===== -->
    <tr bgcolor="#50607b">
        <td style="border:1px solid #000000; padding:5px; width:22%; color:#ffffff; height:22px;" align="center"><b>Parameter</b></td>
        <td style="border:1px solid #000000; padding:5px; width:12%; color:#ffffff; height:22px;" align="center"><b>Reading</b></td>
        <td style="border:1px solid #000000; padding:5px; width:18%; color:#ffffff; height:22px;" align="center"><b>Acceptable Range</b></td>
        <td style="border:1px solid #000000; padding:5px; width:12%; color:#ffffff; height:22px;" align="center"><b>OK / Not OK</b></td>
        <td style="border:1px solid #000000; padding:5px; width:24%; color:#ffffff; height:22px;" align="center"><b>Action Taken</b></td>
        <td style="border:1px solid #000000; padding:5px; width:12%; color:#ffffff; height:22px;" align="center"><b>Initials</b></td>
    </tr>

    <!-- ===== PARAMETER ROWS ===== -->
    ' . $paramRows . '

    <!-- ===== SUMMARY ===== -->
    <tr>
        <td colspan="6" style="border:1px solid #000000; padding:6px; height:20px; color:' . $summaryColor . '; font-weight:bold; font-size:11px;">
            ' . $summaryText . '
        </td>
    </tr>

    <!-- ===== SIGNATURE ===== -->
    <tr style="height:50px;">
        <td colspan="3" style="border:1px solid #000000; padding:5px;" align="center" valign="bottom">
            <b>Prepared By :</b>
        </td>
        <td colspan="3" style="border:1px solid #000000; padding:5px;" align="center" valign="bottom">
            <b>Authorized Sign :</b>
        </td>
    </tr>

    <!-- ===== PRINTED ON ===== -->
    <tr>
        <td colspan="3" style="border:1px solid #000000; padding:5px; height:18px; font-size:10px;">
            <b>Printed On :</b> ' . date('d/m/Y h:i A') . '
        </td>
        <td colspan="3" style="border:1px solid #000000; padding:5px; height:18px;"></td>
    </tr>

</table>';

$pdf->writeHTML($html, true, false, false, false, '');