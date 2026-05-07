<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Year_transfer_model extends App_Model
{
    public function __construct()
    { 
        parent::__construct();
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
        if($selected_company == "1"){
            $GodownID = 'CSPL';
        }else if($selected_company == "2"){
            $GodownID = 'CFF';
        }else if($selected_company == "3"){
            $GodownID = 'CBUPL';
        }
        
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
        //force_download($file_name.'.zip', $backup);
        //die;
        
        
        $trf_from = $data["trf_from"];
        $trf_to = $data["trf_to"];
        $trf_accounts = $data["trf_accounts"];
        $trf_stock = $data["trf_stock"];
        $trf_crates = $data["trf_crates"];
        
        $this->db->select( db_prefix() . 'accountgroups.*,'.db_prefix() . 'accountgroupssub.*,'.db_prefix() . 'clients.AccountID');
        $this->db->join(db_prefix() . 'accountgroupssub', db_prefix() . 'accountgroups.ActGroupID = '.db_prefix() . 'accountgroupssub.SubActGroupID1');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'accountgroupssub.SubActGroupID = '.db_prefix() . 'clients.SubActGroupID AND '.db_prefix() . 'clients.PlantID = '.$selected_company);
        $this->db->where(db_prefix() . 'accountgroups.ActGroupMovementID !=', 'B');
        $this->db->order_by('ActGroupName', 'asc');
        $TransferGroup = $this->db->get(db_prefix() . 'accountgroups')->result_array();
        $NBal_trf_AccountID = array();
        foreach ($TransferGroup as $key11 => $value11) {
            array_push($NBal_trf_AccountID, trim(strtoupper($value11['AccountID'])));
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
                if(trim(strtoupper($value1['AccountID'])) == trim(strtoupper($value2['AccountID'])) && $value1['PlantID']==$value2['PlantID']){
                    //echo "update".$value1['AccountID'];
                    $debitAmt = 0;
                    $balance = 0;
                    $creditAmt = 0;
                    if($trf_accounts== "1"){
                        foreach($credit_bal as $value3){
                            if(trim(strtoupper($value1["AccountID"])) == trim(strtoupper($value3["AccountID"]))){
                                $creditAmt = $value3["credit_bal"];
                            }
                        }
                        foreach($debit_bal as $value4){
                            if(trim(strtoupper($value1["AccountID"])) == trim(strtoupper($value4["AccountID"]))){
                                $debitAmt = $value4["debit_bal"];
                            }
                        }
                        $balance = $value1['BAL1'] + $debitAmt - $creditAmt;
                        $update_array = array(
                            'BAL1' =>$balance,
                        );
                        if (in_array(trim(strtoupper($value1["AccountID"])), $NBal_trf_AccountID)){
                            
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
                        if(trim(strtoupper($value1["AccountID"])) == trim(strtoupper($value3["AccountID"]))){
                            $creditAmt = $value3["credit_bal"];
                        }
                    }
                    foreach($debit_bal as $value4){
                        if(trim(strtoupper($value1["AccountID"])) == trim(strtoupper($value4["AccountID"]))){
                            $debitAmt = $value4["debit_bal"];
                        }
                    }
                    $balance = $value1['BAL1'] + $debitAmt - $creditAmt;
                        if (in_array(trim(strtoupper($value1["AccountID"])), $NBal_trf_AccountID)){
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
            }
        }
        
        
        
        // From Item Stock from StockMaster
            $this->db->select( db_prefix() . 'stockmaster.*');
            $this->db->where(db_prefix() . 'stockmaster.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'stockmaster.FY', $trf_from);
            //$this->db->where('GodownID',$GodownID);
            $this->db->where('cnfid',1);
            //$this->db->where('ItemID',"MASOOR");
            $this->db->order_by('ItemID', 'asc');
            $ItemIDList_From = $this->db->get(db_prefix() . 'stockmaster')->result_array();
            
        // To Item Stock from StockMaster
            $this->db->select( db_prefix() . 'stockmaster.*');
            $this->db->where(db_prefix() . 'stockmaster.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'stockmaster.FY', $trf_to);
            //$this->db->where('GodownID',$GodownID);
            //$this->db->where('ItemID',"MASOOR");
            $this->db->where('cnfid',1);
            $this->db->order_by('ItemID', 'asc');
            $ItemIDList_to = $this->db->get(db_prefix() . 'stockmaster')->result_array();
            
        // Stock Get From History Master
        if($trf_stock== "Y"){
            $this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty,GodownID');
            $this->db->from(db_prefix() .'history');
            $this->db->where(db_prefix() .'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
            $this->db->where(db_prefix() .'history.FY', $trf_from);
            //$this->db->where('ItemID',"MASOOR");
            $this->db->group_by('ItemID,TType,TType2,GodownID');
            $stockDetails = $this->db->get()->result_array();
        }
       
        //echo "<pre>";
        foreach ($ItemIDList_From as $key5 => $value5) {
            $balance = 0;
            $PQty = 0;
            $PRQty = 0;
            $IQty = 0;
            $PRDQty = 0;
            $SQty = 0;
            $SRQty = 0;
            $ADJQTY = 0;
            $GOQTY = 0;
            $GIQTY = 0;
            if($trf_stock== "Y"){ 
                
                foreach ($stockDetails as $stock) {
                    if($stock['TType'] == 'P' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $PQty = $stock['BilledQty'];
                    }elseif($stock['TType'] == 'N' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $PRQty = $stock['BilledQty'];
                    }elseif($stock['TType'] == 'A' && $stock['TType2'] == 'Issue' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $IQty = $stock['BilledQty'];
                    }elseif($stock['TType'] == 'B' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $PRDQty = $stock['BilledQty'];
                    }elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $SQty = $stock['BilledQty'];
                    }elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $SRQty = $stock['BilledQty'];
                    }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $ADJQTY += $stock['BilledQty'];
                    }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $ADJQTY += $stock['BilledQty'];
                    }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $ADJQTY += $stock['BilledQty'];
                    }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free distribution' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $ADJQTY += $stock['BilledQty'];
                    }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $ADJQTY += $stock['BilledQty'];
                    }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $GIQTY += $stock['BilledQty'];
                    }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out' && trim(strtoupper($stock['ItemID'])) == trim(strtoupper($value5['ItemID'])) && $stock['GodownID'] == $value5['GodownID']){
                        $GOQTY += $stock['BilledQty'];
                    }
                }
                
                $balance =  (float) $PQty - (float) $PRQty - (float) $IQty +  (float) $PRDQty - (float) $SQty + (float) $SRQty - (float) $ADJQTY - (float) $GOQTY + (float) $GIQTY;
            }else{
                $balance = 0;
            }      
            $find1 = 0;
            foreach ($ItemIDList_to as $key6 => $value6) {
                if(trim(strtoupper($value5['ItemID'])) == trim(strtoupper($value6['ItemID'])) && $value5['PlantID']==$value6['PlantID'] && $value5['GodownID']==$value6['GodownID']){
                    //echo "update".$value1['AccountID'];
                    
                    //$balance1 = 0;
                    if($trf_stock== "Y"){
                      
                        $balance1 = $value5['OQty'] + $balance;
                        $update_array2 = array(
                            'OQty' =>$balance1,
                        );
                        $this->db->where('PlantID', $selected_company);
                        $this->db->LIKE('FY', $trf_to);
                        $this->db->where('ItemID',$value5['ItemID']);
                        $this->db->where('GodownID',$value5['GodownID']);
                        $this->db->update(db_prefix() . 'stockmaster',$update_array2);
                        //print_r($update_array2);
                        
                    }
                    $find1 = 1;
                }
            }
            if($find1 == "0"){
               
                //$balance = 0;
                if($trf_stock== "Y"){
                    
                    $balance = $value5['OQty'] + $balance;
                    $insert_array2 = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'ItemID' =>$value5['ItemID'],
                        'OQty' =>$balance,
                        'GodownID' =>$value5['GodownID'],
                        'cnfid' =>1
                    );
                }else{
                    $insert_array2 = array(
                        'PlantID'=>$selected_company,
                        'FY'=>$trf_to,
                        'ItemID' =>$value5['ItemID'],
                        'GodownID' =>$value5['GodownID'],
                        'cnfid' =>1
                    );
                }
                //print_r($insert_array2);
                $this->db->insert(db_prefix() . 'stockmaster',$insert_array2);
                //echo $value1['AccountID'] . " Insert Accounts";
            }
        }   
        //die;
        
        // From Account For Crates 
            $this->db->select( db_prefix() . 'accountcrates.*');
            $this->db->where(db_prefix() . 'accountcrates.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'accountcrates.FY', $trf_from);
            $this->db->group_by('AccountID');
            $this->db->order_by('AccountID', 'asc');
            $AccountList_From2 = $this->db->get(db_prefix() . 'accountcrates')->result_array();
            
        // Opening Crates for From Account
            $this->db->select('sum(Qty) as OQTy_crates,AccountID');
            $this->db->where(db_prefix() . 'accountcrates.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'accountcrates.FY', $trf_from);
            $this->db->where(db_prefix() . 'accountcrates.PassedFrom', 'OPENCRATES');
            $this->db->group_by('AccountID');
            $this->db->order_by('AccountID', 'asc');
            $AccountList_From_OQTY = $this->db->get(db_prefix() . 'accountcrates')->result_array();
            
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
                $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                $this->db->where('tblaccountcrates.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
                $this->db->group_by('AccountID');
                $credit_crates = $this->db->get('tblaccountcrates')->result_array();
        
        // FROM Accounts Debit Crates 
            
            // Debit Crates SUM
                $this->db->select('sum(Qty) as debit_crates,AccountID');
                $this->db->where('tblaccountcrates.PlantID', $selected_company);
                $this->db->where('tblaccountcrates.TType LIKE', 'D');
                $this->db->where('tblaccountcrates.PassedFrom !=', 'OPENCRATES');
                $this->db->where('tblaccountcrates.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
                $this->db->group_by('AccountID');
                $debit_crates = $this->db->get('tblaccountcrates')->result_array();
        
        foreach ($AccountList_From2 as $key7 => $value7) {
            $find2 = 0;
            foreach ($AccountList_To2 as $key8 => $value8) {
                if(trim(strtoupper($value7['AccountID'])) == trim(strtoupper($value8['AccountID'])) && $value7['PlantID']==$value8['PlantID']){
                    //echo "update".$value1['AccountID'];
                    $debitcrates = 0;
                    $balance_crates = 0;
                    $creditcrates = 0;
                    $OQTYcrates = 0;
                    if($trf_crates== "Y"){
                        foreach($credit_crates as $value9){
                            if(trim(strtoupper($value7["AccountID"])) == trim(strtoupper($value9["AccountID"]))){
                                $creditcrates = $value9["credit_crates"];
                            }
                        }
                        foreach($debit_crates as $value10){
                            if(trim(strtoupper($value7["AccountID"])) == trim(strtoupper($value10["AccountID"]))){
                                $debitcrates = $value10["debit_crates"];
                            }
                        }
                        foreach($AccountList_From_OQTY as $valueOQTY){
                            if(trim(strtoupper($value7["AccountID"])) == trim(strtoupper($valueOQTY["AccountID"]))){
                                $OQTYcrates = $valueOQTY["OQTy_crates"];
                            }
                        }
                        $balance_crates =  $OQTYcrates - $creditcrates + $debitcrates;
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
        //force_download($file_name.'.zip', $backup);
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
    
}?>