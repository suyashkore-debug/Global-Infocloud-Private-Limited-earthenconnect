<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

$aColumns = array_merge($aColumns, [
    'Transdate',
    'VoucherID',
    db_prefix().'accountledger.AccountID',
    'TType',
    'Amount',
    'Narration',
    'PassedFrom',
    ]);

$sIndexColumn = 'VoucherID';
$sTable       = db_prefix() . 'accountledger';

$where        = [];
// Add blank where all filter can be stored
$filter = [];
$join = [];
$join = [
    'JOIN '.db_prefix().'clients ON '.db_prefix().'accountledger.AccountID='.db_prefix().'clients.AccountID AND '.db_prefix().'accountledger.PlantID='.db_prefix().'clients.PlantID',
    ];
    if ($this->ci->input->post('voucher_type')) {
    $voucher_type = trim($this->ci->input->post('voucher_type'));
    array_push($where, 'AND PassedFrom ="'.$voucher_type.'"');
    }
    
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
array_push($where, 'AND '.db_prefix().'accountledger.PlantID = '.$selected_company);



$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

$total_credit = 0;
$total_debit = 0;
foreach ($rResult as $aRow) {
    
    $row = [];
       
    $row[] = $aRow['VoucherID'];   
    // $row[] = $aRow['VoucherID'];   
    $date = substr($aRow['Transdate'],0,10);
    $row[] = _d($date);
    $row[] = $aRow['AccountID'];
    
    $row[] = $aRow['company'];
    
    if($aRow['TType'] == "C"){
        $credit_amt = $aRow['Amount'];
        $debit_amt = "";
        $dr_cr = "Cr";
        $total_credit = $total_credit + $aRow['Amount'];
    }else {
        $credit_amt = "";
        $debit_amt = $aRow['Amount'];
        $dr_cr = "Dr";
        $total_debit = $total_debit + $aRow['Amount'];
    }
    $row[] = $dr_cr;
    $row[] = number_format($debit_amt,2);
    $row[] = number_format($credit_amt,2);
    $row[] = $aRow['Narration'];
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
$row1[] = "";
$row1[] = '<span style="color:red;">'.number_format($total_debit,2).'</span>';
$row1[] = '<span style="color:red;">'.number_format($total_credit,2).'</span>';

$row1[] = "";
$row1[] = "";

$row1['DT_RowClass'] = 'has-row-options';

$output['aaData'][] = $row1;


