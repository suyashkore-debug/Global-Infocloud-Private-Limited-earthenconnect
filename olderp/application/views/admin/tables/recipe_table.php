<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('hsnmaster', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'id',
    'item_description',
    'item_code',
    'qty',
	'unit'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'recipe';
$where        = [];
$join = [];
$additionalSelect = [];


//array_push($where, 'AND ' . db_prefix() . 'hsn.PlantID ='. $selected_company);


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    if (has_permission('recipe', '', 'edit')) {
	 $numberOutput = '<a href="' . admin_url('production/editRecipe/' . $aRow['id']) . '">' . $aRow['item_description'] . '</a>';
    }else{
        $numberOutput = $aRow['item_description'];
    }
    $row[] = $numberOutput;

   // $row[] = $aRow['item_description'];
	$row[] = $aRow['item_code'];
	$row[] = $aRow['qty'];
	$row[] = $aRow['unit'];
    
    $output['aaData'][] = $row;
}
