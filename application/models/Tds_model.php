<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tds_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
   
    public function GetTDSList()
        {
            $this->db->select('TDSCode, TDSName,ThresholdLimit');
            $TDSList = $this->db->get('tblTDSMaster')->result_array();
			return $TDSList;
        }

	public function GetTDSDetails($TDSCode)
	{
		$this->db->select('tblTDSMaster.*');
		$this->db->where('TDSCode', $TDSCode);
		$TDSDetails = $this->db->get('tblTDSMaster')->row();
		if($TDSDetails){
			$this->db->select('tblTDSDetails.*');
			$this->db->where('TDSCode', $TDSCode);
            $TDSDetailsList = $this->db->get('tblTDSDetails')->result_array();
			$TDSDetails->Details = $TDSDetailsList;
		}
		return $TDSDetails;
	}
	 // Add New ItemID
    public function SaveItemID($data)
    {
        
        $ParameterArray = json_decode($data['paradataSerializedArr'], true);
		$ParameterArraylen = count($ParameterArray);
		unset($data['paradataArraylength']);
		unset($data['paradataSerializedArr']);
		$this->db->insert(db_prefix() . 'TDSMaster', $data);
		if($this->db->affected_rows() > 0){
			foreach ($ParameterArray as $value) {
                $insertArray = array(
                    "TDSCode" =>$data["TDSCode"],
                    "effective_date" =>date('Y-m-d H:i:s'),
					"description" =>$value["0"],
                    "rate" =>$value["1"],
                    "Transdate" =>date('Y-m-d H:i:s'),
                );
                $this->db->insert(db_prefix() . 'TDSDetails', $insertArray);
            }
			return true;
		}else{
			return false;
		}
    }
	
	 // Update Exiting TDSCode
    public function UpdateItemID($data)
    {
        $ParameterArray = json_decode($data['paradataSerializedArr'], true);
		$TDSCode = $data['TDSCode'];
		$ParameterArraylen = count($ParameterArray);
        unset($data["paradataSerializedArr"]);
        unset($data["paradataArraylength"]);
		$this->db->where('TDSCode', $TDSCode);
        if($this->db->update(db_prefix() . 'TDSMaster', $data)){
            // Insert / Update Parameter
			for($k=0; $k<$ParameterArraylen; $k++) {
				$description = $ParameterArray[$k][0];
				$rate = $ParameterArray[$k][1];
				$ids = $ParameterArray[$k][2];
				if(!empty($ids)){
					$UpdateAddress = array(
						"description" =>$description,
						"rate" =>$rate,
					);
					$this->db->where('id', $ids);
					$this->db->update(db_prefix() . 'TDSDetails', $UpdateAddress);
				}else{
					$InsAddress = array(
						"TDSCode" =>$TDSCode,
						"effective_date" =>date('Y-m-d H:i:s'),
						"description" =>$description,
						"rate" =>$rate,
						"Transdate" =>date('Y-m-d H:i:s')
					);
					$this->db->insert(db_prefix() . 'TDSDetails',$InsAddress);
				}
			}
			return true;
        }else{
            return false;
        }
        
    }
    
}

