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
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " app-field-wrapper="TransID">
                                    <label for="TransID" class="control-label">SalesID</label>
                                    <input type="text" name="TransID" id="TransID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="ChallanID"  class="control-label">ChallanID</label>
                                    <input type="text" readonly="" name="ChallanID"  id="ChallanID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <?php $edate = _d(date('Y-m-d'));
                                $attr = array(
                                    'disabled'=>true
                                    );
                                    echo render_date_input('e_date','Date',$edate,$attr); ?>
                            </div>
                            <div class="clearfix"></div>
                            
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="RouteID" class="control-label">RouteID</label>
                                    <input type="text" readonly="" name="RouteID"  id="RouteID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group " style="margin-top: 19px;">
                                    <input type="text" readonly="" name="RouteName"  id="RouteName" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group " style="margin-top: 19px;">
                                    <input type="text" readonly="" name="RouteKm"  id="RouteKm" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            
                        </div>   
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="vehCapacity" class="control-label">Veh Capacity</label>
                                    <input type="text" readonly="" name="vehCapacity"  id="vehCapacity" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="ChallanValue" class="control-label">Challan Value</label>
                                    <input type="text" readonly="" name="ChallanValue"  id="ChallanValue" class="form-control "  value="">
                                </div>
                            </div>
                            
                        </div>   
                    </div>
                    
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="TotalCases" class="control-label">Total Cases</label>
                                    <input type="text" readonly="" name="TotalCases"  id="TotalCases" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="TotalCrates" class="control-label">Total Crates</label>
                                    <input type="text" readonly="" name="TotalCrates"  id="TotalCrates" class="form-control "  value="">
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="VehicleID" class="control-label">Vehicle</label>
                                    <input type="text" readonly="" name="VehicleID"  id="VehicleID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group " style="margin-top: 19px;">
                                    <input type="text" readonly="" name="VehicleName"  id="VehicleName" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="DriverID" class="control-label">Driver</label>
                                    <input type="text" readonly="" name="DriverID"  id="DriverID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group " style="margin-top: 19px;">
                                    <input type="text" readonly="" name="DriverName"  id="DriverName" class="form-control "  value="">
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                            
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="LoaderID" class="control-label">Loader</label>
                                    <input type="text" readonly="" name="LoaderID"  id="LoaderID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group " style="margin-top: 19px;">
                                    <input type="text" readonly="" name="LoaderName"  id="LoaderName" class="form-control "  value="">
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="SalesManID" class="control-label">SalesMan</label>
                                    <input type="text" readonly="" name="SalesManID"  id="SalesManID" class="form-control "  value="">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group " style="margin-top: 19px;">
                                    <input type="text" readonly="" name="SalesManName"  id="SalesManName" class="form-control "  value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <br>
                        <input  type="submit" name="create_json" class="btn btn-info pull-left mleft5 create_json" id="create_json" value="Create JSON">
                        <!--<button type= "submit" class="btn btn-info pull-left mleft5 create_json" id="create_json">Create JSON</button>
                    -->
                    </div>
                    <?php echo form_close(); ?>
                    <?php
                     echo form_open($this->uri->uri_string().'/process_excel',array('id'=>'JSON_res_form','enctype'=>'multipart/form-data','class'=>'JSON_res_form'));
      
                    ?>
                    <div class="col-md-4">
                        <br>
                        <input type="hidden" name="TransID2" id="TransID2">
                        <input type="file" name="res_file" id="res_file" class="form-control" accept=".xlsx">
                    </div>
                    <div class="col-md-2">
                        <br>
                        <input  type="submit" class="btn btn-warning pull-left mleft5 process_excel" name="process_excel" id="process_excel" value="Process Excel">
                    </div>
                    <?php echo form_close(); ?>
                    </div>
                
            </div>
            </div>
        </div>
        <div class="panel-body mtop10">
            <div class="row col-md-12">
                
                <div id="showmsg">
                    
                </div>
                <div id="json_table_div" class="json_table_div">
                    
                </div>
                
            </div>
        </div>
        
        <!--<div class="row">
          <div class="col-md-12 mtop15">

                <div id="vendor_data">
                  
                </div>

                <div class="btn-bottom-toolbar text-right" style="width: 100%;">
                <?php
                if (has_permission_new('vehicle_return', '', 'create')) {  
                ?>
                  <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                  <?php echo _l('submit'); ?>
                  </button>
                <?php } ?>
                </div>
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>-->
        </div>
        </div>

      </div>
      
      
    </div>
  </div>
</div>
</div>
<div class="modal fade" id="transfer-modal">
   <div class="modal-dialog modal-xl" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">SalesMaster</h4>
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
                    <!--<br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">-->
                </div>
                
                <div class="col-md-12">
                 
            <div class="table_sales_list">
             
              <table class="tree table table-striped table-bordered table_sales_list" id="table_sales_list" width="100%">
                  
                <thead>
                    
                    <tr>
                        <th style=" text-align:center;">SalesID</th>
                        <th style=" text-align:center;">SalesDate</th>
                        <th style=" text-align:center;">ChallanID</th>
                        <th style=" text-align:center;">AccountName</th>
                        <th style=" text-align:center;">Address</th>
                        <th style=" text-align:center;">SaleAmt</th>
                        <th style=" text-align:center;">DiscAmt</th>
                        <th style=" text-align:center;">GstAmt</th>
                        <th style=" text-align:center;">TcsAmt</th>
                        <th style=" text-align:center;">BillAmt</th>
                        <th style=" text-align:center;">Itm</th><!--
                        <th style=" text-align:center;">QR</th>-->
                        <th style=" text-align:center;">IRN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
            if(count($Translist) > 0 ){
            
        
         foreach($Translist as $value){
          
        ?>
        <tr class="get_challan_id" data-id="<?php echo $value["ChallanID"]; ?>" data-name="<?php echo $value["SalesID"]; ?>">
            <td ><?php echo $value["SalesID"]; ?></td>
            <td ><?php echo  _d(substr($value["Transdate"],0,10)); ?></td>
            <td ><?php echo $value["ChallanID"]; ?></td>
            <td ><?php echo $value["company"]; ?></td>
            <td><?php echo substr($value["address"],0,20).'...'; ?></td>
            <td style="text-align:right;"><?php echo $value["SaleAmt"]; ?></td>
            <td style="text-align:right;"><?php echo $value["DiscAmt"]; ?></td>
            <?php
            $gst = 0.00;
            if($value["igstamt"]=="0.00"){
                $gst = $value["sgstamt"] + $value["cgstamt"];
            }else{
                $gst = $value["igstamt"];
            }
            ?>
            <td style="text-align:right;"><?php echo $gst; ?></td> 
            <td style="text-align:right;"><?php echo $value["tcsAmt"]; ?></td>
            <td style="text-align:right;"><?php echo $value["BillAmt"]; ?></td>
            <td style="text-align:center;"><?php echo $value["ItCount"]; ?></td>
            <!--<td ><?php echo $value["Qrcode"]; ?></td> -->
            <td><?php echo substr($value["irn"],0,10).'...'; ?></td>
        </tr>
    <?php
       } 
        }else{
    ?>
        <tr>
            <td colspan="13"><span style="color:red;">No data found..</span></td>
        </tr>
    <?php
    }
    ?>
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
.table_sales_list { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_sales_list thead th { position: sticky; top: 0; z-index: 1; }
.table_sales_list tbody th { position: sticky; left: 0; }


*/
#table_sales_list tr:hover {
    background-color: #ccc;
}

#table_sales_list td:hover {
    cursor: pointer;
}

.table_vehicle_return { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_vehicle_return thead th { position: sticky; top: 0; z-index: 1; }
.table_vehicle_return tbody th { position: sticky; left: 0; }


.fixed_header1 { overflow: auto;max-height: 50vh;width:100%;position:relative;top: 0px; }
.fixed_header1 thead th { position: sticky; top: 0; z-index: 1; }
.fixed_header1 tbody th { position: sticky; left: 0; }

.json_table_div { overflow: auto;max-height: 50vh;width:100%;position:relative;top: 0px; }
.json_table_div thead th { position: sticky; top: 0; z-index: 1; }
.json_table_div tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 2px 3px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;color: #fff !important; }


#table_vehicle_return tr:hover {
    background-color: #ccc;
}

#table_vehicle_return td:hover {
    cursor: pointer;
}
</style>

<?php init_tail(); ?>

</body>
/*<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.js" integrity="sha512-is1ls2rgwpFZyixqKFEExPHVUUL+pPkBEPw47s/6NDQ4n1m6T/ySeDW3p54jp45z2EJ0RSOgilqee1WhtelXfA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
*/<style>
    table.dataTable tbody td {
    padding: 4px 4px !important;
    font-size: 11px;
}
</style>
</html>

<?php $this->load->view('admin/E-invoice/manage_js'); ?>
<script type="text/javascript" language="javascript" >

    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_sales_list");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    td1 = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[2];
    td3 = tr[i].getElementsByTagName("td")[3];
    td4 = tr[i].getElementsByTagName("td")[4];
    td5 = tr[i].getElementsByTagName("td")[5];
    td6 = tr[i].getElementsByTagName("td")[6];
    td7 = tr[i].getElementsByTagName("td")[7];
    td8 = tr[i].getElementsByTagName("td")[8];
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
      } else if(td3){
         txtValue = td3.textContent || td3.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td4){
         txtValue = td4.textContent || td4.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td7){
         txtValue = td7.textContent || td7.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td5){
         txtValue = td5.textContent || td5.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td6){
         txtValue = td6.textContent || td6.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td8){
         txtValue = td8.textContent || td8.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }}}}}}}}}
    }       
  }
}
</script>


