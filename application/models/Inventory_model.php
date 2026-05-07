<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Inventory_model extends App_Model
	{
		
		public function __construct()
		{
			parent::__construct(); 
		}
		
		
		public function ItemCountFG()
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql = "SELECT 
			COUNT(*) AS TotalItem,
			SUM(CASE WHEN isactive = 'Y' THEN 1 ELSE 0 END) AS ActiveItem
			FROM tblitems WHERE MainGrpID='1'";
			
			$result = $this->db->query($sql)->row();
			return $result;
		}
		public function ItemCountRM()
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql = "SELECT 
			COUNT(*) AS TotalItem,
			SUM(CASE WHEN isactive = 'Y' THEN 1 ELSE 0 END) AS ActiveItem
			FROM tblitems WHERE MainGrpID='2'";
			
			$result = $this->db->query($sql)->row();
			return $result;
		}
		
		
		public function GetItemList($MainGroup)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$GodownID = $filterdata["GodownID"]; 
			if($selected_company == "1"){
				$CustType = '1';
				}else if($selected_company == "2"){
				$CustType = '13';
				}else if($selected_company == "3"){
				$CustType = '21';
			}
			
			$sql = 'SELECT  tblitems.PlantID,tblitems.item_code,tblitems.description,tblitems.case_qty,tblitems.unit,tblrate_master.assigned_rate,
			(SELECT SUM(tblstockmaster.OQty) AS OQty FROM tblstockmaster WHERE tblstockmaster.ItemID=tblitems.item_code AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" GROUP BY tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY) AS OQty
			FROM `tblitems` 
			LEFT JOIN tblrate_master ON tblrate_master.item_id=tblitems.item_code AND tblrate_master.PlantID = tblitems.PlantID AND tblrate_master.state_id = "UP" AND tblrate_master.distributor_id= "'.$CustType.'"
			WHERE tblitems.PlantID = '.$selected_company.'   
			AND tblitems.MainGrpID ="'.$MainGroup.'" ';
			
			
			$sql .= ' ORDER BY tblitems.SubGrpID1 ASC';
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		
		public function GetStockData($MainGroup)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$from_date = date('Y-m-01').' 00:00:00';
			$to_date = date('Y-m-d').' 23:59:59';
			
			$sql = 'SELECT tblhistory.*,tblitems.item_code FROM `tblhistory` 
			INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
			WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND
			tblitems.MainGrpID ="'.$MainGroup.'" AND tblhistory.BillID IS NOT NULL ';
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		
		
		public function get_item_open_qty($MainGroup)
		{
			$from_date = date('Y-m-01').' 00:00:00';
			$to_date = date('Y-m-d').' 23:59:59';
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$from_date_value = '20'.$fy.'-04-01';
			
			if($from_date == $from_date_value){
				$day_before = $from_date_value;
				}else{
				$day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) );
			}
			$first_date = $from_date_value;
			
			
				$sql = 'SELECT tblhistory.TType,tblhistory.TType2,tblhistory.ItemID,
				(SELECT SUM(tblstockmaster.OQty) FROM tblstockmaster WHERE tblstockmaster.ItemID=tblhistory.ItemID AND tblstockmaster.PlantID = '.$selected_company.' AND tblstockmaster.FY = "'.$fy.'" AND tblstockmaster.cnfid = "1" GROUP BY tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY) AS OQty,
				SUM(tblhistory.BilledQty)as billsum FROM `tblhistory` 
				INNER JOIN tblitems ON tblitems.item_code=tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
				WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.FY = "'.$fy.'" AND tblhistory.TransDate2 BETWEEN "'.$first_date.' 00:00:00" AND "'.$day_before.' 23:59:59" AND tblitems.MainGrpID ="'.$MainGroup.'" AND tblhistory.BillID IS NOT NULL';
				
			
			$sql .= ' GROUP BY tblhistory.ItemID,tblhistory.TType,tblhistory.TType2 ';
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		public function get_stock_data($MainGroup)
		{
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			$AllItemList = $this->GetItemList($MainGroup);
			$StockData = $this->GetStockData($MainGroup);
			$StockOQtyData = $this->get_item_open_qty($MainGroup);
			// print_r($StockOQtyData);die;
			/*echo json_encode($AllItemList);
			die;*/
			
            $OQTYCasesSum = 0;
            $PurchQtyCasesSum = 0;
            $PurchRtnQtyCasesSum = 0;
            $IssueQtyCasesSum = 0;
            $PRDCasesSum = 0;
            $SalesCasesSum = 0;
            $SalesRtnCasesSum = 0;
            $AdjCasesSum = 0;
            $GOCasesSum = 0;
            $GICasesSum = 0;
            $BQtySum = 0;
            $stockValue_sum = 0;
            $SrNo = 1;
			foreach ($AllItemList as $key => $value) {
				$rate = 0;
				$OQTY = 0;
				$OQTYCases = 0;
				$PurchQty = 0;
				$PurchQtyCases = 0;
				if($value["case_qty"] == "0"){
					$CaseQty = 1;
					}else{
					$CaseQty = $value["case_qty"];
				}
				
				
				$PurchRtnQty = 0;
				$PurchRtnQtyCases = 0;
				
				$IssueQty = 0;
				$IssueQtyCases = 0;
				
				$PRDQty = 0;
				$PRDCases = 0;
				
				$SalesQty = 0;
				$SalesCases = 0;
				
				$SalesRtnQty = 0;
				$SalesRtnCases = 0;
				
				$AdjQty = 0;
				$AdjCases = 0;
				
				$GOQty = 0;
				$GOCases = 0;
				
				$GIQty = 0;
				$GICases = 0;
				foreach ($StockData as $key1 => $value1) {
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){
						$PurchQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){
						$PurchRtnQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){
						$IssueQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){
						$PRDQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){
						$SalesQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){
						$SalesRtnQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Free distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){
						$AdjQty += $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){
						$GOQty += $value1['BilledQty'];
						$GOValueSum += $value1["SaleRate"] * $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In")){
						$GIQty += $value1['BilledQty'];
						$GIValueSum += $value1["SaleRate"] * $value1['BilledQty'];
						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){
							$rate = $value1["SaleRate"];
						}
					}
				}
				if($PurchQty !== '0'){
					$PurchQtyCases = floatval($PurchQty) / floatval($CaseQty);
					$PurchQtyCasesSum += $PurchQtyCases;
				}
				
				if($PurchRtnQty !== '0'){
					$PurchRtnQtyCases = floatval($PurchRtnQty) / floatval($CaseQty);
					$PurchRtnQtyCasesSum += $PurchRtnQtyCases;
				}
				
				if($IssueQty !== '0'){
					$IssueQtyCases = floatval($IssueQty) / floatval($CaseQty);
					$IssueQtyCasesSum += $IssueQtyCases;
				}
				
				if($PRDQty !== '0'){
					$PRDCases = floatval($PRDQty) / floatval($CaseQty);
					$PRDCasesSum += $PRDCases;
				}
				
				if($SalesQty !== '0'){
					$SalesCases = floatval($SalesQty) / floatval($CaseQty);
					$SalesCasesSum += $SalesCases;
				}
				
				if($SalesRtnQty !== '0'){
					$SalesRtnCases = floatval($SalesRtnQty) / floatval($CaseQty);
					$SalesRtnCasesSum += $SalesRtnCases;
				}
				
				if($AdjQty !== '0'){
					$AdjCases = floatval($AdjQty) / floatval($CaseQty);
					$AdjCasesSum += $AdjCases;
				}
				
				
				if($GOQty >0){
					$GOCases = floatval($GOQty) / floatval($CaseQty);
					$GOCasesSum += $GOCases;
				}
				
				if($GIQty >0){
					$GICases = floatval($GIQty) / floatval($CaseQty);
					$GICasesSum += $GICases;
				}
				$from_date_value = '20'.$fy.'-04-01';
				
				if($from_date == $from_date_value){
					$OQTYCases = floatval($value["OQty"]) / floatval($CaseQty);
					
					}else{
					$OQtySum = 0;
					$OQtySum += floatval($value["OQty"]);
					foreach ($StockOQtyData as $keyOQty => $valueOQty) {
						
						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "P"  && $valueOQty['TType2'] == "Purchase"){
							$OQtySum += $valueOQty['billsum'];
						}
						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "N"){
							$OQtySum -= $valueOQty['billsum'];
						}
						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "A" && $valueOQty['TType2'] == "Issue"){
							$OQtySum -= $valueOQty['billsum'];
						}
						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "B"){
							$OQtySum += $valueOQty['billsum'];
						}
						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "O" && $valueOQty['TType2'] == "Order"){
							$OQtySum -= $valueOQty['billsum'];
						}
						if((strtoupper($valueOQty['ItemID']) == strtoupper($value["item_code"])) && ($valueOQty['TType'] == "R" && $valueOQty["TType2"] == "Fresh")){
							$OQtySum += $valueOQty['billsum'];
						}
						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "X"){
							$OQtySum -= $valueOQty['billsum'];
						}
						
						if(trim((strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"]))) && ($valueOQty['TType'] == "T" && $valueOQty["TType2"] == "Out")){
							$OQtySum -= $valueOQty['billsum'];
						}
						if(trim((strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"]))) && ($valueOQty['TType'] == "T" && $valueOQty["TType2"] == "In")){
							$OQtySum += $valueOQty['billsum'];
						}
					}
					$OQTYCases = floatval($OQtySum) / floatval($CaseQty);
				}
				
				$OQTYCasesSum += $OQTYCases;
				$BQty =    $OQTYCases +  $PurchQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases  - $GOCases + $GICases;
				$BQtySum += $BQty;    
				if(floatval($OQTYCases) == '0.00' && floatval($PurchQtyCases) == "0.00" && floatval($PurchRtnQtyCases) == "0.00" && floatval($IssueQtyCases) == "0.00" && floatval($PRDCases) == "0.00" && floatval($SalesCases) == "0.00" && floatval($SalesRtnCases) == "0.00" && floatval($AdjCases) == "0.00" && floatval($GOCases) == "0.00" && floatval($GICases) == "0.00"){
					
					}else{
					
					
					
					if($value["assigned_rate"] == null || $value["assigned_rate"] == "" || $value["assigned_rate"] == "0.00"){
						//$rate = 0;
						}else{
						$rate = $value["assigned_rate"];
					}
					
					if($value["case_qty"] == '0' || $value["case_qty"] == ''){
						$stockqty = round($BQty) * 1;
						}else{
						$stockqty = round($BQty) * $value["case_qty"];
					}
					
					$stockValue = $stockqty * $rate;
					
					$stockValue_sum = $stockValue_sum + $stockValue;
					
				}
			}
			
            
			return number_format((float)($stockValue_sum));
		}
	}
