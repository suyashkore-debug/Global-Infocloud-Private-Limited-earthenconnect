<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('hsnmaster', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'id',
    'tcs',
    'EffDate',
    'UserId'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'tcsmaster';
$where        = [];
$join = [];
$additionalSelect = [];



//array_push($where, 'AND ' . db_prefix() . 'hsn.PlantID ='. $selected_company);


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
$count = count($rResult);
$i = 1;
foreach ($rResult as $aRow) {
    
    $date = substr($aRow['EffDate'],0,10);
    $cur_date = date('Y-m-d');
    if($date<=$cur_date){
        $active = "Active";
        $tcs_id = $aRow['id'];
    }
}
foreach ($rResult as $aRow) {
    $row = [];
    
   
    $row[] = $aRow['tcs'];
    
    $row[] = substr($aRow['EffDate'],0,10);
    $full_name = get_staff_name($aRow['UserId']);
    if(empty($full_name)){
        $row[] = $aRow['UserId'];
    }else{
        $row[] = $full_name->firstname .' '.$full_name->lastname;
    }
    
    if($tcs_id == $aRow['id']){
        $row[] = "Active";
    }else{
        $row[] = "DeActive";
    }
   

    /*$date = substr($aRow['EffDate'],0,10);
    $cur_date = date('Y-m-d');
    if($date<=$cur_date){
        $row[] = "Active";
    }else{
        $row[] = "DeActive";
    }*/
    
    /*if($count == $i){
        $row[] = "Active";
    }else{
        $row[] = "DeActive";
    }*/
    
    
    /*if($aRow['status'] == "1"){
        $status = "Y";
    }else{
        $status = "N";
    }
    $row[] = $status;*/
    

    $i++;

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
