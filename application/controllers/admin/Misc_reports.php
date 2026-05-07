<?php

	

	defined('BASEPATH') or exit('No direct script access allowed');

	

	class Misc_reports extends AdminController

	{

		private $not_importable_fields = ['id'];

		

		public function __construct()

		{

			parent::__construct();

			$this->load->model('misc_reports_model');

			$this->load->model('sale_reports_model');

		}

		

		/* Start Stock Position report code */

		

		public function stock_position()

		{

			if (!has_permission_new('stock_position', '', 'view')) {

				access_denied('access_denied');

			}

			$data['main_item_group'] = $this->misc_reports_model->get_main_item_group();

			$data['GodownData'] = $this->misc_reports_model->GetGodownData();

			$data['title'] = "Stock Reports";

			$this->load->view('admin/misc_reports/stock_reports', $data);

		}

		

		public function StockCummulative()

		{

			if (!has_permission_new('stock_position', '', 'view')) {

				access_denied('access_denied');

			}

			$data['main_item_group'] = $this->misc_reports_model->get_main_item_group();

			$data['GodownData'] = $this->misc_reports_model->GetGodownData();

			$data['title'] = "Stock Reports";

			$this->load->view('admin/misc_reports/stockCommulative', $data);

		}

		

		public function stock_positionNew()

		{

			if (!has_permission_new('stock_position', '', 'view')) {

				access_denied('access_denied');

			}

			

			$data['main_item_group'] = $this->misc_reports_model->get_main_item_group();

			

			/*echo "<pre>";

				print_r($data['main_item_group']);

			die;*/

			$data['title'] = "Stock Reports";

			$this->load->view('admin/misc_reports/StockReportNew', $data);

		}

		

		/* Get Item Group */

		public function get_item_group()

		{

			$main_item_group_id = $this->input->post('main_item_group_id');

			$item_group = $this->misc_reports_model->GetItemGroupByMainGroupID($main_item_group_id);

			

			echo json_encode($item_group);

		}

		

		/*public function get_item_groupFR_StkP()

			{

			$data = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'main_item_group_id'  => $this->input->post('main_item_group_id')

			);

			$data = $this->misc_reports_model->get_sale_item_group2($data);

			echo json_encode($data);

		}*/

		

		public function get_item_groupFR_StkP()

		{

			$data = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'main_item_group_id'  => $this->input->post('main_item_group_id')

			);

			$mainGroupID = $this->input->post('main_item_group_id');

			$data = $this->misc_reports_model->GetItemGroupByMainGroupID($mainGroupID);

			echo json_encode($data);

		}

		

		public function export_stock_report()

		{

        	if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$filterdata = array(

				'from_date' => $this->input->post('from_date'),

				'to_date'  => $this->input->post('to_date'),

				'GodownID'  => $this->input->post('GodownID')

				);

				

				$from_date = to_sql_date($this->input->post('from_date'));

				$item_group = $this->input->post('item_group');

				$item_main_group = $this->input->post('item_main_group');

				$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

				$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

				$selected_company = $this->session->userdata('root_company');

				$company_data = $this->misc_reports_model->get_company_detail();

				$AllItemList = $this->misc_reports_model->GetItemList($filterdata,$item_group);

				$StockData = $this->misc_reports_model->GetStockData($filterdata,$item_group);

				$StockOQtyData = $this->misc_reports_model->get_item_open_qty($filterdata,$item_group);

				$fy = $this->session->userdata('finacial_year');

				$writer = new XLSXWriter();

				

				$company_name = array($company_data->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $company_data->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Stock Report of : ".$item_maingroup_name->name."(Stock Value with GST): " .$this->input->post('from_date')." to ".$this->input->post('to_date')." -  Stock in Cases ";

    			$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				$j++;

				

				

				$msg1 = "Rates based on : State - UP & Dist.Type - SS";

				$filter1 = array($msg1);

				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter1);

				$j++;

				

				$msg2 = "Item Group: ".$item_group_name;

				$filter2 = array($msg2);

				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter2);

				$j++;

				

				$PurchQtyCasesSumC = 0;

				$InwardQtyCasesSumC = 0;

				$PurchRtnQtyCasesSumC = 0;

				$IssueQtyCasesSumC = 0;

				$PRDCasesSumC = 0;

				$SalesCasesSumC = 0;

				$SalesRtnCasesSumC = 0;

				$AdjCasesSumC = 0;

				$GOCasesSumC = 0;

				$GICasesSumC = 0;

				foreach ($AllItemList as $key => $value) {

					if($value["case_qty"] > 0){

						$CaseQty = $value["case_qty"];

						}else{

						$CaseQty = $value["crate_qty"];

					}

					$OQTY = 0;

					$PurchQtyC = 0;

					$PurchQtyCasesC = 0;

					

					$InwardQtyC = 0;

					$InwardQtyCasesC = 0;

					

					$PurchRtnQtyC = 0;

					$PurchRtnQtyCasesC = 0;

					

					$IssueQtyC = 0;

					$IssueQtyCasesC = 0;

					

					$PRDQtyC = 0;

					$PRDCasesC = 0;

					

					$SalesQtyC = 0;

					$SalesCasesC = 0;

					

					$SalesRtnQtyC = 0;

					$SalesRtnCasesC = 0;

					

					$AdjQtyC = 0;

					$AdjCasesC = 0;

					

					$GOQtyC = 0;

					$GOCasesC = 0;

					

					$GIQtyC = 0;

					$GICasesC = 0;

					foreach ($StockData as $key1 => $value1) {

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){

							$PurchQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

							$InwardQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

							$PurchRtnQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

							$IssueQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

							$PRDQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

							$SalesQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh" )){

							$SalesRtnQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment")){

							$AdjQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out" )){

							$GOQtyC += $value1['BilledQty'];

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In" )){

							$GIQtyC += $value1['BilledQty'];

						}

					}

					if($PurchQtyC !== '0'){

						$PurchQtyCasesC = floatval($PurchQtyC) / floatval($CaseQty);

						$PurchQtyCasesSumC += $PurchQtyCasesC;

					}

					if($InwardQtyC !== '0'){

						$InwardQtyCasesC = floatval($InwardQtyC) / floatval($CaseQty);

						$InwardQtyCasesSumC += $InwardQtyCasesC;

					}

					

					if($PurchRtnQtyC !== '0'){

						$PurchRtnQtyCasesC = floatval($PurchRtnQtyC) / floatval($CaseQty);

						$PurchRtnQtyCasesSumC += $PurchRtnQtyCasesC;

					}

					

					if($IssueQtyC !== '0'){

						$IssueQtyCasesC = floatval($IssueQtyC) / floatval($CaseQty);

						$IssueQtyCasesSumC += $IssueQtyCasesC;

					}

					

					if($PRDQtyC !== '0'){

						$PRDCasesC = floatval($PRDQtyC) / floatval($CaseQty);

						$PRDCasesSumC += $PRDCasesC;

					}

					

					

					if($SalesQtyC !== '0'){

						$SalesCasesC = floatval($SalesQtyC) / floatval($CaseQty);

						$SalesCasesSumC += $SalesCasesC;

					}

					

					if($SalesRtnQtyC !== '0'){

						$SalesRtnCasesC = floatval($SalesRtnQtyC) / floatval($CaseQty);

						$SalesRtnCasesSumC += $SalesRtnCasesC;

					}

					

					if($AdjQtyC !== '0'){

						$AdjCasesC = floatval($AdjQtyC) / floatval($CaseQty);

						$AdjCasesSumC += $AdjCasesC;

					}

					

					if($GOQtyC !== '0'){

						$GOCasesC = floatval($GOQtyC) / floatval($CaseQty);

						$GOCasesSumC += $GOCasesC;

					}

					

					if($GIQtyC !== '0'){

						$GICasesC = floatval($GIQtyC) / floatval($CaseQty);

						$GICasesSumC += $GICasesC;

					}

				}

				

				$list_add = [];

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

					$list_add[] = "";

				}

				if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

					$list_add[] = "";

				}  

				if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

					$list_add[] = "";

				} 

				if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

					$list_add[] = "";

				} 

				if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

					$list_add[] = "";

				}  

				if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

					$list_add[] = "";

				}  

				if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

					$list_add[] = "";

				}  

				if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

					$list_add[] = "";

				}

				if($GOCasesSumC > 0 || $GOCasesSumC < 0){

					$list_add[] = "";

				}

				if($GICasesSumC > 0 || $GICasesSumC < 0){

					$list_add[] = "";

				}

				$list_add[] = "";

				$list_add[] = "";

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				if($accountId !== ''){

					$set_col_tk["ItemID"] =  'ItemID';

					$set_col_tk["ItemName"] =  'ItemName';

					$set_col_tk["Pkg"] =  'Pkg';

					$set_col_tk["U"] =  'Unit';

					$set_col_tk["OpenQty"] =  'OpenQty';

					if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

						$set_col_tk["PurchQty"] =  'PurchQty';

					}

					if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

						$set_col_tk["Inward"] =  'Inward';

					}

					if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

						$set_col_tk["PurchRtn"] =  'PurchRtn';

					}

					if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

						$set_col_tk["IssueQty"] =  'IssueQty';

					}

					if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

						$set_col_tk["Production"] =  'Production';

					}

					if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

						$set_col_tk["SalesQty"] =  'SalesQty';

					}

					if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

						$set_col_tk["SalesRtn"] =  'SalesRtn';

					}

					if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

						$set_col_tk["AdjQty"] =  'AdjQty';

					}

					if($GOCasesSumC > 0 || $GOCasesSumC < 0){

						$set_col_tk["GTOQty"] =  'GTOQty';

					}

					if($GICasesSumC > 0 || $GICasesSumC < 0){

						$set_col_tk["GTIQty"] =  'GTIQty';

					}

					

					$set_col_tk["Bal.Qty"] =  'Bal.Qty';

					$set_col_tk["Rate"] =  'Rate';

					$set_col_tk["StkValue"] =  'StkValue';

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$OQTYCasesSum = 0;

				$PurchQtyCasesSum = 0;

				$InwardQtyCasesSum = 0;

				$PurchRtnQtyCasesSum = 0;

				$IssueQtyCasesSum = 0;

				$PRDCasesSum = 0;

				$SalesCasesSum = 0;

				$SalesRtnCasesSum = 0;

				$AdjCasesSum = 0;

				$GOCasesSum = 0;

				$GICasesSum = 0;

				$BQtySum = 0;

				foreach ($AllItemList as $key => $value) {

					$rate = 0;

					

					if($value["case_qty"] > 0){

						$CaseQty = $value["case_qty"];

						}else{

						$CaseQty = $value["crate_qty"];

					}

					

					$OQTY = 0;

					$PurchQty = 0;

					$PurchQtyCases = 0;

					

					$InwardQty = 0;

					$InwardQtyCases = 0;

					

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

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

							$InwardQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

							$PurchRtnQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

							$IssueQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

							$PRDQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

							$SalesQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh" )){

							$SalesRtnQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment")){

							$AdjQty += $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

							$GOQty += $value1['BilledQty'];

							$GOValueSum += $value1["SaleRate"] * $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

						if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

							$GIQty += $value1['BilledQty'];

							$GIValueSum += $value1["SaleRate"] * $value1['BilledQty'];

							if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

								$rate = $value1["SaleRate"];

							}

						}

					}

					if($PurchQty !== '0'){

						$PurchQtyCases = floatval($PurchQty) / floatval($CaseQty);

						$PurchQtyCasesSum += $PurchQtyCases;

					}

					if($InwardQty !== '0'){

						$InwardQtyCases = floatval($InwardQty) / floatval($CaseQty);

						$InwardQtyCasesSum += $InwardQtyCases;

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

							

							if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "P" && $valueOQty['TType2'] == "Purchase"){

								$OQtySum += $valueOQty['billsum'];

							}

							if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "I" && $valueOQty['TType2'] == "Inward"){

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

							if(trim((strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"]))) && ($valueOQty['TType'] == "R" && $valueOQty["TType2"] == "Fresh")){

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

					$BQty =    $OQTYCases +   $PurchQtyCases +   $InwardQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases - $GOCases + $GICases;

					$BQtySum += $BQty;    

					if(floatval($OQTYCases) == '0.00' && floatval($PurchQtyCases) == "0.00" && floatval($InwardQtyCases) == "0.00" && floatval($PurchRtnQtyCases) == "0.00" && floatval($IssueQtyCases) == "0.00" && floatval($PRDCases) == "0.00" && floatval($SalesCases) == "0.00" && floatval($SalesRtnCases) == "0.00" && floatval($AdjCases) == "0.00" && floatval($GOCases) == "0.00" && floatval($GICases) == "0.00"){

						

						}else{

						$list_add = [];

						$list_add[] = $value["item_code"];

						$list_add[] = $value["description"];

						$list_add[] = $CaseQty;

						$list_add[] = $value["unit"];

						$list_add[] = round($OQTYCases,2);

						if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

							$list_add[] = round((float)($PurchQtyCases), 2);

						}

						if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

							$list_add[] = round((float)($InwardQtyCases), 2);

						}

						

						if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

							$list_add[] = round((float)($PurchRtnQtyCases), 2);

						}

						

						if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

							$list_add[] = round((float)($IssueQtyCases), 2);

						}

						

						if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

							$list_add[] = round((float)($PRDCases), 2);

						}

						

						if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

							$list_add[] = round((float)($SalesCases), 2);

						}

						

						if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

							$list_add[] = round((float)($SalesRtnCases), 2);

						}

						

						if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

							$list_add[] = round((float)($AdjCases), 2);

						}

						if($GOCasesSumC > 0 || $GOCasesSumC < 0){

							$list_add[] = round((float)($GOCases), 2);

						}

						if($GICasesSumC > 0 || $GICasesSumC < 0){

							$list_add[] = round((float)($GICases), 2);

						}

						

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

						

						$list_add[] = round((float)($BQty), 2); 

						$list_add[] = round((float)($rate), 2);

						$list_add[] = round((float)($stockValue), 2);

						$stockValue_sum = $stockValue_sum + $stockValue;

						$writer->writeSheetRow('Sheet1', $list_add);

					}  

				}

				

                $list_add = [];

                $list_add[] = "";

    			$list_add[] = "Total";

    			$list_add[] = "";

    			$list_add[] = "";

    			$list_add[] = round((float)($OQTYCasesSum), 2);

                

				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

					$list_add[] = round((float)($PurchQtyCasesSum), 2); 

				}

				if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

					$list_add[] = round((float)($InwardQtyCasesSum), 2); 

				}

                

				if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

					$list_add[] = round((float)($PurchRtnQtyCasesSum), 2); 

				}    

                

				if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

					$list_add[] = round((float)($IssueQtyCasesSum), 2); 

				}    

                

				if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

					$list_add[] = round((float)($PRDCasesSum), 2); 

				}    

                

				if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

					$list_add[] = round((float)($SalesCasesSum), 2); 

				}    

                

				if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

					$list_add[] = round((float)($SalesRtnCasesSum), 2); 

				}    

                

				if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

					$list_add[] = round((float)($AdjCasesSum), 2); 

				}

				

				if($GOCasesSumC > 0 || $GOCasesSumC < 0){

					$list_add[] = round((float)($GOCasesSum), 2); 

				}

				if($GICasesSumC > 0 || $GICasesSumC < 0){

					$list_add[] = round((float)($GICasesSum), 2); 

				}

				

				$list_add[] = round((int) $BQtySum, 2); 

				$list_add[] = ""; 

				$list_add[] = round((float)($stockValue_sum), 2);; 

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Stock_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

    			'site_url'          => site_url(),

    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		public function get_stock_dataNew()

		{

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date')

			);

			

			$from_date = to_sql_date($this->input->post('from_date'));

			$item_group = $this->input->post('item_group');

			$item_main_group = $this->input->post('item_main_group');

			$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

			$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

			$selected_company = $this->session->userdata('root_company');

			$company_data = $this->misc_reports_model->get_company_detail();

			$AllItemList = $this->misc_reports_model->GetItemList($filterdata,$item_group);

			$StockData = $this->misc_reports_model->GetStockData($filterdata,$item_group);

			$StockOQtyData = $this->misc_reports_model->get_item_open_qty($filterdata,$item_group);

			

			echo json_encode($StockOQtyData);

			die;

			

            $PurchQtyCasesSumC = 0;

            $PurchRtnQtyCasesSumC = 0;

            $IssueQtyCasesSumC = 0;

            $PRDCasesSumC = 0;

            $SalesCasesSumC = 0;

            $SalesRtnCasesSumC = 0;

            $AdjCasesSumC = 0;

            $GOCasesSumC = 0;

            $GICasesSumC = 0;

			foreach ($AllItemList as $key => $value) {

				

				if($value["case_qty"] == "0" || $value["case_qty"] == ""){

					$CaseQty = 1;

					}else{

					$CaseQty = $value["case_qty"];

				}

				$OQTY = 0;

				$PurchQtyC = 0;

				$PurchQtyCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){

						$PurchQtyC += $value1['BilledQty'];

					}

				}

				if($PurchQtyC >0){

					$PurchQtyCasesC = floatval($PurchQtyC) / floatval($CaseQty);

					$PurchQtyCasesSumC += $PurchQtyCasesC;

				}

				

				$PurchRtnQtyC = 0;

				$PurchRtnQtyCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

						$PurchRtnQtyC += $value1['BilledQty'];

					}

				}

				if($PurchRtnQtyC >0){

					$PurchRtnQtyCasesC = floatval($PurchRtnQtyC) / floatval($CaseQty);

					$PurchRtnQtyCasesSumC += $PurchRtnQtyCasesC;

				}

				

				$IssueQtyC = 0;

				$IssueQtyCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

						$IssueQtyC += $value1['BilledQty'];

					}

				}

				if($IssueQtyC >0){

					$IssueQtyCasesC = floatval($IssueQtyC) / floatval($CaseQty);

					$IssueQtyCasesSumC += $IssueQtyCasesC;

				}

				

				$PRDQtyC = 0;

				$PRDCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

						$PRDQtyC += $value1['BilledQty'];

					}

				}

				if($PRDQtyC >0){

					$PRDCasesC = floatval($PRDQtyC) / floatval($CaseQty);

					$PRDCasesSumC += $PRDCasesC;

				}

				

				

				$SalesQtyC = 0;

				$SalesCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

						$SalesQtyC += $value1['BilledQty'];

					}

				}

				if($SalesQtyC >0){

					$SalesCasesC = floatval($SalesQtyC) / floatval($CaseQty);

					$SalesCasesSumC += $SalesCasesC;

				}

				

				$SalesRtnQtyC = 0;

				$SalesRtnCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh" )){

						$SalesRtnQtyC += $value1['BilledQty'];

					}

				}

				if($SalesRtnQtyC >0){

					$SalesRtnCasesC = floatval($SalesRtnQtyC) / floatval($CaseQty);

					$SalesRtnCasesSumC += $SalesRtnCasesC;

				}

				

				$AdjQtyC = 0;

				$AdjCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment")){

						$AdjQtyC += $value1['BilledQty'];

					}

				}

				if($AdjQtyC >0){

					$AdjCasesC = floatval($AdjQtyC) / floatval($CaseQty);

					$AdjCasesSumC += $AdjCasesC;

				}

				

				$GOQtyC = 0;

				$GOCasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

						$GOQtyC += $value1['BilledQty'];

					}

				}

				if($GOQtyC >0){

					$GOCasesC = floatval($GOQtyC) / floatval($CaseQty);

					$GOCasesSumC += $GOCasesC;

				}

				

				$GIQtyC = 0;

				$GICasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

						$GIQtyC += $value1['BilledQty'];

					}

				}

				if($GIQtyC >0){

					$GICasesC = floatval($GIQtyC) / floatval($CaseQty);

					$GICasesSumC += $GICasesC;

				}

			}

            $html = '';

            $html .= '<input type="hidden" name="comp_name" id="comp_name" value="'.$company_data->company_name.'">';

            $html .= '<input type="hidden" name="comp_addr" id="comp_addr" value="'.$company_data->address.'">';

            $html .= '<input type="hidden" name="filterdate" id="filterdate" value="Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases">';

            $html .= '<input type="hidden" name="rate_base" id="rate_base" value="Rates based on : State - UP & Dist.Type - SS ">';

            $html .= '<input type="hidden" name="filter_group" id="filter_group" value="Item Group : </b>'.$item_group_name.' ">';

            

            $html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';

            $html .= '<thead style="font-size:11px;">';

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>'.$company_data->company_name.'</b></th>';

            

            if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>'.$company_data->address.'</b></th>';

            

            if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases</b> </th>';

            if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center">PurchQty</th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center">PurchRtn</th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center">IssueQty</th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center">Production</th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center">SalesQty</th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center">SalesRtn</th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center">AdjQty</th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>Rates based on : State - UP & Dist.Type - SS </b> </th>';

            

            if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center">PurchQty</th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center">PurchRtn</th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center">IssueQty</th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center">Production</th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center">SalesQty</th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center">SalesRtn</th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center">AdjQty</th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>Item Group : </b>'.$item_group_name.'</th>';

            

            if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center">PurchQty</th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center">PurchRtn</th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center">IssueQty</th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center">Production</th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center">SalesQty</th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center">SalesRtn</th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center">AdjQty</th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center">PurchQty</th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center">PurchRtn</th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center">IssueQty</th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center">Production</th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center">SalesQty</th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center">SalesRtn</th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center">AdjQty</th>';

			}

            

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '</tr>';

            $html .= '<tr>';

			$html .= '<th align="left">ItemID</th>';

			$html .= '<th align="left">ItemName</th>';

			$html .= '<th align="center">Pkg</th>';

			$html .= '<th align="center">U</th>';

			$html .= '<th align="center">OpenQty</th>';

			if($PurchQtyCasesSumC > 0){

				$html .= '<th align="center">PurchQty</th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0){

				$html .= '<th align="center">PurchRtn</th>';

			}

			

			if($IssueQtyCasesSumC > 0){

				$html .= '<th align="center">IssueQty</th>';

			}

			

			if($PRDCasesSumC > 0){

				$html .= '<th align="center">Production</th>';

			}

			

			if($SalesCasesSumC > 0){

				$html .= '<th align="center">SalesQty</th>';

			}

			

			if($SalesRtnCasesSumC > 0){

				$html .= '<th align="center">SalesRtn</th>';

			}

			

			if($AdjCasesSumC > 0){

				$html .= '<th align="center">AdjQty</th>';

			}

			$html .= '<th align="center">GTOQty</th>';

			$html .= '<th align="center">GTIQty</th>';

			$html .= '<th align="center">Bal.Qty</th>';

			$html .= '<th align="center">Rate</th>';

			$html .= '<th align="center">StkValue</th>';

            $html .= '</tr>';

            $html .= '</thead>';

            $html .= '<tbody>';

            

            $OQTYCasesSum = 0;

            $PurchQtyCasesSum = 0;

            $PurchRtnQtyCasesSum = 0;

            $IssueQtyCasesSum = 0;

            $PRDCasesSum = 0;

            $SalesCasesSum = 0;

            $SalesRtnCasesSum = 0;

            $AdjCasesSum = 0;

            $BQtySum = 0;

            $stockValue_sum = 0;

            

            $PurchValueSum = 0;

            $PurchRtnValueSum = 0; 

            $IssueValueSum = 0;

            $PRDValueSum = 0;

            $SalesValueSum = 0;

            $SalesRtnValueSum = 0;

            $AdjValueSum = 0;

            

			foreach ($AllItemList as $key => $value) {

				$rate = 0;

				$OQTY = 0;

				$PurchQty = 0;

				$PurchQtyCases = 0;

				if($value["case_qty"] == "0"){

					$CaseQty = 1;

					}else{

					$CaseQty = $value["case_qty"];

				}

				

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){

						$PurchQty += $value1['BilledQty'];

						$PurchValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($PurchQty >0){

					$PurchQtyCases = floatval($PurchQty) / floatval($CaseQty);

					$PurchQtyCasesSum += $PurchQtyCases;

				}

				

				$PurchRtnQty = 0;

				$PurchRtnQtyCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

						$PurchRtnQty += $value1['BilledQty'];

						$PurchRtnValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($PurchRtnQty >0){

					$PurchRtnQtyCases = floatval($PurchRtnQty) / floatval($CaseQty);

					$PurchRtnQtyCasesSum += $PurchRtnQtyCases;

				}

				

				$SalesQty = 0;

				$SalesCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

						$SalesQty += $value1['BilledQty'];

						$SalesValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($SalesQty >0){

					$SalesCases = floatval($SalesQty) / floatval($CaseQty);

					$SalesCasesSum += $SalesCases;

				}

				

				

				$IssueQty = 0;

				$IssueQtyCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

						$IssueQty += $value1['BilledQty'];

						$IssueValueSum +=  $rate * $value1['BilledQty'];

					}

				}

				if($IssueQty >0){

					$IssueQtyCases = floatval($IssueQty) / floatval($CaseQty);

					$IssueQtyCasesSum += $IssueQtyCases;

				}

				

				$PRDQty = 0;

				$PRDCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

						$PRDQty += $value1['BilledQty'];

						$PRDValueSum += $rate * $value1['BilledQty'];

					}

				}

				if($PRDQty >0){

					$PRDCases = floatval($PRDQty) / floatval($CaseQty);

					$PRDCasesSum += $PRDCases;

				}

				

				$SalesRtnQty = 0;

				$SalesRtnCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh" )){

						$SalesRtnQty += $value1['BilledQty'];

						$SalesRtnValueSum +=  $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($SalesRtnQty >0){

					$SalesRtnCases = floatval($SalesRtnQty) / floatval($CaseQty);

					$SalesRtnCasesSum += $SalesRtnCases;

				}

				

				$AdjQty = 0;

				$AdjCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment")){

						$AdjQty += $value1['BilledQty'];

						$AdjValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($AdjQty >0){

					$AdjCases = floatval($AdjQty) / floatval($CaseQty);

					$AdjCasesSum += $AdjCases;

				}

				

				$GOQty = 0;

				$GOCases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "X" && $value1["TType2"] == "Out")){

						$GOQty += $value1['BilledQty'];

						$GOValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($GOQty >0){

					$GOCases = floatval($GOQty) / floatval($CaseQty);

					$GOCasesSum += $GOCases;

				}

				

				$GIQty = 0;

				$GICases = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "X" && $value1["TType2"] == "In")){

						$GIQty += $value1['BilledQty'];

						$GIValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] !== '' || $value1["SaleRate"] !== null){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($GIQty >0){

					$GICases = floatval($GIQty) / floatval($CaseQty);

					$GICasesSum += $GICases;

				}

				

				

				$OQTYCases = floatval($value["OQty"]) / floatval($CaseQty);

				$OQTYCasesSum += $OQTYCases;

				$BQty =    $OQTYCases +   $PurchQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases - $GOCases + $GICases;

				$BQtySum += $BQty;    

				if($OQTYCases < 1 && $PurchQtyCases < 1 && $PurchRtnQtyCases < 1 && $IssueQtyCases < 1 && $PRDCases < 1 && $SalesCases < 1 && $SalesRtnCases < 1 && $AdjCases < 1 && $GOCases < 1 && $GICases < 1){

					

					}else{

					$html .= '<tr>';

					$html .= '<td>'.$value["item_code"].'</td>';

					$html .= '<td>'.$value["description"].'</td>';

					$html .= '<td align="center">'.$value["case_qty"].'</td>';

					$html .= '<td align="center">'.$value["unit"].'</td>';

					

					$html .= '<td align="right">'.number_format((float)($OQTYCases), 2, '.', ',') .'</td>';

					if($PurchQtyCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($PurchQtyCases), 2, '.', ',').'</td>';

					}

					

					if($PurchRtnQtyCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($PurchRtnQtyCases), 2, '.', ',').'</td>';

					}

					

					if($IssueQtyCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($IssueQtyCases), 2, '.', ',').'</td>';

					}

					

					if($PRDCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($PRDCases), 2, '.', ',').'</td>';

					}

					

					if($SalesCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($SalesCases), 2, '.', ',').'</td>';

					}

					

					if($SalesRtnCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($SalesRtnCases), 2, '.', ',').'</td>';

					}

					

					if($AdjCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($AdjCases), 2, '.', ',').'</td>';

					}

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

					if($GOCasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($GOCases), 2, '.', ',').'</td>';

					}

					if($GICasesSumC > 0){

						$html .= '<td align="right">'.number_format((float)($GICases), 2, '.', ',').'</td>';

					}

					

					$html .= '<td align="right">'.number_format((float)($BQty), 2, '.', ',').'</td>';

					$html .= '<td align="right">'.$rate.'</td>';

					$html .= '<td align="right">'.number_format((float)$stockValue, 2, '.', '').'</td>';

					$stockValue_sum = $stockValue_sum + $stockValue;

					$html .= '</tr>';

				}

			}

            $html .= '<tbody>';

            $html .= '<tfoot>';

            $html .= '<tr>';

            $html .= '<td ><b>Total</b></td>';

            $html .= '<td></td>';

            $html .= '<td></td>';

            $html .= '<td></td>';

            $html .= '<td align="right"><b>'.number_format((float)($OQTYCasesSum), 2, '.', ',').'</b></td>';

            if($PurchQtyCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($PurchQtyCasesSum), 2, '.', ',').'</b></td>';

			}

			

            if($PurchRtnQtyCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($PurchRtnQtyCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($IssueQtyCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($IssueQtyCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($PRDCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($PRDCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($SalesCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($SalesCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($SalesRtnCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($SalesRtnCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($AdjCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($AdjCasesSum), 2, '.', ',').'</b></td>';

			}    

            

            if($GOCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($GOCasesSum), 2, '.', ',').'</b></td>';

			}

            if($GICasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($GICasesSum), 2, '.', ',').'</b></td>';

			}

            /*    

                $html .= '<td align="right"></td>';

			$html .= '<td align="right"></td>';*/

			

			$html .= '<td align="right"><b>'.number_format((float)($BQtySum), 2, '.', ',').'</b></td>';

			$html .= '<td align="right"></td>';

			$html .= '<td align="right"><b></b></td>';

            

            $html .= '</tr>';

            

			// Show Value 

            $html .= '<tr>';

            $html .= '<td ><b>Total Value</b></td>';

            $html .= '<td></td>';

            $html .= '<td></td>';

            $html .= '<td></td>';

            $html .= '<td align="right"><b>'.number_format((float)($OQTYCasesSum), 2, '.', ',').'</b></td>';

            if($PurchQtyCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($PurchValueSum), 2, '.', ',').'</b></td>';

			}

			

            if($PurchRtnQtyCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($PurchRtnValueSum), 2, '.', ',').'</b></td>';

			}    

			

            if($IssueQtyCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($IssueValueSum), 2, '.', ',').'</b></td>';

			}    

			

            if($PRDCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($PRDValueSum), 2, '.', ',').'</b></td>';

			}    

			

            if($SalesCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($SalesValueSum), 2, '.', ',').'</b></td>';

			}    

			

            if($SalesRtnCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($SalesRtnValueSum), 2, '.', ',').'</b></td>';

			}    

			

            if($AdjCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($AdjValueSum), 2, '.', ',').'</b></td>';

			} 

            

            if($GOCasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($GOValueSum), 2, '.', ',').'</b></td>';

			} 

            

            if($GICasesSumC > 0){

                $html .= '<td align="right"><b>'.number_format((float)($GIValueSum), 2, '.', ',').'</b></td>';

			} 

			

			

			$html .= '<td align="right"></td>';

			$html .= '<td align="right"></td>';

			

			$html .= '<td align="right"><b>'.number_format((float)($BQtySum), 2, '.', ',').'</b></td>';

			$html .= '<td align="right"></td>';

			$html .= '<td align="right"><b>'.number_format((float)($stockValue_sum), 2, '.', ',').'</b></td>';

            

            $html .= '</tr>';

            $html .= '</tfoot>';

            $html .= '<table>';

			echo json_encode($html);

			die;

		}

		

		public function getCummulativeStock()

		{

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			);

			

			$from_date = to_sql_date($this->input->post('from_date'));

			$item_group = $this->input->post('item_group');

			$item_main_group = $this->input->post('item_main_group');

			$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

			$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

			$selected_company = $this->session->userdata('root_company');

			$company_data = $this->misc_reports_model->get_company_detail();

			$GodownData = $this->misc_reports_model->GetGodownData();

			

			$AllItemList = $this->misc_reports_model->GetItemListCommulative($filterdata,$item_group);

			$CommulativeData = $this->misc_reports_model->getCommulativeStockData($filterdata,$item_group);

			

			$html = '';

			$html .= '<input type="hidden" name="comp_name" id="comp_name" value="'.$company_data->company_name.'">';

			$html .= '<input type="hidden" name="comp_addr" id="comp_addr" value="'.$company_data->address.'">';

			$html .= '<input type="hidden" name="filterdate" id="filterdate" value="Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases">';

			$html .= '<input type="hidden" name="rate_base" id="rate_base" value="Rates based on : State - UP & Dist.Type - SS ">';

			$html .= '<input type="hidden" name="filter_group" id="filter_group" value="Item Group : </b>'.$item_group_name.' ">';

            

            

			$html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';

			$html .= '<thead style="font-size:11px;">';

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>'.$company_data->company_name.'</b></th>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>'.$company_data->address.'</b></th>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases</b> </th>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>Item Group : </b>'.$item_group_name.'</th>';

			$html .= '</tr>';

			

			$html .= '<tr>';

			$html .= '<th class="sortable" class="sortable" align="left">SrNo</th>';

			$html .= '<th class="sortable" align="left">ItemID</th>';

			$html .= '<th class="sortable" align="left">ItemName</th>';

			$html .= '<th class="sortable" align="center">Pkg</th>';

			$html .= '<th class="sortable" align="center">U</th>';

			foreach ($GodownData as $key => $value) {

				$html .= '<th class="sortable" align="center">'.$value["AccountID"].'</th>';

			}

			$html .= '<th class="sortable" align="center">Total.Qty</th>';

			$html .= '</tr>';

			$html .= '</thead>';

			$html .= '<tbody>';

			$SrNo = 1;

			foreach ($AllItemList as $key => $value) {

				$SumCases = 0;

				$html .= '<tr>';

				$html .= '<td>'.$SrNo.'</td>';

				$html .= '<td>'.$value["item_code"].'</td>';

				$html .= '<td>'.$value["description"].'</td>';

				$html .= '<td align="center">'.$value["case_qty"].'</td>';

				$html .= '<td align="center">'.$value["unit"].'</td>';

				foreach ($GodownData as $key1 => $value1) {

					$OQty = 0;

					$QTYCases = 0;

					

					$PurchQtyC = 0;

					$PurchRtnQtyC = 0;

					$IssueQtyC = 0;

					$PRDQtyC = 0;

					$SalesQtyC = 0;

					$SalesRtnQtyC = 0;

					$AdjQtyC = 0;

					$GOQtyC = 0;

					$GIQtyC = 0;

					foreach ($CommulativeData as $keydata => $valuedata) {

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"]){

							$OQty = $valuedata['OQty'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "P" && $valuedata["TType2"] == "Purchase"){

							$PurchQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "N" && $valuedata["TType2"] == "PurchaseReturn"){

							$PurchRtnQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "A" && $valuedata["TType2"] == "Issue"){

							$IssueQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "B" && $valuedata["TType2"] == "Production"){

							$PRDQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "O" && $valuedata["TType2"] == "Order"){

							$SalesQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "R" && $valuedata["TType2"] == "Fresh"){

							$SalesRtnQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && ($valuedata["TType"] == "X" && $valuedata["TType2"] == "Free Distribution" || $valuedata["TType"] == "X" && $valuedata["TType2"] == "Promotional Activity" || $valuedata["TType"] == "X" && $valuedata["TType2"] == "Stock Adjustment")){

							$AdjQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "T" && $valuedata["TType2"] == "Out"){

							$GOQtyC += $valuedata['billsum'];

						}

						if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "T" && $valuedata["TType2"] == "In"){

							$GIQtyC += $valuedata['billsum'];

						}

						$BQty =    $OQty +   $PurchQtyC - $PurchRtnQtyC - $IssueQtyC + $PRDQtyC - $SalesQtyC + $SalesRtnQtyC - $AdjQtyC  - $GOQtyC + $GIQtyC;

					}

					if($BQty == '0'){

						$QTYCases = '';

						}else{

						$QTYCases = floatval($BQty) / floatval($value["case_qty"]);

						$QTYCases = number_format($QTYCases, 2, '.', '');

						$SumCases += $QTYCases;

					}

					

					$html .= '<td align="center">'.$QTYCases.'</td>';

				}

				$html .= '<td>'.number_format($SumCases, 2, '.', '').'</td>';

				$SrNo++;

			}

			$html .= '</tbody>';

			$html .= '<table>';

			echo json_encode($html);

			die;

		}

		

		public function exportCummulativeStock()

		{

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			);

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			if($this->input->post()){

				$from_date = to_sql_date($this->input->post('from_date'));

				$item_group = $this->input->post('item_group');

				$item_main_group = $this->input->post('item_main_group');

				$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

				$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

				$selected_company = $this->session->userdata('root_company');

				$company_data = $this->misc_reports_model->get_company_detail();

				$GodownData = $this->misc_reports_model->GetGodownData();

				

				$AllItemList = $this->misc_reports_model->GetItemListCommulative($filterdata,$item_group);

				$CommulativeData = $this->misc_reports_model->getCommulativeStockData($filterdata,$item_group);

				

				$writer = new XLSXWriter();

				$company_name = array($company_data->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $company_data->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

        		

				$msg = "Stock Report of : ".$item_maingroup_name->name."(Stock Value with GST): " .$this->input->post('from_date')." to ".$this->input->post('to_date')." -  Stock in Cases ";

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				

				$msg2 = "Item Group: ".$item_group_name;

				$filter2 = array($msg2);

				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter2);

				

				$set_col_tk = [];

				$set_col_tk["ItemID"] =  'ItemID';

				$set_col_tk["ItemName"] =  'ItemName';

				$set_col_tk["Pkg"] =  'Pkg';

				$set_col_tk["Unit"] =  'Unit';

				foreach ($GodownData as $key => $value) {

					$set_col_tk[$value["AccountID"]] =  $value["AccountID"];

				}

				$set_col_tk["Total"] =  'Total';

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				foreach ($AllItemList as $key => $value) {

					$SumCases = 0;

					$list_add = [];

					$list_add[] = $value["item_code"];

					$list_add[] = $value["description"];

					$list_add[] = $value["case_qty"];

					$list_add[] = $value["unit"];

					foreach ($GodownData as $key1 => $value1) {

						$OQty = 0;

						$QTYCases = 0;

						

						$PurchQtyC = 0;

						$PurchRtnQtyC = 0;

						$IssueQtyC = 0;

						$PRDQtyC = 0;

						$SalesQtyC = 0;

						$SalesRtnQtyC = 0;

						$AdjQtyC = 0;

						$GOQtyC = 0;

						$GIQtyC = 0;

						foreach ($CommulativeData as $keydata => $valuedata) {

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"]){

								$OQty = $valuedata['OQty'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "P" && $valuedata["TType2"] == "Purchase"){

								$PurchQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "N" && $valuedata["TType2"] == "PurchaseReturn"){

								$PurchRtnQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "A" && $valuedata["TType2"] == "Issue"){

								$IssueQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "B" && $valuedata["TType2"] == "Production"){

								$PRDQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "O" && $valuedata["TType2"] == "Order"){

								$SalesQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "R" && $valuedata["TType2"] == "Fresh"){

								$SalesRtnQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && ($valuedata["TType"] == "X" && $valuedata["TType2"] == "Free Distribution" || $valuedata["TType"] == "X" && $valuedata["TType2"] == "Promotional Activity" || $valuedata["TType"] == "X" && $valuedata["TType2"] == "Stock Adjustment")){

								$AdjQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "T" && $valuedata["TType2"] == "Out"){

								$GOQtyC += $valuedata['billsum'];

							}

							if($valuedata['GodownID'] == $value1['AccountID'] && $valuedata['ItemID'] == $value["item_code"] && $valuedata["TType"] == "T" && $valuedata["TType2"] == "In"){

								$GIQtyC += $valuedata['billsum'];

							}

							$BQty =    $OQty +   $PurchQtyC - $PurchRtnQtyC - $IssueQtyC + $PRDQtyC - $SalesQtyC + $SalesRtnQtyC - $AdjQtyC  - $GOQtyC + $GIQtyC;

						}

						if($BQty == '0'){

							$QTYCases = '';

							}else{

							$QTYCases = floatval($BQty) / floatval($value["case_qty"]);

							$QTYCases = number_format($QTYCases, 2, '.', '');

							$SumCases += $QTYCases;

						}

						$list_add[] = $QTYCases;

					}

					$list_add[] = number_format($SumCases, 2, '.', '');

					$writer->writeSheetRow('Sheet1', $list_add);

				}

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

        		foreach($files as $file){

        			if(is_file($file)) {

        				unlink($file); 

					}

				}

        		$filename = 'StockCommulativeReport.xlsx';

        		$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

        		echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

        		]);

				die;

			}	

			

		}

		public function get_stock_data()

		{

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'GodownID'  => $this->input->post('GodownID')

			);

			$fy = $this->session->userdata('finacial_year');

			$from_date = to_sql_date($this->input->post('from_date'));

			$item_group = $this->input->post('item_group');

			$item_main_group = $this->input->post('item_main_group');

			$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

			$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

			$selected_company = $this->session->userdata('root_company');

			$company_data = $this->misc_reports_model->get_company_detail();

			$AllItemList = $this->misc_reports_model->GetItemList($filterdata,$item_group);

			$StockData = $this->misc_reports_model->GetStockData($filterdata,$item_group);

			$StockOQtyData = $this->misc_reports_model->get_item_open_qty($filterdata,$item_group);

			// echo "ok";

			// echo "<pre>";print_r($StockData);

			// die;

			

            $PurchQtyCasesSumC = 0;

            $InwardQtyCasesSumC = 0;

            $PurchRtnQtyCasesSumC = 0;

            $IssueQtyCasesSumC = 0;

            $PRDCasesSumC = 0;

            $SalesCasesSumC = 0;

            $SalesRtnCasesSumC = 0;

            $AdjCasesSumC = 0;

            $GOCasesSumC = 0;

            $GICasesSumC = 0;

			foreach ($AllItemList as $key => $value) {

				if($value["case_qty"] > 0){

				    $CaseQty = $value["case_qty"];

					}else{

				    $CaseQty = $value["crate_qty"];

				}

				$OQTY = 0;

				$PurchQtyC = 0;

				$PurchQtyCasesC = 0;

				

				$InwardQtyC = 0;

				$InwardQtyCasesC = 0;

				

				

				$PurchRtnQtyC = 0;

				$PurchRtnQtyCasesC = 0;

				

				$IssueQtyC = 0;

				$IssueQtyCasesC = 0;

				

				$PRDQtyC = 0;

				$PRDCasesC = 0;

				

				$SalesQtyC = 0;

				$SalesCasesC = 0;

				

				$SalesRtnQtyC = 0;

				$SalesRtnCasesC = 0;

				

				$AdjQtyC = 0;

				$AdjCasesC = 0;

				

				$GOQtyC = 0;

				$GOCasesC = 0;

				

				$GIQtyC = 0;

				$GICasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){

						$PurchQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

						$InwardQtyC += $value1['BilledQty'];

					}

					

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

						$PurchRtnQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

						$IssueQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

						$PRDQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

						$SalesQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){

						$SalesRtnQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Free distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){

						$AdjQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

						$GOQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

						$GIQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

						$GIQtyC += $value1['BilledQty'];

					}

				}

				if($PurchQtyC !== '0'){

					$PurchQtyCasesC = floatval($PurchQtyC) / floatval($CaseQty);

					$PurchQtyCasesSumC += $PurchQtyCasesC;

				}

				if($InwardQtyC !== '0'){

					$InwardQtyCasesC = floatval($InwardQtyC) / floatval($CaseQty);

					$InwardQtyCasesSumC += $InwardQtyCasesC;

					

				}

				if($PurchRtnQtyC !== '0'){

					$PurchRtnQtyCasesC = floatval($PurchRtnQtyC) / floatval($CaseQty);

					$PurchRtnQtyCasesSumC += $PurchRtnQtyCasesC;

				}

				

				if($IssueQtyC !== '0'){

					$IssueQtyCasesC = floatval($IssueQtyC) / floatval($CaseQty);

					$IssueQtyCasesSumC += $IssueQtyCasesC;

				}

				

				if($PRDQtyC !== '0'){

					$PRDCasesC = floatval($PRDQtyC) / floatval($CaseQty);

					$PRDCasesSumC += $PRDCasesC;

				}

				

				if($SalesQtyC !== '0'){

					$SalesCasesC = floatval($SalesQtyC) / floatval($CaseQty);

					$SalesCasesSumC += $SalesCasesC;

				}

				

				if($SalesRtnQtyC !== '0'){

					$SalesRtnCasesC = floatval($SalesRtnQtyC) / floatval($CaseQty);

					$SalesRtnCasesSumC += $SalesRtnCasesC;

				}

				

				if($AdjQtyC !== '0'){

					$AdjCasesC = floatval($AdjQtyC) / floatval($CaseQty);

					$AdjCasesSumC += $AdjCasesC;

				}

				

				if($GOQtyC >0){

					$GOCasesC = floatval($GOQtyC) / floatval($CaseQty);

					$GOCasesSumC += $GOCasesC;

				}

				

				if($GIQtyC >0){

					$GICasesC = floatval($GIQtyC) / floatval($CaseQty);

					$GICasesSumC += $GICasesC;

				}

			}

			

			// if($value["item_code"] == 'GFFG0292'){

			// echo $InwardQtyCasesSumC;die;

			// }

			// if($value["item_code"] == 'GFFG0292'){

			// echo $InwardQtyC;die;

			// }

			/*echo json_encode($AdjCasesSumC);

			die;*/

            $html = '';

            $html .= '<input type="hidden" name="comp_name" id="comp_name" value="'.$company_data->company_name.'">';

            $html .= '<input type="hidden" name="comp_addr" id="comp_addr" value="'.$company_data->address.'">';

            $html .= '<input type="hidden" name="filterdate" id="filterdate" value="Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases">';

            $html .= '<input type="hidden" name="rate_base" id="rate_base" value="Rates based on : State - UP & Dist.Type - SS ">';

            $html .= '<input type="hidden" name="filter_group" id="filter_group" value="Item Group : </b>'.$item_group_name.' ">';

            

            $html .= '<table class="table-striped table-bordered stock_position stock_positionFilter" id="stock_position" width="100%">';

            $html .= '<thead style="font-size:11px;">';

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>'.$company_data->company_name.'</b></th>';

            

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>'.$company_data->address.'</b></th>';

            

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases</b> </th>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>Rates based on : State - UP & Dist.Type - SS </b> </th>';

            

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th colspan="10"><b>Item Group : </b>'.$item_group_name.'</th>';

            

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

            

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '<th></th>';

            $html .= '</tr>';

            $html .= '<tr>';

            $html .= '<th class="sortable" align="left">SrNo</th>';

			$html .= '<th class="sortable" align="left">ItemID</th>';

			$html .= '<th class="sortable" align="left">ItemName</th>';

			$html .= '<th class="sortable" align="center">Pkg</th>';

			$html .= '<th class="sortable" align="center">UOM</th>';

			$html .= '<th class="sortable" align="center">OpenQty</th>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th class="sortable" align="center">PurchQty</th>';

			}

			

			

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Inward</th>';

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

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th class="sortable" align="center">GTOQty</th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th class="sortable" align="center">GTIQty</th>';

			}

			$html .= '<th class="sortable" align="center">Bal.Qty</th>';

			$html .= '<th class="sortable" align="center">Rate</th>';

			$html .= '<th class="sortable" align="center">StkValue</th>';

            $html .= '</tr>';

            $html .= '</thead>';

            /*echo json_encode($AdjCasesSumC);

			die;*/

            $html .= '<tbody  id="stock_positionFilter">';

            

            $OQTYCasesSum = 0;

            $PurchQtyCasesSum = 0;

            $InwardQtyCasesSum = 0;

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

				$InwardQty = 0;

				$InwardQtyCases = 0;

				if($value["case_qty"] > 0){

				    $CaseQty = $value["case_qty"];

					}else{

				    $CaseQty = $value["crate_qty"];

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

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

						$InwardQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

						$PurchRtnQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

						$IssueQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

						$PRDQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

						$SalesQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){

						$SalesRtnQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Free distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){

						$AdjQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

						$GOQty += $value1['BilledQty'];

						$GOValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

						$GIQty += $value1['BilledQty'];

						$GIValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] >0){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($PurchQty !== '0'){

					$PurchQtyCases = floatval($PurchQty) / floatval($CaseQty);

					$PurchQtyCasesSum += $PurchQtyCases;

				}

				if($InwardQty !== '0'){

					$InwardQtyCases = floatval($InwardQty) / floatval($CaseQty);

					$InwardQtyCasesSum += $InwardQtyCases;

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

						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "I"  && $valueOQty['TType2'] == "Inward"){

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

				$BQty =    $OQTYCases +  $PurchQtyCases +  $InwardQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases  - $GOCases + $GICases;

				$BQtySum += $BQty;    

				if(floatval($OQTYCases) == '0.00' && floatval($PurchQtyCases) == "0.00" && floatval($InwardQtyCases) == "0.00" && floatval($PurchRtnQtyCases) == "0.00" && floatval($IssueQtyCases) == "0.00" && floatval($PRDCases) == "0.00" && floatval($SalesCases) == "0.00" && floatval($SalesRtnCases) == "0.00" && floatval($AdjCases) == "0.00" && floatval($GOCases) == "0.00" && floatval($GICases) == "0.00"){

					

					}else{

					$html .= '<tr>';

					$html .= '<td>'.$SrNo.'</td>';

					$html .= '<td>'.$value["item_code"].'</td>';

					$html .= '<td>'.$value["description"].'</td>';

					$html .= '<td align="center">'.$CaseQty.'</td>';

					$html .= '<td align="center">'.$value["unit"].'</td>';

					

					$html .= '<td align="right">'.number_format((float)($OQTYCases), 2, '.', ',') .'</td>';

					if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($PurchQtyCases), 2, '.', ',').'</td>';

					}

					if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($InwardQtyCases), 2, '.', ',').'</td>';

					}

					

					if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($PurchRtnQtyCases), 2, '.', ',').'</td>';

					}

					

					if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($IssueQtyCases), 2, '.', ',').'</td>';

					}

					

					if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($PRDCases), 2, '.', ',').'</td>';

					}

					

					if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($SalesCases), 2, '.', ',').'</td>';

					}

					

					if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($SalesRtnCases), 2, '.', ',').'</td>';

					}

					

					if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($AdjCases), 2, '.', ',').'</td>';

					}

					if($GOCasesSumC > 0 || $GOCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($GOCases), 2, '.', ',').'</td>';

					}

					if($GICasesSumC > 0 || $GICasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($GICases), 2, '.', ',').'</td>';

					}

					

					

					/*if($value["assigned_rate"] == null || $value["assigned_rate"] == "" || $value["assigned_rate"] == "0.00"){

						//$rate = 0;

						}else{

						$rate = $value["assigned_rate"];

					}*/

					

					if($value["case_qty"] == '0' || $value["case_qty"] == ''){

						$stockqty = round($BQty) * 1;

						}else{

						$stockqty = round($BQty) * $value["case_qty"];

					}

					

					$stockValue = $stockqty * $rate;

					

					

					$html .= '<td align="right">'.round((float)($BQty), 2).'</td>';

					$html .= '<td align="right">'.$rate.'</td>';

					$html .= '<td align="right">'.number_format((float)$stockValue, 2, '.', '').'</td>';

					/*$html .= '<td align="right"></td>';

					$html .= '<td align="right"></td>';*/

					$stockValue_sum = $stockValue_sum + $stockValue;

					$html .= '</tr>';

					$SrNo++;

				}

			}

            $html .= '<tbody>';

            $html .= '<tfoot>';

            $html .= '<tr>';

            $html .= '<td></td>';

            $html .= '<td ><b>Total</b></td>';

            $html .= '<td></td>';

            $html .= '<td></td>';

            $html .= '<td></td>';

            $html .= '<td align="right"><b>'.number_format((float)($OQTYCasesSum), 2, '.', ',').'</b></td>';

            if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($PurchQtyCasesSum), 2, '.', ',').'</b></td>';

			}

            if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($InwardQtyCasesSum), 2, '.', ',').'</b></td>';

			}

			

            if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($PurchRtnQtyCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($IssueQtyCasesSumC >0 || $IssueQtyCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($IssueQtyCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($PRDCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($SalesCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($SalesRtnCasesSum), 2, '.', ',').'</b></td>';

			}    

			

            if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($AdjCasesSum), 2, '.', ',').'</b></td>';

			}

            if($GOCasesSumC > 0 || $GOCasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($GOCasesSum), 2, '.', ',').'</b></td>';

			}

            if($GICasesSumC > 0 || $GICasesSumC < 0){

                $html .= '<td align="right"><b>'.number_format((float)($GICasesSum), 2, '.', ',').'</b></td>';

			}

			

			$html .= '<td align="right"><b>'.number_format((float)($BQtySum), 2, '.', ',').'</b></td>';

			$html .= '<td align="right"></td>';

			$html .= '<td align="right"><b>'.number_format((float)($stockValue_sum), 2, '.', ',').'</b></td>';

            

            $html .= '</tr>';

            

			// Show Value 

			

            $html .= '</tfoot>';

            $html .= '<table>';

			echo json_encode($html);

			die;

		}

		

		/* End Stock Position report code */

		

		// Production reports Code

		public function production_reports(){

			

			if (!has_permission_new('production_reports', '', 'view')) {

				access_denied('Access denied ');

			}

			$data['title']          = "Production Reports";

			$data['item_group'] = $this->misc_reports_model->item_division_group();

			//  print_r($data);die;

			$this->load->view('admin/misc_reports/production_reports', $data);

		}

		public function accountlist(){

			// POST data

			$postData = $this->input->post();

			// Get data

			$data = $this->misc_reports_model->getaccounts($postData);

			echo json_encode($data);

		}

		

		public function itemlist(){

			

			// POST data

			$postData = $this->input->post();

			

			// Get data

			$data = $this->misc_reports_model->itemlist($postData);

			

			echo json_encode($data);

		}

		

		public function get_account_details()

		{

			$data = array();

			$accountID = $this->input->post('act_id');

			$account_data = $this->misc_reports_model->get_account_details($accountID);

			$staff_data = $this->misc_reports_model->get_staff_details($accountID);

			$data['account_data'] = $account_data;

			$data['staff_data'] = $staff_data;

			echo json_encode($data);

		}

		public function get_item_details()

		{

			

			$ItemID = $this->input->post('ItemID');

			$account_data = $this->misc_reports_model->get_item_details($ItemID);

			echo json_encode($account_data);

		}

		public function export_production_report()

		{

        	if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$filterdata = array(

				'from_date' => $this->input->post('from_date'),

				'to_date'  => $this->input->post('to_date'),

				'report_type'  => $this->input->post('report_type'),

				'accountID'  => $this->input->post('accountID'),

				'ItemID'  => $this->input->post('ItemID'),

				'source'  => $this->input->post('source')

				);

				$accountID = $this->input->post('accountID');

				$ItemID = $this->input->post('ItemID');

				$accountname = $this->input->post('accountName');

				$Itemname = $this->input->post('Itemname');

				$report_type = $this->input->post('report_type');

				$body_data = $this->misc_reports_model->get_production_for_body_data($filterdata);

				$baking_data = $this->misc_reports_model->GetProductionWiseBakingData($filterdata);

				$selected_company_details    = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Production Details Date : ".$this->input->post('from_date')." to " .$this->input->post('to_date');

    			$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				$j++;

				

				if(!empty($accountID)){

					$msg1 = "AccountName : ".$accountname;

					$filter1 = array($msg1);

					$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 12);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter1);

				}

				if(!empty($ItemID)){

					$msg2 = "ItemName: ".$Itemname;

					$filter2 = array($msg2);

					$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 12);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter2);

					

				}

				

				

				

				$list_add = [];

				if($report_type == 1 && empty($ItemID) && empty($accountID)){

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

					}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = ""; 

					$list_add[] = ""; 

					$list_add[] = ""; 

					$list_add[] = ""; 

					$list_add[] = ""; 

					$list_add[] = ""; 

					}else if(!empty($ItemID) && empty($accountID)){

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";  

					}else if(empty($ItemID) && !empty($accountID)){

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";  

					

					}else if(!empty($ItemID) && !empty($accountID)){

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";               

					$list_add[] = "";    

					

				}

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				if($report_type == 1 && empty($ItemID) && empty($accountID)){

					$set_col_tk["PRDID"] =  'PRDID';

					$set_col_tk["PRDDate"] =  'PRDDate';

					$set_col_tk["AccountName"] =  'AccountName';

					$set_col_tk["ReceipeName"] =  'ReceipeName';

					$set_col_tk["StdQty"] =  'StdQty';

					$set_col_tk["BatchCount"] =  'BatchCount';

					$set_col_tk["Std.F.G.Qty"] =  'Std.F.G.Qty';

					$set_col_tk["Baking Qty"] =  'Baking Qty';

					$set_col_tk["F.G.Qty"] =  'ActualF.G.Qty';

					$set_col_tk["Diff.InQty"] =  'Diff.InQty';

					$set_col_tk["remark"] =  'Remark';

					$set_col_tk["comment"] =  'Comment';

					//$set_col_tk["Req.Time(min)"] =  'Req.Time(min)';

					//$set_col_tk["ActualTime(min)"] =  'ActualTime(min)';

					

					}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

					//   $set_col_tk["AccountID"] =  'AccountID';

					//   $set_col_tk["AccountName"] =  'AccountName';

					//   $set_col_tk["F.G.Qty"] =  'F.G.Qty';

					$set_col_tk["ItemID"] =  'ItemID';

					$set_col_tk["ItemName"] =  'ItemName';

					$set_col_tk["CaseQty"] =  'CaseQty';

					$set_col_tk["TotalBatch"] =  'Total Batch';

					$set_col_tk["STDFGQty"] =  'STD FG Qty';

					$set_col_tk["Baking Qty"] =  'Baking Qty';

					$set_col_tk["ActualFGQty"] =  'Actual FG Qty';

					$set_col_tk["DiffQty"] =  'Diff Qty';

					$set_col_tk["ActualFGCasesQty"] =  'Actual FG CasesQty';

					

					}else if(!empty($ItemID) && empty($accountID)){

					$set_col_tk["PRDID"] =  'PRDID';

					$set_col_tk["PRDDate"] =  'PRDDate';

					$set_col_tk["AccountName"] =  'AccountName';

					$set_col_tk["StdQty"] =  'StdQty';

					$set_col_tk["BatchCount"] =  'BatchCount';

					$set_col_tk["Std.F.G.Qty"] =  'Std.F.G.Qty';

					$set_col_tk["Baking Qty"] =  'Baking Qty';

					$set_col_tk["F.G.Qty"] =  'ActualF.G.Qty';

					$set_col_tk["Diff.InQty"] =  'Diff.InQty';

					$set_col_tk["Req.Time(min)"] =  'Req.Time(min)';

					$set_col_tk["ActualTime(min)"] =  'ActualTime(min)';

					

					

					}else if(empty($ItemID) && !empty($accountID)){

					$set_col_tk["PRDID"] =  'PRDID';

					$set_col_tk["PRDDate"] =  'PRDDate';

					$set_col_tk["RecipeName"] =  'RecipeName';

					$set_col_tk["StdQty"] =  'StdQty';

					$set_col_tk["BatchCount"] =  'BatchCount';

					$set_col_tk["Std.F.G.Qty"] =  'Std.F.G.Qty';

					$set_col_tk["Baking Qty"] =  'Baking Qty';

					$set_col_tk["F.G.Qty"] =  'ActualF.G.Qty';

					$set_col_tk["Diff.InQty"] =  'Diff.InQty';

					$set_col_tk["Req.Time(min)"] =  'Req.Time(min)';

					$set_col_tk["ActualTime(min)"] =  'ActualTime(min)';

					

					

					

					}else if(!empty($ItemID) && !empty($accountID)){

					$set_col_tk["PRDID"] =  'PRDID';

					$set_col_tk["PRDDate"] =  'PRDDate';

					$set_col_tk["StdQty"] =  'StdQty';

					$set_col_tk["BatchCount"] =  'BatchCount';

					$set_col_tk["Std.F.G.Qty"] =  'Std.F.G.Qty';

					$set_col_tk["Baking Qty"] =  'Baking Qty';

					$set_col_tk["F.G.Qty"] =  'ActualF.G.Qty';

					$set_col_tk["Diff.InQty"] =  'Diff.InQty';

					$set_col_tk["Req.Time(min)"] =  'Req.Time(min)';

					$set_col_tk["ActualTime(min)"] =  'ActualTime(min)';

					

				}

				

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

    	        $i = 1;

				$Finish_good_qty_new = 0;

				$required_time = 0;

				$actul_time1 = 0;

				$Finish_good_qty_new2 = 0;

				

				$Finish_good_qty_new3 = 0;

				$required_time3 = 0;

				$actul_time3 = 0;

				$Finish_good_qty_new4 = 0;

				$required_time4 = 0;

				$actul_time4 = 0;

				$Finish_good_qty_new5 = 0;

				$required_time5 = 0;

				$actul_time5 = 0;

				$totalBatchQty = 0;

				$stdprodqty_total =0;

				$diffqty_total =0;

				$recstdQty_total =0;

				$totalBatchQty = 0;

				$stdprodqty_total =0;

				$diffqty_total =0;

				$recstdQty_total =0;

				

				// Summary Report Varible 

				$TotalBatchSum = 0;

				$TotalSTDFGQty = 0;

				$TotalActualFGQty = 0;

				$TotalDiff = 0;

				$TotalFGCases = 0;

				$totalBakingQty = 0;

				foreach ($body_data as $key => $value) {

					$bakingQty = 0;

                    foreach($baking_data as $val){

                        if($report_type == 2 && empty($ItemID) && empty($accountID)){

                            if($value["recipeID"]==$val["recipeID"]){

                                $bakingQty = $val["TotalBakingQty"];

							}

							}else{

                            if($value["pro_order_id"]==$val["pro_order_id"]){

                                $bakingQty = $val["TotalBakingQty"];

							}

						}

					}

                    $TotalBakingQty += $bakingQty;

					$list_add = [];

					if($report_type == 1 && empty($ItemID) && empty($accountID)){

						$list_add[] = $value["pro_order_id"];

						$list_add[] = _d(substr($value["TransDate"],0,10));

						if($value["firstname"] == null){

							$con_st_name =  $value["company"];

							}else{

							$con_st_name =  $value["firstname"];

						}

						$stdprodqty = $value["batch_qty"]*$value["qty"];

						$stdprodqty_total += $stdprodqty;

						$diffqty = $stdprodqty - $value["Finish_good_qty_new"];

						$diffqty_total += $diffqty;

						$list_add[] = $con_st_name;

						$list_add[] = $value["description"];

						$list_add[] = $value["qty"];

						$list_add[] = $value["batch_qty"];

						$list_add[] = $bakingQty;

						$totalBatchQty += $value["batch_qty"];

						$list_add[] = $stdprodqty;

						$recstdQty_total +=$value["qty"];

						$list_add[] = $value["Finish_good_qty_new"];

						$list_add[] = $diffqty;

						$Finish_good_qty_new = $Finish_good_qty_new + $value["Finish_good_qty_new"];

						//$list_add[] = $value["required_time"];

						$required_time = $required_time + $value["required_time"];

						

						$dateTimeObject1 = date_create($value['p_start_time']); 

						$dateTimeObject2 = date_create($value['p_end_time']); 

						$difference = date_diff($dateTimeObject1, $dateTimeObject2);

						$minutes = $difference->days * 24 * 60;

						$minutes += $difference->h * 60;

						$minutes += $difference->i;

						//$list_add[] = $minutes;

						$actul_time1 = $actul_time1 + $minutes;

						$list_add[] = $value["remark"];

						$list_add[] = $value["comment"];

						

						}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

						

						/*if($value["AccountID_staff"] == null){

							$con_st_AccountID =  $value["AccountID_con"];

							}else{

							$con_st_AccountID =  $value["AccountID_staff"];

							}

							$list_add[] = $con_st_AccountID;

							if($value["firstname"] == null){

							$con_st_name =  $value["company"];

							}else{

							$con_st_name =  $value["firstname"];

							}

							$list_add[] = $con_st_name;

							$list_add[] = $value["fgqty"];

						$Finish_good_qty_new2 = $Finish_good_qty_new2 + $value["fgqty"];*/

						$list_add[] = $value["recipeID"];

						$list_add[] = $value["description"];

						$list_add[] = $value["case_qty"];

						$list_add[] = $value["TotalBatch"];

						$TotalBatchSum += $value["TotalBatch"];

						$list_add[] = $value["STDFGQty"];

						$list_add[] = $bakingQty;

						$TotalSTDFGQty += $value["STDFGQty"];

						$list_add[] = $value["ActualFGQty"];

						$TotalActualFGQty += $value["ActualFGQty"];

						$diff = $value["STDFGQty"] - $value["ActualFGQty"];

						$TotalDiff += $diff;

						$list_add[] = number_format($diff,2,'.','');

						$InCases = $value["ActualFGQty"] / $value["case_qty"];

						$list_add[] = number_format($InCases,2,'.','');

						$TotalFGCases += $InCases;

						

						}else if(!empty($ItemID) && empty($accountID)){

						$list_add[] = $value["pro_order_id"];

						$list_add[] = _d(substr($value["TransDate"],0,10));

						if($value["firstname"] == null){

							$con_st_name =  $value["company"];

							}else{

							$con_st_name =  $value["firstname"];

						}

						$stdprodqty = $value["batch_qty"]*$value["qty"];

						$stdprodqty_total += $stdprodqty;

						$diffqty = $stdprodqty - $value["Finish_good_qty_new"];

						$diffqty_total += $diffqty;

						$list_add[] = $con_st_name;

						$list_add[] = $value["qty"];

						$list_add[] = $value["batch_qty"];

						

						$totalBatchQty += $value["batch_qty"];

						$list_add[] = $stdprodqty;

						$list_add[] = $bakingQty;

						$recstdQty_total +=$value["qty"];

						

						$list_add[] = $value["Finish_good_qty_new"];

						$list_add[] = $diffqty;

						

						$Finish_good_qty_new3 = $Finish_good_qty_new3 + $value["Finish_good_qty_new"];

						$list_add[] = $value["required_time"];

						$required_time3 = $required_time3 + $value["required_time"];

						$dateTimeObject1 = date_create($value['p_start_time']); 

						$dateTimeObject2 = date_create($value['p_end_time']); 

						$difference = date_diff($dateTimeObject1, $dateTimeObject2);

						$minutes = $difference->days * 24 * 60;

						$minutes += $difference->h * 60;

						$minutes += $difference->i;

						$list_add[] = $minutes;

						

						$actul_time3 = $actul_time3 + $minutes;

						

						}else if(empty($ItemID) && !empty($accountID)){

						$stdprodqty = $value["batch_qty"]*$value["qty"];

						$stdprodqty_total += $stdprodqty;

						$diffqty = $stdprodqty - $value["Finish_good_qty_new"];

						$diffqty_total += $diffqty;

						$list_add[] = $value["pro_order_id"];

						$list_add[] = _d(substr($value["TransDate"],0,10));

						$list_add[] = $value["description"];

						$list_add[] = $value["qty"];

						$list_add[] = $value["batch_qty"];

						$list_add[] = $bakingQty;

						$totalBatchQty += $value["batch_qty"];

						$list_add[] = $stdprodqty;

						$recstdQty_total +=$value["qty"];

						$list_add[] = $value["Finish_good_qty_new"];

						$list_add[] = $diffqty;

						$Finish_good_qty_new4 = $Finish_good_qty_new4 + $value["Finish_good_qty_new"];

						$list_add[] = $value["required_time"];

						$required_time4 = $required_time4 + $value["required_time"];

						$dateTimeObject1 = date_create($value['p_start_time'].':00'); 

						$dateTimeObject2 = date_create($value['p_end_time'].':00'); 

						$difference = date_diff($dateTimeObject1, $dateTimeObject2);

						$minutes = $difference->days * 24 * 60;

						$minutes += $difference->h * 60;

						$minutes += $difference->i;

						$list_add[] = $minutes;

						$actul_time4 = $actul_time4 + $minutes;

						}else if(!empty($ItemID) && !empty($accountID)){

						$stdprodqty = $value["batch_qty"]*$value["qty"];

						$stdprodqty_total += $stdprodqty;

						$diffqty = $stdprodqty - $value["Finish_good_qty_new"];

						$diffqty_total += $diffqty;

						$list_add[] = $value["pro_order_id"];

						$list_add[] = _d(substr($value["TransDate"],0,10));

						$list_add[] = $value["qty"];

						$list_add[] = $value["batch_qty"];

						$list_add[] = $bakingQty;

						$totalBatchQty += $value["batch_qty"];

						$list_add[] = $stdprodqty;

						$recstdQty_total +=$value["qty"];

						$list_add[] = $value["Finish_good_qty_new"];

						$list_add[] = $diffqty;

						

						$Finish_good_qty_new5 = $Finish_good_qty_new5 + $value["Finish_good_qty_new"];

						$list_add[] = $value["required_time"];

						$required_time5 = $required_time5 + $value["required_time"];

						$dateTimeObject1 = date_create($value['p_start_time']); 

						$dateTimeObject2 = date_create($value['p_end_time']); 

						$difference = date_diff($dateTimeObject1, $dateTimeObject2);

						$minutes = $difference->days * 24 * 60;

						$minutes += $difference->h * 60;

						$minutes += $difference->i;

						$list_add[] = $minutes;

						$actul_time5 = $actul_time5 + $minutes;

						

					}

					

					$writer->writeSheetRow('Sheet1', $list_add);

					$i++;

				}

				$list_add = [];

				

				if($report_type == 1 && empty($ItemID) && empty($accountID)){

                	$list_add[] = "Total";

                	$list_add[] = "";

                	$list_add[] = "";

                	$list_add[] = "";

                	$list_add[] = $recstdQty_total;

                	$list_add[] = $totalBatchQty;

                	$list_add[] = $stdprodqty_total;

                	$list_add[] = $TotalBakingQty;

                	$list_add[] = $Finish_good_qty_new;

                	$list_add[] = $diffqty_total;

                	//$list_add[] = $required_time;

                	//$list_add[] = $actul_time1;

                	$list_add[] = "";

                	$list_add[] = "";

                    

                    

					}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

                    $list_add[] = "Total";

                	$list_add[] = "";

                	$list_add[] = "";

                	$list_add[] = number_format($TotalBatchSum,2,'.','');

                	$list_add[] = number_format($TotalSTDFGQty,2,'.','');

                	$list_add[] = number_format($TotalBakingQty,2,'.','');

                	$list_add[] = number_format($TotalActualFGQty,2,'.','');

                	$list_add[] = number_format($TotalDiff,2,'.','');

                	$list_add[] = number_format($TotalFGCases,2,'.','');

					

					}else if(!empty($ItemID) && empty($accountID)){

                    $list_add[] = "Total";

                	$list_add[] = "";

                	$list_add[] = "";

                    $list_add[] = $recstdQty_total;

                	$list_add[] = $totalBatchQty;

                	$list_add[] = $stdprodqty_total;

                	$list_add[] = number_format($TotalBakingQty,2,'.','');

                	$list_add[] = $Finish_good_qty_new3;

                	$list_add[] = $diffqty_total;

                	$list_add[] = $required_time3;

                	$list_add[] = $actul_time3;

                    

					

                    

					}else if(empty($ItemID) && !empty($accountID)){

                    $list_add[] = "Total";

                	$list_add[] = "";

                	$list_add[] = "";

                	$list_add[] = $recstdQty_total;

                	$list_add[] = $totalBatchQty;

                	$list_add[] = $stdprodqty_total;

                	$list_add[] = number_format($TotalBakingQty,2,'.','');

                	$list_add[] = $Finish_good_qty_new4;

                	$list_add[] = $diffqty_total;

                	$list_add[] = $required_time4;

                	$list_add[] = $actul_time4;

                	

					

					}else if(!empty($ItemID) && !empty($accountID)){

					$list_add[] = "Total";

                	$list_add[] = "";

                	$list_add[] = $recstdQty_total;

                	$list_add[] = $totalBatchQty;

                	$list_add[] = $stdprodqty_total;

                	$list_add[] = number_format($TotalBakingQty,2,'.','');

                	$list_add[] = $Finish_good_qty_new5;

                	$list_add[] = $diffqty_total;

                	$list_add[] = $required_time5;

                	$list_add[] = $actul_time5;

                    

				}

				

                $writer->writeSheetRow('Sheet1', $list_add);

				

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Production_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

    			'site_url'          => site_url(),

    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		public function get_production_data()

		{

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'report_type'  => $this->input->post('report_type'),

			'accountID'  => $this->input->post('accountID'),

			'ItemID'  => $this->input->post('ItemID'),

			'source'  => $this->input->post('source')

			);

            $accountID = $this->input->post('accountID');

            $ItemID = $this->input->post('ItemID');

            $accountname = $this->input->post('accountName');

            $Itemname = $this->input->post('Itemname');

			$report_type = $this->input->post('report_type');

			$body_data = $this->misc_reports_model->get_production_for_body_data($filterdata);

			$baking_data = $this->misc_reports_model->GetProductionWiseBakingData($filterdata);

			$company_details = $this->misc_reports_model->get_company_detail();

			$table_width = '100%';

			if($report_type == 1 && empty($ItemID) && empty($accountID)){

				$colspan = 11;

				}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

				$colspan = 8;

				

				}else if(empty($ItemID) && !empty($accountID)){

				$colspan = 7;

				

				}else if(!empty($ItemID) && empty($accountID)){

				$colspan = 7;

				

			}

			else if(!empty($ItemID) && !empty($accountID)){

				$colspan = 6;

				

			}

			$html = '';

            $html .= '<table class="table-striped table-bordered production_report" id="production_report" >';

            $html .= '<thead style="font-size:11px;">';

            

            $html .= '<tr style="display:none;">';

            $html .= '<td colspan="'.$colspan.'" style="font-size:18px;font-weight:700;text-align:center;"><b>'.$company_details->company_name.'</b></td>';

            $html .= '</tr>';

            

            $html .= '<tr style="display:none;">';

            $html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>'.$company_details->address.'</b></td>';

            $html .= '</tr>';

            if($report_type == 1 && empty($ItemID) && empty($accountID)){

				$html .= '<tr style="display:none;">';

				$html .= '<td colspan="'.$colspan.'" style="text-align:center;"><b>Production Details : </b> Date : '.$this->input->post('from_date').' To '.$this->input->post('to_date').'</td>';

				$html .= '</tr>';

				}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

                $html .= '<tr style="display:none;">';

                $html .= '<td colspan="'.$colspan.'" style="text-align:center;"><b>Production Summary </b> Date :  '.$this->input->post('from_date').' To '.$this->input->post('to_date').'</td>';

                $html .= '</tr>';

				}else if(empty($ItemID) && !empty($accountID)){

                $html .= '<tr style="display:none;">';

                $html .= '<td colspan="'.$colspan.'" style="text-align:center;"><b>AccountName : '.$accountname.' </b>  Date : '.$this->input->post('from_date').' To '.$this->input->post('to_date').'</td>';

                $html .= '</tr>';

				}else if(!empty($ItemID) && empty($accountID)){

                $html .= '<tr style="display:none;">';

                $html .= '<td colspan="'.$colspan.'" style="text-align:center;"><b>ItemName : '.$Itemname.' </b>  Date: '.$this->input->post('from_date').' To '.$this->input->post('to_date').'</td>';

                $html .= '</tr>';

				}else if(!empty($ItemID) && !empty($accountID)){

                $html .= '<tr style="display:none;">';

                $html .= '<td colspan="'.$colspan.'" style="text-align:center;"><b>ItemName : '.$Itemname.' AND AccountName : '.$accountname.'</b>  Date :  '.$this->input->post('from_date').' To '.$this->input->post('to_date').'</td>';

                $html .= '</tr>';

			}

            

            $html .= '<tr>';

            if($report_type == 1 && empty($ItemID) && empty($accountID)){

				

                $html .= '<th class="sortable" align="center">PRDID</th>';

                $html .= '<th class="sortable" align="center">PRDDate</th>';

                $html .= '<th class="sortable" align="left">AccountName</th>';

                $html .= '<th class="sortable" align="left">ReceipeName</th>';

                $html .= '<th class="sortable" align="center">Std Qty</th>';

                $html .= '<th class="sortable" align="center">Batch Count</th>';

                $html .= '<th class="sortable" align="center">Std F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Baking Qty</th>';

                $html .= '<th class="sortable" align="center">Actual F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Diff. in Qty</th>';

                $html .= '<th align="left">Remark</th>';

                $html .= '<th align="left">Comment</th>';

                //$html .= '<th align="center">Req.Time(min)</th>';

                //$html .= '<th align="center">ActualTime(min)</th>';

				}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

                /*$html .= '<th align="left" style="width:10%;">AccountID</th>';

					$html .= '<th align="left"style="width:30%;">AccountName</th>';

				$html .= '<th align="left" style="width:10%;">F.G.Qty</th>';*/

                $html .= '<th class="sortable" align="center" style="width:5%;">ItemID</th>';

                $html .= '<th class="sortable" align="left" style="width:30%;">ItemName</th>';

                $html .= '<th class="sortable" align="center" style="width:5%;">Case Qty</th>';

                $html .= '<th class="sortable" align="center" style="width:10%;">Total Batch</th>';

                $html .= '<th class="sortable" align="center" style="width:10%;">STD. FG Qty.</th>';

                $html .= '<th class="sortable" align="center">Baking Qty</th>';

                $html .= '<th class="sortable" align="center" style="width:10%;">Actual FG Qty</th>';

                $html .= '<th class="sortable" align="center" style="width:10%;">Diff. Qty</th>';

                $html .= '<th class="sortable" align="center" style="width:10%;">Actual FG Qty(Cases)</th>';

                

				}else if(!empty($ItemID) && empty($accountID)){

                $html .= '<th class="sortable" align="left">PRDID</th>';

                $html .= '<th class="sortable" align="left">PRDDate</th>';

                $html .= '<th class="sortable" align="left">AccountName</th>';

                $html .= '<th class="sortable" align="center">Std Qty</th>';

                $html .= '<th class="sortable" align="center">Batch Count</th>';

                $html .= '<th class="sortable" align="center">Std F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Baking Qty</th>';

                $html .= '<th class="sortable" align="center">Actual F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Diff. in Qty</th>';

                $html .= '<th class="sortable" align="center">Req.Time(min)</th>';

                $html .= '<th class="sortable" align="center">ActualTime(min)</th>';

				}else if(empty($ItemID) && !empty($accountID)){

                

                $html .= '<th class="sortable" align="left">PRDID</th>';

                $html .= '<th class="sortable" align="center">PRDDate</th>';

                $html .= '<th class="sortable" align="left">RecipeName</th>';

                $html .= '<th class="sortable" align="center">Std Qty</th>';

                $html .= '<th class="sortable" align="center">Batch Count</th>';

                $html .= '<th class="sortable" align="center">Std F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Baking Qty</th>';

                $html .= '<th class="sortable" align="center">Actual F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Diff. in Qty</th>';

                $html .= '<th class="sortable" align="center">Req.Time(min)</th>';

                $html .= '<th class="sortable" align="center">Actual Time(min)</th>';

                

				}else if(!empty($ItemID) && !empty($accountID)){

                $html .= '<th class="sortable" align="left">PRDID</th>';

                $html .= '<th class="sortable" align="left">PRDDate</th>';

                $html .= '<th class="sortable" align="center">Std Qty</th>';

                $html .= '<th class="sortable" align="center">Batch Count</th>';

                $html .= '<th class="sortable" align="center">Std F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Baking Qty</th>';

                $html .= '<th class="sortable" align="center">Actual F.G. Qty</th>';

                $html .= '<th class="sortable" align="center">Diff. in Qty</th>';

                $html .= '<th class="sortable" align="center">Req.Time(min)</th>';

                $html .= '<th class="sortable" align="center">ActualTime(min)</th>';

			}

            

            

            $html .= '</tr>';

            

            $html .= '</thead>';

            $html .= '<tbody>';

            $i = 1;

            $Finish_good_qty_new = 0;

            $required_time = 0;

            $actul_time1 = 0;

            $Finish_good_qty_new2 = 0;

            

            $Finish_good_qty_new3 = 0;

            $required_time3 = 0;

            $actul_time3 = 0;

            $Finish_good_qty_new4 = 0;

            $required_time4 = 0;

            $actul_time4 = 0;

            $Finish_good_qty_new5 = 0;

            $required_time5 = 0;

            $actul_time5 = 0;

            $totalBatchQty = 0;

            $stdprodqty_total =0;

            $diffqty_total =0;

            $recstdQty_total =0;

            // Summary Report Varible 

            $TotalBatchSum = 0;

            $TotalSTDFGQty = 0;

            $TotalActualFGQty = 0;

            $TotalDiff = 0;

            $TotalFGCases = 0;

            $TotalBakingQty = 0;

            foreach ($body_data as $key => $value) {

                $bakingQty = 0;

                foreach($baking_data as $val){

                    if($report_type == 2 && empty($ItemID) && empty($accountID)){

                        if($value["recipeID"]==$val["recipeID"]){

                            $bakingQty = $val["TotalBakingQty"];

						}

						}else{

                        if($value["pro_order_id"]==$val["pro_order_id"]){

                            $bakingQty = $val["TotalBakingQty"];

						}

					}

				}

                $TotalBakingQty += $bakingQty;

                $html .= '<tr>';

                if($report_type == 1 && empty($ItemID) && empty($accountID)){

                    

                    $html .= '<td align="center">'.$value["pro_order_id"].'</td>';

                    $html .= '<td align="center">'._d(substr($value["TransDate"],0,10)).'</td>';

                    if($value["firstname"] == null){

						$con_st_name =  $value["company"];

						}else{

                        $con_st_name =  $value["firstname"];

					}

                    $stdprodqty = $value["batch_qty"]*$value["qty"];

                    $stdprodqty_total += $stdprodqty;

                    $diffqty = $value["Finish_good_qty_new"] - $stdprodqty;

                    $diffqty_total += $diffqty;

                    $html .= '<td align="left">'.$con_st_name.'</td>';

                    $html .= '<td align="left">'.$value["description"].'</td>';

                    $html .= '<td align="right">'.$value["qty"].'</td>';

                    $html .= '<td align="right">'.$value["batch_qty"].'</td>';

                    $html .= '<td align="right">'.$stdprodqty.'</td>';

                    $html .= '<td align="right">'.number_format($bakingQty, 2, '.', '').'</td>';

                    $recstdQty_total +=$value["qty"];

                    $html .= '<td align="right">'.$value["Finish_good_qty_new"].'</td>';

                    $html .= '<td align="right">'.number_format($diffqty, 2, '.', '').'</td>';

                    $html .= '<td align="left">'.$value["remark"].'</td>';

                    $html .= '<td align="left">'.$value["comment"].'</td>';

                    $Finish_good_qty_new = $Finish_good_qty_new + $value["Finish_good_qty_new"];

                    $required_time = $required_time + $value["required_time"];

                    

                    $dateTimeObject1 = date_create($value['p_start_time']); 

                    $dateTimeObject2 = date_create($value['p_end_time']); 

                    $difference = date_diff($dateTimeObject1, $dateTimeObject2);

                    $minutes = $difference->days * 24 * 60;

                    $minutes += $difference->h * 60;

                    $minutes += $difference->i;

					// $html .= '<td align="right">'.$minutes.'</td>';

                    $actul_time1 = $actul_time1 + $minutes;

                    $TotalBatchQty += $value["batch_qty"];

                    

					}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

                    $html .= '<td align="center" style="width:5%;">'.$value["recipeID"].'</td>';

                    $html .= '<td align="left" style="width:30%;">'.$value["description"].'</td>';

                    $html .= '<td align="center" style="width:5%;">'.$value["case_qty"].'</td>';

                    $html .= '<td align="right" style="width:10%;">'.$value["TotalBatch"].'</td>';

                    $TotalBatchSum += $value["TotalBatch"];

                    $html .= '<td align="right" style="width:10%;">'.$value["STDFGQty"].'</td>';

                    $TotalSTDFGQty += $value["STDFGQty"];

                    $html .= '<td align="right">'.number_format($bakingQty, 2, '.', '').'</td>';

                    $html .= '<td align="right" style="width:10%;">'.$value["ActualFGQty"].'</td>';

                    

                    $TotalActualFGQty += $value["ActualFGQty"];

                    $diff = $value["STDFGQty"] - $value["ActualFGQty"];

                    $TotalDiff += $diff;

                    $html .= '<td align="right" style="width:10%;">'.number_format($diff,2,'.','').'</td>';

                    $InCases = $value["ActualFGQty"] / $value["case_qty"];

                    $TotalFGCases += $InCases;

                    $html .= '<td align="right" style="width:10%;">'.number_format($InCases,2,'.','').'</td>';

					}else if(!empty($ItemID) && empty($accountID)){

                    $stdprodqty = $value["batch_qty"]*$value["qty"];

                    $stdprodqty_total += $stdprodqty;

                    $diffqty = $stdprodqty - $value["Finish_good_qty_new"];

                    $diffqty_total += $diffqty;

                    $html .= '<td align="left">'.$value["pro_order_id"].'</td>';

                    $html .= '<td align="center">'._d(substr($value["TransDate"],0,10)).'</td>';

					if($value["firstname"] == null){

						$con_st_name =  $value["company"];

						}else{

                        $con_st_name =  $value["firstname"];

					}

                    $html .= '<td align="left">'.$con_st_name.'</td>';

                    $html .= '<td align="right">'.$value["qty"].'</td>';

                    $html .= '<td align="right">'.$value["batch_qty"].'</td>';

                    $totalBatchQty += $value["batch_qty"];

                    $html .= '<td align="right">'.$stdprodqty.'</td>';

                    $html .= '<td align="right">'.number_format($bakingQty, 2, '.', '').'</td>';

                    $recstdQty_total +=$value["qty"];

                    $html .= '<td align="right">'.$value["Finish_good_qty_new"].'</td>';

                    

                    $html .= '<td align="right">'.number_format($diffqty, 2, '.', '').'</td>';

                    

                    $Finish_good_qty_new3 = $Finish_good_qty_new3 + $value["Finish_good_qty_new"];

                    $html .= '<td align="right">'.$value["required_time"].'</td>';

                    $required_time3 = $required_time3 + $value["required_time"];

                    $dateTimeObject1 = date_create($value['p_start_time']); 

                    $dateTimeObject2 = date_create($value['p_end_time']); 

                    $difference = date_diff($dateTimeObject1, $dateTimeObject2);

                    $minutes = $difference->days * 24 * 60;

                    $minutes += $difference->h * 60;

                    $minutes += $difference->i;

                    $html .= '<td align="right">'.$minutes.'</td>';

                    $actul_time3 = $actul_time3 + $minutes;

					}else if(empty($ItemID) && !empty($accountID)){

                    $stdprodqty = $value["batch_qty"]*$value["qty"];

                    $stdprodqty_total += $stdprodqty;

                    $diffqty = $stdprodqty - $value["Finish_good_qty_new"];

                    $diffqty_total += $diffqty;

                    $html .= '<td align="left">'.$value["pro_order_id"].'</td>';

                    $html .= '<td align="center">'._d(substr($value["TransDate"],0,10)).'</td>';

                    $html .= '<td align="left">'.$value["description"].'</td>';

                    $html .= '<td align="right">'.$value["qty"].'</td>';

                    $recstdQty_total +=$value["qty"];

                    $html .= '<td align="right">'.$value["batch_qty"].'</td>';

                    $totalBatchQty += $value["batch_qty"];

                    $html .= '<td align="right">'.$stdprodqty.'</td>';

                    $html .= '<td align="right">'.number_format($bakingQty, 2, '.', '').'</td>';

                    $html .= '<td align="right">'.$value["Finish_good_qty_new"].'</td>';

                    $html .= '<td align="right">'.number_format($diffqty, 2, '.', '').'</td>';

                    $Finish_good_qty_new4 = $Finish_good_qty_new4 + $value["Finish_good_qty_new"];

                    $html .= '<td align="right">'.$value["required_time"].'</td>';

                    $required_time4 = $required_time4 + $value["required_time"];

                    $dateTimeObject1 = date_create($value['p_start_time'].':00'); 

                    $dateTimeObject2 = date_create($value['p_end_time'].':00'); 

                    $difference = date_diff($dateTimeObject1, $dateTimeObject2);

                    $minutes = $difference->days * 24 * 60;

                    $minutes += $difference->h * 60;

                    $minutes += $difference->i;

                    $html .= '<td align="right">'.$minutes.'</td>';

                    $actul_time4 = $actul_time4 + $minutes;

					}else if(!empty($ItemID) && !empty($accountID)){

                    $stdprodqty = $value["batch_qty"]*$value["qty"];

                    $stdprodqty_total += $stdprodqty;

                    $diffqty = $stdprodqty - $value["Finish_good_qty_new"];

                    $diffqty_total += $diffqty;

                    $html .= '<td align="left">'.$value["pro_order_id"].'</td>';

                    $html .= '<td align="center">'._d(substr($value["TransDate"],0,10)).'</td>';

                    $html .= '<td align="right">'.$value["qty"].'</td>';

                    $recstdQty_total +=$value["qty"];

                    $html .= '<td align="right">'.$value["batch_qty"].'</td>';

                    $totalBatchQty += $value["batch_qty"];

                    $html .= '<td align="right">'.$stdprodqty.'</td>';

                    $html .= '<td align="right">'.number_format($bakingQty, 2, '.', '').'</td>';

                    $html .= '<td align="right">'.$value["Finish_good_qty_new"].'</td>';

                    $html .= '<td align="right">'.number_format($diffqty, 2, '.', '').'</td>';

                    $Finish_good_qty_new5 = $Finish_good_qty_new5 + $value["Finish_good_qty_new"];

                    $html .= '<td align="right">'.$value["required_time"].'</td>';

                    $required_time5 = $required_time5 + $value["required_time"];

                    $dateTimeObject1 = date_create($value['p_start_time']); 

                    $dateTimeObject2 = date_create($value['p_end_time']); 

                    $difference = date_diff($dateTimeObject1, $dateTimeObject2);

                    $minutes = $difference->days * 24 * 60;

                    $minutes += $difference->h * 60;

                    $minutes += $difference->i;

                    $html .= '<td align="right">'.$minutes.'</td>';

                    $actul_time5 = $actul_time5 + $minutes;

                    

				}

                

                $html .= '</tr>';

                $i++;

			}

            $html .= '</tbody>';

            $html .= '<tfoot>';

            $html .= '<tr>';

            

            if($report_type == 1 && empty($ItemID) && empty($accountID)){

				$html .= '<td align="center"><b>Total</b></td>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="right"><b>'.number_format($recstdQty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($totalBatchQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($stdprodqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($TotalBakingQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($Finish_good_qty_new, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($diffqty_total, 2, '.', '').'</b></td>';

				//$html .= '<td align="right"><b>'.$required_time.'</b></td>';

				//$html .= '<td align="right"><b>'.$actul_time1.'</b></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="left"></td>';

				

				}else if($report_type == 2 && empty($ItemID) && empty($accountID)){

				

				$html .= '<td align="left" colspan="3"><b>Total</b></td>';

				$html .= '<td align="right" ><b>'.number_format($TotalBatchSum,2,'.','').'</b></td>';

				$html .= '<td align="right" ><b>'.number_format($TotalSTDFGQty,2,'.','').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($TotalBakingQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right" ><b>'.number_format($TotalActualFGQty,2,'.','').'</b></td>';

				$html .= '<td align="right" ><b>'.number_format($TotalDiff,2,'.','').'</b></td>';

				$html .= '<td align="right" ><b>'.number_format($TotalFGCases,2,'.','').'</b></td>';

				

				}else if(!empty($ItemID) && empty($accountID)){

				$html .= '<td align="left"><b>Total</b></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="right"><b>'.number_format($recstdQty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($totalBatchQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($stdprodqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($TotalBakingQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($Finish_good_qty_new3, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($diffqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.$required_time3.'</b></td>';

				$html .= '<td align="right"><b>'.$actul_time3.'</b></td>';

				

				

				}else if(empty($ItemID) && !empty($accountID)){

				$html .= '<td align="left"><b>Total</b></td>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="right"><b>'.number_format($recstdQty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($totalBatchQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($stdprodqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($TotalBakingQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($Finish_good_qty_new4, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($diffqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.$required_time4.'</b></td>';

				$html .= '<td align="right"><b>'.$actul_time4.'</b></td>';

				

				}else if(!empty($ItemID) && !empty($accountID)){

				$html .= '<td align="left"><b>Total</b></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="right"><b>'.number_format($recstdQty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($totalBatchQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($stdprodqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($TotalBakingQty, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($Finish_good_qty_new5, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.number_format($diffqty_total, 2, '.', '').'</b></td>';

				$html .= '<td align="right"><b>'.$required_time5.'</b></td>';

				$html .= '<td align="right"><b>'.$actul_time5.'</b></td>';

			}

            $html .= '</tr>';

            $html .= '</tfoot>';

            $html .= '</table>';

			echo json_encode($html);

			die;

		}

		// End Production code

		//rate list report start here

		public function rate_list_report()

		{

			if (!has_permission_new('item_rate_list', '', 'view')) {

				access_denied('access_denied');

			}

			$this->load->model('clients_model');

			$this->load->model('rate_master_model');

			$data['main_item_group'] = $this->misc_reports_model->get_main_item_group();

			$data['states'] = $this->rate_master_model->get_state();

			$data['groups'] = $this->clients_model->get_groups();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['title'] = "Rate list report";

			$this->load->view('admin/misc_reports/rate_list_report', $data);

		}

		public function export_rate_list(){

         	if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$item_group = $this->input->post('item_group');

				$item_data = $this->input->post('item_data');

				$states = $this->input->post('states');

				$distributor_id = $this->input->post('distributor_id');

				$data = $this->misc_reports_model->get_rate_table_data($this->input->post());

				$selected_company_details = $this->misc_reports_model->get_company_detail();

				$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

				// print_r($data);die;

				$writer = new XLSXWriter();

				$j=0;

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				$j++;

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				$j++;

				

				$msg = "Rate List Report  State: ".$states;

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 16);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				$j++;

				if($distributor_id !=''){

					$distributor_d = $this->db->get_where('tblcustomers_groups',array('id'=>$distributor_id))->row_array();

					

					$msg1 = "Distributor: ".$distributor_d['name'];

					$filter1 = array($msg1);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 16);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter1);

					$j++;

				}

				if($item_group !=''){

					$msg2 = " Item Group: ".$item_group_name;

					$filter2 = array($msg2);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 16);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter2);

				}

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

				$set_col_tk["Item_Id"] =  'Item Id';

				$set_col_tk["Item_Name"] =  'Item Name';

				$set_col_tk["MRP"] =  'MRP';

				$set_col_tk["CreateQty"] =  'CreateQty';

				$set_col_tk["CaseQty"] =  'CaseQty';

				$set_col_tk["BasicRate"] =  'BasicRate';

				$set_col_tk["GST%"] =  'GST%';

				$set_col_tk["SaleRate"] =  'SaleRate';

				$set_col_tk["CaseRate"] =  'CaseRate';

				$set_col_tk["Effective_Date"] =  'Effective Date';

				$set_col_tk["Disc %"] =  'Disc %';

				$set_col_tk["Created By"] =  'Created By';

				$set_col_tk["Created Date"] =  'Created Date';

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				

				foreach ($data as $k => $value) {

					

					$list_add = [];

					$list_add[] = strtoupper($value["item_id"]);

					

					$list_add[] = $value["description"];

					$list_add[] = $value["mrp"];

					$list_add[] = $value["crate_qty"];

					$list_add[] = $value["case_qty"];

					$list_add[] = $value["assigned_rate"];

					$list_add[] = $value["taxrate"];

					$tax_amt = ($value['assigned_rate']*$value['taxrate'])/100;

					$total_amt = $tax_amt + $value['assigned_rate'];

					$list_add[] = number_format($total_amt, 2, '.', '');

					$caseRate = $value['assigned_rate'] * $value['case_qty'];

					$tax_amt2 = ($caseRate*$value['taxrate'])/100;

					$total_amt2 = $tax_amt2 + $caseRate;

					$list_add[] = number_format($total_amt2, 2, '.', '');

					$list_add[] = date("d/m/Y", strtotime(substr($value['effective_date'],0,10)));

					$list_add[] = $value["dis_per"];

					//$list_add[] = $value["BillAmt"];

					$list_add[] = $value["firstname"]." ".$value["lastname"];

					$list_add[] = _d($value["TransDate"]);

					

					$writer->writeSheetRow('Sheet1', $list_add);

				}

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Rate_list_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

    			'site_url'          => site_url(),

    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

			

		}

		public function get_rate_report()

		{

			if (!has_permission_new('item_rate_list', '', 'view')) {

				access_denied('access_denied');

			}

			$item_group = $this->input->post('item_group');

			$item_data = $this->input->post('item_data');

			$states = $this->input->post('states');

			$distributor_id = $this->input->post('distributor_id');

			$data = $this->misc_reports_model->get_rate_table_data($this->input->post());

			$company_data = $this->misc_reports_model->get_company_detail();

			$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

			

            $html =''; 

            $html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';

            $html .= '<thead style="font-size:11px;">';

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="11"><b class="co_name">'.$company_data->company_name.'</b></th>';

			$html.= '</tr>';

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="11"><b class="co_add">'.$company_data->address.'</b></th>';

			$html.= '</tr>';

			$html .= '<tr style="display:none;">';

            $html .= '<th colspan="11"><b class="state_dist">';

            if($states !=''){

				$html .= 'State : '.$states.','; 

			}

            if($distributor_id !=''){

				$distributor_d = $this->db->get_where('tblcustomers_groups',array('id'=>$distributor_id))->row_array();

				$html .= ' Distributor : '.$distributor_d['name']; 

			}

            $html.= '</b> </th>';

            $html.= '</tr>';

			if($item_group !=''){

				$html .= '<tr style="display:none;">';

				

				$html .= '<th colspan="11"><b class="item_grp">Item Group :'.$item_group_name.' </b ></th>';

				$html.= '</tr>';

			}

			$html.= '<tr>';

			$html.= '<th class="sortablePop" align="center">Sr.</th>';

			$html.= '<th class="sortablePop" align="center">Item Id</th>';

			$html.= '<th class="sortablePop">Item Name</th>';

			$html.= '<th class="sortablePop">MRP</th>';

			$html.= '<th class="sortablePop" align="center">CreateQty</th>';

			$html.= '<th class="sortablePop" align="center">CaseQty</th>';

			$html.= '<th class="sortablePop" align="center">BasicRate</th>';

			$html.= '<th class="sortablePop" align="center">GST%</th>';

			$html.= '<th class="sortablePop" align="center">SaleRate</th>';

			$html.= '<th class="sortablePop" align="center">CaseRate</th>';

			$html.= '<th class="sortablePop" align="center">Effective Date</th>';

			$html.= '<th class="sortablePop" align="center">Disc %</th>';

			$html.= '<th class="sortablePop" align="center">Created By</th>';

			$html.= '<th class="sortablePop" align="center">Created Date</th>';

			$html.= '<th class="sortablePop" align="center">Updated By</th>';

			$html.= '</tr>';

            $html .= '</thead>';

            $html .= '<tbody>';

			$i = 1; 

			/*$ItemIDs = array();

				$stateID = array();

				$distID = array();

				$dates = array();

			$rates = array();*/

			foreach($data as $value){

			    

			    

			    /*array_push($ItemIDs,$value['item_id']);

					array_push($stateID,$value['state_id']);

					array_push($distID,$value['distributor_id']);

					array_push($dates,substr($value['TransDate'],0,10));

				array_push($rates,$value['assigned_rate']);*/

				$html.= '<tr>';

				

				$html.= '<td align="center">'.$i.'</td>';

				$html.= '<td align="center">'.strtoupper($value['item_id']).'</td>';

				$html.= '<td>'.$value['description'].'</td>';

				$html.= '<td>'.$value['mrp'].'</td>';

				$html.= '<td align="right">'.$value['crate_qty'].'</td>';

				$html.= '<td align="right">'.$value['case_qty'].'</td>';

				$html.= '<td align="right">'.$value['assigned_rate'].'</td>';

				$html.= '<td align="right">'.$value['taxrate'].'</td>';

				$tax_amt = ($value['assigned_rate']*$value['taxrate'])/100;

				$total_amt = $tax_amt + $value['assigned_rate'];

				$html.= '<td align="right">'.number_format($total_amt, 2, '.', '').'</td>';

				$caseRate = $value['assigned_rate'] * $value['case_qty'];

				$tax_amt2 = ($caseRate*$value['taxrate'])/100;

				$total_amt2 = $tax_amt2 + $caseRate;

				$html.= '<td align="right">'.number_format($total_amt2,2).'</td>';

				$html.= '<td align="center">'.date("d/m/Y", strtotime(substr($value['effective_date'],0,10))).'</td>';

				$html.= '<td align="right">'.$value['dis_per'].'</td>';

				$html.= '<td align="left">'.$value['firstname']." ".$value['lastname'].'</td>';

				$html.= '<td align="left">'._d($value['TransDate']).'</td>';

				$html.= '<td align="left">'.$value['UpdatedBy'].'</td>';

				

				

				

				$html.= '</tr>';

			$i++; }

			$html .= '</tbody>';

			$html .= '<table>';

			// echo $html;

			echo json_encode($html);

		}

		//end here

		

		//====================== target entry page =====================================

		public function target_sale()

		{

			if (!has_permission_new('staff_target', '', 'view')) {

				access_denied('access_denied');

			}

			$data['staff'] = $this->staff_model->get('', ['active' => 1]);

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['title'] = "Target Sale";

			$this->load->view('admin/misc_reports/target_sale', $data);

		}

		

		public function GroupWise_target_sale()

		{

			if (!has_permission_new('staff_target', '', 'view')) {

				access_denied('access_denied');

			}

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['Subgroup'] = $this->misc_reports_model->get_ItemSubGroup2_data();

			$data['title'] = "Sale Target";

			$this->load->view('admin/misc_reports/GroupWise_target_sale', $data);

		}

		

		public function TargetVsAchievement()

		{

			if (!has_permission_new('staff_target', '', 'view')) {

				access_denied('access_denied');

			}

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['Subgroup'] = $this->misc_reports_model->get_ItemSubGroup2_data();

			$data['title'] = "Target Vs Achievement";

			$this->load->view('admin/misc_reports/TargetVsAchievement', $data);

		}

		//==================== Submit Staff Wise Target ================================	

		public function submit_targetSale()

		{

			if($this->input->post()){

				if (!has_permission_new('staff_target', '', 'edit')) {

					access_denied('access_denied');

				}

				$data_array = $this->input->post();

				$data = $this->misc_reports_model->AddStaffWiseSaleTarget($data_array);

				if($data > 0){

					set_alert('success', _l('added_successfully'));

					$redUrl = admin_url('misc_reports/target_sale');

					redirect($redUrl);

					}else{

					$redUrl = admin_url('misc_reports/target_sale');

					redirect($redUrl);

				}

			}

		}

		

		public function Submit_ItemWiseTargetSale()

		{

			if($this->input->post()){

				if (!has_permission_new('staff_target', '', 'edit')) {

					access_denied('access_denied');

				}

				$data_array = $this->input->post();

				$data = $this->misc_reports_model->AddItemWiseSaleTarget($data_array);

				if($data > 0){

					set_alert('success', _l('added_successfully'));

					$redUrl = admin_url('misc_reports/GroupWise_target_sale');

					redirect($redUrl);

					}else{

					$redUrl = admin_url('misc_reports/GroupWise_target_sale');

					redirect($redUrl);

				}

			}

		}

		public function get_targetList()

		{

            $company_detail = $this->misc_reports_model->get_company_detail();

            $FGID = "1";

            $ItemGroupList = $this->misc_reports_model->GetItemGroupByMainGroupID($FGID);

            $StaffWisePartyList = $this->misc_reports_model->GetStaffWisePartyList($this->input->post());

            $StaffWisePartyWiseTargate = $this->misc_reports_model->GetStaffWisePartyWiseTargate($this->input->post());

            // echo "<pre>";

			// print_r($StaffWisePartyWiseTargate);

			// die;

            $html =''; 

            $html.= '<thead>';

			

            $html.= '<tr style="display:none;">';

            $html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Staff Target</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

            $html.= '<tr>';

            $html.= '<th id="sl" style="text-align:left; text-transform: uppercase;">Party Name <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>';

            $html.= '<th style="text-align:left; text-transform: uppercase;">Dist. Type</th>';

            $html.= '<th style="text-align:left; text-transform: uppercase;">Station</th>';

			foreach($ItemGroupList as $value){

				$html.= '<th style="text-align:left; text-transform: uppercase;" >'.$value['name'].'</th>';

			}

            $html.= '<th style="text-align:left; text-transform: uppercase;">Total</th>';

            $html.= '</tr>';

            $html.= '</thead>';

            $html.= '<tbody>';

			$alltargetTotal = 0;

            foreach($StaffWisePartyList as $PKey=>$PVal){

                $html.= '<tr>';

                $html.= '<td data-id="'.$PVal['AccountID'].'"><input type="hidden" id="AccountID" name="AccountID[]" value="'.$PVal['AccountID'].'" >'.$PVal['company'].'</td>';

				$html.= '<td style="text-align:center;">'.$PVal['name'].'</td>';

				$html.= '<td>'.$PVal['StationName'].'</td>';

				$total_bussiness_target = 0;

				foreach($ItemGroupList as $value){

				    $TargetAmt = "";

				    foreach($StaffWisePartyWiseTargate as $TKey=>$Tval){

				        if($PVal["AccountID"]==$Tval["AccountID"] && $value["id"]==$Tval["ItemSubGroupID"]){

				            $TargetAmt = $Tval["Targate"];

						}

					}

					

					$total_bussiness_target += $TargetAmt;

					$alltargetTotal += $TargetAmt;

				    $actId = ''.$PVal['AccountID'].'';

				    $html.= '<td><input type="text" class="target_data_value target_data_account_'.$actId.' target_count_'.$value["id"].'" onkeyup="myFunction_data('.$value['id'].','."'".$actId."'".')"  name = "Target['.$PVal['AccountID'].']['.$value['id'].']" value="'.$TargetAmt.'" class="form-control"></td>';

				}

				// $html.= '<td></td>';

				$html.= '<td style="text-align:right;font-size: 13px;" class=" target_count_total_left_'.$PVal['AccountID'].'"><b  class="left_lower_total">'.$total_bussiness_target.'</b></td>';

                $html.= '<tr>';

			}

            $html.= '</tbody>';

			

			$html.= '<tfoot>';

			$html.= '<tr>';

			$html.= '<td style="text-transform: uppercase;"><b>Total</b></td>';

			$html.= '<td></td>';

			$html.= '<td></td>';

			foreach($ItemGroupList as $value){

				$TargetAmt = "";

				foreach($StaffWisePartyWiseTargate as $TKey=>$Tval){

					if($value["id"]==$Tval["ItemSubGroupID"]){

						$TargetAmt += $Tval["Targate"];

					}

				}

				$html.= '<td style="font-size: 13px; text-align:right" class="target_count_total_lower_'.$value['id'].'"><b>'.$TargetAmt.'</b></td>';

			}

			// $html.= '<td></td>';

			$html.= '<td style="text-align:right;font-size: 13px;" ><b  class="left_lower_total_count">'.$alltargetTotal.'</b></td>';

			$html.= '<tr>';

            $html.= '</tfoot>';

            /*echo "<pre>";

				print_r($html);

			die;*/

			/*

				$data_division = $this->misc_reports_model->get_coutomer_division($this->input->post());

				$data_staff_business_division = $this->misc_reports_model->get_staff_business_division($this->input->post());

				$sum_get_coutomer_division = $this->misc_reports_model->sum_get_coutomer_division($this->input->post());

				$item_division_group = $this->misc_reports_model->item_division_group();

				

				$selected_company = $this->session->userdata('root_company');

				$year = $_SESSION['finacial_year'];

				$month_data = $this->input->post('month_data');

				$month = substr($month_data, -2);

				$staff_target_data =  $this->db->get_where('tblstaff_target',array('Staff_AccountID'=>$this->input->post('staff_account_name'),'PlantID'=>$selected_company,'FY'=>$year,'MonthID'=>$month))->result_array();

				

				if(count($staff_target_data) > 0){

				$hidden_data = 1;

				}else{

				$hidden_data = 0; 

				}

				

				

				$total_data_total = 0;

				$i = 1; foreach($data as $value){

				$html.= '<tr><input type="hidden" name="hidden" value="'.$hidden_data.'">';

				$total = 0;

				

				

				$html.= '<td data-id="'.$value['AccountID'].'"><input type="hidden" id="AccountID" name="AccountID[]" value="'.$value['AccountID'].'" >'.$value['company'].'</td>';

				$html.= '<td style="text-align:center;">'.$value['name'].'</td>';

				$html.= '<td>'.$value['StationName'].'</td>';

				

				foreach($item_division_group as $item_division_group_data){

				$mm = 0;

				if($item_division_group_data['id'] != 99){

				foreach($data_division as $data_division_data){

				if($value['AccountID'] == $data_division_data['AccountID'] && $item_division_group_data['id'] == $data_division_data['ItemDivID']){

				$actId = ''.$value['AccountID'].'';

				$html.= '<td style="padding: 0px 0px !important;"><input type="hidden" id="ItemDivID" name="'.$value['AccountID'].'_item_id[]" value="'.$data_division_data['ItemDivID'].'" ><input type="text" class="target_data_value target_data_account_'.$value['AccountID'].' target_count_'.$data_division_data['ItemDivID'].'" onkeyup="myFunction_data('.$data_division_data['ItemDivID'].','."'".$actId."'".')" name="'.$value['AccountID'].'_target[]" value="'.$data_division_data['Targate'].'" style=" background-color: #e1e1e17d; text-align: right; height: 30px;width: 100%;font-size: 12px;padding: 5px;" placeholder="0"></td>'; 

				$mm = 1;

				$total+=$data_division_data['Targate'];

				}

				}

				if($mm == "0"){

				$html.= '<td></td>';

				}

				}

				}

				$html.= '<td style="text-align:right;font-size: 13px;" class=" target_count_total_left_'.$value['AccountID'].'"><b  class="left_lower_total">'.$total.'</b></td>';

				$total_data_total+=$total;

				

				

				

				$html.= '</tr>';

				

				$i++; }

				$html.= '<tr>';

				$html.= '<td data-id="New_Business"><input type="hidden" id="AccountID" name="AccountID[]" value="New_Business" >NEW BUSINESS</td>';

				$html.= '<td style="text-align:center;"></td>';

				$html.= '<td></td>';

				$total_bussiness_target =0;

				if(count($data_staff_business_division) > 0){

				foreach($item_division_group as $item_division_group_data){

				foreach($data_staff_business_division as $data_staff_business_division_data){

				

				if($item_division_group_data['id'] == $data_staff_business_division_data['ItemDivID']){

				$mm_data = 0;

				$actId = 'New_Business';

				$html.= '<td style="padding: 0px 0px !important;"><input type="hidden" id="ItemDivID" name="New_Business_item_id[]" value="'.$item_division_group_data['id'].'" ><input type="text" class="target_data_value target_data_account_New_Business target_count_'.$item_division_group_data['id'].'" onkeyup="myFunction_data('.$item_division_group_data['id'].','."'".$actId."'".')" name="New_Business_target[]" value="'.$data_staff_business_division_data['Targate'].'" style=" background-color: #e1e1e17d; text-align: right; height: 30px;width: 100%;font-size: 12px;padding: 5px;" placeholder="0"></td>'; 

				$total_bussiness_target+=$data_staff_business_division_data['Targate'];

				}

				

				}

				

				}

				$html.= '<td style="text-align:right;font-size: 13px;" class=" target_count_total_left_New_Business"><b  class="left_lower_total">'.$total_bussiness_target.'</b></td>';

				$total_data_total+=$total_bussiness_target;

				}else{

				foreach($item_division_group as $item_division_group_data){

				

				$mm_data = 0;

				if($item_division_group_data['id'] != 99){

				$actId = 'New_Business';

				$html.= '<td style="padding: 0px 0px !important;"><input type="hidden" id="ItemDivID" name="New_Business_item_id[]" value="'.$item_division_group_data['id'].'" ><input type="text" class="target_data_value target_data_account_New_Business target_count_'.$item_division_group_data['id'].'" onkeyup="myFunction_data('.$item_division_group_data['id'].','."'".$actId."'".')" name="New_Business_target[]" value="'.$item_division_group_data['Targate'].'" style=" background-color: #e1e1e17d; text-align: right; height: 30px;width: 100%;font-size: 12px;padding: 5px;" placeholder="0"></td>'; 

				$total_data_total+=$total_bussiness_target;     

				}

				}

				$html.= '<td style="text-align:right;font-size: 13px;" class=" target_count_total_left_New_Business"><b  class="left_lower_total">'.$total_bussiness_target.'</b></td>';

				

				}

				

				$html.= '</tr>';

				

				$html.= '</tbody>';

				$html.= '<tfoot>';

				$html.= '<tr>';

				$html.= '<td style="text-transform: uppercase;"><b>Total</b></td>';

				$html.= '<td></td>';

				$html.= '<td></td>';

				foreach($item_division_group as $item_division_group_data){

                $mm = 0;

                if($item_division_group_data['id'] != 99){

				$total_data = 0;

				$target_total =0;

				

				foreach($sum_get_coutomer_division as $sum_get_coutomer_division_data){

				if($item_division_group_data['id'] == $sum_get_coutomer_division_data['ItemDivID']){

				if(count($data_staff_business_division) > 0){

				foreach($data_staff_business_division as $data_staff_business_division_data){ 

				if($item_division_group_data['id'] == $data_staff_business_division_data['ItemDivID']){

				$target_total =0;

				$target_total+=$data_staff_business_division_data['Targate'];

				}

				}

				}

				$mm = 1;

				$target_total+=$sum_get_coutomer_division_data['Targate'];

				$html.= '<td style="font-size: 13px; text-align:right" class="target_count_total_lower_'.$item_division_group_data['id'].'"><b>'. $target_total.'</b></td>';

				

				}

				}

				if($mm == "0"){

				$m_data = 0;

				// $html.= '<td class="1"></td>';

				foreach($data_staff_business_division as $data_staff_business_division_data){

				if($item_division_group_data['id'] == $data_staff_business_division_data['ItemDivID']){

				$html.= '<td style="font-size: 13px; text-align:right" class="target_count_total_lower_'.$item_division_group_data['id'].'"><b>'.$data_staff_business_division_data['Targate'].'</b></td>';

				$m_data++;

				}

				}

				if($m_data  == "0"){

				$html.= '<td style="font-size: 13px; text-align:right" class="target_count_total_lower_'.$item_division_group_data['id'].'"><b>0.00</b></td>';

				

				}

				

				

				

				

				}

				}

				

				}

				$html.= '<td style="font-size: 13px; text-align:right" ><b class=" left_lower_total_count">'.$total_data_total.'</b></td>';

				$html.= '</tr>';

			$html.= '</tfoot>';*/

			echo json_encode($html);

		}

		public function get_groupwise_targetList()

		{

            $company_detail = $this->misc_reports_model->get_company_detail();

            

            $ItemList = $this->misc_reports_model->GetItemListBySubgroup($this->input->post());

            $ItemWiseTarget = $this->misc_reports_model->GetItemWiseTarget($this->input->post());

            // echo "<pre>";

			// print_r($ItemWiseTarget);

			// die;

            $html =''; 

            $html.= '<thead>';

			

            $html.= '<tr style="display:none;">';

            $html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Staff Target</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

            $html.= '<tr>';

            $html.= '<th class="fontsize sortablePop" style="text-align:left; text-transform: uppercase;">ItemID</th>';

            $html.= '<th class="fontsize sortablePop" style="text-align:left; text-transform: uppercase;">Item Name</th>';

            $html.= '<th class="fontsize sortablePop" style="text-align:center; text-transform: uppercase;">Unit</th>';

            $html.= '<th class="fontsize sortablePop" style="text-align:center; text-transform: uppercase;">Packing Qty</th>';

            $html.= '<th class="fontsize " style="text-align:left; text-transform: uppercase;">Target(Qty In Pcs)</th>';

            $html.= '<th class="fontsize " style="text-align:left; text-transform: uppercase;">Target(Amt)</th>';

			

            $html.= '</tr>';

            $html.= '</thead>';

            $html.= '<tbody>';

            foreach($ItemList as $Item){

                $html.= '<tr>';

                $html.= '<td class="fontsize" data-id="'.$Item['item_code'].'"><input type="hidden" id="ItemID" name="ItemID[]" value="'.$Item['item_code'].'" >'.$Item['item_code'].'</td>';

				$html.= '<td class="fontsize" style="text-align:left;">'.$Item['description'].'</td>';

				$html.= '<td class="fontsize" style="text-align:center;">'.$Item['unit'].'</td>';

				$html.= '<td class="fontsize" style="text-align:center;">'.$Item['crate_qty'].'</td>';

				$TargetQty = "";

				$TargetAmt = "";

				foreach($ItemWiseTarget as $TKey=>$Tval){

					if($Item["item_code"]==$Tval["ItemID"]){

						if($Tval["TargetAmt"] > 0){

							$TargetAmt += $Tval["TargetAmt"];

						}

						if($Tval["TargetQty"] > 0){

							$TargetQty += $Tval["TargetQty"];

						}

					}

				}

				

				$html.= '<td class="fontsize"><input type="text"  name="TargetQty[]" class="form-control TargetQty" value="'.$TargetQty.'"></td>';

				$html.= '<td class="fontsize"><input type="text"  name="TargetAmt[]" class="form-control TargetAmt" value="'.$TargetAmt.'"></td>';

				$html.= '<tr>';

			}

			$html.= '</tbody>';

			

			

			echo json_encode($html);

		}

		public function GetTargetAchievement()

		{

            $company_detail = $this->misc_reports_model->get_company_detail();

            

            $ItemList = $this->misc_reports_model->GetItemListBySubgroup($this->input->post());

            $ItemWiseTarget = $this->misc_reports_model->GetItemWiseTarget($this->input->post());

            $SaleAchievment = $this->misc_reports_model->SaleAchievment($this->input->post());

            // echo "<pre>";

			// print_r($SaleAchievment);

			// die;

            $html =''; 

            $html.= '<thead>';

			

            $html.= '<tr style="display:none;">';

            $html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Staff Target</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

            $html.= '<tr>';

            $html.= '<th class="fontsize" style="text-align:left;text-transform: uppercase;">Sr No.</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Item ID</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Item Name</th>';

            $html.= '<th class="fontsize sortable" style="text-align:center; text-transform: uppercase;">Unit</th>';

            $html.= '<th class="fontsize sortable" style="text-align:center; text-transform: uppercase;">Packing Qty</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Target(Pcs)</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Achievement(Pcs)</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Achievement(%)</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Target(Amt)</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Achievement(Amt)</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Achievement(Amt %)</th>';

			

            $html.= '</tr>';

            $html.= '</thead>';

            $html.= '<tbody>';

			$i = 1;

			

			$AllTargetQty = '';

			$AllAchvQty = '';

			$AllTargetAmt = '';

			$AllAchvAmt = '';

            foreach($ItemList as $Item){

                $html.= '<tr>';

                $html.= '<td  class="fontsize">'.$i.'</td>';

                $html.= '<td  class="fontsize">'.$Item['item_code'].'</td>';

				$html.= '<td class="fontsize" style="text-align:left;">'.$Item['description'].'</td>';

				$html.= '<td class="fontsize" style="text-align:center;">'.$Item['unit'].'</td>';

				$html.= '<td class="fontsize" style="text-align:center;">'.$Item['crate_qty'].'</td>';

				$TargetQty = "";

				$TargetAmt = "";

				foreach($ItemWiseTarget as $TKey=>$Tval){

					if($Item["item_code"]==$Tval["ItemID"]){

						if($Tval["TargetAmt"] > 0){

							$TargetAmt += $Tval["TargetAmt"];

						}

						if($Tval["TargetQty"] > 0){

							$TargetQty += $Tval["TargetQty"];

						}

					}

				}

				

				$AchvQty = '';

				$AchvAmt = '';

				foreach($SaleAchievment as $SKey=>$Sval){

					if($Item["item_code"]==$Sval["ItemID"]){

						if($Sval["SaleAmount"] > 0){

							$AchvAmt += $Sval["SaleAmount"];

						}

						if($Sval["SaleQty"] > 0){

							$AchvQty += $Sval["SaleQty"];

						}

					}

				}

				

				$achvPercentage = '';

				$achvAmtPercentage = '';

				if ($TargetQty > 0) {

					$achvPercentage = ($AchvQty / $TargetQty) * 100;

				}

				if ($TargetAmt > 0) {

					$achvAmtPercentage = ($AchvAmt / $TargetAmt) * 100;

				}

				

				$AllTargetQty += $TargetQty;

				$AllAchvQty += $AchvQty;

				$AllTargetAmt += $TargetAmt;

				$AllAchvAmt += $AchvAmt;

				

				$html.= '<td class="fontsize">'.$TargetQty.'</td>';

				$html.= '<td class="fontsize">'.$AchvQty.'</td>';

				$html.= '<td class="fontsize">'.number_format($achvPercentage,2).'</td>';

				$html.= '<td class="fontsize">'.number_format($TargetAmt,2).'</td>';

				$html.= '<td class="fontsize">'.number_format($AchvAmt,2).'</td>';

				$html.= '<td class="fontsize">'.number_format($achvAmtPercentage,2).'</td>';

				$html.= '<tr>';

				$i++;

			}

			$AllachvPercentage = '';

			$AllachvAmtPercentage = '';

			if ($AllTargetQty > 0) {

				$AllachvPercentage = ($AllAchvQty / $AllTargetQty) * 100;

			}

			if ($AllTargetAmt > 0) {

				$AllachvAmtPercentage = ($AllAchvAmt / $AllTargetAmt) * 100;

			}

			$html.= '</tbody>';

			$html.= '<tfoot>';

			$html.= '<tr>';

			$html.= '<td style="text-align:center; text-transform: uppercase;" colspan="5"><b>Total</b></td>';

			$html.= '<td class="fontsize">'.$AllTargetQty.'</td>';

			$html.= '<td class="fontsize">'.$AllAchvQty.'</td>';

			$html.= '<td class="fontsize">'.number_format($AllachvPercentage,2).'</td>';

			$html.= '<td class="fontsize">'.number_format($AllTargetAmt,2).'</td>';

			$html.= '<td class="fontsize">'.number_format($AllAchvAmt,2).'</td>';

			$html.= '<td class="fontsize">'.number_format($AllachvAmtPercentage,2).'</td>';

			$html.= '</tr>';

			$html.= '</tfoot>';

			

			

			echo json_encode($html);

		}

		

		public function GetTargetAchievementGraph()

		{

            $company_detail = $this->misc_reports_model->get_company_detail();

            

            $ItemList = $this->misc_reports_model->GetItemListBySubgroupNew($this->input->post());

            $ItemWiseTarget = $this->misc_reports_model->GetItemWiseTargetNew($this->input->post());

            $SaleAchievment = $this->misc_reports_model->SaleAchievmentNew($this->input->post());

            // echo "<pre>";

			// print_r($SaleAchievment);

			// die;

			

			// Arrays for Highcharts

			$Target = []; // Target

			$Achievement      = []; // Achievement

			

			foreach ($ItemList as $Item) {

				$itemCode = $Item["item_code"];

				$itemName = $Item["description"];

				

				$TargetQty = 0;

				foreach ($ItemWiseTarget as $Tval) {

					if ($itemCode == $Tval["ItemID"]) {

						if ($Tval["TargetQty"] > 0) {

							$TargetQty += $Tval["TargetQty"];

						}

					}

				}

				

				

				$AchvQty = 0;

				foreach ($SaleAchievment as $Sval) {

					if ($itemCode == $Sval["ItemID"]) {

						if ($Sval["SaleQty"] > 0) {

							$AchvQty += $Sval["SaleQty"];

						}

					}

				}

				

				

				$Target[] = [

				'name'  => $itemName,

				'y'     => (int)$TargetQty,

				'z'     => 100,

				'label' => 'Qty'

				];

				

				$Achievement[] = [

				'name'  => $itemName,

				'y'     => (int)$AchvQty,

				'z'     => 100,

				'label' => 'Qty'

				];

			}

			

			$response = [

			"Target" => $Target,

			"Achievement"      => $Achievement

			];

			

			echo json_encode($response);

		}

		

		public function export_TaregetAchievement()

		{

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$ItemList = $this->misc_reports_model->GetItemListBySubgroup($this->input->post());

				$ItemWiseTarget = $this->misc_reports_model->GetItemWiseTarget($this->input->post());

				$SaleAchievment = $this->misc_reports_model->SaleAchievment($this->input->post());

				// echo "<pre>";print_r($StaffWisePartyWiseTargate);die;

				$selected_company = $this->session->userdata('root_company');

				$year = $_SESSION['finacial_year'];

				$month_data = $this->input->post('month_data');

				$month = substr($month_data, -2);

				

				

				$selected_company_details    = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Target VS Achievement Report For Month: ".$month;

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

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

				$list_add[] = "";

				$writer->writeSheetRow('Sheet1', $list_add);

				

				$set_col_tk = [];

				$set_col_tk["Sr No."] =  'Sr No.';

				$set_col_tk["ItemID"] =  'ItemID';

				$set_col_tk["Item Name"] =  'Item Name';

				$set_col_tk["Unit"] =  'Unit';

				$set_col_tk["Packing Qty"] =  'Packing Qty';

				$set_col_tk["Target(Qty)"] =  'Target(Pcs)';

				$set_col_tk["Achievement (Pcs)"] =  'Achievement (Pcs)';

				$set_col_tk["Achievement(%)"] =  'Achievement(%)';

				$set_col_tk["Target(Amt)"] =  'Target(Amt)';

				$set_col_tk["Achievement (Amt)"] =  'Achievement (Amt)';

				$set_col_tk["Achievement (Amt %)"] =  'Achievement (Amt %)';

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				$i = 1;

				

				$AllTargetQty = '';

				$AllAchvQty = '';

				$AllTargetAmt = '';

				$AllAchvAmt = '';

				foreach($ItemList as $Item){

					$list_add = [];

					$list_add[] = $i;

					$list_add[] = $Item['item_code'];

					$list_add[] = $Item['description'];

					$list_add[] = $Item['unit'];

					$list_add[] = $Item['crate_qty'];

					$TargetQty = "";

					$TargetAmt = "";

					foreach($ItemWiseTarget as $TKey=>$Tval){

						if($Item["item_code"]==$Tval["ItemID"]){

							if($Tval["TargetAmt"] > 0){

								$TargetAmt += $Tval["TargetAmt"];

							}

							if($Tval["TargetQty"] > 0){

								$TargetQty += $Tval["TargetQty"];

							}

						}

					}

					

					

					$AchvQty = '';

					$AchvAmt = '';

					foreach($SaleAchievment as $SKey=>$Sval){

						if($Item["item_code"]==$Sval["ItemID"]){

							if($Sval["SaleAmount"] > 0){

								$AchvAmt += $Sval["SaleAmount"];

							}

							if($Sval["SaleQty"] > 0){

								$AchvQty += $Sval["SaleQty"];

							}

						}

					}

					

					$achvPercentage = '';

					$achvAmtPercentage = '';

					if ($TargetQty > 0) {

						$achvPercentage = ($AchvQty / $TargetQty) * 100;

					}

					if ($TargetAmt > 0) {

						$achvAmtPercentage = ($AchvAmt / $TargetAmt) * 100;

					}

					

					$AllTargetQty += $TargetQty;

					$AllAchvQty += $AchvQty;

					$AllTargetAmt += $TargetAmt;

					$AllAchvAmt += $AchvAmt;

					

					$list_add[] = $TargetQty;

					$list_add[] = $AchvQty;

					$list_add[] = number_format($achvPercentage,2);

					$list_add[] = number_format($TargetAmt,2);

					$list_add[] = number_format($AchvAmt,2);

					$list_add[] = number_format($achvAmtPercentage,2);

					

					$writer->writeSheetRow('Sheet1', $list_add);

					$i++;

				}

				

				$AllachvPercentage = '';

				$AllachvAmtPercentage = '';

				if ($AllTargetQty > 0) {

					$AllachvPercentage = ($AllAchvQty / $AllTargetQty) * 100;

				}

				if ($AllTargetAmt > 0) {

					$AllachvAmtPercentage = ($AllAchvAmt / $AllTargetAmt) * 100;

				}

				$list_add = [];

				$list_add[] = 'TOTAL';

				$list_add[] = '';

				$list_add[] = '';

				$list_add[] = '';

				$list_add[] = '';

				$list_add[] = $AllTargetQty;

				$list_add[] = $AllAchvQty;

				$list_add[] = number_format($AllachvPercentage,2);

				$list_add[] = number_format($AllTargetAmt,2);

				$list_add[] = number_format($AllAchvAmt,2);

				$list_add[] = number_format($AllachvAmtPercentage,2);

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Target_VS_ Achievement_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		public function PartyWiseRateReport()

		{

			if (!has_permission_new('PartyWiseRateReport', '', 'view')) {

				access_denied('access_denied');

			}

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['Subgroup'] = $this->misc_reports_model->get_ItemSubGroup2_data();

			$data['title'] = "Party Wise Rate Report";

			$this->load->view('admin/misc_reports/PartyWiseRateReport', $data);

		}

		

		public function GetPartyWiseRateReport()

		{

            $company_detail = $this->misc_reports_model->get_company_detail();

            

            $ItemList = $this->misc_reports_model->GetItemListBySubgroup($this->input->post());

            $Accounts = $this->misc_reports_model->AccountList_table();

            $RateList = $this->misc_reports_model->GetRateList($this->input->post());

            // echo "<pre>";

			// print_r($RateList);

			// die;

            $html =''; 

            $html.= '<thead>';

			

            $html.= '<tr style="display:none;">';

            $html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Staff Target</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

			

            $html.= '<tr>';

            $html.= '<th colspan="4" class="fontsize"></th>';

			foreach($ItemList as $Item){

				$html.= '<th class="fontsize" style="text-align:center; text-transform: uppercase;">'.$Item['subgroup_name'].'</th>';

			}

			$html.= '</tr>';

            $html.= '<tr>';

            $html.= '<th colspan="4" class="fontsize"></th>';

			foreach($ItemList as $Item){

				$html.= '<th class="fontsize" style="text-align:center; text-transform: uppercase;">'.$Item['item_code'].'</th>';

			}

			$html.= '</tr>';

            $html.= '<tr>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Account ID</th>';

            $html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">Account Name</th>';

            $html.= '<th class="fontsize sortable" style="text-align:center; text-transform: uppercase;">Distributor Type</th>';

            $html.= '<th class="fontsize sortable" style="text-align:center; text-transform: uppercase;">Station Name</th>';

			foreach($ItemList as $Item){

				$html.= '<th class="fontsize sortable" style="text-align:left; text-transform: uppercase;">'.$Item['description'].'</th>';

			}

			$html.= '</tr>';

			$html.= '</thead>';

			$html.= '<tbody>';

			

			foreach($Accounts as $Account){

				$html.= '<tr>';

				$html.= '<td  class="fontsize">'.$Account['AccountID'].'</td>';

				$html.= '<td  class="fontsize">'.$Account['company'].'</td>';

				$html.= '<td  class="fontsize">'.$Account['DistType'].'</td>';

				$html.= '<td  class="fontsize">'.$Account['StationName'].'</td>';

				foreach($ItemList as $Item){

					$RateData = '';

					foreach($RateList as $Rate){

						if($Rate['distributor_id'] == $Account['DistributorType'] && $Rate['item_id'] == $Item['item_code']){

							if($this->input->post('RateType') == "Gst"){

								$RateData = $Rate['SaleRate'];

								}else{

								$RateData = $Rate['assigned_rate'];

							}

						}

					}

					$html.= '<td class="fontsize" style="text-align:left;">'.$RateData.'</td>';

				}

				$html.= '<tr>';

				$i++;

			}

			echo json_encode($html);

		}

		

		public function export_PartyWiseRateReport()

		{

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$ItemList = $this->misc_reports_model->GetItemListBySubgroup($this->input->post());

				$Accounts = $this->misc_reports_model->AccountList_table();

				$RateList = $this->misc_reports_model->GetRateList($this->input->post());

				// echo "<pre>";print_r($StaffWisePartyWiseTargate);die;

				$selected_company = $this->session->userdata('root_company');

				$year = $_SESSION['finacial_year'];

				

				

				$selected_company_details    = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Party Wise Rate Report";

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

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

				$list_add[] = "";

				$writer->writeSheetRow('Sheet1', $list_add);

				

				$set_col_tk = [];

				$set_col_tk["Account ID"] =  '';

				$set_col_tk["Account Name"] =  '';

				$set_col_tk["Distributor Type"] =  '';

				$set_col_tk["Station Name"] =  '';

				$i = 1;

				foreach($ItemList as $Item){

					$set_col_tk[$i] =  $Item['subgroup_name'];

					$i++;

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$set_col_tk = [];

				$set_col_tk = [];

				$set_col_tk["Account ID"] =  '';

				$set_col_tk["Account Name"] =  '';

				$set_col_tk["Distributor Type"] =  '';

				$set_col_tk["Station Name"] =  '';

				foreach($ItemList as $Item){

					$set_col_tk[$Item['item_code']] =  $Item['item_code'];

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$set_col_tk = [];

				$set_col_tk["Account ID"] =  'Account ID';

				$set_col_tk["Account Name"] =  'Account Name';

				$set_col_tk["Distributor Type"] =  'Distributor Type';

				$set_col_tk["Station Name"] =  'Station Name';

				foreach($ItemList as $Item){

					$set_col_tk[$Item['description']] =  $Item['description'];

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				foreach($Accounts as $Account){

					$list_add = [];

					$list_add[] = $Account['AccountID'];

					$list_add[] = $Account['company'];

					$list_add[] = $Account['DistType'];

					$list_add[] = $Account['StationName'];

					foreach($ItemList as $Item){

						$RateData = '';

						foreach($RateList as $Rate){

							if($Rate['distributor_id'] == $Account['DistributorType'] && $Rate['item_id'] == $Item['item_code']){

								if($this->input->post('RateType') == "Gst"){

									$RateData = $Rate['SaleRate'];

									}else{

									$RateData = $Rate['assigned_rate'];

								}

							}

						}

						$list_add[] = $RateData;

					}

					

					$writer->writeSheetRow('Sheet1', $list_add);

				}

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'PartyWiseRateReport.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		

		public function export_sale_target_report()

		{

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				

				

				$ItemList = $this->misc_reports_model->GetItemListBySubgroup($this->input->post());

				$ItemWiseTarget = $this->misc_reports_model->GetItemWiseTarget($this->input->post());

				// echo "<pre>";print_r($StaffWisePartyWiseTargate);die;

				$selected_company = $this->session->userdata('root_company');

				$year = $_SESSION['finacial_year'];

				$month_data = $this->input->post('month_data');

				$month = substr($month_data, -2);

				

				

				$selected_company_details    = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');

				//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');

				//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');

				//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Target Report For Month: ".$month;

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

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

				$list_add[] = "";

				$writer->writeSheetRow('Sheet1', $list_add);

				

				$set_col_tk = [];

				$set_col_tk["ItemID"] =  'ItemID';

				$set_col_tk["Item Name"] =  'Item Name';

				$set_col_tk["Unit"] =  'Unit';

				$set_col_tk["Packing Qty"] =  'Packing Qty';

				$set_col_tk["Target(Qty)"] =  'Target(Qty In Pcs)';

				$set_col_tk["Target(Amt)"] =  'Target(Amt)';

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				foreach($ItemList as $Item){

					$list_add = [];

					$list_add[] = $Item['item_code'];

					$list_add[] = $Item['description'];

					$list_add[] = $Item['unit'];

					$list_add[] = $Item['crate_qty'];

					$TargetQty = "";

					$TargetAmt = "";

					foreach($ItemWiseTarget as $TKey=>$Tval){

						if($Item["item_code"]==$Tval["ItemID"]){

							if($Tval["TargetAmt"] > 0){

								$TargetAmt += $Tval["TargetAmt"];

							}

							if($Tval["TargetQty"] > 0){

								$TargetQty += $Tval["TargetQty"];

							}

						}

					}

					

					$list_add[] = $TargetQty;

					$list_add[] = $TargetAmt;

					

					$writer->writeSheetRow('Sheet1', $list_add);

				}

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'ItemWise_Target_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		

		

		public function export_target_report()

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

				

				

				$FGID = "1";

				$ItemGroupList = $this->misc_reports_model->GetItemGroupByMainGroupID($FGID);

				$StaffWisePartyList = $this->misc_reports_model->GetStaffWisePartyList($this->input->post());

				$StaffWisePartyWiseTargate = $this->misc_reports_model->GetStaffWisePartyWiseTargate($this->input->post());

				// echo "<pre>";print_r($StaffWisePartyWiseTargate);die;

				$selected_company = $this->session->userdata('root_company');

				$year = $_SESSION['finacial_year'];

				$month_data = $this->input->post('month_data');

				$month = substr($month_data, -2);

				$staff_target_data =  $this->db->get_where('tblstaff_target',array('Staff_AccountID'=>$this->input->post('staff_account_name'),'PlantID'=>$selected_company,'FY'=>$year,'MonthID'=>$month))->result_array();

				

				if(count($staff_target_data) > 0){

					$hidden_data = 1;

					}else{

					$hidden_data = 0; 

				}

				

				$selected_company_details    = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				//$style_c = array('fill' => '#FFFFFF', 'height'=>30, 'font-size' => 18, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');

				//$style = array('fill' => '#FFFFFF', 'height'=>25, 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000', 'text-align' => 'center', 'font-weight' => '700');

				//$style1 = array('fill' => '#F8CBAD', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');

				//$style2 = array('fill' => '#FCE4D6', 'height'=>25, 'border'=>'left,right,top,bottom', 'border-color' => '#FFFFFF', 'font-size' => 12, 'font' => 'Calibri', 'color' => '#000000');

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Target Report For Month: ".$month." ,  StaffID: " .$this->input->post('staff_account_name');

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

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

				$list_add[] = "";

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				$set_col_tk["PARTY NAME"] =  'PARTY NAME';

				$set_col_tk["DIST. TYPE"] =  'DIST. TYPE';

				$set_col_tk["STATION"] =  'STATION';

				foreach($ItemGroupList as $value1){

					$key = $value1['name'];

					$set_col_tk[$key] =  $value1['name'];

				}

				

				$set_col_tk["Total"] =  'Total';

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$total_data_total = 0;

				$i = 1;

				foreach ($StaffWisePartyList as $k => $value) {

					$total = 0;

					$list_add = [];

					$list_add[] = $value['company'];

					$list_add[] = $value['name'];

					$list_add[] = $value["StationName"];

					foreach($ItemGroupList as $item_division_group_data){

						$mm = 0;

						foreach($StaffWisePartyWiseTargate as $data_division_data){

							if($value['AccountID'] == $data_division_data['AccountID'] && $item_division_group_data['id'] == $data_division_data['ItemSubGroupID']){

								$actId = ''.$value['AccountID'].'';

								$list_add[] = $data_division_data['Targate'];

								$mm = 1;

								$total+=$data_division_data['Targate'];

							}

						}

						if($mm == "0"){

							$list_add[] = "";

						}

					}

					$list_add[] = $total;

					$total_data_total+=$total;

					$i++;

					$writer->writeSheetRow('Sheet1', $list_add);

				}

				

				

				// footer Data

				$list_add = [];

				$list_add[] = "Total";

				$list_add[] = "";

				$list_add[] = "";

				foreach($ItemGroupList as $value){

					$TargetAmt = "";

					foreach($StaffWisePartyWiseTargate as $TKey=>$Tval){

						if($value["id"]==$Tval["ItemSubGroupID"]){

							$TargetAmt += $Tval["Targate"];

						}

					}

					$list_add[] = $TargetAmt;

				}

				

				$alltargetTotal = 0;

				foreach($StaffWisePartyList as $PKey=>$PVal){

					$total_bussiness_target = 0;

					foreach($ItemGroupList as $value){

						$TargetAmt = "";

						foreach($StaffWisePartyWiseTargate as $TKey=>$Tval){

							if($PVal["AccountID"]==$Tval["AccountID"] && $value["id"]==$Tval["ItemSubGroupID"]){

								$TargetAmt = $Tval["Targate"];

							}

						}

						$alltargetTotal += $TargetAmt;

					}

				}

				$list_add[] = $alltargetTotal;

				$writer->writeSheetRow('Sheet1', $list_add);

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Target_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		

		public function get_targetList_bkp_bussiness(){

			$company_detail = $this->misc_reports_model->get_company_detail();

			$data = $this->misc_reports_model->GetStaffWisePartyList($this->input->post());

			$data_division = $this->misc_reports_model->get_coutomer_division($this->input->post());

			$sum_get_coutomer_division = $this->misc_reports_model->sum_get_coutomer_division($this->input->post());

			$item_division_group = $this->misc_reports_model->item_division_group_data();

			

			$selected_company = $this->session->userdata('root_company');

			$year = $_SESSION['finacial_year'];

			$month_data = $this->input->post('month_data');

			$month = substr($month_data, -2);

			$staff_target_data =  $this->db->get_where('tblstaff_target',array('Staff_AccountID'=>$this->input->post('staff_account_name'),'PlantID'=>$selected_company,'FY'=>$year,'MonthID'=>$month))->result_array();

			

			if(count($staff_target_data) > 0){

				$hidden_data = 1;

				}else{

				$hidden_data = 0; 

			}

			$html =''; 

			$html.= '<thead>';

			

			$html.= '<tr style="display:none;">';

			$html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Staff Target</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

			$html.= '<tr>';

			$html.= '<th id="sl" style="text-align:left; text-transform: uppercase;">Party Name <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>';

			$html.= '<th style="text-align:left; text-transform: uppercase;">Dist. Type</th>';

			$html.= '<th style="text-align:left; text-transform: uppercase;">Station</th>';

			foreach($item_division_group as $value){

				if($value['id'] != 99){

					$html.= '<th style="text-align:left; text-transform: uppercase;" >'.$value['name'].'</th>';

					

				}

			}

			$html.= '<th style="text-align:left; text-transform: uppercase;">Total</th>';

			

			

			$html.= '</tr>';

			$html.= '</thead>';

			$html.= '<tbody>';

			$total_data_total = 0;

			$i = 1; foreach($data as $value){

				$html.= '<tr><input type="hidden" name="hidden" value="'.$hidden_data.'">';

				$total = 0;

				

				

				$html.= '<td data-id="'.$value['AccountID'].'"><input type="hidden" id="AccountID" name="AccountID[]" value="'.$value['AccountID'].'" >'.$value['company'].'</td>';

				$html.= '<td style="text-align:center;">'.$value['name'].'</td>';

				$html.= '<td>'.$value['StationName'].'</td>';

				

				foreach($item_division_group as $item_division_group_data){

					$mm = 0;

					if($item_division_group_data['id'] != 99){

						foreach($data_division as $data_division_data){

							if($value['AccountID'] == $data_division_data['AccountID'] && $item_division_group_data['id'] == $data_division_data['ItemDivID']){

								$actId = ''.$value['AccountID'].'';

								$html.= '<td style="padding: 0px 0px !important;"><input type="hidden" id="ItemDivID" name="'.$value['AccountID'].'_item_id[]" value="'.$data_division_data['ItemDivID'].'" ><input type="text" class="target_data_value target_data_account_'.$value['AccountID'].' target_count_'.$data_division_data['ItemDivID'].'" onkeyup="myFunction_data('.$data_division_data['ItemDivID'].','."'".$actId."'".')" name="'.$value['AccountID'].'_target[]" value="'.$data_division_data['Targate'].'" style=" background-color: #e1e1e17d; text-align: right; height: 30px;width: 100%;font-size: 12px;padding: 5px;" placeholder="0"></td>'; 

								$mm = 1;

								$total+=$data_division_data['Targate'];

							}

						}

						if($mm == "0"){

							$html.= '<td></td>';

						}

					}

				}

				$html.= '<td style="text-align:right;font-size: 13px;" class="target_count_total_left_'.$value['AccountID'].'"><b class="left_lower_total">'.$total.'</b></td>';

				$total_data_total+=$total;

				

				

				

				$html.= '</tr>';

				

			$i++; }

			$html.= '</tbody>';

			$html.= '<tfoot>';

			$html.= '<tr>';

			$html.= '<td style="text-transform: uppercase;"><b>Total</b></td>';

			$html.= '<td></td>';

			$html.= '<td></td>';

			foreach($item_division_group as $item_division_group_data){

				$mm = 0;

				if($item_division_group_data['id'] != 99){

					$total_data = 0;

					

					foreach($sum_get_coutomer_division as $sum_get_coutomer_division_data){

						if($item_division_group_data['id'] == $sum_get_coutomer_division_data['ItemDivID']){

							$mm = 1;

							$html.= '<td style="font-size: 13px; text-align:right" class="target_count_total_lower_'.$item_division_group_data['id'].'"><b>'. $sum_get_coutomer_division_data['Targate'].'</b></td>';

							

						}

					}

					if($mm == "0"){

						$html.= '<td class="1"></td>';

					}

				}

				

			}

			$html.= '<td style="font-size: 13px; text-align:right"><b class=" left_lower_total_count">'.$total_data_total.'</b></td>';

			$html.= '</tr>';

			$html.= '</tfoot>';

			echo json_encode($html);

		}

		

		public function target_vs_achievement(){

			if (!has_permission_new('target_vs_achivements', '', 'view')) {

				access_denied('access_denied');

			}

			$data['staff'] = $this->staff_model->get('', ['active' => 1]);

			$data['item_division_group'] = $this->misc_reports_model->item_division_group_data();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['title'] = "Target vs Achievement";

			$this->load->view('admin/misc_reports/target_vs_achievement', $data);

		}

		/*public function get_target_achivement(){

			$company_detail = $this->misc_reports_model->get_company_detail();

			$data = $this->misc_reports_model->GetStaffWisePartyList($this->input->post());

			$data_division = $this->misc_reports_model->get_coutomer_division($this->input->post());

			$sum_get_coutomer_division = $this->misc_reports_model->sum_get_coutomer_division($this->input->post());

			$data_array = $this->input->post();

			$item_division_group = $this->misc_reports_model->item_division_group_data();

			

			

			

			$html =''; 

			$html.= '<thead>';

			

			$html.= '<tr style="display:none;">';

			$html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Target vs Achievement</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

			$html.= '<tr>';

			$html.= '<th id="sl" style="text-align:left;text-transform: uppercase;">Party Name <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>';

			$html.= '<th style="text-align:left; text-transform: uppercase;">Dist. Type</th>';

			$html.= '<th style="text-align:left;text-transform: uppercase;">Station</th>';

			$a = array();

			foreach($item_division_group as $value){

			if($value['id'] != 99){

			foreach($data_division as $data_division_data){

			// if($count != 1){

			

			

			if( $value['id'] == $data_division_data['ItemDivID']){

			

			if (in_array($value['id'], $a)){

			

			}else{

			array_push($a,$value['id']);

			$html.= '<th style="text-align:left; text-transform: uppercase;">'.$value['name'].'</th>';

			}

			

			

			$count = 1;

			

			}

			// }

			}

			

			

			}

			}

			$html.= '<th style="text-align:left; text-transform: uppercase;">Total</th>';

			

			

			$html.= '</tr>';

			$html.= '</thead>';

			$html.= '<tbody>';

			$total_data_total = 0;

			$total_data_total_count_achievment = 0;

			$array_achievement = array();

			$i = 1; 

			foreach($data as $value){

			$data_array['accountId']  = $value['AccountID'];

			$sum_get_achievement_division = $this->misc_reports_model->sum_get_achievement_division($data_array);

			

			array_push($array_achievement,$sum_get_achievement_division);

			

			$html.= '<tr>';

			$total = 0;

			$total_NetChallanAmt = 0;

			

			

			$html.= '<td data-id="'.$value['AccountID'].'" >'.$value['company'].'</td>';

			$html.= '<td style="text-align:center;">'.$value['name'].'</td>';

			$html.= '<td style="">'.$value['StationName'].'</td>';

			

			foreach($item_division_group as $item_division_group_data){

			$mm = 0;

			if($item_division_group_data['id'] != 99){

			if (in_array($item_division_group_data['id'], $a)){

			foreach($data_division as $data_division_data){

			if($mm != 1){

			if($value['AccountID'] == $data_division_data['AccountID'] && $item_division_group_data['id'] == $data_division_data['ItemDivID']){

			$html.= '<td style="text-align:right; class="1">'.$data_division_data['Targate']; 

			

			$mm = 1;

			$total+=$data_division_data['Targate'];

			}

			}

			}

			

			foreach($sum_get_achievement_division as $sum_get_achievement_division_data){

			if($value['AccountID'] == $sum_get_achievement_division_data['AccountID'] && $item_division_group_data['id'] == $sum_get_achievement_division_data['ItemDivID']){

			$html.= ' / '.round($sum_get_achievement_division_data['NetChallanAmt']).'</td>'; 

			

			$mm = 1;

			$total_NetChallanAmt+=$sum_get_achievement_division_data['NetChallanAmt'];

			} 

			}  

			if($mm == "0"){

			$html.= '<td></td>';

			}

			}

			

			

			}

			}

			$html.= '<td  style="text-align:right; font-size:13px;"><b>'.$total.' / '.round($total_NetChallanAmt).'<b></td>';

			$total_data_total+=$total;

			$total_data_total_count_achievment+=$total_NetChallanAmt;

			

			

			

			$html.= '</tr>';

			

			$i++; }

			$html.= '</tbody>';

			$html.= '<tfoot>';

			$html.= '<tr>';

			$html.= '<td style="text-transform: uppercase;">Total</td>';

			$html.= '<td></td>';

			$html.= '<td></td>';

			$total_data_achive_data = 0;

			foreach($item_division_group as $key=>$item_division_group_data){

			$ii = 0;

			$mm = 0;

			$mmm = 0;

			if($item_division_group_data['id'] != 99){

			$total_data = 0;

			$total_data_achive = 0;

			

			

			foreach($sum_get_coutomer_division as $sum_get_coutomer_division_data){

			if($item_division_group_data['id'] == $sum_get_coutomer_division_data['ItemDivID']){

			$mm = 1;

			if($sum_get_coutomer_division_data['Targate'] == ''){

			$html.= '<td  style="text-align:right;  font-size:13px;"><b>0';

			}else{

			$html.= '<td  style="text-align:right;  font-size:13px;"><b>'. $sum_get_coutomer_division_data['Targate'];

			}

			

			

			}

			}

			

			foreach($array_achievement as $array_achievement_data){

			foreach($array_achievement_data as $array_achievement_data_array){

			

			if($item_division_group_data['id'] == $array_achievement_data_array['ItemDivID']){

			$mmm = 1;

			

			$total_data_achive+=$array_achievement_data_array['NetChallanAmt'];

			

			$total_data_achive_data+=$total_data_achive; 

			}

			}}

			

			$html.= ' / '.round($total_data_achive).'</b></td >';

			

			

			}

			

			}

			$html.= '<td  style="text-align:right; font-size:13px;"><b>'.$total_data_total.' / '.round($total_data_total_count_achievment).'</b></td>';

			$html.= '</tr>';

			$html.= '</tfoot>';

			echo json_encode($html);

		}*/

		

		public function get_target_achivement(){

			$company_detail = $this->misc_reports_model->get_company_detail();

			$FGID = "1";

			$ItemGroupList = $this->misc_reports_model->GetItemGroupByMainGroupID($FGID);

			$StaffWisePartyList = $this->misc_reports_model->GetStaffWisePartyList($this->input->post());

			$StaffWisePartyWiseTargate = $this->misc_reports_model->GetStaffWisePartyWiseTargate($this->input->post());

			$StaffWiseSaleAchievment = $this->misc_reports_model->StaffWiseSaleAchievment($this->input->post());

			// echo "<pre>";

			// print_r($StaffWiseSaleAchievment);

			// die;

			$html =''; 

			$html.= '<thead>';

			

			$html.= '<tr style="display:none;">';

			$html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Staff Target</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

			$html.= '<tr>';

			$html.= '<th id="sl" style="text-align:left; text-transform: uppercase;" class="fontsize">Party Name <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>';

			$html.= '<th style="text-align:left; text-transform: uppercase;" class="fontsize">Dist. Type</th>';

			$html.= '<th style="text-align:left; text-transform: uppercase;" class="fontsize">Station</th>';

			foreach($ItemGroupList as $value){

				$html.= '<th style="text-align:left; text-transform: uppercase;" class="fontsize">'.$value['name'].'</th>';

			}

			$html.= '<th style="text-align:left; text-transform: uppercase;">Total</th>';

			$html.= '</tr>';

			$html.= '</thead>';

			$html.= '<tbody>';

			$alltargetTotal = 0;

			$allAchievmentTotal = 0;

			foreach($StaffWisePartyList as $PKey=>$PVal){

				$html.= '<tr>';

				$html.= '<td data-id="'.$PVal['AccountID'].'" class="fontsize"><input type="hidden" id="AccountID" name="AccountID[]" value="'.$PVal['AccountID'].'" >'.$PVal['company'].'</td>';

				$html.= '<td class="fontsize" style="text-align:center;">'.$PVal['name'].'</td>';

				$html.= '<td class="fontsize">'.$PVal['StationName'].'</td>';

				$total_bussiness_target = 0;

				$total_bussiness_Achievement = 0;

				foreach($ItemGroupList as $value){

					$TargetAmt = 0;

					foreach($StaffWisePartyWiseTargate as $TKey=>$Tval){

						if($PVal["AccountID"]==$Tval["AccountID"] && $value["id"]==$Tval["ItemSubGroupID"]){

							$TargetAmt = $Tval["Targate"];

						}

					}

					

					$AchieveAmt = 0;

					foreach($StaffWiseSaleAchievment as $AKey=>$Aval){

						if($PVal["AccountID"]==$Aval["AccountID"] && $value["id"]==$Aval["SubGrpID1"]){

							$AchieveAmt = $Aval["Amount"];

						}

					}

					

					$total_bussiness_target += $TargetAmt;

					$alltargetTotal += $TargetAmt;

					$total_bussiness_Achievement += $AchieveAmt;

					$allAchievmentTotal += $AchieveAmt;

					$actId = ''.$PVal['AccountID'].'';

					if($TargetAmt == 0 && $AchieveAmt == 0){

						$html.= '<td align="right"></td>';

						}else{

						$html.= '<td class="fontsize" align="right"><span style="color:#1c4e80;">'.$TargetAmt." </span> / <span style='color:green;'>".$AchieveAmt.'</span></td>';

					}

				}

				// $html.= '<td></td>';

				if($total_bussiness_Achievement == 0 && $total_bussiness_target == 0){

					$html.= '<td class="fontsize" style="text-align:right;font-size: 13px;" class=" target_count_total_left_'.$PVal['AccountID'].'"><b  class="left_lower_total"></b></td>';

					}else{

					$html.= '<td class="fontsize" style="text-align:right;font-size: 13px;" class=" target_count_total_left_'.$PVal['AccountID'].'"><b  class="left_lower_total"><span style="color:#1c4e80;">'.$total_bussiness_target."</span> / <span style='color:green;'>".$total_bussiness_Achievement.'</span></b></td>';

				}

				$html.= '<tr>';

			}

			$html.= '</tbody>';

			

			$html.= '<tfoot>';

			$html.= '<tr>';

			$html.= '<td class="fontsize2" style="text-transform: uppercase;"><b>Total</b></td>';

			$html.= '<td></td>';

			$html.= '<td></td>';

			foreach($ItemGroupList as $value){

				$TargetAmt = 0;

				foreach($StaffWisePartyWiseTargate as $TKey=>$Tval){

					if($value["id"]==$Tval["ItemSubGroupID"]){

						$TargetAmt += $Tval["Targate"];

					}

				}

				$AchieveAmt = 0;

				foreach($StaffWiseSaleAchievment as $AKey=>$Aval){

					if($value["id"]==$Aval["SubGrpID1"]){

						$AchieveAmt = $Aval["Amount"];

					}

				}

				if($TargetAmt == 0 && $AchieveAmt == 0){

					$html.= '<td style="font-size: 13px; text-align:right" class="target_count_total_lower_'.$value['id'].'"><b></b></td>';

					}else{

					$html.= '<td class="fontsize2" style="text-align:right" class="target_count_total_lower_'.$value['id'].'"><b><span style="color:#1c4e80;">'.$TargetAmt."</span> / <span style='color:green;'>".$AchieveAmt.'<span></b></td>';

				}

			}

			// $html.= '<td></td>';

			if($alltargetTotal == 0 && $allAchievmentTotal == 0){

				$html.= '<td style="text-align:right;font-size: 13px;" ><b  class="left_lower_total_count"></b></td>';

				}else{

				$html.= '<td class="fontsize2" style="text-align:right;" ><b  class="left_lower_total_count"><span style="color:#1c4e80;">'.$alltargetTotal."</span> / <span style='color:green;'>".$allAchievmentTotal.'</span></b></td>';

			}

			$html.= '<tr>';

			$html.= '</tfoot>';

			echo json_encode($html);

		}

		

		public function get_target_achivement_data_bkp(){

			$company_detail = $this->misc_reports_model->get_company_detail();

			$data = $this->misc_reports_model->GetStaffWisePartyList($this->input->post());

			$data_division = $this->misc_reports_model->get_coutomer_division($this->input->post());

			$sum_get_coutomer_division = $this->misc_reports_model->sum_get_coutomer_division($this->input->post());

			$data_array = $this->input->post();

			$item_division_group = $this->misc_reports_model->item_division_group_data();

			

			

			

			$html =''; 

			$html.= '<thead>';

			

			$html.= '<tr style="display:none;">';

			$html.= '<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Target vs Achievement</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>

			</tr>';

			$html.= '<tr>';

			$html.= '<th id="sl" style="text-align:left;text-transform: uppercase;">Party Name <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>';

			$html.= '<th style="text-align:left; text-transform: uppercase;">Dist. Type</th>';

			$html.= '<th style="text-align:left;text-transform: uppercase;">Station</th>';

			foreach($item_division_group as $value){

				if($value['id'] != 99){

					$html.= '<th style="text-align:left; text-transform: uppercase;">'.$value['name'].'</th>';

					

				}

			}

			$html.= '<th style="text-align:left; text-transform: uppercase;">Total</th>';

			

			

			$html.= '</tr>';

			$html.= '</thead>';

			$html.= '<tbody>';

			$total_data_total = 0;

			$array_achievement = array();

			// $array_achievement = [];

			$i = 1; foreach($data as $value){

				$data_array['accountId']  = $value['AccountID'];

				$sum_get_achievement_division = $this->misc_reports_model->sum_get_achievement_division($data_array);

				

				array_push($array_achievement,$sum_get_achievement_division);

				// print_r($array_achievement);

				$html.= '<tr>';

				$total = 0;

				$total_NetChallanAmt = 0;

				

				

				$html.= '<td data-id="'.$value['AccountID'].'" >'.$value['company'].'</td>';

				$html.= '<td style="text-align:center;">'.$value['name'].'</td>';

				$html.= '<td style="">'.$value['StationName'].'</td>';

				

				foreach($item_division_group as $item_division_group_data){

					$mm = 0;

					if($item_division_group_data['id'] != 99){

						foreach($data_division as $data_division_data){

							if($value['AccountID'] == $data_division_data['AccountID'] && $item_division_group_data['id'] == $data_division_data['ItemDivID']){

								$html.= '<td style="text-align:right;">'.$data_division_data['Targate']; 

								

								$mm = 1;

								$total+=$data_division_data['Targate'];

							}

						}

						foreach($sum_get_achievement_division as $sum_get_achievement_division_data){

							if($value['AccountID'] == $sum_get_achievement_division_data['AccountID'] && $item_division_group_data['id'] == $sum_get_achievement_division_data['ItemDivID']){

								$html.= ' / '.round($sum_get_achievement_division_data['NetChallanAmt']).'</td>'; 

								

								$mm = 1;

								$total_NetChallanAmt+=$sum_get_achievement_division_data['NetChallanAmt'];

							}

						}

						if($mm == "0"){

							$html.= '<td></td>';

						}

					}

				}

				$html.= '<td  style="text-align:right; font-size:13px;"><b>'.$total.' / '.round($total_NetChallanAmt).'<b></td>';

				$total_data_total+=$total;

				

				

				

				$html.= '</tr>';

				

			$i++; }

			$html.= '</tbody>';

			$html.= '<tfoot>';

			$html.= '<tr>';

			$html.= '<td style="text-transform: uppercase;">Total</td>';

			$html.= '<td></td>';

			$html.= '<td></td>';

			$total_data_achive_data = 0;

			foreach($item_division_group as $key=>$item_division_group_data){

				$ii = 0;

				$mm = 0;

				$mmm = 0;

				if($item_division_group_data['id'] != 99){

					$total_data = 0;

					$total_data_achive = 0;

					

					

					foreach($sum_get_coutomer_division as $sum_get_coutomer_division_data){

						if($item_division_group_data['id'] == $sum_get_coutomer_division_data['ItemDivID']){

							$mm = 1;

							if($sum_get_coutomer_division_data['Targate'] == ''){

								$html.= '<td  style="text-align:right;  font-size:13px;"><b>0';

								}else{

								$html.= '<td  style="text-align:right;  font-size:13px;"><b>'. $sum_get_coutomer_division_data['Targate'];

							}

							

							

						}

					}

					//   print_r($array_achievement);

					

					foreach($array_achievement as $array_achievement_data){

						foreach($array_achievement_data as $array_achievement_data_array){

							// print_r($array_achievement_data[$ii]['ItemDivID']);

							// print_r($item_division_group_data['id']);

							if($item_division_group_data['id'] == $array_achievement_data_array['ItemDivID']){

								$mmm = 1;

								

								$total_data_achive+=$array_achievement_data_array['NetChallanAmt'];

								

								$total_data_achive_data+=$total_data_achive; 

							}

						}}

						

						$html.= ' / '.round($total_data_achive).'</b></td >';

						

						if($mm == "0"){

							$html.= '<td ></td>';

						}

				}

				

			}

			$html.= '<td  style="text-align:right; font-size:13px;"><b>'.$total_data_total.' / '.round($total_data_achive_data).'</b></td>';

			$html.= '</tr>';

			$html.= '</tfoot>';

			echo json_encode($html);

		}

		// end target entery and target vs achivememnt

		

		public function market_outstanding(){

			

			if (!has_permission_new('market_outstanding', '', 'view')) {

				access_denied('access_denied');

			}

			$title = _l('Market Outstanding');

			$data['title'] = $title;

			$data['route'] = $this->misc_reports_model->get_all_route();

			$data['states'] = $this->misc_reports_model->get_all_states();

			$data['dist_type'] = $this->misc_reports_model->get_all_dist_type();

			$data['item_division'] = $this->misc_reports_model->get_all_item_division();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$this->load->view('admin/misc_reports/market_outstanding', $data);

		}

		public function export_market_outstanding(){

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				$data = $this->misc_reports_model->market_outstanding_data($this->input->post());

				

				$credit_data = $this->misc_reports_model->market_outstanding_credit_data($this->input->post());

				$debit_data = $this->misc_reports_model->market_outstanding_debit_data($this->input->post());

				$opn_bal_data = $this->misc_reports_model->market_outstanding_opn_bal_data($this->input->post());

				$last_billDate = $this->misc_reports_model->market_outstanding_last_billDate($this->input->post());

				$currDaySale = $this->misc_reports_model->market_outstanding_currDaySale($this->input->post());

				$preDaySale = $this->misc_reports_model->market_outstanding_preDaySale($this->input->post());

				$selected_company_details = $this->misc_reports_model->get_company_detail();

				$as_on = $this->input->post("as_on");

				$routName = $this->input->post("routName");

				

				

				$states = $this->input->post("states");

				$state_name = $this->sale_reports_model->get_state_name($states);

				

				$loc_type = $this->input->post("loc_type");

				$loc_type = $this->input->post('loc_type');

				if($loc_type == 1){

					$loc_type_name = "Local";

					}elseif($loc_type == 2){

					$loc_type_name = "OutStation";

					}elseif($loc_type == 3){

					$loc_type_name = "NotDefined";

				}

				

				$dist_type = $this->input->post("dist_type");

				$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);

				

				$staff_name = $this->input->post("staff_name");

				

				$writer = new XLSXWriter();

				$j=0;

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				$j++;

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				$j++;

				

				$msg = "market outstanding Report  Date: ".$as_on;

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				$j++;

				if($routName !=''){

					

					$msg1 = "routName: ".$routName;

					$filter1 = array($msg1);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter1);

					$j++;

				}

				if($loc_type_name !=''){

					

					$msg2 = "Loc Type: ".$loc_type_name;

					$filter2 = array($msg2);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter2);

					$j++;

				}

				if($client_type_name !=''){

					

					$msg3 = "Distributor Type: ".$client_type_name->name;

					$filter3 = array($msg3);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter3);

					$j++;

				}

				if($staff_name !=''){

					

					$msg4 = "StaffName: ".$staff_name;

					$filter4 = array($msg4);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter4);

					$j++;

				}

				if($States !=''){

					$msg5 = " States: ".$state_name->state_name;

					$filter5 = array($msg5);

					$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

					$writer->writeSheetRow('Sheet1', $filter5);

				}

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

				$set_col_tk["SOID"] =  'SOID';

				$set_col_tk["StationName"] =  'StationName';

				$set_col_tk["CtrlActID"] =  'CtrlActID';

				$set_col_tk["AccountID"] =  'AccountID';

				$set_col_tk["AccountName"] =  'AccountName';

				$set_col_tk["StateID"] =  'StateID';

				$set_col_tk["DebitAmt"] =  'DebitAmt';

				$set_col_tk["CreditAmt"] =  'CreditAmt';

				$set_col_tk["LastBillDate"] =  'LastBillDate';

				$set_col_tk["PreDaySale"] =  'PreDaySale';

				$set_col_tk["CurrDaySale"] =  'CurrDaySale';

				

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$totalDebit = 0; 

				$totalCredit = 0;

				$totalCurrSale = 0;

				$totalPreSale = 0;

				foreach($data as $key=>$value){

					

					$crAmt = 0;

					$drAmt = 0;

					$bal = 0;

					foreach($credit_data as $key1=>$value1){

						if(strtoupper($value["AccountID"]) == strtoupper($value1["AccountID"])){

							$crAmt = $value1["Credit_Amt"];

						}

					}

					

					foreach($debit_data as $key2=>$value2){

						if(strtoupper($value["AccountID"]) == strtoupper($value2["AccountID"])){

							$drAmt = $value2["Debit_Amt"];

						}

					}

					

					foreach($opn_bal_data as $key3=>$value3){

						if(strtoupper($value["AccountID"]) == strtoupper($value3["AccountID"])){

							$OpnAmt = $value3["opn_Amt"];

						}

					}

					

					$bal = $crAmt - $drAmt;

					$bal_new = $OpnAmt - $bal;

					if($bal_new == 0 || $bal_new == 0.00){

						

						}else{

						$list_add = [];

						$list_add[] = "";

						$list_add[] = $value["StationName"];

						$list_add[] = $value["CtrlAccountID"];

						$list_add[] = $value["AccountID"];

						$list_add[] = $value["company"];

						$list_add[] = $value["state"];

						

						

						if($bal_new <= 0){

							$list_add[] = "";

							$list_add[] = abs($bal_new);

							

							$totalCredit = $totalCredit + $bal_new;

							}else{

							

							$list_add[] = abs($bal_new);

							$list_add[] = "";

							

							$totalDebit = $totalDebit + $bal_new;

						}

						

						$mm = 0;

						foreach($last_billDate as $key3=>$value3){

							if(strtoupper($value["AccountID"]) == strtoupper($value3["AccountID"])){

								$list_add[] = _d(substr($value3["TransDate"],0,10));

								$mm++;

							}

						}

						if($mm == 0){

							$list_add[] = "";

						}

						

						$mm2 = 0;

						foreach($preDaySale as $key5=>$value5){

							if(strtoupper($value["AccountID"]) == strtoupper($value5["AccountID"])){

								$list_add[] = $value5["NetChallanAmt"];

								$totalPreSale = $totalPreSale + $value5["NetChallanAmt"];

								$mm2++;

							}

						}

						if($mm2 == 0){

							$list_add[] = "";

						}

						$mm1 = 0;

						foreach($currDaySale as $key4=>$value4){

							if(strtoupper($value["AccountID"]) == strtoupper($value4["AccountID"])){

								$list_add[] = $value4["NetChallanAmt"];

								$totalCurrSale = $totalCurrSale + $value4["NetChallanAmt"];

								$mm1++;

							}

						}

						if($mm1 == 0){

							$list_add[] = "";

						}

						

						$writer->writeSheetRow('Sheet1', $list_add);

						$i++;

					}

					//}

					

				}

				$list_add = [];

				$list_add[] = "";

				$list_add[] = "Total";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = abs($totalDebit);

				$list_add[] = abs($totalCredit);

				$list_add[] = "";

				$list_add[] = $totalPreSale;

				$list_add[] = $totalCurrSale;

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				$list_add = [];

				$list_add[] = "";

				$list_add[] = "Balance CR";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$balance_cr = abs($totalCredit) - abs($totalDebit);

				$list_add[] = abs($balance_cr);

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

				$filename = 'market outstanding Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		public function market_outstanding_report()

		{

			

			if (!has_permission_new('market_outstanding', '', 'view')) {

				access_denied('access_denied');

			}

			$data = $this->misc_reports_model->market_outstanding_data($this->input->post());

			// echo "<pre>";

			// print_r($data);

			// die;

			$credit_data = $this->misc_reports_model->market_outstanding_credit_data($this->input->post());

			$debit_data = $this->misc_reports_model->market_outstanding_debit_data($this->input->post());

			$opn_bal_data = $this->misc_reports_model->market_outstanding_opn_bal_data($this->input->post());

			$last_billDate = $this->misc_reports_model->market_outstanding_last_billDate($this->input->post());

			$currDaySale = $this->misc_reports_model->market_outstanding_currDaySale($this->input->post());

			$preDaySale = $this->misc_reports_model->market_outstanding_preDaySale($this->input->post());

			$company_detail = $this->misc_reports_model->get_company_detail();

			$as_on = $this->input->post("as_on");

			$routName = $this->input->post("routName");

			

			$states = $this->input->post("states");

			$state_name = $this->sale_reports_model->get_state_name($states);

			

			$loc_type = $this->input->post("loc_type");

			if($loc_type == 1){

				$loc_type_name = "Local";

				}elseif($loc_type == 2){

				$loc_type_name = "OutStation";

				}elseif($loc_type == 3){

				$loc_type_name = "NotDefined";

			}

			

			$dist_type = $this->input->post("dist_type");

			$client_type_name = $this->sale_reports_model->get_client_type_name($client_type);

			

			$staff_name = $this->input->post("staff_name");

			

			

			/*$_transID = array();

				foreach($data as $key=>$value){

				foreach($trans_data as $key1=>$value1){

				if(strtoupper($value["AccountID"]) == strtoupper($value1["AccountID"])){

				array_push($_transID, $value["AccountID"]);

				}

				}

			}*/

			

			$html = '';

			$html .= '<table class="tree table table-striped table-bordered table-market_outstanding fixTableHead" id="table-market_outstanding">';

			$html .= '<thead>';

			$html.= '<tr style="display:none;">';

			$html.= '<td colspan="11" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;">'.$company_detail->company_name.'</span><br><span style="font-size:10px;font-weight:600;">'.$company_detail->address.'</span><br><span style="font-size:10px;font-weight:600;">Market Outstanding</span></h5></td>';

			$html.= '</tr>';

			$html.= '<tr style="display:none;">';

			$html.= '<td colspan="11" style="font-size:10px;font-weight:600;text-align:center;">Date : '.$as_on.', RoutName : '.$routName.', States : '.$state_name->state_name.', Loc Type : '.$loc_type_name.', Distributor Type : '.$client_type_name->name.', StaffName : '.$staff_name.'</td>';

			$html.= '</tr>';

			$html .= '<tr>';

			$html .= '<th class="sortable">Sr.No</th>';

			$html .= '<th class="sortable">SOID</th>';

			$html .= '<th class="sortable">StationName</th>';

			$html .= '<th class="sortable">CtrlActID</th>';

			$html .= '<th class="sortable">AccountID</th>';

			$html .= '<th class="sortable">AccountName</th>';

			$html .= '<th class="sortable">StateID</th>';

			$html .= '<th class="sortable">DebitAmt</th>';

			$html .= '<th class="sortable">CreditAmt</th>';

			$html .= '<th class="sortable">LastBillDate</th>';

			$html .= '<th class="sortable">PreDaySale</th>';

			$html .= '<th class="sortable">CurrDaySale</th>';

			$html .= '</tr>';

			$html .= '</thead>';

			$html .= '<tbody>';

			$totalDebit = 0; 

			$totalCredit = 0;

			$totalCurrSale = 0;

			$totalPreSale = 0;

			$i = 1;

			foreach($data as $key=>$value){

				/*if (!in_array($value["AccountID"], $_transID)){

					

				}else{*/

				

				$crAmt = 0;

				$drAmt = 0;

				$bal = 0;

				foreach($credit_data as $key1=>$value1){

					if(strtoupper($value["AccountID"]) == strtoupper($value1["AccountID"])){

						$crAmt = $value1["Credit_Amt"];

					}

				}

				

				foreach($debit_data as $key2=>$value2){

					if(strtoupper($value["AccountID"]) == strtoupper($value2["AccountID"])){

						$drAmt = $value2["Debit_Amt"];

					}

				}

				

				foreach($opn_bal_data as $key3=>$value3){

					if(strtoupper($value["AccountID"]) == strtoupper($value3["AccountID"])){

						$OpnAmt = $value3["opn_Amt"];

					}

				}

				$bal = $crAmt - $drAmt;

				$bal_new = $OpnAmt - $bal;

				if($bal_new == 0 || $bal_new == 0.00){

					

					}else{

					$html .= '<tr>';

					$html .= '<td>'.$i.'</td>';

					$html .= '<td></td>';

					$html .= '<td>'.$value["StationName"].'</td>';

					$html .= '<td align="center">'.$value["CtrlAccountID"].'</td>';

					$html .= '<td align="center">'.$value["AccountID"].'</td>';

					$html .= '<td>'.$value["company"].'</td>';

					$html .= '<td align="center">'.$value["state"].'</td>';

					

					if($bal_new <= 0){

						$html .= '<td align="right"></td>';

						$html .= '<td align="right">'.number_format(abs($bal_new),2).'</td>';

						$totalCredit = $totalCredit + $bal_new;

						}else{

						$html .= '<td align="right">'.number_format(abs($bal_new),2).'</td>';

						$html .= '<td align="right"></td>';

						$totalDebit = $totalDebit + $bal_new;

					}

					

					$mm = 0;

					foreach($last_billDate as $key3=>$value3){

						if(strtoupper($value["AccountID"]) == strtoupper($value3["AccountID"])){

							$html .= '<td align="right">'._d(substr($value3["TransDate"],0,10)).'</td>';

							$mm++;

						}

					}

					if($mm == 0){

						$html .= '<td></td>';

					}

					

					$mm2 = 0;

					foreach($preDaySale as $key5=>$value5){

						if(strtoupper($value["AccountID"]) == strtoupper($value5["AccountID"])){

							$html .= '<td align="right">'.number_format($value5["NetChallanAmt"],2).'</td>';

							$totalPreSale = $totalPreSale + $value5["NetChallanAmt"];

							$mm2++;

						}

					}

					if($mm2 == 0){

						$html .= '<td></td>';

					}

					$mm1 = 0;

					foreach($currDaySale as $key4=>$value4){

						if(strtoupper($value["AccountID"]) == strtoupper($value4["AccountID"])){

							$html .= '<td align="right">'.number_format($value4["NetChallanAmt"],2).'</td>';

							$totalCurrSale = $totalCurrSale + $value4["NetChallanAmt"];

							$mm1++;

						}

					}

					if($mm1 == 0){

						$html .= '<td></td>';

					}

					

					$html .= '</tr>';

					$i++;

				}

				//}

				

			}

			

			$html .= '</tbody>';

			$html .= '<tfoot>';

			$html .= '<tr >';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td><b style="color:red;">Total</b></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td align="right"> <b style="color:red;">'.number_format(abs($totalDebit),2).'</b></td>';

			$html .= '<td align="right"><b style="color:red;">'.number_format(abs($totalCredit),2).'</b></td>';

			$html .= '<td></td>';

			$html .= '<td align="right"><b style="color:red;">'.number_format($totalPreSale,2).'</b></td>';

			$html .= '<td align="right"><b style="color:red;">'.number_format($totalCurrSale,2).'</b></td>';

			$html .= '</tr>';

			$i++;

			$html .= '<tr >';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td ><b>Balance CR</b></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$balance_cr = abs($totalCredit) - abs($totalDebit);

			$html .= '<td align="right"><b>'.number_format(abs($balance_cr),2).'</b></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td align="right"><b></b></td>';

			$html .= '</tr>';

			$html .= '</tfoot>';

			$html .= '</table>';

			echo json_encode($html);

		}

		

		// Start Crate ledger 

		public function crate_legder(){

			if (!has_permission_new('crate_ledger', '', 'view')) {

				access_denied('access_denied');

			}

			$title = _l('Crate Legder');

			$data['title'] = $title;

			$data['vendors'] = $this->misc_reports_model->get_vendor_data();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['state_list'] = $this->misc_reports_model->get_state_list();

			$this->load->view('admin/misc_reports/crate_legder', $data);

		} 

		// Start Crate ledger New

		public function Crate_Legder_Report()

		{

			if (!has_permission_new('crate_ledger', '', 'view')) {

				access_denied('access_denied');

			}

			$title = _l('Crate Legder');

			$data['title'] = $title;

			$data['vendors'] = $this->misc_reports_model->get_vendor_data();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['DriverList']    = $this->misc_reports_model->GetDriverList();

			$data['state_list'] = $this->misc_reports_model->get_state_list();

			$this->load->view('admin/misc_reports/Crate_Legder_Report', $data);

		}

		

		

		//  Crate Received vie vehicle return 

		public function crateRcvdVehicle(){

			if (!has_permission_new('Crates_received_via_vehicle_return', '', 'view')) {

				access_denied('access_denied');

			}

			$title = _l('Crate Received via Vehicle ');

			

			$data['title'] = $title;

			$data['DriverList']    = $this->misc_reports_model->GetDriverList();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$this->load->view('admin/misc_reports/CrateRcvdVehicle', $data);

		}

		

		public function get_vendor_data($id =""){

			$vendor = $this->misc_reports_model->get_data_vendor($id);

			echo json_encode([

			'vendor' => $vendor,

			]);

			

		}

		public function export_crate_legder()

		{

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$filterdata = array(

				'from_date' => $this->input->post('from_date'),

				'to_date'  => $this->input->post('to_date'),

				'accountId'  => $this->input->post('accountId'),

				'state_type'  => $this->input->post('state_type'),

				'loc_type'  => $this->input->post('loc_type'),

				'order_by'  => $this->input->post('order_by')

				);

				$from_date = $this->input->post('from_date');

				$to_date = $this->input->post('to_date');

				$accountId = $this->input->post('accountId');

				$state_type = $this->input->post('state_type');

				$loc_type = $this->input->post('loc_type');

				$order_by = $this->input->post('order_by');

				$account_full_name = $this->input->post('account_full_name');

				

				$body_data = $this->misc_reports_model->GetCrateLedger($filterdata);

				/*echo json_encode($body_data['OpenCrate']);

				die;*/

				$selected_company_details = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Crate legder Report For Month: ".$month." ,  StaffID: " .$this->input->post('staff_account_name');

				

				if($accountId != ''){

					$msg = "Crate legder Report For Account: ".$account_full_name." ,  form date: " .$from_date." to date ".$to_date;

					}else{

					$msg = "Crate legder Report For Billing Date: ".$from_date." ,  Vehicle Rtn Date: " .$to_date." State ".$state_type;

				}

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				

				// empty row

				$list_add = [];

				if($accountId !== ''){

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					}else{

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

					$list_add[] = "";

				}

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				if($accountId !== ''){

					$set_col_tk["VoucherID"] =  'VoucherID';

					$set_col_tk["Date"] =  'Date';

					$set_col_tk["Narration"] =  'Narration';

					$set_col_tk["Debit"] =  'Debit';

					$set_col_tk["Credit"] =  'Credit';

					$set_col_tk["Balance"] =  'Balance';

					$set_col_tk["DrCr"] =  'DrCr';

					}else{

					

					$set_col_tk["AccountId"] =  'AccountId';

					$set_col_tk["Account Name"] =  'Account Name';

					$set_col_tk["Address"] =  'Address';

					$set_col_tk["OpCrates"] =  'OpCrates';

					$set_col_tk["DebitCrates"] =  'Debit Crates';

					$set_col_tk["CreditCrates"] =  'Credit Crates';

					$set_col_tk["Bal"] =  'Bal';

					$set_col_tk["DrCr"] =  'DrCr';

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$TotalDebit = 0;

				$TotalCredit = 0;

				

				if($accountId !== ''){

					$list_add = [];

					$list_add[] = "";

					$list_add[] = to_sql_date($from_date);

					$list_add[] = "Opening Crates";

					$OPNBal = 0;

					$DrCr = '';

					if($body_data['OpenCrate'] > 0){

						$list_add[] = $body_data['OpenCrate'];

						$list_add[] = "";

						$DrCr = 'Dr';

						$OPNBal += $body_data['OpenCrate'];

						}else{

						$list_add[] = "";

						$list_add[] = $body_data['OpenCrate'];

						$DrCr = 'Cr';

						$OPNBal += $body_data['OpenCrate'];

					}

					

					$list_add[] = abs($body_data['OpenCrate']);

					$list_add[] = $DrCr;

					

					$writer->writeSheetRow('Sheet1', $list_add);

					

					foreach ($body_data['Trans'] as $key1 => $value1) {

						$list_add = [];

						$list_add[] = $value1['VoucherID'];

						$list_add[] = _d($value1['Transdate']);

						$list_add[] = $value1['Narration'];

						if($value1['TType']== 'D'){

							$OPNBal += $value1['Qty'];

							$TotalDebit += $value1['Qty'];

							$list_add[] = $value1['Qty'];

							$list_add[] = "";

							}else{

							$OPNBal -= $value1['Qty'];

							$TotalCredit += $value1['Qty'];

							$list_add[] = "";

							$list_add[] = $value1['Qty'];

						}

						

						if($OPNBal > 0){

							$DrCr = 'Dr';

							}else{

							$DrCr = 'Cr';

						}

						$list_add[] = abs($OPNBal);

						$list_add[] = $DrCr;

						$writer->writeSheetRow('Sheet1', $list_add);

					}

					

					$list_add = [];

					$list_add[] = '';

					$list_add[] = '';

					$list_add[] = 'Closing Crates';

					$list_add[] = $TotalDebit;

					$list_add[] = $TotalCredit;

					$list_add[] = abs($OPNBal);

					$list_add[] = $DrCr;

					$writer->writeSheetRow('Sheet1', $list_add);

					}else{

					$sr = 1;

					$OCratesSum = 0;

					$DCratesSum = 0;

					$CCratesSum = 0;

					foreach ($body_data['AllAccount'] as $key => $value) {

						$OCrates = 0;

						$DCrates = 0;

						$CCrates = 0;

						// Open Crates

						foreach ($body_data['OpenCrate'] as $key1 => $value1) {

							if(strtoupper($value['AccountID'])== strtoupper($value1['AccountID'])){

								$OCrates = $value1['OQty'];

							}

						}

						// Debit Crates

						foreach ($body_data['Debit'] as $key11 => $value11) {

							if(strtoupper($value['AccountID'])== strtoupper($value11['AccountID'])){

								$DCrates = $value11['OQty'];

							}

						}

						

						// Credit Crates

						foreach ($body_data['Credit'] as $key111 => $value111) {

							if(strtoupper($value['AccountID'])== strtoupper($value111['AccountID'])){

								$CCrates = $value111['OQty'];

							}

						}

						

						

						$BalCrate = $OCrates - $CCrates + $DCrates;

						

						if($BalCrate == '0'){

							

							}else{

							$OCratesSum += $OCrates;

							$DCratesSum += $DCrates;

							$CCratesSum += $CCrates;

							$list_add = [];

							$list_add[] = $value['AccountID'];

							$list_add[] = $value['company'];

							$list_add[] = $value['address'];

							$list_add[] = $OCrates;

							

							$list_add[] = $DCrates;

							$list_add[] = $CCrates;

							$DrCr = '';

							if($BalCrate >0){

								$DrCr = 'Dr';

								}else{

								$DrCr = 'Cr';

							}

							$list_add[] = abs($BalCrate);

							

							$DrCr = '';

							if($BalCrate >0){

								$DrCr = 'Dr';

								}else{

								$DrCr = 'Cr';

							}

							$list_add[] = $DrCr;

							$writer->writeSheetRow('Sheet1', $list_add);

						}

					}

					

					$list_add = [];

					$list_add[] = '';

					

					

					$html .= '<tr>';

					$html .= '<td align="center"></td>';

					$Total = $OCratesSum - $CCratesSum + $DCratesSum;

					$DrCr1 = '';

					if($Total >0){

						$DrCr1 = 'Dr';

						}else{

						$DrCr1 = 'Cr';

					}

					$DrCr11 = '';

					if($OCratesSum >0){

						$DrCr11 = 'Dr';

						}else{

						$DrCr11 = 'Cr';

					}

					$list_add[] = '';

					$list_add[] = 'Total';

					$list_add[] = abs($OCratesSum).' '.$DrCr11;

					$list_add[] = $DCratesSum;

					$list_add[] = $CCratesSum;

					$list_add[] = abs($Total).' '.$DrCr1;

					$writer->writeSheetRow('Sheet1', $list_add);

				}

				

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Crate_legder_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		

		public function ExportPartyWiseDriverWiseCrateLedger()

		{

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				$filterdata = array(

				'from_date' => $this->input->post('from_date'),

				'to_date'  => $this->input->post('to_date'),

				'accountId'  => $this->input->post('accountId'),

				'driver'  => $this->input->post('driver'),

				);

				$from_date = $this->input->post('from_date');

				$to_date = $this->input->post('to_date');

				$accountId = $this->input->post('accountId');

				$DriverID = $this->input->post('driver');

				$account_full_name = $this->input->post('account_full_name');

				$driverName = $this->input->post('driverName');

				$company_details = $this->misc_reports_model->get_company_detail();

				$OpnCrateQty = 0;

				if($accountId != ""){

					// Gate Opening Crate at FY

					$GetOpenCrates = $this->misc_reports_model->GetOpenCrates($filterdata);

					$OpnCrateQty += $GetOpenCrates;

					// Get Transaction data before From date

					$GetOpenTransactionCrates = $this->misc_reports_model->GetOpeningTransactionCrates($filterdata);

					$OpnCrateQty += $GetOpenTransactionCrates;

				}

				$GetTransactionCrates = $this->misc_reports_model->GetTransactionCrates($filterdata);

				

				$writer = new XLSXWriter();

				

				if($DriverID !="" && $accountId == ""){

					$colspan = 8;

					}else{

					$colspan = 10;

				}

				$company_name = array($company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspan);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = $colspan);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				if($accountId != "" && $DriverID !=""){

					$msg = "Crate legder Report For Party Name : ".$account_full_name . "AND  Driver Name : ".$driverName;

					}elseif($accountId != "" && $DriverID == ""){

					$msg = "Crate legder Report For Party Name : ".$account_full_name ;

					}elseif($accountId == "" && $DriverID != ""){

					$msg = "Crate legder Report For Driver Name : ".$driverName;

				}

				$msg2 = "Report From : " .$from_date." To ".$to_date;

				

				$filter = array($msg);

				$filter2 = array($msg2);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = $colspan);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				$writer->writeSheetRow('Sheet1', $filter2);

				

				

				$set_col_tk = [];

				$set_col_tk["Date"] =  'Date';

				$set_col_tk["Particulars"] =  'Particulars';

				$set_col_tk["Vch Type"] =  'Vch Type';

				$set_col_tk["Voucher No."] =  'Voucher No.';

				$set_col_tk["Vehicle No"] =  'Vehicle No';

				$set_col_tk["Debit"] =  'Debit';

				$set_col_tk["Credit"] =  'Credit';

				

				if($accountId !=""){

					$set_col_tk["Balance"] =  'Balance';

					$set_col_tk["DrCr"] =  'DrCr';

					$set_col_tk["Driver Name"] =  'Driver Name';

					}else{

					$set_col_tk["Party Name"] =  'Party Name';

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$TotalDebit = 0;

				$TotalCredit = 0;

				if($accountId !=""){

					$list_add = [];

					$list_add[] = to_sql_date($from_date);

					$list_add[] = "Opening Crates";

					$list_add[] = "";

					$list_add[] = "";

				}

				

				$OPNBal = 0;

				$DrCr = '';

				if($OpnCrateQty > 0){

					$list_add[] = abs($OpnCrateQty);

					$list_add[] = "";

					$DrCr = 'Dr';

					$OPNBal += $OpnCrateQty;

					}else{

					$list_add[] = "";

					$list_add[] = abs($OpnCrateQty);

					$DrCr = 'Cr';

					$OPNBal += $OpnCrateQty;

				}

				

				$list_add[] = abs($body_data['OpenCrate']);

				$list_add[] = $DrCr;

				$list_add[] = "";

				$list_add[] = "";

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				foreach ($GetTransactionCrates as $key1 => $value1) {

					if($value1['Qty'] !== "0" || $value1['Qty'] !== "0.00"){

						$list_add = [];

						$list_add[] = _d($value1['Transdate']);

						$list_add[] = "Crates L";

						$list_add[] = $value1['PassedFrom'];

						$list_add[] = $value1['VoucherID'];

						$list_add[] = $value1['VehicleID'];

						if($value1['TType']== 'D'){

							$OPNBal += $value1['Qty'];

							$TotalDebit += $value1['Qty'];

							$list_add[] = $value1['Qty'];

							$list_add[] = "";

							}else{

							$OPNBal -= $value1['Qty'];

							$TotalCredit += $value1['Qty'];

							$list_add[] = "";

							$list_add[] = $value1['Qty'];

						}

						if($accountId !=""){

							if($OPNBal > 0){

								$DrCr = 'Dr';

								}else{

								$DrCr = 'Cr';

							}

							$list_add[] = abs($OPNBal);

							$list_add[] = $DrCr;

							$list_add[] = $value1['DriverName'];

							}else{

							$list_add[] = $value1['company'];

						}

						$writer->writeSheetRow('Sheet1', $list_add);

					}

				}

				

				$list_add = [];

				$list_add[] = '';

				$list_add[] = '';

				$list_add[] = '';

				$list_add[] = '';

				$list_add[] = "Closing Crates";

				$list_add[] = $TotalDebit;

				$list_add[] = $TotalCredit;

				if($accountId !=""){

					$list_add[] = abs($OPNBal);

					$list_add[] = $DrCr;

					$list_add[] = "";

					}else{

					$list_add[] = "";

				}

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Crate_legder_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		

		public function GetCratesRcvdVehicle()

		{

			$from_date = $this->input->post('from_date');

			$to_date = $this->input->post('to_date');

			$driver = $this->input->post('driver');

			

			$body_data = $this->misc_reports_model->getCratesRcvdVehicle($from_date,$to_date,$driver);

			

			$company_details = $this->misc_reports_model->get_company_detail();

			$table_width = '100%';

			$colspan = 6;

			$html = '';

			$SRCount = 0;

			$VRtnWiseTotal = 0;

			$PartyWiseTotal = 0;

			$ChallanCrates = 0;

			$html .= '<table class="table-striped table-bordered CratesRcvdVehicleTable" id="CratesRcvdVehicleTable" width="'.$table_width.'">';

			$html .= '<thead style="font-size:11px;">';

			$html .= '<tr>';

			$html .= '<th class="sortable">ChallanID</th>';

			$html .= '<th class="sortable">Return ID</th>';

			$html .= '<th class="sortable">VehicleID</th>';

			$html .= '<th class="sortable">Route Name</th>';

			$html .= '<th class="sortable">Driver Name</th>';

			$html .= '<th class="sortable">Sr.</th>';

			$html .= '<th class="sortable">Account Name</th>';

			$html .= '<th class="sortable">Challan Crates</th>';

			$html .= '<th class="sortable">Rcvd Crates</th>';

			$html .= '<th class="sortable">Crate Rcvd Total</th>';

			$html .= '</tr>';

			$html .= '</thead>';

			$html .= '<tbody>';

			foreach ($body_data as $key => $value) {

				$partyCount = count($value["PartyDetails"]);

				if($partyCount >=2){

					$row = 'rowspan ="'.$partyCount.'"';

					}else{

					$row = '';

				}

				if($partyCount >=1){

					$SRCount++;

					$html .= '<tr>';

					$html .= '<td '.$row.'>'.$value["ChallanID"].'</td>';

					$html .= '<td '.$row.'>'.$value["ReturnID"].'</td>';

					$html .= '<td '.$row.'>'.$value["VehicleID"].'</td>';

					$html .= '<td '.$row.'>'.$value["name"].'</td>';

					$html .= '<td '.$row.'>'.$value["firstname"].' '.$value["lastname"].'</td>';

					$html .= '<td align = "center">1</td>';

					$html .= '<td>'.$value["PartyDetails"]['0']['company'].'</td>';

					$html .= '<td '.$row.' align = "right">'.$value["ChlCrates"].'</td>';

					$html .= '<td align = "right">'.$value["PartyDetails"]['0']['Qty'].'</td>';

					$PartyWiseTotal += $value["PartyDetails"]['0']['Qty'];

					$html .= '<td '.$row.' align = "right">'.$value["Crates"].'</td>';

					$VRtnWiseTotal += $value["Crates"];

					$ChallanCrates += $value["ChlCrates"];

					$html .= '</tr>';

					if($partyCount >= 2){

						$j = 1;

						foreach ($value["PartyDetails"] as $key1 => $value1) {

							if($j > 1){

								$SRCount++;

								$html .= '<tr>';

								$html .= '<td align = "center">'.$j.'</td>';

								$html .= '<td>'.$value1["company"].'</td>';

								$html .= '<td align = "right">'.$value1["Qty"].'</td>';

								$PartyWiseTotal += $value1["Qty"];

								$html .= '</tr>';

							}

							$j++;

						}

					}

				}

			}

			

			

			$html .= '</tbody>';

			$html .= '<tfoot>';

			// Footer

			$html .= '</tr>';

			$html .= '<td></td>';

			$html .= '<td>Total Count</td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td align = "center"></td>';

			$html .= '<td>Grand Total</td>';

			$html .= '<td align = "right">'.$ChallanCrates.'</td>';

			$html .= '<td align = "right">'.$PartyWiseTotal.'</td>';

			$html .= '<td align = "right">'.$VRtnWiseTotal.'</td>';

			$html .= '</tr>';

			$html .= '</tfoot>';

			$html .= '</table>';

			

			echo json_encode($html);

			die;

		}

		public function get_cretes_dataNew()

		{ 

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'accountId'  => $this->input->post('accountId'),

			'state_type'  => $this->input->post('state_type'),

			'loc_type'  => $this->input->post('loc_type'),

			'order_by'  => $this->input->post('order_by')

			);

			$from_date = $this->input->post('from_date');

			$to_date = $this->input->post('to_date');

			$accountId = $this->input->post('accountId');

			$state_type = $this->input->post('state_type');

			$loc_type = $this->input->post('loc_type');

			$order_by = $this->input->post('order_by');

			$account_full_name = $this->input->post('account_full_name');

			

			$body_data = $this->misc_reports_model->GetCrateLedger($filterdata);

			/*echo json_encode($body_data['OpenCrate']);

			die;*/

			$company_details = $this->misc_reports_model->get_company_detail();

			$table_width = '100%';

			$colspan = 6;

			$html = '';

			$html .= '<table class="table-striped table-bordered CrateLedger" id="CrateLedger" width="'.$table_width.'">';

			$html .= '<thead style="font-size:11px;">';

			

			$html .= '<tr style="display:none;" class="print_hide">';

			$html .= '<td colspan="'.$colspan.'" style="font-size:18px;font-weight:700;text-align:center;"><b>'.$company_details->company_name.'</b></td>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>'.$company_details->address.'</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>Crate Legder</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			if($accountId != ''){

				

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;"><b>Account:</b>'.$account_full_name.', form date:'.$from_date.', to date:'.$to_date.'</span></td>';

				

				}else{

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;"> Billing Date:'.$from_date.', Vehicle Rtn Date:'.$to_date.', State:'.$state_type.'</span></td>';

			}

			if($accountId !== ''){

				$html .= '<tr>';

				$html .= '<th align="center">VoucherID</th>';

				$html .= '<th align="center">Date</th>';

				$html .= '<th align="center">Narration</th>';

				$html .= '<th align="center">Debit</th>';

				$html .= '<th align="center">Credit</th>';

				$html .= '<th align="center">Balance</th>';

				$html .= '</tr>';

				

				}else{

				$html .= '<tr>';

				$html .= '<th align="center">S.No.</th>';

				$html .= '<th align="center">AccountId</th>';

				$html .= '<th align="center">Account Name</th>';

				$html .= '<th align="center">Address</th>';

				$html .= '<th align="center">OpCrates</th>';

				$html .= '<th align="center">Debit Crates</th>';

				$html .= '<th align="center">Credit Crates</th>';

				$html .= '<th align="center">Balance</th>';

				$html .= '</tr>';

			}

			$html .= '</thead>';

			$html .= '<tbody>';

			$TotalDebit = 0;

			$TotalCredit = 0;

			if($accountId !== ''){

				$html .= '<tr>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="center" style="color:#e93232;font-weight:700;">'.to_sql_date($from_date).'</td>';

				$html .= '<td align="left" style="color:#e93232;font-weight:700;">Opening Crates</td>';

				$OPNBal = 0;

				

				$DrCr = '';

				if($body_data['OpenCrate'] > 0){

					$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$body_data['OpenCrate'].'</td>';

					$html .= '<td align="center"></td>';

					$DrCr = 'Dr';

					$OPNBal += $body_data['OpenCrate'];

					}else{

					$html .= '<td align="center"></td>';

					$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$body_data['OpenCrate'].'</td>';

					$DrCr = 'Cr';

					$OPNBal += $body_data['OpenCrate'];

				}

				

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($body_data['OpenCrate']).' '.$DrCr.'</td>';

				$html .= '</tr>';

				

				foreach ($body_data['Trans'] as $key1 => $value1) {

					if($value1['Qty'] !== "0" || $value1['Qty'] !== "0.00"){

						$html .= '<tr>';

						$html .= '<td align="center">'.$value1['VoucherID'].'</td>';

						$html .= '<td align="center">'._d($value1['Transdate']).'</td>';

						$html .= '<td align="left">'.$value1['Narration'].'</td>';

						if($value1['TType']== 'D'){

							$OPNBal += $value1['Qty'];

							$TotalDebit += $value1['Qty'];

							$html .= '<td align="right">'.$value1['Qty'].'</td>';

							$html .= '<td align="center"></td>';

							}else{

							$OPNBal -= $value1['Qty'];

							$TotalCredit += $value1['Qty'];

							$html .= '<td align="center"></td>';

							$html .= '<td align="right">'.$value1['Qty'].'</td>';

						}

						if($OPNBal > 0){

							$DrCr = 'Dr';

							}else{

							$DrCr = 'Cr';

						}

						$html .= '<td align="right">'.abs($OPNBal).' '.$DrCr.'</td>';

						$html .= '</tr>';

					}

				}

				

				$html .= '<tr>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="left" style="color:#e93232;font-weight:700;">Closing Crates</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$TotalDebit.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$TotalCredit.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($OPNBal).' '.$DrCr.'</td>';

				$html .= '</tr>';

				}else{

				$sr = 1;

				$OCratesSum = 0;

				$DCratesSum = 0;

				$CCratesSum = 0;

				foreach ($body_data['AllAccount'] as $key => $value) {

					$OCrates = 0;

					$DCrates = 0;

					$CCrates = 0;

					// Open Crates

					foreach ($body_data['OpenCrate'] as $key1 => $value1) {

						if(strtoupper($value['AccountID'])== strtoupper($value1['AccountID'])){

							$OCrates = $value1['OQty'];

						}

					}

					// Debit Crates

					foreach ($body_data['Debit'] as $key11 => $value11) {

						if(strtoupper($value['AccountID'])== strtoupper($value11['AccountID'])){

							$DCrates = $value11['OQty'];

						}

					}

					

					// Credit Crates

					foreach ($body_data['Credit'] as $key111 => $value111) {

						if(strtoupper($value['AccountID'])== strtoupper($value111['AccountID'])){

							$CCrates = $value111['OQty'];

						}

					}

					

					

					$BalCrate = $OCrates - $CCrates + $DCrates;

					

					if($BalCrate == '0'){

						

						}else{

						$OCratesSum += $OCrates;

						$DCratesSum += $DCrates;

						$CCratesSum += $CCrates;

						$html .= '<tr>';

						$html .= '<td align="right">'.$sr.'</td>';

						$html .= '<td align="center">'.$value['AccountID'].'</td>';

						$html .= '<td align="left">'.substr($value['company'],0,45).'</td>';

						$html .= '<td align="left">'.substr($value['address'],0,30).'</td>';

						$html .= '<td align="right">'.$OCrates.'</td>';

						$html .= '<td align="right">'.$DCrates.'</td>';

						$html .= '<td align="right">'.$CCrates.'</td>';

						

						$DrCr = '';

						if($BalCrate >0){

							$DrCr = 'Dr';

							}else{

							$DrCr = 'Cr';

						}

						$html .= '<td align="right">'.abs($BalCrate).' '.$DrCr.'</td>';

						$html .= '</tr>';

						$sr++;

					}

				}

				

				$html .= '<tr>';

				$html .= '<td align="center"></td>';

				$Total = $OCratesSum - $CCratesSum + $DCratesSum;

				$DrCr1 = '';

				if($Total >0){

					$DrCr1 = 'Dr';

					}else{

					$DrCr1 = 'Cr';

				}

				$DrCr11 = '';

				if($OCratesSum >0){

					$DrCr11 = 'Dr';

					}else{

					$DrCr11 = 'Cr';

				}

				$html .= '<td align="left" style="color:#e93232;font-weight:700;"></td>';

				$html .= '<td align="left" style="color:#e93232;font-weight:700;">Total</td>';

				$html .= '<td align="left" style="color:#e93232;font-weight:700;"></td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($OCratesSum).' '.$DrCr11.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$DCratesSum.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$CCratesSum.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($Total).' '.$DrCr1.'</td>';

				$html .= '</tr>';

			}

			

			

			$html .= '</tbody>';

			$html .= '</table>';

			echo json_encode($html);

			die;

		}

		public function GetPartyWiseDriverWiseCrateLedger()

		{ 

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'accountId'  => $this->input->post('accountId'),

			'driver'  => $this->input->post('driver'),

			);

			$from_date = $this->input->post('from_date');

			$to_date = $this->input->post('to_date');

			$accountId = $this->input->post('accountId');

			$DriverID = $this->input->post('driver');

			$account_full_name = $this->input->post('account_full_name');

			$driverName = $this->input->post('driverName');

			$company_details = $this->misc_reports_model->get_company_detail();

			$OpnCrateQty = 0;

			if($accountId != ""){

				// Gate Opening Crate at FY

				$GetOpenCrates = $this->misc_reports_model->GetOpenCrates($filterdata);

				$OpnCrateQty += $GetOpenCrates;

				// Get Transaction data before From date

				$GetOpenTransactionCrates = $this->misc_reports_model->GetOpeningTransactionCrates($filterdata);

				$OpnCrateQty += $GetOpenTransactionCrates;

			}

			$GetTransactionCrates = $this->misc_reports_model->GetTransactionCrates($filterdata);

			

			$table_width = '100%';

			$html = '';

			$html .= '<table class="table-striped table-bordered CrateLedger" id="CrateLedger" width="'.$table_width.'">';

			$html .= '<thead style="font-size:11px;">';

			if($DriverID !="" && $accountId == ""){

				$colspan = 7;

				}else{

				$colspan = 9;

			}

			

			$html .= '<tr style="display:none;" class="print_hide">';

			$html .= '<td colspan="'.$colspan.'" style="font-size:18px;font-weight:700;text-align:center;"><b>'.$company_details->company_name.'</b></td>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>'.$company_details->address.'</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>Crate Legder</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			if($accountId != "" && $DriverID !=""){

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center" class="report_for">Party Name : '.$account_full_name.' AND Driver Name : '.$driverName.'</td>';

				}elseif($accountId != "" && $DriverID == ""){

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center" class="report_for">Party Name : '.$account_full_name.'</td>';

				}elseif($accountId == "" && $DriverID != ""){

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center" class="report_for">Driver Name : '.$driverName.'</td>';

			}

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for2" style="font-size:10px;">'.$from_date.' To '.$to_date.'</span></td>';

			$html .= '<tr>';

			

			$html .= '<th class="sortable" align="center">Date</th>';

			$html .= '<th class="sortable" align="center">Particulars</th>';

			$html .= '<th class="sortable" align="center">Vch Type</th>';

			$html .= '<th class="sortable" align="center">Vch No.</th>';

			$html .= '<th class="sortable" align="center">Vehicle No.</th>';

			$html .= '<th class="sortable" align="center">Issue Qty</th>';

			$html .= '<th class="sortable" align="center">Return Qty</th>';

			if($accountId !=""){

				$html .= '<th class="sortable" align="center">Balance Qty</th>';

				$html .= '<th class="sortable" align="center">Driver Name</th>';

				}else{

				$html .= '<th class="sortable" align="center">Party Name</th>';

			}

			

			$html .= '</tr>';

			$TotalDebit = 0;

			$TotalCredit = 0;

			if($accountId !=""){

				$html .= '<tr>';

				$html .= '<td align="center" style="color:#e93232;font-weight:700;">'.to_sql_date($from_date).'</td>';

				$html .= '<td align="left" style="color:#e93232;font-weight:700;">Opening Crates</td>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="center"></td>';

				$OPNBal = 0;

				

				$DrCr = '';

				if($OpnCrateQty > 0){

					$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($OpnCrateQty).'</td>';

					$html .= '<td align="center"></td>';

					$DrCr = 'Dr';

					$OPNBal += $OpnCrateQty;

					}else{

					$html .= '<td align="center"></td>';

					$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($OpnCrateQty).'</td>';

					$DrCr = 'Cr';

					$OPNBal += $OpnCrateQty;

				}

				

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($OpnCrateQty).' '.$DrCr.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;"></td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;"></td>';

				$html .= '</tr>';

			}

			$html .= '</thead>';

			$html .= '<tbody>';

			

			

			foreach ($GetTransactionCrates as $key1 => $value1) {

				if($value1['Qty'] !== "0" || $value1['Qty'] !== "0.00"){

					

					$html .= '<tr>';

					$html .= '<td align="center">'._d($value1['Transdate']).'</td>';

					$html .= '<td align="center">Crates L</td>';

					$html .= '<td align="left">'.$value1['PassedFrom'].'</td>';

					$html .= '<td align="center">'.$value1['VoucherID'].'</td>';

					$html .= '<td align="right">'.$value1['VehicleID'].'</td>';

					if($value1['TType']== 'D'){

						$OPNBal += $value1['Qty'];

						$TotalDebit += $value1['Qty'];

						$html .= '<td align="right">'.$value1['Qty'].'</td>';

						$html .= '<td align="center"></td>';

						}else{

						$OPNBal -= $value1['Qty'];

						$TotalCredit += $value1['Qty'];

						$html .= '<td align="center"></td>';

						$html .= '<td align="right">'.$value1['Qty'].'</td>';

					}

					if($accountId !=""){

						if($OPNBal > 0){

							$DrCr = 'Dr';

							}else{

							$DrCr = 'Cr';

						}

						$html .= '<td align="right">'.abs($OPNBal).' '.$DrCr.'</td>';

						$html .= '<td align="left">'.$value1['DriverName'].'</td>';

						}else{

						$html .= '<td align="left">'.$value1['company'].'</td>';

					}

					$html .= '</tr>';

				}

			}

			

			$html .= '</tbody>';

			$html .= '<tfoot>';

			

			$html .= '<tr>';

			$html .= '<td colspan="5" align="right" style="color:#e93232;font-weight:700;">Closing Crates</td>';

			$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$TotalDebit.'</td>';

			$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.$TotalCredit.'</td>';

			if($accountId !=""){

				$html .= '<td align="right" style="color:#e93232;font-weight:700;">'.abs($OPNBal).' '.$DrCr.'</td>';

				$html .= '<td align="right" style="color:#e93232;font-weight:700;"></td>';

				}else{

				$html .= '<td align="right" style="color:#e93232;font-weight:700;"></td>';

			}

			$html .= '</tr>';

			$html .= '</tfoot>';

			$html .= '</table>';

			echo json_encode($html);

			die;

		}

		public function get_cretes_data()

		{

			

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'accountId'  => $this->input->post('accountId'),

			'state_type'  => $this->input->post('state_type'),

			'loc_type'  => $this->input->post('loc_type'),

			'order_by'  => $this->input->post('order_by')

			);

			$from_date = $this->input->post('from_date');

			$to_date = $this->input->post('to_date');

			$accountId = $this->input->post('accountId');

			$state_type = $this->input->post('state_type');

			$loc_type = $this->input->post('loc_type');

			$order_by = $this->input->post('order_by');

			$account_full_name = $this->input->post('account_full_name');

			

			$body_data = $this->misc_reports_model->get_Crates_for_body_data($filterdata);

			$company_details = $this->misc_reports_model->get_company_detail();

			$table_width = '100%';

			$colspan = 6;

			$html = '';

			$html .= '<table class="table-striped table-bordered CrateLedger" id="CrateLedger" width="'.$table_width.'">';

			$html .= '<thead style="font-size:11px;">';

			

			$html .= '<tr style="display:none;" class="print_hide">';

			$html .= '<td colspan="'.$colspan.'" style="font-size:18px;font-weight:700;text-align:center;"><b>'.$company_details->company_name.'</b></td>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>'.$company_details->address.'</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>Crate Legder</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			if($accountId != ''){

				

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;"><b>Account:</b>'.$account_full_name.', form date:'.$from_date.', to date:'.$to_date.'</span></td>';

				

				}else{

				$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for" style="font-size:10px;"> Billing Date:'.$from_date.', Vehicle Rtn Date:'.$to_date.', State:'.$state_type.'</span></td>';

			}

			if($accountId !== ''){

				$html .= '<tr>';

				$html .= '<th align="center">PassedFrom</th>';

				$html .= '<th align="center">VoucherID</th>';

				$html .= '<th align="center">Date</th>';

				$html .= '<th align="center">Narration</th>';

				$html .= '<th align="center">Debit</th>';

				$html .= '<th align="center">Credit</th>';

				$html .= '<th align="center">Balance</th>';

				$html .= '</tr>';

				

				}else{

				$html .= '<tr>';

				$html .= '<th align="center">S.No.</th>';

				$html .= '<th align="center">AccountId</th>';

				$html .= '<th align="center">Account Name</th>';

				$html .= '<th align="center">Address</th>';

				$html .= '<th align="center">OpCrates</th>';

				$html .= '<th align="center">Debit Crates</th>';

				$html .= '<th align="center">Credit Crates</th>';

				$html .= '<th align="center">Balance</th>';

				/*$html .= '<th align="center">Last Bill Date</th>';

					$html .= '<th align="center">PrevDay Crates</th>';

				$html .= '<th align="center">CurrDay Crates</th>';*/

				$html .= '</tr>';

			}

			$html .= '</thead>';

			if($accountId !== ''){

				$html .= '<tbody>';

				$totalDr = 0;

				$totalCr = 0;

				$opncreates = 0;

				//foreach ($body_data['opn_caret'] as $key1 => $value1) {

				$html .= '<tr>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="center"><span style="color:#e93232;font-weight:700;text-align:right;">'.substr(_d($body_data['opn_caret']->Transdate),0,10).'</span></td>';

				$html .= '<td align="left" ><span style="color:#e93232;font-weight:700;text-align:right;">Opening Crates</span></td>';

				if($body_data['opn_caret']->TType == "C"){

					$html .= '<td align="center"></td>';

					$opncreates1 = $body_data['opn_caret']->Qty.'Cr';

					$totalCr -= $body_data['opn_caret']->Qty;

					$opncreates +=$body_data['opn_caret']->Qty;

					$html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.$body_data['opn_caret']->Qty.'</span></td>';

					}else if($body_data['opn_caret']->TType == "D"){

					$html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.$body_data['opn_caret']->Qty.'</span></td>';

					$totalDr += $body_data['opn_caret']->Qty;

					$opncreates1 = $body_data['opn_caret']->Qty.'Dr';

					$opncreates +=$body_data['opn_caret']->Qty;

					$html .= '<td align="center"></td>';

					}else{

					$opncreates1 = '0Dr';

					$html .= '<td align="center">0</td>';

					$html .= '<td align="center"></td>';

				}

				$html .= '<td align="right" style="color:#e93232;font-weight:700;text-align:right;">'.$opncreates1.'</td>';

				$html .= '</tr>';

				//}

				foreach ($body_data['all'] as $key => $value) {

					$html .= '<tr>';

					$html .= '<td align="center">'.$value["PassedFrom"].'</td>';

					$html .= '<td align="center">'.$value["VoucherID"].'</td>';

					$html .= '<td align="center">'.substr(_d($value["Transdate"]),0,10).'</td>';

					$html .= '<td align="left">'.$value["Narration"].'</td>';

					if($value["TType"] == "C"){

						$html .= '<td align="center"></td>';

						$html .= '<td align="right">'.$value["Qty"].'</td>';

						$totalCr += $value["Qty"];

						$opncreates -= $value["Qty"];

						}else if($value["TType"] == "D"){

						$html .= '<td align="right">'.$value["Qty"].'</td>';

						$totalDr += $value["Qty"];

						$opncreates += $value["Qty"];

						$html .= '<td align="center"></td>';

					}

					if($opncreates >0){

						$crdr = 'Dr';

						}else{

						$crdr = 'Cr';

					}

					$html .= '<td align="right">'.$opncreates.$crdr.'</td>';

					$html .= '</tr>';

				}

				$html .= '</tbody>';

				

				$html .= '<tfoot>';

				$html .= '<tr>';

				$html .= '<td></td>';

				$html .= '<td></td>';

				$html .= '<td></td>';

				$html .= '<td ><span style="color:#e93232;font-weight:700;text-align:right;">Closing Balance</span></td>';

				$html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.$totalDr.'</span></td>';

				$html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.$totalCr.'</span></td>';

				if($opncreates >0){

					$crdr = 'Dr';

					}else{

					$crdr = 'Cr';

				}

				$html .= '<td align="right" ><span style="color:#e93232;font-weight:700;text-align:right;">'.$opncreates.$crdr.'</span></td>';

				$html .= '</tr>';

				$html .= '</tfoot>';

				}else{

				$html .= '<tbody>';

				$i = 1;

				

				$total_c = 0;

				$total_d = 0;

				$total_opn = 0;

				$preDayTT = 0;

				$currDayTT = 0;

				

				foreach ($body_data['all'] as $key => $value) {

					

					$opn_dr = 0;

					$opn_cr = 0;

					$final_opn_in_dr = 0;

					$final_opn_in_cr = 0;

					if(!is_null($value["AccountID"])){

						$html .= '<tr>';

						$html .= '<td align="center">'.$i.'</td>';

						$html .= '<td align="left">'.$value["AccountID"].'</td>';

						$html .= '<td align="left">'.$value["company"].'</td>';

						$html .= '<td align="left">'.$value["address"].'</td>';

						

						foreach ($body_data['opn_debit'] as $key1 => $value_OPNdebit) {

							if($value_OPNdebit['AccountID'] == $value["AccountID"]){

								$opn_dr = $value_OPNdebit["sum_total"];

							}

						}

						foreach ($body_data['opn_credit'] as $key1 => $value_OPNcredit) {

							if($value_OPNcredit['AccountID'] == $value["AccountID"]){

								$opn_cr = $value_OPNcredit["sum_total"];

							}

						}

						$final_opn = $opn_dr - $opn_cr;

						$total_opn+= $final_opn;

						if($final_opn > 0){

							$final_opn_in_dr = $final_opn;

							}else{

							$final_opn_in_cr = $final_opn;

						}

						$html .= '<td align="right">'.$final_opn.'</td>';

						$BalCrate = 0;

						$m = 0;

						foreach ($body_data['debit'] as $key => $value_debit) {

							

							if($value_debit['AccountID'] == $value["AccountID"]){

								$m =1; 

								$new_sum = $value_debit["sum_total"] + $final_opn_in_dr;

								$BalCrate += number_format($new_sum, 2, '.', '');

								$html .= '<td align="right">'.number_format($new_sum, 2, '.', '').'</td>';

								$total_d+= number_format($new_sum, 2, '.', '');

							}

							

						}

						if($m == 0){

							if($final_opn_in_dr == "0"){

								$html .= '<td align="center"></td>';

								}else{

								$total_d+=  number_format($final_opn_in_dr, 2, '.', '');

								$BalCrate += number_format($final_opn_in_dr, 2, '.', '');

								$html .= '<td align="right">'.number_format($final_opn_in_dr, 2, '.', '').'</td>';

							}

						}

						$n = 0;

						foreach ($body_data['credit'] as $key => $value_credit) {

							if($value_credit['AccountID'] == $value["AccountID"]){

								$n = 1;

								$new_sum2 = $value_credit["sum_total"] + $final_opn_in_cr;

								$BalCrate -= number_format($new_sum2, 2, '.', '');

								$html .= '<td align="right">'.number_format($new_sum2, 2, '.', '').'</td>';

								$total_c+= number_format($new_sum2, 2, '.', '');

							}

						}

						if($n == 0){

							if($final_opn_in_cr == "0"){

								$html .= '<td align="center"></td>';

								}else{

								$total_c+=  number_format($final_opn_in_cr, 2, '.', '');

								$html .= '<td align="right">'.number_format($final_opn_in_cr, 2, '.', '').'</td>';

								$BalCrate -= number_format($final_opn_in_cr, 2, '.', '');

							}

						}

						if($BalCrate >0){

							$crdr = 'Dr';

							}else{

							$crdr = 'Cr';

						}

						$html .= '<td align="center">'.number_format($BalCrate, 2, '.', '').$crdr.'</td>';

						// last bill

						/*$n1 = 0;

							foreach ($body_data['lastBill'] as $key2 => $value_lastbill) {

							if($value_lastbill['AccountID'] == $value["AccountID"]){

							$n1 = 1;

							$html .= '<td align="center">'.substr(_d($value_lastbill["lastBill"]),0,10).'</td>';

							}

							}

							if($n1 == 0){

							$html .= '<td align="center"></td>';

						}*/

						

						// PreDay Carets

						/*$n2 = 0;

							foreach ($body_data['preDay'] as $key3 => $value_preDay) {

							if(strtoupper($value_preDay['AccountID']) == strtoupper($value["AccountID"])){

							$n2 = 1;

							$html .= '<td align="right">'.$value_preDay["sum_total"].'</td>';

							$preDayTT+= $value_preDay["sum_total"];

							}

							}

							if($n2 == 0){

							$html .= '<td align="center"></td>';

						}*/

						

						// CurrDay Carets

						/*$n3 = 0;

							foreach ($body_data['currDay'] as $key3 => $value_currDay) {

							if(strtoupper($value_currDay['AccountID']) == strtoupper($value["AccountID"])){

							$n3 = 1;

							$html .= '<td align="right">'.$value_currDay["sum_total"].'</td>';

							$currDayTT+= $value_currDay["sum_total"];

							}

							}

							if($n3 == 0){

							$html .= '<td align="center"></td>';

						}*/

						

						$html .= '</tr>';

						$i++;

					}

					

				}

				

				$html .= '</tbody>';

				$html .= '<tfoot>';

				$html .= '<tr>';

				$html .= '<td align="center"></td>';

				$html .= '<td align="left"></td>';

				$html .= '<td align="center"><b>Total</b></td>';

				

				$html .= '<td align="left"></td>';

				$html .= '<td align="right"><b>'.$total_opn.'</b></td>';

				$html .= '<td align="right"><b>'.$total_d.'</b></td>';

				$html .= '<td align="right" ><b>'.$total_c.'</b></td>';

				$html .= '<td align="right"></td>';

				/*$html .= '<td align="right"><b>'.$preDayTT.'</b></td>';

				$html .= '<td align="right"><b>'.$currDayTT.'</b></td>';*/

				$html .= '</tr>';

				$html .= '</tfoot>';

			}

			$html .= '</table>';

			echo json_encode($html);

			die;

		}

		// End Crate ledger

		public function load_data(){

			

			$data =$this->purchase_model->table_data($this->input->post());

			//   echo $data;

			// die; 

			$states = $this->input->post('states');

			$status = $this->input->post('status');

			$data_state_name  = $this->db->get_where('tblxx_statelist',array('short_name'=>$states))->row_array(); 

			// echo $this->db->last_query();

			if($data_state_name ==''){

				$data_state_name['state_name']  ='';  

			}

			if($status == ''){

				$status  ='';  

			}

			$html ='';

			foreach($data as $value){

				$html.= '<tr>';

				$html.= '<td>'.$value['AccountID'].'</td>';

				$companyy  = $value['company'];

				$isPerson = false;

				

				if ($companyy == '') {

					$companyy  = _l('no_company_view_profile');

					$isPerson = true;

				}

				

				$url = admin_url('purchase/vendor/' . $value['AccountID']);

				

				if ($isPerson && $value['contact_id']) {

					$url .= '?contactid=' . $value['contact_id'];

				}

				$companyy = '<a href="' . $url . '">' . $companyy . '</a>';

				

				$company .= '<div class="row-options">';

				$company .= '<a href="' . $url . '">' . _l('view') . '</a>';

				

				if ($aRow['registration_confirmed'] == 0 && is_admin()) {

					$company .= ' | <a href="' . admin_url('purchase/confirm_registration/' . $aRow['AccountID']) . '" class="text-success bold">' . _l('confirm_registration') . '</a>';

				}

				if (!$isPerson) {

					$company .= ' | <a href="' . admin_url('purchase/vendor/' . $aRow['AccountID'] . '?group=contacts') . '">' . _l('customer_contacts') . '</a>';

				}

				if ($hasPermissionDelete) {

					$company .= ' | <a href="' . admin_url('purchase/delete_vendor/' . $aRow['AccountID']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

				}

				

				$company .= '</div>';

				

				$row_c = $companyy;

				if (has_permission('vendors','','edit')) {

					$vendor_name = '<a href="' . $url . '">' . $value['company'] . '</a>';

					}else{

					$vendor_name = $value['company'];

				}    

				$html.= '<td>'.$vendor_name.'</td>';

				$html.= '<td>'.$value['StationName'].'</td>';

				$city_name = get_city_name($value['city']);

				if($city_name->city_name){

					$city = $city_name->city_name;

					}else{

					$city = $value['city'];

				}

				$row = $city;

				$html.= '<td>'.$row.'</td>';

				

				$html.= '<td>'.$value['state'].'</td>';

				$html.= '<td>'.nl2br($value['address']).'</td>';

				

				if($value['actstatus'] == 1){

					$status = "Active";

					}else{

					$status = "DeActive";

				}

				

				$html.= '<td>'.$status.'</td>';

				$html.= '</tr>';

			}

			// echo $html;

			$data_array =array('html'=>$html,'state'=>$data_state_name,'status'=>$status);

			echo json_encode($data_array);

		}

		

		// Start Crate ledger New

		public function All_Crate_Legder_Report(){

			if (!has_permission_new('all_crate_ledger', '', 'view')) {

				access_denied('access_denied');

			}

			$title = _l('Crate Legder');

			$data['title'] = $title;

			$data['vendors'] = $this->misc_reports_model->get_vendor_data();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['state_list'] = $this->misc_reports_model->get_state_list();

			$this->load->view('admin/misc_reports/All_Crate_Legder_Report', $data);

		}

		

		public function get_all_crates_data_Report()

		{ 

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'accountId'  => $this->input->post('accountId'),

			);

			$from_date = $this->input->post('from_date');

			$to_date = $this->input->post('to_date');

			

			$Clients = $this->misc_reports_model->GetClients($filterdata);

			$CratesData = $this->misc_reports_model->GetCratesByDate($filterdata);

			$DayBeforeTransaction = $this->misc_reports_model->DayBeforeTransactionCrate($filterdata);

			$OpenCrates = $this->misc_reports_model->GetOpeningCrates($filterdata);

			// echo json_encode($body_data);

			// die;

			$company_details = $this->misc_reports_model->get_company_detail();

			$table_width = '100%';

			$colspan = 6;

			$html = '';

			$html .= '<table class="table-striped table-bordered CrateLedger" id="CrateLedger" width="'.$table_width.'">';

			$html .= '<thead style="font-size:11px;">';

			

			$html .= '<tr style="display:none;" class="print_hide">';

			$html .= '<td colspan="'.$colspan.'" style="font-size:18px;font-weight:700;text-align:center;"><b>'.$company_details->company_name.'</b></td>';

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>'.$company_details->address.'</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><b>Crate Legder</b></td>';

			$html .= '</tr>';

			$html .= '<tr style="display:none;" >';

			$html .= '<td colspan="'.$colspan.'" style="font-size:16px;font-weight:600;" align="center"><span class="report_for2" style="font-size:10px;">'.$from_date.' To '.$to_date.'</span></td>';

			

			

			$html .= '<tr>';

			$html .= '<th class="sortable" align="left">Sr.No.</th>';

			$html .= '<th class="sortable" width="30%" align="left">Party Name</th>';

			$html .= '<th class="sortable" align="right">Opening Crate</th>';

			$html .= '<th class="sortable" align="right">Debit Crate</th>';

			$html .= '<th class="sortable" align="right">Credit Crate</th>';

			$html .= '<th class="sortable" align="right">Balance Crate</th>';

			$html .= '</tr>';

			

			

			$html .= '</thead>';

			$html .= '<tbody>';

			$i = 1;

			

			$AllOpenCrate = 0;

			$AllInCrate = 0;

			$AllOutCrate = 0;

			$AllBalanceCrate = 0;

			

			foreach($Clients as $Client){

				

				$InCrate = '';

				$OutCrate = '';

				$BeforeOutCrate = '';

				$BeforeInCrate = '';

				$OpeningCrate = '';

				

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

				

				

				

				

				$BalanceCrate = $OpenCrate + $OutCrate - $InCrate;

				if($BalanceCrate == 0){

					$BalanceCrate = '';

				}

				

				

				$html .= "<tr>";

				$html .= "<td>".$i."</td>";

				$html .= "<td>".$Client['company']."</td>";

				$html .= "<td>".$OpenCrate."</td>";

				$html .= "<td>".$OutCrate."</td>";

				$html .= "<td>".$InCrate."</td>";

				$html .= "<td >".$BalanceCrate."</td>";

				$html .= "</tr>";

				

				$AllOpenCrate += $OpenCrate;

				$AllInCrate += $InCrate;

				$AllOutCrate += $OutCrate;

				$AllBalanceCrate += $BalanceCrate;

				$i++;

			}

			

			

			$html .= '</tbody>';

			$html .= '<tfoot>';

			

			$html .= '<tr>';

			$html .= '<td align="right" colspan="2">Total</td>';

			$html .= '<td align="right">'.$AllOpenCrate.'</td>';

			$html .= '<td align="right">'.$AllOutCrate.'</td>';

			$html .= '<td align="right">'.$AllInCrate.'</td>';

			$html .= '<td align="right">'.$AllBalanceCrate.'</td>';

			$html .= '</tr>';

			$html .= '</tfoot>';

			$html .= '</table>';

			echo json_encode($html);

			die;

		}

		

		public function export_All_crate_legder()

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

				$from_date = $this->input->post('from_date');

				$to_date = $this->input->post('to_date');

				

				$Clients = $this->misc_reports_model->GetClients($filterdata);

				$CratesData = $this->misc_reports_model->GetCratesByDate($filterdata);

				$DayBeforeTransaction = $this->misc_reports_model->DayBeforeTransactionCrate($filterdata);

				$OpenCrates = $this->misc_reports_model->GetOpeningCrates($filterdata);

				

				$selected_company_details = $this->misc_reports_model->get_company_detail();

				

				$writer = new XLSXWriter();

				

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				

				$msg2 = "Report From : " .$from_date." To ".$to_date;

				

				$filter2 = array($msg2);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

				

				$writer->writeSheetRow('Sheet1', $filter2);

				

				// empty row

				$list_add = [];

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				$set_col_tk["Particulars"] =  'Sr.No.';

				$set_col_tk["Party Name"] =  'Party Name';

				

				$set_col_tk["Opening Crate"] =  'Opening Crate';

				$set_col_tk["Debit Crate"] =  'Debit Crate';

				$set_col_tk["Credit Crate"] =  'Credit Crate';

				$set_col_tk["Balance Crate"] =  'Balance Crate';

				

				

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$i = 1;

				

				$AllOpenCrate = 0;

				$AllInCrate = 0;

				$AllOutCrate = 0;

				$AllBalanceCrate = 0;

				

				foreach($Clients as $Client){

					

					$InCrate = '';

					$OutCrate = '';

					$BeforeOutCrate = '';

					$BeforeInCrate = '';

					$OpeningCrate = '';

					

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

					

					

					

					

					$BalanceCrate = $OpenCrate + $OutCrate - $InCrate;

					if($BalanceCrate == 0){

						$BalanceCrate = '';

					}

					

					

					$list_add = [];

					$list_add[] = $i;

					$list_add[] = $Client['company'];

					$list_add[] = $OpenCrate;

					$list_add[] = $OutCrate;

					$list_add[] = $InCrate;

					$list_add[] = $BalanceCrate;

					$writer->writeSheetRow('Sheet1', $list_add);

					

					$AllOpenCrate += $OpenCrate;

					$AllInCrate += $InCrate;

					$AllOutCrate += $OutCrate;

					$AllBalanceCrate += $BalanceCrate;

					$i++;

				}

				

				

				$list_add = [];

				$list_add[] = '';

				$list_add[] = 'Total';

				$list_add[] = $AllOpenCrate;

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

				$filename = 'Crate_legder_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		

		public function Unit_wise_stock_position()

		{

			if (!has_permission_new('unit_stock_position', '', 'view')) {

				access_denied('access_denied');

			}

			$data['main_item_group'] = $this->misc_reports_model->get_main_item_group();

			$data['GodownData'] = $this->misc_reports_model->GetGodownData();

			$data['title'] = "Stock Reports";

			$this->load->view('admin/misc_reports/Unit_wise_stock_position', $data);

		}

		

		public function get_unit_wise_stock_data()

		{

			$filterdata = array(

			'from_date' => $this->input->post('from_date'),

			'to_date'  => $this->input->post('to_date'),

			'GodownID'  => $this->input->post('GodownID')

			);

			$fy = $this->session->userdata('finacial_year');

			$from_date = to_sql_date($this->input->post('from_date'));

			$item_group = $this->input->post('item_group');

			$item_main_group = $this->input->post('item_main_group');

			$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

			$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

			$selected_company = $this->session->userdata('root_company');

			$company_data = $this->misc_reports_model->get_company_detail();

			$AllItemList = $this->misc_reports_model->GetItemList($filterdata,$item_group);

			$StockData = $this->misc_reports_model->GetStockData($filterdata,$item_group);

			$StockOQtyData = $this->misc_reports_model->get_item_open_qty($filterdata,$item_group);

			

			// echo json_encode($StockData);

			// die;

			// echo "<pre>";print_r($StockData);die;

			

			$PurchQtyCasesSumC = 0;

			$InwardQtyCasesSumC = 0;

			$PurchRtnQtyCasesSumC = 0;

			$IssueQtyCasesSumC = 0;

			$PRDCasesSumC = 0;

			$SalesCasesSumC = 0;

			$SalesRtnCasesSumC = 0;

			$AdjCasesSumC = 0;

			$GOCasesSumC = 0;

			$GICasesSumC = 0;

			foreach ($AllItemList as $key => $value) {

				if($value["case_qty"] == "0" || $value["case_qty"] == ""){

					$CaseQty = 1;

					}else{

					$CaseQty = $value["case_qty"];

				}

				$OQTY = 0;

				$PurchQtyC = 0;

				$PurchQtyCasesC = 0;

				

				$InwardQtyC = 0;

				$InwardQtyCasesC = 0;

				

				$PurchRtnQtyC = 0;

				$PurchRtnQtyCasesC = 0;

				

				$IssueQtyC = 0;

				$IssueQtyCasesC = 0;

				

				$PRDQtyC = 0;

				$PRDCasesC = 0;

				

				$SalesQtyC = 0;

				$SalesCasesC = 0;

				

				$SalesRtnQtyC = 0;

				$SalesRtnCasesC = 0;

				

				$AdjQtyC = 0;

				$AdjCasesC = 0;

				

				$GOQtyC = 0;

				$GOCasesC = 0;

				

				$GIQtyC = 0;

				$GICasesC = 0;

				foreach ($StockData as $key1 => $value1) {

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){

						$PurchQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

						$InwardQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

						$PurchRtnQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

						$IssueQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

						$PRDQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

						$SalesQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){

						$SalesRtnQtyC += $value1['BilledQty'];

					}

					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution"  || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){

						$AdjQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

						$GOQtyC += $value1['BilledQty'];

					}

					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

						$GIQtyC += $value1['BilledQty'];

					}

				}

				if($PurchQtyC !== '0'){

					$PurchQtyCasesC = floatval($PurchQtyC);

					$PurchQtyCasesSumC += $PurchQtyCasesC;

				}

				if($InwardQtyC !== '0'){

					$InwardQtyCasesC = floatval($InwardQtyC);

					$InwardQtyCasesSumC += $InwardQtyCasesC;

				}

				

				if($PurchRtnQtyC !== '0'){

					$PurchRtnQtyCasesC = floatval($PurchRtnQtyC);

					$PurchRtnQtyCasesSumC += $PurchRtnQtyCasesC;

				}

				

				if($IssueQtyC !== '0'){

					$IssueQtyCasesC = floatval($IssueQtyC);

					$IssueQtyCasesSumC += $IssueQtyCasesC;

				}

				

				if($PRDQtyC !== '0'){

					$PRDCasesC = floatval($PRDQtyC);

					$PRDCasesSumC += $PRDCasesC;

				}

				

				

				if($SalesQtyC !== '0'){

					$SalesCasesC = floatval($SalesQtyC);

					$SalesCasesSumC += $SalesCasesC;

				}

				

				if($SalesRtnQtyC !== '0'){

					$SalesRtnCasesC = floatval($SalesRtnQtyC);

					$SalesRtnCasesSumC += $SalesRtnCasesC;

				}

				

				if($AdjQtyC !== '0'){

					$AdjCasesC = floatval($AdjQtyC);

					$AdjCasesSumC += $AdjCasesC;

				}

				if($GOQtyC >0){

					$GOCasesC = floatval($GOQtyC);

					$GOCasesSumC += $GOCasesC;

				}

				

				if($GIQtyC >0){

					$GICasesC = floatval($GIQtyC);

					$GICasesSumC += $GICasesC;

				}

			}

			

			/*echo json_encode($AdjCasesSumC);

			die;*/

			$html = '';

			$html .= '<input type="hidden" name="comp_name" id="comp_name" value="'.$company_data->company_name.'">';

			$html .= '<input type="hidden" name="comp_addr" id="comp_addr" value="'.$company_data->address.'">';

			$html .= '<input type="hidden" name="filterdate" id="filterdate" value="Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases">';

			$html .= '<input type="hidden" name="rate_base" id="rate_base" value="Rates based on : State - UP & Dist.Type - SS ">';

			$html .= '<input type="hidden" name="filter_group" id="filter_group" value="Item Group : </b>'.$item_group_name.' ">';

			

			$html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';

			$html .= '<thead style="font-size:11px;">';

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>'.$company_data->company_name.'</b></th>';

			

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>'.$company_data->address.'</b></th>';

			

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>Stock Position of '.$item_maingroup_name->name.'(Stock Value with GST) '.$this->input->post('from_date').' to '.$this->input->post('to_date').' - Stock in Cases</b> </th>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>Rates based on : State - UP & Dist.Type - SS </b> </th>';

			

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th colspan="10"><b>Item Group : </b>'.$item_group_name.'</th>';

			

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			

			$html .= '</tr>';

			

			$html .= '<tr style="display:none;">';

			$html .= '<th></th>';

			$html .= '<th></th>';

			$html .= '<th></th>';

			$html .= '<th></th>';

			$html .= '<th></th>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th align="center"></th>';

			}

			

			$html .= '<th></th>';

			$html .= '<th></th>';

			$html .= '<th></th>';

			$html .= '</tr>';

			$html .= '<tr>';

			$html .= '<th class="sortable" align="left">Sr No</th>';

			$html .= '<th class="sortable" align="left">Item ID</th>';

			$html .= '<th class="sortable" align="left">Item Name</th>';

			$html .= '<th class="sortable" align="center">Pkg</th>';

			$html .= '<th class="sortable" align="center">U</th>';

			$html .= '<th class="sortable" align="center">Open Qty</th>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<th align="center">Purch Qty</th>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<th align="center">Inward</th>';

			}

			

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Purch Rtn</th>';

			}

			

			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Issue Qty</th>';

			}

			

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Production</th>';

			}

			

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Sales Qty</th>';

			}

			

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Sales Rtn</th>';

			}

			

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<th class="sortable" align="center">Adj Qty</th>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<th class="sortable" align="center">GTO Qty</th>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<th class="sortable" align="center">GTI Qty</th>';

			}

			$html .= '<th class="sortable" align="center">Bal Qty</th>';

			$html .= '<th class="sortable" align="center">Rate</th>';

			$html .= '<th class="sortable" align="center">Stk Value</th>';

			$html .= '</tr>';

			$html .= '</thead >';

			/*echo json_encode($AdjCasesSumC);

			die;*/

			$html .= '<tbody id="stock_positionFilter">';

			

			$OQTYCasesSum = 0;

			$PurchQtyCasesSum = 0;

			$InwardQtyCasesSum = 0;

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

				$InwardQty = 0;

				$PurchQtyCases = 0;

				if($value["case_qty"] == "0"){

					$CaseQty = 1;

					}else{

					$CaseQty = $value["case_qty"];

				}

				

				

				$PurchRtnQty = 0;

				$PurchRtnQtyCases = 0;

				

				$InwardRtnQty = 0;

				$InwardRtnQtyCases = 0;

				

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

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

						$InwardQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

						$PurchRtnQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

						$IssueQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

						$PRDQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

						$SalesQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){

						$SalesRtnQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){

						$AdjQty += $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

						$GOQty += $value1['BilledQty'];

						$GOValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

						$GIQty += $value1['BilledQty'];

						$GIValueSum += $value1["SaleRate"] * $value1['BilledQty'];

						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

							$rate = $value1["SaleRate"];

						}

					}

				}

				if($PurchQty !== '0'){

					$PurchQtyCases = floatval($PurchQty);

					$PurchQtyCasesSum += $PurchQtyCases;

				}

				if($InwardQty !== '0'){

					$InwardQtyCases = floatval($InwardQty);

					$InwardQtyCasesSum += $InwardQtyCases;

				}

				

				if($PurchRtnQty !== '0'){

					$PurchRtnQtyCases = floatval($PurchRtnQty);

					$PurchRtnQtyCasesSum += $PurchRtnQtyCases;

				}

				

				if($IssueQty !== '0'){

					$IssueQtyCases = floatval($IssueQty);

					$IssueQtyCasesSum += $IssueQtyCases;

				}

				

				if($PRDQty !== '0'){

					$PRDCases = floatval($PRDQty);

					$PRDCasesSum += $PRDCases;

				}

				if($SalesQty !== '0'){

					$SalesCases = floatval($SalesQty);

					$SalesCasesSum += $SalesCases;

				}

				

				if($SalesRtnQty !== '0'){

					$SalesRtnCases = floatval($SalesRtnQty);

					$SalesRtnCasesSum += $SalesRtnCases;

				}

				

				if($AdjQty !== '0'){

					$AdjCases = floatval($AdjQty);

					$AdjCasesSum += $AdjCases;

				}

				

				if($GOQty >0){

					$GOCases = floatval($GOQty);

					$GOCasesSum += $GOCases;

				}

				

				if($GIQty >0){

					$GICases = floatval($GIQty);

					$GICasesSum += $GICases;

				}

				$from_date_value = '20'.$fy.'-04-01';

				

				if($from_date == $from_date_value){

					$OQTYCases = floatval($value["OQty"]);

					

					}else{

					$OQtySum = 0;

					$OQtySum += floatval($value["OQty"]);

					foreach ($StockOQtyData as $keyOQty => $valueOQty) {

						

						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "P"  && $valueOQty['TType2'] == "Purchase"){

							$OQtySum += $valueOQty['billsum'];

						}

						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "I"  && $valueOQty['TType2'] == "Inward"){

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

					$OQTYCases = floatval($OQtySum);

				}

				

				$OQTYCasesSum += $OQTYCases;

				$BQty =    $OQTYCases +  $PurchQtyCases +  $InwardQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases  - $GOCases + $GICases;

				$BQtySum += $BQty;    

				if(floatval($OQTYCases) == '0.00' && floatval($PurchQtyCases) == "0.00" && floatval($InwardQtyCases) == "0.00" && floatval($PurchRtnQtyCases) == "0.00" && floatval($IssueQtyCases) == "0.00" && floatval($PRDCases) == "0.00" && floatval($SalesCases) == "0.00" && floatval($SalesRtnCases) == "0.00" && floatval($AdjCases) == "0.00" && floatval($GOCases) == "0.00" && floatval($GICases) == "0.00"){

					

					}else{

					$html .= '<tr>';

					$html .= '<td>'.$SrNo.'</td>';

					$html .= '<td>'.$value["item_code"].'</td>';

					$html .= '<td>'.$value["description"].'</td>';

					$html .= '<td align="center">'.$value["case_qty"].'</td>';

					$html .= '<td align="center">'.$value["unit"].'</td>';

					

					$html .= '<td align="right">'.number_format((float)($OQTYCases), 2, '.', ',') .'</td>';

					if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($PurchQtyCases), 2, '.', ',').'</td>';

					}

					if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($InwardQtyCases), 2, '.', ',').'</td>';

					}

					

					if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($PurchRtnQtyCases), 2, '.', ',').'</td>';

					}

					

					if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($IssueQtyCases), 2, '.', ',').'</td>';

					}

					

					if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($PRDCases), 2, '.', ',').'</td>';

					}

					

					if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($SalesCases), 2, '.', ',').'</td>';

					}

					

					if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($SalesRtnCases), 2, '.', ',').'</td>';

					}

					

					if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($AdjCases), 2, '.', ',').'</td>';

					}

					if($GOCasesSumC > 0 || $GOCasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($GOCases), 2, '.', ',').'</td>';

					}

					if($GICasesSumC > 0 || $GICasesSumC < 0){

						$html .= '<td align="right">'.number_format((float)($GICases), 2, '.', ',').'</td>';

					}

					

					/*if($value["assigned_rate"] == null || $value["assigned_rate"] == "" || $value["assigned_rate"] == "0.00"){

						//$rate = 0;

						}else{

						$rate = $value["assigned_rate"];

					}*/

					

					if($value["case_qty"] == '0' || $value["case_qty"] == ''){

						$stockqty = round($BQty) * 1;

						}else{

						$stockqty = round($BQty) ;

					}

					

					if($rate <= 0){

						$rate = $value["StockRate"];

					}

					

					$stockValue = $stockqty * $rate;

					

					$html .= '<td align="right">'.number_format((float)($BQty), 2, '.', ',').'</td>';

					$html .= '<td align="right">'.$rate.'</td>';

					$html .= '<td align="right">'.number_format((float)$stockValue, 2, '.', '').'</td>';

					/*$html .= '<td align="right"></td>';

					$html .= '<td align="right"></td>';*/

					$stockValue_sum = $stockValue_sum + $stockValue;

					$html .= '</tr>';

					$SrNo++;

				}

			}

			$html .= '<tbody>';

			$html .= '<tfoot>';

			$html .= '<tr>';

			$html .= '<td></td>';

			$html .= '<td ><b>Total</b></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td></td>';

			$html .= '<td align="right"><b>'.number_format((float)($OQTYCasesSum), 2, '.', ',').'</b></td>';

			if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($PurchQtyCasesSum), 2, '.', ',').'</b></td>';

			}

			if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($InwardQtyCasesSum), 2, '.', ',').'</b></td>';

			}

			

			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($PurchRtnQtyCasesSum), 2, '.', ',').'</b></td>';

			}    

			

			if($IssueQtyCasesSumC >0 || $IssueQtyCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($IssueQtyCasesSum), 2, '.', ',').'</b></td>';

			}    

			

			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($PRDCasesSum), 2, '.', ',').'</b></td>';

			}    

			

			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($SalesCasesSum), 2, '.', ',').'</b></td>';

			}    

			

			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($SalesRtnCasesSum), 2, '.', ',').'</b></td>';

			}    

			

			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($AdjCasesSum), 2, '.', ',').'</b></td>';

			}

			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($GOCasesSum), 2, '.', ',').'</b></td>';

			}

			if($GICasesSumC > 0 || $GICasesSumC < 0){

				$html .= '<td align="right"><b>'.number_format((float)($GICasesSum), 2, '.', ',').'</b></td>';

			}

			

			$html .= '<td align="right"><b>'.number_format((float)($BQtySum), 2, '.', ',').'</b></td>';

			$html .= '<td align="right"></td>';

			$html .= '<td align="right"><b>'.number_format((float)($stockValue_sum), 2, '.', ',').'</b></td>';

			

			$html .= '</tr>';

			

			// Show Value 

			

			$html .= '</tfoot>';

			$html .= '<table>';

			echo json_encode($html);

			die;

		}

		

		public function export_unit_wise_stock_report(){

			if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				$filterdata = array(

    			'from_date' => $this->input->post('from_date'),

    			'to_date'  => $this->input->post('to_date'),

    			'GodownID'  => $this->input->post('GodownID')

    			);

    			$fy = $this->session->userdata('finacial_year');

    			$from_date = to_sql_date($this->input->post('from_date'));

    			$item_group = $this->input->post('item_group');

    			$item_main_group = $this->input->post('item_main_group');

    			$item_group_name = $this->misc_reports_model->get_item_group_name($item_group);

    			$item_maingroup_name = $this->misc_reports_model->get_mainitem_group($item_main_group);

    			$selected_company = $this->session->userdata('root_company');

    			$company_data = $this->misc_reports_model->get_company_detail();

    			$AllItemList = $this->misc_reports_model->GetItemList($filterdata,$item_group);

    			$StockData = $this->misc_reports_model->GetStockData($filterdata,$item_group);

    			$StockOQtyData = $this->misc_reports_model->get_item_open_qty($filterdata,$item_group);

				$writer = new XLSXWriter();

				

				$company_name = array($company_data->company_name);

				$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				

				$address = $company_data->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				

				$msg = "Stock Report of : ".$item_maingroup_name->name."(Stock Value with GST): " .$this->input->post('from_date')." to ".$this->input->post('to_date')." -  Stock in Cases ";

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

				$j++;

				

				

				$msg1 = "Rates based on : State - UP & Dist.Type - SS";

				$filter1 = array($msg1);

				$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter1);

				$j++;

				

				$msg2 = "Item Group: ".$item_group_name;

				$filter2 = array($msg2);

				$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 12);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter2);

				$j++;

				

				$PurchQtyCasesSumC = 0;

				$InwardQtyCasesSumC = 0;

    			$PurchRtnQtyCasesSumC = 0;

    			$IssueQtyCasesSumC = 0;

    			$PRDCasesSumC = 0;

    			$SalesCasesSumC = 0;

    			$SalesRtnCasesSumC = 0;

    			$AdjCasesSumC = 0;

    			$GOCasesSumC = 0;

    			$GICasesSumC = 0;

				foreach ($AllItemList as $key => $value) {

					

    				if($value["case_qty"] == "0" || $value["case_qty"] == ""){

    					$CaseQty = 1;

    					}else{

    					$CaseQty = $value["case_qty"];

					}

    				$OQTY = 0;

    				$PurchQtyC = 0;

    				$PurchQtyCasesC = 0;

					

    				$InwardQtyC = 0;

    				$InwardQtyCasesC = 0;

    				

    				$PurchRtnQtyC = 0;

    				$PurchRtnQtyCasesC = 0;

    				

    				$IssueQtyC = 0;

    				$IssueQtyCasesC = 0;

    				

    				$PRDQtyC = 0;

    				$PRDCasesC = 0;

    				

    				$SalesQtyC = 0;

    				$SalesCasesC = 0;

    				

    				$SalesRtnQtyC = 0;

    				$SalesRtnCasesC = 0;

    				

    				$AdjQtyC = 0;

    				$AdjCasesC = 0;

    				

    				$GOQtyC = 0;

    				$GOCasesC = 0;

    				

    				$GIQtyC = 0;

    				$GICasesC = 0;

    				foreach ($StockData as $key1 => $value1) {

    					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "P" && $value1["TType2"] == "Purchase"){

    						$PurchQtyC += $value1['BilledQty'];

						}

    					if($value["item_code"] == $value1["ItemID"] && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

    						$InwardQtyC += $value1['BilledQty'];

						}

    					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

    						$PurchRtnQtyC += $value1['BilledQty'];

						}

    					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

    						$IssueQtyC += $value1['BilledQty'];

						}

    					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

    						$PRDQtyC += $value1['BilledQty'];

						}

    					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

    						$SalesQtyC += $value1['BilledQty'];

						}

    					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){

    						$SalesRtnQtyC += $value1['BilledQty'];

						}

    					if(strtoupper($value["item_code"]) == strtoupper($value1["ItemID"]) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution"  || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){

    						$AdjQtyC += $value1['BilledQty'];

						}

    					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

    						$GOQtyC += $value1['BilledQty'];

						}

    					if($value["item_code"] == $value1["ItemID"] && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

    						$GIQtyC += $value1['BilledQty'];

						}

					}

    				if($PurchQtyC !== '0'){

    					$PurchQtyCasesC = floatval($PurchQtyC);

    					$PurchQtyCasesSumC += $PurchQtyCasesC;

					}

    				if($InwardQtyC !== '0'){

    					$InwardQtyCasesC = floatval($InwardQtyC);

    					$InwardQtyCasesSumC += $InwardQtyCasesC;

					}

    				

    				if($PurchRtnQtyC !== '0'){

    					$PurchRtnQtyCasesC = floatval($PurchRtnQtyC);

    					$PurchRtnQtyCasesSumC += $PurchRtnQtyCasesC;

					}

    				

    				if($IssueQtyC !== '0'){

    					$IssueQtyCasesC = floatval($IssueQtyC);

    					$IssueQtyCasesSumC += $IssueQtyCasesC;

					}

    				

    				if($PRDQtyC !== '0'){

    					$PRDCasesC = floatval($PRDQtyC);

    					$PRDCasesSumC += $PRDCasesC;

					}

    				

    				

    				if($SalesQtyC !== '0'){

    					$SalesCasesC = floatval($SalesQtyC);

    					$SalesCasesSumC += $SalesCasesC;

					}

    				

    				if($SalesRtnQtyC !== '0'){

    					$SalesRtnCasesC = floatval($SalesRtnQtyC);

    					$SalesRtnCasesSumC += $SalesRtnCasesC;

					}

    				

    				if($AdjQtyC !== '0'){

    					$AdjCasesC = floatval($AdjQtyC);

    					$AdjCasesSumC += $AdjCasesC;

					}

    				if($GOQtyC >0){

    					$GOCasesC = floatval($GOQtyC);

    					$GOCasesSumC += $GOCasesC;

					}

    				

    				if($GIQtyC >0){

    					$GICasesC = floatval($GIQtyC);

    					$GICasesSumC += $GICasesC;

					}

				}

				

				$list_add = [];

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = "";

				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

    				$list_add[] = "";

				}

				if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($GOCasesSumC > 0 || $GOCasesSumC < 0){

    				$list_add[] = "";

				}

    			if($GICasesSumC > 0 || $GICasesSumC < 0){

    				$list_add[] = "";

				}

    			

    			

				$list_add[] = "";

				$list_add[] = "";

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				if($accountId !== ''){

					$set_col_tk["ItemID"] =  'Item ID';

					$set_col_tk["ItemName"] =  'Item Name';

					$set_col_tk["Pkg"] =  'Pkg';

					$set_col_tk["U"] =  'U';

					$set_col_tk["OpenQty"] =  'Open Qty';

					if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

						$set_col_tk["PurchQty"] =  'Purch Qty';

					}

					if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

						$set_col_tk["Inward"] =  'Inward';

					}

					if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

						$set_col_tk["PurchRtn"] =  'Purch Rtn';

					}

					if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

						$set_col_tk["IssueQty"] =  'Issue Qty';

					}

					if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

						$set_col_tk["Production"] =  'Production';

					}

					if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

						$set_col_tk["SalesQty"] =  'Sales Qty';

					}

					if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

						$set_col_tk["SalesRtn"] =  'Sales Rtn';

					}

					if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

						$set_col_tk["AdjQty"] =  'Adj Qty';

					}

					if($GOCasesSumC > 0 || $GOCasesSumC < 0){

						$set_col_tk["GTOQty"] =  'GTO Qty';

					}

					if($GICasesSumC > 0 || $GICasesSumC < 0){

						$set_col_tk["GTIQty"] =  'GTI Qty';

					}

					

					$set_col_tk["Bal.Qty"] =  'Bal Qty';

					$set_col_tk["Rate"] =  'Rate';

					$set_col_tk["StkValue"] =  'Stk Value';

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$OQTYCasesSum = 0;

    			$PurchQtyCasesSum = 0;

    			$InwardQtyCasesSum = 0;

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

				foreach ($AllItemList as $key => $value) {

					$rate = 0;

    				$OQTY = 0;

    				$OQTYCases = 0;

    				$PurchQty = 0;

    				$PurchQtyCases = 0;

    				$InwardQty = 0;

    				$InwardQtyCases = 0;

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

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "I" && $value1["TType2"] == "Inward"){

    						$InwardQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "N" && $value1["TType2"] == "PurchaseReturn"){

    						$PurchRtnQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "A" && $value1["TType2"] == "Issue"){

    						$IssueQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "B" && $value1["TType2"] == "Production"){

    						$PRDQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && $value1["TType"] == "O" && $value1["TType2"] == "Order"){

    						$SalesQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "R" && $value1["TType2"] == "Fresh")){

    						$SalesRtnQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "X" && $value1["TType2"] == "Free Distribution" || $value1["TType"] == "X" && $value1["TType2"] == "Promotional Activity" || $value1["TType"] == "X" && $value1["TType2"] == "Stock Adjustment" || $value1["TType"] == "X" && $value1["TType2"] == "IssueAgainstReturn")){

    						$AdjQty += $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "Out")){

    						$GOQty += $value1['BilledQty'];

    						$GOValueSum += $value1["SaleRate"] * $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

    					if(trim(strtoupper($value["item_code"])) == trim(strtoupper($value1["ItemID"])) && ($value1["TType"] == "T" && $value1["TType2"] == "In")){

    						$GIQty += $value1['BilledQty'];

    						$GIValueSum += $value1["SaleRate"] * $value1['BilledQty'];

    						if($value1["SaleRate"] != '' && $value1["SaleRate"] != null && $value1["SaleRate"] > 0){

    							$rate = $value1["SaleRate"];

							}

						}

					}

    				if($PurchQty !== '0'){

    					$PurchQtyCases = floatval($PurchQty);

    					$PurchQtyCasesSum += $PurchQtyCases;

					}

    				if($InwardQty !== '0'){

    					$InwardQtyCases = floatval($InwardQty);

    					$InwardQtyCasesSum += $InwardQtyCases;

					}

    				

    				if($PurchRtnQty !== '0'){

    					$PurchRtnQtyCases = floatval($PurchRtnQty);

    					$PurchRtnQtyCasesSum += $PurchRtnQtyCases;

					}

    				

    				if($IssueQty !== '0'){

    					$IssueQtyCases = floatval($IssueQty);

    					$IssueQtyCasesSum += $IssueQtyCases;

					}

    				

    				if($PRDQty !== '0'){

    					$PRDCases = floatval($PRDQty);

    					$PRDCasesSum += $PRDCases;

					}

    				if($SalesQty !== '0'){

    					$SalesCases = floatval($SalesQty);

    					$SalesCasesSum += $SalesCases;

					}

    				

    				if($SalesRtnQty !== '0'){

    					$SalesRtnCases = floatval($SalesRtnQty);

    					$SalesRtnCasesSum += $SalesRtnCases;

					}

    				

    				if($AdjQty !== '0'){

    					$AdjCases = floatval($AdjQty);

    					$AdjCasesSum += $AdjCases;

					}

    				

    				if($GOQty >0){

    					$GOCases = floatval($GOQty);

    					$GOCasesSum += $GOCases;

					}

    				

    				if($GIQty >0){

    					$GICases = floatval($GIQty);

    					$GICasesSum += $GICases;

					}

    				$from_date_value = '20'.$fy.'-04-01';

    				

    				if($from_date == $from_date_value){

    					$OQTYCases = floatval($value["OQty"]);

    					

						}else{

    					$OQtySum = 0;

    					$OQtySum += floatval($value["OQty"]);

    					foreach ($StockOQtyData as $keyOQty => $valueOQty) {

    						

    						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "P"  && $valueOQty['TType2'] == "Purchase"){

    							$OQtySum += $valueOQty['billsum'];

							}

    						if(trim(strtoupper($valueOQty['ItemID'])) == trim(strtoupper($value["item_code"])) && $valueOQty['TType'] == "I"  && $valueOQty['TType2'] == "Inward"){

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

    					$OQTYCases = floatval($OQtySum);

					}

    				

    				$OQTYCasesSum += $OQTYCases;

    				$BQty =    $OQTYCases +  $PurchQtyCases +  $InwardQtyCases - $PurchRtnQtyCases - $IssueQtyCases + $PRDCases - $SalesCases + $SalesRtnCases - $AdjCases  - $GOCases + $GICases;

    				$BQtySum += $BQty;    

    				if(floatval($OQTYCases) == '0.00' && floatval($PurchQtyCases) == "0.00" && floatval($InwardQtyCases) == "0.00" && floatval($PurchRtnQtyCases) == "0.00" && floatval($IssueQtyCases) == "0.00" && floatval($PRDCases) == "0.00" && floatval($SalesCases) == "0.00" && floatval($SalesRtnCases) == "0.00" && floatval($AdjCases) == "0.00" && floatval($GOCases) == "0.00" && floatval($GICases) == "0.00"){

    					

						}else{

						$list_add = [];

						$list_add[] = $value["item_code"];

						$list_add[] = $value["description"];

						$list_add[] = $value["case_qty"];

						$list_add[] = $value["unit"];

						$list_add[] = round($OQTYCases,2);

						if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

							$list_add[] = round((float)($PurchQtyCases), 2);

						}

						if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

							$list_add[] = round((float)($InwardQtyCases), 2);

						}

						

						if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

							$list_add[] = round((float)($PurchRtnQtyCases), 2);

						}

						

						if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

							$list_add[] = round((float)($IssueQtyCases), 2);

						}

						

						if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

							$list_add[] = round((float)($PRDCases), 2);

						}

						

						if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

							$list_add[] = round((float)($SalesCases), 2);

						}

						

						if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

							$list_add[] = round((float)($SalesRtnCases), 2);

						}

						

						if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

							$list_add[] = round((float)($AdjCases), 2);

						}

						if($GOCasesSumC > 0 || $GOCasesSumC < 0){

							$list_add[] = round((float)($GOCases), 2);

						}

						if($GICasesSumC > 0 || $GICasesSumC < 0){

							$list_add[] = round((float)($GICases), 2);

						}

						

						/*if($value["assigned_rate"] == null || $value["assigned_rate"] == "" || $value["assigned_rate"] == "0.00"){

    						//$rate = 0;

							}else{

    						$rate = $value["assigned_rate"];

						}*/

    					

    					if($value["case_qty"] == '0' || $value["case_qty"] == ''){

    						$stockqty = round($BQty) * 1;

							}else{

    						$stockqty = round($BQty) ;

						}

    					

    					$stockValue = $stockqty * $rate;

						

						$list_add[] = round((float)($BQty), 2); 

						$list_add[] = round((float)($rate), 2);

						$list_add[] = round((float)($stockValue), 2);

						$stockValue_sum = $stockValue_sum + $stockValue;

						$writer->writeSheetRow('Sheet1', $list_add);

					}  

				}

				

				$list_add = [];

				$list_add[] = "";

				$list_add[] = "Total";

				$list_add[] = "";

				$list_add[] = "";

				$list_add[] = round((float)($OQTYCasesSum), 2);

				

				if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){

					$list_add[] = round((float)($PurchQtyCasesSum), 2); 

				}

				if($InwardQtyCasesSumC > 0 || $InwardQtyCasesSumC < 0){

					$list_add[] = round((float)($InwardQtyCasesSum), 2); 

				}

				

				if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){

					$list_add[] = round((float)($PurchRtnQtyCasesSum), 2); 

				}    

				

				if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){

					$list_add[] = round((float)($IssueQtyCasesSum), 2); 

				}    

				

				if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){

					$list_add[] = round((float)($PRDCasesSum), 2); 

				}    

				

				if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){

					$list_add[] = round((float)($SalesCasesSum), 2); 

				}    

				

				if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){

					$list_add[] = round((float)($SalesRtnCasesSum), 2); 

				}    

				

				if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){

					$list_add[] = round((float)($AdjCasesSum), 2); 

				}

				

				if($GOCasesSumC > 0 || $GOCasesSumC < 0){

					$list_add[] = round((float)($GOCasesSum), 2); 

				}

				if($GICasesSumC > 0 || $GICasesSumC < 0){

					$list_add[] = round((float)($GICasesSum), 2); 

				}

				

				$list_add[] = round((int) $BQtySum, 2); 

				$list_add[] = ""; 

				$list_add[] = round((float)($stockValue_sum), 2);; 

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'Stock_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

				'site_url'          => site_url(),

				'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

		}

		public function ItemWiseDistWiseRateList()

		{

			if (!has_permission_new('ItemWiseDistWiseRateList', '', 'view')) {

				access_denied('access_denied');

			}

			$this->load->model('clients_model');

			$this->load->model('rate_master_model');

			$data['main_item_group'] = $this->misc_reports_model->get_main_item_group();

			$data['states'] = $this->rate_master_model->get_state();

			$data['groups'] = $this->clients_model->get_groups();

			$data['company_detail'] = $this->misc_reports_model->get_company_detail();

			$data['title'] = "Rate list report";

			$this->load->view('admin/misc_reports/ItemWiseDistWiseRateList', $data);

		}

		public function get_itemwise_distwise_rate_report()

		{

			if (!has_permission_new('ItemWiseDistWiseRateList', '', 'view')) {

				access_denied('access_denied');

			}

			

			$item_group = $this->input->post('item_group');

			$item_data = $this->input->post('item_data');

			$states = $this->input->post('states');

			$distributor_ids = $this->input->post('distributor_id');

			

			$data = $this->misc_reports_model->get_distwise_rate_table_data($this->input->post());

			$company_data = $this->misc_reports_model->get_company_detail();

			$itemslist = $this->misc_reports_model->GetItemsBySubgroup($item_group);

			$Distlist = $this->misc_reports_model->GetDistTypeList($distributor_ids);

			

			//  Step 1: Build lookup array for fast access

			$rate_map = [];

			foreach ($data as $rate) {

				$rate_map[$rate['item_id']][$rate['distributor_id']] = $rate['assigned_rate'];

			}

			

			//  Step 2: Generate table HTML

			$html = '';

			$html .= '<table class="table-striped table-bordered stock_position" id="stock_position" width="100%">';

			$html .= '<thead style="font-size:11px;">';

			$html .= '<tr class="header">';

			$html .= '<th class="sortablePop col-srno fixed-header" align="center">Sr.</th>';

			$html .= '<th class="sortablePop col-ItemID fixed-header" align="center">ItemID</th>';

			$html .= '<th class="sortablePop col-ItemName fixed-header">Item Name</th>';

			$html .= '<th class="sortablePop col-MRP fixed-header">MRP</th>';

			$html .= '<th class="sortablePop col-CreateQty fixed-header" align="center">CreateQty</th>';

			$html .= '<th class="sortablePop col-CaseQty fixed-header" align="center">CaseQty</th>';

			$html .= '<th class="sortablePop col-GST fixed-header" align="center">GST%</th>';

			

			foreach ($Distlist as $dist) {

				$html .= '<th class="sortablePop" align="center">' . $dist['name'] . '</th>';

			}

			

			$html .= '</tr>';

			$html .= '</thead>';

			$html .= '<tbody>';

			

			$i = 1;

			foreach ($itemslist as $value) {

				$item_code = $value['item_code'];

				$html .= '<tr>';

				$html .= '<td class = "col-srno" align="center">' . $i . '</td>';

				$html .= '<td class = "col-ItemID" align="center">' . strtoupper($item_code) . '</td>';

				$html .= '<td class = "col-ItemName">' . $value['description'] . '</td>';

				$html .= '<td class = "col-MRP" >' . $value['mrp'] . '</td>';

				$html .= '<td class = "col-CreateQty" align="right">' . $value['crate_qty'] . '</td>';

				$html .= '<td class = "col-CaseQty" align="right">' . $value['case_qty'] . '</td>';

				$html .= '<td class = "col-GST" align="right">' . $value['taxrate'] . '</td>';

				

				foreach ($Distlist as $dist) {

					$dist_id = $dist['id'];

					$rate = isset($rate_map[$item_code][$dist_id]) ? $rate_map[$item_code][$dist_id] : '';

					$html .= '<td align="right">' . $rate . '</td>';

				}

				$html .= '</tr>';

				$i++;

			}

			

			$html .= '</tbody>';

			$html .= '</table>';

			

			echo json_encode($html);

		}

		

		

		public function export_itemwise_distwise_rate_list(){

         	if(!class_exists('XLSXReader_fin')){

				require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXReader/XLSXReader.php');

			}

			require_once(module_dir_path(TIMESHEETS_MODULE_NAME).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

			

			if($this->input->post()){

				

				

				$item_group = $this->input->post('item_group');

				$item_data = $this->input->post('item_data');

				$states = $this->input->post('states');

				$distributor_ids = $this->input->post('distributor_id');

				$data = $this->misc_reports_model->get_distwise_rate_table_data($this->input->post());

				$company_data = $this->misc_reports_model->get_company_detail();

				$itemslist = $this->misc_reports_model->GetItemsBySubgroup($item_group);

				$Distlist = $this->misc_reports_model->GetDistTypeList($distributor_ids);

				

				$selected_company_details    = $this->misc_reports_model->get_company_detail();

				// print_r($data);die;

				$writer = new XLSXWriter();

				$j=0;

				$company_name = array($selected_company_details->company_name);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_name);

				$j++;

				$address = $selected_company_details->address;

				$company_addr = array($address,);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 15);  //merge cells

				$writer->writeSheetRow('Sheet1', $company_addr);

				$j++;

				

				$msg = "Item Wise Distributor Wise Rate List Report  State: ".$states;

				$filter = array($msg);

				$writer->markMergedCell('Sheet1', $start_row = $j, $start_col = 0, $end_row = $j, $end_col = 16);  //merge cells

				$writer->writeSheetRow('Sheet1', $filter);

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

				$list_add[] = "";

				$list_add[] = "";

				

				$writer->writeSheetRow('Sheet1', $list_add);

				

				

				$set_col_tk = [];

				$set_col_tk["Sr. No"] =  'Sr. No';

				$set_col_tk["Item_Id"] =  'Item Id';

				$set_col_tk["Item_Name"] =  'Item Name';

				$set_col_tk["MRP"] =  'MRP';

				$set_col_tk["CreateQty"] =  'CreateQty';

				$set_col_tk["CaseQty"] =  'CaseQty';

				$set_col_tk["GST%"] =  'GST%';

				

				foreach($Distlist as $dist){

					$set_col_tk[$dist['name']] =  $dist['name'];

				}

				$writer_header = $set_col_tk;

				$writer->writeSheetRow('Sheet1', $writer_header);

				

				$rate_map = [];

				foreach ($data as $rate) {

					$rate_map[$rate['item_id']][$rate['distributor_id']] = $rate['assigned_rate'];

				}

				

				$i = 1; 

				foreach($itemslist as $value){

					

					$item_code = $value['item_code'];

					$list_add = [];

					$list_add[] = $i;

					$list_add[] = strtoupper($value["item_code"]);

					$list_add[] = $value["description"];

					$list_add[] = $value["mrp"];

					$list_add[] = $value["crate_qty"];

					$list_add[] = $value["case_qty"];

					$list_add[] = $value["taxrate"];

					foreach($Distlist as $dist){

						$dist_id = $dist['id'];

						$rate = isset($rate_map[$item_code][$dist_id]) ? $rate_map[$item_code][$dist_id] : '';

						$list_add[] = $rate;

					}

					$writer->writeSheetRow('Sheet1', $list_add);

					$i++; 

				}

				

				

				$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');

				foreach($files as $file){

					if(is_file($file)) {

						unlink($file); 

					}

				}

				$filename = 'DistWise_Rate_list_Report.xlsx';

				$writer->writeToFile(str_replace($filename, TIMESHEETS_PATH_EXPORT_FILE.$filename, $filename));

				echo json_encode([

    			'site_url'          => site_url(),

    			'filename'          => TIMESHEETS_PATH_EXPORT_FILE.$filename,

				]);

				die;

			}

			

		}

		

	}																																				