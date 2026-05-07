<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$fy = $this->ci->session->userdata('finacial_year');
//$fy = date('y');
$aColumns = [
    'OrderID',
    'SalesID',
    'Transdate',
    'ChallanID',
    'AccountID',
    'OrderAmt',
    'subtotal',
    'total_tax',
    'order_type',
    'Crates',
    'Cases',
    'remark',
    
    ];
$sIndexColumn = 'OrderID';
$sTable       = db_prefix() . 'ordermaster';

$where  = [];
$filter = [];
$join = [];
/*$join = [
    'LEFT JOIN '.db_prefix().'clients ON '.db_prefix().'clients.AccountID ='.db_prefix().'ordermaster.AccountID AND '.db_prefix().'clients.PlantID='.db_prefix().'ordermaster.PlantID',
];*/

/*$join = [
    'LEFT JOIN '.db_prefix().'clients ON '.db_prefix().'ordermaster.AccountID ='.db_prefix().'clients.AccountID AND '.db_prefix().'ordermaster.PlantID='.db_prefix().'clients.PlantID',
];*/

array_push($where, 'AND ' . db_prefix() . 'ordermaster.PlantID ='. $selected_company);
//array_push($where, 'AND ' . db_prefix() . 'ordermaster.OrderStatus = "C"');
array_push($where, 'AND ' . db_prefix() . 'ordermaster.FY ='.$fy);
array_push($where, 'AND ' . db_prefix() . 'ordermaster.ChallanID IS NOT NULL');

if (!has_permission_new('orders', '', 'view')) {
    $userWhere = 'AND ' . get_order_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    $numberOutput = '<a href="' . admin_url('order/order_details/' . $aRow['OrderID']) . '" target="_blank">' . $aRow['OrderID'] . '</a>';
    $row[] = $numberOutput;
    $row[] = date("d/m/Y", strtotime(substr($aRow['Transdate'],0,10)));
    $row[] = $aRow['SalesID'];
    $row[] = date("d/m/Y", strtotime(substr($aRow['Transdate'],0,10)));
    $row[] = $aRow['ChallanID'];
    $challan_detail = get_bill_amt($aRow['ChallanID'],$selected_company);
    $row[] = $challan_detail->ChallanAmt;
    $account_name = get_account_name($aRow['AccountID'],$selected_company);
    $row[] = $account_name->company;
    //$row[] = "test";
    //$row[] = $aRow['Cases'];
    $row[] = $aRow['OrderAmt'];
    $row[] = $aRow['order_type'];
    //$row[] = $aRow['order_type'];
    
    $row[] = $aRow['remark'];
    
    
    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}