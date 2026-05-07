<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>HR</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Salary Component</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait!! fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait!! Creating new Salary Head...</div>
								<div class="searchh4" style="display:none;">Please wait!! updating Salary Head...</div>
							</div>
							<input type="hidden" name="group_codehidden" id="group_codehidden" class="form-control" value="">
							<div class="col-md-4">
								<div class="form-group">
								    <small class="req text-danger">* </small>
									<label for="group_code">Component Code</label>
									<input type="text" name="group_code" id="group_code" class="form-control" value="">
									
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
								    <small class="req text-danger">* </small>
									<label for="group_name">Component Name</label>
									<input type="text" name="group_name" id="group_name" class="form-control" value="">
									<input type="hidden" name="form_mode" id="form_mode" value="add">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
								    <small class="req text-danger">* </small>
									<label for="SequenceNo">Sequence No</label>
									<input type="text" name="SequenceNo" id="SequenceNo" class="form-control" value="">
								</div>
							</div>
							
						</div>
						<div class="row"> 
							<div class="col-md-4">
								<div class="form-group">
								    <small class="req text-danger">* </small>
									<label for="group_type">Salary Head </label>
									<select name="group_type" id="group_type" class="selectpicker" data-live-search="true" data-width="100%">
									    <option value="">Not Selected</option>
										<option value="1">Earning</option>
										<option value="2">Deduction</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
								    <small class="req text-danger">* </small>
									<label for="CalcualtedFor">Calculated For </label>
									<select name="CalcualtedFor" id="CalcualtedFor" class="selectpicker" data-live-search="true" data-width="100%">
									    <option value="">Not Selected</option>
										<option value="1">Gross Salary</option>
										<option value="2">CTC</option>
										<option value="3">Net Salary</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
								    <small class="req text-danger">* </small>
									<label for="movement_type">Measured In</label>
									<select name="movement_type" id="movement_type" class="selectpicker" data-live-search="true" data-width="100%">
									    <option value="">Not Selected</option>
										<option value="1">Fixed Amount</option>
										<option value="2">Percentage</option>
										<option value="3">Sum Amount</option>
									</select>
								</div>
							</div>
							
							
						</div>
						<div class="row" > 
							<div class="col-md-4" id="per_div_percentage" style="display:none">
							    <div class="form-group">
									<label for="group_name">Percentage</label>
									<input type="text" name="percentage" id="percentage" class="form-control" value="">
								</div>
							</div>
							<div class="col-md-4" id="per_div_calculatedBy" style="display:none">
								<div class="form-group">
									<label for="calculatedBy">Calculated By</label>
									<select name="calculatedBy" id="calculatedBy" class="selectpicker" data-live-search="true" data-width="100%">
									    <option value="">Not Selected</option>
									</select>
								</div>
							</div>
							<div class="col-md-4" id="per_div_MaxAmt" style="display:none">
							    <div class="form-group">
									<label for="MaxAmt">Max Amount</label>
									<input type="text" name="MaxAmt" id="MaxAmt" class="form-control" value="">
								</div>
							</div>
						</div>
						
						<div class="row"> 
							<div class="col-md-12">
								<?php if (has_permission_new('salaryComponents', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission_new('salaryComponents', '', 'edit')) {
								?>
								<button type="button" class="btn btn-info updateBtn" style="margin-right: 25px;">Update</button>
								<?php
									}else{
									alert("You are not allowed to update record");
								?>
								<button type="button" class="btn btn-info updateBtn2 disabled" style="margin-right: 25px;">Update</button>
								<?php
								}?>
								
								<button type="button" class="btn btn-default cancelBtn" >Cancel</button>
							</div>
						</div>
						
						<div class="row"> 
							<div class="col-md-12">
								<div class="table-responsive tableFixHead2" style="max-height:340px; overflow-y:auto; border:1px solid #ddd;">
									<table class="table table-striped table-bordered" width="100%" id="user_list">
										<thead style="position: sticky; top: 0; background: #f1f1f1; z-index: 2;">
											<tr>
												<th class="sortablePop2" style="text-align:left;">Component Code</th>
												<th class="sortablePop2" style="text-align:left;">Component Name</th>
												<th class="sortablePop2" style="text-align:left;">Sequence No</th>
												<th class="sortablePop2" style="text-align:left;">Salary Head</th>
												<th class="sortablePop2" style="text-align:left;">Calculated For</th>
												<th class="sortablePop2" style="text-align:left;">Measured In</th>
												<th style="text-align:left;">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($salary_head_table as $key => $value) { ?>
												<tr>
													<td><?php echo $value["code"];?></td>
													<td><?php echo $value["name"];?></td>
													<td><?php echo $value["SequenceNo"];?></td>
													<?php
														if($value["type"]=="1"){
															$groupType = "Earning";
															}elseif($value["type"]=="2"){
															$groupType = "Deduction";
															}else{
															$groupType = "";
														}
													?>
													<td><?php echo $groupType;?></td>
													<?php
														if($value["CalcualtedFor"]=="1"){
															$CalcualtedFor = "Gross Salary";
															}elseif($value["CalcualtedFor"]=="2"){
															$CalcualtedFor = "CTC";
															}elseif($value["CalcualtedFor"]=="3"){
															$CalcualtedFor = "Net Salary";
															}else{
															$CalcualtedFor = "";
														}
													?>
													<td><?php echo $CalcualtedFor;?></td>
													<?php 
														if($value["mesuredIn"]=="1"){
															$movement = "Fixed Amount";
															}elseif($value["mesuredIn"]=="2"){
															$movement = "Percentage";
															}elseif($value["mesuredIn"]=="3"){
															$movement = "Sum Amount";
															}else{
															$movement = "";
														}
													?>
													<td><?php echo $movement;?></td>
													<?php 
														$action = '';
														if (has_permission_new('salaryComponents', '', 'edit')) {
															$action .= '<a href="#" class="get_AccountID" data-id="' . $value['code'] . '"><i class="fa fa-pencil"></i></a>';
														}
														if (has_permission_new('salaryComponents', '', 'delete')) {
															$action .= ' &nbsp;&nbsp;<a href="#" onclick="DeleteComponent(\'' . $value['code'] . '\')"><i class="fa fa-trash"></i></a>';
														}
													?>
													<td class="hide_in_print"><?php echo $action;?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<span id="searchh3" style="display:none;">Please wait data exporting.....</span>
							</div>
							
							<style>
								/* Sticky header inside scrollable table */
								.tableFixHead2 thead th {
								position: sticky;
								top: 0;
								color: #fff;
								z-index: 5;
								}
								.tableFixHead2 tbody tr:hover {
								background: #f5f5f5;
								}
							</style>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade AccountGroup" id="AccountGroup" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header" style="padding:5px 10px;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">SalaryHead List</h4>
			</div>
			<div class="modal-body" style="padding:0px 5px !important">
				
				<div class="table-AccountGroup tableFixHead2">
					<table class="tree table table-striped table-bordered table-AccountGroup tableFixHead2" id="table_AccountGroup" width="100%">
						<thead>
							<tr>
								<th class="sortablePop" style="text-align:left;">Component Code</th>
								<th class="sortablePop" style="text-align:left;">Component Name</th>
								<th class="sortablePop" style="text-align:left;">Salary Head</th>
								<th class="sortablePop" style="text-align:left;">Calculated For</th>
								<th class="sortablePop" style="text-align:left;">Measured In</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($salary_head_table as $key => $value) {
								?>
								<tr class="get_AccountID" data-id="<?php echo $value["code"]; ?>">
									<td><?php echo $value["code"];?></td>
									<td><?php echo $value["name"];?></td>
									<?php
										if($value["type"]=="1"){
											$groupType = "Earning";
											}elseif($value["type"]=="2"){
											$groupType = "Deduction";
										}
										else{
											$groupType = "";
										}
									?>
									<td><?php echo $groupType;?></td>
									<?php
										if($value["CalcualtedFor"]=="1"){
											$CalcualtedFor = "Gross Salary";
											}elseif($value["CalcualtedFor"]=="2"){
											$CalcualtedFor = "CTC";
											}elseif($value["CalcualtedFor"]=="3"){
											$CalcualtedFor = "Net Salary";
											}else{
											$CalcualtedFor = "";
										}
									?>
									<td><?php echo $CalcualtedFor;?></td>
									<?php 
										if($value["mesuredIn"]=="1"){
											$movement = "Fixed Amount";
											}elseif($value["mesuredIn"]=="2"){
											$movement = "Percentage";
											}elseif($value["mesuredIn"]=="3"){
											$movement = "Sum Amount";
											}else{
											$movement = "";
										}
									?>
									<td><?php echo $movement;?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>   
				</div>
			</div>
			<div class="modal-footer" style="padding:0px;">
				<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php init_tail(); ?>
<!--new update -->
<script type="text/javascript">
	function DeleteComponent(id){
		
		if(confirm("Are you sure you want to delete")){
			$.ajax({
				url:"<?php echo admin_url(); ?>payroll/DeleteComponent",
				dataType:"JSON",
				method:"POST",
				data:{id:id},
				beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
				complete: function () {
					$('.searchh2').css('display','none');
				},
				success:function(data){
					if(data == true){
						alert('Data Deleted Successfully');
						window.location.reload();
					}
				}
			});
		}
	}
	$('#percentage,#MaxAmt').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});
</script>
<script>
    $('#movement_type').on('change', function () {
		
		var value = $(this).val();
		var $calculatedBy = $('#calculatedBy');
		
		// Reset value
		$calculatedBy.val([]);
		
		if (value == "2") {
			
			$('#per_div_percentage').show();
			$('#per_div_calculatedBy').show();
			$('#per_div_MaxAmt').show();
			
			// Single select
			switchToSingle($calculatedBy);
			$.ajax({
				url: "<?php echo admin_url(); ?>payroll/GetSalaryHeadList",
				type: "POST",
				dataType: "JSON",
				data: { value: value },
				success: function (data) {
					
					var options = '<option value="">Not Selected</option>';
					
					$.each(data, function (i, item) {
						options += `<option value="${item.code}">${item.name}</option>`;
					});
					
					$('#calculatedBy').html(options).selectpicker('refresh');
				}
			});
			
		} 
		else if (value == "3") {
			
			$('#per_div_percentage').hide();
			$('#per_div_calculatedBy').hide();
			$('#per_div_MaxAmt').hide();
			
			// ✅ Multiple select
			switchToMultiple($calculatedBy);
			$.ajax({
				url: "<?php echo admin_url(); ?>payroll/GetSalaryHeadList",
				type: "POST",
				dataType: "JSON",
				data: { value: value },
				success: function (data) {
					
					var options = '';
					
					$.each(data, function (i, item) {
						options += `<option value="${item.code}">${item.name}</option>`;
					});
					
					$('#calculatedBy').html(options).selectpicker('refresh');
				}
			});
			
		} 
		else {
			
			$('#per_div_percentage').hide();
			$('#per_div_calculatedBy').hide();
			$('#per_div_MaxAmt').hide();
		}
	});
	
	
	
	
    
    
    
    
    // Get head Detail by head code
	$('#group_code').on('blur',function(){
		var head_code = $('#group_code').val();
		if(head_code == ""){
			$('.saveBtn').show();
			$('.saveBtn2').show();
			$('.updateBtn').hide();
			$('.updateBtn2').hide();
			$('#group_name').val('');
			$('#SequenceNo').val('');
			$('select[name=group_type]').val('');
			$('.selectpicker').selectpicker('refresh');
			$('select[name=movement_type]').val('');
			$('.selectpicker').selectpicker('refresh');
			$('select[name=CalcualtedFor]').val('');
			$('.selectpicker').selectpicker('refresh');
			}else{
			$.ajax({
				url:"<?php echo admin_url(); ?>payroll/get_salary_head_details",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{head_code:head_code,},
				success:function(data){
					if(empty(data)){
						$('.saveBtn').show();
						$('.saveBtn2').show();
						$('.updateBtn').hide();
						$('.updateBtn2').hide();
						//$('#group_code').val('');
						$('#group_name').val('');
						$('#SequenceNo').val('');
						$('select[name=group_type]').val('');
						$('.selectpicker').selectpicker('refresh');
						$('select[name=CalcualtedFor]').val('');
						$('.selectpicker').selectpicker('refresh');
						$('select[name=movement_type]').val('');
						$('.selectpicker').selectpicker('refresh');
						$('#percentage').val('');
						$('#MaxAmt').val('');
						$('#per_div_percentage').css('display','none');
						$('#per_div_calculatedBy').css('display','none');
						$('#per_div_MaxAmt').css('display','none');
						}else{
						$('#group_code').val(data.code);
						$('#group_name').val(data.name);
						$('#SequenceNo').val(data.SequenceNo);
						$('select[name=group_type]').val(data.type);
						$('.selectpicker').selectpicker('refresh');
						$('select[name=CalcualtedFor]').val(data.CalcualtedFor);
						$('.selectpicker').selectpicker('refresh');
						$('select[name=movement_type]').val(data.mesuredIn);
						$('.selectpicker').selectpicker('refresh')
						
						if(data.mesuredIn == "2"){
							$('#per_div_percentage').css('display','block');
							$('#per_div_calculatedBy').css('display','block');
							$('#per_div_MaxAmt').css('display','block');
							var value = '';
							var $calculatedBy = $('#calculatedBy');
							
							// Enable multiple FIRST
							switchToSingle($calculatedBy);
							
							var calBy = data.calculatedBy;
							
							$.ajax({
								url: "<?php echo admin_url(); ?>payroll/GetSalaryHeadList",
								dataType: "JSON",
								method: "POST",
								cache: false,
								data: { value: value },
								success: function (data) {
									
									var optionsHTMLHead = '<option value="">Non selected</option>';
									
									$.each(data, function (index, option) {
										optionsHTMLHead += '<option value="' + option.code + '">' + option.name + '</option>';
									});
									
									$('#calculatedBy')
									.html(optionsHTMLHead)
									.selectpicker('refresh')
									.selectpicker('val', calBy);   // ✅ correct way
								}
							});
							$('#percentage').val(data.percentage);
							$('#MaxAmt').val(data.MaxAmt);
						}
						if(data.mesuredIn == "3"){
							$('#per_div_percentage').css('display','none');
							$('#per_div_calculatedBy').css('display','none');
							$('#per_div_MaxAmt').css('display','none');
							var value = '';
							var $calculatedBy = $('#calculatedBy');
							
							// Enable multiple FIRST
							switchToMultiple($calculatedBy);
							
							var calBy = data.calculatedBy;
							
							// ✅ Convert to array if needed
							if (typeof calBy === 'string') {
								calBy = calBy.split(',');
							}
							
							$.ajax({
								url: "<?php echo admin_url(); ?>payroll/GetSalaryHeadList",
								dataType: "JSON",
								method: "POST",
								cache: false,
								data: { value: value },
								success: function (data) {
									
									var optionsHTMLHead = '';
									
									$.each(data, function (index, option) {
										optionsHTMLHead += '<option value="' + option.code + '">' + option.name + '</option>';
									});
									
									$('#calculatedBy')
									.html(optionsHTMLHead)
									.selectpicker('refresh')
									.selectpicker('val', calBy);   // ✅ correct way
								}
							});
							$('#percentage').val('');
							$('#MaxAmt').val('');
						}
						$('.saveBtn').hide();
						$('.updateBtn').show();
						$('.saveBtn2').hide();
						$('.updateBtn2').show();
					}
				}
			});
		}
	})
	
	
	/* ---------- Helpers ---------- */
	
	function switchToMultiple($select) {
		$select.selectpicker('destroy');
		$select.attr('multiple', 'multiple');
		$select.selectpicker();
	}
	
	function switchToSingle($select) {
		$select.selectpicker('destroy');
		$select.removeAttr('multiple');
		$select.selectpicker();
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		$("#group_code").dblclick(function(){
			$('#AccountGroup').modal('show');
			$('#AccountGroup').on('shown.bs.modal', function () {
				$('#myInput1').val('');
				$('#myInput1').focus();
			})
		});
		
		$('.updateBtn').hide();
		$('.updateBtn2').hide();
		
		// Focus on head code
 		$('#group_code').on('focus',function(){
 			$('#group_code').val('');
 			$('#group_name').val('');
 			$('#SequenceNo').val('');
 			$('select[name=group_type]').val('');
 			$('.selectpicker').selectpicker('refresh');
 			$('select[name=CalcualtedFor]').val('');
 			$('.selectpicker').selectpicker('refresh');
 			$('select[name=movement_type]').val('');
 			$('.selectpicker').selectpicker('refresh');
 			$('#percentage').val('');
 			$('#MaxAmt').val('');
            $('#per_div_percentage').css('display','none');
            $('#per_div_calculatedBy').css('display','none');
            $('#per_div_MaxAmt').css('display','none');
 			$('.saveBtn').show();
 			$('.saveBtn2').show();
			$('.updateBtn').hide();
 			$('.updateBtn2').hide();
		});
		
		// Cancel selected data
		$(".cancelBtn").click(function(){
			$('#group_code').val('');
			$('#group_name').val('');
			$('#SequenceNo').val('');
			$('select[name=group_type]').val('');
			$('.selectpicker').selectpicker('refresh');
			$('select[name=CalcualtedFor]').val('');
			$('.selectpicker').selectpicker('refresh');
			$('select[name=movement_type]').val('');
			$('.selectpicker').selectpicker('refresh');
			$('#percentage').val('');
			$('#MaxAmt').val('');
            $('#per_div_percentage').css('display','none');
            $('#per_div_calculatedBy').css('display','none');
            $('#per_div_MaxAmt').css('display','none');
			$('.saveBtn').show();
			$('.saveBtn2').show();
			$('.updateBtn').hide();
			$('.updateBtn2').hide();
		});
		
		
		$('.get_AccountID').on('click',function(){ 
            head_code = $(this).attr("data-id");
            $.ajax({
				url:"<?php echo admin_url(); ?>payroll/get_salary_head_details",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{head_code:head_code,},
				success:function(data){
                    if(empty(data)){
                        $('.saveBtn').show();
                        $('.saveBtn2').show();
                        $('.updateBtn').hide();
                        $('.updateBtn2').hide();
						$('#group_code').val('');
                        $('#group_name').val('');
                        $('#SequenceNo').val('');
                        $('select[name=group_type]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        $('select[name=CalcualtedFor]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        $('select[name=movement_type]').val('');
    				    $('#percentage').val('');
    				    $('#MaxAmt').val('');
                        $('#per_div_percentage').css('display','none');
						$('#per_div_calculatedBy').css('display','none');
						$('#per_div_MaxAmt').css('display','none');
                        $('.selectpicker').selectpicker('refresh');
						}else{
                        $('#group_code').val(data.code);
                        $('#group_name').val(data.name);
                        $('#SequenceNo').val(data.SequenceNo);
                        $('select[name=group_type]').val(data.type);
                        $('.selectpicker').selectpicker('refresh');
                        $('select[name=CalcualtedFor]').val(data.CalcualtedFor);
                        $('.selectpicker').selectpicker('refresh');
                        $('select[name=movement_type]').val(data.mesuredIn);
                        $('.selectpicker').selectpicker('refresh')
						
                        $('#per_div_percentage').css('display','none');
						$('#per_div_calculatedBy').css('display','none');
						$('#per_div_MaxAmt').css('display','none');
						
                        if(data.mesuredIn == "2"){
    						$('#per_div_percentage').css('display','block');
							$('#per_div_calculatedBy').css('display','block');
							$('#per_div_MaxAmt').css('display','block');
    						var value = '';
							var $calculatedBy = $('#calculatedBy');
							
							// Enable multiple FIRST
							switchToSingle($calculatedBy);
							
							var calBy = data.calculatedBy;
							
							$.ajax({
								url: "<?php echo admin_url(); ?>payroll/GetSalaryHeadList",
								dataType: "JSON",
								method: "POST",
								cache: false,
								data: { value: value },
								success: function (data) {
									
									var optionsHTMLHead = '<option value="">Non selected</option>';
									
									$.each(data, function (index, option) {
										optionsHTMLHead += '<option value="' + option.code + '">' + option.name + '</option>';
									});
									
									$('#calculatedBy')
									.html(optionsHTMLHead)
									.selectpicker('refresh')
									.selectpicker('val', calBy);   // ✅ correct way
								}
							});
    						$('#percentage').val(data.percentage);
    						$('#MaxAmt').val(data.MaxAmt);
						}
                        if(data.mesuredIn == "3"){
    						$('#per_div_percentage').css('display','none');
							$('#per_div_calculatedBy').css('display','none');
							$('#per_div_MaxAmt').css('display','none');
    						var value = '';
							var $calculatedBy = $('#calculatedBy');
							
							// Enable multiple FIRST
							switchToMultiple($calculatedBy);
							
							var calBy = data.calculatedBy;
							
							// ✅ Convert to array if needed
							if (typeof calBy === 'string') {
								calBy = calBy.split(',');
							}
							
							$.ajax({
								url: "<?php echo admin_url(); ?>payroll/GetSalaryHeadList",
								dataType: "JSON",
								method: "POST",
								cache: false,
								data: { value: value },
								success: function (data) {
									
									var optionsHTMLHead = '';
									
									$.each(data, function (index, option) {
										optionsHTMLHead += '<option value="' + option.code + '">' + option.name + '</option>';
									});
									
									$('#calculatedBy')
									.html(optionsHTMLHead)
									.selectpicker('refresh')
									.selectpicker('val', calBy);   // ✅ correct way
								}
							});
    						$('#percentage').val('');
    						$('#MaxAmt').val('');
						}
                        $('.saveBtn').hide();
                        $('.updateBtn').show();
                        $('.saveBtn2').hide();
                        $('.updateBtn2').show();
					}
				}
			});
            $('#AccountGroup').modal('hide');
		});
		
		// Save New salary head
        $('.saveBtn').on('click',function(){ 
            HeadCode = $('#group_code').val();
            HeadName = $('#group_name').val();
            SequenceNo = $('#SequenceNo').val();
            type = $('#group_type').val();
            CalcualtedFor = $('#CalcualtedFor').val();
            mesuredIn = $('#movement_type').val();
            if(mesuredIn == "2"){
                percentage = $('#percentage').val();
                MaxAmt = $('#MaxAmt').val();
                calculatedBy = $('#calculatedBy').val();
				}else if(mesuredIn == "3"){
			    percentage = '';
				MaxAmt = '';
                calculatedBy = $('#calculatedBy').val();
				}else{
                percentage = '';
                MaxAmt = '';
                calculatedBy = '';
			}
            if(type ==""){
                alert("please select Salery Head Type");
				}else if(CalcualtedFor == ""){
                alert("please select Calcualted For");
				}else if(mesuredIn == ""){
                alert("please select MesuredIN");
				}else if(HeadCode == ""){
                alert("please enter salay head code");
				}else if(HeadName == ""){
                alert("please enter salary head name");
				}else if(SequenceNo == ""){
                alert("please enter Sequence No.");
				}else if(mesuredIn == "2" && percentage ==""){
                alert("please enter percentage");
				}else if(mesuredIn == "2" && calculatedBy ==""){
                alert("please select CalcualtedBy");
				}else if(mesuredIn == "2" && MaxAmt ==""){
                alert("please enter Max Amount");
				}else if(mesuredIn == "3" && calculatedBy ==""){
                alert("please select CalcualtedBy");
				}else{
                $.ajax({
                    url:"<?php echo admin_url(); ?>payroll/SaveHead",
                    dataType:"JSON",
                    method:"POST",
                    data:{HeadCode:HeadCode,HeadName:HeadName,SequenceNo:SequenceNo,type:type,mesuredIn:mesuredIn,percentage:percentage,calculatedBy:calculatedBy,MaxAmt:MaxAmt,CalcualtedFor:CalcualtedFor},
                    beforeSend: function () {
    					$('.searchh3').css('display','block');
    					$('.searchh3').css('color','blue');
					},
                    complete: function () {
    					$('.searchh3').css('display','none');
					},
                    success:function(data){
    					if(data == true){
    						alert_float('success', 'Record created successfully...');
    						$('.saveBtn').show();
    						$('.saveBtn2').show();
    						$('.updateBtn').hide();
    						$('.updateBtn2').hide();
    						$('#group_code').val('');
    						$('#group_codehidden').val();
    						$('#group_name').val('');
    						$('#SequenceNo').val('');
    						$('select[name=group_type]').val('');
    						$('.selectpicker').selectpicker('refresh');
    						$('select[name=CalcualtedFor]').val('');
    						$('.selectpicker').selectpicker('refresh');
    						$('select[name=movement_type]').val('');
    						$('.selectpicker').selectpicker('refresh');
    						$('#percentage').val('');
    						$('#MaxAmt').val('');
    						$('#per_div_percentage').css('display','none');
							$('#per_div_calculatedBy').css('display','none');
							$('#per_div_MaxAmt').css('display','none');
							location.reload();
							}else{
    						alert_float('warning', 'Something went wrong...');
						}
					}
				});
			}
		}); 
        
		// Update Exiting head
        $('.updateBtn').on('click',function(){ 
            HeadCode = $('#group_code').val();
            HeadName = $('#group_name').val();
            SequenceNo = $('#SequenceNo').val();
            HeadType = $('#group_type').val();
            CalcualtedFor = $('#CalcualtedFor').val();
            measuredIn = $('#movement_type').val();
            if(measuredIn == "2"){
                percentage = $('#percentage').val();
                MaxAmt = $('#MaxAmt').val();
                calculatedBy = $('#calculatedBy').val();
				}else if(measuredIn == "3"){
			    percentage = '';
				MaxAmt = '';
                calculatedBy = $('#calculatedBy').val();
				}else{
                percentage = '';
                MaxAmt = '';
                calculatedBy = '';
			}
			if(HeadType ==""){
                alert("please select Salery Head Type");
				}else if(CalcualtedFor == ""){
                alert("please select Calcualted For");
				}else if(measuredIn == ""){
                alert("please select MesuredIN");
				}else if(HeadCode == ""){
                alert("please enter salay head code");
				}else if(HeadName == ""){
                alert("please enter salary head name");
				}else if(SequenceNo == ""){
                alert("please enter Sequence No.");
				}else if(measuredIn == "2" && percentage ==""){
                alert("please enter percentage");
				}else if(measuredIn == "2" && calculatedBy ==""){
                alert("please select CalcualtedBy");
				}else if(measuredIn == "2" && MaxAmt ==""){
                alert("please enter Max Amount");
				}else if(measuredIn == "3" && calculatedBy ==""){
                alert("please select CalcualtedBy");
				}else{
                $.ajax({
                    url:"<?php echo admin_url(); ?>payroll/UpdateSalaryHead",
                    dataType:"JSON",
                    method:"POST",
                    data:{HeadCode:HeadCode,HeadName:HeadName,SequenceNo:SequenceNo,HeadType:HeadType,measuredIn:measuredIn,percentage:percentage,calculatedBy:calculatedBy,MaxAmt:MaxAmt,CalcualtedFor:CalcualtedFor},
                    beforeSend: function () {
    					$('.searchh4').css('display','block');
    					$('.searchh4').css('color','blue');
					},
                    complete: function () {
    					$('.searchh4').css('display','none');
					},
                    success:function(data){
                        if(data == true){
    						alert_float('success', 'Record updated successfully...');
                            $('.saveBtn').show();
                            $('.saveBtn2').show();
                            $('.updateBtn').hide();
                            $('.updateBtn2').hide();
    					    $('#group_code').val('');
                            $('#group_name').val('');
                            $('#SequenceNo').val('');
                            $('select[name=group_type]').val('');
                            $('.selectpicker').selectpicker('refresh');
                            $('select[name=CalcualtedFor]').val('');
                            $('.selectpicker').selectpicker('refresh');
                            $('select[name=movement_type]').val('');
                            $('.selectpicker').selectpicker('refresh');
							$('#per_div_percentage').css('display','none');
							$('#per_div_calculatedBy').css('display','none');
							$('#per_div_MaxAmt').css('display','none');
							location.reload();
							}else{
    						alert_float('warning', 'Something went wrong...');
						}
					}
				});
			}
		});
		
	});
	
</script>

<script>
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_AccountGroup");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						} else if(td2){
						txtValue = td2.textContent || td2.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
							}else if(td3){
							txtValue = td3.textContent || td3.innerText;
							if (txtValue.toUpperCase().indexOf(filter) > -1) {
								tr[i].style.display = "";
								}else{
								tr[i].style.display = "none";
							} 
						}
					}
				}    
			}
		}
	}
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_AccountGroup tbody");
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
	$(document).on("click", ".sortablePop2", function () {
		var table = $("#user_list tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortablePop2").removeClass("asc desc");
		$(".sortablePop2 span").remove();
		
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

<style>
    #table_AccountGroup td:hover {
    cursor: pointer;
	}
	#table_AccountGroup tr:hover {
    background-color: #ccc;
	}
	
    .table-AccountGroup          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-AccountGroup thead th { position: sticky; top: 0; z-index: 1; }
    .table-AccountGroup tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>

<style type="text/css">
	body{
    overflow: hidden;
	}
</style>

