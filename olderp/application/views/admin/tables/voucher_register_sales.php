<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = []; 

$aColumns = array_merge($aColumns, [
    'Transdate',
    'SalesID',
    db_prefix().'salesmaster.AccountID',
    'PayType',
    'RndAmt',
    'SaleAmt',
    'DiscAmt',
    'BillAmt',
    'cgstamt',
    'sgstamt',
    'igstamt',
    
    
    ]);

$sIndexColumn = 'Transdate';
$sTable       = db_prefix() . 'salesmaster';

$where        = [];
// Add blank where all filter can be stored
$filter = [];
//$join = [];

$join = [
    'JOIN '.db_prefix().'clients ON '.db_prefix().'salesmaster.AccountID='.db_prefix().'clients.AccountID AND '.db_prefix().'salesmaster.PlantID='.db_prefix().'clients.PlantID',
    ];
 $join = hooks()->apply_filters('customers_table_sql_join', $join);
 
    if ($this->ci->input->post('from_date') && $this->ci->input->post('to_date')) {
        $from_date = to_sql_date($this->ci->input->post('from_date'));
        $to_date = to_sql_date($this->ci->input->post('to_date'));
        array_push($where, 'AND (Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:23:59")');
    }


$voucher_type = $this->ci->input->post('voucher_type');

$additionalSelect = [
    db_prefix().'clients.AccountID',
    db_prefix().'clients.company',
    db_prefix().'clients.address',
    ];
array_push($where, 'AND '.db_prefix().'salesmaster.PlantID = '.$selected_company);



$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

$total_credit = 0;
$total_debit = 0;
foreach ($rResult as $aRow) {
    
    $row = [];
       
   // $row[] = $aRow['PassedFrom']; 
    $row[] = $aRow['SalesID'];   
    $date = substr($aRow['Transdate'],0,10);
    $row[] = _d($date);
    //$row[] = $aRow['AccountID'];
    $account_name = get_account_name_for_voucher($aRow['AccountID'],$selected_company);
    //$row[] = $account_name->company;
    $row[] = $aRow['company'];
    //$row[] = $account_name->StationName;
    $row[] = $aRow['address'];
    $row[] = number_format($aRow['SaleAmt'],2);
    $row[] = number_format($aRow['DiscAmt'],2);
    if($aRow['sgstamt']!=0 || $aRow['cgstamt']!=0){
        $tax= $aRow['sgstamt'] + $aRow['cgstamt'];
    }else{
        $tax= $aRow['igstamt'];
    }
    $row[] = number_format($tax,2);
    $row[] = "";
    $roundff=($aRow['BillAmt']-$aRow['RndAmt']);
    $round_off=round($roundff,2);
    $row[] = number_format($round_off,2);
    $row[] = number_format($aRow['BillAmt'],2);
    
    $SaleAmt = $SaleAmt + $aRow['SaleAmt'];
    $DiscAmt = $DiscAmt + $aRow['DiscAmt'];
    //$TaxAmt = $TaxAmt + $aRow['cgstamt'];
    $TaxAmt += $tax;
    $BillAmt = $BillAmt + $aRow['BillAmt'];
    $round_off1 += $round_off;
    
   
    $row[] = "";
   // $row[] = "";
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
   
}
$row1 = [];
$row1[] = "";
$row1[] = "";
$row1[] = "";
$row1[] = '<span style="color:red;">Total</span>';
$row1[] = '<span style="color:red;">'.number_format($SaleAmt,2).'</span>';
$row1[] = '<span style="color:red;">'.number_format($DiscAmt,2).'</span>';
$row1[] = '<span style="color:red;">'.number_format($TaxAmt,2).'</span>';
$row1[] = "";
$row1[] = '<span style="color:red;">'.number_format($round_off1,2).'</span>';  
$row1[] = '<span style="color:red;">'.number_format($BillAmt,2).'</span>';

$row1['DT_RowClass'] = 'has-row-options';

$output['aaData'][] = $row1;


