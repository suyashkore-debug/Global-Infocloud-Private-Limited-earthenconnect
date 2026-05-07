<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$fy = date('y');
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
//array_push($where, 'AND ' . db_prefix() . 'ordermaster.OrderStatus = "O"');
array_push($where, 'AND ' . db_prefix() . 'ordermaster.FY ='.$fy);
array_push($where, 'AND ' . db_prefix() . 'ordermaster.ChallanID IS NULL');

if (!has_permission('orders', '', 'view')) {
    $userWhere = 'AND ' . get_order_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    $numberOutput = '<a href="' . admin_url('order/pending_orders2/' . $aRow['OrderID']) . '" onclick="init_order(' . $aRow['OrderID'] . '); return false;">' . $aRow['OrderID'] . '</a>';
    $row[] = $numberOutput;
    $row[] = substr($aRow['Transdate'],0,10);
    //$row[] = $aRow['SalesID'];
    //$row[] = substr($aRow['Transdate'],0,10);
    //$row[] = $aRow['ChallanID'];
    $account_name = get_account_name($aRow['AccountID'],$selected_company);
    $row[] = $account_name->company;
    $StationName = get_station_name($aRow['AccountID'],$selected_company);
    $row[] = $StationName->StationName;
    $short_name = get_state_name_by_acc_id($aRow['AccountID'],$selected_company);
    $row[] = $short_name;
    
    $name = get_dist_name_by_acc_id($aRow['AccountID'],$selected_company);
    $row[] = $name;
    $row[] = $aRow['OrderAmt'];
    $actbal = get_bal_by_acc_id($aRow['AccountID'],$selected_company);
    
    $month = date('m');
            if($month == "01"){
               $m = 2; 
            }
            if($month == "02"){
               $m = 3; 
            }
            if($month == "03"){
               $m = 4; 
            }
            if($month == "04"){
               $m = 5; 
            }
            if($month == "05"){
               $m = 6; 
            }
            if($month == "06"){
               $m = 7; 
            }
            if($month == "07"){
               $m = 8; 
            }
            if($month == "08"){
               $m = 9; 
            }
            if($month == "09"){
               $m = 10; 
            }
            if($month == "10"){
               $m = 11; 
            }
            if($month == "11"){
               $m = 12; 
            }
            if($month == "12"){
               $m = 13; 
            }
            $mm = "BAL".$m;
    $row[] = $actbal->$mm;
    
    //$row[] = $aRow['Cases'];
    
    $row[] = $aRow['order_type'];
    $itemcodeOutput = '<a href="#" data-toggle="modal" data-target="#remark_modal" data-id="' . $aRow['OrderID'] . '"><span class="fa fa-pencil"></span>  </a>'.$aRow['remark'];
    
    $row[] = $itemcodeOutput;
    
    
    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}