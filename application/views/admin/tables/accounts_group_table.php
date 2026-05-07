<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

$aColumns = array_merge($aColumns, [
    'ActGroupID',
    'ActGroupName',
    ]);

$sIndexColumn = 'ActGroupID';
$sTable       = db_prefix() . 'accountgroups';
$where  = [];
$join = [];


$additionalSelect = [];



$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   
    $row[] = $aRow['ActGroupID'];
    
    $row[] = $aRow['ActGroupName'];
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
