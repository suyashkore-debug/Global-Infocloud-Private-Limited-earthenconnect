<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form'));
			
			?>
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
      
           <?php
                  $customer_custom_fields = false;
                  if(total_rows(db_prefix().'customfields',array('fieldto'=>'pur_order','active'=>1)) > 0 ){
                       $customer_custom_fields = true;
                  }
                   ?>
            <div class="tab-content">
                <?php if($customer_custom_fields) { ?>
                 <div role="tabpanel" class="tab-pane" id="custom_fields">
                    <?php $rel_id=( isset($pur_order) ? $pur_order->id : false); ?>
                    <?php echo render_custom_fields( 'pur_order',$rel_id); ?>
                 </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                    <div class="row">
                       <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
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
                            ?>
                                        <?php $order_date = (isset($pur_order) ? _d($pur_order->order_date) : $to_date);
                                        echo render_date_input('as_on','As on',$order_date); ?>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="estimate">Route Id</label>
                                        <select name="route_id" id="route_id"  class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                            <option></option>
                                        <?php foreach($route as $s) { ?>
                                            <option value="<?php echo html_entity_decode($s['RouteID']); ?>" data-name="<?php echo $s['name']; ?>" <?php if(isset($pur_order) && $pur_order->vendor == $s['userid']){ echo 'selected'; }else{ if(isset($ven) && $ven == $s['userid']){ echo 'selected';} } ?>><?php echo html_entity_decode($s['name']); ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                               
                                   <div class="row">
                                <div class="col-md-4">
                         <div class="form-group">
                             <label for="estimate">Loc Type</label>
                               <select name="loc_type" id="loc_type" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
                                <option value="1" data-name="Local">Local</option>
                                <option value="2" data-name="OutStation">OutStation</option>
                                <option value="3" selected data-name="NotDefined">NotDefined</option>
                            </select>
   
                          </div>
                         
                      </div>
                           <div class="col-md-8">
                          <?php echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                         
                      </div>
                       <div class="col-md-4">
                         <div class="form-group">
                             <label for="estimate">Report In</label>
                            <select name="report_in" id="report_in" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                        <option value=""></option>
                                <option value="">VP</option>
                                <option value="">DGM</option>
                                <option value="4">RSM</option>
                                <option value="5">ASM</option>
                                <option value="6">ASE</option>
                                <option value="7">TSI</option>
                                <option value="8">SO</option>

                        </select>    
                          </div>
                         
                      </div>
                      <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Staff Name</label>
                                <select name="staff_name" id="staff_name" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
                                <option value=""></option>
                                
                            </select>
                            </div>
                        </div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="row">
                        <div class="col-md-5">
                            <label for="estimate">Report Type</label>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="report_type" id="report_type" >
                              <label class="form-check-label" for="report_type">
                                Parent Controlled - Consolidated
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="report_type" id="report_type" value="T" checked>
                              <label class="form-check-label" for="report_type">
                                Individual Identity - Consolidated
                              </label>
                            </div>
                            
                            <div class="form-group">
                             <label for="estimate"></label>
                             <select name="dist_type" id="dist_type" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                        <option value="">Select All</option>
                        <?php foreach($dist_type as $s) { ?>
                        <option value="<?php echo html_entity_decode($s['id']); ?>" date-name="<?php echo $s['name'];?>"><?php echo html_entity_decode($s['name']); ?></option>
                        <?php } ?>
                        

                        </select>    
                          </div>
                      </div>
                       <div class="col-md-3">
                         <label for="estimate">Sales Type</label>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sale_type" id="sale_type" checked>
                              <label class="form-check-label" for="sale_type">
                                All
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sale_type" id="sale_type" value="T">
                              <label class="form-check-label" for="sale_type">
                                Tax
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sale_type" id="sale_type" value="B">
                              <label class="form-check-label" for="sale_type">
                               BOS
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sale_type" id="sale_type" value="M">
                              <label class="form-check-label" for="sale_type">
                              Depot Transfer
                              </label>
                            </div>
                         
                      </div>
                       <div class="col-md-3">
                            <div class="form-group">
                                <label for="estimate">Item Division</label>
                                <select name="itemdivision" id="itemdivision" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo 'All Item Division'; ?>" >
                                    <option value="All">All Item Division</option>
                                <?php foreach($item_division as $s) { ?>
                                    <option value="<?php echo html_entity_decode($s['id']); ?>" ><?php echo html_entity_decode($s['name']); ?></option>
                                <?php } ?>
                                </select> 
                                <button class="btn btn-secondry">Find MaxCredit</button>
                            </div>
                          
                        </div>
                    </div>
                 
                <div class="row">
                        
                       <div class="col-md-3 ">
                         <div class="form-group">
                             <label for="estimate"></label>
                            <a href="#" class="btn btn-primary show" id="search_data">Show</a>   
                          </div>
                         
                      </div>
                        <div class="col-md-6 ">
                            <br>
                            <div class="custom_button">
                                <a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
                                <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
                                <!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
                            </div>
                         
                        </div>
                        <div class="col-md-6">
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>  
                      
                    </div>
                </div>

                    </div>
                 
              
                   
                

              </div>
            </div>
             <div class="clearfix"></div>
            
        <?php
        //print_r($company_detail);
        ?>
            <div class="table-market_outstanding">
              
            </div>
            <span id="searchh" style="display:none;">
                                Loading.....
                            </span>
        </div>
       
</div>
</div>
</div>
</div>
</div>


<?php init_tail(); ?>
</body>
</html>
<style>
    .table-market_outstanding { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table-market_outstanding thead th { position: sticky; top: 0; z-index: 1; }
.table-market_outstanding tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.table-market_outstanding table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
.table-market_outstanding th     { background: #50607b;color: #fff !important; }
</style>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script>
 

/*$( "#route_id" ).change(function() {

    var rount_name =$(this).find(':selected').attr('data-name')

    if(rount_name != undefined){
        $('#route').val(rount_name);
    }else{
        $('#route').val('All Route');
    }
  
});*/

 $('#search_data').on('click',function(){
        
	   var as_on = $("#as_on").val();
	   var sale_type = $('input[name="sale_type"]:checked').val();
	   var routID = $("#route_id").val();
	   var routName = $("#route_id").find(':selected').attr('data-name');
	   var states = $("#states").val();
	   var loc_type = $("#loc_type").val();
	   var dist_type = $("#dist_type").val();
	   var staff_id = $("#staff_name").val();
	   var staff_name = $("#staff_name").find(':selected').attr('data-name');
	  
	    $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/market_outstanding_report",
          dataType:"JSON",
          method:"POST",
          cache: false,
        //data:{from_date:from_date, to_date:to_date, report_in:report_in, states:states, client_type:client_type, item_group:item_group, loc_type:loc_type, report_type:report_type,staff_designation:staff_designation,staff_id:staff_id},
          data:{as_on:as_on,routID:routID,states:states,loc_type:loc_type,dist_type:dist_type,staff_id:staff_id,staff_name:staff_name,routName:routName},
          beforeSend: function () {
                   
            $('#searchh').css('display','block');
            $('.table-market_outstanding').css('display','none');
            
         },
          complete: function () {
                                
            $('.table-market_outstanding').css('display','');
            $('#searchh').css('display','none');
         },
          success:function(data){
            $('.table-market_outstanding').html(data);
          }
        });
	  
 });
 
 $('#report_in').on('change', function() {
	var id = $(this).val();
	//alert(id);
	var url = "<?php echo base_url(); ?>admin/sale_reports/staff_list_by_role";
        jQuery.ajax({
                type: 'POST',
                url:url,
                data: {id: id},
                dataType:'json',
                success: function(data) {
                           
                    $("#staff_name").children().remove();
                    $('#staff_name').append('<option value="">Non Selected</option>');
                    $.each(data, function (index, value) {
                        // APPEND OR INSERT DATA TO SELECT ELEMENT.
                        $('#staff_name').append('<option value="' + value.staffid + '" data-name="' +value.firstname +' '+ value.lastname +'">' + value.firstname +' '+ value.lastname + '</option>');
                    });
                               
                    $("#staff_name").selectpicker("refresh");
                }
        });
	});

 
 $("#caexcel").click(function(){
 var as_on = $("#as_on").val();
	   var sale_type = $('input[name="sale_type"]:checked').val();
	   var routID = $("#route_id").val();
	   var routName = $("#route_id").find(':selected').attr('data-name');
	   var states = $("#states").val();
	   var loc_type = $("#loc_type").val();
	   var dist_type = $("#dist_type").val();
	   var staff_id = $("#staff_name").val();
	   var staff_name = $("#staff_name").find(':selected').attr('data-name');
	  
  $.ajax({
            url:"<?php echo admin_url(); ?>misc_reports/export_market_outstanding",
            method:"POST",
            data:{as_on:as_on,routID:routID,states:states,loc_type:loc_type,dist_type:dist_type,staff_id:staff_id,staff_name:staff_name,routName:routName},
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
        
    var from_date = $("#as_on").val();
    var routName = $("#route_id").find(':selected').attr('data-name');
    var state = $("#state").val();
    if(state == "undefined"){
        state = "";
    }
    var loc_type_name = $("#loc_type").find(':selected').attr('data-name');
	var client_type_name = $("#dist_type").find(':selected').attr('data-name');
	var staff_name = $("#staff_name").find(':selected').attr('data-name');
	
	var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
    var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="11"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="11"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td colspan="11" style="font-size:10px;font-weight:600;text-align:center;">Date : '+from_date+', RoutName : '+routName+', States : '+state+', Loc Type : '+loc_type_name+', Distributor Type : '+client_type_name+', StaffName : '+staff_name+'</td>';
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
    
function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table-market_outstanding");
  tr = table.getElementsByTagName("tr");
  for (i = 3; i < tr.length; i++) {
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
      }else if(td1){
         txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td2){
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
      } else {
        tr[i].style.display = "none";
      }
    }    }} }}}  
  }
}
</script>
 <script>
    $(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y => fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    
    var minStartDate = new Date(year, 03);
   
    $('#as_on').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    });
</script> 
