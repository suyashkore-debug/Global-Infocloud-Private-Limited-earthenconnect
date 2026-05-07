<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AccountIDMerge_model extends App_Model
{
    public function __construct()
    { 
        parent::__construct();
    }
    
    function AccountlistByAccountID($postData)
    {
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['search']) ){
            
	        $q = $postData['search'];
	        
	        // Client table
            $this->db->select(db_prefix() . 'clients.*');
            $where_items = '(AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR company LIKE "%' . $q. '%" ESCAPE \'!\') ';
	        $this->db->where($where_items);
	        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
            $records = $this->db->get(db_prefix() . 'clients')->result();
            if(empty($records)){
            }else{
                foreach($records as $row ){
                    $response[] = array("value"=>$row->AccountID,"label"=>$row->company);
                }
            }
        // Staff table
            $this->db->select(db_prefix() . 'staff.*');
            $where_items = '(AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR firstname LIKE "%' . $q. '%" ESCAPE \'!\' OR lastname LIKE "%' . $q. '%" ESCAPE \'!\') ';
	        $this->db->where($where_items);
            $records2 = $this->db->get(db_prefix() . 'staff')->result();
            if(empty($records2)){
            }else{
                foreach($records2 as $row2 ){
                    $label = $row2->firstname.' '.$row2->lastname;
                    $response[] = array("value"=>$row2->AccountID,"label"=>$label);
                }
            }
        }
        return $response;
    }
    
    function CheckNewAccountID($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['newAccountID']) ){
            $this->db->select(db_prefix() . 'clients.company AS Name');
	        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
	        $this->db->where(db_prefix() . 'clients.AccountID', $postData['newAccountID']);
            $records = $this->db->get(db_prefix() . 'clients')->row();
            if(empty($records)){
                $this->db->select('CONCAT('.db_prefix() . 'staff.firstname, ,'.db_prefix() . 'staff.lastname) AS Name');
    	        $this->db->where(db_prefix() . 'staff.AccountID', $postData['newAccountID']);
                $records2 = $this->db->get(db_prefix() . 'staff')->row();
                if(empty($records2)){
                    return true;
                }else{
                    return $records2;
                }
            }else{
                return $records;
            }
        }
    }
    
    function exAccountIDDetails($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['exAccountID']) ){
            $this->db->select(db_prefix() . 'clients.AccountID,'.db_prefix() . 'clients.company AS Name');
	        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
	        $this->db->where(db_prefix() . 'clients.AccountID', $postData['exAccountID']);
            $records = $this->db->get(db_prefix() . 'clients')->row();
            if(empty($records)){
                $this->db->select(db_prefix() . 'staff.AccountID,'.db_prefix() . 'staff.firstname AS Name');
    	        $this->db->where(db_prefix() . 'staff.AccountID', $postData['exAccountID']);
                $records2 = $this->db->get(db_prefix() . 'staff')->row();
                if(empty($records2)){
                    return false;
                }else{
                    return $records2;
                }
            }else{
                return $records;
            }
        }
    }
    function modifyItemID2($data)
    {
      $selected_company = $this->session->userdata('root_company');
        $exAccountID = $data["exAccountID"];
        $exAccountName = $data["exAccountName"];
        $newAccountID = $data["newAccountID"];
        $newAccountName = $data["newAccountName"];
        $result = '';
        $result .= '<div class="table-result tableFixHead2">';
         $result .= '<table class="tree table table-striped table-bordered table-result tableFixHead2" id="table-result">';
         $result .= '<thead>';
         $result .= '<tr>';
         $result .= '<th>TableName</th>';
         $result .= '<th>No Of Record Updated</th>';
         $result .= '</tr>';
         $result .= '</thead>';
         $result .= '<tbody>';
        $AccountbalTableAff = 0;
        $AccountCratesTableAff = 0;
        $AccountitemdivisionTableAff = 0;
        $AccountledgerTableAff = 0;
        $AccountledgerAuditTableAff = 0;
        $AccountlocationTypeTableAff = 0;
        $AccountrouteTableAff = 0;
        $AccountsldTableAff = 0;
        $AccountcdnotesTableAff = 0;
        $AccountcdnotesHistoryTableAff = 0;
        $AccountchallanmasterTableAff = 0;
        $AccountchallananathervehicleTableAff = 0;
        $AccountMasterTableAff = 0;
        $AccountContactsTableAff = 0;
        $AccountAdminsTableAff = 0;
        $HistoryTableAff = 0;
        $ItemTableAff = 0;
        
        // Account Balance1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountbalances',['AccountID'=>$newAccountID]);
        $AccountbalTableAff = $this->db->affected_rows();
        
        // Account Balance2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountbalances',['UserID2'=>$newAccountID]);
        $AccountbalTableAff += $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>tblaccountbalances</td>';
        $result .= '<td align="center">'.$AccountbalTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'tblaccountbalances Table '.$AccountbalTableAff.' records Updated <br>';
        
        // Account Crates1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountcrates',['AccountID'=>$newAccountID]);
        $AccountCratesTableAff = $this->db->affected_rows();
        
        // Account Crates2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountcrates',['UserID2'=>$newAccountID]);
        $AccountCratesTableAff += $this->db->affected_rows();
        
        // Account Crates3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'accountcrates',['UserID'=>$newAccountID]);
        $AccountCratesTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountcrates</td>';
        $result .= '<td align="center">'.$AccountCratesTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountcrates Table '.$AccountCratesTableAff.' records Updated <br>';
        
        
        // Account Item Division1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountitemdiv',['AccountID'=>$newAccountID]);
        $AccountitemdivisionTableAff = $this->db->affected_rows();
        
        // Account Item Division2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountitemdiv',['UserID2'=>$newAccountID]);
        $AccountitemdivisionTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountitemdiv</td>';
        $result .= '<td align="center">'.$AccountitemdivisionTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountitemdiv Table '.$AccountitemdivisionTableAff.' records Updated <br>';
        
        
        // Account Ledger1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountledger',['AccountID'=>$newAccountID]);
        $AccountledgerTableAff = $this->db->affected_rows();
        
        // Account Ledger2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'accountledger',['UserID'=>$newAccountID]);
        $AccountledgerTableAff += $this->db->affected_rows();
        
        // Account Ledger3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountledger',['UserID2'=>$newAccountID]);
        $AccountledgerTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountledger</td>';
        $result .= '<td align="center">'.$AccountledgerTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountledger Table '.$AccountledgerTableAff.' records Updated <br>';
        
        
        // Account Ledger Audit1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountledgeraudit',['AccountID'=>$newAccountID]);
        $AccountledgerAuditTableAff = $this->db->affected_rows();
        
        // Account Ledger Audit2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'accountledgeraudit',['UserID'=>$newAccountID]);
        $AccountledgerAuditTableAff += $this->db->affected_rows();
        
        // Account Ledger Audit3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountledgeraudit',['UserID2'=>$newAccountID]);
        $AccountledgerAuditTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountledgeraudit</td>';
        $result .= '<td align="center">'.$AccountledgerAuditTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountledgeraudit Table '.$AccountledgerAuditTableAff.' records Updated <br>';
        
        
        // Account Location Type1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountlocations',['AccountID'=>$newAccountID]);
        $AccountlocationTypeTableAff = $this->db->affected_rows();
        
        // Account Location Type2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountlocations',['UserID2'=>$newAccountID]);
        $AccountlocationTypeTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountlocations</td>';
        $result .= '<td align="center">'.$AccountlocationTypeTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountlocations Table '.$AccountlocationTypeTableAff.' records Updated <br>';
        
        
        // Account Route1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountroutes',['AccountID'=>$newAccountID]);
        $AccountrouteTableAff = $this->db->affected_rows();
        
        // Account Route2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountroutes',['UserID2'=>$newAccountID]);
        $AccountrouteTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountroutes</td>';
        $result .= '<td align="center">'.$AccountrouteTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountroutes Table '.$AccountrouteTableAff.' records Updated <br>';
        
        
        // Account SLD1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'accountsld',['AccountID'=>$newAccountID]);
        $AccountsldTableAff = $this->db->affected_rows();
        
        // Account SLD2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'accountsld',['UserID'=>$newAccountID]);
        $AccountsldTableAff += $this->db->affected_rows();
        
        // Account SLD3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'accountsld',['UserID2'=>$newAccountID]);
        $AccountsldTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblaccountsld</td>';
        $result .= '<td align="center">'.$AccountsldTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblaccountsld Table '.$AccountsldTableAff.' records Updated <br>';
        
        
        // Account CDNotes1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'cdnote',['AccountID'=>$newAccountID]);
        $AccountcdnotesTableAff = $this->db->affected_rows();
        
        // Account CDNotes2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('Userid',$exAccountID);
        $this->db->update(db_prefix() . 'cdnote',['Userid'=>$newAccountID]);
        $AccountcdnotesTableAff += $this->db->affected_rows();
        
        // Account CDNotes3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'cdnote',['UserID2'=>$newAccountID]);
        $AccountcdnotesTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblcdnote</td>';
        $result .= '<td align="center">'.$AccountcdnotesTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblcdnote Table '.$AccountcdnotesTableAff.' records Updated <br>';
        
        
       // Account CDNotes History1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'cdnotehistory',['AccountID'=>$newAccountID]);
        $AccountcdnotesHistoryTableAff = $this->db->affected_rows();
        
        // Account CDNotes History2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'cdnotehistory',['UserID2'=>$newAccountID]);
        $AccountcdnotesHistoryTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblcdnotehistory</td>';
        $result .= '<td align="center">'.$AccountcdnotesHistoryTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblcdnotehistory Table '.$AccountcdnotesHistoryTableAff.' records Updated <br>';
        
        
        // Account Challan Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('DriverID',$exAccountID);
        $this->db->update(db_prefix() . 'challanmaster',['DriverID'=>$newAccountID]);
        $AccountchallanmasterTableAff = $this->db->affected_rows();
        
        // Account Challan Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('LoaderID',$exAccountID);
        $this->db->update(db_prefix() . 'challanmaster',['LoaderID'=>$newAccountID]);
        $AccountchallanmasterTableAff += $this->db->affected_rows();
        
        // Account Challan Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('SalesmanID',$exAccountID);
        $this->db->update(db_prefix() . 'challanmaster',['SalesmanID'=>$newAccountID]);
        $AccountchallanmasterTableAff += $this->db->affected_rows();
        
        // Account Challan Master4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('Gatepassuserid',$exAccountID);
        $this->db->update(db_prefix() . 'challanmaster',['Gatepassuserid'=>$newAccountID]);
        $AccountchallanmasterTableAff += $this->db->affected_rows();
        
        // Account Challan Master5
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'challanmaster',['UserID2'=>$newAccountID]);
        $AccountchallanmasterTableAff += $this->db->affected_rows();
        
        // Account Challan Master6
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'challanmaster',['UserID'=>$newAccountID]);
        $AccountchallanmasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblchallanmaster</td>';
        $result .= '<td align="center">'.$AccountchallanmasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblchallanmaster Table '.$AccountchallanmasterTableAff.' records Updated <br>';
        
        
       // Account ChallananatherVehicle Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'challanothervehicles',['UserID2'=>$newAccountID]);
        $AccountchallananathervehicleTableAff = $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblchallanothervehicles</td>';
        $result .= '<td align="center">'.$AccountchallananathervehicleTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblchallanothervehicles Table '.$AccountchallananathervehicleTableAff.' records Updated <br>';
        
        
        // Account Client Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'clients',['AccountID'=>$newAccountID]);
        $AccountMasterTableAff = $this->db->affected_rows();
        
        // Account Client Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('CtrlAccountID',$exAccountID);
        $this->db->update(db_prefix() . 'clients',['CtrlAccountID'=>$newAccountID]);
        $AccountMasterTableAff += $this->db->affected_rows();
        
        // Account Client Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('addedfrom',$exAccountID);
        $this->db->update(db_prefix() . 'clients',['addedfrom'=>$newAccountID]);
        $AccountMasterTableAff += $this->db->affected_rows();
        
        // Account Client Master4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'clients',['UserID2'=>$newAccountID]);
        $AccountMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblclients</td>';
        $result .= '<td align="center">'.$AccountMasterTableAff.'</td>';
        $result .= '</tr>';
        
       // $result .= 'tblclients Table '.$AccountMasterTableAff.' records Updated <br>';
        
        
       // Account Contacts Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'contacts',['AccountID'=>$newAccountID]);
        $AccountContactsTableAff = $this->db->affected_rows();
        
        // Account Contacts Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'contacts',['UserID2'=>$newAccountID]);
        $AccountContactsTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblcontacts</td>';
        $result .= '<td align="center">'.$AccountContactsTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblcontacts Table '.$AccountContactsTableAff.' records Updated <br>';
        
        
        // Account Admin1
        $this->db->where('customer_id',$exAccountID);
        $this->db->update(db_prefix() . 'customer_admins',['customer_id'=>$newAccountID]);
        $AccountAdminsTableAff = $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblcustomer_admins</td>';
        $result .= '<td align="center">'.$AccountAdminsTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblcustomer_admins Table '.$AccountAdminsTableAff.' records Updated <br>';
        
        
        // Account History Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'history',['AccountID'=>$newAccountID]);
        $HistoryTableAff = $this->db->affected_rows();
        
        // Account History Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'history',['UserID'=>$newAccountID]);
        $HistoryTableAff += $this->db->affected_rows();
        
        // Account History Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'history',['UserID2'=>$newAccountID]);
        $HistoryTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblhistory</td>';
        $result .= '<td align="center">'.$HistoryTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblhistory Table '.$HistoryTableAff.' records Updated <br>';
        
        
        // Account Item Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'items',['UserID'=>$newAccountID]);
        $ItemTableAff = $this->db->affected_rows();
        
        // Account Item Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'items',['UserID2'=>$newAccountID]);
        $ItemTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblitems</td>';
        $result .= '<td align="center">'.$ItemTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblitems Table '.$ItemTableAff.' records Updated <br>';
        
        
        // Account Order Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'ordermaster',['AccountID'=>$newAccountID]);
        $OrderTableAff = $this->db->affected_rows();
        
        // Account Order Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID2',$exAccountID);
        $this->db->update(db_prefix() . 'ordermaster',['AccountID2'=>$newAccountID]);
        $OrderTableAff += $this->db->affected_rows();
        
        // Account Order Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'ordermaster',['UserID'=>$newAccountID]);
        $OrderTableAff += $this->db->affected_rows();
        
        // Account Order Master4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'ordermaster',['UserID2'=>$newAccountID]);
        $OrderTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblordermaster</td>';
        $result .= '<td align="center">'.$OrderTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'tblordermaster Table '.$OrderTableAff.' records Updated <br>';
        
        
        // Account Production Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('manager_name',$exAccountID);
        $this->db->update(db_prefix() . 'production',['manager_name'=>$newAccountID]);
        $ProductionTableAff = $this->db->affected_rows();
        
        // Account Production Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('contractor_name',$exAccountID);
        $this->db->update(db_prefix() . 'production',['contractor_name'=>$newAccountID]);
        $ProductionTableAff += $this->db->affected_rows();
        
        // Account Production Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'production',['UserID'=>$newAccountID]);
        $ProductionTableAff += $this->db->affected_rows();
        
        // Account Production Master4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'production',['UserID2'=>$newAccountID]);
        $ProductionTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblproduction</td>';
        $result .= '<td align="center">'.$ProductionTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblproduction Table '.$ProductionTableAff.' records Updated <br>';
        
        
        // Account Production Details Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'production_details',['UserID'=>$newAccountID]);
        $ProductionDetailsTableAff = $this->db->affected_rows();
        
        // Account Production Details Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'production_details',['UserID2'=>$newAccountID]);
        $ProductionDetailsTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblproduction_details</td>';
        $result .= '<td align="center">'.$ProductionDetailsTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblproduction_details Table '.$ProductionDetailsTableAff.' records Updated <br>';
        
        
        // Account Purchase Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'purchasemaster',['AccountID'=>$newAccountID]);
        $purchaseTableAff = $this->db->affected_rows();
        
        // Account Purchase Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FrtAccountID',$exAccountID);
        $this->db->update(db_prefix() . 'purchasemaster',['FrtAccountID'=>$newAccountID]);
        $purchaseTableAff += $this->db->affected_rows();
        
        // Account Purchase Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('OthAccountID',$exAccountID);
        $this->db->update(db_prefix() . 'purchasemaster',['OthAccountID'=>$newAccountID]);
        $purchaseTableAff += $this->db->affected_rows();
        
        // Account Purchase Master4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('Userid',$exAccountID);
        $this->db->update(db_prefix() . 'purchasemaster',['Userid'=>$newAccountID]);
        $purchaseTableAff += $this->db->affected_rows();
        
        // Account Purchase Master5
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'purchasemaster',['UserID2'=>$newAccountID]);
        $purchaseTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblpurchasemaster</td>';
        $result .= '<td align="center">'.$purchaseTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblpurchasemaster Table '.$purchaseTableAff.' records Updated <br>';
        
        
        // Account Purchase Return  Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'purchasereturn',['AccountID'=>$newAccountID]);
        $purchaseRtnTableAff = $this->db->affected_rows();
        
        // Account Purchase Return  Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FrtAccountID',$exAccountID);
        $this->db->update(db_prefix() . 'purchasereturn',['FrtAccountID'=>$newAccountID]);
        $purchaseRtnTableAff += $this->db->affected_rows();
        
        // Account Purchase Return  Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('OthAccountID',$exAccountID);
        $this->db->update(db_prefix() . 'purchasereturn',['OthAccountID'=>$newAccountID]);
        $purchaseRtnTableAff += $this->db->affected_rows();
        
        // Account Purchase Return  Master4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('Userid',$exAccountID);
        $this->db->update(db_prefix() . 'purchasereturn',['Userid'=>$newAccountID]);
        $purchaseRtnTableAff += $this->db->affected_rows();
        
        // Account Purchase Return  Master5
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'purchasereturn',['UserID2'=>$newAccountID]);
        $purchaseRtnTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblpurchasereturn</td>';
        $result .= '<td align="center">'.$purchaseRtnTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblpurchasereturn Table '.$purchaseRtnTableAff.' records Updated <br>';
        
        
        // Account Rate Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserId',$exAccountID);
        $this->db->update(db_prefix() . 'rate_master',['UserId'=>$newAccountID]);
        $RateMasterTableAff = $this->db->affected_rows();
        
        // Account Rate Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'rate_master',['UserID2'=>$newAccountID]);
        $RateMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblrate_master</td>';
        $result .= '<td align="center">'.$RateMasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblrate_master Table '.$RateMasterTableAff.' records Updated <br>';
        
        
        // Account Receipt Details Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('accountid',$exAccountID);
        $this->db->update(db_prefix() . 'receiptdetails',['accountid'=>$newAccountID]);
        $ReceiptDMasterTableAff = $this->db->affected_rows();
        
        // Account Receipt Details Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'receiptdetails',['UserID2'=>$newAccountID]);
        $ReceiptDMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblreceiptdetails</td>';
        $result .= '<td align="center">'.$ReceiptDMasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblreceiptdetails Table '.$ReceiptDMasterTableAff.' records Updated <br>';
        
        
        // Account Receipt  Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('PymtMode',$exAccountID);
        $this->db->update(db_prefix() . 'receiptmaster',['PymtMode'=>$newAccountID]);
        $ReceiptMasterTableAff = $this->db->affected_rows();
        
        // Account Receipt  Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('Userid',$exAccountID);
        $this->db->update(db_prefix() . 'receiptmaster',['Userid'=>$newAccountID]);
        $ReceiptMasterTableAff += $this->db->affected_rows();
        
        // Account Receipt  Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'receiptmaster',['UserID2'=>$newAccountID]);
        $ReceiptMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblreceiptmaster</td>';
        $result .= '<td align="center">'.$ReceiptMasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblreceiptmaster Table '.$ReceiptMasterTableAff.' records Updated <br>';
        
        
        // Account Recipe  Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('ADUserID',$exAccountID);
        $this->db->update(db_prefix() . 'recipe',['ADUserID'=>$newAccountID]);
        $RecipeMasterTableAff = $this->db->affected_rows();
        
        // Account Recipe  Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'recipe',['UserID'=>$newAccountID]);
        $RecipeMasterTableAff += $this->db->affected_rows();
        
        // Account Recipe  Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'recipe',['UserID2'=>$newAccountID]);
        $RecipeMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblrecipe</td>';
        $result .= '<td align="center">'.$RecipeMasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblrecipe Table '.$RecipeMasterTableAff.' records Updated <br>';
        
        
       // Account Recipe Details Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'recipe_details',['UserID'=>$newAccountID]);
        $RecipeDetailsTableAff = $this->db->affected_rows();
        
        // Account Recipe Details Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'recipe_details',['UserID2'=>$newAccountID]);
        $RecipeDetailsTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblrecipe_details</td>';
        $result .= '<td align="center">'.$RecipeDetailsTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblrecipe_details Table '.$RecipeDetailsTableAff.' records Updated <br>';
        
        
        // Account Sales Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'salesmaster',['AccountID'=>$newAccountID]);
        $SalesMasterTableAff = $this->db->affected_rows();
        
        // Account Sales Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID2',$exAccountID);
        $this->db->update(db_prefix() . 'salesmaster',['AccountID2'=>$newAccountID]);
        $SalesMasterTableAff += $this->db->affected_rows();
        
        // Account Sales Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'salesmaster',['UserID'=>$newAccountID]);
        $SalesMasterTableAff += $this->db->affected_rows();
        
        // Account Sales Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'salesmaster',['UserID2'=>$newAccountID]);
        $SalesMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblsalesmaster</td>';
        $result .= '<td align="center">'.$SalesMasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblsalesmaster Table '.$SalesMasterTableAff.' records Updated <br>';
        
        
        // Account Sales RTN Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'salesreturn',['AccountID'=>$newAccountID]);
        $SalesRtnTableAff = $this->db->affected_rows();
        
        // Account Sales RTN Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'salesreturn',['UserID'=>$newAccountID]);
        $SalesRtnTableAff += $this->db->affected_rows();
        
        // Account Sales RTN Master3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'salesreturn',['UserID2'=>$newAccountID]);
        $SalesRtnTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblsalesreturn</td>';
        $result .= '<td align="center">'.$SalesRtnTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblsalesreturn Table '.$SalesRtnTableAff.' records Updated <br>';
        
        
        // Account Staff Master1
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'staff',['AccountID'=>$newAccountID]);
        $StaffTableAff = $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblstaff</td>';
        $result .= '<td align="center">'.$StaffTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblstaff Table '.$StaffTableAff.' records Updated <br>';
        
        
        // Account Staff Target1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('Staff_AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'staff_target',['Staff_AccountID'=>$newAccountID]);
        $StaffTargetTableAff = $this->db->affected_rows();
        
        // Account Staff Target2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'staff_target',['AccountID'=>$newAccountID]);
        $StaffTargetTableAff += $this->db->affected_rows();
        
        // Account Staff Target3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'staff_target',['UserID'=>$newAccountID]);
        $StaffTargetTableAff += $this->db->affected_rows();
        
        // Account Staff Target4
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'staff_target',['UserID2'=>$newAccountID]);
        $StaffTargetTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblstaff_target</td>';
        $result .= '<td align="center">'.$StaffTargetTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblstaff_target Table '.$StaffTargetTableAff.' records Updated <br>';
        
        
         // Account Stock Adjustment1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('AccountID',$exAccountID);
        $this->db->update(db_prefix() . 'stockadjmaster',['AccountID'=>$newAccountID]);
        $StockAdjTableAff = $this->db->affected_rows();
        
         // Account Stock Adjustment2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'stockadjmaster',['UserID'=>$newAccountID]);
        $StockAdjTableAff += $this->db->affected_rows();
        
         // Account Stock Adjustment3
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'stockadjmaster',['UserID2'=>$newAccountID]);
        $StockAdjTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblstockadjmaster</td>';
        $result .= '<td align="center">'.$StockAdjTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblstockadjmaster Table '.$StockAdjTableAff.' records Updated <br>';
        
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
         // Account Stock Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserId',$exAccountID);
        $this->db->where('GodownID',$GodownID);
        $this->db->update(db_prefix() . 'stockmaster',['UserId'=>$newAccountID]);
        $StockMasterTableAff = $this->db->affected_rows();
        
         // Account Stock Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->where('GodownID',$GodownID);
        $this->db->update(db_prefix() . 'stockmaster',['UserID2'=>$newAccountID]);
        $StockMasterTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblstockmaster</td>';
        $result .= '<td align="center">'.$StockMasterTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblstockmaster Table '.$StockMasterTableAff.' records Updated <br>';
        
        
         // Account VehicleRtn Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'vehiclereturn',['UserID'=>$newAccountID]);
        $VehicleRtnTableAff = $this->db->affected_rows();
        
         // Account VehicleRtn Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'vehiclereturn',['UserID2'=>$newAccountID]);
        $VehicleRtnTableAff += $this->db->affected_rows();
       
        $result .= '<tr>';
        $result .= '<td>tblvehiclereturn</td>';
        $result .= '<td align="center">'.$VehicleRtnTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblvehiclereturn Table '.$VehicleRtnTableAff.' records Updated <br>';
        
        
         // Account Vehicle Master1
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID',$exAccountID);
        $this->db->update(db_prefix() . 'vehicle',['UserID'=>$newAccountID]);
        $VehicleTableAff = $this->db->affected_rows();
        
         // Account Vehicle Master2
        $this->db->where('PlantID', $selected_company);
        $this->db->where('UserID2',$exAccountID);
        $this->db->update(db_prefix() . 'vehicle',['UserID2'=>$newAccountID]);
        $VehicleTableAff += $this->db->affected_rows();
        
        $result .= '<tr>';
        $result .= '<td>tblvehicle</td>';
        $result .= '<td align="center">'.$VehicleTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'tblvehicle Table '.$VehicleTableAff.' records Updated <br>';
        
        $result .= '</tbody>';
        $result .= '</table>';
        $result .= '</div>';
        return $result;
    }
    function modifyItemID($data)
    {
        
        $selected_company = $this->session->userdata('root_company');
        $exAccountID = $data["exAccountID"];
        $exAccountName = $data["exAccountName"];
        $newAccountID = $data["newAccountID"];
        $newAccountName = $data["newAccountName"];
        
        $result = '';
        $itemTableAff = 0;
        $cdnotehistoryTableAff = 0;
        $historyTableAff = 0;
        $PRDTableAff = 0;
        $PRDDetailsTableAff = 0;
        $RateMasterTableAff = 0;
        $RecipeTableAff = 0;
        $RecipeDetailsTableAff = 0;
        $StockMasterTableAff = 0;
        
        // For Item Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_code',$exItemID);
        $this->db->update(db_prefix() . 'items',['item_code'=>$newItemID, 'description'=>$newItemName]);
        
        $itemTableAff = $this->db->affected_rows();
        $result .= 'Item Table '.$itemTableAff.' records Updated <br>';
        
        // For CDNoteHistory Table
        $this->db->where('plantid', $selected_company);
        $this->db->where('itemid',$exItemID);
        $this->db->update(db_prefix() . 'cdnotehistory',['itemid'=>$newItemID]);
        
        $cdnotehistoryTableAff = $this->db->affected_rows();
        $result .= 'CDNoteHistory Table '.$cdnotehistoryTableAff.' records Updated <br>';
        
        // For History Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('ItemID',$exItemID);
        $this->db->update(db_prefix() . 'history',['ItemID'=>$newItemID]);
        
        $historyTableAff = $this->db->affected_rows();
        $result .= 'History Table '.$historyTableAff.' records Updated <br>';
        
        // For Production Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('recipeID',$exItemID);
        $this->db->update(db_prefix() . 'production',['recipeID'=>$newItemID]);
        
        $PRDTableAff = $this->db->affected_rows();
        $result .= 'Production Table '.$PRDTableAff.' records Updated <br>';
        
        // For Production Details Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_id',$exItemID);
        $this->db->update(db_prefix() . 'production_details',['item_id'=>$newItemID, 'item_name'=>$newItemName]);
        
        $PRDDetailsTableAff = $this->db->affected_rows();
        $result .= 'Production Details Table '.$PRDDetailsTableAff.' records Updated <br>';
        
        // For Rate Master Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_id',$exItemID);
        $this->db->update(db_prefix() . 'rate_master',['item_id'=>$newItemID]);
        
        $RateMasterTableAff = $this->db->affected_rows();
        $result .= 'RateMaster Table '.$RateMasterTableAff.' records Updated <br>';
        
        // For Recipe Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_code',$exItemID);
        $this->db->update(db_prefix() . 'recipe',['item_code'=>$newItemID, 'item_description'=>$newItemName]);
        
        $RecipeTableAff = $this->db->affected_rows();
        $result .= 'Recipe Table '.$RecipeTableAff.' records Updated <br>';
        
        // For Recipe Details Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_id',$exItemID);
        $this->db->update(db_prefix() . 'recipe_details',['item_id'=>$newItemID, 'item_name'=>$newItemName]);
        
        $RecipeDetailsTableAff = $this->db->affected_rows();
        $result .= 'Recipe Details Table '.$RecipeDetailsTableAff.' records Updated <br>';
        
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
        // For StockMaster Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('ItemID',$exItemID);
        $this->db->where('GodownID',$GodownID);
        $this->db->update(db_prefix() . 'stockmaster',['ItemID'=>$newItemID]);
        
        $StockMasterTableAff = $this->db->affected_rows();
        $result .= 'StockMaster Table '.$StockMasterTableAff.' records Updated <br>';
        
        return $result;
        //die;
    }
    
    
    public function get_vendor_data($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        
        $this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
       
        $this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['50003002','60001004']);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        $this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }
    public function get_firm_data($id = '', $where = [])
    {
      
        $selected_company = $this->session->userdata('root_company');
        $this->db->select( db_prefix() . 'setup.*');
        $this->db->where(db_prefix() . 'setup.PlantID', $selected_company);
        $this->db->order_by('FY', 'asc');
        return $this->db->get(db_prefix() . 'setup')->result_array();
    }
    
    public function transfer_year($data = '')
    {
        
        // Load the DB utility class
        $this->load->dbutil();
        
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        if($selected_company == 1){
                $com_name = 'CSPL';
            }elseif($selected_company == 2){
                $com_name = 'CFF';
            }elseif($selected_company == 3){
                $com_name = 'CBU';
            }
        $prefs = array(
            'tables'        => array('tblaccountbalances','tblstockmaster','tblaccountcrates','tblaccountledger'),   // Array of tables to backup.
            'ignore'        => array(),                     // List of tables to omit from the backup
            'format'        => 'zip',                       // gzip, zip, txt
            'filename'      => '"'.$com_name.'"_bal_ledger_stock_crates.sql',              // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            'newline'       => "\n"                         // Newline character used in backup file
        );
        // Backup your entire database and assign it to a variable
        $backup = $this->dbutil->backup($prefs);
        
        // Load the file helper and write the file to your server
        $this->load->helper('file');
        $this->load->library('zip');
        $UserID = $this->session->userdata('username');
        $file_name = $com_name.'_bal_ledger_stock_crates_'.$UserID.'_'.date('d-m-Y');
        write_file('uploads/backup/'.$file_name.'.zip', $backup);
        
        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download($file_name.'.zip', $backup);
        //die;
        
        
        $trf_from = $data["trf_from"];
        $trf_to = $data["trf_to"];
        $trf_accounts = $data["trf_accounts"];
        $trf_stock = $data["trf_stock"];
        $trf_crates = $data["trf_crates"];
        
        $this->db->select( db_prefix() . 'accountgroups.*,'.db_prefix() . 'accountgroupssub.*,'.db_prefix() . 'clients.AccountID');
        $this->db->join(db_prefix() . 'accountgroupssub', db_prefix() . 'accountgroups.ActGroupID = '.db_prefix() . 'accountgroupssub.ActGroupID ');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'accountgroupssub.SubActGroupID = '.db_prefix() . 'clients.SubActGroupID AND '.db_prefix() . 'clients.PlantID = '.$selected_company);
        $this->db->where(db_prefix() . 'accountgroups.ActGroupMovementID !=', 'B');
        $this->db->order_by('ActGroupName', 'asc');
        $TransferGroup = $this->db->get(db_prefix() . 'accountgroups')->result_array();
        $NBal_trf_AccountID = array();
        foreach ($TransferGroup as $key11 => $value11) {
            array_push($NBal_trf_AccountID, strtoupper($value11['AccountID']));
        }
        
        // From Account List Balances
        $this->db->select( db_prefix() . 'accountbalances.*');
        $this->db->where(db_prefix() . 'accountbalances.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'accountbalances.FY', $trf_from);
        $this->db->order_by('AccountID', 'asc');
        $AccountList_From = $this->db->get(db_prefix() . 'accountbalances')->result_array();
        
        // To Account List Balances
        $this->db->select( db_prefix() . 'accountbalances.*');
        $this->db->where(db_prefix() . 'accountbalances.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'accountbalances.FY', $trf_to);
        $this->db->order_by('AccountID', 'asc');
        $AccountList_To = $this->db->get(db_prefix() . 'accountbalances')->result_array();
        
        // FROM Accounts Credit Balance 
            $trf_from_new = $trf_from + 1;
            $from_date = '20'.$trf_from.'-04-01';
            $to_date = '20'.$trf_from_new.'-03-31';
            
            // credit balance SUM
                $this->db->select('sum(Amount) as credit_bal,AccountID');
                $this->db->where('tblaccountledger.PlantID', $selected_company);
                $this->db->LIKE('tblaccountledger.TType', 'C');
                $this->db->LIKE('tblaccountledger.FY', $trf_from);
                $this->db->where('tblaccountledger.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
                $this->db->group_by('AccountID');
                $credit_bal = $this->db->get('tblaccountledger')->result_array();
        
        // FROM Accounts Debit Balance 
            
            // Debit balance SUM
                $this->db->select('sum(Amount) as debit_bal,AccountID');
                $this->db->where('tblaccountledger.PlantID', $selected_company);
                $this->db->LIKE('tblaccountledger.TType', 'D');
                $this->db->LIKE('tblaccountledger.FY', $trf_from);
                $this->db->where('tblaccountledger.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
                $this->db->group_by('AccountID');
                $debit_bal = $this->db->get('tblaccountledger')->result_array();
        
            
        foreach ($AccountList_From as $key1 => $value1) {
            $find = 0;
            foreach ($AccountList_To as $key2 => $value2) {
                if(strtoupper($value1['AccountID'])==strtoupper($value2['AccountID']) && $value1['PlantID']==$value2['PlantID']){
                    //echo "update".$value1['AccountID'];
                    $debitAmt = 0;
                    $balance = 0;
                    $creditAmt = 0;
                    if($trf_accounts== "1"){
                        foreach($credit_bal as $value3){
                            if(strtoupper($value1["AccountID"])==strtoupper($value3["AccountID"])){
                                $creditAmt = $value3["credit_bal"];
                            }
                        }
                        foreach($debit_bal as $value4){
                            if(strtoupper($value1["AccountID"])==strtoupper($value4["AccountID"])){
                                $debitAmt = $value4["debit_bal"];
                            }
                        }
                        $balance = $value1['BAL1'] + $debitAmt - $creditAmt;
                        $update_array = array(
                            'BAL1' =>$balance,
                        );
                        if (in_array(strtoupper($value1["AccountID"]), $NBal_trf_AccountID)){
                            
                        }else{
                            $this->db->where('PlantID', $selected_company);
                            $this->db->LIKE('FY', $trf_to);
                            $this->db->where('AccountID',$value1["AccountID"]);
                            $this->db->update(db_prefix() . 'accountbalances',$update_array);
                        }
                        
                    }
                    $find = 1;
                }
            }
            if($find == "0"){
                $debitAmt = 0;
                $balance = 0;
                $creditAmt = 0;
                if($trf_accounts== "1"){
                    foreach($credit_bal as $value3){
                        if(strtoupper($value1["AccountID"])==strtoupper($value3["AccountID"])){
                            $creditAmt = $value3["credit_bal"];
                        }
                    }
                    foreach($debit_bal as $value4){
                        if(strtoupper($value1["AccountID"])==strtoupper($value4["AccountID"])){
                            $debitAmt = $value4["debit_bal"];
                        }
                    }
                    $balance = $value1['BAL1'] + $debitAmt - $creditAmt;
                        if (in_array(strtoupper($value1["AccountID"]), $NBal_trf_AccountID)){
                            $insert_array = array(
                                'PlantID'=>$selected_company,
                                'FY'=>$trf_to,
                                'AccountID' =>$value1['AccountID'],
                            );
                        }else{
                            $insert_array = array(
                                'PlantID'=>$selected_company,
                                'FY'=>$trf_to,
                                'AccountID' =>$value1['AccountID'],
                                'BAL1' =>$balance,
                            );
                        }
                    
                }else{
                    $insert_array = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'AccountID' =>$value1['AccountID'],
                    );
                }
            $this->db->insert(db_prefix() . 'accountbalances',$insert_array);
                //echo $value1['AccountID'] . " Insert Accounts";
            }
        }
        
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
        // From Item Stock 
            $this->db->select( db_prefix() . 'stockmaster.*');
            $this->db->where(db_prefix() . 'stockmaster.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'stockmaster.FY', $trf_from);
            $this->db->where('GodownID',$GodownID);
            $this->db->order_by('ItemID', 'asc');
            $ItemIDList_From = $this->db->get(db_prefix() . 'stockmaster')->result_array();
            
        // To Item Stock 
            $this->db->select( db_prefix() . 'stockmaster.*');
            $this->db->where(db_prefix() . 'stockmaster.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'stockmaster.FY', $trf_to);
            $this->db->where('GodownID',$GodownID);
            $this->db->order_by('ItemID', 'asc');
            $ItemIDList_to = $this->db->get(db_prefix() . 'stockmaster')->result_array();
        
        foreach ($ItemIDList_From as $key5 => $value5) {
            $find1 = 0;
            foreach ($ItemIDList_to as $key6 => $value6) {
                if($value5['ItemID']==$value6['ItemID'] && $value5['PlantID']==$value6['PlantID']){
                    //echo "update".$value1['AccountID'];
                    
                    $balance1 = 0;
                    if($trf_stock== "Y"){
                      
                        $balance1 = $value5['OQty'] + $value5['PQty'] - $value5['PRQty'] - $value5['IQty'] + $value5['PRDQty'] + $value5['gtiqty'] - $value5['gtoqty'] - $value5['SQty'] + $value5['SRQty'] - $value5['DQTY'] - $value5['ADJQTY'];
                        $update_array2 = array(
                            'OQty' =>$balance1,
                        );
                        $this->db->where('PlantID', $selected_company);
                        $this->db->LIKE('FY', $trf_to);
                        $this->db->where('ItemID',$value5['ItemID']);
                        $this->db->where('GodownID',$GodownID);
                        $this->db->update(db_prefix() . 'stockmaster',$update_array2);
                    }
                    $find1 = 1;
                }
            }
            if($find1 == "0"){
               
                $balance = 0;
                if($trf_stock== "Y"){
                    
                    $balance = $value5['OQty'] + $value5['PQty'] - $value5['PRQty'] - $value5['IQty'] + $value5['PRDQty'] + $value5['gtiqty'] - $value5['gtoqty'] - $value5['SQty'] + $value5['SRQty'] - $value5['DQTY'] - $value5['ADJQTY'];
                    $insert_array2 = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'ItemID' =>$value5['ItemID'],
                        'OQty' =>$balance,
                        'GodownID' =>$GodownID,
                    );
                }else{
                    $insert_array2 = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'ItemID' =>$value5['ItemID'],
                        'GodownID' =>$GodownID,
                    );
                }
            $this->db->insert(db_prefix() . 'stockmaster',$insert_array2);
                //echo $value1['AccountID'] . " Insert Accounts";
            }
        }    
        
        // From Account For Crates 
            $this->db->select( db_prefix() . 'accountcrates.*');
            $this->db->where(db_prefix() . 'accountcrates.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'accountcrates.FY', $trf_from);
            $this->db->group_by('AccountID');
            $this->db->order_by('AccountID', 'asc');
            $AccountList_From2 = $this->db->get(db_prefix() . 'accountcrates')->result_array();
            
        // TO Account For Crates 
            $this->db->select( db_prefix() . 'accountcrates.*');
            $this->db->where(db_prefix() . 'accountcrates.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'accountcrates.FY', $trf_to);
            $this->db->where(db_prefix() . 'accountcrates.PassedFrom', 'OPENCRATES');
            $this->db->order_by('AccountID', 'asc');
            $AccountList_To2 = $this->db->get(db_prefix() . 'accountcrates')->result_array();
            
        // FROM Accounts Credit Crates 
            $trf_from_new = $trf_from + 1;
            $from_date = '20'.$trf_from.'-04-01';
            $to_date = '20'.$trf_from_new.'-03-31';
            
            // credit Crates SUM
                $this->db->select('sum(Qty) as credit_crates,AccountID');
                $this->db->where('tblaccountcrates.PlantID', $selected_company);
                $this->db->where('tblaccountcrates.TType LIKE', 'C');
                $this->db->where('tblaccountcrates.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
                $this->db->group_by('AccountID');
                $credit_crates = $this->db->get('tblaccountcrates')->result_array();
        
        // FROM Accounts Debit Crates 
            
            // Debit Crates SUM
                $this->db->select('sum(Qty) as debit_crates,AccountID');
                $this->db->where('tblaccountcrates.PlantID', $selected_company);
                $this->db->where('tblaccountcrates.TType LIKE', 'D');
                $this->db->where('tblaccountcrates.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
                $this->db->group_by('AccountID');
                $debit_crates = $this->db->get('tblaccountcrates')->result_array();
        
        foreach ($AccountList_From2 as $key7 => $value7) {
            $find2 = 0;
            foreach ($AccountList_To2 as $key8 => $value8) {
                if($value7['AccountID']==$value8['AccountID'] && $value7['PlantID']==$value8['PlantID']){
                    //echo "update".$value1['AccountID'];
                    $debitcrates = 0;
                    $balance_crates = 0;
                    $creditcrates = 0;
                    if($trf_crates== "Y"){
                        foreach($credit_crates as $value9){
                            if($value7["AccountID"]==$value9["AccountID"]){
                                $creditcrates = $value9["credit_crates"];
                            }
                        }
                        foreach($debit_crates as $value10){
                            if($value7["AccountID"]==$value10["AccountID"]){
                                $debitcrates = $value10["debit_crates"];
                            }
                        }
                        $balance_crates =  $debitcrates - $creditcrates;
                        $update_array = array(
                            'Qty' =>$balance_crates,
                        );
                        $this->db->where('PlantID', $selected_company);
                        $this->db->LIKE('FY', $trf_to);
                        $this->db->where('AccountID',$value7["AccountID"]);
                        $this->db->where('PassedFrom',"OPENCRATES");
                        $this->db->update(db_prefix() . 'accountcrates',$update_array);
                    }
                    $find2 = 1;
                }
            }
            if($find2 == "0"){
                $debitcrates = 0;
                $balance_crates = 0;
                $creditcrates = 0;
                if($selected_company == 1){
                    $next_opencrates_number = get_option('next_opencrates_number_for_cspl');
                }elseif($selected_company == 2){
                    $next_opencrates_number = get_option('next_opencrates_number_for_cff');
                }elseif($selected_company == 3){
                    $next_opencrates_number = get_option('next_opencrates_number_for_cbu');
                }
                
                $voucherID = "OPCRT".$trf_to.$next_opencrates_number;
                $narration = "OpenCrates 20".$trf_to;
                if($trf_crates== "Y"){
                    foreach($credit_crates as $value11){
                        if($value7["AccountID"]==$value11["AccountID"]){
                            $creditcrates = $value11["credit_crates"];
                        }
                    }
                    foreach($debit_crates as $value12){
                        if($value7["AccountID"]==$value12["AccountID"]){
                            $debitcrates = $value12["debit_crates"];
                        }
                    }
                    $balance_crates = $debitcrates - $creditcrates;
                    if($balance_crates <= 0){
                        $ttype= "C";
                    }else{
                        $ttype= "D";
                    }
                    
                    $insert_array = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'VoucherID'=>$voucherID,
                        'Transdate'=>date('Y-m-d H:i:s'),
                        'TransDate2'=>date('Y-m-d H:i:s'),
                        'TType'=>$ttype,
                        'Narration'=>$narration,
                        'Ordinalno'=>1,
                        'UserID'=>$this->session->userdata('username'),
                        'AccountID' =>$value7['AccountID'],
                        'PassedFrom' =>'OPENCRATES',
                        'Qty' =>$balance_crates,
                    );
                }else{
                    $ttype = "C";
                    $insert_array = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'VoucherID'=>$voucherID,
                        'Transdate'=>date('Y-m-d H:i:s'),
                        'TransDate2'=>date('Y-m-d H:i:s'),
                        'TType'=>$ttype,
                        'Narration'=>$narration,
                        'Ordinalno'=>1,
                        'UserID'=>$this->session->userdata('username'),
                        'AccountID' =>$value7['AccountID'],
                        'PassedFrom' =>'OPENCRATES',
                        'Qty' =>$balance_crates,
                    );
                }
            $this->db->insert(db_prefix() . 'accountcrates',$insert_array);
            $this->increment_next_number();
                //echo $value1['AccountID'] . " Insert Accounts";
            }
        }
        return true;
    }
    
    public function increment_next_number()
    {
        // Update next OpenCrates number in settings
        
       $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_opencrates_number_for_cspl');
                
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_opencrates_number_for_cff');
               
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_opencrates_number_for_cbu');
                
            }
        $this->db->set('value', 'value+1', false);
        $this->db->update(db_prefix() . 'options');
    }
    
    
}
?>