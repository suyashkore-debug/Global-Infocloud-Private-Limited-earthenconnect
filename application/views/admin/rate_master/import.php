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
            					<li class="breadcrumb-item active text-capitalize"><b>Master</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Rate Master</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<span style="color:red;"><b>State And Party Name Wise Rate Import</b></span>
						<hr/>
						<?php echo $this->import->downloadSampleFormHtml(); ?>
						<?php echo $this->import->maxInputVarsWarningHtml(); ?>
						
						<?php if(!$this->import->isSimulation()) { ?>
							
							<?php echo $this->import->importGuidelinesInfoHtml(); ?>
							<?php //echo $this->import->createSampleTableHtml(); ?>
							
							<?php } else { ?>
							
							<?php echo $this->import->simulationDataInfo(); ?>
							<?php echo $this->import->createSampleTableHtml(true); ?>
							
						<?php } ?>
						<?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'import_form')) ;?>
						<div class="row">
							<div class="col-md-2">
								<?php 
									echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
									
								?>
							</div>
							<div class="col-md-2">
								<?php 
									
									echo render_select('distributor_id',$groups,array('id','name'),'Distributor Type',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
									
								?>
							</div>
							<div class="col-md-2">
								<?php $value = _d(date('Y-m-d'));
									$date_attrs = array();
								?>
								<?php echo render_date_input('effective_date','effective_date',$value,$date_attrs); ?>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-4">
								
								<?php echo form_hidden('items_import','true'); ?>
								
								<?php echo render_input('file_csv','choose_excel_file','','file'); ?>
								<div class="form-group">
									<button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('import'); ?></button>
									<!--<button type="button" class="btn btn-info simulate btn-import-submit"><?php echo _l('simulate_import'); ?></button>-->
								</div>
								
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
		
		<!--Second Section Start-->
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<span style="color:red;"><b>State And Item Wise Rate Import</b></span>
						<hr/>
						<?php echo $this->import->downloadSampleFormHtmlItemWise(); ?>
						<?php echo $this->import->maxInputVarsWarningHtml(); ?>
						
						<?php if(!$this->import->isSimulation()) { ?>
							
							<?php echo $this->import->importGuidelinesInfoHtmlItemWise(); ?>
							<?php //echo $this->import->createSampleTableHtml(); ?>
							
							<?php } else { ?>
							
							<?php echo $this->import->simulationDataInfo(); ?>
							<?php echo $this->import->createSampleTableHtml(true); ?>
							
						<?php } ?>
						<?php echo form_open_multipart(admin_url('rate_master/ImportRateItemWise'),array('id'=>'import_form_itemWise')) ;?>
						<div class="row">
							<div class="col-md-2">
								<?php 
									echo render_select( 'states2',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
								?>
							</div>
							<div class="col-md-2">
								<?php 
									echo render_select( 'ItemID',$ItemList,array( 'item_code',array( 'description')), 'Item Name',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
								?>
							</div>
							<!--<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Rate">
								<label for="Rate" class="control-label">Assigned Rate</label>
								<input type="text" id="Rate" name="Rate" class="form-control" value="">
								</div>
							</div>-->
							
							<div class="col-md-2">
								<?php $value = _d(date('Y-m-d'));
									$date_attrs = array();
								?>
								<?php echo render_date_input('effective_date2','effective_date',$value,$date_attrs); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								
								<?php echo form_hidden('items_import','true'); ?>
								
								<?php echo render_input('file_csv2','choose_excel_file','','file'); ?>
								<div class="form-group">
									<button type="submit" class="btn btn-info"><?php echo _l('import'); ?></button>
									<!--<button type="button" class="btn btn-info simulate btn-import-submit"><?php echo _l('simulate_import'); ?></button>-->
								</div>
								
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
		<!--Third Section Start-->
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<span style="color:red;"><b>Dist Type Wise Rate Import</b></span>
						<hr/>
						
						<div class="row">
							<div class="col-md-2">
								<?php 
									echo render_select( 'states3',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
								?>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="DistType">
									<small class="req text-danger">* </small>
									<label for="DistType" class="form-label">Distributor Type</label>
									<select name="DistType[]" id="DistType" class="selectpicker form-control " multiple  data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Party">
									<small class="req text-danger">* </small>
									<label for="Party" class="form-label">Effective Parties</label>
									<select name="Party" id="Party" class="selectpicker form-control "  data-none-selected-text="None selected" data-live-search="true">
										<option value="">None selected</option>
										
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<?php 
									echo render_select( 'Items',$FGItemList,array( 'item_code',array( 'description')), 'Item Name',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
								?>
							</div>
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Rate">
									<small class="req text-danger">* </small>
									<label for="Rate" class="control-label">Basic Rate</label>
									<input type="text" id="BasicRate" name="BasicRate" class="form-control" value="">
								</div>
							</div>
							<div class="col-md-2">
								<?php $value = _d(date('Y-m-d'));
									$date_attrs = array();
								?>
								<?php echo render_date_input('effective_date3','effective_date',$value,$date_attrs); ?>
							</div>
							<div class="col-md-2">
									<small class="req text-danger">* </small>
								<label for="dis_per" class="control-label">Discount %</label>
								<input type="text" id="dis_per" name="dis_per" value="0.00" class="form-control" value="">
								
							</div>
							<div class="col-md-4">
								<br>
								<div class="form-group">
									<button type="button" id="customerwiseimport" class="btn btn-info"><i class="fa fa-spinner fa-spin spinner" style="display:none;"></i> <?php echo _l('import'); ?></button>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/jquery-validation/additional-methods.min.js'); ?>"></script>

<script type="text/javascript">
	$('#Rate,#BasicRate,#dis_per,#dis_per2').on('keypress',function (event) {
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
	$(function(){
		appValidateForm($('#import_form'),{
			file_csv:{required:true,extension: "csv"},
			states:{required:true},
			distributor_id:{required:true}
		});
		
		appValidateForm($('#import_form_itemWise'),{
			file_csv2:{required:true,extension: "csv"},
			states2:{required:true},
			ItemID:{required:true},
			effective_date2:{required:true}
		});
	});
	
	//==================== Check Distributor Type state ============================
    $('#distributor_id').on('change', function() {
        var DistType = $(this).val();
        var states = $("#states").val();
        if(states == ""){
            alert("Please selecte state first..");
            $('#distributor_id').val('');
            $('.selectpicker').selectpicker('refresh');
			}else{
            var url = "<?php echo base_url(); ?>admin/clients/GetDistTypeState";
            jQuery.ajax({
                type: 'POST',
                url:url,
                data: {DistType: DistType},
                dataType:'json', 
                success: function(data) {
                    if(data){
                        if(data.state == states){
							}else{
                            $('#distributor_id').val('');
                            $('.selectpicker').selectpicker('refresh');
                            alert("Selected state and  Distributor Type state must be same");
						}   
					}
				}
			});
		}
	})
    $('#states').on('change', function() {
        $('#distributor_id').val('');
        $('.selectpicker').selectpicker('refresh');
	})
	
	$('#states3').on('change', function() {
		var StateID = $(this).val();
		var url = "<?php echo base_url(); ?>admin/rate_master/GetStateWiseDist";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {StateID: StateID},
            dataType:'json',
            success: function(data) {
                $("#DistType").find('option').remove();
                $("#DistType").selectpicker("refresh");
                $("#DistType").append(new Option('None Selected', ''));
                for (var i = 0; i < data.length; i++) {
                    $("#DistType").append(new Option(data[i].name, data[i].id));
				}
                $('.selectpicker').selectpicker('refresh');
			}
		});
	});
	
	$('#DistType').on('change', function() {
		var DistType = $(this).val();
		var url = "<?php echo base_url(); ?>admin/rate_master/GetDistWiseParty";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {DistType: DistType},
            dataType:'json',
            success: function(data) {
                $("#Party").find('option').remove();
                $("#Party").selectpicker("refresh");
                // $("#Party").append(new Option('None Selected', ''));
                for (var i = 0; i < data.length; i++) {
                    $("#Party").append(new Option(data[i].company, data[i].AccountID));
				}
                $('.selectpicker').selectpicker('refresh');
			}
		});
	});
	
	$('#customerwiseimport').on('click',function(){ 
		StateID = $('#states3').val();
		DistType = $('#DistType').val();
		Items = $('#Items').val();
		BasicRate = $('#BasicRate').val();
		dis_per = $('#dis_per').val();
		effective_date3 = $('#effective_date3').val();
		
        if(StateID == ''){
            alert('please Select State');
            $('#states3').focus();
			}else if($.trim(DistType) == ''){
            alert('please Select Dist. Type');
            $('#DistType').focus();
			}else if(Items == ''){
            alert('please Select Item Name');
            $('#subgroup').focus();
			}else if(effective_date3 == ''){
			alert('please select Effective Date');
			$('#effective_date3').focus();
			}else if(dis_per == ''){
			alert('please enter discount percentage');
			$('#dis_per').focus();
			}else {
            $.ajax({
                url:"<?php echo admin_url(); ?>rate_master/SaveRateByParty",
                dataType:"JSON",
                method:"POST",
                data:{StateID:StateID,DistType:DistType,Items:Items,BasicRate:BasicRate,effective_date3:effective_date3,dis_per:dis_per
				},
                beforeSend: function () {
					$('.spinner').show();
				},
                complete: function () {
					$('.spinner').hide();
				},
                success:function(data){
					if(data){
					    $('#HiddenAccountID').val(data);
						alert_float('success', 'Rate Inserted Successfully...');
						location.reload();
						}else{
						alert_float('warning', 'Something went wrong...');
					}
				}
			}); 
		}
		
	});
	
	
</script>
</body>
</html>
