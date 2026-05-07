<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Vehicles extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
        $this->load->model('vehicle_model');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission_new('vehiclemaster', '', 'view')) {
            access_denied('Invoice Items');
        }
        
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['items_main_groups'] = $this->invoice_items_model->get_main_groups();
        $data['items_sub_groups'] = $this->invoice_items_model->get_sub_groups();

        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();
        
        $data['vehicle'] = $this->vehicle_model->get();
        $data['vehicle_data'] = $this->vehicle_model->get_vehicle_data();
        $data['company_detail'] = $this->vehicle_model->get_company_detail();
       /* echo "<pre>";
        print($data['vehicle']);
        die;*/

        $data['title'] = "Vehicle Master";
        $this->load->view('admin/vehicle/manage', $data);
    }
    public function export_VehicleMaster()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
            $data = $this->vehicle_model->get_vehicle_data();
    		$selected_company_details    = $this->vehicle_model->get_company_detail();
    		
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
            $writer->writeSheetRow('Sheet1', $list_add);
            
            $set_col_tk = [];
    		$set_col_tk["Vehicle RegNo"] =  'Vehicle RegNo';
    		$set_col_tk["Vehicle Type"] = 'Vehicle Type';
    		$set_col_tk["Vehicle Capacity"] = 'Vehicle Capacity';
    		$set_col_tk["Start Day"] = 'Start Day';
    		$set_col_tk["Active"] = 'Active';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    		
    		foreach ($data as $k => $value) {
    		    
    			$list_add = [];
    			$list_add[] = $value["VehicleID"];
    			if($value["VehicleTypeID"] == "0"){
                       $VehicleTypeID = "Own";
                    }else{
                        $VehicleTypeID =  "Other";
                    }
    			$list_add[] = $VehicleTypeID;
    			
    			$list_add[] = $value["VehicleCapacity"];
    			$date = substr(_d($value['StartDate']),0,10);
    			$list_add[] = $date;
    			if($value['ActiveYN'] == "1"){
                        $status = "Y";
                    }else{
                        $status = "N";
                    }
    			$list_add[] = $status;
    			
    			$writer->writeSheetRow('Sheet1', $list_add);
    		
    	    }
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'VehicleMaster.xlsx';
    		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
    		echo json_encode([
    			'site_url'          => site_url(),
    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
    		]);
    		die;
    	}
    }

    public function table()
    {
        if (!has_permission_new('vehiclemaster', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('vehicle_table');
    }

    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission_new('vehiclemaster', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!has_permission_new('vehiclemaster', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->vehicle_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', "Vehicle");
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->vehicle_model->get($id),
                    ]);
                } else {
                    if (!has_permission_new('vehiclemaster', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->vehicle_model->edit($data);
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

    

    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission_new('vehiclemaster', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('vehicles'));
        }

        $response = $this->vehicle_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', 'Vehicle Delected Successfully..');
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('vehicles'));
    }

    


    /* Get item by id / ajax */
    public function get_vehicle_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $vehicle                     = $this->vehicle_model->get($id);
            

            echo json_encode($vehicle);
        }
    }
}
