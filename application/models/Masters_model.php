<?php
	
defined('BASEPATH') or exit('No direct script access allowed');

class Masters_model extends App_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function GetAllPartyList()
	{
	    $SubActGroupID = "1000012"; // TRADE RECEIVABLES
		$selected_company = $this->session->userdata('root_company');
		$this->db->select('tblclients.AccountID,tblclients.company');
		$this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.AccountID = ' . db_prefix() . 'clients.AccountID AND '. db_prefix() .'contacts.PlantID = ' . db_prefix() . 'clients.PlantID ');
		
		$this->db->where(db_prefix() . 'clients.SubActGroupID', $SubActGroupID);
		$this->db->order_by('company', 'asc');
		$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
		return $this->db->get(db_prefix() . 'clients')->result_array();
	}
	
	public function GetAllFGList()
	{
	    $FGGroupID = array('1'); // Finished Goods,Semi Finished Goods
		$selected_company = $this->session->userdata('root_company');
		$this->db->select('tblitems.item_code,tblitems.description');
		//$this->db->join(db_prefix() . 'items_sub_groups', '' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID2');
		$this->db->where_in(db_prefix() . 'items.MainGrpID', $FGGroupID);
		$this->db->order_by('description', 'asc');
		$this->db->where(db_prefix() . 'items.PlantID', $selected_company);
		return $this->db->get(db_prefix() . 'items')->result_array();
	}
	// Get Party Wise Item Wise Article List
	public function GetPartyWiseArticleList($AccountID)
    {
        $this->db->select('tblArticleMaster.*');
        $this->db->where('tblArticleMaster.AccountID', $AccountID);
        $data = $this->db->get('tblArticleMaster')->result_array();
        return $data;
    }
	// Add New ItemID Wise Party Wise Article
    public function SaveItemWiseArticle($data)
    {
        $UserID = $this->session->userdata('username');
        $ParameterArray = json_decode($data['ArticledataSerializedArr'], true);
		$ParameterArraylen = count($ParameterArray);
		$AccountID = $data['AccountID'];
		unset($data['AccountID']);
		unset($data['ArticledataArraylength']);
		unset($data['ArticledataSerializedArr']);
		$i = 0;
		foreach ($ParameterArray as $value) {
            $insertArray = array(
                "AccountID" =>$AccountID,
                "ItemID" =>$value["0"],
                "ArticleName" =>$value["1"],
                "UserID" =>$UserID,
                "TransDate" =>date('Y-m-d H:i:s'),
            );
            if($this->db->insert(db_prefix() . 'ArticleMaster', $insertArray)){
                $i++;
            }
        }
        if($i>0){
            return true;
        }else{
            return false;
        }
    }
      
    // Add New Or Update Exiting ItemID Wise Party Wise Article
    public function UpdateItemWiseArticle($data)
    { 
        $UserID = $this->session->userdata('username');
        $ParameterArray = json_decode($data['ArticledataSerializedArr'], true);
		$ParameterArraylen = count($ParameterArray);
		$AccountID = $data['AccountID'];
		$ItemDeletedIds = $data['ItemDeleted'];
		$ItemDeletedIdsArray = explode(",", $ItemDeletedIds);
		// Get Exiting Item Wise Article List & move to history table
		$ArticleList = $this->GetPartyWiseArticleList($AccountID);
		foreach($ArticleList as $key=>$val){
		    $moveArray = array(
		        "id" =>$val["id"],
		        "AccountID" =>$val["AccountID"],
		        "ItemID" =>$val["ItemID"],
		        "ArticleName" =>$val["ArticleName"],
		        "UserID" =>$val["UserID"],
		        "TransDate" =>$val["TransDate"],
		        "UserID2" =>$UserID,
                "Lupdate" =>date('Y-m-d H:i:s'),
		    );
		    $this->db->insert(db_prefix() . 'ArticleMasterHistory', $moveArray);
		}
		
		unset($data['AccountID']);
		unset($data['ArticledataArraylength']);
		unset($data['ArticledataSerializedArr']);
		$i = 0;
		foreach ($ParameterArray as $value) {
		    if($value["2"] == ""){
		        $insertArray = array(
                    "AccountID" =>$AccountID,
                    "ItemID" =>$value["0"],
                    "ArticleName" =>$value["1"],
                    "UserID" =>$UserID,
                    "TransDate" =>date('Y-m-d H:i:s'),
                );
                if($this->db->insert(db_prefix() . 'ArticleMaster', $insertArray)){
                    $i++;
                }
		    }else{
		        $this->db->where('AccountID', $AccountID);
                $this->db->where('ItemID', $value["0"]);
                if($this->db->update(db_prefix() . 'ArticleMaster', ["ArticleName"=>$value["1"]])){
                    $i++;
                }
		    }
        }
        if($i>0){
            // Delete Item as per Front end against record id
            $this->db->where('AccountID', $AccountID);
            $this->db->where_in('id', $ItemDeletedIdsArray);
            $this->db->delete(db_prefix() . 'ArticleMaster');
            return true;
        }else{
            return false;
        }
    }
}
?>