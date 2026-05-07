<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Stock_a_model extends App_Model
	{
		public function __construct()
		{ 
			parent::__construct();
		}
		public function get_vendor_data($id = '', $where = [])
		{
			
			$selected_company = $this->session->userdata('root_company');
			
			$this->db->select( db_prefix() . 'clients.company,'.db_prefix() . 'clients.userid,'.db_prefix() . 'clients.AccountID,');
			
			$this->db->where_in(db_prefix() . 'clients.SubActGroupID', ['1000012','1000058']);
			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
			$this->db->order_by('company', 'asc');
			return $this->db->get(db_prefix() . 'clients')->result_array();
		}
		
		public function get_items_code(){
			$selected_company = $this->session->userdata('root_company');
			//   $year = $_SESSION['finacial_year'];
			return $this->db->query('select id as id, CONCAT(item_code," - ",description) as label,item_code from '.db_prefix().'items where PlantID = '.$selected_company)->result_array();
		}
		public function get_data_vendor($id = '')
		{
			//   return $this->db->get_where('clients',array('userid' =>$id))->row();
			$selected_company = $this->session->userdata('root_company');
			$year = $_SESSION['finacial_year'];
			$this->db->select();
			$this->db->from(db_prefix() . 'clients');
			$this->db->join(db_prefix() . 'xx_citylist', db_prefix() . 'xx_citylist.id = ' . db_prefix() . 'clients.city', 'left');
			$this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
			$this->db->join('accountbalances', 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID', 'left');
			$this->db->where(db_prefix() . 'clients.userid', $id);
			$this->db->where('accountbalances.PlantID', $selected_company);
			$this->db->where('accountbalances.FY', $year);
			return $this->db->get()->row();
			//   echo $this->db->last_query();
			
		}
		public function load_data_for_stock_adj($data)
		{  
			$from_date = to_sql_date($data["from_date"]);
			$to_date = to_sql_date($data["to_date"]);
			$fy = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
			
			$sql1 = '('.db_prefix().'stockadjmaster.Transdate BETWEEN "'.$from_date.' 00:00:00" AND "'.$to_date.' 23:59:59")  AND '.db_prefix().'stockadjmaster.FY = "'.$fy.'" AND '.db_prefix().'stockadjmaster.PlantID = "'.$selected_company.'" ORDER BY Transdate,AdjID ASC';
			
			$sql ='SELECT '.db_prefix().'stockadjmaster.*,  
			(SELECT GROUP_CONCAT(company SEPARATOR ",") FROM '.db_prefix().'clients WHERE '.db_prefix().'clients.AccountID = '.db_prefix().'stockadjmaster.AccountID AND '.db_prefix().'clients.PlantID = '.$selected_company.') as AccountName, 
			(SELECT GROUP_CONCAT(SLDTypeName SEPARATOR ",") FROM '.db_prefix().'sldtypes WHERE '.db_prefix().'sldtypes.SLDTypeID = '.db_prefix().'stockadjmaster.AdjTypeID AND '.db_prefix().'sldtypes.FilterTypeID ="STKADJ") as adjType
			FROM '.db_prefix().'stockadjmaster WHERE '.$sql1;
			$result = $this->db->query($sql)->result_array();
			return $result;
		}
		public function items_change($code){
			// $this->db->where('id',$code);
			// $rs = $this->db->get(db_prefix().'items')->row();
			$selected_company = $this->session->userdata('root_company'); 
			$this->db->select();
			$this->db->from(db_prefix() . 'items');
			$this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax', 'left');
			$this->db->where(db_prefix() . 'items.id', $code);
			$this->db->where(db_prefix() .'items.PlantID', $selected_company);
			$rs = $this->db->get()->row();
			
			$sql="SELECT * FROM `tblitems` 
			LEFT JOIN `tbltaxes` ON `tbltaxes`.`id` = `tblitems`.`tax`
			LEFT JOIN `tblitems_sub_groups` ON `tblitems_sub_groups`.`id` = `tblitems`.`SubGrpID1` 
			LEFT JOIN `tblitems_main_groups` ON `tblitems_main_groups`.`id` = `tblitems_sub_groups`.`main_group_id` 
			WHERE `tblitems`.`id` = '".$code."' AND `tblitems`.`PlantID` = '".$selected_company."'";    
			$query = $this->db->query($sql);
			return  $query->row();
			//   echo $this->db->last_query();die;
			// return $rs;
			$this->db->where('unit_type_id',$rs->unit_id);
			$unit = $this->db->get(db_prefix().'ware_unit_type')->row();
			
			if($unit){
				$rs->unit = $unit->unit_name;
				}else{
				$rs->unit = '';
			}
			
			if(get_status_modules_pur('warehouse') == true){
				$this->db->where('commodity_id',$code);
				$commo = $this->db->get(db_prefix().'inventory_manage')->result_array();
				$rs->inventory = 0;
				if(count($commo) > 0){
					foreach($commo as $co){
						$rs->inventory += $co['inventory_number'];
					}
				}       
				}else{
				$rs->inventory = 0;
			}
			
			return $rs;
		}
		public function get_basic_r($item_code,$group_id='',$state=''){
			$selected_company = $this->session->userdata('root_company'); 
			$this->db->select();
			$this->db->from(db_prefix() . 'rate_master'); 
			$this->db->where(db_prefix() . 'rate_master.state_id', $state);
			$this->db->where(db_prefix() . 'rate_master.distributor_id', $group_id);
			$this->db->where(db_prefix() . 'rate_master.item_id', $item_code);
			$this->db->where(db_prefix() .'rate_master.PlantID', $selected_company);
			$rs = $this->db->get()->row();if(empty($rs)){
				$ttype = array('O','P');
				$this->db->select(db_prefix() . 'history.BasicRate AS assigned_rate');
				$this->db->from(db_prefix() . 'history'); 
				$this->db->where(db_prefix() . 'history.ItemID', $item_code);
				$this->db->where_in(db_prefix() . 'history.TType', $ttype);
				$this->db->where(db_prefix() .'history.PlantID', $selected_company);
				$this->db->order_by(db_prefix() . 'history.TransDate', 'DESC');
				$rs = $this->db->get()->row();
			}
			return $rs;
			
		} 
		
		public function get_accountlocations($accountId){
			$selected_company = $this->session->userdata('root_company'); 
			$this->db->select();
			$this->db->from(db_prefix() . 'accountlocations');
			$this->db->where(db_prefix() . 'accountlocations.AccountID', $accountId);
			$this->db->where(db_prefix() .'accountlocations.PlantID', $selected_company);
			$rs = $this->db->get()->row();
			return $rs;
		} 
		
		
		public function add_stock_aadjustment($data){
			$data_i ='';
			if(isset($data['pur_order_detail'])){
				$pur_order_detail = json_decode($data['pur_order_detail']);
				
				unset($data['pur_order_detail']);
				$es_detail = [];
				$row = [];
				$rq_val = [];
				$header = [];
				$header[] = 'item_code';
				$header[] = 'description';
				$header[] = 'Cases/creats';
				$header[] = 'PackQty';
				$header[] = 'Cases';
				$header[] = 'QTY';
				$header[] = 'BasicRate';
				$header[] = 'GST';
				$header[] = 'CGSTAMT';
				$header[] = 'SGSTAMT';
				$header[] = 'IGSTAMT';
				$header[] = 'total';
				foreach ($pur_order_detail as $key => $value) {
					
					if($value[0] != ''){
						$es_detail[] = array_combine($header, $value);
					}
				}
			}
			
			$acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
			
			$PlantID = $this->session->userdata('root_company'); 
			$FY = $this->session->userdata('finacial_year'); 
			
			
			if($PlantID == 1){
                $stock_adj_orderNumbar = get_option('next_stock_adj_number_for_cspl');
				}elseif($PlantID == 2){
                $stock_adj_orderNumbar = get_option('next_stock_adj_number_for_cff');
				}elseif($PlantID == 3){
				$stock_adj_orderNumbar = get_option('next_stock_adj_number_for_cbu');
				}elseif($PlantID == 4){
				$stock_adj_orderNumbar = get_option('next_stock_adj_number_for_cbupl');
			}
			$new_stock_adj_orderNumbar = 'ADJ'.$FY.$stock_adj_orderNumbar;   
			//   echo $data['prd_date'];
			$Transdate =  to_sql_date($data['prd_date'])." ".date('H:i:s');
			if($data['adj_type'] == "Free Distribution"){
				$adj_type = 1;
				}else if($data['adj_type'] == "Promotional Activity"){
				$adj_type = 2;
				}else if($data['adj_type'] == "Stock Adjustment"){
				$adj_type = 3;
				}else if($data['adj_type'] == "Stock Damaged"){
				$adj_type = 4;
			}
			$Invamt =  str_replace(",","",$data['Invoice_amt']);
			$data_array = array(
            'PlantID'=>$PlantID,
            'FY'=>$FY,
            'AdjID' =>$new_stock_adj_orderNumbar,
            'Transdate' =>$Transdate,
            'AdjTypeID' =>$adj_type,
            'AccountID'=>$acc_id->AccountID,
            'AdjAmt'=>$data['adjust_value'],
            'UserID'=>$_SESSION['username'],
            'cnfid'=>1,
            );
            
			$this->db->insert(db_prefix() . 'stockadjmaster',$data_array);
			
			if($this->db->affected_rows() > 0){
				
				$data_remark = array(
				'PlantID'=>$PlantID,
				'FY'=>$FY,
				'TransID' =>$new_stock_adj_orderNumbar,
				'PassedFrom' =>"STOCKADJ",
				'Remarks' => $data['reason_for_Adj']
				);
				$this->db->insert(db_prefix() . 'remarks',$data_remark);
				$this->increment_next_number();
				if($PlantID == "1"){
					$GodownID = 'CSPL';
					}else if($PlantID == "2"){
					$GodownID = 'CFF';
					}else if($PlantID == "3"){
					$GodownID = 'CBUPL';
				}
				$i =1;
				foreach($es_detail as $value){
					//   print_r($value);
					$item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$PlantID))->row();
					
					
					if($value['Cases/creats'] == 'Case'){
						$SuppliedIn =  'CS';
					}else if(($value['Cases/creats'] == 'Crate')){
						$SuppliedIn =  'CR';
					}else{
						$SuppliedIn =  '';
					}
					
					$basic_rate = $value['BasicRate'];
					$gst_amt = ($value['BasicRate'] * $value['GST']) / 100;
					$sale_rate = $basic_rate + $gst_amt;
					
					$gst_devide = 0;
					$gst_devide_amt = 0;
					$gst_igst = 0;
					$gst_igst_amt = 0;
					if($data['state_id'] == 'UP'){
						$gst_devide =  $value['GST']/2;
						$gst_devide_amt =  ($value['QTY']*$gst_amt)/2;
						}else{
						$gst_igst = $value['GST'];
						$gst_igst_amt = $value['QTY']*$gst_amt;
					}
					$gst_amt_total = ($gst_amt * $value['QTY']);
					$data_array_result = array(
					'PlantID'=>$PlantID,
					'FY'=>$FY,
					'cnfid' =>1,
					'GodownID' =>$GodownID,
					'OrderID' =>$new_stock_adj_orderNumbar,
					'TransID' =>$new_stock_adj_orderNumbar,
					'TransDate' =>$Transdate,
					'BillID' =>$new_stock_adj_orderNumbar,
					'TransDate2'=>$Transdate,
					'TType'=>'X',
					'TType2'=> $data['adj_type'],
					'AccountID'=> $acc_id->AccountID,
					'ItemID'=>$item_c->item_code,
					'CaseQty'=>$value['PackQty'],
					'SaleRate'=>$sale_rate,
					'BasicRate'=>$basic_rate,
					'SuppliedIn'=>$SuppliedIn,
					'BilledQty'=>$value['QTY'],
					'gst'=>$value['GST'],
					'gstamt'=>$gst_amt_total,
					'cgst'=>$gst_devide,
					'cgstamt'=>$gst_devide_amt,
					'sgst'=>$gst_devide,
					'sgstamt'=>$gst_devide_amt,
					'igst'=>$gst_igst,
					'igstamt'=>$gst_igst_amt,
					'ChallanAmt'=>$value['total'],
					'Ordinalno'=>$i,
					'UserID'=>$_SESSION['username'],
					);
					// print_r($data_array_result);die;
					$data_i = $this->db->insert(db_prefix() . 'history',$data_array_result);
					//echo $this->db->last_query();
					
					$i++;
					
				}
				// die;
				return true;
			}
			return false;
			// return $data_i;
			//die;
		}
		public function get_stock_list(){
			$selected_company = $this->session->userdata('root_company');
			$year = $_SESSION['finacial_year'];
			$this->db->select();
			$this->db->from(db_prefix() . 'stockadjmaster');
			$this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'stockadjmaster.AccountID', 'left');
			//  $this->db->where(db_prefix() . 'clients.userid', $id);
			$this->db->where(db_prefix() . 'stockadjmaster.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'stockadjmaster.FY', $year);
			$this->db->order_by(db_prefix() . 'stockadjmaster.AdjID', "DESC");
			return $this->db->get()->result_array();
		}
		
		public function get_adj_stock($itemID)
		{
			$selected_company = $this->session->userdata('root_company');
			if($selected_company == "1"){
				$GodownID = 'CSPL';
				}else if($selected_company == "2"){
				$GodownID = 'CFF';
				}else if($selected_company == "3"){
				$GodownID = 'CBUPL';
			}
			$fy = $this->session->userdata('finacial_year');
			$this->db->where('PlantID', $selected_company);
			$this->db->LIKE('FY', $fy);
			$this->db->where('GodownID',$GodownID);
			$this->db->where('ItemID', $itemID);
			
			return $this->db->get(db_prefix() . 'stockmaster')->row();
		}
		
		
		public function get_stock_order_detail($request,$group_id,$state){
			$selected_company = $this->session->userdata('root_company');
			$year = $_SESSION['finacial_year'];
			
			$this->db->select(db_prefix() . 'history.*,tblhistory.CaseQty AS PackQty,'.db_prefix() . 'items.*,'.db_prefix() . 'clients.*,'.db_prefix() . 'taxes.taxrate');
			$this->db->from(db_prefix() . 'history');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID', 'left');
			$this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND '.db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID', 'left');
			
			$this->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax', 'left');
			$this->db->where(db_prefix() . 'history.OrderID', $request);
			
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'items.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.FY', $year);
			$this->db->where(db_prefix() . 'history.TType', 'X');
			$this->db->order_by(db_prefix() . 'history.Ordinalno', 'ASC');
			$data = $this->db->get()->result_array();
			
			foreach($data  as $key => $value){
				$data[$key]['cgst_per'] = $data[$key]['cgst'];
				$data[$key]['sgst_per'] = $data[$key]['sgst'];
				$data[$key]['igst_per'] = $data[$key]['igst'];
				$data[$key]['taxrate']  = $data[$key]['gst'];
				
				$data[$key]['all_qty'] =  $data[$key]['BilledQty'];
				$data[$key]['CaseQty'] =  $data[$key]['BilledQty'] / $data[$key]['PackQty'];
				$data[$key]['case_qty'] =  $data[$key]['PackQty'];
				if($data[$key]['SuppliedIn'] == 'CS'){
					$data[$key]['SuppliedIn_data'] = 'Case';
				}else if($data[$key]['SuppliedIn'] == 'CR') {
					$data[$key]['SuppliedIn_data'] = 'Crate';
				}else{
					$data[$key]['SuppliedIn_data'] = ''; 
				}
			}
			return $data;
			
		}
		public function get_unique_stock_master($id){
			$selected_company = $this->session->userdata('root_company');
			$year = $_SESSION['finacial_year'];
			$this->db->select();
			$this->db->from(db_prefix() . 'stockadjmaster');
			$this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.AccountID = ' . db_prefix() . 'stockadjmaster.AccountID', 'left');
			$this->db->join(db_prefix() . 'xx_statelist', db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'clients.state', 'left');
			$this->db->join(db_prefix() . 'remarks', db_prefix() . 'remarks.TransID = ' . db_prefix() . 'stockadjmaster.AdjID AND '.db_prefix() . 'remarks.PlantID = ' . db_prefix() . 'stockadjmaster.PlantID AND '.db_prefix() . 'remarks.FY  = ' . db_prefix() . 'stockadjmaster.FY AND ' . db_prefix() . 'remarks.PassedFrom = "STOCKADJ"', 'left');
			// $this->db->join('accountbalances', 'accountbalances.AccountID = ' . db_prefix() . 'clients.AccountID', 'left');
			$this->db->where(db_prefix() . 'stockadjmaster.AdjID', $id);
			$this->db->where(db_prefix() . 'stockadjmaster.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'clients.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'stockadjmaster.FY', $year);
			return $this->db->get()->row();
		}
		public function get_total_cases($id){
			$selected_company = $this->session->userdata('root_company'); 
			$year = $_SESSION['finacial_year'];
			$this->db->select();
			$this->db->from(db_prefix() . 'history');
			$this->db->join(db_prefix() . 'items', db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND '.db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID', 'left');
			
			$this->db->where(db_prefix() . 'history.OrderID', $id);
			$this->db->where(db_prefix() .'history.FY', $year);
			$this->db->where(db_prefix() .'history.PlantID', $selected_company);
			$this->db->where(db_prefix() .'items.PlantID', $selected_company);
			return $rs = $this->db->get()->result();
			
		}
		public function get_purchase_detail($id){
			$selected_company = $this->session->userdata('root_company');
			$year = $this->session->userdata('finacial_year');
			$this->db->select();
			$this->db->from(db_prefix() . 'history');
			$this->db->where(db_prefix() . 'history.PlantID', $selected_company);
			$this->db->where(db_prefix() . 'history.FY', $year);
			$this->db->where(db_prefix() . 'history.BillID', $id);
			return $this->db->get()->result_array();
		}
		public function update_stock_adj($data,$id)
		{
			$selected_company = $this->session->userdata('root_company');
			if($selected_company == "1"){
				$GodownID = 'CSPL';
				}else if($selected_company == "2"){
				$GodownID = 'CFF';
				}else if($selected_company == "3"){
				$GodownID = 'CBUPL';
			}
			$fy = $this->session->userdata('finacial_year');
			$data_i ='';
			if(isset($data['pur_order_detail'])){
				$pur_order_detail = json_decode($data['pur_order_detail']);
				unset($data['pur_order_detail']);
				$es_detail = [];
				$row = [];
				$rq_val = [];
				$header = [];
				
				$header[] = 'item_code';
				$header[] = 'description';
				$header[] = 'Cases/creats';
				$header[] = 'PackQty';
				$header[] = 'Cases';
				$header[] = 'QTY';
				$header[] = 'BasicRate';
				$header[] = 'GST';
				$header[] = 'CGSTAMT';
				$header[] = 'SGSTAMT';
				$header[] = 'IGSTAMT';
				$header[] = 'total';
				foreach ($pur_order_detail as $key => $value) {
					
					if($value[0] != ''){
						$es_detail[] = array_combine($header, $value);
					}
				}
			}
			
			$acc_id = $this->db->select('AccountID')->get_where(db_prefix().'clients',array('userid'=>$data['vendor']))->row();
			$old_pur_details = $this->stock_a_model->get_purchase_detail($id);
			$PlantID = $this->session->userdata('root_company'); 
			$FY = $this->session->userdata('finacial_year'); 
			if($data['adj_type'] == "Free distribution"){
				$adj_type = 1;
				}else if($data['adj_type'] == "Promotional Activity"){
				$adj_type = 2;
				}else if($data['adj_type'] == "Stock Adjustment"){
				$adj_type = 3;
				}else if($data['adj_type'] == "Stock Damaged"){
				$adj_type = 4;
			}
			$Transdate =  to_sql_date($data['prd_date'])." ".date('H:i:s');
			$data_array = array(
            'AdjAmt'=>$data['adjust_value'],
            'AdjTypeID' =>$adj_type,
            'Transdate' =>$Transdate,
            'UserID2'=>$_SESSION['username'],
            'Lupdate'=>date('Y-m-d H:i:s'),
            'AccountID'=>$acc_id->AccountID,
            );
            $this->db->where('PlantID', $selected_company);
            $this->db->LIKE('FY', $fy);
            $this->db->where('AdjID',$id);
            $this->db->update(db_prefix() . 'stockadjmaster',$data_array);
			
			if($this->db->affected_rows() > 0){
				
				$data_remark = array(
				'Remarks'=>$data['reason_for_Adj'],
				'UserID2'=>$_SESSION['username'],
				'Lupdate'=>date('Y-m-d H:i:s'),
				);
				$this->db->where('PlantID', $selected_company);
				$this->db->LIKE('FY', $fy);
				$this->db->where('TransID',$id);
				$this->db->where('PassedFrom','STOCKADJ');
				$this->db->update(db_prefix() . 'remarks',$data_remark);
				$new_items = array();
				$deleted_item = array();
				foreach($es_detail as $value){
                    # code...
                    $item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$selected_company))->row();
                    
                    array_push($new_items, $item_c->item_code);
				}
				$old_item_code = array();
				foreach ($old_pur_details as $key => $value) {
					array_push($old_item_code, $value["ItemID"]);
					if (!in_array($value["ItemID"], $new_items)){
						array_push($deleted_item, $value["ItemID"]);
					}  
				}
				
				
				$i =1;
				foreach($es_detail as $value){
					$item_c =  $this->db->get_where(db_prefix().'items',array('id'=>$value['item_code'],'PlantID'=>$PlantID))->row();
					
					if (in_array($item_c->item_code, $old_item_code)){
						
						$basic_rate = $value['BasicRate'];
						$gst_amt = ($value['BasicRate'] * $value['GST']) / 100;
						$sale_rate = $basic_rate + $gst_amt;    
						
						$gst_devide = 0;
						$gst_devide_amt = 0;
						$gst_igst = 0;
						$gst_igst_amt = 0;
						if($data['state_id'] == 'UP'){
							$gst_devide =  $value['GST']/2;
							$gst_devide_amt =  ($value['QTY']*$gst_amt)/2;
							}else{
							$gst_igst = $value['GST'];
							$gst_igst_amt = $value['QTY']*$gst_amt;
						}
						$gst_amt_total = ($gst_amt * $value['QTY']);
						
						if($value['Cases/creats'] == "Case"){
						    $SuppliedIn = "CS";
						}else{
						    $SuppliedIn = "CR";
						}
						$data_array_result = array(
						'ItemID'=>$item_c->item_code,
						'CaseQty'=>$value['PackQty'],
						'TType2'=> $data['adj_type'],
						'SaleRate'=>$sale_rate,
						'TransDate2'=>$Transdate,
						'BasicRate'=>$value['BasicRate'],
						'SuppliedIn'=>$SuppliedIn,
						'gst'=>$value['GST'],
						'gstamt'=>$gst_amt_total,
						'cgst'=>$gst_devide,
						'cgstamt'=>$gst_devide_amt,
						'sgst'=>$gst_devide,
						'sgstamt'=>$gst_devide_amt,
						'igst'=>$gst_igst,
						'igstamt'=>$gst_igst_amt,
						'BilledQty'=>$value['QTY'],
						'ChallanAmt'=>$value['total'],
						'Ordinalno'=>$i,
						'UserID2'=>$_SESSION['username'],
						'Lupdate'=>date('Y-m-d H:i:s'),
						'AccountID'=>$acc_id->AccountID,
						);
						
						$this->db->where('OrderID',$id);
						$this->db->where('ItemID',$item_c->item_code);
						$this->db->where('PlantID', $selected_company);
						$this->db->LIKE('FY', $fy);
						$this->db->update(db_prefix() . 'history',$data_array_result);
						
						}else{
						
                        
						$basic_rate = $value['BasicRate'];
						$gst_amt = ($value['BasicRate'] * $value['GST']) / 100;
						$sale_rate = $basic_rate + $gst_amt;    
						
						$gst_devide = 0;
						$gst_devide_amt = 0;
						$gst_igst = 0;
						$gst_igst_amt = 0;
						if($data['state_id'] == 'UP'){
							$gst_devide =  $value['GST']/2;
							$gst_devide_amt =  ($value['QTY']*$gst_amt)/2;
							}else{
							$gst_igst = $value['GST'];
							$gst_igst_amt = $value['QTY']*$gst_amt;
						}
						$gst_amt_total = ($gst_amt * $value['QTY']);
						
						if($data['pur_unit'] == 'Case'){
							$SuppliedIn =  'CS';
							}else if(($data['pur_unit'] == 'Crate')){
							$SuppliedIn =  'CR';
							}else{
							$SuppliedIn =  '';
						}
						$data_array_result_data = array(
						'PlantID'=>$PlantID,
						'FY'=>$FY,
						'cnfid' =>1,
						'OrderID' =>$id,
						'GodownID' =>$GodownID,
						'TransID' =>$id,
						'TransDate' =>$Transdate,
						'BillID' =>$id,
						'TransDate2'=>$Transdate,
						'TType'=>'X',
						'TType2'=> $data['adj_type'],
						'AccountID'=>$acc_id->AccountID,
						'ItemID'=>$item_c->item_code,
						'CaseQty'=>$value['PackQty'],
						'SaleRate'=>$sale_rate,
						'BasicRate'=>$value['BasicRate'],
						'SuppliedIn'=>$value['Cases/creats'],
						'BilledQty'=>$value['QTY'],
						'gst'=>$value['GST'],
						'gstamt'=>$gst_amt_total,
						'cgst'=>$gst_devide,
						'cgstamt'=>$gst_devide_amt,
						'sgst'=>$gst_devide,
						'sgstamt'=>$gst_devide_amt,
						'igst'=>$gst_igst,
						'igstamt'=>$gst_igst_amt,
						'ChallanAmt'=>$value['total'],
						'Ordinalno'=>$i,
						'UserID'=>$_SESSION['username'],
						);
						$data_i = $this->db->insert(db_prefix() . 'history',$data_array_result_data);
					} 
					$i++;
				}
				foreach($deleted_item as $values){
					$this->db->where('PlantID', $selected_company);
					$this->db->LIKE('FY', $fy);
					$this->db->where('AccountID', $data['vendor_code']);
					$this->db->where('ItemID', $values);
					$this->db->delete(db_prefix() . 'history');
				}
				
			}
			return true;
		}
		public function get_old_stock($order_id,$itemID){
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$this->db->where('PlantID', $selected_company);
			$this->db->LIKE('FY', $fy);
			$this->db->where('ItemID', $itemID);
			$this->db->where('OrderID', $order_id);
			
			return $this->db->get(db_prefix() . 'history')->row();
		}
		
		/**
			* @since  2.7.0
			*
			* Increment the challan next nubmer
			*
			* @return void
		*/
		public function increment_next_number()
		{
			// Update next CHALLAN number in settings
			$FY = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_stock_adj_number_for_cspl');
				}elseif($selected_company == 2){
                $this->db->where('name', 'next_stock_adj_number_for_cff');
				}elseif($selected_company == 3){
                $this->db->where('name', 'next_stock_adj_number_for_cbu');
				}elseif($selected_company == 4){
                $this->db->where('name', 'next_stock_adj_number_for_cbupl');
			}
			$this->db->set('value', 'value+1', false);
			$this->db->WHERE('FY', $FY);
			$this->db->update(db_prefix() . 'options');
		}
		
	}			