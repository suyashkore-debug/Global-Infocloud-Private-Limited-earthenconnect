<?php

defined('BASEPATH') or exit('No direct script access allowed');

class VehRtn_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    // VehicleRtn List
    public function vehicle_return_table($data){
          $selected_company = $this->session->userdata('root_company');
          $year = $this->session->userdata('finacial_year');
          $from_date = to_sql_date($data["from_date"]);
          $to_date = to_sql_date($data["to_date"]);
          
          $regExp ="'.*;s:[0-9]+:'".$selected_company."'.*'";
        $regExp1 ="'.*;s:[0-9]+:";
        $regExp2 =".*'";

            $this->db->select('tblvehiclereturn.ReturnID,tblvehiclereturn.Crates  as return_crates,tblvehiclereturn.Transdate as returnTransdate,tblchallanmaster.*,tblchallanothervehicles.OtherVehicleDetails,tblroute.name, users_table_a.firstname as driver_fn, users_table_a.lastname AS driver_ln,users_table_b.firstname as loader_fn, users_table_b.lastname AS loader_ln, users_table_c.firstname as Salesman_fn, users_table_c.lastname AS Salesman_ln');
           $this->db->join('tblchallanmaster ', 'tblchallanmaster.ChallanID = tblvehiclereturn.ChallanID AND tblchallanmaster.PlantID = '.$selected_company.' AND tblchallanmaster.FY = '.$year, 'left');
            
            $this->db->join('tblstaff users_table_a', 'tblchallanmaster.DriverID = users_table_a.AccountID AND users_table_a.staff_comp REGEXP '.$regExp1.'"'.$selected_company.'"'.$regExp2.'', 'left');
            $this->db->join('tblstaff users_table_b', 'tblchallanmaster.LoaderID = users_table_b.AccountID AND users_table_b.staff_comp REGEXP '.$regExp1.'"'.$selected_company.'"'.$regExp2.'', 'left');
            $this->db->join('tblstaff users_table_c', 'tblchallanmaster.SalesmanID = users_table_c.AccountID AND users_table_c.staff_comp REGEXP '.$regExp1.'"'.$selected_company.'"'.$regExp2.'', 'left');
            $this->db->join('tblroute ', 'tblchallanmaster.RouteID = tblroute.RouteID AND tblroute.PlantID = '.$selected_company, 'left');
            $this->db->join('tblchallanothervehicles ', 'tblchallanmaster.ChallanID = tblchallanothervehicles.ChallanID AND tblchallanothervehicles.PlantID = '.$selected_company.' AND tblchallanothervehicles.FY = '.$year, 'left');
            $this->db->where('tblvehiclereturn.Transdate  BETWEEN "'. $from_date. ' 00:00:00" and "'. $to_date.' 23:59:59"');
            $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
            $this->db->where('tblvehiclereturn.FY', $year);
            $this->db->order_by('tblvehiclereturn.ReturnID','desc');
            return $this->db->get('tblvehiclereturn')->result_array();
            // echo $this->db->last_query();die; 
    }
    
    // Challan List for Not crate vehicle RTN
    
    public function challan_model_table($data){
          $selected_company = $this->session->userdata('root_company');
          $year = $this->session->userdata('finacial_year');
          $from_date = to_sql_date($data["from_date"]);
          $to_date = to_sql_date($data["to_date"]);
          
          $challanIDS = array();
          $this->db->select('*');
          $this->db->where('tblvehiclereturn.PlantID', $selected_company);
          $this->db->where('tblvehiclereturn.FY', $year);
          $vehRtnChallanID = $this->db->get('tblvehiclereturn')->result_array();
            foreach ($vehRtnChallanID as $key => $value) {
               array_push($challanIDS, $value["ChallanID"]);
            }
        if(empty($challanIDS)){
            //return null;
            $this->db->select('tblchallanmaster.*,tblchallanothervehicles.OtherVehicleDetails,tblroute.name, users_table_a.firstname as driver_fn, users_table_a.lastname AS driver_ln,users_table_b.firstname as loader_fn, users_table_b.lastname AS loader_ln, users_table_c.firstname as Salesman_fn, users_table_c.lastname AS Salesman_ln');
            $this->db->join('tblstaff users_table_a', 'tblchallanmaster.DriverID = users_table_a.AccountID', 'left');
            $this->db->join('tblstaff users_table_b', 'tblchallanmaster.LoaderID = users_table_b.AccountID', 'left');
            $this->db->join('tblstaff users_table_c', 'tblchallanmaster.SalesmanID = users_table_c.AccountID', 'left');
            $this->db->join('tblroute ', 'tblchallanmaster.RouteID = tblroute.RouteID AND tblroute.PlantID = '.$selected_company, 'left');
            $this->db->join('tblchallanothervehicles ', 'tblchallanmaster.ChallanID = tblchallanothervehicles.ChallanID AND tblchallanothervehicles.PlantID = '.$selected_company.' AND tblchallanothervehicles.FY = '.$year, 'left');
            //$this->db->where_not_in('tblchallanmaster.ChallanID', $challanIDS);
            $this->db->where('tblchallanmaster.Transdate  BETWEEN "'. $from_date. ' 00:00:00" and "'. $to_date.' 23:59:59"');
            $this->db->where('tblchallanmaster.PlantID', $selected_company);
            $this->db->where('tblchallanmaster.FY', $year);
            $this->db->group_by('tblchallanmaster.ChallanID');
            $this->db->order_by('tblchallanmaster.ChallanID','desc');
            return $this->db->get('tblchallanmaster')->result_array();
        }else{
            $this->db->select('tblchallanmaster.*,tblchallanothervehicles.OtherVehicleDetails,tblroute.name, users_table_a.firstname as driver_fn, users_table_a.lastname AS driver_ln,users_table_b.firstname as loader_fn, users_table_b.lastname AS loader_ln, users_table_c.firstname as Salesman_fn, users_table_c.lastname AS Salesman_ln');
            $this->db->join('tblstaff users_table_a', 'tblchallanmaster.DriverID = users_table_a.AccountID', 'left');
            $this->db->join('tblstaff users_table_b', 'tblchallanmaster.LoaderID = users_table_b.AccountID', 'left');
            $this->db->join('tblstaff users_table_c', 'tblchallanmaster.SalesmanID = users_table_c.AccountID', 'left');
            $this->db->join('tblroute ', 'tblchallanmaster.RouteID = tblroute.RouteID AND tblroute.PlantID = '.$selected_company, 'left');
            $this->db->join('tblchallanothervehicles ', 'tblchallanmaster.ChallanID = tblchallanothervehicles.ChallanID AND tblchallanothervehicles.PlantID = '.$selected_company.' AND tblchallanothervehicles.FY = '.$year, 'left');
            $this->db->where_not_in('tblchallanmaster.ChallanID', $challanIDS);
            $this->db->where('tblchallanmaster.Transdate  BETWEEN "'. $from_date. ' 00:00:00" and "'. $to_date.' 23:59:59"');
            $this->db->where('tblchallanmaster.PlantID', $selected_company);
            $this->db->where('tblchallanmaster.FY', $year);
            $this->db->group_by('tblchallanmaster.ChallanID');
            $this->db->order_by('tblchallanmaster.ChallanID','desc');
            return $this->db->get('tblchallanmaster')->result_array();
        }
    }
    
    // Get Vehicle Detail;s
    
    public function GetDetails($VRtnID){
        $selected_company = $this->session->userdata('root_company');
          $year = $this->session->userdata('finacial_year');
            $this->db->select('tblvehiclereturn.ReturnID,tblvehiclereturn.Crates as return_crates,tblvehiclereturn.Transdate as returnTransdate,tblchallanmaster.*,tblchallanothervehicles.OtherVehicleDetails,tblroute.name,tblroute.KM,tblvehicle.VehicleCapacity, users_table_a.firstname as driver_fn, users_table_a.lastname AS driver_ln,users_table_b.firstname as loader_fn, users_table_b.lastname AS loader_ln, users_table_c.firstname as Salesman_fn, users_table_c.lastname AS Salesman_ln, users_table_d.firstname as UserID_fn, users_table_d.lastname AS UserID_ln');
            $this->db->join('tblchallanmaster ', 'tblchallanmaster.ChallanID = tblvehiclereturn.ChallanID AND tblchallanmaster.PlantID = '.$selected_company.' AND tblchallanmaster.FY = '.$year, 'left');
            $this->db->join('tblstaff users_table_a', 'tblchallanmaster.DriverID = users_table_a.AccountID', 'left');
            $this->db->join('tblstaff users_table_b', 'tblchallanmaster.LoaderID = users_table_b.AccountID', 'left');
            $this->db->join('tblstaff users_table_c', 'tblchallanmaster.SalesmanID = users_table_c.AccountID', 'left');
            $this->db->join('tblstaff users_table_d', 'tblvehiclereturn.UserID = users_table_d.AccountID', 'left');
            $this->db->join('tblroute ', 'tblchallanmaster.RouteID = tblroute.RouteID AND tblroute.PlantID = '.$selected_company, 'left');
            $this->db->join('tblvehicle ', 'tblchallanmaster.VehicleID = tblvehicle.VehicleID', 'left');
            $this->db->join('tblchallanothervehicles ', 'tblchallanmaster.ChallanID = tblchallanothervehicles.ChallanID AND tblchallanothervehicles.PlantID = '.$selected_company.' AND tblchallanothervehicles.FY = '.$year, 'left');
            $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
            $this->db->where('tblvehiclereturn.ReturnID', $VRtnID);
            $this->db->where('tblvehiclereturn.FY', $year);
            $challanDetails =  $this->db->get('tblvehiclereturn')->row();
            $result = array();
            if($challanDetails){
                $result['ChallanDetails'] = $challanDetails;
                $result['CratesDetails'] = $this->GetCrateDetails($VRtnID);
                $result['SaleRtnDetails'] = $this->GetSaleRtnDetails($challanDetails->ChallanID);
                $result['PaymentsDetails'] = $this->GetPaymentsDetails($VRtnID);
                $result['ExpenseDetails'] = $this->GetExpenseDetails($VRtnID);
            }
            return $result;
    }
    
    public function GetExpenseDetails($VRtnID){
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
          
        $this->db->select('tblvehiclereturn.ReturnID,tblstaff.firstname,tblstaff.lastname,tblstaff.current_address,expense_d.Amount as expense_Amount,expense_d.AccountID as Aid,tblclients.company,tblclients.address');
          
        $this->db->join('tblaccountledger expense_d', 'tblvehiclereturn.ReturnID = expense_d.VoucherID AND expense_d.TType = "D" AND expense_d.PassedFrom = "VEHRTNEXP" AND expense_d.PlantID = '.$selected_company.' AND expense_d.FY = '.$year, 'left');
        $this->db->join('tblstaff ', 'expense_d.AccountID = tblstaff.AccountID ', 'left');
        $this->db->join('tblclients ', 'tblclients.AccountID = expense_d.AccountID AND tblclients.PlantID = '.$selected_company, 'left');   
        $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
        $this->db->where('tblvehiclereturn.ReturnID', $VRtnID);
        $this->db->where('tblvehiclereturn.FY', $year);
        return $this->db->get('tblvehiclereturn')->result_array();
    }
    
    public function GetPaymentsDetails($VRtnID){
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
          
        $this->db->select('tblvehiclereturn.ReturnID,tblvehiclereturn.Crates as return_crates,tblclients.company,tblclients.address,tblvehiclereturn.Transdate as returnTransdate,tblchallanmaster.*,payment_recipt.Amount as payment_recipt_Amount,payment_recipt.AccountID as Aid');
        $this->db->join('tblchallanmaster ', 'tblchallanmaster.ChallanID = tblvehiclereturn.ChallanID AND tblchallanmaster.PlantID = '.$selected_company.' AND tblchallanmaster.FY = '.$year, 'left');
          
        $this->db->join('tblaccountledger payment_recipt', 'tblvehiclereturn.ReturnID = payment_recipt.VoucherID AND payment_recipt.TType = "C"  AND payment_recipt.PassedFrom = "VEHRTNPYMTS" AND payment_recipt.PlantID = '.$selected_company.' AND payment_recipt.FY = '.$year, 'left');
        $this->db->join('tblclients ', 'payment_recipt.AccountID = tblclients.AccountID AND tblclients.PlantID = '.$selected_company, 'left');
        $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
        $this->db->where('tblvehiclereturn.ReturnID', $VRtnID);
        $this->db->where('tblvehiclereturn.FY', $year);
        return $this->db->get('tblvehiclereturn')->result_array();
    }
    public function GetSaleRtnDetails($ChallanID){
        $result = array();
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $item_unq = array();
        $response = array();
        
        $this->db->select('*');
        $this->db->where('tblhistory.PlantID', $selected_company);
        $this->db->where('tblhistory.FY', $year);
        $this->db->where('tblhistory.BillID ', $ChallanID);
        $this->db->where('tblhistory.TType ', "O");
        $this->db->where('tblhistory.TType2 ', "Order");
        $itemlist_data = $this->db->get('tblhistory')->result_array();
        foreach ($itemlist_data as $key => $value) {
            if(!in_array($value["ItemID"], $item_unq)){
                array_push($item_unq, $value["ItemID"]);
            }
        }
        
        $this->db->select('tblordermaster.*,tblclients.company,tblclients.state');
        $this->db->join('tblclients ', 'tblordermaster.AccountID = tblclients.AccountID AND tblordermaster.PlantID = tblclients.PlantID ', 'left');
        $this->db->where('tblordermaster.PlantID LIKE', $selected_company);
        $this->db->where('tblordermaster.ChallanID', $ChallanID);
        $this->db->where('tblordermaster.FY', $year);
        $Orderdata = $this->db->get('tblordermaster')->result_array();
        
        $this->db->select('*');
        $this->db->where('tblhistory.PlantID', $selected_company);
        $this->db->where('tblhistory.FY', $year);
        $this->db->where('tblhistory.BillID ', $ChallanID);
        $this->db->where('tblhistory.TType ', "O");
        $this->db->where('tblhistory.TType2 ', "Order");
        $ItemOrderData = $this->db->get('tblhistory')->result_array();
        
        
        $this->db->select('BilledQty,ChallanAmt,NetChallanAmt,igstamt,cgstamt,sgstamt,TransID,ItemID');
        $this->db->where('tblhistory.PlantID', $selected_company);
        $this->db->where('tblhistory.FY', $year);
        $this->db->where('tblhistory.BillID ', $ChallanID);
        $this->db->where('tblhistory.TType ', "R");
        $this->db->where('tblhistory.TType2 ', "Fresh");
        $ItemRtnData = $this->db->get('tblhistory')->result_array();
        
        $response["itemhead"] = $item_unq;
        $response["Orderdata"] = $Orderdata;
        $response["ItemOrderData"] = $ItemOrderData;
        $response["ItemRtnData"] = $ItemRtnData;
        return $response;
    }
    
    public function GetCrateDetails($id){
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $this->db->select('tblvehiclereturn.ReturnID,tblvehiclereturn.Transdate AS VTransDate,tblvehiclereturn.Crates,tblvehiclereturn.ChallanID');
        $this->db->where('tblvehiclereturn.PlantID ', $selected_company);
        $this->db->where('tblvehiclereturn.ReturnID', $id);
        $this->db->where('tblvehiclereturn.FY', $year);
        $data = $this->db->get('tblvehiclereturn')->row();
        $challanID = $data->ChallanID;
        $firstDate = '20'.$year.'-04-01';
        $TransDate = substr($data->VTransDate,0,10);
        
        $this->db->select('SUM(tblaccountcrates.Qty) AS VRtnCrates,tblaccountcrates.AccountID AS act_id,tblclients.company,tblclients.address');
        
        $this->db->join('tblclients ', 'tblaccountcrates.AccountID = tblclients.AccountID AND tblclients.PlantID = tblaccountcrates.PlantID');
        $this->db->where('tblaccountcrates.PlantID', $selected_company);
        $this->db->where('tblaccountcrates.VoucherID', $id);
        $this->db->where('tblaccountcrates.PassedFrom', 'VEHRTNCRATES');
        $this->db->where('tblaccountcrates.TType', 'C');
        $this->db->where('tblaccountcrates.FY', $year);
        $this->db->group_by('tblaccountcrates.AccountID');
        $VRtnCrates = $this->db->get('tblaccountcrates')->result_array();
        
        $AccountIDs = array();
        foreach($VRtnCrates as $value_data){
            array_push($AccountIDs, $value_data["act_id"]);
        }
        
        // For Challan Crates
        $this->db->select('sum(Crates) as CHLCrates,AccountID');
        $this->db->where('tblordermaster.PlantID', $selected_company);
        $this->db->where('tblordermaster.FY', $year);
        $this->db->where('tblordermaster.ChallanID', $challanID);
        $this->db->group_by('AccountID');
        $ChlCrates = $this->db->get('tblordermaster')->result_array();
        
        if($AccountIDs){
                // For Opening balance
            $this->db->select('sum(Qty) as CROQty,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->where_in('tblaccountcrates.AccountID', $AccountIDs);
            $this->db->where('tblaccountcrates.PassedFrom =', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'C');
            $this->db->where('tblaccountcrates.Transdate  BETWEEN "'. $firstDate. ' 00:00:00" and "'. $TransDate.' 23:59:59"');
            $this->db->group_by('AccountID');
            $CROQty = $this->db->get('tblaccountcrates')->result_array();
            
            $this->db->select('sum(Qty) as DROQty,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->where_in('tblaccountcrates.AccountID', $AccountIDs);
            $this->db->where('tblaccountcrates.PassedFrom =', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'D');
            $this->db->where('tblaccountcrates.Transdate  BETWEEN "'. $firstDate. ' 00:00:00" and "'. $TransDate.' 23:59:59"');
            $this->db->group_by('AccountID');
            $DROQty = $this->db->get('tblaccountcrates')->result_array();
            
            // For Credit / Debit Crates
            $this->db->select('sum(Qty) as CRQty,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->where_in('tblaccountcrates.AccountID', $AccountIDs);
            $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'C');
            $this->db->where('tblaccountcrates.Transdate  BETWEEN "'. $firstDate. ' 00:00:00" and "'. $TransDate.' 23:59:59"');
            $this->db->group_by('AccountID');
            $CRQty = $this->db->get('tblaccountcrates')->result_array();
            
            $this->db->select('sum(Qty) as DRQty,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->where_in('tblaccountcrates.AccountID', $AccountIDs);
            $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'D');
            $this->db->where('tblaccountcrates.Transdate  BETWEEN "'. $firstDate. ' 00:00:00" AND "'. $TransDate.' 23:59:59"');
            $this->db->group_by('AccountID');
            $DRQty = $this->db->get('tblaccountcrates')->result_array();
        }
        
        
        $i = 0;
        foreach($VRtnCrates as $value_data){
            $ChlCR = '';
            $balance = 0;
            $OQTY = 0;
            $CD = 0;
            foreach($ChlCrates as $value5){
                if(strtoupper($value_data["act_id"])==strtoupper($value5["AccountID"])){
                    $ChlCR = $value5["CHLCrates"];
                }
            }
            
            if($AccountIDs){
                foreach($CROQty as $value1){
                    if(strtoupper($value_data["act_id"])==strtoupper($value1["AccountID"])){
                        $CROQ = $value1["CROQty"];
                    }
                }
                foreach($DROQty as $value2){
                    if(strtoupper($value_data["act_id"])==strtoupper($value2["AccountID"])){
                        $DROQ = $value2["DROQty"];
                    }
                }
                foreach($CRQty as $value3){
                    if(strtoupper($value_data["act_id"])==strtoupper($value3["AccountID"])){
                        $CRQ = $value3["CRQty"];
                    }
                }
                foreach($DRQty as $value4){
                    if(strtoupper($value_data["act_id"])==strtoupper($value4["AccountID"])){
                        $DRQ = $value4["DRQty"];
                    }
                }
            }else{
                $CROQ = 0;
                $DROQ = 0;
                $CRQ = 0;
                $DRQ = 0;
            }
            
            
            $OQTY = $DROQ - $CROQ;
            $CD =   $DRQ - $CRQ;
            $balance = $CD + $OQTY;
            $VRtnCrates[$i]['OQty'] = $balance;
            $VRtnCrates[$i]['CHLCrates'] = $ChlCR;
            if($ChlCR == ""){
                $ChlCR = 0;
            }
            $newBal = $balance - $value_data["VRtnCrates"];
            $VRtnCrates[$i]['balance_crates'] = $balance;
            /*$VRtnCrates[$i]['DROQ'] = $DROQ;
            $VRtnCrates[$i]['CROQ'] = $CROQ;
            $VRtnCrates[$i]['DRQ'] = $DRQ;
            $VRtnCrates[$i]['CRQ'] = $CRQ;*/
            $i++;
        }
        
        return $VRtnCrates;
        
                  
        /*
        $this->db->select('tblvehiclereturn.ReturnID,tblordermaster.AccountID as act_id,tblchallanmaster.PlantID,tblchallanmaster.ChallanID,tblchallanmaster.FY,SUM(tblordermaster.Crates) AS CHLCrates,tblordermaster.AccountID,tblordermaster.PlantID,tblordermaster.FY,tblvehiclereturn.Crates as return_crates,tblordermaster.Crates as crates_data,tblclients.company,tblclients.address,opening_crates.Qty,opening_crates.TType');
        $this->db->join('tblchallanmaster ', 'tblchallanmaster.ChallanID = tblvehiclereturn.ChallanID AND tblchallanmaster.PlantID = '.$selected_company.' AND tblchallanmaster.FY = '.$year, 'left');
          
        $this->db->join('tblordermaster ', 'tblchallanmaster.ChallanID = tblordermaster.ChallanID AND tblordermaster.PlantID = '.$selected_company.' AND tblordermaster.FY = '.$year, 'left');
        //  $this->db->join('tblaccountcrates crate_data', 'tblvehiclereturn.ReturnID = crate_data.VoucherID AND crate_data.TType = "C"  AND crate_data.PassedFrom = "VehicleReturn" AND crate_data.PlantID = '.$selected_company.' AND crate_data.FY = '.$year, 'left');
        $this->db->join('tblaccountcrates opening_crates', 'opening_crates.PassedFrom = "OPENCRATES" AND tblordermaster.AccountID = opening_crates.AccountID AND opening_crates.PlantID = '.$selected_company.' AND opening_crates.FY = '.$year, 'left');
           
        $this->db->join('tblclients ', 'tblordermaster.AccountID = tblclients.AccountID AND tblclients.PlantID = '.$selected_company, 'left');
        $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
        $this->db->where('tblvehiclereturn.ReturnID', $id);
        $this->db->where('tblvehiclereturn.FY', $year);
        $this->db->group_by('tblordermaster.AccountID');
        $Accountdata = $this->db->get('tblvehiclereturn')->result_array();
        
        foreach($Accountdata as $key=>$value){
                foreach($VRtnCrates as $value_data){
                    if($value['act_id'] ==$value_data['AccountID']){
                        $Accountdata[$key]['VRtnCrates'] = $value_data['VRtnCrates'];
                    }
                }
            }
            
        $i = 0;
            foreach($Accountdata as $value){
                
                  $this->db->select('sum(Qty) as credit_crate,AccountID');
                  $this->db->where('tblaccountcrates.PlantID', $selected_company);
                  $this->db->where('tblaccountcrates.FY', $year);
                  $this->db->where('tblaccountcrates.AccountID', $value['AccountID']);
                  $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                  $this->db->where('tblaccountcrates.TType LIKE', 'C');
                  $this->db->group_by('AccountID');
                  $credit_crate = $this->db->get('tblaccountcrates')->result_array();
                    
                  $this->db->select('sum(Qty) as debit_crate,AccountID');
                  $this->db->where('tblaccountcrates.PlantID', $selected_company);
                  $this->db->where('tblaccountcrates.FY', $year);
                  $this->db->where('tblaccountcrates.AccountID', $value['AccountID']);
                  $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                  $this->db->where('tblaccountcrates.TType LIKE', 'D');
                  $this->db->group_by('AccountID');
                  $debit_crate = $this->db->get('tblaccountcrates')->result_array();
                    
                  $balance = $debit_crate[0]['debit_crate'] - $credit_crate[0]['credit_crate'];
                    
                    
                   if($value['TType'] == 'D'){
                       $Accountdata[$i]['balance_crates'] = $balance+$value['Qty'];
                   }else{
                       $Accountdata[$i]['balance_crates'] = $balance-$value['Qty'];
                   }
            $i++; }
        return $Accountdata;*/
        
    }
    public function get_all_crate_vehicle_return($id){
        
        $selected_company = $this->session->userdata('root_company');
         $year = $this->session->userdata('finacial_year');
      
            // $this->db->select('tblvehiclereturn.ReturnID,tblordermaster.AccountID as act_id,tblchallanmaster.*,tblordermaster.*,tblvehiclereturn.Crates as return_crates,tblordermaster.Crates as crates_data,tblclients.company,tblclients.address,crate_data.Qty as crate_data_qty,opening_crates.Qty,opening_crates.TType');
            $this->db->select('tblvehiclereturn.ReturnID,tblordermaster.AccountID as act_id,tblchallanmaster.*,tblordermaster.*,tblvehiclereturn.Crates as return_crates,tblordermaster.Crates as crates_data,tblclients.company,tblclients.address,opening_crates.Qty,opening_crates.TType');
            $this->db->join('tblchallanmaster ', 'tblchallanmaster.ChallanID = tblvehiclereturn.ChallanID AND tblchallanmaster.PlantID = '.$selected_company.' AND tblchallanmaster.FY = '.$year, 'left');
          
            $this->db->join('tblordermaster ', 'tblchallanmaster.ChallanID = tblordermaster.ChallanID AND tblordermaster.PlantID = '.$selected_company.' AND tblordermaster.FY = '.$year, 'left');
            //  $this->db->join('tblaccountcrates crate_data', 'tblvehiclereturn.ReturnID = crate_data.VoucherID AND crate_data.TType = "C"  AND crate_data.PassedFrom = "VehicleReturn" AND crate_data.PlantID = '.$selected_company.' AND crate_data.FY = '.$year, 'left');
            $this->db->join('tblaccountcrates opening_crates', 'opening_crates.PassedFrom = "OPENCRATES" AND tblordermaster.AccountID = opening_crates.AccountID AND opening_crates.PlantID = '.$selected_company.' AND opening_crates.FY = '.$year, 'left');
           
            $this->db->join('tblclients ', 'tblordermaster.AccountID = tblclients.AccountID AND tblclients.PlantID = '.$selected_company, 'left');
            $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
            $this->db->where('tblvehiclereturn.ReturnID', $id);
            $this->db->where('tblvehiclereturn.FY', $year);
            $data = $this->db->get('tblvehiclereturn')->result_array();
            
            
            
            $this->db->select('crate_data.AccountID as act_id,crate_data.Qty as crate_data_qty,tblordermaster.OrderID AS ORD');
            $this->db->join('tblaccountcrates crate_data', 'tblvehiclereturn.ReturnID = crate_data.VoucherID AND crate_data.TType = "C"  AND crate_data.PassedFrom = "VEHRTNCRATES" AND crate_data.PlantID = '.$selected_company.' AND crate_data.FY = '.$year, 'left');
            $this->db->join('tblordermaster ', 'tblvehiclereturn.ChallanID = tblordermaster.ChallanID AND tblordermaster.PlantID = '.$selected_company.' AND tblordermaster.FY = '.$year, 'left');
            $this->db->where('tblvehiclereturn.PlantID LIKE', $selected_company);
            $this->db->where('tblvehiclereturn.ReturnID', $id);
            $this->db->where('tblvehiclereturn.FY', $year);
            $data_next = $this->db->get('tblvehiclereturn')->result_array();
       
            foreach($data as $key=>$value){
                foreach($data_next as $value_data){
                    if($value['act_id'] ==$value_data['act_id'] && $value['OrderID'] == $value_data['ORD']){
                       
                        $data[$key]['crate_data_qty'] = $value_data['crate_data_qty'];
                    }
                }
            }
             $i = 0;
             foreach($data as $value){
                
                  $this->db->select('sum(Qty) as credit_crate,AccountID');
                  $this->db->where('tblaccountcrates.PlantID', $selected_company);
                  $this->db->where('tblaccountcrates.FY', $year);
                  $this->db->where('tblaccountcrates.AccountID', $value['AccountID']);
                  $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                  $this->db->where('tblaccountcrates.TType LIKE', 'C');
                  $this->db->group_by('AccountID');
                  $credit_crate = $this->db->get('tblaccountcrates')->result_array();
                    
                  $this->db->select('sum(Qty) as debit_crate,AccountID');
                  $this->db->where('tblaccountcrates.PlantID', $selected_company);
                  $this->db->where('tblaccountcrates.FY', $year);
                  $this->db->where('tblaccountcrates.AccountID', $value['AccountID']);
                  $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                  $this->db->where('tblaccountcrates.TType LIKE', 'D');
                  $this->db->group_by('AccountID');
                  $debit_crate = $this->db->get('tblaccountcrates')->result_array();
                    
                  $balance = $debit_crate[0]['debit_crate'] - $credit_crate[0]['credit_crate'];
                    
                    
                   if($value['TType'] == 'D'){
                       $data[$i]['balance_crates'] = $balance+$value['Qty'];
                   }else{
                       $data[$i]['balance_crates'] = $balance-$value['Qty'];
                   }
            $i++; }
            return $data;
    }
    // Get Account List For Crates
    function GetAccountlistForCrates($postData){
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $where_clients = '';
        
        if(isset($postData['search']) ){
           
           $q = $postData['search'];
           $this->db->select(db_prefix() . 'clients.*');
           $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR tblclients.AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID = 60001004';
           $this->db->where($where_clients);
           $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
           $records = $this->db->get(db_prefix() . 'clients')->result();
    
            foreach($records as $row ){
                $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"address"=>$row->address);
            }
        }
        return $response;
    }
    
    // Get Account List For Crates
    function GetAccountDetailsForCrates($postData){
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $where_clients = '';
        
        if(isset($postData['search']) ){
           
           $q = $postData['search'];
           $this->db->select(db_prefix() . 'clients.*');
           $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR tblclients.AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID = 60001004';
           $this->db->where($where_clients);
           $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
           $records = $this->db->get(db_prefix() . 'clients')->result();
    
            foreach($records as $row ){
                $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"address"=>$row->address);
            }
        }
        return $response;
    }
    
    // Get Account List For Expenses
    function GetAccountlistForExpenses($postData){
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        $year = $this->session->userdata('finacial_year');
        $where_clients = '';
        
         if(isset($postData['search']) ){
           
           $q = $postData['search'];
           $this->db->select(db_prefix() . 'clients.*');
           $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR tblclients.AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q. '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID IN("30000001","30000005")';
           $this->db->where($where_clients);
           $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
           $records = $this->db->get(db_prefix() . 'clients')->result();
    
            foreach($records as $row ){
                $response[] = array("label"=>$row->company,"value"=>$row->AccountID,"address"=>$row->address);
            }
         $where_clients = '';   
            $this->db->select(db_prefix() . 'staff.*');
           $where_clients .= '(AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR firstname LIKE "%' . $q . '%" ESCAPE \'!\' OR lastname LIKE "%' . $q . '%" ESCAPE \'!\' ) ';
           $this->db->where($where_clients);
           $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
           $records1 = $this->db->get(db_prefix() . 'staff')->result();
            foreach($records1 as $row ){
                $fullname = $row->firstname." ".$row->lastname;
                $response[] = array("label"=>$fullname,"value"=>$row->AccountID,"address"=>$row->current_address);
            }
        }
        return $response;
    }
    // Get AccountDetails for Expenses
    public function getAccountDetailsForExpenses($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $AccountID = $postData['AccountID'];
        $SubgroupIDS = array("30000001","30000005");
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        
        $this->db->select(db_prefix() . 'clients.*');
        $this->db->where(db_prefix() . 'clients.AccountID', $AccountID);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        //$this->db->where_in(db_prefix() . 'clients.SubActGroupID', $SubgroupIDS);
        $result =  $this->db->get(db_prefix() . 'clients')->row();
        if($result){
            return $result;
        }else{
            $this->db->select(db_prefix() . 'staff.*');
            $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
            $this->db->where(db_prefix() . 'staff.AccountID', $AccountID);
            $result =  $this->db->get(db_prefix() . 'staff')->row();
            return $result;
        }
        
    }
    
    // Get Account Details For Crates
    public function getAccountDetails($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        $year = $this->session->userdata('finacial_year');
        $AccountID = $postData['AccountID'];
        $ChallanID = $postData['ChallanID'];
        // AccountDetails
            $this->db->select('tblclients.*,SUM(tblordermaster.Crates) AS CHLCrates');
            $this->db->join('tblordermaster ', 'tblordermaster.AccountID = tblclients.AccountID AND tblordermaster.PlantID = tblclients.PlantID AND tblordermaster.ChallanID = "'.$ChallanID.'" AND tblordermaster.FY = '.$year, 'left');
            $this->db->where('tblclients.PlantID', $selected_company);
            $this->db->where('tblclients.AccountID', $AccountID);
            $AccountDetails = $this->db->get('tblclients')->row();
            
            $this->db->select('sum(Qty) as credit_crate,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.AccountID', $AccountID);
            $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType', 'C');
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->group_by('AccountID');
            $credit_crate = $this->db->get('tblaccountcrates')->row();
                    
            $this->db->select('sum(Qty) as debit_crate,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.AccountID', $AccountID);
            $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'D');
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->group_by('AccountID');
            $debit_crate = $this->db->get('tblaccountcrates')->row();
            
            $this->db->select('sum(Qty) as Qty,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.AccountID', $AccountID);
            $this->db->where('tblaccountcrates.PassedFrom =', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'D');
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->group_by('AccountID');
            $debitOQty = $this->db->get('tblaccountcrates')->row();
            
            $this->db->select('sum(Qty) as Qty,AccountID');
            $this->db->where('tblaccountcrates.PlantID', $selected_company);
            $this->db->where('tblaccountcrates.AccountID', $AccountID);
            $this->db->where('tblaccountcrates.PassedFrom =', 'OPENCRATES');
            $this->db->where('tblaccountcrates.TType LIKE', 'C');
            $this->db->where('tblaccountcrates.FY', $year);
            $this->db->group_by('AccountID');
            $CreditOQty = $this->db->get('tblaccountcrates')->row();
            
            $OQty = $debitOQty->Qty - $CreditOQty->Qty;
            $balance = $OQty + ($debit_crate->debit_crate - $credit_crate->credit_crate);
            $result = array();
            
            $result["Address"] = $AccountDetails->address;
            $result["company"] = $AccountDetails->company;
            $result["AccountID"] = $AccountDetails->AccountID;
            $result["OQty"] = $OQty;
            $result["BQty"] = $balance;
            $result["CHLCrates"] = $AccountDetails->CHLCrates;
        return $result;
    }
    
    function staffgetaccounts($postData){

        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        $year = $this->session->userdata('finacial_year');
        $where_clients = '';
        
         if(isset($postData['search']) ){
           
           $q = $postData['search'];
           $this->db->select(db_prefix() . 'staff.*');
           $where_clients .= '(AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR firstname LIKE "%' . $q . '%" ESCAPE \'!\' OR lastname LIKE "%' . $q . '%" ESCAPE \'!\' ) ';
           $this->db->where($where_clients);
           $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
           $records = $this->db->get(db_prefix() . 'staff')->result();
            foreach($records as $row ){
                $fullname = $row->firstname." ".$row->lastname;
                $response[] = array("label"=>$fullname,"value"=>$row->AccountID,"address"=>$row->current_address);
            }
         }
         return $response;
    }
    
    public function get_staffAccount_Details($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        $regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
        $year = $this->session->userdata('finacial_year');
        $AccountID = $postData['AccountID'];
        $this->db->select(db_prefix() . 'staff.*');
        $this->db->where('tblstaff.staff_comp REGEXP',$regExp);
        $this->db->where(db_prefix() . 'staff.AccountID', $AccountID);
        $result =  $this->db->get(db_prefix() . 'staff')->row();
        return $result;
    }
    
    public function challan_unique_data($data){
           $selected_company = $this->session->userdata('root_company');
           $year = $this->session->userdata('finacial_year');
      
            $this->db->select('tblchallanmaster.*,tblchallanothervehicles.OtherVehicleDetails,tblroute.name,tblroute.KM,tblvehicle.VehicleCapacity, users_table_a.firstname as driver_fn, users_table_a.lastname AS driver_ln,users_table_b.firstname as loader_fn, users_table_b.lastname AS loader_ln, users_table_c.firstname as Salesman_fn, users_table_c.lastname AS Salesman_ln');
            $this->db->join('tblstaff users_table_a', 'tblchallanmaster.DriverID = users_table_a.AccountID', 'left');
            $this->db->join('tblstaff users_table_b', 'tblchallanmaster.LoaderID = users_table_b.AccountID', 'left');
            $this->db->join('tblstaff users_table_c', 'tblchallanmaster.SalesmanID = users_table_c.AccountID', 'left');
            $this->db->join('tblroute ', 'tblchallanmaster.RouteID = tblroute.RouteID AND tblroute.PlantID = '.$selected_company, 'left');
            $this->db->join('tblvehicle ', 'tblchallanmaster.VehicleID = tblvehicle.VehicleID', 'left');
            $this->db->join('tblchallanothervehicles ', 'tblchallanmaster.ChallanID = tblchallanothervehicles.ChallanID AND tblchallanothervehicles.PlantID = '.$selected_company.' AND tblchallanothervehicles.FY = '.$year, 'left');
            $this->db->where('tblchallanmaster.PlantID LIKE', $selected_company);
            $this->db->where('tblchallanmaster.ChallanID', $data['challan_id']);
            $this->db->where('tblchallanmaster.FY', $year);
            $this->db->group_by('tblchallanmaster.ChallanID');
            return $this->db->get('tblchallanmaster')->row_array();
    }
    
    
    
    
    public function challan_all_data($data){
          
        $response = array();
         $selected_company = $this->session->userdata('root_company');
         $year = $this->session->userdata('finacial_year');
        $ChallanID = $data['challan_id'];
    // for sale return
        $this->db->select('tblchallanmaster.*,tblordermaster.*,tblordermaster.Crates as crates_data,tblclients.company,tblclients.address,tblclients.state');
        $this->db->join('tblordermaster ', 'tblchallanmaster.ChallanID = tblordermaster.ChallanID AND tblordermaster.PlantID = '.$selected_company.' AND tblordermaster.FY = '.$year, 'left');
        $this->db->join('tblclients ', 'tblordermaster.AccountID = tblclients.AccountID AND tblclients.PlantID = '.$selected_company, 'left');
        $this->db->where('tblchallanmaster.PlantID LIKE', $selected_company);
        $this->db->where('tblchallanmaster.ChallanID', $ChallanID);
        $this->db->where('tblchallanmaster.FY', $year);
        $data = $this->db->get('tblchallanmaster')->result_array();
            
        $i = 0;
        $item_unq = array(); 
        foreach($data as $value){
            // item list using challan id
                   
            $this->db->select('*');
            $this->db->where('tblhistory.PlantID', $selected_company);
            $this->db->where('tblhistory.FY', $year);
            $this->db->where('tblhistory.AccountID', $value['AccountID']);
            $this->db->where('tblhistory.BillID ', $value['ChallanID']);
            $this->db->where('tblhistory.TType ', "O");
            $this->db->where('tblhistory.TType2 ', "Order");
            $itemlist_data = $this->db->get('tblhistory')->result_array();
            $item_list_ary = array();
                    
            foreach ($itemlist_data as $key => $value) {
                # code...
                array_push($item_list_ary, $value["ItemID"]);
                    if(!in_array($value["ItemID"], $item_unq)){
                    array_push($item_unq, $value["ItemID"]);
                }
            }
            $data[$i]['itemdetails'] = $itemlist_data;
            $i++; 
        }
        $response["data"] = $data;
        $response["itemhead"] = $item_unq;
        
    // For Payment and crates
        $this->db->select('tblchallanmaster.*,tblordermaster.*,SUM(tblordermaster.Crates) as crates_data,tblclients.company,tblclients.address,tblclients.state,tblaccountcrates.Qty,tblaccountcrates.TType');
        $this->db->join('tblordermaster ', 'tblchallanmaster.ChallanID = tblordermaster.ChallanID AND tblordermaster.PlantID = '.$selected_company.' AND tblordermaster.FY = '.$year, 'left');
        $this->db->join('tblaccountcrates ', 'tblaccountcrates.PassedFrom = "OPENCRATES" AND tblordermaster.AccountID = tblaccountcrates.AccountID AND tblaccountcrates.PlantID = '.$selected_company.' AND tblaccountcrates.FY = '.$year, 'left');
        $this->db->join('tblclients ', 'tblordermaster.AccountID = tblclients.AccountID AND tblclients.PlantID = '.$selected_company, 'left');
        $this->db->where('tblchallanmaster.PlantID LIKE', $selected_company);
        $this->db->where('tblchallanmaster.ChallanID', $ChallanID);
        $this->db->where('tblchallanmaster.FY', $year);
        $this->db->group_by('tblordermaster.AccountID');
        $cratesandpayments = $this->db->get('tblchallanmaster')->result_array();
        
        $j = 0;
        foreach($cratesandpayments as $value){
            // credit crated
                  $this->db->select('sum(Qty) as credit_crate,AccountID');
                  $this->db->where('tblaccountcrates.PlantID', $selected_company);
                  $this->db->where('tblaccountcrates.AccountID', $value['AccountID']);
                  $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                  $this->db->where('tblaccountcrates.TType LIKE', 'C');
                  $this->db->group_by('AccountID');
                  $credit_crate = $this->db->get('tblaccountcrates')->result_array();
                
                // debit crated
                  $this->db->select('sum(Qty) as debit_crate,AccountID');
                  $this->db->where('tblaccountcrates.PlantID', $selected_company);
                  $this->db->where('tblaccountcrates.AccountID', $value['AccountID']);
                  $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                  $this->db->where('tblaccountcrates.TType LIKE', 'D');
                  $this->db->group_by('AccountID');
                  $debit_crate = $this->db->get('tblaccountcrates')->result_array();
                // balance crates
                  $balance = $debit_crate[0]['debit_crate'] - $credit_crate[0]['credit_crate'];
                    
                    
                   if($value['TType'] == 'D'){
                       $cratesandpayments[$j]['balance_crates'] = $balance+$value['Qty'];
                       $cratesandpayments[$j]['balance_crates_org'] = $balance+$value['Qty'];
                   }else{
                       $cratesandpayments[$j]['balance_crates'] = $balance-$value['Qty'];
                       $cratesandpayments[$j]['balance_crates_org'] = $balance-$value['Qty'];
                   }
                 $j++;
        }
        $response["cratesandpayments"] = $cratesandpayments;
        return $response;
    }
    
    public function increment_next_number()
    {
        // Update next CHALLAN number in settings
       $FY = $this->session->userdata('finacial_year'); 
      $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_vehicle_return_number_for_cspl');
                
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_vehicle_return_number_for_cff');
               
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_vehicle_return_number_for_cbu');
                
            }
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
    public function get_stock_item($id)
    {
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
        $FY = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $FY);
        $this->db->where('ItemID', $id);
        $this->db->where('GodownID',$GodownID);
        return $this->db->get(db_prefix() . 'stockmaster')->row();
    }
    public function get_acc_bal($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('AccountID', $id);
        return $this->db->get(db_prefix() . 'accountbalances')->row();
    }
    
    public function GetPreLedger($VoucheID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('VoucherID', $VoucheID);
        return $this->db->get(db_prefix() . 'accountledger')->result_array();
    }
    
    public function GetVRtnDetails($VRtnID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('ReturnID', $VRtnID);
        return $this->db->get(db_prefix() . 'vehiclereturn')->row();
    }
    
    public function CheckVehRtnForChallan($ChallanID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('ChallanID', $ChallanID);
        return $this->db->get(db_prefix() . 'vehiclereturn')->row();
    }
    
    public function GetSaleRtn($VRtnID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('OrderID', $VRtnID);
        return $this->db->get(db_prefix() . 'history')->result_array();
    }
    
    //Function for fetching previous account crates details
    public function GetPreviousAccountCratesDetails($passedFrom, $VRtnID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('PassedFrom', $passedFrom);
        $this->db->where('VoucherID', $VRtnID);
        return $this->db->get(db_prefix() . 'accountcrates')->result_array();
    }
    
}
?>