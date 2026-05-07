<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

$aColumns = array_merge($aColumns, [
    'Transdate',
    'VoucherID',
    'AccountID',
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
/*$join = [
    'INNER JOIN ' . db_prefix() . 'taxes t1 ON t1.id = ' . db_prefix() . 'items.tax',
    'left JOIN ' . db_prefix() . 'rate_master t3 ON t3.item_id = ' . db_prefix() . 'items.item_code AND t3.PlantID = ' . db_prefix() . 'items.PlantID',
    'INNER JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
    'INNER JOIN ' . db_prefix() . 'items_sub_groups ON ' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id',
    ];*/
    
    if ($this->ci->input->post('voucher_type')) {
    $voucher_type = trim($this->ci->input->post('voucher_type'));
    array_push($where, 'AND PassedFrom ="'.$voucher_type.'"');
   
    }
    
    if ($this->ci->input->post('from_date') && $this->ci->input->post('to_date')) {
        $from_date = $this->ci->input->post('from_date');
        $to_date = $this->ci->input->post('to_date');
        array_push($where, 'AND (Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:23:59")');
    }


$voucher_type = $this->ci->input->post('voucher_type');
//$distributor_id = $this->ci->input->post('distributor_id');
/*$additionalSelect = [
    db_prefix() . 'items.id',
    't1.name as taxname_1',
    't3.assigned_rate as assigned_2',
    't3.item_id as rate_id',
    't1.id as tax_id_1',
    't3.item_id as item_id_2',
    't3.id as rate_master_id',
    't3.state_id as state_id_2',
    't3.distributor_id as distributor_id_2',
    't3.groups_id as groups_id_2',
    't3.effective_date as effective_date_2',
    'group_id',
    'subgroup_id',
    ];*/
$additionalSelect = [];
array_push($where, 'AND '.db_prefix().'accountledger.PlantID = '.$selected_company);



$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

$total_credit = 0;
$total_debit = 0;
foreach ($rResult as $aRow) {
    
    $row = [];
       
    $row[] = $aRow['VoucherID'];
    $date = substr($aRow['Transdate'],0,10);
    $row[] = $date;
    $row[] = $aRow['AccountID'];
    $account_name = get_account_name($aRow['AccountID'],$selected_company);
    $row[] = $account_name->company;
    
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
    $row[] = $debit_amt;
    $row[] = $credit_amt;
    $row[] = $aRow['Narration'];
    $row[] = "";
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
   
}
$row1 = [];
$row1[] = "";
$row1[] = "";
$row1[] = "";
$row1[] = '<span style="color:red;">Total</span>';
$row1[] = "";
$row1[] = '<span style="color:red;">'.$total_debit.'</span>';
$row1[] = '<span style="color:red;">'.$total_credit.'</span>';

$row1[] = "";
$row1[] = "";

$row1['DT_RowClass'] = 'has-row-options';

$output['aaData'][] = $row1;


