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
				<nav aria-label="breadcrumb">
    				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
    					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
    					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
    					<li class="breadcrumb-item active" aria-current="page"><b>Stock Adjustment</b></li>
					</ol>
				</nav>
                <hr class="hr_style">
            <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                        <?php
            			    
            			   $selected_company = $this->session->userdata('root_company');
            			   $fy = $this->session->userdata('finacial_year');
                            if($selected_company == 1){
                                $next_stock_adj_number = get_option('next_stock_adj_number_for_cspl');
                            }elseif($selected_company == 2){
                                $next_stock_adj_number = get_option('next_stock_adj_number_for_cff');
                            }elseif($selected_company == 3){
                                $next_stock_adj_number = get_option('next_stock_adj_number_for_cbu');
                            }elseif($selected_company == 4){
                                $next_stock_adj_number = get_option('next_stock_adj_number_for_cbupl');
                            }
                        
                           
                            $prefix = "ADJ";
                                           
                            $prefix = $prefix.'<span id="prefix_year">'.$fy.'</span>';
                            
                            $_sale_stock_adj_number = str_pad($next_stock_adj_number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                                           
                        ?>
               
                          
                          <div class="col-md-2">
                            <div class="form-group ">
                                
                                <div class="input-group">
                                <span class="input-group-addon"><?php echo $prefix; ?></span>
                                    <input type="hidden" name="AdjID" id="AdjID" value="<?php echo $stock_adj->AdjID; ?>">
                                    <input type="hidden" name="adj_id" id="adj_id" value="<?php echo $_sale_stock_adj_number; ?>">
                                    <input type="text" name="adj_id2" id="adj_id2" class="form-control " value="<?php if(isset($stock_adj)){ echo substr($stock_adj->AdjID,5);}else{ echo $_sale_stock_adj_number; } ?>" <?php if(isset($stock_adj)){ echo "disabled";} ?>>
                                            
                                </div>
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
                            <?php $stock_adj_date = (isset($stock_adj) ? _d($stock_adj->order_date) : $to_date);
                            echo render_date_input('prd_date','',$stock_adj_date); ?>
                         
                        </div>
                        <div class="col-md-3">
                           <!-- <select name="comp " id="comp " class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                 <option value="">Crazy Bakery Udyog</option>
                            </select> -->
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-1">
                          <span></span><a href="#" class="btn btn-warning edit-new-order">View List</a>
                        </div>
                          
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                           
                            <select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                <option value="">Select Party</option>
                            <?php foreach($vendors as $s) { ?>
                                <option value="<?php echo html_entity_decode($s['userid']); ?>" ><?php echo html_entity_decode($s['company'])." - ".html_entity_decode($s['AccountID']); ?></option>
                            <?php } ?>
                            </select> 
                        </div>

                        <div class="col-md-3 ">
                            <div class="form-group">
                                <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control"  name="c_name" id="c_name"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <!--<label for="estimate"></label>-->
                                <input type="text" readonly="" class="form-control" name="group_name" id="group_name"  aria-invalid="false">
                                <input type="hidden" readonly="" class="form-control" name="group_id" id="group_id"  aria-invalid="false">
                            </div>
                        </div>
                           
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <!--<label for="">Location Type</label>-->
                                <input type="text"  class="form-control" placeholder="Location type" name="location_type" id="location_type"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <!--<label for="estimate"></label>-->
                                <input type="text" readonly="" class="form-control" name="address1" id="address1"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <!--<label for="estimate"></label>-->
                                <input type="text" readonly="" class="form-control" name="address2" id="address2"  aria-invalid="false">
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
                    <!--<label for="">Adjustment for</label>-->
                        <select name="adj_type" id="adj_type " class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                            <option value="Free Distribution">Free Distribution</option>
                            <option value="Promotional Activity">Promotional Activity</option>
                            <option value="Stock Adjustment">Stock Adjustment</option>
                            <option value="Stock Damaged">Stock Damaged</option>
                        </select> 
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="city" id="city"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="state_id" id="state_id"  aria-invalid="false">
                         </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="state" id="state"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-1">
                
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="t_cases"  id="t_cases" placeholder="Total Cases" aria-invalid="false">
                        </div>
                    </div>
                    
                </div> 
                   
              
                <div class="row">
                    <div class="col-md-3">
                        <!--<label for="">Reason for Adj</label>-->
                        <input type="text"  class="form-control" placeholder="Reason for Adj" name="reason_for_Adj" id="reason_for_Adj"  aria-invalid="false">
                    </div>
                    <div class="col-md-2 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="gst" id="gst"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="station" id="station"  aria-invalid="false">
                           
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                           <input type="text" readonly="" class="form-control" name="adjust_value" id="adjust_value" placeholder="Adjust Value" aria-invalid="false">
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
                if (has_permission_new('stock_adjustment', '', 'create')) {  
                ?>
                  <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                  <?php echo _l('submit'); ?>
                  </button>
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
            <h4 class="modal-title"> Adjustments List</h4>
         </div>
         <div class="modal-body" style="padding:5px;">
             
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
                   <!-- <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">-->
                </div>
                <div class="col-md-12">
                 
            <div class="table_adj_report">
             
              <table class="tree table table-striped table-bordered table_adj_report" id="table_adj_report" width="100%">
                  
                <thead>
                  <tr>
                             <th class="sortablePop">StkAdjID</th>
                             <th  class="sortablePop">EntryDate</th>
                             <th  class="sortablePop">AccountName</th>
                             <th class="sortablePop" style=" text-align:left;">AdjType</th>
                             <th class="sortablePop" style=" text-align:left;">StkAdjAmt</th>
                             <th class="sortablePop" style=" text-align:left;">UserID</th>
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
        <div class="modal-footer" style="padding:0px;">
            <input type="text" id="myInput1"  autofocus="1" name='myInput1' onkeyup="myFunction2()" placeholder="Search for names.."  style="float: left;width: 100%;">
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

</body>
<style>
    table.dataTable tbody td {
    padding: 4px 4px !important;
    font-size: 11px;
}
</style>
</html>
<?php //require 'assets/js/pur_order_js.php';?>
 
<?php $this->load->view('admin/stock_a/stock_a_js'); ?>
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
    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_adj_report");
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
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_adj_report tbody");
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
