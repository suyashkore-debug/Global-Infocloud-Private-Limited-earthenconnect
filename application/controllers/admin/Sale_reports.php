<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Sale_reports extends AdminController
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('sale_reports_model');
			$this->load->model('challan_model');
			$this->load->model('misc_reports_model');
		}
		
		/* Get all invoices in case user go on index page */
		public function index($id = '')
		{
			//$this->list_orders($id);
			$this->daily_sale();
		}
		
		public function SalesDashboard()
		{
			if (!has_permission_new('SalesDashboard', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']      = "Sales Dashboard";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['state'] = $this->sale_reports_model->GetStateList();
			$data['SubGroup'] = $this->sale_reports_model->GetItemGroupList('1');
			$data['TopItem'] = $this->sale_reports_model->TopOrderdItem();
			$data['TotalPendingOrder'] = $this->sale_reports_model->GetTotalPendingOrder();
			$data['TopSaleAmtParty'] = $this->sale_reports_model->TopSaleAmtParty();
			$data['TotalSaleAmt'] = $this->sale_reports_model->GetTotalSaleAmt();
			$data['TotalSaleRtmAmt'] = $this->sale_reports_model->GetTotalSaleRtnAmt();
			/*echo "<pre>";
				print_r($data['TotalSaleRtmAmt']);
			die;*/
			$data['TodaysSale'] = $this->sale_reports_model->TodaysSale();
			$data['TotalGstAmt'] = $this->sale_reports_model->GetTotalGstAmt();
			$data['AvgInvoiceAmt'] = $this->sale_reports_model->AvgInvoiceAmtInCurrentMonth();
			$data['NewParties'] = $this->sale_reports_model->NewParties();
			$data['CustomerCount'] = $this->sale_reports_model->CustomerCount();
			$data['ItemCount'] = $this->sale_reports_model->ItemCount();
			//$data['chart_types_values']     = json_encode($this->sale_reports_model->GetDaywiseSaleForthisMonth());
			//print_r($data['chart_types_values']);die;
			$this->load->model('currencies_model');
			$data['base_currency'] = $this->currencies_model->get_base_currency();
			$data['ItemList'] = $this->sale_reports_model->GetItemListByGroup('1');
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/SalesDashboard', $data);
		}
		public function GetSaleCounters()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$TopItem = $this->sale_reports_model->TopOrderdItem($data);
			$TotalGstAmt = $this->sale_reports_model->GetTotalGstAmt($data);
			$TotalPendingOrder = $this->sale_reports_model->GetTotalPendingOrder($data);
			$AvgInvoiceAmt = $this->sale_reports_model->AvgInvoiceAmtInCurrentMonth($data);
			$NewParties = $this->sale_reports_model->NewParties($data);
			$TotalSaleRtmAmt = $this->sale_reports_model->GetTotalSaleRtnAmt($data);
			$TopSaleAmtParty = $this->sale_reports_model->TopSaleAmtParty($data);
			$TotalSaleAmtByAnyParty = $this->sale_reports_model->TotalSaleAmtByAnyParty($data);
			$return = [
			'TopItem' => $TopItem,
			'TotalPendingOrder' => $TotalPendingOrder,
			'TotalGstAmt' => $TotalGstAmt,
			'AvgInvoiceAmt' => $AvgInvoiceAmt,
			'NewParties' => $NewParties,
			'TotalSaleRtmAmt' => $TotalSaleRtmAmt,
			'TopSaleAmtParty' => $TopSaleAmtParty,
			'TotalSaleAmtByAnyParty' => $TotalSaleAmtByAnyParty,
			];
			
			echo json_encode($return);
		}
		//====================== New Dashboard Page Load ===============================
		public function NewSalesDashboard()
		{
			if (!has_permission_new('SalesDashboard', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Sales Dashboard";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['AllPartyList'] = $this->sale_reports_model->GetAllPartyList();
			$data['MainItemGroup'] = $this->sale_reports_model->get_MainItemGroup_data();
			$data['CityList'] = $this->sale_reports_model->GetAllCityList();
			$data['StationList'] = $this->sale_reports_model->GetAllStationList();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/NewSalesDashboard', $data);
		}
		public function GetPartyListByTradeType()
		{
			$data = array(
			"TradeType"=>$this->input->post('TradeType'),
			"FromDate"=>$this->input->post('FromDate'),
			"ToDate"=>$this->input->post('ToDate')
			);
			$Party     = $this->sale_reports_model->GetPartyListByTradeType($data);
			echo json_encode($Party);
		}
		
		public function GetPartyCityListByFilter()
		{
			$data = array(
			"TradeType"=>$this->input->post('TradeType'),
			"FromDate"=>$this->input->post('FromDate'),
			"ToDate"=>$this->input->post('ToDate')
			);
			$CityList     = $this->sale_reports_model->GetPartyCityListByFilter($data);
			echo json_encode($CityList);
		}
		
		public function GetPartyStationListByFilter()
		{
			$data = array(
			"TradeType"=>$this->input->post('TradeType'),
			"FromDate"=>$this->input->post('FromDate'),
			"ToDate"=>$this->input->post('ToDate')
			);
			$StationList     = $this->sale_reports_model->GetPartyStationListByFilter($data);
			echo json_encode($StationList);
		}
		//========================== Get Widget For Sale Dashboard =====================	
		public function GetSalesDashboardCounters()
		{	
			$TotalSaleAmt = $this->sale_reports_model->TotalSaleAmt($this->input->post());
			$TotalDiscAmt = $this->sale_reports_model->TotalDiscAmt($this->input->post());
			$TotalFreshRtnAmt = $this->sale_reports_model->TotalFreshRtnAmt($this->input->post());
			$TotalDamageRtnAmt = $this->sale_reports_model->TotalDamageRtnAmt($this->input->post());
			$TotalOrders = $this->sale_reports_model->TotalOrders($this->input->post());
			$TotalPendingOrder = $this->sale_reports_model->TotalPendingOrderCount($this->input->post());
			$TotalInvoice = $this->sale_reports_model->TotalInvoice($this->input->post());
			$AvgOrderValue = $this->sale_reports_model->AvgOrderValue($this->input->post());
			$AvgInvoiceValue = $this->sale_reports_model->AvgInvoiceValue($this->input->post());
			$TotalSoldQty = $this->sale_reports_model->TotalSoldQty($this->input->post());
			$TotalGSTAmt = $this->sale_reports_model->TotalGSTAmt($this->input->post());
			$ItemCount = $this->sale_reports_model->ItemCount();
			$NewPartiesCount = $this->sale_reports_model->NewParties($this->input->post());
			$TopOrderItem = $this->sale_reports_model->TopOrderdItem($this->input->post());
			// print_r($MileageGap);die;
			$return = [
			'TotalSaleAmt' =>  number_format($TotalSaleAmt, 2, '.', ','),
			'TotalDiscAmt' =>  number_format($TotalDiscAmt, 2, '.', ','),
			'TotalFreshRtnAmt' =>  number_format($TotalFreshRtnAmt, 2, '.', ','),
			'TotalDamageRtnAmt' =>  number_format($TotalDamageRtnAmt, 2, '.', ','),
			'TotalOrders' =>  number_format($TotalOrders->AllOrder, 0, '.', ','),
			'CancelOrder' =>  number_format($TotalOrders->CancelOrder, 0, '.', ','),
			'TotalInvoice' =>  number_format($TotalInvoice, 0, '.', ','),
			'AvgOrderValue' =>  number_format($AvgOrderValue, 2, '.', ','),
			'AvgInvoiceValue' =>  number_format($AvgInvoiceValue, 2, '.', ','),
			'TotalSoldQty' =>  number_format($TotalSoldQty, 0, '.', ','),
			'TotalPendingOrder' =>  number_format($TotalPendingOrder, 0, '.', ','),
			'GSTCollectionAmt'=>number_format($TotalGSTAmt, 2, '.', ','),
			'ItemCount'=>number_format($ItemCount->TotalItem, 0, '.', ','),
			'NewPartys'=>number_format($NewPartiesCount->NewParty, 0, '.', ','),
			'BestSellerSKUName'=>$TopOrderItem->description_name,
			'BestSellerSKUAmt'=>number_format($TopOrderItem->TotalSale, 2, '.', ','),
			];
			
			echo json_encode($return);
		}
		
		
		public function GetTopCustomer()
		{
			
			$TransData = $this->sale_reports_model->GetTopCustomer($this->input->post());
			$return = [
			'TransData' => $TransData,
			];
			
			echo json_encode($return);
		}
		public function GetTopGroupItem()
		{
			
			$TransData = $this->sale_reports_model->GetTopGroupItem($this->input->post());
			$return = [
			'TransData' => $TransData,
			];
			
			echo json_encode($return);
		}
		public function GetStationWiseTopSale()
		{
			
			$TransData = $this->sale_reports_model->GetStationWiseTopSale($this->input->post());
			$return = [
			'TransData' => $TransData,
			];
			
			echo json_encode($return);
		}
		public function GetMonthWiseSale()
		{
			
			$TransData = $this->sale_reports_model->GetMonthWiseSale($this->input->post());
			echo "<pre>";
			print_r($TransData);die;
			$return = [
			'Sales' => $TransData['Sales'],
			'Months' => $TransData['Months'],
			];
			
			echo json_encode($return);
		}
		public function GetCityWiseTopSale()
		{
			
			$TransData = $this->sale_reports_model->GetCityWiseTopSale($this->input->post());
			$return = [
			'TransData' => $TransData,
			];
			
			echo json_encode($return);
		}
		public function GetTopCustomerReturnRate()
		{
			
			$TransData = $this->sale_reports_model->GetTopCustomerReturnRate($this->input->post());
			$return = [
			'TransData' => $TransData,
			];
			
			echo json_encode($return);
		}
		public function GetTopReturnRateByItemGroup()
		{
			
			$TransData = $this->sale_reports_model->GetTopReturnRateByItemGroup($this->input->post());
			$return = [
			'TransData' => $TransData,
			];
			
			echo json_encode($return);
		}
		
		public function CrateAlertReport()
		{
			
			$data['title']                = "Crate Alert Report";
			
			$this->load->view('admin/sale_reports/CrateAlertReport', $data);
		}
		
		//======================== Get Daily Sale Report ===============================
		public function GetDailySaleReports()
		{
			
			$result = $this->sale_reports_model->GetDaywiseSaleForthisMonth($this->input->post());
			echo json_encode($result);
		}
		public function GetDailySaleReportsNew()
		{
			
			$result = $this->sale_reports_model->GetDaywiseSaleForthisMonthNew($this->input->post());
			echo json_encode($result);
		}
		//==================== Get Daily Sale Return Report ============================
		public function GetDayWiseSaleReturnReports()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'Items'  => $this->input->post('Items'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'state'  => $this->input->post('state'),
			"ReportType"  => $this->input->post('ReportType'),
			);
			$result = $this->sale_reports_model->GetDayWiseSaleReturnReports($data);
			echo json_encode($result);
		}
		//======================== Get Daily Sale Report ===============================
		public function GetCalenderMonthlySaleData()
		{
			$result = $this->sale_reports_model->GetCalenderMonthlySaleData($this->input->post());
			echo json_encode($result);
		} 
		public function GetCalenderMonthlySaleDataNew()
		{
			$result = $this->sale_reports_model->GetCalenderMonthlySaleDataNew($this->input->post());
			echo json_encode($result);
		} 
		public function GetCalenderMonthlySaleReturnDataNew()
		{
			$result = $this->sale_reports_model->GetCalenderMonthlySaleReturnDataNew($this->input->post());
			echo json_encode($result);
		} 
		//=========== Get Daily Sale return Calander Report ============================
		public function GetCalenderMonthlySaleReturnData()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'Items'  => $this->input->post('Items'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'state'  => $this->input->post('state'),
			"ReportType"  => $this->input->post('ReportType'),
			"Month"  => $this->input->post('Month'),
			);
			$result = $this->sale_reports_model->GetCalenderMonthlySaleReturnData($data);
			echo json_encode($result);
		}
		//======================== Get Customer Overview ===============================
		public function GetCustomerOverview()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'Items'  => $this->input->post('Items'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'state'  => $this->input->post('state'),
			"ReportType"  => $this->input->post('ReportType'),
			"Month"  => $this->input->post('Month'),
			);
			$result = $this->sale_reports_model->GetCustomerOverview($data);
			echo json_encode($result);
		}
		public function GetCustomerOverviewNew()
		{
			$result = $this->sale_reports_model->GetCustomerOverviewNew($this->input->post());
			echo json_encode($result);
		}
		//======================== Get Daily Sale Report ===============================
		public function GetDailySaleReportsByAccountID()
		{
			$data = array(
    		'AccountID' => $this->input->post('AccountID'),
    		'from_date'  => $this->input->post('from_date'),
    		'to_date'  => $this->input->post('to_date'),
			);
			$result = $this->sale_reports_model->GetDaywiseSaleForthisMonthByAccountID($data);
			echo json_encode($result);
		}
		//==================== Get YOY Monthly Sale Report =============================
		public function GetYOYMonthlySaleReports()
		{
			/*$data = array(
				'Items'  => $this->input->post('Items'),
				'SubGroup'  => $this->input->post('SubGroup'),
				'state'  => $this->input->post('state'),
				"ReportType"  => $this->input->post('ReportType'),
			);*/
			$result = $this->sale_reports_model->GetYOYMonthlySaleReports($this->input->post());
			echo json_encode($result);
		}
		//==================== Get Monthly Sale Return =================================
		public function MonthlySaleReturns()
		{
			$data = array(
    		'Items'  => $this->input->post('Items'),
    		'SubGroup'  => $this->input->post('SubGroup'),
    		'state'  => $this->input->post('state'),
    		"ReportType"  => $this->input->post('ReportType'),
			);
			$result = $this->sale_reports_model->GetMonthlySaleReturns($data);
			echo json_encode($result);
		}
		public function MonthlySaleReturnsNew()
		{
			$result = $this->sale_reports_model->GetMonthlySaleReturnsNew($this->input->post());
			echo json_encode($result);
		}
		//======================== Sales ForeCasting ===================================
		public function SalesForecasting()
		{
			$data = array(
    		'Items'  => $this->input->post('Items'),
    		'SubGroup'  => $this->input->post('SubGroup'),
    		'state'  => $this->input->post('state')
			);
			$result = $this->sale_reports_model->GetSalesForecasting($data);
			echo json_encode($result);
		}
		//======================== Monthly Best Seller Items ===========================
		public function GetMonthlyBestSellerItems()
		{
			$result = $this->sale_reports_model->MonthlyBestSellerItems($this->input->post());
			echo json_encode($result);
		}
		//==================== Top Selling Items Page load =============================
		public function TopSellingItem()
		{
			if (!has_permission_new('SalesDashboard', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Top Salling Items";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['state'] = $this->sale_reports_model->GetStateList();
			$data['SubGroup'] = $this->sale_reports_model->GetItemGroupList('1');
			$data['ItemList'] = $this->sale_reports_model->GetItemListByGroup('1');
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/TopSellingItems', $data);
		}
		
		//====================== Get Items List By Item Group ==========================
		public function GetGroupWiseItemList()
		{
			$SubGroup  = $this->input->post('SubGroup');
			$result = $this->sale_reports_model->GetGroupWiseItemList($SubGroup);
			echo json_encode($result);
		}
		
		//======================== Load Top SKU'S ======================================
		public function GetTopSellingItem()
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
			$result = $this->sale_reports_model->GetTopSellingItem($data);
			
			$data = [
			'ChartData' => $result['ChartData'],
			];
			
			echo json_encode($data);
		}
		
		public function GetTopSellingItemInventory()
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
			$result = $this->sale_reports_model->GetTopSellingItemInventory($data);
			
			/*$ItemList = $result['ItemCustomers'];
				$Accounts = $result['customerList'];
				$itemIDs = $result['itemIDs'];
				
				echo json_encode($result);
				die;
				$html =''; 
				$html.= '<thead>';
				
				$html.= '<tr>';
				$html.= '<th colspan="2" class="fontsize"></th>';
				foreach($itemIDs as $ItemID => $description){
				$html.= '<th class="fontsize" colspan="2" style="text-align:center; text-transform: uppercase;">'.$description.'</th>';
				}
				$html.= '</tr>';
				$html.= '<tr>';
				$html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Account ID</th>';
				$html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Account Name</th>';
				foreach($itemIDs as $ItemID => $description){
				$html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Qty</th>';
				$html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Amount</th>';
				}
				$html.= '</tr>';
				
				$html.= '</thead>';
				$html.= '<tbody>';
				
				foreach($Accounts as $Account){
				$html.= '<tr>';
				$html.= '<td  class="fontsize">'.$Account['AccountID'].'</td>';
				$html.= '<td  class="fontsize">'.$Account['company'].'</td>';
				foreach($itemIDs as $ItemID => $description){
				$Qty = '';
				$Amt = '';
				
				foreach($ItemList as $Item){
				if($Item['AccountID'] == $Account['AccountID'] && $ItemID == $Item['ItemID']){
				$Qty = (int) $Item['total_qty'];
				$Amt = $Item['total_amt'];
				}
				}
				$html.= '<td class="fontsize" style="text-align:left;">'.$Qty.'</td>';
				$html.= '<td class="fontsize" style="text-align:left;">'.$Amt.'</td>';
				}
				$html.= '<tr>';
				$i++;
				}
				$html.= '<tfoot>';
				$html.= '<tr>';
				$html.= '<td style="text-transform: uppercase;text-align:right;" class="fontsize" colspan="2"><b>Total</b></td>';
				foreach($itemIDs as $ItemID => $description){
				$AllQty = '';
				$AllAmt = '';
				
				foreach($ItemList as $Item){
				if($ItemID == $Item['ItemID']){
				$AllQty += (int) $Item['total_qty'];
				$AllAmt += $Item['total_amt'];
				}
				}
				$html.= '<td class="fontsize" style="text-align:left;">'.$AllQty.'</td>';
				$html.= '<td class="fontsize" style="text-align:left;">'.$AllAmt.'</td>';
				}
				$html.= '</tr>';
			$html.= '</tfoot>';*/
			
			$data = [
			'ChartData' => $result['ChartData'],
			/*'TableData' => $html,*/
			];
			
			echo json_encode($data);
		}
		
		
		// Get Result for Party Wise Crate
		public function GetCrateAlertReport()
		{
			
			
			
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$body_data = $this->misc_reports_model->GetAllCrateLedger($filterdata);
			// echo json_encode($body_data);
			// die;
			$company_details = $this->misc_reports_model->get_company_detail();
			$table_width = '100%';
			$colspan = 6;
			$html = '';
			$html .= '<table class="table-striped table-bordered CrateLedger" id="CrateLedger" style="height: 600px;" width="'.$table_width.'">';
			$html .= '<thead style="font-size:11px;">';
			
			
			
			$html .= '<tr>';
			$html .= '<th class="sortable" align="left">Sr.No.</th>';
			$html .= '<th class="sortable" width="30%" align="left">Party Name</th>';
			$html .= '<th class="sortable" align="right">Crate Limit</th>';
			$html .= '<th class="sortable" align="right">Balance</th>';
			$html .= '<th class="sortable" align="right">Extra Crate</th>';
			$html .= '</tr>';
			
			
			$html .= '</thead>';
			$html .= '<tbody>';
			$sr = 1;
			$totallimit = 0;
			$totalExtraremain = 0;
			$totalcredit = 0;
			$totalbal = 0;
			foreach ($body_data as $key1 => $value1) {
				
				
				if(empty($value1['CrateLimit'])){
					$value1['CrateLimit'] = 0;
				}
				
				$ExtraCrate = $value1['Balance'] - $value1['CrateLimit'];
				
				if($ExtraCrate > 0){
					$totalbal += $value1['Balance'];
					$totalExtra += $ExtraCrate;
					$totallimit += $value1['CrateLimit'];
					$html .= '<tr>';
					$html .= '<td align="right">'.$sr.'</td>';
					$html .= '<td align="left">'.$value1['CustomerName'].'</td>';
					$html .= '<td align="right">'.$value1['CrateLimit'].'</td>';
					$html .= '<td align="right">'.$value1['Balance'].'</td>';
					$html .= '<td align="right">'.$ExtraCrate.'</td>';
					$html .= '</tr>';
					$sr++;
				}
			}
			
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			
			$html .= '<tr>';
			$html .= '<td align="right" colspan="2">Total</td>';
			$html .= '<td align="right">'.$totallimit.'</td>';
			$html .= '<td align="right">'.$totalbal.'</td>';
			$html .= '<td align="right">'.$totalExtra.'</td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function GetTopCrateAlert()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$body_data = $this->misc_reports_model->GetAllCrateLedger($data);
			$chart = [];
			foreach ($body_data as $key1 => $value1) {
				if(empty($value1['CrateLimit'])){
					$value1['CrateLimit'] = 0;
				}
				$ExtraCrate = $value1['Balance'] - $value1['CrateLimit'];
				if($ExtraCrate > 0){
					array_push($chart, [
					'name' 		=> $value1['CustomerName'],
					'y' 	=>	$ExtraCrate,
					]);
				}
			}
			
			$MaxCount = $this->input->post('MaxCount');
			
			usort($chart, function($a, $b) {
				return $b['y'] <=> $a['y'];
			});
			
			// Limit to MaxCount
			$MaxCount = (int)$this->input->post('MaxCount');
			$chart = array_slice($chart, 0, $MaxCount);
			// echo "<pre>";print_r($chart);die;
			
			$data = [
			'ChartData' => $chart,
			];
			
			echo json_encode($data);
		}
		public function export_GetTopSellingItem()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'ItemCount'  => $this->input->post('ItemCount'),
				'state'  => $this->input->post('state'),
				'SubGroup'  => $this->input->post('SubGroup'),
				'ReportType'  => $this->input->post('ReportType'),
				'Items'  => $this->input->post('Items'),
				);
				$result = $this->sale_reports_model->GetTopSellingItem($data);  
				$ItemList = $result['ItemCustomers'];
				$Accounts = $result['customerList'];
				$itemIDs = $result['itemIDs'];
				
				
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Top Selling Items ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
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
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk[] =  '';
				$set_col_tk[] = '';
				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 1);  //merge cells
				$start = 2;
				foreach($itemIDs as $ItemID => $description){
					$set_col_tk[$description] = $description;
					$set_col_tk[] = '';
					$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = $start, $end_row = 4, $end_col = ($start+1));  //merge cells
					$start = $start + 2;
				}
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				$set_col_tk = [];
				$set_col_tk[] =  '';
				$set_col_tk[] = '';
				$writer->markMergedCell('Sheet1', $start_row = 5, $start_col = 0, $end_row = 5, $end_col = 1);  //merge cells
				$i = 2;
				foreach($itemIDs as $ItemID => $description){
					$set_col_tk[$i] = 'Qty';
					$i++;
					$set_col_tk[$i] = 'Amount';
					$i++;
				}
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				foreach($Accounts as $Account){
					$list_add = [];
					$list_add[] = $Account['AccountID'];
					$list_add[] = $Account['company'];
					foreach($itemIDs as $ItemID => $description){
						$Qty = '';
						$Amt = '';
						
						foreach($ItemList as $Item){
							if($Item['AccountID'] == $Account['AccountID'] && $ItemID == $Item['ItemID']){
								$Qty = (int) $Item['total_qty'];
								$Amt = $Item['total_amt'];
							}
						}
						$list_add[] = $Qty;
						$list_add[] = $Amt;
					}
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				$list_add = [];
				$list_add[] = '';
				$list_add[] = "Total";
				foreach($itemIDs as $ItemID => $description){
					$AllQty = '';
					$AllAmt = '';
					
					foreach($ItemList as $Item){
						if($ItemID == $Item['ItemID']){
							$AllQty += (int) $Item['total_qty'];
							$AllAmt += $Item['total_amt'];
						}
					}
					$list_add[] = $AllQty;
					$list_add[] = $AllAmt;
				}
				$writer->writeSheetRow('Sheet1', $list_add);
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'TopSellingItems.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		//==================== Top Selling Customer Page load =============================
		public function TopSellingCustomer()
		{
			if (!has_permission_new('SalesDashboard', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Top Salling Customer";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['state'] = $this->sale_reports_model->GetStateList();
			$data['SubGroup'] = $this->sale_reports_model->GetItemGroupList('1');
			$data['ItemList'] = $this->sale_reports_model->GetItemListByGroup('1');
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/TopSellingCustomer', $data);
		}
		//========================= Get Top Selling Customer ===========================
		public function GetTopSellingCustomer()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
    		'MaxCount'  => $this->input->post('MaxCount'),
    		'state'  => $this->input->post('state'),
    		'SubGroup'  => $this->input->post('SubGroup'),
    		'Items'  => $this->input->post('Items'),
			);
			$result = $this->sale_reports_model->GetTopSellingCustomer($data);
			/*$Transaction = $result['Transaction'];
				$ItemList = $result['ItemList'];
				$CustomerIDs = $result['CustomerIDs'];
				
				
				$html =''; 
				$html.= '<thead>';
				
				$html.= '<tr>';
				$html.= '<th colspan="2" class="fontsize"></th>';
				foreach($CustomerIDs as $AccountID => $company){
				$html.= '<th class="fontsize" colspan="2" style="text-align:center; text-transform: uppercase;">'.$company.'</th>';
				}
				$html.= '</tr>';
				$html.= '<tr>';
				$html.= '<th class="fontsize sortable2" style="text-align:left; text-transform: uppercase;">Item ID</th>';
				$html.= '<th class="fontsize sortable2" style="text-align:left; text-transform: uppercase;">Item Name</th>';
				foreach($CustomerIDs as $AccountID => $company){
				$html.= '<th class="fontsize sortable2" style="text-align:left; text-transform: uppercase;">Qty</th>';
				$html.= '<th class="fontsize sortable2" style="text-align:left; text-transform: uppercase;">Amount</th>';
				}
				$html.= '</tr>';
				
				$html.= '</thead>';
				$html.= '<tbody>';
				
				foreach($ItemList as $Item){
				$html.= '<tr>';
				$html.= '<td  class="fontsize">'.$Item['item_code'].'</td>';
				$html.= '<td  class="fontsize">'.$Item['description'].'</td>';
				foreach($CustomerIDs as $AccountID => $company){
				$Qty = '';
				$Amt = '';
				
				foreach($Transaction as $Trans){
				if($Trans['AccountID'] == $AccountID && $Item['item_code'] == $Trans['ItemID']){
				$Qty = (int) $Trans['total_qty'];
				$Amt = $Trans['total_amt'];
				}
				}
				$html.= '<td class="fontsize" style="text-align:left;">'.$Qty.'</td>';
				$html.= '<td class="fontsize" style="text-align:left;">'.$Amt.'</td>';
				}
				$html.= '<tr>';
				$i++;
				}
				$html.= '<tfoot>';
				$html.= '<tr>';
				$html.= '<td style="text-transform: uppercase;text-align:right;" class="fontsize" colspan="2"><b>Total</b></td>';
				foreach($CustomerIDs as $AccountID => $company){
				$AllQty = '';
				$AllAmt = '';
				
				foreach($Transaction as $Trans){
				if($Trans['AccountID'] == $AccountID){
				$AllQty += (int) $Trans['total_qty'];
				$AllAmt += $Trans['total_amt'];
				}
				}
				$html.= '<td class="fontsize" style="text-align:left;">'.$AllQty.'</td>';
				$html.= '<td class="fontsize" style="text-align:left;">'.$AllAmt.'</td>';
				}
				$html.= '</tr>';
			$html.= '</tfoot>';*/
			
			$data = [
			'ChartData' => $result['ChartData'],
			/*'TableData' => $html,*/
			];
			
			echo json_encode($data);
		}
		public function GetStockData()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'Sort'  => $this->input->post('Sort'),
			);
			$AllItemList = $this->sale_reports_model->GetItemList($data);
			$StockData = $this->sale_reports_model->GetStockData($data);
			$StockOQtyData = $this->sale_reports_model->get_item_open_qty($data);
			
			// echo "<pre>";print_r($StockOQtyData);die;
			$chart = [];
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
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){
						$PurchRtnQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){
						$IssueQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){
						$PRDQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){
						$SalesQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){
						$SalesRtnQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Free distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){
						$AdjQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){
						$GOQty += $value1['BilledQty'];
					}
					
					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In")){
						$GIQty += $value1['BilledQty'];
					}
				}
				if($PurchQty !== '0'){
					$PurchQtyCases = floatval($PurchQty) / floatval($CaseQty);
				}
				
				if($PurchRtnQty !== '0'){
					$PurchRtnQtyCases = floatval($PurchRtnQty) / floatval($CaseQty);
				}
				
				if($IssueQty !== '0'){
					$IssueQtyCases = floatval($IssueQty) / floatval($CaseQty);
					$IssueQtyCasesSum += $IssueQtyCases;
				}
				
				if($PRDQty !== '0'){
					$PRDCases = floatval($PRDQty) / floatval($CaseQty);
				}
				if($SalesQty !== '0'){
					$SalesCases = floatval($SalesQty) / floatval($CaseQty);
				}
				if($SalesRtnQty !== '0'){
					$SalesRtnCases = floatval($SalesRtnQty) / floatval($CaseQty);
				}
				
				if($AdjQty !== '0'){
					$AdjCases = floatval($AdjQty) / floatval($CaseQty);
				}
				
				if($GOQty >0){
					$GOCases = floatval($GOQty) / floatval($CaseQty);
				}
				
				if($GIQty >0){
					$GICases = floatval($GIQty) / floatval($CaseQty);
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
				
				$BQty =    $OQTYCases +  $PurchQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases  - $GOCases + $GICases; 
				
				if($value["case_qty"] == '0' || $value["case_qty"] == ''){
					$stockqty = round($BQty) * 1;
					}else{
					$stockqty = round($BQty) * $value["case_qty"];
				}
				
				array_push($chart, [
				'name' 		=> $value["description"],
				'y' 		=>	$BQty,
				]);
			}
			
			if($this->input->post('Sort') == "Highest"){
				usort($chart, function($a, $b) {
					return $b['y'] <=> $a['y'];
				});
				}else{
				usort($chart, function($a, $b) {
					return $a['y'] <=> $b['y'];  // Ascending order
				});
			}
			
			// Get the top 5 elements
			$chart = array_slice($chart, 0, $this->input->post('MaxCount'));
			
			// echo "<pre>";print_r($chart);die;
			$data = [
			'ChartData' => $chart,
			];
			echo json_encode($data);
			die;
		}
		
		
		public function GetSalesReturnReport()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			'state'  => $this->input->post('state'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'TType2'  => $this->input->post('TType2'),
			'ReportIn'  => $this->input->post('ReportIn'),
			);
			$damage = $this->sale_reports_model->GetSalesReturnReport($data);
			$chart = [];
			
			$itemData = [];
			foreach($damage as $return){
				if($this->input->post('ReportIn') == 'amount'){
					$value = (float) $return["ReturnAmt"];
					}else{
					$value = (float) $return["ReturnQty"];
				}
				array_push($chart, [
				'name' 		=> $return["description"],
				'y' 		=>	$value,
				]);
				
				$itemData[$return['ItemID']] = $return['description'];
			}
			$data = [
		    'ChartData' => $chart,
			];
			
			echo json_encode($data);
		}
		
		public function export_GetTopSellingCustomer()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'CustomerCount'  => $this->input->post('CustomerCount'),
				'state'  => $this->input->post('state'),
				);
				$result = $this->sale_reports_model->GetTopSellingCustomer($data);  
				$Transaction = $result['Transaction'];
				$ItemList = $result['ItemList'];
				$CustomerIDs = $result['CustomerIDs'];
				
				
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Top Customers ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
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
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk[] =  '';
				$set_col_tk[] = '';
				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 1);  //merge cells
				$start = 2;
				foreach($CustomerIDs as $AccountID => $company){
					$set_col_tk[$company] = $company;
					$set_col_tk[] = '';
					$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = $start, $end_row = 4, $end_col = ($start+1));  //merge cells
					$start = $start + 2;
				}
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				$set_col_tk = [];
				$set_col_tk[] =  '';
				$set_col_tk[] = '';
				$writer->markMergedCell('Sheet1', $start_row = 5, $start_col = 0, $end_row = 5, $end_col = 1);  //merge cells
				$i = 2;
				foreach($CustomerIDs as $AccountID => $company){
					$set_col_tk[$i] = 'Qty';
					$i++;
					$set_col_tk[$i] = 'Amount';
					$i++;
				}
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				foreach($ItemList as $Item){
					$list_add = [];
					$list_add[] = $Item['item_code'];
					$list_add[] = $Item['description'];
					foreach($CustomerIDs as $AccountID => $company){
						$Qty = '';
						$Amt = '';
						
						foreach($Transaction as $Trans){
							if($Trans['AccountID'] == $AccountID && $Item['item_code'] == $Trans['ItemID']){
								$Qty = (int) $Trans['total_qty'];
								$Amt = $Trans['total_amt'];
							}
						}
						$list_add[] = $Qty;
						$list_add[] = $Amt;
					}
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				$list_add = [];
				$list_add[] = '';
				$list_add[] = "Total";
				foreach($CustomerIDs as $AccountID => $company){
					$AllQty = '';
					$AllAmt = '';
					
					foreach($Transaction as $Trans){
						if($Trans['AccountID'] == $AccountID){
							$AllQty += (int) $Trans['total_qty'];
							$AllAmt += $Trans['total_amt'];
						}
					}
					$list_add[] = $AllQty;
					$list_add[] = $AllAmt;
				}
				$writer->writeSheetRow('Sheet1', $list_add);
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'TopCustomers.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		//================ Sale Vs Sale Return Page Load ===============================
		public function SaleVsSaleRtn()
		{
			if (!has_permission_new('saleVsSaleRtn', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "SaleVsSaleRtn Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
			$data['Subgroup2'] =  $this->sale_reports_model->GetSubgroup2();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/SaleVsSaleRtn', $data);
		}
		
		//========================= Load Sale Vs Sale Return Report ====================
		public function GetSaleVsSaleRtnReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'AccountID'  => $this->input->post('AccountID'),
			'AccountName'  => $this->input->post('AccountName'),
			'locType'  => $this->input->post('locType'),
			'repType'  => $this->input->post('repType'),
			'Subgroup2'  => $this->input->post('Subgroup2'),
			);
			$AccountID = $filterdata["AccountID"];
			$locType = $filterdata["locType"];
			$repType = $filterdata["repType"];
			if($locType == '1'){
				$locTypeName = 'Local';
				}else if($locType == '2'){
				$locTypeName = 'Outstation';
				}elseif($locType == '3'){
				$locTypeName = 'notDefine';
				}else{
				$locTypeName = "All";
			}
			if($repType == '1'){
				$repTypeName = 'AccountWiseDetails';
				}else{
				$repTypeName = 'ItemWiseDetails';
			}
			
			
			$body_Rowdata = $this->sale_reports_model->GetSaleVsSaleRtnBodyRowData($filterdata);
			$body_data = $this->sale_reports_model->GetSaleVsSaleRtnBodyData($filterdata);
			$company_detail = $this->sale_reports_model->get_company_detail();
			
			if($AccountID !==''){
				$colspan = '11';
				}else{
				if($repType == '2'){
					$colspan = '11';
					}else{
					$colspan = '8';
				}
			}
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr style="display:none;" class="show_in_print">';
			$html .= '<th colspan="8"><b>'.$company_detail->company_name.'</b></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;" class="show_in_print">';
			$html .= '<th colspan="8"><b>'.$company_detail->address.'</b></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;" class="show_in_print">';
			$html .= '<th colspan="8"><b>Report Date : </b> '.$this->input->post('from_date').' To '.$this->input->post('to_date').'</th>';
			$html .= '</tr>';
			if($AccountID ==''){
				$html .= '<tr style="display:none;" class="show_in_print">';
				$html .= '<th colspan="8"><b> LocationType: </b> '.$locTypeName.'</th>';
				$html .= '</tr>';
			}
			
			$html .= '<tr style="display:none;" class="show_in_print">';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '<th colspan="8"><b>Report Type : </b> '.$repTypeName.'</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			if($AccountID !==''){
				$html .= '<th class="sortable" align="center">Sr.No</th>';
				$html .= '<th class="sortable" align="center">ItemID</th>';
				$html .= '<th class="sortable" align="center">ItemName</th>';
				$html .= '<th class="sortable" align="left">CasePack</th>';
				$html .= '<th class="sortable" align="left">SalesIn(Unit)</th>';
				$html .= '<th class="sortable" align="center">Sales Amt</th>';
				$html .= '<th class="sortable" align="center">Fresh Return(Unit)</th>';
				$html .= '<th class="sortable" align="center">Fresh Return Amt</th>';
				$html .= '<th class="sortable" align="center">Damage Return(Unit)</th>';
				$html .= '<th class="sortable" align="center">Damage Amt</th>';
				$html .= '<th class="sortable" align="center">Fresh Return(%)</th>';
				$html .= '<th class="sortable" align="center">Damage Return(%)</th>';
				$html .= '<th class="sortable" align="center">Total Return(%)</th>';
				
				}else{
				if($repType == '2'){
					$html .= '<th class="sortable" align="center">Sr.No</th>';
					$html .= '<th class="sortable" align="center">ItemID</th>';
					$html .= '<th class="sortable" align="center">Item Name</th>';
					$html .= '<th class="sortable" align="left">CasePack</th>';
					$html .= '<th class="sortable" align="left">SalesIn(Unit)</th>';
					$html .= '<th class="sortable" align="center">Sales Amt</th>';
					$html .= '<th class="sortable" align="center">Fresh Return(Unit)</th>';
					$html .= '<th class="sortable" align="center">Fresh Return Amt</th>';
					$html .= '<th class="sortable" align="center">Damage Return(Unit)</th>';
					$html .= '<th class="sortable" align="center">Damage Amt</th>';
					$html .= '<th class="sortable" align="center">Fresh Return(%)</th>';
					$html .= '<th class="sortable" align="center">Damage Return(%)</th>';
					$html .= '<th class="sortable" align="center">Total Return(%)</th>';
					}else{
					$html .= '<th class="sortable" align="center">Sr.No</th>';
					$html .= '<th class="sortable" align="center">AccountID</th>';
					$html .= '<th class="sortable" align="center">Account Name</th>';
					$html .= '<th class="sortable" align="left">Station Name</th>';
					$html .= '<th class="sortable" align="left">Sale Amt</th>';
					$html .= '<th class="sortable" align="center">Fresh Return Amt</th>';
					$html .= '<th class="sortable" align="center">Damage Amt</th>';
					$html .= '<th class="sortable" align="center">Fresh Return(%)</th>';
					$html .= '<th class="sortable" align="center">Damage Return(%)</th>';
					$html .= '<th class="sortable" align="center">Total Return(%)</th>';
				}
			}
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			$BilledInCsCrSum = 0;
			$saleAmtSum = 0;
			$FBilledQtySum = 0;
			$frsRtnAmtSum = 0;
			$DBilledQtySum = 0;
			$DRtnAmtSum = 0;
			foreach ($body_Rowdata as $key => $value) {
				$frsRtnAmt = '';
				$FBilledQty = '';
				$DRtnAmt = '';
				$DBilledQty = '';
				$saleAmt = '';
				$saleCSCR = '';
				$AccountName = '';
				$station = '';
				$CaseQty = '';
				$ItemName = '';
				$BilledQty = '';
				
				foreach ($body_data as $key1 => $value1) {
					if($AccountID !==''){
						if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Fresh'){
							$frsRtnAmt = $value1["NetChallanAmt"];
							$CaseQty = $value1["CaseQty"];
							$ItemName = $value1["description"];
							$FBilledQty = $value1["BilledQty"];
							$FBilledQtySum += $FBilledQty;
							$frsRtnAmtSum += round($frsRtnAmt);
						}
						if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
							$DRtnAmt = $value1["NetChallanAmt"];
							$CaseQty = $value1["CaseQty"];
							$ItemName = $value1["description"];
							$DBilledQty = $value1["BilledQty"];
							$DBilledQtySum += $DBilledQty;
							$DRtnAmtSum += round($DRtnAmt);
						}
						if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
							$saleAmt = $value1["NetChallanAmt"];
							$saleAmtSum += round($saleAmt);
							$CaseQty = $value1["CaseQty"];
							$ItemName = $value1["description"];
							$SBilledQty = $value1["BilledQty"];
						}
						}else{
						if($repType == '2'){
							if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Fresh'){
								$frsRtnAmt = $value1["NetChallanAmt"];
								$ItemName = $value1["description"];
								$FBilledQty = $value1["BilledQty"];
								$FBilledQtySum += $FBilledQty;
								$frsRtnAmtSum += round($frsRtnAmt);
							}
							if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
								$DRtnAmt = $value1["NetChallanAmt"];
								$CaseQty = $value1["CaseQty"];
								$ItemName = $value1["description"];
								$DBilledQty = $value1["BilledQty"];
								$DBilledQtySum += $DBilledQty;
								$DRtnAmtSum += round($DRtnAmt);
							}
							if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
								$saleAmt = $value1["NetChallanAmt"];
								$saleAmtSum += round($saleAmt);
								$CaseQty = $value1["CaseQty"];
								$ItemName = $value1["description"];
								$SBilledQty = $value1["BilledQty"];
							}
							}else{
							if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Fresh'){
								$frsRtnAmt = $value1["NetChallanAmt"];
								$AccountName = $value1["company"];
								$station = $value1["StationName"];
								$FBilledQty = $value1["BilledQty"];
								$FBilledQtySum += $FBilledQty;
								$frsRtnAmtSum += round($frsRtnAmt);
							}
							if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Damage'){
								$DRtnAmt = $value1["NetChallanAmt"];
								$AccountName = $value1["company"];
								$station = $value1["StationName"];
								$DBilledQty = $value1["BilledQty"];
								$DBilledQtySum += $DBilledQty;
								$DRtnAmtSum += round($DRtnAmt);
							}
							if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Order'){
								$saleAmt = $value1["NetChallanAmt"];
								$saleAmtSum += round($saleAmt);
								$AccountName = $value1["company"];
								$station = $value1["StationName"];
								$SBilledQty = $value1["BilledQty"];
							}
						}
					}
				}
				if(($saleAmt == '' || $saleAmt == '0.00') && ($frsRtnAmt == '' || $frsRtnAmt == '0.00') && ($DRtnAmt == '' || $DRtnAmt == '0.00')){
					
					}else{
					$DmgRtnPer = "";
					$FrsRtnPer = "";
					if($saleAmt>0){
						if($frsRtnAmt > 0){
							$FrsRtnPer = ($frsRtnAmt/$saleAmt) * 100;
						}
						if($DRtnAmt > 0){
							$DmgRtnPer = ($DRtnAmt/$saleAmt) * 100;
						}
					}
					$html .= '<tr>';
					if($AccountID !==''){
						$html .= '<td align="center">'.$i.'</td>';
						$html .= '<td align="center">'.$value["ItemID"].'</td>';
						$html .= '<td align="left">'.$ItemName.'</td>';
						$html .= '<td align="center">'.$CaseQty.'</td>';
						// $BilledInCsCr = $SBilledQty/$CaseQty;
						$BilledInCsCr = $SBilledQty;
						$BilledInCsCrSum += $BilledInCsCr;
						$html .= '<td align="center">'. number_format($BilledInCsCr, 2, ".", "").'</td>';
						$html .= '<td align="center">'.number_format(round($saleAmt), 2, ".", "").'</td>';
						$html .= '<td align="center">'.$FBilledQty.'</td>';
						$html .= '<td align="center">'.number_format(round($frsRtnAmt), 2, ".", "").'</td>';
						$html .= '<td align="center">'.$DBilledQty.'</td>';
						$html .= '<td align="center">'.number_format(round($DRtnAmt), 2, ".", "").'</td>';
						
						
						$html .= '<td align="center">'.number_format($FrsRtnPer, 2, ".", "").'</td>';
						$html .= '<td align="center">'.number_format($DmgRtnPer, 2, ".", "").'</td>';
						$html .= '<td align="center">'.number_format(($DmgRtnPer+$FrsRtnPer), 2, ".", "").'</td>';
						}else{
						if($repType == '2'){
							$html .= '<td align="center">'.$i.'</td>';
							$html .= '<td align="center">'.$value["ItemID"].'</td>';
							$html .= '<td align="left">'.$ItemName.'</td>';
							$html .= '<td align="center">'.$CaseQty.'</td>';
							// $BilledInCsCr = $SBilledQty/$CaseQty;
							$BilledInCsCr = $SBilledQty;
							$BilledInCsCrSum += $BilledInCsCr;
							$html .= '<td align="center">'.number_format($BilledInCsCr, 2, ".", "").'</td>';
							$html .= '<td align="center">'.number_format(round($saleAmt), 2, ".", "").'</td>';
							$html .= '<td align="center">'.$FBilledQty.'</td>';
							$html .= '<td align="center">'.number_format(round($frsRtnAmt), 2, ".", "").'</td>';
							$html .= '<td align="center">'.$DBilledQty.'</td>';
							$html .= '<td align="center">'.number_format(round($DRtnAmt), 2, ".", "").'</td>';
							
							$html .= '<td align="center">'.number_format($FrsRtnPer, 2, ".", "").'</td>';
							$html .= '<td align="center">'.number_format($DmgRtnPer, 2, ".", "").'</td>';
							$html .= '<td align="center">'.number_format(($DmgRtnPer+$FrsRtnPer), 2, ".", "").'</td>';
							}else{
							$html .= '<td align="center">'.$i.'</td>';
							$html .= '<td align="center">'.$value["AccountID"].'</td>';
							$html .= '<td align="left">'.$AccountName.'</td>';
							$html .= '<td align="center">'.$station.'</td>';
							$html .= '<td align="center">'. number_format(round($saleAmt), 2, ".", "").'</td>';
							$html .= '<td align="center">'. number_format(round($frsRtnAmt), 2, ".", "").'</td>';
							$html .= '<td align="center">'.number_format(round($DRtnAmt), 2, ".", "").'</td>';
							
							$html .= '<td align="center">'.number_format($FrsRtnPer, 2, ".", "").'</td>';
							$html .= '<td align="center">'.number_format($DmgRtnPer, 2, ".", "").'</td>';
							$html .= '<td align="center">'.number_format(($DmgRtnPer+$FrsRtnPer), 2, ".", "").'</td>';
						}
					}
					
					$i++;
					$html .= '</tr>';
				}
			}
			
			$DmgRtnPer = "";
			$FrsRtnPer = "";
			if($saleAmtSum>0){
				if($frsRtnAmtSum > 0){
					$FrsRtnPer = ($frsRtnAmtSum/$saleAmtSum) * 100;
				}
				if($DRtnAmtSum > 0){
					$DmgRtnPer = ($DRtnAmtSum/$saleAmtSum) * 100;
				}
			}
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			// Footer Data
			$html .= '<tr>';
			if($AccountID !==''){
				$html .= '<td align="center"></td>';
				$html .= '<td align="center">Total</td>';
				$html .= '<td align="center"></td>';
				$html .= '<td align="center"></td>';
				$html .= '<td align="center">'. number_format($BilledInCsCrSum, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format($saleAmtSum, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format($FBilledQtySum, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format($frsRtnAmtSum, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format($DBilledQtySum, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format($DRtnAmtSum, 2, ".", "").'</td>';
				
				$html .= '<td align="center">'.number_format($FrsRtnPer, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format($DmgRtnPer, 2, ".", "").'</td>';
				$html .= '<td align="center">'.number_format(($DmgRtnPer+$FrsRtnPer), 2, ".", "").'</td>';
				}else{
				if($repType == '2'){
					$html .= '<td align="center"></td>';
					$html .= '<td align="center">Total</td>';
					$html .= '<td align="center"></td>';
					$html .= '<td align="center"></td>';
					$html .= '<td align="center">'. number_format($BilledInCsCrSum, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($saleAmtSum, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($FBilledQtySum, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($frsRtnAmtSum, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($DBilledQtySum, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($DRtnAmtSum, 2, ".", "").'</td>';
					
					$html .= '<td align="center">'.number_format($FrsRtnPer, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($DmgRtnPer, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format(($DmgRtnPer+$FrsRtnPer), 2, ".", "").'</td>';
					}else{
					$html .= '<td align="center"></td>';
					$html .= '<td align="center">Total</td>';
					$html .= '<td align="center"></td>';
					$html .= '<td align="center"></td>';
					$html .= '<td align="center">'. number_format($saleAmtSum, 2, ".", "").'</td>';
					$html .= '<td align="center">'. number_format($frsRtnAmtSum, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($DRtnAmtSum, 2, ".", "").'</td>';
					
					$html .= '<td align="center">'.number_format($FrsRtnPer, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format($DmgRtnPer, 2, ".", "").'</td>';
					$html .= '<td align="center">'.number_format(($DmgRtnPer+$FrsRtnPer), 2, ".", "").'</td>';
				}
			}
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
			
		}
		//======================= Export Sale Vs Sale Return ===========================	
		public function ExportSaleVsSaleRtnReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'AccountID'  => $this->input->post('AccountID'),
				'AccountName'  => $this->input->post('AccountName'),
				'AccountAddress'  => $this->input->post('AccountAddress'),
				'locType'  => $this->input->post('locType'),
				'repType'  => $this->input->post('repType'),
				'Subgroup2'  => $this->input->post('Subgroup2'),
				);
				$AccountID = $filterdata["AccountID"];
				$AccountName = $filterdata["AccountName"];
				$AccountAddress = $filterdata["AccountAddress"];
				$locType = $filterdata["locType"];
				$repType = $filterdata["repType"];
				if($locType == '1'){
					$locTypeName = 'Local';
					}else if($locType == '2'){
					$locTypeName = 'Outstation';
					}elseif($locType == '3'){
					$locTypeName = 'notDefine';
					}else{
					$locTypeName = "All";
				}
				if($repType == '1'){
					$repTypeName = 'AccountWiseDetails';
					}else{
					$repTypeName = 'ItemWiseDetails';
				}
				
				
				$body_Rowdata = $this->sale_reports_model->GetSaleVsSaleRtnBodyRowData($filterdata);
				$body_data = $this->sale_reports_model->GetSaleVsSaleRtnBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				
				if($AccountID !==''){
					$AccountDetails = 'Account Name : '.$AccountName.' Address : '.$AccountAddress;
					$colspan = '12';
					}else{
					if($repType == '2'){
						$colspan = '12';
						}else{
						$colspan = '9';
					}
				} 
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = "LocationType : ".$locTypeName;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				$msg3 = "Report Type : ".$repTypeName;
				$filter3 = array($msg3);
				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter3);
				if($AccountID !==''){
					
					$filter5 = array($AccountDetails);
					$writer->markMergedCell('Sheet1', $start_row = 5, $start_col = 0, $end_row = 5, $end_col = $colspan);  //merge cells
					$writer->writeSheetRow('Sheet1', $filter5);
				}
				$set_col_tk = [];
				if($AccountID !==''){
					$set_col_tk["Sr.No."] = 'Sr.No.';
					$set_col_tk["ItemID"] = 'ItemID';
					$set_col_tk["ItemName"] = 'Item Name';
					$set_col_tk["CasePack"] = 'CasePack';
					$set_col_tk["SalesInCSCr"] = 'SalesIn(Unit)';
					$set_col_tk["SalesAmt"] = 'Sales Amt';
					$set_col_tk["FreshRtninUnit"] = 'Fresh Return(Unit)';
					$set_col_tk["FreshRtnAmt"] = 'Fresh Return Amt';
					$set_col_tk["DamageinUnit"] = 'Damage Return(Unit)';
					$set_col_tk["DamageAmt"] = 'Damage Amt';
					$set_col_tk["Fresh Return(%)"] = 'Fresh Return(%)';
					$set_col_tk["Damage Return(%)"] = 'Damage Return(%)';
					}else{
					if($repType == '2'){
						$set_col_tk["Sr.No."] = 'Sr.No.';
						$set_col_tk["ItemID"] = 'ItemID';
						$set_col_tk["ItemName"] = 'Item Name';
						$set_col_tk["CasePack"] = 'CasePack';
						$set_col_tk["SalesInCSCr"] = 'SalesIn(Unit)';
						$set_col_tk["SalesAmt"] = 'SalesAmt';
						$set_col_tk["FreshRtninUnit"] = 'Fresh Return(Unit)';
						$set_col_tk["FreshRtnAmt"] = 'Fresh Return Amt';
						$set_col_tk["DamageinUnit"] = 'Damage Return(Unit)';
						$set_col_tk["DamageAmt"] = 'Damage Return Amt';
						$set_col_tk["Fresh Return(%)"] = 'Fresh Return(%)';
						$set_col_tk["Damage Return(%)"] = 'Damage Return(%)';
						}else{
						$set_col_tk["Sr.No."] = 'Sr.No.';
						$set_col_tk["AccountID"] = 'AccountID';
						$set_col_tk["AccountName"] = 'Account Name';
						$set_col_tk["StationName"] = 'Station Name';
						$set_col_tk["SaleAmt"] = 'Sale Amt';
						$set_col_tk["FreshRtnAmt"] = 'Fresh Return Amt';
						$set_col_tk["DamageAmt"] = 'Damage Return Amt';
						$set_col_tk["Fresh Return(%)"] = 'Fresh Return(%)';
						$set_col_tk["Damage Return(%)"] = 'Damage Return(%)';
					}
				}
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 1;
				$BilledInCsCrSum = 0;
				$saleAmtSum = 0;
				$FBilledQtySum = 0;
				$frsRtnAmtSum = 0;
				$DBilledQtySum = 0;
				$DRtnAmtSum = 0;
				foreach ($body_Rowdata as $key => $value) {
					$frsRtnAmt = 0;
					$FBilledQty = 0;
					$DRtnAmt = 0;
					$DBilledQty = 0;
					$saleAmt = 0;
					$saleCSCR = 0;
					$AccountName = 0;
					$station = 0;
					$CaseQty = 0;
					$ItemName = 0;
					$BilledQty = 0;
					
					foreach ($body_data as $key1 => $value1) {
						if($AccountID !==''){
							if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Fresh'){
								$frsRtnAmt = $value1["NetChallanAmt"];
								$CaseQty = $value1["CaseQty"];
								$ItemName = $value1["description"];
								$FBilledQty = $value1["BilledQty"];
								$FBilledQtySum += $FBilledQty;
								$frsRtnAmtSum += round($frsRtnAmt);
							}
							if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
								$DRtnAmt = $value1["NetChallanAmt"];
								$CaseQty = $value1["CaseQty"];
								$ItemName = $value1["description"];
								$DBilledQty = $value1["BilledQty"];
								$DBilledQtySum += $DBilledQty;
								$DRtnAmtSum += round($DRtnAmt);
							}
							if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
								$saleAmt = $value1["NetChallanAmt"];
								$saleAmtSum += round($saleAmt);
								$CaseQty = $value1["CaseQty"];
								$ItemName = $value1["description"];
								$SBilledQty = $value1["BilledQty"];
							}
							}else{
							if($repType == '2'){
								if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Fresh'){
									$frsRtnAmt = $value1["NetChallanAmt"];
									$ItemName = $value1["description"];
									$FBilledQty = $value1["BilledQty"];
									$FBilledQtySum += $FBilledQty;
									$frsRtnAmtSum += round($frsRtnAmt);
								}
								if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
									$DRtnAmt = $value1["NetChallanAmt"];
									$CaseQty = $value1["CaseQty"];
									$ItemName = $value1["description"];
									$DBilledQty = $value1["BilledQty"];
									$DBilledQtySum += $DBilledQty;
									$DRtnAmtSum += round($DRtnAmt);
								}
								if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
									$saleAmt = $value1["NetChallanAmt"];
									$saleAmtSum += round($saleAmt);
									$CaseQty = $value1["CaseQty"];
									$ItemName = $value1["description"];
									$SBilledQty = $value1["BilledQty"];
								}
								}else{
								if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Fresh'){
									$frsRtnAmt = $value1["NetChallanAmt"];
									$AccountName = $value1["company"];
									$station = $value1["StationName"];
									$FBilledQty = $value1["BilledQty"];
									$FBilledQtySum += $FBilledQty;
									$frsRtnAmtSum += round($frsRtnAmt);
								}
								if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Damage'){
									$DRtnAmt = $value1["NetChallanAmt"];
									$AccountName = $value1["company"];
									$station = $value1["StationName"];
									$DBilledQty = $value1["BilledQty"];
									$DBilledQtySum += $DBilledQty;
									$DRtnAmtSum += round($DRtnAmt);
								}
								if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Order'){
									$saleAmt = $value1["NetChallanAmt"];
									$saleAmtSum += round($saleAmt);
									$AccountName = $value1["company"];
									$station = $value1["StationName"];
									$SBilledQty = $value1["BilledQty"];
								}
							}
						}
					}
					
					if(($saleAmt == '0' || $saleAmt == '0.00') && ($frsRtnAmt == '0' || $frsRtnAmt == '0.00') && ($DRtnAmt == '0' || $DRtnAmt == '0.00')){
						
						}else{
						$list_add = [];
						
						if($FBilledQty == '0' || $FBilledQty == '0.00'){
							$FBilledQty = '';
						}
						if($frsRtnAmt == '0' || $frsRtnAmt == '0.00'){
							$frsRtnAmt = '';
						}
						if($DBilledQty == '0' || $DBilledQty == '0.00'){
							$DBilledQty = '';
						}
						if($DRtnAmt == '0' || $DRtnAmt == '0.00'){
							$DRtnAmt = '';
						}
						
						$DmgRtnPer = "";
						$FrsRtnPer = "";
						if($saleAmt>0){
							if($frsRtnAmt > 0){
								$FrsRtnPer = ($frsRtnAmt/$saleAmt) * 100;
							}
							if($DRtnAmt > 0){
								$DmgRtnPer = ($DRtnAmt/$saleAmt) * 100;
							}
						}
						
						if($AccountID !==''){
							$list_add[] = $i;
							$list_add[] = $value["ItemID"];
							$list_add[] = $ItemName;
							$list_add[] = $CaseQty;
							// $BilledInCsCr = $SBilledQty/$CaseQty;
							$BilledInCsCr = $SBilledQty;
							$BilledInCsCrSum += $BilledInCsCr;
							$list_add[] = $BilledInCsCr;
							$list_add[] = number_format($saleAmt, 2, ".", "");
							$list_add[] = $FBilledQty;
							$list_add[] = number_format($frsRtnAmt, 2, ".", "");
							$list_add[] = $DBilledQty;
							$list_add[] = number_format($DRtnAmt, 2, ".", "");
							
							$list_add[] = number_format($FrsRtnPer, 2, ".", "");
							$list_add[] = number_format($DmgRtnPer, 2, ".", "");
							
							}else{
							if($repType == '2'){
								$list_add[] = $i;
								$list_add[] = $value["ItemID"];
								$list_add[] = $ItemName;
								$list_add[] = $CaseQty;
								// $BilledInCsCr = $SBilledQty/$CaseQty;
								$BilledInCsCr = $SBilledQty;
								$BilledInCsCrSum += $BilledInCsCr;
								$list_add[] = $BilledInCsCr;
								$list_add[] = number_format($saleAmt, 2, ".", "");
								$list_add[] = $FBilledQty;
								$list_add[] = number_format($frsRtnAmt, 2, ".", "");
								$list_add[] = $DBilledQty;
								$list_add[] = number_format($DRtnAmt, 2, ".", "");
								$list_add[] = number_format($FrsRtnPer, 2, ".", "");
								$list_add[] = number_format($DmgRtnPer, 2, ".", "");
								}else{
								$list_add[] = $i;
								$list_add[] = $value["AccountID"];
								$list_add[] = $AccountName;
								$list_add[] = $station;
								$list_add[] = number_format($saleAmt, 2, ".", "");
								$list_add[] = number_format($frsRtnAmt, 2, ".", "");
								$list_add[] = number_format($DRtnAmt, 2, ".", "");
								$list_add[] = number_format($FrsRtnPer, 2, ".", "");
								$list_add[] = number_format($DmgRtnPer, 2, ".", "");
							}
						}
						$i++;
						$writer->writeSheetRow('Sheet1', $list_add);
					}
				}
				
				// Footer Data
				$list_add = [];
				$DmgRtnPer = "";
				$FrsRtnPer = "";
				if($saleAmtSum>0){
					if($frsRtnAmtSum > 0){
						$FrsRtnPer = ($frsRtnAmtSum/$saleAmtSum) * 100;
					}
					if($DRtnAmtSum > 0){
						$DmgRtnPer = ($DRtnAmtSum/$saleAmtSum) * 100;
					}
				}
				if($AccountID !==''){
					$list_add[] = '';
					$list_add[] = '';
					$list_add[] = 'Total';
					$list_add[] = '';
					$list_add[] = number_format($BilledInCsCrSum, 2, ".", "");
					$list_add[] = number_format($saleAmtSum, 2, ".", "");
					$list_add[] = number_format($FBilledQtySum, 2, ".", "");
					$list_add[] = number_format($frsRtnAmtSum, 2, ".", "");
					$list_add[] = number_format($DBilledQtySum, 2, ".", "");
					$list_add[] = number_format($DRtnAmtSum, 2, ".", "");
					$list_add[] = number_format($FrsRtnPer, 2, ".", "");
					$list_add[] = number_format($DmgRtnPer, 2, ".", "");
					}else{
					if($repType == '2'){
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = 'Total';
						$list_add[] = '';
						$list_add[] = number_format($BilledInCsCrSum, 2, ".", "");
						$list_add[] = number_format($saleAmtSum, 2, ".", "");
						$list_add[] = number_format($FBilledQtySum, 2, ".", "");
						$list_add[] = number_format($frsRtnAmtSum, 2, ".", "");
						$list_add[] = number_format($DBilledQtySum, 2, ".", "");
						$list_add[] = number_format($DRtnAmtSum, 2, ".", "");
						$list_add[] = number_format($FrsRtnPer, 2, ".", "");
						$list_add[] = number_format($DmgRtnPer, 2, ".", "");
						}else{
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = 'Total';
						$list_add[] = '';
						$list_add[] = number_format($saleAmtSum, 2, ".", "");
						$list_add[] = number_format($frsRtnAmtSum, 2, ".", "");
						$list_add[] = number_format($DRtnAmtSum, 2, ".", "");
						$list_add[] = number_format($FrsRtnPer, 2, ".", "");
						$list_add[] = number_format($DmgRtnPer, 2, ".", "");
					}
				}
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'SaleVsSaleRtn.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		//======================== Item Group 2 Wise Chart Report ======================
		public function ItemGroupChartReport()
		{
			if (!has_permission_new('ItemGroupChartReport', '', 'view')) {
				access_denied('access denied');
			}
			$data['title'] = "Item Group Chart Based Report";
			$ItemMainGrpID = "1";
			$data['ItemGroupList'] = $this->sale_reports_model->GetItemGroupList($ItemMainGrpID);
			$data['DistributorTypeList'] = $this->sale_reports_model->GetDistributorTypeList();
			$data['CustomerList'] = $this->sale_reports_model->GetCustomerList();
			$data['PartySalePerson'] = $this->sale_reports_model->GetPartySalePerson();
			$data['RouteList'] = $this->sale_reports_model->GetRouteList();
			$data['StateList'] = $this->sale_reports_model->GetStateList();
			$data['company_detail']    = $this->sale_reports_model->get_company_detail();
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$this->load->view('admin/sale_reports/ItemGroupWiseChartReport', $data);
		}
		//============================== Get Item Group Wise Chart Report ==============
		public function GetItemGroupWiseChartReport()
		{
			$Input = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'ItemGroup'  => $this->input->post('ItemGroup'),
			'DistributorType'  => $this->input->post('DistributorType'),
			'state'  => $this->input->post('state'),
			'city'  => $this->input->post('city'),
			'PartyName'  => $this->input->post('PartyName'),
			'SalesPerson'  => $this->input->post('SalesPerson'),
			'Route'  => $this->input->post('Route'),
			'ChartType'  => $this->input->post('ChartType'),
			'ReportIn'  => $this->input->post('ReportIn')
			);
			$data = $this->sale_reports_model->GetItemGroupWiseChartReport($Input);
			$data2 = $this->sale_reports_model->GetItemGroupWiseTableChartReport($Input);
			// echo $this->db->last_query();die;
			$from_date = to_sql_date($this->input->post('from_date'));
			$to_date = to_sql_date($this->input->post('to_date'));
			$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
			$period = new DatePeriod(
			new DateTime($from_date),
			new DateInterval('P1D'),
			new DateTime($to_date_new)
			);
			$from_date = to_sql_date($this->input->post('from_date'));
			$TotalSaleAmt = 0;
			$TotalBilledQty = 0;
			$chartData = [];
			if(count($data) >0){
				foreach ($data as $value) {
					$TotalSaleAmt += $value['NetSaleAmt'];
					$TotalBilledQty += $value['TotalBilledQty'];
				}
				foreach($data as $value)
				{	
					if($this->input->post('ReportIn') == "1"){
						// Sale AmtPercentage
						$Amt = ($value['NetSaleAmt'] / $TotalSaleAmt) * 100;
						$Values = number_format($Amt, 2, '.', '');
						}elseif($this->input->post('ReportIn') == "2"){
						// Sale Amt 
						$Values = $value['NetSaleAmt'];
						}elseif($this->input->post('ReportIn') == "4"){
						// Sale AmtPercentage
						$Amt = ($value['TotalBilledQty'] / $TotalBilledQty) * 100;
						$Values = number_format($Amt, 2, '.', '');
						}elseif($this->input->post('ReportIn') == "3"){
						// Sale Amt 
						$Values = $value['TotalBilledQty'];
					}
					// Prepare data for the pie chart
					$chartData[] = [
					'label' => $value['ItemGroupName'],
					'Value' => $Values
					];
				}
			}
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th align="center">Sr.No</th>';
			$html .= '<th align="center">Date</th>';
			foreach($data as $value)
			{
				$html .= '<th align="center">'.$value["ItemGroupName"].'</th>';
			}
			
			$html .= '<th align="center">Total</th>';
			$html .= '<tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			foreach ($period as $key => $value3)
			{
				$date = $value3->format('d/m/Y'); 
				$html .= '<tr>';
				$html .= '<td align="center">'.$i.'</td>';
				$html .= '<td align="center">'.$date.'</td>';
				
				
				
				$totalright = 0;
				foreach($data as $value)
				{
					$Values=0;
					foreach($data2 as $value2)
					{
						if(_d(substr($value2['Transdate'],0,10)) == $date && $value['SubGrpID1'] == $value2['SubGrpID1']){
							if($this->input->post('ReportIn') == "1"){
								// Sale AmtPercentage
								$Amt = ($value2['NetSaleAmt'] / $TotalSaleAmt) * 100;
								$Values += number_format($Amt, 2, '.', '');
								}elseif($this->input->post('ReportIn') == "2"){
								// Sale Amt 
								$Values += $value2['NetSaleAmt'];
								}elseif($this->input->post('ReportIn') == "4"){
								// Sale AmtPercentage
								$Amt = ($value2['TotalBilledQty'] / $TotalBilledQty) * 100;
								$Values += number_format($Amt, 2, '.', '');
								}elseif($this->input->post('ReportIn') == "3"){
								// Sale Amt 
								$Values += $value2['TotalBilledQty'];
							}	
						}
					}
					if($Values == 0){
						$html .= '<td align="center"></td>';
						}else{
						$html .= '<td align="center">'.$Values.'</td>';
					}
					$totalright += $Values;
					
				}
				$html .= '<td align="center"><b>'.$totalright.'</b></td>';
				
				$html .= '<tr>';
				$i++;
			}
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			$html .= '<tr>';
			$html .= '<td align="center" colspan="2"><b>Total</b></td>';
			$totalright = 0;
			foreach($data as $value)
			{
				$Values=0;
				
				foreach($data2 as $value2)
				{
					if($value['SubGrpID1'] == $value2['SubGrpID1']){
						if($this->input->post('ReportIn') == "1"){
							// Sale AmtPercentage
							$Amt = ($value2['NetSaleAmt'] / $TotalSaleAmt) * 100;
							$Values += number_format($Amt, 2, '.', '');
							}elseif($this->input->post('ReportIn') == "2"){
							// Sale Amt 
							$Values += $value2['NetSaleAmt'];
							}elseif($this->input->post('ReportIn') == "4"){
							// Sale AmtPercentage
							$Amt = ($value2['TotalBilledQty'] / $TotalBilledQty) * 100;
							$Values += number_format($Amt, 2, '.', '');
							}elseif($this->input->post('ReportIn') == "3"){
							// Sale Amt 
							$Values += $value2['TotalBilledQty'];
						}	
					}
				}
				$html .= '<td align="center"><b>'.$Values.'</b></td>';
				$totalright += $Values;
			}
			$html .= '<td align="center"><b>'.$totalright.'</b></td>';
			$html .= '<tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode(['chartData' => $chartData,'HTML'=>$html]);
		}
		
		//=========================== Daily Sale Summary Export ========================	
		public function ExportItemGroupWiseChartReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 11);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 11);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = 'Item Group Wise Sales Report : '.$this->input->post('FilterVal');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 11);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$Input = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'ItemGroup'  => $this->input->post('ItemGroup'),
				'DistributorType'  => $this->input->post('DistributorType'),
				'state'  => $this->input->post('state'),
				'city'  => $this->input->post('city'),
				'PartyName'  => $this->input->post('PartyName'),
				'SalesPerson'  => $this->input->post('SalesPerson'),
				'Route'  => $this->input->post('Route'),
				'ChartType'  => $this->input->post('ChartType'),
				'ReportIn'  => $this->input->post('ReportIn')
				);
				$data = $this->sale_reports_model->GetItemGroupWiseChartReport($Input);
				$data2 = $this->sale_reports_model->GetItemGroupWiseTableChartReport($Input);
				// echo $this->db->last_query();die;
				$from_date = to_sql_date($this->input->post('from_date'));
				$to_date = to_sql_date($this->input->post('to_date'));
				$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
				$period = new DatePeriod(
				new DateTime($from_date),
				new DateInterval('P1D'),
				new DateTime($to_date_new)
				);
				$from_date = to_sql_date($this->input->post('from_date'));
				
				$set_col_tk = [];
				$set_col_tk["Sr.No."] = 'Sr. No.';
				$set_col_tk["Date"] = 'Date';
				foreach($data as $value)
				{
					$set_col_tk[$value["ItemGroupName"]] = $value["ItemGroupName"];
				}
				$set_col_tk["Total"] = 'Total';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				
				$i = 1;
				foreach ($period as $key => $value3)
				{
					$date = $value3->format('d/m/Y'); 
					$list_add = [];
					$list_add[] = $i;
					$list_add[] = $date;
					
					
					$totalright = 0;
					foreach($data as $value)
					{
						$Values=0;
						foreach($data2 as $value2)
						{
							if(_d(substr($value2['Transdate'],0,10)) == $date && $value['SubGrpID1'] == $value2['SubGrpID1']){
								if($this->input->post('ReportIn') == "1"){
									// Sale AmtPercentage
									$Amt = ($value2['NetSaleAmt'] / $TotalSaleAmt) * 100;
									$Values += number_format($Amt, 2, '.', '');
									}elseif($this->input->post('ReportIn') == "2"){
									// Sale Amt 
									$Values += $value2['NetSaleAmt'];
									}elseif($this->input->post('ReportIn') == "4"){
									// Sale AmtPercentage
									$Amt = ($value2['TotalBilledQty'] / $TotalBilledQty) * 100;
									$Values += number_format($Amt, 2, '.', '');
									}elseif($this->input->post('ReportIn') == "3"){
									// Sale Amt 
									$Values += $value2['TotalBilledQty'];
								}	
							}
						}
						if($Values == 0){
							$list_add[] = '';
							}else{
							
							$list_add[] = $Values;
						}
						$totalright += $Values;
						
					}
					$list_add[] = $totalright;
					$writer->writeSheetRow('Sheet1', $list_add);
					
					$i++;
				}
				
				
				$list_add = [];
				$list_add[] = 'Total';
				$list_add[] = '';
				$totalright = 0;
				foreach($data as $value)
				{
					$Values=0;
					
					foreach($data2 as $value2)
					{
						if($value['SubGrpID1'] == $value2['SubGrpID1']){
							if($this->input->post('ReportIn') == "1"){
								// Sale AmtPercentage
								$Amt = ($value2['NetSaleAmt'] / $TotalSaleAmt) * 100;
								$Values += number_format($Amt, 2, '.', '');
								}elseif($this->input->post('ReportIn') == "2"){
								// Sale Amt 
								$Values += $value2['NetSaleAmt'];
								}elseif($this->input->post('ReportIn') == "4"){
								// Sale AmtPercentage
								$Amt = ($value2['TotalBilledQty'] / $TotalBilledQty) * 100;
								$Values += number_format($Amt, 2, '.', '');
								}elseif($this->input->post('ReportIn') == "3"){
								// Sale Amt 
								$Values += $value2['TotalBilledQty'];
							}	
						}
					}
					$list_add[] = $Values;
					$totalright += $Values;
				}
				
				$list_add[] = $totalright;
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'ItemGroupWise_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		
		/* SaleRtn report page */
		public function SaleRtn()
		{
			if (!has_permission_new('SaleRtn', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "SaleRtn Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/SaleReturn', $data);
		}
		
		/* PartyItem Wise report page */
		public function PartyItemWiseReport()
		{
			if (!has_permission_new('PartyItemWiseReport', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "PartyItemWise Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/PartyItemWiseTransaction', $data);
		}
		
		/* Item Wise stock report page */
		public function ItemWiseStockReport()
		{
			if (!has_permission_new('ItemWiseStockReport', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "ItemWise Stock Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['GodownData'] = $this->sale_reports_model->GetGodownData();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/ItemWiseStockReport', $data);
		}
		
		// Get Account List
		public function AccountList(){
			$postData = $this->input->post();
			$data = $this->sale_reports_model->AccountList($postData);
			echo json_encode($data);
		}
		
		public function AccountDetails()
		{
			$AccountID = $this->input->post('AccountID');
			$account_data = $this->sale_reports_model->GetAccountDetails($AccountID);
			echo json_encode($account_data);
		}
		
		// Get Item List
		public function ItemList(){
			$postData = $this->input->post();
			$data = $this->sale_reports_model->ItemList($postData);
			echo json_encode($data);
		}
		
		public function ItemDetails()
		{
			$ItemID = $this->input->post('ItemID');
			$account_data = $this->sale_reports_model->GetItemDetails($ItemID);
			echo json_encode($account_data);
		}
		
		// Get Result for ItemWise Stock report
		public function GetItemWiseStockReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'ItemID'  => $this->input->post('ItemID'),
			'GodownID'  => $this->input->post('GodownID'),
			);
			$ItemID = $this->input->post('ItemID');
			$body_data = $this->sale_reports_model->GetItemWiseStockReport($filterdata);
			$StockOQtyData = $this->sale_reports_model->GetItemWiseStockReportOQty($filterdata);
			$RateData = $this->sale_reports_model->GetItemRate($ItemID);
			/*echo json_encode($body_data);
			die;*/
			$company_detail = $this->sale_reports_model->get_company_detail();
			$from_date = to_sql_date($this->input->post('from_date'));
			$to_date = to_sql_date($this->input->post('to_date'));
			$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
			$period = new DatePeriod(
			new DateTime($from_date),
			new DateInterval('P1D'),
			new DateTime($to_date_new)
			);
			$from_date = to_sql_date($this->input->post('from_date'));
			$PurchQtyCasesSumC = 0;
			$PurchRtnQtyCasesSumC = 0;
			$IssueQtyCasesSumC = 0;
			$PRDCasesSumC = 0;
			$SalesCasesSumC = 0;
			$SalesRtnCasesSumC = 0;
			$AdjCasesSumC = 0;
			$GTOCasesSumC = 0;
			$GTICasesSumC = 0;
			$OQty = 0;
			
			$Rate = 0;
			
			foreach ($body_data as $key => $value) {
				if($value["CaseQty"] == "0" || $value["CaseQty"] == ""){
					$CaseQty = 1;
					}else{
					$CaseQty = $value["CaseQty"];
				}
				$OQty = $value["OQty"];
				if($RateData){
					$Rate = $RateData->SaleRate;
					}else{
					$Rate = $value["SaleRate"];
				}
				if($value["TType"] == "P" && $value["TType2"] == "Purchase"){
					$PurchQtyCasesSumC += $value['Qty'];
				}
				
				if($value["TType"] == "N" && $value["TType2"] == "PurchaseReturn"){
					$PurchRtnQtyCasesSumC += $value['Qty'];
				}
				
				if($value["TType"] == "A" && $value["TType2"] == "Issue"){
					$IssueQtyCasesSumC += $value['Qty'];
				}
				
				if($value["TType"] == "B" && $value["TType2"] == "Production"){
					$PRDCasesSumC += $value['Qty'];
				}
				
				if($value["TType"] == "O" && $value["TType2"] == "Order"){
					$SalesCasesSumC += $value['Qty'];
				}
				
				if($value["TType"] == "R" && $value["TType2"] == "Fresh"){
					$SalesRtnCasesSumC += $value['Qty'];
				}
				
				if($value["TType"] == "X" && $value["TType2"] == "Free Distribution" ){
					$AdjCasesSumC += $value['Qty'];
				}
				if($value["TType"] == "X" && $value["TType2"] == "Free distribution" ){
					$AdjCasesSumC += $value['Qty'];
				}
				if($value["TType"] == "X" && $value["TType2"] == "Promotional Activity" ){
					$AdjCasesSumC += $value['Qty'];
				}
				if($value["TType"] == "X" && $value["TType2"] == "Stock Adjustment"){
					$AdjCasesSumC += $value['Qty'];
				}
				if($value["TType"] == "T" && $value["TType2"] == "Out"){
					$GTOCasesSumC += $value['Qty'];
				}
				if($value["TType"] == "T" && $value["TType2"] == "In"){
					$GTICasesSumC += $value['Qty'];
				}
			}
			
			$html = '';
			$html .= '<input type="hidden" name="comp_name" id="comp_name" value="'.$company_detail->company_name.'">';
			$html .= '<input type="hidden" name="comp_addr" id="comp_addr" value="'.$company_detail->address.'">';
			$html .= '<input type="hidden" name="CaseQty" id="CaseQty" value="'.$CaseQty.'">';
			$html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Date</th>';
			$html .= '<th class="sortable" align="center">OpenQty</th>';
			
			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
				$html .= '<th class="sortable" align="center">PurchQty</th>';
			}      
			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
				$html .= '<th class="sortable" align="center">PurchRtn</th>';
			} 
			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
				$html .= '<th class="sortable" align="center">IssueQty</th>';
			}    
			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
				$html .= '<th class="sortable" align="center">Production</th>';
			}     
			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
				$html .= '<th class="sortable" align="center">SalesQty</th>';
			}     
			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
				$html .= '<th class="sortable" align="center">SalesRtn</th>';
			}      
			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
				$html .= '<th class="sortable" align="center">AdjQty</th>';
			}
			if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
				$html .= '<th class="sortable" align="center">GTOQty</th>';
			}
			if($GTICasesSumC > 0 || $GTICasesSumC < 0){
				$html .= '<th class="sortable" align="center">GTIQty</th>';
			}
			
			$html .= '<th class="sortable" align="center">Bal.Qty</th>';
			$html .= '<th class="sortable" align="center">Rate</th>';
			$html .= '<th class="sortable" align="center">StkValue</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			
			if($from_date == '2022-04-01'){
				$OQTYCases = floatval($OQty) / floatval($CaseQty);
				}else{
				$OQtySum = 0;
				$OQtySum += floatval($OQty);
				foreach ($StockOQtyData as $keyOQty => $valueOQty) {
					
					if($valueOQty['TType'] == "P" && $valueOQty["TType2"] == "Purchase"){
						$OQtySum += $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "N" && $valueOQty["TType2"] == "PurchaseReturn"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "A" && $valueOQty["TType2"] == "Issue"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "B" && $valueOQty["TType2"] == "Production"){
						$OQtySum += $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "O" && $valueOQty['TType2'] == "Order"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "R" && $valueOQty["TType2"] == "Fresh"){
						$OQtySum += $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Free Distribution"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Free distribution"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Promotional Activity"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Stock Adjustment"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "T" && $valueOQty["TType2"] == "Out"){
						$OQtySum -= $valueOQty['Qty'];
					}
					if($valueOQty['TType'] == "T" && $valueOQty["TType2"] == "In"){
						$OQtySum += $valueOQty['Qty'];
					}
				}
				/*if($OQtySum == '0' || $OQtySum == '0.00'){
					$OQTYCases = 0;
				}else{*/
				$OQTYCases = floatval($OQtySum) / floatval($CaseQty);
				//}
			}
			
			foreach ($period as $key => $value) {
				$PurchQtyCasesSum = 0;
				$PurchRtnQtyCasesSum = 0;
				$IssueQtyCasesSum = 0;
				$PRDCasesSum = 0;
				$SalesCasesSum = 0;
				$SalesRtnCasesSum = 0;
				$AdjCasesSum = 0;
				$GTOCasesSum = 0;
				$GTICasesSum = 0;
				
				
				$date = $value->format('d/m/Y'); 
				$date2 = $value->format('Y-m-d');
				foreach ($body_data as $key1 => $value1) {
					if(substr($value1['TransDate2'],0,10) == $date2){
						
						
						$AmtSum = $value1["AmtSum"];
						if($value1["TType"] == "P" && $value1["TType2"] == "Purchase"){
							$PurchQtyCasesSum = $value1['Qty'] / $CaseQty;
						}
						
						if($value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){
							$PurchRtnQtyCasesSum = $value1['Qty'] / $CaseQty;
						}
						
						if($value1["TType"] == "A" && $value1["TType2"] == "Issue"){
							$IssueQtyCasesSum = $value1['Qty'] / $CaseQty;
						}
						
						if($value1["TType"] == "B" && $value1["TType2"] == "Production"){
							$PRDCasesSum = $value1['Qty'] / $CaseQty;
						}
						
						if($value1["TType"] == "O" && $value1["TType2"] == "Order"){
							$SalesCasesSum = $value1['Qty'] / $CaseQty;
						}
						
						if($value1["TType"] == "R" && $value1["TType2"] == "Fresh"){
							$SalesRtnCasesSum = $value1['Qty'] / $CaseQty;
						}
						
						if($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" ){
							$AdjCasesSum += $value1['Qty'] / $CaseQty;
						}
						if($value1['TType'] == "X" && $value1["TType2"] == "Free distribution"){
							$AdjCasesSum += $value1['Qty'] / $CaseQty;
						}
						if($value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" ){
							$AdjCasesSum += $value1['Qty'] / $CaseQty;
						}
						if($value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment"){
							$AdjCasesSum += $value1['Qty'] / $CaseQty;
						}
						if($value1["TType"] == "T" && $value1["TType2"] == "In"){
							$GTICasesSum = $value1['Qty'] / $CaseQty;
						}
						if($value1["TType"] == "T" && $value1["TType2"] == "Out"){
							$GTOCasesSum = $value1['Qty'] / $CaseQty;
						}
					}
				}
				$html .= '<tr>';
				$html .= '<td align="center">'.$date.'</td>';
				$html .= '<td align="center">'. number_format($OQTYCases, 2, ".", "").'</td>';
				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
					if($PurchQtyCasesSum == '0' || $PurchQtyCasesSum == '0.00'){
						$PurchQtyCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($PurchQtyCasesSum, 2, ".", "").'</td>';
				}      
				if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
					if($PurchRtnQtyCasesSum == '0' || $PurchRtnQtyCasesSum == '0.00'){
						$PurchRtnQtyCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($PurchRtnQtyCasesSum, 2, ".", "").'</td>';
				} 
				if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
					if($IssueQtyCasesSum == '0' || $IssueQtyCasesSum == '0.00'){
						$IssueQtyCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($IssueQtyCasesSum, 2, ".", "").'</td>';
				}    
				if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
					if($PRDCasesSum == '0' || $PRDCasesSum == '0.00'){
						$PRDCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($PRDCasesSum, 2, ".", "").'</td>';
				}     
				if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
					if($SalesCasesSum == '0' || $SalesCasesSum == '0.00'){
						$SalesCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($SalesCasesSum, 2, ".", "").'</td>';
				}     
				if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
					if($SalesRtnCasesSum == '0' || $SalesRtnCasesSum == '0.00'){
						$SalesRtnCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($SalesRtnCasesSum, 2, ".", "").'</td>';
				}      
				if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
					if($AdjCasesSum == '0' || $AdjCasesSum == '0.00'){
						$AdjCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($AdjCasesSum, 2, ".", "").'</td>';
				}
				
				if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
					if($GTOCasesSum == '0' || $GTOCasesSum == '0.00'){
						$GTOCasesSum = '';
					}
					$html .= '<td align="center">'.number_format($GTOCasesSum, 2, ".", "").'</td>';
				}
				
				if($GTICasesSumC > 0 || $GTICasesSumC < 0){
					if($GTICasesSum == '0' || $GTICasesSum == '0.00'){
						$GTICasesSum = '';
					}
					$html .= '<td align="center">'.number_format($GTICasesSum, 2, ".", "").'</td>';
				}
				
				$BalQty = $OQTYCases +   $PurchQtyCasesSum - $PurchRtnQtyCasesSum - $IssueQtyCasesSum + $PRDCasesSum - $SalesCasesSum + $SalesRtnCasesSum - $AdjCasesSum + $GTICasesSum - $GTOCasesSum;
				$html .= '<td align="center">'. number_format($BalQty, 2, ".", "").'</td>';
				$html .= '<td align="center">'.$Rate.'</td>';
				$ItemValue = (number_format($BalQty, 2, ".", "") * $CaseQty) *  $Rate;
				$html .= '<td align="center">'. number_format($ItemValue, 2, ".", "").'</td>';
				$html .= '</tr>';
				$OQTYCases = $BalQty;
			}
			
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			
			// Footer Data
			$html .= '<tr>';
			$html .= '<td align="center">Total</td>';
			$html .= '<td align="center"></td>';
			
			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
				$PurchQtyCasesSumC = $PurchQtyCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($PurchQtyCasesSumC, 2, ".", "").'</td>';
			}      
			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
				$PurchRtnQtyCasesSumC = $PurchRtnQtyCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($PurchRtnQtyCasesSumC, 2, ".", "").'</td>';
			} 
			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
				$IssueQtyCasesSumC = $IssueQtyCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($IssueQtyCasesSumC, 2, ".", "").'</td>';
			}    
			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
				$PRDCasesSumC = $PRDCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($PRDCasesSumC, 2, ".", "").'</td>';
			}     
			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
				$SalesCasesSumC = $SalesCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($SalesCasesSumC, 2, ".", "").'</td>';
			}     
			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
				$SalesRtnCasesSumC = $SalesRtnCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($SalesRtnCasesSumC, 2, ".", "").'</td>';
			}      
			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
				$AdjCasesSumC = $AdjCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($AdjCasesSumC, 2, ".", "").'</td>';
			}
			if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
				$GTOCasesSumC = $GTOCasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($GTOCasesSumC, 2, ".", "").'</td>';
			}
			if($GTICasesSumC > 0 || $GTICasesSumC < 0){
				$GTICasesSumC = $GTICasesSumC / $CaseQty;
				$html .= '<td align="center">'.number_format($GTICasesSumC, 2, ".", "").'</td>';
			}
			
			$html .= '<td align="center">'. number_format($BalQty, 2, ".", "").'</td>';
			$html .= '<td align="center"></td>';
			$html .= '<td align="center">'. number_format($ItemValue, 2, ".", "").'</td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function ExportItemWiseStockReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'ItemID'  => $this->input->post('ItemID'),
				'ItemName'  => $this->input->post('ItemName'),
				'GodownID'  => $this->input->post('GodownID'),
				);
				$ItemName = $this->input->post('ItemName');
				$ItemID = $this->input->post('ItemID');
				$GodownID = $this->input->post('GodownID');
				$body_data = $this->sale_reports_model->GetItemWiseStockReport($filterdata);
				$StockOQtyData = $this->sale_reports_model->GetItemWiseStockReportOQty($filterdata);
				$RateData = $this->sale_reports_model->GetItemRate($ItemID);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($RateData);
				die;*/
				
				
				$from_date = to_sql_date($this->input->post('from_date'));
				$to_date = to_sql_date($this->input->post('to_date'));
				$to_date_new = date( 'Y-m-d', strtotime( $to_date . ' +1 day' ) );
				$period = new DatePeriod(
				new DateTime($from_date),
				new DateInterval('P1D'),
				new DateTime($to_date_new)
				);
				$from_date = to_sql_date($this->input->post('from_date'));
				$PurchQtyCasesSumC = 0;
				$PurchRtnQtyCasesSumC = 0;
				$IssueQtyCasesSumC = 0;
				$PRDCasesSumC = 0;
				$SalesCasesSumC = 0;
				$SalesRtnCasesSumC = 0;
				$AdjCasesSumC = 0;
				$GTOCasesSumC = 0;
				$GTICasesSumC = 0;
				$OQty = 0;
				$Rate = 0;
				
				$colspan = 4;
				
				foreach ($body_data as $key => $value) {
					if($value["CaseQty"] == "0" || $value["CaseQty"] == ""){
						$CaseQty = 1;
						}else{
						$CaseQty = $value["CaseQty"];
					}
					$OQty = $value["OQty"];
					if($RateData){
						$Rate = $RateData->SaleRate;
						}else{
						$Rate = $value["SaleRate"];
					}
					if($value["TType"] == "P" && $value["TType2"] == "Purchase"){
						$PurchQtyCasesSumC += $value['Qty'];
					}
					
					if($value["TType"] == "N" && $value["TType2"] == "PurchaseReturn"){
						$PurchRtnQtyCasesSumC += $value['Qty'];
					}
					
					if($value["TType"] == "A" && $value["TType2"] == "Issue"){
						$IssueQtyCasesSumC += $value['Qty'];
					}
					
					if($value["TType"] == "B" && $value["TType2"] == "Production"){
						$PRDCasesSumC += $value['Qty'];
					}
					
					if($value["TType"] == "O" && $value["TType2"] == "Order"){
						$SalesCasesSumC += $value['Qty'];
					}
					
					if($value["TType"] == "R" && $value["TType2"] == "Fresh"){
						$SalesRtnCasesSumC += $value['Qty'];
					}
					if($value["TType"] == "X" && $value["TType2"] == "Free Distribution" ){
						$AdjCasesSumC += $value['Qty'];
					}
					if($value["TType"] == "X" && $value["TType2"] == "Free distribution" ){
						$AdjCasesSumC += $value['Qty'];
					}
					if($value["TType"] == "X" && $value["TType2"] == "Promotional Activity" ){
						$AdjCasesSumC += $value['Qty'];
					}
					if($value["TType"] == "X" && $value["TType2"] == "Stock Adjustment"){
						$AdjCasesSumC += $value['Qty'];
						
					}
					if($value["TType"] == "T" && $value["TType2"] == "Out"){
						$GTOCasesSumC += $value['Qty'];
					}
					if($value["TType"] == "T" && $value["TType2"] == "In"){
						$GTICasesSumC += $value['Qty'];
					}
				}
				
				$ItemDetails = 'ItemID : '. $ItemID.' Item Name : '.$ItemName.' , CaseQty'.$CaseQty;
				
				$writer = new XLSXWriter();
				
				$set_col_tk = [];
				
				$set_col_tk["Date"] = 'Date';
				$set_col_tk["OpenQty"] = 'OpenQty';
				
				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
					$set_col_tk["PurchQty"] = 'PurchQty';
					$colspan++;
				}      
				if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
					$set_col_tk["PurchRtn"] = 'PurchRtn';
					$colspan++;
				} 
				if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
					$set_col_tk["IssueQty"] = 'IssueQty';
					$colspan++;
				}    
				if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
					$set_col_tk["Production"] = 'Production';
					$colspan++;
				}     
				if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
					$set_col_tk["SalesQty"] = 'SalesQty';
					$colspan++;
				}     
				if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
					$set_col_tk["SalesRtn"] = 'SalesRtn';
					$colspan++;
				}      
				if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
					$set_col_tk["AdjQty"] = 'AdjQty';
					$colspan++;
				}
				if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
					$set_col_tk["GTOQty"] = 'GTOQty';
					$colspan++;
				}
				if($GTICasesSumC > 0 || $GTICasesSumC < 0){
					$set_col_tk["GTIQty"] = 'GTIQty';
					$colspan++;
				}
				
				$set_col_tk["Bal.Qty"] = 'Bal.Qty';
				$set_col_tk["Rate"] = 'Rate';
				$set_col_tk["StkValue"] = 'StkValue';
				
				$writer_header = $set_col_tk;
				
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = $ItemDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				if($from_date == '2022-04-01'){
					$OQTYCases = floatval($OQty) / floatval($CaseQty);
					}else{
					$OQtySum = 0;
					$OQtySum += floatval($OQty);
					foreach ($StockOQtyData as $keyOQty => $valueOQty) {
						
						if($valueOQty['TType'] == "P" && $valueOQty["TType2"] == "Purchase"){
							$OQtySum += $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "N" && $valueOQty["TType2"] == "PurchaseReturn"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "A" && $valueOQty["TType2"] == "Issue"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "B" && $valueOQty["TType2"] == "Production"){
							$OQtySum += $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "O" && $valueOQty['TType2'] == "Order"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "R" && $valueOQty["TType2"] == "Fresh"){
							$OQtySum += $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Free Distribution"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty["TType"] == "X" && $valueOQty["TType2"] == "Free distribution" ){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Promotional Activity"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "X" && $valueOQty["TType2"] == "Stock Adjustment"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "T" && $valueOQty["TType2"] == "Out"){
							$OQtySum -= $valueOQty['Qty'];
						}
						if($valueOQty['TType'] == "T" && $valueOQty["TType2"] == "In"){
							$OQtySum += $valueOQty['Qty'];
						}
					}
					if($OQtySum == "0" || $OQtySum == "0.00"){
						$OQTYCases = '0.00';
						}else{
						$OQTYCases = floatval($OQtySum) / floatval($CaseQty);
					}
				}
				
				foreach ($period as $key => $value) {
					$PurchQtyCasesSum = 0;
					$PurchRtnQtyCasesSum = 0;
					$IssueQtyCasesSum = 0;
					$PRDCasesSum = 0;
					$SalesCasesSum = 0;
					$SalesRtnCasesSum = 0;
					$AdjCasesSum = 0;
					$GTOCasesSum = 0;
					$GTICasesSum = 0;
					
					
					$date = $value->format('d/m/Y'); 
					$date2 = $value->format('Y-m-d');
					foreach ($body_data as $key1 => $value1) {
						if(substr($value1['TransDate2'],0,10) == $date2){
							
							if($value1["TType"] == "P" && $value1["TType2"] == "Purchase"){
								$PurchQtyCasesSum = $value1['Qty'] / $CaseQty;
							}
							
							if($value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){
								$PurchRtnQtyCasesSum = $value1['Qty'] / $CaseQty;
							}
							
							if($value1["TType"] == "A" && $value1["TType2"] == "Issue"){
								$IssueQtyCasesSum = $value1['Qty'] / $CaseQty;
							}
							
							if($value1["TType"] == "B" && $value1["TType2"] == "Production"){
								$PRDCasesSum = $value1['Qty'] / $CaseQty;
							}
							
							if($value1["TType"] == "O" && $value1["TType2"] == "Order"){
								$SalesCasesSum = $value1['Qty'] / $CaseQty;
							}
							
							if($value1["TType"] == "R" && $value1["TType2"] == "Fresh"){
								$SalesRtnCasesSum = $value1['Qty'] / $CaseQty;
							}
							
							if($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" ){
								$AdjCasesSum += $value1['Qty'] / $CaseQty;
							}
							if($value1["TType"] == "X" && $value1["TType2"] == "Free distribution" ){
								$AdjCasesSum += $value1['Qty'] / $CaseQty;
							}
							if($value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" ){
								$AdjCasesSum += $value1['Qty'] / $CaseQty;
							}
							if($value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment"){
								$AdjCasesSum += $value1['Qty'] / $CaseQty;
							}
							if($value1["TType"] == "T" && $value1["TType2"] == "Out"){
								$GTOCasesSum += $value1['Qty'] / $CaseQty;
							}
							if($value1["TType"] == "T" && $value1["TType2"] == "In"){
								$GTICasesSum += $value1['Qty'] / $CaseQty;
							}
						}
					}
					
					$list_add = [];
					$list_add[] = $date;
					$list_add[] = number_format($OQTYCases, 2, ".", "");
					
					if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
						if($PurchQtyCasesSum == '0' || $PurchQtyCasesSum == '0.00'){
							$PurchQtyCasesSum = '';
						}
						$list_add[] = $PurchQtyCasesSum;
					}      
					if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
						if($PurchRtnQtyCasesSum == '0' || $PurchRtnQtyCasesSum == '0.00'){
							$PurchRtnQtyCasesSum = '';
						}
						$list_add[] = $PurchRtnQtyCasesSum;
					} 
					if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
						if($IssueQtyCasesSum == '0' || $IssueQtyCasesSum == '0.00'){
							$IssueQtyCasesSum = '';
						}
						$list_add[] = $IssueQtyCasesSum;
					}    
					if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
						if($PRDCasesSum == '0' || $PRDCasesSum == '0.00'){
							$PRDCasesSum = '';
						}
						$list_add[] = $PRDCasesSum;
					}     
					if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
						if($SalesCasesSum == '0' || $SalesCasesSum == '0.00'){
							$SalesCasesSum = '';
						}
						$list_add[] = $SalesCasesSum;
					}     
					if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
						if($SalesRtnCasesSum == '0' || $SalesRtnCasesSum == '0.00'){
							$SalesRtnCasesSum = '';
						}
						$list_add[] = $SalesRtnCasesSum;
					}      
					if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
						if($AdjCasesSum == '0' || $AdjCasesSum == '0.00'){
							$AdjCasesSum = '';
						}
						$list_add[] = $AdjCasesSum;
					}
					if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
						if($GTOCasesSum == '0' || $GTOCasesSum == '0.00'){
							$GTOCasesSum = '';
						}
						$list_add[] = $GTOCasesSum;
					}
					if($GTICasesSumC > 0 || $GTICasesSumC < 0){
						if($GTICasesSum == '0' || $GTICasesSum == '0.00'){
							$GTICasesSum = '';
						}
						$list_add[] = $GTICasesSum;
					}
					
					$BalQty = $OQTYCases +   $PurchQtyCasesSum - $PurchRtnQtyCasesSum - $IssueQtyCasesSum + $PRDCasesSum - $SalesCasesSum + $SalesRtnCasesSum - $AdjCasesSum + $GTICasesSum - $GTOCasesSum;
					
					$list_add[] = number_format($BalQty, 2, ".", "");
					$list_add[] = $Rate;
					$ItemValue = (number_format($BalQty, 2, ".", "") * $CaseQty) *  $Rate;
					$list_add[] = number_format($ItemValue, 2, ".", "");
					$OQTYCases = $BalQty;
					
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				// Footer Data
				$list_add = [];
				
				$list_add[] = 'Total';
				$list_add[] = '';
				$html .= '<td align="center">Total</td>';
				$html .= '<td align="center"></td>';
				
				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
					$PurchQtyCasesSumC = $PurchQtyCasesSumC / $CaseQty;
					$list_add[] = $PurchQtyCasesSumC;
				}      
				if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
					$PurchRtnQtyCasesSumC = $PurchRtnQtyCasesSumC / $CaseQty;
					$list_add[] = $PurchRtnQtyCasesSumC;
				} 
				if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
					$IssueQtyCasesSumC = $IssueQtyCasesSumC / $CaseQty;
					$list_add[] = $IssueQtyCasesSumC;
				}    
				if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
					$PRDCasesSumC = $PRDCasesSumC / $CaseQty;
					$list_add[] = $PRDCasesSumC;
				}     
				if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
					$SalesCasesSumC = $SalesCasesSumC / $CaseQty;
					$list_add[] = $SalesCasesSumC;
				}     
				if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
					$SalesRtnCasesSumC = $SalesRtnCasesSumC / $CaseQty;
					$list_add[] = $SalesRtnCasesSumC;
				}      
				if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
					$AdjCasesSumC = $AdjCasesSumC / $CaseQty;
					$list_add[] = $AdjCasesSumC;
				}
				if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
					$GTOCasesSumC = $GTOCasesSumC / $CaseQty;
					$list_add[] = $GTOCasesSumC;
				}
				if($GTICasesSumC > 0 || $GTICasesSumC < 0){
					$GTICasesSumC = $GTICasesSumC / $CaseQty;
					$list_add[] = $GTICasesSumC;
				}
				
				
				$list_add[] = number_format($BalQty, 2, ".", "");;
				$list_add[] = '';
				$list_add[] = number_format($ItemValue, 2, ".", "");;
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'ItemWiseStockReport.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		// Get Result for PartyItemWise report
		public function GetPartyItemWiseReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'AccountID'  => $this->input->post('AccountID'),
			'TransType'  => $this->input->post('TransType'),
			);
			$ReportType = $this->input->post('ReportType');
			$body_data = $this->sale_reports_model->GetPartyItemWiseBodyData($filterdata);
			$ItemSubGroupList = $this->sale_reports_model->GetPartyWiseItemSubGroupData($filterdata);
			/*echo json_encode($body_data);
			die;*/
			$html = '';
			$html .= '<div>';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th align="center">Sr.No</th>';
			$html .= '<th align="center">ItemID</th>';
			$html .= '<th align="center">ItemName</th>';
			$html .= '<th align="left">PackIn</th>';
			$html .= '<th align="left">Pack</th>';
			$html .= '<th align="center">Rate</th>';
			$html .= '<th align="center">Billed Qty</th>';
			$html .= '<th align="center">Billed Crates</th>';
			$html .= '<th align="center">Billed Cases</th>';
			$html .= '<th align="center">Item value</th>';
			$html .= '<th align="center">Sale Percentage %</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			// Calculate Total Sale Amount
			$TotalAmount = 0;
			foreach($body_data as $Totalkey => $Totalvalue){
				$TotalAmount += $Totalvalue["ItemValue"];
			}
			$i = 1;
			$BilledQtySum = 0;
			$BilledCratesSum = 0;
			$BilledCasaseSum = 0;
			$ItemValueSum = 0;
			foreach($ItemSubGroupList as $GrpKey=>$GrpVal)
			{
				$GrpWiseBilledQty = 0;
				$GrpWiseCrates = 0;
				$GrpWiseCases = 0;
				$GrpWiseValue = 0;
				foreach ($body_data as $key => $value) {
					if($value["BilledQty"] > 0 && $value["subgroup_id"] == $GrpVal["subgroup_id"]){
						$html .= '<tr>';
						$html .= '<td align="center">'.$i.'</td>';
						$html .= '<td align="center">'.$value["ItemID"].'</td>';
						$html .= '<td align="left">'.$value["description"].'</td>';
						$html .= '<td align="center">'.$value["SuppliedIn"].'</td>';
						$html .= '<td align="center">'.$value["CaseQty"].'</td>';
						$html .= '<td align="right">'.$value["SaleRate"].'</td>';
						$html .= '<td align="right">'. number_format($value["BilledQty"], 2, ".", "").'</td>';
						$BilledQtySum += $value["BilledQty"];
						$GrpWiseBilledQty += $value["BilledQty"];
						if($value["SuppliedIn"] == "CS"){
							$Cases = $value["BilledQty"] / $value["CaseQty"];
							$BilledCasaseSum += $Cases;
							$Crates = '';
							}else{
							$Crates = $value["BilledQty"] / $value["CaseQty"];
							$BilledCratesSum += $Crates;
							$Cases = '';
						}
						$GrpWiseCrates += $Crates;
						$GrpWiseCases += $Cases;
						$GrpWiseValue += $value["ItemValue"];
						$html .= '<td align="right">'.number_format($Crates, 2, ".", "").'</td>';
						$html .= '<td align="right">'.number_format($Cases, 2, ".", "").'</td>';
						$html .= '<td align="right">'. number_format($value["ItemValue"], 2, ".", "").'</td>';
						$html .= '<td align="right">'. number_format(($value["ItemValue"]/$TotalAmount)*100, 2, ".", "").'</td>';
						$ItemValueSum += $value["ItemValue"];
						$html .= '</tr>';
						$i++;
					}
				}
				if($ReportType == "1"){
					$html .= '<tr>';
					$html .= '<td align="left" colspan="6" style="color:#03a9f4;font-size:14px;"><b>'.$GrpVal["ItemSubGrpName"].'</b></td>';
					$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'. number_format($GrpWiseBilledQty, 2, ".", "").'</b></td>';
					$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'.number_format($GrpWiseCrates, 2, ".", "").'</b></td>';
					$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'.number_format($GrpWiseCases, 2, ".", "").'</b></td>';
					$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'. number_format($GrpWiseValue, 2, ".", "").'</b></td>';
					$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'. number_format(($GrpWiseValue/$TotalAmount)*100, 2, ".", "").'</b></td>';
					$html .= '</tr>';
				}
			}
			
			
			// Footer Data
			$html .= '<tr>';
			$html .= '<td  colspan="6" align="right" style="color:#03a9f4;font-size:16px;"><b>Total</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:16px;"><b>'. number_format($BilledQtySum, 2, ".", "").'</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:16px;"><b>'. number_format($BilledCratesSum, 2, ".", "").'</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:16px;"><b>'. number_format($BilledCasaseSum, 2, ".", "").'</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:16px;"><b>'. number_format($ItemValueSum, 2, ".", "").'</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:16px;"></td>';
			$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
			echo json_encode($html);
			die;
		}
		
		public function ExportPartyItemWiseReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'AccountID'  => $this->input->post('AccountID'),
				'TransType'  => $this->input->post('TransType'),
				);
				$ReportType = $this->input->post('ReportType');
				$AccountName = $this->input->post('AccountName');
				$AccountAddress = $this->input->post('AccountAddress');
				$AccountAddress2 = $this->input->post('AccountAddress2');
				$station = $this->input->post('station');
				$StateName = $this->input->post('StateName');
				$TransType = $this->input->post('TransType');
				$body_data = $this->sale_reports_model->GetPartyItemWiseBodyData($filterdata);
				$ItemSubGroupList = $this->sale_reports_model->GetPartyWiseItemSubGroupData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Account Name : '. $AccountName.' Address : '.$AccountAddress.' '.$AccountAddress2;
				$OtherDetails = 'Station : '.$station .' State : '. $StateName;
				$colspan = '10';
				if($TransType == '1'){
					$TransTypeName = 'Sale';
					}else if($TransType == '2'){
					$TransTypeName = 'Fresh';
					}else if($TransType == '3'){
					$TransTypeName = 'Damage';
					}else if($TransType == '4'){
					$TransTypeName = 'NetSale';
				}
				$RowCount = 0;
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				$RowCount++;
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				$RowCount++;
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				$RowCount++;
				$msg2 = $AccountDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				$RowCount++;
				$msg3 = $OtherDetails;
				$filter3 = array($msg3);
				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter3);
				$RowCount++;
				$msg4 = 'Trans Type : '.$TransTypeName;
				$filter4 = array($msg4);
				$writer->markMergedCell('Sheet1', $start_row = 5, $start_col = 0, $end_row = 5, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter4);
				$RowCount++;
				$set_col_tk = [];
				$set_col_tk["SrNo"] = 'Sr.No.';
				$set_col_tk["ItemID"] = 'ItemID';
				$set_col_tk["ItemName"] = 'ItemName';
				$set_col_tk["PackIn"] = 'PackIn';
				$set_col_tk["Pack"] = 'Pack';
				$set_col_tk["Rate"] = 'Rate';
				$set_col_tk["Billed Qty"] = 'Billed Qty';
				$set_col_tk["Billed Crates"] = 'Billed Crates';
				$set_col_tk["Billed Cases"] = 'Billed Cases';
				$set_col_tk["Item value"] = 'Item value';
				$set_col_tk["SalePer"] = 'Sale Percentage %';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				$RowCount++;
				// Calculate Total Sale Amount
				$TotalAmount = 0;
				foreach($body_data as $Totalkey => $Totalvalue){
					$TotalAmount += $Totalvalue["ItemValue"];
				}
				
				$i = 1;
				$BilledQtySum = 0;
				$BilledCratesSum = 0;
				$BilledCasaseSum = 0;
				$ItemValueSum = 0;
				
				foreach($ItemSubGroupList as $GrpKey=>$GrpVal)
				{
					$GrpWiseBilledQty = 0;
					$GrpWiseCrates = 0;
					$GrpWiseCases = 0;
					$GrpWiseValue = 0;
					foreach ($body_data as $key => $value) {
						if($value["BilledQty"] > 0 && $value["subgroup_id"] == $GrpVal["subgroup_id"]){
							$list_add = [];
							$list_add[] = $i;
							$list_add[] = $value["ItemID"];
							$list_add[] = $value["description"];
							$list_add[] = $value["SuppliedIn"];
							$list_add[] = $value["CaseQty"];
							$list_add[] = $value["SaleRate"];
							$list_add[] = number_format($value["BilledQty"], 2, ".", "");
							$BilledQtySum += $value["BilledQty"];
							$GrpWiseBilledQty += $value["BilledQty"];
							if($value["SuppliedIn"] == "CS"){
								$Cases = $value["BilledQty"] / $value["CaseQty"];
								$BilledCasaseSum += $Cases;
								$Crates = '';
								}else{
								$Crates = $value["BilledQty"] / $value["CaseQty"];
								$BilledCratesSum += $Crates;
								$Cases = '';
							}
							$GrpWiseCrates += $Crates;
							$GrpWiseCases += $Cases;
							$GrpWiseValue += $value["ItemValue"];
							$list_add[] = number_format($Crates, 2, ".", "");
							$list_add[] = number_format($Cases, 2, ".", "");
							$list_add[] = number_format($value["ItemValue"], 2, ".", "");
							$list_add[] = number_format(($value["ItemValue"]/$TotalAmount)*100, 2, ".", "");
							$ItemValueSum += $value["ItemValue"];
							$writer->writeSheetRow('Sheet1', $list_add);
							$i++;
							$RowCount++;
						}
					}
					if($ReportType == "1"){
						$list_add = [];
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = $GrpVal["ItemSubGrpName"];
						$list_add[] = number_format($GrpWiseBilledQty, 2, ".", "");
						$list_add[] = number_format($GrpWiseCrates, 2, ".", "");
						$list_add[] = number_format($GrpWiseCases, 2, ".", "");
						$list_add[] = number_format($GrpWiseValue, 2, ".", "");
						$list_add[] = number_format(($GrpWiseValue/$TotalAmount)*100, 2, ".", "");
						//$writer->markMergedCell('Sheet1', $start_row = $RowCount, $start_col = 0, $end_row = $RowCount, $end_col = 5);
						$writer->writeSheetRow('Sheet1', $list_add);
						$RowCount++;
					}
				}
				// Footer Data
				$list_add = [];
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = 'Total';
				$list_add[] = number_format($BilledQtySum, 2, ".", "");
				$list_add[] = number_format($BilledCratesSum, 2, ".", "");
				$list_add[] = number_format($BilledCasaseSum, 2, ".", "");
				$list_add[] = number_format($ItemValueSum, 2, ".", "");
				$list_add[] = '';
				//$writer->markMergedCell('Sheet1', $start_row = $RowCount, $start_col = 0, $end_row = $RowCount, $end_col = 5);
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'PartyItemWiseReport.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* PartyItem Wise report page */
		public function BillsReceivableReport()
		{
			if (!has_permission_new('TradeReceivableReport', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Trade Receivable Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/BillsReceivableReport', $data);
		}
		
		
		// Get Result for PartyItemWise report
		public function GetBillsReceivableReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$ReportType = $this->input->post('ReportType');
			$body_data = $this->sale_reports_model->GetBillsReceivableBodyData($filterdata);
			// 			echo json_encode($body_data);
			// 			die;
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">Date</th>';
			$html .= '<th class="sortable" align="center">Customer</th>';
			$html .= '<th class="sortable" align="center">Inv No.</th>';
			$html .= '<th class="sortable" align="center">Inv Amt</th>';
			$html .= '<th class="sortable" align="center">Paid Amt</th>';
			$html .= '<th class="sortable" align="center">SaleRtn Amt</th>';
			$html .= '<th class="sortable" align="center">Credit Note Amt</th>';
			$html .= '<th class="sortable" align="center">Journal Amt</th>';
			$html .= '<th class="sortable" align="center">Due Amt</th>';
			$html .= '<th class="sortable" align="center">Due On</th>';
			$html .= '<th class="sortable" align="center">Over Due By Days</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			
			$chkid = '';
			$total = 0;
			$totaldue = 0;
			$totalpaid = 0;
			$totalcdnote = 0;
			$totaljournal = 0;
			$totalsalertn = 0;
			
			$Grandtotal = 0;
			$Grandtotaldue = 0;
			$Grandtotalpaid = 0;
			$Grandtotalcdnote = 0;
			$Grandtotaljournal = 0;
			$Grandtotalsalertn = 0;
			
			$chk=0;
			$TotalRecord = count($body_data);
			foreach ($body_data as $key => $value) {
				
				
				$dueAmt = $value["RndAmt"]-$value["PaidAmt"]-$value["CDNoteAmt"]-$value["SaleRtnAmt"]-($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
				$transdate = $value["Transdate"];
				
				// Assuming $value["payment_term"] is the number of days
				$paymentTerm = $value["MaxDays"];
				
				// Calculate the next date based on current date and payment term
				$nextDateTimestamp = strtotime($transdate . " + $paymentTerm days");
				
				// Format the timestamp to the desired date format
				$nextDate = date('d-M-y', $nextDateTimestamp);
				
				// Get the current timestamp
				$currentTimestamp = time();
				
				// Calculate the difference in seconds between current date and next date
				$differenceInSeconds = $currentTimestamp - $nextDateTimestamp;
				
				// Convert the difference to days
				$overdueDays = ceil($differenceInSeconds / (60 * 60 * 24)); // ceil to round up to the nearest whole day
				
				if($chkid != $value["AccountID"])
				{
					if($chk > 0){
						$chk = 0;
						$html .= '<tr>';
						$html .= '<td colspan="4" align="right" style="font-weight: 700;font-size: 14px;text-align:right;">Total</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($total, 2, ".", "").'</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalpaid, 2, ".", "").'</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalsalertn, 2, ".", "").'</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalcdnote, 2, ".", "").'</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totaljournal, 2, ".", "").'</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totaldue, 2, ".", "").'</td>';
						$html .= '<td align="right" ></td>';
						
						$html .= '<td align="right"></td>';
						$html .= '</tr>';
					}
					$total = 0;
					$totaldue = 0;
					$totalpaid = 0;
					$totalcdnote = 0;
					$totaljournal = 0;
					$totalsalertn = 0;
				}
				
				if($ReportType == "Overdue" && $overdueDays > 0 && $dueAmt > 0){
				    $chkid = $value["AccountID"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$totalsalertn += $value["SaleRtnAmt"];
					$totaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
					$totalcdnote += $value["CDNoteAmt"];
					$total += $value["RndAmt"];
					$html .= '<tr>';
					$html .= '<td align="center">'.$i.'</td>';
					$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
					$html .= '<td align="center">'.$value["company"].'</td>';
					$html .= '<td align="center">'.$value["SalesID"].'</td>';
					$html .= '<td align="right">'.$value["RndAmt"].'</td>';
					$html .= '<td align="right">'.$value["PaidAmt"].'</td>';
					$html .= '<td align="right">'.$value["SaleRtnAmt"].'</td>';
					$html .= '<td align="right">'.$value["CDNoteAmt"].'</td>';
					$html .= '<td align="right">'.($value["JournalCreditAmt"] - $value["JournalDebitAmt"]).'</td>';
					$html .= '<td align="right">'.number_format($dueAmt, 2, ".", "").'</td>';
					$html .= '<td align="center">'.$nextDate.'</td>';
					if($dueAmt >0 && $overdueDays > 0)
					{
						$html .= '<td align="center">'.$overdueDays.' Days</td>';
					}
					else
					{
						$html .= '<td align="center"></td>';
					}
					$html .= '</tr>';
				}
				
				if($ReportType == "NonOverdue" && $overdueDays <= 0){
				    $chkid = $value["AccountID"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$totalsalertn += $value["SaleRtnAmt"];
					$totalcdnote += $value["CDNoteAmt"];
					$totaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
					$total += $value["RndAmt"];
					$html .= '<tr>';
					$html .= '<td align="center">'.$i.'</td>';
					$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
					$html .= '<td align="center">'.$value["company"].'</td>';
					$html .= '<td align="center">'.$value["SalesID"].'</td>';
					$html .= '<td align="right">'.$value["RndAmt"].'</td>';
					$html .= '<td align="right">'.$value["PaidAmt"].'</td>';
					$html .= '<td align="right">'.$value["SaleRtnAmt"].'</td>';
					$html .= '<td align="right">'.$value["CDNoteAmt"].'</td>';
					$html .= '<td align="right">'.($value["JournalCreditAmt"] - $value["JournalDebitAmt"]).'</td>';
					$html .= '<td align="right">'.number_format($dueAmt, 2, ".", "").'</td>';
					$html .= '<td align="center">'.$nextDate.'</td>';
					if($dueAmt >0 && $overdueDays > 0)
					{
						$html .= '<td align="center">'.$overdueDays.' Days</td>';
					}
					else
					{
						$html .= '<td align="center"></td>';
					}
					$html .= '</tr>';
				}
				if(empty($ReportType)){
				    $chkid = $value["AccountID"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$totalsalertn += $value["SaleRtnAmt"];
					$totalcdnote += $value["CDNoteAmt"];
					$totaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
					$total += $value["RndAmt"];
					$html .= '<tr>';
					$html .= '<td align="center">'.$i.'</td>';
					$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
					$html .= '<td align="center">'.$value["company"].'</td>';
					$html .= '<td align="center">'.$value["SalesID"].'</td>';
					$html .= '<td align="right">'.$value["RndAmt"].'</td>';
					$html .= '<td align="right">'.$value["PaidAmt"].'</td>';;
					$html .= '<td align="right">'.$value["SaleRtnAmt"].'</td>';
					$html .= '<td align="right">'.$value["CDNoteAmt"].'</td>';
					$html .= '<td align="right">'.($value["JournalCreditAmt"] - $value["JournalDebitAmt"]).'</td>';
					$html .= '<td align="right">'.number_format($dueAmt, 2, ".", "").'</td>';
					$html .= '<td align="center">'.$nextDate.'</td>';
					if($dueAmt >0 && $overdueDays > 0)
					{
						$html .= '<td align="center">'.$overdueDays.' Days</td>';
					}
					else
					{
						$html .= '<td align="center"></td>';
					}
					$html .= '</tr>';
				}
				
				
				// for last party total row
				if($TotalRecord == $i && $chk > 0){
				    $html .= '<tr>';
					$html .= '<td colspan="4" align="right" style="font-weight: 700;font-size: 14px;text-align:right;">Total</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($total, 2, ".", "").'</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalpaid, 2, ".", "").'</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalsalertn, 2, ".", "").'</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalcdnote, 2, ".", "").'</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totaljournal, 2, ".", "").'</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totaldue, 2, ".", "").'</td>';
					$html .= '<td align="right" ></td>';
					$html .= '<td align="right"></td>';
					$html .= '</tr>';
				}
				
				
				$Grandtotaldue += $dueAmt;
				$Grandtotalpaid += $value["PaidAmt"];
				$Grandtotalsalertn += $value["SaleRtnAmt"];
				$Grandtotaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
				$Grandtotalcdnote += $value["CDNoteAmt"];
				$Grandtotal += $value["RndAmt"];
				$i++;
			}
			
			$html .= '<tr>';
			$html .= '<td colspan="4" align="right" style="font-weight: 700;font-size: 14px;text-align:right;">Grand Total</td>';
			$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($Grandtotal, 2, ".", "").'</td>';
			$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($Grandtotalpaid, 2, ".", "").'</td>';
			$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($Grandtotalsalertn, 2, ".", "").'</td>';
			$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($Grandtotalcdnote, 2, ".", "").'</td>';
			$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($Grandtotaljournal, 2, ".", "").'</td>';
			$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($Grandtotaldue, 2, ".", "").'</td>';
			$html .= '<td align="right" ></td>';
			$html .= '<td align="right"></td>';
			$html .= '</tr>';
			// Footer Data
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function GetBillsReceivableReportDaywiseChart()
		{
			
			$selected_company = $this->session->userdata('root_company');
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$ReportType = $this->input->post('ReportType');
			$body_data = $this->sale_reports_model->GetBillsReceivableBodyData($filterdata);
			// echo json_encode($body_data);
			// die;
			$ReturnArr = [
			'0-15' => 0,
			'15-30' => 0,
			'30-60' => 0,
			'60+' => 0,
			];
			$currentTimestamp = time();
			$AllTotal = 0;
			foreach ($body_data as $value) {
				
				$dueAmt = $value["RndAmt"]-$value["PaidAmt"]-$value["CDNoteAmt"]-$value["SaleRtnAmt"]-($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
				if ($dueAmt <= 0) {
					continue; // Skip fully paid bills
				}
				$transdate = $value["Transdate"];
				
				// Assuming $value["payment_term"] is the number of days
				$paymentTerm = $value["MaxDays"];
				
				// Calculate the next date based on current date and payment term
				$nextDateTimestamp = strtotime($transdate . " + $paymentTerm days");
				// Format the timestamp to the desired date format
				$nextDate = date('d-M-y', $nextDateTimestamp);
				// echo $nextDate;die;
				
				// Calculate the difference in seconds between current date and next date
				$differenceInSeconds = $currentTimestamp - $nextDateTimestamp;
				
				// Convert the difference to days
				$overdueDays = ceil($differenceInSeconds / (60 * 60 * 24)); // ceil to round up to the nearest whole day
				// echo $dueAmt;die;
				if($ReportType == "Overdue" && $overdueDays > 0 && $dueAmt > 0){
					if ($overdueDays > 0 && $overdueDays <= 15) {
						$ReturnArr['0-15'] += $dueAmt;
						} elseif ($overdueDays > 0 && $overdueDays <= 30) {
						$ReturnArr['15-30'] += $dueAmt;
						} elseif ($overdueDays > 0 && $overdueDays <= 60) {
						$ReturnArr['30-60'] += $dueAmt;
						} elseif ($overdueDays > 60){
						$ReturnArr['60+'] += $dueAmt;
					}
				}
			}
			// echo $AllTotal;die;
			// Format for Highcharts
			$FinalArr = [];
			foreach ($ReturnArr as $range => $amt) {
				$FinalArr[] = [
				'name' => $range,
				'y'    => round($amt, 2)
				];
			}
			echo json_encode($FinalArr);
			die;
		}
		
		public function GetSaleBillsReceivableReport()
		{
			
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$ReportType = 'Overdue';
			$body_data = $this->sale_reports_model->GetBillsReceivableBodyData($filterdata);
			// 			echo json_encode($body_data);
			// 			die;
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">Date</th>';
			$html .= '<th class="sortable" align="center">Customer</th>';
			$html .= '<th class="sortable" align="center">Inv No.</th>';
			// $html .= '<th class="sortable" align="center">Inv Amt</th>';
			// $html .= '<th class="sortable" align="center">Paid Amt</th>';
			$html .= '<th class="sortable" align="center">Due Amt</th>';
			$html .= '<th class="sortable" align="center">Due On</th>';
			$html .= '<th class="sortable" align="center">Over Due By Days</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			
			$chkid = '';
			$total = 0;
			$totaldue = 0;
			$totalpaid = 0;
			$chk=0;
			$TotalRecord = count($body_data);
			foreach ($body_data as $key => $value) {
				
				
				$dueAmt = $value["RndAmt"]-$value["PaidAmt"];
				$transdate = $value["Transdate"];
				
				// Assuming $value["payment_term"] is the number of days
				$paymentTerm = $value["MaxDays"];
				
				// Calculate the next date based on current date and payment term
				$nextDateTimestamp = strtotime($transdate . " + $paymentTerm days");
				
				// Format the timestamp to the desired date format
				$nextDate = date('d-M-y', $nextDateTimestamp);
				
				// Get the current timestamp
				$currentTimestamp = time();
				
				// Calculate the difference in seconds between current date and next date
				$differenceInSeconds = $currentTimestamp - $nextDateTimestamp;
				
				// Convert the difference to days
				$overdueDays = ceil($differenceInSeconds / (60 * 60 * 24)); // ceil to round up to the nearest whole day
				
				if($chkid != $value["AccountID"])
				{
					if($chk > 0){
						$chk = 0;
						$html .= '<tr>';
						$html .= '<td colspan="4" align="right" style="font-weight: 700;font-size: 14px;text-align:right;">Total</td>';
						// $html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($total, 2, ".", "").'</td>';
						// $html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalpaid, 2, ".", "").'</td>';
						$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totaldue, 2, ".", "").'</td>';
						$html .= '<td align="right" ></td>';
						
						$html .= '<td align="right"></td>';
						$html .= '</tr>';
					}
					$total = 0;
					$totaldue = 0;
					$totalpaid = 0;
				}
				
				if($ReportType == "Overdue" && $overdueDays > 0 && $dueAmt > 0){
				    $chkid = $value["AccountID"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$total += $value["RndAmt"];
					$html .= '<tr>';
					$html .= '<td align="center">'.$i.'</td>';
					$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
					$html .= '<td align="center">'.$value["company"].'</td>';
					$html .= '<td align="center">'.$value["SalesID"].'</td>';
					// $html .= '<td align="right">'.$value["RndAmt"].'</td>';
					// $html .= '<td align="right">'.$value["PaidAmt"].'</td>';
					$html .= '<td align="right">'.number_format($dueAmt, 2, ".", "").'</td>';
					$html .= '<td align="center">'.$nextDate.'</td>';
					if($dueAmt >0 && $overdueDays > 0)
					{
						$html .= '<td align="center">'.$overdueDays.' Days</td>';
					}
					else
					{
						$html .= '<td align="center"></td>';
					}
					$html .= '</tr>';
				}
				
				if($ReportType == "NonOverdue" && $overdueDays <= 0){
				    $chkid = $value["AccountID"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$total += $value["RndAmt"];
					$html .= '<tr>';
					$html .= '<td align="center">'.$i.'</td>';
					$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
					$html .= '<td align="center">'.$value["company"].'</td>';
					$html .= '<td align="center">'.$value["SalesID"].'</td>';
					// $html .= '<td align="right">'.$value["RndAmt"].'</td>';
					// $html .= '<td align="right">'.$value["PaidAmt"].'</td>';
					$html .= '<td align="right">'.number_format($dueAmt, 2, ".", "").'</td>';
					$html .= '<td align="center">'.$nextDate.'</td>';
					if($dueAmt >0 && $overdueDays > 0)
					{
						$html .= '<td align="center">'.$overdueDays.' Days</td>';
					}
					else
					{
						$html .= '<td align="center"></td>';
					}
					$html .= '</tr>';
				}
				if(empty($ReportType)){
				    $chkid = $value["AccountID"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$total += $value["RndAmt"];
					$html .= '<tr>';
					$html .= '<td align="center">'.$i.'</td>';
					$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
					$html .= '<td align="center">'.$value["company"].'</td>';
					$html .= '<td align="center">'.$value["SalesID"].'</td>';
					// $html .= '<td align="right">'.$value["RndAmt"].'</td>';
					// $html .= '<td align="right">'.$value["PaidAmt"].'</td>';
					$html .= '<td align="right">'.number_format($dueAmt, 2, ".", "").'</td>';
					$html .= '<td align="center">'.$nextDate.'</td>';
					if($dueAmt >0 && $overdueDays > 0)
					{
						$html .= '<td align="center">'.$overdueDays.' Days</td>';
					}
					else
					{
						$html .= '<td align="center"></td>';
					}
					$html .= '</tr>';
				}
				
				
				// for last party total row
				if($TotalRecord == $i && $chk > 0){
				    $html .= '<tr>';
					$html .= '<td colspan="4" align="right" style="font-weight: 700;font-size: 14px;text-align:right;">Total</td>';
					// $html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($total, 2, ".", "").'</td>';
					// $html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totalpaid, 2, ".", "").'</td>';
					$html .= '<td align="right" style="font-weight: 700;font-size: 14px;text-align:right;">'.number_format($totaldue, 2, ".", "").'</td>';
					$html .= '<td align="right" ></td>';
					$html .= '<td align="right"></td>';
					$html .= '</tr>';
				}
				$i++;
			}
			
			// Footer Data
			
			$html .= '</tbody>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function GetTopBillsReceivableReport()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$ReportType = 'Overdue';
			$body_data = $this->sale_reports_model->GetBillsReceivableBodyData($data);
			$chart = [];
			
			$chkid = '';
			$chkParty = '';
			$total = 0;
			$totaldue = 0;
			$totalpaid = 0;
			$chk=0;
			$TotalRecord = count($body_data);
			foreach ($body_data as $key => $value) {
				
				
				$dueAmt = $value["RndAmt"]-$value["PaidAmt"];
				$transdate = $value["Transdate"];
				
				// Assuming $value["payment_term"] is the number of days
				$paymentTerm = $value["MaxDays"];
				
				// Calculate the next date based on current date and payment term
				$nextDateTimestamp = strtotime($transdate . " + $paymentTerm days");
				
				// Format the timestamp to the desired date format
				$nextDate = date('d-M-y', $nextDateTimestamp);
				
				// Get the current timestamp
				$currentTimestamp = time();
				
				// Calculate the difference in seconds between current date and next date
				$differenceInSeconds = $currentTimestamp - $nextDateTimestamp;
				
				// Convert the difference to days
				$overdueDays = ceil($differenceInSeconds / (60 * 60 * 24)); // ceil to round up to the nearest whole day
				
				if($chkid != $value["AccountID"])
				{
					if($chk > 0){
						$chk = 0;
						array_push($chart, [
						'name' 		=> $chkParty,
						'y' 	=>	$totaldue,
						]);
					}
					$total = 0;
					$totaldue = 0;
					$totalpaid = 0;
				}
				
				if($ReportType == "Overdue" && $overdueDays > 0 && $dueAmt > 0){
				    $chkid = $value["AccountID"];
				    $chkParty = $value["company"];
					$chk = 1;
					$totaldue += $dueAmt;
					$totalpaid += $value["PaidAmt"];
					$total += $value["RndAmt"];
				}
				
				
				
				// for last party total row
				if($TotalRecord == $i && $chk > 0){
					array_push($chart, [
					'name' 		=> $value["company"],
					'y' 	=>	$totaldue,
					]);
				}
				$i++;
			}
			
			$MaxCount = $this->input->post('MaxCount');
			
			usort($chart, function($a, $b) {
				return $b['y'] <=> $a['y'];
			});
			
			// Limit to MaxCount
			$MaxCount = (int)$this->input->post('MaxCount');
			$chart = array_slice($chart, 0, $MaxCount);
			// echo "<pre>";print_r($chart);die;
			
			$data = [
			'ChartData' => $chart,
			];
			
			echo json_encode($data);
		}
		
		public function ExportBillsReceivableReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date')
				);
				$ReportType = $this->input->post('ReportType');
				$body_data = $this->sale_reports_model->GetBillsReceivableBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Trade Receivable Report';
				$colspan = '9';
				
				
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = $AccountDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				
				$set_col_tk = [];
				
				$set_col_tk["Date"] = 'Date';
				$set_col_tk["Customer"] = 'Customer';
				$set_col_tk["Invoice No."] = 'Invoice No.';
				$set_col_tk["Invoice Amount"] = 'Invoice Amount';
				$set_col_tk["Paid Amount"] = 'Paid Amount';
				$set_col_tk["SaleRtn Amt"] = 'SaleRtn Amt';
				$set_col_tk["Credit Note Amt"] = 'Credit Note Amt';
				$set_col_tk["Journal Amt"] = 'Journal Amt';
				$set_col_tk["Due Amount"] = 'Due Amount';
				$set_col_tk["Due On"] = 'Due On';
				$set_col_tk["Over Due By Days"] = 'Over Due By Days';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				$i = 1;
				$chkid = '';
				$total = 0;
				$totaldue = 0;
				$totalpaid = 0;
				$totalcdnote = 0;
				$totaljournal = 0;
				$totalsalertn = 0;
				
				$Grandtotal = 0;
				$Grandtotaldue = 0;
				$Grandtotalpaid = 0;
				$Grandtotalcdnote = 0;
				$Grandtotaljournal = 0;
				$Grandtotalsalertn = 0;
				
				$chk=0;
				$TotalRecord = count($body_data);
				foreach ($body_data as $key => $value) {
					
					
					$dueAmt = $value["RndAmt"]-$value["PaidAmt"]-$value["CDNoteAmt"]-$value["SaleRtnAmt"]-($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
					$transdate = $value["Transdate"];
					
					// Assuming $value["payment_term"] is the number of days
					$paymentTerm = $value["MaxDays"];
					
					// Calculate the next date based on current date and payment term
					$nextDateTimestamp = strtotime($transdate . " + $paymentTerm days");
					
					// Format the timestamp to the desired date format
					$nextDate = date('d-M-y', $nextDateTimestamp);
					
					// Get the current timestamp
					$currentTimestamp = time();
					
					// Calculate the difference in seconds between current date and next date
					$differenceInSeconds = $currentTimestamp - $nextDateTimestamp;
					
					// Convert the difference to days
					$overdueDays = ceil($differenceInSeconds / (60 * 60 * 24)); // ceil to round up to the nearest whole day
					
					if($chkid != $value["AccountID"])
					{
						if($chk > 0){
							$chk = 0;
							$list_add = [];
							$list_add[] = '';
							$list_add[] = '';
							$list_add[] = 'Total';
							$list_add[] = number_format($total, 2, ".", "");
							$list_add[] = number_format($totalpaid, 2, ".", "");
							$list_add[] = number_format($totalsalertn, 2, ".", "");
							$list_add[] = number_format($totalcdnote, 2, ".", "");
							$list_add[] = number_format($totaljournal, 2, ".", "");
							$list_add[] = number_format($totaldue, 2, ".", "");
							$list_add[] = '';
							$list_add[] = '';
							$writer->writeSheetRow('Sheet1', $list_add);
						}
						$total = 0;
						$totaldue = 0;
						$totalpaid = 0;
						$totalcdnote = 0;
						$totaljournal = 0;
						$totalsalertn = 0;
					}
					
					if($ReportType == "Overdue" && $overdueDays > 0 && $dueAmt > 0){
						$chkid = $value["AccountID"];
						$chk = 1;
						$totaldue += $dueAmt;
						$totalpaid += $value["PaidAmt"];
						$totalsalertn += $value["SaleRtnAmt"];
						$totaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
						$totalcdnote += $value["CDNoteAmt"];
						$total += $value["RndAmt"];
						$list_add = [];
						$list_add[] = _d(substr($value["Transdate"],0,10));
						$list_add[] = $value["company"];
						$list_add[] = $value["SalesID"];
						$list_add[] = number_format($value["RndAmt"], 2, ".", "");
						$list_add[] = number_format($value["PaidAmt"], 2, ".", "");
						$list_add[] = number_format($value["SaleRtnAmt"], 2, ".", "");
						$list_add[] = number_format($value["CDNoteAmt"], 2, ".", "");
						$list_add[] = number_format(($value["JournalCreditAmt"] - $value["JournalDebitAmt"]), 2, ".", "");
						$list_add[] = number_format($dueAmt, 2, ".", "");
						$list_add[] = $nextDate;
						if($dueAmt >0 && $overdueDays >0)
						{
							$list_add[] = $overdueDays;
						}
						else
						{
							$list_add[] = '';
						}
						
						$writer->writeSheetRow('Sheet1', $list_add);
					}
					
					if($ReportType == "NonOverdue" && $overdueDays <= 0){
						$chkid = $value["AccountID"];
						$chk = 1;
						$totaldue += $dueAmt;
						$totalpaid += $value["PaidAmt"];
						$totalsalertn += $value["SaleRtnAmt"];
						$totaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
						$totalcdnote += $value["CDNoteAmt"];
						$total += $value["RndAmt"];
						$list_add = [];
						$list_add[] = _d(substr($value["Transdate"],0,10));
						$list_add[] = $value["company"];
						$list_add[] = $value["SalesID"];
						$list_add[] = number_format($value["RndAmt"], 2, ".", "");
						$list_add[] = number_format($value["PaidAmt"], 2, ".", "");
						$list_add[] = number_format($value["SaleRtnAmt"], 2, ".", "");
						$list_add[] = number_format($value["CDNoteAmt"], 2, ".", "");
						$list_add[] = number_format(($value["JournalCreditAmt"] - $value["JournalDebitAmt"]), 2, ".", "");
						$list_add[] = number_format($dueAmt, 2, ".", "");
						$list_add[] = $nextDate;
						if($dueAmt >0 && $overdueDays >0)
						{
							$list_add[] = $overdueDays;
						}
						else
						{
							$list_add[] = '';
						}
						
						$writer->writeSheetRow('Sheet1', $list_add);
					}
					if(empty($ReportType)){
						$chkid = $value["AccountID"];
						$chk = 1;
						$totaldue += $dueAmt;
						$totalpaid += $value["PaidAmt"];
						$totalsalertn += $value["SaleRtnAmt"];
						$totaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
						$totalcdnote += $value["CDNoteAmt"];
						$total += $value["RndAmt"];
						$list_add = [];
						$list_add[] = _d(substr($value["Transdate"],0,10));
						$list_add[] = $value["company"];
						$list_add[] = $value["SalesID"];
						$list_add[] = number_format($value["RndAmt"], 2, ".", "");
						$list_add[] = number_format($value["PaidAmt"], 2, ".", "");
						$list_add[] = number_format($value["SaleRtnAmt"], 2, ".", "");
						$list_add[] = number_format($value["CDNoteAmt"], 2, ".", "");
						$list_add[] = number_format(($value["JournalCreditAmt"] - $value["JournalDebitAmt"]), 2, ".", "");
						$list_add[] = number_format($dueAmt, 2, ".", "");
						$list_add[] = $nextDate;
						if($dueAmt >0 && $overdueDays >0)
						{
							$list_add[] = $overdueDays;
						}
						else
						{
							$list_add[] = '';
						}
						
						$writer->writeSheetRow('Sheet1', $list_add);
					}
					
					
					// for last party total row
					if($TotalRecord == $i && $chk > 0){
						$list_add = [];
						$list_add[] = '';
						$list_add[] = '';
						$list_add[] = 'Total';
						$list_add[] = number_format($total, 2, ".", "");
						$list_add[] = number_format($totalpaid, 2, ".", "");
						$list_add[] = number_format($totalsalertn, 2, ".", "");
						$list_add[] = number_format($totalcdnote, 2, ".", "");
						$list_add[] = number_format($totaljournal, 2, ".", "");
						$list_add[] = number_format($totaldue, 2, ".", "");
						$list_add[] = '';
						$list_add[] = '';
						$writer->writeSheetRow('Sheet1', $list_add);
					}
					
					$Grandtotaldue += $dueAmt;
					$Grandtotalpaid += $value["PaidAmt"];
					$Grandtotalsalertn += $value["SaleRtnAmt"];
					$Grandtotaljournal += ($value["JournalCreditAmt"] - $value["JournalDebitAmt"]);
					$Grandtotalcdnote += $value["CDNoteAmt"];
					$Grandtotal += $value["RndAmt"];
					
					$i++;
				}
				
				
				
				$list_add = [];
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = 'Total';
				$list_add[] = number_format($Grandtotal, 2, ".", "");
				$list_add[] = number_format($Grandtotalpaid, 2, ".", "");
				$list_add[] = number_format($Grandtotalsalertn, 2, ".", "");
				$list_add[] = number_format($Grandtotalcdnote, 2, ".", "");
				$list_add[] = number_format($Grandtotaljournal, 2, ".", "");
				$list_add[] = number_format($Grandtotaldue, 2, ".", "");
				$list_add[] = '';
				$list_add[] = '';
				$writer->writeSheetRow('Sheet1', $list_add);
				
				// Footer Data
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'TradeReceivableReport.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		//=================== Load Daily Sale Summary Page =============================
		public function daily_sale_summary()
		{
			if (!has_permission_new('daily_sale_summary', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Daily Sales Summary Reports";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/daily_sale_summary', $data);
		}
		//=========================== Get Daily Sale Summary  ==========================	
		public function load_data_daily_sale()
		{
			$finacial_year = $this->session->userdata('finacial_year');
			$data = array(
			'from_date' => $this->input->post('from_date'),
			);
			$PartyList = $this->sale_reports_model->GetAllPartyList();
			$GetPreLedgerEntry = $this->sale_reports_model->GetPreLedgerEntry($data);
			$GetLedgerEntry = $this->sale_reports_model->GetLedgerEntry($data);
			$GetPartyOpnBal = $this->sale_reports_model->GetPartyOpnBal($data);
			$html = "";
			$i = 1;
			$GrandOpnBal = 0;
			$GrandSaleAmt = 0;
			$GrandPaymentAmt = 0;
			$GrandReceiptsAmt = 0;
			$GrandJVAmt = 0;
			$GrandSRAmt = 0;
			$GrandActWiseClsBal = 0;
			foreach($PartyList as $Key=>$val)
			{
				$CrAmt = 0;
				$DrAmt = 0;
				$OpnBal = 0;
				foreach($GetPreLedgerEntry as $KPreLed =>$VPreLed){
					if($VPreLed["AccountID"] == $val["AccountID"] && $VPreLed["TType"] == "C"){
						$CrAmt = $VPreLed["TotalAmt"];
					}
					if($VPreLed["AccountID"] == $val["AccountID"] && $VPreLed["TType"] == "D"){
						$DrAmt = $VPreLed["TotalAmt"];
					}
				}
				foreach($GetPartyOpnBal as $KOpnBal =>$VOpnBal){
					if($VOpnBal["AccountID"] == $val["AccountID"]){
						$OpnBal = $VOpnBal["BAL1"];
					}
				}
				$TotalOpnBal = ($OpnBal + $DrAmt ) - $CrAmt;
				$GrandOpnBal += $TotalOpnBal;
				if($TotalOpnBal <= 0){
					$OpnCrDr = " Cr";
					$Color = "green";
					}else{
					$OpnCrDr = " Dr";
					$Color = "red";
				}
				if($val["LocationTypeID"] == "1"){
					$LocationName = "Local";
					}else if($val["LocationTypeID"] == "2"){
					$LocationName = "Out Station";
					}else{
					$LocationName = "Not Defined";
				}
				$html .= '<tr>';
				$html .= '<td class="table_data fontsize" style="text-align:left;">'.$i.'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:left;" title="'.$val["company"].'">'.substr($val["company"],0,60).'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:left;">'.$LocationName.'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;color:'.$Color.'">'.number_format(abs($TotalOpnBal), 2, ".", "").$OpnCrDr.'</td>';
				$SCrAmt = 0;$SDrAmt = 0;$PCrAmt = 0;$PDrAmt = 0;$RCrAmt = 0;$RDrAmt = 0;$JCrAmt = 0;$JDrAmt = 0;$SRCrAmt = 0;$SRDrAmt = 0;
				$CDCrAmt = 0;
				$CDDrAmt = 0;
				$ActWiseClsBal = 0;
				foreach($GetLedgerEntry as $KLed=>$VLedg){
					// Sale Amount Calculation
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "SALE"){
						$SCrAmt += $VLedg["TotalAmt"];
					}
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "SALE"){
						$SDrAmt += $VLedg["TotalAmt"];
					}
					// Payment Amount Calculation
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "PAYMENTS"){
						$PCrAmt += $VLedg["TotalAmt"];
					}
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "PAYMENTS"){
						$PDrAmt += $VLedg["TotalAmt"];
					}
					// Receipt Amount Calculation
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "RECEIPTS"){
						$RCrAmt += $VLedg["TotalAmt"];
					}
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "RECEIPTS"){
						$RDrAmt += $VLedg["TotalAmt"];
					}
					// Journal Amount Calculation
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "JOURNAL"){
						$JCrAmt += $VLedg["TotalAmt"];
					}
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "JOURNAL"){
						$JDrAmt += $VLedg["TotalAmt"];
					}
					
					// Sale Return Amount Calculation
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "SALESRTN"){
						$SRCrAmt += $VLedg["TotalAmt"];
					}
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "SALESRTN"){
						$SRDrAmt += $VLedg["TotalAmt"];
					}
					// CD Note Amount Calculation
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "CDNOTE"){
						$CDCrAmt += $VLedg["TotalAmt"];
					}
					if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "CDNOTE"){
						$CDDrAmt += $VLedg["TotalAmt"];
					}
				}
				$TotalSale = $SDrAmt - $SCrAmt;
				$GrandSaleAmt += $TotalSale;
				
				$TotalPayments = $PDrAmt - $PCrAmt;
				$GrandPaymentAmt += $TotalPayments;
				
				$TotalReceipt =  $RCrAmt - $RDrAmt;
				$GrandReceiptsAmt += $TotalReceipt;
				
				$TotalJV =  $JDrAmt - $JCrAmt;
				$GrandJVAmt += $TotalJV;
				
				$TotalSR =  $SRCrAmt - $SRDrAmt;
				$GrandSRAmt += $TotalSR;
				
				$TotalCD =  $CDCrAmt - $CDDrAmt;
				$GrandCDAmt += $TotalCD;
				
				$ActWiseClsBal = $TotalOpnBal + $TotalSale + $TotalPayments - $TotalReceipt + $TotalJV - $TotalSR - $TotalCD;
				if($ActWiseClsBal <= 0){
					$OpnCrDr = " Cr";
					$Color = "green";
					}else{
					$OpnCrDr = " Dr";
					$Color = "red";
				}
				$html .= '<td class="table_data fontsize" style="text-align:right;">'.number_format($TotalSale, 2, ".", "").'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;">'.number_format($TotalPayments, 2, ".", "").'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;">'.number_format($TotalReceipt, 2, ".", "").'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;">'.number_format($TotalJV, 2, ".", "").'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;">'.number_format($TotalSR, 2, ".", "").'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;">'.number_format($TotalCD, 2, ".", "").'</td>';
				$html .= '<td class="table_data fontsize" style="text-align:right;color:'.$Color.'">'.number_format(abs($ActWiseClsBal), 2, ".", "").$OpnCrDr.'</td>';
				
				$html .= '</tr>';
				$i++;
			}
			if($GrandOpnBal <= 0){
				$OpnCrDr = " Cr";
				$Color = "green";
				}else{
				$OpnCrDr = " Dr";
				$Color = "red";
			}
			$GrandActWiseClsBal = $GrandOpnBal + $GrandSaleAmt + $GrandPaymentAmt - $GrandReceiptsAmt + $GrandJVAmt - $GrandSRAmt - $GrandCDAmt;
			$html .= '<tr>';
			$html .= '<td colspan ="3" class="table_data fontsize" style="text-align:right;font-size:14px;"><b>Total</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;color:'.$Color.'"><b>'.number_format(abs($GrandOpnBal), 2, ".", "").$OpnCrDr.'</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;"><b>'.number_format($GrandSaleAmt, 2, ".", "").'</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;"><b>'.number_format($GrandPaymentAmt, 2, ".", "").'</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;"><b>'.number_format($GrandReceiptsAmt, 2, ".", "").'</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;"><b>'.number_format($GrandJVAmt, 2, ".", "").'</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;"><b>'.number_format($GrandSRAmt, 2, ".", "").'</b></td>';
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;"><b>'.number_format(abs($GrandCDAmt), 2, ".", "").'</b></td>';
			if($GrandActWiseClsBal <= 0){
				$OpnCrDr = " Cr";
				$Color = "green";
				}else{
				$OpnCrDr = " Dr";
				$Color = "red";
			}
			$html .= '<td class="table_data fontsize" style="text-align:right;font-size:14px;color:'.$Color.'"><b>'.number_format(abs($GrandActWiseClsBal), 2, ".", "").$OpnCrDr.'</b></td>';
			$html .= '</tr>';
			echo $html;
		}
		
		//=========================== Daily Sale Summary Export ========================	
		public function export_daily_sale_summary()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 11);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 11);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Daily Sales Summary Report ".$this->input->post('from_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 11);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				
				
				$set_col_tk = [];
				$set_col_tk["Sr.No."] = 'Sr. No.';
				$set_col_tk["Party Name"] = 'Party Name';
				$set_col_tk["Location Type"] = 'Location Type';
				$set_col_tk["Opening Balance"] = 'Opening Balance';
				$set_col_tk["Sale Amount"] = 'Sale Amount';
				$set_col_tk["Payment Amount"] = 'Payment Amount';
				$set_col_tk["Receive Amount"] = 'Received Amount';
				$set_col_tk["Journal Amount"] = 'Journal Amount';
				$set_col_tk["Sale Return Amount"] = 'Sale Return Amount';
				$set_col_tk["CD Note Amount"] = 'CD Note Amount';
				$set_col_tk["Closing Balance"] = 'Closing Balance';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$finacial_year = $this->session->userdata('finacial_year');
				$data = array(
				'from_date' => $this->input->post('from_date'),
				);
				$PartyList = $this->sale_reports_model->GetAllPartyList();
				$GetPreLedgerEntry = $this->sale_reports_model->GetPreLedgerEntry($data);
				$GetLedgerEntry = $this->sale_reports_model->GetLedgerEntry($data);
				$GetPartyOpnBal = $this->sale_reports_model->GetPartyOpnBal($data);
				
				$GrandOpnBal = 0;
				$GrandSaleAmt = 0;
				$GrandPaymentAmt = 0;
				$GrandReceiptsAmt = 0;
				$GrandJVAmt = 0;
				$GrandSRAmt = 0;
				$GrandActWiseClsBal = 0;
				$i = 1;
				foreach($PartyList as $Key=>$val)
				{
					$CrAmt = 0;
					$DrAmt = 0;
					$OpnBal = 0;
					foreach($GetPreLedgerEntry as $KPreLed =>$VPreLed){
						if($VPreLed["AccountID"] == $val["AccountID"] && $VPreLed["TType"] == "C"){
							$CrAmt = $VPreLed["TotalAmt"];
						}
						if($VPreLed["AccountID"] == $val["AccountID"] && $VPreLed["TType"] == "D"){
							$DrAmt = $VPreLed["TotalAmt"];
						}
					}
					foreach($GetPartyOpnBal as $KOpnBal =>$VOpnBal){
						if($VOpnBal["AccountID"] == $val["AccountID"]){
							$OpnBal = $VOpnBal["BAL1"];
						}
					}
					$TotalOpnBal = ($OpnBal + $DrAmt ) - $CrAmt;
					$GrandOpnBal += $TotalOpnBal;
					if($val["LocationTypeID"] == "1"){
						$LocationName = "Local";
						}else if($val["LocationTypeID"] == "2"){
						$LocationName = "Out Station";
						}else{
						$LocationName = "Not Defined";
					}
					$list_add = [];
					$list_add[] = $i;
					$list_add[] = $val["company"];
					$list_add[] = $LocationName;
					$list_add[] = number_format($TotalOpnBal, 2, ".", "");
					
					$SCrAmt = 0;$SDrAmt = 0;$PCrAmt = 0;$PDrAmt = 0;$RCrAmt = 0;$RDrAmt = 0;$JCrAmt = 0;$JDrAmt = 0;$SRCrAmt = 0;$SRDrAmt = 0;
					$CDCrAmt = 0;
					$CDDrAmt = 0;
					$ActWiseClsBal = 0;
					foreach($GetLedgerEntry as $KLed=>$VLedg){
						// Sale Amount Calculation
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "SALE"){
							$SCrAmt += $VLedg["TotalAmt"];
						}
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "SALE"){
							$SDrAmt += $VLedg["TotalAmt"];
						}
						// Payment Amount Calculation
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "PAYMENTS"){
							$PCrAmt += $VLedg["TotalAmt"];
						}
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "PAYMENTS"){
							$PDrAmt += $VLedg["TotalAmt"];
						}
						// Receipt Amount Calculation
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "RECEIPTS"){
							$RCrAmt += $VLedg["TotalAmt"];
						}
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "RECEIPTS"){
							$RDrAmt += $VLedg["TotalAmt"];
						}
						// Journal Amount Calculation
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "JOURNAL"){
							$JCrAmt += $VLedg["TotalAmt"];
						}
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "JOURNAL"){
							$JDrAmt += $VLedg["TotalAmt"];
						}
						
						// Sale Return Amount Calculation
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "SALESRTN"){
							$SRCrAmt += $VLedg["TotalAmt"];
						}
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "SALESRTN"){
							$SRDrAmt += $VLedg["TotalAmt"];
						}
						// CD Note Amount Calculation
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "C" && $VLedg["PassedFrom"] == "CDNOTE"){
							$CDCrAmt += $VLedg["TotalAmt"];
						}
						if($VLedg["AccountID"] == $val["AccountID"] && $VLedg["TType"] == "D" && $VLedg["PassedFrom"] == "CDNOTE"){
							$CDDrAmt += $VLedg["TotalAmt"];
						}
					}
					$TotalSale = $SDrAmt - $SCrAmt;
					$GrandSaleAmt += $TotalSale;
					
					$TotalPayments = $PDrAmt - $PCrAmt;
					$GrandPaymentAmt += $TotalPayments;
					
					$TotalReceipt =  $RCrAmt - $RDrAmt;
					$GrandReceiptsAmt += $TotalReceipt;
					
					$TotalJV =  $JDrAmt - $JCrAmt;
					$GrandJVAmt += $TotalJV;
					
					$TotalSR =  $SRCrAmt - $SRDrAmt;
					$GrandSRAmt += $TotalSR;
					
					$TotalCD =  $CDCrAmt - $CDDrAmt;
					$GrandCDAmt += $TotalCD;
					
					$ActWiseClsBal = $TotalOpnBal + $TotalSale + $TotalPayments - $TotalReceipt + $TotalJV - $TotalSR - $TotalCD;
					
					$list_add[] = number_format($TotalSale, 2, ".", "");
					$list_add[] = number_format($TotalPayments, 2, ".", "");
					$list_add[] = number_format($TotalReceipt, 2, ".", "");
					$list_add[] = number_format($TotalJV, 2, ".", "");
					$list_add[] = number_format($TotalSR, 2, ".", "");
					$list_add[] = number_format($TotalCD, 2, ".", "");
					$list_add[] = number_format($ActWiseClsBal, 2, ".", "");
					
					$writer->writeSheetRow('Sheet1', $list_add);
					$i++;
				}
				$GrandActWiseClsBal = $GrandOpnBal + $GrandSaleAmt + $GrandPaymentAmt - $GrandReceiptsAmt + $GrandJVAmt - $GrandSRAmt - $GrandCDAmt;
				$list_add = [];
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = number_format($GrandOpnBal, 2, ".", "");
				$list_add[] = number_format($GrandSaleAmt, 2, ".", "");
				$list_add[] = number_format($GrandPaymentAmt, 2, ".", "");
				$list_add[] = number_format($GrandReceiptsAmt, 2, ".", "");
				$list_add[] = number_format($GrandJVAmt, 2, ".", "");
				$list_add[] = number_format($GrandSRAmt, 2, ".", "");
				$list_add[] = number_format($GrandCDAmt, 2, ".", "");
				$list_add[] = number_format($GrandActWiseClsBal, 2, ".", "");
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'DailySaleSummary_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function daily_ItemWise_sale_summary_report()
		{
			if (!has_permission_new('daily_ItemWise_sale_summary_report', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Daily Item Wise Sale reports";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/daily_ItemWise_sale_summary_report', $data);
		}
		
		public function load_data_daily_ItemWise_sale_report()
		{
			$finacial_year = $this->session->userdata('finacial_year');
			$data = array(
			'from_date' => $this->input->post('from_date'),
			);
			$result = $this->sale_reports_model->load_data_daily_ItemWise_sale_report($data);
			// echo "<pre>";print_r($result);die;
			echo json_encode($result);
		}
		
		public function export_daily_ItemWise_sale_summary_report()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				
				
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Sales Report ".$this->input->post('from_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				// empty row
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Sr. No."] = 'Sr. No.';
				$set_col_tk["Client Name"] = 'Item Name';
				$set_col_tk["Pkt Qty"] = 'Pkt Qty';
				$set_col_tk["CS/CR Qty"] = 'CS/CR Qty';
				$set_col_tk["Weight"] = 'Weight';
				$set_col_tk["Amount"] = 'Amount';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$finacial_year = $this->session->userdata('finacial_year');
				$data = array(
				'from_date' => $this->input->post('from_date'),
				);
				$result = $this->sale_reports_model->load_data_daily_ItemWise_sale_report($data);
				
				$from_date = to_sql_date($data_filter['from_date']) . ' 00:00:00';
				$from_date = date('Y-m-d',strtotime($from_date));
				
				$totalunitsale = 0;		
				$totalweight = 0;		
				$totalsale = 0;		
				$totalAmount = 0;		
				$sr = 1;		
				foreach($result as $each)
				{
					if($each["Total_unit"] > 0)
					{
						$list_add = [];
						$list_add[] = $sr;
						$list_add[] = $each["description"];
						$list_add[] = number_format($each["Total_unit"], 2, '.', '');
						$list_add[] = number_format($each["Total_Sale"], 2, '.', '');
						$list_add[] = number_format($each["Total_weight"], 2, '.', '');
						$list_add[] = number_format($each["Amount"], 2, '.', '');
						
						$writer->writeSheetRow('Sheet1', $list_add);
						
						$totalsale += number_format($each["Total_Sale"], 2, '.', '');
						$totalunitsale += number_format($each["Total_unit"], 2, '.', '');
						$totalweight += number_format($each["Total_weight"], 2, '.', '');
						$totalAmount += number_format($each["Amount"], 2, '.', '');
						$sr++;
					}
				}
				
				$list_add = [];
				$list_add[] = '';
				$list_add[] = 'Total';
				$list_add[] = number_format($totalunitsale, 2, '.', '');
				$list_add[] = number_format($totalsale, 2, '.', '');
				$list_add[] = number_format($totalweight, 2, '.', '');
				$list_add[] = number_format($totalAmount, 2, '.', '');
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'DailySale_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		// Get Result for SaleVsSaleRtn
		public function GetSaleRtnReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'AccountID'  => $this->input->post('AccountID'),
			'AccountName'  => $this->input->post('AccountName'),
			'AccountAddress2'  => $this->input->post('AccountAddress2'),
			'AccountCity'  => $this->input->post('AccountCity')
			);
			$AccountID = $this->input->post('AccountID');
			$body_data = $this->sale_reports_model->GetSaleRtnBodyData($filterdata);
			
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">SaleRtnID</th>';
			$html .= '<th class="sortable" align="center">ReturnDate</th>';
			
			if($AccountID !==''){
				$html .= '<th class="sortable" align="left">Item Name</th>';
				$html .= '<th class="sortable" align="left">Billed Qty</th>';
				$html .= '<th class="sortable" align="center">Cases</th>';
				}else{
				
				$html .= '<th class="sortable" align="left">Account Name</th>';
				$html .= '<th class="sortable" align="left">Address</th>';
				$html .= '<th class="sortable" align="center">GSTNO</th>';
				
			}
			$html .= '<th class="sortable" align="center">NetRtnAmt</th>';
			$html .= '<th class="sortable" align="center">CGSTAmt</th>';
			$html .= '<th class="sortable" align="center">SGSTAmt</th>';
			$html .= '<th class="sortable" align="center">IGSTAmt</th>';
			$html .= '<th class="sortable" align="center">FinalRtnAmt</th>';
			
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			$billedQtySum = 0;
			$casesSum = 0;
			$NetRtnAmtSum = 0;
			$cgstAmtSum = 0;
			$sgstAmtSum = 0;
			$igstAmtSum = 0;
			$FinalAmtSum = 0;
			foreach ($body_data as $key => $value) {
				$html .= '<tr>';
				$html .= '<td align="center">'.$i.'</td>';
				$html .= '<td align="center">'.$value["OrderID"].'</td>';
				$html .= '<td align="center">'._d(substr($value["TransDate2"],0,10)).'</td>';
				if($AccountID !==''){
					$html .= '<td align="left">'.$value["description"].'</td>';
					$html .= '<td align="right">'. $value["BilledQty"].'</td>';
					$billedQtySum += $value["BilledQty"];
					$cases = $value["BilledQty"] / $value["CaseQty"];
					$html .= '<td align="right">'.$cases.'</td>';
					$casesSum += $cases;
					}else{
					$html .= '<td align="left">'.$value["company"].'</td>';
					$html .= '<td align="left">'. substr($value["address"],0,25).'</td>';
					$html .= '<td align="center">'.$value["vat"].'</td>';
				}
				
				$html .= '<td align="right">'.$value["ChallanAmt"].'</td>';
				$NetRtnAmtSum += $value["ChallanAmt"];
				$html .= '<td align="right">'.$value["cgstamtSum"].'</td>';
				$cgstAmtSum += $value["cgstamtSum"];
				$html .= '<td align="right">'.$value["sgstamtSum"].'</td>';
				$sgstAmtSum += $value["sgstamtSum"];
				$html .= '<td align="right">'.$value["igstamtSum"].'</td>';
				$igstAmtSum += $value["igstamtSum"];
				$html .= '<td align="right">'.number_format(round($value["NetChallanAmt"]), 2, '.', '').'</td>';
				$FinalAmtSum += number_format(round($value["NetChallanAmt"]), 2, '.', '');
				$html .= '</tr>';
				$i++;
			}
			$html .= '</tbody>';
			$html .= '<tfoot>';
			$html .= '<tr>';
			$html .= '<td align="right"></td>';
			$html .= '<td align="right">Total</td>';
			$html .= '<td align="right"></td>';
			if($AccountID !==''){
				$html .= '<td align="left"></td>';
				$html .= '<td align="right">'.$billedQtySum.'</td>';
				$html .= '<td align="right">'.$casesSum.'</td>';
				}else{
				$html .= '<td align="left"></td>';
				$html .= '<td align="left"></td>';
				$html .= '<td align="left"></td>';
			}
			$html .= '<td align="right">'.$NetRtnAmtSum.'</td>';
			$html .= '<td align="right">'.$cgstAmtSum.'</td>';
			$html .= '<td align="right">'.$sgstAmtSum.'</td>';
			$html .= '<td align="right">'.$igstAmtSum.'</td>';
			$html .= '<td align="right">'.$FinalAmtSum.'</td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			
			$html .= '</table>';
			
			echo json_encode($html);
			die;
		}
		
		public function ExportSaleRtnReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'AccountID'  => $this->input->post('AccountID'),
				'AccountName'  => $this->input->post('AccountName'),
				'AccountAddress2'  => $this->input->post('AccountAddress2'),
				'AccountCity'  => $this->input->post('AccountCity')
				);
				$AccountID = $this->input->post('AccountID');
				$AccountName = $this->input->post('AccountName');
				$body_data = $this->sale_reports_model->GetSaleRtnBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				
				$colspan = 9;
				if($AccountID !==''){
					$AccountDetails = 'Account Name : '.$AccountName.' ,  Report Date : '.$this->input->post('from_date').' To '. $this->input->post('to_date');
					
					}else{
					$AccountDetails = 'Account Name : All Accounts , Report Date : '.$this->input->post('from_date').' To '. $this->input->post('to_date');
				} 
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg  = 'Account Name : '.$AccountName.' ,  Report Date : ';
				$filter = array($AccountDetails);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$set_col_tk = [];
				$set_col_tk["SaleRtnID"] = 'SaleRtnID';
				$set_col_tk["ReturnDate"] = 'ReturnDate';
				
				if($AccountID !==''){
					$set_col_tk["ItemName"] = 'Item Name';
					$set_col_tk["BilledQty"] = 'Billed Qty';
					$set_col_tk["Cases"] = 'Cases';
					}else{
					$set_col_tk["AccountName"] = 'Account Name';
					$set_col_tk["Address"] = 'Address';
					$set_col_tk["GSTNO"] = 'GSTNO';
				}
				$set_col_tk["NetRtnAmt"] = 'NetRtnAmt';
				$set_col_tk["CGSTAmt"] = 'CGSTAmt';
				$set_col_tk["SGSTAmt"] = 'SGSTAmt';
				$set_col_tk["IGSTAmt"] = 'IGSTAmt';
				$set_col_tk["FinalRtnAmt"] = 'FinalRtnAmt';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 1;
				$billedQtySum = 0;
				$casesSum = 0;
				$NetRtnAmtSum = 0;
				$cgstAmtSum = 0;
				$sgstAmtSum = 0;
				$igstAmtSum = 0;
				$FinalAmtSum = 0;
				
				foreach ($body_data as $key => $value) {
					
					$list_add = [];
					$list_add[] = $value["OrderID"];
					$list_add[] = _d(substr($value["TransDate2"],0,10));
					
					if($AccountID !==''){
						$list_add[] = $value["description"];
						$list_add[] = $value["BilledQty"];
						$billedQtySum += $value["BilledQty"];
						$cases = $value["BilledQty"] / $value["CaseQty"];
						$list_add[] = $cases;
						$casesSum += $cases;
						}else{
						$list_add[] = $value["company"];
						$list_add[] = $value["address"];
						$list_add[] = $value["vat"];
					}
					
					$list_add[] = $value["ChallanAmt"];
					$NetRtnAmtSum += $value["ChallanAmt"];
					
					$list_add[] = $value["cgstamtSum"];
					$cgstAmtSum += $value["cgstamtSum"];
					
					$list_add[] = $value["sgstamtSum"];
					$sgstAmtSum += $value["sgstamtSum"];
					
					$list_add[] = $value["igstamtSum"];
					$igstAmtSum += $value["igstamtSum"];
					
					$list_add[] = number_format(round($value["NetChallanAmt"]), 2, '.', '');
					$FinalAmtSum += number_format(round($value["NetChallanAmt"]), 2, '.', '');
					
					$writer->writeSheetRow('Sheet1', $list_add);
					
				}
				
				
				// Footer Data
				$list_add = [];
				$list_add[] = 'Total';
				$list_add[] = '';
				if($AccountID !==''){
					$list_add[] = '';
					$list_add[] = $billedQtySum;
					$list_add[] = $casesSum;
					}else{
					$list_add[] = '';
					$list_add[] = '';
					$list_add[] = '';
				}    
				$list_add[] = $NetRtnAmtSum;
				$list_add[] = $cgstAmtSum;
				$list_add[] = $sgstAmtSum;
				$list_add[] = $igstAmtSum;
				$list_add[] = $FinalAmtSum;
				
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'SaleRtn.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function OrderVsDispatchItemWise()
		{
			if (!has_permission_new('OrderVsDispatchItemWise', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Order Vs Dispatch ItemWise";
			$this->load->model('clients_model');
			$data['states'] = $this->clients_model->getallstate();
			$data['groups'] = $this->clients_model->get_groups();
			$id = 8;
			$data['SO'] = $this->sale_reports_model->GetSOList($id);
			$data['PartyList'] = $this->sale_reports_model->GetPartyList();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/OrderVsDispatchItemWise', $data);
		}
		
		public function GetOrderVsDispatchItemWise()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'states'  => $this->input->post('states'),
			'client_type'  => $this->input->post('client_type'),
			'staff_id'  => $this->input->post('staff_id'),
			'AccountID'  => $this->input->post('AccountID')
			);
			
			$company_detail = $this->sale_reports_model->get_company_detail();
			$states = $this->input->post('states');
			$state_name = $this->sale_reports_model->get_state_name($states);
			$AccountID = $this->input->post('AccountID');
			$Partyname = $this->sale_reports_model->GetPartyName($AccountID);
			$client_type = $this->input->post('client_type');
			$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);
			
			$selected_company = $this->session->userdata('root_company');
			
			$tableData = $this->sale_reports_model->GetOrderVsDispatchItemWiseData($filterdata);
			if(empty($tableData)){
				$error = "No record found...";
				echo json_encode($error);
				die;
			}
			
			$html = '';
			$html .= '<table class="table-striped table-bordered daily_report" id="daily_report">';
			$html .= '<thead style="font-size:11px;">';
			
			// Header rows (hidden)
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="8"><b>'.$company_detail->company_name.'</b></th>'; // Changed colspan to 8
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="8"><b>'.$company_detail->address.'</b></th>'; // Changed colspan to 8
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Report Date : </b></th>';
			$html .= '<th>'.$this->input->post('from_date').' To '.$this->input->post('to_date').'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>State : </b></th>';
			$html .= '<th>'.$state_name->state_name.'</th>';
			$html .= '<th></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Distributor Type : </b></th>';
			$html .= '<th>'.$client_type_name->name.'</th>';
			$html .= '<th></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '</tr>';
			
			// Table headers
			$html .= '<tr>';
			$html .= '<th class="sortable">SrNo.</th>';
			$html .= '<th class="sortable">ItemID</th>';
			$html .= '<th class="sortable">Item Name</th>';
			$html .= '<th class="sortable">Pack</th>';
			$html .= '<th class="sortable">Order Qty (Pkt)</th>';
			$html .= '<th class="sortable">Bill Qty (Pkt)</th>';
			$html .= '<th class="sortable">Diff Qty (Pkt)</th>';
			$html .= '<th class="sortable">Fill Rate Gap Percentage</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			
			$html .= '<tbody>';
			$srNo = 1;
			$OrderSum = 0;
			$BillSum = 0;
			$DiffSum = 0;
			
			foreach ($tableData as $key => $value) {
				$OrdCases = $value["OrdQty"]; // Order qty
				$BillCases = $value["BillQty"]; // Bill qty
				$Diff = $BillCases - $OrdCases;
				
				if ($OrdCases != 0) {
					$fillRatePercentage = ($BillCases / $OrdCases) * 100;
					} else {
					$fillRatePercentage = 0;  
				}
				
				$html .= '<tr>';
				$html .= '<td style="text-align:center;">'.$srNo.'</td>';
				$html .= '<td>'.$value["ItemID"].'</td>';
				$html .= '<td>'.$value["description"].'</td>';
				$html .= '<td style="text-align:center;">'.$value["case_qty"].'</td>';
				$html .= '<td style="text-align:right;">'.number_format($OrdCases,2).'</td>';
				$OrderSum += $OrdCases;
				$html .= '<td style="text-align:right;">'.number_format($BillCases,2).'</td>';
				$BillSum += $BillCases;				  
				$html .= '<td style="text-align:right;">'.number_format($Diff,2).'</td>';
				$DiffSum += $Diff;
				$html .= '<td style="text-align:right;">'.number_format($fillRatePercentage,2).' %</td>';  
				$FillRate = ($BillSum / $OrderSum) * 100;
				$html .= '</tr>';
				
				$srNo++;
			}
			
			$html .= '</tbody>';
			$html .= '<tfoot style="font-weight:bold;">';
			$html .= '<tr>';
			$html .= '<td style="text-align:center;"></td>';
			$html .= '<td colspan="3">Total</td>';
			$html .= '<td style="text-align:right;">'.number_format($OrderSum,2).'</td>';
			$html .= '<td style="text-align:right;">'.number_format($BillSum,2).'</td>';
			$html .= '<td style="text-align:right;">'.number_format($DiffSum,2).'</td>';
			$html .= '<td style="text-align:right;">'.number_format($FillRate,2).'</td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			
			echo json_encode($html);
			die;
		}
		
		
		public function GetOrderVsDispatchItemWiseExport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'states'  => $this->input->post('states'),
				'client_type'  => $this->input->post('client_type'),
				'staff_id'  => $this->input->post('staff_id'),
				'AccountID'  => $this->input->post('AccountID')
				);
				
				$company_detail = $this->sale_reports_model->get_company_detail();
				$states = $this->input->post('states');
				$state_name = $this->sale_reports_model->get_state_name($states);
				$AccountID = $this->input->post('AccountID');
				$Partyname = $this->sale_reports_model->GetPartyName($AccountID);
				$client_type = $this->input->post('client_type');
				$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);
				$selected_company = $this->session->userdata('root_company');
				$tableData = $this->sale_reports_model->GetOrderVsDispatchItemWiseData($filterdata);
				
				$writer = new XLSXWriter();
				
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 7);   
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 7);   
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 7);   
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg1 = "State : ".$state_name->state_name." DistributorType : " .$client_type_name->name;
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 7);   
				$writer->writeSheetRow('Sheet1', $filter1);
				
				$msg11 = "Party Name : ".$Partyname->company;
				$filter11 = array($msg11);
				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 7);   
				$writer->writeSheetRow('Sheet1', $filter11);
				
				// Headers with new column
				$set_col_tk = [];
				$set_col_tk["ItemID"] =  'ItemID';
				$set_col_tk["ItemName"] = 'ItemName';
				$set_col_tk["Pack"] = 'Pack';
				$set_col_tk["OrderQty"] = 'OrderQty';
				$set_col_tk["BillQty"] = 'BillQty';
				$set_col_tk["Diff Qty"] = 'Diff Qty';
				$set_col_tk["Fill Rate Gap Percentage"] = 'Fill Rate Gap Percentage';  
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$srNo = 1;
				$OrderQtySum = 0;
				$BillQtySum = 0;
				$DiffQtySum = 0;
				$TotalFillRate = 0;
				
				foreach ($tableData as $key => $value) {
					$list_add = [];
					$list_add[] = $value["ItemID"];
					$list_add[] = $value["description"];
					$list_add[] = $value["case_qty"];
					
					$OrdCases = $value["OrdQty"];
					$list_add[] = $OrdCases;
					$OrderQtySum += $OrdCases;
					
					$BillCases = $value["BillQty"];
					$list_add[] = $BillCases;
					$BillQtySum += $BillCases;
					
					$Diff = $BillCases - $OrdCases;
					$list_add[] = $Diff;
					$DiffQtySum += $Diff;
					
					// Calculate Fill Rate Percentage
					if ($OrdCases != 0) {
						$fillRatePercentage = ($BillCases / $OrdCases) * 100;
						} else {
						$fillRatePercentage = 0;
					}
					$fillRatePer = number_format($fillRatePercentage, 2, '.', '');
					$list_add[] = $fillRatePer;
					
					$TotalFillRate = ($BillQtySum / $OrderQtySum) * 100;
					$TotFillRate = number_format($TotalFillRate, 2, '.', '');
					
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				$footer_style = array(
				'font-style' => 'bold',
				'halign' => 'center',
				'border' => 'left,right,top,bottom',
				);
				
				// Total row
				$list_add = [];
				$list_add[] = 'Total';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = $OrderQtySum;
				$list_add[] = $BillQtySum;
				$list_add[] = $DiffQtySum;
				$list_add[] = $TotFillRate;  
				$writer->writeSheetRow('Sheet1', $list_add, $footer_style);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'OrderVsDispatchItemWise.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		
		
		public function OrderVsDispatch()
		{
			if (!has_permission_new('OrderVsDispatch', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Order Vs Dispatch";
			$this->load->model('clients_model');
			$data['states'] = $this->clients_model->getallstate();
			$data['groups'] = $this->clients_model->get_groups();
			$data['PartyList'] = $this->sale_reports_model->GetPartyList();
			$id = 8;
			$data['SO'] = $this->sale_reports_model->GetSOList($id);
			/* echo '<pre>';
				print_r($data['SO']);
			die;*/
			$cur_date = date('d/m/Y');
			$m = date('m');
			$y = date('Y');
			$date1 = "01/".$m."/".$y;
			$data['from_date'] = $date1;
			$data['to_date'] = $cur_date;
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/OrderVsDispatch', $data);
		}
		public function GetOrderVsDispatch()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'states'  => $this->input->post('states'),
			'client_type'  => $this->input->post('client_type'),
			'AccountID'  => $this->input->post('AccountID'),
			'staff_id'  => $this->input->post('staff_id')
			);
			
			$company_detail = $this->sale_reports_model->get_company_detail();
			$states = $this->input->post('states');
			$state_name = $this->sale_reports_model->get_state_name($states);
			$client_type = $this->input->post('client_type');
			$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);
			
			$selected_company = $this->session->userdata('root_company');
			
			$tableData = $this->sale_reports_model->GetOrderVsDispatchData($filterdata);
			if(empty($tableData)){
				$error = "No record found...";
				echo json_encode($error);
				die;
			}
			
			/*echo json_encode($tableData);
			die;*/
			$html = '';
			$html .= '<table class="table-striped table-bordered daily_report" id="daily_report">';
			$html .= '<thead style="font-size:11px;">';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="11"><b>'.$company_detail->company_name.'</b></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="11"><b>'.$company_detail->address.'</b></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Report Date : </b></th>';
			$html .= '<th>'.$this->input->post('from_date').' To '.$this->input->post('to_date').'</th>';
			$html .= '<th colspan="8"></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>State : </b></th>';
			$html .= '<th>'.$state_name->state_name.'</th>';
			$html .= '<th colspan="8"></th>';
			$html .= '</tr>';
			
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Distributor Type : </b></th>';
			$html .= '<th>'.$client_type_name->name.'</th>';
			$html .= '<th colspan="8"></th>';
			$html .= '</tr>';
			
			
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="11"></th>';
			$html .= '</tr>';
			
			$html .= '<tr>';
			$html .= '<th class="sortable">SrNo.</th>';
			$html .= '<th class="sortable">OrderID</th>';
			$html .= '<th class="sortable">OrderDate</th>';
			$html .= '<th class="sortable">SaleID</th>';
			$html .= '<th class="sortable">InvoiceDate</th>';
			$html .= '<th class="sortable">Tm TakenToBill</th>';
			$html .= '<th class="sortable">GatePass Time</th>';
			$html .= '<th class="sortable">Tm Btn. Bill & Getpass</th>';
			$html .= '<th class="sortable">PartyName</th>';
			$html .= '<th class="sortable">OrderAmt</th>';
			$html .= '<th class="sortable">BillAmt</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$srNo = 1;
			$OrderSum = 0;
			$BillSum = 0;
			$url2 = "_blank";
			foreach ($tableData as $key => $value) {
				$url = admin_url().'order/order/'.$value["OrderID"];
				$html .= '<tr onclick="window.open('."'".$url."'".', '."'".$url2."'".')" >';
				$html .= '<td>'.$srNo.'</td>';
				$html .= '<td>'.$value["OrderID"].'</td>';
				$OrderDate = _d(substr($value["OrderDate"],0,10)).' '.substr($value["OrderDate"],11,8);
				$html .= '<td style="text-align:center;">'.$OrderDate.'</td>';
				$html .= '<td>'.$value["SalesID"].'</td>';
				$SaleDate = _d(substr($value["Transdate"],0,10)).' '.substr($value["Transdate"],11,8);
				$html .= '<td style="text-align:center;">'.$SaleDate.'</td>';
				$Sum = round($value["OrderAmount"]) - round($value["BillAmt"]);
				if($value["Transdate"]!== NULL){
					$datetime_1 = substr($value["OrderDate"],0,19); 
					$datetime_2 = substr($value["Transdate"],0,19);
					$from_time = strtotime($datetime_1); 
					$to_time = strtotime($datetime_2); 
					$minutes = round(abs($from_time - $to_time) / 60,2);
					$d = floor ($minutes / 1440);
					$h = floor (($minutes - $d * 1440) / 60);
					$m = (Int)$minutes - ($d * 1440) - ($h * 60);
					if($d== '0'){
						if($h== '0'){
							$diff_minutes = $m.'m';
							}else{
							$diff_minutes = $h.'h '. $m.'m';
						}
						}else{
						$diff_minutes = $d.'d '. $h.'h '. $m.'m';
					}
					
					}else{
					$diff_minutes = '';
				}
				
				$html .= '<td style="text-align:center;">'.$diff_minutes.'</td>';
				$GatepassDate = _d(substr($value["gatepasstime"],0,10)).' '.substr($value["gatepasstime"],11,8);
				$html .= '<td style="text-align:center;">'.$GatepassDate.'</td>';
				if($value["gatepasstime"]!== NULL){
					$datetime_11 = substr($value["Transdate"],0,19); 
					$datetime_22 = substr($value["gatepasstime"],0,19);
					$from_time1 = strtotime($datetime_11); 
					$to_time1 = strtotime($datetime_22); 
					$minutes1 = round(abs($from_time1 - $to_time1) / 60,2);
					$d1 = floor ($minutes1 / 1440);
					$h1 = floor (($minutes1 - $d1 * 1440) / 60);
					$m1 = (Int)$minutes1 - ($d1 * 1440) - ($h1 * 60);
					if($d1== '0'){
						if($h1== '0'){
							$diff_minutes1 = $m1.'m';
							}else{
							$diff_minutes1 = $h1.'h '. $m1.'m';
						}
						}else{
						$diff_minutes1 = $d1.'d '. $h1.'h '. $m1.'m';
					}
					}else{
					$diff_minutes1 = '';
				}
				
				$html .= '<td style="text-align:center;">'.$diff_minutes1.'</td>';
				$html .= '<td>'.$value["company"].'</td>';
				$html .= '<td style="text-align:right;">'.round($value["OrderAmount"]).'</td>';
				$OrderSum += round($value["OrderAmount"]);
				$html .= '<td style="text-align:right;">'.round($value["BillAmt"]).'</td>';
				$BillSum += round($value["BillAmt"]);
				$html .= '</tr>';
				$srNo++;
			}
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			$html .= '<tr>';
			$html .= '<td colspan="9">Total</td>';
			$html .= '<td style="text-align:right;">'.round($OrderSum).'</td>';
			$html .= '<td style="text-align:right;">'.round($BillSum).'</td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '<table>';
			echo json_encode($html);
			die;
		}
		
		public function GetOrderVsDispatchExport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'states'  => $this->input->post('states'),
				'client_type'  => $this->input->post('client_type'),
				'AccountID'  => $this->input->post('AccountID'),
				'staff_id'  => $this->input->post('staff_id')
				);
				
				$PlantDetail = $this->sale_reports_model->get_company_detail();
				$states = $this->input->post('states');
				$state_name = $this->sale_reports_model->get_state_name($states);
				$client_type = $this->input->post('client_type');
				$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);
				$selected_company = $this->session->userdata('root_company');
				$tableData = $this->sale_reports_model->GetOrderVsDispatchData($filterdata);
				
				$writer = new XLSXWriter();
				
				$company_name = array($PlantDetail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg1 = "State : ".$state_name->state_name." DistributorType : " .$client_type_name->name;
				$filter1 = array($msg1);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter1);
				
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
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["OrderID"] =  'OrderID';
				$set_col_tk["OrderDate"] = 'OrderDate';
				$set_col_tk["SaleID"] = 'SaleID';
				$set_col_tk["InvoiceDate"] = 'InvoiceDate';
				$set_col_tk["Tm TakenToBill"] = 'Tm TakenToBill';
				$set_col_tk["GatePass Time"] = 'GatePass Time';
				$set_col_tk["Tm Btn. Bill & Getpass"] = 'Tm Btn. Bill & Getpass';
				$set_col_tk["PartyName"] = 'PartyName';
				$set_col_tk["OrderAmt"] = 'OrderAmt';
				$set_col_tk["BillAmt"] = 'BillAmt';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$srNo = 1;
				$OrderSum = 0;
				$BillSum = 0;
				foreach ($tableData as $key => $value) {
					$list_add = [];
					$list_add[] = $value["OrderID"];
					$OrderDate = _d(substr($value["OrderDate"],0,10)).' '.substr($value["OrderDate"],11,8);
					$list_add[] = $OrderDate;
					$list_add[] = $value["SalesID"];
					$SaleDate = _d(substr($value["Transdate"],0,10)).' '.substr($value["Transdate"],11,8);
					$list_add[] = $SaleDate;
					if($value["Transdate"]!== NULL){
						$datetime_1 = substr($value["OrderDate"],0,19); 
						$datetime_2 = substr($value["Transdate"],0,19);
						$from_time = strtotime($datetime_1); 
						$to_time = strtotime($datetime_2); 
						$minutes = round(abs($from_time - $to_time) / 60,2);
						$d = floor ($minutes / 1440);
						$h = floor (($minutes - $d * 1440) / 60);
						$m = (Int)$minutes - ($d * 1440) - ($h * 60);
						if($d== '0'){
							if($h== '0'){
								$diff_minutes = $m.'m';
								}else{
								$diff_minutes = $h.'h '. $m.'m';
							}
							}else{
							$diff_minutes = $d.'d '. $h.'h '. $m.'m';
						}
						}else{
						$diff_minutes = '';
					}
					
					$list_add[] = $diff_minutes;
					$GatepassDate = _d(substr($value["gatepasstime"],0,10)).' '.substr($value["gatepasstime"],11,8);
					$list_add[] = $GatepassDate;
					if($value["gatepasstime"]!== NULL){
						$datetime_11 = substr($value["Transdate"],0,19); 
						$datetime_22 = substr($value["gatepasstime"],0,19);
						$from_time1 = strtotime($datetime_11); 
						$to_time1 = strtotime($datetime_22); 
						$minutes1 = round(abs($from_time1 - $to_time1) / 60,2);
						$d1 = floor ($minutes1 / 1440);
						$h1 = floor (($minutes1 - $d1 * 1440) / 60);
						$m1 = (Int)$minutes1 - ($d1 * 1440) - ($h1 * 60);
						if($d1== '0'){
							if($h1== '0'){
								$diff_minutes1 = $m1.'m';
								}else{
								$diff_minutes1 = $h1.'h '. $m1.'m';
							}
							}else{
							$diff_minutes1 = $d1.'d '. $h1.'h '. $m1.'m';
						}
						}else{
						$diff_minutes1 = '';
					}
					$list_add[] = $diff_minutes1;
					$list_add[] = $value["company"];
					$list_add[] = round($value["OrderAmount"]);
					$OrderSum += round($value["OrderAmount"]);
					$list_add[] = round($value["BillAmt"]);
					$BillSum += round($value["BillAmt"]);
					
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				$list_add = [];
				$list_add[] = 'Total';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = '';
				$list_add[] = round($OrderSum);
				$list_add[] = round($BillSum);
				$writer->writeSheetRow('Sheet1', $list_add);
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'OrderVsDispatch.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* List all Party Pack Wise Commulatives Sales datatables */
		public function PartyPackWiseCummulativesSales()
		{
			if (!has_permission_new('cummulatives_sale', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Party Pack Wise Commulatives Sales";
			$this->load->model('clients_model');
			$data['states'] = $this->clients_model->getallstate();
			$data['groups'] = $this->clients_model->get_groups();
			$cur_date = date('d/m/Y');
			$m = date('m');
			$y = date('Y');
			$date1 = "01/".$m."/".$y;
			$data['from_date'] = $date1;
			$data['to_date'] = $cur_date;
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/PartyPackWiseCommulativesSales', $data);
		}
		//===================== Daily Sale Report Page load ============================
		public function daily_sale()
		{
			if (!has_permission_new('daily_sale', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Daily Sale reports";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/daily_sale', $data);
		}
		//======================== Load Daily Sale Report ==============================
		public function load_data()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date')
			);
			$result = $this->sale_reports_model->load_data($data);
			echo json_encode($result);
		}
		//======================== Export Daily Sale Report ============================
		public function export_daily_sale()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date')
				);
				$data = $this->sale_reports_model->load_data($data);  
				
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Sales Report ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
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
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["ChallanId"] =  'ChallanId';
				$set_col_tk["OrderID"] = 'OrderID';
				$set_col_tk["Bill_No"] = 'Bill No';
				$set_col_tk["Bill_Date"] = 'Bill Date';
				$set_col_tk["AccountID"] = 'AccountID';
				$set_col_tk["Account_Name"] = 'Account Name';
				$set_col_tk["Bill_Amount"] = 'Bill Amount';
				$set_col_tk["Rtn"] = 'Rtn';
				$set_col_tk["Veh-R-tn-Pymt"] = 'Veh R tn Pymt';
				$set_col_tk["Fresh-Rtn"] = 'Fresh Rtn';
				$set_col_tk["other-Pymt"] = 'Other Pymt';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 0;
				$total = 0;
				$rowspan = 0;
				$grand_total = 0;
				foreach ($data as $k => $value) {
					$RndAmt = round($value["BillAmt"]);
					$grand_total = $grand_total + $RndAmt ;
					$list_add = [];
					$list_add[] = $value["ChallanID"];
					$list_add[] = $value["OrderID"];
					$list_add[] = $value["SalesID"];
					$date = _d(substr($value["Transdate"],0,10));
					$list_add[] = $date;
					$list_add[] = $value["AccountID"];
					$list_add[] = $value["AccountName"];
					$list_add[] = number_format($RndAmt, 2, '.', '');
					$list_add[] = "N";
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					
					$writer->writeSheetRow('Sheet1', $list_add);
					
					$challan_id = $value["ChallanID"];
					if($value["ChallanID"] == $challan_id){
						$i = $i + 1;
					}
					if($value["Count_number"] > 1){
						if($value["Count_number"] == $i){
							$list_add = [];
							$list_add[] = "";
							$list_add[] = "";
							$list_add[] = "";
							$list_add[] = "";
							$list_add[] = "";
							$list_add[] = "Total";
							$list_add[] = number_format($value["Total_number"], 2, '.', '');
							$list_add[] = "";
							$list_add[] = "";
							$list_add[] = "";
							$list_add[] = "";
							$writer->writeSheetRow('Sheet1', $list_add);
							$i = 0;
						}
						
						}else{
						$i = 0;
					}
					
				}
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "Total ".count($data)." rows Grand Total";
				$list_add[] = number_format($grand_total, 2, '.', '');
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'DailySale_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function get_item_group()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date')
			);
			$data = $this->sale_reports_model->get_sale_item_group2($data);
			echo json_encode($data);
		}
		
		public function staff_list_by_role()
		{  
			$data = $this->sale_reports_model->get_reported_by_staff($this->input->post('id'));
			echo json_encode($data);
		}
		public function export_party_commulative_report()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				ini_set('serialize_precision','-1');
				$filterdata = array(
				'AcountType' => $this->input->post('AcountType'),
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'report_in'  => $this->input->post('report_in'),
				'states'  => $this->input->post('states'),
				'client_type'  => $this->input->post('client_type'),
				'item_group'  => $this->input->post('item_group'),
				'loc_type'  => $this->input->post('loc_type'),
				'report_type'  => $this->input->post('report_type'),
				'staff_designation'  => $this->input->post('staff_designation'),
				'staff_id'  => $this->input->post('staff_id'),
				'values_in'  => $this->input->post('values_in')
				);
				
				$AcountType = $this->input->post('AcountType');
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$report_in = $this->input->post('report_in');
				
				$states = $this->input->post('states');
				$state_name = $this->sale_reports_model->get_state_name($states);
				
				$client_type = $this->input->post('client_type');
				$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);
				
				$item_group = $this->input->post('item_group');
				$item_group_name = $this->sale_reports_model->get_item_group_name($item_group);
				
				$loc_type = $this->input->post('loc_type');
				if($loc_type == 1){
					$loc_type_name = "Local";
					}elseif($loc_type == 2){
					$loc_type_name = "OutStation";
					}elseif($loc_type == 3){
					$loc_type_name = "NotDefined";
				}
				
				$values_in = $this->input->post('values_in');
				if($values_in == 1){
					$values_in_name = "With GST";
					}elseif($values_in == 2){
					$values_in_name = "Without GST";
				}
				$report_type = $this->input->post('report_type');
				$selected_company = $this->session->userdata('root_company');
				$account_ids = array();
    			$item_ids_desc = array();
    			$AccountList = $this->sale_reports_model->GetPartyPackAccountList($filterdata);
    			$AccountListArray = array();
    			foreach($AccountList as $key=>$val){
    			    array_push($AccountListArray,$val['AccountID']);
				}
    			$item_groupArray = explode(",",$item_group);
    			$ItemList = $this->sale_reports_model->GetPartyPackItemList($filterdata,$item_groupArray,$AccountListArray);
    			$ItemWiseAccountWiseSale = $this->sale_reports_model->GetPartyPackBodySaleData($AccountListArray,$filterdata,$item_groupArray);
    			/*echo "<pre>";
					print_r($ItemWiseAccountWiseSale);
				die;*/
    			// Only Use for Net Sale calculation
    			$ItemWiseAccountWiseSaleRtn = $this->sale_reports_model->GetPartyPackSaleRtnData($AccountListArray,$filterdata,$item_groupArray);
    			
    			$ItemWiseSale = $this->sale_reports_model->GetPartyPackSaleItemWiseData($AccountListArray,$filterdata,$item_groupArray);
    			$ItemWiseSaleRtn = $this->sale_reports_model->GetPartyPackSaleRtnItemWise($AccountListArray,$filterdata,$item_groupArray);
				
				$item_count = count($item_ids_desc);
				$total_col = $item_count + 6;
				
				$writer = new XLSXWriter();
				//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');
				//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');
				$j=0;
				$company_name = array($selected_company_details->company_name);
				$writer->markMergedCell('Sheet1', $start_row =$j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				$j++;
				$address = $selected_company_details->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				$j++;
				$msg2 = "Report Date :  ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				$j++;
				if($states){
					$msg3 = "State :  ".$states;
					$filter3 = array($msg3);
					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
					$writer->writeSheetRow('Sheet1', $filter3);
					$j++;
				}
				
				
				$msg4 = "Loc Type :  ".$loc_type_name;
				$filter4 = array($msg4);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter4);
				$j++;
				
				$msg4 = "Values In :  ".$values_in_name;
				$filter4 = array($msg4);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter4);
				$j++;
				
				$msg5 = "Report In :  ".$report_in;
				$filter5 = array($msg5);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter5);
				$j++;
				$msg6 = "Report Type :  ".$report_type;
				$filter6 = array($msg6);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter6);
				$j++;
				if($client_type){
					$msg7 = "Distributor Type :  ".$client_type_name->name;
					$filter7 = array($msg7);
					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
					$writer->writeSheetRow('Sheet1', $filter7);
					$j++;
				}
				
				
				$msg8 = "Item Group :  ".$item_group_name;
				$filter8 = array($msg8);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter8);
				$j++;
				$msg9 = "";
				$filter9 = array($msg9);
				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = $total_col);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter9);
				// empty row
				/*	$list_add = [];
					$tt = 1;
					foreach ($total_col as $value) {
					$list_add[$tt] = "";
					$tt++;
					}
					
				$writer->writeSheetRow('Sheet1', $list_add);*/
				
				
				$set_col_tk = [];
				$set_col_tk["SOID"] =  'SOID';
				$set_col_tk["Station Name"] = 'Station Name';
				$set_col_tk["AccountID"] = 'AccountID';
				$set_col_tk["Party Name"] = 'Party Name';
				foreach ($ItemList as $key => $value) {
					$set_col_tk[$key] = $value["description"];
				}
				$set_col_tk["Item Value"] = 'Item Value';
				$set_col_tk["Crates"] = 'Crates';
				$set_col_tk["Cases"] = 'Cases';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$col_total_val = 0.00;
				$col_total_cases = 0.00;
				$col_total_crates = 0.00;
				$col_total_unit = 0.00;
				
				foreach ($AccountList as $AccountIdkey => $AccountDetails) {
					if($AcountType == "BillTo"){
    			        $AccountId = $AccountDetails['AccountID'];
						}else{
    			        $AccountId = $AccountDetails['AccountID2'];
					}
					$list_add = [];
					$list_add[] = "";
					$list_add[] = $AccountDetails['StationName'];
					$list_add[] = $AccountId;
					$list_add[] = $AccountDetails['company'];
					$row_value = 0.00;
					$row_cases = 0.00;
					$row_crates = 0.00;
					$row_unit = 0.00;
					foreach ($ItemList as $key => $value) {
						$ItemAmt = 0;
    					$ItemUnit = 0;
    					$ItemCrates = 0;
    					$ItemCases = 0;
						foreach ($ItemWiseAccountWiseSale as $key2 => $value2) {
						    if($AcountType == "BillTo"){
            			        $AccountID = $value2['AccountID'];
								}else{
            			        $AccountID = $value2['AccountID'];
							}
							if(trim(strtoupper($AccountID)) == trim(strtoupper($AccountId)) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($value["ItemID"]))){
								$ItemAmt = $value2["amt_sum"];
    							$ItemUnit = $value2["sumunit"];
    							if($value2["SuppliedIn"] == "CR"){
    							    $ItemCrates = $value2["sumcases"];
									}else{
    							    $ItemCases = $value2["sumcases"];
								}
								if($report_type == "netsales"){
									foreach ($ItemWiseAccountWiseSaleRtn as $sr_key => $sr_value) {
										if(trim(strtoupper($AccountID)) == trim(strtoupper($sr_value["AccountID"])) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($sr_value["ItemID"]))){
											$ItemAmt -= $sr_value["sr_amt_sum"];
    									    $ItemUnit -= $sr_value["sr_sumunit"];
    									    if($value2["SuppliedIn"] == "CR"){
                							    $ItemCrates -= $sr_value["sr_sumcases"];
												}else{
                							    $ItemCases -= $sr_value["sr_sumcases"];
											}
										}
									}
								}
							}
						}
						$row_value += $ItemAmt;
    					$row_unit += $ItemUnit;
    					$row_crates += $ItemCrates;
    					$row_cases += $ItemCases;
						$item_value_new = 0;
						if($report_in == "value"){
							$item_value_new = $ItemAmt;
							}elseif($report_in == "cases"){
							$item_value_new = $ItemCases + $ItemCrates;
							}elseif($report_in == "unit"){
							$item_value_new = $ItemUnit;
							}elseif($report_in == "kg"){
							$item_value_new = $ItemUnit * $value["weight"];
							}elseif($report_in == "tonnage"){
							$item_value_new = 0;
						}
						if($item_value_new == "0"){
							$list_add[] = '';
							}else{
							$list_add[] = number_format($item_value_new, 2, ".", "");
						}
					}
					$col_total_val = $col_total_val + $row_value;
					$col_total_cases = $col_total_cases + $row_cases;
					$col_total_crates = $col_total_crates + $row_crates;
					$col_total_unit = $col_total_unit + $row_unit;
					
					$list_add[] = number_format($row_value, 2, ".", "");
					$list_add[] = number_format($row_crates, 2, ".", "");
					$list_add[] = number_format($row_cases, 2, ".", "");
					$writer->writeSheetRow('Sheet1', $list_add);
					
				}
				
				// Footer Data
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "Total Value(Incl GST)";
				foreach ($ItemList as $key => $value) {
					$colsumamt = 0.00;
    				foreach ($ItemWiseSale as $key3 => $value3) {
    					if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
    					    $colsumamt = $value3["amt_sum"];
    						if($report_type == "netsales"){
    							foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
    								if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
    									$colsumamt -= $sr_value3["sr_amt_sum"];
									}
								}
    							
							}
						}
					}
					$list_add[] = number_format($colsumamt, 2, ".", "");
				}
				
				$list_add[] = number_format($col_total_val, 2, ".", "");
				$list_add[] = number_format($col_total_crates, 2, ".", "");
				$list_add[] = number_format($col_total_cases, 2, ".", "");
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				// Cases Selection
				if($report_in == "cases"){
					$list_add = [];
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "Total Cases";
					foreach ($ItemList as $key => $value) {
						$colsumcases = "";
    					foreach ($ItemWiseSale as $key3 => $value3) {
    						if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
    						    $colsumcases = $value3["sumcases"];
    							if($report_type == "netsales"){
    								foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
    									if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
    										$colsumcases -= $sr_value3["sr_sumcases"];
										}
									}
								}
							}
						}
						$list_add[] = number_format($colsumcases, 2, ".", "");
					}
					$list_add[] = number_format($col_total_cases, 2, ".", "");
					$list_add[] = number_format($col_total_crates, 2, ".", "");
					$list_add[] = number_format($col_total_cases, 2, ".", "");
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				// Unit Selection
				if($report_in == "unit"){
					$list_add = [];
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "Total Unit";
					foreach ($ItemList as $key => $value) {
						$colsumunit = "";
    					foreach ($ItemWiseSale as $key3 => $value3) {
    						$match3 = 0;
    						if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
    						    $colsumunit = $value3["sumunit"];
    							if($report_type == "netsales"){
    								foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
    									if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
    										$colsumunit -= $sr_value3["sr_sumunit"];
										}
									}
								}
							}
						}
						$list_add[] = number_format($colsumunit, 2, ".", "");
					}
					$list_add[] = number_format($col_total_unit, 2, ".", "");
					$list_add[] = number_format($col_total_crates, 2, ".", "");
					$list_add[] = number_format($col_total_cases, 2, ".", "");
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				// Unit Selection
				if($report_in == "kg"){
					$list_add = [];
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "Total Kg";
					foreach ($ItemList as $key => $value) {
						$colsumunit = "";
    					foreach ($ItemWiseSale as $key3 => $value3) {
    						$match3 = 0;
    						if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
    						    $colsumunit = $value3["sumunit"];
    							if($report_type == "netsales"){
    								foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
    									if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
    										$colsumunit -= $sr_value3["sr_sumunit"];
										}
									}
								}
							}
						}
						$list_add[] = number_format($colsumunit * $value["weight"], 2, ".", "");
					}
					$list_add[] = '';
					$list_add[] = '';
					$list_add[] = '';
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				if($AcountType == "BillTo"){
			        $filename = 'CummulativeSale_Report.xlsx';
					}else{
			        $filename = 'ShipToCummulativeSale_Report.xlsx';
				}
				
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		public function get_commulative_data()
		{
			$filterdata = array(
			'AcountType' => $this->input->post('AcountType'),
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'report_in'  => $this->input->post('report_in'),
			'states'  => $this->input->post('states'),
			'client_type'  => $this->input->post('client_type'),
			'item_group'  => $this->input->post('item_group'),
			'loc_type'  => $this->input->post('loc_type'),
			'report_type'  => $this->input->post('report_type'),
			'staff_designation'  => $this->input->post('staff_designation'),
			'staff_id'  => $this->input->post('staff_id'),
			'values_in'  => $this->input->post('values_in')
			);
			$AcountType = $this->input->post('AcountType');
			$company_detail = $this->sale_reports_model->get_company_detail();
			
			$report_in = $this->input->post('report_in');
			
			$states = $this->input->post('states');
			$state_name = $this->sale_reports_model->get_state_name($states);
			
			$client_type = $this->input->post('client_type');
			$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);
			
			$item_group = $this->input->post('item_group');
			$item_group_name = $this->sale_reports_model->get_item_group_name($item_group);
			
			$loc_type = $this->input->post('loc_type');
			if($loc_type == 1){
				$loc_type_name = "Local";
				}elseif($loc_type == 2){
				$loc_type_name = "OutStation";
				}elseif($loc_type == 3){
				$loc_type_name = "NotDefined";
			}
			$report_type = $this->input->post('report_type');
			$selected_company = $this->session->userdata('root_company');
			
			$account_ids = array();
			$item_ids_desc = array();
			$AccountList = $this->sale_reports_model->GetPartyPackAccountList($filterdata);
			$AccountListArray = array();
			foreach($AccountList as $key=>$val){
			    array_push($AccountListArray,$val['AccountID']);
			}
			// echo "<pre>";
			// print_r($AccountList);
			// die;
			$item_groupArray = explode(",",$item_group);
			$ItemList = $this->sale_reports_model->GetPartyPackItemList($filterdata,$item_groupArray,$AccountListArray);
			$ItemWiseAccountWiseSale = $this->sale_reports_model->GetPartyPackBodySaleData($AccountListArray,$filterdata,$item_groupArray);
			
			// Only Use for Net Sale calculation
			$ItemWiseAccountWiseSaleRtn = $this->sale_reports_model->GetPartyPackSaleRtnData($AccountListArray,$filterdata,$item_groupArray);
			
			$ItemWiseSale = $this->sale_reports_model->GetPartyPackSaleItemWiseData($AccountListArray,$filterdata,$item_groupArray);
			$ItemWiseSaleRtn = $this->sale_reports_model->GetPartyPackSaleRtnItemWise($AccountListArray,$filterdata,$item_groupArray);
			
			// echo "<pre>";
			// print_r($ItemWiseSale);
			// die;
			$html = '';
			$html .= '<table class="table-striped table-bordered daily_report" id="daily_report">';
			$html .= '<thead style="font-size:11px;">';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="7"><b>'.$company_detail->company_name.'</b></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th colspan="7"><b>'.$company_detail->address.'</b></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Report Date : </b></th>';
			$html .= '<th>'.$this->input->post('from_date').' To '.$this->input->post('to_date').'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>State : </b></th>';
			$html .= '<th>'.$state_name->state_name.'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Loc Type : </b></th>';
			$html .= '<th>'.$loc_type_name.'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Reports In : </b></th>';
			$html .= '<th>'.$report_in.'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Reports In : </b></th>';
			$html .= '<th>Sales</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Distributor Type : </b></th>';
			$html .= '<th>'.$client_type_name->name.'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th><b>Item Group : </b></th>';
			$html .= '<th colspan="6">'.$item_group_name.'</th>';
			$html .= '</tr>';
			
			$html .= '<tr style="display:none;">';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '<th></th>';
			$html .= '</tr>';
			
			$html .= '<tr style="background-color:#438EB9;">';
			$html .= '<th class="col-id-no fixed-header sortable">Sr.No.</th>';
			$html .= '<th class="col-id-ordid fixed-header sortable">Station Name</th>';
			$html .= '<th class="col-id-custname fixed-header sortable">AccountID</th>';
			$html .= '<th width="20%" class="col-id-custstate fixed-header sortable">Account Name</th>';
			foreach ($ItemList as $key => $value) {
				$html .='<th class="sortable" style="text-align:right;">'.$value["description"].'('.$value["unit"].')</th>';
				
			}
			
			$html .= '<th class="sortable">Item Value</th>';
			$html .= '<th class="sortable">Crates</th>';
			$html .= '<th class="sortable">Cases</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			
			$col_total_val = 0.00;
			$col_total_cases = 0.00;
			$col_total_crates = 0.00;
			$col_total_unit = 0.00;
			$SrNo = 1;
			foreach ($AccountList as $AccountIdkey => $AccountDetails) {
				$html .='<tr>';
				if($AcountType == "BillTo"){
			        $AccountId = $AccountDetails['AccountID'];
					}else{
			        $AccountId = $AccountDetails['AccountID2'];
				}
				$html .='<td class="col-id-no">'.$SrNo.'</td>';
				$html .='<td class="col-id-ordid">'.$AccountDetails['StationName'].'</td>';
				$html .='<td class="col-id-custname acctname" width="20%">'.$AccountId.'</td>';
				$html .='<td width="20%" class="col-id-custstate">'.$AccountDetails['company'].'</td>';
				//$sumamt = "A";
				$row_value = 0.00;
				$row_cases = 0.00;
				$row_crates = 0.00;
				$row_unit = 0.00;
				
				foreach ($ItemList as $key => $value) {
					
					$ItemAmt = 0;
					$ItemUnit = 0;
					$ItemCrates = 0;
					$ItemCases = 0;
					foreach ($ItemWiseAccountWiseSale as $key2 => $value2) {
					    if($AcountType == "BillTo"){
        			        $AccountID = $value2['AccountID'];
							}else{
        			        $AccountID = $value2['AccountID'];
						}
						if(trim(strtoupper($AccountID)) == trim(strtoupper($AccountId)) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($value["ItemID"]))){
							$ItemAmt = $value2["amt_sum"];
							$ItemUnit = $value2["sumunit"];
							if($value2["SuppliedIn"] == "CR"){
							    $ItemCrates = $value2["sumcases"];
								}else{
							    $ItemCases = $value2["sumcases"];
							}
							
							if($report_type == "netsales"){
								foreach ($ItemWiseAccountWiseSaleRtn as $sr_key => $sr_value) {
									if(trim(strtoupper($AccountID)) == trim(strtoupper($sr_value["AccountID"])) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($sr_value["ItemID"]))){
									    $ItemAmt -= $sr_value["sr_amt_sum"];
									    $ItemUnit -= $sr_value["sr_sumunit"];
									    if($value2["SuppliedIn"] == "CR"){
            							    $ItemCrates -= $sr_value["sr_sumcases"];
											}else{
            							    $ItemCases -= $sr_value["sr_sumcases"];
										}
									}
								}
							}
							
						}
					}
					$row_value += $ItemAmt;
					$row_unit += $ItemUnit;
					$row_crates += $ItemCrates;
					$row_cases += $ItemCases;
					
					$item_value_new = 0;
					if($report_in == "value"){
						$item_value_new = $ItemAmt;
						}elseif($report_in == "cases"){
						$item_value_new = $ItemCrates + $ItemCases;
						}elseif($report_in == "unit"){
						$item_value_new = $ItemUnit;
						}elseif($report_in == "kg"){
						$item_value_new = $ItemUnit * $value["weight"];
						}elseif($report_in == "tonnage"){
						$item_value_new = 0;
					}
					if($item_value_new == 0){
						$html .='<td style="text-align:right;"></td>';
						}else{
						$html .='<td style="text-align:right;">'.number_format($item_value_new,2).'</td>';
					}
				}
				
				
				
				$col_total_val = $col_total_val + $row_value;
				$col_total_cases = $col_total_cases + $row_cases;
				$col_total_crates = $col_total_crates + $row_crates;
				$col_total_unit = $col_total_unit + $row_unit;
				$html .='<td style="text-align:right;">'.number_format($row_value,2).'</td>';
				$html .='<td style="text-align:right;">'.number_format($row_crates,2).'</td>';
				$html .='<td style="text-align:right;">'.number_format($row_cases,2).'</td>';
				$html .='</tr>';
				$SrNo++;
			}
			$html .= '</tbody>';
			$html .= '<tfoot>';
			// footer data
			
			$html .='<tr>';
			$html .='<td scope="row" class="col-id-no"></td>';
			$html .='<td scope="row" class="col-id-ordid"></td>';
			$html .='<td scope="row" class="col-id-custname"></td>';
			$html .='<td style="color:#FFF;font-weight:700;" scope="row" class="col-id-custstate"><b>Total Value(Incl GST)</b></td>';
			foreach ($ItemList as $key => $value) {
				$colsumamt = 0.00;
				foreach ($ItemWiseSale as $key3 => $value3) {
					if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
					    $colsumamt = $value3["amt_sum"];
						if($report_type == "netsales"){
							foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
								if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
									$colsumamt -= $sr_value3["sr_amt_sum"];
								}
							}
							
						}
					}
				}
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($colsumamt,2).'</b></td>';
			}
			$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_val,2).'</b></td>';
			$html .='<td style="color:#e93232;font-weight:700;text-align:right;">'.number_format($col_total_crates,2).'</td>';
			$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_cases,2).'</b></td>';
			$html .='</tr>';
			
			// Cases Selection
			if($report_in == "cases"){
				
				$html .='<tr>';
				$html .='<td></td>';
				$html .='<td></td>';
				$html .='<td></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>Total Cases</b></td>';
				foreach ($ItemList as $key => $value) {
					$colsumcases = "";
					foreach ($ItemWiseSale as $key3 => $value3) {
						if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
						    $colsumcases = $value3["sumcases"];
							if($report_type == "netsales"){
								foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
									if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
										$colsumcases -= $sr_value3["sr_sumcases"];
									}
								}
							}
						}
					}
					$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($colsumcases,2).'</b></td>';
				}
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_cases,2).'</b></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;">'.number_format($col_total_crates,2).'</td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_cases,2).'</b></td>';
				$html .='</tr>';
			}
			
			// Unit Selection
			if($report_in == "unit"){
				$html .='<tr>';
				$html .='<td></td>';
				$html .='<td></td>';
				$html .='<td></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>Total Unit</b></td>';
				foreach ($ItemList as $key => $value) {
					$colsumunit = "";
					foreach ($ItemWiseSale as $key3 => $value3) {
						$match3 = 0;
						if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
						    $colsumunit = $value3["sumunit"];
							if($report_type == "netsales"){
								foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
									if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
										$colsumunit -= $sr_value3["sr_sumunit"];
									}
								}
							}
						}
					}
					$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($colsumunit,2).'</b></td>';
				}
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_unit,2).'</b></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_crates,2).'</b></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($col_total_cases,2).'</b></td>';
				$html .='</tr>';
			}
			// Kg Selection
			if($report_in == "kg"){
				$html .='<tr>';
				$html .='<td></td>';
				$html .='<td></td>';
				$html .='<td></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>Total Kg</b></td>';
				foreach ($ItemList as $key => $value) {
					$colsumunit = "";
					foreach ($ItemWiseSale as $key3 => $value3) {
						$match3 = 0;
						if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($value["ItemID"]))){
						    $colsumunit = $value3["sumunit"];
							if($report_type == "netsales"){
								foreach ($ItemWiseSaleRtn as $sr_key3 => $sr_value3) {
									if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
										$colsumunit -= $sr_value3["sr_sumunit"];
									}
								}
							}
						}
					}
					$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b>'.number_format($colsumunit * $value["weight"],2).'</b></td>';
				}
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b></b></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b></b></td>';
				$html .='<td style="color:#e93232;font-weight:700;text-align:right;"><b></b></td>';
				$html .='</tr>';
			}
			$html .= '</tfoot>'; 
			$html .= '</table>';
			
			echo json_encode($html);
			die;
		}
		
		/* List all Stock Alert Items */
		public function Stock_alert()
		{
			if (!has_permission_new('Stock_alert', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Stock Alert Reports";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['ItemListFG'] = $this->sale_reports_model->ItemList_New();
			// echo "<pre>";print_r($data['ItemListFG']);die;
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/Stock_alert', $data);
		}
		
		
		public function export_Stock_alert()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			$data = $this->sale_reports_model->ItemList_New();  
			
			$selected_company_details    = $this->sale_reports_model->get_company_detail();
			$PlantDetail = $this->sale_reports_model->GetPlantDetails();
			$writer = new XLSXWriter();
			
			$company_name = array($PlantDetail->FIRMNAME);
			$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
			$writer->writeSheetRow('Sheet1', $company_name);
			
			$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
			$company_addr = array($address,);
			$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
			$writer->writeSheetRow('Sheet1', $company_addr);
			
			$msg = "Stock Alert Report ";
			$filter = array($msg);
			$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
			$writer->writeSheetRow('Sheet1', $filter);
			
			// empty row
			$list_add = [];
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$writer->writeSheetRow('Sheet1', $list_add);
			
			
			$set_col_tk = [];
			$set_col_tk["Item ID"] =  'Item ID';
			$set_col_tk["Item Name"] = 'Item Name';
			$set_col_tk["Minimum Required Quantity"] = 'Minimum Required Quantity';
			$set_col_tk["Maximum Required Quantity"] = 'Maximum Required Quantity';
			$set_col_tk["Stock Available"] = 'Stock Available';
			$writer_header = $set_col_tk;
			$writer->writeSheetRow('Sheet1', $writer_header);
			
			$i = 0;
			foreach ($data as $each) {
				
				$PQty = 0;
				$PRQty = 0;
				$IQty = 0;
				$PRDQty = 0;
				$SQty = 0;
				$SRTQty = 0;
				$AQty = 0;
				$AQty2 = 0;
				$AQty3 = 0;
				$AQty4 = 0;
				$GIQty = 0;
				$GOQty = 0;
				
				$itemStocks = $this->sale_reports_model->GetStockReport($each['item_code']);
				foreach ($itemStocks as $stock) {
					if($stock['ItemID']==$each['item_code']){
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
							}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
							$AQty += $stock['BilledQty'];
							}elseif($stock['TType'] == 'X'  && $stock['TType2'] == 'Free distribution'){
							$AQty += $stock['BilledQty'];
							}elseif($stock['TType'] == 'X'  && $stock['TType2'] == 'Free Distribution'){
							$AQty += $stock['BilledQty'];
							}elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Damaged'){
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
				$stockQty = $each['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty - $GOQty + $GIQty;
				$stockQtyInCase = $stockQty / $each['case_qty'];
				
				if($each['min_qty'] > 0 && $stockQtyInCase <= $each['min_qty'] || $each['min_qty'] > 0 && $stockQtyInCase >= $each['max_qty'])
				{
					$list_add = [];
					$list_add[] = $each['item_code'];
					$list_add[] = $each['description'];
					$list_add[] = $each['min_qty'];
					$list_add[] = $each['max_qty'];
					$list_add[] = $stockQtyInCase;
					
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				
				
			}
			
			$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
			foreach($files as $file){
				if(is_file($file)) {
					unlink($file); 
				}
			}
			$filename = 'Stock_Alert_Report.xlsx';
			$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
			echo json_encode([
			'site_url'          => site_url(),
			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
			]);
			die;
			
		}
		
		/* Category Wise Item Sale page */
		public function GroupWiseItemSale()
		{
			if (!has_permission_new('GroupWiseItemSale', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Group Wise Item Sale";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/GroupWiseItemSale', $data);
		}
		
		// Get Result for Item CategoryWise report
		public function GetItemGroupWiseItemSaleReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$body_data = $this->sale_reports_model->GetGroupWiseItemSaleReportBodyData($filterdata);
			$CategoryList = $this->sale_reports_model->GetGroupList($filterdata);
			// echo json_encode($body_data);
			// die;
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">ItemID</th>';
			$html .= '<th class="sortable" align="center">Item Name</th>';
			$html .= '<th class="sortable" align="center">Qty</th>';
			$html .= '<th class="sortable" align="center">Unit</th>';
			$html .= '<th class="sortable" align="center">Rate</th>';
			$html .= '<th class="sortable" align="center">Disc Amt</th>';
			$html .= '<th class="sortable" align="center">Amount</th>';
			$html .= '<th class="sortable" align="center">Weight(Kg)</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			$AllTotalQty = 0;
			$AllTotalDiscAmt = 0;
			$AllTotalAmt = 0;
			$AllTotalWeight = 0;
			foreach($CategoryList as $catKey=>$catVal){
				$html .= '<tr>';
				$html .= '<td colspan="8" style="color:#03a9f4;font-size:14px;"><b>Group : '.$catVal["name"].'</b></td>';
				$html .= '</tr>';
				$TotalQty = 0;
				$TotalAmt = 0;
				$TotalDiscAmt = 0;
				$Totalweight = 0;
				foreach($body_data as $BKey=>$BVal){
					if($catVal["subgroup_id"]==$BVal["subgroup_id"]){
						$html .= '<tr>';
						$html .= '<td align="center">'.$i.'</td>';
						$html .= '<td align="center">'.$BVal["ItemID"].'</td>';
						$html .= '<td align="left">'.$BVal["description"].'</td>';
						$html .= '<td align="right">'.$BVal["BillQty"].'</td>';
						$html .= '<td align="center">'.$BVal["unit"].'</td>';
						$html .= '<td align="right">'.number_format($BVal["SaleRate"], 2, '.', '').'</td>';
						$html .= '<td align="right">'.number_format($BVal["DiscAmt"], 2, '.', '').'</td>';
						$html .= '<td align="right">'.number_format(($BVal["BillQty"]*$BVal["SaleRate"])-$BVal["DiscAmt"], 2, '.', '').'</td>';
						$html .= '<td align="right">'.number_format($BVal["BillQty"]*$BVal["weight"], 2, '.', '').'</td>';
						$html .= '</tr>';
						$i++;
						$TotalQty += ($BVal["BillQty"]);
						$TotalDiscAmt += ($BVal["DiscAmt"]);
						$TotalAmt += (($BVal["BillQty"])*$BVal["SaleRate"])-$BVal["DiscAmt"];
						$Totalweight += ($BVal["BillQty"]*$BVal["weight"]);
					}
				}
				$AllTotalQty += $TotalQty;
				$AllTotalDiscAmt += $TotalDiscAmt;
				$AllTotalAmt += $TotalAmt;
				$AllTotalWeight += $Totalweight;
				$html .= '<tr>';
				$html .= '<td colspan="3" style="color:#03a9f4;font-size:14px;"><b>Total of '.$catVal["name"].'</b></td>';
				$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'.$TotalQty.'</b></td>';
				$html .= '<td><b></b></td>';
				$html .= '<td ></td>';
				$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'.number_format($TotalDiscAmt, 2, '.', '').'</b></td>';
				$html .= '<td align="right" style="color:#03a9f4;font-size:14px;"><b>'.number_format($TotalAmt, 2, '.', '').'</b></td>';
				$html .= '<td align="right" style="color:#03a9f4;font-size:14px;">'.number_format($Totalweight, 2, '.', '').'</td>';
				$html .= '</tr>';
			}
			
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			
			$html .= '<tr>';
			$html .= '<td colspan="3" style="color:#03a9f4;font-size:15px;"><b>Total </b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:15px;"><b>'.number_format($AllTotalQty, 2, '.', '').'</b></td>';
			$html .= '<td><b></b></td>';
			$html .= '<td ></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:15px;"><b>'.number_format($AllTotalDiscAmt, 2, '.', '').'</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:15px;"><b>'.number_format($AllTotalAmt, 2, '.', '').'</b></td>';
			$html .= '<td align="right" style="color:#03a9f4;font-size:15px;">'.number_format($AllTotalWeight, 2, '.', '').'</td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function ExportGroupWiseItemSale()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				);
				
				$body_data = $this->sale_reports_model->GetGroupWiseItemSaleReportBodyData($filterdata);
				$CategoryList = $this->sale_reports_model->GetGroupList($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Group Wise Item Sale';
				$colspan = '8';
				
				
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = $AccountDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				
				$set_col_tk = [];
				$set_col_tk["Sr. No"] = 'Sr. No.';
				$set_col_tk["ItemID"] = 'ItemID';
				$set_col_tk["Item Name"] = 'Item Name';
				$set_col_tk["Qty"] = 'Qty';
				$set_col_tk["Unit"] = 'Unit';
				$set_col_tk["Rate"] = 'Rate';
				$set_col_tk["Disc Amt"] = 'Disc Amt';
				$set_col_tk["Amount"] = 'Amount';
				$set_col_tk["Weight(Kg)"] = 'Weight(Kg)';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				$RowCount = 4;
				$i = 1;
				$AllTotalQty = 0;
				$AllTotalAmt = 0;
				$AllTotalDiscAmt = 0;
				$AllTotalWeight = 0;
				foreach($CategoryList as $catKey=>$catVal){
					/*$Name = "Group : ".$catVal["name"];
						$GrpName = array($Name);
						$writer->markMergedCell('Sheet1', $start_row = $RowCount, $start_col = 0, $end_row = $RowCount, $end_col = $colspan);  //merge cells
						$writer->writeSheetRow('Sheet1', $GrpName);
					$RowCount++;*/
					$list_add = [];
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "Group Name : ".$catVal["name"];
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "";
					$writer->writeSheetRow('Sheet1', $list_add);
					$TotalQty = 0;
					$TotalAmt = 0;
					$TotalDiscAmt = 0;
					$Totalweight = 0;
					foreach($body_data as $BKey=>$BVal){
						if($catVal["subgroup_id"]==$BVal["subgroup_id"]){
							$list_add = [];
							$list_add[] = $i;
							$list_add[] = $BVal["ItemID"];
							$list_add[] = $BVal["description"];
							$list_add[] = $BVal["BillQty"];
							$list_add[] = $BVal["unit"];
							$list_add[] = number_format($BVal["SaleRate"], 2, '.', '');
							$list_add[] = number_format($BVal["DiscAmt"], 2, '.', '');
							$list_add[] = number_format(($BVal["BillQty"]*$BVal["SaleRate"])-$BVal["DiscAmt"], 2, '.', '');
							$list_add[] = number_format($BVal["BillQty"]*$BVal["weight"], 2, '.', '');
							$writer->writeSheetRow('Sheet1', $list_add);
							$i++;
							$TotalQty += ($BVal["BillQty"]);
							$TotalDiscAmt += ($BVal["DiscAmt"]);
							$TotalAmt += ($BVal["BillQty"]*$BVal["SaleRate"])-$BVal["DiscAmt"];
							$Totalweight += ($BVal["BillQty"]*$BVal["weight"]);
						}
					}
					
					$AllTotalQty += $TotalQty;
					$AllTotalDiscAmt += $TotalDiscAmt;
					$AllTotalAmt += $TotalAmt;
					$AllTotalWeight += $Totalweight;
					$list_add = [];
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = "Total of ".$catVal["name"];
					$list_add[] = number_format($TotalQty, 2, '.', '');
					$list_add[] = "";
					$list_add[] = "";
					$list_add[] = number_format($TotalDiscAmt, 2, '.', '');
					$list_add[] = number_format($TotalAmt, 2, '.', '');
					$list_add[] = number_format($Totalweight, 2, '.', '');
					$writer->writeSheetRow('Sheet1', $list_add);
				}
				
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = "Total ";
				$list_add[] = number_format($AllTotalQty, 2, '.', '');
				$list_add[] = "";
				$list_add[] = "";
				$list_add[] = number_format($AllTotalDiscAmt, 2, '.', '');
				$list_add[] = number_format($AllTotalAmt, 2, '.', '');
				$list_add[] = number_format($AllTotalWeight, 2, '.', '');
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'GroupWiseItemSale.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* Item Category Wise Sale Report */
		public function ItemGroupWiseSale()
		{
			if (!has_permission_new('ItemGroupWiseSale', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Item Group Wise Sale";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/ItemGroupWiseSale', $data);
		}
		
		// Get Result for PartyItemWise report
		public function GetItemGroupWiseSaleReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$body_data = $this->sale_reports_model->GetItemGroupWiseSaleReportBodyData($filterdata);
			// echo json_encode($body_data);
			// die;
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="70%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">Group Name</th>';
			$html .= '<th class="sortable" align="center">Pkt Qty</th>';
			$html .= '<th class="sortable" align="center">CS/CR Qty</th>';
			$html .= '<th class="sortable" align="center">Weight</th>';
			$html .= '<th class="sortable" align="center">Amount</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			
			$WeightTotal = 0;
			$unitTotal = 0;
			$cscrTotal = 0;
			$AmtTotal = 0;
			foreach ($body_data as $key => $value) {
				$WeightTotal += $value["TotalWeight"];
				
				$unitTotal += number_format($value["Total_unit"], 0, '.', '');
				$cscrTotal += number_format($value["Total_Sale"], 2, '.', '');
				$AmtTotal += number_format(($value["Amount"]-$value["DiscAmt"]), 2, '.', '');
				$html .= '<tr>';
				$html .= '<td align="center">'.$i.'</td>';
				$html .= '<td align="left">'.$value["GroupName"].'</td>';
				$html .= '<td align="right">'.number_format($value["Total_unit"], 0, '.', '').'</td>';
				$html .= '<td align="right">'.number_format($value["Total_Sale"], 2, '.', '').'</td>';
				$html .= '<td align="right">'.number_format($value["TotalWeight"], 2, '.', '').'</td>';
				$html .= '<td align="right">'.number_format(($value["Amount"]-$value["DiscAmt"]), 2, '.', '').'</td>';
				$html .= '</tr>';
				$i++;
			}
			
			
			// Footer Data
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			
			$html .= '<tr>';
			$html .= '<td colspan="2" align="right"><b>Total</b></td>';
			$html .= '<td align="right"><b>'.number_format($unitTotal, 0, '.', '').'</b></td>';
			$html .= '<td align="right"><b>'.number_format($cscrTotal, 2, '.', '').'</b></td>';
			$html .= '<td align="right"><b>'.number_format($WeightTotal, 2, '.', '').'</b></td>';
			$html .= '<td align="right"><b>'.number_format($AmtTotal, 2, '.', '').'</b></td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function ExportItemGroupWiseSale()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				);
				
				$body_data = $this->sale_reports_model->GetItemGroupWiseSaleReportBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Item Group Wise Sale';
				$colspan = '9';
				
				
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = $AccountDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				
				$set_col_tk = [];
				
				$set_col_tk["Sr. No."] = 'Sr. No.';
				$set_col_tk["Group Name"] = 'Group Name';
				$set_col_tk["Pkt Qty"] = 'Pkt Qty';
				$set_col_tk["CS/CR Qty"] = 'CS/CR Qty';
				$set_col_tk["Weight(Kg)"] = 'Weight(Kg)';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				$sr = 1;
				$WeightTotal = 0;
				$unitTotal = 0;
				$cscrTotal = 0;
				foreach ($body_data as $key => $value) {
					$WeightTotal += $value["TotalWeight"];
					$unitTotal += number_format($value["Total_unit"], 0, '.', '');
					$cscrTotal += number_format($value["Total_Sale"], 2, '.', '');
					$list_add = [];
					$list_add[] = $sr;
					$list_add[] = $value["GroupName"];
					$list_add[] = number_format($value["Total_unit"], 0, '.', '');
					$list_add[] = number_format($value["Total_Sale"], 2, '.', '');
					$list_add[] = number_format($value["TotalWeight"], 2, '.', '');
					
					$writer->writeSheetRow('Sheet1', $list_add);
					$sr++;
				}
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "Total";
				$list_add[] = number_format($unitTotal, 0, '.', '');
				$list_add[] = number_format($cscrTotal, 2, '.', '');
				$list_add[] = number_format($WeightTotal, 2, '.', '');
				
				$writer->writeSheetRow('Sheet1', $list_add);
				// Footer Data
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'ItemGroupWiseSale.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* Item Division Wise Sale Report */
		public function ItemDivisionWiseSale()
		{
			if (!has_permission_new('ItemDivisionWiseSale', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Item Division Wise Sale";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/ItemDivisionWiseSale', $data);
		}
		
		
		// Get Result for PartyItemWise report
		public function GetItemDivisionWiseSaleReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			);
			$body_data = $this->sale_reports_model->GetItemDivisionWiseSaleReportBodyData($filterdata);
			// echo json_encode($body_data);
			// die;
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="left">Division Name</th>';
			$html .= '<th class="sortable" align="left">Pkt Qty</th>';
			$html .= '<th class="sortable" align="left">CS/CR Qty</th>';
			$html .= '<th class="sortable" align="center">Weight</th>';
			$html .= '<th class="sortable" align="center">Amount</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			$WeightTotal = 0;
			$unitTotal = 0;
			$cscrTotal = 0;
			$AmtTotal = 0;
			foreach ($body_data as $key => $value) {
				$Weight = $value["TotalWeight"];
				$WeightTotal += number_format($Weight, 2, '.', '');
				$unitTotal += number_format($value["Total_unit"], 0, '.', '');
				$cscrTotal += number_format($value["Total_Sale"], 2, '.', '');
				$AmtTotal += number_format(($value["Amount"]-$value["DiscAmt"]), 2, '.', '');
				$html .= '<tr>';
				$html .= '<td align="center">'.$i.'</td>';
				$html .= '<td align="left">'.$value["Division"].'</td>';
				$html .= '<td align="right">'.number_format($value["Total_unit"], 0, '.', '').'</td>';
				$html .= '<td align="right">'.number_format($value["Total_Sale"], 2, '.', '').'</td>';
				$html .= '<td align="right">'.number_format($Weight, 2, '.', '').'</td>';
				$html .= '<td align="right">'.number_format(($value["Amount"]-$value["DiscAmt"]), 2, '.', '').'</td>';
				$html .= '</tr>';
				$i++;
			}
			// Footer Data
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			$html .= '<tr>';
			$html .= '<td colspan="2" align="right"><b>Total</b></td>';
			$html .= '<td align="right"><b>'.number_format($unitTotal, 0, '.', '').'</b></td>';
			$html .= '<td align="right"><b>'.number_format($cscrTotal, 2, '.', '').'</b></td>';
			$html .= '<td align="right"><b>'.number_format($WeightTotal, 2, '.', '').'</b></td>';
			$html .= '<td align="right"><b>'.number_format($AmtTotal, 2, '.', '').'</b></td>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		
		public function ExportItemDivisionWiseSale()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				);
				
				$body_data = $this->sale_reports_model->GetItemDivisionWiseSaleReportBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Item Division Wise Sale';
				$colspan = '9';
				
				
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = $AccountDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				
				$set_col_tk = [];
				
				$set_col_tk["Sr. No."] = 'Sr. No.';
				$set_col_tk["Division Name"] = 'Division Name';
				$set_col_tk["Pkt Qty"] = 'Pkt Qty';
				$set_col_tk["CS/CR Qty"] = 'CS/CR Qty';
				$set_col_tk["Weight"] = 'Weight';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				$sr = 1;
				$WeightTotal = 0;
				$unitTotal = 0;
				$cscrTotal = 0;
				foreach ($body_data as $key => $value) {
					$WeightTotal += $value["TotalWeight"];
					$unitTotal += number_format($value["Total_unit"], 0, '.', '');
					$cscrTotal += number_format($value["Total_Sale"], 2, '.', '');
					$list_add = [];
					$list_add[] = $sr;
					$list_add[] = $value["Division"];
					$list_add[] = number_format($value["Total_unit"], 0, '.', '');
					$list_add[] = number_format($value["Total_Sale"], 2, '.', '');
					$list_add[] = number_format($value["TotalWeight"], 2, '.', '');;
					
					$writer->writeSheetRow('Sheet1', $list_add);
					$sr++;
				}
				$list_add = [];
				$list_add[] = "";
				$list_add[] = "Total";
				$list_add[] = number_format($unitTotal, 0, '.', '');
				$list_add[] = number_format($cscrTotal, 2, '.', '');
				$list_add[] = number_format($WeightTotal, 2, '.', '');
				$writer->writeSheetRow('Sheet1', $list_add);
				// Footer Data
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'ItemDivisionWiseSale.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* List all Food License Number Status */
		public function FoodLicenseNumberStatus()
		{
			if (!has_permission_new('FoodLicenseNumberStatus', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Food License Number Status";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['ClientsData'] = $this->sale_reports_model->get_client_data();
			// print_r($data['ClientsData']);die;
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/FoodLicenseNumberStatus', $data);
		}
		
		public function export_food_license_status()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			$data = $this->sale_reports_model->get_client_data();  
			
			$PlantDetail = $this->sale_reports_model->GetPlantDetails();
			$writer = new XLSXWriter();
			
			$company_name = array($PlantDetail->FIRMNAME);
			$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
			$writer->writeSheetRow('Sheet1', $company_name);
			
			$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
			$company_addr = array($address,);
			$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
			$writer->writeSheetRow('Sheet1', $company_addr);
			
			$msg = "Food License Number Status";
			$filter = array($msg);
			$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
			$writer->writeSheetRow('Sheet1', $filter);
			
			// empty row
			$list_add = [];
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$list_add[] = "";
			$writer->writeSheetRow('Sheet1', $list_add);
			
			
			$set_col_tk = [];
			$set_col_tk["Sr. No."] =  'Sr. No.';
			$set_col_tk["Customer Name"] =  'Customer Name';
			$set_col_tk["Station Name"] = 'Station Name';
			$set_col_tk["FSSAI No"] = 'FSSAI No';
			$set_col_tk["FSSAI Date of Expiry"] = 'FSSAI Date of Expiry';
			$set_col_tk["Day Remain"] = 'Day Remain';
			$writer_header = $set_col_tk;
			$writer->writeSheetRow('Sheet1', $writer_header);
			
			$i = 1;
			foreach ($data as $each) {
				if(!empty($each['FLNO1'])){
					// Today's date
					$today = new DateTime();
					
					// Date you want to calculate the remaining days until (31/01/2025)
					$targetDate = DateTime::createFromFormat('d/m/Y', _d(substr($each['expiry_licence'],0,10)));
					
					// Calculate the difference between the two dates
					$interval = $today->diff($targetDate);
					
					// Get the remaining days
					$remainingDays = $interval->days;
					
					if ($interval->invert) {
						// If the target date has passed, display the remaining days as negative
						$remainingDays = -$remainingDays;
					}
					$list_add = [];
					$list_add[] = $i;
					$list_add[] = $each['company'].' - ('.$each['AccountID'].')';
					$list_add[] = $each['StationName'];
					$list_add[] = " ".$each['FLNO1'];
					$list_add[] = _d(substr($each['expiry_licence'],0,10));
					$list_add[] = $remainingDays;
					
					$writer->writeSheetRow('Sheet1', $list_add);
					$i++;
				}
			}
			
			$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
			foreach($files as $file){
				if(is_file($file)) {
					unlink($file); 
				}
			}
			$filename = 'Food_license_status.xlsx';
			$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
			echo json_encode([
			'site_url'          => site_url(),
			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
			]);
			die;
			
		}
		
		
		/* PartyItem Wise report page */
		public function CD_Report()
		{
			if (!has_permission_new('CD_Report', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "CD Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/CD_Report', $data);
		}
		
		// Get Result for Party CD Report
		public function GetCDReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'AccountID'  => $this->input->post('AccountID'),
			);
			$body_data = $this->sale_reports_model->GetPartyCDReportBodyData($filterdata);
			// echo json_encode($body_data);
			// die;
			$html = '';
			$html .= '<div>';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">OrderID</th>';
			$html .= '<th class="sortable" align="center">Challan No.</th>';
			$html .= '<th class="sortable" align="center">Invoice No.</th>';
			$html .= '<th class="sortable" align="center">Date</th>';
			$html .= '<th class="sortable" align="left">Customer</th>';
			$html .= '<th class="sortable" align="right">Billing Amount</th>';
			$html .= '<th class="sortable" align="right">Disc Amount</th>';
			$html .= '<th class="sortable" align="right">Net Amount</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			foreach ($body_data as $key => $value) {
				$html .= '<tr>';
				$html .= '<td align="center">'.$i.'</td>';
				$html .= '<td align="center">'.$value["OrderID"].'</td>';
				$html .= '<td align="center">'.$value["ChallanID"].'</td>';
				$html .= '<td align="center">'.$value["SalesID"].'</td>';
				$html .= '<td align="center">'._d(substr($value["Transdate"],0,10)).'</td>';
				$html .= '<td align="left">'.$value["company"].'</td>';
				$html .= '<td align="right">'. $value["RndAmt"].'</td>';
				$html .= '<td align="right">'. round($value["DiscAmt"]).'</td>';
				$html .= '<td align="right">'. number_format($value["DiscAmt"] + $value["RndAmt"], 2, ".", "").'</td>';
				$html .= '</tr>';
				$i++;
				
			}
			$html .= '</tbody>';
			
			echo json_encode($html);
			die;
		}
		
		public function ExportCDReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'AccountID'  => $this->input->post('AccountID'),
				);
				$AccountName = $this->input->post('AccountName');
				$AccountAddress = $this->input->post('AccountAddress');
				$AccountAddress2 = $this->input->post('AccountAddress2');
				$station = $this->input->post('station');
				$StateName = $this->input->post('StateName');
				$body_data = $this->sale_reports_model->GetPartyCDReportBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Account Name : '. $AccountName.' Address : '.$AccountAddress.' '.$AccountAddress2;
				$OtherDetails = 'Station : '.$station .' State : '. $StateName;
				$colspan = '9';
				
				
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				if(!empty($this->input->post('AccountID')))
				{
					$msg2 = $AccountDetails;
					$filter2 = array($msg2);
					$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
					$writer->writeSheetRow('Sheet1', $filter2);
				}
				
				
				$set_col_tk = [];
				
				$set_col_tk["OrderID"] = 'OrderID';
				$set_col_tk["Challan No."] = 'Challan No.';
				$set_col_tk["Invoice No."] = 'Invoice No.';
				$set_col_tk["Date"] = 'Date';
				$set_col_tk["Customer"] = 'Customer';
				$set_col_tk["Billing Amount"] = 'Billing Amount';
				$set_col_tk["Disc Amount"] = 'Disc Amount';
				$set_col_tk["Net Amount"] = 'Net Amount';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 1;
				foreach ($body_data as $key => $value) {
					
					$list_add = [];
					$list_add[] = $value["OrderID"];
					$list_add[] = $value["ChallanID"];
					$list_add[] = $value["SalesID"];
					$list_add[] = _d(substr($value["Transdate"],0,10));
					$list_add[] = $value["company"];
					$list_add[] = $value["RndAmt"];
					
					$list_add[] = round($value["DiscAmt"]);
					$list_add[] = number_format($value["DiscAmt"] + $value["RndAmt"], 2, ".", "");
					$i++;
					$writer->writeSheetRow('Sheet1', $list_add);
					
				}
				
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'CDReport.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* PartyItem Wise report page */
		public function SaleItemFlowReport()
		{
			if (!has_permission_new('SaleItemFlowReport', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Sale Item Flow Report";
			$data['company_detail'] = $this->sale_reports_model->get_company_detail();
			$data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
			$data['ItemListFG'] =  $this->sale_reports_model->ItemListFG();
			// var_dump($data['ItemListFG']);
			// die;
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/SaleItemFlowReport', $data);
		}
		
		// Get Result for PartyItemWise report
		public function GetSaleItemFlowReport()
		{
			$filterdata = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'ItemID'  => $this->input->post('ItemID'),
			);
			$body_data = $this->sale_reports_model->GetSaleItemFlowReportBodyData($filterdata);
			// echo json_encode($body_data);
			// die;
			$html = '';
			$html .= '<table class="table-striped table-bordered SaleVsSaleRtn_report" id="SaleVsSaleRtn_report" width="100%">';
			$html .= '<thead style="font-size:11px;">';
			$html .= '<tr>';
			$html .= '<th class="sortable" align="center">Sr.No</th>';
			$html .= '<th class="sortable" align="center">Bill No.</th>';
			$html .= '<th class="sortable" align="center">Date</th>';
			$html .= '<th class="sortable" align="left">Client Name</th>';
			$html .= '<th class="sortable" align="right">PKt Qty</th>';
			$html .= '<th class="sortable" align="right">CS/CR Qty</th>';
			$html .= '<th class="sortable" align="right">Weight</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$i = 1;
			
			$chkid = '';
			$caseqtytotal = 0;
			$totalweight = 0;
			foreach ($body_data as $key => $value) {
				$totalqty += (int) $value["BilledQty"];
				$caseqtytotal += (float) $value["totCaseQty"];
				$totalweight += (float) $value["TotalWeight"];
				$html .= '<tr>';
				$html .= '<td align="center">'.$i.'</td>';
				$html .= '<td align="center">'.$value["TransID"].'</td>';
				$html .= '<td align="center">'._d(substr($value["TransDate2"],0,10)).'</td>';
				$html .= '<td align="left">'.$value["company"].'</td>';
				$html .= '<td align="right">'.(int) $value["BilledQty"].'</td>';
				$html .= '<td align="right">'.(float) $value["totCaseQty"].'</td>';
				$html .= '<td align="right">'.(float) $value["TotalWeight"].'</td>';
				$i++;
			}
			
			$html .= '</tbody>';
			$html .= '<tfoot>';
			
			$html .= '<tr>';
			$html .= '<td align="right" colspan="4">Total</td>';
			$html .= '<td align="right">'.$totalqty.'</td>';
			$html .= '<td align="right">'.$caseqtytotal.'</td>';
			$html .= '<td align="right">'.$totalweight.'</td>';
			// Footer Data
			$html .= '</tfoot>';
			$html .= '</table>';
			echo json_encode($html);
			die;
		}
		
		public function ExportSaleItemFlowReport()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$filterdata = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'ItemID'  => $this->input->post('ItemID'),
				);
				
				$body_data = $this->sale_reports_model->GetSaleItemFlowReportBodyData($filterdata);
				$company_detail = $this->sale_reports_model->get_company_detail();
				/*echo json_encode($body_data);
				die;*/
				$AccountDetails = 'Sale Item Flow Report';
				$colspan = '9';
				
				
				$writer = new XLSXWriter();
				$company_name = array($company_detail->company_name);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $company_detail->address;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date')." - Item (".$this->input->post('Itemname').")";
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter);
				
				$msg2 = $AccountDetails;
				$filter2 = array($msg2);
				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
				$writer->writeSheetRow('Sheet1', $filter2);
				
				
				$set_col_tk = [];
				
				$set_col_tk["Bill No."] = 'Bill No.';
				$set_col_tk["Date"] = 'Date';
				$set_col_tk["Client Name"] = 'Client Name';
				$set_col_tk["Pkt Qty"] = 'Pkt Qty';
				$set_col_tk["CS/CR Qty"] = 'CS/CR Qty';
				$set_col_tk["Weight"] = 'Weight';
				
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				
				$i = 1;
				$totalqty = 0;
				$caseqtytotal = 0;
				$totalweight = 0;
				foreach ($body_data as $key => $value) {
					$totalqty += (int) $value["BilledQty"];
					$caseqtytotal += (float) $value["totCaseQty"];
					$totalweight += (float) $value["TotalWeight"];
					$list_add = [];
					$list_add[] = $value["TransID"];
					$list_add[] = _d(substr($value["TransDate2"],0,10));
					$list_add[] = $value["company"];
					$list_add[] = (int) $value["BilledQty"];
					$list_add[] = (float) $value["totCaseQty"];
					$list_add[] = (float) $value["TotalWeight"];
					
					$i++;
					$writer->writeSheetRow('Sheet1', $list_add);
					
				}
				$list_add = [];
				$list_add[] = '';
				$list_add[] = 'Total';
				$list_add[] = $totalqty;
				$list_add[] = $caseqtytotal;
				$list_add[] = $totalweight;
				$list_add[] = '';
				
				$writer->writeSheetRow('Sheet1', $list_add);
				
				// Footer Data
				
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'SaleItemFlowReport.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		/* List all Party Pack Wise Commulatives Sales datatables */
		public function ShipToPartyPackWiseCummulativesSales()
		{
			if (!has_permission_new('cummulatives_sale_shipto', '', 'view')) {
				access_denied('orders');
			}
			
			close_setup_menu();
			$data['title']                = "Ship To Party Pack Wise Commulatives Sales";
			$this->load->model('clients_model');
			$data['states'] = $this->clients_model->getallstate();
			$data['groups'] = $this->clients_model->get_groups();
			$cur_date = date('d/m/Y');
			$m = date('m');
			$y = date('Y');
			$date1 = "01/".$m."/".$y;
			$data['from_date'] = $date1;
			$data['to_date'] = $cur_date;
			
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/ShipToPartyPackWiseCommulativesSales', $data);
		}
		
		
		
		public function CustomerPerformanceReport()
		{
			if (!has_permission_new('CustomerPerformanceReport', '', 'view')) {
				access_denied('orders');
			}
			close_setup_menu();
			$data['title']                = "Customer Performance Report";
			$data['PlantDetail'] = $this->sale_reports_model->GetPlantDetails();
			$data['routes']    = $this->sale_reports_model->getroute();
			$data['bodyclass']            = 'invoices-total-manual';
			$this->load->view('admin/sale_reports/CustomerPerformanceReport', $data);
		}
		
		public function GetCustPerformReport()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'Route'  => $this->input->post('Route')
			);
			$Clients = $this->sale_reports_model->GetClientsForPerformReport($data);
			$saledata = $this->sale_reports_model->GetSaleByDate($data);
			$SaleReturn = $this->sale_reports_model->GetSaleReturnByDate($data);
			$PaymentData = $this->sale_reports_model->GetPaymentByDate($data);
			$CratesData = $this->sale_reports_model->GetCratesByDate($data);
			$DayBeforeTransaction = $this->sale_reports_model->DayBeforeTransactionCrate($data);
			$OpenCrates = $this->sale_reports_model->GetOpeningCrates($data);
			// echo "<pre>";print_r($DayBeforeTransaction);die;
			$html = '';
			
			$AllsaleAmt = 0;
			$AllsaleRtnAmt = 0;
			$AllPaymentAmt = 0;
			$AllInCrate = 0;
			$AllOutCrate = 0;
			$AllBalanceAmt = 0;
			$AllBalanceCrate = 0;
			
			foreach($Clients as $Client){
				$saleAmt = '';
				$saleRtnAmt = '';
				$PaymentAmt = '';
				$InCrate = '';
				$OutCrate = '';
				$BeforeOutCrate = '';
				$BeforeInCrate = '';
				$OpeningCrate = '';
				foreach($saledata as $sale){
					if($sale['AccountID'] == $Client['AccountID']){
						$saleAmt += $sale['BillAmt'];
					}
				}
				foreach($SaleReturn as $Return){
					if($Return['AccountID'] == $Client['AccountID']){
						$saleRtnAmt += $Return['BillAmt'];
					}
				}
				foreach($PaymentData as $Payment){
					if($Payment['AccountID'] == $Client['AccountID']){
						$PaymentAmt += $Payment['Amount'];
					}
				}
				foreach($DayBeforeTransaction as $Transaction){
					if($Transaction['AccountID'] == $Client['AccountID']){
						if($Transaction['TType'] == 'D'){
							$BeforeOutCrate += $Transaction['Qty'];
						}
						if($Transaction['TType'] == 'C'){
							$BeforeInCrate += $Transaction['Qty'];
						}
					}
				}
				$BeforeCrate = $BeforeOutCrate - $BeforeInCrate;
				
				
				foreach($OpenCrates as $OpeningCrates){
					if($OpeningCrates['AccountID'] == $Client['AccountID']){
						if($OpeningCrates['TType'] == 'D'){
							$OpeningCrate += $OpeningCrates['Qty'];
						}
						if($OpeningCrates['TType'] == 'C'){
							$OpeningCrate -= $OpeningCrates['Qty'];
						}
					}
				}
				$OpenCrate = $OpeningCrate + $BeforeCrate;
				
				foreach($CratesData as $Crates){
					if($Crates['AccountID'] == $Client['AccountID']){
						if($Crates['TType'] == 'D'){
							$OutCrate += $Crates['Qty'];
						}
						if($Crates['TType'] == 'C'){
							$InCrate += $Crates['Qty'];
						}
					}
				}
				
				
				$BalanceAmt = $saleAmt - $saleRtnAmt - $PaymentAmt;
				if($BalanceAmt == 0){
					$BalanceAmt = '';
				}
				
				$BalanceCrate = $OpenCrate + $OutCrate - $InCrate;
				if($BalanceCrate == 0){
					$BalanceCrate = '';
				}
				
				$balanBold = '';
				if($BalanceAmt > $Client['MaxCrdAmt']){
					$balanBold = 'font-weight:bold';
				}
				$crateBold = '';
				if($BalanceCrate > $Client['crate_limit']){
					$crateBold = 'font-weight:bold';
				}
				$html .= "<tr  onclick='GetSaleReport(\"".$Client['AccountID']."\")' style='cursor:pointer;'>";
				$html .= "<td>".$Client['AccountID']."</td>";
				$html .= "<td>".$Client['company']."</td>";
				$html .= "<td>".round($saleAmt)."</td>";
				$html .= "<td>".round($saleRtnAmt)."</td>";
				$html .= "<td>".round($PaymentAmt)."</td>";
				$html .= "<td style='".$balanBold."'>".round($BalanceAmt)."</td>";
				$html .= "<td>".$OpenCrate."</td>";
				$html .= "<td>".$OutCrate."</td>";
				$html .= "<td>".$InCrate."</td>";
				$html .= "<td style='".$crateBold."'>".$BalanceCrate."</td>";
				$html .= "</tr>";
				
				$AllsaleAmt += round($saleAmt);
				$AllsaleRtnAmt += round($saleRtnAmt);
				$AllPaymentAmt += round($PaymentAmt);
				$AllBalanceAmt += round($BalanceAmt);
				$AllInCrate += $InCrate;
				$AllOutCrate += $OutCrate;
				$AllBalanceCrate += $BalanceCrate;
				
			}
			$html .= "<tr>";
			$html .= "<td colspan='2' align='right'>Total</td>";
			$html .= "<td>".$AllsaleAmt."</td>";
			$html .= "<td>".$AllsaleRtnAmt."</td>";
			$html .= "<td>".$AllPaymentAmt."</td>";
			$html .= "<td>".$AllBalanceAmt."</td>";
			$html .= "<td></td>";
			$html .= "<td>".$AllOutCrate."</td>";
			$html .= "<td>".$AllInCrate."</td>";
			$html .= "<td>".$AllBalanceCrate."</td>";
			$html .= "</tr>";
			echo json_encode($html);
		}
		
		public function export_customer_performance()
		{
			if(!class_exists('XLSXReader_fin')){
				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');
			}
			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');
			
			if($this->input->post()){
				
				$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date'  => $this->input->post('to_date'),
				'Route'  => $this->input->post('Route'),
				);
				
				$Clients = $this->sale_reports_model->GetClientsForPerformReport($data);
				$saledata = $this->sale_reports_model->GetSaleByDate($data);
				$SaleReturn = $this->sale_reports_model->GetSaleReturnByDate($data);
				$PaymentData = $this->sale_reports_model->GetPaymentByDate($data);
				$CratesData = $this->sale_reports_model->GetCratesByDate($data);
				$DayBeforeTransaction = $this->sale_reports_model->DayBeforeTransactionCrate($data);
				$OpenCrates = $this->sale_reports_model->GetOpeningCrates($data);
				
				$selected_company_details    = $this->sale_reports_model->get_company_detail();
				$PlantDetail = $this->sale_reports_model->GetPlantDetails();
				$writer = new XLSXWriter();
				
				$company_name = array($PlantDetail->FIRMNAME);
				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_name);
				
				$address = $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2;
				$company_addr = array($address,);
				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 9);  //merge cells
				$writer->writeSheetRow('Sheet1', $company_addr);
				
				$msg = "Customer Performance Report ".$this->input->post('from_date')." To " .$this->input->post('to_date');
				$filter = array($msg);
				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 9);  //merge cells
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
				$list_add[] = "";
				$writer->writeSheetRow('Sheet1', $list_add);
				
				
				$set_col_tk = [];
				$set_col_tk["Customer ID"] =  'Customer ID';
				$set_col_tk["Customer Name"] = 'Customer Name';
				$set_col_tk["Sale Amt"] = 'Sale Amt';
				$set_col_tk["Return Amt"] = 'Return Amt';
				$set_col_tk["Payment Amt"] = 'Payment Amt';
				$set_col_tk["Balance Amt"] = 'Balance Amt';
				$set_col_tk["Crate Out"] = 'Crate Out';
				$set_col_tk["Crate In"] = 'Crate In';
				$set_col_tk["Balance Crate"] = 'Balance Crate';
				$writer_header = $set_col_tk;
				$writer->writeSheetRow('Sheet1', $writer_header);
				$AllsaleAmt = 0;
				$AllsaleRtnAmt = 0;
				$AllPaymentAmt = 0;
				$AllInCrate = 0;
				$AllOutCrate = 0;
				$AllBalanceAmt = 0;
				$AllBalanceCrate = 0;
				
				foreach($Clients as $Client){
					$saleAmt = '';
					$saleRtnAmt = '';
					$PaymentAmt = '';
					$InCrate = '';
					$OutCrate = '';
					$BeforeOutCrate = '';
					$BeforeInCrate = '';
					$OpeningCrate = '';
					foreach($saledata as $sale){
						if($sale['AccountID'] == $Client['AccountID']){
							$saleAmt += $sale['BillAmt'];
						}
					}
					foreach($SaleReturn as $Return){
						if($Return['AccountID'] == $Client['AccountID']){
							$saleRtnAmt += $Return['BillAmt'];
						}
					}
					foreach($PaymentData as $Payment){
						if($Payment['AccountID'] == $Client['AccountID']){
							$PaymentAmt += $Payment['Amount'];
						}
					}
					foreach($DayBeforeTransaction as $Transaction){
						if($Transaction['AccountID'] == $Client['AccountID']){
							if($Transaction['TType'] == 'D'){
								$BeforeOutCrate += $Transaction['Qty'];
							}
							if($Transaction['TType'] == 'C'){
								$BeforeInCrate += $Transaction['Qty'];
							}
						}
					}
					$BeforeCrate = $BeforeOutCrate - $BeforeInCrate;
					
					
					foreach($OpenCrates as $OpeningCrates){
						if($OpeningCrates['AccountID'] == $Client['AccountID']){
							if($OpeningCrates['TType'] == 'D'){
								$OpeningCrate += $OpeningCrates['Qty'];
							}
							if($OpeningCrates['TType'] == 'C'){
								$OpeningCrate -= $OpeningCrates['Qty'];
							}
						}
					}
					$OpenCrate = $OpeningCrate + $BeforeCrate;
					
					foreach($CratesData as $Crates){
						if($Crates['AccountID'] == $Client['AccountID']){
							if($Crates['TType'] == 'D'){
								$OutCrate += $Crates['Qty'];
							}
							if($Crates['TType'] == 'C'){
								$InCrate += $Crates['Qty'];
							}
						}
					}
					
					
					$BalanceAmt = $saleAmt - $saleRtnAmt - $PaymentAmt;
					if($BalanceAmt == 0){
						$BalanceAmt = '';
					}
					
					$BalanceCrate = $OpenCrate + $OutCrate - $InCrate;
					if($BalanceCrate == 0){
						$BalanceCrate = '';
					}
					
					$list_add = [];
					$list_add[] = $Client['AccountID'];
					$list_add[] = $Client['company'];
					$list_add[] = round($saleAmt);
					$list_add[] = round($saleRtnAmt);
					$list_add[] = round($PaymentAmt);
					$list_add[] = round($BalanceAmt);
					$list_add[] = $OutCrate;
					$list_add[] = $InCrate;
					$list_add[] = $BalanceCrate;
					$writer->writeSheetRow('Sheet1', $list_add);
					
					$AllsaleAmt += round($saleAmt);
					$AllsaleRtnAmt += round($saleRtnAmt);
					$AllPaymentAmt += round($PaymentAmt);
					$AllBalanceAmt += round($BalanceAmt);
					$AllInCrate += $InCrate;
					$AllOutCrate += $OutCrate;
					$AllBalanceCrate += $BalanceCrate;
					
					
				}
				
				$list_add = [];
				$list_add[] = '';
				$list_add[] = 'Total';
				$list_add[] = $AllsaleAmt;
				$list_add[] = $AllsaleRtnAmt;
				$list_add[] = $AllPaymentAmt;
				$list_add[] = $AllBalanceAmt;
				$list_add[] = $AllOutCrate;
				$list_add[] = $AllInCrate;
				$list_add[] = $AllBalanceCrate;
				$writer->writeSheetRow('Sheet1', $list_add);
				
				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file); 
					}
				}
				$filename = 'CustomerPerformance_Report.xlsx';
				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));
				echo json_encode([
				'site_url'          => site_url(),
				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,
				]);
				die;
			}
		}
		
		public function CityWiseSales()
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
			$result = $this->sale_reports_model->CityWiseSales($data);
			
			
			echo json_encode($result);
		}
		public function CityWiseSalesNew()
		{
			
			$result = $this->sale_reports_model->CityWiseSalesNew($this->input->post());
			
			
			echo json_encode($result);
		}
		public function CityWiseCustomers()
		{
			$result = $this->sale_reports_model->CityWiseCustomers();
			
			
			echo json_encode($result);
		}
		
		public function GetFreshVsDamageSalesReturnReport()
		{
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			'state'  => $this->input->post('state'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'TType2'  => 'Fresh',
			'ReportIn'  => $this->input->post('ReportIn'),
			);
			$Fresh = $this->sale_reports_model->GetFreshVsDamageSalesReturn($data);
			
			
			$data = array(
			'from_date' => $this->input->post('from_date'),
			'to_date'  => $this->input->post('to_date'),
			'SubGroup'  => $this->input->post('SubGroup'),
			'Items'  => $this->input->post('Items'),
			'state'  => $this->input->post('state'),
			'MaxCount'  => $this->input->post('MaxCount'),
			'TType2'  => 'Damage',
			'ReportIn'  => $this->input->post('ReportIn'),
			);
			$Damage = $this->sale_reports_model->GetFreshVsDamageSalesReturn($data);
			// echo "</pre>";print_r($Damage);die;
			$chart = [];
			$DamageData = [];
			
			$itemData = [];
			foreach($Fresh as $return){
				if($this->input->post('ReportIn') == 'amount'){
					$value = (float) $return["ReturnAmt"];
					}else{
					$value = (float) $return["ReturnQty"];
				}
				array_push($chart, [
				'name' 		=> $return["month"],
				'y' 		=>	$value,
				]);
				
				$itemData[$return['ItemID']] = $return['month'];
			}
			foreach($Damage as $return){
				if($this->input->post('ReportIn') == 'amount'){
					$value = (float) $return["ReturnAmt"];
					}else{
					$value = (float) $return["ReturnQty"];
				}
				array_push($DamageData, [
				'name' 		=> $return["month"],
				'y' 		=>	$value,
				]);
				
			}
			$data = [
		    'Fresh' => $chart,
		    'Damage' => $DamageData,
			];
			
			echo json_encode($data);
		}
	}
