
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
    
    $("#act_name").focus();
    $( "#act_name" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/damage_entry/accountlist",
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
      var old_AccountID = $('#old_vendor').val();
      if(empty(old_AccountID)){
          $('#act_name').val(ui.item.value);
          $('#c_name').val(ui.item.label);
            return false;
      }else{
            $('#act_name').val(ui.item.value);
            return false;
        }
    }
    });

 $('#act_name').on('blur', function () {
      
        var AccountID = $(this).val();
        var old_AccountID = $('#old_vendor').val();
        if(empty(old_AccountID)){
            if(empty(AccountID)){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/damage_entry/get_Account_Details",
                    type: 'post',
                    dataType: "json",
                    data: {
                      AccountID: AccountID,
                    },
                    success: function( data ) {
                        if(empty(data)){
                            alert('AccountID not found.');
                            $("#act_name").val('');
                            $("#act_name").focus();
                        }else{
                           $('#c_name').val(data.company);
                                  $('#address1').val(data.address); 
                                  $('#address2').val(data.Address3);
                                  $('#state_id').val(data.state);
                                  $("#city").val(data.city);
                                 
                                  $('#state').val(data.state_name);
                                  $('#group_name').val(data.aname);
                                  $('#group_id').val(data.DistributorType);
                                   if(data.LocationTypeID == 1){
                                      var location_data = 'Local';
                                  }else{
                                       var location_data = 'OutStation';
                                  }
                                 $("#location_type").val(location_data);
                                 
                                 
                        }
                    }
                });
            }
            
        }else{
            if(old_AccountID == AccountID){
                
            }else{
                var Conform = myFunction();
                if(Conform == true){
                    $.ajax({
                        url: "<?=base_url()?>admin/damage_entry/get_Account_Details",
                        type: 'post',
                        dataType: "json",
                        data: {
                          AccountID: AccountID,
                        },
                        success: function( data ) {
                            if(empty(data)){
                                alert('AccountID not found.');
                                $("#act_name").val(old_AccountID);
                            }else{
                                  $('#c_name').val(data.company);
                                  $('#address1').val(data.address); 
                                  $('#address2').val(data.address2);
                                  $('#state_id').val(data.state);
                                  $("#city").val(data.city);
                                 
                                  $('#state').val(data.state_name);
                                  $('#group_name').val(data.aname);
                                  $('#group_id').val(data.DistributorType);
                                   if(data.LocationTypeID == 1){
                                      var location_data = 'Local';
                                  }else{
                                       var location_data = 'OutStation';
                                  }
                                 $("#location_type").val(location_data);
                                                        
                            }
                        }
                    });
                }else{
                    $('#act_name').val(old_AccountID);
                }
            }
        }
        
    });
    function myFunction() {
    let text = "Do you really want to change account?";
      if (confirm(text) == true) {
        /*text = "You pressed OK!";*/
        return true;
      } else {
        //text = "You canceled!";
        return false;
      }
      
    }
    $("#vendor" ).change(function() {
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
               var location_data = 'OutStation';
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



$(function(){
  "use strict";
		validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        appValidateForm($(selector), {
            prd_date: {
				remote: {
					url: site_url + "admin/misc/checkdamage_val",
					type: 'post',
					data: {
						order_date: function() {
							return $('input[name="prd_date"]').val();
						},
						DamageID: function() {
							return $('input[name="adj_id"]').val();
						}
					}
				}
			},
            act_name: 'required',
        });
    }


});


function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

<?php if(!isset($pur_order)){
 ?>	

<?php if(!empty($damage_entry_detail)){ ?>
    var dataObject = <?php echo html_entity_decode($damage_entry_detail); ?>;
    var InEdit = 1;
<?php }else{ ?>
    var dataObject = []; 
    var InEdit = 0;
<?php }?>

    var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;


    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'ItemID',
          type: 'text',
          width: 100,
          
        },
        { 
            data: 'item_code',
            renderer: customDropdownRenderer2,
            editor: "chosen",
            width: 150,
            chosenOptions: {
              data:  <?php echo json_encode($item_code); ?>
            }
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
          data: 'OrderQty',
          type: 'numeric',
          width: 50,
      
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
          data: 'taxrate',
          type: 'text',
           width: 50,
          readOnly: true
        },
         {
          data: 'cgst',
          type: 'numeric',
          
           width: 60,
          readOnly: true
        },
          {
          data: 'cgstamt',
          type: 'numeric',
          
           width: 60,
          readOnly: true
        },
            {
          data: 'sgst',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
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
          data: 'igst',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
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
          data: 'NetChallanAmt',
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
      height:'300px',
    //   autoWrapRow: true,
    //   rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 50,
      maxRows: 70,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('ItemId'); ?>',
        '<?php echo _l('ItemName'); ?>',
        '<?php echo _l('Case/Create'); ?>',
        '<?php echo _l('PackQty'); ?>',
        '<?php echo _l('DmgQty'); ?>',
        
        '<?php echo _l('BasicRate'); ?>',
        '<?php echo _l('GST%'); ?>',
        
        '<?php echo _l('CGST%'); ?>',
        '<?php echo _l('CGSTAMT'); ?>',
        '<?php echo _l('SGST%'); ?>',
        '<?php echo _l('SGSTAMT'); ?>',
        '<?php echo _l('IGST%'); ?>',
         '<?php echo _l('IGSTAMT'); ?>',
        '<?php echo _l('DmgAmt'); ?>',
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
            vendor_id = $("#act_name").val();
             
  	        if(prop == 'ItemID'){
  	          vendor_id = $("#act_name").val();
  	          if(vendor_id == ''){
  	              alert("Please Select vendor");return false;
  	          }else{
  	             /*var AccountName = hot.getDataAtCell(row,1);
                if(AccountName == null){*/
                    //alert(AccountName);
                    var state = $("#state_id").val();
  	                   var group_id = $("#group_id").val();
  	                  $.post(admin_url + 'damage_entry/items_change/'+newValue+'/'+group_id+'/'+state).done(function(response){
          	           
          	          response = JSON.parse(response);
          	          if(response.value.outst_supply_in == 'CS'){
          	              var case_create = 'Case';
          	          }else{
          	               var case_create = 'Create';
          	          }
          	          var AccountName = hot.getDataAtCell(row,1);
                        if(AccountName == null){
                             hot.setDataAtCell(row,1, response.value.item_code);
                        }
                     
          	          hot.setDataAtCell(row,2, case_create);
          	          hot.setDataAtCell(row,3, response.value.case_qty);
          	          hot.setDataAtCell(row,4, '');
          	          if(response.basic_r == null){
          	              hot.setDataAtCell(row,5, '0.00');
          	          }else{
          	              hot.setDataAtCell(row,5, response.basic_r.assigned_rate);
          	          }
          	          
          	          hot.setDataAtCell(row,6, response.value.taxrate);
          	          hot.setDataAtCell(row,8, '');
          	          hot.setDataAtCell(row,9, '');
          	          hot.setDataAtCell(row,10, '');
          	          hot.setDataAtCell(row,11, '');
          	          hot.selectCell(row,4);
          	          
          	           count++; 
  	            });
              //}
  	                   
  	        }
  	      
  	      }else if(prop == 'item_code'){
                hot.setDataAtCell(row,0,newValue);
	      }else if(prop == 'OrderQty'){
	          if(newValue !== '' || newValue !== null){
	              var case_q =  newValue*hot.getDataAtCell(row,5);
  	       	      var gst = hot.getDataAtCell(row,6);
  	              var state = $("#state_id").val();
  	              if(state == 'UP'){

  	                var new_v =  case_q
  	                var prec = (gst*new_v)/100;
  	                var prec_cgst_igst = prec/2;
  	                var devide_gst = gst/2
  	                hot.setDataAtCell(row,7, devide_gst);
  	                hot.setDataAtCell(row,9, devide_gst);
  	                hot.setDataAtCell(row,8, parseFloat(prec_cgst_igst).toFixed(2));
  	                hot.setDataAtCell(row,10, parseFloat(prec_cgst_igst).toFixed(2));
  	                hot.setDataAtCell(row,11, 0);
  	                hot.setDataAtCell(row,12, 0);
  	                hot.setDataAtCell(row,13, (parseFloat(new_v) + prec).toFixed(2));
  	            }else{
  	            
  	                var new_v =  case_q
  	                var prec = (gst*new_v)/100;
  	                hot.setDataAtCell(row,7, 0);
  	                hot.setDataAtCell(row,8, 0);
  	                hot.setDataAtCell(row,9, 0);
  	                hot.setDataAtCell(row,10, 0);
  	                hot.setDataAtCell(row,11, gst);
  	                hot.setDataAtCell(row,12, prec);
  	                hot.setDataAtCell(row,13, ((parseFloat(new_v) + prec)).toFixed(2));
  	            }
	         }
  	      }else if(prop == 'NetChallanAmt'){ 
           var t_cases = 0.00;
           var full_total_d = 0;
           var total_value = 0.00;
           var total_cgst = 0;
           var total_sgst = 0;
           var total_igst = 0;
           var basic_rate =0;
            
            for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 8)) > 0 || parseFloat(hot.getDataAtCell(row_index, 8)) < 0){
                total_cgst += (parseFloat(hot.getDataAtCell(row_index, 8)));
               
              }
              if(parseFloat(hot.getDataAtCell(row_index, 10)) > 0 || parseFloat(hot.getDataAtCell(row_index, 10)) < 0){
                total_sgst += (parseFloat(hot.getDataAtCell(row_index, 10)));
               
              }
              if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0 || parseFloat(hot.getDataAtCell(row_index, 12)) < 0){
                total_igst += (parseFloat(hot.getDataAtCell(row_index, 12)));
               
              }
              if(parseFloat(hot.getDataAtCell(row_index, 13)) > 0 || parseFloat(hot.getDataAtCell(row_index, 13)) < 0){
                total_value += (parseFloat(hot.getDataAtCell(row_index, 13)));
               
              }
              
               if(parseFloat(hot.getDataAtCell(row_index, 4)) > 0 || parseFloat(hot.getDataAtCell(row_index, 4)) < 0){
                t_cases += (parseFloat(hot.getDataAtCell(row_index, 4)));
               
              }
           
            }
            
            basic_rate = parseFloat(total_cgst)+parseFloat(total_sgst)+parseFloat(total_igst);
            basic_rate = total_value-basic_rate;
             $('input[name="cases"]').val(t_cases.toFixed(2));
             $('input[name="cgst_amt"]').val(total_cgst.toFixed(2));
             $('input[name="sgst_amt"]').val(total_sgst.toFixed(2));
             $('input[name="igst_amt"]').val(total_igst.toFixed(2));
             $('input[name="basic_amt"]').val(basic_rate.toFixed(2));
             $('input[name="dmg_amt"]').val(total_value.toFixed(2));
             
          }
	    });
	}
  });
  <?php if(isset($damage_entry_detail)){ ?>
       old_vendor_id = $("#old_vendor").val();
        $( "#act_name" ).blur(function() {
           if(this.value != old_vendor_id){
            //   console.log('working')
              for (var row_index = 0; row_index <= 10; row_index++) {
                   hot.alter('remove_row', row_index);
            
          }
          $("#cases").val('0');
          $("#basic_amt").val('0');
          $("#cgst_amt").val('0');
          $("#sgst_amt").val('0');
          $("#igst_amt").val('0');
          $("#dmg_amt").val('0');
           }
            });
  <?php } ?>
 
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


<?php } else{ ?>

	var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;

  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'ItemID',
          type: 'text',
          width: 100,
          
        },
        { 
            data: 'item_code',
            renderer: customDropdownRenderer2,
            editor: "chosen",
            width: 150,
            chosenOptions: {
              data:  <?php echo json_encode($item_code); ?>
            }
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
          data: 'OrderQty',
          type: 'numeric',
          width: 50,
      
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
          data: 'taxrate',
          type: 'text',
           width: 50,
          readOnly: true
        },
         {
          data: 'cgst',
          type: 'numeric',
          
           width: 60,
          readOnly: true
        },
          {
          data: 'cgstamt',
          type: 'numeric',
          
           width: 60,
          readOnly: true
        },
            {
          data: 'sgst',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
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
          data: 'igst',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
           width: 50,
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
          data: 'NetChallanAmt',
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
      minRows: 50,
      maxRows: 70,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('ItemId'); ?>',
        '<?php echo _l('ItemName'); ?>',
        '<?php echo _l('Case/Create'); ?>',
        '<?php echo _l('PackQty'); ?>',
        '<?php echo _l('DmgQty'); ?>',
        
        '<?php echo _l('BasicRate'); ?>',
        '<?php echo _l('GST%'); ?>',
        
        '<?php echo _l('CGST%'); ?>',
        '<?php echo _l('CGSTAMT'); ?>',
        '<?php echo _l('SGST%'); ?>',
        '<?php echo _l('SGSTAMT'); ?>',
        '<?php echo _l('IGST%'); ?>',
         '<?php echo _l('IGSTAMT'); ?>',
        '<?php echo _l('DmgAmt'); ?>',
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
        if(newValue != ''){
	      if(prop == 'ItemID'){
                    $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
	           
        	          response = JSON.parse(response);
                      hot.setDataAtCell(row,3, response.value.item_code);
        	          hot.setDataAtCell(row,4, response.value.unit_id);
        	          hot.setDataAtCell(row,5, response.value.case_qty);
        	          hot.setDataAtCell(row,6, '');
        	          hot.setDataAtCell(row,8, response.value.purchase_price);
        	          hot.setDataAtCell(row,7, response.value.purchase_price*hot.getDataAtCell(row,6));
        	          hot.selectCell(row,4);
        	        });
	      }else if(prop == 'item_code'){
                hot.setDataAtCell(row,0,newValue);
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

/*function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
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
}*/

function customDropdownRenderer2(instance, td, row, col, prop, value, cellProperties) {
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
	          value.push(optionsList[index].label);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}

</script>