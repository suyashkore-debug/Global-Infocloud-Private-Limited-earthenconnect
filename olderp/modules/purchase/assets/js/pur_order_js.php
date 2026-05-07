<script>

function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}
 $('.edit-new-order').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
      //init_journal_entry_table();
    });

$( "#vendor" ).change(function() {
   if(this.value != 0){
    $.post(admin_url + 'purchase/get_vendor_data/'+this.value).done(function(response){
       
        var last_month = <?php echo date("n", strtotime("previous month")); ?>;
       
    response = JSON.parse(response);
     var ActBal = parseFloat(response.vendor.BAL1) + parseFloat(response.vendor.BAL2)+parseFloat(response.vendor.BAL3)+parseFloat(response.vendor.BAL4)+parseFloat(response.vendor.BAL5)+parseFloat(response.vendor.BAL6)+parseFloat(response.vendor.BAL7)+parseFloat(response.vendor.BAL8)+parseFloat(response.vendor.BAL9)+parseFloat(response.vendor.BAL10)+parseFloat(response.vendor.BAL11)+parseFloat(response.vendor.BAL12)+parseFloat(response.vendor.BAL13);
      
      $("#c_name").val(response.vendor.company);
      $("#gst_num").val(response.vendor.vat);
      if(ActBal> 0){
          var new_ActBal = ActBal.toFixed(2)+'Dr';
      }else{
          var new_ActBal = ActBal.toFixed(2)+'Cr';
      }
      $("#c_balance").val(new_ActBal);
      $("#address").val(response.vendor.address);
      $("#station_n").val(response.vendor.StationName);
      $("#address2").val(response.vendor.Address3);
      $("#city").val(response.vendor.city_name);
      $("#state_c").val(response.vendor.state);
      $("#state_f").val(response.vendor.state_name);
      var igst = $("#IGST_amt").val();
      var sgst = $("#SGST_AMT").val();
      var cgst = $("#CGST_amt").val();
      var grand_total1 = 0;
      var total_cgst1 = 0;
      var total_sgst1 = 0;
      var total_igst1 = 0;
      
      if(response.vendor.state == "UP"){
            var new_igst_sum = 0.00;
            var new_cgst_sum = 0.00;
            var new_sgst_sum = 0.00;
              for(var row_index = 0; row_index <= 40; row_index++) {
                var igst = parseFloat(hot.getDataAtCell(row_index, 12));
                if(!isNaN(igst)){
                    
                    var new_igst = 0.00;
                    //alert(igst);
                    if(igst == "0" || igst == "0.00" || igst == null){
                        var cgst = parseFloat(hot.getDataAtCell(row_index, 10));
                        var sgst = parseFloat(hot.getDataAtCell(row_index, 11));
                        hot.setDataAtCell(row_index,10, cgst);
              	        hot.setDataAtCell(row_index,11, sgst);
              	        hot.setDataAtCell(row_index,12, new_igst);
              	        new_igst_sum += new_igst;
              	        new_cgst_sum += cgst;
              	        new_sgst_sum += sgst;
              	        /*var total_cgst = $('input[name="CGST_amt"]').val();
                        var total_sgst = $('input[name="SGST_amt"]').val();
                        $('input[name="CGST_amt"]').val(total_cgst.toFixed(2));
                        $('input[name="SGST_AMT"]').val(total_sgst.toFixed(2));
                        $('input[name="IGST_amt"]').val('0.00');*/
                    }else{
                        var divide_igst = igst / 2 ;
                        hot.setDataAtCell(row_index,10, divide_igst);
              	        hot.setDataAtCell(row_index,11, divide_igst);
              	        hot.setDataAtCell(row_index,12, new_igst);
              	        new_igst_sum += new_igst;
              	        new_cgst_sum += divide_igst;
              	        new_sgst_sum += divide_igst;
              	        /*var total_igst = $('input[name="IGST_amt"]').val();
                        var new_cgst_total = parseFloat(total_igst) / 2;
                        $('input[name="CGST_amt"]').val(new_cgst_total.toFixed(2));
                        $('input[name="SGST_AMT"]').val(new_cgst_total.toFixed(2));
                        $('input[name="IGST_amt"]').val('0.00');*/
                    }
                    
                }
            }
      }else{
            var new_igst_sum = 0.00;
            var new_cgst_sum = 0.00;
            var new_sgst_sum = 0.00;
          for(var row_index = 0; row_index <= 40; row_index++) {
                var igst = parseFloat(hot.getDataAtCell(row_index, 12));
                var cgst = parseFloat(hot.getDataAtCell(row_index, 10));
                if(!isNaN(igst)){
                    var new_sgst = 0.00;
                    var new_cgst = 0.00;
                    
                    if(cgst == "0" || cgst == "0.00" || cgst == null){
                        hot.setDataAtCell(row_index,12, igst);
                        hot.setDataAtCell(row_index,10, new_cgst);
              	        hot.setDataAtCell(row_index,11, new_sgst);
              	        new_igst_sum += igst;
              	        new_cgst_sum += new_cgst;
              	        new_sgst_sum += new_sgst;
              	        /*var total_igst = $('input[name="IGST_amt"]').val();
                        $('input[name="CGST_amt"]').val('0.00');
                        $('input[name="SGST_AMT"]').val('0.00');
                        $('input[name="IGST_amt"]').val(total_igst.toFixed(2));*/
                    }else{
                        var sgst = parseFloat(hot.getDataAtCell(row_index, 11));
                        var cgst = parseFloat(hot.getDataAtCell(row_index, 10));
                        var gst = sgst + cgst;
                        var new_igst = gst;
                        hot.setDataAtCell(row_index,10, new_cgst);
              	        hot.setDataAtCell(row_index,11, new_sgst);
              	        hot.setDataAtCell(row_index,12, new_igst);
              	        new_igst_sum += new_igst;
              	        new_cgst_sum += new_cgst;
              	        new_sgst_sum += new_sgst;
              	        /*var total_cgst = $('input[name="CGST_amt"]').val();
                        var total_sgst = $('input[name="SGST_AMT"]').val();
                        var new_igst_total = parseFloat(total_cgst) + parseFloat(total_sgst);
                        $('input[name="CGST_amt"]').val('0.00');
                        $('input[name="SGST_AMT"]').val('0.00');
                        $('input[name="IGST_amt"]').val(new_igst_total.toFixed(2));*/
                    }
                    
                }
            }
      }
        $('input[name="CGST_amt"]').val(new_cgst_sum.toFixed(2));
        $('input[name="SGST_AMT"]').val(new_sgst_sum.toFixed(2));
        $('input[name="IGST_amt"]').val(new_igst_sum.toFixed(2));
      
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
            pro_orderid: 'required',
            pur_order_number: 'required',
            prd_date: {
				remote: {
					url: site_url + "admin/misc/checkpurch_val",
					type: 'post',
					data: {
						order_date: function() {
							return $('input[name="prd_date"]').val();
						},
						PurchID: function() {
							return $('input[name="pur_order_number"]').val();
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


function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
<?php if(!empty($pur_order_detail)){ ?>
    var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;
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
              data: <?php echo json_encode($item_code); ?>
          }
        },
        { 
          data: 'description',
          type: 'text',
           width: 150,
          readOnly: true
        },
        {
          data: 'name',
          type: 'text',
          
          width: 150,
         
          readOnly: true
     
        },
        {
          data: 'CaseQty',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
           readOnly: true
        },
         {
          data: 'PurchRate',
          type: 'numeric',
          width: 60,
      
        },
        {
          data: 'Cases',
          type: 'numeric',
          width: 50,
      
        },
        {
          data: 'OrderQty',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
        {
          data: 'Disc',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'DiscAmt',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
        },
        {
          data: 'gst',
          type: 'text',
           width: 50,
          readOnly: true
        },
         {
          data: 'cgstamt',
          type: 'numeric',
          
           width: 60,
          readOnly: true
        },
            {
          data: 'sgstamt',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 60,
          readOnly: true
        },
            {
          data: 'igstamt',
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
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
    //   autoWrapRow: true,
    //   rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('ItemID'); ?>',
        '<?php echo _l('ItemName'); ?>',
        '<?php echo _l('MainItemGroupName'); ?>',
        '<?php echo _l('CaseQty'); ?>',
        '<?php echo _l('PurchRate'); ?>',
        '<?php echo _l('Cases'); ?>',
        '<?php echo _l('Qty'); ?>',
        '<?php echo _l('Disc%'); ?>',
        '<?php echo _l('DiscAmt'); ?>',
        '<?php echo _l('GST%'); ?>',
        '<?php echo _l('CGSTAMT'); ?>',
        '<?php echo _l('SGSTAMT'); ?>',
        '<?php echo _l('IGSTAMT'); ?>',
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
        // if(newValue != ''){
             vendor_id = $("#vendor").val();
             
          $.post(admin_url + 'purchase/items_vendor_check_tcs/'+vendor_id).done(function(result){
  	              result_data = JSON.parse(result);
              })
  	      if(prop == 'id'){
  	         vendor_id = $("#vendor").val();
  	         if(newValue == null){
  	                hot.setDataAtCell(row,9, '0');
                  	hot.setDataAtCell(row,10, '0');
                  	hot.setDataAtCell(row,11, '0');
                  	hot.setDataAtCell(row,12, '0');
                  	hot.setDataAtCell(row,13, '0');
  	                hot.setDataAtCell(row,1, '');
                  	hot.setDataAtCell(row,2, '');
                  	hot.setDataAtCell(row,3, '0');
                  	hot.setDataAtCell(row,4, '0');
                  	hot.setDataAtCell(row,5, '0');
                  	hot.setDataAtCell(row,6, '0');
                  	hot.setDataAtCell(row,7, '0');
                  	hot.setDataAtCell(row,8, '0');
                  	
  	         }else{
          	         if(vendor_id == ''){
          	              alert("Please Select vendor");return false;
          	          }else{
          	          $.post(admin_url + 'purchase/items_vendor_check/'+newValue+'/'+vendor_id).done(function(response){
          	              response = JSON.parse(response);
          	             
          	              //if(response.items > ''){
          	     //   if(count == 1){
                         
                    //  }
          	                  count++;
          	                  $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
                  	           
                  	          response = JSON.parse(response);
                              hot.setDataAtCell(row,1, response.value.description);
                  	          hot.setDataAtCell(row,2, response.value.name);
                  	          hot.setDataAtCell(row,3, response.value.case_qty);
                  	          hot.setDataAtCell(row,4, '');
                  	          hot.setDataAtCell(row,5, '');
                  	          hot.setDataAtCell(row,6, '');
                  	          hot.setDataAtCell(row,7, '0.00');
                  	          hot.setDataAtCell(row,8, '0.00');
                  	          hot.setDataAtCell(row,9, response.value.taxrate);
                  	          hot.setDataAtCell(row,10, '');
                  	          hot.setDataAtCell(row,11, '');
                  	          hot.setDataAtCell(row,12, '');
                  	          hot.setDataAtCell(row,13, '');
                  	          /*hot.setDataAtCell(row,5, response.value.purchase_price*hot.getDataAtCell(row,4));*/
                  	          
                  	           count++; 
          	            });
          	              /*}else{
          	                   alert('Selected Item Division not assign to Vendor..')
          	              }*/
          	          });
          	      
          	        }
  	         }
  	          
  	      }else if(prop == 'PurchRate'){
  	          
  	        hot.setDataAtCell(row,13, newValue*hot.getDataAtCell(row,6));
  	       var state = $("#state_c").val();
  	       if(state == 'UP'){
  	           var gst = hot.getDataAtCell(row,9)
  	          var new_v =  hot.getDataAtCell(row,6)*newValue
  	         // alert(new_v)
  	          var prec = (gst*new_v)/100;
  	         var devide_gst = prec/2
  	            hot.setDataAtCell(row,10, devide_gst);
  	            hot.setDataAtCell(row,11, devide_gst);
  	            hot.setDataAtCell(row,12, '0');
  	            hot.setDataAtCell(row,13, (parseFloat(new_v)));
  	       }else{
  	             var gst = hot.getDataAtCell(row,9)
  	          var new_v =  hot.getDataAtCell(row,6)*newValue
  	          var prec = (gst*new_v)/100;
  	          hot.setDataAtCell(row,10, '0');
  	          hot.setDataAtCell(row,11, '0');
  	          hot.setDataAtCell(row,12, prec);
  	          hot.setDataAtCell(row,13, (parseFloat(new_v).toFixed(2)));
  	       }
  	      }else if(prop == 'Cases'){
  	          vendor_id = $("#vendor").val();
  	         /* $.post(admin_url + 'purchase/items_vendor_check_tcs/'+vendor_id).done(function(result){
  	              result_data = JSON.parse(result);
              })*/
  	        var case_q =  newValue*hot.getDataAtCell(row,3)
            hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,3));
  	       var state = $("#state_c").val();
  	       if(state == 'UP'){
  	           var gst = hot.getDataAtCell(row,9)
  	          var new_v =  hot.getDataAtCell(row,4)*case_q
  	          var prec = (gst*new_v)/100;
  	         var devide_gst = prec/2
  	            hot.setDataAtCell(row,10, devide_gst);
  	            hot.setDataAtCell(row,11, devide_gst);
  	            hot.setDataAtCell(row,12, '0');
  	             hot.setDataAtCell(row,13, (parseFloat(new_v).toFixed(2)));
  	       }else{
  	             var gst = hot.getDataAtCell(row,9)
  	          var new_v =  hot.getDataAtCell(row,4)*case_q
  	          var prec = (gst*new_v)/100;
  	          hot.setDataAtCell(row,10, '0');
  	          hot.setDataAtCell(row,11, '0');
  	          hot.setDataAtCell(row,12, prec);
  	          hot.setDataAtCell(row,13, (parseFloat(new_v).toFixed(2)));
  	       }
  	      }
          else if(prop == 'DiscAmt'){
            var TaxableAmt = hot.getDataAtCell(row,4)*hot.getDataAtCell(row,6);
            var NewTxableAmt =   parseFloat(TaxableAmt) - parseFloat(newValue);
            var state = $("#state_c").val();
  	       if(state == 'UP'){
  	           var gst = hot.getDataAtCell(row,9);
  	          var prec = (gst*NewTxableAmt)/100;
  	         var devide_gst = prec/2
  	            hot.setDataAtCell(row,10, parseFloat(devide_gst).toFixed(2));
  	            hot.setDataAtCell(row,11, parseFloat(devide_gst).toFixed(2));
  	            hot.setDataAtCell(row,12, '0');
  	       }else{
  	             var gst = hot.getDataAtCell(row,9);
  	          var prec = (gst*NewTxableAmt)/100;
  	          hot.setDataAtCell(row,10, '0');
  	          hot.setDataAtCell(row,11, '0');
  	          hot.setDataAtCell(row,12, parseFloat(prec).toFixed(2));
  	       }
           updateValue();
          }else if(prop == 'ChallanAmt'){
            updateValue();
         }else if(prop == 'cgstamt'){
             updateValue();
         }else if(prop == 'igstamt'){
             updateValue();
         }
	    });
	}
  });
  function updateValue(){
    var grand_total = 0;
            var total_cgst = 0;
            var total_sgst = 0;
            var total_igst = 0;
            var totalDisc = 0;
            
            for (var row_index = 0; row_index <= 40; row_index++) {
                if(parseFloat(hot.getDataAtCell(row_index, 13)) > 0){
                    grand_total += (parseFloat(hot.getDataAtCell(row_index, 13)));
                }
                if(parseFloat(hot.getDataAtCell(row_index, 10)) > 0){
                    total_cgst += (parseFloat(hot.getDataAtCell(row_index, 10)));
                }
                if(parseFloat(hot.getDataAtCell(row_index, 11)) > 0){
                    total_sgst += (parseFloat(hot.getDataAtCell(row_index, 11)));
                }
                if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
                    total_igst += (parseFloat(hot.getDataAtCell(row_index, 12)));
                }
                if(parseFloat(hot.getDataAtCell(row_index, 8)) > 0){
                    totalDisc += (parseFloat(hot.getDataAtCell(row_index, 8)));
                }
            }
        
        var FinalAmt = (grand_total + total_igst + total_sgst + total_cgst) - totalDisc;
        result_data = '';
         $.post(admin_url + 'purchase/items_vendor_check_tcs/'+vendor_id).done(function(result){
  	              result_data = JSON.parse(result);
              })
        if(result_data){
            
            var tcsAmt = (FinalAmt*(result_data.tcs_prec))/100;
             var tcs_prec = result_data.tcs_prec;
        }else{
            var tcs_prec = $('input[name="tcs_pre"]').val();
            var tcsAmt = (FinalAmt*(tcs_prec))/100;
        }
        var grand_total_with_tcs = FinalAmt + tcsAmt;
        grand_total_roundAmt = Math.round(grand_total_with_tcs);
        round_offAmt =  grand_total_roundAmt - grand_total_with_tcs;
         
            $('input[name="dc_total"]').val(totalDisc.toFixed(2));
             $('input[name="tcs_pre_data"]').val(tcsAmt);
             $('input[name="tcs_pre"]').val(tcs_prec);
             $('input[name="Round_OFF"]').val(round_offAmt.toFixed(2));
             $('input[name="total_mn"]').val(grand_total.toFixed(2));
             //$('input[name="dc_total"]').val(numberWithCommas(total_d));
             $('input[name="CGST_amt"]').val(total_cgst.toFixed(2));
             $('input[name="SGST_AMT"]').val(total_sgst.toFixed(2));
             $('input[name="IGST_amt"]').val(total_igst.toFixed(2));
             
              var Freight_AMT = $('#Freight_AMT').val();
              if(Freight_AMT == ''){
                  Freight_AMT = 0;
              }
               var Other_amt = $('#Other_amt').val();
               if(Other_amt == ''){
                   Other_amt = 0;
               }
             var all_total = parseFloat(grand_total_roundAmt)+parseFloat(Freight_AMT)+parseFloat(Other_amt);
             $('input[name="Invoice_amt"]').val(all_total.toFixed(2));  
  }
 $('#Other_amt').click(function(e) {
      var Other_amt = $(this).val();
   $('#Other_amt_hidden').val(Other_amt)
 });
 $('#Other_amt').on('blur', function() {
        var InvAmt = $('input[name="Invoice_amt"]').val();
        var Freight_AMT = $('#Freight_AMT').val();
        var PurchAmt = $('input[name="total_mn"]').val();
        var DiscAmt = $('input[name="dc_total"]').val();
        var CGSTAmt = $('input[name="CGST_amt"]').val();
        var SGSTAmt = $('input[name="SGST_AMT"]').val();
        var IGSTAmt = $('input[name="IGST_amt"]').val();
        var Other_amt = $('#Other_amt_hidden').val();
        var Tcs = $('#tcs_pre_data').val();
        var RAmt = $('input[name="Round_OFF"]').val();
        if(Tcs == ""){
            var TcsAmt = 0;
        }else{
            var TcsAmt = Tcs;
        }
        c_value1 = $(this).val();
       if(c_value1 == ''){
           c_value1 = 0;
       }
       if(Other_amt == ''){
           Other_amt = 0;
       }
       if(Freight_AMT == ''){
           Freight_AMT = 0;
       }
       if(RAmt == ''){
           RAmt = 0;
       }
       
        //var newAmt =parseFloat(InvAmt)+parseFloat(c_value1)-parseFloat(Other_amt);
        var newAmt = (parseFloat(PurchAmt)-parseFloat(DiscAmt)+parseFloat(TcsAmt)+parseFloat(CGSTAmt)+parseFloat(SGSTAmt)+parseFloat(IGSTAmt)) + (parseFloat(c_value1) + parseFloat(Freight_AMT) + parseFloat(RAmt));
        $('input[name="Invoice_amt"]').val(newAmt);
});

 $('#Freight_2').on('change', function() {
    //  alert($(this).val())
    $.post(admin_url + 'purchase/get_accounts_freightid/'+$(this).val()).done(function(result){
  	              result_data = JSON.parse(result);
  	             // console.log(result_data.items.AccountID)
  	              
  	               $('input[name="Freight_1"]').val(result_data.items.AccountID);
              })
});

$('#tcs_pre_data').click(function(e) {
      var tcs_pre_data = $(this).val();
   $('#tcs_old_value').val(tcs_pre_data)
 });
  $('#tcs_pre_data').on('blur', function() {
    var InvAmt = $('input[name="Invoice_amt"]').val();
    var tcs_old_value = $('#tcs_old_value').val();
    c_value = $(this).val();
   if(c_value == ''){
       c_value = 0;
   }
   if(tcs_old_value == ''){
       tcs_old_value  = 0;
   }
     var newAmt =parseFloat(InvAmt)+parseFloat(c_value)-parseFloat(tcs_old_value);
             $('input[name="Invoice_amt"]').val(newAmt);
});
function isNumber(evt) {
      console.log('test')
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
 $('#Other_ac1').on('change', function() {
    //  alert($(this).val())
    $.post(admin_url + 'purchase/get_accounts_othertid/'+$(this).val()).done(function(result){
  	              result_data = JSON.parse(result);
  	             // console.log(result_data)
  	              
  	               $('input[name="Other_ac"]').val(result_data.items.AccountID);
              })
});
$('#Freight_AMT').click(function(e) {
      var Freight_AMT = $(this).val();
   $('#Freight_AMT_hidden').val(Freight_AMT)
 });
 
 $('#Freight_AMT').on('blur', function() {
    var Freight_AMT = $('#Freight_AMT_hidden').val();
    var PurchAmt = $('input[name="total_mn"]').val();
    var DiscAmt = $('input[name="dc_total"]').val();
    var CGSTAmt = $('input[name="CGST_amt"]').val();
    var SGSTAmt = $('input[name="SGST_AMT"]').val();
    var IGSTAmt = $('input[name="IGST_amt"]').val();
    var InvAmt = $('input[name="Invoice_amt"]').val();
    var Other_amt = $('#Other_amt').val();
    var Tcs = $('#tcs_pre_data').val();
    var RAmt = $('input[name="Round_OFF"]').val();
        if(Tcs == ""){
            var TcsAmt = 0;
        }else{
            var TcsAmt = Tcs;
        }
     c_value2 = $(this).val();
   if(c_value2 == ''){
       c_value2 = 0;
   }
   if(Freight_AMT == ''){
       Freight_AMT = 0;
   }
   if(Other_amt == ''){
       Other_amt = 0;
   }
   if(RAmt == ''){
        RAmt = 0;
    }
    //var newAmt =parseFloat(InvAmt)+parseFloat(c_value2)-parseFloat(Freight_AMT);
    var newAmt = (parseFloat(PurchAmt)-parseFloat(DiscAmt)+parseFloat(TcsAmt)+parseFloat(CGSTAmt)+parseFloat(SGSTAmt) + parseFloat(IGSTAmt)) + (parseFloat(c_value2)) + (parseFloat(Other_amt)) + (parseFloat(RAmt));
      $('input[name="Invoice_amt"]').val(newAmt);
});

$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(JSON.stringify(hot.getData()));   
});

<?php } else{ ?>



function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
// var dataobjj = 'test'; 
	var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;
	console.log(dataObject);
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
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'Disc',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
        },
        {
          data: 'DiscAmt',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
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
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
        },
        
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 90,
          readOnly: true
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
      maxRows: 40,
      rowHeaders: true,
      colWidths: [0,0,200,50,100,50,100,50,100,50,100,100],
      colHeaders: [
      	'',
        '',
        '<?php echo _l('ItemID'); ?>',
        '<?php echo _l('ItemName'); ?>',
        '<?php echo _l('MainItemGroupName'); ?>',
        '<?php echo _l('CaseQty'); ?>',
        '<?php echo _l('PurchRate'); ?>',
        '<?php echo _l('Cases'); ?>',
        '<?php echo _l('Qty'); ?>',
        '<?php echo _l('Disc%'); ?>',
        '<?php echo _l('DiscAmt'); ?>',
        '<?php echo _l('GST%'); ?>',
        '<?php echo _l('CGSTAMT'); ?>',
        '<?php echo _l('SGSTAMT'); ?>',
        '<?php echo _l('IGSTAMT'); ?>',
        '<?php echo _l('Amount'); ?>',
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
	          hot.setDataAtCell(row,5, response.value.purchase_price);
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
          for (var row_index = 0; row_index <= 40; row_index++) {
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
for (var row_index = 0; row_index <= 40; row_index++) {
  if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
    total_money += (parseFloat(hot.getDataAtCell(row_index, 12)));
  }
  
 
}
$('input[name="total_mn"]').val(numberWithCommas(total_money));


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

<script type="text/javascript">
    $('#Freight_AMT').on('keypress',function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
            event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
            event.preventDefault();
        }
    });
    
    $('#Other_amt').on('keypress',function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
            event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
            event.preventDefault();
        }
    });
</script>