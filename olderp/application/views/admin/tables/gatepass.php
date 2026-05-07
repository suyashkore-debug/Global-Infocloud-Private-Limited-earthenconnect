<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$fy = $this->ci->session->userdata('finacial_year');
$aColumns = [
    'ChallanID',
    'RouteID',
    'VehicleID',
    'DriverID',
    'Crates',
    'Cases',
    'ChallanAmt',
    'UserID',
    ];
$sIndexColumn = 'ChallanID';
$sTable       = db_prefix() . 'challanmaster';

$where  = [];
$filter = [];
$join = [];

array_push($where, 'AND ' . db_prefix() . 'challanmaster.PlantID ='. $selected_company);

if (!has_permission('challan', '', 'view')) {
    $userWhere = 'AND ' . get_challan_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

if ($this->ci->input->post('date')) {
    $order_date = to_sql_date($this->ci->input->post('date'));
    array_push($where, 'AND ' . db_prefix() . 'challanmaster.Transdate <= "'.$order_date.' 23:59:00"');
   
}


array_push($where, 'AND ' . db_prefix() . 'challanmaster.FY ='. $fy);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    $numberOutput = '<a href="' . admin_url('challan/gatepass/' . $aRow['ChallanID']) . '?output_type=I" target="_blank">' . $aRow['ChallanID'] . '</a>';
    $row[] = $numberOutput;
    $row[] = get_staff_full_name($aRow['UserID']);
    $row[] = $aRow['VehicleID'];
    $row[] = $aRow['DriverID'];
    $row[] = get_route_name($aRow['RouteID'],$selected_company);
    $row[] = $aRow['Cases'];
    $row[] = $aRow['Crates'];
    $row[] = $aRow['ChallanAmt'];
    
    
    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}