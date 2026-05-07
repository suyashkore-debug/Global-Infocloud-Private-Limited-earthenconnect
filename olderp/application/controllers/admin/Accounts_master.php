<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accounts_master extends AdminController
{
    private $not_importable_fields = ['id'];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('clients_model');
        $this->load->model('accounts_master_model');
        $this->load->model('departments_model');
    }

    /* Add/Edit New Accounts Head */
    public function index($accountId = '')
    {
        if (!has_permission_new('account_head', '', 'view')) {
            access_denied('Invoice Items');
        }
        
        if ($this->input->post()) {
            
            $selected_company = $this->session->userdata('root_company');
            $FY = $this->session->userdata('finacial_year');
            $StaffID = $this->session->userdata('username');
            $data = $this->input->post();
            if(empty($data["edit_account_id"])){
                
               if (!has_permission_new('account_head', '', 'create')) {
                   access_denied('Access denied');
               }
                $client_array = array(
                'PlantID'=>$selected_company,
                'AccountID' =>$data["account_id"],
                'CtrlAccountID' =>$data["account_id"],
                'company' =>$data["account_name"],
                'SubActGroupID' =>$data["Account_Group"],
                'DistributorType' =>24,
                'Blockyn' =>$data["block_ac"],
                'addedfrom' =>$StaffID,
                'StartDate' =>$data["start_date"]." ".date('H:i:s'),
                'country' =>1
                );
            
            $contact_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'BalancesYN' =>$data["bal_on_bill"],
                );
            $sld_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'SLDTypeID' =>1,
                );
                $opn_bal = $data["opening_bal"];
                $actbal_array = array(
                    'PlantID' =>$selected_company,
                    'AccountID' =>$data["account_id"],
                    'FY' =>$FY,
                    'BAL1' =>$opn_bal,
                );
            
            $client_data = $this->accounts_master_model->add_client($client_array);
            if($client_data == true){
                $contact_data = $this->accounts_master_model->add_contact($contact_array);
                if($contact_data == true){
                    
                    $sld_data = $this->accounts_master_model->add_sldtype($sld_array);
                    
                    if($sld_data == true){
                        $actbal_data = $this->accounts_master_model->add_act_bal($actbal_array);
                        set_alert('success', _l('added_successfully', 'Account'));
                        //$redUrl = admin_url('order/pending_orders/' . $id);
                        $redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                    }
                }
                
            }
            
         }else{
            
            if (!has_permission_new('account_head', '', 'edit')) {
                   access_denied('Access denied');
               }
            $account_id = $data["edit_account_id"];
            $client_array = array(
                'company' =>$data["account_name"],
                'Blockyn' =>$data["block_ac"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
            $contact_array = array(
                'BalancesYN' =>$data["bal_on_bill"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
            $update_bal = array(
                'BAL1' =>$data["opening_bal"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );    
                
             $client_data = $this->accounts_master_model->update_client($client_array,$account_id);
             $contact_data = $this->accounts_master_model->update_contact($contact_array,$account_id);
            $staff_user_id = $this->session->userdata('staff_user_id');
            if($staff_user_id == "3"){
                $bal_data = $this->accounts_master_model->update_bal($update_bal,$account_id);
            }
                if($client_data == true){
                    
                        set_alert('success', "Account Update successfully");
                        $redUrl = admin_url('accounts_master/edit_account_head/' . $account_id);
                        //$redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                    
                }else{
                    
                    set_alert('error', "somthing went wrong..");
                        $redUrl = admin_url('accounts_master/edit_account_head/' . $account_id);
                        //$redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                }
         
            }   
            
         }
      
        $data['title'] = "Add/Edit Account Head";
        //table code start here
         $data['account_types'] = $this->accounts_master_model->get_accoun_main_group();
        $data['detail_types'] = $this->accounts_master_model->get_account_subgroup();
        $data['accounts'] = $this->accounts_master_model->get_accounts_list();
         $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        //end
        
         $data['state_list'] = $this->accounts_master_model->get_state();
        $data['distributor_type'] = $this->accounts_master_model->get_distibutor_type();
        $data['account_subgroup'] = $this->accounts_master_model->get_subgroup_for_accounting_head();
        
        
        $this->load->view('admin/accounts_master/add_account_head', $data);
    }
    
    /* Add/Edit New Accounts Head */
    public function AddEditAccountHead($accountId = '')
    {
        if (!has_permission_new('account_head', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->post()) {
            
            $selected_company = $this->session->userdata('root_company');
            $FY = $this->session->userdata('finacial_year');
            $StaffID = $this->session->userdata('username');
            $data = $this->input->post();
            if(empty($data["edit_account_id"])){
                
               if (!has_permission_new('account_head', '', 'create')) {
                   access_denied('Access denied');
               }
                $client_array = array(
                'PlantID'=>$selected_company,
                'AccountID' =>$data["account_id"],
                'CtrlAccountID' =>$data["account_id"],
                'company' =>$data["account_name"],
                'SubActGroupID' =>$data["Account_Group"],
                'DistributorType' =>24,
                'Blockyn' =>$data["block_ac"],
                'addedfrom' =>$StaffID,
                'StartDate' =>$data["start_date"]." ".date('H:i:s'),
                'country' =>1
                );
            
            $contact_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'BalancesYN' =>$data["bal_on_bill"],
                );
            $sld_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'SLDTypeID' =>1,
                );
                $opn_bal = $data["opening_bal"];
                $actbal_array = array(
                    'PlantID' =>$selected_company,
                    'AccountID' =>$data["account_id"],
                    'FY' =>$FY,
                    'BAL1' =>$opn_bal,
                );
            
            $client_data = $this->accounts_master_model->add_client($client_array);
            if($client_data == true){
                $contact_data = $this->accounts_master_model->add_contact($contact_array);
                if($contact_data == true){
                    
                    $sld_data = $this->accounts_master_model->add_sldtype($sld_array);
                    
                    if($sld_data == true){
                        $actbal_data = $this->accounts_master_model->add_act_bal($actbal_array);
                        set_alert('success', _l('added_successfully', 'Account'));
                        //$redUrl = admin_url('order/pending_orders/' . $id);
                        $redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                    }
                }
                
            }
            
         }else{
            
            if (!has_permission_new('account_head', '', 'edit')) {
                   access_denied('Access denied');
               }
            $account_id = $data["edit_account_id"];
            $client_array = array(
                'company' =>$data["account_name"],
                'Blockyn' =>$data["block_ac"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
            $contact_array = array(
                'BalancesYN' =>$data["bal_on_bill"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
            $update_bal = array(
                'BAL1' =>$data["opening_bal"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );    
                
             $client_data = $this->accounts_master_model->update_client($client_array,$account_id);
             $contact_data = $this->accounts_master_model->update_contact($contact_array,$account_id);
            $staff_user_id = $this->session->userdata('staff_user_id');
            if($staff_user_id == "3"){
                $bal_data = $this->accounts_master_model->update_bal($update_bal,$account_id);
            }
                if($client_data == true || $bal_data == true){
                        set_alert('success', "Account Update successfully");
                        $redUrl = admin_url('accounts_master/edit_account_head/' . $account_id);
                        //$redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                    
                }else{
                        set_alert('error', "somthing went wrong..");
                        $redUrl = admin_url('accounts_master/edit_account_head/' . $account_id);
                        //$redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                }
            }   
            
         }
        if($accountId !== ""){
		    $AccountIDSetData = array(
                'AccountIDSet'  => $accountId
            );
            $this->session->set_userdata($AccountIDSetData);
		}else{
		    $this->session->unset_userdata('AccountIDSet');
		}
        $data['title'] = "Add/Edit Account Head";
        //table code start here
        
        $data['accounts'] = $this->accounts_master_model->get_accounts_list();
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        //end
      
        $data['account_subgroup'] = $this->accounts_master_model->get_subgroup_for_accounting_head();
        $this->load->view('admin/accounts_master/AddEditAccountHead', $data);
    }
    
    /* Get Account Details by AccountID / ajax */
    public function GetAccountDetailByID()
    {
        $AccountID = $this->input->post('AccountID');
        $Account                    = $this->accounts_master_model->getNEW($AccountID);
        echo json_encode($Account);
    }
    
    /* Update Exiting ItemID / ajax */
    public function UpdateAccountID()
    {
        $UserID = $this->session->userdata('username');
        $dataClient = array(
            'company'=>$this->input->post('company'),
            'SubActGroupID'=>$this->input->post('SubActGroupID'),
            'Blockyn'=>$this->input->post('Blockyn'),
            'StartDate'=>to_sql_date($this->input->post('StartDate')),
            "UserID2"=>$UserID,
            "Lupdate"=>date('Y-m-d H:i:s')
        );
        /*$dataContacts = array(
            'BalancesYN'=>$this->input->post('BalancesYN'),
        );*/
        $AccountID = $this->input->post('AccountID');
        $BAL1 = $this->input->post('BAL1');
        $AccountDetails         = $this->accounts_master_model->UpdateAccountID($dataClient,$AccountID,$BAL1);
        echo json_encode($AccountDetails);
    }
    
    /* Save New ItemID / ajax */
    public function SaveItemID()
    {
        $data = array(
            'AccountID' => strtoupper($this->input->post('AccountID')),
            'company'=>$this->input->post('company'),
            'SubActGroupID'=>$this->input->post('SubActGroupID'),
            'Blockyn'=>$this->input->post('Blockyn'),
            'StartDate'=>to_sql_date($this->input->post('StartDate')),
        );
        /*$dataContacts = array(
            'BalancesYN'=>$this->input->post('BalancesYN'),
        );*/
        $BAL1 = $this->input->post('BAL1');
        $AccountID = strtoupper($this->input->post('AccountID'));
        $AccountDetails                  = $this->accounts_master_model->SaveAccountID($data,$BAL1,$AccountID);
        
        echo json_encode($AccountDetails);
    }
    
    /*  Accounts Head List */
    public function AccountHeadList($accountId = '')
    {
        if (!has_permission_new('account_head', '', 'view')) {
            access_denied('Invoice Items');
        }
        
        $data['title'] = "Account Head List";
        //table code start here
        $data['account_types'] = $this->accounts_master_model->get_accoun_main_group();
        $data['detail_types'] = $this->accounts_master_model->get_account_subgroup();
        $data['accounts'] = $this->accounts_master_model->get_accounts_list();
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        //end
        $this->load->view('admin/accounts_master/AccountHead', $data);
    }
    
    public function export_Account_Head()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
            $data =$this->accounts_master_model->table_data($this->input->post());
    		$selected_company_details    = $this->accounts_master_model->get_company_detail();
    		$selected_company = $this->session->userdata('root_company');
           $account = $this->input->post('account');
           $sub_group_id = $this->input->post('sub_group_id');
           $status = $this->input->post('status');
           if($status == 1){
               $status = 'Yes';
           }else{
               $status = 'No';
           }
           if($account){
               $data_act = $this->db->select('company')->get_where('tblclients',array('AccountID'=>$account))->row_array();
           
               $data_act_header = $data_act['company'];
           }else{
               $data_act_header = '';
           }
           
         if($sub_group_id != ''){
            $SubActGroup_name = get_subgroup_name($sub_group_id,$selected_company);
            $SubActGroup_name_header = $SubActGroup_name->SubActGroupName;
         }else{
             $SubActGroup_name_header = '';
         }
    		$writer = new XLSXWriter();
    		//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
    		//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
    		//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
    		//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
    		
    		$company_name = array($selected_company_details->company_name);
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 8);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		
    		$msg = "Accounts List Filter Account: ".$data_act_header.", SubActGroup Name: " .$SubActGroup_name_header.", Active: ".$status;
    		$filter = array($msg);
    		$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 8);  //merge cells
    		$writer->writeSheetRow('Sheet1', $filter);
    		
    		// empty row
    		$list_add = [];
    		$list_add[] = "";
    		$list_add[] = "";
    		$list_add[] = "";
    	    $list_add[] = "";
    		$list_add[] = "";
    	    $list_add[] = "";
            $list_add[] = "";
            $list_add[] = "";
            $list_add[] = "";
            $writer->writeSheetRow('Sheet1', $list_add);
            
            
            $set_col_tk = [];
    		$set_col_tk["AccountID"] =  'AccountID';
    		$set_col_tk["Account Name"] = 'Account Name';
    		$set_col_tk["Subgroup"] = 'Subgroup';
    		$set_col_tk["Main Group"] = 'Main Group';
    		$set_col_tk["Blocked"] = 'Blocked';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    		
    		foreach ($data as $k => $value) {
    		   
    			$list_add = [];
    			$list_add[] = $value["AccountID"];
    			$list_add[] = $value["company"];
    			$SubActGroup_name = get_subgroup_name($value['SubActGroupID'],$selected_company);
                $SubActGroup_name_data = $SubActGroup_name->SubActGroupName;
    			$list_add[] = $SubActGroup_name_data;
    			$ActGroup_name = get_group_name($SubActGroup_name->ActGroupID);
                $ActGroup_name_data = $ActGroup_name->ActGroupName;
    			$list_add[] = $ActGroup_name_data;
    			$list_add[] = $value["Blockyn"];
    			$writer->writeSheetRow('Sheet1', $list_add);
    	    }
    	
    		
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'AccountHead.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
    
    //load table in here
    public function load_data(){
        $data =$this->accounts_master_model->table_data($this->input->post());
      
        $selected_company = $this->session->userdata('root_company');
       $account = $this->input->post('account');
       $sub_group_id = $this->input->post('sub_group_id');
       $status = $this->input->post('status');
       if($status == 1){
           $status = 'Yes';
       }
       if($account){
           $data_act = $this->db->select('company')->get_where('tblclients',array('CtrlAccountID'=>$account))->row_array();
       
           $data_act_header = $data_act['company'];
       }else{
           $data_act_header = '';
       }
       
     if($sub_group_id != ''){
          $SubActGroup_name = get_subgroup_name($sub_group_id,$selected_company);
     $SubActGroup_name_header = $SubActGroup_name->SubActGroupName;
     }else{
         $SubActGroup_name_header = '';
     }
    
        $html ='';
        foreach($data as $value){
            $html.= '<tr>';
         
            $html.= '<td>'.$value['AccountID'].'</td>';
         if (has_permission_new('account_head', '', 'edit')) {
            $account_name = '<a href="'.admin_url('accounts_master/edit_account_head/' . $value['AccountID']).'">'.$value['company'].'</a>';
            }else{
                $account_name = $value['company'];
            }
            $html.= '<td>'.$value['company'].'</td>';
             $SubActGroup_name = get_subgroup_name($value['SubActGroupID'],$selected_company);
                $SubActGroup_name_data = $SubActGroup_name->SubActGroupName;
                
            $html.= '<td>'.$SubActGroup_name_data.'</td>';
              
              $ActGroup_name = get_group_name($SubActGroup_name->ActGroupID);
                $ActGroup_name_data = $ActGroup_name->ActGroupName;
    
            $html.= '<td>'.$ActGroup_name_data.'</td>';
               
             $html.= '<td>'.$value['Blockyn'].'</td>';
            $html.= '</tr>';
        }
        $data_array =array('html'=>$html,'act'=>$data_act_header,'sub_act'=>$SubActGroup_name_header,'active'=>$status);
      echo json_encode($data_array);
    }
    //end here load table 
    
    
    /* Edit Account Head */
    public function edit_account_head($account_id = "")
    {
        $data['title'] = "Edit Account Head";
        $data['account_detail'] = $this->accounts_master_model->get_acount_detail($account_id);
        $data['state_list'] = $this->accounts_master_model->get_state();
        $data['distributor_type'] = $this->accounts_master_model->get_distibutor_type();
        $data['account_subgroup'] = $this->accounts_master_model->get_subgroup_for_accounting_head();
       /* echo "<pre>";
        print_r($data);
        die;*/
        $this->load->view('admin/accounts_master/add_account_head', $data);
    }
    
    /* Edit Other Staff */
    public function edit_other_staff($account_id = "")
    {
        $data['title'] = "Edit other staff";
        $data['account_detail'] = $this->accounts_master_model->get_acount_detail($account_id);
        $data['account_designation'] = $this->accounts_master_model->get_acount_designation($account_id);
        /*echo "<pre>";
        print_r($data['account_designation']);
        die;*/
        $data['state_list'] = $this->accounts_master_model->get_state();
        $data['distributor_type'] = $this->accounts_master_model->get_distibutor_type();
        $data['account_subgroup'] = $this->accounts_master_model->get_subgroup_for_other_staff();
        $data['account_department'] = $this->accounts_master_model->get_account_department();
        $this->load->view('admin/accounts_master/addstaff_master', $data);
    }
    
    
    /* List all available Account */
    public function manage_accounts()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            access_denied('Invoice Items');
        }
      
        $data['title'] = "Account Master";
        $data['account_types'] = $this->accounts_master_model->get_accoun_main_group();
        $data['detail_types'] = $this->accounts_master_model->get_account_subgroup();
        $data['accounts'] = $this->accounts_master_model->get_accounts_list();
        $this->load->view('admin/accounts_master/manage', $data);
    }
    
    
    public function table()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('accounts_table');
    }
    
    public function table_account_group()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('accounts_group_table');
    }
    
    public function table_account_subgroup()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('accounts_subgroup_table');
    }
    
    /* List all available Other  */
    public function staff_master()
    {
        if (!has_permission_new('other_staff_master', '', 'view')) {
            access_denied('Invoice Items');
        }
      
        $data['title'] = "Staff Master";
        $data['detail_types'] = $this->accounts_master_model->get_subgroup_for_other_staff();
        $this->load->view('admin/accounts_master/staff_master', $data);
    }
    
    
    
    /* Add / edit account group  */
    public function ActGroup()
    {
        if (!has_permission('account_groups', '', 'view')) {
            access_denied('Invoice Items');
        }
        
        $data['title'] = "Add/Edit Account Group";
        $data['account_group_mov'] = $this->accounts_master_model->get_actgroup_movement();
        $data['account_group_table'] = $this->accounts_master_model->get_actgroup_data();
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $this->load->view('admin/accounts_master/AddEditGroup', $data);
    }
    
    
    
    /* List account group  */
    public function AccountGroupList()
    {
        if (!has_permission('AcccountGroupList', '', 'view')) {
            access_denied('AcccountGroupList');
        }
        $data['title'] = "Account Group List";
        $data['account_group_mov'] = $this->accounts_master_model->get_actgroup_movement();
        $data['account_group_table'] = $this->accounts_master_model->get_actgroup_data();
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $this->load->view('admin/accounts_master/AccountGroupList', $data);
    }
    
    public function Account_main_group()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	    $data = $this->accounts_master_model->get_actgroup_data();
    	    $selected_company_details    = $this->accounts_master_model->get_company_detail();
    	    
    	    $writer = new XLSXWriter();
    	    $company_name = array($selected_company_details->company_name);
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 5);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 5);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		
    		// empty row
    		$list_add = [];
    		$list_add[] = "";
    		$list_add[] = "";
    		$list_add[] = "";
    		$list_add[] = "";
    		$writer->writeSheetRow('Sheet1', $list_add);
    		
    		$set_col_tk = [];
    		$set_col_tk["AccountGroup"] =  'AccountGroupID';
    		$set_col_tk["AccountDescription"] = 'AccountDescription';
    		$set_col_tk["GroupType"] = 'GroupType';
    		$set_col_tk["Movement"] = 'Movement';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
    		$i=1;
    		foreach ($data as $k => $value) {
    		    $list_add = [];
    			$list_add[] = $value["ActGroupID"];
    			$list_add[] = $value["ActGroupName"];
    			if($value["ActGroupTypeID"]=="A"){
                        $groupType = "Assets";
                }else{
                        $groupType = "Liability";
                }
                $list_add[] = $groupType;
                if($value["ActGroupMovementID"]=="B"){
                        $movement = "BALANCE SHEET";
                }elseif($value["ActGroupMovementID"]=="P"){
                        $movement = "PROFIT & LOSS A/C";
                }elseif($value["ActGroupMovementID"]=="T"){
                        $movement = "TRADING A/C";
                }
                $list_add[] = $movement;            
    			$writer->writeSheetRow('Sheet1', $list_add);
    			
    		}
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'AccountMainGroup.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
    
    
    /* Add / edit account Sub group  */
    public function SubGroup()
    {
        if (!has_permission_new('account_subgroups', '', 'view')) {
            access_denied('Invoice Items');
        }
        $data['title'] = "Add/Edit Account SubGroup";
        $data['account_maingroup'] = $this->accounts_master_model->get_act_maingroup();
        $data['next_act_groupId'] = $this->accounts_master_model->get_act_groupid();
        $data['account_subgroup_table'] = $this->accounts_master_model->get_actsubgroup_data();
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $this->load->view('admin/accounts_master/AddEditSubGroup', $data);
    }
    
    /* Save New Sub Group / ajax */
    public function SaveSubGroup()
    {
        $data = array(
            'SubActGroupID'=>$this->input->post('SubGroupID'),
            'SubActGroupName'=>$this->input->post('SubGroupName'),
            'ActGroupID'=>$this->input->post('MainGroupID'),
        );
        $AccountSubGroup  = $this->accounts_master_model->SaveSubGroup($data);
        echo json_encode($AccountSubGroup);
    }
    
    /* Update Exiting SubGroup / ajax */
    public function UpdateSubGroup()
    {
        $data = array(
            
            'SubActGroupName'=>$this->input->post('SubGroupName'),
            'ActGroupID'=>$this->input->post('MainGroupID'),
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
        );
        $SubGroupID = $this->input->post('SubGroupID');
        $itemGroupID                     = $this->accounts_master_model->UpdateSubGroup($data,$SubGroupID);
        echo json_encode($itemGroupID);
    }
    
    /* Save New Group / ajax */
    public function SaveGroup()
    {
        $data = array(
            'ActGroupID'=>$this->input->post('ActGroupID'),
            'ActGroupName'=>$this->input->post('ActGroupName'),
            'ActGroupTypeID'=>$this->input->post('ActGroupTypeID'),
            'ActGroupMovementID'=>$this->input->post('ActGroupMovementID'),
        );
        $AccountGroup  = $this->accounts_master_model->SaveGroup($data);
        echo json_encode($AccountGroup);
    }
    
    /* Update Exiting SubGroup / ajax */
    public function UpdateGroup()
    {
        $data = array(
            'ActGroupName'=>$this->input->post('ActGroupName'),
            'ActGroupTypeID'=>$this->input->post('ActGroupTypeID'),
            'ActGroupMovementID'=>$this->input->post('ActGroupMovementID'),
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
        );
        $ActGroupID = $this->input->post('ActGroupID');
        $AccountGroupID                     = $this->accounts_master_model->UpdateGroup($data,$ActGroupID);
        echo json_encode($AccountGroupID);
    }
    
    /* Add / edit account Sub group  */
    public function AccountSubGroupList()
    {
        if (!has_permission_new('AcccountSubGroupList', '', 'view')) {
            access_denied('AcccountSubGroupList');
        }
        $data['title'] = "Account SubGroup List";
        $data['account_maingroup'] = $this->accounts_master_model->get_act_maingroup();
        $data['next_act_groupId'] = $this->accounts_master_model->get_act_groupid();
        $data['account_subgroup_table'] = $this->accounts_master_model->get_actsubgroup_data();
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $this->load->view('admin/accounts_master/AccountSubGroupList', $data);
    }
    
    public function Account_sub_group()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	    $data = $this->accounts_master_model->get_actsubgroup_data();
    	    $account_maingroup = $this->accounts_master_model->get_act_maingroup();
    	    $selected_company_details    = $this->accounts_master_model->get_company_detail();
    	    
    	    $writer = new XLSXWriter();
    	    $company_name = array($selected_company_details->company_name);
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 5);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 5);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		
    		// empty row
    		$list_add = [];
    		$list_add[] = "";
    		$list_add[] = "";
    		$list_add[] = "";
    		$writer->writeSheetRow('Sheet1', $list_add);
    		
    		$set_col_tk = [];
    		$set_col_tk["SubAccountGroupID"] =  'SubAccountGroupID';
    		$set_col_tk["SubAccountGroupName"] = 'SubAccountGroupName';
    		$set_col_tk["MainGroup"] = 'MainGroup';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
    		$i=1;
    		foreach ($data as $k => $value) {
    		    $list_add = [];
    			$list_add[] = $value["SubActGroupID"];
    			$list_add[] = $value["SubActGroupName"];
    			$mainGroupName = '';
                    foreach ($account_maingroup as $key1 => $value1) {
                        if($value["ActGroupID"] == $value1["ActGroupID"]){
                            $mainGroupName = $value1["ActGroupName"];
                        }
                    }
                $list_add[] = $mainGroupName;
    			$writer->writeSheetRow('Sheet1', $list_add);
    			
    		}
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'AccountSubGroup.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
    
    public function get_accounts_group(){
        
    // POST data
    $postData = $this->input->post();

    // Get data
    $data = $this->accounts_master_model->get_accounts_group($postData);

    echo json_encode($data);
  }
  
    public function get_user_list(){
        // POST data
        $postData = $this->input->post();
        // Get data
        $data = $this->accounts_master_model->get_user_list($postData);
        echo json_encode($data);
    }
    
    public function get_no_act_list(){
        // POST data
        $postData = $this->input->post();
        // Get data
        $data = $this->accounts_master_model->get_no_act_list($postData);
        $data2 = $this->accounts_master_model->get_no_act_list_for_staff($postData);
        $body_data = $this->accounts_master_model->get_selected_record($postData);
        $html = '';
        $html .= '<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" style="float: right;">'; 
        $html .= '<div class="tableFixHead">';
        $html .='<table class="table table-striped table-bordered tableFixHead" width="100%" id="no_show_act_table">';
        $html .='<thead>';
        $html .='<tr>';
        $html .='<th>AccountID</th>';
        $html .='<th>AccountName</th>';
        $html .='<th>AllowedTo View</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        foreach ($data as $key => $value) {
            $html .='<tr>';
            $html .='<td>'.$value["AccountID"].'</td>';
            $html .='<td>'.$value["company"].'</td>';
            $checked = '';
            foreach ($body_data as $key2 => $value2) {
                if($value2['AccountID'] == $value["AccountID"]){
                    $checked = 'checked';
                }
            }
            $html .='<td><input type="checkbox" class="selected_acct" name="selected_acct[]" value="'.$value["AccountID"].'" '.$checked.'></td>';
            $html .='</tr>';
        }
        foreach ($data2 as $key1 => $value1) {
            $html .='<tr>';
            $html .='<td>'.$value1["AccountID"].'</td>';
            $html .='<td>'.$value1["firstname"]." ".$value1["lastname"].'</td>';
            $checked = '';
            foreach ($body_data as $key2 => $value2) {
                if($value2['AccountID'] == $value1["AccountID"]){
                    $checked1 = 'checked';
                }
            }
            $html .='<td><input type="checkbox" class="selected_acct" name="selected_acct[]" value="'.$value1["AccountID"].'" '.$checked1.'></td>';
            $html .='</tr>';
        }
        $html .='</tbody>';
        $html .='</table>';
        $html .="</div>";
        echo json_encode($html);
    }
   public function get_accounts_subgroup(){
        
    // POST data
    $postData = $this->input->post();

    // Get data
    $data = $this->accounts_master_model->get_accounts_subgroup($postData);

    echo json_encode($data);
  }
  
    public function get_staff_details()
     {
       
        $userID = $this->input->post('userID');
        $staff_data = $this->accounts_master_model->get_staff_details($userID);
        echo json_encode($staff_data);
    }
  
  public function get_account_group_details()
     {
       
        $accountID = $this->input->post('act_id');
        $account_data = $this->accounts_master_model->get_account_group_details($accountID);
        echo json_encode($account_data);
     }
    public function get_account_subgroup_details()
     {
       
        $account_subgroupID = $this->input->post('account_subgroupID');
        $account_data = $this->accounts_master_model->get_account_subgroup_details($account_subgroupID);
        echo json_encode($account_data);
     }
     
    public function AccountSubgroupListPopUp()
    {
        $account_subgroup_table = $this->accounts_master_model->GetSupgroupList();
        $html = "";
        foreach ($account_subgroup_table as $key => $value) {
            $html .= '<tr class="get_AccountID" data-id="'.$value["SubActGroupID"].'">';
            $html .= '<td>'.$value["SubActGroupID"].'</td>';
            $html .= '<td>'.$value["SubActGroupName"].'</td>';
            $html .= '<td>'.$value["ActGroupName"].'</td>';
            $html .= '</tr>';
        }
        echo $html;
        //echo json_encode($account_data);
     }
    public function get_account_max_subgroupId()
     {
       
        $maingroup_id = $this->input->post('maingroup_id');
        $account_data = $this->accounts_master_model->get_account_max_subgroupId($maingroup_id);
        echo json_encode($account_data);
     }
     
    public function User_master()
    {
        if (!has_permission_new('user_master', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->post()) {
            
            if (!has_permission_new('user_master', '', 'edit')) {
            access_denied('Invoice Items');
            }
            $data = $this->input->post();
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            /*echo "<pre>";
            print_r($data);
            die;*/
            $affected_row = 0;
            $this->db->where('UserID', $data['userid']);
            $this->db->delete(db_prefix() . 'nsaccountmaster');
            if($this->db->affected_rows()){
                    $affected_row++;
                }
            foreach ($data['selected_acct'] as $id) {
                $permisstion_array = array(
                    "PlantID" =>$selected_company,
                    "AccountID" =>$id,
                    "UserID" =>$data['userid']
                    );
                
                $this->db->insert(db_prefix() . 'nsaccountmaster', $permisstion_array);
                if($this->db->affected_rows()){
                    $affected_row++;
                }
            }
            if($data['new_password'] !=="" && $data['re_password'] !=="" && $data['new_password'] == $data['re_password']){
                $staff_update = array(
                "login_access" =>$data['login_access'],
                "password_erp" =>app_hash_password($data['new_password']),
                "last_password_change" =>date('Y-m-d H:i:s')
                );
            }else{
                $staff_update = array(
                "login_access" =>$data['login_access'],
                );
            }
            
            $this->db->where('AccountID', $data['userid']);
            $this->db->update(db_prefix() . 'staff', $staff_update);
            if($this->db->affected_rows()){
                    $affected_row++;
                }
            if($affected_row > 0){
                set_alert('success', "Updated Successfully...");
                redirect(admin_url('accounts_master/User_master'));
            }else{
                set_alert('warning', "somethng went wrong...");
                redirect(admin_url('accounts_master/User_master'));
            }
            
        }
        $data['user_list'] = $this->accounts_master_model->get_login_user_list($postData);
        $data['title'] = "User Master";
        $this->load->view('admin/accounts_master/user_master', $data);
    }
    
    /* Add new Other staff */
    public function manage_staff()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->post()) {
            
            $data = $this->input->post();
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            /*echo "<pre>";
            print_r($data);
            */
            if (isset($data['departments'])) {
			$departments = $data['departments'];
			unset($data['departments']);
		    }
		    $comp_ids= serialize($this->input->post('company_id1'));
			$_company_assigned = $data['company_id1'];
			unset($data['company_id1']);
			if(isset($data["password"])){
			    $password = app_hash_password($data['password']);
			}else{
			    $password = '';
			}
			unset($data["password"]);
		    //print_r($_company_assigned);
            $staff_Array = array(
                'AccountID' =>$data["account_id"],
                'email' =>$data["email"],
                'username' =>$data["username"],
                'peremail' =>$data["peremail"],
                'firstname' =>$data["firstname"],
                'lastname' =>$data["lastname"],
                'phonenumber' =>$data["phonenumber"],
                'mobile2' =>$data["mobile2"],
                'openingbal' =>$data["opening_bal"],
                'stationName' =>$data["station_name"],
                'ctrlAccountId' =>$data["ctrlAccountId"],
                'password' =>$password,
                'datecreated' =>to_sql_date($data["datecreated"]).' 00:00:00',
                'active' =>$data["active"],
                'sex' =>$data["sex"],/*
                'marital_status' =>$data["account_id"],*/
                'home_town' =>$data["home_town"],
                'current_address' =>$data["current_address"],
                'account_number' =>$data["account_number"],
                'name_account' =>$data["name_account"],
                'issue_bank' =>$data["issue_bank"],
                'staff_comp' =>$comp_ids,
                'state' =>$data["state"],
                'city' =>$data["city"],
                'pincode' =>$data["pin"],
                'login_access' =>$data["login_access"],
                'app_access' =>$data["app_access"],
                'pan_number' =>$data["pan_number"],
                'aadhar_number' =>$data["aadhaar"]
                );
                $id = $this->accounts_master_model->add_user($data);
                if ($id) {
                    
                    if (isset($departments)) {
				        foreach ($departments as $department) {
					        $this->db->insert(db_prefix() . 'staff_departments', [
            						'staffid'      => $id,
            						'departmentid' => $department,
            					]);
				            }
			            }
				    
				    foreach ($_company_assigned as $value) {
				        $this->db->insert(db_prefix() . 'accountbalances', [
                            'PlantID'   => $value,
                            'AccountID' => $data['account_id'],
                            'FY'      => $fy,
                            'BAL1' => $data['opening_bal'],
                        ]);
				    }
					//hr_profile_handle_staff_profile_image_upload($id);
					set_alert('success', _l('added_successfully', _l('staff_member')));
					redirect(admin_url('accounts_master/manage_staff'));
				}else{
				    set_alert('warning', 'something went wrong..');
					redirect(admin_url('accounts_master/manage_staff'));
				}
               /* print_r($staff_Array);
            die;*/
            if(empty($data["edit_account_id"])){
                
                $client_array = array(
                'PlantID'=>$selected_company,
                'AccountID' =>$data["account_id"],
                'CtrlAccountID' =>$data["account_id"],
                'company' =>$data["account_name"],
                'city' =>$data["city"],
                'address' =>$data["address"],
                'Address3' =>$data["address2"],
                'state' =>$data["state"],
                'SubActGroupID' =>$data["Account_Group"],
                'DistributorType' =>24,
                'Blockyn' =>$data["block_ac"],
                'addedfrom' =>!DEFINED('CRON') ? get_staff_user_id() : 0,
                'StationName' =>$data["station_name"],
                'StartDate' =>$data["start_date"]." ".date('h:i:s'),
                'country' =>1
                );
            
            $contact_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'firstname' =>$data["contact_person"],
                'phonenumber' =>$data["mobile_no"],
                'Officeno' =>$data["office_no"],
                'email' =>$data["email_id"],
                'BalancesYN' =>$data["bal_on_bill"],
                'pincode' =>$data["pin"],
                'kms' =>$data["km"],
                'Pan' =>$data["pan_number"],
                'Aadhaarno' =>$data["aadhaar"],
                );
            $sld_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'SLDTypeID' =>$data["Account_sldtype"],
                'UserID' =>$this->session->userdata('username'),
                );
                
                $opn_bal = "-".$data["opening_bal"];
            $actbal_array = array(
                'PlantID' =>$selected_company,
                'AccountID' =>$data["account_id"],
                'BAL1' =>$opn_bal,
                'FY' =>date('y'),
                );
            
           
            $client_data = $this->accounts_master_model->add_client($client_array);
            if($client_data == true){
                $contact_data = $this->accounts_master_model->add_contact($contact_array);
                if($contact_data == true){
                    
                    $sld_data = $this->accounts_master_model->add_sldtype($sld_array);
                    
                    if($sld_data == true){
                        $actbal_data = $this->accounts_master_model->add_act_bal($actbal_array);
                        set_alert('success', _l('added_successfully', 'Account'));
                        //$redUrl = admin_url('order/pending_orders/' . $id);
                        $redUrl = admin_url('accounts_master/staff_master');
                        redirect($redUrl);
                    }
                }
                
            }
            
            }else{
            
                $account_id = $data["edit_account_id"];
            $client_array = array(
                'company' =>$data["account_name"],
                'state' =>$data["state"],
                'city' =>$data["city"],
                'address' =>$data["address"],
                'Address3' =>$data["address2"],
                'StationName' =>$data["station_name"],
                'Blockyn' =>$data["block_ac"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
            $contact_array = array(
                'firstname' =>$data["contact_person"],
                'pincode' =>$data["pin"],
                'kms' =>$data["km"],
                'Officeno' =>$data["office_no"],
                'phonenumber' =>$data["mobile_no"],
                'email' =>$data["email_id"],
                'BalancesYN' =>$data["bal_on_bill"],
                'Pan' =>$data["pan_number"],
                'Aadhaarno' =>$data["aadhaar"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
                
                $sld_array = array(
                'SLDTypeID' =>$data["Account_sldtype"],
                'UserID2' =>$this->session->userdata('username'),
                'Lupdate' =>date('Y-m-d h:i:s'),
                );
                
             $client_data = $this->accounts_master_model->update_client($client_array,$account_id);
             $contact_data = $this->accounts_master_model->update_contact($contact_array,$account_id);
             $sld_data = $this->accounts_master_model->update_sldtype($sld_array,$account_id);
            
                
                if($contact_data == true){
                    
                        set_alert('success', "Account Update successfully");
                        $redUrl = admin_url('accounts_master/edit_other_staff/' . $account_id);
                        //$redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                    
                }else{
                    
                    set_alert('error', "somthing went wrong..");
                        $redUrl = admin_url('accounts_master/edit_other_staff/' . $account_id);
                        //$redUrl = admin_url('accounts_master/manage_accounts');
                        redirect($redUrl);
                }
                
            }
            
        }
      
        $data['title'] = "Manage User";
        $data['state_list'] = $this->accounts_master_model->get_state();
        //$data['distributor_type'] = $this->accounts_master_model->get_distibutor_type();
        $data['user_work_on'] = $this->accounts_master_model->get_user_work_on();
        $data['account_subgroup'] = $this->accounts_master_model->get_subgroup_for_usermaster();
        $data['rootcompany'] = $this->clients_model->get_rootcompany();
        $data['departments']   = $this->departments_model->get();
        /*echo "<pre>";
        print_r($data['rootcompany']);
        die;*/
        $this->load->view('admin/accounts_master/adduser_master', $data);
    }

    public function staff_table()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('staff_accounts_table');
    }
    
    /* List all available SUNDRY CREDITORS  */
    public function sundry_creditors()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            access_denied('Invoice Items');
        }
        $data['state'] = $this->clients_model->getallstate();
        $data['title'] = "SUNDRY CREDITORS";
        $this->load->view('admin/accounts_master/sundry_creditors_master', $data);
    }

    public function sundry_creditors_table()
    {
        if (!has_permission_new('account_head', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('sundry_creditors_table');
    }

    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission_new('account_head', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!has_permission_new('account_head', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->route_master_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->route_master_model->get($id),
                    ]);
                } else {
                    if (!has_permission_new('account_head', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->route_master_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }

    public function import()
    {
        if (!has_permission_new('account_head', '', 'create')) {
            access_denied('Items Import');
        }

        $this->load->library('import/import_items', [], 'import');

        $this->import->setDatabaseFields($this->db->list_fields(db_prefix().'items'))
                     ->setCustomFields(get_custom_fields('items'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
            $this->import->setSimulation($this->input->post('simulate'))
                          ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                          ->setFilename($_FILES['file_csv']['name'])
                          ->perform();

            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['title'] = _l('import');
        $this->load->view('admin/invoice_items/import', $data);
    }

    public function add_group()
    {
        if ($this->input->post() && has_permission_new('account_head', '', 'create')) {
            $this->invoice_items_model->add_group($this->input->post());
            set_alert('success', _l('added_successfully', _l('item_group')));
        }
    }

    public function update_group($id)
    {
        if ($this->input->post() && has_permission_new('account_head', '', 'edit')) {
            $this->invoice_items_model->edit_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_group')));
        }
    }

    public function delete_group($id)
    {
        if (has_permission_new('account_head', '', 'delete')) {
            if ($this->invoice_items_model->delete_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
    
    public function add_main_group()
    {
        if ($this->input->post() && has_permission_new('account_head', '', 'create')) {
            
            $data = 
            $this->invoice_items_model->add_main_group([
                'name' => $this->input->post('name')
            ]);
            if($data){
                set_alert('success', _l('added_successfully', _l('item_main_group')));
            }else {
                
                set_alert('warning', 'Somthing went wrong');
            }
            
        }
    }
    
    public function update_main_group($id)
    {
        if ($this->input->post() && has_permission_new('account_head', '', 'edit')) {
            $this->invoice_items_model->edit_main_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_main_group')));
        }
    }
    
    public function update_sub_group($id)
    {
        if ($this->input->post() && has_permission_new('account_head', '', 'edit')) {
            $this->invoice_items_model->edit_sub_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_sub_group')));
        }
    }
    
    public function delete_main_group($id)
    {
        if (has_permission_new('account_head', '', 'delete')) {
            if ($this->invoice_items_model->delete_main_group($id)) {
                set_alert('success', _l('deleted', _l('item_main_group')));
            }
        }
        redirect(admin_url('invoice_items?main_groups_modal=true'));
    }
    
    public function delete_sub_group($id)
    {
        if (has_permission_new('account_head', '', 'delete')) {
            if ($this->invoice_items_model->delete_sub_group($id)) {
                set_alert('success', _l('deleted', _l('item_sub_group')));
            }
        }
        redirect(admin_url('invoice_items?sub_groups_modal=true'));
    }
    
    public function add_sub_group()
    {
        if ($this->input->post() && has_permission_new('account_head', '', 'create')) {
            
            $data = 
            $this->invoice_items_model->add_sub_group([
                'name' => $this->input->post('name'),
                'main_group_id'    => $this->input->post('id'),
            ]);
            set_alert('success', _l('added_successfully', _l('item_sub_group')));
        }
    }

    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission_new('account_head', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('vehicles'));
        }

        $response = $this->route_master_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', 'Route Delected Successfully..');
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('route_master'));
    }

    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $has_permission_delete = has_permission_new('account_head', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($has_permission_delete) {
                            if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                            }
                        }
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_items_deleted', $total_deleted));
        }
    }

    public function search()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->search($this->input->post('q')));
        }
    }

    /* Get item by id / ajax */
    public function get_route_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $vehicle                     = $this->route_master_model->get($id);
            

            echo json_encode($vehicle);
        }
    }
    
    public function InvoiceNote()
    {
        if (!has_permission_new('user_rights', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->post()) {
            
            if (!has_permission_new('user_rights', '', 'view')) {
            access_denied('Invoice Items');
            }
            $data = $this->input->post();
            $selected_company = $this->session->userdata('root_company');
            $UserID = $this->session->userdata('username');
            
            $affected_row = 0;
            $this->db->where('PlantID', $selected_company);
            $this->db->delete(db_prefix() . 'invoicenote');
            if($this->db->affected_rows()){
                $affected_row++;
                $invoice_note = array(
                    "PlantID" =>$selected_company,
                    "note" =>$data['comment'],
                    "UserID" =>$UserID,
                    "Transdate" =>date('Y-m-d H:i:s')
                );
                
                $this->db->insert(db_prefix() . 'invoicenote', $invoice_note);
                set_alert('success', "Updated Successfully...");
                redirect(admin_url('accounts_master/InvoiceNote'));
            }else{
                set_alert('warning', "something went wrong...");
                redirect(admin_url('accounts_master/InvoiceNote'));
            }
        }
        $data['Getnote'] = $this->accounts_master_model->GetinvoiceNote();
        $data['title'] = "Invoice Note";
        $this->load->view('admin/accounts_master/invoiceNote', $data);
    }
}
