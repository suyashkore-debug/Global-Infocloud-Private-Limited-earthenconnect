<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class History_model extends App_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->PlantID = $this->session->userdata('root_company');
			$this->FY = $this->session->userdata('finacial_year');
		}

		public function getDistinctClientsFromHistory($where = ''){
			$this->db->select('h.AccountID, c.company, c.userid');
			$this->db->from(db_prefix() . 'history h');
			$this->db->join(db_prefix() . 'clients c', 'c.AccountID = h.AccountID', 'left');
			$this->db->where('h.PlantID ='.$this->PlantID);
			$this->db->where('h.FY ='. $this->FY);
			$this->db->where('h.AccountID IS NOT NULL');
			if (!empty($where)) {
				$this->db->where($where);
			}
			$this->db->group_by('h.AccountID');
			return $this->db->get()->result();
		}

		public function getDistinctItemsFromHistory($where = ''){
			$this->db->select('h.ItemID, i.id, i.item_code, i.description');
			$this->db->from(db_prefix() . 'history h');
			$this->db->join(db_prefix() . 'items i', 'i.item_code = h.ItemID', 'left');
			$this->db->where('h.ItemID IS NOT NULL');
			$this->db->where('h.PlantID ='.$this->PlantID);
			$this->db->where('h.FY ='. $this->FY);
			if(!empty($where)){
				$this->db->where($where);
			}
			$this->db->group_by('h.ItemID');
			return $this->db->get()->result();
		}

		public function getDistinctBatchesFromHistory($where = ''){
			$this->db->select('h.batch_no');
			$this->db->from(db_prefix() . 'history h');
			$this->db->where('h.batch_no IS NOT NULL');
			$this->db->where('h.PlantID ='.$this->PlantID);
			$this->db->where('h.FY ='. $this->FY);
			if(!empty($where)){
				$this->db->where($where);
			}
			$this->db->group_by('h.batch_no');
			return $this->db->get()->result();
		}
	}
