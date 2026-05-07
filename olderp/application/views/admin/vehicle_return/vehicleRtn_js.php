<script>

$(function(){
  "use strict";
		validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        appValidateForm($(selector), {
            from_date: {
				remote: {
					url: site_url + "admin/misc/checkvehicle_rtn_val",
					type: 'post',
					data: {
						from_date: function() {
							return $('input[name="from_date"]').val();
						},
						vehRtnID: function() {
							return $('input[name="vehRtnID"]').val();
						}
					}
				}
			},
			VrtnDate: {
				remote: {
					url: site_url + "admin/misc/checkvehicle_rtn_val",
					type: 'post',
					data: {
						from_date: function() {
							return $('input[name="VrtnDate"]').val();
						},
						vehRtnID: function() {
							return $('input[name="ex_vehicle_return_id"]').val();
						}
					}
				}
			},
            challan_n: 'required',
        });
    }


});
$(document).ready(function(){
    $('#crate_details').show();
    $('#fresh_stock_return').hide(); 
    $('#payment_reciept').hide();
    $('#expense_detail').hide();
    var data = '';
    //myFunction_for_crate_d(data);
    
    $('.edit-vehicle_return').on('click', function(){
        $('#transfer-modal_return_list').find('button[type="submit"]').prop('disabled', false);
          $('#transfer-modal_return_list').modal('show');
          $('#transfer-modal_return_list').on('shown.bs.modal', function () {
                  $('#myInput2').focus();
            })
    });
     $("#search_data_vehicle_return").click(function(){  
        
        var from_date = $('#from_date2').val();
        var to_date = $('#to_date2').val();
     
     $.ajax({
      url:"<?php echo admin_url(); ?>Vehicle_return/vehicle_return_model",
      dataType:"html",
      method:"POST",
      data:{from_date:from_date,to_date:to_date},
      beforeSend: function () {
        
        $('#searchh3').css('display','block');
        $('.table_vehicle_return tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table_vehicle_return tbody').css('display','');
        $('#searchh3').css('display','none');
     },
      success:function(data){
         $('#table_vehicle_return tbody').html(data);
            $('.get_challan_id').on('click',function(){ 
              challan_id = $(this).attr("data-id");
            
             
              $('#transfer-modal').modal('hide');
        
            });
        }
     });
   });
   
    $(".crate_details").click(function(){
        $('#crate_details').show();
        $('#fresh_stock_return').hide(); 
         $('#payment_reciept').hide();
        $('#expense_detail').hide();
    })
    $(".fresh_stock_return").click(function(){
        $('#crate_details').hide();
        $('#fresh_stock_return').show();
         $('#payment_reciept').hide();
        $('#expense_detail').hide();
    })
    $(".payment_reciept").click(function(){
        $('#crate_details').hide();
        $('#fresh_stock_return').hide();
        $('#payment_reciept').show();
        $('#expense_detail').hide();
    }) 
    
    $(".expense_details").click(function(){
        $('#crate_details').hide();
        $('#fresh_stock_return').hide();
        $('#payment_reciept').hide();
        $('#expense_detail').show();
    })
    
  $("#challan_n").dblclick(function(){
      $('#transfer-modal').modal('show');
      $('#transfer-modal').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
  })
    $("#search_data").click(function(){  
        
        var from_date = $('#from_date1').val();
        var to_date = $('#to_date1').val();
     
     $.ajax({
      url:"<?php echo admin_url(); ?>Vehicle_return/challan_details_model",
      dataType:"html",
      method:"POST",
      data:{from_date:from_date,to_date:to_date},
      beforeSend: function () {
        
        $('#searchh2').css('display','block');
        $('.table_adj_report tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table_adj_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
         $('#table_adj_report tbody').html(data);
            $('.get_challan_id').on('click',function(){ 
              challan_id = $(this).attr("data-id");
              
                myFunction_table_details(challan_id);
                myFunction(challan_id);
                $('#transfer-modal').modal('hide');
        
            });
        }
     });
   });
 function myFunction_table_details(challan_id) {
    $.ajax({
      url:"<?php echo admin_url(); ?>Vehicle_return/all_challan_details",
      dataType:"JSON",
      method:"POST",
      data:{challan_id:challan_id},
      beforeSend: function () {
        $('.searchh3').css('display','block');
        $('.searchh3').css('color','blue');
      },
      complete: function () {
        $('.searchh3').css('display','none');
      },
      success:function(response){
        var total_ItemID =(response.itemhead.length);
        $("#ItemCount").val(total_ItemID);
        var $itemModal = $('#crate_details_tbl');
        var count_row = $("#row_count").val();
        var count_row_pay = $("#row_count_pay").val();
        var count_row_exp = $("#row_count_exp").val();
        var i = 1;
        for(var count = 1; count <= count_row; count++)
        {
            $("#row"+count).remove();
        }
        for(var count1 = 1; count1 <= count_row_pay; count1++)
        {
            $("#row_pay"+count1).remove();
        }
        for(var count2 = 1; count2 <= count_row_exp; count2++)
        {
            $("#row_exp"+count2).remove();
        }
        
            var html2 = '';
            
            html2 += '<table class="table table-striped table-bordered stock_details_tbl fixed_header1" id="stock_details_tbl" width="100%">';
            html2 += '<thead id="thead">';
            html2 += '<tr>';
            html2 += '<th>AccountID</th>';
            html2 += '<th>AccoutName</th>';
            html2 += '<th>SaleID</th>';
            html2 += '<th>RtnAMT</th>';
            html2 += '<th>CGST</th>';
            html2 += '<th>SGST</th>';
            html2 += '<th>IGST</th>';
            
            $.each(response.itemhead, function (column1, value1) {
                html2 += '<th style="width: 60px;">'+value1+'</th>';
            })
           
            html2 += '</tr>';
            html2 += '</thead>';
            html2 += '<tbody id="tbody">';
            
            // payment table
            var itemCount = 0;
            $.each(response.data, function (column, value) {
                var html = '';
                var html3 = '';
                var col = column + 1;
                $("#row_count").val(col);
                $("#row_count_frRtn").val(col);
                $("#row_count_pay").val(col);
                html += '<tr class="accounts" id="row'+col+'">';
                html += '<td style="width: 125px;"><input type="text" name="AccountID'+col+'" style="width: 125px;" id="AccountID'+col+'" value="'+value.AccountID+'"></td>';
                html += '<td style="padding:1px 5px !important;">'+value.company+'</td>';
                html += '<td style="padding:1px 5px !important;">'+value.address+'</td>';
                
                html3 += '<tr class="accounts" id="row_pay'+col+'">';
                html3 += '<td style="width: 125px;" id="AccountIDTD_pay"><input type="text" name="AccountID_pay'+col+'" style="width: 125px;" id="AccountID_pay'+col+'" value="'+value.AccountID+'"></td>';
                html3 += '<td style="padding:1px 5px !important;">'+value.company+'</td>';
                html3 += '<td style="padding:1px 5px !important;">'+value.address+'</td>';
                html3 += '<td class="rcptAmts" style="width: 80px;"><input type="text" name="receiptamt'+col+'" id="receiptamt'+col+'" onblur="calculate_payment();" value="0" style="width: 80px;text-align: right" onkeypress="return isNumber(event)"></td>';
                html3 += '</tr>';
                if(value.Qty == null){
                    var qty = 0;
                }else{
                    var qty =value.Qty;
                }
                html += '<td style="padding:1px 5px !important;text-align: right;">'+qty+'</td>';
                html += '<td style="padding:1px 5px !important;text-align: right;">'+value.Crates+'</td>';
                html += '<td class="rtnqty" style="width: 80px;"><input type="text" name="rtncrates'+col+'" id="rtncrates'+col+'" onblur="calculate_balcrates();" value="0" style="width: 80px;text-align: right;" onkeypress="return isNumber(event)"><input type="hidden" name="balcrates'+col+'" id="balcrates'+col+'" value="'+value.balance_crates_org+'"><input type="hidden" name="colno" id="colno" value="'+col+'"></td>';
                html += '<td id="balCrates" style="padding:1px 5px !important;text-align: right;"><span>'+value.balance_crates+'</span></td>';
                html += '</tr>';
                $('#crate_details_tbl tbody').append(html);
                $('#payment_details_tbl tbody').append(html3);
                
                html2 += '<tr id="row'+col+'">';
                html2 += '<td style="padding:1px 5px !important;">'+value.AccountID+'<input type="hidden" name="AccountID_SRtn'+col+'" id="AccountID_SRtn'+col+'" value="'+value.AccountID+'"></td>';
                html2 += '<td style="padding:1px 5px !important;">'+value.company+'</td>';
                html2 += '<td style="padding:1px 5px !important;">'+value.SalesID+'</td>';
                html2 += '<td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="RtnAmt_val'+col+'" id="RtnAmt_val'+col+'" value="0"><span id="RtnAmt">0.00</span></td>';
                html2 += '<td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="cgst_val'+col+'" id="cgst_val'+col+'" value="0"><span id="cgst">0.00</span></td>';
                html2 += '<td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="sgst_val'+col+'" id="sgst_val'+col+'" value="0"><span id="sgst">0.00</span></td>';
                html2 += '<td style="padding:1px 5px !important;text-align: right;"><input type="hidden" name="igst_val'+col+'" id="igst_val'+col+'" value="0"><span id="igst">0.00</span></td>';
                
                $.each(response.itemhead, function (column4, value4) {
                    var column4 = column4 + 1;
                    var match = 0;
                    var rate = 0;
                    var gst = 0;
                    var BilledQty = 0;
                    var ItemID = 0;
                    var TransID = 0;
                    var AccountID = 0;
                    var PackQty = 0;
                    var state = '';
                    $.each(value.itemdetails, function (column3, value3) {
                        if(value4==value3["ItemID"]){
                            itemCount = itemCount + 1;
                            match = 1;
                            rate = value3["BasicRate"];
                            BilledQty = value3["BilledQty"];
                            ItemID = value3["ItemID"];
                            TransID = value3["TransID"];
                            AccountID = value3["AccountID"];
                            PackQty = value3["CaseQty"];
                            if(value3["igst"]==null){
                                gst = value3["cgst"] * 2;
                                state = "UP"
                            }else{
                                gst = value3["igst"];
                                state = "UP1"
                            }
                        }
                    })
                    if(match == "1"){
                    html2 += '<td style="width: 60px;"><input type="hidden" name="PackQty_val'+itemCount+'" id="PackQty_val'+itemCount+'" value="'+PackQty+'"><input type="hidden" name="AccountID_val'+itemCount+'" id="AccountID_val'+itemCount+'" value="'+AccountID+'"><input type="hidden" name="ItemID_val'+itemCount+'" id="ItemID_val'+itemCount+'" value="'+ItemID+'"><input type="hidden" name="TransID_val'+itemCount+'" id="TransID_val'+itemCount+'" value="'+TransID+'"><input type="hidden" name="rate_val'+itemCount+'" id="rate_val'+itemCount+'" value="'+rate+'"><input type="hidden" name="gst_val'+itemCount+'" id="gst_val'+itemCount+'" value="'+gst+'"><input type="hidden" name="state_val'+itemCount+'" id="state_val'+itemCount+'" value="'+state+'"><input type="hidden" name="BilledQty" id="BilledQty" value="'+BilledQty+'"><input type="hidden" name="state" id="state" value="'+value.state+'"><input type="hidden" name="rate" id="rate" value="'+rate+'"><input type="hidden" name="gst" id="gst" value="'+gst+'"><input type="text" name="rtnqty'+itemCount+'" id="rtnqty'+itemCount+'" onblur="calculate_rtnqty();" onkeypress="return isNumber(event)" onfocus="myFunction(this)" style="width: 60px;text-align: right;" value="" ></td>';
                    }else{
                        html2 += '<td style="width: 60px;padding:1px 5px !important;text-align: right;background-color: #aea5a599;"></td>';
                    }   
                })
                
                html2 += '</tr>';
                
            });
            html2 += '</tbody>';
            html2 += '</table>';
            
            $('#ItemCount').val(itemCount);
            $('#fixed_header1').html(html2);
            //$('#fixed_header3').html(html3);
        }
     });
    }
  function myFunction(challan_id) { 
    $.ajax({
      url:"<?php echo admin_url(); ?>Vehicle_return/unique_challan_details",
      dataType:"JSON",
      method:"POST",
      data:{challan_id:challan_id},
     
      success:function(data){
           $('#challan_n').val(data.ChallanID);
           $('#route_code').val(data.RouteID);
           $('#route_name').val(data.name);
           $('#routekm').val(data.KM);
           $('#vehicle_number').val(data.VehicleID);
           $('#driver_id').val(data.DriverID);
           $('#driver_name').val(data.driver_fn);
           $('#loder_id').val(data.LoaderID);
           $('#loder_name').val(data.loader_fn);
           $('#salesman_id').val(data.SalesmanID);
           $('#salesman_name').val(data.Salesman_fn);
           $('#challan_crates').val(data.Crates);
           $('#vehicle_capc').val(data.VehicleCapacity);
           $('#case_depo').val(0);
           $('#case_depo1').val(0);
           $('#check_depo').val(0);
           $('#total_expense').val(0);
           $('#total_expense1').val(0);
           $('#fresh_ret_amt').val(0);
           $('#fresh_ret_amt1').val(0);
           $('#NERT_trans').val(0);
      
        }
     });
    }
    // For Crates Details
    $('#rtncrates').on('blur', function () {
        //alert('hello');
        var AccountID =document.getElementById("AccountID").value;
        var party_name_val =document.getElementById("party_name_val").value;
        var address_val =document.getElementById("address_val").value;
        var opnCrates_val =document.getElementById("opnCrates_val").value;
        
        var balcrates =document.getElementById("balcrates").value;
        var rtncrates =document.getElementById("rtncrates").value;
        var balCrates_new = balcrates - rtncrates;
        
        var table=document.getElementById("crate_details_tbl");
        var table_len=(table.rows.length) - 1;
        var html = '';
        
        if(AccountID == "" || AccountID == null){
            alert('please add AccountID');
            $('#AccountID').val('');
            $('#AccountID').focus();
        }else{
            var count = $("#row_count").val();
            var new_count = parseInt(count) + 1;
            $("#row_count").val(new_count);
            html += '<tr class="accounts" id="row'+table_len+'">';
            html += '<td><input type="text" name="AccountID'+table_len+'" style="width: 125px;" id="AccountID'+table_len+'" value="'+AccountID+'"></td>';
            html += '<td style="padding:1px 5px !important;">'+party_name_val+'</td>';
            html += '<td style="padding:1px 5px !important;">'+address_val+'</td>';
            if(opnCrates_val == null){
                var qty = 0;
            }else{
                var qty =opnCrates_val;
            }
            html += '<td style="padding:1px 5px !important;text-align: right;">'+qty+'</td>';
            html += '<td style="padding:1px 5px !important;text-align: right;"></td>';
            html += '<td class="rtnqty"><input type="text" name="rtncrates'+table_len+'" id="rtncrates'+table_len+'"  onblur="calculate_balcrates();" value="'+rtncrates+'" style="width: 80px;text-align: right;" onkeypress="return isNumber(event)"><input type="hidden" name="balcrates'+table_len+'" id="balcrates'+table_len+'" value="'+balcrates+'"><input type="hidden" name="colno" id="colno" value="'+table_len+'"></td>';
            html += '<td id="balCrates" style="padding:1px 5px !important;text-align: right;"><span>'+balCrates_new+'</span></td>';
            html += '</tr>';
            var row = table.insertRow(table_len).outerHTML=html;
            
            document.getElementById("AccountID").value="";
            document.getElementById("party_name_val").value="";
            document.getElementById("address_val").value="";
            document.getElementById("opnCrates_val").value="";
            document.getElementById("balCrates_new_val").value="";
            document.getElementById("balcrates").value ="";
            document.getElementById("rtncrates").value="";
            
            
            document.getElementById("party_name").innerHTML="";
            document.getElementById("address").innerHTML ="";
            document.getElementById("opnCrates").innerHTML="";
            document.getElementById("balCrates_new").innerHTML="";
        }
        
    });
    
    // For Payments Details
    $('#receiptamt').on('blur', function () {
        //alert('hello');
        var AccountID_pay =document.getElementById("AccountID_pay").value;
        var party_name_pay_val =document.getElementById("party_name_pay_val").value;
        var address_pay_val =document.getElementById("address_pay_val").value;
        var receiptamt =document.getElementById("receiptamt").value;
        
        var table=document.getElementById("payment_details_tbl");
        var table_len=(table.rows.length) - 1;
        var html = '';
        
        if(AccountID_pay == "" || AccountID_pay == null){
            alert('please add AccountID');
            $('#AccountID_pay').val('');
            $('#AccountID_pay').focus();
        }else{
            var count = $("#row_count_pay").val();
            var new_count = parseInt(count) + 1;
            $("#row_count_pay").val(new_count);
            html += '<tr class="accounts" id="row_pay'+table_len+'">';
            html += '<td><input type="text" name="AccountID_pay'+table_len+'" style="width: 125px;" id="AccountID_pay'+table_len+'" value="'+AccountID_pay+'"></td>';
            html += '<td style="padding:1px 5px !important;">'+party_name_pay_val+'</td>';
            html += '<td style="padding:1px 5px !important;">'+address_pay_val+'</td>';
            
            html += '<td class="rcptAmts"><input type="text" name="receiptamt'+table_len+'" id="receiptamt'+table_len+'"  onblur="calculate_payment();" value="'+receiptamt+'" style="width: 80px;text-align: right" onkeypress="return isNumber(event)"></td>';
            html += '</tr>';
            var row = table.insertRow(table_len).outerHTML=html;
            
            document.getElementById("AccountID_pay").value="";
            document.getElementById("party_name_pay_val").value="";
            document.getElementById("address_pay_val").value="";
            document.getElementById("receiptamt").value="";
            
            document.getElementById("party_name_pay").innerHTML="";
            document.getElementById("address_pay").innerHTML ="";
            
        }
        
    });
    
    // For Expense Details
    $('#expamt').on('blur', function () {
        //alert('hello');
        var AccountID_exp =document.getElementById("AccountID_exp").value;
        var party_name_exp_val =document.getElementById("party_name_exp_val").value;
        var address_exp_val =document.getElementById("address_exp_val").value;
        var expamt =document.getElementById("expamt").value;
        
        var table=document.getElementById("expense_details_tbl");
        var table_len=(table.rows.length) - 1;
        var html = '';
        
        if(AccountID_exp == "" || AccountID_exp == null){
            alert('please add AccountID');
            $('#AccountID_exp').val('');
            $('#AccountID_exp').focus();
        }else{
            var count = $("#row_count_exp").val();
            var new_count = parseInt(count) + 1;
            $("#row_count_exp").val(new_count);
            html += '<tr class="accounts" id="row_exp'+table_len+'">';
            html += '<td><input type="text" name="AccountID_exp'+table_len+'" style="width: 125px;" id="AccountID_exp'+table_len+'" value="'+AccountID_exp+'"></td>';
            html += '<td style="padding:1px 5px !important;">'+party_name_exp_val+'</td>';
            html += '<td style="padding:1px 5px !important;">'+address_exp_val+'</td>';
            
            html += '<td class="expamts"><input type="text" name="expamt'+table_len+'" id="expamt'+table_len+'"  onblur="calculate_expense();" value="'+expamt+'" style="width: 80px;text-align:right;" onkeypress="return isNumber(event)"></td>';
            html += '</tr>';
            var row = table.insertRow(table_len).outerHTML=html;
            
            document.getElementById("AccountID_exp").value="";
            document.getElementById("party_name_exp_val").value="";
            document.getElementById("address_exp_val").value="";
            document.getElementById("expamt").value="";
            
            document.getElementById("party_name_exp").innerHTML="";
            document.getElementById("address_exp").innerHTML ="";
            
        }
    });
    
    
});
</script>

<script>
    function isNumber(evt) {
        
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
    }
</script>

<script>
    function myFunction(val) {
    $(this).val('');
    }
</script>

<script type='text/javascript'>
    $(document).ready(function(){
    // Initialize For Account
     $("#AccountID").autocomplete({
        
        source: function( request, response ) {
          
          $.ajax({
            url: "<?=base_url()?>admin/Vehicle_return/accountlist",
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
             
       //alert(Conform);
       var ChallanID = $('#challan_n').val();
       if(empty(ChallanID)){
          alert('please select challanID');
            $("#AccountID").focus();
            return false;
       }else{
                $('#AccountID').val(ui.item.value);
                $('#party_name').html(ui.item.label);
                $('#party_name_val').val(ui.item.label);
                $('#address').html(ui.item.address);
                $('#address_val').val(ui.item.address);
                if(ui.item.openCrates == null){
                    var opnCrates = 0;
                }else{
                    var opnCrates = ui.item.openCrates;
                }
                $('#balcrates').val(ui.item.BalCrates);
                $('#opnCrates').html(opnCrates);
                $('#opnCrates_val').val(opnCrates);
                var balhtml = '<span>'+ui.item.BalCrates+'</span>'
                $('#balCrates_new').html(ui.item.BalCrates);
                $('#balCrates_new_val').val(ui.item.BalCrates);
                $("#rtncrates").focus();
                return false;
            }
        }
      });
        $('#AccountID').on('blur', function () {
            var AccountID = $(this).val();
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/Vehicle_return/get_Account_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#AccountID").val('');
                            $("#AccountID").focus();
                        }else{
                            $('#party_name_val').val(data.company); // display the selected text
                            $('#party_name').html(data.company); // display the selected text
                            $('#address_val').val(data.address); // display the selected text
                            $('#address').html(data.address); // display the selected text
                            if(data.Qty == null){
                                var opnCrates = 0;
                            }else{
                                var opnCrates = data.Qty;
                            }
                            $('#opnCrates_val').val(opnCrates); // display the selected text
                            $('#opnCrates').html(opnCrates); // display the selected text
                            $('#balCrates_new_val').val(data.balance_crates); // display the selected text
                            $('#balcrates').val(data.balance_crates); // display the selected text
                            $('#balCrates_new').html(data.balance_crates); // display the selected text
                            
                            $("#rtncrates").focus();
                        }
                    }
                });
            }
        });
        
    // For payment
    // Initialize For Account
     $("#AccountID_pay").autocomplete({
        
        source: function( request, response ) {
          
          $.ajax({
            url: "<?=base_url()?>admin/Vehicle_return/accountlist",
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
             
       //alert(Conform);
       var ChallanID = $('#challan_n').val();
       if(empty(ChallanID)){
          alert('please select challanID');
            $("#AccountID_pay").focus();
            return false;
       }else{
                $('#AccountID_pay').val(ui.item.value);
                $('#party_name_pay').html(ui.item.label);
                $('#party_name_pay_val').val(ui.item.label);
                $('#address_pay').html(ui.item.address);
                $('#address_pay_val').val(ui.item.address);
                $("#receiptamt").focus();
                return false;
            }
        }
      });
        $('#AccountID_pay').on('blur', function () {
            var AccountID = $(this).val();
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/Vehicle_return/get_Account_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#AccountID_pay").val('');
                            $("#AccountID_pay").focus();
                        }else{
                            $('#party_name_pay_val').val(data.company); // display the selected text
                            $('#party_name_pay').html(data.company); // display the selected text
                            $('#address_pay_val').val(data.address); // display the selected text
                            $('#address_pay').html(data.address); // display the selected text
                            
                            $("#receiptamt").focus();
                        }
                    }
                });
            }
        });
        
        // For Expenses
    // Initialize For Account
     $("#AccountID_exp").autocomplete({
        
        source: function( request, response ) {
          
          $.ajax({
            url: "<?=base_url()?>admin/Vehicle_return/staffaccountlist",
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
             
       //alert(Conform);
       var ChallanID = $('#challan_n').val();
       if(empty(ChallanID)){
          alert('please select challanID');
            $("#AccountID_exp").focus();
            return false;
       }else{
                $('#AccountID_exp').val(ui.item.value);
                $('#party_name_exp').html(ui.item.label);
                $('#party_name_exp_val').val(ui.item.label);
                $('#address_exp').html(ui.item.address);
                $('#address_exp_val').val(ui.item.address);
                $("#expamt").focus();
                return false;
            }
        }
      });
        $('#AccountID_exp').on('blur', function () {
            var AccountID = $(this).val();
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/Vehicle_return/get_staffAccount_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#AccountID_exp").val('');
                            $("#AccountID_exp").focus();
                        }else{
                            var fullname = data.firstname+' '+data.lastname
                            $('#party_name_exp_val').val(fullname); // display the selected text
                            $('#party_name_exp').html(fullname); // display the selected text
                            $('#address_exp_val').val(data.current_address); // display the selected text
                            $('#address_exp').html(data.current_address); // display the selected text
                            
                            $("#expamt").focus();
                        }
                    }
                });
            }
        });
    
    
    });
</script>
<script>
    //  Return Quantity calculation
    function calculate_rtnqty(){
        //var len = $('#challan_data tr')[0].cells.length;
        var frsrtnAmt = 0;
        var item_count = 0;
        $('#stock_details_tbl tbody').find('tr').each(function (k, v) {
            var tr_total = 0;
            var tr_gst = 0;
            var tr_half_gst = 0;
            var state = '';
            $(this).find('td').each(function (k, v) {
                
                var rtnqty = $(this).find('input[type="text"]').val();
                var rate = $(this).find('input[name="rate"]').val();
                var gst = $(this).find('input[name="gst"]').val();
                var billedqty = $(this).find('input[name="BilledQty"]').val();
               
                if(rtnqty !== undefined){
                    item_count++;
                    if(rtnqty !==""){
                         state = $(this).find('input[name="state"]').val();
                        var comp = 0;
                        //alert(state);
                        if(parseFloat(billedqty) >= parseFloat(rtnqty)){
                            
                            var total = parseFloat(rtnqty) * parseFloat(rate);
                            var gstAmt = (total / 100 ) * parseFloat(gst);
                            tr_gst = parseFloat(tr_gst) + parseFloat(gstAmt);
                            tr_total = parseFloat(tr_total) + parseFloat(total);
                        }else{
                            $(this).find('input[type="text"]').val('0')
                            var msg = "Please enter less than or equal to '"+billedqty+"' ";
                            alert(msg);
                            
                        }
                        
                    }
                }
            })
                frsrtnAmt = parseFloat(frsrtnAmt) + parseFloat(tr_total);
                
                $('td', this).eq(3).find('input[type="hidden"]').val(tr_total.toFixed(2));
                $('td', this).eq(3).find('span').html(tr_total.toFixed(2));
                //alert(state);
                if(state == "UP"){
                     tr_half_gst = tr_gst / 2;
                    $('td', this).eq(4).find('input[type="hidden"]').val(tr_half_gst.toFixed(2));
                    $('td', this).eq(5).find('input[type="hidden"]').val(tr_half_gst.toFixed(2));
                    $('td', this).eq(4).find('span').html(tr_half_gst.toFixed(2));
                    $('td', this).eq(5).find('span').html(tr_half_gst.toFixed(2));
                }else{
                    $('td', this).eq(6).find('input[type="hidden"]').val(tr_gst.toFixed(2));
                    $('td', this).eq(6).find('span').html(tr_gst.toFixed(2));
                }
        })
        $("#fresh_ret_amt").val(frsrtnAmt.toFixed(2));
        $("#fresh_ret_amt1").val(frsrtnAmt.toFixed(2));
        $("#ItemCount").val(item_count);
    }
</script>
<script>
    // balance crate calculation
    function calculate_balcrates(){
        var p = $(".table.crate_details_tbl tbody tr.accounts");
        var totalRfCrates = 0;
        $.each(p, function() {
            var row_no = $(this).find("td.rtnqty input[name='colno']").val();
            var AccountID = $(this).find("td#AccountIDTD input[type='text']").val();
            var actualbal = $(this).find("td.rtnqty input[type='hidden']").val();
            var returnCrates = $(this).find("td.rtnqty input[type='text']").val();
            if(returnCrates !== "" && AccountID !== ""){
                totalRfCrates = parseInt(totalRfCrates) + parseInt(returnCrates);
            }
            var newBal = parseInt(actualbal) - parseInt(returnCrates);
            if(row_no == ""){
              
                $(this).find("td#balCrates_new span").html(newBal);
            
            }else{
                $(this).find("td#balCrates span").html(newBal);
            }
        })
        $("#refund_crates").val(totalRfCrates);
    }
</script>
<script type='text/javascript'>
    //  Receipt Payments calculation
    function calculate_payment(){
        var p = $(".table.payment_details_tbl tbody tr.accounts");
        var totalpymt = 0;
        $.each(p, function() {
            
            var receiptamt = $(this).find("td.rcptAmts input[type='text']").val();
            var AccountID = $(this).find("td#AccountIDTD_pay input[type='text']").val();
            
            if(receiptamt !== "" && AccountID !== ""){
                totalpymt = parseInt(totalpymt) + parseInt(receiptamt);
            }
        })
        $("#case_depo").val(totalpymt);
        $("#case_depo1").val(totalpymt);
    }
</script>

<script type='text/javascript'>
    // Expense Amt calculation
    function calculate_expense(){
        var p = $(".table.expense_details_tbl tbody tr.accounts");
        var totalExpense = 0;
        $.each(p, function() {
            
            var expAmt = $(this).find("td.expamts input[type='text']").val();
            var AccountID = $(this).find("td#AccountIDTD_exp input[type='text']").val();
            if(expAmt !== "" && AccountID !== ""){
                totalExpense = parseInt(totalExpense) + parseInt(expAmt);
            }
        })
        $("#total_expense").val(totalExpense);
        $("#total_expense1").val(totalExpense);
    }
</script>
