<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
    input:focus{
	outline: none;
	box-shadow: none;
	border: none;
	}
	.for-item-id{
    position: sticky !important;
    left: 0;
    width: 43px;
    background-color:#fff;
    }
	.for-item-name{
    position: sticky;
    width: 81px;
    left: 43px;
    background-color:#fff;
    }
    
	.for-item-idth{
    position: sticky !important;
    left: 0;
    width: 43px;
    }
    
    .for-item-nameth{
	position: sticky;
    width: 81px;
    left: 43px;
    }
	
	.table-daily_report {
    max-height: 60vh;
    overflow: auto;
    position: relative;
	}
	
	.table-daily_report {
    border-collapse: separate;
    border-spacing: 0;
	}
	/* Second header row */
	.table-daily_report thead tr:nth-child(2) th,
	.table-daily_report thead tr:nth-child(2) td {
    position: sticky;
    top: 18px; /* height of first header row */
    z-index: 4;
	background: #50607b;
    color: #fff !important;
	box-shadow: inset 0 -1px 0 #dee2e6;
	}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12 text-centerr"  >
								<nav aria-label="breadcrumb" >
									<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
										<li class="breadcrumb-item" ><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
										<li class="breadcrumb-item active text-capitalize"><b>HR</b></li>
										<li class="breadcrumb-item active" aria-current="page"><b>Salary Master</b></li>
										
									</ol>
								</nav>
								<hr style="margin-Bottom:12px !important;">
							</div>
						</div>
						<div class="row">
							
							<div class="col-md-12">
								<?php
									echo form_open($this->uri->uri_string(),array('id'=>'salary_form','class'=>'_transaction_form invoice-form'));
								?>
								
								<div class="table-daily_report tableFixHead2">
									<input type="hidden" name="error_log" id="error_log" value="">
									<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
										<thead>
											<tr>
												<th class="sortablePop" style="text-align:center;" class="for-item-idth">SR. No</th>
												<th class="sortablePop" style="text-align:center;" class="for-item-idth" >EMP Code</th>
												<th class="sortablePop" style="text-align:center;" class="for-item-nameth">EMP Name</th>
												<?php
													$GrossSalaryHead = 1;
													$CTCHead = 1;
													$NetSalaryHead = 1;
													foreach($SalaryHead as $Key=>$val){
														if($val['CalcualtedFor']=="1"){
															$GrossSalaryHead++;
														}
														if($val['CalcualtedFor']=="2"){
															$CTCHead++;
														}
														if($val['CalcualtedFor']=="3"){
															$NetSalaryHead++;
														}
													}
												?>
												<th style="text-align:center;" class="for-item-nameth" colspan="<?php echo $GrossSalaryHead; ?>">Gross Salary</th>
												<th style="text-align:center;" class="for-item-nameth" colspan="<?php echo $CTCHead; ?>">CTC</th>
												<th style="text-align:center;" class="for-item-nameth" colspan="<?php echo $NetSalaryHead; ?>">Net Salary</th>
											</tr>
											<tr>
												<td style="text-align:center;" class="for-item-idth" colspan="3"></td>
												<?php
													foreach($SalaryHead as $Key1=>$val1){
														if($val1['CalcualtedFor']== "1"){
															
														?>
														<td style="text-align:center;min-width: 90px;" class="for-item-idth"><b><?php echo $val1['name'];?></b></td>
														
														<?php
														}
													}
												?>
												<td style="text-align:center;min-width: 90px; color:white;background-color:red;" class="for-item-idth"><b>Yearly Gross Salary</b></td>
												<?php
													foreach($SalaryHead as $Key1=>$val1){
														if($val1['CalcualtedFor']== "2"){
															
														?>
														<td style="text-align:center;min-width: 90px;" class="for-item-idth"><b><?php echo $val1['name'];?></b></td>
														
														<?php
														}
													}
												?>
												<td style="text-align:center;min-width: 90px; color:white;background-color:red;" class="for-item-idth"><b>Yearly CTC</b></td>
												<?php
													foreach($SalaryHead as $Key1=>$val1){
														if($val1['CalcualtedFor']== "3"){
															
														?>
														<td style="text-align:center;min-width: 90px;" class="for-item-idth"><b><?php echo $val1['name'];?></b></td>
														
														<?php
														}
													}
												?>
												<td style="text-align:center;min-width: 90px; color:white;background-color:red;" class="for-item-idth"><b>Net Yearly Salary</b></td>
												
											</tr>
										</thead>
										<tbody id="rate_update_table">
											<?php
												$sr = 1;
												foreach($ActiveStaff as $staffKey=>$staffval){
												?>
												<tr>
													
													<td style="text-align:left;" class="for-item-idth"><b><?php echo $sr;?></b></td>
													<td style="text-align:left;" class="for-item-idth"><b><?php echo $staffval['AccountID'];?></b>
														<input type="hidden" name="PF_<?php echo $staffval['AccountID'];?>" id="PF_<?php echo $staffval['AccountID'];?>" value="<?php echo $staffval["IsPF"];?>">
													<input type="hidden" name="ESIC_<?php echo $staffval['AccountID'];?>" id="ESIC_<?php echo $staffval['AccountID'];?>" value="<?php echo $staffval["IsESIC"];?>"></td>
													<td style="text-align:left;" class="for-item-idth"><b><?php echo $staffval['firstname'].$staffval['lastname'];?></b></td>
													<?php
														$GrossTotal = '';
														$GrossTotalYearly = '';
														$CTC = '';
														$CTCYearly = '';
														$NetSalary = '';
														$NetSalaryYearly = '';
														foreach($SalaryHead as $Key1=>$val1){
															if($val1['CalcualtedFor']== "1"){
																$css = '';
																if($val1['mesuredIn']== "2" || $val1['mesuredIn']== "3"){
																	$css = 'readonly';
																}
																
																$value = '';
																foreach($SalaryDetails as $salaryKey=>$salaryValue){
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $val1['code']==$salaryValue['HeadID']){
																		$value = $salaryValue['value'];
																	}
																	
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'GrossTotal'){
																		$GrossTotal = $salaryValue['value'];
																	}
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'GrossTotalYearly'){
																		$GrossTotalYearly = $salaryValue['value'];
																	}
																	
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'CTC'){
																		$CTC = $salaryValue['value'];
																	}
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'CTCYearly'){
																		$CTCYearly = $salaryValue['value'];
																	}
																	
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'NetSalary'){
																		$NetSalary = $salaryValue['value'];
																	}
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $salaryValue['HeadID'] == 'NetSalaryYearly'){
																		$NetSalaryYearly = $salaryValue['value'];
																	}
																}
															?>
															<td style="text-align:right;" class="for-item-idth"><input type="text"  class="AmtEnter form-control" <?= $css;?> name="Amt_<?php echo $staffval['AccountID']?>_<?php echo $val1['code']?>" id="Amt_<?php echo $staffval['AccountID']?>_<?php echo $val1['code']?>" value="<?php echo $value;?>" style="width: 100%;" onchange = "calculateSalary(this.id)"></td>
															<?php
															}
														}
													?>
													<td style="text-align:right;" class="for-item-idth"><input type="text"  class="AmtEnter form-control" readonly name="Amt_<?php echo $staffval['AccountID']?>_GrossTotalYearly" id="Amt_<?php echo $staffval['AccountID']?>_GrossSalaryYearly" value="<?php echo $GrossTotalYearly;?>" style="width: 100%;"></td>
													<?php
														foreach($SalaryHead as $Key1=>$val1){
															if($val1['CalcualtedFor']== "2"){
																$css = '';
																if($val1['mesuredIn']== "2" || $val1['mesuredIn']== "3"){
																	$css = 'readonly';
																}
																$value = '';
																foreach($SalaryDetails as $salaryKey=>$salaryValue){
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $val1['code']==$salaryValue['HeadID']){
																		$value = $salaryValue['value'];
																	}
																	
																}
															?>
															<td style="text-align:right;"  class="for-item-idth"><input type="text"  class="AmtEnter form-control" <?= $css;?> name="Amt_<?php echo $staffval['AccountID']?>_<?php echo $val1['code']?>" id="Amt_<?php echo $staffval['AccountID']?>_<?php echo $val1['code']?>" value="<?php echo $value;?>" style="width: 100%;" onchange = "calculateSalary(this.id)"></td>
															<?php
															}
														}
													?>
													<td style="text-align:right;" class="for-item-idth"><input type="text"  class="AmtEnter form-control" readonly name="Amt_<?php echo $staffval['AccountID']?>_CTCYearly" id="Amt_<?php echo $staffval['AccountID']?>_CTCYearly" value="<?php echo $CTCYearly;?>" style="width: 100%;"></td>
													<?php
														foreach($SalaryHead as $Key1=>$val1){
															if($val1['CalcualtedFor']== "3"){
																$css = '';
																if($val1['mesuredIn']== "2" || $val1['mesuredIn']== "3"){
																	$css = 'readonly';
																}
																$value = '';
																foreach($SalaryDetails as $salaryKey=>$salaryValue){
																	if($staffval['AccountID'] == $salaryValue['AccountID'] && $val1['code']==$salaryValue['HeadID']){
																		$value = $salaryValue['value'];
																	}
																	
																}
															?>
															<td style="text-align:right;" class="for-item-idth"><input type="text"  class="AmtEnter form-control" <?= $css;?> name="Amt_<?php echo $staffval['AccountID']?>_<?php echo $val1['code']?>" id="Amt_<?php echo $staffval['AccountID']?>_<?php echo $val1['code']?>" value="<?php echo $value;?>" style="width: 100%;" onchange = "calculateSalary(this.id)"></td>
															<?php
															}
														}
													?>
													<td style="text-align:right;" class="for-item-idth"><input type="text"  class="AmtEnter form-control" readonly name="Amt_<?php echo $staffval['AccountID']?>_NetSalaryYearly" id="Amt_<?php echo $staffval['AccountID']?>_NetSalaryYearly" value="<?php echo $NetSalaryYearly;?>" style="width: 100%;"></td>
													
												</tr>
												<?php
													$sr++;
												}
											?>
											
										</tbody>
									</table>   
									
								</div>
								<div class="btn-bottom-toolbar text-right">
									<button type="button" class="btn btn-info saveBtn" onclick="SubmitFormData()" style="margin-right: 25px;">Update</button>
									<button type="button" class="btn btn-default cancelBtn">Cancel</button>
								</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php init_tail(); ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
	<script type="text/javascript">
		$('.AmtEnter').on('keypress',function (event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
				event.preventDefault();
			}
			var input = $(this).val();
			if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
				event.preventDefault();
			}
		});
		$('.cancelBtn').on('click',function () {
			window.location.reload(true);
		})
	</script>
	
	<script>
		function changeAmt(id,val) {
			var value = $("#"+id).val();
			let id_array = id.split('_');
			var staffName = id_array[0]+'_'+id_array[1];
			//alert(val);
			if(value == ""){
				$("#"+id).val(0);
				var NetID = staffName+'_NET';
				var Net_Payable = $("#"+NetID).val();
				calculateSalary(Net_Payable,id,val);
				}else{
				var NetID = staffName+'_NET';
				var Net_Payable = $("#"+NetID).val();
				calculateSalary(Net_Payable,id,val);
			}
		}
		function calculateSalary(id) {
			
			let id_array = id.split('_');
			
			var staffName  = id_array[0] + '_' + id_array[1];
			var AccountID  = id_array[1];
			var Is_PF   = $("#PF_" + AccountID).val();
			var Is_ESIC = $("#ESIC_" + AccountID).val();
			var Comparray  = <?php echo json_encode($SalaryHead); ?>;
			
			let TotalGross      = 0;
			let TotalCTC        = 0;
			let TotalNetSalary  = 0;
			var SumComponentCode = "";
			for (var i = 0; i < Comparray.length; i++) {
				var value = 0;
				// Calculate gross Salary
				var CurrentHeadID = staffName + '_' + Comparray[i]['code'];
				if (Comparray[i]['CalcualtedFor'] == "1") {
					if (Comparray[i]['mesuredIn'] == "2") {
						var calculatedBy = Comparray[i]['calculatedBy'];
						var CalculateFrom = staffName + '_' + calculatedBy;
						var CalculateFromVal = parseFloat($("#" + CalculateFrom).val()) || 0;
						var percentage = parseFloat(Comparray[i]['percentage']) || 0;
						var MaxAmt = parseFloat(Comparray[i]['MaxAmt']) || 0;
						
						if (MaxAmt > 0 && CalculateFromVal > MaxAmt) {
							CalculateFromVal = MaxAmt;
						}
						
						value = Math.round((CalculateFromVal * percentage) / 100);
						$("#" + CurrentHeadID).val(value > 0 ? value : '0');
						}else if(Comparray[i]['mesuredIn'] == "1"){
						value = parseFloat($("#" + CurrentHeadID).val()) || 0;
						}else if(Comparray[i]['mesuredIn'] == "3"){
						SumComponentCode = CurrentHeadID;
					}
					
					if(Comparray[i]['type'] == "1"){
						TotalGross += value;
						}else{
						TotalGross -= value;
					}
				}
			}
			$("#" + SumComponentCode).val(TotalGross > 0 ? TotalGross : '');
			SumComponentCode = "";
			for (var i = 0; i < Comparray.length; i++) {
				var value = 0;
				var CurrentHeadID = staffName + '_' + Comparray[i]['code'];
				if ((Comparray[i]['code'] == 'CB024' || Comparray[i]['code'] == 'CB026') && Is_PF == 'N') {
					$("#" + CurrentHeadID).val('');
					value = 0;
					continue;
				}
				
				// ESIC
				if ((Comparray[i]['code'] == 'CB025' || Comparray[i]['code'] == 'CB027') && Is_ESIC == 'N') {
					$("#" + CurrentHeadID).val('');
					value = 0;
					continue;
				}
				
				if (Comparray[i]['CalcualtedFor'] == "2") {
					if (Comparray[i]['mesuredIn'] == "2") {
						var calculatedBy = Comparray[i]['calculatedBy'];
						var CalculateFrom = staffName + '_' + calculatedBy;
						var CalculateFromVal = parseFloat($("#" + CalculateFrom).val()) || 0;
						var percentage = parseFloat(Comparray[i]['percentage']) || 0;
						var MaxAmt = parseFloat(Comparray[i]['MaxAmt']) || 0;
						
						if (MaxAmt > 0 && CalculateFromVal > MaxAmt) {
							CalculateFromVal = 0;
						}
						
						value = Math.round((CalculateFromVal * percentage) / 100);
						$("#" + CurrentHeadID).val(value > 0 ? value : '0');
						}else if(Comparray[i]['mesuredIn'] == "1"){
						value = parseFloat($("#" + CurrentHeadID).val()) || 0;
						}else if(Comparray[i]['mesuredIn'] == "3"){
						SumComponentCode = CurrentHeadID;
					}
					if(Comparray[i]['type'] == "1"){
						TotalCTC += value;
						}else{
						TotalCTC -= value;
					}
				}
			}
			TotalCTC += TotalGross;
			
			$("#" + SumComponentCode).val(TotalCTC > 0 ? TotalCTC : '');
			SumComponentCode = "";
			for (var i = 0; i < Comparray.length; i++) {
				var value = 0;
				var CurrentHeadID = staffName + '_' + Comparray[i]['code'];
				
				if ((Comparray[i]['code'] == 'CB024' || Comparray[i]['code'] == 'CB026') && Is_PF == 'N') {
					$("#" + CurrentHeadID).val('');
					value = 0;
					continue;
				}
				
				// ESIC
				if ((Comparray[i]['code'] == 'CB025' || Comparray[i]['code'] == 'CB027') && Is_ESIC == 'N') {
					$("#" + CurrentHeadID).val('');
					value = 0;
					continue;
				}
				
				if (Comparray[i]['CalcualtedFor'] == "3") {
					if (Comparray[i]['mesuredIn'] == "2") {
						var calculatedBy = Comparray[i]['calculatedBy'];
						var CalculateFrom = staffName + '_' + calculatedBy;
						var CalculateFromVal = parseFloat($("#" + CalculateFrom).val()) || 0;
						var percentage = parseFloat(Comparray[i]['percentage']) || 0;
						var MaxAmt = parseFloat(Comparray[i]['MaxAmt']) || 0;
						
						if (MaxAmt > 0 && CalculateFromVal > MaxAmt) {
							CalculateFromVal = 0;
						}
						
						value = Math.round((CalculateFromVal * percentage) / 100);
						$("#" + CurrentHeadID).val(value > 0 ? value : '0');
						}else if(Comparray[i]['mesuredIn'] == "1"){
						value = parseFloat($("#" + CurrentHeadID).val()) || 0;
						}else if(Comparray[i]['mesuredIn'] == "3"){
						SumComponentCode = CurrentHeadID;
					}
					if(Comparray[i]['type'] == "1"){
						TotalNetSalary += value;
						}else{
						TotalNetSalary -= value;
					}
					
				}
				
			}
			TotalNetSalary += TotalCTC;
			$("#" + SumComponentCode).val(TotalNetSalary > 0 ? TotalNetSalary : '0');
			
			
			
			
			
			/*
				// PF
				if ((Comparray[i]['code'] == 'CB024' || Comparray[i]['code'] == 'CB026') && Is_PF == 'N') {
				$("#" + CurrentHeadID).val('');
				value = 0;
				continue;
				}
				
				// ESIC
				if ((Comparray[i]['code'] == 'CB025' || Comparray[i]['code'] == 'CB027') && Is_ESIC == 'N') {
				$("#" + CurrentHeadID).val('');
				value = 0;
				continue;
			}*/
			/*
				if (Comparray[i]['mesuredIn'] == "2") {
				
				var calculatedBy = Comparray[i]['calculatedBy'];
				var CalculateFrom = staffName + '_' + calculatedBy;
				var CalculateFromVal = parseFloat($("#" + CalculateFrom).val()) || 0;
				
				var percentage = parseFloat(Comparray[i]['percentage']) || 0;
				var MaxAmt = parseFloat(Comparray[i]['MaxAmt']) || 0;
				
				if (MaxAmt > 0 && CalculateFromVal > MaxAmt) {
				CalculateFromVal = MaxAmt;
				}
				
				value = Math.round((CalculateFromVal * percentage) / 100);
				
				$("#" + CurrentHeadID).val(value > 0 ? value : '');
				}
				
				if (Comparray[i]['mesuredIn'] == "3") {
				
				var calculatedByArr = Comparray[i]['calculatedBy'].split(',');
				
				var totalCalculatedValue = 0;
				
				$.each(calculatedByArr, function (_, calculatedBy) {
				var CalculateFrom = staffName + '_' + calculatedBy.trim();
				totalCalculatedValue += parseFloat($("#" + CalculateFrom).val()) || 0;
				
				});
				
				
				value = Math.round(totalCalculatedValue);
				
				
				$("#" + CurrentHeadID).val(value > 0 ? value : '');
				}
				
				value = parseFloat(value) || 0;
				
				if (Comparray[i]['mesuredIn'] != "3") {
				if (Comparray[i]['CalcualtedFor'] == "1") {
				TotalGross += value;
				}
				
				if (Comparray[i]['CalcualtedFor'] == "2") {
				TotalCTC += value;
				}
				
				if (Comparray[i]['CalcualtedFor'] == "3") {
				TotalNetSalary += value;
				}
				}
				*/
				$("#" + staffName + "_GrossSalaryYearly").val(TotalGross * 12);
				$("#" + staffName + "_CTCYearly").val(TotalCTC * 12);
			$("#" + staffName + "_NetSalaryYearly").val(TotalNetSalary * 12);
		}
		
		function changeAmount(id,val) 
		{
			let id_array = id.split('_');
			var InputID = id_array[2];
			let InputValue = val;
			var staffName = id_array[0]+'_'+id_array[1];
			var AccountID = id_array[1];
			var IsPFID = "PF_"+AccountID;
			var isPF = $("#"+IsPFID).val();
			// Get Net Salary Amt 
			var NetSalaryID = "Amt_"+AccountID+"_NET";
			let NetAmt = $("#"+NetSalaryID).val();
			if(isPF == "Y"){
				// Claculate Employee PF
				let EMP_PF = parseFloat(NetAmt) * (12 / 100);
				let PF = parseFloat(NetAmt) * (12 / 100);
				}else{
				let EMP_PF = 0;
				let PF = 0;
			}
			alert(isPF);
			let TotalDeduction = parseFloat(PF) + parseFloat(EMP_PF);
			let TotalGrossAmt = parseFloat(NetAmt) + parseFloat(EMP_PF);
			let BasicAmt = parseFloat(TotalGrossAmt) * (55 / 100);
			let HRAAmt = parseFloat(BasicAmt) * (40 / 100);
			let CTC = parseFloat(NetAmt) + parseFloat(PF) + parseFloat(EMP_PF);
			let YrCTC = parseFloat(CTC) * 12;
			let MA = InputValue;
			//var MedicalID = staffName+"_MA";
			$("#"+id).val(parseFloat(MA).toFixed(2));
			// Calculate Other
			let OthAmt = parseFloat(TotalGrossAmt) - parseFloat(BasicAmt) - parseFloat(HRAAmt) - parseFloat(MA);
			var OthID = staffName+"_OTH";
			$("#"+OthID).val(parseFloat(OthAmt).toFixed(2));
			
			let NetPayable = parseFloat(CTC) - parseFloat(TotalDeduction);
			if(NetAmt != NetPayable){
				var log = $('#error_log').val();
				if(log != ""){
					let AccountIDs = log.split(',');
					}else{
					var AccountIDs = [];
				}
				var isInArray = AccountIDs.includes(AccountID);
				if(isInArray == false){
					AccountIDs.push(AccountID);
				}
				var text = AccountIDs.toString();
				$('#error_log').val(text);
				$("#total_earning_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
				$("#total_deduction_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
				$("#total_net_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
				$("#total_ctc_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
				$("#yearly_ctc_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
				}else{
				$("#total_earning_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
				$("#total_deduction_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
				$("#total_net_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
				$("#total_ctc_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
				$("#yearly_ctc_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
				var log = $('#error_log').val();
				if(log != ""){
					let AccountIDs = log.split(',');
					}else{
					let AccountIDs = [];
				}
				
				var isInArray = AccountIDs.includes(AccountID);
				if(isInArray == true){
					AccountIDs.splice(AccountIDs.indexOf(AccountID), 1);
				}
				let text = AccountIDs.toString();
				$('#error_log').val(text);
			}
		}
		
		function changeValue(id,val) {
			var value = $("#"+id).val();
			let id_array = id.split('_');
			var staffName = id_array[0]+'_'+id_array[1];
			var AccountID = id_array[1];
			if(value == ""){
				$("#"+id).val(0);
				}else{
				var IsPFID = "PF_"+AccountID;
				var isPF = $("#"+IsPFID).val();
				let EMP_PF = 0;
				let PF = 0;
				if(isPF == "Y"){
					// Claculate Employee PF
					EMP_PF = parseFloat(value) * (12 / 100);
					PF = parseFloat(value) * (12 / 100);
				}
				
				let TotalDeduction = parseFloat(PF) + parseFloat(EMP_PF);
				let TotalGrossAmt = parseFloat(value) + parseFloat(EMP_PF);
				let BasicAmt = parseFloat(TotalGrossAmt) * (55 / 100);
				let HRAAmt = parseFloat(BasicAmt) * (40 / 100);
				let CTC = parseFloat(value) + parseFloat(PF) + parseFloat(EMP_PF);
				let YrCTC = parseFloat(CTC) * 12;
				let NetPayable = parseFloat(CTC) - parseFloat(TotalDeduction);
				var BasicID = staffName+"_BASIC";
				$("#"+BasicID).val(parseFloat(BasicAmt).toFixed(2));
				
				var HRAID = staffName+"_HRA";
				$("#"+HRAID).val(parseFloat(HRAAmt).toFixed(2));
				
				
				var GrossID = "total_earning_"+AccountID;
				var GrossID2 = "total_earning_html_"+AccountID;
				$("#"+GrossID).val(parseFloat(TotalGrossAmt).toFixed(2));
				$("#"+GrossID2).html(parseFloat(TotalGrossAmt).toFixed(2));
				// Calculate Medical
				let MA = 0;
				var MedicalID = staffName+"_MA";
				$("#"+MedicalID).val(parseFloat(MA).toFixed(2));
				// Calculate Other
				let OthAmt = parseFloat(TotalGrossAmt) - parseFloat(BasicAmt) - parseFloat(HRAAmt) - parseFloat(MA);
				var OthID = staffName+"_OTH";
				$("#"+OthID).val(parseFloat(OthAmt).toFixed(2));
				// PF Amount
				var PFID = staffName+"_PF";
				$("#"+PFID).val(parseFloat(PF).toFixed(2));
				var EPFID = staffName+"_EPF";
				$("#"+EPFID).val(parseFloat(EMP_PF).toFixed(2));
				//Total Deduction
				var TotalDedID = "total_deduction_"+AccountID;
				var TotalDedID2 = "total_deduction_html_"+AccountID;
				$("#"+TotalDedID).val(parseFloat(TotalDeduction).toFixed(2));
				$("#"+TotalDedID2).html(parseFloat(TotalDeduction).toFixed(2));
				//Monthly CTC
				var TotalCTC = "total_ctc_"+AccountID;
				var TotalCTC2 = "total_ctc_html_"+AccountID;
				$("#"+TotalCTC).val(parseFloat(CTC).toFixed(2));
				$("#"+TotalCTC2).html(parseFloat(CTC).toFixed(2));
				//Monthly Net
				var TotalNet = "total_net_"+AccountID;
				var TotalNet2 = "total_net_html_"+AccountID;
				$("#"+TotalNet).val(parseFloat(NetPayable).toFixed(2));
				$("#"+TotalNet2).html(parseFloat(NetPayable).toFixed(2));
				//Yearly CTC
				var TotalYrCTC = "yearly_ctc_"+AccountID;
				var TotalYrCTC2 = "yearly_ctc_html_"+AccountID;
				$("#"+TotalYrCTC).val(parseFloat(YrCTC).toFixed(2));
				$("#"+TotalYrCTC2).html(parseFloat(YrCTC).toFixed(2));
				
				if(val != NetPayable){
					var log = $('#error_log').val();
					if(log != ""){
						let AccountIDs = log.split(',');
						}else{
						var AccountIDs = [];
					}
					var isInArray = AccountIDs.includes(AccountID);
					if(isInArray == false){
						AccountIDs.push(AccountID);
					}
					var text = AccountIDs.toString();
					$('#error_log').val(text);
					$("#total_earning_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
					$("#total_deduction_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
					$("#total_net_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
					$("#total_ctc_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
					$("#yearly_ctc_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
					}else{
					$("#total_earning_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
					$("#total_deduction_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
					$("#total_net_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
					$("#total_ctc_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
					$("#yearly_ctc_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
					var log = $('#error_log').val();
					if(log != ""){
						let AccountIDs = log.split(',');
						}else{
						let AccountIDs = [];
					}
					
					var isInArray = AccountIDs.includes(AccountID);
					if(isInArray == true){
						AccountIDs.splice(AccountIDs.indexOf(AccountID), 1);
					}
					let text = AccountIDs.toString();
					$('#error_log').val(text);
				}
			}
		}
		/*function changeValue(id,val) {
			var value = $("#"+id).val();
			let id_array = id.split('_');
			var staffName = id_array[0]+'_'+id_array[1];
			var AccountID = id_array[1];
			if(value == ""){
            $("#"+id).val(0);
			}else{
            var Comparray = <?php echo json_encode($SalaryHead); ?>;
            var total_earning = 0;
            var total_deduction = 0;
            var ESIC_Total = 0;
            for (var i = 0; i < Comparray.length; i++) {
			var CurrentHeadID = staffName+'_'+Comparray[i]['code'];
			if(Comparray[i]['mesuredIn'] == "2" && Comparray[i]['code'] != "ESIC"){
			var per = Comparray[i]['percentage'];
			var calBy = Comparray[i]['calculatedBy'];
			var calBy_ID = staffName+'_'+Comparray[i]['calculatedBy'];
			var BaseValue = $("#"+calBy_ID).val();
			var calAmt = parseFloat(BaseValue) * (parseFloat(per) / 100);
			$("#"+CurrentHeadID).val(parseFloat(calAmt).toFixed(2));
			if(Comparray[i]['ESIC_Calculated'] == "Y"){
			var ESIC = parseFloat(calAmt) * (parseFloat(0.75) / 100);
			ESIC_Total = parseFloat(ESIC_Total) + parseFloat(ESIC);
			}
			if(Comparray[i]['type'] == "1"){
			total_earning = parseFloat(total_earning) + parseFloat(calAmt);
			}else{
			total_deduction = parseFloat(total_deduction) + parseFloat(calAmt);
			}
			}
            }
            
            var ESICID = 'Amt_'+AccountID+'_ESIC';
            $("#"+ESICID).val(parseFloat(ESIC_Total).toFixed(2));
            total_deduction = parseFloat(total_deduction) + parseFloat(ESIC_Total);
            $("#total_earning_html_"+AccountID).html(parseFloat(total_earning).toFixed(2));
            $("#total_earning_"+AccountID).val(parseFloat(total_earning).toFixed(2));
            $("#total_deduction_html_"+AccountID).html(parseFloat(total_deduction).toFixed(2));
            $("#total_deduction_"+AccountID).val(parseFloat(total_deduction).toFixed(2));
            
            var yearly_ctc = parseFloat(total_earning) * 12;
            $("#yearly_ctc_html_"+AccountID).html(parseFloat(yearly_ctc).toFixed(2));
            $("#yearly_ctc_"+AccountID).val(parseFloat(yearly_ctc).toFixed(2));
            var NetPayable = parseFloat(total_earning) - parseFloat(total_deduction);
            if(val != NetPayable){
			var log = $('#error_log').val();
			if(log != ""){
			let AccountIDs = log.split(',');
			}else{
			var AccountIDs = [];
			}
			var isInArray = AccountIDs.includes(AccountID);
			if(isInArray == false){
			AccountIDs.push(AccountID);
			}
			var text = AccountIDs.toString();
			$('#error_log').val(text);
			$("#total_earning_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
			$("#total_deduction_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
			$("#yearly_ctc_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
            }else{
			$("#total_earning_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
			$("#total_deduction_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
			$("#yearly_ctc_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
			var log = $('#error_log').val();
			if(log != ""){
			let AccountIDs = log.split(',');
			}else{
			let AccountIDs = [];
			}
			
			var isInArray = AccountIDs.includes(AccountID);
			if(isInArray == true){
			AccountIDs.splice(AccountIDs.indexOf(AccountID), 1);
			}
			let text = AccountIDs.toString();
			$('#error_log').val(text);
            }
			}
		}*/
		function SubmitFormData() {
			var count = $('#error_log').val();
			if(count != ""){
				alert('Please check Net payable and Monthly Gross Amt is not equal');
				}else{
				if(confirm("Do you want to update salary...!")){
					$('#salary_form').submit();
					}else{
					return false;
				}
			}
			
			/*var InputArray = new Array();
				var i = 1;
				$("input[type=text]").each(function() {
				var ii = i - 1;
                InputArray[ii]=new Array();
                InputArray[ii][0]=this.name;
                InputArray[ii][1]=this.value;
                i++;
				});
				var ItemDivSerializedArr = JSON.stringify(InputArray);
				
				$.ajax({
				url:"<?php echo admin_url(); ?>rate_master/getUpdatedRate",
				method:"POST",
				data:{inputData:ItemDivSerializedArr}, 
				
				success: function(data){
                if(data){
				Swal.fire({
				position: 'top-end',
				title: 'Rate Updated!',
				padding: '5px',
				icon: 'success',
				timer: 3000,
				showConfirmButton: false,
				timerProgressBar: false,
				})  
                }
				
				}
			});*/
			
		}
		
		$(document).on("click", ".sortablePop", function () {
			var table = $("#table-daily_report tbody");
			var rows = table.find("tr").toArray();
			var index = $(this).index();
			var ascending = !$(this).hasClass("asc");
			
			
			// Remove existing sort classes and reset arrows
			$(".sortablePop").removeClass("asc desc");
			$(".sortablePop span").remove();
			
			// Add sort classes and arrows
			$(this).addClass(ascending ? "asc" : "desc");
			$(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
			
			rows.sort(function (a, b) {
				var valA = $(a).find("td").eq(index).text().trim();
				var valB = $(b).find("td").eq(index).text().trim();
				
				if ($.isNumeric(valA) && $.isNumeric(valB)) {
					return ascending ? valA - valB : valB - valA;
					} else {
					return ascending
					? valA.localeCompare(valB)
					: valB.localeCompare(valA);
				}
			});
			table.append(rows);
		});
	</script>													