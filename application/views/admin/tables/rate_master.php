<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

if (has_permission('ratemaster', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    'description',
    'long_description',
    'item_code',
    db_prefix() . 'items.rate as rate',
    't1.taxrate as taxrate_1',
    't3.assigned_rate as assigned_rate_2',
    'unit',
    db_prefix() . 'items_groups.name as group_name',
    'subgroup_id',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'items';

$where        = [];
// Add blank where all filter can be stored
$filter = [];

$join = [
    'INNER JOIN ' . db_prefix() . 'taxes t1 ON t1.id = ' . db_prefix() . 'items.tax',
    'left JOIN ' . db_prefix() . 'rate_master t3 ON t3.item_id = ' . db_prefix() . 'items.item_code AND t3.PlantID = ' . db_prefix() . 'items.PlantID',
    'INNER JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
    'INNER JOIN ' . db_prefix() . 'items_sub_groups ON ' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id',
    ];
    
    if ($this->ci->input->post('states')) {
    $state = trim($this->ci->input->post('states'));
    array_push($where, 'AND t3.state_id ="' .$state.'"');
   array_push($where, 'AND t3.distributor_id ='. $this->ci->input->post('distributor_id'));
   //array_push($where, 'GROUP BY' . db_prefix() . 'items.id');
}


$state = $this->ci->input->post('states');
$distributor_id = $this->ci->input->post('distributor_id');
$additionalSelect = [
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
    ];
array_push($where, 'AND ('.db_prefix().'items.PlantID = '.$selected_company.')');
$custom_fields = get_custom_fields('items');

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

$aa = array();
$i=1;
foreach ($rResult as $aRow) {
    
    /*if(($aRow["state_id_2"]==$state && $aRow["distributor_id_2"]==$distributor_id) || ($aRow["state_id_2"] == "" && $aRow["distributor_id_2"]== "") || ($aRow["state_id_2"] !== $state && $aRow["distributor_id_2"] !== $distributor_id)){
     */
     if($i==1 || ($aRow['item_code'] !== $name)){
         $name= $aRow['item_code'];
    
    $row = [];
       
        //$row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';

    $descriptionOutput = '';
    $descriptionOutput = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    $descriptionOutput .= '<div class="row-options">';

    if (has_permission('ratemaster', '', 'edit')) {
        $descriptionOutput .= '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
    }

    if (has_permission('ratemaster', '', 'delete')) {
        $descriptionOutput .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $descriptionOutput .= '</div>';
    
    if (has_permission('ratemaster', '', 'edit')) {
        
        $row[] = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $aRow['rate_id'] . '">' . $aRow['item_code'] . '</a>';
        
    }else {
        $row[] = $aRow['item_code'];
    }
    if (has_permission('ratemaster', '', 'edit')) {
        
        $row[] = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $aRow['rate_id'] . '">' . $aRow['description'] . '</a>';
        
    }else {
        $row[] = $aRow['description'];
    }
   // $row[] = $descriptionOutput;
    
    
    $row[]             = $aRow['unit'];
    //$row[] = app_format_money($aRow['rate'], get_base_currency());
    

    /*$row[] = $aRow['group_name'];
    $row[] = $aRow['subgroup_id'];*/
    if($aRow['state_id_2'] == !"" && $aRow['state_id_2'] !== "0" && $aRow['state_id_2'] == $state && $aRow['distributor_id_2'] == !"" && $aRow['distributor_id_2'] !== "0" && $aRow['distributor_id_2'] == $distributor_id ){
        $new_rate = $aRow['assigned_2'];
    } else {
        $new_rate = 0;
    }
    if (has_permission('ratemaster', '', 'edit')) {
    $row[] = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $aRow['rate_id'] . '">' . app_format_money($new_rate, get_base_currency()) . '</a>';
    }else{
        $row[] = app_format_money($new_rate, get_base_currency());
    }
    $aRow['taxrate_1'] = $aRow['taxrate_1'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_1'] . '" data-taxid="' . $aRow['tax_id_1'] . '">' . app_format_number($aRow['taxrate_1']) . '%' . '</span>';

    /*$aRow['taxrate_2'] = $aRow['taxrate_2'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_2'] . '" data-taxid="' . $aRow['tax_id_2'] . '">' . app_format_number($aRow['taxrate_2']) . '%' . '</span>';*/
   
   
    $p = $aRow['taxrate_1'] /100;
    $Y = $p * $new_rate;
    $sale_rate = $new_rate + $Y;
    $row[] = number_format($sale_rate, 2);
    
    if (has_permission('ratemaster', '', 'edit')) {
    $action1 = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $aRow['rate_id'] . '"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission('ratemaster', '', 'delete')) {
    $action1 .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['rate_id']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    }
    //$row[] = $action1;

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
    
    
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
   
    }
    $i++;
}
