<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
</style>
<style>
	.table-invoice-items tbody{
	display: block;
	max-height: 350px;
	overflow-y: scroll;
	}
	.table-invoice-items thead, .table-invoice-items tbody tr{
	display: table;
	table-layout: fixed;
	width: 100%;
	}
	.table-invoice-items thead{
	width: calc(100% - 1.1em);
	}
	.table-invoice-items thead{
	position: relative;
	}
	.table-invoice-items thead th:last-child:after{
	content: ' ';
	position: absolute;
	background-color: #337ab7;
	width: 1.3em;
	height: 38px;
	right: -1.3em;
	top: 0;
	border-bottom: 2px solid #ddd;
	}
</style>
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
            					<li class="breadcrumb-item active" aria-current="page"><b>Item List</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						
						<?php hooks()->do_action('before_items_page_content'); ?>
						
						
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="row">
							<div class="col-md-6">
								<div class="custom_button">&nbsp;&nbsp;
									<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
								</div>
							</div>
							<div class="col-md-6">
								<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
								
							</div>
						</div>
						
						<div class="table-daily_report tableFixHead2">
							
							<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
								
								<thead>
									
									<tr style="display:none;">
										<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
									</tr>
									<tr>
										<th class="sortable" style="text-align:left;">Item Code </th>
										<th class="sortable" style="text-align:left;">Item Name</th>
										<th class="sortable" style="text-align:left;">MeasuredIn</th>
										<th class="sortable" style="text-align:left;">Division Name</th>
										<th class="sortable" style="text-align:left;">Group Name2</th>
										<th style="text-align:left;">Image</th>
										<th style="text-align:left; display:none;">HsnCode</th>
										<th style="text-align:left;display:none;">Tax</th>
										<th style="text-align:left;display:none;">BowlQty</th>
										<th style="text-align:left;display:none;">CaseQty</th>
										<th style="text-align:left;display:none;">CrateQty</th>
										<th style="text-align:left;display:none;">MinQty</th>
										<th style="text-align:left;display:none;">CaseWeight</th>
										<th style="text-align:left;display:none;">LocalSupply</th>
										<th style="text-align:left;display:none;">OutstSupply</th>
										<th style="text-align:left;display:none;">MonitorStock</th>
										<th style="text-align:left;display:none;">RackId</th>
										<th style="text-align:left;display:none;">SubrackId</th>
										<th style="text-align:left;display:none;">MinWtg</th>
										<th style="text-align:left;display:none;">MinDay</th>
										<th style="text-align:left;display:none;">Isactive</th>
										<!--<th style="text-align:left;display:none;">UserId</th>-->
										
									</tr>
								</thead>
								<tbody>
									<?php
										foreach ($table_data as $key => $value) {
											
											if(!empty($value["image"]))
											{
												$url = base_url()."media/Product/".$value["image"];
												
												$img = "<a href='".$url."' target='_blank'><img src='".$url."' class='img-thumbnail' style='height:70px; width:70px;'></a>";
											}
											else
											{
												$img = "";
											}
										?>
										<tr>
											<?php /*if (has_permission('items', '', 'edit')) {
												$item_code = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $value['item_code'] . '">'.$value['item_code'].'</a>';
												
												}else{
												$item_code = $value['item_code'];
											}*/
											$item_code = $value['item_code'];
											?>
											
											
											<td><?php echo $item_code;?></td>
											<?php 
												/*if (has_permission('items', '', 'edit')) {
													$description = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $value['item_code'] . '">'.$value['description'].'</a>';
													
													}else{
													$description = $value['description'];
												}*/
												$description = $value['description'];
											?>
											
											<td><?php echo $description;?></td>
											
											<td><?php echo $value["unit"];?></td>
											<td><?php echo $value["group_name"];?></td>
											<td><?php echo $value["subgroup_name"];?></td>
											<td><?php echo $img;?></td>
											
											<td style="display:none;"><?php echo $value["hsn_code"];?></td>
											<td style="display:none;"><?php echo $value["taxrate"];?></td>
											<td style="display:none;"><?php echo $value["bowl_qty"];?></td>
											<td style="display:none;"><?php echo $value["case_qty"];?></td>
											<td style="display:none;"><?php echo $value["crate_qty"];?></td>
											<td style="display:none;"><?php echo $value["min_qty"];?></td>
											<td style="display:none;"><?php echo $value["case_weight"];?></td>
											<td style="display:none;"><?php echo $value["local_supply_in"];?></td>
											<td style="display:none;"><?php echo $value["outst_supply_in"];?></td>
											<td style="display:none;"><?php echo $value["monitorstock"];?></td>
											<td style="display:none;"><?php echo $value["rack_id"];?></td>
											<td style="display:none;"><?php echo $value["subrack_id"];?></td>
											<td style="display:none;"><?php echo $value["MinWtg"];?></td>
											<td style="display:none;"><?php echo $value["min_day"];?></td>
											<td style="display:none;"><?php echo $value["isactive"];?></td>
											<!--<td style="display:none;"><?php// echo $value["useriditem"];?></td>-->
											
											
										</tr>
										<?php
										}
									?>
								</tbody>
							</table>   
						</div>
						<span id="searchh3" style="display:none;">Please wait data exporting.....</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/invoice_items/item'); ?>

<!-- Item Division Model -->

<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<?php echo _l('item_groups'); ?>
				</h4>
			</div>
			<div class="modal-body">
				<?php if(has_permission_new('itemsdivision','','create')){ ?>
					<div class="input-group">
						<input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
						<span class="input-group-btn">
							<button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('save'); ?></button>
						</span>
					</div>
					<hr />
				<?php } ?>
				<div class="row">
					<div class="container-fluid">
						<table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
							<thead>
								<tr>
									<th><?php echo _l('sr_no'); ?></th>
									<th><?php echo _l('item_group_name'); ?></th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items_groups as $group){ ?>
									<tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
										<td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
										<td data-order="<?php echo $group['name']; ?>">
											<span class="group_name_plain_text"><?php echo $group['name']; ?></span>
											<div class="group_edit hide">
												<div class="input-group">
													<input type="text" class="form-control">
													<span class="input-group-btn">
														<button class="btn btn-info p8 update-item-group" type="button"><?php echo _l('submit'); ?></button>
													</span>
												</div>
											</div>
											<!--<div class="row-options">
												
											</div>-->
										</td>
										<td>
											<?php if(has_permission_new('itemsdivision','','edit')){ ?>
												<a href="#" class="edit-item-group">
													<?php echo _l('edit'); ?>
												</a>
											<?php } ?>
											<?php if(has_permission_new('itemsdivision','','delete')){ ?>
												| <a href="<?php echo admin_url('invoice_items/delete_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
													<?php echo _l('delete'); ?>
												</a>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
			</div>
		</div>
	</div>
</div>

<!-- End Item Division -->

<!-- Item Main Group -->
<div class="modal fade" id="maingroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<?php echo _l('item_main_groups'); ?>
				</h4>
			</div>
			<div class="modal-body">
				<?php if(has_permission_new('itemsmaingrp','','create')){ ?>
					<div class="row">
						<!--<div class="col-md-4">
							
							<input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">
							
						</div>-->
						<div class="col-md-4">
							
							<input type="text" name="item_main_group_name" id="item_main_group_name" class="form-control" placeholder="<?php echo _l('item_main_group_name'); ?>">
							
							
						</div>
						
						<div class="col-md-4">
							<span class="btn" style="top: -7px;position: relative;">
								<button class="btn btn-info p7" type="button" id="new-item-main-group-insert"><?php echo _l('save'); ?></button>
							</span>
						</div>
					</div>
					
					<hr />
				<?php } ?>
				<div class="row">
					<div class="container-fluid">
						<table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
							<thead>
								<tr>
									<th><?php echo _l('id'); ?></th>
									<!--<th><?php echo _l('item_main_group_id'); ?></th>-->
									<th><?php echo _l('item_main_group'); ?></th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items_main_groups as $group){ ?>
									<tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
										<td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
										<!--<td data-order="<?php echo $group['item_main_group_id']; ?>"><?php echo $group['item_main_group_id']; ?></td>-->
										<td data-order="<?php echo $group['name']; ?>">
											<span class="group_name_plain_text"><?php echo $group['name']; ?></span>
											<div class="main_group_edit hide">
												<div class="input-group">
													<input type="text" class="form-control">
													<span class="input-group-btn">
														<button class="btn btn-info p8 update-item-main-group" type="button"><?php echo _l('submit'); ?></button>
													</span>
												</div>
											</div>
											<!--<div class="row-options">
												
											</div>-->
										</td>
										<td>
											<?php if(has_permission_new('itemsmaingrp','','edit')){ ?>
												<a href="#" class="edit-item-main-group">
													<?php echo _l('edit'); ?>
												</a>
											<?php } ?>
											<?php if(has_permission_new('itemsmaingrp','','delete')){ ?>
												| <a href="<?php echo admin_url('invoice_items/delete_main_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
													<?php echo _l('delete'); ?>
												</a>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
			</div>
		</div>
	</div>
</div>

<!-- End Item Main Group-->

<!-- Item Sub Group -->

<div class="modal fade" id="subgroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<?php echo _l('item_sub_groups'); ?>
				</h4>
			</div>
			<div class="modal-body">
				<?php if(has_permission_new('itemssubgrp','','create')){ ?>
					<div class="row">
						<div class="col-md-4">
							
							<!--<input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">-->
							<?php 
								//print_r($items_main_groups);
								$s_attrs = array('data-none-selected-text'=>'Select Main Group');
								$selected = '';
							echo render_select('item_main_group_id1',$items_main_groups,array('id','name'),'',$selected,$s_attrs); ?>
							<!--<select id="item_main_group_id1" name="item_main_group_id1" class="form-control">
								<?php 
									foreach ($items_main_groups as $key => $value) {
										# code...
									?>
									<option value="<?php echo $value['item_main_group_id'];?>"><?php echo $value["name"];?></option>
									<?php
									}
								?>
								
							</select>-->
							
						</div>
						<div class="col-md-4">
							
							<input type="text" name="item_main_group_name" id="item_sub_group_name" class="form-control" placeholder="<?php echo _l('item_sub_group_name'); ?>">
							
							
						</div>
						
						<div class="col-md-4">
							<span class="btn" style="top: -7px;position: relative;">
								<button class="btn btn-info p7" type="button" id="new-item-sub-group-insert"><?php echo _l('save'); ?></button>
							</span>
						</div>
					</div>
					
					<hr />
				<?php } ?>
				<div class="row">
					<div class="container-fluid">
						<table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
							<thead>
								<tr>
									<th><?php echo _l('id'); ?></th>
									<th><?php echo _l('item_main_group_name'); ?></th>
									<th><?php echo _l('item_sub_group'); ?></th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items_sub_groups as $group){ ?>
									<tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
										<td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
										<td data-order="<?php echo $group['main_group_id']; ?>"><?php $ss = get_main_group_name($group['main_group_id']);
										echo $ss->name; ?>
										<?php $getall_main_group = get_main_group(); 
											//print_r($getall_main_group);
										?>
										
										</td>
										<td data-order="<?php echo $group['name']; ?>">
											<span class="subgroup_name_plain_text"><?php echo $group['name']; ?></span>
											<div class="sub_group_edit hide">
												<?php
													
													
													$s_attrs = array('data-none-selected-text'=>'Select Main Group');
													
													$selected = '';
												echo render_select('item_main_group_id_edit',$getall_main_group,array('id','name'),'',$group['main_group_id'],$s_attrs); ?>
												
												<div class="input-group">
													<input type="text" class="form-control" id="subgroup_name">
													<span class="input-group-btn">
														<button class="btn btn-info p8 update-item-sub-group" type="button"><?php echo _l('submit'); ?></button>
													</span>
												</div>
											</div>
											
										</td>
										<td><?php if(has_permission_new('itemssubgrp','','edit')){ ?>
											<a href="#" class="edit-item-sub-group">
												<?php echo _l('edit'); ?>
											</a>
										<?php } ?>
										<?php if(has_permission_new('itemssubgrp','','delete')){ ?>
											| <a href="<?php echo admin_url('invoice_items/delete_sub_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
												<?php echo _l('delete'); ?>
											</a>
										<?php } ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
			</div>
		</div>
	</div>
</div>

<!-- End Item Sub Group-->

<?php init_tail(); ?>
<script>
	$(function(){
		
		var notSortableAndSearchableItemColumns = [];
		<?php if(has_permission_new('items','','delete')){ ?>
			notSortableAndSearchableItemColumns.push(0);
		<?php } ?>
		
		initDataTable('.table-invoice-items', admin_url+'invoice_items/table', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[1,'asc']);
		
		if(get_url_param('groups_modal')){
			// Set time out user to see the message
			setTimeout(function(){
				$('#groups').modal('show');
			},1000);
		}
		
		
		// Item Division Add 
		$('#new-item-group-insert').on('click',function(){
			var group_name = $('#item_group_name').val();
			if(group_name != ''){
				$.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
					window.location.href = admin_url+'invoice_items?groups_modal=true';
				});
				}else{
				alert("please enter division name..");
			}
		});
		
		if(get_url_param('main_groups_modal')){
			// Set time out user to see the message
			setTimeout(function(){
				$('#maingroups').modal('show');
			},1000);
		}
		
		if(get_url_param('sub_groups_modal')){
			// Set time out user to see the message
			setTimeout(function(){
				$('#subgroups').modal('show');
			},1000);
		}
		
		
		// Item Main Group add
		$('#new-item-main-group-insert').on('click',function(){
			var main_group_name = $('#item_main_group_name').val();
			//var main_group_id = $('#item_main_group_id').val();
			if(main_group_name != ''){
				$.post(admin_url+'invoice_items/add_main_group',{name:main_group_name}).done(function(){
					window.location.href = admin_url+'invoice_items?main_groups_modal=true';
				});
			}
		});
		
		
		// Item Sub Group add
		$('#new-item-sub-group-insert').on('click',function(){
			var group_name = $('#item_sub_group_name').val();
			var main_group_id = $('#item_main_group_id1').val();
			//var main_group_id = $( "#item_main_group_id1:selected" ).val();
			
			
			if(group_name != '' && main_group_id != ''){
				$.post(admin_url+'invoice_items/add_sub_group',{name:group_name, id:main_group_id}).done(function(){
					window.location.href = admin_url+'invoice_items?sub_groups_modal=true';
				});
				}else{
				alert("please select main group and enter group name..");
			}
		});
		
		$('body').on('click','.update-item-sub-group',function(){
			var tr = $(this).parents('tr');
			var subgroup_id = tr.attr('data-group-row-id');
			name = tr.find('#subgroup_name').val();
			main_group_id = tr.find('select#item_main_group_id_edit option:selected').val();
			//alert(main_group_id);
			alert(name);
			if(name != ''){
				$.post(admin_url+'invoice_items/update_sub_group/'+subgroup_id,{name:name,main_group_id:main_group_id}).done(function(){
					//window.location.href = admin_url+'invoice_items';
					window.location.href = admin_url+'invoice_items?sub_groups_modal=true';
				});
				}else{
				alert("please enter group name");
			}
		});
		
		
		$('body').on('click','.edit-item-group',function(e){
			e.preventDefault();
			var tr = $(this).parents('tr'),
			group_id = tr.attr('data-group-row-id');
			tr.find('.group_name_plain_text').toggleClass('hide');
			tr.find('.group_edit').toggleClass('hide');
			tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
		});
		
		$('body').on('click','.update-item-group',function(){
			var tr = $(this).parents('tr');
			var group_id = tr.attr('data-group-row-id');
			name = tr.find('.group_edit input').val();
			if(name != ''){
				$.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
					window.location.href = admin_url+'invoice_items?groups_modal=true';
				});
				}else{
				alert("please enter division name..");
			}
		});
		
		$('body').on('click','.edit-item-main-group',function(e){
			e.preventDefault();
			var tr = $(this).parents('tr'),
			group_id = tr.attr('data-group-row-id');
			tr.find('.group_name_plain_text').toggleClass('hide');
			tr.find('.main_group_edit').toggleClass('hide');
			tr.find('.main_group_edit input').val(tr.find('.group_name_plain_text').text());
		});
		
		$('body').on('click','.edit-item-sub-group',function(e){
			e.preventDefault();
			var tr = $(this).parents('tr'),
			group_id = tr.attr('data-group-row-id');
			tr.find('.subgroup_name_plain_text').toggleClass('hide');
			tr.find('.sub_group_edit').toggleClass('hide');
			tr.find('.sub_group_edit input').val(tr.find('.subgroup_name_plain_text').text());
		});
		
		$('body').on('click','.update-item-main-group',function(){
			var tr = $(this).parents('tr');
			var group_id = tr.attr('data-group-row-id');
			name = tr.find('.main_group_edit input').val();
			if(name != ''){
				$.post(admin_url+'invoice_items/update_main_group/'+group_id,{name:name}).done(function(){
					//window.location.href = admin_url+'invoice_items';
					window.location.href = admin_url+'invoice_items?main_groups_modal=true';
				});
				}else{
				alert("please enter main group name");
			}
		});
		
	});
	
</script>
<script>
	function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table-daily_report");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			td4 = tr[i].getElementsByTagName("td")[4];
			td5 = tr[i].getElementsByTagName("td")[5];
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
								}else if(td4){
								txtValue = td4.textContent || td4.innerText;
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
	}
</script>

<script>
	
	$("#caexcel").click(function(){
		var data_val = "data";
		$.ajax({
			url:"<?php echo admin_url(); ?>invoice_items/export_ItemMaster",
			method:"POST",
			data:{data_val:data_val,},
			beforeSend: function () {
				$('#searchh3').css('display','block');
			},
			complete: function () {
				$('#searchh3').css('display','none');
			},
			success:function(data){
				response = JSON.parse(data);
				window.location.href = response.site_url+response.filename;
			}
		});
	});
	
	
</script>
<script type="text/javascript">
	function printPage(){
		var html_filter_name =    $('.report_for').html();
		var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">Item Master </td>';
		heading_data += '</tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name+'</td>';
		heading_data += '</tr>';
		
		heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
	
	$(document).on("click", ".sortable", function () {
		var table = $("#table-daily_report tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortable").removeClass("asc desc");
		$(".sortable span").remove();
		
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
</body>
</html>
