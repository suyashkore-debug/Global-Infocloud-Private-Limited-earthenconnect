<script type="text/javascript">
	var commodity_type_value, data;
(function($) {
	"use strict";

  acc_init_currency();
	appValidateForm($('#journal-entry-form'), {
		journal_date: {
				remote: {
					url: site_url + "admin/misc/checkjournal_val",
					type: 'post',
					data: {
						journal_date: function() {
							return $('input[name="journal_date"]').val();
						},
						VoucheriD: function() {
							return $('input[name="VoucheriD"]').val();
						}
					}
				}
			},
		number: 'required',
		/*journal_date1: {
				remote: {
					url: site_url + "admin/misc/checkjournal_val",
					type: 'post',
					data: {
						journal_date: function() {
							return $('input[name="journal_date1"]').val();
						},
						VoucheriD: function() {
							return $('input[name="VoucheriD"]').val();
						}
					}
				}
			},*/
    });

  <?php if(isset($journal_entry)){ ?>
    data = <?php echo json_encode($journal_entry->details); ?>
  <?php }else{ ?>
  	data = [
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              {"AccountID":"","company":"","dr_cr":"","debit":"","credit":"","description":""},
              
            ];
  <?php } ?>

	var hotElement1 = document.querySelector('#journal_entry_container');

    var commodity_type = new Handsontable(hotElement1, {
      contextMenu: true,
      manualRowMove: true,
      autoWrapRow: true,
      width: '100%',
      height:'400px',
      rowHeights: 5,
      stretchH: 'all',
      defaultRowHeight: 5,
      minRows: 20,
      licenseKey: 'non-commercial-and-evaluation',
      rowHeaders: true,
      autoColumnSize: {
        samplingRatio: 5
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true,
      columnHeaderHeight: 10,
      colWidths: [50, 250, 30, 50, 50, 250],
      rowHeights: 5,
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
                    type: 'text',
                    data: 'dr_cr',
                  },
                  {
                    type: 'numeric',
                    data: 'debit',
                    numericFormat: {
				        pattern: '0,0.00',
				    },
                  },
                  {
                    type: 'numeric',
                    data: 'credit',
                    numericFormat: {
				        pattern: '0,0.00',
				    },
                  },
                  {
                    type: 'text',
                    data: 'description',
                  },
                
                ],
      colHeaders: [
         '<?php echo "AccountID"; ?>',
	    '<?php echo _l('acc_account'); ?>',
	    '<?php echo "Dr/Cr"; ?>',
	    '<?php echo _l('debit'); ?>',
	    '<?php echo _l('credit'); ?>',
	    '<?php echo "Narration"; ?>'
	  ],
      data: data,
      afterChange: (changes) => {
        if(changes != null){
        changes.forEach(([row, prop, oldValue, newValue]) => {
            if(prop == 'dr_cr'){
                if(newValue == ""){
                }else{
                    if(newValue.toUpperCase() == "C"){
                        commodity_type.setDataAtCell(row,3,''); 
                        commodity_type.setDataAtCell(row,4,'0');
                        commodity_type.setCellMeta(row,3,'readOnly',true);
                        commodity_type.setCellMeta(row,4,'readOnly',false);
                    }else if(newValue.toUpperCase() == "D"){
                        commodity_type.setDataAtCell(row,4,'');
                        commodity_type.setDataAtCell(row,3,'0');
                        commodity_type.setCellMeta(row,3,'readOnly',false);
                        commodity_type.setCellMeta(row,4,'readOnly',true);
                    }else{
                        alert('please enter C or D');
                        commodity_type.setDataAtCell(row,2,'');
                    }
                }
            }
            
            if(prop == 'AccountID'){
                var AccountName = commodity_type.getDataAtCell(row,1);
                if(AccountName == ""){
                    if(newValue == null && newValue == ""){
          	            commodity_type.setDataAtCell(row,1, '0');
                        commodity_type.setDataAtCell(row,2, '0');
                        commodity_type.setDataAtCell(row,3, '0');
                        commodity_type.setDataAtCell(row,4, '0');
          	        }else{
              	        $.post(admin_url + 'accounting/AccountChange/'+newValue).done(function(response){
              	            response = JSON.parse(response);
              	            if(response.value == null){
              	                alert('AccountId not found');
              	            }else{
                          	    commodity_type.setDataAtCell(row,1, response.value.AccountID);
                              	commodity_type.setDataAtCell(row,2, '');
                              	commodity_type.setDataAtCell(row,3, '');
                              	commodity_type.setDataAtCell(row,4, '');
              	            }
              	        });
      	            }
                }
  	      }if(prop == 'company'){
                if(newValue !== '' || newValue !== null){
                    commodity_type.setDataAtCell(row,0,newValue);
                    commodity_type.setDataAtCell(row,2,''); 
                    commodity_type.setDataAtCell(row,3,''); 
                    commodity_type.setDataAtCell(row,4,''); 
                    commodity_type.setDataAtCell(row,5,''); 
                }
            }
            /*if(prop == 'debit'){
                var accountID = commodity_type.getDataAtCell(row, 0)
                if(newValue !== '' || newValue !== null){
                    if(empty(accountID) || accountID == null){
                    commodity_type.setDataAtCell(row,2,'0.00');
                    }
                }
                  
            }
            if(prop == 'credit'){
                var accountID = commodity_type.getDataAtCell(row, 0)
                if(newValue !== '' || newValue !== null){
                    if(empty(accountID) || accountID == null){
                    commodity_type.setDataAtCell(row,3,'0.00');
                    }
                }  
            }*/
        });
          var journal_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
          var total_debit = 0, total_credit = 0;

          $.each(journal_entry, function(index, value) {
            if(value[3] != '' && value[3] != null){
                if(value[0] == '' || value[0] == null){
                    
                }else{
                    total_debit += parseFloat(value[3]);
                }
            }
            if(value[4] != '' && value[4] != null){
              
              if(value[0] == '' || value[0] == null){
                    
                }else{
                    total_credit += parseFloat(value[4]);
                }
            }
            
          });
          
          $('.total_debit').html(format_money(total_debit));
          $('.total_credit').html(format_money(total_credit));
        }
      }
    });
    commodity_type_value = commodity_type;

    $('.journal-entry-form-submiter').on('click', function() {
	    $('input[name="journal_entry"]').val(JSON.stringify(commodity_type_value.getData()));
    	var journal_entry = JSON.parse($('input[name="journal_entry"]').val());
      var total_debit = 0, total_credit = 0;
	    $.each(journal_entry, function(index, value) {
        if(value[3] != '' && value[3] != null){
          
          if(value[0] == '' || value[0] == null){
                    
                }else{
                    total_debit += parseFloat(value[3].toFixed(2));
                }
        }
        if(value[4] != '' && value[4] != null){
            if(value[0] == '' || value[0] == null){
                    
                }else{
                    total_credit += parseFloat(value[4].toFixed(2));
                }
        }
      });
      
	    if(parseFloat(total_debit.toFixed(2)) == parseFloat(total_credit.toFixed(2))){
	    	if(parseFloat(total_debit.toFixed(2)) > 0){
	    		$('input[name="amount"]').val(parseFloat(total_debit.toFixed(2)));
	    		$('#journal-entry-form').submit();
	    	}else{
	    		alert('<?php echo _l('you_must_fill_out_at_least_two_detail_lines'); ?>');
	    	}
	    }else{
            alert('<?php echo _l('please_balance_debits_and_credits'); ?>');
            $('.journal-entry-form-submiter').removeAttr('disabled');
	    }
	});
})(jQuery);


function customDropdownRenderer1(instance, td, row, col, prop, value, cellProperties) {
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
  
  if(selectedId == "C"){
      instance.setCellMeta(row,3,'readOnly',true);
      instance.setCellMeta(row,4,'readOnly',false);
  }
  if(selectedId == "D"){
      instance.setCellMeta(row,4,'readOnly',true);
      instance.setCellMeta(row,3,'readOnly',false);
  }

  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
  return td;
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
          value.push(optionsList[index].label);
      }
  }
  value = value.join(", ");

  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
  return td;
}

function calculate_amount_total(){
  "use strict";
  var journal_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
  var total_debit = 0, total_credit = 0;
  $.each(journal_entry, function(index, value) {
    if(value[3] != ''){
      total_debit += parseFloat(value[3]);
    }
    if(value[4] != ''){
      total_credit += parseFloat(value[4]);
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