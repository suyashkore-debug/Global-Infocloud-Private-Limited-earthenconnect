<?php

defined('BASEPATH') or exit('No direct script access allowed');
$selected_company = $this->ci->session->userdata('root_company');
$aColumns = [];

/*if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'AccountID',
    'company',
    'ActGroupID',
    'SubActGroupID',
    'active',
    'MaxCrdAmt',
    'ManagerID',
    'Blockyn',
    'city',
    'state',
    'address',
    'Address3',
    ]);

$sIndexColumn = 'AccountID';
$sTable       = db_prefix() . 'clients';
$where  = [];
$join = [];

if ($this->ci->input->post('ft_detail_type')) {
    array_push($where, 'AND SubActGroupID ='. $this->ci->input->post('ft_detail_type'));
}

if ($this->ci->input->post('ft_active') == "all") {
    
}else{
    array_push($where, 'AND '.db_prefix().'clients.active ='. $this->ci->input->post('ft_active'));
}
$additionalSelect = [];




array_push($where, 'AND active = 1');
            array_push($where, 'AND '. db_prefix() .'clients.PlantID = '.$selected_company);
            array_push($where, 'AND SubActGroupID IN ("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007")');
            

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   
    $row[] = $aRow['AccountID'];
    if (!has_permission_new('other_staff_master', '', 'edit')) {
        $account_name = $aRow['company'];
    }else{
    $account_name = '<a href="'.admin_url('accounts_master/edit_other_staff/' . $aRow['AccountID']).'">'.$aRow['company'].'</a>';
    }
    $row[] = $account_name;
    $row[] = $aRow['address'];
    $row[] = $aRow['Address3'];
    $city_name = get_city_name($aRow['city']);
    if($city_name->city_name){
        $city = $city_name->city_name;
    }else{
        $city = $aRow['city'];
    }
    $row[] = $city;
    $state_name = get_state_code($aRow['state']);
    $row[] = $state_name;
    
   
    
    $SubActGroup_name = get_subgroup_name($aRow['SubActGroupID'],$selected_company);
    $row[] = $SubActGroup_name->SubActGroupName;
    
    /*$ActGroup_name = get_group_name($SubActGroup_name->ActGroupID);
    $row[] = $ActGroup_name->ActGroupName;*/
    
    //$row[] = $aRow['SubActGroupID'];
    /*if($aRow['active']=="1"){
                    $status = "Active";
                }else{
                    $status = "DeActive";
                }
                $row[] = $status;*/

    $row[] = $aRow['Blockyn'];

    
    $action = '<a href="#" data-toggle="modal" data-target="#route_modal" data-id="' . $aRow['id'] . '"><i class="fa fa-pencil"></i></a>';
    $action .= ' | <a href="' . admin_url('route_master/delete/' . $aRow['id']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    //==$row[] = $action;

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
