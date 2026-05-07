<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Production</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Production Order</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="clearfix"></div>
						<?php echo form_open('admin/production/create_order',array('id'=>'production_form')); ?>
						<div class="row">
							
							<div class="col-md-2">
								<?php
									$selected_company = $this->session->userdata('root_company');
									if($selected_company == 1){
										
										$new_production_orderNumbar = get_option('next_production_return_number_for_cspl');
										}elseif($selected_company == 2){
										$new_production_orderNumbar = get_option('next_production_return_number_for_cff');
										}elseif($selected_company == 3){
										$new_production_orderNumbar = get_option('next_production_return_number_for_cbu');
										}elseif($selected_company == 4){
										$new_production_orderNumbar = get_option('next_production_return_number_for_cbupl');
									}
									
									$format = get_option('invoice_number_format');
									
									$prefix = "POI";
									if ($format == 1) {
										$__number = $new_production_orderNumbar;
										$prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>';
										} else if($format == 2) {
										
										$__number = $new_production_orderNumbar;
										$prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>/';
										
										} else if($format == 3) {
										
										$__number = $new_production_orderNumbar;
										
										} else if($format == 4) {
										
										$yyyy = date('Y');
										$mm = date('m');
										$__number = $new_production_orderNumbar;						
									}
									
									$_receipts_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
								?> 
								<div class="form-group">
									<label for="number"> 
										Production Order Number
										
										<!-- <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('invoice_number_not_applied_on_draft') ?>" data-placement="top"></i>-->
									</label>
									<div class="input-group">
										<span class="input-group-addon">
											<?php
												echo $prefix;
											?>
										</span>
										<input type="text" name="pro_orderid" id="pro_orderid" class="form-control receiptsid" value="<?php echo ($_is_draft) ? 'DRAFT' : $_receipts_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($_is_draft) ? 'disabled' : '' ?>>
									</div>
								</div>
								
							</div>
							
							<!--  <div class="col-md-4">
								<?php //echo render_input('lot_no','Lot Number'); ?>
							</div> -->
							
							<div class="col-md-2">
								<?php
									$fy = $this->session->userdata('finacial_year');
									$fy_new  = $fy + 1;
									$lastdate_date = '20'.$fy_new.'-03-31';
									$firstdate_date = '20'.$fy_new.'-04-01';
									$curr_date = date('Y-m-d');
									$curr_date_new    = new DateTime($curr_date);
									$last_date_yr = new DateTime($lastdate_date);
									if($last_date_yr < $curr_date_new){
										$to_date = '31/03/20'.$fy_new;
										$from_date = '01/03/20'.$fy_new;
										}else{
										$from_date = "01/".date('m')."/".date('Y');
										$to_date = date('d/m/Y');
									}
									/*$attr = array(
										'disabled'=>'disabled'    
									);*/
									$attr = array();
								?>    
								
								<?php echo render_date_input('start_date1','Date',$to_date,$attr); ?>
								<input type="hidden" name="start_date" id="start_date" value="<?php echo $to_date;?>">
								<input type="hidden" name="PRDID" id="PRDID" value="">
								<input type="hidden" name="PlantID" id="PlantID" value="<?php echo $selected_company; ?>">
								<?php $PRDLastDateNew = str_replace("-","/",$PRDLastDate); ?>
								<input type="hidden" name="LastPRDDate" id="LastPRDDate" value="<?php echo $PRDLastDateNew; ?>">
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="GodownID">
									<small class="req text-danger">* </small>
									<label for="GodownID" class="form-label">Godown Name</label> 
									<select name="GodownID" id="GodownID" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
										<!--<option value="">Non Selected</option>-->
										<?php
											foreach ($GodownData as $key => $value) {
												if($value['AccountID'] == "PU"){
												?>
												<option value="<?php echo $value['AccountID'];?>"><?php echo $value['AccountName'];?></option>
												<?php
												}
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<?php echo render_input('recipeID','Production For'); ?>
								<div class="" id="serchh" style="display:none;">Serching</div>
							</div>
							
							<div class="col-md-1">
								<div class="form-group" app-field-wrapper="batch_qty">
									<small class="req text-danger">* </small>
									<label for="batch_qty" class="control-label">Batch Qty</label>
									<input type="text" class="form-control" name="batch_qty" id="batch_qty" value="" >
								</div>
								
								<?php //echo render_input('batch_qty','Batch Qty'); ?>
							</div>	
							<div class="col-md-3">
								<?php echo render_input('recipeName','Production Name','','',$attr); ?>
								
								</div>
							
						</div>	
						
						<div class="row" style="margin-top:10px;">
							
							
							<div class="col-md-2">
								<?php
									$attr2 = array(
									'readonly'=>'readonly'    
									);
								?>
								<?php echo render_input('qty_product','Finish Good Qty','','',$attr2); ?>
								<input type="hidden" name="finishgood_qty" id="finishgood_qty" value="">
								<input type="hidden" name="defualt_finishgood_qty" id="defualt_finishgood_qty" value="">
								<input type="hidden" name="unit_new" id="unit_new" value="">
							</div>
							
							<div class="col-md-2">
								<?php echo render_input('unit_f_g','Measured In','','',$attr); ?>     
							</div>	
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="env_temp">
									<label for="env_temp" class="control-label">Env. Temp.</label>
									<input type="text" name="env_temp" id="env_temp" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="env_humidity">
									<label for="env_humidity" class="control-label">Env. Humidity</label>
									<input type="text" name="env_humidity" id="env_humidity" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="water_temp">
									<label for="water_temp" class="control-label">Water Temp</label>
									<input type="text" name="water_temp" id="water_temp" class="form-control" value="" >
								</div>
							</div>
							<div class="col-md-2">
								
								<?php echo render_input('req_hour','Required Time - HH'); ?>     
								<!-- <input class="form-control"  type="text" name="req_hour" id="req_hour" placeholder="HH">-->
							</div>
							<div class="col-md-2">
								<!--<label for="req_min" class="control-label"></label> -->
								<?php echo render_input('req_min','MM'); ?> 
							</div>
							<div class="col-md-3" id="a">
								<div class="form-group" app-field-wrapper="operator_name">
									<label for="operator_name" class="control-label">Production Manager Name</label>
									<select name="operator_name" id="operator_name" data-live-search="true" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
										<option value=""></option>
										<?php
											foreach ($manager as $key => $value) {
												# code...
											?>
											<option value="<?php echo $value["AccountID"];?>"><?php echo $value['firstname']." ".$value['lastname']?></option>
											<?php
											}
										?>
									</select>
								</div>
								<?php //echo render_select('operator_name',$manager,array('AccountID','company'),'Production Manager Name'); ?> 
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="shift">
									<label for="shift" class="control-label">Shift Type</label>
									<select name="shift" id="shift" data-live-search="true" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
										<option value=""></option>
										<option value="Day">Day</option>
										<option value="Night">Night</option>
										
									</select>
								</div>
							</div>
							
						</div>
						
						<div class="row" style="margin-top:10px;">	
							
							<div class="col-md-4" id="b">
								<?php echo render_select('con_name',$contractor,array('AccountID','company'),'Contractor Name'); ?>
							</div>
							
							<div class="col-md-4">
								<?php //echo render_textarea('comments','Comments'); ?>
								<?php echo render_input('comments','Comments'); ?> 
							</div>
							
							
						</div>
						
						
						
						<div id="rawMaterial"></div>
						
						<div class="row">
							<div class="col-md-1" >
								<?php
									if (has_permission_new('production', '', 'create')) {
									?>    
									<button type="submit" id="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
								<?php } ?>
							</div>
							<!--<div class="col-md-1" >
								<?php
									if (has_permission_new('production', '', 'view')) {
									?> 
									<a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>
								<?php } ?>
							</div>-->
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="transfer-modal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Production List</h4>
			</div>
			<div class="modal-body">
				
				<div class="row">
					<?php $current_date = date('d/m/Y'); 
						$from_date = '01/'.date('m').'/'.date('Y');
					?>
					<div class="col-md-3">
						<?php
							echo render_date_input('from_date','From',$from_date);
						?>
					</div>
					<div class="col-md-3">
						<?php
							echo render_date_input('to_date','To',$current_date);
						?>
					</div>
					<div class="col-md-2">
						<div class="form-group" app-field-wrapper="status_list">
							<label for="status_list" class="control-label"><?php echo _l('Order Status'); ?></label>
							<select name="status_list" id="status_list" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
								<option value="pending">Pending</option>
								<option value="In-Progress">In-Progress</option>
								<option value="Completed">Completed</option>
							</select>
						</div>
					</div>
					<div class="col-md-1">
						<br>
						<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
					</div>
					<div class="col-md-3">
						<br>
						<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
					</div>
					<div class="col-md-12">
						
						<div class="table_production_report">
							
							<table class="tree table table-striped table-bordered table_production_report" id="table_production_report" width="100%">
								
								<thead>
									<tr style="display:none;">
										<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
									</tr>
									<tr>
										<th style="text-align:left;">ProductionID</th>
										<th style="text-align:left;">PRDDate</th>
										<th style="text-align:left;">RecipeName</th>
										<th style="text-align:left;">BatchQty</th>
										<th style="text-align:left;">FGQty</th>
										<th style="text-align:left;">ReqTM</th>
										<th style="text-align:left;">PRDTM</th>
										<th style="text-align:left;">Man/Con Name</th>
										<th style="text-align:left;">Status</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>   
						</div>
						<span id="searchh2" style="display:none;">
							Loading.....
						</span>
						
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
</div>
<style>
	#recipeID {
    text-transform: uppercase;
	}
    .table_production_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table_production_report thead th { position: sticky; top: 0; z-index: 1; }
	.table_production_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table_production_report table  { border-collapse: collapse; width: 100%; }
	.table_production_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
	.table_production_report th     { background: #50607b;color: #fff !important; }
	
	
	#table_production_report tr:hover {
    background-color: #ccc;
	}
	
	#table_production_report td:hover {
    cursor: pointer;
	}
</style>
<?php init_tail(); ?>

<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		function load_data(from_date,to_date,status_list)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>production/load_data_for_production",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date,status_list:status_list},
				beforeSend: function () {
					
					$('#searchh2').css('display','block');
					$('.table_production_report tbody').css('display','none');
					
				},
				complete: function () {
					
					$('.table_production_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					var html = '';
					
					for(var count = 0; count < data.length; count++)
					{
						
						var url = "'<?php echo admin_url() ?>production/production_order/"+data[count].pro_order_id+"'";
						html += '<tr onclick="location.href='+url+'">';
						html += '<td style="text-align:center;">'+data[count].pro_order_id+'</td>';
						
						var date = data[count].TransDate.substring(0, 10);
						var date_new = date.split("-").reverse().join("/");
						
						html += '<td  style="text-align:center;">'+date_new+'</td>';
						html += '<td style="text-align:center;">'+data[count].recipeID+'</td>';
						html += '<td style="text-align:center;">'+data[count].batch_qty+'</td>';
						html += '<td style="text-align:center;">'+data[count].Finish_good_qty_new+'</td>';
						html += '<td style="text-align:center;">'+data[count].required_time+'</td>';
						html += '<td style="text-align:center;">'+data[count].production_time+'</td>';
						if(data[count].contractor_name == null){
							if(data[count].lastname == null){
								var AccoutName = data[count].firstname;
								}else{
								var AccoutName = data[count].firstname + data[count].lastname;
							}
							
							}else{
							var AccoutName = data[count].conName;
						}
						html += '<td  style="text-align:left;">'+ AccoutName +'</td>';
						html += '<td  style="text-align:center;">'+data[count].production_status+'</td>';
						html += '</tr>';
					}
					$('.table_production_report tbody').html(html);
					
				}
			});
		}
		
		$('#search_data').on('click',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var status_list = $("#status_list").val();
			
			load_data(from_date,to_date,status_list);
			
		});
		
	});
</script>
<script>
    $('.add-new-transfer').on('click', function(){
		$('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
		$('#transfer-modal').modal('show');
	});
</script>

<script type='text/javascript'>
	$(document).ready(function(){
		$("#GodownID").focus();	
		// recipe name 
		$( "#recipeID" ).autocomplete({
			source: function( request, response ) {
				// Fetch data
				$.ajax({
					url: "<?=base_url()?>admin/production/itemlist_recipe",
					type: 'post',
					dataType: "json",
					data: {
						search: request.term
					},
					beforeSend: function () {
						
						$('#serchh').css('display','block');
						//$("#ui-id-2").prepend("<li value='' class='ui-menu-item'>Serching</li>");
					},
					complete: function () {
						//$("#item_code").val("");
						$('#serchh').css('display','none');
					},
					success: function( data ) {
						
						response( data );
					}
					
				});
			},
			select: function (event, ui) {
                
                $('#recipeID').val(ui.item.value);
                $('#recipeName').val(ui.item.label);
				$('#qty_product').val(ui.item.quantity);
				$('#finishgood_qty').val(ui.item.quantity);
				$('#defualt_finishgood_qty').val(ui.item.quantity);
				$('#env_temp').val(ui.item.env_temp);
				$('#env_humidity').val(ui.item.env_humidity);
				$('#water_temp').val(ui.item.water_temp);
				$('#unit_f_g').val(ui.item.units); // save selected id to input
				$('#unit_new').val(ui.item.units); // save selected id to input
				//$('#batch_qty').focus();
				
			}
		});  
	});
	
	$('#recipeID').on('focus',function(){
        
        $('#recipeID').val('');
        $('#recipeName').val('');
        $('#qty_product').val('');
        $('#batch_qty').val('');
		$('#qty_product').val('');
		$('#finishgood_qty').val('');
		$('#defualt_finishgood_qty').val('');
		$('#unit_f_g').val('');
		$('#unit_new').val('');
		$('#rawMaterial').html('');
		
		$('#env_temp').val('');
		$('#env_humidity').val('');
		$('#water_temp').val('');
        
	});
    
    $('#GodownID').change(function() {
	    $('#recipeID').val('');
        $('#recipeName').val('');
        $('#qty_product').val('');
        $('#batch_qty').val('');
		$('#qty_product').val('');
		$('#finishgood_qty').val('');
		$('#defualt_finishgood_qty').val('');
		$('#unit_f_g').val('');
		$('#unit_new').val('');
		$('#rawMaterial').html('');
		
		$('#env_temp').val('');
		$('#env_humidity').val('');
		$('#water_temp').val('');
	});
	
	$('#operator_name').change(function() {
		
		var val = $(this).val();
		//alert(val);
		if(val == ""){
			$('#b *').prop('disabled', false);
			}else{
			$('#b *').prop('disabled', true);
		}
	});
	
	
	$('#con_name').change(function() {
		
		var val = $(this).val();
		//alert(val);
		if(val == ""){
			$('#a *').prop('disabled', false);
			}else{
			$('#a *').prop('disabled', true);
		}
		
	});
	
</script>
<script>
    function myFunction2() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		filter = input.value.toUpperCase();
		table = document.getElementById("table_production_report");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[2];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					} else {
					tr[i].style.display = "none";
				}
			}       
		}
	}
</script>
<script type="text/javascript">
	
	function showData() {
		var recipeID =$('#recipeID').val();
		var GodownID =$('#GodownID').val();
		var batch = $('#batch_qty').val();
		
		if(recipeID == ""){
			$("#rawMaterial").html();
			}else{
			if(GodownID == ""){
				$("#rawMaterial").html();
				}else{
				$.ajax({
					type:'POST',
					url: "<?=base_url()?>admin/production/itemlist_name",
					data:{'recipeID':recipeID,'GodownID':GodownID, 'batchQuantity' : batch || '1'},
					
					success:function(data){
						
						$("#rawMaterial").html(data);
					}  
				}); 
			}
			
		}
        
	}
	
	//get name
	$(document).on("blur","#recipeID",function(){
		var recipeID =$('#recipeID').val();
		var GodownID =$('#GodownID').val();
		if(recipeID == ""){
			//$("#rawMaterial").html();
			}else{
			if(GodownID  == ""){
				alert('Please select Main GodownID');
				$('#recipeID').val('');
				$('#GodownID').focus();
				}else{
				$.ajax({
					url:"<?php echo admin_url(); ?>production/get_recipe_details",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{recipeID:recipeID,GodownID:GodownID},
					
					success:function(data){
						if(empty(data)){
							alert('Recipe not found...');
							$('#recipeID').val('');
							$('#recipeName').val('');
							$('#qty_product').val("");
							$('#finishgood_qty').val("");
							$('#defualt_finishgood_qty').val("");
							$('#unit_f_g').val(''); // save selected id to input
							$('#unit_new').val('');
							}else{
							$('#recipeID').val(data.item_code);
							$('#recipeName').val(data.item_description);
							$('#qty_product').val(data.qty);
							$('#finishgood_qty').val(data.qty);
							$('#defualt_finishgood_qty').val(data.qty);
							$('#unit_f_g').val(data.unit); // save selected id to input
							$('#unit_new').val(data.unit);
							showData();
						}
						
					}
				});
			}
		}
		
	});
	
	
	$(document).on("blur","#batch_qty",function(){
		var batch = $(this).val();
		var recipeID =$('#recipeID').val();
		if(batch < 1 ){
			alert('please select atleast 1 qty..');
			$('#batch_qty').val('1');
			$.ajax({
				url:"<?php echo admin_url(); ?>production/get_recipe_details",
				dataType:"JSON",
				method:"POST",
				cache: false,
				data:{recipeID:recipeID},
				success:function(data){
					$('#qty_product').val(data.qty);
					$('#finishgood_qty').val(data.qty);
					showData();
				}
			});
			}else{
			var defualt_f_g_qty = $('#defualt_finishgood_qty').val();
			var final_f_g_qty = parseFloat(defualt_f_g_qty) * parseFloat(batch);
			$('#qty_product').val(final_f_g_qty);
			$('#finishgood_qty').val(final_f_g_qty);
			showData();
		}
		
		return ;
	});
	
	
	
	$(document).ready(function() {
		$("#production_form").validate({
			rules: {
				recipeID: "required",
				batch_qty: "required",
				req_hour: "required",
				req_min: "required",
				GodownID: "required",
				start_date: {
					remote: {
						url: site_url + "admin/misc/CheckPRDDate",
						type: 'post',
						data: {
							Prd_date: function() {
								return $('input[name="start_date"]').val();
							},
							PRDID: function() {
								return $('input[name="PRDID"]').val();
							},
							PlantID: function() {
								return $('input[name="PlantID"]').val();
							}
						}
					}
				},
			},
			messages: {
				recipeID: "Please Select Recipe Name",
				batch_qty: "Please Enter Batch Quantity",
				req_hour: "Please Enter Require Hours",
				req_min: "Please Enter Require Minute"
			}
		})
		
		$('#submit').click(function() {
			$("#production_form").valid();
		});
	});
	
</script> 

<script type="text/javascript">
	$('#batch_qty,#req_hour,#req_min').on('keypress',function (event) {
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

<script>
    $(document).ready(function(){
		
		var FY = "<?php echo $this->session->userdata('finacial_year')?>";
		var PlantID = "<?php echo $this->session->userdata('root_company')?>";
		var LastPRDDate = $('#LastPRDDate').val();
		var curY = new Date().getFullYear().toString().substr(-2);
		var LastDateY = parseInt(FY) + parseInt(1);
		var YDiff = parseInt(curY) -  parseInt(FY);
		var Y = "20"+LastDateY;
		if(parseInt(curY) == parseInt(FY)){
			if(parseInt(PlantID) == 1){
				var startdate = new Date(LastPRDDate);
				}else{
				var startdate = new Date('Y/m/d');
			}
			var EndDate = new Date(Y+'/03/31');
			}else if(parseInt(LastDateY ) == parseInt(curY)){
			if(parseInt(PlantID) == 1){
				var startdate = new Date(LastPRDDate);
				}else{
				var startdate = new Date('Y/m/d');
			}
			var EndDate = new Date(Y+'/03/31');
		}
		
		$('#start_date').datetimepicker({
			format: 'd/m/Y',
			minDate: startdate,
			maxDate: EndDate,
			timepicker: false
		});
	});
</script> 
