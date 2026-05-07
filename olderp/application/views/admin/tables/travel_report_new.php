<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$hasPermissionDelete = has_permission('customers', '', 'delete');

$custom_fields = get_table_custom_fields('customers');
$this->ci->db->query("SET sql_mode = ''");

$aColumns = [
    'id',
    'staff_id',
    'location_list',
    'location_trav','location_name_list','battery_level','device_information','GPS_Status','created_date','date',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'travel_report';
$where        = [];
// Add blank where all filter can be stored
$filter = [];


$join = [];


$join = hooks()->apply_filters('customers_table_sql_join', $join);



if ($this->ci->input->post('staff')) {
    array_push($filter, 'AND staff_id ='. $this->ci->input->post('staff'));
}


if ($this->ci->input->post('report_date')) {
    $date = $this->ci->input->post('report_date');
    array_push($filter, 'AND date = "'.$date.'"');
}
/*if ($this->ci->input->post('status')) {
    array_push($filter, 'AND '.db_prefix().'clients.active ='. $this->ci->input->post('status'));
}
if ($this->ci->input->post('status') == "0") {
    array_push($filter, 'AND '.db_prefix().'clients.active ='. $this->ci->input->post('status'));
}
if ($this->ci->input->post('responsible_admin')) {
    array_push($filter, 'AND '.db_prefix().'clients.AccountID IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id ='.$this->ci->input->post('responsible_admin').' AND company_id ='.$selected_company.')');
}*/










if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}


$aColumns = hooks()->apply_filters('customers_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $full_name = get_staff_name($aRow['staff_id']);
    $name = $full_name->firstname .' '.$full_name->lastname;
    $row[] = $name;
     
   
    //$row[] = $aRow['location_trav'];
    $row[] = $aRow['location_name_list'];
    $row[] = $aRow['battery_level'];
    $row[] = $aRow['device_information'];
    $row[] = $aRow['GPS_Status'];
    $row[] = substr($aRow['created_date'],10);
    $row[] = $aRow['date'];
    
    /*// Company
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

    $company = '<a href="' . $url . '">' . $company . '</a>';

    
    $row[] = $aRow['AccountID'];
    $row[] = $company;
    
    
    
    // Toggle active/inactive customer
    $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
    <input type="checkbox"' . ($aRow['registration_confirmed'] == 0 ? ' disabled' : '') . ' data-switch-url="' . admin_url() . 'clients/change_client_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['userid'] . '" data-id="' . $aRow['userid'] . '" ' . ($aRow[db_prefix().'clients.active'] == 1 ? 'checked' : '') . '>
    <label class="onoffswitch-label" for="' . $aRow['userid'] . '"></label>
    </div>';

    // For exporting
    $toggleActive .= '<span class="hide">' . ($aRow[db_prefix().'clients.active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';

    $row[] = $toggleActive;
    
    $row[] =$aRow['StationName'].'madhav';
    //$row[] =$aRow['city'];
    $city_name = get_city_name($aRow['city']);
    if($city_name->city_name){
        $city = $city_name->city_name;
    }else{
        $city = $aRow['city'];
    }
    $row[] = $city;

    // Customer groups parsing
    $groupsRow = '';
    if ($aRow['customerGroups']) {
        $groups = explode(',', $aRow['customerGroups']);
        foreach ($groups as $group) {
            $groupsRow .= '<span class="label label-default mleft5 inline-block customer-group-list pointer">' . $group . '</span>';
        }
    }

    $row[] = $groupsRow;
    $state_name = get_state_code($aRow['state']);
    $row[] = $state_name;
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
     
    $assigned_staff = unserialize($aRow['company_assigned_staff']);
    $staff=  "";
    foreach ($assigned_staff as $value) {
        	# code...
        	
        	$full_name = get_staff_name($value);
        	//$staff  = $staff.$full_name->firstname;
        	if($full_name){
        	    $staff .= '<span class="label label-default mleft5 inline-block customer-group-list pointer">' . $full_name->firstname .' '.$full_name->lastname. '</span>';
        	}
        	
        }
    $row[] = $staff;

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    if ($aRow['registration_confirmed'] == 0) {
        $row['DT_RowClass'] .= ' alert-info requires-confirmation';
        $row['Data_Title']  = _l('customer_requires_registration_confirmation');
        $row['Data_Toggle'] = 'tooltip';
    }
    //$row[] = _dt($aRow['datecreated']);
*/
    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
