<?php

defined('BASEPATH') or exit('No direct script access allowed');

class traceability_model extends App_Model
{
    public function __construct()
    {
        parent::__construct(); 
    }
    
    public function GetPoEntryDetails()
    {
        $this->db->distinct(); 
        $this->db->select('tblpurchasemaster.PurchID');
        $this->db->from('tblpurchasemaster');
    
        $this->db->join(
            'tblhistory',
            'tblhistory.OrderID = tblpurchasemaster.PO_Number',
            'inner'
        );
    
        $this->db->join(
            'tblitems',
            'tblitems.item_code = tblhistory.ItemID',
            'inner'
        );
    
        $this->db->where('tblitems.IsTraceability', 'Y');
    
        $this->db->order_by('tblpurchasemaster.PurchID', 'DESC');
    
        $query = $this->db->get();
    
        if (!$query) {
            print_r($this->db->error());
            return [];
        }
    
        return $query->result_array();
    }
    
    public function GetPoEntryDetailByID($POEntryNo)
    {
        $this->db->select("
            tblpurchasemaster.PurchID,
            tblpurchasemaster.PO_Number,
            DATE_FORMAT(tblpurchasemaster.Transdate, '%d/%m/%Y') AS Transdate,
            tblclients.company
        ");
        
        $this->db->from('tblpurchasemaster');
        $this->db->join(
            'tblclients',
            'tblclients.AccountID = tblpurchasemaster.AccountID',
            'left'
        );
        $this->db->where('tblpurchasemaster.PurchID', $POEntryNo);
    
        return $this->db->get()->row();
    }
    
    public function GetPoHistoryByPONumber($PO_Number)
    {
        $this->db->select('tblhistory.ItemID,tblitems.description AS ItemName');
		$this->db->from('tblhistory');
		$this->db->join('tblitems', 'tblitems.item_code = tblhistory.ItemID', 'left');
		$this->db->where('OrderID', $PO_Number);
		$this->db->where('tblitems.IsTraceability', 'Y'); 
		return $this->db->get()->result_array();
    }
    
    public function GethistoryDetails($ItemID,$PO_No)
    {
        $this->db->select('tblhistory.batch_no,tblitems.description AS ItemName,tblitems.BotanicalName,tblitems_sub_groups.name AS SubGrp1,tblitems_sub_group2.name AS SubGrp2');
		$this->db->from('tblhistory');
		$this->db->join('tblitems', 'tblitems.item_code = tblhistory.ItemID', 'left');
		$this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.SubGrpID1', 'left');
		$this->db->join('tblitems_sub_group2', 'tblitems_sub_group2.id = tblitems.SubGrpID2', 'left');
		$this->db->where('BillID', $PO_No);
		$this->db->where('ItemID', $ItemID);
		$Details = $this->db->get()->row_array();
		if($Details)
		{
		    $this->db->select('tblItemQcParameter.ItemID,tblQcMaster.ParameterName,tblItemQcParameter.ParameterID');
    		$this->db->from('tblItemQcParameter');
    		$this->db->join('tblQcMaster', 'tblQcMaster.id = tblItemQcParameter.ParameterID', 'left');
    		$this->db->where('tblItemQcParameter.ItemID', $ItemID);
    		$ItemParameterDetails = $this->db->get()->result_array();
    		$Details['ParameterDetails'] = $ItemParameterDetails;
		}
		return $Details;
    }
    
    public function GetTraceabilityDetails()
    {
        $this->db->select('tblTraceability.*,tblpurchasemaster.PO_Number,tblpurchasemaster.Transdate AS POEntryDate,tblpurchasemaster.AccountID,tblclients.company,tblitems.description AS ItemName,tblstaff.firstname,tblstaff.lastname');
		$this->db->from('tblTraceability');
		$this->db->join('tblpurchasemaster', 'tblpurchasemaster.PurchID = tblTraceability.PurchID', 'left');
		$this->db->join('tblclients', 'tblclients.AccountID = tblpurchasemaster.AccountID', 'left');
		$this->db->join('tblitems', 'tblitems.item_code = tblTraceability.ItemID', 'left');
		$this->db->join('tblstaff', 'tblstaff.AccountID = tblTraceability.UserID', 'left');
		return $this->db->get()->result_array();
    }
    
    public function GetTraceabilityDetailsByID($ID) 
    {
        $this->db->select('tblTraceability.*,tblpurchasemaster.PO_Number,tblpurchasemaster.Transdate AS POEntryDate,tblpurchasemaster.AccountID,tblclients.company,tblitems.description AS ItemName,tblitems.BotanicalName,tblstaff.firstname,tblstaff.lastname,
        tblitems.SubGrpID1,tblitems.SubGrpID2,tblitems_sub_groups.name AS SubGrp1,tblitems_sub_group2.name AS SubGrp2');
		$this->db->from('tblTraceability');
		$this->db->join('tblpurchasemaster', 'tblpurchasemaster.PurchID = tblTraceability.PurchID', 'left');
		$this->db->join('tblclients', 'tblclients.AccountID = tblpurchasemaster.AccountID', 'left');
		$this->db->join('tblitems', 'tblitems.item_code = tblTraceability.ItemID', 'left');
		$this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.SubGrpID1', 'left');
		$this->db->join('tblitems_sub_group2', 'tblitems_sub_group2.id = tblitems.SubGrpID2', 'left');
		$this->db->join('tblstaff', 'tblstaff.AccountID = tblTraceability.UserID', 'left');
		$this->db->where('tblTraceability.id', $ID);
		return $this->db->get()->row_array();
    }
    
    public function GetNutritionalValueDetails($BatchNo)
    {
        $this->db->select('tbltraceabilityNutritionalValue.*,');
		$this->db->from('tbltraceabilityNutritionalValue');
		$this->db->where('tbltraceabilityNutritionalValue.BatchNo', $BatchNo);
		return $this->db->get()->result_array();
    }
    
    public function GetLabanalysisDetails($BatchNo)
    {
        $this->db->select('tblLabanalysisReport.*,tblQcMaster.ParameterName');
		$this->db->from('tblLabanalysisReport');
		$this->db->join('tblQcMaster', 'tblQcMaster.id = tblLabanalysisReport.Name', 'left');
		$this->db->where('tblLabanalysisReport.BatchNo', $BatchNo);
		return $this->db->get()->result_array();
    }
    
    public function GetParameterDetailsByID($ID)
    {
        $this->db->select("
            tblQcMaster.*
        ");
        
        $this->db->from('tblQcMaster');
      
        $this->db->where('tblQcMaster.id', $ID);
    
        return $this->db->get()->row();
    }
    
    public function GetAllQcParameters()
    {
        return $this->db
        ->select('*')
        ->from('tblQcMaster')
        ->order_by('id', 'DESC')
        ->get()
        ->result_array();
    }
    
}