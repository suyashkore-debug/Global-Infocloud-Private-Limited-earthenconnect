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
    ]);

$sIndexColumn = 'AccountID';
$sTable       = db_prefix() . 'clients';
$where  = [];
$join = [];

if ($this->ci->input->post('ft_account')) {
    $account = $this->ci->input->post('ft_account');
    array_push($where, 'AND AccountID ="'.$account.'"');
}

/*if ($this->ci->input->post('ft_type')) {
    array_push($where, 'AND ActGroupID ='. $this->ci->input->post('ft_type'));
}*/

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
            array_push($where, 'AND SubActGroupID NOT IN ("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")');
            

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   // $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    //$row[] = $aRow['item_code'];
    $itemcodeOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['item_code'] . '</a>';
    $itemcodeOutput .= '<div class="row-options">';

    if (has_permission_new('items', '', 'edit')) {
        $itemcodeOutput .= '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
    }

    if (has_permission_new('items', '', 'delete')) {
        $itemcodeOutput .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $itemcodeOutput .= '</div>';
    //==$row[] = $aRow['name'];
    //$descriptionOutput = '';
    //$descriptionOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    

    //$row[] = $descriptionOutput;
    $row[] = $aRow['AccountID'];
    if (has_permission_new('account_head', '', 'edit')) {
    $account_name = '<a href="'.admin_url('accounts_master/edit_account_head/' . $aRow['AccountID']).'">'.$aRow['company'].'</a>';
    }else{
        $account_name = $aRow['company'];
    }
    $row[] = $account_name;
    //$row[] = $aRow['ActGroupID'];
    
   
    
    $SubActGroup_name = get_subgroup_name($aRow['SubActGroupID'],$selected_company);
    $row[] = $SubActGroup_name->SubActGroupName;
    
    $ActGroup_name = get_group_name($SubActGroup_name->ActGroupID);
    $row[] = $ActGroup_name->ActGroupName;
    
    
    /*if($aRow['active']=="1"){
                    $status = "Active";
                }else{
                    $status = "DeActive";
                }
                $row[] = $status;
*/
    
    $row[] = $aRow['Blockyn'];
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
