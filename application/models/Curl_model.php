<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Curl_model extends App_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function validatePayload($data, $requiredFields=[]) {
			foreach($data as $key => $value){
				if(in_array($key, $requiredFields) && empty($value)){
					return false;
				}
				if(is_array($key)){
					continue;
				}
				$key = trim($key);
			}
			return $data;
		}
		
		public function getRow($table, $select, $where){
			$this->db->select($select);
			$this->db->from($table);
			$this->db->where($where);
			return $this->db->get()->row();
		}

		public function getResult($table, $select='*', $where='', $group_by = '', $order_by = '', $limit = '', $offset = ''){
			$this->db->select($select);
			$this->db->from($table);
			if(!empty($where)){
				$this->db->where($where);
			}
			if(!empty($group_by)){
				$this->db->group_by($group_by);
			}
			if(!empty($order_by)){
				$this->db->order_by($order_by);
			}
			if(!empty($limit)){
				$this->db->limit($limit, $offset);
			}
			return $this->db->get()->result();
		}

		public function checkExist($table, $where){
			$this->db->select('1');
			$this->db->from($table);
			$this->db->where($where);
			return $this->db->get()->row() ? true : false;
		}

		public function saveData($table, $data, $where = ''){
			if(!empty($where)){
				$this->db->where($where);
				$result = $this->db->update($table, $data);
				return $result ? $this->db->affected_rows() : false;
			}else{
				$this->db->insert($table, $data);
				return $this->db->insert_id();
			}
		}

		public function batchSave($table, $data, $batch_size = 100){
			$chunks = array_chunk($data, $batch_size);
			foreach($chunks as $chunk){
				$this->db->insert_batch($table, $chunk);
			}
			return true;
		}

		public function batchUpdate($table, $data)
		{
			// Data format: [
			// ['where_column' => value, ...], ['update_column' => value, ...],
			// ['where_column' => value, ...], ['update_column' => value, ...],
			// ...
			// ]
			foreach ($data as $row) {
				$where = $row[0];
				$updateData = $row[1];

				$this->db->where($where);
				$this->db->update($table, $updateData);
			}

			return true;
		}
		
		public function getrootcompany($table)
        {
            $this->db->select('*');
            $this->db->from($table);
        
            $query = $this->db->get();
        
            return [
                'data'  => $query->result_array()
            ];
        }
		
	}
