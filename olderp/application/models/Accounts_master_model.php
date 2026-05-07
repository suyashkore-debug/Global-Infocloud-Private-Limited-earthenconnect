<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accounts_master_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        $SubActGroupID = array("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004","50003008","50003009");
        $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.StartDate,'.db_prefix() . 'clients.Blockyn,'.db_prefix() . 'clients.SubActGroupID,'.db_prefix() . 'accountbalances.BAL1,'.db_prefix() . 'contacts.BalancesYN');
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'contacts.PlantID = ' . db_prefix() . 'clients.PlantID');
        $this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND ' . db_prefix() . 'accountbalances.FY = "'.$FY.'"','LEFT');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', $SubActGroupID);
        $this->db->from(db_prefix() . 'clients');
        if ($id) {
            $this->db->where(db_prefix() . 'clients.AccountID', $id);

            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
    
    public function getNEW($id = '')
    {                          
        $SubActGroupID = array("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004","50003008","50003009");
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.PlantID,'.db_prefix() . 'clients.StartDate,'.db_prefix() . 'clients.Blockyn,'.db_prefix() . 'clients.SubActGroupID,'.db_prefix() . 'accountbalances.BAL1,'.db_prefix() . 'contacts.BalancesYN');
        $this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'contacts.PlantID = ' . db_prefix() . 'clients.PlantID','LEFT');
        $this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND ' . db_prefix() . 'accountbalances.FY = "'.$FY.'"','LEFT');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', $SubActGroupID);
        $this->db->from(db_prefix() . 'clients');
        if ($id) {
            $this->db->where(db_prefix() . 'clients.AccountID', $id);
            $Data = $this->db->get()->row();
            if($Data){
               $Data->AccountType = 'Client';
                $Data->cityList = $cityList;
           }else{
               $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
               $this->db->select(db_prefix() . 'staff.*,');
               $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
               $this->db->where(db_prefix() . 'staff.AccountID', $id);
               $Data = $this->db->get(db_prefix() . 'staff')->row();
               if($Data){
                   $Data->AccountType = 'Staff';
               }else{
                   $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.PlantID,'.db_prefix() . 'clients.StartDate,'.db_prefix() . 'clients.Blockyn,'.db_prefix() . 'clients.SubActGroupID,'.db_prefix() . 'accountbalances.BAL1,'.db_prefix() . 'contacts.BalancesYN');
                    $this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'contacts.PlantID = ' . db_prefix() . 'clients.PlantID','LEFT');
                    $this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND ' . db_prefix() . 'accountbalances.FY = "'.$FY.'"','LEFT');
                    //$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
                    //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', $SubActGroupID);
                    $this->db->from(db_prefix() . 'clients');
                    $this->db->where(db_prefix() . 'clients.AccountID', $id);
                    $Data = $this->db->get()->row();
                    if($Data){
                        $Data->AccountType = 'Client';   
                    }
               }
           }
           return $Data;
        }
    }
    
    // Update Exiting ItemID
    public function UpdateAccountID($dataClient,$AccountID,$BAL1)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $UserID = $this->session->userdata('username');
        $UPDATE = 0; 
        $this->db->where('AccountID', $AccountID);
        $this->db->where('PlantID', $selected_company);
        $this->db->update(db_prefix() . 'clients', $dataClient);
        //$UPDATE = $UPDATE + $this->db->affected_rows();
        if($this->db->affected_rows() > 0){
            $UPDATE++;
        }
        
        /*$this->db->where('AccountID', $AccountID);
        $this->db->where('PlantID', $selected_company);
        $this->db->update(db_prefix() . 'contacts', $dataContacts);
        $UPDATE = $UPDATE + $this->db->affected_rows();*/
        $staff_user_id = $this->session->userdata('staff_user_id');
            $checkBalRecord = $this->ChkBalRecord($AccountID,$selected_company,$FY);
                if(empty($checkBalRecord)){
                    //Balance Record Create
                    $Bal_data = array(
                        "BAL1"=>$BAL1,
                        "PlantID"=>$selected_company,
                        "FY"=>$FY,
                        "AccountID"=>$AccountID
                    );
                    $this->db->insert(db_prefix() . 'accountbalances', $Bal_data);
                    $UPDATE++;
                }else{
                    if($staff_user_id == "3"){
                        //Balance Record Update
                        $Bal_data = array(
                            "BAL1"=>$BAL1,
                            "UserID2"=>$UserID,
                            "Lupdate"=>date('Y-m-d H:i:s')
                        );
                        $this->db->where('AccountID', $AccountID);
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $FY);
                        $this->db->update(db_prefix() . 'accountbalances', $Bal_data);
                        if($this->db->affected_rows() > 0){
                            $UPDATE++;
                        }
                    }
            }
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
    // Add New ItemID
    public function SaveAccountID($data,$BAL1,$AccountID)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $UserID = $this->session->userdata('username');
        
        $data['PlantID'] = $selected_company;
        $data['addedfrom'] = $UserID;
        $data['datecreated'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'clients', $data);
        $INSERT = $this->db->affected_rows();
            if($INSERT > 0){
                $dataContacts = array();
                $dataContacts['PlantID'] = $selected_company;
                $dataContacts['AccountID'] = $AccountID;
                $this->db->insert(db_prefix() . 'contacts', $dataContacts);
                /*$staff_user_id = $this->session->userdata('staff_user_id');
                if($staff_user_id == "3"){*/
                    $Bal_data = array(
                            "BAL1"=>$BAL1,
                            "AccountID"=>$AccountID,
                            "PlantID"=>$selected_company,
                            "FY"=>$FY
                        );
                        $this->db->insert(db_prefix() . 'accountbalances', $Bal_data);
                //}
            }    
        
        if($INSERT > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function ChkBalRecord($AccountID,$PlantID,$fy)
    {
        $this->db->select(db_prefix() . 'accountbalances.*');
        $this->db->where('AccountID', $AccountID);
        $this->db->where('PlantID', $PlantID);
        $this->db->where('FY', $fy);
        $this->db->from(db_prefix() . 'accountbalances');
        $data =  $this->db->get()->row();
        return $data;
    }
    
    public function get_accoun_main_group(){
      
       $acc_main_group = $this->db->get(db_prefix() . 'accountgroups')->result_array();
       return $acc_main_group;
    }
    
    public function get_account_subgroup(){
      
       $ss = 'SELECT *
        FROM tblaccountgroupssub WHERE SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")';

                $result_data = $this->db->query($ss)->result_array();
                return $result_data;
    }
    
    public function get_actgroup_movement(){
    
       $account_group_mov = $this->db->get(db_prefix() . 'actgroupmovement')->result_array();
       return $account_group_mov;
    }
    
    public function get_act_maingroup(){
    
        
        $this->db->order_by(db_prefix() . 'accountgroups.ActGroupName','ASC');
       $account_maingroup = $this->db->get(db_prefix() . 'accountgroups')->result_array();
       return $account_maingroup;
    }
    
    function get_accounts_group($postData){

    $response = array();
    
    $where_ = '';
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'accountgroups.*');
       $where_ .= '(ActGroupName LIKE "%' . $q . '%" ESCAPE \'!\' OR CtrlActGroupID LIKE "%' . $q . '%" ESCAPE \'!\' OR ActGroupID LIKE "%' . $q. '%" ESCAPE \'!\')';
       $this->db->where($where_);
       
       $records = $this->db->get(db_prefix() . 'accountgroups')->result();
    //   echo $this->db->last_query();die;

       foreach($records as $row ){
          $response[] = array("label"=>$row->ActGroupName,"value"=>$row->ActGroupID);
       }

     }

     return $response;
  }
    function get_user_list($postData){

        $response = array();
        $where_ = '';
        $selected_company = $this->session->userdata('root_company');
         if(isset($postData['search']) ){
           
           $q = $postData['search'];
           $this->db->select(db_prefix() . 'staff.*');
           $where_ .= '(AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR firstname LIKE "%' . $q . '%" ESCAPE \'!\' OR 	lastname LIKE "%' . $q. '%" ESCAPE \'!\')';
           $this->db->where($where_);
           $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
           $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
           $records = $this->db->get(db_prefix() . 'staff')->result();
        
           foreach($records as $row ){
               $full_name = $row->firstname." ".$row->lastname;
              $response[] = array("label"=>$full_name,"value"=>$row->AccountID);
           }
    
         }
    
         return $response;
    }
    
  function get_accounts_subgroup($postData){

    $response = array();
    
    $where_ = '';
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'accountgroupssub.*');
       $where_ .= '(SubActGroupID LIKE "%' . $q . '%" ESCAPE \'!\' OR SubActGroupName LIKE "%' . $q . '%" ESCAPE \'!\')';
       $this->db->where($where_);
       
       $records = $this->db->get(db_prefix() . 'accountgroupssub')->result();
    //   echo $this->db->last_query();die;

       foreach($records as $row ){
          $response[] = array("label"=>$row->SubActGroupName,"value"=>$row->SubActGroupID);
       }

     }

     return $response;
  }
  
  public function get_act_groupid()
    {
        $this->db->select(db_prefix() . 'accountgroupssub.SubActGroupID');
        $this->db->where(db_prefix() . 'accountgroupssub.ActGroupID', "50000");
        $this->db->order_by(db_prefix() . 'accountgroupssub.SubActGroupID', 'DESC');
        $row = $this->db->get(db_prefix() . 'accountgroupssub')->row();
        return $row;
        
    }
    
    // Add New AccountSubGroup
    public function SaveSubGroup($data)
    {
        $this->db->insert(db_prefix() . 'accountgroupssub', $data);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            return true;    
        }else{
            return false;
        }
    }
    
     // Update Exiting Account SubGroup
    public function UpdateSubGroup($data,$SubGroupID)
    {
        $this->db->where('SubActGroupID', $SubGroupID);
        $this->db->update(db_prefix() . 'accountgroupssub', $data);
        $UPDATE = $this->db->affected_rows();        
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
     // Update Exiting Account Group
    public function UpdateGroup($data,$ActGroupID)
    {
        $this->db->where('ActGroupID', $ActGroupID);
        $this->db->update(db_prefix() . 'accountgroups', $data);
        $UPDATE = $this->db->affected_rows();        
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    
    // Add New AccountGroup
    public function SaveGroup($data)
    {
        $this->db->insert(db_prefix() . 'accountgroups', $data);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            return true;    
        }else{
            return false;
        }
    }
    
    public function get_login_user_list()
    {   
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'staff.*');
        $this->db->where(db_prefix() . 'staff.login_access', "Yes");
        $this->db->where(db_prefix() . 'staff.active', 1);
        $this->db->where(db_prefix() . 'staff.admin', 0);
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
        $this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');
        $result = $this->db->get(db_prefix() . 'staff')->result_array();
        return $result;
             /*$last_row=$this->db->select('SubActGroupID')->$this->db->where(db_prefix() . 'accountgroupssub.ActGroupID', "50000")->order_by('SubActGroupID',"desc")->limit(1)->get('tblaccountgroupssub')->row();
            return $last_row + 1;*/
        
    }
    public function get_selected_record($postData)
     {  
        $selected_company = $this->session->userdata('root_company');
        
        $sql ='SELECT '.db_prefix().'nsaccountmaster.* FROM '.db_prefix().'nsaccountmaster WHERE UserID = "'.$postData['userid'].'" AND PlantID = '.$selected_company;
        
        $result = $this->db->query($sql)->result_array();
        return $result;
        
    }
    
    public function get_no_act_list($user_id)
     {  
        $selected_company = $this->session->userdata('root_company');
        
        $sql ='SELECT '.db_prefix().'clients.* FROM '.db_prefix().'clients WHERE no_show = "1" AND PlantID = '.$selected_company;
        
        $result = $this->db->query($sql)->result_array();
        return $result;
        
    }
    
    public function get_no_act_list_for_staff($user_id)
     {  
        $selected_company = $this->session->userdata('root_company');
        
        /*$sql ='SELECT '.db_prefix().'staff.* FROM '.db_prefix().'staff WHERE no_show = "1"';
        
        $result = $this->db->query($sql)->result_array();
        return $result;*/
        
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'staff.*');
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
        $this->db->where('no_show',"1");
        $this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');
        $result = $this->db->get(db_prefix() . 'staff')->result_array();
        return $result;
        
    }
  
    public function get_account_group_details($AccountID)
     {  
        
        $sql ='SELECT '.db_prefix().'accountgroups.*
        FROM '.db_prefix().'accountgroups WHERE ActGroupID = '.$AccountID;
        
        $result = $this->db->query($sql)->row();
        return $result;
        
    }
    
    public function get_staff_details($userID)
     {  
        $selected_company = $this->session->userdata('root_company');
            $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
            //$regExp = 'REGEXP ".*;s:[0-9]+:'2'.*";
        /*    $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
            
        $sql ='SELECT '.db_prefix().'staff.*
        FROM '.db_prefix().'staff WHERE AccountID = "'.$userID.'"';
        
        $result = $this->db->query($sql)->row();*/
        
        $this->db->select(db_prefix() . 'staff.*');
        $this->db->where(db_prefix() . 'staff.staff_comp REGEXP',$regExp);
        $this->db->where(db_prefix() . 'staff.AccountID',$userID);
        $result = $this->db->get(db_prefix() . 'staff')->row(); 
        return $result;
        
    }
    
    public function get_account_subgroup_details($subgroup_code)
     {  
        
        $sql ='SELECT '.db_prefix().'accountgroupssub.*
        FROM '.db_prefix().'accountgroupssub WHERE SubActGroupID = '.$subgroup_code;
        
        $result = $this->db->query($sql)->row();
        return $result;
        
    }
    
    
    
    public function get_account_max_subgroupId($maingroup_id)
     {  
        
        $sql ='SELECT '.db_prefix().'accountgroupssub.SubActGroupID
        FROM '.db_prefix().'accountgroupssub WHERE ActGroupID = '.$maingroup_id .' ORDER BY SubActGroupID DESC LIMIT 1';
        
        $result = $this->db->query($sql)->row();
        
        return $result->SubActGroupID+1;
        
    }
    
    // add Account Group record
    
    public function add_account_group($data)
    {
        
        $this->db->insert(db_prefix() . 'accountgroups', $data);
        
        if ($this->db->affected_rows() > 0) {
            
            return true;
        }

        return false;
    }
    
    // add Account Group record
    public function add_account_subgroup($data)
    {
        
        $this->db->insert(db_prefix() . 'accountgroupssub', $data);
        
        if ($this->db->affected_rows() > 0) {
            
            return true;
        }

        return false;
    }
    
    // add Account Group record
    public function add_user($data)
    {
        
        $this->db->insert(db_prefix() . 'staff', $data);
        if ($this->db->affected_rows() > 0) {
            $staffid = $this->db->insert_id();
            return $staffid;
        }
        
    }
    
    // Update account Group data
    public function update_account_group($accout_group_id,$data)
    {
        
        $this->db->where('ActGroupID', $accout_group_id);
        $this->db->update(db_prefix() . 'accountgroups', $data);
         if ($this->db->affected_rows() > 0) {
            
            return true;
        }

        return false;
    }
    
    // Update account Group data
    public function update_account_subgroup($accout_subgroup_id,$data)
    {
        
        $this->db->where('SubActGroupID', $accout_subgroup_id);
        $this->db->update(db_prefix() . 'accountgroupssub', $data);
         if ($this->db->affected_rows() > 0) {
            
            return true;
        }

        return false;
    }
    
    public function get_user_work_on(){
      
        $this->db->where('FilterTypeID','SLDTYPE');
       $departments = $this->db->get(db_prefix() . 'sldtypes')->result_array();
       return $departments;
    }
    
    public function get_state(){
      
      $this->db->order_by('state_name');
       $state__list = $this->db->get(db_prefix() . 'xx_statelist')->result_array();
       return $state__list;
    }
    
    public function get_acount_detail($account_id)
    {
      
        /*
        $this->db->where('PlantID',$selected_company);
        $this->db->where('AccountID',$account_id);
        $account_detail = $this->db->get(db_prefix() . 'clients')->row();
        return $account_detail;*/
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $this->db->select(db_prefix() . 'clients.*,' . db_prefix() . 'contacts.firstname,' . db_prefix() . 'contacts.phonenumber AS mobile1,'. db_prefix() . 'contacts.email,
        '. db_prefix() . 'contacts.pincode,'. db_prefix() . 'contacts.kms,'. db_prefix() . 'contacts.Pan,'. db_prefix() . 'contacts.Aadhaarno,'. db_prefix() . 'contacts.Officeno,'. db_prefix() . 'accountbalances.BAL1');
            $this->db->where(db_prefix() . 'contacts.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'clients.AccountID', $account_id);
            $this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'contacts.PlantID = ' . db_prefix() . 'clients.PlantID');
            $this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND ' . db_prefix() . 'accountbalances.FY = "'.$FY.'"','LEFT');
            //$this->db->order_by('description', 'asc');
            $account_detail = $this->db->get(db_prefix() . 'clients')->row();
            return $account_detail;
    }
    
    public function get_acount_designation($account_id){
      
      $selected_company = $this->session->userdata('root_company');
       $ss = 'SELECT *
        FROM tblaccountsld WHERE AccountID ="'.$account_id.'" AND PlantID ='.$selected_company;

                $result_data = $this->db->query($ss)->row();
                return $result_data;
    }
    
    public function get_subgroup_for_usermaster(){
      
       $ss = 'SELECT *
        FROM tblaccountgroupssub WHERE SubActGroupID IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007")';

                $result_data = $this->db->query($ss)->result_array();
                return $result_data;
    }
    
    public function get_subgroup_for_accounting_head(){
      
       /*$ss = 'SELECT *
        FROM tblaccountgroupssub WHERE SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")';*/
        $ss = 'SELECT * FROM tblaccountgroupssub ';
        $result_data = $this->db->query($ss)->result_array();
        return $result_data;
    }
    
    public function get_distibutor_type(){
      
        $selected_company = $this->session->userdata('root_company');
        $ss = 'SELECT *
        FROM tblcustomers_groups WHERE PlantID ='.$selected_company;

        $result_data = $this->db->query($ss)->result_array();
        return $result_data;
    }
    
    public function get_accounts_list(){
       $selected_company = $this->session->userdata('root_company');
       
       $ss = 'SELECT tblclients.*,tblaccountgroupssub.SubActGroupName,tblaccountgroups.ActGroupName FROM tblclients 
        INNER JOIN tblaccountgroupssub ON tblaccountgroupssub.SubActGroupID = tblclients.SubActGroupID
        INNER JOIN tblaccountgroups ON tblaccountgroups.ActGroupID = tblaccountgroupssub.ActGroupID
        WHERE PlantID ='.$selected_company.' AND tblclients.SubActGroupID NOT IN("30000004","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")
        ORDER BY tblclients.company ASC';

        $result_data = $this->db->query($ss)->result_array();
        return $result_data;
    }

    public function get_grouped()
    {
        $items = [];
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add_client($data)
    {
        
        $this->db->insert(db_prefix() . 'clients', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            
            log_activity('New Account Added [ID:' . $insert_id . ', ' . $data['AccountID'] . ']');

            return true;
        }

        return false;
    }
    
    // Update Client record
    
    public function update_client($client_array,$account_id)
    {
        $selected_company = $this->session->userdata("root_company");
            $this->db->where('PlantID', $selected_company);
            $this->db->where('AccountID', $account_id);
            $this->db->update(db_prefix() . 'clients', $client_array);
            if ($this->db->affected_rows() > 0) {
                return true;
             }else{
                 return false;
            }
    }
    
    // Update Client contacts record
    
    public function update_contact($contact_array,$account_id)
    {
        $selected_company = $this->session->userdata("root_company");
            $this->db->where('PlantID', $selected_company);
            $this->db->where('AccountID', $account_id);
            $this->db->update(db_prefix() . 'contacts', $contact_array);
            if ($this->db->affected_rows() > 0) {
                return true;
             }else{
                 return false;
            }
    }
    
    public function update_bal($update_bal,$account_id)
    {
        $selected_company = $this->session->userdata("root_company");
        $FY = $this->session->userdata("finacial_year");
            $this->db->where('PlantID', $selected_company);
            $this->db->where('AccountID', $account_id);
            $this->db->where('FY', $FY);
            $this->db->update(db_prefix() . 'accountbalances', $update_bal);
            if ($this->db->affected_rows() > 0) {
                return true;
             }else{
                 return false;
            }
    }
    
    // Update Client SLDType
    
    public function update_sldtype($sld_array,$account_id)
    {
        $selected_company = $this->session->userdata("root_company");
            $this->db->where('PlantID', $selected_company);
            $this->db->where('AccountID', $account_id);
            $this->db->update(db_prefix() . 'accountsld', $sld_array);
            
                return true;
            
    }
    
    // add contact details
    
    public function add_contact($data)
    {
        
        $this->db->insert(db_prefix() . 'contacts', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            
            return true;
        }

        return false;
    }
    
    // add SLDtype details
    
    public function add_sldtype($data)
    {
        
        $this->db->insert(db_prefix() . 'accountsld', $data);
        
        
            return true;
             
        
    }
    
    // add Act Balance record
    
    public function add_act_bal($data)
    {
        
        $this->db->insert(db_prefix() . 'accountbalances', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            
            return true;
        }

        return false;
    }

    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);

        $selected_company = $this->session->userdata('root_company');
        $this->db->where('id', $itemid);
        $this->db->where('PlantID', $selected_company);
        $this->db->update(db_prefix() . 'route', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity(' Route Updated [ID: ' . $itemid . ', ' . $data['name'] . ']');
            $affectedRows++;
        }

        

        return $affectedRows > 0 ? true : false;
    }

    public function search($q)
    {
        $this->db->select('rate, id, description as name, long_description as subtext');
        $this->db->like('description', $q);
        $this->db->or_like('long_description', $q);

        $items = $this->db->get(db_prefix() . 'items')->result_array();

        foreach ($items as $key => $item) {
            $items[$key]['subtext'] = strip_tags(mb_substr($item['subtext'], 0, 200)) . '...';
            $items[$key]['name']    = '(' . app_format_number($item['rate']) . ') ' . $item['name'];
        }

        return $items;
    }

    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $this->db->where('id', $id);
        $this->db->where('PlantID', $selected_company);
        $this->db->delete(db_prefix() . 'route');
        if ($this->db->affected_rows() > 0) {
            

            log_activity('Route Deleted [ID: ' . $id . ']');

            

            return true;
        }

        return false;
    }

    public function get_groups()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }

    public function add_group($data)
    {
        $this->db->insert(db_prefix() . 'items_groups', $data);
        log_activity('Items Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }

    public function edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);

            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_groups');

            log_activity('Item Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
    
    
    public function get_main_groups()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'items_main_groups')->result_array();
    }
    
    public function add_main_group($data)
    {
        $this->db->insert(db_prefix() . 'items_main_groups', $data);
        log_activity('Items Main Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }
    
    public function edit_main_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_main_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Main Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    
    public function edit_sub_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_sub_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Sub Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    
     public function delete_main_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_main_groups')->row();

        if ($group) {
            /*$this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);
*/
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_main_groups');

            log_activity('Item Main Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
     public function delete_sub_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_sub_groups')->row();

        if ($group) {
            /*$this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);
*/
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_sub_groups');

            log_activity('Item Sub Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
    public function get_sub_groups()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
    }
    
    public function add_sub_group($data)
    {
        $this->db->insert(db_prefix() . 'items_sub_groups', $data);
        log_activity('Items Sub Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }
    public function get_company_detail()
     {   
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'rootcompany.*
        FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    public function get_actgroup_data()
    {
        $this->db->order_by(db_prefix() . 'accountgroups.ActGroupName', 'ASC');
       return $this->db->get(db_prefix().'accountgroups')->result_array();
    }
    public function get_actsubgroup_data()
    {
        $this->db->order_by(db_prefix() . 'accountgroupssub.SubActGroupName', 'ASC');
       return $this->db->get(db_prefix().'accountgroupssub')->result_array();
    }
    public function GetSupgroupList()
    {
        $this->db->select(db_prefix() . 'accountgroupssub.SubActGroupID,'.db_prefix() . 'accountgroupssub.SubActGroupName,'.db_prefix() . 'accountgroups.ActGroupName');
        $this->db->join(db_prefix() . 'accountgroups', '' . db_prefix() . 'accountgroups.ActGroupID = ' . db_prefix() . 'accountgroupssub.ActGroupID ');
        $this->db->from(db_prefix() . 'accountgroupssub');
        $this->db->order_by(db_prefix() . 'accountgroupssub.SubActGroupID');
        $Data = $this->db->get()->result_array();
        return $Data;
    }
    
    // this query for load table code start here
     public function table_data($data){
      
        $account = $data['account'];
        $sub_group_id = $data['sub_group_id'];
        $status = $data['status'];
        $selected_company = $this->session->userdata('root_company');
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients');
  
          if ($account) {
               $this->db->where(db_prefix() . 'clients.AccountID', $account);
            }
            
            if ($sub_group_id) {
                 $this->db->where(db_prefix() . 'clients.SubActGroupID', $sub_group_id);
            }
            
            if ($status == "all") {
                
            }else{
               $this->db->where(db_prefix() . 'clients.active', 1);
            }
                $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'clients.SubActGroupID NOT IN ("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")');
           $this->db->order_by('AccountID');
            return $this->db->get()->result_array();    
    }
    //end here
    
    public function GetinvoiceNote()
    {   
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'invoicenote.*');
        $this->db->where(db_prefix() . 'invoicenote.PlantID', $selected_company);
        $result = $this->db->get(db_prefix() . 'invoicenote')->row();
        return $result;
        
    }
}
