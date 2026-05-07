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
                   <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate">Adjustment Id</label>-->
                             <input type="hidden" name="AdjID" id="AdjID" value="<?php echo $stock_details->AdjID; ?>">
                            <input type="text" value="<?= $stock_details->AdjID?>" readonly class="form-control" placeholder="Adjustment Id" name="adj_id" id="adj_id"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-3">
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
                          <?php $order_date = (isset($stock_details) ? _d(substr($stock_details->Transdate,0,10)) : $to_date);
                        echo render_date_input('prd_date','',$order_date); ?>
                        <!--<div class="form-group" app-field-wrapper="prd_date">
                            <div class="input-group date"><input type="text" id="prd_date" name="prd_date" class="form-control datetimepicker" value="<?= $order_date;?>" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="col-md-3">
                        <select name="comp " id="comp " disabled="true" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                            <option value=""></option>
                        </select> 
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-1">
                        <span></span><a href="#" class="btn btn-warning edit-new-order">View List</a>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <input type="hidden" name="vendor_code" id="vendor_code" value="<?php echo $stock_details->AccountID; ?>">
                        <select name="vendor" id="vendor"  class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                            <option value="">Select Vendor</option>
                         <?php foreach($vendors as $s) { ?>
                            <option value="<?php echo html_entity_decode($s['userid']); ?>" <?php if($stock_details->AccountID == $s['AccountID']){ echo 'selected'; }?>><?php echo html_entity_decode($s['company'])." - ".html_entity_decode($s['AccountID']); ?></option>
                        <?php } ?>
                        </select>  
                    </div>

                   <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control"  name="c_name" id="c_name" value="<?= $stock_details->company?>"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="group_name" id="group_name"  value="<?= $customer_groups_name->name; ?>" aria-invalid="false">
                           <input type="hidden" readonly="" class="form-control" name="group_id" id="group_id" value="<?= $customer_groups_name->id; ?>" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="">Location Type</label>-->
                            <?php
                                if($locations->LocationTypeID !==null){
                                    if($locations->LocationTypeID == 1){
                                        $location_data = 'Local';
                                      }else{
                                        $location_data = 'OutStanding';
                                      }
                                }
                            ?>
                           <input type="text" class="form-control" placeholder="Location type"  value="<?= $location_data;?>" name="location_type" id="location_type"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="address1" id="address1" value="<?= $client_details->address;?>" aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="address2" id="address2" value="<?= $client_details->Address3;?>" aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" value="" name="total_crates" id="total_crates" placeholder="Total Crates" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <?php $order_d = json_decode($order_detail);?>
                        <input type="hidden" name="camp_type_data" value="<?php print_r($order_d[0]->TType2);?>">
                        <!--<label for="">Adjustment for</label>-->
                        <select name="adj_type" id="adj_type "  class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                 
                            <option <?php if($order_d[0]->TType2 == 'Free Distribution'){echo 'selected';} ?> value="Free Distribution">Free Distribution</option>
                            <option <?php if($order_d[0]->TType2 == 'Promotional Activity'){echo 'selected';} ?> value="Promotional Activity">Promotional Activity</option>
                            <option <?php if($order_d[0]->TType2 == 'Stock Adjustment'){echo 'selected';} ?> value="Stock Adjustment">Stock Adjustment</option>
                            <option <?php if($order_d[0]->TType2 == 'Stock Damaged'){echo 'selected';} ?> value="Stock Damaged">Stock Damaged</option>
                        </select> 
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="city" id="city" value="<?= $client_details->city;?>" aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="state_id" id="state_id"  value="<?= $stock_details->state?>"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="state" id="state"  value="<?= $stock_details->state_name?>" aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-1">
                
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <?php $val_data = 0; 
                            foreach($total_cases as $val){ 
                                $val_data += $val->BilledQty; 
                            } ?>
                           <input type="text" readonly="" class="form-control" name="t_cases"   id="t_cases" placeholder="Total Cases" aria-invalid="false" value="<?= $val_data;?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <!--<label for="">Reason for Adj</label>-->
                        <input type="text" class="form-control" placeholder="Reason for Adj" name="reason_for_Adj" id=" "  aria-invalid="false" value="<?= $stock_details->Remarks?>">
                    </div>
                    <div class="col-md-2 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="gst" id="gst" value="<?= $stock_details->vat?>"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="station" id="station" value="<?= $stock_details->StationName?>" aria-invalid="false">
                           
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="adjust_value" id="adjust_value" placeholder="Adjust Value" value="<?= $stock_details->AdjAmt;?>" aria-invalid="false">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="panel-body mtop10">
        <div class="row col-md-12">
        <p class="bold p_style"><?php echo _l('Stock adjustment'); ?></p>
        <hr class="hr_style"/>
         <div class="" id="example">
         </div>
         <?php echo form_hidden('pur_order_detail'); ?>
          <div class="col-md-4">
             </div>
         <div class="col-md-4">
              
         </div>
         <div class="col-md-4 ">
            
         </div> 
         
        </div>
        </div>
        <div class="row">
          <div class="col-md-12 mtop15">

                <div id="vendor_data">
                  
                </div>

                <div class="btn-bottom-toolbar text-right" style="width: 100%;">
                <?php
                if (has_permission_new('stock_adjustment', '', 'edit')) {  
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $lgstaff = $this->session->userdata('staff_user_id');
                $Adjdate = substr($stock_details->Transdate,0,10);
                
                $Adjdate_new    = new DateTime($Adjdate);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                /*$sql = 'SELECT * FROM tblstockadjmaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblstockadjmaster.AdjID DESC ';
                $result_data = $this->db->query($sql)->row();
                $lastdate_Adj = substr($result_data->Transdate,0,10);*/
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $this->db->select('*');
                $this->db->where('plant_id', $selected_company);
                $this->db->where('year', $fy);
                $this->db->where('staff_id', $lgstaff);
                $this->db->LIKE('feature', "stock_adjustment");
                $this->db->LIKE('capability', "view");
                $this->db->from(db_prefix() . 'staff_permissions');
                $result2 = $this->db->get()->row();
                $day = $result2->days;
                
                if($day == 0){
                            $return = '';
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($Adjdate_new < $tillDate_new) {
                                $return = 'disabled';
                            }else{
                                $return = '';
                            }
                        }
            ?>
            <?php if($return == "disabled"){
            ?>
            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
            <?php
            }else{
            ?>
            <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                  Update</button>
            <?php
            }?>  
                  
                <?php } ?>
                </div>
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>
        </div>
        </div>

			</div>
			<?php echo form_close(); ?>
			
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="transfer-modal">
   <div class="modal-dialog modal-lg" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Adjustments List</h4>
         </div>
         
         
         <div class="modal-body" style="padding:5px;">
             
              <div class="row">
                
                <div class="col-md-3">
                    <?php
                   echo render_date_input('from_date','From',$from_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('to_date','To',$to_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
                </div>
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                <div class="col-md-12">
                 
            <div class="table_adj_report">
             
              <table class="tree table table-striped table-bordered table_adj_report" id="table_adj_report" width="100%">
                  
                <thead>
                    <tr style="display:none;">
                      <td colspan="8" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                             <th >StkAdjID</th>
                             <th >EntryDate</th>
                             <th >AccountName</th>
                             <th style=" text-align:left;">AdjType</th>
                             <th style=" text-align:left;">StkAdjAmt</th>
                             <th style=" text-align:left;">UserID</th>
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
    .table_adj_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_adj_report thead th { position: sticky; top: 0; z-index: 1; }
.table_adj_report tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.table_adj_report table  { border-collapse: collapse; width: 100%; }
.table_adj_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.table_adj_report th     { background: #50607b;color: #fff !important; }


#table_adj_report tr:hover {
    background-color: #ccc;
}

#table_adj_report td:hover {
    cursor: pointer;
}
</style>

<?php init_tail(); ?>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
  function load_data(from_date,to_date)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>Stock_adjustment/load_data_for_stock_adj",
      dataType:"JSON",
      method:"POST",
      data:{from_date:from_date, to_date:to_date},
      beforeSend: function () {
               
        $('#searchh2').css('display','block');
        $('.table_adj_report tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table_adj_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
        var html = '';
      
        for(var count = 0; count < data.length; count++)
        {
           
          var url = "'<?php echo admin_url() ?>Stock_adjustment/stock_list/"+data[count].AdjID+"'";
        html += '<tr onclick="location.href='+url+'">';
        html += '<td style="text-align:center;">'+data[count].AdjID+'</td>';
          
        var date = data[count].Transdate.substring(0, 10)
        var date_new = date.split("-").reverse().join("/");
          
          html += '<td  style="text-align:center;">'+date_new+'</td>';
          
        var AccoutName = data[count].AccountName;
         
          html += '<td  style="text-align:left;">'+ AccoutName +'</td>';
          html += '<td  style="text-align:left;">'+data[count].adjType+'</td>';
          html += '<td  style="text-align:center;">'+data[count].AdjAmt+'</td>';
          html += '<td style="text-align:center;">'+data[count].UserID+'</td>';
          
          html += '</tr>';
        }
         $('.table_adj_report tbody').html(html);
      
      }
    });
  }
  
 $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
        load_data(from_date,to_date);
        
 });

});
</script>
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
   
    $('#prd_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    });
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
        var e_dat2 = new Date(year2+'/03/31');
        var maxEndDate_new = e_dat2;
    }
    
    var minStartDate = new Date(year, 03);
   
    
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
        timepicker: false,
        showOtherMonths: false,
        pickTime: false,
            orientation: "left",
    });
    
    });
</script>
</body>

</html>
<?php //require 'assets/js/pur_order_js.php';?>
 
<?php $this->load->view('admin/stock_a/stock_a_js'); ?>


