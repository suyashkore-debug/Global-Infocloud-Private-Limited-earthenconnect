<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$hasPermissionDelete = has_permission_new('customers', '', 'delete');

$custom_fields = get_table_custom_fields('customers');
$this->ci->db->query("SET sql_mode = ''");

$aColumns = [
    '1',
    db_prefix().'clients.userid as userid',
    db_prefix().'clients.AccountID as AccountID',
    'company','itemdivision','state','address','StationName','city',
    
    db_prefix().'clients.active',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM '.db_prefix().'customers_groups WHERE '.db_prefix().'customers_groups.id = '.db_prefix().'clients.DistributorType) as customerGroups'
];

$sIndexColumn = 'company';
$sTable       = db_prefix().'clients';
$where        = [];
// Add blank where all filter can be stored
$filter = [];

$join = [
    'INNER JOIN '.db_prefix().'contacts ON '.db_prefix().'clients.AccountID='.db_prefix().'contacts.AccountID AND '.db_prefix().'clients.PlantID='.db_prefix().'contacts.PlantID',
    'LEFT JOIN '.db_prefix().'customer_admins ON '.db_prefix().'clients.AccountID='.db_prefix().'customer_admins.customer_id AND '.db_prefix().'clients.PlantID='.db_prefix().'customer_admins.company_id',
];
//$join = [];


$join = hooks()->apply_filters('customers_table_sql_join', $join);

// Filter by custom groups
$groups   = $this->ci->clients_model->get_groups();
$groupIds = [];
foreach ($groups as $group) {
    if ($this->ci->input->post('customer_group_' . $group['id'])) {
        array_push($groupIds, $group['id']);
    }
}
if (count($groupIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_groups WHERE groupid IN (' . implode(', ', $groupIds) . '))');
}
/*if ($this->ci->input->post('client_type')) {
    array_push($filter, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_groups WHERE groupid ='.$this->ci->input->post('client_type').')');
}*/
if ($this->ci->input->post('client_type')) {
    array_push($filter, 'AND DistributorType ='. $this->ci->input->post('client_type'));
}/*else {
    array_push($filter, 'AND DistributorType != 24');
    array_push($filter, 'AND DistributorType != 16');
    //array_push($filter, 'AND DistributorType != 24');
}*/
array_push($filter, 'AND SubActGroupID ="60001004"');
if ($this->ci->input->post('distributor_state')) {
    array_push($filter, 'AND state ="'. $this->ci->input->post('distributor_state').'"');
}
if ($this->ci->input->post('status')) {
    array_push($filter, 'AND '.db_prefix().'clients.active ='. $this->ci->input->post('status'));
}
if ($this->ci->input->post('status') == "0") {
    array_push($filter, 'AND '.db_prefix().'clients.active ='. $this->ci->input->post('status'));
}
if ($this->ci->input->post('responsible_admin')) {
    array_push($filter, 'AND '.db_prefix().'clients.AccountID IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id ='.$this->ci->input->post('responsible_admin').' AND company_id ='.$selected_company.')');
}

if ($this->ci->input->post('division')) {
    array_push($filter, 'AND '.db_prefix().'clients.AccountID IN (SELECT AccountID FROM '.db_prefix().'accountitemdiv WHERE ItemDivID ='.$this->ci->input->post('division').')');
}

if (count($groupIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_groups WHERE groupid IN (' . implode(', ', $groupIds) . '))');
}

$countries  = $this->ci->clients_model->get_clients_distinct_countries();
$countryIds = [];
foreach ($countries as $country) {
    if ($this->ci->input->post('country_' . $country['country_id'])) {
        array_push($countryIds, $country['country_id']);
    }
}
if (count($countryIds) > 0) {
    array_push($filter, 'AND country IN (' . implode(',', $countryIds) . ')');
}




// Filter by proposals
$customAdminIds = [];
foreach ($this->ci->clients_model->get_customers_admin_unique_ids() as $cadmin) {
    if ($this->ci->input->post('responsible_admin_' . $cadmin['staff_id'])) {
        array_push($customAdminIds, $cadmin['staff_id']);
    }
}

if (count($customAdminIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id IN (' . implode(', ', $customAdminIds) . '))');
}

if ($this->ci->input->post('requires_registration_confirmation')) {
    array_push($filter, 'AND '.db_prefix().'clients.registration_confirmed=0');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if (!has_permission_new('customers', '', 'view')) {
    array_push($where, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
}

if ($this->ci->input->post('exclude_inactive')) {
    array_push($where, 'AND ('.db_prefix().'clients.active = 1 OR '.db_prefix().'clients.active=0 AND registration_confirmed = 0)');
}


if ($this->ci->input->post('my_customers')) {
    array_push($where, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
}

array_push($where, 'AND ' . db_prefix() . 'clients.PlantID ='. $selected_company);
//array_push($where, 'AND ' . db_prefix() . 'clients.ActSalestype = Sales');

$aColumns = hooks()->apply_filters('customers_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix().'contacts.id as contact_id',
    'lastname',
    db_prefix().'clients.zip as zip',
    'registration_confirmed',
    db_prefix().'customer_admins.staff_id as assigned_staff',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Bulk actions
    /*$row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['userid'] . '"><label></label></div>';*/
    // User id
    //$row[] = $aRow['userid'];

    // Company
    $company  = $aRow['company'];
    $isPerson = false;

    if ($company == '') {
        $company  = _l('no_company_view_profile');
        $isPerson = true;
    }

    $url = admin_url('clients/client/' . $aRow['AccountID']);

    if ($isPerson && $aRow['contact_id']) {
        $url .= '?contactid=' . $aRow['contact_id'];
    }
if (!has_permission_new('customers', '', 'edit')) {
     $company = $company;
}else{
     $company = '<a href="' . $url . '">' . $company . '</a>';
}
   

    
    $row[] = $aRow['AccountID'];
    $row[] = $company;
    
    
    
    /*// Toggle active/inactive customer
    $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
    <input type="checkbox"' . ($aRow['registration_confirmed'] == 0 ? ' disabled' : '') . ' data-switch-url="' . admin_url() . 'clients/change_client_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['userid'] . '" data-id="' . $aRow['userid'] . '" ' . ($aRow[db_prefix().'clients.active'] == 1 ? 'checked' : '') . '>
    <label class="onoffswitch-label" for="' . $aRow['userid'] . '"></label>
    </div>';

    // For exporting
    $toggleActive .= '<span class="hide">' . ($aRow[db_prefix().'clients.active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';

    $row[] = $toggleActive;*/
    // Customer groups parsing
    $groupsRow = '';
    if ($aRow['customerGroups']) {
        $groups = explode(',', $aRow['customerGroups']);
        foreach ($groups as $group) {
            $groupsRow .= '<span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:0px;">' . $group . '</span>';
        }
    }

    $row[] = $groupsRow;
    
    $row[] =$aRow['StationName'];
    $state_name = $aRow['state'];
    $row[] = $state_name;
    //$row[] =$aRow['city'];
    $city_name = get_city_name($aRow['city']);
    if($city_name->city_name){
        $city = $city_name->city_name;
    }else{
        $city = $aRow['city'];
    }
    $row[] = $city;

    
    
    //$row[] = $aRow['state'];
    $address = explode(' ', $aRow['address']);
    $i = 1;
    $string = "";
    foreach($address as $aa){
        if($i%2 == 0){
            $string = $string." ".$aa."\n";
        }else {
            $string = $string." ".$aa;
        }
        
        $i++;
    }
    $row[] = nl2br($string);
     
    
    $staff=  "";
    
        	$full_name = get_staff_name($aRow['assigned_staff']);
        	//$staff  = $staff.$full_name->firstname;
        	if($full_name){
        	    $staff .= '<span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:0px;">' . $full_name->firstname .' '.$full_name->lastname. '</span>';
        	}
        
    $row[] = $staff;

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
    
    if($aRow[db_prefix().'clients.active'] == 1){
        $status = "Active";
    }else{
        $status = "InActive";
    }
    $row[] = $status;

    $row['DT_RowClass'] = 'has-row-options';

    if ($aRow['registration_confirmed'] == 0) {
        $row['DT_RowClass'] .= ' alert-info requires-confirmation';
        $row['Data_Title']  = _l('customer_requires_registration_confirmation');
        $row['Data_Toggle'] = 'tooltip';
    }
    //$row[] = _dt($aRow['datecreated']);

    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
