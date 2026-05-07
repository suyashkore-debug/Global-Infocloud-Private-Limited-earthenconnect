<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
<div class="content">
    <?php
		echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form invoice-form'));
	?>
	<div class="row">
	    <div class="col-md-12">
	        <div class="panel_s">
                <div class="panel-body">
                    
                    
    		        <div class="row">
    		            <input type="hidden" name="countof_record" id="countof_record" value="1">
                        <div class="col-md-2">
                            <?php 
                            $to_date = date('d/m/Y');
                            $value = (isset($orderDetails) ? _d(substr($orderDetails->Transdate,0,10)) : $to_date); ?>
                            <?php echo render_date_input('order_date','Posting Date',$value,$attr); ?>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="PlantID">
                                <label for="PlantID" class="control-label">Plant</label>
                                <select name="PlantID" id="PlantID" readonly class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    
                                <?php 
                                    foreach($PlantList as $key=>$value){ ?>
                                        <option value="<?php echo $value['PlantID']; ?>"><?php echo $value['PlantName']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <input type="hidden" name="selected_state" id="selected_state" value="">
                            <div class="form-group" app-field-wrapper="CenterID">
                                <label for="CenterID" class="control-label">Center</label>
                                <select name="CenterID" id="CenterID" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                <?php 
                                    foreach($CenterList as $key=>$value){ ?>
                                        <option value="<?php echo $value['CenterID']; ?>"><?php echo $value['CenterName']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        
                        
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="WHID">
                                <label for="WHID" class="control-label">Warehouse</label>
                                <select name="WHID" id="WHID" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="BrokerID">
                                <label for="BrokerID" class="control-label">Broker</label>
                                <select name="BrokerID" id="BrokerID" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                <?php 
                                    foreach($AllBrokerList as $key=>$value){ ?>
                                        <option value="<?php echo $value['AccountID']; ?>"><?php echo $value['company']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="CenterID">
                                <lable><b>Business Type</b></lable><br>
                                <input type="radio" id="DOC" checked name="business_type" value="DOC">
                                <label for="DOC" style="font-weight:400 !important;">DOC</label>
                                <input type="radio" id="Retail" name="business_type" value="Retail">
                                <label for="Retail" style="font-weight:400 !important;">Retail</label>
                                <input type="radio" id="Loose" name="business_type" value="Loose">
                                <label for="Loose" style="font-weight:400 !important;">Loose</label>
                                <input type="radio" id="Other" name="business_type" value="Other">
                                <label for="Loose" style="font-weight:400 !important;">Other</label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="cash_credit">
							<small class="req text-danger">* </small>
                                <label for="cash_credit" class="control-label">Cash / Credit</label>
                                <select name="cash_credit" id="cash_credit" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                    <option value="CASH">Cash</option>
                                    <option value="CREDIT">Credit</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="PaymentTerm">
                                <label for="PaymentTerm" class="control-label">Payment Terms</label>
                                <select name="PaymentTerm" id="PaymentTerm" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                <?php 
                                    foreach($PaymentCycleList as $key=>$value){ ?>
                                        <option value="<?php echo $value['CycleID']; ?>"><?php echo $value['CycleName']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="TransporterID">
                                <label for="TransporterID" class="control-label">Transporter Name</label>
                                <select name="TransporterID" id="TransporterID" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                <option></option>
                                <?php 
                                    foreach($TransportList as $key=>$value){ ?>
                                        <option value="<?php echo $value['TransportID']; ?>"><?php echo $value['TransportName']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <div class="form-group" app-field-wrapper="VehicleNo">
                                <label for="VehicleNo">Vehicle No.</label>
                                <input type="text" name="VehicleNo" id="VehicleNo" class="form-control" value="<?php echo $GST_Aadhar; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-2" >
                            <div class="form-group" app-field-wrapper="LRNo">
                                <label for="LRNo">LR No.</label>
                                <input type="text" name="LRNo" id="LRNo" class="form-control" value="<?php echo $GST_Aadhar; ?>">
                            </div>
                        </div>
                        
                    </div> 
                    <div class="row" id="bill_to_div" style="display:none;">
                        <div class="col-md-2" >
                            
                            <?php $value = (isset($orderDetails) ? $orderDetails->AccountID : ''); ?>
                            <?php
                                if(isset($orderDetails)){
                            ?>
                                <input type="hidden" name="bill_toActID_hidden" id="bill_toActID_hidden" value="<?php echo $value; ?>">
                            <?php
                                }
                            ?>
                            <div class="form-group" app-field-wrapper="bill_to_ActID">
                                <small class="req text-danger">* </small>
                                <label for="bill_to_ActID">Bill to Customer ID</label>
                                <input type="text" name="bill_to_ActID" id="bill_to_ActID"  class="form-control" value="<?php echo $value; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4" >
                            <div class="form-group" app-field-wrapper="bill_to_ActName">
                                <small class="req text-danger">* </small>
                                <label for="bill_to_ActName">Bill to Customer Name</label>
                                <input type="text" name="bill_to_ActName" id="bill_to_ActName" readonly class="form-control" value="<?php echo $AccountName; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="Bill_to_State">
                                <label for="Bill_to_State" class="control-label">Bill to State</label>
                                <select name="Bill_to_State" id="Bill_to_State" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                <?php 
                                    foreach($StateList as $key=>$value){ ?>
                                        <option value="<?php echo $value['short_name']; ?>"><?php echo $value['state_name']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4" >
                            <div class="form-group" app-field-wrapper="Bill_to_address">
                                <label for="Bill_to_address">Bill to Address</label>
                                <input type="text" name="Bill_to_address" id="Bill_to_address" readonly class="form-control" value="<?php echo $AccountName; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="ship_to_div" style="display:none;">
                        <div class="col-md-2" >
                            
                            <?php $value = (isset($orderDetails) ? $orderDetails->AccountID : ''); ?>
                            <?php
                                if(isset($orderDetails)){
                            ?>
                                <input type="hidden" name="ship_toActID_hidden" id="ship_toActID_hidden" value="<?php echo $value; ?>">
                            <?php
                                }
                            ?>
                            <div class="form-group" app-field-wrapper="ship_to_ActID">
                                <label for="ship_to_ActID">Ship to Customer ID</label>
                                <input type="text" name="ship_to_ActID" id="ship_to_ActID"  class="form-control" value="<?php echo $value; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4" >
                            <div class="form-group" app-field-wrapper="ship_to_ActName">
                                <label for="ship_to_ActName">Ship to Customer Name</label>
                                <input type="text" name="ship_to_ActName" id="ship_to_ActName" readonly class="form-control" value="<?php echo $AccountName; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="ship_to_State">
                                <label for="ship_to_State" class="control-label">Ship to State</label>
                                <select name="ship_to_State" id="ship_to_State" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                <?php 
                                    foreach($StateList as $key=>$value){ ?>
                                        <option value="<?php echo $value['short_name']; ?>"><?php echo $value['state_name']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4" >
                            <div class="form-group" app-field-wrapper="ship_to_address">
                                <label for="ship_to_address">Ship to Address</label>
                                <input type="text" name="ship_to_address" id="ship_to_address" readonly class="form-control" value="<?php echo $AccountName; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="Cash_to_div" style="display:none;">
                        <div class="col-md-2" >
                            
                            <?php $value = (isset($orderDetails) ? $orderDetails->AccountID : ''); ?>
                            <?php
                                if(isset($orderDetails)){
                            ?>
                                <input type="hidden" name="CustID_hidden" id="CustID_hidden" value="<?php echo $value; ?>">
                            <?php
                                }
                            ?>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
                                <label for="CustID">Customer ID</label>
                                <input type="text" name="CustID" id="CustID" class="form-control" value="<?php echo $value; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4" >
                            <small class="req text-danger">* </small>
                            <label for="CustName">Customer Name</label>
                            <input type="text" name="CustName" id="CustName" class="form-control" value="<?php echo $AccountName; ?>">
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" app-field-wrapper="CustState">
                                <small class="req text-danger">* </small>
                                <label for="CustState" class="control-label">Customer State</label>
                                <select name="CustState" id="CustState" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                    <option value=""></option>
                                <?php 
                                    foreach($StateList as $key=>$value){ ?>
                                        <option value="<?php echo $value['short_name']; ?>"><?php echo $value['state_name']; ?></option>
                                <?php 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4" >
                            <div class="form-group" app-field-wrapper="CustAddress">
                                <label for="CustAddress">Customer Address</label>
                                <input type="text" name="CustAddress" id="CustAddress" class="form-control" value="<?php echo $AccountName; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	    </div>
	</div>
	
	<div class="row">
	    <div class="col-md-12">
	        <div class="panel_s">
                <div class="panel-body">
                    <p class="bold p_style">Item Details</p>
                    <hr class="hr_style"/>
                    <div class="" id="example">
                    </div>
                    <?php echo form_hidden('sale_invoice_detail'); ?>
                    
                    <div class="col-md-12 ">
            <table class="table">
               <tbody>
                    <tr id="total_td">
                        <td>
                            <label for="total_qty_in_mt">Qty(MT)</label> 
                            <input type="text" readonly class="form-control pull-left text-right" name="total_qty_in_mt" id="total_qty_in_mt" value="0.00">
                        </td>
                        <td>
                            <label  for="Total_value">Total Value</label>  
                            <input  type="text" readonly class="form-control pull-left text-right" name="Total_value" id="Total_value"  value="0.00" >
                        </td>
                        
                        <td>  
                            <label  for="total_cgst_amt">Total CGST Amt</label>  
                            <input type="text" readonly value="0.00" class="form-control pull-left text-right"  name="total_cgst_amt">
                        </td>
                        <td>  
                            <label  for="total_sgst_amt">Total SGST Amt</label>
                            <input type="text" readonly value="0.00" class="form-control pull-left text-right" name="total_sgst_amt" id="total_sgst_amt">
                        </td>
                        <td>  
                            <label  for="total_igst_amt">Total IGST Amt</label> 
                            <input type="text" readonly value="0.00" class="form-control pull-left text-right" name="total_igst_amt">
                        </td>
                        
                        <td>  
                            <label  for="total_net_value">Total NetValue</label>  
                            <input type="text" readonly value="0.00" class="form-control pull-left text-right" name="total_net_value">
                        </td>
                    </tr>
                    
                    

               </tbody>
            </table>
         </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
          <div class="col-md-12 mtop15">
             <div class="panel-body bottom-transaction">
                
                <div id="vendor_data">
                  
                </div>

                <div class="btn-bottom-toolbar text-right" style="width: 100%;">
                  <?php if (has_permission_new('mandi_purchase_order', '', 'create')){
                      ?>
                
                  <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                  <?php echo _l('submit'); ?>
                  </button>
                  <?php
                  }?>
                </div>
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>
        </div>
	
	
	<?php echo form_close(); ?>
	
</div>
</div>
<?php init_tail(); ?>

<script>
    $( "#cash_credit" ).on('change', function () {
        var value = $('#cash_credit').val();
        if(value =="CASH"){
            $("#Cash_to_div").css("display", "block");
            $("#ship_to_div").css("display", "none");
            $("#bill_to_div").css("display", "none");
        }else if(value =="CREDIT"){
            $("#Cash_to_div").css("display", "none");
            $("#ship_to_div").css("display", "block");
            $("#bill_to_div").css("display", "block");
        }else{
            $("#Cash_to_div").css("display", "none");
            $("#ship_to_div").css("display", "none");
            $("#bill_to_div").css("display", "none");
        }
    });
    
    $('#Bill_to_State').on('change', function() {
        var StateCode = $(this).val();
        $('#selected_state').val(StateCode);
    })
    
    $('#CustState').on('change', function() {
        var StateCode = $(this).val();
        $('#selected_state').val(StateCode);
    })
    
    
    $('#CenterID').on('change', function() {
		var CenterID = $(this).val();
		var url = "<?php echo base_url(); ?>admin/order/GetWHListByCenterID";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {CenterID: CenterID},
            dataType:'json',
            success: function(data) {
                $("#WHID").find('option').remove();
                $("#WHID").selectpicker("refresh");
                $("#WHID").append(new Option('', 'select center'));
                for (var i = 0; i < data.length; i++) {
                    $("#WHID").append(new Option(data[i].w_name, data[i].AccountID));
                }
                $('.selectpicker').selectpicker('refresh');
            }
        });
	});
</script>
<!-- Cash Sale Javascript -->
<script>
    $('#CustID').on('blur', function () {
        var AccountID = $(this).val();
        if(empty(AccountID)){
            
        }else{
            $.ajax({
                url: "<?=base_url()?>admin/Cd_notes/GetAccountDetailsByID",
                type: 'post',
                dataType: "json",
                data: {
                  AccountID: AccountID,
                },
                success: function( data ) {
                    if(empty(data)){
                        /*alert('AccountID not found.');
                        $("#act_name").val('');
                        $("#act_name").focus();*/
                    }else{
                        $('#CustName').val(data.AccountName); // display the selected text
                        $('#CustState').val(data.state_short_code); // display the selected text
                        $('.selectpicker').selectpicker('refresh');
                        $('#selected_state').val(data.state_short_code);
                        //$('#gst_no').val(data.GST_Aadhar); // display the selected text
                        $('#CustAddress').val(data.address); // display the selected text
                        //GetSaleItemCenterWiseStaffWise(CenterID);
                        //$("#item_code").focus();
                    }
                }
            });
        }
    })
</script>

<script>
    // For Bill to Accounts
    // Initialize For Account
    $( "#bill_to_ActID" ).autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "<?=base_url()?>admin/Cd_notes/accountlist",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
          var old_AccountID = $('#old_act_name').val();
            if(empty(old_AccountID)){
                $('#bill_to_ActID').val(ui.item.value);
                $('#bill_to_ActName').val(ui.item.label); 
                $("#item_code").focus();
                return false; 
            }else{
              $('#act_name').val(ui.item.value);
                return false
            }
        }
    });
    
    $('#bill_to_ActID').on('blur', function () {
        var AccountID = $(this).val();
        var CenterID = $('#CenterID').val();
        if(empty(AccountID)){
            
        }else{
            $.ajax({
                url: "<?=base_url()?>admin/Cd_notes/GetAccountDetailsByID",
                type: 'post',
                dataType: "json",
                data: {
                  AccountID: AccountID,
                },
                success: function( data ) {
                    if(empty(data)){
                        alert('Account not found.');
                        $("#bill_to_ActID").val('');
                        $("#bill_to_ActID").focus();
                    }else{
                        $('#bill_to_ActName').val(data.AccountName); // display the selected text
                        $('#Bill_to_State').val(data.state_short_code); // display the selected text
                        $('.selectpicker').selectpicker('refresh');
                        $('#selected_state').val(data.state_short_code);
                        $('#Bill_to_address').val(data.address); // display the selected text
                        //GetSaleItemCenterWiseStaffWise(CenterID);
                        $("#item_code").focus();
                    }
                }
            });
        }
    }); 
    
    // for Ship to accounts
    // Initialize For Account
    $( "#ship_to_ActID" ).autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "<?=base_url()?>admin/Cd_notes/accountlist",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
            $('#ship_to_ActID').val(ui.item.value);
            $('#ship_to_ActName').val(ui.item.label); 
            $("#item_code").focus();
            return false; 
        }
    });
    
    $('#ship_to_ActID').on('blur', function () {
        var AccountID = $(this).val();
        var CenterID = $('#CenterID').val();
        if(empty(AccountID)){
            
        }else{
            $.ajax({
                url: "<?=base_url()?>admin/Cd_notes/GetAccountDetailsByID",
                type: 'post',
                dataType: "json",
                data: {
                  AccountID: AccountID,
                },
                success: function( data ) {
                    if(empty(data)){
                        alert('Account not found.');
                        $("#ship_to_ActID").val('');
                        $("#ship_to_ActID").focus();
                    }else{
                        $('#ship_to_ActName').val(data.AccountName); // display the selected text
                        $('#ship_to_State').val(data.state_short_code); // display the selected text
                        $('.selectpicker').selectpicker('refresh');
                        $('#ship_to_address').val(data.address); // display the selected text
                        //GetSaleItemCenterWiseStaffWise(CenterID);
                        $("#item_code").focus();
                    }
                }
            });
        }
    }); 
    
    
     // Initialize For ItemID
    $( "#item_code" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
        var cash_credit = $("#cash_credit").val();
            if(cash_credit == "Cash"){
                $State = $("#CustState").val();
            }else if(cash_credit == "Credit"){
                $State = $("#Bill_to_State").val();
            }else{
                $State = '';
            }
          var CenterID = $("#CenterID").val();
          var type_select = $("#type_select2").val();
            if(CenterID){
                if($State){
                    $.ajax({
                        url: "<?=base_url()?>admin/order/itemlist",
                        type: 'post',
                        dataType: "json",
                        data: {
                          search: request.term,
                          CenterID:CenterID,
                        },
                        success: function( data ) {
                          response( data );
                        }
                    });
                }else{
                    alert("please select State first...");
                }
            }else{
                alert("please select Center first...");
                $("#CenterID").focus();
            }
        },
        select: function (event, ui) {
            if(ui.item.hsn_code == ""){
                alert('HSN Code not assigned to this Item');
                $('#item_code').val(''); 
                $("#item_code").focus();
                return false;
            }else{
                $('#item_code').val(ui.item.value); 
                $('#item_name').html(ui.item.label);
                $('#item_name_val').val(ui.item.label);
                $('#hsn').html(ui.item.hsn_code); 
                $('#hsn_val').val(ui.item.hsn_code);
                $('#gstper').html(ui.item.tax); // display the selected text
                $('#gstper_val').val(ui.item.tax);
                $('#unit').html(ui.item.unit); // display the selected text
                $('#unit_val').val(ui.item.unit);
                $('#rate_val').val('1');
                $('#qty_val').val(1);
                $("#rate_val").focus();
                return false;
            }
        }
    });
    
    $('#item_code').on('blur', function () {
        var ItemID = $(this).val();
        var cash_credit = $("#cash_credit").val();
        if(cash_credit == "Cash"){
            $State = $("#CustState").val();
        }else if(cash_credit == "Credit"){
            $State = $("#Bill_to_State").val();
        }else{
            $State = '';
        }
        if(empty(ItemID)){
            alert("please select Center first...");
            $("#CenterID").focus();
        }else{
            if($State){
                $.ajax({
                    url: "<?=base_url()?>admin/order/GetItemDetails",
                    type: 'post',
                    dataType: "json",
                    data: {
                      ItemID: ItemID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('Account not found.');
                            $("#item_code").val('');
                            $('#item_name').html('');
                            $('#item_name_val').val('');
                            $('#hsn').html(''); 
                            $('#hsn_val').val('');
                            $('#gstper').html(''); // display the selected text
                            $('#gstper_val').val('');
                            $('#unit').html(''); // display the selected text
                            $('#unit_val').val('');
                            $('#rate_val').val('1');
                            $('#qty_val').val(1);
                            $("#item_code").focus();
                        }else{
                            $('#item_code').val(data.ItemID); 
                            $('#item_name').html(data.ItemName);
                            $('#item_name_val').val(data.ItemName);
                            $('#hsn').html(data.hsn_code); 
                            $('#hsn_val').val(data.hsn_code);
                            $('#gstper').html(data.taxrate); // display the selected text
                            $('#gstper_val').val(data.taxrate);
                            $('#unit').html(data.unit); // display the selected text
                            $('#unit_val').val(data.unit);
                            $('#rate_val').val('1');
                            $('#qty_val').val(1);
                            $("#rate_val").focus();
                        }
                    }
                });
            }else{
                alert("please select State first...");
            }
        }
    }); 
    $('#item_code').on('focus', function () {
        $("#item_code").val('');
        $('#item_name').html('');
        $('#item_name_val').val('');
        $('#hsn').html(''); 
        $('#hsn_val').val('');
        $('#gstper').html(''); 
        $('#gstper_val').val('');
        $('#unit').html(''); 
        $('#unit_val').val('');
        $('#rate_val').val('1');
        $('#qty_val').val(1);
    })
    
    $('#rate_val').on('keyup', function () {
        var rate_val = $('#rate_val').val();
        var qty_val = $('#qty_val').val();
        var gstper_val = $('#gstper_val').val();
        rate_val = parseFloat(rate_val);
        qty_val = parseFloat(qty_val);
        gstper_val = parseFloat(gstper_val);
        if(isNaN(rate_val) || isNaN(qty_val)){
            var saleAmt = 0;
            $("#sale_amount_val").val(parseFloat(saleAmt));
            $("#sale_amount").html(parseFloat(saleAmt));
            var gstAmt = (parseFloat(saleAmt) * parseFloat(gstper_val)/100);
            $("#gst_amt_val").val(parseFloat(gstAmt));
            $("#gstamt").html(parseFloat(gstAmt));
            var NetAmt = parseFloat(saleAmt) + parseFloat(gstAmt);
            $("#total_amt_val").val(parseFloat(NetAmt));
            $("#amount").html(parseFloat(NetAmt));
        }else{
            var cash_credit = $("#cash_credit").val();
            if(cash_credit == "Cash"){
                $State = $("#CustState").val();
            }else if(cash_credit == "Credit"){
                $State = $("#Bill_to_State").val();
            }else{
                $State = '';
            }
            var saleAmt = qty_val *  rate_val;
            $("#sale_amount_val").val(parseFloat(saleAmt));
            $("#sale_amount").html(parseFloat(saleAmt));
            var gstAmt = (parseFloat(saleAmt) * parseFloat(gstper_val)/100);
            $("#gst_amt_val").val(parseFloat(gstAmt));
            $("#gstamt").html(parseFloat(gstAmt));
            var NetAmt = parseFloat(saleAmt) + parseFloat(gstAmt);
            $("#total_amt_val").val(parseFloat(NetAmt));
            $("#amount").html(parseFloat(NetAmt));
            
        }
        //add_row();
        
            /**/
    })
    $('#add').on('click', function () {
        var item_code =document.getElementById("item_code").value;
         var item_name =document.getElementById("item_name_val").value;
         var hsn_val =document.getElementById("hsn_val").value;
         var unit_val =document.getElementById("unit_val").value;
         var rate_val =document.getElementById("rate_val").value;
         var qty_val =document.getElementById("qty_val").value;
         var gst_amt_val =document.getElementById("gst_amt_val").value;
         var gstper_val =document.getElementById("gstper_val").value;
         var total_amt_val =document.getElementById("total_amt_val").value;
         var sale_amount_val =document.getElementById("sale_amount_val").value;
     
     var countof_record = document.getElementById("countof_record").value;
        var cash_credit = $("#cash_credit").val();
        if(cash_credit == "Cash"){
            $State = $("#CustState").val();
        }else if(cash_credit == "Credit"){
            $State = $("#Bill_to_State").val();
        }else{
            $State = '';
        }
            
    	if(item_name !=="" && item_name !==null){
    	    var table=document.getElementById("table_cd_report");
            var table_len=(table.rows.length)-1;
            var html = '';
            html += "<tr id='row"+table_len+"'>";
            html += "<td id='item_code"+table_len+"'>"+item_code+" <input type='hidden' name='item_code"+table_len+"' value='"+item_code+"'></td>";
            html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name_val"+table_len+"' value='"+item_name+"'></td>";
            html += "<td id='hsn"+table_len+"'>"+hsn_val+" <input type='hidden' name='hsn_val"+table_len+"' value='"+hsn_val+"'></td>";
            html += "<td id='unit"+table_len+"'>"+unit_val+" <input type='hidden' name='unit_val"+table_len+"' value='"+unit_val+"'></td>";
            html += "<td id='rate"+table_len+"'>"+rate_val+" <input type='hidden' name='rate_val"+table_len+"' value='"+rate_val+"'></td>";
            html += "<td id='qty"+table_len+"' align='right'>"+qty_val+" <input type='hidden' name='qty_val"+table_len+"' value='"+qty_val+"'></td>";
            html += "<td id='sale_amount"+table_len+"' align='right'>"+sale_amount_val+" <input type='hidden' name='sale_amount_val"+table_len+"' value='"+sale_amount_val+"'></td>";
            html += "<td id='gstper"+table_len+"' align='right'>"+gstper_val+" <input type='hidden' name='gstper_val"+table_len+"' value='"+gstper_val+"'></td>";
            html += "<td id='gstamt"+table_len+"' align='right'>"+gst_amt_val+" <input type='hidden' name='gstamt_val"+table_len+"' value='"+gst_amt_val+"'></td>";
            html += "<td id='amount"+table_len+"' align='right'>"+total_amt_val+" <input type='hidden' name='total_amt_val"+table_len+"' value='"+total_amt_val+"'></td>";
            html += '<td><button type="button" name="edit" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum" id="rownum" value="'+countof_record+'"></td>';
             
            html += '</tr>';
            var row = table.insertRow(table_len).outerHTML=html;
            
            var countof_record = document.getElementById("countof_record").value;
            var temp1 = parseFloat(countof_record) + parseFloat(1);
     
            document.getElementById("countof_record").value=temp1;
            var new_rec = '';
            new_rec = $('#new_record').val();
            new_rec = new_rec +","+ item_code
            $('#new_record').val(new_rec);
        
     
            document.getElementById("item_code").value="";
            document.getElementById("item_name_val").value="";
            document.getElementById("hsn_val").value="";
            document.getElementById("unit_val").value="";
            document.getElementById("rate_val").value="1.00";
            document.getElementById("qty_val").value="1.00";
            document.getElementById("sale_amount_val").value="";
            document.getElementById("gstper_val").value="";
            document.getElementById("gst_amt_val").value="";
            document.getElementById("total_amt_val").value="";
            
            
            document.getElementById("item_name").innerHTML="";
            document.getElementById("hsn").innerHTML="";
            document.getElementById("unit").innerHTML="";
            document.getElementById("rate").innerHTML="";
            document.getElementById("qty").innerHTML="";
            document.getElementById("sale_amount").innerHTML="";
            document.getElementById("gstper").innerHTML="";
            document.getElementById("gstamt").innerHTML="";
            document.getElementById("amount").innerHTML="";
         
            document.getElementById("item_code").focus();
            
            var gross_total = $("#gross_total_val").val();
            var cgst_total = $("#cgst_total_val").val();
            var sgst_total = $("#sgst_total_val").val();
            var igst_total = $("#igst_total_val").val();
            var net_total = $("#net_total_val").val();
            
            gross_total = parseFloat(gross_total) + parseFloat(sale_amount_val);
            $('#gross_total').html(parseFloat(gross_total).toFixed(2));
            $('#gross_total_val').val(parseFloat(gross_total).toFixed(2));
          
            var new_net_total = parseFloat(net_total) + parseFloat(total_amt_val);
            $('#net_total').html(parseFloat(new_net_total).toFixed(2));
            $('#net_total_val').val(parseFloat(new_net_total).toFixed(2));
                  
            if($State == "MH"){
                var cgst_total_new = parseFloat(cgst_total) + parseFloat(gst_amt_val) / 2;
                $('#cgst_total').html(parseFloat(cgst_total_new).toFixed(2));
                $('#cgst_total_val').val(parseFloat(cgst_total_new).toFixed(2));
                var sgst_total_new = parseFloat(sgst_total) + parseFloat(gst_amt_val) / 2;        
                $('#sgst_total').html(parseFloat(sgst_total_new).toFixed(2));
                $('#sgst_total_val').val(parseFloat(sgst_total_new).toFixed(2));
                $('#igstamt').html("0.00");
                $('#igst_amt_val').val("0.00");
            }else{
                var igst_total_new = parseFloat(igst_total) + parseFloat(gst_amt_val) / 2;
                $('#igstamt').html(parseFloat(igst_total_new).toFixed(2));
                $('#igst_amt_val').val(parseFloat(igst_total_new).toFixed(2));
                
                $('#cgstamt').html("0.00");
                $('#cgst_amt_val').val("0.00");
                $('#sgstamt').html("0.00");
                $('#sgst_amt_val').val("0.00");
            }
            
    	}else{
    	    alert("please add proper item..");
    	    document.getElementById("item_code").focus();
    	}
    });
    
    $('#tbody').on('click', '.remove', function () {
  
        var child = $(this).closest('tr').nextAll();
      
        child.each(function () {
              
            // Getting <tr> id.
            var id = $(this).attr('id');
      
            // Getting the <p> inside the .row-index class.
            var idx = $(this).children('.row-index').children('p');
      
            // Gets the row number from <tr> id.
            var dig = parseInt(id.substring(1));
      
            // Modifying row index.
            idx.html(`Row ${dig - 1}`);
      
            // Modifying row id.
            $(this).attr('id', `R${dig - 1}`);
        });
        
        var  no = $(this).parents("tr").find('input[name="rownum"]').val();
        var item_code =$(this).parents("tr").find('input[name="item_code'+no+'"]').val();
        
        var gross_total = $("#gross_total_val").val();
        var cgst_total = $("#cgst_total_val").val();
        var sgst_total = $("#sgst_total_val").val();
        var igst_total = $("#igst_total_val").val();
        var net_total = $("#net_total_val").val();
        
        var total_amt = $(this).parents("tr").find('input[name="total_amt_val'+no+'"]').val();
        var total_taxable = $(this).parents("tr").find('input[name="sale_amount_val'+no+'"]').val();
        
        var cash_credit = $("#cash_credit").val();
        if(cash_credit == "Cash"){
            $State = $("#CustState").val();
        }else if(cash_credit == "Credit"){
            $State = $("#Bill_to_State").val();
        }else{
            $State = '';
        }
        
        if($State =="MH"){
            var gst_amt =$(this).parents("tr").find('input[name="gstamt_val'+no+'"]').val();
            var cgst_amt = gst_amt / 2;
            
            sgst_total = parseFloat(sgst_total) - parseFloat(cgst_amt);
            $('#sgst_total').html(parseFloat(sgst_total).toFixed(2));
            $('#sgst_total_val').val(parseFloat(sgst_total).toFixed(2));
                    
            cgst_total = parseFloat(cgst_total) - parseFloat(cgst_amt);
            $('#cgst_total').html(parseFloat(cgst_total).toFixed(2));
            $('#cgst_total_val').val(parseFloat(cgst_total).toFixed(2));
        }else{
            var igst_amt =$(this).parents("tr").find('input[name="igst_amt_val'+no+'"]').val();
            igst_total = parseFloat(igst_total) - parseFloat(igst_amt);
            $('#igst_total').html(parseFloat(igst_total).toFixed(2));
            $('#igst_total_val').val(parseFloat(igst_total).toFixed(2));
        }
        
        
        var new_gross = parseFloat(gross_total) - parseFloat(total_taxable);
        $('#gross_total').html(parseFloat(new_gross).toFixed(2));
        $('#gross_total_val').val(parseFloat(new_gross).toFixed(2));
        
        net_total = parseFloat(net_total) - parseFloat(total_amt).toFixed(2);
        $('#net_total').html(parseFloat(net_total).toFixed(2));
        $('#net_total_val').val(parseFloat(net_total).toFixed(2));
      
        // Removing the current row.
        $(this).closest('tr').remove();
      
        // Decreasing the total number of rows by 1.
        rowIdx--;
        
        var countof_record = $("#countof_record").val();
        var new_cont = countof_record -1;
        $("#countof_record").val(new_cont);
        if(new_cont == 1){
            $("#tax_id").val('');
        }
        var new_rec = $('#new_record').val();
        $new_item_code = ','+item_code;
        new_rec = new_rec.replace($new_item_code, " ");
        $('#new_record').val(new_rec);
    });

   
</script>
<?php require 'ManageNew_js.php';?>