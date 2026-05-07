<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('vehiclemaster', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'VehicleID',
    'VehicleTypeID',
    'VehicleCapacity',
    'EngageID',
    'StartDate',
    'ActiveYN',
    ]);

$sIndexColumn = 'VehicleID';
$sTable       = db_prefix() . 'vehicle';
$where        = [];
$join = [];
$additionalSelect = [];

array_push($where, 'AND ('.db_prefix().'vehicle.PlantID = '.$selected_company.')');




$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   // $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    //$row[] = $aRow['item_code'];
    $itemcodeOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['VehicleID'] . '">' . $aRow['item_code'] . '</a>';
    $itemcodeOutput .= '<div class="row-options">';

    if (has_permission('vehiclemaster', '', 'edit')) {
        $itemcodeOutput .= '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['VehicleID'] . '">' . _l('edit') . '</a>';
    }

    if (has_permission('vehiclemaster', '', 'delete')) {
        $itemcodeOutput .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['VehicleID']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $itemcodeOutput .= '</div>';
    $row[] = $aRow['VehicleID'];
    //$descriptionOutput = '';
    //$descriptionOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    

    //$row[] = $descriptionOutput;
    $row[] = $aRow['VehicleTypeID'];

    //$row[] = $aRow['long_description'];

    /*$row[] = app_format_money($aRow['rate'], get_base_currency());

    $aRow['taxrate_1'] = $aRow['taxrate_1'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_1'] . '" data-taxid="' . $aRow['tax_id_1'] . '">' . app_format_number($aRow['taxrate_1']) . '%' . '</span>';

    $aRow['taxrate_2'] = $aRow['taxrate_2'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_2'] . '" data-taxid="' . $aRow['tax_id_2'] . '">' . app_format_number($aRow['taxrate_2']) . '%' . '</span>';*/
    $row[]             = $aRow['VehicleCapacity'];
    $startdate = substr(_d($aRow['StartDate']),0,10);
    $row[] = $startdate;
    if($aRow['ActiveYN'] == "1"){
        $status = "Y";
    }else{
        $status = "N";
    }
    $row[] = $status;
    

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
    if (has_permission_new('vehiclemaster', '', 'edit')) {
    $action = '<a href="#" data-toggle="modal" data-target="#vehicle_modal" data-id="' . $aRow['VehicleID'] . '"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_new('vehiclemaster', '', 'delete')) {
    $action .= ' | <a href="' . admin_url('vehicles/delete/' . $aRow['VehicleID']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $action;

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
