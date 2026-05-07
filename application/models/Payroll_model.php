<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class Payroll_model extends App_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_company_detail()
		{   
			$selected_company = $this->session->userdata('root_company');
			$sql ='SELECT '.db_prefix().'rootcompany.*
			FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
			$result = $this->db->query($sql)->row();
			return $result;
			
		}
		
		public function get_head_data()
		{
			$this->db->order_by(db_prefix() . 'salary_head.CalcualtedFor', 'ASC');
			$this->db->order_by(db_prefix() . 'salary_head.SequenceNo', 'ASC');
			return $this->db->get(db_prefix().'salary_head')->result_array();
		}
		
		public function get_salary_head_details($headCode)
		{  
			$sql ='SELECT '.db_prefix().'salary_head.*
			FROM '.db_prefix().'salary_head WHERE code  = '."'$headCode'";
			$result = $this->db->query($sql)->row();
			return $result;
			
		}
		
		public function SaveHead($data)
		{
			$this->db->insert(db_prefix() . 'salary_head', $data);
			$INSERT = $this->db->affected_rows();
			if($INSERT > 0){
				return true;    
				}else{
				return false;
			}
		}
		
		public function UpdateHead($data,$HeadCode)
		{
			$this->db->where('code', $HeadCode);
			$this->db->update(db_prefix() . 'salary_head', $data);
			$UPDATE = $this->db->affected_rows();        
			if($UPDATE > 0){
				return true;
				}else{
				return false;
			}
		}
		
        public function DeleteComponent($code)
        {
			$this->db->where('code', $code);
			if($this->db->delete(db_prefix() . 'salary_head')){
				return true;
				}else{
				return false;
			}
		}
		public function GetActiveStaff()
		{
			$AccountID = array('GIC','admin','GIC7','MAN');
			$selected_company = $this->session->userdata('root_company');
			$this->db->select('tblstaff.*');
			$this->db->from(db_prefix() . 'staff');
			$this->db->order_by('tblstaff.AccountID', 'ASC');
			$this->db->where('tblstaff.active','1');
			$this->db->where('tblstaff.EmpType','ON-ROLL');
			$this->db->where_not_in('tblstaff.AccountID',$AccountID);
			$this->db->where('tblstaff.PlantID',$selected_company);
			return $this->db->get()->result_array();
		}
		public function GetStaffDetailByAccountID($AccountID)
		{
			$selected_company = $this->session->userdata('root_company');
			$this->db->select('tblstaff.*,tblhr_job_position.position_name');
			$this->db->from(db_prefix() . 'staff');
			$this->db->join(db_prefix() . 'hr_job_position', '' . db_prefix() . 'hr_job_position.position_id = ' . db_prefix() . 'staff.job_position', 'left');
			$this->db->where('tblstaff.AccountID',$AccountID);
			$this->db->where('tblstaff.PlantID',$selected_company);
			return $this->db->get()->row_array();
		}
		public function GetSalaryHead()
		{
			$selected_company = $this->session->userdata('root_company');
			$this->db->select('tblsalary_head.*');
			$this->db->from(db_prefix() . 'salary_head');
			$this->db->order_by('tblsalary_head.SequenceNo', 'ASC');
			return $this->db->get()->result_array();
		}
		
		public function GetSalaryDetails()
		{
			$selected_company = $this->session->userdata('root_company');
			$this->db->select('tblsalaryMaster.*');
			$this->db->from(db_prefix() . 'salaryMaster');
			$this->db->where('tblsalaryMaster.isactive', 1);
			$this->db->where('tblsalaryMaster.PlantID', $selected_company);
			return $this->db->get()->result_array();
		}
		
		public function GetSalaryStructure($AccountID,$HeadID)
		{
			$selected_company = $this->session->userdata('root_company');
			$this->db->select('tblsalaryMaster.*');
			$this->db->from(db_prefix() . 'salaryMaster');
			$this->db->where('tblsalaryMaster.isactive', 1);
			$this->db->where('tblsalaryMaster.PlantID', $selected_company);
			$this->db->where('tblsalaryMaster.AccountID', $AccountID);
			$this->db->where('tblsalaryMaster.HeadID', $HeadID);
			return $this->db->get()->row();
		}
		
		public function SaveSalaryDetails($inputData)
		{
			$selected_company = $this->session->userdata('root_company');
			$UserID = $this->session->userdata('username');
			$affected_row = 0;
			$date = date('Y-m-d H:i:s');
			// echo "<pre>";
			// print_r($inputData);
			// die;
			foreach($inputData as $Key=>$value){
				$Keyarray = explode('_', $Key);
				//print_r($Keyarray);
				$AccountID = $Keyarray[1];
				$HeadID = $Keyarray[2];
				
				if($value != ""){
					
					$GetDetails = $this->GetSalaryStructure($AccountID,$HeadID);
					if($GetDetails){
						if($GetDetails->value == $value){
							
							}else{
							$this->db->set('UserID2', $UserID);
							$this->db->set('Lupdate', $date);
							$this->db->set('isactive', '0');
							$this->db->where('AccountID', $AccountID);
							$this->db->where('HeadID', $HeadID);
							$this->db->where('isactive', 1);
							if($this->db->update(db_prefix() . 'salaryMaster')){
								$date_array = array(
                                "PlantID"=>$selected_company,  
                                "AccountID"=>$AccountID,  
                                "HeadID"=>$HeadID,  
                                "value"=>$value,  
                                "isactive"=>1,  
                                "UserID"=>$UserID,  
                                "TransDate"=>date('Y-m-d H:i:s'),  
								);
								$this->db->insert(db_prefix() . 'salaryMaster', $date_array);
								$insert_id = $this->db->insert_id();
								if ($insert_id) {
									$affected_row++;
								}
							}
						}
						}else{
						$date_array = array(
                        "PlantID"=>$selected_company,  
                        "AccountID"=>$AccountID,  
                        "HeadID"=>$HeadID,  
                        "value"=>$value,  
                        "isactive"=>1,  
                        "UserID"=>$UserID,  
                        "TransDate"=>date('Y-m-d H:i:s'),  
						);
						$this->db->insert(db_prefix() . 'salaryMaster', $date_array);
						$insert_id = $this->db->insert_id();
						if ($insert_id) {
							$affected_row++;
						}
					}
					
				}else{
					$this->db->set('UserID2', $UserID);
					$this->db->set('Lupdate', $date);
					$this->db->set('isactive', '0');
					$this->db->where('AccountID', $AccountID);
					$this->db->where('HeadID', $HeadID);
					$this->db->where('isactive', 1);
					if($this->db->update(db_prefix() . 'salaryMaster')){
						$affected_row++;
					}
				}
			}
			//die;
			if($affected_row > 0){
				return true;
			}
			return false;
		}
	}		