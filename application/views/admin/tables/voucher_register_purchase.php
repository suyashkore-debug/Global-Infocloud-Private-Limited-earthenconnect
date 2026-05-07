<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$fy = $this->ci->session->userdata('finacial_year');
$aColumns = []; 

$aColumns = array_merge($aColumns, [
    'Transdate',
    'PurchID',
    db_prefix().'purchasemaster.AccountID',
    'Invoiceno',
    'Purchamt',
    'Discamt',
    'Excamt',
    'Vatamt',
    'Cstamt',
    'cgstamt',
    'sgstamt',
    'igstamt',
    'RoundOffAmt',
    'Invamt',
    'Frtamt'
    ]);

$sIndexColumn = 'PurchID';
$sTable       = db_prefix() . 'purchasemaster';

$where        = [];
// Add blank where all filter can be stored
$filter = [];
//$join = [];

$join = [
    'JOIN '.db_prefix().'clients ON '.db_prefix().'purchasemaster.AccountID='.db_prefix().'clients.AccountID AND '.db_prefix().'purchasemaster.PlantID='.db_prefix().'clients.PlantID',
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
array_push($where, 'AND '.db_prefix().'purchasemaster.PlantID = '.$selected_company);
array_push($where, 'AND '.db_prefix().'purchasemaster.FY = '.$fy);



$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];


$purch_amt_total = 0.00;
$DiscAmt = 0.00;
$TaxAmt = 0.00;
$freight_amt_total = 0.00;
$round_off1 = 0.00;
$InvAmt = 0.00;
foreach ($rResult as $aRow) {
    
    $row = [];
       
   // $row[] = $aRow['PassedFrom']; 
    $row[] = $aRow['PurchID'];   
    $date = _d(substr($aRow['Transdate'],0,10));
    $row[] = $date;
    $row[] = $aRow['Invoiceno'];
    //$row[] = $account_name->company;
    $row[] = $aRow['company'];
    //$row[] = $account_name->StationName;
    $row[] = number_format($aRow['Purchamt'],2);
    $purch_amt_total = $purch_amt_total + $aRow['Purchamt'];
    $row[] = number_format($aRow['Discamt'],2);
    $DiscAmt = $DiscAmt + $aRow['Discamt'];
    $row[] = number_format($aRow['Excamt'],2);
    $row[] = number_format($aRow['Cstamt'],2);
    if($aRow['sgstamt']!=0 || $aRow['cgstamt']!=0){
        $tax= $aRow['sgstamt'] + $aRow['cgstamt'];
    }else{
        $tax= $aRow['igstamt'];
    }
    $row[] = number_format($tax,2);
    $TaxAmt = $TaxAmt + $tax;
    $row[] = "";
    $row[] = number_format($aRow['Frtamt'],2);
    $freight_amt_total = $freight_amt_total + $aRow['Frtamt'];
    $row[] = number_format($aRow['RoundOffAmt'],2);
    $round_off1 = $round_off1 + $aRow['RoundOffAmt'];
    $row[] = number_format($aRow['Invamt'],2);
    $InvAmt = $InvAmt + $aRow['Invamt'];
    
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
   
}
$row1 = [];
$row1[] = "";
$row1[] = "";
$row1[] = "";
$row1[] = '<span style="color:red;">Total</span>';
$row1[] = '<span style="color:red;">'.number_format($purch_amt_total,2).'</span>';
$row1[] = '<span style="color:red;">'.number_format($DiscAmt,2).'</span>';
$row1[] = "";
$row1[] = "";
$row1[] = '<span style="color:red;">'.number_format($TaxAmt,2).'</span>';
$row1[] = "";

$row1[] = '<span style="color:red;">'.number_format($freight_amt_total,2).'</span>';
$row1[] = '<span style="color:red;">'.number_format($round_off1,2).'</span>';  
$row1[] = '<span style="color:red;">'.number_format($InvAmt,2).'</span>';

$row1['DT_RowClass'] = 'has-row-options';

$output['aaData'][] = $row1;


