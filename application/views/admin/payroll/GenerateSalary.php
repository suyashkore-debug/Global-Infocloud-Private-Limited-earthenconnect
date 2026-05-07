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
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Staff Payroll</h4>
                        <hr>
                    </div>
                    <div class="col-md-12">
								<!-- filter -->
								<div class="row filter_by">

									<div class="col-md-2">
										<?php echo render_input('month_employees','month',date('Y-m'), 'month'); ?>   
									</div>

									<div class="col-md-3 leads-filter-column pull-left">
										<?php echo render_select('department_employees',$departments,array('departmentid', 'name'),'department',''); ?>
									</div>

									<div class="col-md-3 leads-filter-column pull-left">
										<div class="form-group">
											<label for="role_employees" class="control-label"><?php echo _l('role'); ?></label>
											<select name="role_employees[]" class="form-control selectpicker" multiple="true" id="role_employees" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true"> 
												<?php foreach ($roles as $key => $role) { ?>
													<option value="<?php echo html_entity_decode($role['roleid']); ?>" ><?php  echo html_entity_decode($role['name']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-3 leads-filter-column pull-left">

										<div class="form-group">
											<label for="staff_employees" class="control-label"><?php echo _l('staff'); ?></label>
											<select name="staff_employees[]" class="form-control selectpicker" multiple="true" id="staff_employees" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true"> 
												<?php foreach ($staffs as $key => $staff) { ?>

													<option value="<?php echo html_entity_decode($staff['staffid']); ?>" ><?php  echo html_entity_decode($staff['firstname'].' '.$staff['lastname']); ?></option>
												<?php } ?>
											</select>
										</div>

									</div>
									
									<div class="col-md-2">
									    <button type="button" class="btn btn-info search" id="search_data"  style="margin-right: 25px;">Search</button>
									</div>

								</div>
								<!-- filter -->
							</div>
                    <div class="col-md-12">
                <?php
			            echo form_open($this->uri->uri_string(),array('id'=>'salary_form','class'=>'_transaction_form invoice-form'));
			    ?>
                        
                        <div class="table-daily_report tableFixHead2 load_data">
                        <!--<input type="hidden" name="error_log" id="error_log" value="">-->
                        
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

    $('.timesheets_filter').on('click', function() {
        var data = {};
        data.month = $("#month_timesheets").val();
        data.staff = $('select[name="staff_timesheets[]"]').val();
        data.department = $('#department_timesheets').val();
        data.job_position = $('#job_position_timesheets').val();
        var url = admin_url + 'timesheets/reload_timesheets_byfilter';
        $.ajax({
           url: url,
           type: 'post',
           data: data,
           beforeSend: function(){
            // Show image container
            $("#loader_filter").show();
            dataObject = [];
            dataCol = [];
            data_lack = [];
            hot.updateSettings({
              data: dataObject,
              columns: dataCol,
            })
           },
           success: function(response){
            response = JSON.parse(response);
        dataObject = response.arr;
        dataCol = response.set_col_tk;
        dataHeader = response.day_by_month_tk;
        data_lack = response.data_lack;
        hot.updateSettings({
          data: dataObject,
          columns: dataCol,
          colHeaders: dataHeader,
        })
        $('input[name="month"]').val(response.month);
        if(response.check_latch_timesheet){
          $('#btn_unlatch').removeClass('hide');
          $('#btn_latch').addClass('hide');
          $('.edit_timesheets').addClass('hide');
          $('.exit_edit_timesheets').addClass('hide');
          $('.save_time_sheet').addClass('hide');
        }else{
          $('#btn_latch').removeClass('hide');
          $('#btn_unlatch').addClass('hide');
          $('.edit_timesheets').removeClass('hide');
          $('.exit_edit_timesheets').addClass('hide');
          $('.save_time_sheet').addClass('hide');
        }
           },
           complete:function(data){
            // Hide image container
            $("#loader_filter").hide();
           }
    });

    });
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
    function calculateSalary(Net_Payable,id,val){
        var CompType = '';
        let id_array = id.split('_');
        var staffName = id_array[0]+'_'+id_array[1];
        var AccountID = id_array[1];
        var Comparray = <?php echo json_encode($SalaryHead); ?>;
        var total_earning = 0;
        var total_deduction = 0;
        var ESIC_Total = 0;
        for (var i = 0; i < Comparray.length; i++) {
            if(Comparray[i]['code'] != "NET" && Comparray[i]['code'] != "ESIC"){
                var CurrentHeadID = staffName+'_'+Comparray[i]['code'];
                if(CurrentHeadID == id){
                    CompType = Comparray[i]['type'];
                }
                var value = $("#"+CurrentHeadID).val();
                if(value !=""){
                    if(Comparray[i]['ESIC_Calculated'] == "Y"){
                        var ESIC = parseFloat(value) * (parseFloat(0.75) / 100);
                        ESIC_Total = parseFloat(ESIC_Total) + parseFloat(ESIC);
                    }
                    if(Comparray[i]['type'] == "1"){
                        total_earning = parseFloat(total_earning) + parseFloat(value);
                    }else{
                        total_deduction = parseFloat(total_deduction) + parseFloat(value);
                    }
                }
            }
        }
        var ESICID = 'Amt_'+AccountID+'_ESIC';
        $("#"+ESICID).val(parseFloat(ESIC_Total).toFixed(2));
        total_deduction = parseFloat(total_deduction) + parseFloat(ESIC_Total);
        var Cal = parseFloat(total_earning) - parseFloat(total_deduction)
        if(parseFloat(Net_Payable) >= parseFloat(Cal)){
            $("#total_earning_html_"+AccountID).html(parseFloat(total_earning).toFixed(2));
            $("#total_earning_"+AccountID).val(parseFloat(total_earning).toFixed(2));
            $("#total_deduction_html_"+AccountID).html(parseFloat(total_deduction).toFixed(2));
            $("#total_deduction_"+AccountID).val(parseFloat(total_deduction).toFixed(2));
            
            var yearly_ctc = parseFloat(total_earning) * 12;
            $("#yearly_ctc_html_"+AccountID).html(parseFloat(yearly_ctc).toFixed(2));
            $("#yearly_ctc_"+AccountID).val(parseFloat(yearly_ctc).toFixed(2));
        }else{
            if(CompType == "1"){
                total_earning = parseFloat(total_earning) - parseFloat(val)
            } else{
                total_deduction = parseFloat(total_deduction) - parseFloat(val)
            }
            $("#total_earning_html_"+AccountID).html(parseFloat(total_earning).toFixed(2));
            $("#total_earning_"+AccountID).val(parseFloat(total_earning).toFixed(2));
            $("#total_deduction_html_"+AccountID).html(parseFloat(total_deduction).toFixed(2));
            $("#total_deduction_"+AccountID).val(parseFloat(total_deduction).toFixed(2));
            
            var yearly_ctc = parseFloat(total_earning) * 12;
            $("#yearly_ctc_html_"+AccountID).html(parseFloat(yearly_ctc).toFixed(2));
            $("#yearly_ctc_"+AccountID).val(parseFloat(yearly_ctc).toFixed(2));
            alert('calculated value is exceed to Net Payable salary');
            $("#"+id).val(0);
        }
        if(parseFloat(Net_Payable) != parseFloat(Cal)){
            $("#total_earning_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
            $("#total_deduction_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
            $("#yearly_ctc_td_"+AccountID).css({'background-color':'#f71616','color':'#fff','font-size':'14px'});
            var log = $('#error_log').val();
            let AccountIDs = log.split(',');
            var isInArray = AccountIDs.includes(AccountID);
            if(isInArray == false){
                AccountIDs.push(AccountID);
            }
            let text = AccountIDs.toString();
            $('#error_log').val(text);
        }else{
            $("#total_earning_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
            $("#total_deduction_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
            $("#yearly_ctc_td_"+AccountID).css({'background-color':'#12bc23','color':'#fff','font-size':'14px'});
            var log = $('#error_log').val();
            let AccountIDs = log.split(',');
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
    }
    function SubmitFormData() {
        var count = $('#error_log').val();
        //alert(count);
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
</script>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
         $('#search_data').on('click',function(){
           $.ajax({
          url:"<?php echo admin_url(); ?>Payroll/get_Staff_payroll",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{},
          beforeSend: function () {
                   
            $('#searchh').css('display','block');
            $('.load_data').css('display','none');
            
         },
          complete: function () {
                                
            $('.load_data').css('display','');
            $('#searchh').css('display','none');
         },
          success:function(data){
              
                $('.load_data').html(data);
           
            
          }
        });
	    
 });
});
</script>