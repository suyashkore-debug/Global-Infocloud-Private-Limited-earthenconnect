<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="panel_s">
          <div class="panel-body">
              <div class="_buttons">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="act_name">Account ID</label>
                             <select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                  <option value=""></option>
                                    <?php foreach($vendors as $s) { ?>
                                    <option value="<?php echo html_entity_decode($s['AccountID']); ?>" ><?php echo html_entity_decode($s['company'])." - ".html_entity_decode($s['AccountID']); ?></option>
                                      <?php } ?>
                                </select> 
                                        
                        </div>
                    </div>
                    <div class="col-md-3">
                        <br>
                        <div class="form-group">
                        <input type="text" name="account_full_name" id="account_full_name" class="form-control" value="" readonly>
                        <!--<input type="hidden" name="account_source" id="account_source" class="form-control" value="">-->
                        </div>
                    </div>
                    <div class="col-md-3">
                       
                        <div class="form-group">
                            <label for="estimate"></label>
                           <input type="text" readonly="" class="form-control" name="address" id="address" aria-invalid="false">
                         </div>
                    </div>
                    <div class="col-md-3">
                    <div class="form-group">
                            <label for="estimate"></label>
                            <input type="text" readonly="" class="form-control" name="address2" id="address2" aria-invalid="false">
                         </div>
                    </div>
                </div>
                
                <div class="row">
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
                        <div class="col-md-3">
                            
                            <div class="form-group" app-field-wrapper="from_date">
                                <label for="from_date" class="control-label from_date_text">Billing Date</label>
                                <div class="input-group date">
                                    <input type="text" id="from_date" name="from_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                       <i class="fa fa-calendar calendar-icon"></i>
                                     </div>
                                </div>
                             </div>
                           
                            <?php// echo render_date_input('from_date','FROM',$from_date);  ?>
                        </div>
                        
                        <div class="col-md-3">
                            
                            <div class="form-group" app-field-wrapper="to_date">
                                <label for="to_date" class="control-label to_date_text">Vehicle Return Date</label>
                                <div class="input-group date">
                                    <input type="text" id="to_date" name="to_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar calendar-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <?php //echo render_date_input('to_date','TO',$to_date); ?>
                        </div>
                        <div class="col-md-3">
                    <div class="form-group">
                            <label for="estimate"></label>
                            <input type="text" readonly="" class="form-control" name="city" id="city" aria-invalid="false">
                         </div>
                    </div>
                       <div class="col-md-3">
                    <div class="form-group">
                            <label for="estimate"></label>
                            <input type="text" readonly="" class="form-control" name="state_f" id="state_f" aria-invalid="false">
                         </div>
                    </div>
                    </div> 
                    </div>
                 
                
                <div class="row"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estimate">State</label>
                           <select name="state_type" id="state_type" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="None" tabindex="-98">
                              <option></option>
                              <?php foreach($state_list as $state){?>
                                   <option value="<?php echo $state['short_name'] ; ?>"><?php echo $state['state_name'];?></option>
                              <?php } ?>
                              
                           </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estimate">Location Type</label>
                           <select name="loc_type" id="loc_type" class="form-control">
                               <option value="1">Local</option>
                                <option value="2">OutStation</option>
                                <option value="3">Not Defined</option>
                           </select>
                           </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="estimate">Order By</label>
                           <select name="order_by" id="order_by" class="form-control">
                               <option value="1">Station Name</option>
                                <option value="2">Account Name</option>
                           </select>
                           </div>
                        </div>
                <div class="clearfix"></div>   
                <div class="col-md-6">
                    <div class="custom_button">
                        <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;">Show</button>
                    </div>
                
                    <div class="custom_button">
                        <button class="btn btn-default pull-left mleft5 " href="javascript:void(0);"    onclick="printPage();" style="font-size:12px;">Print</button>
                    </div>
                    <div class="custom_button">
                        &nbsp;&nbsp;<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="production_report" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
                                <!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Print</span></a>-->
                    </div>
                </div>    
                <div class="col-md-6">
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>    
                </div> 
                  
                <div class="clearfix"></div>
                <br>
                <div class="col-md-12">
                    <span id="searchh" style="display:none;">Please wait fetching data</span>
                    <span id="searchh11" style="display:none;">Please wait exporting data..</span>
                    <div class="CrateLedger load_data">
              
                    </div>
                    
                </div>
                
             </div>
            </div>
          </div>
</div>
</div>
</div>
</div>
</div>

<style>
    .CrateLedger { overflow: auto;max-height: 60vh;position:relative;top: 0px; }
.CrateLedger thead th { position: sticky; top: 0; z-index: 1; }
.CrateLedger tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.CrateLedger table  { border-collapse: collapse; }
.CrateLedger th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.CrateLedger th     { background: #50607b;color: #fff !important; }


</style>
<?php init_tail(); ?>
<!--new update -->
<script>
    
function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("CrateLedger");
  tr = table.getElementsByTagName("tr");
  for (i = 4; i < tr.length; i++) {
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
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    $( "#from_date" ).change(function() {
        $('.load_data').html('');
    });
    $( "#to_date" ).change(function() {
        $('.load_data').html('');
    });
    $( "#vendor" ).change(function() {
        $('.load_data').html('');
       if(this.value == ""){
            $('#state_type').attr("disabled", false); 
            $('#loc_type').attr("disabled", false);
            $("#account_full_name").val('');
            $("#address").val('');
            $("#address2").val('');
            $("#city").val('');
            $("#state_f").val('');
            $('.from_date_text').html('Billing Date');
            $('.to_date_text').html('Vehicle Rtn Date');
       }else{
           $('#state_type').attr("disabled", true); 
            $('#loc_type').attr("disabled", true); 
            $('.from_date_text').html('From Date');
            $('.to_date_text').html('To Date');
       }
        
        
   if(this.value != 0){
    $.post(admin_url + 'Misc_reports/get_vendor_data/'+this.value).done(function(response){
    
    response = JSON.parse(response);
     
      $("#account_full_name").val(response.vendor.company);
      $("#address").val(response.vendor.address);
      $("#address2").val(response.vendor.Address3);
      $("#city").val(response.vendor.city_name);
      $("#state_f").val(response.vendor.state_name);
    
    });
   }
});


 $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var accountId = $("#vendor").val();
	    var state_type = $("#state_type").val();
	    var loc_type = $("#loc_type").val();
	    var order_by = $("#order_by").val();
	    var account_full_name = $("#account_full_name").val();
	    
	        $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/get_cretes_dataNew",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{from_date:from_date,account_full_name:account_full_name, to_date:to_date,accountId:accountId,state_type:state_type,loc_type:loc_type,order_by:order_by},
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

 $("#caexcel").click(function(){
 var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var accountId = $("#vendor").val();
	    var state_type = $("#state_type").val();
	    var loc_type = $("#loc_type").val();
	    var order_by = $("#order_by").val();
	    var account_full_name = $("#account_full_name").val();
	  
        $.ajax({
            url:"<?php echo admin_url(); ?>misc_reports/export_crate_legder",
            method:"POST",
           data:{from_date:from_date,account_full_name:account_full_name, to_date:to_date,accountId:accountId,state_type:state_type,loc_type:loc_type,order_by:order_by},
            beforeSend: function () {
             $('#searchh11').css('display','block');  
                
            },
            complete: function () {
                $('#searchh11').css('display','none');  
            },
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
            }
        });
});


function printPage(){
    var html_filter_name =    $('.report_for').html();
    // $('.print_hide').show();
    //      var from_date = $("#from_date").val();
	   // var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">Crate Legder</td>';
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

<style>
    input[type=checkbox], input[type=radio] {
    margin: 4px 4px 0px;
    line-height: normal;
}
</style>

<script>
$(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    
    
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y > fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    
    var minStartDate = new Date(year, 03);
   /* console.log(minStartDate);
    console.log(maxEndDate_new);*/
    
    $('#from_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    $('#to_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
});
</script> 


