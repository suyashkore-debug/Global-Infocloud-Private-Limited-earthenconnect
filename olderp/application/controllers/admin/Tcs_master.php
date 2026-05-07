<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tcs_master extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tcs_master_model');
        $this->load->model('route_master_model');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission_new('tcsmaster', '', 'view')) {
            access_denied('TCS Master');
        }
        $data['tcs_table'] = $this->tcs_master_model->get_data_table();
        $data['company_detail'] = $this->tcs_master_model->get_company_detail();
        $data['title'] = "TCS Master";
        $this->load->view('admin/tcs_master/manage', $data);
    }

    public function export_TcsMaster()
    {
    	if(!class_exists('XLSXReader_fin')){
    		require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
    	}
    	require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
    	
    	if($this->input->post()){
    	
            $data = $this->tcs_master_model->get_data_table();
    		$selected_company_details    = $this->tcs_master_model->get_company_detail();
    		
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
            $writer->writeSheetRow('Sheet1', $list_add);
            
            $set_col_tk = [];
    		$set_col_tk["TCS %"] =  'TCS %';
    		$set_col_tk["Effected Date"] = 'Effected Date';
    		$set_col_tk["Created staff"] = 'Created staff';
    		$set_col_tk["Status"] = 'Status';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    		
    		foreach ($data as $k => $value) {
    		    
    			$list_add = [];
    			$list_add[] = $value["tcs"];
    			
    			$list_add[] = substr(_d($value['EffDate'],0,10));
    			
    			$full_name = get_staff_name($value['UserId']);
                    if(empty($full_name)){
                        $row = $value['UserId'];
                    }else{
                        $row = $full_name->firstname .' '.$full_name->lastname;
                    }
    			$list_add[] = $row;
    			
    		        if($tcs_id == $value['id']){
                        $row_a = "Active";
                    }else{
                        $row_a = "DeActive";
                    }
    			$list_add[] = $row_a;
    			
    			$writer->writeSheetRow('Sheet1', $list_add);
    		
    	    }
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'TcsMaster.xlsx';
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
        if (has_permission_new('tcsmaster', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                
                    if (!has_permission_new('tcsmaster', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    /*echo "<pre>";
                    print_r($data);
                    die;*/
                    $id      = $this->tcs_master_model->add($data);
                    
                    if ($id) {
                        set_alert('success', 'TCS added Successfully..');
                        redirect(admin_url('tcs_master'));
                    }
               
            }
        }
    }

}
