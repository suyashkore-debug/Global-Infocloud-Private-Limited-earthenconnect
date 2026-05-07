<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class SplDisc_model extends App_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function GateDiscountDetails($id = '',$order_by = '')
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select(db_prefix() . 'discountmaster.*,'.db_prefix() . 'xx_statelist.state_name');
			$this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'discountmaster.StateID','LEFT');
			//$this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND ' . db_prefix() . 'accountbalances.FY = "'.$FY.'"','LEFT');
			$this->db->where(db_prefix() . 'discountmaster.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'discountmaster.FY', $FY);
			$this->db->from(db_prefix() . 'discountmaster');
			$this->db->where(db_prefix() . 'discountmaster.DiscountID', $id);
			$result = $this->db->get()->row();
			return $result;
		}
		public function GateList($id = '',$order_by = '')
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select(db_prefix() . 'discountmaster.*,'.db_prefix() . 'xx_statelist.state_name');
			$this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'discountmaster.StateID','LEFT');
			//$this->db->join(db_prefix() . 'accountbalances', '' . db_prefix() . 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID AND ' . db_prefix() . 'accountbalances.PlantID = ' . db_prefix() . 'clients.PlantID AND ' . db_prefix() . 'accountbalances.FY = "'.$FY.'"','LEFT');
			$this->db->where(db_prefix() . 'discountmaster.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'discountmaster.FY', $FY);
			$this->db->from(db_prefix() . 'discountmaster');
			if ($id) {
				$this->db->where(db_prefix() . 'discountmaster.DiscountID', $id);
				$result = $this->db->get()->row();
				//return $result;
				if($result){
					$data = array(
                    'FromDate' => _d(substr($result->TransdateFrom,0,10)),
                    'ToDate'  => _d(substr($result->TransdateTo,0,10))
					);
					$GetSaleItemGroup = $this->GetSaleItemGroup($data);
					$GetDiscountItem = $this->GetDiscItem($result->DiscountID);
					$GetDiscountLedger = $this->GetDiscLedger($result->DiscountID,$order_by);
					$company_detail = $this->get_company_detail();
					
					$DiscountItemName = '';
					$discItemGroupID = array();
					
					foreach ($GetDiscountItem as $key1 => $value1) {
						$DiscountItemName = $DiscountItemName .','.$value1['name'];
						array_push($discItemGroupID, $value1['ItemGroupID']);
					}
					$saleDetails = $this->SaleDetails($discItemGroupID,$result->TransdateFrom,$result->TransdateTo,$result->StateID,$result->LocationTypeID);
					//return $saleDetails;
					$FreshRtn = $this->FreshRtnDetails($discItemGroupID,$result->TransdateFrom,$result->TransdateTo,$result->StateID,$result->LocationTypeID);
					$DamageRtn = $this->DamageRtnDetails($discItemGroupID,$result->TransdateFrom,$result->TransdateTo,$result->StateID,$result->LocationTypeID);
					
					$html = '';
					$html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';
					$html .= '<thead style="font-size:11px;">';
					$html .= '<tr>';
					$html .= '<td colspan="8" align="center"><b>'.$company_detail->company_name.'</b></td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td colspan="8" align="center"><b>'.$company_detail->address.'</b></td>';
					$html .= '</tr>';
					
					$html .= '<tr>';
					if($result->LocationTypeID == '1'){
						$LocType = 'Local';
						}else if($result->LocationTypeID == '2'){
						$LocType = 'OutStation';
						}else{
						$LocType = 'notdefine';
					}
					$html .= '<td colspan="8" align="center"><b> Discount Report From'._d(substr($result->TransdateFrom,0,10)).' To '._d(substr($result->TransdateTo,0,10)).'
					for State : '.$result->state_name.' , Location Type : '.$LocType.' </b></td>';
					$html .= '</tr>';
					
					$html .= '<tr>';
					$html .= '<td colspan="8" align="center"><b> DiscountID '.$result->DiscountID.' Discount '.$result->DiscPerc.'
					On : '.$DiscountItemName.' </b></td>';
					$html .= '</tr>';
					
					$html .= '<tr>';
					$html .= '<th>SrNo.</th>';
					$html .= '<th>Account Name</th>';
					$html .= '<th>Station</th>';
					$html .= '<th>SaleAmt</th>';
					$html .= '<th>FreshRtn</th>';
					$html .= '<th>Damages</th>';
					$html .= '<th>NetSale</th>';
					$html .= '<th>Discount%</th>';
					$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
					$i = 1;
					$saleAmtSum = 0;
					$FreshAmtSum = 0;
					$DamageAmtSum = 0;
					$NetSaleSum = 0;
					$DiscAmtSum = 0;
					foreach ($GetDiscountLedger as $key => $value) {
						$html .= '<tr>';
						$html .= '<td align="center">'.$i.'</td>';
						$html .= '<td>'.$value["company"].'</td>';
						$html .= '<td>'.$value["StationName"].'</td>';
						$SaleAmt = '';
						$FreshAmt = '';
						$DamageAmt = '';
						foreach ($saleDetails as $key1 => $value1) {
							if(strtoupper($value["AccountID"]) == strtoupper($value1["AccountID"])){
								$SaleAmt = $value1["SaleSum"];
							}
						}
						
						foreach ($FreshRtn as $key2 => $value2) {
							if(strtoupper($value["AccountID"]) == strtoupper($value2["AccountID"])){
								$FreshAmt = $value2["SaleSum"];
							}
						}
						
						foreach ($DamageRtn as $key3 => $value3) {
							if(strtoupper($value["AccountID"]) == strtoupper($value3["AccountID"])){
								$DamageAmt = $value3["SaleSum"];
							}
						}
						
						$html .= '<td align="right">'.$SaleAmt.'</td>';
						$saleAmtSum += $SaleAmt;
						$html .= '<td align="right">'.$FreshAmt.'</td>';
						$FreshAmtSum += $FreshAmt;
						$html .= '<td align="right">'.$DamageAmt.'</td>';
						$DamageAmtSum += $DamageAmt;
						$NetSale = $SaleAmt - ($FreshAmt + $DamageAmt);
						$NetSaleSum += $NetSale;
						$html .= '<td align="right">'.$NetSale.'</td>';
						$html .= '<td align="right">'.$value["Amount"].'</td>';
						$DiscAmtSum += $value["Amount"];
						$html .= '</tr>';
						$i++;
					}
					// Total 
					$html .= '<tr>';
					$html .= '<td align="right"></td>';
					$html .= '<td align="right" colspan="2"></td>';
					$html .= '<td align="right">'.$saleAmtSum.'</td>';
					$html .= '<td align="right">'.$FreshAmtSum.'</td>';
					$html .= '<td align="right">'.$DamageAmtSum.'</td>';
					$html .= '<td align="right">'.$NetSaleSum.'</td>';
					$html .= '<td align="right">'.$DiscAmtSum.'</td>';
					$html .= '</tr>';
					$html .= '</tbody>';
					$html .= '</table>';
					
					
					$result->DiscItem = $GetDiscountItem;
					$result->DiscLedger = $html;
					$result->GetSaleItemGroup = $GetSaleItemGroup;
				}
				return $result;
			}
			$this->db->order_by(db_prefix() . 'discountmaster.DiscountID', 'DESC');
			return $this->db->get()->result_array();
		}
		
		public function GetItemGroupDetailsByID($ids)
		{
			
			$this->db->where_in('id', $ids);
			return $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
		}
		public function ShowResultForExport($data)
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$FromDate = to_sql_date($data['FromDate']).' 00:00:00';
			$ToDate = to_sql_date($data['ToDate']).' 23:59:59';
			$TransDate = $data['TransDate'];
			$Discper = $data['Discper'];
			$states = $data['states'];
			$loc_type = $data['loc_type'];
			$order_by = $data['order_by'];
			$ItemGroupSerializedArr = $data['ItemGroupSerializedArr'];
			$_ItemGroupSerializedArr = json_decode($ItemGroupSerializedArr, true);
			
			$this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS SaleSum,' . db_prefix() . 'history.AccountID,' . db_prefix() . 'history.TType,' . db_prefix() . 'history.TType2,' . db_prefix() . 'clients.company,' . db_prefix() . 'clients.StationName,');
			$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'history.PlantID','LEFT');
			$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			if($states){
				$this->db->where(db_prefix() . 'clients.state',$states);
			}
			if($loc_type){
				$this->db->where(db_prefix() . 'accountroutes.RouteID',$loc_type);
			}
			$this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"');
			$this->db->where(db_prefix() . 'history.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'history.FY',$FY);
			$this->db->where_in(db_prefix() . 'items.SubGrpID1',$_ItemGroupSerializedArr);
			$this->db->group_by(db_prefix() . 'history.AccountID,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2');
			
			$result = $this->db->get(db_prefix() . 'history')->result_array();
			return $result;
			
		}
		public function AccountIDList($data)
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$FromDate = to_sql_date($data['FromDate']).' 00:00:00';
			$ToDate = to_sql_date($data['ToDate']).' 23:59:59';
			
			$order_by = $data['order_by'];
			
			
			$sql = 'SELECT DISTINCT tblhistory.AccountID FROM `tblhistory`';
			$sql .= ' INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID';
			$sql .= ' WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.TType IN("O","R","D") AND FY = "'.$FY.'" AND tblhistory.TransDate2 BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"';
			if($order_by == '1'){
				$sql .= ' ORDER BY tblclients.StationName ASC';
				}else{
				$sql .= ' ORDER BY tblclients.company ASC';
			}
			$AccountIDList = $this->db->query($sql)->result_array();
			return $AccountIDList;
		}
		public function ShowResult($data)
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$FromDate = to_sql_date($data['FromDate']).' 00:00:00';
			$ToDate = to_sql_date($data['ToDate']).' 23:59:59';
			$TransDate = $data['TransDate'];
			$Discper = $data['Discper'];
			$states = $data['states'];
			$loc_type = $data['loc_type'];
			$order_by = $data['order_by'];
			$ItemGroupSerializedArr = $data['ItemGroupSerializedArr'];
			$_ItemGroupSerializedArr = json_decode($ItemGroupSerializedArr, true);
			
			$this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS SaleSum,' . db_prefix() . 'history.AccountID,' . db_prefix() . 'history.TType,' . db_prefix() . 'history.TType2,' . db_prefix() . 'clients.company,' . db_prefix() . 'clients.StationName,');
			$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'history.PlantID','LEFT');
			$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			if($states){
				$this->db->where(db_prefix() . 'clients.state',$states);
			}
			if($loc_type){
				$this->db->where(db_prefix() . 'accountroutes.RouteID',$loc_type);
			}
			$this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"');
			$this->db->where(db_prefix() . 'history.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'history.FY',$FY);
			$this->db->where_in(db_prefix() . 'items.SubGrpID1',$_ItemGroupSerializedArr);
			$this->db->group_by(db_prefix() . 'history.AccountID,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2');
			
			$result = $this->db->get(db_prefix() . 'history')->result_array();
			
			
			$sql = 'SELECT DISTINCT tblhistory.AccountID FROM `tblhistory`';
			$sql .= ' INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID';
			$sql .= ' WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.TType IN("O","R","D") AND FY = "'.$FY.'" AND tblhistory.TransDate2 BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"';
			if($order_by == '1'){
				$sql .= ' ORDER BY tblclients.StationName ASC';
				}else{
				$sql .= ' ORDER BY tblclients.company ASC';
			}
			$AccountIDList = $this->db->query($sql)->result_array();
			
			$company_detail = $this->get_company_detail();
			
			$html = '';
			$html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			
			
			$html .= '<tr>';
			$html .= '<th>SrNo.</th>';
			$html .= '<th>Account Name</th>';
			$html .= '<th>Station</th>';
			$html .= '<th>SaleAmt</th>';
			$html .= '<th>FreshRtn</th>';
			$html .= '<th>Damages from Sale Rtn</th>';
			$html .= '<th>Damages from Damage Module</th>';
			$html .= '<th>NetSale</th>';
			$html .= '<th>Discount%</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			$SaleSum = 0;
			$FreshAmtSum = 0;
			$DamageAmtSumFromSaleRtn = 0;
			$DamageAmtSumFromDamageModule = 0;
			$NetSaleSum = 0;
			foreach ($AccountIDList as $key => $value) {
				$saleAmt = '';
				$FreshAmt = '';
				$DamageAmtFromSaleRtn = '';
				$DamageAmtFromDamageModule = '';
				$Name = '';
				$Station = '';
				$match = 0;
				foreach ($result as $key1 => $value1) {
					if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "O" && $value1["TType2"] == "Order"){
						$saleAmt = $value1["SaleSum"];
						$Name = $value1["company"];
						$Station = $value1["StationName"];
						$match = 1;
					}
					if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "R" && $value1["TType2"] == "Fresh"){
						$FreshAmt = $value1["SaleSum"];
						$Name = $value1["company"];
						$Station = $value1["StationName"];
						$match = 1;
					}
					
					if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "D" && $value1["TType2"] == "Damage"){
						$DamageAmtFromDamageModule = $value1["SaleSum"];
						$Name = $value1["company"];
						$Station = $value1["StationName"];
						$match = 1;
					}
					if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "R" && $value1["TType2"] == "Damage"){
						$DamageAmtFromSaleRtn = $value1["SaleSum"];
						$Name = $value1["company"];
						$Station = $value1["StationName"];
						$match = 1;
					}
				}
				if($match == "1"){
					if(($saleAmt == "" || $saleAmt == "0") && ($FreshAmt == "" || $FreshAmt == "0") && ($DamageAmt == "" || $DamageAmt == "0")){
						
                        }else{
						$html .= '<tr>';
						$html .= '<td align="center">'.$i.'</td>';
						$html .= '<td>'.$Name.'</td>';
						$html .= '<td>'.$Station.'</td>';
						
						$html .= '<td align="right">'. number_format($saleAmt, 2, '.', '').'</td>';
						$SaleSum += $saleAmt;
						$html .= '<td align="right">'. number_format($FreshAmt, 2, '.', '').'</td>';
						$FreshAmtSum += $FreshAmt;
						$html .= '<td align="right">'. number_format($DamageAmtFromSaleRtn, 2, '.', '').'</td>';
						$DamageAmtSumFromSaleRtn += $DamageAmtFromSaleRtn;
						
						$html .= '<td align="right">'. number_format($DamageAmtFromDamageModule, 2, '.', '').'</td>';
						$DamageAmtSumFromDamageModule += $DamageAmtFromDamageModule;
						
						$NetSale = $saleAmt - ($FreshAmt + $DamageAmtFromSaleRtn + $DamageAmtFromDamageModule);
						$html .= '<td align="right">'. number_format($NetSale, 2, '.', '').'</td>';
						$NetSaleSum += $NetSale;
						
						//Convert our percentage value into a decimal.
						$percentInDecimal = $Discper / 100;
						
						//Get the result.
						$DiscAmt = $percentInDecimal * $NetSale;
						$html .= '<td align="right">'. number_format($DiscAmt, 2, '.', '').'</td>';
						$DiscAmtSum += $DiscAmt;
						$html .= '</tr>';
						$i++;
					}
				}
			}
			$html .= '<tr>';
			$html .= '<td align="right"></td>';
			$html .= '<td align="right">Total</td>';
			$html .= '<td align="right"></td>';
			$html .= '<td align="right">'. number_format($SaleSum, 2, '.', '').'</td>';
			$html .= '<td align="right">'. number_format($FreshAmtSum, 2, '.', '').'</td>';
			$html .= '<td align="right">'. number_format($DamageAmtSumFromSaleRtn, 2, '.', '').'</td>';
			$html .= '<td align="right">'. number_format($DamageAmtSumFromDamageModule, 2, '.', '').'</td>';
			$html .= '<td align="right">'. number_format($NetSaleSum, 2, '.', '').'</td>';
			$html .= '<td align="right">'. number_format($DiscAmtSum, 2, '.', '').'</td>';
			
			$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';
			
            return $html;
			
		}
		
		public function SaveResult($data)
		{
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$DiscID = $data['DiscID'];
			$ToDate = to_sql_date($data['ToDate']).' 23:59:59';
			$TransDate = $data['TransDate'];
			$narration = $data['narration'];
			$Discper = $data['Discper'];
			$states = $data['states'];
			$loc_type = $data['loc_type'];
			$order_by = $data['order_by'];
			$ItemGroupSerializedArr = $data['ItemGroupSerializedArr'];
			$_ItemGroupSerializedArr = json_decode($ItemGroupSerializedArr, true);
			
			$checkDescID = $this->CheckDiscID($DiscID);
			if($checkDescID){
				$Oldmonth = substr($checkDescID[0]['Transdate'],5,2);
				
				$this->db->where('PlantID', $selected_company);
				$this->db->LIKE('FY', $FY);
				$this->db->where('VoucherID', $DiscID);
				$this->db->delete(db_prefix() . 'accountledger');
				
				$DiscArray=array(
                "PlantID"=>$selected_company,
                "FY"=>$FY,
                "TransdateFrom"=>to_sql_date($data['FromDate']).' '.date('H:i:s'),
                "TransdateTo"=>to_sql_date($data['ToDate']).' '.date('H:i:s'),
                "Transdate"=>to_sql_date($data['TransDate']).' '.date('H:i:s'),
                "UserID2"=>$this->session->userdata('username'),
                "DiscPerc"=>$Discper,
                "StateID"=>$states,
                "LocationTypeID"=>$loc_type,
                "Narration"=>$narration,
                "LUpdate"=>date('Y-m-d H:i:s')
				);
				$this->db->where('PlantID', $selected_company);
				$this->db->LIKE('FY', $FY);
				$this->db->where('DiscountID', $DiscID);
				$this->db->update(db_prefix() . 'discountmaster', $DiscArray);
				
				}else{
				$DiscArray=array(
                "PlantID"=>$selected_company,
                "FY"=>$FY,
                "DiscountID"=>$DiscID,
                "TransdateFrom"=>to_sql_date($data['FromDate']).' '.date('H:i:s'),
                "TransdateTo"=>to_sql_date($data['ToDate']).' '.date('H:i:s'),
                "Transdate"=>to_sql_date($data['TransDate']).' '.date('H:i:s'),
                "UserID"=>$this->session->userdata('username'),
                "DiscPerc"=>$Discper,
                "StateID"=>$states,
                "LocationTypeID"=>$loc_type,
                "Narration"=>$narration,
                "PassedFrom"=>"SALESRECEIPT"
				);
				$this->db->insert(db_prefix() . 'discountmaster', $DiscArray);
				$this->increment_next_number();
			}
			
			$this->db->where('PlantID', $selected_company);
			$this->db->LIKE('FY', $FY);
			$this->db->where('DiscountID', $DiscID);
			$this->db->delete(db_prefix() . 'discountitemgroups');
            
			foreach ($_ItemGroupSerializedArr as $ItemID) {
                $itemGroupArray = array(
				"PlantID" =>$selected_company,
				"FY"=>$FY,
				"DiscountID" =>$DiscID,
				"ItemGroupID" =>$ItemID,
                );
                $this->db->insert(db_prefix() . 'discountitemgroups', $itemGroupArray);
			}
            
			$FromDate = to_sql_date($data['FromDate']).' 00:00:00';
			
			$month = substr($FromDate,5,2);
			
            
			
			
			$this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS SaleSum,' . db_prefix() . 'history.AccountID,' . db_prefix() . 'history.TType,' . db_prefix() . 'history.TType2,' . db_prefix() . 'clients.company,' . db_prefix() . 'clients.StationName,');
			$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'history.PlantID','LEFT');
			
			$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			if($states){
				$this->db->where(db_prefix() . 'clients.state',$states);
			}
			if($loc_type){
				$this->db->where(db_prefix() . 'accountroutes.RouteID',$loc_type);
			}
			$this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"');
			$this->db->where(db_prefix() . 'history.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'history.FY',$FY);
			$this->db->where_in(db_prefix() . 'items.SubGrpID1',$_ItemGroupSerializedArr);
			$this->db->group_by(db_prefix() . 'history.AccountID,'.db_prefix() . 'history.TType,'.db_prefix() . 'history.TType2');
			
			$result = $this->db->get(db_prefix() . 'history')->result_array();
			
			
			$sql = 'SELECT DISTINCT tblhistory.AccountID FROM `tblhistory`';
			$sql .= ' INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID';
			$sql .= ' WHERE tblhistory.PlantID = '.$selected_company.' AND tblhistory.TType IN("O","R","D") AND FY = "'.$FY.'" AND tblhistory.TransDate2 BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"';
			if($order_by == '1'){
				$sql .= ' ORDER BY tblclients.StationName ASC';
				}else{
				$sql .= ' ORDER BY tblclients.company ASC';
			}
			$AccountIDList = $this->db->query($sql)->result_array();
			$totalDebit = 0;
			$affectedRow = false;
            foreach ($AccountIDList as $key => $value) {
                $saleAmt = '';
                $FreshAmt = '';
                $DamageAmt = '';
                $match = 0;
                foreach ($result as $key1 => $value1) {
                    if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "O" && $value1["TType2"] == "Order"){
                        $saleAmt = $value1["SaleSum"];
                        $match = 1;
					}
                    if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  $value1["TType"] == "R" && $value1["TType2"] == "Fresh"){
                        $FreshAmt = $value1["SaleSum"];
                        $match = 1;
					}
					
                    if((strtoupper($value["AccountID"]) === strtoupper($value1["AccountID"])) &&  ($value1["TType"] == "D" || $value1["TType"] == "R") && $value1["TType2"] == "Damage"){
                        $DamageAmt = $value1["SaleSum"];
                        $match = 1;
					}
				}
                if($match == "1"){
                    if(($saleAmt == "" || $saleAmt == "0") && ($FreshAmt == "" || $FreshAmt == "0") && ($DamageAmt == "" || $DamageAmt == "0")){
						
						}else{
                        //Convert our percentage value into a decimal.
                        $percentInDecimal = $Discper / 100;
						
                        //Get the result.
                        $NetSale = $saleAmt - ($FreshAmt + $DamageAmt);
                        $DiscAmt = $percentInDecimal * $NetSale;
                        
                        $ledgerdata_credit=array(
						"PlantID"=>$selected_company,
						"FY"=>$FY,
						"Transdate"=>to_sql_date($data['TransDate']).' '.date('H:i:s'),
						"TransDate2"=>date('Y-m-d H:i:s'),
						"VoucherID"=>$DiscID,
						"AccountID"=>$value["AccountID"],
						"EffectOn"=>"CLAIM",
						"TType"=>"C",
						"Amount"=>$DiscAmt,
						"Narration"=>$narration,
						"PassedFrom"=>"SPLDISCOUNT",
						"OrdinalNo"=>1,
						"UserID"=>$this->session->userdata('username')
                        );
                        $this->db->insert(db_prefix() . 'accountledger', $ledgerdata_credit);
						
                        $totalDebit += $DiscAmt;
                        $ledgerdata_Debit=array(
						"PlantID"=>$selected_company,
						"FY"=>$FY,
						"Transdate"=>to_sql_date($data['TransDate']).' '.date('H:i:s'),
						"TransDate2"=>date('Y-m-d H:i:s'),
						"VoucherID"=>$DiscID,
						"AccountID"=>'CLAIM',
						"EffectOn"=>$value["AccountID"],
						"TType"=>"D",
						"Amount"=>$DiscAmt,
						"Narration"=>$narration,
						"PassedFrom"=>"SPLDISCOUNT",
						"OrdinalNo"=>2,
						"UserID"=>$this->session->userdata('username')
                        );
                        $this->db->insert(db_prefix() . 'accountledger', $ledgerdata_Debit);
                        $affectedRow = true;
					}
				}
			}
            
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $FY);
            $this->db->where('DiscountID', $DiscID);
            $this->db->update(db_prefix() . 'discountmaster', [
			'DiscAmt' => $totalDebit,
			]);
            
            if($affectedRow == true){
                if(empty($checkDescID)){
                    $DD = substr($DiscID,3);
                    $NDD = $DD +1;
                    $prefix = "DIS";
                    $_new_DiscNumberNew = $prefix.$NDD;
					}else{
                    $_new_DiscNumberNew = true;
				} 
			}
            return $_new_DiscNumberNew; 
		}
		
		public function increment_next_number()
		{
			// Update next Disc number in settings
			$FY = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			if($selected_company == 1){
				$this->db->where('name', 'next_spldisc_number_for_cspl');
				}elseif($selected_company == 2){
				$this->db->where('name', 'next_spldisc_number_for_cff');
				}elseif($selected_company == 3){
				$this->db->where('name', 'next_spldisc_number_for_cbu');
				}elseif($selected_company == 4){
				$this->db->where('name', 'next_spldisc_number_for_cbupl');
			}
			$this->db->set('value', 'value+1', false);
			$this->db->WHERE('FY', $FY);
			$this->db->update(db_prefix() . 'options');
		}
		public function GetAccountBal($id)
		{
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$this->db->where('PlantID', $selected_company);
			$this->db->LIKE('FY', $fy);
			$this->db->where('AccountID', $id);
			
			return $this->db->get(db_prefix() . 'accountbalances')->row();
		}
		public function SaleDetails($discItemGroupID,$TransdateFrom,$TransdateTo,$StateID,$LocationTypeID)
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$From = substr($TransdateFrom,0,10).' 00:00:00';
			$To = substr($TransdateTo,0,10).' 23:59:59';
			$this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS SaleSum,' . db_prefix() . 'history.AccountID');
			if($StateID !== ""){
				$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
				$this->db->where(db_prefix() . 'clients.state',$StateID);
			}
			if($LocationTypeID){
				$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'history.PlantID','LEFT');
				$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'history.PlantID');
				$this->db->where(db_prefix() . 'accountroutes.RouteID',$LocationTypeID);
			}
			$this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->where(db_prefix() . 'history.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'history.FY',$FY);
			$this->db->where_in(db_prefix() . 'items.SubGrpID1',$discItemGroupID);
			$this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$From.'" AND "'.$To.'"');
			$this->db->where(db_prefix() . 'history.TType','O');
			$this->db->where(db_prefix() . 'history.TType2','Order');
			$this->db->group_by(db_prefix() . 'history.AccountID');
			$result = $this->db->get(db_prefix() . 'history')->result_array();
			return $result;
		}
		
		public function DamageRtnDetails($discItemGroupID,$TransdateFrom,$TransdateTo,$StateID,$LocationTypeID)
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$From = substr($TransdateFrom,0,10).' 00:00:00';
			$To = substr($TransdateTo,0,10).' 23:59:59';
			$this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS SaleSum,' . db_prefix() . 'history.AccountID');
			if($StateID !== ""){
				$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
				$this->db->where(db_prefix() . 'clients.state',$StateID);
			}
			if($LocationTypeID){
				$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'history.PlantID','LEFT');
				
				$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'history.PlantID');
				$this->db->where(db_prefix() . 'accountroutes.RouteID',$LocationTypeID);
			}
			$this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->where(db_prefix() . 'history.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'history.FY',$FY);
			$this->db->where_in(db_prefix() . 'items.SubGrpID1',$discItemGroupID);
			$this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$From.'" AND "'.$To.'"');
			//$this->db->where(db_prefix() . 'history.TType','R');
			$this->db->where(db_prefix() . 'history.TType2','Damage');
			$this->db->group_by(db_prefix() . 'history.AccountID');
			$result = $this->db->get(db_prefix() . 'history')->result_array();
			return $result;
		}
		
		public function FreshRtnDetails($discItemGroupID,$TransdateFrom,$TransdateTo,$StateID,$LocationTypeID)
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			$From = substr($TransdateFrom,0,10).' 00:00:00';
			$To = substr($TransdateTo,0,10).' 23:59:59';
			$this->db->select('SUM('.db_prefix() . 'history.NetChallanAmt) AS SaleSum,' . db_prefix() . 'history.AccountID');
			if($StateID !== ""){
				$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID');
				$this->db->where(db_prefix() . 'clients.state',$StateID);
			}
			if($LocationTypeID){
				$this->db->join(db_prefix() . 'accountlocations', '' . db_prefix() . 'accountlocations.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountlocations.PlantID = ' . db_prefix() . 'history.PlantID','LEFT');
				$this->db->join(db_prefix() . 'accountroutes', '' . db_prefix() . 'accountroutes.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'accountroutes.PlantID = ' . db_prefix() . 'history.PlantID');
				$this->db->where(db_prefix() . 'accountroutes.RouteID',$LocationTypeID);
			}
			$this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID');
			$this->db->where(db_prefix() . 'history.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'history.FY',$FY);
			$this->db->where_in(db_prefix() . 'items.SubGrpID1',$discItemGroupID);
			$this->db->where(db_prefix() . 'history.TransDate2 BETWEEN "'.$From.'" AND "'.$To.'"');
			$this->db->where(db_prefix() . 'history.TType','R');
			$this->db->where(db_prefix() . 'history.TType2','Fresh');
			$this->db->group_by(db_prefix() . 'history.AccountID');
			$result = $this->db->get(db_prefix() . 'history')->result_array();
			return $result;
		}
		
		public function GetDiscItem($DiscountID)
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select(db_prefix() . 'discountitemgroups.*,' . db_prefix() . 'items_sub_groups.name');
			$this->db->join(db_prefix() . 'items_sub_groups', '' . db_prefix() . 'items_sub_groups.id = ' . db_prefix() . 'discountitemgroups.ItemGroupID');
			$this->db->where(db_prefix() . 'discountitemgroups.DiscountID',$DiscountID);
		$this->db->where(db_prefix() . 'discountitemgroups.PlantID',$selected_company);
		$this->db->where(db_prefix() . 'discountitemgroups.FY',$FY);
		$result = $this->db->get(db_prefix() . 'discountitemgroups')->result_array();
		return $result;
		}
		
		public function GetDiscLedger($DiscountID,$order_by)
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select(db_prefix() . 'accountledger.*,'.db_prefix() . 'clients.company,'.db_prefix() . 'clients.StationName');
			$this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'accountledger.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'accountledger.PlantID');
			$this->db->where(db_prefix() . 'accountledger.VoucherID',$DiscountID);
			$this->db->where(db_prefix() . 'accountledger.TType','C');
			$this->db->where(db_prefix() . 'accountledger.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'accountledger.FY',$FY);
			if($order_by == '1'){
				$this->db->order_by(db_prefix() . 'clients.StationName','ASC');
				}else{
				$this->db->order_by(db_prefix() . 'clients.company','ASC');
			}
			$result = $this->db->get(db_prefix() . 'accountledger')->result_array();
			return $result;
		}
		
		public function get_company_detail()
		{   
			$selected_company = $this->session->userdata('root_company');
			$sql ='SELECT '.db_prefix().'rootcompany.*
			FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
			$result = $this->db->query($sql)->row();
			return $result;
			
		}
		
		// Get All Item Group
		public function GetAllItemGroup()
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select(db_prefix() . 'items_sub_groups.*');
			$this->db->where(db_prefix() . 'items_sub_groups.main_group_id','1');/*
				$this->db->where(db_prefix() . 'items_sub_groups.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'items_sub_groups.FY',$FY);*/
			$this->db->order_by('name','ASC');
			$result = $this->db->get(db_prefix() . 'items_sub_groups')->result_array();
			return $result;
		}
		
		public function GetSaleItemGroup($data)
		{  
			$FromDate = to_sql_date($data["FromDate"]);
			$ToDate = to_sql_date($data["ToDate"]);
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '(Transdate2 BETWEEN "'.$FromDate.' 00:00:00" AND "'.$ToDate.' 23:59:59")';
			$sql1 .= ' AND PlantID = "'.$selected_company.'" AND FY = "'.$fy.'" AND TType IN("O","D","R")';
			
			$sql ='SELECT '.db_prefix().'history.* FROM '.db_prefix().'history WHERE '.$sql1;
			$result = $this->db->query($sql)->result_array();
			if(empty($result)){
				return $result;
			}
			
			$item_ids = array();
			foreach ($result as $key => $value) {
				array_push($item_ids, $value["ItemID"]);
			}
			if(empty($item_ids)){
				
				}else{
				$item_ids_uniqu = array_unique($item_ids);
				
				$this->db->select('*');
				$this->db->from(db_prefix() . 'items');
				$this->db->where(db_prefix() . 'items.PlantID', $selected_company);
				$this->db->where_in('item_code',$item_ids_uniqu);
				$result3 = $this->db->get()->result_array();
				
				$item_group_ids = array();
				foreach ($result3 as $key3 => $value3) {
					array_push($item_group_ids, $value3["SubGrpID1"]);
				}
				$item_group_ids_uniqu = array_unique($item_group_ids);
				
				$this->db->select('*');
				$this->db->from(db_prefix() . 'items_sub_groups');
				$this->db->where_in('id',$item_group_ids_uniqu);
				$this->db->order_by('name','ASC');
				$result4 = $this->db->get()->result_array();
				
				return $result4;
			}
		}
		
		// Check DiscID
		public function CheckDiscID($DiscID)
		{  
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			$this->db->select(db_prefix() . 'accountledger.AccountID,SUM('.db_prefix() . 'accountledger.Amount),'.db_prefix() . 'accountledger.Transdate,'.db_prefix() . 'accountledger.TType');
			$this->db->where(db_prefix() . 'accountledger.VoucherID',$DiscID);
			$this->db->where(db_prefix() . 'accountledger.PlantID',$selected_company);
			$this->db->where(db_prefix() . 'accountledger.FY',$FY);
			$this->db->group_by('AccountID,TType');
			$this->db->order_by('TType','ASC');
			$result = $this->db->get(db_prefix() . 'accountledger')->result_array();
			return $result;
		}
	}
