<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Production extends AdminController
	{
		private $not_importable_fields = ['id'];
		
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('production_model');
		}
		
		/* List all available items */
		public function index()
		{   
			if (!has_permission_new('recipe', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			
			//$data['items_main_groups'] = $this->production_model->get_sub_groups();
			$data['title'] = "New Recipe";
			$status = 'Y';
			$data['recipe_list'] = $this->production_model->load_data_for_recipe($status);
			$this->load->view('admin/production/manage', $data);
			//print_r($data); exit();
		}
		//========================= Production Dashboard Report ==============================
		public function ProductionDashboard()
		{
			if (!has_permission_new('ProductionDashboard', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Production Dashboard";
			$data['PlantDetail'] = $this->production_model->GetPlantDetails();
			$data['state'] = $this->production_model->GetStateList();
			$data['SubGroup'] = $this->production_model->GetItemGroupList('1');
			$data['ProductionStatus'] = $this->production_model->TodaysProductionStatus();
			$data['HighestYieldPacking'] = $this->production_model->TodaysHighestYieldPacking();
			$data['LowestYieldPacking'] = $this->production_model->TodaysLowestYieldPacking();
			$data['HighestProduction'] = $this->production_model->TodaysHighestProduction();
			$data['ProductionYieldStatus'] = $this->production_model->TodaysProductionYieldStatus();
			$data['MonthlyYieldStatus'] = $this->production_model->MonthlyProductionYieldStatus();
			$data['HighestYieldBaking'] = $this->production_model->TodaysHighestYieldBaking();
			$data['LowestYieldBaking'] = $this->production_model->TodaysLowestYieldBaking();
			$data['TotalBatchProduction'] = $this->production_model->TodaysTotalBatchProduction();
			// echo "<pre>";print_r($data['ProductionYieldStatus']);die;
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/production/ProductionDashboard', $data);
		}
		
		public function GetGetProductionCounters()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$ProductionStatus = $this->production_model->TodaysProductionStatus($data);
			$HighestYieldPacking = $this->production_model->TodaysHighestYieldPacking($data);
			$LowestYieldPacking = $this->production_model->TodaysLowestYieldPacking($data);
			$HighestProduction = $this->production_model->TodaysHighestProduction($data);
			$ProductionYieldStatus = $this->production_model->TodaysProductionYieldStatus($data);
			$MonthlyYieldStatus = $this->production_model->MonthlyProductionYieldStatus($data);
			$HighestYieldBaking = $this->production_model->TodaysHighestYieldBaking($data);
			$LowestYieldBaking = $this->production_model->TodaysLowestYieldBaking($data);
			$TotalBatchProduction = $this->production_model->TodaysTotalBatchProduction($data);
			$return = [
			'ProductionStatus' => $ProductionStatus,
			'HighestYieldPacking' => $HighestYieldPacking,
			'LowestYieldPacking' => $LowestYieldPacking,
			'HighestProduction' => $HighestProduction,
			'ProductionYieldStatus' => $ProductionYieldStatus,
			'MonthlyYieldStatus' => $MonthlyYieldStatus,
			'HighestYieldBaking' => $HighestYieldBaking,
			'LowestYieldBaking' => $LowestYieldBaking,
			'TotalBatchProduction' => $TotalBatchProduction,
			];
			
			echo json_encode($return);
		}
		
		//======================== Get Daily Production Report ===============================
		public function GetDailyProductionReports()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
    		'Items'  => $this->input->post('Items'),
    		'SubGroup'  => $this->input->post('SubGroup'),
			);
			$result = $this->production_model->GetDaywiseProductionForthisMonth($data);
			echo json_encode($result);
		}
		//======================== Get Daily RM Issue Report ===============================
		public function GetDateWiseRMISSUE()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
    		'Items'  => $this->input->post('Items'),
    		'SubGroup'  => $this->input->post('SubGroup'),
			);
			$result = $this->production_model->GetDaywiseRMIssueForthisMonth($data);
			echo json_encode($result);
		}
		//======================== Get Daily PM Issue Report ===============================
		public function GetDateWisePMISSUE()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
    		'Items'  => $this->input->post('Items'),
    		'SubGroup'  => $this->input->post('SubGroup'),
			);
			$result = $this->production_model->GetDaywisePMIssueForthisMonth($data);
			echo json_encode($result);
		}
		
		//========================= Item Wise Cost Report ==============================
		public function ItemWisePrdCostReports()
		{   
			if (!has_permission_new('recipe', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			$this->load->model('sale_reports_model');
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['title'] = "Item Wise Production Cost Report";
			$data['PrdItemList'] = $this->production_model->GetProductionItems($status);
			$this->load->view('admin/production/ItemWiseCostReports', $data);
		}
		
		public function Prod_VS_Sales()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'state'  => $this->input->post('state'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'SubGroup2'  => $this->input->post('SubGroup2'),
			'Items'  => $this->input->post('Items'),
			);
			$result = $this->production_model->Prod_VS_Sales($data);
			
			
			$data = [
			'Sales' => $result['Sales'],
			'Production' => $result['Production'],
			/*'TableData' => $html,*/
			];
			
			echo json_encode($data);
		}
		
		public function GetTopProduction()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'state'  => $this->input->post('state'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			);
			$result = $this->production_model->GetTopProduction($data);
			
			
			$data = [
			'Production' => $result['Production'],
			];
			
			echo json_encode($data);
		}
		public function GetProduction_VS_Baking()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'state'  => $this->input->post('state'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			);
			$result = $this->production_model->GetProduction_VS_Baking($data);
			
			
			$data = [
			'Production' => $result['Production'],
			];
			
			echo json_encode($data);
		}
		public function GetProduction_VS_Packing()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'state'  => $this->input->post('state'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			);
			$result = $this->production_model->GetProduction_VS_Packing($data);
			
			
			$data = [
			'Production' => $result['Production'],
			];
			
			echo json_encode($data);
		}
		public function GetBaking_VS_Packing()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'state'  => $this->input->post('state'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			);
			$result = $this->production_model->GetBaking_VS_Packing($data);
			
			
			$data = [
			'Production' => $result['Production'],
			];
			
			echo json_encode($data);
		}
		
		public function GetProduction_Vs_Sales()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'state'  => $this->input->post('state'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			);
			$result = $this->production_model->GetProduction_Vs_Sales($data);
			
			
			$data = [
			'Sales' => $result['Sales'],
			'Production' => $result['Production'],
			];
			
			echo json_encode($data);
		}
		
		//=================== load Item Wise Cost Reports ==============================
		public function LoadItemWiseCostReport()
		{
			$curMonth = $this->input->post('month');
			if($curMonth > 3){
				$fy = $this->session->userdata('finacial_year');
				}else{
				$fy = $this->session->userdata('finacial_year') + 1;
			}
			$curYear  = "20".$fy;
			$date    =    $curYear.'-'.$curMonth.'-01';//your given date
			
			$first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
			$first_date = date("Y-m-d",$first_date_find);
			
			$last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
			$last_date = date("Y-m-d",$last_date_find);
			
			$data = array(
		    'from_date' => $first_date,
		    'to_date'  =>$last_date,
		    'product_name'  => $this->input->post('ItemID'),
			);
			$Results = $this->production_model->GetRMItemListByFGItemID($data);
			$DamageAmt = $this->production_model->GetDamageDetailsByItemID($data);
			/*1000207 => PRODUCTION LABOUR
				1000208 =>PACKAGING LABOUR
				1000209 => FIXED LABOUR
				1000210 = >ADMIN STAFF
			1000211 => SALES/MARKETING*/
			$dataFetchGroup =array("1000207","1000208","1000209","1000210","1000211");
			$ConversionAndOverHeadCost = $this->production_model->GetConversionAndOverHeadCost($data,$dataFetchGroup);
			// echo "<pre>";print_r($Results);die;
			
			$PRODUCTION_LABOUR = 0;
			$PACKAGING_LABOUR = 0;
			$FIXED_LABOUR = 0;
			$ADMIN_STAFF = 0;
			$SALES_MARKETING = 0;
			foreach($ConversionAndOverHeadCost as $each){
				if($each['SubActGroupID'] == '1000207'){
					$PRODUCTION_LABOUR += $each['TotalAmt'];
				}
				if($each['SubActGroupID'] == '1000208'){
					$PACKAGING_LABOUR += $each['TotalAmt'];
				}
				if($each['SubActGroupID'] == '1000209'){
					$FIXED_LABOUR += $each['TotalAmt'];
				}
				if($each['SubActGroupID'] == '1000210'){
					$ADMIN_STAFF += $each['TotalAmt'];
				}
				if($each['SubActGroupID'] == '1000211'){
					$SALES_MARKETING += $each['TotalAmt'];
				}
			}
			$html1 =''; 
			
			if($Results){
				$RecipeMaster = $Results->RecipeDetails;
				
				$chart = [
                'labels'   => [],
                'datasets' => [],
				];
				
				$_data                         = [];
				$_data['data']                 = [];
				$_data['backgroundColor']      = [];
				$_data['hoverBackgroundColor'] = [];
				$_data['statusLink']           = [];
				
				$html1 .= '</br>';
				
				$html1 .= '<table class="table-striped table-bordered RMTable" id="RMTable" width="100%">';
				$html1 .= '<thead style="font-size:11px;">';
				$html1.= '<tr>';
				$html1.= '<th colspan="11" style="font-size: 14px;font-weight: 900;">FG Details</th>';
				$html1.= '</tr>';
				$html1.= '<tr>';
				$html1.= '<td colspan="2"><b>ITEM ID </b> <span style="font-size:13px;font-weight:900;"> : '.$RecipeMaster->item_code.'</span></td>';
				$html1.= '<td colspan="4"><b>PRODUCT NAME </b> <span style="font-size:13px;font-weight:900;"> : '.$RecipeMaster->item_description.'</span></td>';
				$html1.= '<td colspan="2"><b>VARIANT </b> <span style="font-size:13px;font-weight:900;"> : '.$RecipeMaster->VerientName.'</span></td>';
				$html1.= '<td colspan="3"><b>MRP </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->mrp, 2, '.', '').'</span></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2"><b>BATCH SIZE (KG) In Flour base </b> <span style="font-size:13px;font-weight:900;"> : '.$RecipeMaster->batch_size.'</span></td>';
				$html1.= '<td colspan="3"><b>GRAMMAGE OF THE PRODUCT (ingm) </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->grammage_product, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="3"><b>DOUGH DIVIDING WEIGHT </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->dough_dividing_weight, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="3"><b>MOISTURE LOSS POST DDW </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->moisture_loss_post_ddw, 2, '.', '') .'</span></td>';
				$html1.= '</tr>';
				
				$WrapperRate = 0;
				foreach($Results->PMItemList as $Key=>$val){
					if($val['SubGrpID1'] == '11' && $val['BasicRate'] != ''){
						$WrapperRate  = $val['BasicRate'];
					}
				}
				if($WrapperRate <= 0){
					$WrapperRate = $RecipeMaster->rate_wrapper_per_kg;
				}
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2"><b>VOLUME/WEIGHT RATIO CC </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->volume_ratio_cc, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="2"><b>MOULD LENGTH </b> <span style="font-size:13px;font-weight:900;"> : '. number_format((float)$RecipeMaster->mould_length, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="2"><b>MOULD WIDTH </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->mould_width, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="2"><b>MOULD DEPTH </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->mould_depth, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="3"><b>RATE OF WRAPPER PER KG </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$WrapperRate, 2, '.', '').'</span></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2"><b>NUMBER OF WRAPPER PER KG </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->wrapper_per_kg, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="3"><b>Total Batch Qty </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->TotalBatchQty, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="3"><b>Total STD. FG Qty </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->TotalStdFGQty, 2, '.', '') .'</span></td>';
				$html1.= '<td colspan="3"><b>Actual FG Qty </b> <span style="font-size:13px;font-weight:900;"> : '.number_format((float)$RecipeMaster->TotalFGQty, 2, '.', '') .'</span></td>';
				$html1.= '</tr>';
				
				
				
				$html1.= '<tr>';
				$html1.= '<th align="center">Sr. No.</th>';
				$html1.= '<th align="center">Item </th>';
				$html1.= '<th align="center">RM Qty as per Receipe</th>';
				$html1.= '<th align="center">Actual RM Qty</th>';
				$html1.= '<th align="center">Diff in qty</th>';
				$html1.= '<th align="center">RECIPE COMPOSITION%</th>';
				$html1.= '<th align="center">Rate</th>';
				$html1.= '<th align="center">Value</th>';
				$html1.= '<th align="center">GST</th>';
				$html1.= '<th align="center">Net Value</th>';
				$html1.= '<th align="center">COST %</th>';
				$html1.= '</tr>';
				$html1 .= '</thead>';
				$html1 .= '<tbody>';
				$html1.= '<tr>';
				$html1.= '<td colspan="11" style="font-size: 14px;font-weight: 900;">A. Raw Material Cost</td>';
				$html1.= '</tr>';
				$TotalDoughWt = 0;
				$TotalRMValue = 0;
				$colors = [ '#119EFA','#15f34f', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#ED561B','#d62626','#e3f20e','#10ead8','#0c83f2'];
				$i = 0;
				$colorCount = count($colors);
				foreach($Results->RMItemList as $Key=>$value){
					$TotalDoughWt += (float)$value['production_req_qty'];
					if($value['BasicRate'] != ''){
						$actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
						$value_data  = $actual_q*$value['BasicRate'];
						$TotalRMValue+= $value_data;
						
						$color =  $colors[$Key % $colorCount];
						array_push($chart['labels'], $value['item_name']);
						array_push($_data['backgroundColor'], $color);
						
						array_push($_data['hoverBackgroundColor'], adjust_color_brightness($color, -20));
						array_push($_data['data'], number_format($value_data, 2, '.', ''));
						$i++;
					}
				}
				$chart['datasets'][] = $_data;
				$i = 1;
				$RMTotal = 0;$RMActual = 0;$RMDiff = 0;$RMValue = 0;$RMGST = 0;$RMNetAmt = 0;$TotalRecipeComp = 0;$TotalRecipeCost = 0;$PALMOILRate = 0;
				foreach($Results->RMItemList as $Key=>$value){
					
					$html1.= '<tr>';
					$html1.= '<td align="center">'.$i.'</td>';
					$html1.= '<td align="left">'.$value['item_name'].'</td>';
					$html1.= '<td align="right">'.number_format((float)$value['production_req_qty'], 2, '.', '').'</td>';
					$RMTotal += (float)$value['production_req_qty'];
					$actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
					$RMActual += $actual_q;
					
					$html1.= '<td align="right">'.number_format((float)$actual_q, 2, '.', '').'</td>';
					$diffrence_q = $actual_q - $value['production_req_qty'];
					$RMDiff += $diffrence_q;
					$html1.= '<td align="right">'.number_format((float)$diffrence_q, 2, '.', '').'</td>';
					$RecipeComp = ($actual_q/$TotalDoughWt) * 100;
					$TotalRecipeComp += $RecipeComp;
					$html1.= '<td align="right">'.number_format($RecipeComp, 2, '.', '').'</td>';
					$html1.= '<td align="right">'.number_format((float)$value['BasicRate'], 2, '.', '').'</td>';
					$value_data = 0;
					if($value['BasicRate'] != ''){
						if($value["item_id"]=="GFRM0053"){
							$PALMOILRate = $value['BasicRate'];
						}
						$html1.= '<td align="right">'.round($actual_q*$value['BasicRate'],2).'</td>';
						$value_data  = $actual_q*$value['BasicRate'];
						$RMValue+= $value_data;
						$prec_amount =  ($value_data*$value['taxrate'])/100;
						$RMGST+= $prec_amount;
						$html1.= '<td align="right">'.number_format((float)$prec_amount, 2, '.', '').'</td>';
						$NetAmt = $prec_amount+$value_data;
						$RMNetAmt+= $NetAmt;
						$html1.= '<td align="right">'.number_format((float)$NetAmt, 2, '.', '').'</td>';
						}else{
						$html1.= '<td align="right"></td>';
						$html1.= '<td align="right"></td>';
						$html1.= '<td align="right"></td>';
					}
					$RecipeCost = ($value_data/$TotalRMValue) * 100;
					$TotalRecipeCost += $RecipeCost;
					$html1.= '<td align="right">'.number_format((float)$RecipeCost, 2, '.', '').'</td>';
					$html1.= '</tr>';
					$i++; 	
				}
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 14px;font-weight: 900;">RM Total</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$RMTotal, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$RMActual, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$RMDiff, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalRecipeComp, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 13px;font-weight: 900;">RMC OF THE BATCH</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$RMValue, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$RMGST, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$RMNetAmt, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalRecipeCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				// Dough Weight
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 13px;font-weight: 900;"><b>DOUGH WEIGHT</b></td>';
				$html1.= '<td align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$RMTotal, 2, '.', '').'</td>';
				$html1.= '<td colspan="3" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '<td  align="right" style="font-size: 13px;font-weight: 900;">RMC PER KG</td>';
				$RMCPerKG = $RMValue/$RMTotal;
				$html1.= '<td  align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$RMCPerKG, 2, '.', '').'</td>';
				$html1.= '<td colspan="3" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '</tr>';
				// Net Dough Weight
				$NetDoughWt = $RMTotal - ($RMTotal * $RecipeMaster->dough_wastage_per/100);
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 13px;font-weight: 900;"><b>NET DOUGH WEIGHT @WASTAGE '.$RecipeMaster->dough_wastage_per.'%</b></td>';
				$html1.= '<td align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$NetDoughWt, 2, '.', '').'</td>';
				$html1.= '<td colspan="3" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '<td  align="right" style="font-size: 13px;font-weight: 900;">RMC PER PKT</td>';
				// NUMBER OF PACKETS
				$TotalPackates = $NetDoughWt/($RecipeMaster->dough_dividing_weight/1000);
				$RMCPerPKT = $RMValue/$TotalPackates;
				$html1.= '<td  align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$RMCPerPKT, 2, '.', '').'</td>';
				$html1.= '<td colspan="3" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 13px;font-weight: 900;"><b>DOUGH DIVIDING WEIGHT</b></td>';
				$html1.= '<td align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$RecipeMaster->dough_dividing_weight, 2, '.', '').'</td>';
				$html1.= '<td colspan="8" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 13px;font-weight: 900;"><b>NUMBER OF PACKETS</b></td>';
				$html1.= '<td align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$TotalPackates, 2, '.', '').'</td>';
				$html1.= '<td colspan="8" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '</tr>';
				
				// YEILD OF PACKET @ WASTAGE 1%
				$YeildPackates = $TotalPackates - ($TotalPackates * $RecipeMaster->yield_wastage_per/100);
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 13px;font-weight: 900;"><b>YEILD OF PACKET @ WASTAGE '.$RecipeMaster->yield_wastage_per.'%</b></td>';
				$html1.= '<td align="right" style="font-size: 13px;font-weight: 900;">'.number_format((float)$YeildPackates, 2, '.', '').'</td>';
				$html1.= '<td colspan="8" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="11" style="font-size: 14px;font-weight: 900;">B. Packing Material Cost</td>';
				$html1.= '</tr>';
				
				$i = 1;
				$TotalPackQty = 0;
				$TotalPackCost = 0;
				foreach($Results->PMItemList as $Key=>$val){
					$actual_q = $val['production_req_qty']- $val['return_req_qty'] + $val['ExtraQty'];
					$TotalPackQty += $actual_q;
					if($val['BasicRate'] != ''){
						$value_data  = $actual_q*$val['BasicRate'];
						$TotalPackCost += $value_data;
					}
				}
				$PMTotal = 0;$PMActual = 0;$PMDiff = 0;$PMValue = 0;$PMGST = 0;$PMNetAmt = 0;$TotalRecipeComp = 0;$TotalRecipeCost = 0;
				foreach($Results->PMItemList as $Key=>$value){
					$html1.= '<tr>';
					$html1.= '<td align="center">'.$i.'</td>';
					$html1.= '<td align="left">'.$value['item_name'].'</td>';
					$html1.= '<td align="right">'.number_format((float)$value['production_req_qty'], 2, '.', '').'</td>';
					$PMTotal += (float)$value['production_req_qty'];
					$actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
					$PMActual += $actual_q;
					$html1.= '<td align="right">'.number_format((float)$actual_q, 2, '.', '').'</td>';
					$diffrence_q = $actual_q - $value['production_req_qty'];
					$PMDiff += $diffrence_q;
					$html1.= '<td align="right">'.number_format((float)$diffrence_q, 2, '.', '').'</td>';
					$RecipeComp = ($actual_q/$TotalPackQty) * 100;
					$TotalRecipeComp += $RecipeComp;
					$html1.= '<td align="right">'.number_format((float)$RecipeComp, 2, '.', '').'</td>';
					$html1.= '<td align="right">'.number_format((float)$value['BasicRate'], 2, '.', '').'</td>';
					$value_data = 0;
					if($value['BasicRate'] != '' && $value['BasicRate'] > 0){
						$html1.= '<td align="right">'.round($actual_q*$value['BasicRate'],2).'</td>';
						$value_data  = $actual_q*$value['BasicRate'];
						$PMValue+= $value_data;
						$prec_amount =  ($value_data*$value['taxrate'])/100;
						$PMGST+= $prec_amount;
						$html1.= '<td align="right">'.number_format((float)$prec_amount, 2, '.', '').'</td>';
						$NetAmt = $prec_amount+$value_data;
						$PMNetAmt+= $NetAmt;
						$html1.= '<td align="right">'.number_format((float)$NetAmt, 2, '.', '').'</td>';
						$RecipePackCost = ($value_data/$TotalPackCost) * 100;
						}else{
						$html1.= '<td align="right"></td>';
						$html1.= '<td align="right"></td>';
						$html1.= '<td align="right"></td>';
						$RecipePackCost = 0;
					}
					
					$TotalRecipeCost += $RecipePackCost;
					$html1.= '<td align="right">'.number_format((float)$RecipePackCost, 2, '.', '').'</td>';
					$html1.= '</tr>';
					$i++; 	
				}
				
				
				
				$html1.= '<tr>';
				$html1.= '<td colspan="2" style="font-size: 14px;font-weight: 900;">PM Total</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PMTotal, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PMActual, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PMDiff, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalRecipeComp, 2, '.', '').'</td>';
				$html1.= '<td></td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PMValue, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PMGST, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PMNetAmt, 2, '.', '').'</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalRecipeCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				// Packing price per kg
				$PricePerKG = $PMTotal / $NetDoughWt;
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="6" ></td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">Price Per KG</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$PricePerKG, 2, '.', '').'</td>';
				$html1.= '<td colspan="3" align="right" style="font-size: 14px;font-weight: 900;"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="11" style="font-size: 14px;font-weight: 900;">C. COVERSION COST </td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<th align="center">Sr. No.</th>';
				$html1.= '<th align="center">ITEM </th>';
				$html1.= '<th align="center">QUANTITY</th>';
				$html1.= '<th align="center">COST/KG</th>';
				$html1.= '<th align="center">TOTAL COST</th>';
				$html1.= '<td align="center" colspan="6" rowspan="20"> </td>';
				$html1.= '</tr>';
				$TotalConCost = 0;
				//Production labour Cost
				$PRDLebour = $RMTotal * 3.78;
				$TotalConCost += $PRODUCTION_LABOUR;
				$html1.= '<tr>';
				$html1.= '<td align="center">1</td>';
				$html1.= '<td align="left">PRODUCTION LABOUR PER BATCH</td>';
				$html1.= '<td align="right">'.number_format((float)$RecipeMaster->TotalBatchQty, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format(($PRODUCTION_LABOUR/$RecipeMaster->TotalBatchQty), 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format($PRODUCTION_LABOUR, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//PACKAGING labour Cost
				$PackLebour = $RMTotal * 1.38;
				$TotalConCost += $PACKAGING_LABOUR;
				$html1.= '<tr>';
				$html1.= '<td align="center">2</td>';
				$html1.= '<td align="left">PACKAGING LABOUR</td>';
				$html1.= '<td align="right">'.number_format((float)$RecipeMaster->TotalBatchQty, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format(($PACKAGING_LABOUR/$RecipeMaster->TotalBatchQty), 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format($PACKAGING_LABOUR, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//PACKAGING labour Cost
				$FixLebour = $RMTotal * 1.90;
				$TotalConCost += ($ADMIN_STAFF + $FIXED_LABOUR);
				$html1.= '<tr>';
				$html1.= '<td align="center">3</td>';
				$html1.= '<td align="left">ADMIN + FIXED LABOUR</td>';
				$html1.= '<td align="right">'.number_format((float)$RecipeMaster->TotalBatchQty, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format((($ADMIN_STAFF + $FIXED_LABOUR)/$RecipeMaster->TotalBatchQty), 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format(($ADMIN_STAFF + $FIXED_LABOUR), 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//REFIND PALM OIL (MOULD) Cost 
				$PALMOILMouldTotal = $PALMOILRate * 0.3;
				$TotalConCost += $PALMOILMouldTotal;
				$html1.= '<tr>';
				$html1.= '<td align="center">4</td>';
				$html1.= '<td align="left">REFIND PALM OIL (MOULD)</td>';
				$html1.= '<td align="right">'.number_format((float)0.3, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format((float)$PALMOILRate, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format((float)$PALMOILMouldTotal, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//GAS Cost
				$GASCost = $RMTotal * 3.1;
				$TotalConCost += $GASCost;
				$html1.= '<tr>';
				$html1.= '<td align="center">5</td>';
				$html1.= '<td align="left">GAS</td>';
				$html1.= '<td align="right">'.number_format((float)$RMTotal, 2, '.', '').'</td>';
				$html1.= '<td align="right">3.1</td>';
				$html1.= '<td align="right">'.number_format((float)$GASCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//Electricity Cost
				$ELECTRICITYCost = $RMTotal * 0.76;
				$TotalConCost += $ELECTRICITYCost;
				$html1.= '<tr>';
				$html1.= '<td align="center">6</td>';
				$html1.= '<td align="left">ELECTRICITY</td>';
				$html1.= '<td align="right">'.number_format((float)$RMTotal, 2, '.', '').'</td>';
				$html1.= '<td align="right">0.76</td>';
				$html1.= '<td align="right">'.number_format((float)$ELECTRICITYCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 14px;font-weight: 900;">Total</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalConCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$ConCostPerKG = $TotalConCost/$RMTotal;
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 14px;font-weight: 900;">COVERSION COST PER KG</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$ConCostPerKG, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$TotalCostPerKgForDispatch = $ConCostPerKG + $PricePerKG + $RMCPerKG;
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 15px;font-weight: 900;">(A + B + C) TOTAL COST PER KG AT DISPATCH</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalCostPerKgForDispatch, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="11" style="font-size: 14px;font-weight: 900;">D. OVER HEAD ON RMC </td>';
				$html1.= '</tr>';
				$TotalOverHeadCost = 0;
				$html1.= '<tr>';
				$html1.= '<th align="center">Sr. No.</th>';
				$html1.= '<th align="center">ITEM </th>';
				$html1.= '<th align="center">PERCENTAGE</th>';
				$html1.= '<th align="center">COST/KG</th>';
				$html1.= '<th align="center">TOTAL COST</th>';
				$html1.= '</tr>';
				
				//Production labour Cost
				$TransportCost = 1 * 4.42;
				$TotalOverHeadCost += $TransportCost;
				$html1.= '<tr>';
				$html1.= '<td align="center">1</td>';
				$html1.= '<td align="left">TRANSPORT </td>';
				$html1.= '<td align="right">'.number_format((float)$NetDoughWt, 2, '.', '').'</td>';
				$html1.= '<td align="right">4.42</td>';
				$html1.= '<td align="right">'.number_format((float)$TransportCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//SALES/MARKETING Cost
				$SalesCost = 1 * 1;
				$TotalOverHeadCost += $SALES_MARKETING;
				$html1.= '<tr>';
				$html1.= '<td align="center">2</td>';
				$html1.= '<td align="left">SALES/MARKETING</td>';
				$html1.= '<td align="right">'.number_format((float)$RecipeMaster->TotalBatchQty, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format(($SALES_MARKETING/$RecipeMaster->TotalBatchQty), 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format($SALES_MARKETING, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				//DAMAGE/RETURN labour Cost
				//$DmgCost = $TotalCostPerKgForDispatch * (15/100);
				$DmgCost = $DamageAmt->TotalDmgAmt;
				$DmgQty = $DamageAmt->TotalDmgQty;
				$TotalOverHeadCost += $DmgCost;
				$html1.= '<tr>';
				$html1.= '<td align="center">3</td>';
				$html1.= '<td align="left">DAMAGE/RETURN</td>';
				$html1.= '<td align="right">'.number_format((float)$DmgQty, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format((float)$DmgCost, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format((float)$DmgCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 14px;font-weight: 900;">Total</td>';
				$html1.= '<td align="right" style="font-size: 14px;font-weight: 900;">'.number_format((float)$TotalOverHeadCost, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$TotalCostPerKG = $TotalCostPerKgForDispatch + $TotalOverHeadCost;
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 16px;font-weight: 900;">TOTAL COST (A+B+C+D) PER KG</td>';
				$html1.= '<td align="right" style="font-size: 16px;font-weight: 900;">'.number_format((float)$TotalCostPerKG, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$TotalCostPerPkt = ($TotalCostPerKG / 1000) * $RecipeMaster->grammage_product;
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 16px;font-weight: 900;">TOTAL COST PER PACKET</td>';
				$html1.= '<td align="right" style="font-size: 16px;font-weight: 900;">'.number_format((float)$TotalCostPerPkt, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$NetRecoryRate = 37;
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 16px;font-weight: 900;">NET RCOVERY RATE</td>';
				$html1.= '<td align="right" style="font-size: 16px;font-weight: 900;">'.number_format((float)$NetRecoryRate, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				$MARGIN = 100 - (($TotalCostPerPkt/$NetRecoryRate) * 100);
				$html1.= '<tr>';
				$html1.= '<td align="right" colspan="4" style="font-size: 16px;font-weight: 900;">MARGIN</td>';
				$html1.= '<td align="right" style="font-size: 16px;font-weight: 900;">'.number_format((float)$MARGIN, 2, '.', '').'</td>';
				$html1.= '</tr>';
				
				
				
				$html1.= '<tr>';
				$html1.= '<td colspan="11" style="font-size: 14px;font-weight: 900;height:50px"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td colspan="11" style="font-size: 14px;font-weight: 900;">RMC Of Batch - Cost of 1 Mixing </td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<th align="center"></th>';
				$html1.= '<th align="center">PARAMETER </th>';
				$html1.= '<th align="center">COST</th>';
				$html1.= '<th align="center" colspan="2">PERCENTAGE CONTRIBUTION (PC%)</th>';
				$html1.= '<td align="center" colspan="6" ></td>';
				$html1.= '</tr>';
				
				$TotalCostOfWB = 0;
				$TotalCostOfWB += $RMCPerKG;
				$TotalCostOfWB += $PricePerKG;
				$TotalCostOfWB += $ConCostPerKG;
				$TotalCostOfWB += $TotalOverHeadCost;
				$RMCPerKG_Contr = ($RMCPerKG / $TotalCostOfWB) * 100;
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">RMC PER KG</td>';
				$html1.= '<td align="right">'.number_format($RMCPerKG, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">'.number_format($RMCPerKG_Contr, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="6"></td>';
				$html1.= '</tr>';
				
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">PACKAGING COST PER KG</td>';
				$html1.= '<td align="right">'.number_format($PricePerKG, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2"></td>';
				$html1.= '<td align="right" colspan="6"></td>';
				$html1.= '</tr>';
				
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">COVERSION COST PER KG</td>';
				$html1.= '<td align="right">'.number_format($ConCostPerKG, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2"></td>';
				$html1.= '<td align="right" colspan="6"></td>';
				$html1.= '</tr>';
				
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">OVER HEAD ON RMC IN KG</td>';
				$html1.= '<td align="right">'.number_format($TotalOverHeadCost, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2"></td>';
				$html1.= '<td align="right" colspan="6"></td>';
				$html1.= '</tr>';
				
				
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">TOTAL COST OF WB IN KG</td>';
				$html1.= '<td align="right">'.number_format($TotalCostOfWB, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2"></td>';
				$html1.= '<th align="center">RATE</th>';
				$html1.= '<th align="center">MARGIN</th>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$TotalCostPerPkt = ($TotalCostOfWB/1000) * $RecipeMaster->grammage_product;
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">TOTAL COST PER PACKET</td>';
				$html1.= '<td align="right">'.number_format($TotalCostPerPkt, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PTC</td>';
				$html1.= '<td align="right">'.number_format((float)$RecipeMaster->mrp, 2, '.', '').'</td>';
				$PTCMargin = ($TotalCostPerPkt / $RecipeMaster->mrp) * 100;
				$html1.= '<td align="right">'.number_format((float)$PTCMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">TOTAL COST PER BATCH</td>';
				$TotalCostPerBatch = $RMValue + $PMValue + $TotalConCost + $TotalOverHeadCost;
				$html1.= '<td align="right">'.number_format((float)$TotalCostPerBatch, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PTR @10%</td>';
				$PTR = $RecipeMaster->mrp -($RecipeMaster->mrp * 10/100);
				$html1.= '<td align="right">'.number_format((float)$PTR, 2, '.', '').'</td>';
				$PTRMargin = (1 - ($PTR / $RecipeMaster->mrp)) * 100;
				$html1.= '<td align="right">'.number_format((float)$PTRMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$TotalCostPerSL = $TotalCostOfWB * 0.4;
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">TOTAL COST PER SL</td>';
				$html1.= '<td align="right">'.number_format($TotalCostPerSL, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PTD LOCAL @24%</td>';
				$PTD = $PTR -($PTR * 24/100);
				$html1.= '<td align="right">'.number_format((float)$PTD, 2, '.', '').'</td>';
				$PTDMargin = (1 - ($PTD / $RecipeMaster->mrp)) * 100;
				$html1.= '<td align="right">'.number_format((float)$PTDMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">RECOVERY PER PACKET</td>';
				$html1.= '<td align="right">'.number_format((float)$PTD, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PROFIT PER PACKET</td>';
				$ProfitPerPkt = $PTD - $TotalCostPerPkt;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerPkt, 2, '.', '').'</td>';
				$ProfitPerPktMargin = ($ProfitPerPkt / $TotalCostPerPkt) * 100;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerPktMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">RECOVERY PER SL</td>';
				$RecoveryPerSL = ($PTD / $RecipeMaster->grammage_product) * 400;
				$html1.= '<td align="right">'.number_format((float)$RecoveryPerSL, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PROFIT PER SL</td>';
				$ProfitPerSL = $RecoveryPerSL - $TotalCostPerSL;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerSL, 2, '.', '').'</td>';
				$ProfitPerSLMargin = ($ProfitPerSL / $TotalCostPerSL) * 100;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerSLMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">RECOVERY PER KG</td>';
				$RecoveryPerKG = ($PTD / $RecipeMaster->grammage_product) * 1000;
				$html1.= '<td align="right">'.number_format((float)$RecoveryPerKG, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PROFIT PER MONTH</td>';
				$ProfitPerkg = $RecoveryPerKG - $TotalCostOfWB;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerkg, 2, '.', '').'</td>';
				$ProfitPerkgMargin = ($ProfitPerkg  / $TotalCostOfWB) * 100;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerkgMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$html1.= '<tr>';
				$html1.= '<td align="center"></td>';
				$html1.= '<td align="left">RECOVERY PER BATCH</td>';
				$RecoveryPerBtach = $PTD * $YeildPackates;
				$html1.= '<td align="right">'.number_format((float)$RecoveryPerBtach, 2, '.', '').'</td>';
				$html1.= '<td align="right" colspan="2">PROFIT PER BATCH</td>';
				$ProfitPerBatch = $RecoveryPerBtach - $TotalCostPerBatch;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerBatch, 2, '.', '').'</td>';
				$ProfitPerBatchMargin = ($ProfitPerBatch / $TotalCostPerBatch) * 100;
				$html1.= '<td align="right">'.number_format((float)$ProfitPerBatchMargin, 2, '.', '').'%</td>';
				$html1.= '<td align="right" colspan="4"></td>';
				$html1.= '</tr>';
				
				$html1 .= '</tbody>';
				/*$html1 .= '<tfoot>';
					$html1.= '<tr>';
					$html1.= '<td>Total</td>';
					$html1.= '<td></td>';
					$html1.= '<td></td>';
					$html1.= '<td></td>';
					$html1.= '<td></td>';
					$html1.= '<td></td>';
					$html1.= '<td align="right"></td>';
					$html1.= '<td align="right"></td>';
					$html1.= '<td align="right"></td>';
					$html1.= '</tr>';
				$html1 .= '</tfoot>';*/
				$html1 .= '<table>';
			}
			
			$result->chart = $chart;
			$result->html1 = $html1;
			//$data = array('production_table'=>$html , 'row_material_table'=>$html1,'lower_table'=>$html2);
			echo json_encode($result);
		}
		
		public function all_recipe()
		{
			if (!has_permission_new('recipe', '', 'view')) {
				ajax_access_denied();
			}
			$data['title'] = "View Recipe";
			$this->load->view('admin/production/recipe', $data);
		}
		public function view_Order()
		{
			if (!has_permission_new('production', '', 'view')) {
				ajax_access_denied();
			}
			$data['title'] = _l('Production Order View');
			$this->load->view('admin/production/view_production_order', $data);
		}
		
		public function table_production()
		{
			if (!has_permission_new('production', '', 'view')) {
				ajax_access_denied();
			}
			$this->app->get_table_data('production_table');
		}
		
		
		public function table()
		{
			if (!has_permission_new('recipe', '', 'view')) {
				ajax_access_denied();
			}
			$this->app->get_table_data('recipe_table');
		}
		
		public function increment_next_number()
		{
			// Update next Receipts number in settings
			$FY = $this->session->userdata('finacial_year');
			$selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_production_return_number_for_cspl');
				}elseif($selected_company == 2){
                $this->db->where('name', 'next_production_return_number_for_cff');
				}elseif($selected_company == 3){
                $this->db->where('name', 'next_production_return_number_for_cbu');
				}elseif($selected_company == 4){
                $this->db->where('name', 'next_production_return_number_for_cbupl');
			}
			$this->db->set('value', 'value+1', false);
			$this->db->WHERE('FY', $FY);
			$this->db->update(db_prefix() . 'options');
		}
		
		public function create_order()
		{ 
			if (!has_permission_new('production', '', 'view')) {
				ajax_access_denied();
			}
			//$this->load->model('sales_receipts_model');
			$data['manager'] = $this->production_model->get_managername();
			$data['contractor'] = $this->production_model->get_contractorname();
			$data['PRDLastDate'] = $this->production_model->GetLastPrdDate();
			$data['GodownData'] = $this->production_model->GetGodownData();
			$data['title'] = _l('Production Order');
			$this->load->view('admin/production/manage_production_order', $data);
			if($this->input->post()){
				
				if (!has_permission_new('production', '', 'create')) {
					ajax_access_denied();
				}
				// echo "<pre>";print_r($this->input->post());die;
				$data = $this->input->post();
				$time1 = $data["req_hour"] * 60;
				
				$req_time= $time1 + $data["req_min"];
				
				$operator_name=$data["operator_name"];
				$con_name=$data["con_name"];
				$name = '';
				if($operator_name){
					$name=$operator_name;
					}else if($con_name){
					$name=$con_name;
				}
				if($name == "" ){
					set_alert('warning', 'Please add production manager..');
					redirect(admin_url('production/create_order'));     
				}
				$selected_company = $this->session->userdata('root_company');
				$fy = $this->session->userdata('finacial_year');
				if($selected_company == "1"){
					$GodownID = 'CSPL';
					}else if($selected_company == "2"){
					$GodownID = 'CFF';
					}else if($selected_company == "3"){
					$GodownID = 'CBUPL';
				}
				
				if($selected_company == 1){
					$new_production_orderNumbar = get_option('next_production_return_number_for_cspl');
					}elseif($selected_company == 2){
					$new_production_orderNumbar = get_option('next_production_return_number_for_cff');
					}elseif($selected_company == 3){
					$new_production_orderNumbar = get_option('next_production_return_number_for_cbu');
					}elseif($selected_company == 4){
					$new_production_orderNumbar = get_option('next_production_return_number_for_cbupl');
				}
				$order_id = "POI".$fy.$new_production_orderNumbar;
				
				$finish_good_qty = $data["finishgood_qty"];
				$date = to_sql_date($data["start_date1"])." ".date('H:i:s');
				
				$production_data = array(
                "PlantID"=>$selected_company,
                "FY"=>$fy,
                "GodownID"=>$data["GodownID"],
                "pro_order_id"=>$order_id,
                "TransDate"=>$date,
                "recipeID"=>$data["recipeID"],
                "std_batch_qty"=>$data["batch_qty"],
                "batch_qty"=>$data["batch_qty"],
                "Finish_good_qty"=>$finish_good_qty,
                "Finish_good_qty_new"=>$finish_good_qty,
                "finish_good_unit"=>$data["unit_new"],
				"env_temp"=>$data["env_temp"],
				"env_humidity"=>$data["env_humidity"],
				"water_temp"=>$data["water_temp"],
                "required_time"=>$req_time,
                "production_status"=>"pending",
                "manager_name"=>$data["operator_name"],
                "shift"=>$data["shift"],
                "contractor_name"=>$data["con_name"],
                "comment"=>$data["comments"],
                "TransDate2"=>date('Y-m-d H:i:s'),
                "UserID"=>$this->session->userdata('username')
				); 
                
				$this->db->insert(db_prefix() . 'production', $production_data);
				$last_inserted_id = $this->db->insert_id();
				if($last_inserted_id){
					$this->increment_next_number();
					$count = $data["count_of_rec"];
					
					$item_id2 = $this->input->post('item_id[]');
					$item_name2 = $this->input->post('item_name[]');
					$req_qty2 = $this->input->post('req_qty[]');
					$unit2 = $this->input->post('unit[]');
					$pro_req_qty = $this->input->post('pro_req_qty[]');
					
					$production_details = array(  
					array(
                    'item_id' => 'item_id' ,
                    'item_name' => 'item_name' ,
                    'req_qty' => 'req_qty',
					'unit' => 'unit',
					'production_req_qty' => 'pro_req_qty'));
					
					$i = 0;
					foreach($item_id2 as $key=>$val)
					{     
						$production_details[$i]['PlantID'] = $selected_company;
						$production_details[$i]['FY'] = $fy;
						$production_details[$i]['GodownID'] = $data["GodownID"];
						$production_details[$i]['item_id'] = $val;
						$production_details[$i]['item_name'] = $item_name2[$key];
						$production_details[$i]['req_qty'] = $req_qty2[$key];
						$production_details[$i]['unit'] = $unit2[$key];
						$production_details[$i]['production_id'] = $order_id;
						$production_details[$i]['production_req_qty'] = $pro_req_qty[$key];
						$production_details[$i]['TransDate2'] = date('Y-m-d H:i:s');
						$production_details[$i]['TransDate'] = $date;
						$production_details[$i]['UserID'] = $this->session->userdata('username');
						$i++;
					}
					$insert_comme=$this->db->insert_batch(db_prefix() . 'production_details', $production_details);
					set_alert('success', 'Production order added successfully');
					redirect(admin_url('production/create_order')); 
					}else{
					set_alert('warning', 'Something went wrong');
					redirect(admin_url('production/create_order')); 
				}
			} 
		}
		
		public function itemlist_name()
		{
			$recipeID = $this->input->post('recipeID');
			//$GodownID = $this->input->post('GodownID');
			$GodownID = 'RM';
			$data['batch_qty']= $this->input->post('batchQuantity');
			$data['result2'] = $this->production_model->getbyitemname($recipeID,$GodownID); 
			$data['ItemStocks'] = $this->production_model->GetItemStock($GodownID);
			$this->load->view('admin/production/get_recipe',$data);          
		}
		
		public function ReceipeData()
		{
			$recipeID = $this->input->post('recipeID');
			$batch_qty = $this->input->post('batchQuantity');
			$PONumber = $this->input->post('PONumber');
			$GodownID = $this->input->post('GodownID');
			$recipeDetails = $this->production_model->getReceipeDetailswithPODetails($recipeID,$PONumber);
			$ItemStocks = $this->production_model->GetItemStock($GodownID);
			// print_r($ItemStocks);die;
			
			
			$html = '';
			$html2 = '';
			foreach($recipeDetails as $key=>$value){
				if($value['MainGrpID'] == '2'){
					$reqQty = $value['StdQty'] * $batch_qty;
					$actualQty = $reqQty + $value['ExtraQty'] - $value['RtnQty'];
					$PQty = 0;
					$PRQty = 0;
					$IQty = 0;
					$PRDQty = 0;
					$SQty = 0;
					$SRTQty = 0;
					$AQty = 0;
					$GOQty = 0;
					$GIQty = 0;
					
					foreach ($ItemStocks as $stock) {
						if($stock['ItemID']==$value['item_id']){
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
								}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
								$AQty += $stock['BilledQty'];
								}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
								$AQty += $stock['BilledQty'];
								}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock distribution'){
								$AQty += $stock['BilledQty'];
								}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
								$GIQty = $stock['BilledQty'];
								}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
								$GOQty = $stock['BilledQty'];
							}
						}
					}
					$stockQty = $value['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty- $GOQty + $GIQty;
					if($stockQty < $actualQty){
						$style = 'border-color: red;color:red';
						}else{
						$style = '';
					}
					
					$NeededQty = '';
					if($stockQty < 0){
						$NeededQty = number_format($actualQty - $stockQty,2);
					}
					$html .= '<tr>';
					$html .= '<td>'.$value['item_id'].'</td>';
					$html .= '<td>'.$value['item_name'].'</td>';
					$html .= '<td>'.$value['StdQty'].'</td>';
					$html .= '<td>'.$reqQty.'</td>';
					$html .= '<td>'.$value['RtnQty'].'</td>';
					$html .= '<td>'.$value['ExtraQty'].'</td>';
					$html .= '<td>'.$actualQty.'</td>';
					$html .= '<td style="'.$style.'">'.$stockQty.'</td>';
					$html .= '<td>'.$NeededQty.'</td>';
					$html .= '<td>'.$value['unit'].'</td>';
					$html .= '</tr>';
				}
				if($value['MainGrpID'] == '3'){
					$reqQty = $value['StdQty'] * $batch_qty;
					$actualQty = $reqQty + $value['ExtraQty'] - $value['RtnQty'];
					$PQty = 0;
					$PRQty = 0;
					$IQty = 0;
					$PRDQty = 0;
					$SQty = 0;
					$SRTQty = 0;
					$AQty = 0;
					$GOQty = 0;
					$GIQty = 0;
					
					foreach ($ItemStocks as $stock) {
						if($stock['ItemID']==$value['item_id']){
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
								}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
								$AQty += $stock['BilledQty'];
								}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
								$AQty += $stock['BilledQty'];
								}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock distribution'){
								$AQty += $stock['BilledQty'];
								}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
								$GIQty = $stock['BilledQty'];
								}elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
								$GOQty = $stock['BilledQty'];
							}
						}
					}
					$stockQty = $value['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty- $GOQty + $GIQty;
					if($stockQty < $actualQty){
						$style = 'border-color: red;color:red';
						}else{
						$style = '';
					}
					$html2 .= '<tr>';
					$html2 .= '<td>'.$value['item_id'].'</td>';
					$html2 .= '<td>'.$value['item_name'].'</td>';
					$html2 .= '<td>'.$value['StdQty'].'</td>';
					$html2 .= '<td>'.$reqQty.'</td>';
					$html2 .= '<td>'.$value['RtnQty'].'</td>';
					$html2 .= '<td>'.$value['ExtraQty'].'</td>';
					$html2 .= '<td>'.$actualQty.'</td>';
					$html2 .= '<td style="'.$style.'">'.$stockQty.'</td>';
					$html2 .= '<td>'.$value['unit'].'</td>';
					$html2 .= '</tr>';
				}
			}
			$ItemData = [
			"RM" => $html,
			"PM" => $html2,
			];
			echo json_encode($ItemData);
		}
		
		
		public function itemlist_recipe(){
			$this->load->model('production_model');
			// POST data
			$postData = $this->input->post();
			
			// Get data
			$data = $this->production_model->get_recipename($postData);
			//print_r($postData); exit();
			echo json_encode($data);
		}
		
		public function itemlist_using_itemcode()
		{
			$this->load->model('production_model');
			// POST data
			$postData = $this->input->post();
			
			// Get data
			$data = $this->production_model->getitem_using_itemcode($postData);
			//print_r($postData); exit();
			echo json_encode($data);
		}
		public function itemDetails_by_itemcode(){
			$this->load->model('production_model');
			// POST data
			$postData = $this->input->post();
			
			// Get data
			$data = $this->production_model->itemDetails_by_itemcode($postData);
			//print_r($postData); exit();
			echo json_encode($data);
		}
		
		public function itemlist_subgroup(){
			// POST data
			$postData = $this->input->post();
			$ProdId = $this->input->post('ProductionId');
			// Get data
			$data = $this->production_model->getitem_subgroup($postData,$ProdId);
			
			echo json_encode($data);
		}
		public function itemlist_subgroup_baking(){
			// POST data
			$postData = $this->input->post();
			$ProdId = $this->input->post('ProductionId');
			// Get data
			$data = $this->production_model->getitem_subgroup_baking($postData,$ProdId);
			
			echo json_encode($data);
		}
		
		public function ItemListReceipe(){
			$postData = $this->input->post();
			$data = $this->production_model->ItemListReceipe($postData);
			
			echo json_encode($data);
		}
		
		public function itemlist_subgroup1()
		{
			// POST data
			$postData = $this->input->post();
			$ProdId = $this->input->post('ProductionId');
			// Get data
			$data = $this->production_model->getitem_subgroup1($postData,$ProdId);
			
			echo json_encode($data);
		}
		public function get_recipe_details(){
			// POST data
			$postData = $this->input->post();
			$recipeID = $this->input->post('recipeID');
			// Get data
			$data = $this->production_model->get_recipe_details($recipeID);
			
			echo json_encode($data);
		}
		public function get_item_details(){
			// POST data
			$postData = $this->input->post();
			$ProdId = $this->input->post('proId');
			$ItemID = $this->input->post('ItemID');
			// Get data
			$data = $this->production_model->get_item_details($ProdId,$ItemID);
			
			echo json_encode($data);
		}
		public function get_item_details_baking(){
			// POST data
			$postData = $this->input->post();
			$ProdId = $this->input->post('proId');
			$ItemID = $this->input->post('ItemID');
			// Get data
			$data = $this->production_model->get_item_details_baking($ProdId,$ItemID);
			
			echo json_encode($data);
		}
		
		public function add()
		{  
			if (!has_permission_new('recipe', '', 'create')) {
				ajax_access_denied();
			}
			$data = $this->input->post(); 
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			// echo "<pre>";print_r($data);die;
			// Check Recipe Exit OR Not
			$ReceipDetails = $this->production_model->get_recipe_details($data["item_code"]);
			if($ReceipDetails){
				$OldRecUpdate = array(
                'status'=>'N',
                'DeActiveDate'=>date('Y-m-d H:i:s'),
                'ADUserID'=>$this->session->userdata('username'),
                "UserID2"=>$this->session->userdata('username'),
                "Lupdate"=>date('Y-m-d H:i:s')
				);
				$this->db->where('item_code', $data["item_code"]);
				$this->db->where('PlantID', $selected_company);
				//$this->db->where('FY', $fy);
				$UpdateOldReceipe = $this->db->update(db_prefix() . 'recipe', $OldRecUpdate);
			}
            $receipt_date = array(
			"PlantID"=>$selected_company,
			"FY"=>$fy,
			"item_code"=>$data["item_code"],
			"item_description"=>$data["item_desc"],
			"qty"=>$data["number_packets"],
			"unit"=>$data["unit_f_g"],
			"std_yield"=>$data["std_yield"],
			// "conv_cost"=>$data["conv_cost"],
			// "st_cost"=>$data["st_cost"],
			// "frt_cost"=>$data["frt_cost"],
			// "mrkt_cost"=>$data["mrkt_cost"],
			// "dmg_cost"=>$data["dmg_cost"],
			"dough_wastage_per"=>$data["dough_wastage_per"],
			"yield_wastage_per"=>$data["yield_wastage_per"],
			"env_temp"=>$data["env_temp"],
			"env_humidity"=>$data["env_humidity"],
			"water_temp"=>$data["water_temp"],
			"batch_size"=>$data["batch_size"],
			"grammage_product"=>$data["grammage_product"],
			"dough_dividing_weight"=>$data["dough_dividing_weight"],
			"mould_length"=>$data["mould_length"],
			"mould_width"=>$data["mould_width"],
			"mould_depth"=>$data["mould_depth"],
			"wrapper_per_kg"=>$data["wrapper_per_kg"],
			"rate_wrapper_per_kg"=>$data["rate_wrapper_per_kg"],
			"refined_palm_oil"=>$data["refined_palm_oil"],
			"net_rec_rate"=>$data["net_rec_rate"],
			"ptr"=>$data["ptr"],
			"moisture_loss_post_ddw"=>$data["moisture_loss_post_ddw"],
			"volume_ratio_cc"=>$data["volume_ratio_cc"],
			"rm_size"=>$data["rm_size"],
			"dough_weight"=>$data["dough_weight"],
			"net_dough_weight"=>$data["net_dough_weight"],
			"yield_packets"=>$data["yield_packets"],
			"Is_Baking"=>$data["Is_Baking"],
			"status"=>$data["status"],
			'ActiveDate'=>date('Y-m-d H:i:s'),
			'ADUserID'=>$this->session->userdata('username'),
			"UserID"=>$this->session->userdata('username'),
			"TransDate"=>date('Y-m-d H:i:s'),
            );
            if($data["countof_record"] > 0){
                $this->db->insert(db_prefix() . 'recipe', $receipt_date);
				$lastid = $this->db->insert_id(); 
				}else{
                set_alert('warning', 'please select atleast one raw material..');
				redirect(admin_url('production'));
			}
            
			if($lastid){
				$count = $data["countof_record"];  
				$Pcount = $data["Pcountof_record"];  
				
				for($i=1;$i<=$count;$i++){
					
					$item_id = "item_id".$i;
                    $item_name = "item_name".$i;
                    $req_qty = "req_qty".$i;
                    $unit = "unit".$i;
                    $is_calculation = "is_calculation".$i;
                    $recipe_details = array(
					"PlantID"=>$selected_company,
					"FY"=>$fy,
					"item_id"=>$data[$item_id],
					"item_name"=>$data[$item_name],
					"req_qty"=>$data[$req_qty],
					"unit"=>$data[$unit],
					"is_calculation"=>$data[$is_calculation],
					"rec_id"=>$lastid,
					"Ordinalno"=>$i,
					"UserID"=>$this->session->userdata('username'),
					"TransDate"=>date('Y-m-d H:i:s'),
					);
					
					$recipeData=$this->db->insert(db_prefix() . 'recipe_details', $recipe_details);
					//print_r($recipe_details);
				}
				for($i=1;$i<=$Pcount;$i++){
					
					$item_id = "Pitem_id".$i;
                    $item_name = "Pitem_name".$i;
                    $req_qty = "Preq_qty".$i;
                    $unit = "Punit".$i;
                    $is_calculation = "Pis_calculation".$i;
                    $recipe_details = array(
					"PlantID"=>$selected_company,
					"FY"=>$fy,
					"item_id"=>$data[$item_id],
					"item_name"=>$data[$item_name],
					"req_qty"=>$data[$req_qty],
					"unit"=>$data[$unit],
					"is_calculation"=>$data[$is_calculation],
					"rec_id"=>$lastid,
					"Ordinalno"=>$i,
					"UserID"=>$this->session->userdata('username'),
					"TransDate"=>date('Y-m-d H:i:s'),
					);
					
					$recipeData=$this->db->insert(db_prefix() . 'recipe_details', $recipe_details);
					//print_r($recipe_details);
				}
				//die;
				if($lastid){
					set_alert('success', 'Recipe added Successfully..');
                    redirect(admin_url('production'));
				}
				}else{
				set_alert('warning', 'Something went wrong..');
				redirect(admin_url('production'));
			}
			
		}
		
		public function load_data_for_recipe() 
		{ 
			$postData = $this->input->post('status'); 
			$data = $this->production_model->load_data_for_recipe($postData);
			echo json_encode($data); 
		} 
		public function editRecipe($id)
		{  
			if (!has_permission_new('recipe', '', 'view')) {
				ajax_access_denied();
			}
			if ($this->input->post()) {
				if (!has_permission_new('recipe', '', 'edit')) {
					ajax_access_denied();
				}
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $data = $this->input->post();
                
                $recipe_id = $this->input->post('id');
                $count = $data["countof_record"]; 
                $Pcount = $data["Pcountof_record"];  
                $ItCount = $count - 1;  
				/*echo "<pre>";
					echo $recipe_id;
					echo "<br>";
					print_r($data);
				die;*/
				
				//new Item aadded
                $new_record = $data["new_record"];
                $new_record = str_replace(" ,",'',$data["new_record"]);
                $new_record_array = explode(',', $new_record);
                
                //update exiting Item
                $edit_record = $data["updated_record"];
                $edit_record = str_replace(" ,",'',$data["updated_record"]);
                $edit_record_array = explode(',', $edit_record);
                
                // delete exiting Item
                $delete_record = $data["deleted_record"];
                $delete_record = str_replace(" ,",'',$data["deleted_record"]);
                $delete_record_array = explode(',', $delete_record);
                
                
                $this->db->where('rec_id', $recipe_id);
                $this->db->where_in('item_id', $delete_record_array);
                $this->db->where('PlantID', $selected_company);
                //$this->db->where('FY', $fy);
                $this->db->delete(db_prefix() . 'recipe_details');
                
				// Check Recipe Exit OR Not
				$ReceipDetails = $this->production_model->get_recipe_details($data["item_code"]);
				if($ReceipDetails){
					if($ReceipDetails->status == $data["status"]){
						// echo "pok";die;
						}else{
						if($data["status"] == "Y"){
							$OldRecUpdate = array(
                            'status'=>'Y',
                            'ActiveDate'=>date('Y-m-d H:i:s'),
                            'ADUserID'=>$this->session->userdata('username'),
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s')
							);
							}else{
							$OldRecUpdate = array(
                            'status'=>'N',
                            'DeActiveDate'=>date('Y-m-d H:i:s'),
                            'ADUserID'=>$this->session->userdata('username'),
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s')
							);
						}
					}
					
					// print_r($OldRecUpdate);die;
					$this->db->where(db_prefix() . 'recipe.item_code', $data["item_code"]);
					$this->db->where(db_prefix() . 'recipe.PlantID', $selected_company);
					//$this->db->where('FY', $fy);
					$UpdateOldReceipe = $this->db->update(db_prefix() . 'recipe', $OldRecUpdate);
				}    
                
				
				if($recipe_id){ 
                    $receipt_date = array(
					"item_code"=>$data["item_code"],
					"item_description"=>$data["item_desc"],
					"qty"=>$data["number_packets"],
					"unit"=>$data["unit_f_g1"],
					// "conv_cost"=>$data["conv_cost"],
					// "st_cost"=>$data["st_cost"],
					// "frt_cost"=>$data["frt_cost"],
					// "mrkt_cost"=>$data["mrkt_cost"],
					// "dmg_cost"=>$data["dmg_cost"],
					"std_yield"=>$data["std_yield"],
					"dough_wastage_per"=>$data["dough_wastage_per"],
					"yield_wastage_per"=>$data["yield_wastage_per"],
					"env_temp"=>$data["env_temp"],
					"env_humidity"=>$data["env_humidity"],
					"water_temp"=>$data["water_temp"],
					"batch_size"=>$data["batch_size"],
					"grammage_product"=>$data["grammage_product"],
					"dough_dividing_weight"=>$data["dough_dividing_weight"],
					"mould_length"=>$data["mould_length"],
					"mould_width"=>$data["mould_width"],
					"mould_depth"=>$data["mould_depth"],
					"wrapper_per_kg"=>$data["wrapper_per_kg"],
					"rate_wrapper_per_kg"=>$data["rate_wrapper_per_kg"],
					"refined_palm_oil"=>$data["refined_palm_oil"],
					"net_rec_rate"=>$data["net_rec_rate"],
					"ptr"=>$data["ptr"],
					"moisture_loss_post_ddw"=>$data["moisture_loss_post_ddw"],
					"volume_ratio_cc"=>$data["volume_ratio_cc"],
					"rm_size"=>$data["rm_size"],
					"dough_weight"=>$data["dough_weight"],
					"net_dough_weight"=>$data["net_dough_weight"],
					"yield_packets"=>$data["yield_packets"],
					"Is_Baking"=>$data["Is_Baking"],
					"UserID2"=>$this->session->userdata('username'),
					"Lupdate"=>date('Y-m-d H:i:s')
                    );
					
					$multiClause = array('id' => $recipe_id);
					$this->db->where($multiClause);   
					$query = $this->db->update(db_prefix() . 'recipe', $receipt_date);
					
					for($i=1; $i<$count; $i++) { 
						$itemid = "item_id".$i;
						$itemName = "item_name".$i;
						$reqQty = "req_qty".$i;
						$itemUnit = "unit".$i;
						$is_calculation = "is_calculation".$i;
						$itemrownum = "rownum".$i;
						
						if(in_array($data[$itemid], $new_record_array)){
							
							$new_record_details = array(
                            "PlantID"=>$selected_company,
                            "FY"=>$fy,
                            "item_id"=>$data[$itemid],
                            "item_name"=>$data[$itemName],
                            "req_qty"=>$data[$reqQty],
                            "unit"=>$data[$itemUnit],
                            "is_calculation"=>$data[$is_calculation],
                            "rec_id"=>$recipe_id,
                            "Ordinalno"=>$itemrownum,
                            "UserID"=>$this->session->userdata('username'),
                            "TransDate"=>date('Y-m-d H:i:s'),
							);
							$this->db->insert(db_prefix() . 'recipe_details', $new_record_details);
							// print_r($new_record_details); die;
						}
						
						if(in_array($data[$itemid], $edit_record_array)){
							$edit_record_details = array(
                            "req_qty"=>$data[$reqQty],
                            "is_calculation"=>$data[$is_calculation],
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s'),
							);
							// print_r($edit_record_details); die;
							$this->db->where('rec_id', $recipe_id);
							$this->db->where('item_id', $data[$itemid]);
							$this->db->where('PlantID', $selected_company);
							//$this->db->where('FY', $fy);
							$pr =$this->db->update(db_prefix() . 'recipe_details', $edit_record_details);
						}      
					}  //for end
					
					for($i=1; $i<$Pcount; $i++) { 
						$itemid = "Pitem_id".$i;
						$itemName = "Pitem_name".$i;
						$reqQty = "Preq_qty".$i;
						$itemUnit = "Punit".$i;
						$is_calculation = "Pis_calculation".$i;
						$itemrownum = "Prownum".$i;
						
						if(in_array($data[$itemid], $new_record_array)){
							
							$new_record_details = array(
                            "PlantID"=>$selected_company,
                            "FY"=>$fy,
                            "item_id"=>$data[$itemid],
                            "item_name"=>$data[$itemName],
                            "req_qty"=>$data[$reqQty],
                            "unit"=>$data[$itemUnit],
                            "is_calculation"=>$data[$is_calculation],
                            "rec_id"=>$recipe_id,
                            "Ordinalno"=>$itemrownum,
                            "UserID"=>$this->session->userdata('username'),
                            "TransDate"=>date('Y-m-d H:i:s'),
							);
							$this->db->insert(db_prefix() . 'recipe_details', $new_record_details);
							// print_r($new_record_details); die;
						}
						
						if(in_array($data[$itemid], $edit_record_array)){
							$edit_record_details = array(
                            "req_qty"=>$data[$reqQty], 
                            "is_calculation"=>$data[$is_calculation],
                            "UserID2"=>$this->session->userdata('username'),
                            "Lupdate"=>date('Y-m-d H:i:s'),
							);
							// print_r($edit_record_details); die;
							$this->db->where('rec_id', $recipe_id);
							$this->db->where('item_id', $data[$itemid]);
							$this->db->where('PlantID', $selected_company);
							//$this->db->where('FY', $fy);
							$pr =$this->db->update(db_prefix() . 'recipe_details', $edit_record_details);
						}      
					}  //for end
				} // if end
				//die;
				set_alert('success', 'Recipe Updated Successfully..');
                redirect(admin_url('production/editRecipe/'.$recipe_id));    
			}
			$this->load->model('sale_reports_model');
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['editRecipe_details'] = $this->production_model->edit_recipe($id);
			$data['editRecipe_details1'] = $this->production_model->edit_recipe1($id);
			$status = 'Y';
			$data['recipe_list'] = $this->production_model->load_data_for_recipe($status);
			$data['title'] = _l('Edit Recipe');
			$this->load->view('admin/production/edit_recipe', $data); 
			
		} 
		
		public function export_recipe(){
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$Recipe = $this->production_model->edit_recipe($this->input->post('id'));
				$Recipe_details = $this->production_model->edit_recipe1($this->input->post('id'));
				
				$this->load->model('accounts_master_model');
				$selected_company_details = $this->accounts_master_model->get_company_detail();
				$writer = new XLSXWriter();
				$j=0;
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				$j++;
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				$j++;
				
				$msg = "Recipe Details: ".$Recipe[0]['item_description'];
				// echo $msg;die;
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				$j++;
				
				
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Measured In"] =  'Measured In';
				$set_col_tk["Std. Yield"] =  'Std. Yield';
				$set_col_tk["Dough Wastage %"] =  'Dough Wastage %';
				$set_col_tk["Yield Of Packate Wastage %"] =  'Yield Of Packate Wastage %';
				$set_col_tk["Env. Temp."] =  'Env. Temp.';
				$set_col_tk["Env. Humidity"] =  'Env. Humidity';
				$set_col_tk["Water Temp"] =  'Water Temp';
				$set_col_tk["BATCH SIZE (KG) In Flour base"] =  'BATCH SIZE (KG) In Flour base';
				$set_col_tk["GRAMMAGE OF THE PRODUCT (ingm)"] =  'GRAMMAGE OF THE PRODUCT (ingm)';
				$set_col_tk["DOUGH DIVIDING WEIGHT"] =  'DOUGH DIVIDING WEIGHT';
				$set_col_tk["MOULD LENGTH"] =  'MOULD LENGTH';
				$set_col_tk["MOULD WIDTH"] =  'MOULD WIDTH';
				$set_col_tk["MOULD DEPTH"] =  'MOULD DEPTH';
				$set_col_tk["NUMBER OF WRAPPER PER KG"] =  'NUMBER OF WRAPPER PER KG';
				$set_col_tk["RATE OF WRAPPER PER KG"] =  'RATE OF WRAPPER PER KG';
				$set_col_tk["REFIND PALM OIL (MOULD)"] =  'REFIND PALM OIL (MOULD)';
				$set_col_tk["NET RCOVERY RATE"] =  'NET RCOVERY RATE';
				$set_col_tk["PTR"] =  'PTR';
				$set_col_tk["MOISTURE LOSS POST DDW"] =  'MOISTURE LOSS POST DDW';
				$set_col_tk["VOLUME/WEIGHT RATIO CC"] =  'VOLUME/WEIGHT RATIO CC';
				$set_col_tk["RM SIZE"] =  'RM SIZE';
				$set_col_tk["DOUGH WEIGHT"] =  'DOUGH WEIGHT';
				$set_col_tk["NET DOUGH WEIGHT @WASTAGE 1%"] =  'NET DOUGH WEIGHT @WASTAGE 1%';
				$set_col_tk["NUMBER OF PACKETS"] =  'NUMBER OF PACKETS';
				$set_col_tk["YEILD OF PACKET @ WASTAGE 1%"] =  'YEILD OF PACKET @ WASTAGE 1%';
				$set_col_tk["Is Baking Required"] =  'Is Baking Required';
				$set_col_tk["Status"] =  'Status';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				foreach ($Recipe as $k => $value) {
					
					$list_add = [];
					$list_add[] = $value['unit'];
					$list_add[] = $value['std_yield'];
					$list_add[] = $value['dough_wastage_per'];
					$list_add[] = $value['yield_wastage_per'];
					$list_add[] = $value['env_temp'];
					$list_add[] = $value['env_humidity'];
					$list_add[] = $value['water_temp'];
					$list_add[] = $value['batch_size'];
					$list_add[] = $value['grammage_product'];
					$list_add[] = $value['dough_dividing_weight'];
					$list_add[] = $value['mould_length'];
					$list_add[] = $value['mould_width'];
					$list_add[] = $value['mould_depth'];
					$list_add[] = $value['wrapper_per_kg'];
					$list_add[] = $value['rate_wrapper_per_kg'];
					$list_add[] = $value['refined_palm_oil'];
					$list_add[] = $value['net_rec_rate'];
					$list_add[] = $value['ptr'];
					$list_add[] = $value['moisture_loss_post_ddw'];
					$list_add[] = $value['volume_ratio_cc'];
					$list_add[] = $value['rm_size'];
					$list_add[] = $value['dough_weight'];
					$list_add[] = $value['net_dough_weight'];
					$list_add[] = $value['qty'];
					$list_add[] = $value['yield_packets'];
					if($value['yield_packets'] == 'Is_Baking'){
						$Is_Baking = "Yes";
						}else{
						$Is_Baking = "No";
					}
					
					if($value['status'] == 'Y'){
						$status = "Active";
						}else{
						$status = "Inactive";
					}
					
					$list_add[] = $Is_Baking;
					$list_add[] = $status;
					
					
					$writer->writeSheetRow('Sheet1', $list_add);
					
				}
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$msg1 = "Raw Material Summary :-";
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter1);
				$j++;
				
				
				$set_col_tk = [];
				$set_col_tk["Item Code"] =  'Item Code';
				$set_col_tk["Item Name"] =  'Item Name';
				$set_col_tk["Is Calculation?"] =  'Is Calculation?';
				$set_col_tk["Req. Qty"] =  'Req. Qty';
				$set_col_tk["Measured In"] =  'Measured In';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				foreach ($Recipe_details as $k => $value) {
					if($value['MainGrpID'] == '2'){
						$list_add = [];
						$list_add[] = $value["item_id"];
						$list_add[] = $value["item_name"];
						
						if($value['is_calculation'] == 'Y'){
							$is_calculation = "Yes";
							}else{
							$is_calculation = "No";
						}
						
						$list_add[] = $is_calculation;
						$list_add[] = $value["req_qty"];
						$list_add[] = $value["unit"];
						
						
						$writer->writeSheetRow('Sheet1', $list_add);
					}
				}
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				$msg1 = "Packaging Material Summary :-";
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter1);
				$j++;
				
				
				
				$set_col_tk = [];
				$set_col_tk["Item Code"] =  'Item Code';
				$set_col_tk["Item Name"] =  'Item Name';
				$set_col_tk["Is Calculation?"] =  'Is Calculation?';
				$set_col_tk["Req. Qty"] =  'Req. Qty';
				$set_col_tk["Measured In"] =  'Measured In';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				foreach ($Recipe_details as $k => $value) {
					if($value['MainGrpID'] == '3'){
						$list_add = [];
						$list_add[] = $value["item_id"];
						$list_add[] = $value["item_name"];
						if($value['is_calculation'] == 'Y'){
							$is_calculation = "Yes";
							}else{
							$is_calculation = "No";
						}
						$list_add[] = $is_calculation;
						$list_add[] = $value["req_qty"];
						$list_add[] = $value["unit"];
						$writer->writeSheetRow('Sheet1', $list_add);
						
					}
				}
				// echo "ok";die;
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$sanitized_item_description = preg_replace('/[\/:*?"<>|]/', '_', $Recipe[0]['item_description']); // Replace invalid characters with _
				$filename = 'Recipe For '.$sanitized_item_description.'.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
			
		}
		
		public function production_order()
		{
			if (!has_permission_new('production', '', 'view')) {
				ajax_access_denied();
			}
			if($this->input->post()){
				if (!has_permission_new('production', '', 'edit')) {
					ajax_access_denied();
				}
				
				$data = $this->input->post();
				$proId = $this->input->post('pid');
				
				$count = $data["countof_record"];
				$count1 = $data["countof_record1"];   
				
				$new_record = $data["new_record"];
				$new_record = str_replace(" ,",'',$data["new_record"]);
				$new_record_array = explode(',', $new_record);
				
				$new_record1 = $data["new_record1"];
				$new_record1 = str_replace(" ,",'',$data["new_record1"]);
				$new_record_array1 = explode(',', $new_record1);
				
				$selected_company = $this->session->userdata('root_company');
				$fy = $this->session->userdata('finacial_year');
				$LogIn = $this->session->userdata('username');
				$GodownID = $data["GodownID"];
				$selected_company = $this->session->userdata('root_company');
				if($selected_company == "1"){
					$GodownIDF = 'CSPL';
					}else if($selected_company == "2"){
					$GodownIDF = 'CFF';
					}else if($selected_company == "3"){
					$GodownIDF = 'CBUPL';
				}
				
				if($data["status"]=="pending"){
					if($data["opttype"] == "1"){
						$production_data = array(
                        "TransDate"=>to_sql_date($data["start_date"]).' '.date('H:i:s'),
                        "required_time"=>$data["req_time"],
                        "batch_qty"=>$data["batch_qty"],
                        "GodownID"=>$GodownID,
                        "Finish_good_qty"=>$data["qty_product"],
                        "Finish_good_qty_new"=>$data["qty_product"],
						"env_temp"=>$data["env_temp"],
						"env_humidity"=>$data["env_humidity"],
						"water_temp"=>$data["water_temp"],
                        "shift"=>$data["shift"],
                        "remark"=>$data["remark"],
                        "comment"=>$data["comments"],
                        "production_status"=>$data["status"],
                        "manager_name"=>$data["operator_name"],
                        "UserID2"=>$LogIn,
                        "Lupdate"=>date('Y-m-d H:i:s')
						);
						}else{
						$production_data = array(
                        "TransDate"=>to_sql_date($data["start_date"]).' '.date('H:i:s'),
                        "required_time"=>$data["req_time"],
                        "batch_qty"=>$data["batch_qty"],
                        "GodownID"=>$GodownID,
                        "Finish_good_qty"=>$data["qty_product"],
                        "Finish_good_qty_new"=>$data["qty_product"],
						"env_temp"=>$data["env_temp"],
						"env_humidity"=>$data["env_humidity"],
						"water_temp"=>$data["water_temp"],
                        "shift"=>$data["shift"],
                        "remark"=>$data["remark"],
                        "comment"=>$data["comments"],
                        "production_status"=>$data["status"],
                        "contractor_name"=>$data["operator_name"],
                        "UserID2"=>$LogIn,
                        "Lupdate"=>date('Y-m-d H:i:s')
						);
					}
					
					$productionChange = array(
                    'pro_order_id' => $proId,
                    'PlantID' => $selected_company,
                    'FY' => $fy
					);
					$this->db->where($productionChange);    
					$query = $this->db->update(db_prefix() . 'production', $production_data);
					
					// Update  Godown ID
					
					$GodownUpdate = array(
                    "GodownID"=>$GodownID,
					);
					$this->db->where('production_id', $proId);
					$this->db->where('PlantID', $selected_company);
					$this->db->where('FY', $fy);
					$this->db->update(db_prefix() . 'production_details', $GodownUpdate);
					
					$production_details = $this->production_model->get_production_order($proId);
					foreach ($production_details->items as $key => $value) {
						$production_req_qty = $value["req_qty"] * $data["batch_qty"];
						$edit_prd_qty = array(
						"production_req_qty"=>$production_req_qty,
						"UserID2"=>$LogIn,
						"Lupdate"=>date('Y-m-d H:i:s')
						);
						$this->db->where('production_id', $proId);
						$this->db->where('item_id', $value["item_id"]);
						$pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
					}
					
					// Add extra qty    
					for($i=1; $i<$count; $i++) { 
						
						$itemid = "item_id".$i;
						$itemName = "item_name".$i;
						$reqQty = "req_qty".$i;
						$itemUnit = "unit".$i;
						
						if($new_record_array){  
							foreach ($production_details->items as $key => $value) {
								if($data[$itemid] == $value["item_id"]){
									$newExtraQty = $value["ExtraQty"] + $data[$reqQty];
								}
							}
							$edit_prd_qty = array(
                            "ExtraQty"=>$newExtraQty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
							);
							$this->db->where('production_id', $proId);
							$this->db->where('item_id', $data[$itemid]);
							$pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
						}
					}
					
					// return qty 
					for($j=1; $j<$count1; $j++) {
						$itemid = "pro_item_id".$j;
						$itemName = "pro_item_name".$j;
						$reqQty = "pro_req_qty".$j;
						$return_reqQty = "return_pro_req_qty".$j;
						$itemUnit = "pro_unit".$j;
						
						if($new_record_array1){
							foreach ($production_details->items as $key => $value) {
								if($data[$itemid] == $value["item_id"]){
									$new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
								}
							}
							$edit_record_details = array(
                            "return_req_qty"=>$new_rtn_qty,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
							);
                            
							$this->db->where('production_id', $proId);
							$this->db->where('item_id', $data[$itemid]);
							$pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
							
						} 
					}
					}else if($data["status"]=="In-Progress"){
                    $PRDDetails = $this->production_model->GetPrdDetails($proId);
                    /*if($selected_company == "3"){
                        // Check Row Material Stock 
                        if($PRDDetails->status_count == "0"){
						$PRDItemStocks = $this->production_model->GetPRDItemStock($proId,$GodownID);
						$PRDItemOQty = $this->production_model->GetPRDItemOQty($proId,$GodownID);
						// echo "ok";die;
						foreach ($PRDDetails->items as $key => $value) {
						$PRDQtyNew = 0;
						$PRDQtyNew += $value["production_req_qty"] + $value["return_req_qty"] + $value["ExtraQty"];
						// Add extra qty
						for($i=1; $i<$count; $i++) { 
						$itemid = "item_id".$i;
						$itemName = "item_name".$i;
						$reqQty = "req_qty".$i;
						$itemUnit = "unit".$i;
						if($new_record_array){
						if($data[$itemid] == $value["item_id"]){
						$PRDQtyNew += $data[$reqQty];
						}
						}
						}
						// return qty 
						for($j=1; $j<$count1; $j++) {
						$itemid = "pro_item_id".$j;
						$itemName = "pro_item_name".$j;
						$reqQty = "pro_req_qty".$j;
						$return_reqQty = "return_pro_req_qty".$j;
						$itemUnit = "pro_unit".$j;
						if($new_record_array1){
						if($data[$itemid] == $value["item_id"]){
						$PRDQtyNew = $PRDQtyNew - $data[$return_reqQty];
						}
						}
						}
						$PQty = 0;
						$PRQty = 0;
						$IQty = 0;
						$PRDQty = 0;
						$SQty = 0;
						$SRTQty = 0;
						$AQty = 0;
						$OQty = 0;
						$GOQty = 0;
						$GIQty = 0;
						// echo "<pre>";
						// print_r($PRDItemStocks);
						// die;
						foreach ($PRDItemOQty as $Ostock) {
						if(strtoupper($Ostock['ItemID'])==strtoupper($value['item_id'])){
						$OQty = $Ostock['OQty'];
						}
						}
						foreach ($PRDItemStocks as $stock) {
						if(strtoupper($stock['ItemID'])==strtoupper($value['item_id'])){
						
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
						}
						$stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty + $GIQty - $GOQty;
						if($stockQty <= $PRDQtyNew){
						set_alert('warning', $value['item_id'].'== '.$stockQty);
						redirect(admin_url('production/production_order/'.$proId));
						}
						}
                        }
                        // End stock check
					}*/
                    if($PRDDetails->status_count !== "0"){
                        if(isset($data["finish_outcome"]) && $data["finish_outcome"] !== "" && $data["finish_outcome"] !== "0.00"){
                            if($PRDDetails->p_end_time == "" || $PRDDetails->p_end_time == null){
                                $p_end_time = date('Y-m-d H:i:s');
								}else{
                                $p_end_time = $PRDDetails->p_end_time;
							}
                            $Finish_good_qty_new = $data["finish_outcome"];
							}else{
                            $Finish_good_qty_new = 0.00;
						}
                        
                        
                        if($data["opttype"] == "1"){
                            $production_data = array(
							"required_time"=>$data["req_time"],
							"batch_qty"=>$data["batch_qty"],
							"GodownID"=>$GodownID,
							"p_end_time"=>$p_end_time,
							"remark"=>$data["remark"],
							"comment"=>$data["comments"],
							"production_status"=>$data["status"],
							"manager_name"=>$data["operator_name"],
							"status_count"=>1,
							"UserID2"=>$LogIn,
							"Lupdate"=>date('Y-m-d H:i:s')
                            );
							}else{
                            $production_data = array(
							"required_time"=>$data["req_time"],
							"batch_qty"=>$data["batch_qty"],
							"GodownID"=>$GodownID,
							"p_end_time"=>$p_end_time,
							"remark"=>$data["remark"],
							"comment"=>$data["comments"],
							"production_status"=>$data["status"],
							"contractor_name"=>$data["operator_name"],
							"status_count"=>1,
							"UserID2"=>$LogIn,
							"Lupdate"=>date('Y-m-d H:i:s')
                            );
						}
					}
					
					if($PRDDetails->status_count == "0"){
						if($data["opttype"] == "1"){
							$production_data = array(
                            "required_time"=>$data["req_time"],
                            "batch_qty"=>$data["batch_qty"],
                            "GodownID"=>$GodownID,
                            "Finish_good_qty"=>$data["qty_product"],
                            "Finish_good_qty_new"=>$data["qty_product"],
                            "p_start_time"=>date('Y-m-d H:i:s'),
                            "remark"=>$data["remark"],
                            "comment"=>$data["comments"],
                            "production_status"=>$data["status"],
                            "manager_name"=>$data["operator_name"],
                            "status_count"=>1,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
							);
							}else{
							$production_data = array(
                            "required_time"=>$data["req_time"],
                            "batch_qty"=>$data["batch_qty"],
                            "GodownID"=>$GodownID,
                            "Finish_good_qty"=>$data["qty_product"],
                            "Finish_good_qty_new"=>$data["qty_product"],
                            "p_start_time"=>date('Y-m-d H:i:s'),
                            "remark"=>$data["remark"],
                            "comment"=>$data["comments"],
                            "production_status"=>$data["status"],
                            "contractor_name"=>$data["operator_name"],
                            "status_count"=>1,
                            "UserID2"=>$LogIn,
                            "Lupdate"=>date('Y-m-d H:i:s')
							);
						}
					}    
                    
                    $productionChange = array(
					'pro_order_id' => $proId,
					'PlantID' => $selected_company,
					'FY' => $fy
                    );
                    $this->db->where($productionChange);    
                    $query = $this->db->update(db_prefix() . 'production', $production_data);
                    
                    // Add extra qty    
                    for($i=1; $i<$count; $i++) { 
						
                        $itemid = "item_id".$i;
                        $itemName = "item_name".$i;
                        $reqQty = "req_qty".$i;
                        $itemUnit = "unit".$i;
						
                        if($new_record_array){  
                            foreach ($PRDDetails->items as $key => $value) {
                                if($data[$itemid] == $value["item_id"]){
                                    $newExtraQty = $value["ExtraQty"] + $data[$reqQty];
								}
							}
                            $edit_prd_qty = array(
							"ExtraQty"=>$newExtraQty,
							"UserID2"=>$LogIn,
							"Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where('production_id', $proId);
                            $this->db->where('item_id', $data[$itemid]);
                            $pr=$this->db->update(db_prefix() . 'production_details', $edit_prd_qty);
						}
					}
					
                    // return qty 
                    for($j=1; $j<$count1; $j++) {
                        $itemid = "pro_item_id".$j;
                        $itemName = "pro_item_name".$j;
                        $reqQty = "pro_req_qty".$j;
                        $return_reqQty = "return_pro_req_qty".$j;
                        $itemUnit = "pro_unit".$j;
						
                        if($new_record_array1){
                            foreach ($PRDDetails->items as $key => $value) {
                                if($data[$itemid] == $value["item_id"]){
                                    $new_rtn_qty = $value["return_req_qty"] + $data[$return_reqQty];
								}
							}
                            $edit_record_details = array(
							"return_req_qty"=>$new_rtn_qty,
							"UserID2"=>$LogIn,
							"Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where('production_id', $proId);
                            $this->db->where('item_id', $data[$itemid]);
                            $pr=$this->db->update(db_prefix() . 'production_details', $edit_record_details);  
						} 
					}
					
					
					if($PRDDetails->status_count !== "0"){
						// Update History Table for Row Material
						$GetRowMaterial = $this->production_model->GetRowMaterialDetails($proId);
                        foreach ($GetRowMaterial as $key => $value) {
							if($value['MainGrpID'] == '3'){
								continue;	
							}
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            $whereIssue_row = array(
							'ItemID' =>$value["item_id"],
							'OrderID' => $proId,
							'PlantID' => $selected_company,
							'FY' => $fy
                            );
                            $history_IssueUpdate = array(
							'OrderQty' =>$acutal_issue,
							'BilledQty' =>$acutal_issue,
							'GodownID' => $GodownID,
							"UserID2"=>$LogIn,
							"Lupdate"=>date('Y-m-d H:i:s')
                            );
                            $this->db->where($whereIssue_row);    
                            $query = $this->db->update(db_prefix() . 'history', $history_IssueUpdate);
						}
						
						}else{
						
                        $FGDetails = $this->production_model->GetPrdFGDetails($proId);   
                        if($FGDetails->manager_name == null){
                            $accountId = $FGDetails->contractor_name;
							}else{
                            $accountId = $FGDetails->manager_name;
						}
                        
                        // Move Row Material to History table
                        $RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
                        $RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
                        $i = 1;
                        foreach ($RMItemDetails as $key => $value) {
							if($value['MainGrpID'] == '3'){
								continue;	
							}
                            $acutal_issue = $value["production_req_qty"] - $value["return_req_qty"] + $value["ExtraQty"];
                            foreach ($RMItemOtherDetails as $key1 => $value1) {
                                if($value["item_id"] == $value1["item_id"]){
                                    $CaseQty = $value1["CaseQty"];
                                    $BasicRate = $value1["BasicRate"];
                                    $SaleRatee = $value1["SaleRate"];
								}
                                
							}
                            $history_details = array(
							'PlantID' =>$selected_company,
							'FY' =>$fy,
							'cnfid' =>1,
							'OrderID' =>$proId,
							'TransDate' =>$value["TransDate"],
							'BillID' =>$proId,
							'TransID' =>$proId,
							'TransDate2' =>$value["TransDate"],
							'TType' =>"A",
							'TType2' =>"Issue",
							'AccountID' =>$accountId,
							'GodownID' =>$GodownID,
							'CaseQty' =>$CaseQty,
							'BasicRate' =>$BasicRate,
							'SaleRate' =>$SaleRatee,
							'ItemID' =>$value["item_id"],
							'OrderQty' =>$acutal_issue,
							'BilledQty' =>$acutal_issue,
							'Ordinalno' =>$i,
							'UserID' =>$value["UserID"]
                            );
                            $this->db->insert(db_prefix() . 'history', $history_details);
                            $i++;
						}
					}
					
					}else if($data["status"]=="cancel"){
					
                    $production_details = $this->production_model->get_PRD_DetailsFromHistory($proId);
                    if($production_details){
						
						// Delete record from History
                        $this->db->where(db_prefix() . 'history.OrderID', $proId);
                        $this->db->delete(db_prefix() . 'history');
					}
                    $production_data = array(
					"remark"=>$data["remark"],
					"comment"=>$data["comments"],
					"production_status"=>$data["status"],
                    );
                    $productionChange = array(
					'pro_order_id' => $proId,
					'PlantID' => $selected_company,
					'FY' => $fy
                    );
                    $this->db->where($productionChange);    
                    $query = $this->db->update(db_prefix() . 'production', $production_data);
				}
				set_alert('success', 'Production Order Updated Successfully..');
				redirect(admin_url('production/production_order/'.$proId));
			}
			$this->load->model('sale_reports_model');
			$pro_orid =$this->uri->segment(4); 
			$data['manager'] = $this->production_model->get_managername();
			$data['contractor'] = $this->production_model->get_contractorname();
			$data['production'] = $this->production_model->GetPrdDetails($pro_orid);
			$GodownID = $data['production']->GodownID;
			$PrdItem = array();
			/*echo "<pre>";
				echo $pro_orid;
				print_r($data['production']);
			die;*/
			foreach ($data['production']->items as $key=>$value) {
				array_push($PrdItem, $value["item_id"]);
			}
			//$data['ItemStocks'] = $this->production_model->GetPRDItemStock($pro_orid);
			$data['ItemStocks'] = $this->production_model->GetPRDItemStockNew($PrdItem,$GodownID);
			$data['OQtyItems'] = $this->production_model->GetPRDItemOQty($pro_orid,$GodownID);
			$data['BakingList'] = $this->production_model->GetBakingList($pro_orid);
			$data['PackingList'] = $this->production_model->GetPackingList($pro_orid);
			$data['GodownData'] = $this->production_model->GetGodownData();
			$data['ReceipeDetails'] = $this->production_model->get_receipeDetails($data['production']->recipeID);
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['title'] = _l('Production Order');
			// echo "<pre>";
			// print_r($data['BakingList']);
			// die;
			
			$this->load->view('admin/production/production_order', $data);
		}
		
		public function load_data_for_production()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'status_list'  => $this->input->post('status_list')
			);
			$data = $this->production_model->load_data_for_production($data);
			if(count($data) >0){
				$minutes = 0;
				$i = 1; 
				foreach($data as $value){
					$url = '"'.admin_url('production/production_order/' . $value['pro_order_id']).'"';
					$html1.= '<tr onclick=location.href='.$url.'>'; 
					$html1.= '<td>'.$i.'</td>';
					$html1.= '<td>'.$value['pro_order_id'].'</td>';
					
					$html1.= '<td align="left">'. _d(substr($value["TransDate"],0,10)).'</td>';
					$html1.= '<td>'.strtoupper($value['recipeID']).'</td>';
					$html1.= '<td>'.strtoupper($value['description']).'</td>';
					$html1.= '<td align="right">'.$value['batch_qty'].'</td>';
					$html1.= '<td align="right">'.$value['Finish_good_qty'].'</td>';
					$html1.= '<td align="right">'.$value['Finish_good_qty_new'].'</td>';
					$diff = $value['Finish_good_qty'] - $value['Finish_good_qty_new'];
					$html1.= '<td align="right">'.number_format($diff,2,'.','').'</td>';
					/*$html1.= '<td align="right">'.$value['required_time'].'</td>';
						$dateTimeObject1 = date_create($value['p_start_time']); 
						$dateTimeObject2 = date_create($value['p_end_time']); 
						$difference = date_diff($dateTimeObject1, $dateTimeObject2);
						$minutes = $difference->days * 24 * 60;
						$minutes += $difference->h * 60;
						$minutes += $difference->i;
					$html1.= '<td style="text-align:right;">'.$minutes.'</td>';*/
					
					if($value['contractor_name'] == null){
						if($value['lastname'] == null){
							$AccoutName = $value['firstname'];
							}else{
							$AccoutName = $value['firstname'].' '.$value['lastname'];
						}
						}else{ 
						$AccoutName = $value['conName'];
					}
					
					$html1.= '<td>'.$AccoutName.'</td>';
					$html1.= '<td>'.$value['production_status'].'</td>';
					$html1.= '</tr>';
					$i++;
				}
				}else{
				$html1.= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html1);
		}
		public function load_data_for_production_baking()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'status_list'  => $this->input->post('status_list')
			);
			$data = $this->production_model->load_data_for_production($data);
			if(count($data) >0){
				$minutes = 0;
				$i = 1; 
				$html1 = '';
				foreach($data as $value){
					$url = '"'.admin_url('production/production_order/' . $value['pro_order_id']).'"';
					
					if($value['PackingQty'] > 0){
						$isEditable = 'disabled';
						}else{
						$isEditable = '';
					}
					$html1.= '<tr>'; 
					$html1.= '<td>'.$i.'</td>';
					$html1.= '<td><input type="checkbox" class="selected_prd_id"  value="'.$value['pro_order_id'].'" '.$isEditable.'></td>';
					$html1.= '<td>'.$value['shift'].'</td>';
					$html1.= '<td><span class="Prd_id">'.$value['pro_order_id'].'</span></td>';
					$html1.= '<td align="left">'. _d(substr($value["TransDate"],0,10)).'</td>';
					$html1.= '<td><span class="recipeID">'.strtoupper($value['recipeID']).'</span></td>';
					$html1.= '<td>'.strtoupper($value['description']).'</td>';
					$html1.= '<td align="right">'.$value['std_batch_qty'].'</td>';
					$html1.= '<td align="right">'.$value['batch_qty'].'</td>';
					$html1.= '<td align="right"><span class="StdBakingQty">'.intval($value['Finish_good_qty']).'</span></td>';
					$html1.= '<td align="right"><input type="text"  onblur="CalculateDamageBaking()" class="actual_bakingQty" id="actual_bakingQty'.$i.'" value="'.$value['BakingQty'].'" '.$isEditable.'></td>';
					$diff = intval($value['Finish_good_qty']) - $value['BakingQty'];
					$html1.= '<td align="right"><span class="DamageQty">'.number_format($diff,2,'.','').'</span></td>';
					
					$html1.= '<td>'.$value['production_status'].'</td>';
					$html1.= '</tr>';
					$i++;
				}
				}else{
				$html1.= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html1);
		}
		public function load_data_for_production_packing()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'status_list'  => $this->input->post('status_list')
			);
			$data = $this->production_model->load_data_for_production($data);
			if(count($data) >0){
				$minutes = 0;
				$i = 1; 
				foreach($data as $value){
					$url = '"'.admin_url('production/production_order/' . $value['pro_order_id']).'"';
					
					if($value['production_status'] == 'Completed'){
						$isEditable = 'disabled';
						}else{
						$isEditable = '';
					}
					
					$show = true;
					if($value['Is_Baking'] == 'Y' && $value['BakingQty'] == ''){
						$show = false;
					}
					
					if($show){
						$html1.= '<tr>'; 
						$html1.= '<td>'.$i.'</td>';
						$html1.= '<td><input type="checkbox" class="selected_prd_id"  value="'.$value['pro_order_id'].'" '.$isEditable.'></td>';
						$html1.= '<td>'.$value['shift'].'</td>';
						$html1.= '<td><span class="Prd_id">'.$value['pro_order_id'].'</span></td>';
						$html1.= '<td align="left">'. _d(substr($value["TransDate"],0,10)).'</td>';
						$html1.= '<td><span class="recipeID">'.strtoupper($value['recipeID']).'</span></td>';
						$html1.= '<td>'.strtoupper($value['description']).'</td>';
						$html1.= '<td align="right">'.$value['batch_qty'].'</td>';
						$html1.= '<td align="right"><span class="StdPackingQty">'.intval($value['Finish_good_qty']).'</span></td>';
						$html1.= '<td align="right"><span class="BakingQty">'.$value['BakingQty'].'</span></td>';
						
						$html1.= '<td align="right"><input type="text"  onblur="CalculateDamagePacking()" '.$isEditable.' class="actual_packingQty" id="actual_packingQty'.$i.'" readonly value="'.$value['PackingQty'].'"></td>';
						$html1.= '<td align="right"><input type="text"  onblur="CalculateActualPacking()" '.$isEditable.' class="packingQty" id="packingQty'.$i.'" value=""></td>';
						$diff = intval($value['Finish_good_qty']) - $value['PackingQty'];
						$html1.= '<td align="right"><span class="DamageQty">'.intval($diff).'</span></td>';
						
						$html1.= '<td>'.$value['production_status'].'</td>';
						$html1.= '</tr>';
						$i++;
					}
					
				}
				}else{
				$html1.= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html1);
		}
		public function export_productionReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'status_list'  => $this->input->post('status_list')
				);
				$data = $this->production_model->load_data_for_production($data); 
				$this->load->model('sale_reports_model');    
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				
				$writer = new XLSXWriter();
				//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Production Report : ".$this->input->post('from_date')." To " .$this->input->post('to_date') ." For : ".$this->input->post('status_list');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["ProductionID"] =  'ProductionID';
				$set_col_tk["PRDDate"] =  'PRDDate';
				$set_col_tk["RecipeName"] =  'RecipeName';
				$set_col_tk["description"] =  'ItemName';
				$set_col_tk["BatchQty"] =  'BatchQty';
				$set_col_tk["FGQty"] =  'STD FGQty';
				$set_col_tk["AcctualQty"] =  'Acctual Qty';
				$set_col_tk["DiffQty"] =  'Diff. Qty';
				/*$set_col_tk["ReqTM"] =  'ReqTM';
				$set_col_tk["PRDTM"] =  'PRDTM';*/
				$set_col_tk["Man/Con Name"] =  'Man/Con Name';
				$set_col_tk["Status"] =  'Status';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				foreach ($data as $k => $value) 
				{
					$list_add = [];
					$list_add[] = $value["pro_order_id"];
					$date = _d(substr($value["TransDate"],0,10));
					$list_add[] = $date;
					$list_add[] = $value["recipeID"];
					$list_add[] = $value["description"];
					$list_add[] = $value["batch_qty"];
					$list_add[] = $value["Finish_good_qty"];
					$list_add[] = $value["Finish_good_qty_new"];
					$diff = $value["Finish_good_qty"] - $value["Finish_good_qty_new"];
					$list_add[] = number_format($diff,2,'.','');
					//$list_add[] = $value["required_time"];
					
					/*$dateTimeObject1 = date_create($value['p_start_time']); 
						$dateTimeObject2 = date_create($value['p_end_time']); 
						$difference = date_diff($dateTimeObject1, $dateTimeObject2);
						$minutes = $difference->days * 24 * 60;
						$minutes += $difference->h * 60;
					$minutes += $difference->i;*/
					//$list_add[] = $minutes;
					if($value["contractor_name"] == null){
						if($value["lastname"] == null){
							$AccoutName = $value["firstname"];
							}else{
							$AccoutName = $value["firstname"].' '.$value["lastname"];
						}
						}else{ 
						$AccoutName = $value["conName"];
					}
					$list_add[] = $AccoutName;
					$list_add[] = $value["production_status"];
					
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'Production list.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function view_production_list(){
			if (!has_permission_new('production_list', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			$data['title'] = "Production List";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$this->load->view('admin/production/production_list', $data);
		}
		public function production_order_report(){
			if (!has_permission_new('production_order_report', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			
			//$data['items_main_groups'] = $this->production_model->get_sub_groups();
			$data['title'] = "Production Order Wise Report";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			
			$data['PrdList'] = $this->production_model->PRDList();
			$this->load->view('admin/production/production_order_wise_report', $data);
		}
		
		/* Get Production Details by ItemID / ajax */
		public function GetPRDDetailByID()
		{
			$PRDID = $this->input->post('PRDID');
			$itemPRDDetails  = $this->production_model->getPRDDetailsByID($PRDID);
			echo json_encode($itemPRDDetails);
		}
		public function load_table_production_report(){
			$this->load->model('accounts_master_model');
			$company_data = $this->accounts_master_model->get_company_detail();
			$Pro_report = $this->production_model->pro_order_report($this->input->post());
			
			$Pro_details = $this->production_model->pro_order_report_details($this->input->post());
            // print_r($Pro_details);die;
			$html =''; 
			$html .='<span>Status of PO: <b>'.$Pro_report['production_status'].'</b></span>'; 
			
            $html .= '<table class="table-striped table-bordered production_table" id="production_table" width="100%">';
            $html .= '<thead style="font-size:11px;">';
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="9"><b class="co_name">'.$company_data->company_name.'</b></th>';
			$html.= '</tr>';
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="9"><b class="co_add">'.$company_data->address.'</b></th>';
			$html.= '</tr>';
			$html .= '<tr style="display:none;">';
            $html .= '<th colspan="9"><b >';
            $html .= 'Report : '.$this->input->post('pro_order_id'); 
            $html.= '</b> </th>';
            $html .= '<th colspan="9"><b >';
            $html .= 'Status of PO : '.$Pro_report['production_status']; 
			
            $html.= '</b> </th>';
            $html.= '</tr>';
			
			$html.= '<tr>';
			$html.= '<th align="left">ItemName</th>';
			$html.= '<th align="left">Date</th>';
			$html.= '<th align="left">No of Batches </th>';
			$html.= '<th align="left">Output as per Receipe</th>';
			$html.= '<th align="left">Actual Output in Pc</th>';
			$html.= '<th align="left">Diffrence % (if Negative its loss)</th>';
			$html.= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $total = 0;
            $prec =0;
            $prec_fg_total = 0;
			
            $html.= '<tr>';
			
			$html.= '<td align="left">'.$Pro_report['description'].'</td>';
			$html.= '<td align="left">'._d(substr($Pro_report['TransDate'],0,10)).'</td>';
			$html.= '<td align="right">'.$Pro_report['batch_qty'].'</td>';
			$html.= '<td align="right">'.$Pro_report['Finish_good_qty'].'</td>';
			$html.= '<td align="right">'.$Pro_report['Finish_good_qty_new'].'</td>';
			$total = $Pro_report['Finish_good_qty_new']-$Pro_report['Finish_good_qty'];
			if($total > 0){
				$prec =  ($total*100)/$Pro_report['Finish_good_qty'];
				}else{
                $total = $total*-1;
				$prec =  ($total*100)/$Pro_report['Finish_good_qty'];
				$prec = $prec*-1;
			}
			if($Pro_report['Finish_good_qty_new'] == '' || $Pro_report['Finish_good_qty_new'] == 0){
				
				$prec = '';
				$prec_fg_total = 0;
				}else{
				$prec_fg_total =  ($Pro_report['Finish_good_qty_new']*100)/$Pro_report['Finish_good_qty'];
			}
			$html.= '<td align="right">'.number_format($prec,2).'</td>';
			
			
            
			
            $html.= '</tr>';
			
			$html .= '</tbody>';
			$html .= '<table>';
			
			
			$html1 =''; 
            $html1 .= '<table class="table-striped table-bordered row_material_table" id="row_material_table" width="100%">';
            $html1 .= '<thead style="font-size:11px;">';
			$html1 .= '<tr style="display:none;">';
			$html1 .= '<th colspan="9"><b class="co_name">'.$company_data->company_name.'</b></th>';
			$html1.= '</tr>';
			$html1 .= '<tr style="display:none;">';
			$html1 .= '<th colspan="9"><b class="co_add">'.$company_data->address.'</b></th>';
			$html1.= '</tr>';
			
			$html1 .= '<tr style="display:none;">';
            $html1 .= '<th colspan="9"><b >';
            $html1 .= '<span class="report_for" style="font-size:10px;">Report : '.$this->input->post('pro_order_id').',Status of PO : '.$Pro_report['production_status'].'</span>'; 
            $html1.= '</b> </th>';
            $html1 .= '<th colspan="9"><b >';
            $html1 .= 'Status of PO : '.$Pro_report['production_status']; 
			
            $html1.= '</b> </th>';
            $html1.= '</tr>';
			
			$html1.= '<tr>';
			$html1.= '<th align="center">SrNo</th>';
			$html1.= '<th align="left">ItemName </th>';
			$html1.= '<th align="left">RM Qty. as per Receipe</th>';
			$html1.= '<th align="left">Actual RM Qty.</th>';
			$html1.= '<th align="left">Diff. in Qty.</th>';
			$html1.= '</tr>';
            $html1 .= '</thead>';
            $html1 .= '<tbody>';
            $actual_q = 0;
            $diffrence_q = 0;
            $total_actual = 0;
			$i = 1; foreach($Pro_details as $value){
				$html1.= '<tr>';
				
				$html1.= '<td align="center">'.$i.'</td>';
				$html1.= '<td align="left">'.$value['item_name'].'</td>';
				$html1.= '<td align="right">'.number_format($value['production_req_qty'],2).'</td>';
				$actual_q = ($value['production_req_qty'] + $value['ExtraQty']) - ($value['return_req_qty']);
				$html1.= '<td align="right">'.number_format($actual_q,2).'</td>';
				$total_actual+=$actual_q;
				$diffrence_q = $actual_q - $value['production_req_qty'];
				$html1.= '<td align="right">'.number_format($diffrence_q,2).'</td>';
				
				
				
				$html1.= '</tr>';
			$i++; }
			$html1 .= '</tbody>';
			$html1 .= '<table>';
			
			$html2 =''; 
			$html2 .= '<table class="table text-right" style="width: 38%;" id="lower_table">';
			$html2 .= '<tbody>';
			$html2 .= '<tr id="">';
			$html2 .= '<td width="50%" id="" style="border:none!important;">';
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_rm">Total RM in KG</label>';  
			$html2 .= '<div class="input-group" id="">';
			$html2 .= '<input type="text" readonly="" class="form-control text-right" name="total_rm_kg" value="'.number_format($total_actual,2).'">';
			$html2 .= '</div>';
			$html2 .= '</td>';
			$html2 .= '</tr>';
			$html2 .= '<tr>';
			$html2 .= '<td  style="border:none!important;"> '; 
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_fg">Total FG</label>';  
			$html2 .= '<div class="input-group" id="">';
			$html2 .= '<input type="text" readonly="" value="'.number_format($Pro_report['Finish_good_qty_new'],2).' '.$Pro_report['finish_good_unit'].'" class="form-control pull-left text-right" name="total_fg">';
			$html2 .= '</div>';
			$html2 .= '</td>';
			
			$html2 .= '</tr>';
			$html2 .= '<tr id=" ">';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="output">Output %</label>';  
			$html2 .= '<div class="input-group" id="">';
			$html2 .= '<input type="text" readonly="" value="'.number_format($prec_fg_total,2).'" class="form-control pull-left text-right"  name="output">';
			$html2 .= ' </div>';
			$html2 .= '</td>';
			
			$html2 .= '</tr>';
			$html2 .= '<tr>';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="oil_comsumption">Oil Comsumption %</label>';  
			$html2 .= '<div class="input-group">';
			$html2 .= '<input type="text" readonly="" value="" class="form-control pull-left text-right"  name="oil_comsumption">';
			$html2 .= '</div>';
			$html2 .= '</td>';
			
			$html2 .= '</tr>';
			$html2 .= '<tr id=" ">';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="masal_consumption">Masala Consumption %</label>';  
			$html2 .= '<div class="input-group" >';
			$html2 .= '<input type="text" readonly="" value="" class="form-control pull-left text-right" name="masal_consumption">';
			$html2 .= '</div>';
			$html2 .= '</td>';  
			$html2 .= '</tr>';
			$html2 .= '</tbody>';
			$html2 .= '</table>';
			
			$data = array('production_table'=>$html , 'row_material_table'=>$html1,'lower_table'=>$html2);
			// echo $html;
			echo json_encode($data);
		}
		public function export_production_report(){
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$Pro_report = $this->production_model->pro_order_report($this->input->post());
				$Pro_details = $this->production_model->pro_order_report_details($this->input->post());
				$this->load->model('accounts_master_model');
				$selected_company_details = $this->accounts_master_model->get_company_detail();
				$writer = new XLSXWriter();
				$j=0;
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				$j++;
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				$j++;
				
				$msg = "Production Report: ".$this->input->post('pro_order_id');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				$j++;
				
				$msg1 = "Status of PO: ".$Pro_report['production_status'];
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter1);
				$j++;
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Item Name"] =  'ItemName';
				$set_col_tk["No of batches"] =  'No of Batches';
				$set_col_tk["Output as per Receipe"] =  'Output as per Receipe';
				$set_col_tk["Actual Output in Pc"] =  'Actual Output in Pc';
				$set_col_tk["Diffrence % (if Negative its loss)"] =  'Diffrence % (if Negative its loss)';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$total = 0;
				$prec =0;
				$prec_fg_total =0;
				//    foreach ($Pro_report as $k => $value) {
				
				$list_add = [];
				$list_add[] = $Pro_report['description'];
				$list_add[] = $Pro_report['batch_qty'];
				$list_add[] = $Pro_report['Finish_good_qty'];
				$list_add[] = $Pro_report['Finish_good_qty_new'];
				$total = $Pro_report['Finish_good_qty_new']-$Pro_report['Finish_good_qty'];
				if($total > 0){
					$prec =  ($total*100)/$Pro_report['Finish_good_qty'];
					}else{
					$total = $total*-1;
					$prec =  ($total*100)/$Pro_report['Finish_good_qty'];
					$prec = $prec*-1;
				}
				if($Pro_report['Finish_good_qty_new'] == '' || $Pro_report['Finish_good_qty_new'] == 0){
					$prec = '';
					$prec_fg_total = 0;
					}else{
					$prec_fg_total =  ($Pro_report['Finish_good_qty_new']*100)/$Pro_report['Finish_good_qty'];
				}
				
				
				$list_add[] = number_format($prec,2);
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				//  }
				
				
				
				$msg1 = "Raw Material Summary :-";
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter1);
				$j++;
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Sl No"] =  'SrNo';
				$set_col_tk["Item"] =  'ItemName';
				$set_col_tk["RM Qty as per Receipe"] =  'RM Qty. as per Receipe';
				$set_col_tk["Actual Rm Qty"] =  'Actual RM Qty.';
				$set_col_tk["Diff in qty"] =  'Diff. in Qty.';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$actual_q = 0;
				$diffrence_q = 0;
				$i =1;
				$total_actual = 0;
				foreach ($Pro_details as $k => $value) {
					
					$list_add = [];
					$list_add[] = $i;
					$list_add[] = $value["item_name"];
					$list_add[] = number_format($value["production_req_qty"],2);
					$actual_q = ($value['production_req_qty'] + $value['ExtraQty']) - ($value['return_req_qty']);
					$list_add[] = number_format($actual_q,2);
					
					$total_actual+=$actual_q;
					$diffrence_q = $actual_q -$value['production_req_qty'];
					$list_add[] = number_format($diffrence_q,2);
					
					$writer->writeSheetRow('Sheet1', $list_add);
					$i++;
				}
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk[""] =  '';
				$set_col_tk[""] =  '';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$list_add = [];
				$list_add[] = "Total RM in KG";
				$list_add[] = number_format($total_actual,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Total FG";
				$list_add[] = number_format($Pro_report['Finish_good_qty_new'],2).' '.$Pro_report['finish_good_unit'];
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Output %";
				$list_add[] = number_format($prec_fg_total,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Oil Comsumption %";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Masala Consumption %";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'production_order_wise_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
			
		}
		public function product_list(){
			$postData = $this->input->post();
			
			// Get data
			$data = $this->production_model->getproduct_list($postData);
			
			echo json_encode($data);
		}
		public function production_cost_report(){
			if (!has_permission_new('cost_report', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			
			//$data['items_main_groups'] = $this->production_model->get_sub_groups();
			$data['title'] = "Production Cost Report Item Wise ";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			
			$data['Pro_order_list'] = $this->production_model->pro_order_list();
			$this->load->view('admin/production/production_cost_report', $data);
		}
		public function load_table_production_cost_report()
		{
			
			$Pro_report = $this->production_model->pro_cost_report($this->input->post());
			
			$Pro_details = $this->production_model->GetRMItemListByFGItemID($this->input->post());
            // print_r($Pro_details);die;
			$html =''; 
			//  $html .='<span>Status of PO: <b>'.$Pro_report['production_status'].'</b></span>'; 
			
            $html .= '<table class="table-striped table-bordered production_table" id="production_table" width="100%">';
            $html .= '<thead style="font-size:11px;">';
            
			
			$html.= '<tr>';
			$html.= '<th align="center">Item Name</th>';
			$html.= '<th align="center">No of batches </th>';
			$html.= '<th>Output as per Receipe</th>';
			$html.= '<th align="center">Actual Output in Pc</th>';
			$html.= '<th align="center">Diffrence % (if nigative its loss)</th>';
			$html.= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $total = 0;
            $prec =0;
            $prec_fg_total = 0;
            $ffQty = 0;
            $SaleRate_FG = 0;
            $basicRate_FG = 0;
            $GSTPer_FG = 0;
            $conv_costPer = 0;
            $st_costPer = 0;
            $frt_costPer = 0;
            $mrkt_costPer = 0;
            $dmg_costPer = 0;
			foreach($Pro_report as $value){
				
				
				$html.= '<tr>';
				
				$html.= '<td align="left">'.$value['description'].'</td>';
				$html.= '<td align="right">'.$value['batch_qty'].'</td>';
				$html.= '<td align="right">'.number_format((float)$value['Finish_good_qty'], 2, '.', '').'</td>';
				$html.= '<td align="right">'.round($value['Finish_good_qty_new'], 2).'</td>';
				$ffQty +=$value['Finish_good_qty_new'];
				$SaleRate_FG +=$value['SaleRate'];
				$basicRate_FG+=$value['BasicRate'];
				$GSTPer_FG+=$value['gst'];
				
				$conv_costPer= $value['conv_cost'];
				$st_costPer= $value['st_cost'];
				$frt_costPer= $value['frt_cost'];
				$mrkt_costPer= $value['mrkt_cost'];
				$dmg_costPer= $value['dmg_cost'];
				$total = $value['Finish_good_qty_new']-$value['Finish_good_qty'];
				if($total > 0){
					$prec =  ($total*100)/$value['Finish_good_qty'];
					}else{
					$total = $total*-1;
					$prec =  ($total*100)/$value['Finish_good_qty'];
					$prec = $prec*-1;
				}
				if($value['Finish_good_qty_new'] == '' || $value['Finish_good_qty_new'] == 0){
					$prec = -100;
					//  $prec = '';
					$prec_fg_total = 0;
					}else{
					$prec_fg_total =  ($value['Finish_good_qty_new']*100)/$value['Finish_good_qty'];
				}
				$html.= '<td align="right">'.round($prec, 2).'</td>';
				
				$html.= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '<table>';
			
			
			$html1 =''; 
            $html1 .= '<table class="table-striped table-bordered row_material_table" id="row_material_table" width="100%">';
            $html1 .= '<thead style="font-size:11px;">';
			
			
			$html1.= '<tr>';
			$html1.= '<th align="center">Sl No</th>';
			$html1.= '<th align="center">Item </th>';
			$html1.= '<th align="center">RM Qty as per Receipe</th>';
			$html1.= '<th align="center">Actual RM Qty</th>';
			$html1.= '<th align="center">Diff in qty</th>';
			$html1.= '<th align="center">Rate</th>';
			$html1.= '<th align="center">Value</th>';
			$html1.= '<th align="center">GST</th>';
			$html1.= '<th align="center">Net Value</th>';
			$html1.= '</tr>';
            $html1 .= '</thead>';
            $html1 .= '<tbody>';
            $actual_q = 0;
            $diffrence_q = 0;
            $total_actual = 0;
            $value_data =0;
            $value_total =0;
            $gst_total =0;
            $netvalue_total =0;
            $prec_amount =0;
			$i = 1; foreach($Pro_details as $value){
				$html1.= '<tr>';
				
				$html1.= '<td align="center">'.$i.'</td>';
				$html1.= '<td align="left">'.$value['item_name'].'</td>';
				$html1.= '<td align="right">'.number_format((float)$value['production_req_qty'], 2, '.', '').'</td>';
				$actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
				$html1.= '<td align="right">'.number_format((float)$actual_q, 2, '.', '').'</td>';
				$total_actual+=$actual_q;
				$diffrence_q = $actual_q - $value['production_req_qty'];
				$html1.= '<td align="right">'.number_format((float)$diffrence_q, 2, '.', '').'</td>';
				$html1.= '<td align="right">'.number_format((float)$value['BasicRate'], 2, '.', '').'</td>';
				if($value['BasicRate'] != ''){
					$html1.= '<td align="right">'.round($actual_q*$value['BasicRate'],2).'</td>';
					$value_data  = $actual_q*$value['BasicRate'];
					$value_total+=$value_data;
					$prec_amount =  ($value_data*$value['taxrate'])/100;
					$gst_total+=$prec_amount;
					$html1.= '<td align="right">'.number_format((float)$prec_amount, 2, '.', '').'</td>';
					$netvalue_total+=$prec_amount+$value_data;
					$html1.= '<td align="right">'.number_format((float)$prec_amount+$value_data, 2, '.', '').'</td>';
					}else{
					$html1.= '<td align="right"></td>';
					$html1.= '<td align="right"></td>';
					$html1.= '<td align="right"></td>';
				}
				$html1.= '</tr>';
				$i++; 
			}
			$html1 .= '</tbody>';
			$html1 .= '<tfoot>';
			
			$html1 .= '</tfoot>';
			$html1.= '<tr>';
			$html1.= '<td>Total</td>';
			$html1.= '<td></td>';
			$html1.= '<td></td>';
			$html1.= '<td></td>';
			$html1.= '<td></td>';
			$html1.= '<td></td>';
			$html1.= '<td align="right">'.round($value_total,2).'</td>';
			$html1.= '<td align="right">'.round($gst_total,2).'</td>';
			$html1.= '<td align="right">'.round($netvalue_total,2).'</td>';
			$html1.= '</tr>';
			$html1 .= '<table>';
			
			$FG_Cost = $ffQty*$SaleRate_FG;
			$SaleAmt_GF = $ffQty*$basicRate_FG;
			$GSTAmt_FG = ($GSTPer_FG/100)*$SaleAmt_GF;
			$conversion_Cost = ($conv_costPer/100)*$FG_Cost;
			$sale_team_Cost = ($st_costPer/100)*$FG_Cost;
			$Freight_Cost = ($frt_costPer/100)*$FG_Cost;
			$Marketing_Cost = ($mrkt_costPer/100)*$FG_Cost;
			$Damage_Cost  = ($dmg_costPer/100)*$FG_Cost;
			
			$html2 =''; 
			$html2 .= '<table class="table text-right" style="width: 38%;" id="lower_table">';
			$html2 .= '<tbody>';
			$html2 .= '<tr id="">';
			$html2 .= '<td width="50%" id="" style="border:none!important;">';
			$html2 .= '<label style="float: left; padding: 9px;width: 139px;" for="total_rm">Conversion Cost</label>';  
			$html2 .= '<div class="input-group" id="">';
			$html2 .= '<input type="text" readonly="" class="form-control text-right" name="total_rm_kg" value="'.$conversion_Cost.'">';
			$html2 .= '</div>';
			$html2 .= '</td>';
			$html2 .= '</tr>';
			
			$html2 .= '<tr>';
			$html2 .= '<td  style="border:none!important;"> '; 
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="total_fg">Sales team Cost</label>';  
			$html2 .= '<div class="input-group" id="">';
			$html2 .= '<input type="text" readonly="" value="'.$sale_team_Cost.'" class="form-control pull-left text-right" name="total_fg">';
			$html2 .= '</div>';
			$html2 .= '</td>';
			
			$html2 .= '</tr>';
			$html2 .= '<tr id=" ">';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="output">Freight</label>';  
			$html2 .= '<div class="input-group" id="">';
			$html2 .= '<input type="text" readonly="" value="'.round($Freight_Cost,2).'" class="form-control pull-left text-right"  name="output">';
			$html2 .= ' </div>';
			$html2 .= '</td>';
			
			$html2 .= '</tr>';
			$html2 .= '<tr>';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="oil_comsumption">Marketing Cost</label>';  
			$html2 .= '<div class="input-group">';
			$html2 .= '<input type="text" readonly="" value="'.round($Marketing_Cost,2).'" class="form-control pull-left text-right"  name="oil_comsumption">';
			$html2 .= '</div>';
			$html2 .= '</td>';
			
			$html2 .= '</tr>';
			$html2 .= '<tr id=" ">';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="masal_consumption">Damage </label>';  
			$html2 .= '<div class="input-group" >';
			$html2 .= '<input type="text" readonly="" value="'.round($Damage_Cost,2).'" class="form-control pull-left text-right" name="masal_consumption">';
			$html2 .= '</div>';
			$html2 .= '</td>';  
			$html2 .= '</tr>';
			
			$html2 .= '<tr id=" ">';
			$html2 .= '<td  style="border:none!important;">';  
			$html2 .= '<label style="float: left; padding: 9px 9px 9px 0px;width: 139px;" for="masal_consumption">GST Payable </label>';  
			$html2 .= '<div class="input-group" >';
			$html2 .= '<input type="text" readonly="" value="'.round(($GSTAmt_FG-$gst_total),2).'" class="form-control pull-left text-right" name="masal_consumption">';
			$html2 .= '</div>';
			$html2 .= '</td>';  
			$html2 .= '</tr>';
			$html2 .= '</tbody>';
			$html2 .= '</table>';
			
			$data = array('production_table'=>$html , 'row_material_table'=>$html1,'lower_table'=>$html2);
			// $data = array('production_table'=>$html , 'row_material_table'=>$html1);
			// echo $html;
			echo json_encode($data);
		}
		public function export_production_cost_report(){
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$Pro_report = $this->production_model->pro_cost_report($this->input->post());
				
				$Pro_details = $this->production_model->GetRMItemListByFGItemID($this->input->post());
				$this->load->model('accounts_master_model');
				$selected_company_details = $this->accounts_master_model->get_company_detail();
				$writer = new XLSXWriter();
				$j=0;
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				$j++;
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				$j++;
				$date = 'Production Cost Report Date form: '.$this->input->post('from_date').' date to: '.$this->input->post('to_date');
				$date_from_to = array($date,);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $date_from_to);
				$j++;
				if($this->input->post('product_name') != ''){
					$product = 'Product: '.$this->input->post('product_name');
					$product_name = array($product,);
					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
					$writer->writeSheetRow('Sheet1', $product_name);
					$j++;
				}
				
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Item Name"] =  'Item Name';
				$set_col_tk["No of batches"] =  'No of batches';
				$set_col_tk["Output as per Receipe"] =  'Output as per Receipe';
				$set_col_tk["Actual Output in Pc"] =  'Actual Output in Pc';
				$set_col_tk["Diffrence % (if nigative its loss)"] =  'Diffrence % (if nigative its loss)';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$total = 0;
				$prec =0;
				$prec_fg_total =0;
				$ffQty = 0;
				$SaleRate_FG = 0;
				$basicRate_FG = 0;
				$GSTPer_FG = 0;
				$conv_costPer = 0;
				$st_costPer = 0;
				$frt_costPer = 0;
				$mrkt_costPer = 0;
				$dmg_costPer = 0;
				foreach ($Pro_report as $k => $value) {
					
					$list_add = [];
					$list_add[] = $value['description'];
					$list_add[] = $value['batch_qty'];
					$list_add[] = round($value['Finish_good_qty'],2);
					$list_add[] = round($value['Finish_good_qty_new'],2);
					
					$ffQty+=$value['Finish_good_qty_new'];
					$SaleRate_FG+=$value['SaleRate'];
					$basicRate_FG+=$value['BasicRate'];
					$GSTPer_FG+=$value['gst'];
					
					$conv_costPer= $value['conv_cost'];
					$st_costPer= $value['st_cost'];
					$frt_costPer= $value['frt_cost'];
					$mrkt_costPer= $value['mrkt_cost'];
					$dmg_costPer= $value['dmg_cost'];
					
					$total = $value['Finish_good_qty_new']-$value['Finish_good_qty'];
					if($total > 0){
						$prec =  ($total*100)/$value['Finish_good_qty'];
						}else{
						$total = $total*-1;
						$prec =  ($total*100)/$value['Finish_good_qty'];
						$prec = $prec*-1;
					}
					if($value['Finish_good_qty_new'] == '' || $value['Finish_good_qty_new'] == 0){
						$prec = -100;
						$prec_fg_total = 0;
						}else{
						$prec_fg_total =  ($value['Finish_good_qty_new']*100)/$value['Finish_good_qty'];
					}
					
					
					$list_add[] = round($prec,2);
					
					$writer->writeSheetRow('Sheet1', $list_add);
					
				}
				
				
				
				$msg1 = "Raw Material Summery :-";
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 12);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter1);
				$j++;
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Sl No"] =  'Sl No';
				$set_col_tk["Item"] =  'Item';
				$set_col_tk["RM Qty as per Receipe"] =  'RM Qty as per Receipe';
				$set_col_tk["Actual Rm Qty"] =  'Actual RM Qty';
				$set_col_tk["Diff in qty"] =  'Diff in qty';
				$set_col_tk["Rate"] =  'Rate';
				$set_col_tk["Value"] =  'Value';
				$set_col_tk["GST"] =  'GST';
				$set_col_tk["Net Value"] =  'Net Value';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$actual_q = 0;
				$diffrence_q = 0;
				$i =1;
				$total_actual = 0;
				$value_data =0;
				$value_total =0;
				$gst_total =0;
				$netvalue_total =0;
				$prec_amount =0;
				foreach ($Pro_details as $k => $value) {
					
					$list_add = [];
					$list_add[] = $i;
					
					$list_add[] = $value["item_name"];
					$list_add[] = round($value["production_req_qty"],2);
					$actual_q = $value['production_req_qty']- $value['return_req_qty'] + $value['ExtraQty'];
					$list_add[] = round($actual_q,2);
					
					$total_actual+=$actual_q;
					$diffrence_q = $actual_q - $value['production_req_qty'];
					$list_add[] = round($diffrence_q,2);
					$list_add[] = round($value['BasicRate'],2);
					
					if($value['BasicRate'] != ''){
						$list_add[] = round($actual_q*$value['BasicRate'],2);
						$value_data  = $actual_q*$value['BasicRate'];
						$value_total+=$value_data;
						$prec_amount =  ($value_data*$value['taxrate'])/100;
						$gst_total+=$prec_amount;
						$list_add[] = round($prec_amount,2);
						$netvalue_total+=$prec_amount+$value_data;
						$list_add[] = round(($prec_amount+$value_data),2);
						}else{
						$list_add[] = '';
						$value_data  = $actual_q;
						$list_add[] = '';
						$list_add[] = '';
					}
					$writer->writeSheetRow('Sheet1', $list_add);
					$i++;
				}
				
				$list_add = [];
				$list_add[] = "Total";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = round($value_total,2);
				$list_add[] = round($gst_total,2);
				$list_add[] = round($netvalue_total,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk[""] =  '';
				$set_col_tk[""] =  '';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$FG_Cost = $ffQty*$SaleRate_FG;
				$SaleAmt_GF = $ffQty*$basicRate_FG;
				$GSTAmt_FG = ($GSTPer_FG/100)*$SaleAmt_GF;
				$convertion_Cost = ($conv_costPer/100)*$FG_Cost;
				$sale_team_Cost = ($st_costPer/100)*$FG_Cost;
				$Freight_Cost = ($frt_costPer/100)*$FG_Cost;
				$Marketing_Cost = ($mrkt_costPer/100)*$FG_Cost;
				$Damage_Cost  = ($dmg_costPer/100)*$FG_Cost;
				
				$list_add = [];
				$list_add[] = "Conversion Cost";
				$list_add[] = round($convertion_Cost,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				
				$list_add = [];
				$list_add[] = "Sales team Cost";
				$list_add[] = round($sale_team_Cost,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Freight";
				$list_add[] = round($Freight_Cost,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Marketing Cost";
				$list_add[] = round($Marketing_Cost,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$list_add = [];
				$list_add[] = "Damage ";
				$list_add[] = round($Damage_Cost,2);
				$writer->writeSheetRow('Sheet1', $list_add);
				$list_add = [];
				$list_add[] = "GST Payable ";
				$list_add[] = round(($GSTAmt_FG-$gst_total),2);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'production_cost_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
			
		}
		
		public function savebakingdata(){
			// Get the raw POST data (JSON payload)
			$requestBody = file_get_contents('php://input');
			
			// Decode the JSON data into a PHP associative array
			$data = json_decode($requestBody, true);
			
			// Accessing the data from the JSON
			$proId = $data['proId']; // Getting the proId
			$bakingData = $data['tabledata']; // Getting the table data array
			// echo "<pre>";print_r($bakingData);die;
			// Now you can loop through the $bakingData and process it as needed
			$affected = 0;
			if(count($bakingData) >0)
			{	
				foreach ($bakingData as $row) {
					$itemId = $row['item_id'];
					$itemName = $row['item_name'];
					$reqQty = $row['req_qty'];
					$unit = $row['unit'];
					if(empty($row['edit_id']))
					{
						
						$baking_data = array(
						"ProductionID"=>$proId,
						"ItemID"=>$itemId,
						"Qty"=>$reqQty,
						"Stage"=>'Baking',
						"TransDate"=>date('Y-m-d H:i:s'),
						"UserID"=>$this->session->userdata('username')
						); 
						
						if($this->db->insert(db_prefix() . 'production_stage', $baking_data)){
							$affected++;
						}
					}
					else
					{
						$baking_data_old = array(
						"Qty"=>$reqQty,
						"Lupdate"=>date('Y-m-d H:i:s'),
						"UserID2"=>$this->session->userdata('username')
						); 
						
						$this->db->where('ProductionID', $proId);
						$this->db->where('ItemID', $itemId);
						$this->db->where('id', $row['edit_id']);
						if($this->db->update(db_prefix() . 'production_stage', $baking_data_old)){
							$affected++;
						}
					}
				}
			}
			if($affected >0)
			{
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		public function savebakingdata_new(){
			// Get the raw POST data (JSON payload)
			$requestBody = file_get_contents('php://input');
			
			// Decode the JSON data into a PHP associative array
			$data = json_decode($requestBody, true);
			
			// Accessing the data from the JSON
			$bakingData = $data['tabledata']; // Getting the table data array
			// echo "<pre>";print_r($bakingData);die;
			// Now you can loop through the $bakingData and process it as needed
			$affected = 0;
			if(count($bakingData) >0)
			{	
				foreach ($bakingData as $row) {
					$itemId = $row['item_id'];
					$reqQty = $row['req_qty'];
					$proId = $row['proId'];
					
					$this->db->where('ItemID', $itemId);
					$this->db->where('ProductionID', $proId);
					$this->db->where('Stage', 'Baking');
					$this->db->delete(db_prefix() . 'production_stage');
					
					$baking_data = array(
					"ProductionID"=>$proId,
					"ItemID"=>$itemId,
					"Qty"=>$reqQty,
					"Stage"=>'Baking',
					"TransDate"=>date('Y-m-d H:i:s'),
					"UserID"=>$this->session->userdata('username')
					); 
					
					if($this->db->insert(db_prefix() . 'production_stage', $baking_data)){
						$affected++;
					}
					
				}
			}
			if($affected >0)
			{
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		public function savepackingdata_new()
		{
		    if($selected_company == "1"){
				$GodownIDF = 'CSPL';
				}else if($selected_company == "2"){
				$GodownIDF = 'CFF';
				}else if($selected_company == "3"){
				$GodownIDF = 'CBUPL';
			}
			// Get the raw POST data (JSON payload)
			$requestBody = file_get_contents('php://input');
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$LogIn = $this->session->userdata('username');
			// Decode the JSON data into a PHP associative array
			$data = json_decode($requestBody, true);
			
			// Accessing the data from the JSON
			$PackingData = $data['tabledata']; // Getting the table data array
			// echo "<pre>";print_r($PackingData);die;
			// Now you can loop through the $PackingData and process it as needed
			$affected = 0;
			if(count($PackingData) >0)
			{	
				foreach ($PackingData as $row) {
					$itemId = $row['item_id'];
					$reqQty = $row['req_qty'];
					$proId = $row['proId'];
					$StdPackingQty = $row['StdPackingQty'];
					
					
    				$PackingDetails = $this->production_model->GetPrdPackingRecordSum($proId,$itemId);
					
					$prdmaster = $this->production_model->GetPrdDetails($proId);
					$RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
					$RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
					
					$PackingQty = $reqQty - $PackingDetails->TotalPacking;
					if($PackingQty > 0  || $PackingQty < 0){
						// echo "</pre>";print_r($Prd_Detail);die;
						$Packing_data = array(
						"ProductionID"=>$proId,
						"ItemID"=>$itemId,
						"Qty"=>$PackingQty,
						"Stage"=>'Packing',
						"TransDate"=>date('Y-m-d H:i:s'),
						"UserID"=>$this->session->userdata('username')
						); 
						
						if($this->db->insert(db_prefix() . 'production_stage', $Packing_data)){
							$FGDetails = $this->production_model->GetPrdFGDetails($proId);   
							if($FGDetails->manager_name == null){
								$accountId = $FGDetails->contractor_name;
								}else{
								$accountId = $FGDetails->manager_name;
							}
							// $FGHistoryDetails = $this->production_model->GetPrdFGrecord($proId,$itemId);
							$FGHistoryDetails = $this->production_model->GetPrdFGrecordSum($proId,$itemId);
							
							$BilledQty = $reqQty - $FGHistoryDetails->TotalProduction;
							if($BilledQty > 0  || $BilledQty < 0){
								$history_details = array(
								'PlantID' =>$selected_company,
								'FY' =>$fy,
								'cnfid' =>1,
								'OrderID' =>$proId,
								'TransDate' =>date('Y-m-d H:i:s'),
								'BillID' =>$proId,
								'TransID' =>$proId,
								'TransDate2' =>date('Y-m-d H:i:s'),
								'TType' =>"B",
								'TType2' =>"Production",
								'AccountID' =>$accountId,
								'GodownID' =>$GodownIDF,
								'ItemID' =>$itemId,
								'SuppliedIn'=>$FGDetails->finish_good_unit,
								'BasicRate' =>$FGDetails->ItemRate->BasicRate,
								'SaleRate' =>$FGDetails->ItemRate->SaleRate,
								'CaseQty' =>$FGDetails->case_qty,
								'OrderQty' =>$BilledQty,
								'BilledQty' =>$BilledQty,
								'Ordinalno' =>1,
								'UserID' =>$this->session->userdata('username')
								);
								$this->db->insert(db_prefix() . 'history', $history_details);
								$affected++;
								
								$i = 1;
								foreach ($RMItemDetails as $key => $value) {
									if($value['MainGrpID'] == '3'){
										
										$singleissueqty = $StdPackingQty / $value['production_req_qty'];
										$issueQty = $BilledQty / $singleissueqty;
										foreach ($RMItemOtherDetails as $key1 => $value1) {
											if($value["item_id"] == $value1["item_id"]){
												$CaseQty = $value1["CaseQty"];
												$BasicRate = $value1["BasicRate"];
												$SaleRatee = $value1["SaleRate"];
											}
											
										} 
										if($prdmaster->manager_name == null){
											$accountId = $prdmaster->contractor_name;
											}else{
											$accountId = $prdmaster->manager_name;
										}
										$history_details = array(
										'PlantID' =>$selected_company,
										'FY' =>$fy,
										'cnfid' =>1,
										'OrderID' =>$proId,
										'TransDate' =>date('Y-m-d H:i:s'),
										'BillID' =>$proId,
										'TransID' =>$proId,
										'TransDate2' =>date('Y-m-d H:i:s'),
										'TType' =>"A",
										'TType2' =>"Issue",
										'AccountID' =>$accountId,
										'GodownID' =>$prdmaster->GodownID,
										'SuppliedIn'=>$value["unit"],
										'CaseQty' =>$CaseQty,
										'BasicRate' =>$BasicRate,
										'SaleRate' =>$SaleRatee,
										'ItemID' =>$value["item_id"],
										'OrderQty' =>$issueQty,
										'BilledQty' =>$issueQty,
										'Ordinalno' =>$i,
										'UserID' =>$value["UserID"]
										);
										$this->db->insert(db_prefix() . 'history', $history_details);
										$i++;
									}
								}
							}
						}
					}
				}
			}
			if($affected >0)
			{
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		public function UpdateCompleteProduction(){
			
			$selected_company = $this->session->userdata('root_company');
			$fy = $this->session->userdata('finacial_year');
			$LogIn = $this->session->userdata('username');
			if($selected_company == "1"){
				$GodownIDF = 'CSPL';
				}else if($selected_company == "2"){
				$GodownIDF = 'CFF';
				}else if($selected_company == "3"){
				$GodownIDF = 'CBUPL';
			}
			// Get the raw POST data (JSON payload)
			$requestBody = file_get_contents('php://input');
			
			// Decode the JSON data into a PHP associative array
			$data = json_decode($requestBody, true);
			
			// Accessing the data from the JSON
			$PackingData = $data['tabledata']; // Getting the table data array
			// echo "<pre>";print_r($PackingData);die;
			// Now you can loop through the $PackingData and process it as needed
			$affected = 0;
			if(count($PackingData) >0)
			{	
				foreach ($PackingData as $row) {
					$itemId = $row['item_id'];
					$reqQty = $row['req_qty'];
					$proId = $row['proId'];
					$StdPackingQty = $row['StdPackingQty'];
					
					
    				$PackingDetails = $this->production_model->GetPrdPackingRecordSum($proId,$itemId);
					
					$prdmaster = $this->production_model->GetPrdDetails($proId);
					$RMItemDetails = $this->production_model->GetRowMaterialDetails($proId);
					$RMItemOtherDetails = $this->production_model->GetRowMaterialOthDetails($proId);
					
					$UpdateArr = array(
					"Finish_good_qty_new"=>$reqQty,
					"p_end_time"=>date('Y-m-d H:i:s'),
					"production_status"=>'Completed',
					"Lupdate"=>date('Y-m-d H:i:s'),
					"UserID2"=>$this->session->userdata('username')
					); 
					
					$this->db->where('pro_order_id', $proId);
					$this->db->where('recipeID', $itemId);
					$this->db->update(db_prefix() . 'production', $UpdateArr);
					
					$PackingQty = $reqQty - $PackingDetails->TotalPacking;
					if($PackingQty > 0 || $PackingQty < 0){
						// echo "</pre>";print_r($Prd_Detail);die;
						$Packing_data = array(
						"ProductionID"=>$proId,
						"ItemID"=>$itemId,
						"Qty"=>$PackingQty,
						"Stage"=>'Packing',
						"TransDate"=>date('Y-m-d H:i:s'),
						"UserID"=>$this->session->userdata('username')
						); 
						
						if($this->db->insert(db_prefix() . 'production_stage', $Packing_data)){
							
							
							$FGDetails = $this->production_model->GetPrdFGDetails($proId);   
							if($FGDetails->manager_name == null){
								$accountId = $FGDetails->contractor_name;
								}else{
								$accountId = $FGDetails->manager_name;
							}
							// $FGHistoryDetails = $this->production_model->GetPrdFGrecord($proId,$itemId);
							$FGHistoryDetails = $this->production_model->GetPrdFGrecordSum($proId,$itemId);
							
							$BilledQty = $reqQty - $FGHistoryDetails->TotalProduction;
							if($BilledQty > 0 || $BilledQty < 0){
								$history_details = array(
								'PlantID' =>$selected_company,
								'FY' =>$fy,
								'cnfid' =>1,
								'OrderID' =>$proId,
								'TransDate' =>date('Y-m-d H:i:s'),
								'BillID' =>$proId,
								'TransID' =>$proId,
								'TransDate2' =>date('Y-m-d H:i:s'),
								'TType' =>"B",
								'TType2' =>"Production",
								'AccountID' =>$accountId,
								'GodownID' =>$GodownIDF,
								'ItemID' =>$itemId,
								'SuppliedIn'=>$FGDetails->finish_good_unit,
								'BasicRate' =>$FGDetails->ItemRate->BasicRate,
								'SaleRate' =>$FGDetails->ItemRate->SaleRate,
								'CaseQty' =>$FGDetails->case_qty,
								'OrderQty' =>$BilledQty,
								'BilledQty' =>$BilledQty,
								'Ordinalno' =>1,
								'UserID' =>$this->session->userdata('username')
								);
								$this->db->insert(db_prefix() . 'history', $history_details);
								$affected++;
								
								$i = 1;
								foreach ($RMItemDetails as $key => $value) {
									if($value['MainGrpID'] == '3'){
										
										$singleissueqty = $StdPackingQty / $value['production_req_qty'];
										$issueQty = $BilledQty / $singleissueqty;
										foreach ($RMItemOtherDetails as $key1 => $value1) {
											if($value["item_id"] == $value1["item_id"]){
												$CaseQty = $value1["CaseQty"];
												$BasicRate = $value1["BasicRate"];
												$SaleRatee = $value1["SaleRate"];
											}
											
										} 
										if($prdmaster->manager_name == null){
											$accountId = $prdmaster->contractor_name;
											}else{
											$accountId = $prdmaster->manager_name;
										}
										$history_details = array(
										'PlantID' =>$selected_company,
										'FY' =>$fy,
										'cnfid' =>1,
										'OrderID' =>$proId,
										'TransDate' =>date('Y-m-d H:i:s'),
										'BillID' =>$proId,
										'TransID' =>$proId,
										'TransDate2' =>date('Y-m-d H:i:s'),
										'TType' =>"A",
										'TType2' =>"Issue",
										'AccountID' =>$accountId,
										'GodownID' =>$prdmaster->GodownID,
										'SuppliedIn'=>$value["unit"],
										'CaseQty' =>$CaseQty,
										'BasicRate' =>$BasicRate,
										'SaleRate' =>$SaleRatee,
										'ItemID' =>$value["item_id"],
										'OrderQty' =>$issueQty,
										'BilledQty' =>$issueQty,
										'Ordinalno' =>$i,
										'UserID' =>$value["UserID"]
										);
										$this->db->insert(db_prefix() . 'history', $history_details);
										$i++;
									}
								}
							}
						}
					}
				}
			}
			if($affected >0)
			{
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		public function savePackingdata(){
			// Get the raw POST data (JSON payload)
			$requestBody = file_get_contents('php://input');
			
			// Decode the JSON data into a PHP associative array
			$data = json_decode($requestBody, true);
			
			// Accessing the data from the JSON
			$proId = $data['proId']; // Getting the proId
			$PackingData = $data['tabledata']; // Getting the table data array
			// echo "<pre>";print_r($PackingData);die;
			// Now you can loop through the $PackingData and process it as needed
			$affected = 0;
			if(count($PackingData) >0)
			{	
				foreach ($PackingData as $row) {
					$itemId = $row['item_id'];
					$itemName = $row['item_name'];
					$reqQty = $row['req_qty'];
					$unit = $row['unit'];
					if(empty($row['edit_id']))
					{
						
						$baking_data = array(
						"ProductionID"=>$proId,
						"ItemID"=>$itemId,
						"Qty"=>$reqQty,
						"Stage"=>'Packing',
						"TransDate"=>date('Y-m-d H:i:s'),
						"UserID"=>$this->session->userdata('username')
						); 
						
						if($this->db->insert(db_prefix() . 'production_stage', $baking_data)){
							$affected++;
						}
					}
					else
					{
						$baking_data_old = array(
						"Qty"=>$reqQty,
						"Lupdate"=>date('Y-m-d H:i:s'),
						"UserID2"=>$this->session->userdata('username')
						); 
						
						$this->db->where('ProductionID', $proId);
						$this->db->where('ItemID', $itemId);
						$this->db->where('id', $row['edit_id']);
						if($this->db->update(db_prefix() . 'production_stage', $baking_data_old)){
							$affected++;
						}
					}
				}
			}
			if($affected >0)
			{
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		
		public function AddEditBakingQty()
		{
			if (!has_permission_new('AddEditBakingQty', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			$data['title'] = "Add Edit Baking Quantity";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$this->load->view('admin/production/AddEditBakingQty', $data);
		}
		public function AddEditPackingQty()
		{
			if (!has_permission_new('AddEditPackingQty', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			$data['title'] = "Add Edit Packing Quantity";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$this->load->view('admin/production/AddEditPackingQty', $data);
		}
		
		
		public function ItemUsedInRecipeReport(){
			if (!has_permission_new('ItemUsedInRecipeReport', '', 'view')) {
				ajax_access_denied();
			}
			$data['title'] = "Item Used In Recipe Report";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$data['ItemList'] = $this->production_model->GetAllItemList();
			$this->load->view('admin/production/ItemUsedInRecipeReport', $data);
		}
		
		public function GetItemUsedRecipeList()
		{
			$ItemID = $this->input->post('ItemID');
			$data = $this->production_model->GetItemUsedRecipeList($ItemID);
			// echo "<pre>";print_r($data);die;
			if(count($data) >0){
				$minutes = 0;
				$i = 1; 
				foreach($data as $value){
					$html1.= '<tr>'; 
					$html1.= '<td>'.$i.'</td>';
					$html1.= '<td>'.$value['RecipeName'].'</td>';
					$html1.= '<td>'.$value['batch_size'].'</td>';
					$html1.= '<td>'.$value['item_name'].'</td>';
					$html1.= '<td>'.$value['req_qty'].'</td>';
					$html1.= '<td>'.$value['unit'].'</td>';
					$html1.= '</tr>';
					$i++;
				}
				}else{
				$html1.= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html1);
		}
		
		public function export_itemusedrecipeReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$ItemID = $this->input->post('ItemID');
				$data = $this->production_model->GetItemUsedRecipeList($ItemID); 
				$this->load->model('sale_reports_model');
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				
				$writer = new XLSXWriter();
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Item Used In Recipe Report";
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Recipe Name"] =  'Recipe Name';
				$set_col_tk["Batch Size (Kg) In Flour Base"] =  'Batch Size (Kg) In Flour Base';
				$set_col_tk["Item Name"] =  'Item Name';
				$set_col_tk["Qty"] =  'Qty';
				$set_col_tk["Unit"] =  'Unit';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				foreach ($data as $k => $value) 
				{
					$list_add = [];
					$list_add[] = $value["RecipeName"];
					
					$list_add[] = $value["batch_size"];
					$list_add[] = $value["item_name"];
					$list_add[] = $value["req_qty"];
					$list_add[] = $value["unit"];
					
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'Item Used Recipe list.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function NeededQtyTransfer(){
			if (!has_permission_new('NeededQtyTransfer', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			$data['title'] = "Needed Qty Transfer";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$data['PendingProduction'] = $this->production_model->PendingProductionList($data);
			
			// print_r($data['PendingProduction']);die;
			$this->load->view('admin/production/NeededQtyTransfer', $data);
		}
		
		public function GetNeededQtyItemByOrderIds()
		{
			$data = array(
			'selected_ids'  => $this->input->post('selected_ids')
			);
			$Result = $this->production_model->GetNeededQtyItemByOrderIds($data);
			
			$html = '';
			
			foreach ($Result as $key => $value) {
				
				// Format Qty based on unit
				if (strtoupper($value['unit']) == "KG" || strtoupper($value['unit']) == "LTR") {
					$PRDStd = number_format($value['req_qty'], 3);
					$PRDReq = number_format($value['production_req_qty'], 3);
					$PRDRTN = number_format($value['return_req_qty'], 3);
					$PRDEXT = number_format($value['ExtraQty'], 3);
					$PRDAct = number_format($value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'], 3);
					} elseif (strtoupper($value['unit']) == "PCS") {
					$PRDStd = (int) $value['req_qty'];
					$PRDReq = (int) $value['production_req_qty'];
					$PRDRTN = (int) $value['return_req_qty'];
					$PRDEXT = (int) $value['ExtraQty'];
					$PRDAct = (int) $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];
				}
				$stockQty = $value['StockBal'];
				$StockBalRMPM = $value['StockBalRMPM'];
				
				// Check stock status
				$style = '';
				if ($PRDAct > $stockQty) {
					$style = 'style="color:red;border-color:red"';
				}
				
				$NeededQty = number_format($PRDAct - $stockQty, 2, '.', '');
				
				// echo $stockQty;die;
				if ($NeededQty > 0) {
					$style2 = '';
					if ($NeededQty > $StockBalRMPM) {
						$style2 = 'style="color:red;border-color:red"';
					}
					// Build HTML row
					$html .= '<tr>';
					$html .= '<td align="center"><input type="hidden" class="ItemID" value="'.$value['ItemID'].'">'.$value['ItemID'].'</td>';
					$html .= '<td>'.$value['description'].'</td>';
					$html .= '<td align="right">'.$PRDStd.'</td>';
					$html .= '<td align="right">'.$PRDReq.'</td>';
					$html .= '<td align="right">'.$PRDRTN.'</td>';
					$html .= '<td align="right">'.$PRDEXT.'</td>';
					$html .= '<td align="right">'.$PRDAct.'</td>';
					$html .= '<td align="right" '.$style.'>'.number_format($stockQty,2).'</td>';
					$html .= '<td align="right" '.$style2.'>'.number_format($StockBalRMPM, 2, '.', '').'</td>';
					$html .= '<td align="right">'.$NeededQty.'</td>';
					$html .= '<td align="right"><input type="text" class="form-control TransQty" value="'.$NeededQty.'"><input type="hidden" class="CaseQty" value="'.$value['CaseQty'].'"></td>';
					$html .= '<td align="center">'.$value['unit'].'</td>';
					$html .= '</tr>';
				}
				
			}
			// echo "<pre>";print_r($data);die;
			echo json_encode($html);
		}
		
		public function SaveNeededStockTransfer()
		{
			$this->load->model('godown_model');
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			
			if($selected_company == 1){
				$new_TransNumber = get_option('next_trns_number_for_cspl');
				}elseif($selected_company == 2){
				$new_TransNumber = get_option('next_trns_number_for_cff');
				}elseif($selected_company == 3){
				$new_TransNumber = get_option('next_trns_number_for_cbu');
				}elseif($selected_company == 4){
				$new_TransNumber = get_option('next_trns_number_for_cbupl');
			}
            
			$TransNumber = 'TRS'.$FY.str_pad($new_TransNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
			$Transdate = date('Y-m-d H:i:s');
			$TrnsFrom = 'RM';
			$TrnsTo = 'PU';
			$masterData = array(
            'PlantID'=> $selected_company,
            'FY'=> $FY,
            'TransID'=> $TransNumber,
            'Transdate'=> date('Y-m-d H:i:s'),
            'Transdate2'=> $Transdate,
            'TransFrom'=> $TrnsFrom,
            'TransTo'=> $TrnsTo,
            'UserID'=> $_SESSION['username'],
			);
			$this->db->insert(db_prefix() . 'TransferMaster',$masterData);
			if($this->db->affected_rows() > 0){
				
				$this->godown_model->increment_next_number();
				
				$ItemSerializedArr = $this->input->post('ItemSerializedArr');
				$ItemArray = json_decode($ItemSerializedArr, true);
				$ItemCountN = count($ItemArray);
				$selectedArray = array();
				for($i=0; $i<$ItemCountN; $i++) {
					$ItemID = $ItemArray[$i][0];
					array_push($selectedArray,$ItemID);
				}
				$OrdNo = 1;
				for($k=0; $k<$ItemCountN; $k++) {
					$ItemID = $ItemArray[$k][0];
					$ItemName = $ItemArray[$k][1];
					$Pack = $ItemArray[$k][2];
					$Unit = $ItemArray[$k][3];
					$qtyCases = $ItemArray[$k][4];
					$Qty = $qtyCases;
					
					$CheckItemStockRecord = $this->godown_model->CheckStockRecord($ItemID,$TrnsTo);
					
					if(empty($CheckItemStockRecord)){
						$insertStock = array(
						'PlantID' =>$selected_company,
						'FY' =>$FY,
						'cnfid' =>1,
						'ItemID' =>$ItemID,
						'gtiqty' =>$Qty,
						'GodownID' =>$TrnsTo,
						'UserId' =>$_SESSION['username'],
						//'EffDate' =>date('Y-m-d H:i:s'),
						'EffDate' =>$Transdate
                        );
                        $this->db->insert(db_prefix() . 'stockmaster',$insertStock);
					}
					
					
                    $HistoryArrayOut = array(
					'PlantID' =>$selected_company,
					'FY' =>$FY,
					'cnfid' =>'1',
					'OrderID' =>$TransNumber,
					'BillID' =>$TransNumber,
					'TransID' =>$TransNumber,
					'TransDate' =>date('Y-m-d H:i:s'),
					'TransDate2' =>$Transdate,
					'TType' =>'T',
					'TType2' =>'Out',
					'AccountID' =>$TrnsFrom,
					'GodownID' =>$TrnsFrom,
					'ItemID' =>$ItemID,
					'SaleRate' =>null,
					'BasicRate' =>null,
					'SuppliedIn' =>'CS',
					'OrderQty' =>$Qty,
					'BilledQty' =>$Qty,
					'CaseQty' =>$Pack,
					'OrderAmt' =>null,
					'ChallanAmt' =>null,
					'NetOrderAmt' =>null,
					'NetChallanAmt' =>null,
					'Ordinalno' =>$OrdNo,
					'UserID' =>$_SESSION['username'],
                    );
					//print_r($HistoryArrayOut);
					$this->db->insert(db_prefix() . 'history',$HistoryArrayOut);
					$OrdNo++;
                    $HistoryArrayIn = array(
					'PlantID' =>$selected_company,
					'FY' =>$FY,
					'cnfid' =>'1',
					'OrderID' =>$TransNumber,
					'BillID' =>$TransNumber,
					'TransID' =>$TransNumber,
					'TransDate' =>date('Y-m-d H:i:s'),
					'TransDate2' =>$Transdate,
					'TType' =>'T',
					'TType2' =>'In',
					'AccountID' =>$TrnsTo,
					'GodownID' =>$TrnsTo,
					'ItemID' =>$ItemID,
					'SaleRate' =>null,
					'BasicRate' =>null,
					'SuppliedIn' =>'CS',
					'OrderQty' =>$Qty,
					'BilledQty' =>$Qty,
					'CaseQty' =>$Pack,
					'OrderAmt' =>null,
					'ChallanAmt' =>null,
					'NetOrderAmt' =>null,
					'NetChallanAmt' =>null,
					'Ordinalno' =>$OrdNo,
					'UserID' =>$_SESSION['username'],
                    );
                    //print_r($HistoryArrayIn);
					$this->db->insert(db_prefix() . 'history',$HistoryArrayIn);
					$OrdNo++;
				}
				
                echo json_encode(true);
                die;
				}else{
				echo json_encode(false);
                die;
			}
		}
		
		
		public function DamagePackingQtyReport()
		{
			if (!has_permission_new('DamagePackingQtyReport', '', 'view')) {
				ajax_access_denied();
			}
			$this->load->model('production_model');
			$data['title'] = "Damage Packing Qty Report";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$this->load->view('admin/production/DamagePackingQtyReport', $data);
		}
		
		public function load_data_for_damage_production_packing()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'status_list'  => $this->input->post('status_list')
			);
			$data = $this->production_model->load_data_for_damage_production($data);
			if(count($data) >0){
				$i = 1; 
				$TotalBatch = 0;
				$StandardPacking = 0;
				$ActualPacking = 0;
				$DamageQty = 0;
				$TotalWeight = 0;
				foreach($data as $value){
					$url = '"'.admin_url('production/production_order/' . $value['pro_order_id']).'"';
					
					$Weight = number_format((intval($value['PackingQty'])*$value['weight']), 2, '.', '');
					$diff = intval($value['Finish_good_qty']) - $value['PackingQty'];
					
					
					$TotalBatch += $value['batch_qty'];
					$StandardPacking += intval($value['Finish_good_qty']);
					$ActualPacking += intval($value['PackingQty']);
					$DamageQty += $diff;
					$TotalWeight += $Weight;
					
					$html1.= '<tr>'; 
					$html1.= '<td>'.$i.'</td>';
					
					$html1.= '<td><span class="recipeID">'.strtoupper($value['recipeID']).'</span></td>';
					$html1.= '<td>'.strtoupper($value['description']).'</td>';
					$html1.= '<td align="right">'.$value['batch_qty'].'</td>';
					$html1.= '<td align="right"><span class="StdPackingQty">'.intval($value['Finish_good_qty']).'</span></td>';
					
					$html1.= '<td align="right">'.intval($value['PackingQty']).'</td>';
					
					$html1.= '<td align="right"><span class="DamageQty">'.number_format($diff,2,'.','').'</span></td>';
					
					$html1.= '<td>'.$Weight.'</td>';
					$html1.= '<td>'.$value['production_status'].'</td>';
					$html1.= '</tr>';
					$i++;
				}
				$html1.= '<tr>'; 
				$html1.= '<td align="right" colspan="3">Total</td>';
				$html1.= '<td align="right">'.$TotalBatch.'</td>';
				$html1.= '<td align="right" >'.$StandardPacking.'</td>';
				$html1.= '<td align="right" >'.$ActualPacking.'</td>';
				$html1.= '<td align="right" >'.$DamageQty.'</td>';
				$html1.= '<td >'.$TotalWeight.'</td>';
				$html1.= '<td ></td>';
				$html1.= '</tr>';
				}else{
				$html1.= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html1);
		}
		
		public function GetMonthlyDamageReport()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'status_list'  => $this->input->post('status_list')
			);
			$TotalDamage = $this->production_model->GetMonthlyDamageReport($data);
			// echo "<pre>";print_r($TotalExpense);die;
			$return = [
			'TotalDamage' => $TotalDamage,
			];
			
			echo json_encode($return);
		}
		
		public function export_damage_production_packing()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'status_list'  => $this->input->post('status_list')
				);
				$data = $this->production_model->load_data_for_damage_production($data); 
				$this->load->model('sale_reports_model');    
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				
				$writer = new XLSXWriter();
				
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Damage Qty Report : ".$this->input->post('from_date')." To " .$this->input->post('to_date') ." For : ".$this->input->post('status_list');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Sr No."] =  'Sr No.';
				$set_col_tk["RecipeID"] =  'RecipeID';
				$set_col_tk["Item Name"] =  'Item Name';
				$set_col_tk["No Of Batch"] =  'No Of Batch';
				$set_col_tk["Standard Packing Qty"] =  'Standard Packing Qty';
				$set_col_tk["Actual Packing Qty"] =  'Actual Packing Qty';
				$set_col_tk["Damage Qty"] =  'Damage Qty';
				$set_col_tk["Packing Weight (Kg)"] =  'Packing Weight (Kg)';
				$set_col_tk["Status"] =  'Status';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 1; 
				$TotalBatch = 0;
				$StandardPacking = 0;
				$ActualPacking = 0;
				$DamageQty = 0;
				$TotalWeight = 0;
				foreach($data as $value){
					$url = '"'.admin_url('production/production_order/' . $value['pro_order_id']).'"';
					
					$Weight = number_format((intval($value['PackingQty'])*$value['weight']), 2, '.', '');
					$diff = intval($value['Finish_good_qty']) - $value['PackingQty'];
					
					
					$TotalBatch += $value['batch_qty'];
					$StandardPacking += intval($value['Finish_good_qty']);
					$ActualPacking += intval($value['PackingQty']);
					$DamageQty += $diff;
					$TotalWeight += $Weight;
					
					$list_add = [];
					$list_add[] = $i;
					$list_add[] = strtoupper($value['recipeID']);
					$list_add[] = strtoupper($value['description']);
					$list_add[] = $value['batch_qty'];
					$list_add[] = intval($value['Finish_good_qty']);
					$list_add[] = intval($value['PackingQty']);
					$list_add[] = number_format($diff,2,'.','');
					$list_add[] = $Weight;
					$list_add[] = $value['production_status'];
					$writer->writeSheetRow('Sheet1', $list_add);
					
					$i++;
				}
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "Total";
				$list_add[] = $TotalBatch;
				$list_add[] = $StandardPacking;
				$list_add[] = $ActualPacking;
				$list_add[] = $DamageQty;
				$list_add[] = $TotalWeight;
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'Damage Qty.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function PhysicalStockEntry()
		{
			if (!has_permission_new('PhysicalStockEntry', '', 'view')) {
				access_denied('Invoice Items');
			}
			
			$data['title'] = "Phyical Stock Entry";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$data['main_item_group'] = $this->production_model->get_main_item_group();
			$this->load->view('admin/production/PhysicalStockEntry', $data);
		}
		
		
		public function GetSubgroup1Data()
		{
			$MainItemGroup = $this->input->post('MainItemGroup');
			$Subgroup                    = $this->production_model->GetSubgroup1Data($MainItemGroup);
			echo json_encode($Subgroup);
		}
		
		public function Get_ItemData_for_PhysicalStock()
		{
			$data = array(
			'Date' => $this->input->post('Date'),
			'MainGroup'  => $this->input->post('MainGroup'),
			'SubGroup1'  => $this->input->post('SubGroup1')
			);
			$data = $this->production_model->Get_ItemData_for_PhysicalStock($data);
			// echo "<pre>";print_r($data);die;
			if(count($data) >0){
				$minutes = 0;
				$i = 1; 
				$html = '';
				foreach($data as $value){
					
					$html.= '<tr>'; 
					$html.= '<td>'.$i.'</td>';
					$html.= '<td><span class="ItemID">'.$value['item_code'].'</span></td>';
					$html.= '<td><span class="">'.$value['description'].'</span></td>';
					$html.= '<td align="left"><span class="Erp_Stock">'.number_format((float)($value['StockBal']), 2, '.', '').'</span></td>';
					$html.= '<td align="right"><input type="text" class="form-control PhysicalQty" id="PhysicalQty'.$i.'" value="" ></td>';
					
					$html.= '</tr>';
					$i++;
				}
				}else{
				$html.= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html);
		}
		
		public function SavePhysicalStock(){
			$selected_company = $this->session->userdata('root_company');
			$FY = $this->session->userdata('finacial_year');
			
			// Get the raw POST data (JSON payload)
			$requestBody = file_get_contents('php://input');
			
			// Decode the JSON data into a PHP associative array
			$data = json_decode($requestBody, true);
			
			$ItemData = $data['tabledata']; 
			$Date = $data['Date']; 
			$dt = DateTime::createFromFormat('d/m/Y H:i', $Date);
			if ($dt) {
				$dates = $dt->format('Y-m-d H:i:s');
				} else {
				$dates = null; // invalid format
			}
			$MainGroup = $data['MainGroup']; 
			$SubGroup1 = $data['SubGroup1']; 
			
			$affected = 0;
			if(count($ItemData) >0)
			{	
				
				$new_TransNumber = get_option('next_physical_stock_number_for_cspl');
				$TransNumber = 'PSE'.$FY.str_pad($new_TransNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
				
				$masterData = array(
				'PlantID'=> $selected_company,
				'FY'=> $FY,
				'TransID'=> $TransNumber,
				'Transdate'=> $dates,
				'Transdate2'=> date('Y-m-d H:i:s'),
				'MainGroup'=> $MainGroup,
				'SubGroup1'=> $SubGroup1,
				'UserID'=> $_SESSION['username'],
				);
				$this->db->insert(db_prefix() . 'PhysicalStockEntry',$masterData);
				if($this->db->affected_rows() > 0){
					$this->production_model->increment_next_physical_stock_number();
					foreach ($ItemData as $row) {
						$ItemID = $row['ItemID'];
						$Erp_Stock = $row['Erp_Stock'];
						$Qty = $row['Qty'];
						
						$item_data = array(
						"TransID"=>$TransNumber,
						"ItemID"=>$ItemID,
						"Erp_Stock"=>$Erp_Stock,
						"Qty"=>$Qty,
						"TransDate"=>$dates,
						"UserID"=>$this->session->userdata('username')
						); 
						
						if($this->db->insert(db_prefix() . 'PhysicalStockDetail', $item_data)){
							$affected++;
						}
						
					}
				}
			}
			if($affected >0)
			{
				echo json_encode(true);
				}else{
				echo json_encode(false);
			}
			
		}
		
		public function PhysicalStockEntryReport()
		{
			if (!has_permission_new('PhysicalStockEntryReport', '', 'view')) {
				access_denied('Invoice Items');
			}
			
			$data['title'] = "Phyical Stock Entry Report";
			$this->load->model('accounts_master_model');
			$data['company_detail'] = $this->accounts_master_model->get_company_detail();
			$data['main_item_group'] = $this->production_model->get_main_item_group();
			$data['StockEntryStaff'] = $this->production_model->StockEntryStaff();
			$this->load->view('admin/production/PhysicalStockEntryReport', $data);
		}
		
		public function GetPhysicalStockEntryData()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'MainGroup'  => $this->input->post('MainGroup'),
			'SubGroup1'  => $this->input->post('SubGroup1'),
			'UserID'  => $this->input->post('UserID')
			);
			$data = $this->production_model->GetPhysicalStockEntryData($data);
			// echo "<pre>";print_r($data);die;
			if (count($data) > 0) {
				$i = 1; 
				$html = '';
				
				// Group by EntryID
				$grouped = [];
				foreach ($data as $row) {
					$grouped[$row['TransID']][] = $row;
				}
				// echo "<pre>";print_r($grouped);die;
				foreach ($grouped as $entryID => $rows) {
					$rowCount = count($rows); // rowspan value
					$first = true;
					
					foreach ($rows as $value) {
						$html .= '<tr>';
						
						// Sr. No only on first row
						if ($first) {
							$html .= '<td rowspan="'.$rowCount.'">'.$i.'</td>';
							$html .= '<td rowspan="'.$rowCount.'">'.$value['TransID'].'</td>';
							$html .= '<td rowspan="'.$rowCount.'">'._d(substr($value["TransDate"],0,19)).'</td>';
						}
						
						$html .= '<td>'.$value['ItemID'].'</td>';
						$html .= '<td><span class="">'.$value['description'].'</span></td>';
						$html .= '<td align="left">'.number_format((float)($value['Erp_Stock']), 2, '.', ',').'</td>';
						$html .= '<td align="right">'.number_format((float)($value['Qty']), 2, '.', ',').'</td>';
						$html .= '<td align="right">'.number_format((float)($value['Erp_Stock'] - $value['Qty']), 2, '.', ',').'</td>';
						$html .= '<td>'.$value['AccountName'].'</td>';
						$html .= '<td>'._d(substr($value['Transdate2'],0,10)).'</td>';
						
						$html .= '</tr>';
						$first = false;
					}
					$i++;
				}
				} else {
				$html .= '<span style="color:red;">No Data found...</span>';
			}
			echo json_encode($html);
		}
		
		public function export_PhysicalStockEntryReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'MainGroup'  => $this->input->post('MainGroup'),
				'SubGroup1'  => $this->input->post('SubGroup1'),
				'UserID'  => $this->input->post('UserID')
				);
				$data = $this->production_model->GetPhysicalStockEntryData($data); 
				$this->load->model('sale_reports_model');    
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				
				$writer = new XLSXWriter();
				//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Physical Stock Entry Report";
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 8);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Sr No."] =  'Sr No.';
				$set_col_tk["EntryID"] =  'EntryID';
				$set_col_tk["Entry Date"] =  'Entry Date';
				$set_col_tk["Item ID"] =  'Item ID';
				$set_col_tk["Item Name"] =  'Item Name';
				$set_col_tk["ERP Stock"] =  'ERP Stock';
				$set_col_tk["Physical Stock"] =  'Physical Stock';
				$set_col_tk["Discrepancy count"] =  'Discrepancy count';
				$set_col_tk["Created By"] =  'Created By';
				$set_col_tk["Created Date"] =  'Created Date';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 1; 
				
				
				foreach ($data as $value) {
				$list_add = [];
					$list_add[] = $i;
					$list_add[] = $value['TransID'];
					$list_add[] = _d(substr($value["TransDate"],0,19));
					$list_add[] = $value['ItemID'];
					$list_add[] = $value['description'];
					$list_add[] = number_format((float)($value['Erp_Stock']), 2, '.', '');
					$list_add[] = number_format((float)($value['Qty']), 2, '.', '');
					$list_add[] = number_format((float)($value['Erp_Stock'] - $value['Qty']), 2, '.', '');
					$list_add[] = $value['AccountName'];
					$list_add[] = _d(substr($value['Transdate2'],0,10));
										
					$writer->writeSheetRow('Sheet1', $list_add);
					$i++;
				}
				
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'Physical Stock.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
	}																																																				