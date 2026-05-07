<?php
	
defined('BASEPATH') or exit('No direct script access allowed');

class QcMaster_model extends App_Model
{
    public function __construct()
	{
		parent::__construct();
	}
	public function GetPlantDetails()
	{   
		$selected_company = $this->session->userdata('root_company');
		$FY = $this->session->userdata('finacial_year');
		
		$sql ='SELECT '.db_prefix().'setup.*
		FROM '.db_prefix().'setup WHERE PlantID = '.$selected_company.' AND FY = "'.$FY.'"';
		$result = $this->db->query($sql)->row();
		return $result;
	}
//==================== Get All Purchase Entry Qc Status ========================
	public function LoadQCStatusList($data)
	{  
		$from_date = to_sql_date($data["from_date"]);
		$to_date = to_sql_date($data["to_date"]);
		$status = $data["status"];
		$fy = $this->session->userdata('finacial_year');
		$selected_company = $this->session->userdata('root_company');
		
		$sql1 = '('.db_prefix().'purchasemaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'purchasemaster.FY = "'.$fy.'" AND '.db_prefix().'purchasemaster.PlantID = "'.$selected_company.'" ';
		if(!empty($status))
		{
			$sql1 .= ' AND '.db_prefix().'purchasemaster.cur_status = "'.$status.'"';
		}
		$sql1 .= ' ORDER BY Transdate DESC';
		$sql ='SELECT '.db_prefix().'purchasemaster.*,  
		(SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'purchasemaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as AccountName
		FROM '.db_prefix().'purchasemaster WHERE '.$sql1;
		$result = $this->db->query($sql)->result_array();
		
		foreach($result as &$each){
			$each['QCStatus'] = $this->GetItemWiseQCStatusByEntryNo($each['PurchID']);
		}
		return $result;
	}
//======================= Get ItemWise Purchase Entry wise Qc status ===========
	public function GetItemWiseQCStatusByEntryNo($PurEntryNO)
	{
		$selected_company = $this->session->userdata('root_company');
		
		$this->db->select('*');
		$this->db->where(db_prefix() . 'ItemWiseQCStatus.PurchaseEntryNo', $PurEntryNO);
		$Data = $this->db->get('tblItemWiseQCStatus')->result_array();
		return $Data;
	}
//===================== Get Purchase Entry Details =============================
    public function GetPurchaseEntryDetails($id)
    {
		$selected_company = $this->session->userdata('root_company');
		$year = $this->session->userdata('finacial_year');
		$this->db->select('tblpurchasemaster.*,tblpurchasemaster.AccountID As Vendor,tblclients.*,tblxx_statelist.*,tblaccountbalances.*,tblxx_citylist.city_name');
		$this->db->from(db_prefix() . 'purchasemaster');
		$this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'purchasemaster.AccountID', 'left');
		$this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
		$this->db->join(db_prefix() . 'xx_citylist', db_prefix() . 'xx_citylist.id = ' . db_prefix() . 'clients.city', 'left');
		$this->db->join(db_prefix() . 'accountbalances', db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND '.db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND '.db_prefix() . 'accountbalances.FY ="'.$year.'"', 'left');
		$this->db->where(db_prefix() . 'purchasemaster.PurchID', $id);
		$this->db->where(db_prefix() . 'purchasemaster.PlantID', $selected_company);
		$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
		$this->db->where(db_prefix() . 'purchasemaster.FY', $year);
		$data = $this->db->get()->row();
		if($data){
			$data->items = $this->ItemAssocToVendor($data->AccountID);
		}
		return $data;
	}
//======================= Get Purchase Entry Item List =========================
    public function GetItemListAgianstPurchaseEntry($id)
    {
		$selected_company = $this->session->userdata('root_company');
		$year = $_SESSION['finacial_year'];
		$this->db->select();
		$this->db->from(db_prefix() . 'history');
		$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID', 'left');
		// $this->db->join(db_prefix() . 'history', db_prefix() . 'history.OrderID = ' . db_prefix() . 'purchasemaster.PurchID', 'left');
		$this->db->where(db_prefix() . 'history.OrderID', $id);
		$this->db->where(db_prefix() . 'history.PlantID', $selected_company);
		//$this->db->where(db_prefix() . 'items.PlantID', $selected_company);
		$this->db->where(db_prefix() . 'history.FY', $year);
		return $this->db->get()->result_array();
	}
//=================== Get QC Item List against Purchase Entry ==================
	public function GetQCApplicableItem($EntryNo)
	{
		$selected_company = $this->session->userdata('root_company');
		
		$this->db->select('*');
		$this->db->where(db_prefix() . 'ItemWiseQCStatus.PurchaseEntryNo', $EntryNo);
		$Data = $this->db->get('tblItemWiseQCStatus')->result_array();
		foreach($Data as &$each){
			$each['QCParameters']	= $this->GetQCParameterByItem($each['ItemID']);
			$each['QCValues']	= $this->GetQCParameterValuesByItem($EntryNo,$each['ItemID']);
		}
		return $Data;
	}
//============================= Get QC Parameter ===============================
    public function GetQCParameterByItem($itemid)
    {
		$selected_company = $this->session->userdata('root_company');
		$year = $_SESSION['finacial_year'];
		$this->db->select('tblqc_master.*,tblqc_parameter.parameter_name,tblqc_unit.unit_name,tblqc_unit.measured_in');
		$this->db->from(db_prefix() . 'qc_master');
		$this->db->join(db_prefix() . 'qc_parameter', db_prefix() . 'qc_parameter.id = ' . db_prefix() . 'qc_master.para_id', 'INNER');
		$this->db->join(db_prefix() . 'qc_unit', db_prefix() . 'qc_unit.id = ' . db_prefix() . 'qc_parameter.unit_id', 'INNER');
		$this->db->where(db_prefix() . 'qc_master.ItemID',$itemid);
		return $this->db->get()->result_array();
	}
	
//======================== Get QC Parameter with Value =========================
    public function GetQCParameterValuesByItem($EntryNo,$itemid)
    {
		$selected_company = $this->session->userdata('root_company');
		$year = $_SESSION['finacial_year'];
		$this->db->select('*');
		$this->db->from(db_prefix() . 'ItemWiseQcDetails');
		$this->db->where(db_prefix() . 'ItemWiseQcDetails.PurchaseEntryNo',$EntryNo);
		$this->db->where(db_prefix() . 'ItemWiseQcDetails.ItemID',$itemid);
		return $this->db->get()->result_array();
	}
//================ Vendor Wise Item List =======================================
    // Item Assoc To Vendor
	public function ItemAssocToVendor($AccountID)
	{
		$selected_company = $this->session->userdata('root_company');
		
		$this->db->select('tblVendorWiseItems.*,tblitems.description,tblitems.unit,tblitems.case_qty as CaseQty');
		$this->db->from(db_prefix() . 'VendorWiseItems');
		$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'VendorWiseItems.ItemID AND '.db_prefix() . 'items.PlantID = ' . $selected_company . '', 'INNER');
		$this->db->where('AccountID', $AccountID);
		$this->db->where('status', 'Y');
		$result = $this->db->get()->result();
		
		// Extract the ItemID into an array
		$item_ids = array_column($result, 'ItemID');
		
		// Convert the array of ItemIDs into a comma-separated string
		$comma_separated_item_ids = implode(',', $item_ids);
		
		// Return the comma-separated string
		return $comma_separated_item_ids;
		
	}
}
	