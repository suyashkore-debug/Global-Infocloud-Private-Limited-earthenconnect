<?php

	

	defined('BASEPATH') or exit('No direct script access allowed');

	

	class Clients_model extends App_Model

	{

		private $contact_columns;

		

		public function __construct()

		{

			parent::__construct();

			

			$this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);

			

			$this->load->model(['client_vault_entries_model', 'client_groups_model', 'statement_model']);

		}

		

		/**

			* Get client object based on passed clientid if not passed clientid return array of all clients

			* @param  mixed $id    client id

			* @param  array  $where

			* @return mixed

		*/

		public function get($id = '', $where = [])

		{

			$selected_company = $this->session->userdata('root_company');

			

			

			$this->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'clients')) . ',' . get_sql_select_client_company().',

			' . db_prefix() . 'contacts.kms,' . db_prefix() . 'contacts.FLNO1,' . db_prefix() . 'contacts.Pan,' . db_prefix() . 'contacts.Aadhaarno,

			' . db_prefix() . 'contacts.istcs,' . db_prefix() . 'contacts.TcsStartDate,' . db_prefix() . 'contacts.phonenumber as altnumber,

			'. db_prefix() . 'contacts.BalancesYN,'. db_prefix() . 'contacts.BalancelYN,'. db_prefix() . 'contacts.pincode as pincodes,

			tblxx_statelist.state_name,tblxx_citylist.city_name,tblaccountlocations.LocationTypeID,tblStationMaster.StationName AS Station');

			

			$this->db->join(db_prefix() . 'countries', '' . db_prefix() . 'countries.country_id = ' . db_prefix() . 'clients.country', 'left');

			$this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND '. db_prefix() .'contacts.PlantID = ' . db_prefix() . 'clients.PlantID AND  ' . db_prefix() . 'clients.PlantID = '.$selected_company, 'left');

			$this->db->join(db_prefix() .'xx_statelist', db_prefix() .'xx_statelist.short_name = '.db_prefix() .'clients.state',"LEFT");

		    $this->db->join(db_prefix() .'xx_citylist', db_prefix() .'xx_citylist.id = '.db_prefix() .'clients.city',"LEFT");

		    $this->db->join(db_prefix() .'StationMaster', db_prefix() .'StationMaster.id = '.db_prefix() .'clients.StationName',"LEFT");

		    $this->db->join(db_prefix() .'accountlocations', 'tblaccountlocations.AccountID = tblclients.AccountID AND tblaccountlocations.PlantID = tblclients.PlantID',"LEFT");

		    if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {

				$this->db->where($where);

			}

			

			if ($id) {

				$this->db->where(db_prefix() . 'clients.AccountID', $id);

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

		

		public function Getaccountgroupssub()

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'accountgroupssub.*');

			$this->db->where(db_prefix() . 'accountgroupssub.SubActGroupID1', '100056');

			$this->db->order_by(db_prefix() . 'accountgroupssub.SubActGroupName', 'ASC');

			return $this->db->get('tblaccountgroupssub')->result_array();

		}

//==================== Get Only Party List =====================================

	public function GetPartyList()

	{

		$selected_company = $this->session->userdata('root_company');

		$this->db->select(db_prefix() . 'clients.*');

		$this->db->where(db_prefix() . 'clients.SubActGroupID1', '100056');

		$this->db->order_by(db_prefix() . 'clients.company', 'ASC');

		return $this->db->get('tblclients')->result_array();

	}

		

		public function SaveAccountDetails($Data)

		{

			$ShippingData = json_decode($Data['ShippingData'], true);

			

			unset($Data['ShippingData']);

			$prefix = "C";

			$next_cust_numberval = (int) get_option('next_customer_number');

			$next_cust_number = $prefix.str_pad($next_cust_numberval,5,'0',STR_PAD_LEFT);

			$AccountID = $next_cust_number;

			$location_type = $Data["location_type"];

			$selected_company = $this->session->userdata('root_company');

			$FY = $this->session->userdata('finacial_year');

			$LogID = $this->session->userdata('username');

			$route = $Data["route"];

			$routeArray = $route;

			$routeArraylen = count($routeArray);

			$CompAssign = $Data["CompSerializedArr"];

			$CompAssignArray = json_decode($CompAssign, true);

			$CompAssignArraylen = count($CompAssignArray);

			

			$ClientArray = array(

                'AccountID' =>strtoupper($AccountID),

                'company' =>$Data["AccoountName"],

                'ActGroupID' =>'10035',

                'SubActGroupID1' =>'100056',

                'SubActGroupID' =>'1000012',

                'Cust_group' =>$Data["subgroup"],

                'phonenumber' =>$Data["phonenumber"],

                'altphonenumber' =>$Data["altphonenumber"],

                'gsttype' =>$Data["gsttype"],

                'vat' =>strtoupper($Data["vat"]),

                'DistributorType' =>$Data["groups_in"],
                'country' => isset($Data["country"]) ? $Data["country"] : 0,

                'state' =>$Data["state"],

                'city' =>$Data["city"],

                'address' =>$Data["address"],

                'Address3' =>$Data["Address3"],

                'zip' =>$Data["zip"],

                'MaxCrdAmt' =>$Data["MaxCrdAmt"],

                'Blockyn' =>$Data["Blockyn"],

                'SalesFrequency' =>$Data["SalesFrequency"],

    			'dis_per' =>$Data["dis_per"],

    			'dis_per_taxable' =>$Data["dis_per_taxable"],

    			'cd' =>$Data["cd"],

    			'rate_print' =>$Data["rate_print"],

    			'credit_days' =>$Data["credit_days"],

    			'crate_limit' =>$Data["crate_limit"],

    			'article' =>$Data["article"],

                'StationName' =>$Data["StationName"],

                'ActSalestype' =>$Data["ActSalestype"],

                'bill_till_bal' =>$Data["bill_till_bal"],

    			'FreshReturn' =>$Data["FreshReturn"],

    			'DamageReturn' =>$Data["DamageReturn"],

    			'latitude' =>$Data["Latitude"],

    			'longitude' =>$Data["Longitude"],

                'StartDate' =>to_sql_date($Data["StartDate"]),

                'shipping_state' =>$Data["shipping_state"],

                'shipping_city' =>$Data["shipping_city"],

                'shipping_street' =>$Data["shipping_street"],

                'shipping_zip' =>$Data["shipping_zip"],

                'active' =>$Data["active"],

                'datecreated' =>date('Y-m-d H:i:s'),

                'addedfrom' =>$LogID,

                'RoutePoint'=>$Data["route_point"],

                'Trade_Type'=>$Data["TradeType"],

			);

			

			$ContactsArray = array(

                'PlantID' =>$selected_company,

                'AccountID' =>strtoupper($AccountID),

                'firstname' =>$Data["firstname"],

                'lastname' =>$Data["lastname"],

                'title' =>$Data["title"],

                'email' =>$Data["email"],

                'kms' =>$Data["kms"],

                'FLNO1' =>$Data["FLNO1"],

    			'expiry_licence' =>to_sql_date($Data["expiry_licence"]),

                'Pan' =>$Data["Pan"],

                'Aadhaarno' =>$Data["Aadhaarno"],

                'istcs' =>$Data["istcs"],

                'TcsStartDate' =>to_sql_date($Data["TcsStartDate1"]),

                'BalancesYN' =>$Data["BalancesYN"],

			);

			

			// Create Own Distributor Type

			$DistType = array(

			    "PlantID"=>$selected_company,

			    "name"=>$Data["AccoountName"],

			    "AccountID"=>strtoupper($AccountID),

			);

			$rootcompany = $this->get_rootcompany();

			$isinsert = 0;

			foreach($rootcompany as $r_company){ 

				$ClientArray['PlantID'] = $r_company["id"];

				$this->db->insert(db_prefix() . 'clients',$ClientArray);

				$LastId = $this->db->insert_id();

				if($LastId){

				    $ContactsArray['PlantID'] = $r_company["id"];

					$this->db->insert(db_prefix() . 'contacts',$ContactsArray);

					

					$locType = array(

                        'PlantID' => $r_company["id"],

                        'AccountID' => strtoupper($AccountID),

                        'LocationTypeID' => $location_type

					);

					$this->db->insert(db_prefix() . 'accountlocations',$locType);

					

					// Insert Account Route

    				for($k=0; $k<=$routeArraylen; $k++) {

    					$RouteID = $routeArray[$k];

    					$InsAccountRoute = array(

                            'PlantID' =>$r_company["id"],

                            'AccountID' =>strtoupper($AccountID),

                            'RouteID' =>$RouteID,

    					);

    					$this->db->insert(db_prefix() . 'accountroutes',$InsAccountRoute);

    				}

					$isinsert++;

				}

			}

			if($isinsert > 0){

			    $next_number = get_option('next_customer_number');

				// Update next number in settings

				$next_number = $next_number+1;

				$this->db->where('name', 'next_customer_number');

				$this->db->update(db_prefix() . 'options',['value' =>  $next_number,]);

				

				$next_number = (int) get_option('article');

				// Update next number in settings

				$next_number = $next_number+1;

				$this->db->where('name', 'article');

				$this->db->update(db_prefix() . 'options',['value' =>  $next_number,]);

			}

			if($LastId){

				// Insert Company Assigned AND Opening Balance

				$k=0;

				foreach($rootcompany as $r_company){ 

					//for($k=0; $k<$CompAssignArraylen; $k++) {

					$PlantID = $r_company["id"];

					$StaffID = $CompAssignArray[$k][1];

					$OBal = $CompAssignArray[$k][2];

					$DrCr = $CompAssignArray[$k][3];

					

					$InsAccountAdmin = array(

                        'staff_id' =>$StaffID,

                        'customer_id' =>strtoupper($AccountID),

                        'company_id' =>$PlantID,

                        'date_assigned' =>date('Y-m-d H:i:s')

					);

					$this->db->insert(db_prefix() . 'customer_admins',$InsAccountAdmin);

					if($DrCr == "CR"){

						$OBal = '-'.$OBal;

					}

					

					$InsAccountBal = array(

                        'PlantID' =>$PlantID,

                        'AccountID' =>strtoupper($AccountID),

                        'BAL1' =>$OBal,

                        'FY' =>$FY

					);

					$this->db->insert(db_prefix() . 'accountbalances',$InsAccountBal);

					$k++;

				}

				foreach ($ShippingData as $index => $shipdata) {

					if ($index == 0) {

						continue;

					}

					$shipwisedata = array(

    					'AccountID' => strtoupper($AccountID),

    					'ShippingState' => $shipdata['shipping_state'],

    					'ShippingCity' => $shipdata['shipping_city'],

    					'ShippingAdrees' => $shipdata['ShippingAdrees'],

    					'ShippingPin' => $shipdata['ShippingPin'],

    					'UserID' => $LogID,

    					'TransDate' => date('Y-m-d H:i:s')

					);

					$this->db->insert(db_prefix() . 'clientwiseshippingdata', $shipwisedata);

				}

			    $next_cust_numberval = (int) get_option('next_customer_number');

			    $next_cust_number = $prefix.str_pad($next_cust_numberval,5,'0',STR_PAD_LEFT);

				return $next_cust_number;

			}else{

				return false;

			}

		}

		

		public function UpdateAccountDetails($Data)

		{

			$ShippingData = json_decode($Data['ShippingData'], true);

			// echo $Data["gsttype"];die;

			unset($Data['ShippingData']);

			

			$AccountID = $Data["AccountID"];

			$location_type = $Data["location_type"];

			$selected_company = $this->session->userdata('root_company');

			$FY = $this->session->userdata('finacial_year');

			$LogID = $this->session->userdata('username');

			$route = $Data["route"];

			

			

			$routeArray = $route;

			$routeArraylen = count($routeArray);

            

			$CompAssign = $Data["CompSerializedArr"];

			$CompAssignArray = json_decode($CompAssign, true);

			

			$CompAssignArraylen = count($CompAssignArray);

			

			$ClientArray = array(

                'company' =>$Data["AccoountName"],

                'Cust_group' =>$Data["subgroup"],

                'phonenumber' =>$Data["phonenumber"],

                'altphonenumber' =>$Data["altphonenumber"],

                'gsttype' =>$Data["gsttype"],

                'vat' =>strtoupper($Data["vat"]),

                'DistributorType' =>$Data["groups_in"],
                'country' => isset($Data["country"]) ? $Data["country"] : 0,

    			'dis_per' =>$Data["dis_per"],

    			'dis_per_taxable' =>$Data["dis_per_taxable"],

    			'cd' =>$Data["cd"],

    			'rate_print' =>$Data["rate_print"],

    			'article' =>$Data["article"],

                'state' =>$Data["state"],

                'city' =>$Data["city"],

                'address' =>$Data["address"],

                'Address3' =>$Data["Address3"],

                'zip' =>$Data["zip"],

                'MaxCrdAmt' =>$Data["MaxCrdAmt"],

                'Blockyn' =>$Data["Blockyn"],

                'SalesFrequency' =>$Data["SalesFrequency"],

                'StationName' =>$Data["StationName"],

                'ActSalestype' =>$Data["ActSalestype"],

                'bill_till_bal' =>$Data["bill_till_bal"],

    			'credit_days' =>$Data["credit_days"],

    			'crate_limit' =>$Data["crate_limit"],

    			'FreshReturn' =>$Data["FreshReturn"],

    			'DamageReturn' =>$Data["DamageReturn"],

    			'latitude' =>$Data["Latitude"],

    			'longitude' =>$Data["Longitude"],

                'StartDate' =>to_sql_date($Data["StartDate"]),

                'shipping_state' =>$Data["shipping_state"],

                'shipping_city' =>$Data["shipping_city"],

                'shipping_street' =>$Data["shipping_street"],

                'shipping_zip' =>$Data["shipping_zip"],

                'active' =>$Data["active"],

                'Lupdate' =>date('Y-m-d H:i:s'),

                'UserID2' =>$LogID,

                'RoutePoint' =>$Data["route_point"],

                'Trade_Type'=>$Data["TradeType"],

			);

			

			$ContactsArray = array(

                'firstname' =>$Data["firstname"],

                'lastname' =>$Data["lastname"],

                'title' =>$Data["title"],

                'email' =>$Data["email"],

                'kms' =>$Data["kms"],

                'FLNO1' =>$Data["FLNO1"],

    			'expiry_licence' =>to_sql_date($Data["expiry_licence"]),

                'Pan' =>$Data["Pan"],

                'Aadhaarno' =>$Data["Aadhaarno"],

                'istcs' =>$Data["istcs"],

                'TcsStartDate' =>to_sql_date($Data["TcsStartDate1"]),

                'BalancesYN' =>$Data["BalancesYN"],

                'Lupdate' =>date('Y-m-d H:i:s'),

                'UserID2' =>$LogID

			);

			

			$this->db->where('AccountID', $AccountID);

			$this->db->where('PlantID', $selected_company);

			$this->db->update(db_prefix() . 'clients', $ClientArray);

			

			

			foreach ($ShippingData as $index => $shipdata) {

				

				$shipping_id = $shipdata['shipping_id']; 

				

				if(!empty($shipping_id)){

					$update_arr = [

					'AccountID' => strtoupper($AccountID),

					'ShippingState' => $shipdata['shipping_state'],

					'ShippingCity' => $shipdata['shipping_city'],

					'ShippingAdrees' => $shipdata['ShippingAdrees'],

					'ShippingPin' => $shipdata['ShippingPin'],

					];

					$this->db->where('id', $shipping_id);

					$this->db->where('AccountID', $AccountID);

					$this->db->update(db_prefix() . 'clientwiseshippingdata', $update_arr);

				}else{

					$shipwisedata = array(

					'AccountID' => strtoupper($AccountID),

					'ShippingState' => $shipdata['shipping_state'],

					'ShippingCity' => $shipdata['shipping_city'],

					'ShippingAdrees' => $shipdata['ShippingAdrees'],

					'ShippingPin' => $shipdata['ShippingPin'],

					'UserID' => $LogID,

					'TransDate' => date('Y-m-d H:i:s')

					);

					$this->db->insert(db_prefix() . 'clientwiseshippingdata', $shipwisedata);

				}

			}

			// Update and insert location type

			

			$CheckLocationRecord = $this->ChkLocationRecord($AccountID);

			if($CheckLocationRecord){

				$locType = array(

                    'LocationTypeID' => $location_type,

                    'Lupdate' =>date('Y-m-d H:i:s'),

                    'UserID2' =>$LogID

				);

				$this->db->where('AccountID', $AccountID);

				$this->db->where('PlantID', $selected_company);

				$this->db->update(db_prefix() . 'accountlocations', $locType);

			}else{

				$locType = array(

                    'LocationTypeID' => $location_type,

                    'PlantID' =>$selected_company,

                    'AccountID' =>$AccountID

				);

				$this->db->insert(db_prefix() . 'accountlocations',$locType);

			}

			

			$CheckContactRecord = $this->ChkContactRecord($AccountID);

			if($CheckContactRecord){

				$this->db->where('AccountID', $AccountID);

				$this->db->where('PlantID', $selected_company);

				$this->db->update(db_prefix() . 'contacts', $ContactsArray);

			}else{

				$ContactsArray['AccountID'] = $AccountID;

				$ContactsArray['PlantID'] = $selected_company;

				$this->db->insert(db_prefix() . 'contacts',$ContactsArray);

			}

			

			$this->db->where('AccountID', $AccountID);

			$this->db->where('PlantID', $selected_company);

			$this->db->delete(db_prefix() . 'accountroutes');

			

			// Insert Account Route

            for($k=0; $k<=$routeArraylen; $k++) {

                $RouteID = $routeArray[$k];

                

                $InsAccountRoute = array(

				'PlantID' =>$selected_company,

				'AccountID' =>strtoupper($AccountID),

				'RouteID' =>$RouteID,

				'UserID2' =>$LogID,

				'Lupdate' =>date('Y-m-d H:i:s'),

                );

                $this->db->insert(db_prefix() . 'accountroutes',$InsAccountRoute);

			}

            

			// Insert Company Assigned AND Opening Balance

            for($k=0; $k<$CompAssignArraylen; $k++) {

                $PlantID = $CompAssignArray[$k][0];

                $StaffID = $CompAssignArray[$k][1];

                $OBal = $CompAssignArray[$k][2];

                $DrCr = $CompAssignArray[$k][3];

                

                $CheckAdminRecord = $this->ChkAdminRecord($AccountID,$PlantID);

                if($CheckAdminRecord){

                    $UpdateAccountAdmin = array(

    					'staff_id' =>$StaffID,

    					'UserID2' =>$LogID,

    					'Lupdate' =>date('Y-m-d H:i:s'),

                    );

                    $this->db->where('customer_id', $AccountID);

                    $this->db->where('company_id', $PlantID);

                    $this->db->update(db_prefix() . 'customer_admins', $UpdateAccountAdmin);

				}else{

                    $InsAccountAdmin = array(

    					'staff_id' =>$StaffID,

    					'customer_id' =>strtoupper($AccountID),

    					'company_id' =>$PlantID,

    					'date_assigned' =>date('Y-m-d H:i:s')

                    );

                    $this->db->insert(db_prefix() . 'customer_admins',$InsAccountAdmin);

				}

                //if($LogID == "admin"){

				if($DrCr == "CR"){

					$OBal = '-'.$OBal;

				}

				$CheckBalRecord = $this->ChkBalRecord($AccountID,$PlantID,$FY);

				$staff_user_id = $this->session->userdata('staff_user_id');

				if($CheckBalRecord){

					if($staff_user_id == "3"){

						$UpdateAccountBal = array(

    						'BAL1' =>$OBal,

    						'UserID2' =>$LogID,

    						'Lupdate' =>date('Y-m-d H:i:s'),

						);

						$this->db->where('AccountID', $AccountID);

						$this->db->where('PlantID', $PlantID);

						$this->db->where('FY', $FY);

						$this->db->update(db_prefix() . 'accountbalances', $UpdateAccountBal);

					}

                }else{

					$InsAccountBal = array(

    					'PlantID' =>$PlantID,

    					'AccountID' =>strtoupper($AccountID),

    					'BAL1' =>$OBal,

    					'FY' =>$FY

					);

					$this->db->insert(db_prefix() . 'accountbalances',$InsAccountBal);

				}

                //}

			}

			return true;

		}

		

		// Check AccountBalanceMaster Record

		public function ChkBalRecord($AccountID,$PlantID,$FY)

		{

			$this->db->select(db_prefix() . 'accountbalances.*');

			$this->db->where('AccountID', $AccountID);

			$this->db->where('PlantID', $PlantID);

			$this->db->where('FY', $FY);

			$this->db->from(db_prefix() . 'accountbalances');

			$data =  $this->db->get()->row();

			return $data;

		}

		

		// Check Account Sales Person Record

		public function ChkAdminRecord($AccountID,$PlantID)

		{

			$this->db->select(db_prefix() . 'customer_admins.*');

			$this->db->where('customer_id', $AccountID);

			$this->db->where('company_id', $PlantID);

			$this->db->from(db_prefix() . 'customer_admins');

			$data =  $this->db->get()->row();

			return $data;

		}

		

		// Check Account Location Type

		public function ChkLocationRecord($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'accountlocations.*');

			$this->db->where('AccountID', $AccountID);

			$this->db->where('PlantID', $selected_company);

			$this->db->from(db_prefix() . 'accountlocations');

			$data =  $this->db->get()->row();

			return $data;

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

		public function get_AccountDetails($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('tblclients.*, tblaccountlocations.LocationTypeID,tblcontacts.firstname,tblcontacts.lastname,tblcontacts.kms,tblcontacts.FLNO1,tblcontacts.expiry_licence,

			tblcontacts.Pan,tblcontacts.Aadhaarno,tblcontacts.TcsStartDate,tblcontacts.title,tblcontacts.istcs,tblcontacts.BalancesYN,tblcontacts.email');

			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

			//$this->db->where(db_prefix() . 'clients.SubActGroupID', "60001004");

			$this->db->where(db_prefix() . 'clients.AccountID', $AccountID);

			$this->db->join('tblcontacts', 'tblcontacts.AccountID = tblclients.AccountID AND tblcontacts.PlantID = tblclients.PlantID','LEFT');

			$this->db->join('tblaccountlocations', 'tblaccountlocations.AccountID = tblclients.AccountID AND tblaccountlocations.PlantID = tblclients.PlantID','LEFT');

			$result = $this->db->get('clients')->row();

			if($result){

				$result->Type = 'client';

				//$AccountItemDiv = $this->getclientitem_division($AccountID);

				$AccountCompany = $this->getclientCompany($AccountID);

				$AccountOpnBal = $this->getAccoountOpnBal($AccountID);

				$AccountRoute = $this->getAccoountRoute($AccountID);

				$RouteIDs = array();

				foreach($AccountRoute as $key=>$val){

				    array_push($RouteIDs,$val["RouteID"]);

				}

				if($RouteIDs){

				    $AccountRoutePoints = $this->GetAccoountRoutePoints($RouteIDs);

				}else{

				    $AccountRoutePoints = [];

				}

				$CityList = $this->getCityList($result->state);

				$CityList2 = $this->getCityList($result->shipping_state);

				//$result->ItemDiv = $AccountItemDiv;

				$result->Company = $AccountCompany;

				$result->OpnBal = $AccountOpnBal;

				$result->Route = $AccountRoute;

				$result->AccountRoutePoints = $AccountRoutePoints;

				$result->CityList = $CityList;

				$result->CityList2 = $CityList2;

				

				// Retrieve shipping data

				

				$this->db->select('tblclientwiseshippingdata.*,tblxx_statelist.state_name,tblxx_citylist.city_name');

				$this->db->from(db_prefix() . 'clientwiseshippingdata');

				$this->db->join('tblxx_statelist', 'tblxx_statelist.short_name = tblclientwiseshippingdata.ShippingState','LEFT');

				$this->db->join('tblxx_citylist', 'tblxx_citylist.id = tblclientwiseshippingdata.ShippingCity','LEFT');

				$this->db->where('AccountID', $AccountID);

				$shippingdata = $this->db->get()->result();

				$result->shippingdata = $shippingdata;

				}else{

				

				$regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';

				$this->db->select(db_prefix() . 'staff.*');

				//$this->db->where(db_prefix() . 'staff.staff_comp REGEXP',$regExp);

				$this->db->where(db_prefix() . 'staff.AccountID',$AccountID);

				$this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');

				$result = $this->db->get('staff')->row();

				if($result){

					$result->Type = 'staff';

				}

			}

			

			return $result;

			

		}

		

		public function GetAllCustomerList()

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('tblclients.AccountID,tblclients.company,tblclients.active,tblcontacts.firstname,tblcontacts.lastname,

			tblcustomers_groups.name,tblxx_statelist.state_name,tblxx_citylist.city_name');

			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

			$this->db->where(db_prefix() . 'clients.SubActGroupID1', "100056");

			$this->db->join('tblcontacts', 'tblcontacts.AccountID = tblclients.AccountID AND tblcontacts.PlantID = tblclients.PlantID','LEFT');

			$this->db->join('tblcustomers_groups', 'tblcustomers_groups.id = tblclients.DistributorType','LEFT');

			$this->db->join('tblxx_citylist', 'tblxx_citylist.id = tblclients.city','LEFT');

			$this->db->join('tblxx_statelist', 'tblxx_statelist.short_name = tblclients.state','LEFT');

			$result = $this->db->get('tblclients')->result_array();

			return $result;

		}

		

		public function get_AccountDetailsAllPlant($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('tblclients.*, tblaccountlocations.LocationTypeID,tblcontacts.firstname,tblcontacts.lastname,tblcontacts.kms,tblcontacts.FLNO1,tblcontacts.expiry_licence,

			tblcontacts.Pan,tblcontacts.Aadhaarno,tblcontacts.TcsStartDate,tblcontacts.title,tblcontacts.istcs,tblcontacts.BalancesYN,tblcontacts.email');

			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

			//$this->db->where(db_prefix() . 'clients.SubActGroupID', "60001004");

			$this->db->where(db_prefix() . 'clients.AccountID', $AccountID);

			$this->db->join('tblcontacts', 'tblcontacts.AccountID = tblclients.AccountID AND tblcontacts.PlantID = tblclients.PlantID','LEFT');

			$this->db->join('tblaccountlocations', 'tblaccountlocations.AccountID = tblclients.AccountID AND tblaccountlocations.PlantID = tblclients.PlantID','LEFT');

			$result = $this->db->get('clients')->row();

			if($result){

				$result->Type = 'client';

				$AccountItemDiv = $this->getclientitem_division($AccountID);

				$AccountCompany = $this->getclientCompany($AccountID);

				$AccountOpnBal = $this->getAccoountOpnBal($AccountID);

				$AccountRoute = $this->getAccoountRoute($AccountID);

				$CityList = $this->getCityList($result->state);

				$CityList2 = $this->getCityList($result->shipping_state);

				$result->ItemDiv = $AccountItemDiv;

				$result->Company = $AccountCompany;

				$result->OpnBal = $AccountOpnBal;

				$result->Route = $AccountRoute;

				$result->CityList = $CityList;

				$result->CityList2 = $CityList2;

				

				// Retrieve shipping data

				

				$this->db->select('tblclientwiseshippingdata.*,tblxx_statelist.state_name,tblxx_citylist.city_name');

				$this->db->from(db_prefix() . 'clientwiseshippingdata');

				$this->db->join('tblxx_statelist', 'tblxx_statelist.short_name = tblclientwiseshippingdata.ShippingState','LEFT');

				$this->db->join('tblxx_citylist', 'tblxx_citylist.id = tblclientwiseshippingdata.ShippingCity','LEFT');

				$this->db->where('AccountID', $AccountID);

				$shippingdata = $this->db->get()->result();

				

				$result->shippingdata = $shippingdata;

				}else{

				

				$regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';

				$this->db->select(db_prefix() . 'staff.*');

				//$this->db->where(db_prefix() . 'staff.staff_comp REGEXP',$regExp);

				$this->db->where(db_prefix() . 'staff.AccountID',$AccountID);

				$this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');

				$result = $this->db->get('staff')->row();

				if($result){

					$result->Type = 'staff';

					}else{

					$this->db->select('tblclients.*');

					//$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

					//$this->db->where(db_prefix() . 'clients.SubActGroupID', "60001004");

					$this->db->where(db_prefix() . 'clients.AccountID', $AccountID);

					$result = $this->db->get('clients')->row();

					if($result){

						$result->Type = 'client';

					}

				}

			}

			

			return $result;

			

		}

//============================== Get CIty List Against State COde ==============

	public function GetCityList($StateID)

	{

		$this->db->select(db_prefix() . 'xx_citylist.*');

		$this->db->where(db_prefix() . 'xx_citylist.state_id', $StateID);

		$this->db->order_by(db_prefix() . 'xx_citylist.city_name', 'ASC');

		return $this->db->get('tblxx_citylist')->result_array();

	}

//==================== Get Route Points Against Route ==========================

	public function GetRoutePoints($routes)

	{

		$this->db->select(db_prefix() . 'RoutePoints.*,tblPointsMaster.PointName');

		$this->db->join(db_prefix() . 'PointsMaster', '' . db_prefix() . 'PointsMaster.id = ' . db_prefix() . 'RoutePoints.PointID');

		$this->db->where_in(db_prefix() . 'RoutePoints.RouteID', $routes);

		$this->db->order_by(db_prefix() . 'PointsMaster.PointName', 'ASC');

		return $this->db->get('tblRoutePoints')->result_array();

	}

//==================== Check Distributor Type state ============================

    public function GetDistTypeState($DistType)

	{

		$this->db->select(db_prefix() . 'clients.*');

		$this->db->where_in(db_prefix() . 'clients.DistributorType', $DistType);

		return $this->db->get('tblclients')->row();

	}

		

		public function GetNoShowclients()

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountgroupssub.*');

			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

			$this->db->where(db_prefix() . 'clients.no_show ', '1');

			$this->db->join(db_prefix() . 'accountgroupssub', '' . db_prefix() . 'accountgroupssub.SubActGroupID = ' . db_prefix() . 'clients.SubActGroupID');

			$this->db->order_by(db_prefix() . 'clients.company', 'ASC');

			return $this->db->get('clients')->result_array();

			

		}

		public function GetNonNoShowclients()

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'clients.*,'.db_prefix() . 'accountgroupssub.*');

			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

			$this->db->where(db_prefix() . 'clients.no_show IS NULL', NULL, FALSE);

			$this->db->join(db_prefix() . 'accountgroupssub', '' . db_prefix() . 'accountgroupssub.SubActGroupID = ' . db_prefix() . 'clients.SubActGroupID');

			$this->db->order_by(db_prefix() . 'clients.company', 'ASC');

			return $this->db->get('clients')->result_array();

			

		}

		public function get_table_on_load_filter($data)

		{    

			$selected_company = $this->session->userdata('root_company');

			$wh =array();

			

			

			if($data['client_type']!=''){

				$wh[]='DistributorType = '.$data['client_type'];

			}

			if($data['distributor_state']!=''){

				$wh[]= 'state = '.$data['distributor_state'];

			}

			if($data['division']!=''){

				$wh[] = db_prefix().'clients.AccountID IN (SELECT AccountID FROM '.db_prefix().'accountitemdiv WHERE ItemDivID ='.$data['division'].')';

			}

			if($data['responsible_admin']!=''){

				$wh[]= db_prefix().'clients.AccountID IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id ='.$data['division'].' AND company_id ='.$selected_company.')';

			}

			if($data['status']!=''){

				$wh[]="`tblclients`.`active` = '".$data['status']."'";

			}

			$WHERE ='';

			if(count($wh)>0)

			{

				$WHERE = implode(" AND ",$wh);

			}

			

			$this->db->select('tblclients.userid as userid,tblclients.AccountID as AccountID,tblclients.company,tblclients.phonenumber,tblcustomer_admins.staff_id as assigned_staff,tblclients.state,tblclients.address,tblStationMaster.StationName,tblclients.city,tblclients.active,tblxx_citylist.city_name,(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tblcustomers_groups WHERE tblcustomers_groups.id = tblclients.DistributorType) as customerGroups');

			$this->db->join('tblcustomer_admins', 'tblclients.AccountID=tblcustomer_admins.customer_id AND tblclients.PlantID=tblcustomer_admins.company_id', 'left');

			$this->db->join('tblxx_citylist', 'tblclients.city=tblxx_citylist.id', 'left');

			$this->db->join('tblStationMaster', 'tblStationMaster.id=tblclients.StationName', 'left');

			$this->db->where('tblclients.SubActGroupID1', "100056");

			$this->db->where('tblclients.PlantID', $selected_company);

			if($data['distributor_state']!=''){

				$this->db->where('state', $data['distributor_state']);

			}

			if($data['client_type']!=''){

				$this->db->where('DistributorType', $data['client_type']);

			}

			if($data['division']!=''){

				$this->db->where(db_prefix().'clients.AccountID IN (SELECT AccountID FROM '.db_prefix().'accountitemdiv WHERE ItemDivID ='.$data['division'].')');

				

			}

			if($data['responsible_admin']!=''){

				

				$this->db->where(db_prefix().'clients.AccountID IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id ='.$data['responsible_admin'].' AND company_id ='.$selected_company.')');

			}

			if($data['status']!=''){

				$this->db->where(db_prefix() . 'clients.active', $data['status']);

			}

			$this->db->order_by(db_prefix().'clients.company','ASC');

			$result= $this->db->get(db_prefix() . 'clients')->result_array();

			return $result;

		}

		

		public function GetNoShowstaff()

		{

			$selected_company = $this->session->userdata('root_company');

			$regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';

			$this->db->select(db_prefix() . 'staff.*,'.db_prefix() . 'accountgroupssub.*');

			$this->db->join(db_prefix() . 'accountgroupssub', '' . db_prefix() . 'accountgroupssub.SubActGroupID = ' . db_prefix() . 'staff.SubActGroupID');

			$this->db->where(db_prefix() . 'staff.no_show ', '1');

			$this->db->where(db_prefix() . 'staff.staff_comp REGEXP',$regExp);

			$this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');

			return $this->db->get('staff')->result_array();

		}

		

		public function GetNonNoShowstaff()

		{

			$selected_company = $this->session->userdata('root_company');

			$regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';

			$this->db->select(db_prefix() . 'staff.*,'.db_prefix() . 'accountgroupssub.*');

			$this->db->join(db_prefix() . 'accountgroupssub', '' . db_prefix() . 'accountgroupssub.SubActGroupID = ' . db_prefix() . 'staff.SubActGroupID');

			$this->db->where(db_prefix() . 'staff.no_show IS NULL', NULL, FALSE);

			$this->db->where(db_prefix() . 'staff.staff_comp REGEXP',$regExp);

			$this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');

			return $this->db->get('staff')->result_array();

		}

		

		public function getroutebyclient($id)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('*');

			$this->db->where(db_prefix() . 'accountroutes.AccountID', $id);

			$this->db->where(db_prefix() . 'accountroutes.PlantID', $selected_company);

			//$this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'customer_admins.staffid', 'left');

			

			

			return $this->db->get('tblaccountroutes')->result_array();

		}

		

		public function getclientitem_division($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('ItemDivID,plant_assign');

			$this->db->where(db_prefix() . 'accountitemdiv.AccountID', $AccountID);

			//$this->db->where(db_prefix() . 'accountitemdiv.plant_assign', $selected_company);

			//$this->db->where(db_prefix() . 'accountitemdiv.plant_assign', $selected_company);

			//$this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'customer_admins.staffid', 'left');

			

			

			return $this->db->get(db_prefix() . 'accountitemdiv')->result_array();

		}

		

		public function getclientCompany($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'customer_admins.*');

			$this->db->where(db_prefix() . 'customer_admins.customer_id', $AccountID);

			return $this->db->get(db_prefix() . 'customer_admins')->result_array();

		}

		

		public function getAccoountOpnBal($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$FY = $this->session->userdata('finacial_year');

			$this->db->select(db_prefix() . 'accountbalances.BAL1,'.db_prefix() . 'accountbalances.PlantID,'.db_prefix() . 'accountbalances.AccountID');

			$this->db->join('tblcustomer_admins', 'tblcustomer_admins.customer_id = tblaccountbalances.AccountID AND tblcustomer_admins.company_id = tblaccountbalances.PlantID','LEFT');

			$this->db->where(db_prefix() . 'accountbalances.AccountID', $AccountID);

			$this->db->where(db_prefix() . 'accountbalances.FY', $FY);

			return $this->db->get(db_prefix() . 'accountbalances')->result_array();

		}

		

		public function getAccoountRoute($AccountID)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'accountroutes.*');

			$this->db->where(db_prefix() . 'accountroutes.AccountID', $AccountID);

			$this->db->where(db_prefix() . 'accountroutes.PlantID', $selected_company);

			return $this->db->get(db_prefix() . 'accountroutes')->result_array();

		}

		public function GetAccoountRoutePoints($Routes)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select(db_prefix() . 'RoutePoints.*,tblPointsMaster.PointName');

			$this->db->join('tblPointsMaster', 'tblPointsMaster.id = tblRoutePoints.PointID');

			$this->db->where_in(db_prefix() . 'RoutePoints.RouteID', $Routes);

			return $this->db->get(db_prefix() . 'RoutePoints')->result_array();

		}

		

		

		public function get_location_type($id)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('LocationTypeID');

			$this->db->where(db_prefix() . 'accountlocations.AccountID', $id);

			$this->db->where(db_prefix() . 'accountlocations.PlantID', $selected_company);

			return $this->db->get(db_prefix() . 'accountlocations')->row();

		}

//=============================== Get Vehicle List =============================

    public function getvehicle()

	{

		$selected_company = $this->session->userdata('root_company');

		$this->db->select(db_prefix() . 'vehicle.*');

		$this->db->where(db_prefix() . 'vehicle.PlantID', $selected_company);

		$this->db->order_by('VehicleID', 'ASC');

		return $this->db->get(db_prefix() . 'vehicle')->result_array();

	}

	

//====================== Get Staff List Type Wise ==============================

    public function GetStaffListTypeWise($StaffType)

	{

		$selected_company = $this->session->userdata('root_company');

		$this->db->select(db_prefix() . 'staff.*');

		$this->db->where(db_prefix() . 'staff.SubActGroupID', $StaffType);

		$this->db->order_by('firstname,lastname', 'ASC');

		return $this->db->get(db_prefix() . 'staff')->result_array();

	}

		

		/**

			* Get client object based on passed clientid if not passed clientid return array of all clients

			* @param  mixed $id    client id

			* @param  array  $where

			* @return mixed

		*/

		public function get1()

		{

			$this->db->select('*');

			

			//$this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'customer_admins.staffid', 'left');

			

			

			return $this->db->get('tblso_enquiry')->result_array();

		}

		

		/**

			* Get client object based on passed clientid if not passed clientid return array of all clients

			* @param  mixed $id    client id

			* @param  array  $where

			* @return mixed

		*/

		public function get_customers_assigned_person()

		{

			$this->db->select('*');

			

			//$this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'customer_admins.staffid', 'left');

			

			

			return $this->db->get(db_prefix() . 'staff')->result_array();

		}

		

		/**

			Check AccountID 

		*/

		public function check_AccountID($AccountID = '')

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->where('AccountID', $AccountID);

			$this->db->where('PlantID', $selected_company);

			

			$details = $this->db->get(db_prefix() . 'clients')->row();

			if($details){

				return true;

				}else{

				return false;

			}

		}

		

		/**

			Check AccountID 

		*/

		public function check_company($companyName = '')

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->where('company', $companyName);

			$this->db->where('PlantID', $selected_company);

			

			$details = $this->db->get(db_prefix() . 'clients')->row();

			if($details){

				return true;

				}else{

				return false;

			}

		}

		

		

		/**

			* Get customers contacts

			* @param  mixed $customer_id

			* @param  array  $where       perform where in query

			* @return array

		*/

		public function get_contacts($customer_id = '', $where = ['active' => 1])

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->where($where);

			if ($customer_id != '') {

				$this->db->where('AccountID', $customer_id);

			}

			$this->db->where('PlantID', $selected_company);

			$this->db->order_by('is_primary', 'DESC');

			

			return $this->db->get(db_prefix() . 'contacts')->result_array();

		}

		

		/**

			* Get customers Route

			* @return array

		*/

		public function getroute()

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->select('*');

			$this->db->where(db_prefix() . 'route.PlantID', $selected_company);

			$this->db->order_by('name', 'ASC');

			

			return $this->db->get(db_prefix() . 'route')->result_array();

		}

	

		

		

		

		/**

			* Get All State

			* 

			* @return array

		*/

		public function getallstate()

		{

			

			$this->db->order_by('state_name', 'ASE');

			return $this->db->get(db_prefix() . 'xx_statelist')->result_array();

		}


		/**
			* Get all countries
			*
			* @return array
		*/

		public function getallcountries()

		{

			$this->db->order_by('short_name', 'ASC');

			return $this->db->get(db_prefix() . 'countries')->result_array();

		}


		/**
			* Get all states for given country
			*
			* @param int $country_id
			* @return array
		*/

		public function get_states_by_country($country_id)

		{

			$this->db->where('country_id', $country_id);

			$this->db->order_by('state_name', 'ASC');

			return $this->db->get(db_prefix() . 'xx_statelist')->result_array();

		}

		

		/**

			* Get All Account Subgroup

			* 

			* @return array

		*/

		public function VendorActSubgroup()

		{

			// show only CURRENT LIABILITIES

			

			$this->db->where('ActGroupID', '50003');

			$this->db->order_by('SubActGroupName', 'ASE');

			return $this->db->get(db_prefix() . 'accountgroupssub')->result_array();

		}

		

		public function StaffActSubgroup()

		{

			// show only Staff

			

			$this->db->where('ActGroupID', '60002');

			$this->db->order_by('SubActGroupName', 'ASE');

			return $this->db->get(db_prefix() . 'accountgroupssub')->result_array();

		}

		

		public function get_all_beat()

		{

			

			

			return $this->db->get(db_prefix() . 'beat')->result_array();

		}

		

		/**

			* Get Root Company

			

			* @return array

		*/

		

		public function get_rootcompany()

		{

			

			return $this->db->get(db_prefix() . 'rootcompany')->result_array();

		}

		

		

		/**

			* Get Item Division

			

			* @return array

		*/

		

		public function get_itemDivision()

		{

			//$selected_company = $this->session->userdata('root_company');

			

			//$this->db->where('PlantID', $selected_company);

			

			return $this->db->get(db_prefix() . 'items_groups')->result_array();

		}

		public function get_StationList()

		{

			$this->db->where('status', '1');

			

			return $this->db->get(db_prefix() . 'StationMaster')->result_array();

		}

		/**

			* Get single contacts

			* @param  mixed $id contact id

			* @return object

		*/

		public function get_contact($id)

		{

			$this->db->where('id', $id);

			

			return $this->db->get(db_prefix() . 'contacts')->row();

		}

		

		

		

		/**

			* @param array $_POST data

			* @param client_request is this request from the customer area

			* @return integer Insert ID

			* Add new client to database

		*/

		public function add($data, $client_or_lead_convert_request = false)

		{

			

			

			$contact_data = [];

			/*$selected_company = $this->session->userdata('root_company');

				$data['PlantID'] = $selected_company;

			$data['StartDate'] = date('Y-m-d H:i:s');*/

			

			if (is_staff_logged_in()) {

				$data['addedfrom'] = $this->session->userdata('username');

			}

			

			// New filter action

			$data = hooks()->apply_filters('before_client_added', $data);

			

			$this->db->insert(db_prefix() . 'clients', $data);

			

			$userid = $this->db->insert_id();

			if ($userid) {

				

				

				$log = 'ID: ' . $userid;

				

				if ($log == '' && isset($contact_id)) {

					$log = get_contact_full_name($contact_id);

				}

				

				$isStaff = null;

				if (!is_client_logged_in() && is_staff_logged_in()) {

					$log .= ', From Staff: ' . get_staff_user_id();

					$isStaff = get_staff_user_id();

				}

				

				hooks()->do_action('after_client_added', $userid);

				

				log_activity('New Client Created [' . $log . ']', $isStaff);

			}

			

			return $userid;

		}

		

		/**

			* @param  array $_POST data

			* @param  integer ID

			* @return boolean

			* Update client informations

		*/

		public function update($data, $id, $client_request = false)

		{

			if (isset($data['update_all_other_transactions'])) {

				$update_all_other_transactions = true;

				unset($data['update_all_other_transactions']);

			}

			

			if (isset($data['update_credit_notes'])) {

				$update_credit_notes = true;

				unset($data['update_credit_notes']);

			}

			

			$affectedRows = 0;

			if (isset($data['custom_fields'])) {

				$custom_fields = $data['custom_fields'];

				if (handle_custom_fields_post($id, $custom_fields)) {

					$affectedRows++;

				}

				unset($data['custom_fields']);

			}

			

			/*if (isset($data['groups_in'])) {

				$groups_in = $data['groups_in'];

				unset($data['groups_in']);

			}*/

			

			$data = $this->check_zero_columns($data);

			

			$data = hooks()->apply_filters('before_client_updated', $data, $id);

			

			$selected_company = $this->session->userdata('root_company');

			

			$this->db->where('PlantID', $selected_company);

			$this->db->where('AccountID', $id);

			$this->db->update(db_prefix() . 'clients', $data);

			

			if ($this->db->affected_rows() > 0) {

				$affectedRows++;

			}

			

			if (isset($update_all_other_transactions) || isset($update_credit_notes)) {

				$transactions_update = [

				'billing_street'   => $data['billing_street'],

				'billing_city'     => $data['billing_city'],

				'billing_state'    => $data['billing_state'],

				'billing_zip'      => $data['billing_zip'],

				'billing_country'  => $data['billing_country'],

				'shipping_street'  => $data['shipping_street'],

				'shipping_city'    => $data['shipping_city'],

				'shipping_state'   => $data['shipping_state'],

				'shipping_zip'     => $data['shipping_zip'],

				'shipping_country' => $data['shipping_country'],

                ];

				if (isset($update_all_other_transactions)) {

					

					// Update all invoices except paid ones.

					$this->db->where('clientid', $id);

					$this->db->where('status !=', 2);

					$this->db->update(db_prefix() . 'invoices', $transactions_update);

					if ($this->db->affected_rows() > 0) {

						$affectedRows++;

					}

					

					// Update all estimates

					$this->db->where('clientid', $id);

					$this->db->update(db_prefix() . 'estimates', $transactions_update);

					if ($this->db->affected_rows() > 0) {

						$affectedRows++;

					}

				}

				if (isset($update_credit_notes)) {

					$this->db->where('clientid', $id);

					$this->db->where('status !=', 2);

					$this->db->update(db_prefix() . 'creditnotes', $transactions_update);

					if ($this->db->affected_rows() > 0) {

						$affectedRows++;

					}

				}

			}

			

			/*if (!isset($groups_in)) {

				$groups_in = false;

				}

				

				if ($this->client_groups_model->sync_customer_groups($id, $groups_in)) {

				$affectedRows++;

			}*/

			

			if ($affectedRows > 0) {

				hooks()->do_action('after_client_updated', $id);

				

				log_activity('Customer Info Updated [ID: ' . $id . ']');

				

				return $id;

			}

			

			return false;

		}

		

		

		/**

			* Update contact data

			* @param  array  $data           $_POST data

			* @param  mixed  $id             contact id

			* @param  boolean $client_request is request from customers area

			* @return mixed

		*/

		

		public function update_contact_new($data, $id)

		{

			$data = hooks()->apply_filters('before_update_contact', $data, $id);

			$selected_company = $this->session->userdata('root_company');

			/*echo "<pre>";

				echo $id;

				print_r($data);

			die;*/

			$this->db->where('PlantID', $selected_company);

			$this->db->where('AccountID', $id);

			$this->db->update(db_prefix() . 'contacts', $data);

			return true;

		}

		

		/**

			* Update Location data

			* @param  array  $data           $_POST data

			* @param  mixed  $id             contact id

			* @param  boolean $client_request is request from customers area

			* @return mixed

		*/

		

		public function location_update($selected_company,$id,$LocationTypeID)

		{

			

			$this->db->where('PlantID', $selected_company);

			$this->db->where('AccountID', $id);

			$data = array(

            "LocationTypeID" =>$LocationTypeID

            );

			$this->db->update(db_prefix() . 'accountlocations', $data);

			return true;

		}

		

		public function update_route($data, $id)

		{

			

			

			$selected_company = $this->session->userdata('root_company');

			

			$this->db->where('AccountID', $id);

			$this->db->where('PlantID', $selected_company);

			$this->db->delete(db_prefix() . 'accountroutes');

			

			foreach ($data as $value) {

				# code...

				$route_data = array(

                "PlantID" =>$selected_company,

                "AccountID" =>$id,

                "RouteID" =>$value

                );

				$this->db->insert(db_prefix() . 'accountroutes', $route_data);

			}

			

			

			return true;

		}

		

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

			

			$permissions        = isset($data['permissions']) ? $data['permissions'] : [];

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

			$this->db->update(db_prefix() . 'contacts', $data);

			

			if ($this->db->affected_rows() > 0) {

				$affectedRows++;

				if (isset($data['is_primary']) && $data['is_primary'] == 1) {

					$this->db->where('userid', $contact->userid);

					$this->db->where('id !=', $id);

					$this->db->update(db_prefix() . 'contacts', [

                    'is_primary' => 0,

					]);

				}

			}

			

			if ($client_request == false) {

				$customer_permissions = $this->roles_model->get_contact_permissions($id);

				if (sizeof($customer_permissions) > 0) {

					foreach ($customer_permissions as $customer_permission) {

						if (!in_array($customer_permission['permission_id'], $permissions)) {

							$this->db->where('userid', $id);

							$this->db->where('permission_id', $customer_permission['permission_id']);

							$this->db->delete(db_prefix() . 'contact_permissions');

							if ($this->db->affected_rows() > 0) {

								$affectedRows++;

							}

						}

					}

					foreach ($permissions as $permission) {

						$this->db->where('userid', $id);

						$this->db->where('permission_id', $permission);

						$_exists = $this->db->get(db_prefix() . 'contact_permissions')->row();

						if (!$_exists) {

							$this->db->insert(db_prefix() . 'contact_permissions', [

                            'userid'        => $id,

                            'permission_id' => $permission,

							]);

							if ($this->db->affected_rows() > 0) {

								$affectedRows++;

							}

						}

					}

					} else {

					foreach ($permissions as $permission) {

						$this->db->insert(db_prefix() . 'contact_permissions', [

                        'userid'        => $id,

                        'permission_id' => $permission,

						]);

						if ($this->db->affected_rows() > 0) {

							$affectedRows++;

						}

					}

				}

				if ($send_set_password_email) {

					$set_password_email_sent = $this->authentication_model->set_password_email($data['email'], 0);

				}

			}

			

			if (($client_request == true) && $send_set_password_email) {

				$set_password_email_sent = $this->authentication_model->set_password_email($data['email'], 0);

			}

			

			if ($affectedRows > 0) {

				hooks()->do_action('contact_updated', $id, $data);

			}

			

			if ($affectedRows > 0 && !$set_password_email_sent) {

				log_activity('Contact Updated [ID: ' . $id . ']');

				

				return true;

				} elseif ($affectedRows > 0 && $set_password_email_sent) {

				return [

                'set_password_email_sent_and_profile_updated' => true,

				];

				} elseif ($affectedRows == 0 && $set_password_email_sent) {

				return [

                'set_password_email_sent' => true,

				];

			}

			

			return false;

		}

		

		/**

			* Add new contact

			* @param array  $data               $_POST data

			* @param mixed  $customer_id        customer id

			* @param boolean $not_manual_request is manual from admin area customer profile or register, convert to lead

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

			

			$send_welcome_email = true;

			

			if (isset($data['donotsendwelcomeemail'])) {

				$send_welcome_email = false;

			}

			

			if (defined('CONTACT_REGISTERING')) {

				$send_welcome_email = true;

				

				// Do not send welcome email if confirmation for registration is enabled

				if (get_option('customers_register_require_confirmation') == '1') {

					$send_welcome_email = false;

				}

				

				// If client register set this contact as primary

				$data['is_primary'] = 1;

				

				if (is_email_verification_enabled() && !empty($data['email'])) {

					// Verification is required on register

					$data['email_verified_at']      = null;

					$data['email_verification_key'] = app_generate_hash();

				}

			}

			

			if (isset($data['is_primary'])) {

				$data['is_primary'] = 1;

				$this->db->where('userid', $customer_id);

				$this->db->update(db_prefix() . 'contacts', [

                'is_primary' => 0,

				]);

				} else {

				$data['is_primary'] = 0;

			}

			

			$password_before_hash = '';

			$data['userid']       = $customer_id;

			if (isset($data['password'])) {

				$password_before_hash = $data['password'];

				$data['password']     = app_hash_password($data['password']);

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

			

			$this->db->insert(db_prefix() . 'contacts', $data);

			$contact_id = $this->db->insert_id();

			

			if ($contact_id) {

				if (isset($custom_fields)) {

					handle_custom_fields_post($contact_id, $custom_fields);

				}

				// request from admin area

				if (!isset($permissions) && $not_manual_request == false) {

					$permissions = [];

					} elseif ($not_manual_request == true) {

					$permissions         = [];

					$_permissions        = get_contact_permissions();

					$default_permissions = @unserialize(get_option('default_contact_permissions'));

					if (is_array($default_permissions)) {

						foreach ($_permissions as $permission) {

							if (in_array($permission['id'], $default_permissions)) {

								array_push($permissions, $permission['id']);

							}

						}

					}

				}

				

				if ($not_manual_request == true) {

					// update all email notifications to 0

					$this->db->where('id', $contact_id);

					$this->db->update(db_prefix() . 'contacts', [

                    'invoice_emails'     => 0,

                    'estimate_emails'    => 0,

                    'credit_note_emails' => 0,

                    'contract_emails'    => 0,

                    'task_emails'        => 0,

                    'project_emails'     => 0,

                    'ticket_emails'      => 0,

					]);

				}

				foreach ($permissions as $permission) {

					$this->db->insert(db_prefix() . 'contact_permissions', [

                    'userid'        => $contact_id,

                    'permission_id' => $permission,

					]);

					

					// Auto set email notifications based on permissions

					if ($not_manual_request == true) {

						if ($permission == 6) {

							$this->db->where('id', $contact_id);

							$this->db->update(db_prefix() . 'contacts', ['project_emails' => 1, 'task_emails' => 1]);

							} elseif ($permission == 3) {

							$this->db->where('id', $contact_id);

							$this->db->update(db_prefix() . 'contacts', ['contract_emails' => 1]);

							} elseif ($permission == 2) {

							$this->db->where('id', $contact_id);

							$this->db->update(db_prefix() . 'contacts', ['estimate_emails' => 1]);

							} elseif ($permission == 1) {

							$this->db->where('id', $contact_id);

							$this->db->update(db_prefix() . 'contacts', ['invoice_emails' => 1, 'credit_note_emails' => 1]);

							} elseif ($permission == 5) {

							$this->db->where('id', $contact_id);

							$this->db->update(db_prefix() . 'contacts', ['ticket_emails' => 1]);

						}

					}

				}

				

				if ($send_welcome_email == true && !empty($data['email'])) {

					send_mail_template(

                    'customer_created_welcome_mail',

                    $data['email'],

                    $data['userid'],

                    $contact_id,

                    $password_before_hash

					);

				}

				

				if ($send_set_password_email) {

					$this->authentication_model->set_password_email($data['email'], 0);

				}

				

				if (defined('CONTACT_REGISTERING')) {

					$this->send_verification_email($contact_id);

					} else {

					// User already verified because is added from admin area, try to transfer any tickets

					$this->load->model('tickets_model');

					$this->tickets_model->transfer_email_tickets_to_contact($data['email'], $contact_id);

				}

				

				log_activity('Contact Created [ID: ' . $contact_id . ']');

				

				hooks()->do_action('contact_created', $contact_id);

				

				return $contact_id;

			}

			

			return false;

		}

		public function add_beat($data)

		{

			$this->db->insert(db_prefix() . 'beat', $data);

			$beat_id = $this->db->insert_id();

			return $beat_id;

		}

		/**

			* Add new contact via customers area

			*

			* @param array  $data

			* @param mixed  $customer_id

		*/

		public function add_contact_via_customers_area($data, $customer_id)

		{

			$send_welcome_email      = isset($data['donotsendwelcomeemail']) && $data['donotsendwelcomeemail'] ? false : true;

			$send_set_password_email = isset($data['send_set_password_email']) && $data['send_set_password_email'] ? true : false;

			$custom_fields           = $data['custom_fields'];

			unset($data['custom_fields']);

			

			if (!is_email_verification_enabled()) {

				$data['email_verified_at'] = date('Y-m-d H:i:s');

			}

			

			$password_before_hash = $data['password'];

			

			$data = array_merge($data, [

            'datecreated' => date('Y-m-d H:i:s'),

            'userid'      => $customer_id,

            'password'    => app_hash_password(isset($data['password']) ? $data['password'] : time()),

			]);

			

			$data = hooks()->apply_filters('before_create_contact', $data);

			$this->db->insert(db_prefix() . 'contacts', $data);

			

			$contact_id = $this->db->insert_id();

			

			if ($contact_id) {

				handle_custom_fields_post($contact_id, $custom_fields);

				

				// Apply default permissions

				$default_permissions = @unserialize(get_option('default_contact_permissions'));

				

				if (is_array($default_permissions)) {

					foreach (get_contact_permissions() as $permission) {

						if (in_array($permission['id'], $default_permissions)) {

							$this->db->insert(db_prefix() . 'contact_permissions', [

                            'userid'        => $contact_id,

                            'permission_id' => $permission['id'],

							]);

						}

					}

				}

				

				if ($send_welcome_email === true) {

					send_mail_template(

                    'customer_created_welcome_mail',

                    $data['email'],

                    $customer_id,

                    $contact_id,

                    $password_before_hash

					);

				}

				

				if ($send_set_password_email === true) {

					$this->authentication_model->set_password_email($data['email'], 0);

				}

				

				log_activity('Contact Created [ID: ' . $contact_id . ']');

				hooks()->do_action('contact_created', $contact_id);

				

				return $contact_id;

			}

			

			return false;

		}

		

		/**

			* Used to update company details from customers area

			* @param  array $data $_POST data

			* @param  mixed $id

			* @return boolean

		*/

		public function update_company_details($data, $id)

		{

			$affectedRows = 0;

			if (isset($data['custom_fields'])) {

				$custom_fields = $data['custom_fields'];

				if (handle_custom_fields_post($id, $custom_fields)) {

					$affectedRows++;

				}

				unset($data['custom_fields']);

			}

			if (isset($data['country']) && $data['country'] == '' || !isset($data['country'])) {

				$data['country'] = 0;

			}

			if (isset($data['billing_country']) && $data['billing_country'] == '') {

				$data['billing_country'] = 0;

			}

			if (isset($data['shipping_country']) && $data['shipping_country'] == '') {

				$data['shipping_country'] = 0;

			}

			

			// From v.1.9.4 these fields are textareas

			$data['address'] = trim($data['address']);

			$data['address'] = nl2br($data['address']);

			if (isset($data['billing_street'])) {

				$data['billing_street'] = trim($data['billing_street']);

				$data['billing_street'] = nl2br($data['billing_street']);

			}

			if (isset($data['shipping_street'])) {

				$data['shipping_street'] = trim($data['shipping_street']);

				$data['shipping_street'] = nl2br($data['shipping_street']);

			}

			

			$data = hooks()->apply_filters('customer_update_company_info', $data, $id);

			

			$this->db->where('userid', $id);

			$this->db->update(db_prefix() . 'clients', $data);

			if ($this->db->affected_rows() > 0) {

				$affectedRows++;

			}

			if ($affectedRows > 0) {

				hooks()->do_action('customer_updated_company_info', $id);

				log_activity('Customer Info Updated From Clients Area [ID: ' . $id . ']');

				

				return true;

			}

			

			return false;

		}

		

		/**

			* Get customer staff members that are added as customer admins

			* @param  mixed $id customer id

			* @return array

		*/

		public function get_admins($id)

		{

			$selected_company = $this->session->userdata('root_company');

			$this->db->where('customer_id', $id);

			//$this->db->where('company_id', $selected_company);

			return $this->db->get(db_prefix() . 'customer_admins')->result_array();

		}

		public function get_acc_bal1($id)

		{

			$selected_company = $this->session->userdata('root_company');

			$fy = $this->session->userdata('finacial_year');

			$this->db->where('AccountID', $id);

			$this->db->where('PlantID', "1");

			$this->db->where('FY', $fy);

			return $this->db->get(db_prefix() . 'accountbalances')->row();

		}

		public function get_acc_bal2($id)

		{

			$selected_company = $this->session->userdata('root_company');

			$fy = $this->session->userdata('finacial_year');

			$this->db->where('AccountID', $id);

			$this->db->where('PlantID', '2');

			$this->db->where('FY', $fy);

			return $this->db->get(db_prefix() . 'accountbalances')->row();

		}

		public function get_acc_bal3($id)

		{

			$selected_company = $this->session->userdata('root_company');

			$fy = $this->session->userdata('finacial_year');

			$this->db->where('AccountID', $id);

			$this->db->where('PlantID', "3");

			$this->db->where('FY', $fy);

			return $this->db->get(db_prefix() . 'accountbalances')->row();

		}

		

		/**

			* Get unique staff id's of customer admins

			* @return array

		*/

		public function get_customers_admin_unique_ids()

		{

			return $this->db->query('SELECT DISTINCT(staff_id) FROM ' . db_prefix() . 'customer_admins')->result_array();

		}

		

		/**

			* Assign staff members as admin to customers

			* @param  array $data $_POST data

			* @param  mixed $id   customer id

			* @return boolean

		*/

		public function assign_admins($data, $id)

		{

			$affectedRows = 0;

			

			if (count($data) == 0) {

				$this->db->where('customer_id', $id);

				$this->db->delete(db_prefix() . 'customer_admins');

				if ($this->db->affected_rows() > 0) {

					$affectedRows++;

				}

				} else {

				$current_admins     = $this->get_admins($id);

				$current_admins_ids = [];

				foreach ($current_admins as $c_admin) {

					array_push($current_admins_ids, $c_admin['staff_id']);

				}

				foreach ($current_admins_ids as $c_admin_id) {

					if (!in_array($c_admin_id, $data['customer_admins'])) {

						$this->db->where('staff_id', $c_admin_id);

						$this->db->where('customer_id', $id);

						$this->db->delete(db_prefix() . 'customer_admins');

						if ($this->db->affected_rows() > 0) {

							$affectedRows++;

						}

					}

				}

				foreach ($data['customer_admins'] as $n_admin_id) {

					if (total_rows(db_prefix() . 'customer_admins', [

                    'customer_id' => $id,

                    'staff_id' => $n_admin_id,

					]) == 0) {

						$this->db->insert(db_prefix() . 'customer_admins', [

                        'customer_id'   => $id,

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

			* @param  integer ID

			* @return boolean

			* Delete client, also deleting rows from, dismissed client announcements, ticket replies, tickets, autologin, user notes

		*/

		public function delete($id)

		{

			$affectedRows = 0;

			

			if (!is_gdpr() && is_reference_in_table('clientid', db_prefix() . 'invoices', $id)) {

				return [

                'referenced' => true,

				];

			}

			

			if (!is_gdpr() && is_reference_in_table('clientid', db_prefix() . 'estimates', $id)) {

				return [

                'referenced' => true,

				];

			}

			

			if (!is_gdpr() && is_reference_in_table('clientid', db_prefix() . 'creditnotes', $id)) {

				return [

                'referenced' => true,

				];

			}

			

			hooks()->do_action('before_client_deleted', $id);

			

			$last_activity = get_last_system_activity_id();

			$company       = get_company_name($id);

			

			$this->db->where('userid', $id);

			$this->db->delete(db_prefix() . 'clients');

			if ($this->db->affected_rows() > 0) {

				$affectedRows++;

				// Delete all user contacts

				$this->db->where('userid', $id);

				$contacts = $this->db->get(db_prefix() . 'contacts')->result_array();

				foreach ($contacts as $contact) {

					$this->delete_contact($contact['id']);

				}

				

				// Delete all tickets start here

				$this->db->where('userid', $id);

				$tickets = $this->db->get(db_prefix() . 'tickets')->result_array();

				$this->load->model('tickets_model');

				foreach ($tickets as $ticket) {

					$this->tickets_model->delete($ticket['ticketid']);

				}

				

				$this->db->where('rel_id', $id);

				$this->db->where('rel_type', 'customer');

				$this->db->delete(db_prefix() . 'notes');

				

				if (is_gdpr() && get_option('gdpr_on_forgotten_remove_invoices_credit_notes') == '1') {

					$this->load->model('invoices_model');

					$this->db->where('clientid', $id);

					$invoices = $this->db->get(db_prefix() . 'invoices')->result_array();

					foreach ($invoices as $invoice) {

						$this->invoices_model->delete($invoice['id'], true);

					}

					

					$this->load->model('credit_notes_model');

					$this->db->where('clientid', $id);

					$credit_notes = $this->db->get(db_prefix() . 'creditnotes')->result_array();

					foreach ($credit_notes as $credit_note) {

						$this->credit_notes_model->delete($credit_note['id'], true);

					}

					} elseif (is_gdpr()) {

					$this->db->where('clientid', $id);

					$this->db->update(db_prefix() . 'invoices', ['deleted_customer_name' => $company]);

					

					$this->db->where('clientid', $id);

					$this->db->update(db_prefix() . 'creditnotes', ['deleted_customer_name' => $company]);

				}

				

				$this->db->where('clientid', $id);

				$this->db->update(db_prefix() . 'creditnotes', [

                'clientid'   => 0,

                'project_id' => 0,

				]);

				

				$this->db->where('clientid', $id);

				$this->db->update(db_prefix() . 'invoices', [

                'clientid'                 => 0,

                'recurring'                => 0,

                'recurring_type'           => null,

                'custom_recurring'         => 0,

                'cycles'                   => 0,

                'last_recurring_date'      => null,

                'project_id'               => 0,

                'subscription_id'          => 0,

                'cancel_overdue_reminders' => 1,

                'last_overdue_reminder'    => null,

				]);

				

				if (is_gdpr() && get_option('gdpr_on_forgotten_remove_estimates') == '1') {

					$this->load->model('estimates_model');

					$this->db->where('clientid', $id);

					$estimates = $this->db->get(db_prefix() . 'estimates')->result_array();

					foreach ($estimates as $estimate) {

						$this->estimates_model->delete($estimate['id'], true);

					}

					} elseif (is_gdpr()) {

					$this->db->where('clientid', $id);

					$this->db->update(db_prefix() . 'estimates', ['deleted_customer_name' => $company]);

				}

				

				$this->db->where('clientid', $id);

				$this->db->update(db_prefix() . 'estimates', [

                'clientid'           => 0,

                'project_id'         => 0,

                'is_expiry_notified' => 1,

				]);

				

				$this->load->model('subscriptions_model');

				$this->db->where('clientid', $id);

				$subscriptions = $this->db->get(db_prefix() . 'subscriptions')->result_array();

				foreach ($subscriptions as $subscription) {

					$this->subscriptions_model->delete($subscription['id'], true);

				}

				// Get all client contracts

				$this->load->model('contracts_model');

				$this->db->where('client', $id);

				$contracts = $this->db->get(db_prefix() . 'contracts')->result_array();

				foreach ($contracts as $contract) {

					$this->contracts_model->delete($contract['id']);

				}

				// Delete the custom field values

				$this->db->where('relid', $id);

				$this->db->where('fieldto', 'customers');

				$this->db->delete(db_prefix() . 'customfieldsvalues');

				

				// Get customer related tasks

				$this->db->where('rel_type', 'customer');

				$this->db->where('rel_id', $id);

				$tasks = $this->db->get(db_prefix() . 'tasks')->result_array();

				

				foreach ($tasks as $task) {

					$this->tasks_model->delete_task($task['id'], false);

				}

				

				$this->db->where('rel_type', 'customer');

				$this->db->where('rel_id', $id);

				$this->db->delete(db_prefix() . 'reminders');

				

				$this->db->where('customer_id', $id);

				$this->db->delete(db_prefix() . 'customer_admins');

				

				$this->db->where('customer_id', $id);

				$this->db->delete(db_prefix() . 'vault');

				

				$this->db->where('customer_id', $id);

				$this->db->delete(db_prefix() . 'customer_groups');

				

				$this->load->model('proposals_model');

				$this->db->where('rel_id', $id);

				$this->db->where('rel_type', 'customer');

				$proposals = $this->db->get(db_prefix() . 'proposals')->result_array();

				foreach ($proposals as $proposal) {

					$this->proposals_model->delete($proposal['id']);

				}

				$this->db->where('rel_id', $id);

				$this->db->where('rel_type', 'customer');

				$attachments = $this->db->get(db_prefix() . 'files')->result_array();

				foreach ($attachments as $attachment) {

					$this->delete_attachment($attachment['id']);

				}

				

				$this->db->where('clientid', $id);

				$expenses = $this->db->get(db_prefix() . 'expenses')->result_array();

				

				$this->load->model('expenses_model');

				foreach ($expenses as $expense) {

					$this->expenses_model->delete($expense['id'], true);

				}

				

				$this->db->where('client_id', $id);

				$this->db->delete(db_prefix() . 'user_meta');

				

				$this->db->where('client_id', $id);

				$this->db->update(db_prefix() . 'leads', ['client_id' => 0]);

				

				// Delete all projects

				$this->load->model('projects_model');

				$this->db->where('clientid', $id);

				$projects = $this->db->get(db_prefix() . 'projects')->result_array();

				foreach ($projects as $project) {

					$this->projects_model->delete($project['id']);

				}

			}

			if ($affectedRows > 0) {

				hooks()->do_action('after_client_deleted', $id);

				

				// Delete activity log caused by delete customer function

				if ($last_activity) {

					$this->db->where('id >', $last_activity->id);

					$this->db->delete(db_prefix() . 'activity_log');

				}

				

				log_activity('Client Deleted [ID: ' . $id . ']');

				

				return true;

			}

			

			return false;

		}

		

		/**

			* Delete customer contact

			* @param  mixed $id contact id

			* @return boolean

		*/

		public function delete_contact($id)

		{

			hooks()->do_action('before_delete_contact', $id);

			

			$this->db->where('id', $id);

			$result      = $this->db->get(db_prefix() . 'contacts')->row();

			$customer_id = $result->userid;

			

			$last_activity = get_last_system_activity_id();

			

			$this->db->where('id', $id);

			$this->db->delete(db_prefix() . 'contacts');

			

			if ($this->db->affected_rows() > 0) {

				if (is_dir(get_upload_path_by_type('contact_profile_images') . $id)) {

					delete_dir(get_upload_path_by_type('contact_profile_images') . $id);

				}

				

				$this->db->where('contact_id', $id);

				$this->db->delete(db_prefix() . 'consents');

				

				$this->db->where('contact_id', $id);

				$this->db->delete(db_prefix() . 'shared_customer_files');

				

				$this->db->where('userid', $id);

				$this->db->where('staff', 0);

				$this->db->delete(db_prefix() . 'dismissed_announcements');

				

				$this->db->where('relid', $id);

				$this->db->where('fieldto', 'contacts');

				$this->db->delete(db_prefix() . 'customfieldsvalues');

				

				$this->db->where('userid', $id);

				$this->db->delete(db_prefix() . 'contact_permissions');

				

				$this->db->where('user_id', $id);

				$this->db->where('staff', 0);

				$this->db->delete(db_prefix() . 'user_auto_login');

				

				$this->db->select('ticketid');

				$this->db->where('contactid', $id);

				$this->db->where('userid', $customer_id);

				$tickets = $this->db->get(db_prefix() . 'tickets')->result_array();

				

				$this->load->model('tickets_model');

				foreach ($tickets as $ticket) {

					$this->tickets_model->delete($ticket['ticketid']);

				}

				

				$this->load->model('tasks_model');

				

				$this->db->where('addedfrom', $id);

				$this->db->where('is_added_from_contact', 1);

				$tasks = $this->db->get(db_prefix() . 'tasks')->result_array();

				

				foreach ($tasks as $task) {

					$this->tasks_model->delete_task($task['id'], false);

				}

				

				// Added from contact in customer profile

				$this->db->where('contact_id', $id);

				$this->db->where('rel_type', 'customer');

				$attachments = $this->db->get(db_prefix() . 'files')->result_array();

				

				foreach ($attachments as $attachment) {

					$this->delete_attachment($attachment['id']);

				}

				

				// Remove contact files uploaded to tasks

				$this->db->where('rel_type', 'task');

				$this->db->where('contact_id', $id);

				$filesUploadedFromContactToTasks = $this->db->get(db_prefix() . 'files')->result_array();

				

				foreach ($filesUploadedFromContactToTasks as $file) {

					$this->tasks_model->remove_task_attachment($file['id']);

				}

				

				$this->db->where('contact_id', $id);

				$tasksComments = $this->db->get(db_prefix() . 'task_comments')->result_array();

				foreach ($tasksComments as $comment) {

					$this->tasks_model->remove_comment($comment['id'], true);

				}

				

				$this->load->model('projects_model');

				

				$this->db->where('contact_id', $id);

				$files = $this->db->get(db_prefix() . 'project_files')->result_array();

				foreach ($files as $file) {

					$this->projects_model->remove_file($file['id'], false);

				}

				

				$this->db->where('contact_id', $id);

				$discussions = $this->db->get(db_prefix() . 'projectdiscussions')->result_array();

				foreach ($discussions as $discussion) {

					$this->projects_model->delete_discussion($discussion['id'], false);

				}

				

				$this->db->where('contact_id', $id);

				$discussionsComments = $this->db->get(db_prefix() . 'projectdiscussioncomments')->result_array();

				foreach ($discussionsComments as $comment) {

					$this->projects_model->delete_discussion_comment($comment['id'], false);

				}

				

				$this->db->where('contact_id', $id);

				$this->db->delete(db_prefix() . 'user_meta');

				

				$this->db->where('(email="' . $result->email . '" OR bcc LIKE "%' . $result->email . '%" OR cc LIKE "%' . $result->email . '%")');

				$this->db->delete(db_prefix() . 'mail_queue');

				

				if (is_gdpr()) {

					$this->db->where('email', $result->email);

					$this->db->delete(db_prefix() . 'listemails');

					

					if (!empty($result->last_ip)) {

						$this->db->where('ip', $result->last_ip);

						$this->db->delete(db_prefix() . 'knowedge_base_article_feedback');

					}

					

					$this->db->where('email', $result->email);

					$this->db->delete(db_prefix() . 'tickets_pipe_log');

					

					$this->db->where('email', $result->email);

					$this->db->delete(db_prefix() . 'tracked_mails');

					

					$this->db->where('contact_id', $id);

					$this->db->delete(db_prefix() . 'project_activity');

					

					$this->db->where('(additional_data LIKE "%' . $result->email . '%" OR full_name LIKE "%' . $result->firstname . ' ' . $result->lastname . '%")');

					$this->db->where('additional_data != "" AND additional_data IS NOT NULL');

					$this->db->delete(db_prefix() . 'sales_activity');

					

					$contactActivityQuery = false;

					if (!empty($result->email)) {

						$this->db->or_like('description', $result->email);

						$contactActivityQuery = true;

					}

					if (!empty($result->firstname)) {

						$this->db->or_like('description', $result->firstname);

						$contactActivityQuery = true;

					}

					if (!empty($result->lastname)) {

						$this->db->or_like('description', $result->lastname);

						$contactActivityQuery = true;

					}

					

					if (!empty($result->phonenumber)) {

						$this->db->or_like('description', $result->phonenumber);

						$contactActivityQuery = true;

					}

					

					if (!empty($result->last_ip)) {

						$this->db->or_like('description', $result->last_ip);

						$contactActivityQuery = true;

					}

					

					if ($contactActivityQuery) {

						$this->db->delete(db_prefix() . 'activity_log');

					}

				}

				

				// Delete activity log caused by delete contact function

				if ($last_activity) {

					$this->db->where('id >', $last_activity->id);

					$this->db->delete(db_prefix() . 'activity_log');

				}

				

				hooks()->do_action('contact_deleted', $id, $result);

				

				return true;

			}

			

			return false;

		}

		

		/**

			* Get customer default currency

			* @param  mixed $id customer id

			* @return mixed

		*/

		public function get_customer_default_currency($id)

		{

			$this->db->select('default_currency');

			$this->db->where('AccountID', $id);

			$result = $this->db->get(db_prefix() . 'clients')->row();

			if ($result) {

				return $result->default_currency;

			}

			

			return false;

		}

		

		/**

			*  Get customer billing details

			* @param   mixed $id   customer id

			* @return  array

		*/

		public function get_customer_billing_and_shipping_details($id)

		{

			$this->db->select('billing_street,billing_city,billing_state,billing_zip,billing_country,shipping_street,shipping_city,shipping_state,shipping_zip,shipping_country');

			$this->db->from(db_prefix() . 'clients');

			$this->db->where('AccountID', $id);

			

			$result = $this->db->get()->result_array();

			if (count($result) > 0) {

				$result[0]['billing_street']  = clear_textarea_breaks($result[0]['billing_street']);

				$result[0]['shipping_street'] = clear_textarea_breaks($result[0]['shipping_street']);

			}

			

			return $result;

		}

		

		/**

			* Get customer files uploaded in the customer profile

			* @param  mixed $id    customer id

			* @param  array  $where perform where

			* @return array

		*/

		public function get_customer_files($id, $where = [])

		{

			$this->db->where($where);

			$this->db->where('rel_id', $id);

			$this->db->where('rel_type', 'customer');

			$this->db->order_by('dateadded', 'desc');

			

			return $this->db->get(db_prefix() . 'files')->result_array();

		}

		

		/**

			* Delete customer attachment uploaded from the customer profile

			* @param  mixed $id attachment id

			* @return boolean

		*/

		public function delete_attachment($id)

		{

			$this->db->where('id', $id);

			$attachment = $this->db->get(db_prefix() . 'files')->row();

			$deleted    = false;

			if ($attachment) {

				if (empty($attachment->external)) {

					$relPath  = get_upload_path_by_type('customer') . $attachment->rel_id . '/';

					$fullPath = $relPath . $attachment->file_name;

					unlink($fullPath);

					$fname     = pathinfo($fullPath, PATHINFO_FILENAME);

					$fext      = pathinfo($fullPath, PATHINFO_EXTENSION);

					$thumbPath = $relPath . $fname . '_thumb.' . $fext;

					if (file_exists($thumbPath)) {

						unlink($thumbPath);

					}

				}

				

				$this->db->where('id', $id);

				$this->db->delete(db_prefix() . 'files');

				if ($this->db->affected_rows() > 0) {

					$deleted = true;

					$this->db->where('file_id', $id);

					$this->db->delete(db_prefix() . 'shared_customer_files');

					log_activity('Customer Attachment Deleted [ID: ' . $attachment->rel_id . ']');

				}

				

				if (is_dir(get_upload_path_by_type('customer') . $attachment->rel_id)) {

					// Check if no attachments left, so we can delete the folder also

					$other_attachments = list_files(get_upload_path_by_type('customer') . $attachment->rel_id);

					if (count($other_attachments) == 0) {

						delete_dir(get_upload_path_by_type('customer') . $attachment->rel_id);

					}

				}

			}

			

			return $deleted;

		}

		

		/**

			* @param  integer ID

			* @param  integer Status ID

			* @return boolean

			* Update contact status Active/Inactive

		*/

		public function change_contact_status($id, $status)

		{

			$status = hooks()->apply_filters('change_contact_status', $status, $id);

			

			$this->db->where('id', $id);

			$this->db->update(db_prefix() . 'contacts', [

            'active' => $status,

			]);

			if ($this->db->affected_rows() > 0) {

				hooks()->do_action('contact_status_changed', [

                'id'     => $id,

                'status' => $status,

				]);

				

				log_activity('Contact Status Changed [ContactID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

				

				return true;

			}

			

			return false;

		}

		

		/**

			* @param  integer ID

			* @param  integer Status ID

			* @return boolean

			* Update client status Active/Inactive

		*/

		public function change_client_status($id, $status)

		{

			$this->db->where('userid', $id);

			$this->db->update(db_prefix() . 'clients', [

            'active' => $status,

			]);

			

			if ($this->db->affected_rows() > 0) {

				hooks()->do_action('client_status_changed', [

                'id'     => $id,

                'status' => $status,

				]);

				

				log_activity('Customer Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

				

				return true;

			}

			

			return false;

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

			$client = $this->db->get(db_prefix() . 'contacts')->row();

			

			if (!app_hasher()->CheckPassword($oldPassword, $client->password)) {

				return [

                'old_password_not_match' => true,

				];

			}

			

			$this->db->where('id', $id);

			$this->db->update(db_prefix() . 'contacts', [

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

			* Get customer groups where customer belongs

			* @param  mixed $id customer id

			* @return array

		*/

		public function get_customer_groups($id)

		{

			return $this->client_groups_model->get_customer_groups($id);

		}

		

		/**

			* Get customer groups where customer belongs

			* @param  mixed $id customer id

			* @return array

		*/

		public function get_customer_groups_name($id)

		{

			return $this->client_groups_model->get_groups_name($id);

		}

		

		/**

			* Get all customer groups

			* @param  string $id

			* @return mixed

		*/

		/*public function get_groups($id = '')

			{

			return $this->client_groups_model->get_groups($id);

		}*/

		

		public function get_groups($id = '')

		{

			return $this->client_groups_model->get_groups($id);

		}

		

		

		

		/**

			* Delete customer groups

			* @param  mixed $id group id

			* @return boolean

		*/

		public function delete_group($id)

		{

			return $this->client_groups_model->delete($id);

		}

		

		/**

			* Add new customer groups

			* @param array $data $_POST data

		*/

		public function add_group($data)

		{

			return $this->client_groups_model->add($data);

		}

		

		/**

			* Edit customer group

			* @param  array $data $_POST data

			* @return boolean

		*/

		public function edit_group($data)

		{

			return $this->client_groups_model->edit($data);

		}

		

		/**

			* Create new vault entry

			* @param  array $data        $_POST data

			* @param  mixed $customer_id customer id

			* @return boolean

		*/

		public function vault_entry_create($data, $customer_id)

		{

			return $this->client_vault_entries_model->create($data, $customer_id);

		}

		

		/**

			* Update vault entry

			* @param  mixed $id   vault entry id

			* @param  array $data $_POST data

			* @return boolean

		*/

		public function vault_entry_update($id, $data)

		{

			return $this->client_vault_entries_model->update($id, $data);

		}

		

		/**

			* Delete vault entry

			* @param  mixed $id entry id

			* @return boolean

		*/

		public function vault_entry_delete($id)

		{

			return $this->client_vault_entries_model->delete($id);

		}

		

		/**

			* Get customer vault entries

			* @param  mixed $customer_id

			* @param  array  $where       additional wher

			* @return array

		*/

		public function get_vault_entries($customer_id, $where = [])

		{

			return $this->client_vault_entries_model->get_by_customer_id($customer_id, $where);

		}

		

		/**

			* Get single vault entry

			* @param  mixed $id vault entry id

			* @return object

		*/

		public function get_vault_entry($id)

		{

			return $this->client_vault_entries_model->get($id);

		}

		

		/**

			* Get customer statement formatted

			* @param  mixed $customer_id customer id

			* @param  string $from        date from

			* @param  string $to          date to

			* @return array

		*/

		public function get_statement($customer_id, $from, $to)

		{

			return $this->statement_model->get_statement($customer_id, $from, $to);

		}

		

		/**

			* Send customer statement to email

			* @param  mixed $customer_id customer id

			* @param  array $send_to     array of contact emails to send

			* @param  string $from        date from

			* @param  string $to          date to

			* @param  string $cc          email CC

			* @return boolean

		*/

		public function send_statement_to_email($customer_id, $send_to, $from, $to, $cc = '')

		{

			return $this->statement_model->send_statement_to_email($customer_id, $send_to, $from, $to, $cc);

		}

		

		/**

			* When customer register, mark the contact and the customer as inactive and set the registration_confirmed field to 0

			* @param  mixed $client_id  the customer id

			* @return boolean

		*/

		public function require_confirmation($client_id)

		{

			$contact_id = get_primary_contact_user_id($client_id);

			$this->db->where('userid', $client_id);

			$this->db->update(db_prefix() . 'clients', ['active' => 0, 'registration_confirmed' => 0]);

			

			$this->db->where('id', $contact_id);

			$this->db->update(db_prefix() . 'contacts', ['active' => 0]);

			

			return true;

		}

		

		public function confirm_registration($client_id)

		{

			$contact_id = get_primary_contact_user_id($client_id);

			$this->db->where('userid', $client_id);

			$this->db->update(db_prefix() . 'clients', ['active' => 1, 'registration_confirmed' => 1]);

			

			$this->db->where('id', $contact_id);

			$this->db->update(db_prefix() . 'contacts', ['active' => 1]);

			

			$contact = $this->get_contact($contact_id);

			

			if ($contact) {

				send_mail_template('customer_registration_confirmed', $contact);

				

				return true;

			}

			

			return false;

		}

		

		public function send_verification_email($id)

		{

			$contact = $this->get_contact($id);

			

			if (empty($contact->email)) {

				return false;

			}

			

			$success = send_mail_template('customer_contact_verification', $contact);

			

			if ($success) {

				$this->db->where('id', $id);

				$this->db->update(db_prefix() . 'contacts', ['email_verification_sent_at' => date('Y-m-d H:i:s')]);

			}

			

			return $success;

		}

		

		public function mark_email_as_verified($id)

		{

			$contact = $this->get_contact($id);

			

			$this->db->where('id', $id);

			$this->db->update(db_prefix() . 'contacts', [

            'email_verified_at'          => date('Y-m-d H:i:s'),

            'email_verification_key'     => null,

            'email_verification_sent_at' => null,

			]);

			

			if ($this->db->affected_rows() > 0) {

				

				// Check for previous tickets opened by this email/contact and link to the contact

				$this->load->model('tickets_model');

				$this->tickets_model->transfer_email_tickets_to_contact($contact->email, $contact->id);

				

				return true;

			}

			

			return false;

		}

		

		public function get_clients_distinct_countries()

		{

			return $this->db->query('SELECT DISTINCT(country_id), short_name FROM ' . db_prefix() . 'clients JOIN ' . db_prefix() . 'countries ON ' . db_prefix() . 'countries.country_id=' . db_prefix() . 'clients.country')->result_array();

		}

		public function get_clients_distinct_state()

		{

			return $this->db->query('SELECT DISTINCT(country_id), short_name FROM ' . db_prefix() . 'clients JOIN ' . db_prefix() . 'countries ON ' . db_prefix() . 'countries.country_id=' . db_prefix() . 'clients.country')->result_array();

		}

		

		public function send_notification_customer_profile_file_uploaded_to_responsible_staff($contact_id, $customer_id)

		{

			$staff         = $this->get_staff_members_that_can_access_customer($customer_id);

			$merge_fields  = $this->app_merge_fields->format_feature('client_merge_fields', $customer_id, $contact_id);

			$notifiedUsers = [];

			

			

			foreach ($staff as $member) {

				mail_template('customer_profile_uploaded_file_to_staff', $member['email'], $member['staffid'])

				->set_merge_fields($merge_fields)

				->send();

				

				if (add_notification([

				'touserid' => $member['staffid'],

				'description' => 'not_customer_uploaded_file',

				'link' => 'clients/client/' . $customer_id . '?group=attachments',

                ])) {

					array_push($notifiedUsers, $member['staffid']);

				}

			}

			pusher_trigger_notification($notifiedUsers);

		}

		

		public function get_staff_members_that_can_access_customer($id)

		{

			$id = $this->db->escape_str($id);

			

			return $this->db->query('SELECT * FROM ' . db_prefix() . 'staff

            WHERE (

			admin=1

			OR staffid IN (SELECT staff_id FROM ' . db_prefix() . "customer_admins WHERE customer_id='.$id.')

			OR staffid IN(SELECT staff_id FROM " . db_prefix() . 'staff_permissions WHERE feature = "customers" AND capability="view")

			)

            AND active=1')->result_array();

		}

		

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

		

		public function delete_contact_profile_image($id)

		{

			hooks()->do_action('before_remove_contact_profile_image');

			if (file_exists(get_upload_path_by_type('contact_profile_images') . $id)) {

				delete_dir(get_upload_path_by_type('contact_profile_images') . $id);

			}

			$this->db->where('id', $id);

			$this->db->update(db_prefix() . 'contacts', [

            'profile_image' => null,

			]);

		}

	}

