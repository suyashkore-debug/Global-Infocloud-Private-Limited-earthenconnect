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
                    					<li class="breadcrumb-item active text-capitalize"><b>Accounts</b></li>
                    					<li class="breadcrumb-item active" aria-current="page"><b>Accounts Sub-Group2</b></li>
                    				</ol>
                                </nav>
                                <hr class="hr_style">
						<?php //echo form_open('admin/accounts_master/manage_account_subgroup',array('id'=>'account_subgroup_form')); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait Create new SubGroup...</div>
								<div class="searchh4" style="display:none;">Please wait update SubGroup...</div>
							</div>
							<br>
							
							<input type="hidden" name="form_mode" id="form_mode" value="add">
							<input type="hidden" name="OldAccountSubGroupID3" id="OldAccountSubGroupID3" value="<?php echo $NextAccountGroupID2;?>">
							<div class="col-md-3">
								<div class="form-group">
									<label for="subgroup_code">Account Sub-GroupID2</label>
									<input type="text" name="subgroup_code" id="subgroup_code" class="form-control" value="<?php echo $NextAccountGroupID2;?>" onkeypress="return isNumber(event)">
									
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for="subgroup_name">Account Sub-GroupID2 Name</label>
									<input type="text" name="subgroup_name" id="subgroup_name" class="form-control" value="">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="GroupFor">Group For</label>
									<select name="GroupFor" id="GroupFor" class="selectpicker display-block" data-width="100%" data-none-selected-text="None Selected" data-live-search="true">
										<option value="">None Selected</option>
										<option value="Customer">Group For Customer</option>
										<option value="Vendor">Group For Vendor</option>
										<option value="Staff">Group For Staff</option>
										<option value="Ledger">Group For Other Ledger</option>
									</select>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="main_group">Account Main Group</label>
									<select name="main_group" id="main_group" class="selectpicker display-block" data-width="100%" data-none-selected-text="--Select--" data-live-search="true">
										<?php
											foreach ($AccountMainGroup as $key => $value) {
											?>
											<option value="<?php echo $value["ActGroupID"];?>"><?php echo $value["ActGroupName"];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="AccountSubGroupID1">Account Sub-Group1</label>
									<select name="AccountSubGroupID1" id="AccountSubGroupID1" class="selectpicker display-block" data-width="100%" data-none-selected-text="--Select--" data-live-search="true">
										<?php
											foreach ($AccountSubGroupID1 as $key => $value) {
											?>
											<option value="<?php echo $value["SubActGroupID1"];?>"><?php echo $value["SubActGroupName"];?></option>
											<?php
											}
										?>
									</select>
								</div>
							</div>
							
							
							
						</div>
						<div class="clearfix"></div>
						
						
						<div class="row"> 
							<div class="col-md-12">
								<?php if (has_permission_new('account_subgroups2', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission_new('account_subgroups2', '', 'edit')) {
								?>
								<button type="button" class="btn btn-info updateBtn" style="margin-right: 25px;">Update</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info updateBtn2 disabled" style="margin-right: 25px;">Update</button>
								<?php
								}?>
								
								<button type="button" class="btn btn-default cancelBtn" >Cancel</button>
							</div>
							
						</div>
						
						<?php //echo form_close(); ?>
						
						<div class="clearfix"></div>
						<!-- Account Head List Model-->
						
						<div class="modal fade AccountSubgroup" id="AccountSubgroup" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Account Sub-Group</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-AccountSubgroup tableFixHead2">
											<table class="tree table table-striped table-bordered table-AccountSubgroup tableFixHead2" id="table_AccountSubgroup" width="100%">
												<thead>
													<tr style="display:none;">
														<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
													</tr>
													<tr>
														<th class="sortablePop" >Sub-Account GroupID2 </th>
														<th class="sortablePop">Sub-Account Group Name2</th>
														<th class="sortablePop">Sub-Account Group-ID1</th>
														<th class="sortablePop">Sub-account Group Name1</th>
													</tr>
												</thead>
												<tbody>
													<?php
														foreach ($AccountSubGroupID2 as $key => $value) {
														?>
														<tr class="get_AccountID" data-id="<?php echo $value["SubActGroupID"]; ?>">
															<td><?php echo $value["SubActGroupID"];?></td>
															<td><?php echo $value["SubActGroupName"];?></td>
															<td><?php echo $value["SubActGroupID1"];?></td>
															<?php
																$mainGroupName = '';
																foreach ($AccountMainGroup1 as $key1 => $value1) {
																	if($value["SubActGroupID1"] == $value1["SubActGroupID1"]){
																		$mainGroupName = $value1["SubActGroupName"];
																	}
																}
															?>
															<td><?php echo $mainGroupName;?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>   
										</div>
									</div>
									<div class="modal-footer" style="padding:0px;">
										<input type="text" id="myInput1"  name='myInput1' onkeyup="myFunction2()" placeholder="Search for names.."  style="float: left;width: 100%;">
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
<!--new update -->

<script>
    function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		$('.updateBtn').hide();
		$('.updateBtn2').hide();  
		
		$("#subgroup_code").dblclick(function(){
			$('#AccountSubgroup').modal('show');
			$('#AccountSubgroup').on('shown.bs.modal', function () {
				$('#myInput1').val('');
				$('#myInput1').focus();
			})
		});  
		
		// Focus in Subgroup ID 
		$('#subgroup_code').on('focus',function(){
			var maingroup_id = "10000";
			$.ajax({
				url:"<?php echo admin_url(); ?>accounts_master/NextAccountGroupID2",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{maingroup_id:maingroup_id,},
				success:function(data){
					$('select[name=main_group]').val('10000');
					$('.selectpicker').selectpicker('refresh'); 
					$('select[name=GroupFor]').val('');
					$('.selectpicker').selectpicker('refresh'); 
					$('#subgroup_code').val(data);
					$('#subgroup_name').val('');
					$('#form_mode').val('add');
					$('.saveBtn').show();
					$('.saveBtn2').show();
					$('.updateBtn').hide();
					$('.updateBtn2').hide();
				}
			});
		});
		
		// Cancel selected data
        $(".cancelBtn").click(function(){
            var maingroup_id = "10000";
            $.ajax({
				url:"<?php echo admin_url(); ?>accounts_master/NextAccountGroupID2",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{maingroup_id:maingroup_id},
				success:function(data){
					$('select[name=main_group]').val('10000');
					$('.selectpicker').selectpicker('refresh'); 
					$('select[name=GroupFor]').val('');
					$('.selectpicker').selectpicker('refresh'); 
					$('#subgroup_code').val(data);
					$('#subgroup_name').val('');
					GetChangedData(10000)
					$('#form_mode').val('add');
					$('.saveBtn').show();
					$('.saveBtn2').show();
					$('.updateBtn').hide();
					$('.updateBtn2').hide();
				}
			});
            
		});
        
		// Blur Subgroup ID
		$('#subgroup_code').on('blur',function(){
			
			var account_subgroupID = $(this).val();
			if(account_subgroupID == ""){
				$('#add_button').show();
				$('#edit_button').hide();
				$('#subgroup_name').val('');
				$('.selectpicker').selectpicker('refresh')
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>accounts_master/get_account_subgroup_details2",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{account_subgroupID:account_subgroupID,},
					
					success:function(data){
						if(empty(data)){
							$('#form_mode').val('add');
							$('.saveBtn').show();
							$('.saveBtn2').show();
							$('.updateBtn').hide();
							$('.updateBtn2').hide();
							$('#subgroup_name').val('');
							$('select[name=main_group]').val('10000');
							$('.selectpicker').selectpicker('refresh');
							}else{
							$('#subgroup_code').val(data.SubActGroupID);
							$('#subgroup_name').val(data.SubActGroupName);
							$('select[name=main_group]').val(data.ActGroupID);
							$('.selectpicker').selectpicker('refresh');
							
							var GroupFor = '';
							if(data.IsAccountHead == 'Y')
							{
								GroupFor = 'Ledger';
							}
							if(data.IsCustomer == 'Y')
							{
								GroupFor = 'Customer';
							}
							if(data.IsVendor == 'Y')
							{
								GroupFor = 'Vendor';
							}
							if(data.IsStaff == 'Y')
							{
								GroupFor = 'Staff';
							}
							$('select[name=GroupFor]').val(GroupFor);
							$('.selectpicker').selectpicker('refresh');
							GetChangedData(data.ActGroupID)
							$('#form_mode').val('edit');
							$('.saveBtn').hide();
							$('.updateBtn').show();
							$('.saveBtn2').hide();
							$('.updateBtn2').show();
							setTimeout(function(){
								$('select[name=AccountSubGroupID1]').val(data.SubActGroupID1);
								$('.selectpicker').selectpicker('refresh');
							}, 1000);
						}
					}
				});
			}
		});
		
		// Initialize For SubgroupID
		$( "#subgroup_code" ).autocomplete({
			
			source: function( request, response ) {
				// Fetch data
				
				$.ajax({
					url: "<?=base_url()?>admin/accounts_master/get_accounts_subgroup2",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			select: function (event, ui) {
				$('#subgroup_code').val(ui.item.value); // display the selected text
				$('#subgroup_name').val(ui.item.label); // display the selected text
				$('#form_mode').val('edit');
				$('.saveBtn').hide();
				$('.updateBtn').show();
				$('.saveBtn2').hide();
				$('.updateBtn2').show();
				$('#subgroup_name').focus();
			}
		});
		
		$('.get_AccountID').on('click',function(){ 
            account_subgroupID = $(this).attr("data-id");
            $.ajax({
				url:"<?php echo admin_url(); ?>accounts_master/get_account_subgroup_details2",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{account_subgroupID:account_subgroupID,},
				
				success:function(data){
                    if(empty(data)){
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        $('#subgroup_name').val('');
                        $('.selectpicker').selectpicker('refresh');
                        $('#form_mode').val('add');
						}else{
                        $('#subgroup_code').val(data.SubActGroupID);
                        $('#subgroup_name').val(data.SubActGroupName);
                        $('select[name=main_group]').val(data.ActGroupID);
                        $('.selectpicker').selectpicker('refresh');
						
						var GroupFor = '';
						if(data.IsAccountHead == 'Y')
						{
							GroupFor = 'Ledger';
						}
						if(data.IsCustomer == 'Y')
						{
							GroupFor = 'Customer';
						}
						if(data.IsVendor == 'Y')
						{
							GroupFor = 'Vendor';
						}
						if(data.IsStaff == 'Y')
						{
							GroupFor = 'Staff';
						}
                        $('select[name=GroupFor]').val(GroupFor);
                        $('.selectpicker').selectpicker('refresh');
						GetChangedData(data.ActGroupID)
                        $('#form_mode').val('edit');
                        $('.saveBtn').hide();
                        $('.updateBtn').show();
                        $('.saveBtn2').hide();
                        $('.updateBtn2').show();
						setTimeout(function(){
							$('select[name=AccountSubGroupID1]').val(data.SubActGroupID1);
							$('.selectpicker').selectpicker('refresh');
						}, 1000);
					}
				}
			});
            $('#AccountSubgroup').modal('hide');
		});
		
		$('#main_group').on('change',function(){
			var AccountMainGroupID = $(this).val();
			var form_mode = $('#form_mode').val();
            $.ajax({
                url:"<?php echo admin_url(); ?>accounts_master/GetAccountSubGroupID1",
                dataType:"JSON",
                method:"POST",
                cache: false,
                data:{AccountMainGroupID:AccountMainGroupID,},
                success:function(data){
                    $("#AccountSubGroupID1").children().remove();
                    $("#AccountSubGroupID1").append('<option value="">--Select--</option>');
					for (var i = 0; i < data.length; i++) {
						$("#AccountSubGroupID1").append('<option value="'+data[i]["SubActGroupID1"]+'">'+data[i]["SubActGroupName"]+'</option>');
					}
					$('.selectpicker').selectpicker('refresh');
					$("#AccountSubGroupID2").children().remove();
					$('.selectpicker').selectpicker('refresh');
				}
			});
		})
		
		function GetChangedData(AccountMainGroupID){
			var form_mode = $('#form_mode').val();
            $.ajax({
                url:"<?php echo admin_url(); ?>accounts_master/GetAccountSubGroupID1",
                dataType:"JSON",
                method:"POST",
                cache: false,
                data:{AccountMainGroupID:AccountMainGroupID,},
                success:function(data){
                    $("#AccountSubGroupID1").children().remove();
                    $("#AccountSubGroupID1").append('<option value="">--Select--</option>');
					for (var i = 0; i < data.length; i++) {
						$("#AccountSubGroupID1").append('<option value="'+data[i]["SubActGroupID1"]+'">'+data[i]["SubActGroupName"]+'</option>');
					}
					$('.selectpicker').selectpicker('refresh');
					$("#AccountSubGroupID2").children().remove();
					$('.selectpicker').selectpicker('refresh');
				}
			});
		}
		
		
		// Save New SubGroup
        $('.saveBtn').on('click',function(){ 
            SubGroupID = $('#subgroup_code').val();
            SubGroupName = $('#subgroup_name').val();
            MainGroupID = $('#AccountSubGroupID1').val();
            GroupFor = $('#GroupFor').val();
            if(SubGroupID == '' || SubGroupID == null){
				alert('Account Sub-GroupID2 Cannot blank');
				}else if(SubGroupName == '' || SubGroupName == null){
				alert('Account Sub-GroupID2 Name Cannot blank');
				}else if(MainGroupID == '' || MainGroupID == null){
				alert('Please Select Account Main Group');
				}else if(GroupFor == '' || GroupFor == null){
				alert('Please Select Group For');
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>accounts_master/SaveSubGroup2",
					dataType:"JSON",
					method:"POST",
					data:{SubGroupID:SubGroupID,SubGroupName:SubGroupName,MainGroupID:MainGroupID,GroupFor:GroupFor
					},
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
							var maingroup_id = "10000";
							$.ajax({
								url:"<?php echo admin_url(); ?>accounts_master/NextAccountGroupID2",
								dataType:"JSON",
								method:"POST",
								cache: false,
								data:{maingroup_id:maingroup_id},
								success:function(data){
									$('select[name=main_group]').val('10000');
									$('.selectpicker').selectpicker('refresh'); 
									$('select[name=GroupFor]').val('');
									$('.selectpicker').selectpicker('refresh'); 
									$('#subgroup_code').val(data);
									$('#subgroup_name').val('');
									GetChangedData(10000)
									$('.saveBtn').show();
									$('.updateBtn').hide();
									$('.saveBtn2').show();
									$('.updateBtn2').hide();
								}
							});
							}else{
							alert_float('warning', 'Something went wrong...');
						}
					}
				});
			}
		}); 
		
		// Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            SubGroupID = $('#subgroup_code').val();
            SubGroupName = $('#subgroup_name').val();
            MainGroupID = $('#AccountSubGroupID1').val();
            GroupFor = $('#GroupFor').val();
			if(SubGroupID == '' || SubGroupID == null){
				alert('Account Sub-GroupID2 Cannot blank');
				}else if(SubGroupName == '' || SubGroupName == null){
				alert('Account Sub-GroupID2 Name Cannot blank');
				}else if(MainGroupID == '' || MainGroupID == null){
				alert('Please Select Account Main Group');
				}else if(GroupFor == '' || GroupFor == null){
				alert('Please Select Group For');
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>accounts_master/UpdateSubGroup2",
					dataType:"JSON",
					method:"POST",
					data:{SubGroupID:SubGroupID,SubGroupName:SubGroupName,MainGroupID:MainGroupID,GroupFor:GroupFor
					},
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
							var maingroup_id = "50000";
							$.ajax({
								url:"<?php echo admin_url(); ?>accounts_master/NextAccountGroupID2",
								dataType:"JSON",
								method:"POST",
								cache: false,
								data:{maingroup_id:maingroup_id},
								success:function(data){
									$('select[name=main_group]').val('10000');
									$('.selectpicker').selectpicker('refresh'); 
									$('select[name=GroupFor]').val('');
									$('.selectpicker').selectpicker('refresh'); 
									$('#subgroup_code').val(data);
									$('#subgroup_name').val('');
									GetChangedData(10000)
									$('.saveBtn').show();
									$('.updateBtn').hide();
									$('.saveBtn2').show();
									$('.updateBtn2').hide();
								}
							});
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
		table = document.getElementById("table_AccountSubgroup");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
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
							}else{
							tr[i].style.display = "none";
						} 
					}
				}   
			}
		}
	}
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_AccountSubgroup tbody");
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
<style>
	
	#account_id {
	text-transform: uppercase;
	}
	#table_AccountSubgroup td:hover {
	cursor: pointer;
	}
	#table_AccountSubgroup tr:hover {
	background-color: #ccc;
	}
	
	.table-AccountSubgroup          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
	.table-AccountSubgroup thead th { position: sticky; top: 0; z-index: 1; }
	.table-AccountSubgroup tbody th { position: sticky; left: 0; }
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

