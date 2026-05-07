<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('hsnmaster', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'pro_order_id',
    'recipe_name',
    'batch_qty',
	'required_time',
	'manager_name',
	'contractor_name',
	'TransDate',
	'production_status',
	'comment',
	'Finish_good_qty_new',
	'Finish_good_qty'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'production';
$where        = [];
$join = [];
$join = [
    'INNER JOIN '.db_prefix().'clients ON '.db_prefix().'clients.AccountID =IFNULL('.db_prefix().'production.manager_name,'.db_prefix().'production.contractor_name) AND '.db_prefix().'clients.PlantID='.db_prefix().'production.PlantID',
];
$additionalSelect = [
    db_prefix().'clients.company'
    ];


//array_push($where, 'AND ' . db_prefix() . 'hsn.PlantID ='. $selected_company);


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
	
	//$row[] = $aRow['pro_order_id'];
	if (has_permission_new('production', '', 'edit')) { 
	$numberOutput = '<a href="' . admin_url('production/production_order/' . $aRow['pro_order_id']) . '">' . $aRow['pro_order_id'] . '</a>';
	}else{
	    $numberOutput = $aRow['pro_order_id'];
	}
    $row[] = $numberOutput;
    $row[] = _d(substr($aRow['TransDate'],0,10));
	$row[] = $aRow['recipe_name'];
	$row[] = $aRow['batch_qty'];
	if(is_null($aRow['Finish_good_qty_new'])){
	    $f_g_qty = $aRow['Finish_good_qty'];
	}else{
	    $f_g_qty = $aRow['Finish_good_qty_new'];
	}
	$row[] = $f_g_qty;
	$row[] = $aRow['required_time'];
	if(is_null($aRow['manager_name'])){
	    $man_con_name = $aRow['contractor_name'];
	}else{
	    $man_con_name = $aRow['manager_name'];
	}
	$row[] = $aRow['company'];
	
	$row[] = $aRow['production_status'];
	$row[] = $aRow['comment'];
    
    $output['aaData'][] = $row;
}
