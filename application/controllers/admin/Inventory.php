<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Inventory extends AdminController
	{
		private $not_importable_fields = ['id'];
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('inventory_model');
		}
		
		/* List all available items */
		public function index()
		{
			if (!has_permission_new('InventoryDashboard', '', 'view')) {
				access_denied('orders');
			}
			$data['title'] = "Inventory Dashboard";
			$data['stock_val_FG'] = $this->inventory_model->get_stock_data('1');
			$data['stock_val_RM'] = $this->inventory_model->get_stock_data('2');
			// print_r($data['stock_val_RM']);die;
			$data['ItemCountFG'] = $this->inventory_model->ItemCountFG();
			$data['ItemCountRM'] = $this->inventory_model->ItemCountRM();
			$this->load->view('admin/inventory/dashboard', $data);
		}
		
	}
