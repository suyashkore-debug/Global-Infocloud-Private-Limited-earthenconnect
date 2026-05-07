
<script>

function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}
 $('.edit-new-order').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
      $('#transfer-modal').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
      //init_journal_entry_table();
    });

$( "#vendor" ).change(function() {
   if(this.value != 0){
    $.post(admin_url + 'Stock_adjustment/get_vendor_data/'+this.value).done(function(response){
       
     
      response = JSON.parse(response);
     //console.log(response);
      $("#c_name").val(response.vendor.company);
      $("#gst").val(response.vendor.vat);
      if(response.customer_groups_name != null){
           $("#group_name").val(response.customer_groups_name.name);
           $("#group_id").val(response.customer_groups_name.id);
      }
     
      $("#location_type").val(response.client_details.location_type);
      if(response.locations != null){
          if(response.locations.LocationTypeID == 1){
              var location_data = 'Local';
          }else{
               var location_data = 'OutStanding';
          }
         $("#location_type").val(location_data);
      }
     
       $("#address1").val(response.vendor.address);
       $("#address2").val(response.vendor.Address3);
       $("#city").val(response.vendor.city_name);
       $("#station").val(response.vendor.StationName);
    //   $("#state_c").val(response.vendor.state);
      $("#state_id").val(response.vendor.state);
      $("#state").val(response.vendor.state_name);
      
    });
   }
});

function dc_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;
  var tax_order_amount = $('input[name="tax_order_amount"]').val();

  if(tax_order_amount == ''){
    tax_order_amount = '0';
  }

  var grand_total = t_mn - rs + parseFloat(removeCommas(tax_order_amount));

  $('input[name="grand_total"]').val(numberWithCommas(grand_total));

  $('input[name="dc_total"]').val(numberWithCommas(rs));
  $('input[name="after_discount"]').val(numberWithCommas(t_mn - rs));

}

function tax_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;
  var dc_total = $('input[name="dc_total"]').val();
  if(dc_total == ''){
    dc_total = '0';
  }

  var grand_total = t_mn + rs - parseFloat(removeCommas(dc_total));

  $('input[name="tax_order_amount"]').val(numberWithCommas(rs));
  $('input[name="grand_total"]').val(numberWithCommas(grand_total));
}

function dc_total_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = t_mn - parseFloat(removeCommas(invoker.value));

  var tax_order_amount = $('input[name="tax_order_amount"]').val();

  if(tax_order_amount == ''){
    tax_order_amount = '0';
  }

  var grand_total = rs + parseFloat(removeCommas(tax_order_amount));

  $('input[name="grand_total"]').val(numberWithCommas(grand_total));

  $('input[name="after_discount"]').val(numberWithCommas(rs));
}

$(function(){
  "use strict";
		validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        appValidateForm($(selector), {
            pur_order_name: 'required',
            pur_order_number: 'required',
            prd_date: {
				remote: {
					url: site_url + "admin/misc/checkstock_val",
					type: 'post',
					data: {
						Adj_date: function() {
							return $('input[name="prd_date"]').val();
						},
						AdjID: function() {
							return $('input[name="AdjID"]').val();
						}
					}
				}
			},
            vendor: 'required',
            invoce_n: 'required',
        });
    }


});

<?php if(!isset($pur_order)){
 ?>	

function estimate_by_vendor(invoker){
  "use strict";
  var po_number = '';
  if(invoker.value != 0){
    $.post(admin_url + 'purchase/estimate_by_vendor/'+invoker.value).done(function(response){
      response = JSON.parse(response);
      $('select[name="estimate"]').html('');
      $('select[name="estimate"]').append(response.result);
      $('select[name="estimate"]').selectpicker('refresh');
      $('#vendor_data').html('');
      $('#vendor_data').append(response.ven_html);
      $('input[name="pur_order_number"]').val(po_number+'-'+response.company);
      <?php if(get_purchase_option('item_by_vendor') == 1){ ?>
      hot.updateSettings({ 
         columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: response.items
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        }
      
      ],
      });

    <?php } ?>

    });

  }
}

function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
<?php if(!empty($order_detail)){ ?>
    var dataObject = <?php echo html_entity_decode($order_detail); ?>;
<?php }else{ ?>
    var dataObject = [ 
      
    ]; 
<?php }?>
// var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;

    
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;


    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'id',
          renderer: customDropdownRenderer,
          editor: "chosen",
         
          width: 100,
          chosenOptions: {
              data:  <?php echo json_encode($item_code); ?>
          }
        },
        { 
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
       
        {
          data: 'SuppliedIn_data',
          type: 'text',
          
           width: 50,
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
          data: 'BasicRate',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
    
          {
          data: 'taxrate',
          type: 'text',
           width: 50,
          readOnly: true
        },
         {
          data: 'cgst_per',
          type: 'numeric',
          
           width: 60,
          readOnly: true
        },
            {
          data: 'sgst_per',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
            {
          data: 'igst_per',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'ChallanAmt',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
           readOnly: true
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
    //   autoWrapRow: true,
    //   rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 25,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('ItemId'); ?>',
        '<?php echo _l('ItemName'); ?>',
        '<?php echo _l('Case/Create'); ?>',
        '<?php echo _l('PackQty'); ?>',
        '<?php echo _l('Cases'); ?>',
        '<?php echo _l('QTY'); ?>',
        '<?php echo _l('BasicRate'); ?>',
        '<?php echo _l('GST%'); ?>',
        '<?php echo _l('CGST%'); ?>',
        '<?php echo _l('SGST%'); ?>',
        '<?php echo _l('IGST%'); ?>',
        '<?php echo _l('Amount'); ?>',
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
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row, prop, oldValue, newValue]) => {
	        var count = 1; 
       /* if(newValue != ''){*/
             vendor_id = $("#vendor").val();
        
  	      if(prop == 'id'){
  	         vendor_id = $("#vendor").val();
  	          if(vendor_id == ''){
  	              alert("Please Select vendor");return false;
  	          }else{
  	              if(newValue == '' || newValue == null){
  	                  
  	              }else{
  	                  var state = $("#state_id").val();
  	                   var group_id = $("#group_id").val();
  	                  $.post(admin_url + 'Stock_adjustment/items_change/'+newValue+'/'+group_id+'/'+state).done(function(response){
          	           
          	          response = JSON.parse(response);
          	          if(response.basic_r == '' || response.basic_r == null){
          	              alert("rate not assigned for this Item... ");
          	              hot.setDataAtCell(row,0, '');
          	              return false;
          	          }else{
          	              if(response.value.outst_supply_in == 'CS'){
          	              var case_create = 'Case';
          	          }else{
          	               var case_create = 'Create';
          	          }
                    //   hot.setDataAtCell(row,0, response.value.item_code);
                      hot.setDataAtCell(row,1, response.value.description);
          	          hot.setDataAtCell(row,2, case_create);
          	          hot.setDataAtCell(row,3, response.value.case_qty);
          	          hot.setDataAtCell(row,4, '');
          	          hot.setDataAtCell(row,5, response.value.CaseQty);
          	          hot.setDataAtCell(row,6, response.basic_r.assigned_rate);
          	          hot.setDataAtCell(row,7, response.value.taxrate);
          	          hot.setDataAtCell(row,8, '');
          	          hot.setDataAtCell(row,9, '');
          	          hot.setDataAtCell(row,10, '');
          	          hot.setDataAtCell(row,11, '');
          	          /*hot.setDataAtCell(row,5, response.value.purchase_price*hot.getDataAtCell(row,4));*/
          	          
          	           count++;
          	          }
  	            });
  	         }
  	              /*}else{
  	                   alert('Selected Item Division not assign to Vendor..')
  	              }*/
  	         // });
  	      
  	        }
  	      
  	      }else if(prop == 'CaseQty'){  
  	        //console.log(newValue);
  	        var case_q =  newValue*hot.getDataAtCell(row,3)
            hot.setDataAtCell(row,5, case_q);
             var case_quntity =  case_q*hot.getDataAtCell(row,6);
  	       	           var gst = hot.getDataAtCell(row,7)
  	       	           //console.log(gst)
  	       var state = $("#state_id").val();
  	       if(state == 'UP'){

  	          var new_v =  case_quntity
  	         // alert(new_v)
  	          var prec = (gst*new_v)/100;
  	         var devide_gst = gst/2
  	            hot.setDataAtCell(row,8, devide_gst);
  	               hot.setDataAtCell(row,9, devide_gst);
  	               hot.setDataAtCell(row,10, 0);
  	                hot.setDataAtCell(row,11, parseFloat(new_v).toFixed(2));
  	             //   hot.setDataAtCell(row,13, (parseFloat(new_v) + prec));
  	       }else{
  	            
  	          var new_v =  case_quntity
  	          var prec = (gst*new_v)/100;
  	            hot.setDataAtCell(row,8, 0);
  	               hot.setDataAtCell(row,9, 0);
  	          hot.setDataAtCell(row,10, gst);
  	           hot.setDataAtCell(row,11, parseFloat(new_v.toFixed(2)));
  	         //  hot.setDataAtCell(row,13, (parseFloat(new_v) + prec));
  	       }
  	       if(gst == null){
  	            hot.setDataAtCell(row,7, 0);
  	            hot.setDataAtCell(row,8, 0);
  	               hot.setDataAtCell(row,9, 0);
  	               hot.setDataAtCell(row,10, 0);
  	       }
  	      }
         else if(prop == 'ChallanAmt'){ 
           var t_cases = 0.00;
           var full_total_d = 0;
           var total_value = 0.00;
           var total_cgst = 0;
           var total_sgst = 0;
           var total_igst = 0;
            round_off_v =0;
            
            for (var row_index = 0; row_index <= 80; row_index++) {
            
              if(parseFloat(hot.getDataAtCell(row_index, 11)) > 0 || parseFloat(hot.getDataAtCell(row_index, 11)) < 0){
                total_value += (parseFloat(hot.getDataAtCell(row_index, 11)));
               
              }
               if(parseFloat(hot.getDataAtCell(row_index, 5)) > 0 || parseFloat(hot.getDataAtCell(row_index, 5)) < 0){
                t_cases += (parseFloat(hot.getDataAtCell(row_index, 5)));
               
              }
           
            }
     
             $('input[name="t_cases"]').val(t_cases);
             $('input[name="adjust_value"]').val(total_value.toFixed(2));
            
             
             
          }
        /*}else{
            //console.log('test')
        }*/
	    });
	}
  });
  $('#Other_amt').on('change', function() {
    var total_am = $('input[name="Invoice_amt"]').val();
    var Freight_AMT = $('#Freight_AMT').val();
     var all_total = parseFloat(total_am)+parseFloat($(this).val());
             $('input[name="Invoice_amt"]').val(numberWithCommas(all_total));
});

 $('#Freight_2').on('change', function() {
    //  alert($(this).val())
    $.post(admin_url + 'purchase/get_accounts_freightid/'+$(this).val()).done(function(result){
  	              result_data = JSON.parse(result);
  	             // console.log(result_data.items.AccountID)
  	              
  	               $('input[name="Freight_1"]').val(result_data.items.AccountID);
              })
});
 $('#Other_ac1').on('change', function() {
    //  alert($(this).val())
    $.post(admin_url + 'purchase/get_accounts_othertid/'+$(this).val()).done(function(result){
  	              result_data = JSON.parse(result);
  	             // console.log(result_data)
  	              
  	               $('input[name="Other_ac"]').val(result_data.items.AccountID);
              })
});
 $('#Freight_AMT').on('change', function() {
    var total_am = $('input[name="Invoice_amt"]').val();
    var Other_amt = $('#Other_amt').val();
     var all_total = parseFloat(total_am)+parseFloat($(this).val());
             $('input[name="Invoice_amt"]').val(numberWithCommas(all_total));
});
$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(JSON.stringify(hot.getData()));   
});

function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
     hot.alter('remove_row',0,hot.countRows ());
      hot.updateSettings({  
        columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        }
      
      ],
     });
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({          
        data: response.result,
        });

          var total_money = 0;
          for (var row_index = 0; row_index <= 80; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 10)) > 0){
              total_money += (parseFloat(hot.getDataAtCell(row_index, 10)));
            }
          }
          $('input[name="total_mn"]').val(numberWithCommas(total_money));
          $('input[name="dc_percent"]').val(numberWithCommas(response.dc_percent));
          $('input[name="dc_total"]').val(numberWithCommas(response.dc_total));
          $('input[name="after_discount"]').val(numberWithCommas(total_money - response.dc_total));
    });

    
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_estimate'); ?>')
  }
}

function coppy_pur_request(){
  "use strict";
  var pur_request = $('select[name="pur_request"]').val();
  if(pur_request != ''){
     hot.alter('remove_row',0,hot.countRows ());
     hot.updateSettings({
        columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        }
      
      ],
     });
    $.post(admin_url + 'purchase/coppy_pur_request/'+pur_request).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });
        });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_request'); ?>')
  }
}


<?php } else{ ?>

function estimate_by_vendor(invoker){
  "use strict";
  var po_number = '<?php echo html_entity_decode( $pur_order_number); ?>';
  if(invoker.value != 0){
    $.post(admin_url + 'purchase/estimate_by_vendor/'+invoker.value).done(function(response){
      response = JSON.parse(response);
      $('select[name="estimate"]').html('');
      $('select[name="estimate"]').append(response.result);
      $('select[name="estimate"]').selectpicker('refresh');
      $('#vendor_data').html('');
      $('#vendor_data').append(response.ven_html);
      $('input[name="pur_order_number"]').val(po_number+'-'+response.company);
    <?php if(get_purchase_option('item_by_vendor') == 1){ ?>
      hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: response.items
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 90,
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      });
    <?php } ?>
    });

  }
}

function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
// var dataobjj = 'test'; 
	var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
      	{
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          },
          
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
          	  multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 90,
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 80,
      rowHeaders: true,
      colWidths: [0,0,200,50,100,50,100,50,100,50,100,100],
      colHeaders: [
      	'',
        '',
        '<?php echo _l('items'); ?>',
        '<?php echo _l('item_description'); ?>',
        '<?php echo _l('pur_unit'); ?>',
        '<?php echo _l('purchase_unit_price'); ?>',
        '<?php echo _l('purchase_quantity'); ?>',
        '<?php echo _l('subtotal_before_tax'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      hiddenColumns: {
        columns: [0,1],
        indicators: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
	      if(prop == 'item_code'){
	        $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
	           
	          response = JSON.parse(response);
              hot.setDataAtCell(row,3, response.value.long_description);
	          hot.setDataAtCell(row,4, response.value.unit_id);
	          hot.setDataAtCell(row,5, response.value.case_qty);
	          hot.setDataAtCell(row,6, '');
	          hot.setDataAtCell(row,8, response.value.purchase_price);
	          hot.setDataAtCell(row,7, response.value.purchase_price*hot.getDataAtCell(row,6));
	        });
	      }else if(prop == 'quantity'){
          hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,5));
	        hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,5));
	        hot.setDataAtCell(row,12, newValue*hot.getDataAtCell(row,5));
	      }else if(prop == 'unit_price'){
          hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,6));
          hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,6));
          hot.setDataAtCell(row,12, newValue*hot.getDataAtCell(row,6));
        }else if(prop == 'tax'){
	      	$.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
	          response = JSON.parse(response);
	          hot.setDataAtCell(row,9, (response.total_tax*parseFloat(hot.getDataAtCell(row,7)))/100 + parseFloat(hot.getDataAtCell(row,7)));
             hot.setDataAtCell(row,12, (response.total_tax*parseFloat(hot.getDataAtCell(row,7)))/100 + parseFloat(hot.getDataAtCell(row,7)));
	      	});
	      }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,11, (newValue*parseFloat(hot.getDataAtCell(row,9)))/100 );

        }else if(prop == 'discount_money'){
           hot.setDataAtCell(row,12, (parseFloat(hot.getDataAtCell(row,9)) - newValue));
        }else if(prop == 'total_money'){
         var total_money = 0;
          for (var row_index = 0; row_index <= 80; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
              total_money += (parseFloat(hot.getDataAtCell(row_index, 12)));
            }
          }
          $('input[name="total_mn"]').val(numberWithCommas(total_money));
        }
      }
	    });
	}
  });
$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(JSON.stringify(hot.getData()));   
});

id = $('select[name="vendor"]').val();
$.post(admin_url + 'purchase/estimate_by_vendor/'+id).done(function(response){
  response = JSON.parse(response);
  $('select[name="estimate"]').html('');
  $('select[name="estimate"]').append(response.result);
  $('select[name="estimate"]').val(<?php echo html_entity_decode($pur_order->estimate); ?>).change();
  $('select[name="estimate"]').selectpicker('refresh');
  $('#vendor_data').html('');
  $('#vendor_data').append(response.ven_html);
  

});

var total_money = 0;
for (var row_index = 0; row_index <= 80; row_index++) {
  if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
    total_money += (parseFloat(hot.getDataAtCell(row_index, 12)));
  }
  
 
}
$('input[name="total_mn"]').val(numberWithCommas(total_money));

function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
     hot.alter('remove_row',0,hot.countRows ());
     hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 90,
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      });
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });
        var total_money = 0;
        for (var row_index = 0; row_index <= 80; row_index++) {
          if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
            total_money += (parseFloat(hot.getDataAtCell(row_index, 12)));
          }
        }
        $('input[name="total_mn"]').val(numberWithCommas(total_money));
        $('input[name="dc_percent"]').val(numberWithCommas(response.dc_percent));
        $('input[name="dc_total"]').val(numberWithCommas(response.dc_total));
        $('input[name="after_discount"]').val(numberWithCommas(total_money - response.dc_total));  
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_estimate'); ?>')
  }
}

function coppy_pur_request(){
  "use strict";
  var pur_request = $('select[name="pur_request"]').val();
  if(pur_request != ''){
     hot.alter('remove_row',0,hot.countRows ());
     hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 90,
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      });
    $.post(admin_url + 'purchase/coppy_pur_request/'+pur_request).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });
        });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_request'); ?>')
  }
}

<?php } ?>
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
	          value.push(optionsList[index].item_code);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}

</script>