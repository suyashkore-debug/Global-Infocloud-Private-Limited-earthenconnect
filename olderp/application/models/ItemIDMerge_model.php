<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ItemIDMerge_model extends App_Model
{
    public function __construct()
    { 
        parent::__construct();
    }
    
    function getitem_using_itemcode($postData)
    {
        $response = array();
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['search']) ){
	        $q = $postData['search'];
            $this->db->select(db_prefix() . 'items.*');
            $where_items = '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q. '%" ESCAPE \'!\') ';
	        $this->db->where($where_items);
	        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
            $records = $this->db->get(db_prefix() . 'items')->result();
            if(empty($records)){
                $response[] = array("value"=>'',"label"=>'No record found...');
            }else{
                foreach($records as $row ){
                    $response[] = array("value"=>$row->item_code,"label"=>$row->description);
                }
            }
        }
        return $response;
    }
    
    function CheckNewItemID($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['newItemID']) ){
            $this->db->select(db_prefix() . 'items.*');
	        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
	        $this->db->where(db_prefix() . 'items.item_code', $postData['newItemID']);
            $records = $this->db->get(db_prefix() . 'items')->result();
            if(empty($records)){
                return true;
            }else{
                return false;
            }
        }
    }
    
    function exItemIDDetails($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        if(isset($postData['exItemID']) ){
            $this->db->select(db_prefix() . 'items.*');
	        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
	        $this->db->where(db_prefix() . 'items.item_code', $postData['exItemID']);
            $records = $this->db->get(db_prefix() . 'items')->row();
            if(empty($records)){
                return false;
            }else{
                return $records;
            }
        }
    }
    
    function modifyItemID($data)
    {
        
        $selected_company = $this->session->userdata('root_company');
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
        $exItemID = $data["exItemID"];
        $exItemName = $data["exItemName"];
        $newItemID = $data["newItemID"];
        $newItemName = $data["newItemName"];
        
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
        $result .= '<tr>';
        $result .= '<td>Item</td>';
        $result .= '<td align="center">'.$itemTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'Item Table '.$itemTableAff.' records Updated <br>';
        
        // For CDNoteHistory Table
        $this->db->where('plantid', $selected_company);
        $this->db->where('itemid',$exItemID);
        $this->db->update(db_prefix() . 'cdnotehistory',['itemid'=>$newItemID]);
        
        $cdnotehistoryTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>CDNoteHistory</td>';
        $result .= '<td align="center">'.$cdnotehistoryTableAff.'</td>';
        $result .= '</tr>';
        
        //$result .= 'CDNoteHistory Table '.$cdnotehistoryTableAff.' records Updated <br>';
        
        // For History Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('ItemID',$exItemID);
        $this->db->update(db_prefix() . 'history',['ItemID'=>$newItemID]);
        
        $historyTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>History</td>';
        $result .= '<td align="center">'.$historyTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'History Table '.$historyTableAff.' records Updated <br>';
        
        // For Production Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('recipeID',$exItemID);
        $this->db->update(db_prefix() . 'production',['recipeID'=>$newItemID]);
        
        $PRDTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>Production</td>';
        $result .= '<td align="center">'.$PRDTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'Production Table '.$PRDTableAff.' records Updated <br>';
        
        // For Production Details Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_id',$exItemID);
        $this->db->update(db_prefix() . 'production_details',['item_id'=>$newItemID, 'item_name'=>$newItemName]);
        
        $PRDDetailsTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>Production Details</td>';
        $result .= '<td align="center">'.$PRDDetailsTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'Production Details Table '.$PRDDetailsTableAff.' records Updated <br>';
        
        // For Rate Master Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_id',$exItemID);
        $this->db->update(db_prefix() . 'rate_master',['item_id'=>$newItemID]);
        
        $RateMasterTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>RateMaster</td>';
        $result .= '<td align="center">'.$RateMasterTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'RateMaster Table '.$RateMasterTableAff.' records Updated <br>';
        
        // For Recipe Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_code',$exItemID);
        $this->db->update(db_prefix() . 'recipe',['item_code'=>$newItemID, 'item_description'=>$newItemName]);
        
        $RecipeTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>Recipe</td>';
        $result .= '<td align="center">'.$RecipeTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'Recipe Table '.$RecipeTableAff.' records Updated <br>';
        
        // For Recipe Details Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('item_id',$exItemID);
        $this->db->update(db_prefix() . 'recipe_details',['item_id'=>$newItemID, 'item_name'=>$newItemName]);
        
        $RecipeDetailsTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>Recipe Details</td>';
        $result .= '<td align="center">'.$RecipeDetailsTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'Recipe Details Table '.$RecipeDetailsTableAff.' records Updated <br>';
        
        // For StockMaster Table
        $this->db->where('PlantID', $selected_company);
        $this->db->where('ItemID',$exItemID);
         $this->db->where('GodownID',$GodownID);
        $this->db->update(db_prefix() . 'stockmaster',['ItemID'=>$newItemID]);
        
        $StockMasterTableAff = $this->db->affected_rows();
        $result .= '<tr>';
        $result .= '<td>StockMaster</td>';
        $result .= '<td align="center">'.$StockMasterTableAff.'</td>';
        $result .= '</tr>';
        //$result .= 'StockMaster Table '.$StockMasterTableAff.' records Updated <br>';
        
        $result .= '</tbody>';
        $result .= '</table>';
        $result .= '</div>';
        
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