
<script>
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
      //init_journal_entry_table();
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
     
      success:function(response){
          $("#fresh_stock_return").html('');
            fress_stock_return(response.crate_data);
            let data = response.crate_data
            let length = data.length;
            //alert(length);
            
            for(var row_index = 0; row_index < length; row_index++) {
                hot1.setDataAtCell(row_index,0, data[row_index].AccountID);
                hot1.setDataAtCell(row_index,1, data[row_index].company);
                hot1.setDataAtCell(row_index,2, data[row_index].address);
                hot1.setDataAtCell(row_index,3, data[row_index].Qty);
                hot1.setDataAtCell(row_index,4, data[row_index].Crates);
                hot1.setDataAtCell(row_index,5, '0');
                hot1.setDataAtCell(row_index,7, data[row_index].balance_crates);
                hot1.setDataAtCell(row_index,6, data[row_index].balance_crates);
                
                
                hot2.setDataAtCell(row_index,0, data[row_index].AccountID);
                hot2.setDataAtCell(row_index,1, data[row_index].company);
                hot2.setDataAtCell(row_index,2, data[row_index].address);
                hot2.setDataAtCell(row_index,3, '');
                
                //hot4.setDataAtCell(row_index,0, data[row_index].AccountID);
                //hot4.setDataAtCell(row_index,1, data[row_index].company);
            }
            
            for(var row_index = length; row_index < 10; row_index++) {
                hot1.setDataAtCell(row_index,0, '');
                hot1.setDataAtCell(row_index,1, '');
                hot1.setDataAtCell(row_index,2, '');
                hot1.setDataAtCell(row_index,3, '');
                hot1.setDataAtCell(row_index,4, '');
                hot1.setDataAtCell(row_index,5, '');
                hot1.setDataAtCell(row_index,6, '');
                hot1.setDataAtCell(row_index,7, '');
                
                hot2.setDataAtCell(row_index,0, '');
                hot2.setDataAtCell(row_index,1, '');
                hot2.setDataAtCell(row_index,2, '');
                hot2.setDataAtCell(row_index,3, '');
                
                //hot4.setDataAtCell(row_index,0, '');
                //hot4.setDataAtCell(row_index,1, '');
                
                
            }
            
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
           $('#check_depo').val(0);
           $('#total_expense').val(0);
           $('#fresh_ret_amt').val(0);
           $('#NERT_trans').val(0);
      
        }
     });
    }
     

if(data != ''){
    var dataObject1 = data;

 }else{ 
    var dataObject1 = [ 
    ]; 
}
 
  var hotElement1 = document.querySelector('#crate_details');
    var hotElementContainer = hotElement1.parentNode;

    var hotSettings1 = {
      data: dataObject1,
      columns: [
         {
          data: 'AccountID',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data:  <?php echo json_encode($clients_details); ?>
          }
        },
        { 
          data: 'company',
          type: 'text',
           width: 100,
          readOnly: true
        },
       
        {
          data: 'address',
          type: 'text',
          
           width: 100,
           readOnly: true
        },
         {
          data: 'Qty',
          type: 'numeric',
          width: 50,
           readOnly: true
      
        },
        {
          data: 'crates_data',
          type: 'numeric',
          width: 50,
           readOnly: true
      
        },
        {
          data: 'return_crates',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
         
        },
         {
          data: 'balance_crates',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
        {
          data: 'balance_crates_org',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        }
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [100,10,100,50,100,50,100],
      colHeaders: [
        '<?php echo _l('Account Id'); ?>',
        '<?php echo _l('Account Name'); ?>',
        '<?php echo _l('Address'); ?>',
        '<?php echo _l('OpeningCrates'); ?>',
        '<?php echo _l('ChallanCrates'); ?>',
        '<?php echo _l('ReturnCrates'); ?>',
        '<?php echo _l('BalanceCrates'); ?>',
        
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
    //   dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: false,
      manualColumnMove: false,
      multiColumnSorting: {
        indicator: false
      },
      filters: false,
      manualRowResize: true,
      manualColumnResize: true,
      hiddenColumns: {
      columns: [7],
      }
    };


var hot1 = new Handsontable(hotElement1, hotSettings1);
hot1.addHook('afterChange', function(changes1, src) {
	if(changes1 !== null){
	    var total_return = 0.00;
	    changes1.forEach(([row1, prop1, oldValue, newValue]) => {
	        var count = 1; 
           if(prop1 == 'AccountID'){
               var comp = hot1.getDataAtCell(row1, 1);
               if(comp !== null){
                    if(newValue !== null){
                       $.post(admin_url + 'Vehicle_return/get_vendor_d/'+newValue).done(function(response){
              	          response = JSON.parse(response);
                          hot1.setDataAtCell(row1,1, response.company);
              	          hot1.setDataAtCell(row1,2, response.address);
              	          if(response.Qty != null){
              	              hot1.setDataAtCell(row1,3, response.opening_crates);
              	          }else{
              	              hot1.setDataAtCell(row1,3, 0);
              	          }
              	          
              	          hot1.setDataAtCell(row1,6, response.balance_crates);
              	        
              	           count++; 
      	                });
                    }
               }
                
  	               
  	      }else if(prop1 == 'return_crates'){
  	          if(newValue !== ''){
  	                
  	                var pre_bal = hot1.getDataAtCell(row1,7);
  	                var balance_c =  pre_bal - newValue;
  	                //alert(pre_bal);
  	                hot1.setDataAtCell(row1,6, balance_c);
  	          }
  	           
  	            
  	      }else if(prop1 == 'balance_crates'){
  	         
            for (var row_index = 0; row_index < 10; row_index++) {
                var return_crates = hot1.getDataAtCell(row_index, 5);
                var AccountID1 = hot1.getDataAtCell(row_index, 0);
                //alert(AccountID1);
                    if(AccountID1 !== null){
                        if(return_crates !== ''){
                            total_return += return_crates;
                        }
                    }
            }
  	      }
        
	    });
	    $('input[name="refund_crates"]').val(total_return);
	}
  });
  
//here end crate-details table 

 //here table for payment_reciept
if(data != ''){
    var dataObject2 = data;

 }else{ 
    var dataObject2 = [ 
       
    ]; 
}

  var hotElement2 = document.querySelector('#payment_reciept');
    var hotElementContainer2 = hotElement2.parentNode;

    var hotSettings2 = {
      data: dataObject2,
      columns: [
       {
          data: 'AccountID',
          renderer: customDropdownRenderer2,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data:  <?php echo json_encode($clients_details); ?>
          }
        },
        { 
          data: 'company',
          type: 'text',
           width: 100,
          readOnly: true
        },
       
        {
          data: 'address',
          type: 'text',
          
           width: 100,
           readOnly: true
        },
          {
          data: 'reciept_amt',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
         
        }
        
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [100,10,100,50],
      colHeaders: [
        '<?php echo _l('AccountId'); ?>',
        '<?php echo _l('Account Name'); ?>',
        '<?php echo _l('Address'); ?>',
        '<?php echo _l('Reciept Amt'); ?>',
        
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      mergeCells: true,
      contextMenu: true,
      manualRowMove: false,
      manualColumnMove: false,
      multiColumnSorting: {
        indicator: false
      },
      filters: false,
      manualRowResize: true,
      manualColumnResize: true
    };
var hot2 = new Handsontable(hotElement2, hotSettings2);
hot2.addHook('afterChange', function(changes, src) {
    
	if(changes !== null){
	    total_payment = 0.00;
	    changes.forEach(([row1, prop, oldValue, newValue]) => {
	        var count = 1; 
            if(prop == 'AccountID'){
                var comp2 = hot2.getDataAtCell(row1, 1);
                if(comp2 !== null){
                    if(newValue !== null){
                        $.post(admin_url + 'Vehicle_return/get_vendor_d/'+newValue).done(function(response){
                  	        response = JSON.parse(response);
                            hot2.setDataAtCell(row1,1, response.company);
                  	        hot2.setDataAtCell(row1,2, response.address);
      	                });
                    }
                }
                
  	        }else if(prop == 'reciept_amt'){
                for (var row_index = 0; row_index <= 10; row_index++) {
                    var return_payment = hot2.getDataAtCell(row_index, 3);
                    var AccountID = hot2.getDataAtCell(row_index, 0);
                    if(AccountID !== null){
                        if(return_payment !== ''){
                            total_payment += return_payment;
                        } 
                    }
                    
                }
  	        }
	    });
	    $('input[name="fresh_ret_amt"]').val(total_payment);
	}
  });
  //here end table for payment_reciept
  
  
  //here table for expense_details
if(data != ''){
    var dataObject3 = [];

 }else{ 
    var dataObject3 = [ 
       
    ]; 
}

  var hotElement3 = document.querySelector('#expense_detail');
    var hotElementContainer = hotElement3.parentNode;
    
    var hotSettings3 = {
      data: dataObject3,
      columns: [
       {
          data: 'AccountID',
          renderer: customDropdownRenderer3,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data:  <?php echo json_encode($staff_details); ?>
          }
        },
        { 
          data: 'company',
          type: 'text',
           width: 100,
          readOnly: true
        },
       
        {
          data: 'address',
          type: 'text',
          
           width: 100,
           readOnly: true
        },
          {
          data: 'expense_amt',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
         
        }
        
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [100,10,100,50],
      colHeaders: [
        '<?php echo _l('AccountId'); ?>',
        '<?php echo _l('Account Name'); ?>',
        '<?php echo _l('Address'); ?>',
        '<?php echo _l('Expense Amt'); ?>',
        
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      mergeCells: true,
      contextMenu: true,
      manualRowMove: false,
      manualColumnMove: false,
      multiColumnSorting: {
        indicator: false
      },
      filters: false,
      manualRowResize: true,
      manualColumnResize: true
    };
var hot3 = new Handsontable(hotElement3, hotSettings3);
hot3.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row3, prop, oldValue, newValue]) => {
	        var count = 1; 
               if(prop == 'AccountID'){
  	                  $.post(admin_url + 'Vehicle_return/get_vendor_details_expenses/'+newValue).done(function(response){
          	           
          	          response = JSON.parse(response);
          	     //   console.log(response)
                      hot3.setDataAtCell(row3,1, response.company);
          	          hot3.setDataAtCell(row3,2, response.address);
          	          
  	            });
  	        
  	      }else if(prop == 'expense_amt'){
  	         var total_expense =0.00;
            for (var row_index = 0; row_index <= 10; row_index++) {
                if((hot3.getDataAtCell(row_index, 3)) != null){
                  
                    total_expense += (parseFloat(hot3.getDataAtCell(row_index, 3)));
                }
            }
             $('input[name="total_expense"]').val(total_expense);
         
  	      }
	    });
	}
  });
  $('.save_detail').on('click', function() {
  $('input[name="crate_details"]').val(JSON.stringify(hot1.getData()));   
});
$('.save_detail').on('click', function() {
  $('input[name="payment_reciept"]').val(JSON.stringify(hot2.getData()));   
});
$('.save_detail').on('click', function() {
  $('input[name="expense_detail"]').val(JSON.stringify(hot3.getData()));   
});

  //here end table for expense_details
function fress_stock_return(data){  
  //here table for fresh_stock_return
if(data != ''){
    var dataObject4 = data;

 }else{ 
    var dataObject4 = []; 
}

    var hotElement4 = document.querySelector('#fresh_stock_return');
    var hotElementContainer = hotElement4.parentNode;
    var hotSettings4 = {
      data: dataObject4,
      columns: [
         {
          data: 'AccountID',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data:  <?php echo json_encode($clients_details); ?>
          }
        },
        { 
          data: 'company',
          type: 'text',
           width: 100,
          readOnly: true
        },
        {
          data: 'SuppliedIn_data',
          type: 'text',
          
           width: 100,
           readOnly: true
        },
         {
          data: 'case_qty',
          type: 'numeric',
          width: 50,
           readOnly: true
      
        },
        {
          data: 'CaseQty',
          type: 'numeric',
          width: 50,
           readOnly: true
        },
        {
          data: 'all_qty',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
         {
          data: 'assigned_rate',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
        {
          data: 'assigned_rate',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        }
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [100,10,100,50,100,50,50,50],
      colHeaders: [
        '<?php echo _l('ActId'); ?>',
        '<?php echo _l('Account Name'); ?>',
        '<?php echo _l('Sales Id'); ?>',
        '<?php echo _l('Reciept Id'); ?>',
        '<?php echo _l('Rtn Amt'); ?>',
        '<?php echo _l('CGST'); ?>',
        '<?php echo _l('SGST'); ?>',
        '<?php echo _l('IGST'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      mergeCells: true,
      contextMenu: true,
      manualRowMove: false,
      manualColumnMove: false,
      multiColumnSorting: {
        indicator: false
      },
      filters: false,
      manualRowResize: true,
      manualColumnResize: true
    };
    var hot4 = new Handsontable(hotElement4, hotSettings4);
    hot4.addHook('afterChange', function(changes4, src) {
	if(changes4 !== null){
	    changes4.forEach(([row4, prop4, oldValue, newValue]) => {
	        var count = 1; 
             vendor_id = $("#vendor").val();
        
  	      /*if(prop4 == 'id'){
  	         vendor_id = $("#vendor").val();
  	         //console.log(vendor_id); 
  	          if(vendor_id == ''){
  	              alert("Please Select vendor");return false;
  	          }else{
  	                   var state = $("#state_id").val();
  	                   var group_id = $("#group_id").val();
  	                  $.post(admin_url + 'Stock_adjustment/items_change/'+newValue+'/'+group_id+'/'+state).done(function(response){
          	           
          	          response = JSON.parse(response);
          	          console.log(response)
          	          if(response.value.outst_supply_in == 'CS'){
          	              var case_create = 'Case';
          	          }else{
          	               var case_create = 'Create';
          	          }
                      hot4.setDataAtCell(row,1, response.value.description);
          	          hot4.setDataAtCell(row,2, case_create);
          	          hot4.setDataAtCell(row,3, response.value.case_qty);
          	          hot4.setDataAtCell(row,4, '');
          	          hot4.setDataAtCell(row,5, response.value.CaseQty);
          	          hot4.setDataAtCell(row,6, response.basic_r.assigned_rate);
          	          hot4.setDataAtCell(row,7, response.value.taxrate);
          	          hot4.setDataAtCell(row,8, '');
          	          hot4.setDataAtCell(row,9, '');
          	          hot4.setDataAtCell(row,10, '');
          	          hot4.setDataAtCell(row,11, '');
          	           count++; 
  	            });
  	        }
  	      }else if(prop4 == 'CaseQty'){  
  	        var case_q =  newValue*hot4.getDataAtCell(row4,3)
            hot4.setDataAtCell(row4,5, case_q);
             var case_quntity =  case_q*hot4.getDataAtCell(row4,6);
  	       	           var gst = hot4.getDataAtCell(row4,7)
  	       var state = $("#state_id").val();
  	       if(state == 'UP'){
  	          var new_v =  case_quntity
  	          var prec = (gst*new_v)/100;
  	         var devide_gst = gst/2
  	            hot4.setDataAtCell(row4,8, devide_gst);
  	               hot4.setDataAtCell(row4,9, devide_gst);
  	               hot4.setDataAtCell(row4,10, 0);
  	                hot4.setDataAtCell(row4,11, parseFloat(new_v).toFixed(2));
  	       }else{
  	          var new_v =  case_quntity
  	          var prec = (gst*new_v)/100;
  	            hot4.setDataAtCell(row4,8, 0);
  	               hot4.setDataAtCell(row4,9, 0);
  	          hot4.setDataAtCell(row4,10, gst);
  	           hot4.setDataAtCell(row4,11, parseFloat(new_v.toFixed(2)));
  	       }
  	       if(gst == null){
  	            hot4.setDataAtCell(row4,7, 0);
  	            hot4.setDataAtCell(row4,8, 0);
  	               hot4.setDataAtCell(row4,9, 0);
  	               hot4.setDataAtCell(row4,10, 0);
  	       }
  	      }
         else if(prop4 == 'ChallanAmt'){ 
           var t_cases = 0.00;
           var full_total_d = 0;
           var total_value = 0.00;
           var total_cgst = 0;
           var total_sgst = 0;
           var total_igst = 0;
            round_off_v =0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot4.getDataAtCell(row_index, 11)) > 0 || parseFloat(hot4.getDataAtCell(row_index, 11)) < 0){
                total_value += (parseFloat(hot4.getDataAtCell(row_index, 11)));
                }
               if(parseFloat(hot4.getDataAtCell(row_index, 5)) > 0 || parseFloat(hot4.getDataAtCell(row_index, 5)) < 0){
                t_cases += (parseFloat(hot4.getDataAtCell(row_index, 5)));
                }
            }
             $('input[name="t_cases"]').val(t_cases);
             $('input[name="adjust_value"]').val(total_value.toFixed(2));
              }*/
	    });
	}
  });
  //here end table for fresh_stock_return
  
}

function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}
 $('.edit-new-order').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
      //init_journal_entry_table();
    });



$(function(){
  "use strict";
		validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        appValidateForm($(selector), {
            prd_date: 'required',
            vendor: 'required',
        });
    }


});

function customRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if(td.innerHTML != ''){
      td.innerHTML = td.innerHTML + '%'
      td.className = 'htRight';
    }
}

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
	  var selectedId;
	  var optionsList = cellProperties.chosenOptions.data;
	  
	  if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
	      Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	      return td;
	  }

	  var values = (value + "").split("|");
	  value = [];
	  for (var index = 0; index < optionsList.length; index++) {

	      if (values.indexOf(optionsList[index].id + "") > -1) {
	          selectedId = optionsList[index].id;
	          value.push(optionsList[index].AccountID);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}
function customDropdownRenderer2(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
	  var selectedId;
	  var optionsList2 = cellProperties.chosenOptions.data;
	  
	  if(typeof optionsList2 === "undefined" || typeof optionsList2.length === "undefined" || !optionsList2.length) {
	      Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	      return td;
	  }

	  var values = (value + "").split("|");
	  value = [];
	  for (var index = 0; index < optionsList2.length; index++) {

	      if (values.indexOf(optionsList2[index].id + "") > -1) {
	          selectedId = optionsList2[index].id;
	          value.push(optionsList2[index].AccountID);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}
function customDropdownRenderer3(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
	  var selectedId;
	  var optionsList3 = cellProperties.chosenOptions.data;
	  
	  if(typeof optionsList3 === "undefined" || typeof optionsList3.length === "undefined" || !optionsList3.length) {
	      Handsontable3.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	      return td;
	  }

	  var values = (value + "").split("|");
	  value = [];
	  for (var index = 0; index < optionsList3.length; index++) {

	      if (values.indexOf(optionsList3[index].id + "") > -1) {
	          selectedId = optionsList3[index].id;
	          value.push(optionsList3[index].AccountID);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}



  
});

</script>