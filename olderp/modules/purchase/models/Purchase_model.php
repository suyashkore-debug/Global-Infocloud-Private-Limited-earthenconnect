<?php

defined('BASEPATH') or exit('No direct script access allowed'); 

/**
 * This class describes a purchase model. 
 */
class Purchase_model extends App_Model
{   
    private $shipping_fields = ['shipping_street', 'shipping_city', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'];

    private $contact_columns;

    public function __construct()
    {
        parent::__construct();
        
        $this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);
    }

    /**
     * Gets the vendor.
     *
     * @param      string        $id     The identifier
     * @param      array|string  $where  The where
     *
     * @return     <type>        The vendor or list vendors.
     */
    public function get_vendor_bkp($id = '', $where = [])
    {
        $this->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'pur_vendor')) . ',' . get_sql_select_vendor_company());

        $this->db->join(db_prefix() . 'countries', '' . db_prefix() . 'countries.country_id = ' . db_prefix() . 'pur_vendor.country', 'left');
        $this->db->join(db_prefix() . 'pur_contacts', '' . db_prefix() . 'pur_contacts.userid = ' . db_prefix() . 'pur_vendor.userid AND is_primary = 1', 'left');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        if (is_numeric($id)) {

            $this->db->where(db_prefix().'pur_vendor.userid', $id);
            $vendor = $this->db->get(db_prefix() . 'pur_vendor')->row();

            if ($vendor && get_option('company_requires_vat_number_field') == 0) {
                $vendor->vat = null;
            }


            return $vendor;

        }

        $this->db->order_by('company', 'asc');

        return $this->db->get(db_prefix() . 'pur_vendor')->result_array();
    }
    
    public function GetAccountList()
    {
        $selected_company = $this->session->userdata('root_company');
        $SubActGroupID = array('50003002','50003004');
        $this->db->select('tblclients.AccountID,tblclients.company,tblxx_statelist.state_name,tblxx_citylist.city_name');
        $this->db->join(' tblxx_statelist', ' tblxx_statelist.short_name = tblclients.state','LEFT');
        $this->db->join('tblxx_citylist', 'tblxx_citylist.id = tblclients.city','LEFT');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', $SubActGroupID);
        $Data = $this->db->get('tblclients')->result_array();
        return $Data;
    }
    
    public function GetAccountDetails($AccountID)
    {
        $selected_company = $this->session->userdata('root_company');
        $SubActGroupID = array('50003002','50003004');
        $this->db->select('tblclients.AccountID,tblclients.company');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'clients.AccountID', $AccountID);
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', $SubActGroupID);
        $Data = $this->db->get('tblclients')->row();
        return $Data;
    }
    
    public function GetItemList()
    {
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select('tblitems.item_code,tblitems.description,tblitems.hsn_code,tblitems_groups.name AS DivisionName,tblitems_sub_groups.name AS SubGroupName,tblitems_main_groups.name AS MainGroupName');
        $this->db->join(' tblitems_groups', ' tblitems_groups.id = tblitems.group_id','LEFT');
        $this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.subgroup_id','LEFT');
        $this->db->join('tblitems_main_groups', 'tblitems_main_groups.id = tblitems_sub_groups.main_group_id','LEFT');
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        $Data = $this->db->get('tblitems')->result_array();
        return $Data;
    }
    
    public function GetItemDetails($ItemID)
    {
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select('tblitems.item_code,tblitems.description');
        $this->db->where(db_prefix() . 'items.item_code', $ItemID);
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        $Data = $this->db->get('tblitems')->row();
        return $Data;
    }
    
    //-----------------------------------------------------
	public function GetCityList($id){
		$query = $this->db->get_where('tblxx_citylist', array('state_id' => $id));
		return $result = $query->result_array();
	}
    public function get_vendor($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select('*,' . db_prefix() . 'clients.CstNo as Cst_No,' . db_prefix() . 'clients.phonenumber as phone_number,' . db_prefix() . 'accountbalances.BAL1');
        $this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND '. db_prefix() .'contacts.PlantID = ' . db_prefix() . 'clients.PlantID AND  ' . db_prefix() . 'clients.PlantID = '.$selected_company, 'left');
        $this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '. db_prefix() .'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND  ' . db_prefix() . 'clients.PlantID = '.$selected_company, 'left');

       /* if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }*/

        if ($id) {
            $this->db->where(db_prefix() . 'clients.AccountID', $id);
            $this->db->where(db_prefix() . 'accountbalances.FY', $FY);
            $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
            $client = $this->db->get(db_prefix() . 'clients')->row();

            if ($client && get_option('company_requires_vat_number_field') == 0) {
                $client->vat = null;
            }

            $GLOBALS['client'] = $client;

            return $client;
        }

        $this->db->order_by('company', 'asc');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
    
    public function get_data_vendor($id = '')
    {
    //   return $this->db->get_where('clients',array('userid' =>$id))->row();
     $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select();
        $this->db->from(db_prefix() . 'clients');
        $this->db->join(db_prefix() . 'xx_citylist', db_prefix() . 'xx_citylist.id = ' . db_prefix() . 'clients.city', 'left');
        $this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
        $this->db->join(db_prefix() . 'accountbalances', db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND '.db_prefix() . 'accountbalances.FY ="'.$year.'"', 'left');
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'clients.userid', $id);
       
       return $this->db->get()->row();
    }
    
    public function GetVendorList($id = '')
    {
    
     $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountbalances.BAL1,'.db_prefix() . 'xx_citylist.city_name,'.db_prefix() . 'contacts.FLNO1,'.db_prefix() . 'contacts.email,'.db_prefix() . 'contacts.Pan,'.db_prefix() . 'contacts.Aadhaarno');
        $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'contacts.PlantID = ' . db_prefix() . 'clients.PlantID');
        $this->db->join(db_prefix() . 'xx_citylist', db_prefix() . 'xx_citylist.id = ' . db_prefix() . 'clients.city', 'left');
        $this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
        $this->db->join(db_prefix() . 'accountbalances', db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND '.db_prefix() . 'accountbalances.FY ="'.$year.'"', 'left');
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       $this->db->where_in(db_prefix() . 'clients.SubActGroupID', ["50003002","50003008","50003009"]);
       //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', ["50003002"]);
       if($id){
           $this->db->where(db_prefix() . 'clients.AccountID', $id);
           $Data = $this->db->get(db_prefix() . 'clients')->row();
           if($Data){
               $cityList = $this->GetCityList($Data->state);
                $Data->cityList = $cityList;
           }
           return $Data;
       }
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
    
    public function GetVendorListNEW($id = '')
    {
    
    $selected_company = $this->session->userdata('root_company');
    $year = $_SESSION['finacial_year'];
    $this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountbalances.BAL1,'.db_prefix() . 'contacts.FLNO1,'.db_prefix() . 'contacts.email,'.db_prefix() . 'contacts.Pan,'.db_prefix() . 'contacts.Aadhaarno');
    $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'contacts.PlantID = ' . db_prefix() . 'clients.PlantID', 'left');
    $this->db->join(db_prefix() . 'accountbalances', db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND '.db_prefix() . 'accountbalances.FY ="'.$year.'"', 'left');
    $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
    $this->db->where(db_prefix() . 'clients.AccountID', $id);
    $Data = $this->db->get(db_prefix() . 'clients')->row();
           if($Data){
               $Data->AccountType = 'Client';
               $cityList = $this->GetCityList($Data->state);
                $Data->cityList = $cityList;
           }else{
               $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
               $this->db->select(db_prefix() . 'staff.*,');
               //$this->db->where('tblstaff.staff_comp REGEXP',$regExp);
               $this->db->where(db_prefix() . 'staff.AccountID', $id);
               $this->db->where(db_prefix() . 'staff.PlantID', $selected_company);
               $Data = $this->db->get(db_prefix() . 'staff')->row();
               if($Data){
                   $Data->AccountType = 'Staff';
               }
           }
           return $Data;
       
    }
    
    // Add New Vendor
    public function SaveVendor($Clientdata,$Contactdata,$Balancedata)
    {
        $this->db->insert(db_prefix() . 'clients', $Clientdata);
        $INSERT = $this->db->affected_rows();
        if($INSERT > 0){
            $this->db->insert(db_prefix() . 'contacts', $Contactdata);
            $this->db->insert(db_prefix() . 'accountbalances', $Balancedata);
            return true;    
        }else{
            return false;
        }
    }
    
    // Update Exiting Vendor
    public function UpdateVendor($Clientdata,$Contactdata,$Balancedata,$AccountID)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->where('AccountID', $AccountID);
        $this->db->where('PlantID', $selected_company);
        $this->db->update(db_prefix() . 'clients', $Clientdata);
        $UPDATE = $this->db->affected_rows(); 
        $CheckContactRecord = $this->ChkContactRecord($AccountID);
        if($CheckContactRecord){
            $this->db->where('AccountID', $AccountID);
            $this->db->where('PlantID', $selected_company);
            $this->db->update(db_prefix() . 'contacts', $Contactdata);
            $UPDATE = $this->db->affected_rows();
        }else{
            $Contactdata['AccountID'] = $AccountID;
            $Contactdata['PlantID'] = $selected_company;
            $this->db->insert(db_prefix() . 'contacts',$Contactdata);
        }
        
        $CheckACTBALRecord = $this->ChkActBalRecord($AccountID);
        $staff_user_id = $this->session->userdata('staff_user_id');
        if($CheckACTBALRecord){
            if($staff_user_id == "3"){
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $FY);
                $this->db->where('AccountID', $AccountID);
                $this->db->update(db_prefix() . 'accountbalances', $Balancedata);
                $UPDATE = $this->db->affected_rows();
            }
        }else{
            $Balancedata['AccountID'] = $AccountID;
            $Balancedata['PlantID'] = $selected_company;
            $Balancedata['FY'] = $FY;
            $this->db->insert(db_prefix() . 'accountbalances',$Balancedata);
        }
        if($UPDATE > 0){
            return true;
        }else{
            return false;
        }
    }
    // Check Account Contact Type
    public function ChkContactRecord($AccountID)
    {
        $selected_company = $this->session->userdata('root_company');
        $this->db->select(db_prefix() . 'contacts.*');
        $this->db->where('AccountID', $AccountID);
        $this->db->where('PlantID', $selected_company);
        $this->db->from(db_prefix() . 'contacts');
        $data =  $this->db->get()->row();
        return $data;
    }
    // Check Account Contact Type
    public function ChkActBalRecord($AccountID)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $this->db->select(db_prefix() . 'accountbalances.*');
        $this->db->where('AccountID', $AccountID);
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $FY);
        $this->db->from(db_prefix() . 'accountbalances');
        $data =  $this->db->get()->row();
        return $data;
    }
    public function get_vendor_data($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
       
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002','60001004','50003009','50003008']);
        //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002']);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
    public function GetRMVendor($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
       
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002','60001004','50003009','50003008']);
        //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002']);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
   

    
    public function get_contacts($vendor_id = '', $where = ['active' => 1])
    {
        $this->db->where($where);
        if ($vendor_id != '') {
            $this->db->where('userid', $vendor_id);
        }
        $this->db->order_by('is_primary', 'DESC');

        return $this->db->get(db_prefix() . 'pur_contacts')->result_array();
    }

    /**
     * Gets the contact.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <type>  The contact.
     */
    public function get_contact($id)
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'pur_contacts')->row();
    }

    /**
     * Gets the primary contacts.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <type>  The primary contacts.
     */
    public function get_primary_contacts($id)
    {
        $this->db->where('userid', $id);
        $this->db->where('is_primary', 1);
        return $this->db->get(db_prefix() . 'pur_contacts')->row();
    }

    /**
     * Adds a vendor.
     *
     * @param      <type>   $data       The data
     * @param      integer  $client_id  The client identifier
     *
     * @return     integer  ( id vendor )
     */
    public function add_vendor($data, $client_id = null,$client_or_lead_convert_request = false)
    {
       
        // From customer profile register
        if (isset($data['vendor_code'])) {
             $selected_company = $this->session->userdata('root_company');
             $last_year = $this->session->userdata('finacial_year');
            $client['AccountID'] = $data['vendor_code'];
            $client['SubActGroupID'] = $data['account_group'];
            $client['DistributorType'] = '24';
            $accountbalances['AccountID'] = $data['vendor_code'];
            $contacts['AccountID'] = $data['vendor_code'];
            $accountbalances['PlantID'] = $selected_company;
            $client['PlantID'] = $selected_company;
            $contacts['PlantID'] = $selected_company;
            $accountbalances['FY'] = $last_year;
            unset($data['vendor_code']);
        }

        if (isset($data['company'])) {
            $client['company'] = $data['company'];
            unset($data['company']);
        }
        if (isset($data['address'])) {
            $client['address'] = $data['address'];
            unset($data['address']);
        }
        
        if (isset($data['state'])) {
            $client['state'] = $data['state'];
            unset($data['state']);
        }
        if (isset($data['city'])) {
            $client['city'] = $data['city'];
            unset($data['city']);
        }
        if (isset($data['phonenumber'])) {
            $client['altphonenumber'] = $data['phonenumber'];
            unset($data['phonenumber']);
        }
        if (isset($data['address2'])) {
            $client['Address3'] = $data['address2'];
            unset($data['address2']);
        }
        if (isset($data['zip'])) {
            $client['zip'] = $data['zip'];
            unset($data['zip']);
        }
        
        
        if (isset($data['email'])) {
            $contacts['email'] = $data['email'];
            unset($data['email']);
        }
        if (isset($data['Mobile_number'])) {
            $client['phonenumber'] = $data['Mobile_number'];
            unset($data['Mobile_number']);
        }
        if (isset($data['account_group'])) {
            $client['ActGroupID'] = $data['account_group'];
            unset($data['account_group']);
        }
        if (isset($data['vat'])) {
            $client['vat'] = $data['vat'];
            unset($data['vat']);
        }
       
        if (isset($data['food_lic_n'])) {
            $contacts['FLNO1'] = $data['food_lic_n'];
            unset($data['food_lic_n']);
        }
        if (isset($data['opening_b'])) {
            $accountbalances['BAL1'] = $data['opening_b'];
            unset($data['opening_b']);
        }
        
        if (isset($data['Satrt_date'])) {
            $Satrt_date = to_sql_date($data['Satrt_date']);
            $client['StartDate'] = $Satrt_date." ".date('H:i:s');
            unset($data['Satrt_date']);
        }
        
        if (isset($data['gst_type'])) {
            $client['gsttype'] = $data['gst_type'];
            unset($data['gst_type']);
        }
        
         if (isset($data['pan'])) {
            $contacts['Pan'] = $data['pan'];
            unset($data['pan']);
        }
        if (isset($data['adhaar'])) {
            $contacts['Aadhaarno'] = $data['adhaar'];
            unset($data['adhaar']);
        }
        
        $contacts['datecreated'] = date('Y-m-d H:i:s');

        if (is_staff_logged_in()) {
            $client['addedfrom'] = $this->session->userdata('username');
        }
       /* echo "<pre>";
        print_r($client);
        print_r($contacts);
        print_r($accountbalances);
        die;*/

        if(isset($client_id) && $client_id > 0){
            $userid = $client_id;
        } else {
            $this->db->insert(db_prefix() . 'clients', $client);
           
            $userid = $this->db->insert_id(); 
           
             if ($userid) {
                    $this->db->insert(db_prefix() . 'contacts', $contacts);
                    $this->db->insert(db_prefix() . 'accountbalances', $accountbalances);
                }
            }
        return $userid;
    }

    /**
     * { update vendor }
     *
     * @param      <type>   $data            The data
     * @param      <type>   $id              The identifier
     * @param      boolean  $client_request  The client request
     *
     * @return     boolean 
     */
    public function update_vendor($data, $id, $client_request = false)
    {
      $UserID = $this->session->userdata('username');
        if (isset($data['company'])) {
            $client['company'] = $data['company'];
            unset($data['company']);
        }
        
        if (isset($data['account_group'])) {
            $client['SubActGroupID'] = $data['account_group'];
            unset($data['account_group']);
        }
        
        if (isset($data['address'])) {
            $client['address'] = $data['address'];
            unset($data['address']);
        }
        
        if (isset($data['state'])) {
            $client['state'] = $data['state'];
            unset($data['state']);
        }
        if (isset($data['city'])) {
            $client['city'] = $data['city'];
            unset($data['city']);
        }
        if (isset($data['phonenumber'])) {
            $client['altphonenumber'] = $data['phonenumber'];
            unset($data['phonenumber']);
        }
        if (isset($data['address2'])) {
            $client['Address3'] = $data['address2'];
            unset($data['address2']);
        }
        if (isset($data['zip'])) {
            $client['zip'] = $data['zip'];
            unset($data['zip']);
        }
        
        if (isset($data['email'])) {
            $contacts['email'] = $data['email'];
            unset($data['email']);
        }
        if (isset($data['Mobile_number'])) {
            $client['phonenumber'] = $data['Mobile_number'];
            unset($data['Mobile_number']);
        }
      
        if (isset($data['vat'])) {
            if($data['vat'] == ''){
                $client['vat'] = NULL;
                $client['gsttype'] = 2;
            }else{
                $client['vat'] = $data['vat'];
                $client['gsttype'] = 1;
            }
            
            unset($data['vat']);
        }
       
        if (isset($data['food_lic_n'])) {
            $contacts['FLNO1'] = $data['food_lic_n'];
            unset($data['food_lic_n']);
        }
        if (isset($data['opening_b'])) {
            $accountbalances['BAL1'] = $data['opening_b'];
            $accountbalances['UserID2'] = $UserID;
            $accountbalances['Lupdate'] = date('Y-m-d H:i:s');
            unset($data['opening_b']);
        }
        
        if (isset($data['Satrt_date'])) {
            $Satrt_date = to_sql_date($data['Satrt_date']);
            $client['StartDate'] = $Satrt_date.' '.date('H:i:s');
            unset($data['Satrt_date']);
        }
        
        
       
         if (isset($data['pan'])) {
            $contacts['Pan'] = $data['pan'];
            unset($data['pan']);
        }
        if (isset($data['adhaar'])) {
            $contacts['Aadhaarno'] = $data['adhaar'];
            unset($data['adhaar']);
        }
        $selected_company = $this->session->userdata('root_company');
        $AccountID = $data['userid'];
        $client['UserID2'] = $UserID;
        $client['Lupdate'] = date('Y-m-d H:i:s');
        /*echo "<pre>";
        echo $AccountID;
        echo $selected_company;
        print_r($client);
        print_r($contacts);
        print_r($accountbalances);
        die;*/
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID', $AccountID);
        $this->db->update(db_prefix() . 'clients', $client);
        
        $affectedRows++;
        
            $this->db->where('PlantID', $selected_company);
            $this->db->where('AccountID', $AccountID);
            $this->db->update(db_prefix() . 'contacts', $contacts);
            
            $year = $_SESSION['finacial_year'];
            $staff_user_id = $this->session->userdata('staff_user_id');
            if($staff_user_id == "3"){
                $this->db->where('PlantID', $selected_company);
                $this->db->where('AccountID', $AccountID);
                $this->db->where('FY', $year);
                $this->db->update(db_prefix() . 'accountbalances', $accountbalances);
            }
        if ($affectedRows > 0) {
            hooks()->do_action('after_client_updated', $id);
            return true;
        }

        return false;
    }

    /**
     * { check zero columns }
     *
     * @param      <type>  $data   The data
     *
     * @return     array  
     */
    private function check_zero_columns($data)
    {
        if (!isset($data['show_primary_contact'])) {
            $data['show_primary_contact'] = 0;
        }

        if (isset($data['default_currency']) && $data['default_currency'] == '' || !isset($data['default_currency'])) {
            $data['default_currency'] = 0;
        }

        if (isset($data['country']) && $data['country'] == '' || !isset($data['country'])) {
            $data['country'] = 0;
        }

        if (isset($data['billing_country']) && $data['billing_country'] == '' || !isset($data['billing_country'])) {
            $data['billing_country'] = 0;
        }

        if (isset($data['shipping_country']) && $data['shipping_country'] == '' || !isset($data['shipping_country'])) {
            $data['shipping_country'] = 0;
        }

        return $data;
    }

    /**
     * Gets the vendor admins.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <type>  The vendor admins.
     */
    public function get_vendor_admins($id)
    {
        $this->db->where('vendor_id', $id);

        return $this->db->get(db_prefix() . 'pur_vendor_admin')->result_array();
    }


    /**
     * { assign vendor admins }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function assign_vendor_admins($data, $id)
    {
        $affectedRows = 0;

        if (count($data) == 0) {
            $this->db->where('vendor_id', $id);
            $this->db->delete(db_prefix() . 'pur_vendor_admin');
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }
        } else {
            $current_admins     = $this->get_vendor_admins($id);
            $current_admins_ids = [];
            foreach ($current_admins as $c_admin) {
                array_push($current_admins_ids, $c_admin['staff_id']);
            }
            foreach ($current_admins_ids as $c_admin_id) {
                if (!in_array($c_admin_id, $data['customer_admins'])) {
                    $this->db->where('staff_id', $c_admin_id);
                    $this->db->where('vendor_id', $id);
                    $this->db->delete(db_prefix() . 'pur_vendor_admin');
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
            foreach ($data['customer_admins'] as $n_admin_id) {
                if (total_rows(db_prefix() . 'pur_vendor_admin', [
                    'vendor_id' => $id,
                    'staff_id' => $n_admin_id,
                ]) == 0) {
                    $this->db->insert(db_prefix() . 'pur_vendor_admin', [
                        'vendor_id'   => $id,
                        'staff_id'      => $n_admin_id,
                        'date_assigned' => date('Y-m-d H:i:s'),
                    ]);
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
        }
        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }
    
    /**
     * { delete vendor }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_vendor($id)
    {
        $affectedRows = 0;

        hooks()->do_action('before_client_deleted', $id);

        $last_activity = get_last_system_activity_id();
        $company       = get_company_name($id);

        $this->db->where('userid', $id);
        $this->db->delete(db_prefix() . 'pur_vendor');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            // Delete all user contacts
            $this->db->where('userid', $id);
            $contacts = $this->db->get(db_prefix() . 'pur_contacts')->result_array();
            foreach ($contacts as $contact) {
                $this->delete_contact($contact['id']);
            }

            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'vendor');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            $this->db->where('vendor_id', $id);
            $this->db->delete(db_prefix() . 'pur_vendor_admin');

            $this->db->where('rel_id',$id);
            $this->db->where('rel_type','pur_vendor');
            $this->db->delete(db_prefix().'files');
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $id)) {
                delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $id);
            }

            $this->db->where('rel_type','pur_vendor');
            $this->db->where('rel_id',$id);
            $this->db->delete(db_prefix().'notes');
        }
        if ($affectedRows > 0) {
            hooks()->do_action('after_client_deleted', $id);

            return true;
        }

        return false;
    }

    /**
     * Adds a contact.
     *
     * @param      <type>   $data                The data
     * @param      <type>   $customer_id         The customer identifier
     * @param      boolean  $not_manual_request  Not manual request
     *
     * @return     boolean  or contact id
     */
    public function add_contact($data, $customer_id, $not_manual_request = false)
    {
        $send_set_password_email = isset($data['send_set_password_email']) ? true : false;

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
            unset($data['permissions']);
        }

        $data['email_verified_at'] = date('Y-m-d H:i:s');


        if (isset($data['is_primary'])) {
            $data['is_primary'] = 1;
            $this->db->where('userid', $customer_id);
            $this->db->update(db_prefix() . 'pur_contacts', [
                'is_primary' => 0,
            ]);
        } else {
            $data['is_primary'] = 0;
        }

        $password_before_hash = '';
        $data['userid']       = $customer_id;
        if (isset($data['password'])) {
            $password_before_hash = $data['password'];
            $data['password'] = app_hash_password($data['password']);
        }

        $data['datecreated'] = date('Y-m-d H:i:s');

        if (!$not_manual_request) {
            $data['invoice_emails']     = isset($data['invoice_emails']) ? 1 :0;
            $data['estimate_emails']    = isset($data['estimate_emails']) ? 1 :0;
            $data['credit_note_emails'] = isset($data['credit_note_emails']) ? 1 :0;
            $data['contract_emails']    = isset($data['contract_emails']) ? 1 :0;
            $data['task_emails']        = isset($data['task_emails']) ? 1 :0;
            $data['project_emails']     = isset($data['project_emails']) ? 1 :0;
            $data['ticket_emails']      = isset($data['ticket_emails']) ? 1 :0;
        }

        $data['email'] = trim($data['email']);

        $data = hooks()->apply_filters('before_create_contact', $data);

        $this->db->insert(db_prefix() . 'pur_contacts', $data);
        $contact_id = $this->db->insert_id();

        if ($contact_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($contact_id, $custom_fields);
            }
           
            if ($not_manual_request == true) {
                // update all email notifications to 0
                $this->db->where('id', $contact_id);
                $this->db->update(db_prefix() . 'pur_contacts', [
                    'invoice_emails'     => 0,
                    'estimate_emails'    => 0,
                    'credit_note_emails' => 0,
                    'contract_emails'    => 0,
                    'task_emails'        => 0,
                    'project_emails'     => 0,
                    'ticket_emails'      => 0,
                ]);
            } 


            hooks()->do_action('contact_created', $contact_id);

            return $contact_id;
        }

        return false;
    }

    /**
     * { update contact }
     *
     * @param      <type>   $data            The data
     * @param      <type>   $id              The identifier
     * @param      boolean  $client_request  The client request
     *
     * @return     boolean 
     */
    public function update_contact($data, $id, $client_request = false)
    {
        $affectedRows = 0;
        $contact      = $this->get_contact($id);
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password']             = app_hash_password($data['password']);
            $data['last_password_change'] = date('Y-m-d H:i:s');
        }

        $send_set_password_email = isset($data['send_set_password_email']) ? true : false;
        $set_password_email_sent = false;
      
        $data['is_primary'] = isset($data['is_primary']) ? 1 : 0;

        // Contact cant change if is primary or not
        if ($client_request == true) {
            unset($data['is_primary']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        if ($client_request == false) {
            $data['invoice_emails']     = isset($data['invoice_emails']) ? 1 :0;
            $data['estimate_emails']    = isset($data['estimate_emails']) ? 1 :0;
            $data['credit_note_emails'] = isset($data['credit_note_emails']) ? 1 :0;
            $data['contract_emails']    = isset($data['contract_emails']) ? 1 :0;
            $data['task_emails']        = isset($data['task_emails']) ? 1 :0;
            $data['project_emails']     = isset($data['project_emails']) ? 1 :0;
            $data['ticket_emails']      = isset($data['ticket_emails']) ? 1 :0;
        }

        $data = hooks()->apply_filters('before_update_contact', $data, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if (isset($data['is_primary']) && $data['is_primary'] == 1) {
                $this->db->where('userid', $contact->userid);
                $this->db->where('id !=', $id);
                $this->db->update(db_prefix() . 'pur_contacts', [
                    'is_primary' => 0,
                ]);
            }
        }

       
        if ($affectedRows > 0 ) {
            return true;
        } 

        return false;
    }

    /**
     * { delete contact }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_contact($id)
    {
        hooks()->do_action('before_delete_contact', $id);

        $this->db->where('id', $id);
        $result      = $this->db->get(db_prefix() . 'pur_contacts')->row();
        $customer_id = $result->userid;

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'pur_contacts');

        if ($this->db->affected_rows() > 0) {
            
            hooks()->do_action('contact_deleted', $id, $result);

            return true;
        }

        return false;
    }

    /**
     * Gets the approval setting.
     *
     * @param      string  $id     The identifier
     *
     * @return     <type>  The approval setting.
     */
    public function get_approval_setting($id = '')
    {
        if(is_numeric($id)){
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'pur_approval_setting')->row();
        }
        return $this->db->get(db_prefix().'pur_approval_setting')->result_array();
    }

    /**
     * Adds an approval setting.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function add_approval_setting($data)
    {
        unset($data['approval_setting_id']);

        if(isset($data['approver'])){
            $setting = [];
            foreach ($data['approver'] as $key => $value) {
                $node = [];
                $node['approver'] = $data['approver'][$key];
                $node['staff'] = $data['staff'][$key];
                $node['action'] = $data['action'][$key];

                $setting[] = $node;
            }
            unset($data['approver']);
            unset($data['staff']);
            unset($data['action']);
        }
        $data['setting'] = json_encode($setting);

        $this->db->insert(db_prefix() .'pur_approval_setting', $data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return true;
        }
        return false;
    }

    /**
     * { edit approval setting }
     *
     * @param      <type>   $id     The identifier
     * @param      <type>   $data   The data
     *
     * @return     boolean  
     */
    public function edit_approval_setting($id, $data)
    {
        unset($data['approval_setting_id']);

        if(isset($data['approver'])){
            $setting = [];
            foreach ($data['approver'] as $key => $value) {
                $node = [];
                $node['approver'] = $data['approver'][$key];
                $node['staff'] = $data['staff'][$key];
                $node['action'] = $data['action'][$key];

                $setting[] = $node;
            }
            unset($data['approver']);
            unset($data['staff']);
            unset($data['action']);
        }
        $data['setting'] = json_encode($setting);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() .'pur_approval_setting', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { delete approval setting }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean   
     */
    public function delete_approval_setting($id)
    {
        if(is_numeric($id)){
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() .'pur_approval_setting');

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the items.
     *
     * @return     <array>  The items.
     */
    public function get_items(){
         $selected_company = $this->session->userdata('root_company');
    //   $year = $_SESSION['finacial_year'];
       return $this->db->query('select id as id, CONCAT(commodity_code," - " ,description) as label from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
    }
    
    public function get_items_code(){
         $selected_company = $this->session->userdata('root_company');
    //   $year = $_SESSION['finacial_year'];
       return $this->db->query('select id as id, CONCAT(item_code," - ",description) as label,item_code from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
    }
    
    public function get_items_code_purReturn(){
         $selected_company = $this->session->userdata('root_company');
    //   $year = $_SESSION['finacial_year'];
       return $this->db->query('select item_code as item_code, CONCAT(item_code," - ",description) as label,item_code from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
    }
    public function get_items_for_purchRtn(){
         $selected_company = $this->session->userdata('root_company');
       return $this->db->query('select item_code as id, CONCAT(item_code," - " ,description) as label from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
    }
    /**
     * Gets the items by vendor.
     *
     * @return     <array>  The items.
     */
    public function get_items_by_vendor($vendor){
       return $this->db->query('select id as id, CONCAT(commodity_code," - " ,description) as label from '.db_prefix().'items where id IN ( select items from '.db_prefix().'pur_vendor_items where vendor = '.$vendor.' )')->result_array();
    }
    public function get_items_by_vendor_data($item,$vendor){
       return $this->db->query('select id as id, CONCAT(commodity_code," - " ,description) as label from '.db_prefix().'items where id IN ( select items from '.db_prefix().'pur_vendor_items where vendor = '.$vendor.' and items ='.$item.' )')->result_array();
    }
    public function items_purchaseid_check($item,$vendor){
        $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
     //$item_details =  $this->db->get_where('tblitems',array('PlantID'=>$selected_company,'id'=>$item))->row();
     
       $this->db->select();
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'purchasemaster', db_prefix() . 'purchasemaster.PurchID = ' . db_prefix() . 'history.OrderID', 'left');
         $this->db->where(db_prefix() . 'history.TType', 'P');
         $this->db->where(db_prefix() . 'history.ItemID', $item);
         $this->db->where(db_prefix() . 'history.AccountID', $vendor);
       $this->db->where(db_prefix() .'history.PlantID', $selected_company);
    //   $this->db->where(db_prefix() .'history.FY', $year);
       return $this->db->get()->result_array();
    }
    public function items_vendor_check_tcs($vendor){
         //   return $this->db->get_where('clients',array('userid' =>$id))->row();
     $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select();
        $this->db->from(db_prefix() . 'clients');
        $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID', 'left');
         $this->db->where(db_prefix() . 'clients.userid', $vendor);
       $this->db->where(db_prefix() .'contacts.PlantID', $selected_company);
       return $this->db->get()->row();
    //   echo $this->db->last_query();die;
    }
    /**
     * Gets the items by identifier.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <row>  The items by identifier.
     */
    public function get_items_by_id($id){
        $this->db->where('id',$id);
        return $this->db->get(db_prefix().'items')->row();
    }

    /**
     * Gets the units by identifier.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <row>  The units by identifier.
     */
    public function get_units_by_id($id){
        $this->db->where('unit_type_id',$id);
        return $this->db->get(db_prefix().'ware_unit_type')->row();
    }

    /**
     * Gets the units.
     *
     * @return     <array>  The list units.
     */
    public function get_units(){
        return $this->db->query('select unit_type_id as id, unit_name as label from '.db_prefix().'ware_unit_type')->result_array();
    }

    /**
     * { items change event}
     *
     * @param      <type>  $code   The code
     *
     * @return     <row>  ( item )
     */
        public function get_accounts_list(){
      
      
       $selected_company = $this->session->userdata('root_company');
       
       $ss = 'SELECT *
        FROM tblclients WHERE PlantID ='.$selected_company.' AND active = 1 AND SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002")';

                $result_data = $this->db->query($ss)->result_array();
                return $result_data;
    }
    public function get_accounts_freightid($id =""){
         $selected_company = $this->session->userdata('root_company');
        if(!empty($id)){
             $ss = 'SELECT *
        FROM tblclients  WHERE userid='.$id.' AND PlantID ='.$selected_company.' AND active = 1 AND SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002")';

                $result_data = $this->db->query($ss)->row();
             return $result_data;
        }else{
             $ss = 'SELECT *
        FROM tblclients WHERE AccountID="209" AND PlantID ='.$selected_company.' AND active = 1 AND SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")';

                $result_data = $this->db->query($ss)->row();
                // echo $this->db->last_query();die;
                return $result_data;
        }
    } 
     public function get_accounts_othertid($id =""){
         $selected_company = $this->session->userdata('root_company');
         if($selected_company == "1"){
            $OACT = "92";
         }else if($selected_company == "2"){
            $OACT = "92";
         }else if($selected_company == "3"){
            $OACT = "ME"; 
         }
        if(!empty($id)){
             $ss = 'SELECT *
        FROM tblclients  WHERE userid='.$id.' AND PlantID ='.$selected_company.' AND active = 1 AND SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")';

                $result_data = $this->db->query($ss)->row();
             return $result_data;
        }else{
             $ss = 'SELECT *
        FROM tblclients WHERE AccountID ="'.$OACT.'" AND PlantID ='.$selected_company.' AND active = 1 AND SubActGroupID NOT IN("30000004","10022003","10022004","10022005","1002504","1002503","1002506","30000006","30000007","30001002","50003002","60001004")';

                $result_data = $this->db->query($ss)->row();
                // echo $this->db->last_query();die;
                return $result_data;
        }
    }
    public function items_change($code){
        // $this->db->where('id',$code);
        // $rs = $this->db->get(db_prefix().'items')->row();
        
         $this->db->select();
        $this->db->from(db_prefix() . 'items');
        $this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax', 'left');
         $this->db->where(db_prefix() . 'items.id', $code);
    //   $this->db->where(db_prefix() .'contacts.PlantID', $selected_company);
      $rs = $this->db->get()->row();
      
      $sql="SELECT * FROM `tblitems` LEFT JOIN `tbltaxes` ON `tbltaxes`.`id` = `tblitems`.`tax` 
       LEFT JOIN `tblitems_sub_groups` ON `tblitems_sub_groups`.`id` = `tblitems`.`subgroup_id` LEFT JOIN `tblitems_main_groups` ON `tblitems_main_groups`.`id` = `tblitems_sub_groups`.`main_group_id` WHERE `tblitems`.`id` = '".$code."'";    
    $query = $this->db->query($sql);
    return $query->row();
    //   echo $this->db->last_query();die;
// return $rs;
        $this->db->where('unit_type_id',$rs->unit_id);
        $unit = $this->db->get(db_prefix().'ware_unit_type')->row();

        if($unit){
            $rs->unit = $unit->unit_name;
        }else{
            $rs->unit = '';
        }
        
        if(get_status_modules_pur('warehouse') == true){
            $this->db->where('commodity_id',$code);
            $commo = $this->db->get(db_prefix().'inventory_manage')->result_array();
            $rs->inventory = 0;
            if(count($commo) > 0){
                foreach($commo as $co){
                    $rs->inventory += $co['inventory_number'];
                }
            }       
        }else{
            $rs->inventory = 0;
        }

        return $rs;
    }

    /**
     * Gets the purchase request.
     *
     * @param      string  $id     The identifier
     *
     * @return     <row or array>  The purchase request.
     */
    public function get_purchase_request($id = ''){
        if($id == ''){
            return $this->db->get(db_prefix().'pur_request')->result_array();
        }else{
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_request')->row();
        }
    }

    /**
     * Gets the pur request detail.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur request detail.
     */
    public function get_pur_request_detail($pur_request){
        $this->db->where('pur_request',$pur_request);
        return $this->db->get(db_prefix().'pur_request_detail')->result_array();
    }

    /**
     * Gets the pur request detail in estimate.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur request detail in estimate.
     */
    public function get_pur_request_detail_in_estimate($pur_request){
        $this->db->where('pur_request',$pur_request);
        $this->db->select('item_code');
        $this->db->select('unit_id');
        $this->db->select('unit_price');
        $this->db->select('quantity');
        $this->db->select('into_money');
        return $this->db->get(db_prefix().'pur_request_detail')->result_array();
    }

    /**
     * Gets the pur estimate detail in order.
     *
     * @param      <int>  $pur_estimate  The pur estimate
     *
     * @return     <array>  The pur estimate detail in order.
     */
    public function get_pur_estimate_detail_in_order($pur_estimate){
        $this->db->where('pur_estimate',$pur_estimate);
        $this->db->select('item_code');
        $this->db->select('unit_id');
        $this->db->select('unit_price');
        $this->db->select('quantity');
        $this->db->select('into_money');
        $this->db->select('tax');
        $this->db->select('total');
        $this->db->select('total_money');
        $this->db->select('discount_money');
        $this->db->select('discount_%');
        return $this->db->get(db_prefix().'pur_estimate_detail')->result_array();
    }

    /**
     * Gets the pur estimate detail.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur estimate detail.
     */
    public function get_pur_estimate_detail($pur_request){
        $this->db->where('pur_estimate',$pur_request);
        return $this->db->get(db_prefix().'pur_estimate_detail')->result_array();
    }

    /**
     * Gets the pur order detail.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur order detail.
     */
    public function get_pur_order_detail($pur_request){
        $this->db->where('pur_order',$pur_request);
        return $this->db->get(db_prefix().'pur_order_detail')->result_array();
    }
    
     public function get_p_order_detail($pur_request){
         $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select( db_prefix() . 'items_sub_groups.main_group_id,'.db_prefix() . 'history.*,'.db_prefix() . 'items.*,'.db_prefix() . 'items_main_groups.name');
        // $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID', 'left');
        $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.subgroup_id', 'left');
        $this->db->join(db_prefix() . 'items_main_groups', db_prefix() . 'items_main_groups.id = ' . db_prefix() . 'items_sub_groups.main_group_id', 'left');
         $this->db->where(db_prefix() . 'history.OrderID', $pur_request);
       $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'history.FY', $year);
       $data = $this->db->get()->result_array();
       foreach($data  as $key => $value){
           $data[$key]['sub_total'] = $value['OrderAmt']+$value['cgstamt']+$value['sgstamt']+$value['igstamt'];
           $data[$key]['total'] = $value['OrderAmt']+$value['cgstamt']+$value['sgstamt']+$value['igstamt']+$value['DiscAmt'];
       }
       return $data;
         
    }

    /**
     * Adds a pur request.
     *
     * @param      <array>   $data   The data
     *
     * @return     boolean  
     */
    public function add_pur_request($data){
        
        $data['request_date'] = date('Y-m-d H:i:s');
        $check_appr = $this->get_approve_setting('pur_request');
        $data['status'] = 1;
        if($check_appr && $check_appr != false){
            $data['status'] = 1;
        }else{
            $data['status'] = 2;
        }

        if(isset($data['from_items'])){
            $data['from_items'] = 1;
        }else{
            if($data['status'] != 2){
                $data['from_items'] = 0;
            }else{
                $data['from_items'] = 1;
            }
            
        }

        $dpm_name = department_pur_request_name($data['department']);
        $prefix = get_purchase_option('pur_order_prefix');

        $this->db->where('pur_rq_code',$data['pur_rq_code']);
        $check_exist_number = $this->db->get(db_prefix().'pur_request')->row();

        while($check_exist_number) {
          $data['number'] = $data['number'] + 1;
          $data['pur_rq_code'] =  $prefix.'-'.str_pad($data['number'],5,'0',STR_PAD_LEFT).'-'.date('M-Y').'-'.$dpm_name;
          $this->db->where('pur_rq_code',$data['pur_rq_code']);
          $check_exist_number = $this->db->get(db_prefix().'pur_request')->row();
        }

        $data['hash'] = app_generate_hash();

        if(isset($data['request_detail'])){
            $request_detail = json_decode($data['request_detail']);
            unset($data['request_detail']);
            $rq_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];

            if($data['from_items'] == 1){
                $header[] = 'item_code';
            }else{
                $header[] = 'item_text';
            }

            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'inventory_quantity';

            foreach ($request_detail as $key => $value) {

                if($value[0] != ''){
                    $rq_detail[] = array_combine($header, $value);
                }
            }
        }
      
        $this->db->insert(db_prefix().'pur_request',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){

            // Update next purchase order number in settings
            $next_number = $data['number']+1;
            $this->db->where('option_name', 'next_pr_number');
            $this->db->update(db_prefix() . 'purchase_option',['option_val' =>  $next_number,]);

            foreach($rq_detail as $key => $rqd){
                $rq_detail[$key]['pur_request'] = $insert_id;
                if($data['status'] == 2){
                    $item_data['description'] = $rqd['item_code'];
                    $item_data['purchase_price'] = $rqd['unit_price'];
                    $item_data['unit_id'] = $rqd['unit_id'];
                    $item_data['rate'] = '';
                    $item_data['sku_code'] = '';
                    $item_data['commodity_barcode'] = $this->generate_commodity_barcode();
                    $item_data['commodity_code'] = $this->generate_commodity_barcode();
                    $item_id = $this->add_commodity_one_item($item_data);
                    if($item_id){
                       $rq_detail[$key]['item_code'] = $item_id; 
                    }
                    
                }
            }
            $this->db->insert_batch(db_prefix().'pur_request_detail',$rq_detail);
            return $insert_id;
        }
        return false;
    }

    /**
     * { update pur request }
     *
     * @param      <array>   $data   The data
     * @param      <int>   $id     The identifier
     *
     * @return     boolean   
     */
    public function update_pur_request($data,$id){
        $affectedRows = 0;
        $purq = $this->get_purchase_request($id);

        if(isset($data['request_detail'])){
            $request_detail = json_decode($data['request_detail']);
            unset($data['request_detail']);
            $rq_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'prd_id';
            $header[] = 'pur_request';
            if($purq){
                if($purq->from_items == 0){
                    $header[] = 'item_text';
                }else{
                    $header[] = 'item_code';
                }
            }
            
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'inventory_quantity';

            foreach ($request_detail as $key => $values) {

                if($values[2] != ''){
                    $rq_detail[] = array_combine($header, $values);
                }
            }
        }
        
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_request',$data);
        if($this->db->affected_rows() > 0){
            $affectedRows++;
        }

        $row = [];
        $row['update'] = []; 
        $row['insert'] = []; 
        $row['delete'] = [];
        foreach ($rq_detail as $key => $value) {
            if($value['prd_id'] != ''){
                $row['delete'][] = $value['prd_id'];
                $row['update'][] = $value;
            }else{
                unset($value['prd_id']);
                $value['pur_request'] = $id;
                $row['insert'][] = $value;
            }
        }

        if(count($row['delete']) != 0){
            $row['delete'] = implode(",",$row['delete']);
            $this->db->where('prd_id NOT IN ('.$row['delete'] .') and pur_request ='.$id);
            $this->db->delete(db_prefix().'pur_request_detail');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['insert']) != 0){
            $this->db->insert_batch(db_prefix().'pur_request_detail', $row['insert']);
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['update']) != 0){
            $this->db->update_batch(db_prefix().'pur_request_detail', $row['update'], 'prd_id');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }


        if($affectedRows > 0){
            return true;
        }
        return false;
    }

    /**
     * { delete pur request }
     *
     * @param      <int>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_pur_request($id){
        $affectedRows = 0;
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_request');
        if($this->db->affected_rows() > 0){
            $affectedRows++;
        }

        $this->db->where('pur_request',$id);
        $this->db->delete(db_prefix().'pur_request_detail');
        if($this->db->affected_rows() > 0){
            $affectedRows++;
        }

         if($affectedRows > 0){
            return true;
        }
        return false;
    }

    /**
     * { change status pur request }
     *
     * @param      <type>   $status  The status
     * @param      <type>   $id      The identifier
     *
     * @return     boolean 
     */
    public function change_status_pur_request($status,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_request',['status' => $status]);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the pur request by status.
     *
     * @param      <type>  $status  The status
     *
     * @return     <array>  The pur request by status.
     */
    public function get_pur_request_by_status($status){
        $this->db->where('status',$status);
        return $this->db->get(db_prefix().'pur_request')->result_array();
    }

    /**
     * { function_description }
     *
     * @param      <type>  $data   The data
     *
     * @return     <array> data
     */
    private function map_shipping_columns($data)
    {
        if (!isset($data['include_shipping'])) {
            foreach ($this->shipping_fields as $_s_field) {
                if (isset($data[$_s_field])) {
                    $data[$_s_field] = null;
                }
            }
            $data['show_shipping_on_estimate'] = 1;
            $data['include_shipping']          = 0;
        } else {
            $data['include_shipping'] = 1;
            // set by default for the next time to be checked
            if (isset($data['show_shipping_on_estimate']) && ($data['show_shipping_on_estimate'] == 1 || $data['show_shipping_on_estimate'] == 'on')) {
                $data['show_shipping_on_estimate'] = 1;
            } else {
                $data['show_shipping_on_estimate'] = 0;
            }
        }

        return $data;
    }

    /**
     * Gets the estimate.
     *
     * @param      string  $id     The identifier
     * @param      array   $where  The where
     *
     * @return     <row , array>  The estimate, list estimate.
     */
    public function get_estimate($id = '', $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'currencies.id as currencyid, ' . db_prefix() . 'pur_estimates.id as id, ' . db_prefix() . 'currencies.name as currency_name');
        $this->db->from(db_prefix() . 'pur_estimates');
        $this->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'pur_estimates.currency', 'left');
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'pur_estimates.id', $id);
            $estimate = $this->db->get()->row();
            if ($estimate) {
                
                $estimate->visible_attachments_to_customer_found = false;
                
                $estimate->items = get_items_by_type('pur_estimate', $id);

                if ($estimate->pur_request != 0) {
                   
                    $estimate->pur_request = $this->get_purchase_request($estimate->pur_request);
                }else{
                    $estimate->pur_request = '';
                }

                $estimate->vendor = $this->get_vendor($estimate->vendor);
                if (!$estimate->vendor) {
                    $estimate->vendor          = new stdClass();
                    $estimate->vendor->company = $estimate->deleted_customer_name;
                }
            }

            return $estimate;
        }
        $this->db->order_by('number,YEAR(date)', 'desc');

        return $this->db->get()->result_array();
    }

    /**
     * Gets the pur order.
     *
     * @param      <int>  $id     The identifier
     *
     * @return     <row>  The pur order.
     */
    public function get_pur_order($id){
        $this->db->where('id',$id);
        return $this->db->get(db_prefix().'pur_orders')->row();
    }


    /**
     * Adds an estimate.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean  or in estimate
     */
    public function add_estimate($data)
    {   
        $check_appr = $this->get_approve_setting('pur_quotation');
        $data['status'] = 1;
        if($check_appr && $check_appr != false){
            $data['status'] = 1;
        }else{
            $data['status'] = 2;
        }
        $data['date'] = to_sql_date($data['date']);
        $data['expirydate'] = to_sql_date($data['expirydate']);

        $data['datecreated'] = date('Y-m-d H:i:s');

        $data['addedfrom'] = get_staff_user_id();

        $data['prefix'] = get_option('estimate_prefix');

        $data['number_format'] = get_option('estimate_number_format');

        $this->db->where('prefix',$data['prefix']);
        $this->db->where('number',$data['number']);
        $check_exist_number = $this->db->get(db_prefix().'pur_estimates')->row();

        while($check_exist_number) {
          $data['number'] = $data['number'] + 1;
          
          $this->db->where('prefix',$data['prefix']);
          $this->db->where('number',$data['number']);
          $check_exist_number = $this->db->get(db_prefix().'pur_estimates')->row();
        }

        $save_and_send = isset($data['save_and_send']);

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['hash'] = app_generate_hash();

        $data = $this->map_shipping_columns($data);

        if (isset($data['shipping_street'])) {
            $data['shipping_street'] = trim($data['shipping_street']);
            $data['shipping_street'] = nl2br($data['shipping_street']);
        }

        if(isset($data['dc_total'])){
            $data['discount_total'] = reformat_currency_pur($data['dc_total']);
            unset($data['dc_total']);
        }

        if(isset($data['dc_percent'])){
            $data['discount_percent'] = $data['dc_percent'];
            unset($data['dc_percent']);
        }

        if(isset($data['estimate_detail'])){
            $estimate_detail = json_decode($data['estimate_detail']);
            unset($data['estimate_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'tax';
            $header[] = 'total';
            $header[] = 'discount_%';
            $header[] = 'discount_money';
            $header[] = 'total_money';

            foreach ($estimate_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        

        $this->db->insert(db_prefix() . 'pur_estimates', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            $total = [];
            $total['total'] = 0;
            $total['total_tax'] = 0;
            $total['subtotal'] = 0;
            
            foreach($es_detail as $key => $rqd){
                $es_detail[$key]['pur_estimate'] = $insert_id;
                $total['total'] += $rqd['total_money'];
                $total['total_tax'] += ($rqd['total']-$rqd['into_money']);
                $total['subtotal'] += $rqd['into_money'];
            }

            if($data['discount_total'] > 0){
                $total['total'] = $total['total'] - $data['discount_total'];
            }

            $this->db->insert_batch(db_prefix().'pur_estimate_detail',$es_detail);

            $this->db->where('id',$insert_id);
            $this->db->update(db_prefix().'pur_estimates',$total);

            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }
            
            hooks()->do_action('after_estimate_added', $insert_id);

            return $insert_id;
        }

        return false;
    }

    /**
     * { update estimate }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function update_estimate($data, $id)
    {
        $data['date'] = to_sql_date($data['date']);
        $data['expirydate'] = to_sql_date($data['expirydate']);
        $affectedRows = 0;

        $data['number'] = trim($data['number']);

        $original_estimate = $this->get_estimate($id);

        $original_status = $original_estimate->status;

        $original_number = $original_estimate->number;

        $original_number_formatted = format_estimate_number($id);

        $data = $this->map_shipping_columns($data);
        
        unset($data['isedit']);

        if(isset($data['estimate_detail'])){
            $estimate_detail = json_decode($data['estimate_detail']);
            unset($data['estimate_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'id';
            $header[] = 'pur_estimate';
            $header[] = 'item_code';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'tax';
            $header[] = 'total';
            $header[] = 'discount_%';
            $header[] = 'discount_money';
            $header[] = 'total_money';

            foreach ($estimate_detail as $key => $value) {

                if($value[2] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }

        if(isset($data['dc_total'])){
            $data['discount_total'] = reformat_currency_pur($data['dc_total']);
            unset($data['dc_total']);
        }

        if(isset($data['dc_percent'])){
            $data['discount_percent'] = $data['dc_percent'];
            unset($data['dc_percent']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_estimates', $data);

        if ($this->db->affected_rows() > 0) {
            if ($original_status != $data['status']) {
                if ($data['status'] == 2) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'pur_estimates', ['sent' => 1, 'datesend' => date('Y-m-d H:i:s')]);
                }
            }
            $affectedRows++;
        }

        

        $row = [];
        $row['update'] = []; 
        $row['insert'] = []; 
        $row['delete'] = [];
        $total = [];
        $total['total'] = 0;
        $total['total_tax'] = 0;
        $total['subtotal'] = 0;
        
        foreach ($es_detail as $key => $value) {
            if($value['id'] != ''){
                $row['delete'][] = $value['id'];
                $row['update'][] = $value;
            }else{
                unset($value['id']);
                $value['pur_estimate'] = $id;
                $row['insert'][] = $value;
            }

            $total['total'] += $value['total_money'];
            $total['total_tax'] += ($value['total']-$value['into_money']);
            $total['subtotal'] += $value['into_money'];
            
        }

        if($data['discount_total'] > 0){
            $total['total'] = $total['total'] - $data['discount_total'];
        }
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_estimates',$total);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if(empty($row['delete'])){
            $row['delete'] = ['0'];
        }
            $row['delete'] = implode(",",$row['delete']);
            $this->db->where('id NOT IN ('.$row['delete'] .') and pur_estimate ='.$id);
            $this->db->delete(db_prefix().'pur_estimate_detail');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        
        if(count($row['insert']) != 0){
            $this->db->insert_batch(db_prefix().'pur_estimate_detail', $row['insert']);
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['update']) != 0){
            $this->db->update_batch(db_prefix().'pur_estimate_detail', $row['update'], 'id');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }

        
        if ($affectedRows > 0) {
           

            return true;
        }

        return false;
    }

    /**
     * Gets the estimate item.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <row>  The estimate item.
     */
    public function get_estimate_item($id)
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'itemable')->row();
    }

    /**
     * { delete estimate }
     *
     * @param      string   $id            The identifier
     * @param      boolean  $simpleDelete  The simple delete
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function delete_estimate($id, $simpleDelete = false)
    {
        
        
        hooks()->do_action('before_estimate_deleted', $id);

        $number = format_estimate_number($id);

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'pur_estimates');

        if ($this->db->affected_rows() > 0) {
           
            $this->db->where('pur_estimate', $id);
            $this->db->delete(db_prefix() . 'pur_estimate_detail');

            $this->db->where('relid IN (SELECT id from ' . db_prefix() . 'itemable WHERE rel_type="pur_estimate" AND rel_id="' . $id . '")');
            $this->db->where('fieldto', 'items');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            $this->db->where('rel_type', 'pur_estimate');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'taggables');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'pur_estimate');
            $this->db->delete(db_prefix() . 'itemable');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'pur_estimate');
            $this->db->delete(db_prefix() . 'item_tax');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'pur_estimate');
            $this->db->delete(db_prefix() . 'sales_activity');

            return true;
        }

        return false;
    }

    /**
     * Gets the taxes.
     *
     * @return     <array>  The taxes.
     */
    public function get_taxes()
    {
       return $this->db->query('select id, name as label, taxrate from '.db_prefix().'taxes')->result_array();
    }

    /**
     * Gets the total tax.
     *
     * @param      <type>   $taxes  The taxes
     *
     * @return     integer  The total tax.
     */
    public function get_total_tax($taxes){
        $rs = 0;
        foreach($taxes as $tax){
            $this->db->where('id',$tax);
            $this->db->select('taxrate');
            $ta = $this->db->get(db_prefix().'taxes')->row();
            $rs += $ta->taxrate;
        }
        return $rs;
    }

    /**
     * { change status pur estimate }
     *
     * @param      <type>   $status  The status
     * @param      <type>   $id      The identifier
     *
     * @return     boolean   
     */
    public function change_status_pur_estimate($status,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_estimates',['status' => $status]);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * { change status pur order }
     *
     * @param      <type>   $status  The status
     * @param      <type>   $id      The identifier
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function change_status_pur_order($status,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_orders',['approve_status' => $status]);
        if($this->db->affected_rows() > 0){

            hooks()->apply_filters('create_goods_receipt',['status' => $status,'id' => $id]);
            return true;
        }
        return false;
    }

    /**
     * Gets the estimates by status.
     *
     * @param      <type>  $status  The status
     *
     * @return     <array>  The estimates by status.
     */
    public function get_estimates_by_status($status){
        $this->db->where('status',$status);
        return $this->db->get(db_prefix().'pur_estimates')->result_array();
    }

    /**
     * { estimate by vendor }
     *
     * @param      <type>  $vendor  The vendor
     *
     * @return     <array>  ( list estimate by vendor )
     */
    public function estimate_by_vendor($vendor){
        $this->db->where('vendor',$vendor);
        $this->db->where('status', 2);
        return $this->db->get(db_prefix().'pur_estimates')->result_array();
    }

    
    public function add_pur_order_new($data)
    {
        
        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'pur_unit';
            $header[] = 'CaseQty';
            $header[] = 'PurchRate';
            $header[] = 'Cases';
            $header[] = 'QTY';
            $header[] = 'disc';
            $header[] = 'DiscAmt';
            $header[] = 'GST';
            $header[] = 'CGSTAMT';
            $header[] = 'SGSTAMT';
            $header[] = 'IGSTAMT';
            
            
            $header[] = 'total_money';
            foreach ($pur_order_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        
        // print_r($data);
        // echo '<br>';
        $acc_id = $this->db->select('AccountID,vat')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
        
        if($acc_id->vat == ''){
            $bt = 'N';
        }else{
            $bt = 'Y';
        }
        $PlantID = $this->session->userdata('root_company'); 
        $FY = $this->session->userdata('finacial_year'); 
        if($PlantID == "1"){
            $GodownID = 'CSPL';
        }else if($PlantID == "2"){
            $GodownID = 'CFF';
        }else if($PlantID == "3"){
            $GodownID = 'CBUPL';
        }
        
        if($PlantID == 1){
            $purchase_orderNumbar = get_option('next_purchase_number_for_cspl');
        }elseif($PlantID == 2){
            $purchase_orderNumbar = get_option('next_purchase_number_for_cff');
        }elseif($PlantID == 3){
            $purchase_orderNumbar = get_option('next_purchase_number_for_cbu');
        }
        $Discamt = 0;
        $new_purchase_orderNumbar = 'PUR'.$FY.$purchase_orderNumbar;   
        $ItCount = count($es_detail);
        $Transdate =  to_sql_date($data['prd_date'])." ".date('H:i:s');
        $PurchID =  $data['pur_order_number'];
        $prd_date =  $data['prd_date'];
        $vendor =  $data['vendor'];
        $Invoiceno =  $data['invoce_n'];
        $invoce_date =  to_sql_date($data['invoce_date']);
        $FrtAccountID =  $data['Freight_1'];
        $OthAccountID =  $data['Other_ac'];
        $tcs =  $data['tcs_pre'];
        $tcsAmt =  $data['tcs_pre_data'];
        $Discamt =  $data['dc_total'];
        $Frtamt =  $data['Freight_AMT'];
        $cgstamt =  $data['CGST_amt'];
        $Othamt =  $data['Other_amt'];
        $sgstamt =  $data['SGST_AMT'];
        $RoundOffAmt =  $data['Round_OFF'];
        $igstamt =  $data['IGST_amt'];
       
        $Invamt =  str_replace(",","",$data['Invoice_amt']);
        $purchase_amt =    str_replace(",","",$data['total_mn']);
        $data_array = array(
            'PlantID'=>$PlantID,
            'FY'=>$FY,
            'BT'=>$bt,
            'PurchID' =>$new_purchase_orderNumbar,
            'Transdate' =>$Transdate,
            'FrtAccountID' =>$FrtAccountID,
            'AccountID'=>$acc_id->AccountID,
            'Invoiceno'=>$Invoiceno,
            'Invoicedate'=> to_sql_date($data['invoce_date']),
            'Purchamt'=> $purchase_amt,
            'Discamt'=>$Discamt,
            'Frtamt'=>$Frtamt,
            'Othamt'=>$Othamt,
            'Invamt'=>$Invamt,
            'ItCount'=>$ItCount,
            'RoundOffAmt'=>$RoundOffAmt,
            'OthAccountID'=>$OthAccountID,
            'cgstamt'=>$cgstamt,
            'sgstamt'=>$sgstamt,
            'igstamt'=>$igstamt,
            'tcs'=>$tcs,
            'tcsAmt'=>$tcsAmt,
            "Userid" => $_SESSION['username'],
        );
       
        $this->db->insert(db_prefix() . 'purchasemaster',$data_array);
        //print_r($data_array);
        if($this->db->affected_rows() > 0){
            $ord_n = 1;
            $this->increment_next_purchase_number();
            $narrations = 'By Inv no.'.$Invoiceno.'-'._d(to_sql_date($data['invoce_date'])).'-'.$new_purchase_orderNumbar.'-'._d(to_sql_date($data['prd_date']));
            $ledger_credit = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchase_orderNumbar,
                "AccountID" => $acc_id->AccountID,
                "TType" => "C",
                "Amount" => $Invamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" => $ord_n,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
                );
                //print_r($ledger_credit);
            $this->db->insert(db_prefix() . 'accountledger',$ledger_credit);
            $ord_n++;
            $newmonth = substr($Transdate,5,2);
            $month = $newmonth;
            if($month == "01"){
               $m = 11; 
            }
            if($month == "02"){
               $m = 12; 
            }
            if($month == "03"){
               $m = 13; 
            }
            if($month == "04"){
               $m = 2; 
            }
            if($month == "05"){
               $m = 3; 
            }
            if($month == "06"){
               $m = 4; 
            }
            if($month == "07"){
               $m = 5; 
            }
            if($month == "08"){
               $m = 6; 
            }
            if($month == "09"){
               $m = 7; 
            }
            if($month == "10"){
               $m = 8; 
            }
            if($month == "11"){
               $m = 9; 
            }
            if($month == "12"){
               $m = 10; 
            }
            $mm = "BAL".$m;
            
            // credit ledger for selected account
            $act_bal = $this->get_acc_bal($acc_id->AccountID);
            $current_bal = $act_bal->$mm;
            $current_bal_total = $current_bal - $Invamt;
                
                   $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', $acc_id->AccountID);
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total,
                                    ]);
            
            if($Othamt > 0 || $Othamt < 0){
                $ord_n++;
                // other Account ledger
                    $ledger_otherAct = array(
                        "PlantID"     => $PlantID,
                        "Transdate"   => $Transdate,
                        "TransDate2"  => date('Y-m-d H:i:s'),
                        "VoucherID"   => $new_purchase_orderNumbar,
                        "AccountID"   => $OthAccountID,
                        "TType"       => "D",
                        "Amount"      => $Othamt,
                        "Narration"   => $narrations,
                        "PassedFrom"  => "PURCHASE",
                        "OrdinalNo"   => $ord_n,
                        "UserID"      => $_SESSION['username'],
                        "FY"          => $FY
                    );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_otherAct);
                $ord_n++;
                $act_bal_O = $this->get_acc_bal($OthAccountID);
                if(empty($act_bal_O)){
                    $bal_o = $Othamt;
                    $insertActBal_O = array(
                        'PlantID'   =>$PlantID,
                        'AccountID' =>$OthAccountID,
                        'FY'        =>$FY,
                        $mm         =>$bal_o,
                        'BAL1'      =>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal_O);
                }else{
                    $current_bal_o = $act_bal_O->$mm;
                    $current_bal_total_o = $current_bal_o + $Othamt;
                    
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', $OthAccountID);
                    $this->db->update(db_prefix() . 'accountbalances', [
                                $mm => $current_bal_total_o,
                            ]);
                }
            }
            
            if($Frtamt > 0 || $Frtamt < 0){
                // Frt Account ledger
                $ledger_frtAct = array(
                    "PlantID"    => $PlantID,
                    "Transdate"  => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID"  => $new_purchase_orderNumbar,
                    "AccountID"  => $FrtAccountID,
                    "TType"      => "D",
                    "Amount"     => $Frtamt,
                    "Narration"  => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo"  => $ord_n,
                    "UserID"     => $_SESSION['username'],
                    "FY"         => $FY
                );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_frtAct);
                $ord_n++;
                $act_bal_f = $this->get_acc_bal($FrtAccountID);
                if(empty($act_bal_f)){
                    $bal_f = $Frtamt;
                    $insertActBal_F = array(
                        'PlantID'=>$PlantID,
                        'AccountID'=>$FrtAccountID,
                        'FY'=>$FY,
                        $mm=>$bal_f,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal_F);
                }else{
                    $current_bal_f = $act_bal_f->$mm;
                    $current_bal_total_f = $current_bal_f + $Frtamt;
                    
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', $FrtAccountID);
                    $this->db->update(db_prefix() . 'accountbalances', [
                                $mm => $current_bal_total_f,
                            ]);
                }
            }
            if($tcsAmt > 0 || $tcsAmt < 0){
                // TCS Account ledger
                $ledger_tcsAct = array(
                    "PlantID"    => $PlantID,
                    "Transdate"  => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID"  => $new_purchase_orderNumbar,
                    "AccountID"  => "TCS",
                    "TType"      => "D",
                    "Amount"     => $tcsAmt,
                    "Narration"  => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo"  => $ord_n,
                    "UserID"     => $_SESSION['username'],
                    "FY"         => $FY
                );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_tcsAct);
                $ord_n++;
                $act_bal_tcs = $this->get_acc_bal('TCS');
                $current_bal_tcs = $act_bal_tcs->$mm;
                $current_bal_total_tcs = $current_bal_tcs + $tcsAmt;
                
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', 'TCS');
                $this->db->update(db_prefix() . 'accountbalances', [
                            $mm => $current_bal_total_tcs,
                        ]);
            }
            
        // Debit ledger for selected account
            $ledger_debit = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchase_orderNumbar,
                "AccountID" => "PURCH",
                "TType" => "D",
                "Amount" => $purchase_amt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" =>$ord_n,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_n++;
            $act_bal2 = $this->get_acc_bal("PURCH");
            $current_bal2 = $act_bal2->$mm;
            $current_bal_total2 = $current_bal2 + (int) $purchase_amt;
            
            $this->db->where('PlantID', $PlantID);
            $this->db->LIKE('FY', $FY);
            $this->db->where('AccountID', "PURCH");
            $this->db->update(db_prefix() . 'accountbalances', [
                                $mm => $current_bal_total2,
                            ]);
                            
            if($Discamt > 0){
                // Credit ledger for Discounts
                $ledger_debit = array(
                    "PlantID" => $PlantID,
                    "Transdate" => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $new_purchase_orderNumbar,
                    "AccountID" => "PURCH",
                    "TType" => "C",
                    "Amount" => $Discamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" =>$ord_n,
                    "UserID" => $_SESSION['username'],
                    "FY" => $FY
                );
                    //print_r($ledger_debit);
                $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
                $ord_n++;
                $act_bal22 = $this->get_acc_bal("PURCH");
                $current_bal22 = $act_bal22->$mm;
                $current_bal_total22 = $current_bal22 + $Discamt;
                
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', "PURCH");
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm => $current_bal_total22,
                                ]);
            }
            if($igstamt !== "0.00"){
                $gst = $igstamt;
                $act_bal3 = $this->get_acc_bal("IGST");
                $current_bal3 = $act_bal3->$mm;
                $current_bal_total3 = $current_bal3 + $gst;
                   
                $ledger_igst = array(
                    "PlantID"    => $PlantID,
                    "Transdate"  => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID"  => $new_purchase_orderNumbar,
                    "AccountID"  => "IGST",
                    "TType"      => "D",
                    "Amount"     => $gst,
                    "Narration"  => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo"  => $ord_n,
                    "UserID"     => $_SESSION['username'],
                    "FY"         => $FY
                );
                //print_r($ledger_debit);
                $this->db->insert(db_prefix() . 'accountledger',$ledger_igst);
                $ord_n++;
                    
                      $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "IGST");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total3,
                                    ]);
            }else{
            //cgst ledger creation
                $gst1 = $cgstamt;
                $act_bal4 = $this->get_acc_bal("CGST");
                $current_bal4 = $act_bal4->$mm;
                $current_bal_total4 = $current_bal4 + $gst1;
                
                $ledger_cgst = array(
                    "PlantID" => $PlantID,
                    "Transdate" => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $new_purchase_orderNumbar,
                    "AccountID" => "CGST",
                    "TType" => "D",
                    "Amount" => $gst1,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" => $ord_n,
                    "UserID" => $_SESSION['username'],
                    "FY" => $FY
                );
                //print_r($ledger_debit);
                $this->db->insert(db_prefix() . 'accountledger',$ledger_cgst);
                $ord_n++;
                    
                      $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "CGST");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total4,
                                    ]);
            //sgst ledger creation             
                $gst2 = $sgstamt;
                $act_bal5 = $this->get_acc_bal("SGST");
                $current_bal5 = $act_bal5->$mm;
                $current_bal_total5 = $current_bal5 + (int) $gst2;
                   
                $ledger_sgst = array(
                    "PlantID" => $PlantID,
                    "Transdate" => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $new_purchase_orderNumbar,
                    "AccountID" => "SGST",
                    "TType" => "D",
                    "Amount" => $gst2,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" => $ord_n,
                    "UserID" => $_SESSION['username'],
                    "FY" => $FY
                );
                //print_r($ledger_debit);
                $this->db->insert(db_prefix() . 'accountledger',$ledger_sgst);
                $ord_n++;
                    
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "SGST");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total5,
                                    ]);
            }
                
            if($RoundOffAmt > 0 || $RoundOffAmt < 0){
                $RoundOffAmt = $RoundOffAmt;
                $act_bal6 = $this->get_acc_bal("ROUNDOFF");
                $current_bal6 = $act_bal6->$mm;
                $current_bal_total6 = $current_bal6 + $RoundOffAmt;
                    
                $ledger_ROUNDOFF = array(
                    "PlantID" => $PlantID,
                    "Transdate" => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $new_purchase_orderNumbar,
                    "AccountID" => "ROUNDOFF",
                    "TType" => "D",
                    "Amount" => $RoundOffAmt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" => $ord_n,
                    "UserID" => $_SESSION['username'],
                    "FY" => $FY
                );
                //print_r($ledger_debit);
                $this->db->insert(db_prefix() . 'accountledger',$ledger_ROUNDOFF);
                $ord_n++;
                    
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "ROUNDOFF");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total6,
                                    ]);
                
            }   
                
            $i =1;
            foreach($es_detail as $value){
                $item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$PlantID))->row();
            
                $get_purch_stock = $this->get_purch_stock($item_c->item_code);
                $new_purch_stock = $get_purch_stock->PQty + $value['QTY'];
           
                $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('ItemID', $item_c->item_code);
                    $this->db->update(db_prefix() . 'stockmaster', [
                                        'PQty' => $new_purch_stock,
                                    ]);
                $gst_devide = 0;
                $gst_igst = 0;
                if($data['state_c'] == 'UP'){
                 $gst_devide =  $value['GST']/2;
                }else{
                    $gst_igst = $value['GST'];
                }
                $Cases = $value['QTY'] / $value['pack'];
                $data_array_result = array(
                    'PlantID'=>$PlantID,
                    'FY'=>$FY,
                    'cnfid' =>1,
                    'OrderID' =>$new_purchase_orderNumbar,
                    'TransDate' =>$Transdate,
                    'BillID' =>$new_purchase_orderNumbar,
                    'GodownID' =>$GodownID,
                    'TransDate2'=>$Transdate,
                    'TType'=>'P',
                    'TType2'=> 'Purchase',
                    'AccountID'=> $acc_id->AccountID,
                    'ItemID'=>$item_c->item_code,
                    'CaseQty'=>$value['CaseQty'],
                    'PurchRate'=>$value['PurchRate'],
                    'SaleRate'=>$value['PurchRate'],
                    'BasicRate'=>$value['PurchRate'],
                    'SuppliedIn'=>1,
                    'Cases'=>$value['Cases'],
                    'OrderQty'=>$value['QTY'],
                    'BilledQty'=>$value['QTY'],
                    'OrderAmt'=>$value['PurchRate']*$value['QTY'],
                    'DiscAmt'=>$value['DiscAmt'],
                    'gst'=>$value['GST'],
                    'cgst'=>$gst_devide,
                    'sgst'=>$gst_devide,
                    'igst'=>$gst_igst,
                    'cgstamt'=>$value['CGSTAMT'],
                    'sgstamt'=>$value['SGSTAMT'],
                    'igstamt'=>$value['IGSTAMT'],
                    'OrderAmt'=>$value['PurchRate']*$value['QTY'],
                    'ChallanAmt'=>$value['PurchRate']*$value['QTY'],
                    'NetOrderAmt'=>($value['PurchRate']*$value['QTY'])-$value['DiscAmt']+$value['CGSTAMT']+$value['SGSTAMT']+$value['IGSTAMT'],
                    'NetChallanAmt'=>($value['PurchRate']*$value['QTY'])-$value['DiscAmt']+$value['CGSTAMT']+$value['SGSTAMT']+$value['IGSTAMT'],
                    'Ordinalno'=>$i,
                    'UserID'=>$_SESSION['username'],
                );
                $this->db->insert(db_prefix() . 'history',$data_array_result);
                $i++;
                
            }
            return true;
        }
    }
    public function get_acc_bal($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('AccountID', $id);

        return $this->db->get(db_prefix() . 'accountbalances')->row();
    }
    public function get_purch_stock($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('ItemID', $id);

        return $this->db->get(db_prefix() . 'stockmaster')->row();
    }
    
    public function increment_next_purchase_number()
    {
        // Update next TAX Transaction number in settings
        $FY = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_purchase_number_for_cspl');
                
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_purchase_number_for_cff');
               
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_purchase_number_for_cbu');
                
            }
        
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }

    
    public function delete_pur_order($id)
    {
        $affectedRows = 0;
        $this->db->where('pur_order',$id);
        $this->db->delete(db_prefix().'pur_order_detail');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_order');
        $this->db->delete(db_prefix().'files');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $id)) {
            delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $id);
        }

        $this->db->where('pur_order',$id);
        $this->db->delete(db_prefix().'pur_order_payment');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('rel_type','purchase_order');
        $this->db->where('rel_id',$id);
        $this->db->delete(db_prefix().'notes');

        $this->db->where('rel_type','purchase_order');
        $this->db->where('rel_id',$id);
        $this->db->delete(db_prefix().'reminders');

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'pur_orders');

        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'pur_order');
        $this->db->delete(db_prefix() . 'taggables');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if($affectedRows > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the pur order approved.
     *
     * @return     <array>  The pur order approved.
     */
    public function get_pur_order_approved(){
        $this->db->where('approve_status', 2);
        return $this->db->get(db_prefix().'pur_orders')->result_array();
    }

    /**
     * Adds a contract.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean  ( false) or int id contract
     */
    public function add_contract($data){
        
        $data['contract_value'] = reformat_currency_pur($data['contract_value']);
        $data['payment_amount'] = reformat_currency_pur($data['payment_amount']);

        $project = $this->projects_model->get($data['project']);
        $vendor_name = get_vendor_company_name($data['vendor']);
        $ven_rs = strtoupper(str_replace(' ', '', $vendor_name));
        $ct_rs = strtoupper(str_replace(' ', '', $data['contract_name']));
        if($project){
            $pj_rs = strtoupper(str_replace(' ', '', $project->name));
            $data['contract_number'] = $pj_rs.'-'.$ct_rs.'-'.$ven_rs;
        }else{
            $data['contract_number'] = $ct_rs.'-'.$ven_rs;
        }

        $data['add_from'] = get_staff_user_id();
        $data['start_date'] = to_sql_date($data['start_date']);
        $data['end_date'] = to_sql_date($data['end_date']);
        $data['signed_date'] = to_sql_date($data['signed_date']);
        $this->db->insert(db_prefix().'pur_contracts',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return $insert_id;
        }
        return false;
        
    }

    /**
     * { update contract }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function update_contract($data,$id) {
        $data['contract_value'] = reformat_currency_pur($data['contract_value']);
        $data['payment_amount'] = reformat_currency_pur($data['payment_amount']);

        $project = $this->projects_model->get($data['project']);
        $vendor_name = get_vendor_company_name($data['vendor']);
        $ven_rs = strtoupper(str_replace(' ', '', $vendor_name));
        $ct_rs = strtoupper(str_replace(' ', '', $data['contract_name']));
        if($project){
            $pj_rs = strtoupper(str_replace(' ', '', $project->name));
            $data['contract_number'] = $pj_rs.'-'.$ct_rs.'-'.$ven_rs;
        }else{
            $data['contract_number'] = $ct_rs.'-'.$ven_rs;
        }

        $data['add_from'] = get_staff_user_id();
        $data['start_date'] = to_sql_date($data['start_date']);
        $data['end_date'] = to_sql_date($data['end_date']);
        $data['time_payment'] = to_sql_date($data['time_payment']);
        $data['signed_date'] = to_sql_date($data['signed_date']);
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_contracts',$data);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * { delete contract }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean   
     */
    public function delete_contract($id){
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_contract');
        $this->db->delete(db_prefix().'files');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $id)) {
            delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $id);
        }

        if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/contract_sign/'. $id)) {
            delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/contract_sign/'. $id);
        }

        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_contracts');
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the html vendor.
     *
     * @param      <type>  $vendor  The vendor
     *
     * @return     string  The html vendor.
     */
    public function get_html_vendor($vendor){
        
        $vendors = $this->get_vendor($vendor);
        $html = '<table class="table border table-striped ">
                            <tbody>
                               <tr class="project-overview">';
        $html .= '<td width="20%" class="bold">'._l('company').'</td>';
        $html .= '<td>'.$vendors->company.'</td>';
        $html .= '<td width="20%" class="bold">'._l('phonenumber').'</td>';
        $html .= '<td>'.$vendors->phonenumber.'</td>';                               
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="bold">'._l('city').'</td>';
        $html .= '<td>'.$vendors->city.'</td>';
        $html .= '<td width="20%" class="bold">'._l('address').'</td>';
        $html .= '<td>'.$vendors->address.'</td>';                               
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="bold">'._l('client_vat_number').'</td>';
        $html .= '<td>'.$vendors->vat.'</td>';
        $html .= '<td width="20%" class="bold">'._l('website').'</td>';
        $html .= '<td>'.$vendors->website.'</td>';                               
        $html .= '</tr>';
        $html .= '</tbody>
                </table>';

        return $html;
    }

    /**
     * Gets the contract.
     *
     * @param      string  $id     The identifier
     *
     * @return     <row>,<array>  The contract.
     */
    public function get_contract($id = ''){
        if($id == ''){
            return  $this->db->get(db_prefix().'pur_contracts')->result_array();
        }else{
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_contracts')->row();
        }
    }

    /**
     * { sign contract }
     *
     * @param      <type>   $contract  The contract
     * @param      <type>   $status    The status
     *
     * @return     boolean 
     */
    public function sign_contract($contract,$status){
        $this->db->where('id',$contract);
        $this->db->update(db_prefix().'pur_contracts',[
            'signed_status' => $status,
            'signed_date' => date('Y-m-d'),
            'signer' => get_staff_user_id(),
        ]);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * { check approval details }
     *
     * @param      <type>          $rel_id    The relative identifier
     * @param      <type>          $rel_type  The relative type
     *
     * @return     boolean|string 
     */
    public function check_approval_details($rel_id, $rel_type){
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $approve_status = $this->db->get(db_prefix().'pur_approval_details')->result_array();
        if(count($approve_status) > 0){
            foreach ($approve_status as $value) {
                if($value['approve'] == -1){
                    return 'reject';
                }
                if($value['approve'] == 0){
                    $value['staffid'] = explode(', ',$value['staffid']);
                    return $value;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Gets the list approval details.
     *
     * @param      <type>  $rel_id    The relative identifier
     * @param      <type>  $rel_type  The relative type
     *
     * @return     <array>  The list approval details.
     */
    public function get_list_approval_details($rel_id, $rel_type){
        $this->db->select('*');
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        return $this->db->get(db_prefix().'pur_approval_details')->result_array();
    }

    /**
     * Sends a request approve.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean   
     */
    public function send_request_approve($data){
        if(!isset($data['status'])){
            $data['status'] = '';
        }
        $date_send = date('Y-m-d H:i:s');
        $data_new = $this->get_approve_setting($data['rel_type'], $data['status']);
        if(!$data_new){
            return false;
        }
        $this->delete_approval_details($data['rel_id'], $data['rel_type']);
        $list_staff = $this->staff_model->get();
        $list = [];
        $staff_addedfrom = $data['addedfrom'];
        $sender = get_staff_user_id();
        
        foreach ($data_new as $value) {
            $row = [];
            
            if($value->approver !== 'staff'){
            $value->staff_addedfrom = $staff_addedfrom;
            $value->rel_type = $data['rel_type'];
            $value->rel_id = $data['rel_id'];
            
                $approve_value = $this->get_staff_id_by_approve_value($value, $value->approver);

                if(is_numeric($approve_value)){
                    $approve_value = $this->staff_model->get($approve_value)->email;
                }else{

                    $this->db->where('rel_id', $data['rel_id']);
                    $this->db->where('rel_type', $data['rel_type']);
                    $this->db->delete('tblpur_approval_details');


                    return $value->approver;
                }
                $row['approve_value'] = $approve_value;
            
            $staffid = $this->get_staff_id_by_approve_value($value, $value->approver);
            
            if(empty($staffid)){
                $this->db->where('rel_id', $data['rel_id']);
                $this->db->where('rel_type', $data['rel_type']);
                $this->db->delete('tblpur_approval_details');


                return $value->approver;
            }

                $row['action'] = $value->action;
                $row['staffid'] = $staffid;
                $row['date_send'] = $date_send;
                $row['rel_id'] = $data['rel_id'];
                $row['rel_type'] = $data['rel_type'];
                $row['sender'] = $sender;
                $this->db->insert('tblpur_approval_details', $row);

            }else if($value->approver == 'staff'){
                $row['action'] = $value->action;
                $row['staffid'] = $value->staff;
                $row['date_send'] = $date_send;
                $row['rel_id'] = $data['rel_id'];
                $row['rel_type'] = $data['rel_type'];
                $row['sender'] = $sender;

                $this->db->insert('tblpur_approval_details', $row);
            }
        }
        return true;
    }

    /**
     * Gets the approve setting.
     *
     * @param      <type>   $type    The type
     * @param      string   $status  The status
     *
     * @return     boolean  The approve setting.
     */
    public function get_approve_setting($type, $status = ''){
        $this->db->select('*');
        $this->db->where('related', $type);
        $approval_setting = $this->db->get('tblpur_approval_setting')->row();
        if($approval_setting){
            return json_decode($approval_setting->setting);
        }else{
            return false;
        }
    }

    /**
     * { delete approval details }
     *
     * @param      <type>   $rel_id    The relative identifier
     * @param      <type>   $rel_type  The relative type
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function delete_approval_details($rel_id, $rel_type)
    {
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->delete(db_prefix().'pur_approval_details');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Gets the staff identifier by approve value.
     *
     * @param      <type>  $data           The data
     * @param      string  $approve_value  The approve value
     *
     * @return     array   The staff identifier by approve value.
     */
    public function get_staff_id_by_approve_value($data, $approve_value){
        $list_staff = $this->staff_model->get();
        $list = [];
        $staffid = [];
        
        if($approve_value == 'department_manager'){
            $staffid = $this->departments_model->get_staff_departments($data->staff_addedfrom)[0]['manager_id'];
        }elseif($approve_value == 'direct_manager'){
            $staffid = $this->staff_model->get($data->staff_addedfrom)->team_manage;
        }
        
        return $staffid;
    }

    /**
     * Gets the staff sign.
     *
     * @param      <type>  $rel_id    The relative identifier
     * @param      <type>  $rel_type  The relative type
     *
     * @return     array   The staff sign.
     */
    public function get_staff_sign($rel_id, $rel_type){
        $this->db->select('*');

        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->where('action', 'sign');    
        $approve_status = $this->db->get(db_prefix().'pur_approval_details')->result_array();
        if(isset($approve_status))
        {
            $array_return = [];
            foreach ($approve_status as $key => $value) {
               array_push($array_return, $value['staffid']);
            }
            return $array_return;
        }
        return [];
    }


    /**
     * Sends a mail.
     *
     * @param      <type>  $data   The data
     */
    public function send_mail($data){
        $this->load->model('emails_model');
        if(!isset($data['status'])){
            $data['status'] = '';
        }
        $get_staff_enter_charge_code = '';
        $mes = 'notify_send_request_approve_project';
        $staff_addedfrom = 0;
        $additional_data = $data['rel_type'];
        $object_type = $data['rel_type'];
        switch ($data['rel_type']) {
            case 'pur_request':
                $staff_addedfrom = $this->get_purchase_request($data['rel_id'])->requester;
                $additional_data = $this->get_purchase_request($data['rel_id'])->pur_rq_name;
                $list_approve_status = $this->get_list_approval_details($data['rel_id'],$data['rel_type']);
                $mes = 'notify_send_request_approve_pur_request';
                $mes_approve = 'notify_send_approve_pur_request';
                $mes_reject = 'notify_send_rejected_pur_request';
                $link = 'purchase/view_pur_request/' . $data['rel_id'];
                break;

            case 'pur_quotation':
                $staff_addedfrom = $this->get_estimate($data['rel_id'])->addedfrom;
                $additional_data = format_pur_estimate_number($data['rel_id']);
                $list_approve_status = $this->get_list_approval_details($data['rel_id'],$data['rel_type']);
                $mes = 'notify_send_request_approve_pur_quotation';
                $mes_approve = 'notify_send_approve_pur_quotation';
                $mes_reject = 'notify_send_rejected_pur_quotation';
                $link = 'purchase/quotations/' . $data['rel_id'];
                break;

            case 'pur_order':
                $pur_order = $this->get_pur_order($data['rel_id']);
                $staff_addedfrom = $pur_order->addedfrom;
                $additional_data = $pur_order->pur_order_number;
                $list_approve_status = $this->get_list_approval_details($data['rel_id'],$data['rel_type']);
                $mes = 'notify_send_request_approve_pur_order';
                $mes_approve = 'notify_send_approve_pur_order';
                $mes_reject = 'notify_send_rejected_pur_order';
                $link = 'purchase/purchase_order/' . $data['rel_id'];
                break;        
            case 'payment_request':
                $pur_inv = $this->get_payment_pur_invoice($data['rel_id']);
                $staff_addedfrom = $pur_inv->requester;
                $additional_data = _l('payment_for').' '.get_pur_invoice_number($pur_inv->pur_invoice);
                $list_approve_status = $this->get_list_approval_details($data['rel_id'],$data['rel_type']);
                $mes = 'notify_send_request_approve_pur_inv';
                $mes_approve = 'notify_send_approve_pur_inv';
                $mes_reject = 'notify_send_rejected_pur_inv';
                $link = 'purchase/payment_invoice/' . $data['rel_id'];
                break;
            default:
                
                break;
        }


        $check_approve_status = $this->check_approval_details($data['rel_id'], $data['rel_type'], $data['status']);
        if(isset($check_approve_status['staffid'])){

        $mail_template = 'send-request-approve';

            if(!in_array(get_staff_user_id(),$check_approve_status['staffid'])){
                foreach ($check_approve_status['staffid'] as $value) {
                    $staff = $this->staff_model->get($value);
                    $notified = add_notification([
                    'description'     => $mes,
                    'touserid'        => $staff->staffid,
                    'link'            => $link,
                    'additional_data' => serialize([
                        $additional_data,
                    ]),
                    ]);
                    if ($notified) {
                        pusher_trigger_notification([$staff->staffid]);
                    }
                }
            }
        }

        if(isset($data['approve'])){
            if($data['approve'] == 2){
                $mes = $mes_approve;
                $mail_template = 'send_approve';
            }else{
                $mes = $mes_reject;
                $mail_template = 'send_rejected';
            }

            
            $staff = $this->staff_model->get($staff_addedfrom);
            $notified = add_notification([
            'description'     => $mes,
            'touserid'        => $staff->staffid,
            'link'            => $link,
            'additional_data' => serialize([
                $additional_data,
            ]),
            ]);
            if ($notified) {
                pusher_trigger_notification([$staff->staffid]);
            }

            foreach($list_approve_status as $key => $value){
            $value['staffid'] = explode(', ',$value['staffid']);
                if($value['approve'] == 1 && !in_array(get_staff_user_id(),$value['staffid'])){
                    foreach ($value['staffid'] as $staffid) {
                      
                    $staff = $this->staff_model->get($staffid);
                    $notified = add_notification([
                    'description'     => $mes,
                    'touserid'        => $staff->staffid,
                    'link'            => $link,
                    'additional_data' => serialize([
                        $additional_data,
                    ]),
                    ]);
                    if ($notified) {
                        pusher_trigger_notification([$staff->staffid]);
                    }
        
                    }
                }
            }
        }
    }

    /**
     * { update approve request }
     *
     * @param      <type>   $rel_id    The relative identifier
     * @param      <type>   $rel_type  The relative type
     * @param      <type>   $status    The status
     *
     * @return     boolean
     */
    public function update_approve_request($rel_id , $rel_type, $status){ 
        $data_update = [];
        
        switch ($rel_type) {
            case 'pur_request':
                $data_update['status'] = $status;
                $this->update_item_pur_request($rel_id);
                $this->db->where('id', $rel_id);
                $this->db->update(db_prefix().'pur_request', $data_update);
                return true;
                break;
            case 'pur_quotation':
                $data_update['status'] = $status;
                $this->db->where('id', $rel_id);
                $this->db->update(db_prefix().'pur_estimates', $data_update);
                return true;
                break;
            case 'pur_order':
                $data_update['approve_status'] = $status;
                $this->db->where('id', $rel_id);
                $this->db->update(db_prefix().'pur_orders', $data_update);
                return true;
                break;
            case 'payment_request':
                $data_update['approval_status'] = $status;
                $this->db->where('id', $rel_id);
                $this->db->update(db_prefix().'pur_invoice_payment', $data_update);

                $this->update_invoice_after_approve($rel_id);

                return true;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * { update item pur request }
     *
     * @param      $id     The identifier
     */
    public function update_item_pur_request($id){
        $pur_rq = $this->get_purchase_request($id);
        if($pur_rq){
            if($pur_rq->from_items == 0){
                $this->db->where('id',$id);
                $this->db->update(db_prefix().'pur_request',['from_items' => 1]);

                $pur_rqdt = $this->get_pur_request_detail($id);
                if(count($pur_rqdt) > 0){
                    foreach($pur_rqdt as $rqdt){
                        $item_data['description'] = $rqdt['item_text'];
                        $item_data['purchase_price'] = $rqdt['unit_price'];
                        $item_data['unit_id'] = $rqdt['unit_id'];
                        $item_data['rate'] = '';
                        $item_data['sku_code'] = '';
                        $item_data['commodity_barcode'] = $this->generate_commodity_barcode();
                        $item_data['commodity_code'] = $this->generate_commodity_barcode();
                        $item_id = $this->add_commodity_one_item($item_data);
                        $this->db->where('prd_id',$rqdt['prd_id']);
                        $this->db->update(db_prefix().'pur_request_detail',['item_code' => $item_id,]);
                    }
                }
            }
        }
    }

    /**
     * { update approval details }
     *
     * @param      <int>   $id     The identifier
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function update_approval_details($id, $data){
        $data['date'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'pur_approval_details', $data);
        if($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { pur request pdf }
     *
     * @param      <type>  $pur_request  The pur request
     *
     * @return      ( pdf )
     */
    public function pur_request_pdf($pur_request)
    {
        return app_pdf('pur_request', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_request_pdf'), $pur_request);
    }

    /**
     * Gets the pur request pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The pur request pdf html.
     */
    public function get_pur_request_pdf_html($pur_request_id){
        $this->load->model('departments_model');

        $pur_request = $this->get_purchase_request($pur_request_id);
        $project = $this->projects_model->get($pur_request->project);
        $project_name = '';
        if($project){
            $project_name = $project->name;
        }

        $pur_request_detail = $this->get_pur_request_detail($pur_request_id);
        $company_name = get_option('invoice_company_name'); 
        $dpm_name = $this->departments_model->get($pur_request->department)->name;
        $address = get_option('invoice_company_address'); 
        $day = date('d',strtotime($pur_request->request_date));
        $month = date('m',strtotime($pur_request->request_date));
        $year = date('Y',strtotime($pur_request->request_date));
        $list_approve_status = $this->get_list_approval_details($pur_request_id,'pur_request');

    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('purchase_company_name').': '. $company_name.'</td>
            <td rowspan="3" width="" class="text-right">'.get_po_logo().'</td>
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
          </tr>
          <tr>
            <td class="font_500">'.$pur_request->pur_rq_code.'</td>
          </tr>
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('purchase_request')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            <td class="td_width_25"><h4>'. _l('requester').':</h4></td>
            <td class="td_width_75">'. get_staff_full_name($pur_request->requester).'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('department').':</h4></td>
            <td>'. $dpm_name.'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('type').':</h4></td>
            <td>'. _l($pur_request->type).'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('project').':</h4></td>
            <td>'.  $project_name.'</td>
          </tr>
        </tbody>
      </table>
      <br><br>
      ';

      $html .=  '<table class="table pur_request-item">
            <thead>
              <tr class="border_tr">
                <th align="left" class="thead-dark">'._l('items').'</th>
                <th  class="thead-dark">'._l('pur_unit').'</th>
                <th align="right" class="thead-dark">'._l('purchase_unit_price').'</th>
                <th align="right" class="thead-dark">'._l('purchase_quantity').'</th>
                <th align="right" class="thead-dark">'._l('into_money').'</th>
                <th align="right" class="thead-dark">'._l('inventory_quantity').'</th>
              </tr>
            </thead>
          <tbody>';

      $tmn = 0;    
      foreach($pur_request_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr class="border_tr">
            <td >'.$items->commodity_code.' - '.$items->description.'</td>
            <td >'.$units->unit_name.'</td>
            <td align="right">'.app_format_money($row['unit_price'],'').'</td>
            <td align="right">'.$row['quantity'].'</td>
            <td align="right">'.app_format_money($row['into_money'],'').'</td>
            <td align="right">'.$row['inventory_quantity'].'</td>
          </tr>';
          $tmn += $row['into_money'];
      }  
      $html .=  '</tbody>
      </table><br><br>';

      $html .= '<table class="table text-right"><tbody>';
      $html .= '<tr>
                 <td width="33%"></td>
                 <td>'. _l('total').'</td>
                 <td class="subtotal">
                    '. app_format_money($tmn, '').'
                 </td>
              </tr>';

      $html .= ' </tbody></table>';

      $html .= '<br>
      <br>
      <br>
      <br>
      <table class="table">
        <tbody>
          <tr>';
     if(count($list_approve_status) > 0){
      
        foreach ($list_approve_status as $value) {
     $html .= '<td class="td_appr">';
        if($value['action'] == 'sign'){
            $html .= '<h3>'.mb_strtoupper(get_staff_full_name($value['staffid'])).'</h3>';
            if($value['approve'] == 2){ 
                $html .= '<img src="'.site_url('modules/purchase/uploads/pur_request/signature/'.$pur_request->id.'/signature_'.$value['id'].'.png').'" class="img_style">';
            }
                
        }else{ 
        $html .= '<h3>'.mb_strtoupper(get_staff_full_name($value['staffid'])).'</h3>';
              if($value['approve'] == 2){ 
        $html .= '<img src="'.site_url('modules/purchase/uploads/approval/approved.png').'" class="img_style">';
             }elseif($value['approve'] == 3){
        $html .= '<img src="'.site_url('modules/purchase/uploads/approval/rejected.png').'" class="img_style">';
             }
              
                }
       $html .= '</td>';
        }
       
    
    
     } 
            $html .= '<td class="td_ali_font"><h3>'.mb_strtoupper(_l('purchase_requestor')).'</h3></td>
            <td class="td_ali_font"><h3>'.mb_strtoupper(_l('purchase_treasurer')).'</h3></td></tr>
        </tbody>
      </table>';
      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * { request quotation pdf }
     *
     * @param      <type>  $pur_request  The pur request
     *
     * @return      ( pdf )
     */
    public function request_quotation_pdf($pur_request)
    {
        return app_pdf('pur_request', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Request_quotation_pdf'), $pur_request);
    }

    /**
     * Gets the request quotation pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The request quotation pdf html.
     */
    public function get_request_quotation_pdf_html($pur_request_id){
        $this->load->model('departments_model');

        $pur_request = $this->get_purchase_request($pur_request_id);
        $project = $this->projects_model->get($pur_request->project);
        $project_name = '';
        if($project){
            $project_name = $project->name;
        }

        $pur_request_detail = $this->get_pur_request_detail($pur_request_id);
        $company_name = get_option('invoice_company_name'); 
        $dpm_name = $this->departments_model->get($pur_request->department)->name;
        $address = get_option('invoice_company_address'); 
        $day = date('d',strtotime($pur_request->request_date));
        $month = date('m',strtotime($pur_request->request_date));
        $year = date('Y',strtotime($pur_request->request_date));
        $list_approve_status = $this->get_list_approval_details($pur_request_id,'pur_request');

    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('purchase_company_name').': '. $company_name.'</td>
            <td rowspan="3" width="" class="text-right">'.get_po_logo().'</td>
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
          </tr>
          <tr>
            <td class="font_500">'.$pur_request->pur_rq_code.'</td>
          </tr>
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('purchase_request')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            <td class="td_width_25"><h4>'. _l('requester').':</h4></td>
            <td class="td_width_75">'. get_staff_full_name($pur_request->requester).'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('department').':</h4></td>
            <td>'. $dpm_name.'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('type').':</h4></td>
            <td>'. _l($pur_request->type).'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('project').':</h4></td>
            <td>'.  $project_name.'</td>
          </tr>
        </tbody>
      </table>
      <br><br>
      ';

      $html .=  '<table class="table pur_request-item">
            <thead>
              <tr class="border_tr">
                <th align="left" class="thead-dark">'._l('items').'</th>
                <th  class="thead-dark">'._l('pur_unit').'</th>
                <th align="right" class="thead-dark">'._l('purchase_unit_price').'</th>
                <th align="right" class="thead-dark">'._l('purchase_quantity').'</th>
                <th align="right" class="thead-dark">'._l('into_money').'</th>
              </tr>
            </thead>
          <tbody>';

      $tmn = 0;    
      foreach($pur_request_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr class="border_tr">
            <td >'.$items->commodity_code.' - '.$items->description.'</td>
            <td >'.$units->unit_name.'</td>
            <td align="right">'.app_format_money($row['unit_price'],'').'</td>
            <td align="right">'.$row['quantity'].'</td>
            <td align="right">'.app_format_money($row['into_money'],'').'</td>
          </tr>';
          $tmn += $row['into_money'];
      }  
      $html .=  '</tbody>
      </table><br><br>';

      $html .= '<table class="table text-right"><tbody>';
      $html .= '<tr>
                 <td width="33%"></td>
                 <td>'. _l('total').'</td>
                 <td class="subtotal">
                    '. app_format_money($tmn, '').'
                 </td>
              </tr>';

      $html .= ' </tbody></table>';

      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * Sends a request quotation.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean
     */
    public function send_request_quotation($data){
        $staff_id = get_staff_user_id();

        $inbox = array();

        $inbox['to'] = implode(',',$data['email']);
        $inbox['sender_name'] = get_staff_full_name($staff_id);
        $inbox['subject'] = _strip_tags($data['subject']);
        $inbox['body'] = _strip_tags($data['content']);        
        $inbox['body'] = nl2br_save_html($inbox['body']);
        $inbox['date_received']      = date('Y-m-d H:i:s');
        $inbox['from_email'] = get_option('smtp_email');
        
        if(strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0){

            $ci = &get_instance();
            $ci->email->initialize();
            $ci->load->library('email');    
            $ci->email->clear(true);
            $ci->email->from($inbox['from_email'], $inbox['sender_name']);
            $ci->email->to($inbox['to']);
            
            $ci->email->subject($inbox['subject']);
            $ci->email->message($inbox['body']);
            
            $attachment_url = site_url(PURCHASE_PATH.'request_quotation/'.$data['pur_request_id'].'/'.str_replace(" ", "_", $_FILES['attachment']['name']));
            $ci->email->attach($attachment_url);

            return $ci->email->send(true);
        }
        
        return false;
    }

    /**
     * { update purchase setting }
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function update_purchase_setting($data)
    {

            $val = $data['input_name_status'] == 'true' ? 1 : 0;
            $this->db->where('option_name',$data['input_name']);
            $this->db->update(db_prefix() . 'purchase_option', [
                    'option_val' => $val,
                ]);
            if ($this->db->affected_rows() > 0) {
                return true;
            }else{
                return false;
            }
    }


    /**
     * { update purchase setting }
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function update_po_number_setting($data)
    {   
        $rs = 0;
        $this->db->where('option_name','create_invoice_by');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['create_invoice_by'],
            ]);
        if ($this->db->affected_rows() > 0) {
            $rs++;
        }
        
        $this->db->where('option_name','pur_request_prefix');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['pur_request_prefix'],
            ]);
        if ($this->db->affected_rows() > 0) {
            $rs++;
        }

        $this->db->where('option_name','pur_inv_prefix');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['pur_inv_prefix'],
            ]);
        if ($this->db->affected_rows() > 0) {
            $rs++;
        }

        $this->db->where('option_name','pur_order_prefix');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['pur_order_prefix'],
            ]);
        if ($this->db->affected_rows() > 0) {
            $rs++;
        }

        $this->db->where('option_name','terms_and_conditions');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['terms_and_conditions'],
            ]);
        if ($this->db->affected_rows() > 0) {
            $rs++;
        }

        $this->db->where('option_name','vendor_note');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['vendor_note'],
            ]);
        if ($this->db->affected_rows() > 0) {
            $rs++;
        }

        $this->db->where('rel_id', 0);
        $this->db->where('rel_type', 'po_logo');
        $avar = $this->db->get(db_prefix() . 'files')->row();

        if ($avar && (isset($_FILES['po_logo']['name']) && $_FILES['po_logo']['name'] != '')) {
            if (empty($avar->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id . '/' . $avar->file_name);
            }
            $this->db->where('id', $avar->id);
            $this->db->delete('tblfiles');

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id)) {
                // Check if no avars left, so we can delete the folder also
                $other_avars = list_files(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id);
                if (count($other_avars) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id);
                }
            }
        }

        if(handle_po_logo()){
            $rs++;
        }

        if($rs > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the purchase order attachments.
     *
     * @param      <type>  $id     The purchase order
     *
     * @return     <type>  The purchase order attachments.
     */
    public function get_purchase_order_attachments($id){
   
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_order');
        return $this->db->get(db_prefix().'files')->result_array();
    }

    /**
     * Gets the file.
     *
     * @param      <type>   $id      The file id
     * @param      boolean  $rel_id  The relative identifier
     *
     * @return     boolean  The file.
     */
    public function get_file($id, $rel_id = false)
    {
        $this->db->where('id', $id);
        $file = $this->db->get(db_prefix().'files')->row();

        if ($file && $rel_id) {
            if ($file->rel_id != $rel_id) {
                return false;
            }
        }
        return $file;
    }

    /**
     * Gets the part attachments.
     *
     * @param      <type>  $surope  The surope
     * @param      string  $id      The identifier
     *
     * @return     <type>  The part attachments.
     */
    public function get_purorder_attachments($surope, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_order');
        $result = $this->db->get(db_prefix().'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * { delete purorder attachment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function delete_purorder_attachment($id)
    {
        $attachment = $this->get_purorder_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Gets the payment purchase order.
     *
     * @param      <type>  $id     The purcahse order id
     *
     * @return     <type>  The payment purchase order.
     */
    public function get_payment_purchase_order($id){
        $this->db->where('pur_order',$id);
        return $this->db->get(db_prefix().'pur_order_payment')->result_array();
    }

    /**
     * Adds a payment.
     *
     * @param      <type>   $data       The data
     * @param      <type>   $pur_order  The pur order id
     *
     * @return     boolean  ( return id payment after insert )
     */
    public function add_payment($data, $pur_order){
        $data['date'] = to_sql_date($data['date']);
        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data['amount'] = str_replace(',', '', $data['amount']);
        $data['pur_order'] = $pur_order;

        $this->db->insert(db_prefix().'pur_order_payment',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return $insert_id;
        }
        return false;
    }

    /**
     * { delete payment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  ( delete payment )
     */
    public function delete_payment($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_order_payment');
        if ($this->db->affected_rows() > 0) {
                return true;
        }
        return false;
    }

    /**
     * { purorder pdf }
     *
     * @param      <type>  $pur_request  The pur request
     *
     * @return     <type>  ( purorder pdf )
     */
    public function purorder_pdf($pur_order)
    {
        return app_pdf('pur_order', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_order_pdf'), $pur_order);
    }


    /**
     * Gets the pur request pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The pur request pdf html.
     */
    public function get_purorder_pdf_html($pur_order_id){
        

        $pur_order = $this->get_pur_order($pur_order_id);
        $pur_order_detail = $this->get_pur_order_detail($pur_order_id);
        $company_name = get_option('invoice_company_name'); 
        $vendor = $this->get_vendor($pur_order->vendor);

        
        $address = '';
        $vendor_name = '';
        $ship_to = '';
        if($vendor){
            $address = $vendor->address;
            $vendor_name = $vendor->company;
            $ship_to = $vendor->shipping_street.'  '.$vendor->shipping_city.'  '.$vendor->shipping_state;
            if($vendor->shipping_street == '' && $vendor->shipping_city == '' && $vendor->shipping_state == ''){
                $ship_to = $address;
            }
        }

        $day = _d($pur_order->order_date);
       
        
    $html = '<table class="table">
        <tbody>
          <tr>
            <td rowspan="6" class="text-left" width="70%">
            '.get_po_logo(150).'
             <br>'.format_organization_info().'
            </td>
            <td class="text-right" width="30%">
                <strong class="fsize20">'.mb_strtoupper(_l('purchase_order')).'</strong><br>
                <strong>'.mb_strtoupper($pur_order->pur_order_number).'</strong><br>
            </td>
          </tr>

          <tr>
            <td class="text-right" width="30%">
                <br><strong>'._l('vendor').'</strong>    
                <br>'. $vendor_name.'
                <br>'. $address.'
            </td>
            <td></td>
          </tr>

          <tr>
            <td></td>
          </tr>
          <tr>
            <td class="text-right" width="30%">
                <br><strong>'._l('pur_ship_to').'</strong>    
                <br>'. $ship_to.'
            </td>
            <td></td>
          </tr>

          <tr>
            <td></td>
          </tr>
          <tr>
            <td class="text-right">'. _l('order_date').': '. $day.'</td>
            <td></td>
          </tr>

        </tbody>
      </table>
      <br><br><br>
      ';

      $html .=  '<table class="table purorder-item">
        <thead>
          <tr>
            <th class="thead-dark">'._l('items').'</th>
            <th class="thead-dark" align="right">'._l('purchase_unit_price').'</th>
            <th class="thead-dark" align="right">'._l('purchase_quantity').'</th>
         
            <th class="thead-dark" align="right">'._l('tax').'</th>
 
            <th class="thead-dark" align="right">'._l('discount').'</th>
            <th class="thead-dark" align="right">'._l('total').'</th>
          </tr>
          </thead>
          <tbody>';
        $t_mn = 0;
      foreach($pur_order_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr nobr="true" class="sortable">
            <td >'.$items->commodity_code.' - '.$items->description.'</td>
            <td align="right">'.app_format_money($row['unit_price'],'').'</td>
            <td align="right">'.$row['quantity'].'</td>
         
            <td align="right">'.app_format_money($row['total'] - $row['into_money'],'').'</td>
       
            <td align="right">'.app_format_money($row['discount_money'],'').'</td>
            <td align="right">'.app_format_money($row['total_money'],'').'</td>
          </tr>';

        $t_mn += $row['total_money'];
      }  
      $html .=  '</tbody>
      </table><br><br>';

      $html .= '<table class="table text-right"><tbody>';
      if($pur_order->discount_total > 0){
        $html .= '<tr id="subtotal">
                    <td width="33%"></td>
                     <td>'._l('subtotal').' </td>
                     <td class="subtotal">
                        '.app_format_money($t_mn,'').'
                     </td>
                  </tr>
                  <tr id="subtotal">
                  <td width="33%"></td>
                     <td>'._l('discount(%)').'(%)'.'</td>
                     <td class="subtotal">
                        '.app_format_money($pur_order->discount_percent,'').' %'.'
                     </td>
                  </tr>
                  <tr id="subtotal">
                  <td width="33%"></td>
                     <td>'._l('discount(money)').'</td>
                     <td class="subtotal">
                        '.app_format_money($pur_order->discount_total, '').'
                     </td>
                  </tr>';
      }
      $html .= '<tr id="subtotal">
                 <td width="33%"></td>
                 <td>'. _l('total').'</td>
                 <td class="subtotal">
                    '. app_format_money($pur_order->total, '').'
                 </td>
              </tr>';

      $html .= ' </tbody></table>';

      $html .= '<div class="col-md-12 mtop15">
                        <h4>'. _l('terms_and_conditions').':</h4><p>'. $pur_order->terms .'</p>
                       
                     </div>';
      $html .= '<br>';
      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') .'?v=' . PURCHASE_REVISION.'"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * clear signature
     *
     * @param      string   $id     The identifier
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function clear_signature($id)
    {
        $this->db->select('signature');
        $this->db->where('id', $id);
        $contract = $this->db->get(db_prefix() . 'pur_contracts')->row();

        if ($contract) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'pur_contracts', ['signed_status' => 'not_signed']);

            if (!empty($contract->signature)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER.'/contract_sign/' . $id . '/' . $contract->signature);
            }

            return true;
        }


        return false;
    }

    /**
     * get data Purchase statistics by cost
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function cost_of_purchase_orders_analysis($year = ''){
        if($year == ''){
            $year = date('Y');
        }
        $query = $this->db->query('SELECT DATE_FORMAT(order_date, "%m") AS month, Sum((SELECT SUM(total_money) as total FROM '.db_prefix().'pur_order_detail where pur_order = '.db_prefix().'pur_orders.id)) as total 
            FROM '.db_prefix().'pur_orders where DATE_FORMAT(order_date, "%Y") = '.$year.'
            group by month')->result_array();
        $result = [];
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $cost = [];
        $rs = 0;
        foreach ($query as $value) {
            if($value['total'] > 0){
                $result[$value['month'] - 1] =  (double)$value['total'];
            }
        }
        return $result;
    }

    /**
     * get data Purchase statistics by number of purchase orders
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function number_of_purchase_orders_analysis($year = ''){
        if($year == ''){
            $year = date('Y');
        }
        $query = $this->db->query('SELECT DATE_FORMAT(order_date, "%m") AS month, Count(*) as count 
            FROM '.db_prefix().'pur_orders where DATE_FORMAT(order_date, "%Y") = '.$year.'
            group by month')->result_array();
        $result = [];
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $cost = [];
        $rs = 0;
        foreach ($query as $value) {
            if($value['count'] > 0){
                $result[$value['month'] - 1] =  (int)$value['count'];
            }
        }
        return $result;
    }

    /**
     * Gets the payment by vendor.
     *
     * @param      <type>  $vendor  The vendor
     */
    public function get_payment_by_vendor($vendor){
        return  $this->db->query('select pop.pur_order, pop.id as pop_id, pop.amount, pop.date, pop.paymentmode, pop.transactionid, po.pur_order_name from '.db_prefix().'pur_order_payment pop left join '.db_prefix().'pur_orders po on po.id = pop.pur_order where po.vendor = '.$vendor)->result_array();
    }

/**
     * get unit add item 
     * @return array
     */
    public function get_unit_add_item()
    {
        return $this->db->query('select * from tblware_unit_type where display = 1 order by tblware_unit_type.order asc ')->result_array();
    }

    /**
     * get commodity
     * @param  boolean $id
     * @return array or object
     */
    public function get_item($id = false)
    {

        if (is_numeric($id)) {
        $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'items')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblitems')->result_array();
        }

    }
     public function get_item_data($id = false)
    {

      
            // return $this->db->query('select * from tblitems')->result_array();
            $data = $this->db->query('SELECT `ItemGroupID` FROM `itemgroups` WHERE `MainItemGroupID` = 2 ORDER BY `itemgroups`.`ItemGroupID` asc ')->result_array();
      $group_id = array();
      foreach($data as $value){

                array_push($group_id,$value['ItemGroupID']);
      }
      
      $this->db->select('id,description');
        $this->db->where_in('group_id', $group_id);
     return  $contract = $this->db->get('tblitems')->result_array();
    //   return $this->db->last_query();
     

    }

    /**
     * get inventory commodity
     * @param  integer $commodity_id 
     * @return array            
     */
    public function get_inventory_item($commodity_id){
        $sql ='SELECT '.db_prefix().'warehouse.warehouse_code, sum(inventory_number) as inventory_number, unit_name FROM '.db_prefix().'inventory_manage 
            LEFT JOIN '.db_prefix().'items on '.db_prefix().'inventory_manage.commodity_id = '.db_prefix().'items.id 
            LEFT JOIN '.db_prefix().'ware_unit_type on '.db_prefix().'items.unit_id = '.db_prefix().'ware_unit_type.unit_type_id
            LEFT JOIN '.db_prefix().'warehouse on '.db_prefix().'inventory_manage.warehouse_id = '.db_prefix().'warehouse.warehouse_id
             where commodity_id = '.$commodity_id. ' group by '.db_prefix().'inventory_manage.warehouse_id';
        return  $this->db->query($sql)->result_array();


    }

    /**
     * get warehourse attachments
     * @param  integer $commodity_id 
     * @return array               
     */
    public function get_item_attachments($commodity_id){

        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $commodity_id);
        $this->db->where('rel_type', 'commodity_item_file');

        return $this->db->get(db_prefix() . 'files')->result_array();

    }

    /**
     * generate commodity barcode
     *
     * @return     string 
     */
    public function generate_commodity_barcode(){
        $item = false;
        do{
            $length = 11;
            $chars = '0123456789';
            $count = mb_strlen($chars);
            $password = '';
            for ($i = 0; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $password .= mb_substr($chars, $index, 1);
            }
            $this->db->where('commodity_barcode',$password);
            $item = $this->db->get(db_prefix().'items')->row();
        }while ($item);

        return $password;
    }

    /**
     * add commodity one item
     * @param array $data
     * @return integer 
     */
    public function add_commodity_one_item($data){
        /*add data tblitem*/
        $data['rate'] = reformat_currency_pur($data['rate']);
        $data['purchase_price'] = reformat_currency_pur($data['purchase_price']);

        /*create sku code*/
        if($data['sku_code'] != ''){
            $data['sku_code'] = $data['sku_code'];
        }else{
            $data['sku_code'] = $this->create_sku_code('', '');
        }
        
        /*create sku code*/

        $this->db->insert(db_prefix().'items', $data);
        $insert_id = $this->db->insert_id();

        /*add data tblinventory*/
        return $insert_id;

    }


    /**
     * update commodity one item
     * @param  array $data 
     * @param  integer $id   
     * @return boolean        
     */
    public function update_commodity_one_item($data,$id){
        /*add data tblitem*/
        $data['rate'] = reformat_currency_pur($data['rate']);
        $data['purchase_price'] = reformat_currency_pur($data['purchase_price']);

        
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'items',$data);
        

        return true;
    }

    /**
     * create sku code 
     * @param  int commodity_group 
     * @param  int sub_group 
     * @return string
     */
    public function  create_sku_code($commodity_group, $sub_group)
    {
        // input  commodity group, sub group
        //get commodity group from id
        $group_character = '';
        if(isset($commodity_group)){

            $sql_group_where = 'SELECT * FROM '.db_prefix().'items_groups where id = "'.$commodity_group.'"';
            $group_value = $this->db->query($sql_group_where)->row();
            if($group_value){

                if($group_value->commodity_group_code != ''){
                    $group_character = mb_substr($group_value->commodity_group_code, 0, 1, "UTF-8").'-';

                }
            }

        }

        //get sku code from sku id
        $sub_code = '';
        



        $sql_where = 'SELECT * FROM '.db_prefix().'items order by id desc limit 1';
        $last_commodity_id = $this->db->query($sql_where)->row();
        if($last_commodity_id){
            $next_commodity_id = (int)$last_commodity_id->id + 1;
        }else{
            $next_commodity_id = 1;
        }
        $commodity_id_length = strlen((string)$next_commodity_id);

        $commodity_str_betwen ='';

        $create_candidate_code='';

        switch ($commodity_id_length) {
            case 1:
                $commodity_str_betwen = '000';
                break;
            case 2:
                $commodity_str_betwen = '00';
                break;
            case 3:
                $commodity_str_betwen = '0';
                break;

            default:
                $commodity_str_betwen = '0';
                break;
        }

 
        return  $group_character.$sub_code.$commodity_str_betwen.$next_commodity_id; // X_X_000.id auto increment

        
    }


    /**
     * get commodity group add commodity
     * @return array
     */
    public function get_commodity_group_add_commodity()
    {

        return $this->db->query('select * from tblitems_groups where display = 1 order by tblitems_groups.order asc ')->result_array();
    }
  public function get_commodity_group_add_commodity_data()
    {

        return $this->db->query('SELECT * FROM `itemgroups` WHERE `MainItemGroupID` = 2 ORDER BY `itemgroups`.`ItemGroupID` asc ')->result_array();
    }

    //delete _commodity_file file for any 
    /**
     * delete commodity file
     * @param  integer $attachment_id 
     * @return boolean                
     */
    public function delete_commodity_file($attachment_id)
    {
        $deleted    = false;
        $attachment = $this->get_commodity_attachments_delete($attachment_id);

        if ($attachment) {
            if (empty($attachment->external)) {
                if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id.'/'.$attachment->file_name)){
                    unlink(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id.'/'.$attachment->file_name);
                }else{
                    unlink('modules/warehouse/uploads/item_img/' .$attachment->rel_id.'/'.$attachment->file_name);
                }
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('commodity Attachment Deleted [commodityID: ' . $attachment->rel_id . ']');
            }
            if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id.'/'.$attachment->file_name)){
                if (is_dir(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id)) {
                    // Check if no attachments left, so we can delete the folder also
                    $other_attachments = list_files(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id);
                    if (count($other_attachments) == 0) {
                        // okey only index.html so we can delete the folder also
                        delete_dir(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id);
                    }
                }
            }else{
                if (is_dir(site_url('modules/warehouse/uploads/item_img/') .$attachment->rel_id)) {
                    // Check if no attachments left, so we can delete the folder also
                    $other_attachments = list_files(site_url('modules/warehouse/uploads/item_img/') .$attachment->rel_id);
                    if (count($other_attachments) == 0) {
                        // okey only index.html so we can delete the folder also
                        delete_dir(site_url('modules/warehouse/uploads/item_img/') .$attachment->rel_id);
                    }
                }
            }
        }

        return $deleted;
    }

    /**
     * get commodity attachments delete
     * @param  integer $id 
     * @return object     
     */
    public function get_commodity_attachments_delete($id){

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'files')->row();
        }
    }

    /**
     * get unit type
     * @param  boolean $id
     * @return array or object
     */
    public function get_unit_type($id = false)
    {

        if (is_numeric($id)) {
        $this->db->where('unit_type_id', $id);

            return $this->db->get(db_prefix() . 'ware_unit_type')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblware_unit_type')->result_array();
        }

    }

    /**
     * add unit type 
     * @param array  $data
     * @param boolean $id
     * return boolean
     */
    public function add_unit_type($data, $id = false){
        
        $unit_type = str_replace(', ','|/\|',$data['hot_unit_type']);
        $data_unit_type = explode( ',', $unit_type);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        
        foreach ($data_unit_type as  $unit_type_key => $unit_type_value) {
            if($unit_type_value == ''){
                    $unit_type_value = 0;
                }
            if(($unit_type_key+1)%6 == 0){
                $arr_temp['note'] = str_replace('|/\|',', ',$unit_type_value);
                
                if($id == false && $flag_empty == 1){
                    $this->db->insert(db_prefix().'ware_unit_type', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if($insert_id){
                        $results++;
                    }
                }
                if(is_numeric($id) && $flag_empty == 1){
                    $this->db->where('unit_type_id', $id);
                    $this->db->update(db_prefix() . 'ware_unit_type', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    }else{
                        $results_update = false;
                    }
                }
                $flag_empty =0;
                $arr_temp = [];
            }else{

                switch (($unit_type_key+1)%6) {
                    case 1:
                     $arr_temp['unit_code'] = str_replace('|/\|',', ',$unit_type_value);

                        if($unit_type_value != '0'){
                            $flag_empty = 1;
                        }
                        break;
                    case 2:
                    $arr_temp['unit_name'] = str_replace('|/\|',', ',$unit_type_value);
                        break;
                    case 3:
                    $arr_temp['unit_symbol'] = $unit_type_value;
                        break;
                    case 4:
                    $arr_temp['order'] = $unit_type_value;
                        break;
                     case 5:
                     if($unit_type_value == 'yes'){
                        $display_value = 1;
                     }else{
                        $display_value = 0;
                     }
                    $arr_temp['display'] = $display_value;
                        break;
                }
            }

        }

        if($id == false){
            return $results > 0 ? true : false;
        }else{
            return $results_update ;
        }

    }

    /**
     * delete unit type
     * @param  integer $id
     * @return boolean
     */
    public function delete_unit_type($id){
        $this->db->where('unit_type_id', $id);
        $this->db->delete(db_prefix() . 'ware_unit_type');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * delete commodity
     * @param  integer $id
     * @return boolean
     */
        public function delete_commodity($id){
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { mark converted pur order }
     *
     * @param      <int>  $pur_order  The pur order
     * @param      <int>  $expense    The expense
     */
    public function mark_converted_pur_order($pur_order, $expense){
        $this->db->where('id',$pur_order);
        $this->db->update(db_prefix().'pur_orders',['expense_convert' => $expense]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { delete purchase vendor attachment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_ic_attachment($id)
    {
        $attachment = $this->get_ic_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Gets the ic attachments.
     *
     * @param      <type>  $assets  The assets
     * @param      string  $id      The identifier
     *
     * @return     <type>  The ic attachments.
     */
    public function get_ic_attachments($assets, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_vendor');
        $result = $this->db->get('tblfiles');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * Change contact password, used from client area
     * @param  mixed $id          contact id to change password
     * @param  string $oldPassword old password to verify
     * @param  string $newPassword new password
     * @return boolean
     */
    public function change_contact_password($id, $oldPassword, $newPassword)
    {
        // Get current password
        $this->db->where('id', $id);
        $client = $this->db->get(db_prefix() . 'pur_contacts')->row();

        if (!app_hasher()->CheckPassword($oldPassword, $client->password)) {
            return [
                'old_password_not_match' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', [
            'last_password_change' => date('Y-m-d H:i:s'),
            'password'             => app_hash_password($newPassword),
        ]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Contact Password Changed [ContactID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Gets the pur order by vendor.
     *
     * @param      <type>  $vendor  The vendor
     */
    public function get_pur_order_by_vendor($vendor){
        $this->db->where('vendor',$vendor);
        return $this->db->get(db_prefix().'pur_orders')->result_array();
    }

    public function get_contracts_by_vendor($vendor){
        $this->db->where('vendor',$vendor);
        return $this->db->get(db_prefix().'pur_contracts')->result_array();
    }

    /**
     * @param  integer ID
     * @param  integer Status ID
     * @return boolean
     * Update contact status Active/Inactive
     */
    public function change_contact_status($id, $status)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', [
            'active' => $status,
        ]);
        if ($this->db->affected_rows() > 0) {
            
            return true;
        }

        return false;
    }

    /**
     * Gets the item by group.
     *
     * @param        $group  The group
     *
     * @return      The item by group.
     */
    public function get_item_by_group($group){
        $this->db->where('group_id',$group);
        return $this->db->get(db_prefix().'items')->result_array();
    }  

    /**
     * Adds vendor items.
     *
     * @param      $data   The data
     *
     * @return     boolean 
     */
    public function add_vendor_items($data){
        $rs = 0;
        $data['add_from'] = get_staff_user_id();
        $data['datecreate'] = date('Y-m-d');
        foreach($data['items'] as $val){
            $this->db->insert(db_prefix().'pur_vendor_items',[
                'vendor' => $data['vendor'],
                'group_items' => $data['group_item'],
                'items' => $val,
                'add_from' => $data['add_from'],
                'datecreate' => $data['datecreate'],
            ]);
            $insert_id = $this->db->insert_id();

            if($insert_id){
                $rs++;
            }
        }

        if($rs > 0){
            return true;
        }
        return false;
    } 

    /**
     * { delete vendor items }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_vendor_items($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_vendor_items');
        if ($this->db->affected_rows() > 0) {
            
            return true;
        }
        return false;
    }

    /**
     * Gets the item by vendor.
     *
     * @param      $vendor  The vendor
     */
    public function get_item_by_vendor($vendor){
        
        $this->db->where('vendor',$vendor);
        return $this->db->get(db_prefix().'pur_vendor_items')->result_array();  
    }

    /**
     * Gets the items.
     *
     * @return     <array>  The items.
     */
    public function get_items_hs_vendor($vendor){
       return $this->db->query('select items as id, CONCAT(it.commodity_code," - " ,it.description) as label from '.db_prefix().'pur_vendor_items pit LEFT JOIN '.db_prefix().'items it ON it.id = pit.items where pit.vendor = '.$vendor)->result_array();
    }

    /**
     * get commodity group type
     * @param  boolean $id
     * @return array or object
     */
    public function get_commodity_group_type($id = false) {

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'items_groups')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblitems_groups')->result_array();
        }

    }

    /**
     * add commodity group type
     * @param array  $data
     * @param boolean $id
     * return boolean
     */
    public function add_commodity_group_type($data, $id = false) {
        $data['commodity_group'] = str_replace(', ', '|/\|', $data['hot_commodity_group_type']);

        $data_commodity_group_type = explode(',', $data['commodity_group']);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        foreach ($data_commodity_group_type as $commodity_group_type_key => $commodity_group_type_value) {
            if ($commodity_group_type_value == '') {
                $commodity_group_type_value = 0;
            }
            if (($commodity_group_type_key + 1) % 5 == 0) {

                $arr_temp['note'] = str_replace('|/\|', ', ', $commodity_group_type_value);

                if ($id == false && $flag_empty == 1) {
                    $this->db->insert(db_prefix() . 'items_groups', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if ($insert_id) {
                        $results++;
                    }
                }
                if (is_numeric($id) && $flag_empty == 1) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'items_groups', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    } else {
                        $results_update = false;
                    }
                }

                $flag_empty = 0;
                $arr_temp = [];
            } else {

                switch (($commodity_group_type_key + 1) % 5) {
                case 1:
                    if(is_numeric($id)){
                        //update
                        $arr_temp['commodity_group_code'] = str_replace('|/\|', ', ', $commodity_group_type_value);
                            $flag_empty = 1;

                    }else{
                        //add
                        $arr_temp['commodity_group_code'] = str_replace('|/\|', ', ', $commodity_group_type_value);

                        if ($commodity_group_type_value != '0') {
                            $flag_empty = 1;
                        }
                        
                    }
                    break;
                case 2:
                    $arr_temp['name'] = str_replace('|/\|', ', ', $commodity_group_type_value);
                    break;
                case 3:
                    $arr_temp['order'] = $commodity_group_type_value;
                    break;
                case 4:
                    //display 1: display (yes) , 0: not displayed (no)
                    if ($commodity_group_type_value == 'yes') {
                        $display_value = 1;
                    } else {
                        $display_value = 0;
                    }
                    $arr_temp['display'] = $display_value;
                    break;
                }
            }

        }

        if ($id == false) {
            return $results > 0 ? true : false;
        } else {
            return $results_update;
        }

    }

    /**
     * delete commodity group type
     * @param  integer $id
     * @return boolean
     */
    public function delete_commodity_group_type($id) {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items_groups');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * get sub group
     * @param  boolean $id
     * @return array  or object
     */
    public function get_sub_group($id = false) {

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'wh_sub_group')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblwh_sub_group')->result_array();
        }

    }

    /**
     * get item group
     * @return array 
     */
    public function get_item_group() {
        return $this->db->query('select id as id, CONCAT(name,"_",commodity_group_code) as label from ' . db_prefix() . 'items_groups')->result_array();
    }

    /**
     * add sub group
     * @param array  $data
     * @param boolean $id
     * @return boolean
     */
    public function add_sub_group($data, $id = false) {
        $commodity_type = str_replace(', ', '|/\|', $data['hot_sub_group']);

        $data_commodity_type = explode(',', $commodity_type);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        foreach ($data_commodity_type as $commodity_type_key => $commodity_type_value) {
            if ($commodity_type_value == '') {
                $commodity_type_value = 0;
            }
            if (($commodity_type_key + 1) % 6 == 0) {
                $arr_temp['note'] = str_replace('|/\|', ', ', $commodity_type_value);

                if ($id == false && $flag_empty == 1) {
                    $this->db->insert(db_prefix() . 'wh_sub_group', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if ($insert_id) {
                        $results++;
                    }
                }
                if (is_numeric($id) && $flag_empty == 1) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'wh_sub_group', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    } else {
                        $results_update = false;
                    }
                }
                $flag_empty = 0;
                $arr_temp = [];
            } else {

                switch (($commodity_type_key + 1) % 6) {
                case 1:
                    $arr_temp['sub_group_code'] = str_replace('|/\|', ', ', $commodity_type_value);
                    if ($commodity_type_value != '0') {
                        $flag_empty = 1;
                    }
                    break;
                case 2:
                    $arr_temp['sub_group_name'] = str_replace('|/\|', ', ', $commodity_type_value);
                    break;
                case 3:
                    $arr_temp['group_id'] = $commodity_type_value;
                    break;
                case 4:
                    $arr_temp['order'] = $commodity_type_value;
                    break;
                case 5:
                    //display 1: display (yes) , 0: not displayed (no)
                    if ($commodity_type_value == 'yes') {
                        $display_value = 1;
                    } else {
                        $display_value = 0;
                    }
                    $arr_temp['display'] = $display_value;
                    break;
                }
            }

        }

        if ($id == false) {
            return $results > 0 ? true : false;
        } else {
            return $results_update;
        }

    }

    /**
     * delete_sub_group
     * @param  integer $id
     * @return boolean
     */
    public function delete_sub_group($id) {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'wh_sub_group');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * list subgroup by group
     * @param  integer $group 
     * @return string        
     */
    public function list_subgroup_by_group($group)
    {
        $this->db->where('group_id', $group);
        $arr_subgroup = $this->db->get(db_prefix().'wh_sub_group')->result_array();

        $options = '';
        if(count($arr_subgroup) > 0){
            foreach ($arr_subgroup as $value) {

              $options .= '<option value="' . $value['id'] . '">' . $value['sub_group_name'] . '</option>';
            }

        }
        return $options;

    }

    /**
     * get item tag filter
     * @return array 
     */
    public function get_item_tag_filter()
    {
        return $this->db->query('select * FROM '.db_prefix().'taggables left join '.db_prefix().'tags on '.db_prefix().'taggables.tag_id =' .db_prefix().'tags.id where '.db_prefix().'taggables.rel_type = "pur_order"')->result_array();
    }

    /**
     * Gets the pur contract attachment.
     *
     * @param        $id     The identifier
     */
    public function get_pur_contract_attachment($id){
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_contract');
        return $this->db->get(db_prefix().'files')->result_array();
    }

    /**
     * Gets the pur contract attachments.
     *
     * @param        $assets  The assets
     * @param      string  $id      The identifier
     *
     * @return       The pur contract attachments.
     */
    public function get_pur_contract_attachments($assets, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_contract');
        $result = $this->db->get(db_prefix().'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * { delete purchase contract attachment }
     *
     * @param         $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_pur_contract_attachment($id)
    {
        $attachment = $this->get_pur_contract_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix().'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Adds a vendor category.
     *
     * @param         $data   The data
     *
     * @return     id inserted 
     */
    public function add_vendor_category($data){
        $this->db->insert(db_prefix().'pur_vendor_cate',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return $insert_id;
        }
        return false;
    }

    /**
     * { update vendor category }
     *
     * @param         $data   The data
     * @param        $id     The identifier
     *
     * @return     boolean   
     */
    public function update_vendor_category($data,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_vendor_cate',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { delete vendor category }
     *
     * @param         $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_vendor_category($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_vendor_cate');
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the vendor category.
     *
     * @param      string  $id     The identifier
     *
     * @return       The vendor category.
     */
    public function get_vendor_category($id = ''){
        if($id != ''){
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_vendor_cate')->row();
        }else{
            return $this->db->get(db_prefix().'pur_vendor_cate')->result_array();
        }
    }

    /**
     * Gets the purchase estimate attachments.
     *
     * @param        $id     The purchase estimate
     *
     * @return       The purchase estimate attachments.
     */
    public function get_purchase_estimate_attachments($id){
   
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_estimate');
        return $this->db->get(db_prefix().'files')->result_array();
    }

    /**
     * Gets the purcahse estimate attachments.
     *
     * @param      <type>  $surope  The surope
     * @param      string  $id      The identifier
     *
     * @return     <type>  The part attachments.
     */
    public function get_estimate_attachments($surope, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_estimate');
        $result = $this->db->get(db_prefix().'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * { delete estimate attachment }
     *
     * @param         $id     The identifier
     *
     * @return     boolean 
     */
    public function delete_estimate_attachment($id)
    {
        $attachment = $this->get_estimate_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_estimate/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_estimate/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_estimate/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_estimate/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * { update customfield po }
     *
     * @param        $id     The identifier
     * @param        $data   The data
     */
    public function update_customfield_po($id, $data){

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                return true;
            }
        }
        return false;
    }

    /**
     * { PO voucher pdf }
     *
     * @param        $po_voucher  The Purchase order voucher
     *
     * @return      ( pdf )
     */
    public function povoucher_pdf($po_voucher)
    {
        return app_pdf('po_voucher', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Po_voucher_pdf'), $po_voucher);
    }

    /**
     * Gets the po voucher pdf html.
     *
     *
     *
     * @return     string  The request quotation pdf html.
     */
    public function get_po_voucher_html(){
        $this->load->model('departments_model');

        $po_voucher = $this->db->get(db_prefix().'pur_orders')->result_array();
        

        $company_name = get_option('invoice_company_name'); 
        
        $address = get_option('invoice_company_address'); 
        $day = date('d');
        $month = date('m');
        $year = date('Y');


    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('purchase_company_name').': '. $company_name.'</td>
            <td rowspan="2" width="" class="text-right">'.get_po_logo().'</td>
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
          </tr>
         
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('po_voucher')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table><br><br><br>';

      $html .=  '<table class="table pur_request-item">
            <thead>
              <tr class="border_tr">
                <th align="left" class="thead-dark">'._l('purchase_order').'</th>
                <th  class="thead-dark">'._l('date').'</th>
                <th class="thead-dark">'._l('type').'</th>
                <th class="thead-dark">'._l('project').'</th>
                <th class="thead-dark">'._l('department').'</th>
                <th class="thead-dark">'._l('vendor').'</th>
                <th class="thead-dark">'._l('approval_status').'</th>
                <th class="thead-dark">'._l('delivery_status').'</th>
                <th class="thead-dark">'._l('payment_status').'</th>
              </tr>
            </thead>
          <tbody>';

      $tmn = 0;    
      foreach($po_voucher as $row){
        $paid = $row['total'] - purorder_left_to_pay($row['id']);
        $percent = 0;
        if($row['total'] > 0){
            $percent = ($paid / $row['total'] ) * 100;
        }

        $delivery_status = '';
        if($row['delivery_status'] == 0){
            $delivery_status = _l('undelivered');
        }else{
            $delivery_status = _l('delivered');
        }

        $project_name = '';
        $department_name = '';
        $vendor_name = get_vendor_company_name($row['vendor']);

        $project = $this->projects_model->get($row['project']);
        $department = $this->departments_model->get($row['department']);
        if($project){
            $project_name = $project->name;
        }

        if($department){
            $department_name = $department->name;
        }

        $html .= '<tr>
            <td>'.$row['pur_order_number'].'</td>
            <td>'._d($row['order_date']).'</td>
            <td>'._l($row['type']).'</td>
            <td>'.$project_name.'</td>
            <td>'.$department_name.'</td>
            <td>'.$vendor_name.'</td>
            <td>'.get_status_approve($row['approve_status']).'</td>
            <td>'.$delivery_status.'</td>
            <td align="right">'.$percent.'%</td>
          </tr>';
       
      }  
      $html .=  '</tbody>
      </table><br><br>';


      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * Adds a pur invoice.
     *
     * @param        $data   The data
     */
    public function add_pur_invoice($data){
        $data['add_from'] = get_staff_user_id();
        $data['date_add'] = date('Y-m-d');
        $data['payment_status'] = 'unpaid';
        $prefix = get_purchase_option('pur_inv_prefix');

        $this->db->where('invoice_number',$data['invoice_number']);
        $check_exist_number = $this->db->get(db_prefix().'pur_invoices')->row();

        while($check_exist_number) {
          $data['number'] = $data['number'] + 1;
          $data['invoice_number'] =  $prefix.str_pad($data['number'],5,'0',STR_PAD_LEFT);
          $this->db->where('invoice_number',$data['invoice_number']);
          $check_exist_number = $this->db->get(db_prefix().'pur_invoices')->row();
        }

        $data['invoice_date'] = to_sql_date($data['invoice_date']);
        $data['transaction_date'] = to_sql_date($data['transaction_date']);
        $data['subtotal'] = reformat_currency_pur($data['subtotal']);
        $data['tax'] = reformat_currency_pur($data['subtotal']);
        $data['total'] = reformat_currency_pur($data['total']);

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $this->db->insert(db_prefix().'pur_invoices',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            $next_number = $data['number']+1;
            $this->db->where('option_name', 'next_inv_number');
            $this->db->update(db_prefix() . 'purchase_option',['option_val' =>  $next_number,]);

            handle_tags_save($tags, $insert_id, 'pur_invoice');

            return $insert_id;
        }
        return false;
    }

    /**
     * { update pur invoice }
     *
     * @param        $id     The identifier
     * @param        $data   The data
     */
    public function update_pur_invoice($id,$data){
        $data['invoice_date'] = to_sql_date($data['invoice_date']);
        $data['transaction_date'] = to_sql_date($data['transaction_date']);
        $data['subtotal'] = reformat_currency_pur($data['subtotal']);
        $data['tax'] = reformat_currency_pur($data['subtotal']);
        $data['total'] = reformat_currency_pur($data['total']);

        if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'pur_invoice')) {
                $affectedRows++;
            }
            unset($data['tags']);
        }

        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_invoices',$data);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the pur invoice.
     *
     * @param      string  $id     The identifier
     *
     * @return       The pur invoice.
     */
    public function get_pur_invoice($id = ''){
        if($id != ''){
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_invoices')->row();
        }else{
            return $this->db->get(db_prefix().'pur_invoices')->result_array();
        }
    }

    /**
     * { delete pur invoice }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_pur_invoice($id){
        $this->db->where('rel_type','pur_invoice');
        $this->db->where('rel_id', $id);
        $this->db->delete(db_prefix().'taggables');

        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_invoices');
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the payment invoice.
     *
     * @param        $invoice  The invoice
     *
     * @return       The payment invoice.
     */
    public function get_payment_invoice($invoice){
        $this->db->where('pur_invoice',$invoice);
        return $this->db->get(db_prefix().'pur_invoice_payment')->result_array();
    }

    /**
     * Adds a invoice payment.
     *
     * @param         $data       The data
     * @param         $invoice  The invoice id
     *
     * @return     boolean  
     */
    public function add_invoice_payment($data, $invoice){
        $data['date'] = to_sql_date($data['date']);
        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data['amount'] = str_replace(',', '', $data['amount']);
        $data['pur_invoice'] = $invoice;
        $data['approval_status'] = 1;
        $data['requester'] = get_staff_user_id();
        $check_appr = $this->get_approve_setting('payment_request');
        if($check_appr && $check_appr != false){
            $data['approval_status'] = 1;
        }else{
            $data['approval_status'] = 2;
        }

        $this->db->insert(db_prefix().'pur_invoice_payment',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){

            if($data['approval_status'] == 2){
                $pur_invoice = $this->get_pur_invoice($invoice);
                if($pur_invoice){
                    $status_inv = $pur_invoice->payment_status;
                    if(purinvoice_left_to_pay($invoice) > 0){
                        $status_inv = 'partially_paid';
                    }else{
                        $status_inv = 'paid';
                    }
                    $this->db->where('id',$invoice);
                    $this->db->update(db_prefix().'pur_invoices', [ 'payment_status' => $status_inv, ]);
                }
            }

            return $insert_id;
        }
        return false;
    }

    /**
     * { delete invoice payment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  ( delete payment )
     */
    public function delete_payment_pur_invoice($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_invoice_payment');
        if ($this->db->affected_rows() > 0) {
                return true;
        }
        return false;
    }

    /**
     * Gets the payment pur invoice.
     *
     * @param      string  $id     The identifier
     */
    public function get_payment_pur_invoice($id = ''){
        if($id != ''){
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_invoice_payment')->row();
        }else{
            return $this->db->get(db_prefix().'pur_invoice_payment')->result_array();
        }
    }

    /**
     * { update invoice after approve }
     *
     * @param        $id     The identifier
     */
    public function update_invoice_after_approve($id){
        $payment = $this->get_payment_pur_invoice($id);

        if($payment){
            $pur_invoice = $this->get_pur_invoice($payment->pur_invoice);
            if($pur_invoice){
                $status_inv = $pur_invoice->payment_status;
                if(purinvoice_left_to_pay($payment->pur_invoice) > 0){
                    $status_inv = 'partially_paid';
                }else{
                    $status_inv = 'paid';
                }
                $this->db->where('id',$payment->pur_invoice);
                $this->db->update(db_prefix().'pur_invoices', [ 'payment_status' => $status_inv, ]);
            }
        }
    }

     /**
     * Gets the purchase order attachments.
     *
     * @param      <type>  $id     The purchase order
     *
     * @return     <type>  The purchase order attachments.
     */
    public function get_purchase_invoice_attachments($id){
   
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_invoice');
        return $this->db->get(db_prefix().'files')->result_array();
    }

    /**
     * Gets the inv attachments.
     *
     * @param      <type>  $surope  The surope
     * @param      string  $id      The identifier
     *
     * @return     <type>  The part attachments.
     */
    public function get_purinv_attachments($surope, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_invoice');
        $result = $this->db->get(db_prefix().'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * { delete purchase invoice attachment }
     *
     * @param         $id     The identifier
     *
     * @return     boolean 
     */
    public function delete_purinv_attachment($id)
    {
        $attachment = $this->get_purinv_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_invoice/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_invoice/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_invoice/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_invoice/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Gets the payment by contract.
     *
     * @param        $id     The identifier
     */
    public function get_payment_by_contract($id){
        return $this->db->query('select * from '.db_prefix().'pur_invoice_payment where pur_invoice IN ( select id from '.db_prefix().'pur_invoices where contract = '.$id.' )')->result_array();
    }

    /**
     * { purestimate pdf }
     *
     * @param        $pur_request  The pur request
     *
     * @return       ( purorder pdf )
     */
    public function purestimate_pdf($pur_estimate,$id)
    {
        return app_pdf('pur_estimate', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_estimate_pdf'), $pur_estimate,$id);
    }


    /**
     * Gets the pur request pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The pur request pdf html.
     */
    public function get_purestimate_pdf_html($pur_estimate_id){
        

        $pur_estimate = $this->get_estimate($pur_estimate_id);
        $pur_estimate_detail = $this->get_pur_estimate_detail($pur_estimate_id);
        $company_name = get_option('invoice_company_name'); 
        
        $address = get_option('invoice_company_address'); 
        $day = date('d',strtotime($pur_estimate->date));
        $month = date('m',strtotime($pur_estimate->date));
        $year = date('Y',strtotime($pur_estimate->date));
        
    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('purchase_company_name').': '. $company_name.'</td>
            <td rowspan="2" width="" class="text-right">'.get_po_logo().'</td>
            
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
            <td></td>
            
          </tr>
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('estimate')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            <td class="td_width_25"><h4>'. _l('add_from').':</h4></td>
            <td class="td_width_75">'. get_staff_full_name($pur_estimate->addedfrom).'</td>
          </tr>
          <tr>
            <td class="td_width_25"><h4>'. _l('vendor').':</h4></td>
            <td class="td_width_75">'. get_vendor_company_name($pur_estimate->vendor->userid).'</td>
          </tr>
          
        </tbody>
      </table>

      <h3>
       '. html_entity_decode(format_pur_estimate_number($pur_estimate_id)).'
       </h3>
      <br><br>
      ';

      $html .=  '<table class="table purorder-item">
        <thead>
          <tr>
            <th class="thead-dark">'._l('items').'</th>
            <th class="thead-dark" align="right">'._l('purchase_unit_price').'</th>
            <th class="thead-dark" align="right">'._l('purchase_quantity').'</th>
         
            <th class="thead-dark" align="right">'._l('tax').'</th>
 
            <th class="thead-dark" align="right">'._l('discount').'</th>
            <th class="thead-dark" align="right">'._l('total').'</th>
          </tr>
          </thead>
          <tbody>';
        $t_mn = 0;
      foreach($pur_estimate_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr nobr="true" class="sortable">
            <td >'.$items->commodity_code.' - '.$items->description.'</td>
            <td align="right">'.app_format_money($row['unit_price'],'').'</td>
            <td align="right">'.$row['quantity'].'</td>
         
            <td align="right">'.app_format_money($row['total'] - $row['into_money'],'').'</td>
       
            <td align="right">'.app_format_money($row['discount_money'],'').'</td>
            <td align="right">'.app_format_money($row['total_money'],'').'</td>
          </tr>';

        $t_mn += $row['total_money'];
      }  
      $html .=  '</tbody>
      </table><br><br>';

      $html .= '<table class="table text-right"><tbody>';
      if($pur_estimate->discount_total > 0){
        $html .= '<tr id="subtotal">
                    <td width="33%"></td>
                     <td>'._l('subtotal').' </td>
                     <td class="subtotal">
                        '.app_format_money($t_mn,'').'
                     </td>
                  </tr>
                  <tr id="subtotal">
                  <td width="33%"></td>
                     <td>'._l('discount(%)').'(%)'.'</td>
                     <td class="subtotal">
                        '.app_format_money($pur_estimate->discount_percent,'').' %'.'
                     </td>
                  </tr>
                  <tr id="subtotal">
                  <td width="33%"></td>
                     <td>'._l('discount(money)').'</td>
                     <td class="subtotal">
                        '.app_format_money($pur_estimate->discount_total, '').'
                     </td>
                  </tr>';
      }
      $html .= '<tr id="subtotal">
                 <td width="33%"></td>
                 <td>'. _l('total').'</td>
                 <td class="subtotal">
                    '. app_format_money($pur_estimate->total, '').'
                 </td>
              </tr>';

      $html .= ' </tbody></table>';

      $html .= '<div class="col-md-12 mtop15">
                        <h4>'. _l('terms_and_conditions').': </h4><p>'. html_entity_decode($pur_estimate->terms).'</p>
                       
                     </div>';
      $html .= '<br>
      <br>
      <br>
      <br>';
      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * Sends a quotation.
     *
     * @param         $data   The data
     *
     * @return     boolean
     */
    public function send_quotation($data){
        $staff_id = get_staff_user_id();

        $inbox = array();

        $inbox['to'] = implode(',',$data['email']);
        $inbox['sender_name'] = get_staff_full_name($staff_id);
        $inbox['subject'] = _strip_tags($data['subject']);
        $inbox['body'] = _strip_tags($data['content']);        
        $inbox['body'] = nl2br_save_html($inbox['body']);
        $inbox['date_received']      = date('Y-m-d H:i:s');
        $inbox['from_email'] = get_option('smtp_email');
        
        if(strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0){

            $ci = &get_instance();
            $ci->email->initialize();
            $ci->load->library('email');    
            $ci->email->clear(true);
            $ci->email->from($inbox['from_email'], $inbox['sender_name']);
            $ci->email->to($inbox['to']);
            
            $ci->email->subject($inbox['subject']);
            $ci->email->message($inbox['body']);
            
            $attachment_url = site_url(PURCHASE_PATH.'send_quotation/'.$data['pur_estimate_id'].'/'.str_replace(" ", "_", $_FILES['attachment']['name']));
            $ci->email->attach($attachment_url);
            return $ci->email->send(true);
        }
        
        return false;
    }

    /**
     * Sends a purchase order.
     *
     * @param         $data   The data
     *
     * @return     boolean
     */
    public function send_po($data){
        $staff_id = get_staff_user_id();

        $inbox = array();

        $inbox['to'] = implode(',',$data['email']);
        $inbox['sender_name'] = get_staff_full_name($staff_id);
        $inbox['subject'] = _strip_tags($data['subject']);
        $inbox['body'] = _strip_tags($data['content']);        
        $inbox['body'] = nl2br_save_html($inbox['body']);
        $inbox['date_received']      = date('Y-m-d H:i:s');
        $inbox['from_email'] = get_option('smtp_email');
        
        if(strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0){

            $ci = &get_instance();
            $ci->email->initialize();
            $ci->load->library('email');    
            $ci->email->clear(true);
            $ci->email->from($inbox['from_email'], $inbox['sender_name']);
            $ci->email->to($inbox['to']);
            
            $ci->email->subject($inbox['subject']);
            $ci->email->message($inbox['body']);
            
            $attachment_url = site_url(PURCHASE_PATH.'send_po/'.$data['po_id'].'/'.str_replace(" ", "_", $_FILES['attachment']['name']));
            $ci->email->attach($attachment_url);
            return $ci->email->send(true);
        }
        
        return false;
    }

    /**
     * import xlsx commodity
     * @param  array $data
     * @return integer
     */
    public function import_xlsx_commodity($data) {
        if($data['commodity_barcode'] != ''){
            $data['commodity_barcode'] = $data['commodity_barcode'];
        }else{
            $data['commodity_barcode'] = $this->generate_commodity_barcode();
        }
        
        
        /*create sku code*/
        if($data['sku_code'] != ''){
            $data['sku_code'] = str_replace(' ', '', $data['sku_code']) ;
        }else{
            //data sku_code = group_character.sub_code.commodity_str_betwen.next_commodity_id; // X_X_000.id auto increment
            $data['sku_code'] = $this->create_sku_code($data['group_id'], isset($data['sub_group']) ? $data['sub_group'] : '' );
            /*create sku code*/
        }

        if(get_warehouse_option('barcode_with_sku_code') == 1){
            $data['commodity_barcode'] = $data['sku_code'];
        }
        
        /*check update*/

        $item = $this->db->query('select * from tblitems where commodity_code = "'.$data['commodity_code'].'"')->row();

        if($item){
            //check sku code dulicate
            if($this->check_sku_duplicate(['sku_code' => $data['sku_code'], 'item_id' => $item->id]) == false){
                return false;
            }

            if(isset($data['tags'])){
                $tags_value =  $data['tags'];
                unset($data['tags']);
            }else{
                $tags_value ='';
            }

            foreach ($data as $key => $data_value) {
                if(!isset($data_value)){
                    unset($data[$key]);
                }
            }

            $minimum_inventory = 0;
            if(isset($data['minimum_inventory'])){
                $minimum_inventory = $data['minimum_inventory'];
                 unset($data['minimum_inventory']);
            }

            //update
            $this->db->where('commodity_code', $data['commodity_code']);
            $this->db->update(db_prefix() . 'items', $data);

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }else{
            //check sku code dulicate
            if($this->check_sku_duplicate(['sku_code' => $data['sku_code'], 'item_id' => '']) == false){
                return false;
            }

            $sku_prefix = '';

            if (function_exists('get_warehouse_option')) {
                $sku_prefix = get_warehouse_option('item_sku_prefix');
            }

            $data['sku_code'] = $sku_prefix.$data['sku_code'];

            //insert
            $this->db->insert(db_prefix() . 'items', $data);
            $insert_id = $this->db->insert_id();

            return $insert_id;
        }
    }

    /**
     * check sku duplicate
     * @param  [type] $data 
     * @return [type]       
     */
    public function check_sku_duplicate($data)
    {   
        if(isset($data['item_id'])){
        //check update
            $this->db->where('sku_code', $data['sku_code']);
            $this->db->where('id != ', $data['item_id']);

            $items = $this->db->get(db_prefix() . 'items')->result_array();

            if(count($items) > 0){
                return false;
            }
            return true;

        }elseif(isset($data['sku_code'])){
        //check insert
            $this->db->where('sku_code', $data['sku_code']);
            $items = $this->db->get(db_prefix() . 'items')->row();
            if($items){
                return false;
            }
            return true;
        }

        return true;

    }

    public function remove_po_logo(){

        $this->db->where('rel_id', 0);
        $this->db->where('rel_type', 'po_logo');
        $avar = $this->db->get(db_prefix() . 'files')->row();

        if ($avar) {
            if (empty($avar->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id . '/' . $avar->file_name);
            }
            $this->db->where('id', $avar->id);
            $this->db->delete('tblfiles');

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id)) {
                // Check if no avars left, so we can delete the folder also
                $other_avars = list_files(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id);
                if (count($other_avars) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER . '/po_logo/' . $avar->rel_id);
                }
            }
        }

        return true;
    }
    public function get_Order_list(){
         $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select();
        $this->db->from(db_prefix() . 'purchasemaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasemaster.AccountID', 'left');
        //  $this->db->where(db_prefix() . 'clients.userid', $id);
       $this->db->where(db_prefix() . 'purchasemaster.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'purchasemaster.FY', $year);
       $this->db->order_by(db_prefix() . 'purchasemaster.PurchID', "DESC");
       return $this->db->get()->result_array();
    }
    
    public function load_data_for_purchase($data)
     {  
         $from_date = to_sql_date($data["from_date"]);
         $to_date = to_sql_date($data["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '('.db_prefix().'purchasemaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'purchasemaster.FY = "'.$fy.'" AND '.db_prefix().'purchasemaster.PlantID = "'.$selected_company.'" ORDER BY PurchID ASC';
        
        $sql ='SELECT '.db_prefix().'purchasemaster.*,  
        (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'purchasemaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as AccountName
        FROM '.db_prefix().'purchasemaster WHERE '.$sql1;
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
     public function get_unique_purchasemaster($id){
         $selected_company = $this->session->userdata('root_company');
      $year = $this->session->userdata('finacial_year');
       $this->db->select();
        $this->db->from(db_prefix() . 'purchasemaster');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasemaster.AccountID', 'left');
       $this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
        $this->db->join(db_prefix() . 'accountbalances', db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND '.db_prefix() . 'accountbalances.FY ="'.$year.'"', 'left');
         $this->db->where(db_prefix() . 'purchasemaster.PurchID', $id);
       $this->db->where(db_prefix() . 'purchasemaster.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'purchasemaster.FY', $year);
       return $this->db->get()->row();
    }
    
    public function get_unique_history($id){
         $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select();
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID', 'left');
        // $this->db->join(db_prefix() . 'history', db_prefix() . 'history.OrderID = ' . db_prefix() . 'purchasemaster.PurchID', 'left');
         $this->db->where(db_prefix() . 'history.OrderID', $id);
       $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'history.FY', $year);
       return $this->db->get()->result_array();
    }
    
    
    public function update_purchase_order($data,$id){
        
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        $fy = $this->session->userdata('finacial_year');
        
        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'pur_unit';
            $header[] = 'CaseQty';
            $header[] = 'PurchRate';
            $header[] = 'Cases';
            $header[] = 'QTY';
            $header[] = 'disc';
            $header[] = 'DiscAmt';
            $header[] = 'GST';
            $header[] = 'CGSTAMT';
            $header[] = 'SGSTAMT';
            $header[] = 'IGSTAMT';
            $header[] = 'total_money';
            
            foreach ($pur_order_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        $old_pur_details = $this->purchase_model->get_purchase_detail($id);
        
        $acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
        $back_ItCount =  $this->db->select('*')->get_where(db_prefix().'purchasemaster',array('PurchID'=>$id,'PlantID'=>$selected_company))->row();
        
        // Add PurchaseMaster Audit record 
        $PurchaseAudit = array(
            "PlantID"=>$back_ItCount->PlantID,
            "FY"=>$back_ItCount->FY,
            "BT"=>$back_ItCount->BT,
            "PurchID"=>$back_ItCount->PurchID,
            "Transdate"=>$back_ItCount->Transdate,
            "AccountID"=>$back_ItCount->AccountID,
            "FrtAccountID"=>$back_ItCount->FrtAccountID,
            "Invoiceno"=>$back_ItCount->Invoiceno,
            "Invoicedate"=>$back_ItCount->Invoicedate,
            "Purchamt"=>$back_ItCount->Purchamt,
            "Discamt"=>$back_ItCount->Discamt,
            "Frtamt"=>$back_ItCount->Frtamt,
            "Othamt"=>$back_ItCount->Othamt,
            "Invamt"=>$back_ItCount->Invamt,
            "ItCount"=>$back_ItCount->ItCount,
            "Userid"=>$back_ItCount->Userid,
            "RoundOffAmt"=>$back_ItCount->RoundOffAmt,
            "OthAccountID"=>$back_ItCount->OthAccountID,
            "cgstamt"=>$back_ItCount->cgstamt,
            "sgstamt"=>$back_ItCount->sgstamt,
            "igstamt"=>$back_ItCount->igstamt,
            "tcs"=>$back_ItCount->tcs,
            "tcsAmt"=>$back_ItCount->tcsAmt,
            "UserID2"=>$this->session->userdata('username'),
            "Lupdate"=>date('Y-m-d H:i:s'),
        );
        if($this->db->insert(db_prefix().'purchasemaster_Audit', $PurchaseAudit)){
            foreach($old_pur_details as $key=>$value){
                $Item_audit = array(
                    "PlantID"=>$value['PlantID'],
                    "FY"=>$value['FY'],
                    "OrderID"=>$value['OrderID'],
                    "BillID"=>$value['BillID'],
                    "TransID"=>$value['TransID'],
                    "IsSchemeYN"=>$value['IsSchemeYN'],
                    "TransDate"=>$value['TransDate'],
                    "TransDate2"=>$value['TransDate2'],
                    "TType"=>$value['TType'],
                    "TType2"=>$value['TType2'],
                    "AccountID"=>$value['AccountID'],
                    "ItemID"=>$value['ItemID'],
                    "GodownID"=>$value['GodownID'],
                    "PurchRate"=>$value['PurchRate'],
                    "Mrp"=>$value['Mrp'],
                    "SaleRate"=>$value['SaleRate'],
                    "SuppliedIn"=>$value['SuppliedIn'],
                    "OrderQty"=>$value['OrderQty'],
                    "eOrderQty"=>$value['eOrderQty'],
                    "ereason"=>$value['ereason'],
                    "BilledQty"=>$value['BilledQty'],
                    "DiscPerc"=>$value['DiscPerc'],
                    "DiscAmt"=>$value['DiscAmt'],
                    "cgst"=>$value['cgst'],
                    "cgstamt"=>$value['cgstamt'],
                    "sgst"=>$value['sgst'],
                    "sgstamt"=>$value['sgstamt'],
                    "igst"=>$value['igst'],
                    "igstamt"=>$value['igstamt'],
                    "CaseQty"=>$value['CaseQty'],
                    "Cases"=>$value['Cases'],
                    "OrderAmt"=>$value['OrderAmt'],
                    "ChallanAmt"=>$value['ChallanAmt'],
                    "NetOrderAmt"=>$value['NetOrderAmt'],
                    "NetChallanAmt"=>$value['NetChallanAmt'],
                    "Ordinalno"=>$value['Ordinalno'],
                    "rowid"=>$value['rowid'],
                    "UserID"=>$value['UserID'],
                    "cnfid"=>$value['cnfid'],
                    "UserID2"=>$this->session->userdata('username'),
                    "Lupdate"=>date('Y-m-d H:i:s'),
                ); 
                $this->db->insert(db_prefix().'history_Audit', $Item_audit);
            }
        }
        $Discamt = 0;     
        $ItCount = count($es_detail);
        $PurchID =  $data['pur_order_number'];
        //$prd_date =  $data['trans_date'];
        $old_date =  $data['trans_date'];
        $new_date =  to_sql_date($data['prd_date'])." ".date('H:i:m');
        // $vendor =  $data['vendor'];
        $Invoiceno =  $data['invoce_n'];
        $invoce_date =  to_sql_date($data['invoce_date']);
        $FrtAccountID =  $data['Freight_1'];
        $OthAccountID =  $data['Other_ac'];
        $tcs =  $data['tcs_pre'];
        $tcsAmt =  $data['tcs_pre_data'];
        $Discamt =  $data['dc_total'];
        $Frtamt =  $data['Freight_AMT'];
        $cgstamt =  str_replace(",","",$data['CGST_amt']);
        $Othamt =  str_replace(",","",$data['Other_amt']);
        $sgstamt =  str_replace(",","",$data['SGST_AMT']);
        $RoundOffAmt =  $data['Round_OFF'];
        $igstamt =  str_replace(",","",$data['IGST_amt']);
        $Invamt =  str_replace(",","",$data['Invoice_amt']);
        $purchAmt = str_replace(",","",$data['total_mn']);
        
        $data_array = array(
            'Transdate' =>$new_date,
            'AccountID' =>$acc_id->AccountID,
            'FrtAccountID' =>$FrtAccountID,
            'Purchamt'=> $purchAmt,
            'Discamt'=>$Discamt,
            'Frtamt'=>$Frtamt,
            'Othamt'=>$Othamt,
            'Invamt'=>$Invamt,
            'Invoiceno'=>$Invoiceno,
            'Invoicedate'=>$invoce_date,
            'ItCount'=>$ItCount,
            'RoundOffAmt'=>$RoundOffAmt,
            'OthAccountID'=>$OthAccountID,
            'cgstamt'=>$cgstamt,
            'sgstamt'=>$sgstamt, 
            'igstamt'=>$igstamt,
            'tcs'=>$tcs,
            'tcsAmt'=>$tcsAmt,
            "Lupdate"=>date('Y-m-d H:i:s'),
            "UserID2"=>$this->session->userdata('username')
        );
       
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('PurchID',$id);
        $this->db->update(db_prefix() . 'purchasemaster',$data_array);
        if($this->db->affected_rows() > 0){
            $newmonth = substr($new_date,5,2);
            $month = $newmonth;
            if($month == "01"){
               $m = 11; 
            }
            if($month == "02"){
               $m = 12; 
            }
            if($month == "03"){
               $m = 13; 
            }
            if($month == "04"){
               $m = 2; 
            }
            if($month == "05"){
               $m = 3; 
            }
            if($month == "06"){
               $m = 4; 
            }
            if($month == "07"){
               $m = 5; 
            }
            if($month == "08"){
               $m = 6; 
            }
            if($month == "09"){
               $m = 7; 
            }
            if($month == "10"){
               $m = 8; 
            }
            if($month == "11"){
               $m = 9; 
            }
            if($month == "12"){
               $m = 10; 
            }
            $mm = "BAL".$m;
            
            $oldmonth = substr($old_date,5,2);
            $month_old = $oldmonth;
            if($month_old == "01"){
               $m_old = 11; 
            }
            if($month_old == "02"){
               $m_old = 12; 
            }
            if($month_old == "03"){
               $m_old = 13; 
            }
            if($month_old == "04"){
               $m_old = 2; 
            }
            if($month_old == "05"){
               $m_old = 3; 
            }
            if($month_old == "06"){
               $m_old = 4; 
            }
            if($month_old == "07"){
               $m_old = 5; 
            }
            if($month_old == "08"){
               $m_old = 6; 
            }
            if($month_old == "09"){
               $m_old = 7; 
            }
            if($month_old == "10"){
               $m_old = 8; 
            }
            if($month_old == "11"){
               $m_old = 9; 
            }
            if($month_old == "12"){
               $m_old = 10; 
            }
            $mm_old = "BAL".$m_old;
            
            $narrations = 'By Inv no.'.$Invoiceno.'-'._d(to_sql_date($data['invoce_date'])).'-'.$PurchID.'-'._d(to_sql_date($data['prd_date']));
            
            $get_pre_credit_amt = $this->get_pre_ledger_amt($data['vendor_code'],$PurchID);
            
            $get_act_bal = $this->get_acc_bal($data['vendor_code']);
            $current_bal = $get_act_bal->$mm_old;
            $current_bal_total = $current_bal + $get_pre_credit_amt->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $data['vendor_code']);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total,
                                    ]);
            $get_act_bal1 = $this->get_acc_bal($acc_id->AccountID);
            $current_bal1 = $get_act_bal1->$mm;
            $current_bal_total1 = $current_bal1 - $Invamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $acc_id->AccountID);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total1,
                                    ]);
            // delete previous ledger entry
            if($get_pre_credit_amt){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt->PlantID,
                    "FY"=>$get_pre_credit_amt->FY,
                    "Transdate"=>$get_pre_credit_amt->Transdate,
                    "TransDate2"=>$get_pre_credit_amt->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt->VoucherID,
                    "AccountID"=>$get_pre_credit_amt->AccountID,
                    "TType"=>$get_pre_credit_amt->TType,
                    "Amount"=>$get_pre_credit_amt->Amount,
                    "Narration"=>$get_pre_credit_amt->Narration,
                    "PassedFrom"=>$get_pre_credit_amt->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
            }
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('AccountID', $data['vendor_code']);
            $this->db->where('VoucherID', $PurchID);
            $this->db->delete(db_prefix().'accountledger');
            $ord_no = 1;
            // create new ledger entry
            $ledger_credit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchID,
                "AccountID" => $acc_id->AccountID,
                "TType" => "C",
                "Amount" => $Invamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_credit);
            $ord_no++;
            
            // Debit ledger update && Balance
            $act = 'PURCH';
            $type = 'D';
            $get_pre_credit_amt1 = $this->get_pre_ledger_amt_PURCH($act,$PurchID,$type);
            
            $get_act_bal2 = $this->get_acc_bal($act);
            $current_bal2 = $get_act_bal2->$mm_old;
            $current_bal_total2 = $current_bal2 - $get_pre_credit_amt1->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $act);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total2,
                                    ]);
            $get_act_bal3 = $this->get_acc_bal($act);
            $current_bal3 = $get_act_bal3->$mm;
            $current_bal_total3 = $current_bal3 + $purchAmt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $act);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total3,
                                    ]);
            // delete previous ledger entry
            
            if($get_pre_credit_amt1){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt1->PlantID,
                    "FY"=>$get_pre_credit_amt1->FY,
                    "Transdate"=>$get_pre_credit_amt1->Transdate,
                    "TransDate2"=>$get_pre_credit_amt1->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt1->VoucherID,
                    "AccountID"=>$get_pre_credit_amt1->AccountID,
                    "TType"=>$get_pre_credit_amt1->TType,
                    "Amount"=>$get_pre_credit_amt1->Amount,
                    "Narration"=>$get_pre_credit_amt1->Narration,
                    "PassedFrom"=>$get_pre_credit_amt1->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt1->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt1->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
            }
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('AccountID', "PURCH");
            $this->db->where('VoucherID', $PurchID);
            $this->db->delete(db_prefix().'accountledger');
            
            // create new ledger entry
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchID,
                "AccountID" => "PURCH",
                "TType" => "D",
                "Amount" => $purchAmt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
            
        // for Discount Amt
            $type = 'C';
            $get_pre_credit_amt11 = $this->get_pre_ledger_amt_PURCH($act,$PurchID,$type);
            if($get_pre_credit_amt11){
                $get_act_bal22 = $this->get_acc_bal($act);
                $current_bal22 = $get_act_bal22->$mm_old;
                $current_bal_total22 = $current_bal22 + $get_pre_credit_amt11->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $act);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total22,
                                    ]);
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt11->PlantID,
                    "FY"=>$get_pre_credit_amt11->FY,
                    "Transdate"=>$get_pre_credit_amt11->Transdate,
                    "TransDate2"=>$get_pre_credit_amt11->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt11->VoucherID,
                    "AccountID"=>$get_pre_credit_amt11->AccountID,
                    "TType"=>$get_pre_credit_amt11->TType,
                    "Amount"=>$get_pre_credit_amt11->Amount,
                    "Narration"=>$get_pre_credit_amt11->Narration,
                    "PassedFrom"=>$get_pre_credit_amt11->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt11->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt11->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "PURCH");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
            if($Discamt > 0){
                $get_act_bal33 = $this->get_acc_bal($act);
                $current_bal33 = $get_act_bal33->$mm;
                $current_bal_total33 = $current_bal33 - $Discamt;
                    
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $act);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm => $current_bal_total33,
                                ]);
                // create new ledger entry for Discount Amt
                $ledger_credit = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchID,
                    "AccountID" => "PURCH",
                    "TType" => "C",
                    "Amount" => $Discamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" => $ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                    );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_credit);
                $ord_no++;
            }
            
        // Other Account ledger
            if($selected_company == "3"){
                $othrAct = 'ME';
            }else{
                $othrAct = '92';
            }
            
            $get_pre_credit_amt_o = $this->get_pre_ledger_amt($othrAct,$PurchID);
            if($get_pre_credit_amt_o){
                $get_act_bal_o = $this->get_acc_bal($othrAct);
                $current_bal_o = $get_act_bal_o->$mm_old;
                if($get_pre_credit_amt_o->TType == "C"){
                    $current_bal_total_o = $current_bal_o + $get_pre_credit_amt_o->Amount;
                }else{
                    $current_bal_total_o = $current_bal_o - $get_pre_credit_amt_o->Amount;
                }
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $othrAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm_old => $current_bal_total_o,
                                ]);
            // delete previous ledger entry
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt_o->PlantID,
                    "FY"=>$get_pre_credit_amt_o->FY,
                    "Transdate"=>$get_pre_credit_amt_o->Transdate,
                    "TransDate2"=>$get_pre_credit_amt_o->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt_o->VoucherID,
                    "AccountID"=>$get_pre_credit_amt_o->AccountID,
                    "TType"=>$get_pre_credit_amt_o->TType,
                    "Amount"=>$get_pre_credit_amt_o->Amount,
                    "Narration"=>$get_pre_credit_amt_o->Narration,
                    "PassedFrom"=>$get_pre_credit_amt_o->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt_o->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt_o->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
            
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $othrAct);
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
                
            }
            if($Othamt > 0 || $Othamt < 0.00){
                $get_act_bal_oo = $this->get_acc_bal($othrAct);
                $current_bal_oo = $get_act_bal_oo->$mm;
                $ttype = "D";
                $current_bal_total_oo = $current_bal_oo + $Othamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $othrAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total_oo,
                                    ]);
                $ledger_otherAct = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchID,
                    "AccountID" => $othrAct,
                    "TType" =>$ttype,
                    "Amount" => $Othamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" =>$ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                    );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_otherAct);
                $ord_no++;
            }
            // Frt Account ledger
            $frtAct = '209';
            $get_pre_credit_amt_f = $this->get_pre_ledger_amt($frtAct,$PurchID);
            if($get_pre_credit_amt_f){
                $get_act_bal_f = $this->get_acc_bal($frtAct);
                $current_bal_f = $get_act_bal_f->$mm_old;
                if($get_pre_credit_amt_f->TType == "C"){
                    $current_bal_total_f = $current_bal_f + $get_pre_credit_amt_f->Amount;
                }else{
                    $current_bal_total_f = $current_bal_f - $get_pre_credit_amt_f->Amount;
                }
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $frtAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm_old => $current_bal_total_f,
                                ]);
                 // delete previous ledger entry
               
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt_f->PlantID,
                    "FY"=>$get_pre_credit_amt_f->FY,
                    "Transdate"=>$get_pre_credit_amt_f->Transdate,
                    "TransDate2"=>$get_pre_credit_amt_f->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt_f->VoucherID,
                    "AccountID"=>$get_pre_credit_amt_f->AccountID,
                    "TType"=>$get_pre_credit_amt_f->TType,
                    "Amount"=>$get_pre_credit_amt_f->Amount,
                    "Narration"=>$get_pre_credit_amt_f->Narration,
                    "PassedFrom"=>$get_pre_credit_amt_f->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt_f->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt_f->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
           
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $frtAct);
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
            if($Frtamt > 0 || $Frtamt < 0){
                $get_act_bal_ff = $this->get_acc_bal($frtAct);
                $current_bal_ff = $get_act_bal_ff->$mm;
                $ttype = "D";
                $current_bal_total_ff = $current_bal_ff + $Frtamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $frtAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total_ff,
                                    ]);
                $ledger_otherAct = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchID,
                    "AccountID" => $frtAct,
                    "TType" => $ttype,
                    "Amount" => $Frtamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" =>$ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                    );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_otherAct);
                $ord_no++;
            }
            
            // TCS Account ledger
            $tcsAct = 'TCS';
            $get_pre_credit_amt_tcs = $this->get_pre_ledger_amt($tcsAct,$PurchID);
            if($get_pre_credit_amt_tcs){
                $get_act_bal_tcs = $this->get_acc_bal($tcsAct);
                $current_bal_tcs = $get_act_bal_tcs->$mm_old;
                $current_bal_total_tcs = $current_bal_tcs - $get_pre_credit_amt_tcs->Amount;
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $tcsAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm_old => $current_bal_total_tcs,
                                ]);
                 // delete previous ledger entry
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt_tcs->PlantID,
                    "FY"=>$get_pre_credit_amt_tcs->FY,
                    "Transdate"=>$get_pre_credit_amt_tcs->Transdate,
                    "TransDate2"=>$get_pre_credit_amt_tcs->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt_tcs->VoucherID,
                    "AccountID"=>$get_pre_credit_amt_tcs->AccountID,
                    "TType"=>$get_pre_credit_amt_tcs->TType,
                    "Amount"=>$get_pre_credit_amt_tcs->Amount,
                    "Narration"=>$get_pre_credit_amt_tcs->Narration,
                    "PassedFrom"=>$get_pre_credit_amt_tcs->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt_tcs->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt_tcs->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $tcsAct);
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
            if($tcsAmt > 0 || $tcsAmt < 0){
                $get_act_bal_tcs2 = $this->get_acc_bal($tcsAct);
                $current_bal_tcs2 = $get_act_bal_tcs2->$mm;
                $current_bal_total_tcs2 = $current_bal_tcs2 + $tcsAmt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $tcsAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total_tcs2,
                                    ]);
                $ledger_tcsAct = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchID,
                    "AccountID" => $tcsAct,
                    "TType" => "D",
                    "Amount" => $tcsAmt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASE",
                    "OrdinalNo" =>$ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                    );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_tcsAct);
                $ord_no++;
            }            
        // gst ledger update && Balance
        if($igstamt !== "0.00"){
                // for igst ladger    
                $get_pre_credit_amt11 = $this->get_pre_ledger_amt('IGST',$PurchID);
                $get_act_bal11 = $this->get_acc_bal('IGST');
                $current_bal11 = $get_act_bal11->$mm_old;
                $current_bal_total11 = $current_bal11 - $get_pre_credit_amt11->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'IGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total11,
                                    ]);
            
            // for cgst ladger    
                $get_pre_credit_amt22 = $this->get_pre_ledger_amt('CGST',$PurchID);
                $get_act_bal22 = $this->get_acc_bal('CGST');
                $current_bal22 = $get_act_bal22->$mm_old;
                $current_bal_total22 = $current_bal22 - $get_pre_credit_amt22->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'CGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total22,
                                    ]);
            // for Sgst ladger    
                $get_pre_credit_amt222 = $this->get_pre_ledger_amt('SGST',$PurchID);
                $get_act_bal222 = $this->get_acc_bal('SGST');
                $current_bal222 = $get_act_bal222->$mm_old;
                $current_bal_total222 = $current_bal222 - $get_pre_credit_amt222->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'SGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total222,
                                    ]);
                                    
                $get_act_bal12 = $this->get_acc_bal('IGST');
                $current_bal12 = $get_act_bal12->$mm;
                $current_bal_total12 = $current_bal12 + $igstamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'IGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total12,
                                    ]);
            
            // delete previous ledger entry
            //IGST
            if($get_pre_credit_amt11){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt11->PlantID,
                    "FY"=>$get_pre_credit_amt11->FY,
                    "Transdate"=>$get_pre_credit_amt11->Transdate,
                    "TransDate2"=>$get_pre_credit_amt11->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt11->VoucherID,
                    "AccountID"=>$get_pre_credit_amt11->AccountID,
                    "TType"=>$get_pre_credit_amt11->TType,
                    "Amount"=>$get_pre_credit_amt11->Amount,
                    "Narration"=>$get_pre_credit_amt11->Narration,
                    "PassedFrom"=>$get_pre_credit_amt11->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt11->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt11->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "IGST");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
        //CGST
            if($get_pre_credit_amt22){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt22->PlantID,
                    "FY"=>$get_pre_credit_amt22->FY,
                    "Transdate"=>$get_pre_credit_amt22->Transdate,
                    "TransDate2"=>$get_pre_credit_amt22->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt22->VoucherID,
                    "AccountID"=>$get_pre_credit_amt22->AccountID,
                    "TType"=>$get_pre_credit_amt22->TType,
                    "Amount"=>$get_pre_credit_amt22->Amount,
                    "Narration"=>$get_pre_credit_amt22->Narration,
                    "PassedFrom"=>$get_pre_credit_amt22->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt22->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt22->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "CGST");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
        //SGST
            if($get_pre_credit_amt222){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt222->PlantID,
                    "FY"=>$get_pre_credit_amt222->FY,
                    "Transdate"=>$get_pre_credit_amt222->Transdate,
                    "TransDate2"=>$get_pre_credit_amt222->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt222->VoucherID,
                    "AccountID"=>$get_pre_credit_amt222->AccountID,
                    "TType"=>$get_pre_credit_amt222->TType,
                    "Amount"=>$get_pre_credit_amt222->Amount,
                    "Narration"=>$get_pre_credit_amt222->Narration,
                    "PassedFrom"=>$get_pre_credit_amt222->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt222->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt222->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "SGST");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
        // create new ledger entry
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchID,
                "AccountID" => "IGST",
                "TType" => "D",
                "Amount" => $igstamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" =>$ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
        }else{ 
            // for igst ladger    
            $get_pre_credit_amt11 = $this->get_pre_ledger_amt('IGST',$PurchID);
            $get_act_bal11 = $this->get_acc_bal('IGST');
            $current_bal11 = $get_act_bal11->$mm_old;
            $current_bal_total11 = $current_bal11 - $get_pre_credit_amt11->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'IGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total11,
                                    ]);
            // for cgst ladger    
            $get_pre_credit_amt22 = $this->get_pre_ledger_amt('CGST',$PurchID);
            $get_act_bal22 = $this->get_acc_bal('CGST');
            $current_bal22 = $get_act_bal22->$mm_old;
            $current_bal_total22 = $current_bal22 - $get_pre_credit_amt22->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'CGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total22,
                                    ]);
                                    
            $get_act_bal23 = $this->get_acc_bal('CGST');
            $current_bal23 = $get_act_bal23->$mm;
            $current_bal_total23 = $current_bal23 + $cgstamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'CGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total23,
                                    ]);
            
            // for sgst ladger    
            $get_pre_credit_amt33 = $this->get_pre_ledger_amt('SGST',$PurchID);
            $get_act_bal33 = $this->get_acc_bal('SGST');
            $current_bal33 = $get_act_bal33->$mm_old;
            $current_bal_total33 = $current_bal33 - $get_pre_credit_amt33->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'SGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total33,
                                    ]);
                                    
            $get_act_bal34 = $this->get_acc_bal('SGST');
            $current_bal34 = $get_act_bal34->$mm;
            $current_bal_total34 = $current_bal34 + $sgstamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'SGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total34,
                                    ]);
            // delete previous ledger entry
            //IGST
            if($get_pre_credit_amt11){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt11->PlantID,
                    "FY"=>$get_pre_credit_amt11->FY,
                    "Transdate"=>$get_pre_credit_amt11->Transdate,
                    "TransDate2"=>$get_pre_credit_amt11->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt11->VoucherID,
                    "AccountID"=>$get_pre_credit_amt11->AccountID,
                    "TType"=>$get_pre_credit_amt11->TType,
                    "Amount"=>$get_pre_credit_amt11->Amount,
                    "Narration"=>$get_pre_credit_amt11->Narration,
                    "PassedFrom"=>$get_pre_credit_amt11->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt11->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt11->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "IGST");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
            //CGST
            if($get_pre_credit_amt22){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt22->PlantID,
                    "FY"=>$get_pre_credit_amt22->FY,
                    "Transdate"=>$get_pre_credit_amt22->Transdate,
                    "TransDate2"=>$get_pre_credit_amt22->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt22->VoucherID,
                    "AccountID"=>$get_pre_credit_amt22->AccountID,
                    "TType"=>$get_pre_credit_amt22->TType,
                    "Amount"=>$get_pre_credit_amt22->Amount,
                    "Narration"=>$get_pre_credit_amt22->Narration,
                    "PassedFrom"=>$get_pre_credit_amt22->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt22->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt22->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "CGST");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
            //SGST
            if($get_pre_credit_amt33){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt33->PlantID,
                    "FY"=>$get_pre_credit_amt33->FY,
                    "Transdate"=>$get_pre_credit_amt33->Transdate,
                    "TransDate2"=>$get_pre_credit_amt33->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt33->VoucherID,
                    "AccountID"=>$get_pre_credit_amt33->AccountID,
                    "TType"=>$get_pre_credit_amt33->TType,
                    "Amount"=>$get_pre_credit_amt33->Amount,
                    "Narration"=>$get_pre_credit_amt33->Narration,
                    "PassedFrom"=>$get_pre_credit_amt33->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt33->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt33->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "SGST");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
            }
            // create new ledger entry
            // CGST
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchID,
                "AccountID" => "CGST",
                "TType" => "D",
                "Amount" => $sgstamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
            // create new ledger entry
            // SGST
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchID,
                "AccountID" => "SGST",
                "TType" => "D",
                "Amount" => $sgstamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" =>$ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
                            
        }
      //for RoundOffAmt ladger  
       $get_pre_credit_amt33 = $this->get_pre_ledger_amt('ROUNDOFF',$PurchID);
       
        // Delete old Roudoff ledger
        
        if($get_pre_credit_amt33){
            $oldroundoffAmt = $get_pre_credit_amt33->Amount;
            if($get_pre_credit_amt33->TType == 'C'){
               $current_bal_total44 = $current_bal44 + $oldroundoffAmt;
            }else{
               $current_bal_total44 = $current_bal44 - $oldroundoffAmt;
            }
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('AccountID', 'ROUNDOFF');
            $this->db->update(db_prefix() . 'accountbalances', [
                    $mm_old => $current_bal_total44,
                ]);
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt33->PlantID,
                    "FY"=>$get_pre_credit_amt33->FY,
                    "Transdate"=>$get_pre_credit_amt33->Transdate,
                    "TransDate2"=>$get_pre_credit_amt33->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt33->VoucherID,
                    "AccountID"=>$get_pre_credit_amt33->AccountID,
                    "TType"=>$get_pre_credit_amt33->TType,
                    "Amount"=>$get_pre_credit_amt33->Amount,
                    "Narration"=>$get_pre_credit_amt33->Narration,
                    "PassedFrom"=>$get_pre_credit_amt33->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt33->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt33->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "ROUNDOFF");
                $this->db->where('VoucherID', $PurchID);
                $this->db->delete(db_prefix().'accountledger');
        }
            
        if($RoundOffAmt > 0 || $RoundOffAmt < 0){
            $get_act_bal55 = $this->get_acc_bal('ROUNDOFF');
            $current_bal55 = $get_act_bal55->$mm;
            $current_bal_total55 = $current_bal55 + $RoundOffAmt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'ROUNDOFF');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total55,
                                    ]);
            
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchID,
                "AccountID" => "ROUNDOFF",
                "TType" => "D",
                "Amount" => $RoundOffAmt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASE",
                "OrdinalNo" =>$ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
        }                     
            
            $deleted_item = array();
            $new_items = array();
            foreach($es_detail as $value){
                $item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$selected_company))->row();
                array_push($new_items, $item_c->item_code);
            }
            $old_item_code = array();
            foreach ($old_pur_details as $key => $value) {
                $get_stock_details = $this->get_purch_stock($value["ItemID"]);
                $new_stock = $get_stock_details->PQty - $value["BilledQty"];
                    
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('ItemID', $value["ItemID"]);
                $this->db->update(db_prefix() . 'stockmaster', [
                                        'PQty' => $new_stock,
                                    ]);
                array_push($old_item_code, $value["ItemID"]);
                    //check deleted item
                if (!in_array($value["ItemID"], $new_items)){
                        array_push($deleted_item, $value["ItemID"]);
                }
            }
        $i =1;
            foreach($es_detail as $value){
                $item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$selected_company))->row();
                    
                $get_purch_stock = $this->get_purch_stock($item_c->item_code);
                $new_purch_stock = $get_purch_stock->PQty + $value['QTY'];
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('ItemID', $item_c->item_code);
                $this->db->update(db_prefix() . 'stockmaster', [
                                            'PQty' => $new_purch_stock,
                                        ]);
                $gst_devide = 0;
                $gst_igst = 0;
                if($data['state_c'] == 'UP'){
                    $gst_devide =  $value['GST']/2;
                }else{
                    $gst_igst = $value['GST'];
                }
                if (in_array($item_c->item_code, $old_item_code)){
                    $data_array_result_update = array(
                        'AccountID' =>$acc_id->AccountID,
                        'TransDate2'=>$new_date,
                        'CaseQty'=>$value['CaseQty'],
                        'PurchRate'=>$value['PurchRate'],
                        'SaleRate'=>$value['PurchRate'],
                        'BasicRate'=>$value['PurchRate'],
                        'SuppliedIn'=>1,
                        'Cases'=>$value['Cases'],
                        'OrderQty'=>$value['QTY'],
                        'BilledQty'=>$value['QTY'],
                        'OrderAmt'=>$value['PurchRate']*$value['QTY'],
                        'DiscAmt'=>$value['DiscAmt'],
                        'gst'=>$value['GST'],
                        'cgst'=>$gst_devide,
                        'sgst'=>$gst_devide,
                        'igst'=>$gst_igst,
                        'cgstamt'=>$value['CGSTAMT'],
                        'sgstamt'=>$value['SGSTAMT'],
                        'igstamt'=>$value['IGSTAMT'],
                        'OrderAmt'=>$value['PurchRate']*$value['QTY'],
                        'ChallanAmt'=>$value['PurchRate']*$value['QTY'],
                        'NetOrderAmt'=>($value['PurchRate']*$value['QTY'])-$value['DiscAmt']+$value['CGSTAMT']+$value['SGSTAMT']+$value['IGSTAMT'],
                        'NetChallanAmt'=>($value['PurchRate']*$value['QTY'])-$value['DiscAmt']+$value['CGSTAMT']+$value['SGSTAMT']+$value['IGSTAMT'],
                        'UserID2'=>$_SESSION['username'],
                        'Lupdate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->where('OrderID',$id);
                    $this->db->where('ItemID',$item_c->item_code);
                    $this->db->where('PlantID', $selected_company);
                    $this->db->LIKE('FY', $fy);
                    $this->db->update(db_prefix() . 'history',$data_array_result_update);
                     
                }else{
                    $data_array_result_add = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$fy,
                        'cnfid' =>1,
                        'OrderID' =>$PurchID,
                        'TransDate'=>$new_date,
                        'BillID' =>$PurchID,
                        'GodownID' =>$GodownID,
                        'TransDate2'=>$new_date,
                        'TType'=>'P',
                        'TType2'=> 'Purchase',
                        'AccountID'=> $acc_id->AccountID,
                        'ItemID'=>$item_c->item_code,
                        'CaseQty'=>$value['CaseQty'],
                        'PurchRate'=>$value['PurchRate'],
                        'SaleRate'=>$value['PurchRate'],
                        'BasicRate'=>$value['PurchRate'],
                        'SuppliedIn'=>1,
                        'Cases'=>$value['Cases'],
                        'OrderQty'=>$value['QTY'],
                        'BilledQty'=>$value['QTY'],
                        'OrderAmt'=>$value['PurchRate']*$value['QTY'],
                        'DiscAmt'=>$value['DiscAmt'],
                        'gst'=>$value['GST'],
                        'cgst'=>$gst_devide,
                        'sgst'=>$gst_devide,
                        'igst'=>$gst_igst,
                        'cgstamt'=>$value['CGSTAMT'],
                        'sgstamt'=>$value['SGSTAMT'],
                        'igstamt'=>$value['IGSTAMT'],
                        'OrderAmt'=>$value['PurchRate']*$value['QTY'],
                        'ChallanAmt'=>$value['PurchRate']*$value['QTY'],
                        'NetOrderAmt'=>($value['PurchRate']*$value['QTY'])-$value['DiscAmt']+$value['CGSTAMT']+$value['SGSTAMT']+$value['IGSTAMT'],
                        'NetChallanAmt'=>($value['PurchRate']*$value['QTY'])-$value['DiscAmt']+$value['CGSTAMT']+$value['SGSTAMT']+$value['IGSTAMT'],
                        'Ordinalno'=>$i,
                        'UserID'=>$_SESSION['username']
                    );
                        $this->db->insert(db_prefix() . 'history',$data_array_result_add);
                } 
            }
            foreach($deleted_item as $values){
               $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $data['vendor_code']);
                $this->db->where('OrderID', $PurchID);
                $this->db->where('ItemID', $values);
                $this->db->delete(db_prefix() . 'history');
           }
        }
        return true;
    }
    
    function getaccounts($postData){

     $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_clients = '';
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'xx_statelist.state_name');
       $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID = 50003002';
       $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state');
       $this->db->where($where_clients);
       
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        
       $records = $this->db->get(db_prefix() . 'clients')->result();

       foreach($records as $row ){
          $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"address"=>$row->address,"address2"=>$row->Address3,"state"=>$row->state,"station"=>$row->StationName,"gst"=>$row->vat,"state_name"=>$row->state_name);
       }

     }

     return $response;
  }
  
  function itemlist($postData){

     $response = array();
    $selected_company = $this->session->userdata('root_company');
    $where_items = '';
     if(isset($postData['search']) ){
       
       $q = $postData['search'];
       
       $this->db->select(db_prefix() . 'items.*');
       $where_items .= '(	item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q . '%" ESCAPE \'!\' OR long_description LIKE "%' . $q. '%" ESCAPE \'!\') AND ' . db_prefix() . 'items.isactive = "Y" ';
       
       $this->db->where($where_items);
       
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        
       $records = $this->db->get(db_prefix() . 'items')->result();

       foreach($records as $row ){
          $response[] = array("label"=>$row->description,"value"=>$row->item_code);
       }

     }

     return $response;
  }
    
    public function get_pre_ledger_amt($vendor_code,$pur_id){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $this->db->select();
        $this->db->from(db_prefix() . 'accountledger');
        $this->db->where(db_prefix() . 'accountledger.PlantID', $selected_company);
        $this->db->LIKE(db_prefix() . 'accountledger.FY', $year);
        $this->db->where(db_prefix() . 'accountledger.AccountID', $vendor_code);
        $this->db->where(db_prefix() . 'accountledger.VoucherID', $pur_id);
       return $this->db->get()->row();
    }
    
    public function get_pre_ledger_amt_PURCH($vendor_code,$pur_id,$type){
        
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $this->db->select();
        $this->db->from(db_prefix() . 'accountledger');
        $this->db->where(db_prefix() . 'accountledger.PlantID', $selected_company);
        $this->db->LIKE(db_prefix() . 'accountledger.FY', $year);
        $this->db->LIKE(db_prefix() . 'accountledger.TType', $type);
        $this->db->where(db_prefix() . 'accountledger.AccountID', $vendor_code);
        $this->db->where(db_prefix() . 'accountledger.VoucherID', $pur_id);
       return $this->db->get()->row();
    }
    
    public function get_purchase_for_body_data($filterdata)
     { 
        $from_date = to_sql_date($filterdata["from_date"]);
        $to_date = to_sql_date($filterdata["to_date"]);
        $report_type = $filterdata["report_type"];
        $accountID = $filterdata["accountID"];
        $ItemID = $filterdata["ItemID"];
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $sql = '';
        if($report_type == 1 || $report_type == 3){
            $sql .= 'SELECT tblpurchasemaster.*,tblclients.company';
        }else if($report_type == 2){
            $sql .= 'SELECT SUM(Purchamt) as Purchamt,SUM(Discamt) as Discamt,SUM(cgstamt) as cgstamt,SUM(sgstamt) as sgstamt,SUM(igstamt) as igstamt,SUM(Invamt) as Invamt,SUM(RoundOffAmt) as RoundOffAmt,tblclients.company,tblclients.AccountID';
        }
        $sql .=' FROM `tblpurchasemaster` 
        INNER JOIN tblclients ON tblclients.AccountID=tblpurchasemaster.AccountID AND tblclients.PlantID = tblpurchasemaster.PlantID
        WHERE tblpurchasemaster.PlantID = '.$selected_company.' AND tblpurchasemaster.FY = "'.$fy.'" AND tblpurchasemaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"';
        if($report_type == 3){
            $sql .=' AND tblpurchasemaster.AccountID ="'.$accountID.'"';
        }
        if($report_type == 2){
            $sql .= ' GROUP BY tblpurchasemaster.AccountID';
        }
        //$sql .= ' GROUP BY tblhistory.ItemID,tblhistory.TType,tblhistory.TType2';
        $sql .= ' ORDER BY tblclients.company ASC';
        if(empty($ItemID) && empty($accountID)){
            $result = $this->db->query($sql)->result_array();
        }
        
        if(!empty($ItemID)){
            $sql2 = 'SELECT tblhistory.OrderID,tblpurchasemaster.Transdate,tblpurchasemaster.Invoicedate,tblpurchasemaster.Invoiceno,tblhistory.PurchRate,SUM(tblhistory.BilledQty) as rcptqty,SUM(tblhistory.ChallanAmt) as amount,SUM(tblhistory.DiscAmt) as discamt,SUM(tblhistory.sgstamt) as sgstamt,SUM(tblhistory.cgstamt) as cgstamt,SUM(tblhistory.igstamt) as igstamt,SUM(tblhistory.ChallanAmt) as netamount,tblclients.company,tblhistory.AccountID 
            FROM `tblhistory`
            INNER JOIN tblclients ON tblclients.AccountID=tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID
            INNER JOIN tblpurchasemaster ON tblpurchasemaster.PurchID=tblhistory.OrderID AND tblpurchasemaster.PlantID = tblhistory.PlantID AND tblpurchasemaster.FY = tblhistory.FY 
            WHERE tblhistory.PlantID ='.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.ItemID = "'.$ItemID.'"'; 
            if(!empty($accountID)){
                $sql2 .= ' AND tblhistory.AccountID = "'.$accountID.'"';
            }
            $sql2 .= ' AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            GROUP BY tblhistory.ItemID,tblhistory.OrderID ORDER BY tblclients.company ASC';
            $result = $this->db->query($sql2)->result_array();
        }
        if(empty($ItemID) && !empty($accountID)){
            $sql3 = 'SELECT tblhistory.OrderID,tbltaxes.taxrate as taxname,tblpurchasemaster.Transdate,tblpurchasemaster.Invoicedate,tblpurchasemaster.Invoiceno,tblhistory.PurchRate,SUM(tblhistory.BilledQty) as rcptqty,SUM(tblhistory.ChallanAmt) as amount,SUM(tblhistory.DiscAmt) as discamt,SUM(tblhistory.sgstamt) as sgstamt,SUM(tblhistory.cgstamt) as cgstamt,SUM(tblhistory.igstamt) as igstamt,SUM(tblhistory.ChallanAmt) as netamount,tblitems.description,tblitems.case_qty,tblitems.tax,tblhistory.AccountID 
            FROM `tblhistory`
            INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID
            INNER JOIN tbltaxes ON tbltaxes.id=tblitems.tax 
            INNER JOIN tblpurchasemaster ON tblpurchasemaster.PurchID=tblhistory.OrderID AND tblpurchasemaster.PlantID = tblhistory.PlantID AND tblpurchasemaster.FY = tblhistory.FY 
            WHERE tblhistory.PlantID ='.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.AccountID = "'.$accountID.'"'; 
    
            $sql3 .= ' AND tblhistory.TransDate2 BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59" 
            GROUP BY tblhistory.ItemID,tblpurchasemaster.PurchID ORDER BY tblpurchasemaster.PurchID ASC';
            $result = $this->db->query($sql3)->result_array();
        }
        return $result;
     }
    public function get_company_detail()
     {  
         
        $selected_company = $this->session->userdata('root_company');
      
        $sql ='SELECT '.db_prefix().'rootcompany.*
        FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
        
        $result = $this->db->query($sql)->row();
        
        return $result;
        
       
     }
    
    public function get_account_details($AccountID)
     {  
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'clients.*
        FROM '.db_prefix().'clients WHERE AccountID = "'.$AccountID.'" AND PlantID = '.$selected_company;
        
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    
    public function get_item_details($ItemID)
     {  
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'items.*
        FROM '.db_prefix().'items WHERE item_code = "'.$ItemID.'" AND PlantID = '.$selected_company;
        
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    public function get_purchase_detail($id){
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $this->db->select(db_prefix() . 'history.*');
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.BillID', $id);
       return $this->db->get()->result_array();
    }
    
    public function get_account_data(){
        $selected_company = $this->session->userdata('root_company');
        
        
        $this->db->order_by('company', 'asc');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
   public function getallstate()
    {
       
       $this->db->where('country_id', '1');
       $this->db->order_by('state_name', 'ASE');
        return $this->db->get(db_prefix() . 'xx_statelist')->result_array();
    }
     public function getallstation()
    { 
       
      $selected_company = $this->session->userdata('root_company');
        
        
        $this->db->select(db_prefix() . 'clients.StationName,'.db_prefix() . 'clients.userid');
        $this->db->order_by('StationName', 'asc');
        $this->db->group_by('StationName');
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'clients.StationName !=', '');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
     public function getallroute()
    {
         $selected_company = $this->session->userdata('root_company');
        
       $this->db->where('PlantID', $selected_company); 
        return $this->db->get(db_prefix() . 'route')->result_array();
    }
     public function get_groups($id = '')
    {
        $selected_company = $this->session->userdata('root_company');
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix().'customers_groups')->row();
        }
        $this->db->where('PlantID', $selected_company);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix().'customers_groups')->result_array();
    }
       public function get_state()
    {
        $this->db->select('*');
        $this->db->where('country_id', '1');
        $this->db->from(db_prefix() . 'xx_statelist');
        $this->db->order_by('state_name', 'ASC');

        return $this->db->get()->result_array();
    }
    public function table_data($data){
    //   print_r($data);die;
        $states = $data['states'];
        $status = $data['status'];
        $selected_company = $this->session->userdata('root_company');
       
        $SQL = '';
        $SQL.= 'SELECT tblclients.AccountID as AccountID,tblclients.vat,tblaccountgroupssub.SubActGroupName,
           company,itemdivision,state,address,StationName,city,tblclients.active as actstatus, (SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tblcustomers_groups WHERE tblcustomers_groups.id = tblclients.DistributorType) as customerGroups
        FROM tblclients
        INNER JOIN tblcontacts ON tblclients.PlantID =tblcontacts.PlantID AND tblclients.AccountID=tblcontacts.AccountID 
        INNER JOIN tblaccountgroupssub ON tblaccountgroupssub.SubActGroupID =tblclients.SubActGroupID';
        
        //$SQL.= ' WHERE `tblclients`.`PlantID` = '.$selected_company.' AND tblclients.SubActGroupID IN("50003002","50003008","50003009")';
        $SQL.= ' WHERE `tblclients`.`PlantID` = '.$selected_company.'
        AND tblclients.SubActGroupID IN("50003002","50003008","50003009")';
        if($states != ''){
            $SQL.= '  AND `tblclients`.`state` = "'.$states.'"';
            // $this->db->where(db_prefix() . 'clients.state', $states);    
        }
        if($status != ''){
                $SQL.= '  AND `tblclients`.`active` = '.$status;
                //  $this->db->where(db_prefix() . 'clients.active', $status);
        }
        $SQL.= ' ORDER BY `tblclients`.`AccountID` ASC';
        $query = $this->db->query($SQL);
        return $query->result_array();
          
    }
    
    public function items_change_purchaseId($item_id,$purchaseId){
           $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
     //$item_details =  $this->db->get_where('tblitems',array('PlantID'=>$selected_company,'id'=>$item_id))->row();
     
        $this->db->select();
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'purchasemaster', db_prefix() . 'purchasemaster.PurchID = ' . db_prefix() . 'history.OrderID', 'left');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID', 'left');
        $this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax', 'left');
        $this->db->where(db_prefix() . 'history.TType', 'P');
        $this->db->where(db_prefix() . 'history.ItemID', $item_id);
        $this->db->where(db_prefix() . 'history.OrderID', $purchaseId);
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        //$this->db->where(db_prefix() .'history.FY', $year);
        $purch_data =  $this->db->get()->row_array();
        
        /*$this->db->select(db_prefix() . 'history.BilledQty');
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.TType', 'N');
        $this->db->where(db_prefix() . 'history.TType2', 'PurchaseReturn');
        $this->db->where(db_prefix() . 'history.ItemID', $item_id);
        $this->db->where(db_prefix() . 'history.BillID', $purchaseId);
        $this->db->where(db_prefix() .'history.PlantID', $selected_company);
        $this->db->where(db_prefix() .'history.FY', $year);
        $Rtnpurch_data =  $this->db->get()->row_array();
        if(empty($Rtnpurch_data)){
            $purch_data['NewPurchQty'] = $purch_data->BilledQty;
        }else{
            $purch_data['NewPurchQty'] = $purch_data->BilledQty - $Rtnpurch_data->BilledQty;
        }*/
        
        return $purch_data;
      //echo $this->db->last_query();
    }
    
    public function add_pur_return_order($data){
        // echo '<pre>';print_r($data);die; 
        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
           // print_r($pur_order_detail);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'pack';
            $header[] = 'purchaseId';
            $header[] = 'Purchqty';
            $header[] = 'Purchrate';
            $header[] = 'RtnCases';
            $header[] = 'inuint';
            $header[] = 'Amount';
            $header[] = 'disc';
            $header[] = 'discount_money';
            $header[] = 'CGST';
            $header[] = 'SGST';
            $header[] = 'IGST';
            $header[] = 'NetAmount';
            
            foreach ($pur_order_detail as $key => $value) {
                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        
        $acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
        
        $PlantID = $this->session->userdata('root_company'); 
        $FY = $this->session->userdata('finacial_year'); 
        
        if($PlantID == 1){
            $purchaseRtn_orderNumbar = get_option('next_purchasertn_number_for_cspl');
        }elseif($PlantID == 2){
            $purchaseRtn_orderNumbar = get_option('next_purchasertn_number_for_cff');
        }elseif($PlantID == 3){
            $purchaseRtn_orderNumbar = get_option('next_purchasertn_number_for_cbu');
        }
        $new_purchaseRtn_orderNumbar = 'PRT'.$FY.$purchaseRtn_orderNumbar;   
        $ItCount = count($es_detail);
        $Transdate =  to_sql_date($data['purch_rtn_date'])." ".date('H:i:s');
      
        $vendor =  $data['vendor'];
        $FrtAccountID =  $data['Freight_1'];
        $OthAccountID =  $data['Other_ac'];
        $Discamt =  $data['dc_total'];
        $Frtamt =  $data['Freight_AMT'];
        $cgstamt =  $data['CGST_amt'];
        $Othamt =  $data['Other_amt'];
        $sgstamt =  $data['SGST_AMT'];
        $RoundOffAmt =  $data['Round_OFF'];
        $igstamt =  $data['IGST_amt'];
        $BillID =  $data['purchase_id_store'];
       
        $Invamt =  str_replace(",","",$data['Invoice_amt']);
        $purchase_amt =    str_replace(",","",$data['total_mn']);
        $data_array = array(
            'PlantID'=>$PlantID,
            'FY'=>$FY,
            'BT'=>'Y',
            'PurchRtnID' =>$new_purchaseRtn_orderNumbar,
            'Transdate' =>$Transdate,
            'FrtAccountID' =>$FrtAccountID,
            'AccountID'=>$acc_id->AccountID,
            'Purchamt'=> $purchase_amt,
            'Discamt'=>$Discamt,
            'Frtamt'=>$Frtamt,
            'Othamt'=>$Othamt,
            'Invamt'=>$Invamt,
            'ItCount'=>$ItCount,
            'RoundOffAmt'=>$RoundOffAmt,
            'OthAccountID'=>$OthAccountID,
            'cgstamt'=>$cgstamt,
            'sgstamt'=>$sgstamt,
            'igstamt'=>$igstamt,
            'Userid' =>$_SESSION['username']
        );
        $this->db->insert(db_prefix() . 'purchasereturn',$data_array);
        
        if($this->db->affected_rows() > 0){
            $orde_no = 1;
            $this->increment_next_purchasertn_number();
            $yrdata= strtotime($data['purch_rtn_date']);
            //$date_narration = date('D-M-Y', $yrdata);
            $narrations = 'By PurchRtnID '.$new_purchaseRtn_orderNumbar.' / '.$data['purch_rtn_date'];
            $ledger_credit = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar, 
                "AccountID" => $acc_id->AccountID,
                "TType" => "D",
                "Amount" => $Invamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_credit);
            $orde_no++;
            $newmonth = substr($Transdate,5,2);
            $month = $newmonth;
            if($month == "01"){
              $m = 11; 
            }
            if($month == "02"){
              $m = 12; 
            }
            if($month == "03"){
              $m = 13; 
            }
            if($month == "04"){
              $m = 2; 
            }
            if($month == "05"){
              $m = 3; 
            }
            if($month == "06"){
              $m = 4; 
            }
            if($month == "07"){
              $m = 5; 
            }
            if($month == "08"){
              $m = 6; 
            }
            if($month == "09"){
              $m = 7; 
            }
            if($month == "10"){
              $m = 8; 
            }
            if($month == "11"){
              $m = 9; 
            }
            if($month == "12"){
              $m = 10; 
            }
            $mm = "BAL".$m;
            
            // credit ledger for selected account
            $act_bal = $this->get_acc_bal($acc_id->AccountID);
            if(empty($act_bal)){
                $bal = $Invamt;
                $insertActBal = array(
                    'PlantID'=>$PlantID,
                    'AccountID'=>$acc_id->AccountID,
                    'FY'=>$FY,
                    $mm=>$bal,
                    'BAL1'=>0.00,
                );
                $this->db->insert(db_prefix().'accountbalances', $insertActBal);
            }else{
                $current_bal = $act_bal->$mm;
                $current_bal_total = $current_bal + $Invamt;
                
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', $acc_id->AccountID);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total,
                                    ]);
            }
            
        if($Othamt > 0 || $Othamt < 0){
            $ledger_otherAct = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar,
                "AccountID" => $OthAccountID,
                "TType" => "C",
                "Amount" => $Othamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_otherAct);
            $orde_no++;
            $act_bal_O = $this->get_acc_bal($OthAccountID);
            if(empty($act_bal_O)){
                $Bal_O = 0.00 - $Othamt;
                $insertActBal_O = array(
                    'PlantID'=>$PlantID,
                    'AccountID'=>$OthAccountID,
                    'FY'=>$FY,
                    $mm=>$Bal_O,
                    'BAL1'=>0.00,
                );
                $this->db->insert(db_prefix().'accountbalances', $insertActBal_O);
            }else{
                $current_bal_o = $act_bal_O->$mm;
                $current_bal_total_o = $current_bal_o - $Othamt;
            
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', $OthAccountID);
                $this->db->update(db_prefix() . 'accountbalances', [
                            $mm => $current_bal_total_o,
                        ]);
            }
        }
            
        if($Frtamt > 0 || $Frtamt < 0){
        // Frt Account ledger
            $ledger_frtAct = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar,
                "AccountID" => $FrtAccountID,
                "TType" => "C",
                "Amount" => $Frtamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_frtAct);
            $orde_no++;
            $act_bal_f = $this->get_acc_bal($FrtAccountID);
            if(empty($act_bal_f)){
                $bal_F = 0.00 - $Frtamt;
                $insertActBal_F = array(
                    'PlantID'=>$PlantID,
                    'AccountID'=>$FrtAccountID,
                    'FY'=>$FY,
                    $mm=>$bal_F,
                    'BAL1'=>0.00,
                );
                $this->db->insert(db_prefix().'accountbalances', $insertActBal_F);
            }else{
                $current_bal_f = $act_bal_f->$mm;
                $current_bal_total_f = $current_bal_f - $Frtamt;
                
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', $FrtAccountID);
                $this->db->update(db_prefix() . 'accountbalances', [
                            $mm => $current_bal_total_f,
                        ]);
            }
        }

        // Credit ledger for selected account
            
            $ledger_debit = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar,
                "AccountID" => "PURCH",
                "TType" => "C",
                "Amount" => $purchase_amt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" =>$orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $orde_no++;
            $act_bal2 = $this->get_acc_bal("PURCH");
            if(empty($act_bal2)){
                $Bal2 = 0.00 - $purchase_amt;
                $insertActBal_PURCH = array(
                    'PlantID'=>$PlantID,
                    'AccountID'=>"PURCH",
                    'FY'=>$FY,
                    $mm=>$Bal2,
                    'BAL1'=>0.00,
                );
                $this->db->insert(db_prefix().'accountbalances', $insertActBal_PURCH);
            }else{
                $current_bal2 = $act_bal2->$mm;
                $current_bal_total2 = $current_bal2 - $purchase_amt;
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', "PURCH");
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm => $current_bal_total2,
                                ]);
            }   
        if($igstamt != 0.00){
            $gst = $igstamt;
            $ledger_igst = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar,
                "AccountID" => "IGST",
                "TType" => "C",
                "Amount" => $gst,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_igst);
            $orde_no++;
            $act_bal3 = $this->get_acc_bal("IGST");
                if(empty($act_bal3)){
                    $BAL3 = 0.00 - $gst;
                    $insertActBal_IGST = array(
                        'PlantID'=>$PlantID,
                        'AccountID'=>"IGST",
                        'FY'=>$FY,
                        $mm=>$BAL3,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal_IGST);
                }else{
                    $current_bal3 = $act_bal3->$mm;
                    $current_bal_total3 = $current_bal3 - $gst;
                        
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "IGST");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total3,
                                    ]);
                }
        }else{
        //cgst ledger creation
            $gst1 = $cgstamt;
            $ledger_cgst = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar,
                "AccountID" => "CGST",
                "TType" => "C",
                "Amount" => $gst1,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_cgst);
            $orde_no++;   
            $act_bal4 = $this->get_acc_bal("CGST");
                if(empty($act_bal4)){
                    $BAL4 = 0.00 - $gst1;
                    $insertActBal_CGST = array(
                        'PlantID'=>$PlantID,
                        'AccountID'=>"CGST",
                        'FY'=>$FY,
                        $mm=>$BAL4,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal_CGST);
                }else{
                    $current_bal4 = $act_bal4->$mm;
                    $current_bal_total4 = $current_bal4 - $gst1;
                        
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "CGST");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm => $current_bal_total4,
                                ]);
                }
        //sgst ledger creation             
            $gst2 = $sgstamt;
                   
            $ledger_sgst = array(
                "PlantID" => $PlantID,
                "Transdate" => $Transdate,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $new_purchaseRtn_orderNumbar,
                "AccountID" => "SGST",
                "TType" => "C",
                "Amount" => $gst2,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $orde_no,
                "UserID" => $_SESSION['username'],
                "FY" => $FY
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_sgst);
            $orde_no++;    
            $act_bal5 = $this->get_acc_bal("SGST");
            if(empty($act_bal5)){
                $BAL5 = 0.00 - (int) $gst2;
                $insertActBal_SGST = array(
                    'PlantID'=>$PlantID,
                    'AccountID'=>"SGST",
                    'FY'=>$FY,
                    $mm=>$BAL5,
                    'BAL1'=>0.00,
                );
                $this->db->insert(db_prefix().'accountbalances', $insertActBal_SGST);
            }else{
                $current_bal5 = $act_bal5->$mm;
                $current_bal_total5 = $current_bal5 - (int) $gst2;
                $this->db->where('PlantID', $PlantID);
                $this->db->LIKE('FY', $FY);
                $this->db->where('AccountID', "SGST");
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm => $current_bal_total5,
                                ]);
            }
        }
                
        if($RoundOffAmt > 0 || $RoundOffAmt < 0){
            $RoundOffAmt = $RoundOffAmt;
                $ledger_ROUNDOFF = array(
                    "PlantID" => $PlantID,
                    "Transdate" => $Transdate,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $new_purchaseRtn_orderNumbar,
                    "AccountID" => "ROUNDOFF",
                    "TType" => "C",
                    "Amount" => $RoundOffAmt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASERTN",
                    "OrdinalNo" => $orde_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $FY
                );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_ROUNDOFF);
                $orde_no++;    
                $act_bal6 = $this->get_acc_bal("ROUNDOFF");
                if(empty($act_bal6)){
                    $BAL6 = (int) $RoundOffAmt;
                    $insertActBal_6 = array(
                        'PlantID'=>$PlantID,
                        'AccountID'=>"ROUNDOFF",
                        'FY'=>$FY,
                        $mm=>$BAL6,
                        'BAL1'=>0.00,
                    );
                    $this->db->insert(db_prefix().'accountbalances', $insertActBal_6);
                }else{
                    $current_bal6 = $act_bal6->$mm;
                    $current_bal_total6 = $current_bal6 + (int) $RoundOffAmt;
                    $this->db->where('PlantID', $PlantID);
                    $this->db->LIKE('FY', $FY);
                    $this->db->where('AccountID', "ROUNDOFF");
                    $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total6,
                                    ]);
                }
                
        }      
        $i =1;
        foreach($es_detail as $value){
            $item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$PlantID))->row();
            $get_purch_stock = $this->get_purch_stock($value['item_code']);
            $new_purch_stock = $get_purch_stock->PRQty + $value['inuint'];
            
            $this->db->where('PlantID', $PlantID);
            $this->db->LIKE('FY', $FY);
            $this->db->where('ItemID', $value['item_code']);
            $this->db->update(db_prefix() . 'stockmaster', [
                                        'PRQty' => $new_purch_stock,
                                    ]);
            $gst_devide = 0;
            $gst_igst = 0;
            if($data['state_c'] == 'UP'){
                 $CGST =  $value['CGST'];
                 $SGST =  $value['SGST'];
                 $IGST =  $value['IGST'];
                 $gst = $CGST+$SGST;
                 $CGST_amt = ($value['Amount']*$CGST)/100;
                 $SGST_amt = ($value['Amount']*$SGST)/100;
                 $IGST_amt = 0;
            }else{
                    $CGST =  $value['CGST'];
                 $SGST =  $value['SGST'];
                 $IGST =  $value['IGST'];
                 $gst = $IGST;
                 $CGST_amt = 0;
                 $SGST_amt = 0;
                 $IGST_amt = ($value['Amount']*$IGST)/100;
            }
            $data_array_result = array(
                'PlantID'=>$PlantID,
                'FY'=>$FY,
                'cnfid' =>1,
                'OrderID' =>$new_purchaseRtn_orderNumbar,
                'TransDate' =>$Transdate,
                'BillID' =>$value['purchaseId'],
                'TransDate2'=>$Transdate,
                'TType'=>'N',
                'TType2'=> 'PurchaseReturn',
                'AccountID'=> $acc_id->AccountID,
                'ItemID'=>$value['item_code'],
                'CaseQty'=>$value['pack'],
                'PurchRate'=>$value['Purchrate'],
                'SaleRate'=>$value['Purchrate'],
                'BasicRate'=>$value['Purchrate'],
                'SuppliedIn'=>1,
                'Cases'=>$value['RtnCases'],
                'OrderQty'=>$value['inuint'],
                'BilledQty'=>$value['inuint'],
                'DiscAmt'=>$value['discount_money'],
                'DiscPerc'=>$value['disc'],
                'gst'=>$gst,
                'cgst'=>$CGST,
                'sgst'=>$SGST,
                'igst'=>$IGST,
                'cgstamt'=>$CGST_amt,
                'sgstamt'=>$SGST_amt,
                'igstamt'=>$IGST_amt,
                'OrderAmt'=>$value['Amount'],
                'ChallanAmt'=>$value['Amount'],
                'Ordinalno'=>$i,
                'UserID'=>$_SESSION['username'],
            );
            $this->db->insert(db_prefix() . 'history',$data_array_result);
            $i++;
        }
        return true;
    }
}   
        public function increment_next_purchasertn_number()
    {
        // Update next TAX Transaction number in settings
        $FY = $this->session->userdata('finacial_year'); 
        $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_purchasertn_number_for_cspl');
                
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_purchasertn_number_for_cff');
               
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_purchasertn_number_for_cbu');
                
            }
        
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
     public function load_data_for_purchaseRtn($data)
     {  
         $from_date = to_sql_date($data["from_date"]);
         $to_date = to_sql_date($data["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
      
        $sql1 = '('.db_prefix().'purchasereturn.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'purchasereturn.FY = "'.$fy.'" AND '.db_prefix().'purchasereturn.PlantID = "'.$selected_company.'" ORDER BY PurchRtnID ASC';
        
        $sql ='SELECT '.db_prefix().'purchasereturn.*,  
        (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'purchasereturn.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as AccountName
        FROM '.db_prefix().'purchasereturn WHERE '.$sql1;
        $result = $this->db->query($sql)->result_array();
        return $result;
     }
     public function get_unique_purchasereturn($id){
         $selected_company = $this->session->userdata('root_company');
      $year = $this->session->userdata('finacial_year');
       $this->db->select();
        $this->db->from(db_prefix() . 'purchasereturn');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasereturn.AccountID', 'left');
       $this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
        $this->db->join(db_prefix() . 'accountbalances', db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND '.db_prefix() . 'accountbalances.FY ="'.$year.'"', 'left');
         $this->db->where(db_prefix() . 'purchasereturn.PurchRtnID', $id);
       $this->db->where(db_prefix() . 'purchasereturn.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'purchasereturn.FY', $year);
       return $this->db->get()->row();
    }
    
    public function get_unique_historyreturn($id){
         $selected_company = $this->session->userdata('root_company');
      $year = $_SESSION['finacial_year'];
       $this->db->select();
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID', 'left');
        // $this->db->join(db_prefix() . 'history', db_prefix() . 'history.OrderID = ' . db_prefix() . 'purchasemaster.PurchID', 'left');
         $this->db->where(db_prefix() . 'history.OrderID', $id);
       $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
       $this->db->where(db_prefix() . 'history.FY', $year);
       return $this->db->get()->result_array();
    }
    public function get_pReturn_order_detail($purchRtn_id){
        $selected_company = $this->session->userdata('root_company');
        $year = $_SESSION['finacial_year'];
        
        $this->db->select(db_prefix() . 'history.*,'.db_prefix() . 'items.*,('.db_prefix() . 'history.BilledQty) AS Cases');
        $this->db->from(db_prefix() . 'history');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID', 'left');
        $this->db->where(db_prefix() . 'history.OrderID', $purchRtn_id);
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $data = $this->db->get()->result_array();
        
        foreach($data  as $key => $value){
            $this->db->select(db_prefix() . 'history.BilledQty');
            $this->db->from(db_prefix() . 'history');
            $this->db->where(db_prefix() . 'history.OrderID', $value['BillID']);
            $this->db->where(db_prefix() . 'history.ItemID', $value['ItemID']);
            $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.FY', $year);
            $data_purchase = $this->db->get()->row();
            $data[$key]['Net_total'] = round($value['ChallanAmt']+$value['cgstamt']+$value['sgstamt']+$value['igstamt'],2);
            
            $data[$key]['PurchQty'] = $data_purchase->BilledQty;
        }
       return $data;
         
    }
    
     public function update_purchaseRtn_order($data,$id){
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        
        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
           $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'case_qty';
            $header[] = 'BillID';
            $header[] = 'PurchQty';
            $header[] = 'BasicRate';
            $header[] = 'Cases';
            $header[] = 'BilledQty';
            $header[] = 'ChallanAmt';
            $header[] = 'DiscPerc';
            $header[] = 'DiscAmt';
            $header[] = 'cgst';
            $header[] = 'sgst';
            $header[] = 'igst';
            $header[] = 'Net_total';
            
            foreach ($pur_order_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        $old_purRtn_details = $this->purchase_model->get_purchaseRtn_detail($id);
        $acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
        $back_ItCount =  $this->db->select('*')->get_where(db_prefix().'purchasereturn',array('PurchRtnID'=>$id,'PlantID'=>$selected_company))->row();
        
        // Add PurchaseReturnMaster Audit record 
        $PurchaseRtnAudit = array(
            "PlantID"=>$back_ItCount->PlantID,
            "FY"=>$back_ItCount->FY,
            "BT"=>$back_ItCount->BT,
            "PurchRtnID"=>$back_ItCount->PurchRtnID,
            "Transdate"=>$back_ItCount->Transdate,
            "AccountID"=>$back_ItCount->AccountID,
            "FrtAccountID"=>$back_ItCount->FrtAccountID,
            "OthAccountID"=>$back_ItCount->OthAccountID,
            "Purchamt"=>$back_ItCount->Purchamt,
            "Discamt"=>$back_ItCount->Discamt,
            "Frtamt"=>$back_ItCount->Frtamt,
            "Othamt"=>$back_ItCount->Othamt,
            "RoundOffAmt"=>$back_ItCount->RoundOffAmt,
            "Invamt"=>$back_ItCount->Invamt,
            "ItCount"=>$back_ItCount->ItCount,
            "Userid"=>$back_ItCount->Userid,
            "cgstamt"=>$back_ItCount->cgstamt,
            "sgstamt"=>$back_ItCount->sgstamt,
            "igstamt"=>$back_ItCount->igstamt,
            "UserID2"=>$this->session->userdata('username'),
            "Lupdate"=>date('Y-m-d H:i:s'),
        );
        if($this->db->insert(db_prefix().'purchasereturn_Audit', $PurchaseRtnAudit)){
            foreach($old_purRtn_details as $key=>$value){
                $Item_audit = array(
                    "PlantID"=>$value['PlantID'],
                    "FY"=>$value['FY'],
                    "OrderID"=>$value['OrderID'],
                    "BillID"=>$value['BillID'],
                    "TransID"=>$value['TransID'],
                    "IsSchemeYN"=>$value['IsSchemeYN'],
                    "TransDate"=>$value['TransDate'],
                    "TransDate2"=>$value['TransDate2'],
                    "TType"=>$value['TType'],
                    "TType2"=>$value['TType2'],
                    "AccountID"=>$value['AccountID'],
                    "ItemID"=>$value['ItemID'],
                    "GodownID"=>$value['GodownID'],
                    "PurchRate"=>$value['PurchRate'],
                    "Mrp"=>$value['Mrp'],
                    "SaleRate"=>$value['SaleRate'],
                    "SuppliedIn"=>$value['SuppliedIn'],
                    "OrderQty"=>$value['OrderQty'],
                    "eOrderQty"=>$value['eOrderQty'],
                    "ereason"=>$value['ereason'],
                    "BilledQty"=>$value['BilledQty'],
                    "DiscPerc"=>$value['DiscPerc'],
                    "DiscAmt"=>$value['DiscAmt'],
                    "cgst"=>$value['cgst'],
                    "cgstamt"=>$value['cgstamt'],
                    "sgst"=>$value['sgst'],
                    "sgstamt"=>$value['sgstamt'],
                    "igst"=>$value['igst'],
                    "igstamt"=>$value['igstamt'],
                    "CaseQty"=>$value['CaseQty'],
                    "Cases"=>$value['Cases'],
                    "OrderAmt"=>$value['OrderAmt'],
                    "ChallanAmt"=>$value['ChallanAmt'],
                    "NetOrderAmt"=>$value['NetOrderAmt'],
                    "NetChallanAmt"=>$value['NetChallanAmt'],
                    "Ordinalno"=>$value['Ordinalno'],
                    "rowid"=>$value['rowid'],
                    "UserID"=>$value['UserID'],
                    "cnfid"=>$value['cnfid'],
                    "UserID2"=>$this->session->userdata('username'),
                    "Lupdate"=>date('Y-m-d H:i:s'),
                ); 
                $this->db->insert(db_prefix().'history_Audit', $Item_audit);
            }
        }
        $ItCount = count($es_detail);
        $new_purchaseRtn_orderNumbar =  $data['purchRtnID'];
        $PurchRtnID = $new_purchaseRtn_orderNumbar;
        
        $old_date =  to_sql_date($data['old_purRtnDate']);
        $new_date =  to_sql_date($data['purch_rtn_date'])." ".date('H:i:m');
        
        $FrtAccountID =  $data['Freight_1'];
        $Frtamt =  $data['Freight_AMT'];
        $OthAccountID =  $data['Other_ac'];
        $Othamt =  $data['Other_amt'];
        $Discamt =  $data['dc_total'];
        $cgstamt =  $data['CGST_amt'];
        $sgstamt =  $data['SGST_AMT'];
        $RoundOffAmt =  $data['Round_OFF'];
        $igstamt =  $data['IGST_amt'];
        
        $BillID =  $data['purchase_id_store'];
        $Invamt =  str_replace(",","",$data['Invoice_amt']);
        $purchase_amt =    str_replace(",","",$data['total_mn']);
        
        $newmonth = substr($new_date,5,2);
        $month = $newmonth;
            if($month == "01"){
               $m = 11; 
            }
            if($month == "02"){
               $m = 12; 
            }
            if($month == "03"){
               $m = 13; 
            }
            if($month == "04"){
               $m = 2; 
            }
            if($month == "05"){
               $m = 3; 
            }
            if($month == "06"){
               $m = 4; 
            }
            if($month == "07"){
               $m = 5; 
            }
            if($month == "08"){
               $m = 6; 
            }
            if($month == "09"){
               $m = 7; 
            }
            if($month == "10"){
               $m = 8; 
            }
            if($month == "11"){
               $m = 9; 
            }
            if($month == "12"){
               $m = 10; 
            }
            $mm = "BAL".$m;
            
            $oldmonth = substr($old_date,5,2);
            $month_old = $oldmonth;
            
            if($month_old == "01"){
               $m_old = 11; 
            }
            if($month_old == "02"){
               $m_old = 12; 
            }
            if($month_old == "03"){
               $m_old = 13; 
            }
            if($month_old == "04"){
               $m_old = 2; 
            }
            if($month_old == "05"){
               $m_old = 3; 
            }
            if($month_old == "06"){
               $m_old = 4; 
            }
            if($month_old == "07"){
               $m_old = 5; 
            }
            if($month_old == "08"){
               $m_old = 6; 
            }
            if($month_old == "09"){
               $m_old = 7; 
            }
            if($month_old == "10"){
               $m_old = 8; 
            }
            if($month_old == "11"){
               $m_old = 9; 
            }
            if($month_old == "12"){
               $m_old = 10; 
            }
            $mm_old = "BAL".$m_old;
        
        
        $data_array = array(
            'AccountID' =>$acc_id->AccountID,
            'Transdate' =>$new_date,
            'FrtAccountID' =>$FrtAccountID,
            'Purchamt'=> $purchase_amt,
            'Discamt'=>$Discamt,
            'Frtamt'=>$Frtamt,
            'Othamt'=>$Othamt,
            'Invamt'=>$Invamt,
            'ItCount'=>$ItCount,
            'RoundOffAmt'=>$RoundOffAmt,
            'OthAccountID'=>$OthAccountID,
            'cgstamt'=>$cgstamt,
            'sgstamt'=>$sgstamt,
            'igstamt'=>$igstamt,
            'UserID2'=>$this->session->userdata('username'),
            'Lupdate'=>date('Y-m-d H:i:s'),
        );
        
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('PurchRtnID',$id);
        $this->db->update(db_prefix() . 'purchasereturn',$data_array);
        if($this->db->affected_rows() > 0){
            $ord_no = 1;
            $narrations = 'By PurchRtnID '.$new_purchaseRtn_orderNumbar.' / '.$data['purch_rtn_date'];
            
            // Debit ledger && update  Balance
            
            $get_pre_debit_amt = $this->get_pre_ledger_amt($data['vendor_code'],$new_purchaseRtn_orderNumbar);
            
            $get_act_bal = $this->get_acc_bal($data['vendor_code']);
            
            $current_bal = $get_act_bal->$mm_old;
            $current_bal_total = $current_bal - $get_pre_debit_amt->Amount;
               
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $data['vendor_code']);
                $this->db->update(db_prefix() . 'accountbalances', [
                            $mm_old => $current_bal_total,
                    ]);
                    
            $get_act_bal1 = $this->get_acc_bal($acc_id->AccountID);
            $current_bal1 = $get_act_bal1->$mm;
            $current_bal_total1 = $current_bal1 + $Invamt;
                 
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $acc_id->AccountID);
                $this->db->update(db_prefix() . 'accountbalances', [
                            $mm => $current_bal_total1,
                        ]);
            // delete previous ledger entry
            if($get_pre_debit_amt){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_debit_amt->PlantID,
                    "FY"=>$get_pre_debit_amt->FY,
                    "Transdate"=>$get_pre_debit_amt->Transdate,
                    "TransDate2"=>$get_pre_debit_amt->TransDate2,
                    "VoucherID"=>$get_pre_debit_amt->VoucherID,
                    "AccountID"=>$get_pre_debit_amt->AccountID,
                    "TType"=>$get_pre_debit_amt->TType,
                    "Amount"=>$get_pre_debit_amt->Amount,
                    "Narration"=>$get_pre_debit_amt->Narration,
                    "PassedFrom"=>$get_pre_debit_amt->PassedFrom,
                    "OrdinalNo"=>$get_pre_debit_amt->OrdinalNo,
                    "UserID"=>$get_pre_debit_amt->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->LIKE('AccountID', $data['vendor_code']);
                $this->db->LIKE('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            
            // create new ledger entry
            $ledger_credit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchRtnID,
                "AccountID" => $acc_id->AccountID,
                "TType" => "D",
                "Amount" => $Invamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_credit);
            $ord_no++;
            // Credit ledger && update  Balance
            $act = 'PURCH';
            $get_pre_credit_amt1 = $this->get_pre_ledger_amt($act,$PurchRtnID);
            
            $get_act_bal2 = $this->get_acc_bal($act);
            $current_bal2 = $get_act_bal2->$mm_old;
            $current_bal_total2 = $current_bal2 + $get_pre_credit_amt1->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $act);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total2,
                                    ]);
                                    
            $get_act_bal3 = $this->get_acc_bal($act);
            $current_bal3 = $get_act_bal3->$mm;
            $current_bal_total3 = $current_bal3 - $purchase_amt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $act);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total3,
                                    ]);
                
            // delete previous ledger entry
            
            if($get_pre_credit_amt1){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt1->PlantID,
                    "FY"=>$get_pre_credit_amt1->FY,
                    "Transdate"=>$get_pre_credit_amt1->Transdate,
                    "TransDate2"=>$get_pre_credit_amt1->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt1->VoucherID,
                    "AccountID"=>$get_pre_credit_amt1->AccountID,
                    "TType"=>$get_pre_credit_amt1->TType,
                    "Amount"=>$get_pre_credit_amt1->Amount,
                    "Narration"=>$get_pre_credit_amt1->Narration,
                    "PassedFrom"=>$get_pre_credit_amt1->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt1->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt1->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "PURCH");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
        // create new ledger entry
            $ledger_credit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchRtnID,
                "AccountID" => "PURCH",
                "TType" => "C",
                "Amount" => $purchase_amt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
            );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_credit);
            $ord_no++;    
        // Other Account ledger
            if($selected_company == "3"){
                $othrAct = 'ME';
            }else{
                $othrAct = '92';
            }
            $get_pre_credit_amt_o = $this->get_pre_ledger_amt($othrAct,$PurchRtnID);
            if($get_pre_credit_amt_o){
                $get_act_bal_o = $this->get_acc_bal($othrAct);
                $current_bal_o = $get_act_bal_o->$mm_old;
                $current_bal_total_o = $current_bal_o + $get_pre_credit_amt_o->Amount;
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $othrAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm_old => $current_bal_total_o,
                                ]);
               
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt_o->PlantID,
                    "FY"=>$get_pre_credit_amt_o->FY,
                    "Transdate"=>$get_pre_credit_amt_o->Transdate,
                    "TransDate2"=>$get_pre_credit_amt_o->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt_o->VoucherID,
                    "AccountID"=>$get_pre_credit_amt_o->AccountID,
                    "TType"=>$get_pre_credit_amt_o->TType,
                    "Amount"=>$get_pre_credit_amt_o->Amount,
                    "Narration"=>$get_pre_credit_amt_o->Narration,
                    "PassedFrom"=>$get_pre_credit_amt_o->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt_o->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt_o->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $othrAct);
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
                
            }
            if($Othamt > 0 || $Othamt < 0){
                $get_act_bal_oo = $this->get_acc_bal($othrAct);
                $current_bal_oo = $get_act_bal_oo->$mm;
                $current_bal_total_oo = $current_bal_oo - $Othamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $othrAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total_oo,
                                    ]);
                $ledger_otherAct = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchRtnID,
                    "AccountID" => $othrAct,
                    "TType" => "C",
                    "Amount" => $Othamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASERTN",
                    "OrdinalNo" =>$ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                    );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_otherAct);
                $ord_no++;
            }
            
            // Frt Account ledger
            $frtAct = '209';
            $get_pre_credit_amt_f = $this->get_pre_ledger_amt($frtAct,$PurchRtnID);
            if($get_pre_credit_amt_f){
                $get_act_bal_f = $this->get_acc_bal($frtAct);
                $current_bal_f = $get_act_bal_f->$mm_old;
                $current_bal_total_f = $current_bal_f + $get_pre_credit_amt_f->Amount;
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $frtAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                    $mm_old => $current_bal_total_f,
                                ]);
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt_f->PlantID,
                    "FY"=>$get_pre_credit_amt_f->FY,
                    "Transdate"=>$get_pre_credit_amt_f->Transdate,
                    "TransDate2"=>$get_pre_credit_amt_f->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt_f->VoucherID,
                    "AccountID"=>$get_pre_credit_amt_f->AccountID,
                    "TType"=>$get_pre_credit_amt_f->TType,
                    "Amount"=>$get_pre_credit_amt_f->Amount,
                    "Narration"=>$get_pre_credit_amt_f->Narration,
                    "PassedFrom"=>$get_pre_credit_amt_f->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt_f->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt_f->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $frtAct);
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            if($Frtamt > 0 || $Frtamt < 0){
                $get_act_bal_ff = $this->get_acc_bal($frtAct);
                $current_bal_ff = $get_act_bal_ff->$mm;
                $current_bal_total_ff = $current_bal_ff - $Frtamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $frtAct);
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total_ff,
                                    ]);
                $ledger_otherAct = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchRtnID,
                    "AccountID" => $frtAct,
                    "TType" => "C",
                    "Amount" => $Frtamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASERTN",
                    "OrdinalNo" =>$ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_otherAct);
                $ord_no++;
            }
                                 
        // gst ledger update && Balance
            if($igstamt != "0.00"){
                // for igst ladger    
                $get_pre_credit_amt11 = $this->get_pre_ledger_amt('IGST',$PurchRtnID);
            
                $get_act_bal11 = $this->get_acc_bal('IGST');
                $current_bal11 = $get_act_bal11->$mm_old;
                $current_bal_total11 = $current_bal11 + $get_pre_credit_amt11->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'IGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total11,
                                    ]);
                                    
                $get_act_bal12 = $this->get_acc_bal('IGST');
                $current_bal12 = $get_act_bal12->$mm;
                $current_bal_total12 = $current_bal12 - $igstamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'IGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total12,
                                    ]);
            
            // for cgst ladger    
                $get_pre_credit_amt22 = $this->get_pre_ledger_amt('CGST',$PurchRtnID);
                $get_act_bal22 = $this->get_acc_bal('CGST');
                $current_bal22 = $get_act_bal22->$mm_old;
                $current_bal_total22 = $current_bal22 - $get_pre_credit_amt22->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'CGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total22,
                                    ]);
            // for Sgst ladger    
                $get_pre_credit_amt33 = $this->get_pre_ledger_amt('SGST',$PurchRtnID);
                $get_act_bal33 = $this->get_acc_bal('SGST');
                $current_bal33 = $get_act_bal33->$mm_old;
                $current_bal_total33 = $current_bal33 - $get_pre_credit_amt33->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'SGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total33,
                                    ]);
            
            // delete previous ledger entry
            //IGST
            if($get_pre_credit_amt11){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt11->PlantID,
                    "FY"=>$get_pre_credit_amt11->FY,
                    "Transdate"=>$get_pre_credit_amt11->Transdate,
                    "TransDate2"=>$get_pre_credit_amt11->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt11->VoucherID,
                    "AccountID"=>$get_pre_credit_amt11->AccountID,
                    "TType"=>$get_pre_credit_amt11->TType,
                    "Amount"=>$get_pre_credit_amt11->Amount,
                    "Narration"=>$get_pre_credit_amt11->Narration,
                    "PassedFrom"=>$get_pre_credit_amt11->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt11->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt11->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "IGST");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            //CGST
            if($get_pre_credit_amt22){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt22->PlantID,
                    "FY"=>$get_pre_credit_amt22->FY,
                    "Transdate"=>$get_pre_credit_amt22->Transdate,
                    "TransDate2"=>$get_pre_credit_amt22->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt22->VoucherID,
                    "AccountID"=>$get_pre_credit_amt22->AccountID,
                    "TType"=>$get_pre_credit_amt22->TType,
                    "Amount"=>$get_pre_credit_amt22->Amount,
                    "Narration"=>$get_pre_credit_amt22->Narration,
                    "PassedFrom"=>$get_pre_credit_amt22->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt22->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt22->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "CGST");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            //SGST
            
            if($get_pre_credit_amt33){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt33->PlantID,
                    "FY"=>$get_pre_credit_amt33->FY,
                    "Transdate"=>$get_pre_credit_amt33->Transdate,
                    "TransDate2"=>$get_pre_credit_amt33->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt33->VoucherID,
                    "AccountID"=>$get_pre_credit_amt33->AccountID,
                    "TType"=>$get_pre_credit_amt33->TType,
                    "Amount"=>$get_pre_credit_amt33->Amount,
                    "Narration"=>$get_pre_credit_amt33->Narration,
                    "PassedFrom"=>$get_pre_credit_amt33->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt33->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt33->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "SGST");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            
            // create new ledger entry
                $ledger_IgstAct = array(
                    "PlantID" => $selected_company,
                    "Transdate" => $new_date,
                    "TransDate2" => date('Y-m-d H:i:s'),
                    "VoucherID" => $PurchRtnID,
                    "AccountID" => "IGST",
                    "TType" => "C",
                    "Amount" => $igstamt,
                    "Narration" => $narrations,
                    "PassedFrom" => "PURCHASERTN",
                    "OrdinalNo" =>$ord_no,
                    "UserID" => $_SESSION['username'],
                    "FY" => $fy
                );
                $this->db->insert(db_prefix() . 'accountledger',$ledger_IgstAct);
                $ord_no++;
            }else{
                // for igst ladger    
                $get_pre_credit_amt11 = $this->get_pre_ledger_amt('IGST',$PurchRtnID);
                $get_act_bal11 = $this->get_acc_bal('IGST');
                $current_bal11 = $get_act_bal11->$mm_old;
                $current_bal_total11 = $current_bal11 - $get_pre_credit_amt11->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'IGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total11,
                                    ]);
                // for cgst ladger    
                $get_pre_credit_amt22 = $this->get_pre_ledger_amt('CGST',$PurchRtnID);
            
                $get_act_bal22 = $this->get_acc_bal('CGST');
                $current_bal22 = $get_act_bal22->$mm_old;
                $current_bal_total22 = $current_bal22 + $get_pre_credit_amt22->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'CGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total22,
                                    ]);
                                    
                $get_act_bal23 = $this->get_acc_bal('CGST');
                $current_bal23 = $get_act_bal23->$mm;
                $current_bal_total23 = $current_bal23 - $cgstamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'CGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total23,
                                    ]);
            
            
                                    
                // for sgst ladger    
                $get_pre_credit_amt33 = $this->get_pre_ledger_amt('SGST',$PurchRtnID);
            
                $get_act_bal33 = $this->get_acc_bal('SGST');
                $current_bal33 = $get_act_bal33->$mm_old;
                $current_bal_total33 = $current_bal33 + $get_pre_credit_amt33->Amount;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'SGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm_old => $current_bal_total33,
                                    ]);
                                    
                $get_act_bal34 = $this->get_acc_bal('SGST');
                $current_bal34 = $get_act_bal34->$mm;
                $current_bal_total34 = $current_bal34 - $sgstamt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'SGST');
                $this->db->update(db_prefix() . 'accountbalances', [
                                        $mm => $current_bal_total34,
                                    ]);
            
            // delete previous ledger entry
            
            //IGST
            if($get_pre_credit_amt11){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt11->PlantID,
                    "FY"=>$get_pre_credit_amt11->FY,
                    "Transdate"=>$get_pre_credit_amt11->Transdate,
                    "TransDate2"=>$get_pre_credit_amt11->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt11->VoucherID,
                    "AccountID"=>$get_pre_credit_amt11->AccountID,
                    "TType"=>$get_pre_credit_amt11->TType,
                    "Amount"=>$get_pre_credit_amt11->Amount,
                    "Narration"=>$get_pre_credit_amt11->Narration,
                    "PassedFrom"=>$get_pre_credit_amt11->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt11->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt11->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "IGST");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            //CGST
            if($get_pre_credit_amt22){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt22->PlantID,
                    "FY"=>$get_pre_credit_amt22->FY,
                    "Transdate"=>$get_pre_credit_amt22->Transdate,
                    "TransDate2"=>$get_pre_credit_amt22->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt22->VoucherID,
                    "AccountID"=>$get_pre_credit_amt22->AccountID,
                    "TType"=>$get_pre_credit_amt22->TType,
                    "Amount"=>$get_pre_credit_amt22->Amount,
                    "Narration"=>$get_pre_credit_amt22->Narration,
                    "PassedFrom"=>$get_pre_credit_amt22->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt22->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt22->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "CGST");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            //SGST
            if($get_pre_credit_amt33){
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt33->PlantID,
                    "FY"=>$get_pre_credit_amt33->FY,
                    "Transdate"=>$get_pre_credit_amt33->Transdate,
                    "TransDate2"=>$get_pre_credit_amt33->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt33->VoucherID,
                    "AccountID"=>$get_pre_credit_amt33->AccountID,
                    "TType"=>$get_pre_credit_amt33->TType,
                    "Amount"=>$get_pre_credit_amt33->Amount,
                    "Narration"=>$get_pre_credit_amt33->Narration,
                    "PassedFrom"=>$get_pre_credit_amt33->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt33->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt33->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "SGST");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
            }
            
            // create new ledger entry
            // CGST
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchRtnID,
                "AccountID" => "CGST",
                "TType" => "C",
                "Amount" => $sgstamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
            // create new ledger entry
            // SGST
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchRtnID,
                "AccountID" => "SGST",
                "TType" => "C",
                "Amount" => $sgstamt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
            $ord_no++;
        }
      //for RoundOffAmt ladger  
      $get_pre_credit_amt33 = $this->get_pre_ledger_amt('ROUNDOFF',$PurchRtnID);
        if($get_pre_credit_amt33){
            $oldroundoffAmt = $get_pre_credit_amt33->Amount;
            $get_act_bal44 = $this->get_acc_bal('ROUNDOFF');
            $current_bal44 = $get_act_bal44->$mm_old;
            if($get_pre_credit_amt33->TType == 'C'){
                $current_bal_total44 = $current_bal44 + $oldroundoffAmt;
            }else{
                $current_bal_total44 = $current_bal44 - $oldroundoffAmt;
            }
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('AccountID', 'ROUNDOFF');
            $this->db->update(db_prefix() . 'accountbalances', [
                                $mm_old => $current_bal_total44,
                            ]);
          
                $ledger_audit = array(
                    "PlantID"=>$get_pre_credit_amt33->PlantID,
                    "FY"=>$get_pre_credit_amt33->FY,
                    "Transdate"=>$get_pre_credit_amt33->Transdate,
                    "TransDate2"=>$get_pre_credit_amt33->TransDate2,
                    "VoucherID"=>$get_pre_credit_amt33->VoucherID,
                    "AccountID"=>$get_pre_credit_amt33->AccountID,
                    "TType"=>$get_pre_credit_amt33->TType,
                    "Amount"=>$get_pre_credit_amt33->Amount,
                    "Narration"=>$get_pre_credit_amt33->Narration,
                    "PassedFrom"=>$get_pre_credit_amt33->PassedFrom,
                    "OrdinalNo"=>$get_pre_credit_amt33->OrdinalNo,
                    "UserID"=>$get_pre_credit_amt33->UserID,
                    "Lupdate"=>date('Y-m-d H:i:s'),
                    "UserID2"=>$this->session->userdata('username')
                );
                $this->db->insert(db_prefix().'accountledgeraudit', $ledger_audit);
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', "ROUNDOFF");
                $this->db->where('VoucherID', $PurchRtnID);
                $this->db->delete(db_prefix().'accountledger');
        }
      
        if($RoundOffAmt > 0 || $RoundOffAmt < 0){
            $get_act_bal55 = $this->get_acc_bal('ROUNDOFF');
            $current_bal55 = $get_act_bal55->$mm;
            $current_bal_total55 = $current_bal55 - $RoundOffAmt;
                
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', 'ROUNDOFF');
                $this->db->update(db_prefix() . 'accountbalances', [
                        $mm => $current_bal_total55,
                    ]);
            
            $ledger_debit = array(
                "PlantID" => $selected_company,
                "Transdate" => $new_date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "VoucherID" => $PurchRtnID,
                "AccountID" => "ROUNDOFF",
                "TType" => "C",
                "Amount" => $RoundOffAmt,
                "Narration" => $narrations,
                "PassedFrom" => "PURCHASERTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $_SESSION['username'],
                "FY" => $fy
                );
            $this->db->insert(db_prefix() . 'accountledger',$ledger_debit);
        }
            
        $deleted_item = array();
        $new_items = array();
        foreach($es_detail as $value){
            array_push($new_items, $value['item_code']);
        }
        $old_item_code = array();
            foreach ($old_purRtn_details as $key => $value) {
                $get_stock_details = $this->get_purch_stock($value["ItemID"]);
                $new_stock = $get_stock_details->PRQty - $value["BilledQty"];
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('ItemID', $value["ItemID"]);
                $this->db->update(db_prefix() . 'stockmaster', [
                                'PRQty' => $new_stock,
                            ]);
                array_push($old_item_code, $value["ItemID"]);
                    //check deleted item
                if (!in_array($value["ItemID"], $new_items)){
                    array_push($deleted_item, $value["ItemID"]);
                }
            }
        if($acc_id->AccountID == $data["vendor_code"]){
            $i =1;
            foreach($es_detail as $value){
                $get_purch_stock = $this->get_purch_stock($value['item_code']);
                $new_purch_stock = $get_purch_stock->PRQty + $value['BilledQty'];
            
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('ItemID', $value['item_code']);
                $this->db->update(db_prefix() . 'stockmaster', [
                        'PRQty' => $new_purch_stock,
                    ]);
            
                $gst_devide = 0;
                $gst_igst = 0;
                if($data['state_c'] == 'UP'){
                    $CGST =  $value['cgst'];
                    $SGST =  $value['sgst'];
                    $IGST =  $value['igst'];
                    $gst = $CGST+$SGST;
                    $CGST_amt = ($value['ChallanAmt']*$CGST)/100;
                    $SGST_amt = ($value['ChallanAmt']*$SGST)/100;
                    $IGST_amt = 0;
                }else{
                    $CGST =  $value['cgst'];
                    $SGST =  $value['sgst'];
                    $IGST =  $value['igst'];
                    $gst = $IGST;
                    $CGST_amt = 0;
                    $SGST_amt = 0;
                    $IGST_amt = ($value['ChallanAmt']*$IGST)/100;
                }
                if (in_array($value['item_code'], $old_item_code)){
                    $Cases = $value['Cases'] / $value['case_qty']; 
                    $data_array_result_update = array(
                        'TransDate2'=>$new_date,
                        'CaseQty'=>$value['case_qty'],
                        'PurchRate'=>$value['BasicRate'],
                        'SaleRate'=>$value['BasicRate'],
                        'BasicRate'=>$value['BasicRate'],
                        'SuppliedIn'=>1,
                        'Cases'=>$Cases,
                        'OrderQty'=>$value['BilledQty'],
                        'BilledQty'=>$value['BilledQty'],
                        'DiscAmt'=>$value['DiscAmt'],
                        'DiscPerc'=>$value['DiscPerc'],
                        'gst'=>$gst,
                        'cgst'=>$CGST,
                        'sgst'=>$SGST,
                        'igst'=>$IGST,
                        'cgstamt'=>$CGST_amt,
                        'sgstamt'=>$SGST_amt,
                        'igstamt'=>$IGST_amt,
                        'OrderAmt'=>$value['ChallanAmt'],
                        'ChallanAmt'=>$value['ChallanAmt'],
                        'NetOrderAmt'=>$value['Net_total'],
                        'NetChallanAmt'=>$value['Net_total'],
                        'UserID2'=>$_SESSION['username'],
                        'Lupdate'=>$new_date,
                    );
                    $this->db->where('OrderID',$new_purchaseRtn_orderNumbar);
                    $this->db->where('ItemID',$value['item_code']);
                    $this->db->where('PlantID', $selected_company);
                    $this->db->LIKE('FY', $fy);
                    $this->db->update(db_prefix() . 'history',$data_array_result_update);
               
                }else{
                    $Cases = $value['Cases'] / $value['case_qty'];      
                    $data_array_result_add = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$fy,
                        'cnfid' =>1,
                        'OrderID' =>$new_purchaseRtn_orderNumbar,
                        'TransDate' =>$new_date,
                        'BillID' =>$BillID,
                        'TransDate2'=>$new_date,
                        'TType'=>'N',
                        'TType2'=> 'PurchaseReturn',
                        'AccountID'=> $acc_id->AccountID,
                        'ItemID'=>$value['item_code'],
                        'CaseQty'=>$value['case_qty'],
                        'PurchRate'=>$value['BasicRate'],
                        'SaleRate'=>$value['BasicRate'],
                        'BasicRate'=>$value['BasicRate'],
                        'SuppliedIn'=>1,
                        'Cases'=>$Cases,
                        'OrderQty'=>$value['BilledQty'],
                        'BilledQty'=>$value['BilledQty'],
                        'DiscPerc'=>$value['DiscPerc'],
                        'DiscAmt'=>$value['DiscAmt'],
                        'gst'=>$gst,
                        'cgst'=>$CGST,
                        'sgst'=>$SGST,
                        'igst'=>$IGST,
                        'cgstamt'=>$CGST_amt,
                        'sgstamt'=>$SGST_amt,
                        'igstamt'=>$IGST_amt,
                        'OrderAmt'=>$value['ChallanAmt'],
                        'ChallanAmt'=>$value['ChallanAmt'],
                        'NetOrderAmt'=>$value['Net_total'],
                        'NetChallanAmt'=>$value['Net_total'],
                        'Ordinalno'=>$i,
                        'UserID'=>$_SESSION['username'],
                    );
                    $this->db->insert(db_prefix() . 'history',$data_array_result_add);
                }
            }
        
            foreach($deleted_item as $values){
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('AccountID', $data['vendor_code']);
                $this->db->where('OrderID', $new_purchaseRtn_orderNumbar);
                $this->db->where('ItemID', $values);
                $this->db->delete(db_prefix() . 'history');
            }
        }else{
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('AccountID', $data['vendor_code']);
            $this->db->where('OrderID', $new_purchaseRtn_orderNumbar);
            $this->db->delete(db_prefix() . 'history');
            $i =1;
            foreach($es_detail as $value){
                $get_purch_stock = $this->get_purch_stock($value['item_code']);
                $new_purch_stock = $get_purch_stock->PRQty + $value['BilledQty'];
            
                $this->db->where('PlantID', $selected_company);
                $this->db->LIKE('FY', $fy);
                $this->db->where('ItemID', $value['item_code']);
                $this->db->update(db_prefix() . 'stockmaster', [
                            'PRQty' => $new_purch_stock,
                        ]);
                    
                $gst_devide = 0;
                $gst_igst = 0;
                if($data['state_c'] == 'UP'){
                    $CGST =  $value['cgst'];
                    $SGST =  $value['sgst'];
                    $IGST =  $value['igst'];
                    $gst = $CGST+$SGST;
                    $CGST_amt = ($value['ChallanAmt']*$CGST)/100;
                    $SGST_amt = ($value['ChallanAmt']*$SGST)/100;
                    $IGST_amt = 0;
                }else{
                    $CGST =  $value['cgst'];
                    $SGST =  $value['sgst'];
                    $IGST =  $value['igst'];
                    $gst = $IGST;
                    $CGST_amt = 0;
                    $SGST_amt = 0;
                    $IGST_amt = ($value['ChallanAmt']*$IGST)/100;
                }
                $Cases = $value['Cases'] / $value['case_qty'];
                $data_array_result_add = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$fy,
                        'cnfid' =>1,
                        'OrderID' =>$new_purchaseRtn_orderNumbar,
                        'TransDate' =>$new_date,
                        'BillID' =>$BillID,
                        'TransDate2'=>$new_date,
                        'TType'=>'N',
                        'TType2'=> 'PurchaseReturn',
                        'AccountID'=> $acc_id->AccountID,
                        'ItemID'=>$value['item_code'],
                        'CaseQty'=>$value['case_qty'],
                        'PurchRate'=>$value['BasicRate'],
                        'SaleRate'=>$value['BasicRate'],
                        'BasicRate'=>$value['BasicRate'],
                        'SuppliedIn'=>1,
                        'Cases'=>$Cases,
                        'OrderQty'=>$value['BilledQty'],
                        'BilledQty'=>$value['BilledQty'],
                        'DiscPerc'=>$value['DiscPerc'],
                        'DiscAmt'=>$value['DiscAmt'],
                        'gst'=>$gst,
                        'cgst'=>$CGST,
                        'sgst'=>$SGST,
                        'igst'=>$IGST,
                        'cgstamt'=>$CGST_amt,
                        'sgstamt'=>$SGST_amt,
                        'igstamt'=>$IGST_amt,
                        'OrderAmt'=>$value['ChallanAmt'],
                        'ChallanAmt'=>$value['ChallanAmt'],
                        'NetOrderAmt'=>$value['Net_total'],
                        'NetChallanAmt'=>$value['Net_total'],
                        'Ordinalno'=>$i,
                        'UserID'=>$_SESSION['username'],
                    );
                    $this->db->insert(db_prefix() . 'history',$data_array_result_add);
            }
        }
        return true;    
    }
    return false;
    }
    public function get_purchaseRtn_detail($id){
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $this->db->select();
        $this->db->from(db_prefix() . 'history');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $year);
        $this->db->where(db_prefix() . 'history.OrderID', $id);
       return $this->db->get()->result_array();
        // echo $this->db->last_query();die;
    }
}