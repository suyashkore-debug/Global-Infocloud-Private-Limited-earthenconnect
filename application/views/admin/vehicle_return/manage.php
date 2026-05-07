<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .form-group{
        margin-bottom: 1px;
    }
    input[type=text]{
        height: 29px !important;
    }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form'));
			
			?>
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
      
            <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                     <?php
                                $data_attr = array();
                                if(isset($sale_return)){
                                    $data_attr = array(
                                        "disabled" =>true
                                        );
                                }
                                ?>    
               
                          
                          <div class="col-md-2">
                            <?php
                                $selected_company = $this->session->userdata('root_company');
                                if($selected_company == 1){
                                        
                                        $new_vehicle_returnNumber = get_option('next_vehicle_return_number_for_cspl');
                                        
                                    }elseif($selected_company == 2){
                                        $new_vehicle_returnNumber = get_option('next_vehicle_return_number_for_cff');
                                        
                                    }elseif($selected_company == 3){
                                        $new_vehicle_returnNumber = get_option('next_vehicle_return_number_for_cbu');
                                        
                                    }
                                   $format = get_option('invoice_number_format');
                
                               
                                $prefix = "VRT";
                                
                               if ($format == 1) {
                                 $__number = $new_vehicle_returnNumber;
                                 
                                 $prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>';
                                 
                               } else if($format == 2) {
                                 
                                  $__number = $new_vehicle_returnNumber;
                                  
                                  $prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>/';
                                  
                               } else if($format == 3) {
                                  $__number = $new_vehicle_returnNumber;
                               } else if($format == 4) {
                                  
                                  $yyyy = date('Y');
                                  $mm = date('m');
                                  $__number = $new_vehicle_returnNumber;
                               }
                
                              
                               $_vehicle_return_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                               
                                ?>
                                <div class="form-group credit_div">
                                           <!--<label for="number">
                                              Returnno
                                             </label>-->
                                    <div class="input-group">
                                <span class="input-group-addon">
                                              <?php
                                                echo $prefix;
                                              ?>
                                              </span>
                                              <!--<input type="hidden" name="ex_sale_return_id" id="ex_sale_return_id" value="<?php if(isset($sale_return)){ echo $sale_return->SalesRtnID;}?>">-->
                                              <input type="text" name="vehicle_return_id" id="vehicle_return_id" class="form-control vehicle_return_id" value="<?php if(isset($sale_return)){ echo substr($sale_return->SalesRtnID,5);}else{ echo $_vehicle_return_number; } ?>" <?php if(isset($sale_return)){ echo "disabled";} ?>>
                                            <?php if(isset($sale_return)){
                                                ?>
                                            <input type="hidden" name="updated_record" value=" " id="updated_record">
                                            <input type="hidden" name="new_record" value=" " id="new_record">
                                            <?php } ?>
                                    </div>
                                    </div>
                                      
                              
                            <!--<div class="form-group ">
                                <input type="text" name="return_no" id="return_no" class="form-control " placeholder="Retrn No" value="" <?php //if(isset($stock_adj)){ echo "disabled";} ?>>
                            </div>-->
                           
                      </div>
                        <div class="col-md-2">
                            <?php $stock_adj_date = (isset($stock_adj) ? _d($stock_adj->order_date) : _d(date('Y-m-d')));
                            echo render_date_input('from_date','',$stock_adj_date); ?>
                         
                        </div>
                        <div class="col-md-3">
                           
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-1">
                          <!--<span></span><a href="#" class="btn btn-primary edit-new-order">View List</a>-->
                        </div>
                         <div class="col-md-1">
                          <span></span><a href="#" class="btn btn-primary edit-vehicle_return">View List</a>
                        </div>
                          
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                           
                           <div class="form-group ">
                                
                                    
                                        <input type="text" name="challan_n" placeholder="Challan No" id="challan_n" class="form-control "  value="">
                                
                            </div>
                        </div>
                         <div class="col-md-2">
                            <?php $stock_adj_date = (isset($stock_adj) ? _d($stock_adj->order_date) : _d(date('Y-m-d')));
                            echo render_date_input('to_date','',$stock_adj_date); ?>
                         
                        </div>
                        <div class="col-md-6 ">
                           
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                               
                                <input type="text" readonly="" class="form-control" placeholder="Route code" name="route_code" id="route_code"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <input type="text" readonly="" class="form-control" name="route_name" id="route_name"  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-2 ">
                            <div class="form-group">
                                <input type="text" readonly="" class="form-control" name="" id=""  aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-3"></div>
               
                       
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                         <div class="form-group">
                                <input type="text" readonly="" class="form-control" value="" name="vehicle_number" id="vehicle_number" placeholder="vehicle" aria-invalid="false">
                            </div>
                    </div>
                    <div class="col-md-3 ">
                       
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" readonly="" placeholder="Vehi.Capacity" class="form-control" name="vehicle_capc" id="vehicle_capc"  aria-invalid="false">
                         </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text"  placeholder="CaseDeposit" class="form-control" name="case_depo" id="case_depo"  aria-invalid="false">
                        </div>
                    </div>
                    
                    
                    <div class="col-md-2">
                        
                    </div>
                    
                </div> 
                   
              
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" readonly="" class="form-control" placeholder="Driver" name="driver_id" id="driver_id"  aria-invalid="false">
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="driver_name" id="driver_name"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" placeholder="ChallanCrates" name="challan_crates" id="challan_crates"  aria-invalid="false">
                           
                        </div>
                    </div>
                  
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text"  class="form-control" name="check_depo" id="check_depo" placeholder="CheckDeposite" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" readonly="" class="form-control" placeholder="Loder" name="loder_id" id="loder_id"  aria-invalid="false">
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="loder_name" id="loder_name"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" readonly="" class="form-control" placeholder="RefundCrates" name="refund_crates" id="refund_crates"  aria-invalid="false">
                           
                        </div>
                    </div>
                  
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text"  class="form-control" name="NERT_trans" id="NERT_trans" placeholder="NERT/Transfer" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" readonly="" class="form-control" placeholder="Sales Man" name="salesman_id" id="salesman_id"  aria-invalid="false">
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <!--<label for="estimate"></label>-->
                            <input type="text" readonly="" class="form-control" name="salesman_name" id="salesman_name"  aria-invalid="false">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text"  class="form-control" placeholder="FreshReturn Amt" name="fresh_ret_amt" id="fresh_ret_amt"  aria-invalid="false">
                           
                        </div>
                    </div>
                  
                    <div class="col-md-2">
                        <div class="form-group">
                           <input type="text"  class="form-control" name="total_expense" id="total_expense" placeholder="TotalExpenses" aria-invalid="false">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="panel-body mtop10">
        <div class="row col-md-12">
            <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home" class="crate_details">Crate Details</a></li>
    <li><a data-toggle="tab" href="#menu1" class="fresh_stock_return">Fresh Stock Return</a></li>
    <li><a data-toggle="tab" href="#menu2" class="payment_reciept" >Payment Reciept</a></li>
    <li><a data-toggle="tab" href="#menu3" class="expense_details" >Expense Details</a></li>
  </ul>
        <div>
            <div class="" id="crate_details" >
            </div> 
          <?php echo form_hidden('crate_details'); ?>
        </div>
          
        <div class="" id="fresh_stock_return" >
        </div>
         <?php echo form_hidden('fresh_stock_return'); ?>
         
        <div class="" id="payment_reciept" >
        </div>
        <?php echo form_hidden('payment_reciept'); ?>
        
        <div class="" id="expense_detail" >
        </div>
        <?php echo form_hidden('expense_detail'); ?>
          
        <!--<p class="bold p_style"><?php echo _l('Crate Details'); ?></p>-->
       
  <!--        <div class="tab-content">-->
  <!--  <div id="home" class="tab-pane fade in active">-->
      
  <!--  </div>-->
  <!--  <div id="menu1" class="tab-pane fade">-->
    
  <!--  </div>-->
  <!--  <div id="menu2" class="tab-pane fade">-->
  <!--    <div class="" id="payment_reciept">-->
  <!--       </div>-->
  <!--  </div>-->
  <!--  <div id="menu3" class="tab-pane fade">-->
  <!--   <div class="" id="expense_details">-->
  <!--       </div>-->
  <!--  </div>-->
  <!--</div>-->
         
        
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
   <div class="modal-dialog modal-xl" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"> Challan List</h4>
         </div>
         <div class="modal-body" style="padding:5px;">
             
            <div class="row">
               <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="from_date">
                        <label for="from_date" class="control-label">From</label>
                        <?php $form_date = '01/'.date('m/Y'); ?>
                        <div class="input-group date">
                            <input type="text" id="from_date1" name="from_date1" class="form-control datepicker" value="<?php echo $form_date;?>" >
                            <div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="to_date">
                        <label for="to_date" class="control-label">To</label>
                         <?php $to_date = date('d/m/Y'); ?>
                        <div class="input-group date">
                            <input type="text" id="to_date1" name="to_date1" class="form-control datepicker" value="<?php echo $to_date;?>">
                            <div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data">Search</button>
                </div>
                
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                
                <div class="col-md-12">
                 
            <div class="table_adj_report">
             
              <table class="tree table table-striped table-bordered table_adj_report" id="table_adj_report" width="100%">
                  
                <thead>
                    
                  <tr>
                             <th >Challan No.</th>
                             <th >Challan Date</th>
                             <th >VehRtnId</th>
                             <th style=" text-align:center;">Route</th>
                             <th style=" text-align:center;">VehicleNo</th>
                             <th style=" text-align:center;">DriverName</th>
                             <th style=" text-align:center;">LoaderName</th>
                             <th style=" text-align:center;">SalemsmanName</th>
                             <th style=" text-align:center;">Crates</th>
                             <th style=" text-align:center;">Cases</th>
                             <th style=" text-align:center;">ChallanAmt</th>
                             <th style=" text-align:center;">OtherVehicleDetails</th>
                            
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
<div class="modal fade" id="transfer-modal_return_list">
   <div class="modal-dialog modal-xl" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vehicle Return List</h4>
         </div>
         <div class="modal-body" style="padding:5px;">
             
            <div class="row">
                <?php $current_date = date('d/m/Y'); 
                $from_date = '01/'.date('m').'/'.date('Y');
                ?>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('from_date2','From',$from_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('to_date2','To',$current_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data_vehicle_return"><?php echo _l('rate_filter'); ?></button>
                </div>
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                <div class="col-md-12">
                 
            <div class="table_vehicle_return">
             
              <table class="tree table table-striped table-bordered table_vehicle_return" id="table_vehicle_return" width="100%">
                  
                <thead>
                    <tr style="display:none;">
                      <td colspan="8" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                            <th >Return No.</th>
                              <th >Return Date</th>
                            <th >Challan No.</th>
                             <th >Challan Date</th>
                             <th >VehRtnId</th>
                             <th style=" text-align:center;">Route</th>
                             <th style=" text-align:center;">DriverName</th>
                             <th style=" text-align:center;">LoaderName</th>
                             <th style=" text-align:center;">SalemsmanName</th>
                             <th style=" text-align:center;">Crates</th>
                             <th style=" text-align:center;">Cases</th>
                             <th style=" text-align:center;">ChallanAmt</th>
                             <th style=" text-align:center;">OtherVehicleDetails</th>
                          </tr>
                </thead>
                <tbody>
                </tbody>
              </table>   
            </div>
            <span id="searchh3" style="display:none;">
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

   .transfer-modal_return_list { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.transfer-modal_return_list thead th { position: sticky; top: 0; z-index: 1; }
.transfer-modal_return_list tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.transfer-modal_return_list table  { border-collapse: collapse; width: 100%; }
.transfer-modal_return_list th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.transfer-modal_return_list th     { background: #50607b;color: #fff !important; }


#transfer-modal_return_list tr:hover {
    background-color: #ccc;
}

#transfer-modal_return_list td:hover {
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

<?php $this->load->view('admin/vehicle_return/vehicle_return_js'); ?>
<script type="text/javascript" language="javascript" >

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
