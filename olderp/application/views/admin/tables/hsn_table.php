<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('hsnmaster', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'id',
    'name',
    'hsndesc',
    'created_date',
    'status',
    'UserID2',
    'Lupdate'
    ]);

$sIndexColumn = 'name';
$sTable       = db_prefix() . 'hsn';
$where        = [];
$join = [];
$additionalSelect = [];



//array_push($where, 'AND ' . db_prefix() . 'hsn.PlantID ='. $selected_company);


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   if (has_permission('hsnmaster', '', 'edit')) {
    $itemcodeOutput = '<a href="#" data-toggle="modal" data-target="#hsn_modal" data-id="' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
   }else{
       $itemcodeOutput = $aRow['name'];
   }

    //$itemcodeOutput .= '</div>';
    //$row[] = $aRow['name'];
    $row[] = $itemcodeOutput;
    $row[] = $aRow['hsndesc'];
    //$descriptionOutput = '';
    //$descriptionOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    

    //$row[] = $descriptionOutput;
    $row[] = $aRow['created_date'];

    //$row[] = $aRow['UserID2'];
    //$row[] = $aRow['Lupdate'];

    /*$row[] = app_format_money($aRow['rate'], get_base_currency());

    $aRow['taxrate_1'] = $aRow['taxrate_1'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_1'] . '" data-taxid="' . $aRow['tax_id_1'] . '">' . app_format_number($aRow['taxrate_1']) . '%' . '</span>';

    $aRow['taxrate_2'] = $aRow['taxrate_2'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_2'] . '" data-taxid="' . $aRow['tax_id_2'] . '">' . app_format_number($aRow['taxrate_2']) . '%' . '</span>';*/
    //$row[]             = $aRow['capacity'];

    //$row[] = $aRow['start_date'];
    /*if($aRow['status'] == "1"){
        $status = "Y";
    }else{
        $status = "N";
    }
    $row[] = $status;*/
    

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
    if (has_permission('hsnmaster', '', 'edit')) {
    $action = '<a href="#" data-toggle="modal" data-target="#hsn_modal" data-id="' . $aRow['id'] . '"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission('hsnmaster', '', 'delete')) {
    $action .= ' | <a href="' . admin_url('hsn_master/delete/' . $aRow['id']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    }
    //$row[] = $action;

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
