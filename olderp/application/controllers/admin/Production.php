<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Production extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
         
        $this->load->model('production_model');
    }
    
    /* Bill Of Material Page */
    public function BillOfMaterial()
    {   
        if (!has_permission_new('recipe', '', 'view')) {
            ajax_access_denied();
        }
        $this->load->model('production_model');
        $data['title'] = "Bill Of Material";
        $data['GodownData'] = $this->production_model->GetGodownData();
        $this->load->view('admin/production/BillOfMaterial', $data);
    }

    /* List all available items */
    public function index()
    {   
        if (!has_permission_new('recipe', '', 'view')) {
            ajax_access_denied();
        }
        $this->load->model('production_model');
   
        //$data['items_main_groups'] = $this->production_model->get_sub_groups();
        $data['title'] = "New Recipe";
        $status = 'Y';
        $data['recipe_list'] = $this->production_model->load_data_for_recipe($status);
        $this->load->view('admin/production/manage', $data);
        //print_r($data); exit();
    }
  
  public function all_recipe()
    {
       if (!has_permission_new('recipe', '', 'view')) {
            ajax_access_denied();
        }
        $data['title'] = "View Recipe";
        $this->load->view('admin/production/recipe', $data);
    }
  public function view_Order()
    {
       if (!has_permission_new('production', '', 'view')) {
            ajax_access_denied();
        }
        $data['title'] = _l('Production Order View');
        $this->load->view('admin/production/view_production_order', $data);
    }
  
  public function table_production()
    {
        if (!has_permission_new('production', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('production_table');
    }
  
  
  public function table()
    {
        if (!has_permission_new('recipe', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('recipe_table');
    }
  
    public function increment_next_number()
    {
        // Update next Old Production number in settings
        $FY = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_production_return_number_for_cspl');
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_production_return_number_for_cff');
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_production_return_number_for_cbu');
            }elseif($selected_company == 4){
                $this->db->where('name', 'next_production_return_number_for_cbupl');
            }
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
    
    public function increment_next_prd_number()
    {
        // Update next Old Production number in settings
        $FY = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == 1){
            $this->db->where('name', 'next_prd_number_for_cspl');
        }elseif($selected_company == 2){
            $this->db->where('name', 'next_prd_number_for_cff');
        }elseif($selected_company == 3){
            $this->db->where('name', 'next_prd_number_for_cbu');
        }
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
    
    public function increment_next_bom_number()
    {
        // Update next Receipts number in settings
        $FY = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_bom_number_for_cspl');
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_bom_number_for_cff');
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_bom_number_for_cbu');
            }
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
    
    public function get_bom_fm_details()
    {
        // POST data
        $postData = $this->input->post();
        $BOMID = $this->input->post('BOMID');
        // Get data
        $data = $this->production_model->get_bom_fm_details($BOMID);
        echo json_encode($data);
    }
    
    public function GetBOM_RM_view()
    {
        $BOMID = $this->input->post('BOMID');
        $data['RMItemDetails'] = $this->production_model->get_bom_rm_details($BOMID); 
        $this->load->view('admin/production/GetBOMRMView',$data);          
    }
    public function create_prd()
    { 
        if (!has_permission_new('production', '', 'view')) {
            ajax_access_denied();
        }
        //$this->load->model('sales_receipts_model');
        $data['manager'] = $this->production_model->get_managername();
        $data['contractor'] = $this->production_model->get_contractorname();
        $data['PRDLastDate'] = $this->production_model->GetLastPrdDate();
        $data['GodownData'] = $this->production_model->GetGodownData();
        $data['BOMList'] = $this->production_model->GetBOMList();
        $data['title'] = _l('Production Order');
        $this->load->view('admin/production/create_prd', $data);
        if($this->input->post()){
            if (!has_permission_new('production', '', 'create')) {
                ajax_access_denied();
            }
        
            $data = $this->input->post();
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == 1){
                $prd_numbar = get_option('next_prd_number_for_cspl');
            }elseif($selected_company == 2){
                $prd_numbar = get_option('next_prd_number_for_cff');
            }elseif($selected_company == 3){
                $prd_numbar = get_option('next_prd_number_for_cbu');
            }
            $new_prd_numbar = "PRD".$fy.$prd_numbar;
            $GetRMDetails = $this->production_model->get_bom_rm_details($data["BOMID"]);
            /*echo "<pre>";
            print_r($GetRMDetails);
            die;*/
            
            $date = to_sql_date($data["start_date1"])." ".date('H:i:s');
    
            $production_data = array(
                "PlantID"=>$selected_company,
                "FY"=>$fy,
                "GodownID"=>$data["fg_store"],
                "pro_order_id"=>$new_prd_numbar,
                "TransDate"=>$date,
                "recipeID"=>$data["ItemID"],
                "BOMID"=>$data["BOMID"],
                "batch_qty"=>$data["qtytoproduce_hidden"],
                "Finish_good_qty"=>$data["qtytoproduce_hidden"],
                "finish_good_unit"=>$data["fg_unit_hidden"],
                "required_time"=>NULL,
                "production_status"=>"pending",
                "manager_name"=>$data["operator_name"],
                "contractor_name"=>NULL,
                "comment"=>$data["bom_comments_hidden"],
                "TransDate2"=>date('Y-m-d H:i:s'),
                "UserID"=>$this->session->userdata('username')
            ); 
                
            $this->db->insert(db_prefix() . 'production', $production_data);
            $last_inserted_id = $this->db->insert_id();
            if($last_inserted_id){
                $this->increment_next_prd_number();
                $GetRMDetails = $this->production_model->get_bom_rm_details($data["BOMID"]);
                foreach($GetRMDetails as $val){
                    $insert = array(
                        "PlantID"=>$selected_company,
                        "FY"=>$fy,
                        "GodownID"=>$data["rm_store"],
                        "item_id"=>$val["item_id"],
                        "item_name"=>$val["item_name"],
                        "req_qty"=>$val["req_qty"],
                        "unit"=>$val["unit"],
                        "production_id"=>$new_prd_numbar,
                        "production_req_qty"=>$val["req_qty"],
                        "TransDate2"=>date('Y-m-d H:i:s'),
                        "TransDate"=>$date,
                        "UserID"=>$this->session->userdata('username'),
                    );
                    $insert_comme = $this->db->insert(db_prefix() . 'production_details', $insert);
                }
                $insert = array(
                    "PlantID"=>$selected_company,
                    "FY"=>$fy,
                    "GodownID"=>$data["rm_store"],
                    "item_id"=>"SCRAP",
                    "item_name"=>"Scrap Item",
                    "req_qty"=>"0",
                    "unit"=>"0",
                    "production_id"=>$new_prd_numbar,
                    "production_req_qty"=>"0",
                    "TransDate2"=>date('Y-m-d H:i:s'),
                    "TransDate"=>$date,
                    "UserID"=>$this->session->userdata('username'),
                );
                $insert_comme = $this->db->insert(db_prefix() . 'production_details', $insert);
                set_alert('success', 'Production Order added Successfully..');
                redirect(admin_url('production/create_prd')); 
            }else{
                set_alert('warning', 'something went wrong..');
                redirect(admin_url('production/create_prd')); 
            }
        } 
    }
    
    public function create_order()
    { 
        if (!has_permission_new('production', '', 'view')) {
            ajax_access_denied();
        }
        //$this->load->model('sales_receipts_model');
        $data['manager'] = $this->production_model->get_managername();
        $data['contractor'] = $this->production_model->get_contractorname();
        $data['PRDLastDate'] = $this->production_model->GetLastPrdDate();
        $data['GodownData'] = $this->production_model->GetGodownData();
        $data['title'] = _l('Production Order');
        $this->load->view('admin/production/manage_production_order', $data);
        if($this->input->post()){
            if (!has_permission_new('production', '', 'create')) {
                ajax_access_denied();
            }
        
            $data = $this->input->post();
            $time1 = $data["req_hour"] * 60;
            $req_time= $time1 + $data["req_min"];
     
            $operator_name=$data["operator_name"];
            $con_name=$data["con_name"];
            $name = '';
            if($operator_name){
                $name=$operator_name;
            }else if($con_name){
                $name=$con_name;
            }
            if($name == "" ){
                set_alert('warning', 'Please add production manager..');
                redirect(admin_url('production/create_order'));     
            }
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
                $GodownID = 'CSPL';
            }else if($selected_company == "2"){
                $GodownID = 'CFF';
            }else if($selected_company == "3"){
                $GodownID = 'CBUPL';
            }
            
            if($selected_company == 1){
                $new_production_orderNumbar = get_option('next_production_return_number_for_cspl');
            }elseif($selected_company == 2){
                $new_production_orderNumbar = get_option('next_production_return_number_for_cff');
            }elseif($selected_company == 3){
                $new_production_orderNumbar = get_option('next_production_return_number_for_cbu');
            }elseif($selected_company == 4){
                $new_production_orderNumbar = get_option('next_production_return_number_for_cbupl');
            }
            $order_id = "POI".$fy.$new_production_orderNumbar;
  
            $finish_good_qty = $data["finishgood_qty"];
            $date = to_sql_date($data["start_date1"])." ".date('H:i:s');
    
            $production_data = array(
                "PlantID"=>$selected_company,
                "FY"=>$fy,
                "GodownID"=>$data["GodownID"],
                "pro_order_id"=>$order_id,
                "TransDate"=>$date,
                "recipeID"=>$data["recipeID"],
                "batch_qty"=>$data["batch_qty"],
                "Finish_good_qty"=>$finish_good_qty,
                "finish_good_unit"=>$data["unit_new"],
                "required_time"=>$req_time,
                "production_status"=>"pending",
                "manager_name"=>$data["operator_name"],
                "contractor_name"=>$data["con_name"],
                "comment"=>$data["comments"],
                "TransDate2"=>date('Y-m-d H:i:s'),
                "UserID"=>$this->session->userdata('username')
            ); 
                
            $this->db->insert(db_prefix() . 'production', $production_data);
            $last_inserted_id = $this->db->insert_id();
            if($last_inserted_id){
                $this->increment_next_number();
                $count = $data["count_of_rec"];
    
                $item_id2 = $this->input->post('item_id[]');
                $item_name2 = $this->input->post('item_name[]');
                $req_qty2 = $this->input->post('req_qty[]');
                $unit2 = $this->input->post('unit[]');
                $pro_req_qty = $this->input->post('pro_req_qty[]');
          
                $production_details = array(  
                    array(
                        'item_id' => 'item_id' ,
                        'item_name' => 'item_name' ,
                        'req_qty' => 'req_qty',
                        'unit' => 'unit',
                        'production_req_qty' => 'pro_req_qty'
                    )
                );
         
                $i = 0;
                foreach($item_id2 as $key=>$val)
                {     
                    $production_details[$i]['PlantID'] = $selected_company;
                    $production_details[$i]['FY'] = $fy;
                    $production_details[$i]['GodownID'] = $data["GodownID"];
                    $production_details[$i]['item_id'] = $val;
                    $production_details[$i]['item_name'] = $item_name2[$key];
                    $production_details[$i]['req_qty'] = $req_qty2[$key];
                    $production_details[$i]['unit'] = $unit2[$key];
                    $production_details[$i]['production_id'] = $order_id;
                    $production_details[$i]['production_req_qty'] = $pro_req_qty[$key];
                    $production_details[$i]['TransDate2'] = date('Y-m-d H:i:s');
                    $production_details[$i]['TransDate'] = $date;
                    $production_details[$i]['UserID'] = $this->session->userdata('username');
                    $i++;
                }
                $insert_comme=$this->db->insert_batch(db_prefix() . 'production_details', $production_details);
                set_alert('success', 'Production Order added Successfully..');
                redirect(admin_url('production/create_order')); 
            }else{
                set_alert('warning', 'something went wrong..');
                redirect(admin_url('production/create_order')); 
            }
        } 
    }
  
    public function itemlist_name()
    {
        $recipeID = $this->input->post('recipeID');
        $GodownID = $this->input->post('GodownID');
        $data['batch_qty']= $this->input->post('batchQuantity');
        $data['result2'] = $this->production_model->getbyitemname($recipeID,$GodownID); 
        $data['ItemStocks'] = $this->production_model->GetItemStock($GodownID);
        $this->load->view('admin/production/get_recipe',$data);          
    }
    
    public function ReceipeData()
    {
        $recipeID = $this->input->post('recipeID');
    $batch_qty = $this->input->post('batchQuantity');
    $PONumber = $this->input->post('PONumber');
    $recipeDetails = $this->production_model->getReceipeDetailswithPODetails($recipeID,$PONumber);
    
    $html = '';
    foreach($recipeDetails as $key=>$value){
        $html .= '<tr>';
        $html .= '<td>'.$value['item_id'].'</td>';
        $html .= '<td>'.$value['item_name'].'</td>';
        $html .= '<td>'.$value['StdQty'].'</td>';
        $reqQty = $value['StdQty'] * $batch_qty;
        $html .= '<td>'.$reqQty.'</td>';
        $html .= '<td>'.$value['RtnQty'].'</td>';
        $html .= '<td>'.$value['ExtraQty'].'</td>';
        $actualQty = $reqQty + $value['ExtraQty'] - $value['RtnQty'];
        $html .= '<td>'.$actualQty.'</td>';
        $html .= '<td>'.$value['unit'].'</td>';
        $html .= '</tr>';
    }
    echo $html;
    }
    
    
  public function itemlist_recipe(){
        $this->load->model('production_model');
    // POST data
    $postData = $this->input->post();

    // Get data
    $data = $this->production_model->get_recipename($postData);
    //print_r($postData); exit();
    echo json_encode($data);
  }
    
    public function itemlist_using_itemcode()
    {
        $this->load->model('production_model');
        // POST data
        $postData = $this->input->post();
        // Get data
        $data = $this->production_model->getitem_using_itemcode($postData);
        //print_r($postData); exit();
        echo json_encode($data);
    }
  public function itemDetails_by_itemcode(){
        $this->load->model('production_model');
    // POST data
        $postData = $this->input->post();

    // Get data
    $data = $this->production_model->itemDetails_by_itemcode($postData);
    //print_r($postData); exit();
    echo json_encode($data);
  }
  
  public function itemlist_subgroup(){
    // POST data
    $postData = $this->input->post();
    $ProdId = $this->input->post('ProductionId');
    // Get data
    $data = $this->production_model->getitem_subgroup($postData,$ProdId);

    echo json_encode($data);
  }
  
  public function ItemListReceipe(){
    $postData = $this->input->post();
    $data = $this->production_model->ItemListReceipe($postData);

    echo json_encode($data);
  }
  
  public function itemlist_subgroup1(){
    // POST data
    $postData = $this->input->post();
  $ProdId = $this->input->post('ProductionId');
    // Get data
    $data = $this->production_model->getitem_subgroup1($postData,$ProdId);

    echo json_encode($data);
  }
  public function get_recipe_details(){
    // POST data
    $postData = $this->input->post();
    $recipeID = $this->input->post('recipeID');
    // Get data
    $data = $this->production_model->get_recipe_details($recipeID);

    echo json_encode($data);
  }
    public function get_item_details()
    {
        // POST data
        $postData = $this->input->post();
        $ProdId = $this->input->post('proId');
        $ItemID = $this->input->post('ItemID');
        // Get data
        $data = $this->production_model->get_item_details($ProdId,$ItemID);
        echo json_encode($data);
    }
    
    public function add_bom()
    {  
        if (!has_permission_new('recipe', '', 'create')) {
            ajax_access_denied();
        }
        $data = $this->input->post(); 
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        if($selected_company == 1){
            $new_BOMNumbar = get_option('next_bom_number_for_cspl');
        }elseif($selected_company == 2){
            $new_BOMNumbar = get_option('next_bom_number_for_cff');
        }elseif($selected_company == 3){
            $new_BOMNumbar = get_option('next_bom_number_for_cbu');
        }
        /*echo "<pre>";
        print_r($data);
        die;*/
        $_new_number = str_pad($new_BOMNumbar, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
        $BOMID = 'BOM'.$fy.$_new_number;
        
        $receipt_date = array(
            "PlantID"=>$selected_company,
            "FY"=>$fy,
            "BOMID"=>$BOMID,
            "FG_Godown	"=>$data["fg_store"],
            "RM_Godown"=>$data["rm_store"],
            "Scrap_Godown"=>$data["scrap_store"],
            "bom_comments"=>$data["bom_comments"],
            "cost_allocation"=>$data["cost_allocation"],
            "item_code"=>$data["item_code"],
            "item_description"=>$data["item_desc"],
            "qty"=>$data["qtytoproduce"],
            "unit"=>$data["unit_f_g"],
            "conv_cost"=>$data["labour_cost"],
            "st_cost"=>$data["electricity_cost"],
            "frt_cost"=>$data["machinery_cost"],
            "mrkt_cost"=>$data["other_cost"],
            'ActiveDate'=>date('Y-m-d H:i:s'),
            'ADUserID'=>$this->session->userdata('username'),
            "UserID"=>$this->session->userdata('username'),
            "TransDate"=>date('Y-m-d H:i:s'),
        );
        
        if($data["countof_record"] > 0){
            $this->db->insert(db_prefix() . 'recipe', $receipt_date);
            $lastid = $this->db->insert_id(); 
            $this->increment_next_bom_number();
        }else{
            set_alert('warning', 'please select atleast one row material..');
            redirect(admin_url('production/BillOfMaterial'));
        }
        if($lastid){
            $count = $data["countof_record"];  
            for($i=1;$i<=$count;$i++){
                $item_id = "item_id".$i;
                $item_name = "item_name".$i;
                $req_qty = "req_qty".$i;
                $unit = "unit".$i;
                $item_cat = "item_cat".$i;
                $item_comm = "item_comm".$i;
                $item_child_bom = "item_child_bom".$i;
                    $recipe_details = array(
                        "PlantID"=>$selected_company,
                        "FY"=>$fy,
                        "BOMID"=>$BOMID,
                        "item_id"=>$data[$item_id],
                        "item_name"=>$data[$item_name],
                        "ItemSubGroup"=>$data[$item_cat],
                        "Item_comments"=>$data[$item_comm],
                        "child_bom"=>$data[$item_child_bom],
                        "req_qty"=>$data[$req_qty],
                        "unit"=>$data[$unit],
                        "rec_id"=>$lastid,
                        "Ordinalno"=>$i,
                        "UserID"=>$this->session->userdata('username'),
                        "TransDate"=>date('Y-m-d H:i:s'),
                    );
                $recipeData = $this->db->insert(db_prefix() . 'recipe_details', $recipe_details);
            }
            set_alert('success', 'BOM added Successfully..');
            redirect(admin_url('production/BillOfMaterial'));
        }else{
            set_alert('warning', 'Something went wrong..');
            redirect(admin_url('production/BillOfMaterial'));
        }
    }
    
    public function EditBOM($id)
    {  
        if (!has_permission_new('recipe', '', 'view')) {
            ajax_access_denied();
        }
        if ($this->input->post()) {
            if (!has_permission_new('recipe', '', 'edit')) {
                ajax_access_denied();
            }
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $data = $this->input->post();
                
                $recipe_id = $this->input->post('id');
                $count = $data["countof_record"]; 
                $ItCount = $count - 1;  
            /*echo "<pre>";
            print_r($data["new_record"]);
            echo "<br>";
            print_r($data["updated_record"]);
            echo "<br>";
            print_r($data["deleted_record"]);
            print_r($data);
            die;*/
            
            //new Item aadded
                $new_record = $data["new_record"];
                $new_record = str_replace(" ,",'',$data["new_record"]);
                $new_record_array = explode(',', $new_record);
                
                //update exiting Item
                $edit_record = $data["updated_record"];
                $edit_record = str_replace(" ,",'',$data["updated_record"]);
                $edit_record_array = explode(',', $edit_record);
                
                // delete exiting Item
                $delete_record = $data["deleted_record"];
                $delete_record = str_replace(" ,",'',$data["deleted_record"]);
                $delete_record_array = explode(',', $delete_record);
                
                $this->db->where(db_prefix() . 'recipe_details.BOMID', $data["BOMID"]);
                $this->db->where('rec_id', $recipe_id);
                $this->db->where_in('item_id', $delete_record_array);
                $this->db->where('PlantID', $selected_company);
                //$this->db->where('FY', $fy);
                $this->db->delete(db_prefix() . 'recipe_details');
                
            // Check Recipe Exit OR Not
           /* $ReceipDetails = $this->production_model->get_recipe_details($data["item_code"]);
            if($ReceipDetails){
                if($ReceipDetails->status == $data["status"]){
                    
                }else{
                    if($data["status"] == "Y"){
                        $OldRecUpdate = array(
                            'status'=>'Y',
                            'ActiveDate'=>date('Y-m-d H:i:s'),
                        );
                    }else{
                        $OldRecUpdate = array(
                            'status'=>'N',
                            'DeActiveDate'=>date('Y-m-d H:i:s')
                        );
                    }
                }
                
                $this->db->where(db_prefix() . 'recipe.item_code', $data["item_code"]);
                $this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
                $UpdateOldReceipe = $this->db->update(db_prefix() . 'recipe', $OldRecUpdate);
            } */   
                
               
            if($recipe_id){ 
                $receipt_date = array(
                    'qty'=>$data["qtytoproduce"],
                    'conv_cost'=>$data["labour_cost"],
                    'st_cost'=>$data["electricity_cost"],
                    'frt_cost'=>$data["machinery_cost"],
                    'mrkt_cost'=>$data["other_cost"],
                    'bom_comments'=>$data["bom_comments"],
                    'cost_allocation'=>$data["cost_allocation"],
                    'FG_Godown'=>$data["fg_store"],
                    'RM_Godown'=>$data["rm_store"],
                    'Scrap_Godown'=>$data["scrap_store"],
                    'ADUserID'=>$this->session->userdata('username'),
                    "UserID2"=>$this->session->userdata('username'),
                    "Lupdate"=>date('Y-m-d H:i:s')
                );
               
                $multiClause = array('id' => $recipe_id);
                $this->db->where($multiClause);   
                $this->db->where(db_prefix() . 'recipe.BOMID', $data["BOMID"]);
                $query = $this->db->update(db_prefix() . 'recipe', $receipt_date);
             
                for($i=1; $i<$count; $i++) { 
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                    $item_cat = "item_cat".$i;
                    $item_comm = "item_comm".$i;
                    $item_child_bom = "item_child_bom".$i;
                    $itemrownum = "rownum".$i;
              
                    if(in_array($data[$itemid], $new_record_array)){
                        
                        $new_record_details = array(
                            "PlantID"=>$selected_company,
                            "FY"=>$fy,
                            "BOMID"=>$data["BOMID"],
                            "item_id"=>$data[$itemid],
                            "item_name"=>$data[$itemName],
                            "ItemSubGroup"=>$data[$item_cat],
                            "Item_comments"=>$data[$item_comm],
                            "child_bom"=>$data[$item_child_bom],
                            "req_qty"=>$data[$reqQty],
                            "unit"=>$data[$itemUnit],
                            "rec_id"=>$recipe_id,
                            "Ordinalno"=>$itemrownum,
                            "UserID"=>$this->session->userdata('username'),
                            "TransDate"=>date('Y-m-d H:i:s'),
                        );
                        $this->db->insert(db_prefix() . 'recipe_details', $new_record_details);
                    }
               
                    if(in_array($data[$itemid], $edit_record_array)){
                        $edit_record_details = array(
                            "Item_comments"=>$data[$item_comm],
                            "child_bom"=>$data[$item_child_bom],
                            "req_qty"=>$data[$reqQty],
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s'),
                        );
                        //print_r($edit_record_details); 
                        $this->db->where(db_prefix() . 'recipe_details.BOMID', $data["BOMID"]);
                        $this->db->where('rec_id', $recipe_id);
                        $this->db->where('item_id', $data[$itemid]);
                        $this->db->where('PlantID', $selected_company);
                        //$this->db->where('FY', $fy);
                        $pr =$this->db->update(db_prefix() . 'recipe_details', $edit_record_details);
                    }      
                }  //for end
            } // if end
            //die;
            set_alert('success', 'BOM Updated Successfully..');
            redirect(admin_url('production/EditBOM/'.$recipe_id));    
        }
        $this->load->model('sale_reports_model');
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $data['BOMDetails'] = $this->production_model->GetBom_details($id);
        $data['BOMRMDetails'] = $this->production_model->GetBOMRMDetails($id);
        
        $status = 'Y';
        $data['GodownData'] = $this->production_model->GetGodownData();
        $data['title'] = "Edit Bill of Material";
        $this->load->view('admin/production/edit_bom', $data); 
       
    } 
    
    public function load_data_for_BOM()
    {
        $postData = $this->input->post('status');
        $data = $this->production_model->load_data_for_bom($postData);
        echo json_encode($data);
    }
    
    public function add()
    {  
        if (!has_permission_new('recipe', '', 'create')) {
            ajax_access_denied();
        }
        $data = $this->input->post(); 
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        // Check Recipe Exit OR Not
        $ReceipDetails = $this->production_model->get_recipe_details($data["item_code"]);
        if($ReceipDetails){
            $OldRecUpdate = array(
                'status'=>'N',
                'DeActiveDate'=>date('Y-m-d H:i:s'),
                'ADUserID'=>$this->session->userdata('username'),
                "UserID2"=>$this->session->userdata('username'),
                "Lupdate"=>date('Y-m-d H:i:s')
            );
            $this->db->where('item_code', $data["item_code"]);
            $this->db->where('PlantID', $selected_company);
            //$this->db->where('FY', $fy);
            $UpdateOldReceipe = $this->db->update(db_prefix() . 'recipe', $OldRecUpdate);
        }
            $receipt_date = array(
                "PlantID"=>$selected_company,
                "FY"=>$fy,
                "item_code"=>$data["item_code"],
                "item_description"=>$data["item_desc"],
                "qty"=>$data["qtytoproduce"],
                "unit"=>$data["unit_f_g"],
                "conv_cost"=>$data["conv_cost"],
                "st_cost"=>$data["st_cost"],
                "frt_cost"=>$data["frt_cost"],
                "mrkt_cost"=>$data["mrkt_cost"],
                "dmg_cost"=>$data["dmg_cost"],
                "status"=>$data["status"],
                'ActiveDate'=>date('Y-m-d H:i:s'),
                'ADUserID'=>$this->session->userdata('username'),
                "UserID"=>$this->session->userdata('username'),
                "TransDate"=>date('Y-m-d H:i:s'),
            );
            if($data["countof_record"] > 0){
                $this->db->insert(db_prefix() . 'recipe', $receipt_date);
                $lastid = $this->db->insert_id(); 
            }else{
                set_alert('warning', 'please select atleast one row material..');
                redirect(admin_url('production'));
            }
            
        if($lastid){
            $count = $data["countof_record"];  
            for($i=1;$i<=$count;$i++){
                $item_id = "item_id".$i;
                $item_name = "item_name".$i;
                $req_qty = "req_qty".$i;
                $unit = "unit".$i;
                    $recipe_details = array(
                        "PlantID"=>$selected_company,
                        "FY"=>$fy,
                        "item_id"=>$data[$item_id],
                        "item_name"=>$data[$item_name],
                        "req_qty"=>$data[$req_qty],
                        "unit"=>$data[$unit],
                        "rec_id"=>$lastid,
                        "Ordinalno"=>$i,
                        "UserID"=>$this->session->userdata('username'),
                        "TransDate"=>date('Y-m-d H:i:s'),
                    );
                $recipeData = $this->db->insert(db_prefix() . 'recipe_details', $recipe_details);
            }
            set_alert('success', 'Recipe added Successfully..');
            redirect(admin_url('production'));
        }else{
            set_alert('warning', 'Something went wrong..');
            redirect(admin_url('production'));
        }
    }
  
    public function load_data_for_recipe()
    {
        $postData = $this->input->post('status');
        $data = $this->production_model->load_data_for_recipe($postData);
        echo json_encode($data);
    }
    public function editRecipe($id)
    {  
        if (!has_permission_new('recipe', '', 'view')) {
            ajax_access_denied();
        }
        if ($this->input->post()) {
            if (!has_permission_new('recipe', '', 'edit')) {
                ajax_access_denied();
            }
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $data = $this->input->post();
                
                $recipe_id = $this->input->post('id');
                $count = $data["countof_record"]; 
                $ItCount = $count - 1;  
            /*echo "<pre>";
            echo $recipe_id;
            echo "<br>";
            print_r($data);
            die;*/
            
            //new Item aadded
                $new_record = $data["new_record"];
                $new_record = str_replace(" ,",'',$data["new_record"]);
                $new_record_array = explode(',', $new_record);
                
                //update exiting Item
                $edit_record = $data["updated_record"];
                $edit_record = str_replace(" ,",'',$data["updated_record"]);
                $edit_record_array = explode(',', $edit_record);
                
                // delete exiting Item
                $delete_record = $data["deleted_record"];
                $delete_record = str_replace(" ,",'',$data["deleted_record"]);
                $delete_record_array = explode(',', $delete_record);
                
                
                $this->db->where('rec_id', $recipe_id);
                $this->db->where_in('item_id', $delete_record_array);
                $this->db->where('PlantID', $selected_company);
                //$this->db->where('FY', $fy);
                $this->db->delete(db_prefix() . 'recipe_details');
                
            // Check Recipe Exit OR Not
            $ReceipDetails = $this->production_model->get_recipe_details($data["item_code"]);
            if($ReceipDetails){
                if($ReceipDetails->status == $data["status"]){
                    
                }else{
                    if($data["status"] == "Y"){
                        $OldRecUpdate = array(
                            'status'=>'Y',
                            'ActiveDate'=>date('Y-m-d H:i:s'),
                            'ADUserID'=>$this->session->userdata('username'),
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                    }else{
                        $OldRecUpdate = array(
                            'status'=>'N',
                            'DeActiveDate'=>date('Y-m-d H:i:s'),
                            'ADUserID'=>$this->session->userdata('username'),
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                    }
                }
                
                $this->db->where(db_prefix() . 'recipe.item_code', $data["item_code"]);
                $this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
                //$this->db->where('FY', $fy);
                $UpdateOldReceipe = $this->db->update(db_prefix() . 'recipe', $OldRecUpdate);
            }    
                
               
             if($recipe_id){ 
                    $receipt_date = array(
                        "item_code"=>$data["item_code"],
                        "item_description"=>$data["item_desc"],
                        "qty"=>$data["qtytoproduce"],
                        "unit"=>$data["unit_f_g1"],
                        "conv_cost"=>$data["conv_cost"],
                        "st_cost"=>$data["st_cost"],
                        "frt_cost"=>$data["frt_cost"],
                        "mrkt_cost"=>$data["mrkt_cost"],
                        "dmg_cost"=>$data["dmg_cost"],
                        "UserID2"=>$this->session->userdata('username'),
                        "Lupdate"=>date('Y-m-d H:i:s')
                    );
               
            $multiClause = array('id' => $recipe_id);
            $this->db->where($multiClause);   
            $query = $this->db->update(db_prefix() . 'recipe', $receipt_date);
             
            for($i=1; $i<$count; $i++) { 
                $itemid = "item_id".$i;
                $itemName = "item_name".$i;
                $reqQty = "req_qty".$i;
                $itemUnit = "unit".$i;
                $itemrownum = "rownum".$i;
              
                    if(in_array($data[$itemid], $new_record_array)){
                        
                        $new_record_details = array(
                            "PlantID"=>$selected_company,
                            "FY"=>$fy,
                            "item_id"=>$data[$itemid],
                            "item_name"=>$data[$itemName],
                            "req_qty"=>$data[$reqQty],
                            "unit"=>$data[$itemUnit],
                            "rec_id"=>$recipe_id,
                            "Ordinalno"=>$itemrownum,
                            "UserID"=>$this->session->userdata('username'),
                            "TransDate"=>date('Y-m-d H:i:s'),
                        );
                        $this->db->insert(db_prefix() . 'recipe_details', $new_record_details);
                    }
               
                if(in_array($data[$itemid], $edit_record_array)){
                        $edit_record_details = array(
                            "req_qty"=>$data[$reqQty],
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s'),
                        );
                        //print_r($edit_record_details); 
                        $this->db->where('rec_id', $recipe_id);
                        $this->db->where('item_id', $data[$itemid]);
                        $this->db->where('PlantID', $selected_company);
                        //$this->db->where('FY', $fy);
                        $pr =$this->db->update(db_prefix() . 'recipe_details', $edit_record_details);
                    }      
                }  //for end
            } // if end
            //die;
            set_alert('success', 'Recipe Updated Successfully..');
                redirect(admin_url('production/editRecipe/'.$recipe_id));    
        }
        $this->load->model('sale_reports_model');
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $data['editRecipe_details'] = $this->production_model->edit_recipe($id);
        $data['editRecipe_details1'] = $this->production_model->edit_recipe1($id);
        $status = 'Y';
        $data['recipe_list'] = $this->production_model->load_data_for_recipe($status);
        $data['title'] = _l('Edit Recipe');
        $this->load->view('admin/production/edit_recipe', $data); 
       
    } 
  
    public function production_order()
    {
        if (!has_permission_new('production', '', 'view')) {
            ajax_access_denied();
        }
        if($this->input->post()){
            if (!has_permission_new('production', '', 'edit')) {
                ajax_access_denied();
            }
            
            $data = $this->input->post();
            $proId = $this->input->post('pid');
          
            $count = $data["countof_record"];
            $count1 = $data["countof_record1"];   
            
            $new_record = $data["new_record"];
            $new_record = str_replace(" ,",'',$data["new_record"]);
            $new_record_array = explode(',', $new_record);
        
            $new_record1 = $data["new_record1"];
            $new_record1 = str_replace(" ,",'',$data["new_record1"]);
            $new_record_array1 = explode(',', $new_record1);
            
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            $LogIn = $this->session->userdata('username');
            $GodownID = $data["GodownID"];
            $selected_company = $this->session->userdata('root_company');
            if($selected_company == "1"){
                $GodownIDF = 'CSPL';
            }else if($selected_company == "2"){
                $GodownIDF = 'CFF';
            }else if($selected_company == "3"){
                $GodownIDF = 'CBUPL';
            }
                    
            if($data["status"]=="pending"){
                if($data["opttype"] == "1"){
                    $production_data = array(
                        "TransDate"=>to_sql_date($data["start_date"]).' '.date('H:i:s'),
                        "required_time"=>$data["req_time"],
                        "batch_qty"=>$data["batch_qty"],
                        "GodownID"=>$GodownID,
                        "Finish_good_qty"=>$data["qty_product"],
                        "remark"=>$data["remark"],
                        "comment"=>$data["comments"],
                        "production_status"=>$data["status"],
                        "manager_name"=>$data["operator_name"],
                        "UserID2"=>$LogIn,
                        "Lupdate"=>date('Y-m-d H:i:s')
                    );
                }else{
                    $production_data = array(
                        "TransDate"=>to_sql_date($data["start_date"]).' '.date('H:i:s'),
                        "required_time"=>$data["req_time"],
                        "batch_qty"=>$data["batch_qty"],
                        "GodownID"=>$GodownID,
                        "Finish_good_qty"=>$data["qty_product"],
                        "remark"=>$data["remark"],
                        "comment"=>$data["comments"],
                        "production_status"=>$data["status"],
                        "contractor_name"=>$data["operator_name"],
                        "UserID2"=>$LogIn,
                        "Lupdate"=>date('Y-m-d H:i:s')
                    );
                }
                
                $productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
                );
                $this->db->where($productionChange);    
                $query = $this->db->update(db_prefix() . 'production', $production_data);
                
                // Update  Godown ID
                
                $GodownUpdate = array(
                    "GodownID"=>$GodownID,
                );
                $this->db->where('production_id', $proId);
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $fy);
                $this->db->update(db_prefix() . 'production_details', $GodownUpdate);
                
                $production_details = $this->production_model->get_production_order($proId);
                
                // Add extra qty    
                for($i=1; $i<$count; $i++) { 
                     
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                  
                    if($new_record_array){  
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                            }
                        }
                        $edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                    }
                }
                
                // return qty 
                for($j=1; $j<$count1; $j++) {
                    $itemid = "pro_item_id".$j;
                    $itemName = "pro_item_name".$j;
                    $reqQty = "pro_req_qty".$j;
                    $return_reqQty = "return_pro_req_qty".$j;
                    $itemUnit = "pro_unit".$j;
                
                    if($new_record_array1){
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                            }
                        }
                        $edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                            
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                                 
                    } 
                }
            }else if($data["status"]=="In-Progress"){
                    $PRDDetails = $this->production_model->GetPrdDetails($proId);
                    //if($selected_company == "3"){
                        // Check Row Material Stock 
                        if($PRDDetails->status_count == "0"){
                            $PRDItemStocks = $this->production_model->GetPRDItemStock($proId,$GodownID);
                            $PRDItemOQty = $this->production_model->GetPRDItemOQty($proId,$GodownID);
                            
                            foreach ($PRDDetails->items as $key => $value) {
                                $PRDQtyNew = 0;
                                $PRDQtyNew += $value["production_req_qty"] + $value["return_req_qty"] + $value["ExtraQty"];
                                // Add extra qty
                                for($i=1; $i<$count; $i++) { 
                                    $itemid = "item_id".$i;
                                    $itemName = "item_name".$i;
                                    $reqQty = "req_qty".$i;
                                    $itemUnit = "unit".$i;
                                    if($new_record_array){
                                        if($data[$itemid] == $value["item_id"]){
                                            $PRDQtyNew += $data[$reqQty];
                                        }
                                    }
                                }
                                // return qty 
                                for($j=1; $j<$count1; $j++) {
                                    $itemid = "pro_item_id".$j;
                                    $itemName = "pro_item_name".$j;
                                    $reqQty = "pro_req_qty".$j;
                                    $return_reqQty = "return_pro_req_qty".$j;
                                    $itemUnit = "pro_unit".$j;
                                    if($new_record_array1){
                                        if($data[$itemid] == $value["item_id"]){
                                            $PRDQtyNew = $PRDQtyNew - $data[$return_reqQty];
                                        }
                                    }
                                }
                                $PQty = 0;
                                $PRQty = 0;
                                $IQty = 0;
                                $PRDQty = 0;
                                $SQty = 0;
                                $SRTQty = 0;
                                $AQty = 0;
                                $OQty = 0;
                                $GOQty = 0;
                                $GIQty = 0;
                                /*echo "<pre>";
                                print_r($PRDItemStocks);
                                die;*/
                                foreach ($PRDItemOQty as $Ostock) {
                                    if(strtoupper($Ostock['ItemID'])==strtoupper($value['item_id'])){
                                        $OQty = $Ostock['OQty'];
                                    }
                                }
                                foreach ($PRDItemStocks as $stock) {
                                        if(strtoupper($stock['ItemID'])==strtoupper($value['item_id'])){
                                            
                                            if($stock['TType'] == 'P'){
                                                $PQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'N'){
                                                $PRQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'A'){
                                                $IQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'B'){
                                                $PRDQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
                                                $SQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
                                                $SRTQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
                                                $AQty += $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free distribution'){
                                                $AQty += $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged'){
                                                $AQty += $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
                                                $AQty += $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
                                                $AQty += $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
                                                $GIQty = $stock['BilledQty'];
                                            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
                                                $GOQty = $stock['BilledQty'];
                                            }
                                        }
                                    }
                                $stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty + $GIQty - $GOQty;
                                if($stockQty <= $PRDQtyNew){
                                    set_alert('warning', $value['item_id'].'== '.$stockQty);
                                    redirect(admin_url('production/production_order/'.$proId));
                                }
                            }
                        }
                        // End stock check
                    //}
                    if($PRDDetails->status_count !== "0"){
                        if(isset($data["finish_outcome"]) && $data["finish_outcome"] !== "" && $data["finish_outcome"] !== "0.00"){
                            if($PRDDetails->p_end_time == "" || $PRDDetails->p_end_time == null){
                                $p_end_time = date('Y-m-d H:i:s');
                            }else{
                                $p_end_time = $PRDDetails->p_end_time;
                            }
                            $Finish_good_qty_new = $data["finish_outcome"];
                        }else{
                            $Finish_good_qty_new = 0.00;
                        }
                        // Stock Revart for Finish Goods
                        $stock_details = $this->production_model->get_stock_details($data["ItemID"]);
                        $BilledQty = $this->production_model->get_PRD_Details($proId);
                        $new_prd = $stock_details->PRDQty - $BilledQty->BilledQty;
                        $stock_array = array(
                            'PRDQty' =>$new_prd
                        );
                        $stockwhere = array(
                            'ItemID' => $data["ItemID"],
                            'PlantID' => $selected_company,
                            'FY' => $fy,
                            'GodownID' => $GodownIDF,
                        );
                        $this->db->where($stockwhere);    
                        $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array);
                        
                    // Stock Revart for Row Materials
                        $StkForRM = $this->production_model->GetStockDetailsForRM($proId);
                        foreach ($StkForRM as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $new_issue = $value["IQty"] - $acutal_issue;
                            $stock_array_fr_row = array(
                                'IQty' =>$new_issue
                            );
                            $stockwhere_row = array(
                                'ItemID' => $value["item_id"],
                                'PlantID' => $selected_company,
                                'FY' => $fy,
                                'GodownID' => $value["GodownID"],
                            );
                            $this->db->where($stockwhere_row);    
                            $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array_fr_row);
                        }
                        
                        if($data["opttype"] == "1"){
                            $production_data = array(
                                "required_time"=>$data["req_time"],
                                "batch_qty"=>$data["batch_qty"],
                                "GodownID"=>$GodownID,
                                "Finish_good_qty_new"=>$Finish_good_qty_new,
                                "p_end_time"=>$p_end_time,
                                "remark"=>$data["remark"],
                                "comment"=>$data["comments"],
                                "production_status"=>$data["status"],
                                "manager_name"=>$data["operator_name"],
                                "status_count"=>1,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                        }else{
                            $production_data = array(
                                "required_time"=>$data["req_time"],
                                "batch_qty"=>$data["batch_qty"],
                                "GodownID"=>$GodownID,
                                "Finish_good_qty_new"=>$Finish_good_qty_new,
                                "p_end_time"=>$p_end_time,
                                "remark"=>$data["remark"],
                                "comment"=>$data["comments"],
                                "production_status"=>$data["status"],
                                "contractor_name"=>$data["operator_name"],
                                "status_count"=>1,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                        }
                    }
                
                if($PRDDetails->status_count == "0"){
                    if($data["opttype"] == "1"){
                        $production_data = array(
                            "required_time"=>$data["req_time"],
                            "batch_qty"=>$data["batch_qty"],
                            "GodownID"=>$GodownID,
                            "Finish_good_qty"=>$data["qty_product"],
                            "p_start_time"=>date('Y-m-d H:i:s'),
                            "remark"=>$data["remark"],
                            "comment"=>$data["comments"],
                            "production_status"=>$data["status"],
                            "manager_name"=>$data["operator_name"],
                            "status_count"=>1,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                    }else{
                        $production_data = array(
                            "required_time"=>$data["req_time"],
                            "batch_qty"=>$data["batch_qty"],
                            "GodownID"=>$GodownID,
                            "Finish_good_qty"=>$data["qty_product"],
                            "p_start_time"=>date('Y-m-d H:i:s'),
                            "remark"=>$data["remark"],
                            "comment"=>$data["comments"],
                            "production_status"=>$data["status"],
                            "contractor_name"=>$data["operator_name"],
                            "status_count"=>1,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                    }
                }    
                    
                    $productionChange = array(
                        'pro_order_id' => $proId,
                        'PlantID' => $selected_company,
                        'FY' => $fy
                    );
                    $this->db->where($productionChange);    
                    $query = $this->db->update(db_prefix() . 'production', $production_data);
                    
                    // Add extra qty    
                    for($i=1; $i<$count; $i++) { 
                         
                        $itemid = "item_id".$i;
                        $itemName = "item_name".$i;
                        $reqQty = "req_qty".$i;
                        $itemUnit = "unit".$i;
                      
                        if($new_record_array){  
                            foreach ($PRDDetails->items as $key => $value) {
                                if($data[$itemid] == $value["item_id"]){
                                    $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                                }
                            }
                            $edit_prd_qty = array(
                                "ExtraQty"=>$newExtraQty,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where('production_id', $proId);
                            $this->db->where('item_id', $data[$itemid]);
                            $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                        }
                    }
                
                    // return qty 
                    for($j=1; $j<$count1; $j++) {
                        $itemid = "pro_item_id".$j;
                        $itemName = "pro_item_name".$j;
                        $reqQty = "pro_req_qty".$j;
                        $return_reqQty = "return_pro_req_qty".$j;
                        $itemUnit = "pro_unit".$j;
                    
                        if($new_record_array1){
                            foreach ($PRDDetails->items as $key => $value) {
                                if($data[$itemid] == $value["item_id"]){
                                    $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                                }
                            }
                            $edit_record_details = array(
                                "return_req_qty"=>$new_rtn_qty,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where('production_id', $proId);
                            $this->db->where('item_id', $data[$itemid]);
                            $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                        } 
                    }
                     
             
                if($PRDDetails->status_count !== "0"){
                    // Update History table for Production table
                    $history_update = array(
                        'OrderQty' =>$Finish_good_qty_new,
                        'BilledQty' =>$Finish_good_qty_new,
                        "UserID2"=>$LogIn,
                        "Lupdate"=>date('Y-m-d H:i:s')
                    );
                    $where_row = array(
                        'OrderID' => $proId,
                        'PlantID' => $selected_company,
                        'FY' => $fy
                    );
                    $this->db->where($where_row);    
                    $query = $this->db->update(db_prefix() . 'history', $history_update);
                    
                // Update History Table for Row Material
                    $GetRowMaterial = $this->production_model->GetRowMaterialDetails($proId);
                        foreach ($GetRowMaterial as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $whereIssue_row = array(
                                'ItemID' =>$value["item_id"],
                                'OrderID' => $proId,
                                'PlantID' => $selected_company,
                                'FY' => $fy
                            );
                            $history_IssueUpdate = array(
                                'OrderQty' =>$acutal_issue,
                                'BilledQty' =>$acutal_issue,
                                'GodownID' => $GodownID,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where($whereIssue_row);    
                            $query = $this->db->update(db_prefix() . 'history', $history_IssueUpdate);
                        }
                    
                }else{
                    
                    // Move Finish Goods to History table
                    
                        $FGDetails = $this->production_model->GetPrdFGDetails($proId);   
                        if($FGDetails->manager_name == null){
                            $accountId = $FGDetails->contractor_name;
                        }else{
                            $accountId = $FGDetails->manager_name;
                        }
                        
                        $history_details = array(
                            'PlantID' =>$selected_company,
                            'FY' =>$fy,
                            'cnfid' =>1,
                            'OrderID' =>$proId,
                            'TransDate' =>$FGDetails->TransDate,
                            'BillID' =>$proId,
                            'TransID' =>$proId,
                            'TransDate2' =>$FGDetails->TransDate,
                            'TType' =>"B",
                            'TType2' =>"Production",
                            'AccountID' =>$accountId,
                            'GodownID' =>$GodownIDF,
                            'ItemID' =>$data["ItemID"],
                            'BasicRate' =>$FGDetails->ItemRate->BasicRate,
                            'SaleRate' =>$FGDetails->ItemRate->SaleRate,
                            'CaseQty' =>$FGDetails->case_qty,
                            'OrderQty' =>$FGDetails->Finish_good_qty,
                            'BilledQty' =>$FGDetails->Finish_good_qty,
                            'Ordinalno' =>1,
                            'UserID' =>$FGDetails->UserID
                        );
                        $this->db->insert(db_prefix() . 'history', $history_details);
                        
                        // Move Row Material to History table
                        $RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
                        $RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
                        $i = 1;
                        foreach ($RMItemDetails as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            foreach ($RMItemOtherDetails as $key1 => $value1) {
                                if($value["item_id"] == $value1["item_id"]){
                                    $CaseQty = $value1["CaseQty"];
                                    $BasicRate = $value1["BasicRate"];
                                    $SaleRatee = $value1["SaleRate"];
                                }
                                
                            }
                            $history_details = array(
                                'PlantID' =>$selected_company,
                                'FY' =>$fy,
                                'cnfid' =>1,
                                'OrderID' =>$proId,
                                'TransDate' =>$value["TransDate"],
                                'BillID' =>$proId,
                                'TransID' =>$proId,
                                'TransDate2' =>$value["TransDate"],
                                'TType' =>"A",
                                'TType2' =>"Issue",
                                'AccountID' =>$accountId,
                                'GodownID' =>$GodownID,
                                'CaseQty' =>$CaseQty,
                                'BasicRate' =>$BasicRate,
                                'SaleRate' =>$SaleRatee,
                                'ItemID' =>$value["item_id"],
                                'OrderQty' =>$acutal_issue,
                                'BilledQty' =>$acutal_issue,
                                'Ordinalno' =>$i,
                                'UserID' =>$value["UserID"]
                            );
                            $this->db->insert(db_prefix() . 'history', $history_details);
                            $i++;
                        }
                }
                
                // Stock update for Finish Goods
                        $stock_details = $this->production_model->get_stock_details($data["ItemID"]);
                        $new_prd = $stock_details->PRDQty + $Finish_good_qty_new;
                        $stock_array = array(
                            'PRDQty' =>$new_prd
                        );
                        $stockwhere = array(
                            'ItemID' => $data["ItemID"],
                            'PlantID' => $selected_company,
                            'FY' => $fy,
                            'GodownID' =>$GodownIDF,
                        );
                        $this->db->where($stockwhere);    
                        $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array);
                        
                    // Stock update for Row Materials
                        $StkForRM = $this->production_model->GetStockDetailsForRM($proId);
                        foreach ($StkForRM as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $new_issue = $value["IQty"] + $acutal_issue;
                            $stock_array_fr_row = array(
                                'IQty' =>$new_issue
                            );
                            $stockwhere_row = array(
                                'ItemID' => $value["item_id"],
                                'PlantID' => $selected_company,
                                'FY' => $fy,
                                'GodownID' =>$value["GodownID"],
                            );
                            $this->db->where($stockwhere_row);    
                            $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array_fr_row);
                        }
            }else if($data["status"]=="Completed"){
                $PRDDetails = $this->production_model->GetPrdDetails($proId);
                
                // Stock Revart for Finish Goods
                        $stock_details = $this->production_model->get_stock_details($data["ItemID"]);
                        $BilledQty = $this->production_model->get_PRD_Details($proId);
                        $new_prd = $stock_details->PRDQty - $BilledQty->BilledQty;
                        $stock_array = array(
                            'PRDQty' =>$new_prd
                        );
                        $stockwhere = array(
                            'ItemID' => $data["ItemID"],
                            'PlantID' => $selected_company,
                            'FY' => $fy,
                            'GodownID' =>$GodownIDF,
                        );
                        $this->db->where($stockwhere);    
                        $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array);
                        
                    // Stock Revart for Row Materials
                        $StkForRM = $this->production_model->GetStockDetailsForRM($proId);
                        foreach ($StkForRM as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $new_issue = $value["IQty"] - $acutal_issue;
                            $stock_array_fr_row = array(
                                'IQty' =>$new_issue
                            );
                            $stockwhere_row = array(
                                'ItemID' => $value["item_id"],
                                'PlantID' => $selected_company,
                                'FY' => $fy,
                                'GodownID' =>$GodownID,
                            );
                            $this->db->where($stockwhere_row);    
                            $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array_fr_row);
                        }
                    if($PRDDetails->p_end_time == "" || $PRDDetails->p_end_time == null){
                        $p_end_time = date('Y-m-d H:i:s');
                    }else{
                        $p_end_time = $PRDDetails->p_end_time;
                    }
                    if($data["opttype"] == "1"){
                        $production_data = array(
                            "Finish_good_qty_new"=>$data["finish_outcome"],
                            "remark"=>$data["remark"],
                            "comment"=>$data["comments"],
                            "p_end_time"=>$p_end_time,
                            "production_status"=>$data["status"],
                            "manager_name"=>$data["operator_name"],
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                    }else{
                        $production_data = array(
                            "Finish_good_qty_new"=>$data["finish_outcome"],
                            "remark"=>$data["remark"],
                            "comment"=>$data["comments"],
                            "p_end_time"=>$p_end_time,
                            "production_status"=>$data["status"],
                            "contractor_name"=>$data["operator_name"],
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                    }
                    $productionChange = array(
                        'pro_order_id' => $proId,
                        'PlantID' => $selected_company,
                        'FY' => $fy
                    );
                    $this->db->where($productionChange);    
                    $query = $this->db->update(db_prefix() . 'production', $production_data);
                    
                    
                    // Add extra qty    
                    for($i=1; $i<$count; $i++) { 
                         
                        $itemid = "item_id".$i;
                        $itemName = "item_name".$i;
                        $reqQty = "req_qty".$i;
                        $itemUnit = "unit".$i;
                      
                        if($new_record_array){  
                            foreach ($PRDDetails->items as $key => $value) {
                                if($data[$itemid] == $value["item_id"]){
                                    $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                                }
                            }
                            $edit_prd_qty = array(
                                "ExtraQty"=>$newExtraQty,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where('production_id', $proId);
                            $this->db->where('item_id', $data[$itemid]);
                            $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                        }
                    }
                
                    // return qty 
                    for($j=1; $j<$count1; $j++) {
                        $itemid = "pro_item_id".$j;
                        $itemName = "pro_item_name".$j;
                        $reqQty = "pro_req_qty".$j;
                        $return_reqQty = "return_pro_req_qty".$j;
                        $itemUnit = "pro_unit".$j;
                    
                        if($new_record_array1){
                            foreach ($PRDDetails->items as $key => $value) {
                                if($data[$itemid] == $value["item_id"]){
                                    $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                                }
                            }
                            $edit_record_details = array(
                                "return_req_qty"=>$new_rtn_qty,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where('production_id', $proId);
                            $this->db->where('item_id', $data[$itemid]);
                            $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                        } 
                    }
                    
                    // Update History table for Production table
                    $history_update = array(
                        'OrderQty' =>$data["finish_outcome"],
                        'BilledQty' =>$data["finish_outcome"],
                        "UserID2"=>$LogIn,
                        "Lupdate"=>date('Y-m-d H:i:s')
                    );
                    $where_row = array(
                        'OrderID' => $proId,
                        'PlantID' => $selected_company,
                        'FY' => $fy
                    );
                    $this->db->where($where_row);    
                    $query = $this->db->update(db_prefix() . 'history', $history_update);
                    
                // Update History Table for Row Material
                    $GetRowMaterial = $this->production_model->GetRowMaterialDetails($proId);
                        foreach ($GetRowMaterial as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $whereIssue_row = array(
                                'ItemID' =>$value["item_id"],
                                'OrderID' => $proId,
                                'PlantID' => $selected_company,
                                'FY' => $fy
                            );
                            $history_IssueUpdate = array(
                                'OrderQty' =>$acutal_issue,
                                'BilledQty' =>$acutal_issue,
                                "UserID2"=>$LogIn,
                                "Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where($whereIssue_row);    
                            $query = $this->db->update(db_prefix() . 'history', $history_IssueUpdate);
                        }
                    
                    // Stock update for Finish Goods
                        $stock_details = $this->production_model->get_stock_details($data["ItemID"]);
                        $new_prd = $stock_details->PRDQty + $data["finish_outcome"];
                        $stock_array = array(
                            'PRDQty' =>$new_prd
                        );
                        $stockwhere = array(
                            'ItemID' => $data["ItemID"],
                            'PlantID' => $selected_company,
                            'FY' => $fy,
                            'GodownID' =>$GodownIDF,
                        );
                        $this->db->where($stockwhere);    
                        $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array);
                        
                    // Stock update for Row Materials
                        $StkForRM = $this->production_model->GetStockDetailsForRM($proId);
                        foreach ($StkForRM as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $new_issue = $value["IQty"] + $acutal_issue;
                            $stock_array_fr_row = array(
                                'IQty' =>$new_issue
                            );
                            $stockwhere_row = array(
                                'ItemID' => $value["item_id"],
                                'PlantID' => $selected_company,
                                'FY' => $fy,
                                'GodownID' =>$value["GodownID"],
                            );
                            $this->db->where($stockwhere_row);    
                            $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array_fr_row);
                        }
            }else if($data["status"]=="cancel"){
          
                    $production_details = $this->production_model->get_PRD_DetailsFromHistory($proId);
                    if($production_details){
                        
                        // Stock Revart for Finish Goods
                        $stock_details = $this->production_model->get_stock_details($data["ItemID"]);
                        $BilledQty = $this->production_model->get_PRD_Details($proId);
                        $new_prd = $stock_details->PRDQty - $BilledQty->BilledQty;
                        $stock_array = array(
                            'PRDQty' =>$new_prd
                        );
                        $stockwhere = array(
                            'ItemID' => $data["ItemID"],
                            'PlantID' => $selected_company,
                            'FY' => $fy,
                            'GodownID' =>$GodownIDF,
                        );
                        $this->db->where($stockwhere);    
                        $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array);
                        
                    // Stock Revart for Row Materials
                        $StkForRM = $this->production_model->GetStockDetailsForRM($proId);
                        foreach ($StkForRM as $key => $value) {
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $new_issue = $value["IQty"] - $acutal_issue;
                            $stock_array_fr_row = array(
                                'IQty' =>$new_issue
                            );
                            $stockwhere_row = array(
                                'ItemID' => $value["item_id"],
                                'PlantID' => $selected_company,
                                'FY' => $fy,
                                'GodownID' =>$GodownID,
                            );
                            $this->db->where($stockwhere_row);    
                            $query = $this->db->update(db_prefix() . 'stockmaster', $stock_array_fr_row);
                        }
                    // Delete record from History
                        $this->db->where(db_prefix() . 'history.OrderID', $proId);
                        $this->db->delete(db_prefix() . 'history');
                    }
                    $production_data = array(
                        "remark"=>$data["remark"],
                        "comment"=>$data["comments"],
                        "production_status"=>$data["status"],
                    );
                    $productionChange = array(
                        'pro_order_id' => $proId,
                        'PlantID' => $selected_company,
                        'FY' => $fy
                    );
                    $this->db->where($productionChange);    
                    $query = $this->db->update(db_prefix() . 'production', $production_data);
            }
            set_alert('success', 'Production Order Updated Successfully..');
            redirect(admin_url('production/production_order/'.$proId));
        }
        $this->load->model('sale_reports_model');
        $pro_orid =$this->uri->segment(4); 
        $data['manager'] = $this->production_model->get_managername();
        $data['contractor'] = $this->production_model->get_contractorname();
        $data['production'] = $this->production_model->GetPrdDetails($pro_orid);
        $GodownID = $data['production']->GodownID;
        $PrdItem = array();
        foreach ($data['production']->items as $key=>$value) {
           array_push($PrdItem, $value["item_id"]);
        }
        //$data['ItemStocks'] = $this->production_model->GetPRDItemStock($pro_orid);
        $data['ItemStocks'] = $this->production_model->GetPRDItemStockNew($PrdItem,$GodownID);
        $data['OQtyItems'] = $this->production_model->GetPRDItemOQty($pro_orid,$GodownID);
        $data['GodownData'] = $this->production_model->GetGodownData();
        $data['ReceipeDetails'] = $this->production_model->get_receipeDetails($data['production']->recipeID);
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $data['title'] = _l('Production Order');
        /*echo "<pre>";
        print_r($data['ReceipeDetails']);
        die;*/
        $this->load->view('admin/production/production_order', $data);
    }
    
    public function edit_prd()
    {
        if (!has_permission_new('production', '', 'view')) {
            ajax_access_denied();
        }
        if($this->input->post()){
            if (!has_permission_new('production', '', 'edit')) {
                ajax_access_denied();
            }
            
            $data = $this->input->post();
            $proId = $this->input->post('pid');
          
            $count = $data["countof_record"];
            $count1 = $data["countof_record1"];   
            
            $new_record = $data["new_record"];
            $new_record = str_replace(" ,",'',$data["new_record"]);
            $new_record_array = explode(',', $new_record);
        
            $new_record1 = $data["new_record1"];
            $new_record1 = str_replace(" ,",'',$data["new_record1"]);
            $new_record_array1 = explode(',', $new_record1);
            
            $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            $LogIn = $this->session->userdata('username');
            $GodownID_FG = $data["fg_store"];
            $GodownID_RM = $data["rm_store"];
            $GodownID_Scrap = $data["scrap_store"];
            
            $production_details = $this->production_model->get_production_order($proId);   
            if($data["status"]=="pending"){
                // Add extra qty    
                for($i=1; $i<$count; $i++) { 
                     
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                  
                    if($new_record_array){  
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                            }
                        }
                        $edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                    }
                }
                
                // return qty 
                for($j=1; $j<$count1; $j++) {
                    $itemid = "pro_item_id".$j;
                    $itemName = "pro_item_name".$j;
                    $reqQty = "pro_req_qty".$j;
                    $return_reqQty = "return_pro_req_qty".$j;
                    $itemUnit = "pro_unit".$j;
                
                    if($new_record_array1){
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                            }
                        }
                        $edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                            
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                                 
                    } 
                }
                $production_data = array(
                    "remark"=>$data["remark"],
                    "comment"=>$data["comments"],
                    "production_status"=>$data["status"],
                    "manager_name"=>$data["operator_name"]
                );
                $productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
                );
                $this->db->where($productionChange);    
                $query = $this->db->update(db_prefix() . 'production', $production_data);
            }else if($data["status"]=="WELDING"){
                
                $NewScrap = $data["Scrap_old_qty"] + $data["Scrap_new_qty"];
                // Update Scrap in production details
                $edit_prd_qty = array(
                    "production_req_qty"=>$NewScrap,
                    "UserID2"=>$LogIn,
                    "Lupdate"=>date('Y-m-d H:i:s')
                );
                $this->db->where('production_id', $proId);
                $this->db->where('item_id', "SCRAP");
                $this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                
                // Add extra qty    
                for($i=1; $i<$count; $i++) { 
                     
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                  
                    if($new_record_array){  
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                            }
                        }
                        $edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                    }
                }
                
                // return qty 
                for($j=1; $j<$count1; $j++) {
                    $itemid = "pro_item_id".$j;
                    $itemName = "pro_item_name".$j;
                    $reqQty = "pro_req_qty".$j;
                    $return_reqQty = "return_pro_req_qty".$j;
                    $itemUnit = "pro_unit".$j;
                
                    if($new_record_array1){
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                            }
                        }
                        $edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                            
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                                 
                    } 
                }
                
                // Move or Update Row Material to History table
                $RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
                $RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
                $IssueDetails = $this->production_model->GetIssueDetails($proId);
                
                $i = 1;
                foreach ($RMItemDetails as $key => $value) {
                    $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                    foreach ($RMItemOtherDetails as $key1 => $value1) {
                        if($value["item_id"] == $value1["item_id"]){
                            $CaseQty = $value1["CaseQty"];
                            $BasicRate = $value1["BasicRate"];
                            $SaleRatee = $value1["SaleRate"];
                        }
                        
                    }
                    $Moved = 0;
                    foreach($IssueDetails as $Ikey => $Ivalue){
                        if($Ivalue["ItemID"] == $value["item_id"]){
                            $Moved++;
                        }
                    }
                    if($Moved > 0){
                        $history_update = array(
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'UserID2' =>$LogIn,
                            'Lupdate' =>date('Y-m-d H:i:s')
                        );
                        $this->db->where('OrderID', $proId);
                        $this->db->where('ItemID', $value["item_id"]);
                        $pr=$this->db->update(db_prefix() . 'history', $history_update);
                        $i++;
                    }else{
                        
                        $history_insert = array(
                            'PlantID' =>$selected_company,
                            'FY' =>$fy,
                            'cnfid' =>1,
                            'OrderID' =>$proId,
                            'TransDate' =>$value["TransDate"],
                            'BillID' =>$proId,
                            'TransID' =>$proId,
                            'TransDate2' =>date('Y-m-d H:i:s'),
                            'TType' =>"A",
                            'TType2' =>"Issue",
                            'AccountID' =>$LogIn,
                            'GodownID' =>$GodownID_RM,
                            'CaseQty' =>$CaseQty,
                            'BasicRate' =>$BasicRate,
                            'SaleRate' =>$SaleRatee,
                            'ItemID' =>$value["item_id"],
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'Ordinalno' =>$i,
                            'UserID' =>$LogIn
                        );
                        $this->db->insert(db_prefix() . 'history', $history_insert);
                        $i++;
                    }
                }
                $production_data = array(
                    "welding_remark"=>$data["welding_remark"],
                    "comment"=>$data["comments"],
                    "production_status"=>$data["status"],
                    "p_start_time"=>date('Y-m-d H:i:s'),
                    "manager_name"=>$data["operator_name"]
                );
                $productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
                );
                $this->db->where($productionChange);    
                $query = $this->db->update(db_prefix() . 'production', $production_data);
            }else if($data["status"]=="ASSEMBLY"){
                
                $NewScrap = $data["Scrap_old_qty"] + $data["Scrap_new_qty"];
                // Update Scrap in production details
                $edit_prd_qty = array(
                    "production_req_qty"=>$NewScrap,
                    "UserID2"=>$LogIn,
                    "Lupdate"=>date('Y-m-d H:i:s')
                );
                $this->db->where('production_id', $proId);
                $this->db->where('item_id', "SCRAP");
                $this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                
                // Add extra qty    
                for($i=1; $i<$count; $i++) { 
                     
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                  
                    if($new_record_array){  
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                            }
                        }
                        $edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                    }
                }
                
                // return qty 
                for($j=1; $j<$count1; $j++) {
                    $itemid = "pro_item_id".$j;
                    $itemName = "pro_item_name".$j;
                    $reqQty = "pro_req_qty".$j;
                    $return_reqQty = "return_pro_req_qty".$j;
                    $itemUnit = "pro_unit".$j;
                
                    if($new_record_array1){
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                            }
                        }
                        $edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                            
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                                 
                    } 
                }
                // Move or Update Row Material to History table
                $RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
                $RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
                $IssueDetails = $this->production_model->GetIssueDetails($proId);
                $i = 1;
                foreach ($RMItemDetails as $key => $value) {
                    $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                    foreach ($RMItemOtherDetails as $key1 => $value1) {
                        if($value["item_id"] == $value1["item_id"]){
                            $CaseQty = $value1["CaseQty"];
                            $BasicRate = $value1["BasicRate"];
                            $SaleRatee = $value1["SaleRate"];
                        }
                        
                    }
                    $Moved = 0;
                    foreach($IssueDetails as $Ikey => $Ivalue){
                        if($Ivalue["ItemID"] == $value["item_id"]){
                            $Moved++;
                        }
                    }
                    if($Moved > 0){
                        $history_update = array(
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'UserID2' =>$LogIn,
                            'Lupdate' =>date('Y-m-d H:i:s')
                        );
                        $this->db->where('OrderID', $proId);
                        $this->db->where('ItemID', $value["item_id"]);
                        $pr=$this->db->update(db_prefix() . 'history', $history_update);
                        $i++;
                    }else{
                        
                        $history_insert = array(
                            'PlantID' =>$selected_company,
                            'FY' =>$fy,
                            'cnfid' =>1,
                            'OrderID' =>$proId,
                            'TransDate' =>$value["TransDate"],
                            'BillID' =>$proId,
                            'TransID' =>$proId,
                            'TransDate2' =>date('Y-m-d H:i:s'),
                            'TType' =>"A",
                            'TType2' =>"Issue",
                            'AccountID' =>$LogIn,
                            'GodownID' =>$GodownID_RM,
                            'CaseQty' =>$CaseQty,
                            'BasicRate' =>$BasicRate,
                            'SaleRate' =>$SaleRatee,
                            'ItemID' =>$value["item_id"],
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'Ordinalno' =>$i,
                            'UserID' =>$LogIn
                        );
                        $this->db->insert(db_prefix() . 'history', $history_insert);
                        $i++;
                    }
                }
                $production_data = array(
                    "welding_remark"=>$data["welding_remark"],
                    "assembly_remark"=>$data["assembly_remark"],
                    "comment"=>$data["comments"],
                    "production_status"=>$data["status"],
                    "manager_name"=>$data["operator_name"]
                );
                $productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
                );
                $this->db->where($productionChange);    
                $query = $this->db->update(db_prefix() . 'production', $production_data);
            }else if($data["status"]=="PAINTING"){
                
                $NewScrap = $data["Scrap_old_qty"] + $data["Scrap_new_qty"];
                // Update Scrap in production details
                $edit_prd_qty = array(
                    "production_req_qty"=>$NewScrap,
                    "UserID2"=>$LogIn,
                    "Lupdate"=>date('Y-m-d H:i:s')
                );
                $this->db->where('production_id', $proId);
                $this->db->where('item_id', "SCRAP");
                $this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                
                // Add extra qty    
                for($i=1; $i<$count; $i++) { 
                     
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                  
                    if($new_record_array){  
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                            }
                        }
                        $edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                    }
                }
                
                // return qty 
                for($j=1; $j<$count1; $j++) {
                    $itemid = "pro_item_id".$j;
                    $itemName = "pro_item_name".$j;
                    $reqQty = "pro_req_qty".$j;
                    $return_reqQty = "return_pro_req_qty".$j;
                    $itemUnit = "pro_unit".$j;
                
                    if($new_record_array1){
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                            }
                        }
                        $edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                            
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                                 
                    } 
                }
                // Move or Update Row Material to History table
                $RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
                $RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
                $IssueDetails = $this->production_model->GetIssueDetails($proId);
                $i = 1;
                foreach ($RMItemDetails as $key => $value) {
                    $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                    foreach ($RMItemOtherDetails as $key1 => $value1) {
                        if($value["item_id"] == $value1["item_id"]){
                            $CaseQty = $value1["CaseQty"];
                            $BasicRate = $value1["BasicRate"];
                            $SaleRatee = $value1["SaleRate"];
                        }
                        
                    }
                    $Moved = 0;
                    foreach($IssueDetails as $Ikey => $Ivalue){
                        if($Ivalue["ItemID"] == $value["item_id"]){
                            $Moved++;
                        }
                    }
                    if($Moved > 0){
                        $history_update = array(
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'UserID2' =>$LogIn,
                            'Lupdate' =>date('Y-m-d H:i:s')
                        );
                        $this->db->where('OrderID', $proId);
                        $this->db->where('ItemID', $value["item_id"]);
                        $pr=$this->db->update(db_prefix() . 'history', $history_update);
                        $i++;
                    }else{
                        
                        $history_insert = array(
                            'PlantID' =>$selected_company,
                            'FY' =>$fy,
                            'cnfid' =>1,
                            'OrderID' =>$proId,
                            'TransDate' =>$value["TransDate"],
                            'BillID' =>$proId,
                            'TransID' =>$proId,
                            'TransDate2' =>date('Y-m-d H:i:s'),
                            'TType' =>"A",
                            'TType2' =>"Issue",
                            'AccountID' =>$LogIn,
                            'GodownID' =>$GodownID_RM,
                            'CaseQty' =>$CaseQty,
                            'BasicRate' =>$BasicRate,
                            'SaleRate' =>$SaleRatee,
                            'ItemID' =>$value["item_id"],
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'Ordinalno' =>$i,
                            'UserID' =>$LogIn
                        );
                        $this->db->insert(db_prefix() . 'history', $history_insert);
                        $i++;
                    }
                }
                
                $production_data = array(
                    "welding_remark"=>$data["welding_remark"],
                    "assembly_remark"=>$data["assembly_remark"],
                    "painting_remark"=>$data["painting_remark"],
                    "comment"=>$data["comments"],
                    "production_status"=>$data["status"],
                    "manager_name"=>$data["operator_name"]
                );
                $productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
                );
                $this->db->where($productionChange);    
                $query = $this->db->update(db_prefix() . 'production', $production_data);
            }else if($data["status"]=="MOVEMENT FOR GODOWN"){
                
                $NewScrap = $data["Scrap_old_qty"] + $data["Scrap_new_qty"];
                // Update Scrap in production details
                $edit_prd_qty = array(
                    "production_req_qty"=>$NewScrap,
                    "UserID2"=>$LogIn,
                    "Lupdate"=>date('Y-m-d H:i:s')
                );
                $this->db->where('production_id', $proId);
                $this->db->where('item_id', "SCRAP");
                $this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                
                // Add extra qty    
                for($i=1; $i<$count; $i++) { 
                     
                    $itemid = "item_id".$i;
                    $itemName = "item_name".$i;
                    $reqQty = "req_qty".$i;
                    $itemUnit = "unit".$i;
                  
                    if($new_record_array){  
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
                            }
                        }
                        $edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
                    }
                }
                
                // return qty 
                for($j=1; $j<$count1; $j++) {
                    $itemid = "pro_item_id".$j;
                    $itemName = "pro_item_name".$j;
                    $reqQty = "pro_req_qty".$j;
                    $return_reqQty = "return_pro_req_qty".$j;
                    $itemUnit = "pro_unit".$j;
                
                    if($new_record_array1){
                        foreach ($production_details->items as $key => $value) {
                            if($data[$itemid] == $value["item_id"]){
                                $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
                            }
                        }
                        $edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                            
                        $this->db->where('production_id', $proId);
                        $this->db->where('item_id', $data[$itemid]);
                        $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
                                 
                    } 
                }
                // Move or Update Row Material to History table
                $RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
                $RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
                $IssueDetails = $this->production_model->GetIssueDetails($proId);
                
                $i = 1;
                foreach ($RMItemDetails as $key => $value) {
                    $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                    foreach ($RMItemOtherDetails as $key1 => $value1) {
                        if($value["item_id"] == $value1["item_id"]){
                            $CaseQty = $value1["CaseQty"];
                            $BasicRate = $value1["BasicRate"];
                            $SaleRatee = $value1["SaleRate"];
                        }
                        
                    }
                    $Moved = 0;
                    foreach($IssueDetails as $Ikey => $Ivalue){
                        if($Ivalue["ItemID"] == $value["item_id"]){
                            $Moved++;
                        }
                    }
                    if($Moved > 0){
                        $history_update = array(
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'UserID2' =>$LogIn,
                            'Lupdate' =>date('Y-m-d H:i:s')
                        );
                        $this->db->where('OrderID', $proId);
                        $this->db->where('ItemID', $value["item_id"]);
                        $pr=$this->db->update(db_prefix() . 'history', $history_update);
                        $i++;
                    }else{
                        
                        $history_insert = array(
                            'PlantID' =>$selected_company,
                            'FY' =>$fy,
                            'cnfid' =>1,
                            'OrderID' =>$proId,
                            'TransDate' =>$value["TransDate"],
                            'BillID' =>$proId,
                            'TransID' =>$proId,
                            'TransDate2' =>date('Y-m-d H:i:s'),
                            'TType' =>"A",
                            'TType2' =>"Issue",
                            'AccountID' =>$LogIn,
                            'GodownID' =>$GodownID_RM,
                            'CaseQty' =>$CaseQty,
                            'BasicRate' =>$BasicRate,
                            'SaleRate' =>$SaleRatee,
                            'ItemID' =>$value["item_id"],
                            'OrderQty' =>$acutal_issue,
                            'BilledQty' =>$acutal_issue,
                            'Ordinalno' =>$i,
                            'UserID' =>$LogIn
                        );
                        $this->db->insert(db_prefix() . 'history', $history_insert);
                        $i++;
                    }
                }
                
                // Move Finish Goods to History table
                    
                $FGDetails = $this->production_model->GetPrdFGDetails($proId);   
                $FGHistoryDetails = $this->production_model->GetFGDetails($proId);
                if($FGHistoryDetails){
                    $history_update = array(
                        'OrderQty' =>$FGDetails->Finish_good_qty,
                        'BilledQty' =>$FGDetails->Finish_good_qty,
                    );
                    $this->db->where('OrderID', $proId);
                    $this->db->where('ItemID', $FGDetails->recipeID);
                    $pr=$this->db->update(db_prefix() . 'history', $history_update);
                }else{
                    $history_details = array(
                        'PlantID' =>$selected_company,
                        'FY' =>$fy,
                        'cnfid' =>1,
                        'OrderID' =>$proId,
                        'TransDate' =>$FGDetails->TransDate,
                        'BillID' =>$proId,
                        'TransID' =>$proId,
                        'TransDate2' =>date('Y-m-d H:i:s'),
                        'TType' =>"B",
                        'TType2' =>"Production",
                        'AccountID' =>$LogIn,
                        'GodownID' =>$GodownID_FG,
                        'ItemID' =>$FGDetails->recipeID,
                        'BasicRate' =>$FGDetails->ItemRate->BasicRate,
                        'SaleRate' =>$FGDetails->ItemRate->SaleRate,
                        'CaseQty' =>$FGDetails->case_qty,
                        'OrderQty' =>$FGDetails->Finish_good_qty,
                        'BilledQty' =>$FGDetails->Finish_good_qty,
                        'Ordinalno' =>1,
                        'UserID' =>$FGDetails->UserID
                    );
                    $this->db->insert(db_prefix() . 'history', $history_details);
                }
                if($data["finish_outcome"] == ""){
                    $actual_fg = $data["qty_product"];
                }else{
                    $actual_fg = $data["finish_outcome"];
                }
                $production_data = array(
                    "welding_remark"=>$data["welding_remark"],
                    "assembly_remark"=>$data["assembly_remark"],
                    "painting_remark"=>$data["painting_remark"],
                    "move_for_godown_remark"=>$data["move_for_godown_remark"],
                    "comment"=>$data["comments"],
                    "Finish_good_qty_new"=>$actual_fg,
                    "production_status"=>$data["status"],
                    "p_end_time"=>date('Y-m-d H:i:s'),
                    "manager_name"=>$data["operator_name"]
                );
                $productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
                );
                $this->db->where($productionChange);    
                $query = $this->db->update(db_prefix() . 'production', $production_data);
                
            }else if($data["status"]=="cancel"){
          
                    $production_details = $this->production_model->get_PRD_DetailsFromHistory($proId);
                    if($production_details){
                        
                    // Delete record from History
                        $this->db->where(db_prefix() . 'history.OrderID', $proId);
                        $this->db->delete(db_prefix() . 'history');
                    }
                    $production_data = array(
                        "comment"=>$data["comments"],
                        "production_status"=>$data["status"],
                    );
                    $productionChange = array(
                        'pro_order_id' => $proId,
                        'PlantID' => $selected_company,
                        'FY' => $fy
                    );
                    $this->db->where($productionChange);    
                    $query = $this->db->update(db_prefix() . 'production', $production_data);
            }
            set_alert('success', 'Production Order Updated Successfully..');
            redirect(admin_url('production/edit_prd/'.$proId));
        }
        $this->load->model('sale_reports_model');
        $pro_orid =$this->uri->segment(4); 
        $data['manager'] = $this->production_model->get_managername();
        $data['contractor'] = $this->production_model->get_contractorname();
        $data['production'] = $this->production_model->GetPrdDetails($pro_orid);
        $GodownID = $data['production']->GodownID;
        $PrdItem = array();
        foreach ($data['production']->items as $key=>$value) {
           array_push($PrdItem, $value["item_id"]);
        }
        //$data['ItemStocks'] = $this->production_model->GetPRDItemStock($pro_orid);
        $data['ItemStocks'] = $this->production_model->GetPRDItemStockNew($PrdItem,$GodownID);
        $data['OQtyItems'] = $this->production_model->GetPRDItemOQty($pro_orid,$GodownID);
        $data['GodownData'] = $this->production_model->GetGodownData();
        $data['ReceipeDetails'] = $this->production_model->get_receipeDetails($data['production']->recipeID);
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $data['title'] = _l('Production Order');
         /*echo "<pre>";
         print_r($data['production']);
         die;*/
        $this->load->view('admin/production/edit_prd', $data);
    }
    
    public function load_data_for_production()
    {
        $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'status_list'  => $this->input->post('status_list')
          );
        $data = $this->production_model->load_data_for_production($data);
        if(count($data) >0){
          $minutes = 0;
            $i = 1; 
            foreach($data as $value){
                $url = '"'.admin_url('production/edit_prd/' . $value['pro_order_id']).'"';
                $html1.= '<tr onclick=location.href='.$url.'>'; 
                $html1.= '<td>'.$value['pro_order_id'].'</td>';
             
                $html1.= '<td align="left">'. _d(substr($value["TransDate"],0,10)).'</td>';
                $html1.= '<td>'.strtoupper($value['recipeID']).'</td>';
                $html1.= '<td>'.strtoupper($value['description']).'</td>';
                //$html1.= '<td align="right">'.$value['batch_qty'].'</td>';
                $html1.= '<td align="right">'.$value['Finish_good_qty'].'</td>';
                $html1.= '<td align="right">'.$value['Finish_good_qty_new'].'</td>';
                $diff = $value['Finish_good_qty'] - $value['Finish_good_qty_new'];
                $html1.= '<td align="right">'.number_format($diff,2,'.','').'</td>';
                /*$html1.= '<td align="right">'.$value['required_time'].'</td>';
                    $dateTimeObject1 = date_create($value['p_start_time']); 
                    $dateTimeObject2 = date_create($value['p_end_time']); 
                    $difference = date_diff($dateTimeObject1, $dateTimeObject2);
                    $minutes = $difference->days * 24 * 60;
                    $minutes += $difference->h * 60;
                    $minutes += $difference->i;
                $html1.= '<td style="text-align:right;">'.$minutes.'</td>';*/
        
                if($value['contractor_name'] == null){
                    if($value['lastname'] == null){
                        $AccoutName = $value['firstname'];
                    }else{
                        $AccoutName = $value['firstname'].' '.$value['lastname'];
                    }
                }else{ 
                    $AccoutName = $value['conName'];
                }
      
                $html1.= '<td>'.$AccoutName.'</td>';
                $html1.= '<td>'.$value['production_status'].'</td>';
                $html1.= '</tr>';
                $i++;
            }
        }else{
            $html1.= '<span style="color:red;">No Data found...</span>';
        }
        echo json_encode($html1);
    }
    public function export_productionReport()
    {
      if(!class_exists('XLSXReader_fin')){
        require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
      }
      require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
      
      if($this->input->post()){
      
      $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'status_list'  => $this->input->post('status_list')
          );
        $data = $this->production_model->load_data_for_production($data); 
        $this->load->model('sale_reports_model');    
      $selected_company_details    = $this->sale_reports_model->get_company_detail();
        
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
        
        $msg = "Production Report : ".$this->input->post('from_date')." To " .$this->input->post('to_date') ." For : ".$this->input->post('status_list');
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
        $set_col_tk["ProductionID"] =  'ProductionID';
        $set_col_tk["PRDDate"] =  'PRDDate';
        $set_col_tk["RecipeName"] =  'RecipeName';
        $set_col_tk["description"] =  'ItemName';
        $set_col_tk["BatchQty"] =  'BatchQty';
        $set_col_tk["FGQty"] =  'STD FGQty';
        $set_col_tk["AcctualQty"] =  'Acctual Qty';
        $set_col_tk["DiffQty"] =  'Diff. Qty';
        /*$set_col_tk["ReqTM"] =  'ReqTM';
        $set_col_tk["PRDTM"] =  'PRDTM';*/
        $set_col_tk["Man/Con Name"] =  'Man/Con Name';
        $set_col_tk["Status"] =  'Status';
        $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
            
      
        foreach ($data as $k => $value) 
        {
            $list_add = [];
            $list_add[] = $value["pro_order_id"];
            $date = _d(substr($value["TransDate"],0,10));
            $list_add[] = $date;
            $list_add[] = $value["recipeID"];
            $list_add[] = $value["description"];
            $list_add[] = $value["batch_qty"];
            $list_add[] = $value["Finish_good_qty"];
            $list_add[] = $value["Finish_good_qty_new"];
            $diff = $value["Finish_good_qty"] - $value["Finish_good_qty_new"];
            $list_add[] = number_format($diff,2,'.','');
            //$list_add[] = $value["required_time"];
    
                /*$dateTimeObject1 = date_create($value['p_start_time']); 
                $dateTimeObject2 = date_create($value['p_end_time']); 
                $difference = date_diff($dateTimeObject1, $dateTimeObject2);
                $minutes = $difference->days * 24 * 60;
                $minutes += $difference->h * 60;
                $minutes += $difference->i;*/
            //$list_add[] = $minutes;
            if($value["contractor_name"] == null){
                if($value["lastname"] == null){
                    $AccoutName = $value["firstname"];
                }else{
                    $AccoutName = $value["firstname"].' '.$value["lastname"];
                }
            }else{ 
                $AccoutName = $value["conName"];
            }
            $list_add[] = $AccoutName;
            $list_add[] = $value["production_status"];
          
            $writer->writeSheetRow('Sheet1', $list_add);
        }
      
        
        $files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        foreach($files as $file){
          if(is_file($file)) {
            unlink($file); 
          }
        }
        $filename = 'Production list.xlsx';
        $writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        echo json_encode([
          'site_url'          => site_url(),
          'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        ]);
        die;
      }
    }
     
    public function view_production_list(){
        if (!has_permission_new('production_list', '', 'view')) {
            ajax_access_denied();
        }
        $this->load->model('production_model');
        $data['title'] = "Production List";
        $this->load->model('accounts_master_model');
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $this->load->view('admin/production/production_list', $data);
     }
    public function production_order_report(){
        if (!has_permission_new('production_order_report', '', 'view')) {
            ajax_access_denied();
        }
        $this->load->model('production_model');
   
         //$data['items_main_groups'] = $this->production_model->get_sub_groups();
        $data['title'] = "Production Order Wise Report";
        $this->load->model('accounts_master_model');
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
     
        $data['PrdList'] = $this->production_model->PRDList();
        $this->load->view('admin/production/production_order_wise_report', $data);
    }
    
    /* Get Production Details by ItemID / ajax */
    public function GetPRDDetailByID()
    {
        $PRDID = $this->input->post('PRDID');
        $itemPRDDetails  = $this->production_model->getPRDDetailsByID($PRDID);
        echo json_encode($itemPRDDetails);
    }
     public function load_table_production_report(){
         $this->load->model('accounts_master_model');
         $company_data = $this->accounts_master_model->get_company_detail();
         $Pro_report = $this->production_model->pro_order_report($this->input->post());
       
         $Pro_details = $this->production_model->pro_order_report_details($this->input->post());
         $BOM_details = $this->production_model->get_bom_details($Pro_report['BOMID']);
         $Sum_by_unit = $this->production_model->get_unit_sum($this->input->post());
         //print_r($BOM_details);die;
         $html =''; 
         $html .='<span>Status of PO: <b>'.$Pro_report['production_status'].'</b></span>'; 
         
            $html .= '<table class="table-striped table-bordered production_table" id="production_table" width="100%">';
            $html .= '<thead style="font-size:11px;">';
             $html .= '<tr style="display:none;">';
             $html .= '<th colspan="9"><b class="co_name">'.$company_data->company_name.'</b></th>';
             $html.= '</tr>';
             $html .= '<tr style="display:none;">';
             $html .= '<th colspan="9"><b class="co_add">'.$company_data->address.'</b></th>';
             $html.= '</tr>';
              $html .= '<tr style="display:none;">';
            $html .= '<th colspan="9"><b >';
            $html .= 'Report : '.$this->input->post('pro_order_id'); 
            $html.= '</b> </th>';
            $html .= '<th colspan="9"><b >';
            $html .= 'Status of PO : '.$Pro_report['production_status']; 
      
            $html.= '</b> </th>';
            $html.= '</tr>';
         
             $html.= '<tr>';
             $html.= '<th align="left">ItemName</th>';
             $html.= '<th align="left">Date</th>';
             $html.= '<th align="left">Output as per BOM</th>';
             $html.= '<th align="left">Actual Output</th>';
             $html.= '<th align="left">Unit</th>';
             $html.= '<th align="left">Diffrence % (if Negative its loss)</th>';
             $html.= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $total = 0;
            $prec =0;
            $prec_fg_total = 0;
       
            $html.= '<tr>';
               
             $html.= '<td align="left">'.$Pro_report['description'].'</td>';
             $html.= '<td align="left">'._d(substr($Pro_report['TransDate'],0,10)).'</td>';
             
             $html.= '<td align="right">'.$Pro_report['Finish_good_qty'].'</td>';
             $html.= '<td align="right">'.$Pro_report['Finish_good_qty_new'].'</td>';
             $total = $Pro_report['Finish_good_qty_new']-$Pro_report['Finish_good_qty'];
             $html.= '<td align="right">'.$Pro_report['finish_good_unit'].'</td>';
             if($total > 0){
               $prec =  ($total*100)/$Pro_report['Finish_good_qty'];
             }else{
                $total = $total*-1;
                 $prec =  ($total*100)/$Pro_report['Finish_good_qty'];
                 $prec = $prec*-1;
             }
             if($Pro_report['Finish_good_qty_new'] == '' || $Pro_report['Finish_good_qty_new'] == 0){
                 
                 $prec = '';
                 $prec_fg_total = 0;
             }else{
                   $prec_fg_total =  ($Pro_report['Finish_good_qty_new']*100)/$Pro_report['Finish_good_qty'];
             }
             $html.= '<td align="right">'.number_format($prec,2).'</td>';
         
        
            
              
            $html.= '</tr>';
       
        $html .= '</tbody>';
        $html .= '<table>';
        
        
        $html1 =''; 
            $html1 .= '<table class="table-striped table-bordered row_material_table" id="row_material_table" width="100%">';
            $html1 .= '<thead style="font-size:11px;">';
             $html1 .= '<tr style="display:none;">';
             $html1 .= '<th colspan="10"><b class="co_name">'.$company_data->company_name.'</b></th>';
             $html1.= '</tr>';
             $html1 .= '<tr style="display:none;">';
             $html1 .= '<th colspan="10"><b class="co_add">'.$company_data->address.'</b></th>';
             $html1.= '</tr>';
             
              $html1 .= '<tr style="display:none;">';
            $html1 .= '<th colspan="10"><b >';
            $html1 .= '<span class="report_for" style="font-size:10px;">Report : '.$this->input->post('pro_order_id').',Status of PO : '.$Pro_report['production_status'].'</span>'; 
            $html1.= '</b> </th>';
            $html1 .= '<th colspan="10"><b >';
            $html1 .= 'Status of PO : '.$Pro_report['production_status']; 
      
            $html1.= '</b> </th>';
            $html1.= '</tr>';
         
             $html1.= '<tr>';
             $html1.= '<th align="center">SrNo</th>';
             $html1.= '<th align="left">ItemName </th>';
             $html1.= '<th align="left">RM Qty. as per BOM</th>';
             $html1.= '<th align="left">Actual RM Qty.</th>';
             $html1.= '<th align="left">Unit</th>';
             $html1.= '<th align="left">Diff. in Qty.</th>';
             $html1.= '</tr>';
            $html1 .= '</thead>';
            $html1 .= '<tbody>';
            $actual_q = 0;
            $diffrence_q = 0;
            $total_actual = 0;
            $Scrap_qty = 0;
        $i = 1; 
        foreach($Pro_details as $value){
            if($value['item_id'] == "SCRAP"){
                $Scrap_qty = ($value['production_req_qty'] + $value['ExtraQty']) - ($value['return_req_qty']);
            }else{
                $html1.= '<tr>';
                $html1.= '<td align="center">'.$i.'</td>';
                $html1.= '<td align="left">'.$value['item_name'].'</td>';
                $html1.= '<td align="right">'.number_format($value['production_req_qty'],2).'</td>';
                $actual_q = ($value['production_req_qty'] + $value['ExtraQty']) - ($value['return_req_qty']);
                $html1.= '<td align="right">'.number_format($actual_q,2).'</td>';
                $html1.= '<td align="left">'.$value['unit'].'</td>';
                $total_actual+=$actual_q;
                $diffrence_q = $actual_q - $value['production_req_qty'];
                $html1.= '<td align="right">'.number_format($diffrence_q,2).'</td>';
                $html1.= '</tr>';
                $i++; 
            }
        }
        $html1 .= '</tbody>';
        $html1 .= '<table>';
        $html2 =''; 
         $html2 .= '<table class="table text-right" id="lower_table">';
                $html2 .= '<tbody>';
                foreach($Sum_by_unit as $key=>$val){
                    $html2 .= '<tr id="">';
                      $html2 .= '<td width="50%" id="">';
                       $html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">'.$val["unit"].'</label>';  
                      $html2 .= '</td>';
                      $ActualQty = $val["PrdQty"] + $val["ExtQty"] - $val["rtnQty"];
                      $html2 .= '<td width="50%" id=""><b>'.$ActualQty.'</b></td>';
                   $html2 .= '</tr>';
                } 
                $html2 .= '</tbody>';
             $html2 .= '</table>';
            
        $html3 =''; 
         $html3 .= '<table class="table text-right" id="cost_table">';
            $html3 .= '<tbody>';
                $html3 .= '<tr id="">';
                    $html3 .= '<td width="50%" id="">';
                    $html3 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">Labour Cost</label>';  
                    $html3 .= '</td>';
                    $html3 .= '<td width="50%" id=""><b>'.$BOM_details->conv_cost.'</b></td>';
                $html3 .= '</tr>';
                
                $html3 .= '<tr id="">';
                    $html3 .= '<td width="50%" id="">';
                    $html3 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">Electricity Cost</label>';  
                    $html3 .= '</td>';
                    $html3 .= '<td width="50%" id=""><b>'.$BOM_details->st_cost.'</b></td>';
                $html3 .= '</tr>';
                
                $html3 .= '<tr id="">';
                    $html3 .= '<td width="50%" id="">';
                    $html3 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">Machinery Cost</label>';  
                    $html3 .= '</td>';
                    $html3 .= '<td width="50%" id=""><b>'.$BOM_details->frt_cost.'</b></td>';
                $html3 .= '</tr>';
                
                $html3 .= '<tr id="">';
                    $html3 .= '<td width="50%" id="">';
                    $html3 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">Other Cost</label>';  
                    $html3 .= '</td>';
                    $html3 .= '<td width="50%" id=""><b>'.$BOM_details->mrkt_cost.'</b></td>';
                $html3 .= '</tr>';
                
                $html3 .= '<tr id="">';
                    $html3 .= '<td width="50%" id="">';
                    $html3 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">Total</label>';  
                    $html3 .= '</td>';
                    $total = $BOM_details->mrkt_cost + $BOM_details->frt_cost + $BOM_details->st_cost + $BOM_details->conv_cost;
                    $html3 .= '<td width="50%" id=""><b>'.$total.'</b></td>';
                $html3 .= '</tr>';
            $html3 .= '</tbody>';
        $html3 .= '</table>';
             
        $data = array('production_table'=>$html , 'row_material_table'=>$html1,'lower_table'=>$html2,'cost_table'=>$html3);
        // echo $html;
        echo json_encode($data);
     }
     public function export_production_report(){
          if(!class_exists('XLSXReader_fin')){
        require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
      }
      require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
      
      if($this->input->post()){
          
        $Pro_report = $this->production_model->pro_order_report($this->input->post());
        $Pro_details = $this->production_model->pro_order_report_details($this->input->post());
        $this->load->model('accounts_master_model');
        $selected_company_details = $this->accounts_master_model->get_company_detail();
        $writer = new XLSXWriter();
        $j=0;
        $company_name = array($selected_company_details->company_name);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $company_name);
        $j++;
        $address = $selected_company_details->address;
        $company_addr = array($address,);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $company_addr);
        $j++;
      
        $msg = "Production Report: ".$this->input->post('pro_order_id');
        $filter = array($msg);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $filter);
        $j++;
         
        $msg1 = "Status of PO: ".$Pro_report['production_status'];
        $filter1 = array($msg1);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $filter1);
        $j++;
      
         $list_add = [];
        $list_add[] = "";
        $list_add[] = "";
        $list_add[] = "";
        $list_add[] = "";
            
        $writer->writeSheetRow('Sheet1', $list_add);
            
            
        $set_col_tk = [];
        $set_col_tk["Item Name"] =  'ItemName';
        $set_col_tk["No of batches"] =  'No of Batches';
        $set_col_tk["Output as per Receipe"] =  'Output as per Receipe';
        $set_col_tk["Actual Output in Pc"] =  'Actual Output in Pc';
        $set_col_tk["Diffrence % (if Negative its loss)"] =  'Diffrence % (if Negative its loss)';
      
        $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
            
          $total = 0;
            $prec =0;
            $prec_fg_total =0;
    //    foreach ($Pro_report as $k => $value) {
      
          $list_add = [];
          $list_add[] = $Pro_report['description'];
          $list_add[] = $Pro_report['batch_qty'];
          $list_add[] = $Pro_report['Finish_good_qty'];
          $list_add[] = $Pro_report['Finish_good_qty_new'];
      $total = $Pro_report['Finish_good_qty_new']-$Pro_report['Finish_good_qty'];
             if($total > 0){
               $prec =  ($total*100)/$Pro_report['Finish_good_qty'];
             }else{
                $total = $total*-1;
                 $prec =  ($total*100)/$Pro_report['Finish_good_qty'];
                 $prec = $prec*-1;
             }
             if($Pro_report['Finish_good_qty_new'] == '' || $Pro_report['Finish_good_qty_new'] == 0){
                $prec = '';
                 $prec_fg_total = 0;
             }else{
                   $prec_fg_total =  ($Pro_report['Finish_good_qty_new']*100)/$Pro_report['Finish_good_qty'];
             }
       
         
          $list_add[] = number_format($prec,2);
        
          $writer->writeSheetRow('Sheet1', $list_add);
          
    //  }
      

        
      $msg1 = "Raw Material Summary :-";
        $filter1 = array($msg1);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $filter1);
        $j++;
      
        // empty row
        $list_add = [];
        $list_add[] = "";
        $list_add[] = "";
        $list_add[] = "";
        $list_add[] = "";
        $list_add[] = "";
            
        $writer->writeSheetRow('Sheet1', $list_add);
            
            
        $set_col_tk = [];
        $set_col_tk["Sl No"] =  'SrNo';
        $set_col_tk["Item"] =  'ItemName';
        $set_col_tk["RM Qty as per Receipe"] =  'RM Qty. as per Receipe';
        $set_col_tk["Actual Rm Qty"] =  'Actual RM Qty.';
        $set_col_tk["Diff in qty"] =  'Diff. in Qty.';
      
        $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
            
        $actual_q = 0;
        $diffrence_q = 0;
        $i =1;
        $total_actual = 0;
        foreach ($Pro_details as $k => $value) {
      
          $list_add = [];
          $list_add[] = $i;
          $list_add[] = $value["item_name"];
          $list_add[] = number_format($value["production_req_qty"],2);
          $actual_q = ($value['production_req_qty'] + $value['ExtraQty']) - ($value['return_req_qty']);
          $list_add[] = number_format($actual_q,2);
          
          $total_actual+=$actual_q;
          $diffrence_q = $actual_q -$value['production_req_qty'];
          $list_add[] = number_format($diffrence_q,2);
        
          $writer->writeSheetRow('Sheet1', $list_add);
          $i++;
        }
      
        $list_add = [];
        $list_add[] = "";
        $list_add[] = "";
            
            $writer->writeSheetRow('Sheet1', $list_add);
            
            
            $set_col_tk = [];
        $set_col_tk[""] =  '';
        $set_col_tk[""] =  '';
          $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
        
          $list_add = [];
          $list_add[] = "Total RM in KG";
          $list_add[] = number_format($total_actual,2);
         $writer->writeSheetRow('Sheet1', $list_add);
         
           $list_add = [];
          $list_add[] = "Total FG";
          $list_add[] = number_format($Pro_report['Finish_good_qty_new'],2).' '.$Pro_report['finish_good_unit'];
          $writer->writeSheetRow('Sheet1', $list_add);
          
          $list_add = [];
          $list_add[] = "Output %";
          $list_add[] = number_format($prec_fg_total,2);
          $writer->writeSheetRow('Sheet1', $list_add);
          
          $list_add = [];
          $list_add[] = "Oil Comsumption %";
          $list_add[] = "";
          $writer->writeSheetRow('Sheet1', $list_add);
          
          $list_add = [];
          $list_add[] = "Masala Consumption %";
          $list_add[] = "";
          $writer->writeSheetRow('Sheet1', $list_add);
      
      
        $files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        foreach($files as $file){
          if(is_file($file)) {
            unlink($file); 
          }
        }
        $filename = 'production_order_wise_Report.xlsx';
        $writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        echo json_encode([
          'site_url'          => site_url(),
          'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        ]);
        die;
      }
        
    }
    public function product_list(){
         $postData = $this->input->post();
    
        // Get data
        $data = $this->production_model->getproduct_list($postData);
    
        echo json_encode($data);
    }
    public function production_cost_report()
    {
        if (!has_permission_new('cost_report', '', 'view')) {
        ajax_access_denied();
        }
        $this->load->model('production_model');
        $data['title'] = "Production Cost Report Item Wise ";
        $this->load->model('accounts_master_model');
        $data['company_detail'] = $this->accounts_master_model->get_company_detail();
        $data['Pro_order_list'] = $this->production_model->pro_order_list();
        $this->load->view('admin/production/production_cost_report', $data);
     }
     public function load_table_production_cost_report()
     {
        $Pro_report = $this->production_model->pro_cost_report($this->input->post());
        $PRD_Costing = $this->production_model->prd_cost_calculate($this->input->post());
        $Pro_details = $this->production_model->pro_cost_report_details($this->input->post());
        //print_r($Pro_details);die;
        $html =''; 
        $html .= '<table class="table-striped table-bordered production_table" id="production_table" width="100%">';
        $html .= '<thead style="font-size:11px;">';
        $html.= '<tr>';
        $html.= '<th align="center">Item Name</th>';
        $html.= '<th align="center">No of batches </th>';
        $html.= '<th>Output as per BOM</th>';
        $html.= '<th align="center">Actual Output in</th>';
        $html.= '<th align="center">FG Unit</th>';
        $html.= '<th align="center">Diffrence % (if nigative its loss)</th>';
        $html.= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $total = 0;
        $prec =0;
        $prec_fg_total = 0;
        $ffQty = 0;
        $SaleRate_FG = 0;
        $basicRate_FG = 0;
        $GSTPer_FG = 0;
        $Labour_Cost = 0;
        $Electricity_Cost = 0;
        $Machinery_Cost	 = 0;
        $Other_Cost = 0;
        foreach($Pro_report as $value)
        {
            $html.= '<tr>';
            $html.= '<td align="left">'.$value['description'].'</td>';
            $html.= '<td align="right">'.$value['batch_qty'].'</td>';
            $html.= '<td align="right">'.number_format((float)$value['Finish_good_qty'], 2, '.', '').'</td>';
            $html.= '<td align="right">'.round($value['Finish_good_qty_new'], 2).'</td>';
            $html.= '<td align="right">'.$value['FG_Unit'].'</td>';
            $ffQty +=$value['Finish_good_qty_new'];
            $SaleRate_FG +=$value['SaleRate'];
            $basicRate_FG+=$value['BasicRate'];
            $GSTPer_FG+=$value['gst'];
            $Labour_Cost += $value['conv_cost'];
            
            $total = $value['Finish_good_qty_new']-$value['Finish_good_qty'];
            if($total > 0){
                $prec =  ($total*100)/$value['Finish_good_qty'];
            }else{
                $total = $total*-1;
                $prec =  ($total*100)/$value['Finish_good_qty'];
                $prec = $prec*-1;
            }
            if($value['Finish_good_qty_new'] == '' || $value['Finish_good_qty_new'] == 0){
                $prec = -100;
                //  $prec = '';
                $prec_fg_total = 0;
            }else{
                $prec_fg_total =  ($value['Finish_good_qty_new']*100)/$value['Finish_good_qty'];
            }
            $html.= '<td align="right">'.round($prec, 2).'</td>';
            $html.= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '<table>';
        
        
        $html1 =''; 
        $html1 .= '<table class="table-striped table-bordered row_material_table" id="row_material_table" width="100%">';
        $html1 .= '<thead style="font-size:11px;">';
        
        
        $html1.= '<tr>';
        $html1.= '<th align="center">Sl No</th>';
        $html1.= '<th align="center">Item </th>';
        $html1.= '<th align="center">RM Qty as per BOM</th>';
        $html1.= '<th align="center">Actual RM Qty</th>';
        $html1.= '<th align="center">Diff in qty</th>';
        $html1.= '<th align="center">Unit</th>';
        $html1.= '<th align="center">Rate</th>';
        $html1.= '<th align="center">Value</th>';
        $html1.= '<th align="center">GST</th>';
        $html1.= '<th align="center">Net Value</th>';
        $html1.= '</tr>';
        $html1 .= '</thead>';
        $html1 .= '<tbody>';
        $actual_q = 0;
        $diffrence_q = 0;
        $total_actual = 0;
        $value_data =0;
        $value_total =0;
        $gst_total =0;
        $netvalue_total =0;
        $prec_amount =0;
        $i = 1; 
        foreach($Pro_details as $value)
        {
            if($value['item_id'] == "SCRAP"){
                
            }else{
                $html1.= '<tr>';
                $html1.= '<td align="center">'.$i.'</td>';
                $html1.= '<td align="left">'.$value['item_name'].'</td>';
                $html1.= '<td align="right">'.number_format((float)$value['production_req_qty'], 2, '.', '').'</td>';
                $actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
                $html1.= '<td align="right">'.number_format((float)$actual_q, 2, '.', '').'</td>';
                $total_actual+=$actual_q;
                $diffrence_q = $actual_q - $value['production_req_qty'];
                $html1.= '<td align="right">'.number_format((float)$diffrence_q, 2, '.', '').'</td>';
                $html1.= '<td align="left">'.$value['RMUnit'].'</td>';
                $html1.= '<td align="right">'.number_format((float)$value['BasicRate'], 2, '.', '').'</td>';
                if($value['BasicRate'] != ''){
                    $html1.= '<td align="right">'.round($actual_q*$value['BasicRate'],2).'</td>';
                    $value_data  = $actual_q*$value['BasicRate'];
                    $value_total+=$value_data;
                    $prec_amount =  ($value_data*$value['taxrate'])/100;
                    $gst_total+=$prec_amount;
                    $html1.= '<td align="right">'.number_format((float)$prec_amount, 2, '.', '').'</td>';
                    $netvalue_total+=$prec_amount+$value_data;
                    $html1.= '<td align="right">'.number_format((float)$prec_amount+$value_data, 2, '.', '').'</td>';
                }else{
                    $html1.= '<td align="right"></td>';
                    $html1.= '<td align="right"></td>';
                    $html1.= '<td align="right"></td>';
                }
                $html1.= '</tr>';
                $i++; 
            }
        }
        $html1 .= '</tbody>';
        $html1 .= '<tfoot>';
        
        $html1 .= '</tfoot>';
        $html1.= '<tr>';
        $html1.= '<td>Total</td>';
        $html1.= '<td></td>';
        $html1.= '<td></td>';
        $html1.= '<td></td>';
        $html1.= '<td></td>';
        $html1.= '<td></td>';
        $html1.= '<td></td>';
        $html1.= '<td align="right">'.round($value_total,2).'</td>';
        $html1.= '<td align="right">'.round($gst_total,2).'</td>';
        $html1.= '<td align="right">'.round($netvalue_total,2).'</td>';
        $html1.= '</tr>';
        $html1 .= '<table>';
        
        $FG_Cost = $ffQty*$SaleRate_FG;
        $SaleAmt_GF = $ffQty*$basicRate_FG;
        $GSTAmt_FG = ($GSTPer_FG/100)*$SaleAmt_GF;
        
        $Labour_Cost = 0;
        $Electric_Cost = 0;
        $machinery_Cost = 0;
        $other_Cost = 0;
        foreach($PRD_Costing as $key=>$val){
            $Labour_Cost += $val["conv_cost"];
            $Electric_Cost += $val["st_cost"];
            $machinery_Cost += $val["frt_cost"];
            $other_Cost += $val["mrkt_cost"];
        }
        
        $html2 =''; 
        $html2 .= '<table class="table text-right" style="width: 38%;" id="lower_table">';
        $html2 .= '<tbody>';
        $html2 .= '<tr>';
        $html2 .= '<td>Labour Cost</td>';
        $html2 .= '<td>'. number_format($Labour_Cost, 2, '.', '').'</td>';
        $html2 .= '</tr>';
        
        $html2 .= '<tr>';
        $html2 .= '<td>Electricity Cost</td>';
        $html2 .= '<td>'. number_format($Electric_Cost, 2, '.', '').'</td>';
        $html2 .= '</tr>';
        
        $html2 .= '<tr>';
        $html2 .= '<td>Machinery Cost</td>';
        $html2 .= '<td>'. number_format($machinery_Cost, 2, '.', '').'</td>';
        $html2 .= '</tr>';
        
        $html2 .= '<tr>';
        $html2 .= '<td>Other Cost</td>';
        $html2 .= '<td>'. number_format($other_Cost, 2, '.', '').'</td>';
        $html2 .= '</tr>';
        
        $html2 .= '</tbody>';
        $html2 .= '</table>';
             
        $data = array('production_table'=>$html , 'row_material_table'=>$html1,'lower_table'=>$html2);
        // $data = array('production_table'=>$html , 'row_material_table'=>$html1);
        // echo $html;
         echo json_encode($data);
     }
      public function export_production_cost_report(){
          if(!class_exists('XLSXReader_fin')){
        require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
      }
      require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
      
      if($this->input->post()){
      
            $Pro_report = $this->production_model->pro_cost_report($this->input->post());
       
            $Pro_details = $this->production_model->pro_cost_report_details($this->input->post());
            $this->load->model('accounts_master_model');
            $selected_company_details = $this->accounts_master_model->get_company_detail();
        $writer = new XLSXWriter();
        $j=0;
        $company_name = array($selected_company_details->company_name);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $company_name);
        $j++;
        $address = $selected_company_details->address;
        $company_addr = array($address,);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $company_addr);
        $j++;
          $date = 'Production Cost Report Date form: '.$this->input->post('from_date').' date to: '.$this->input->post('to_date');
        $date_from_to = array($date,);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $date_from_to);
        $j++;
        if($this->input->post('product_name') != ''){
            $product = 'Product: '.$this->input->post('product_name');
            $product_name = array($product,);
            $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
            $writer->writeSheetRow('Sheet1', $product_name);
            $j++;
        }
          
      
          $list_add = [];
        $list_add[] = "";
        $list_add[] = "";
        $list_add[] = "";
          $list_add[] = "";
          $list_add[] = "";
            
            $writer->writeSheetRow('Sheet1', $list_add);
            
            
            $set_col_tk = [];
        $set_col_tk["Item Name"] =  'Item Name';
        $set_col_tk["No of batches"] =  'No of batches';
        $set_col_tk["Output as per Receipe"] =  'Output as per Receipe';
        $set_col_tk["Actual Output in Pc"] =  'Actual Output in Pc';
        $set_col_tk["Diffrence % (if nigative its loss)"] =  'Diffrence % (if nigative its loss)';
      
        $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
            
          $total = 0;
            $prec =0;
            $prec_fg_total =0;
            $ffQty = 0;
            $SaleRate_FG = 0;
            $basicRate_FG = 0;
            $GSTPer_FG = 0;
            $conv_costPer = 0;
            $st_costPer = 0;
            $frt_costPer = 0;
            $mrkt_costPer = 0;
            $dmg_costPer = 0;
        foreach ($Pro_report as $k => $value) {
      
          $list_add = [];
          $list_add[] = $value['description'];
          $list_add[] = $value['batch_qty'];
          $list_add[] = round($value['Finish_good_qty'],2);
          $list_add[] = round($value['Finish_good_qty_new'],2);
    
             $ffQty+=$value['Finish_good_qty_new'];
             $SaleRate_FG+=$value['SaleRate'];
              $basicRate_FG+=$value['BasicRate'];
             $GSTPer_FG+=$value['gst'];
             
             $conv_costPer= $value['conv_cost'];
             $st_costPer= $value['st_cost'];
             $frt_costPer= $value['frt_cost'];
             $mrkt_costPer= $value['mrkt_cost'];
             $dmg_costPer= $value['dmg_cost'];
             
           $total = $value['Finish_good_qty_new']-$value['Finish_good_qty'];
             if($total > 0){
               $prec =  ($total*100)/$value['Finish_good_qty'];
             }else{
                $total = $total*-1;
                 $prec =  ($total*100)/$value['Finish_good_qty'];
                 $prec = $prec*-1;
             }
             if($value['Finish_good_qty_new'] == '' || $value['Finish_good_qty_new'] == 0){
                $prec = -100;
                 $prec_fg_total = 0;
             }else{
                   $prec_fg_total =  ($value['Finish_good_qty_new']*100)/$value['Finish_good_qty'];
             }
       
         
          $list_add[] = round($prec,2);
        
          $writer->writeSheetRow('Sheet1', $list_add);
          
      }
      

        
      $msg1 = "Raw Material Summery :-";
        $filter1 = array($msg1);
        $writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
        $writer->writeSheetRow('Sheet1', $filter1);
        $j++;
      
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
        $set_col_tk["Sl No"] =  'Sl No';
        $set_col_tk["Item"] =  'Item';
        $set_col_tk["RM Qty as per Receipe"] =  'RM Qty as per Receipe';
        $set_col_tk["Actual Rm Qty"] =  'Actual RM Qty';
        $set_col_tk["Diff in qty"] =  'Diff in qty';
        $set_col_tk["Rate"] =  'Rate';
        $set_col_tk["Value"] =  'Value';
        $set_col_tk["GST"] =  'GST';
        $set_col_tk["Net Value"] =  'Net Value';
      
        $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
            
        $actual_q = 0;
            $diffrence_q = 0;
            $i =1;
            $total_actual = 0;
            $value_data =0;
            $value_total =0;
            $gst_total =0;
            $netvalue_total =0;
            $prec_amount =0;
        foreach ($Pro_details as $k => $value) {
      
          $list_add = [];
          $list_add[] = $i;
        
          $list_add[] = $value["item_name"];
          $list_add[] = round($value["production_req_qty"],2);
          $actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
          $list_add[] = round($actual_q,2);
          
            $total_actual+=$actual_q;
            $diffrence_q = $actual_q - $value['production_req_qty'];
          $list_add[] = round($diffrence_q,2);
          $list_add[] = round($value['BasicRate'],2);
        
             if($value['BasicRate'] != ''){
                $list_add[] = round($actual_q*$value['BasicRate'],2);
                $value_data  = $actual_q*$value['BasicRate'];
                $value_total+=$value_data;
                $prec_amount =  ($value_data*$value['taxrate'])/100;
                $gst_total+=$prec_amount;
                $list_add[] = round($prec_amount,2);
                $netvalue_total+=$prec_amount+$value_data;
                $list_add[] = round(($prec_amount+$value_data),2);
             }else{
                $list_add[] = '';
                $value_data  = $actual_q;
                $list_add[] = '';
                $list_add[] = '';
             }
          $writer->writeSheetRow('Sheet1', $list_add);
          $i++;
        }
      
            $list_add = [];
          $list_add[] = "Total";
          $list_add[] = "";
          $list_add[] = "";
          $list_add[] = "";
          $list_add[] = "";
          $list_add[] = "";
          $list_add[] = round($value_total,2);
          $list_add[] = round($gst_total,2);
          $list_add[] = round($netvalue_total,2);
         $writer->writeSheetRow('Sheet1', $list_add);
    
        $list_add = [];
        $list_add[] = "";
        $list_add[] = "";
            
            $writer->writeSheetRow('Sheet1', $list_add);
            
            
            $set_col_tk = [];
        $set_col_tk[""] =  '';
        $set_col_tk[""] =  '';
          $writer_header = $set_col_tk;
        $writer->writeSheetRow('Sheet1', $writer_header);
        
        $FG_Cost = $ffQty*$SaleRate_FG;
          $SaleAmt_GF = $ffQty*$basicRate_FG;
            $GSTAmt_FG = ($GSTPer_FG/100)*$SaleAmt_GF;
            $convertion_Cost = ($conv_costPer/100)*$FG_Cost;
            $sale_team_Cost = ($st_costPer/100)*$FG_Cost;
            $Freight_Cost = ($frt_costPer/100)*$FG_Cost;
            $Marketing_Cost = ($mrkt_costPer/100)*$FG_Cost;
            $Damage_Cost  = ($dmg_costPer/100)*$FG_Cost;
            
          $list_add = [];
          $list_add[] = "Conversion Cost";
          $list_add[] = round($convertion_Cost,2);
         $writer->writeSheetRow('Sheet1', $list_add);
         
          
                  
            $list_add = [];
          $list_add[] = "Sales team Cost";
          $list_add[] = round($sale_team_Cost,2);
          $writer->writeSheetRow('Sheet1', $list_add);
          
          $list_add = [];
          $list_add[] = "Freight";
          $list_add[] = round($Freight_Cost,2);
          $writer->writeSheetRow('Sheet1', $list_add);
          
          $list_add = [];
          $list_add[] = "Marketing Cost";
          $list_add[] = round($Marketing_Cost,2);
          $writer->writeSheetRow('Sheet1', $list_add);
          
          $list_add = [];
          $list_add[] = "Damage ";
          $list_add[] = round($Damage_Cost,2);
          $writer->writeSheetRow('Sheet1', $list_add);
           $list_add = [];
          $list_add[] = "GST Payable ";
          $list_add[] = round(($GSTAmt_FG-$gst_total),2);
          $writer->writeSheetRow('Sheet1', $list_add);
      
      
        $files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
        foreach($files as $file){
          if(is_file($file)) {
            unlink($file); 
          }
        }
        $filename = 'production_cost_Report.xlsx';
        $writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
        echo json_encode([
          'site_url'          => site_url(),
          'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
        ]);
        die;
      }
        
    }
}