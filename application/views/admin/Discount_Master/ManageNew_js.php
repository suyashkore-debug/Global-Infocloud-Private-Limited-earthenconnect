<script>

    $(function(){
        "use strict";
    	validate_purorder_form();
        function validate_purorder_form(selector) {
            selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;
            appValidateForm($(selector), {
                issue_orderid: 'required',
                issue_date: 'required',
                CenterID: 'required',
                WHID:'required',
            });
        }
    });
    
    var dataObject = [];
    var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    
    var hotSettings = {
      data: dataObject,
      columns: [
        
        {
          data: 'id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 180,
          chosenOptions: {
              data: <?php echo json_encode($ItemList); ?>
          }
        },
        
        {
          data: 'Batch',
          type: 'text',
          width:70,
        },
        {
          data: 'sale_qty',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          width:70,
        },
        {
          data: 'rate',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          width:70,
        },
        {
          data: 'value',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          readOnly: true,
          width:90,
        },
        {
          data: 'gst',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          readOnly: true,
          width:70,
        },
        {
          data: 'cgst_amt',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          readOnly: true,
          width:70,
        },
        {
          data: 'sgst_amt',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          readOnly: true,
          width:70,
        },
        {
          data: 'igst_amt',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          readOnly: true,
          width:70,
        },
        {
          data: 'net_amt',
          type: 'numeric',
          numericFormat: {
            pattern: '0.00'
          },
          readOnly: true,
          width:70,
        },
      
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
      colWidths: [40,180,100,60,60,60,60,110,80,80,60,110,60,130],
      colHeaders: [
        '<?php echo "Item"; ?>',
        '<?php echo "Batch"; ?>',
        '<?php echo "Sale Qty(MT)"; ?>',
        '<?php echo "Rate / MT"; ?>',
        '<?php echo "Value"; ?>',
        '<?php echo "GST%"; ?>',
        '<?php echo "CGST Amt"; ?>',
        '<?php echo "SGAT Amt"; ?>',
        '<?php echo "IGST Amt"; ?>',
        '<?php echo "Net Amt"; ?>',
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
	        var StateCode = $("#selected_state").val();
            if(prop == 'id'){
    	        if(StateCode){
    	            $.post(admin_url + 'order/ItemDetails/'+newValue).done(function(response){
                        response = JSON.parse(response);
                        hot.setDataAtCell(row,5, parseFloat(response.taxrate).toFixed(2));
                    });
                    hot.setDataAtCell(row,1, '');
                    hot.setDataAtCell(row,2, 0);
                    hot.setDataAtCell(row,3, 0);
                    hot.setDataAtCell(row,4, 0);
                    hot.setDataAtCell(row,6, 0);
                    hot.setDataAtCell(row,7, 0);
                    hot.setDataAtCell(row,8, 0);
                    hot.setDataAtCell(row,9, 0);
    	        }else{
    	            alert('please select customer first');
    	            //return true;
    	        }
	        }else if(prop == 'sale_qty'){
	            var CGSTAmt = 0;
	            var SGSTAmt = 0;
	            var IGSTAmt = 0;
	            let rate = hot.getDataAtCell(row,3);
	            let gst_per = hot.getDataAtCell(row,5);
	            let sale_qty = newValue;
	            let value = rate * sale_qty;
	            hot.setDataAtCell(row,4, parseFloat(value).toFixed(2));
	            let GSTAmount = (value * gst_per ) / 100;
	            if(StateCode == "MH"){
	                CGSTAmt = GSTAmount / 2;
	                SGSTAmt = GSTAmount / 2;
	            }else{
	                IGSTAmt = GSTAmount;
	            }
	            hot.setDataAtCell(row,6, parseFloat(CGSTAmt).toFixed(2));
	            hot.setDataAtCell(row,7, parseFloat(SGSTAmt).toFixed(2));
	            hot.setDataAtCell(row,8, parseFloat(IGSTAmt).toFixed(2));
	            let NetAmt = value + GSTAmount;
	            hot.setDataAtCell(row,9, parseFloat(NetAmt).toFixed(2));
	        }else if(prop == 'rate'){
	            var CGSTAmt = 0;
	            var SGSTAmt = 0;
	            var IGSTAmt = 0;
	            let rate = newValue;
	            let gst_per = hot.getDataAtCell(row,5);
	            let sale_qty = hot.getDataAtCell(row,2);
	            let value = rate * sale_qty;
	            hot.setDataAtCell(row,4, parseFloat(value).toFixed(2));
	            let GSTAmount = (value * gst_per ) / 100;
	            if(StateCode == "MH"){
	                CGSTAmt = GSTAmount / 2;
	                SGSTAmt = GSTAmount / 2;
	            }else{
	                IGSTAmt = GSTAmount;
	            }
	            hot.setDataAtCell(row,6, parseFloat(CGSTAmt).toFixed(2));
	            hot.setDataAtCell(row,7, parseFloat(SGSTAmt).toFixed(2));
	            hot.setDataAtCell(row,8, parseFloat(IGSTAmt).toFixed(2));
	            let NetAmt = value + GSTAmount;
	            hot.setDataAtCell(row,9, parseFloat(NetAmt).toFixed(2));
	        }else if(prop == 'value'){
	            updateValue();
	        }else if(prop == 'net_amt'){
	            updateValue();
	        }
	    })
	}
});
    function updateValue(){
        let total_qty = 0;
        let total_value = 0;
        let total_cgst = 0;
        let total_sgst = 0;
        let total_igst = 0;
        let total_net = 0;
        
        for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 2)) > 0){
                total_qty += (parseFloat(hot.getDataAtCell(row_index, 2)));
            }
            if(parseFloat(hot.getDataAtCell(row_index, 4)) > 0){
                total_value += (parseFloat(hot.getDataAtCell(row_index, 4)));
            }
            if(parseFloat(hot.getDataAtCell(row_index, 6)) > 0){
                total_cgst += (parseFloat(hot.getDataAtCell(row_index, 6)));
            }
            if(parseFloat(hot.getDataAtCell(row_index, 7)) > 0){
                total_sgst += (parseFloat(hot.getDataAtCell(row_index, 7)));
            }
            if(parseFloat(hot.getDataAtCell(row_index, 8)) > 0){
                total_igst += (parseFloat(hot.getDataAtCell(row_index, 8)));
            }
            if(parseFloat(hot.getDataAtCell(row_index, 9)) > 0){
                total_net += (parseFloat(hot.getDataAtCell(row_index, 9)));
            }
        }
        
        $('input[name="total_qty_in_mt"]').val(total_qty.toFixed(2));
        $('input[name="Total_value"]').val(total_value.toFixed(2));
        $('input[name="total_cgst_amt"]').val(total_cgst.toFixed(2));
        $('input[name="total_sgst_amt"]').val(total_sgst.toFixed(2));
        $('input[name="total_igst_amt"]').val(total_igst.toFixed(2));
        $('input[name="total_net_value"]').val(total_net.toFixed(2));
        
    }
    
    $('.save_detail').on('click', function() {
      $('input[name="sale_invoice_detail"]').val(JSON.stringify(hot.getData()));   
    });
    
    function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) 
    {
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