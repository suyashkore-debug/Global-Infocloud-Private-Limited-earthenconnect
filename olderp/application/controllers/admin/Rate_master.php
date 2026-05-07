<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rate_master extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('rate_master_model');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission_new('ratemaster', '', 'view')) {
            access_denied('rate Master');
        }

        $this->load->model('taxes_model');
         $this->load->model('clients_model');
         $this->load->model('sale_reports_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->rate_master_model->get_groups();
        $data['states'] = $this->rate_master_model->get_state();
        $data['groups'] = $this->clients_model->get_groups();
        $data['items_main_groups'] = $this->rate_master_model->get_main_groups();
        $data['items_sub_groups'] = $this->rate_master_model->get_sub_groups();
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['title'] = _l('rate_master');
        $this->load->view('admin/rate_master/manage', $data);
    }

    public function table()
    {
        if (!has_permission_new('ratemaster', '', 'view')) {
            ajax_access_denied();
        }
        if ($this->input->is_ajax_request()) {
			if($this->input->post()){
        $this->app->get_table_data('rate_master');
			}
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
                        'item'    => $this->rate_model_model->get($id),
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
    
    /* Edit or update items / ajax request /*/
    public function add_edit_rate_master()
    {
        if (has_permission_new('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                
                if ($data['rate_master_id'] == '') {
                    if (!has_permission_new('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $data["item_id"] = $data["itemid"];
                    $data["assigned_rate"] = $data["assignrate"];
                    unset($data["itemid"]);
                    unset($data["assignrate"]);
                    unset($data["itemid"]);
                    unset($data["assignrate"]);
                    unset($data["description"]);
                    unset($data["group_id"]);
                    unset($data["item_code"]);
                    unset($data["long_description"]);
                    unset($data["rate"]);
                    unset($data["subgroup_id"]);
                    unset($data["tax"]);
                    unset($data["tax2"]);
                    unset($data["unit"]);
                     unset($data["rate_master_id"]);
                    
                    $id = $this->rate_master_model->add_rate_master($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('rate_master'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                } else {
                    if (!has_permission_new('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    
                    //$data["item_id"] = $data["itemid"];
                    $data["assigned_rate"] = $data["assignrate"];
                    $data["effective_date"] = to_sql_date($data["effective_date"])." 00:00:01";
                    /*unset($data["state_id"]);
                    unset($data["distributor_id"]);*/
                    unset($data["itemid"]);
                    unset($data["assignrate"]);
                    unset($data["description"]);
                    unset($data["group_id"]);
                    $data["item_code"] = $data["item_code"];
                    unset($data["long_description"]);
                    unset($data["rate"]);
                    unset($data["subgroup_id"]);
                    unset($data["tax"]);
                    unset($data["tax2"]);
                    unset($data["unit"]);
                    
                    $get_data = $this->rate_master_model->get_rate_master_data_by_id($data["state_id"],$data["distributor_id"]);
                    if($get_data["distributor_id"] == $data["distributor_id"] && $get_data["state_id"] == $data["state_id"]){
                       
                        $success = $this->rate_master_model->edit_rate_master($data);
                    }else {
                        unset($data["rate_master_id"]);
                        $success = $this->rate_master_model->add_rate_master($data);
                    }
                   
                    
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', _l('rate_master'));
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

        $this->load->library('import/import_rate_master', [], 'import');
        $mm = array('item_id','assigned_rate');

        $this->import->setDatabaseFields($mm)
                     ->setCustomFields(get_custom_fields('rate_master'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                $selected_company = $this->session->userdata('root_company');
                $cuurent_user = $this->session->userdata('username');
                $states = $this->input->post('states');
                $distributor_id = $this->input->post('distributor_id');
                $effective_date = $this->input->post('effective_date');
                
            $this->import->setSimulation($this->input->post('simulate'))
                          ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                          ->setFilename($_FILES['file_csv']['name'])
                          ->perform($states, $distributor_id, $effective_date,$selected_company,$cuurent_user);

            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
                redirect(admin_url('rate_master/import'));
            }
        }
        $data['items_groups'] = $this->rate_master_model->get_groups();
        $data['states'] = $this->rate_master_model->get_state();
        $data['groups'] = $this->clients_model->get_groups();
        $data['items_main_groups'] = $this->rate_master_model->get_main_groups();
        $data['items_sub_groups'] = $this->rate_master_model->get_sub_groups();
        $data['title'] = _l('import');
        $this->load->view('admin/rate_master/import', $data);
    }

    
    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission_new('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        /*echo $id;
        die;*/

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->rate_master_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
       redirect(admin_url('rate_master'));
    }


    public function search()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->search($this->input->post('q')));
        }
    }

    /* Get item by id / ajax */
    public function get_item_by_id($id,$state_id,$distributor_id)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->rate_master_model->get($id,$state_id,$distributor_id);
            $item->long_description   = nl2br($item->long_description);
            $item->custom_fields_html = render_custom_fields('items', $id, [], ['items_pr' => true]);
            $item->custom_fields      = [];

            $cf = get_custom_fields('items');

            foreach ($cf as $custom_field) {
                $val = get_custom_field_value($id, $custom_field['id'], 'items_pr');
                if ($custom_field['type'] == 'textarea') {
                    $val = clear_textarea_breaks($val);
                }
                $custom_field['value'] = $val;
                $item->custom_fields[] = $custom_field;
            }

            echo json_encode($item);
        }
    }
    
    public function export_rate_master()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
    	$data =$this->rate_master_model->table_data($this->input->post());
        $distributor_id = $this->input->post('distributor_id');
        $state_id = $this->input->post('state_id');
        $data_state_name  = $this->db->get_where('tblxx_statelist',array('short_name'=>$state_id))->row_array(); 
        $data_distributor_name  = $this->db->get_where('tblcustomers_groups',array('id'=>$distributor_id))->row_array(); 
        $this->load->model('sale_reports_model');    
    	$selected_company_details    = $this->sale_reports_model->get_company_detail();
    		
    		$writer = new XLSXWriter();
    		//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
    		//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
    		//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
    		//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
    		
    		$company_name = array($selected_company_details->company_name);
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $selected_company_details->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		$msg = "Rate Master State: ".$data_state_name["state_name"] ." Distributor: " .$data_distributor_name["name"];
    		$filter = array($msg);
    		$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 6);  //merge cells
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
    		$set_col_tk["ItemID"] =  'ItemID';
    		$set_col_tk["Item Name"] = 'Item Name';
    		$set_col_tk["Unit"] = 'Unit';
    		$set_col_tk["Basic Rate"] = 'Basic Rate';
    		$set_col_tk["GST"] = 'GST';
    		$set_col_tk["SaleRate"] = 'SaleRate';
    		
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    		
    		foreach ($data as $k => $value) {
    		    
    			$list_add = [];
    			$list_add[] = $value["item_code"];
    			$list_add[] = $value["description"];
    			$list_add[] = $value["unit"];
    			if($value['state_id_2'] == !"" && $value['state_id_2'] !== "0" && $value['state_id_2'] == $state_id && $value['distributor_id_2'] == !"" && $value['distributor_id_2'] !== "0" && $value['distributor_id_2'] == $distributor_id ){
                    $new_rate = $value['assigned_2'];
                } else {
                    $new_rate = 0;
                }
                $rate = app_format_money($new_rate, get_base_currency());
                
    			$list_add[] = $rate;
    			$list_add[] = app_format_number($value['taxrate'])."%";
    			$p = $value['taxrate'] /100;
                $Y = $p * $new_rate;
                $sale_rate = $new_rate + $Y;
                $sale_field = number_format($sale_rate, 2);
    			$list_add[] = $sale_field;
    			
    			$writer->writeSheetRow('Sheet1', $list_add);
    			
            }
    	    
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'RateMaster.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }
    
    public function load_data(){
        // print_r($this->input->post());
        $data =$this->rate_master_model->table_data($this->input->post());
       $distributor_id = $this->input->post('distributor_id');
       $state_id = $this->input->post('state_id');
      $data_state_name  = $this->db->get_where('tblxx_statelist',array('short_name'=>$state_id))->row_array(); 
       $data_distributor_name  = $this->db->get_where('tblcustomers_groups',array('id'=>$distributor_id))->row_array(); 
        
        $html ='';
        foreach($data as $value){
            $html.= '<tr>';
             if (has_permission('ratemaster', '', 'edit')) {
            $item = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $value['rate_id'] . '">' . $value['item_code'] . '</a>';
            }else {
                $item = $value['item_code'];
            }
            $html.= '<td>'.$item.'</td>';
             if (has_permission('ratemaster', '', 'edit')) {
        
                $desc = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $value['rate_id'] . '">' . $value['description'] . '</a>';
                
            }else {
                $desc = $value['description'];
            }
            $html.= '<td>'.$desc.'</td>';
            $html.= '<td>'.$value['unit'].'</td>';
              if($value['state_id_2'] == !"" && $value['state_id_2'] !== "0" && $value['state_id_2'] == $state_id && $value['distributor_id_2'] == !"" && $value['distributor_id_2'] !== "0" && $value['distributor_id_2'] == $distributor_id ){
                    $new_rate = $value['assigned_2'];
                } else {
                    $new_rate = 0;
                }
                if (has_permission('ratemaster', '', 'edit')) {
                $rate = '<a href="#" data-toggle="modal" data-target="#rate_master_modal" data-id="' . $value['rate_id'] . '">' . app_format_money($new_rate, get_base_currency()) . '</a>';
                }else{
                    $rate = app_format_money($new_rate, get_base_currency());
                }
            $html.= '<td>'.$rate.'</td>';
                $aRow['taxrate'] = $value['taxrate'] ?? 0;
               $tax_field             = '<span data-toggle="tooltip" title="' . $value['taxname_1'] . '" data-taxid="' . $value['tax_id_1'] . '">' . app_format_number($aRow['taxrate']) . '%' . '</span>';
            $html.= '<td>'.$tax_field.'</td>';
              
                $p = $value['taxrate'] /100;
                $Y = $p * $new_rate;
                $sale_rate = $new_rate + $Y;
                $sale_field = number_format($sale_rate, 2);
           
            $html.= '<td>'.$sale_field.'</td>';
            $html.= '</tr>';
        }
        // echo $html;
        $data_array =array('html'=>$html,'state'=>$data_state_name,'distributor'=>$data_distributor_name);
      echo json_encode($data_array);
    }
}
