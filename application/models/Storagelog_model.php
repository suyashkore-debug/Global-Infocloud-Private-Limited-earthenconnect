<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Storagelog_model extends App_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function getListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_daily_storage_log log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			$this->db->join('tbl_daily_storage_log_details details', "details.LogID = log.LogID AND details.ParameterName = 'Temp'", 'left');
			$this->db->join('tbl_daily_storage_log_details details1', "details1.LogID = log.LogID AND details1.ParameterName = 'Humidity'", 'left');
			$this->db->join('tbl_daily_storage_log_details details2', "details2.LogID = log.LogID AND details2.ParameterName = 'Pest'", 'left');
			$this->db->join('tbl_daily_storage_log_details details3', "details3.LogID = log.LogID AND details3.ParameterName = 'Clean'", 'left');
			$this->db->join('tbl_daily_storage_log_details details4', "details4.LogID = log.LogID AND details4.ParameterName = 'Packaging'", 'left');
			
			$this->db->where('log.TransDate >=', $from_date);
			$this->db->where('log.TransDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			
			$total = $this->db->count_all_results('', FALSE);

			$this->db->select([
				'log.*',
				'godown.AccountName as WarehouseName',
				'details.ReadingValue as TempReading',
				'details1.ReadingValue as HumidityReading',
				'details2.ReadingValue as PestReading',
				'details3.ReadingValue as CleanReading',
				'details4.ReadingValue as PackagingReading'
			]);
			
			$this->db->limit($limit, $offset);
			$this->db->order_by('log.TransDate', 'DESC');
			$rows = $this->db->get()->result_array();

			return [
				'total' => $total,
				'rows'  => $rows
			];
		}
		
		public function getRICListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_receiving_inspection log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			$this->db->join('tblclients client', 'client.userid = log.SupplierID', 'left');
			$this->db->join('tblitems item', 'item.id = log.ProductID', 'left');
			$this->db->join('tbl_receiving_inspection_details details', "details.InspectionID = log.InspectionID AND details.ParameterName = 'Packaging'", 'left');
			$this->db->join('tbl_receiving_inspection_details details1', "details1.InspectionID = log.InspectionID AND details1.ParameterName = 'Moisture'", 'left');
			$this->db->join('tbl_receiving_inspection_details details2', "details2.InspectionID = log.InspectionID AND details2.ParameterName = 'Pest'", 'left');
			$this->db->join('tbl_receiving_inspection_details details3', "details3.InspectionID = log.InspectionID AND details3.ParameterName = 'Labels'", 'left');
			$this->db->join('tbl_receiving_inspection_details details4', "details4.InspectionID = log.InspectionID AND details4.ParameterName = 'COA'", 'left');
			
			$this->db->where('log.TransDate >=', $from_date);
			$this->db->where('log.TransDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			
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
		
		public function getSCListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_weekly_sanitation log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			$this->db->join('tbl_weekly_sanitation_details details', "details.SanitationID = log.SanitationID AND details.AreaName = 'Floor'", 'left');
			$this->db->join('tbl_weekly_sanitation_details details1', "details1.SanitationID = log.SanitationID AND details1.AreaName = 'Pallets'", 'left');
			$this->db->join('tbl_weekly_sanitation_details details2', "details2.SanitationID = log.SanitationID AND details2.AreaName = 'Rack'", 'left');
			$this->db->join('tbl_weekly_sanitation_details details3', "details3.SanitationID = log.SanitationID AND details3.AreaName = 'Walls'", 'left');
			$this->db->join('tbl_weekly_sanitation_details details4', "details4.SanitationID = log.SanitationID AND details4.AreaName = 'Entry'", 'left');
			
			$this->db->where('log.WeekStartDate >=', $from_date);
			$this->db->where('log.WeekStartDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			
			$total = $this->db->count_all_results('', FALSE);

			$this->db->select([
				'log.*',
				'godown.AccountName as WarehouseName',
				'details.Status as FloorStatus',
				'details1.Status as PalletsStatus',
				'details2.Status as RackStatus',
				'details3.Status as WallsStatus',
				'details4.Status as EntryStatus'
			]);
			
			$this->db->limit($limit, $offset);
			$this->db->order_by('log.WeekStartDate', 'DESC');
			$rows = $this->db->get()->result_array();

			return [
				'total' => $total,
				'rows'  => $rows
			];
		}

		public function getPCListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_pest_control_log log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			$this->db->join('tbl_pest_control_log_details details', "details.PestLogID = log.PestLogID AND details.TrapLocation = 'Door'", 'left');
			$this->db->join('tbl_pest_control_log_details details1', "details1.PestLogID = log.PestLogID AND details1.TrapLocation = 'Corners'", 'left');
			$this->db->join('tbl_pest_control_log_details details2', "details2.PestLogID = log.PestLogID AND details2.TrapLocation = 'Area'", 'left');
			
			$this->db->where('log.TransDate >=', $from_date);
			$this->db->where('log.TransDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			
			$total = $this->db->count_all_results('', FALSE);

			$this->db->select([
				'log.*',
				'godown.AccountName as WarehouseName',
				'details.TrapCondition as DoorCondition',
				'details1.TrapCondition as CornersCondition',
				'details2.TrapCondition as AreaCondition'
			]);
			
			$this->db->limit($limit, $offset);
			$this->db->order_by('log.TransDate', 'DESC');
			$rows = $this->db->get()->result_array();

			return [
				'total' => $total,
				'rows'  => $rows
			];
		}

		public function getNCAListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_non_conformance_log log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			
			$this->db->where('log.TransDate >=', $from_date);
			$this->db->where('log.TransDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			
			$total = $this->db->count_all_results('', FALSE);

			$this->db->select([
				'log.*',
				'godown.AccountName as WarehouseName'
			]);
			
			$this->db->limit($limit, $offset);
			$this->db->order_by('log.TransDate', 'DESC');
			$rows = $this->db->get()->result_array();

			return [
				'total' => $total,
				'rows'  => $rows
			];
		}

		public function getMICListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_monthly_inspection log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			
			$this->db->where('log.TransDate >=', $from_date);
			$this->db->where('log.TransDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			
			$total = $this->db->count_all_results('', FALSE);

			$this->db->select([
				'log.*',
				'godown.AccountName as WarehouseName'
			]);
			
			$this->db->limit($limit, $offset);
			$this->db->order_by('log.TransDate', 'DESC');
			$rows = $this->db->get()->result_array();

			return [
				'total' => $total,
				'rows'  => $rows
			];
		}

		public function getRMDListByFilter($data, $limit, $offset)
		{
			$from_date    = $data['fromDate'] ?? date('Y-m-01');
			$to_date      = $data['toDate'] ?? date('Y-m-d');
			$WarehouseID  = $data['WarehouseID'] ?? '';
			$ProductID  	= $data['ProductID'] ?? '';
			$offset = ($offset == 0) ? 0 : ($offset * $limit);

			$this->db->from('tbl_recall_mock_drill log');
			$this->db->join('tblgodownmaster godown', 'godown.id = log.WarehouseID', 'left');
			$this->db->join('tblitems item', 'item.id = log.ProductID', 'left');
			
			$this->db->where('log.TransDate >=', $from_date);
			$this->db->where('log.TransDate <=', $to_date);
			if($WarehouseID != '') $this->db->where('log.WarehouseID', $WarehouseID);
			if($ProductID != '') $this->db->where('log.ProductID', $ProductID);
			
			$total = $this->db->count_all_results('', FALSE);

			$this->db->select([
				'log.*',
				'godown.AccountName as WarehouseName',
				'item.description as ProductName'
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
