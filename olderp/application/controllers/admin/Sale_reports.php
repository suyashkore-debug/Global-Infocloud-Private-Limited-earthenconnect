<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sale_reports extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('sale_reports_model');
        $this->load->model('challan_model');
    }

    /* Get all invoices in case user go on index page */
    public function index($id = '')
    {
        //$this->list_orders($id);
        $this->daily_sale();
    }
    
    /* SaleVsSaleRtn report page */
    public function SaleVsSaleRtn()
    {
        if (!has_permission_new('saleVsSaleRtn', '', 'view')) {
            access_denied('orders');
        }

        close_setup_menu();
        $data['title']                = "SaleVsSaleRtn Report";
        $data['company_detail'] = $this->sale_reports_model->get_company_detail();
        $data['Accountlist'] =  $this->sale_reports_model->AccountList_table();
        /*print_r($data['Accountlist']);
        die;*/
        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/sale_reports/SaleVsSaleRtn', $data);
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
        $html .= '<th align="center">Date</th>';
        $html .= '<th align="center">OpenQty</th>';
        
        if($PurchQtyCasesSumC > 0 || $PurchQtyCasesSumC < 0){
            $html .= '<th align="center">PurchQty</th>';
        }      
        if($PurchRtnQtyCasesSumC > 0 || $PurchRtnQtyCasesSumC < 0){
                $html .= '<th align="center">PurchRtn</th>';
        } 
        if($IssueQtyCasesSumC > 0 || $IssueQtyCasesSumC < 0){
            $html .= '<th align="center">IssueQty</th>';
        }    
        if($PRDCasesSumC > 0 || $PRDCasesSumC < 0){
            $html .= '<th align="center">Production</th>';
        }     
        if($SalesCasesSumC > 0 || $SalesCasesSumC < 0){
            $html .= '<th align="center">SalesQty</th>';
        }     
        if($SalesRtnCasesSumC > 0 || $SalesRtnCasesSumC < 0){
            $html .= '<th align="center">SalesRtn</th>';
        }      
        if($AdjCasesSumC > 0 || $AdjCasesSumC < 0){
            $html .= '<th align="center">AdjQty</th>';
        }
        if($GTOCasesSumC > 0 || $GTOCasesSumC < 0){
            $html .= '<th align="center">GTOQty</th>';
        }
        if($GTICasesSumC > 0 || $GTICasesSumC < 0){
            $html .= '<th align="center">GTIQty</th>';
        }
        
        $html .= '<th align="center">Bal.Qty</th>';
        $html .= '<th align="center">Rate</th>';
        $html .= '<th align="center">StkValue</th>';
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
        
        $html .= '</tbody>';
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
        $body_data = $this->sale_reports_model->GetPartyItemWiseBodyData($filterdata);
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
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i = 1;
        $BilledQtySum = 0;
        $BilledCratesSum = 0;
        $BilledCasaseSum = 0;
        $ItemValueSum = 0;
        foreach ($body_data as $key => $value) {
            if($value["BilledQty"] == '0.000' || $value["BilledQty"] == ''){
                
            }else{
                $html .= '<tr>';
                $html .= '<td align="center">'.$i.'</td>';
                $html .= '<td align="center">'.$value["ItemID"].'</td>';
                $html .= '<td align="left">'.$value["description"].'</td>';
                $html .= '<td align="center">'.$value["SuppliedIn"].'</td>';
                $html .= '<td align="center">'.$value["CaseQty"].'</td>';
                $html .= '<td align="right">'.$value["SaleRate"].'</td>';
                $html .= '<td align="right">'. number_format($value["BilledQty"], 2, ".", "").'</td>';
                $BilledQtySum += $value["BilledQty"];
                if($value["SuppliedIn"] == "CS"){
                    $Cases = $value["BilledQty"] / $value["CaseQty"];
                    $BilledCasaseSum += $Cases;
                    $Crates = '';
                }else{
                    $Crates = $value["BilledQty"] / $value["CaseQty"];
                    $BilledCratesSum += $Crates;
                    $Cases = '';
                }
                $html .= '<td align="right">'.number_format($Crates, 2, ".", "").'</td>';
                $html .= '<td align="right">'.number_format($Cases, 2, ".", "").'</td>';
                $html .= '<td align="right">'. number_format($value["ItemValue"], 2, ".", "").'</td>';
                $ItemValueSum += $value["ItemValue"];
                $html .= '</tr>';
                $i++;
            }
        }
        
        // Footer Data
        $html .= '<tr>';
        $html .= '<td align="center"></td>';
        $html .= '<td align="center"></td>';
        $html .= '<td align="center">Total</td>';
        $html .= '<td align="center"></td>';
        $html .= '<td align="center"></td>';
        $html .= '<td align="center"></td>';
        $html .= '<td align="right">'. number_format($BilledQtySum, 2, ".", "").'</td>';
        $html .= '<td align="right">'. number_format($BilledCratesSum, 2, ".", "").'</td>';
        $html .= '<td align="right">'. number_format($BilledCasaseSum, 2, ".", "").'</td>';
        $html .= '<td align="right">'. number_format($ItemValueSum, 2, ".", "").'</td>';
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
        $AccountName = $this->input->post('AccountName');
        $AccountAddress = $this->input->post('AccountAddress');
        $AccountAddress2 = $this->input->post('AccountAddress2');
        $station = $this->input->post('station');
        $StateName = $this->input->post('StateName');
        $TransType = $this->input->post('TransType');
        $body_data = $this->sale_reports_model->GetPartyItemWiseBodyData($filterdata);
        $company_detail = $this->sale_reports_model->get_company_detail();
        /*echo json_encode($body_data);
        die;*/
        $AccountDetails = 'Account Name : '. $AccountName.' Address : '.$AccountAddress.' '.$AccountAddress2;
        $OtherDetails = 'Station : '.$station .' State : '. $StateName;
        $colspan = '9';
        if($TransType == '1'){
            $TransTypeName = 'Sale';
        }else if($TransType == '2'){
            $TransTypeName = 'Fresh';
        }else if($TransType == '3'){
            $TransTypeName = 'Damage';
        }else if($TransType == '4'){
            $TransTypeName = 'NetSale';
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
    	
    	$msg2 = $AccountDetails;
    	$filter2 = array($msg2);
    	$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = $colspan);  //merge cells
    	$writer->writeSheetRow('Sheet1', $filter2);
    	
    	$msg3 = $OtherDetails;
    	$filter3 = array($msg3);
    	$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = $colspan);  //merge cells
    	$writer->writeSheetRow('Sheet1', $filter3);
    
        $msg4 = 'Trans Type : '.$TransTypeName;
    	$filter4 = array($msg4);
    	$writer->markMergedCell('Sheet1', $start_row = 5, $start_col = 0, $end_row = 5, $end_col = $colspan);  //merge cells
    	$writer->writeSheetRow('Sheet1', $filter4);
    	
    	$set_col_tk = [];
    	
    	$set_col_tk["ItemID"] = 'ItemID';
    	$set_col_tk["ItemName"] = 'ItemName';
    	$set_col_tk["PackIn"] = 'PackIn';
    	$set_col_tk["Pack"] = 'Pack';
    	$set_col_tk["Rate"] = 'Rate';
    	$set_col_tk["Billed Qty"] = 'Billed Qty';
    	$set_col_tk["Billed Crates"] = 'Billed Crates';
    	$set_col_tk["Billed Cases"] = 'Billed Cases';
    	$set_col_tk["Item value"] = 'Item value';
    	
    	$writer_header = $set_col_tk;
    	$writer->writeSheetRow('Sheet1', $writer_header);
    	
    	$i = 1;
        $BilledQtySum = 0;
        $BilledCratesSum = 0;
        $BilledCasaseSum = 0;
        $ItemValueSum = 0;
        foreach ($body_data as $key => $value) {
            if($value["BilledQty"] == '0.000' || $value["BilledQty"] == ''){
                
            }else{
                $list_add = [];
                $list_add[] = $value["ItemID"];
                $list_add[] = $value["description"];
                $list_add[] = $value["SuppliedIn"];
                $list_add[] = $value["CaseQty"];
                $list_add[] = $value["SaleRate"];
                $list_add[] = number_format($value["BilledQty"], 2, ".", "");
                $BilledQtySum += $value["BilledQty"];
                if($value["SuppliedIn"] == "CS"){
                    $Cases = $value["BilledQty"] / $value["CaseQty"];
                    $BilledCasaseSum += $Cases;
                    $Crates = '';
                }else{
                    $Crates = $value["BilledQty"] / $value["CaseQty"];
                    $BilledCratesSum += $Crates;
                    $Cases = '';
                }
                $list_add[] = number_format($Crates, 2, ".", "");
                $list_add[] = number_format($Cases, 2, ".", "");
                $list_add[] = number_format($value["ItemValue"], 2, ".", "");
                $ItemValueSum += $value["ItemValue"];
                $i++;
                $writer->writeSheetRow('Sheet1', $list_add);
            }
        }
        
        
        // Footer Data
        $list_add = [];
        $list_add[] = '';
        $list_add[] = 'Total';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = '';
        $list_add[] = number_format($BilledQtySum, 2, ".", "");
        $list_add[] = number_format($BilledCratesSum, 2, ".", "");
        $list_add[] = number_format($BilledCasaseSum, 2, ".", "");
        $list_add[] = number_format($ItemValueSum, 2, ".", "");
            
        
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
        $html .= '<th align="center">Sr.No</th>';
        $html .= '<th align="center">SaleRtnID</th>';
        $html .= '<th align="center">ReturnDate</th>';
            
        if($AccountID !==''){
            $html .= '<th align="left">Item Name</th>';
            $html .= '<th align="left">Billed Qty</th>';
            $html .= '<th align="center">Cases</th>';
        }else{
            
            $html .= '<th align="left">Account Name</th>';
            $html .= '<th align="left">Address</th>';
            $html .= '<th align="center">GSTNO</th>';
            
        }
        $html .= '<th align="center">NetRtnAmt</th>';
        $html .= '<th align="center">CGSTAmt</th>';
        $html .= '<th align="center">SGSTAmt</th>';
        $html .= '<th align="center">IGSTAmt</th>';
        $html .= '<th align="center">FinalRtnAmt</th>';
        
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
            $html .= '<td align="right">'.$value["NetChallanAmt"].'</td>';
            $FinalAmtSum += $value["NetChallanAmt"];
            $html .= '</tr>';
            $i++;
        }
        $html .= '</tbody>';
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
            
            $list_add[] = $value["NetChallanAmt"];
            $FinalAmtSum += $value["NetChallanAmt"];
            
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
    // Get Result for SaleVsSaleRtn
    public function GetSaleVsSaleRtnReport()
     {
        $filterdata = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date'),
           'AccountID'  => $this->input->post('AccountID'),
           'AccountName'  => $this->input->post('AccountName'),
           'locType'  => $this->input->post('locType'),
           'repType'  => $this->input->post('repType')
          );
        $AccountID = $filterdata["AccountID"];
        $locType = $filterdata["locType"];
        $repType = $filterdata["repType"];
        if($locType == '1'){
            $locTypeName = 'Local';
        }else if($locType == '2'){
            $locTypeName = 'Outstation';
        }else{
            $locTypeName = 'notDefine';
        }
        if($repType == '1'){
            $repTypeName = 'AccountWiseDetails';
        }else{
            $repTypeName = 'ItemWiseDetails';
        }
        
        
        $body_Rowdata = $this->sale_reports_model->GetSaleVsSaleRtnBodyRowData($filterdata);
        $body_data = $this->sale_reports_model->GetSaleVsSaleRtnBodyData($filterdata);
        $company_detail = $this->sale_reports_model->get_company_detail();
        /*echo json_encode($body_data);
        die;*/
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
            $html .= '<th colspan="7"><b>'.$company_detail->company_name.'</b></th>';
            $html .= '</tr>';
            
            $html .= '<tr style="display:none;" class="show_in_print">';
            $html .= '<th colspan="7"><b>'.$company_detail->address.'</b></th>';
            $html .= '</tr>';
            
            $html .= '<tr style="display:none;" class="show_in_print">';
            $html .= '<th></th>';
            $html .= '<th><b>Report Date : </b></th>';
            $html .= '<th>'.$this->input->post('from_date').' To '.$this->input->post('to_date').'</th>';
            $html .= '</tr>';
            if($AccountID ==''){
                $html .= '<tr style="display:none;" class="show_in_print">';
                $html .= '<th></th>';
                $html .= '<th><b> LocationType: </b></th>';
                $html .= '<th>'.$locTypeName.'</th>';
                $html .= '</tr>';
            }
            
            $html .= '<tr style="display:none;" class="show_in_print">';
            $html .= '<th></th>';
            $html .= '<th><b>Report Type : </b></th>';
            $html .= '<th>'.$repTypeName.'</th>';
            $html .= '</tr>';
        $html .= '<tr>';
        if($AccountID !==''){
            $html .= '<th align="center">Sr.No</th>';
            $html .= '<th align="center">ItemID</th>';
            $html .= '<th align="center">ItemName</th>';
            $html .= '<th align="left">CasePack</th>';
            $html .= '<th align="left">SalesIn CS/Cr</th>';
            $html .= '<th align="center">SalesAmt</th>';
            $html .= '<th align="center">SalesRtn in Unit</th>';
            $html .= '<th align="center">SalesRtn Amt</th>';
            $html .= '<th align="center">Damage in Unit</th>';
            $html .= '<th align="center">Damage Amt</th>';
            $html .= '<th align="center">Rtn %</th>';
        }else{
            if($repType == '2'){
                $html .= '<th align="center">Sr.No</th>';
                $html .= '<th align="center">ItemID</th>';
                $html .= '<th align="center">ItemName</th>';
                $html .= '<th align="left">CasePack</th>';
                $html .= '<th align="left">SalesIn CS/Cr</th>';
                $html .= '<th align="center">SalesAmt</th>';
                $html .= '<th align="center">SalesRtn in Unit</th>';
                $html .= '<th align="center">SalesRtn Amt</th>';
                $html .= '<th align="center">Damage in Unit</th>';
                $html .= '<th align="center">Damage Amt</th>';
                $html .= '<th align="center">Rtn %</th>';
            }else{
                $html .= '<th align="center">Sr.No</th>';
                $html .= '<th align="center">AccountID</th>';
                $html .= '<th align="center">Account Name</th>';
                $html .= '<th align="left">Station Name</th>';
                $html .= '<th align="left">Sale Amt</th>';
                $html .= '<th align="center">Fresh Rtn Amt</th>';
                $html .= '<th align="center">Damage Amt</th>';
                $html .= '<th align="center">Rtn %</th>';
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
                            $frsRtnAmtSum += $frsRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
                            $DRtnAmt = $value1["NetChallanAmt"];
                            $CaseQty = $value1["CaseQty"];
                            $ItemName = $value1["description"];
                            $DBilledQty = $value1["BilledQty"];
                            $DBilledQtySum += $DBilledQty;
                            $DRtnAmtSum += $DRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
                            $saleAmt = $value1["NetChallanAmt"];
                            $saleAmtSum += $saleAmt;
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
                            $frsRtnAmtSum += $frsRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
                            $DRtnAmt = $value1["NetChallanAmt"];
                            $CaseQty = $value1["CaseQty"];
                            $ItemName = $value1["description"];
                            $DBilledQty = $value1["BilledQty"];
                            $DBilledQtySum += $DBilledQty;
                            $DRtnAmtSum += $DRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
                            $saleAmt = $value1["NetChallanAmt"];
                            $saleAmtSum += $saleAmt;
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
                            $frsRtnAmtSum += $frsRtnAmt;
                        }
                        if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Damage'){
                            $DRtnAmt = $value1["NetChallanAmt"];
                            $AccountName = $value1["company"];
                            $station = $value1["StationName"];
                            $DBilledQty = $value1["BilledQty"];
                            $DBilledQtySum += $DBilledQty;
                            $DRtnAmtSum += $DRtnAmt;
                        }
                        if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Order'){
                            $saleAmt = $value1["NetChallanAmt"];
                            $saleAmtSum += $saleAmt;
                            $AccountName = $value1["company"];
                            $station = $value1["StationName"];
                            $SBilledQty = $value1["BilledQty"];
                        }
                    }
                }
            }
            if(($saleAmt == '' || $saleAmt == '0.00') && ($frsRtnAmt == '' || $frsRtnAmt == '0.00') && ($DRtnAmt == '' || $DRtnAmt == '0.00')){
                
            }else{
                $html .= '<tr>';
                if($AccountID !==''){
                    $html .= '<td align="center">'.$i.'</td>';
                    $html .= '<td align="center">'.$value["ItemID"].'</td>';
                    $html .= '<td align="left">'.$ItemName.'</td>';
                    $html .= '<td align="center">'.$CaseQty.'</td>';
                    $BilledInCsCr = $SBilledQty/$CaseQty;
                    $BilledInCsCrSum += $BilledInCsCr;
                    $html .= '<td align="center">'. number_format($BilledInCsCr, 2, ".", "").'</td>';
                    $html .= '<td align="center">'.$saleAmt.'</td>';
                    $html .= '<td align="center">'.$FBilledQty.'</td>';
                    $html .= '<td align="center">'.$frsRtnAmt.'</td>';
                    $html .= '<td align="center">'.$DBilledQty.'</td>';
                    $html .= '<td align="center">'.$DRtnAmt.'</td>';
                    $RtnSum = $frsRtnAmt + $DRtnAmt;
                        if($saleAmt > 0){
                            $onePerValue = $saleAmt / 100;
                        }else{
                            $onePerValue = $RtnSum / 100;
                        }
                        if($RtnSum > 0){
                            $RtnPer = $RtnSum / $onePerValue;
                        }else{
                            $RtnPer = '';
                        }
                    $html .= '<td align="center">'.number_format($RtnPer, 2, ".", "").'</td>';
                }else{
                    if($repType == '2'){
                        $html .= '<td align="center">'.$i.'</td>';
                        $html .= '<td align="center">'.$value["ItemID"].'</td>';
                        $html .= '<td align="left">'.$ItemName.'</td>';
                        $html .= '<td align="center">'.$CaseQty.'</td>';
                        $BilledInCsCr = $SBilledQty/$CaseQty;
                        $BilledInCsCrSum += $BilledInCsCr;
                        $html .= '<td align="center">'.number_format($BilledInCsCr, 2, ".", "").'</td>';
                        $html .= '<td align="center">'.$saleAmt.'</td>';
                        $html .= '<td align="center">'.$FBilledQty.'</td>';
                        $html .= '<td align="center">'.$frsRtnAmt.'</td>';
                        $html .= '<td align="center">'.$DBilledQty.'</td>';
                        $html .= '<td align="center">'.$DRtnAmt.'</td>';
                        $RtnSum = $frsRtnAmt + $DRtnAmt;
                        if($saleAmt > 0){
                            $onePerValue = $saleAmt / 100;
                        }else{
                            $onePerValue = $RtnSum / 100;
                        }
                        if($RtnSum > 0){
                            $RtnPer = $RtnSum / $onePerValue;
                        }else{
                            $RtnPer = '';
                        }
                        $html .= '<td align="center">'.number_format($RtnPer, 2, ".", "").'</td>';
                    }else{
                        $html .= '<td align="center">'.$i.'</td>';
                        $html .= '<td align="center">'.$value["AccountID"].'</td>';
                        $html .= '<td align="left">'.$AccountName.'</td>';
                        $html .= '<td align="center">'.$station.'</td>';
                        $html .= '<td align="center">'. number_format($saleAmt, 2, ".", "").'</td>';
                        $html .= '<td align="center">'. number_format($frsRtnAmt, 2, ".", "").'</td>';
                        $html .= '<td align="center">'.number_format($DRtnAmt, 2, ".", "").'</td>';
                        $RtnSum = $frsRtnAmt + $DRtnAmt;
                        if($saleAmt > 0){
                            $onePerValue = $saleAmt / 100;
                        }else{
                            $onePerValue = $RtnSum / 100;
                        }
                        if($RtnSum > 0){
                            $RtnPer = $RtnSum / $onePerValue;
                        }else{
                            $RtnPer = '';
                        }
                        $html .= '<td align="center">'.number_format($RtnPer, 2, ".", "").'</td>';
                    }
                }
                
                $i++;
                $html .= '</tr>';
            }
        }
        
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
            
            $RtnSum1 = $frsRtnAmtSum + $DRtnAmtSum;
            if($saleAmtSum > 0){
                $onePerValue1 = $saleAmtSum / 100;
            }else{
                $onePerValue1 = $RtnSum1 / 100;
            }
            if($RtnSum1 > 0){
                $RtnPer1 = $RtnSum1 / $onePerValue1;
            }else{
                $RtnPer1 = '';
            }
                    
            $html .= '<td align="center">'.number_format($RtnPer1, 2, ".", "").'</td>';
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
                
                $RtnSum1 = $frsRtnAmtSum + $DRtnAmtSum;
                if($saleAmtSum > 0){
                    $onePerValue1 = $saleAmtSum / 100;
                }else{
                    $onePerValue1 = $RtnSum1 / 100;
                }
                if($RtnSum1 > 0){
                    $RtnPer1 = $RtnSum1 / $onePerValue1;
                }else{
                    $RtnPer1 = '';
                }
                        
                $html .= '<td align="center">'.number_format($RtnPer1, 2, ".", "").'</td>';
            }else{
                $html .= '<td align="center"></td>';
                $html .= '<td align="center">Total</td>';
                $html .= '<td align="center"></td>';
                $html .= '<td align="center"></td>';
                $html .= '<td align="center">'. number_format($saleAmtSum, 2, ".", "").'</td>';
                $html .= '<td align="center">'. number_format($frsRtnAmtSum, 2, ".", "").'</td>';
                $html .= '<td align="center">'.number_format($DRtnAmtSum, 2, ".", "").'</td>';
                $RtnSum1 = $frsRtnAmtSum + $DRtnAmtSum;
                if($saleAmtSum > 0){
                    $onePerValue1 = $saleAmtSum / 100;
                }else{
                    $onePerValue1 = $RtnSum1 / 100;
                }
                if($RtnSum1 > 0){
                    $RtnPer1 = $RtnSum1 / $onePerValue1;
                }else{
                    $RtnPer1 = '';
                }
                $html .= '<td align="center">'.number_format($RtnPer1, 2, ".", "").'</td>';
            }
        }
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        echo json_encode($html);
        die;
        
    }
    
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
           'repType'  => $this->input->post('repType')
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
        }else{
            $locTypeName = 'notDefine';
        }
        if($repType == '1'){
            $repTypeName = 'AccountWiseDetails';
        }else{
            $repTypeName = 'ItemWiseDetails';
        }
        
        
        $body_Rowdata = $this->sale_reports_model->GetSaleVsSaleRtnBodyRowData($filterdata);
        $body_data = $this->sale_reports_model->GetSaleVsSaleRtnBodyData($filterdata);
        $company_detail = $this->sale_reports_model->get_company_detail();
        /*echo json_encode($body_data);
        die;*/
        if($AccountID !==''){
            $AccountDetails = 'Account Name : '.$AccountName.' Address : '.$AccountAddress;
            $colspan = '9';
        }else{
            if($repType == '2'){
                $colspan = '9';
            }else{
                $colspan = '6';
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
    		
    		$set_col_tk["ItemID"] = 'ItemID';
    		$set_col_tk["ItemName"] = 'ItemName';
    		$set_col_tk["CasePack"] = 'CasePack';
    		$set_col_tk["SalesInCSCr"] = 'SalesIn CS/Cr';
    		$set_col_tk["SalesAmt"] = 'SalesAmt';
    		$set_col_tk["SalesRtninUnit"] = 'SalesRtn in Unit';
    		$set_col_tk["SalesRtnAmt"] = 'SalesRtn Amt';
    		$set_col_tk["DamageinUnit"] = 'Damage in Unit';
    		$set_col_tk["DamageAmt"] = 'Damage Amt';
    		$set_col_tk["Rtn"] = 'Rtn %';
    	}else{
    	    if($repType == '2'){
    	        
        		$set_col_tk["ItemID"] = 'ItemID';
        		$set_col_tk["ItemName"] = 'ItemName';
        		$set_col_tk["CasePack"] = 'CasePack';
        		$set_col_tk["SalesInCSCr"] = 'SalesIn CS/Cr';
        		$set_col_tk["SalesAmt"] = 'SalesAmt';
        		$set_col_tk["SalesRtninUnit"] = 'SalesRtn in Unit';
        		$set_col_tk["SalesRtnAmt"] = 'SalesRtn Amt';
        		$set_col_tk["DamageinUnit"] = 'Damage in Unit';
        		$set_col_tk["DamageAmt"] = 'Damage Amt';
        		$set_col_tk["Rtn"] = 'Rtn %';
    	    }else{
    	        
        		$set_col_tk["AccountID"] = 'AccountID';
        		$set_col_tk["AccountName"] = 'Account Name';
        		$set_col_tk["StationName"] = 'Station Name';
        		$set_col_tk["SaleAmt"] = 'Sale Amt';
        		$set_col_tk["FreshRtnAmt"] = 'Fresh Rtn Amt';
        		$set_col_tk["DamageAmt"] = 'Damage Amt';
        		$set_col_tk["Rtn"] = 'Rtn %';
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
                            $frsRtnAmtSum += $frsRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
                            $DRtnAmt = $value1["NetChallanAmt"];
                            $CaseQty = $value1["CaseQty"];
                            $ItemName = $value1["description"];
                            $DBilledQty = $value1["BilledQty"];
                            $DBilledQtySum += $DBilledQty;
                            $DRtnAmtSum += $DRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
                            $saleAmt = $value1["NetChallanAmt"];
                            $saleAmtSum += $saleAmt;
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
                            $frsRtnAmtSum += $frsRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Damage'){
                            $DRtnAmt = $value1["NetChallanAmt"];
                            $CaseQty = $value1["CaseQty"];
                            $ItemName = $value1["description"];
                            $DBilledQty = $value1["BilledQty"];
                            $DBilledQtySum += $DBilledQty;
                            $DRtnAmtSum += $DRtnAmt;
                        }
                        if($value["ItemID"] == $value1["ItemID"] && $value1["TType2"] == 'Order'){
                            $saleAmt = $value1["NetChallanAmt"];
                            $saleAmtSum += $saleAmt;
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
                            $frsRtnAmtSum += $frsRtnAmt;
                        }
                        if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Damage'){
                            $DRtnAmt = $value1["NetChallanAmt"];
                            $AccountName = $value1["company"];
                            $station = $value1["StationName"];
                            $DBilledQty = $value1["BilledQty"];
                            $DBilledQtySum += $DBilledQty;
                            $DRtnAmtSum += $DRtnAmt;
                        }
                        if($value["AccountID"] == $value1["AccountID"] && $value1["TType2"] == 'Order'){
                            $saleAmt = $value1["NetChallanAmt"];
                            $saleAmtSum += $saleAmt;
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
                    
                if($AccountID !==''){
                    
                    $list_add[] = $value["ItemID"];
                    $list_add[] = $ItemName;
                    $list_add[] = $CaseQty;
                    $BilledInCsCr = $SBilledQty/$CaseQty;
                    $BilledInCsCrSum += $BilledInCsCr;
                    $list_add[] = $BilledInCsCr;
                    $list_add[] = $saleAmt;
                    $list_add[] = $FBilledQty;
                    $list_add[] = $frsRtnAmt;
                    $list_add[] = $DBilledQty;
                    $list_add[] = $DRtnAmt;
                    $RtnSum = $frsRtnAmt + $DRtnAmt;
                        if($saleAmt > 0){
                            $onePerValue = $saleAmt / 100;
                        }else{
                            $onePerValue = $RtnSum / 100;
                        }
                        if($RtnSum > 0){
                            $RtnPer = $RtnSum / $onePerValue;
                        }else{
                            $RtnPer = '';
                        }
                    $list_add[] = $RtnPer;
                    
                }else{
                    if($repType == '2'){
                        $list_add[] = $value["ItemID"];
                        $list_add[] = $ItemName;
                        $list_add[] = $CaseQty;
                        $BilledInCsCr = $SBilledQty/$CaseQty;
                        $BilledInCsCrSum += $BilledInCsCr;
                        $list_add[] = $BilledInCsCr;
                        $list_add[] = $saleAmt;
                        $list_add[] = $FBilledQty;
                        $list_add[] = $frsRtnAmt;
                        $list_add[] = $DBilledQty;
                        $list_add[] = $DRtnAmt;
                        $RtnSum = $frsRtnAmt + $DRtnAmt;
                            if($saleAmt > 0){
                                $onePerValue = $saleAmt / 100;
                            }else{
                                $onePerValue = $RtnSum / 100;
                            }
                            if($RtnSum > 0){
                            $RtnPer = $RtnSum / $onePerValue;
                        }else{
                            $RtnPer = '';
                        }
                        $list_add[] = $RtnPer;
                    }else{
                        $list_add[] = $value["AccountID"];
                        $list_add[] = $AccountName;
                        $list_add[] = $station;
                        $list_add[] = $saleAmt;
                        $list_add[] = $frsRtnAmt;
                        $list_add[] = $DRtnAmt;
                        $RtnSum = $frsRtnAmt + $DRtnAmt;
                        if($saleAmt > 0){
                            $onePerValue = $saleAmt / 100;
                        }else{
                            $onePerValue = $RtnSum / 100;
                        }
                        if($RtnSum > 0){
                            $RtnPer = $RtnSum / $onePerValue;
                            $RtnPer = number_format($RtnPer, 2, ".", "");
                        }else{
                            $RtnPer = '';
                        }
                        $list_add[] = $RtnPer;
                    }
                }
                
                $writer->writeSheetRow('Sheet1', $list_add);
            }
            
        }
        
        // Footer Data
        $list_add = [];
        if($AccountID !==''){
            
            $list_add[] = '';
            $list_add[] = 'Total';
            $list_add[] = '';
            $list_add[] = $BilledInCsCrSum;
            $list_add[] = $saleAmtSum;
            $list_add[] = $FBilledQtySum;
            $list_add[] = $frsRtnAmtSum;
            $list_add[] = $DBilledQtySum;
            $list_add[] = $DRtnAmtSum;
            $RtnSum1 = $frsRtnAmtSum + $DRtnAmtSum;
            if($saleAmtSum > 0){
                $onePerValue1 = $saleAmtSum / 100;
            }else{
                $onePerValue1 = $RtnSum1 / 100;
            }
            if($RtnSum1 > 0){
                $RtnPer1 = $RtnSum1 / $onePerValue1;
            }else{
                $RtnPer1 = '';
            }
            $list_add[] = $RtnPer1;
            
        }else{
            if($repType == '2'){
                $list_add[] = '';
                $list_add[] = 'Total';
                $list_add[] = '';
                $list_add[] = $BilledInCsCrSum;
                $list_add[] = $saleAmtSum;
                $list_add[] = $FBilledQtySum;
                $list_add[] = $frsRtnAmtSum;
                $list_add[] = $DBilledQtySum;
                $list_add[] = $DRtnAmtSum;
                $RtnSum1 = $frsRtnAmtSum + $DRtnAmtSum;
                if($saleAmtSum > 0){
                    $onePerValue1 = $saleAmtSum / 100;
                }else{
                    $onePerValue1 = $RtnSum1 / 100;
                }
                if($RtnSum1 > 0){
                    $RtnPer1 = $RtnSum1 / $onePerValue1;
                    $RtnPer1 = number_format($RtnPer1, 2, ".", "");
                }else{
                    $RtnPer1 = '';
                }
                $list_add[] = $RtnPer1;
            }else{
                $list_add[] = '';
                $list_add[] = 'Total';
                $list_add[] = '';
                $list_add[] = $saleAmtSum;
                $list_add[] = $frsRtnAmtSum;
                $list_add[] = $DRtnAmtSum;
                $RtnSum1 = $frsRtnAmtSum + $DRtnAmtSum;
                if($saleAmtSum > 0){
                    $onePerValue1 = $saleAmtSum / 100;
                }else{
                    $onePerValue1 = $RtnSum1 / 100;
                }
                if($RtnSum1 > 0){
                    $RtnPer1 = $RtnSum1 / $onePerValue1;
                }else{
                    $RtnPer1 = '';
                }
                $list_add[] = $RtnPer1;
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
    /* List all Dialy sales datatables */
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
        
       /*echo json_encode($tableData);
        die;*/
        $html = '';
        $html .= '<table class="table-striped table-bordered daily_report" id="daily_report">';
        $html .= '<thead style="font-size:11px;">';
            
        $html .= '<tr style="display:none;">';
        $html .= '<th colspan="6"><b>'.$company_detail->company_name.'</b></th>';
        $html .= '</tr>';
            
        $html .= '<tr style="display:none;">';
        $html .= '<th colspan="6"><b>'.$company_detail->address.'</b></th>';
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
            
        $html .= '<tr>';
        $html .= '<th>SrNo.</th>';
        $html .= '<th>ItemID</th>';
        $html .= '<th>Item Name</th>';
        $html .= '<th>Pack</th>';
        $html .= '<th>Order Qty</th>';
        $html .= '<th>Bill Qty</th>';
        $html .= '<th>Diff Qty</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $srNo = 1;
        $OrderSum = 0;
        $BillSum = 0;
        $DiffSum = 0;
        foreach ($tableData as $key => $value) {
            $html .= '<tr>';
            $html .= '<td style="text-align:center;">'.$srNo.'</td>';
            $html .= '<td>'.$value["ItemID"].'</td>';
            $html .= '<td>'.$value["description"].'</td>';
            $html .= '<td style="text-align:center;">'.$value["case_qty"].'</td>';
            $OrdCases = $value["OrdQty"] / $value["case_qty"];
            $html .= '<td style="text-align:right;">'.number_format($OrdCases,2).'</td>';
            $OrderSum += $OrdCases;
            $BillCases = $value["BillQty"] / $value["case_qty"];
            $html .= '<td style="text-align:right;">'.number_format($BillCases,2).'</td>';
            $BillSum += $BillCases;
            $Diff = $BillCases - $OrdCases;
            $DiffSum += $Diff;
            $html .= '<td style="text-align:right;">'.number_format($Diff,2).'</td>';
            $html .= '</tr>';
            $srNo++;
        }
            $html .= '<tr>';
            $html .= '<td style="text-align:center;"></td>';
            $html .= '<td colspan="3">Total</td>';
            $html .= '<td style="text-align:right;">'.number_format($OrderSum,2).'</td>';
            $html .= '<td style="text-align:right;">'.number_format($BillSum,2).'</td>';
            $html .= '<td style="text-align:right;">'.number_format($DiffSum,2).'</td>';
            $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '<table>';
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
    		$writer->markMergedCell('Sheet1', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_name);
    		
    		$address = $company_detail->address;
    		$company_addr = array($address,);
    		$writer->markMergedCell('Sheet1', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $company_addr);
    		
    		$msg = "Report Date : ".$this->input->post('from_date')." To " .$this->input->post('to_date');
    		$filter = array($msg);
    		$writer->markMergedCell('Sheet1', $start_row = 2, $start_col = 0, $end_row = 2, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $filter);
    		
    		$msg1 = "State : ".$state_name->state_name." DistributorType : " .$client_type_name->name;
    		$filter1 = array($msg1);
    		$writer->markMergedCell('Sheet1', $start_row = 3, $start_col = 0, $end_row = 3, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $filter1);
    		$msg11 = "Party Name : ".$Partyname->company;
    		$filter11 = array($msg11);
    		$writer->markMergedCell('Sheet1', $start_row = 4, $start_col = 0, $end_row = 4, $end_col = 6);  //merge cells
    		$writer->writeSheetRow('Sheet1', $filter11);
    		
    		// empty row
    		/*$list_add = [];
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
            $writer->writeSheetRow('Sheet1', $list_add);*/
            
            
            $set_col_tk = [];
    		$set_col_tk["ItemID"] =  'ItemID';
    		$set_col_tk["ItemName"] = 'ItemName';
    		$set_col_tk["Pack"] = 'Pack';
    		$set_col_tk["OrderQty"] = 'OrderQty';
    		$set_col_tk["BillQty"] = 'BillQty';
    		$set_col_tk["Diff Qty"] = 'Diff Qty';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
        $srNo = 1;
        $OrderQtySum = 0;
        $BillQtySum = 0;
        $DiffQtySum = 0;
        foreach ($tableData as $key => $value) {
            $list_add = [];
    		$list_add[] = $value["ItemID"];
    		$list_add[] = $value["description"];
    		$list_add[] = $value["case_qty"];
    		$OrdCases = $value["OrdQty"] / $value["case_qty"];
    		$list_add[] = $OrdCases;
    		$OrderQtySum += $OrdCases;
    		$BillCases = $value["BillQty"] / $value["case_qty"];
    		$list_add[] = $BillCases;
    		$BillQtySum += $BillCases;
    		$Diff = 	$BillCases - $OrdCases;
    		$list_add[] = $Diff;
    		$DiffQtySum += $Diff;
    		$writer->writeSheetRow('Sheet1', $list_add);
        }
            $list_add = [];
            $list_add[] = 'Total';
            $list_add[] = '';
            $list_add[] = '';
            $list_add[] = $OrderQtySum;
            $list_add[] = $BillQtySum;
            $list_add[] = $DiffQtySum;
            $writer->writeSheetRow('Sheet1', $list_add);
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
        $html .= '<th>SrNo.</th>';
        $html .= '<th>OrderID</th>';
        $html .= '<th>OrderDate</th>';
        $html .= '<th>SaleID</th>';
        $html .= '<th>InvoiceDate</th>';
        $html .= '<th>Tm TakenToBill</th>';
        $html .= '<th>GatePass Time</th>';
        $html .= '<th>Tm Btn. Bill & Getpass</th>';
        $html .= '<th>PartyName</th>';
        $html .= '<th>OrderAmt</th>';
        $html .= '<th>BillAmt</th>';
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
            $Sum = $value["OrderAmt"] - $value["BillAmt"];
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
            $html .= '<td style="text-align:right;">'.number_format($value["OrderAmt"],2).'</td>';
            $OrderSum += $value["OrderAmt"];
            $html .= '<td style="text-align:right;">'.number_format($value["BillAmt"],2).'</td>';
            $BillSum += $value["BillAmt"];
            $html .= '</tr>';
            $srNo++;
        }
        $html .= '<tr>';
        $html .= '<td colspan="9">Total</td>';
        $html .= '<td style="text-align:right;">'.number_format($OrderSum,2).'</td>';
        $html .= '<td style="text-align:right;">'.number_format($BillSum,2).'</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
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
    		$list_add[] = $value["OrderAmt"];
    		$OrderSum += $value["OrderAmt"];
    		$list_add[] = $value["BillAmt"];
    		$BillSum += $value["BillAmt"];
    			
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
            $list_add[] = $OrderSum;
            $list_add[] = $BillSum;
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
    
    public function load_data()
     {
        $data = array(
           'from_date' => $this->input->post('from_date'),
           'to_date'  => $this->input->post('to_date')
          );
      $result = $this->sale_reports_model->load_data($data);
      echo json_encode($result);
    }
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
            $writer->writeSheetRow('Sheet1', $list_add);
            
            
            $set_col_tk = [];
    		$set_col_tk["ChallanId"] =  'ChallanId';
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
    		    $RndAmt = $value["RndAmt"];
                $grand_total = $grand_total + $RndAmt ;
    			$list_add = [];
    			$list_add[] = $value["ChallanID"];
    			$list_add[] = $value["SalesID"];
    			$date = _d(substr($value["Transdate"],0,10));
    			$list_add[] = $date;
    			$list_add[] = $value["AccountID"];
    			$list_add[] = $value["AccountName"];
    			$list_add[] = $value["RndAmt"];
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
    			    $list_add[] = "Total";
    			    $list_add[] = $value["Total_number"];
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
    	    $list_add[] = "Total ".count($data)." rows Grand Total";
    		$list_add[] = $grand_total;
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
        
        $table_header_data = $this->sale_reports_model->get_orders_itemlist_new($filterdata,$item_group);
        
        
        foreach ($table_header_data as $value) {
            array_push($account_ids,$value['AccountID']);
            $item_ids_desc[$value["ItemID"]] = $value["description"];
        }
        $item_ids_desc = array_unique($item_ids_desc);
        $getAccountDetailsList = $this->sale_reports_model->GetAccountList($account_ids);
         
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
    		foreach ($item_ids_desc as $key => $value) {
    		    $set_col_tk[$key] = $value;
    		}
    		$set_col_tk["Item Value"] = 'Item Value';
    		$set_col_tk["Crates"] = 'Crates';
    		$set_col_tk["Cases"] = 'Cases';
    		$writer_header = $set_col_tk;
    		$writer->writeSheetRow('Sheet1', $writer_header);
            
    		$account_ids = array_unique($account_ids);
            $account_ids_str = "";
            $ii = 1;
            foreach ($account_ids as $id) {
                if($ii == "1"){
                    $account_ids_str = '"'.$id.'"';
                }else{
                    $account_ids_str = $account_ids_str.',"'.$id.'"';
                }
                $ii++;
            }
            $body_data = $this->sale_reports_model->get_itemdetails_for_body_data($account_ids_str,$filterdata);
            
            $footer_data = $this->sale_reports_model->get_itemdetails_for_footer_data($account_ids_str,$filterdata);
            $sale_return = $this->sale_reports_model->get_itemdetails_for_sale_return2($account_ids_str,$filterdata);
            $sale_return3 = $this->sale_reports_model->get_itemdetails_for_sale_return3($account_ids_str,$filterdata);
           
            $col_total_val = 0.00;
            $col_total_cases = 0.00;
            $col_total_crates = 0.00;
            $col_total_unit = 0.00;
            
    	    foreach ($getAccountDetailsList as $AccountIdkey => $AccountDetails) {
    		    //$account_name = get_account_name($AccountId,$selected_company);
                //$account_station = get_station_name($AccountId,$selected_company);
                $AccountId = $AccountDetails['AccountID'];
    			$list_add = [];
    			$list_add[] = "";
    			$list_add[] = $AccountDetails['StationName'];
    			$list_add[] = $AccountDetails['AccountID'];
    			$list_add[] = $AccountDetails['company'];
    			$row_value = 0.00;
                $row_cases = 0.00;
                $row_crates = 0.00;
                $row_unit = 0.00;
                foreach ($item_ids_desc as $key => $value) {
                    $sumamt = "";
                    $sumcases = "";
                    $sumcrates = "";
                    $sumunit = "";
                    foreach ($body_data as $key2 => $value2) {
                        $match = 0;
                        if(trim(strtoupper($value2["AccountID"])) == trim(strtoupper($AccountId)) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($key))){
                            if($report_type == "netsales"){
                                foreach ($sale_return as $sr_key => $sr_value) {
                                    if(trim(strtoupper($value2["AccountID"])) == trim(strtoupper($sr_value["AccountID"])) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($sr_value["ItemID"]))){
                                        
                                        $sumamt = $value2["amt_sum"] - $sr_value["sr_amt_sum"] - $sr_value["sr_amt_sum"];
                                        if($value2["SuppliedIn"] == "CR"){
                                            $sumcrates = $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }else{
                                            $sumcases = $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }
                                        
                                        $sumunit = $value2["sumunit"] - $sr_value["sr_sumunit"]- $sr_value["sr_sumunit"];
                                        
                                        $row_value = $row_value + $value2["amt_sum"] - $sr_value["sr_amt_sum"] - $sr_value["sr_amt_sum"];
                                        if($value2["SuppliedIn"] == "CR"){
                                            $row_crates = $row_crates + $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }else{
                                            $row_cases = $row_cases + $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }
                                        
                                        $row_unit = $row_unit + $value2["sumunit"] - $sr_value["sr_sumunit"] - $sr_value["sr_sumunit"];
                                        $match++;
                                    }
                                }
                                if($match == 0){
                                    $sumamt = $value2["amt_sum"];
                                    if($value2["SuppliedIn"] == "CR"){
                                        $sumcrates = $value2["sumcases"];
                                    }else{
                                        $sumcases = $value2["sumcases"];
                                    }
                                    $sumunit = $value2["sumunit"];
                                
                                    $row_value = $row_value + $value2["amt_sum"];
                                    if($value2["SuppliedIn"] == "CR"){
                                        $row_crates = $row_crates + $value2["sumcases"];
                                    }else{
                                        $row_cases = $row_cases + $value2["sumcases"];
                                    }
                                    $row_unit = $row_unit + $value2["sumunit"];
                                }
                            }else{
                                $sumamt = $value2["amt_sum"];
                                if($value2["SuppliedIn"] == "CR"){
                                    $sumcrates = $value2["sumcases"];
                                }else{
                                    $sumcases = $value2["sumcases"];
                                }
                                $sumunit = $value2["sumunit"];
                            
                                $row_value = $row_value + $value2["amt_sum"];
                                if($value2["SuppliedIn"] == "CR"){
                                    $row_crates = $row_crates + $value2["sumcases"];
                                }else{
                                    $row_cases = $row_cases + $value2["sumcases"];
                                }
                                $row_unit = $row_unit + $value2["sumunit"];
                            }
                        }
                    }
                    $item_value_new = 0;
                    if($report_in == "value"){
                        $item_value_new = $sumamt;
                    }elseif($report_in == "cases"){
                        $item_value_new = $sumcases + $sumcrates;
                    }elseif($report_in == "unit"){
                        $item_value_new = $sumunit;
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
    	    foreach ($item_ids_desc as $key => $value) {
    	        foreach ($account_ids as $AccountId) {
    	            $colsumamt = "";
    	            foreach ($footer_data as $key3 => $value3) {
    	                 $match1 = 0;
    	                 if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($key))){
    	                     if($report_type == "netsales"){
    	                         foreach ($sale_return3 as $sr_key3 => $sr_value3) {
    	                             if($value3["ItemID"] == $sr_value3["ItemID"]){
    	                                 /*if($value3["amt_sum"] == $sr_value1["sr_amt_sum"]){
                                         $colsumamt = 0.00 - $sr_value1["sr_amt_sum"];
                                         }else{
                                            $colsumamt = $value3["amt_sum"] - $sr_value1["sr_amt_sum"];
                                         }*/
                                         $colsumamt = $value3["amt_sum"] - $sr_value3["sr_amt_sum"] - $sr_value3["sr_amt_sum"];
                                        $match1++;
    	                             }
    	                         }
    	                         if($match1 == 0){
                                    $colsumamt = $value3["amt_sum"];
                                }
    	                     }else{
                                    $colsumamt = $value3["amt_sum"];
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
                foreach ($item_ids_desc as $key => $value) {
                    foreach ($account_ids as $AccountId) {
                        $colsumcases = "";
                        foreach ($footer_data as $key3 => $value3) {
                            $match2 = 0;
                            if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($key))){
                                if($report_type == "netsales"){
                                    foreach ($sale_return3 as $sr_key3 => $sr_value3) {
                                        if($value3["ItemID"] == $sr_value3["ItemID"]){
                                            /*if($value3["sumcases"] == $sr_value1["sr_sumcases"]){
                                                $colsumcases = 0.00 - $sr_value1["sr_sumcases"];
                                            }else{
                                                $colsumcases = $value3["sumcases"] - $sr_value1["sr_sumcases"];
                                            }*/
                                            $colsumcases = $value3["sumcases"] - $sr_value3["sr_sumcases"] - $sr_value3["sr_sumcases"];
                                            $match2++;
                                        }
                                    }
                                    if($match2 == 0){
                                        $colsumcases = $value3["sumcases"];
                                    }
                                }else{
                                    $colsumcases = $value3["sumcases"];
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
                foreach ($item_ids_desc as $key => $value) {
                    foreach ($account_ids as $AccountId) {
                        $colsumunit = "";
                        foreach ($footer_data as $key3 => $value3) {
                            $match3 = 0;
                            if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($key))){
                                if($report_type == "netsales"){
                                    foreach ($sale_return3 as $sr_key33 => $sr_value3) {
                                        if($value3["ItemID"] == $sr_value3["ItemID"]){
                                            /*if($value3["sumunit"] == $sr_value1["sr_sumunit"]){
                                                $colsumunit = 0.00 - $sr_value1["sr_sumunit"];
                                            }else{
                                                $colsumunit = $value3["sumunit"] - $sr_value1["sr_sumunit"];
                                            }*/
                                            $colsumunit = $value3["sumunit"] - $sr_value3["sr_sumunit"] - $sr_value3["sr_sumunit"];
                                            $match3++;
                                        }
                                    }
                                    if($match3 == 0){
                                        $colsumunit = $value3["sumunit"];
                                    }
                                }else{
                                    $colsumunit = $value3["sumunit"];
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
    		$files = glob(TIMESHEETS_PATH_EXPORT_FILE.'*');
    		foreach($files as $file){
    			if(is_file($file)) {
    				unlink($file); 
    			}
    		}
    		$filename = 'CummulativeSale_Report.xlsx';
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
        
        $table_header_data = $this->sale_reports_model->get_orders_itemlist_new($filterdata,$item_group);
        if(empty($table_header_data)){
            $error = "No record found...";
            echo json_encode($error);
        die;
        }
        
        foreach ($table_header_data as $value) {
            array_push($account_ids,$value['AccountID']);
            $item_ids_desc[$value["ItemID"]] = $value["description"];
        }
        $item_ids_desc = array_unique($item_ids_desc);
        $getAccountDetailsList = $this->sale_reports_model->GetAccountList($account_ids);
       /*echo json_encode($table_header_data);
        die;*/
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
            $html .= '<th class="col-id-no fixed-header">SOID</th>';
            $html .= '<th class="col-id-ordid fixed-header">Station Name</th>';
            $html .= '<th class="col-id-custname fixed-header">AccountID</th>';
            $html .= '<th width="20%" class="col-id-custstate fixed-header">Account Name</th>';
            foreach ($item_ids_desc as $key => $value) {
            $html .='<th style="text-align:right;">'.$value.'</th>';
            
            }
            
            $html .= '<th>Item Value</th>';
            $html .= '<th>Crates</th>';
            $html .= '<th>Cases</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $account_ids = array_unique($account_ids);
            $account_ids_str = "";
            $ii = 1;
            foreach ($account_ids as $id) {
                if($ii == "1"){
                    $account_ids_str = '"'.$id.'"';
                }else{
                    $account_ids_str = $account_ids_str.',"'.$id.'"';
                }
                $ii++;
            }
            $body_data = $this->sale_reports_model->get_itemdetails_for_body_data($account_ids_str,$filterdata);
            /*echo json_encode($account_ids_str);
        die;*/
            $footer_data = $this->sale_reports_model->get_itemdetails_for_footer_data($account_ids_str,$filterdata);
            $sale_return = $this->sale_reports_model->get_itemdetails_for_sale_return2($account_ids_str,$filterdata);
            $sale_return3 = $this->sale_reports_model->get_itemdetails_for_sale_return3($account_ids_str,$filterdata);
           
            $col_total_val = 0.00;
            $col_total_cases = 0.00;
            $col_total_crates = 0.00;
            $col_total_unit = 0.00;
            foreach ($getAccountDetailsList as $AccountIdkey => $AccountDetails) {
            $html .='<tr>';
            $AccountId = $AccountDetails['AccountID'];
            $html .='<td class="col-id-no"></td>';
            $html .='<td class="col-id-ordid">'.$AccountDetails['StationName'].'</td>';
            $html .='<td class="col-id-custname acctname" width="20%">'.$AccountDetails['AccountID'].'</td>';
            $html .='<td width="20%" class="col-id-custstate">'.$AccountDetails['company'].'</td>';
            //$sumamt = "A";
            $row_value = 0.00;
            $row_cases = 0.00;
            $row_crates = 0.00;
            $row_unit = 0.00;
            
            foreach ($item_ids_desc as $key => $value) {
                $sumamt = "";
                $sumcases = "";
                $sumunit = "";
                $sumcrates = "";
                foreach ($body_data as $key2 => $value2) {
                    $match = 0;
                    if(trim(strtoupper($value2["AccountID"])) == trim(strtoupper($AccountId)) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($key))){
                        
                        if($report_type == "netsales"){
                            foreach ($sale_return as $sr_key => $sr_value) {
                                if(trim(strtoupper($value2["AccountID"])) == trim(strtoupper($sr_value["AccountID"])) && trim(strtoupper($value2["ItemID"])) == trim(strtoupper($sr_value["ItemID"]))){
                                    
                                        $sumamt = $value2["amt_sum"] - $sr_value["sr_amt_sum"] - $sr_value["sr_amt_sum"];
                                        if($value2["SuppliedIn"] == "CR"){
                                            $sumcrates = $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }else{
                                            $sumcases = $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }
                                        
                                        $sumunit = $value2["sumunit"] - $sr_value["sr_sumunit"]- $sr_value["sr_sumunit"];
                                        
                                        $row_value = $row_value + $value2["amt_sum"] - $sr_value["sr_amt_sum"] - $sr_value["sr_amt_sum"];
                                        if($value2["SuppliedIn"] == "CR"){
                                            $row_crates = $row_crates + $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }else{
                                            $row_cases = $row_cases + $value2["sumcases"] - $sr_value["sr_sumcases"] - $sr_value["sr_sumcases"];
                                        }
                                        $row_unit = $row_unit + $value2["sumunit"] - $sr_value["sr_sumunit"] - $sr_value["sr_sumunit"];
                                    
                                    $match++;
                                }
                            }
                            if($match == 0){
                                $sumamt = $value2["amt_sum"];
                                if($value2["SuppliedIn"] == "CR"){
                                    $sumcrates = $value2["sumcases"];
                                }else{
                                    $sumcases = $value2["sumcases"];
                                }
                                
                                $sumunit = $value2["sumunit"];
                            
                                $row_value = $row_value + $value2["amt_sum"];
                                if($value2["SuppliedIn"] == "CR"){
                                    $row_crates = $row_crates + $value2["sumcases"];
                                }else{
                                    $row_cases = $row_cases + $value2["sumcases"];
                                }
                                $row_unit = $row_unit + $value2["sumunit"];
                            }
                        }else{
                                $sumamt = $value2["amt_sum"];
                                if($value2["SuppliedIn"] == "CR"){
                                    $sumcrates = $value2["sumcases"];
                                }else{
                                    $sumcases = $value2["sumcases"];
                                }
                                
                                $sumunit = $value2["sumunit"];
                            
                                $row_value = $row_value + $value2["amt_sum"];
                                if($value2["SuppliedIn"] == "CR"){
                                    $row_crates = $row_crates + $value2["sumcases"];
                                }else{
                                    $row_cases = $row_cases + $value2["sumcases"];
                                }
                                $row_unit = $row_unit + $value2["sumunit"];
                        }
                        
                    }
                }
                $item_value_new = 0;
                if($report_in == "value"){
                        $item_value_new = $sumamt;
                    }elseif($report_in == "cases"){
                        $item_value_new = $sumcases + $sumcrates;
                    }elseif($report_in == "unit"){
                        $item_value_new = $sumunit;
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
            }
            
            // footer data
            
            $html .='<tr>';
            $html .='<td scope="row" class="col-id-no"></td>';
            $html .='<td scope="row" class="col-id-ordid"></td>';
            $html .='<td scope="row" class="col-id-custname"></td>';
            $html .='<td style="color:#FFF;font-weight:700;" scope="row" class="col-id-custstate"><b>Total Value(Incl GST)</b></td>';
        foreach ($item_ids_desc as $key => $value) {
            $colsumamt = 0.00;
            //foreach ($account_ids as $AccountId) {
                
                foreach ($footer_data as $key3 => $value3) {
                    $match1 = 0;
                    if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($key))){
                        if($report_type == "netsales"){
                            
                            foreach ($sale_return3 as $sr_key3 => $sr_value3) {
                                if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
                                     /*if($value3["amt_sum"] == $sr_value1["sr_amt_sum"]){
                                         $colsumamt = 0.00 - $sr_value1["sr_amt_sum"];
                                     }else{
                                         $colsumamt = $value3["amt_sum"] - $sr_value1["sr_amt_sum"];
                                     }*/
                                     $colsumamt = $value3["amt_sum"] - $sr_value3["sr_amt_sum"] - $sr_value3["sr_amt_sum"];
                                    $match1++;
                                }
                            }
                            if($match1 == 0){
                                $colsumamt = $value3["amt_sum"];
                            }
                        }else{
                            $colsumamt = $value3["amt_sum"];
                        }
                    }
                }
            //}
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
        foreach ($item_ids_desc as $key => $value) {
            
            foreach ($account_ids as $AccountId) {
            
            $colsumcases = "";
                foreach ($footer_data as $key3 => $value3) {
                    $match2 = 0;
                    if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($key))){
                        if($report_type == "netsales"){
                            
                            foreach ($sale_return3 as $sr_key3 => $sr_value3) {
                                if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
                                     /*if($value3["sumcases"] == $sr_value1["sr_sumcases"]){
                                         $colsumcases = 0.00 - $sr_value1["sr_sumcases"];
                                     }else{
                                         $colsumcases = $value3["sumcases"] - $sr_value1["sr_sumcases"];
                                     }*/
                                     $colsumcases = $value3["sumcases"] - $sr_value3["sr_sumcases"]- $sr_value3["sr_sumcases"];
                                    $match2++;
                                }
                            }
                            if($match2 == 0){
                                $colsumcases = $value3["sumcases"];
                            }
                        }else{
                            $colsumcases = $value3["sumcases"];
                        }
                        //$colsumcases = $value3["sumcases"];
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
        foreach ($item_ids_desc as $key => $value) {
            
            foreach ($account_ids as $AccountId) {
            
            $colsumunit = "";
                foreach ($footer_data as $key3 => $value3) {
                    $match3 = 0;
                    if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($key))){
                        if($report_type == "netsales"){
                            
                            foreach ($sale_return3 as $sr_key3 => $sr_value3) {
                                if(trim(strtoupper($value3["ItemID"])) == trim(strtoupper($sr_value3["ItemID"]))){
                                     /*if($value3["sumunit"] == $sr_value1["sr_sumunit"]){
                                         $colsumunit = 0.00 - $sr_value1["sr_sumunit"];
                                     }else{
                                         $colsumunit = $value3["sumunit"] - $sr_value1["sr_sumunit"];
                                     }*/
                                     $colsumunit = $value3["sumunit"] - $sr_value3["sr_sumunit"] - $sr_value3["sr_sumunit"];
                                    $match3++;
                                }
                            }
                            if($match3 == 0){
                                $colsumunit = $value3["sumunit"];
                            }
                        }else{
                            $colsumunit = $value3["sumunit"];
                        }
                        //$colsumunit = $value3["sumunit"];
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
            
            $html .= '</tbody>';
            $html .= '</table>';
           
        echo json_encode($html);
        die;
        
    }
}
