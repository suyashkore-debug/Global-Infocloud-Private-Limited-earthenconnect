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
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Item Main Group</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait Create new Main Item Group...</div>
								<div class="searchh4" style="display:none;">Please wait update Main Item Group...</div>
							</div>
							<br>
							<div class="col-md-2">
								<?php
									$nextItemGroupID = end($table_data)['id'] + 1;
								?>
								<div class="form-group" app-field-wrapper="MainItemGroupID">
									<small class="req text-danger">* </small>
									<label for="MainItemGroupID" class="control-label">Main ItemGroup ID</label>
									<input type="text" id="MainItemGroupID" name="MainItemGroupID" class="form-control" value="<?php echo $nextItemGroupID; ?>">
								</div>
								<input type="hidden" id="NextMainItemGroupID" name="NextMainItemGroupID" class="form-control" value="<?php echo $nextItemGroupID; ?>">
							</div>
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="MainItemGroupName">
									<small class="req text-danger">* </small>
									<label for="MainItemGroupName" class="control-label">Main ItemGroup Name</label>
									<input type="text" id="MainItemGroupName" name="MainItemGroupName" class="form-control" value="">
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="MainItemGroupPrefix">
									<small class="req text-danger">* </small>
									<label for="MainItemGroupPrefix" class="control-label">Prefix</label>
									<input type="text" id="MainItemGroupPrefix" name="MainItemGroupPrefix" class="form-control" value="">
								</div>
							</div>
							
							<div class="clearfix"></div>
							<br>
							<div class="col-md-12">
								<?php if (has_permission_new('itemsmaingrp', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission_new('itemsmaingrp', '', 'edit')) {
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
						
						<div class="clearfix"></div>
						<!-- Iteme List Model-->
						
						<div class="modal fade mainItemGroup_List" id="mainItemGroup_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Main Item Group List</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-mainItemGroup_List tableFixHead2">
											<table class="tree table table-striped table-bordered table-mainItemGroup_List tableFixHead2" id="table_mainItemGroup_List" width="100%">
												<thead>
													<tr>
														<th style="text-align:left;" class="sortablePop">Main Item Group ID</th>
														<th style="text-align:left;" class="sortablePop">Prefix</th>
														<th style="text-align:left;" class="sortablePop">Main Item Group Name</th>
													</tr>
												</thead>
												<tbody>
													<?php
														foreach ($table_data as $key => $value) {
														?>
														<tr class="get_MainItemGroup" data-id="<?php echo $value["id"]; ?>">
															<td><?php echo $value['id'];?></td>
															<td><?php echo $value['prefix'];?></td>
															<td><?php echo $value['name'];?></td>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>

<script>
    $(document).ready(function(){
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $("#MainItemGroupID").dblclick(function(){
            $('#mainItemGroup_List').modal('show');
            $('#mainItemGroup_List').on('shown.bs.modal', function () {
				$('#myInput1').focus();
			})
		});
		// ItemID Typing Validation
        $("#MainItemGroupPrefix").keypress(function (key) {
            var keycode = (key.which) ? key.which : key.keyCode;
            if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123))  
            {     
                return true;    
			}else
            {
                return false;
			}
		});
		function ResetForm()
		{
			var NextItemGroupID = $('#NextMainItemGroupID').val();
			$('#MainItemGroupID').val(NextItemGroupID);
			$('#MainItemGroupName').val('');
			$('#MainItemGroupPrefix').val('');
			$("#MainItemGroupPrefix").removeAttr('disabled');           
			$('.saveBtn').show();
			$('.saveBtn2').show();
			$('.updateBtn').hide();
			$('.updateBtn2').hide();
		}
		// Empty and open create mode
		$("#MainItemGroupID").focus(function(){
			ResetForm();
		});
        
		// Cancel selected data
		$(".cancelBtn").click(function(){
			ResetForm();
		});
        
		//======================= On Blur ItemID Get All Date ==========================
		$('#MainItemGroupID').blur(function(){ 
			ItemGroupID = $(this).val();
			if(ItemGroupID == ''){
				
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>invoice_items/GetMainItemGroupDetailByID",
					dataType:"JSON",
					method:"POST",
					data:{ItemGroupID:ItemGroupID},
					beforeSend: function () {
						$('.searchh2').css('display','block');
						$('.searchh2').css('color','blue');
					},
					complete: function () {
						$('.searchh2').css('display','none');
					},
					success:function(data){
						init_selectpicker();
						if(data == null){
							ResetForm();
							}else{
							$('#MainItemGroupName').val(data.name);
							$('#MainItemGroupPrefix').val(data.prefix);
							$("#MainItemGroupPrefix").attr('disabled','disabled');
							$('.saveBtn').hide();
							$('.updateBtn').show();
							$('.saveBtn2').hide();
							$('.updateBtn2').show();
						} 
					}
				});
			}
			
		});
		
		$('#MainItemGroupPrefix').blur(function(){ 
			MainItemGroupPrefix = $(this).val();
			if(MainItemGroupPrefix == ''){
				
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>invoice_items/CheckPrefixExit",
					dataType:"JSON",
					method:"POST",
					data:{MainItemGroupPrefix:MainItemGroupPrefix},
					beforeSend: function () {
						$('.searchh2').css('display','block');
						$('.searchh2').css('color','blue');
					},
					complete: function () {
						$('.searchh2').css('display','none');
					},
					success:function(data){
						if(data){
							alert("Prefix Already Exist.");
							$('#MainItemGroupPrefix').val("");
						}
					}
				});
			}
		});
        
		$('.get_MainItemGroup').on('click',function(){ 
			ItemGroupID = $(this).attr("data-id");
			$.ajax({
				url:"<?php echo admin_url(); ?>invoice_items/GetMainItemGroupDetailByID",
				dataType:"JSON",
				method:"POST",
				data:{ItemGroupID:ItemGroupID},
				beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
				complete: function () {
					$('.searchh2').css('display','none');
				},
				success:function(data){
					$('#MainItemGroupID').val(data.id);
					$('#MainItemGroupName').val(data.name);
					$('#MainItemGroupPrefix').val(data.prefix);
					$("#MainItemGroupPrefix").attr('disabled','disabled');
					$('.saveBtn').hide();
					$('.updateBtn').show();
					$('.saveBtn2').hide();
					$('.updateBtn2').show();
				}
			});
			$('#mainItemGroup_List').modal('hide');
		});
        
		//===================== Save New MainItemGroup =================================
		$('.saveBtn').on('click',function(){ 
			MainItemGroupID = $('#MainItemGroupID').val();
			MainItemGroupName = $('#MainItemGroupName').val();
			MainItemGroupPrefix = $('#MainItemGroupPrefix').val();
			if(MainItemGroupID == ""){
				alert("Please Refresh Page, something went wrong");
				}else if(MainItemGroupName == ""){
				alert("Please Enter Item Main Group Name");
				}else if(MainItemGroupPrefix == ""){
				alert("Please Enter Prefix");
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>invoice_items/SaveMainItemGroup",
					dataType:"JSON",
					method:"POST",
					data:{MainItemGroupID:MainItemGroupID,MainItemGroupName:MainItemGroupName,MainItemGroupPrefix:MainItemGroupPrefix
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
							var NextItemGroupID = $('#NextMainItemGroupID').val();
							var newGroupID = parseInt(NextItemGroupID) + 1;
							$('#MainItemGroupID').val(newGroupID);
							ResetForm();
							}else{
							alert_float('warning', 'Something went wrong...');
							ResetForm();
						}
					}
				});
			}
		});
		//===================== Update Exiting Item ====================================
		$('.updateBtn').on('click',function(){ 
			MainItemGroupID = $('#MainItemGroupID').val();
			MainItemGroupName = $('#MainItemGroupName').val();
			if(MainItemGroupID == ""){
				alert("Please Refresh Page, something went wrong");
				}else if(MainItemGroupName == ""){
				alert("Please Enter Item Main Group Name");
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>invoice_items/UpdateMainItemGroup",
					dataType:"JSON",
					method:"POST",
					data:{MainItemGroupID:MainItemGroupID,MainItemGroupName:MainItemGroupName
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
							ResetForm();
							}else{
							alert_float('warning', 'Something went wrong...');
							ResetForm();
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
		table = document.getElementById("table_mainItemGroup_List");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else if(td1){
					txtValue = td1.textContent || td1.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						}else{
						tr[i].style.display = "none";
					} 
				}   
			}
		}
	}
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_mainItemGroup_List tbody");
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
	
	#item_code1 {
    text-transform: uppercase;
	}
	#table_mainItemGroup_List td:hover {
    cursor: pointer;
	}
	#table_mainItemGroup_List tr:hover {
    background-color: #ccc;
	}
	
    .table-mainItemGroup_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-mainItemGroup_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-mainItemGroup_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>