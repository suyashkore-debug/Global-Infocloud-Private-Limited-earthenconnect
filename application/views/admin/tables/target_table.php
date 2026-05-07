<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('hsnmaster', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'id',
	'staff_id',
    'year',
    'month',
    'total'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'target_vs_achievement';
$where        = [];
$join = [];
$additionalSelect = [];


//array_push($where, 'AND ' . db_prefix() . 'hsn.PlantID ='. $selected_company);


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	
	if($aRow['month']==1){
				$mon="January";
			}elseif($aRow['month']==2){
				$mon="February";
			}elseif($aRow['month']==3){
				$mon="March";
			}elseif($aRow['month']==4){
				$mon="April";
			}elseif($aRow['month']==5){
				$mon="May";
			}elseif($aRow['month']==6){
				$mon="June";
			}elseif($aRow['month']==7){
				$mon="July";
			}elseif($aRow['month']==9){
				$mon="September";
			}elseif($aRow['month']==10){
				$mon="October";
			}elseif($aRow['month']==11){
				$mon="November";
			}elseif($aRow['month']==12){
				$mon="December";
			}else{
				$mon="August";
			}
	
	
    $row = [];
	 //$numberOutput = '<a href="' . admin_url('production/editRecipe/' . $aRow['id']) . '">' . $aRow['item_description'] . '</a>';
    //$row[] = $numberOutput;

   // $row[] = $aRow['item_description'];
   $staff_name = get_staff_name($aRow['staff_id']);
    $row[] = $staff_name->firstname." ".$staff_name->lastname;
	//$row[] = $aRow['staff_id'];
	$row[] = $aRow['year'];
	$row[] = $mon;
	$row[] = $aRow['total'];
	$action = '<a href="' . admin_url('target/editTarget/' . $aRow['id']) . '"><i class="fa fa-pencil"></i></a>';
	//$action = '<a href="' . admin_url('target/editTarget/' . $aRow['id']) . '">' . $aRow['staff_id'] . '</a>';
	$row[] = $action;
    
    $output['aaData'][] = $row;
}
