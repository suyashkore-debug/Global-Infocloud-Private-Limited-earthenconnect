<script>
    function removeCommas(str) {
		"use strict";
		return(str.replace(/,/g,''));
	}
    
    $("#act_name").focus();
	
	$(function(){
		"use strict";
		validate_purorder_form();
		function validate_purorder_form(selector) {
			selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;
			appValidateForm($(selector), {
				DamageDate: {
					remote: {
						url: site_url + "admin/misc/checkdamage_val",
						type: 'post',
						data: {
							order_date: function() {
								return $('input[name="DamageDate"]').val();
							},
							DamageID: function() {
								return $('input[name="DamageNo"]').val();
							}
						}
					}
				},
				DamageDate:'required',
				DamageNo: 'required',
				AccountID: 'required',
			});
		}
	});
	
	function numberWithCommas(x) {
		"use strict";
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
	<?php if(!empty($damage_entry_detail)){ ?>
		var dataObject = <?php echo html_entity_decode($damage_entry_detail); ?>;
		<?php }else{ ?>
		var dataObject = []; 
	<?php }?>
	
    var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
		data: dataObject,
		columns: [
        { 
            data: 'id',
            renderer: customDropdownRenderer,
            editor: "chosen",
            width: 150,
            chosenOptions: {
				data:  <?php echo json_encode($item_code); ?>
			}
		},
        { 
            data: 'convert_to',
            renderer: customDropdownRenderer,
            editor: "chosen",
            width: 150,
            chosenOptions: {
				data:  <?php echo json_encode($item_code); ?>
			}
		},
        {
			data: 'Unit',
			type: 'text',
			width: 50,
			readOnly: true
		},
        {
			data: 'weight',
			type: 'text',
			width: 50,
			readOnly: true
		},
        {
			data: 'PackQty',
			type: 'numeric',
			width: 50,
			readOnly: true
		},
        {
			data: 'OrderQty',
			type: 'numeric',
			width: 50,
			validator: function(value, callback) {
				var row = this.row;
				var itemId = hot.getDataAtCell(row, 0); // Get Item Name
				if (itemId && itemId !== '' && (!value || value <= 0)) {
					callback(false); // Validation failed
					} else {
					callback(true); // Validation passed
				}
			}
		},
        {
			data: 'ConvertQty',
			type: 'numeric',
			width: 60,
			validator: function(value, callback) {
				var row = this.row;
				var convertTo = hot.getDataAtCell(row, 1); // Get Convert To
				if (convertTo && convertTo !== '' && (!value || value <= 0)) {
					callback(false); // Validation failed
					} else {
					callback(true); // Validation passed
				}
			}
		},
		{
			data: 'BasicRate',
			type: 'numeric',
			numericFormat: {
				pattern: '0,0'
			},
			width: 60,
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
		columnHeaderHeight: 40,
		minRows: 10,
		maxRows: 70,
		rowHeaders: true,	   
		colHeaders: [
        '<?php echo _l('Item Name'); ?>',
        '<?php echo _l('Convert To'); ?>',
        '<?php echo _l('Unit'); ?>',
        '<?php echo _l('Weight (Kg)'); ?>',
        '<?php echo _l('Pack Qty'); ?>',
        '<?php echo _l('Dmg Qty'); ?>',
        '<?php echo _l('Convert Qty'); ?>',
        '<?php echo _l('Basic Rate'); ?>',
        '<?php echo _l('CGST%'); ?>',
        '<?php echo _l('CGST Amt'); ?>',
        '<?php echo _l('SGST%'); ?>',
        '<?php echo _l('SGST Amt'); ?>',
        '<?php echo _l('IGST%'); ?>',
        '<?php echo _l('IGST Amt'); ?>',
        '<?php echo _l('Dmg Amt'); ?>',
		],
		columnSorting: {
			indicator: true
		},
		autoColumnSize: {
			samplingRatio: 23
		},
		mergeCells: true,
		contextMenu: true,
		manualRowMove: true,
		manualColumnMove: true,
		multiColumnSorting: {
			indicator: true
		},
		filters: true,
		manualRowResize: true,
		manualColumnResize: true,
		afterValidate: function(isValid, value, row, prop, source) {
			if (!isValid) {
				// Show validation error message
				if (prop === 'OrderQty') {
					alert('Dmg Qty is mandatory and must be greater than 0 when Item Name is selected.');
					} else if (prop === 'ConvertQty') {
					alert('Convert Qty is mandatory and must be greater than 0 when Convert To is selected.');
				}
			}
		}
	};
	
	var hot = new Handsontable(hotElement, hotSettings);
	hot.addHook('afterChange', function(changes, src) {
		if(changes !== null){
			changes.forEach(([row, prop, oldValue, newValue]) => {
				var count = 1; 
				vendor_id = $("#act_name").val();
				
				if(prop == 'id'){
					$.post(admin_url + 'damage_entry/ItemDetails/'+newValue).done(function(response){
						response = JSON.parse(response);
						hot.setDataAtCell(row,2, response.unit);
						if(response.outst_supply_in == 'CS'){
							var PackQty = response.case_qty;
							}else{
							var PackQty = response.crate_qty;
						}
						
						hot.setDataAtCell(row,3, response.weight);
						hot.setDataAtCell(row,4, PackQty);
						hot.setDataAtCell(row,7, response.assigned_rate); // Updated index for BasicRate
						var CGST = response.taxrate / 2;
						hot.setDataAtCell(row,8, CGST); // Updated index for cgst
						hot.setDataAtCell(row,10, CGST); // Updated index for sgst
						hot.setDataAtCell(row,12, 0.00); // Updated index for igst
						if(response.SubGrpID1 == '17' || response.SubGrpID1 == '18'){
							hot.setDataAtCell(row,1, 'GFFG0292');
						}
						count++; 
					});
					}else if(prop == 'OrderQty'){
					if(newValue !== '' || newValue !== null){
						var OrderAmt = newValue*hot.getDataAtCell(row,7); // Updated index for BasicRate
						var CGST = hot.getDataAtCell(row,8); // Updated index for cgst
						let GsmAmt = (CGST*OrderAmt)/100;
						hot.setDataAtCell(row,9, parseFloat(GsmAmt).toFixed(2)); // Updated index for cgstamt
						hot.setDataAtCell(row,11, parseFloat(GsmAmt).toFixed(2)); // Updated index for sgstamt
						hot.setDataAtCell(row,13, 0.00); // Updated index for igstamt
						let NetAmt = parseFloat(OrderAmt) + parseFloat(GsmAmt) + parseFloat(GsmAmt); 
						hot.setDataAtCell(row,14, parseFloat(NetAmt).toFixed(2)); // Updated index for NetChallanAmt
						
						var ConQty = newValue*hot.getDataAtCell(row,3);
						hot.setDataAtCell(row,6, ConQty); 
						}else{
						hot.setDataAtCell(row,9, 0); // Updated index for cgstamt
						hot.setDataAtCell(row,11, 0); // Updated index for sgstamt
						hot.setDataAtCell(row,14, 0); // Updated index for NetChallanAmt
						hot.setDataAtCell(row,6, 0); 
						
						
					}
					}else if(prop == 'BasicRate'){
					if(newValue !== '' || newValue !== null){
						var OrderAmt = newValue*hot.getDataAtCell(row,5); // OrderQty index
						var CGST = hot.getDataAtCell(row,8); // Updated index for cgst
						let GsmAmt = (CGST*OrderAmt)/100;
						hot.setDataAtCell(row,9, parseFloat(GsmAmt).toFixed(2)); // Updated index for cgstamt
						hot.setDataAtCell(row,11, parseFloat(GsmAmt).toFixed(2)); // Updated index for sgstamt
						hot.setDataAtCell(row,13, 0.00); // Updated index for igstamt
						let NetAmt = parseFloat(OrderAmt) + parseFloat(GsmAmt) + parseFloat(GsmAmt); 
						hot.setDataAtCell(row,14, parseFloat(NetAmt).toFixed(2)); // Updated index for NetChallanAmt
						}else{
						hot.setDataAtCell(row,9, 0); // Updated index for cgstamt
						hot.setDataAtCell(row,11, 0); // Updated index for sgstamt
						hot.setDataAtCell(row,14, 0); // Updated index for NetChallanAmt
					}
					}else if(prop == 'ConvertQty'){
					//  ConvertQty  calculations
					}if(prop == 'NetChallanAmt'){ 
					var TotalCrates = 0.00;
					var TotalCases = 0;
					var SubTotal = 0.00;
					var TotalCGSTAmt = 0;
					var TotalSGSTAmt = 0;
					var TotalIGSTAmt = 0;
					var TotalNetAmt =0;
					
					for (var row_index = 0; row_index <= 40; row_index++) {
						var OrderQty = 0;
						var BasicRate = 0;
						var CSCR = "";
						var PackQty = 1;
						let OrderAmt = 0;
						if(parseFloat(hot.getDataAtCell(row_index, 4)) > 0){ // PackQty index
							PackQty = (parseFloat(hot.getDataAtCell(row_index, 4)));
						}
						if(parseFloat(hot.getDataAtCell(row_index, 5)) > 0){ // OrderQty index
							OrderQty = (parseFloat(hot.getDataAtCell(row_index, 5)));
						}
						if(parseFloat(hot.getDataAtCell(row_index, 7)) > 0){ // BasicRate index
							BasicRate = (parseFloat(hot.getDataAtCell(row_index, 7)));
						}
						if(parseFloat(hot.getDataAtCell(row_index, 9)) > 0){ // cgstamt index
							TotalCGSTAmt += (parseFloat(hot.getDataAtCell(row_index, 9)));
						}
						if(parseFloat(hot.getDataAtCell(row_index, 11)) > 0){ // sgstamt index
							TotalSGSTAmt += (parseFloat(hot.getDataAtCell(row_index, 11)));
						}
						
						if(parseFloat(hot.getDataAtCell(row_index, 14)) > 0){ // NetChallanAmt index
							TotalNetAmt += (parseFloat(hot.getDataAtCell(row_index, 14)));
						}
						OrderAmt = parseFloat(OrderQty) * parseFloat(BasicRate);
						SubTotal += OrderAmt;
						// if(hot.getDataAtCell(row_index, 3) == "CR"){ // SuppliedIn index
							// TotalCrates += parseFloat(OrderQty) / parseFloat(PackQty);
							// }else{
							// TotalCases += parseFloat(OrderQty) / parseFloat(PackQty);
						// }
					}
					// $('input[name="TotalCases"]').val(TotalCases.toFixed(2));
					// $('input[name="TotalCrates"]').val(TotalCrates.toFixed(2));
					$('input[name="cgst_amt"]').val(TotalCGSTAmt.toFixed(2));
					$('input[name="sgst_amt"]').val(TotalSGSTAmt.toFixed(2));
					$('input[name="igst_amt"]').val(TotalIGSTAmt.toFixed(2));
					$('input[name="basic_amt"]').val(SubTotal.toFixed(2));
					$('input[name="dmg_amt"]').val(TotalNetAmt.toFixed(2));
				}
			});
		}
	});
	
	// Add validation before saving
	$('.save_detail').on('click', function() {
		var isValid = true;
		var errorMessages = [];
		
		// Check all rows for validation
		for (var row = 0; row < hot.countRows(); row++) {
			var itemId = hot.getDataAtCell(row, 0); // Item Name
			var orderQty = hot.getDataAtCell(row, 5); // Dmg Qty
			var convertTo = hot.getDataAtCell(row, 1); // Convert To
			var convertQty = hot.getDataAtCell(row, 6); // Convert Qty
			
			// Validation 1: If Item Name is selected, Dmg Qty must be > 0
			if (itemId && itemId !== '' && (!orderQty || orderQty <= 0)) {
				isValid = false;
				errorMessages.push('Row ' + (row + 1) + ': Dmg Qty is mandatory and must be greater than 0 when Item Name is selected.');
			}
			
			// Validation 2: If Convert To is selected, Convert Qty must be > 0
			if (convertTo && convertTo !== '' && (!convertQty || convertQty <= 0)) {
				isValid = false;
				errorMessages.push('Row ' + (row + 1) + ': Convert Qty is mandatory and must be greater than 0 when Convert To is selected.');
			}
		}
		
		if (!isValid) {
			alert('Please fix the following points:\n\n' + errorMessages.join('\n'));
			return false;
		}
		
		// If validation passes, save the data
		$('input[name="pur_order_detail"]').val(JSON.stringify(hot.getData()));   
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
				value.push(optionsList[index].label);
			}
		}
		value = value.join(", ");
		
		Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
		return td;
	}
</script>