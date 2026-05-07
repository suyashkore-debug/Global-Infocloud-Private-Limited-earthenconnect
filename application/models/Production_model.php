<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Production_model extends App_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		//========================= Get Production Item List ===========================
		public function GetProductionItems()
		{		
			$this->db->select('tblproduction.recipeID,tblitems.description');
			$this->db->from('tblproduction');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction.recipeID');
			$this->db->group_by('tblproduction.recipeID');
			return $this->db->get()->result_array();
		}
		//===================== Get Damage Amt ItemID ==================================
		public function GetDamageDetailsByItemID($data)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];
			$TType = array("R","D");
			$this->db->select('SUM(tblhistory.BilledQty) AS TotalDmgQty,SUM(tblhistory.NetChallanAmt) AS TotalDmgAmt');
			$this->db->from('tblhistory');
			$this->db->where_in('tblhistory.ItemID', $data['product_name']);
			$this->db->where('tblhistory.PlantID', $selected_company);
			$this->db->where_in('tblhistory.FY', $fy);
			$this->db->where_in('tblhistory.TType', $TType);
			$this->db->where('tblhistory.TType2', 'Damage');
			$this->db->where(db_prefix().'history.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			$DmgDetails =  $this->db->get()->row();
			return $DmgDetails;
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
		
		public function GetStateList()
		{
			$this->db->select('tblxx_statelist.*');
			$this->db->where('country_id', 1);
			$this->db->order_by('tblxx_statelist.state_name', 'ASC');
			return $this->db->get('tblxx_statelist')->result_array();
		}
		//===================== Get Item Group List By Main GroupID ====================
		public function GetItemGroupList($ItemMainGrpID = "")
		{
			$this->db->select('tblitems_sub_groups.*');
			$this->db->where('main_group_id', $ItemMainGrpID);
			$this->db->order_by('tblitems_sub_groups.name', 'ASC');
			return $this->db->get('tblitems_sub_groups')->result_array();
		}
		//=========== Get Conversion & Over Head Cost ==================================
		public function GetConversionAndOverHeadCost($data,$dataFetchGroup)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];
			/* Data fetch for below group
				1000207 => PRODUCTION LABOUR
				1000208 =>PACKAGING LABOUR
				1000209 => FIXED LABOUR
				1000210 = >ADMIN STAFF
			1000211 => SALES/MARKETING*/
			$this->db->select('tblclients.SubActGroupID,SUM(tblaccountledger.Amount) AS TotalAmt');
			$this->db->from('tblaccountledger');
			$this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = '.db_prefix() . 'accountledger.AccountID AND '.db_prefix() . 'clients.PlantID = '.db_prefix() . 'accountledger.PlantID');
			$this->db->where('tblaccountledger.PlantID', $selected_company);
			$this->db->where_in('tblaccountledger.FY', $fy);
			$this->db->where_in('tblclients.SubActGroupID', $dataFetchGroup);
			$this->db->where('tblaccountledger.TType', 'C');
			$this->db->where(db_prefix().'accountledger.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			$this->db->group_by('tblclients.SubActGroupID');
			$DmgDetails =  $this->db->get()->result_array();
			return $DmgDetails;
		}
		
		//===================== Get RM Item List Against FG ItemID =====================
		public function GetRMItemListByFGItemID($data)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];
			
			$this->db->select('tblproduction.pro_order_id,tblproduction.batch_qty,tblproduction.Finish_good_qty_new,tblproduction.Finish_good_qty');
			$this->db->from('tblproduction');
			
			if($data['product_name'] !=""){
				$this->db->where('tblproduction.recipeID', $data['product_name']);
			}
			$status = array("Completed","In-Progress");
			$this->db->where_in('production_status',$status);
			$this->db->where(db_prefix().'production.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			$result =  $this->db->get()->result_array();
			
			// Recipe Details
			$this->db->select('tblrecipe.*,tblitems.mrp,tblitems_sub_group2.name AS VerientName');
			$this->db->from('tblrecipe');
			$this->db->join('tblitems', 'tblitems.item_code = tblrecipe.item_code AND tblitems.PlantID = tblrecipe.PlantID');
			$this->db->join('tblitems_sub_group2', 'tblitems_sub_group2.id = tblitems.SubGrpID2');
			if($data['product_name'] !=""){
				$this->db->where('tblrecipe.item_code', $data['product_name']);
			}
			$RecipeDetails =  $this->db->get()->row();
			
			
			$pro_order_id = array();
			$TotalBatchQty = 0;
			$TotalFGQty = 0;
			$TotalStdFGQty = 0;
			foreach($result as $value){
				if($value["pro_order_id"] != ''){
					$TotalBatchQty += $value["batch_qty"];
					$TotalFGQty += $value["Finish_good_qty_new"];
					$TotalStdFGQty += $value["Finish_good_qty"];
					array_push($pro_order_id, "'".$value["pro_order_id"]."'");
				}
			}
			$RecipeDetails->TotalBatchQty = $TotalBatchQty;
			$RecipeDetails->TotalFGQty = $TotalFGQty;
			$RecipeDetails->TotalStdFGQty = $TotalStdFGQty;
			$pro_order_id_data = implode(", ", $pro_order_id);
			if($pro_order_id_data){
				// Get RM Item List
				$this->db->select('tblitems.mrp,tblproduction_details.item_name,tblproduction_details.item_id,SUM(tblproduction_details.production_req_qty) as production_req_qty,SUM(tblproduction_details.return_req_qty) as return_req_qty,SUM(tblproduction_details.ExtraQty) as ExtraQty,'.db_prefix() .'taxes.taxrate');
				$this->db->from('tblproduction_details');
				$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production_details.PlantID');
				$this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = '.db_prefix() . 'items.tax ');
				$this->db->where_in('tblproduction_details.production_id', $pro_order_id_data,FALSE);
				$this->db->where('tblitems.MainGrpID', 2);// Only RM Items
				$this->db->where('tblproduction_details.FY', $fy);
				$this->db->where('tblproduction_details.PlantID', $selected_company);
				$this->db->group_by('tblitems.item_code');
				$this->db->order_by('tblitems.MainGrpID',"ASC");
				$RawItemsList =  $this->db->get()->result_array();
				
				// Get Packing Materials Item List
				$this->db->select('tblproduction_details.item_name,tblproduction_details.item_id,SUM(tblproduction_details.production_req_qty) as production_req_qty,SUM(tblproduction_details.return_req_qty) as return_req_qty,SUM(tblproduction_details.ExtraQty) as ExtraQty,'.db_prefix() .'taxes.taxrate,tblitems.SubGrpID1');
				$this->db->from('tblproduction_details');
				$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production_details.PlantID');
				$this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = '.db_prefix() . 'items.tax ');
				$this->db->where_in('tblproduction_details.production_id', $pro_order_id_data,FALSE);
				$this->db->where('tblitems.MainGrpID', 3);// Only RM Items
				$this->db->where('tblproduction_details.FY', $fy);
				$this->db->where('tblproduction_details.PlantID', $selected_company);
				$this->db->group_by('tblitems.item_code');
				$this->db->order_by('tblitems.MainGrpID',"ASC");
				$PackingItemsList =  $this->db->get()->result_array();
				$ItemList = array();
				foreach($RawItemsList as $key=>$val){
					array_push($ItemList,$val['item_id']);
				}
				foreach($PackingItemsList as $Pkey=>$Pval){
					array_push($ItemList,$Pval['item_id']);
				}
				
				$last_fy = $fy-1; 
				$from_date = '20'.$last_fy.'-04-01';
				$this->db->select('tblhistory.SaleRate,tblhistory.ItemID');
				$this->db->from('tblhistory');
				$this->db->where_in('tblhistory.ItemID', $ItemList);
				$this->db->where('tblhistory.PlantID', $selected_company);
				$this->db->where('tblhistory.TType', 'P');
				$this->db->where('tblhistory.TType2', 'Purchase');
				$this->db->where(db_prefix().'history.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
				$this->db->order_by('TransDate','desc');
				//$this->db->limit(1);
				$SaleRateList =  $this->db->get()->result_array();
				// echo "<pre>";print_r($SaleRateList);die;
				foreach($RawItemsList as $keys=>$val){
					$RawItemsList[$keys]['BasicRate'] = '';
					foreach($SaleRateList as $key=>$value){
						if($val["item_id"]==$value["ItemID"]){
							$RawItemsList[$keys]['BasicRate'] = $value['SaleRate'];
							break;
						}
					}
				}
				
				foreach($PackingItemsList as $Pkeys=>$Pval){
					$PackingItemsList[$Pkeys]['BasicRate'] = '';
					foreach($SaleRateList as $key=>$value){
						if($Pval["item_id"]==$value["ItemID"]){
							$PackingItemsList[$Pkeys]['BasicRate'] = $value['SaleRate'];
							break;
						}
					}
				}
			}
			$response->RMItemList = $RawItemsList;
			$response->PMItemList = $PackingItemsList;
			$response->RecipeDetails = $RecipeDetails;
			return $response;
		}
		
		function getitem_using_itemcode($postData){
			
			$response = array();
			if(isset($postData['search']) ){
				
				$q = $postData['search'];
				$this->db->select(db_prefix() . 'items.*');
				/*$where_items .= '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q. '%" ESCAPE \'!\') AND ' . 
				db_prefix() . 'items.subgroup_id IN(16,17,19,22,23,24,26,27,30,32,33,35,39,40,41,43,44,45)';*/
				$where_items .= '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q. '%" ESCAPE \'!\')';
				
				$selected_company = $this->session->userdata('root_company');
				$this->db->where($where_items);
				$this->db->where(db_prefix() . 'items.isactive', "Y");
				//$this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.SubGrpID2');
				//$this->db->join('tblitems_main_groups', 'tblitems_main_groups.id = tblitems_sub_groups.main_group_id');
				$this->db->where(db_prefix() . 'items.MainGrpID', '1');
				$this->db->where(db_prefix() . 'items.PlantID', $selected_company);
				$records = $this->db->get(db_prefix() . 'items')->result();
				
				foreach($records as $row ){
					$response[] = array("units"=>$row->unit,"value"=>$row->item_code,"label"=>$row->description);
				}
			}
			
			return $response;
		}
		
		function itemDetails_by_itemcode($postData){
			if(isset($postData['search']) ){
				
				$q = $postData['search'];
				$this->db->select(db_prefix() . 'items.*');
				$selected_company = $this->session->userdata('root_company');
				$this->db->where("item_code",$q);
				$this->db->where(db_prefix() . 'items.isactive', "Y");
				//$this->db->join('tblitems_sub_groups', 'tblitems_sub_groups.id = tblitems.SubGrpID2');
				//$this->db->join('tblitems_main_groups', 'tblitems_main_groups.id = tblitems_sub_groups.main_group_id');
				//$this->db->where(db_prefix() . 'items_main_groups.id', '2');
				if(!empty($postData['maingroup'])){
					$this->db->where('tblitems.MainGrpID', $postData['maingroup']);
				}
				$this->db->where(db_prefix() . 'items.PlantID', $selected_company);
				$records = $this->db->get(db_prefix() . 'items')->row();
				return $records;
			}
			return false;
		}
		// Get Item List For Recipe edit add
		
		function ItemListReceipe($postData){
			$response = array();
			$MainGroup = array('2','3');
			
			$selected_company = $this->session->userdata('root_company');
			if(isset($postData['search'])){
				// Select record
				$q = $postData['search'];
				$where_items = '(item_code LIKE "%' . $q . '%" ESCAPE \'!\' OR description LIKE "%' . $q. '%")';
				$this->db->select('*');
				$this->db->where($where_items);
				$this->db->where('tblitems.PlantID', $selected_company);
				if(!empty($postData['maingroup'])){
					$this->db->where('tblitems.MainGrpID', $postData['maingroup']);
				}
				$records = $this->db->get(db_prefix() . 'items')->result();
				foreach($records as $row ){
					$response[] = array("value"=>$row->item_code,"label"=>$row->description,"unit"=>$row->unit,"MainGrpID"=>$row->MainGrpID);
				}
			}
			return $response;
		}
		
		function getitem_subgroup($postData,$ProdId){
			$response = array();
			$selected_company = $this->session->userdata('root_company');
			if(isset($postData['search']) ){
				// Select record
				$q = $postData['search'];
				$where_items = '(item_name LIKE "%' . $q . '%" ESCAPE \'!\' OR item_id LIKE "%' . $q. '%")';
				$this->db->select('tblproduction_details.*');
				$this->db->from(db_prefix() . 'production_details');
				$this->db->join('tblitems', 'tblitems.item_code = tblproduction_details.item_id');
				if(!empty($postData['maingroup'])){
					$this->db->where('tblitems.MainGrpID', $postData['maingroup']);
				}
				$this->db->where($where_items);
				$this->db->where('production_id', $ProdId);
				$records = $this->db->get()->result();
				foreach($records as $row ){
					$response[] = array("value"=>$row->item_id,"label"=>$row->item_name,"unit"=>$row->unit);
				}
			}
			return $response;
		}
		
		function getitem_subgroup_baking($postData,$ProdId){
			$response = array();
			$selected_company = $this->session->userdata('root_company');
			if(isset($postData['search']) ){
				// Select record
				$q = $postData['search'];
				$recipeID = $postData['recipeID'];
				$where_items = '(tblitems.description LIKE "%' . $q . '%" ESCAPE \'!\' OR tblitems.item_code LIKE "%' . $q. '%")';
				$this->db->select('tblitems.*');
				$this->db->from(db_prefix() . 'production');
				$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID','INNER');
				$this->db->where($where_items);
				$this->db->where('tblproduction.recipeID', $recipeID);
				$this->db->where('tblproduction.pro_order_id', $ProdId);
				$row = $this->db->get()->row();
				// foreach($records as $row ){
				$response[] = array("value"=>$row->item_code,"label"=>$row->description,"unit"=>$row->unit);
				// }
			}
			return $response;
		}
		
		
		
		function getitem_subgroup1($postData,$ProdId)
		{
			$response = array();
			if(isset($postData['search']) ){
				// Select record
				$where_items = '(item_name LIKE "%' . $q . '%" ESCAPE \'!\' OR item_id LIKE "%' . $q. '%")';
				$q = $postData['search'];
				$this->db->select('tblproduction_details.*');
				$this->db->from(db_prefix() . 'production_details');
				$this->db->join('tblitems', 'tblitems.item_code = tblproduction_details.item_id');
				if(!empty($postData['maingroup'])){
					$this->db->where('tblitems.MainGrpID', $postData['maingroup']);
				}
				$this->db->where($where_items);
				$this->db->where('production_id', $ProdId);
				$records = $this->db->get()->result();
				//$aa = $this->db->last_query(); print_r($aa); exit(); 
				foreach($records as $row ){
					$response[] = array("value"=>$row->item_id,"label"=>$row->item_name,"req_qty"=>$row->req_qty,"pro_req_qty"=>$row->production_req_qty,"unit"=>$row->unit);
				} 
			} 
			return $response;
		}
		
		function get_item_details($ProdId,$ItemID){
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$this->db->select('*');
			$this->db->where('PlantID', $selected_company);
			$this->db->LIKE('FY', $FY);
			$this->db->where('production_id', $ProdId);
			$this->db->where('item_id', $ItemID);
			$records = $this->db->get(db_prefix() . 'production_details')->row();
			if($records){
				$Stocks = $this->GetItemStockDetails($ItemID); 
				$PQty = 0;
				$PRQty = 0;
				$IQty = 0;
				$PRDQty = 0;
				$SQty = 0;
				$SRTQty = 0;
				$AQty = 0;
				$GIQty = 0;
				$GOQty = 0;
				
				foreach ($Stocks as $stock) {
					$OQty = $stock['OQty'];
					if($stock['TType'] == 'P'){
						$PQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'N'){
						$PRQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'A'){
						$IQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'B'){
						$PRDQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
						$SQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
						$SRTQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free distribution'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
						$GIQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
						$GOQty = $stock['BilledQty'];
					}
				}
				$stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
				$records->ItemStocks = $stockQty;
			}
			return $records;
		}
		function get_item_details_baking($ProdId,$ItemID){
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			// $this->db->select('*');
			// $this->db->where('PlantID', $selected_company);
			// $this->db->LIKE('FY', $FY);
			// $this->db->where('production_id', $ProdId);
			// $this->db->where('item_id', $ItemID);
			// $records = $this->db->get(db_prefix() . 'production_details')->row();
			$this->db->select('tblitems.*');
			$this->db->from(db_prefix() . 'production');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID','INNER');
			$this->db->where('tblproduction.recipeID', $ItemID);
			$this->db->where('tblproduction.pro_order_id', $ProdId);
			$records = $this->db->get()->row();
			
			if($records){
				$Stocks = $this->GetItemStockDetails($ItemID); 
				$PQty = 0;
				$PRQty = 0;
				$IQty = 0;
				$PRDQty = 0;
				$SQty = 0;
				$SRTQty = 0;
				$AQty = 0;
				$GIQty = 0;
				$GOQty = 0;
				
				foreach ($Stocks as $stock) {
					$OQty = $stock['OQty'];
					if($stock['TType'] == 'P' && $stock['TType2'] == 'Purchase'){
						$PQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'N'){
						$PRQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'A'){
						$IQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'B'){
						$PRDQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
						$SQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
						$SRTQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free distribution'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
						$AQty += $stock['BilledQty'];
						}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
						$GIQty = $stock['BilledQty'];
						}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
						$GOQty = $stock['BilledQty'];
					}
				}
				$stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
				$records->ItemStocks = $stockQty;
			}
			return $records;
		}
		
		function get_recipe_details($recipeID){
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select('*');
			$this->db->where('PlantID', $selected_company);
			//$this->db->LIKE('FY', $FY);
			$this->db->where('item_code', $recipeID);
			$this->db->where('status', 'Y');
			$records = $this->db->get(db_prefix() . 'recipe')->row();
			
			return $records;
		}
		function get_recipename($postData){
			$response = array();
			if(isset($postData['search']) ){
				
				$q = $postData['search'];
				$this->db->select(db_prefix() . 'recipe.*');
				$where_items .= '(item_description LIKE "%' . $q . '%" ESCAPE \'!\' OR item_code LIKE "%' . $q. '%")';
				$selected_company = $this->session->userdata('root_company');
				$this->db->where($where_items);
				$this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
				$this->db->where(db_prefix() . 'recipe.status', 'Y');
				$records = $this->db->get(db_prefix() . 'recipe')->result();
				// $aa = $this->db->last_query(); print_r($aa); exit(); 
				
				foreach($records as $row ){
					// print_r($row); exit();
					$response[] = array("value"=>$row->item_code,"quantity"=>$row->qty,"units"=>$row->unit,"env_temp"=>$row->env_temp,"env_humidity"=>$row->env_humidity,"water_temp"=>$row->water_temp,"label"=>$row->item_description);
				}
			}
			return $response;
		}
		
		public function load_data_for_production($data)
		{  
			$from_date = to_sql_date($data["from_date"]);
			$to_date = to_sql_date($data["to_date"]);
			$status_list = $data["status_list"];
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			if($status_list == "all"){
				
				}else{
				$sql1 .= ' AND '.db_prefix().'production.production_status = "'.$status_list.'"';
			}
			
			$sql1 .= '  ORDER BY Transdate ASC';
			
			$sql ='SELECT '.db_prefix().'production.*,tblrecipe.Is_Baking,  tblitems.description,
			(SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'production.contractor_name AND '.db_prefix().'clients.PlantID = '.$selected_company.') as conName, 
			(SELECT COALESCE(SUM(Qty),"") FROM '.db_prefix().'production_stage WHERE '.db_prefix().'production_stage.ProductionID = '.db_prefix().'production.pro_order_id AND '.db_prefix().'production_stage.ItemID = '.db_prefix().'production.recipeID AND '.db_prefix().'production_stage.Stage = "Baking" ) as BakingQty,
			(SELECT COALESCE(SUM(Qty),"") FROM '.db_prefix().'production_stage WHERE '.db_prefix().'production_stage.ProductionID = '.db_prefix().'production.pro_order_id AND '.db_prefix().'production_stage.ItemID = '.db_prefix().'production.recipeID AND '.db_prefix().'production_stage.Stage = "Packing" ) as PackingQty,
			(SELECT GROUP_CONCAT(firstname SEPARATOR ",") FROM '.db_prefix().'staff WHERE '.db_prefix().'staff.AccountID = '.db_prefix().'production.manager_name ) as firstname,
			(SELECT GROUP_CONCAT(lastname SEPARATOR ",") FROM '.db_prefix().'staff WHERE '.db_prefix().'staff.AccountID = '.db_prefix().'production.manager_name ) as lastname
			FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		
		
		public function get_managername($id = '')
		{
			$selected_company = $this->session->userdata('root_company');
			$subgroup = array('1000190');
			$regExp ='.*;s:[0-9]+:"'.$selected_company.'".*';
			// $this->db->where('SubActGroupID', '10022004');
			$this->db->where_in('SubActGroupID', $subgroup);
			$this->db->where('PlantID', $selected_company);
			//$this->db->where('tblstaff.staff_comp REGEXP',$regExp);
			$this->db->order_by('firstname', 'ASC');
			$accounts = $this->db->get(db_prefix() . 'staff')->result_array();
			return $accounts;
		}
		
		public function get_contractorname($id = '')
		{
			$selected_company = $this->session->userdata('root_company');
			$subgroup = array('1000191');
			$this->db->where_in('SubActGroupID', $subgroup);
			$this->db->where('PlantID', $selected_company);
			$this->db->order_by('company', 'ASC');
			$accounts = $this->db->get(db_prefix() . 'clients')->result_array();
			return $accounts;
		}
		
		public function GetLastPrdDate()
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			if($selected_company == "1"){
				$this->db->select('*');
				$this->db->where(db_prefix() . 'production.PlantID', $selected_company);
				$this->db->where(db_prefix() . 'production.FY', $fy);
				$this->db->from(db_prefix() . 'production');
				$this->db->order_by(db_prefix() . 'production.TransDate', 'DESC');
				$PRDOrder = $this->db->get()->row();
				$LastPRDORDDate = substr($PRDOrder->TransDate,0,10);
				}else{
				$LastPRDORDDate = date('Y-m-d');
			}
			return $LastPRDORDDate;
		}
		
		public function GetGodownData()
		{
			$PlantID = $this->session->userdata('root_company');
			$this->db->where('PlantID', $PlantID);
			$this->db->order_by(db_prefix() . 'godownmaster.Type,'.db_prefix() . 'godownmaster.AccountName', 'ASC');
			return $this->db->get(db_prefix().'godownmaster')->result_array();
		}
		
		public function getbyitemname($recipeID,$GodownID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			/*if($selected_company == "1"){
				$GodownID = 'CSPL';
				}else if($selected_company == "2"){
				$GodownID = 'CFF';
				}else if($selected_company == "3"){
				$GodownID = 'CBUPL';
			}*/
			
			$this->db->select(db_prefix() . 'recipe.*,'.db_prefix() . 'recipe_details.*,'.db_prefix() . 'stockmaster.OQty,tblitems.MainGrpID');
			$this->db->from(db_prefix() . 'recipe');
			$this->db->join(db_prefix() . 'recipe_details', db_prefix() . 'recipe.id = '.db_prefix() . 'recipe_details.rec_id');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'recipe_details.item_id');
			$this->db->join(db_prefix() . 'stockmaster', db_prefix() . 'stockmaster.ItemID = '.db_prefix() . 'recipe_details.item_id AND '.db_prefix() . 'stockmaster.PlantID = "'.$selected_company.'" AND '.db_prefix() . 'stockmaster.FY = "'.$fy.'" AND '.db_prefix() . 'stockmaster.GodownID = "'.$GodownID.'"','LEFT');
			$this->db->where(db_prefix() . 'recipe.item_code', $recipeID);
			$this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'recipe_details.PlantID', $selected_company);
			//$this->db->where(db_prefix() . 'stockmaster.GodownID',$GodownID);
			$this->db->where(db_prefix() . 'recipe.status', 'Y');
			return $this->db->get()->result_array();
		}  
		
		public function GetItemStock($GodownID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			
			$this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
			$this->db->from(db_prefix() .'history');
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
			$this->db->where(db_prefix() .'history.FY', $fy);
			$this->db->where(db_prefix() .'history.GodownID', $GodownID);
			$this->db->group_by('ItemID,TType,TType2');
			return $this->db->get()->result_array();
		}
		
		public function GetPRDItemStock($pro_orid,$GodownID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select(db_prefix() .'history.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty,'.db_prefix() .'stockmaster.OQty');
			$this->db->from(db_prefix() .'history');
			$this->db->join('tblstockmaster', 'tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY AND tblstockmaster.GodownID = "'.$GodownID.'"','LEFT');
			$this->db->join('tblproduction_details', 'tblproduction_details.item_id = tblhistory.ItemID AND tblproduction_details.PlantID = tblhistory.PlantID AND tblproduction_details.FY = tblhistory.FY','LEFT');
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() .'production_details.production_id', $pro_orid);
			$this->db->where(db_prefix() .'history.GodownID', $GodownID);
			$this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
			$this->db->where(db_prefix() .'history.FY', $fy);
			$this->db->group_by(db_prefix() .'history.ItemID,TType,TType2');
			$result = $this->db->get()->result_array();
            return $result;
		}
		
		public function GetPRDItemStockNew($PrdItem,$GodownID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select(db_prefix() .'history.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
			$this->db->from(db_prefix() .'history');
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
			$this->db->where(db_prefix() .'history.FY', $fy);
			$this->db->where(db_prefix() .'history.GodownID', $GodownID);
			$this->db->where_in(db_prefix() .'history.ItemID', $PrdItem);
			$this->db->group_by(db_prefix() .'history.ItemID,TType,TType2');
			$result = $this->db->get()->result_array();
			return $result;
		}
		
		public function GetPRDItemOQty($pro_orid,$GodownID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			/*if($selected_company == "1"){
				$GodownID = 'CSPL';
				}else if($selected_company == "2"){
				$GodownID = 'CFF';
				}else if($selected_company == "3"){
				$GodownID = 'CBUPL';
			}*/
			$this->db->select(db_prefix() .'production_details.item_id');
			$this->db->from(db_prefix() .'production_details');
			$this->db->where(db_prefix() .'production_details.PlantID', $selected_company);
			$this->db->where(db_prefix() .'production_details.production_id', $pro_orid);
			$this->db->where(db_prefix() .'production_details.FY', $fy);
			$ItemList = $this->db->get()->result_array();
			$ItemIDs = array();
			foreach ($ItemList as $ItemID) {
				array_push($ItemIDs,$ItemID['item_id']);
			}
			$this->db->select(db_prefix() .'stockmaster.ItemID,'.db_prefix() .'stockmaster.OQty');
			$this->db->from(db_prefix() .'stockmaster');
			$this->db->where(db_prefix() .'stockmaster.PlantID', $selected_company);
			$this->db->where_in(db_prefix() .'stockmaster.ItemID', $ItemIDs);
			$this->db->where(db_prefix() .'stockmaster.FY', $fy);
			$this->db->where(db_prefix() .'stockmaster.GodownID',$GodownID);
			$this->db->where(db_prefix() .'stockmaster.cnfid', 1);
			return $this->db->get()->result_array();
		}
		
		public function GetItemStockDetails($ItemID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			if($selected_company == "1"){
				$GodownID = 'CSPL';
				}else if($selected_company == "2"){
				$GodownID = 'CFF';
				}else if($selected_company == "3"){
				$GodownID = 'CBUPL';
			}
			
			$this->db->select('tblhistory.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty,tblstockmaster.OQty');
			$this->db->from(db_prefix() .'history');
			$this->db->join('tblstockmaster', 'tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY ','LEFT');
			$this->db->where(db_prefix() .'history.ItemID', $ItemID);
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
			$this->db->where(db_prefix() .'history.FY', $fy);
			$this->db->where(db_prefix() .'history.GodownID',$GodownID);
			$this->db->where(db_prefix() .'stockmaster.GodownID',$GodownID);
			$this->db->group_by('ItemID,TType,TType2');
			return $this->db->get()->result_array();
		}
		
		public function getReceipeDetailswithPODetails($recipeID,$PONumber)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
            
			$this->db->select('recipe_details.item_id,recipe_details.item_name,production_details.req_qty AS StdQty,production_details.return_req_qty AS RtnQty,production_details.ExtraQty AS ExtraQty,production_details.unit,tblitems.MainGrpID');
			$this->db->from('recipe');
			$this->db->join('recipe_details', 'recipe.id = recipe_details.rec_id');
			$this->db->join('production_details', 'recipe_details.item_id = production_details.item_id AND production_details.production_id = "'.$PONumber.'" AND production_details.FY="'.$fy.'" AND production_details.PlantID="'.$selected_company.'"');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'recipe_details.item_id');
			$this->db->where(db_prefix() . 'recipe.item_code', $recipeID);
			return $this->db->get()->result_array();
		} 
		
		public function get_production_order($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select(db_prefix() . 'production.*,'.db_prefix() . 'production.TransDate AS Date,'.db_prefix() . 'items.description,'.db_prefix() . 'items.case_qty');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where(db_prefix() . 'production.pro_order_id', $pro_orid);
			$this->db->where(db_prefix() . 'production.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production.FY', $fy); 
			$pro_order =  $this->db->get('production')->row();
			if($pro_order){
			    
			    $item          = $this->get_production_item($pro_order->pro_order_id);
                $pro_order->items = $item;
                $ItemRate = $this->GetItemRate($pro_order->recipeID);
		        $pro_order->ItemRate = $ItemRate;
			}
			return $pro_order;
		}
		
		public function GetPrdDetails($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select(db_prefix() . 'production.*,'.db_prefix() . 'production.TransDate AS Date,'.db_prefix() . 'items.description,'.db_prefix() . 'items.case_qty,'.db_prefix() . 'items.MainGrpID');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where(db_prefix() . 'production.pro_order_id', $pro_orid);
			$this->db->where(db_prefix() . 'production.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production.FY', $fy); 
			$pro_order =  $this->db->get('production')->row();
			if($pro_order){
			    $item          = $this->GetPrdItems($pro_order->pro_order_id);
                $pro_order->items = $item;
			}
			return $pro_order;
		}
		public function get_PRD_Details($pro_orid)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$this->db->select('*');
			$this->db->where(db_prefix() . 'history.OrderID', $pro_orid);
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'history.FY', $fy); 
			$this->db->where(db_prefix() . 'history.TType', 'B'); 
			$PRDDetails =  $this->db->get('history')->row();
			
			return $PRDDetails;
		}
		
		public function GetItemRate($ItemID)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			
			if($selected_company == "1"){
				$CustType = '1';
				}else if($selected_company == "2"){
				$CustType = '13';
				}else if($selected_company == "3"){
				$CustType = '21';
			}
			
			$this->db->select(db_prefix() . 'rate_master.assigned_rate AS BasicRate,'.db_prefix() . 'rate_master.SaleRate AS SaleRate');
			$this->db->where(db_prefix() . 'rate_master.item_id', $ItemID);
			$this->db->where(db_prefix() . 'rate_master.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'rate_master.state_id', 'UP'); 
			$this->db->where(db_prefix() . 'rate_master.distributor_id', $CustType); 
			$ItemRateDetails =  $this->db->get('tblrate_master')->row();
			if(empty($ItemRateDetails)){
				$this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate');
				$this->db->where(db_prefix() . 'history.ItemID', $ItemID);
				$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
				//$this->db->where(db_prefix() . 'history.TType', 'O'); 
				$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
				$ItemRateDetails =  $this->db->get('history')->row();
			}
			return $ItemRateDetails;
		}
		
		public function get_PRDIssue_Details($pro_orid)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$this->db->select('*');
			$this->db->where(db_prefix() . 'history.OrderID', $pro_orid);
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'history.FY', $fy); 
			$this->db->where(db_prefix() . 'history.TType', 'A'); 
			$PRDIssueDetails =  $this->db->get('history')->result_array();
			return $PRDIssueDetails;
		}
		
		public function get_receipeDetails($receipeID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
			$this->db->select('*');
			$this->db->where(db_prefix() . 'recipe.item_code', $receipeID);
			$ReceipeDetails =  $this->db->get('recipe')->row();
			return $ReceipeDetails;
		}
		
		public function get_stock_details($ItemID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
				$GodownID = 'CSPL';
				}else if($selected_company == "2"){
				$GodownID = 'CFF';
				}else if($selected_company == "3"){
				$GodownID = 'CBUPL';
			}
			
			$this->db->select('*');
			$this->db->where(db_prefix() . 'stockmaster.ItemID', $ItemID);
			$this->db->where(db_prefix() . 'stockmaster.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'stockmaster.FY', $fy); 
			$this->db->where(db_prefix() . 'stockmaster.GodownID',$GodownID);
			$stock =  $this->db->get(db_prefix() . 'stockmaster')->row();
			return $stock;
			
		}
		
		public function GetPrdItemStockList($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            if($selected_company == "1"){
                $GodownID = 'CSPL';
				}else if($selected_company == "2"){
                $GodownID = 'CFF';
				}else if($selected_company == "3"){
                $GodownID = 'CBUPL';
			}
            
			$this->db->select('*');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->join(db_prefix() . 'stockmaster', db_prefix() . 'stockmaster.ItemID = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'stockmaster.PlantID = '.db_prefix() . 'production_details.PlantID AND '.db_prefix() . 'stockmaster.FY = '.db_prefix() . 'production_details.FY AND '.db_prefix() . 'stockmaster.GodownID ="'.$GodownID.'"','LEFT');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$ItemStock =  $this->db->get()->result_array();
			$i = 0;
			foreach($ItemStock as $row ){
			    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate,'.db_prefix() . 'history.CaseQty AS CaseQty');
    			$this->db->where(db_prefix() . 'history.ItemID', $row['item_id']);
    			$this->db->where(db_prefix() . 'history.TType', 'P');
    			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    			$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
    			$ItemStock[$i]['BasicRate'] = $PrdItemDetails->BasicRate;
    			$ItemStock[$i]['SaleRate'] = $PrdItemDetails->SaleRate;
    			$ItemStock[$i]['CaseQty'] = $PrdItemDetails->CaseQty;
    			$i++;
			}
			return $ItemStock;
			
		}
		
		public function GetRowMaterialOthDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'production_details.item_id');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$ItemStock =  $this->db->get()->result_array();
			$i = 0;
			foreach($ItemStock as $row ){
				
			    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate,'.db_prefix() . 'history.CaseQty AS CaseQty');
    			$this->db->where(db_prefix() . 'history.ItemID', $row['item_id']);
    			$this->db->where(db_prefix() . 'history.TType', 'P');
    			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    			$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
    			if($PrdItemDetails->BasicRate == null || $PrdItemDetails->BasicRate == ''){
					$ItemStock[$i]['BasicRate'] = 0.00;
					}else{
    			    $ItemStock[$i]['BasicRate'] = $PrdItemDetails->BasicRate;
				}
    			if($PrdItemDetails->SaleRate == null || $PrdItemDetails->SaleRate == ''){
					$ItemStock[$i]['SaleRate'] = 0.00;
					}else{
    			    $ItemStock[$i]['SaleRate'] = $PrdItemDetails->SaleRate;
				}
    			if($PrdItemDetails->CaseQty == null || $PrdItemDetails->CaseQty == ''){
					$ItemStock[$i]['CaseQty'] = 1;
					}else{
    			    $ItemStock[$i]['CaseQty'] = $PrdItemDetails->CaseQty;
				}
    			
    			$i++;
			}
			return $ItemStock;
			
		}
		
		
		
		public function GetStockDetailsForRM($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select('*');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->join(db_prefix() . 'stockmaster', db_prefix() . 'stockmaster.ItemID = '.db_prefix() . 'production_details.item_id AND '.db_prefix() . 'stockmaster.PlantID = '.db_prefix() . 'production_details.PlantID AND '.db_prefix() . 'stockmaster.FY = '.db_prefix() . 'production_details.FY AND '.db_prefix() . 'stockmaster.GodownID = '.db_prefix() . 'production_details.GodownID','LEFT');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$ItemStock =  $this->db->get()->result_array();
			return $ItemStock;
		}
		
		public function GetRowMaterialDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select('tblproduction_details.*,tblitems.MainGrpID');
			$this->db->from(db_prefix() . 'production_details');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production_details.item_id');
			$this->db->where(db_prefix() . 'production_details.production_id', $proId);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production_details.FY', $fy); 
			$RMDetails =  $this->db->get()->result_array();
			return $RMDetails;
		}
		public function GetPrdFGDetails($proId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'production.*,'.db_prefix() . 'items.case_qty');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where(db_prefix() . 'production.pro_order_id', $proId);
			$this->db->where(db_prefix() . 'production.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'production.FY', $fy); 
			$PrdDetails =  $this->db->get(db_prefix() . 'production')->row();
			if($PrdDetails){
			    $ItemRate = $this->GetItemRate($PrdDetails->recipeID);
		        $PrdDetails->ItemRate = $ItemRate;
			}
			return $PrdDetails;
			
		}
		public function GetPrdFGrecord($proId,$itemId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'history.*');
			$this->db->where(db_prefix() . 'history.OrderID', $proId);
			$this->db->where(db_prefix() . 'history.ItemID', $itemId);
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'history.FY', $fy); 
			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
			return $PrdItemDetails;
		}
		public function GetPrdFGrecordSum($proId,$itemId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'history.*,SUM(BilledQty) As TotalProduction');
			$this->db->where(db_prefix() . 'history.OrderID', $proId);
			$this->db->where(db_prefix() . 'history.ItemID', $itemId);
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
			$this->db->where(db_prefix() . 'history.FY', $fy); 
			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
			return $PrdItemDetails;
		}
		public function GetPrdPackingRecordSum($proId,$itemId)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select(db_prefix() . 'production_stage.*,SUM(Qty) As TotalPacking');
			$this->db->where('ItemID', $itemId);
			$this->db->where('ProductionID', $proId);
			$this->db->where('Stage', 'Packing'); 
			$data =  $this->db->get(db_prefix() . 'production_stage')->row();
			return $data;
		}
		
		public function get_prd_item_rtnQty($PrdID, $ItemID)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
			$this->db->select('*');
			$this->db->where(db_prefix() . 'production_details.item_id', $ItemID);
			$this->db->LIKE(db_prefix() . 'production_details.production_id', $PrdID);
			$this->db->where(db_prefix() . 'production_details.PlantID', $selected_company); 
			$this->db->LIKE(db_prefix() . 'production_details.FY', $fy); 
			$PrdItemDetails =  $this->db->get(db_prefix() . 'production_details')->row();
			
			return $PrdItemDetails;
			
		}
		
		public function GetPrdItems($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('tblproduction_details.*,tblitems.MainGrpID');
			$this->db->from('tblproduction_details');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production_details.item_id');
			$this->db->where(db_prefix() . 'production_details.production_id', $pro_orid);
			$PRDItems = $this->db->get()->result_array();
			return $PRDItems;
		}
		
		public function get_production_item($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('*');
			$this->db->from('production_details');
			$this->db->where(db_prefix() . 'production_details.production_id', $pro_orid);
			//return $this->db->get()->result_array();
			$PRDItems = $this->db->get()->result_array();
			$i = 0;
			foreach($PRDItems as $row ){
			    $this->db->select(db_prefix() . 'history.BasicRate AS BasicRate,'.db_prefix() . 'history.SaleRate AS SaleRate,'.db_prefix() . 'history.CaseQty AS CaseQty');
    			$this->db->where(db_prefix() . 'history.ItemID', $row['item_id']);
    			$this->db->where(db_prefix() . 'history.TType', 'P');
    			$this->db->where(db_prefix() . 'history.PlantID', $selected_company); 
    			$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC'); 
    			$PrdItemDetails =  $this->db->get(db_prefix() . 'history')->row();
    			$PRDItems[$i]['BasicRate'] = $PrdItemDetails->BasicRate;
    			$PRDItems[$i]['SaleRate'] = $PrdItemDetails->SaleRate;
    			$PRDItems[$i]['CaseQty'] = $PrdItemDetails->CaseQty;
    			$i++;
			}
			return $PRDItems;
		}
		
		public function get_PRD_DetailsFromHistory($pro_orid)
		{
		    $selected_company = $this->session->userdata('root_company');
            $fy = $this->session->userdata('finacial_year');
            
            $this->db->select('*');
			$this->db->from(db_prefix() . 'history');
			$this->db->where(db_prefix() . 'history.OrderID', $pro_orid);
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.FY', $fy);
			return $this->db->get()->result_array();
		}
		
		public function edit_recipe($id)
		{		
			$this->db->select('*');
			$this->db->from('tblrecipe');
			//$this->db->join('recipe_details', 'recipe.id = recipe_details.rec_id');
			$this->db->where(db_prefix() . 'recipe.id', $id);
			return $this->db->get()->result_array();
			//$aa = $this->db->last_query(); print_r($aa); exit();
		}
		public function load_data_for_recipe($status)
		{  
			
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			//$sql1 = db_prefix().'recipe.FY = "'.$fy.'" AND '.db_prefix().'recipe.PlantID = "'.$selected_company.'" ORDER BY item_code ASC';
			if($status == "Y"){
				$sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" AND '.db_prefix().'recipe.status = "Y" ORDER BY item_code ASC';
				}else if($status == "N"){
				$sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" AND '.db_prefix().'recipe.status = "N" ORDER BY item_code ASC';
				}else if($status == "YN"){
				$sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" ORDER BY item_code ASC';
				}else{
				$sql1 = db_prefix().'recipe.PlantID = "'.$selected_company.'" AND '.db_prefix().'recipe.status = "Y" ORDER BY item_code ASC';
			}
			
			$sql ='SELECT '.db_prefix().'recipe.* 
			FROM '.db_prefix().'recipe 
			INNER JOIN tblitems ON tblitems.item_code = tblrecipe.item_code
			WHERE '.$sql1;
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		
		public function edit_recipe1($id)
	    {		
			$this->db->select('tblrecipe_details.*,tblitems.MainGrpID');
			$this->db->from('tblrecipe_details');
			$this->db->join('tblitems', 'tblitems.item_code = tblrecipe_details.item_id');
			$this->db->where(db_prefix() . 'recipe_details.rec_id', $id);
			return $this->db->get()->result_array();
			//$aa = $this->db->last_query(); print_r($aa); exit();
		}
		
		
		
		public function Pro_order_list(){
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select('*');
			$this->db->from('tblproduction');
			$this->db->where('FY', $fy);
			$this->db->where('PlantID', $selected_company);
			$this->db->order_by('pro_order_id', 'DESC');
			return $this->db->get()->result_array();
		}
		
		public function PRDList(){
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select('tblproduction.*,tblitems.description');
			$this->db->from('tblproduction');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->order_by('pro_order_id', 'DESC');
			return $this->db->get()->result_array();
		}
		
		// Production Details Data By ID
		public function getPRDDetailsByID($PRDID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select(db_prefix() . 'production.*');
			$this->db->from(db_prefix() . 'production');
			/*$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);*/
			$this->db->where(db_prefix() . 'production.pro_order_id', $PRDID);
			return $this->db->get()->row();
		}
		public function Pro_order_report($data){
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
		    $this->db->select('tblproduction.*,tblitems.description');
			$this->db->from('tblproduction');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			
			$this->db->where('tblproduction.pro_order_id', $data['pro_order_id']);
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			return $this->db->get()->row_array();
		}
		public function pro_order_report_details($data){
		    $fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
		    $this->db->select('*');
			$this->db->from('tblproduction');
			
			$this->db->join('tblproduction_details', 'tblproduction.pro_order_id = tblproduction_details.production_id AND tblproduction.FY = tblproduction_details.FY AND tblproduction.PlantID = tblproduction_details.PlantID','left');
			$this->db->where('tblproduction.pro_order_id', $data['pro_order_id']);
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->order_by('tblproduction_details.item_name', 'ASC');
			return $this->db->get()->result_array();
		}
		function getproduct_list($postData){
			
			$response = array();
			$selected_company = $this->session->userdata('root_company');
			$where_items = '';
			$fy = $this->session->userdata('finacial_year');
			if(isset($postData['search']) ){
				$q = $postData['search'];
				// Select record
				$this->db->select('*');
				// $this->db->where("recipeID like '%".$postData['search']."%' ");
				$where_items .= '('.db_prefix() . 'items.description LIKE "%' . $q . '%" ESCAPE \'!\' OR recipeID LIKE "%' . $q. '%")';
				$this->db->where('tblproduction.FY', $fy);
				
				$this->db->where('tblproduction.PlantID', $selected_company);
				$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID','left');
				$this->db->where($where_items);
				$this->db->group_by('recipeID');
				$records = $this->db->get(db_prefix() . 'production')->result();
				foreach($records as $row ){
					$response[] = array("value"=>$row->recipeID,"label"=>$row->description,);
				} 
			} 
			
			return $response;
		}
		public function pro_cost_report($data){
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$from_date = to_sql_date($data['from_date']);
			$to_date = to_sql_date($data['to_date']);
		    $this->db->select('SUM(tblproduction.batch_qty) as batch_qty,SUM(tblproduction.Finish_good_qty) as Finish_good_qty, SUM(tblproduction.Finish_good_qty_new) as Finish_good_qty_new, tblproduction.recipeID,tblitems.description');
			$this->db->from('tblproduction');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = '.db_prefix() . 'production.recipeID AND '.db_prefix() . 'items.PlantID = '.db_prefix() . 'production.PlantID');
			if($data['product_name'] !=""){
			    $this->db->where('tblproduction.recipeID', $data['product_name']);
			}
			//$this->db->where('production_status','Completed');
			$status = array("Completed","In-Progress");
			$this->db->where_in('production_status',$status);
			$this->db->where(db_prefix().'production.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
			$this->db->where('tblproduction.FY', $fy);
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->group_by('recipeID');
			$result = $this->db->get()->result_array();
            foreach($result as $key=>$value){
				$this->db->select('tblhistory.SaleRate,tblhistory.BasicRate,tblhistory.cgst,tblhistory.sgst,tblhistory.igst');
				$this->db->from('tblhistory');
				
				$this->db->where('tblhistory.ItemID', $value['recipeID']);
				$this->db->where('tblhistory.FY', $fy);
				$this->db->where('tblhistory.PlantID', $selected_company);
				$this->db->where('tblhistory.TType', 'O');
				$this->db->where('tblhistory.TType2', 'Order');
				$this->db->where(db_prefix().'history.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59"');
				
				$this->db->order_by('id','desc');
				$this->db->limit(1);
				$data_row =  $this->db->get()->row_array();
				if($data_row != ''){
			    	$result[$key]['SaleRate'] = $data_row['SaleRate'];
			    	$result[$key]['BasicRate'] = $data_row['BasicRate'];
			    	if($data_row['cgst'] == null &&  $data_row['sgst']== null){
			    	    $result[$key]['gst'] = $data_row['cgst'] + $data_row['sgst'] + $data_row['igst'];
						}else{
						$result[$key]['gst'] = $data_row['cgst'] + $data_row['sgst'] + $data_row['igst'];
					}
					}else{
			    	$result[$key]['SaleRate'] = '';
			    	$result[$key]['BasicRate'] = '';
			    	$result[$key]['gst'] = '';
				}
				
				$this->db->select('tblrecipe.conv_cost,tblrecipe.st_cost,tblrecipe.frt_cost,tblrecipe.mrkt_cost,tblrecipe.dmg_cost');
				$this->db->from('tblrecipe');
				$this->db->where('tblrecipe.item_code', $value['recipeID']);
				//$this->db->where('tblrecipe.FY', $fy);
				$this->db->where('tblrecipe.PlantID', $selected_company);
				$this->db->order_by('id','desc');
				$data_row1 =  $this->db->get()->row_array();
				if($data_row1 != ''){
			    	$result[$key]['conv_cost'] = $data_row1['conv_cost'];
			    	$result[$key]['st_cost'] = $data_row1['st_cost'];
			    	$result[$key]['frt_cost'] = $data_row1['frt_cost'];
			    	$result[$key]['mrkt_cost'] = $data_row1['mrkt_cost'];
			    	$result[$key]['dmg_cost'] = $data_row1['dmg_cost'];
					}else{
			    	$result[$key]['conv_cost'] = '';
			    	$result[$key]['st_cost'] = '';
			    	$result[$key]['frt_cost'] = '';
			    	$result[$key]['mrkt_cost'] = '';
			    	$result[$key]['dmg_cost'] = '';	
				}
			}
			return $result;
			//echo $this->db->last_query();die;
			
		}
		
		
		// Production Details Data By ID
		public function GetBakingList($PRDID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select(db_prefix() . 'production_stage.*,tblitems.description,tblitems.unit');
			$this->db->from(db_prefix() . 'production_stage');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction_stage.ItemID');
			$this->db->where(db_prefix() . 'production_stage.ProductionID', $PRDID);
			$this->db->where(db_prefix() . 'production_stage.Stage', 'Baking');
			return $this->db->get()->result_array();
		}
		
		// Production Details Data By ID
		public function GetPackingList($PRDID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			
			$this->db->select(db_prefix() . 'production_stage.*,tblitems.description,tblitems.unit');
			$this->db->from(db_prefix() . 'production_stage');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction_stage.ItemID');
			$this->db->where(db_prefix() . 'production_stage.ProductionID', $PRDID);
			$this->db->where(db_prefix() . 'production_stage.Stage', 'Packing');
			return $this->db->get()->result_array();
		}
		
		public function TodaysProductionStatus($data = "")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql1 .= '  GROUP BY '.db_prefix().'production.production_status ORDER BY tblproduction.Transdate ASC';
			
			$sql ='SELECT '.db_prefix().'production.production_status,COUNT(*) as count
			FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		public function TodaysHighestYieldPacking($data = "")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql1 .= ' AND tblproduction_stage.Stage ="Packing" GROUP BY '.db_prefix().'production_stage.ProductionID ORDER BY AchievementPercentage DESC LIMIT 1';
			
			$sql ='SELECT '.db_prefix().'production_stage.ItemID,tblproduction_stage.ProductionID,tblproduction.Finish_good_qty,tblitems.description,SUM(tblproduction_stage.Qty) AS TotalQty,(SUM(tblproduction_stage.Qty) / NULLIF(tblproduction.Finish_good_qty, 0)) * 100 AS AchievementPercentage
			FROM '.db_prefix().'production_stage 
			LEFT JOIN tblproduction ON tblproduction.pro_order_id = tblproduction_stage.ProductionID
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction_stage.ItemID)
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		public function TodaysLowestYieldPacking($data = "")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql1 .= ' AND tblproduction_stage.Stage ="Packing" GROUP BY '.db_prefix().'production_stage.ProductionID ORDER BY AchievementPercentage ASC LIMIT 1';
			
			$sql ='SELECT '.db_prefix().'production_stage.ItemID,tblproduction_stage.ProductionID,tblproduction.Finish_good_qty,tblitems.description,SUM(tblproduction_stage.Qty) AS TotalQty,(SUM(tblproduction_stage.Qty) / NULLIF(tblproduction.Finish_good_qty, 0)) * 100 AS AchievementPercentage
			FROM '.db_prefix().'production_stage 
			LEFT JOIN tblproduction ON tblproduction.pro_order_id = tblproduction_stage.ProductionID
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction_stage.ItemID)
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		
		
		public function TodaysHighestProduction($data="")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql1 .= ' ORDER BY tblproduction.Finish_good_qty DESC';
			
			$sql ='SELECT '.db_prefix().'production.*,tblitems.description
			FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		
		
		public function TodaysProductionYieldStatus($data = "")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql ='SELECT SUM(tblproduction.Finish_good_qty) AS StandardQty,
			(SELECT COALESCE(SUM(Qty),0) FROM tblproduction_stage
			LEFT JOIN tblproduction as Prd ON Prd.pro_order_id = tblproduction_stage.ProductionID
			WHERE tblproduction_stage.Stage = "Baking" AND (tblproduction_stage.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")) AS BakingQty,
			(SELECT COALESCE(SUM(Qty),0) FROM tblproduction_stage
			LEFT JOIN tblproduction as Prd ON Prd.pro_order_id = tblproduction_stage.ProductionID
			WHERE tblproduction_stage.Stage = "Packing" AND (tblproduction_stage.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")) AS PackingQty
			FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		public function MonthlyProductionYieldStatus($data="")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-01');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql ='SELECT SUM(tblproduction.Finish_good_qty) AS StandardQty,
			(SELECT COALESCE(SUM(Qty),0) FROM tblproduction_stage
			LEFT JOIN tblproduction as Prd ON Prd.pro_order_id = tblproduction_stage.ProductionID
			WHERE tblproduction_stage.Stage = "Baking" AND (tblproduction_stage.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")) AS BakingQty,
			(SELECT COALESCE(SUM(Qty),0) FROM tblproduction_stage
			LEFT JOIN tblproduction as Prd ON Prd.pro_order_id = tblproduction_stage.ProductionID
			WHERE tblproduction_stage.Stage = "Packing" AND (tblproduction_stage.TransDate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")) AS PackingQty
			FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		
		public function TodaysHighestYieldBaking($data="")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql1 .= ' AND tblproduction_stage.Stage ="Baking" GROUP BY '.db_prefix().'production_stage.ProductionID ORDER BY AchievementPercentage DESC LIMIT 1';
			
			$sql ='SELECT '.db_prefix().'production_stage.ItemID,tblproduction_stage.ProductionID,tblproduction.Finish_good_qty,tblitems.description,SUM(tblproduction_stage.Qty) AS TotalQty,(SUM(tblproduction_stage.Qty) / NULLIF(tblproduction.Finish_good_qty, 0)) * 100 AS AchievementPercentage
			FROM '.db_prefix().'production_stage 
			LEFT JOIN tblproduction ON tblproduction.pro_order_id = tblproduction_stage.ProductionID
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction_stage.ItemID)
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		public function TodaysLowestYieldBaking($data = "")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			$sql1 .= ' AND tblproduction_stage.Stage ="Baking" GROUP BY '.db_prefix().'production_stage.ProductionID ORDER BY AchievementPercentage ASC LIMIT 1';
			
			$sql ='SELECT '.db_prefix().'production_stage.ItemID,tblproduction_stage.ProductionID,tblproduction.Finish_good_qty,tblitems.description,SUM(tblproduction_stage.Qty) AS TotalQty,(SUM(tblproduction_stage.Qty) / NULLIF(tblproduction.Finish_good_qty, 0)) * 100 AS AchievementPercentage
			FROM '.db_prefix().'production_stage 
			LEFT JOIN tblproduction ON tblproduction.pro_order_id = tblproduction_stage.ProductionID
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction_stage.ItemID)
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		
		
		public function TodaysTotalBatchProduction($data = "")
		{  
			if(!empty($data)){
				$from_date = to_sql_date($data["from_date"]);
				$to_date = to_sql_date($data["to_date"]);
				}else{
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
			}
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			
			
			$sql ='SELECT SUM(tblproduction.batch_qty) AS TotalBatch
			FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->row();
			return $result;
		}
		
		public function GetDaywiseProductionForthisMonth($filter = "")
		{
			// $month_input = $filter['month']; // Example: '2024-11'
			// $selected_year = date('Y', strtotime($month_input . "-01")); // Extract year
			// $selected_month = date('m', strtotime($month_input . "-01")); // Extract month
			// $date = $month_input.'-01';//your given date
			// $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
			// $first_date = date("Y-m-d",$first_date_find);
			
			// $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
			// $last_date = date("Y-m-d",$last_date_find);
			
			// $Currentdate = date('Y-m-d');
			// if($last_date > $Currentdate){
			// $todate = $Currentdate;
			// }else{
			// $todate = $last_date;
			// }
			
			$from_date = to_sql_date($filter["from_date"]);
			$to_date = to_sql_date($filter["to_date"]);
			$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
			$period = new DatePeriod(
    		new DateTime($from_date),
    		new DateInterval('P1D'),
    		new DateTime($to_date_new)
			);
			$filter["from_date"] = $from_date;
			$filter["to_date"] = $to_date;
			
			$DayWiseProduction  = $this->GetDayWiseProductionReport($filter);
			
			$labels = [];
			$totals = [];
			//$types  = $this->get();
			// Get the current date
			$i = 1;
			foreach ($period as $key => $value) {
				$date = $value->format('d/m/Y'); 
				$date2 = $value->format('Y-m-d');
				$lable = substr($date,0,2) ."-".date("M", strtotime($date2));
				array_push($labels, $lable);
				$DayProduction = 0;
				foreach ($DayWiseProduction as $key1 => $value1) {
					if(substr($value1['TransDate'],0,10) == $date2){
						$DayProduction = (float) $value1["QtySum"];
					}
				}
				array_push($totals, $DayProduction);
				$i++;
			}
			$chart = [
            'labels'   => $labels,
            'datasets' => [
			[
			'label'           => "Qty",
			'backgroundColor' => 'rgba(37,155,35,0.2)',
			'borderColor'     => '#84c529',
			'tension'         => false,
			'borderWidth'     => 1,
			'data'            => $totals,
			],
            ],
			];
			
			return $chart;
		}
		public function GetDaywiseRMIssueForthisMonth($filter = "")
		{
			// $month_input = $filter['month']; // Example: '2024-11'
			// $selected_year = date('Y', strtotime($month_input . "-01")); // Extract year
			// $selected_month = date('m', strtotime($month_input . "-01")); // Extract month
			// $date = $month_input.'-01';//your given date
			// $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
			// $first_date = date("Y-m-d",$first_date_find);
			
			// $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
			// $last_date = date("Y-m-d",$last_date_find);
			
			// $Currentdate = date('Y-m-d');
			// if($last_date > $Currentdate){
			// $todate = $Currentdate;
			// }else{
			// $todate = $last_date;
			// }
			
			$from_date = to_sql_date($filter["from_date"]);
			$to_date = to_sql_date($filter["to_date"]);
			$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
			$period = new DatePeriod(
    		new DateTime($from_date),
    		new DateInterval('P1D'),
    		new DateTime($to_date_new)
			);
			$filter["from_date"] = $from_date;
			$filter["to_date"] = $to_date;
			
			$DayWiseProduction  = $this->GetDayWiseRMIssueReport($filter);
			
			$labels = [];
			$totals = [];
			//$types  = $this->get();
			// Get the current date
			$i = 1;
			foreach ($period as $key => $value) {
				$date = $value->format('d/m/Y'); 
				$date2 = $value->format('Y-m-d');
				$lable = substr($date,0,2) ."-".date("M", strtotime($date2));
				array_push($labels, $lable);
				$DayProduction = 0;
				foreach ($DayWiseProduction as $key1 => $value1) {
					if(substr($value1['TransDate2'],0,10) == $date2){
						$DayProduction = (float) $value1["QtySum"];
					}
				}
				array_push($totals, $DayProduction);
				$i++;
			}
			$chart = [
            'labels'   => $labels,
            'datasets' => [
			[
			'label'           => "Qty",
			'backgroundColor' => 'rgba(37,155,35,0.2)',
			'borderColor'     => '#84c529',
			'tension'         => false,
			'borderWidth'     => 1,
			'data'            => $totals,
			],
            ],
			];
			
			return $chart;
		}
		public function GetDaywisePMIssueForthisMonth($filter = "")
		{
			// $month_input = $filter['month']; // Example: '2024-11'
			// $selected_year = date('Y', strtotime($month_input . "-01")); // Extract year
			// $selected_month = date('m', strtotime($month_input . "-01")); // Extract month
			// $date = $month_input.'-01';//your given date
			// $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
			// $first_date = date("Y-m-d",$first_date_find);
			
			// $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
			// $last_date = date("Y-m-d",$last_date_find);
			
			// $Currentdate = date('Y-m-d');
			// if($last_date > $Currentdate){
			// $todate = $Currentdate;
			// }else{
			// $todate = $last_date;
			// }
			
			$from_date = to_sql_date($filter["from_date"]);
			$to_date = to_sql_date($filter["to_date"]);
			$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
			$period = new DatePeriod(
    		new DateTime($from_date),
    		new DateInterval('P1D'),
    		new DateTime($to_date_new)
			);
			$filter["from_date"] = $from_date;
			$filter["to_date"] = $to_date;
			
			$DayWiseProduction  = $this->GetDayWisePMIssueReport($filter);
			
			$labels = [];
			$totals = [];
			//$types  = $this->get();
			// Get the current date
			$i = 1;
			foreach ($period as $key => $value) {
				$date = $value->format('d/m/Y'); 
				$date2 = $value->format('Y-m-d');
				$lable = substr($date,0,2) ."-".date("M", strtotime($date2));
				array_push($labels, $lable);
				$DayProduction = 0;
				foreach ($DayWiseProduction as $key1 => $value1) {
					if(substr($value1['TransDate2'],0,10) == $date2){
						$DayProduction = (float) $value1["QtySum"];
					}
				}
				array_push($totals, $DayProduction);
				$i++;
			}
			$chart = [
            'labels'   => $labels,
            'datasets' => [
			[
			'label'           => "Qty",
			'backgroundColor' => 'rgba(37,155,35,0.2)',
			'borderColor'     => '#84c529',
			'tension'         => false,
			'borderWidth'     => 1,
			'data'            => $totals,
			],
            ],
			];
			
			return $chart;
		}
		
		public function Prod_VS_Sales($filterdata)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			if(!empty($filterdata["from_date"])){
				$from_date = to_sql_date($filterdata["from_date"]);
				$to_date = to_sql_date($filterdata["to_date"]);
				}else{
				$from_date = date('Y-m-01');
				$to_date = date('Y-m-d');
			}
			$ItemCount = $filterdata["MaxCount"];
			$SubGroup = $filterdata["SubGroup"];
			$SubGroup2 = $filterdata["SubGroup2"];
			$Items = $filterdata["Items"];
			
			$chart = [];
			$Production = [];
			
			
			
			if($SubGroup){
			    $this->db->select(db_prefix().'production_stage.ItemID, SUM(tblproduction_stage.Qty) as total_qty,'.db_prefix().'items.description as description_name');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID, SUM(tblproduction_stage.Qty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name');
			}
			$this->db->join('tblproduction', 'tblproduction.pro_order_id = tblproduction_stage.ProductionID');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction_stage.ItemID');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				$this->db->group_by('tblproduction_stage.ItemID');
				}else{
			    $this->db->group_by('tblitems.SubGrpID1');
			}
			
			if($SubGroup2){
				$this->db->where_in('tblitems.SubGrpID2', $SubGroup2);
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where('tblproduction_stage.Stage', "Packing");
			
			$this->db->where("tblproduction_stage.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->order_by("total_qty", "DESC");
			$this->db->limit($ItemCount);
			$TopProduction = $this->db->get('tblproduction_stage')->result_array();
			$group_byList = array();
			foreach($TopProduction as $val){
			    array_push($group_byList,$val["ItemID"]);
			}
			$i=0;
			foreach ($TopProduction as $key => $value) {
				array_push($Production, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(int)$value['total_qty'],
				'z' 		=> 100,
				'label' 		=> "Qty"
				]);
				$i++;
			}
			
			// Sale Qty 
			if($SubGroup){
			    $this->db->select(db_prefix().'history.ItemID, SUM(BilledQty) as total_qty,'.db_prefix().'items.description as description_name');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID, SUM(BilledQty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name');
			}
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'clients', 'tblclients.AccountID = tblhistory.AccountID  AND '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID','INNER');
			
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			$this->db->where('tblhistory.PlantID',$selected_company);
			$this->db->where('tblhistory.FY',$fy);
			$this->db->where('tblhistory.TransDate >=', $from_date.' 00:00:00');
			$this->db->where('tblhistory.TransDate <=', $to_date.' 23:59:59');
			$this->db->where('tblhistory.TType ', 'O');
			$this->db->where('tblhistory.TType2 ', 'Order');
			$this->db->where('tblhistory.TransID IS NOT NULL');
			
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				if($group_byList){
			        $this->db->where_in('tblhistory.ItemID', $group_byList);
				}
				$this->db->group_by('tblhistory.ItemID');
				}else{
			    if($group_byList){
			        $this->db->where_in('tblitems.SubGrpID1', $group_byList);
				}
			    $this->db->group_by('tblitems.SubGrpID1');
			}
			if($SubGroup2){
				$this->db->where_in('tblitems.SubGrpID2', $SubGroup2);
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			$this->db->order_by("total_qty", "DESC");
			
			$this->db->limit($ItemCount);
			$TopItem = $this->db->get(db_prefix().'history')->result_array();
			
			$i=0;
			foreach ($TopItem as $key => $value) {
				array_push($chart, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(int)$value['total_qty'],
				'z' 		=> 100,
				'label' 		=> "Qty"
				]);
				$i++;
			}
			
			
			$data = [
			'Sales' => $chart,
			'Production' => $Production,
			];
			
			return $data;
		}
		public function GetProduction_Vs_Sales($filterdata)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			
			$from_date = to_sql_date($filterdata["from_date"]);
			$to_date = to_sql_date($filterdata["to_date"]);
			$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
			$period = new DatePeriod(
    		new DateTime($first_date),
    		new DateInterval('P1D'),
    		new DateTime($to_date_new)
			);
			
			$ItemCount = $filterdata["MaxCount"];
			$SubGroup = $filterdata["SubGroup"];
			$Items = $filterdata["Items"];
			
			$chart = [];
			$Production = [];
			
			if($SubGroup){
			    $this->db->select(db_prefix().'history.ItemID,DATE(tblhistory.TransDate) AS TransDate, SUM(BilledQty) as total_qty,'.db_prefix().'items.description as description_name');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID,DATE(tblhistory.TransDate) AS TransDate, SUM(BilledQty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name');
			}
			
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'clients', 'tblclients.AccountID = tblhistory.AccountID  AND '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID','INNER');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			$this->db->where('tblhistory.PlantID',$selected_company);
			$this->db->where('tblhistory.FY',$fy);
			$this->db->where('tblhistory.TransDate >=', $from_date.' 00:00:00');
			$this->db->where('tblhistory.TransDate <=', $to_date.' 23:59:59');
			$this->db->where('tblhistory.TType ', 'O');
			$this->db->where('tblhistory.TType2 ', 'Order');
			$this->db->where('tblhistory.TransID IS NOT NULL');
			
			
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				}else{
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->group_by('DATE(tblhistory.TransDate)');
			$this->db->order_by("total_qty", "DESC");
			$TopItem = $this->db->get(db_prefix().'history')->result_array();
			
			$i=0;
			foreach ($TopItem as $key => $value) {
				array_push($chart, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(int)$value['total_qty'],
				'z' 		=> $value['TransDate'],
				'label' 		=> "Qty"
				]);
				$i++;
			}
			
			
			if($SubGroup){
			    $this->db->select('tblproduction_stage.ItemID,DATE(tblproduction_stage.TransDate) AS TransDate,SUM(tblproduction_stage.Qty) as total_qty,'.db_prefix().'items.description as description_name');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID,DATE(tblproduction_stage.TransDate) AS TransDate, SUM(tblproduction_stage.Qty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name');
			}
			$this->db->join('tblproduction', 'tblproduction.pro_order_id = tblproduction_stage.ProductionID');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction_stage.ItemID');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				}else{
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where('tblproduction_stage.Stage', "Packing");
			
			$this->db->where("tblproduction_stage.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->group_by('DATE(tblproduction_stage.TransDate)');
			$this->db->order_by("total_qty", "DESC");
			$this->db->limit($ItemCount);
			$TopProduction = $this->db->get('tblproduction_stage')->result_array();
			$i=0;
			foreach ($TopProduction as $key => $value) {
				array_push($Production, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(int) $value['total_qty'],
				'z' 		=> $value['TransDate'],
				'label' 		=> "Qty"
				]);
				$i++;
			}
			
			$data = [
			'Sales' => $chart,
			'Production' => $Production,
			];
			
			return $data;
		}
		
		public function GetTopProduction($filterdata)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			// $month_input = $filterdata['month']; // Example: '2024-11'
    		// $date = $month_input.'-01';//your given date
            // $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
            // $from_date = date("Y-m-d",$first_date_find);
            
            // $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
            // $last_date = date("Y-m-d",$last_date_find);
			
            // $Currentdate = date('Y-m-d');
            // if($last_date > $Currentdate){
			// $to_date = $Currentdate;
			// }else{
			// $to_date = $last_date;
			// }
			
			$from_date = to_sql_date($filterdata["from_date"]);
			$to_date = to_sql_date($filterdata["to_date"]);
			$ItemCount = $filterdata["MaxCount"];
			$SubGroup = $filterdata["SubGroup"];
			$Items = $filterdata["Items"];
			
			$Production = [];
			
			
			if($SubGroup){
			    $this->db->select(db_prefix().'production_stage.ItemID, SUM(tblproduction_stage.Qty) as total_qty,'.db_prefix().'items.description as description_name');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID, SUM(tblproduction_stage.Qty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name');
			}
			$this->db->join('tblproduction', 'tblproduction.pro_order_id = tblproduction_stage.ProductionID');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction_stage.ItemID');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				$this->db->group_by('tblproduction_stage.ItemID');
				}else{
			    $this->db->group_by('tblitems.SubGrpID1');
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where('tblproduction_stage.Stage', "Packing");
			
			$this->db->where("tblproduction_stage.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->order_by("total_qty", "DESC");
			$this->db->limit($ItemCount);
			$TopProduction = $this->db->get('tblproduction_stage')->result_array();
			$i=0;
			foreach ($TopProduction as $key => $value) {
				array_push($Production, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(int)$value['total_qty'],
				'z' 		=> 100,
				'label' 		=> "Qty"
				]);
				$i++;
			}
			
			
			$data = [
			'Production' => $Production,
			];
			
			return $data;
		}
		public function GetProduction_VS_Baking($filterdata)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			// $month_input = $filterdata['month']; // Example: '2024-11'
    		// $date = $month_input.'-01';//your given date
            // $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
            // $from_date = date("Y-m-d",$first_date_find);
            
            // $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
            // $last_date = date("Y-m-d",$last_date_find);
			
            // $Currentdate = date('Y-m-d');
            // if($last_date > $Currentdate){
			// $to_date = $Currentdate;
			// }else{
			// $to_date = $last_date;
			// }
			
			
			$from_date = to_sql_date($filterdata["from_date"]);
			$to_date = to_sql_date($filterdata["to_date"]);
			$ItemCount = $filterdata["MaxCount"];
			$SubGroup = $filterdata["SubGroup"];
			$Items = $filterdata["Items"];
			
			$Production = [];
			
			
			if($SubGroup){
			    $this->db->select(db_prefix().'production.recipeID AS ItemID, SUM(tblproduction.Finish_good_qty) as total_qty,'.db_prefix().'items.description as description_name,COALESCE(SUM(B1.Qty),0) as BakingQty');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID, SUM(tblproduction.Finish_good_qty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name,COALESCE(SUM(B1.Qty),0) as BakingQty');
			}
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction.recipeID');
			$this->db->join('tblproduction_stage as B1', 'B1.ItemID = tblproduction.recipeID AND B1.ProductionID = tblproduction.pro_order_id AND B1.Stage = "Baking"','LEFT');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				$this->db->group_by('tblproduction.recipeID');
				}else{
			    $this->db->group_by('tblitems.SubGrpID1');
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where("tblproduction.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->order_by("total_qty", "DESC");
			$this->db->limit($ItemCount);
			$TopProduction = $this->db->get('tblproduction')->result_array();
			$i=0;
			foreach ($TopProduction as $key => $value) {
				$Percentage = (($value['BakingQty']/$value['total_qty'])*100);
				array_push($Production, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(float) $Percentage,
				'z' 		=> 100,
				'label' 		=> "Percentage"
				]);
				$i++;
			}
			
			$data = [
			'Production' =>$Production,
			];
			
			return $data;
		}
		public function GetProduction_VS_Packing($filterdata)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			// $month_input = $filterdata['month']; // Example: '2024-11'
    		// $date = $month_input.'-01';//your given date
            // $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
            // $from_date = date("Y-m-d",$first_date_find);
            
            // $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
            // $last_date = date("Y-m-d",$last_date_find);
			
            // $Currentdate = date('Y-m-d');
            // if($last_date > $Currentdate){
			// $to_date = $Currentdate;
			// }else{
			// $to_date = $last_date;
			// }
			
			
			$from_date = to_sql_date($filterdata["from_date"]);
			$to_date = to_sql_date($filterdata["to_date"]);
			$ItemCount = $filterdata["MaxCount"];
			$SubGroup = $filterdata["SubGroup"];
			$Items = $filterdata["Items"];
			
			$Production = [];
			
			
			if($SubGroup){
			    $this->db->select(db_prefix().'production.recipeID AS ItemID, SUM(tblproduction.Finish_good_qty) as total_qty,'.db_prefix().'items.description as description_name,COALESCE(SUM(P1.Qty),0) as PackingQty');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID, SUM(tblproduction.Finish_good_qty) as total_qty,'.db_prefix().'items_sub_groups.name as description_name,COALESCE(SUM(P1.Qty),0) as PackingQty');
			}
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction.recipeID');
			$this->db->join('tblproduction_stage as P1', 'P1.ItemID = tblproduction.recipeID AND P1.ProductionID = tblproduction.pro_order_id AND P1.Stage = "Packing"','LEFT');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				$this->db->group_by('tblproduction.recipeID');
				}else{
			    $this->db->group_by('tblitems.SubGrpID1');
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where("tblproduction.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->order_by("total_qty", "DESC");
			$this->db->limit($ItemCount);
			$TopProduction = $this->db->get('tblproduction')->result_array();
			$i=0;
			foreach ($TopProduction as $key => $value) {
				$Percentage = (($value['PackingQty']/$value['total_qty'])*100);
				array_push($Production, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(float) $Percentage,
				'z' 		=> 100,
				'label' 		=> "Percentage"
				]);
				$i++;
			}
			
			$data = [
			'Production' =>$Production,
			];
			
			return $data;
		}
		public function GetBaking_VS_Packing($filterdata)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			// $month_input = $filterdata['month']; // Example: '2024-11'
    		// $date = $month_input.'-01';//your given date
            // $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
            // $from_date = date("Y-m-d",$first_date_find);
            
            // $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
            // $last_date = date("Y-m-d",$last_date_find);
			
            // $Currentdate = date('Y-m-d');
            // if($last_date > $Currentdate){
			// $to_date = $Currentdate;
			// }else{
			// $to_date = $last_date;
			// }
			
			
			$from_date = to_sql_date($filterdata["from_date"]);
			$to_date = to_sql_date($filterdata["to_date"]);
			$ItemCount = $filterdata["MaxCount"];
			$SubGroup = $filterdata["SubGroup"];
			$Items = $filterdata["Items"];
			
			$Production = [];
			
			
			if($SubGroup){
			    $this->db->select('B1.ItemID,'.db_prefix().'items.description as description_name,COALESCE(SUM(B1.Qty),0) as BakingQty,COALESCE(SUM(P1.Qty),0) as PackingQty,CASE 
				WHEN COALESCE(SUM(B1.Qty),0) = 0 THEN 0 
				ELSE (COALESCE(SUM(P1.Qty),0) / COALESCE(SUM(B1.Qty),0)) * 100 
				END as PackingPercentage');
				}else{
			    $this->db->select(db_prefix().'items_sub_groups.id as ItemID,'.db_prefix().'items_sub_groups.name as description_name,COALESCE(SUM(B1.Qty),0) as BakingQty,COALESCE(SUM(P1.Qty),0) as PackingQty,CASE 
				WHEN COALESCE(SUM(B1.Qty),0) = 0 THEN 0 
				ELSE (COALESCE(SUM(P1.Qty),0) / COALESCE(SUM(B1.Qty),0)) * 100 
				END as PackingPercentage');
			}
			$this->db->from('tblproduction_stage as B1');
			$this->db->join('tblproduction', 'tblproduction.pro_order_id = B1.ProductionID');
			$this->db->join('tblitems', 'tblitems.item_code = B1.ItemID');
			$this->db->join('tblproduction_stage as P1', 'P1.ItemID = B1.ItemID AND P1.ProductionID = B1.ProductionID AND P1.Stage = "Packing"','LEFT');
			if($SubGroup){
				
				}else{
			    $this->db->join(db_prefix() . 'items_sub_groups', db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'items.SubGrpID1');
			}
			if($SubGroup){
				$this->db->where_in('tblitems.SubGrpID1', $SubGroup);
				$this->db->group_by('tblitems.item_code');
				}else{
			    $this->db->group_by('tblitems.SubGrpID1');
			}
			if($Items){
				$this->db->where_in('tblitems.item_code', $Items);
			}
			
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where("B1.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->order_by("PackingPercentage", "DESC");
			$this->db->limit($ItemCount);
			$TopProduction = $this->db->get()->result_array();
			$i=0;
			foreach ($TopProduction as $key => $value) {
				$Percentage = (($value['PackingQty']/$value['BakingQty'])*100);
				array_push($Production, [
				'name' 		=> $value['description_name'],
				'y' 		=>	(float) $value['PackingPercentage'],
				'z' 		=> 100,
				'label' 		=> "Percentage"
				]);
				$i++;
			}
			
			$data = [
			'Production' =>$Production,
			];
			
			return $data;
		}
		
		//===================== Get Day Wise Sale Reports ==============================
		public function GetDayWiseProductionReport($filterdata = "")
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$from_date = $filterdata["from_date"].' 00:00:00';
			$to_date = $filterdata["to_date"].' 23:59:59';
			$this->db->select('tblproduction_stage.TransDate,SUM(tblproduction_stage.Qty) AS QtySum');
			
			$this->db->join('tblproduction', 'tblproduction.pro_order_id = tblproduction_stage.ProductionID');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction_stage.ItemID');
			if($filterdata["SubGroup"]){
				$this->db->where_in('tblitems.SubGrpID1', $filterdata["SubGroup"]);
			}
			if($filterdata["Items"]){
				$this->db->where_in('tblitems.item_code', $filterdata["Items"]);
			}
			$this->db->where('tblproduction.PlantID', $selected_company);
			$this->db->where('tblproduction.FY', $fy);
			
			$this->db->where('tblproduction_stage.Stage', "Packing");
			
			$this->db->where("tblproduction_stage.TransDate BETWEEN '$from_date' AND '$to_date'");
			$this->db->group_by('DATE(tblproduction_stage.TransDate)');
			$this->db->order_by('tblproduction_stage.TransDate', 'ASC');
			return $this->db->get('tblproduction_stage')->result_array();
		}
		//===================== Get Day Wise RM ISSUE Reports ==============================
		public function GetDayWiseRMIssueReport($filterdata = "")
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$from_date = $filterdata["from_date"].' 00:00:00';
			$to_date = $filterdata["to_date"].' 23:59:59';
			
			$this->db->select('tblhistory.TransDate2,SUM(tblhistory.BilledQty) AS QtySum');
			$this->db->join('tblitems', 'tblitems.item_code = tblhistory.ItemID');
			if($filterdata["SubGroup"]){
				$this->db->where_in('tblitems.SubGrpID1', $filterdata["SubGroup"]);
			}
			if($filterdata["Items"]){
				$this->db->where_in('tblitems.item_code', $filterdata["Items"]);
			}
			
			$this->db->where('tblitems.MainGrpID','2');
			$this->db->where('tblhistory.PlantID',$selected_company);
			$this->db->where('tblhistory.FY',$fy);
			$this->db->where('tblhistory.TransDate2 >=', $from_date.' 00:00:00');
			$this->db->where('tblhistory.TransDate2 <=', $to_date.' 23:59:59');
			$this->db->where('tblhistory.TType ', 'A');
			$this->db->where('tblhistory.TType2 ', 'Issue');
			$this->db->where('tblhistory.TransID IS NOT NULL');
			
			$this->db->group_by('DATE(tblhistory.TransDate2)');
			$this->db->order_by('tblhistory.TransDate2', 'ASC');
			return $this->db->get('tblhistory')->result_array();
			
		}
		//===================== Get Day Wise RM ISSUE Reports ==============================
		public function GetDayWisePMIssueReport($filterdata = "")
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$from_date = $filterdata["from_date"].' 00:00:00';
			$to_date = $filterdata["to_date"].' 23:59:59';
			
			$this->db->select('tblhistory.TransDate2,SUM(tblhistory.BilledQty) AS QtySum');
			$this->db->join('tblitems', 'tblitems.item_code = tblhistory.ItemID');
			if($filterdata["SubGroup"]){
				$this->db->where_in('tblitems.SubGrpID1', $filterdata["SubGroup"]);
			}
			if($filterdata["Items"]){
				$this->db->where_in('tblitems.item_code', $filterdata["Items"]);
			}
			
			$this->db->where('tblitems.MainGrpID','3');
			$this->db->where('tblhistory.PlantID',$selected_company);
			$this->db->where('tblhistory.FY',$fy);
			$this->db->where('tblhistory.TransDate2 >=', $from_date.' 00:00:00');
			$this->db->where('tblhistory.TransDate2 <=', $to_date.' 23:59:59');
			$this->db->where('tblhistory.TType ', 'A');
			$this->db->where('tblhistory.TType2 ', 'Issue');
			$this->db->where('tblhistory.TransID IS NOT NULL');
			
			$this->db->group_by('DATE(tblhistory.TransDate2)');
			$this->db->order_by('tblhistory.TransDate2', 'ASC');
			return $this->db->get('tblhistory')->result_array();
			
		}
		
		public function GetAllItemList()
		{
			$selected_company = $this->session->userdata('root_company');
			$this->db->select('tblitems.*');
			$this->db->where('tblitems.PlantID', $selected_company);
			$this->db->where('tblitems.isactive', "Y");
			$this->db->order_by('tblitems.description', 'ASC');
			return $this->db->get(db_prefix() . 'items')->result_array();
		}
		
		public function GetItemUsedRecipeList($ItemID)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select(db_prefix() .'recipe_details.*,tblrecipe.item_description as RecipeName,tblrecipe.batch_size');
			$this->db->from(db_prefix() .'recipe_details');
			$this->db->join('tblrecipe', 'tblrecipe.id = tblrecipe_details.rec_id AND tblrecipe.PlantID = tblrecipe_details.PlantID ','INNER');
			$this->db->where(db_prefix() .'recipe_details.PlantID', $selected_company);
			$this->db->where(db_prefix() .'recipe_details.item_id', $ItemID);
			$this->db->where(db_prefix() .'recipe.status', 'Y');
			$result = $this->db->get()->result_array();
            return $result;
		}
		
		public function PendingProductionList()
		{  
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = 'tblproduction.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'" AND '.db_prefix().'production.production_status = "pending"';
			
			$sql1 .= '  ORDER BY Transdate ASC';
			
			$sql ='SELECT '.db_prefix().'production.*,tblrecipe.Is_Baking,  tblitems.description
			FROM '.db_prefix().'production 
			INNER JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) AND tblrecipe.status = "Y"
			WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		
		public function GetNeededQtyItemByOrderIds($data)
		{  
			$selected_ids = $data["selected_ids"];
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			if (empty($selected_ids)) {
				$sql1 = '1=0 ';
				}else {
				$ids = explode(",",$selected_ids);   
				$sql1 = db_prefix().'production_details.production_id IN ("'.implode('","',$ids).'")';
			}
			
			$sql1 .= ' GROUP BY '.db_prefix().'production_details.item_id';
			$sql1 .= ' ORDER BY '.db_prefix().'items.SubGrpID1 ASC';
			
			$sql ='SELECT SUM('.db_prefix().'production_details.req_qty) AS req_qty,SUM('.db_prefix().'production_details.production_req_qty) AS production_req_qty,SUM('.db_prefix().'production_details.return_req_qty) AS return_req_qty,SUM('.db_prefix().'production_details.return_req_qty) AS return_req_qty,SUM('.db_prefix().'production_details.ExtraQty) AS ExtraQty,
			'.db_prefix().'stockmaster.OQty,'.db_prefix().'production_details.item_id AS ItemID,'.db_prefix().'items.description,'.db_prefix().'items.case_qty as CaseQty,'.db_prefix().'items.unit
			FROM '.db_prefix().'production_details 
			INNER JOIN '.db_prefix().'items ON '.db_prefix().'production_details.item_id = '.db_prefix().'items.item_code AND '.db_prefix().'production_details.PlantID = '.db_prefix().'items.PlantID 
			LEFT JOIN '.db_prefix().'stockmaster ON '.db_prefix().'production_details.item_id = '.db_prefix().'stockmaster.ItemID AND '.db_prefix().'production_details.PlantID = '.db_prefix().'stockmaster.PlantID AND '.db_prefix().'production_details.FY = '.db_prefix().'stockmaster.FY AND tblstockmaster.GodownID="PU"
			WHERE '.$sql1;
			$result = $this->db->query($sql)->result_array();
			
			$itemIds = array();
			foreach ($result as $key => $value) {
				array_push($itemIds, $value["ItemID"]);
			}
			
			$from_date = '20'.$fy.'-04-01 00:00:00';
			$dates = date('Y-m-d H:i:s');
			$this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
			$this->db->from(db_prefix() .'history');
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() .'history.FY', $fy);
			$this->db->where(db_prefix() .'history.GodownID', "PU");
			if (empty($itemIds)) {
				$this->db->where('1=0');
				} else {
				$this->db->where_in(db_prefix().'history.ItemID', $itemIds);
			}
			$this->db->where(db_prefix() .'history.TransDate2 BETWEEN "'. $from_date. '" AND "'. $dates. ' 23:59:00" ');
			$this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
			$this->db->group_by('ItemID,TType,TType2');
			$StockData = $this->db->get()->result_array();
			
			$this->db->select('tblhistory.ItemID,TType,TType2,SUM(BilledQty) AS BilledQty,tblstockmaster.OQty');
			$this->db->from(db_prefix() .'history');
			$this->db->join('tblstockmaster', 'tblstockmaster.ItemID = tblhistory.ItemID AND tblstockmaster.PlantID = tblhistory.PlantID AND tblstockmaster.FY = tblhistory.FY  AND tblstockmaster.GodownID=tblhistory.GodownID','LEFT');
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() .'history.FY', $fy);
			$this->db->where(db_prefix() .'history.GodownID', "RM");
			if (empty($itemIds)) {
				$this->db->where('1=0');
				} else {
				$this->db->where_in(db_prefix().'history.ItemID', $itemIds);
			}
			$this->db->where(db_prefix() .'history.TransDate2 BETWEEN "'. $from_date. '" AND "'. $dates. ' 23:59:00" ');
			$this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
			$this->db->group_by('ItemID,TType,TType2');
			$StockDataRMPM = $this->db->get()->result_array();
			// echo "<pre>";print_r($StockDataRMPM);die;
			$i = 0;
			foreach ($result as $key1 => $value1) {
				$PQty = 0;
                $PRQty = 0;
                $IQty = 0;
                $PRDQty = 0;
                $SQty = 0;
                $SRTQty = 0;
                $AQty = 0;
                $GIQty = 0;
                $GOQty = 0;
				foreach ($StockData as $key2 => $value2) {
					if($value1["ItemID"] == $value2["ItemID"]){
						
						if($value2['TType'] == 'P' && $value2['TType2'] == 'Purchase'){
							$PQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'N'){
							$PRQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'A'){
							$IQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'B'){
							$PRDQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'O' && $value2['TType2'] == 'Order'){
							$SQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'R' && $value2['TType2'] == 'Fresh'){
							$SRTQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Free Distribution'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Stock Adjustment'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Promotional Activity'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Stock distribution'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'T' && $value2['TType2'] == 'In'){
							$GIQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'T' && $value2['TType2'] == 'Out'){
							$GOQty = $value2['BilledQty'];
						}
					}
				}
				$stockQty = $value1['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
				$stockQtyInCase = $stockQty / $value1['CaseQty'];
				$result[$i]['StockBal'] = $stockQtyInCase;
				
				$PQty2 = 0;
                $PRQty2 = 0;
                $IQty2 = 0;
                $PRDQty2 = 0;
                $SQty2 = 0;
                $SRTQty2 = 0;
                $AQty2 = 0;
                $GIQty2 = 0;
                $GOQty2 = 0;
                $OQty2 = 0;
				foreach ($StockDataRMPM as $key3 => $value3) {
					
					if($value1["ItemID"] == $value3["ItemID"]){
						// echo "<pre>";print_r($value3);die;
						// echo $value3["ItemID"];
						// echo $value3['OQty'];die;
						if(!empty($value3['OQty'])){
							$OQty2 = $value3['OQty'];
						}
						
						if($value3['TType'] == 'P' && $value3['TType2'] == 'Purchase'){
							$PQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'N'){
							$PRQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'A'){
							$IQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'B'){
							$PRDQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'O' && $value3['TType2'] == 'Order'){
							$SQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'R' && $value3['TType2'] == 'Fresh'){
							$SRTQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'X' && $value3['TType2'] == 'Free Distribution'){
							$AQty2 += $value3['BilledQty'];
							}elseif($value3['TType'] == 'X' && $value3['TType2'] == 'Stock Adjustment'){
							$AQty2 += $value3['BilledQty'];
							}elseif($value3['TType'] == 'X' && $value3['TType2'] == 'Promotional Activity'){
							$AQty2 += $value3['BilledQty'];
							}elseif($value3['TType'] == 'X' && $value3['TType2'] == 'Stock distribution'){
							$AQty2 += $value3['BilledQty'];
							}elseif($value3['TType'] == 'T' && $value3['TType2'] == 'In'){
							$GIQty2 = $value3['BilledQty'];
							}elseif($value3['TType'] == 'T' && $value3['TType2'] == 'Out'){
							$GOQty2 = $value3['BilledQty'];
						}
					}
				}
				$stockQty2 = $OQty2 + $PQty2 - $PRQty2 - $IQty2 + $PRDQty2 - $SQty2 + $SRTQty2 - $AQty2 - $GOQty2 + $GIQty2;
				$stockQtyInCase2 = $stockQty2 / $value1['CaseQty'];
				$result[$i]['StockBalRMPM'] = $stockQtyInCase2;
				// echo "<pre>";print_r($OQty2);die;
				
				$i++;
			}
			return $result;
		}
		
		public function increment_next_number_stock_transfer()
		{
			// Update next CHALLAN number in settings
			$FY = $this->session->userdata('finacial_year'); 
			$selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_trns_number_for_cspl');
                
				}elseif($selected_company == 2){
                $this->db->where('name', 'next_trns_number_for_cff');
				
				}elseif($selected_company == 3){
                $this->db->where('name', 'next_trns_number_for_cbu');
                
				}elseif($selected_company == 4){
                $this->db->where('name', 'next_trns_number_for_cbupl');
                
			}
			$this->db->set('value', 'value+1', false);
			$this->db->WHERE('FY', $FY);
			$this->db->update(db_prefix() . 'options');
		}
		
		public function load_data_for_damage_production($data)
		{  
			$from_date = to_sql_date($data["from_date"]);
			$to_date = to_sql_date($data["to_date"]);
			$status_list = $data["status_list"];
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'production.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59") AND '.db_prefix().'production.FY = "'.$fy.'" AND '.db_prefix().'production.PlantID = "'.$selected_company.'"';
			if($status_list == "all"){
				
				}else{
				$sql1 .= ' AND '.db_prefix().'production.production_status = "'.$status_list.'"';
			}
			
			$sql1 .= ' GROUP BY recipeID ORDER BY recipeID ASC';
			
			$sql = 'SELECT tblproduction.production_status,tblproduction.recipeID,tblitems.description,tblitems.weight,tblitems.unit,
		    SUM(tblproduction.batch_qty) as batch_qty,SUM(FLOOR(tblproduction.Finish_good_qty)) as Finish_good_qty,COALESCE(SUM(FLOOR(tblproduction_stage.Qty)), 0) as PackingQty FROM '.db_prefix().'production 
			LEFT JOIN tblitems ON UPPER(tblitems.item_code) = UPPER(tblproduction.recipeID)
			INNER JOIN tblrecipe ON UPPER(tblrecipe.item_code) = UPPER(tblproduction.recipeID) 
            AND tblrecipe.status = "Y"
			LEFT JOIN tblproduction_stage ON tblproduction_stage.ProductionID = tblproduction.pro_order_id AND tblproduction_stage.ItemID = tblproduction.recipeID AND tblproduction_stage.Stage = "Packing" WHERE tblitems.PlantID = '.$selected_company.' AND '.$sql1;
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		
		public function GetMonthlyDamageReport($data)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$status_list = $data["status_list"];
			$start_year = 2000 + $fy;       // 2000 + 25 = 2025
			$end_year   = $start_year + 1;  // 2026
			
			// Define financial year range
			$from_date = $start_year . '-04-01 00:00:00'; // 1st April 2025
			$to_date   = $end_year . '-03-31 23:59:59';   // 31st March 2026
			
			$chart = [];
			
			$this->db->select('DATE_FORMAT(tblproduction.Transdate, "%b-%Y") as month, tblitems.description, tblitems.weight, tblitems.unit,SUM(tblproduction.batch_qty) as batch_qty,SUM(FLOOR(tblproduction.Finish_good_qty)) as Finish_good_qty,SUM(tblproduction_stage.Qty) as PackingQty');
			$this->db->from(db_prefix() . 'production_stage');
			$this->db->join('tblproduction', 'tblproduction.pro_order_id = tblproduction_stage.ProductionID','INNNER');
			$this->db->join('tblitems', 'tblitems.item_code = tblproduction.recipeID','LEFT');
			$this->db->join('tblrecipe', 'tblrecipe.item_code = tblproduction.recipeID AND tblrecipe.status = "Y" ','INNER');
			$this->db->where('tblproduction.Transdate >=', $from_date.' 00:00:00');
			$this->db->where('tblproduction.Transdate <=', $to_date.' 23:59:59');
			$this->db->where('tblproduction.FY ', $fy);
			$this->db->where('tblproduction.PlantID ', $selected_company);
			$this->db->where('tblproduction_stage.Stage ', 'Packing');
			if($status_list == "all"){
				
				}else{
				
				$this->db->where('tblproduction.production_status', $status_list);
			}
			$this->db->group_by("YEAR(tblproduction.Transdate), MONTH(tblproduction.Transdate)");
			$this->db->order_by("YEAR(tblproduction.Transdate), MONTH(tblproduction.Transdate)", "ASC");
			
			$Damage = $this->db->get()->result_array();
			//return $TopItem;
			$i=0;
			foreach ($Damage as $key => $value) {
				
				$diff = intval($value['Finish_good_qty']) - $value['PackingQty'];
				// $diff =  intval($value['PackingQty']);
				array_push($chart, [
				'name' 		=> $value['month'],
				'y' 		=>	(int) $diff,
				'z' 		=> 100,
				'label' 		=> "Total"
				]);
				$i++;
			}
			
			return $chart;
		}
		
		public function get_main_item_group()
		{	
			$this->db->select('*');
			$this->db->from(db_prefix() . 'items_main_groups');
			return $this->db->get()->result_array();
		}
		
		
		public function GetSubgroup1Data($MainGroupId)
		{
			$this->db->select(db_prefix() . 'items_sub_groups.*');
			$this->db->where(db_prefix() . 'items_sub_groups.main_group_id', $MainGroupId);
			$this->db->order_by(db_prefix() . 'items_sub_groups.name', 'ASC');
			return $this->db->get('tblitems_sub_groups')->result_array();
		}
		
		public function Get_ItemData_for_PhysicalStock($Filter)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			
			$Date = $Filter["Date"];
			$dt = DateTime::createFromFormat('d/m/Y H:i', $Date);
			if ($dt) {
				$dates = $dt->format('Y-m-d H:i:s');
				} else {
				$dates = null; // invalid format
			}
			
			$MainGroup = $Filter["MainGroup"];
			$SubGroup1 = $Filter["SubGroup1"];
			
			$this->db->select('tblitems.*,tblitems.item_code AS ItemID,tblitems.case_qty AS CaseQty,
			(SELECT SUM(tblstockmaster.OQty) FROM tblstockmaster WHERE tblstockmaster.ItemID=tblitems.item_code AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" ) AS OQty');
			$this->db->where('tblitems.PlantID', $selected_company);
			$this->db->where('tblitems.MainGrpID', $MainGroup);
			$this->db->where('tblitems.SubGrpID1', $SubGroup1);
			$this->db->where('tblitems.isactive', "Y");
			$this->db->order_by('tblitems.description', 'ASC');
			$Items = $this->db->get(db_prefix() . 'items')->result_array();
			// echo "<pre>";print_r($Items);die;
			$itemIds = array();
			foreach ($Items as $key => $value) {
				array_push($itemIds, $value["ItemID"]);
			}
			
			$from_date = '20'.$fy.'-04-01 00:00:00';
			$this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
			$this->db->from(db_prefix() .'history');
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() .'history.FY', $fy);
			if (empty($itemIds)) {
				$this->db->where('1=0');
				} else {
				$this->db->where_in(db_prefix().'history.ItemID', $itemIds);
			}
			$this->db->where(db_prefix() .'history.TransDate2 BETWEEN "'. $from_date. '" AND "'. $dates. ' 23:59:00" ');
			$this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
			$this->db->group_by('ItemID,TType,TType2');
			$StockData = $this->db->get()->result_array();
			
			$i = 0;
			foreach ($Items as $key1 => $value1) {
				$PQty = 0;
                $PRQty = 0;
                $IQty = 0;
                $PRDQty = 0;
                $SQty = 0;
                $SRTQty = 0;
                $AQty = 0;
                $GIQty = 0;
                $GOQty = 0;
				foreach ($StockData as $key2 => $value2) {
					if($value1["ItemID"] == $value2["ItemID"]){
						
						if($value2['TType'] == 'P' && $value2['TType2'] == 'Purchase'){
							$PQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'N'){
							$PRQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'A'){
							$IQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'B'){
							$PRDQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'O' && $value2['TType2'] == 'Order'){
							$SQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'R' && $value2['TType2'] == 'Fresh'){
							$SRTQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Free Distribution'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Stock Adjustment'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Promotional Activity'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Stock distribution'){
							$AQty += $value2['BilledQty'];
							}elseif($value2['TType'] == 'T' && $value2['TType2'] == 'In'){
							$GIQty = $value2['BilledQty'];
							}elseif($value2['TType'] == 'T' && $value2['TType2'] == 'Out'){
							$GOQty = $value2['BilledQty'];
						}
					}
				}
				$stockQty = $value1['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
				
				if($value1['CaseQty'] == 0){
					$value1['CaseQty'] = 1;
				}
				$stockQtyInCase = $stockQty / $value1['CaseQty'];
				$Items[$i]['StockBal'] = $stockQtyInCase;
				
				$i++;
			}
			
			return $Items;
		}
		
		
		public function increment_next_physical_stock_number()
		{
			// Update next number in settings
			$FY = $this->session->userdata('finacial_year'); 
			$selected_company = $this->session->userdata('root_company');
            
            $this->db->where('name', 'next_physical_stock_number_for_cspl');
			$this->db->set('value', 'value+1', false);
			$this->db->WHERE('FY', $FY);
			$this->db->update(db_prefix() . 'options');
		}
		
		
		public function StockEntryStaff()
		{
			$this->db->select('tblstaff.AccountID, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS AccountName');
			$this->db->from('tblPhysicalStockEntry');
			$this->db->join('tblstaff', 'tblstaff.AccountID = tblPhysicalStockEntry.UserID AND tblstaff.PlantID = tblPhysicalStockEntry.PlantID');
			$this->db->group_by('tblstaff.AccountID');
			$this->db->order_by(db_prefix() . 'staff.firstname', 'ASC');
			$this->db->order_by(db_prefix() . 'staff.lastname', 'ASC');
			return $this->db->get()->result_array();
		}
		
		public function GetPhysicalStockEntryData($Filter)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			
			$from_date = to_sql_date($Filter["from_date"]);
			$to_date = to_sql_date($Filter["to_date"]);
			
			$MainGroup = $Filter["MainGroup"];
			$SubGroup1 = $Filter["SubGroup1"];
			$UserID = $Filter["UserID"];
			
			$this->db->select('tblPhysicalStockDetail.TransID,tblPhysicalStockDetail.TransDate,tblPhysicalStockEntry.Transdate2,tblPhysicalStockDetail.Erp_Stock,tblPhysicalStockDetail.Qty, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS AccountName,tblitems.description,tblitems.item_code AS ItemID,tblitems.case_qty AS CaseQty,
			(SELECT SUM(tblstockmaster.OQty) FROM tblstockmaster WHERE tblstockmaster.ItemID=tblitems.item_code AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" ) AS OQty');
			$this->db->from('tblPhysicalStockDetail');
			$this->db->join('tblitems', 'tblitems.item_code = tblPhysicalStockDetail.ItemID');
			$this->db->join('tblPhysicalStockEntry', 'tblPhysicalStockEntry.TransID = tblPhysicalStockDetail.TransID');
			$this->db->join('tblstaff', 'tblstaff.AccountID = tblPhysicalStockEntry.UserID AND tblstaff.PlantID = tblPhysicalStockEntry.PlantID');
			$this->db->where('tblitems.PlantID', $selected_company);
			if(isset($MainGroup) && $MainGroup != ''){
				$this->db->where('tblitems.MainGrpID', $MainGroup);
			}
			if(isset($SubGroup1) && $SubGroup1 != ''){
				$this->db->where('tblitems.SubGrpID1', $SubGroup1);
			}
			if(isset($UserID) && $UserID != ''){
				$this->db->where('tblPhysicalStockEntry.UserID', $UserID);
			}
			$this->db->where('tblPhysicalStockEntry.TransDate >=', $from_date.' 00:00:00');
			$this->db->where('tblPhysicalStockEntry.TransDate <=', $to_date.' 23:59:59');
			$this->db->order_by('tblPhysicalStockDetail.TransID', 'ASC');
			$Items = $this->db->get()->result_array();
			
			// $i = 0;
			// foreach ($Items as $key1 => $value1) {
				// $PQty = 0;
                // $PRQty = 0;
                // $IQty = 0;
                // $PRDQty = 0;
                // $SQty = 0;
                // $SRTQty = 0;
                // $AQty = 0;
                // $GIQty = 0;
                // $GOQty = 0;
				
				// $from_date = '20'.$fy.'-04-01 00:00:00';
				// $to_date = substr($value1['TransDate'],0,19);
				// $this->db->select('ItemID,TType,TType2,SUM(BilledQty) AS BilledQty');
				// $this->db->from(db_prefix() .'history');
				// $this->db->where(db_prefix() .'history.PlantID', $selected_company);
				// $this->db->where(db_prefix() .'history.FY', $fy);
				
				// $this->db->where(db_prefix().'history.ItemID', $value1["ItemID"]);
				// $this->db->where(db_prefix() .'history.TransDate2 BETWEEN "'. $from_date. '" AND "'. $to_date. '" ');
				// $this->db->where(db_prefix() . 'history.BillID is NOT NULL', NULL, FALSE);
				// $this->db->group_by('ItemID,TType,TType2');
				// $StockData = $this->db->get()->result_array();
				// foreach ($StockData as $key2 => $value2) {
					// if($value1["ItemID"] == $value2["ItemID"]){
						
						// if($value2['TType'] == 'P' && $value2['TType2'] == 'Purchase'){
							// $PQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'N'){
							// $PRQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'A'){
							// $IQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'B'){
							// $PRDQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'O' && $value2['TType2'] == 'Order'){
							// $SQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'R' && $value2['TType2'] == 'Fresh'){
							// $SRTQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Free Distribution'){
							// $AQty += $value2['BilledQty'];
							// }elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Stock Adjustment'){
							// $AQty += $value2['BilledQty'];
							// }elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Promotional Activity'){
							// $AQty += $value2['BilledQty'];
							// }elseif($value2['TType'] == 'X' && $value2['TType2'] == 'Stock distribution'){
							// $AQty += $value2['BilledQty'];
							// }elseif($value2['TType'] == 'T' && $value2['TType2'] == 'In'){
							// $GIQty = $value2['BilledQty'];
							// }elseif($value2['TType'] == 'T' && $value2['TType2'] == 'Out'){
							// $GOQty = $value2['BilledQty'];
						// }
					// }
				// }
				// $stockQty = $value1['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
				
				// if($value1['CaseQty'] == 0){
					// $value1['CaseQty'] = 1;
				// }
				// $stockQtyInCase = $stockQty / $value1['CaseQty'];
				// $Items[$i]['StockBal'] = $stockQtyInCase;
				
				// $i++;
			// }
			
			return $Items;
		}
	}																							