<?php
defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$fy = $this->ci->session->userdata('finacial_year');
$this->ci->db->query("SET sql_mode = ''");
$aColumns = [
    db_prefix() . 'challanmaster.ChallanID as challan_no',
    'RouteID',
    'VehicleID',
    'DriverID',
    'Crates',
    'Cases',
    'ChallanAmt',
    'BillAmt'
    ];
$sIndexColumn = 'ChallanID';
$sTable       = db_prefix() . 'challanmaster';

$where  = [];
$join = [
    'INNER JOIN '.db_prefix().'salesmaster ON '.db_prefix().'salesmaster.ChallanID='.db_prefix().'challanmaster.ChallanID AND '.db_prefix().'challanmaster.PlantID='.db_prefix().'salesmaster.PlantID AND '.db_prefix().'challanmaster.FY='.db_prefix().'salesmaster.FY',
    'LEFT JOIN '.db_prefix().'clients ON '.db_prefix().'clients.AccountID ='.db_prefix().'salesmaster.AccountID AND '.db_prefix().'salesmaster.PlantID='.db_prefix().'clients.PlantID'
    ];

$join = hooks()->apply_filters('customers_table_sql_join', $join);

array_push($where, 'AND ' . db_prefix() . 'challanmaster.PlantID ='. $selected_company);
//array_push($where, 'AND ' . db_prefix() . 'challanmaster.FY ='.$fy);

$from_date = to_sql_date($this->ci->input->post('from_date'));
$to_date = to_sql_date($this->ci->input->post('to_date'));
array_push($where, 'AND ' . db_prefix() . 'challanmaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');


$aColumns = hooks()->apply_filters('customers_table_sql_columns', $aColumns);
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'company',
    'StationName',
    'state',
    'city',
    ]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    $numberOutput = '<a href="' . admin_url('challan/UpdateChallan/' . $aRow['challan_no']) . '" target="_blank">' . $aRow['challan_no'] . '</a>';
    if (has_permission_new('challan_list', '', 'view')) {
        $row[] = $numberOutput;
    }else{
        $row[] = $aRow['challan_no'];
    }
    $row[] = $aRow['company'];
    $row[] = $aRow['StationName'];
    $row[] = $aRow['state'];
    
    $city_name_detail = get_city_name($aRow['city']);
        if(empty($city_name_detail)){
            $city_name_name = $aRow['city'];
        }else {
            $city_name_name = $city_name_detail->city_name;
        }
        
    $row[] = $city_name_name;
    $row[] = $aRow['VehicleID'];
    $row[] = get_route_name($aRow['RouteID'],$selected_company);
    $row[] = $aRow['Cases'];
    $row[] = $aRow['Crates'];
    $row[] = $aRow['BillAmt'];
    
    
    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}