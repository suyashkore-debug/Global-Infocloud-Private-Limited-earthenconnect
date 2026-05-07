<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    'description',
    'long_description',
    'item_code',
    db_prefix() . 'items.rate as rate',
    't1.taxrate as taxrate_1',
    't2.taxrate as taxrate_2',
    'unit',
    db_prefix() . 'items_groups.name as group_name',
    'subgroup_id',
    db_prefix() . 'items_sub_groups.name as subgroup_name',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'items';
$where        = [];
$join = [
    'LEFT JOIN ' . db_prefix() . 'taxes t1 ON t1.id = ' . db_prefix() . 'items.tax',
    'LEFT JOIN ' . db_prefix() . 'taxes t2 ON t2.id = ' . db_prefix() . 'items.tax2',
    'LEFT JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
    'LEFT JOIN ' . db_prefix() . 'items_sub_groups ON ' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id',
    ];
$additionalSelect = [
    db_prefix() . 'items.id',
    't1.name as taxname_1',
    't2.name as taxname_2',
    't1.id as tax_id_1',
    't2.id as tax_id_2',
    'group_id','subgroup_id',
    ];
array_push($where, 'AND ('.db_prefix().'items.PlantID = '.$selected_company.')');

$custom_fields = get_custom_fields('items',array('show_on_table'=>1));

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'items.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="items_pr" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   // $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    //$row[] = $aRow['item_code'];
    $itemcodeOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['item_code'] . '</a>';
    $itemcodeOutput .= '<div class="row-options">';

    if (has_permission('items', '', 'edit')) {
        $itemcodeOutput .= '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
    }

    if (has_permission('items', '', 'delete')) {
        $itemcodeOutput .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $itemcodeOutput .= '</div>';
    if (has_permission('items', '', 'edit')) {
    $row[] = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['item_code'] . '">'.$aRow['item_code'].'</a>';
    
    }else{
        $row[] = $aRow['item_code'];
    }//$descriptionOutput = '';
    //$descriptionOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    

    //$row[] = $descriptionOutput;
    if (has_permission('items', '', 'edit')) {
    $row[] = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['item_code'] . '">'.$aRow['description'].'</a>';
    
    }else{
        $row[] = $aRow['description'];
    }
    
    //$row[] = $aRow['long_description'];

    /*$row[] = app_format_money($aRow['rate'], get_base_currency());

    $aRow['taxrate_1'] = $aRow['taxrate_1'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_1'] . '" data-taxid="' . $aRow['tax_id_1'] . '">' . app_format_number($aRow['taxrate_1']) . '%' . '</span>';

    $aRow['taxrate_2'] = $aRow['taxrate_2'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_2'] . '" data-taxid="' . $aRow['tax_id_2'] . '">' . app_format_number($aRow['taxrate_2']) . '%' . '</span>';*/
    $row[]             = $aRow['unit'];

    $row[] = $aRow['group_name'];
    $row[] = $aRow['subgroup_name'];
    

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
    /*$action = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['item_code'] . '"><i class="fa fa-pencil"></i></a>';
    $action .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['item_code']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    $row[] = $action;*/

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
