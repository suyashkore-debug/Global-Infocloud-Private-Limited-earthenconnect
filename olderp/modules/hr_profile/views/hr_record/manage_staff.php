<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php  init_head(); ?>
<style>
    .table-table_staff tbody{
  display: block;
  max-height: 450px;
  overflow-y: scroll;
  width: calc(100% - -8.9em);
}
.table-table_staff thead, .table-table_staff tbody tr{
  display: table;
  table-layout: fixed;
  width: 100%;
  
}
.table-table_staff thead{
  width: calc(100% - -5.9em);
}
.table-table_staff thead{
  position: relative;
}
.table-table_staff thead th:last-child:after{
  content: ' ';
  position: absolute;
  background-color: #337ab7;
  width: 1.3em;
  height: 38px;
  right: -1.3em;
  top: 0;
  border-bottom: 2px solid #ddd;
}

/*.staff_name{*/
/*width:21%;*/
/*}*/
.table-table_staff th td{padding: 32px -20px 12px 14px;
}



</style>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
.table-daily_report tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<!--<div class="_buttons">
							<?php if (is_admin() || has_permission('hrm_hr_records','','create')) { ?>

								<a href="<?php echo admin_url('hr_profile/new_member'); ?>" class="btn mright5 btn-info pull-left display-block hidden-xs"><?php echo _l('new_staff'); ?></a>
								<a href="<?php echo admin_url('hr_profile/importxlsx'); ?>" class="btn mright5 btn-info pull-left  hidden">
									<?php echo _l('hr_import_xlsx_hr_profile'); ?>
								</a>


							<?php } ?>
							<a href="#" onclick="view_staff_chart(); return false;"  class="mright5 btn btn-default pull-left display-block">
								<?php echo _l('hr_view_staff_chart'); ?>
							</a>
						</div>
						<br>
						<div class="row"></div>
						<br>-->
						<div class="row">

							<!-- fillter by teammanage -->
							<!--<div class="col-md-2 pull-right hide">
								<input type="text" id="staff_dep_tree" name="staff_dep_tree" class="selectpicker" placeholder="<?php echo _l('hr_team_manage'); ?>" autocomplete="off">
								<input type="hidden" name="staff_tree" id="staff_tree"/>
							</div>-->

							<!--<div class="col-md-3 pull-right">
								<select name="status_work[]" class="selectpicker" multiple="true" id="status_work" data-width="100%" data-none-selected-text="<?php echo _l('hr_status_label'); ?>"> 
									<option value="<?php echo 'working' ?>"><?php echo _l('hr_working'); ?></option>
									<option value="<?php echo 'maternity_leave'; ?>"><?php echo _l('hr_maternity_leave'); ?></option>
									<option value="<?php echo 'inactivity'; ?>">Not Working</option>
								</select>
							</div>-->
							<div class="col-md-2 pull-right">
								<select name="staff_type[]" class="selectpicker" multiple="true" id="staff_type" data-width="100%" data-actions-box="true" data-live-search="true" data-none-selected-text="<?php echo 'Staff type'; ?>"> 
									<?php 
									foreach ($staff_type as $value) { ?>
										<option value="<?php echo html_entity_decode($value['roleid']); ?>"><?php echo html_entity_decode($value['name']) ?></option>
									<?php }
									?>              
								</select>
							</div>
							<div class="col-md-2 pull-right">
								<select name="status" class="selectpicker" id="status" data-width="100%" data-none-selected-text="<?php echo _l('hr_status_label'); ?>"> 
									<option value="all">All</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
							</div>
							<div class="col-md-2 pull-right">
								<select name="staff_role[]" class="selectpicker" multiple="true" id="staff_role" data-width="100%" data-actions-box="true" data-live-search="true" data-none-selected-text="<?php echo _l('hr_hr_job_position'); ?>"> 
									<?php 
									foreach ($staff_role as $value) { ?>
										<option value="<?php echo html_entity_decode($value['position_id']); ?>"><?php echo html_entity_decode($value['position_name']) ?></option>
									<?php }
									?>              
								</select>
							</div>
							<div class="col-md-2 leads-filter-column pull-right">
								<select name="hr_profile_deparment" class="selectpicker" id="hr_profile_deparment" data-width="100%"  data-live-search="true" data-none-selected-text="<?php echo _l('departments'); ?>"> 
									<option value=""></option>
									<?php 
									foreach ($departments as $value) { ?>
										<option value="<?php echo html_entity_decode($value['departmentid']); ?>"><?php echo html_entity_decode($value['name']) ?></option>
									<?php }
									?>              
								</select>
							</div>
							<div class="col-md-2 leads-filter-column pull-right">
								<select name="hr_profile_state" class="selectpicker" id="hr_profile_state" data-width="100%"  data-live-search="true" data-none-selected-text="All State"> 
									<option value=""></option>
									<?php 
									foreach ($state as $value) { ?>
										<option value="<?php echo html_entity_decode($value['short_name']); ?>"><?php echo html_entity_decode($value['state_name']) ?></option>
									<?php }
									?>              
								</select>
							</div>
							<div class="col-md-2 leads-filter-column ">
								<select name="hr_profile_report_to" class="selectpicker" id="hr_profile_report_to" data-width="100%"  data-live-search="true" data-none-selected-text="Report To"> 
									<option value=""></option>
									<?php 
									foreach ($staff_members as $value) { ?>
										<option value="<?php echo html_entity_decode($value['staffid']); ?>"><?php echo html_entity_decode($value['firstname']." ".$value['lastname']) ?></option>
									<?php }
									?>              
								</select>
							</div>
							

						</div>
						<br>
						

						<div class="row">
							<div class="col-md-12">
								<div class="modal bulk_actions" id="table_staff_bulk_actions" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title"><?php echo _l('hr_bulk_actions'); ?></h4>
											</div>
											<div class="modal-body">
												<?php if(has_permission('crm_mana_leads','','delete')){ ?>
													<div class="checkbox checkbox-danger">
														<input type="checkbox" name="mass_delete" id="mass_delete">
														<label for="mass_delete"><?php echo _l('hr_mass_delete'); ?></label>
													</div>
												<?php } ?>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
												<a href="#" class="btn btn-info" onclick="staff_delete_bulk_action(this); return false;"><?php echo _l('hr_confirm'); ?></a>
											</div>
										</div>
									</div>
								</div>
								<!--<div class="clearfix mtop20"></div>-->
								 &nbsp;&nbsp;<button class="btn btn-info pull-left mleft5 search_data" style="" id="search_data">Show</button>
     
            &nbsp;<a class="btn btn-default buttons-excel buttons-html5"  style=""  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
            <a class="btn btn-default" href="javascript:void(0);"  style=""  onclick="printPage();">Print</a>

								  
                   <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
            <div class="table-daily_report tableFixHead2">
             
              <table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
                  
                <thead>
                 
                    <tr style="display:none;">
                      <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span style="font-size:10px;font-weight:600;">Staff Information</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                    <th style="text-align:left;">AccountID</th>
                    <th id="sl" style="text-align:left;">Full Name</th>
                    <!--<th style="text-align:left;">Company Name</th>-->
                    <th style="text-align:left;">Mobile</th>
                    <th style="text-align:left;">State</th>
                    <th style="text-align:left;">Report To</th>
<!--                    <th style="text-align:left;">Departments</th>-->
                    <th style="text-align:left;">Designation</th>
                    <th style="text-align:left;">Active</th>
                   
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>   
            </div>
            <span id="searchh2" style="display:none;">
                                Loading.....
                            </span>
                 
						

								<?php
							
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal" id="delete_staff" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<?php echo form_open(admin_url('hr_profile/delete_staff',array('delete_staff_form'))); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
				</div>
				<div class="modal-body">
					<div class="delete_id">
						<?php echo form_hidden('id'); ?>
					</div>
					<p><?php echo _l('delete_staff_info'); ?></p>
					<?php
					echo render_select('transfer_data_to',$staff_members,array('staffid',array('firstname','lastname')),'staff_member',get_staff_user_id(),array(),array(),'','',false);
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
					<button type="submit" class="btn btn-danger _delete"><?php echo _l('hr_confirm'); ?></button>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>

	<div class="modal fade" id="staff_chart_view" tabindex="-1" role="dialog">
		<div class="modal-dialog w-100 h-100">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('hr_staff_chart'); ?></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12" id="st_chart">
								<div id="staff_chart"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<style>

</style>
	<div id="modal_wrapper"></div>
	<?php init_tail(); ?>
	<?php 
	require('modules/hr_profile/assets/js/hr_record/hr_record_js.php');
	?>
	<script>
	 $(document).ready(function(){
  function load_data(hr_profile_report_to,hr_profile_state,hr_profile_deparment,staff_role,status,staff_type)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>hr_profile/load_data_table",
      dataType:"json",
      method:"POST",
      data:{hr_profile_report_to:hr_profile_report_to, hr_profile_state:hr_profile_state, hr_profile_deparment:hr_profile_deparment, staff_role:staff_role, status:status, staff_type:staff_type},
      beforeSend: function () {
               
        $('#searchh2').css('display','block');
        $('.table-daily_report tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table-daily_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
        //  data1 = JSON.parse(data);
           
        //   console.log(data.length); 
           $('#table-daily_report tbody').html(data.html);
            if( data.hr_profile_state == undefined){
              state =  ''
            }else{
               state =  data.hr_profile_state 
            }
            if( data.hr_profile_report_to == undefined){
              hr_profile_report_to =  ''
            }else{
               hr_profile_report_to =  data.hr_profile_report_to 
            }
            if( data.hr_profile_deparment == undefined){
              hr_profile_deparment =  ''
            }else{
               hr_profile_deparment =  data.hr_profile_deparment 
            }
            if( data.staff_role == undefined){
              staff_role =  ''
            }else{
               staff_role =  data.staff_role 
            }
            if( data.staff_type == undefined){
              staff_type =  ''
            }else{
               staff_type =  data.staff_type 
            }
            var msg = "Filter ReportTo: "+hr_profile_report_to+ " State: " + state+' , Deparment: '+hr_profile_deparment+' , Designation: '+staff_role+' , Status :'+data.status+' staff type : '+staff_type;
	    $(".report_for").text(msg);
        // $('tbody').html(data);
      }
    }); 
  }
 $('#search_data').on('click',function(){
        var hr_profile_report_to = $("#hr_profile_report_to").val();
	    var hr_profile_state = $("#hr_profile_state").val();
	    var hr_profile_deparment = $("#hr_profile_deparment").val();
	    var staff_role = $("#staff_role").val();
	    var status = $("#status").val();
	    var staff_type = $("#staff_type").val();
	   // var msg = "Filter "+from_date +" To " + to_date;
	   // $(".report_for").text(msg);
        load_data(hr_profile_report_to,hr_profile_state,hr_profile_deparment,staff_role,status,staff_type);
    //   }else{
    //           alert("Please select State and Customer Type");
    //         }
        
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
      td6 = tr[i].getElementsByTagName("td")[6];
      //td7 = tr[i].getElementsByTagName("td")[7];
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
        
      }else if(td5){
         txtValue = td5.textContent || td5.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td6){
         txtValue = td6.textContent || td6.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }
      /*else if(td7){
         txtValue = td7.textContent || td7.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }*/
      else{
           tr[i].style.display = "none";
      } 
    }
    }
    
    }     
  }}
}
}
}
}
 </script>
 <script>
 
$("#caexcel").click(function(){
      var hr_profile_report_to = $("#hr_profile_report_to").val();
	    var hr_profile_state = $("#hr_profile_state").val();
	    var hr_profile_deparment = $("#hr_profile_deparment").val();
	    var staff_role = $("#staff_role").val();
	    var status = $("#status").val();
	    var staff_type = $("#staff_type").val();
	  
  $.ajax({
            url:"<?php echo admin_url(); ?>hr_profile/export_staff_info",
            method:"POST",
            data:{hr_profile_report_to:hr_profile_report_to, hr_profile_state:hr_profile_state, hr_profile_deparment:hr_profile_deparment, staff_role:staff_role, status:status, staff_type:staff_type},
      
            beforeSend: function () {
               
                
            },
            complete: function () {
                
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
         heading_data += '<td style="text-align:center;"colspan="3">Staff Information</td>';
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
 </script>
 <script>
    
      function sortTable(f,n){
	var rows = $('#table-daily_report tbody  tr').get();

	rows.sort(function(a, b) {

		var A = getVal(a);
		var B = getVal(b);

		if(A < B) {
			return -1*f;
		}
		if(A > B) {
			return 1*f;
		}
		return 0;
	});

	function getVal(elm){
		var v = $(elm).children('td').eq(n).text().toUpperCase();
		if($.isNumeric(v)){
			v = parseInt(v,10);
		}
		return v;
	}

	$.each(rows, function(index, row) {
		$('#table-daily_report').children('tbody').append(row);
	});
    }
    var f_sl = 1;
    var f_nm = 1;
    $("#sl").click(function(){
      if ( $('.up').css('display') == 'none')
    {
         $(".up_starting").hide()
      $(".up").show()
      $(".down").hide()
    }else{
         $(".up_starting").hide()
        $(".up").hide()
      $(".down").show()
    }
        f_sl *= -1;
        var n = $(this).prevAll().length;
        sortTable(f_sl,n);
    });
    $("#nm").click(function(){
        f_nm *= -1;
        var n = $(this).prevAll().length;
        sortTable(f_nm,n);
    });
 </script>
</body>
</html>
