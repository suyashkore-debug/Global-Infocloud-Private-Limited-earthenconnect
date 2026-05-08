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

	 public function getRowpdf($table, $select, $where)
		{
		$this->db->select($select . ', tblgodownmaster.AccountName');
		$this->db->from($table);

		// Join with godown master table
		$this->db->join(
			'tblgodownmaster',
			'tblgodownmaster.id = ' . $table . '.WarehouseID',
			'left'
		);

		$this->db->where($where);

		return $this->db->get()->row();
		}


		public function getRIC($data, $limit, $offset)
{
    $from_date    = $data['fromDate'] ?? date('Y-m-01');
    $to_date      = $data['toDate'] ?? date('Y-m-d');
    $WarehouseID  = $data['WarehouseID'] ?? '';

    $offset = ($offset == 0) ? 0 : ($offset * $limit);

    $this->db->from('tbl_receiving_inspection log');

    $this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
    $this->db->join('tblclients client', 'client.userid = log.SupplierID', 'left');
    $this->db->join('tblitems item', 'item.id = log.ProductID', 'left');

    $this->db->join(
        'tbl_receiving_inspection_details details',
        "details.InspectionID = log.InspectionID AND details.ParameterName = 'Packaging'",
        'left'
    );

    $this->db->join(
        'tbl_receiving_inspection_details details1',
        "details1.InspectionID = log.InspectionID AND details1.ParameterName = 'Moisture'",
        'left'
    );

    $this->db->join(
        'tbl_receiving_inspection_details details2',
        "details2.InspectionID = log.InspectionID AND details2.ParameterName = 'Pest'",
        'left'
    );

    $this->db->join(
        'tbl_receiving_inspection_details details3',
        "details3.InspectionID = log.InspectionID AND details3.ParameterName = 'Labels'",
        'left'
    );

    $this->db->join(
        'tbl_receiving_inspection_details details4',
        "details4.InspectionID = log.InspectionID AND details4.ParameterName = 'COA'",
        'left'
    );

    // Date filter
    if (!empty($from_date)) {
        $this->db->where('DATE(log.TransDate) >=', $from_date);
    }

    if (!empty($to_date)) {
        $this->db->where('DATE(log.TransDate) <=', $to_date);
    }

    // Warehouse filter
    if ($WarehouseID != '') {
        $this->db->where('log.WarehouseID', $WarehouseID);
    }

    $total = $this->db->count_all_results('', FALSE);

    $this->db->select([
        'log.*',
        'godown.AccountName as WarehouseName',
        'client.company as SupplierName',
        'item.description as ProductName',
        'details.Status as PackagingStatus',
        'details1.Status as MoistureStatus',
        'details2.Status as PestStatus',
        'details3.Status as LabelsStatus',
        'details4.Status as COAStatus'
    ]);

    $this->db->limit($limit, $offset);
    $this->db->order_by('log.TransDate', 'DESC');

    $rows = $this->db->get()->result_array();

    return [
        'total' => $total,
        'rows'  => $rows
    ];
}
					
	}
