<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	$selected_company = $this->ci->session->userdata('root_company');
	$fy = $this->ci->session->userdata('finacial_year');
	$aColumns = [
        'ChallanID',
        'Transdate',
        'GetPassTime',
        '(SELECT GROUP_CONCAT(company SEPARATOR ",") FROM tblclients JOIN tblsalesmaster ON tblclients.AccountID = tblsalesmaster.AccountID WHERE tblsalesmaster.ChallanID = tblchallanmaster.ChallanID ORDER by company ASC) as company',
        'VehicleID',
        'DriverID',
        'name',// Route Name
        'Crates',
        'Cases',
        'ChallanAmt',
        'Gatepassuserid',
		'CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS DriverName'
    ];
	$sIndexColumn = 'ChallanID';
	$sTable       = db_prefix() . 'challanmaster';
	
	$where  = [];
	$filter = [];
	$join = [
        'JOIN ' . db_prefix() . 'route ON ' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'challanmaster.RouteID',
        'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.AccountID = ' . db_prefix() . 'challanmaster.DriverID',
    ];
	array_push($where, 'AND ' . db_prefix() . 'challanmaster.PlantID ='. $selected_company);
	
	if (!has_permission('challan', '', 'view')) {
		$userWhere = 'AND ' . get_challan_where_sql_for_staff(get_staff_user_id());
		array_push($where, $userWhere);
	}
	
	if ($this->ci->input->post('date')) {
		$from_date = to_sql_date($this->ci->input->post('from_date'));
		$order_date = to_sql_date($this->ci->input->post('date'));
		$gatepass_status = $this->ci->input->post('gatepass_status');
		array_push($where, 'AND ' . db_prefix() . 'challanmaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$order_date.' 23:59:59"');
		if(!empty($gatepass_status))
		{
			if($gatepass_status == "Generated")
			{
				array_push($where, 'AND ' . db_prefix() . 'challanmaster.Gatepassuserid IS NOT NULL');
			}else{
				array_push($where, 'AND ' . db_prefix() . 'challanmaster.Gatepassuserid IS NULL');
			}
		}
	}
	array_push($where, 'AND ' . db_prefix() . 'challanmaster.FY ='. $fy);
	$aColumns = hooks()->apply_filters('projects_table_sql_columns', $aColumns);
	// Fix for big queries. Some hosting have max_join_limit
    if (count($custom_fields) > 4) {
        @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
    }
	$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
	$output  = $result['output'];
	$rResult = $result['rResult'];
	
	foreach ($rResult as $aRow) 
	{
		$row = [];
		
		if(empty($aRow['Cases']))
		{
			$qty = $aRow['Crates'];
		}else{
			$qty = $aRow['Cases'];
		}
		
		if(!empty($aRow['Gatepassuserid']))
		{
			$numberOutput = '<a target="_blank" href="' . admin_url('challan/gatepass/' . $aRow['ChallanID']) . '?output_type=I">' . $aRow['ChallanID'] . '</a>';
        }else{
			$numberOutput = '<a onclick="Getepassgenerate(\'' . admin_url('challan/gatepass/' . $aRow['ChallanID']) . '?output_type=I\')" href="javascript:void(0);">' . $aRow['ChallanID'] . '</a>';
		}
		
		$row[] = $numberOutput;
		$row[] = _d($aRow['Transdate']);
		$row[] = _d($aRow['GetPassTime']);
		$row[] = render_tags($aRow['company']);
		$row[] = $aRow['VehicleID'];
		$row[] = $aRow['DriverName'];
		$row[] = render_tags($aRow['name']);
		$row[] = $qty;
		$row[] = round($aRow['ChallanAmt']);
		
		if(!empty($aRow['Gatepassuserid']))
		{
			$row[] = 'Generated';
		}else{
			$row[] = 'Not Generated';
		}
		$row['DT_RowClass'] = 'has-row-options';
		
		$row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);
		
		$output['aaData'][] = $row;
	}			