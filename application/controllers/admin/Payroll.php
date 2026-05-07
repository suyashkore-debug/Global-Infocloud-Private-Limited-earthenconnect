<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Payroll extends AdminController
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('payroll_model');
			
			$this->load->model('hr_profile/hr_profile_model');
		}
		
		public function salaryComponents()
		{
		    if (!has_permission_new('salaryComponents', '', 'view')) {
				access_denied('salaryComponents');
			}
		    $data['title'] = "Salary Components";
		    $data['salary_head_table'] = $this->payroll_model->get_head_data();
		    $data['company_detail'] = $this->payroll_model->get_company_detail();
		    $this->load->view('admin/payroll/salaryComponents', $data);
		}
		public function GetSalaryHeadList()
		{
			$headList = $this->payroll_model->get_head_data();
			echo json_encode($headList);
		}
		public function get_salary_head_details()
		{
			$headCode = $this->input->post('head_code');
			$head_data = $this->payroll_model->get_salary_head_details($headCode);
			echo json_encode($head_data);
		}
		
		public function SaveHead()
		{
		    if (!has_permission_new('salaryComponents', '', 'create')) {
				access_denied('salaryComponents');
			}
			$calculatedBy = $this->input->post('calculatedBy');
			
			if (is_array($calculatedBy)) {
				$calculatedBy = implode(',', $calculatedBy);
			}
			// echo "<pre>";print_r($calculatedBy);die;
			
			$data = array(
			'code'=>$this->input->post('HeadCode'),
			'name'=>$this->input->post('HeadName'),
			'SequenceNo'=>$this->input->post('SequenceNo'),
			'type'=>$this->input->post('type'),
			'CalcualtedFor'=>$this->input->post('CalcualtedFor'),
			'mesuredIn'=>$this->input->post('mesuredIn'),
			'UserID'=>$this->session->userdata('username'),
			'TransDate'=>date('Y-m-d H:i:s'),
			);
			if($this->input->post('mesuredIn') == "2"){
			    $data['percentage'] = $this->input->post('percentage');
			    $data['calculatedBy'] = $this->input->post('calculatedBy');
			    $data['MaxAmt'] = $this->input->post('MaxAmt');
			    $data['auto_calculate'] = 'Y';
			}
			if($this->input->post('mesuredIn') == "3"){
			    // $data['calculatedBy'] = $calculatedBy;
			    $data['auto_calculate'] = 'Y';
			}
			$SalaryHead  = $this->payroll_model->SaveHead($data);
			echo json_encode($SalaryHead);
		}
		
		public function UpdateSalaryHead()
		{
		    if (!has_permission_new('salaryComponents', '', 'edit')) {
				access_denied('salaryComponents');
			}
			
			$calculatedBy = $this->input->post('calculatedBy');
			
			if (is_array($calculatedBy)) {
				$calculatedBy = implode(',', $calculatedBy);
			}
			
			$data = array(
			'name'=>$this->input->post('HeadName'),
			'type'=>$this->input->post('HeadType'),
			'SequenceNo'=>$this->input->post('SequenceNo'),
			'CalcualtedFor'=>$this->input->post('CalcualtedFor'),
			'mesuredIn'=>$this->input->post('measuredIn'),
			'UserID2'=>$this->session->userdata('username'),
			'Lupdate'=>date('Y-m-d H:i:s'),
			);
			
			if($this->input->post('measuredIn') == "2"){
			    $data['percentage'] = $this->input->post('percentage');
			    $data['calculatedBy'] = $this->input->post('calculatedBy');
			    $data['MaxAmt'] = $this->input->post('MaxAmt');
			    $data['auto_calculate'] = 'Y';
				}elseif($this->input->post('measuredIn') == "3"){
			    $data['percentage'] = NULL;
			    $data['MaxAmt'] = NULL;
			    $data['calculatedBy'] = NULL;
			    $data['auto_calculate'] = 'Y';
				}else{
			    $data['percentage'] = NULL;
			    $data['MaxAmt'] = NULL;
			    $data['calculatedBy'] = NULL;
			    $data['auto_calculate'] = 'N';
			}
			$HeadCode = $this->input->post('HeadCode');
			$HeadID  = $this->payroll_model->UpdateHead($data,$HeadCode);
			echo json_encode($HeadID);
		}
		
		public function SalaryMaster()
		{
			if (!has_permission_new('salarymaster', '', 'view')) {
				access_denied('salaryHead');
			}
			if($this->input->post()){
				if (!has_permission_new('salarymaster', '', 'edit')) {
					access_denied('salaryHead');
				}
				$inputData = $this->input->post();
				$result = $this->payroll_model->SaveSalaryDetails($inputData);
				if($result){
					set_alert('success', 'Salary updated successfully');
					}else{
					set_alert('warning', 'something went wrong please try again.');
				}
				$redUrl = admin_url('payroll/SalaryMaster');
				redirect($redUrl);
				/*echo "<pre>";
					print_r($inputData);
				die;*/
			}
			$data['title'] = 'Salary Master';
			$data['ActiveStaff'] = $this->payroll_model->GetActiveStaff();
			$data['SalaryHead'] = $this->payroll_model->GetSalaryHead();
			$data['SalaryDetails'] = $this->payroll_model->GetSalaryDetails();
			/*echo "<pre>";
				print_r($data['SalaryDetails']);
			die;*/
			
			$this->load->view('admin/payroll/SalaryMaster',$data);     
		}
		public function Staff_payroll()
		{
			if (!has_permission_new('salarymaster', '', 'view')) {
				access_denied('salaryHead');
			}
			$data['title'] = 'Staff Payroll';
			// $data['ActiveStaff'] = $this->payroll_model->GetActiveStaff();
			// $data['SalaryHead'] = $this->payroll_model->GetSalaryHead();
			// $data['SalaryDetails'] = $this->payroll_model->GetSalaryDetails();
			
			$this->load->view('admin/payroll/GenerateSalary',$data);
		}
		
		public function get_Staff_payroll()
		{
			$ActiveStaff =  $this->payroll_model->GetActiveStaff();
			$SalaryHead = $this->payroll_model->GetSalaryHead();
			$SalaryDetails = $this->payroll_model->GetSalaryDetails();
			
			$html = '';
			$html .= '<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th style="text-align:center;" class="for-item-idth">EMP Code</th>';
			$html .= '<th style="text-align:center;" class="for-item-nameth">EMP Name</th>';
            $EHead = 0;
            $DHead = 0;
			foreach($SalaryHead as $Key=>$val){
				if($val['type']=="1"){
					$EHead++;
                    }else{
					$DHead++;
				}
			}
			$details_col = $DHead + $EHead + 6;
			$html .= '<th style="text-align:center;" class="for-item-nameth" >Net Salary</th>';
			//$html .= '<th style="text-align:center;" class="for-item-nameth" colspan="'.$DHead.'">Deductions</th>';
			$html .= '<th style="text-align:center;" class="for-item-nameth" colspan="2">Summary</th>';
			$html .= '<th style="text-align:center;" class="for-item-nameth" colspan="'.$details_col.'">Salary Details</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td style="text-align:center;" class="for-item-idth" colspan="2"></td>';
			foreach($SalaryHead as $Key1=>$val1){
				if($val1['code'] == "NET"){
					if($val1['mesuredIn']=="1"){
						$ValueType = "Amt"; 
						}else{
						$ValueType = "%"; 
					}
					$html .= '<td style="text-align:center;" class="for-item-idth"><b>'.$val1['code'].' ('.$ValueType.')'.'</b></td>';
				}
			}
			//$html .= '<td>Monthly Gross</td>';
			//$html .= '<td>Monthly Deduction</td>';
			$html .= '<td>Working Days</td>';
			$html .= '<td>Present Days</td>';
			$html .= '<td>Absent Days</td>';
            foreach($SalaryHead as $Key1=>$val1){
                if($val1['mesuredIn']=="1"){
					$ValueType = "Amt"; 
					}else{
                    $ValueType = "%"; 
				}
				$html .= '<td style="text-align:center;" class="for-item-idth"><b>'.$val1['code'].' ('.$ValueType.')'.'</b></td>';
			}
			$html .= '<td>Monthly Gross</td>';
			$html .= '<td>Monthly Deduction</td>';
			$html .= '<td>Net Amount</td>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody id="rate_update_table">';
			$html .= '<tr>';
			foreach($ActiveStaff as $staffKey=>$staffval){
				$html .= '<td style="text-align:left;" class="for-item-idth"><b>'.$staffval['AccountID'].'</b></td>';
				$html .= '<td style="text-align:left;" class="for-item-idth"><b>'.$staffval['firstname'].' '.$staffval['lastname'].'</b></td>';
				$deduction = 0;
				$earning = 0;
				$basic = 0;
				$NetPayable = 0;
				$NetAmt = 0;
				foreach($SalaryHead as $Key1=>$val1){
					if($val1['code'] == "NET"){
						$value = '';
						foreach($SalaryDetails as $salaryKey=>$salaryValue){
							if($staffval['AccountID'] == $salaryValue['AccountID'] && $val1['code']==$salaryValue['HeadID']){
								$value = $salaryValue['value'];
							}
						}
						$html .= '<td style="text-align:right;" class="for-item-idth">'.$value.'</td>';
					}
				}
				$NetPayable = $earning - $deduction;
				$YearlyCTC = $earning * 12;
				
				//$html .= '<td style="text-align:right;'.$css.'" id="total_earning_td_'.$staffval['AccountID'].'" ><span id="total_earning_html_'.$staffval['AccountID'].'">'.$earning.'</span><input type="hidden" name="total_earning_'.$staffval['AccountID'].'" id="total_earning_'.$staffval['AccountID'].'"></td>';
				//$html .= '<td style="text-align:right;'.$css.' " id="total_deduction_td_'.$staffval['AccountID'].'" ><span id="total_deduction_html_'.$staffval['AccountID'].'">'.$deduction.'</span><input type="hidden" name="total_deduction_'.$staffval['AccountID'].'" id="total_deduction_'.$staffval['AccountID'].'"></td>';
				$working = 30 ;
				$present = 20 ;
				$html .= '<td>'.$working.'</td>';
				$html .= '<td>'.$present.'</td>';
				$absent = $working-$present;
				$html .= '<td>'.$absent.'</td>';
				foreach($SalaryHead as $Key1=>$val1){
					$value = '';
					$css = '';
					$PRDayAmt = 0;
					if($val1['auto_calculate']=="Y"){
						$css = 'readonly';
					}
					
					foreach($SalaryDetails as $salaryKey=>$salaryValue){
						if($staffval['AccountID'] == $salaryValue['AccountID'] && $val1['code']==$salaryValue['HeadID']){
							$value = $salaryValue['value'];
						}
					}
					if($val1['type'] == '1' && $val1['code'] != "NET"){
						$earning += $value;
						}else if($val1['type'] == '2' && $val1['code'] != "NET"){
						$deduction += $value;
					}
					if($val1['code'] == "NET"){
						$NetAmt = $value;
					}
					$oneDayAmt = $value / $working;
					$PRDayAmt = $oneDayAmt * $present;
					//$html .= '<td style="text-align:right;" class="for-item-idth">'.$value.'</td>';
					$html .= '<td style="text-align:right;" class="for-item-idth"><input type="text"'.$css.' class="AmtEnter form-control" name="Amt_'.$staffval['AccountID'].'_'.$val1['code'].'" id="Amt_'.$staffval['AccountID'].'_'.$val1['code'].'" value="'.number_format($PRDayAmt, 2, '.', '').'" style="width: 80px;" onchange = "'.$functonName.'(this.id,this.value)"></td>';    
				}
				$html .= '<td></td>';
				$html .= '<td></td>';
				$html .= '<td></td>';
				$html .= '</tr>';
			}
            $html .= '</tbody>';
            $html .= '</table>'; 
			echo json_encode($html);
		}
		
		
		public function DeleteComponent(){
			
			$id = $this->input->post('id');
			$data = $this->payroll_model->DeleteComponent($id);
			echo json_encode($data);
		    
		}
		
		
		public function NewSalaryMaster()
		{
			if (!has_permission_new('salarymaster', '', 'view')) {
				access_denied('salaryHead');
			}
			if($this->input->post()){
				if (!has_permission_new('salarymaster', '', 'edit')) {
					access_denied('salaryHead');
				}
				$inputData = $this->input->post();
				$result = $this->payroll_model->SaveSalaryDetails($inputData);
				if($result){
					set_alert('success', 'Salary updated successfully');
					}else{
					set_alert('warning', 'something went wrong please try again.');
				}
				$redUrl = admin_url('payroll/NewSalaryMaster');
				redirect($redUrl);
				/*echo "<pre>";
					print_r($inputData);
				die;*/
			}
			$data['title'] = 'Salary Master';
			$data['ActiveStaff'] = $this->payroll_model->GetActiveStaff();
			$data['SalaryHead'] = $this->payroll_model->GetSalaryHead();
			$data['SalaryDetails'] = $this->payroll_model->GetSalaryDetails();
			/*echo "<pre>";
				print_r($data['SalaryDetails']);
			die;*/
			
			$this->load->view('admin/payroll/NewSalaryMaster',$data);     
		}
		
		
		public function StaffPayout()
		{
			if (!has_permission_new('StaffPayout', '', 'view')) {
				access_denied('StaffPayout');
			}
			
			$data['title'] = 'Staff Payout';
			$data['ActiveStaff'] = $this->payroll_model->GetActiveStaff();
			$data['SalaryHead'] = $this->payroll_model->GetSalaryHead();
			$data['SalaryDetails'] = $this->payroll_model->GetSalaryDetails();
			/*echo "<pre>";
				print_r($data['SalaryDetails']);
			die;*/
			
			$this->load->view('admin/payroll/StaffPayout',$data);     
		}
		
		public function StaffPayoutData()
		{
			
			$month = $this->input->post('Month');
			$selected_year = date('Y', strtotime($month . "-01")); // Extract year
			$selected_month = date('m', strtotime($month . "-01")); // Extract month
			if (!$month) {
				echo json_encode(['error' => 'Month are required']);
				return;
			}
			
			$firstDay = date("Y-m-01", strtotime($month));
			$lastDay = date("Y-m-t", strtotime($month));
			
			$start = new DateTime($firstDay);
			$end = new DateTime($lastDay);
			
			$totalDays = $end->format('t');
			
			if ($start > $end) {
				echo json_encode(['error' => 'End date should be greater than or equal to start date']);
				return;
			}
			
			$dates = [];
			while ($start <= $end) {
				$date = $start->format('d');
				$day = $start->format('M');
				$formatted_date = strtoupper($start->format('D'));
				$new_array = [
				"lebel" => $formatted_date . " " . $date . "-" . $day,
				"val" => $day,
				"dates" => $start->format('Y-m-d'),
				];
				array_push($dates, $new_array);
				$start->modify('+1 day');
			}
			
			
			$ActiveStaff = $this->payroll_model->GetActiveStaff();
			$Attendance_data = $this->hr_profile_model->GetMonthWiseAttendance(['month' => $month]);
			$SalaryDetails = $this->payroll_model->GetSalaryDetails();
			// echo "<pre>";print_r($Attendance_data);die;
			$html  = '<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" 
			id="table-daily_report" width="100%">';
			
			$html .= '<thead>
            <tr>
			<th class="sortablePop for-item-idth" style="text-align:center;">SR. No</th>
			<th class="sortablePop for-item-idth" style="text-align:center;">AccountID</th>
			<th class="sortablePop for-item-nameth" style="text-align:center;">Staff Name</th>
			<th class="sortablePop" style="text-align:center;">Total Days</th>
			<th class="sortablePop" style="text-align:center;">Holidays</th>
			<th class="sortablePop" style="text-align:center;">Weekly Off</th>
			<th class="sortablePop" style="text-align:center;">Total Working Days</th>
			<th class="sortablePop" style="text-align:center;">Present Days</th>
			<th class="sortablePop" style="text-align:center;">Absent Days</th>
			<th class="sortablePop" style="text-align:center;">Monthly Gross Salary Amt</th>
			<th class="sortablePop" style="text-align:center;">Monthly Deduction Amt</th>
			<th class="sortablePop" style="text-align:center;">Monthly Net Salary Amt</th>
			<th class="sortablePop" style="text-align:center;">Payable Salary</th>
			<th class="sortablePop" style="text-align:center;">Action</th>
            </tr>
			</thead>';
			
			$html .= '<tbody id="rate_update_table">';
			
			$sr = 1;
			foreach ($ActiveStaff as $staffKey => $staffval) {
				
				
				$TotalPresent = 0;
				$TotalHolidays = 0;
				$TotalWeekOff = 0;
				$TotalWorkingDays = $totalDays - $TotalHolidays - $TotalWeekOff;
				
				for ($day = 1; $day <= $totalDays; $day++) {
					$WorkingHr = 0;
					$date = date('Y-m-d', strtotime("$selected_year-$selected_month-$day"));
					$dayOfWeekNumber = date('N', strtotime("$selected_year-$selected_month-$day"));
					foreach ($Attendance_data as $each) {
						if ($staffval['AccountID'] == $each['AccountID'] && $date == date('Y-m-d', strtotime($each['InDateTime']))) {
							$inDateTime = $each['InDateTime'];
							$outDateTime = $each['OutDateTime'];
							$time1 = new DateTime(substr($inDateTime, 11, 19));
							$time2 = new DateTime(substr($outDateTime, 11, 19));
							$time_diff = $time1->diff($time2);
							$WorkingHr = $time_diff->h;
							
							
							break; // No need to check further
						}
					}
					if($WorkingHr > 0){
						if($WorkingHr >= $staffval['working_hour']){
							$TotalPresent = $TotalPresent+1;
							}else{
							$TotalPresent = $TotalPresent + 0.5;
						}
					}
				}
				
				$GrossSalary = 0; // CTC Amount 
				$NetSalary = 0;
				foreach($SalaryDetails as $salaryKey=>$salaryValue){
					if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'CB029'){
						$GrossSalary = $salaryValue['value'];
					}
					if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'CB030'){
						$NetSalary = $salaryValue['value'];
					}
					
				}
				
				$Payable = $NetSalary/$TotalWorkingDays;
				$NetPayable = $Payable*$TotalPresent;
				
				
				$html .= '<tr>';
				
				$html .= '<td style="text-align:left;" class="for-item-idth">
                <b>' . $sr . '</b>
				</td>';
				
				$html .= '<td style="text-align:left;" class="for-item-idth">
                <b>' . $staffval['AccountID'] . '</b>
                <input type="hidden" name="PF_' . $staffval['AccountID'] . '" 
				id="PF_' . $staffval['AccountID'] . '" 
				value="' . $staffval['IsPF'] . '">
                <input type="hidden" name="ESIC_' . $staffval['AccountID'] . '" 
				id="ESIC_' . $staffval['AccountID'] . '" 
				value="' . $staffval['IsESIC'] . '">
				</td>';
				
				$html .= '<td style="text-align:left;" class="for-item-idth">
                <b>' . $staffval['firstname'] . $staffval['lastname'] . '</b>
				</td>';
				
				// Empty calculated columns
				$html .= '<td align="center">'.$totalDays.'</td>'; // Total Days
				$html .= '<td align="center">'.$TotalHolidays.'</td>'; // Holidays
				$html .= '<td align="center">'.$TotalWeekOff.'</td>'; // Weekly Off
				$html .= '<td align="center">'.$TotalWorkingDays.'</td>'; // Total Working Days
				$html .= '<td align="center">'.$TotalPresent.'</td>'; // Present Days
				$html .= '<td align="center">'.($TotalWorkingDays - $TotalPresent).'</td>'; // Absent Days
				$html .= '<td align="right">'.number_format($GrossSalary, 0, '.', '').'</td>'; // Gross Salary
				$html .= '<td align="right">'.number_format(($GrossSalary-$NetSalary), 0, '.', '').'</td>'; // Deduction
				$html .= '<td align="right">'.number_format($NetSalary, 0, '.', '').'</td>'; // Net Salary
				$html .= '<td align="right">'.number_format($NetPayable, 0, '.', '').'</td>'; // Net Payable
				$html .= '<td align="right"><button type="button" class="btn btn-sm btn-primary openPopup" data-id="' . $staffval['AccountID'] . '"><i class="fa fa-check-square-o"></i></button></td>'; 
				
				$html .= '</tr>';
				
				$sr++;
			}
			
			$html .= '</tbody>';
			$html .= '</table>';
			echo $html;
		}
		
		public function GetStaffPayoutMonthlyData()
		{
			
			$month = $this->input->post('Month');
			$AccountID = $this->input->post('AccountID');
			$selected_year = date('Y', strtotime($month . "-01")); // Extract year
			$selected_month = date('F', strtotime($month . "-01")); // Extract month
			if (!$month) {
				echo json_encode(['error' => 'Month are required']);
				return;
			}
			
			$firstDay = date("Y-m-01", strtotime($month));
			$lastDay = date("Y-m-t", strtotime($month));
			
			$start = new DateTime($firstDay);
			$end = new DateTime($lastDay);
			
			$totalDays = $end->format('t');
			
			
			$dates = [];
			while ($start <= $end) {
				$date = $start->format('d');
				$day = $start->format('M');
				$formatted_date = strtoupper($start->format('D'));
				$new_array = [
				"lebel" => $formatted_date . " " . $date . "-" . $day,
				"val" => $day,
				"dates" => $start->format('Y-m-d'),
				];
				array_push($dates, $new_array);
				$start->modify('+1 day');
			}
			
			
			$Staff = $this->payroll_model->GetStaffDetailByAccountID($AccountID);
			$Attendance_data = $this->hr_profile_model->GetMonthWiseAttendance(['month' => $month]);
			$SalaryDetails = $this->payroll_model->GetSalaryDetails();
			// echo "<pre>";print_r($Attendance_data);die;
			$html  = '<table class="tree table table-striped table-bordered" width="100%" style="border-collapse:collapse;">';
			
			$html .= '<tr>
            <th colspan="6" style="text-align:center;font-size:20px;">Salary Slip</th>
			</tr>';
			
			$html .= '<tr>
            <th style="width:15%;">Employee Name :</th>
            <th colspan="3">'.$Staff['firstname'] . $Staff['lastname'].'</th>
            <th style="width:10%;">Month</th>
            <th>'.$selected_month.'</th>
			</tr>';
			
			$html .= '<tr>
            <th>Designation :</th>
            <th colspan="3">'.$Staff['position_name'].'</th>
            <th>Year</th>
            <th>'.$selected_year.'</th>
			</tr>';
			
			$html .= '<tr>
            <th>EARNING</th>
            <th>Full (Amt)</th>
            <th>Actual (Amt)</th>
            <th>DEDUCTIONS</th>
            <th>Full (Amt)</th>
            <th>Actual (Amt)</th>
			</tr>';
			
			$html .= '<tr>
            <td>Basic</td>
            <td align="right">15000</td>
            <td align="right">726</td>
            <td>Profession Tax</td>
            <td align="right">0</td>
            <td align="right">-</td>
			</tr>';
			
			$html .= '<tr>
            <td>HRA</td>
            <td align="right">6000</td>
            <td align="right">290</td>
            <td>Employee PF</td>
            <td align="right">1800</td>
            <td align="right">87</td>
			</tr>';
			
			$html .= '<tr>
            <td>Medical Allowance</td>
            <td align="right">0</td>
            <td align="right">-</td>
            <td>Employee ESIC</td>
            <td align="right">157.50</td>
            <td align="right">8</td>
			</tr>';
			
			$html .= '<tr>
            <td>Conveyance Allowance</td>
            <td align="right">0</td>
            <td align="right">-</td>
            <td colspan="3"></td>
			</tr>';
			
			$html .= '<tr>
            <td>Vehicle Allowance</td>
            <td align="right">0</td>
            <td></td>
            <td colspan="3"></td>
			</tr>';
			
			$html .= '<tr>
            <td>Washing Allowance</td>
            <td align="right">0</td>
            <td></td>
            <td colspan="3"></td>
			</tr>';
			
			$html .= '<tr>
            <td>Other Allowance</td>
            <td align="right">0</td>
            <td></td>
            <td colspan="3"></td>
			</tr>';
			
			$html .= '<tr>
            <th>Gross Pay</th>
            <th align="right">21000</th>
            <th align="right">1016</th>
            <th>Total Deductions</th>
            <th colspan="2" align="right">95</th>
			</tr>';
			
			$html .= '<tr>
            <th colspan="3"></th>
            <th>Net Pay</th>
            <th colspan="2" align="right">921</th>
			</tr>';
			
			$html .= '<tr>
            <th>Compliances</th>
            <th>Full (Amt)</th>
            <th>Actual (Amt)</th>
			</tr>';
			
			$html .= '<tr>
            <td>Employer PF</td>
            <td align="right">1950</td>
            <td align="right">94</td>
			</tr>';
			
			$html .= '<tr>
            <td>Employer ESIC</td>
            <td align="right">683</td>
            <td align="right">33</td>
			</tr>';
			
			$html .= '<tr>
            <th>CTC</th>
            <th align="right">23632.5</th>
            <th align="right">1143.4</th>
			</tr>';
			
			$html .= '<tr>
            <td colspan="2">Checked By</td>
            <td colspan="2">Authorized By</td>
            <td colspan="2">Received By</td>
			</tr>';
			
			$html .= '</table>';
			
			echo $html;
		}
	}							