<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sale_return_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_sale_return_details($id = '')
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');

        $this->db->select('*');
        $this->db->from(db_prefix() . 'salesreturn');

        $this->db->where(db_prefix() . 'salesreturn.SalesRtnID', $id);
        $this->db->where(db_prefix() . 'salesreturn.FY', $fy);
        $this->db->where(db_prefix() . 'salesreturn.PlantID', $selected_company);
        $sale_return = $this->db->get()->row();
        if ($sale_return) {
            $item = $this->get_sale_return_items($sale_return->SalesRtnID, $selected_company, $fy);
            $account = $this->get_sale_return_account_details($sale_return->AccountID, $selected_company);
            $postData = array(
                "account_id" => $sale_return->AccountID,
            );
            $purchased_item = $this->getsale_item_list($postData);
            $sale_return->items = $item;
            $sale_return->accounts = $account;
            $sale_return->purchased_item = $purchased_item;
        }
        return $sale_return;
    }
    public function update_sale_return($data)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');

        if ($data["act_name"] == "" || $data["act_name"] == null || $data["countof_record"] == "1") {

            set_alert('warning', "please add atleast one item..");
            redirect(admin_url('sale_return/edit/' . $data["ex_sale_return_id"]));
        }

        $date = to_sql_date($data['sale_return_date']) . " " . date('H:i:s');
        $month = substr($date, 5, 2);
        $date_old = to_sql_date($data['sale_return_date_old']) . " " . date('H:i:s');
        $month_old = substr($date_old, 5, 2);

        if ($data["type_select"] == "fresh") {
            $salertnType = "Fresh";
        } else {
            $salertnType = "Damage";
        }
        $roundoff = round($data["net_total_val"]);
        $rnd_amt = $roundoff - $data["net_total_val"];
        (int) $count = $data["countof_record"];
        $ItCount = $count - 1;
        $new_record = $data["new_record"];
        $new_record = str_replace(" ,", '', $data["new_record"]);
        $new_record_array = explode(',', $new_record);

        $edit_record = $data["updated_record"];
        $edit_record = str_replace(" ,", '', $data["updated_record"]);
        $edit_record_array = explode(',', $edit_record);

        //Inserting records into salesreturn audit before updating sales return table
        $salesReturnDetails = $this->fetchSalesReturnDetails($data['old_act_name'], $data["ex_sale_return_id"]);

        foreach ($salesReturnDetails as $value) {
            $insertArray = array(
                'PlantID' => $value["PlantID"],
                'FY' => $value["FY"],
                'BT' => $value["BT"],
                'SalesRtnID' => $value["SalesRtnID"],
                'Transdate' => $value["Transdate"],
                'AccountID' => $value["AccountID"],
                'PayType' => $value["PayType"],
                'SaleAmt' => $value["SaleAmt"],
                'DiscAmt' => $value["DiscAmt"],
                'VATAmt' => $value["VATAmt"],
                'SATAmt' => $value["SATAmt"],
                'CSTAmt' => $value["CSTAmt"],
                'BillAmt' => $value["BillAmt"],
                'RndAmt' => $value["RndAmt"],
                'ItCount' => $value["ItCount"],
                'UserID' => $value["UserID"],
                'SalesRtnTypeID' => $value["SalesRtnTypeID"],
                'cgstamt' => $value["cgstamt"],
                'sgstamt' => $value["sgstamt"],
                'igstamt' => $value["igstamt"],
                'passedfrom' => $value["passedfrom"],
                'UserID2' => $value["UserID2"],
                'Lupdate' => $value["Lupdate"],
                'created_by' => $this->session->userdata('username'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->db->insert(db_prefix() . 'salesreturn_audit', $insertArray);
        }


        $update_record = array(
            "AccountID" => $data["act_name"],
            "SaleAmt" => $data["gross_total_val"],
            "cgstamt" => $data["cgst_total_val"],
            "sgstamt" => $data["sgst_total_val"],
            "igstamt" => $data["igst_total_val"],
            "RndAmt" => $roundoff,
            "ItCount" => $ItCount,
            "SalesRtnTypeID" => $salertnType,
            "BillAmt" => $data["net_total_val"],
            "Transdate" => $date,
            "UserID2" => $this->session->userdata('username'),
            "Lupdate" => date('Y-m-d H:i:s'),
        );

        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('AccountID', $data['old_act_name']);
        $this->db->where('SalesRtnID', $data["ex_sale_return_id"]);
        $this->db->update(db_prefix() . 'salesreturn', $update_record);

        // Shift data from account ladger to ladger audit 
        $GetLadgerData = $this->get_ledger_data($data["ex_sale_return_id"]);

        foreach ($GetLadgerData as $key => $value) {
            $ledger_audit = array(
                "PlantID" => $value["PlantID"],
                "FY" => $value["FY"],
                "Transdate" => $value["Transdate"],
                "TransDate2" => $value["TransDate2"],
                "VoucherID" => $value["VoucherID"],
                "AccountID" => $value["AccountID"],
                "TType" => $value["TType"],
                "Amount" => $value["Amount"],
                "Narration" => $value["Narration"],
                "PassedFrom" => $value["PassedFrom"],
                "OrdinalNo" => $value["OrdinalNo"],
                "UserID" => $value["UserID"],
                "Lupdate" => date('Y-m-d H:i:s'),
                "UserID2" => $this->session->userdata('username')
            );
            $this->db->insert(db_prefix() . 'accountledgeraudit', $ledger_audit);
        }

        // Delete previous Old AccoutID ledger
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('PassedFrom', "SALESRTN");
        $this->db->where('VoucherID', $data["ex_sale_return_id"]);
        $this->db->delete(db_prefix() . 'accountledger');

        $ord_no = 1;
        $credit_ledger = array(
            "FY" => $fy,
            "PlantID" => $selected_company,
            "VoucherID" => $data["ex_sale_return_id"],
            "Transdate" => $date,
            "TransDate2" => date('Y-m-d H:i:s'),
            "TType" => "C",
            "AccountID" => $data['act_name'],
            "Amount" => round($data["net_total_val"]),
            "Narration" => "By SalesRtnID " . $data["ex_sale_return_id"],
            "PassedFrom" => "SALESRTN",
            "OrdinalNo" => $ord_no,
            "UserID" => $this->session->userdata('username'),
        );
        $this->db->insert(db_prefix() . 'accountledger', $credit_ledger);
        $ord_no++;

        // ledger and balance update for Sale Account

        $debit_ledger = array(
            "FY" => $fy,
            "PlantID" => $selected_company,
            "VoucherID" => $data["ex_sale_return_id"],
            "Transdate" => $date,
            "TransDate2" => date('Y-m-d H:i:s'),
            "TType" => "D",
            "AccountID" => "SALE",
            "Amount" => $data["gross_total_val"],
            "Narration" => "By SalesRtnID " . $data["ex_sale_return_id"],
            "PassedFrom" => "SALESRTN",
            "OrdinalNo" => $ord_no,
            "UserID" => $this->session->userdata('username'),
        );
        $this->db->insert(db_prefix() . 'accountledger', $debit_ledger);
        $ord_no++;

        // Ledger & balances update for GST
        if ($data['account_state'] == "UP") {

            $debit_ledger_for_sgst = array(
                "FY" => $fy,
                "PlantID" => $selected_company,
                "VoucherID" => $data["ex_sale_return_id"],
                "Transdate" => $date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "TType" => "D",
                "AccountID" => "SGST",
                "Amount" => $data["sgst_total_val"],
                "Narration" => "By SalesRtnID " . $data["ex_sale_return_id"],
                "PassedFrom" => "SALESRTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_for_sgst);
            $ord_no++;


            // ledger & balances for CGST 

            $debit_ledger_for_cgst = array(
                "FY" => $fy,
                "PlantID" => $selected_company,
                "VoucherID" => $data["ex_sale_return_id"],
                "Transdate" => $date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "TType" => "D",
                "AccountID" => "CGST",
                "Amount" => $data["sgst_total_val"],
                "Narration" => "By SalesRtnID " . $data["ex_sale_return_id"],
                "PassedFrom" => "SALESRTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_for_cgst);
            $ord_no++;

        } else {

            $debit_ledger_for_igst = array(
                "FY" => $fy,
                "PlantID" => $selected_company,
                "VoucherID" => $data["ex_sale_return_id"],
                "Transdate" => $date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "TType" => "D",
                "AccountID" => "IGST",
                "Amount" => $data["igst_total_val"],
                "Narration" => "By SalesRtnID " . $data["ex_sale_return_id"],
                "PassedFrom" => "SALESRTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $debit_ledger_for_igst);
            $ord_no++;
        }

        if ($rnd_amt > 0 || $rnd_amt < 0) {
            $credit_ledger_for_rnd = array(
                "FY" => $fy,
                "PlantID" => $selected_company,
                "VoucherID" => $data["ex_sale_return_id"],
                "Transdate" => $date,
                "TransDate2" => date('Y-m-d H:i:s'),
                "TType" => "D",
                "AccountID" => "ROUNDOFF",
                "Amount" => $rnd_amt,
                "Narration" => "By SalesRtnID " . $data["ex_sale_return_id"],
                "PassedFrom" => "SALESRTN",
                "OrdinalNo" => $ord_no,
                "UserID" => $this->session->userdata('username'),
            );
            $this->db->insert(db_prefix() . 'accountledger', $credit_ledger_for_rnd);
            $ord_no++;
        }

        //end balance and ledger entry code
        if ($selected_company == "1") {
            $GodownID = 'CSPL';
        } else if ($selected_company == "2") {
            $GodownID = 'CFF';
        } else if ($selected_company == "3") {
            $GodownID = 'CBUPL';
        }

        //Insert records into history audit table before updating history table
        $historyDetails = $this->fetchPreviousHistoryDetails($data['old_act_name'], $data["ex_sale_return_id"]);
        if (!empty($historyDetails)) {
            foreach ($historyDetails as $value) {
                $insertArray = array(
                    "PlantID" => $value["PlantID"],
                    "FY" => $value["FY"],
                    "OrderID" => $value["OrderID"],
                    "BillID" => $value["BillID"],
                    "TransID" => $value["TransID"],
                    "IsSchemeYN" => $value["IsSchemeYN"],
                    "TransDate" => $value["TransDate"],
                    "TransDate2" => $value["TransDate2"],
                    "TType" => $value["TType"],
                    "TType2" => $value["TType2"],
                    "AccountID" => $value["AccountID"],
                    "ItemID" => $value["ItemID"],
                    "GodownID" => $value["GodownID"],
                    "PurchRate" => $value["PurchRate"],
                    "Mrp" => $value["Mrp"],
                    "SaleRate" => $value["SaleRate"],
                    "BasicRate" => $value["BasicRate"],
                    "SuppliedIn" => $value["SuppliedIn"],
                    "OrderQty" => $value["OrderQty"],
                    "eOrderQty" => $value["eOrderQty"],
                    "ereason" => $value["ereason"],
                    "BilledQty" => $value["BilledQty"],
                    "DiscPerc" => $value["DiscPerc"],
                    "DiscAmt" => $value["DiscAmt"],
                    "gst" => $value["gst"],
                    "gstamt" => $value["gstamt"],
                    "cgst" => $value["cgst"],
                    "cgstamt" => $value["cgstamt"],
                    "sgst" => $value["sgst"],
                    "sgstamt" => $value["sgstamt"],
                    "igst" => $value["igst"],
                    "igstamt" => $value["igstamt"],
                    "CaseQty" => $value["CaseQty"],
                    "Cases" => $value["Cases"],
                    "OrderAmt" => $value["OrderAmt"],
                    "ChallanAmt" => $value["ChallanAmt"],
                    "NetOrderAmt" => $value["NetOrderAmt"],
                    "NetChallanAmt" => $value["NetChallanAmt"],
                    "Ordinalno" => $value["Ordinalno"],
                    "rowid" => $value["rowid"],
                    "UserID" => $value["UserID"],
                    "cnfid" => $value["cnfid"],
                    "UserID2" => $value["UserID2"],
                    "Lupdate" => $value["Lupdate"],
                    "created_by" => $this->session->userdata('username'),
                    "created_at" => date('Y-m-d H:i:s'),
                );
                $this->db->insert(db_prefix() . 'history_Audit', $insertArray);
            }
        }

        // END Stock value revert from old sale return 

        $update_history = array(
            "TType2" => $salertnType,
            "Transdate" => $date,
            "UserID2" => $this->session->userdata('username'),
            "Lupdate" => date('Y-m-d H:i:s'),
        );

        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('AccountID', $data['old_act_name']);
        $this->db->where('OrderID', $data["ex_sale_return_id"]);
        $this->db->update(db_prefix() . 'history', $update_history);

        if ($data["old_act_name"] == $data["act_name"]) {
            for ($i = 1; $i < $count; $i++) {

                $itemid = "item_code" . $i;
                $hsn_val = "hsn_val" . $i;
                $cgst_per_val = "cgst_per_val" . $i;
                $cgst_amt_val = "cgst_amt_val" . $i;
                $sgst_per_val = "sgst_per_val" . $i;
                $sgst_amt_val = "sgst_amt_val" . $i;
                $igst_per_val = "igst_per_val" . $i;
                $igst_amt_val = "igst_amt_val" . $i;
                $total_amt_val = "total_amt_val" . $i;
                $basic_rate_val = "basic_rate_val" . $i;
                $return_qty = "return_qty" . $i;
                $pack_val = "pack_val" . $i;
                $sale_rate_val = "sale_rate_val" . $i;
                if (in_array($data[$itemid], $new_record_array)) {
                    $saleAmtPerItem = $data[$total_amt_val] - $data[$sgst_amt_val] - $data[$sgst_amt_val] - $data[$igst_amt_val];
                    $new_record_details = array(
                        "PlantID" => $selected_company,
                        "FY" => $fy,
                        "cnfid" => "1",
                        "OrderID" => $data["ex_sale_return_id"],
                        "TransDate" => $date,
                        "TransDate2" => $date,
                        "BillID" => $data["tax_id"],
                        "GodownID" => $GodownID,
                        "TransID" => $data["tax_id"],
                        "TType" => "R",
                        "TType2" => $salertnType,
                        "AccountID" => $data["act_name"],
                        "ItemID" => $data[$itemid],
                        "CaseQty" => $data[$pack_val],
                        "SaleRate" => $data[$sale_rate_val],
                        "BasicRate" => $data[$basic_rate_val],
                        "SuppliedIn" => "CS",
                        "BilledQty" => $data[$return_qty],
                        "DiscPerc" => "0.00",
                        "DiscAmt" => "0.00",
                        "cgst" => $data[$cgst_per_val],
                        "cgstamt" => $data[$cgst_amt_val],
                        "sgst" => $data[$sgst_per_val],
                        "sgstamt" => $data[$sgst_amt_val],
                        "igst" => $data[$igst_per_val],
                        "igstamt" => $data[$igst_amt_val],
                        "ChallanAmt" => $saleAmtPerItem,
                        "NetChallanAmt" => $data[$total_amt_val],
                        "Ordinalno" => $i,
                        "UserID" => $this->session->userdata('username'),
                    );
                    $this->db->insert(db_prefix() . 'history', $new_record_details);

                }
                if (in_array($data[$itemid], $edit_record_array)) {
                    $saleAmtPerItem = $data[$total_amt_val] - $data[$sgst_amt_val] - $data[$sgst_amt_val] - $data[$igst_amt_val];

                    $edit_record_details = array(
                        "BilledQty" => $data[$return_qty],
                        "cgst" => $data[$cgst_per_val],
                        "cgstamt" => $data[$cgst_amt_val],
                        "sgst" => $data[$sgst_per_val],
                        "sgstamt" => $data[$sgst_amt_val],
                        "igst" => $data[$igst_per_val],
                        "igstamt" => $data[$igst_amt_val],
                        "ChallanAmt" => $saleAmtPerItem,
                        "NetChallanAmt" => $data[$total_amt_val],
                        "TransDate" => $date,
                        "TransDate2" => $date,
                        "UserID2" => $this->session->userdata('username'),
                        "Lupdate" => date('Y-m-d H:i:s'),
                    );
                    $this->db->where('PlantID', $selected_company);
                    $this->db->where('FY', $fy);
                    $this->db->where('AccountID', $data['act_name']);
                    $this->db->where('TransID', $data['tax_id']);
                    $this->db->where('ItemID', $data[$itemid]);
                    $this->db->where('OrderID', $data["ex_sale_return_id"]);
                    $this->db->update(db_prefix() . 'history', $edit_record_details);
                }
            }

        } else {

            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('TType', "R");
            $this->db->where('AccountID', $data['old_act_name']);
            $this->db->where('OrderID', $data["ex_sale_return_id"]);
            $this->db->delete(db_prefix() . 'history');

            for ($i = 1; $i < $count; $i++) {

                $itemid = "item_code" . $i;
                $hsn_val = "hsn_val" . $i;
                $cgst_per_val = "cgst_per_val" . $i;
                $cgst_amt_val = "cgst_amt_val" . $i;
                $sgst_per_val = "sgst_per_val" . $i;
                $sgst_amt_val = "sgst_amt_val" . $i;
                $igst_per_val = "igst_per_val" . $i;
                $igst_amt_val = "igst_amt_val" . $i;
                $total_amt_val = "total_amt_val" . $i;
                $basic_rate_val = "basic_rate_val" . $i;
                $return_qty = "return_qty" . $i;
                $pack_val = "pack_val" . $i;
                $sale_rate_val = "sale_rate_val" . $i;
                $TaxableAmt = $data[$total_amt_val] - $data[$cgst_amt_val] - $data[$sgst_amt_val] - $data[$igst_amt_val];
                $new_record_details = array(
                    "PlantID" => $selected_company,
                    "FY" => $fy,
                    "cnfid" => "1",
                    "OrderID" => $data["ex_sale_return_id"],
                    "TransDate" => $date,
                    "TransDate2" => $date,
                    "BillID" => $data["tax_id"],
                    "TransID" => $data["tax_id"],
                    "GodownID" => $GodownID,
                    "TType" => "R",
                    "TType2" => $salertnType,
                    "AccountID" => $data["act_name"],
                    "ItemID" => $data[$itemid],
                    "CaseQty" => $data[$pack_val],
                    "SaleRate" => $data[$sale_rate_val],
                    "BasicRate" => $data[$basic_rate_val],
                    "SuppliedIn" => "CS",
                    "BilledQty" => $data[$return_qty],
                    "DiscPerc" => "0.00",
                    "DiscAmt" => "0.00",
                    "cgst" => $data[$cgst_per_val],
                    "cgstamt" => $data[$cgst_amt_val],
                    "sgst" => $data[$sgst_per_val],
                    "sgstamt" => $data[$sgst_amt_val],
                    "igst" => $data[$igst_per_val],
                    "igstamt" => $data[$igst_amt_val],
                    "ChallanAmt" => $TaxableAmt,
                    "NetChallanAmt" => $data[$total_amt_val],
                    "Ordinalno" => $i,
                    "UserID" => $this->session->userdata('username'),
                );
                $this->db->insert(db_prefix() . 'history', $new_record_details);
            }
        }
        return true;

    }

    public function load_data_for_salertn($data)
    {
        $from_date = to_sql_date($data["from_date"]);
        $to_date = to_sql_date($data["to_date"]);
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');

        $sql1 = '(' . db_prefix() . 'salesreturn.Transdate BETWEEN "' . $from_date . ' 00:00:00" AND "' . $to_date . ' 23:59:59")  AND ' . db_prefix() . 'salesreturn.FY = "' . $fy . '" AND ' . db_prefix() . 'salesreturn.PlantID = "' . $selected_company . '" ORDER BY SalesRtnID DESC';

        $sql = 'SELECT ' . db_prefix() . 'salesreturn.*,  
        (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesreturn.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . $selected_company . ') as AccountName, 
        (SELECT GROUP_CONCAT(address SEPARATOR ",") FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesreturn.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . $selected_company . ') as AccountAddr
        FROM ' . db_prefix() . 'salesreturn WHERE ' . $sql1;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /**
     * delete Sae entry
     * @param integer $id
     * @return boolean
     */

    public function delete_sale_entry($id)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if ($selected_company == "1") {
            $GodownID = 'CSPL';
        } else if ($selected_company == "2") {
            $GodownID = 'CFF';
        } else if ($selected_company == "3") {
            $GodownID = 'CBUPL';
        }
        $sale_return_ledger_details = $this->get_sale_return_ledger_detail($id);

        $fetchPreviousSalesReturnDetails = $this->fetchPreviousSalesReturnDeleteDetails($id);

        foreach ($fetchPreviousSalesReturnDetails as $value) {
            $insertArray = array(
                'PlantID' => $value["PlantID"],
                'FY' => $value["FY"],
                'BT' => $value["BT"],
                'SalesRtnID' => $value["SalesRtnID"],
                'Transdate' => $value["Transdate"],
                'AccountID' => $value["AccountID"],
                'PayType' => $value["PayType"],
                'SaleAmt' => $value["SaleAmt"],
                'DiscAmt' => $value["DiscAmt"],
                'VATAmt' => $value["VATAmt"],
                'SATAmt' => $value["SATAmt"],
                'CSTAmt' => $value["CSTAmt"],
                'BillAmt' => $value["BillAmt"],
                'RndAmt' => $value["RndAmt"],
                'ItCount' => $value["ItCount"],
                'UserID' => $value["UserID"],
                'SalesRtnTypeID' => $value["SalesRtnTypeID"],
                'cgstamt' => $value["cgstamt"],
                'sgstamt' => $value["sgstamt"],
                'igstamt' => $value["igstamt"],
                'passedfrom' => $value["passedfrom"],
                'UserID2' => $value["UserID2"],
                'Lupdate' => $value["Lupdate"],
                'created_by' => $this->session->userdata('username'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->db->insert(db_prefix() . 'salesreturn_audit', $insertArray);
        }

        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('PassedFrom', "SALESRTN");
        $this->db->LIKE('SalesRtnID', $id);
        $this->db->delete(db_prefix() . 'salesreturn');
        if ($this->db->affected_rows() > 0) {

            // Stock value revert from old sale return 
            $salertn_details = $this->get_salertn_detailsFrDelete($id);
            foreach ($salertn_details as $key => $value) {
                # code...
                //$stock_data = $this->get_stock_item($value['ItemID']);
                if ($value['TType2'] == "Damage") {
                    //$item_stock= $stock_data->SRDQty;
                    $new_stock = $value['SRDQty'] - $value['BilledQty'];
                    $this->db->where('PlantID', $selected_company);
                    $this->db->where('FY', $fy);
                    $this->db->where('ItemID', $value['ItemID']);
                    $this->db->where('GodownID', $GodownID);
                    $this->db->update(db_prefix() . 'stockmaster', [
                        'SRDQty' => $new_stock,
                    ]);
                } else {
                    //$item_stock= $stock_data->SRQty;
                    $new_stock = $value['SRQty'] - $value['BilledQty'];
                    $this->db->where('PlantID', $selected_company);
                    $this->db->where('FY', $fy);
                    $this->db->where('ItemID', $value['ItemID']);
                    $this->db->where('GodownID', $GodownID);
                    $this->db->update(db_prefix() . 'stockmaster', [
                        'SRQty' => $new_stock,
                    ]);
                }
            }

            $historyDetails = $this->fetchPreviousHistoryDeleteDetails($id);
            foreach ($historyDetails as $value) {
                $insertArray = array(
                    "PlantID" => $value["PlantID"],
                    "FY" => $value["FY"],
                    "OrderID" => $value["OrderID"],
                    "BillID" => $value["BillID"],
                    "TransID" => $value["TransID"],
                    "IsSchemeYN" => $value["IsSchemeYN"],
                    "TransDate" => $value["TransDate"],
                    "TransDate2" => $value["TransDate2"],
                    "TType" => $value["TType"],
                    "TType2" => $value["TType2"],
                    "AccountID" => $value["AccountID"],
                    "ItemID" => $value["ItemID"],
                    "GodownID" => $value["GodownID"],
                    "PurchRate" => $value["PurchRate"],
                    "Mrp" => $value["Mrp"],
                    "SaleRate" => $value["SaleRate"],
                    "BasicRate" => $value["BasicRate"],
                    "SuppliedIn" => $value["SuppliedIn"],
                    "OrderQty" => $value["OrderQty"],
                    "eOrderQty" => $value["eOrderQty"],
                    "ereason" => $value["ereason"],
                    "BilledQty" => $value["BilledQty"],
                    "DiscPerc" => $value["DiscPerc"],
                    "DiscAmt" => $value["DiscAmt"],
                    "gst" => $value["gst"],
                    "gstamt" => $value["gstamt"],
                    "cgst" => $value["cgst"],
                    "cgstamt" => $value["cgstamt"],
                    "sgst" => $value["sgst"],
                    "sgstamt" => $value["sgstamt"],
                    "igst" => $value["igst"],
                    "igstamt" => $value["igstamt"],
                    "CaseQty" => $value["CaseQty"],
                    "Cases" => $value["Cases"],
                    "OrderAmt" => $value["OrderAmt"],
                    "ChallanAmt" => $value["ChallanAmt"],
                    "NetOrderAmt" => $value["NetOrderAmt"],
                    "NetChallanAmt" => $value["NetChallanAmt"],
                    "Ordinalno" => $value["Ordinalno"],
                    "rowid" => $value["rowid"],
                    "UserID" => $value["UserID"],
                    "cnfid" => $value["cnfid"],
                    "UserID2" => $value["UserID2"],
                    "Lupdate" => $value["Lupdate"],
                    "created_by" => $this->session->userdata('username'),
                    "created_at" => date('Y-m-d H:i:s'),
                );
                $this->db->insert(db_prefix() . 'history_Audit', $insertArray);
            }

            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->LIKE('TType', "R");
            $this->db->LIKE('OrderID', $id);
            $this->db->delete(db_prefix() . 'history');

            foreach ($sale_return_ledger_details as $key2 => $value2) {

                $ledger_audit = array(
                    "PlantID" => $value2["PlantID"],
                    "FY" => $value2["FY"],
                    "Transdate" => $value2["Transdate"],
                    "TransDate2" => $value2["TransDate2"],
                    "VoucherID" => $value2["VoucherID"],
                    "AccountID" => $value2["AccountID"],
                    "TType" => $value2["TType"],
                    "Amount" => $value2["Amount"],
                    "Narration" => $value2["Narration"],
                    "PassedFrom" => $value2["PassedFrom"],
                    "OrdinalNo" => $value2["OrdinalNo"],
                    "UserID" => $value2["UserID"],
                    "Lupdate" => date('Y-m-d H:i:s'),
                    "UserID2" => $this->session->userdata('username')
                );
                $this->db->insert(db_prefix() . 'accountledgeraudit', $ledger_audit);
            }
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->LIKE('PassedFrom', "SALESRTN");
            $this->db->LIKE('VoucherID', $id);
            $this->db->delete(db_prefix() . 'accountledger');

            return true;
        }
        return false;
    }

    /**
     * Cancel Sae entry
     * @param integer $id
     * @return boolean
     */

    public function cancel_sale_entry($id)
    {
        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        if ($selected_company == "1") {
            $GodownID = 'CSPL';
        } else if ($selected_company == "2") {
            $GodownID = 'CFF';
        } else if ($selected_company == "3") {
            $GodownID = 'CBUPL';
        }

        $sale_return_entry_details = $this->get_sale_return_details($id);

        foreach ($sale_return_entry_details->items as $key => $value) {
            // stock update
            $stock_data = $this->get_stock_item($value['ItemID']);
            if ($value['TType2'] == "Damage") {
                $item_stock = $stock_data->SRDQty;
                $new_stock = $item_stock - $value['BilledQty'];
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $fy);
                $this->db->where('GodownID', $GodownID);
                $this->db->where('ItemID', $value['ItemID']);
                $this->db->update(db_prefix() . 'stockmaster', [
                    'SRDQty' => $new_stock,
                ]);
            } else {
                $item_stock = $stock_data->SRQty;
                $new_stock = $item_stock - $value['BilledQty'];
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $fy);
                $this->db->where('GodownID', $GodownID);
                $this->db->where('ItemID', $value['ItemID']);
                $this->db->update(db_prefix() . 'stockmaster', [
                    'SRQty' => $new_stock,
                ]);
            }
        }

        $fetchPreviousSalesReturnDetails = $this->fetchPreviousSalesReturnDeleteDetails($id);

        foreach ($fetchPreviousSalesReturnDetails as $value) {
            $insertArray = array(
                'PlantID' => $value["PlantID"],
                'FY' => $value["FY"],
                'BT' => $value["BT"],
                'SalesRtnID' => $value["SalesRtnID"],
                'Transdate' => $value["Transdate"],
                'AccountID' => $value["AccountID"],
                'PayType' => $value["PayType"],
                'SaleAmt' => $value["SaleAmt"],
                'DiscAmt' => $value["DiscAmt"],
                'VATAmt' => $value["VATAmt"],
                'SATAmt' => $value["SATAmt"],
                'CSTAmt' => $value["CSTAmt"],
                'BillAmt' => $value["BillAmt"],
                'RndAmt' => $value["RndAmt"],
                'ItCount' => $value["ItCount"],
                'UserID' => $value["UserID"],
                'SalesRtnTypeID' => $value["SalesRtnTypeID"],
                'cgstamt' => $value["cgstamt"],
                'sgstamt' => $value["sgstamt"],
                'igstamt' => $value["igstamt"],
                'passedfrom' => $value["passedfrom"],
                'UserID2' => $value["UserID2"],
                'Lupdate' => $value["Lupdate"],
                'created_by' => $this->session->userdata('username'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->db->insert(db_prefix() . 'salesreturn_audit', $insertArray);
        }

        $update_salertn = array(
            'SaleAmt' => '0.00',
            'cgstamt' => '0.00',
            'sgstamt' => '0.00',
            'igstamt' => '0.00',
            'RndAmt' => '0.00',
            'BillAmt' => '0.00',
            'UserID2' => $this->session->userdata('username'),
            'Lupdate' => date('Y-m-d H:i:s'),
        );

        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('PassedFrom', "SALESRTN");
        $this->db->LIKE('SalesRtnID', $id);
        $this->db->update(db_prefix() . 'salesreturn', $update_salertn);
        if ($this->db->affected_rows() > 0) {

            $historyDetails = $this->fetchPreviousHistoryDeleteDetails($id);
            foreach ($historyDetails as $value) {
                $insertArray = array(
                    "PlantID" => $value["PlantID"],
                    "FY" => $value["FY"],
                    "OrderID" => $value["OrderID"],
                    "BillID" => $value["BillID"],
                    "TransID" => $value["TransID"],
                    "IsSchemeYN" => $value["IsSchemeYN"],
                    "TransDate" => $value["TransDate"],
                    "TransDate2" => $value["TransDate2"],
                    "TType" => $value["TType"],
                    "TType2" => $value["TType2"],
                    "AccountID" => $value["AccountID"],
                    "ItemID" => $value["ItemID"],
                    "GodownID" => $value["GodownID"],
                    "PurchRate" => $value["PurchRate"],
                    "Mrp" => $value["Mrp"],
                    "SaleRate" => $value["SaleRate"],
                    "BasicRate" => $value["BasicRate"],
                    "SuppliedIn" => $value["SuppliedIn"],
                    "OrderQty" => $value["OrderQty"],
                    "eOrderQty" => $value["eOrderQty"],
                    "ereason" => $value["ereason"],
                    "BilledQty" => $value["BilledQty"],
                    "DiscPerc" => $value["DiscPerc"],
                    "DiscAmt" => $value["DiscAmt"],
                    "gst" => $value["gst"],
                    "gstamt" => $value["gstamt"],
                    "cgst" => $value["cgst"],
                    "cgstamt" => $value["cgstamt"],
                    "sgst" => $value["sgst"],
                    "sgstamt" => $value["sgstamt"],
                    "igst" => $value["igst"],
                    "igstamt" => $value["igstamt"],
                    "CaseQty" => $value["CaseQty"],
                    "Cases" => $value["Cases"],
                    "OrderAmt" => $value["OrderAmt"],
                    "ChallanAmt" => $value["ChallanAmt"],
                    "NetOrderAmt" => $value["NetOrderAmt"],
                    "NetChallanAmt" => $value["NetChallanAmt"],
                    "Ordinalno" => $value["Ordinalno"],
                    "rowid" => $value["rowid"],
                    "UserID" => $value["UserID"],
                    "cnfid" => $value["cnfid"],
                    "UserID2" => $value["UserID2"],
                    "Lupdate" => $value["Lupdate"],
                    "created_by" => $this->session->userdata('username'),
                    "created_at" => date('Y-m-d H:i:s'),
                );
                $this->db->insert(db_prefix() . 'history_Audit', $insertArray);
            }

            $update_salertn_history = array(
                'OrderQty' => '0.00',
                'BilledQty' => '0.00',
                'OrderAmt' => '0.00',
                'DiscAmt' => '0.00',
                'gstamt' => '0.00',
                'cgstamt' => '0.00',
                'sgstamt' => '0.00',
                'igstamt' => '0.00',
                'ChallanAmt' => '0.00',
                'NetOrderAmt' => '0.00',
                'NetChallanAmt' => '0.00',
                'UserID2' => $this->session->userdata('username'),
                'Lupdate' => date('Y-m-d H:i:s'),
            );
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->LIKE('TType', "R");
            $this->db->LIKE('OrderID', $id);
            $this->db->update(db_prefix() . 'history', $update_salertn_history);


            $sale_return_ledger_details = $this->get_sale_return_ledger_detail($id);
            foreach ($sale_return_ledger_details as $key2 => $value2) {

                $ledger_audit = array(
                    "PlantID" => $value2["PlantID"],
                    "FY" => $value2["FY"],
                    "Transdate" => $value2["Transdate"],
                    "TransDate2" => $value2["TransDate2"],
                    "VoucherID" => $value2["VoucherID"],
                    "AccountID" => $value2["AccountID"],
                    "TType" => $value2["TType"],
                    "Amount" => $value2["Amount"],
                    "Narration" => $value2["Narration"],
                    "PassedFrom" => $value2["PassedFrom"],
                    "OrdinalNo" => $value2["OrdinalNo"],
                    "UserID" => $value2["UserID"],
                    "Lupdate" => date('Y-m-d H:i:s'),
                    "UserID2" => $this->session->userdata('username')
                );
                $this->db->insert(db_prefix() . 'accountledgeraudit', $ledger_audit);
            }


            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->LIKE('PassedFrom', "SALESRTN");
            $this->db->LIKE('VoucherID', $id);
            $this->db->delete(db_prefix() . 'accountledger');

            return true;
        }
        return false;
    }

    /**
     * @since  2.7.0
     *
     * decrement the Sale return next nubmer
     *
     * @return void
     */
    public function decrement_sale_return_number()
    {

        $selected_company = $this->session->userdata('root_company');
        if ($selected_company == 1) {
            $this->db->where('name', 'next_sale_return_number_for_cspl');
        } elseif ($selected_company == 2) {
            $this->db->where('name', 'next_sale_return_number_for_cff');
        } elseif ($selected_company == 3) {
            $this->db->where('name', 'next_sale_return_number_for_cbu');
        } elseif ($selected_company == 4) {
            $this->db->where('name', 'next_sale_return_number_for_cbupl');
        }
        $this->db->set('value', 'value-1', false);
        $this->db->update(db_prefix() . 'options');
    }

    public function get_sale_return_entry_detail($id)
    {

        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('SalesRtnID', $id);
        $this->db->where('PassedFrom', "SALESRTN");
        $sale_return_data = $this->db->get(db_prefix() . 'salesreturn')->row();
        return $sale_return_data;
    }

    public function get_sale_return_ledger_detail($id)
    {

        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('VoucherID', $id);
        $sale_return_ledger = $this->db->get(db_prefix() . 'accountledger')->result_array();
        return $sale_return_ledger;
    }

    public function get_next_sale_return_entry($id)
    {

        $fy = $this->session->userdata('finacial_year');
        $selected_company = $this->session->userdata('root_company');


        $sql = 'SELECT * FROM tblsalesreturn WHERE PlantID = ' . $selected_company . ' AND PassedFrom LIKE "SALESRTN" AND FY LIKE "' . $fy . '" AND SalesRtnID > "' . $id . '" ORDER BY tblsalesreturn.SalesRtnID ASC';
        $staff_data = $this->db->query($sql)->result_array();
        return $staff_data;
    }

    public function get_acc_bal($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->where('AccountID', $id);

        return $this->db->get(db_prefix() . 'accountbalances')->row();
    }
    public function get_salertn_details($AccountID, $saleRtnID)
    {
        $selected_company = $this->session->userdata('root_company');
        if ($selected_company == "1") {
            $GodownID = 'CSPL';
        } else if ($selected_company == "2") {
            $GodownID = 'CFF';
        } else if ($selected_company == "3") {
            $GodownID = 'CBUPL';
        }
        $fy = $this->session->userdata('finacial_year');
        $this->db->select(db_prefix() . 'history.*,' . db_prefix() . 'stockmaster.SRQty,' . db_prefix() . 'stockmaster.SRDQty');
        $this->db->join(db_prefix() . 'stockmaster', '' . db_prefix() . 'stockmaster.ItemID = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'stockmaster.PlantID = ' . db_prefix() . 'history.PlantID AND ' . db_prefix() . 'stockmaster.FY = ' . db_prefix() . 'history.FY', 'LEFT');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->LIKE(db_prefix() . 'history.FY', $fy);
        $this->db->where(db_prefix() . 'history.AccountID', $AccountID);
        $this->db->where(db_prefix() . 'history.OrderID', $saleRtnID);
        return $this->db->get(db_prefix() . 'history')->result_array();
    }

    public function get_salertn_detailsFrDelete($saleRtnID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('OrderID', $saleRtnID);
        return $this->db->get(db_prefix() . 'history')->result_array();
    }
    public function get_stock_item($id)
    {
        $selected_company = $this->session->userdata('root_company');
        if ($selected_company == "1") {
            $GodownID = 'CSPL';
        } else if ($selected_company == "2") {
            $GodownID = 'CFF';
        } else if ($selected_company == "3") {
            $GodownID = 'CBUPL';
        }
        $FY = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $FY);
        $this->db->where('ItemID', $id);
        $this->db->where('GodownID', $GodownID);
        return $this->db->get(db_prefix() . 'stockmaster')->row();
    }

    public function get_last_ledger_amt($id, $account_id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->LIKE('AccountID', $account_id);
        $this->db->LIKE('VoucherID', $id);
        $this->db->LIKE('PassedFrom', "SALESRTN");
        return $this->db->get(db_prefix() . 'accountledger')->row();
    }

    public function get_ledger_data($SRTID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $fy);
        $this->db->LIKE('VoucherID', $SRTID);
        $this->db->LIKE('PassedFrom', "SALESRTN");
        return $this->db->get(db_prefix() . 'accountledger')->result_array();
    }

    public function get_sale_return_items($sale_rtn_id, $PlantID, $FY)
    {
        $this->db->select(db_prefix() . 'history.*,' . db_prefix() . 'items.description');
        $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
        $this->db->where(db_prefix() . 'history.OrderID', $sale_rtn_id);
        $this->db->where(db_prefix() . 'history.PlantID', $PlantID);
        $this->db->where(db_prefix() . 'history.FY', $FY);

        return $this->db->get(db_prefix() . 'history')->result_array();


    }

    public function get_sale_return_list()
    {

        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');

        $this->db->select(db_prefix() . 'salesreturn.*,' . db_prefix() . 'clients.company,' . db_prefix() . 'clients.address');
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesreturn.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'salesreturn.PlantID');

        $this->db->where(db_prefix() . 'salesreturn.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'salesreturn.FY', $fy);
        $this->db->order_by(db_prefix() . 'salesreturn.SalesRtnID', "DESC");
        return $this->db->get(db_prefix() . 'salesreturn')->result_array();
    }

    public function SaleRtnList()
    {

        $fy = $this->session->userdata('finacial_year');
        $fy_new = $fy + 1;
        $lastdate_date = '20' . $fy_new . '-03-31';
        $firstdate_date = '20' . $fy_new . '-04-01';
        $curr_date = date('Y-m-d');
        $curr_date_new = new DateTime($curr_date);
        $last_date_yr = new DateTime($lastdate_date);
        if ($last_date_yr < $curr_date_new) {
            $to_date = '31/03/20' . $fy_new;
            $from_date = '01/03/20' . $fy_new;
        } else {
            $from_date = "01/" . date('m') . "/" . date('Y');
            $to_date = date('d/m/Y');
        }
        $from_date = to_sql_date($from_date);
        $to_date = to_sql_date($to_date);

        $sql1 = '(' . db_prefix() . 'salesreturn.Transdate BETWEEN "' . $from_date . ' 00:00:00" AND "' . $to_date . ' 23:59:59")  AND ' . db_prefix() . 'salesreturn.FY = "' . $fy . '" AND ' . db_prefix() . 'salesreturn.PlantID = "' . $selected_company . '" ORDER BY SalesRtnID DESC';

        $sql = 'SELECT ' . db_prefix() . 'salesreturn.*,  
        (SELECT GROUP_CONCAT(company SEPARATOR ",") FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesreturn.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . $selected_company . ') as AccountName, 
        (SELECT GROUP_CONCAT(address SEPARATOR ",") FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesreturn.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . $selected_company . ') as AccountAddr
        FROM ' . db_prefix() . 'salesreturn WHERE ' . $sql1;
        return $sql;
        $result = $this->db->query($sql)->result_array();
        return $result;

    }
    public function get_sale_return_account_details($sale_rtn_act_id, $PlantID)
    {
        $this->db->where('AccountID', $sale_rtn_act_id);
        $this->db->where('PlantID', $PlantID);
        return $this->db->get(db_prefix() . 'clients')->row();
    }

    public function get_Account_Details($postData)
    {
        $selected_company = $this->session->userdata('root_company');
        $AccountID = $postData['AccountID'];
        $this->db->select(db_prefix() . 'clients.*,' . db_prefix() . 'accountroutes.RouteID,' . db_prefix() . 'route.name,' . db_prefix() . 'xx_statelist.state_name,' . db_prefix() . 'customers_groups.name AS aname');

        $this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'clients.PlantID', 'LEFT');
        $this->db->join(db_prefix() . 'route', '' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'accountroutes.RouteID AND ' . db_prefix() . 'route.PlantID = ' . db_prefix() . 'accountroutes.PlantID', 'LEFT');
        $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'LEFT');
        $this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'clients.PlantID', 'LEFT');
        $this->db->where(db_prefix() . 'clients.AccountID', $AccountID);
        $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
        return $this->db->get(db_prefix() . 'clients')->row();

    }
    function getaccounts($postData)
    {

        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $where_clients = '';

        if (isset($postData['search'])) {

            $q = $postData['search'];
            $this->db->select(db_prefix() . 'clients.*,' . db_prefix() . 'xx_statelist.state_name,' . db_prefix() . 'customers_groups.name AS aname');
            $where_clients .= '(company LIKE "%' . $q . '%" ESCAPE \'!\' OR StationName LIKE "%' . $q . '%" ESCAPE \'!\' OR tblclients.AccountID LIKE "%' . $q . '%" ESCAPE \'!\' OR address LIKE "%' . $q . '%" ESCAPE \'!\' OR Address3 LIKE "%' . $q . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1 AND ' . db_prefix() . 'clients.SubActGroupID = 60001004 AND ' . db_prefix() . 'clients.PlantID = ' . $selected_company;

            //$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'clients.PlantID');
            //$this->db->join(db_prefix() . 'route', '' . db_prefix() . 'route.RouteID = ' . db_prefix() . 'accountroutes.RouteID AND ' . db_prefix() . 'route.PlantID = ' . db_prefix() . 'accountroutes.PlantID');
            $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state');
            $this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'clients.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'clients.PlantID');
            $this->db->where($where_clients);

            $this->db->where(db_prefix() . 'clients.PlantID', $selected_company);

            $records = $this->db->get(db_prefix() . 'clients')->result();

            foreach ($records as $row) {
                $lebel = $row->company . ' - ' . $row->AccountID;
                $response[] = array("label" => $lebel, "value" => $row->AccountID, "address" => $row->address, "address2" => $row->Address3, "state" => $row->state, "station" => $row->StationName, "gst" => $row->vat, "route" => $row->RouteID, "route_name" => $row->name, "state_name" => $row->state_name, "account_type" => $row->DistributorType, "account_type_name" => $row->aname);
            }

        }

        return $response;
    }

    function getransaction($postData)
    {

        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');

        if (isset($postData['search'])) {

            $q = $postData['search'];
            $this->db->select('*');
            $this->db->where("TransID like '%" . $postData['search'] . "%' ");
            $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'history.FY', $fy);
            $this->db->where(db_prefix() . 'history.AccountID', $postData['accountId']);
            $this->db->where(db_prefix() . 'history.ItemID', $postData['item_code']);
            $records = $this->db->get(db_prefix() . 'history')->result();
            if ($records) {

                foreach ($records as $row) {
                    $billedCS = $row->BilledQty / $row->CaseQty;
                    $response[] = array("label" => $row->TransID, "value" => $row->TransID, "order_qty" => $row->OrderQty, "FY" => $row->FY, "billedCS" => $billedCS, "disc" => $row->DiscPerc, "disc_amt" => $row->DiscAmt, "basic_rate" => $row->BasicRate);
                }
            } else {
                $response[] = array("label" => "Rocord not fount", "value" => "not found");
            }


        }

        return $response;
    }

    function get_bill($item_code, $act_code)
    {

        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $lastFY = $fy - 1;
        $FYS = array($fy, $lastFY);
        $this->db->select(db_prefix() . 'history.*');
        //$this->db->where("TransID like '%".$postData['search']."%' ");
        $this->db->where(db_prefix() . 'history.TType', "O");
        $this->db->where(db_prefix() . 'history.TransID !=', null);
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where_in(db_prefix() . 'history.FY', $FYS);
        $this->db->where(db_prefix() . 'history.AccountID', $act_code);
        $this->db->where(db_prefix() . 'history.ItemID', $item_code);
        $this->db->order_by(db_prefix() . 'history.FY,' . db_prefix() . 'history.TransID', 'DESC');
        $records = $this->db->get(db_prefix() . 'history')->result();


        return $records;
    }

    function get_bill_details($bill_id, $item_code)
    {

        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');


        $this->db->select('*');
        //$this->db->where("TransID like '%".$postData['search']."%' ");
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        //$this->db->where(db_prefix() . 'history.FY', $fy);
        $this->db->where(db_prefix() . 'history.TType', 'O');
        $this->db->where(db_prefix() . 'history.ItemID', $item_code);
        $this->db->where(db_prefix() . 'history.TransID', $bill_id);
        $records = $this->db->get(db_prefix() . 'history')->row();


        return $records;
    }

    function getitems($postData)
    {

        $response = array();
        $selected_company = $this->session->userdata('root_company');

        if (isset($postData['search'])) {

            $q = $postData['search'];
            $where_item = '';
            $subgroup = array('9', '20', '36');
            //$this->db->select(db_prefix() .'items.*');
            $where_item .= '(description LIKE "%' . $q . '%" ESCAPE \'!\' OR item_code LIKE "%' . $q . '%" ESCAPE \'!\') ';
            $where_item2 = 'SEELCT TransID';
            /*$this->db->where($where_item);
            
             $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
             
            $records = $this->db->get(db_prefix() . 'items')->result();*/



            $this->db->select(db_prefix() . 'items.*, ' . db_prefix() . 'hsn.hsndesc,' . db_prefix() . 'taxes.taxrate');
            $this->db->from(db_prefix() . 'items');
            $this->db->join(db_prefix() . 'hsn', '' . db_prefix() . 'hsn.name = ' . db_prefix() . 'items.hsn_code');
            $this->db->join(db_prefix() . 'taxes', '' . db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax');
            //$this->db->join(db_prefix() . 'history', '' . db_prefix() . 'history.ItemID = ' . db_prefix() . 'items.item_code AND ' . db_prefix() . 'history.AccountID="'.$postData['act_code'].'"','right');
            $this->db->where($where_item);
            //$this->db->where_not_in(db_prefix() . 'items.subgroup_id',$subgroup);
            $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
            $records = $this->db->get()->result();

            foreach ($records as $row) {
                $response[] = array("label" => $row->description, "value" => $row->item_code, "hsn_code" => $row->hsn_code, "tax" => $row->taxrate, "hsndesc" => $row->hsndesc, "case_qty" => $row->case_qty);
            }

        }

        return $response;
    }

    function getitemsDetails($postData)
    {

        $selected_company = $this->session->userdata('root_company');
        $ItemID = $postData['ItemID'];
        $subgroup = array('9', '20', '36');

        $this->db->select(db_prefix() . 'items.*, ' . db_prefix() . 'hsn.hsndesc,' . db_prefix() . 'taxes.taxrate');
        $this->db->from(db_prefix() . 'items');
        $this->db->join(db_prefix() . 'hsn', '' . db_prefix() . 'hsn.name = ' . db_prefix() . 'items.hsn_code');
        $this->db->join(db_prefix() . 'taxes', '' . db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax');
        $this->db->where(db_prefix() . 'items.item_code', $ItemID);
        //$this->db->where_not_in(db_prefix() . 'items.subgroup_id',$subgroup);
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        $records = $this->db->get()->row();
        return $records;
    }

    function getsale_item_list($postData)
    {

        $response = array();
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');


        $this->db->select(db_prefix() . 'history.ItemID');
        $this->db->distinct();
        //$this->db->where("TransID like '%".$postData['search']."%' ");
        //$this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        //$this->db->where(db_prefix() . 'history.FY', $fy);
        $this->db->where(db_prefix() . 'history.AccountID', $postData['account_id']);
        $this->db->where(db_prefix() . 'history.BillID !=', null);
        //$this->db->where(db_prefix() . 'history.ItemID', $postData['item_code']);
        $records = $this->db->get(db_prefix() . 'history')->result();

        $record_string = '';
        foreach ($records as $row) {
            // $response[] = array("label"=>$row->TransID,"value"=>$row->OrderID,"order_amt"=>$row->NetOrderAmt);
            $record_string .= $row->ItemID . ",";
        }


        return $record_string;
    }



    public function get_accounts_data_model($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = 21;

        $sql = 'SELECT ' . db_prefix() . 'salesmaster.SalesID, ' . db_prefix() . 'salesmaster.Transdate, ' . db_prefix() . 'salesmaster.BillAmt, ' . db_prefix() . 'salesmaster.AccountID,
        ' . db_prefix() . 'clients.company FROM ' . db_prefix() . 'salesmaster
 INNER JOIN ' . db_prefix() . 'clients
 ON ' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'salesmaster.AccountID
 WHERE ' . db_prefix() . 'salesmaster.AccountID = ' . $id . ' AND      
 ' . db_prefix() . 'salesmaster.PlantID = ' . $selected_company . ' AND ' . db_prefix() . 'salesmaster.FY = ' . $fy . ' AND ' . db_prefix() . 'clients.PlantID = ' . $selected_company;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function fetchPreviousHistoryDetails($accountID, $orderID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('AccountID', $accountID);
        $this->db->where('OrderID', $orderID);

        return $this->db->get(db_prefix() . 'history')->result_array();
    }

    public function fetchSalesReturnDetails($accountID, $orderID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->where('AccountID', $accountID);
        $this->db->where('SalesRtnID', $orderID);

        return $this->db->get(db_prefix() . 'salesreturn')->result_array();
    }

    public function fetchPreviousSalesReturnDeleteDetails($salesReturnID)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('PassedFrom', "SALESRTN");
        $this->db->LIKE('SalesRtnID', $salesReturnID);

        return $this->db->get(db_prefix() . 'salesreturn')->result_array();
    }

    public function fetchPreviousHistoryDeleteDetails($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $fy = $this->session->userdata('finacial_year');
        $this->db->where('PlantID', $selected_company);
        $this->db->LIKE('FY', $fy);
        $this->db->LIKE('TType', "R");
        $this->db->LIKE('OrderID', $id);
        return $this->db->get(db_prefix() . 'history')->result_array();
    }
}