<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Master</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Qc Parameter Master</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait Create new Qc Parameter...</div>
								<div class="searchh4" style="display:none;">Please wait update Qc Parameter...</div>
							</div>
							<br>
							
							<div class="col-md-2">
							    <div class="form-group" app-field-wrapper="ids">
									<label for="ids" class="control-label">ID</label>
									<input type="text" id="ids" name="ids" class="form-control" value="" readonly>
								</div>							
							</div> 
							
							<div class="col-md-6">
								<div class="form-group" app-field-wrapper="parametername">
									<small class="req text-danger">* </small>
									<label for="parametername" class="control-label">Parameter Name</label>
									<input type="text" id="parametername" name="parametername" class="form-control" value="">
								</div>							
							</div>
							
							<div class="clearfix"></div>
							<br><br>
							<div class="col-md-12">
								<?php if (has_permission('qcparameter_master', '', 'create')) {
								?>
								<button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
								<?php
									}else{
								?>
								<button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
								<?php
								}?>
								
								<?php if (has_permission('qcparameter_master', '', 'edit')) {
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
						
						<div class="modal fade QcMaster_List" id="QcMaster_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Qc Parameter List</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-QcMaster_List tableFixHead2">
											<table class="tree table table-striped table-bordered table-QcMaster_List tableFixHead2" id="table_QcMaster_List" width="100%">
												<thead>
													<tr>
														<th style="text-align:left;" class="sortablePop">Sr.No </th>
														<th style="text-align:left;" class="sortablePop">Parameter Name</th>
													</tr>
												</thead>
												<tbody>
													<?php
														foreach ($table_data as $key => $value) {
														?>
														<tr class="get_ParameterMaster" data-id="<?php echo $value["id"]; ?>">
															<td><?php echo $value['id'];?></td>
															<td><?php echo $value['ParameterName'];?></td>
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
        
        $("#parametername").dblclick(function(){
            $('#QcMaster_List').modal('show');
            $('#QcMaster_List').on('shown.bs.modal', function () {
				$('#myInput1').focus();
			})
		});
        
		// Cancel selected data
        $(".cancelBtn").click(function(){
            
            $('#ids').val('');
            $('#parametername').val('');
            $('select[name=ItemID]').val('');
            $('.selectpicker').selectpicker('refresh');  
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
		});
        
        $('.get_ParameterMaster').on('click',function(){ 
            ID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>Traceability/GetParameterDetailsByID",
                dataType:"JSON",
                method:"POST",
                data:{ID:ID},
                beforeSend: function () {
					$('.searchh2').css('display','block');
					$('.searchh2').css('color','blue');
				},
                complete: function () {
					$('.searchh2').css('display','none');
				},
                success:function(data){
					
					$('#ids').val(data.id);
					$('#parametername').val(data.ParameterName);
					$('.saveBtn').hide();
					$('.updateBtn').show();
					$('.saveBtn2').hide();
					$('.updateBtn2').show();
				}
			});
            $('#QcMaster_List').modal('hide');
		});
        
		// Save New State
        $('.saveBtn').on('click',function(){ 
            ParameterName = $('#parametername').val();
           
            if(ParameterName == '' || ParameterName == null){
				alert('Please enter parameter name');
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>Traceability/SaveQcParameter",
					dataType:"JSON",
					method:"POST",
					data:{ParameterName:ParameterName
					},
					beforeSend: function () {
						$('.searchh3').css('display','block');
						$('.searchh3').css('color','blue');
					},
					complete: function () {
						$('.searchh3').css('display','none');
					},
					success:function(data){
						if(data.status == true){
							
							alert_float('success', 'Record created successfully...');
							location.reload();
							}else{
							alert_float('warning', 'Something went wrong...');
						}
					}
				});
			}
		});
		
		// Update Exiting Item
		$('.updateBtn').on('click',function(){ 
			ParameterName = $('#parametername').val();
            ID = $('#ids').val();
            if(ParameterName == '' || ParameterName == null){
				alert('Please enter parameter name');
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>Traceability/UpdateQcParameter",
					dataType:"JSON",
					method:"POST",
					data:{ParameterName:ParameterName,ID:ID
					},
					beforeSend: function () {
						$('.searchh4').css('display','block');
						$('.searchh4').css('color','blue');
					},
					complete: function () {
						$('.searchh4').css('display','none');
					},
					success:function(data){
						if(data.status == true){
							alert_float('success', 'Record updated successfully...');
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
		table = document.getElementById("table_PointMaster_List");
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
						}else if(td2){
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
		var table = $("#table_PointMaster_List tbody");
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
<script>
    function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode = 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57)){
			return false;
		}
		return true;
	}
</script>
<style>
	
	#item_code1 {
    text-transform: uppercase;
	}
	#table_PointMaster_List td:hover {
    cursor: pointer;
	}
	#table_PointMaster_List tr:hover {
    background-color: #ccc;
	}
	
    .table-PointMaster_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-PointMaster_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-PointMaster_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>