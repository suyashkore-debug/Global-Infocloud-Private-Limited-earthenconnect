<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Rate_master extends AdminController
	{
		private $not_importable_fields = ['id'];
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('rate_master_model');
			$this->load->model('sale_reports_model');
		}
		
		/* List all available items */
		public function index()
		{
			if (!has_permission_new('ratemaster', '', 'view')) {
				access_denied('rate Master');
			}
			
			$this->load->model('taxes_model');
			$this->load->model('clients_model');
			
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
		//========================== Rate Master On page load ==========================
		public function import()
		{
			if (!has_permission_new('ratemaster', '', 'create')) {
				access_denied('Items Import');
			}
			
			$this->load->library('import/import_rate_master', [], 'import');
			$mm = array('item_id','assigned_rate','dis_per');
			
			$this->import->setDatabaseFields($mm)
			->setCustomFields(get_custom_fields('rate_master'));
			
			$field = array('Distributor_Id','Distributor Type','assigned_rate','Discount');
			$this->import->setDatabaseFieldsItemwise($field)
			->setCustomFields(get_custom_fields('rate_master'));
			
			if ($this->input->post('download_sample') === 'true') {
				$this->import->downloadSample();
			}
			if ($this->input->post('download_sample_itemwise') === 'true') {
				$this->import->downloadSampleItemWise();
			}
			
			if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
				// echo "<pre>";print_r($this->input->post());die;
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
			$data['ItemList'] = $this->rate_master_model->GetItemList();
			$data['FGItemList'] = $this->rate_master_model->GetFGItemList();
			$data['states'] = $this->rate_master_model->get_state();
			$data['groups'] = $this->clients_model->get_groups();
			$data['items_main_groups'] = $this->rate_master_model->get_main_groups();
			$data['items_sub_groups'] = $this->rate_master_model->get_sub_groups();
			$data['title'] = _l('import');
			$this->load->view('admin/rate_master/import', $data);
		}
		
		public function ImportRateItemWise()
		{
			$this->load->library('import/import_rate_master', [], 'import');
			$field = array('distributor_id','Distributor_Type_Name','assigned_rate','dis_per');
			$this->import->setDatabaseFieldsItemwise($field)
			->setCustomFields(get_custom_fields('rate_master'));
			if ($this->input->post()
            && isset($_FILES['file_csv2']['name']) && $_FILES['file_csv2']['name'] != '') {
				
                $selected_company = $this->session->userdata('root_company');
                $cuurent_user = $this->session->userdata('username');
                $states = $this->input->post('states2');
                $ItemID = $this->input->post('ItemID');
                $dis_per = $this->input->post('dis_per2');
                $effective_date = $this->input->post('effective_date2');
                // echo "<pre>";print_r($this->input->post());die;
				$this->import->setSimulation($this->input->post('simulate'))
				->setTemporaryFileLocation($_FILES['file_csv2']['tmp_name'])
				->setFilename($_FILES['file_csv2']['name'])
				->performItemWise($states, $ItemID, $effective_date,$selected_company,$cuurent_user);
				
				$data['total_rows_post'] = $this->import->totalRows();
				
				if (!$this->import->isSimulation()) {
					set_alert('success', _l('import_total_imported', $this->import->totalImported()));
					redirect(admin_url('rate_master/import'));
				}
			}
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
		
		public function load_data()
		{
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
		
		//===================== ItemWise Rate List Page load ===========================
		public function ItemWiseRateList()
		{
			if (!has_permission_new('ItemWiseRateList', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Item Wise Sale Rate";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['ItemList'] = $this->rate_master_model->GetFGItemList();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/rate_master/ItemWiseRateList', $data);
		}
		//===================== Item Wise Rate List ====================================
		public function GetItemWiseRateList()
		{
			if (!has_permission_new('ItemWiseRateList', '', 'view')) {
				access_denied('orders');
			}
			$filterdata = array(
		    'ItemID' => $this->input->post('ItemID'),
			);
			$ItemWiseRateList = $this->rate_master_model->GetItemWiseRateList($filterdata);
			$html = '';
			$srNo = 1;
			foreach($ItemWiseRateList as $key=>$val){
				$html .= '<tr>';
				$html .= '<td>'.$srNo.'</td>';
				$html .= '<td>'.$val["name"].'</td>';
				$html .= '<td>'.$val["state_name"].'</td>';
				$html .= '<td>'.$val["assigned_rate"].'</td>';
				$html .= '<td>'.$val["SaleRate"].'</td>';
				//$html .= '<td>'._d(substr($val["effective_date"],0,10)).'</td>';
				$html .= '<td>'.$val["firstname"]." ".$val["lastname"].'</td>';
				$html .= '<td>'._d(substr($val["TransDate"],0,10)).'</td>';
				$html .= '</tr>';
				$srNo++;
			}
			echo $html;
			die;
		}
		
		//===================== Item Wise Rate List ====================================
		public function ExportItemWiseRateList()
		{
			if (!has_permission_new('ItemWiseRateList', '', 'export')) {
				access_denied('orders');
			}
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				$filterdata = array(
    		    'ItemID' => $this->input->post('ItemID'),
				);
				$ItemWiseRateList = $this->rate_master_model->GetItemWiseRateList($filterdata);
				$PlantDetail = $this->sale_reports_model->get_company_detail();
				$writer = new XLSXWriter();
				
				$company_name = array($PlantDetail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Item Name : ".$this->input->post('ItemName');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				$set_col_tk = [];
				$set_col_tk["Distributor Name"] =  'Distributor Name';
				$set_col_tk["State Name"] = 'State Name';
				$set_col_tk["Basic Rate"] = 'Basic Rate';
				$set_col_tk["Sale Rate"] = 'Sale Rate';
				$set_col_tk["Created By"] = 'Created By';
				$set_col_tk["Created Date"] = 'Created Date';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				foreach($ItemWiseRateList as $key=>$val){
					$list_add = [];
					$list_add[] = $val["name"];
					$list_add[] = $val["state_name"];
					$list_add[] = $val["assigned_rate"];
					$list_add[] = $val["SaleRate"];
					$list_add[] = $val["firstname"]." ".$val["lastname"];
					$list_add[] = _d(substr($val["TransDate"],0,10));
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'ItemWiseRateList.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function GetStateWiseDist()
		{
			$StateID = $this->input->post('StateID');
			$StateID = $this->rate_master_model->GetStateWiseDist($StateID);
			echo json_encode($StateID);
		}
		public function GetDistWiseParty()
		{
			$DistType = $this->input->post('DistType');
			$DistType = $this->rate_master_model->GetDistWiseParty($DistType);
			echo json_encode($DistType);
		}
		public function SaveRateByParty()
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$UserID = $this->session->userdata('username');
			
			$Data = $this->input->post();
			
			$DistType = $Data['DistType'];
			$StateID = $Data['StateID'];
			$Items = $Data['Items'];
			$BasicRate = $Data['BasicRate'];
			$dis_per = $Data['dis_per'];
			$effective_date3 = $Data['effective_date3'];
			$ItemData = $this->rate_master_model->GetItemDataByItemID($Items);
			
			$taxamt = ($BasicRate * $ItemData->taxrate)/100;
			
			$SaleRate= $BasicRate + $taxamt;
			$insert_arr = [];
			foreach($DistType as $key => $Distributor){
				$insert_arr[] = [
		        'PlantID' => $selected_company,
		        'state_id' => $StateID,
		        'distributor_id' => $Distributor,
		        'item_id' => $Items,
		        'assigned_rate' => $BasicRate,
		        'gst' => $ItemData->taxrate,
		        'SaleRate' => $SaleRate,
		        'effective_date' => to_sql_date($effective_date3)." 00:00:01",
		        'dis_per' => $dis_per,
		        'TransDate' => date('Y-m-d H:i:s'),
		        'UserId' => $UserID,
		        ];
				
			}
			if($this->db->insert_batch('tblrate_master', $insert_arr)){
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		
		public function ImportStatement()
		{
			if (!has_permission_new('ImportStatement', '', 'create')) {
				access_denied('Items Import');
			}
			
			$this->load->library('import/import_rate_master', [], 'import');
			$mm = array('sr_no','transaction_date','value_date','description','chq_ref_no','debit','credit');
			
			$this->import->setDatabaseFieldsStatementWise($mm)
			->setCustomFields(get_custom_fields('rate_master'));
			
			
			
			if ($this->input->post('download_statement_sample') === 'true') {
				$this->import->downloadSampleStatement();
			}
			
			if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
				// echo "<pre>";print_r($this->input->post());die;
                $selected_company = $this->session->userdata('root_company');
                $cuurent_user = $this->session->userdata('username');
                $BankAccount = $this->input->post('BankAccount');
                
				$this->import->setSimulation($this->input->post('simulate'))
				->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
				->setFilename($_FILES['file_csv']['name'])
				->performStatement($BankAccount,$selected_company,$cuurent_user);
				
				$data['total_rows_post'] = $this->import->totalRows();
				
				if (!$this->import->isSimulation()) {
					set_alert('success', _l('import_total_imported', $this->import->totalImported()));
					redirect(admin_url('rate_master/ImportStatement'));
				}
			}
			$data['BankAccount'] = $this->rate_master_model->GetBankAccounts();
			$data['title'] = _l('import');
			$this->load->view('admin/rate_master/ImportStatement', $data);
		}
		
		public function GetPendingStatement()
		{
			$BankAccount = $this->input->post('BankAccount');
			$Accounts = $this->rate_master_model->get_data_ganeral_account_to_select();
			$result = $this->rate_master_model->GetPendingStatement($BankAccount);
			$html = '';
			$i=1;
			
			if(count($result)){
				
				foreach($result as $each){
					
					$html .='<tr>';
					$html .='<td><input type="checkbox" class="selected_id" name="selected_id" value="'.$each["id"].'"></td>';
					$html .='<td>'.$i.'</td>';
					$html .='<td>'._d($each["TransDate"]).'</td>';
					$html .='<td>'._d($each["transaction_date"]).'</td>';
					$html .='<td>'._d($each["value_date"]).'</td>';
					$html .='<td>'.$each["description"].'</td>';
					$html .='<td>'.$each["chq_ref_no"].'</td>';
					$html .='<td>'.$each["debit"].'</td>';
					$html .='<td>'.$each["credit"].'</td>';
					$html .= '<td class="LegerAccount"><select class="selectpicker" name="AccountID" id="AccountID" data-width="100%" data-action-box="true" data-hide-disabled="true" data-live-search="true" data-none-selected-text="None Selected"><option value="">None Selected</option>';
					
					foreach ($Accounts as $key => $value) {
						$html .= '<option value="' . $value["id"] . '">' . $value["label"] . '</option>';
					}
					
					$html .= '</select></td>';
					$html .='</tr>';
					$i++;
				}
				
				}else{
				$html .='<span style="color:red;">No data found<span>';
			}
			echo json_encode($html);
		}
		public function GenerateStatementVoucher()
		{
			$Data = $this->input->post();
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$entryids = [];
			foreach($Data['entries'] as $key => $value){
				$entryids[] = $value['id'];
			}
			
			$statementdata = $this->rate_master_model->GetStatementImportedDataByids($entryids);
			$indexedStatementData = [];
			foreach($statementdata as $statement) {
				$indexedStatementData[$statement['id']] = $statement;
			}
			
			$Return = false;
			if($Data['EntryType'] == 'Receipt'){
				
				$receipt_date = date('Y-m-d H:i:s');
				$month = substr($receipt_date,5,2);
				$date = date('Y-m-d');
				$get_result_to_cur_date = $this->rate_master_model->get_result_to_cur_date_receipts($date);
				$PassedFrom = "RECEIPTS";
				$GetLastUniqueNo = $this->rate_master_model->GetLastUniqueNo($PassedFrom);
				$LastUniqueID = $GetLastUniqueNo[0]['UniquID'] + 1;
				if(empty($get_result_to_cur_date)){
					if($selected_company == 1){					
						$new_tax_transactionNumber = get_option('next_receipts_number_for_cspl');
						}elseif($selected_company == 2){
						$new_tax_transactionNumber = get_option('next_receipts_number_for_cff');
						}elseif($selected_company == 3){
						$new_tax_transactionNumber = get_option('next_receipts_number_for_cbu');
					}
					$new_voucher_number = $new_tax_transactionNumber;
					}else{				
					$count = count($get_result_to_cur_date);
					$last_index = $count - 1;
					$new_voucher_number = $get_result_to_cur_date[$last_index]['VoucherID'];
					
					$incNo = (int) $new_voucher_number - 1;
					$sql = 'UPDATE tblaccountledger SET VoucherID = abs(VoucherID) + 1 where abs(VoucherID) > "'.$incNo.'" AND PassedFrom = "RECEIPTS" AND FY = "'.$fy.'" AND PlantID = '.$selected_company;
					$this->db->query($sql);
					$sql = 'UPDATE tblaccountledger SET VoucherID = abs(VoucherID) + 1 where abs(VoucherID) > "'.$incNo.'" AND PassedFrom = "RECEIPTS" AND FY = "'.$fy.'" AND PlantID = '.$selected_company;
					$this->db->query($sql);
					if ($this->db->affected_rows() > 0) {
						$this->rate_master_model->increment_next_receipts_number();
						// $sql2 = 'UPDATE tblReconsileMaster SET TransID = abs(TransID) + 1 where abs(TransID) > "'.$incNo.'" AND PassedFrom = "RECEIPT"';
						// $this->db->query($sql2);
					}
				}
				
				
				$Insert_credit_Data = [];
				$Insert_debit_Data = [];
				$i = 1;
				foreach($Data['entries'] as $key => $value){
					$ledgerAccount = $value['ledgerAccount'];
					$id = $value['id'];
					
					$statementForThisId = $indexedStatementData[$id];
					
					// Ledger Entry
					$credit_data = array(
					"PlantID" =>$selected_company,
					"Transdate" =>$receipt_date,
					"TransDate2" =>date('Y-m-d H:i:s'),
					"VoucherID" =>$new_voucher_number,
					"AccountID" =>$ledgerAccount,
					"EffectOn" =>$statementForThisId['AccountID'],
					"TType" =>"C",
					"Amount" =>$statementForThisId['credit'],
					"Narration" =>$statementForThisId['description'],
					"PassedFrom" =>"RECEIPTS",
					"OrdinalNo" =>$i,
					"UserID" =>$this->session->userdata('username'),
					"FY" =>$fy,
					"UniquID" =>$LastUniqueID,
					);
					$Insert_credit_Data[] = $credit_data;
					
					$debit_data = array(
					"PlantID" =>$selected_company,
					"Transdate" =>$receipt_date,
					"TransDate2" =>date('Y-m-d H:i:s'),
					"VoucherID" =>$new_voucher_number,
					"AccountID" =>$statementForThisId['AccountID'],
					"EffectOn" =>$ledgerAccount,
					"TType" =>"D",
					"Amount" =>$statementForThisId['credit'],
					"Narration" =>$statementForThisId['description'],
					"PassedFrom" =>"RECEIPTS",
					"OrdinalNo" =>$i,
					"UserID" =>$this->session->userdata('username'),
					"FY" =>$fy,
					"UniquID" =>$LastUniqueID,
					);
					$Insert_debit_Data[] = $debit_data;
					$i++;
					
				}
				if($this->db->insert_batch(db_prefix().'accountledger', $Insert_credit_Data)){
					$this->db->insert_batch(db_prefix().'accountledger', $Insert_debit_Data);
					
					
					$this->db->set('Status', 'Y');
					$this->db->where_in('id', $entryids);
					$this->db->update(db_prefix() . 'import_statement');
					
					if(empty($get_result_to_cur_date)){
						$this->rate_master_model->increment_next_receipts_number();
					}
					
					$Return = true;
				}
				}else{
				$payment_date = date('Y-m-d H:i:s');
				$date= date('Y-m-d');
				$month = substr($payment_date,5,2);
				$get_result_to_cur_date = $this->rate_master_model->get_result_to_cur_date_payments($date);
				$PassedFrom = "PAYMENTS";
				$GetLastUniqueNo = $this->rate_master_model->GetLastUniqueNo($PassedFrom);
				$LastUniqueID = $GetLastUniqueNo[0]['UniquID'] + 1;
				
				if(empty($get_result_to_cur_date)){
					if($selected_company == 1){
						$new_tax_transactionNumber = get_option('next_payment_number_for_cspl');
						}elseif($selected_company == 2){
						$new_tax_transactionNumber = get_option('next_payment_number_for_cff');
						}elseif($selected_company == 3){
						$new_tax_transactionNumber = get_option('next_payment_number_for_cbu');
					}
					$new_voucher_number = $new_tax_transactionNumber;
					}else{
					$count = count($get_result_to_cur_date);
					$last_index = $count - 1;
					$new_voucher_number = $get_result_to_cur_date[$last_index]['VoucherID'];
					
					$incNo = (int) $new_voucher_number - 1;
					$sql = 'UPDATE tblaccountledger SET VoucherID = abs(VoucherID) + 1 where abs(VoucherID) > "'.$incNo.'" AND PassedFrom = "PAYMENTS" AND FY = "'.$fy.'" AND PlantID = '.$selected_company;
					$this->db->query($sql);
					if ($this->db->affected_rows() > 0) {
						$sql = 'UPDATE tblaccountledger SET VoucherID = abs(VoucherID) + 1 where abs(VoucherID) > "'.$incNo.'" AND PassedFrom = "PAYMENTS" AND FY = "'.$fy.'" AND PlantID = '.$selected_company;
						$this->db->query($sql);
						$this->rate_master_model->increment_next_payment_number();
						// $sql2 = 'UPDATE tblReconsileMaster SET TransID = abs(TransID) + 1 where abs(TransID) > "'.$incNo.'" AND PassedFrom = "PAYMENT"';
						// $this->db->query($sql2);
					}
				}
				
				
				$Insert_credit_Data = [];
				$Insert_debit_Data = [];
				$i = 1;
				foreach($Data['entries'] as $key => $value){
					$ledgerAccount = $value['ledgerAccount'];
					$id = $value['id'];
					
					$statementForThisId = $indexedStatementData[$id];
					
					// Ledger Entry
					$debit_data = array(
					"PlantID" =>$selected_company,
					"Transdate" =>$payment_date,
					"TransDate2" =>date('Y-m-d H:i:s'),
					"VoucherID" =>$new_voucher_number,
					"AccountID" =>$ledgerAccount,
					"EffectOn" =>$statementForThisId['AccountID'],
					"TType" =>"D",
					"Amount" =>$statementForThisId['debit'],
					"Narration" =>$statementForThisId['description'],
					"PassedFrom" =>"PAYMENTS",
					"OrdinalNo" =>$i,
					"UserID" =>$this->session->userdata('username'),
					"FY" =>$fy,
					"UniquID" =>$LastUniqueID,
					);
					$Insert_debit_Data[] = $debit_data;
					
					$credit_data = array(
					"PlantID" =>$selected_company,
					"Transdate" =>$payment_date,
					"TransDate2" =>date('Y-m-d H:i:s'),
					"VoucherID" =>$new_voucher_number,
					"AccountID" =>$statementForThisId['AccountID'],
					"EffectOn" =>$ledgerAccount,
					"TType" =>"C",
					"Amount" =>$statementForThisId['debit'],
					"Narration" =>$statementForThisId['description'],
					"PassedFrom" =>"PAYMENTS",
					"OrdinalNo" =>$i,
					"UserID" =>$this->session->userdata('username'),
					"FY" =>$fy,
					"UniquID" =>$LastUniqueID,
					);
					$Insert_credit_Data[] = $credit_data;
					$i++;
					
				}
				if($this->db->insert_batch(db_prefix().'accountledger', $Insert_debit_Data)){
					$this->db->insert_batch(db_prefix().'accountledger', $Insert_credit_Data);
					
					
					$this->db->set('Status', 'Y');
					$this->db->where_in('id', $entryids);
					$this->db->update(db_prefix() . 'import_statement');
					
					if(empty($get_result_to_cur_date)){
						$this->rate_master_model->increment_next_payment_number();
					}
					
					$Return = true;
				}
			}
			echo json_encode($Return);
		}
	}
