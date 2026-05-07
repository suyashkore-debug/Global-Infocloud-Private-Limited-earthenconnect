<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Company_assign extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->model('company_assign_model');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission('salesperassign', '', 'update')) {
            access_denied('Invoice Items');
        }
         if ($this->input->post()) {
             
            $data = $this->input->post();
            
            $update = $this->company_assign_model->uptate_salse_person($data);
            if($update == true){
                set_alert('success', _l('updated_successfully', "Sales Person"));
                redirect(admin_url('company_assign'));
            }else {
                set_alert('error', 'Something Went Wrong..');
                redirect(admin_url('company_assign'));
            }
            
            /*echo "<pre>";
            print_r($data);
            //print_r($transfer_staff);
            die;*/
         }
        
        
        $data['all_staff'] = $this->company_assign_model->get_all_staff();
        
        /*echo "<pre>";
        print($data['route']);
        die;
*/
        $data['title'] = "Company Assign";
        $this->load->view('admin/company_assign/manage', $data);
    }
    
    //--------------------------------------------------
    public function select_staff()
    {
        $id=$this->input->post('id'); 
        $staff_data = $this->company_assign_model->get_staff_by_id($id);
        
      
    echo json_encode($staff_data);
    }
    
    //--------------------------------------------------
    public function get_distributor_by_company()
    {
        $company_id = $this->input->post('id'); 
        $staff_id = $this->input->post('all_staff'); 
        
        $distributor_data = $this->company_assign_model->get_distributor_by_company($company_id,$staff_id);
        
        
    echo json_encode($distributor_data);
    }
    
    //--------------------------------------------------
    public function get_staff_by_company()
    {
        $company_id = $this->input->post('id'); 
        
        $staff_data = $this->company_assign_model->get_staff_by_company($company_id);
        
    echo json_encode($staff_data);
    }
    
     //--------------------------------------------------
    public function transfer_staff()
    {
        $id=$this->input->post('id'); 
        $job_id=$this->input->post('job_id');
        $staff_data = $this->hierarchy_model->get_transfer_staff($id,$job_id);
        
      
    echo json_encode($staff_data);
    }
    
    //--------------------------------------------------
    public function to_staff()
    {
        $id=$this->input->post('id'); 
        $get_tostaff_data = $this->hierarchy_model->get_to_staff_detail($id);
       
      
    echo json_encode($get_tostaff_data);
    }

    public function table()
    {
        if (!has_permission('salesperassign', '', 'update')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('hsn_table');
    }

    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission('salesperassign', '', 'update')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!has_permission('salesperassign', '', 'update')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->hsn_master_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', "HSN Code");
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->hsn_master_model->get($id),
                    ]);
                } else {
                    if (!has_permission('salesperassign', '', 'update')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->hsn_master_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', "HSN Code");
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
        if (!has_permission('salesperassign', '', 'update')) {
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

    
    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission('salesperassign', '', 'update')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('vehicles'));
        }

        $response = $this->hsn_master_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', 'HSN Code Delected Successfully..');
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('hsn_master'));
    }

    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $has_permission_delete = has_permission('salesperassign', '', 'update');
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
    public function get_hsn_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $hsn                    = $this->hsn_master_model->get($id);
            

            echo json_encode($hsn);
        }
    }
}
