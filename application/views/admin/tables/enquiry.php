<?php

defined('BASEPATH') or exit('No direct script access allowed');

//$hasPermissionDelete = has_permission('customers', '', 'delete');
$custom_fields = get_table_custom_fields('customers');
$this->ci->db->query("SET sql_mode = ''");

$aColumns = [
    'id',
    'staff_id',
    'farm_name',
    'mobile_no',
    'EmailID',
    'contact_person',
    'cp_mobile_no',
    'address',
    'remark',
    'state',
    'district',
    'area',
    'revisit',
    'status',
    'Enq_date',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'so_enquiry';
$where        = [];
// Add blank where all filter can be stored
$filter = [];

$join = [];





if ($this->ci->input->post('distributor_state')) {
    array_push($filter, 'AND state ='. $this->ci->input->post('distributor_state'));
}
if ($this->ci->input->post('responsible_admin')) {
    array_push($filter, 'AND staff_id ='. $this->ci->input->post('responsible_admin'));
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

/*if (!has_permission('customers', '', 'view')) {
    array_push($where, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
}*/



if ($this->ci->input->post('my_customers')) {
    array_push($where, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
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

   
    //$row[] = $aRow['id'];
    $staff_name = get_staff_name($aRow['staff_id']);
    $row[] = $staff_name->firstname." ".$staff_name->lastname;

    // Company
    $company  = $aRow['farm_name'];
    $isPerson = false;

    if ($company == '') {
        $company  = _l('no_company_view_profile');
        $isPerson = true;
    }

    $url = admin_url('enquiry/view/' . $aRow['id']);

    if ($isPerson && $aRow['contact_id']) {
        $url .= '?contactid=' . $aRow['contact_id'];
    }

    

    $row[] = $company;
    $row[] = $aRow['Enq_date'];
    $row[] = $aRow['contact_person'];
    $row[] = $aRow['cp_mobile_no'];

    $state_name = get_state_name2($aRow['state']);
    $row[] = $state_name->short_name;
    $city_name = get_city_name($aRow['district']);
    $row[] = $city_name->city_name;
    $row[] = $aRow['area'];
    $row[] = $aRow['address'];
    $row[] = $aRow['remark'];
    if($aRow['revisit']=="1"){
        $revisit = "Yes";
    }else {
        $revisit = "No";
    }
    $row[] = $revisit;
   

    $row['DT_RowClass'] = 'has-row-options';

    

    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
