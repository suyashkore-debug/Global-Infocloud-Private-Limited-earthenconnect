<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_items extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
        $this->load->model('hsn_master_model');
    }

    /* Add Edit available items */
    public function index()
    {
        if (!has_permission_new('items', '', 'view')) {
            access_denied('Invoice Items');
        }

        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['items_main_groups'] = $this->invoice_items_model->get_main_groups();
        $data['items_sub_groups'] = $this->invoice_items_model->get_sub_groups();
        $data['items_rack'] = $this->invoice_items_model->get_item_rack();
        $data['hsn'] = $this->hsn_master_model->get();
        $data['RootCompany'] = $this->invoice_items_model->GetRootCompany();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();
        
        $this->load->model('accounts_master_model');
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $data['table_data'] = $this->invoice_items_model->get_table_data();
        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manageNew', $data);
    }
    
    /* List all available items */
    public function ItemList()
    {
        if (!has_permission_new('ItemList', '', 'view')) {
            access_denied('Invoice Items');
        }

        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['items_main_groups'] = $this->invoice_items_model->get_main_groups();
        $data['items_sub_groups'] = $this->invoice_items_model->get_sub_groups();
        $data['items_rack'] = $this->invoice_items_model->get_item_rack();
        $data['hsn'] = $this->hsn_master_model->get();
        $data['RootCompany'] = $this->invoice_items_model->GetRootCompany();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();
        
        $this->load->model('accounts_master_model');
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $data['table_data'] = $this->invoice_items_model->get_table_data();
        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manage', $data);
    }
    
    /* List all available MAinitemGroups */
    public function MainGroups()
    {
        if (!has_permission_new('itemsmaingrp', '', 'view')) {
            access_denied('Invoice Items');
        }
        $data['RootCompany'] = $this->invoice_items_model->GetRootCompany();
        $data['table_data'] = $this->invoice_items_model->get_MainItemGroup_data();
        $data['title'] = _l('ItemMainGroups');
        $this->load->view('admin/invoice_items/ItemMainGroup', $data);
    }
    
    /* List all available itemGroups */
    public function ItemGroups()
    {
        if (!has_permission_new('itemssubgrp', '', 'view')) {
            access_denied('Invoice Items');
        }
        $data['MainItemGroup'] = $this->invoice_items_model->get_MainItemGroup_data();
        $data['table_data'] = $this->invoice_items_model->get_ItemGroup_data();
        $data['lastId'] = $this->invoice_items_model->get_last_recordItemGroup();
        $data['title'] = _l('ItemGroups');
        $this->load->view('admin/invoice_items/ItemGroup', $data);
    }
    
    /* List all available ItemDivision */
    public function ItemDivision()
    {
        if (!has_permission_new('itemsdivision', '', 'view')) {
            access_denied('Invoice Items');
        }
        $data['table_data'] = $this->invoice_items_model->get_ItemDivision_data();
        $data['lastId'] = $this->invoice_items_model->get_last_recordItemDevision();
        $data['title'] = _l('ItemDivision');
        $this->load->view('admin/invoice_items/ItemDivision', $data);
    }

    public function export_ItemMaster()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
            $data = $this->invoice_items_model->get_table_data();
            $this->load->model('accounts_master_model');
    		$selected_company_details    = $this->accounts_master_model->get_company_detail();
    		
    		$writer = new XLSXWriter();
    		//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
    		//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
    		//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
    		//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
    		
    		$company_name = array($selected_company_details->company_name);
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 4);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		
    	
    		
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
    		$list_add[] = "";
    		$list_add[] = "";
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
    		$set_col_tk["ItemID"] =  'ItemID';
    		$set_col_tk["Item Name"] = 'Item Name';
    		$set_col_tk["MeasuredIn"] = 'MeasuredIn';
    		$set_col_tk["Division Name"] = 'Division Name';
    		$set_col_tk["Group Name"] = 'Group Name';
    		$set_col_tk["HsnCode"] =  'HsnCode';
    		$set_col_tk["Tax"] = 'Tax';
    		$set_col_tk["BowlQty"] = 'BowlQty';
    		$set_col_tk["CaseQty"] = 'CaseQty';
    		$set_col_tk["CrateQty"] = 'CrateQty';
    		$set_col_tk["MinQty"] =  'MinQty';
    		$set_col_tk["CaseWeight"] = 'CaseWeight';
    		$set_col_tk["LocalSupply"] = 'LocalSupply';
    		$set_col_tk["OutstSupply"] = 'OutstSupply';
    		$set_col_tk["MonitorStock"] = 'MonitorStock';
    		$set_col_tk["RackId"] =  'RackId';
    		$set_col_tk["SubrackId"] = 'SubrackId';
    		$set_col_tk["MinWtg"] = 'MinWtg';
    		$set_col_tk["MinDay"] = 'MinDay';
    		$set_col_tk["Isactive"] = 'Isactive';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    		
    		foreach ($data as $k => $value) {
    		    
    			$list_add = [];
    			$list_add[] = $value["item_code"];
    			$list_add[] = $value["description"];
    			$list_add[] = $value["unit"];
    			$list_add[] = $value["group_name"];
    			$list_add[] = $value["subgroup_name"];
    			$list_add[] = $value["hsn_code"];
    			$list_add[] = $value["taxrate"]."%";
    			$list_add[] = $value["bowl_qty"];
    			$list_add[] = $value["case_qty"];
    			$list_add[] = $value["crate_qty"];
    			$list_add[] = $value["min_qty"];
    			$list_add[] = $value["case_weight"];
    			$list_add[] = $value["local_supply_in"];
    			$list_add[] = $value["outst_supply_in"];
    			$list_add[] = $value["monitorstock"];
    			$list_add[] = $value["rack_id"];
    			$list_add[] = $value["subrack_id"];
    			$list_add[] = $value["MinWtg"];
    			$list_add[] = $value["min_day"];
    			$list_add[] = $value["isactive"];
    			$writer->writeSheetRow('Sheet1', $list_add);
    		
    	    }
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'ItemMaster.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }

    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission_new('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!has_permission_new('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->invoice_items_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->invoice_items_model->get($id),
                    ]);
                } else {
                    if (!has_permission_new('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->invoice_items_model->edit($data);
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
        if (!has_permission_new('items', '', 'create')) {
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
        if ($this->input->post() && has_permission_new('items', '', 'create')) {
            $id = $this->invoice_items_model->add_group($this->input->post());
            if($id == "false"){
                set_alert('warning', 'Somthing went wrong');
            }else{
                set_alert('success', _l('added_successfully', _l('item_group')));
            }
            
        }
    }

    public function update_group($id)
    {
        if ($this->input->post() && has_permission_new('items', '', 'edit')) {
            $success = $this->invoice_items_model->edit_group($this->input->post(), $id);
            if($success == "true"){
                set_alert('success', _l('updated_successfully', _l('item_group')));
            }else{
                set_alert('warning', 'Somthing went wrong');
            }
            
        }
    }

    public function delete_group($id)
    {
        if (has_permission_new('items', '', 'delete')) {
            $sucess = $this->invoice_items_model->delete_group($id);
            if ($sucess == "true") {
                set_alert('success', _l('deleted', _l('item_group')));
            }else{
                set_alert('warning', 'Somthing went wrong');
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
    
    public function add_main_group()
    {
        if ($this->input->post() && has_permission_new('items', '', 'create')) {
            
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
        if ($this->input->post() && has_permission_new('items', '', 'edit')) {
            $success = $this->invoice_items_model->edit_main_group($this->input->post(), $id);
            if($success == "true"){
                set_alert('success', _l('updated_successfully', _l('item_main_group')));
            }else{
                set_alert('warning', 'Somthing went wrong');
            }
            
        }
    }
    
    public function update_sub_group($id)
    {
        if ($this->input->post() && has_permission_new('items', '', 'edit')) {
            $this->invoice_items_model->edit_sub_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_sub_group')));
        }
    }
    
    public function delete_main_group($id)
    {
        if (has_permission_new('items', '', 'delete')) {
            $id = $this->invoice_items_model->delete_main_group($id);
            if ($id == "true") {
                set_alert('success', _l('deleted', _l('item_main_group')));
                
            }else{
                set_alert('warning', "somthing went wrong");
            }
        }
        redirect(admin_url('invoice_items?main_groups_modal=true'));
    }
    
    public function delete_sub_group($id)
    {
        if (has_permission_new('items', '', 'delete')) {
            $id = $this->invoice_items_model->delete_sub_group($id);
            if ($id == "true") {
                set_alert('success', _l('deleted', _l('item_sub_group')));
                
            }else{
                set_alert('warning', "somthing went wrong");
            }
        }
        redirect(admin_url('invoice_items?sub_groups_modal=true'));
    }
    
    public function add_sub_group()
    {
        if ($this->input->post() && has_permission_new('items', '', 'create')) {
            
            $data = 
            $id = $this->invoice_items_model->add_sub_group([
                'name' => $this->input->post('name'),
                'main_group_id'    => $this->input->post('id'),
            ]);
            if($id == "false"){
                
                set_alert('warning', "somthing went wrong");
            }else{
                set_alert('success', _l('added_successfully', _l('item_sub_group')));
                
            }
            
        }
    }

    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission_new('items', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }

    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $has_permission_delete = has_permission_new('items', '', 'delete');
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
    public function get_item_by_id($id, $dist_type ="", $dist_st ="")
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->invoice_items_model->get($id);
            $item->long_description   = nl2br($item->long_description);
            $rate_from_master = $this->invoice_items_model->get_rate_master_data_by_id2($id, $dist_type, $dist_st);
            $item->rate   = $rate_from_master->assigned_rate;
            echo json_encode($item);
        }
    }
    
    /* Get item Details by ItemID / ajax */
    public function GetItemDetailByID()
    {
        $ItemID = $this->input->post('ItemID');
        $item                     = $this->invoice_items_model->get($ItemID);
        echo json_encode($item);
    }
    
    /* Get Main item Group Details by ItemID / ajax */
    public function GetMainItemGroupDetailByID()
    {
        $ItemGroupID = $this->input->post('ItemGroupID');
        $itemMainGroupDetails  = $this->invoice_items_model->getMainItemGroupDetails($ItemGroupID);
        echo json_encode($itemMainGroupDetails);
    }
    
    /* Get item Division Details by ItemID / ajax */
    public function GetItemDivisionDetailByID()
    {
        $ItemDivisionID = $this->input->post('ItemDivisionID');
        $itemDivisionDetails  = $this->invoice_items_model->getitemDivisionDetails($ItemDivisionID);
        echo json_encode($itemDivisionDetails);
    }
    
    /* Get item Group Details by ItemID / ajax */
    public function GetItemGroupDetailByID()
    {
        $ItemGroupID = $this->input->post('ItemGroupID');
        $itemGroupDetails  = $this->invoice_items_model->getItemGroupDetails($ItemGroupID);
        echo json_encode($itemGroupDetails);
    }
    
    /* Save New ItemID / ajax */
    public function SaveItemID()
    {
        $data = array(
            'item_code'=>strtoupper($this->input->post('item_code')),
            'description'=>$this->input->post('description'),
            'long_description'=>$this->input->post('description'),
            'crate_qty'=>$this->input->post('crate_qty'),
            'case_qty'=>$this->input->post('case_qty'),
            'bowl_qty'=>$this->input->post('bowl_qty'),
            'min_qty'=>$this->input->post('min_qty'),
            'min_day'=>$this->input->post('min_day'),
            'case_weight'=>$this->input->post('case_weight'),
            'tax'=>$this->input->post('tax'),
            'unit'=>$this->input->post('unit'),
            'subgroup_id'=>$this->input->post('subgroup_id'),
            'group_id'=>$this->input->post('group_id'),
            'local_supply_in'=>$this->input->post('local_supply_in'),
            'outst_supply_in'=>$this->input->post('outst_supply_in'),
            'monitorstock'=>$this->input->post('monitorstock'),
            'hsn_code'=>$this->input->post('hsn_code'),
            'rack_id'=>$this->input->post('rack_id'),
            'subrack_id'=>$this->input->post('subrack_id'),
            /*'isactive'=>$this->input->post('isactive'),*/
        );
        $StockQty = $this->input->post('OQty');
        $ItemStatus_new = $this->input->post('ItemStatus_new');
        $item                     = $this->invoice_items_model->SaveItemID($data,$StockQty,$ItemStatus_new);
        echo json_encode($item);
    }
    
    /* Save New Main Item Group / ajax */
    public function SaveMainItemGroup()
    {
        $data = array(
            'id'=>$this->input->post('MainItemGroupID'),
            'name'=>$this->input->post('MainItemGroupName'),
        );
        $monitorstock_new = $this->input->post('monitorstock_new');
        $MainitemGroup  = $this->invoice_items_model->SaveMainItemGroup($data,$monitorstock_new);
        echo json_encode($MainitemGroup);
    }
    
    /* Save New  Item Division / ajax */
    public function SaveItemDivision()
    {
        $data = array(
            'id'=>$this->input->post('ItemDivisionID'),
            'name'=>$this->input->post('ItemDivisionName'),
        );
        $itemDivision  = $this->invoice_items_model->SaveItemDivision($data);
        echo json_encode($itemDivision);
    }
    
    /* Save New ItemID Group / ajax */
    public function SaveItemGroup()
    {
        $data = array(
            'id'=>$this->input->post('MainItemGroupID'),
            'name'=>$this->input->post('ItemGroupName'),
            'main_group_id'=>$this->input->post('MainItemGroupName'),
        );
        $itemGroup  = $this->invoice_items_model->SaveItemGroup($data);
        echo json_encode($itemGroup);
    }
    
    /* Update Exiting ItemID / ajax */
    public function UpdateItemID()
    {
        $data = array(
            
            'description'=>$this->input->post('description'),
            'long_description'=>$this->input->post('description'),
            'crate_qty'=>$this->input->post('crate_qty'),
            'case_qty'=>$this->input->post('case_qty'),
            'bowl_qty'=>$this->input->post('bowl_qty'),
            'min_qty'=>$this->input->post('min_qty'),
            'min_day'=>$this->input->post('min_day'),
            'case_weight'=>$this->input->post('case_weight'),
            'tax'=>$this->input->post('tax'),
            'unit'=>$this->input->post('unit'),
            'subgroup_id'=>$this->input->post('subgroup_id'),
            'group_id'=>$this->input->post('group_id'),
            'local_supply_in'=>$this->input->post('local_supply_in'),
            'outst_supply_in'=>$this->input->post('outst_supply_in'),
            'monitorstock'=>$this->input->post('monitorstock'),
            'hsn_code'=>$this->input->post('hsn_code'),
            'rack_id'=>$this->input->post('rack_id'),
            'subrack_id'=>$this->input->post('subrack_id'),
            /*'isactive'=>$this->input->post('isactive'),*/
        );
        $StockQty = $this->input->post('OQty');
        $ItemStatus_new = $this->input->post('ItemStatus_new');
        $item_code = $this->input->post('item_code');
        $item                     = $this->invoice_items_model->UpdateItemID($data,$StockQty,$item_code,$ItemStatus_new);
        echo json_encode($item);
    }
    
    /* Update Exiting MainItemGroup / ajax */
    public function UpdateMainItemGroup()
    {
        $data = array(
            'name'=>$this->input->post('MainItemGroupName'),
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
        );
        $itemGroupID = $this->input->post('MainItemGroupID');
        $monitorstock_new = $this->input->post('monitorstock_new');
        $itemGroupID                     = $this->invoice_items_model->UpdateMainItemGroup($data,$itemGroupID,$monitorstock_new);
        echo json_encode($itemGroupID);
    }
    
    /* Update Exiting Item Division / ajax */
    public function UpdateItemDivision()
    {
        $data = array(
            'name'=>$this->input->post('ItemDivisionName'),
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
        );
        $ItemDivisionID = $this->input->post('ItemDivisionID');
        $itemDivision  = $this->invoice_items_model->UpdateItemDivision($data,$ItemDivisionID);
        echo json_encode($itemDivision);
    }
    
    /* Update Exiting ItemGroup / ajax */
    public function UpdateItemGroup()
    {
        $data = array(
            
            'name'=>$this->input->post('ItemGroupName'),
            'main_group_id'=>$this->input->post('MainItemGroup'),
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
        );
        $itemGroupID = $this->input->post('ItemGroupID');
        $itemGroupID                     = $this->invoice_items_model->UpdateItemGroup($data,$itemGroupID);
        echo json_encode($itemGroupID);
    }
    
    
}
