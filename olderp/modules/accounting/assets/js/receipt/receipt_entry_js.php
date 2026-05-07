<script type="text/javascript">
	var commodity_type_value, data;
(function($) {
	"use strict";

  acc_init_currency();
	appValidateForm($('#receipt-entry-form'), {
		receipt_date: {
				remote: {
					url: site_url + "admin/misc/checkreceipt_val",
					type: 'post',
					data: {
						receipt_date: function() {
							return $('input[name="receipt_date"]').val();
						},
						VoucheriD: function() {
							return $('input[name="VoucheriD"]').val();
						}
					}
				}
			},
		receipt_number: 'required',
		ganeral_account: 'required',
		/*receipt_date1: {
				remote: {
					url: site_url + "admin/misc/checkreceipt_val",
					type: 'post',
					data: {
						receipt_date: function() {
							return $('input[name="receipt_date1"]').val();
						},
						VoucheriD: function() {
							return $('input[name="VoucheriD"]').val();
						}
					}
				}
			},*/
    });

  <?php if(isset($receipts_entry)){ ?>
    data = <?php echo json_encode($receipts_entry->details); ?>
  <?php }else{ ?>
  	data = [
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              {"AccountID":"","company":"","debit":"","description":""},
              
            ];
  <?php } ?>

	var hotElement1 = document.querySelector('#receipt_entry_container');

    var commodity_type = new Handsontable(hotElement1, {
      contextMenu: true,
      manualRowMove: true,
      autoWrapRow: true,
      rowHeights: 10,
      stretchH: 'all',
      defaultRowHeight: 10,
      minRows: 20,
      licenseKey: 'non-commercial-and-evaluation',
      width: '100%',
      height:'400px',
      rowHeaders: true,
      autoColumnSize: {
        samplingRatio: 10
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true,
      columnHeaderHeight: 10,
      colWidths: [50, 250, 50, 350],
      rowHeights: 10,
      rowHeaderWidth: [20],
      columns: [
                {
                    type: 'text',
                    data: 'AccountID',
                  },
		          {
			        data: 'company',
			        renderer: customDropdownRenderer,
			        editor: "chosen",
			        chosenOptions: {
			            data: <?php echo json_encode($account_to_select); ?>
			        }
			      },
                  {
                    type: 'numeric',
                    data: 'debit',
                    numericFormat: {
				        pattern: '0,0.00',
				    },
                  },
                  /*{
                    type: 'numeric',
                    data: 'credit',
                    numericFormat: {
				        pattern: '0,0.00',
				    },
                  },*/
                  {
                    type: 'text',
                    data: 'description',
                  },
                
                ],
      colHeaders: [
          '<?php echo "AccountID"; ?>',
	    '<?php echo "Account Name"; ?>',
	    '<?php echo "Amount"; ?>',
	    /*'<?php echo _l('credit'); ?>',*/
	    '<?php echo "Narration"; ?>'
	  ],
      data: data,
      afterChange: (changes) => {
        if(changes != null){
            changes.forEach(([row, prop, oldValue, newValue]) => {
                if(prop == 'AccountID'){
                    var AccountName = commodity_type.getDataAtCell(row,1);
                    if(AccountName == ""){
                        if(newValue == null && newValue == ""){
              	            commodity_type.setDataAtCell(row,1, '0');
                            commodity_type.setDataAtCell(row,2, '0');
                            commodity_type.setDataAtCell(row,3, '0');
              	        }else{
                  	        $.post(admin_url + 'accounting/AccountChange/'+newValue).done(function(response){
                  	            response = JSON.parse(response);
                  	            if(response.value == null){
                  	                alert('AccountID Not available...');
                  	            }else{
                              	    commodity_type.setDataAtCell(row,1, response.value.AccountID);
                                  	commodity_type.setDataAtCell(row,2, '');
                                  	commodity_type.setDataAtCell(row,3, '');
                  	            }
                  	        });
          	            }
                    }
      	        }if(prop == 'company'){
                    if(newValue !== '' || newValue !== null){
                        commodity_type.setDataAtCell(row,0,newValue);
                        commodity_type.setDataAtCell(row,2,''); 
                        commodity_type.setDataAtCell(row,3,''); 
                    }
                }
            })
          var receipts_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
          var total_debit = 0, total_credit = 0;
            
          $.each(receipts_entry, function(index, value) {
            if(value[2] != '' && value[2] != null){
              total_debit += parseFloat(value[2]);
            }
            if(value[2] != '' && value[2] != null){
              total_credit += parseFloat(value[2]);
            }
          });
          
          $('.total_debit').html(format_money(total_debit));
         // $('.total_credit').html(format_money(total_credit));
        }
      }
    });
    commodity_type_value = commodity_type;

    $('.receipt-entry-form-submiter').on('click', function() {
	    $('input[name="receipts_entry"]').val(JSON.stringify(commodity_type_value.getData()));
    	var receipts_entry = JSON.parse($('input[name="receipts_entry"]').val());
      var total_debit = 0, total_credit = 0;
	    $.each(receipts_entry, function(index, value) {
        if(value[2] != '' && value[2] != null){
          total_debit += parseFloat(value[2].toFixed(2));
        }
        /*if(value[2] != '' && value[2] != null){
          total_credit += parseFloat(value[2].toFixed(2));
        }*/
      });
      
      if(total_debit > 0 || total_debit < 0){
	    		$('input[name="amount"]').val(total_debit);
	    		$('#receipt-entry-form').submit();
	    	}else{
	    		alert('<?php echo _l('you_must_fill_out_at_least_two_detail_lines'); ?>');
	    	}
      
	    /*if(total_debit == total_credit){
	    	if(total_debit > 0){
	    		$('input[name="amount"]').val(total_debit);
	    		$('#receipt-entry-form').submit();
	    	}else{
	    		alert('<?php echo _l('you_must_fill_out_at_least_two_detail_lines'); ?>');
	    	}
	    }else{
            alert('<?php echo _l('please_balance_debits_and_credits'); ?>');
	    }*/
	});
})(jQuery);

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
          value.push(optionsList[index].label);
      }
  }
  value = value.join(", ");

  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
  return td;
}

function calculate_amount_total(){
  "use strict";
  var receipts_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
  var total_debit = 0, total_credit = 0;
  $.each(receipts_entry, function(index, value) {
    if(value[2] != ''){
      total_debit += parseFloat(value[2]);
    }
    if(value[3] != ''){
      total_credit += parseFloat(value[3]);
    }
  });

  $('.total_debit').html(format_money(total_debit));
  $('.total_credit').html(format_money(total_credit));
}

// Set the currency for accounting
function acc_init_currency() {
  "use strict";
  
  var selectedCurrencyId = <?php echo html_entity_decode($currency->id); ?>;

  requestGetJSON('misc/get_currency/' + selectedCurrencyId)
      .done(function(currency) {
          // Used for formatting money
          accounting.settings.currency.decimal = currency.decimal_separator;
          accounting.settings.currency.thousand = currency.thousand_separator;
          accounting.settings.currency.symbol = currency.symbol;
          accounting.settings.currency.format = currency.placement == 'after' ? '%v %s' : '%s%v';
      });
}

</script>